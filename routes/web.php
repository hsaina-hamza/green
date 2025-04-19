<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WasteReportController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\GarbageScheduleController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes (no authentication required)
Route::get('/', function () {
    $recentReports = \App\Models\WasteReport::with('site')
        ->latest()
        ->take(3)
        ->get()
        ->map(function($report) {
            return (object)[
                'id' => $report->id,
                'title' => $report->title,
                'location' => $report->site->name,
                'status' => ucfirst($report->status),
                'image_url' => $report->image_url
            ];
        });
    
    return view('welcome', compact('recentReports'));
});

// Public Site Routes
Route::get('/sites', [SiteController::class, 'index'])->name('sites.index');
Route::get('/sites/map', [SiteController::class, 'map'])->name('sites.map');
Route::get('/sites/{site}', [SiteController::class, 'show'])->name('sites.show');

// Public Schedule Routes
Route::get('/schedules', [GarbageScheduleController::class, 'index'])->name('schedules.index');
Route::get('/schedules/{schedule}', [GarbageScheduleController::class, 'show'])->name('schedules.show');
Route::get('/calendar', [GarbageScheduleController::class, 'calendar'])->name('schedules.calendar');
Route::get('/sites/{site}/schedules', [GarbageScheduleController::class, 'siteSchedules'])
    ->name('sites.schedules');

// Public Waste Report Routes
Route::get('/waste-reports', [WasteReportController::class, 'index'])->name('waste-reports.index');
Route::get('/waste-reports/{waste_report}', [WasteReportController::class, 'show'])->name('waste-reports.show');

// Routes that require authentication
Route::middleware(['auth'])->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Authenticated Waste Report Routes
    Route::get('/waste-reports/create', [WasteReportController::class, 'create'])->name('waste-reports.create');
    Route::post('/waste-reports', [WasteReportController::class, 'store'])->name('waste-reports.store');
    Route::get('/waste-reports/{waste_report}/edit', [WasteReportController::class, 'edit'])->name('waste-reports.edit');
    Route::put('/waste-reports/{waste_report}', [WasteReportController::class, 'update'])->name('waste-reports.update');
    Route::delete('/waste-reports/{waste_report}', [WasteReportController::class, 'destroy'])->name('waste-reports.destroy');

    // Comment Routes
    Route::post('/waste-reports/{waste_report}/comments', [CommentController::class, 'store'])
        ->name('comments.store');
    Route::patch('/comments/{comment}', [CommentController::class, 'update'])
        ->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');
});

// Routes that require worker or admin role
Route::middleware(['auth', 'role:worker|admin'])->group(function () {
    Route::get('/statistics', [DashboardController::class, 'statistics'])->name('statistics');
    Route::patch('/waste-reports/{waste_report}/status', [WasteReportController::class, 'updateStatus'])
        ->name('waste-reports.update-status');
});

// Routes that require admin role
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin Site Routes
    Route::get('/sites/create', [SiteController::class, 'create'])->name('sites.create');
    Route::post('/sites', [SiteController::class, 'store'])->name('sites.store');
    Route::get('/sites/{site}/edit', [SiteController::class, 'edit'])->name('sites.edit');
    Route::put('/sites/{site}', [SiteController::class, 'update'])->name('sites.update');
    Route::delete('/sites/{site}', [SiteController::class, 'destroy'])->name('sites.destroy');

    // Admin Schedule Routes
    Route::get('/schedules/create', [GarbageScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/schedules', [GarbageScheduleController::class, 'store'])->name('schedules.store');
    Route::get('/schedules/{schedule}/edit', [GarbageScheduleController::class, 'edit'])->name('schedules.edit');
    Route::put('/schedules/{schedule}', [GarbageScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/schedules/{schedule}', [GarbageScheduleController::class, 'destroy'])->name('schedules.destroy');

    // Admin Waste Report Routes
    Route::patch('/waste-reports/{waste_report}/assign', [WasteReportController::class, 'assign'])
        ->name('waste-reports.assign');
});



require __DIR__.'/auth.php';

Route::get('/waste-map', [WasteReportController::class, 'wasteMap'])->name('waste-map');
