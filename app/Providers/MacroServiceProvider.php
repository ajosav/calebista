<?php

namespace App\Providers;

use App\Macros\HttpMacro;
use App\Macros\ResponseMacro;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        Response::mixin(new ResponseMacro);
        Http::mixin(new HttpMacro);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
