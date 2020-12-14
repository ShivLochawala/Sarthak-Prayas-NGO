<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Transfer_bank;
use App\Models\Transfer_mode;

class BankController extends Controller
{
    public function show(){
        $banks = Transfer_bank::all();
        $modes = Transfer_mode::all();
        return view('admin.show-banks',compact('banks'), ['modes'=>$modes]);
    }

    public function store(Request $request){
        Transfer_bank::create($request->all());
        $request->session()->flash('success-message','Bank added successfully');
        return redirect()->back();
    }
}
