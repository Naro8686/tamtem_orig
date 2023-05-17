@php
	$isTamtem = (strpos($_SERVER['HTTP_HOST'], 'tamtem.ru') !== false);
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	@if ($isTamtem)
	<!-- BING -->
	<meta name="msvalidate.01" content="098E721FA39EE921AB523C90E10C8C44" />
	<!-- END BING -->
	@endif

	<!-- Meta data -->
	<meta name="title" content="TamTem - Сервис поиска оптовых поставщиков для бизнеса">
	<meta name="keywords" content="сервис поиска поставщиков, поиск оптовых поставщиков, оптовые поставки для бизнеса, объявления о покупке оптом, бесплатно объявление оптом.">
	<meta name="description" content="TamTem – это сервис поиска оптовых поставщиков для бизнеса. Здесь Вы можете бесплатно подать объявление о покупке оптом определенного товара, а мы найдем поставщика.">

	{{-- <link rel="image_src" href="{{ url('/') }}/images/image-site.jpg"> --}}
	<meta name="author" content="TamTem">
	<meta name="copyright" content="© ООО «Акстек», 2020 ОГРН 1187847338920">
	<meta name="application-name" content="TamTem">
	<meta property="og:type" content="website">
	<meta property="og:site_name" content="{{ url('/') }}">
	<meta property="og:title" content="TamTem">
	<meta property="og:image" content="{{ url('/') }}/images/og_logo.png">
	<meta property="og:url" content="{{ url('/') }}">
	<meta property="og:description" content="TamTem – это сервис поиска оптовых поставщиков для бизнеса. Здесь Вы можете бесплатно подать объявление о покупке оптом определенного товара, а мы найдем поставщика.">
	<title>TamTem - Сервис поиска оптовых поставщиков для бизнеса</title>

	<!-- Icon -->
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

	<!-- Scripts -->
	<!-- <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript" defer></script> -->
	<script src="{{ mix('js/manifest.js') }}" defer></script>
	<script src="{{ mix('js/vendor.js') }}" defer></script>
	<script src="{{ mix('js/client.js') }}" defer></script>

	<!-- Styles -->
	<link href="{{ mix('css/client.css') }}" rel="stylesheet">
	<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

</head>
<body>

	<noscript>
		<strong>We're sorry but vue-admin-lte-template doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
	</noscript>

	<div id="app"></div>
	
	<!-- Analytics and metrics -->
	@if ($isTamtem)
	
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-141180310-1"></script>
		<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-141180310-1');
		</script>
		<!-- END Global site tag (gtag.js) - Google Analytics -->
		
		<!-- Top100 (Kraken) Counter -->
		<script>
			(function (w, d, c) {
			(w[c] = w[c] || []).push(function() {
				var options = {
					project: 6658962,
				};
				try {
					w.top100Counter = new top100(options);
				} catch(e) { }
			});
			var n = d.getElementsByTagName("script")[0],
			s = d.createElement("script"),
			f = function () { n.parentNode.insertBefore(s, n); };
			s.type = "text/javascript";
			s.async = true;
			s.src =
			(d.location.protocol == "https:" ? "https:" : "http:") +
			"//st.top100.ru/top100/top100.js";
			if (w.opera == "[object Opera]") {
			d.addEventListener("DOMContentLoaded", f, false);
		} else { f(); }
		})(window, document, "_top100q");
		</script>
		<noscript>
		<img src="//counter.rambler.ru/top100.cnt?pid=6658962" alt="Топ-100" />
		</noscript>
		<!-- END Top100 (Kraken) Counter -->

		<!-- Yandex.Metrika counter -->
		<script>
		(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
		m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
		(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

		ym(76387882, "init", {
			clickmap:true,
			trackLinks:true,
			accurateTrackBounce:true,
			webvisor:true
		});
		window.ym = ym
		window.ym(76387882, "reachGoal", "main_page");
		</script>
		<noscript><div><img src="https://mc.yandex.ru/watch/76387882" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
		<!-- /Yandex.Metrika counter -->

		<!-- Rating@Mail.ru counter -->
		<script>
		var _tmr = window._tmr || (window._tmr = []);
		_tmr.push({id: "3125531", type: "pageView", start: (new Date()).getTime()});
		(function (d, w, id) {
		if (d.getElementById(id)) return;
		var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
		ts.src = "https://top-fwz1.mail.ru/js/code.js";
		var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
		if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
		})(document, window, "topmailru-code");
		</script><noscript><div>
		<img src="https://top-fwz1.mail.ru/counter?id=3125531;js=na" style="border:0;position:absolute;left:-9999px;" alt="Top.Mail.Ru" />
		</div></noscript>
		<!-- //Rating@Mail.ru counter -->

		<!-- VK - metric -->
			<script>!function(){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src="https://vk.com/js/api/openapi.js?162",t.onload=function(){VK.Retargeting.Init("VK-RTRG-373515-g2mAa"),VK.Retargeting.Hit()},document.head.appendChild(t)}();</script><noscript><img src="https://vk.com/rtrg?p=VK-RTRG-373515-g2mAa" style="position:fixed; left:-999px;" alt=""/></noscript>
		<!-- VK - metric -->
		<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '315150146045581');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=315150146045581&ev=PageView&noscript=1" alt=""/></noscript>
<!-- End Facebook Pixel Code -->  
<!-- Roistat -->
<script>(function(w, d, s, h, id) {    w.roistatProjectId = id; w.roistatHost = h;    var p = d.location.protocol == "https:" ? "https://" : "http://";    var u = /^.*roistat_visit=[^;]+(.*)?$/.test(d.cookie) ? "/dist/module.js" : "/api/site/1.0/"+id+"/init";    var js = d.createElement(s); js.charset="UTF-8"; js.async = 1; js.src = p+h+u; var js2 = d.getElementsByTagName(s)[0]; js2.parentNode.insertBefore(js, js2);})(window, document, 'script', 'cloud.roistat.com', 'ca04ee5c41c8e47c722300ec84d8c981');</script>
<!-- Roistat -->
	@endif
	<!-- END Analytics and metrics -->
<script src="//code.jivosite.com/widget/te4uKTP41V" async></script>	
</body>
</html>
