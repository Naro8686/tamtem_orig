@extends('emails.blue_template', ['title' => $title, 'username' => $userName])

@section('content')
    <p>{!! $msg !!}</p>
@endsection