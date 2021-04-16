<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {return view('index');});



Route::get('/producto/{id}',
 function ($id) {
     return view('verproducto')->with('id',$id);
    });
Route::get('/contacto', 
function () {
    $contacto="Angélica Salaices";
    $valores=7;
    $color="#ccc";
    return view('contacto')
    ->with('nombre',$contacto)
    ->with('valores',$valores)
    ->with('fondo',$color);
});


Route::group(['prefix'=>'admin','as'=>'admin.'], function(){
Route::get('/', function () {return view('admin.index');});
Route::get('/usuarios', [App\Http\Controllers\admin\UsuariosController::class,'index'] );
Route::get('/productos', [App\Http\Controllers\admin\ProductosController::class,'index'] );

Route::resource('productos',App\Http\Controllers\admin\ProductosController::class);
Route::resource('usuarios',App\Http\Controllers\admin\UsuariosController::class);
});
