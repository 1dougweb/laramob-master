<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\BankAccount;
use App\Models\Person;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AccountsReceivableController extends Controller
{
    /**
     * Display a listing of accounts receivable.
     */
    public function index(Request $request)
    {
        $query = Transaction::receivable()->with(['bankAccount', 'person', 'contract']);
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter by due date range
        if ($request->has('date_from') && $request->date_from) {
            $query->where('due_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->where('due_date', '<=', $request->date_to);
        }
        
        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }
        
        // Filter by person
        if ($request->has('person_id') && $request->person_id) {
            $query->where('person_id', $request->person_id);
        }
        
        // Get summary statistics
        $totalPending = Transaction::receivable()->where('status', 'pending')->sum('amount');
        $totalOverdue = Transaction::receivable()->overdue()->sum('amount');
        $totalPaid = Transaction::receivable()->where('status', 'paid')->sum('amount');
        
        $receivables = $query->orderBy('due_date', 'asc')->paginate(15);
        
        return view('admin.accounts-receivable.index', compact(
            'receivables', 
            'totalPending', 
            'totalOverdue', 
            'totalPaid'
        ));
    }

    /**
     * Show the form for creating a new account receivable.
     */
    public function create()
    {
        $bankAccounts = BankAccount::where('is_active', true)->get();
        $people = Person::orderBy('name')->get();
        $contracts = Contract::with('property')->get();
        
        return view('admin.accounts-receivable.create', compact('bankAccounts', 'people', 'contracts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'due_date' => 'required|date',
            'category' => 'required|string',
            'person_id' => 'nullable|exists:people,id',
        ]);
        
        DB::beginTransaction();
        try {
            // Handle file upload if present
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('transactions', 'public');
            }
            
            // Create base transaction data
            $transactionData = array_merge($request->except('attachment'), [
                'type' => 'income',
                'status' => $request->status ?? 'pending',
                'financial_category' => 'accounts_receivable',
                'attachment' => $attachmentPath,
            ]);
            
            // Handle installments if needed
            if ($request->has('create_installments') && $request->create_installments && $request->total_installments > 1) {
                $this->createInstallments($transactionData, $request->total_installments);
            } else {
                Transaction::create($transactionData);
            }
            
            // Update bank account balance if transaction is paid
            if (($request->status ?? 'pending') === 'paid') {
                $bankAccount = BankAccount::find($request->bank_account_id);
                $bankAccount->balance += $request->amount;
                $bankAccount->save();
            }
            
            DB::commit();
            
            return redirect()->route('admin.accounts-receivable.index')
                ->with('success', 'Conta a receber criada com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao criar conta a receber: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $receivable = Transaction::receivable()->with(['bankAccount', 'person', 'contract', 'property'])->findOrFail($id);
        
        return view('admin.accounts-receivable.show', compact('receivable'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $receivable = Transaction::receivable()->findOrFail($id);
        $bankAccounts = BankAccount::where('is_active', true)->get();
        $people = Person::orderBy('name')->get();
        $contracts = Contract::with('property')->get();
        
        return view('admin.accounts-receivable.edit', compact('receivable', 'bankAccounts', 'people', 'contracts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'due_date' => 'required|date',
            'category' => 'required|string',
            'person_id' => 'nullable|exists:people,id',
        ]);
        
        $receivable = Transaction::receivable()->findOrFail($id);
        
        DB::beginTransaction();
        try {
            $oldStatus = $receivable->status;
            $oldAmount = $receivable->amount;
            $oldBankAccountId = $receivable->bank_account_id;
            
            // Handle file upload if present
            if ($request->hasFile('attachment')) {
                // Delete old attachment if exists
                if ($receivable->attachment) {
                    Storage::disk('public')->delete($receivable->attachment);
                }
                $attachmentPath = $request->file('attachment')->store('transactions', 'public');
                $request->merge(['attachment' => $attachmentPath]);
            }
            
            // Revert old transaction effect on bank balance if it was paid
            if ($oldStatus === 'paid') {
                $oldBankAccount = BankAccount::find($oldBankAccountId);
                if ($oldBankAccount) {
                    $oldBankAccount->balance -= $oldAmount;
                    $oldBankAccount->save();
                }
            }
            
            // Apply new transaction effect on bank balance if it's paid
            if ($request->status === 'paid') {
                $bankAccount = BankAccount::find($request->bank_account_id);
                $bankAccount->balance += $request->amount;
                $bankAccount->save();
                
                // Set payment_date if not provided
                if (!$request->filled('payment_date')) {
                    $request->merge(['payment_date' => now()]);
                }
            } else {
                // Clear payment_date if status is not paid
                $request->merge(['payment_date' => null]);
            }
            
            $receivable->update(array_merge($request->except('_token', '_method'), [
                'financial_category' => 'accounts_receivable'
            ]));
            
            DB::commit();
            
            return redirect()->route('admin.accounts-receivable.index')
                ->with('success', 'Conta a receber atualizada com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao atualizar conta a receber: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $receivable = Transaction::receivable()->findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Revert transaction effect on bank balance if it was paid
            if ($receivable->status === 'paid') {
                $bankAccount = BankAccount::find($receivable->bank_account_id);
                if ($bankAccount) {
                    $bankAccount->balance -= $receivable->amount;
                    $bankAccount->save();
                }
            }
            
            // Delete attachment if exists
            if ($receivable->attachment) {
                Storage::disk('public')->delete($receivable->attachment);
            }
            
            $receivable->delete();
            
            DB::commit();
            
            return redirect()->route('admin.accounts-receivable.index')
                ->with('success', 'Conta a receber excluída com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao excluir conta a receber: ' . $e->getMessage());
        }
    }
    
    /**
     * Register payment for a receivable.
     */
    public function registerPayment(Request $request, $id)
    {
        $receivable = Transaction::receivable()->findOrFail($id);
        
        $request->validate([
            'payment_date' => 'required|date',
            'amount_paid' => 'required|numeric|min:0.01',
        ]);
        
        DB::beginTransaction();
        try {
            // Update transaction
            $receivable->status = 'paid';
            $receivable->payment_date = $request->payment_date;
            
            // Handle amount difference if needed
            if ($receivable->amount != $request->amount_paid) {
                // Create adjustment transaction if necessary
                if ($request->amount_paid < $receivable->amount) {
                    $difference = $receivable->amount - $request->amount_paid;
                    // Create write-off transaction for the difference
                    Transaction::create([
                        'bank_account_id' => $receivable->bank_account_id,
                        'type' => 'expense',
                        'description' => 'Abatimento em conta a receber #' . $receivable->id,
                        'amount' => $difference,
                        'date' => $request->payment_date,
                        'due_date' => $request->payment_date,
                        'payment_date' => $request->payment_date,
                        'category' => 'other',
                        'financial_category' => 'adjustment',
                        'status' => 'paid',
                        'person_id' => $receivable->person_id,
                        'notes' => 'Abatimento automático gerado para a conta a receber #' . $receivable->id,
                    ]);
                } else {
                    $difference = $request->amount_paid - $receivable->amount;
                    // Create additional income transaction for the excess
                    Transaction::create([
                        'bank_account_id' => $receivable->bank_account_id,
                        'type' => 'income',
                        'description' => 'Excedente em pagamento da conta a receber #' . $receivable->id,
                        'amount' => $difference,
                        'date' => $request->payment_date,
                        'due_date' => $request->payment_date,
                        'payment_date' => $request->payment_date,
                        'category' => 'other',
                        'financial_category' => 'adjustment',
                        'status' => 'paid',
                        'person_id' => $receivable->person_id,
                        'notes' => 'Excedente automático gerado para a conta a receber #' . $receivable->id,
                    ]);
                }
            }
            
            // Update bank account balance
            $bankAccount = BankAccount::find($receivable->bank_account_id);
            if ($bankAccount) {
                $bankAccount->balance += $request->amount_paid;
                $bankAccount->save();
            }
            
            $receivable->save();
            
            DB::commit();
            
            return redirect()->route('admin.accounts-receivable.index')
                ->with('success', 'Pagamento registrado com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao registrar pagamento: ' . $e->getMessage());
        }
    }
    
    /**
     * Create installments from a transaction.
     */
    private function createInstallments(array $transactionData, int $totalInstallments)
    {
        $amount = $transactionData['amount'];
        $dueDate = new \DateTime($transactionData['due_date']);
        $recurringId = Str::uuid()->toString();
        
        // Calculate installment amount (rounded to 2 decimal places)
        $installmentAmount = round($amount / $totalInstallments, 2);
        
        // Calculate if there's any remainder due to rounding
        $remainder = $amount - ($installmentAmount * $totalInstallments);
        
        for ($i = 1; $i <= $totalInstallments; $i++) {
            // Calculate installment date (1 month apart)
            $installmentDate = clone $dueDate;
            $installmentDate->modify('+' . ($i - 1) . ' month');
            
            // Add remainder to the first installment if any
            $currentAmount = $installmentAmount;
            if ($i === 1 && $remainder > 0) {
                $currentAmount += $remainder;
            }
            
            Transaction::create(array_merge($transactionData, [
                'description' => $transactionData['description'] . ' - Parcela ' . $i . '/' . $totalInstallments,
                'amount' => $currentAmount,
                'due_date' => $installmentDate->format('Y-m-d'),
                'installment_number' => $i,
                'total_installments' => $totalInstallments,
                'recurring_id' => $recurringId,
            ]));
        }
    }
} 