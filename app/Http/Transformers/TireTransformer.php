<?php
namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Tire;

class TireTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];

    protected $defaultIncludes = [
        // 'user', 'vehicle'
        // 'user'
    ];


    /**
     * Turn User object into a generic array.
     *
     * @return array
     */
    public function transform(Tire $tire)
    {
        return [
            'id' => $tire->id,
            "created_by" => $tire->created_by,
            "posistion" => $tire->posistion,
            "merek" => $tire->merek,
            "name" => $tire->merek,
            "ukuran_ban" => $tire->ukuran,
            "nomor_ban" => $tire->nomor,
            "stempel_ban" => $tire->stempel,
            "buy_date" => $tire->buy_date,
            "images_path" => ($tire->image !== "") ? url('media/tires/' . $tire->images) : "",
            "user" => $tire->getUser,
            "vehicle" => $tire->getVehicle,
            "afkir" => date("Y-m-d H:m:s", strtotime($tire->deleted_at))
        ];
    }

    public function includeUser(Tire $tire)
    {
        return $this->item($tire->getUser, new UserTransformer());
    }
    public function includeVehicle(Tire $tire)
    {
        return $this->item($tire->getVehicle, new VehicleTransformer());
    }
}
