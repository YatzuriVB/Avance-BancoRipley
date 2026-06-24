<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class CuentaAhorroController extends Controller
{
    public function show($pkcuentaahorro)
    {
        $pkcliente = auth()->user()->pkcliente;

        // Verificar que la cuenta pertenece al cliente (seguridad)
        $cuentaCheck = DB::table('dcuentaahorro')
            ->where('pkcuentaahorro', $pkcuentaahorro)
            ->where('pkcliente', $pkcliente)
            ->exists();

        if (!$cuentaCheck) {
            abort(403, 'No autorizado');
        }

        // Detalle completo de la cuenta (último registro diario)
        $cuenta = DB::table('dcuentaahorro as d')
            ->join('fcuentaahorro as f', 'f.pkcuentaahorro', '=', 'd.pkcuentaahorro')
            ->join('dtipocuentaahorro as tp', 'tp.pktipocuentaahorro', '=', 'f.pktipocuentaahorro')
            ->join('dproductoahorro as pa', 'pa.pkproductoahorro', '=', 'f.pkproductoahorro')
            ->join('destadocuenta as ec', 'ec.pkestadocuenta', '=', 'f.pkestadocuenta')
            ->join('dagencia as ag', 'ag.pkagencia', '=', 'f.pkagencia')
            ->join('dmoneda as m', 'm.pkmoneda', '=', 'f.pkmoneda')
            ->where('d.pkcuentaahorro', $pkcuentaahorro)
            ->select(
                'd.pkcuentaahorro',
                'd.codcuentaahorro',
                'f.montosaldodisponible_ac as saldo',
                'f.tasaefectivaanual as tea',
                'f.fechaaperturacuenta',
                'f.flag_ac',
                'f.flag_pf',
                'f.flag_cts',
                'f.flag_ap',
                'f.nrotitulares',
                'f.nrooperaciones_ac as nrooperaciones',
                'f.montosaldominimo_ac as saldo_minimo',
                'f.flagexoneracioncomision',
                'f.montodepositoapertura',
                'tp.destipocuentaahorro as tipo_cuenta',
                'pa.destipoproducto',
                'pa.destiposubproducto',
                'ec.desestadocuenta',
                'ag.desagencia',
                'm.simbolo as moneda',
                'm.desmoneda'
            )
            ->orderBy('f.periododia', 'desc')
            ->first();

        if (!$cuenta) {
            abort(404);
        }

        // Movimientos desde foperaciones (kardex)
        $movimientos = DB::table('foperaciones as op')
            ->join('dconceptooperacion as c', 'c.pkconceptooperacion', '=', 'op.pkconceptooperacion')
            ->join('dtipooperacion as t', 't.pktipooperacion', '=', 'op.pktipooperacion')
            ->where('op.pkcuentaahorro', $pkcuentaahorro)
            ->select(
                'op.fechahoraoperacion',
                'op.codtipoegresoingreso',
                'op.montooperacion',
                'op.montopagoconcepto',
                'c.desconceptooperacion',
                't.destipooperacion'
            )
            ->orderBy('op.fechahoraoperacion', 'desc')
            ->limit(50)
            ->get();

        // Datos del cliente para el sidebar
        $cliente = DB::table('dcliente')->where('pkcliente', $pkcliente)->first();

        return view('cuentas.detalle', compact('cuenta', 'movimientos', 'cliente'));
    }
    public function index()
    {
        $pkcliente = auth()->user()->pkcliente;
        $cliente = DB::table('dcliente')->where('pkcliente', $pkcliente)->first();

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

        return view('cuentas.index', compact('cliente', 'cuentas'));
    }
}
