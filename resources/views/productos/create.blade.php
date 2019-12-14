@extends('layouts.app')

@section('title', '| Crear Producto')
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
@section('menu_navegacion')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('productos.index')}}">Productos</a></li>
        <li class="breadcrumb-item active"><a href="">Crear</a></li>
    </ol>
@endsection
@section('content')

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                            <div class="col-md-8 col-md-offset-2">
                                    <h1>Registro Producto</h1>
                                    <hr>
                                    @if (Auth::user()->hasRole('Admin'))
                                    @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                {{-- Using the Laravel HTML Form Collective to create our form --}}
                                    {{ Form::open(array('route' => 'productos.store', 'enctype="multipart/form-data"')) }}

                                    <div class="form-group">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Codigo</label>
                                                <input type="text" id="p_codigo" name="p_codigo" class="form-control" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Nombre</label>
                                                <input type="text" name="p_nombre" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label>Categoria</label>
                                                    <select class="form-control form-control-sm" name="categoria_id">
                                                            @foreach ($query as $rw)
                                                            @php
                                                                echo '<option value="'.$rw->id.'">'.$rw->c_texto.'</option>';
                                                            @endphp
                                                            @endforeach
                                                              </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Tipo de Producto</label>
                                                    <select class="form-control form-control-sm" id="tipoProducto"  name="tipoProducto">
                                                            <option value="1">Servico</option>
                                                            <option value="2">Mercaderia</option>
                                                        </select>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                    <div class="form-group col-md-4" id="div1">
                                                        <label>Precio Costo</label>
                                                        <input type="text" id="p_precio_costo"  name="p_precio_costo" class="form-control" >
                                                    </div>
                                                    <div class="form-group col-md-4" id="div2">
                                                        <label>Precio Venta</label>
                                                        <input type="text"   name="p_precio_venta" class="form-control" required>
                                                    </div>
                                                    <div class="form-group col-md-4" id="div3">
                                                            <label>Cantidad</label>
                                                            <input type="text" id="p_catidad" name="p_catidad" class="form-control" >
                                                        </div>
                                                </div>
                                            <div class="form-group">
                                                    <label>Descripción:</label>
                                                    <textarea class="form-control h-150px" rows="2" name="p_descripcion"></textarea>
                                            </div>
                                            <div class="form-group">
                                                    <label>Imagen:</label>
                                                    <input id="input-b1" name="p_url_img" type="file" class="file" data-browse-on-zone-click="true">

                                            </div>

                                        {{ Form::submit('Crear', array('class' => 'btn btn-success btn-lg btn-block')) }}
                                        {{ Form::close() }}
                                        @endif
                                    </div>
                                    </div>



                    </div>
                </div>
            </div>
        </div>
@endsection
@section('js_bajo_body')
<script>
    //inicio script
        //div 2
        $('#div2').removeClass("form-group col-md-4");
        $('#div2').addClass("form-group col-md-12");
        $('#p_catidad').val('');
        $('#div1').hide();
        $('#div3').hide();
$(document).on('change', '#tipoProducto', function(){
    var valor = $(this).val();
    if (valor == 1) {
        $('#div1').removeClass("form-group col-md-4");
        $('#div1').addClass("form-group col-md-6");
        //div 2
        $('#div2').removeClass("form-group col-md-4");
        $('#div2').addClass("form-group col-md-12");
        $('#p_catidad').val('');
        $('#div3').hide();
        $('#div1').hide();
        $('#p_catidad').prop('required', false);
        $('#p_precio_costo').prop('required', false);
      }else {
       $('#div1').removeClass("form-group col-md-6");
        $('#div1').addClass("form-group col-md-4");
        //div 2
        $('#div2').removeClass("form-group col-md-12");
        $('#div2').addClass("form-group col-md-4");
        $('#div1').show();
        $('#div3').show();
        $('#p_catidad').prop('required', true);
      }

   });
//validar si exite codigo del producto
$(document).on('change', '#p_codigo', function(){
 var query = $(this).val();
 $.ajax({
  url:"{{ route('validarcodigoproducto.ejecutar') }}",
  method:'GET',
  data:{query:query},
  dataType:'json',
  success:function(data)
  {
    if (data.error) {
      swal({
            title: 'Oops!',
            text: data.error,
            type: 'error',
            confirmButtonText: 'OK!'
          });

     $('#labcodigo').addClass( "text-danger" );
     $('#p_codigo').removeClass( "form-control" );
     $('#p_codigo').addClass( "form-control is-invalid" );
     $('#p_codigo').val('');
   }else {
     $('#p_codigo').removeClass( "form-control is-invalid" );
     $('#labcodigo').removeClass( "text-danger" );
     $('#labcodigo').addClass( "text-success" );
     $('#p_codigo').addClass( "form-control is-valid" );
   }

  }
 })

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



@endsection


