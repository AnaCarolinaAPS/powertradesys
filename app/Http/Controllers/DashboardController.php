<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $id = Auth::user()->id;
        $user = User::find($id);

        $isAdmin = $user->isAdmin();
        $isClient = $user->isClient();
        $isLogistics = $user->isLogistics();
        $isFinancial = $user->isFinancial();

        // return view('dashboard.index');
        return view('admin.index', compact('isAdmin', 'isClient', 'isLogistics', 'isFinancial'));
    }
}
