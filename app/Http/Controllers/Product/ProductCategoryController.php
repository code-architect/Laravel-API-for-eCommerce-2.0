<?php

namespace App\Http\Controllers\Product;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductCategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']);
        $this->middleware('auth:api')->except(['index']);
    }
    /**
     * Display all categories of a specific product
     *
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Product $product)
    {
        $categories = $product->categories;

        return $this->showAll($categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Product $product, Category $category)
    {
        // to interact with manytomany relationships we can use attach, sync, syncWithoutDetach
        $product->categories()->syncWithoutDetaching([$category->id]);

        return $this->showAll($product->categories);
    }

    /**
     * Removing the relation from the pivot table
     *
     * @param  \App\Models\Product  $product
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product, Category $category)
    {
        // check if the category exists in the list of the categories of the product
        if(!$product->categories()->find($category->id)){
            return $this->errorResponse('This specified category is not a category of this product', 404);
        }

        $product->categories()->detach($category->id);
        return $this->showAll($product->categories);
    }
}
