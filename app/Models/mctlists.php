<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mctlists extends Model
{
    use HasFactory;
 // name description location date
    protected $fillable = [
        'name',
        'description',
        'location',
        'date',
        'image',
        'heroimage',
        'herolink'
    ];
}
