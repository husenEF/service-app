<?php

namespace App\Exports;

use Carbon\Traits\Date;
use App\Vehicle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VehicleExport implements FromCollection, WithHeadings, WithMapping
{
    public function headings(): array
    {
        return [
            'id',
            'Nama Creator',
            'Merek',
            "size",
            "platnumber",
        ];
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Vehicle::all();
    }
    public function map($vehicle): array
    {
        return [
            $vehicle->id,
            $vehicle->user->name,
            $vehicle->merek,
            $vehicle->size,
            $vehicle->platnumber,
            // Date::dateTimeToExcel($vehicle->created_at),
        ];
    }
}
