<?php
namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\History;

class HistoryTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];

    protected $defaultIncludes = [
        'user'
    ];

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
            'created_at' => date("Y-m-d H:m:s", strtotime($history->created_at)),
            'vehicle' => [
                "merek" => $history->vehicle->merek,
                "platnumber" => $history->vehicle->platnumber,
                "id" => $history->vehicle->id
            ]
        ];
    }

    public function includeUser(History $history)
    {
        return $this->item($history->user, new UserTransformer());
    }
}
