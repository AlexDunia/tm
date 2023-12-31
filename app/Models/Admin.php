<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    // Your Admin model code here
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
       'firstname',
       'lastname',
       'email',
       'password',
       'profilepic',
       'is_admin',
   ];
}