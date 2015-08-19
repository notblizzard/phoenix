@extends('app')


@section('content')
@if (count($errors) > 0)
  <ul class='errors'>
  @foreach($errors->all() as $error)
    <li>{{$error}}</li>
  @endforeach
  </ul>
@endif

  <form method='POST' action='{{ route("post.create", ["title" => $name]) }}'>
  {!! csrf_field() !!}

  <label>Title</label><br />
  <input class='input' name='title'><br />

  <label>Content</label><br />
  <textarea class='input' name='content'></textarea><br />

  <input class='button' type='submit'>
</form>
@stop