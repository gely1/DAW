<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use PDF;




//PARA LAS LIBRERIAS DEL PDF IR A ESTE LINK ----->>> https://github.com/barryvdh/laravel-dompdf
class ClientesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index(){
        if (Auth::user()->level != "admin"){
            return redirect('/admin');
        }
        $datos=\DB::table('clients')
            ->select('clients.*', 'users.id as idUser', 'users.email', 'users.name')                                                  
            ->orderBy('clients.id', 'DESC')
            ->join('users', 'id_user','=','users.id')
            ->get();

        // PARA RAW QUERY $datos=\DB::statement('select * from clients')

        // $datos2=\DB::table('users')
        //  ->select('users.*')     
        //  ->where('id','=', $datos[0]->id_user)                                             
        // ->orderBy('id', 'DESC')
        // ->get();
        
       
        return view('admin.clientes')->with('datos',$datos);
    }

    public function generar(){
        $datos=\DB::table('clients')
            ->select('clients.*', 'users.id as idUser', 'users.email', 'users.name')                                                  
            ->orderBy('clients.id', 'DESC')
            ->join('users', 'id_user','=','users.id')
            ->get();
        
        $fecha=date("Y-m-d");
        $todo=compact('datos', 'fecha');
    


        $pdf = PDF::loadView('reportes.clientes', $todo);
        // return $pdf->download('reporte.pdf');
        return $pdf ->download('reporte_'.date('Y_m_d_h_m_s').'.pdf');
    }
}
