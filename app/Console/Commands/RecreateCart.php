<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cart;
use App\Models\mctlists;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RecreateCart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:recreate {user_id? : The ID of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recreate cart items with proper images for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');

        if (!$userId) {
            // Get the first user if no user_id is provided
            $user = User::first();
            if (!$user) {
                $this->error('No users found in the database');
                return 1;
            }
            $userId = $user->id;
        } else {
            $user = User::find($userId);
            if (!$user) {
                $this->error('User not found');
                return 1;
            }
        }

        $this->info("Creating test cart items for user ID: {$userId}");

        // Get some events to add to cart
        $events = mctlists::take(2)->get();

        if ($events->isEmpty()) {
            $this->error('No events found in the database');
            return 1;
        }

        // Clear existing cart first
        Cart::where('user_id', $userId)->delete();
        $this->info('Cleared existing cart items');

        foreach ($events as $event) {
            $this->info("Adding event to cart: {$event->name}");

            // Create cart item
            $cartItem = new Cart();
            $cartItem->user_id = $userId;
            $cartItem->cname = $event->name;
            $cartItem->eventname = 'VIP Ticket';
            $cartItem->cprice = $event->startingprice ?? 15000;
            $cartItem->cquantity = 2;
            $cartItem->ctotalprice = $cartItem->cprice * $cartItem->cquantity;
            $cartItem->clocation = $event->location ?? 'Lagos';
            $cartItem->event_id = $event->id;

            // Set the images
            $cartItem->image = $event->image;
            $cartItem->event_image = $event->heroimage;
            $cartItem->cdescription = $event->image;

            $cartItem->save();

            $this->info("Added cart item ID: {$cartItem->id}");
            $this->info("Image: {$cartItem->image}");
            $this->info("Event image: {$cartItem->event_image}");
        }

        $this->info('Cart recreated successfully with proper images');
        return 0;
    }
}
