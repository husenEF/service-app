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
            'Pengguna',
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
        \DB::enableQueryLog();
        // if (count($this->where) > 0) {
        //     $service = new Service();
        //     foreach ($this->where as $K => $v)
        //         $service->where($K, $v);
        //     $serviceReturn = $service->get();
        //     $query = \DB::getQueryLog();
        //     $this->debug = $query;
        //     // return $service->get();
        //     return $serviceReturn;
        // } else {

        //     $serviceReturn = Service::all();
        //     $query = \DB::getQueryLog();
        //     $this->debug = $query;
        //     // return Service::all();
        //     return $serviceReturn;
        // }
        if (isset($this->where["tire_id"]) && !isset($this->where["kendaraan"])) {
            $returnData = Service::where("tire_id", $this->where["tire_id"])->get();
            $this->debug = \DB::getQueryLog();
            return $returnData;
        } else if (!isset($this->where["tire_id"]) && isset($this->where["kendaraan"])) {
            $returnData = Service::where("kendaraan", $this->where["kendaraan"])->get();
            $this->debug = \DB::getQueryLog();
            return $returnData;
        } else if (isset($this->where["tire_id"]) && isset($this->where["kendaraan"])) {
            $returnData = Service::where("kendaraan", "=", $this->where["kendaraan"])->where("tire_id", "=", $this->where["tire_id"])->get();
            $this->debug = \DB::getQueryLog();
            return $returnData;
        } else {
            $returnData = Service::all();
            $this->debug = \DB::getQueryLog();
            return $returnData;
        }
        // $service = new Service();
        // if (isset($this->where["tire_id"]))
        //     $service->where("tire_id", $this->where["tire_id"]);
        // if (isset($this->where["kendaraan"]))
        //     $service->where("kendaraan", $this->where["kendaraan"]);
        // // foreach ($this->where as $K => $v)
        // //     $service->where($K, $v);
        // $serviceReturn = $service->get();
        // $query = \DB::getQueryLog();
        // $this->debug = $query;
        // // return $service->get();
        // return $serviceReturn;
    }

    public function map($service): array
    {
        $lepasBan = ($service->lepasban) ? "Ya" : "Tidak";
        
        return [
            $service->id,
            $service->tire->merek,
            $service->getUser->name,
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
