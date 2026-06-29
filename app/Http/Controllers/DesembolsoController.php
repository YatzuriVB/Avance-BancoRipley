<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DesembolsoController extends Controller
{
    public function index()
    {
        if (!Session::get('core_logged_in')) {
            return redirect()->route('core.login');
        }

        $solicitudes = DB::table('dsolicitud as s')
            ->join('dcliente as c', 'c.pkcliente', '=', 's.pkcliente')
            ->join('dproducto as p', 'p.pkproducto', '=', 's.pkproducto')
            ->where('s.pksolicitudestado', 2) // Aprobado
            ->select(
                's.pksolicitud',
                's.codsolicitud',
                's.montoaprobadocredito',
                's.plazoaprobadocredito',
                's.fechaaprobacioncredito',
                'c.nomcliente',
                'c.numerodocumentoidentidad',
                'p.destipocredito'
            )
            ->orderBy('s.fechaaprobacioncredito', 'desc')
            ->get();

        return view('core.solicitudes.desembolso', compact('solicitudes'));
    }

    public function desembolsar(Request $request, $pksolicitud)
    {
        if (!Session::get('core_logged_in')) {
            return redirect()->route('core.login');
        }

        DB::beginTransaction();
        try {
            $solicitud = DB::table('dsolicitud')->where('pksolicitud', $pksolicitud)->first();

            if (!$solicitud || $solicitud->pksolicitudestado != 2) {
                throw new \Exception('La solicitud no está en estado Aprobado.');
            }

                        // TEA según tarifario oficial Banco Ripley - Préstamo Súper Efectivo
            // Rango vigente: 15.94% - 87.90% según evaluación crediticia del cliente
            // Microempresa (sin seguro de desgravamen): 43.92%
            $producto = DB::table('dproducto')->where('pkproducto', $solicitud->pkproducto)->first();
            $tasaAnual = str_contains(strtolower($producto->destipocredito ?? ''), 'micro') ? 43.92 : 35.0;
            // Conversión correcta de TEA a tasa mensual efectiva
            // tasa_mensual = (1 + TEA)^(1/12) - 1
            $tasaMensual = pow(1 + ($tasaAnual / 100), 1/12) - 1;
            $monto = $solicitud->montoaprobadocredito;
            $plazo = $solicitud->plazoaprobadocredito;

            $factor = pow(1 + $tasaMensual, $plazo);
            $cuota = $monto * ($tasaMensual * $factor) / ($factor - 1);

            $cuentaCredito = DB::selectOne("
                INSERT INTO dcuentacredito (pkcuentacredito, codcuentacredito, codlineacredito, pkcliente, nrocronograma, fecultactualizacion)
                VALUES (
                    nextval('dcuentacredito_pkcuentacredito_seq'),
                    'CRED' || LPAD(currval('dcuentacredito_pkcuentacredito_seq')::text, 7, '0'),
                    ?, ?, ?, now()
                )
                RETURNING pkcuentacredito, codcuentacredito
            ", [$solicitud->codlineacredito, $solicitud->pkcliente, 1]);

            $periodoActual = (int) now()->format('Ym');

            DB::statement("
                INSERT INTO fagcuentacredito (
                    periodomes, pkcuentacredito, pksolicitud, pkestadocredito,
                    nrocuotas, pkproducto, pkmoneda,
                    tasainterescompensatoria, tasainteresmoratoria,
                    fechageneracioncredito, fechadesembolsocredito,
                    montoaprobadocredito, montocapitaldesembolsado,
                    montocapitalpagado, montosaldocapital,
                    pkcliente, pkagencia, pkasesor, fecultactualizacion
                ) VALUES (
                    ?, ?, ?, 1,
                    ?, ?, ?,
                    ?, ?,
                    CURRENT_DATE, CURRENT_DATE,
                    ?, ?,
                    0, ?,
                    ?, ?, ?, now()
                )
            ", [
                $periodoActual, $cuentaCredito->pkcuentacredito, $pksolicitud,
                $plazo, $solicitud->pkproducto, $solicitud->pkmoneda,
                $tasaAnual, $tasaAnual * 1.5,
                $monto, $monto,
                $monto,
                $solicitud->pkcliente, $solicitud->pkagencia, $solicitud->pkasesor
            ]);

            $saldoCapital = $monto;
            $fechaCuota = now()->addMonth();

            for ($i = 1; $i <= $plazo; $i++) {
                $interes = $saldoCapital * $tasaMensual;
                $capital = $cuota - $interes;
                $saldoCapital -= $capital;
                if ($saldoCapital < 0) $saldoCapital = 0;

                DB::statement("
                INSERT INTO fplanpagomes (
                    periodomes, pkcuentacredito, codplanpago, nrocuota,
                    pksolicitud, pkestadocredito, pkproducto, pkmoneda,
                    pkcliente, pkagencia, pkasesor,
                    codestadocuota, codestadoplan,
                    fechavencimientopagocuota,
                    montocuota, montosaldo,
                    montosaldocapital, montocapitalpagado,
                    montocapitalprogramado, montointeresprogramado,
                    fecultactualizacion
                ) VALUES (
                    ?, ?, ?, ?,
                    ?, 1, ?, ?,
                    ?, ?, ?,
                    '01', '01',
                    ?,
                    ?, ?,
                    ?, 0,
                    ?, ?,
                    now()
                )
            ", [
                $periodoActual, $cuentaCredito->pkcuentacredito, $cuentaCredito->codcuentacredito, $i,
                $pksolicitud, $solicitud->pkproducto, $solicitud->pkmoneda,
                $solicitud->pkcliente, $solicitud->pkagencia, $solicitud->pkasesor,
                $fechaCuota->toDateString(),
                round($cuota, 2), round($saldoCapital + $capital, 2),
                round($saldoCapital, 2),
                round($capital, 2), round($interes, 2)
            ]);

                $fechaCuota = $fechaCuota->copy()->addMonth();
            }

            DB::table('dsolicitud')
                ->where('pksolicitud', $pksolicitud)
                ->update([
                    'pksolicitudestado' => 4,
                    'fechahoraultmodificacion' => now(),
                    'fecultactualizacion' => now(),
                ]);

            DB::commit();

            return redirect()->route('core.solicitudes.desembolso')
                ->with('success', "Crédito {$cuentaCredito->codcuentacredito} desembolsado correctamente. Cuota mensual: S/ " . number_format($cuota, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al desembolsar: ' . $e->getMessage()]);
        }
    }
}