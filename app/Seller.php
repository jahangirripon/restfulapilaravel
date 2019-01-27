<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\SellerScope;
use App\Product;
use App\Transformers\SellerTransformer;

class Seller extends User
{   
    public $transformer = SellerTransformer::class;
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SellerScope);
    }


    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
