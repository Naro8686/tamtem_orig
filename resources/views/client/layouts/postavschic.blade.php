<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
	<title>Оптовые покупатели. Сервис поиска покупателей оптом в России.</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<meta name="theme-color" content="#ffffff">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<link rel="mask-icon" href="{{ url('/') }}/safari-pinned-tab.svg" color="#2fc06e">
	<link rel="manifest" href="{{ url('/') }}/site.webmanifest">
	<meta name="msapplication-config" content="browserconfig.xml" />
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="title" content="Оптовые покупатели. Сервис поиска покупателей оптом в России.">
	<meta name="description"
		content="Сервис поиска покупателей онлайн. Преимущества платформы при поиске оптовых покупателей. Более тысячи  оптовых заказов и предложений.">
	<meta name="keywords"
		content="оптовый заказ, поиск оптовых заказов, сервис поиска оптовых покупателей, поиск оптовых заказчиков, оптовые поставки для бизнеса, объявления о покупке оптом, бесплатно объявление оптом, тендерные заказы, оптовый заказ для бизнеса">
	<meta property="og:type" content="website">
	<meta property="og:site_name" content="{{ url('/') }}">
	<meta property="og:title" content="Оптовые покупатели. Сервис поиска покупателей оптом в России.">
	<meta property="og:image" content="{{ url('/') }}/images/og_logo.png">
	<meta property="og:url" content="{{ url('/') }}">
	<meta property="og:description"
		content="Сервис поиска покупателей онлайн. Преимущества платформы при поиске оптовых покупателей. Более тысячи  оптовых заказов и предложений.">
	<link rel="apple-touch-icon" href="{{ url('/') }}/favicons/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ url('/') }}/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ url('/') }}/favicon-16x16.png">
	<link rel="shortcut icon" href="{{ url('/') }}/favicon.ico" type="image/x-icon">
	<!-- <base href=""> -->
	<!-- <link rel="stylesheet" type="text/css" href="{{asset ('/blades/styles/main.css') }}"> -->
	<link rel="stylesheet" type="text/css" href="{{asset ('/blades/styles/vendor.css') }}">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
	<div class="page-wrapper">
		<header class="mainheader mainheader--homepage">
			<div class="container mainheader__container">
				<section class="logo"><img src="images/home/logo.png" alt="logo tamtem"></section>
				<nav class="mainmenu"><a class="mainmenu__burger">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 91 91">
							<g fill="#ffffff">
								<path
									d="M85.713 12.142H5.861c-2.305 0-4.174-1.869-4.174-4.176 0-2.305 1.869-4.174 4.174-4.174h79.852c2.305 0 4.174 1.869 4.174 4.174 0 2.307-1.869 4.176-4.174 4.176zM85.713 49.858H5.861c-2.305 0-4.174-1.869-4.174-4.175 0-2.307 1.869-4.176 4.174-4.176h79.852c2.305 0 4.174 1.869 4.174 4.176 0 2.306-1.869 4.175-4.174 4.175zM85.713 87.571H5.861c-2.305 0-4.174-1.869-4.174-4.176s1.869-4.176 4.174-4.176h79.852c2.305 0 4.174 1.869 4.174 4.176s-1.869 4.176-4.174 4.176z">
								</path>
							</g>
						</svg></a>
					<ul class="mainmenu__list notablet">
						<li class="mainmenu__item"><a class="mainmenu__link animation-link-underline"
								href="/postavschic" onclick="ym(76387882,'reachGoal','onas')">О нас</a></li> <!-- TODO change link -->
						<li class="mainmenu__item"><a class="mainmenu__link animation-link-underline" href="/!sales" onclick="ym(76387882,'reachGoal','optovieob')">Оптовые заявления</a></li>
						<li class="mainmenu__item"><a class="mainmenu__link animation-link-underline"
								href="/faq">Помощь</a></li>
					</ul>
					<section class="modalmenu">
						<header class="modalmenu__header">
							<div class="modalmenu__logo"><svg xmlns="http://www.w3.org/2000/svg" width="134.54"
									height="39.329" viewBox="0 0 134.54 39.329">
									<g id="Group_16981" data-name="Group 16981" transform="translate(-39 -13)">
										<g id="Group_16800" data-name="Group 16800" transform="translate(-336 -4)">
											<g id="Group_16648" data-name="Group 16648" transform="translate(375 17)">
												<path id="Path_17351" data-name="Path 17351"
													d="M298.463,24.525,294.384,28.6l-2.148-1.074L297,32.3l.046.047a2.279,2.279,0,0,1-3.223,3.223l-1.591-1.592a2.279,2.279,0,0,0-3.221,0L287.4,35.587a4.557,4.557,0,0,0,0,6.446h0a2.278,2.278,0,0,1-3.221,3.221h0l-.01-.011-.01-.008c-.009-.009-.017-.02-.026-.03a9.113,9.113,0,0,1,.046-12.841l9.8-9.8a19.664,19.664,0,0,0-15.619,35.76l4.079-4.079,2.149,1.074-4.768-4.768-.047-.046a2.279,2.279,0,0,1,3.223-3.223l1.592,1.591a2.279,2.279,0,0,0,3.221,0l1.613-1.613a4.557,4.557,0,0,0,0-6.446v0a2.278,2.278,0,0,1,3.221-3.221h0l.01.01.009.008c.01.01.018.021.027.03a9.115,9.115,0,0,1-.047,12.842l-9.8,9.8a19.664,19.664,0,0,0,15.619-35.761Z"
													transform="translate(-268.747 -21.761)" fill="#0a5f87" />
											</g>
											<g id="Group_16649" data-name="Group 16649"
												transform="translate(425.356 23.279)">
												<g id="Group_16608" data-name="Group 16608"
													transform="translate(0 21.188)">
													<path id="Path_18031" data-name="Path 18031"
														d="M358.726,663.43l1.108-4.478q.468-1.865,3.092-1.866h2.913a.363.363,0,0,1,.268.107.371.371,0,0,1,.108.274v5.993a.37.37,0,0,1-.376.376H364.46a.36.36,0,0,1-.268-.108.364.364,0,0,1-.108-.268v-4.884h-1.157a1.048,1.048,0,0,0-1.09.905l-.967,3.9a.61.61,0,0,1-.219.33.56.56,0,0,1-.348.126H359.04a.321.321,0,0,1-.333-.277A.4.4,0,0,1,358.726,663.43Z"
														transform="translate(-358.707 -656.963)" fill="#2dc84d" />
													<path id="Path_18032" data-name="Path 18032"
														d="M367.291,663.466v-5.992a.372.372,0,0,1,.108-.274.364.364,0,0,1,.268-.107h1.379a.364.364,0,0,1,.268.107.376.376,0,0,1,.108.274v2.205h.905a2.819,2.819,0,0,1,1.1-2.017,4.1,4.1,0,0,1,2.5-.693,3.5,3.5,0,0,1,2.515.9,4.056,4.056,0,0,1,0,5.207,3.526,3.526,0,0,1-2.528.893,4.029,4.029,0,0,1-2.516-.718,2.9,2.9,0,0,1-1.087-2.078h-.892v2.3a.364.364,0,0,1-.108.268.36.36,0,0,1-.268.108h-1.379a.37.37,0,0,1-.376-.376Zm7.97-3q0-2.1-1.425-2.1t-1.426,2.1q0,2.1,1.426,2.1T375.261,660.467Z"
														transform="translate(-358.268 -656.969)" fill="#2dc84d" />
													<path id="Path_18033" data-name="Path 18033"
														d="M377.147,663.46v-.733a.367.367,0,0,1,.382-.382h.48l.844-3.394q.468-1.865,3.091-1.866h2.8a.369.369,0,0,1,.383.382v5.993a.364.364,0,0,1-.108.268.368.368,0,0,1-.274.108h-7.212a.369.369,0,0,1-.274-.108A.363.363,0,0,1,377.147,663.46Zm2.876-1.114h2.968v-3.769h-1.114a1.082,1.082,0,0,0-1.133.905Z"
														transform="translate(-357.764 -656.963)" fill="#2dc84d" />
													<path id="Path_18034" data-name="Path 18034"
														d="M386.176,663.46v-5.993a.374.374,0,0,1,.107-.274.367.367,0,0,1,.268-.107h1.288a.367.367,0,0,1,.382.382v4.047l2.168-3.985a.805.805,0,0,1,.3-.317.747.747,0,0,1,.394-.126h1.724a.369.369,0,0,1,.383.382v5.993a.358.358,0,0,1-.108.271.375.375,0,0,1-.274.1h-1.287a.358.358,0,0,1-.271-.108.37.37,0,0,1-.1-.268v-4.04l-2.167,3.972a.9.9,0,0,1-.3.311.724.724,0,0,1-.394.132h-1.731a.36.36,0,0,1-.375-.376Z"
														transform="translate(-357.303 -656.963)" fill="#2dc84d" />
													<path id="Path_18035" data-name="Path 18035"
														d="M398.006,662.111a.357.357,0,0,1,.108-.27.365.365,0,0,1,.267-.105h.924a.727.727,0,0,1,.656.415.955.955,0,0,0,.909.416h1.25q.893,0,.893-.733t-.893-.728h-1.527a.367.367,0,0,1-.382-.381v-.64a.369.369,0,0,1,.382-.383H402a.9.9,0,0,0,.613-.182.61.61,0,0,0,.206-.482.62.62,0,0,0-.206-.487.889.889,0,0,0-.613-.185H400.87a1.035,1.035,0,0,0-.444.107.73.73,0,0,0-.339.311.719.719,0,0,1-.658.413h-.919a.374.374,0,0,1-.273-.105.358.358,0,0,1-.108-.271,1.536,1.536,0,0,1,.811-1.342,3.591,3.591,0,0,1,1.955-.511h1.238a3.849,3.849,0,0,1,2.06.477,1.6,1.6,0,0,1,.76,1.451,1.459,1.459,0,0,1-.88,1.4,1.8,1.8,0,0,1,1.071,1.712,1.637,1.637,0,0,1-.723,1.487,4.025,4.025,0,0,1-2.153.464h-1.374a3.9,3.9,0,0,1-2.063-.5A1.53,1.53,0,0,1,398.006,662.111Z"
														transform="translate(-356.036 -656.969)" fill="#2dc84d" />
													<path id="Path_18036" data-name="Path 18036"
														d="M405.817,663.46v-5.993a.374.374,0,0,1,.107-.274.363.363,0,0,1,.268-.107h1.379a.361.361,0,0,1,.271.107.377.377,0,0,1,.105.274v2.205h2.7v-2.205a.369.369,0,0,1,.383-.382H412.4a.361.361,0,0,1,.271.107.381.381,0,0,1,.105.274v5.993a.368.368,0,0,1-.105.271.364.364,0,0,1-.271.1h-1.373a.368.368,0,0,1-.274-.108.364.364,0,0,1-.108-.268v-2.3h-2.7v2.3a.359.359,0,0,1-.108.271.367.367,0,0,1-.268.1h-1.379a.358.358,0,0,1-.375-.376Z"
														transform="translate(-355.636 -656.963)" fill="#2dc84d" />
													<path id="Path_18037" data-name="Path 18037"
														d="M414.332,663.4a1.837,1.837,0,0,1-.816-1.6,1.8,1.8,0,0,1,.816-1.589,3.669,3.669,0,0,1,2.1-.555h2.039a1.343,1.343,0,0,0-.3-1,1.541,1.541,0,0,0-1.055-.283,1.3,1.3,0,0,0-1.06.372.933.933,0,0,1-.749.373h-.918a.368.368,0,0,1-.274-.108.363.363,0,0,1-.107-.268q0-1.768,3.356-1.767a3.861,3.861,0,0,1,2.352.656,2.355,2.355,0,0,1,.887,2.029v3.812a.36.36,0,0,1-.375.376h-1.189a.357.357,0,0,1-.271-.108.366.366,0,0,1-.105-.268v-.19a3.746,3.746,0,0,1-2.245.693A3.6,3.6,0,0,1,414.332,663.4Zm1.57-2.137a.674.674,0,0,0-.249.541.684.684,0,0,0,.259.549,1.113,1.113,0,0,0,.733.215,3.394,3.394,0,0,0,1.83-.554v-.961h-1.891A1.027,1.027,0,0,0,415.9,661.262Z"
														transform="translate(-355.243 -656.969)" fill="#2dc84d" />
													<path id="Path_18038" data-name="Path 18038"
														d="M421.7,663.466v-5.992a.376.376,0,0,1,.108-.274.364.364,0,0,1,.268-.107h1.379a.364.364,0,0,1,.268.107.372.372,0,0,1,.108.274v2.205h.905a2.819,2.819,0,0,1,1.1-2.017,4.1,4.1,0,0,1,2.5-.693,3.505,3.505,0,0,1,2.515.9,4.056,4.056,0,0,1,0,5.207,3.526,3.526,0,0,1-2.528.893,4.028,4.028,0,0,1-2.516-.718,2.9,2.9,0,0,1-1.087-2.078h-.892v2.3a.37.37,0,0,1-.376.376h-1.379a.36.36,0,0,1-.268-.108A.364.364,0,0,1,421.7,663.466Zm7.97-3q0-2.1-1.425-2.1t-1.426,2.1q0,2.1,1.426,2.1T429.671,660.467Z"
														transform="translate(-354.824 -656.969)" fill="#2dc84d" />
													<path id="Path_18039" data-name="Path 18039"
														d="M431.674,658.2v-.733a.369.369,0,0,1,.382-.382h6.067a.364.364,0,0,1,.271.107.38.38,0,0,1,.1.274v.733a.369.369,0,0,1-.1.268.361.361,0,0,1-.271.108h-1.965v4.884a.364.364,0,0,1-.108.268.368.368,0,0,1-.274.108H434.4a.37.37,0,0,1-.383-.376v-4.884h-1.965a.368.368,0,0,1-.273-.108A.36.36,0,0,1,431.674,658.2Z"
														transform="translate(-354.314 -656.963)" fill="#2dc84d" />
												</g>
												<g id="Group_16609" data-name="Group 16609"
													transform="translate(0.527)">
													<path id="Path_17339" data-name="Path 17339"
														d="M162.58,43.165l1.378-.121h.373a.615.615,0,0,1,.447.171.588.588,0,0,1,.176.442v1.077a.549.549,0,0,1-.176.4.772.772,0,0,1-.447.216,14.459,14.459,0,0,1-2.675.2q-4.324,0-4.324-4.525V31.549a.606.606,0,0,1,.176-.447.593.593,0,0,1,.437-.176H160.2a.592.592,0,0,1,.437.176.606.606,0,0,1,.176.447v3.36h2.806a.6.6,0,0,1,.437.17.59.59,0,0,1,.176.443v1.2a.587.587,0,0,1-.176.442.6.6,0,0,1-.437.171H160.81v3.691a2.352,2.352,0,0,0,.482,1.584A1.618,1.618,0,0,0,162.58,43.165Z"
														transform="translate(-157.331 -30.926)" fill="#0a5f87" />
													<path id="Path_17340" data-name="Path 17340"
														d="M168.851,45.237a3,3,0,0,1-1.332-2.615,2.947,2.947,0,0,1,1.332-2.594,5.992,5.992,0,0,1,3.434-.905h3.328a2.2,2.2,0,0,0-.493-1.639,2.512,2.512,0,0,0-1.725-.463,2.122,2.122,0,0,0-1.729.609,1.524,1.524,0,0,1-1.222.608h-1.5a.607.607,0,0,1-.448-.176.593.593,0,0,1-.175-.437q0-2.887,5.48-2.886a6.309,6.309,0,0,1,3.841,1.071,3.85,3.85,0,0,1,1.448,3.313v6.226a.587.587,0,0,1-.613.613h-1.941a.59.59,0,0,1-.443-.176.606.606,0,0,1-.17-.437v-.312a6.114,6.114,0,0,1-3.666,1.131A5.873,5.873,0,0,1,168.851,45.237Zm2.564-3.49a1.1,1.1,0,0,0-.407.884,1.116,1.116,0,0,0,.422.9,1.818,1.818,0,0,0,1.2.352,5.532,5.532,0,0,0,2.987-.905V41.405h-3.087A1.674,1.674,0,0,0,171.415,41.748Z"
														transform="translate(-158.971 -31.539)" fill="#0a5f87" />
													<path id="Path_17341" data-name="Path 17341"
														d="M184.263,45.347V35.562a.61.61,0,0,1,.175-.448.6.6,0,0,1,.438-.176h2.252a.59.59,0,0,1,.443.176.617.617,0,0,1,.171.448V36.2a6.5,6.5,0,0,1,1.423-1.082,3.506,3.506,0,0,1,1.684-.377,3.926,3.926,0,0,1,3.55,1.931,5.368,5.368,0,0,1,3.837-1.936,4.133,4.133,0,0,1,3.107,1.186,5.1,5.1,0,0,1,1.142,3.656v5.773a.587.587,0,0,1-.614.613h-2.242a.607.607,0,0,1-.448-.176.594.594,0,0,1-.176-.437V39.574q0-2.414-1.749-2.4-1.066,0-2.2,1.539a6.248,6.248,0,0,1,.06.865v5.773a.59.59,0,0,1-.176.442.6.6,0,0,1-.437.171h-2.243a.6.6,0,0,1-.447-.176.594.594,0,0,1-.176-.437V39.574q0-2.414-1.749-2.4a2.784,2.784,0,0,0-2.142,1.438v6.739a.59.59,0,0,1-.176.442.6.6,0,0,1-.438.171h-2.252a.585.585,0,0,1-.613-.613Z"
														transform="translate(-161.667 -31.539)" fill="#0a5f87" />
													<path id="Path_17342" data-name="Path 17342"
														d="M214.178,43.165l1.378-.121h.373a.614.614,0,0,1,.447.171.588.588,0,0,1,.176.442v1.077a.549.549,0,0,1-.176.4.772.772,0,0,1-.447.216,14.459,14.459,0,0,1-2.675.2q-4.324,0-4.324-4.525V31.549a.606.606,0,0,1,.176-.447.593.593,0,0,1,.437-.176H211.8a.593.593,0,0,1,.437.176.607.607,0,0,1,.176.447v3.36h2.806a.6.6,0,0,1,.437.17.59.59,0,0,1,.176.443v1.2a.587.587,0,0,1-.176.442.6.6,0,0,1-.437.171h-2.806v3.691a2.353,2.353,0,0,0,.482,1.584A1.618,1.618,0,0,0,214.178,43.165Z"
														transform="translate(-165.638 -30.926)" fill="#0a5f87" />
													<path id="Path_17343" data-name="Path 17343"
														d="M220.666,44.694a5.547,5.547,0,0,1-1.549-4.228,5.581,5.581,0,0,1,1.558-4.244A6.018,6.018,0,0,1,225,34.738a6.082,6.082,0,0,1,4.314,1.443,5.377,5.377,0,0,1,1.549,4.138v.573a.6.6,0,0,1-.623.623h-7.572a2.656,2.656,0,0,0,.709,1.8,2.8,2.8,0,0,0,1.946.564,2.267,2.267,0,0,0,1.83-.6,1.545,1.545,0,0,1,1.217-.613h1.5a.6.6,0,0,1,.623.623q0,2.887-5.5,2.876A6.031,6.031,0,0,1,220.666,44.694Zm1.981-5.3h4.676q-.1-2.374-2.328-2.374T222.647,39.394Z"
														transform="translate(-167.278 -31.539)" fill="#0a5f87" />
													<path id="Path_17344" data-name="Path 17344"
														d="M235.321,45.347V35.562a.607.607,0,0,1,.176-.448.594.594,0,0,1,.437-.176h2.253a.59.59,0,0,1,.443.176.619.619,0,0,1,.17.448V36.2a6.535,6.535,0,0,1,1.423-1.082,3.511,3.511,0,0,1,1.685-.377,3.926,3.926,0,0,1,3.55,1.931,5.367,5.367,0,0,1,3.836-1.936,4.135,4.135,0,0,1,3.108,1.186,5.094,5.094,0,0,1,1.141,3.656v5.773a.587.587,0,0,1-.613.613h-2.243a.606.606,0,0,1-.447-.176.594.594,0,0,1-.176-.437V39.574q0-2.414-1.749-2.4-1.066,0-2.2,1.539a6.253,6.253,0,0,1,.06.865v5.773a.59.59,0,0,1-.176.442.6.6,0,0,1-.437.171h-2.243a.607.607,0,0,1-.448-.176.6.6,0,0,1-.175-.437V39.574q0-2.414-1.75-2.4a2.784,2.784,0,0,0-2.142,1.438v6.739a.592.592,0,0,1-.175.442.6.6,0,0,1-.438.171h-2.253a.587.587,0,0,1-.613-.613Z"
														transform="translate(-169.887 -31.539)" fill="#0a5f87" />
												</g>
											</g>
										</g>
									</g>
								</svg>
							</div>
							<div class="modalmenu__close">
								<svg xmlns="http://www.w3.org/2000/svg" width="15.557" height="15.556"
									viewBox="0 0 15.557 15.556">
									<g fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"
										stroke-miterlimit="10" stroke-width="2">
										<path d="M14.142 1.414L1.414 14.1419"></path>
										<path d="M1.414 1.414l12.728 12.7279"></path>
									</g>
								</svg>
							</div>
						</header>
						<section class="modalmenu__user modalmenu__user--unlogged">
							<div class="modalmenu__user-box"></div>
						</section>
						<nav class="modalmenu__navigation">
							<ul class="modalmenu__menu-list">
								<li class="modalmenu__menu-item"><a class="modalmenu__menu-link"
										href="/lk/bids#actived">Личный кабинет</a></li>
								<li class="modalmenu__menu-item"><a class="modalmenu__menu-link"
										href="https://tamtem.ru">Заказы</a></li>
								<li class="modalmenu__menu-item"><a class="modalmenu__menu-link" href="/faq">С чего
										начать?</a></li>
								<li class="modalmenu__menu-item"><a class="modalmenu__menu-link"
										href="http://blog.tamtem.ru/">Блог</a></li>
								<li class="modalmenu__menu-item"><a class="modalmenu__menu-link"
										href="https://agent.tamtem.ru"><span
											class="modalmenu__menu-link__text--blue-text">tamtem</span><span
											class="modalmenu__menu-link__text--black-text">Агент</span></a></li>
							</ul>
						</nav>
						<footer class="modalmenu__footer">
							<ul class="modalmenu__footer-list">
								<li class="modalmenu__footer-item"><a class="modalmenu__footer-link"
										href="/contact">Контакты</a></li>
								<li class="modalmenu__footer-item"><a class="modalmenu__footer-link"
										href="mailto:team@tamtem.ru">team@tamtem.ru</a></li>
								<li class="modalmenu__footer-item"><a class="modalmenu__footer-link"
										href="tel:+78007001476">8 800 700 14 76</a></li>
							</ul>
						</footer>
					</section>
				</nav>
				<section class="createorder mainheader__createorder"><a class="createorder__btn" href="/newbid"></a></section>
				<section class="mainheader__profile profile">
					<div class="profile__section">
                        @if(Illuminate\Support\Facades\Auth::check())

                        @else
                            <a class="profile__box-homepage" href="/lk/profile">
                                <div class="profile__box-homepage-pic"><img src="images/home/log-in.png" alt="log in"></div>
                                <p class="profile__box-homepage-txt">Войти</p>
                            </a>
                        @endif
                    </div>
				</section>
			</div>
		</header>
		<!-- CONTENT -->
		<div class="content-wrapper">
			<section class="supplier">
				<!-- homepage-firstscreen -->
				<section class="homepage-firstscreen">
					<div class="container">
						<div class="homepage-firstscreen__inner">
							<div class="pagetype-switch homepage__pageswitch"><a class="pagetype-switch__btn"
									href="/">Для
									покупателей</a><a class="pagetype-switch__btn is-active" href="/postavschic">Для
									поставщиков</a></div>
						</div>
						<div class="homepage-firstscreen__inner">
							<div class="homepage-firstscreen__box">
								<h1 class="homepage-firstscreen__title">Биржа оптовых <br> продаж tamtem.ru</h1>
								<p class="homepage-firstscreen__desc">безопасная платформа </p>
								<p class="homepage-firstscreen__note">тысячи заказов и предложений по всей России</p>
								@if (Cookie::has('api_auth'))
									<a class="homepage-firstscreen__button" href="/bids?page=1&per_page=12&type_deal=buy&date_published=desc" onclick="ym(76387882,'reachGoal','nspostav')">Начать сейчас</a>
								@else
									<a class="homepage-firstscreen__button" href="/?itm=signup" onclick="ym(76387882,'reachGoal','nspostav')">Начать сейчас</a>
								@endif
							</div>
						</div>
					</div>
				</section><!-- end homepage-firstscreen -->

				<!-- shortabout -->
				<section class="shortabout">
					<div class="container">
						<ul class="shortabout__list">
							<li class="shortabout__item"><img class="shortabout__pic" src="images/home/shortabout-1.png">
								<p class="shortabout__cap">крупный и малый опт</p>
							</li>
							<li class="shortabout__item"><img class="shortabout__pic" src="images/home/shortabout-2.png">
								<p class="shortabout__cap">в Москве и регионах</p>
							</li>
							<li class="shortabout__item"><img class="shortabout__pic" src="images/home/shortabout-3.png">
								<p class="shortabout__cap">все категории товаров</p>
							</li>
						</ul>
					</div>
				</section><!-- end shortabout -->

				<section class="homepage-gradient-block">
					<!-- supplier steps -->
					<div class="supplier-steps">
						<div class="container">
							<h2 class="homepage__title homepage__title--decored supplier-steps__title">Как получить
								заказ</h2>
							<ul class="supplier-steps__list">
								<li>
									<div class="supplier-steps__pic"><img src="images/home/step1.png" alt="Выберите заказ">
									</div><strong class="supplier-steps__cap">1. Выберите заказ</strong>
									<p class="supplier-steps__txt">Выберите интересующий вас заказ. Ознакомьтесь с его
										условиями и
										описанием.
									</p>
								</li>
								<li>
									<div class="supplier-steps__pic"><img src="images/home/step2.png"
											alt="Оставьте предложение"></div>
									<strong class="supplier-steps__cap">2. Оставьте предложение</strong>
									<p class="supplier-steps__txt">Отметьте условия, которые вы можете выполнить.
										Оставьте своё
										ценовое
										предложение и комментарий для заказчика.</p>
								</li>
								<li>
									<div class="supplier-steps__pic"><img src="images/home/step3.png"
											alt="Получите контактные данные">
									</div><strong class="supplier-steps__cap">3. Получите контактные данные
										заказчика</strong>
									<p class="supplier-steps__txt">Если заказчик выбирает вас, оплачивайте получение
										контакта.
										Звоните
										заказчику.</p>
								</li>
							</ul><a class="supplier-steps__button supplier__button"
								@click.prevent="showBids('view_zakazy');" href="/bids">Посмотреть заказы</a>
						</div>
					</div><!-- end supplier steps -->
					<!-- supplier-adv -->
					<section class="supplier-adv">
						<div class="container">
							<h3 class="supplier-adv__title homepage__title homepage__title--decored">ПРЕИМУЩЕСТВА</h3>
							<p class="supplier-adv__desc">купить оптом от производителя проще, чем когда либо прежде</p>
							<ul class="supplier-adv__list">
								<li class="supplier-adv__item">
									<div class="supplier-adv__pic"><img src="images/home/advantage-1.png"
											alt="advantage time"></div>
									<h4 class="supplier-adv__name">Сократите время на поиск покупателей</h4>
									<p class="supplier-adv__text">Без утомительных обзвонов и писем. Создавайте заказы,
										получайте на
										них ответы,
										или просматривайте уже готовые предложения.</p>
								</li>
								<li class="supplier-adv__item">
									<div class="supplier-adv__pic"><img src="images/home/advantage-2.png"
											alt="advantage time"></div>
									<h4 class="supplier-adv__name">Бесплатно</h4>
									<p class="supplier-adv__text">Сервис tamtem.ru полностью бесплатен для покупателей.
										Вы можете
										создавать
										неограниченное количество заказов по любым категориям товаров или услуг. </p>
								</li>
								<li class="supplier-adv__item">
									<div class="supplier-adv__pic"><img src="images/home/advantage-3.png"
											alt="advantage time"></div>
									<h4 class="supplier-adv__name">Высокий уровень конфидециальности</h4>
									<p class="supplier-adv__text">Мы ценим безопаснь прежде всего. Ваши контакты увидит
										только тот
										поставщик,
										которого Вы сами выберите. </p>
								</li>
								<li class="supplier-adv__item">
									<div class="supplier-adv__pic"><img src="images/home/advantage-4.png"
											alt="advantage time"></div>
									<h4 class="supplier-adv__name">Просто и быстро</h4>
									<p class="supplier-adv__text">Простая и удобная в использовании платформа. Находите
										новых
										поставщиков в
										несколько кликов. Обсуждайте в чате детали сделки и получайте уведомления, чтобы
										не
										пропустить актуальные
										ответы на Ваши заказы. </p>
								</li>
								<li class="supplier-adv__item">
									<div class="supplier-adv__pic"><img src="images/home/advantage-5.png"
											alt="advantage time"></div>
									<h4 class="supplier-adv__name">Поддержка</h4>
									<p class="supplier-adv__text">Вы всегда можете связаться со службой поддержки
										сервиса tamtem.ru.
										Наши
										специалисты помогут Вам по любым вопросам, связанным с работой сервиса или
										взаимодействием с
										заказчиком.
									</p>
								</li>
								<li class="supplier-adv__item">
									<div class="supplier-adv__pic"><img src="images/home/advantage-6.png"
											alt="advantage time"></div>
									<h4 class="supplier-adv__name">Удобство</h4>
									<p class="supplier-adv__text">Вы получите уведомление, если подходящий заказ
										появится в сервисе.
									</p>
								</li>
							</ul>
						</div>
					</section><!-- end supplier-adv -->
				</section>
				<!-- supplier-price -->
				<section class="supplier-price">
					<div class="container">
						<h2 class="supplier-price__title homepage__title homepage__title--decored">Услуги и цены</h2>
						<p class="supplier-price__desc">Получайте заказы на сервисе tamtem.ru, оплачивая за контакт от
							200 ₽</p>
						<div class="supplier-price__tablebox">
							<table class="supplier-price__table">
								<tr>
									<th></th>
									<th>
										<h3>Покупателям</h3>
										<p>0 рублей</p>
									</th>
									<th colspan="2">
										<h3>Продавцам *</h3>
										<p>от 200 рублей</p>
									</th>
								</tr>
								<tr>
									<td class="supplier-price__table-serve">
										<p>Размещение объявлений</p>
									</td>
									<td class="supplier-price__table-value">
										<p>бесплатно</p>
									</td>
									<td class="supplier-price__table-value" colspan="2">
										<p>бесплатно</p>
									</td>
								</tr>
								<tr>
									<td class="supplier-price__table-serve">
										<p>Заключение сделки</p>
									</td>
									<td class="supplier-price__table-value">
										<p>бесплатно</p>
									</td>
									<td class="supplier-price__table-text">
										<p>сумма сделки, руб</p>
										<ul>
											<li>
												< 500 000 </li> <li>
													< 1 000 000 </li> <li>
														< 3 000 000 </li> </ul> </td> <td
															class="supplier-price__table-text">
															<p>стоимость, руб</p>
															<ul>
																<li>200</li>
																<li>500</li>
																<li>800</li>
															</ul>
									</td>
								</tr>
							</table>
						</div>
						<div class="supplier-price__mobile">
							<div class="supplier-price__mobile-block">
								<p class="supplier-price__mobile-title"><b>Размещение объявлений</b></p>
								<div class="supplier-price__mobile-row">
									<p class="supplier-price__mobile-name">Покупателям</p>
									<p class="supplier-price__mobile-value"><span>бесплатно</span></p>
								</div>
								<div class="supplier-price__mobile-row">
									<p class="supplier-price__mobile-name">Продавцам</p>
									<p class="supplier-price__mobile-value"><span>бесплатно</span></p>
								</div>
							</div>
							<div class="supplier-price__mobile-block">
								<p class="supplier-price__mobile-title"><b>Заключение сделки</b></p>
								<div class="supplier-price__mobile-row">
									<p class="supplier-price__mobile-name">Покупателям</p>
									<p class="supplier-price__mobile-value"><span>бесплатно</span></p>
								</div>
								<div class="supplier-price__mobile-row">
									<p class="supplier-price__mobile-name">Продавцам</p>
									<p class="supplier-price__mobile-value">от 200 рублей</p>
								</div>
								<div class="supplier-price__mobile-tablebox">
									<table class="supplier-price__mobile-table">
										<tr>
											<th>сумма сделки, руб</th>
											<th>стоимость, руб</th>
										</tr>
										<tr>
											<td class="supplier-price__mobile-table-txt">
												<ul>
													<li>
														< 500 000 </li> <li>
															< 1 000 000 </li> <li>
																< 3 000 000 </li> </ul> </td> <td
																	class="supplier-price__mobile-table-txt">
																	<ul>
																		<li>200</li>
																		<li>500</li>
																		<li>800</li>
																	</ul>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<div class="supplier-price__notes">
							<p class="supplier-price__note"><i>Вы можете отправлять ответы на неограниченное колличество
									заказов.</i></p>
							<p class="supplier-price__note"><i>Продавец оплачивает предоставление контактных данных
									<b>только тогда,
										когда
										Покупатель выберет его.</b> Сумма оплаты за контакт видна при отправке
									предложения
									Покупателю.</i></p>
						</div>
					</div>
				</section><!-- end supplier-price -->
				<!-- homepage-bids -->
				<section class="homepage-bids">
					<div class="container">
						<h2 class="homepage__title homepage__title--decored homepage-bids__title">Заказы покупателей
						</h2>
						<p class="homepage-bids__desc">База заказов на сервисе tamtem.ru регулярно обновляется. Мы
							работаем с
							заказчиками
							по всей России.</p>
						<ul class="homepage-bids__list">
							<li class="homepage-bids__item"><a class="card">
									<div class="card-header">
										<div class="card-header-items-wrap">
											<h3 class="card-header-title">Заказ:&nbsp; наименование</h3>
											<div class="card-header-icons">
												<div class="line">
													<div class="card-infograph" title="Предложения"><img
															src="images/home/icon-user.svg"><span
															class="text-small">0</span></div>
													<div class="card-infograph" title="Просмотры"><img
															src="images/home/icon-eye.svg"><span
															class="text-small">0</span></div>
												</div>
											</div>
										</div>
									</div>
									<div class="card-body">
										<div class="card-body-descr"><span>Тип
												сделки</span><span>Объем</span><span>Минимальная
												партия</span>
										</div>
										<p class="card-body-descr">Описание</p>
									</div>
									<div class="card-footer">
										<div class="card-footer-content">
											<div class="card-infograph"><img src="images/home/icon-map-pin.svg"><span
													class="text-small">Регион</span>
											</div>
											<div class="card-infograph">
												<p class="deal-budget">Бюджет ₽</p>
											</div>
										</div>
									</div>
								</a></li>
							<li class="homepage-bids__item"><a class="card">
									<div class="card-header">
										<div class="card-header-items-wrap">
											<h3 class="card-header-title">Заказ:&nbsp; наименование</h3>
											<div class="card-header-icons">
												<div class="line">
													<div class="card-infograph" title="Предложения"><img
															src="images/home/icon-user.svg"><span
															class="text-small">0</span></div>
													<div class="card-infograph" title="Просмотры"><img
															src="images/home/icon-eye.svg"><span
															class="text-small">0</span></div>
												</div>
											</div>
										</div>
									</div>
									<div class="card-body">
										<div class="card-body-descr"><span>Тип
												сделки</span><span>Объем</span><span>Минимальная
												партия</span>
										</div>
										<p class="card-body-descr">Описание</p>
									</div>
									<div class="card-footer">
										<div class="card-footer-content">
											<div class="card-infograph"><img src="images/home/icon-map-pin.svg"><span
													class="text-small">Регион</span>
											</div>
											<div class="card-infograph">
												<p class="deal-budget">Бюджет ₽</p>
											</div>
										</div>
									</div>
								</a></li>
							<li class="homepage-bids__item"><a class="card">
									<div class="card-header">
										<div class="card-header-items-wrap">
											<h3 class="card-header-title">Заказ:&nbsp; наименование</h3>
											<div class="card-header-icons">
												<div class="line">
													<div class="card-infograph" title="Предложения"><img
															src="images/home/icon-user.svg"><span
															class="text-small">0</span></div>
													<div class="card-infograph" title="Просмотры"><img
															src="images/home/icon-eye.svg"><span
															class="text-small">0</span></div>
												</div>
											</div>
										</div>
									</div>
									<div class="card-body">
										<div class="card-body-descr"><span>Тип
												сделки</span><span>Объем</span><span>Минимальная
												партия</span>
										</div>
										<p class="card-body-descr">Описание</p>
									</div>
									<div class="card-footer">
										<div class="card-footer-content">
											<div class="card-infograph"><img src="images/home/icon-map-pin.svg"><span
													class="text-small">Регион</span>
											</div>
											<div class="card-infograph">
												<p class="deal-budget">Бюджет ₽</p>
											</div>
										</div>
									</div>
								</a></li>
						</ul>
					</div>
				</section><!-- end homepage-bids -->
				<!-- find-new-cl -->
				<section class="find-new-cl">
					<div class="container">
						<div class="find-new-cl__inner">
							<div class="find-new-cl__content">
								<h2 class="find-new-cl__title">Хотите найти новых клиентов?</h2>
								<p class="find-new-cl__desc">отвечайте на заказы</p>
								<p class="find-new-cl__note">и размещайте бесплатно свои объявления</p>
								@if (Cookie::has('api_auth'))
									<a class="find-new-cl__button" href="/bids?page=1&per_page=12&type_deal=buy&date_published=desc" onclick="ym(76387882,'reachGoal','nspostavniz')">Начать сейчас</a>
								@else
									<a class="find-new-cl__button" href="/?itm=signup" onclick="ym(76387882,'reachGoal','nspostavniz')">Начать сейчас</a>
								@endif
							</div>
							<div class="find-new-cl__illustration"><img src="images/home/find-new-customers-pic.png"></div>
						</div>
					</div>
				</section><!-- end find-new-cl -->
				<!-- homepage-reviews -->
				<section class="homepage-reviews">
					<div class="container">
						<h2 class="homepage__title homepage-reviews__title">СМИ о нас</h2>
						<div class="homepage-reviews__wrapper" id="slider">
							<ul class="slider-bar homepage-reviews__list" id="reviews">
								<li class="homepage-reviews__box"><a class="homepage-reviews__item"
										href="https://wall.wayxar.ru/startups/kak_effektivnee_rabotat_s_poiskom_zakazchikov_i_postavshchikov">
										<p class="homepage-reviews__text">О нашем проекте пишут в новостном издании и
											рассказывают о
											работе
											сервиса для заказчиков по поиску поставщиков</p>
										<p class="homepage-reviews__link"><span>Читать</span><i>
												<iconLongArrow></iconLongArrow>
											</i><span class="homepage-reviews__resname">wall.wayxar.ru</span></p>
									</a></li>
								<li class="homepage-reviews__box"><a class="homepage-reviews__item"
										href="https://www.e-xecutive.ru/career/hr-management/1991542-kak-ne-pereplatit-programmistu">
										<p class="homepage-reviews__text">Наш технический директор рассказывает, как
											правильно
											подобрать
											программистов. Экспертное мнение для журнала e-xecutive.ru.</p>
										<p class="homepage-reviews__link"><span>Читать</span><i>
												<iconLongArrow></iconLongArrow>
											</i><span class="homepage-reviews__resname">e-xecutive.ru</span></p>
									</a></li>
								<li class="homepage-reviews__box"><a class="homepage-reviews__item"
										href="https://businesstory.ru/my-soedinjaem-biznes-i-obshhestvo-istorija-platformy-tamtem-ru/">
										<p class="homepage-reviews__text">Артём Ковалев, генеральный директор ООО
											“Акстек” (интернет
											- сервис
											agent.tamtem.ru), рассказал ВS, каким должен быть социальный бизнес, почему
											для малого
											бизнеса важны
											рекомендации</p>
										<p class="homepage-reviews__link"><span>Читать</span><i>
												<iconLongArrow></iconLongArrow>
											</i><span class="homepage-reviews__resname">businesstory.ru</span></p>
									</a></li>
							</ul>
							<ul class="slider-controls homepage-reviews__controls">
								<li>
									<button class="slider-prev"><svg xmlns="http://www.w3.org/2000/svg" width="24"
											height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
											stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
											class="feather feather-chevron-left">
											<polyline points="15 18 9 12 15 6"></polyline>
										</svg></button>
								</li>
								<li>
									<button class="slider-next"><svg xmlns="http://www.w3.org/2000/svg" width="24"
											height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
											stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
											class="feather feather-chevron-right">
											<polyline points="9 18 15 12 9 6"></polyline>
										</svg></button>
								</li>
							</ul>
						</div>
					</div>
				</section><!-- end homepage-reviews -->

				<!-- homepage-feedbacks -->
				<section class="homepage-feedbacks">
					<div class="container">
						<h2 class="homepage-feedbacks__title">Что говорят клиенты о нас</h2>
						<ul class="homepage-feedbacks__list">
							<li class="homepage-feedbacks__item">
								<div class="homepage-feedbacks__card feedback-card">
									<p class="feedback-card__name">Степан Орлов</p>
									<div class="feedback-card__stars">
										<ul class="rate">
											<li class="rate__item rate__item--active"><svg
													xmlns="http://www.w3.org/2000/svg"
													xmlns:xlink="http://www.w3.org/1999/xlink" width="20.758"
													height="19.779" viewBox="0 0 20.758 19.779">
													<defs>
														<linearGradient id="linear-gradient" x1="0.5" y1="0.02" x2="0.5"
															y2="1.096" gradientUnits="objectBoundingBox">
															<stop offset="0" stop-color="#2fc06e" />
															<stop offset="1" stop-color="#48c3c2" />
														</linearGradient>
													</defs>
													<g id="Group_23596" data-name="Group 23596"
														transform="translate(0)">
														<path id="Path_42004" data-name="Path 42004"
															d="M1149.979,679.218c.261-.538.688-.538.949,0l2.387,4.922a2.281,2.281,0,0,0,1.551,1.128l5.419.75c.592.082.725.488.294.9l-3.943,3.791a2,2,0,0,0-.593,1.824l.962,5.385c.105.59-.24.84-.768.559l-4.824-2.579a2.276,2.276,0,0,0-1.918,0l-4.825,2.579c-.527.281-.872.031-.767-.559l.961-5.385a2.276,2.276,0,0,0-.593-1.824l-3.943-3.791c-.43-.416-.3-.821.294-.9l5.418-.75a2.283,2.283,0,0,0,1.553-1.128Z"
															transform="translate(-1140.075 -678.815)"
															fill="url(#linear-gradient)" />
													</g>
												</svg>
											</li>
											<li class="rate__item rate__item--active"><svg
													xmlns="http://www.w3.org/2000/svg"
													xmlns:xlink="http://www.w3.org/1999/xlink" width="20.758"
													height="19.779" viewBox="0 0 20.758 19.779">
													<defs>
														<linearGradient id="linear-gradient" x1="0.5" y1="0.02" x2="0.5"
															y2="1.096" gradientUnits="objectBoundingBox">
															<stop offset="0" stop-color="#2fc06e" />
															<stop offset="1" stop-color="#48c3c2" />
														</linearGradient>
													</defs>
													<g id="Group_23596" data-name="Group 23596"
														transform="translate(0)">
														<path id="Path_42004" data-name="Path 42004"
															d="M1149.979,679.218c.261-.538.688-.538.949,0l2.387,4.922a2.281,2.281,0,0,0,1.551,1.128l5.419.75c.592.082.725.488.294.9l-3.943,3.791a2,2,0,0,0-.593,1.824l.962,5.385c.105.59-.24.84-.768.559l-4.824-2.579a2.276,2.276,0,0,0-1.918,0l-4.825,2.579c-.527.281-.872.031-.767-.559l.961-5.385a2.276,2.276,0,0,0-.593-1.824l-3.943-3.791c-.43-.416-.3-.821.294-.9l5.418-.75a2.283,2.283,0,0,0,1.553-1.128Z"
															transform="translate(-1140.075 -678.815)"
															fill="url(#linear-gradient)" />
													</g>
												</svg>
											</li>
											<li class="rate__item rate__item--active"><svg
													xmlns="http://www.w3.org/2000/svg"
													xmlns:xlink="http://www.w3.org/1999/xlink" width="20.758"
													height="19.779" viewBox="0 0 20.758 19.779">
													<defs>
														<linearGradient id="linear-gradient" x1="0.5" y1="0.02" x2="0.5"
															y2="1.096" gradientUnits="objectBoundingBox">
															<stop offset="0" stop-color="#2fc06e" />
															<stop offset="1" stop-color="#48c3c2" />
														</linearGradient>
													</defs>
													<g id="Group_23596" data-name="Group 23596"
														transform="translate(0)">
														<path id="Path_42004" data-name="Path 42004"
															d="M1149.979,679.218c.261-.538.688-.538.949,0l2.387,4.922a2.281,2.281,0,0,0,1.551,1.128l5.419.75c.592.082.725.488.294.9l-3.943,3.791a2,2,0,0,0-.593,1.824l.962,5.385c.105.59-.24.84-.768.559l-4.824-2.579a2.276,2.276,0,0,0-1.918,0l-4.825,2.579c-.527.281-.872.031-.767-.559l.961-5.385a2.276,2.276,0,0,0-.593-1.824l-3.943-3.791c-.43-.416-.3-.821.294-.9l5.418-.75a2.283,2.283,0,0,0,1.553-1.128Z"
															transform="translate(-1140.075 -678.815)"
															fill="url(#linear-gradient)" />
													</g>
												</svg>
											</li>
											<li class="rate__item rate__item--active"><svg
													xmlns="http://www.w3.org/2000/svg"
													xmlns:xlink="http://www.w3.org/1999/xlink" width="20.758"
													height="19.779" viewBox="0 0 20.758 19.779">
													<defs>
														<linearGradient id="linear-gradient" x1="0.5" y1="0.02" x2="0.5"
															y2="1.096" gradientUnits="objectBoundingBox">
															<stop offset="0" stop-color="#2fc06e" />
															<stop offset="1" stop-color="#48c3c2" />
														</linearGradient>
													</defs>
													<g id="Group_23596" data-name="Group 23596"
														transform="translate(0)">
														<path id="Path_42004" data-name="Path 42004"
															d="M1149.979,679.218c.261-.538.688-.538.949,0l2.387,4.922a2.281,2.281,0,0,0,1.551,1.128l5.419.75c.592.082.725.488.294.9l-3.943,3.791a2,2,0,0,0-.593,1.824l.962,5.385c.105.59-.24.84-.768.559l-4.824-2.579a2.276,2.276,0,0,0-1.918,0l-4.825,2.579c-.527.281-.872.031-.767-.559l.961-5.385a2.276,2.276,0,0,0-.593-1.824l-3.943-3.791c-.43-.416-.3-.821.294-.9l5.418-.75a2.283,2.283,0,0,0,1.553-1.128Z"
															transform="translate(-1140.075 -678.815)"
															fill="url(#linear-gradient)" />
													</g>
												</svg>
											</li>
											<li class="rate__item rate__item--active"><svg
													xmlns="http://www.w3.org/2000/svg"
													xmlns:xlink="http://www.w3.org/1999/xlink" width="20.758"
													height="19.779" viewBox="0 0 20.758 19.779">
													<defs>
														<linearGradient id="linear-gradient" x1="0.5" y1="0.02" x2="0.5"
															y2="1.096" gradientUnits="objectBoundingBox">
															<stop offset="0" stop-color="#2fc06e" />
															<stop offset="1" stop-color="#48c3c2" />
														</linearGradient>
													</defs>
													<g id="Group_23596" data-name="Group 23596"
														transform="translate(0)">
														<path id="Path_42004" data-name="Path 42004"
															d="M1149.979,679.218c.261-.538.688-.538.949,0l2.387,4.922a2.281,2.281,0,0,0,1.551,1.128l5.419.75c.592.082.725.488.294.9l-3.943,3.791a2,2,0,0,0-.593,1.824l.962,5.385c.105.59-.24.84-.768.559l-4.824-2.579a2.276,2.276,0,0,0-1.918,0l-4.825,2.579c-.527.281-.872.031-.767-.559l.961-5.385a2.276,2.276,0,0,0-.593-1.824l-3.943-3.791c-.43-.416-.3-.821.294-.9l5.418-.75a2.283,2.283,0,0,0,1.553-1.128Z"
															transform="translate(-1140.075 -678.815)"
															fill="url(#linear-gradient)" />
													</g>
												</svg>
											</li>
										</ul>
									</div>
									<div class="feedback-card__text">
										<p>Отзывы писать не умею, но по просьбе менеджера напишу, сильно понравилось как
											ребята
											сработали. Я
											выращиваю картофель. Свое КФХ. Зашел на сайт, нажал на заказы, смотрю, что
											требуется
											картошка, ну я и
											нажал, просмотрел условия, все устраивает. Отправил отклик, смутило сначала,
											что нужно
											оплачивать
											контакт, но менеджер объяснила, что существует гарантия возврата, если
											сделка сорвалась
											по вине
											заказчика. Оплатил, получил контакты-работаем. За небольшую сумму получил
											постоянного
											заказчика с
											хорошими объемами, которого бы сам никогда не нашел. Спасибо менеджеру Елене
											за терпение
										</p>
									</div>
								</div>
							</li>
							<li class="homepage-feedbacks__item">
								<div class="homepage-feedbacks__card feedback-card">
									<p class="feedback-card__name">Кравцова Ирина Сергеевна</p>
									<div class="feedback-card__stars">
										<ul class="rate">
											<li class="rate__item rate__item--active"><svg
													xmlns="http://www.w3.org/2000/svg"
													xmlns:xlink="http://www.w3.org/1999/xlink" width="20.758"
													height="19.779" viewBox="0 0 20.758 19.779">
													<defs>
														<linearGradient id="linear-gradient" x1="0.5" y1="0.02" x2="0.5"
															y2="1.096" gradientUnits="objectBoundingBox">
															<stop offset="0" stop-color="#2fc06e" />
															<stop offset="1" stop-color="#48c3c2" />
														</linearGradient>
													</defs>
													<g id="Group_23596" data-name="Group 23596"
														transform="translate(0)">
														<path id="Path_42004" data-name="Path 42004"
															d="M1149.979,679.218c.261-.538.688-.538.949,0l2.387,4.922a2.281,2.281,0,0,0,1.551,1.128l5.419.75c.592.082.725.488.294.9l-3.943,3.791a2,2,0,0,0-.593,1.824l.962,5.385c.105.59-.24.84-.768.559l-4.824-2.579a2.276,2.276,0,0,0-1.918,0l-4.825,2.579c-.527.281-.872.031-.767-.559l.961-5.385a2.276,2.276,0,0,0-.593-1.824l-3.943-3.791c-.43-.416-.3-.821.294-.9l5.418-.75a2.283,2.283,0,0,0,1.553-1.128Z"
															transform="translate(-1140.075 -678.815)"
															fill="url(#linear-gradient)" />
													</g>
												</svg>
											</li>
											<li class="rate__item rate__item--active"><svg
													xmlns="http://www.w3.org/2000/svg"
													xmlns:xlink="http://www.w3.org/1999/xlink" width="20.758"
													height="19.779" viewBox="0 0 20.758 19.779">
													<defs>
														<linearGradient id="linear-gradient" x1="0.5" y1="0.02" x2="0.5"
															y2="1.096" gradientUnits="objectBoundingBox">
															<stop offset="0" stop-color="#2fc06e" />
															<stop offset="1" stop-color="#48c3c2" />
														</linearGradient>
													</defs>
													<g id="Group_23596" data-name="Group 23596"
														transform="translate(0)">
														<path id="Path_42004" data-name="Path 42004"
															d="M1149.979,679.218c.261-.538.688-.538.949,0l2.387,4.922a2.281,2.281,0,0,0,1.551,1.128l5.419.75c.592.082.725.488.294.9l-3.943,3.791a2,2,0,0,0-.593,1.824l.962,5.385c.105.59-.24.84-.768.559l-4.824-2.579a2.276,2.276,0,0,0-1.918,0l-4.825,2.579c-.527.281-.872.031-.767-.559l.961-5.385a2.276,2.276,0,0,0-.593-1.824l-3.943-3.791c-.43-.416-.3-.821.294-.9l5.418-.75a2.283,2.283,0,0,0,1.553-1.128Z"
															transform="translate(-1140.075 -678.815)"
															fill="url(#linear-gradient)" />
													</g>
												</svg>
											</li>
											<li class="rate__item rate__item--active"><svg
													xmlns="http://www.w3.org/2000/svg"
													xmlns:xlink="http://www.w3.org/1999/xlink" width="20.758"
													height="19.779" viewBox="0 0 20.758 19.779">
													<defs>
														<linearGradient id="linear-gradient" x1="0.5" y1="0.02" x2="0.5"
															y2="1.096" gradientUnits="objectBoundingBox">
															<stop offset="0" stop-color="#2fc06e" />
															<stop offset="1" stop-color="#48c3c2" />
														</linearGradient>
													</defs>
													<g id="Group_23596" data-name="Group 23596"
														transform="translate(0)">
														<path id="Path_42004" data-name="Path 42004"
															d="M1149.979,679.218c.261-.538.688-.538.949,0l2.387,4.922a2.281,2.281,0,0,0,1.551,1.128l5.419.75c.592.082.725.488.294.9l-3.943,3.791a2,2,0,0,0-.593,1.824l.962,5.385c.105.59-.24.84-.768.559l-4.824-2.579a2.276,2.276,0,0,0-1.918,0l-4.825,2.579c-.527.281-.872.031-.767-.559l.961-5.385a2.276,2.276,0,0,0-.593-1.824l-3.943-3.791c-.43-.416-.3-.821.294-.9l5.418-.75a2.283,2.283,0,0,0,1.553-1.128Z"
															transform="translate(-1140.075 -678.815)"
															fill="url(#linear-gradient)" />
													</g>
												</svg>
											</li>
											<li class="rate__item rate__item--active"><svg
													xmlns="http://www.w3.org/2000/svg"
													xmlns:xlink="http://www.w3.org/1999/xlink" width="20.758"
													height="19.779" viewBox="0 0 20.758 19.779">
													<defs>
														<linearGradient id="linear-gradient" x1="0.5" y1="0.02" x2="0.5"
															y2="1.096" gradientUnits="objectBoundingBox">
															<stop offset="0" stop-color="#2fc06e" />
															<stop offset="1" stop-color="#48c3c2" />
														</linearGradient>
													</defs>
													<g id="Group_23596" data-name="Group 23596"
														transform="translate(0)">
														<path id="Path_42004" data-name="Path 42004"
															d="M1149.979,679.218c.261-.538.688-.538.949,0l2.387,4.922a2.281,2.281,0,0,0,1.551,1.128l5.419.75c.592.082.725.488.294.9l-3.943,3.791a2,2,0,0,0-.593,1.824l.962,5.385c.105.59-.24.84-.768.559l-4.824-2.579a2.276,2.276,0,0,0-1.918,0l-4.825,2.579c-.527.281-.872.031-.767-.559l.961-5.385a2.276,2.276,0,0,0-.593-1.824l-3.943-3.791c-.43-.416-.3-.821.294-.9l5.418-.75a2.283,2.283,0,0,0,1.553-1.128Z"
															transform="translate(-1140.075 -678.815)"
															fill="url(#linear-gradient)" />
													</g>
												</svg>
											</li>
											<li class="rate__item rate__item--unactive"><svg
													xmlns="http://www.w3.org/2000/svg"
													xmlns:xlink="http://www.w3.org/1999/xlink" width="20.758"
													height="19.779" viewBox="0 0 20.758 19.779">
													<defs>
														<linearGradient id="linear-gradient" x1="0.5" y1="0.02" x2="0.5"
															y2="1.096" gradientUnits="objectBoundingBox">
															<stop offset="0" stop-color="#2fc06e" />
															<stop offset="1" stop-color="#48c3c2" />
														</linearGradient>
													</defs>
													<g id="Group_23596" data-name="Group 23596"
														transform="translate(0)">
														<path id="Path_42004" data-name="Path 42004"
															d="M1149.979,679.218c.261-.538.688-.538.949,0l2.387,4.922a2.281,2.281,0,0,0,1.551,1.128l5.419.75c.592.082.725.488.294.9l-3.943,3.791a2,2,0,0,0-.593,1.824l.962,5.385c.105.59-.24.84-.768.559l-4.824-2.579a2.276,2.276,0,0,0-1.918,0l-4.825,2.579c-.527.281-.872.031-.767-.559l.961-5.385a2.276,2.276,0,0,0-.593-1.824l-3.943-3.791c-.43-.416-.3-.821.294-.9l5.418-.75a2.283,2.283,0,0,0,1.553-1.128Z"
															transform="translate(-1140.075 -678.815)"
															fill="url(#linear-gradient)" />
													</g>
												</svg>
											</li>
										</ul>
									</div>
									<div class="feedback-card__text">
										<p>Я являюсь поставщиком в торговые сети. У меня есть производители, у которых я
											регулярно
											закупаюсь.
											Когда производство у одного из них встало, я не знала куда бежать, сроки
											горят, график
											поставок с сетью
											согласован. Я понимала, что попадаю на штрафы. Обзвонила своих поставщиков-
											никто не
											смог дать
											дополнительный объем. Как любой современный человек сделала запрос в
											интернете- вышла на
											сайт tamtem.ru
										</p>
										<p>Зашла на сайт, удивилась, что нет объявлений о продаже. Мне предложили
											оставить заказ, я
											прописала
											требования, закрыла сайт и продолжила искать поставщиков дальше. Очень
											удивилась, когда
											через пару часов
											на почту пришло письмо от сервиса о том, что на мой заказ есть предложения.
											Перешла на
											сайт. Выбрала
											поставщика, он полностью подошел под мои условия, с ним, кстати, мы до сих
											пор
											сотрудничаем. По итогу:
											работой сервиса довольна, подбор поставщиков на уровне</p>
									</div>
								</div>
							</li>
							<li class="homepage-feedbacks__item">
								<div class="homepage-feedbacks__card feedback-card">
									<p class="feedback-card__name">Петр Серебряков</p>
									<div class="feedback-card__stars">
										<ul class="rate">
											<li class="rate__item rate__item--active"><svg
													xmlns="http://www.w3.org/2000/svg"
													xmlns:xlink="http://www.w3.org/1999/xlink" width="20.758"
													height="19.779" viewBox="0 0 20.758 19.779">
													<defs>
														<linearGradient id="linear-gradient" x1="0.5" y1="0.02" x2="0.5"
															y2="1.096" gradientUnits="objectBoundingBox">
															<stop offset="0" stop-color="#2fc06e" />
															<stop offset="1" stop-color="#48c3c2" />
														</linearGradient>
													</defs>
													<g id="Group_23596" data-name="Group 23596"
														transform="translate(0)">
														<path id="Path_42004" data-name="Path 42004"
															d="M1149.979,679.218c.261-.538.688-.538.949,0l2.387,4.922a2.281,2.281,0,0,0,1.551,1.128l5.419.75c.592.082.725.488.294.9l-3.943,3.791a2,2,0,0,0-.593,1.824l.962,5.385c.105.59-.24.84-.768.559l-4.824-2.579a2.276,2.276,0,0,0-1.918,0l-4.825,2.579c-.527.281-.872.031-.767-.559l.961-5.385a2.276,2.276,0,0,0-.593-1.824l-3.943-3.791c-.43-.416-.3-.821.294-.9l5.418-.75a2.283,2.283,0,0,0,1.553-1.128Z"
															transform="translate(-1140.075 -678.815)"
															fill="url(#linear-gradient)" />
													</g>
												</svg>
											</li>
											<li class="rate__item rate__item--active"><svg
													xmlns="http://www.w3.org/2000/svg"
													xmlns:xlink="http://www.w3.org/1999/xlink" width="20.758"
													height="19.779" viewBox="0 0 20.758 19.779">
													<defs>
														<linearGradient id="linear-gradient" x1="0.5" y1="0.02" x2="0.5"
															y2="1.096" gradientUnits="objectBoundingBox">
															<stop offset="0" stop-color="#2fc06e" />
															<stop offset="1" stop-color="#48c3c2" />
														</linearGradient>
													</defs>
													<g id="Group_23596" data-name="Group 23596"
														transform="translate(0)">
														<path id="Path_42004" data-name="Path 42004"
															d="M1149.979,679.218c.261-.538.688-.538.949,0l2.387,4.922a2.281,2.281,0,0,0,1.551,1.128l5.419.75c.592.082.725.488.294.9l-3.943,3.791a2,2,0,0,0-.593,1.824l.962,5.385c.105.59-.24.84-.768.559l-4.824-2.579a2.276,2.276,0,0,0-1.918,0l-4.825,2.579c-.527.281-.872.031-.767-.559l.961-5.385a2.276,2.276,0,0,0-.593-1.824l-3.943-3.791c-.43-.416-.3-.821.294-.9l5.418-.75a2.283,2.283,0,0,0,1.553-1.128Z"
															transform="translate(-1140.075 -678.815)"
															fill="url(#linear-gradient)" />
													</g>
												</svg>
											</li>
											<li class="rate__item rate__item--active"><svg
													xmlns="http://www.w3.org/2000/svg"
													xmlns:xlink="http://www.w3.org/1999/xlink" width="20.758"
													height="19.779" viewBox="0 0 20.758 19.779">
													<defs>
														<linearGradient id="linear-gradient" x1="0.5" y1="0.02" x2="0.5"
															y2="1.096" gradientUnits="objectBoundingBox">
															<stop offset="0" stop-color="#2fc06e" />
															<stop offset="1" stop-color="#48c3c2" />
														</linearGradient>
													</defs>
													<g id="Group_23596" data-name="Group 23596"
														transform="translate(0)">
														<path id="Path_42004" data-name="Path 42004"
															d="M1149.979,679.218c.261-.538.688-.538.949,0l2.387,4.922a2.281,2.281,0,0,0,1.551,1.128l5.419.75c.592.082.725.488.294.9l-3.943,3.791a2,2,0,0,0-.593,1.824l.962,5.385c.105.59-.24.84-.768.559l-4.824-2.579a2.276,2.276,0,0,0-1.918,0l-4.825,2.579c-.527.281-.872.031-.767-.559l.961-5.385a2.276,2.276,0,0,0-.593-1.824l-3.943-3.791c-.43-.416-.3-.821.294-.9l5.418-.75a2.283,2.283,0,0,0,1.553-1.128Z"
															transform="translate(-1140.075 -678.815)"
															fill="url(#linear-gradient)" />
													</g>
												</svg>
											</li>
											<li class="rate__item rate__item--active"><svg
													xmlns="http://www.w3.org/2000/svg"
													xmlns:xlink="http://www.w3.org/1999/xlink" width="20.758"
													height="19.779" viewBox="0 0 20.758 19.779">
													<defs>
														<linearGradient id="linear-gradient" x1="0.5" y1="0.02" x2="0.5"
															y2="1.096" gradientUnits="objectBoundingBox">
															<stop offset="0" stop-color="#2fc06e" />
															<stop offset="1" stop-color="#48c3c2" />
														</linearGradient>
													</defs>
													<g id="Group_23596" data-name="Group 23596"
														transform="translate(0)">
														<path id="Path_42004" data-name="Path 42004"
															d="M1149.979,679.218c.261-.538.688-.538.949,0l2.387,4.922a2.281,2.281,0,0,0,1.551,1.128l5.419.75c.592.082.725.488.294.9l-3.943,3.791a2,2,0,0,0-.593,1.824l.962,5.385c.105.59-.24.84-.768.559l-4.824-2.579a2.276,2.276,0,0,0-1.918,0l-4.825,2.579c-.527.281-.872.031-.767-.559l.961-5.385a2.276,2.276,0,0,0-.593-1.824l-3.943-3.791c-.43-.416-.3-.821.294-.9l5.418-.75a2.283,2.283,0,0,0,1.553-1.128Z"
															transform="translate(-1140.075 -678.815)"
															fill="url(#linear-gradient)" />
													</g>
												</svg>
											</li>
											<li class="rate__item rate__item--unactive"><svg
													xmlns="http://www.w3.org/2000/svg"
													xmlns:xlink="http://www.w3.org/1999/xlink" width="20.758"
													height="19.779" viewBox="0 0 20.758 19.779">
													<defs>
														<linearGradient id="linear-gradient" x1="0.5" y1="0.02" x2="0.5"
															y2="1.096" gradientUnits="objectBoundingBox">
															<stop offset="0" stop-color="#2fc06e" />
															<stop offset="1" stop-color="#48c3c2" />
														</linearGradient>
													</defs>
													<g id="Group_23596" data-name="Group 23596"
														transform="translate(0)">
														<path id="Path_42004" data-name="Path 42004"
															d="M1149.979,679.218c.261-.538.688-.538.949,0l2.387,4.922a2.281,2.281,0,0,0,1.551,1.128l5.419.75c.592.082.725.488.294.9l-3.943,3.791a2,2,0,0,0-.593,1.824l.962,5.385c.105.59-.24.84-.768.559l-4.824-2.579a2.276,2.276,0,0,0-1.918,0l-4.825,2.579c-.527.281-.872.031-.767-.559l.961-5.385a2.276,2.276,0,0,0-.593-1.824l-3.943-3.791c-.43-.416-.3-.821.294-.9l5.418-.75a2.283,2.283,0,0,0,1.553-1.128Z"
															transform="translate(-1140.075 -678.815)"
															fill="url(#linear-gradient)" />
													</g>
												</svg>
											</li>
										</ul>
									</div>
									<div class="feedback-card__text">
										<p>Для фирмы нужны были подарки для контрагентов. Большие объемы и требования к
											полиграфии
											по ТЗ. Времени
											на поиск тех, кто сделает ручки, календари и блокноты и прочую мелочь, у
											меня не было,
											друг посоветовал
											разместить заказ на сайте tamtem.ru Сайт понятный, менеджеры отзывчивые,
											заказ разместил
											и через 3 дня
											уже созванивался с менеджером типографии, которая взяла мой заказ. Через
											неделю все было
											у меня. Быстро
											и очень удобно</p>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</section><!-- end homepage-feedbacks -->
				<!-- homepage-faq -->
				<section class="homepage-faq">
					<div class="container">
						<h2 class="homepage-faq__title">Часто задаваемые вопросы</h2>
						<section class="answers">
							<ul class="answers__list">
								<li class="answers__item answer">
									<div class="answer__head">
										<p class="answer__name">Как работает tamtem.ru?</p><span class="answer__icon"><i
												class="answer__icon-closed"><svg xmlns="http://www.w3.org/2000/svg"
													width="24" height="24" viewBox="0 0 24 24" fill="none"
													stroke="currentColor" stroke-width="2" stroke-linecap="round"
													stroke-linejoin="round" class="feather feather-plus">
													<line x1="12" y1="5" x2="12" y2="19"></line>
													<line x1="5" y1="12" x2="19" y2="12"></line>
												</svg></i><i class="answer__icon-opened"><svg
													xmlns="http://www.w3.org/2000/svg" width="24" height="24"
													viewBox="0 0 24 24" fill="none" stroke="currentColor"
													stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
													class="feather feather-minus">
													<line x1="5" y1="12" x2="19" y2="12"></line>
												</svg></i></span>
									</div>
									<div class="answer__text">
										<p>tamtem.ru - это удобный сервис для поставщиков и закупщиков товаров оптом,
											который
											работает по системе
											краудсорсинга.</p>
									</div>
								</li>
								<li class="answers__item answer">
									<div class="answer__head">
										<p class="answer__name">Как создать заказ?</p><span class="answer__icon"><i
												class="answer__icon-closed"><svg xmlns="http://www.w3.org/2000/svg"
													width="24" height="24" viewBox="0 0 24 24" fill="none"
													stroke="currentColor" stroke-width="2" stroke-linecap="round"
													stroke-linejoin="round" class="feather feather-plus">
													<line x1="12" y1="5" x2="12" y2="19"></line>
													<line x1="5" y1="12" x2="19" y2="12"></line>
												</svg></i><i class="answer__icon-opened"><svg
													xmlns="http://www.w3.org/2000/svg" width="24" height="24"
													viewBox="0 0 24 24" fill="none" stroke="currentColor"
													stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
													class="feather feather-minus">
													<line x1="5" y1="12" x2="19" y2="12"></line>
												</svg></i></span>
									</div>
									<div class="answer__text">
										<p>Краудсо́рсинг — мобилизация ресурсов людей посредством информационных
											технологий.</p>
									</div>
								</li>
								<li class="answers__item answer">
									<div class="answer__head">
										<p class="answer__name">Как выбрать победителя?</p><span class="answer__icon"><i
												class="answer__icon-closed"><svg xmlns="http://www.w3.org/2000/svg"
													width="24" height="24" viewBox="0 0 24 24" fill="none"
													stroke="currentColor" stroke-width="2" stroke-linecap="round"
													stroke-linejoin="round" class="feather feather-plus">
													<line x1="12" y1="5" x2="12" y2="19"></line>
													<line x1="5" y1="12" x2="19" y2="12"></line>
												</svg></i><i class="answer__icon-opened"><svg
													xmlns="http://www.w3.org/2000/svg" width="24" height="24"
													viewBox="0 0 24 24" fill="none" stroke="currentColor"
													stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
													class="feather feather-minus">
													<line x1="5" y1="12" x2="19" y2="12"></line>
												</svg></i></span>
									</div>
									<div class="answer__text">
										<p>Краудсо́рсинг — мобилизация ресурсов людей посредством информационных
											технологий.</p>
									</div>
								</li>
								<li class="answers__item answer">
									<div class="answer__head">
										<p class="answer__name">Можно ли отредактировать заказ?</p><span
											class="answer__icon"><i class="answer__icon-closed"><svg
													xmlns="http://www.w3.org/2000/svg" width="24" height="24"
													viewBox="0 0 24 24" fill="none" stroke="currentColor"
													stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
													class="feather feather-plus">
													<line x1="12" y1="5" x2="12" y2="19"></line>
													<line x1="5" y1="12" x2="19" y2="12"></line>
												</svg></i><i class="answer__icon-opened"><svg
													xmlns="http://www.w3.org/2000/svg" width="24" height="24"
													viewBox="0 0 24 24" fill="none" stroke="currentColor"
													stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
													class="feather feather-minus">
													<line x1="5" y1="12" x2="19" y2="12"></line>
												</svg></i></span>
									</div>
									<div class="answer__text">
										<p>Краудсо́рсинг — мобилизация ресурсов людей посредством информационных
											технологий.</p>
									</div>
								</li>
							</ul>
						</section>
						<div class="homepage-faq__questions">
							<h3 class="homepage-faq__questions-title">Остались вопросы?</h3>
							<p class="homepage-faq__questions-here"><a href="/faq" onclick="ym(76387882,'reachGoal','zdesvopros')">Здесь</a> Вы найдете больше информации.
							</p>
							<p class="homepage-faq__questions-desc">Наш сервис делает работу простой и безопасной.
								Экономьте время и
								средства на поиске заказов.</p>
						</div>
					</div>
				</section><!-- end homepage-faq -->
			</section>
		</div>

		<footer class="mainfooter">
			<section class="mainfooter__content">
				<div class="container mainfooter__container">
					<section class="mainfooter__logo"><svg xmlns="http://www.w3.org/2000/svg" width="145.955"
							height="47" viewBox="0 0 145.955 47">
							<g id="Group_16767" data-name="Group 16767" transform="translate(-375 -1777)">
								<g id="Group_16066" data-name="Group 16066" transform="translate(376.621 1777)">
									<path id="Path_17339" data-name="Path 17339"
										d="M166.387,52.043l2.377-.208h.643a1.06,1.06,0,0,1,.772.3,1.015,1.015,0,0,1,.3.763V54.75a.947.947,0,0,1-.3.685,1.332,1.332,0,0,1-.772.373,24.948,24.948,0,0,1-4.615.346q-7.461,0-7.461-7.807V32a1.046,1.046,0,0,1,.3-.772,1.023,1.023,0,0,1,.754-.3h3.887a1.022,1.022,0,0,1,.754.3,1.046,1.046,0,0,1,.3.772v5.8h4.841a1.043,1.043,0,0,1,.754.294,1.019,1.019,0,0,1,.3.764V40.92a1.013,1.013,0,0,1-.3.763,1.038,1.038,0,0,1-.754.3h-4.841v6.368a4.059,4.059,0,0,0,.832,2.733A2.792,2.792,0,0,0,166.387,52.043Z"
										transform="translate(-157.331 -30.926)" fill="#fff" />
									<path id="Path_17340" data-name="Path 17340"
										d="M169.818,52.852a5.172,5.172,0,0,1-2.3-4.512,5.085,5.085,0,0,1,2.3-4.476,10.339,10.339,0,0,1,5.925-1.562h5.743a3.792,3.792,0,0,0-.85-2.827,4.334,4.334,0,0,0-2.976-.8,3.661,3.661,0,0,0-2.983,1.051,2.63,2.63,0,0,1-2.108,1.05h-2.585a1.047,1.047,0,0,1-.773-.3,1.024,1.024,0,0,1-.3-.754q0-4.981,9.456-4.98a10.885,10.885,0,0,1,6.627,1.847,6.642,6.642,0,0,1,2.5,5.717V53.043A1.012,1.012,0,0,1,186.43,54.1h-3.348a1.018,1.018,0,0,1-.764-.3,1.045,1.045,0,0,1-.294-.754V52.5a10.548,10.548,0,0,1-6.324,1.951A10.133,10.133,0,0,1,169.818,52.852Zm4.424-6.021a1.9,1.9,0,0,0-.7,1.526,1.925,1.925,0,0,0,.728,1.545,3.137,3.137,0,0,0,2.064.608,9.545,9.545,0,0,0,5.153-1.562V46.241H176.16A2.889,2.889,0,0,0,174.242,46.832Z"
										transform="translate(-152.771 -29.22)" fill="#fff" />
									<path id="Path_17341" data-name="Path 17341"
										d="M184.263,53.046V36.164a1.052,1.052,0,0,1,.3-.773,1.026,1.026,0,0,1,.756-.3h3.885a1.018,1.018,0,0,1,.764.3,1.064,1.064,0,0,1,.3.773v1.093a11.213,11.213,0,0,1,2.455-1.866,6.049,6.049,0,0,1,2.905-.65,6.774,6.774,0,0,1,6.125,3.331q3.227-3.348,6.62-3.34a7.131,7.131,0,0,1,5.36,2.047q1.969,2.039,1.97,6.307v9.96a1.012,1.012,0,0,1-1.06,1.058h-3.868a1.047,1.047,0,0,1-.773-.3,1.025,1.025,0,0,1-.3-.754v-9.96q0-4.165-3.018-4.147-1.839,0-3.8,2.655a10.782,10.782,0,0,1,.1,1.493v9.96a1.017,1.017,0,0,1-.3.763,1.038,1.038,0,0,1-.754.3h-3.869a1.044,1.044,0,0,1-.772-.3,1.025,1.025,0,0,1-.3-.754v-9.96q0-4.165-3.018-4.147-1.926,0-3.7,2.481V53.046a1.018,1.018,0,0,1-.3.763,1.042,1.042,0,0,1-.756.3h-3.885a1.01,1.01,0,0,1-1.058-1.058Z"
										transform="translate(-145.277 -29.222)" fill="#fff" />
									<path id="Path_17342" data-name="Path 17342"
										d="M217.985,52.043l2.377-.208H221a1.06,1.06,0,0,1,.772.3,1.015,1.015,0,0,1,.3.763V54.75a.947.947,0,0,1-.3.685,1.332,1.332,0,0,1-.772.373,24.947,24.947,0,0,1-4.615.346q-7.461,0-7.461-7.807V32a1.046,1.046,0,0,1,.3-.772,1.023,1.023,0,0,1,.754-.3h3.887a1.022,1.022,0,0,1,.754.3,1.046,1.046,0,0,1,.3.772v5.8h4.841a1.04,1.04,0,0,1,.754.294,1.018,1.018,0,0,1,.3.764V40.92a1.013,1.013,0,0,1-.3.763,1.036,1.036,0,0,1-.754.3h-4.841v6.368a4.059,4.059,0,0,0,.832,2.733A2.792,2.792,0,0,0,217.985,52.043Z"
										transform="translate(-134.237 -30.926)" fill="#fff" />
									<path id="Path_17343" data-name="Path 17343"
										d="M221.789,51.914q-2.673-2.532-2.672-7.295t2.688-7.322a10.384,10.384,0,0,1,7.461-2.559q4.773,0,7.443,2.49t2.672,7.14v.989a1.04,1.04,0,0,1-1.076,1.076H225.242a4.582,4.582,0,0,0,1.223,3.105,4.834,4.834,0,0,0,3.357.973,3.912,3.912,0,0,0,3.157-1.042,2.665,2.665,0,0,1,2.1-1.058h2.585a1.038,1.038,0,0,1,1.076,1.076q0,4.981-9.49,4.962Q224.461,54.448,221.789,51.914Zm3.418-9.143h8.067q-.174-4.1-4.017-4.1T225.207,42.771Z"
										transform="translate(-129.678 -29.22)" fill="#fff" />
									<path id="Path_17344" data-name="Path 17344"
										d="M235.321,53.046V36.164a1.047,1.047,0,0,1,.3-.773,1.025,1.025,0,0,1,.754-.3h3.887a1.018,1.018,0,0,1,.764.3,1.069,1.069,0,0,1,.294.773v1.093a11.278,11.278,0,0,1,2.455-1.866,6.059,6.059,0,0,1,2.907-.65,6.774,6.774,0,0,1,6.125,3.331q3.227-3.348,6.618-3.34a7.134,7.134,0,0,1,5.362,2.047q1.969,2.039,1.969,6.307v9.96A1.012,1.012,0,0,1,265.7,54.1h-3.869a1.046,1.046,0,0,1-.772-.3,1.025,1.025,0,0,1-.3-.754v-9.96q0-4.165-3.018-4.147-1.839,0-3.8,2.655a10.787,10.787,0,0,1,.1,1.493v9.96a1.017,1.017,0,0,1-.3.763,1.041,1.041,0,0,1-.754.3h-3.869a1.047,1.047,0,0,1-.773-.3,1.03,1.03,0,0,1-.3-.754v-9.96q0-4.165-3.02-4.147-1.926,0-3.7,2.481V53.046a1.022,1.022,0,0,1-.3.763,1.042,1.042,0,0,1-.756.3h-3.887a1.012,1.012,0,0,1-1.058-1.058Z"
										transform="translate(-122.425 -29.222)" fill="#fff" />
								</g>
								<g id="Group_16599" data-name="Group 16599" transform="translate(16.293 1156.458)">
									<path id="Path_18031" data-name="Path 18031"
										d="M358.736,666.668l1.673-6.764q.707-2.818,4.671-2.818h4.4a.549.549,0,0,1,.4.162.561.561,0,0,1,.164.414v9.052a.559.559,0,0,1-.568.568H367.4a.544.544,0,0,1-.4-.164.55.55,0,0,1-.164-.4v-7.377H365.08a1.583,1.583,0,0,0-1.646,1.367l-1.461,5.889a.921.921,0,0,1-.33.5.847.847,0,0,1-.526.19H359.21a.484.484,0,0,1-.5-.419A.6.6,0,0,1,358.736,666.668Z"
										transform="translate(0 0.069)" fill="#2dc84d" />
									<path id="Path_18032" data-name="Path 18032"
										d="M367.291,666.783v-9.052a.562.562,0,0,1,.164-.414.549.549,0,0,1,.4-.162h2.083a.55.55,0,0,1,.4.162.568.568,0,0,1,.164.414v3.331h1.367a4.258,4.258,0,0,1,1.661-3.047,6.194,6.194,0,0,1,3.782-1.046,5.294,5.294,0,0,1,3.8,1.353,6.127,6.127,0,0,1,0,7.865,5.327,5.327,0,0,1-3.818,1.35,6.085,6.085,0,0,1-3.8-1.084,4.388,4.388,0,0,1-1.642-3.139h-1.348v3.469a.551.551,0,0,1-.164.4.545.545,0,0,1-.4.163h-2.083a.559.559,0,0,1-.568-.568Zm12.038-4.53q0-3.172-2.153-3.172t-2.155,3.172q0,3.172,2.155,3.172T379.329,662.253Z"
										transform="translate(5.045)" fill="#2dc84d" />
									<path id="Path_18033" data-name="Path 18033"
										d="M377.147,666.714v-1.107a.555.555,0,0,1,.576-.576h.726l1.275-5.127q.707-2.818,4.669-2.818h4.223a.561.561,0,0,1,.414.162.567.567,0,0,1,.164.414v9.052a.55.55,0,0,1-.164.4.556.556,0,0,1-.414.164H377.723a.558.558,0,0,1-.414-.164A.549.549,0,0,1,377.147,666.714Zm4.344-1.683h4.484v-5.693h-1.683a1.634,1.634,0,0,0-1.712,1.367Z"
										transform="translate(10.837 0.069)" fill="#2dc84d" />
									<path id="Path_18034" data-name="Path 18034"
										d="M386.176,666.714v-9.052a.566.566,0,0,1,.162-.414.554.554,0,0,1,.4-.162h1.945a.554.554,0,0,1,.576.576v6.113l3.275-6.019a1.217,1.217,0,0,1,.456-.479,1.13,1.13,0,0,1,.6-.19h2.6a.557.557,0,0,1,.578.576v9.052a.541.541,0,0,1-.164.41.565.565,0,0,1-.414.159h-1.943a.54.54,0,0,1-.41-.164.559.559,0,0,1-.159-.4v-6.1l-3.274,6a1.35,1.35,0,0,1-.456.47,1.093,1.093,0,0,1-.6.2h-2.615a.544.544,0,0,1-.567-.568Z"
										transform="translate(16.144 0.069)" fill="#2dc84d" />
									<path id="Path_18035" data-name="Path 18035"
										d="M398.006,664.736a.539.539,0,0,1,.164-.408.551.551,0,0,1,.4-.159h1.4a1.1,1.1,0,0,1,.991.627,1.442,1.442,0,0,0,1.373.629h1.888q1.348,0,1.349-1.107t-1.349-1.1h-2.307a.554.554,0,0,1-.576-.576v-.967a.557.557,0,0,1,.576-.578h2.131a1.357,1.357,0,0,0,.926-.275.921.921,0,0,0,.311-.729.937.937,0,0,0-.311-.735,1.341,1.341,0,0,0-.926-.279h-1.712a1.565,1.565,0,0,0-.67.162,1.1,1.1,0,0,0-.513.47,1.086,1.086,0,0,1-.994.624h-1.388a.564.564,0,0,1-.413-.159.541.541,0,0,1-.164-.41,2.319,2.319,0,0,1,1.224-2.027,5.422,5.422,0,0,1,2.953-.772h1.87a5.814,5.814,0,0,1,3.112.721,2.412,2.412,0,0,1,1.148,2.191A2.2,2.2,0,0,1,407.17,662a2.715,2.715,0,0,1,1.618,2.586,2.473,2.473,0,0,1-1.092,2.247,6.079,6.079,0,0,1-3.252.7h-2.075a5.884,5.884,0,0,1-3.117-.762A2.311,2.311,0,0,1,398.006,664.736Z"
										transform="translate(24.096)" fill="#2dc84d" />
									<path id="Path_18036" data-name="Path 18036"
										d="M405.817,666.714v-9.052a.566.566,0,0,1,.162-.414.549.549,0,0,1,.4-.162h2.083a.545.545,0,0,1,.41.162.57.57,0,0,1,.159.414v3.331h4.074v-3.331a.567.567,0,0,1,.164-.414.561.561,0,0,1,.414-.162h2.074a.545.545,0,0,1,.41.162.577.577,0,0,1,.159.414v9.052a.556.556,0,0,1-.159.41.549.549,0,0,1-.41.159h-2.074a.559.559,0,0,1-.578-.568v-3.469h-4.074v3.469a.541.541,0,0,1-.164.41.553.553,0,0,1-.4.159h-2.083a.541.541,0,0,1-.567-.568Z"
										transform="translate(28.687 0.069)" fill="#2dc84d" />
									<path id="Path_18037" data-name="Path 18037"
										d="M414.748,666.681a2.774,2.774,0,0,1-1.232-2.418,2.727,2.727,0,0,1,1.232-2.4,5.541,5.541,0,0,1,3.177-.838h3.08a2.029,2.029,0,0,0-.457-1.516,2.327,2.327,0,0,0-1.594-.427,1.966,1.966,0,0,0-1.6.562,1.409,1.409,0,0,1-1.13.564h-1.386a.556.556,0,0,1-.414-.164.549.549,0,0,1-.162-.4q0-2.67,5.07-2.669a5.833,5.833,0,0,1,3.553.991,3.557,3.557,0,0,1,1.34,3.064v5.759a.544.544,0,0,1-.567.568h-1.8a.541.541,0,0,1-.41-.163.553.553,0,0,1-.159-.4V666.5a5.658,5.658,0,0,1-3.391,1.046A5.44,5.44,0,0,1,414.748,666.681Zm2.372-3.228a1.018,1.018,0,0,0-.376.818,1.033,1.033,0,0,0,.391.829,1.681,1.681,0,0,0,1.107.325,5.126,5.126,0,0,0,2.764-.837v-1.451h-2.856A1.551,1.551,0,0,0,417.12,663.453Z"
										transform="translate(33.212)" fill="#2dc84d" />
									<path id="Path_18038" data-name="Path 18038"
										d="M421.7,666.783v-9.052a.568.568,0,0,1,.164-.414.55.55,0,0,1,.4-.162h2.083a.55.55,0,0,1,.4.162.561.561,0,0,1,.164.414v3.331h1.367a4.259,4.259,0,0,1,1.661-3.047,6.2,6.2,0,0,1,3.782-1.046,5.294,5.294,0,0,1,3.8,1.353,6.127,6.127,0,0,1,0,7.865,5.327,5.327,0,0,1-3.819,1.35,6.085,6.085,0,0,1-3.8-1.084,4.388,4.388,0,0,1-1.642-3.139h-1.348v3.469a.558.558,0,0,1-.568.568h-2.083a.545.545,0,0,1-.4-.163A.551.551,0,0,1,421.7,666.783Zm12.038-4.53q0-3.172-2.153-3.172t-2.155,3.172q0,3.172,2.155,3.172T433.739,662.253Z"
										transform="translate(38.022)" fill="#2dc84d" />
									<path id="Path_18039" data-name="Path 18039"
										d="M431.674,658.769v-1.107a.557.557,0,0,1,.576-.576h9.164a.55.55,0,0,1,.41.162.575.575,0,0,1,.157.414v1.107a.558.558,0,0,1-.157.4.545.545,0,0,1-.41.164h-2.967v7.377a.559.559,0,0,1-.578.568H435.8a.559.559,0,0,1-.578-.568v-7.377H432.25a.555.555,0,0,1-.413-.164A.544.544,0,0,1,431.674,658.769Z"
										transform="translate(43.883 0.069)" fill="#2dc84d" />
								</g>
							</g>
						</svg>
					</section>
					<p class="mainfooter__copyright">© ООО «Акстек», 2019 ОГРН 1187847338920</p>
					<ul class="mainfooter__services mainfooter__list">
						<li><a class="animation-link-underline" href="/newbid" onclick="ym(76387882,'reachGoal','sozdzakfut')">Создать Заказ</a></li>
						<li><a class="animation-link-underline" href="/newsell" onclick="ym(76387882,'reachGoal','sozdobfut')">Создать Объявление</a></li>
						<li><a class="animation-link-underline" href="/price" onclick="ym(76387882,'reachGoal','oplatafut')">Оплата</a></li>
						<li><a class="animation-link-underline" href="/postavschic">Я - Поставщик</a></li>
					</ul>
					<ul class="mainfooter__info mainfooter__list">
						<li><a class="animation-link-underline" href="/faq" onclick="ym(76387882,'reachGoal','faq')">FAQ</a></li>
						<li><a class="animation-link-underline" href="http://blog.tamtem.ru/" target="_blank">Блог</a></li>
						<li><a class="animation-link-underline" href="/contact" onclick="ym(76387882,'reachGoal','contact')">Контакты</a></li>
						<li><a class="animation-link-underline" href="https://agent.tamtem.ru">Агенты</a></li>
					</ul>
					<div class="mainfooter__contacts"><a class="mainfooter__contacts-phone" href="tel:+78007001476">8
							800
							700 14
							76</a><i class="mainfooter__contacts-cap">Бесплатно по России</i><a
							class="mainfooter__contacts-phone" href="tel:+79307202300">+7 930 720 23 00</a>
						<div class="mainfooter__contacts-cap mainfooter__messengers"><span>Пишите</span>
							<ul class="messengers-list">
								<li><svg id="Group_17030" data-name="Group 17030" xmlns="http://www.w3.org/2000/svg"
										width="13.458" height="13.473" viewBox="0 0 13.458 13.473">
										<path id="Path_18042" data-name="Path 18042"
											d="M411.8,426.129l.942-3.348a6.719,6.719,0,1,1,2.51,2.459Zm3.629-2.111.206.126a5.58,5.58,0,1,0-1.8-1.756l.139.216-.543,1.928Zm0,0"
											transform="translate(-411.797 -412.656)" fill="#fff" />
										<path id="Path_18043" data-name="Path 18043"
											d="M476.589,480.284l-.436-.024a.528.528,0,0,0-.375.128,1.986,1.986,0,0,0-.653,1,3.035,3.035,0,0,0,.7,2.379,7.078,7.078,0,0,0,3.814,2.773,1.859,1.859,0,0,0,1.574-.195,1.408,1.408,0,0,0,.613-.894l.069-.325a.226.226,0,0,0-.126-.253L480.3,484.2a.226.226,0,0,0-.274.067l-.579.75a.166.166,0,0,1-.186.055,4.532,4.532,0,0,1-2.452-2.1.167.167,0,0,1,.021-.187l.553-.64a.226.226,0,0,0,.037-.236l-.635-1.486a.226.226,0,0,0-.2-.137Zm0,0"
											transform="translate(-471.739 -476.691)" fill="#fff" />
									</svg>
								</li>
								<li><svg id="Group_17031" data-name="Group 17031" xmlns="http://www.w3.org/2000/svg"
										width="13.035" height="13.473" viewBox="0 0 13.035 13.473">
										<path id="Path_18044" data-name="Path 18044"
											d="M431.332,418.772l0-.015a4.494,4.494,0,0,0-3.034-2.919l-.015,0a16.857,16.857,0,0,0-6.347,0l-.015,0a4.5,4.5,0,0,0-3.034,2.919l0,.015a12.425,12.425,0,0,0,0,5.351l0,.016a4.532,4.532,0,0,0,2.867,2.876v1.419a.57.57,0,0,0,.982.4l1.438-1.495c.312.018.624.027.936.027a16.921,16.921,0,0,0,3.173-.3l.015,0a4.494,4.494,0,0,0,3.034-2.919l0-.016a12.422,12.422,0,0,0,0-5.351Zm-1.138,5.094a3.4,3.4,0,0,1-2.142,2.051,15.706,15.706,0,0,1-3.375.273.08.08,0,0,0-.06.024l-1.05,1.078-1.117,1.146a.131.131,0,0,1-.225-.09V426a.081.081,0,0,0-.066-.079h0a3.4,3.4,0,0,1-2.141-2.051,11.27,11.27,0,0,1,0-4.836,3.4,3.4,0,0,1,2.141-2.051,15.7,15.7,0,0,1,5.893,0,3.4,3.4,0,0,1,2.142,2.051,11.258,11.258,0,0,1,0,4.836Zm0,0"
											transform="translate(-418.589 -415.533)" fill="#fff" />
										<path id="Path_18045" data-name="Path 18045"
											d="M481.7,476.5c-.132-.04-.257-.067-.373-.115a8.476,8.476,0,0,1-3.195-2.135,8.3,8.3,0,0,1-1.222-1.87c-.157-.319-.289-.65-.424-.979a.85.85,0,0,1,.249-.836,1.97,1.97,0,0,1,.658-.495.423.423,0,0,1,.528.125,6.812,6.812,0,0,1,.817,1.144.544.544,0,0,1-.152.738c-.062.042-.118.091-.176.139a.582.582,0,0,0-.133.14.383.383,0,0,0-.026.337,3.046,3.046,0,0,0,1.711,1.895.872.872,0,0,0,.439.107c.268-.031.355-.325.543-.479a.511.511,0,0,1,.616-.027c.2.125.39.259.58.4a6.607,6.607,0,0,1,.546.416.433.433,0,0,1,.13.536,1.813,1.813,0,0,1-.779.858,1.874,1.874,0,0,1-.337.107c-.132-.04.114-.035,0,0Zm0,0"
											transform="translate(-473.325 -467.08)" fill="#fff" />
										<path id="Path_18046" data-name="Path 18046"
											d="M534.372,463.166a3.2,3.2,0,0,1,3.15,2.65c.047.266.064.537.085.807a.189.189,0,0,1-.178.223c-.126,0-.183-.1-.191-.218-.016-.224-.027-.45-.058-.672a2.818,2.818,0,0,0-2.27-2.356c-.176-.031-.356-.04-.534-.058-.113-.012-.26-.019-.285-.159a.192.192,0,0,1,.19-.217c.03,0,.061,0,.092,0,1.577.044-.031,0,0,0Zm0,0"
											transform="translate(-527.852 -460.593)" fill="#fff" />
										<path id="Path_18047" data-name="Path 18047"
											d="M541.843,481.043a.752.752,0,0,1-.015.11.179.179,0,0,1-.337.018.477.477,0,0,1-.019-.152,1.911,1.911,0,0,0-.241-.958,1.785,1.785,0,0,0-.748-.7,2.155,2.155,0,0,0-.6-.183c-.09-.015-.181-.024-.271-.037a.172.172,0,0,1-.163-.193.17.17,0,0,1,.189-.167,2.477,2.477,0,0,1,1.033.269,2.09,2.09,0,0,1,1.137,1.624c0,.033.013.066.015.1.006.082.01.164.016.272,0,.02-.006-.108,0,0Zm0,0"
											transform="translate(-532.925 -475.363)" fill="#fff" />
										<path id="Path_18048" data-name="Path 18048"
											d="M545.061,496.143a.2.2,0,0,1-.216-.192,1.972,1.972,0,0,0-.037-.252.709.709,0,0,0-.262-.411.686.686,0,0,0-.213-.1c-.1-.028-.2-.02-.294-.044a.18.18,0,0,1-.146-.209.19.19,0,0,1,.2-.152,1.085,1.085,0,0,1,1.111,1.08.5.5,0,0,1,0,.153.157.157,0,0,1-.141.129c-.132,0,.06,0,0,0Zm0,0"
											transform="translate(-537.124 -490.501)" fill="#fff" />
									</svg>
								</li>
								<li><svg xmlns="http://www.w3.org/2000/svg" width="14.88" height="12.589"
										viewBox="0 0 14.88 12.589">
										<path id="Path_18052" data-name="Path 18052"
											d="M298.649,323.345a.966.966,0,0,0-.767-.345,1.5,1.5,0,0,0-.535.106l-12.6,4.808c-.668.255-.758.638-.753.843s.117.583.8.8l.012,0,2.613.748,1.413,4.04a1.22,1.22,0,0,0,1.129.893,1.336,1.336,0,0,0,.9-.385l1.616-1.488,2.344,1.887h0l.022.018.006,0a1.353,1.353,0,0,0,.823.309h0a1.155,1.155,0,0,0,1.1-1.023l2.064-10.19a1.217,1.217,0,0,0-.2-1.031ZM288.3,330.167l5.041-2.575-3.139,3.335a.436.436,0,0,0-.106.194l-.605,2.451Zm1.98,4.049c-.021.019-.042.036-.063.052l.562-2.274,1.021.822Zm7.713-10.014-2.064,10.19c-.02.1-.083.324-.246.324a.508.508,0,0,1-.286-.124l-2.656-2.139h0l-1.58-1.273,4.539-4.823a.436.436,0,0,0-.516-.687l-7.465,3.814-2.648-.758,12.592-4.807a.639.639,0,0,1,.224-.049c.027,0,.074,0,.092.025a.416.416,0,0,1,.016.306Zm0,0"
											transform="translate(-283.999 -323)" fill="#fff" />
									</svg>
								</li>
							</ul>
						</div><a class="mainfooter__email" href="mailto:team@tamtem.ru">team@tamtem.ru</a>
					</div>
					<ul class="mainfooter__socials">
						<li><a class="mainfooter__socials-link mainfooter__socials-link--vk"
								href="https://vk.com/tamtem_b2b?roistat_visit=102953"><svg id="Векторный_смарт-объект"
									data-name="Векторный смарт-объект" xmlns="http://www.w3.org/2000/svg" width="30.779"
									height="17.236" viewBox="0 0 30.779 17.236">
									<path id="Path_14" data-name="Path 14"
										d="M128.06,186.651h1.84a1.568,1.568,0,0,0,.838-.361,1.305,1.305,0,0,0,.253-.793s-.037-2.424,1.11-2.781c1.13-.353,2.579,2.341,4.118,3.377a2.954,2.954,0,0,0,2.046.612l4.109-.055s2.15-.131,1.13-1.791a13.517,13.517,0,0,0-3.057-3.471c-2.577-2.347-2.232-1.969.873-6.031,1.891-2.473,2.647-3.983,2.412-4.63-.226-.617-1.616-.453-1.616-.453l-4.63.028a1.073,1.073,0,0,0-.6.1,1.264,1.264,0,0,0-.408.489,25.974,25.974,0,0,1-1.71,3.541c-2.061,3.437-2.885,3.617-3.222,3.405-.783-.5-.588-2-.588-3.064,0-3.329.514-4.717-1-5.075a7.975,7.975,0,0,0-2.159-.21,10.141,10.141,0,0,0-3.842.385c-.527.252-.932.817-.686.849a2.1,2.1,0,0,1,1.367.675,4.319,4.319,0,0,1,.457,2.055s.273,3.92-.637,4.406c-.624.335-1.479-.349-3.318-3.465a28.788,28.788,0,0,1-1.651-3.36,1.349,1.349,0,0,0-.381-.505,1.942,1.942,0,0,0-.712-.282l-4.4.028s-.661.018-.9.3c-.214.25-.016.769-.016.769s3.442,7.907,7.341,11.891a10.67,10.67,0,0,0,7.635,3.415Z"
										transform="translate(-113 -169.485)" fill="#2fc06e" fill-rule="evenodd" />
								</svg></a></li>
						<li><a class="mainfooter__socials-link mainfooter__socials-link--ok"
								href="https://ok.ru/group/54744477925527?roistat_visit=102953"><svg
									xmlns="http://www.w3.org/2000/svg" width="17.236" height="29.549"
									viewBox="0 0 17.236 29.549">
									<g id="Векторный_смарт-объект" data-name="Векторный смарт-объект"
										transform="translate(0)">
										<path id="Path_12" data-name="Path 12"
											d="M128.68,173.67a7.481,7.481,0,1,0,7.22,7.574A7.382,7.382,0,0,0,128.68,173.67Zm-.01,11.139a3.662,3.662,0,1,1,3.531-3.667A3.591,3.591,0,0,1,128.67,184.81Z"
											transform="translate(-120.032 -173.67)" fill="#2fc06e"
											fill-rule="evenodd" />
										<path id="Path_13" data-name="Path 13"
											d="M137.072,188.77a8.711,8.711,0,0,1-3.146,2.093,14.427,14.427,0,0,1-3.568.823c.183.206.27.307.385.426,1.653,1.709,3.313,3.411,4.959,5.126a1.725,1.725,0,0,1,.369,1.988,1.953,1.953,0,0,1-1.836,1.179,1.771,1.771,0,0,1-1.162-.611c-1.246-1.291-2.518-2.562-3.74-3.877-.357-.384-.527-.31-.841.021-1.254,1.33-2.53,2.641-3.812,3.941a1.566,1.566,0,0,1-1.931.356,2.015,2.015,0,0,1-1.127-1.849,1.881,1.881,0,0,1,.606-1.243q2.454-2.52,4.9-5.05c.109-.112.21-.231.367-.405a10.95,10.95,0,0,1-5.951-2.185c-.213-.172-.433-.339-.627-.53a1.773,1.773,0,0,1-.233-2.473,1.668,1.668,0,0,1,2.25-.52,3.589,3.589,0,0,1,.495.3,9.519,9.519,0,0,0,10.811.1,2.915,2.915,0,0,1,1.053-.56,1.63,1.63,0,0,1,1.894.779,1.683,1.683,0,0,1-.113,2.17Z"
											transform="translate(-120.288 -170.876)" fill="#2fc06e"
											fill-rule="evenodd" />
									</g>
								</svg></a></li>
						<li><a class="mainfooter__socials-link mainfooter__socials-link--fb"
								href="https://www.facebook.com/%D0%A2%D0%B0%D0%BC%D0%A2%D0%B5%D0%BC-417576149054496/?roistat_visit=102953"><svg
									xmlns="http://www.w3.org/2000/svg" width="12.311" height="29.549"
									viewBox="0 0 12.311 29.549">
									<g id="Векторный_смарт-объект" data-name="Векторный смарт-объект"
										transform="translate(0)">
										<path id="Path_15" data-name="Path 15"
											d="M116.369,175.929h-3.652v14.8h-5.533v-14.8h-2.631v-5.2h2.631v-3.366c0-2.407,1.033-6.177,5.583-6.177l4.1.02v5.05h-2.973c-.488,0-1.174.27-1.174,1.417v3.063h4.137Zm0,0"
											transform="translate(-104.552 -161.182)" fill="#2fc06e" />
									</g>
								</svg></a></li>
						<li><a class="mainfooter__socials-link mainfooter__socials-link--inst"
								href="https://www.instagram.com/tamtemb2b/?roistat_visit=102953"><svg
									xmlns="http://www.w3.org/2000/svg" width="25.855" height="25.854"
									viewBox="0 0 25.855 25.854">
									<g id="Векторный_смарт-объект" data-name="Векторный смарт-объект"
										transform="translate(0.001)">
										<path id="Path_9" data-name="Path 9"
											d="M198.916,197.774a1.576,1.576,0,1,0,1.576,1.576A1.578,1.578,0,0,0,198.916,197.774Z"
											transform="translate(-179.115 -193.28)" fill="#2fc06e" />
										<path id="Path_10" data-name="Path 10"
											d="M201.871,194.124H189.06a6.531,6.531,0,0,0-6.523,6.523v12.809a6.529,6.529,0,0,0,6.523,6.523h12.811a6.528,6.528,0,0,0,6.521-6.523V200.647A6.53,6.53,0,0,0,201.871,194.124Zm3.708,19.332a3.714,3.714,0,0,1-3.708,3.711H189.06a3.713,3.713,0,0,1-3.708-3.711V200.647a3.711,3.711,0,0,1,3.708-3.708h12.811a3.712,3.712,0,0,1,3.708,3.708Z"
											transform="translate(-182.537 -194.124)" fill="#2fc06e" />
										<path id="Path_11" data-name="Path 11"
											d="M194.287,199.215a6.659,6.659,0,1,0,6.66,6.659A6.668,6.668,0,0,0,194.287,199.215Zm0,10.5a3.845,3.845,0,1,1,3.846-3.846A3.85,3.85,0,0,1,194.287,209.72Z"
											transform="translate(-181.36 -192.947)" fill="#2fc06e" />
									</g>
								</svg></a></li>
						<li><a class="mainfooter__socials-link mainfooter__socials-link--twi"
								href="https://twitter.com/tamtemb2b"><svg width="30" height="30" viewBox="0 0 512 512"
									xmlns="http://www.w3.org/2000/svg">
									<path
										d="M4 440.5c55.6 5 105.7-9 151.3-43.2-47.7-4.2-79.6-28-97.4-72.6 15.6 1.9 30.2 2.4 45.7-1.8-51.4-16-78.8-49.6-82.5-103.8 15.4 7.2 29.9 12.4 47 12.6-30.5-22.9-46.1-52.6-45.5-90 .3-17.2 4.9-33.4 14-48.7C93.1 159.1 164 195.7 251.3 201.8c-.5-3.8-.8-6.8-1.2-9.9-7.2-55.4 28.8-105.8 83.8-116.3 34.5-6.6 65 2.5 90.8 26.3 4 3.6 7.4 4.4 12.4 3.1 20.1-5.1 39.2-12.5 57.7-22.5-7.1 23.4-21.7 41-41.5 55.8 4.5-.8 9.1-1.4 13.6-2.3 4.7-1 9.4-2.1 14.1-3.4 4.5-1.2 8.9-2.6 13.3-4.1 4.5-1.5 9-3.2 14.3-4.1-2.6 3.6-5.1 7.4-7.9 10.9-11.6 14.7-25 27.6-39.7 39.1-1.5 1.2-2.8 3.8-2.7 5.6.8 35.5-4.2 70.1-15.7 103.7-22.6 66.2-62 119.8-121.1 158.1-29.2 18.9-61.1 31.3-95.2 38.5-33.8 7.1-67.8 8.4-101.9 4.4-34.2-4-66.7-14.1-97.3-29.9-8.1-4.1-15.9-8.7-23.8-13.1.3-.4.5-.8.7-1.2z" />
								</svg></a></li>
					</ul>
				</div>
			</section>
			<section class="filebar">
				<div class="container filebar__container"><a class="filebar__item animation-link-underline"
						href="/files/agreement.pdf">Условия использования</a><a
						class="filebar__item animation-link-underline" href="/files/politic.doc">Политика
						конфиденциальности</a><a class="filebar__item animation-link-underline"
						href="/files/terms_of_use.pdf">Правила использования</a></div>
			</section>
		</footer>
	</div>
	<script src="{{asset ('/blades/js/vendor.min.js') }}"></script>
	@if (config('app.env') === 'production')
	<!-- Yandex.Metrika counter -->
	<script type="text/javascript">
		(function (m, e, t, r, i, k, a) {
			m[i] = m[i] || function () {
				(m[i].a = m[i].a || []).push(arguments)
			};
			m[i].l = 1 * new Date();
			k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k,
				a)
		})
		(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

		ym(76387882, "init", {
			clickmap: true,
			trackLinks: true,
			accurateTrackBounce: true,
			webvisor: true
		});
	</script>
	<noscript>
		<div><img src="https://mc.yandex.ru/watch/76387882" style="position:absolute; left:-9999px;" alt="" /></div>
	</noscript>
	<!-- /Yandex.Metrika counter -->
	@endif
	<script src="//code.jivosite.com/widget/te4uKTP41V" async></script>
</body>

</html>
