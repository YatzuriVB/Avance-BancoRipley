<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class GestionCuentaController extends Controller
{
    public function index(Request $request)
    {
        if (!Session::get('core_logged_in')) {
            return redirect()->route('core.login');
        }

        $dniQuery = $request->input('dni');
        $cuentas = collect();

        if ($dniQuery) {
            $cuentas = DB::table('dcuentaahorro as d')
                ->join('fcuentaahorro as f', 'f.pkcuentaahorro', '=', 'd.pkcuentaahorro')
                ->join('dcliente as c', 'c.pkcliente', '=', 'f.pkcliente')
                ->join('destadocuenta as e', 'e.pkestadocuenta', '=', 'f.pkestadocuenta')
                ->where('c.numerodocumentoidentidad', $dniQuery)
                ->select(
                    'd.pkcuentaahorro',
                    'd.codcuentaahorro',
                    'c.nomcliente',
                    'c.numerodocumentoidentidad',
                    'f.montosaldodisponible_ac',
                    'e.pkestadocuenta',
                    'e.desestadocuenta',
                    'f.periododia'
                )
                ->orderByDesc('f.periododia')
                ->get()
                ->unique('pkcuentaahorro');
        }

        return view('core.cuentas.gestion', compact('cuentas', 'dniQuery'));
    }

    public function cambiarEstado(Request $request, $pkcuentaahorro)
    {
        if (!Session::get('core_logged_in')) {
            return redirect()->route('core.login');
        }

        $request->validate([
            'nuevo_estado' => 'required|in:01,02,03,04',
            'dni' => 'required',
        ]);

        $pkestado = DB::table('destadocuenta')
            ->where('codestadocuenta', $request->nuevo_estado)
            ->value('pkestadocuenta');

        $ultimoPeriodo = DB::table('fcuentaahorro')
            ->where('pkcuentaahorro', $pkcuentaahorro)
            ->max('periododia');

        DB::table('fcuentaahorro')
            ->where('pkcuentaahorro', $pkcuentaahorro)
            ->where('periododia', $ultimoPeriodo)
            ->update([
                'pkestadocuenta' => $pkestado,
                'fecultactualizacion' => now(),
            ]);

        $estadoTexto = DB::table('destadocuenta')->where('pkestadocuenta', $pkestado)->value('desestadocuenta');

        return redirect()->route('core.cuentas.gestion', ['dni' => $request->dni])
            ->with('success', "Cuenta actualizada al estado: {$estadoTexto}");
    }
}