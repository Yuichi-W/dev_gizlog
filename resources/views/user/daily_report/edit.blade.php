@extends ('common.user')
@section ('content')

<h1 class="brand-header">日報編集</h1>
<div class="main-wrap">
  <div class="container">
    <!-- <form> -->
    {!! From::open(['route' => ['user.daily_report.update', '$report->id'], 'method' => 'PUT']) !!}
      <!-- <input class="form-control" name="user_id" type="hidden" value="4"> -->
      {!! Form::hidden('user_id', '4', ['class' => 'form-control']) !!}
      <div class="form-group form-size-small">
        <!-- <input class="form-control" name="reporting_time" type="date"> -->
        {!! Form::input('date', 'reporting_time', '$report->reporting_time', ['required', 'class' => 'form-control']) !!}
      <span class="help-block"></span>
      </div>
      <div class="form-group">
        <!-- <input class="form-control" placeholder="Title" name="title" type="text"> -->
        {!! Form::input('text', 'title', '$report->title', ['required', 'class' => 'form-control', 'placeholder' => 'Title']) !!}
      <span class="help-block"></span>
      </div>
      <div class="form-group">
        <!-- <textarea class="form-control" placeholder="本文" name="contents" cols="50" rows="10">本文</textarea> -->
        {!! Form::input('texterea', 'contents', ['cols' => '50', 'rows' => '10'], ['required', 'class' => 'form-control', 'placeholder' => '本文']) !!}
      <span class="help-block"></span>
      </div>
      <!-- <button type="submit" class="btn btn-success pull-right">Update</button> -->
      {!! Form::submit('Update', ['class' => 'btn btn-success pull-right']) !!}
    {!! Form::close() !!}
    </form>
  </div>
</div>

@endsection

