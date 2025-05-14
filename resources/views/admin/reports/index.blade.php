<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-trash-alt mr-2 text-green-500"></i>
                            إدارة تقارير النفايات
                        </h2>
                        <a href="{{ route('admin.reports.create') }}" class="flex items-center bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                            <i class="fas fa-plus-circle mr-2"></i>
                            تقرير جديد
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto shadow-md rounded-lg">
                        <table class="min-w-full table-auto divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الموقع</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع النفايات</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الإنشاء</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($reports as $report)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $report->getKey() }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $report->reporter->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $report->location->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $report->wasteType->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $report->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $report->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $report->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}">
                                                @if($report->status === 'pending')
                                                    <i class="fas fa-clock mr-1"></i>
                                                @elseif($report->status === 'in_progress')
                                                    <i class="fas fa-spinner mr-1"></i>
                                                @else
                                                    <i class="fas fa-check mr-1"></i>
                                                @endif
                                                {{ $report->status === 'pending' ? 'قيد الانتظار' : ($report->status === 'in_progress' ? 'قيد المعالجة' : 'تم الحل') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $report->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex justify-end space-x-2 space-x-reverse">
                                                <a href="{{ route('admin.reports.edit', $report) }}" 
                                                   class="flex items-center bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded text-sm transition duration-300">
                                                    <i class="fas fa-edit mr-1"></i>
                                                    تعديل
                                                </a>
                                                <form action="{{ route('admin.reports.destroy', $report) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="flex items-center bg-red-600 hover:bg-red-700 text-white py-1 px-3 rounded text-sm transition duration-300"
                                                            onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذا التقرير؟')">
                                                        <i class="fas fa-trash-alt mr-1"></i>
                                                        حذف
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-center">
                        {{ $reports->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table-auto {
            width: 100%;
        }
        .table-auto th, .table-auto td {
            padding: 12px 15px;
            text-align: right;
        }
        .table-auto thead th {
            background-color: #f9fafb;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e5e7eb;
        }
        .table-auto tbody tr:not(:last-child) {
            border-bottom: 1px solid #e5e7eb;
        }
        .table-auto tbody tr:hover {
            background-color: #f9fafb;
        }
    </style>
</x-app-layout>