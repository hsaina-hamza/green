@extends('layouts.app')

@section('content')
<div class="mb-8">
  <h1 class="text-3xl font-bold text-green-700">تعديل بلاغ النفايات</h1>
</div>

<form action="{{ route('worker.reports.update', $wasteReport->id) }}" method="POST">
  @csrf
  @method('PUT')

  <!-- نوع النفايات -->
  <div class="mb-4">
    <label for="type" class="block text-gray-700">نوع النفايات</label>
    <input type="text" id="type" name="type" value="{{ old('type', $wasteReport->type) }}" class="w-full p-3 border rounded" required>
  </div>

  <!-- الموقع -->
  <div class="mb-4">
    <label for="location" class="block text-gray-700">الموقع</label>
    <input type="text" id="location" name="location" value="{{ old('location', $wasteReport->location) }}" class="w-full p-3 border rounded" required>
  </div>

  <!-- صورة البلاغ -->
  <div class="mb-4">
    <label for="image" class="block text-gray-700">الصورة</label>
    <input type="file" id="image" name="image" class="w-full p-3 border rounded">
    @if($wasteReport->image)
      <div class="mt-2">
        <img src="{{ asset('storage/' . $wasteReport->image) }}" alt="Waste Image" class="w-32 h-32 object-cover rounded">
      </div>
    @endif
  </div>

  <!-- تعليق -->
  <div class="mb-4">
    <label for="comment" class="block text-gray-700">التعليق</label>
    <textarea id="comment" name="comment" class="w-full p-3 border rounded">{{ old('comment', $wasteReport->comment) }}</textarea>
  </div>

  <!-- الحالة -->
  <div class="mb-4">
    <label for="status" class="block text-gray-700">الحالة</label>
    <select id="status" name="status" class="w-full p-3 border rounded">
      <option value="pending" {{ $wasteReport->status == 'pending' ? 'selected' : '' }}>قيد المعالجة</option>
      <option value="in_progress" {{ $wasteReport->status == 'in_progress' ? 'selected' : '' }}>جارِ التنفيذ</option>
      <option value="completed" {{ $wasteReport->status == 'completed' ? 'selected' : '' }}>مكتمل</option>
    </select>
  </div>

  <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">تحديث البلاغ</button>
</form>
@endsection
