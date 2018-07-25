<?php

namespace App\Http\Controllers\Buyer;

use App\Models\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerCategoryController extends ApiController
{
    /**
     * Display a listing of categories used by the specific buyer.
     * After pluck() we are getting multiple collection. So we are using collapse() method from Laravel
     * The collapse() method will create a single collection, using multiple collection we have.
     *
     * @param Buyer $buyer
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Buyer $buyer)
    {
        $categories = $buyer->transactions()->with('product.categories')
                                            ->get()
                                            ->pluck('product.categories')
                                            ->collapse()
                                            ->unique('id')
                                            ->values();

        return $this->showAll($categories);
    }


}
