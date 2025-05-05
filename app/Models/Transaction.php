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
    protected $fillable = [
        'status',
        'message',
        'email',
        'phone',
        'amount',
        'ticket_ids',
        'event_id',
        'reference'
    ];

    // Define casts for proper data handling in MySQL
    protected $casts = [
        'amount' => 'decimal:2',
        'ticket_ids' => 'array',  // This will handle JSON serialization/deserialization
    ];

    /**
     * Get the user that owns the transaction
     */
    public function relateuser(){
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the event associated with this transaction
     */
    public function event(){
        return $this->belongsTo(mctlists::class, 'event_id');
    }

    /**
     * Get event category from related event or attempt to find it
     *
     * @return string|null
     */
    public function getEventCategory()
    {
        // If event relationship is loaded and has category
        if ($this->event && $this->event->category) {
            return $this->event->category;
        }

        // Try to find event by name and get category
        if ($this->eventname) {
            $event = mctlists::where('name', $this->eventname)->first();
            return $event ? $event->category : null;
        }

        return null;
    }
}
