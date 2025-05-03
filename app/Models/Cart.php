<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    /**
     * Ensure proper data type casting for cart items
     */
    protected $casts = [
        'cquantity' => 'integer',
        'cprice' => 'decimal:2',
        'ctotalprice' => 'decimal:2',
    ];

    protected $fillable = [
        'user_id', 'cname', 'cprice', 'cquantity', 'ctotalprice', 'eventname', 'clocation', 'cdescription', 'image', 'event_image', 'thumbnail', 'event_id'
    ];

    /**
     * Get the user that owns the cart item
     */
    public function relate(){
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the event associated with this cart item
     */
    public function event(){
        return $this->belongsTo(mctlists::class, 'event_id');
    }

    /**
     * Find all cart items for a specific user
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getUserCartItems($userId)
    {
        return self::where('user_id', $userId)->get();
    }

    /**
     * Find a specific cart item for a user
     *
     * @param int $userId
     * @param string $productName
     * @param string $eventName
     * @return Cart|null
     */
    public static function findUserCartItem($userId, $productName, $eventName)
    {
        return self::where('user_id', $userId)
            ->where('cname', $productName)
            ->where('eventname', $eventName)
            ->first();
    }

    /**
     * Get total cart value for a user
     *
     * @param int $userId
     * @return float
     */
    public static function getUserCartTotal($userId)
    {
        return self::where('user_id', $userId)->sum('ctotalprice');
    }

    /**
     * Get total number of items in a user's cart
     *
     * @param int $userId
     * @return int
     */
    public static function getUserCartCount($userId)
    {
        return self::where('user_id', $userId)->sum('cquantity');
    }
}
