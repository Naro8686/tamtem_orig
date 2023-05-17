import LkRoutes from "./routes.lk"
import BidsRoutes from "./routes.bids"
import UsersRoutes from "./routes.users"
import CompaniesRoutes from "./routes.companies"
import Home from "../views/Home/Home.vue"
import Lk from "../views/LK/Lk"
import LkNew from "../views/LK/LkNew"
import Bids from "../views/Catalog/Bids"
import NewBid from "../views/NewDeal/NewBid"
import Users from "../views/Users/Users"
import Companies from "../views/Companies/Companies"
import Page404 from "../views/Page404"
import Tarifs from "../views/Tarifs"
import faq2 from "../views/faq"
import Unsubscribe from "../views/GeneralComponents/components/UnsubscribeView"
import Supplier from "../views/Supplier/Supplier"
const routes = [
  {
    path: "/lk",
    name: "lk",
    component: LkNew,
    redirect: "lk/bids#actived",
    meta: {
      breadcrumb: false,
      title: `${APPNAME} - Личный кабинет`,
      auth: true
    },
    children: LkRoutes
  },
  {
    path: "/fq",
    name: "faq2",
    component: faq2
  },
  {
    path: "/",
    name: "homepage",
    component: Home,
    meta: {
      // title: `${APPNAME} - Главная страница`
      title: `TamTem - Сервис поиска оптовых поставщиков для бизнеса`
    }
  },
  {
    path: "/postavschik",
    name: "supplier",
    component: Supplier,
    meta: {
      title: `${APPNAME} - Для поставщиков`
    }
  },
  {
    path: "/unsub",
    name: "unsub",
    component: Unsubscribe,
    meta: {
      title: `${APPNAME} - Отписаться`
    }
  },
  {
    path: "/bids",
    name: "bids",
    redirect: "bids.list",
    component: Bids,
    meta: {
      title: `${APPNAME} - Сервис поиска оптовых поставщиков для бизнеса`
    },
    children: BidsRoutes
  },
  {
    path: "/newbid",
    name: "new.bid",
    component: NewBid,
    meta: {
      title: `${APPNAME} - Новая заявка`,
      auth: false,
      type: "buy"
    }
  },
  {
    path: "/newsell",
    name: "new.sell",
    component: NewBid,
    meta: {
      title: `${APPNAME} - Новое объявление`,
      auth: false,
      type: "sell"
    }
  },
  {
    path: "/users",
    name: "users",
    redirect: "users.list",
    component: Users,
    meta: {
      title: `${APPNAME} - Пользователи`,
      breadcrumb: false
    },
    children: UsersRoutes
  },
  {
    path: "/companies",
    name: "companies",
    redirect: "companies.list",
    component: Companies,
    meta: {
      title: `${APPNAME} - Компании`,
      breadcrumb: false
    },
    children: CompaniesRoutes
  },
  {
    path: "/tarifs",
    name: "tarifs",
    component: Tarifs,
    meta: {
      title: `${APPNAME} - Тарифы и услуги`,
      breadcrumb: false
    }
  },
  {
    path: "/success-reset",
    name: "success.reset"
  },
  {
    path: "/404",
    name: "page404",
    component: Page404,
    meta: {
      title: `${APPNAME} - Страница не найдена`
    }
  },
  {
    path: "*",
    redirect: "/404"
  }
]

export default routes
