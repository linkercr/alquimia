<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Alert;
use App\User;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $query = User::all();
        //Alert::success('Success Title', 'Success Message');
        return view('home', compact('query'));
    }
}
