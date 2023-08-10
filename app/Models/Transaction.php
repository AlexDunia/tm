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
    protected $fillable = ['status', 'message', 'email', 'phone', 'amount'];

    public function relateuser(){
        return $this->belongsTo(User::class, 'user_id');
    }

}
