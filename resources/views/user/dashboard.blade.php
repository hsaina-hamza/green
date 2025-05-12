@extends('layouts.app')

@section('content')
<div class="mb-8">
  <h1 class="text-3xl font-bold text-green-700">لوحة تحكم المستخدم</h1>
  <p class="text-gray-500">مرحبًا {{ Auth::user()->name }} 👋</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
  <div class="bg-white p-6 rounded-2xl shadow text-center">
    <h2 class="text-gray-500">إجمالي بلاغاتي</h2>
    <p class="text-4xl font-bold text-green-700">12</p>
  </div>
  <div class="bg-white p-6 rounded-2xl shadow text-center">
    <h2 class="text-gray-500">قيد المعالجة</h2>
    <p class="text-4xl font-bold text-yellow-600">3</p>
  </div>
  <div class="bg-white p-6 rounded-2xl shadow text-center">
    <h2 class="text-gray-500">مكتمل</h2>
    <p class="text-4xl font-bold text-blue-600">9</p>
  </div>
</div>

<!-- Map Section -->
<div class="p-4 bg-white rounded-2xl shadow mb-8">
  <h2 class="text-2xl font-bold mb-4 text-green-700">خريطة بلاغاتي</h2>
  <div id="map" class="h-72 rounded-lg"></div>
</div>

<!-- Waste Truck Schedule Table -->
<div class="p-4 bg-white rounded-2xl shadow">
  <h2 class="text-2xl font-bold mb-4 text-green-700">مواعيد مرور شاحنات النفايات</h2>
  <table class="w-full text-right border">
    <thead>
      <tr class="bg-gray-200">
        <th class="p-2">الموقع</th>
        <th class="p-2">اليوم</th>
        <th class="p-2">الساعة</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="p-2">حي النور</td>
        <td class="p-2">الإثنين</td>
        <td class="p-2">09:00 صباحًا</td>
      </tr>
      <tr>
        <td class="p-2">حي الهدى</td>
        <td class="p-2">الخميس</td>
        <td class="p-2">11:00 صباحًا</td>
      </tr>
    </tbody>
  </table>
</div>

@push('scripts')
<script>
  var map = L.map('map').setView([35.6895, 10.0979], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
  }).addTo(map);

  // بلاغات المستخدم
  L.marker([35.6895, 10.0979]).addTo(map)
    .bindPopup('بلاغك الأول').openPopup();

  L.marker([35.6910, 10.0930]).addTo(map)
    .bindPopup('بلاغ آخر');
</script>
@endpush

@endsection
