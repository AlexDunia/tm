<?php

namespace App\Http\Controllers;
use App\Models\User;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    //
    public function store(Request $request){
        // dd($request);
        $Formfield = $request->validate([
            'name'=>'required',
            'email'=>'required',
            'password'=>'required',
            // 'date'=>'required',
        ]);

        if ($request->hasFile('profilepic')) {
            $profilePicPath = $request->file('profilepic')->store('uploadedimage', 'public');
            $Formfield['profilepic'] = $profilePicPath;
        }

        $Formfield['password'] = bcrypt($Formfield['password']);
        // Admin::create($Formfield);

        $adminuser = User::create($Formfield);

        Auth()->login($adminuser);

        return redirect('/');
    }
}
