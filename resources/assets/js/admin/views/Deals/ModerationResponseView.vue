<template>
  <!-- Main content -->
  <section class="content">
    <progressbar v-if="!item"></progressbar>

    <div class="box" v-if="item">
      <div class="row box-body">
        <div class="deal-info sell-deal col-md-12">
          <form @submit.prevent="saveItem()" action="javascript:void(0);">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th class="col-md-3">Параметр</th>
                  <th class="col-md-3">Статус выполнения</th>
                  <th class="col-md-6">Значение</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Пользователь, совершивший отклик</td>
                  <td></td>
                  <td>
                    <router-link
                      :to="{
                        name: 'clients.edit',
                        params: { id: item.user_id }
                      }"
                      >{{ item.user_name }} ({{
                        item.user_unique_id
                      }})</router-link
                    >
                  </td>
                </tr>
                <tr>
                  <td>Предмет сделки</td>
                  <td></td>
                  <td>{{ item.deal_name }}</td>
                </tr>
                <tr>
                  <td>Спецификация</td>
                  <td>
                    <select
                      v-model.number="item.answers.dqh_specification.is_agree"
                      id
                    >
                      <option value="1">Да</option>
                      <option value="0">Нет</option>
                    </select>
                  </td>
                  <td>{{ item.answers.dqh_specification.question }}</td>
                </tr>
                <tr>
                  <td>Тип сделки</td>
                  <td>
                    <select
                      v-model.number="item.answers.dqh_type_deal.is_agree"
                      id
                    >
                      <option value="1">Да</option>
                      <option value="0">Нет</option>
                    </select>
                  </td>
                  <td>{{ item.answers.dqh_type_deal.question }}</td>
                </tr>
                <tr>
                  <td>Объём общий</td>
                  <td>
                    <select
                      v-model.number="item.answers.dqh_volume.is_agree"
                      id
                    >
                      <option value="1">Да</option>
                      <option value="0">Нет</option>
                    </select>
                  </td>
                  <td>
                    {{ item.answers.dqh_volume.question }}
                    {{ item.unit_for_all }}
                  </td>
                </tr>
                <tr>
                  <td>Минимальная партия</td>
                  <td>
                    <select
                      v-model.number="item.answers.dqh_min_party.is_agree"
                      id
                    >
                      <option value="1">Да</option>
                      <option value="0">Нет</option>
                    </select>
                  </td>
                  <td>{{ item.answers.dqh_min_party.question }}</td>
                </tr>
                <tr>
                  <td>Логистика</td>
                  <td>
                    <select
                      v-model.number="item.answers.dqh_logistics.is_agree"
                      id
                    >
                      <option value="1">Да</option>
                      <option value="0">Нет</option>
                    </select>
                  </td>
                  <td>{{ item.answers.dqh_logistics.question }}</td>
                </tr>
                <tr>
                  <td>Способ оплаты</td>
                  <td>
                    <select
                      v-model.number="item.answers.dqh_payment_method.is_agree"
                      id
                    >
                      <option value="1">Да</option>
                      <option value="0">Нет</option>
                    </select>
                  </td>
                  <td>{{ item.answers.dqh_payment_method.question }}</td>
                </tr>
                <tr>
                  <td>Условия оплаты</td>
                  <td>
                    <select
                      v-model.number="item.answers.dqh_payment_term.is_agree"
                      id
                    >
                      <option value="1">Да</option>
                      <option value="0">Нет</option>
                    </select>
                  </td>
                  <td>{{ item.answers.dqh_payment_term.question }}</td>
                </tr>
                <tr>
                  <td>Документы, подтверждающие качество</td>
                  <td>
                    <select
                      v-model.number="
                        item.answers.dqh_doc_confirm_quality.is_agree
                      "
                      id
                    >
                      <option value="1">Да</option>
                      <option value="0">Нет</option>
                    </select>
                  </td>
                  <td>{{ item.answers.dqh_doc_confirm_quality.question }}</td>
                </tr>
                <tr>
                  <td>Дополнительно от поставщика</td>
                  <td></td>
                  <td>
                    <div class="editable-area">
                      <textarea
                        onfocus="this.select()"
                        v-model="item.notice"
                      ></textarea>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Бюджет</td>
                  <td>
                    <select v-model.number="item.is_shipping_included" id>
                      <option value="1">Доставка включена в стоимость</option>
                      <option value="0">
                        Доставка не включена в стоимость
                      </option>
                    </select>
                  </td>
                  <td>
                    <div class="editable-area">
                      <input
                        onfocus="this.select()"
                        v-model="item.price_offer"
                      />
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <div>
              Прикрепленные фотографии:
              <div class="filelist">
                <div
                  class="filewrapper"
                  :key="image.id"
                  v-for="image in item.files"
                >
                  <div
                    class="filewrapper-file"
                    :style="addBackground(image.path)"
                  ></div>
                  <button type="button" @click="removeItem(image.id)">
                    Удалить
                  </button>
                  <br />
                  <p>https://tamtem.ru{{ image.path }}</p>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="row box-body">
      <div class="col-md-12 frm-buttons">
        <div class="btn-group">
          <input
            class="btn btn-success"
            type="button"
            @click="saveItem()"
            value="Сохранить изменения"
          />
        </div>
      </div>
    </div>
    <div class="row box-body">
      <div class="col-md-12 frm-buttons">
        <div class="btn-group">
          <input
            class="btn btn-success"
            type="button"
            @click="successModeration()"
            value="Принять"
          />
          <input
            class="btn btn-danger pull-right"
            type="button"
            @click="failModeration()"
            value="Отклонить"
          />
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
</template>

<script>
import { Component, Prop, Vue } from "vue-property-decorator";
import ViewMixins from "../../mixins/view";
// import BootstrapVue from "bootstrap-vue";

// Vue.use(BootstrapVue);

export default {
  mixins: [ViewMixins],
  data() {
    return {
      formData: new FormData(),
      date: new Date(),
      viewDelete: false,
      activeTab: 1,
      itemId: "",
      item: null,
      clearedData: null,
      categories: null,
      cityName: "",
      images: [],
      procentesArray: [],
      questions: {
        dqh_specification: "",
        dqh_type_deal: "",
        dqh_min_party: "",
        dqh_logistics: "",
        dqh_payment_method: "",
        dqh_payment_term: "",
        dqh_volume: "",
        dqh_doc_confirm_quality: ""
      }
    };
  },
  mounted() {
    this.loadItem();
  },
  methods: {
    removeItem(id) {
      axios
        .post(`/admin/api/deals/moderation/response/image/delete/${id}`)
        .then((response) => {
          if (response.data.result == true) {
            let i = this.item.files.findIndex((item) => {
              return item.id == response.data.id;
            });
            this.item.files.splice(i - 1, 1);
            this.showSuccess("Файл удалён успешно");
          } else {
            this.showError(response.data.err);
          }
        })
        .catch((e) => {
          this.showError("Ошибка удаления файла");
        });
    },
    addBackground(imageurl) {
      return `background: url(${imageurl})`;
    },
    successModeration() {
      axios
        .post(`/admin/api/deals/moderation/response/success`, {
          id: this.item.id
        })
        .then((resp) => {
          console.log(resp);

          this.showSuccess("Отклик успешно опубликован");
          this.$router.push({
            name: "deals.moderation.responses",
            query: { page: 1, perPage: 10, sort: "id|asc", only_new: 0 }
          });
        });
    },

    failModeration() {
      axios
        .post(`/admin/api/deals/moderation/response/fail`, {
          id: this.item.id
        })
        .then((resp) => {
          console.log(resp);
          if (resp.data.result) {
            this.showError("Отклик успешно отклонён");
            this.$router.push({
              name: "deals.moderation.responses",
              query: { page: 1, perPage: 10, sort: "id|asc", only_new: 0 }
            });
          } else {
            this.showError(resp.data.error);
          }
        });
    },
    loadItem() {
      axios
        .get(`/admin/api/deals/moderation/response/${this.$route.params.id}`)
        .then((resp) => {
          console.log(resp);
          this.item = resp.data.data;
          if (!resp.data.data.questions) {
            this.item.questions = this.questions;
          }
        });
    },
    setFormData(data) {
      for (let key in data) {
        switch (key) {
          case "answers":
            for (let i in data[key]) {
              let arraykey = `answers[${i}]`;
              this.formData.set(arraykey, data[key][i]);
            }
            break;
          default:
            this.formData.set(key, data[key]);
            break;
        }
      }
    },
    setAnswers() {
      let answers = new Map();
      for (let key in this.item.answers) {
        answers.set(key, this.item.answers[key].is_agree);
      }
      let result = {};
      answers.forEach((v, k) => {
        result[k] = v;
      });
      return result;
    },
    saveItem() {
      const rawData = {
        id: this.item.id,
        price_offer: `${this.item.price_offer}`.replace(",", "."),
        is_shipping_included: this.item.is_shipping_included,
        notice: this.item.notice,
        answers: this.setAnswers()
      };
      this.setFormData(rawData);
      axios
        .post("/admin/api/deals/moderation/response", this.formData, {
          headers: { "Content-Type": "multipart/form-data" }
        })
        .then((response) => {
          console.log(response);
          this.messageSaved();
        })
        .catch((err) => {});
    }
  }
};
</script>
<style scoped>
.filelist {
  display: flex;
}
.filewrapper {
  width: 400px;
  border: 1px solid;
  display: flex;
  flex-direction: column;
}
.filewrapper-file {
  width: 100%;
  height: 200px;
  background-position: center !important;
  background-repeat: no-repeat !important;
  background-size: contain !important;
}
tr >>> .el-date-editor .el-range-separator {
  width: 10%;
}
.flex-wrapper {
  display: flex;
  align-self: center;
}
.flex-wrapper > * {
  margin: 0 5px;
  align-self: center;
}
.flex-wrapper > *:first-child {
  margin-left: 0;
}
.btn-group {
  width: 100%;
}
.editable-area textarea {
  width: 100%;
  min-height: 80px;
  resize: vertical;
  padding: 0 5px;
}
.editable-area input {
  width: 100%;
  padding: 0 5px;
  min-height: 30px;
}
.btn-group >>> .shadow {
  position: fixed;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  background: #0006;
  width: 100%;
  height: 100%;
  z-index: 2;
}
.btn-group >>> .confirmDelete-wrapper {
  position: absolute;
  z-index: 3;
  padding: 20px;
  background: white;
  width: 320px;
  text-align: center;
  top: 30vh;
  left: calc(50% - 210px);
  border-radius: 5px;
  border: 1px solid gray;
}

.img {
  position: relative;
  margin-bottom: 10px;
}
.img img {
  width: 100%;
  height: 200px;
  display: flex;
}
.drop-img {
  position: absolute;
  top: 10px;
  right: 10px;
  cursor: pointer;
}
.images-title {
  margin-bottom: 10px;
  font-size: 16px;
  font-weight: 600;
}
.help-block {
  color: red;
}
</style>
