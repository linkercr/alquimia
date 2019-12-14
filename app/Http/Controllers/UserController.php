<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;//modelo columnas de la tabla
use Auth; //verifica si el usaurio esta logeado
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles; //para verifica roles y permisos
//Importing laravel-permission models
use Spatie\Permission\Models\Role; //para verifica roles y permisos
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables; //un plugins para mostrar registros en una tabla dinamica
//Enables us to output flash messaging
use Session;

class UserController extends Controller {
    public function __construct() {
        $this->middleware(['auth', 'isAdmin']);//parte de un plugin //isAdmin middleware lets only users with a //specific permission permission to access these resources
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function index() {
        //Get all users and pass it to the view
           // $users = User::orderby('id', 'ASC')->paginate(10);
            return view('users.index');//->with('users', $users);
        }
    //obtener regsitro total de la bd
    public function Registro_Total_Usurios()
    {
        # code...
        $query = User::all();
        $obj = array();
      foreach ($query as $res =>$row) {
          $tipoIdentificaicon = $row['tipo_identificacion'];
          $tipoTelefono = $row['tipo_telefono'];
          $textoTipoIdent = '';
          $textoTipoTele = '';
          if( $tipoIdentificaicon == 1){
            $textoTipoIdent = 'Cédula';
         }else if( $tipoIdentificaicon == 2){
            $textoTipoIdent = 'DIMEX';
         }else{
            $textoTipoIdent = 'Pasaporte';
         }
         if($tipoTelefono == 1){
            $textoTipoTele = 'Celular';
         }else if($tipoTelefono == 2){
            $textoTipoTele = 'Fijo Oficina';
         }else{
            $textoTipoTele = 'Fijo Casa';
         }
        $obj[] = [
          'id'=>$row['id'],
          'name'=>$row['name'],
          'tipo_identificacion'=> $textoTipoIdent,
          'identificacion'=>$row['identificacion'],
          'email' =>$row['email'],
          'tipo_telefono' => $textoTipoTele,
          'telefono' =>$row['telefono'],
          'created_at'=>date("d/m/Y", strtotime($row['created_at']))
        ];
      }
      return Datatables::of($obj)->make(true);//esto es lo que le pasa el objecto a la tabla dimanamica

    }
        /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create() {
        //Get all roles and pass it to the view
            $roles = Role::get();
            return view('users.create', ['roles'=>$roles]);
        }

        /**
        * Store a newly created resource in storage.
        *
        * @param  \Illuminate\Http\Request  $request
        * @return \Illuminate\Http\Response
        */
        public function store(Request $request) {
        //Validate name, email and password fields
        /*
name
tipo_identificacion
identificacion
email
tipo_telefono
telefono
password
        */
            $this->validate($request, [
                'name'=>'required|min:3|max:120',
                'tipo_identificacion' => 'required',
                'identificacion' => 'required|min:7|max:20',
                'email'=>'required|email|unique:users',
                'tipo_telefono' => 'required',
                'telefono' => 'required|numeric|min:8',
                'password'=>'required|min:6|confirmed'
            ],
            ['name.required' => 'Nombre es requerido',
            'name.max' => 'Nombre solo se permiten 120 caracteres',
            'name.min' => 'Nombre minimo 3 caracteres',
            'tipo_identificacion.required' => 'Tipo de Cedula es requerido',
            'identificacion.required' => 'Identificacion es requerido',
            'identificacion.max' => 'Identificacion maximo 20 caracteres',
            'identificacion.min' => 'Identificacion minimo 7 caracteres',
            'email.required' => 'Email es requerido',
            'email.email' => 'Email no corresponde a un formato de correo valido',
            'email.unique' => 'Email ya existe en la bd',
            'tipo_telefono.required' => 'Tipo de Telefono es requerido',
            'telefono.required' => 'Telefono es requerido',
            'telefono.min' => 'Telefono minimo 8 caracteres',
            'password.confirmed' => ' Las claves no coinciden']);

            $user = User::create($request->only(
                'name', 'tipo_identificacion',
                'identificacion', 'email', 'tipo_telefono',
                'telefono', 'password')); //Retrieving only the email and password data

            $roles = $request['roles']; //Retrieving the roles field
        //Checking if a role was selected
            if (isset($roles)) {

                foreach ($roles as $role) {
                $role_r = Role::where('id', '=', $role)->firstOrFail();
                $user->assignRole($role_r); //Assigning role to user
                }
            }
        //Redirect to the users.index view and display message
            return redirect()->route('users.index')
                ->with('flash_message',
                 'User successfully added.');
        }

        /**
        * Display the specified resource.
        *
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function show($id) {
            return redirect('users');
        }

        /**
        * Show the form for editing the specified resource.
        *
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function edit($id) {
            $user = User::findOrFail($id); //Get user with specified id
            $roles = Role::get(); //Get all roles

            return view('users.edit', compact('user', 'roles')); //pass user and roles data to view

        }

        /**
        * Update the specified resource in storage.
        *
        * @param  \Illuminate\Http\Request  $request
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function update(Request $request, $id) {
            $user = User::findOrFail($id); //Get role specified by id

        //Validate name, email and password fields
        $this->validate($request, [
            'name'=>'required|min:3|max:120',
            'tipo_identificacion' => 'required',
            'identificacion' => 'required|min:7|max:20',
            'email'=>'required|email',
            'tipo_telefono' => 'required',
            'telefono' => 'required|numeric|min:8'
        ],
        ['p_codigo.name' => 'Nombre es requerido',
        'name.max' => 'Nombre solo se permiten 120 caracteres',
        'name.min' => 'Nombre minimo 3 caracteres',
        'tipo_identificacion.required' => 'Tipo de Cedula es requerido',
        'identificacion.required' => 'Identificacion es requerido',
        'identificacion.max' => 'Identificacion maximo 20 caracteres',
        'identificacion.min' => 'Identificacion minimo 7 caracteres',
        'email.required' => 'Email es requerido',
        'email.email' => 'Email no corresponde a un formato de correo valido',
        'tipo_telefono.required' => 'Tipo de Telefono es requerido',
        'telefono.required' => 'Telefono es requerido',
        'telefono.min' => 'Telefono minimo 8 caracteres']);
            $input = $request->only(['name', 'tipo_identificacion',
            'identificacion', 'email', 'tipo_telefono',
            'telefono', 'password']); //Retreive the name, email and password fields
            $roles = $request['roles']; //Retreive all roles
            $user->fill($input)->save();

            if (isset($roles)) {
                $user->roles()->sync($roles);  //If one or more role is selected associate user to roles
            }
            else {
                $user->roles()->detach(); //If no role is selected remove exisiting role associated to a user
            }
            return redirect()->route('users.index')
                ->with('flash_message',
                 'User successfully edited.');
        }

        /**
        * Remove the specified resource from storage.
        *
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function destroy($id) {
        //Find a user with a given id and delete
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('users.index')
                ->with('flash_message',
                 'User successfully deleted.');
        }


}
