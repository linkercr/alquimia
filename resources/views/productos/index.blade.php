@extends('layouts.app')

@section('title', '| Productos registradas')

@section('content')

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                            <div class="panel panel-default">
                                    <div class="panel-heading">
                                         <div class="panel-title pull-left">
                                             <h3 class="m-0 text-primary">Lista de productos</h3>
                                         </div>
                                        <div class="panel-title pull-right">
                                                <a target="_blank" href="{{ URL::to('/crear_reporte_productos/1') }}" class="btn mb-1 btn-flat btn-warning">Export PDF</a>
                                                @if (Auth::user()->hasRole('Admin'))
                                                <a href="{{ route('productos.create')}}" type="button" class="btn mb-1 btn-flat btn-outline-success">
                                                Nuevo Producto</a>
                                                @endif
                                            </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div id="linkCarro" class="alert alert-success alert-dismissible fade show" style="display:none;">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>

                                            </button> <strong>Genial !</strong> se ha agregado el producto al carrito sastifactoriamente.
                                            <a href="{{ route('carro.index')}}" type="button" class="btn mb-1 btn-flat btn-outline-warning">Ir al Carrito <span class="btn-icon-right"><i class="fa fa-shopping-cart"></i></span>
                                            </a>

                                        </div>

                                </div>

                                    <table id="info-table" class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th><div class="pull-center" >IMG</div></th>
                                                <th><div class="pull-center" >Código</div></th>
                                                <th><div class="pull-center" >Nombre</div></th>
                                                <th><div class="pull-center" >Categoria</div></th>
                                                <th><div class="pull-center" >Precio</div></th>
                                                <th><div class="pull-center" >Tipo</div></th>
                                                <th><div class="pull-center" >Descripción</div></th>
                                                <th><div class="pull-center" >Creado</div></th>
                                                <th>Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>

                    </div>
                </div>
            </div>
        </div>
@endsection
@section('js_bajo_body')
<script>

        var table1 = $('#info-table').DataTable({
          processing:true,
          serverSide:true,
          responsive: true,
          ajax: "{{route('all.productos')}}",
          columns:[
            {data:'id'},
            {"render": function (data, type, row) {
             return '<img src="{{ asset("/images/productos")}}/'+row.p_url_img+'" class="rounded-circle" alt="IMG Productos ALQUIMIA" style="width: 50px;">';
           }},
           {data:'p_codigo'},
            {data:'p_nombre'},
            {data:'categoria'},
            {data:'p_precio_venta'},
            {data:'p_tipo'},
            {data:'p_descripcion'},
            {data:'created_at'},
            {"render": function (data, type, row) {
                if(row.permiso_permitido == '1'){ ////1 indica q es admin
             return ' <a href="{{url("productos")}}/'+row.id+'" type="button" id="ButtonVer" class="ver btn btn-info botonEditar btn-md">'+
             '<span class="fa fa-eye"></span><span class="hidden-xs"> Ver</span></a>'+
             '<a type="button"  href="{{url("productos")}}/'+row.id+'/edit" class="editar btn btn-warning botonEditar btn-md">'+
             '<span class="fa fa-edit"></span><span class="hidden-xs"> Editar</span></a>'+
             '<button type="button" id="ButtonDelete" onclick="deletedForm('+row.id+')" class="eliminar btn btn-danger botonEliminar btn-md">'+
             '<span class="fa fa-trash"></span><span class="hidden-xs"> Eliminar</span></button> ';
            }else if(row.permiso_permitido == '2'){
                return '<div class="btn-group"> <a href="{{url("productos")}}/'+row.id+'" type="button" id="ButtonVer" class="ver btn btn-info botonEditar btn-md">'+
             '<span class="fa fa-eye"></span><span class="hidden-xs"></span></a>'+
             '<a type="button"  href="#" onclick="addProducto(1,'+row.id+');"  class="editar btn btn-success botonEditar btn-md">'+
             '<span class="fa fa-plus"></span><span class="hidden-xs"></span></a></div>';
            }
           }},

          ]

        });$('select, input[type="search"]').css({
                    "background-color": "#f3f3f3",
                    "font-weight": "bold"
                });


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
url: "{{ url('productos')}}"+"/"+id,
type: "POST",
data: {
  '_method' : 'delete',
  '_token' : csrf_token
},
success : function(data){
  table1.ajax.reload();
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

}
////funcion selccionar grupo de facturacion
function addProducto(i, b) {
//console.log($('input[name=_token]').val());

$.LoadingOverlay("show");
// correcto insertamos en la bd
url = "{{ url('carro')}}";
$.ajax({//4
  url : url,
  type : "POST",
  data: {
    '_token': $('input[name=_token]').val(),
    'tipoVenta':2,
    'producto_id': b

  },
  processData: true,
//id_factura_perfil
  success: function(data){ //5
  //console.log(data);
  $.LoadingOverlay("hide");
  $('#linkCarro').show();
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
@endsection
