@extends('layouts.app')

@section('content')
<div class="container" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">جداول مرور الحافلات</h2>
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
                                    <th>ملاحظات</th>
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
                                        <td>{{ $schedule->notes ?? 'لا توجد ملاحظات' }}</td>
                                        <td>
                                            <a href="{{ route('worker.bus-schedules.edit', $schedule) }}" class="btn btn-sm btn-primary">
                                                تعديل الملاحظات
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">لا توجد جداول متاحة</td>
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
