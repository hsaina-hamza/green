@extends('layouts.app')

@section('content')
<div class="container" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">تعديل ملاحظات جدول الحافلة</h2>
                </div>

                <div class="card-body">
                    <div class="schedule-details mb-4">
                        <h5>تفاصيل الجدول:</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>الموقع:</strong> {{ $busSchedule->location->name }}</p>
                                <p><strong>المسار:</strong> {{ $busSchedule->route }}</p>
                                <p><strong>التكرار:</strong> {{ $busSchedule->frequency }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>وقت المغادرة:</strong> {{ date('h:i A', strtotime($busSchedule->departure_time)) }}</p>
                                <p><strong>وقت الوصول:</strong> {{ date('h:i A', strtotime($busSchedule->arrival_time)) }}</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('worker.bus-schedules.update', $busSchedule) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="notes" class="form-label">ملاحظات</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="4">{{ old('notes', $busSchedule->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('worker.bus-schedules.index') }}" class="btn btn-secondary ml-2">
                                إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                تحديث الملاحظات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
