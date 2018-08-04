<?php

namespace App\Http\Controllers\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Product $product)
    {
        $transaction = $product->transactions;

        return $this->showAll($transaction);
    }


}
