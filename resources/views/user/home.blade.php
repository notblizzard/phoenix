@extends('app')

@section('content')
@foreach ($posts as $post)
  <div class='post'>
    <p><i class='fa fa-arrow-up upvote-post' data-slug="{{$post->slug}}" data-hub="{{$post->hub_name}}" data-csrf="{{csrf_token()}}"></i><span class='upvotes'>{{$post->upvotes}}</span>|
       <i class='fa fa-arrow-down downvote-post' data-slug="{{$post->slug}}" data-hub="{{$post->hub_name}}" data-csrf="{{csrf_token()}}"></i><span class='downvotes'>{{$post->downvotes}}</span>
    <a href='{{route("post", ["title" => $post->hub_name, "slug" => $post->slug])}}'>{{$post->title}} </a>
    @if ($post->user_is_admin)
      <small>(By <span id='admin'>{{$post->username}}</span> | Comments: {{$post->comment_count}})</small>
    @elseif ($post->user_is_mod)
      <small>(By <span id='mod'>{{$post->username}}</span> | Comments: {{$post->comment_count}})</small>
    @else
      <small>(By {{$post->username}} | Comments: {{$post->comment_count}})</small>
    @endif

    @if (Auth::check())
      @if (Auth::user()->id === $post->user_id || Auth::user()->is_admin)
    <i class='fa fa-times delete-post' data-post-id="{{$post->id}}" data-csrf="{{csrf_token()}}"></i>
    </p>
  @endif
@endif
  </div>
@endforeach
@stop
