<?php

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
//use League\Fractal\
use App\Vehicle;

class VehicleItemTransformer extends TransformerAbstract
{

    protected $availableIncludes = [];
    protected $defaultIncludes = [
        'create_user', 'tire'
    ];

    public function transform(Vehicle $vehicle)
    {
        return [
            'id' => $vehicle->id,
            'platnumber' => $vehicle->platnumber,
            'merek' => $vehicle->merek,
        ];
    }

    public function includeCreateUser(Vehicle $vehicle)
    {
        return $this->item($vehicle->user, new UserTransformer());
    }

    public function includeTire(Vehicle  $vehicle)
    {
        return $this->collection($vehicle->tires, new TireTransformer());
    }
}
