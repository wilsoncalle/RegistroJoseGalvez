<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Mostrar el perfil del usuario autenticado.
     */
    public function show()
    {
        $usuario = Auth::user();
        return view('profile.show', compact('usuario'));
    }

    /**
     * Actualizar el perfil del usuario autenticado.
     */
    public function update(Request $request)
    {
        $usuario = Auth::user();
        
        $rules = [
            'nombre' => 'required|string|max:50',
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('usuarios')->ignore($usuario->id_usuario, 'id_usuario')],
        ];

        // Solo validar password si se está intentando cambiar
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        try {
            // Actualizar el usuario
            $usuario->nombre = $request->nombre;
            $usuario->email = $request->email;
            
            // Solo actualizar password si se proporcionó uno nuevo
            if ($request->filled('password')) {
                $usuario->password = Hash::make($request->password);
            }
            
            $usuario->save();

            return redirect()->route('profile.show')
                ->with('success', 'Perfil actualizado correctamente.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al actualizar perfil: ' . $e->getMessage());
        }
    }
}
