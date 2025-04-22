@extends('layouts.app')

@section('content')
<div class="container" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">إضافة جدول حافلة جديد</h2>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.bus-schedules.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="location_id" class="form-label">الموقع</label>
                            <select id="location_id" name="location_id" class="form-control @error('location_id') is-invalid @enderror" required>
                                <option value="">اختر الموقع</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('location_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="route" class="form-label">المسار</label>
                            <input type="text" class="form-control @error('route') is-invalid @enderror" id="route" name="route" value="{{ old('route') }}" required>
                            @error('route')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="departure_time" class="form-label">وقت المغادرة</label>
                            <input type="time" class="form-control @error('departure_time') is-invalid @enderror" id="departure_time" name="departure_time" value="{{ old('departure_time') }}" required>
                            @error('departure_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="arrival_time" class="form-label">وقت الوصول</label>
                            <input type="time" class="form-control @error('arrival_time') is-invalid @enderror" id="arrival_time" name="arrival_time" value="{{ old('arrival_time') }}" required>
                            @error('arrival_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="frequency" class="form-label">التكرار</label>
                            <select id="frequency" name="frequency" class="form-control @error('frequency') is-invalid @enderror" required>
                                <option value="">اختر التكرار</option>
                                <option value="يومياً" {{ old('frequency') == 'يومياً' ? 'selected' : '' }}>يومياً</option>
                                <option value="أسبوعياً" {{ old('frequency') == 'أسبوعياً' ? 'selected' : '' }}>أسبوعياً</option>
                                <option value="شهرياً" {{ old('frequency') == 'شهرياً' ? 'selected' : '' }}>شهرياً</option>
                            </select>
                            @error('frequency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.bus-schedules.index') }}" class="btn btn-secondary ml-2">
                                إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                حفظ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
