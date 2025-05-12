@extends('layouts.app')

@section('content')
<div class="container">
    <h1>قائمة البلاغات</h1>
    <a href="{{ route('reports.create') }}" class="btn btn-primary mb-3">إضافة بلاغ جديد</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>الموقع</th>
                <th>نوع النفايات</th>
                <th>الوصف</th>
                <th>الحالة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
                <tr>
                    <td>{{ $report->location }}</td>
                    <td>{{ $report->waste_type }}</td>
                    <td>{{ $report->description }}</td>
                    <td>{{ $report->status }}</td>
                    <td>
                        <a href="{{ route('reports.show', $report->id) }}" class="btn btn-info">عرض التفاصيل</a>
                        <a href="{{ route('reports.edit', $report->id) }}" class="btn btn-warning">تعديل</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
