<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'dni'      => ['required', 'string', 'digits:8'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Verificar que el cliente exista en dcliente
        $cliente = DB::table('dcliente')
            ->where('numerodocumentoidentidad', $request->dni)
            ->first();

        if (!$cliente) {
            throw ValidationException::withMessages([
                'dni' => 'No encontramos un cliente registrado con ese DNI. Acércate a una agencia Ripley para abrir tu cuenta.',
            ]);
        }

        // Verificar que no tenga ya un usuario de homebanking
        $yaExiste = DB::table('usuarios_homebanking')
            ->where('pkcliente', $cliente->pkcliente)
            ->exists();

        if ($yaExiste) {
            throw ValidationException::withMessages([
                'dni' => 'Este cliente ya tiene una cuenta de banca por internet. Intenta iniciar sesión.',
            ]);
        }

        // Verificar que el username no esté tomado
        $usernameExiste = DB::table('usuarios_homebanking')
            ->whereRaw('LOWER(username) = ?', [strtolower($request->username)])
            ->exists();

        if ($usernameExiste) {
            throw ValidationException::withMessages([
                'username' => 'Ese nombre de usuario ya está en uso.',
            ]);
        }

        $pkusuario = DB::table('usuarios_homebanking')->insertGetId([
            'pkcliente' => $cliente->pkcliente,
            'username' => strtolower($request->username),
            'password_hash' => Hash::make($request->password),
            'activo' => 'S',
            'bloqueado' => 'N',
            'intentos_fallidos' => 0,
            'fecultactualizacion' => now(),
        ], 'pkusuario');

        Auth::loginUsingId($pkusuario);

        return redirect(route('dashboard', absolute: false));
    }
}