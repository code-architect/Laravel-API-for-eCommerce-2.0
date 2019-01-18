<?php

namespace App\Http\Controllers\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategorySellerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of all the sellers under a single category.
     *
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Category $category)
    {
        $sellers = $category->products()->with('seller')
                                        ->get()
                                        ->pluck('seller')
                                        ->unique('id')
                                        ->values();

        return $this->showAll($sellers);
    }


}
