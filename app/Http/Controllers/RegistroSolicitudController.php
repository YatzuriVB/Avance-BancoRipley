<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RegistroSolicitudController extends Controller
{
    public function buscar()
    {
        if (!Session::get('core_logged_in')) {
            return redirect()->route('core.login');
        }

        return view('core.solicitudes.buscar-cliente');
    }

    public function buscarCliente(Request $request)
    {
        if (!Session::get('core_logged_in')) {
            return redirect()->route('core.login');
        }

        $request->validate(['dni' => 'required|digits:8']);

        $cliente = DB::table('dcliente')
            ->where('numerodocumentoidentidad', $request->dni)
            ->first();

        if (!$cliente) {
            return back()->withErrors(['dni' => 'No se encontró ningún cliente con ese DNI.'])->withInput();
        }

        return redirect()->route('core.solicitud.registrar', $cliente->pkcliente);
    }

    public function create($pkcliente)
    {
        if (!Session::get('core_logged_in')) {
            return redirect()->route('core.login');
        }

        $cliente = DB::table('dcliente')->where('pkcliente', $pkcliente)->first();

        if (!$cliente) {
            abort(404);
        }

        $actividades = DB::table('dactividadeconomica')
            ->select('pkactividadeconomica', 'desactividadeconomica')
            ->orderBy('desactividadeconomica')
            ->get();

        return view('core.solicitudes.registrar', compact('cliente', 'actividades'));
    }

    public function store(Request $request, $pkcliente)
    {
        if (!Session::get('core_logged_in')) {
            return redirect()->route('core.login');
        }

        $request->validate([
            'monto' => ['required', 'numeric', 'min:500', 'max:100000'],
            'plazo' => ['required', 'integer', 'min:6', 'max:60'],
            'tipo_credito' => ['required', 'in:ME,CO'],
            'actividad' => ['required'],
            'ingreso' => ['required', 'numeric', 'min:0'],
        ]);

        $mapaTipo = ['ME' => '01', 'CO' => '03'];

        DB::beginTransaction();
        try {
            $codTipoProducto = $mapaTipo[$request->tipo_credito];

            $pkproducto = DB::table('dproducto')
                ->where('codtipocredito', $codTipoProducto)
                ->where('flagactivo', '1')
                ->min('pkproducto');

            if (!$pkproducto) {
                throw new \Exception('No hay producto activo para este tipo de crédito.');
            }

            $pkestado = DB::table('dsolicitudestado')
                ->where('codsolicitudestado', '01')
                ->value('pksolicitudestado');

            $pkmoneda = DB::table('dmoneda')->where('codmoneda', 'SO')->value('pkmoneda');

            $pkasesor = Session::get('core_pkasesor');

            $agencia = DB::table('dasesor')->where('pkasesor', $pkasesor)->first();
            $pkagencia = $agencia->pkagencia ?? DB::table('dagencia')->min('pkagencia');

            $periodoActual = (int) now()->format('Ym');

            DB::statement("
                INSERT INTO fclientefuenteingreso (pkcliente, periodomes, montofuenteingreso, pkactividadeconomicacliente, fecultactualizacion)
                VALUES (?, ?, ?, ?, now())
                ON CONFLICT (pkcliente, periodomes)
                DO UPDATE SET montofuenteingreso = EXCLUDED.montofuenteingreso,
                              pkactividadeconomicacliente = EXCLUDED.pkactividadeconomicacliente,
                              fecultactualizacion = now()
            ", [$pkcliente, $periodoActual, $request->ingreso, $request->actividad]);

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
                    CURRENT_DATE, 'CORE',
                    'N', 0,
                    ?, ?, ?,
                    now(), now(), now()
                )
                RETURNING pksolicitud, codsolicitud
            ", [
                $pkcliente, $pkestado, $pkmoneda, $pkproducto,
                $request->monto, $request->plazo, $request->plazo,
                $request->actividad, $pkagencia, $pkasesor
            ]);

            DB::commit();

            return redirect()->route('core.solicitudes.bandeja')
                ->with('success', "Solicitud {$row->codsolicitud} registrada correctamente para el cliente.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al registrar: ' . $e->getMessage()]);
        }
    }

    public function preSolicitud()
    {
        if (!Session::get('core_logged_in')) {
            return redirect()->route('core.login');
        }

        return view('core.solicitudes.pre-solicitud');
    }

    public function evaluarElegibilidad(Request $request)
    {
        if (!Session::get('core_logged_in')) {
            return redirect()->route('core.login');
        }

        $request->validate([
            'dni' => 'required|digits:8',
            'monto' => 'required|numeric|min:500|max:100000',
            'ingreso' => 'required|numeric|min:0',
        ]);

        $cliente = DB::table('dcliente')
            ->where('numerodocumentoidentidad', $request->dni)
            ->first();

        if (!$cliente) {
            return back()->withErrors(['dni' => 'No se encontró ningún cliente con ese DNI.'])->withInput();
        }

        // Verificar créditos vigentes con mora
        $tieneMora = DB::table('fagcuentacredito')
            ->where('pkcliente', $cliente->pkcliente)
            ->where('diasatrasocredito', '>', 30)
            ->exists();

        // Regla simple: la cuota estimada no debe superar el 30% del ingreso
        $cuotaEstimada = $request->monto / 12; // estimación simple a 12 meses
        $ratioEndeudamiento = $request->ingreso > 0 ? ($cuotaEstimada / $request->ingreso) * 100 : 100;

        $elegible = !$tieneMora && $ratioEndeudamiento <= 30;

        return view('core.solicitudes.pre-solicitud', compact(
            'cliente', 'elegible', 'tieneMora', 'ratioEndeudamiento', 'cuotaEstimada'
        ))->with('evaluado', true);
    }
}