<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupLga extends Model
{
    use HasFactory;

    protected $table = 'grouped_lgas';

    protected $fillable = [
        'states', 
        'lgas'
    ];

    protected $casts = [
        'lgas' => 'array',
    ];

    // protected $appends = [
    //     'keyValue'
    // ];

    // protected $hidden = [
    //     'states',
    //     'lgas'
    // ];

    // public function getkeyValueAttribute() {
    //     return [$this->states => $this->lgas];
    // }
    
    // public function valuePair ()
    // {
    //     return [$this->states => $this->lgas];
    // }
}
