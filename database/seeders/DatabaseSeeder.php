<?php

namespace Database\Seeders;

use App\Models\mctlists;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create base events
        $this->createEventRecords();

        // Generate ticket types for the events
        $this->call([
            TicketTypeSeeder::class,
        ]);
    }

    /**
     * Create base event records
     */
    protected function createEventRecords(): void
    {
        mctlists::create([
            'id' => '1',
            'name' => 'ASA LIVE IN CONCERT',
            'location' => 'Genesis Event Center, Lagos, Nigeria.',
            'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.",
            'date' => 'December 15 @6:30pm',
            'herolink' => "Slideone",
            'image' => 'https://res.cloudinary.com/dnuhjsckk/image/upload/v1745339803/6th-Service-with-mudiaga_1_hjvlab.jpg',
            'heroimage' => 'https://res.cloudinary.com/dnuhjsckk/image/upload/v1745339804/IMG-20250219-WA0008_entmwi.jpg',
        ]);

        mctlists::create([
            'id' => '2',
            'name' => 'WIZKID LIVE IN CONCERT',
            'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.",
            'location' => 'Genesis Event Center, Lagos, Nigeria.',
            'date' => 'December 15 @6:30pm',
            'herolink' => "Slidetwo",
            'image' => 'https://res.cloudinary.com/dnuhjsckk/image/upload/v1745339803/Miss-Treasure-Base_gh6jnz.jpg',
            'heroimage' => 'https://res.cloudinary.com/dnuhjsckk/image/upload/v1745339803/Miss-Treasure-Base_gh6jnz.jpg',
        ]);

        mctlists::create([
            'id' => '3',
            'name' => 'ASA LIVE IN CONCERT',
            'location' => 'Genesis Event Center, Lagos, Nigeria.',
            'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.",
            'date' => 'December 15 @6:30pm',
            'herolink' => "Slideone",
            'image' => 'https://res.cloudinary.com/dnuhjsckk/image/upload/v1745339803/6th-Service-with-mudiaga_1_hjvlab.jpg',
            'heroimage' => 'https://res.cloudinary.com/dnuhjsckk/image/upload/v1745339804/IMG-20250219-WA0008_entmwi.jpg',
        ]);

        mctlists::create([
            'id' => '4',
            'name' => 'WIZKID LIVE IN CONCERT',
            'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.",
            'location' => 'Genesis Event Center, Lagos, Nigeria.',
            'date' => 'December 15 @6:30pm',
            'herolink' => "Slidetwo",
            'image' => 'https://res.cloudinary.com/dnuhjsckk/image/upload/v1745339803/Miss-Treasure-Base_gh6jnz.jpg',
            'heroimage' => 'https://res.cloudinary.com/dnuhjsckk/image/upload/v1745339803/Miss-Treasure-Base_gh6jnz.jpg',
        ]);

        mctlists::create([
            'id' => '5',
            'name' => 'ASA LIVE IN CONCERT',
            'location' => 'Genesis Event Center, Lagos, Nigeria.',
            'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.",
            'date' => 'December 15 @6:30pm',
            'herolink' => "Slideone",
            'image' => 'https://res.cloudinary.com/dnuhjsckk/image/upload/v1745339803/6th-Service-with-mudiaga_1_hjvlab.jpg',
            'heroimage' => 'https://res.cloudinary.com/dnuhjsckk/image/upload/v1745339804/IMG-20250219-WA0008_entmwi.jpg',
        ]);

        mctlists::create([
            'id' => '6',
            'name' => 'WIZKID LIVE IN CONCERT',
            'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.",
            'location' => 'Genesis Event Center, Lagos, Nigeria.',
            'date' => 'December 15 @6:30pm',
            'herolink' => "Slidetwo",
            'image' => 'https://res.cloudinary.com/dnuhjsckk/image/upload/v1745339803/Miss-Treasure-Base_gh6jnz.jpg',
            'heroimage' => 'https://res.cloudinary.com/dnuhjsckk/image/upload/v1745339803/Miss-Treasure-Base_gh6jnz.jpg',
        ]);

        mctlists::create([
            'id' => '7',
            'name' => 'ASA LIVE IN CONCERT',
            'location' => 'Genesis Event Center, Lagos, Nigeria.',
            'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.",
            'date' => 'December 15 @6:30pm',
            'herolink' => "Slideone",
            'image' => 'https://res.cloudinary.com/dnuhjsckk/image/upload/v1745339803/6th-Service-with-mudiaga_1_hjvlab.jpg',
            'heroimage' => 'https://res.cloudinary.com/dnuhjsckk/image/upload/v1745339804/IMG-20250219-WA0008_entmwi.jpg',
        ]);

        mctlists::create([
            'id' => '8',
            'name' => 'WIZKID LIVE IN CONCERT',
            'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.",
            'location' => 'Genesis Event Center, Lagos, Nigeria.',
            'date' => 'December 15 @6:30pm',
            'herolink' => "Slidetwo",
            'image' => 'https://res.cloudinary.com/dnuhjsckk/image/upload/v1745339803/Miss-Treasure-Base_gh6jnz.jpg',
            'heroimage' => 'https://res.cloudinary.com/dnuhjsckk/image/upload/v1745339803/Miss-Treasure-Base_gh6jnz.jpg',
        ]);

        mctlists::create([
            'id' => '9',
            'name' => 'ASA LIVE IN CONCERT',
            'location' => 'Genesis Event Center, Lagos, Nigeria.',
            'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.",
            'date' => 'December 15 @6:30pm',
            'herolink' => "Slideone",
            'image' => 'https://res.cloudinary.com/dnuhjsckk/image/upload/v1745339803/6th-Service-with-mudiaga_1_hjvlab.jpg',
            'heroimage' => 'https://res.cloudinary.com/dnuhjsckk/image/upload/v1745339804/IMG-20250219-WA0008_entmwi.jpg',
        ]);

        mctlists::create([
            'id' => '10',
            'name' => 'WIZKID LIVE IN CONCERT',
            'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.",
            'location' => 'Genesis Event Center, Lagos, Nigeria.',
            'date' => 'December 15 @6:30pm',
            'herolink' => "Slidetwo",
            'image' => 'https://res.cloudinary.com/dnuhjsckk/image/upload/v1745339803/Miss-Treasure-Base_gh6jnz.jpg',
            'heroimage' => 'https://res.cloudinary.com/dnuhjsckk/image/upload/v1745339803/Miss-Treasure-Base_gh6jnz.jpg',
        ]);
    }
}
