<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Category extends Model
{
    protected $fillable = ['name', 'description'];



    // -------------------- Relations -----------------------------------------------//
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
    // -----------------------------------------------------------------------------//
}
