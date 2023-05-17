<template lang="pug">
.card
	.card-body
		.company(
			v-if='profile && Object.keys(profile.company).length'
		)
			.company.company--header
				.item.item--wrapper
					.item.item--ava
						img(
								v-if='profile.company.organization.media.image_1'
								:src='profile.company.organization.media.image_1' 
								alt=''
							)
						nophoto(v-else)
					.item.item--data(v-if="isAuth")
						.organization
							h4.organization.organization--title {{profile.company.organization.name}}
							dl.organization.organization--info
								dt {{phoneFormat(profile.company.contact_phone)}}
								dt ИНН {{profile.company.organization.inn}}
				.item.item--data.extras(v-if="isAuth")
					.categories
						a(href="javascript:void(0);").category {{profile.company.categories[0].name}}
					.settings
						router-link(
							v-if='isMy'
							:to="{ name: 'lk.company.edit', params: {} }" 
							class="settings--link"
						)
							feather(type='settings')
			.company.company--body
				.content(v-if="isAuth")
					dl.company-info--item(v-if="profile.company.organization.name")
						dt Название организации/ЮР лица
						dd 
							span(v-html="profile.company.organization.name")
					dl.company-info--item(v-if="profile.company.organization.address_legal")
						dt Юридический адрес
						dd {{profile.company.organization.address_legal}}
					dl.company-info--item(v-if="profile.company.organization.inn")
						dt ИНН
						dd {{profile.company.organization.inn}}
					dl.company-info--item(v-if="profile.company.organization.org_ogrn")
						dt ОГРН
						dd {{profile.company.organization.org_ogrn}}
					dl.company-info--item(v-if="profile.company.organization.kpp")
						dt КПП
						dd {{profile.company.organization.kpp}}
					dl.company-info--item(v-if="profile.company.organization.registration_date")
						dt Дата регистрации
						dd {{profile.company.organization.org_registration_date|convertDate}}
					dl.company-info--item(v-if="profile.company.organization.address_legal")
						dt Город
						dd {{profile.company.organization.address}}
					dl.company-info--item(v-if="profile.company.organization.org_is_active")
						dt Состояние компании
						dd(:class="{'active' : profile.company.organization.org_is_active }") {{profile.company.organization.org_is_active|setStatus}}
					dl.company-info--item(v-if="profile.company.organization.director")
						dt ФИО руководителя
						dd {{profile.company.organization.director}}
					dl.company-info--item(v-if="profile.company.organization.org_manager_post")
						dt Должность руководителя
						dd {{profile.company.organization.org_manager_post}}
					dl.company-info--item(v-if="profile.company.organization.org_okved")
						dt ОКВЭД
						dd {{profile.company.organization.org_okved}} - Основной код ОКВЭД
				.content(v-else)
					dt Чтобы увидеть данные компании, необходимо войти
				.footage(v-if="profile.company.city.name")
					dl.company-info--item
						dt Город отгрузки товара
						dd {{profile.company.city.name}}
			.company.company--description(v-if="profile.company.organization.products")
				dl.company-info--item
						dt Описание компании
						dd {{profile.company.organization.products}}
		p(v-else) Компания не создана

</template>

<script>
import nophoto from "../../GeneralComponents/components/nophoto";
import { mapGetters, mapState } from "vuex";
export default {
  props: {
    profile: Object
  },
  components: {
    nophoto
  },
  data() {
    return {};
  },
  filters: {
    convertDate(dateObj) {
      if (dateObj) {
        let buff = dateObj.date.split(" ")[0];
        let [year, month, day] = buff.split("-");
        return `${day}.${month}.${year}`;
      } else {
        return "";
      }
    },
    setStatus(id) {
      return id === 1 ? "Действующая" : "Нет данных";
    }
  },
  computed: {
    ...mapState({
      user: (state) => {return state.profile}
    }),
    isMy() {
      return this.$root.isAuth && this.profile.profile.id === this.user.profile.id;
    },
    isAuth() {
      return !!this.$root.profile;
    }
  },
  methods: {},
  mounted() {},
  watch: {
    profile: function(newVal, oldVal) {
      if (this.$root.profile) {
        if (newVal && newVal.profile.id === this.user.profile.id)
          // if current user
          this.$router.push({ name: "lk.company" });
        else if (newVal && newVal.profile.is_org_created === 0)
          // if company is not created
          this.$router.push({ name: "lk.profile" });
      }
    }
  }
};
</script>
<style lang="scss" scoped>
@import "~styleguide";

.company {
  &--header {
    padding-left: 20px;
    display: flex;
    margin-bottom: 35px;
    @media (max-width: 767px) {
      padding-left: 0;
      flex-direction: column;
    }
    .item {
      &--wrapper {
        display: flex;
        width: 100%;
        @media (max-width: 767px) {
          order: 1;
        }
      }
      &--ava {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 120px;
        margin-right: 22px;
        img {
          width: 120px;
          max-height: 120px;
          border-radius: $border-radius-standart;
        }
        /deep/ svg {
          width: 110px;
          height: 110px;
        }
      }
      &--data {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        width: 100%;
        @media (max-width: 767px) {
          align-items: center;
        }
        &.extras {
          justify-content: flex-end;
          @media (max-width: 767px) {
            align-items: center;
            margin-bottom: 5px;
            justify-content: flex-end;
            order: 0;
          }
        }
        .organization {
          width: 100%;
          max-width: 320px;
          &--title {
            @media (max-width: 767px) {
              display: none;
            }
            font: $font-semibold;
            font-size: 17px;
            color: $color-base-dark;
            margin-bottom: 42px;
          }
          &--info {
            dt {
              font: $font-regular;
              font-size: $fontsize-base;
              color: #000;
              margin-bottom: 8px;
            }
          }
        }
        .categories {
          width: 100%;
          max-width: 320px;
          padding: 0 15px;
          display: flex;
          justify-content: end;
          .category {
            border: 1px solid $color-green-elements;
            border-radius: 16px;
            padding: 9px 25px;
            font: $font-regular;
            font-size: $fontsize-base;
            color: #000;
            cursor: default;
            text-decoration: none;
          }
        }
        .settings {
          width: 25px;
          height: 25px;
          &--link {
            color: $color-green-elements;
          }
        }
      }
    }
  }
  &--body {
    padding-left: 20px;
    margin-top: 35px;
    @media (max-width: 767px) {
      padding-left: 0px;
    }
    .company-info--item {
      display: flex;
      @media (max-width: 767px) {
        margin-bottom: 0;
        padding-top: 5px;
        padding-bottom: 5px;
        flex-direction: column;
      }
      dt {
        font: $font-regular;
        font-size: $fontsize-base;
        color: $color-base-dark;
        margin-right: 11px;
        min-width: 242px;
        @media (max-width: 767px) {
          margin-right: 0px;
        }
      }
      dd {
        margin-left: 11px;
        font: $font-regular;
        font-size: $fontsize-base;
        color: #000;
        @media (max-width: 767px) {
          margin-left: 0px;
        }
        &.active {
          color: $color-green-elements;
        }
      }
    }
    .content {
      padding-top: 37px;
      padding-bottom: 37px;
      border-top: 1px solid $color-profile-border-line;
      border-bottom: 1px solid $color-profile-border-line;
      @media (max-width: 768px) {
        padding-top: 10px;
        padding-bottom: 10px;
      }
    }
    .footage {
      padding-top: 37px;
      padding-bottom: 37px;
    }
  }
  &--description {
    padding: 13px 22px 21px 22px;
    box-shadow: $box-shadow-standart;
    border-radius: $border-radius-standart;
    @media (max-width: 767px) {
      box-shadow: none;
      padding: 0;
    }
    .company-info--item {
      display: block;
      dt {
        font: $font-regular;
        font-size: $fontsize-base;
        color: $color-base-dark;
        margin-bottom: 6px;
      }
      dd {
        font: $font-regular;
        font-size: $fontsize-base;
        color: #000;
      }
    }
  }
}
</style>