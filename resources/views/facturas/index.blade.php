@extends('layouts.app')

@section('title', '| Facturado')
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
                            <div class="panel panel-default">
                                    <div class="panel-heading">
                                         <div class="panel-title pull-left">
                                             <h3 class="m-0 text-primary">Facturado</h3>
                                         </div>
                                        <div class="panel-title pull-right">
                                            <a target="_blank" href="{{ URL::to('/crear_reporte_facturado/1') }}" class="btn mb-1 btn-flat btn-warning">Export PDF</a>
                                            </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                    <table id="info-table" class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th><div class="pull-center" >Factura</div></th>
                                                <th><div class="pull-center" >Usuario</div></th>
                                                <th><div class="pull-center" >Total</div></th>
                                                <th><div class="pull-center" >Fecha de Entrega</div></th>
                                                <th>Acciones</th>
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
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" onclick="imprimirFact();">Imprimir</button>
                  <button type="button" class="btn btn-secondary" onclick="mostrarModalGrupoFact();" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

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
@endsection
@section('js_bajo_body')
<script>

        var table1 = $('#info-table').DataTable({
          processing:true,
          serverSide:true,
          responsive: true,
          "order": [ [0, 'desc'],
                     ],
          ajax: "{{route('all.facturado')}}",
          columns:[
            {data:'id'},
            {data:'factura_id'},
            {data:'usuario'},
            {data:'total'},
            {data:'fecha'},
                {"render": function (data, type, row) {
                    return '<button type="button" id="ButtonDelete" onclick="verDetalles('+row.factura_id+')" data-toggle="modal" data-target="#basicModal" class="btn btn-info  btn-md">'+
             '<span class="fa fa-check-circle-o"></span><span class="hidden-xs"> Ver</span></button> ';

            }},

          ]

        });$('select, input[type="search"]').css({
                    "background-color": "#f3f3f3",
                    "font-weight": "bold"
                });

//ver detalles de facturas
function verDetalles(i) {
$.LoadingOverlay("show");
        url = "{{ route('detalle.factura')}}";
        $.ajax({//4
          url : url,
          type : "POST",
          data: {
            '_token': $('input[name=_token]').val(),
            'numeroFactura': i
          } ,
          success: function(data){ //5
            //console.log(data);
            $.LoadingOverlay("hide");
            $('#preloaderf').css("display", "none");
            //datosRecibo
            $('#detallerecibo').modal('show');
            $('#datosRecibo').html(data).fadeIn('slow');
          }, //5
          error : function(data){ //7
          //  console.log(data);
          $.LoadingOverlay("hide");
          $('#preloaderf').css("display", "none");

          } //7
        }); //4


}

function imprimirFact(){
    var win = window.open();

    var content = "<html>";
    content += "<body onload=\"window.print(); window.close();\">";
    content += document.getElementById("printSection").innerHTML ;
    content += "</body>";
    content += "</html>";
    win.document.write(content);
        win.document.close(); // necessary for IE >= 10
        win.focus(); // necessary for IE >= 10
		win.print();
		win.close();
        return true;
}
            </script>
@endsection
