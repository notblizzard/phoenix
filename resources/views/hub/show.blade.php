@extends('app')

@section('content')
<h1>{{$hub->name}}</h1>
<small>{{$hub->description}}</small>

@if (Auth::check())
  @if ($subscribed)
    <form method="POST" action="{{route('unsubscribe', ['title' => $hub->name])}}">
      {!! csrf_field() !!}
      <input type='submit' class='button' style="left:3px;" value="Unsubscribe">
      <br />
      <br />
      <button class='button'><a href='{{ route("post.create", ["title" => $name]) }}'>New Post</a></button>
    </form>
  @else
    <form method="POST" action="{{route('subscribe', ['title' => $hub->name])}}">
      {!! csrf_field() !!}
      <button type='submit' class='button' value="Subscribe" style="left:3px;">Subscribe</button>
      <br />
      <br />
      <button class='button'><a href='{{ route("post.create", ["title" => $name]) }}'>New Post</a></button>
    </form>
  @endif
@endif
<br />
<p># of Subscribers: {{$subscriptions}}</p>
<p>Moderators: </p>
@foreach ($moderators as $mod)
  <p>{{$mod->username}}</p>
@endforeach
<br />

@foreach ($posts as $post)
  @if ($post->is_stickied)
    <div class='post stickied'>
  @else
    <div class='post'>
  @endif
    <p><i class='fa fa-arrow-up upvote-post' data-slug="{{$post->slug}}" data-hub="{{$hub->name}}" data-csrf="{{csrf_token()}}"></i><span class='upvotes'>{{$post->upvotes}}</span>|
       <i class='fa fa-arrow-down downvote-post' data-slug="{{$post->slug}}" data-hub="{{$hub->name}}" data-csrf="{{csrf_token()}}"></i><span class='downvotes'>{{$post->downvotes}}</span>
    <a href='{{route("post", ["title" => $hub->name, "slug" => $post->slug])}}'>{{$post->title}} </a>
    @if ($post->user_is_admin)
      <small>(By <span id='admin'><a href="/users/{{$post->username}}">{{$post->username}}</a></span> | Comments: {{$post->comment_count}})</small></p>
    @elseif ($post->user_is_mod)
      <small>(By <span id='mod'><a href="/users/{{$post->username}}">{{$post->username}}</a></span> | Comments: {{$post->comment_count}})</small></p>
    @else
      <small>(By <a href="/users/{{$post->username}}">{{$post->username}}</a> | Comments: {{$post->comment_count}})</small></p>
    @endif
  </div>
@endforeach
@stop