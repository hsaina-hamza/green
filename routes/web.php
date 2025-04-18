<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WasteReportController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\GarbageScheduleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard Routes - accessible by all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Statistics Route - accessible by admin and workers
    Route::get('/statistics', [DashboardController::class, 'statistics'])
        ->middleware('role:admin,worker')
        ->name('statistics');

    // Waste Report Routes
    Route::resource('waste-reports', WasteReportController::class);
    Route::patch('/waste-reports/{waste_report}/status', [WasteReportController::class, 'updateStatus'])
        ->middleware('role:admin,worker')
        ->name('waste-reports.update-status');
    Route::patch('/waste-reports/{waste_report}/assign', [WasteReportController::class, 'assign'])
        ->middleware('role:admin')
        ->name('waste-reports.assign');

    // Comment Routes
    Route::post('/waste-reports/{waste_report}/comments', [CommentController::class, 'store'])
        ->name('comments.store');
    Route::patch('/comments/{comment}', [CommentController::class, 'update'])
        ->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');

    // Site Routes - admin only except for viewing
    Route::resource('sites', SiteController::class)->except(['index', 'show'])
        ->middleware('role:admin');
    Route::get('/sites', [SiteController::class, 'index'])->name('sites.index');
    Route::get('/sites/{site}', [SiteController::class, 'show'])->name('sites.show');
    Route::get('/map', [SiteController::class, 'map'])->name('sites.map');

    // Garbage Schedule Routes - admin only except for viewing
    Route::resource('schedules', GarbageScheduleController::class)->except(['index', 'show'])
        ->middleware('role:admin');
    Route::get('/schedules', [GarbageScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/{schedule}', [GarbageScheduleController::class, 'show'])->name('schedules.show');
    Route::get('/calendar', [GarbageScheduleController::class, 'calendar'])->name('schedules.calendar');
    Route::get('/sites/{site}/schedules', [GarbageScheduleController::class, 'siteSchedules'])
        ->name('sites.schedules');
});

require __DIR__.'/auth.php';
