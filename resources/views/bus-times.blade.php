<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bus Times') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-3xl font-bold text-green-700 mb-8">🚌 Local Bus Schedule</h1>

                <div class="space-y-8">
                    <!-- Morning Routes -->
                    <section>
                        <h3 class="text-xl font-semibold text-green-600 mb-4">Morning Routes</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left">Route</th>
                                        <th class="px-4 py-2 text-left">Departure</th>
                                        <th class="px-4 py-2 text-left">Arrival</th>
                                        <th class="px-4 py-2 text-left">Frequency</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-4 py-2">Route 1</td>
                                        <td class="px-4 py-2">6:00 AM</td>
                                        <td class="px-4 py-2">7:30 AM</td>
                                        <td class="px-4 py-2">Every 15 min</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2">Route 2</td>
                                        <td class="px-4 py-2">6:30 AM</td>
                                        <td class="px-4 py-2">8:00 AM</td>
                                        <td class="px-4 py-2">Every 20 min</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <!-- Afternoon Routes -->
                    <section>
                        <h3 class="text-xl font-semibold text-green-600 mb-4">Afternoon Routes</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left">Route</th>
                                        <th class="px-4 py-2 text-left">Departure</th>
                                        <th class="px-4 py-2 text-left">Arrival</th>
                                        <th class="px-4 py-2 text-left">Frequency</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-4 py-2">Route 1</td>
                                        <td class="px-4 py-2">12:00 PM</td>
                                        <td class="px-4 py-2">1:30 PM</td>
                                        <td class="px-4 py-2">Every 15 min</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2">Route 2</td>
                                        <td class="px-4 py-2">12:30 PM</td>
                                        <td class="px-4 py-2">2:00 PM</td>
                                        <td class="px-4 py-2">Every 20 min</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <!-- Evening Routes -->
                    <section>
                        <h3 class="text-xl font-semibold text-green-600 mb-4">Evening Routes</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left">Route</th>
                                        <th class="px-4 py-2 text-left">Departure</th>
                                        <th class="px-4 py-2 text-left">Arrival</th>
                                        <th class="px-4 py-2 text-left">Frequency</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-4 py-2">Route 1</td>
                                        <td class="px-4 py-2">5:00 PM</td>
                                        <td class="px-4 py-2">6:30 PM</td>
                                        <td class="px-4 py-2">Every 15 min</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2">Route 2</td>
                                        <td class="px-4 py-2">5:30 PM</td>
                                        <td class="px-4 py-2">7:00 PM</td>
                                        <td class="px-4 py-2">Every 20 min</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <div class="mt-8 p-4 bg-green-50 rounded-lg">
                        <p class="text-green-800">
                            <strong>Note:</strong> Schedule may vary on weekends and holidays. Please check with local transit authority for real-time updates.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
