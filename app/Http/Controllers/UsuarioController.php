<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtener el usuario actual
        $usuarioActual = Auth::user();
        
        // Filtros
        $busqueda = $request->input('busqueda');
        $filtroEstado = $request->input('estado');

        // Consulta base
        $query = User::query();
        
        // Aplicar filtros de búsqueda si se proporcionan
        if ($busqueda) {
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'like', "%$busqueda%")
                  ->orWhere('email', 'like', "%$busqueda%");
            });
        }
        
        // Aplicar filtro de estado si se proporciona
        if ($filtroEstado !== null) {
            $query->where('activo', $filtroEstado);
        }
        
        // Ordenar por nombre
        $query->orderBy('nombre');
        
        // Ejecutar la consulta y paginar los resultados
        $usuarios = $query->paginate(15);

        return view('usuarios.index', compact('usuarios', 'busqueda', 'filtroEstado', 'usuarioActual'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'email' => 'required|string|email|max:100|unique:usuarios,email',
            'password' => 'required|string|min:8|confirmed',
            'activo' => 'boolean',
        ]);
        
        try {
            DB::beginTransaction();

            // Crear el usuario
            $usuario = new User();
            $usuario->nombre = $request->nombre;
            $usuario->email = $request->email;
            $usuario->password = Hash::make($request->password);
            $usuario->activo = $request->has('activo');
            $usuario->save();

            DB::commit();

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario registrado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al registrar usuario: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $usuario)
    {
        // Verificar si el usuario actual está intentando ver su propio perfil
        if (Auth::id() !== $usuario->id_usuario) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No tienes permiso para ver los detalles de otro usuario.');
        }
        
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $usuario)
    {
        // Verificar si el usuario actual está intentando editar su propio perfil
        if (Auth::id() !== $usuario->id_usuario) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No tienes permiso para editar otro usuario.');
        }
        
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        $rules = [
            'nombre' => 'required|string|max:50',
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('usuarios')->ignore($usuario->id_usuario, 'id_usuario')],
            'activo' => 'boolean',
        ];

        // Solo validar password si se está intentando cambiar
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            // Actualizar el usuario
            $usuario->nombre = $request->nombre;
            $usuario->email = $request->email;
            $usuario->activo = $request->has('activo');
            
            // Solo actualizar password si se proporcionó uno nuevo
            if ($request->filled('password')) {
                $usuario->password = Hash::make($request->password);
            }
            
            $usuario->save();

            DB::commit();

            return redirect()->route('usuarios.show', $usuario)
                ->with('success', 'Usuario actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar usuario: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $usuario)
    {
        // Verificar si el usuario actual está intentando eliminar su propio perfil
        if (Auth::id() !== $usuario->id_usuario) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No tienes permiso para eliminar otro usuario.');
        }
        
        // Validar la contraseña
        $request->validate([
            'password_confirmation' => 'required',
        ]);
        
        // Verificar que la contraseña sea correcta
        if (!Hash::check($request->password_confirmation, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password_confirmation' => ['La contraseña proporcionada no coincide con nuestros registros.'],
            ]);
        }
        
        try {
            $usuario->delete();
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')
                ->with('success', 'Tu cuenta ha sido eliminada correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar usuario: ' . $e->getMessage());
        }
    }

    /**
     * Cambiar el estado del usuario (activar/desactivar)
     */
    public function cambiarEstado(User $usuario)
    {
        // Verificar si el usuario actual está intentando cambiar el estado de su propio perfil
        if (Auth::id() !== $usuario->id_usuario) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No tienes permiso para cambiar el estado de otro usuario.');
        }
        
        try {
            $usuario->activo = !$usuario->activo;
            $usuario->save();
            
            $estado = $usuario->activo ? 'activado' : 'desactivado';
            return redirect()->route('usuarios.index')
                ->with('success', "Tu cuenta ha sido {$estado} correctamente.");
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cambiar estado de tu cuenta: ' . $e->getMessage());
        }
    }
}
