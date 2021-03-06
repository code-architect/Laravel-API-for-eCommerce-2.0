<?php

namespace App\Http\Controllers\Transaction;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class TransactionCategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']);
    }
    /**
     * Displaying all the categories for a specific transaction.
     *
     * @param Transaction $transaction
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Transaction $transaction)
    {
        $categories = $transaction->product->categories;

        return $this->showAll($categories);
    }


}
