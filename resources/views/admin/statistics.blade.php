@extends('layouts.app')

@section('content')
<div class="mb-8">
  <h1 class="text-3xl font-bold text-green-700">إحصائيات الأداء</h1>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
  <!-- Total Reports -->
  <div class="p-4 bg-white rounded-2xl shadow">
    <h2 class="text-xl font-semibold text-gray-700">إجمالي البلاغات</h2>
    <p class="text-2xl font-bold text-green-600">{{ $totalReports }}</p>
  </div>

  <!-- Pending Reports -->
  <div class="p-4 bg-white rounded-2xl shadow">
    <h2 class="text-xl font-semibold text-gray-700">البلاغات قيد المعالجة</h2>
    <p class="text-2xl font-bold text-yellow-500">{{ $pendingReports }}</p>
  </div>

  <!-- Completed Reports -->
  <div class="p-4 bg-white rounded-2xl shadow">
    <h2 class="text-xl font-semibold text-gray-700">البلاغات المكتملة</h2>
    <p class="text-2xl font-bold text-green-600">{{ $completedReports }}</p>
  </div>
</div>

<!-- Statistics Table for Garbage Trucks -->
<div class="mt-6 p-4 bg-white rounded-2xl shadow">
  <h2 class="text-2xl font-semibold text-gray-700 mb-4">جدول مواعيد الحافلات</h2>
  <table class="w-full text-right border">
    <thead>
      <tr class="bg-gray-200">
        <th class="p-2">التاريخ</th>
        <th class="p-2">الوقت</th>
        <th class="p-2">المسار</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($schedules as $schedule)
        <tr>
          <td class="p-2">{{ $schedule->date }}</td>
          <td class="p-2">{{ $schedule->time }}</td>
          <td class="p-2">{{ $schedule->route }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
