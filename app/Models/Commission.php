<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'broker_id',
        'contract_id',
        'transaction_id',
        'amount',
        'rate',
        'date',
        'status',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'rate' => 'decimal:2',
        'date' => 'date',
        'payment_date' => 'date',
    ];

    public function broker()
    {
        return $this->belongsTo(Person::class, 'broker_id');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
