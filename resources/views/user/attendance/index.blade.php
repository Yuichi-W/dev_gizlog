@extends ('common.user')
@section ('content')

<h2 class="brand-header">勤怠登録</h2>

<div class="main-wrap">

  <div id="clock" class="light">
    <div class="display">
      <div class="weekdays"></div>
      <div class="today"></div>
      <div class="digits"></div>
    </div>
  </div>
  <div class="button-holder">
    @if (is_string($attendances))
      <a class="button disabled" href="">{{ $message }}</a>
    @elseif (empty($attendances))
      <a class="button start-btn" id="register-attendance" href=#openModal>出社時間登録</a>
    @elseif ($attendances['absent_status'] === 1)
      <a class="button disabled" href="">欠席</a>
    @elseif (!empty($attendances->start_time) && empty($attendances->end_time) && empty($attendances['absent_status']))
      <a class="button end-btn" id="register-attendance" href=#openModal>退社時間登録</a>
    @elseif (($attendances === 1) || !empty($attendances->start_time) && !empty($attendances->end_time))
      <a class="button disabled" href="">退社済み</a>
    @endif
  </div>
  <ul class="button-wrap">
    <li>
      <a class="at-btn absence" href="{{ route('attendance.absence.page') }}">欠席登録</a>
    </li>
    <li>
      <a class="at-btn modify" href="{{ route('attendance.modify.page') }}">修正申請</a>
    </li>
    <li>
      <a class="at-btn my-list" href="/attendance/mypage">マイページ</a>
    </li>
  </ul>
</div>

<div id="openModal" class="modalDialog">
  <div>
    <div class="register-text-wrap"><p>フェイク</p></div>
    <div class="register-btn-wrap">
      @if (empty($attendances))
        {!! Form::open(['route' => 'attendance.startTime.register', 'method' => 'POST']) !!}
      @else
        {!! Form::open(['route' => 'attendance.endTime.register', 'method' => 'PUT']) !!}
      @endif
        <a href="#close" class="cancel-btn">Cancel</a>
        {!! Form::submit('Yes', ['class' => 'yes-btn']) !!}
      {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection
