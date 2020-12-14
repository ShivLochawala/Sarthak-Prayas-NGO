<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Transfer_mode;

class ModeController extends Controller
{
    public function show(){
        $modes = Transfer_mode::all();
        return view('admin.show-modes',['modes'=>$modes]);
    }

    public function store(Request $request){
        Transfer_mode::create($request->all());
        $request->session()->flash('success-message','Mode added successfully');
        return redirect()->back();
    }
}
