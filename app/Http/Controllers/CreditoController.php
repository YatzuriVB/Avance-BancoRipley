<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class CreditoController extends Controller
{
    public function show($pkcuentacredito)
    {
        $pkcliente = auth()->user()->pkcliente;

        // Seguridad: la cuenta debe pertenecer al cliente
        $check = DB::table('dcuentacredito')
            ->where('pkcuentacredito', $pkcuentacredito)
            ->where('pkcliente', $pkcliente)
            ->exists();

        if (!$check) {
            abort(403, 'No autorizado');
        }

        // Detalle del crédito (último período disponible)
        $credito = DB::table('dcuentacredito as dc')
            ->join('fagcuentacredito as fa', 'fa.pkcuentacredito', '=', 'dc.pkcuentacredito')
            ->join('dproducto as pr', 'pr.pkproducto', '=', 'fa.pkproducto')
            ->join('destadocredito as e', 'e.pkestadocredito', '=', 'fa.pkestadocredito')
            ->join('dmoneda as m', 'm.pkmoneda', '=', 'fa.pkmoneda')
            ->where('dc.pkcuentacredito', $pkcuentacredito)
            ->select(
                'dc.pkcuentacredito',
                'dc.codcuentacredito',
                'fa.montoaprobadocredito',
                'fa.montocapitalpagado',
                'fa.montosaldocapital',
                'fa.nrocuotas',
                'fa.tasainterescompensatoria as tea',
                'fa.tasainteresmoratoria',
                'fa.fechadesembolsocredito',
                'fa.fechaculminacioncredito',
                'fa.diasatrasocredito',
                'fa.montosaldovencido',
                'pr.desproducto',
                'pr.dessubproducto',
                'pr.destipocredito',
                'e.desestadocredito',
                'fa.pkestadocredito',
                'm.simbolo as moneda',
                'fa.periodomes'
            )
            ->orderBy('fa.periodomes', 'desc')
            ->first();

        if (!$credito) {
            abort(404);
        }

        // Cronograma completo de cuotas
        $cronograma = DB::table('fplanpagomes as fp')
            ->where('fp.pkcuentacredito', $pkcuentacredito)
            ->where('fp.pkcliente', $pkcliente)
            ->select(
                'fp.nrocuota',
                'fp.fechavencimientopagocuota',
                'fp.fechapagocuota',
                'fp.montocuota',
                'fp.montocapitalprogramado',
                'fp.montointeresprogramado',
                'fp.montomora',
                'fp.montosaldocapital',
                'fp.codestadocuota',
                'fp.diasatrasocuota',
                'fp.montopagoparcial',
                'fp.periodomes'
            )
            ->orderBy('fp.nrocuota', 'asc')
            ->get()
            ->unique('nrocuota'); // Evitar duplicados por período

        // Estadísticas del cronograma
        $cuotasPagadas   = $cronograma->where('codestadocuota', '!=', '01')->count();
        $cuotasPendientes = $cronograma->where('codestadocuota', '01')->count();
        $proximaCuota    = $cronograma->where('codestadocuota', '01')
                                       ->sortBy('nrocuota')
                                       ->first();

        // Datos del cliente para el sidebar
        $cliente = DB::table('dcliente')->where('pkcliente', $pkcliente)->first();

        return view('creditos.show', compact(
            'credito', 'cronograma', 'cliente',
            'cuotasPagadas', 'cuotasPendientes', 'proximaCuota'
        ));
    }

    public function simulador()
    {
        $pkcliente = auth()->user()->pkcliente;
        $cliente   = DB::table('dcliente')->where('pkcliente', $pkcliente)->first();

        return view('creditos.simulador', compact('cliente'));
    }
    public function index()
    {
        $pkcliente = auth()->user()->pkcliente;
        $cliente = DB::table('dcliente')->where('pkcliente', $pkcliente)->first();

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
            ->get();

        return view('creditos.index', compact('cliente', 'creditos'));
    }
}
