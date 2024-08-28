<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Validator;

use App\Models\User;

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

        Validator::extend('check_username', function ($attribute, $value, $parameters, $validator) {
            $check = User::where('username', $value)->where('obsolete', '0')->first();

            if ($check) {
                return FALSE;
            } else {
                return TRUE;
            }
        });

        Validator::extend('check_email', function ($attribute, $value, $parameters, $validator) {
            $check = User::where('email', $value)->where('obsolete', '0')->first();

            if ($check) {
                return FALSE;
            } else {
                return TRUE;
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
