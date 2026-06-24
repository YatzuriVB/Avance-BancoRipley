<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    public function index()
    {
        $pkcliente = auth()->user()->pkcliente;

        $cliente = DB::table('dcliente as c')
            ->leftJoin('dtipodocumentoidentidad as td', 'td.pktipodocumentoidentidad', '=', 'c.pktipodocumentoidentidad')
            ->leftJoin('dubigeo as u', 'u.pkubigeo', '=', 'c.pkubigeo')
            ->where('c.pkcliente', $pkcliente)
            ->select(
                'c.pkcliente',
                'c.codcliente',
                'c.nomcliente',
                'c.email',
                'c.numerotelefonopersonal',
                'c.telefono',
                'c.numerodocumentoidentidad',
                'c.destipodocumentoidentidad',
                'c.fechanacimiento',
                'c.sexo',
                'c.estadocivil',
                'c.montodeingreso',
                'c.fechaingresocaja',
                'td.destipodocumentoidentidad as tipodoc',
                'u.desdistrito',
                'u.desprovincia',
                'u.desdepartamento'
            )
            ->first();

        $usuario = auth()->user();

        return view('perfil.index', compact('cliente', 'usuario'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_actual'   => 'required|string',
            'password_nuevo'    => 'required|string|min:8|confirmed',
        ], [
            'password_actual.required'   => 'La contraseña actual es requerida.',
            'password_nuevo.required'    => 'La nueva contraseña es requerida.',
            'password_nuevo.min'         => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password_nuevo.confirmed'   => 'Las contraseñas no coinciden.',
        ]);

        $usuario = auth()->user();

        if (!Hash::check($request->password_actual, $usuario->password_hash)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual no es correcta.'])->withInput();
        }

        DB::table('usuarios_homebanking')
            ->where('pkusuario', $usuario->pkusuario)
            ->update([
                'password_hash'       => Hash::make($request->password_nuevo),
                'fecultactualizacion' => now(),
            ]);

        return back()->with('success', '¡Contraseña actualizada correctamente!');
    }
}
