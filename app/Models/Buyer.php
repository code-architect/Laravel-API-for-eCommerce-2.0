<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;

class Buyer extends User
{

    // -------------------- Relations -----------------------------------------------//
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    // -----------------------------------------------------------------------------//
}
