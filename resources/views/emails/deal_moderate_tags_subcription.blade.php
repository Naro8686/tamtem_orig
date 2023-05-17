@extends('emails.blue_template', ['title' => '', 'username' => $user_name])

@section('content')
    <!-- <p style="color:#001d85;">Спецификация:</p>
    <p>{!! html_entity_decode($dqh_specification)!!}</p> -->
    <p style="color:#001d85;">Наименование:</p>
    <p>{{$deal_name}}</p>
    <p style="color:#001d85;">Тип сделки:</p>
    <p>{{$dqh_type_deal}}</p>
    <p style="color:#001d85;">Объем:</p>
    <p>{{$dqh_volume_unit_for_all}}</p>
    <p style="color:#001d85;">Минимальная партия:</p>
    <p>{{$dqh_min_party}}</p>
    <p style="color:#001d85;">Подробнее изучить заказ можно по ссылке:</p>
    <p><a href="{{$url_to_deal}}">{{$url_to_deal}}</a></p>
    <p style="color:#001d85; margin-top: 50px;">вы всегда можете <a href="{{$url_to_unsubscribe}}">отписаться</a> от уведомлений</p>
    <br/>
@endsection
