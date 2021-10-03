<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        return inertia('Dashboard');
    }

    public function applications()
    {
        return inertia('Applications');
    }
}
