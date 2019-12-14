<?php

namespace App\Http\Controllers;

use App\Factura;
use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\DataTables;
use Auth;
use App\FacturaDetalle;
use App\Producto;
use App\BitacoraEntrega;
use PDF;
use App\Categoria;
use App\FacturaPago;
use App\MetodoPago;

class FacturaController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //validamos si hay abierto una factura
        $datoId = Auth::user()->id;
        $idFacturaActual = check_numerofactura_pendiente(1, 'persona_id', $datoId );
         //obtenemos la info
         return view('carro.index');
    }

     //exportar a pdf
     public function export_pdf()
     {
       // Fetch all customers from database
       $data = Categoria::get();

       // Send data to the view using loadView function of PDF facade
       $pdf = PDF::loadView('pdf.customers', $data);
       // If you want to store the generated pdf to the server then you can use the store function
       $pdf->save(storage_path().'_filename.pdf');
       // Finally, you can download the file using download function
       return $pdf->download('customers.pdf');
     }
    //aprbar pedido  pedidos
    function aprobar_pedido(Request $request){
        $datoId = Auth::user()->id;
        $factura = Factura::findOrFail($request['Numerofactura']);
        // actualizamos
        $fechaEntregado = '';
        $datos = [];//array para la bitocora control de entregas
        if($request['f_estado_entrega'] == 1){
            $factura->f_fecha_entregado = date("Y-m-d");
            $factura->f_fecha_a_entregar= date("Y-m-d");
            $factura->f_estado_entrega = 1;
            //regsitramos en bitacora
        $datos = [
            'usario_id'=> $datoId,
            'factura_id'=> $request['Numerofactura'],
            'detalle'=> 'La factura #'.$request['Numerofactura']. ' fue entregada por el usuario '.nombre_cleinte($datoId).' en la fecha '.date("Y-m-d")
        ];
        }else{//programamos la entrega
            $fecha = date("Y-m-d", strtotime($request['f_fecha_a_entregar']));
            $factura->f_fecha_a_entregar=$fecha;
            $factura->f_estado_entrega = 2;
            $factura->f_fecha_entregado = NULL;
            $datos = [
                'usario_id'=> $datoId,
                'factura_id'=> $request['Numerofactura'],
                'detalle'=> 'La factura #'.$request['Numerofactura']. ' fue programada para entregar en la fecha '. $fecha.' por el usuario '.nombre_cleinte($datoId)
            ];
        }
        $factura->f_pedido_aprobado = 1;
        $factura->update();
        $info = BitacoraEntrega::create($datos);


        echo json_encode('ok');

    }
    function rechzar_pedido(Request $request){
        $factura = Factura::findOrFail($request['Numerofactura']);
        // actualizamos cantidad
        $factura->f_pedido_aprobado = 2;
        $factura->f_estado_entrega = 0;
        $factura->update();
        echo json_encode('ok');

    }
    //ejecutar pedido
    function ejecutar_pedido(Request $request){
        $numfac = $request['Numerofactura'];
        $factura = Factura::findOrFail($numfac);
        // actualizamos cantidad
        $factura->f_estado = 1;
        $factura->update();
        $detalleRecibo = detalles_venta($numfac);
        Un_Set_Factura_session();
        //echo json_encode('ok');
        return $detalleRecibo;

    }
    //actualizar cantidad en bd
    function actualizar_cantidad_bd(Request $request){
        $cantidad = $request['cantidad_factura'];
        $facturadetalle = FacturaDetalle::findOrFail($request['id_rw']);
        // actualizamos cantidad
        $facturadetalle->fd_cantidad = $cantidad;
        $facturadetalle->update();
        echo json_encode('ok');

    }
      //mostrar lista de productos en la factura
  public function lista_producto_en_factura(Request $request)
  {
    $idFacturaSession = session()->get('NumeroFactura');
    $sumaTotal = 0;
        //validar ajax
    if ($request->ajax()) {
        $origen = $request->get('origen');//valida si es pedidos o pos
        $info_detalles = '
        <div class="col-lg-8 col-xl-9">
            <div class="card">
                <div class="card-body">
                  <div class="table-responsive" >
                      <table  class="table table-striped table-bordered zero-configuration nowrap">
                        <thead>
                            <tr>
                               <th><div  >Nombre Producto</div></th>
                               <th><div  >Cantidad</div></th>
                               <th><div  >Precio</div></th>
                               <th><div >Total</div></th>
                               <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>';
        //hacemos la consulta en la bd
        $dataConsulta = FacturaDetalle::where('factura_id', $idFacturaSession)->get();
        //contamos si hay datos
        $total_row = $dataConsulta->count();
        if($origen=='cliente'){ //validamos cual es el origen
            //si el total_row es mayor a cero procedemos
            if ($total_row > 0) {
                foreach ($dataConsulta as $key => $value) {
                    $id_row = $value['id'];
                    $factura_id = $value['factura_id'];
                    $producto_id = $value['producto_id'];
                    $cantidad = $value['fd_cantidad'];
                    $precio = $value['fd_precio_venta'];
                    $total_linea = $cantidad * $precio;
                    $sumaTotal += $total_linea;
                    $info_detalles .= '
                                    <tr>
                                        <th>' . nombre_producto($producto_id) . '</th>
                                        <td><div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                        <button type="button" id="restar" onclick="restarcantidad(' . $id_row . '), actualizar(' . $id_row . ')" class="btn btn-warning btn-md">
                                        <span class="fa fa-minus"></span><span class="hidden-sm"></span></button>
                                        </div>
                                        <input type="text" onchange="actualizar(' . $id_row . ')" class="form-control" id="cantidad_factura_' . $id_row . '" value="' . $cantidad . '">
                                        <div class="input-group-append">
                                        <button type="button" id="sumar" onclick="sumarcantidad(' . $id_row . '), actualizar(' . $id_row . ')" class="btn btn-success btn-md">
                                        <span class="fa fa-plus"></span><span class="hidden-sm"></span></button>
                                        </div>
                                        <input type="hidden" id="Numerofactura" value="' . $factura_id . '">
                                        <input type="hidden" id="id_producto_factura_' . $id_row . '" value="' . $producto_id . '">
                                        </div>
                                        </td>
                                        <td> ¢' . redondearDosDecimal($precio,2) . '</td>

                                        <td> ¢ ' . redondearDosDecimal($total_linea,2) . '</td>
                                        <td><button type="button" id="eliminar" onclick="deletedForm(' . $id_row . ')" class="btn btn-danger btn-md">
                                        <span class="fa fa-trash"></span><span class="hidden-sm"></span></button></td>
                                    </tr>';
                  } //cierre bucle de detalles
                  $info_detalles .= '
            <tr> <td class="text-right" colspan=4>Total </td>
                 <td class="text-right" colspan=4>¢ '. redondearDosDecimal($sumaTotal,2) . '</td>
            </tr>';
            //obtenemos info de factura
            $factura = Factura::findOrFail($idFacturaSession);
                              $info_detalles .= '
                   </tbody>
                </table>
               </div>
              </div>
            </div>
          </div>';
          if (Auth::user()->hasRole('Cliente')){
          $info_detalles .= '
  <div class="col-lg-4 col-xl-3">
      <div class="card">
         <div class="card-body">
            <div class="media align-items-center mb-4">
                <div class="media-body">
                <h4>Estado</h4>
                <ul class="card-profile__info">
                <li class="mb-1">
                <strong class="text-dark mr-4">
                <h3><code>' .
                estado_factura($idFacturaSession) . '</code>
                </h3>
                </strong></li>
               </ul>
          </div>
  </div>
  <div class="row mb-5">
          <div class="col-12 text-center">';
          if($factura->f_estado == 2){
              $info_detalles .= '
              <button id="completarventa" type="button" onclick="ejecutarPedido();" class="btn btn-success px-5">Solicitar
              </button>
      <input type="hidden" id="monto_bd" value="">
      <input type="hidden" id="total_factura" value="">
      </div>
  </div>

       </div>
     </div>
    </div>';

          }else{
            $info_detalles .= '
            <button  type="button"  class="btn btn-warning px-5">Sulicitud Precentada
            </button>
    </div>
</div>

     </div>
   </div>
  </div>';
          }
        }else{//admin procesar pagos FacturaPagoController
            $metodoPago = MetodoPago::all();
            $pagos_registrados = FacturaPago::where('factura_id', '=', $idFacturaSession)->get();

                    $info_detalles .= '
                    <div class="col-lg-4 col-xl-3">
      <div class="card">
         <div class="card-body">
            <div class="media align-items-center mb-4">
                <div class="media-body">
                    <h4>Pagos</h4>
                           <ul class="card-profile__info">';
                $operacion = 0;
                $sumaBD = 0;
                foreach ($pagos_registrados as $key) {
                  $sumaBD += $key->fp_monto;
                  $info_detalles .= '<li class="mb-1">
                             <strong class="text-dark mr-4">' .
                    metodo_pago_texto($key->metodo_pago_id) . '</strong>
                             <span>' . $key->fp_monto . '</span></li>';
                }
                $info_detalles .=  '
                              </ul>

                          <h3 class="mb-0">Metodo Pago</h3>
                      <div class="form-group col-md-12">
                        <label>Opciones</label>
                        <select class="form-control" id="id_metodo_pago" name="id_metodo_pago" >
                          <option >Selecione...</option>';
                foreach ($metodoPago as $row) {
                  $slec = '';
                  if ($row->id == 1) {
                    $slec = 'selected';
                  }
                  $info_detalles .= '<option value="' . $row->id . '" ' . $slec . '>' . $row->mp_texto . '</option>';
                }
                $info_detalles .= '</select>
                    </div>
                </div>
        </div>

        <div class="row mb-5">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Monto</label>';
                if ($sumaBD == $sumaTotal) {
                  // pago igual al total factura
                  $info_detalles .=   '<input type="hidden" class="form-control" id="monto" name="monto">';
                } elseif ($sumaBD > $sumaTotal) {
                  $operacion = $sumaBD - $sumaTotal;
                } else {
                  // code...
                  $operacion = $sumaTotal - $sumaBD;
                  $info_detalles .= '<input type="text" class="form-control is-invalid" id="monto" name="monto" value="' . redondearDosDecimal($operacion,2) . '">';
                }
                $info_detalles .=  '
                </div>
              <div class="form-group col-md-6">
                  <label>Vuelto</label>
                  <input type="text" class="form-control" id="cambio" name="cambio" value="' . redondearDosDecimal($operacion, 2) . '" readonly >
              </div>
            </div>
            <div class="col-12 text-center">';
            if($sumaBD < $sumaTotal){
                $info_detalles .= '
                <button id="agregar_pagp" type="button" onclick="agregarPagosFacturas();" class="btn btn-warning px-5" >Agregar</button>
               ';
            }else{
                $info_detalles .= '
                <button id="completarventa" type="button" onclick=" ejecutarPedido()();" class="btn btn-success px-5">Completar
                </button>
                ';
            }
            $info_detalles .=  '
            <input type="hidden" id="monto_bd" value="' . $sumaBD . '">
            <input type="hidden" id="total_factura" value="' . $sumaTotal . '">
            </div>
        </div>

             </div>
           </div>
          </div>';


        }

            }else{//cierre de la valdiacion si hay regsitro en la bd
                $info_detalles = '
   <div class="col-lg-8 col-xl-9">
       <div class="card">
           <div class="card-body">
             <div class="alert alert-warning">
                No hay datos registrados!.
              </div>
            </div>
        </div>
    </div>
   ';
            }

        }else{// si no es pos mas opciones

        }

        $datar = array(
          'detalles'  => $info_detalles,
          'total_data'  => $total_row
        );
        //return Datatables::of($productos_factura)->make(true);
        echo json_encode($datar);
      } //cierre if

  }
  public function crear_metodos_pagos_facturas(Request $request)
  {
    $idFacturaSession = $request['Numerofactura'];
    $id_metodo_pago = $request['metodo_pago'];
    $monto_ofrecido = $request['monto_ofrecido'];
    $nuevo_monto = 0;
    $registro = FacturaPago::where('factura_id', '=', $idFacturaSession)
      ->where('metodo_pago_id', '=', $id_metodo_pago)->first();
    $id_pago_tb = 0;
    $monto_bd = 0;
    if ($registro) { //si objeto no esta vacio
      $id_pago_tb = $registro->id;
      $monto_bd = $registro->fp_monto;
      $nuevo_monto = $monto_bd + $monto_ofrecido;

      //  $update_pa = MetodoPagoFacturaPerfil::findOrFail($id_pago_tb);
      //actualizamos metodo
      // actualizamos cantidad
      $registro->fp_monto = $nuevo_monto;
      $registro->update();
      $registrar = $registro;
    } else {
      // procedemos a crear datos en tb metodo_pago_factura_perfil session()->get('IdFacturaPerfil')
      $resumen = [
        'factura_id'=>$idFacturaSession,
        'metodo_pago_id' => $id_metodo_pago,
        'fp_monto' => $monto_ofrecido,
        'fp_vuelto' => 0
      ];

      $registrar =  FacturaPago::create($resumen);
    }

    return $registrar;
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
        //array vacio para mensajes
        $resp = [
            'tipo'=>'',
            'mns'=>'',
            'factura'=>''
        ];
        Un_Set_Factura_session();
        //variables
            $usuarioId = 0; $clienteId = 0; $textoFactura = 0; $condicionVenta = 0;
            $diasCredito = 0; $estadoPedido = 0; $estadoEntrega = 0; $fechaEntrega = '';
            $fechaAEntregar = NULL;
            $tipoVenta = $request['tipoVenta'];
            $columnaAValidar = '';
            $datoId = '';
            //tabla detalles de la factura
            $cantidad = 0;
            $productoId = $request['producto_id'];
            //buscamos info del producto
            $productos = Producto::findOrFail($productoId); //Find post of id = $id
            $costo = 0;
            $precio = 0;
            if($productos->p_precio_costo !=''){
                $costo = $productos->p_precio_costo;
            }else{
                    $costo = '0.00';
                }
                $precio = $productos->p_precio_venta;
            if($tipoVenta == 1){//pos venta hay dos modo de facturar, 1 por venta directa 2 pedidos por usuarios(cliente)
                //Validating title and body field
             $this->validate($request, [
            'condicion_id'=>'required'
            ]);
                $usuarioId =Auth::user()->id;
                $clienteId = $request['persona_id'];
                $textoFactura = '2';
                $condicionVenta = $request['condicion_id'];
                $diasCredito = $request['fd_dias_credito'];
                $estadoPedido = 1;
                $estadoEntrega = 1; //$request['f_estado_entrega'];
                $fechaAEntregar = date("Y-m-d", strtotime(date('Y-m-d')));
                    $fechaEntrega = date("Y-m-d", strtotime(date('Y-m-d')));
                ///
                $columnaAValidar = 'usario_id';//como es carro pos validamos el id del usurio vendiendo
               $datoId = $usuarioId; //pasamos el id del usaurio como vendedor
               //tabla detalles de factira
               $cantidad =$request['cantidad'];
               $precio = $request['precio_venta'];
            }elseif($tipoVenta == 2){//pedidos en lineas
                $usuarioId = 1;
                $clienteId = Auth::user()->id;
                $textoFactura = 1;
                $condicionVenta = 2;
                $diasCredito = 0;
                $estadoPedido = 3;
                $estadoEntrega = 2;
                $fechaAEntregar = NULL;
                $fechaEntrega = NULL;
                $columnaAValidar = 'persona_id';//como es pedido el id del usurio que ingreso
                $datoId = $clienteId; //pasamos el id del usaurio que ingreso que va a la columna persona
                //tabla detalles de facturas
                $cantidad = 1;
            }
            //validamos si hay abierto una factura
            $idFacturaActual = check_numerofactura_pendiente($textoFactura, $columnaAValidar, $datoId );
            //llenamos los datos par aregistrar
            if($idFacturaActual > 0){ //si el dato es mayor a cero devuelve el id de la factura
                $factura_id =0;
                $detalle_id=0;
                //buscamos si exite el producto en la factura actual
                $val = validar_existe_producto_factura($idFacturaActual, $productoId);
                  if($val >0){//si es mayor a 0(cero) se actualiza cantidad
                    $buscarDtalleFactId = FacturaDetalle::where('factura_id',$idFacturaActual)
                    ->where('producto_id', $productoId)
                    ->get();
                    //recorremos resultados
                    foreach ($buscarDtalleFactId as $key) {
                        $factura_id  = $key->factura_id;
                        $detalle_id = $key->id;
                    }
                    Set_Factura_Session($factura_id);//set una variable de sesion el numero
                    $nuevaCantidad = validar_existe_producto_factura($factura_id, $productoId) +$cantidad;
                  //encontramos el producto para actualizar
                  $facturaDetallesup = FacturaDetalle::findOrFail($detalle_id);
                  $facturaDetallesup->fd_cantidad = $nuevaCantidad;
                  $facturaDetallesup->save();
                }else{//si es 0(cero) no se encuentra el producto en la factura actual
                    //registramos nueva linea
                    //array para la tabla detalles de factura
                    $datosdetalle = [
                        'factura_id'=>$idFacturaActual,
                        'producto_id'=>$productoId,
                        'fd_cantidad'=>$cantidad,
                        'fd_precio_costo'=>$costo,
                        'fd_precio_venta'=>$precio,
                        //'fd_iva', agregar iva a producto si desean aplicar impuestos, el campo ya eta en esta tbl
                        //'fd_descuento',
                        //'fd_nota'
                    ];
                    $facturaDetalles = FacturaDetalle::create($datosdetalle);
                }
                //enviamos un array con los mensajes
                $resp = [
                    'tipo'=>'success',
                    'mns'=>'Producto se ha agregado a la factura',
                    'factura'=>$idFacturaActual
                ];


            }else {//de lo contario devuelve 0(cero) se crea una nueva factura
                // creamos una nueva factura para el grupo de facturacion
                $nuevoNumeroFactura = ultimo_numero_factura() + 1;
                //array para la tabla factura
            $data = [
                'f_numero'=>$nuevoNumeroFactura,
                'usario_id'=>$usuarioId,
                'persona_id'=>$clienteId,
                'textofactura_id'=>$textoFactura,
                'condicion_id'=>$condicionVenta,
                'fd_dias_credito'=>$diasCredito,
                'f_estado'=>2,
                'f_tipo_factura'=>$tipoVenta,
                'f_pedido_aprobado'=>$estadoPedido,
                'f_estado_entrega'=>$estadoEntrega,
                'f_fecha_a_entregar'=>$fechaAEntregar,
                'f_fecha_entregado'=>$fechaEntrega,
            ];

            $factura = Factura::create($data);
            if($factura){
                  //buscamos si exite el producto
                  $factura_id = $factura->id;
                  Set_Factura_Session($factura_id);//set una variable de sesion el numero
                  $nuevaCantidad = validar_existe_producto_factura($factura_id, $productoId) +$cantidad;
                 $datosdetalle = [
                    'factura_id'=>$factura_id,
                    'producto_id'=>$productoId,
                    'fd_cantidad'=>$nuevaCantidad,
                    'fd_precio_costo'=>$costo,
                    'fd_precio_venta'=>$precio,
                    //'fd_iva', agregar iva a producto si desean aplicar impuestos, el campo ya eta en esta tbl
                    //'fd_descuento',
                    //'fd_nota'
                ];
                $facturaDetalles = FacturaDetalle::create($datosdetalle);
            }
            //enviamos un array con los mensajes
            $resp = [
                'tipo'=>'success',
                'mns'=>'Producto se ha agregado a la factura ',
                'factura'=>$nuevoNumeroFactura
            ];
            }

    //Display a successful message upon save
        return $resp;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function show(Factura $factura)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function edit(Factura $factura)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Factura $factura)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = FacturaDetalle::findOrFail($id);
        $post->delete();

        return $post;
    }
}
