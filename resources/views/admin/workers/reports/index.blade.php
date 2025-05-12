@extends('layouts.app')

@section('content')
<div class="mb-8">
  <h1 class="text-3xl font-bold text-green-700">متابعة البلاغات</h1>
  <a href="{{ route('worker.reports.create') }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">إضافة بلاغ جديد</a>
</div>

<!-- Waste Reports Table -->
<div class="p-4 bg-white rounded-2xl shadow">
  <table class="w-full text-right border">
    <thead>
      <tr class="bg-gray-200">
        <th class="p-2">نوع النفايات</th>
        <th class="p-2">الموقع</th>
        <th class="p-2">التعليق</th>
        <th class="p-2">الحالة</th>
        <th class="p-2">تحكم</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($wasteReports as $report)
        <tr>
          <td class="p-2">{{ $report->type }}</td>
          <td class="p-2">{{ $report->location }}</td>
          <td class="p-2">{{ $report->comment }}</td>
          <td class="p-2">
            <span class="px-3 py-1 {{ $report->status == 'pending' ? 'bg-yellow-500' : ($report->status == 'in_progress' ? 'bg-blue-500' : 'bg-green-500') }} text-white rounded">
              {{ ucfirst($report->status) }}
            </span>
          </td>
          <td class="p-2">
            <a href="{{ route('worker.reports.edit', $report->id) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">تعديل</a>
            <form action="{{ route('worker.reports.destroy', $report->id) }}" method="POST" class="inline">
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
