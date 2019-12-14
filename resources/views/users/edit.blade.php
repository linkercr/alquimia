{{-- \resources\views\users\edit.blade.php --}}

@extends('layouts.app')

@section('title', '| Edit User')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <h1><i class='fa fa-user-plus'></i> Edit {{$user->name}}</h1>
    <hr>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
{{--el objeto con las columna de la tabla d ela bd lo manda el controller--}}
    {{ Form::model($user, array('route' => array('users.update', $user->id), 'method' => 'PUT')) }}{{-- Form model binding to automatically populate our fields with user data --}}
        <div class="form-row">
                <div class="form-group col-md-6">
                        {{ Form::label('name', 'Name') }}
                        {{ Form::text('name', null, array('class' => 'form-control')) }}
                </div>
                <div class="form-group col-md-6">
                        <label>Tipo de Identificacion</label>
                        <select class="form-control form-control-sm"  name="tipo_identificacion">
                                @php
                                if($user->tipo_identificacion == 1){
                                   echo '<option value="1" selected>Cédula</option>
                                   <option value="2" >DIMEX</option>
                                   <option value="3" >Pasaporte</option>';
                                }else if($user->tipo_identificacion == 2){
                                   echo '<option value="1" >Cédula</option>
                                   <option value="2" selected>DIMEX</option>
                                   <option value="3" >Pasaporte</option>';
                                }else{
                                    echo '<option value="1" >Cédula</option>
                                   <option value="2" >DIMEX</option>
                                   <option value="3" selected>Pasaporte</option>';
                                }
                                @endphp
                            </select>
                </div>
            </div>
            <div class="form-row">
                    <div class="form-group col-md-6">
                            {{ Form::label('name', 'Número de Indentificación') }}
                            {{ Form::text('identificacion', null, array('class' => 'form-control')) }}
                    </div>
                    <div class="form-group col-md-6">
                            {{ Form::label('email', 'Email') }}
                            {{ Form::email('email', null, array('class' => 'form-control')) }}
                    </div>
                </div>
                <div class="form-row">
                        <div class="form-group col-md-6">
                                <label>Tipo de Telefono</label>
                                <select class="form-control form-control-sm"  name="tipo_telefono">
                                        @php
                                        if($user->tipo_telefono == 1){
                                           echo '<option value="1" selected>Celular</option>
                                           <option value="2" >Fijo Oficina</option>
                                           <option value="3" >Fijo Casa</option>';
                                        }else if($user->tipo_telefono == 2){
                                           echo '<option value="1" >Celular</option>
                                           <option value="2" selected>Fijo Oficina</option>
                                           <option value="3" >Fijo Casa</option>';
                                        }else{
                                            echo '<option value="1" >Celular</option>
                                           <option value="2" >Fijo Oficina</option>
                                           <option value="3" selected>Fijo Casa</option>';
                                        }
                                        @endphp
                                    </select>
                        </div>
                        <div class="form-group col-md-6">
                                {{ Form::label('name', 'Número de Telefono') }}
                                {{ Form::number('telefono', null, array('class' => 'form-control')) }}
                        </div>
                    </div>

    <h5><b>Asigne Role</b></h5>

    <div class='form-group'>
        @foreach ($roles as $role)
            {{ Form::checkbox('roles[]',  $role->id, $user->roles ) }}
            {{ Form::label($role->name, ucfirst($role->name)) }}<br>

        @endforeach
    </div>

    <div class="form-row">
            <div class="form-group col-md-6">
                    {{ Form::label('password', 'Password') }}<br>
                    {{ Form::password('password', array('class' => 'form-control')) }}
            </div>
            <div class="form-group col-md-6">
                    {{ Form::label('password', 'Confirm Password') }}<br>
                    {{ Form::password('password_confirmation', array('class' => 'form-control')) }}
            </div>
        </div>

    {{ Form::submit('Actualizar', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>

@endsection
