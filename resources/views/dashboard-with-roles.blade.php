<x-role-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Overall Stats -->
        <div @class([
            'p-6 rounded-lg shadow',
            'bg-purple-100 border-purple-200' => Auth::user()->role === 'admin',
            'bg-blue-100 border-blue-200' => Auth::user()->role === 'worker',
            'bg-green-100 border-green-200' => Auth::user()->role === 'user'
        ])>
            <h3 @class([
                'text-lg font-semibold mb-4',
                'text-purple-800' => Auth::user()->role === 'admin',
                'text-blue-800' => Auth::user()->role === 'worker',
                'text-green-800' => Auth::user()->role === 'user'
            ])>{{ __('Overall Statistics') }}</h3>
            <div class="space-y-2">
                <p class="text-sm text-gray-600">Total Reports: <span class="font-semibold">{{ $stats['total_reports'] }}</span></p>
                <p class="text-sm text-gray-600">Pending Reports: <span class="font-semibold">{{ $stats['pending_reports'] }}</span></p>
                <p class="text-sm text-gray-600">In Progress: <span class="font-semibold">{{ $stats['in_progress_reports'] }}</span></p>
                <p class="text-sm text-gray-600">Completed: <span class="font-semibold">{{ $stats['completed_reports'] }}</span></p>
                <p class="text-sm text-gray-600">Total Sites: <span class="font-semibold">{{ $stats['total_sites'] }}</span></p>
                <p class="text-sm text-gray-600">Upcoming Schedules: <span class="font-semibold">{{ $stats['upcoming_schedules'] }}</span></p>
            </div>
        </div>

        @if(Auth::user()->isWorker())
        <div class="bg-blue-100 border-blue-200 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-blue-800 mb-4">{{ __('Worker Statistics') }}</h3>
            <div class="space-y-2">
                <p class="text-sm text-gray-600">Assigned Reports: <span class="font-semibold">{{ $stats['my_assigned_reports'] }}</span></p>
            </div>
        </div>
        @endif

        @if(Auth::user()->isUser())
        <div class="bg-green-100 border-green-200 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-green-800 mb-4">{{ __('My Reports') }}</h3>
            <div class="space-y-2">
                <p class="text-sm text-gray-600">Total Reports: <span class="font-semibold">{{ $stats['my_reports'] }}</span></p>
                <p class="text-sm text-gray-600">Pending Reports: <span class="font-semibold">{{ $stats['my_pending_reports'] }}</span></p>
            </div>
        </div>
        @endif
    </div>

    <!-- Recent Reports -->
    <div class="mt-6">
        <h3 @class([
            'text-lg font-semibold mb-4',
            'text-purple-800' => Auth::user()->role === 'admin',
            'text-blue-800' => Auth::user()->role === 'worker',
            'text-green-800' => Auth::user()->role === 'user'
        ])>{{ __('Recent Reports') }}</h3>
        
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
                            <x-role-button href="{{ route('waste-reports.show', $report) }}" class="text-sm">
                                {{ __('View Details') }}
                            </x-role-button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">{{ __('No recent reports found.') }}</p>
        @endif
    </div>

    <!-- Upcoming Schedules -->
    <div class="mt-6">
        <h3 @class([
            'text-lg font-semibold mb-4',
            'text-purple-800' => Auth::user()->role === 'admin',
            'text-blue-800' => Auth::user()->role === 'worker',
            'text-green-800' => Auth::user()->role === 'user'
        ])>{{ __('Upcoming Schedules') }}</h3>
        
        @if($upcomingSchedules->count() > 0)
            <div class="space-y-4">
                @foreach($upcomingSchedules as $schedule)
                    <div class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $schedule->site->name }}</h4>
                                <p class="text-sm text-gray-600">Date: {{ $schedule->scheduled_date ? $schedule->scheduled_date->format('M d, Y') : 'Not scheduled' }}</p>
                                <p class="text-sm text-gray-600">Time: {{ $schedule->scheduled_date ? $schedule->scheduled_date->format('g:i A') : 'Not set' }}</p>
                            </div>
                            <x-role-button href="{{ route('schedules.show', $schedule) }}" class="text-sm">
                                {{ __('View Details') }}
                            </x-role-button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">{{ __('No upcoming schedules found.') }}</p>
        @endif
    </div>
</x-role-layout>
