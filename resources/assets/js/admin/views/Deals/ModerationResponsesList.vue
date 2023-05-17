<template>
  <!-- Main content -->
  <section class="content">
    <div class="box">
      <div class="tablesettings">
        <p class="pagedeal">{{ `Заявка № ${$route.params.id}, отклики: ` }}</p>
        <div class="showblock-wrapper">
          <label for="myUsers" class="search-client-info blockedUsers">
            <input
              id="myUsers"
              type="checkbox"
              v-on:click="changeResponseType()"
              v-model="showAll"
            />
            Показывать ранее откликнувшихся
          </label>
        </div>
      </div>
      <mytable
        ref="dealstable"
        :eventPrefix="eventPrefix"
        :tabledata="tabledata"
        :tablekey="key"
        :fieldslist="tablecols"
        :nodata="nodata"
        :activesort="queryParams.sort"
        :isFixedHead="isfixedhead"
      ></mytable>
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
      <!-- <div class="addnewClient-wrap">
                    <router-link class="create-client" :to="{name: 'clients.create'}">Создать клиента</router-link>
      </div>-->
      <!-- /.box-body -->
    </div>
  </section>
  <!-- /.content -->
</template>

<script>
import { Component, Prop, Vue } from "vue-property-decorator";
import ViewMixins from "../../mixins/view";
import mytable from "../Components/mytable";
import pagination from "../Components/pagination";
Vue.component("mytable", mytable);
Vue.component("pagination", pagination);

Vue.component("responseid", {
  template: `
    <router-link :to="propert.response.link || '' ">
        {{propert.response.id}}
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
    name: "__component:responseid",
    title: "ID",
    disabled: true,
    visible: true,
    sortable: false
  },
  {
    name: "__component:userlink",
    title: "Создатель",
    disabled: false,
    visible: true,
    sortable: false
  },
  {
    name: "response_date",
    title: "Дата отклика",
    disabled: false,
    visible: true,
    sortable: false
  },
  {
    name: "response_status",
    title: "Статус отклика",
    disabled: false,
    visible: true,
    sortable: false
  }
];

Vue.component("userlink", {
  template: `
      <router-link :to="propert.user.link || '' ">
                       {{propert.user.name+' ('+propert.user.id+')' }}
                    </router-link>
  `,
  props: {
    propert: {
      type: Object
    }
  }
});

export default {
  mixins: [ViewMixins],
  watch: {
    $route(to, from) {
      this.updateData();
    }
  },
  data() {
    return {
      created: false,
      deal_type: "buy",
      eventPrefix: "responses:",
      tabledata: [],
      key: "id",
      searchDeal: "",
      tablecols: tablefields,
      isfixedhead: true,
      nodata: "Нет данных",
      queryParams: {
        page: 1,
        perPage: 10,
        sort: "id|asc"
      },
      showAll: false,
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
        elemsname: "элемент"
      },
      typeDeal: {
        buy: "Покупка",
        sell: "Продажа"
      }
    };
  },
  created() {
    this.addEventListeners();
    this.getdataforTable();
  },
  beforeDestroy() {
    this.removeEventListeners();
  },
  methods: {
    switchStatus(status) {
      switch (status) {
        case "banned":
          return "Отклик забанен";
          break;
        case "moderation":
          return "Модерация отклика";
          break;
        case "waiting_winner":
          return "Ожидание решения кто победил";
          break;
        case "waiting_payment":
          return "Ожидание оплаты";
          break;
        case "na":
        default:
          return "Без статуса";
          break;
      }
    },
    changeResponseType() {
      this.showAll = !this.showAll;
      this.queryParams.page = 1;
      this.getdataforTable();
    },
    addEventListeners() {
      this.$root.$on(this.eventPrefix + "changeperpage", (payload) => {
        let fr = 0;
        fr = (this.queryParams.page - 1) * this.queryParams.perPage + 1;
        this.queryParams.page = Math.ceil(fr / payload);
        this.queryParams.perPage = payload;
        this.getdataforTable();
      });
      // изменение страницы
      this.$root.$on(this.eventPrefix + "newpage", (payload) => {
        if (
          this.queryParams.page != payload &&
          payload > 0 &&
          payload <= this.pagination.lastPage
        ) {
          this.queryParams.page = +payload;

          this.getdataforTable();
        } else {
          this.queryParams.page = 1;

          this.getdataforTable();
        }
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
        all: this.showAll
      };
      let dealId = this.$route.params.id;
      let uri = this.serializeData(params);
      axios
        .get(`/admin/api/deals/moderation/response/deals/${dealId}?${uri}`)
        .then((resp) => {
          // подготавливаем данные для таблицы

          if (resp.data.result) {
            this.processdata(resp.data.data.data);
          }
          // подготавливаем данные для пагинации
          this.getpaginationdata(resp.data);
        })
        .catch((error) => {
          this.messageLoadError();
        });
    },
    processdata(data) {
      for (let i in data) {
        this.tabledata.push(data[i]);
        this.tabledata[i].response_status = this.switchStatus(
          data[i].trading_status
        );
        this.tabledata[i].user = {
          link: `/admin/clients/edit/${data[i].user_id}`,
          name: data[i].user_name,
          id: data[i].user_unique_id
        };
        this.tabledata[i].response = {
          id: data[i].id,
          link: `/admin/deals/show/response/${data[i].id}`
        };
        if (data[i].created_at) {
          let [year, month, day] = data[i].created_at.date
            .split(" ")[0]
            .split("-");
          let time = data[i].created_at.date.split(" ")[1].split(".")[0];
          this.tabledata[i].response_date = `${day}.${month}.${year} ${time}`;
        } else {
          this.tabledata[i].response_date = "";
        }
      }
    },
    getpaginationdata(response) {
      this.pagination.currentPage = response.data.current_page;
      this.pagination.perPage = response.data.per_page;
      this.pagination.total = response.data.total;
      this.pagination.firstPage = 1;
      this.pagination.lastPage = response.data.last_page || 1;
      this.pagination.hasnext =
        response.data.next_page_url == null ? false : true;
      this.pagination.hasprev =
        response.data.prev_page_url == null ? false : true;
    },
    updateData() {
      this.setTitle(this.$route.meta.title);
      this.setBreadcrumbs(this.$route.meta.breadcrumbs);
    },

    getItemLink(item) {
      if (!this.$root.profile.permissions.clients.edit) return {};

      return { name: "clients.view", params: { id: item.id } };
    }
  }
};
</script>

<style scoped>
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
.pagedeal {
  margin: 10px 0 10px 5px;
  font-weight: 500;
  font-size: 18px;
}
</style>
