<?php
namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Vehicle;

class VehicleTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];

    protected $defaultIncludes = [
        'create_user', 'tire'
    ];

    /**
     * Turn User object into a generic array.
     *
     * @return array
     */
    public function transform(Vehicle  $vehicle)
    {
        return [
            'id' => $vehicle->id,
            'platnumber' => $vehicle->platnumber,
            // 'user'=>$vehicle->user_id,
            'update_by' => $vehicle->update_by,
            'created_by' => $vehicle->created_by,
            // 'userUpdate'=>$vehicle->userUpdate
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
