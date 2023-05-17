<template lang="pug">
.content
  .company
    form.profile__form(@submit.prevent="validateBeforeSubmit")
      .company__top-block
        .company__left-block
          CustomInput(dataId="companyName", dataTitle="Название Компании", dataName="companyName")
          CitiesSelect(@setCityEmit="setCity", :cityName="cityName", :err="err", :placeholder="'Город отгрузки товара'")
          CustomInput(dataId="companyINN", dataTitle="ИНН", dataName="companyINN")
          CustomInput(dataId="companyKPP", dataTitle="КПП", dataName="companyKPP")
          CustomInput(dataId="companyDirector", dataTitle="ФИО руководителя", dataName="companyDirector")
          CategorySelect(@setCategoryEmit="setCategory", :category="category", :err="err")
        .company__right-block
          .company__logo-block
            img#company__logo.company__logo(src="/images/form logo.png", alt="logo company")
          .company__logo-change
            input#logo-img.company__logo-change-input(type="file", name="logo-img", accept="image/*")
            label(for="logo-img") ИЗМЕНИТЬ ЛОГОТИП
      .company__bottom-block
        CustomInput(dataId="companyDescr", dataTitle="Описание Компании, которое будет появляться в заголовках и поиске", dataName="companyDescr", dataType="textarea")
        .custom-btn-green
          button.custom-btn-green__btn.profile__btn(type="submit", :disabled="loading")
            | Сохранить
</template>

<script>
import CustomInput from "../../GeneralComponents/components/CustomInput"
import CategorySelect from "../../GeneralComponents/components/CategorySelect"
import CitiesSelect from "../../GeneralComponents/components/CitiesSelect"

export default {
  components: {
    CustomInput,
    CategorySelect,
    CitiesSelect
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
  }
}
</script>
