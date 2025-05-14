<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <i class="fas fa-bus ml-2 text-blue-500"></i>
                {{ __('مواعيد الحافلات') }}
            </h2>
            @auth
                @if(auth()->user()->hasAnyRole(['admin', 'worker']))
                    <a href="{{ route('bus-times.create') }}" class="flex items-center bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg">
                        <i class="fas fa-plus ml-2"></i>
                        {{ __('إضافة جدول جديد') }}
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center" role="alert">
                    <i class="fas fa-check-circle ml-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-map-marker-alt ml-2"></i>
                                        الموقع
                                    </th>
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
                                    @auth
                                        @if(auth()->user()->hasAnyRole(['admin', 'worker']))
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <i class="fas fa-cog ml-2"></i>
                                                الإجراءات
                                            </th>
                                        @endif
                                    @endauth
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($busSchedules as $schedule)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $schedule->location->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $schedule->route }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $schedule->departure_time }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $schedule->arrival_time }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($schedule->frequency == 'Daily')
                                                يومي
                                            @elseif($schedule->frequency == 'Weekly')
                                                أسبوعي
                                            @elseif($schedule->frequency == 'Monthly')
                                                شهري
                                            @else
                                                {{ $schedule->frequency }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $schedule->notes }}
                                        </td>
                                        @auth
                                            @if(auth()->user()->hasAnyRole(['admin', 'worker']))
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex justify-end space-x-4 space-x-reverse">
                                                        <a href="{{ route('bus-times.edit', $schedule) }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                                                            <i class="fas fa-edit ml-2"></i>
                                                            تعديل
                                                        </a>
                                                        <form action="{{ route('bus-times.destroy', $schedule) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-800 flex items-center" onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذا الجدول؟')">
                                                                <i class="fas fa-trash-alt ml-2"></i>
                                                                حذف
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            @endif
                                        @endauth
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            <div class="flex flex-col items-center justify-center py-8">
                                                <i class="fas fa-bus-slash text-4xl text-gray-300 mb-2"></i>
                                                لا توجد جداول حافلات متاحة
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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