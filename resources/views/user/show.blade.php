@extends('app')

@section('content')
  <h1>{{$user->username}}</h1>
  <img src="https://gravatar.com/avatar/{{$email}}" class='userpage-avatar' />
  <p>Post Upvotes: {{$post_upvotes}}</p>
  <p>Comment Upvotes: {{$comment_upvotes}}</p>
  <br />
  <h2>Latest Posts</h2>
  @foreach ($posts as $post)
    <div class='post'>
    <p>
    <a href='{{route("post", ["title" => $post->hub_name, "slug" => $post->slug])}}'>{{$post->title}} </a>
    @if ($post->user_is_admin)
      <small>(By <span id='admin'><a href="/users/{{$post->username}}">{{$post->username}}</a></span> | Comments: {{$post->comment_count}})</small></p>
    @elseif ($post->user_is_mod)
      <small>(By <span id='mod'><a href="/users/{{$post->username}}">{{$post->username}}</a></span> | Comments: {{$post->comment_count}})</small></p>
    @else
      <small>(By <span><a href="/users/{{$post->username}}">{{$post->username}}</a></span> | Comments: {{$post->comment_count}})</small></p>
    @endif
  </div>
  @endforeach
  <h2 id='latest-comments'>Latest Comments</h2>
  <br />
  @foreach ($comments as $comment)
  <div id='comment'>
      <img src="https://gravatar.com/avatar/{{$comment->email}}" class='avatar' />
      <p><small>
      @if ($comment->user_is_admin)
        <span id='admin'>{{$comment->username}}</span>
      @elseif ($comment->user_is_mod)
        <span id='mod'>{{$comment->username}}</span>
      @else
        <span>{{$comment->username}}</span>
      @endif
      </small>
      </p>
      <small>{{$comment->created_at}}</small>
      <article>{{$comment->content}}</article>
    </div>
  @endforeach
@stop