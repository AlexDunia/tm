<?php

namespace Database\Factories;

use App\Models\mctlists;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketType>
 */
class TicketTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ticketNames = [
            'Regular Ticket',
            'Early Bird',
            'VIP Access',
            'VVIP Access',
            'Backstage Pass',
            'Table for 2',
            'Table for 4',
            'Table for 6',
            'Table for 8',
            'Table for 10',
            'Premium Seating',
            'Standard Entry',
            'Gold Package',
            'Platinum Package',
            'Student Ticket'
        ];

        $descriptions = [
            'Standard event access',
            'Early access at a discounted rate',
            'Premium seating with complimentary drinks',
            'Exclusive area with premium service and complimentary food and drinks',
            'Meet the performers backstage after the show',
            'Private table for two with bottle service',
            'Private table for four with bottle service',
            'Private table for six with bottle service',
            'Private table for eight with bottle service',
            'Premium table for ten with dedicated waitstaff and bottle service',
            'Front row seating with excellent view',
            'Basic entry to the event',
            'Enhanced experience with priority seating',
            'Ultimate luxury experience with all amenities included',
            'Discounted ticket for students (valid ID required)'
        ];

        $name = $this->faker->randomElement($ticketNames);
        $nameIndex = array_search($name, $ticketNames);
        $description = $descriptions[$nameIndex] ?? $this->faker->sentence();

        // Base price mapping for different ticket types
        $basePrices = [
            'Regular Ticket' => 5000,
            'Early Bird' => 3500,
            'VIP Access' => 15000,
            'VVIP Access' => 25000,
            'Backstage Pass' => 35000,
            'Table for 2' => 20000,
            'Table for 4' => 40000,
            'Table for 6' => 60000,
            'Table for 8' => 80000,
            'Table for 10' => 100000,
            'Premium Seating' => 12000,
            'Standard Entry' => 4000,
            'Gold Package' => 18000,
            'Platinum Package' => 30000,
            'Student Ticket' => 2500
        ];

        $basePrice = $basePrices[$name] ?? 5000;
        // Add some randomness to the price
        $price = $basePrice + ($this->faker->numberBetween(-500, 1000) - ($this->faker->numberBetween(-500, 1000) % 100));

        // Capacity mapping
        $capacities = [
            'Regular Ticket' => null, // Unlimited
            'Early Bird' => 100,
            'VIP Access' => 50,
            'VVIP Access' => 20,
            'Backstage Pass' => 10,
            'Table for 2' => 15, // 15 tables
            'Table for 4' => 10,
            'Table for 6' => 8,
            'Table for 8' => 5,
            'Table for 10' => 3,
            'Premium Seating' => 40,
            'Standard Entry' => null, // Unlimited
            'Gold Package' => 25,
            'Platinum Package' => 10,
            'Student Ticket' => 200
        ];

        $capacity = $capacities[$name] ?? null;

        // Default event is a year from now
        $eventDate = $this->faker->dateTimeBetween('+3 months', '+1 year');

        // Sales start 2 months before the event
        $salesStart = (clone $eventDate)->modify('-2 months');

        // Sales end 1 day before the event
        $salesEnd = (clone $eventDate)->modify('-1 day');

        return [
            'mctlists_id' => mctlists::inRandomOrder()->first()->id ?? 1,
            'name' => $name,
            'price' => $price,
            'sales_start' => $salesStart,
            'sales_end' => $salesEnd,
            'description' => $description,
            'capacity' => $capacity,
            'sold' => $capacity ? $this->faker->numberBetween(0, max(0, $capacity - 5)) : 0,
            'is_active' => true,
        ];
    }

    /**
     * Create a regular ticket
     */
    public function regular()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Regular Ticket',
                'price' => 5000,
                'description' => 'Standard event access',
                'capacity' => null,
            ];
        });
    }

    /**
     * Create a VIP ticket
     */
    public function vip()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'VIP Access',
                'price' => 15000,
                'description' => 'Premium seating with complimentary drinks',
                'capacity' => 50,
            ];
        });
    }

    /**
     * Create a VVIP ticket
     */
    public function vvip()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'VVIP Access',
                'price' => 25000,
                'description' => 'Exclusive area with premium service and complimentary food and drinks',
                'capacity' => 20,
            ];
        });
    }

    /**
     * Create a table ticket
     */
    public function table($size = 10)
    {
        $sizes = [2, 4, 6, 8, 10];
        $size = in_array($size, $sizes) ? $size : 10;

        $prices = [
            2 => 20000,
            4 => 40000,
            6 => 60000,
            8 => 80000,
            10 => 100000
        ];

        $capacities = [
            2 => 15,
            4 => 10,
            6 => 8,
            8 => 5,
            10 => 3
        ];

        return $this->state(function (array $attributes) use ($size, $prices, $capacities) {
            return [
                'name' => "Table for {$size}",
                'price' => $prices[$size],
                'description' => "Private table for {$size} with bottle service",
                'capacity' => $capacities[$size],
            ];
        });
    }
}
