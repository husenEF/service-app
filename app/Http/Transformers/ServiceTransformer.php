<?php
namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Service;

class ServiceTransformer extends TransformerAbstract
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
    public function transform(Service $service)
    {
        return [
            "id" => $service->id
        ];
    }
}
