<?php

namespace App\Http\Controllers\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryBuyerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Listing all unique buyers of a single category.
     *
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Category $category)
    {
        $buyers = $category->products()->whereHas('transactions')
                                        ->with('transactions.buyer')
                                        ->get()
                                        ->pluck('transactions')
                                        ->collapse()
                                        ->pluck('buyer')
                                        ->unique('id')
                                        ->values();

        return $this->showAll($buyers);
    }


}
