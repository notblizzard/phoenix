@extends('app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<h2>{{$post->title}}</h2>
@if ($user->moderator)
  <small>Posted by <span id='mod'><a href="/users/{{$user->username}}">{{$user->username}}</a></span></small>
@else
  <small>Posted by <a href="/users/{{$user->username}}">{{$user->username}}</a></small>
@endif

@if (Auth::check())
  @if (Auth::user()->id === $user->id || Auth::user()->is_admin)
    <i class='fa fa-times delete-post' data-csrf="{{csrf_token()}}" data-post-id="{{$post->id}}"></i>
  @endif
  @if (Auth::user()->is_admin || $user->moderator)
    <i class='fa fa-sticky-note-o sticky-post'  data-post-id="{{$post->id}}"></i>
  @endif
@endif

<p><i class='fa fa-arrow-up upvote-post' data-slug="{{$post->slug}}" data-hub="{{$title}}" data-csrf="{{csrf_token()}}"></i><span class='upvotes'>{{$post->upvotes}}</span>|
       <i class='fa fa-arrow-down downvote-post' data-slug="{{$post->slug}}" data-hub="{{$title}}" data-csrf="{{csrf_token()}}"></i><span class='downvotes'>{{$post->downvotes}}</span>
<article>{{$post->content}}</article>

@if ($comments)
  @foreach ($comments as $comment)
    <div id='comment'>
      <img src="https://gravatar.com/avatar/{{$comment->email}}" class='avatar' />
      <p><small>
      @if ($comment->user_is_admin)
        <span id='admin'><a href="/users/{{$comment->username}}">{{$comment->username}}</a></span>
      @elseif ($comment->user_is_mod)
        <span id='mod'><a href="/users/{{$comment->username}}">{{$comment->username}}</a></span>
      @else
        <span><a href="/users/{{$comment->username}}">{{$comment->username}}</a></span>
      @endif
      </small>
      </p>
      <small>{{$comment->created_at}}</small>
      <article>{{$comment->content}}</article>

    </div>
  @endforeach
@endif
@if (count($errors) > 0)
  <ul class='errors'>
  @foreach($errors->all() as $error)
    <li>{{$error}}</li>
  @endforeach
  </ul>
@endif
@if (Auth::check())
  <br />
  <br />
  <form method="POST" action="{{route('new comment', ['slug' => $post->slug, 'title' => $title])}}">
  {!! csrf_field() !!}
  <textarea name='content'></textarea><br />
  <input type='submit' class='button' value='New Comment' />
  </form>
@endif
@stop