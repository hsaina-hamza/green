@extends('layouts.app')

@section('content')
<div class="container py-4" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0">
                            <i class="fas fa-bus-alt ml-2"></i>
                            تعديل ملاحظات جدول الحافلة
                        </h2>
                        <a href="{{ route('worker.bus-schedules.index') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-arrow-left ml-2"></i>
                            رجوع
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="schedule-details mb-4 p-3 bg-light rounded">
                        <h5 class="text-primary border-bottom pb-2">
                            <i class="fas fa-info-circle ml-2"></i>
                            تفاصيل الجدول
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong><i class="fas fa-map-marker-alt ml-2 text-secondary"></i> الموقع:</strong>
                                    <span class="text-muted">{{ $busSchedule->location->name }}</span>
                                </p>
                                <p class="mb-2">
                                    <strong><i class="fas fa-route ml-2 text-secondary"></i> المسار:</strong>
                                    <span class="text-muted">{{ $busSchedule->route }}</span>
                                </p>
                                <p class="mb-2">
                                    <strong><i class="fas fa-sync-alt ml-2 text-secondary"></i> التكرار:</strong>
                                    <span class="text-muted">
                                        @if($busSchedule->frequency == 'Daily')
                                            يومي
                                        @elseif($busSchedule->frequency == 'Weekly')
                                            أسبوعي
                                        @elseif($busSchedule->frequency == 'Monthly')
                                            شهري
                                        @else
                                            {{ $busSchedule->frequency }}
                                        @endif
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong><i class="far fa-clock ml-2 text-secondary"></i> وقت المغادرة:</strong>
                                    <span class="text-muted">{{ date('h:i A', strtotime($busSchedule->departure_time)) }}</span>
                                </p>
                                <p class="mb-2">
                                    <strong><i class="far fa-clock ml-2 text-secondary"></i> وقت الوصول:</strong>
                                    <span class="text-muted">{{ date('h:i A', strtotime($busSchedule->arrival_time)) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('worker.bus-schedules.update', $busSchedule) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="notes" class="form-label fw-bold">
                                <i class="fas fa-sticky-note ml-2 text-primary"></i>
                                الملاحظات
                            </label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="5"
                                      placeholder="أدخل أي ملاحظات إضافية حول الجدول...">{{ old('notes', $busSchedule->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle ml-2"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('worker.bus-schedules.index') }}" class="btn btn-outline-secondary mx-2">
                                <i class="fas fa-times ml-2"></i>
                                إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save ml-2"></i>
                                حفظ التعديلات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: #6c757d;
        box-shadow: 0 0 0 0.25rem rgba(108, 117, 125, 0.25);
    }
    .card {
        border: none;
        border-radius: 10px;
    }
    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }
</style>
@endsection