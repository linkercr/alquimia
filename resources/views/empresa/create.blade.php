@extends('layouts.app')

@section('title', '| Crear Empresa')
@section('menu_navegacion')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('empresa.index')}}">Empresa</a></li>
        <li class="breadcrumb-item active"><a href="">Crear</a></li>
    </ol>
@endsection
@section('css')
  <link href="{{ asset('plugins/dropzone/css/dropzone.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('plugins/bootstrap-fileinput/css/fileinput.min.css')}}" rel="stylesheet" />
@endsection
@section('content')

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">

                            <div class="col-md-8 col-md-offset-2">
                                    <h1>Registro Empresa</h1>
                                    <hr>

                                {{-- Using the Laravel HTML Form Collective to create our form --}}
                                    {{ Form::open(array('route' => 'empresa.store', 'enctype="multipart/form-data"')) }}

                                    <div class="form-group">
                                        {{ Form::label('title', 'Nombre Empresa') }}
                                        {{ Form::text('nombre_empresa', null, array('class' => 'form-control', 'required')) }}
                                        <br>
                                        @foreach ($query as $rw)
                                        <div class="form-group">
                                        <label>{{ $rw->tdc_texto}}</label>
                                        @php
                                        $rq = '';
                                        if ($rw->tdc_requerido == 1){
                                            $rq = 'required';
                                        }else{
                                            $rq = '';
                                        }
                                        @endphp
                                                <input type="text" name="dato_contacto[]" class="form-control" placeholder="{{ $rw->tdc_descripcion}}" {{$rq}}>
                                                <input type="hidden" name="id_dato_contacto[]" class="form-control" value="{{ $rw->id}}">
                                        </div>
                                        @endforeach

                                        <div class="form-group">
                                                <label>Dirección:</label>
                                                <textarea class="form-control h-150px" rows="3" name="direccion"></textarea>
                                        </div>
                                        <div class="form-group">
                                                <label>Imagen:</label>
                                                <input id="input-b1" name="imagen" type="file" class="file" data-browse-on-zone-click="true">

                                        </div>

                                        {{ Form::submit('Crear', array('class' => 'btn btn-success btn-lg btn-block')) }}
                                        {{ Form::close() }}
                                    </div>
                                    </div>

                    </div>
                </div>
            </div>
        </div>
@endsection
@section('js')
<script src="{{ asset('plugins/dropzone/js/dropzone.min.js')}}"></script>
<script src="{{ asset('plugins/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
@endsection


