@extends('emails.blue_template', ['title' => 'Данные для доступа к сервису', 'username' => $user->name])

@section('content')
    <p>Вы успешно зарегистрированны в TamTem.</p>
    <p>Ваш  логин для входа в систему <b>{{ $email }}</b></p>
    <p>Ваш  пароль для входа в систему <b>{{ $password }}</b></p>
		<p>
			<a href="https://tamtem.ru" style="color: #ffffff; display: block; padding: 10px 15px; border-radius: 32px; background-color: #2fc06e; font-family: Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 600; line-height: 15px; text-decoration: none;text-align: center; width: 200px; margin: 0 auto;">Перейти</a>
		</p>
    <br/>
@endsection