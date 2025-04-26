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
        'category',
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

    /**
     * Get the ticket types associated with this event
     */
    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class, 'mctlists_id');
    }

    /**
     * Get active ticket types that are not sold out
     */
    public function availableTicketTypes()
    {
        return $this->ticketTypes()
                    ->where('is_active', true)
                    ->where(function($query) {
                        $query->whereNull('capacity')
                              ->orWhereRaw('sold < capacity');
                    });
    }

    // public function scopeFilter($query, array $filters){
    //     if($filters['search'] ?? false){
    //         $query->where('name', 'like',  '%' . $filters['search'] . '%');
    //     }
    // }



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
