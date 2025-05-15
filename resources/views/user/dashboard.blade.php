@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
  <!-- Header Section -->
  <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center">
    <div>
      <h1 class="text-3xl md:text-4xl font-bold text-green-600">لوحة تحكم المستخدم</h1>
      <p class="text-gray-600 mt-2">مرحبًا {{ Auth::user()->name }} 👋</p>
    </div>
    <div class="mt-4 md:mt-0">
      <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md">
        بلاغ جديد
      </button>
    </div>
  </div>

  <!-- Stats Cards with Hover Effects -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 text-center border-t-4 border-green-500">
      <h2 class="text-gray-500 mb-2">إجمالي بلاغاتي</h2>
      <p class="text-4xl font-bold text-green-600">12</p>
      <div class="mt-4 h-1 bg-gradient-to-r from-green-400 to-green-200 rounded-full"></div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 text-center border-t-4 border-yellow-500">
      <h2 class="text-gray-500 mb-2">قيد المعالجة</h2>
      <p class="text-4xl font-bold text-yellow-500">3</p>
      <div class="mt-4 h-1 bg-gradient-to-r from-yellow-400 to-yellow-200 rounded-full"></div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 text-center border-t-4 border-blue-500">
      <h2 class="text-gray-500 mb-2">مكتمل</h2>
      <p class="text-4xl font-bold text-blue-500">9</p>
      <div class="mt-4 h-1 bg-gradient-to-r from-blue-400 to-blue-200 rounded-full"></div>
    </div>
  </div>

  <!-- Map Section with Modern Card -->
  <div class="p-6 bg-white rounded-xl shadow-md mb-8 transition-all duration-300 hover:shadow-lg">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-2xl font-bold text-green-600">خريطة بلاغاتي</h2>
      <button class="text-green-600 hover:text-green-800 transition-colors duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
        </svg>
      </button>
    </div>
    <div id="map" class="h-80 rounded-lg border border-gray-200"></div>
  </div>

  <!-- Waste Truck Schedule Table with Modern Styling -->
  <div class="p-6 bg-white rounded-xl shadow-md transition-all duration-300 hover:shadow-lg">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-2xl font-bold text-green-600">مواعيد مرور شاحنات النفايات</h2>
      <button class="text-green-600 hover:text-green-800 transition-colors duration-200 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
        تصدير
      </button>
    </div>
    
    <div class="overflow-x-auto">
      <table class="w-full text-right rounded-lg overflow-hidden">
        <thead>
          <tr class="bg-gradient-to-r from-green-500 to-green-600 text-white">
            <th class="p-3 font-medium">الموقع</th>
            <th class="p-3 font-medium">اليوم</th>
            <th class="p-3 font-medium">الساعة</th>
            <th class="p-3 font-medium">الحالة</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr class="hover:bg-gray-50 transition-colors duration-150">
            <td class="p-3">حي النور</td>
            <td class="p-3">الإثنين</td>
            <td class="p-3">09:00 صباحًا</td>
            <td class="p-3">
              <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">نشط</span>
            </td>
          </tr>
          <tr class="hover:bg-gray-50 transition-colors duration-150">
            <td class="p-3">حي الهدى</td>
            <td class="p-3">الخميس</td>
            <td class="p-3">11:00 صباحًا</td>
            <td class="p-3">
              <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">قريبًا</span>
            </td>
          </tr>
          <tr class="hover:bg-gray-50 transition-colors duration-150">
            <td class="p-3">حي السلام</td>
            <td class="p-3">السبت</td>
            <td class="p-3">03:00 مساءً</td>
            <td class="p-3">
              <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">جديد</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Professional Alert Notification (Example) -->
<div id="successAlert" class="fixed top-4 right-4 w-80 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-lg transform transition-all duration-500 opacity-0 translate-x-full">
  <div class="flex items-start">
    <div class="flex-shrink-0">
      <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
      </svg>
    </div>
    <div class="ml-3">
      <h3 class="text-sm font-medium">تم بنجاح!</h3>
      <div class="mt-1 text-sm text-green-600">
        تم تسجيل بلاغك بنجاح وسيتم معالجته قريبًا.
      </div>
    </div>
    <button onclick="hideAlert('successAlert')" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-100 inline-flex h-8 w-8">
      <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
      </svg>
    </button>
  </div>
</div>

@push('scripts')
<script>
  // Initialize map
  var map = L.map('map').setView([35.6895, 10.0979], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
  }).addTo(map);

  // User reports with custom icons
  var greenIcon = L.icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
  });

  var redIcon = L.icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
  });

  L.marker([35.6895, 10.0979], {icon: greenIcon}).addTo(map)
    .bindPopup('<div class="font-bold text-green-600">بلاغك الأول</div><div class="text-sm">تم معالجته بنجاح</div>')
    .openPopup();

  L.marker([35.6910, 10.0930], {icon: redIcon}).addTo(map)
    .bindPopup('<div class="font-bold text-red-600">بلاغ آخر</div><div class="text-sm">قيد المعالجة</div>');

  // Show alert notification (example - you would trigger this after an action)
  setTimeout(() => {
    showAlert('successAlert');
  }, 1000);

  function showAlert(id) {
    const alert = document.getElementById(id);
    alert.classList.remove('opacity-0', 'translate-x-full');
    alert.classList.add('opacity-100', 'translate-x-0');
    
    // Auto hide after 5 seconds
    setTimeout(() => {
      hideAlert(id);
    }, 5000);
  }

  function hideAlert(id) {
    const alert = document.getElementById(id);
    alert.classList.remove('opacity-100', 'translate-x-0');
    alert.classList.add('opacity-0', 'translate-x-full');
  }
</script>

<style>
  /* Custom scrollbar for table */
  .overflow-x-auto::-webkit-scrollbar {
    height: 8px;
  }
  .overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
  }
  .overflow-x-auto::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
  }
  .overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
  }
</style>
@endpush

@endsection