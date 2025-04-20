<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BusTimesController extends Controller
{
    public function index()
    {
        return view('bus-times');
    }
}
