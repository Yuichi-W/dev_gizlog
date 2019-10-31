@extends ('common.user')
@section ('content')

<h2 class="brand-header">マイページ</h2>

<div class="main-wrap">
  <div class="btn-wrapper">
    <div class="my-info day-info">
      <p>学習経過日数</p>
      <div class="study-hour-box clearfix">
        <div class="userinfo-box"><img src="{{ Auth::user()->avatar }}"></div>
        <p class="study-hour"><span>{{ $dateSum }}</span>日</p>
      </div>
    </div>
    <div class="my-info">
      <p>累計学習時間</p>
      <div class="study-hour-box clearfix">
        <div class="userinfo-box"><img src="{{ Auth::user()->avatar }}"></div>
        <p class="study-hour"><span>{{ $attendanceHours }}</span>時間</p>
      </div>
    </div>
  </div>
  <div class="content-wrapper table-responsive">
    <table class="table">
      <thead>
        <tr class="row">
          <th class="col-xs-2">date</th>
          <th class="col-xs-3">start time</th>
          <th class="col-xs-3">end time</th>
          <th class="col-xs-2">state</th>
          <th class="col-xs-2">request</th>
        </tr>
      </thead>
      <tbody>
        @foreach($attendances as $attendance)
          <tr class="@if ($attendance->absent_status === 1) row absent-row @else row @endif">
            <td class="col-xs-2">{{ $attendance->date->format('m/d (D)') }}</td>
            <td class="col-xs-3">{{ empty($attendance->start_time) || $attendance->absent_status === 1 ? '-' : $attendance->start_time->format('H:i') }}</td>
            <td class="col-xs-3">{{ empty($attendance->end_time) || $attendance->absent_status === 1 ? '-' : $attendance->end_time->format('H:i') }}</td>
            <td class="col-xs-2">
              @if (isset($attendance->start_time) && isset($attendance->end_time) && $attendance->absent_status === 0)
                出社
              @elseif ($attendance->absent_status === 1)
                欠席
              @elseif (isset($attendance->start_time) && !isset($attendance->end_time))
                研修中
              @else
                -
              @endif 
            </td>
            <td class="col-xs-2">{{ ($attendance->revision_status == 0) ? '-' : '申請中' }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

@endsection

