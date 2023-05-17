<template>
  <!-- Main content -->
  <section class="content">
    <div class="box">
      <div class="tablesettings">
        <div class="search-client--wrapper">
          <p class="search-client-info">
            Поиск клиента по личному идентификатору:
          </p>
          <form
            action="javascript:void(0);"
            @submit.prevent="setSearch()"
            @reset.prevent="resetSearch()"
          >
            <input class="search-input" v-model="searchClient" type="text" />
            <button class="search-button" type="submit">Искать</button>
            <button class="resetsearch-button" type="reset">Сбросить</button>
          </form>
        </div>
        <div class="showblock-wrapper">
          <label for="blockedUsers" class="search-client-info blockedUsers">
            <input
              id="blockedUsers"
              type="checkbox"
              v-on:click="changeBlocked()"
              v-model="showBlocked"
            />
            Показывать заблокированных
          </label>
        </div>
      </div>
      <mytable
        ref="usertable"
        :eventPrefix="eventPrefix"
        :tabledata="tabledata"
        :tablekey="key"
        :fieldslist="tablecols"
        :nodata="nodata"
        :activesort="queryParams.sort"
        :isFixedHead="isfixedhead"
      >
      </mytable>
      <pagination
        v-if="tabledata.length > 0"
        :eventPrefix="eventPrefix"
        :paginationsettings="paginationsettings"
        :hasnext="pagination.hasnext"
        :hasprev="pagination.hasprev"
        :firstPage="pagination.firstPage"
        :lastPage="pagination.lastPage"
        :total="pagination.total"
        :perPage="pagination.perPage"
        :currentPage="queryParams.page"
      ></pagination>
      <div class="addnewClient-wrap">
        <router-link class="create-client" :to="{ name: 'clients.create' }"
          >Создать клиента</router-link
        >
      </div>
      <!-- /.box-body -->
    </div>
  </section>
  <!-- /.content -->
</template>

<script>
import Vue from "vue";
import ViewMixins from "../../mixins/view";
import mytable from "../Components/mytable";
import pagination from "../Components/pagination";

Vue.component("mytable", mytable);
Vue.component("pagination", pagination);

Vue.component("userlinks", {
  template: `
    <router-link :to="propert.link || '' ">
                       {{propert.name}}
                    </router-link>
  `,
  props: {
    propert: {
      type: Object
    }
  }
});

let tablefields = [
  {
    name: "id",
    title: "Номер",
    disabled: true,
    visible: true,
    sortable: false
  },
  {
    name: "unique_id",
    title: "Идентификатор",
    disabled: true,
    visible: true,
    sortable: false
  },
  {
    name: "__component:userlinks",
    title: "Название",
    disabled: true,
    visible: true,
    sortable: false
  },
  {
    name: "email",
    title: "E-mail",
    disabled: false,
    visible: true,
    sortable: false
  },
  {
    name: "phone",
    title: "Телефон",
    disabled: false,
    visible: true,
    sortable: false
  },
  {
    name: "inn",
    title: "ИНН",
    settings_title: "Наличие",
    disabled: false,
    visible: true,
    sortable: false
  },
  {
    name: "status",
    title: "Статус",
    disabled: true,
    visible: true,
    sortable: false
  },
  {
    name: "created_at",
    title: "Дата регистрации",
    disabled: true,
    visible: true,
    sortable: true
  }
];

export default {
  mixins: [ViewMixins],
  props: {
    page: {
      type: [Number, String],
      default: 1
    },
    perPage: {
      type: [Number, String],
      default: 10
    },
    blocked: {
      type: [String, Boolean],
      default: false
    },
    search: {
      type: String,
      default: null
    }
  },
  data() {
    return {
      eventPrefix: "users:",
      tabledata: [],
      key: "id",
      searchClient: "",
      tablecols: tablefields,
      isfixedhead: true,
      nodata: "Нет данных",
      queryParams: {
        page: 1,
        perPage: 10,
        sort: "id|asc"
      },
      searchField: "",
      showBlocked: false,
      pagination: {
        hasprev: "",
        hasnext: "",
        perPage: 10,
        total: 0,
        firstPage: "",
        lastPage: "",
        currentPage: ""
      },
      paginationsettings: {
        viewcount: true,
        viewperpage: true,
        viewpagination: true,
        elemsname: "клиент"
      }
    };
  },
  created() {
    this.updateParams();

    this.$router
      .replace({ name: "clients", query: this.queryParams })
      .catch(() => {});

    this.addEventListeners();
    this.getdataforTable();
  },
  beforeDestroy() {
    this.removeEventListeners();
  },
  watch: {
    $route() {
      this.updateParams();
      this.getdataforTable();
    }
  },
  methods: {
    updateParams() {
      this.queryParams.page = this.page;
      this.queryParams.perPage = this.perPage;
      this.queryParams.blocked = this.blocked;

      this.showBlocked = JSON.parse(this.blocked);

      if (this.search) {
        this.queryParams.search = this.search;
        this.searchClient = this.search;
      } else {
        this.searchClient = "";
      }
    },
    updateRoute() {
      this.$router
        .push({ name: "clients", query: this.queryParams })
        .catch(() => {});
    },
    changeBlocked() {
      this.showBlocked = !this.showBlocked;
      this.queryParams.blocked = this.showBlocked;
      this.queryParams.page = 1;
      // this.getdataforTable();
      this.updateRoute();
    },
    addEventListeners() {
      this.$root.$on(this.eventPrefix + "changeperpage", (payload) => {
        let fr = 0;
        fr = (this.queryParams.page - 1) * this.queryParams.perPage + 1;
        this.queryParams.page = Math.ceil(fr / payload);
        this.queryParams.perPage = payload;

        this.updateRoute();
      });
      // изменение страницы
      this.$root.$on(this.eventPrefix + "newpage", (payload) => {
        if (
          this.queryParams.page != payload &&
          payload > 0 &&
          payload <= this.pagination.lastPage
        ) {
          this.queryParams.page = +payload;

          // this.getdataforTable();
        } else {
          this.queryParams.page = 1;

          // this.getdataforTable();
        }
        this.updateRoute();
      });
    },
    removeEventListeners() {
      let events = [
        this.eventPrefix + "changeperpage",
        this.eventPrefix + "newpage"
      ];

      for (let item of events) {
        this.$root.$off(item);
      }
    },
    setSearch() {
      if (this.searchClient.length < 1) {
        this.showError("Минимальная длина идентификатора 1 символ");
        return false;
      } else {
        // this.getdataforTable();
        this.$set(this.queryParams, "search", this.searchClient);
        this.updateRoute();
      }
    },
    resetSearch() {
      this.searchClient = "";
      this.$delete(this.queryParams, "search");

      this.updateRoute();
    },
    serializeData(obj, prefix) {
      var str = [],
        p;
      for (p in obj) {
        if (obj.hasOwnProperty(p)) {
          var k = prefix ? prefix + "[" + p + "]" : p,
            v = obj[p];
          str.push(
            v !== null && typeof v === "object"
              ? this.serializeData(v, k)
              : encodeURIComponent(k) + "=" + encodeURIComponent(v)
          );
        }
      }
      return str.join("&");
    },
    getdataforTable() {
      this.tabledata = [];
      let params = {
        page: this.queryParams.page,
        per_page: this.queryParams.perPage,
        blocked: this.showBlocked
      };
      if (this.searchClient != "") {
        params.search = this.searchClient;
        this.queryParams.page = 1;
        params.page = 1;
      }

      let uri = this.serializeData(params);
      axios
        .get("/admin/api/clients/get/user?" + uri)
        .then((resp) => {
          // подготавливаем данные для таблицы
          this.processdata(resp.data.data);
          // подготавливаем данные для пагинации
          this.getpaginationdata(resp);
        })
        .catch((error) => {
          this.messageLoadError();
        });
    },
    processdata(data) {
      for (let i in data) {
        this.tabledata.push(data[i]);
        this.tabledata[i].link = "/admin/clients/edit/" + data[i].id;
        this.tabledata[i].status = this.getOrgStatus(data[i].status);
        this.tabledata[i].hide_email
          ? (this.tabledata[i].trClass = "fakeDeal")
          : "";
      }
    },
    getpaginationdata(response) {
      this.pagination.currentPage = response.data.current_page;
      this.pagination.perPage = response.data.per_page;
      this.pagination.total = response.data.total;
      this.pagination.firstPage = 1;
      this.pagination.lastPage = response.data.last_page;
      this.pagination.hasnext =
        response.data.next_page_url == null ? false : true;
      this.pagination.hasprev =
        response.data.prev_page_url == null ? false : true;
    }
  }
};
</script>

<style scoped>
.content >>> .my__table tr.fakeDeal {
  background-color: rgba(0, 0, 0, 0.1);
}
.no-bg {
  background: transparent !important;
}
.addnewClient-wrap {
  padding-top: 10px;
  padding-left: 5px;
}
.create-client {
  border: 1px solid #908e8e;
  border-radius: 2px;
  padding: 5px;
  display: inline-block;
  margin-bottom: 10px;
}
.search-client--wrapper {
  margin: 5px;
}
.search-client-info {
  /*! margin-left: 5px; */
  /*! margin-top: 5px; */
  font-size: 15px;
  margin-bottom: 5px;
}
.search-input {
  border-radius: 2px;
  border: 1px solid;
  width: 200px;
}
.search-button {
  border-radius: 2px;
  border: 1px solid #7a7a7a;
  width: 80px;
}
.resetsearch-button {
  border-radius: 2px;
  border: 1px solid #7a7a7a;
  width: 80px;
}
.blockedUsers {
  font-weight: 500;
}
.tablesettings {
  display: flex;
  justify-content: space-between;
}
.showblock-wrapper {
  align-self: end;
  margin-right: 5px;
}
</style>
