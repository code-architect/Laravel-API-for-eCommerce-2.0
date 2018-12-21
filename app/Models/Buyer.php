<?php

namespace App\Models;

use App\Scopes\BuyerScope;
use App\Transformers\BuyerTransformer;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;

class Buyer extends User
{
    public $transformer = BuyerTransformer::class;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BuyerScope);
    }

    // -------------------- Relations -----------------------------------------------//
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    // -----------------------------------------------------------------------------//
}
