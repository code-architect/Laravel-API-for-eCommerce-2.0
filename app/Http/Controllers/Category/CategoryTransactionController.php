<?php

namespace App\Http\Controllers\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of all the transaction under a category, In here we have to be sure to obtain only
     * those products which will have transactions.
     *
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Category $category)
    {
        $transactions = $category->products()->whereHas('transactions')
                                             ->with('transactions')
                                             ->get()
                                             ->pluck('transactions')
                                             ->collapse(); //collapsing all the collection into one

        return $this->showAll($transactions);
    }


}
