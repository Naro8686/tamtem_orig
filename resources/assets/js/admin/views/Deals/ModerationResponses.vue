<template>
  <!-- Main content -->
  <section class="content">
    <div class="box">
      <div class="tablesettings">
        <div class="showblock-wrapper">
          <label for="newResponses" class="search-client-info blockedUsers">
            <input
              id="newResponses"
              type="checkbox"
              v-on:click="changeResponses()"
              v-model.number="onlyNew"
            />
            Показывать только новые отклики
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

Vue.component("responseslink", {
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
    title: "ID",
    disabled: true,
    visible: true,
    sortable: false
  },
  {
    name: "__component:responseslink",
    title: "Название",
    disabled: true,
    visible: true,
    sortable: false
  },
  {
    name: "category",
    title: "Категория",
    disabled: true,
    visible: true,
    sortable: false
  },
  {
    name: "owner",
    title: "Создатель",
    disabled: false,
    visible: true,
    sortable: false
  },
  {
    name: "created_at",
    title: "Дата создания",
    disabled: false,
    visible: true,
    sortable: false
  },
  {
    name: "deadline_deal",
    title: "Дата завершения",
    disabled: false,
    visible: true,
    sortable: false
  },
  {
    name: "responses",
    title: "Откликов всего/на модерации",
    disabled: false,
    visible: true,
    sortable: false,
    additional_item_class: "moderation-col",
    additional_header_class: "moderation-col"
  }
];

Vue.component("userlink", {
  template: `
      <router-link :to="propert.userlink || '' ">
                       {{propert.user.name}}
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
  props: {
    page: {
      type: [Number, String],
      default: 1
    },
    perPage: {
      type: [Number, String],
      default: 10
    },
    only_new: {
      type: [Number, String],
      default: 0
    }
  },
  data() {
    return {
      created: false,
      deal_type: "buy",
      eventPrefix: "moderation.responses:",
      tabledata: [],
      key: "id",
      tablecols: tablefields,
      isfixedhead: true,
      nodata: "Нет данных",
      queryParams: {
        page: 1,
        perPage: 10,
        sort: "id|asc"
      },
      onlyNew: 0,
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
      }
    };
  },
  created() {
    this.updateParams();
    this.$router
      .replace({ name: "deals.moderation.responses", query: this.queryParams })
      .catch(() => {});

    this.addEventListeners();
    this.$nextTick(() => {
      this.getdataforTable();
    });
  },
  beforeDestroy() {
    this.removeEventListeners();
  },
  watch: {
    $route(to, from) {
      this.updateParams();
      this.getdataforTable();
    }
  },
  methods: {
    updateParams() {
      this.queryParams.page = this.page;
      this.queryParams.perPage = this.perPage;
      this.queryParams.only_new = this.only_new | 0;

      this.onlyNew = this.only_new | 0;
    },
    updateRoute() {
      this.$router
        .push({ name: "deals.moderation.responses", query: this.queryParams })
        .catch(() => {});
    },
    changeResponses() {
      this.onlyNew = !this.onlyNew | 0;
      this.queryParams.page = 1;
      this.queryParams.only_new = this.onlyNew;
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
        } else {
          this.queryParams.page = 1;
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
        only_new: this.only_new | 0
      };

      let uri = this.serializeData(params);
      axios
        .get(`/admin/api/deals/moderation/response/deals?${uri}`)
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
        this.tabledata[
          i
        ].link = `/admin/deals/show/moderation-responses/${data[i].id}`;
        this.tabledata[i].owner = data[i].owner.name;
        this.tabledata[i].category = data[i].categories[0].name;

        let [year, month, day] = data[i].date_create.date
          .split(" ")[0]
          .split("-");
        this.tabledata[i].created_at = `${day}.${month}.${year}`;

        let [year1, month1, day1] = data[i].planned_finish.date
          .split(" ")[0]
          .split("-");
        this.tabledata[i].deadline_deal = `${day1}.${month1}.${year1}`;

        this.tabledata[
          i
        ].responses = `${data[i].count_response}/${data[i].count_response_moderate}`;
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
    },
    getItemLink(item) {
      if (!this.$root.profile.permissions.clients.edit) return {};

      return { name: "clients.view", params: { id: item.id } };
    }
  }
};
</script>

<style scoped>
.box >>> .moderation-col {
  max-width: 120px;
  text-align: center;
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
  margin: 5px;
  display: flex;
  width: 100%;
  justify-content: right;
}
</style>
