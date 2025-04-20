<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Overall Statistics</h3>
                        <div class="space-y-2">
                            <p class="text-sm text-gray-600">Total Reports: <span class="font-semibold">{{ $stats['total_reports'] }}</span></p>
                            <p class="text-sm text-gray-600">Pending Reports: <span class="font-semibold">{{ $stats['pending_reports'] }}</span></p>
                            <p class="text-sm text-gray-600">In Progress: <span class="font-semibold">{{ $stats['in_progress_reports'] }}</span></p>
                            <p class="text-sm text-gray-600">Completed: <span class="font-semibold">{{ $stats['completed_reports'] }}</span></p>
                            <p class="text-sm text-gray-600">Total Sites: <span class="font-semibold">{{ $stats['total_sites'] }}</span></p>
                            <p class="text-sm text-gray-600">Upcoming Schedules: <span class="font-semibold">{{ $stats['upcoming_schedules'] }}</span></p>
                        </div>
                    </div>
                </div>

                @if(Auth::user()->isWorker())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Worker Statistics</h3>
                        <div class="space-y-2">
                            <p class="text-sm text-gray-600">Assigned Reports: <span class="font-semibold">{{ $stats['my_assigned_reports'] }}</span></p>
                        </div>
                    </div>
                </div>
                @endif

                @if(Auth::user()->isUser())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">My Reports</h3>
                        <div class="space-y-2">
                            <p class="text-sm text-gray-600">Total Reports: <span class="font-semibold">{{ $stats['my_reports'] }}</span></p>
                            <p class="text-sm text-gray-600">Pending Reports: <span class="font-semibold">{{ $stats['my_pending_reports'] }}</span></p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Recent Reports -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Reports</h3>
                    @if($recentReports->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentReports as $report)
                                <div class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $report->title }}</h4>
                                            <p class="text-sm text-gray-600">Site: {{ $report->site->name }}</p>
                                            <p class="text-sm text-gray-600">Status: <span class="font-medium">{{ ucfirst($report->status) }}</span></p>
                                        </div>
                                        <a href="{{ route('waste-reports.show', $report) }}" class="text-sm text-green-600 hover:text-green-700">View Details</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600">No recent reports found.</p>
                    @endif
                </div>
            </div>

            <!-- Upcoming Schedules -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Upcoming Schedules</h3>
                    @if($upcomingSchedules->count() > 0)
                        <div class="space-y-4">
                            @foreach($upcomingSchedules as $schedule)
                                <div class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $schedule->site->name }}</h4>
                                            <p class="text-sm text-gray-600">Date: {{ $schedule->scheduled_date->format('M d, Y') }}</p>
                                            <p class="text-sm text-gray-600">Time: {{ $schedule->scheduled_date->format('g:i A') }}</p>
                                        </div>
                                        <a href="{{ route('schedules.show', $schedule) }}" class="text-sm text-green-600 hover:text-green-700">View Details</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600">No upcoming schedules found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
