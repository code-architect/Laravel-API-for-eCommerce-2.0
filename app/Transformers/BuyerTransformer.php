<?php

namespace App\Transformers;

use App\Models\Buyer;
use League\Fractal\TransformerAbstract;

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
            'id'            =>  (int)$buyer->id,
            'name'          =>  (string)$buyer->name,
            'email'         =>  $buyer->email,
            'isVerified'    =>  (int)$buyer->verified,
            'creationDate'  =>  (string)$buyer->created_at,
            'lastChange'    =>  (string)$buyer->updated_at,
            'deleteDate'    =>  isset($buyer->deleted_at) ? (string)$buyer->deleted_at : null,

            'links'         =>  [
                [
                    'rel'   =>  'self',
                    'href'  =>  route('buyers.show', $buyer->id)
                ],
                [
                    'rel'   =>  'categories',
                    'href'  =>  route('buyers.categories.index', $buyer->id)
                ],
                [
                    'rel'   =>  'products',
                    'href'  =>  route('buyers.products.index', $buyer->id)
                ],
                [
                    'rel'   =>  'sellers',
                    'href'  =>  route('buyers.sellers.index', $buyer->id)
                ],
                [
                    'rel'   =>  'transactions',
                    'href'  =>  route('buyers.transactions.index', $buyer->id)
                ],
            ],

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
