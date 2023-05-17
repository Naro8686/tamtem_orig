<template>
  <!-- Main content -->
  <section class="content">
    <progressbar v-if="!item"></progressbar>

    <div class="box" v-if="item">
      <div class="row box-body" v-if="item.type_deal == 'buy'">
        <div class="deal-info sell-deal col-md-12">
          <form
            @submit.prevent="validateBeforeSubmit"
            action="javascript:void(0);"
          >
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th class="col-md-4">Параметр</th>
                  <th class="col-md-8">Значение</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Номер сделки</td>
                  <td>{{ item.id }}</td>
                </tr>
                <tr>
                  <td>Тип сделки</td>
                  <td>Покупка</td>
                </tr>
                <tr v-if="item.owner">
                  <td>Пользователь, создавший сделку</td>
                  <td>
                    <router-link
                      :to="{
                        name: 'clients.edit',
                        params: { id: item.owner.id }
                      }"
                      >{{ item.owner.name }} ({{
                        item.owner.unique_id
                      }})</router-link
                    >
                  </td>
                </tr>
                <tr>
                  <td>Описание от поставщика/закупщика</td>
                  <td>
                    <div class="editable-area">
                      <textarea readonly v-model="item.description"></textarea>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Предмет сделки</td>
                  <td>
                    <div class="editable-area">
                      <input
                        v-validate="'required'"
                        data-vv-as="Наименование"
                        data-vv-name="dealname"
                        placeholder="Наименование сделки"
                        type="text"
                        onfocus="this.select()"
                        v-model="item.name"
                        maxlength="52"
                      />
                      <span class="help-block">{{
                        errorsServer.name || errors.collect("dealname")[0]
                      }}</span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Спецификация</td>
                  <td>
                    <div class="editable-area">
                      <wysiwyg
                        v-model="questions.dqh_specification"
                        v-validate="'required'"
                        data-vv-as="Спецификация"
                        data-vv-name="dqh_specification"
                        placeholder="Требуется продукт, физические параметры, упаковка/фасовка"
                      />
                      <span class="help-block">{{
                        errorsServer.dqh_specification ||
                        errors.collect("dqh_specification")[0]
                      }}</span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Файл</td>
                  <td>
                    <div
                      v-if="item.files && item.files.length > 0"
                      class="editable-area"
                    >
                      <div
                        class="file"
                        :key="file.id"
                        v-for="file in item.files"
                      >
                        <div class="file__name">
                          <a :href="file.path">{{ file.name }}</a>
                        </div>
                        <button
                          type="button"
                          @click.prevent="removeFile(file)"
                          class="file__remove"
                        >
                          Удалить
                        </button>
                      </div>
                    </div>
                    <div v-else class="editable-area">
                      <div class="file">
                        <input
                          v-validate="
                            `ext:doc,docx,xls,xlsx,pdf,rar,zip,7z|size:10240`
                          "
                          data-vv-as="Файл"
                          data-vv-name="file"
                          v-if="visibleInput"
                          class="file__input"
                          @change="addFile($event.target.files)"
                          type="file"
                        />
                        <button
                          class="file__remove"
                          @click="abortUploading"
                          type="button"
                        >
                          Удалить
                        </button>
                      </div>
                      <span class="help-block">{{
                        errorsServer.file || errors.collect("file")[0]
                      }}</span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Тип сделки</td>
                  <td>
                    <div class="editable-area">
                      <input
                        v-validate="'required'"
                        data-vv-as="Тип сделки"
                        data-vv-name="dqh_type_deal"
                        placeholder="Постоянный/разовый"
                        type="text"
                        onfocus="this.select()"
                        v-model="questions.dqh_type_deal"
                      />
                      <span class="help-block">{{
                        errorsServer.dqh_type_deal ||
                        errors.collect("dqh_type_deal")[0]
                      }}</span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Объём общий</td>
                  <td>
                    <div class="flex-wrapper">
                      <div class="editable-area">
                        <input
                          v-validate="'required|decimal:6'"
                          data-vv-as="Объём общий"
                          data-vv-name="dqh_volume"
                          placeholder="0"
                          type="text"
                          onfocus="this.select()"
                          v-model="questions.dqh_volume"
                        />
                      </div>
                      <select v-model="operand" name id class="operandtype">
                        <option value="0">*</option>
                        <option value="1">/</option>
                      </select>
                      <div class="editable-area">
                        <input
                          v-validate="'required|decimal:6'"
                          data-vv-as="Единиц в объёме"
                          data-vv-name="count_unit_in_volume"
                          placeholder="0"
                          type="text"
                          onfocus="this.select()"
                          v-model="item.count_unit_in_volume"
                        />
                      </div>
                      <div class="calculate-button-wrapper">
                        <input
                          type="button"
                          class="calculate-button"
                          v-on:click="calculateValforall()"
                          value="="
                        />
                      </div>
                      <div class="editable-area">
                        <input
                          v-validate="'required|decimal:6'"
                          data-vv-as="Общее количество"
                          data-vv-name="val_for_all"
                          placeholder="0"
                          type="text"
                          onfocus="this.select()"
                          v-model="item.val_for_all"
                        />
                      </div>
                      <div class="editable-area">
                        <input
                          v-validate="'required'"
                          data-vv-as="Ед. изм. в срок"
                          data-vv-name="unit_for_all"
                          placeholder="ед. измерения в срок"
                          type="text"
                          onfocus="this.select()"
                          v-model="item.unit_for_all"
                        />
                      </div>
                    </div>
                    <div class="errors-block">
                      <span class="help-block">{{
                        errorsServer.dqh_volume ||
                        errors.collect("dqh_volume")[0]
                      }}</span>
                      <span class="help-block">{{
                        errorsServer.count_unit_in_volume ||
                        errors.collect("count_unit_in_volume")[0]
                      }}</span>
                      <span class="help-block">{{
                        errorsServer.val_for_all ||
                        errors.collect("val_for_all")[0]
                      }}</span>
                      <span class="help-block">{{
                        errorsServer.unit_for_all ||
                        errors.collect("unit_for_all")[0]
                      }}</span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Минимальная партия</td>
                  <td>
                    <div class="editable-area">
                      <input
                        v-validate="'required'"
                        data-vv-as="Минимальная партия"
                        data-vv-name="dqh_min_party"
                        placeholder="кол-во, единица измерения"
                        type="text"
                        onfocus="this.select()"
                        v-model="questions.dqh_min_party"
                      />
                      <span class="help-block">{{
                        errorsServer.dqh_min_party ||
                        errors.collect("dqh_min_party")[0]
                      }}</span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Логистика</td>
                  <td>
                    <div class="editable-area">
                      <textarea
                        v-validate="'required'"
                        data-vv-as="Логистика"
                        data-vv-name="dqh_logistics"
                        v-model="questions.dqh_logistics"
                        placeholder="(Доставка куда, самовывоз из каких областей)"
                        onfocus="this.select()"
                      ></textarea>
                      <span class="help-block">{{
                        errorsServer.dqh_logistics ||
                        errors.collect("dqh_logistics")[0]
                      }}</span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Способ оплаты</td>
                  <td>
                    <div class="editable-area">
                      <textarea
                        v-validate="'required'"
                        data-vv-as="Способ оплаты"
                        data-vv-name="dqh_payment_method"
                        v-model="questions.dqh_payment_method"
                        placeholder="(Наличный, безналичный расчет с НДС или без НДС)"
                        onfocus="this.select()"
                      ></textarea>
                      <span class="help-block">{{
                        errorsServer.dqh_payment_method ||
                        errors.collect("dqh_payment_method")[0]
                      }}</span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Условия оплаты</td>
                  <td>
                    <div class="editable-area">
                      <textarea
                        v-validate="'required'"
                        data-vv-as="Условия оплаты"
                        data-vv-name="dqh_payment_term"
                        v-model="questions.dqh_payment_term"
                        placeholder="(Отсрочка дней, предоплата, по факту поступления продукции на складе покупателя/поставщика)"
                        onfocus="this.select()"
                      ></textarea>
                      <span class="help-block">{{
                        errorsServer.dqh_payment_term ||
                        errors.collect("dqh_payment_term")[0]
                      }}</span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Документы, подтверждающие качество</td>
                  <td>
                    <div class="editable-area">
                      <input
                        v-validate="'required'"
                        data-vv-as="Документы"
                        data-vv-name="dqh_doc_confirm_quality"
                        placeholder="Требуются/не требуются"
                        v-model="questions.dqh_doc_confirm_quality"
                        type="text"
                      />
                      <span class="help-block">{{
                        errorsServer.dqh_doc_confirm_quality ||
                        errors.collect("dqh_doc_confirm_quality")[0]
                      }}</span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Цена от заказчика</td>
                  <td>
                    <div class="flex-wrapper">
                      <p>от</p>
                      <div class="editable-area">
                        <input
                          ref="budget_from"
                          v-validate="'required|price|less:budget_to'"
                          data-vv-as="Цена от"
                          data-vv-name="budget_from"
                          onfocus="this.select()"
                          v-model="item.budget_from"
                          type="text"
                        />
                      </div>
                      <p>до</p>
                      <div class="editable-area">
                        <input
                          ref="budget_to"
                          v-validate="'required|price|more:budget_from'"
                          data-vv-as="Цена до"
                          data-vv-name="budget_to"
                          onfocus="this.select()"
                          v-model="item.budget_to"
                          type="text"
                        />
                      </div>
                      <p>за</p>
                      <div class="editable-area">
                        <input
                          v-validate="'required'"
                          data-vv-as="Единица измерения"
                          data-vv-name="unit_for_unit"
                          onfocus="this.select()"
                          v-model="item.unit_for_unit"
                          type="text"
                        />
                      </div>
                    </div>
                    <div style="padding: 5px 0" class="flex-wrapper">
                      <label for="hasNDS">
                        <input
                          id="hasNDS"
                          v-model="item.budget_with_nds"
                          type="checkbox"
                        />
                        <span>Цена с НДС</span>
                      </label>
                    </div>
                    <div class="errors-block">
                      <span class="help-block">{{
                        errorsServer.budget_from ||
                        errors.collect("budget_from")[0]
                      }}</span>
                      <span class="help-block">{{
                        errorsServer.budget_to || errors.collect("budget_to")[0]
                      }}</span>
                      <span class="help-block">{{
                        errorsServer.unit_for_unit ||
                        errors.collect("unit_for_unit")[0]
                      }}</span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Период поставки</td>
                  <td>
                    <div class="editable-area">
                      <input
                        v-validate="'required'"
                        data-vv-as="Период поставки"
                        data-vv-name="date_of_event"
                        onfocus="this.select()"
                        v-model="item.date_of_event"
                        type="text"
                      />
                      <span class="help-block">{{
                        errorsServer.date_of_event ||
                        errors.collect("date_of_event")[0]
                      }}</span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Дата окончания</td>
                  <td>
                    <div class="editable-area">
                      <input
                        v-validate="'required'"
                        data-vv-as="Дата окончания"
                        data-vv-name="deadline_deal"
                        v-model="item.deadline_deal"
                        type="date"
                      />
                      <span class="help-block">{{
                        errorsServer.deadline_deal ||
                        errors.collect("deadline_deal")[0]
                      }}</span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Категории</td>
                  <td>
                    <div class="editable-area">
                      <treeselect
                        v-model="item.categories[0]"
                        instanceId="categories"
                        :multiple="false"
                        valueFormat="object"
                        :options="categories"
                        :normalizer="getNode"
                      />
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Город</td>
                  <td v-on:click="addSelected(this.event.target)">
                    <cityselect
                      @setCityEmit="setCity"
                      :cityName="cityName"
                    ></cityselect>
                  </td>
                </tr>
                <tr>
                  <td>Наша комиссия с поставщика</td>
                  <td>
                    <div class="flex-wrapper">
                      <input
                        type="button"
                        value="Рассчитать"
                        v-on:click="calculateComission()"
                      />
                      <div class="editable-area">
                        <input
                          v-validate="'required'"
                          data-vv-as="Комиссия"
                          data-vv-name="commission"
                          onfocus="this.select()"
                          v-model="item.commission"
                          type="text"
                        />
                      </div>
                      <p>рублей</p>
                    </div>
                    <span class="help-block">{{
                      errorsServer.commission || errors.collect("commission")[0]
                    }}</span>
                  </td>
                </tr>
                <tr>
                  <td>Средний бюджет</td>
                  <td>
                    <div class="flex-wrapper">
                      <input
                        data-vv-as="Средний бюджет"
                        data-vv-name="budget_all"
                        v-model.number="item.budget_all"
                        type="text"
                        v-validate="'required'"
                      />
                      <p>рублей</p>
                    </div>
                    <span
                      class="help-block"
                      v-if="errorsServer.budget_all || errors.has('budget_all')"
                      >{{
                        errorsServer.budget_all ||
                        errors.collect("budget_all")[0]
                      }}</span
                    >
                  </td>
                </tr>
                <!-- <tr>
                  <td>Наш процент (автоподсчёт), %</td>
                  <td>
                    <div class="editable-area">
                      <input readonly onfocus="this.select()" v-model="item.procent" type="text" />
                    </div>
                  </td>
                </tr>-->
                <tr>
                  <td>Ссылка на сайте</td>
                  <td>
                    <router-link :to="addbidslink(item.id)">{{
                      item.name
                    }}</router-link>
                  </td>
                </tr>
              </tbody>
            </table>
          </form>
        </div>
      </div>

      <div class="row box-body" v-if="item.type_deal == 'sell'">
        <!-- {{item}} -->
        <div class="deal-info sell-deal col-md-12">
          <form
            @submit.prevent="validateBeforeSubmit"
            action="javascript:voiD(0)"
          >
            <table class="table table-bordered table-striped">
              <!-- заголовок таблицы -->
              <thead>
                <tr>
                  <th class="col-md-4">Параметр</th>
                  <th class="col-md-8">Значение</th>
                </tr>
              </thead>
              <!-- тело таблицы -->
              <tbody>
                <tr>
                  <td>Номер сделки</td>
                  <td>{{ item.id }}</td>
                </tr>
                <tr>
                  <td>Тип сделки</td>
                  <td>Продажа</td>
                </tr>
                <tr v-if="item.owner">
                  <td>Пользователь, создавший сделку</td>
                  <td>
                    <router-link
                      :to="{
                        name: 'clients.edit',
                        params: { id: item.owner.id }
                      }"
                      >{{ item.owner.name }} ({{
                        item.owner.unique_id
                      }})</router-link
                    >
                  </td>
                </tr>
                <tr>
                  <td>Описание от поставщика/закупщика</td>
                  <td>
                    <div class="editable-area">
                      <textarea v-model="item.description"></textarea>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Предмет сделки (название объявления)</td>
                  <td>
                    <div class="editable-area">
                      <input
                        v-validate="'required'"
                        data-vv-as="Наименование"
                        data-vv-name="dealname"
                        placeholder="Наименование сделки"
                        type="text"
                        onfocus="this.select()"
                        v-model="item.name"
                        maxlength="52"
                      />
                      <span class="help-block">{{
                        errorsServer.name || errors.collect("dealname")[0]
                      }}</span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Файл</td>
                  <td>
                    <div
                      v-if="item.files && item.files.length > 0"
                      class="editable-area"
                    >
                      <div
                        class="file"
                        :key="file.id"
                        v-for="file in item.files"
                      >
                        <div class="file__name">
                          <a :href="file.path">{{ file.name }}</a>
                        </div>
                        <button
                          type="button"
                          @click.prevent="removeFile(file)"
                          class="file__remove"
                        >
                          Удалить
                        </button>
                      </div>
                    </div>
                    <div v-else class="editable-area">
                      <div class="file">
                        <input
                          v-validate="
                            `ext:jpg,png,doc,docx,xls,xlsx,pdf,rar,zip,7z|size:10240`
                          "
                          data-vv-as="Файл"
                          data-vv-name="file"
                          v-if="visibleInput"
                          class="file__input"
                          multiple
                          @change="addFile($event.target.files)"
                          type="file"
                        />
                        <input
                          data-vv-name="file"
                          data-vv-as="Файл"
                          type="text"
                          v-validate="'max_value:3'"
                          ref="filesGuard"
                          v-model="fileslength"
                          hidden
                        />
                        <button
                          class="file__remove"
                          @click="abortUploading"
                          type="button"
                        >
                          Удалить
                        </button>
                      </div>
                      <span class="help-block">{{
                        errorsServer.file ||
                        errors.collect("file")[0] ||
                        overfilesString
                      }}</span>
                    </div>
                  </td>
                </tr>
                <tr v-if="files && files.length > 0">
                  <td>Загруженные модератором файлы</td>
                  <td>
                    <div>
                      <ul>
                        <li v-for="file in files" :key="file.id">
                          <a :href="file.path">{{ file.name }}</a>
                        </li>
                      </ul>
                      <button @click.prevent="abortUploading">Очистить</button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Категории</td>
                  <td>
                    <div class="editable-area">
                      <treeselect
                        v-model="item.categories[0]"
                        instanceId="categories"
                        :multiple="false"
                        valueFormat="object"
                        :options="categories"
                        :normalizer="getNode"
                      />
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>Город</td>
                  <td v-on:click="addSelected(this.event.target)">
                    <cityselect
                      @setCityEmit="setCity"
                      :cityName="cityName"
                    ></cityselect>
                  </td>
                </tr>

                <!-- ключевые слова -->
                <tr>
                  <td>Ключевые слова от пользователя</td>
                  <td>
                    <div class="editable-area">
                      <input v-model="item.tags" />
                      <!--div class="keywords-old">{{item.tags}}</div-->
                      <div>
                        <button @click.prevent="sendTagsToApprove()">
                          Отправить теги на подписку
                        </button>
                      </div>
                    </div>
                  </td>
                </tr>

                <!-- <tr>
                  <td>Новые теги</td>
                  <td>
                    <div class="keywords-new">
                        <div class="keywords-new__row">
                          <div class="keywords-new__wrapper">
                            <input type="text" class="keywords-new__input" v-model="newTagCurrent">
                          </div>
                          <button class="keywords-new__btn" @click.prevent="addNewTag()">Добавить</button>
                        </div>
                        <div class="keywords-new__box">
                          <ul class="keywords-new__tags">
                            <li :key="tag.id" v-for="tag in newtags">
                              <span>{{tag}}</span>
                              <button @click.prevent="removeFromNewTags(tag)">&times;</button>
                            </li>
                          </ul>
                        </div>
                      </div>
                      
                  </td>
                </tr> -->
                <tr v-if="tagsResponse">
                  <td>Ответ от бэка</td>
                  <td>
                    <input v-model="tagsResponse" />
                    <button
                      @click.prevent="
                        sendTagsTocreateandsubscribe(tagsResponse)
                      "
                    >
                      Создать теги
                    </button>
                  </td>
                </tr>
                <tr>
                  <td>Ссылка на сайте</td>
                  <td>
                    <router-link :to="addbidslink(item.id)">{{
                      item.name
                    }}</router-link>
                  </td>
                </tr>
              </tbody>
            </table>
          </form>
        </div>
      </div>
      <div class="row box-body">
        <div class="col-md-12 frm-buttons">
          <div class="btn-group">
            <input
              class="btn btn-success"
              type="button"
              @click="validateBeforeSubmit()"
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
            <div class="items pull-right">
              <label for="fake">
                <input id="fake" type="checkbox" v-model="item.is_fake" />
                FAKE
              </label>
              <input
                class="btn btn-danger pull-right"
                type="button"
                @click="failModeration()"
                value="Отклонить"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
</template>

<script>
import { Component, Prop, Vue } from "vue-property-decorator";
import ViewMixins from "../../mixins/view";
import Treeselect from "@riophae/vue-treeselect";
import cityselect from "../Components/citySelect/citySelect";
import BootstrapVue from "bootstrap-vue";

import VeeValidate, { Validator } from "vee-validate";
import VeeValidateRu from "vee-validate/dist/locale/ru";
import Cookies from "js-cookie";
VeeValidateRu.messages.decimal = (field) => {
  return (
    "Поле " +
    field +
    " должно быть числовым и может содержать 2 знака после точки."
  );
};
Validator.localize({
  ru: VeeValidateRu
});
Vue.use(VeeValidate, {
  locale: "ru",
  events: "input|blur"
});
Vue.use(BootstrapVue);

Vue.component("cityselect", cityselect);

Vue.component("confirmDelete", {
  template: `
    <div class="shadow">
      <div class="confirmDelete-wrapper clearfix">
      <h3>Вы уверены?</h3>
      <div class="col-md-12 frm-buttons">
        <input class="btn btn-danger" type="button" @click="$emit('delete')" value="Удалить">
        <input class="btn btn-default" type="button" @click="$emit('close')" value="Отмена">
      </div>
    </div>
    </div>
  `
});

export default {
  mixins: [ViewMixins],
  components: {
    Treeselect
  },

  data() {
    return {
      formData: new FormData(),
      operand: 0,
      file: null,
      files: [],
      visibleInput: true,
      viewDelete: false,
      activeTab: 1,
      itemId: "",
      item: null,
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
      },
      keywords: "",
      newtags: new Set(),
      newTagCurrent: "",
      tagsResponse: "",
      fileslength: "",
      overfiles: false,
      overfilesMessage: "Можно загрузить не более трех файлов",
      overfilesString: ""
    };
  },
  beforeMount() {
    // this.loadProcentes();
    this.getCategories();
  },
  mounted() {
    this.loadItem();

    Validator.extend("price", {
      getMessage: (field) =>
        `Поле ${field} должно быть числом и может содержать 2 знака после запятой`,
      validate: (value) => {
        return /^\d*[,.]?\d{0,2}$/.test(value);
      }
    });
    const locale = {
      custom: {
        file: {
          ext: (field, rule) =>
            `Разрешено только 3 файла в следующих форматах: ${[...rule]} `,
          max_value: (field, rule) => `Можно загрузить не более трех файлов`
        }
      }
    };
    this.$validator.localize("ru", locale);
    Validator.extend(
      "less",
      {
        getMessage: (field) =>
          "Минимальная цена должна быть меньше максимальной.",
        validate: (value, [args]) => {
          return (
            parseFloat(value.replace(",", ".")) <
            parseFloat(args.replace(",", "."))
          );
        }
      },
      {
        hasTarget: true
      }
    );
    Validator.extend(
      "more",
      {
        getMessage: (field) =>
          "Максимальная цена должна быть больше минимальной.",
        validate: (value, [args]) => {
          return (
            parseFloat(value.replace(",", ".")) >
            parseFloat(args.replace(",", "."))
          );
        }
      },
      {
        hasTarget: true
      }
    );
  },
  methods: {
    // добавить тег в набор
    addNewTag() {
      if (this.newTagCurrent) {
        this.selectValue(this.newTagCurrent);
      }
    },
    // осуществляет добавление в набор
    selectValue(item) {
      !this.newtags.has(item)
        ? (this.newtags = new Set(this.newtags.add(item)))
        : "";
      this.newTagCurrent = "";
    },
    // исключает тег из набора
    removeFromNewTags(tag) {
      this.newtags.delete(tag);
      this.newtags = new Set(this.newtags);
      this.transformKeywords();
    },
    // возможно понадобится трансформация набора в обычный массив перед отправкой
    transformKeywords() {
      let list = Array.from(this.newtags);
      let string = "";
      list.forEach((item, i, bob) => {
        let word = item.trim();
        // word = word.charAt(0).toUpperCase() + word.slice(1)
        if (i >= 1) {
          string += ", " + word;
        } else {
          string += word;
        }
      });
      return string;
    },
    sendTagsTocreateandsubscribe(tags) {
      let org = this.item.organization.id;
      let tagsDData = new FormData();
      tagsDData.set("tags", tags);
      tagsDData.set("organization_id", org);
      axios
        .post("/admin/api/deals/tags/createandsubscribe", tagsDData)
        .then((resp) => {
          this.saveItem();
        })
        .catch((err) => {
          this.handeErrorResponse(err);
        });
    },

    // отправка тегов на проверку
    sendTagsToApprove() {
      // /admin/api/deals/ tags/subscribe

      // требуется строка тегов, разделенная запятыми, id организации (привязано к юзеру и его сделкам)

      // Возвращает в параметре data, внутри json ответа, строку тэгов, которых нет в БД, разделенную запятыми {"result": true, "data": "кефирный йогурт дядя ваня, гвозди, доски"}
      //console.log('пока все нормально')
      let org = this.item.organization.id;
      // let string = this.transformKeywords()
      let string = this.item.tags;
      let tagsData = new FormData();
      tagsData.set("organization_id", org);
      tagsData.set("tags", string);
      // let fd = tagsData
      // for(let key of fd.keys()) {
      //   console.log(`${key}: ${fd.getAll(key)}`);
      // }
      axios
        .post("/admin/api/deals/tags/subscribe", tagsData)
        .then((resp) => {
          this.tagsResponse = resp.data.data;
        })
        .catch((err) => {
          this.handeErrorResponse(err);
        });
    },
    setDateVal(newVal) {
      this.item.deadline_deal = newVal;
    },
    calculateBudgetAll() {
      this.item.budget_all =
        this.item.val_for_all *
        ((parseFloat(this.item.budget_from) + parseFloat(this.item.budget_to)) /
          2);
    },
    calculateComission() {
      this.calculateBudgetAll();
      const middlebudget =
        (parseFloat(this.item.budget_from.replace(",", ".")) +
          parseFloat(this.item.budget_to.replace(",", "."))) /
        2;
      const middlePrice = middlebudget * this.item.val_for_all;
      if (middlePrice < 100000) {
        this.item.commission = 200;
        this.item.procent = 0;
      } else {
        this.item.commission = Math.floor(middlePrice * 0.002);
        this.item.procent = Math.floor(
          (this.item.commission * 200) / middlePrice
        );
      }
    },
    calculateValforall() {
      if (this.questions.dqh_volume && this.item.count_unit_in_volume) {
        var res;
        if (this.operand == 0) {
          res = this.questions.dqh_volume * this.item.count_unit_in_volume;
        } else {
          res = this.questions.dqh_volume / this.item.count_unit_in_volume;
        }
        if (res) {
          this.item.val_for_all = +res.toFixed(6);
        }
      }
    },
    validateBeforeSubmit() {
      this.$validator.validateAll().then((result) => {
        if (result) {
          this.err = {};

          this.saveItem();
        } else {
          this.showError("Проверьте заполнение формы");
        }
      });
    },
    setCity(cityId) {
      if (cityId) this.item.city = cityId;
    },
    addSelected(elem) {
      if (elem.select) {
        elem.select();
      }
    },
    uploadImage(event) {
      const URL = "/admin/api/deals/upload/image";

      let data = new FormData();
      data.append("name", "my-picture");
      data.append("file", event.target.files[0]);
      data.append("deal_id", this.item.id);
      data.append("user_id", this.user_id);

      let config = {
        header: {
          "Content-Type": "image/png"
        }
      };

      axios.post(URL, data, config).then((response) => {
        this.images = response.data.images;
      });
    },

    deleteImage(id) {
      var params = {};
      params.id = id;
      const URL = "/admin/api/deals/image/delete";
      axios.post(URL, params).then((response) => {
        this.images = response.data.images;
      });
    },
    successModeration() {
      axios
        .post("/admin/api/deals/moderation/success/" + this.$route.params.id)
        .then((resp) => {
          this.item = resp.data;
          this.messageSuccessModeration();
          this.$router.push({
            name: "deals.moderation"
          });
        })
        .catch((err) => {
          this.handleErrorResponse(err);
        });
    },

    failModeration() {
      axios
        .post("/admin/api/deals/moderation/fails/" + this.$route.params.id)
        .then((resp) => {
          this.messageFailsModeration();
          this.$router.push({
            name: "deals.moderation"
          });
        });
    },

    addbidslink(id) {
      return "/bids/" + id;
    },
    loadProcentes() {
      axios
        .get("/admin/api/deals-procent")
        .then((result) => {
          if (result.data.result) {
            this.procentesArray = result.data.data;
          } else {
            this.showError("Произошла ошибка загрузки, попробуйте позднее");
          }
        })
        .catch((err) => {
          console.log(error);
          this.messageLoadError();
        });
    },
    getCategories() {
      axios.get("/admin/api/categories").then((resp) => {
        this.categories = resp.data;
      });
    },
    getNode(node) {
      return {
        id: node.id,
        label: node.name,
        children: node.items
      };
    },
    // загрузка данных
    loadItem() {
      axios.get("/admin/api/deals/" + this.$route.params.id).then((resp) => {
        this.item = resp.data.data;

        this.processedSomething();

        //this.getAddresses();
      });
    },
    processedSomething() {
      if (!this.item.deadline_deal) {
        const curr = new Date();
        const end = curr.setDate(curr.getDate() + 30);
        const endStr = new Date(end);
        const res = `${endStr.getFullYear()}-${
          (endStr.getMonth() + 1 < 10 ? "0" : "") + (endStr.getMonth() + 1)
        }-${(endStr.getDate() < 10 ? "0" : "") + endStr.getDate()}`;
        this.item.deadline_deal = res;
      }

      if (this.item.questions) {
        for (const el in this.item.questions) {
          this.questions[el] = this.item.questions[el].question;
        }
      }

      this.cityName = this.item.cities[0].name;

      this.images = this.item.imagesDeals;
      this.$delete(this.item, "imagesDeals");
    },
    setFormData(data) {
      console.log(data.files);
      for (let key in data) {
        switch (key) {
          case "budget_from":
          case "budget_to":
            if (data[key]) {
              this.formData.set(key, data[key].replace(",", "."));
            }
            break;
          case "budget_with_nds":
          case "is_fake":
            this.formData.set(key, Number(data[key]));
            break;
          case "cities":
            this.formData.set(
              "cities[]",
              data["city"] ? data["city"] : data["cities"][0].id
            );
            break;
          case "categories":
            this.formData.set("categories[]", data[key][0].id);
            break;
          case "questions":
            for (let item in data[key]) {
              let arraykey = `questions[${item}]`;
              this.formData.set(arraykey, data[key][item]);
            }
            break;
          case "files":
            if (this.files.length > 0) {
              this.files.forEach((item, idx) => {
                let arrkey = `files[${idx}]`;
                this.formData.append(arrkey, item);
              });
            }
            break;
            break;
          default:
            this.formData.set(key, data[key]);
            break;
        }
      }
    },
    // пост-запрос с измененными данными
    saveItem() {
      let rawData = this.item;

      rawData.questions = this.questions;
      this.setFormData(rawData);
      if (this.file) {
        this.formData.set("file", this.file);
      }
      // почистить поле загруженных модером файлов, а то они там и останутся
      this.files = [];
      let fd = this.formData;
      for (let key of fd.keys()) {
        console.log(`${key}: ${fd.getAll(key)}`);
      }
      axios
        .post("/admin/api/deals/" + this.item.id, this.formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
        .then((resp) => {
          if (resp.data.result == true) {
            this.messageSaved();
            this.loadItem();
          } else {
            this.showError("Произошла ошибка, попробуйте позднеее");
          }

          //this.$router.push({ name: "deals" });
        })
        .catch((err) => {
          this.handleErrorResponse(err);
        });
    },
    addFile(fileList) {
      if (this.item.type_deal == "buy") {
        this.file = fileList[0];
      }
      if (this.item.type_deal == "sell") {
        this.files = Array.from(fileList);
        this.fileslength = this.files.length;
      }
    },
    abortUploading() {
      this.visibleInput = false;
      this.$nextTick(() => {
        this.visibleInput = true;
        this.file = null;
      });
      if (this.item.type_deal == "sell") {
        this.files = [];
      }
    },
    removeFile(file) {
      axios
        .delete("/api/v1/deals-file/" + file.id, {
          headers: {
            Authorization: `Bearer ${Cookies.get("api_token")}`
          }
        })
        .then((response) => {
          console.log(response);
          if (response.data.result == true) {
            this.messageDeleted();
            // let filtered = this.item.files.filter(item => {
            // 	item.id !== file.id;
            // });
            // this.item.files = [...filtered];
            this.loadItem();
          } else {
            this.showError("Произошла ошибка, попробуйте позднеее");
          }
        })
        .catch((err) => {
          this.showError("Произошла ошибка, попробуйте позднеее");
        });
    },
    deleteItem() {
      console.log("deleted");
      axios.delete("/admin/api/deals/" + this.item.id).then((resp) => {
        this.messageDeleted();
        this.$router.push({
          name: "deals"
        });
      });
    }
  },
  watch: {
    fileslength() {
      if (this.fileslength > 3) {
        this.overfiles = true;
        this.overfilesString = this.overfilesMessage;
      }
      if (this.fileslength <= 3) {
        this.overfiles = false;
        this.overfilesString = "";
      }
    }
  }
};
</script>
<style lang="scss" scoped>
.keywords-new {
  &__row {
    display: flex;
  }
  &__wrapper {
    margin-right: 30px;
  }
  &__push {
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 32px;
    color: #fff;
  }
  &__text {
    display: none;
  }
  &__tags {
    display: flex;
    flex-wrap: wrap;
    padding: 0;
    margin: 0;
    margin-top: 10px;
    li {
      list-style: none;
      font-size: 14px;
      line-height: 18px;
      border-bottom: 1px dashed #000;
      position: relative;
      margin-bottom: 14px;
      &:not(:last-child) {
        margin-right: 27px;
      }
      button {
        background: none;
        border: none;
        position: absolute;
        right: -20px;
        top: -9px;
        font-style: normal;
        font-size: 16px;
      }
    }
  }
}
.file {
  display: flex;
  &__name,
  &__input {
    margin-right: 10px;
    max-width: 200px;
  }
}
.items.pull-right {
  display: flex;
  justify-content: space-between;
  flex-direction: column;
  label {
    color: #ec4025;
  }
}
</style>
<style scoped>
@import "~vue-wysiwyg/dist/vueWysiwyg.css";

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
