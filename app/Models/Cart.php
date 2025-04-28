<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    /**
     * Ensure proper data type casting for cart items
     */
    protected $casts = [
        'cquantity' => 'integer',
        'cprice' => 'float',
        'ctotalprice' => 'float',
    ];

    public function relate(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
