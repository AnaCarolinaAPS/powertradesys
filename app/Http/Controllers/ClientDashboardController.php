<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientDashboardController extends Controller
{
    public function index()
    {
        return view('client.index'); // Isso assume que você possui uma vista chamada 'admin.dashboard.index'
    }
}
