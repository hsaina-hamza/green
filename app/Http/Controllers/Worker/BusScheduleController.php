<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\BusTime;
use Illuminate\Http\Request;

class BusScheduleController extends Controller
{
    public function index()
    {
        $schedules = BusTime::with('location')->latest()->paginate(10);
        return view('worker.bus-schedules.index', compact('schedules'));
    }

    public function edit(BusTime $busSchedule)
    {
        return view('worker.bus-schedules.edit', compact('busSchedule'));
    }

    public function update(Request $request, BusTime $busSchedule)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000'
        ]);

        $busSchedule->update($validated);

        return redirect()->route('worker.bus-schedules.index')
            ->with('success', 'تم تحديث ملاحظات جدول الحافلة بنجاح');
    }
}
