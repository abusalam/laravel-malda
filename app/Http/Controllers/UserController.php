<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function home()
    {
        return view('login_home');
    }

    // public function login_home() {

    //     return view('login_home');
    // }
}
