<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;

class ActivityController extends Controller
{
    /**
     * Список видов деятельности с пагинацией
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $activities = Activity::with('children')->paginate($perPage);

        return response()->json($activities);
    }

    /**
     * Показать вид деятельности по ID
     */
    public function show($id)
    {
        $activity = Activity::with(['children', 'organizations'])->findOrFail($id);
        return response()->json($activity);
    }

    /**
     * Создать новый вид деятельности
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'parent_id' => 'nullable|exists:activities,id',
        ]);

        $activity = Activity::create($validated);

        return response()->json($activity, 201);
    }

    /**
     * Обновить вид деятельности
     */
    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);

        $validated = $request->validate([
            'name'      => 'string|max:255',
            'parent_id' => 'nullable|exists:activities,id',
        ]);

        $activity->update($validated);

        return response()->json($activity);
    }

    /**
     * Удалить вид деятельности
     */
    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();

        return response()->json(['message' => 'Activity deleted successfully']);
    }
}
