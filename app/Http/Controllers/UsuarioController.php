<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::where('activo', true)->get();
        return view('usuarios.index', compact('usuarios'));
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
            'name' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'ci' => 'required|unique:users',
            'placa' => 'required',
            'email' => 'required|email|unique:users',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'rol' => 'required|in:administrador,comandante,Sargento,policia',
        ]);

        try {
            $data = $request->all();
            
            // Procesar y guardar la foto si se proporcionó una
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $nombreFoto = time() . '_' . $request->ci . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('fotos'), $nombreFoto);
                $data['foto'] = 'fotos/' . $nombreFoto;
            }

            $usuario = User::create([
                'name' => $data['name'],
                'apellido_paterno' => $data['apellido_paterno'],
                'apellido_materno' => $data['apellido_materno'],
                'ci' => $data['ci'],
                'placa' => $data['placa'],
                'foto' => $data['foto'] ?? null,
                'email' => $data['email'],
                'password' => bcrypt($data['ci']), // Usando CI como contraseña por defecto
                'activo' => true,
            ]);

            // Asignar el rol al usuario
            $usuario->assignRole($data['rol']);

            return redirect()
                ->route('usuarios.index')
                ->with('success', 'Usuario creado correctamente');
        } catch (\Exception $e) {
            // Si hay un error, eliminar la foto si se subió
            if (isset($data['foto']) && file_exists(public_path($data['foto']))) {
                unlink(public_path($data['foto']));
            }

            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al crear el usuario: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $usuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'rol' => 'required',
        ]);
        $usuario->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Actualizar el rol utilizando syncRoles (elimina roles anteriores y asigna el nuevo)
        $usuario->syncRoles([$request->rol]);

        if ($request->filled('password')) {
            $usuario->update(['password' => bcrypt($request->password)]);
        }
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        $usuario->update(['activo' => false]);
        return redirect()->route('usuarios.inactivos')->with('success', 'Usuario desactivado correctamente');
    }

    /**
     * Display a listing of inactive users.
     */
    public function inactivos()
    {
        $usuarios = User::where('activo', false)->get();
        return view('usuarios.inactivos', compact('usuarios'));
    }

    /**
     * Reactivate the specified user.
     */
    public function activar($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->update(['activo' => true]);
        return redirect()->route('usuarios.index')->with('success', 'Usuario activado correctamente');
    }
}
