<?php

namespace App\Http\Controllers\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductBuyerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of buyers of a specific product.
     *
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Product $product)
    {
        $buyers = $product->transactions()->with('buyer')
                                          ->get()
                                          ->pluck('buyer')
                                          ->unique('id')
                                          ->values();
        return $this->showAll($buyers);
    }


}
