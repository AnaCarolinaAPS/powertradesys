<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.index'); // Isso assume que você possui uma vista chamada 'admin.dashboard.index'
    }
}
