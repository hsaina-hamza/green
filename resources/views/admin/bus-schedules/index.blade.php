@extends('layouts.app')

@section('content')
<div class="container" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">جداول مرور الحافلات</h2>
                    <a href="{{ route('admin.bus-schedules.create') }}" class="btn btn-primary">إضافة جدول جديد</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>الموقع</th>
                                    <th>المسار</th>
                                    <th>وقت المغادرة</th>
                                    <th>وقت الوصول</th>
                                    <th>التكرار</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($schedules as $schedule)
                                    <tr>
                                        <td>{{ $schedule->location->name }}</td>
                                        <td>{{ $schedule->route }}</td>
                                        <td>{{ date('h:i A', strtotime($schedule->departure_time)) }}</td>
                                        <td>{{ date('h:i A', strtotime($schedule->arrival_time)) }}</td>
                                        <td>{{ $schedule->frequency }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.bus-schedules.edit', $schedule) }}" class="btn btn-sm btn-primary ml-2">
                                                    تعديل
                                                </a>
                                                <form action="{{ route('admin.bus-schedules.destroy', $schedule) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                                        حذف
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">لا توجد جداول متاحة</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $schedules->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
