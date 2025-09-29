<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\SwaggerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// фильтры — сначала специфические маршруты
Route::middleware('check.api.key')->group(function () {
    Route::get('organizations/search', [OrganizationController::class, 'searchByName']);
    Route::get('organizations/radius', [OrganizationController::class, 'byRadius']);
    Route::get('organizations/bybbox', [OrganizationController::class, 'byBoundingBox']);
    Route::get('organizations/building/{buildingId}', [OrganizationController::class, 'byBuilding']);
    Route::get('organizations/activity/{activityId}', [OrganizationController::class, 'byActivity']);

    // стандартные CRUD
    Route::apiResource('organizations', OrganizationController::class);
    Route::apiResource('buildings', BuildingController::class);
    Route::apiResource('activities', ActivityController::class);
});

