<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class NetworkController extends Controller
{
    public function client_network(Request $request)
    {
        return Inertia::render('Network/ClientNetwork');
    }
}
