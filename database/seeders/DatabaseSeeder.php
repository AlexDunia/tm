<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use App\Models\listing;
use App\Models\mctlists;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        mctlists::create(  [
            'id'=>'1',
            'name'=>'ASA LIVE IN CONCERT',
            'location'=>'Genesis Event Center, Lagos, Nigeria.',
            'description'=>"Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'date'=>'December 15 @6:30pm',
            'herolink'=>"Slideone",
            'earlybirds'=>'Table for 10, 5000'
        ]);

        mctlists::create(  [
            'id'=>'2',
            'name'=>'WIZKID LIVE IN CONCERT',
            'description'=>"Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'location'=>'Genesis Event Center, Lagos, Nigeria.',
            'date'=>'December 15 @6:30pm',
            'herolink'=>"Slidetwo"
        ]);

        mctlists::create(  [
            'id'=>'3',
            'name'=>'ASA LIVE IN CONCERT',
            'location'=>'Genesis Event Center, Lagos, Nigeria.',
            'description'=>"Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'date'=>'December 15 @6:30pm',
            'herolink'=>"Slideone",
            'earlybirds'=>'Table for 10, 5000'
        ]);

        mctlists::create(  [
            'id'=>'4',
            'name'=>'WIZKID LIVE IN CONCERT',
            'description'=>"Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'location'=>'Genesis Event Center, Lagos, Nigeria.',
            'date'=>'December 15 @6:30pm',
            'herolink'=>"Slidetwo"
        ]);

        mctlists::create(  [
            'id'=>'5',
            'name'=>'ASA LIVE IN CONCERT',
            'location'=>'Genesis Event Center, Lagos, Nigeria.',
            'description'=>"Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'date'=>'December 15 @6:30pm',
            'herolink'=>"Slideone",
            'earlybirds'=>'Table for 10, 5000'
        ]);

        mctlists::create(  [
            'id'=>'6',
            'name'=>'WIZKID LIVE IN CONCERT',
            'description'=>"Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'location'=>'Genesis Event Center, Lagos, Nigeria.',
            'date'=>'December 15 @6:30pm',
            'herolink'=>"Slidetwo"
        ]);

        mctlists::create(  [
            'id'=>'7',
            'name'=>'ASA LIVE IN CONCERT',
            'location'=>'Genesis Event Center, Lagos, Nigeria.',
            'description'=>"Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'date'=>'December 15 @6:30pm',
            'herolink'=>"Slideone",
            'earlybirds'=>'Table for 10, 5000'
        ]);

        mctlists::create(  [
            'id'=>'8',
            'name'=>'WIZKID LIVE IN CONCERT',
            'description'=>"Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'location'=>'Genesis Event Center, Lagos, Nigeria.',
            'date'=>'December 15 @6:30pm',
            'herolink'=>"Slidetwo"
        ]);
        mctlists::create(  [
            'id'=>'9',
            'name'=>'ASA LIVE IN CONCERT',
            'location'=>'Genesis Event Center, Lagos, Nigeria.',
            'description'=>"Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'date'=>'December 15 @6:30pm',
            'herolink'=>"Slideone",
            'earlybirds'=>'Table for 10, 5000'
        ]);

        mctlists::create(  [
            'id'=>'10',
            'name'=>'WIZKID LIVE IN CONCERT',
            'description'=>"Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'location'=>'Genesis Event Center, Lagos, Nigeria.',
            'date'=>'December 15 @6:30pm',
            'herolink'=>"Slidetwo"
        ]);


    }
}
