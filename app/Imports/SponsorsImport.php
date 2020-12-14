<?php

namespace App\Imports;

use App\Models\Sponsor;
use Maatwebsite\Excel\Concerns\ToModel;
//use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SponsorsImport implements ToModel
{
    
    public function model(array $row)
    {
        return new Sponsor([
            'name' => $row[0],
            'address' => $row[1],
            'mobile_no1' => $row[2],
            'mobile_no2' => $row[3],
            'email_id' => $row[4],
            'dob' => $row[5],
            'reference' => $row[6],
            'isactive' => $row[7]
        ]);
    }
}
