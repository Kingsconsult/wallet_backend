<?php

namespace App\Imports;

use App\Models\StateLga;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StateLgaImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        // $states = $row['state'];
        // $lgas = $row['lga'];

        // dd($lgas);












        return new StateLga([
            'lgas'     => $row['lga'],
            'states'    => $row['state'] 
        ]);
    }
}
