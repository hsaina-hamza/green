@extends('layouts.app')

@section('content')
<div class="container">
    <h1>تفاصيل البلاغ</h1>

    <table class="table table-bordered">
        <tr>
            <th>الموقع</th>
            <td>{{ $report->location }}</td>
        </tr>
        <tr>
            <th>نوع النفايات</th>
            <td>{{ $report->waste_type }}</td>
        </tr>
        <tr>
            <th>الوصف</th>
            <td>{{ $report->description }}</td>
        </tr>
        <tr>
            <th>الحالة</th>
            <td>{{ $report->status }}</td>
        </tr>
        @if($report->image)
        <tr>
            <th>الصورة</th>
            <td><img src="{{ asset('storage/reports/' . $report->image) }}" alt="صورة البلاغ" width="200"></td>
        </tr>
        @endif
    </table>

    <a href="{{ route('reports.index') }}" class="btn btn-secondary">عودة إلى قائمة البلاغات</a>
</div>
@endsection
