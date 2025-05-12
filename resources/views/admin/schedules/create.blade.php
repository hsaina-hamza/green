@extends('layouts.app')

@section('content')
<div class="mb-8">
  <h1 class="text-3xl font-bold text-green-700">إضافة/تعديل موعد الحافلة</h1>
</div>

<form action="{{ route('admin.schedules.store') }}" method="POST">
  @csrf

  <!-- التاريخ -->
  <div class="mb-4">
    <label for="date" class="block text-gray-700">التاريخ</label>
    <input type="date" id="date" name="date" class="w-full p-3 border rounded" required>
  </div>

  <!-- الوقت -->
  <div class="mb-4">
    <label for="time" class="block text-gray-700">الوقت</label>
    <input type="time" id="time" name="time" class="w-full p-3 border rounded" required>
  </div>

  <!-- المسار -->
  <div class="mb-4">
    <label for="route" class="block text-gray-700">المسار</label>
    <input type="text" id="route" name="route" class="w-full p-3 border rounded" required>
  </div>

  <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">حفظ الموعد</button>
</form>
@endsection
