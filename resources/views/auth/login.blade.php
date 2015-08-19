@extends('app')

@section('content')
<h1>Login</h1>
<form method='POST' action='{{ url("login") }}'>
  {!! csrf_field() !!}

  <label>Email</label><br />
  <input class='input' name='email'><br />

  <label>Password</label><br />
  <input class='input' name='password' type='password'><br /><br />

  <input class='button' type='submit' value="Login">
</form>
<br />
<h3>Don't have an account?</h3>
<br />
<a href="{{url('register')}}" class='button'>Register</a>
@stop