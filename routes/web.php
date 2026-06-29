<?php
use App\Http\Controllers\AperturaClienteController;
use App\Http\Controllers\GestionCuentaController;
use App\Http\Controllers\RegistroSolicitudController;
use App\Http\Controllers\MoraController;
use App\Http\Controllers\DesembolsoController;
use App\Http\Controllers\SolicitudBandejaController;
use App\Http\Controllers\SolicitudCreditoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\CuentaAhorroController;
use App\Http\Controllers\CreditoController;
use App\Http\Controllers\TransferenciaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\CoreAuthController;
use App\Http\Controllers\CoreDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    // ── Perfil de usuario ────────────────────────────────────────────────────
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');
    Route::patch('/perfil/password', [PerfilController::class, 'updatePassword'])->name('perfil.password');

    Route::get('/force-password-change', [\App\Http\Controllers\Auth\PasswordRecoveryController::class, 'showForceChangeForm'])->name('password.force.form');
    Route::post('/force-password-change', [\App\Http\Controllers\Auth\PasswordRecoveryController::class, 'forceChange'])->name('password.force.update');
    
    // Perfil legacy Breeze (mantener por compatibilidad)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── Cuentas de Ahorro ────────────────────────────────────────────────────
    Route::get('/cuentas/ahorro', [CuentaAhorroController::class, 'index'])->name('cuenta.index');
    Route::get('/cuentas/ahorro/{id}', [CuentaAhorroController::class, 'show'])->name('cuenta.show');

    // ── Créditos ─────────────────────────────────────────────────────────────
    Route::get('/creditos', [CreditoController::class, 'index'])->name('credito.index');
    Route::get('/creditos/simulador', [CreditoController::class, 'simulador'])->name('credito.simulador');
    Route::get('/creditos/solicitar', [SolicitudCreditoController::class, 'create'])->name('credito.solicitar');
    Route::get('/creditos/solicitudes/{id}', [SolicitudCreditoController::class, 'show'])->name('credito.solicitud.show');
    Route::post('/creditos/solicitar', [SolicitudCreditoController::class, 'store'])->name('credito.solicitar.store');
    Route::get('/creditos/{id}', [CreditoController::class, 'show'])->name('credito.show');

    // ── Transferencias ───────────────────────────────────────────────────────
    Route::get('/transferencias', [TransferenciaController::class, 'index'])->name('transferencias');
    Route::post('/transferencias', [TransferenciaController::class, 'store'])->name('transferencias.store');

    // ── Pagos ────────────────────────────────────────────────────────────────
    Route::get('/pagos/credito', [PagoController::class, 'creditoIndex'])->name('pagos.credito');
    Route::post('/pagos/credito', [PagoController::class, 'creditoStore'])->name('pagos.credito.store');
    Route::get('/pagos/servicios', [PagoController::class, 'serviciosIndex'])->name('pagos.servicios');
    Route::post('/pagos/servicios', [PagoController::class, 'serviciosStore'])->name('pagos.servicios.store');

});

// ── CORE FINANCIERO (Asesores) ───────────────────────────────────────────
Route::prefix('core')->group(function () {
    Route::get('/login', [CoreAuthController::class, 'showLoginForm'])->name('core.login');
    Route::post('/login', [CoreAuthController::class, 'login'])->name('core.login.post');
    Route::post('/logout', [CoreAuthController::class, 'logout'])->name('core.logout');

    Route::get('/dashboard', [CoreDashboardController::class, 'index'])->name('core.dashboard');

    // Pre-solicitud y registro: solo asesores
    Route::middleware('core.rol:asesor')->group(function () {
        Route::get('/presolicitud', [RegistroSolicitudController::class, 'preSolicitud'])->name('core.presolicitud');
        Route::post('/presolicitud', [RegistroSolicitudController::class, 'evaluarElegibilidad'])->name('core.presolicitud.evaluar');
        Route::get('/solicitudes/registro', [RegistroSolicitudController::class, 'buscar'])->name('core.solicitud.buscar.form');
        Route::post('/solicitudes/registro', [RegistroSolicitudController::class, 'buscarCliente'])->name('core.solicitud.buscar');
        Route::get('/solicitudes/registro/{pkcliente}', [RegistroSolicitudController::class, 'create'])->name('core.solicitud.registrar');
        Route::post('/solicitudes/registro/{pkcliente}', [RegistroSolicitudController::class, 'store'])->name('core.solicitud.registrar.store');
        Route::get('/solicitudes', [SolicitudBandejaController::class, 'index'])->name('core.solicitudes.bandeja');
        Route::post('/solicitudes/{id}/evaluar', [SolicitudBandejaController::class, 'evaluar'])->name('core.solicitud.evaluar');
        Route::get('/clientes/apertura', [AperturaClienteController::class, 'create'])->name('core.clientes.apertura');
        Route::post('/clientes/apertura', [AperturaClienteController::class, 'store'])->name('core.clientes.apertura.store');
    });

    // Comité: solo Funcionario de Créditos
    Route::middleware('core.rol:comite')->group(function () {
        Route::get('/solicitudes/comite', [SolicitudBandejaController::class, 'comite'])->name('core.solicitudes.comite');
        Route::post('/solicitudes/{id}/resolver', [SolicitudBandejaController::class, 'resolver'])->name('core.solicitud.resolver');
        Route::post('/mora/{id}/castigar', [MoraController::class, 'castigar'])->name('core.mora.castigar');
    });

    // Desembolso: comité o administrador
    Route::middleware('core.rol:comite,administrador')->group(function () {
        Route::get('/solicitudes/desembolso', [DesembolsoController::class, 'index'])->name('core.solicitudes.desembolso');
        Route::post('/solicitudes/{id}/desembolsar', [DesembolsoController::class, 'desembolsar'])->name('core.solicitud.desembolsar');
    });

    // Mora: administrador o analista (consulta), derivar judicial solo administrador
    Route::middleware('core.rol:administrador,analista,comite')->group(function () {
        Route::get('/mora', [MoraController::class, 'index'])->name('core.solicitudes.mora');
    });
    Route::middleware('core.rol:administrador')->group(function () {
        Route::post('/mora/{id}/judicial', [MoraController::class, 'derivarJudicial'])->name('core.mora.judicial');
        Route::get('/cuentas/gestion', [GestionCuentaController::class, 'index'])->name('core.cuentas.gestion');
        Route::post('/cuentas/{id}/estado', [GestionCuentaController::class, 'cambiarEstado'])->name('core.cuenta.cambiarestado');
    });

    // Ver detalle de solicitud: cualquier rol logueado puede ver
    Route::get('/solicitudes/{id}', [SolicitudBandejaController::class, 'show'])->name('core.solicitud.show');
});

require __DIR__.'/auth.php';