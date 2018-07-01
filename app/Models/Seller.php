<?php

namespace App\Models;


class Seller extends User
{
    // -------------------- Relations -----------------------------------------------//
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    // -----------------------------------------------------------------------------//
}
