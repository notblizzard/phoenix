@extends('app')

@section('content')
<h1>New Hub</h1>
<i>Make it classy</i>
<br />
<br />
@if (count($errors) > 0)
  <ul class='errors'>
  @foreach($errors->all() as $error)
    <li>{{$error}}</li>
  @endforeach
  </ul>
@endif

<form method='POST' action='{{ url("new") }}'>
  {!! csrf_field() !!}

  <label>Title</label><br />
  <input class='input' name='name'><br />

  <label>Description</label><br />
  <textarea class='textarea' name='description'></textarea><br />

  <input class='button' type='submit'>
</form>
@stop