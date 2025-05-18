<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Contract;
use App\Models\Person;
use App\Models\Transaction;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Commission::with(['contract', 'broker', 'transaction']);
        
        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter by broker if provided
        if ($request->has('broker_id') && $request->broker_id) {
            $query->where('broker_id', $request->broker_id);
        }
        
        $commissions = $query->orderBy('created_at', 'desc')->paginate(10);
        
        $brokers = Person::where('type', 'broker')->where('is_active', true)->orderBy('name')->get();
        
        return view('admin.commissions.index', compact('commissions', 'brokers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contracts = Contract::where('status', 'active')->orderBy('created_at', 'desc')->get();
        $brokers = Person::where('type', 'broker')->where('is_active', true)->orderBy('name')->get();
        $bankAccounts = BankAccount::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.commissions.create', compact('contracts', 'brokers', 'bankAccounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'broker_id' => 'required|exists:people,id',
            'amount' => 'required|numeric|min:0',
            'percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:pending,paid',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'bank_account_id' => 'required_if:status,paid|nullable|exists:bank_accounts,id',
        ]);

        $commission = new Commission($request->except('transaction_id'));
        $commission->save();

        // Create transaction if status is paid
        if ($request->status === 'paid' && $request->filled('bank_account_id')) {
            $transaction = new Transaction([
                'description' => 'Commission payment for ' . $commission->broker->name,
                'amount' => $commission->amount,
                'type' => 'expense',
                'date' => $request->payment_date ?? now(),
                'status' => 'paid',
                'bank_account_id' => $request->bank_account_id,
                'category' => 'commission',
                'notes' => $request->notes,
            ]);
            $transaction->save();
            
            // Update bank account balance
            $bankAccount = BankAccount::find($request->bank_account_id);
            $bankAccount->balance -= $commission->amount;
            $bankAccount->save();
            
            // Link transaction to commission
            $commission->transaction_id = $transaction->id;
            $commission->save();
        }

        return redirect()->route('admin.commissions.index')
            ->with('success', 'Comissão criada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Commission $commission)
    {
        $commission->load(['contract', 'broker', 'transaction']);
        return view('admin.commissions.show', compact('commission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Commission $commission)
    {
        $contracts = Contract::where('status', 'active')->orderBy('created_at', 'desc')->get();
        $brokers = Person::where('type', 'broker')->where('is_active', true)->orderBy('name')->get();
        $bankAccounts = BankAccount::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.commissions.edit', compact('commission', 'contracts', 'brokers', 'bankAccounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Commission $commission)
    {
        $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'broker_id' => 'required|exists:people,id',
            'amount' => 'required|numeric|min:0',
            'percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:pending,paid',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'bank_account_id' => 'required_if:status,paid|nullable|exists:bank_accounts,id',
        ]);

        $oldStatus = $commission->status;
        $oldAmount = $commission->amount;
        
        $commission->fill($request->except('transaction_id'));
        
        // Handle transaction if status changes from pending to paid
        if ($oldStatus === 'pending' && $request->status === 'paid' && $request->filled('bank_account_id')) {
            // Create new transaction
            $transaction = new Transaction([
                'description' => 'Commission payment for ' . $commission->broker->name,
                'amount' => $commission->amount,
                'type' => 'expense',
                'date' => $request->payment_date ?? now(),
                'status' => 'paid',
                'bank_account_id' => $request->bank_account_id,
                'category' => 'commission',
                'notes' => $request->notes,
            ]);
            $transaction->save();
            
            // Update bank account balance
            $bankAccount = BankAccount::find($request->bank_account_id);
            $bankAccount->balance -= $commission->amount;
            $bankAccount->save();
            
            // Link transaction to commission
            $commission->transaction_id = $transaction->id;
        }
        // Handle transaction if status remains paid but amount changes
        elseif ($oldStatus === 'paid' && $request->status === 'paid' && $commission->transaction_id) {
            $transaction = Transaction::find($commission->transaction_id);
            
            if ($transaction) {
                // Update bank account balance
                $bankAccount = BankAccount::find($transaction->bank_account_id);
                $bankAccount->balance += $oldAmount; // Revert old amount
                $bankAccount->balance -= $commission->amount; // Apply new amount
                $bankAccount->save();
                
                // Update transaction
                $transaction->update([
                    'amount' => $commission->amount,
                    'date' => $request->payment_date ?? $transaction->date,
                    'bank_account_id' => $request->bank_account_id,
                    'notes' => $request->notes,
                ]);
            }
        }
        // Handle transaction if status changes from paid to pending
        elseif ($oldStatus === 'paid' && $request->status === 'pending' && $commission->transaction_id) {
            $transaction = Transaction::find($commission->transaction_id);
            
            if ($transaction) {
                // Update bank account balance
                $bankAccount = BankAccount::find($transaction->bank_account_id);
                $bankAccount->balance += $oldAmount; // Revert amount
                $bankAccount->save();
                
                // Delete transaction
                $transaction->delete();
                $commission->transaction_id = null;
            }
        }
        
        $commission->save();

        return redirect()->route('admin.commissions.index')
            ->with('success', 'Comissão atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commission $commission)
    {
        // If commission has a transaction, delete it and update bank balance
        if ($commission->transaction_id) {
            $transaction = Transaction::find($commission->transaction_id);
            
            if ($transaction) {
                // Update bank account balance
                $bankAccount = BankAccount::find($transaction->bank_account_id);
                $bankAccount->balance += $commission->amount; // Revert amount
                $bankAccount->save();
                
                // Delete transaction
                $transaction->delete();
            }
        }
        
        $commission->delete();

        return redirect()->route('admin.commissions.index')
            ->with('success', 'Comissão deletada com sucesso.');
    }
} 