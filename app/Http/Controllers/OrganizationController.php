<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\Activity;

class OrganizationController extends Controller
{    

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $organizations = Organization::with(['building', 'activities'])
            ->paginate($perPage);

        return response()->json($organizations);
    }

    /**
     * Показать организацию по ID
     */
    public function show($id)
    {
        $organization = Organization::with(['building', 'activities'])
            ->findOrFail($id);

        return response()->json($organization);
    }

    /**
     * 🔎 Организации в конкретном здании
     */
    public function byBuilding($buildingId, Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $organizations = Organization::where('building_id', $buildingId)
            ->with(['building', 'activities'])
            ->paginate($perPage);

        return response()->json($organizations);
    }

    /**
     * 🔎 Организации по виду деятельности (с учётом вложенности)
     */
    public function byActivity($activityId, Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $activityIds = $this->getActivityWithChildren($activityId);

        $organizations = Organization::whereHas('activities', function ($q) use ($activityIds) {
                $q->whereIn('activities.id', $activityIds);
            })
            ->with(['building', 'activities'])
            ->paginate($perPage);

        return response()->json($organizations);
    }

    /**
     * Рекурсивно достаём id активности и её детей (до 3 уровней)
     */
    private function getActivityWithChildren($activityId, $level = 1)
    {
        if ($level > 3) return [];

        $activity = Activity::with('children')->findOrFail($activityId);
        $ids = [$activity->id];

        foreach ($activity->children as $child) {
            $ids = array_merge($ids, $this->getActivityWithChildren($child->id, $level + 1));
        }

        return $ids;
    }

    /**
     * 🔎 Организации в радиусе
     */
    public function byRadius(Request $request)
    {
        $lat = $request->get('latitude');
        $lng = $request->get('longitude');
        $radius = $request->get('radius', 5);
        $perPage = $request->get('per_page', 10);

        $organizations = Organization::join('buildings', 'organizations.building_id', '=', 'buildings.id')
            ->select('organizations.*')
            ->selectRaw(
                "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) 
                * cos(radians(longitude) - radians(?)) + sin(radians(?)) 
                * sin(radians(latitude)))) AS distance",
                [$lat, $lng, $lat]
            )
            ->having('distance', '<=', $radius) // фильтр по радиусу
            ->orderBy('distance', 'asc')
            ->with(['building', 'activities'])
            ->paginate($perPage);

        return response()->json($organizations);
    }

    /**
     * 🔎 Организации в прямоугольной области (bbox)
     */
    public function byBoundingBox(Request $request)
    {
        $lat1 = $request->get('lat1');
        $lng1 = $request->get('lng1');
        $lat2 = $request->get('lat2');
        $lng2 = $request->get('lng2');
        $perPage = $request->get('per_page', 10);

        $minLat = min($lat1, $lat2);
        $maxLat = max($lat1, $lat2);
        $minLng = min($lng1, $lng2);
        $maxLng = max($lng1, $lng2);

        $organizations = Organization::whereHas('building', function ($q) use ($minLat, $maxLat, $minLng, $maxLng) {
                $q->whereBetween('latitude', [$minLat, $maxLat])
                ->whereBetween('longitude', [$minLng, $maxLng]);
            })
            ->with(['building', 'activities'])
            ->paginate($perPage);

        return response()->json($organizations);
    }

    /**
     * 🔎 Поиск организаций по названию
     */
    public function searchByName(Request $request)
    {
        $query = $request->get('query', '');
        $perPage = $request->get('per_page', 10);

        $organizations = Organization::where('name', 'like', "%{$query}%")
            ->with(['building', 'activities'])
            ->paginate($perPage);

        return response()->json($organizations);
    }

    /**
     * Создать новую организацию
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'phones'     => 'required|array',
            'building'   => 'required|array',
            'building.address' => 'required|string|max:255',
            'activities' => 'array'
        ]);

        // Найти существующее здание по адресу или создать новое
        $building = Building::firstOrCreate([
            'address' => $validated['building']['address']
        ]);

        $organization = Organization::create([
            'name'        => $validated['name'],
            'phones'      => json_encode($validated['phones']),
            'building_id' => $building->id,
        ]);

        if (!empty($validated['activities'])) {
            $organization->activities()->syncWithoutDetaching($validated['activities']);
        }

        return response()->json($organization->load('activities','building'), 201);
    }

    /**
     * Обновить организацию
     */
    public function update(Request $request, $id)
    {
        $organization = Organization::findOrFail($id);

        $validated = $request->validate([
            'name'       => 'string|max:255',
            'phones'     => 'array',
            'building'   => 'array',
            'building.address' => 'string|max:255',
            'activities' => 'array'
        ]);

        // Если передан адрес здания — ищем или создаем
        if (!empty($validated['building']['address'])) {
            $building = Building::firstOrCreate([
                'address' => $validated['building']['address']
            ]);
            $organization->building_id = $building->id;
        }

        $organization->name = $validated['name'] ?? $organization->name;
        if (isset($validated['phones'])) {
            $organization->phones = json_encode($validated['phones']);
        }
        $organization->save();

        if (isset($validated['activities'])) {
            $organization->activities()->syncWithoutDetaching($validated['activities']);
        }

        return response()->json($organization->load('activities','building'));
    }
    /**
     * Удалить организацию
     */
    public function destroy($id)
    {
        $organization = Organization::findOrFail($id);
        $organization->delete();

        return response()->json(['message' => 'Organization deleted successfully']);
    }
}
