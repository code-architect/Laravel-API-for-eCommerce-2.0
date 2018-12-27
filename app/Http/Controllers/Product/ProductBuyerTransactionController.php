<?php

namespace App\Http\Controllers\Product;

use App\Models\Product;
use App\Models\Seller;
use App\Models\Transaction;
use App\Models\User;
use App\Transformers\TransactionTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends ApiController
{

    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:'. TransactionTransformer::class)->only(['store']);
    }

    /**
     * Transaction of a product between Buyer and Seller
     *
     * @param  \Illuminate\Http\Request  $request
     * @param $product
     * @param $buyer
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Product $product, User $buyer)
    {
        $rules = [
            'quantity'  => 'required|integer|min:1'
        ];

        $this->validate($request, $rules);

        // first we need to be sure that the seller is different then the buyer
        if($buyer->id == $product->seller_id)
        {
            return $this->errorResponse('The buyer must be different from the seller', 409);
        }

        // check if the user is verified or not
        if(!$buyer->isVerified())
        {
            return $this->errorResponse('The buyer must be a verified user', 409);
        }

        // check if the seller is verified
        if(!$product->seller->isVerified())
        {
            return $this->errorResponse('The buyer must be a verified user', 409);
        }

        // check if the product is available
        if(!$product->isAvailable())
        {
            return $this->errorResponse('The product is not available', 409);
        }

        // check if the quantity is not grater then the available quantity
        if($product->quantity < $request->quantity)
        {
            return $this->errorResponse('The product does not have enough quantity for transaction', 409);
        }

        return DB::transaction(function() use ($request, $product, $buyer){
            $product->quantity -= $request->quantity;
            $product->save();   // this will reduce the quantity of the product according to the request

            $transaction = Transaction::create([
                'quantity'  =>  $request->quantity,
                'buyer_id'  =>  $buyer->id,
                'product_id'=>  $product->id,
            ]);

            return $this->showOne($transaction, 201);
        });
    }


}
