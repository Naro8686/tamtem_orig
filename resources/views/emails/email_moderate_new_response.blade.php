@extends('emails.blue_template', ['title' => $title, 'username' => $userName])

@section('content')
    <p>На Вашу заявку <b>{{$dealName}}</b> (№ заявки на сайте: <b>{{$dealId}}</b> ),  </p>
    <p>появился новый отклик.</p>
    <p style="color:#001d85;">Перейти на страницу заявки можно по ссылке:</p>
    <p><a href="{{$url_to_deal}}">{{$url_to_deal}}</a></p>
    <p>Если возникли вопросы, обратитесь в службу поддержки сайта</p>
    <br/>
@endsection