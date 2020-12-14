<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Sponsor;
use App\Models\Level;
class ProgramController extends Controller
{
    public function show(){
        $programs = Program::all();
        $levels = Level::all();
        $programs_array = $programs;
        
        return view('admin/show-program',compact('programs_array'), ['levels'=>$levels]);
    }

    public function store(Request $request){
        
        $total_levels = $request->count;
        /*$levels_array = array();
        $amounts_array = array();

        for($i=1;$i<=$total_levels;$i++){
            array_push($levels_array,$request["program-level-$i"]);
            //$request->request->remove("program-level-$i");
            array_push($amounts_array,$request["program-amount-$i"]);
            //$request->request->remove("program-amount-$i");
        }*/
        //$request->request->remove('count');

        //$levels = implode(',',$levels_array);
        //$amounts = implode(',',$amounts_array);
        
        $request->request->add(['isactive'=>1]);
        //$request->merge(['levels'=>$levels]);
        //$request->merge(['amount'=>$amounts]);
        
        $pro = Program::create($request->all());
        
        for($i=1;$i<=$total_levels;$i++){
            $level = new Level;
            $level->program_id = $pro->id;
            $level->name = $request["program-level-$i"];
            $level->amount = $request["program-amount-$i"];
            $level->save();
        }
        $request->session()->flash('success-message','Program added successflly');
        return redirect()->back();
    } 

    public function deactive(Request $request){
        $id = $request->id;

        $program = Program::findOrFail($id);

        $program->isactive = 0;

        $program->save();

        $request->session()->flash('deactive-message','Program deactivated successflly');
        return redirect()->back();
    }

    public function active(Request $request){
        $id = $request->id;

        $program = Program::findOrFail($id);

        $program->isactive = 1;

        $program->save();

        $request->session()->flash('active-message','Program activated successflly');
        return redirect()->back();
    }

    public function edit(Request $request){
        $id=$request->id;
        $program = Program::find($id);
        $level = Level::all();
        return view('admin.edit-program',compact('program'), ['levels'=>$level]);
    }

    public function update(Request $request){

        $total_levels = $request->count;
        //$levels_array = array();
        //$amounts_array = array();

        $id=$request->id;  
        $levUpdate = Level::where("program_id",$id)->update(['flag'=>0]);
        $level = Level::all()->where("program_id",$id);
        
        $i = 1;
        while($i<=$total_levels){
            $level_name = $request["program-level-$i"];
            $level_amount = $request["program-amount-$i"];
            foreach($level as $lev){
                if($lev->flag==0){
                    if($level_name == "" && $level_amount == ""){
                        Level::where("id",$lev->id)->delete();
                        continue;
                    }else{
                        $lev->name = $level_name;
                        $lev->amount = $level_amount;
                        $lev->flag = 1;
                        $lev->save();
                        break;
                    }
                }
            }
            
            $new = Level::where([['program_id','=',$id],['name','=',$level_name], ['amount','=',$level_amount]])->doesntExist();
            if($new){
                if($level_name != "" && $level_name !=""){
                $levnew = new Level;
                $levnew->program_id = $id;
                $levnew->name = $level_name;
                $levnew->amount = $level_amount;
                $levnew->save();
                }
            }
            $i++;
            continue;
        }
        

        /*for($i=1;$i<=$total_levels;$i++){
            array_push($levels_array,$request["program-level-$i"]);
            $request->request->remove("program-level-$i");
            array_push($amounts_array,$request["program-amount-$i"]);
            $request->request->remove("program-amount-$i");
        }*/

        /*$request->request->remove('count');

        $levels = implode(',',$levels_array);
        $amounts = implode(',',$amounts_array);*/
        
        //$request->request->add(['isactive'=>1]);

        //$request->merge(['levels'=>$levels]);
        //$request->merge(['amount'=>$amounts]);

        //$request->request->remove("_token");
        //$request->request->remove("_method");

        //$program = Program::where('id',$id)->update($request->all());
        $program = Program::all()->where('id',$request->id);
        foreach($program as $pro){
            $pro->name = $request->name;
            $pro->desc = $request->desc;
            $pro->frequency = $request->frequency;
            $pro->save();
        }
        //$program = Program::where(['id'=>$request->id])->update([['name'=>$request->name],['desc'=>$request->desc], ['frequency'=>$request->frequency]]);
        $request->session()->flash('success-message','Program updated successfully');
        return redirect('view-program');
    }

    function getName(){
        $program = Program::all()->where('isactive', '1');
        $sponsor = Sponsor::all()->where('isactive', '1')->sortBy('name');
        return view('admin/add-beneficiary',['program'=>$program,'sponsor'=>$sponsor]);
    }
}
