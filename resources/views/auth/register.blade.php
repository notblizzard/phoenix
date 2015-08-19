@extends('app')

@section('content')
<h2>Register</h2>
<i>It only takes like 20 seconds</i>
<form method='POST' action='{{ url("register") }}'>
  {!! csrf_field() !!}

  <label>Username</label><br />
  <input class='input' name='username'><br />

  <label>Email</label><br />
  <input class='input' name='email'><br />

  <label>Password</label><br />
  <input class='input' name='password' type='password'><br />

  <label>Confirm Password</label><br />
  <input class='input' name='password_confirmation' type='password'><br />

  <input class='button' type='submit'>
</form>
@stop