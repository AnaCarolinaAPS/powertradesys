<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FaturaCarga;

class RelatorioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexCarga()
    {
        $all_items = FaturaCarga::all();
        return view('admin.relatoriocarga.index', compact('all_items'));
    }

}
