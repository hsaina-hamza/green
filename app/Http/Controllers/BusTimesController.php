<?php

namespace App\Http\Controllers;

use App\Models\BusTime;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class BusTimesController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Only allow authenticated workers and admins to manage schedules
        $this->middleware(['auth', 'role:worker,admin'])->except(['index']);
    }

    /**
     * Display a listing of bus schedules.
     */
    public function index()
    {
        $busSchedules = BusTime::with('location')
            ->orderBy('departure_time')
            ->get();

        return view('bus-times.index', compact('busSchedules'));
    }

    /**
     * Show the form for creating a new bus schedule.
     */
    public function create()
    {
        $locations = Location::all();
        return view('bus-times.create', compact('locations'));
    }

    /**
     * Store a newly created bus schedule in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'route' => 'required|string|max:255',
            'departure_time' => 'required|date_format:H:i',
            'arrival_time' => 'required|date_format:H:i|after:departure_time',
            'frequency' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        BusTime::create($validated);

        return redirect()->route('bus-times.index')
            ->with('success', 'Bus schedule created successfully.');
    }

    /**
     * Show the form for editing the specified bus schedule.
     */
    public function edit(BusTime $busTime)
    {
        $locations = Location::all();
        return view('bus-times.edit', compact('busTime', 'locations'));
    }

    /**
     * Update the specified bus schedule in storage.
     */
    public function update(Request $request, BusTime $busTime)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'route' => 'required|string|max:255',
            'departure_time' => 'required|date_format:H:i',
            'arrival_time' => 'required|date_format:H:i|after:departure_time',
            'frequency' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $busTime->update($validated);

        return redirect()->route('bus-times.index')
            ->with('success', 'Bus schedule updated successfully.');
    }

    /**
     * Remove the specified bus schedule from storage.
     */
    public function destroy(BusTime $busTime)
    {
        $busTime->delete();

        return redirect()->route('bus-times.index')
            ->with('success', 'Bus schedule deleted successfully.');
    }
}
