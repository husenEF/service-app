<?php

namespace App\Exports;

use App\Service;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ServiceExport implements FromCollection, WithHeadings, WithMapping
{
    private $where;
    private $debug = [];

    public function __construct(array $where)
    {
        $this->where = $where;
    }

    public function headings(): array
    {

        return [
            'id',
            'Merek Ban',
            'Waktu Beli Ban',
            "Kendaraan",
            "Tekanan Angin",
            "Tebal Tapak",
            "Posisi",
            "Jarak Km",
            "Catatan",
            "Kelainan",
            "Lepas Ban",
            "Alasan Lepas",
            "Foto",
            "Dibuat Tanggal",
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {        
        if (isset($this->where["tire_id"]) && !isset($this->where["kendaraan"])) {
            $returnData = Service::where("tire_id", $this->where["tire_id"])->get();
            return $returnData;
        } else if (!isset($this->where["tire_id"]) && isset($this->where["kendaraan"])) {
            $returnData = Service::where("kendaraan", $this->where["kendaraan"])->get();
            return $returnData;
        } else if (isset($this->where["tire_id"]) && isset($this->where["kendaraan"])) {
            $returnData = Service::where("kendaraan", "=", $this->where["kendaraan"])->where("tire_id", "=", $this->where["tire_id"])->get();
            return $returnData;
        } else if (isset($this->where['check_date'])) {
            $returnData = Service::where('create_date', $this->where['check_date'])
            ->with(['getUser', 'tire', 'vehicle'])
            ->get();
            return $returnData;
        } else {
            $returnData = Service::all();
            return $returnData;
        }
    }

    public function map($service): array
    {
        $lepasBan = ($service->lepasban) ? "Ya" : "Tidak";
        // dd($service);

        return [
            $service->id,
            $service->tire->merek,
            date("d-m-Y H:m:s", strtotime($service->tire->buy_date)),
            $service->vehicle->merek,
            $service->tekanan_angin,
            $service->tebal_tapak,
            $service->posisi,
            $service->jarakkm,
            $service->catatan,
            $service->kelainan,
            $lepasBan,
            $service->alasanlepas,
            $service->image,
            date("d-m-Y H:m:s", strtotime($service->created_at)),
            // json_encode($this->debug),
            // json_encode($this->where)
            // Date::dateTimeToExcel($vehicle->created_at),
        ];
    }
}
