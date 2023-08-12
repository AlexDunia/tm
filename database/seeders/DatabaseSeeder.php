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
            'Name'=>'ASA LIVE IN CONCERT',
            'Location'=>'Genesis Event Center, Lagos, Nigeria.',
            'Description'=>"Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'Date'=>'December 15 @6:30pm',
            'herolink'=>"Slideone",
            'earlybirds'=>'Table for 10, 5000'
        ]);

        mctlists::create(  [
            'id'=>'2',
            'Name'=>'WIZKID LIVE IN CONCERT',
            'Description'=>"Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'Location'=>'Genesis Event Center, Lagos, Nigeria.',
            'Date'=>'December 15 @6:30pm',
            'herolink'=>"Slidetwo"
        ]);

        mctlists::create(  [
            'id'=>'3',
            'Name'=>'ASA LIVE IN CONCERT',
            'Location'=>'Genesis Event Center, Lagos, Nigeria.',
            'Description'=>"Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'Date'=>'December 15 @6:30pm',
            'herolink'=>"Slideone",
            'earlybirds'=>'Table for 10, 5000'
        ]);

        mctlists::create(  [
            'id'=>'4',
            'Name'=>'WIZKID LIVE IN CONCERT',
            'Description'=>"Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'Location'=>'Genesis Event Center, Lagos, Nigeria.',
            'Date'=>'December 15 @6:30pm',
            'herolink'=>"Slidetwo"
        ]);

        mctlists::create(  [
            'id'=>'5',
            'Name'=>'ASA LIVE IN CONCERT',
            'Location'=>'Genesis Event Center, Lagos, Nigeria.',
            'Description'=>"Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'Date'=>'December 15 @6:30pm',
            'herolink'=>"Slideone",
            'earlybirds'=>'Table for 10, 5000'
        ]);

        mctlists::create(  [
            'id'=>'6',
            'Name'=>'WIZKID LIVE IN CONCERT',
            'Description'=>"Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'Location'=>'Genesis Event Center, Lagos, Nigeria.',
            'Date'=>'December 15 @6:30pm',
            'herolink'=>"Slidetwo"
        ]);

        mctlists::create(  [
            'id'=>'7',
            'Name'=>'ASA LIVE IN CONCERT',
            'Location'=>'Genesis Event Center, Lagos, Nigeria.',
            'Description'=>"Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'Date'=>'December 15 @6:30pm',
            'herolink'=>"Slideone",
            'earlybirds'=>'Table for 10, 5000'
        ]);

        mctlists::create(  [
            'id'=>'8',
            'Name'=>'WIZKID LIVE IN CONCERT',
            'Description'=>"Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'Location'=>'Genesis Event Center, Lagos, Nigeria.',
            'Date'=>'December 15 @6:30pm',
            'herolink'=>"Slidetwo"
        ]);
        mctlists::create(  [
            'id'=>'9',
            'Name'=>'ASA LIVE IN CONCERT',
            'Location'=>'Genesis Event Center, Lagos, Nigeria.',
            'Description'=>"Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'Date'=>'December 15 @6:30pm',
            'herolink'=>"Slideone",
            'earlybirds'=>'Table for 10, 5000'
        ]);

        mctlists::create(  [
            'id'=>'10',
            'Name'=>'WIZKID LIVE IN CONCERT',
            'Description'=>"Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'Location'=>'Genesis Event Center, Lagos, Nigeria.',
            'Date'=>'December 15 @6:30pm',
            'herolink'=>"Slidetwo"
        ]);


    }
}
