<?php

namespace App\Http\Controllers;

use App\Models\Shipper;
use Illuminate\Http\Request;

class ShipperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_shippers = Shipper::all();
        return view('admin.shipper.index', compact('all_shippers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validação dos dados do formulário
        $request->validate([
            'name' => 'required|string|max:255',
            // Adicione outras regras de validação conforme necessário
        ]);

        // Criação de um novo Shipper no banco de dados
        Shipper::create([
            'name' => $request->input('name'),
            // Adicione outros campos conforme necessário
        ]);

        // Redirecionamento após a criação bem-sucedida
        return redirect()->route('shippers.index')->with('success', 'Shipper criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shipper $shipper)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shipper $shipper)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipper $shipper)
    {
        //
    }
}
