<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('لوحة التحكم') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Overall Statistics -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">الإحصائيات العامة</h3>
                            <i class="fas fa-chart-pie text-green-500 text-xl"></i>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <p class="text-sm text-gray-600">إجمالي البلاغات:</p>
                                <span class="font-semibold bg-gray-100 px-3 py-1 rounded-full text-sm">{{ $stats['total_reports'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-sm text-gray-600">البلاغات المعلقة:</p>
                                <span class="font-semibold bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">{{ $stats['pending_reports'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-sm text-gray-600">قيد المعالجة:</p>
                                <span class="font-semibold bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">{{ $stats['in_progress_reports'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-sm text-gray-600">المكتملة:</p>
                                <span class="font-semibold bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">{{ $stats['completed_reports'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-sm text-gray-600">إجمالي المواقع:</p>
                                <span class="font-semibold bg-gray-100 px-3 py-1 rounded-full text-sm">{{ $stats['total_sites'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-sm text-gray-600">المواعيد القادمة:</p>
                                <span class="font-semibold bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">{{ $stats['upcoming_schedules'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if(Auth::user()->isWorker())
                <!-- Worker Statistics -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">إحصائيات العامل</h3>
                            <i class="fas fa-hard-hat text-blue-500 text-xl"></i>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <p class="text-sm text-gray-600">البلاغات المخصصة:</p>
                                <span class="font-semibold bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">{{ $stats['my_assigned_reports'] }}</span>
                            </div>
                            <div class="mt-6">
                                <a href="{{ route('worker.assigned-reports') }}" class="inline-flex items-center text-sm text-green-600 hover:text-green-700">
                                    <i class="fas fa-list-ul ml-2"></i>
                                    عرض جميع البلاغات المخصصة
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if(Auth::user()->isUser())
                <!-- User Reports -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">بلاغاتي</h3>
                            <i class="fas fa-user text-indigo-500 text-xl"></i>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <p class="text-sm text-gray-600">إجمالي البلاغات:</p>
                                <span class="font-semibold bg-gray-100 px-3 py-1 rounded-full text-sm">{{ $stats['my_reports'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-sm text-gray-600">البلاغات المعلقة:</p>
                                <span class="font-semibold bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">{{ $stats['my_pending_reports'] }}</span>
                            </div>
                            <div class="mt-6">
                                <a href="{{ route('waste-reports.create') }}" class="inline-flex items-center text-sm bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                                    <i class="fas fa-plus ml-2"></i>
                                    إنشاء بلاغ جديد
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Recent Reports -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">أحدث البلاغات</h3>
                        <i class="fas fa-clock text-orange-500"></i>
                    </div>
                    @if($recentReports->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($recentReports as $report)
                                <div class="py-4">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <h4 class="font-medium text-gray-900">{{ $report->title }}</h4>
                                                <span class="text-xs px-2 py-1 rounded-full 
                                                    @if($report->status == 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($report->status == 'in_progress') bg-blue-100 text-blue-800
                                                    @else bg-green-100 text-green-800
                                                    @endif">
                                                    {{ ucfirst($report->status) }}
                                                </span>
                                            </div>
                                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                                <i class="fas fa-map-marker-alt ml-2 text-green-500"></i>
                                                <span>{{ $report->site->name }}</span>
                                            </div>
                                            <div class="mt-1 text-xs text-gray-400">
                                                <i class="far fa-clock ml-2"></i>
                                                {{ $report->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                        <a href="{{ route('waste-reports.show', $report) }}" class="text-sm text-green-600 hover:text-green-700 flex items-center">
                                            <i class="fas fa-eye ml-2"></i>
                                            التفاصيل
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="far fa-folder-open text-4xl text-gray-300 mb-2"></i>
                            <p class="text-gray-600">لا توجد بلاغات حديثة</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Upcoming Schedules -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">المواعيد القادمة</h3>
                        <i class="fas fa-calendar-alt text-purple-500"></i>
                    </div>
                    @if($upcomingSchedules->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($upcomingSchedules as $schedule)
                                <div class="py-4">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $schedule->site->name }}</h4>
                                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                                <i class="far fa-calendar-alt ml-2 text-purple-500"></i>
                                                <span>{{ $schedule->scheduled_date ? $schedule->scheduled_date->format('Y-m-d') : 'غير محدد' }}</span>
                                            </div>
                                            <div class="mt-1 flex items-center text-sm text-gray-500">
                                                <i class="far fa-clock ml-2 text-purple-500"></i>
                                                <span>{{ $schedule->scheduled_date ? $schedule->scheduled_date->format('g:i A') : 'غير محدد' }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('schedules.show', $schedule) }}" class="text-sm text-green-600 hover:text-green-700 flex items-center">
                                            <i class="fas fa-eye ml-2"></i>
                                            التفاصيل
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="far fa-calendar-times text-4xl text-gray-300 mb-2"></i>
                            <p class="text-gray-600">لا توجد مواعيد قادمة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    
    <style>
        [dir="rtl"] .divide-y > :not([hidden]) ~ :not([hidden]) {
            border-top-width: 1px;
            border-bottom-width: 0;
        }
    </style>
</x-app-layout>