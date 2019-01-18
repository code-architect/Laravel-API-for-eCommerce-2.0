<?php

namespace App\Http\Controllers\Buyer;

use App\Models\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerProductController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display all the products bought by the buyer, if remove the pluck() method we will get full details
     * In here we are using eager loading to obtain directly the product with in every transaction
     *
     * @param Buyer $buyer
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Buyer $buyer)
    {
        $products = $buyer->transactions()->with('product')
                            ->get()
                            ->pluck('product');

        return $this->showAll($products);
    }


}
