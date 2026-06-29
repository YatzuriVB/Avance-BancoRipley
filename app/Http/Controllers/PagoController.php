<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    // ── Pago de Crédito ──────────────────────────────────────────────────────

    public function creditoIndex()
    {
        $pkcliente = auth()->user()->pkcliente;

        // Créditos con cuotas pendientes
        $creditos = DB::table('dcuentacredito as dc')
            ->join('fagcuentacredito as fa', 'fa.pkcuentacredito', '=', 'dc.pkcuentacredito')
            ->join('dproducto as pr', 'pr.pkproducto', '=', 'fa.pkproducto')
            ->where('dc.pkcliente', $pkcliente)
            ->select(
                'dc.pkcuentacredito',
                'dc.codcuentacredito',
                'fa.montoaprobadocredito',
                'fa.montosaldocapital',
                'pr.desproducto',
                'fa.periodomes'
            )
            ->orderBy('fa.periodomes', 'desc')
            ->get()
            ->unique('pkcuentacredito');

        // Para cada crédito, obtener la próxima cuota pendiente
        $cuotasPendientes = collect();
        foreach ($creditos as $credito) {
            $cuota = DB::table('fplanpagomes')
                ->where('pkcuentacredito', $credito->pkcuentacredito)
                ->where('pkcliente', $pkcliente)
                ->where('codestadocuota', '01')
                ->where('fechavencimientopagocuota', '>=', now()->toDateString())
                ->orderBy('nrocuota', 'asc')
                ->select('nrocuota', 'montocuota', 'montocapitalprogramado', 'montointeresprogramado', 'fechavencimientopagocuota', 'montomora', 'pkcuentacredito', 'periodomes')
                ->first();

            if ($cuota) {
                $cuotasPendientes->push(array_merge((array) $credito, (array) $cuota));
            }
        }

        // Cuentas de ahorro para débito
        $cuentasAhorro = DB::table('dcuentaahorro as d')
            ->join('fcuentaahorro as f', 'f.pkcuentaahorro', '=', 'd.pkcuentaahorro')
            ->where('d.pkcliente', $pkcliente)
            ->select('d.pkcuentaahorro', 'd.codcuentaahorro', 'f.montosaldodisponible_ac as saldo')
            ->orderBy('f.periododia', 'desc')
            ->get()
            ->unique('pkcuentaahorro');

        $cliente = DB::table('dcliente')->where('pkcliente', $pkcliente)->first();

        return view('pagos.credito', compact('cuotasPendientes', 'cuentasAhorro', 'cliente'));
    }

    public function creditoStore(Request $request)
    {
        $request->validate([
            'pkcuentacredito' => 'required|integer',
            'nrocuota'        => 'required|integer',
            'periodomes'      => 'required|integer',
            'pkcuentaahorro'  => 'required|integer',
        ]);

        $pkcliente = auth()->user()->pkcliente;

        // Verificar que la cuota pertenece al cliente y está pendiente
        $cuota = DB::table('fplanpagomes')
            ->where('pkcuentacredito', $request->pkcuentacredito)
            ->where('pkcliente', $pkcliente)
            ->where('nrocuota', $request->nrocuota)
            ->where('periodomes', $request->periodomes)
            ->where('codestadocuota', '01')
            ->first();

        if (!$cuota) {
            return back()->withErrors(['error' => 'Cuota no válida o ya fue pagada.']);
        }

        // Verificar saldo suficiente
        $cuentaAhorro = DB::table('dcuentaahorro as d')
            ->join('fcuentaahorro as f', 'f.pkcuentaahorro', '=', 'd.pkcuentaahorro')
            ->where('d.pkcuentaahorro', $request->pkcuentaahorro)
            ->where('d.pkcliente', $pkcliente)
            ->select('f.montosaldodisponible_ac as saldo', 'f.pkagencia', 'f.pkmoneda')
            ->orderBy('f.periododia', 'desc')
            ->first();

        if (!$cuentaAhorro) {
            return back()->withErrors(['error' => 'Cuenta de ahorro no válida.']);
        }

        $montoPagar = $cuota->montocuota + $cuota->montomora;

        if ($cuentaAhorro->saldo < $montoPagar) {
            return back()->withErrors(['error' => 'Saldo insuficiente. Necesita S/ ' . number_format($montoPagar, 2)]);
        }

        $periododia = DB::table('dtiempo')
            ->where('periododia', intval(now()->format('Ymd')))
            ->value('periododia');

        DB::transaction(function () use ($request, $pkcliente, $cuota, $cuentaAhorro, $periododia, $montoPagar) {
            // Marcar cuota como pagada
            DB::table('fplanpagomes')
                ->where('pkcuentacredito', $request->pkcuentacredito)
                ->where('pkcliente', $pkcliente)
                ->where('nrocuota', $request->nrocuota)
                ->where('periodomes', $request->periodomes)
                ->update([
                    'codestadocuota'      => 'PA',
                    'fechapagocuota'      => now()->toDateString(),
                    'montocapitalpagado'  => $cuota->montocapitalprogramado,
                    'montointerespagado'  => $cuota->montointeresprogramado,
                    'fecultactualizacion' => now(),
                ]);

            // Registrar operación en kardex
            $pkconcepto = DB::table('dconceptooperacion')
                ->where('codconceptooperacion', 'PAGC')
                ->value('pkconceptooperacion')
                ?? DB::table('dconceptooperacion')->value('pkconceptooperacion');

            $pktipo  = DB::table('dtipooperacion')->value('pktipooperacion');
            $pkcanal = DB::table('dcanaltransaccional')
                ->where('codcanaltransaccional', 'WEB')
                ->value('pkcanaltransaccional');

            DB::table('foperaciones')->insert([
                'codtipkar'            => 'CR',
                'pkcuentacredito'      => $request->pkcuentacredito,
                'pkcuentaahorro'       => $request->pkcuentaahorro,
                'codkardex'            => 'PAG-' . now()->format('YmdHis'),
                'pkconceptooperacion'  => $pkconcepto,
                'nrocuotaplazo'        => $request->nrocuota,
                'fechahoraoperacion'   => now(),
                'periododia'           => $periododia,
                'pktipooperacion'      => $pktipo,
                'pkmoneda'             => $cuentaAhorro->pkmoneda,
                'pkagenciaorigen'      => $cuentaAhorro->pkagencia,
                'pkcanaltransaccional' => $pkcanal,
                'codtipoegresoingreso' => 'E',
                'montooperacion'       => $montoPagar,
                'montopagoconcepto'    => $montoPagar,
                'fecultactualizacion'  => now(),
            ]);
        });

        return redirect()->route('pagos.credito')
            ->with('success', '¡Pago realizado! Cuota N° ' . $request->nrocuota . ' por S/ ' . number_format($montoPagar, 2));
    }

    // ── Pago de Servicios ────────────────────────────────────────────────────

    public function serviciosIndex()
    {
        $pkcliente = auth()->user()->pkcliente;

        // Convenios del cliente
        $convenios = DB::table('dconvenio')
            ->where(function ($q) use ($pkcliente) {
                $q->where('pkcliente', $pkcliente)
                  ->orWhereNull('pkcliente');
            })
            ->where('codestado', '1')
            ->orderBy('desempresa')
            ->get();

        // Agrupar por empresa
        $porEmpresa = $convenios->groupBy('desempresa');

        $cuentasAhorro = DB::table('dcuentaahorro as d')
            ->join('fcuentaahorro as f', 'f.pkcuentaahorro', '=', 'd.pkcuentaahorro')
            ->where('d.pkcliente', $pkcliente)
            ->select('d.pkcuentaahorro', 'd.codcuentaahorro', 'f.montosaldodisponible_ac as saldo')
            ->orderBy('f.periododia', 'desc')
            ->get()
            ->unique('pkcuentaahorro');

        $cliente = DB::table('dcliente')->where('pkcliente', $pkcliente)->first();

        return view('pagos.servicios', compact('porEmpresa', 'cliente', 'cuentasAhorro'));
    }

    public function serviciosStore(Request $request)
    {
        $request->validate([
            'pkconvenio' => 'required|integer',
            'codigo_suministro' => 'required|string|max:50',
            'monto' => 'required|numeric|min:1|max:5000',
            'pkcuentaahorro' => 'required|integer',
        ]);

        $pkcliente = auth()->user()->pkcliente;

        $convenio = DB::table('dconvenio')->where('pkconvenio', $request->pkconvenio)->first();

        if (!$convenio) {
            return back()->withErrors(['error' => 'Servicio no válido.']);
        }

        $cuentaAhorro = DB::table('dcuentaahorro as d')
            ->join('fcuentaahorro as f', 'f.pkcuentaahorro', '=', 'd.pkcuentaahorro')
            ->where('d.pkcuentaahorro', $request->pkcuentaahorro)
            ->where('d.pkcliente', $pkcliente)
            ->select('f.montosaldodisponible_ac as saldo', 'f.pkagencia', 'f.pkmoneda')
            ->orderBy('f.periododia', 'desc')
            ->first();

        if (!$cuentaAhorro) {
            return back()->withErrors(['error' => 'Cuenta de ahorro no válida.']);
        }

        if ($cuentaAhorro->saldo < $request->monto) {
            return back()->withErrors(['error' => 'Saldo insuficiente. Disponible: S/ ' . number_format($cuentaAhorro->saldo, 2)]);
        }

        DB::transaction(function () use ($request, $convenio, $cuentaAhorro) {
            $periododia = (int) now()->format('Ymd');

            $pkconcepto = DB::table('dconceptooperacion')
                ->where('codconceptooperacion', 'PSER')
                ->value('pkconceptooperacion');
            $pktipo = DB::table('dtipooperacion')->value('pktipooperacion');
            $pkcanal = DB::table('dcanaltransaccional')
                ->where('codcanaltransaccional', 'WEB')
                ->value('pkcanaltransaccional');

            DB::table('foperaciones')->insert([
                'codtipkar' => 'AH',
                'pkcuentaahorro' => $request->pkcuentaahorro,
                'codkardex' => 'SERV-' . now()->format('YmdHis'),
                'pkconceptooperacion' => $pkconcepto,
                'fechahoraoperacion' => now(),
                'periododia' => $periododia,
                'pktipooperacion' => $pktipo,
                'pkmoneda' => $cuentaAhorro->pkmoneda,
                'pkagenciaorigen' => $cuentaAhorro->pkagencia,
                'pkcanaltransaccional' => $pkcanal,
                'codtipoegresoingreso' => 'E',
                'montooperacion' => $request->monto,
                'montopagoconcepto' => $request->monto,
                'fecultactualizacion' => now(),
            ]);

            // Actualizar saldo de la cuenta
            DB::table('fcuentaahorro')
                ->where('pkcuentaahorro', $request->pkcuentaahorro)
                ->orderByDesc('periododia')
                ->limit(1)
                ->update([
                    'montosaldodisponible_ac' => DB::raw('montosaldodisponible_ac - ' . $request->monto),
                    'fecultactualizacion' => now(),
                ]);
        });

        return redirect()->route('pagos.servicios')
            ->with('success', "Pago de {$convenio->desempresa} realizado correctamente por S/ " . number_format($request->monto, 2));
    }
}
