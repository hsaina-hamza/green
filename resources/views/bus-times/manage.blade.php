<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <i class="fas fa-bus mr-2 text-blue-500"></i>
                {{ __('إدارة مواعيد الحافلات') }}
            </h2>
            <div class="flex space-x-4 space-x-reverse">
                <a href="{{ route('bus-times.create') }}" class="flex items-center bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg">
                    <i class="fas fa-plus ml-2"></i>
                    {{ __('إضافة جدول جديد') }}
                </a>
                <a href="{{ route('bus-times.index') }}" class="flex items-center bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg">
                    <i class="fas fa-eye ml-2"></i>
                    {{ __('عرض الجدول للعامة') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center" role="alert">
                    <i class="fas fa-check-circle ml-2"></i>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @forelse($schedules as $neighborhood => $neighborhoodSchedules)
                        <div class="mb-10">
                            <h3 class="text-lg font-semibold mb-4 bg-gray-100 p-3 rounded-lg flex items-center">
                                <i class="fas fa-map-marker-alt ml-2 text-green-500"></i>
                                {{ $neighborhood }}
                            </h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <i class="fas fa-route ml-2"></i>
                                                المسار
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <i class="fas fa-clock ml-2"></i>
                                                وقت المغادرة
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <i class="fas fa-clock ml-2"></i>
                                                وقت الوصول
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <i class="fas fa-sync-alt ml-2"></i>
                                                التكرار
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <i class="fas fa-sticky-note ml-2"></i>
                                                ملاحظات
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <i class="fas fa-info-circle ml-2"></i>
                                                الحالة
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <i class="fas fa-cog ml-2"></i>
                                                الإجراءات
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($neighborhoodSchedules as $schedule)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $schedule->route_name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $schedule->formatted_departure_time }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $schedule->formatted_arrival_time }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    @if($schedule->frequency_label == 'Daily')
                                                        يومي
                                                    @elseif($schedule->frequency_label == 'Weekly')
                                                        أسبوعي
                                                    @elseif($schedule->frequency_label == 'Monthly')
                                                        شهري
                                                    @else
                                                        {{ $schedule->frequency_label }}
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    {{ $schedule->notes }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $schedule->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $schedule->is_active ? 'نشط' : 'غير نشط' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex justify-end space-x-4 space-x-reverse">
                                                        <a href="{{ route('bus-times.edit', $schedule) }}" 
                                                           class="text-blue-600 hover:text-blue-800 flex items-center">
                                                            <i class="fas fa-edit ml-2"></i>
                                                            تعديل
                                                        </a>
                                                        <form action="{{ route('bus-times.toggle-status', $schedule) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="text-yellow-600 hover:text-yellow-800 flex items-center">
                                                                <i class="fas fa-power-off ml-2"></i>
                                                                {{ $schedule->is_active ? 'تعطيل' : 'تفعيل' }}
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('bus-times.destroy', $schedule) }}" method="POST" class="inline"
                                                              onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا الجدول؟');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-800 flex items-center">
                                                                <i class="fas fa-trash-alt ml-2"></i>
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
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="bg-gray-50 p-8 rounded-lg">
                                <i class="fas fa-bus-slash text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500 mb-4">لا توجد جداول حافلات متاحة</p>
                                <a href="{{ route('bus-times.create') }}" 
                                   class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg">
                                    <i class="fas fa-plus ml-2"></i>
                                    إضافة جدول جديد
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    
    <style>
        [dir="rtl"] table {
            direction: rtl;
        }
        [dir="rtl"] th, [dir="rtl"] td {
            text-align: right;
        }
    </style>
</x-app-layout>