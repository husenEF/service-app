<?php

namespace App\Exports;

use App\Service;
use Maatwebsite\Excel\Concerns\FromCollection;

class ServiceExport implements FromCollection
{
    private $where;

    public function __construct(array $where)
    {
        $this->where = $where;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if (count($this->where) > 0) {
            $service = new Service();
            foreach ($this->where as $K => $v)
                $service->where($K, $v);
            return $service->get();
        } else {
            return Service::all();
        }
    }
}
