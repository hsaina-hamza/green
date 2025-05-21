<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display waste reports for a specific location.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\View\View
     */
    public function reports(Location $location)
    {
        $reports = $location->wasteReports()
            ->with(['wasteType', 'reporter'])
            ->latest()
            ->paginate(10);

        return view('locations.reports', compact('location', 'reports'));
    }
}
