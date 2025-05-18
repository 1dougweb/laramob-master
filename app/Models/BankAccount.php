<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'bank_name',
        'account_number',
        'agency',
        'type',
        'initial_balance',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'initial_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getCurrentBalanceAttribute()
    {
        $incomes = $this->transactions()->where('type', 'income')->where('status', 'paid')->sum('amount');
        $expenses = $this->transactions()->where('type', 'expense')->where('status', 'paid')->sum('amount');
        
        return $this->initial_balance + $incomes - $expenses;
    }
}
