<?php

namespace App\Http\Controllers;

use App\Models\WasteReport;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class WasteReportController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->authorizeResource(WasteReport::class, 'waste_report');
    }

    public function index()
    {
        $reports = WasteReport::with(['user', 'site', 'assignedWorker'])
            ->latest()
            ->paginate(10);

        return view('waste-reports.index', compact('reports'));
    }

    public function create()
    {
        $sites = Site::all();
        return view('waste-reports.create', compact('sites'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'waste_type' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('waste-reports', 'public');
            $validated['image_path'] = $path;
        }

        $validated['user_id'] = Auth::id();
        $report = WasteReport::create($validated);

        return redirect()->route('waste-reports.show', $report)
            ->with('success', 'Report created successfully.');
    }

    public function show(WasteReport $wasteReport)
    {
        $wasteReport->load(['comments.user', 'assignedWorker']);
        return view('waste-reports.show', compact('wasteReport'));
    }

    public function edit(WasteReport $wasteReport)
    {
        $sites = Site::all();
        $workers = User::where('role', 'worker')->get();
        return view('waste-reports.edit', compact('wasteReport', 'sites', 'workers'));
    }

    public function update(Request $request, WasteReport $wasteReport)
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'waste_type' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:pending,in_progress,completed',
            'assigned_worker_id' => 'nullable|exists:users,id',
        ]);

        if ($request->hasFile('image')) {
            if ($wasteReport->image_path) {
                Storage::disk('public')->delete($wasteReport->image_path);
            }
            $path = $request->file('image')->store('waste-reports', 'public');
            $validated['image_path'] = $path;
        }

        $wasteReport->update($validated);

        return redirect()->route('waste-reports.show', $wasteReport)
            ->with('success', 'Report updated successfully.');
    }

    public function destroy(WasteReport $wasteReport)
    {
        if ($wasteReport->image_path) {
            Storage::disk('public')->delete($wasteReport->image_path);
        }
        
        $wasteReport->delete();

        return redirect()->route('waste-reports.index')
            ->with('success', 'Report deleted successfully.');
    }

    public function updateStatus(Request $request, WasteReport $wasteReport)
    {
        $this->authorize('updateStatus', $wasteReport);

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $wasteReport->update($validated);

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    public function assign(Request $request, WasteReport $wasteReport)
    {
        $this->authorize('assign', $wasteReport);

        $validated = $request->validate([
            'assigned_worker_id' => 'required|exists:users,id',
        ]);

        $wasteReport->update($validated);

        return redirect()->back()->with('success', 'Worker assigned successfully.');
    }
}
