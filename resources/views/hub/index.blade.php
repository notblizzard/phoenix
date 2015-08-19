@extends('app')

@section('content')
@foreach ($hubs as $hub)
  <p><a href="/hub/{{$hub->name}}">{{$hub->name}}</a></p>
  <small>{{$hub->description}}</small>
  <br />
@endforeach
@stop