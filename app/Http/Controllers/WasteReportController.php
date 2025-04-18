<?php

namespace App\Http\Controllers;

use App\Models\WasteReport;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class WasteReportController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Apply auth middleware except for index and show
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the waste reports.
     */
    public function index()
    {
        $wasteReports = WasteReport::with(['site', 'user', 'worker'])
            ->latest()
            ->paginate(10);

        return view('waste-reports.index', compact('wasteReports'));
    }

    /**
     * Show the form for creating a new waste report.
     */
    public function create()
    {
        $sites = Site::all();
        return view('waste-reports.create', compact('sites'));
    }

    /**
     * Store a newly created waste report in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string|in:general,recyclable,hazardous,organic',
            'urgency_level' => 'required|string|in:low,medium,high',
            'site_id' => 'required|exists:sites,id',
            'estimated_size' => 'nullable|integer|min:1',
            'location_details' => 'nullable|string|max:255',
            'image_url' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        $wasteReport = WasteReport::create($validated);

        return redirect()->route('waste-reports.show', $wasteReport)
            ->with('success', 'Waste report created successfully.');
    }

    /**
     * Display the specified waste report.
     */
    public function show(WasteReport $wasteReport)
    {
        $wasteReport->load(['site', 'user', 'worker', 'comments.user']);
        return view('waste-reports.show', compact('wasteReport'));
    }

    /**
     * Show the form for editing the specified waste report.
     */
    public function edit(WasteReport $wasteReport)
    {
        $this->authorize('update', $wasteReport);
        $sites = Site::all();
        return view('waste-reports.edit', compact('wasteReport', 'sites'));
    }

    /**
     * Update the specified waste report in storage.
     */
    public function update(Request $request, WasteReport $wasteReport)
    {
        $this->authorize('update', $wasteReport);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string|in:general,recyclable,hazardous,organic',
            'urgency_level' => 'required|string|in:low,medium,high',
            'site_id' => 'required|exists:sites,id',
            'estimated_size' => 'nullable|integer|min:1',
            'location_details' => 'nullable|string|max:255',
            'image_url' => 'nullable|string|max:255',
        ]);

        $wasteReport->update($validated);

        return redirect()->route('waste-reports.show', $wasteReport)
            ->with('success', 'Waste report updated successfully.');
    }

    /**
     * Remove the specified waste report from storage.
     */
    public function destroy(WasteReport $wasteReport)
    {
        $this->authorize('delete', $wasteReport);

        $wasteReport->delete();

        return redirect()->route('waste-reports.index')
            ->with('success', 'Waste report deleted successfully.');
    }

    /**
     * Update the status of the specified waste report.
     */
    public function updateStatus(Request $request, WasteReport $wasteReport)
    {
        $this->authorize('updateStatus', $wasteReport);

        $validated = $request->validate([
            'status' => 'required|string|in:pending,in_progress,completed',
        ]);

        $wasteReport->update($validated);

        return redirect()->route('waste-reports.show', $wasteReport)
            ->with('success', 'Waste report status updated successfully.');
    }

    /**
     * Assign a worker to the specified waste report.
     */
    public function assign(Request $request, WasteReport $wasteReport)
    {
        $this->authorize('assign', $wasteReport);

        $validated = $request->validate([
            'worker_id' => 'required|exists:users,id',
        ]);

        $wasteReport->update($validated);

        return redirect()->route('waste-reports.show', $wasteReport)
            ->with('success', 'Worker assigned successfully.');
    }
}
