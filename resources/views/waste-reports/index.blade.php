@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">بلاغات النفايات</h2>
            @can('create', App\Models\WasteReport::class)
                <a href="{{ route('waste-reports.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    إضافة بلاغ جديد
                </a>
            @endcan
        </div>

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
                        <p class="text-gray-500">لا توجد بلاغات حالياً</p>
                        @can('create', App\Models\WasteReport::class)
                            <a href="{{ route('waste-reports.create') }}" class="text-green-600 hover:text-green-700 mt-2 inline-block">
                                إضافة أول بلاغ
                            </a>
                        @endcan
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        نوع النفايات
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        الكمية
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        الموقع
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        الحالة
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        المبلغ
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        التاريخ
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        الإجراءات
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($wasteReports as $report)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $report->wasteType ? $report->wasteType->name : 'غير محدد' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $report->quantity }} {{ $report->unit }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $report->location ? $report->location->name : 'غير محدد' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($report->status === 'completed') bg-green-100 text-green-800
                                                @elseif($report->status === 'in_progress') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $report->status === 'pending' ? 'قيد الانتظار' : 
                                                   ($report->status === 'in_progress' ? 'قيد المعالجة' : 'مكتمل') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $report->user ? $report->user->name : 'غير معروف' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" dir="ltr">
                                            {{ $report->created_at->format('Y/m/d') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex gap-2">
                                                <a href="{{ route('waste-reports.show', $report) }}" class="text-green-600 hover:text-green-900">
                                                    عرض
                                                </a>
                                                @can('update', $report)
                                                    <a href="{{ route('waste-reports.edit', $report) }}" class="text-blue-600 hover:text-blue-900">
                                                        تعديل
                                                    </a>
                                                @endcan
                                                @can('delete', $report)
                                                    <form action="{{ route('waste-reports.destroy', $report) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" 
                                                                onclick="return confirm('هل أنت متأكد من حذف هذا البلاغ؟')">
                                                            حذف
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
@endsection
