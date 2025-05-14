<?php

namespace App\Http\Controllers;

use App\Models\WasteReport;
use App\Models\WasteType;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class WasteReportController extends Controller
{
    public function __construct()
    {
        // Public routes
        $this->middleware('auth')->except(['index', 'show', 'wasteMap']);
    }

    /**
     * Check if the show route is actually trying to access create
     */
    private function isCreateRoute($waste_report): bool
    {
        return $waste_report === 'create';
    }

    public function index()
    {
        $wasteReports = WasteReport::latest()->paginate(10);
        return view('waste-reports.index', compact('wasteReports'));
    }

    public function create()
    {
        $wasteTypes = WasteType::all();
        $locations = Location::all();
        return view('waste-reports.create-with-roles', compact('wasteTypes', 'locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'waste_type_id' => 'required|exists:waste_types,id',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'location_id' => 'required|exists:locations,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048', // 2MB max
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('waste-reports', 'public');
        }

        $wasteReport = WasteReport::create([
            'waste_type_id' => $validated['waste_type_id'],
            'quantity' => $validated['quantity'],
            'unit' => $validated['unit'],
            'location_id' => $validated['location_id'],
            'description' => $validated['description'] ?? null,
            'reported_by' => Auth::id(),
            'status' => 'pending',
            'image_path' => $imagePath,
        ]);

        return redirect()->route('waste-reports.show', $wasteReport)
            ->with('success', 'Waste report created successfully.');
    }

    public function show($waste_report)
    {
        // If trying to access 'create' route, check auth and redirect to create
        if ($this->isCreateRoute($waste_report)) {
            if (!Auth::check()) {
                return Redirect::route('login')->with('message', 'Please login to create a waste report.');
            }
            return $this->create();
        }

        // Otherwise treat as a normal show request
        if (!is_numeric($waste_report)) {
            abort(404);
        }

        $wasteReport = WasteReport::findOrFail($waste_report);
        return view('waste-reports.show', compact('wasteReport'));
    }

    public function edit(WasteReport $wasteReport)
    {
        $this->authorize('update', $wasteReport);
        return view('waste-reports.edit', compact('wasteReport'));
    }

    public function update(Request $request, WasteReport $wasteReport)
    {
        $this->authorize('update', $wasteReport);

        $validated = $request->validate([
            'waste_type' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,resolved',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($wasteReport->image_path) {
                Storage::disk('public')->delete($wasteReport->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('waste-reports', 'public');
        }

        $wasteReport->update($validated);

        return redirect()->route('waste-reports.show', $wasteReport)
            ->with('success', 'Waste report updated successfully.');
    }

    public function destroy(WasteReport $wasteReport)
    {
        $this->authorize('delete', $wasteReport);

        // Delete image if exists
        if ($wasteReport->image_path) {
            Storage::disk('public')->delete($wasteReport->image_path);
        }

        $wasteReport->delete();

        return redirect()->route('waste-reports.index')
            ->with('success', 'Waste report deleted successfully.');
    }

    /**
     * Update the status of the waste report.
     */
    public function updateStatus(Request $request, WasteReport $wasteReport)
    {
        $this->authorize('updateStatus', $wasteReport);

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,resolved',
        ]);

        $wasteReport->update($validated);

        return back()->with('success', 'Status updated successfully.');
    }

    /**
     * Display a map of all waste reports.
     */
    public function wasteMap()
    {
        $reports = WasteReport::with(['location'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($report) {
                return [
                    'id' => $report->id,
                    'title' => $report->title,
                    'type' => $report->waste_type,
                    'status' => ucfirst($report->status),
                    'location' => $report->location->name,
                    'latitude' => $report->location->latitude,
                    'longitude' => $report->location->longitude,
                    'url' => route('waste-reports.show', ['id' => $report->id]),
                ];
            });

        return view('waste-reports.map', compact('reports'));
    }
}
