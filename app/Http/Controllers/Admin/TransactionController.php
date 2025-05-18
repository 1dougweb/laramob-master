<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\BankAccount;
use App\Models\Contract;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['bankAccount', 'contract']);
        
        // Filter by type if provided
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range if provided
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }
        
        $transactions = $query->orderBy('date', 'desc')->paginate(15);
        
        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bankAccounts = BankAccount::where('is_active', true)->orderBy('name')->get();
        $contracts = Contract::where('status', 'active')->orderBy('created_at', 'desc')->get();
        
        return view('admin.transactions.create', compact('bankAccounts', 'contracts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense',
            'date' => 'required|date',
            'status' => 'required|in:pending,paid,canceled',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'category' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        Transaction::create($request->all());

        // Update bank account balance if transaction is paid
        if ($request->status === 'paid') {
            $bankAccount = BankAccount::find($request->bank_account_id);
            
            if ($request->type === 'income') {
                $bankAccount->balance += $request->amount;
            } else {
                $bankAccount->balance -= $request->amount;
            }
            
            $bankAccount->save();
        }

        return redirect()->route('admin.transactions.index')
            ->with('success', 'Transaction created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['bankAccount', 'contract']);
        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $bankAccounts = BankAccount::where('is_active', true)->orderBy('name')->get();
        $contracts = Contract::where('status', 'active')->orderBy('created_at', 'desc')->get();
        
        return view('admin.transactions.edit', compact('transaction', 'bankAccounts', 'contracts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense',
            'date' => 'required|date',
            'status' => 'required|in:pending,paid,canceled',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'category' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        // Handle bank account balance changes
        $oldStatus = $transaction->status;
        $oldType = $transaction->type;
        $oldAmount = $transaction->amount;
        $oldBankAccountId = $transaction->bank_account_id;
        
        $newStatus = $request->status;
        $newType = $request->type;
        $newAmount = $request->amount;
        $newBankAccountId = $request->bank_account_id;
        
        // Revert old transaction effect on bank balance if it was paid
        if ($oldStatus === 'paid') {
            $oldBankAccount = BankAccount::find($oldBankAccountId);
            
            if ($oldType === 'income') {
                $oldBankAccount->balance -= $oldAmount;
            } else {
                $oldBankAccount->balance += $oldAmount;
            }
            
            $oldBankAccount->save();
        }
        
        // Apply new transaction effect on bank balance if it's paid
        if ($newStatus === 'paid') {
            $newBankAccount = BankAccount::find($newBankAccountId);
            
            if ($newType === 'income') {
                $newBankAccount->balance += $newAmount;
            } else {
                $newBankAccount->balance -= $newAmount;
            }
            
            $newBankAccount->save();
        }
        
        $transaction->update($request->all());

        return redirect()->route('admin.transactions.index')
            ->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        // Revert transaction effect on bank balance if it was paid
        if ($transaction->status === 'paid') {
            $bankAccount = BankAccount::find($transaction->bank_account_id);
            
            if ($transaction->type === 'income') {
                $bankAccount->balance -= $transaction->amount;
            } else {
                $bankAccount->balance += $transaction->amount;
            }
            
            $bankAccount->save();
        }
        
        $transaction->delete();

        return redirect()->route('admin.transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }
} 