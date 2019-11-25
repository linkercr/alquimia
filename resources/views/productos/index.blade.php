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
                                            <a href="{{ route('productos.create')}}" type="button" class="btn mb-1 btn-flat btn-outline-success">
                                                Nuevo Producto</a></div>
                                        <div class="clearfix"></div>
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
                                                <th>Accion</th>
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
             return ' <a href="{{url("productos")}}/'+row.id+'" type="button" id="ButtonVer" class="ver btn btn-info botonEditar btn-md">'+
             '<span class="fa fa-eye"></span><span class="hidden-xs"> Ver</span></a>'+
             '<a type="button"  href="{{url("productos")}}/'+row.id+'/edit" class="editar btn btn-warning botonEditar btn-md">'+
             '<span class="fa fa-edit"></span><span class="hidden-xs"> Editar</span></a>'+
             '<button type="button" id="ButtonDelete" onclick="deletedForm('+row.id+')" class="eliminar btn btn-danger botonEliminar btn-md">'+
             '<span class="fa fa-trash"></span><span class="hidden-xs"> Eliminar</span></button> ';
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

            </script>
@endsection