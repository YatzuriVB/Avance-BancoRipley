<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MoraController extends Controller
{
    public function index()
    {
        if (!Session::get('core_logged_in')) {
            return redirect()->route('core.login');
        }

        $pkasesor = Session::get('core_pkasesor');

        $ultimoPeriodo = DB::table('fagcuentacredito')
            ->where('pkasesor', $pkasesor)
            ->max('periodomes');

        $creditosMora = DB::table('fagcuentacredito as f')
            ->join('dcuentacredito as d', 'd.pkcuentacredito', '=', 'f.pkcuentacredito')
            ->join('dcliente as c', 'c.pkcliente', '=', 'f.pkcliente')
            ->join('dproducto as p', 'p.pkproducto', '=', 'f.pkproducto')
            ->where('f.pkasesor', $pkasesor)
            ->where('f.periodomes', $ultimoPeriodo)
            ->where('f.diasatrasocredito', '>', 0)
            ->select(
                'f.pkcuentacredito',
                'd.codcuentacredito',
                'c.nomcliente',
                'c.numerodocumentoidentidad',
                'c.telefono',
                'p.destipocredito',
                'f.montosaldovencido',
                'f.montosaldocapital',
                'f.diasatrasocredito',
                'f.flagjudicial',
                'f.flagcastigado'
            )
            ->orderByDesc('f.diasatrasocredito')
            ->get();

        // Clasificación de riesgo
        $totalMora = $creditosMora->sum('montosaldovencido');
        $puedeJudicial = $creditosMora->where('diasatrasocredito', '>=', 121)->count();
        $puedeCastigar = $creditosMora->where('diasatrasocredito', '>', 180)->count();

        return view('core.solicitudes.mora', compact(
            'creditosMora', 'totalMora', 'puedeJudicial', 'puedeCastigar'
        ));
    }

    public function derivarJudicial(Request $request, $pkcuentacredito)
    {
        if (!Session::get('core_logged_in')) {
            return redirect()->route('core.login');
        }

        $credito = DB::table('fagcuentacredito')
            ->where('pkcuentacredito', $pkcuentacredito)
            ->orderByDesc('periodomes')
            ->first();

        if (!$credito || $credito->diasatrasocredito < 121) {
            return back()->withErrors(['error' => 'El crédito no cumple el mínimo de 121 días de atraso para derivar a judicial.']);
        }

        DB::table('fagcuentacredito')
            ->where('pkcuentacredito', $pkcuentacredito)
            ->update([
                'flagjudicial' => 'S',
                'pkestadocredito' => 3, // En Cobranza Judicial
                'fechaingresojudicial' => now()->toDateString(),
                'fecultactualizacion' => now(),
            ]);

        return redirect()->route('core.solicitudes.mora')->with('success', 'Crédito derivado a cobranza judicial.');
    }

    public function castigar(Request $request, $pkcuentacredito)
    {
        if (!Session::get('core_logged_in')) {
            return redirect()->route('core.login');
        }

        $credito = DB::table('fagcuentacredito')
            ->where('pkcuentacredito', $pkcuentacredito)
            ->orderByDesc('periodomes')
            ->first();

        if (!$credito || $credito->diasatrasocredito <= 180) {
            return back()->withErrors(['error' => 'El crédito no cumple el mínimo de 180 días de atraso para castigar.']);
        }

        DB::table('fagcuentacredito')
            ->where('pkcuentacredito', $pkcuentacredito)
            ->update([
                'flagcastigado' => 'S',
                'pkestadocredito' => 7, // Castigado
                'fecultactualizacion' => now(),
            ]);

        return redirect()->route('core.solicitudes.mora')->with('success', 'Crédito castigado correctamente.');
    }
}