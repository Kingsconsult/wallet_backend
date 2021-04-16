<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StateLga extends Model
{
    use HasFactory;

    protected $fillable = [
        'states', 
        'lgas'
    ];
}
