@extends ('common.user')
@section ('content')

<h2 class="brand-header">質問一覧</h2>
<div class="main-wrap">
  {!! Form::open(['route' => 'question.index', 'method' => 'GET', 'id' => 'category-form']) !!}
    <div class="btn-wrapper">
      <div class="search-box">
        {!! Form::text('search_word', empty($inputs['search_word']) ? null : $inputs['search_word'], ['class' => 'form-control search-form', 'placeholder' => 'Search words...']) !!}
        {!! Form::button('<i class="fa fa-search" aria-hidden="true"></i>', ['type' => 'submit', 'class' => 'search-icon']) !!}
      </div>
      <a class="btn" href="{{ route('question.create') }}"><i class="fa fa-plus" aria-hidden="true"></i></a>
      <a class="btn" href="{{ route('question.mypage') }}">
        <i class="fa fa-user" aria-hidden="true"></i>
      </a>
    </div>
    <div class="category-wrap">
      <div class="btn all @if (empty($inputs['tag_category_id'])) selected @endif" id="0">all</div>
      @foreach($categories as $category)
        <div class="btn @if (isset($inputs['tag_category_id']) && $category->id == $inputs['tag_category_id']) selected @endif {{ $category->name }}" id="{{ $category->id }}">{{ $category->name }}</div>
      @endforeach
      {!! Form::input('hidden', 'tag_category_id', empty($inputs['tag_category_id']) ? null : $inputs['tag_category_id'], ['id' => 'category-val']) !!}
    </div>
  {!! Form::close() !!}
  <div class="content-wrapper table-responsive">
    <table class="table table-striped">
      <thead>
        <tr class="row">
          <th class="col-xs-1">user</th>
          <th class="col-xs-2">category</th>
          <th class="col-xs-6">title</th>
          <th class="col-xs-1">comments</th>
          <th class="col-xs-2"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($questions as $question)
        <tr class="row">
          <td class="col-xs-1"><img src="{{ $question->user->avatar }}" class="avatar-img"></td>
          <td class="col-xs-2">{{ $question->tagCategory->name }}</td>
          <td class="col-xs-6">{{ mb_strimwidth($question->title, 0, 30, '...', 'UTF-8') }}</td>
          <td class="col-xs-1">{{ $question->comments->count()}}<span class="point-color"></span></td>
          <td class="col-xs-2">
            <a class="btn btn-success" href="{{ route('question.show', $question->id) }}">
              <i class="fa fa-comments-o" aria-hidden="true"></i>
            </a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {{ $questions->links() }}
    <div aria-label="Page navigation example" class="text-center"></div>
  </div>
</div>

@endsection

