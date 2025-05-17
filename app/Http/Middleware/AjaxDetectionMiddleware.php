<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AjaxDetectionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Procesar la solicitud normalmente
        $response = $next($request);

        // Si es una solicitud AJAX y la respuesta es una vista completa
        if ($request->ajax() && $request->header('X-Requested-With') == 'XMLHttpRequest') {
            // Verificar que la respuesta sea de tipo vista
            if (method_exists($response, 'getContent')) {
                // Devolver la respuesta normal (Laravel ya maneja esto correctamente)
                return $response;
            }
        }

        return $response;
    }
}
