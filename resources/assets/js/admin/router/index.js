import MainView from "../views/MainView";
import NotFound from "../views/NotFound";

import Rubricator from "../views/Rubricator/Rubricator";
import RubricatorCreate from "../views/Rubricator/RubricatorCreate";
import RubricatorEdit from "../views/Rubricator/RubricatorEdit";

import Users from "../views/Users/Users";
import UsersCreate from "../views/Users/UsersCreate";
import UsersEdit from "../views/Users/UsersEdit";

import Clients from "../views/Clients/Clients";
import ClientsCreate from "../views/Clients/ClientsCreate";
import ClientsEdit from "../views/Clients/ClientsEdit";

import Kladr from "../views/Kladr/Kladr";
import KladrCreate from "../views/Kladr/KladrCreate";
import KladrEdit from "../views/Kladr/KladrEdit";

import Deals from "../views/Deals/Deals";
import DealsView from "../views/Deals/DealsView";
import ModerationDeals from "../views/Deals/ModerationDeals";
import ModerationDealsView from "../views/Deals/ModerationDealsView";
import ModerationResponses from "../views/Deals/ModerationResponses";
import ModerationResponsesList from "../views/Deals/ModerationResponsesList";
import ModerationResponseShow from "../views/Deals/ModerationResponseView";

import SendTicket from "../views/Users/SendTicket";
const routes = [
  {
    path: "/admin/",
    name: "redirect.home",
    redirect: "/admin/dashboard"
  },
  {
    path: "/admin/dashboard",
    name: "home",
    component: MainView,
    meta: {
      title: "Dashboard"
    }
  },
  {
    path: "/admin/rubricator",
    name: "rubricator",
    meta: {
      title: "Рубрикатор",
      breadcrumbs: [{ name: "Рубрикатор" }],
      permission: "categories.show"
    },
    component: Rubricator
  },
  {
    path: "/admin/rubricator/create/:id?",
    name: "rubricator.create",
    meta: {
      title: "Новая рубрика",
      breadcrumbs: [
        { name: "Рубрикатор", path: "rubricator" },
        { name: "Новая рубрика" }
      ],
      permission: "categories.create"
    },
    component: RubricatorCreate
  },
  {
    path: "/admin/rubricator/edit/:id",
    name: "rubricator.edit",
    meta: {
      title: "Редактирование рубрики",
      breadcrumbs: [
        { name: "Рубрикатор", path: "rubricator" },
        { name: "Редактирование рубрики" }
      ],
      permission: "categories.edit"
    },
    component: RubricatorEdit
  },
  {
    path: "/admin/users",
    name: "users",
    meta: {
      title: "Сотрудники",
      breadcrumbs: [{ name: "Сотрудники" }],
      permission: "users.show"
    },
    component: Users
  },
  {
    path: "/admin/users/ticket/create",
    name: "users.ticket",
    meta: {
      title: "Создание тикета",
      breadcrumbs: [{ name: "Создание тикета" }],
      permission: "users.show"
    },
    component: SendTicket
  },
  {
    path: "/admin/users/create",
    name: "users.create",
    meta: {
      title: "Новый сотрудник",
      breadcrumbs: [
        { name: "Сотрудники", path: "users" },
        { name: "Новый сотрудник" }
      ],
      permission: "users.create"
    },
    component: UsersCreate
  },
  {
    path: "/admin/users/edit/:id",
    name: "users.edit",
    meta: {
      title: "Редактирование сотрудника",
      breadcrumbs: [
        { name: "Сотрудники", path: "users" },
        { name: "Редактирование" }
      ],
      permission: "users.edit"
    },
    component: UsersEdit
  },
  {
    path: "/admin/clients",
    name: "clients",
    meta: {
      title: "Клиенты",
      breadcrumbs: [{ name: "Клиенты" }],
      permission: "clients.show"
    },
    props: route => ({
      ...route.query
    }),
    component: Clients
  },
  {
    path: "/admin/clients/create",
    name: "clients.create",
    meta: {
      title: "Новый клиент",
      breadcrumbs: [
        { name: "Клиенты", path: "clients" },
        { name: "Новый клиент" }
      ],
      permission: "clients.create"
    },
    component: ClientsCreate
  },
  {
    path: "/admin/clients/edit/:id",
    name: "clients.edit",
    meta: {
      title: "Редактирование клиента",
      breadcrumbs: [
        { name: "Клиенты", path: "clients" },
        { name: "Редактирование клиента" }
      ],
      permission: "clients.edit"
    },
    component: ClientsEdit
  },
  //
  {
    path: "/admin/kladr/items/:country?/:region?",
    name: "kladr.list",
    meta: {
      title: "КЛАДР",
      breadcrumbs: [{ name: "КЛАДР", path: "kladr.list" }],
      permission: "kladr.show"
    },
    component: Kladr
  },
  {
    path: "/admin/kladr/create/:country?/:region?",
    name: "kladr.create",
    meta: {
      title: "Новый элемент КЛАДРа",
      breadcrumbs: [{ name: "КЛАДР", path: "kladr.list" }],
      permission: "kladr.create"
    },
    component: KladrCreate
  },
  {
    path: "/admin/kladr/edit/:country?/:region?/:city?",
    name: "kladr.edit",
    meta: {
      title: "Редактирование элемента КЛАДРа",
      breadcrumbs: [{ name: "КЛАДР", path: "kladr.list" }],
      permission: "kladr.edit"
    },
    component: KladrEdit
  },
  {
    path: "/admin/deals",
    name: "deals",
    props: route => ({
      ...route.query
    }),
    meta: {
      title: "Сделки",
      breadcrumbs: [{ name: "Сделки" }],
      permission: "publications.show"
    },
    component: Deals
  },
  {
    path: "/admin/deals/:id",
    name: "deals.view",
    meta: {
      title: "Сделка",
      breadcrumbs: [{ name: "Сделки", path: "deals" }, { name: "Сделка" }],
      permission: "publications.show"
    },
    component: DealsView
  },

  {
    path: "/admin/deals/show/moderation",
    name: "deals.moderation",
    props: route => ({
      ...route.query
    }),
    meta: {
      title: "Модерация сделок",
      breadcrumbs: [{ name: "Модерация сделок" }],
      permission: "publications.show"
    },
    component: ModerationDeals
  },
  {
    path: "/admin/deals/show/moderation-responses",
    name: "deals.moderation.responses",
    props: route => ({
      ...route.query
    }),
    meta: {
      title: "Отклики",
      breadcrumbs: [{ name: "Отклики" }],
      permission: "publications.show"
    },
    component: ModerationResponses
  },
  {
    path: "/admin/deals/show/moderation-responses/:id",
    name: "deals.moderation.responses.list",
    meta: {
      title: "Список откликов на заявку",
      breadcrumbs: [
        { name: "Отклики", path: "deals.moderation.responses" },
        { name: `Список откликов на заявку` }
      ],
      permission: "publications.show"
    },
    component: ModerationResponsesList
  },
  {
    path: "/admin/deals/show/response/:id",
    name: "deals.moderation.responses.show",
    meta: {
      title: "Отклик на заявку",
      breadcrumbs: [
        { name: "Отклики", path: "deals.moderation.responses" },
        {
          name: `Список откликов на заявку`,
          path: "deals.moderation.responses.list"
        },
        { name: `Отклик` }
      ],
      permission: "publications.show"
    },
    component: ModerationResponseShow
  },
  {
    path: "/admin/deals/show/moderation/:id",
    name: "deals.moderation.view",
    meta: {
      title: "Модерация сделки",
      breadcrumbs: [
        { name: "Модерация сделок", path: "deals" },
        { name: "Сделка" }
      ],
      permission: "publications.show"
    },
    component: ModerationDealsView
  },

  // 404
  {
    path: "*",
    name: "notfound",
    meta: {
      title: "404 - Not Found",
      breadcrumbs: [{ name: "404" }]
    },
    component: NotFound
  }
];

export default routes;
