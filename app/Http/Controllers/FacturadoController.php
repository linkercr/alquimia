<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\DataTables;
use Auth;

class FacturadoController extends Controller
{
    //constructor para valdiar permisos
    public function __construct() {
        $this->middleware(['auth', 'isAdmin']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('facturas.index');
    }
         //obtener regsitro total de la bd
         public function Registro_Total_facturado()
         {
                $isadmin = 1; //es admin
                //validamos si hay abierto una factura
             $datos =DB::select('SELECT * FROM
             facturas
              INNER JOIN factura_detalles ON factura_detalles.factura_id = facturas.id
              INNER JOIN users ON facturas.usario_id = users.id
              WHERE
              factura_detalles.deleted_at IS NULL AND
              facturas.f_estado =1' );
             $obj = array();


               foreach ($datos as $key) {
                   $total = $key->fd_cantidad *  $key->fd_precio_venta;
                $obj[] = [
                    'id'=>$key->id,
                    'factura_id'=>$key->f_numero,
                    'usuario'=>$key->name,
                    'total'=> $total,
                    'fecha'=> date("d/m/Y", strtotime($key->created_at))
                  ];

               }
               return Datatables::of($obj)->make(true);
         }
    //detalles del recibo para imprimir
    public  function datosRecibo(Request $request)
    {
        return detalles_venta($request->input('numeroFactura'));
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
        //
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
    public function edit($id)
    {
        //
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
        //
    }
}
