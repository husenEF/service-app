<?php
namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\History;

class HistoryTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];

    protected $defaultIncludes = [];

    public function transform(History $history)
    {
        return [
            'id' => $history->id,
            'dataname' => $history->dataname,
            'status' => $history->status,
            'id_tires' => $history->id_tires,
            'id_vehicle' => $history->id_vehicle,
            'id_vehicle' => $history->id_vehicle,
            'posistion' => $history->posistion,
            'merek' => $history->merek,
            'buy_date' => $history->buy_date,
            'images' => $history->images,
        ];
    }
}
