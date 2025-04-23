<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Waste Reports') }}
            </h2>
            @can('create', App\Models\WasteReport::class)
                <a href="{{ route('waste-reports.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('New Report') }}
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($wasteReports->isEmpty())
                        <div class="text-center py-8">
                            <i class="fas fa-clipboard text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-500">No waste reports found.</p>
                            @can('create', App\Models\WasteReport::class)
                                <a href="{{ route('waste-reports.create') }}" class="text-green-600 hover:text-green-700 mt-2 inline-block">
                                    Create your first report
                                </a>
                            @endcan
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Site
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Type
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Reporter
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Assigned To
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($wasteReports as $report)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $report->site->name }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ ucfirst($report->waste_type) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($report->status === 'completed') bg-green-100 text-green-800
                                                    @elseif($report->status === 'in_progress') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst($report->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $report->user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $report->worker ? $report->worker->name : 'Unassigned' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $report->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('waste-reports.show', $report) }}" class="text-green-600 hover:text-green-900">
                                                        View
                                                    </a>
                                                    @can('update', $report)
                                                        <a href="{{ route('waste-reports.edit', $report) }}" class="text-blue-600 hover:text-blue-900">
                                                            Edit
                                                        </a>
                                                    @endcan
                                                    @can('delete', $report)
                                                        <form action="{{ route('waste-reports.destroy', $report) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this report?')">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $wasteReports->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
