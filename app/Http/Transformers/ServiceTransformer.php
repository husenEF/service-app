<?php
namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Service;

class ServiceTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];

    protected $defaultIncludes = [
        'user', 'vehicle',
        'tire'
    ];


    /**
     * Turn User object into a generic array.
     *
     * @return array
     */
    public function transform(Service $service)
    {
        return [
            "id" => $service->id,
            "tekanan_angin" => $service->tekanan_angin,
            "tebal_tapak" => $service->tebal_tapak,
            "posisi" => $service->posisi,
            "jarakkm" => $service->jarakkm,
            "catatan" => $service->catatan,
            "kelainan" => $service->kelainan,
            "lepasban" => $service->lepasban,
            "alasanlepas" => $service->alasanlepas,
            "create_at" => date("Y-m-d H:m:s", strtotime($service->created_at)),
            "image" => url("media/service/" . $service->image)
            // "user" => $service->getUser
            // "raw" => $service
        ];
    }

    public function includeUser(Service $service)
    {
        return $this->item($service->getUser, new UserTransformer());
    }

    public function includeVehicle(Service $service)
    {
        return $this->item($service->vehicle, new VehicleTransformer());
    }

    public function includeTire(Service $service)
    {
        return $this->item($service->tire, new TireTransformer());
    }
}
