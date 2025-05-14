@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">جداول الحافلات</h2>
            @can('create', App\Models\BusSchedule::class)
                <a href="{{ route('bus-schedules.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    إضافة جدول جديد
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
                @if($schedules->isEmpty())
                    <div class="text-center py-8">
                        <i class="fas fa-bus text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500">لا توجد جداول حافلات متاحة حالياً</p>
                        @can('create', App\Models\BusSchedule::class)
                            <a href="{{ route('bus-schedules.create') }}" class="text-green-600 hover:text-green-700 mt-2 inline-block">
                                إضافة أول جدول
                            </a>
                        @endcan
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        رقم الخط
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        المسار
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        أيام العمل
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        وقت البداية
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        وقت النهاية
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        الإجراءات
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($schedules as $schedule)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $schedule->route_number }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                {{ $schedule->route_name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ str_replace(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                                                    ['الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت', 'الأحد'],
                                                    $schedule->operating_days) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap" dir="ltr">
                                            <div class="text-sm text-gray-900">
                                                {{ $schedule->start_time }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap" dir="ltr">
                                            <div class="text-sm text-gray-900">
                                                {{ $schedule->end_time }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex gap-2">
                                                <a href="{{ route('bus-schedules.show', $schedule) }}" class="text-green-600 hover:text-green-900">
                                                    عرض
                                                </a>
                                                @can('update', $schedule)
                                                    <a href="{{ route('bus-schedules.edit', $schedule) }}" class="text-blue-600 hover:text-blue-900">
                                                        تعديل
                                                    </a>
                                                @endcan
                                                @can('delete', $schedule)
                                                    <form action="{{ route('bus-schedules.destroy', $schedule) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" 
                                                                onclick="return confirm('هل أنت متأكد من حذف هذا الجدول؟')">
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
                        {{ $schedules->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
