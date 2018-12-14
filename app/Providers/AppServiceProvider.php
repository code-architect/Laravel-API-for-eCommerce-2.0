<?php

namespace App\Providers;

use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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

        // Event listener for sending user verification email for creating an account
        User::created(function($user){
            // retry to send mail 5 times every 1 second
            retry(5, function() use($user) {
                Mail::to($user)->send(new UserCreated($user));
            }, 100);
        });

        // Event listener for sending verification email to use when user updates it's email
        User::updated(function($user){
            if($user->isDirty('email')){
                // retry to send mail 5 times every 1 second
                retry(5, function() use($user) {
                    Mail::to($user)->send(new UserMailChanged($user));
                }, 100);
            }
        });


        Product::updated(function($product){
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
