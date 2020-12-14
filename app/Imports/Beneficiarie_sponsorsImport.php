<?php

namespace App\Imports;

use App\Models\Beneficiarie_sponsor;
use Maatwebsite\Excel\Concerns\ToModel;

class Beneficiarie_sponsorsImport implements ToModel
{
   
    public function model(array $row)
    {
        return new Beneficiarie_sponsor([
            'beneficiarie_id' => $row[0],
            'sponsor_id'   => $row[1],
            'flag' => $row[2]
        ]);
    }
}
