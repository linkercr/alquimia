<?php
use App\Factura;
use App\FacturaDetalle;
use App\Producto;
use App\User;
use App\MetodoPago;
use App\Empresa;
//tiquete para imprimir
function detalles_venta($id){
    date_default_timezone_set('America/Costa_Rica');
    //detalles de tiquete / factura
    $totalServGravados2 = 0;
    $totalGravados = 0;
    $totalExentos = 0;
    $totalVentas = 0;
    $totalDescuentos = 0;
    $total_con_descuento = 0;
    $total_linea = 0;
    $totalVentasNeta = 0;
    $totalComprobante = 0;
    $descuento_por_linea = 0;
    $otros = '';
    $TotalImpuesto = 0;
    $otrosType = 0;
    //servicos de restaurante
    $incluirServicioRestaurante = 0;
    //total por linea con iv
    $total_con_iv_x_linea = 0;
    $id_local = session()->get('Local_Selected');
    //detalles del recibo
    $recibo =
        ' <div class="col-md-10" id="printSection">
        <div class="text-center">';
    //datos empresa
    $datoPerfil = Empresa::all();

    $nombreComercial ='';
    $direccion = '';
    foreach ($datoPerfil as $key => $value){
        $nombreComercial = $value['nombre_empresa'];
        $direccion = $value['direccion_empresa'];

    }
    //datos del encabezado
    $recibo .= '<div class="table-responsive">
    <table class="table">
            <tr>
                <td color: #34495e;text-align:center">
                      <span style="color: #34495e;font-weight:bold">
                        ' . $nombreComercial . '</span><br>';
                        $infocontacto = DB::table('contacto_empresa')
                        ->join('contactos', 'contacto_empresa.contacto_id', '=', 'contactos.id')
                        ->join('tipo_dato_contacto', 'contactos.tipo_dato_id', '=', 'tipo_dato_contacto.id')
                         ->get();
                         //dd($infocontacto);
                     $total_row = $infocontacto->count();
                     if ($total_row > 0) {
                         foreach ($infocontacto as $row) {
                            $recibo .= $row->tdc_texto.': '.$row->c_info.'<br>';
                         }
                     }
                     $recibo .= '
                     Direccion:' . $direccion . '
                </td>
              </tr>
          </table>
        </div>';

        $recibo .= '<strong>Documento # </strong>' . numeor_factura($id) . '<br>';
    //encabezado tabla
    $recibo .= '<br>
             <div style="clear:both;">
               <table class="table" cellspacing="0" border="0">
                 <thead>
                   <tr>
                     <th >Detalle</th>
                     <th >Cant</th>
                     <th  >Prec</th>
                     <th  >Total</th>
                   </tr>
                 </thead>
                 <tbody>';
    ///obetnesmos datos de detalles
    ///obetnesmos datos de detalles
    ///obetnesmos datos de detalles
    ///obetnesmos datos de detalles
    ///obetnesmos datos de detalles
    ///obetnesmos datos de detalles

    $infoDetalle = DB::table('factura_detalles')
    ->join('productos', 'factura_detalles.producto_id', '=', 'productos.id')
    ->where('factura_detalles.factura_id', '=', 1)
     ->get();
     $producto = '';
     $cantidad = '';
     $precio = '';
     $cont = 0;
     $total_linea = 0;
     $lineas_fact = [];
     $total_row2 = $infoDetalle->count();
                     if ($total_row2 > 0) {
                         foreach ($infoDetalle as $row) {
                            $cont += 1;
                            $producto = $row->p_nombre;
                            $cantidad = $row->fd_cantidad;
                            $precio = $row->fd_precio_venta;
            $total_linea = $cantidad * $precio; //total linea bruto
                    // hay descuento
                    // llenamos array con iv
                    $recibo .= ' <tr>
                    <td >' . $producto.'</td>
                    <td >' . $cantidad . '</td>
                    <td >¢ ' . redondearDosDecimal($precio, 2);
                       $recibo .= '</td>
                    <td >¢ ' . redondearDosDecimal($total_linea, 2);
                       $recibo .= '</td>
                  </tr>';
        } //cierre foreach detalles de producto

        //recibo
        $colspan = '3';
        $colspan2 = '5';
        $recibo .=
            '<tr>
                 <td>Total Items</td>
                 <td>' . redondearDosDecimal($cont, 2) . '</td>
               </tr>
               <tr>
                 <td colspan="' . $colspan . '" >Total</td>
                 <td colspan="' . $colspan2 . '" >¢ ' . redondearDosDecimal($total_linea, 2) . '</td>
               </tr>';
        $recibo .=  '
               </tbody>
             </table>
              ';
        $recibo .= '
                 <p >
                   Gracias por permitirnos brindar los servicios de ' . $nombreComercial . '!
                   </p>
           </div>
         </div>
       </div>
    </div>
    </div>
  ';
} //cierre validacion si hay detalles
 return $recibo;
}
//brinda el estado de la factura
//brinda el estado de la factura
//brinda el estado de la factura
//brinda el estado de la factura
//brinda el estado de la factura
function estado_factura($productoId){
    $factura = Factura::findOrFail($productoId);
                //validamos si esta vacio el objecto
                $r = '';
                  if ($factura->count()) { //validamos si el ojecto no esta vacio
                    if($factura->f_pedido_aprobado == 3){
                        return 'Pendiente';
                    }elseif($factura->f_pedido_aprobado == 2){
                        return 'Rechazado';
                    }elseif($factura->f_pedido_aprobado == 1){
                        return 'Aprobado';
                    }
                  }else{
                    return 'Error';
                  }
}
//nombre del cliente(usuario regsitrado en el sitema)
function nombre_cleinte($id){
    $consulta = User::findOrFail($id);
                //validamos si esta vacio el objecto
                $r = 0;
                  if ($consulta->count()) { //validamos si el ojecto no esta vacio
                    $r =  $consulta->name;
                  }else{
                    $r =  0;
                  }
                  return $r ;
}
//devolvemos el nombre del producto por id
function nombre_producto($productoId){
    $productos = Producto::findOrFail($productoId);
                //validamos si esta vacio el objecto
                $r = 0;
                  if ($productos->count()) { //validamos si el ojecto no esta vacio
                    $r =  $productos->p_nombre;
                  }else{
                    $r =  0;
                  }
                  return $r ;
}
//redondear numeros formato de numero con decimales
//redondear numeros formato de numero con decimales
//redondear numeros formato de numero con decimales
//redondear numeros formato de numero con decimales
//redondear numeros formato de numero con decimales
function redondearDosDecimal($valor, $decimales) {
    /* $float_redondeado=round($valor * 100) / 100 + 1;
     return $float_redondeado;*/
     $factor = pow(10, $decimales);
     return (round($valor*$factor)/$factor);
  }
//validar si exisite el produto en la factura
function validar_existe_producto_factura($factura_id, $productoId){
    $exite_producto_fact = FacturaDetalle::where('factura_id', '=', $factura_id)
                ->where('producto_id', '=', $productoId)->first();
                //validamos si esta vacio el objecto
                  if ($exite_producto_fact) { //validamos si el ojecto no esta vacio
                    $rcount = $exite_producto_fact->count();
                    return $exite_producto_fact->fd_cantidad;
                  }else{
                      return 0;
                  }
}
//validamos si hay factura abierta
//validar si la factura en resume ya esta inciada
//numeor factura por id de grupo factura _x_usuario_textofactura
function check_numerofactura_pendiente($id_grupo_factura, $culumna, $idUsuario)
{
    $data =  DB::table('facturas')
        ->select('id as id_factura')
        ->where('textofactura_id', '=', $id_grupo_factura)
        ->where($culumna, '=', $idUsuario)
        ->where('f_estado', '=', '2')
        ->get();
    $id_factura = 0;
    foreach ($data as $key) {
        $id_factura = $key->id_factura;
    }
    return $id_factura;
}
//obtenemos el numero de factura actual segun el perfil, ambiente
//devulve la el ultmio registro
function ultimo_numero_factura()
{
    $data =  DB::table('facturas')
        ->select('f_numero as num')->get();
    if ($data->count()) {
        $num = 0;
        foreach ($data as $key) {
            $num = $key->num;
        }
        return $num;
    } else {
        return 0;
    }
}
//creacion de sesion de varibale numeor de factura
function Set_Factura_Session($IdFacturaPerfil)
{
    session(['NumeroFactura' => $IdFacturaPerfil]);
    //return response()->json('ok');
}
//numero de factura
function numeor_factura($id)
{
    return Factura::findOrFail($id)->f_numero;
}
//titpo pago
function metodo_pago_texto($id)
{
    return MetodoPago::findOrFail($id)->mp_texto;
}
function Un_Set_Factura_session()
{
    session()->forget('NumeroFactura');
    //return response()->json('Cerrada');
}
