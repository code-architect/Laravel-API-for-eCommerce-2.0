<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerCategoryController extends ApiController
{
    /**
     * DListing all the categories under a single seller.
     *
     * @param $seller $seller
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Seller $seller)
    {
        $categories = $seller->products()->whereHas('categories')
                                         ->with('categories')
                                         ->get()
                                         ->pluck('categories')
                                         ->collapse()
                                         ->unique('id')
                                         ->values();

        return $this->showAll($categories);
    }


}
