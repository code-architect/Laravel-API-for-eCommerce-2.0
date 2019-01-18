<?php

namespace App\Http\Controllers\Transaction;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class TransactionSellerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Displaying the Seller based on the transaction ID.
     *
     * @param Transaction $transaction
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Transaction $transaction)
    {
        $seller = $transaction->product->seller;

        return $this->showOne($seller);
    }


}
