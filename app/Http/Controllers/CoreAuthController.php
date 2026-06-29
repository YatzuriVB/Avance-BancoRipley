<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CoreAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('core.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'dni' => 'required|numeric|digits:8',
        ], [
            'dni.required' => 'El DNI es obligatorio.',
            'dni.digits'   => 'El DNI debe tener 8 dígitos.',
        ]);

        $throttleKey = 'core-login|' . $request->dni . '|' . $request->ip();

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'dni' => "Demasiados intentos. Intenta de nuevo en {$seconds} segundos.",
            ])->withInput();
        }

        $personal = DB::table('dpersonal as p')
            ->leftJoin('dpersonalasesor as pa', 'pa.pkpersonal', '=', 'p.pkpersonal')
            ->leftJoin('dasesor as a', 'a.pkasesor', '=', 'pa.pkasesor')
            ->leftJoin('dpersonalcargo as pc', 'pc.pkpersonal', '=', 'p.pkpersonal')
            ->leftJoin('dcargopersonal as cp', 'cp.pkcargopersonal', '=', 'pc.pkcargopersonal')
            ->where('p.numerodni', $request->dni)
            ->where('p.estadopersonal', '1')
            ->select(
                'p.pkpersonal',
                'p.codpersonal',
                'p.nombre',
                'a.pkasesor',
                'a.codasesor',
                'a.nomasesor',
                'cp.descargopersonal'
            )
            ->first();

        if (!$personal) {
            \Illuminate\Support\Facades\RateLimiter::hit($throttleKey, 60);
            return back()->withErrors(['dni' => 'El DNI ingresado no corresponde a un empleado activo en el sistema.'])->withInput();
        }

        \Illuminate\Support\Facades\RateLimiter::clear($throttleKey);

        Session::put('core_logged_in', true);
        Session::put('core_pkpersonal', $personal->pkpersonal);
        Session::put('core_pkasesor', $personal->pkasesor);
        Session::put('core_nombre', $personal->nombre);
        Session::put('core_codasesor', $personal->codasesor);
        Session::put('core_cargo', $personal->descargopersonal);

        return redirect()->route('core.dashboard');
    }

    public function logout()
    {
        Session::forget(['core_logged_in', 'core_pkpersonal', 'core_pkasesor', 'core_nombre', 'core_codasesor']);
        return redirect()->route('core.login');
    }
}
