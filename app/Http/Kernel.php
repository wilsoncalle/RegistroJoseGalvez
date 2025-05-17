<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // Tu middleware personalizado
        \App\Http\Middleware\AjaxDetectionMiddleware::class,
    ];

    // Otros arrays como $middlewareGroups y $routeMiddleware...
}
