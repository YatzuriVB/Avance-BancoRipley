<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SolicitudBandejaController extends Controller
{
    public function index()
    {
        if (!Session::get('core_logged_in')) {
            return redirect()->route('core.login');
        }

        $pkasesor = Session::get('core_pkasesor');

        $solicitudes = DB::table('dsolicitud as s')
            ->join('dsolicitudestado as e', 'e.pksolicitudestado', '=', 's.pksolicitudestado')
            ->join('dcliente as c', 'c.pkcliente', '=', 's.pkcliente')
            ->join('dproducto as p', 'p.pkproducto', '=', 's.pkproducto')
            ->where('s.pkasesor', $pkasesor)
            ->select(
                's.pksolicitud',
                's.codsolicitud',
                's.montosolicitudcredito',
                's.plazosolicitudcredito',
                's.fechasolicitudcredito',
                'c.nomcliente',
                'c.numerodocumentoidentidad',
                'p.destipocredito',
                'e.codsolicitudestado',
                'e.dessolicitudestado'
            )
            ->orderBy('s.fechahoracreacion', 'desc')
            ->get();

        // Conteos para los filtros/resumen
        $totalEvaluacion = $solicitudes->where('codsolicitudestado', '01')->count();
        $totalComite = $solicitudes->where('codsolicitudestado', '06')->count();
        $totalAprobado = $solicitudes->where('codsolicitudestado', '02')->count();
        $totalRechazado = $solicitudes->where('codsolicitudestado', '03')->count();

        return view('core.solicitudes.bandeja', compact(
            'solicitudes',
            'totalEvaluacion',
            'totalComite',
            'totalAprobado',
            'totalRechazado'
        ));
    }

    public function show($pksolicitud)
    {
        if (!Session::get('core_logged_in')) {
            return redirect()->route('core.login');
        }

        $solicitud = DB::table('dsolicitud as s')
            ->join('dsolicitudestado as e', 'e.pksolicitudestado', '=', 's.pksolicitudestado')
            ->join('dcliente as c', 'c.pkcliente', '=', 's.pkcliente')
            ->join('dproducto as p', 'p.pkproducto', '=', 's.pkproducto')
            ->leftJoin('dactividadeconomica as ae', 'ae.pkactividadeconomica', '=', 's.pkactividadeconomicasolicitud')
            ->where('s.pksolicitud', $pksolicitud)
            ->select(
                's.pksolicitud',
                's.codsolicitud',
                's.montosolicitudcredito',
                's.plazosolicitudcredito',
                's.fechasolicitudcredito',
                's.nrocuotasolicitud',
                's.pkcliente',
                'c.nomcliente',
                'c.numerodocumentoidentidad',
                'c.email',
                'c.telefono',
                'c.montoingresoneto',
                'p.destipocredito',
                'p.desproducto',
                'e.codsolicitudestado',
                'e.dessolicitudestado',
                'ae.desactividadeconomica'
            )
            ->first();

        if (!$solicitud) {
            abort(404);
        }

        // Ingreso registrado del cliente (más reciente)
        $ingreso = DB::table('fclientefuenteingreso')
            ->where('pkcliente', $solicitud->pkcliente)
            ->orderByDesc('periodomes')
            ->first();

        return view('core.solicitudes.show', compact('solicitud', 'ingreso'));
    }

    public function evaluar(Request $request, $pksolicitud)
    {
        if (!Session::get('core_logged_in')) {
            return redirect()->route('core.login');
        }

        $request->validate([
            'accion' => 'required|in:comite,rechazar',
            'comentario' => 'nullable|string',
        ]);

        $codEstadoDestino = $request->accion === 'comite' ? '06' : '03'; // 06=En Comité, 03=Rechazado

        $pkestadoDestino = DB::table('dsolicitudestado')
            ->where('codsolicitudestado', $codEstadoDestino)
            ->value('pksolicitudestado');

        DB::table('dsolicitud')
            ->where('pksolicitud', $pksolicitud)
            ->update([
                'pksolicitudestado' => $pkestadoDestino,
                'fechahoraultmodificacion' => now(),
                'fecultactualizacion' => now(),
            ]);

        $mensaje = $request->accion === 'comite'
            ? 'Solicitud enviada a Comité correctamente.'
            : 'Solicitud rechazada.';

        return redirect()->route('core.solicitudes.bandeja')->with('success', $mensaje);
    }

    public function comite()
    {
        if (!Session::get('core_logged_in')) {
            return redirect()->route('core.login');
        }

        $solicitudes = DB::table('dsolicitud as s')
            ->join('dsolicitudestado as e', 'e.pksolicitudestado', '=', 's.pksolicitudestado')
            ->join('dcliente as c', 'c.pkcliente', '=', 's.pkcliente')
            ->join('dproducto as p', 'p.pkproducto', '=', 's.pkproducto')
            ->where('e.codsolicitudestado', '06') // En Comité
            ->select(
                's.pksolicitud',
                's.codsolicitud',
                's.montosolicitudcredito',
                's.plazosolicitudcredito',
                's.fechasolicitudcredito',
                'c.nomcliente',
                'c.numerodocumentoidentidad',
                'p.destipocredito',
                'e.dessolicitudestado'
            )
            ->orderBy('s.fechahoraultmodificacion', 'desc')
            ->get();

        return view('core.solicitudes.comite', compact('solicitudes'));
    }

    public function resolver(Request $request, $pksolicitud)
    {
        if (!Session::get('core_logged_in')) {
            return redirect()->route('core.login');
        }

        $request->validate([
            'accion' => 'required|in:aprobar,rechazar',
            'comentario' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            if ($request->accion === 'aprobar') {
                $pkestado = DB::table('dsolicitudestado')->where('codsolicitudestado', '02')->value('pksolicitudestado');

                DB::table('dsolicitud')
                    ->where('pksolicitud', $pksolicitud)
                    ->update([
                        'pksolicitudestado' => $pkestado,
                        'montoaprobadocredito' => DB::raw('montosolicitudcredito'),
                        'nrocuotaaprobado' => DB::raw('nrocuotasolicitud'),
                        'plazoaprobadocredito' => DB::raw('plazosolicitudcredito'),
                        'fechaaprobacioncredito' => now()->toDateString(),
                        'fechahoraultmodificacion' => now(),
                        'fecultactualizacion' => now(),
                    ]);

                $mensaje = 'Solicitud aprobada correctamente.';
            } else {
                $pkestado = DB::table('dsolicitudestado')->where('codsolicitudestado', '03')->value('pksolicitudestado');

                DB::table('dsolicitud')
                    ->where('pksolicitud', $pksolicitud)
                    ->update([
                        'pksolicitudestado' => $pkestado,
                        'fechahoraultmodificacion' => now(),
                        'fecultactualizacion' => now(),
                    ]);

                $mensaje = 'Solicitud rechazada.';
            }

            DB::commit();
            return redirect()->route('core.solicitudes.comite')->with('success', $mensaje);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }
}