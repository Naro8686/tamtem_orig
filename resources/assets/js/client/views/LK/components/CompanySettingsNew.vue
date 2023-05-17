<template lang="pug">
.content
  .company
    form.profile__form(@submit.prevent="validateBeforeSubmit")
      .company__top-block
        .company__left-block
          CustomInput(dataId="companyName", dataTitle="Название Компании", dataName="companyName", disabled, :dataValue="profile.company.organization.name", message="Это поле нельзя изменить")
          p.labelparagraph Город
          CitiesSelect(@setCityEmit="setCity", :cityName="cityName", :err="err", :placeholder="'Город отгрузки товара'")
          CustomInput(dataId="companyINN", dataTitle="ИНН", dataName="companyINN", :dataValue="form.inn", disabled, message="Это поле нельзя изменить")
          CustomInput(dataId="companyKPP", dataTitle="КПП", dataName="companyKPP", disabled, :dataValue="profile.company.organization.kpp", message="Это поле нельзя изменить")
          CustomInput(dataId="companyDirector", dataTitle="ФИО руководителя", dataName="companyDirector", disabled, :dataValue="profile.company.organization.director", message="Это поле нельзя изменить")
          .custom-input-block
            input#fullname(
              placeholder="Телефон", 
              data-vv-as="Телефон",
              data-vv-name="phone", 
              v-mask="['+7 (###) ### ##-##']", 
              v-validate=`'required|phoneFormat'`, 
              v-model="form.phone"
              ).custom-input-input
            label.custom-input-label(for="fullname") Телефон организации
          p.labelparagraph Категория
          CategorySelect(@setCategoryEmit="setCategory", :category="category", :err="err")
        .company__right-block
          .company__logo-block
            img#company__logo.company__logo(:style="addbackground()")
          .company__logo-change
            input#logo-img.company__logo-change-input(type="file", placeholder="Выберите фотографию", data-vv-as="Фото", data-vv-name="photo", name="image_field", v-validate=`'isImage'`)
            label(for="logo-img") ИЗМЕНИТЬ ЛОГОТИП
      .company__bottom-block
        .custom-input-block
          textarea#description.custom-textarea(rows=7, placeholder="Добавьте описание компании", solo, v-model="form.org_products", data-vv-as="Добавьте краткое описание компании", data-vv-name="org_products", v-validate="`required`")
          label.custom-input-label(for="description")
            | Описание Компании, которое будет появляться в
            | заголовках и поиске
        .custom-btn-green
          button.custom-btn-green__btn.profile__btn(type="submit", :disabled="loading")
            | Сохранить
</template>

<script>
import { Validator } from "vee-validate"
import CategorySelect from "../../GeneralComponents/components/CategorySelect"
import CitiesSelect from "../../GeneralComponents/components/CitiesSelect"
import innsend from "../components/innsend"
import { mask } from "vue-the-mask"
import axios from "axios"
import CustomInput from "../../GeneralComponents/components/CustomInput"

export default {
  components: {
    CategorySelect,
    CitiesSelect,
    innsend,
    CustomInput
  },
  directives: {
    mask
  },
  props: {
    profile: {
      type: Object
    }
  },
  data() {
    return {
      category: null,
      loading: false,
      err: {},
      form: {
        image_1: null,
        inn: "",
        phone: null,
        org_products: null,
        categories: null,
        org_legal_form_id: 1
      },
      cityName: null,
      formData: new FormData(),
      base64Img: null,
      windowWidth: window.innerWidth
    }
  },
  created() {
    // this.form.phone = this.userphone;

    Validator.extend("phoneFormat", {
      getMessage: (field) => "Неверный формат телефона",
      validate: (value) => {
        return value.length == 18
      }
    })
    Validator.extend("isImage", {
      getMessage: (field) => "Разрешены только изображения",
      validate: (value) => {
        const extArr = ["png", "jpg", "jpeg", "gif", "bmp"]
        const ext = value[0].name.split(".").pop()
        if (extArr.indexOf(ext) === -1) return false
        else {
          console.log("kek")
          this.transformFile(value[0])

          return true
        }
      }
    })
  },
  mounted() {
    window.addEventListener("resize", () => {
      this.setWindowWidth()
    })
    if (this.profile) {
      this.updateFields()
    }
  },
  computed: {
    windowSize() {
      return this.windowWidth
    },
    isDesktop() {
      return this.windowSize > 768
    }
  },
  watch: {
    profile: function (newVal, oldVal) {
      this.updateFields()
    }
  },
  methods: {
    closeModal() {
      this.$refs.modalReport.hide()
    },
    addReport() {
      this.$refs.modalReport.show()
    },
    setWindowWidth() {
      this.windowWidth = window.innerWidth
    },
    setCategory(category) {
      const arrCat = []
      arrCat.push(category)
      this.form.categories = arrCat
    },
    setCity(cityId) {
      if (cityId) this.form.org_city_id = cityId
    },
    addbackground() {
      let res = "background: url('/images/form logo.png') no-repeat center;"
      let old = this.profile.company.organization.media.image_1

      if (old && old.length > 0) {
        res = "background: url(" + old + ");"
      }

      if (this.base64Img) {
        res = "background: url(" + this.base64Img + ");"
      }
      return res
    },
    transformFile(file) {
      const app = this
      if (file) {
        this.form.image_1 = file
        const reader = new FileReader()
        reader.readAsDataURL(file)
        reader.onload = function () {
          app.base64Img = reader.result
        }
        reader.onerror = function (error) {
          this.printErrorOnConsole(error)
        }
      }
    },
    updateFields() {
      this.form.inn = this.profile.company.organization.inn
      this.form.org_products = this.profile.company.organization.products

      this.form.phone = this.profile.company.contact_phone

      // this.form.image_1 = this.profile.company.organization.media.image_1;
      this.category = this.profile.company.categories[0]
      this.cityName = this.profile.company.city.name
      this.setCategory(this.profile.company.categories[0].id)
    },
    setFields(result) {
      for (let key in result) {
        if (result[key]) {
          switch (key) {
            case "image_1":
              this.formData.set("image_1", result[key])
              console.log(result[key])
              break
            case "org_products":
              this.formData.set("org_products", result[key])
              break
            case "org_city_id":
              this.formData.set("org_city_id", this.form[key])
              break
            case "categories":
              this.formData.set("categories[]", result[key])
              break
            case "phone":
              this.formData.set("phone", this.clearPhone(result[key]))
              break
            default:
              break
          }
        }
      }
      console.log(this.formData)
      this.companySave()
    },
    validateBeforeSubmit() {
      this.$validator.validateAll().then((result) => {
        if (result) {
          this.err = {}
          // вызвать сборку
          this.setFields(this.form)
        }
      })
    },
    clearPhone(phone) {
      let res = phone.slice(3)
      return res.replace(/[\(\)\ \-]/g, "")
    },
    companySave() {
      let path = "/api/v1/organization/manage/update"
      console.log(this.formData)
      axios
        .post(path, this.formData, {
          headers: { "Content-Type": "multipart/form-data" }
        })
        .then((resp) => {
          this.loading = false
          if (resp.data.error) {
            this.err = resp.data.error
          } else {
            // this.$emit("profileUpdateEmit");
            this.$root.reLogin()
            this.$store.dispatch("setSnackbar", {
              color: "success",
              text: "Компания обновлена",
              toggle: true
            })
            this.$router.push({ name: "lk.company" })
          }
        })
        .catch((error) => {
          this.printErrorOnConsole(error)
          this.loading = false
          this.err = error.response.data.errors
        })
    }
  }
}
</script>

<style>
.company__logo {
  background: url("/images/form logo.png");
  background-repeat: no-repeat;
}
</style>

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
      border: 1px solid $danger !important;
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
          }
        }
      }
      &--city {
        /deep/ .form-control.typeahead {
          border-radius: $border-radius-standart !important;
          box-shadow: $box-shadow-standart !important;
          border: 1px solid;
          padding-left: 12px;
          border-color: $color-grey;
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
    }
  }
}
</style>
<style>
.labelparagraph {
  font-family: Montserrat;
  font-style: normal;
  font-weight: 500;
  font-size: 12px;
  line-height: 15px;
  color: #6e6e6e;
  transition: 0.2s;
  margin-bottom: 5px;
}
.typeahead-wrap {
  margin-bottom: 20px;
  height: 40px;
}
.input-group > .form-control {
  background: rgba(47, 192, 110, 0.1);
  height: 40px !important;
  border: 0px !important;
}
.v-input__slot {
  background: rgba(47, 192, 110, 0.1) !important;
  height: 40px !important;
  border: 0px !important;
}
.v-input {
  background: rgba(47, 192, 110, 0.1) !important;
  margin-bottom: 20px;
  border-radius: 5px;
  color: black;
}
.v-text-field .v-input__slot .v-select__selection {
  color: black !important;
}
</style>
