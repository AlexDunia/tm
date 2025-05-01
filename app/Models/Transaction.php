<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    // Specify the table associated with the model
    protected $table = 'newtransactions';
    protected $fillable = ['status', 'message', 'email', 'phone', 'amount', 'ticket_ids'];

    // Define casts for proper data handling in MySQL
    protected $casts = [
        'amount' => 'decimal:2',
        'ticket_ids' => 'array',  // This will handle JSON serialization/deserialization
    ];

    public function relateuser(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
