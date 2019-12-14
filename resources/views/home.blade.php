@extends('layouts.app')
@section('title')
  Dash --Sistema ALQUIMIA
@endsection
@section('css')
  <link href="{{ asset('plugins/dropzone/css/dropzone.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('plugins/bootstrap-fileinput/css/fileinput.min.css')}}" rel="stylesheet" />
  <!-- Custom Stylesheet -->
  <link href="{{ asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}" rel="stylesheet">
  <!-- Page plugins css -->
    <link href="{{ asset('plugins/clockpicker/dist/jquery-clockpicker.min.css')}}" rel="stylesheet">
    <!-- Color picker plugins css -->
    <link href="{{ asset('plugins/jquery-asColorPicker-master/css/asColorPicker.css')}}" rel="stylesheet">
    <!-- Date picker plugins css -->
    <link href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <!-- Daterange picker plugins css public\plugins\bootstrap\4.0.0_alpha.6_bootstrap.min.css -->
    <link href="{{ asset('plugins/timepicker/bootstrap-timepicker.min.css')}}" rel="stylesheet">
    <link href="{{ asset('plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
@endsection
@section('content')
<div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">

                        @if (session('status'))
                        <div class="alert alert-primary alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                                </button> <strong>{{ session('status') }}!</strong> You are logged in!.</div>
                    @endif
                    <div class="alert alert-success alert-dismissible fade show">

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                            </button> <strong>Bienvenid@!</strong> has ingresado sastifactoriamente!</div>


                            @if (Auth::user()->hasRole('Admin'))
                            <div class="example">
                                    <h5 class="box-title m-t-30">Rango de Fecha:</h5>
                                    <p class="text-muted m-b-20">Seleccione el rango de <code>fecha</code> para generrar el reporte!</p>
                                            <input class="form-control" type="text" data-toggle="daterangepicker" id="daterangeCategorias" maxlength="23" name="timestamp" data-filter-type="date-range">

                                </div>


                                <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                    <div class="text-center">
                                                     <span class="display-5"><i class="icon-user gradient-3-text"></i></span>
                                                     <h2 class="mt-2">Usuarios</h2>
                                                    <select class="form-control form-control-sm" id="user_id" name="user_id">
                                                            @foreach ($query as $rw)
                                                            @php
                                                                echo '<option value="'.$rw->id.'">'.$rw->name.'</option>';
                                                            @endphp
                                                            @endforeach
                                                              </select>
                                                              <input type="hidden" id="user" value="1">
                                                     <p class="m-0">Reporte de Ventas</p>
                                                     <br>
                                                     <button type="button" class="btn btn-primary" data-toggle="modal" onclick="generarReporteUsuario()" data-target="#modalReportesCategoria">Generar</button>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                            @endif


                            <table id="info-table" class="table table-responsive table-striped table-bordered zero-configuration">
                                    <thead>
                                        <tr>
                                            <th><div class="pull-center" >Entrenador</div></th>
                                            <th><div class="pull-center" >Cliente</div></th>
                                            <th><div class="pull-center" >Altura</div></th>
                                            <th><div class="pull-center" >Peso</div></th>
                                            <th><div class="pull-center" >Porc.Grasa</div></th>
                                            <th><div class="pull-center" >Grasa Viceral</div></th>
                                            <th><div class="pull-center" >Cintura</div></th>
                                            <th><div class="pull-center" >Pecho</div></th>
                                            <th><div class="pull-center" >cadera</div></th>
                                            <th><div class="pull-center" >Brazo</div></th>
                                            <th><div class="pull-center" >IMC</div></th>
                                            <th><div class="pull-center" >Tipo Regsitro</div></th>
                                            <th><div class="pull-center" >Nota</div></th>
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
  "order": [ [0, 'desc'],
                     ],
          ajax: "{{route('control.historico')}}",
          columns:[
            {data:'usario_id'},
            {data:'persona_id'},
            {data:'c_altura'},
            {data:'c_peso'},
            {data:'c_procentaje_grasa'},
            {data:'c_grasa_viceral'},
            {data:'c_cintura'},
            {data:'c_pecho'},
            {data:'c_cadera'},
            {data:'c_brazo'},
            {data:'c_imc'},
            {data:'c_tipo'},
            {data:'c_nota'},
            {data:'created_at'},
            {"render": function (data, type, row) {
             return ' <a href="{{url("personas")}}/'+row.id+'" type="button" id="ButtonVer" class="ver btn btn-info botonEditar btn-md">'+
             '<span class="fa fa-eye"></span><span class="hidden-xs"> Ver</span></a>'+
             '<a type="button"  href="{{url("personas")}}/'+row.id+'/edit" class="editar btn btn-warning botonEditar btn-md">'+
             '<span class="fa fa-edit"></span><span class="hidden-xs"> Editar</span></a>'+
             '<button type="button" id="ButtonDelete" onclick="deletedForm('+row.id+')" class="eliminar btn btn-danger botonEliminar btn-md">'+
             '<span class="fa fa-trash"></span><span class="hidden-xs"> Eliminar</span></button> ';
           }},

          ]

        });$('select, input[type="search"]').css({
                    "background-color": "#f3f3f3",
                    "font-weight": "bold"
                });


            </script>
@endsection
@section('js')
<script src="{{ asset('plugins/dropzone/js/dropzone.min.js')}}"></script>
<script src="{{ asset('plugins/bootstrap-fileinput/js/fileinput.min.js')}}"></script>

<script src="{{ asset('plugins/moment/moment.js')}}"></script>
<script src="{{ asset('plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>
<!-- Clock Plugin JavaScript -->
<script src="{{ asset('plugins/clockpicker/dist/jquery-clockpicker.min.js')}}"></script>
<!-- Color Picker Plugin JavaScript -->
<script src="{{ asset('plugins/jquery-asColorPicker-master/libs/jquery-asColor.js')}}"></script>
<script src="{{ asset('plugins/jquery-asColorPicker-master/libs/jquery-asGradient.js')}}"></script>
<script src="{{ asset('plugins/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js')}}"></script>
<!-- Date Picker Plugin JavaScript -->
<script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<!-- Date range Plugin JavaScript -->
<script src="{{ asset('plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script src="{{ asset('plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script src="{{ asset('js/plugins-init/form-pickers-init.js')}}"></script>
<script>
  //datepicker
  $('#daterangeCategorias').daterangepicker({
        showDropdowns: true,
        separator: 'to',
        ranges: {
            'Hoy': [moment(), moment()],
            'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Esta semana': [moment().startOf('week'), moment().endOf('week')],
            'Última semana': [moment().subtract(6, 'days'), moment()],
            'Últimas 2 semanas': [moment().subtract(13, 'days'), moment()],
            'Este mes': [moment().startOf('month'), moment().endOf('month')],
            'Mes anterior': [moment().subtract(1, 'month').startOf('month'),
                moment().subtract(1, 'month').endOf('month')]
        },
        autoUpdateInput: true,
        applyClass: 'btn-sm btn-primary',
        cancelClass: 'btn-sm btn-default',
        locale: {
            format: 'YYYY/MM/DD',
            applyLabel: 'Aplicar',
            cancelLabel: 'Cancelar',
            fromLabel: 'Desde',
            toLabel: 'Hasta',
            customRangeLabel: 'Seleccionar rango',
            daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre',
                'Diciembre'],
            firstDay: 1
        }
    });

    //crear reporte
    function generarReporteUsuario()
{
   var  fecha = $('#daterangeCategorias').val();
   var id = $('#user').val();
   // console.log($fecha);

   $('#detallerecibo').modal('show');

    // procedemos ya que el cliente esta seguro de la seleccion
      $.LoadingOverlay("show");
        url = "{{ route('resumen.proceder')}}";
        $.ajax({//4
          url : url,
          type : "POST",
          data: {
            '_token': $('input[name=_token]').val(),
            'fecha': fecha,
            'userid':id,
          } ,
          success: function(data){ //5
            $.LoadingOverlay("hide");
            $('#detallerecibo').modal('show');
            $('#datosRecibo').html(data).fadeIn('slow');
          }, //5
          error : function(data){ //7
            console.log(data);

          } //7
        }); //4







}
$(document).on('change', '#user_id', function(){
 $('#user').val($(this).val());


});
</script>


@endsection
