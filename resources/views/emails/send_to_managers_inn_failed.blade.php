@extends('emails.blue_template', ['title' => 'Проблема с созданием организации у пользователя', 'username' => 'Менеджер'])

@section('content')
    <p>{{$msg}}</p>
    <br/>
@endsection