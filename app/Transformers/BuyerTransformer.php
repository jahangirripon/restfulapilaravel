<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Buyer;

class BuyerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Buyer $buyer)
    {
        return [
            'identifier' =>  (int) $buyer->id,
            'name' =>  (string) $buyer->name,
            'email' =>  (string) $buyer->email,
            'isVerified' =>  (int) $buyer->verified,
            'creationDate' => (string)$buyer->created_at,
            'lastChange' => (string)$buyer->updated_at,
            'deletedData' => isset($buyer->deleted_at) ? (string) $buyer->deleted_at : null,
        ];
    }
}
