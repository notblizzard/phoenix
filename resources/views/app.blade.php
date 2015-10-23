<!DOCTYPE html>
<html>
<head>
  <title>Phoenix</title>
  <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
  <script src="{{secure_asset('js/app.js')}}"></script>
      <link rel='stylesheet' href='{{secure_asset("css/app.css")}}'>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">


</head>
<body>
@if (Auth::check())
  <ul class='menu-bar'>
  <li><a href="/home">home</a></li>
  <span>|</span>
  <li><a href="/logout">logout</a></li>
  <span>|</span>
  <li><a href="/new">new hub</a></li>
  <span>|</span>
  <li><a href="/explore">explore</a></li>
  <span>|</span>
  @foreach(explode(',',Auth::user()->subscriptions) as $subscription)
    <li><a href="/hub/{{$subscription}}">{{$subscription}}</a></li>
  @endforeach
  </ul>
@endif
<center>
@yield('content')
</center>
</body>
</html>