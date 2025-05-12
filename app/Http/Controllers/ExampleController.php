<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExampleController extends Controller
{
    /**
     * Show the example page with role-based components.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('examples.role-components');
    }

    /**
     * Handle the form submission from the example page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|integer',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048'
        ]);

        // Here you would typically store the data
        // This is just an example to demonstrate form handling

        $role = Auth::user()->role;
        $message = match($role) {
            'admin' => 'Example data stored with admin privileges',
            'worker' => 'Example data stored with worker privileges',
            'user' => 'Example data stored with user privileges',
            default => 'Example data stored successfully'
        };

        return back()->with('success', $message);
    }
}
