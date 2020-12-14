<?php

namespace App\Exports;

use App\Models\Sponsor;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SponsorsExport implements FromCollection, WithHeadings
{
    
    public function collection()
    {
        $sponsor = DB::table('sponsors')
            ->join('beneficiarie_sponsors', 'sponsors.id', '=', 'beneficiarie_sponsors.sponsor_id')
            ->join('program_sponsors', 'sponsors.id', '=', 'program_sponsors.sponsor_id')
            ->join('beneficiaries', 'beneficiaries.id', '=', 'beneficiarie_sponsors.beneficiarie_id')
            ->join('programs', 'programs.id', '=', 'program_sponsors.program_id')
            ->join('levels', 'levels.id', '=', 'program_sponsors.level_id')
            ->select('sponsors.*','programs.name as ProgramName', 'levels.name as LevelName', 'levels.amount','beneficiaries.name as BeneficiarieName')
            ->get();    
        return $sponsor;
            //return Sponsor::all();
    }
    public function headings():array
    {
        return ["sponsor_id", "Name", "Address", "Mobile No1", "Mobile No2", "Email id", "Date of Birth", "Reference", "Isactive", "Program", "Level Name", "Level Amount", "Beneficiaries"];
    }
}
