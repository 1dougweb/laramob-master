<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bankAccounts = BankAccount::orderBy('name')->paginate(10);
        return view('admin.bank-accounts.index', compact('bankAccounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.bank-accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'agency' => 'required|string|max:50',
            'account_type' => 'required|in:checking,savings',
            'balance' => 'required|numeric',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        BankAccount::create($request->all());

        return redirect()->route('admin.bank-accounts.index')
            ->with('success', 'Conta de banco criada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BankAccount $bankAccount)
    {
        // Load transactions if needed
        // $bankAccount->load('transactions');
        
        return view('admin.bank-accounts.show', compact('bankAccount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BankAccount $bankAccount)
    {
        return view('admin.bank-accounts.edit', compact('bankAccount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankAccount $bankAccount)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'agency' => 'required|string|max:50',
            'account_type' => 'required|in:checking,savings',
            'balance' => 'required|numeric',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $bankAccount->update($request->all());

        return redirect()->route('admin.bank-accounts.index')
            ->with('success', 'Conta de banco atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankAccount $bankAccount)
    {
        // Check if there are transactions associated with this account
        // if ($bankAccount->transactions()->count() > 0) {
        //     return redirect()->route('admin.bank-accounts.index')
        //         ->with('error', 'Cannot delete this bank account because it has transactions associated with it.');
        // }
        
        $bankAccount->delete();

        return redirect()->route('admin.bank-accounts.index')
            ->with('success', 'Conta de banco deletada com sucesso.');
    }
} 