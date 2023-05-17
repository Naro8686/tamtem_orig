@extends('emails.blue_template', ['title' => 'Создано новое объявление', 'username' => 'Cотрудник'])

@section('content')
    <p>Было создано новое объявление <b>{{$deal->name}}</b>, с номером <b>{{$deal->id}}</b>, в данный момент оно проходит модерацию.</p>
@endsection