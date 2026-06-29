<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class PasswordRecoveryController extends Controller
{
    public function showRequestForm(): View
    {
        return view('auth.forgot-password');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'dni' => ['required', 'digits:8'],
            'username' => ['required', 'string'],
        ]);

        $usuario = DB::table('usuarios_homebanking as u')
            ->join('dcliente as c', 'c.pkcliente', '=', 'u.pkcliente')
            ->where('c.numerodocumentoidentidad', $request->dni)
            ->whereRaw('LOWER(u.username) = ?', [strtolower($request->username)])
            ->select('u.pkusuario')
            ->first();

        if (!$usuario) {
            return back()->withErrors([
                'dni' => 'No encontramos una cuenta con ese DNI y nombre de usuario.',
            ])->withInput();
        }

        // Guardamos el pkusuario verificado en la sesión temporalmente
        session(['recovery_pkusuario' => $usuario->pkusuario]);

        return redirect()->route('password.reset.form');
    }

    public function showResetForm(): View|RedirectResponse
    {
        if (!session('recovery_pkusuario')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password');
    }

    public function reset(Request $request): RedirectResponse
    {
        if (!session('recovery_pkusuario')) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::table('usuarios_homebanking')
            ->where('pkusuario', session('recovery_pkusuario'))
            ->update([
                'password_hash' => Hash::make($request->password),
                'fecultactualizacion' => now(),
            ]);

        session()->forget('recovery_pkusuario');

        return redirect()->route('login')->with('status', 'Tu contraseña fue actualizada correctamente. Ya puedes iniciar sesión.');
    }

    public function showForceChangeForm(): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        $debeActualizar = DB::table('usuarios_homebanking')
            ->where('pkusuario', auth()->id())
            ->value('debe_cambiar_password');

        if ($debeActualizar !== 'S') {
            return redirect()->route('dashboard');
        }

        return view('auth.force-password-change');
    }

    public function forceChange(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()],
        ]);

        DB::table('usuarios_homebanking')
            ->where('pkusuario', auth()->id())
            ->update([
                'password_hash' => Hash::make($request->password),
                'debe_cambiar_password' => 'N',
                'fecultactualizacion' => now(),
            ]);

        return redirect()->route('dashboard')->with('success', 'Tu contraseña fue actualizada correctamente.');
    }
}