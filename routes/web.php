<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpleadosController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DepartamentoController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\CargosController;
use App\Http\Controllers\RubroController;
use App\Http\Controllers\RolPagoController;
use App\Http\Controllers\DailyController;
use App\Http\Controllers\MatrizCumplimientoController;
use App\Http\Controllers\ParametroController;
use App\Http\Controllers\ImageController;
use App\Models\Image;
use Illuminate\Support\Facades\DB;



// Ruta de Bienvenida para todos 
Route::get('/', function () {
    $imagen = Image::latest()->first();
    return view('welcome', compact('imagen'));
})->name('welcome');



// Rutas de autenticación
Auth::routes(['register' => false]);  // Desactiva el registro si no lo necesitas

// Redirigir a la vista de bienvenida si alguien intenta acceder al login
Route::get('/login', function () {
    return redirect()->route('welcome');
})->name('login');

// Ruta de cerrar sesion
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirigir a la vista de bienvenida si alguien intenta acceder al registro
Route::get('/register', function () {
    return redirect()->route('welcome');
})->name('register');

// Ruta de cierre de sesión
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas que requieren autenticación
Route::middleware(['auth'])->group(function () {

    // Rutas comunes para administradores
    Route::middleware(['role:admin'])->group(function () {

        Route::resource('empleados', EmpleadosController::class)->names([
            'index' => 'empleados.indexEmpleados',
            'store' => 'empleados.store',
            'show' => 'empleados.show',
            'edit' => 'empleados.edit',
            'update' => 'empleados.update',
            'destroy' => 'empleados.destroy',
        ]);

        Route::resource('actividades', ActividadesController::class)->names([
            'index' => 'actividades.indexActividades',
            'store' => 'actividades.store',
            'show' => 'actividades.show',
            'edit' => 'actividades.edit',
            'update' => 'actividades.update',
            'destroy' => 'actividades.destroy',
        ]);

        Route::put('/actividades/{id}/observaciones', [ActividadesController::class, 'updateObservaciones'])->name('actividades.updateObservaciones');


        Route::resource('productos', ProductoController::class)->names([
            'index' => 'productos.index',
            'store' => 'productos.store',
            'show' => 'productos.show',
            'edit' => 'productos.edit',
            'update' => 'productos.update',
            'destroy' => 'productos.destroy',
        ]);

        Route::resource('clientes', ClienteController::class)->names([
            'index' => 'clientes.index',
            'store' => 'clientes.store',
            'show' => 'clientes.show',
            'edit' => 'clientes.edit',
            'update' => 'clientes.update',
            'destroy' => 'clientes.destroy',
        ]);

        Route::resource('departamentos', DepartamentoController::class)->names([
            'index' => 'departamentos.index',
            'store' => 'departamentos.store',
            'show' => 'departamentos.show',
            'edit' => 'departamentos.edit',
            'update' => 'departamentos.update',
            'destroy' => 'departamentos.destroy',

        ]);

        Route::resource('supervisores', SupervisorController::class)->names([
            'index' => 'supervisores.index',
            'store' => 'supervisores.store',
            'show' => 'supervisores.show',
            'edit' => 'supervisores.edit',
            'update' => 'supervisores.update',
            'destroy' => 'supervisores.destroy',
        ]);
        Route::post('/supervisores/asignar-superior', [SupervisorController::class, 'asignarSupervisorSuperior'])->name('supervisores.asignar-superior');
        Route::get('/supervisores/{id}/subordinados', [SupervisorController::class, 'obtenerSubordinados'])->name('supervisores.obtener-subordinados');

        Route::get('/departamentos/{id}/supervisor', [DepartamentoController::class, 'getSupervisores']);
        Route::get('/supervisores/{departamento_id}', [EmpleadosController::class, 'getSupervisoresPorDepartamento']);
        Route::get('/asignar-supervisor', [DepartamentoController::class, 'asignarSupervisor']);

        Route::get('/supervisores/departamento/{departamento_id}', [SupervisorController::class, 'getSupervisoresPorDepartamento']);


        Route::resource('cargos', CargosController::class)->names([
            'index' => 'cargos.index',
            'store' => 'cargos.store',
            'show' => 'cargos.show',
            'edit' => 'cargos.edit',
            'update' => 'cargos.update',
            'destroy' => 'cargos.destroy',
        ]);

        Route::resource('rubros', RubroController::class)->names([
            'index' => 'rubros.index',
            'store' => 'rubros.store',
            'show' => 'rubros.show',
            'edit' => 'rubros.edit',
            'update' => 'rubros.update',
            'destroy' => 'rubros.destroy',
        ]);

        Route::resource('roles_pago', RolPagoController::class)->names([
            'index' => 'roles_pago.index',
            'store' => 'roles_pago.store',
            'show' => 'roles_pago.show',
            'edit' => 'roles_pago.edit',
            'update' => 'roles_pago.update',
            'destroy' => 'roles_pago.destroy',
        ]);

        Route::resource('daily', DailyController::class)->names([
            'index' => 'daily.index',
            'create' => 'daily.create',
            'store' => 'daily.store',
            'show' => 'daily.show',
            'edit' => 'daily.edit',
            'update' => 'daily.update',
            'destroy' => 'daily.destroy',
        ]);

        Route::resource('matriz_cumplimientos', MatrizCumplimientoController::class)->names([
            'index' => 'matriz_cumplimientos.index',
            'create' => 'matriz_cumplimientos.create',
            'store' => 'matriz_cumplimientos.store',
            'show' => 'matriz_cumplimientos.show',
            'edit' => 'matriz_cumplimientos.edit',
            'update' => 'matriz_cumplimientos.update',
            'destroy' => 'matriz:cumplimientos.destroy',
        ]);

        Route::put('matriz_cumplimientos/{matriz_cumplimiento}', [MatrizCumplimientoController::class, 'update'])->name('matriz_cumplimientos.update');
        Route::put('/matriz_cumplimientos/update_puntos/{id}', [MatrizCumplimientoController::class, 'updatePuntos'])->name('matriz_cumplimientos.updatePuntos');




        Route::resource('parametros', ParametroController::class)->names([
            'index' => 'parametros.index',
            'store' => 'parametros.store',
            'show' => 'parametros.show',
            'edit' => 'parametros.edit',
            'update' => 'parametros.update',
            'destroy' => 'parametros.destroy',
        ]);

        Route::resource('imagen', ImageController::class)->names([
            'index' => 'imagen.index',
        ]);

        Route::post('/imagen', [ImageController::class, 'upload'])->name('images.upload');
    });

    // Rutas que solo puede acceder un empleado
    Route::middleware(['role:empleado'])->group(function () {



        //Ruta para ver el home
        Route::get('/home', [AuthController::class, 'home'])->name('home');

        Route::resource('actividades', ActividadesController::class)->names([
            'index' => 'actividades.indexActividades',
            'store' => 'actividades.store',
            'show' => 'actividades.show',
            'update' => 'actividades.update',


        ]);

        Route::put('/actividades/{id}/observaciones', [ActividadesController::class, 'updateObservaciones'])->name('actividades.updateObservaciones');


        Route::resource('daily', DailyController::class)->names([
            'index' => 'daily.index',
            'create' => 'daily.create',
            'store' => 'daily.store',
            'show' => 'daily.show',
            'edit' => 'daily.edit',
            'update' => 'daily.update',
            'destroy' => 'daily.destroy',
        ]);

        Route::resource('matriz_cumplimientos', MatrizCumplimientoController::class)->names([
            'index' => 'matriz_cumplimientos.index',
            'create' => 'matriz_cumplimientos.create',
            'store' => 'matriz_cumplimientos.store',
            'show' => 'matriz_cumplimientos.show',
            'edit' => 'matriz_cumplimientos.edit',
            'update' => 'matriz_cumplimientos.update',
            'destroy' => 'matriz_cumplimientos.destroy',

        ]);
        Route::put('matriz_cumplimientos/{matriz_cumplimiento}', [MatrizCumplimientoController::class, 'update'])->name('matriz_cumplimientos.update');
        Route::post('/matriz_cumplimientos/update_puntos/{id}', [MatrizCumplimientoController::class, 'updatePuntos'])->name('matriz_cumplimientos.updatePuntos');






        Route::put('actividades/{id}/avance', [ActividadesController::class, 'updateAvance'])->name('actividades.updateAvance');
        //    //Ruta de update de estado 
        Route::put('actividades/{id}/estado', [ActividadesController::class, 'updateEstado'])->name('actividades.updateEstado');

        Route::post('/actividades/{id}/start-counter', [ActividadesController::class, 'startCounter'])->name('actividades.startCounter');

        Route::get('/empleado/{id}/details', [EmpleadosController::class, 'getEmployeeDetails'])->name('empleado.details');
    });

    // Rutas que solo puede acceder un supervisor
    Route::middleware(['auth', 'supervisor'])->group(function () {
        Route::get('matriz_cumplimientos', [MatrizCumplimientoController::class, 'index'])->name('matriz_cumplimientos.index');
        Route::put('/matriz_cumplimientos/update_puntos/{id}', [MatrizCumplimientoController::class, 'updatePuntos'])->name('matriz_cumplimientos.updatePuntos');
    });



    Route::middleware(['check.user:id,8'])->group(function () {
        Route::resource('imagen', ImageController::class)->names([
            'index' => 'imagen.index',
        ]);

        Route::post('/imagen', [ImageController::class, 'upload'])->name('images.upload');
    });
});
