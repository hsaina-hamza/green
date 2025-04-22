<?php

namespace App\Http\Controllers;

use App\Models\WasteReport;
use Illuminate\Support\Facades\Route;

class WelcomeController extends Controller
{
    public function index()
    {
        $recentReports = WasteReport::with(['site', 'worker'])
            ->latest()
            ->take(3)
            ->get()
            ->map(function($report) {
                return (object)[
                    'id' => $report->id,
                    'title' => $report->title,
                    'location' => $report->site->name,
                    'status' => ucfirst($report->status),
                    'image_url' => $report->image_url,
                    'worker_name' => $report->worker ? $report->worker->name : null
                ];
            });

        $routeInfo = [
            'hasLogin' => Route::has('login'),
            'hasRegister' => Route::has('register'),
        ];

        return view('welcome', compact('recentReports', 'routeInfo'));
    }
}
