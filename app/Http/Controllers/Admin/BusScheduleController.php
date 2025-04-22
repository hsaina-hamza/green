<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\BusTime;
use Illuminate\Http\Request;

class BusScheduleController extends Controller
{
    public function index()
    {
        $schedules = BusTime::with('location')->latest()->paginate(10);
        return view('admin.bus-schedules.index', compact('schedules'));
    }

    public function create()
    {
        $locations = Location::all();
        return view('admin.bus-schedules.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'route' => 'required|string|max:255',
            'departure_time' => 'required|date_format:H:i',
            'arrival_time' => 'required|date_format:H:i|after:departure_time',
            'frequency' => 'required|string|max:255',
        ]);

        BusTime::create($validated);

        return redirect()->route('admin.bus-schedules.index')
            ->with('success', 'تم إنشاء جدول الحافلة بنجاح');
    }

    public function edit(BusTime $busSchedule)
    {
        $locations = Location::all();
        return view('admin.bus-schedules.edit', compact('busSchedule', 'locations'));
    }

    public function update(Request $request, BusTime $busSchedule)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'route' => 'required|string|max:255',
            'departure_time' => 'required|date_format:H:i',
            'arrival_time' => 'required|date_format:H:i|after:departure_time',
            'frequency' => 'required|string|max:255',
        ]);

        $busSchedule->update($validated);

        return redirect()->route('admin.bus-schedules.index')
            ->with('success', 'تم تحديث جدول الحافلة بنجاح');
    }

    public function destroy(BusTime $busSchedule)
    {
        $busSchedule->delete();

        return redirect()->route('admin.bus-schedules.index')
            ->with('success', 'تم حذف جدول الحافلة بنجاح');
    }
}
