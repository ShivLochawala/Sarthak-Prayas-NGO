<?php

namespace App\Exports;
use DB;
use App\Models\Beneficiarie;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BeneficiariesExport implements FromCollection, WithHeadings
{
    
    public function collection()
    {
        $beneficiarie = DB::table('beneficiaries')
            ->join('beneficiarie_sponsors', 'beneficiaries.id', '=', 'beneficiarie_sponsors.beneficiarie_id')
            ->join('sponsors', 'sponsors.id', '=', 'beneficiarie_sponsors.sponsor_id')
            ->select('beneficiaries.*','sponsors.name as SponsorName')
            ->get();    
        return $beneficiarie;
        //return Beneficiarie::all();
    }
    public function headings():array
    {
        return ["beneficiarie_id", "program_id", "Name", "Address", "Mobile No", "Date of Birth", "Father Name", "Father Occupation", "Class", "Reference", "Isactive", "Sponsor Name"];
    }
}
