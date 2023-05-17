@extends('emails.blue_template', ['title' => 'Победа в заявке', 'username' => $deal->winner->owner->name])

@section('content')
    <p></p>
    <p></p>
    <p>Поздравляем! Вас выбрали победителем в заявке {{$deal->name}} #{{$deal->id}}</p>
    <p></p>
    <p>{!! $extendedMessage !!}</p>
    <p></p>
    <p>Желаем приятной работы!</p>
@endsection