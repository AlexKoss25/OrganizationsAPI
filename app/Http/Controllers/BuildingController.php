<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;

class BuildingController extends Controller
{
    /**
     * Список всех зданий с пагинацией
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $buildings = Building::paginate($perPage);

        return response()->json($buildings);
    }

    /**
     * Показать здание по ID
     */
    public function show($id)
    {
        $building = Building::findOrFail($id);
        return response()->json($building);
    }

    /**
     * Создать новое здание
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'address'   => 'required|string|max:255',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $building = Building::create($validated);

        return response()->json($building, 201);
    }

    /**
     * Обновить здание
     */
    public function update(Request $request, $id)
    {
        $building = Building::findOrFail($id);

        $validated = $request->validate([
            'address'   => 'string|max:255',
            'latitude'  => 'numeric',
            'longitude' => 'numeric',
        ]);

        $building->update($validated);

        return response()->json($building);
    }

    /**
     * Удалить здание
     */
    public function destroy($id)
    {
        $building = Building::findOrFail($id);
        $building->delete();

        return response()->json(['message' => 'Building deleted successfully']);
    }
}
