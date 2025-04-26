<?php

namespace Database\Seeders;

use App\Models\mctlists;
use App\Models\TicketType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all events
        $events = mctlists::all();

        foreach ($events as $event) {
            // Skip 2 events at random (so they have no ticket types)
            if ($event->id % 5 === 0) {
                continue;
            }

            // Create 3-5 ticket types for each event
            $ticketCount = rand(3, 5);

            // Make sure each event has a Regular ticket type
            TicketType::factory()->regular()->create([
                'mctlists_id' => $event->id,
                'sales_start' => now()->subDays(rand(10, 30)),
                'sales_end' => now()->addDays(rand(60, 120)),
            ]);

            // Standard event should have some combination of these ticket types
            $standardTicketTypes = [
                'vip', 'table:2', 'table:4', 'early_bird'
            ];

            // VIP-level concerts and premium events should have higher-tier options
            $premiumTicketTypes = [
                'vvip', 'table:6', 'table:8', 'table:10', 'backstage'
            ];

            // Determine if this is a premium event based on name
            $isPremium = stripos($event->name, 'VIP') !== false ||
                        stripos($event->name, 'Premium') !== false ||
                        stripos($event->name, 'Concert') !== false;

            // Choose the appropriate pool of ticket types
            $ticketPool = $isPremium
                ? array_merge($standardTicketTypes, $premiumTicketTypes)
                : $standardTicketTypes;

            // Select a subset of these ticket types
            $selectedTypes = array_rand(array_flip($ticketPool), min(count($ticketPool), $ticketCount - 1));
            if (!is_array($selectedTypes)) {
                $selectedTypes = [$selectedTypes];
            }

            foreach ($selectedTypes as $type) {
                if (strpos($type, 'table:') === 0) {
                    $size = (int) substr($type, 6);
                    TicketType::factory()->table($size)->create([
                        'mctlists_id' => $event->id,
                        'sales_start' => now()->subDays(rand(10, 30)),
                        'sales_end' => now()->addDays(rand(60, 120)),
                    ]);
                } elseif ($type === 'vip') {
                    TicketType::factory()->vip()->create([
                        'mctlists_id' => $event->id,
                        'sales_start' => now()->subDays(rand(10, 30)),
                        'sales_end' => now()->addDays(rand(60, 120)),
                    ]);
                } elseif ($type === 'vvip') {
                    TicketType::factory()->vvip()->create([
                        'mctlists_id' => $event->id,
                        'sales_start' => now()->subDays(rand(10, 30)),
                        'sales_end' => now()->addDays(rand(60, 120)),
                    ]);
                } elseif ($type === 'early_bird') {
                    TicketType::factory()->create([
                        'mctlists_id' => $event->id,
                        'name' => 'Early Bird',
                        'price' => 3500,
                        'description' => 'Early access at a discounted rate',
                        'capacity' => 100,
                        'sales_start' => now()->subDays(rand(40, 60)),
                        'sales_end' => now()->subDays(rand(5, 20)), // Early bird ends sooner
                    ]);
                } elseif ($type === 'backstage') {
                    TicketType::factory()->create([
                        'mctlists_id' => $event->id,
                        'name' => 'Backstage Pass',
                        'price' => 35000,
                        'description' => 'Meet the performers backstage after the show',
                        'capacity' => 10,
                        'sales_start' => now()->subDays(rand(10, 30)),
                        'sales_end' => now()->addDays(rand(60, 120)),
                    ]);
                } else {
                    // Create a random ticket type
                    TicketType::factory()->create([
                        'mctlists_id' => $event->id,
                        'sales_start' => now()->subDays(rand(10, 30)),
                        'sales_end' => now()->addDays(rand(60, 120)),
                    ]);
                }
            }
        }
    }
}
