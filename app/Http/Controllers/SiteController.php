<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class SiteController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->authorizeResource(Site::class, 'site');
    }

    public function index()
    {
        $sites = Site::withCount(['wasteReports', 'garbageSchedules'])
            ->latest()
            ->paginate(10);

        return view('sites.index', compact('sites'));
    }

    public function create()
    {
        return view('sites.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $site = Site::create($validated);

        return redirect()->route('sites.show', $site)
            ->with('success', 'Site created successfully.');
    }

    public function show(Site $site)
    {
        $site->load([
            'wasteReports' => function ($query) {
                $query->latest()->take(5);
            },
            'garbageSchedules' => function ($query) {
                $query->upcoming()->take(5);
            }
        ]);

        return view('sites.show', compact('site'));
    }

    public function edit(Site $site)
    {
        return view('sites.edit', compact('site'));
    }

    public function update(Request $request, Site $site)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $site->update($validated);

        return redirect()->route('sites.show', $site)
            ->with('success', 'Site updated successfully.');
    }

    public function destroy(Site $site)
    {
        $site->delete();

        return redirect()->route('sites.index')
            ->with('success', 'Site deleted successfully.');
    }

    public function map()
    {
        $sites = Site::withCount(['wasteReports', 'garbageSchedules'])
            ->with(['activeWasteReports', 'upcomingSchedules'])
            ->get()
            ->map(function ($site) {
                return [
                    'id' => $site->id,
                    'name' => $site->name,
                    'address' => $site->address,
                    'latitude' => (float) $site->latitude,
                    'longitude' => (float) $site->longitude,
                    'waste_reports_count' => $site->waste_reports_count,
                    'garbage_schedules_count' => $site->garbage_schedules_count,
                    'active_reports_count' => $site->activeWasteReports->count(),
                    'upcoming_schedules_count' => $site->upcomingSchedules->count(),
                    'url' => route('sites.show', $site),
                    'google_maps_url' => $site->google_maps_url,
                    'has_active_reports' => $site->hasActiveReports(),
                    'has_upcoming_schedules' => $site->hasUpcomingSchedules(),
                ];
            });

        return view('sites.map', compact('sites'));
    }
}
