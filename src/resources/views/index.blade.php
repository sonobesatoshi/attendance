@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

<div class="attendance__content">

  <div class="top__wrap">
      <p class="top__text">
          {{ \Auth::user()->name }}さんお疲れ様です！
      </p>
        @if (session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
  </div>

  <div class="attendance__panel">
    <form class="form__wrap" method="POST">
    @csrf
    <div class="form__item">
                  <button class="form__item-button" type="submit" formaction="{{ route('attendance.start') }}" name="action" value="start" {{ $canStart ? '' : 'disabled' }}>勤務開始</button>
      </div>
      <div class="form__item">
                  <button class="form__item-button" type="submit"  formaction="{{ route('attendance.end') }}"  name="action" value="end" {{ $canEnd ? '' : 'disabled' }} >勤務終了</button>
      </div>
      <div class="form__item">
                  <button class="form__item-button" type="submit" formaction="{{ route('rest.start') }}" name="action" value="start_rest" {{ $canStartRest ? '' : 'disabled' }}>休憩開始</button>
      </div>
      <div class="form__item">
                  <button class="form__item-button" type="submit" formaction="{{ route('rest.end') }}" name="action" value="end_rest" {{ $canEndRest ? '' : 'disabled' }}>休憩終了</button>
      </div>
    </form>
  </div>

</div>
@endsection
