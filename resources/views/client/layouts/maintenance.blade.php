<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
	<title>Tamtem - сайт на обслуживании</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<meta name="theme-color" content="#ffffff">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<link rel="mask-icon" href="{{ url('/') }}/safari-pinned-tab.svg" color="#2fc06e">
	<link rel="manifest" href="{{ url('/') }}/site.webmanifest">
	<meta name="msapplication-config" content="browserconfig.xml" />
	<meta name="msapplication-TileColor" content="#ffffff">
	<link rel="apple-touch-icon" href="{{ url('/') }}/favicons/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ url('/') }}/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ url('/') }}/favicon-16x16.png">
	<link rel="shortcut icon" href="{{ url('/') }}/favicon.ico" type="image/x-icon">
	<!-- <base href=""> -->
	<link rel="stylesheet" type="text/css" href="{{asset ('/blades/styles/main.css') }}">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
	<div class="page-wrapper">
		<section class="serve">
      <div class="container serve__container">
        <h1 class="serve__title">Ошибка 504</h1>
        <p class="serve__message">Извините, сайт находится на техническом обслуживании</p><a class="serve__tel" href="tel:+79307202300">+7 930 720 23 00</a>
      </div>
    </section>
	</div>
	<script src="//code.jivosite.com/widget/te4uKTP41V" async></script>	
</body>
</html>