@extends('layouts.app')

@section('content')
<div class="mb-8">
  <h1 class="text-3xl font-bold text-green-700">إدارة أنواع النفايات</h1>
  <a href="{{ route('admin.waste_types.create') }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">إضافة نوع نفايات جديد</a>
</div>

<!-- Waste Types Table -->
<div class="p-4 bg-white rounded-2xl shadow">
  <table class="w-full text-right border">
    <thead>
      <tr class="bg-gray-200">
        <th class="p-2">اسم النوع</th>
        <th class="p-2">الوصف</th>
        <th class="p-2">تحكم</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($wasteTypes as $wasteType)
        <tr>
          <td class="p-2">{{ $wasteType->name }}</td>
          <td class="p-2">{{ $wasteType->description }}</td>
          <td class="p-2">
            <a href="{{ route('admin.waste_types.edit', $wasteType->id) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">تعديل</a>
            <form action="{{ route('admin.waste_types.destroy', $wasteType->id) }}" method="POST" class="inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">حذف</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
