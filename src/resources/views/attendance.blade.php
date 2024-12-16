@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')

<div class="attendance__content">
    <div class="attendance__top">
        <div class="date-navigation">
            <!-- 矢印リンク -->
            <a href="{{ route('attendance.pagination', ['date' => \Carbon\Carbon::parse($date)->subDay()->toDateString()]) }}" class="arrow-link">&lt;</a>
            <span class="current-date">{{ $date }}</span>
            <a href="{{ route('attendance.pagination', ['date' => \Carbon\Carbon::parse($date)->addDay()->toDateString()]) }}" class="arrow-link">&gt;</a>
        </div>
    </div>
        <table>
            <thead>
                <tr>
                    <th>名前</th>
                    <th>勤務開始</th>
                    <th>勤務終了</th>
                    <th>休憩時間</th>
                    <th>勤務時間</th>
                </tr>
            </thead>

            <tbody>
            @foreach ($attendances as $attendance)
            <tr>
                <td>{{ $attendance->user->name ?? '-' }}</td>
                <td>{{ $attendance->start_time ?? '-' }}</td>
                <td>{{ $attendance->end_time ?? '-' }}</td>
                <td>{{ $attendance->total_rest_time ?? 0 }} 分</td>
                <td>{{ $attendance->working_hours ?? '-' }}</td>
            </tr>
            @endforeach
            </tbody>

        </table>
        {{ $attendances->links('vendor.pagination.custom') }}
    </div>
</div>

@endsection
