@extends('emails.blue_template', ['title' => 'Подтверждение регистрации', 'username' => $user->name])

@section('content')
    <p>Вы зарегистрировались на портале TamTem,</p>
    <p>для подтверждения регистрации пройдите по ссылке:</p>
    <p><a style="color: #ffffff; display: block; padding: 10px 15px; border-radius: 32px; background-color: #2fc06e; font-family: Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 600; line-height: 15px; text-decoration: none;text-align: center; width: 200px; margin: 0 auto;" href="{{route('register.confirm', ['token' => $user->register_confirm_token])}}">Подтвердить</a></p>
    <br/>
@endsection