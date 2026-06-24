<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CoreDashboardController extends Controller
{
    public function index()
    {
        // Verificar sesión del core
        if (!Session::get('core_logged_in')) {
            return redirect()->route('core.login');
        }

        $pkasesor = Session::get('core_pkasesor');

        // Obtener el periodo actual según ftiempo/dtiempo o el último disponible en la cartera
        // Simularemos con el último periodo disponible en fagcuentacredito para este asesor
        $ultimoPeriodo = DB::table('fagcuentacredito')
            ->where('pkasesor', $pkasesor)
            ->max('periodomes');

        if (!$ultimoPeriodo) {
            $ultimoPeriodo = intval(now()->format('Ym')); // Fallback ej: 202606
        }

        // Consultar la cartera del asesor para el último periodo
        $cartera = DB::table('fagcuentacredito as f')
            ->join('destadocredito as e', 'e.pkestadocredito', '=', 'f.pkestadocredito')
            ->join('dcliente as c', 'c.pkcliente', '=', 'f.pkcliente')
            ->join('dproducto as p', 'p.pkproducto', '=', 'f.pkproducto')
            ->leftJoin('dcalificacioncrediticia as cal', 'cal.pkcalificacioncrediticia', '=', 'f.pkcalificacioncrediticiainterna')
            ->where('f.pkasesor', $pkasesor)
            ->where('f.periodomes', $ultimoPeriodo)
            ->select(
                'f.montosaldocapital',
                'f.montosaldovencido',
                'f.diasatrasocredito',
                'f.pkestadocredito',
                'cal.codcalificacioncrediticia as codcalificacion',
                'f.pkcliente',
                'p.destipocredito'
            )
            ->get();

        // Cálculos de KPIs
        $carteraTotal = $cartera->sum('montosaldocapital');
        
        // Vigente (ej: estado 1 = Vigente, pero para asegurar, todo lo que no es mora ni vencido)
        // O sumamos saldo_capital menos saldo_vencido.
        $carteraVencida = $cartera->where('diasatrasocredito', '>', 0)->sum('montosaldocapital');
        $carteraVigente = $carteraTotal - $carteraVencida;

        $ratioMora = $carteraTotal > 0 ? ($carteraVencida / $carteraTotal) * 100 : 0;
        
        $numCreditos = $cartera->count();
        $numClientes = $cartera->unique('pkcliente')->count();
        $creditosConAtraso = $cartera->where('diasatrasocredito', '>', 0)->count();

        // Gráfico: Cartera por calificación
        $porCalificacion = $cartera->groupBy('codcalificacion')->map(function ($items) {
            return $items->sum('montosaldocapital');
        })->sortKeys();

        // Gráfico: Cartera vencida por tipo de producto
        $moraPorProducto = $cartera->where('diasatrasocredito', '>', 0)->groupBy('destipocredito')->map(function ($items) {
            return $items->sum('montosaldocapital');
        });

        // Desembolsos del día (de toda la entidad, no solo del asesor)
        $desembolsosHoy = DB::table('fagcuentacredito as f')
            ->join('dcuentacredito as d', 'd.pkcuentacredito', '=', 'f.pkcuentacredito')
            ->join('dcliente as c', 'c.pkcliente', '=', 'f.pkcliente')
            ->whereDate('f.fechadesembolsocredito', now()->toDateString())
            ->select(
                'd.codcuentacredito',
                'c.nomcliente',
                'f.montocapitaldesembolsado'
            )
            ->get();

        $totalDesembolsadoHoy = $desembolsosHoy->sum('montocapitaldesembolsado');
        $cantidadDesembolsosHoy = $desembolsosHoy->count();

        // Solicitudes pendientes (de toda la entidad, por estado)
        $solicitudesPorEstado = DB::table('dsolicitud as s')
            ->join('dsolicitudestado as e', 'e.pksolicitudestado', '=', 's.pksolicitudestado')
            ->select('e.dessolicitudestado', DB::raw('count(*) as total'))
            ->groupBy('e.dessolicitudestado')
            ->get();

        // Cartera en mora con días de atraso clasificados (de la cartera del asesor)
        $tramos = $cartera->where('montosaldovencido', '>', 0)->map(function ($item) {
            return $item;
        });

        return view('core.dashboard', compact(
            'ultimoPeriodo',
            'carteraTotal',
            'carteraVigente',
            'carteraVencida',
            'ratioMora',
            'numCreditos',
            'numClientes',
            'creditosConAtraso',
            'porCalificacion',
            'moraPorProducto',
            'desembolsosHoy',
            'totalDesembolsadoHoy',
            'cantidadDesembolsosHoy',
            'solicitudesPorEstado'
        ));
    }
}