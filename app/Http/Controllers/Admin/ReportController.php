<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WasteReport;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $reports = WasteReport::with(['reporter', 'location', 'wasteType'])
            ->latest()
            ->paginate(10);
            
        return view('admin.reports.index', compact('reports'));
    }

    public function edit(WasteReport $report)
    {
        $report->load(['reporter', 'location', 'wasteType', 'comments.user']);
        return view('admin.reports.edit', compact('report'));
    }

    public function update(Request $request, WasteReport $report)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,in_progress,resolved'],
            'notes' => ['nullable', 'string'],
        ]);

        $oldStatus = $report->status;
        $report->update([
            'status' => $validated['status']
        ]);

        // Create a comment for the status change
        if ($oldStatus !== $validated['status']) {
            Comment::create([
                'waste_report_id' => $report->getKey(),
                'user_id' => Auth::id(),
                'content' => "Status changed from " . ucfirst($oldStatus) . " to " . ucfirst($validated['status']),
            ]);
        }

        // Add notes as a comment if provided
        if (!empty($validated['notes'])) {
            Comment::create([
                'waste_report_id' => $report->getKey(),
                'user_id' => Auth::id(),
                'content' => $validated['notes'],
            ]);
        }

        return redirect()->route('admin.reports.edit', $report)
            ->with('success', 'Report updated successfully.');
    }

    public function destroy(WasteReport $report)
    {
        // Delete associated comments first
        $report->comments()->delete();
        
        $report->delete();

        return redirect()->route('admin.reports.index')
            ->with('success', 'Report deleted successfully.');
    }
}
