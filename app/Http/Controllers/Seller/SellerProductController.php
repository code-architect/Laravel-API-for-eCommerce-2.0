<?php

namespace App\Http\Controllers\Seller;

use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    /**
     * Listing all the products under a single seller
     *
     * @param Seller $seller
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;

        return $this->showAll($products);
    }


    /**
     * Store Method for creating new product under seller.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param User $seller
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, User $seller)
    {
        $rules = [
            'name'  => 'required',
            'description'  => 'required',
            'quantity'  => 'required|integer|min:1',
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        $data['status'] = Product::UNAVAILABLE_PRODUCT;
        $data['image'] = '1.jpg';   //TODO: change static image
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);

        return $this->showOne($product);
    }

    /**
     * Update the specified product of a specified seller based on id in storage.
     *
     * @param Product $product
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(Request $request, Seller $seller, Product $product)
    {
        $rules = [
            'quantity' => 'integer|min:1',
            'status' => 'in:'.Product::UNAVAILABLE_PRODUCT.','.Product::AVAILABLE_PRODUCT,
        ];

        $this->validate($request, $rules);

        $this->checkSeller($seller, $product);

        $product->fill($request->intersect([    // this ignore null or empty values
            'name',
            'description',
            'quantity',
            'image'
        ]));

        // check if status is there and if product has at least one category
        if($request->has('status')){
            $product->status = $request->status;
            if($product->isAvailable() && $product->categories()->count() == 0){
                return $this->errorResponse('An active product must have at least one category', 409);
            }
        }

        if($product->isClean()){
            return $this->errorResponse('You need to change some values to update', 422);
        }

        $product->save();

        return $this->showOne($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Seller $seller, Product $product)
    {
        $this->checkSeller($seller, $product);  // check if the seller is the product owner

        $product->delete();

        return $this->showOne($product);
    }


    //------------------------------------- Additional Methods --------------------------------------------//

    /**
     * Checks if the seller is the owner of the product
     *
     * @param Seller $seller
     * @param Product $product
     */
    protected function checkSeller(Seller $seller, Product $product)
    {
        if($seller->id != $product->seller_id){
            throw new HttpException(422, 'The specified seller is not the actual seller of the product');
        }
    }
}
