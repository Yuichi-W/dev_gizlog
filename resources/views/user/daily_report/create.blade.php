@extends ('common.user')
@section ('content')

<h2 class="brand-header">日報作成</h2>
<div class="main-wrap">
  <div class="container">
    {!! Form::open(['route' => 'report.store']) !!}
      {!! Form::input('hidden', 'user_id', 2, ['class' => 'form-control']) !!}
      <div class="form-group form-size-small">
        {!! Form::input('date', 'reporting_time', $today, ['class' => 'form-control']) !!}
        <span class="help-block "></span>
      </div>
      <div class="form-group {{ $errors->has('title')? 'has-error' : '' }}">
        {!! Form::input('text', 'title', null, ['class' => 'form-control', 'placeholder' => 'Title']) !!}
        <span class="help-block">{{ $errors->first('title') }}</span>
      </div>
      <div class="form-group {{ $errors->has('content')? 'has-error' : '' }}">
        {!! Form::textarea('content', '', ['cols' => 50, 'rows' => 10, 'class' => 'form-control', 'placeholder' => 'Content']) !!}
        <span class="help-block">{{ $errors->first('content') }}</span>
      </div>
      <button type="submit" class="btn btn-success pull-right">Add</button>
    {!! Form::close() !!}
  </div>
</div>

@endsection

