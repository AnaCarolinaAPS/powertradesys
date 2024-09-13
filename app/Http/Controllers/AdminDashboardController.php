<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PacotesPendentes;

class AdminDashboardController extends Controller
{
    public function index()
    {        
        return view('admin.index'); // Isso assume que você possui uma vista chamada 'admin.dashboard.index'
    }
}
