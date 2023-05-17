<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
	<title>Цены на tamtem.ru</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<meta name="theme-color" content="#ffffff">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<link rel="mask-icon" href="{{ url('/') }}/safari-pinned-tab.svg" color="#2fc06e">
	<link rel="manifest" href="{{ url('/') }}/site.webmanifest">
	<meta name="msapplication-config" content="browserconfig.xml" />
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="title" content="Цены на tamtem.ru">
	<meta name="description" content="Цены на услуги tamtem.ru">
	<meta name="keywords" content="оптовый поставщик, поиск поставщиков, сервис поиска поставщиков, поиск оптовых поставщиков, цены на опт, цена на услуги, цена, оптовые поставки для бизнеса, объявления о покупке оптом, бесплатно объявление оптом.">
	<meta property="og:type" content="website">
	<meta property="og:site_name" content="{{ url('/') }}">
	<meta property="og:title" content="Цены на tamtem.ru">
	<meta property="og:image" content="{{ url('/') }}/images/og_logo.png">
	<meta property="og:url" content="{{ url('/') }}">
	<meta property="og:description" content="Цены на услуги tamtem.ru">
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
		<header class="mainheader">
			<div class="container mainheader__container">
				<section class="logo">
				<a href="/">
					<img src="/blades/img/logo/mainlogo-desktop.svg" class="desktop" alt="Tamtem logo">
					<img src="/blades/img/logo/mainlogo-mobile.svg" alt="Tamtem logo" class="mobile">
				</a>
				</section>
				<nav class="mainmenu"><a class="mainmenu__burger">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 91 91">
							<g fill="#ffffff">
								<path
									d="M85.713 12.142H5.861c-2.305 0-4.174-1.869-4.174-4.176 0-2.305 1.869-4.174 4.174-4.174h79.852c2.305 0 4.174 1.869 4.174 4.174 0 2.307-1.869 4.176-4.174 4.176zM85.713 49.858H5.861c-2.305 0-4.174-1.869-4.174-4.175 0-2.307 1.869-4.176 4.174-4.176h79.852c2.305 0 4.174 1.869 4.174 4.176 0 2.306-1.869 4.175-4.174 4.175zM85.713 87.571H5.861c-2.305 0-4.174-1.869-4.174-4.176s1.869-4.176 4.174-4.176h79.852c2.305 0 4.174 1.869 4.174 4.176s-1.869 4.176-4.174 4.176z">
								</path>
							</g>
						</svg></a>
					<ul class="mainmenu__list notablet">
						<li class="mainmenu__item"><a class="mainmenu__link animation-link-underline" href="/bids">Заказы</a></li>
						<li class="mainmenu__item"><a class="mainmenu__link animation-link-underline" href="/!sales">Оптовые объявления</a></li>
						<li class="mainmenu__item"><a class="mainmenu__link animation-link-underline" href="/faq">С чего
								начать?</a></li>
						<li class="mainmenu__item"><a class="mainmenu__link animation-link-underline"
								href="http://blog.tamtem.ru/">Блог</a></li>
					</ul>
					<section class="modalmenu">
						<header class="modalmenu__header">
							<div class="modalmenu__logo">
							<a href="/"><img src="/blades/img/logo/modal-logo.svg" alt="Tamtem logo"></a>
							</div>
							<div class="modalmenu__close">
								<svg xmlns="http://www.w3.org/2000/svg" width="15.557" height="15.556" viewBox="0 0 15.557 15.556">
									<g fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"
										stroke-width="2">
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
								<li class="modalmenu__menu-item"><a class="modalmenu__menu-link" href="/lk/bids#actived">Личный кабинет</a>
								</li>
								<li class="modalmenu__menu-item"><a class="modalmenu__menu-link" href="/bids">Заказы</a></li>
								<li class="modalmenu__menu-item"><a class="modalmenu__menu-link" href="/!sales">Оптовые объявления</a></li>
								<li class="modalmenu__menu-item"><a class="modalmenu__menu-link" href="/faq">FAQ</a></li>
								<li class="modalmenu__menu-item"><a class="modalmenu__menu-link" href="http://blog.tamtem.ru/">Блог</a>
								</li>
								<!--li class="modalmenu__menu-item"><a class="modalmenu__menu-link" href="https://agent.tamtem.ru"><span
											class="modalmenu__menu-link__text--blue-text">tamtem</span><span
											class="modalmenu__menu-link__text--black-text">Агент</span></a></li-->
							</ul>
						</nav>
						<footer class="modalmenu__footer">
							<ul class="modalmenu__footer-list">
								<li class="modalmenu__footer-item"><a class="modalmenu__footer-link" href="/contact">Контакты</a></li>
								<li class="modalmenu__footer-item"><a class="modalmenu__footer-link"
										href="mailto:team@tamtem.ru">team@tamtem.ru</a></li>
								<li class="modalmenu__footer-item"><a class="modalmenu__footer-link" href="tel:+79307202300">+7 930 720 23 00</a></li>
							</ul>
						</footer>
					</section>
				</nav>
				<section class="createorder mainheader__createorder"><a class="createorder__btn" href="/newbid" onclick="ym(76387882,'reachGoal','sozdzaknn')">Создать
						заказ</a></section>
				<section class="mainheader__profile profile">
					<div class="profile__section">
						<section class="profilemodal profilemodal--unlogged">
							<div class="profilemodal__box profilemodal__accountmenu accountmenu">
								<div class="accountmenu__profile"><a class="accountmenu__office" href="/lk/bids#actived">Личный кабинет</a>
								</div>
							</div>
						</section>
						<div class="profile__box">
							<p class="profile__pic profile__pic--unlogged">
								<svg xmlns="http://www.w3.org/2000/svg" width="37.066" height="37.066">
									<defs>
										<clipPath id="a">
											<circle cx="18.533" cy="18.533" r="18.533" fill="#fff" stroke="#fff" stroke-width="2"></circle>
										</clipPath>
									</defs>
									<g>
										<g fill="none" stroke="#fff" stroke-width="1.5">
											<circle cx="18.533" cy="18.533" r="18.533" stroke="none"></circle>
											<circle cx="18.533" cy="18.533" r="17.783"></circle>
										</g>
										<g clip-path="url(#a)">
											<g fill="none" stroke="#fff" stroke-linecap="round" stroke-width="1.5">
												<g transform="translate(12.725 6.436)">
													<circle cx="5.945" cy="5.945" stroke="none" r="5.945"></circle>
													<circle cx="5.945" cy="5.945" r="5.195"></circle>
												</g>
												<g>
													<path
														d="M18.642 21.869A12.752 12.752 0 0131.39 34.621v2.834H5.89v-2.834a12.752 12.752 0 0112.752-12.752z"
														stroke="none"></path>
													<path
														d="M18.642 22.619h0a12 12 0 0112 12v1.45a.632.632 0 01-.632.632H7.272a.632.632 0 01-.632-.632v-1.448a12 12 0 0112.002-12.002z">
													</path>
												</g>
											</g>
										</g>
									</g>
								</svg>
							</p>
							<div class="profile__content notablet"><span class="profile__enter-box"><a class="profile__enter"
										href="/lk/bids#actived">Личный кабинет</a></span></div>
						</div>
					</div>
				</section>
			</div>
		</header>

		<!-- CONTENT -->
		<div class="content-wrapper">
			 <section class="tarifs">
        <div class="tarifs__container container">
          <h1 class="tarifs__title">Цены</h1>
          <ul class="tarifs__list">
            <li class="tarifs__item">
              <div class="servcard">
                <h4 class="servcard__name">Разместить заказ</h4>
                <p class="servcard__price">0  ₽</p>
                <p class="servcard__desc">Бесплатно! Создайте подробный заказ самостоятельно или с помощью личного менеджера.</p>
              </div>
            </li>
            <li class="tarifs__item">
              <div class="servcard">
                <h4 class="servcard__name"> Предложение</h4>
                <p class="servcard__price">0  ₽</p>
                <p class="servcard__desc">Бесплатно! Поставщик может отправлять предложения на неограниченное количество заказов.</p>
              </div>
            </li>
            <li class="tarifs__item">
              <div class="servcard">
                <h4 class="servcard__name"> Получение контактов</h4>
                <p class="servcard__price">от 200 ₽</p>
                <p class="servcard__desc">В зависимости от средней суммы заказа. Поставщик оплачивает предоставление контактных данных закупщика только в случае, если закупщик выбрал поставщика и его предложение. Сумма оплаты за контакт закупщика видна при отправке предложения закупщику</p>
              </div>
            </li>
          </ul>
        </div>
      </section>
		</div>




		<footer class="mainfooter">
			<section class="mainfooter__content">
				<div class="container mainfooter__container">
					<section class="mainfooter__logo">
						<img src="/blades/img/logo/footer-logo.svg">
					</section>
					<p class="mainfooter__copyright">© ООО «Акстек», 2020 ОГРН 1187847338920</p>
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
					<div class="mainfooter__contacts"><a class="mainfooter__contacts-phone"
							href="tel:+79307202300">+7 930 720 23 00</a>
						<div class="mainfooter__contacts-cap mainfooter__messengers"><span>Пишите</span>
							<ul class="messengers-list">
								<li><svg id="Group_17030" data-name="Group 17030" xmlns="http://www.w3.org/2000/svg" width="13.458"
										height="13.473" viewBox="0 0 13.458 13.473">
										<path id="Path_18042" data-name="Path 18042"
											d="M411.8,426.129l.942-3.348a6.719,6.719,0,1,1,2.51,2.459Zm3.629-2.111.206.126a5.58,5.58,0,1,0-1.8-1.756l.139.216-.543,1.928Zm0,0"
											transform="translate(-411.797 -412.656)" fill="#fff" />
										<path id="Path_18043" data-name="Path 18043"
											d="M476.589,480.284l-.436-.024a.528.528,0,0,0-.375.128,1.986,1.986,0,0,0-.653,1,3.035,3.035,0,0,0,.7,2.379,7.078,7.078,0,0,0,3.814,2.773,1.859,1.859,0,0,0,1.574-.195,1.408,1.408,0,0,0,.613-.894l.069-.325a.226.226,0,0,0-.126-.253L480.3,484.2a.226.226,0,0,0-.274.067l-.579.75a.166.166,0,0,1-.186.055,4.532,4.532,0,0,1-2.452-2.1.167.167,0,0,1,.021-.187l.553-.64a.226.226,0,0,0,.037-.236l-.635-1.486a.226.226,0,0,0-.2-.137Zm0,0"
											transform="translate(-471.739 -476.691)" fill="#fff" />
									</svg>
								</li>
								<li><svg id="Group_17031" data-name="Group 17031" xmlns="http://www.w3.org/2000/svg" width="13.035"
										height="13.473" viewBox="0 0 13.035 13.473">
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
								<li><svg xmlns="http://www.w3.org/2000/svg" width="14.88" height="12.589" viewBox="0 0 14.88 12.589">
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
									data-name="Векторный смарт-объект" xmlns="http://www.w3.org/2000/svg" width="30.779" height="17.236"
									viewBox="0 0 30.779 17.236">
									<path id="Path_14" data-name="Path 14"
										d="M128.06,186.651h1.84a1.568,1.568,0,0,0,.838-.361,1.305,1.305,0,0,0,.253-.793s-.037-2.424,1.11-2.781c1.13-.353,2.579,2.341,4.118,3.377a2.954,2.954,0,0,0,2.046.612l4.109-.055s2.15-.131,1.13-1.791a13.517,13.517,0,0,0-3.057-3.471c-2.577-2.347-2.232-1.969.873-6.031,1.891-2.473,2.647-3.983,2.412-4.63-.226-.617-1.616-.453-1.616-.453l-4.63.028a1.073,1.073,0,0,0-.6.1,1.264,1.264,0,0,0-.408.489,25.974,25.974,0,0,1-1.71,3.541c-2.061,3.437-2.885,3.617-3.222,3.405-.783-.5-.588-2-.588-3.064,0-3.329.514-4.717-1-5.075a7.975,7.975,0,0,0-2.159-.21,10.141,10.141,0,0,0-3.842.385c-.527.252-.932.817-.686.849a2.1,2.1,0,0,1,1.367.675,4.319,4.319,0,0,1,.457,2.055s.273,3.92-.637,4.406c-.624.335-1.479-.349-3.318-3.465a28.788,28.788,0,0,1-1.651-3.36,1.349,1.349,0,0,0-.381-.505,1.942,1.942,0,0,0-.712-.282l-4.4.028s-.661.018-.9.3c-.214.25-.016.769-.016.769s3.442,7.907,7.341,11.891a10.67,10.67,0,0,0,7.635,3.415Z"
										transform="translate(-113 -169.485)" fill="#2fc06e" fill-rule="evenodd" />
								</svg></a></li>
						<li><a class="mainfooter__socials-link mainfooter__socials-link--ok"
								href="https://ok.ru/group/54744477925527?roistat_visit=102953"><svg xmlns="http://www.w3.org/2000/svg"
									width="17.236" height="29.549" viewBox="0 0 17.236 29.549">
									<g id="Векторный_смарт-объект" data-name="Векторный смарт-объект" transform="translate(0)">
										<path id="Path_12" data-name="Path 12"
											d="M128.68,173.67a7.481,7.481,0,1,0,7.22,7.574A7.382,7.382,0,0,0,128.68,173.67Zm-.01,11.139a3.662,3.662,0,1,1,3.531-3.667A3.591,3.591,0,0,1,128.67,184.81Z"
											transform="translate(-120.032 -173.67)" fill="#2fc06e" fill-rule="evenodd" />
										<path id="Path_13" data-name="Path 13"
											d="M137.072,188.77a8.711,8.711,0,0,1-3.146,2.093,14.427,14.427,0,0,1-3.568.823c.183.206.27.307.385.426,1.653,1.709,3.313,3.411,4.959,5.126a1.725,1.725,0,0,1,.369,1.988,1.953,1.953,0,0,1-1.836,1.179,1.771,1.771,0,0,1-1.162-.611c-1.246-1.291-2.518-2.562-3.74-3.877-.357-.384-.527-.31-.841.021-1.254,1.33-2.53,2.641-3.812,3.941a1.566,1.566,0,0,1-1.931.356,2.015,2.015,0,0,1-1.127-1.849,1.881,1.881,0,0,1,.606-1.243q2.454-2.52,4.9-5.05c.109-.112.21-.231.367-.405a10.95,10.95,0,0,1-5.951-2.185c-.213-.172-.433-.339-.627-.53a1.773,1.773,0,0,1-.233-2.473,1.668,1.668,0,0,1,2.25-.52,3.589,3.589,0,0,1,.495.3,9.519,9.519,0,0,0,10.811.1,2.915,2.915,0,0,1,1.053-.56,1.63,1.63,0,0,1,1.894.779,1.683,1.683,0,0,1-.113,2.17Z"
											transform="translate(-120.288 -170.876)" fill="#2fc06e" fill-rule="evenodd" />
									</g>
								</svg></a></li>
						<li><a class="mainfooter__socials-link mainfooter__socials-link--fb"
								href="https://www.facebook.com/%D0%A2%D0%B0%D0%BC%D0%A2%D0%B5%D0%BC-417576149054496/?roistat_visit=102953"><svg
									xmlns="http://www.w3.org/2000/svg" width="12.311" height="29.549" viewBox="0 0 12.311 29.549">
									<g id="Векторный_смарт-объект" data-name="Векторный смарт-объект" transform="translate(0)">
										<path id="Path_15" data-name="Path 15"
											d="M116.369,175.929h-3.652v14.8h-5.533v-14.8h-2.631v-5.2h2.631v-3.366c0-2.407,1.033-6.177,5.583-6.177l4.1.02v5.05h-2.973c-.488,0-1.174.27-1.174,1.417v3.063h4.137Zm0,0"
											transform="translate(-104.552 -161.182)" fill="#2fc06e" />
									</g>
								</svg></a></li>
						<li><a class="mainfooter__socials-link mainfooter__socials-link--inst"
								href="https://www.instagram.com/tamtemb2b/?roistat_visit=102953"><svg xmlns="http://www.w3.org/2000/svg"
									width="25.855" height="25.854" viewBox="0 0 25.855 25.854">
									<g id="Векторный_смарт-объект" data-name="Векторный смарт-объект" transform="translate(0.001)">
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
						<li>
							<a class="mainfooter__socials-link mainfooter__socials-link--tube" href="https://www.youtube.com/watch?v=CcP_-_eKiWw">
								<svg xmlns="http://www.w3.org/2000/svg" width="16.115" height="11.28" viewBox="0 0 16.115 11.28"><path id="Path_18055" data-name="Path 18055" d="M219.459,358.293a2.409,2.409,0,0,0-2.408-2.41h-11.3a2.409,2.409,0,0,0-2.408,2.41v6.461a2.409,2.409,0,0,0,2.408,2.41h11.3a2.409,2.409,0,0,0,2.408-2.41v-6.461Zm-9.67,5.956V358.18l4.6,3.035Zm0,0" transform="translate(-203.344 -355.883)" fill="#fff"/></svg>
							</a>
						</li>
					</ul>
				</div>
			</section>
			<section class="filebar">
				<div class="container filebar__container"><a class="filebar__item animation-link-underline"
						href="/files/agreement.pdf">Условия использования</a><a class="filebar__item animation-link-underline"
						href="/files/politic.doc">Политика конфиденциальности</a><a class="filebar__item animation-link-underline"
						href="/files/terms_of_use.pdf">Правила использования</a></div>
			</section>
		</footer>
	</div>
	<script src="{{asset ('/blades/js/main.min.js') }}"></script>
	@if (config('app.env') === 'production')
<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(76387882, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/76387882" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
		@endif
	<script src="//code.jivosite.com/widget/te4uKTP41V" async></script>	
</body>

</html>