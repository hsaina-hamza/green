<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WasteType;
use Illuminate\Http\Request;

class WasteTypeController extends Controller
{
    public function index()
    {
        $wasteTypes = WasteType::all();
        return view('admin.waste-types.index', compact('wasteTypes'));
    }

    public function create()
    {
        return view('admin.waste-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_ar' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'description_ar' => ['nullable', 'string'],
            'icon' => ['required', 'string', 'max:50'],
            'color' => ['required', 'string', 'max:7', 'regex:/^#[a-zA-Z0-9]{6}$/'],
            'is_active' => ['boolean'],
        ]);

        WasteType::create($validated);

        return redirect()->route('admin.waste-types.index')
            ->with('success', 'تم إضافة نوع النفايات بنجاح');
    }

    public function edit(WasteType $wasteType)
    {
        return view('admin.waste-types.edit', compact('wasteType'));
    }

    public function update(Request $request, WasteType $wasteType)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_ar' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'description_ar' => ['nullable', 'string'],
            'icon' => ['required', 'string', 'max:50'],
            'color' => ['required', 'string', 'max:7', 'regex:/^#[a-zA-Z0-9]{6}$/'],
            'is_active' => ['boolean'],
        ]);

        $wasteType->update($validated);

        return redirect()->route('admin.waste-types.index')
            ->with('success', 'تم تحديث نوع النفايات بنجاح');
    }

    public function destroy(WasteType $wasteType)
    {
        // Check if waste type is being used
        if ($wasteType->wasteReports()->exists()) {
            return redirect()->route('admin.waste-types.index')
                ->with('error', 'لا يمكن حذف نوع النفايات لأنه مرتبط ببلاغات');
        }

        $wasteType->delete();

        return redirect()->route('admin.waste-types.index')
            ->with('success', 'تم حذف نوع النفايات بنجاح');
    }

    public function toggleStatus(WasteType $wasteType)
    {
        $wasteType->update(['is_active' => !$wasteType->is_active]);

        return redirect()->route('admin.waste-types.index')
            ->with('success', 'تم تحديث حالة نوع النفايات بنجاح');
    }
}
