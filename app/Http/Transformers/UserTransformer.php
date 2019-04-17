<?php
namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\User;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];

    protected $defaultIncludes = [];

    /**
     * Turn User object into a generic array.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            "name" => $user->name,
            "roles" => $user->roles,
            "active" => ($user->active) ? true : false,
            "email" => $user->email
        ];
    }
}
