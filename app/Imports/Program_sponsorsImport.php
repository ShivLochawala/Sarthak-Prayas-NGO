<?php

namespace App\Imports;

use App\Models\Program_sponsor;
use Maatwebsite\Excel\Concerns\ToModel;
//use Maatwebsite\Excel\Concerns\WithHeadingRow;

class Program_sponsorsImport implements ToModel
{
    public function model(array $row)
    {
        return new Program_sponsor([
            'program_id' => $row[0],
            'level_id'   => $row[1],
            'sponsor_id' => $row[2]
        ]);
    }
}
