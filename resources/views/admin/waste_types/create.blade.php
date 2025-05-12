@extends('layouts.app')

@section('content')
<div class="mb-8">
  <h1 class="text-3xl font-bold text-green-700">إضافة/تعديل نوع نفايات جديد</h1>
</div>

<form action="{{ route('admin.waste_types.store') }}" method="POST">
  @csrf

  <!-- الاسم -->
  <div class="mb-4">
    <label for="name" class="block text-gray-700">اسم النوع</label>
    <input type="text" id="name" name="name" class="w-full p-3 border rounded" value="{{ old('name') }}" required>
  </div>

  <!-- الوصف -->
  <div class="mb-4">
    <label for="description" class="block text-gray-700">الوصف</label>
    <textarea id="description" name="description" class="w-full p-3 border rounded" required>{{ old('description') }}</textarea>
  </div>

  <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">حفظ نوع النفايات</button>
</form>
@endsection
