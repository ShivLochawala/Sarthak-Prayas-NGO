<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sponsor;
use App\Models\Beneficiarie;
use App\Models\Beneficiarie_sponsor;
use App\Models\Program;
use App\Models\Level;
use App\Models\Program_sponsor;
use DB;
use Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SponsorsImport;
use App\Imports\Program_sponsorsImport;
use App\Exports\SponsorsExport;

class SponsorController extends Controller
{
    public function create(){
        $beneficiaries = Beneficiarie::all()->where("isactive",1);
        $sponsors = sponsor::all();
        $programs = Program::all();
        $levels = Level::all();
        return view('admin.add-sponsor',compact('beneficiaries','sponsors','programs', 'levels'));
    }

    public function show(){
        $sponsors = Sponsor::all()->sortByDesc('id');
        $programs = Program::all();
        $levels = Level::all();
        $program_sponsors = Program_sponsor::all();
        $beneficiaries = Beneficiarie::all()->where("isactive",1);
        // foreach($sponsors as $sponsor){
        //     foreach($sponsor->$beneficiaries as $beneficiary){
        //         dd($beneficiary);
        //     }
        // }
        // dd($sponsors);
        return view('admin.view-sponsor',compact('sponsors'),['programs'=>$programs, 'levels'=>$levels, 'program_sponsors'=>$program_sponsors, 'beneficiaries' => $beneficiaries]);
    }

    public function store(Request $request){
        //dd($request->all());
        
        $beneficiary_id_array = $request->beneficiary_id;
        
        $Sponsor = new Sponsor;
        $Sponsor->name = $request->name;
        $Sponsor->address = $request->address;
        $Sponsor->mobile_no1 = $request->mobile_no1;
        $Sponsor->mobile_no2 = $request->mobile_no2;
        $Sponsor->email_id = $request->email_id;
        $Sponsor->dob = $request->dob;
        $Sponsor->reference = $request->reference;
        $Sponsor->save();
        $sponsor_id = $Sponsor->id;

        foreach($beneficiary_id_array as $beneficiary_id){
            $Beneficiarie_sponsor = new Beneficiarie_sponsor;
            $Beneficiarie_sponsor->sponsor_id = $sponsor_id;
            $Beneficiarie_sponsor->beneficiarie_id = $beneficiary_id;
            $Beneficiarie_sponsor->save();
        }
        $programs = Program::all();
        $i = 1;
        foreach($programs as $program){
            $pro_id = $request["program$i"];
            $lev_id = $request["level$i"];
            if($pro_id != "" && $lev_id != ""){
                $program_sponsor = new Program_sponsor;
                $program_sponsor->program_id = $pro_id;
                $program_sponsor->level_id = $lev_id;
                $program_sponsor->sponsor_id = $sponsor_id;
                $program_sponsor->save();
            }
            $i++;
        }
        $request->session()->flash('success-message','Sponsor added successfully');
        return redirect()->back();
    }

    public function deactive(Request $request){
        $id = $request->id;

        $sponsor = Sponsor::findOrFail($id);

        $sponsor->isactive = 0;

        $sponsor->save();

        $request->session()->flash('deactive-message','sponsor deactivated successflly');
        return redirect()->back();
    }

    public function active(Request $request){
        $id = $request->id;

        $sponsor = Sponsor::findOrFail($id);

        $sponsor->isactive = 1;

        $sponsor->save();

        $request->session()->flash('active-message','sponsor activated successflly');
        return redirect()->back();
    }

    public function editData(Request $req){
        $id = $req->id;
        $beneficiaries = Beneficiarie::all();
        $sponsors = sponsor::find($id);
        $programs = Program::all();
        $levels = Level::all();
        $program_sponsors = Program_sponsor::all();
        $Beneficiarie_sponsor = Beneficiarie_sponsor::all();
        return view('admin.edit-sponsor',compact('beneficiaries','sponsors','programs', 'levels', 'program_sponsors'),['Beneficiaries_sponsor'=>$Beneficiarie_sponsor]);
    }
    public function updateData(Request $req){
        $sponsors = sponsor::all()->where('id',$req->id);
        foreach($sponsors as $sponsor){
            $sponsor->name = $req->name;
            $sponsor->address = $req->address;
            $sponsor->mobile_no1 = $req->mobile_no1;
            $sponsor->mobile_no2 = $req->mobile_no2;
            $sponsor->email_id = $req->email_id;
            $sponsor->dob = $req->dob;
            $sponsor->reference = $req->reference;
            $sponsor->save();
        }
        $beneficiarie_id = $req->beneficiarie_id;
        //$levUpdate = Beneficiarie_sponsor::where("program_id",$id)->update(['flag'=>0]);
        $BenSpoUpdate = Beneficiarie_sponsor::where("sponsor_id",$req->id)->update(['flag'=>0]);
        $Beneficiarie_sponsor = Beneficiarie_sponsor::all()->where("sponsor_id",$req->id);

        foreach($beneficiarie_id as $ben){
            foreach($Beneficiarie_sponsor as $ben_spo){
                if($ben_spo['sponsor_id'] == $req->id && $ben_spo['flag']==0){
                    $ben_spo->beneficiarie_id = $ben;
                    $ben_spo->flag = 1;
                    $ben_spo->save();
                    break;
                }
            }
            $newben = Beneficiarie_sponsor::where([['beneficiarie_id','=',$ben],['sponsor_id','=',$req->id]])->doesntExist();
            if($newben){
                $beneficiarie_s = new Beneficiarie_sponsor;
                $beneficiarie_s->beneficiarie_id = $ben;
                $beneficiarie_s->sponsor_id = $req->id;
                $beneficiarie_s->save();
                break;
            }
        }
        return redirect("view-sponsor");
    }
    /*
    public function getLevels(Request $request){
        $r = $request->all();
        $programs = Program::find($r['id']);
        // $program = 
        print_r($programs['name']);
        return "A";
    }*/
    
    public function viewMultipleSponsor(){
        return view('admin.add-multiple-sponsor');
    }
    
    public function sponsorImport(Request $request) 
    {
        Excel::import(new SponsorsImport, $request->file('file')->store('temp'));
        //return back();
        return redirect("view-sponsor");
    }

    public function sponsorExport() 
    {
        return Excel::download(new SponsorsExport, 'Sponsor-Details.xlsx');
    }    

    public function sponsorProgramImport(Request $request) 
    {
        Excel::import(new Program_sponsorsImport, $request->file('file1')->store('temp'));
        //return back();
        return redirect("view-sponsor");
    }

    public function search(Request $request){
        if($request->type == 1){
            if($request->ajax()){
                
                $output="";
                $programs = Program::all();
                $levels = Level::all();
                $beneficiaries = Beneficiarie::all();
                $Beneficiarie_sponsors = Beneficiarie_sponsor::all();
                $program_sponsors = Program_sponsor::all();
                $sponsors=DB::table('sponsors')
                        ->where('name','LIKE','%'.$request->search."%")
                        ->orWhere('address','LIKE','%'.$request->search."%")
                        ->orWhere('mobile_no1','LIKE','%'.$request->search."%")
                        ->orWhere('mobile_no2','LIKE','%'.$request->search."%")
                        ->orWhere('email_id','LIKE','%'.$request->search."%")
                        //->orWhere('dob','LIKE','%'.$request->search."%")
                        ->orWhere('reference','LIKE','%'.$request->search."%")
                        ->get();

                if($sponsors){
                    foreach ($sponsors as $key => $sponsor) {
                    $output.='<tr>';
                        $output.=
                        //'<td>'.$beneficiarie->program_id.'</td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->name.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->address.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->mobile_no1.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->mobile_no2.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->email_id.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->dob.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">';
                                foreach($program_sponsors as $program_sponsor){
                                    if($program_sponsor['sponsor_id'] == $sponsor->id){
                                        foreach($programs as $program){
                                            if($program_sponsor['program_id'] == $program['id'])
                                            $output .= $program->name.'<br><hr>';
                                        }
                                    }
                                }
                            $output .= '</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">';
                                foreach($program_sponsors as $program_sponsor){
                                    if($program_sponsor['sponsor_id'] == $sponsor->id){
                                        foreach($levels as $level){
                                            if($program_sponsor['level_id'] == $level['id']){
                                                $output .= $level->name."-".$level->amount.'<br><hr>';
                                            }
                                        }
                                    }
                                }
                            $output .= '</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">';
                                foreach($Beneficiarie_sponsors as $Beneficiarie_sponsor){
                                    if($Beneficiarie_sponsor['sponsor_id'] == $sponsor->id){
                                        foreach($beneficiaries as $beneficiarie){
                                            if($beneficiarie['id'] == $Beneficiarie_sponsor['beneficiarie_id']){
                                                $output.= $beneficiarie->name.'<br><hr>';
                                            }
                                        }
                                    }
                                }
                            $output .= '</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->reference.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">';
                                if($sponsor->isactive === 1){
                                    $output .='<form method="post" action="deactive-sponsor" >';
                                    $output .= csrf_field();
                                    $output .= '<input type="hidden" name="id" value="'.$sponsor->id.'"/>
                                        <button class="bg-green-100 text-xs font-bold text-green-900 px-3 py-1 rounded trasition duration-300 ease-in-out hover:bg-green-200 hover:outline-none focus:outline-none">
                                            Active
                                        </button>
                                    </form>';
                                }else{
                                    $output .= '<form method="post" action="active-sponsor" >';
                                    $output .= csrf_field();
                                    $output .= '<input type="hidden" name="id" value="'.$sponsor->id.'"/>
                                        <button class="bg-red-100 text-xs font-bold text-red-900 px-3 py-1 rounded trasition duration-300 ease-in-out hover:bg-red-200 hover:outline-none focus:outline-none">
                                            Deactive
                                        </button>
                                    </form>';
                                }
                            $output .= '</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">
                                <form method="post" action="edit-sponsor" >';
                                    $output .= csrf_field();
                                    $output .= '<input type="hidden" name="id" value="'.$sponsor->id.'"/>
                                    <button class="btn-outline-primary transition duration-300 ease-in-out focus:outline-none focus:shadow-outline border border-purple-700 hover:bg-purple-700 text-purple-700 hover:text-white font-normal py-1 px-2 rounded">
                                        Edit
                                    </button>
                                </form>
                            </div>
                        </div>
                        </td>'.

                    '</tr>';
                    }
                }
                return Response($output);
            }
        }
        if($request->type == 2){
            if($request->ajax()){
                
                $output="";
                $programs = Program::all();
                $levels = Level::all();
                $beneficiaries = Beneficiarie::all();
                $Beneficiarie_sponsors = Beneficiarie_sponsor::all();
                $program_sponsors = Program_sponsor::all();
                /*$sponsors=DB::table('sponsors')
                        ->where('name','LIKE','%'.$request->search."%")
                        ->orWhere('address','LIKE','%'.$request->search."%")
                        ->orWhere('mobile_no1','LIKE','%'.$request->search."%")
                        ->orWhere('mobile_no2','LIKE','%'.$request->search."%")
                        ->orWhere('email_id','LIKE','%'.$request->search."%")
                        //->orWhere('dob','LIKE','%'.$request->search."%")
                        ->orWhere('reference','LIKE','%'.$request->search."%")
                        ->get();*/
                $sponsors=DB::table('sponsors')
                        ->join('program_sponsors', 'sponsors.id', '=', 'program_sponsors.sponsor_id')
                        ->join('programs', 'programs.id', '=', 'program_sponsors.program_id')
                        ->select('sponsors.*')
                        ->where('programs.id','=',$request->search)
                        ->get();

                if($sponsors){
                    foreach ($sponsors as $key => $sponsor) {
                    $output.='<tr>';
                        $output.=
                        //'<td>'.$beneficiarie->program_id.'</td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->name.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->address.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->mobile_no1.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->mobile_no2.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->email_id.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->dob.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">';
                                foreach($program_sponsors as $program_sponsor){
                                    if($program_sponsor['sponsor_id'] == $sponsor->id){
                                        foreach($programs as $program){
                                            if($program_sponsor['program_id'] == $program['id'])
                                            $output .= $program->name.'<br><hr>';
                                        }
                                    }
                                }
                            $output .= '</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">';
                                foreach($program_sponsors as $program_sponsor){
                                    if($program_sponsor['sponsor_id'] == $sponsor->id){
                                        foreach($levels as $level){
                                            if($program_sponsor['level_id'] == $level['id']){
                                                $output .= $level->name."-".$level->amount.'<br><hr>';
                                            }
                                        }
                                    }
                                }
                            $output .= '</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">';
                                foreach($Beneficiarie_sponsors as $Beneficiarie_sponsor){
                                    if($Beneficiarie_sponsor['sponsor_id'] == $sponsor->id){
                                        foreach($beneficiaries as $beneficiarie){
                                            if($beneficiarie['id'] == $Beneficiarie_sponsor['beneficiarie_id']){
                                                $output.= $beneficiarie->name.'<br><hr>';
                                            }
                                        }
                                    }
                                }
                            $output .= '</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->reference.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">';
                                if($sponsor->isactive === 1){
                                    $output .='<form method="post" action="deactive-sponsor" >';
                                    $output .= csrf_field();
                                    $output .= '<input type="hidden" name="id" value="'.$sponsor->id.'"/>
                                        <button class="bg-green-100 text-xs font-bold text-green-900 px-3 py-1 rounded trasition duration-300 ease-in-out hover:bg-green-200 hover:outline-none focus:outline-none">
                                            Active
                                        </button>
                                    </form>';
                                }else{
                                    $output .= '<form method="post" action="active-sponsor" >';
                                    $output .= csrf_field();
                                    $output .= '<input type="hidden" name="id" value="'.$sponsor->id.'"/>
                                        <button class="bg-red-100 text-xs font-bold text-red-900 px-3 py-1 rounded trasition duration-300 ease-in-out hover:bg-red-200 hover:outline-none focus:outline-none">
                                            Deactive
                                        </button>
                                    </form>';
                                }
                            $output .= '</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">
                                <form method="post" action="edit-sponsor" >';
                                    $output .= csrf_field();
                                    $output .= '<input type="hidden" name="id" value="'.$sponsor->id.'"/>
                                    <button class="btn-outline-primary transition duration-300 ease-in-out focus:outline-none focus:shadow-outline border border-purple-700 hover:bg-purple-700 text-purple-700 hover:text-white font-normal py-1 px-2 rounded">
                                        Edit
                                    </button>
                                </form>
                            </div>
                        </div>
                        </td>'.

                    '</tr>';
                    }
                }
                return Response($output);
            }
        }
        if($request->type == 3){
            if($request->ajax()){
                
                $output="";
                $programs = Program::all();
                $levels = Level::all();
                $beneficiaries = Beneficiarie::all();
                $Beneficiarie_sponsors = Beneficiarie_sponsor::all();
                $program_sponsors = Program_sponsor::all();
                $sponsors=DB::table('sponsors')
                        ->whereMonth('dob', '=', $request->search)
                        ->get();

                if($sponsors){
                    foreach ($sponsors as $key => $sponsor) {
                    $output.='<tr>';
                        $output.=
                        //'<td>'.$beneficiarie->program_id.'</td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->name.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->address.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->mobile_no1.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->mobile_no2.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->email_id.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->dob.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">';
                                foreach($program_sponsors as $program_sponsor){
                                    if($program_sponsor['sponsor_id'] == $sponsor->id){
                                        foreach($programs as $program){
                                            if($program_sponsor['program_id'] == $program['id'])
                                            $output .= $program->name.'<br><hr>';
                                        }
                                    }
                                }
                            $output .= '</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">';
                                foreach($program_sponsors as $program_sponsor){
                                    if($program_sponsor['sponsor_id'] == $sponsor->id){
                                        foreach($levels as $level){
                                            if($program_sponsor['level_id'] == $level['id']){
                                                $output .= $level->name."-".$level->amount.'<br><hr>';
                                            }
                                        }
                                    }
                                }
                            $output .= '</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">';
                                foreach($Beneficiarie_sponsors as $Beneficiarie_sponsor){
                                    if($Beneficiarie_sponsor['sponsor_id'] == $sponsor->id){
                                        foreach($beneficiaries as $beneficiarie){
                                            if($beneficiarie['id'] == $Beneficiarie_sponsor['beneficiarie_id']){
                                                $output.= $beneficiarie->name.'<br><hr>';
                                            }
                                        }
                                    }
                                }
                            $output .= '</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->reference.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">';
                                if($sponsor->isactive === 1){
                                    $output .='<form method="post" action="deactive-sponsor" >';
                                    $output .= csrf_field();
                                    $output .= '<input type="hidden" name="id" value="'.$sponsor->id.'"/>
                                        <button class="bg-green-100 text-xs font-bold text-green-900 px-3 py-1 rounded trasition duration-300 ease-in-out hover:bg-green-200 hover:outline-none focus:outline-none">
                                            Active
                                        </button>
                                    </form>';
                                }else{
                                    $output .= '<form method="post" action="active-sponsor" >';
                                    $output .= csrf_field();
                                    $output .= '<input type="hidden" name="id" value="'.$sponsor->id.'"/>
                                        <button class="bg-red-100 text-xs font-bold text-red-900 px-3 py-1 rounded trasition duration-300 ease-in-out hover:bg-red-200 hover:outline-none focus:outline-none">
                                            Deactive
                                        </button>
                                    </form>';
                                }
                            $output .= '</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">
                                <form method="post" action="edit-sponsor" >';
                                    $output .= csrf_field();
                                    $output .= '<input type="hidden" name="id" value="'.$sponsor->id.'"/>
                                    <button class="btn-outline-primary transition duration-300 ease-in-out focus:outline-none focus:shadow-outline border border-purple-700 hover:bg-purple-700 text-purple-700 hover:text-white font-normal py-1 px-2 rounded">
                                        Edit
                                    </button>
                                </form>
                            </div>
                        </div>
                        </td>'.

                    '</tr>';
                    }
                }
                return Response($output);
            }
        }
        if($request->type == 4){
            if($request->ajax()){
                
                $output="";
                $programs = Program::all();
                $levels = Level::all();
                $beneficiaries = Beneficiarie::all();
                $Beneficiarie_sponsors = Beneficiarie_sponsor::all();
                $program_sponsors = Program_sponsor::all();
                $sponsors=DB::table('sponsors')
                    ->join('beneficiarie_sponsors', 'sponsors.id', '=', 'beneficiarie_sponsors.sponsor_id')
                    ->join('beneficiaries', 'beneficiaries.id', '=', 'beneficiarie_sponsors.beneficiarie_id')
                    ->select('sponsors.*')
                    ->where('beneficiaries.id','=',$request->search)
                    ->get();

                if($sponsors){
                    foreach ($sponsors as $key => $sponsor) {
                    $output.='<tr>';
                        $output.=
                        //'<td>'.$beneficiarie->program_id.'</td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->name.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->address.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->mobile_no1.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->mobile_no2.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->email_id.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->dob.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">';
                                foreach($program_sponsors as $program_sponsor){
                                    if($program_sponsor['sponsor_id'] == $sponsor->id){
                                        foreach($programs as $program){
                                            if($program_sponsor['program_id'] == $program['id'])
                                            $output .= $program->name.'<br><hr>';
                                        }
                                    }
                                }
                            $output .= '</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">';
                                foreach($program_sponsors as $program_sponsor){
                                    if($program_sponsor['sponsor_id'] == $sponsor->id){
                                        foreach($levels as $level){
                                            if($program_sponsor['level_id'] == $level['id']){
                                                $output .= $level->name."-".$level->amount.'<br><hr>';
                                            }
                                        }
                                    }
                                }
                            $output .= '</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">';
                                foreach($Beneficiarie_sponsors as $Beneficiarie_sponsor){
                                    if($Beneficiarie_sponsor['sponsor_id'] == $sponsor->id){
                                        foreach($beneficiaries as $beneficiarie){
                                            if($beneficiarie['id'] == $Beneficiarie_sponsor['beneficiarie_id']){
                                                $output.= $beneficiarie->name.'<br><hr>';
                                            }
                                        }
                                    }
                                }
                            $output .= '</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$sponsor->reference.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">';
                                if($sponsor->isactive === 1){
                                    $output .='<form method="post" action="deactive-sponsor" >';
                                    $output .= csrf_field();
                                    $output .= '<input type="hidden" name="id" value="'.$sponsor->id.'"/>
                                        <button class="bg-green-100 text-xs font-bold text-green-900 px-3 py-1 rounded trasition duration-300 ease-in-out hover:bg-green-200 hover:outline-none focus:outline-none">
                                            Active
                                        </button>
                                    </form>';
                                }else{
                                    $output .= '<form method="post" action="active-sponsor" >';
                                    $output .= csrf_field();
                                    $output .= '<input type="hidden" name="id" value="'.$sponsor->id.'"/>
                                        <button class="bg-red-100 text-xs font-bold text-red-900 px-3 py-1 rounded trasition duration-300 ease-in-out hover:bg-red-200 hover:outline-none focus:outline-none">
                                            Deactive
                                        </button>
                                    </form>';
                                }
                            $output .= '</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">
                                <form method="post" action="edit-sponsor" >';
                                    $output .= csrf_field();
                                    $output .= '<input type="hidden" name="id" value="'.$sponsor->id.'"/>
                                    <button class="btn-outline-primary transition duration-300 ease-in-out focus:outline-none focus:shadow-outline border border-purple-700 hover:bg-purple-700 text-purple-700 hover:text-white font-normal py-1 px-2 rounded">
                                        Edit
                                    </button>
                                </form>
                            </div>
                        </div>
                        </td>'.

                    '</tr>';
                    }
                }
                return Response($output);
            }
        }
    }
}
