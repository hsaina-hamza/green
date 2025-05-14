@extends('layouts.app')

@section('content')
<div class="container py-4" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h2 class="h4 mb-0">
                        <i class="fas fa-bus-alt ml-2"></i>
                        جداول مرور الحافلات
                    </h2>
                    <a href="{{ route('worker.bus-schedules.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus ml-2"></i>
                        إضافة جدول جديد
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="fas fa-check-circle ml-2"></i>
                            <div>{{ session('success') }}</div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>الموقع</th>
                                    <th>المسار</th>
                                    <th>وقت المغادرة</th>
                                    <th>وقت الوصول</th>
                                    <th>التكرار</th>
                                    <th>الحالة</th>
                                    <th class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($schedules as $schedule)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $schedule->location->name }}</td>
                                        <td>{{ $schedule->route }}</td>
                                        <td dir="ltr">{{ date('h:i A', strtotime($schedule->departure_time)) }}</td>
                                        <td dir="ltr">{{ date('h:i A', strtotime($schedule->arrival_time)) }}</td>
                                        <td>
                                            @if($schedule->frequency === 'daily')
                                                <span class="badge badge-success">يومي</span>
                                            @elseif($schedule->frequency === 'weekdays')
                                                <span class="badge badge-info">أيام العمل</span>
                                            @else
                                                <span class="badge badge-warning">عطلة نهاية الأسبوع</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($schedule->is_active)
                                                <span class="badge badge-success">نشط</span>
                                            @else
                                                <span class="badge badge-danger">غير نشط</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('worker.bus-schedules.edit', $schedule) }}" 
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="تعديل">
                                                   <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('worker.bus-schedules.toggle-status', $schedule) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-outline-{{ $schedule->is_active ? 'warning' : 'success' }}"
                                                            title="{{ $schedule->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                        <i class="fas fa-{{ $schedule->is_active ? 'times' : 'check' }}"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                                            <p class="h5">لا توجد جداول متاحة حالياً</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($schedules->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $schedules->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th {
        font-weight: 600;
        white-space: nowrap;
    }
    .badge {
        font-size: 0.85rem;
        font-weight: 500;
    }
    .btn-group {
        direction: ltr;
    }
</style>
@endpush