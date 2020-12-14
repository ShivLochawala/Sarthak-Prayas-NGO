<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Program;
use App\Models\Beneficiarie;
use App\Models\Sponsor;
use App\Models\Beneficiarie_sponsor;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BeneficiariesImport;
use App\Imports\Beneficiarie_sponsorsImport;
use App\Exports\BeneficiariesExport;


class BeneficiaryController extends Controller
{
    function addData(Request $req){
        $beneficiary = new Beneficiarie;
        $beneficiary->program_id = $req->program_id;
        $beneficiary->name = $req->beneficiary_name;
        $beneficiary->address = $req->beneficiary_address;
        $beneficiary->mobile_no = $req->beneficiary_mobile_no;
        $beneficiary->dob = $req->beneficiary_dob;
        $beneficiary->father_name = $req->beneficiary_father_name;
        $beneficiary->father_occupation = $req->beneficiary_father_occupation;
        $beneficiary->class = $req->beneficiary_class;
        $beneficiary->reference = $req->beneficiary_reference;
        $sponsor_id = $req->sponsor_id;
        $beneficiary->save();
        $beneficiary_id = $beneficiary->id;
        
        foreach($sponsor_id as $spon){
            $Beneficiarie_sponsor = new Beneficiarie_sponsor;
            $Beneficiarie_sponsor->beneficiarie_id = $beneficiary_id;
            $Beneficiarie_sponsor->sponsor_id = $spon;
            $Beneficiarie_sponsor->save();
        }
        
        return redirect("view-beneficiary");
    }
    function showData(){
        $Beneficiarie = Beneficiarie::all()->sortByDesc('id');
        $program = Program::all()->where("isactive",1);
        $sponsor = Sponsor::all()->where("isactive",1);
        $Beneficiarie_sponsor = Beneficiarie_sponsor::all();
        return view("admin.view-beneficiary",['Beneficiarie'=>$Beneficiarie, 'Program'=>$program, 'Sponsor'=>$sponsor, 'Beneficiaries_sponsor'=>$Beneficiarie_sponsor]);
    }
    function activeDeactiveData($beneficiary_id, $isactive){
        $Beneficiarie = Beneficiarie::all()->sortByDesc('id');
        $program = Program::all();
        DB::update('update beneficiaries set isactive = ? where id = ?',[$isactive,$beneficiary_id]);
        return redirect("view-beneficiary");
        //return view("admin/view-beneficiary", ['Beneficiarie'=>$Beneficiarie, 'Program'=>$program]);
    }
    function editData(Request $req){
        $id=$req->id;
        $Beneficiarie = Beneficiarie::find($id);
        $program = Program::all()->where("isactive",1);
        $sponsor = Sponsor::all()->where("isactive",1);
        $Beneficiarie_sponsor = Beneficiarie_sponsor::all();
        return view("admin.edit-beneficiary", ['Beneficiarie'=>$Beneficiarie, 'Program'=>$program, 'Sponsor'=>$sponsor, 'Beneficiaries_sponsor'=>$Beneficiarie_sponsor]);
    }
    function updateData(Request $req){
        $beneficiarie = Beneficiarie::all()->where('id',$req->id);
        foreach($beneficiarie as $ben){
            $ben->program_id = $req->program_id;
            $ben->name = $req->beneficiary_name;
            $ben->address = $req->beneficiary_address;
            $ben->mobile_no = $req->beneficiary_mobile_no;
            $ben->dob = $req->beneficiary_dob;
            $ben->father_name = $req->beneficiary_father_name;
            $ben->father_occupation = $req->beneficiary_father_occupation;
            $ben->class = $req->beneficiary_class;
            $ben->reference = $req->beneficiary_reference;
            $ben->save();
        }
        $sponsor_id = $req->sponsor_id;
        //$levUpdate = Beneficiarie_sponsor::where("program_id",$id)->update(['flag'=>0]);
        $BenSpoUpdate = Beneficiarie_sponsor::where("beneficiarie_id",$req->id)->update(['flag'=>0]);
        $Beneficiarie_sponsor = Beneficiarie_sponsor::all()->where("beneficiarie_id",$req->id);

        foreach($sponsor_id as $spo){
            foreach($Beneficiarie_sponsor as $ben_spo){
                if($ben_spo['beneficiarie_id'] == $req->id && $ben_spo['flag']==0){
                    $ben_spo->sponsor_id = $spo;
                    $ben_spo->flag = 1;
                    $ben_spo->save();
                    break;
                }
            }
            $newben = Beneficiarie_sponsor::where([['sponsor_id','=',$spo],['beneficiarie_id','=',$req->id]])->doesntExist();
            if($newben){
                $beneficiarie_s = new Beneficiarie_sponsor;
                $beneficiarie_s->beneficiarie_id = $req->id;
                $beneficiarie_s->sponsor_id = $spo;
                $beneficiarie_s->save();
                break;
            }
        }
        return redirect("view-beneficiary");
    }

    public function viewMultipleBeneficiary(){
        return view('admin.add-multiple-beneficiary');
    }

    public function beneficiarieImport(Request $request) 
    {
        Excel::import(new BeneficiariesImport, $request->file('file')->store('temp'));
        return redirect("view-beneficiary");
    }

    
    public function beneficiarieExport() 
    {
        return Excel::download(new BeneficiariesExport, 'Beneficiaries-Details.xlsx');
    }

    public function beneficiareSponsorImport(Request $request) 
    {
        Excel::import(new Beneficiarie_sponsorsImport, $request->file('file2')->store('temp'));
        //return back();
        return redirect("view-sponsor");
    }

    public function search(Request $request){
        if($request->type == 1){
            if($request->ajax()){
                
                $output="";
                $programs = Program::all();
                $sponsors = Sponsor::all();
                $Beneficiarie_sponsors = Beneficiarie_sponsor::all();

                $beneficiaries=DB::table('beneficiaries')
                        ->where('name','LIKE','%'.$request->search."%")
                        ->orWhere('address','LIKE','%'.$request->search."%")
                        ->orWhere('mobile_no','LIKE','%'.$request->search."%")
                        //->orWhere('dob','LIKE','%'.$request->search."%")
                        ->orWhere('father_name','LIKE','%'.$request->search."%")
                        ->orWhere('father_occupation','LIKE','%'.$request->search."%")
                        ->orWhere('class','LIKE','%'.$request->search."%")
                        ->orWhere('reference','LIKE','%'.$request->search."%")
                        ->get();

                if($beneficiaries){
                    foreach ($beneficiaries as $key => $beneficiarie) {
                    $output.='<tr>';
                        foreach($programs as $program){
                            if($program['id'] == $beneficiarie->program_id){
                                $output.='<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900">'.$program->name.'</div>
                                </div>
                            </td>';
                            }
                        }
                        $output.=
                        //'<td>'.$beneficiarie->program_id.'</td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->name.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->address.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->mobile_no.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->dob.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->father_name.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->father_occupation.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->class.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->reference.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">';
                        foreach($Beneficiarie_sponsors as $Beneficiarie_sponsor){
                            if($Beneficiarie_sponsor['beneficiarie_id'] == $beneficiarie->id){
                                foreach($sponsors as $sponsor){
                                    if($sponsor['id'] == $Beneficiarie_sponsor['sponsor_id']){
                                        $output.= $sponsor->name.'<br><hr>';
                                    }
                                }
                            }
                        }
                        $output.='</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900">';
                                if($beneficiarie->isactive === 1)
                                $output.='
                                    <a href="beneficiaryActiveOrDeactive/'.$beneficiarie->id.'/0"><button class="bg-green-100 text-xs font-bold text-green-900 px-3 py-1 rounded trasition duration-300 ease-in-out hover:bg-green-200 hover:outline-none focus:outline-none" name="active">Active</button></a>';
                                else
                                $output.=' 
                                    <a href="beneficiaryActiveOrDeactive/'.$beneficiarie->id.'/1"><button class="bg-red-100 text-xs font-bold text-red-900 px-3 py-1 rounded trasition duration-300 ease-in-out hover:bg-red-200 hover:outline-none focus:outline-none" name="deactive">Deactive</button></a>';
                                $output .= '</div>
                            </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">
                                <form method="post" action="edit-beneficiary">';
                                    $output .= csrf_field();
                                    $output .= '.<input type="hidden" name="id" value="'.$beneficiarie->id.'"/>
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
                $sponsors = Sponsor::all();
                $Beneficiarie_sponsors = Beneficiarie_sponsor::all();

                $beneficiaries=DB::table('beneficiaries')
                        ->where('program_id','=',$request->search)
                        ->get();
                if($beneficiaries){
                    foreach ($beneficiaries as $key => $beneficiarie) {
                    $output.='<tr>';
                        foreach($programs as $program){
                            if($program['id'] == $beneficiarie->program_id){
                                $output.='<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900">'.$program->name.'</div>
                                </div>
                            </td>';
                            }
                        }
                        $output.=
                        //'<td>'.$beneficiarie->program_id.'</td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->name.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->address.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->mobile_no.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->dob.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->father_name.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->father_occupation.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->class.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->reference.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">';
                        foreach($Beneficiarie_sponsors as $Beneficiarie_sponsor){
                            if($Beneficiarie_sponsor['beneficiarie_id'] == $beneficiarie->id){
                                foreach($sponsors as $sponsor){
                                    if($sponsor['id'] == $Beneficiarie_sponsor['sponsor_id']){
                                        $output.= $sponsor->name.'<br><hr>';
                                    }
                                }
                            }
                        }
                        $output.='</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900">';
                                if($beneficiarie->isactive === 1)
                                $output.='
                                    <a href="beneficiaryActiveOrDeactive/'.$beneficiarie->id.'/0"><button class="bg-green-100 text-xs font-bold text-green-900 px-3 py-1 rounded trasition duration-300 ease-in-out hover:bg-green-200 hover:outline-none focus:outline-none" name="active">Active</button></a>';
                                else
                                $output.=' 
                                    <a href="beneficiaryActiveOrDeactive/'.$beneficiarie->id.'/1"><button class="bg-red-100 text-xs font-bold text-red-900 px-3 py-1 rounded trasition duration-300 ease-in-out hover:bg-red-200 hover:outline-none focus:outline-none" name="deactive">Deactive</button></a>';
                                $output .= '</div>
                            </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">
                                <form method="post" action="edit-beneficiary">';
                                    $output .= csrf_field();
                                    $output .= '.<input type="hidden" name="id" value="'.$beneficiarie->id.'"/>
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
                $sponsors = Sponsor::all();
                $Beneficiarie_sponsors = Beneficiarie_sponsor::all();

                $beneficiaries=DB::table('beneficiaries')
                        ->where('class','=',$request->search)
                        ->get();
                if($beneficiaries){
                    foreach ($beneficiaries as $key => $beneficiarie) {
                    $output.='<tr>';
                        foreach($programs as $program){
                            if($program['id'] == $beneficiarie->program_id){
                                $output.='<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900">'.$program->name.'</div>
                                </div>
                            </td>';
                            }
                        }
                        $output.=
                        //'<td>'.$beneficiarie->program_id.'</td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->name.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->address.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->mobile_no.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->dob.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->father_name.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->father_occupation.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->class.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->reference.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">';
                        foreach($Beneficiarie_sponsors as $Beneficiarie_sponsor){
                            if($Beneficiarie_sponsor['beneficiarie_id'] == $beneficiarie->id){
                                foreach($sponsors as $sponsor){
                                    if($sponsor['id'] == $Beneficiarie_sponsor['sponsor_id']){
                                        $output.= $sponsor->name.'<br><hr>';
                                    }
                                }
                            }
                        }
                        $output.='</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900">';
                                if($beneficiarie->isactive === 1)
                                $output.='
                                    <a href="beneficiaryActiveOrDeactive/'.$beneficiarie->id.'/0"><button class="bg-green-100 text-xs font-bold text-green-900 px-3 py-1 rounded trasition duration-300 ease-in-out hover:bg-green-200 hover:outline-none focus:outline-none" name="active">Active</button></a>';
                                else
                                $output.=' 
                                    <a href="beneficiaryActiveOrDeactive/'.$beneficiarie->id.'/1"><button class="bg-red-100 text-xs font-bold text-red-900 px-3 py-1 rounded trasition duration-300 ease-in-out hover:bg-red-200 hover:outline-none focus:outline-none" name="deactive">Deactive</button></a>';
                                $output .= '</div>
                            </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">
                                <form method="post" action="edit-beneficiary">';
                                    $output .= csrf_field();
                                    $output .= '.<input type="hidden" name="id" value="'.$beneficiarie->id.'"/>
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
                $sponsors = Sponsor::all();
                $Beneficiarie_sponsors = Beneficiarie_sponsor::all();

                $beneficiaries=DB::table('beneficiaries')
                        ->join('beneficiarie_sponsors', 'beneficiaries.id', '=', 'beneficiarie_sponsors.beneficiarie_id')
                        ->join('sponsors', 'sponsors.id', '=', 'beneficiarie_sponsors.sponsor_id')
                        ->select('beneficiaries.*')
                        ->where('sponsors.id','=',$request->search)
                        ->get();
                if($beneficiaries){
                    foreach ($beneficiaries as $key => $beneficiarie) {
                    $output.='<tr>';
                        foreach($programs as $program){
                            if($program['id'] == $beneficiarie->program_id){
                                $output.='<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900">'.$program->name.'</div>
                                </div>
                            </td>';
                            }
                        }
                        $output.=
                        //'<td>'.$beneficiarie->program_id.'</td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->name.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->address.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->mobile_no.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->dob.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->father_name.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->father_occupation.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->class.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">'.$beneficiarie->reference.'</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap"><div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900">';
                        foreach($Beneficiarie_sponsors as $Beneficiarie_sponsor){
                            if($Beneficiarie_sponsor['beneficiarie_id'] == $beneficiarie->id){
                                foreach($sponsors as $sponsor){
                                    if($sponsor['id'] == $Beneficiarie_sponsor['sponsor_id']){
                                        $output.= $sponsor->name.'<br><hr>';
                                    }
                                }
                            }
                        }
                        $output.='</div>
                        </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900">';
                                if($beneficiarie->isactive === 1)
                                $output.='
                                    <a href="beneficiaryActiveOrDeactive/'.$beneficiarie->id.'/0"><button class="bg-green-100 text-xs font-bold text-green-900 px-3 py-1 rounded trasition duration-300 ease-in-out hover:bg-green-200 hover:outline-none focus:outline-none" name="active">Active</button></a>';
                                else
                                $output.=' 
                                    <a href="beneficiaryActiveOrDeactive/'.$beneficiarie->id.'/1"><button class="bg-red-100 text-xs font-bold text-red-900 px-3 py-1 rounded trasition duration-300 ease-in-out hover:bg-red-200 hover:outline-none focus:outline-none" name="deactive">Deactive</button></a>';
                                $output .= '</div>
                            </div>
                        </td>'.
                        '<td class="pl-3 py-4 bg-white whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">
                                <form method="post" action="edit-beneficiary">';
                                    $output .= csrf_field();
                                    $output .= '.<input type="hidden" name="id" value="'.$beneficiarie->id.'"/>
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
