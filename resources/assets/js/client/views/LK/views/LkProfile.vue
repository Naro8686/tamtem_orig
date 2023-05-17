<template lang="pug">
section.lk-items
	.card.lk-profile
		.card-body
			.profile(v-if='profile')
				.profile-row.profile-row--settings
					router-link(
						v-if='isMy'
						:to="{ name: 'lk.profile.edit', params: {} }" 
						class="row-settings--link"
					)
						feather(type='settings')
				.profile-row.profile-row--userdata
					.row-item.row-item--ava
							img(
								v-if='profile.profile.photo'
								:src='profile.profile.photo' 
								alt=' '
							)
							nophoto(v-else)
					.row-item.row-item--data
						.profiledata
							.profiledata--companyname(v-if="profile.company && profile.company.organization")
								|{{profile.company.organization.name}}
							.profiledata--username {{profile.profile.name}}
						.companydata(v-if="isMy")
							.companydata--info
								router-link(
									v-if='profile && !profile.profile.is_org_created && isMy'
									:to="{ name: 'lk.company.create', params: {} }" 
									class='companydata--info--link'
									) Создать компанию
								router-link(
									v-if='profile && profile.profile.is_org_created'
									:to="{name: 'companies.detail', params: {id: profile.profile.id}}" 
									class='companydata--info--link'
								) Информация о компании	
							dl.companydata--id
								dt ваш id
								dd {{profile.profile.unique_id}}
				.profile-row.profile-row--contacts(v-if='this.$root.profile')
					.row-item.row-item--title Контакты
					.row-item.row-item--items
						dl
							dt Телефон
							dd {{phoneFormat(profile.profile.phone)}}
						dl
							dt Email
							dd {{profile.profile.email}}
				.profile-row.profile-row--contacts(v-else)
					p Чтобы увидеть контакты, необходимо войти
				.profile-row.profile-row--company-link
					.link--wrapper
								router-link(
									v-if='profile && !profile.profile.is_org_created && isMy'
									:to="{ name: 'lk.company.create', params: {} }" 
									class='link'
									) Создать компанию
								router-link(
									v-if='profile && profile.profile.is_org_created'
									:to="{name: 'companies.detail', params: {id: profile.profile.id}}" 
									class='link'
								) Информация о компании
	.card.lk-notifications(v-if="profile && profile.profile.notice_allowed==false")
		.lk-notifications__item.lk-notifications__item
				p.lk-notifications__title Настройка уведомлений             
				div.lk-notifications__text--wrapper
					p.lk-notifications__text--text На данный момент вы не подписаны на уведомления по электронной почте.
					p.lk-notifications__text--text Хочу получать персональные уведомления о новых заказах по эл. почте.
				button.lk-notifications__button.lk-notifications__button--subscribe(@click="changeSubscribe(1)" :disabled="changingSubscribeStatus") Подписаться 
	.card.lk-notifications(v-else) 	
		.lk-notifications__item.lk-notifications__item--active              
			p.lk-notifications__title Настройка уведомлений             
			button.lk-notifications__button.lk-notifications__button--unsubscribe(@click="changeSubscribe(0)" :disabled="changingSubscribeStatus") Отписаться от всех
			div.lk-notifications__text
				p.lk-notifications__text--text Способ получения 
					br.no-mobile
					| уведомлений 
			p.lk-notifications__channel Электронная почта	
</template>

<script>
import nophoto from "../../GeneralComponents/components/nophoto";
import { mapState, mapActions } from 'vuex';
export default {
  components: {
    nophoto
  },
  props: {
    profile: Object
  },
  data() {
    return {
      changingSubscribeStatus: false
    };
  },
  computed: {
    isMy() {
      return (
        this.profile &&
        this.$root.profile &&
        this.profile.profile.id === this.$root.profile.profile.id
      );
	},
	...mapState({
		getProfile: 'profile'
	})
  },
  methods: {
	  ...mapActions(['setProfile']),
    changeSubscribe(newValue) {
      const notice_allowed = newValue;
      this.changingSubscribeStatus = false;
      axios
        .post("/api/v1/user/subscribe", { notice_allowed: notice_allowed })
        .then(response => {
          if (response.data.result == true) {
			// reload profile
			const items = Object.assign({},this.getProfile);
			items.profile.notice_allowed = Boolean(newValue);
			this.setProfile(items);

          }else{
			 this.$store.dispatch("setSnackbar", {
            color: "error",
            text: `Произошла ошибка, попробуйте позднее`,
            toggle: true
          }); 
		  }
        })
        .catch(err => {

          this.$store.dispatch("setSnackbar", {
            color: "error",
            text: `Произошла ошибка, попробуйте позднее`,
            toggle: true
          });
        })
        .then(() => {
          this.changingSubscribeStatus = false;
        });
    }
  },
  mounted() {}
};
</script>
<style lang="scss" scoped>
@import "~styleguide";
@import "~mixins";

.no-mobile {
  @media (max-width: 768px) {
    display: none;
  }
}
.lk-notifications {
  margin-top: 74px;
  padding: 26px 33px 50px 23px;
  @media (max-width: 768px) {
    padding: 15px;
  }

  &__item {
    display: grid;
    grid-template-areas: "title title" "text buttonSubscribe";
    gap: 40px;
    @media (max-width: 768px) {
      grid-template-areas: "title" "text" "buttonSubscribe";
    }
    &--active {
      @media (max-width: 768px) {
        grid-template-areas: "title" "buttonUnsubscribe" "text" "channel";
      }
      grid-template-areas: "title title buttonUnsubscribe" "text channel .";
    }
  }
  &__title {
    grid-area: title;
    font: $font-medium;
    font-size: 18px;
    color: #000;
  }
  &__text {
    grid-area: text;
    &--text {
      font: $font-regular;
      font-size: $fontsize-base;
      color: #000;
    }
  }
  &__button {
    &--subscribe {
      grid-area: buttonSubscribe;
      @include big-rounded-btn;
      padding: 0 22px;
      &:hover {
        background-color: $color-hover-button;
      }
      &[disabled] {
        opacity: 0.5;
      }
      &:active {
        background-color: $color-border-gray;
        color: #000;
      }
    }
    &--unsubscribe {
      grid-area: buttonUnsubscribe;
      justify-self: end;
      font: $font-regular;
      font-size: $fontsize-smaller;
      color: #000;
      border-bottom: 1px dashed #000;
    }
  }
  &__channel {
    grid-area: channel;
    font: $font-regular;
    font-size: $fontsize-base;
    color: #000;
    padding: 13px 14px 12px 14px;
    border-radius: $border-radius-standart;
    background-color: $color-pale-green;
  }
}

.profile {
  @include media-breakpoint-down(xs) {
    display: flex;
    flex-direction: column;
  }
  &-row {
    display: flex;
    &--settings {
      justify-content: flex-end;
      .feather {
        color: $color-green-elements;
      }
    }
    &--userdata {
      .row-item {
        &--ava {
          display: flex;
          align-items: center;
          justify-content: center;
          width: 120px;
          img {
            width: 120px;
            height: 120px;
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
          align-items: center;
          margin-left: 54px;
          width: 100%;
          border-bottom: 1px solid;
          border-color: $color-profile-border-line;
          .profiledata {
            &--companyname {
              font: $font-semibold;
              font-size: 15px;
              color: #000;
              line-height: 19px;
            }
            &--username {
              font: $font-regular;
              font-size: $fontsize-base;
              color: #000;
            }
          }
          .companydata {
            &--info {
              padding: 13px 26px;
              border-radius: 32px;
              background-color: $color-green-elements;
              &--link {
                text-decoration: none;
                font: $font-semibold;
                font-size: $fontsize-base;
                line-height: 19px;
                color: #fff;
              }
            }
            &--id {
              display: flex;
              justify-content: flex-end;
              margin-top: 16px;
              dt,
              dd {
                font: $font-regular;
                font-size: $fontsize-base;
                color: $color-text-gray;
              }
              dd {
                margin-left: 5px;
              }
            }
          }
        }
      }
      @include media-breakpoint-down(xs) {
        display: block;
        margin: 0 auto;
        text-align: center;
        .row-item {
          &--ava {
            width: 100%;
          }
          &--data {
            margin: 0;
            flex-direction: column;
            border: none;
            .profiledata {
              order: 1;
            }
            .companydata {
              order: 0;
              &--info {
                display: none;
              }
              &--id {
                margin: 5px 0px 15px 0px;
              }
            }
          }
        }
      }
    }
    &--contacts {
      margin-top: 16px;
      .row-item {
        &--title {
          width: 120px;
          display: flex;
          justify-content: center;
          align-items: center;
          border-right: 1px solid;
          border-color: $color-profile-border-line;
          line-height: 19px;
          font: $font-regular;
          font-weight: 400;
          font-size: $fontsize-base;
          color: #000;
        }
        &--items {
          margin-left: 54px;
          margin-top: 15px;
          dl {
            margin-top: 15px;
            margin-bottom: 15px;
            dt,
            dd {
              line-height: 19px;
              font: $font-regular;
              font-weight: 400;
              font-size: $fontsize-base;
              color: #000;
            }
          }
        }
      }
      @include media-breakpoint-down(xs) {
        display: block;
        text-align: center;
        margin: 0 auto;
        .row-item {
          &--title {
            display: none;
          }
          &--items {
            margin: 15px 0 0 0;
            text-align: left;
          }
        }
      }
    }
    &--company-link {
      display: none;
      @include media-breakpoint-down(xs) {
        display: flex;
        justify-content: center;
        align-content: center;
        .link--wrapper {
          padding: 13px 26px;
          border-radius: 32px;
          background-color: $color-green-elements;
          .link {
            text-decoration: none;
            font: $font-semibold;
            font-size: $fontsize-base;
            line-height: 19px;
            color: #fff;
          }
        }
      }
    }
  }
}
</style>