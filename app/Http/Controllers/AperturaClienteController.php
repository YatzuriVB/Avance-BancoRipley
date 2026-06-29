<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AperturaClienteController extends Controller
{
    public function create()
    {
        if (!Session::get('core_logged_in')) {
            return redirect()->route('core.login');
        }

        return view('core.clientes.apertura');
    }

    public function store(Request $request)
    {
        if (!Session::get('core_logged_in')) {
            return redirect()->route('core.login');
        }

        $request->validate([
            'nombre' => ['required', 'string', 'max:200'],
            'dni' => ['required', 'string', 'digits:8', 'unique:dcliente,numerodocumentoidentidad'],
            'email' => ['nullable', 'email'],
            'telefono' => ['nullable', 'string', 'max:9'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        DB::beginTransaction();
        try {
            $pktipodoc = DB::table('dtipodocumentoidentidad')
                ->where('codtipodocumentoidentidad', '01')
                ->first();

            $pkclase = DB::table('dclasepersona')
                ->where('codclasepersona', '01')
                ->first();

            // Generar código de cliente correlativo
            $row = DB::selectOne("
                INSERT INTO dcliente (
                    pkcliente, codcliente, nomcliente,
                    pkclasepersona, codclasepersona, desclasepersona,
                    fechaingresocaja,
                    pktipodocumentoidentidad, codtipodocumentoidentidad, destipodocumentoidentidad,
                    numerodocumentoidentidad,
                    email, telefono, numerotelefonopersonal,
                    fecultactualizacion
                ) VALUES (
                    nextval('dcliente_pkcliente_seq'),
                    'CLI' || LPAD(currval('dcliente_pkcliente_seq')::text, 6, '0'),
                    ?,
                    ?, ?, ?,
                    CURRENT_DATE,
                    ?, ?, ?,
                    ?,
                    ?, ?, ?,
                    now()
                )
                RETURNING pkcliente, codcliente
            ", [
                $request->nombre,
                $pkclase->pkclasepersona, $pkclase->codclasepersona, $pkclase->desclasepersona,
                $pktipodoc->pktipodocumentoidentidad, $pktipodoc->codtipodocumentoidentidad, $pktipodoc->destipodocumentoidentidad,
                $request->dni,
                $request->email, $request->telefono, $request->telefono,
            ]);

            // Verificar username disponible
            $usernameExiste = DB::table('usuarios_homebanking')
                ->whereRaw('LOWER(username) = ?', [strtolower($request->username)])
                ->exists();

            if ($usernameExiste) {
                throw new \Exception('El nombre de usuario ya está en uso.');
            }

            // Crear acceso a HomeBanking
            DB::table('usuarios_homebanking')->insert([
                'pkcliente' => $row->pkcliente,
                'username' => strtolower($request->username),
                'password_hash' => Hash::make($request->password),
                'activo' => 'S',
                'bloqueado' => 'N',
                'intentos_fallidos' => 0,
                'debe_cambiar_password' => 'S',
                'fecultactualizacion' => now(),
            ]);

            DB::commit();

            return redirect()->route('core.clientes.apertura')
                ->with('success', "Cliente {$row->codcliente} registrado correctamente. Usuario HomeBanking: {$request->username}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al registrar: ' . $e->getMessage()])->withInput();
        }
    }
}