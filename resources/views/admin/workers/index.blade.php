@extends('layouts.app')

@section('content')
<div class="mb-8">
  <h1 class="text-3xl font-bold text-green-700">إدارة الموظفين</h1>
  <a href="{{ route('admin.workers.create') }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">إضافة موظف جديد</a>
</div>

<!-- Workers Table -->
<div class="p-4 bg-white rounded-2xl shadow">
  <table class="w-full text-right border">
    <thead>
      <tr class="bg-gray-200">
        <th class="p-2">الاسم</th>
        <th class="p-2">البريد</th>
        <th class="p-2">الدور</th>
        <th class="p-2">تحكم</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="p-2">أحمد</td>
        <td class="p-2">ahmed@email.com</td>
        <td class="p-2">موظف</td>
        <td class="p-2">
          <a href="#" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">تعديل</a>
          <a href="#" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">حذف</a>
        </td>
      </tr>
      <tr>
        <td class="p-2">سارة</td>
        <td class="p-2">sara@email.com</td>
        <td class="p-2">موظف</td>
        <td class="p-2">
          <a href="#" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">تعديل</a>
          <a href="#" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">حذف</a>
        </td>
      </tr>
    </tbody>
  </table>
</div>
@endsection
