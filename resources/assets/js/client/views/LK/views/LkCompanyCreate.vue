<template lang="pug">
.company-edit
  h3.company-edit--title Создание компании
  .card
    .card-body
      form.company-edit--form(
        @submit.prevent='validateBeforeSubmit'
      )
        .horizontal-wrapper(:class="{'empty' : !hasorgData }")
          .vertical-wrapper
            .form-group.form-group--image-wrapper
              .fakeimg(:style="addbackground()")
              .input-wrapper
                input(
                  type="file"
                  placeholder='Выберите фотографию'
                  data-vv-as='Фото'
                  data-vv-name='photo'
                  name="image_field"
                  v-validate=`'isImage'`
                )
                p Добавьте фото,#[br]логотип	
                span.error-text {{ errors.first('photo') }}
            .form-group.form-group--inn
              .input-wrapper(:class="{'errors' : errors.has('org_inn') || reloadButtonVisible || err['org_inn'] }")
                  input(
                      v-model.trim="form.inn"
                      placeholder="Введите ИНН"
                      v-validate="`required|inn`"
                      data-vv-as='ИНН'
                      data-vv-name='org_inn'
                  )
                  button.reload.search(v-if="canSearch" @click="getDataTries++; search()")
                    search
                  .reload(v-if="!errors.has('org_inn') && hasorgData && !loading")
                    successicon(:flag="true")
                  button.reload(@click="getDataTries++; search()" v-if="reloadButtonVisible")
                    reload
                  i.errors-list(v-if="errors.has('org_inn') || err['org_inn']") {{errors.first('org_inn') || err['org_inn'][0]}}
            .form-group.form-group--dadata-mobile(v-if="!isDesktop && hasorgData" )
              dadata-info(:orgData="orgData")
            .form-group.form-group--report.report(v-if="getDataTries>4")
              a.report__btn(@click="addReport")
                envelope
                span.report__label Сообщить о проблеме    
            .form-group.form-group--city
              CitiesSelect(
                @setCityEmit='setCity'
                :cityName='cityName'
                :err='err'
                :disabled="!hasorgData || this.errors.has('org_inn') || !isOrgActive"
                :placeholder="'Город отгрузки товара'"
              )
            .form-group.form-group--phone
              .input-wrapper(:class="{'errors' : errors.has('phone')}")
                input(
                placeholder='Телефон'
                data-vv-as='Телефон'
                data-vv-name='phone'
                v-mask="'+7 (###) ### ##-##'"
                v-validate=`'required|phoneFormat'`
                :disabled="!hasorgData || this.errors.has('org_inn') || !isOrgActive"
                v-model="form.phone"
                )
                i.errors-list(v-if="errors.has('phone')") {{errors.first('phone')}}
            .form-group.form-group--category-wrapper
              CategorySelect(
                @setCategoryEmit='setCategory'
                :category='category'
                :err='err'
                :disabled="!hasorgData || this.errors.has('org_inn') || !isOrgActive"
              )              
          dadata-info(v-if="hasorgData && isDesktop" :orgData="orgData")
        
        .form-group.form-group--description
          p.field-description Описание компании, которое будет появляться в заявках и поиске.
          .input-wrapper(:class="{'errors' : errors.has('org_products') || reloadButtonVisible || err['org_products'] }")
            textarea(
              rows=7
              placeholder='Добавьте описание компании'
              solo
              v-model='form.org_products'
              data-vv-as='Краткое описание компании'
              data-vv-name='org_products'
              v-validate="`required`"
              :disabled="!hasorgData || this.errors.has('org_inn') || !isOrgActive"
            )
            i.errors-list(v-if="errors.has('org_products')") {{errors.first('org_products')}}
        .form-group.form-group--save-button
          button( class="link" type='submit' :disabled="loading || !hasorgData || this.errors.has('org_inn') || !isOrgActive")
            span Создать компанию
      b-modal(
		    modal-class='modal-report'
        ref="modalReport"
	    )
        innsend(@close="closeModal")
        div(slot='modal-footer')
</template>

<script>
import { Validator } from "vee-validate";
import CategorySelect from "../../GeneralComponents/components/CategorySelect";
import CitiesSelect from "../../GeneralComponents/components/CitiesSelect";
import successicon from "../../Catalog/components/BidPointMark";
import dadataInfo from "../components/dadatainfo";
import innsend from "../components/innsend";
import { mask } from "vue-the-mask";
import axios from "axios";
import search from "../../Icons/Search";
import arrowRight from "../../Icons/ArrowRight";

export default {
  components: {
    CategorySelect,
    CitiesSelect,
    successicon,
    dadataInfo,
    innsend,
    search,
    arrowRight
  },
  directives: {
    mask
  },
  data() {
    return {
      category: null,
      loading: false,
      getDataTries: 0,
      err: {},
      form: {
        image_1: null,
        inn: "",
        phone: null,
        org_products: null,
        categories: null,
        org_legal_form_id: 1
      },
      orgData: {},
      cityName: null,
      dadataResult: null,
      formData: new FormData(),
      base64Img: null,
      windowWidth: window.innerWidth
    };
  },
  beforeMount() {
    if (this.$root.profile.profile.is_org_created)
      this.$router.replace({ name: "lk.company.edit" });
    this.form.phone = this.$root.profile.profile.phone;
  },
  created() {
    // this.form.phone = this.userphone;

    Validator.extend("inn", {
      getMessage: field => "ИНН может содержать 10 или 12 цифр",
      validate: value => {
        return !/[^0-9]/.test(value) && [10, 12].indexOf(value.length) !== -1;
      }
    });
    Validator.extend("phoneFormat", {
      getMessage: field => "Неверный формат телефона",
      validate: value => {
        return value.length == 18;
      }
    });
    Validator.extend("isImage", {
      getMessage: field => "Разрешены только изображения",
      validate: value => {
        const extArr = ["png", "jpg", "jpeg", "gif", "bmp"];
        const ext = value[0].name.split(".").pop();
        if (extArr.indexOf(ext) === -1) return false;
        else {
          this.transformFile(value[0]);

          return true;
        }
      }
    });
  },
  mounted() {
    window.addEventListener("resize", () => {
      this.setWindowWidth();
    });
  },
  computed: {
    canSearch() {
      return (
        !this.errors.has("org_inn") &&
        this.form.inn.length != 0 &&
        this.getDataTries == 0
      );
    },
    windowSize() {
      return this.windowWidth;
    },
    isDesktop() {
      return this.windowSize > 768;
    },
    isOrgActive() {
      return (
        Object.keys(this.orgData).length > 0 && this.orgData.org_status.id == 1
      );
    },
    hasorgData() {
      return Object.keys(this.orgData).length > 0;
    },
    reloadButtonVisible() {
      return (
        !this.errors.has("org_inn") &&
        !this.hasorgData &&
        !this.loading &&
        this.form.inn.length != 0 &&
        this.getDataTries > 0
      );
    }
  },
  methods: {
    closeModal() {
      this.$refs.modalReport.hide();
    },
    addReport() {
      this.$refs.modalReport.show();
    },
    setWindowWidth() {
      this.windowWidth = window.innerWidth;
    },
    setCategory(category) {
      const arrCat = [];
      arrCat.push(category);
      this.form.categories = arrCat;
    },
    setCity(cityId) {
      if (cityId) this.form.org_city_id = cityId;
    },
    search() {
      if (!this.errors.has("org_inn")) {
        this.getDatabyInn();
      }
    },
    getDatabyInn() {
      this.loading = true;
      const requestLog = {};

      const apiKey = "f91af2fd3ec6b628f49c9a5208bb0713568cea54";
      const inn = this.form.inn;

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
          this.processResponse(response.data.suggestions);
        })
        .catch(err => {
          if (err.response) {
            this.setLog(err.response);
          }
          this.printErrorOnConsole(err);
        })
        .then(() => {
          this.loading = false;
        });
    },
    setLog(dataObj) {
      const requestLog = {};

      requestLog.url = dataObj.config.url;
      requestLog.method = dataObj.config.method;
      requestLog.data = dataObj.config.data;
      requestLog.status = dataObj.status;
      requestLog.response = dataObj.data;

      axios.post("/api/v1/statistic/log/store", requestLog).then(response => {
        //  console.log(response)
      });
    },
    processResponse(suggestionsArray) {
      const result = {};
      if (suggestionsArray.length == 1) {
        result.org_name = suggestionsArray[0].value;
        result.org_kpp = suggestionsArray[0].data.kpp;
        result.org_director = suggestionsArray[0].data.management
          ? suggestionsArray[0].data.management.name
          : null;
        (result.org_manager_post = suggestionsArray[0].data.management
          ? suggestionsArray[0].data.management.post
          : null),
          (result.org_okved = suggestionsArray[0].data.okved),
          (result.org_status = this.setOrgStatus(
            suggestionsArray[0].data.state.status
          )),
          (result.org_ogrn = suggestionsArray[0].data.ogrn),
          (result.org_registration_date = this.transformDate(
            suggestionsArray[0].data.state.registration_date
          )),
          (result.org_address_legal =
            suggestionsArray[0].data.address.data.source),
          (result.org_inn = suggestionsArray[0].data.inn);
        result.org_address = suggestionsArray[0].data.address.data.city;
      }
      this.orgData = result;
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
    transformDate(unixDate) {
      let date = new Date(unixDate).toLocaleString("ru").split(",")[0];
      let [day, month, year] = date.split(".");
      return `${year}-${month}-${day}`;
    },
    addbackground() {
      let res = "background: url('/images/no-photo.svg') no-repeat center;";

      if (this.base64Img) {
        res = "background: url(" + this.base64Img + ");";
      }
      return res;
    },
    transformFile(file) {
      const app = this;
      if (file) {
        this.form.image_1 = file;
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function() {
          app.base64Img = reader.result;
        };
        reader.onerror = function(error) {
          this.printErrorOnConsole(error);
        };
      }
    },
    validateBeforeSubmit() {
      this.$validator.validateAll().then(result => {
        if (result) {
          this.err = {};
          // вызвать сборку
          this.prepareData(this.orgData, this.form);
        }
      });
    },
    clearPhone(phone) {
      let res = phone.slice(3);
      return res.replace(/[\(\)\ \-]/g, "");
    },
    prepareData(dadataInfo, form) {
      const result = Object.assign(dadataInfo, form);

      for (let key in result) {
        if (result[key]) {
          switch (key) {
            case "categories":
              this.formData.set("categories[]", result[key]);
              break;
            case "stores":
              this.formData.set("stores[]", result[key]);
              break;
            case "org_status":
              this.formData.set("org_is_active", result[key].id);
              break;
            case "phone":
              this.formData.set("phone", this.clearPhone(result[key]));
              break;
            default:
              this.formData.set(key, result[key]);
              break;
          }
        }
      }
      this.companyCreate();
    },
    companyCreate() {
      let path = "/api/v1/organization/manage/store";

      axios
        .post(path, this.formData, {
          headers: { "Content-Type": "multipart/form-data" }
        })
        .then(resp => {
          this.loading = false;
          if (!resp.data.result) {
            this.err = resp.data.error;
          } else {
            this.$root.reLogin();
            this.$store.dispatch("setSnackbar", {
              color: "success",
              text: "Компания создана",
              toggle: true
            });
            if (window.isProdMode) {
              window.ym(76387882, "reachGoal", "company_complete");
            }
            this.$router.push({ name: "lk.profile" });
          }
        })
        .catch(error => {
          this.printErrorOnConsole(error);
          this.loading = false;
          this.err = error.response.data.errors;
          console.log(error.response.data.errors);
          if (this.err.hasOwnProperty("org_inn")) {
            this.getDataTries = 5;
          }
        });
    }
  }
};
</script>
<style lang="scss">
.modal-report {
  @media (max-width: 320px) {
    padding: 0 !important;
  }
  .modal-header {
    .close {
      font: 400 20px/24px "Montserrat", sans-serif;
      background: #cecece;
      display: flex;
      justify-content: center;
      align-items: center;
      transform: none;
      text-shadow: none;
      font-weight: 300;
      border-radius: 6px;
      min-width: 24px;
      min-height: 24px;
      color: #fff;
      &::before,
      &::after {
        display: none;
      }
      &:hover {
        background-color: #27d066;
      }
    }
  }
  .modal-dialog {
    @media (min-width: 720px) {
      width: 457px;
    }
    @media (min-width: 576px) {
      margin-top: 100px;
    }
    @media (max-width: 320px) {
      margin: 0;
    }
  }
  .modal-content {
    border-radius: 10px;
    @media (max-width: 320px) {
      border: none;
      border-radius: 0;
    }
  }
  .modal-body {
    @media (min-width: 720px) {
      padding: 0 86px;
    }
    @media (max-width: 320px) {
      padding: 0 42px;
    }
  }
}
</style>
<style lang="scss" scoped>
@import "~styleguide";

.input-wrapper {
  width: 280px;
  input {
    width: 100%;
    height: 41px;
    border-radius: $border-radius-standart;
    box-shadow: $box-shadow-standart;
    background-color: white;
    font: $font-regular;
    font-size: $fontsize-base;
    color: $color-text-gray;
    padding: 0 20px;
    border-width: 1px;
		border-style: solid;
		// add border color
		border-color: $color-profile-border-line;
    &:active,
    &:focus {
      box-shadow: none;
      border-color: $color-green-elements;
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
    input,
    textarea {
      border: 1px solid $danger;
    }
  }
}
.report {
  font: $font-regular;
  display: flex;
  justify-content: center;
  &__field {
    padding-right: 10px;
    flex-grow: 1;
    input {
      width: 100%;
      height: 16px;
      border-bottom: 1px solid $color-border-gray;
      padding-bottom: 4px;
    }
  }
  &__btn {
    display: flex;
    align-items: center;
  }
  &__label {
    margin: 0;
    margin-left: 10px;
    font-size: 12px;
  }
}
.company-edit {
  max-width: 100%;
  &--title {
    text-align: left;
    font: $font-medium;
    font-size: 22px;
    color: $color-base-dark;
    line-height: 32px;
    margin-bottom: 10px;
  }
  &--form {
    display: flex;
    flex-direction: column;
    align-items: center;
    .horizontal-wrapper {
      display: grid;
      gap: 25px;
      grid-template-columns: 2fr 1fr;
      width: 100%;
      @media (max-width: 768px) {
        display: flex;
        justify-content: center;
      }
      .vertical-wrapper {
        justify-self: flex-end;
      }
      &.empty {
        justify-content: center;
      }
    }
    .form-group {
      max-width: 280px;
      width: 100%;
      margin: 16px 0;
      &--image-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        .fakeimg {
          width: 120px;
          height: 120px;
          overflow: hidden;
          background-repeat: no-repeat !important;
          background-position: center !important;
          background-size: 100% !important;
        }
        .input-wrapper {
          position: relative;
          margin: 15px 0;
          text-align: center;
          input {
            position: absolute;
            left: 0;
            right: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
          }
          p {
            text-decoration: none;
            color: $color-base-dark;
            font: $font-regular;
            font-size: $fontsize-base;
          }
          .error-text {
            color: $danger;
            font: $font-regular;
            font-size: $fontsize-smaller;
            background-color: transparent;
          }
        }
      }
      &--report {
        margin: 20px 0;
      }
      &--inn {
        .input-wrapper {
          position: relative;
          input {
            padding-right: 25px;
          }
          .reload {
            position: absolute;
            height: 20px;
            top: 11px;
            right: 10px;
            display: flex;
            align-items: center;
            outline: none;
            &.search /deep/ svg * {
              stroke: $color-green-elements;
            }
          }
        }
      }
      &--dadata-mobile {
        display: flex;
        justify-content: center;
      }
      &--city {
				/deep/ .typeahead-wrap{
					&[disabled] {
						.form-control.typeahead {
							border-color: $color-grey;
						}
					}
				}
        /deep/ .form-control.typeahead {
          border-radius: $border-radius-standart !important;
          box-shadow: $box-shadow-standart !important;
          border: 1px solid;
          padding-left: 12px;
          border-color: $color-profile-border-line;
          height: 41px;
          color: $color-text-gray;
          font: $font-regular;
          font-size: $fontsize-base;
          &:active,
          &:focus {
            box-shadow: none;
            border-color: $color-green-elements;
          }
        }
      }
      &--description {
        max-width: 100%;
        margin-top: 60px;
        .field-description {
          padding-left: 21px;
          margin-bottom: 5px;
          font: $font-regular;
          font-size: $fontsize-base;
        }
        .input-wrapper {
					width: 100%;
        }
        textarea {
          resize: none;
          width: 100%;
          border-radius: $border-radius-standart;
          box-shadow: $box-shadow-standart;
          background-color: white;
          font: $font-regular;
          font-size: $fontsize-base;
          color: $color-text-gray;
          padding: 13px 18px;
          border-width: 1px;
          border-style: solid;
          border-color: $color-profile-border-line;
          &:active,
          &:focus {
            box-shadow: none;
            border-color: $color-green-elements;
          }
        }
      }
      &--save-button {
        button {
          outline: none;
          width: 228px;
          height: 44px;
          border-radius: $border-radius-big;
          background-color: $color-green-elements;
          cursor: pointer;
          &[disabled] {
            opacity: 0.5;
            cursor: none;
          }
          span {
            font: $font-semibold;
            font-size: $fontsize-base;
            color: #fff;
          }
        }
      }
      input[disabled] {
				background-color: #e9ecef;
				border-color: #ececec;
      }
      /deep/ .v-input--is-disabled {
        .v-input__slot {
          background-color: #e9ecef !important;
          &:active,
          &:focus {
            box-shadow: none;
            border-color: $color-green-elements;
          }
        }
			}
			&--category-wrapper {
				& /deep/ .form-group .v-input__control .v-input__slot {
					background-color: $color-base-light !important;
					box-shadow: none !important;
				}
				& /deep/ .v-input .v-input__control .v-input__slot {
					border: 1px solid $color-profile-border-line;
					padding: 0 0.75rem;
					height: 41px;
					border-radius: 10px;
					input {
						&::placeholder {
							color: $color-text-gray;
							opacity: 1;
						} 
					}
				}
				& /deep/ .v-input.v-input--is-disabled .v-input__control .v-input__slot{
					border-color: #ececec;
				}
			}
    }
  }
}
</style>