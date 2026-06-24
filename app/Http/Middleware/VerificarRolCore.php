<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class VerificarRolCore
{
    /**
     * Mapa de roles funcionales -> cargos reales en la BD
     */
    private const ROLES = [
        'asesor'        => ['Asesor de Negocios'],
        'comite'        => ['Funcionario de Créditos'],
        'administrador' => ['Administrador de Agencia'],
        'jefe_regional' => ['Jefe de Negocios Regional'],
        'riesgos'       => ['Jefe de Riesgos'],
        'analista'      => ['Analista de Créditos'],
    ];

    public function handle(Request $request, Closure $next, ...$rolesPermitidos): Response
    {
        if (!Session::get('core_logged_in')) {
            return redirect()->route('core.login');
        }

        $cargo = trim(Session::get('core_cargo') ?? '');

        $cargosPermitidos = [];
        foreach ($rolesPermitidos as $rol) {
            $cargosPermitidos = array_merge($cargosPermitidos, self::ROLES[$rol] ?? []);
        }

        if (!in_array($cargo, $cargosPermitidos)) {
            abort(403, 'No tienes permiso para acceder a este módulo. Tu cargo: ' . ($cargo ?: 'No asignado'));
        }

        return $next($request);
    }
}