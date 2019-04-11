<?php
namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\History;

class HistoryTransformer extends TransformerAbstract{
    protected $availableIncludes = [];

    protected $defaultIncludes = [
        
    ];

    public function transform(History $history)
    {
        return [
            'id' => $history->id,
            'dataname'=>$history->dataname,
            'comment'=>$history->comment,
            'raw'=>unserialize($history->raw),
            'user'=>$history->update_by
        ];
    }
}