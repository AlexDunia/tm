<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    use HasFactory;

    protected $fillable = [
        'mctlists_id',
        'name',
        'description',
        'price',
        'capacity',
        'sales_start',
        'sales_end',
        'is_active',
        'sold'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sales_start' => 'datetime',
        'sales_end' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the event that owns the ticket type
     */
    public function event()
    {
        return $this->belongsTo(mctlists::class, 'mctlists_id');
    }

    /**
     * Check if ticket is currently on sale
     */
    public function isOnSale()
    {
        $now = now();

        // If no sales dates are set, assume it's always on sale
        if (!$this->sales_start && !$this->sales_end) {
            return $this->is_active;
        }

        // Check if current time is within sales window
        if ($this->sales_start && $this->sales_end) {
            return $this->is_active &&
                   $now->greaterThanOrEqualTo($this->sales_start) &&
                   $now->lessThanOrEqualTo($this->sales_end);
        }

        // Only start date is set
        if ($this->sales_start && !$this->sales_end) {
            return $this->is_active && $now->greaterThanOrEqualTo($this->sales_start);
        }

        // Only end date is set
        if (!$this->sales_start && $this->sales_end) {
            return $this->is_active && $now->lessThanOrEqualTo($this->sales_end);
        }

        return false;
    }

    /**
     * Check if ticket is sold out
     */
    public function isSoldOut()
    {
        if ($this->capacity === null) {
            return false;
        }

        return $this->sold >= $this->capacity;
    }

    /**
     * Get the available quantity remaining
     */
    public function getAvailableQuantity()
    {
        if ($this->capacity === null) {
            return null; // Unlimited
        }

        return max(0, $this->capacity - $this->sold);
    }
}
