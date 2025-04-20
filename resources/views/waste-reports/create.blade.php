@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4 text-gray-700">Report a Waste Site</h2>

    <form action="{{ route('waste-reports.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Waste Type --}}
        <div class="mb-4">
            <label for="waste_type" class="block text-sm font-medium text-gray-700">Waste Type</label>
            <select name="waste_type" id="waste_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="plastic">Plastic</option>
                <option value="paper">Paper</option>
                <option value="glass">Glass</option>
                <option value="organic">Organic</option>
                <option value="other">Other</option>
            </select>
        </div>

        {{-- Severity --}}
        <div class="mb-4">
            <label for="severity" class="block text-sm font-medium text-gray-700">Severity</label>
            <select name="severity" id="severity" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>
        </div>

        {{-- Description --}}
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
        </div>

        {{-- Image --}}
        <div class="mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700">Image (Optional)</label>
            <input type="file" name="image" id="image" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>

        {{-- Submit Button --}}
        <div class="mt-6">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Submit Report
            </button>
        </div>
    </form>
</div>
@endsection
