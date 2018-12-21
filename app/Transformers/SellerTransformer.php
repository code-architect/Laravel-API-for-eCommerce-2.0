<?php

namespace App\Transformers;

use App\Models\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Seller $seller)
    {
        return [
            'id'            =>  (int)$seller->id,
            'name'          =>  (string)$seller->name,
            'email'         =>  $seller->email,
            'isVerified'    =>  (int)$seller->verified,
            'creationDate'  =>  $seller->created_at,
            'lastChange'    =>  $seller->updated_at,
            'deleteDate'    =>  isset($seller->deleted_at) ? (string)$seller->deleted_at : null,
        ];
    }
}
