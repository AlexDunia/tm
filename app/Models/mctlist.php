<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mctlist extends Model
{
    use HasFactory;
    public function scopeFilter($query, array $filter){
        if($filter['search'] ?? false){
            $query->where('Name', 'like'.  '% ' . $filter['search'] . '%');
        }
    }
}
