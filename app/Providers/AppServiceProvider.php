<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\User;
use App\Mail\UserCreated;
use Illuminate\Support\Facades\Mail;
use App\Product;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        User::created(function($user) {

            retry(5, function() use ($user) {
                Mail::to($user)->send(new UserCreated($user));
            }, 100);
        });

        User::created(function($user) {
            if($user -> isDirty('email'))
            {
                Mail::to($user)->send(new UserMailChanged($user));
            }
        });

        Product::updated(function($product) {
            if($product->quantity == 0 && $product->isAvailable()) 
            {
                $product->status = Product::UNAVAILABLE_PRODUCT;

                $product->save();
            }
        });


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
