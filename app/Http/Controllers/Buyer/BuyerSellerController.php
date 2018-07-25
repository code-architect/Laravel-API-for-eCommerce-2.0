<?php

namespace App\Http\Controllers\Buyer;

use App\Models\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerSellerController extends ApiController
{
    /**
     * Display a listing of the sellers the buyers bought from.
     * In here based on the relation the transaction has no direct relation with seller
     * so we use eager loading with a nested relationship. Then use pluck() in same manner.
     * Then to obtain all the unique seller details we use Laravel values to recreate the index of the collection.
     * Because the unique() method is going to remove any repeated seller and that spot is going to be empty, hence we
     * use the Values()
     *
     * @param Buyer $buyer
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Buyer $buyer)
    {
        $sellers = $buyer->transactions()->with('product.seller')
                                         ->get()
                                         ->pluck('product.seller')
                                         ->unique('id')
                                         ->values();

        return $this->showAll($sellers);
    }


}
