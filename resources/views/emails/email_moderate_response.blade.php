@extends('emails.blue_template', ['title' => $title, 'username' => $userName])

@section('content')
    <p>Вашему отклику, на заявку  <b>{{$dealName}}</b> (№ заявки на сайте: <b>{{$dealId}}</b> ),  </p>
    <p>присвоен статус: <b>"{{$status}}"</b></p>
    <p>Если возникли вопросы, обратитесь в службу поддержки сайта</p>
		<p>Информируем Вас о гарантии, которую мы предоставляем:</p>
		<ul style="text-align: left;">
			<li>Во-первых, Вы платите только за результат, а именно после выбора покупателем Вас и Вашего предложения.</li><br>
			<li>Во-вторых, Мы гарантируем возврат денежных средств - при не состоявшейся сделке по вине заказчика, не просто «на словах», а на основании договора публичной оферты, с которым Вы согласились при регистрации.</li><br>
			<li>В-третьих, оплату контакта вы сможете совершить как через расчетный счет, так и посредством дебетовой карты.</li>
		</ul>
		<p>Спасибо Вам за уделенное внимание, возможно у Вас появились вопросы? <br>Позвоните по номеру - +7 930 720 23 00 готовы на них ответить.</p>
    <br/>
@endsection