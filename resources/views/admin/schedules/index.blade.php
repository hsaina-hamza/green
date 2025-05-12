@extends('layouts.app')

@section('content')
<div class="mb-8">
  <h1 class="text-3xl font-bold text-green-700">جدولة الحافلات</h1>
  <a href="{{ route('admin.schedules.create') }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">إضافة موعد جديد</a>
</div>

<!-- Schedules Table -->
<div class="p-4 bg-white rounded-2xl shadow">
  <table class="w-full text-right border">
    <thead>
      <tr class="bg-gray-200">
        <th class="p-2">التاريخ</th>
        <th class="p-2">الوقت</th>
        <th class="p-2">المسار</th>
        <th class="p-2">تحكم</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($schedules as $schedule)
        <tr>
          <td class="p-2">{{ $schedule->date }}</td>
          <td class="p-2">{{ $schedule->time }}</td>
          <td class="p-2">{{ $schedule->route }}</td>
          <td class="p-2">
            <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">تعديل</a>
            <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" class="inline">
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
