<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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
     * Get ticket types for this event
     */
    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class, 'mctlists_id');
    }

    /**
     * Get active ticket types that are not sold out - optimized with eager loading
     */
    public function availableTicketTypes()
    {
        // Cache the query results for 10 minutes to improve performance
        $cacheKey = 'event_' . $this->id . '_available_tickets';

        return Cache::remember($cacheKey, 600, function () {
            return $this->ticketTypes()
                    ->where('is_active', true)
                    ->where(function($query) {
                        $query->whereNull('capacity')
                              ->orWhereRaw('sold < capacity');
                    })
                    ->orderBy('price')
                    ->get();
        });
    }

    /**
     * Optimized method to get all events with caching
     */
    public static function getAllCached($limit = null)
    {
        $cacheKey = 'all_events' . ($limit ? '_' . $limit : '');

        return Cache::remember($cacheKey, 600, function () use ($limit) {
            $query = static::latest();

            if ($limit) {
                return $query->take($limit)->get();
            }

            return $query->get();
        });
    }

    /**
     * Optimized scoped method to search by name
     */
    public function scopeSearch($query, $searchTerm)
    {
        if (!$searchTerm) {
            return $query;
        }

        return $query->where('name', 'like', '%' . $searchTerm . '%')
                     ->orWhere('description', 'like', '%' . $searchTerm . '%')
                     ->orWhere('location', 'like', '%' . $searchTerm . '%')
                     ->orWhere('category', 'like', '%' . $searchTerm . '%');
    }

    /**
     * Optimized scoped method to filter by category
     */
    public function scopeCategory($query, $category)
    {
        if (!$category || $category === 'all') {
            return $query;
        }

        return $query->where('category', $category);
    }

    /**
     * Clear the cache for this model
     */
    public function clearCache()
    {
        Cache::forget('event_' . $this->id . '_available_tickets');
        Cache::forget('all_events');
    }

    /**
     * Override the save method to clear cache on save
     */
    public function save(array $options = [])
    {
        $result = parent::save($options);
        $this->clearCache();
        return $result;
    }
}
