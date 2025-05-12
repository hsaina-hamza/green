<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Waste Reports Management</h2>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2">ID</th>
                                    <th class="px-4 py-2">Reporter</th>
                                    <th class="px-4 py-2">Location</th>
                                    <th class="px-4 py-2">Waste Type</th>
                                    <th class="px-4 py-2">Status</th>
                                    <th class="px-4 py-2">Created At</th>
                                    <th class="px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $report)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">#{{ $report->getKey() }}</td>
                                        <td class="px-4 py-2">{{ $report->reporter->name }}</td>
                                        <td class="px-4 py-2">{{ $report->location->name }}</td>
                                        <td class="px-4 py-2">{{ $report->wasteType->name }}</td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $report->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $report->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $report->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}">
                                                {{ ucfirst($report->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">{{ $report->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="px-4 py-2">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.reports.edit', $report) }}" 
                                                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.reports.destroy', $report) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm"
                                                            onclick="return confirm('Are you sure you want to delete this report?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $reports->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
