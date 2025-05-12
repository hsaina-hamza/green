<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationSelectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $locations = Location::whereNotNull('site_id')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();
        
        return view('waste-reports.select-location', compact('locations'));
    }
}
