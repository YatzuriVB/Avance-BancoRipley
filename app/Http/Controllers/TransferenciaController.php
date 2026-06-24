<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferenciaController extends Controller
{
    public function index()
    {
        $pkcliente = auth()->user()->pkcliente;

        // Cuentas propias con saldo
        $cuentas = DB::table('dcuentaahorro as d')
            ->join('fcuentaahorro as f', 'f.pkcuentaahorro', '=', 'd.pkcuentaahorro')
            ->join('dtipocuentaahorro as tp', 'tp.pktipocuentaahorro', '=', 'f.pktipocuentaahorro')
            ->where('d.pkcliente', $pkcliente)
            ->select(
                'd.pkcuentaahorro',
                'd.codcuentaahorro',
                'f.montosaldodisponible_ac as saldo',
                'tp.destipocuentaahorro as tipo'
            )
            ->orderBy('f.periododia', 'desc')
            ->get()
            ->unique('pkcuentaahorro');

        $cliente = DB::table('dcliente')->where('pkcliente', $pkcliente)->first();

        // Últimas transferencias realizadas
        $historial = DB::table('foperaciones as op')
            ->join('dconceptooperacion as c', 'c.pkconceptooperacion', '=', 'op.pkconceptooperacion')
            ->join('dcuentaahorro as d', 'd.pkcuentaahorro', '=', 'op.pkcuentaahorro')
            ->where('d.pkcliente', $pkcliente)
            ->where('op.codtipoegresoingreso', 'E')
            ->select(
                'op.fechahoraoperacion',
                'op.montooperacion',
                'd.codcuentaahorro',
                'c.desconceptooperacion'
            )
            ->orderBy('op.fechahoraoperacion', 'desc')
            ->limit(5)
            ->get();

        return view('transferencias.index', compact('cuentas', 'cliente', 'historial'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cuenta_origen'  => 'required|integer',
            'cuenta_destino' => 'required|integer|different:cuenta_origen',
            'monto'          => 'required|numeric|min:1',
            'descripcion'    => 'nullable|string|max:100',
        ], [
            'cuenta_destino.different' => 'La cuenta destino debe ser diferente a la cuenta origen.',
            'monto.min'                => 'El monto mínimo es S/ 1.00',
        ]);

        $pkcliente = auth()->user()->pkcliente;

        // Verificar que ambas cuentas pertenecen al cliente
        $cuentaOrigen = DB::table('dcuentaahorro as d')
            ->join('fcuentaahorro as f', 'f.pkcuentaahorro', '=', 'd.pkcuentaahorro')
            ->where('d.pkcuentaahorro', $request->cuenta_origen)
            ->where('d.pkcliente', $pkcliente)
            ->select('f.montosaldodisponible_ac as saldo', 'f.pkagencia', 'f.pkmoneda')
            ->orderBy('f.periododia', 'desc')
            ->first();

        $cuentaDestino = DB::table('dcuentaahorro')
            ->where('pkcuentaahorro', $request->cuenta_destino)
            ->where('pkcliente', $pkcliente)
            ->first();

        if (!$cuentaOrigen || !$cuentaDestino) {
            return back()->withErrors(['cuenta_origen' => 'Cuenta no válida.'])->withInput();
        }

        if ($request->monto > $cuentaOrigen->saldo) {
            return back()->withErrors(['monto' => 'Saldo insuficiente en la cuenta origen.'])->withInput();
        }

        // Obtener catálogos necesarios
        $periododia = DB::table('dtiempo')
            ->where('periododia', intval(now()->format('Ymd')))
            ->value('periododia');

        if (!$periododia) {
            return back()->withErrors(['monto' => 'Error en el calendario de operaciones. Contacte al administrador.'])->withInput();
        }

        $pkconcepto = DB::table('dconceptooperacion')
            ->where('codconceptooperacion', 'TRAN')
            ->value('pkconceptooperacion');

        if (!$pkconcepto) {
            $pkconcepto = DB::table('dconceptooperacion')->value('pkconceptooperacion');
        }

        $pktipo = DB::table('dtipooperacion')->value('pktipooperacion');
        $pkcanal = DB::table('dcanaltransaccional')
            ->where('codcanaltransaccional', 'WEB')
            ->value('pkcanaltransaccional');

        DB::transaction(function () use ($request, $pkcliente, $cuentaOrigen, $periododia, $pkconcepto, $pktipo, $pkcanal) {
            $descripcion = $request->descripcion ?? 'Transferencia entre cuentas propias';

            // Egreso en cuenta origen
            DB::table('foperaciones')->insert([
                'codtipkar'            => 'AH',
                'pkcuentaahorro'       => $request->cuenta_origen,
                'codkardex'            => 'TRF-' . now()->format('YmdHis') . '-E',
                'pkconceptooperacion'  => $pkconcepto,
                'fechahoraoperacion'   => now(),
                'periododia'           => $periododia,
                'pktipooperacion'      => $pktipo,
                'pkmoneda'             => $cuentaOrigen->pkmoneda,
                'pkagenciaorigen'      => $cuentaOrigen->pkagencia,
                'pkcanaltransaccional' => $pkcanal,
                'codtipoegresoingreso' => 'E',
                'montooperacion'       => $request->monto,
                'montopagoconcepto'    => $request->monto,
                'fecultactualizacion'  => now(),
            ]);

            // Ingreso en cuenta destino
            DB::table('foperaciones')->insert([
                'codtipkar'            => 'AH',
                'pkcuentaahorro'       => $request->cuenta_destino,
                'codkardex'            => 'TRF-' . now()->format('YmdHis') . '-I',
                'pkconceptooperacion'  => $pkconcepto,
                'fechahoraoperacion'   => now(),
                'periododia'           => $periododia,
                'pktipooperacion'      => $pktipo,
                'pkmoneda'             => $cuentaOrigen->pkmoneda,
                'pkagenciaorigen'      => $cuentaOrigen->pkagencia,
                'pkcanaltransaccional' => $pkcanal,
                'codtipoegresoingreso' => 'I',
                'montooperacion'       => $request->monto,
                'montopagoconcepto'    => $request->monto,
                'fecultactualizacion'  => now(),
            ]);
        });

        return redirect()->route('transferencias')
            ->with('success', 'Transferencia realizada exitosamente por S/ ' . number_format($request->monto, 2));
    }
}
