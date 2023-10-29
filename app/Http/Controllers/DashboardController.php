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

        // return view('admin.index');
        // return view('dashboard.index');
        // return view('admin.index', compact('isAdmin'));

        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->hasRole('client')) {
            return redirect()->route('client.dashboard');
        } else {
            return view('dashboard');
        }
    }
}
