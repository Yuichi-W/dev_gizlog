@extends ('common.user')
@section ('content')

<h2 class="brand-header">修正申請</h2>
<div class="main-wrap">
  <div class="container">
    {!! Form::open(['route' => ['attendance.modify'], 'method' => 'put']) !!}
      <div class="form-group form-size-small @if(!empty($errors->first('date_time'))) has-error @endif">
        {!! Form::input('date', 'date', Carbon::now()->format('Y-m-d'), ['class' => 'form-control']) !!}
        <span class="help-block">{{ $errors->first('date_time') }}</span>
      </div>
      <div class="form-group @if (!empty($errors->first('revision_request'))) has-error @endif">
        {!! Form::textarea('revision_request', null, ['class' => 'form-control', 'placeholder' => '修正申請の内容を入力してください。']) !!}
        <span class="help-block">{{ $errors->first('revision_request') }}</span>
      </div>
      {!! Form::submit('申請', ['class' => 'btn btn-success pull-right']) !!}
    {!! Form::close() !!}
  </div>
</div>

@endsection

