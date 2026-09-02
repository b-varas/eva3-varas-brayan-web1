<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    
    public function store(Request $request)
    {
        $validado = $request->validate([
            'nombre' => 'required|string|max:150',
            'fecha_inicio' => 'required|date',
            'estado' => 'required|string|max:50',
            'responsable' => 'required|string|max:150',
            'monto' => 'required|numeric|min:0',
            // En un sistema con autenticación por token (ej: Sanctum), este valor
            // se obtendría automáticamente con auth()->id(). Como esta API no
            // implementa autenticación, el cliente lo envía directamente en el body.
            'created_by' => 'required|integer|exists:users,id',
        ]);

        $proyecto = Project::create($validado);

        return response()->json($proyecto, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
