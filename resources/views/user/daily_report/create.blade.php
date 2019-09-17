@extends ('common.user')
@section ('content')

<h2 class="brand-header">日報作成</h2>
<div class="main-wrap">
  <div class="container">
    <!-- <input class="form-control" name="user_id" type="hidden"> -->
    {!! Form::open(['route' => 'user.daily_report.store']) !!}
      <div class="form-group form-size-small">
        <!-- <input class="form-control" name="reporting_time" type="date"> -->
        {!! Form::input('date', 'date', '<?= date('Y-m-j');?>', ['required', 'class' => 'form-control']) !!}
        <span class="help-block"></span>
      </div>
      <div class="form-group">
        <!-- <input class="form-control" placeholder="Title" name="title" type="text"> -->
        {!! From::input('text', 'title', null, ['required', 'class' => 'form-control', 'placeholder' => 'Title']) !!}
        <span class="help-block"></span>
      </div>
      <div class="form-group">
        <!-- <textarea class="form-control" placeholder="Content" name="contents" cols="50" rows="10"></textarea> -->
        {!! Form::input('texterea', 'contents', ['cols' => '50', 'rows' => '10'], ['required', 'class' => 'form-control', 'placeholder' => 'Content']) !!}
        <span class="help-block"></span>
      </div>
      <!-- <button type="submit" class="btn btn-success pull-right">Add</button> -->
      {!! Form::submit('Add', ['class' => 'btn btn-success pull-right']) !!}
    {!! Form::close() !!}
  </div>
</div>

@endsection

