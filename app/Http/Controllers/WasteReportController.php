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
        $this->middleware('auth')->except(['index', 'show', 'wasteMap', 'createArabic', 'store']);
    }

    public function index()
    {
        $wasteReports = WasteReport::latest()->paginate(10);
        return view('waste-reports.index', compact('wasteReports'));
    }

    public function createArabic()
    {
        $wasteTypes = WasteType::all();
        $messages = [
            'waste_type_id.required' => 'يرجى اختيار نوع النفايات',
            'waste_type_id.exists' => 'نوع النفايات غير صالح',
            'latitude.required' => 'يرجى تحديد الموقع على الخريطة',
            'longitude.required' => 'يرجى تحديد الموقع على الخريطة',
            'latitude.numeric' => 'إحداثيات غير صالحة',
            'longitude.numeric' => 'إحداثيات غير صالحة',
            'latitude.between' => 'إحداثيات غير صالحة',
            'longitude.between' => 'إحداثيات غير صالحة',
            'image.image' => 'يجب أن يكون الملف صورة',
            'image.max' => 'حجم الصورة يجب أن لا يتجاوز 2 ميغابايت',
        ];
        return view('waste-reports.create-ar', compact('wasteTypes', 'messages'));
    }

    public function store(Request $request)
    {
        $messages = [
            'waste_type_id.required' => 'يرجى اختيار نوع النفايات',
            'waste_type_id.exists' => 'نوع النفايات غير صالح',
            'latitude.required' => 'يرجى تحديد الموقع على الخريطة',
            'longitude.required' => 'يرجى تحديد الموقع على الخريطة',
            'latitude.numeric' => 'إحداثيات غير صالحة',
            'longitude.numeric' => 'إحداثيات غير صالحة',
            'latitude.between' => 'إحداثيات غير صالحة',
            'longitude.between' => 'إحداثيات غير صالحة',
            'image.image' => 'يجب أن يكون الملف صورة',
            'image.max' => 'حجم الصورة يجب أن لا يتجاوز 2 ميغابايت',
        ];

        $validated = $request->validate([
            'waste_type_id' => 'required|exists:waste_types,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ], $messages);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('waste-reports', 'public');
        }

        // Get default site
        $defaultSite = \App\Models\Site::first();
        
        // Create or find location based on coordinates
        $location = Location::firstOrCreate([
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude']
        ], [
            'name' => 'Location at ' . $validated['latitude'] . ', ' . $validated['longitude'],
            'site_id' => $defaultSite->id
        ]);

        // Create waste report
        $wasteReport = WasteReport::create([
            'waste_type_id' => $validated['waste_type_id'],
            'location_id' => $location->id,
            'site_id' => $location->site_id,
            'description' => $validated['description'],
            'reported_by' => Auth::id() ?? null,
            'status' => 'pending',
            'image_path' => $imagePath,
            'urgency_level' => $request->input('urgency_level', 'normal'),
        ]);

        return redirect()->route('waste-reports.show', $wasteReport)
            ->with('success', 'تم إرسال التقرير بنجاح');
    }

    public function show(WasteReport $waste_report)
    {
        return view('waste-reports.show', ['wasteReport' => $waste_report]);
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
            'waste_type_id' => 'required|exists:waste_types,id',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,resolved',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($wasteReport->image_path) {
                Storage::disk('public')->delete($wasteReport->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('waste-reports', 'public');
        }

        $wasteReport->update($validated);

        return redirect()->route('waste-reports.show', $wasteReport)
            ->with('success', 'تم تحديث التقرير بنجاح');
    }

    public function destroy(WasteReport $wasteReport)
    {
        $this->authorize('delete', $wasteReport);

        if ($wasteReport->image_path) {
            Storage::disk('public')->delete($wasteReport->image_path);
        }

        $wasteReport->delete();

        return redirect()->route('waste-reports.index')
            ->with('success', 'تم حذف التقرير بنجاح');
    }

    public function wasteMap()
    {
        $reports = WasteReport::with(['site', 'wasteType', 'location'])
            ->whereHas('location', function ($query) {
                $query->whereNotNull('latitude')->whereNotNull('longitude');
            })
            ->get()
            ->map(function ($report) {
                return [
                    'id' => $report->id,
                    'title' => $report->title,
                    'type' => $report->wasteType->name,
                    'status' => ucfirst($report->status),
                    'location' => $report->location->name,
                    'latitude' => $report->location->latitude,
                    'longitude' => $report->location->longitude,
                    'url' => route('waste-reports.show', $report),
                ];
            });

        return view('waste-reports.map', compact('reports'));
    }
}
