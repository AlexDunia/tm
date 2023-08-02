<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class mctlists extends Model
class  listing
{
    // use HasFactory;
    public static function all(){
        return [
            [
                'id'=>'1',
                'Name'=>'ASA LIVE IN CONCERT',
                'Location'=>'Genesis Event Center, Lagos, Nigeria.',
                'Date'=>'December 15 @6:30pm'
            ]
            ];
    }

    public static function find($id){
        // First step is to actually call the main
        $events = self::all();
        // with the code above, you are assigning all the listings to a new variable.

        foreach($events as $listone){
            if($listone['id'] == $id){
                // of listoneid is equals to the id, then return the id we are looking for.
                return $listone;
            }
        }
    }
}
