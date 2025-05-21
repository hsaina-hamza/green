<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $site->name }}
            </h2>
            <div class="flex space-x-4 rtl:space-x-reverse">
                @can('update', $site)
                    <a href="{{ route('admin.sites.edit', $site) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                        {{ __('تعديل الموقع') }}
                    </a>
                @endcan
                <a href="{{ route('sites.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                    {{ __('العودة إلى القائمة') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Site Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4 text-blue-600 border-b pb-2">{{ __('معلومات الموقع') }}</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <dt class="text-sm font-medium text-gray-500">{{ __('العنوان') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $site->address }}</dd>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <dt class="text-sm font-medium text-gray-500">{{ __('الإحداثيات') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $site->latitude }}, {{ $site->longitude }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4 text-blue-600 border-b pb-2">{{ __('الإحصائيات') }}</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <dt class="text-sm font-medium text-gray-500">{{ __('إجمالي البلاغات') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $site->waste_reports_count ?? 0 }}</dd>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <dt class="text-sm font-medium text-gray-500">{{ __('المواعيد النشطة') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $site->garbage_schedules_count ?? 0 }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Waste Reports -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-blue-600">{{ __('أحدث بلاغات النفايات') }}</h3>
                        <a href="{{ route('waste-reports.create', ['site_id' => $site->id]) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 text-sm">
                            {{ __('إضافة بلاغ جديد') }}
                        </a>
                    </div>
                    @if($site->wasteReports->isEmpty())
                        <div class="bg-gray-50 rounded-lg p-8 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-500 mt-4">{{ __('لا توجد بلاغات نفايات لهذا الموقع.') }}</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('التاريخ') }}</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('النوع') }}</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('الحالة') }}</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('الإجراءات') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($site->wasteReports as $report)
                                        <tr class="hover:bg-gray-50 transition duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                                {{ $report->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                                {{ __('بلاغ ') }}{{ ucfirst($report->waste_type) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($report->status === 'completed') bg-green-100 text-green-800
                                                    @elseif($report->status === 'in_progress') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    @if($report->status === 'completed') {{ __('مكتمل') }}
                                                    @elseif($report->status === 'in_progress') {{ __('قيد المعالجة') }}
                                                    @else {{ __('معلق') }} @endif
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                                                <a href="{{ route('waste-reports.show', $report) }}" class="text-green-600 hover:text-green-900 ml-4">{{ __('عرض') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Upcoming Schedules -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-blue-600">{{ __('المواعيد القادمة') }}</h3>
                        @can('create', App\Models\GarbageSchedule::class)
                            <a href="{{ route('admin.schedules.create', ['site_id' => $site->id]) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 text-sm">
                                {{ __('إضافة موعد جديد') }}
                            </a>
                        @endcan
                    </div>
                    @if($site->garbageSchedules->isEmpty())
                        <div class="bg-gray-50 rounded-lg p-8 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-500 mt-4">{{ __('لا توجد مواعيد قادمة لهذا الموقع.') }}</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('التاريخ والوقت') }}</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('رقم الشاحنة') }}</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('الإجراءات') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($site->garbageSchedules as $schedule)
                                        <tr class="hover:bg-gray-50 transition duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="text-sm text-gray-900">{{ $schedule->scheduled_time->format('M d, Y') }}</div>
                                                <div class="text-sm text-gray-500">{{ $schedule->scheduled_time->format('H:i') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                                {{ $schedule->truck_number }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                                                <a href="{{ route('admin.schedules.show', $schedule) }}" class="text-green-600 hover:text-green-900 ml-4">{{ __('عرض') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>