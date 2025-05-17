<?php
// Archivo: app/Http/Controllers/Controller.php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Determina si la respuesta debe ser AJAX o completa
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $view  Vista a renderizar
     * @param  array  $data  Datos para la vista
     * @return mixed
     */
    protected function ajaxOrCompleteResponse(Request $request, $view, array $data = [])
    {
        // En peticiones AJAX, devolver solo la vista
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view($view, $data);
        }
        
        // En peticiones normales, devolver la vista completa
        return view($view, $data);
    }
    
    /**
     * Devuelve una respuesta adecuada después de realizar una acción
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $successMessage  Mensaje de éxito
     * @param  string  $redirectUrl  URL para redireccionar después de la acción
     * @return mixed
     */
    protected function ajaxOrRedirectResponse(Request $request, $successMessage, $redirectUrl)
    {
        // Guardar mensaje en sesión flash
        session()->flash('success', $successMessage);
        
        // En peticiones AJAX, enviar respuesta con redirección
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'redirect' => $redirectUrl
            ]);
        }
        
        // En peticiones normales, redireccionar con mensaje flash
        return redirect($redirectUrl)->with('success', $successMessage);
    }
}