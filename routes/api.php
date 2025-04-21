<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WasteReportController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\GarbageScheduleController;
use App\Http\Controllers\DashboardController;

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

Route::middleware('auth:sanctum')->group(function () {
    // User Profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Waste Reports
    Route::get('/waste-reports', [WasteReportController::class, 'index']);
    Route::get('/waste-reports/{waste_report}', [WasteReportController::class, 'show']);
    Route::post('/waste-reports', [WasteReportController::class, 'store']);
    Route::put('/waste-reports/{waste_report}', [WasteReportController::class, 'update']);
    Route::delete('/waste-reports/{waste_report}', [WasteReportController::class, 'destroy']);

    // Comments
    Route::get('/waste-reports/{waste_report}/comments', [CommentController::class, 'index']);
    Route::post('/waste-reports/{waste_report}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

    // Sites
    Route::get('/sites', [SiteController::class, 'index']);
    Route::get('/sites/{site}', [SiteController::class, 'show']);
    Route::middleware('role:admin')->group(function () {
        Route::post('/sites', [SiteController::class, 'store']);
        Route::put('/sites/{site}', [SiteController::class, 'update']);
        Route::delete('/sites/{site}', [SiteController::class, 'destroy']);
    });

    // Garbage Schedules
    Route::get('/schedules', [GarbageScheduleController::class, 'index']);
    Route::get('/schedules/{schedule}', [GarbageScheduleController::class, 'show']);
    Route::middleware('role:admin')->group(function () {
        Route::post('/schedules', [GarbageScheduleController::class, 'store']);
        Route::put('/schedules/{schedule}', [GarbageScheduleController::class, 'update']);
        Route::delete('/schedules/{schedule}', [GarbageScheduleController::class, 'destroy']);
    });

    // Statistics
    Route::middleware('role:admin,worker')->group(function () {
        Route::get('/statistics', [DashboardController::class, 'statistics']);
    });
});
