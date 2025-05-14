<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\WasteReport;
use Illuminate\Http\Request;

class WasteMapController extends Controller
{
    /**
     * Display the waste map with both waste reports and admin locations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all waste reports with their relationships
        $wasteReports = WasteReport::with([
            'location',
            'wasteType',
            'reporter'
        ])->get();

        // Get all locations
        $locations = Location::withCount('wasteReports')->get();

        return view('waste-map', compact('wasteReports', 'locations'));
    }
}
