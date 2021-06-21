<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Product;
use File;
use Auth;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->level != "admin"){
            return redirect('/admin');
        }
        $datos=\DB::table('products')
            ->select('products.*')cc                                                    x
            ->orderBy('id', 'DESC')
            ->get();


        return View('admin.products')->with('productos', $datos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator= validator::make($request->all(),[
            'nombre'=>'required|max:255|min:1',
            'precio'=>'required|max:255|min:1|numeric',
            'descripcion'=>'required|max:255|min:1',
            'stock'=>'required|max:255|min:1|numeric',
            'tags'=>'required|max:255|min:1',
            'imagen'=>'required|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ]);
        if($validator->fails()){
            
            return back()->withInput()
            ->with('errorInsert', 'Favor de llenar todos los campos')
            ->withErrors($validator);

        }else{
            $imagen = $request->file('imagen');
            $nombre=time().'.'.$imagen->getClientOriginalExtension();
            $destino= public_path('img/productos');
            $request->imagen->move($destino, $nombre);
            $producto = Product::create([
                'name'=>$request->nombre,
                'price'=>$request->precio,
                'description'=>$request->descripcion,
                'stock'=>$request->stock,
                'tags'=>$request->tags,
                'image'=>$nombre,
                'slug'=>'',
            ]); //INSERT
            $producto->save();
         return back()->with('Listo','Se ha insertado correctamente');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $validator= validator::make($request->all(),[
            'nombre'=>'required|max:255|min:1',
            'precio'=>'required|max:255|min:1|numeric',
            'descripcion'=>'required|max:255|min:1',
            'stock'=>'required|max:255|min:1|numeric',
            'tags'=>'required|max:255|min:1',
        ]);
        if($validator->fails()){
            
            return back()->withInput()
            ->with('errorEdit', 'Favor de llenar todos los campos')
            ->withErrors($validator);

        }else{
            $producto =Product::find($request->id);
            $producto->name=$request->nombre;
            $producto->price=$request->precio;
            $producto->description=$request->descripcion;
            $producto->stock=$request->stock;
            $producto->tags=$request->tags;
            $validator2= validator::make($request->all(),[
                'imagen'=>'required|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ]);
        if($validator2->fails()){
            
            $imagen = $request->file('imagen');
            $nombre=time().'.'.$imagen->getClientOriginalExtension();
            $destino= public_path('img/productos');
            $request->imagen->move($destino, $nombre);
            if(File::exists(public_path('img/productos/'.$producto->image))){
                unlink(public_path('img/productos/'.$producto->image));
            }
            $producto->imagen=$nombre;

        }
            
            $producto->save();
            return back()->with('Listo','Se ha actualizado correctamente');
            
        
    }
}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $producto = Product::find($id);
        if(File::exists(public_path('img/productos/'.$producto->image))){
            unlink(public_path('img/productos/'.$producto->image));
        }
        $producto->delete();
        return back()->with('Listo','Se ha borrado correctamente');
    }
}
