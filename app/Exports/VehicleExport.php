<?php

namespace App\Exports;

use App\Vehicle;
use Maatwebsite\Excel\Concerns\FromCollection;

class VehicleExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Vehicle::all();
    }
}
