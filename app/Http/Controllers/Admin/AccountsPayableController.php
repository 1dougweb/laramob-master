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

class AccountsPayableController extends Controller
{
    /**
     * Display a listing of accounts payable.
     */
    public function index(Request $request)
    {
        $query = Transaction::payable()->with(['bankAccount', 'person', 'contract']);
        
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
        
        // Filter by supplier (person)
        if ($request->has('person_id') && $request->person_id) {
            $query->where('person_id', $request->person_id);
        }
        
        // Get summary statistics
        $totalPending = Transaction::payable()->where('status', 'pending')->sum('amount');
        $totalOverdue = Transaction::payable()->overdue()->sum('amount');
        $totalPaid = Transaction::payable()->where('status', 'paid')->sum('amount');
        
        $payables = $query->orderBy('due_date', 'asc')->paginate(15);
        
        return view('admin.accounts-payable.index', compact(
            'payables', 
            'totalPending', 
            'totalOverdue', 
            'totalPaid'
        ));
    }

    /**
     * Show the form for creating a new account payable.
     */
    public function create()
    {
        $bankAccounts = BankAccount::where('is_active', true)->get();
        $people = Person::orderBy('name')->get();
        $contracts = Contract::with('property')->get();
        
        return view('admin.accounts-payable.create', compact('bankAccounts', 'people', 'contracts'));
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
                'type' => 'expense',
                'status' => $request->status ?? 'pending',
                'financial_category' => 'accounts_payable',
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
                $bankAccount->balance -= $request->amount;
                $bankAccount->save();
            }
            
            DB::commit();
            
            return redirect()->route('admin.accounts-payable.index')
                ->with('success', 'Conta a pagar criada com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao criar conta a pagar: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $payable = Transaction::payable()->with(['bankAccount', 'person', 'contract', 'property'])->findOrFail($id);
        
        return view('admin.accounts-payable.show', compact('payable'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $payable = Transaction::payable()->findOrFail($id);
        $bankAccounts = BankAccount::where('is_active', true)->get();
        $people = Person::orderBy('name')->get();
        $contracts = Contract::with('property')->get();
        
        return view('admin.accounts-payable.edit', compact('payable', 'bankAccounts', 'people', 'contracts'));
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
        
        $payable = Transaction::payable()->findOrFail($id);
        
        DB::beginTransaction();
        try {
            $oldStatus = $payable->status;
            $oldAmount = $payable->amount;
            $oldBankAccountId = $payable->bank_account_id;
            
            // Handle file upload if present
            if ($request->hasFile('attachment')) {
                // Delete old attachment if exists
                if ($payable->attachment) {
                    Storage::disk('public')->delete($payable->attachment);
                }
                $attachmentPath = $request->file('attachment')->store('transactions', 'public');
                $request->merge(['attachment' => $attachmentPath]);
            }
            
            // Revert old transaction effect on bank balance if it was paid
            if ($oldStatus === 'paid') {
                $oldBankAccount = BankAccount::find($oldBankAccountId);
                if ($oldBankAccount) {
                    $oldBankAccount->balance += $oldAmount;
                    $oldBankAccount->save();
                }
            }
            
            // Apply new transaction effect on bank balance if it's paid
            if ($request->status === 'paid') {
                $bankAccount = BankAccount::find($request->bank_account_id);
                $bankAccount->balance -= $request->amount;
                $bankAccount->save();
                
                // Set payment_date if not provided
                if (!$request->filled('payment_date')) {
                    $request->merge(['payment_date' => now()]);
                }
            } else {
                // Clear payment_date if status is not paid
                $request->merge(['payment_date' => null]);
            }
            
            $payable->update(array_merge($request->except('_token', '_method'), [
                'financial_category' => 'accounts_payable'
            ]));
            
            DB::commit();
            
            return redirect()->route('admin.accounts-payable.index')
                ->with('success', 'Conta a pagar atualizada com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao atualizar conta a pagar: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $payable = Transaction::payable()->findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Revert transaction effect on bank balance if it was paid
            if ($payable->status === 'paid') {
                $bankAccount = BankAccount::find($payable->bank_account_id);
                if ($bankAccount) {
                    $bankAccount->balance += $payable->amount;
                    $bankAccount->save();
                }
            }
            
            // Delete attachment if exists
            if ($payable->attachment) {
                Storage::disk('public')->delete($payable->attachment);
            }
            
            $payable->delete();
            
            DB::commit();
            
            return redirect()->route('admin.accounts-payable.index')
                ->with('success', 'Conta a pagar excluída com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao excluir conta a pagar: ' . $e->getMessage());
        }
    }
    
    /**
     * Register payment for a payable.
     */
    public function registerPayment(Request $request, $id)
    {
        $payable = Transaction::payable()->findOrFail($id);
        
        $request->validate([
            'payment_date' => 'required|date',
            'amount_paid' => 'required|numeric|min:0.01',
        ]);
        
        DB::beginTransaction();
        try {
            // Update transaction
            $payable->status = 'paid';
            $payable->payment_date = $request->payment_date;
            
            // Handle amount difference if needed
            if ($payable->amount != $request->amount_paid) {
                // Create adjustment transaction if necessary
                if ($request->amount_paid < $payable->amount) {
                    $difference = $payable->amount - $request->amount_paid;
                    // Create income adjustment for the difference
                    Transaction::create([
                        'bank_account_id' => $payable->bank_account_id,
                        'type' => 'income',
                        'description' => 'Desconto em conta a pagar #' . $payable->id,
                        'amount' => $difference,
                        'date' => $request->payment_date,
                        'due_date' => $request->payment_date,
                        'payment_date' => $request->payment_date,
                        'category' => 'other',
                        'financial_category' => 'adjustment',
                        'status' => 'paid',
                        'person_id' => $payable->person_id,
                        'notes' => 'Desconto automático gerado para a conta a pagar #' . $payable->id,
                    ]);
                } else {
                    $difference = $request->amount_paid - $payable->amount;
                    // Create additional expense for the excess
                    Transaction::create([
                        'bank_account_id' => $payable->bank_account_id,
                        'type' => 'expense',
                        'description' => 'Excedente em pagamento da conta a pagar #' . $payable->id,
                        'amount' => $difference,
                        'date' => $request->payment_date,
                        'due_date' => $request->payment_date,
                        'payment_date' => $request->payment_date,
                        'category' => 'other',
                        'financial_category' => 'adjustment',
                        'status' => 'paid',
                        'person_id' => $payable->person_id,
                        'notes' => 'Excedente automático gerado para a conta a pagar #' . $payable->id,
                    ]);
                }
            }
            
            // Update bank account balance
            $bankAccount = BankAccount::find($payable->bank_account_id);
            if ($bankAccount) {
                $bankAccount->balance -= $request->amount_paid;
                $bankAccount->save();
            }
            
            $payable->save();
            
            DB::commit();
            
            return redirect()->route('admin.accounts-payable.index')
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