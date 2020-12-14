<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class HelperController extends Controller
{
    public function register(Request $request){
        $request->validate([
            "email"=>"email",
        ]);
        
        $request->merge([
            'password'=>Hash::make($request->password),
        ]);

        $request->request->add(["slug"=>"helper"]);

        $user = User::create($request->all());
        $user->save();

        return redirect()->back();
    }
}
