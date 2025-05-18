<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bank_account_id',
        'type',
        'description',
        'amount',
        'date',
        'due_date',
        'payment_date',
        'category',
        'financial_category',
        'status',
        'contract_id',
        'property_id',
        'person_id',
        'document_number',
        'attachment',
        'notes',
        'installment_number',
        'total_installments',
        'recurring_id',
        'recurrence_info',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'due_date' => 'date',
        'payment_date' => 'date',
        'recurrence_info' => 'array',
    ];

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function commission()
    {
        return $this->hasOne(Commission::class);
    }

    public function getFormattedAmountAttribute()
    {
        $prefix = $this->type === 'expense' ? '-' : '';
        return $prefix . 'R$ ' . number_format($this->amount, 2, ',', '.');
    }
    
    /**
     * Scope a query to only include accounts receivable transactions
     */
    public function scopeReceivable($query)
    {
        return $query->where('type', 'income')->whereNotNull('due_date');
    }
    
    /**
     * Scope a query to only include accounts payable transactions
     */
    public function scopePayable($query)
    {
        return $query->where('type', 'expense')->whereNotNull('due_date');
    }
    
    /**
     * Scope a query to only include overdue transactions
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                     ->where('status', 'pending');
    }
    
    /**
     * Scope a query to only include transactions due in the next X days
     */
    public function scopeDueInDays($query, $days)
    {
        return $query->where('due_date', '>=', now())
                     ->where('due_date', '<=', now()->addDays($days))
                     ->where('status', 'pending');
    }
}
