<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegistrasiController extends Controller
{
    public function index($branch)
    {
        return view('registrasi');
    }
}
