<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use File;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (env('APP_ENV') == 'local') {
            DB::listen(function ($query) {
                File::append(
                    storage_path('/logs/query.log'),
                    '[' . date('Y-m-d H:i:s') . ']' . PHP_EOL .
                    $query->sql . ' [' . implode(', ', $query->bindings) . ']' .
                    PHP_EOL .
                    PHP_EOL
                );
            });
        }
    }
}
