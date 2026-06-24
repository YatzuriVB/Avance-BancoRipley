<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $pkcliente = auth()->user()->pkcliente;

        // Datos del cliente
        $cliente = DB::table('dcliente')
            ->where('pkcliente', $pkcliente)
            ->first();

        // Cuentas de ahorro
        $cuentas = DB::table('fcuentaahorro as f')
            ->join('dcuentaahorro as d', 'd.pkcuentaahorro', '=', 'f.pkcuentaahorro')
            ->where('f.pkcliente', $pkcliente)
            ->select(
                'd.pkcuentaahorro',
                'd.codcuentaahorro',
                'f.montosaldodisponible_ac as saldo',
                'f.tasaefectivaanual as tea',
                'f.fechaaperturacuenta'
            )
            ->get();

        // Saldo total
        $saldoTotal = $cuentas->sum('saldo');

        // Créditos activos
        $creditos = DB::table('fagcuentacredito as f')
            ->join('dcuentacredito as d', 'd.pkcuentacredito', '=', 'f.pkcuentacredito')
            ->join('destadocredito as e', 'e.pkestadocredito', '=', 'f.pkestadocredito')
            ->where('d.pkcliente', $pkcliente)
            ->select(
                'd.pkcuentacredito',
                'd.codcuentacredito',
                'f.montoaprobadocredito',
                'f.montocapitalpagado',
                'e.desestadocredito',
                'f.pkestadocredito'
            )
            ->limit(3)
            ->get();

        // Próxima cuota pendiente
        $proximaCuota = DB::table('fplanpagomes')
            ->where('pkcliente', $pkcliente)
            ->where('codestadocuota', '01')
            ->where('fechavencimientopagocuota', '>=', now()->toDateString())
            ->orderBy('fechavencimientopagocuota')
            ->select('montocuota', 'fechavencimientopagocuota', 'pkcuentacredito')
            ->first();

        // Últimas operaciones del cronograma
        $movimientos = DB::table('fplanpagomes')
            ->where('pkcliente', $pkcliente)
            ->orderBy('fechavencimientopagocuota', 'desc')
            ->select(
                'nrocuota',
                'montocuota',
                'montocapitalpagado',
                'fechavencimientopagocuota',
                'fechapagocuota',
                'codestadocuota',
                'pkcuentacredito'
            )
            ->limit(4)
            ->get();

        return view('dashboard', compact(
            'cliente',
            'cuentas',
            'saldoTotal',
            'creditos',
            'proximaCuota',
            'movimientos'
        ));
    }
}