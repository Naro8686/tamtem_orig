@extends('emails.blue_template', ['title' => $title, 'username' => $deal->user->name])

@section('content')
    <p>Вашей заявке № <b>{{$deal->id}}</b> , название: <b>{{$deal->name}}, </b> </p>
    <p>присвоен статус: <b>"{{$msg}}"</b></p>
    <p style="font-size:50%;">Доступно по ссылке:</p>
    <a href="{{$url_to_deal}}">{{$url_to_deal}}</a>
    <p>Если возникли вопросы, обратитесь в службу поддержки сайта</p>
		
    <br/>
@endsection