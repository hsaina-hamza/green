@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-green-700">لوحة تحكم المشرف</h1>
                <p class="text-gray-600">مرحباً {{ Auth::user()->name }} 👋</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('admin.reports.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    <i class="fas fa-plus-circle ml-1"></i>
                    بلاغ جديد
                </a>
                <a href="{{ route('admin.schedules.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-calendar-plus ml-1"></i>
                    جدولة مهمة
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">إجمالي البلاغات</p>
                    <h2 class="text-4xl font-bold text-green-700">{{ $totalReports }}</h2>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-clipboard-list text-2xl text-green-700"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">البلاغات المعلقة</p>
                    <h2 class="text-4xl font-bold text-yellow-600">{{ $pendingReports }}</h2>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-clock text-2xl text-yellow-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">البلاغات المكتملة</p>
                    <h2 class="text-4xl font-bold text-blue-600">{{ $completedReports }}</h2>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">المهام النشطة</p>
                    <h2 class="text-4xl font-bold text-purple-600">{{ $activeSchedules }}</h2>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-tasks text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Tables Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Monthly Statistics Chart -->
        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <h2 class="text-xl font-bold text-gray-800 mb-4">إحصائيات شهرية</h2>
            <canvas id="monthlyStats" class="w-full h-64"></canvas>
        </div>

        <!-- Active Schedules -->
        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">المهام النشطة</h2>
                <a href="{{ route('admin.schedules.index') }}" class="text-blue-600 hover:text-blue-800">
                    عرض الكل
                    <i class="fas fa-arrow-left mr-1"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-right">الموظف</th>
                            <th class="px-4 py-2 text-right">المركبة</th>
                            <th class="px-4 py-2 text-right">التاريخ</th>
                            <th class="px-4 py-2 text-right">الحالة</th>
                            <th class="px-4 py-2 text-right">تحكم</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($schedules as $schedule)
                            <tr>
                                <td class="px-4 py-2">{{ $schedule->assignedEmployee->name }}</td>
                                <td class="px-4 py-2">{{ $schedule->truck_identifier }}</td>
                                <td class="px-4 py-2">{{ $schedule->schedule_date->format('Y-m-d') }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded-full text-sm {{ 
                                        $schedule->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                        ($schedule->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                        'bg-blue-100 text-blue-800') 
                                    }}">
                                        {{ $schedule->status === 'scheduled' ? 'مجدولة' : 
                                           ($schedule->status === 'in_progress' ? 'قيد التنفيذ' : 'مكتملة') }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('admin.schedules.edit', $schedule->id) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                                    لا توجد مهام نشطة حالياً
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="bg-white p-6 rounded-2xl shadow-lg">
        <h2 class="text-xl font-bold text-gray-800 mb-4">خريطة البلاغات</h2>
        <div id="map" class="h-96 rounded-lg"></div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Statistics Chart
    const monthlyStats = @json($monthlyStats);
    const months = ['يناير', 'فبراير', 'مارس', 'إبريل', 'مايو', 'يونيو', 
                   'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
    
    new Chart(document.getElementById('monthlyStats'), {
        type: 'bar',
        data: {
            labels: monthlyStats.map(stat => months[stat.month - 1]),
            datasets: [{
                label: 'عدد البلاغات',
                data: monthlyStats.map(stat => stat.total),
                backgroundColor: '#10B981',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Map
    var map = L.map('map').setView([35.6895, 10.0979], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Add markers for waste reports
    @foreach($wasteReports as $report)
        L.marker([{{ $report->latitude }}, {{ $report->longitude }}])
            .addTo(map)
            .bindPopup(`
                <div class="text-right">
                    <strong>{{ $report->location }}</strong><br>
                    نوع النفايات: {{ $report->wasteType->name }}<br>
                    الحالة: {{ $report->status === 'pending' ? 'معلق' : 
                              ($report->status === 'in_progress' ? 'قيد المعالجة' : 'مكتمل') }}<br>
                    <a href="{{ route('admin.reports.show', $report->id) }}" class="text-blue-600 hover:underline">
                        عرض التفاصيل
                    </a>
                </div>
            `);
    @endforeach
</script>
@endpush
@endsection
