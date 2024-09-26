<?php

namespace App\Http\Controllers;

use App\Models\Properties;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    } 
}
