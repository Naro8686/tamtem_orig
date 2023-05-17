@extends('emails.blue_template', ['title' => 'Восстановление пароля', 'username' => $user->name])

@section('content')
    <!-- <p>Вы от вас поступила заявка на сброс пароля в TamTem.</p> -->
    <p style="font-family:Verdana, sans-serif; font-size: 14px;">Ваш новый пароль для входа в систему <b>{{ $new_password }}</b></p>
    <p style="font-family:Verdana, sans-serif; font-size: 14px;">Для смены пароля пройдите по ссылке:</p>
    <p style="font-family:Verdana, sans-serif; font-size: 14px;">
			<a style="color: #ffffff; display: block; padding: 10px 15px; border-radius: 32px; background-color: #2fc06e; font-family: Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 600; line-height: 15px; text-decoration: none;text-align: center; width: 200px; margin: 0 auto;" href="{{route('password.reset.confirm', ['token' => $password_reset_token])}}">Восстановить</a></p>
    <br/>
@endsection