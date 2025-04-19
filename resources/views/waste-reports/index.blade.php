<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Waste Reports') }}
            </h2>
            @auth
                <a href="{{ route('waste-reports.create') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Report Waste') }}
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($wasteReports->isEmpty())
                        <p class="text-gray-600 text-center py-4">No waste reports found.</p>
                    @else
                        <div class="space-y-6">
                            @foreach($wasteReports as $report)
                                <div class="border-b border-gray-200 pb-6 last:border-0 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">
                                                <a href="{{ route('waste-reports.show', $report) }}" class="hover:text-green-600">
                                                    {{ $report->title }}
                                                </a>
                                            </h3>
                                            <p class="text-sm text-gray-600 mt-1">
                                                Site: {{ $report->site->name }}
                                            </p>
                                            <div class="flex items-center mt-2 space-x-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    {{ $report->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                       ($report->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                                        'bg-green-100 text-green-800') }}">
                                                    {{ ucfirst($report->status) }}
                                                </span>
                                                <span class="text-sm text-gray-500">
                                                    Type: {{ ucfirst($report->type) }}
                                                </span>
                                                <span class="text-sm text-gray-500">
                                                    Urgency: {{ ucfirst($report->urgency_level) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $report->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <p class="text-gray-600 line-clamp-2">{{ $report->description }}</p>
                                    </div>
                                    @if($report->worker)
                                        <div class="mt-4 text-sm text-gray-600">
                                            Assigned to: {{ $report->worker->name }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $wasteReports->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
