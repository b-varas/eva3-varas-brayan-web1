<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $proyectos = Project::all();

        return response()->json($proyectos, 200);
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

    public function show(string $id)
    {
        $proyecto = Project::find($id);

        if (!$proyecto) {
            return response()->json(['message' => 'Proyecto no encontrado'], 404);
        }

        return response()->json($proyecto, 200);
    }

    
    public function update(Request $request, string $id)
    {
        $proyecto = Project::find($id);

        if (!$proyecto) {
            return response()->json(['message' => 'Proyecto no encontrado'], 404);
        }

        $validado = $request->validate([
            'nombre' => 'required|string|max:150',
            'fecha_inicio' => 'required|date',
            'estado' => 'required|string|max:50',
            'responsable' => 'required|string|max:150',
            'monto' => 'required|numeric|min:0',
        ]);

        $proyecto->update($validado);

        return response()->json($proyecto, 200);
    }

    public function destroy(string $id)
    {
        $proyecto = Project::find($id);

        if (!$proyecto) {
            return response()->json(['message' => 'Proyecto no encontrado'], 404);
        }

        $proyecto->delete();

        return response()->json(null, 204);
    }
}
