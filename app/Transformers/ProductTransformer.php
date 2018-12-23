<?php

namespace App\Transformers;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Product $product
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'id'            =>  (int)$product->id,
            'title'         =>  (string)$product->name,
            'details'       =>  (string)$product->description,
            'stock'         =>  (int)$product->quantity,
            'situation'     =>  (string)$product->status,
            'picture'       =>  url("img/{$product->image}"),
            'seller'        =>  (int)$product->seller_id,
            'creationDate'  =>  (string)$product->created_at,
            'lastChange'    =>  (string)$product->updated_at,
            'deleteDate'    =>  isset($product->deleted_at) ? (string)$product->deleted_at : null,
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
            'title'         =>  'name',
            'details'       =>  'description',
            'stock'         =>  'quantity',
            'situation'     =>  'status',
            'picture'       =>  'image',
            'seller'        =>  'seller_id',
            'creationDate'  =>  'created_at',
            'lastChange'    =>  'updated_at',
            'deleteDate'    =>  'deleted_at',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
