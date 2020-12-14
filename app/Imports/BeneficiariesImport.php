<?php

namespace App\Imports;

use App\Models\Beneficiarie;
use Maatwebsite\Excel\Concerns\ToModel;

class BeneficiariesImport implements ToModel
{
    
    public function model(array $row)
    {
        return new Beneficiarie([
            'program_id' =>$row[0],
            'name' => $row[1],
            'address' => $row[2],
            'mobile_no' => $row[3],
            'dob' => $row[4],
            'father_name' => $row[5],
            'father_occupation' => $row[6],
            'class' => $row[7],
            'reference' => $row[8],
            'isactive' => $row[9]
        ]);
    }
}
