<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Aplicar middleware para proteção de arquivos privados
        Route::pattern('path', '[a-zA-Z0-9-_/.]+');
        Route::get('private/{path}', function ($path) {
            return response()->file(storage_path('app/private/' . $path));
        })->middleware('protect.files')->where('path', '.*');
    }
}
