@extends('emails.blue_template', ['title' => 'Победа в заказе и списание с балланса', 'username' => $deal->winner->owner->name])

@section('content')
    <p>Вы были выбраны победителем в заказе: <b>{{$deal->name}}</b></p>
    <p></p>
    <p>Ссылка на заказ:</p>
    <p><a href="{{$url_to_deal}}">{{$url_to_deal}}</a></p>
    <p>С Вашего счета было списано - {{$amount}} р.</p>
    <p>Контакты открыты, свяжитесь с заказчиком!</p>
@endsection