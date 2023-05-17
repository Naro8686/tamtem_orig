<template>
  <!-- Main content -->
  <section class="content">
    <div class="box">
      <form class="row box-body" @submit="saveForm">
        <div class="col-md-12">
          <h3>Данные учётной записи</h3>
          <p class="current-status">
            Текущий статус :
            <b class="client-status">{{ getOrgStatus(item.user.status) }}</b>
          </p>
          <div class="unique_id--wrapper">
            <p class="current-status">
              Идентификатор пользователя:
              <b class="client-status">{{ item.user.unique_id }}</b>
              <!-- <button
                type="button"
                class="btn create-new-id"
                title="Сгенерировать новый идентификатор"
                v-on:click="generateNewID()"
              >
                <i class="fa fa-refresh" aria-hidden="true"></i>
              </button>-->
            </p>
          </div>
          <div class="userdata--wrapper clearfix">
            <div class="col-md-3">
              <div
                :class="['form-group', errorsServer['name'] ? 'has-error' : '']"
              >
                <label for="name">Логин пользователя</label>
                <input
                  v-model="item.user.name"
                  type="text"
                  class="form-control"
                  id="name"
                  placeholder="Введите логин"
                  onfocus="this.select()"
                />
                <span
                  class="help-block"
                  v-if="errorsServer['name']"
                  :errorsServer="errorsServer"
                  >{{ errorsServer["name"][0] }}</span
                >
              </div>
            </div>
            <div class="col-md-3">
              <div
                :class="[
                  'form-group',
                  errorsServer['email'] ? 'has-error' : ''
                ]"
              >
                <label for="email">Email пользователя</label>
                <input
                  v-model="item.user.email"
                  type="email"
                  class="form-control"
                  id="email"
                  placeholder="Введите email"
                  onfocus="this.select()"
                />
                <span
                  class="help-block"
                  v-if="errorsServer['email']"
                  :errorsServer="errorsServer"
                  >{{ errorsServer["email"][0] }}</span
                >
              </div>
            </div>
            <div class="col-md-3">
              <div
                :class="[
                  'form-group',
                  errorsServer['phone'] ? 'has-error' : ''
                ]"
              >
                <label for="phone">Телефон пользователя</label>
                <the-mask
                  v-model="item.user.phone"
                  type="text"
                  class="form-control"
                  id="text"
                  mask="+7 (###) ###-##-##"
                  placeholder="Введите телефон"
                  onfocus="this.select()"
                />
                <!-- <input
                  v-model="item.user.phone"
                  type="text"
                  class="form-control"
                  id="text"
                  v-mask="`+7 (###) ###-##-##`"
                  placeholder="Введите телефон"
                  onfocus="this.select()"
                />-->
                <span
                  class="help-block"
                  v-if="errorsServer['phone']"
                  :errorsServer="errorsServer"
                  >{{ errorsServer["phone"][0] }}</span
                >
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Генерация нового пароля</label>
                <button
                  type="button"
                  class="btn create-new-id"
                  v-on:click="generateNewPassword(item.user.unique_id)"
                >
                  <i class="fa fa-refresh" aria-hidden="true"></i>
                  Сгенерировать новый пароль
                </button>
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
          <button
            type="button"
            v-on:click="showBalance = true"
            class="btn btn-default"
          >
            Пополнить баланс
          </button>
          <completion-purse
            v-if="showBalance"
            @closecomponent="showBalance = false"
            :user="item.user"
          ></completion-purse>

          <button
            type="button"
            class="btn btn-default"
            v-on:click="showScore = true"
          >
            Сформировать счёт
          </button>
          <scoreform
            :user="item.user"
            v-if="showScore"
            @closecomponent="showScore = false"
          />
          <input class="btn btn-default" type="submit" value="Сохранить" />
          <button
            type="button"
            class="btn btn-default"
            v-on:click="showAddDeals = true"
          >
            Создать заявку/ки
          </button>
          <adddealstouser
            :userId="item.user.id"
            v-if="showAddDeals"
            @closeme="showAddDeals = false"
          ></adddealstouser>
          <button
            type="button"
            v-on:click="showBlock = true"
            class="btn btn-default pull-right"
          >
            Статус пользователя
          </button>
          <userblock
            v-if="showBlock"
            @closecomponent="showBlock = false"
            :user="item.user"
          ></userblock>
          <button
            type="button"
            v-on:click="
              confirmCompanyToAgents(item.organization.org_inn, item.user.id)
            "
            class="btn btn-default"
          >
            Подтвердить
          </button>
          <button
            type="button"
            v-on:click="checkagentCompany(item.organization.org_inn)"
            class="btn btn-default"
          >
            Кто привел
          </button>
        </div>
      </form>
    </div>
  </section>
  <!-- /.content -->
</template>

<script>
import ViewMixins from "../../mixins/view";
import Treeselect from "@riophae/vue-treeselect";
import CompletionPurse from "../Components/completionPurse";
import adddealstouser from "../Components/adddealstouser";
import { TheMask } from "vue-the-mask";
export default {
  mixins: [ViewMixins],
  directives: {},
  components: { TheMask, CompletionPurse, Treeselect, adddealstouser },
  data() {
    return {
      companiesArray: [],
      categories: [],
      showBlock: false,
      showScore: false,
      showBalance: false,
      showAddDeals: false,
      dadataResult: null,
      legalForms: [],
      item: {
        user: {
          name: "",
          email: "",
          phone: ""
        },
        organization: {
          categories: null,
          phone: "",
          org_name: "",
          org_inn: "",
          org_kpp: "",
          org_address: "",
          org_legal_form_id: "",
          org_director: "",
          org_type: "buyer",
          org_description: "",
          offices: [],
          stores: []
        }
      }
    };
  },
  mounted() {
    this.getUser();
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
      result.org_director = elem.data.management
        ? elem.data.management.name
        : null;
      (result.org_manager_post = elem.data.management
        ? elem.data.management.post
        : null),
        (result.org_okved = elem.data.okved),
        (result.org_status = this.setOrgStatus(elem.data.state.status)),
        (result.org_ogrn = elem.data.ogrn),
        (result.org_registration_date = this.transformDate(
          elem.data.state.registration_date
        )),
        (result.org_address_legal = elem.data.address.data.source),
        (result.org_inn = elem.data.inn);
      result.org_address = elem.data.address.data.city;
      console.log(result);
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
    confirmCompanyToAgents(inn) {
      let params = {
        inn: this.item.organization.org_inn,
        id: this.item.user.id
      };
      axios
        .post("/admin/api/clients/update/orgconfirmforagent", { params })
        .then((resp) => {
          if (resp.data.result === true) {
            this.messageSaved();
          } else {
            this.showError("Вы уже подтвердили компанию ранее");
          }
        });
    },
    checkagentCompany(inn) {
      let params = { inn: this.item.organization.org_inn };
      axios
        .post("/admin/api/clients/update/agentcompanycheck", { params })
        .then((resp) => {
          if (resp.data.result === true) {
            this.showSuccess("Хомяк");
          } else {
            this.showError("Не хомяк");
          }
        });
    },
    generateNewPassword(unique_id) {
      let params = { unique_id: unique_id, email: this.item.user.email };
      axios
        .post("/admin/api/clients/generate/user/password", { params })
        .then((resp) => {
          if (resp.data.result === true) {
            this.messageSaved();
          } else {
            this.showError("Ошибка, попробуйте ещё раз");
          }
        });
    },
    generateNewID() {
      let params = { unique_id: this.item.user.unique_id };
      axios
        .post("/admin/api/clients/generate/user/uniqueid", { params })
        .then((resp) => {
          if (resp.data.result === true) {
            this.messageSaved();
            this.item.user.unique_id = resp.data.unique_id;
          } else {
            this.showError("Ошибка сохранения, попробуйте ещё раз");
          }
        });
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

    getUser() {
      axios
        .get("/admin/api/clients/edit/user/" + this.$route.params.id)
        .then((resp) => {
          this.item = resp.data;
          this.item.organization.org_registration_date = this.item.organization.org_registration_date
            .split(" ")[0]
            .split("-")
            .reverse()
            .join(".");
        })
        .catch(this.handleErrorResponse);
    },
    processingData(dataObject) {
      let result = Object.assign({}, dataObject);

      let orgDataCleared = {};
      let temp = Object.assign({}, dataObject.organization);

      for (let prop in temp) {
        if (temp[prop] && temp[prop] != "") {
          orgDataCleared[prop] = temp[prop];
        }
      }

      this.$set(result, "organization", orgDataCleared);
      result.organization.org_registration_date = result.organization.org_registration_date
        .split(".")
        .reverse()
        .join("-");
      return result;
    },
    saveForm(event) {
      event.preventDefault();
      if (this.item.user.email == "") {
        this.showError("Заполните E-mail");
      } else {
        const dataToSave = this.processingData(this.item);

        axios
          .post("/admin/api/clients/update/user/" + this.$route.params.id, {
            body: dataToSave
          })
          .then((resp) => {
            if (resp.data.result) {
              this.messageSaved();
              // this.$router.push({ name: "clients" });
            } else {
              //  this.handleErrorResponse(resp);
              this.errorsServer = resp.data.error;
              this.messageFormError();
            }
          });
      }
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
  resize: vertical;
  height: 120px;
}
.client-status {
  text-transform: uppercase;
}
</style>
