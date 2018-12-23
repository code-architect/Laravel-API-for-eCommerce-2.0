<?php

namespace App\Transformers;

use App\Models\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Transaction $transaction
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'id'            =>  (int)$transaction->id,
            'quantity'      =>  $transaction->quantity,
            'buyer'         =>  $transaction->buyer_id,
            'product'       =>  $transaction->product_id,
            'creationDate'  =>  (string)$transaction->created_at,
            'lastChange'    =>  (string)$transaction->updated_at,
            'deleteDate'    =>  isset($transaction->deleted_at) ? (string)$transaction->deleted_at : null,
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
            'quantity'      =>  'quantity',
            'buyer'         =>  'buyer_id',
            'product'       =>  'product_id',
            'creationDate'  =>  'created_at',
            'lastChange'    =>  'updated_at',
            'deleteDate'    =>  'deleted_at',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
