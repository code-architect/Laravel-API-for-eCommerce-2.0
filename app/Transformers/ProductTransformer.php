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
            'title'         =>  $product->name,
            'details'       =>  $product->description,
            'stock'         =>  $product->quantity,
            'situation'     =>  $product->status,
            'picture'       =>  url("img/{$product->image}"),
            'seller'        =>  (int)$product->seller_id,
            'creationDate'  =>  $product->created_at,
            'lastChange'    =>  $product->updated_at,
            'deleteDate'    =>  isset($product->deleted_at) ? (string)$product->deleted_at : null,
        ];
    }
}
