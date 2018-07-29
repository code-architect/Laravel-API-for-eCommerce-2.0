<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = ['name', 'description'];

    protected $hidden = ['pivot'];

    // -------------------- Relations -----------------------------------------------//
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
    // -----------------------------------------------------------------------------//
}
