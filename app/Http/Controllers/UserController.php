<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tbl_user;
use DB;

class UserController extends Controller {

    public function index() {
        

        return view('index');
    }

     public function home() {
        

        return view('home');
    }

    

}
