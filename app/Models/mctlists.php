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
        'startingprice',
        'earlybirds',
        'tableforone',
        'tablefortwo',
        'tableforthree',
        'tableforfour',
        'tableforfive',
        'tableforsix',
        'tableforseven',
        'tableforeight',
        'image',
        'heroimage',
        'herolink'
    ];

    public function scopeFilter($query, array $filters){
        if($filters['search'] ?? false){
            $query->where('name', 'like',  '%' . $filters['search'] . '%');
        }
    }

//     public function scopeFilter($query, $filters)
// {
//     // Implement your filtering logic here based on the $filters parameter
//     // For example, if you want to filter by a 'name' column:
//     if ($filters['search']) {
//         $query->where('name', 'like', '%' . $filters['search'] . '%');
//     }
//     return $query;
// }
}
