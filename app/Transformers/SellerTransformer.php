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
            'email'         =>  (string)$seller->email,
            'isVerified'    =>  (int)$seller->verified,
            'creationDate'  =>  (string)$seller->created_at,
            'lastChange'    =>  (string)$seller->updated_at,
            'deleteDate'    =>  isset($seller->deleted_at) ? (string)$seller->deleted_at : null,
        ];
    }


    /**
     * Preventing sort function from accessing the original names from database
     * @param $index
     * @return mixed|null
     */
    public static function originalAttribute($index)
    {
        $attribute = [
            'id'            =>  'id',
            'name'          =>  'name',
            'email'         =>  'email',
            'isVerified'    =>  'verified',
            'creationDate'  =>  'created_at',
            'lastChange'    =>  'updated_at',
            'deleteDate'    =>  'deleted_at',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
