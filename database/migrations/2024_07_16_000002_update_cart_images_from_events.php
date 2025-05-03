<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\mctlists;

return new class extends Migration
{
    /**
     * Run the migrations to update images in existing cart items.
     */
    public function up(): void
    {
        // Get all cart items with event_id but missing images
        $cartItems = DB::table('carts')
            ->whereNotNull('event_id')
            ->where(function($query) {
                $query->whereNull('image')
                    ->orWhere('image', '')
                    ->orWhereNull('event_image')
                    ->orWhere('event_image', '');
            })
            ->get();

        foreach ($cartItems as $item) {
            // Find the associated event
            $event = mctlists::find($item->event_id);
            if ($event) {
                // Update cart item with event images
                DB::table('carts')
                    ->where('id', $item->id)
                    ->update([
                        'image' => !empty($item->image) ? $item->image : $event->image,
                        'event_image' => !empty($item->event_image) ? $item->event_image : $event->heroimage,
                    ]);
            }
        }

        // Update cart items where image is saved in cdescription field but image is empty
        DB::table('carts')
            ->whereNotNull('cdescription')
            ->where('cdescription', 'LIKE', 'http%')
            ->whereNull('image')
            ->update([
                'image' => DB::raw('cdescription'),
            ]);
    }

    /**
     * Reverse the migrations - no op since this is a one-time data update.
     */
    public function down(): void
    {
        // No rollback needed for a data update
    }
};
