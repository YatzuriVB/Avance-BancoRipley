<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudCreditoController extends Controller
{
    // Mapa tipo de crédito del portal -> codtipocredito en dproducto
    private const MAPA_TIPO_CREDITO = [
        'ME' => '01', // Microempresa
        'CO' => '03', // Consumo
    ];

    public function create()
    {
        $pkcliente = auth()->user()->pkcliente;
        $cliente = DB::table('dcliente')->where('pkcliente', $pkcliente)->first();

        $actividades = DB::table('dactividadeconomica')
            ->select('pkactividadeconomica', 'codactividadeconomica', 'desactividadeconomica')
            ->orderBy('desactividadeconomica')
            ->get();

        // Historial de solicitudes del cliente
        $solicitudes = DB::table('dsolicitud as s')
            ->join('dsolicitudestado as e', 'e.pksolicitudestado', '=', 's.pksolicitudestado')
            ->where('s.pkcliente', $pkcliente)
            ->select(
                's.pksolicitud',
                's.codsolicitud',
                's.montosolicitudcredito',
                's.plazosolicitudcredito',
                's.fechasolicitudcredito',
                'e.dessolicitudestado'
            )
            ->orderBy('s.fechahoracreacion', 'desc')
            ->get();

        return view('creditos.solicitar', compact('cliente', 'actividades', 'solicitudes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'monto' => ['required', 'numeric', 'min:500', 'max:100000'],
            'plazo' => ['required', 'integer', 'min:6', 'max:60'],
            'tipo_credito' => ['required', 'in:ME,CO'],
            'actividad' => ['required'],
            'ingreso' => ['required', 'numeric', 'min:0'],
        ]);

        $pkcliente = auth()->user()->pkcliente;

        DB::beginTransaction();
        try {
            $codTipoProducto = self::MAPA_TIPO_CREDITO[$request->tipo_credito];

            $pkproducto = DB::table('dproducto')
                ->where('codtipocredito', $codTipoProducto)
                ->where('flagactivo', '1')
                ->min('pkproducto');

            if (!$pkproducto) {
                throw new \Exception('No hay producto activo para este tipo de crédito.');
            }

            $pkestado = DB::table('dsolicitudestado')
                ->where('codsolicitudestado', '01') // En Evaluación
                ->value('pksolicitudestado');

            $pkactividad = DB::table('dactividadeconomica')
                ->where('pkactividadeconomica', $request->actividad)
                ->value('pkactividadeconomica');

            $pkmoneda = DB::table('dmoneda')->where('codmoneda', 'SO')->value('pkmoneda');

            // Agencia y asesor: del crédito vigente del cliente, o fallback
            $agAs = DB::table('fagcuentacredito')
                ->where('pkcliente', $pkcliente)
                ->orderByDesc('pkcuentacredito')
                ->select('pkagencia', 'pkasesor')
                ->first();

            $pkagencia = $agAs->pkagencia ?? DB::table('dagencia')->min('pkagencia');
            $pkasesor = $agAs->pkasesor ?? DB::table('dasesor')->min('pkasesor');

            // Registrar/actualizar ingreso del cliente
            $periodoActual = (int) now()->format('Ym');

            DB::statement("
                INSERT INTO fclientefuenteingreso (pkcliente, periodomes, montofuenteingreso, pkactividadeconomicacliente, fecultactualizacion)
                VALUES (?, ?, ?, ?, now())
                ON CONFLICT (pkcliente, periodomes)
                DO UPDATE SET montofuenteingreso = EXCLUDED.montofuenteingreso,
                              pkactividadeconomicacliente = EXCLUDED.pkactividadeconomicacliente,
                              fecultactualizacion = now()
            ", [$pkcliente, $periodoActual, $request->ingreso, $pkactividad]);

            // Crear la solicitud
            $row = DB::selectOne("
                INSERT INTO dsolicitud (
                    pksolicitud, codsolicitud, pkcliente, codlineacredito,
                    pksolicitudestado, pkmoneda, pkproducto,
                    codtiposolicitud, destiposolicitud,
                    montosolicitudcredito, nrocuotasolicitud, plazosolicitudcredito,
                    fechasolicitudcredito, codususol,
                    flaglibreamortizacioncredito, nrodiasgracia,
                    pkactividadeconomicasolicitud, pkagencia, pkasesor,
                    fechahoracreacion, fechahoraultmodificacion, fecultactualizacion
                ) VALUES (
                    nextval('dsolicitud_pksolicitud_seq'),
                    'SOL' || LPAD(currval('dsolicitud_pksolicitud_seq')::text, 7, '0'),
                    ?, 'CR',
                    ?, ?, ?,
                    '01', 'Credito Nuevo',
                    ?, ?, ?,
                    CURRENT_DATE, 'HB',
                    'N', 0,
                    ?, ?, ?,
                    now(), now(), now()
                )
                RETURNING pksolicitud, codsolicitud
            ", [
                $pkcliente, $pkestado, $pkmoneda, $pkproducto,
                $request->monto, $request->plazo, $request->plazo,
                $pkactividad, $pkagencia, $pkasesor
            ]);

            DB::commit();

            return redirect()->route('credito.solicitar')
                ->with('success', "Solicitud {$row->codsolicitud} registrada correctamente. Estado: En Evaluación.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al registrar la solicitud: ' . $e->getMessage()]);
        }
    }

    public function show($pksolicitud)
    {
        $pkcliente = auth()->user()->pkcliente;

        $solicitud = DB::table('dsolicitud as s')
            ->join('dsolicitudestado as e', 'e.pksolicitudestado', '=', 's.pksolicitudestado')
            ->join('dproducto as p', 'p.pkproducto', '=', 's.pkproducto')
            ->leftJoin('dactividadeconomica as ae', 'ae.pkactividadeconomica', '=', 's.pkactividadeconomicasolicitud')
            ->where('s.pksolicitud', $pksolicitud)
            ->where('s.pkcliente', $pkcliente) // seguridad: solo su propia solicitud
            ->select(
                's.codsolicitud',
                's.montosolicitudcredito',
                's.montoaprobadocredito',
                's.plazosolicitudcredito',
                's.plazoaprobadocredito',
                's.fechasolicitudcredito',
                's.fechaaprobacioncredito',
                's.destiposolicitud',
                'p.destipocredito',
                'p.desproducto',
                'e.dessolicitudestado',
                'e.codsolicitudestado',
                'ae.desactividadeconomica'
            )
            ->first();

        if (!$solicitud) {
            abort(404);
        }

        // Si fue desembolsada, buscamos el crédito generado
        $creditoGenerado = null;
        if (trim($solicitud->codsolicitudestado) === '04') {
            $creditoGenerado = DB::table('fagcuentacredito as f')
                ->join('dcuentacredito as d', 'd.pkcuentacredito', '=', 'f.pkcuentacredito')
                ->where('f.pksolicitud', $pksolicitud)
                ->select('d.pkcuentacredito', 'd.codcuentacredito')
                ->first();
        }

        return view('creditos.solicitud-detalle', compact('solicitud', 'creditoGenerado'));
    }
}