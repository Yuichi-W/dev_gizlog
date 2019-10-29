@extends ('common.user')
@section ('content')

<h2 class="brand-header">欠席登録</h2>
<div class="main-wrap">
  <div class="container">
    {!! Form::open(['route' => 'attendance.absence']) !!}
      <div class="form-group @if (!empty($errors->first('absent_reason'))) has-error @endif">
        {!! Form::textarea('absent_reason', null, ['class' => 'form-control', 'placeholder' => '欠席理由を入力してください。']) !!}
        {!! Form::hidden('date_time', Carbon::now()->format('Y-m-d')) !!}
        {!! Form::hidden('absent_status', 1) !!}
        <span class="help-block">{{ $errors->first('absent_reason') }}</span>
      </div>
      {!! Form::hidden('id', $attendance->id) !!}
      {!! Form::submit('登録', ['class' => 'btn btn-success pull-right']) !!}
    {!! Form::close() !!}
  </div>
</div>

@endsection

