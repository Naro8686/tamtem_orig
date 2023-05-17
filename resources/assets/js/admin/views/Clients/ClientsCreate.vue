<template>
  <!-- Main content -->
  <section class="content">
    <div class="box">
      <form class="row box-body" @submit="saveForm">
        <div class="col-md-12">
          <h3>Данные учётной записи</h3>
          <div class="userdata--wrapper clearfix">
            <div class="col-md-3">
              <div
                :class="[
                  'form-group',
                  errorsServer['user.name'] ? 'has-error' : ''
                ]"
              >
                <label for="name">Логин пользователя</label>
                <input
                  required
                  v-model="item.user.name"
                  type="text"
                  class="form-control"
                  id="name"
                  placeholder="Введите логин"
                />
                <span
                  class="help-block"
                  v-if="errorsServer['user.name']"
                  :errorsServer="errorsServer"
                  >{{ errorsServer["user.name"][0] }}</span
                >
              </div>
            </div>
            <div class="col-md-3">
              <div
                :class="[
                  'form-group',
                  errorsServer['user.email'] ? 'has-error' : ''
                ]"
              >
                <label for="email">Email пользователя</label>
                <input
                  required
                  v-model="item.user.email"
                  type="email"
                  class="form-control"
                  id="email"
                  placeholder="Введите email"
                />
                <span
                  class="help-block"
                  v-if="errorsServer['user.email']"
                  :errorsServer="errorsServer"
                  >{{ errorsServer["user.email"][0] }}</span
                >
              </div>
            </div>
            <div class="col-md-3">
              <div
                :class="[
                  'form-group',
                  errorsServer['user.phone'] ? 'has-error' : ''
                ]"
              >
                <label for="phone">Телефон пользователя</label>
                <input
                  v-model="item.user.phone"
                  type="text"
                  class="form-control"
                  id="text"
                  placeholder="Введите телефон"
                />
                <span
                  class="help-block"
                  v-if="errorsServer['user.phone']"
                  :errorsServer="errorsServer"
                  >{{ errorsServer["user.phone"][0] }}</span
                >
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <h3>Данные организации</h3>
          <div class="col-md-4">
            <div
              :class="[
                'form-group',
                errorsServer['organization.org_inn'] ? 'has-error' : ''
              ]"
            >
              <label for="org_inn">ИНН</label>
              <div class="input--wrapper">
                <input
                  v-model="item.organization.org_inn"
                  type="text"
                  class="form-control"
                  id="org_inn"
                  placeholder="Введите ИНН"
                />
                <ul
                  v-if="companiesArray.length > 0"
                  class="input--search-results"
                >
                  <li
                    @click="selectCompany(item)"
                    class="input--search-result search-result"
                    v-for="item in companiesArray"
                    :key="item.id"
                  >
                    <div class="search-result--wrapper">
                      <span>{{ item.value }}</span>
                      <span>{{
                        setOrgStatus(item.data.state.status).text
                      }}</span>
                      <br />
                    </div>
                  </li>
                </ul>
                <button
                  class="input--button search-button"
                  type="button"
                  @click="getDatabyInn()"
                />
              </div>
              <span class="help-block" v-if="dadataResult"
                >Статус организации: {{ dadataResult.org_status.text }}</span
              >
              <span
                class="help-block"
                v-if="errorsServer['organization.org_inn']"
                :errorsServer="errorsServer"
                >{{ errorsServer["organization.org_inn"][0] }}</span
              >
            </div>
          </div>
          <div class="col-md-4">
            <div
              :class="[
                'form-group',
                errorsServer['organization.categories'] ? 'has-error' : ''
              ]"
            >
              <label for="categories">Категории</label>
              <treeselect
                v-model="item.organization.categories"
                instanceId="categories"
                :multiple="true"
                :options="categories"
                :normalizer="getNode"
              />
              <span
                class="help-block"
                v-if="errorsServer['organization.categories']"
                :errorsServer="errorsServer"
                >{{ errorsServer["organization.categories"][0] }}</span
              >
            </div>
          </div>
          <div class="col-md-4">
            <div
              :class="[
                'form-group',
                errorsServer['organization.org_name'] ? 'has-error' : ''
              ]"
            >
              <label for="org_name">Название организации/ЮР лица</label>
              <input
                v-model="item.organization.org_name"
                type="text"
                class="form-control"
                id="org_name"
                placeholder="Введите название"
              />
              <span
                class="help-block"
                v-if="errorsServer['organization.org_name']"
                :errorsServer="errorsServer"
                >{{ errorsServer["organization.org_name"][0] }}</span
              >
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-md-4">
            <div
              :class="[
                'form-group',
                errorsServer['organization.org_kpp'] ? 'has-error' : ''
              ]"
            >
              <label for="org_kpp">КПП</label>
              <input
                v-model="item.organization.org_kpp"
                type="text"
                class="form-control"
                id="org_kpp"
                placeholder="Введите КПП"
              />
              <span
                class="help-block"
                v-if="errorsServer['organization.org_kpp']"
                :errorsServer="errorsServer"
                >{{ errorsServer["organization.org_kpp"][0] }}</span
              >
            </div>
          </div>
          <div class="col-md-4">
            <div
              :class="[
                'form-group',
                errorsServer['organization.org_okved'] ? 'has-error' : ''
              ]"
            >
              <label for="org_okved">ОКВЭД</label>
              <input
                v-model="item.organization.org_okved"
                type="text"
                class="form-control"
                id="org_okved"
                placeholder="Введите ОКВЭД"
              />
              <span
                class="help-block"
                v-if="errorsServer['organization.org_okved']"
                :errorsServer="errorsServer"
                >{{ errorsServer["organization.org_okved"][0] }}</span
              >
            </div>
          </div>
          <div class="col-md-4">
            <div
              :class="[
                'form-group',
                errorsServer['organization.org_manager_post'] ? 'has-error' : ''
              ]"
            >
              <label for="org_manager_post">Должность руководителя</label>
              <input
                v-model="item.organization.org_manager_post"
                type="text"
                class="form-control"
                id="org_manager_post"
                placeholder="Введите должность руководителя"
              />
              <span
                class="help-block"
                v-if="errorsServer['organization.org_manager_post']"
                :errorsServer="errorsServer"
                >{{ errorsServer["organization.org_manager_post"][0] }}</span
              >
            </div>
          </div>
          <div class="col-md-4">
            <div
              :class="[
                'form-group',
                errorsServer['organization.org_ogrn'] ? 'has-error' : ''
              ]"
            >
              <label for="org_ogrn">ОГРН</label>
              <input
                v-model="item.organization.org_ogrn"
                type="text"
                class="form-control"
                id="org_ogrn"
                placeholder="ОГРН"
              />
              <span
                class="help-block"
                v-if="errorsServer['organization.org_ogrn']"
                :errorsServer="errorsServer"
                >{{ errorsServer["organization.org_ogrn"][0] }}</span
              >
            </div>
          </div>
          <div class="col-md-4">
            <div
              :class="[
                'form-group',
                errorsServer['organization.org_address'] ? 'has-error' : ''
              ]"
            >
              <label for="org_address">Юридический адрес</label>
              <input
                v-model="item.organization.org_address"
                type="text"
                class="form-control"
                id="org_address"
                placeholder="Введите юридический адрес"
              />
              <span
                class="help-block"
                v-if="errorsServer['organization.org_address']"
                :errorsServer="errorsServer"
                >{{ errorsServer["organization.org_address"][0] }}</span
              >
            </div>
          </div>
          <div class="col-md-4">
            <div
              :class="[
                'form-group',
                errorsServer['organization.org_director'] ? 'has-error' : ''
              ]"
            >
              <label for="org_director">ФИО директора</label>
              <input
                v-model="item.organization.org_director"
                type="text"
                class="form-control"
                id="org_director"
                placeholder="Введите ФИО генерального директора"
              />
              <span
                class="help-block"
                v-if="errorsServer['organization.org_director']"
                :errorsServer="errorsServer"
                >{{ errorsServer["organization.org_director"][0] }}</span
              >
            </div>
          </div>
          <div class="col-md-4">
            <div
              :class="[
                'form-group',
                errorsServer['organization.org_registration_date']
                  ? 'has-error'
                  : ''
              ]"
            >
              <label for="org_registration_date">Дата регистрации</label>
              <input
                v-model="item.organization.org_registration_date"
                type="text"
                class="form-control"
                id="org_registration_date"
                placeholder="Дата регистрации"
              />
              <span
                class="help-block"
                v-if="errorsServer['organization.org_registration_date']"
                :errorsServer="errorsServer"
                >{{
                  errorsServer["organization.org_registration_date"][0]
                }}</span
              >
            </div>
          </div>
          <div class="col-md-4">
            <div
              :class="[
                'form-group',
                errorsServer['organization.phone'] ? 'has-error' : ''
              ]"
            >
              <label for="phone">Телефон компании</label>
              <input
                v-model="item.organization.phone"
                type="text"
                class="form-control"
                id="text"
                placeholder="Введите телефон"
              />
              <span
                class="help-block"
                v-if="errorsServer['phone']"
                :errorsServer="errorsServer"
                >{{ errorsServer["organization.phone"][0] }}</span
              >
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-md-12">
            <div
              :class="[
                'form-group',
                errorsServer['organization.org_description'] ? 'has-error' : ''
              ]"
            >
              <label for="org_description">Описание компании</label>
              <textarea
                v-model="item.organization.org_description"
                type="text"
                class="form-control textarea-description"
                id="org_description"
              ></textarea>
              <span
                class="help-block"
                v-if="errorsServer['organization.org_description']"
                :errorsServer="errorsServer"
                >{{ errorsServer["organization.org_description"][0] }}</span
              >
            </div>
          </div>
        </div>
        <div class="col-md-12 frm-buttons">
          <input
            class="btn btn-default pull-right"
            type="submit"
            value="Добавить"
          />
        </div>
      </form>
    </div>
  </section>
  <!-- /.content -->
</template>
<script>
import ViewMixins from "../../mixins/view";
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";

export default {
  mixins: [ViewMixins],
  components: { Treeselect },
  data() {
    return {
      companiesArray: [],
      categories: [],
      dadataResult: null,
      item: {
        user: {
          name: "",
          email: "",
          phone: ""
        },
        organization: {
          offices: [],
          stores: [],
          org_type: "buyer",
          org_inn: "",
          org_kpp: "",
          org_name: "",
          categories: [2],
          org_legal_form_id: 1,
          org_director: "",
          org_address_legal: "",
          phone: "",
          org_description: "",
          org_address: ""
        }
      }
    };
  },
  mounted() {
    this.getCategories();
  },
  methods: {
    selectCompany(item) {
      this.processDadataItem(item);
      this.companiesArray.splice(0, this.companiesArray.length);
    },
    transformDate(unixDate) {
      let date = new Date(unixDate).toLocaleString("ru").split(",")[0];
      let [day, month, year] = date.split(".");
      return `${year}-${month}-${day}`;
    },
    setOrgStatus(status) {
      switch (status) {
        case "ACTIVE":
          return {
            id: 1,
            text: "Действующая"
          };
          break;
        case "LIQUIDATING":
          return {
            id: 0,
            text: "Ликвидируется"
          };
          break;
        case "LIQUIDATED":
          return {
            id: 0,
            text: "Ликвидирована"
          };
          break;
        case "REORGANIZING":
          return {
            id: 0,
            text: "Реорганизация"
          };
          break;
        default:
          return {
            id: 0,
            text: "Нет данных"
          };
          break;
      }
    },
    processDadataItem(elem) {
      const result = {};

      result.org_name = elem.value;
      result.org_kpp = elem.data.kpp;
      if (elem.data.management) {
        result.org_director = elem.data.management.name;
        result.org_manager_post = elem.data.management.post;
      }
      (result.org_okved = elem.data.okved),
        (result.org_status = this.setOrgStatus(elem.data.state.status)),
        (result.org_ogrn = elem.data.ogrn),
        (result.org_registration_date = this.transformDate(
          elem.data.state.registration_date
        )),
        (result.org_address_legal = elem.data.address.data.source),
        (result.org_inn = elem.data.inn);
      result.org_address = elem.data.address.data.city;

      this.dadataResult = Object.assign({}, result);
      this.item.organization = Object.assign(
        this.item.organization,
        this.dadataResult
      );

      this.item.organization.org_registration_date = this.item.organization.org_registration_date
        .split("-")
        .reverse()
        .join(".");
    },
    getDatabyInn() {
      const requestLog = {};

      const apiKey = "f91af2fd3ec6b628f49c9a5208bb0713568cea54";
      const inn = this.item.organization.org_inn;

      const axiosImplement = axios.create({
        baseURL:
          "https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/",
        timeout: 1000,
        headers: {
          "Content-Type": "application/json",
          Authorization: `Token ${apiKey}`
        }
      });

      axiosImplement
        .post("party", { query: inn, branch_type: "MAIN" })
        .then((response) => {
          console.log(response);
          if (response.data.suggestions.length > 0) {
            if (response.data.suggestions.length > 1) {
              this.companiesArray = response.data.suggestions;
            } else {
              this.processDadataItem(response.data.suggestions[0]);
            }
          } else {
            this.showError("Ничего не найдено");
          }
        })
        .catch((err) => {
          this.handleErrorResponse(err);
          this.showError("Произошла ошибка");
        })
        .then(() => {});
    },
    getNode(node) {
      return {
        id: node.id,
        label: node.name,
        children: node.items
      };
    },
    getCategories() {
      axios.get("/admin/api/categories").then((resp) => {
        this.categories = resp.data;
      });
    },
    saveForm(event) {
      event.preventDefault();

      this.item.organization.phone.length == 0
        ? (this.item.organization.phone = this.item.user.phone)
        : "";
      this.item.organization.org_city_id = 108;

      let data = {};

      for (let i in this.item) {
        if (this.item[i] != null && this.item[i] != undefined) {
          this.$set(data, i, this.item[i]);
        }
      }

      delete data.organization.org_status;
      data.organization.org_registration_date = `${data.organization.org_registration_date
        .split(".")
        .reverse()
        .join("-")}`;

      axios
        .post("/admin/api/clients/store/user", data)
        .then((resp) => {
          this.messageCreated();
          this.$router.push({
            name: "clients",
            query: { page: 1, perPage: 10, sort: "id|asc", blocked: false }
          });
        })
        .catch((err) => {
          this.handleErrorResponse(err);
        });
    }
  }
};
</script>
<style lang="scss" scoped>
$search-icon: "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciICAgICB3aWR0aD0iMjgiICAgICBoZWlnaHQ9IjI4IiAgICAgdmlld0JveD0iMCAwIDE4IDE4IiAgICAgYXJpYS1sYWJlbGxlZGJ5PSJzZWFyY2giPiAgICA8ZyBmaWxsPSJub25lIiAgICAgZmlsbC1ydWxlPSJldmVub2RkIj4gICAgPHBhdGggc3Ryb2tlPSIjRDhEOEQ4IiAgICAgICAgICBkPSJNLTU1OC41LTQyNy41aDgxNnY2MjZoLTgxNnoiIC8+ICAgIDxwYXRoIHN0cm9rZT0iI0Q4RDhEOCIgICAgICAgICAgZD0iTS0xNDQuNS01OC41aDI5N3YxNDBoLTI5N3oiIC8+ICAgIDxnIHN0cm9rZT0iIzJmYzA2ZSIgICAgICAgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiAgICAgICBzdHJva2UtbGluZWpvaW49InJvdW5kIiAgICAgICBzdHJva2Utd2lkdGg9IjIiICAgICAgIHRyYW5zZm9ybT0idHJhbnNsYXRlKDEgMSkiPiAgICAgIDxjaXJjbGUgY3g9IjYuNjY3IiAgICAgICAgICAgICAgY3k9IjYuNjY3IiAgICAgICAgICAgICAgcj0iNi42NjciIC8+ICAgICAgPHBhdGggZD0iTTE2IDE2bC00LjYyMi00LjYyMiIgLz4gICAgPC9nPiAgPC9nPjwvc3ZnPg==";

.input {
  &--wrapper {
    position: relative;
  }
  &--button {
    &.search-button {
      position: absolute;
      right: 10px;
      top: 8px;
      border: none;
      outline: none;
      width: 20px;
      height: 20px;
      background: url($search-icon);
      background-size: contain;
    }
  }
  &--search-results {
    position: absolute;
    z-index: 1;
    background: white;
    width: 100%;
    border: 1px solid;
    list-style-type: none;
    padding: 5px;
  }
  &--search-result {
    padding: 3px;
    cursor: pointer;
    &:hover {
      background: aquamarine;
    }
  }
}
</style>
<style>
.textarea-description {
  resize: none;
  height: 120px;
}
</style>
