<template>
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li>
          <router-link to="/admin/dashboard">
            <i class="fa fa-tachometer"></i>
            <span>Главная</span>
          </router-link>
        </li>
        <li>
          <router-link
            :to="{
              name: 'deals',
              query: { page: 1, perPage: 10, sort: 'id|asc', finished: false }
            }"
          >
            <i class="fa fa-bank"></i>
            <span>Сделки</span>
          </router-link>
        </li>
        <li>
          <router-link
            :to="{
              name: 'deals.moderation',
              query: {
                page: 1,
                perPage: 10,
                sort: 'id|asc',
                only_my_deals: false
              }
            }"
          >
            <i class="fa fa-circle-o"></i>Модерация - Заявки
          </router-link>
        </li>
        <li>
          <router-link
            :to="{
              name: 'deals.moderation.responses',
              query: { page: 1, perPage: 10, sort: 'id|asc', only_new: 0 }
            }"
          >
            <i class="fa fa-circle-o"></i>Модерация - Отклики
          </router-link>
        </li>
        <!-- <li class="treeview">
          <a href="javascript:void(0);">
            <i class="fa fa-bank"></i>
            <span>Модерация</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li>
              <router-link :to="{name: 'deals.moderation'}">
                <i class="fa fa-circle-o"></i>Заявки
              </router-link>
            </li>
            <li>
              <router-link :to="{name: 'deals.moderation.responses'}">
                <i class="fa fa-circle-o"></i>Отклики
              </router-link>
            </li>
          </ul>
        </li> -->
        <li>
          <router-link
            v-if="this.$root.profile.permissions.kladr.show"
            :to="{ name: 'kladr.list' }"
          >
            <i class="fa fa-circle-o"></i>Справочники - КЛАДР
          </router-link>
        </li>
        <li>
          <router-link
            v-if="this.$root.profile.permissions.categories.show"
            :to="{ name: 'rubricator' }"
          >
            <i class="fa fa-circle-o"></i>Справочники - Рубрикатор
          </router-link>
        </li>
        <!-- <li class="treeview">
          <a href="javascript:void(0);">
            <i class="fa fa-table"></i>
            <span>Справочники</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li>
              <router-link
                v-if="this.$root.profile.permissions.dealquestion.show"
                :to="{name: 'deals.questions'}"
              >
                <i class="fa fa-circle-o"></i>Вопросы к сделкам
              </router-link>
            </li>
            <li>
              <router-link
                v-if="this.$root.profile.permissions.legalforms.show"
                :to="{name: 'orgs.legalforms'}"
              >
                <i class="fa fa-circle-o"></i>Формы организаций
              </router-link>
            </li>
          </ul>
        </li> -->

        <li class="treeview">
          <router-link
            v-if="this.$root.profile.permissions.clients.show"
            :to="{
              name: 'clients',
              query: { page: 1, perPage: 10, sort: 'id|asc', blocked: false }
            }"
          >
            <i class="fa fa-circle-o"></i>Клиенты
          </router-link>
        </li>
        <li>
          <router-link
            v-if="this.$root.profile.permissions.users.show"
            :to="{ name: 'users' }"
          >
            <i class="fa fa-circle-o"></i>Сотрудники
          </router-link>
        </li>
        <li>
          <router-link
            v-if="this.$root.profile.permissions.users.show"
            :to="{ name: 'users.ticket' }"
          >
            <i class="fa fa-circle-o"></i>Создать тикет
          </router-link>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
</template>

<script>
export default {
  name: "MainSidebar",
  mounted() {
    //$('ul').tree();
    $(window).trigger("resize");
    $(window).trigger("load");
  }
};
</script>

<style scoped>
.sub-item--counter {
  position: absolute;
  top: 9px;
  right: 0px;
}
.counter-item {
  position: relative;
}
</style>
