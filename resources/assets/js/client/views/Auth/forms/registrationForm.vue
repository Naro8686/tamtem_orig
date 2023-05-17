<template lang="pug">
section.register
	div(v-if="step == 1").register__box
		h4.register__header Регистрация
		form(
			@submit.prevent='prepareData'
			autocomplete='off'
		).form-register
			div.form-group.form-group--inn
				div(:class="{'errors' : errors.has('org_inn') || reloadButtonVisible || (canSearch && !company) || err['organization.org_inn'] }").input-wrapper.input-wrapper--extended
					input(
						v-model="inn"
						placeholder="ИНН компании"
						v-validate="`required|inn`"
						data-vv-as='ИНН'
						data-vv-name='org_inn'
					).input-wrapper__input
					.input-wrapper__action
						successicon.input-wrapper__action-success(:flag="true" v-if="company && canSearch")
						//button.input-wrapper__action-reload(v-show="false")
							reload
						button.input-wrapper__action-search(type="button" v-if="canSearch && !company" title="Получить данные" @click="getDatabyInn()") Найти
					i.errors-list {{ ...errors.collect("org_inn"), ...err['organization.org_inn'] ,(canSearch && !company) ? "Нажмите на кнопку в поле ИНН": "" }}
				p(v-if="company").form-group__message.form-group__message--company {{company.org_name}}
				selectCompanyName(v-if="companiesList.length>0" :companies="companiesList"  @selectCompany="processData").form-group__selectcompany
			div.form-group
				div.input-wrapper(:class="{'errors' : (errors.has('name') || err['name'])}")
					input(
						:disabled="noCompany"
						type="text" 
						placeholder="Имя"
						v-model="form.name"
						data-vv-as='Имя'
						data-vv-name='name'
						maxlength="32"
						tabindex="1"
						v-validate=`'required'`
					).input-wrapper__input
					i.errors-list {{ ...errors.collect('name'), ...err.name}}
			div.form-group
				div.input-wrapper(:class="{'errors' : (errors.has('email') || err['email'])}")
					input(
						:disabled="noCompany"
						placeholder="Введите email"
						v-model="form.email"
						data-vv-as='Email'
						data-vv-name='email'
						v-validate=`'required|email'`
						autocomplete='off'
					).input-wrapper__input
					i.errors-list {{...errors.collect('email'), ...err['email']}}
			div.form-group
				div.input-wrapper(:class="{'errors' : (errors.has('phone') || err['phone'])}")
					input(
						:disabled="noCompany"
						type="text" 
						placeholder="+7 (___) ___ __ __"
						v-model="form.phone"
						data-vv-as='Телефон'
						data-vv-name='phone'
            v-mask="['+7 (###) ###-##-##']"
						v-validate=`'required|mobilePhone'`
						autocomplete='off'
					).input-wrapper__input
					i.errors-list {{...errors.collect('phone'), ...err['phone']}}
			div.form-group
				div.input-wrapper.input-wrapper--extended(:class="{'errors' : (errors.has('password') || err['password'])}")
					input(
						:disabled="noCompany"
						:type="isPassOpen ? 'text' :'password'" 
						placeholder="Пароль"
						v-model="form.password"
						data-vv-as='Пароль'
						data-vv-name='password'
						v-validate=`'required|min:6'`
					).input-wrapper__input
					i.errors-list {{...errors.collect('password'), ...err['password']}}
					div.input-wrapper__action
						button(type="button" @click="openPassword" :class="{'active' : isPassOpen }").input-wrapper__action-eye
			div.input-wrapper(:class="{'errors' : (errors.has('license_agreement'))}")
				div.auth__check
					input(
						:disabled="noCompany"
						v-model="form.license_agreement"
						type="checkbox"
						id="register-agree"
						data-vv-as='Согласие'
						data-vv-name='license_agreement'
						v-validate="'required'"
						required
					).auth__check-input
					label(for="register-agree" :class="{'req': errors.has('license_agreement')}").auth__check-label 
						| Я принимаю условия #[a(href='/files/agreement.pdf') Пользовательского соглашения] и даю свое согласие на обработку персональных данных на условиях и целях, определенных #[a(href="/files/politic.doc") политикой конфиденциальности]
				i.errors-list {{...errors.collect('license_agreement')}}
				div.auth__check.supplier
					input(
						:disabled="noCompany"
						v-model.number="form.notice_allowed"
						type="checkbox"
						id="supplier-agree"
					).auth__check-input
					label(for="supplier-agree").auth__check-label 
						| Я - поставщик, хочу получать персональные уведомления о новых заказах по эл. почте.
			button(
				type='button'
				@click="prepareData()"
			).auth__submit
				span Зарегистрироваться 
		.register__links
			p.register__text Есть аккаунт?
			a.register__link(
				href="javascript:void(0);" 
				@click="$root.$emit('showForm','authorizationForm')") Войти
	//- step 2 - остался один шаг
	//div(v-if="step == 2").register__box
		h4.register__header Остался #[br] 1 шаг
		form.form-register(
			@submit.prevent="prepareData"
		)
			.form-group.form-group__categories
				p.form-group__title.register__cap Чем вы заинтересованы?
				categories-select(
					@setCategoryEmit='setCategory'
					:category='category'
					:err='err'
				)
			.form-group.form-group__more-categories
				p.form-group__title.register__cap
					span(v-if="form.is_need_more_categories===null") Нужно больше категорий #[br] для профиля?
					span(v-else) Спасибо, мы учтём #[br] ваши пожелания
				button(
					type="button" 
					@click="form.is_need_more_categories=1" 
					:class="{'selected' : form.is_need_more_categories===1, 'unselected' : form.is_need_more_categories===0}"
				).more-categories-button.more-categories-button--yes
				button(
					type="button" 
					@click="form.is_need_more_categories=0" 
					:class="{'selected' : form.is_need_more_categories===0, 'unselected' : form.is_need_more_categories===1}"
				).more-categories-button.more-categories-button--no
			.form-group
				button(type='submit' :disabled="loading").auth__submit
					span Готово
	//- step 3 - спасибо, что вы с нами
	div(v-if="step == 3").register__box.register__box
		.register__pic
				feather(type='check')
		h4.register__header.register__header--confirm Спасибо,#[br]что вы с нами!
		div.register__confirm
			p.register__confirm-text На #[b {{form.email}}] отправлено письмо для активации аккаунта
			//- p.register__confirm-text Откройте письмо и перейдите по ссылке
		div.register-confirm.bid-data(v-if="bidInfo")
			h6.bid-data__title Заказ для вас
			.bid-data__item.bid-info(v-if="bidInfo.title")
				.bid-info__title Наименование
				.bid-info__text {{bidInfo.title}}
			.bid-data__item.bid-info(v-if="bidInfo.bidType")
				.bid-info__title Тип сделки
				.bid-info__text {{bidInfo.bidType}}
			.bid-data__item.bid-info(v-if="bidInfo.volume")
				.bid-info__title Объём
				.bid-info__text {{bidInfo.volume}}
			.bid-data__item.bid-info(v-if="bidInfo.minParty")
				.bid-info__title Мин. партия
				.bid-info__text {{bidInfo.minParty}}
			.bid-data__item.bid-info(v-if="bidInfo.price")
				.bid-info__title Цена
				.bid-info__text {{bidInfo.price}}
			.bid-data__item.button-more
				router-link.button-more__link(:to="{name:'bids.detail',params:{id:agent.agent_bid_id}}") Подробнее		
</template>

<script>
import Cookies from "js-cookie";
import { Validator } from "vee-validate";
import categoriesSelect from "../../GeneralComponents/components/CategorySelect";
import selectCompanyName from "../../GeneralComponents/components/selectCompanyName";
import successicon from "../../Catalog/components/BidPointMark";
import { mask } from "vue-the-mask";
import { mapActions, mapState, mapGetters } from "vuex";
export default {
  components: {
    categoriesSelect,
    successicon,
    selectCompanyName
  },
  directives: {
    mask
  },
  data() {
    return {
      reloadButtonVisible: false,
      isPassOpen: false,
      step: 1,
      category: null,
      err: {},
      loading: false,
      companiesList: [],
      company: null,
      inn: "",
      // data from cookies
      agent: {
        agent_id: null,
        agent_inn: null,
        agent_bid_id: null
      },
      bidInfo: null,
      form: {
        // is_need_more_categories: null,
        license_agreement: false,
        name: "",
        email: "",
        phone: "",
        password: "",
        categories: null,
        organization: null,
        notice_allowed: false
      }
    };
  },
  created() {
    this.checkRefererData();
    Validator.extend("mobilePhone", {
      field: field => `Поле ${field} имеет ошибочный формат`,
      validate: value => {
        return value.replace(/[^0-9]/gi, "").replace(/^[78]/, "").length == 10;
      }
    });
    Validator.extend("inn", {
      getMessage: field => "ИНН может содержать 10 или 12 цифр",
      validate: value => {
        return !/[^0-9]/.test(value) && [10, 12].indexOf(value.length) !== -1;
      }
    });
  },
  watch: {
    $route() {
      this.$emit("close");
    },
    inn: {
      handler(newVal) {
        this.company = null;
      }
    }
  },
  computed: {
    ...mapState("categories", ["categoriesArray"]),
    canSearch() {
      return !this.errors.has("org_inn") && this.inn.length != 0;
    },
    noCompany() {
      return this.company == null;
    },
    ...mapGetters("createBid", ["getStep"]),
    currentStep: {
      get() {
        return this.getStep("step_subzero");
      },
      set(data) {
        this.saveStep({ step: "step_subzero", data: data });
      }
    },
    fakeCategorySelectResult() {
      const category =
        this.categoriesArray &&
        this.categoriesArray[0].items &&
        this.categoriesArray[0].items[0]
          ? this.categoriesArray[0].items[0].id
          : 2;
      return category;
    }
  },
  methods: {
    ...mapActions("createBid", ["saveStep"]),
    checkRefererData() {
      const agentInn = Cookies.get("tamtem-ref-inn");
      const agentId = Cookies.get("tamtem-ref-agent-id");
      const agentBidId = Cookies.get("tamtem-ref-bid-id");
      this.agent.agent_id = agentId;
      this.agent.agent_inn = agentInn;
      this.agent.agent_bid_id = agentBidId;
      // if (this.agent.agent_bid_id){
      // 	this.loadBidInfo();
      // }
      this.agent.agent_inn ? (this.inn = this.agent.agent_inn) : null;
      if (this.inn != "") {
        this.getDatabyInn();
      }
    },
    // loadBidInfo(){
    // 	axios.get(`/api/v1/deals/${this.agent.agent_bid_id}`).then(response=>{
    // 		if (response.data.result==true){
    // 			this.processBidData(response.data.data)
    // 		}
    // 	}).catch(err=>{
    // 		this.printErrorOnConsole(err);
    // 	})
    // },
    // processBidData(bidData){
    // 	this.bidInfo = {
    // 		title: bidData.name,
    // 		bidType: bidData.questions.dqh_type_deal.question,
    // 		volume: `${this.priceFormat(bidData.questions.dqh_volume.question)} ${bidData.unit_for_all}`,
    // 		minParty: `${bidData.questions.dqh_min_party.question}`,
    // 		price: `${this.priceFormat(bidData.budget_from)} - ${this.priceFormat(bidData.budget_to)} ₽/${bidData.unit_for_unit}`
    // 	}
    // },
    openPassword() {
      this.isPassOpen = !this.isPassOpen;
    },
    getDatabyInn() {
      this.loading = true;

      const apiKey = "f91af2fd3ec6b628f49c9a5208bb0713568cea54";
      const inn = this.inn;

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
        .then(response => {
          const filteredArray = response.data.suggestions.filter(item => {
            return item.data.state.status == "ACTIVE";
          });

          if (filteredArray == 0) {
            this.$store.dispatch("setSnackbar", {
              color: "error",
              text: `Компания не найдена`,
              toggle: true
            });
          } else {
            filteredArray.length == 1
              ? this.processData(filteredArray[0])
              : (this.companiesList = filteredArray);
          }
        })
        .catch(err => {
          this.$store.dispatch("setSnackbar", {
            color: "error",
            text: `Произошла ошибка, попробуйте позднее`,
            toggle: true
          });
          this.printErrorOnConsole(err);
        })
        .then(() => {
          this.loading = false;
        });
    },
    processData(suggestion) {
      const result = {};

      result.org_name = suggestion.value;
      result.org_kpp = suggestion.data.kpp;
      result.org_director = suggestion.data.management
        ? suggestion.data.management.name
        : null;
      (result.org_manager_post = suggestion.data.management
        ? suggestion.data.management.post
        : null),
        (result.org_okved = suggestion.data.okved),
        (result.org_ogrn = suggestion.data.ogrn),
        (result.org_registration_date = this.transformDate(
          suggestion.data.state.registration_date
        )),
        (result.org_address_legal = suggestion.data.address.data.source),
        (result.org_inn = suggestion.data.inn);
      result.org_address = suggestion.data.address.data.city;

      this.company = result;
      this.$set(this, "companiesList", []);
    },
    transformDate(unixDate) {
      let date = new Date(unixDate).toLocaleString("ru").split(",")[0];
      let [day, month, year] = date.split(".");
      return `${year}-${month}-${day}`;
    },
    // setCategory(category) {
    // 	const arrCat = [];
    // 	arrCat.push(category);
    // 	this.form.categories = arrCat;
    // },
    prepareData() {
      this.yandexReachGoal("registration_button_ok");
      this.googleReachGoal("registration_button_ok");

      this.$validator.validateAll().then(result => {
        if (result && this.company) {
          this.formSend();
        }
      });
    },
    formSend() {
      this.err = {};
      this.loading = true;

      let data = Object.assign({}, this.form);
      data.organization = {};
      for (const item in this.company) {
        if (this.company[item] != null) {
          this.$set(data.organization, item, this.company[item]);
        }
      }
      data.phone = data.phone
        .replace(new RegExp(/['+','\-',' ','(',')']/, "g"), "")
        .replace(/^[78]/, "")
        .replace(/[^0-9]/gi, "");
      delete data.organization.org_status;

      data.to_register_user = this.getStep("step_subzero").needAuth;

      const arrCat = [];
      arrCat.push(this.fakeCategorySelectResult);
      data.categories = arrCat;

      data.notice_allowed = data.notice_allowed | 0;

      if (this.agent.agent_id && this.agent.agent_inn) {
        data.agent_id = this.agent.agent_id;
        data.agent_inn = this.agent.agent_inn;
        if (this.agent.agent_bid_id) {
          data.agent_bid_id = this.agent.agent_bid_id;
        }
      }

      axios
        .post("/api/v1/register/user", data)
        .then(resp => {
          if (this.inn == this.agent.agent_inn) {
            Cookies.remove("tamtem-ref-inn");
            Cookies.remove("tamtem-ref-agent-id");
            Cookies.remove("tamtem-ref-org-name");
            Cookies.remove("tamtem-ref-bid-id");
          }
          if (data.to_register_user == 1) {
            if (resp.data.result == true) {
              let dataToSave = Object.assign({}, this.getStep("step_subzero"));
              dataToSave.token = resp.data.data.api_token;
              this.saveStep({ step: "step_subzero", data: dataToSave });
            } else {
              this.$store.dispatch("setSnackbar", {
                color: "error",
                text: `Произошла ошибка, попробуйте позднее`,
                toggle: true
              });
            }
          } else {
            this.step = 3;
          }
        })
        .catch(error => {
          this.err = error.response.data.errors;
          this.step = 1;
        })
        .then(() => {
          this.loading = false;
        });
    }
  }
};
</script>

<style lang="scss" scoped>
@import "~styleguide";
@import "~mixins";
$search-icon: "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciICAgICB3aWR0aD0iMjgiICAgICBoZWlnaHQ9IjI4IiAgICAgdmlld0JveD0iMCAwIDE4IDE4IiAgICAgYXJpYS1sYWJlbGxlZGJ5PSJzZWFyY2giPiAgICA8ZyBmaWxsPSJub25lIiAgICAgZmlsbC1ydWxlPSJldmVub2RkIj4gICAgPHBhdGggc3Ryb2tlPSIjRDhEOEQ4IiAgICAgICAgICBkPSJNLTU1OC41LTQyNy41aDgxNnY2MjZoLTgxNnoiIC8+ICAgIDxwYXRoIHN0cm9rZT0iI0Q4RDhEOCIgICAgICAgICAgZD0iTS0xNDQuNS01OC41aDI5N3YxNDBoLTI5N3oiIC8+ICAgIDxnIHN0cm9rZT0iIzJmYzA2ZSIgICAgICAgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiAgICAgICBzdHJva2UtbGluZWpvaW49InJvdW5kIiAgICAgICBzdHJva2Utd2lkdGg9IjIiICAgICAgIHRyYW5zZm9ybT0idHJhbnNsYXRlKDEgMSkiPiAgICAgIDxjaXJjbGUgY3g9IjYuNjY3IiAgICAgICAgICAgICAgY3k9IjYuNjY3IiAgICAgICAgICAgICAgcj0iNi42NjciIC8+ICAgICAgPHBhdGggZD0iTTE2IDE2bC00LjYyMi00LjYyMiIgLz4gICAgPC9nPiAgPC9nPjwvc3ZnPg==";
$eye-active: "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxOSIgaGVpZ2h0PSIxMS4wODMiIHZpZXdCb3g9IjAgMCAxOSAxMS4wODMiPiAgPHBhdGggaWQ9ImV5ZS01IiBkPSJNOS41MTIsNi41ODNhMTAuMzMsMTAuMzMsMCwwLDEsNy41MjQsMy42N0MxNS45MjcsMTEuNzA4LDEzLjMsMTQuNSw5LjUxMiwxNC41Yy0zLjUsMC02LjI3OC0yLjgtNy41LTQuMjgxQTEwLjQzMSwxMC40MzEsMCwwLDEsOS41MTIsNi41ODNaTTkuNTEyLDVDMy41Miw1LDAsMTAuMTg2LDAsMTAuMTg2czMuODI4LDUuOSw5LjUxMiw1LjljNi4xMjIsMCw5LjQ4OC01LjksOS40ODgtNS45UzE1LjYsNSw5LjUxMiw1Wk05LjUsNy4zNzVhMy4xNjcsMy4xNjcsMCwxLDAsMy4xNjcsMy4xNjdBMy4xNjcsMy4xNjcsMCwwLDAsOS41LDcuMzc1WiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMCAtNSkiIGZpbGw9IiMyZmMwNmUiLz48L3N2Zz4=";
$eye-inactive: "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxOSIgaGVpZ2h0PSIxNC42MDUiIHZpZXdCb3g9IjAgMCAxOSAxNC42MDUiPiAgPHBhdGggaWQ9ImV5ZS04IiBkPSJNMTUuNTIsMi41NjMsMTIuODcyLDUuMDQ2YTEwLjQ2OSwxMC40NjksMCwwLDAtMy4zNi0uNTUzQzMuNTIsNC40OTIsMCw5LjY3OSwwLDkuNjc5YTE2LjM4NSwxNi4zODUsMCwwLDAsNC4wNzMsNC4wNjhsLTIuMywyLjMsMS4xMTksMS4xMTlMMTYuNjQsMy42ODJaTTEwLjc1Nyw3LjEzN2EzLjE1NiwzLjE1NiwwLDAsMC00LjE2OCw0LjEyMUw1LjIyMywxMi42MDlhMTQuMzg4LDE0LjM4OCwwLDAsMS0zLjIxNC0yLjksMTAuNDMyLDEwLjQzMiwwLDAsMSw3LjUtMy42MzYsOC44LDguOCwwLDAsMSwyLjA2Ni4yNDlaTTguNDc1LDEzLjAyLDEyLjUsOS4wNjdBMy4xNTksMy4xNTksMCwwLDEsOC40NzUsMTMuMDJaTTE5LDkuNjc5cy0zLjM2Niw1LjktOS40ODgsNS45YTguNjQxLDguNjQxLDAsMCwxLTMuMDQ5LS41ODNMNy43NDEsMTMuNzRhNi44LDYuOCwwLDAsMCwxLjc3MS4yNTNjMy43OTMsMCw2LjQxNS0yLjc5Miw3LjUyNC00LjI0N0ExMi4xMiwxMi4xMiwwLDAsMCwxNC4yLDcuNDA5bDEuMTc4LTEuMTU2QTEzLjE1NCwxMy4xNTQsMCwwLDEsMTksOS42NzlaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwIC0yLjU2MykiIGZpbGw9IiNhNWE1YTUiLz48L3N2Zz4=";

$color-disagree: #ed5448;
@mixin hamster-field {
  background: $color-base-light;
  border-radius: 6px;
  border: 1px solid $color-border-gray;
  min-height: 43px;
  padding: 0 12px;
  display: flex;
  align-items: center;
}

.bid-data {
  display: flex;
  flex-direction: column;
  padding: 10px;
  border-radius: $border-radius-standart;
  border: 1px solid $color-border-gray;
  background-color: $color-base-gray-light;
  margin-top: 30px;
  &__title {
    font: $font-medium;
    font-size: 20px;
    text-align: center;
    margin: 22px auto;
  }
  &__item {
    display: flex;
    flex-direction: row;
  }
}
.button-more {
  margin: 30px auto;
  justify-content: center;
  &__link {
    @include big-rounded-btn();
    text-transform: none;
    height: 41px;
    text-decoration: none;
  }
}
.bid-info {
  font: $font-medium;
  margin-bottom: 17px;
  &__title,
  &__text {
    display: flex;
    line-height: 14px;
  }
  &__title {
    font-size: 10px;
    margin-right: 4px;
    color: $color-text-gray;
    width: 60%;
    justify-content: end;
  }
  &__text {
    justify-content: start;
    margin-left: 4px;
    font-size: $fontsize-smaller;
    width: 80%;
    color: #000;
  }
}
.input-wrapper {
  position: relative;
  &__input {
    @include hamster-field;
    width: 100%;
    padding-right: 20px;
    background-color: #f6f6f6;
    &::placeholder {
      color: #707070;
    }
    &:active,
    &:focus {
      box-shadow: none;
      border-color: $color-base-accent;
      background-color: #ffffff;
    }
  }
  &--extended {
    .input-wrapper__input {
      padding-right: 25px;
      &:focus ~ .input-wrapper__action {
        & /deep/ svg {
          stroke: $color-base-accent;
        }
      }
    }
  }
  &__action {
    position: absolute;
    height: 20px;
    top: 11px;
    right: 10px;
    display: flex;
    align-items: center;
    button {
      cursor: pointer;
      outline: none;
    }
    &-search {
      font-size: 12px;
      color: #2FC06E;
      height: 20px;
      background-size: contain;
    }
    &-eye {
      width: 20px;
      height: 20px;
      border: none;
      background: url($eye-inactive);
      background-position: center;
      background-repeat: no-repeat;
      &.active {
        background: url($eye-active);
        background-position: center;
        background-repeat: no-repeat;
      }
    }
  }
  &.errors {
    .errors-list {
      margin: 5px 0;
      padding: 0px 10px;
      font: $font-regular;
      font-size: 10px;
      color: $danger;
      display: block;
    }
    input {
      border: 1px solid $danger;
    }
  }
}
.register {
  &__header {
    color: $color-text-bright;
    font: $font-medium;
    font-size: 32px;
    line-height: 42px;
    align-self: flex-start;
    margin-bottom: 54px;
    &--confirm {
      text-align: center;
      margin-bottom: 40px;
    }
    @media (max-width: 767px) {
      font-size: 26px;
      line-height: 36px;
    }
  }
  &__box {
  }
  &__cap {
    color: $color-text-dark;
    font: $font-medium;
    font-size: 14px;
    font-weight: 400;
    margin-bottom: 11px;
  }
  &__pic {
    margin: 30px auto;
    border-radius: 50%;
    border: 2px solid $color-base-accent;
    width: 72px;
    height: 72px;
    display: flex;
    justify-content: center;
    align-items: center;
    & /deep/ svg {
      width: 30px;
      height: auto;
      stroke: $color-base-accent;
      stroke-width: 3;
    }
  }
  &__confirm {
    display: flex;
    flex-direction: column;
    align-items: center;
    &-text {
      text-align: center;
      font: $font-regular;
      font-size: $fontsize-base;
    }
    &-link {
      color: $color-text-bright;
      font: $font-semibold;
      font-size: 14px;
      cursor: pointer;
      border: none;
      background: none;
      padding: 0;
      margin-top: 20px;
    }
  }
  &__confirm--success {
    //todo
  }
  .form-group {
    margin-bottom: 26px;
    @media (max-width: 576px) {
      margin-bottom: 22px;
    }
    &__more-categories {
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      p {
        flex-basis: 70%;
      }
    }
    &__message {
      font-size: 11px;
      line-height: 16px;
      padding-left: 14px;
      margin-top: 10px;
      &--company {
        color: $color-base-accent;
        text-transform: uppercase;
      }
    }
    &__selectcompany {
      position: absolute;
      left: 0;
      top: 100%;
    }
    &__categories {
      & /deep/ .v-text-field .v-input__slot {
        height: 49px;
        box-shadow: none !important;
        background-color: #f6f6f6 !important;
        .v-icon {
          color: #000;
        }
      }
    }
    &--inn {
      position: relative;
    }
  }
  .more-categories-button {
    border-radius: 50%;
    border: 1px solid;
    width: 32px;
    height: 32px;
    font-size: 16px;
    font-weight: 600;
    &--yes {
      color: $color-green-elements;
      border-color: $color-green-elements;
      &.selected {
        color: white;
        background-color: $color-green-elements;
      }
      &.unselected {
        opacity: 0.31;
      }
      &:before {
        content: "\2713";
      }
    }
    &--no {
      color: $color-disagree;
      border-color: $color-disagree;
      &.selected {
        color: white;
        background-color: $color-disagree;
      }
      &.unselected {
        opacity: 0.31;
      }
      &:before {
        content: "\2014";
      }
    }
  }
  &__text {
    margin-top: 28px;
    font-size: $fontsize-base;
    margin-bottom: 5px;
    color: $color-text-dark;
  }
  &__links {
    text-align: center;
  }
  &__link {
    color: $color-text-bright;
    font: $font-semibold;
    font-size: 14px;
    cursor: pointer;
    border: none;
    background: none;
    padding: 0;
  }
  &__msgbox {
    & /deep/ p {
      font-size: 14px;
    }
  }
}

// эти стили первоначально определены в корневом компоненте авторизации LoginPopup и работали через /deep/. Поскольку компоненты Register и Login отделяются, эти стили нужно определять в каждом. Сделать scss регистрации и импортировать куда-нибудь ?
.auth__submit {
  @include big-rounded-btn;
	@include btnHov($color-hover-button);
  @media (max-width: 576px) {
    width: 100%;
  }
  margin: 42px auto 0;
}
.auth__check {
  font: $font-regular;
  font-size: 12px;
  display: flex;
  align-items: center;
  min-height: 20px;
  &.supplier {
    margin-top: 20px;
  }
  span {
    display: block;
    padding-top: 3px;
  }
  .auth__check-input {
    display: none;
  }
  .auth__check-label {
    position: relative;
    padding-left: 28px;
    margin: 0;
    a {
      color: $color-base-accent;
    }
  }
  .auth__check-input + .auth__check-label::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 4px;
    border: 1px solid $color-gray-dark;
  }
  .auth__check-input:checked + .auth__check-label::before {
    background: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4LjgiIGhlaWdodD0iNi41NTgiIHZpZXdCb3g9IjAgMCA4LjggNi41NTgiPiAgICA8ZGVmcz4gICAgICAgIDxzdHlsZT4gICAgICAgICAgICAuY2xzLTF7ZmlsbDpub25lO3N0cm9rZTojZmZmO3N0cm9rZS1saW5lY2FwOnJvdW5kO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2UtbWl0ZXJsaW1pdDoxMDtzdHJva2Utd2lkdGg6MnB4fSAgICAgICAgPC9zdHlsZT4gICAgPC9kZWZzPiAgICA8ZyBpZD0iY2hlY2stc3F1YXJlIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgxIDEpIj4gICAgICAgIDxwYXRoIGlkPSJTaGFwZSIgZD0iTS0xLjUyNSAyLjM3OGwyLjA1MyAyLjE4IDIuNzMxLTIuNjIyTDUuMjc1IDAiIGNsYXNzPSJjbHMtMSIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMS41MjUpIi8+ICAgIDwvZz48L3N2Zz4=),
      $color-base-accent;
    background-repeat: no-repeat, no-repeat;
    background-size: 70%, contain;
    background-position: center;
    border-color: $color-base-accent;
  }
}
</style>