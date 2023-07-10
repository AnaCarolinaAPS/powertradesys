<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // $isAdmin = "";

        return view('dashboard.index');
        // return view('admin.index', compact('isAdmin'));
    }
}
