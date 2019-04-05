<?php
namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\Tire;

class TireTransformer extends TransformerAbstract{
    protected $availableIncludes = [
    ];

    protected $defaultIncludes = [
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
        ];
    }
}