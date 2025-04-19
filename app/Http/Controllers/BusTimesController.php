<?php

namespace App\Http\Controllers;

use App\Models\BusSchedule;
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
        $this->middleware(['auth', 'role:worker|admin'])->except(['index', 'show']);
    }

    /**
     * Display a listing of bus schedules.
     */
    public function index()
    {
        $schedules = BusSchedule::with(['creator', 'updater'])
            ->active()
            ->orderBy('neighborhood')
            ->orderBy('departure_time')
            ->get()
            ->groupBy('neighborhood');

        return view('bus-times.index', compact('schedules'));
    }

    /**
     * Show the form for managing bus schedules (admin/worker view).
     */
    public function manage()
    {
        $schedules = BusSchedule::with(['creator', 'updater'])
            ->orderBy('neighborhood')
            ->orderBy('departure_time')
            ->get()
            ->groupBy('neighborhood');

        return view('bus-times.manage', compact('schedules'));
    }

    /**
     * Show the form for creating a new bus schedule.
     */
    public function create()
    {
        return view('bus-times.create', [
            'frequencies' => BusSchedule::FREQUENCIES,
        ]);
    }

    /**
     * Store a newly created bus schedule in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'neighborhood' => 'required|string|max:255',
            'route_name' => 'required|string|max:255',
            'departure_time' => 'required|date_format:H:i',
            'arrival_time' => 'required|date_format:H:i|after:departure_time',
            'frequency' => 'required|string|in:' . implode(',', array_keys(BusSchedule::FREQUENCIES)),
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        BusSchedule::create($validated);

        return redirect()->route('bus-times.manage')
            ->with('success', 'Bus schedule created successfully.');
    }

    /**
     * Show the form for editing the specified bus schedule.
     */
    public function edit(BusSchedule $busSchedule)
    {
        return view('bus-times.edit', [
            'schedule' => $busSchedule,
            'frequencies' => BusSchedule::FREQUENCIES,
        ]);
    }

    /**
     * Update the specified bus schedule in storage.
     */
    public function update(Request $request, BusSchedule $busSchedule)
    {
        $validated = $request->validate([
            'neighborhood' => 'required|string|max:255',
            'route_name' => 'required|string|max:255',
            'departure_time' => 'required|date_format:H:i',
            'arrival_time' => 'required|date_format:H:i|after:departure_time',
            'frequency' => 'required|string|in:' . implode(',', array_keys(BusSchedule::FREQUENCIES)),
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['updated_by'] = Auth::id();

        $busSchedule->update($validated);

        return redirect()->route('bus-times.manage')
            ->with('success', 'Bus schedule updated successfully.');
    }

    /**
     * Remove the specified bus schedule from storage.
     */
    public function destroy(BusSchedule $busSchedule)
    {
        $busSchedule->delete();

        return redirect()->route('bus-times.manage')
            ->with('success', 'Bus schedule deleted successfully.');
    }

    /**
     * Toggle the active status of the specified bus schedule.
     */
    public function toggleStatus(BusSchedule $busSchedule)
    {
        $busSchedule->update([
            'is_active' => !$busSchedule->is_active,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('bus-times.manage')
            ->with('success', 'Bus schedule status updated successfully.');
    }
}
