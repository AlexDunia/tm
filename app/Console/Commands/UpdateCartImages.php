<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cart;
use App\Models\mctlists;
use Illuminate\Support\Facades\DB;

class UpdateCartImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:update-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update cart images from their associated events';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to update cart images...');

        // Get all cart items
        $cartItems = Cart::all();
        $updatedCount = 0;

        $this->info('Found ' . $cartItems->count() . ' cart items');

        foreach ($cartItems as $item) {
            $this->info("Processing cart item ID: {$item->id}, event_id: {$item->event_id}");

            // Get the event_id - if it's missing, try to find the event by name
            $eventId = $item->event_id;

            if (empty($eventId) && !empty($item->cname)) {
                $this->info("Trying to find event by name: {$item->cname}");
                // Try to find the event by name
                $event = mctlists::where('name', $item->cname)->first();
                if ($event) {
                    $eventId = $event->id;
                    // Update the event_id in the cart item
                    $item->event_id = $eventId;
                    $item->save();
                    $this->info("Found and updated event_id to {$eventId}");
                }
            }

            if ($eventId) {
                $event = mctlists::find($eventId);

                if ($event) {
                    $this->info("Found event: {$event->name}");
                    $this->info("Event images: {$event->image}, {$event->heroimage}");

                    $needsUpdate = false;

                    // Check if we need to update the image
                    if (empty($item->image) && !empty($event->image)) {
                        $item->image = $event->image;
                        $needsUpdate = true;
                        $this->info("Updating image to: {$event->image}");
                    }

                    // Check if we need to update the event_image
                    if (empty($item->event_image) && !empty($event->heroimage)) {
                        $item->event_image = $event->heroimage;
                        $needsUpdate = true;
                        $this->info("Updating event_image to: {$event->heroimage}");
                    }

                    // Check if we need to update cdescription (for backward compatibility)
                    if (empty($item->cdescription) && !empty($event->image)) {
                        $item->cdescription = $event->image;
                        $needsUpdate = true;
                        $this->info("Updating cdescription to: {$event->image}");
                    }

                    if ($needsUpdate) {
                        $item->save();
                        $updatedCount++;
                        $this->info("Updated cart item ID: {$item->id}");
                    } else {
                        $this->info("No updates needed for cart item ID: {$item->id}");
                    }
                } else {
                    $this->warn("Could not find event with ID: {$eventId}");
                }
            } else {
                $this->warn("No event ID for cart item: {$item->id}");
            }
        }

        $this->info("Updated {$updatedCount} cart items with images from their events");
        return 0;
    }
}
