@extends('layouts.app')

@section('title', '| Personas registradas')

@section('content')

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                            <div class="panel panel-default">
                                    <div class="panel-heading">
                                         <div class="panel-title pull-left">
                                             <h3 class="m-0 text-primary">Productos en el Carrito</h3>
                                         </div>


                                        <div class="panel-title pull-right">
                                            <a href="{{ route('productos.index')}}" type="button" class="btn mb-1 btn-flat btn-outline-success">
                                                Seguir agregando</a></div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <input type="hidden" id="search" value="">{{--imput para q cargue el registro ajax la tbala detalles facturas--}}
                                @if (Auth::user()->hasRole('Admin'))
                                {{--contneido --}}
<div class="row">

<button  type="button" id="btnBuscarProducto" class="btn btn-success" data-toggle="modal" data-target="#ModalBuscar" >Buscar Producto</button>
<div class="modal-body" id="PreordenFact">
          <div id="msg_fact"></div>
        </div>

</div>
<form method="post" data-toogle="validator" >
<div class="row">
<div class="col-lg-6" id="divdias">
  <div class="form-group">
    <label >Días de Credito</label>
        <input type="text" id="dias_cretido" class="form-control" placeholder="30">
  </div>
</div>
<div class="col-lg-6">
    <div class="form-group">
      <label >Condicion Venta *</label>
          <select class="form-control" id="id_condicion_venta" name="id_condicion_venta" >
            <option >Selecione...</option>
              <option value="1" >Contado</option>
              <option value="2" >Credito</option>
          </select>
    </div>
</div>

<div class="col-lg-6">
  <div class="form-group">

    <div class="form-group row" id="divcheck">
        <label class="col-lg-3 col-form-label" for="val-username">
           <input type="checkbox" class="form-check-input" id="incluirCliente">Incluir Cliente
        </label>
        <div class="col-lg-7" id="divcliente">
          <label id="labcliente">Cliente</label>
            <input type="text" id="buscar_persona" class="form-control" placeholder="Search Person">
            <div id="cleintes"></div>
        </div>
    </div>
    <input type="hidden" id="usario_id" value="">
  </div>
</div>
</div>
</form>
{{--modals --}}
<!-- Modal recibos  -->
<!-- Modal -->
<div class="modal fade" id="detallerecibo" style="overflow-y: scroll;">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles</h5>
                    <button type="button" class="close" data-dismiss="modal" onclick="mostrarModalGrupoFact();"><span class="ti-close"></span>
                    </button>
                </div>

                                  <div class="modal-body" id="datosRecibo">

                                  </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" onclick="imprimirFact();">Imprimir</button>
                  <button type="button" class="btn btn-secondary" onclick="mostrarModalGrupoFact();" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
{{--contenido admin--}}
                                <input type="hidden" id="id_factura_perfil" value="">
<input type="hidden" id="id_grupo_factura" value="">

                                @endif


                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="datos_factura">

            </div>
            {{--modal productos--}}
            <div class="modal fade bd-example-modal-lg" id="ModalBuscar" tabindex="-1" role="dialog" aria-hidden="true" style="overflow-y: scroll;">
                    <div class="modal-dialog modal-lg">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h5 class="modal-title" id="ModalLabel">Agregar</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                          </button>
                                      </div>
                                      {{--productos para el local--}}
                        <div class="table-responsive " >
                                <table id="info-table" class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th><div class="pull-center" >Nombre</div></th>
                                                <th><div class="pull-center" >Cantidad</div></th>
                                                <th><div class="pull-center" >Precio</div></th>
                                                <th>Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                        </div>

                                      {{--fin de productos locales --}}
                                  </div>
                              </div>
                          </div>
@endsection
@section('js_bajo_body')
@if (Auth::user()->hasRole('Admin'))


<script>
     var table1 = $('#info-table').DataTable({
          processing:true,
          serverSide:true,
          responsive: true,
          ajax: "{{route('all.productos')}}",
          columns:[
           {data:'p_codigo'},
            {data:'p_nombre'},
            {"render": function (data, type, row) {
    return '<div class="input-group mb-2">'+
    '<input class="form-control form-control-sm" type="text"  id="cantidad_' + row.id + '" value="1">'+
    '</div>';
}},
{"render": function (data, type, row) {
        return '<div class="input-group mb-2">'+
                '<div class="input-group-prepend">'+
                '<div class="input-group-text">¢</div>'+
                '</div>'+
                '<input type="text" onchange="cambiarPreciostbP(' + row.id + ', 1)" class="form-control" id="precio_' + row.id + '" value="' +  row.venta + '">';
   }},
            {"render": function (data, type, row) {
                return  '<a type="button"  href="#" onclick="addProducto(1,'+row.id+');"  class="editar btn btn-success botonEditar btn-md">'+
             '<span class="fa fa-plus"></span><span class="hidden-xs"></span></a></div>';
           }},

          ],

        });$('select, input[type="search"]').css({
                    "background-color": "#f3f3f3",
                    "font-weight": "bold"
                });

  //funciones generar pagos para la factura actual
  function agregarPagosFacturas() {
           //$('#datos_factura').html("");
          var id_factura_grupo = $('#id_grupo_factura').val();
          var id_factura_perfil = $('#id_factura_perfil').val();
        var monto_ofrecido = $('#monto').val();
        var metodo_pago = $('#id_metodo_pago').val();
        var Numerofactura = $('#Numerofactura').val();
        console.log(Numerofactura);

        var cambio = $('#cambio').val();
        $.LoadingOverlay("show");
          //comprobar que el valor escrito, son solo números.
            if(isNaN(monto_ofrecido) || monto_ofrecido === '') {
              swal({
                    title: 'Oops!',
                    text: "Monto solo debe contener números",
                    type: 'error',
                    confirmButtonText: 'OK!'
                  });
                  return false;
              }
              if(isNaN(metodo_pago) || metodo_pago=== 'Selecione...') {
                swal({
                      title: 'Oops!',
                      text: "Selecione un metodo de pago",
                      type: 'error',
                      confirmButtonText: 'OK!'
                    });
                    return false;
                }
                url = "{{ route('pagos.proceder')}}";
                $.ajax({//4
                  url : url,
                  type : "POST",
                  data: {
                    '_token': $('input[name=_token]').val(),
                    'Numerofactura': Numerofactura,
                    'metodo_pago': metodo_pago,
                    'monto_ofrecido': monto_ofrecido,
                    'metodo_pago':metodo_pago
                  } ,
                  success: function(data){ //5
                   // console.log(data);
                   $.LoadingOverlay("hide");
                    detalles_facturacion('');//refresca la tabala
                    alerttoastr('success','Pago se ha agregado correctamente!', 'Buen Trabajo!', 'bottom-left');
                  }, //5
                  error : function(data){ //7
                    console.log(data);
                    $.LoadingOverlay("hide");
                    $('#bnt_insert').attr("disabled", false);
                    $('#bnt_insert').removeClass("btn btn");
                    $('#bnt_insert').addClass("btn btn-primary");
                    swal({
                          title: 'Oops!',
                          text: "No se ha podido guardar el registrado!",
                          type: 'error',
                          confirmButtonText: 'OK!'
                        });
                  } //7
                }); //4


        }
   $('#datos_factura').html("");
  $('#divcliente').hide();
  $('#btnBuscarProducto').hide();
  $('#divdias').hide();
 //si hay q inlcuir persona
$('#incluirCliente').click(function() {
    if($(this).is(':checked')){
        //alert('checked');
        $('#divcliente').show();
        $('#cleintes').html('');
    }else{
      //  alert('unchecked');
      $('#divcliente').hide();
      $('#usario_id').val('');
      $('#buscar_persona').val('');
      $('#cleintes').html('');
     }
});
//validamos si es credito o no
$(document).on('change', '#id_condicion_venta', function(){
  if ($('#id_condicion_venta').val() == 2) {
    $('#btnBuscarProducto').show();
  $('#divdias').show();
}else if ($('#id_condicion_venta').val() == 1){
    $('#btnBuscarProducto').show();
  $('#divdias').hide();
}else{
    $('#divdias').hide();
}
});

////funcion selccionar grupo de facturacion
function addProducto(i, b) {
//console.log($('input[name=_token]').val());

$.LoadingOverlay("show");
// correcto insertamos en la bd
var persona_id = $('#usario_id').val();
var condicion_id = $('#id_condicion_venta').val();
var fd_dias_credito = $('#dias_cretido').val();
var cantidad = $('#cantidad_'+b).val();
var precio_venta = $('#precio_'+b).val();
url = "{{ url('carro')}}";
$.ajax({//4
  url : url,
  type : "POST",
  data: {
    '_token': $('input[name=_token]').val(),
    'tipoVenta':i,
    'persona_id': persona_id,
    'fd_dias_credito':fd_dias_credito,
    'condicion_id':condicion_id,
    'cantidad': cantidad,
    'precio_venta':precio_venta,
    'producto_id': b

  },
  processData: true,
//id_factura_perfil
  success: function(data){ //5
  //console.log(data);
  $.LoadingOverlay("hide");
  detalles_facturacion('');
    //success, warning, info, warning, error, position: right, left, top, bottom, Top Full Width
    //Bottom Full Width, Top Center, Bottom Center
    alerttoastr(data.tipo,data.mns+' #'+data.factura, 'Genial!', 'bottom-left');

  }, //5
  error : function(data){ //7
 console.log(data);
 $.LoadingOverlay("hide");
    swal({
          title: 'Oops!',
          text: "El no se ha podido guardar el registrado!",
          type: 'error',
          confirmButtonText: 'OK!'
        });
  } //7
}); //4

}

    </script>
@endif

@if (!Auth::user()->hasRole('otros'))
<script>
      //detalles de la facturacion
 detalles_facturacion();
 $(document).on('keyup', '#search', function(){
  var query = $(this).val();

  detalles_facturacion(query);
 });
//cargamos la tabla detalles de la facturacion
function detalles_facturacion(query = '')
{
    var origen = 'cliente';
    $.LoadingOverlay("show");
 $.ajax({
  url:"{{ route('all.productos_facturas') }}",
  method:'GET',
  data:{query:query, origen: origen},
  dataType:'json',
  success:function(data)
  {
    $.LoadingOverlay("hide");
    $('#datos_factura').html("");
   // console.log(data.detalles);
   $('#datos_factura').html(data.detalles);
   $('#total_records').text(data.total_data);

 }
});
}

//sumar cantidad
function sumarcantidad(i){
var cantidad = $('#cantidad_factura_'+i).val();
if ( cantidad=='' || cantidad > 0) {
  var suma = parseFloat(cantidad) + 1;
  $('#cantidad_factura_'+i).val(suma);
}

}
function restarcantidad(i){
  var cantidad = $('#cantidad_factura_'+i).val();
  if (cantidad>0) {
    var suma = cantidad - 1;
    $('#cantidad_factura_'+i).val(suma);
  }else {
    $('#cantidad_factura_'+i).val('1');
  }

}
//funcion para actualizar bd con los input de la dataTable lista_producto_en_factura
function actualizar(i){
  //i es el id de la tabla detalles
    var cantidad = $('#cantidad_factura_'+i).val();
    //comprobar que el valor escrito, son solo números.

        if(isNaN(cantidad) || cantidad=== '') {
          swal({
                title: 'Oops!',
                text: "Cantidad solo debe contener números",
                type: 'error',
                confirmButtonText: 'OK!'
              });
              return false;
          }
          url = "{{ route('detalle.proceder')}}";
          $.ajax({//4
            url : url,
            type : "POST",
            data: {
              '_token': $('input[name=_token]').val(),
              'id_rw': i,
              'cantidad_factura':cantidad,
              'tb_id':'actualizar'
            } ,
            success: function(data){ //5
            console.log(data);

            detalles_facturacion('');
            //success, warning, info, warning, error, position: right, left, top, bottom, Top Full Width
            //Bottom Full Width, Top Center, Bottom Center
            alerttoastr('success','Se ha procesado la solicitud sastifactoriamente!', 'Genial!', 'bottom-left');
            }, //5
            error : function(data){ //7
            console.log(data);

              //success, warning, info, warning, error, position: right, left, top, bottom, Top Full Width
              //Bottom Full Width, Top Center, Bottom Center
              alerttoastr('error','Algo ha salido mal!', 'Oops!', 'bottom-left');
            } //7
          }); //4
}

///ejecutamos pedidos
function ejecutarPedido(){
  //i es el id de la tabla detalles
    var factid = $('#Numerofactura').val();
    //comprobar que el valor escrito, son solo números.
          url = "{{ route('realizar.pedido')}}";
          $.ajax({//4
            url : url,
            type : "POST",
            data: {
              '_token': $('input[name=_token]').val(),
              'Numerofactura':factid,
              'tb_id':'actualizar'
            } ,
            success: function(data){ //5
            //console.log(data);
            //window.location.href='{{ route("productos.index")}}';
            detalles_facturacion('');
            //datosRecibo
            $('#detallerecibo').modal('show');
            $('#datosRecibo').html(data).fadeIn('slow');
            //success, warning, info, warning, error, position: right, left, top, bottom, Top Full Width
            //Bottom Full Width, Top Center, Bottom Center
            alerttoastr('success','Se ha procesado la solicitud sastifactoriamente!', 'Genial!', 'top-right');
            }, //5
            error : function(data){ //7
           // console.log(data);

              //success, warning, info, warning, error, position: right, left, top, bottom, Top Full Width
              //Bottom Full Width, Top Center, Bottom Center
              alerttoastr('error','Algo ha salido mal!', 'Oops!', 'bottom-left');
            } //7
          }); //4
}
//iprimir factura
function imprimirFact(){
    var printContents = document.getElementById('printSection').innerHTML;
        w = window.open();
        w.document.write(printContents);
        w.document.close(); // necessary for IE >= 10
        w.focus(); // necessary for IE >= 10
		w.print();
		w.close();
        return true;
        $('#detallerecibo').modal('hide');
}
{{--funcion eliminar registro--}}
function deletedForm(id) {

var csrf_token = $('meta[name="csrf-token"]').attr('content');
$('input[name="_method"]').val('DELETE');

swal({
  title: "Seguro?",
  text: "Si da click 'OK', se eliminara el registro! " ,
  type: "warning",
  showCancelButton: true,
  confirmButtonColor: "#DD6B55",
  confirmButtonText: "Ok, eliminar!",
  cancelButtonText: "No, cancelar!"
}).then(function(result) {
  if (result.value) {
    $.ajax({
url: "{{ url('carro')}}"+"/"+id,
type: "POST",
data: {
  '_method' : 'delete',
  '_token' : csrf_token
},
success : function(data){
    detalles_facturacion('');
  swal({
        title: 'Genail!',
        text: 'Se ha aliminado el registro correctamente!',
        type: 'success',
        timer:5000,
        confirmButtonText: 'OK!'
      });
},
error : function(data){
  swal({
        title: 'Oops...!',
        text: data.message,
        type: 'error',
        confirmButtonText: 'OK!'
      });
}
});

    } else {
      swal({
            title: 'Genial!',
            text: 'No se ha aliminado el registro!',
            type: 'success',
            timer:5000,
            confirmButtonText: 'OK!'
          });

    }

});

}</script>
@endif

@endsection

