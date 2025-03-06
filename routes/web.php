<?php

use App\Http\Controllers\Agenda;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EmpleadoController;

Route::get('/',[HomeController::class,'index'])->name('app.home')->middleware('auth');

Route::get('/login',[LoginController::class,'showlogin'])->name('app.login');
Route::post('/login', [loginController::class, 'login'])->name('login');
Route::post('/logout', [loginController::class, 'logout'])->name('logout');
Route::get('/obtener/sucursales/{usuario}', [loginController::class, 'getSucursalesByUsuario']);

//route para Usuarios
Route::get('/usuarios',[UsuarioController::class,'index'])->name('app.user');
Route::post('/usuarios/crear', [UsuarioController::class, 'CrearUsuario'])->name('usuarios.Crear');
Route::post('/Usuarios/show', [UsuarioController::class, 'UsuarioAll'])->name('usuarios.tabla');
Route::post('/obtener-usuario',[UsuarioController::class,'get_usuario_by_id'])->name('app.Usuarios.getUsuario');
Route::post('/modulo/save',[UsuarioController::class,'save_modulo'])->name('app.modulo.save');
Route::post('/modulos/show', [UsuarioController::class, 'modulosAll'])->name('modulos.tabla');
Route::post('/permisos/show', [UsuarioController::class, 'permisosAll'])->name('permisos.tabla');
Route::post('/permiso/save',[UsuarioController::class,'save_permiso'])->name('app.permiso.save');
Route::post('ruta/eliminar-sesion',[UsuarioController::class,'deletesession'])->name('app.session.deelte');
Route::post('/CambiarPassword', [UsuarioController::class, 'CambiarPassword'])->name('Cambiar.pasword');


//ROUTE PARA EMPRESAS
Route::get('/empresa',[EmpresaController::class,'index'])->name('app.empresa');
Route::post('/Empresas/show', [EmpresaController::class, 'EmpresasAll'])->name('empresas.tabla');
Route::post('/Sucursal/show/{empresa_id}', [EmpresaController::class, 'SucursalAll'])->name('sucursal.tabla');
Route::post('/empresa/guardar', [EmpresaController::class, 'guardarEmpresa'])->name('empresas.crear');
Route::post('/SaveHorario', [EmpresaController::class, 'guardarHorarios'])->name('horarios.guardar');
Route::get('/getHorarioEmpresa/{empresa_id}', [EmpresaController::class, 'getHorariosEmpresa'])->name('horarios.empresa');
Route::post('/obtener-emp',[EmpresaController::class,'getEmpresaEdit'])->name('app.empresa.getEmpresa');
Route::post('/actualizar-empresa', [EmpresaController::class, 'actualizarEmpresa']);
Route::get('/det_jerarquia/check', [EmpresaController::class, 'checkJerarquia'])->name('app.det_jerarquia.check');
//Route::get('/det_emp/check', [EmpresaController::class, 'dataEmp'])->name('app.det_emp.check');
Route::post('/det_jerarquia/save', [EmpresaController::class, 'saveJerarquia'])->name('app.det_jerarquia.save');
Route::post('/save_jerarquia',[EmpresaController::class,'save_jerarquias'])->name('jerarquia.save');
Route::post('/sucursal/guardar', [EmpresaController::class, 'guardarSucursal'])->name('sucursal.crear');
Route::post('/sucursal/edit',[EmpresaController::class,'getSucursalEdit'])->name('app.sucursal.edit');
Route::post('/sucursal/save_edit', [EmpresaController::class, 'guardarEditSucursal'])->name('sucursal.save.edit');
//
Route::post('/obtenEmpresa', [EmpresaController::class, 'getEmpresas'])->name('empresas.get.api');
Route::post('/empresass/guardar', [EmpresaController::class, 'SavNewEmpresa'])->name('empresas.save.api');
Route::post('/datosUser', [EmpresaController::class, 'obtenerInformacion'])->name('empresas.datos.usuario');

//Routas para empleados
Route::prefix('empleados')->middleware('auth')->group(function(){
    Route::get('/',[EmpleadoController::class,'index'])->name('app.empleados.index');
});