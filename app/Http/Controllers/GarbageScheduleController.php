<?php

namespace App\Http\Controllers;

use App\Models\GarbageSchedule;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class GarbageScheduleController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->authorizeResource(GarbageSchedule::class, 'schedule');
    }

    public function index()
    {
        $upcomingSchedules = GarbageSchedule::with('site')
            ->upcoming()
            ->paginate(10, ['*'], 'upcoming');

        $pastSchedules = GarbageSchedule::with('site')
            ->past()
            ->paginate(10, ['*'], 'past');

        return view('schedules.index', compact('upcomingSchedules', 'pastSchedules'));
    }

    public function create()
    {
        $sites = Site::all();
        return view('schedules.create', compact('sites'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'truck_number' => 'required|string|max:255',
            'scheduled_time' => 'required|date|after:now',
        ]);

        $schedule = GarbageSchedule::create($validated);

        return redirect()->route('schedules.show', $schedule)
            ->with('success', 'Schedule created successfully.');
    }

    public function show(GarbageSchedule $schedule)
    {
        $schedule->load('site');
        return view('schedules.show', compact('schedule'));
    }

    public function edit(GarbageSchedule $schedule)
    {
        $sites = Site::all();
        return view('schedules.edit', compact('schedule', 'sites'));
    }

    public function update(Request $request, GarbageSchedule $schedule)
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'truck_number' => 'required|string|max:255',
            'scheduled_time' => 'required|date|after:now',
        ]);

        $schedule->update($validated);

        return redirect()->route('schedules.show', $schedule)
            ->with('success', 'Schedule updated successfully.');
    }

    public function destroy(GarbageSchedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule deleted successfully.');
    }

    public function calendar()
    {
        $schedules = GarbageSchedule::with('site')
            ->whereDate('scheduled_time', '>=', now()->startOfMonth())
            ->whereDate('scheduled_time', '<=', now()->endOfMonth())
            ->get();

        return view('schedules.calendar', compact('schedules'));
    }

    public function siteSchedules(Site $site)
    {
        $schedules = $site->garbageSchedules()
            ->with('site')
            ->upcoming()
            ->paginate(10);

        return view('schedules.site', compact('schedules', 'site'));
    }
}
