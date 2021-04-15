<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletType extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_type',
        'interest_rate',
        'minimum_balance'
    ];


    public function wallet()
    {
        return $this->hasMany(wallet::class);
    }
}
