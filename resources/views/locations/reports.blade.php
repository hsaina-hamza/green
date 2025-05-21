<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>بلاغات {{ $location->name }} - المغرب الأخضر</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    @include('layouts.navigation')

    <!-- Page Content -->
    <main>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Page Header -->
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">بلاغات {{ $location->name }}</h1>
                            <p class="text-gray-600 mt-1">{{ $location->address }}</p>
                        </div>
                        <a href="{{ route('waste-map') }}" class="text-sm bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-arrow-right ml-2"></i>
                            العودة للخريطة
                        </a>
                    </div>
                </div>

                <!-- Reports List -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        @if($reports->isEmpty())
                            <div class="text-center py-8">
                                <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                                <p class="text-gray-500">لا توجد بلاغات لهذا الموقع حالياً</p>
                            </div>
                        @else
                            <div class="space-y-6">
                                @foreach($reports as $report)
                                    <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h3 class="font-semibold text-lg text-gray-800">
                                                    {{ $report->wasteType->name }}
                                                </h3>
                                                <p class="text-gray-600 mt-1">
                                                    الكمية: {{ $report->quantity }} {{ $report->unit }}
                                                </p>
                                                <p class="text-gray-500 text-sm mt-2">
                                                    <i class="fas fa-user ml-1"></i>
                                                    {{ $report->reporter->name }}
                                                    <span class="mx-2">•</span>
                                                    <i class="fas fa-calendar ml-1"></i>
                                                    {{ $report->created_at->format('Y-m-d') }}
                                                </p>
                                            </div>
                                            <div>
                                                @switch($report->status)
                                                    @case('new')
                                                        <span class="bg-red-100 text-red-800 text-sm px-3 py-1 rounded-full">
                                                            جديد
                                                        </span>
                                                        @break
                                                    @case('in_progress')
                                                        <span class="bg-yellow-100 text-yellow-800 text-sm px-3 py-1 rounded-full">
                                                            قيد المعالجة
                                                        </span>
                                                        @break
                                                    @case('cleaned')
                                                        <span class="bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full">
                                                            تم التنظيف
                                                        </span>
                                                        @break
                                                @endswitch
                                            </div>
                                        </div>
                                        @if($report->description)
                                            <p class="text-gray-600 mt-3 text-sm">
                                                {{ $report->description }}
                                            </p>
                                        @endif
                                        <div class="mt-4 text-left">
                                            <a href="{{ route('waste-reports.show', $report->id) }}" 
                                               class="text-sm bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded inline-block">
                                                <i class="fas fa-eye ml-1"></i>
                                                عرض التفاصيل
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="mt-6">
                                {{ $reports->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
