@extends('emails.blue_template', ['title' => $title, 'username' => 'Менеджер'])

@section('content')
    <p>{{$msg}}</p>
    <br/>
@endsection