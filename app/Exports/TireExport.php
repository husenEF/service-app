<?php

namespace App\Exports;

use App\Tire;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TireExport implements FromCollection, WithHeadings, WithMapping
{

    public function headings(): array
    {
        return [
            'id',
            'Merek Ban',
            'Ukuran Ban',
            "Nomor Ban",
            "Stempel ban",
            "Waktu Beli",
            // "Foto",
            "Creator"
        ];
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Tire::all();
    }
    public function map($tire): array
    {
        return [
            $tire->id,
            $tire->merek,
            $tire->ukuran,
            $tire->nomor,
            $tire->stempel,
            $tire->buy_date,
            // url("/media/tires/" + $tire->images),
            $tire->getUser->name,
            // Date::dateTimeToExcel($vehicle->created_at),
        ];
    }
}
