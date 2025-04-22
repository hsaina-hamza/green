<?php

namespace App\Http\Controllers;

use App\Models\GarbageTruckSchedule;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class GarbageTruckScheduleController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|worker']);
    }

    /**
     * Display a listing of the schedules.
     */
    public function index(): View
    {
        $schedules = GarbageTruckSchedule::with('location')
            ->orderBy('scheduled_time')
            ->get();

        return view('garbage-truck-schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new schedule.
     */
    public function create(): View
    {
        $locations = Location::orderBy('name')->get();
        return view('garbage-truck-schedules.create', compact('locations'));
    }

    /**
     * Store a newly created schedule in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'truck_number' => 'required|string|max:255',
            'scheduled_time' => 'required|date',
        ]);

        GarbageTruckSchedule::create($validated);

        return redirect()
            ->route('garbage-truck-schedules.index')
            ->with('success', 'Schedule created successfully.');
    }

    /**
     * Show the form for editing the specified schedule.
     */
    public function edit(GarbageTruckSchedule $garbageTruckSchedule): View
    {
        $locations = Location::orderBy('name')->get();
        return view('garbage-truck-schedules.edit', [
            'schedule' => $garbageTruckSchedule,
            'locations' => $locations,
        ]);
    }

    /**
     * Update the specified schedule in storage.
     */
    public function update(Request $request, GarbageTruckSchedule $garbageTruckSchedule): RedirectResponse
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'truck_number' => 'required|string|max:255',
            'scheduled_time' => 'required|date',
        ]);

        $garbageTruckSchedule->update($validated);

        return redirect()
            ->route('garbage-truck-schedules.index')
            ->with('success', 'Schedule updated successfully.');
    }

    /**
     * Remove the specified schedule from storage.
     */
    public function destroy(GarbageTruckSchedule $garbageTruckSchedule): RedirectResponse
    {
        $garbageTruckSchedule->delete();

        return redirect()
            ->route('garbage-truck-schedules.index')
            ->with('success', 'Schedule deleted successfully.');
    }
}
