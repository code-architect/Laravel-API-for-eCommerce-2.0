<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerBuyerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Displaying all the buyers (unique) of a single seller.
     *
     * @param $seller $seller
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Seller $seller)
    {
        $categories = $seller->products()
                            ->whereHas('transactions')
                            ->with('transactions.buyer')
                            ->get()
                            ->pluck('transactions')
                            ->collapse()
                            ->pluck('buyer')
                            ->unique('id')
                            ->values();

        return $this->showAll($categories);
    }


}
