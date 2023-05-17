import LkProfile from "../views/LK/views/LkProfile"
import LkProfileEdit from "../views/LK/views/LkProfileEdit"
import LkSettings from "../views/LK/views/LkSettings"
import LkPassword from "../views/LK/views/LkPassword"
import MyBids from "../views/LK/views/MyBids"
import MyBidsNew from "../views/LK/views/MyBidsNew"
import LkCompany from "../views/LK/views/LkCompany"
import LkCompanyEdit from "../views/LK/views/LkCompanyEdit"
import LkCompanyCreate from "../views/LK/views/LkCompanyCreate"
import LkDialogs from "../views/LK/views/LkDialogs"
import LkDialogsNew from "../views/LK/views/LkDialogsNew"
import LkDialogPrivate from "../views/LK/views/LkDialogPrivate"
import LkDialogPrivateNewNew from "../views/LK/views/LkDialogPrivateNewNew"
import MyFavorites from "../views/LK/views/MyFavorites"
import MyFavoritesNew from "../views/LK/views/MyFavoritesNew"
import LkDeleted from "../views/LK/views/Deleted"
import LkWallet from "../views/LK/views/LkWallet"
import LkWalletNew from "../views/LK/views/LkWalletNew"
import PaidServices from "../views/LK/views/PaidServices"
// import LkTarifs from "../views/LK/views/LkTarifs";
import LkResponses from "../views/LK/views/LkResponses.vue"
import LkResponsesNew from "../views/LK/views/LkResponsesNew.vue"

const routes = [
  {
    path: "/lk/profile",
    name: "lk.profile",
    component: LkProfile,
    meta: {
      title: "Профиль"
    }
  },
  {
    path: "/lk/mobile",
    name: "lk.mobile",
    meta: {
      title: "Личный кабинет"
    }
  },
  {
    path: "/lk/settings",
    name: "lk.settings",
    component: LkSettings,
    meta: {
      title: "Настройки профиля и данных о компании",
      breadcrumb: [{ name: "Личный кабинет", link: "/lk/bids/" }, { name: "Настройки" }]
    }
  },
  {
    path: "/lk/profile/password",
    name: "lk.profile.password",
    component: LkPassword,
    meta: {
      title: "Изменить пароль"
    }
  },
  {
    path: "/lk/bids",
    name: "lk.deals",
    component: MyBidsNew,
    meta: {
      title: "Мои объявления",
      breadcrumb: [{ name: "Личный кабинет", link: "/lk/bids/" }, { name: "Мои объявления" }]
    }
  },
  {
    path: "/lk/company",
    name: "lk.company",
    component: LkCompany,
    meta: {
      title: "Моя компания"
    }
  },
  {
    path: "/lk/wallet",
    name: "lk.wallet",
    component: LkWalletNew,
    meta: {
      title: "Кошелёк",
      breadcrumb: [{ name: "Личный кабинет", link: "/lk/bids/" }, { name: "Кошелёк" }]
    }
  },
  // {
  //   path: "/lk/tarifs",
  //   name: "lk.tarifs",
  //   component: LkTarifs,
  //   meta: {
  //     title: `${APPNAME} - Тарифы и услуги`
  //   }
  // },
  {
    path: "/lk/company/edit",
    name: "lk.company.edit",
    component: LkCompanyEdit,
    meta: {
      title: `${APPNAME} - Редактирование компании`
    }
  },
  {
    path: "/lk/company/create",
    name: "lk.company.create",
    component: LkCompanyCreate,
    meta: {
      title: `${APPNAME} - Создание компании`
    }
  },
  {
    path: "/lk/dialogs",
    name: "lk.dialogs",
    component: LkDialogsNew,
    meta: {
      title: "Сообщения",
      breadcrumb: [{ name: "Личный кабинет", link: "/lk/bids/" }, { name: "Сообщения" }]
    }
  },
  {
    path: "/lk/dialogs/:id",
    name: "lk.dialogs.private",
    component: LkDialogPrivateNewNew,
    meta: {
      title: "Сообщения",
      breadcrumb: [{ name: "Личный кабинет", link: "/lk/bids/" }, { name: "Сообщения" }]
    }
  },
  {
    path: "/lk/favorites",
    name: "lk.favorites",
    component: MyFavoritesNew,
    meta: {
      title: "Закладки",
      breadcrumb: [{ name: "Личный кабинет", link: "/lk/bids/" }, { name: "Закладки" }]
    }
  },
  {
    path: "/lk/deleted",
    name: "lk.deleted",
    component: LkDeleted,
    meta: {
      title: `${APPNAME} - Завершённые заявки`
    }
  },
  {
    path: "/lk/responses",
    name: "lk.responses",
    component: LkResponsesNew,
    meta: {
      title: "Отклики",
      breadcrumb: [{ name: "Личный кабинет", link: "/lk/bids/" }, { name: "Отклики" }]
    }
  },
  {
    path: "/lk/paidservices",
    name: "lk.paidservices",
    component: PaidServices,
    meta: {
      title: "Платные услуги",
      breadcrumb: [{ name: "Личный кабинет", link: "/lk/bids/" }, { name: "Платные услуги" }]
    }
  }
]

export default routes
