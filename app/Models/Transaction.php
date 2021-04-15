<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount', 
        'transaction_type', 
        'debit_wallet_id',
        'credit_wallet_id', 
    ];
}
