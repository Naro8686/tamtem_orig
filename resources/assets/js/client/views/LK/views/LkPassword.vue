<template lang="pug">
.password-change
  h3.password-change--title Редактирование профиля
  .card
    .card-body
      form.password-change--form(
        @submit.prevent='changePassword'
      )
        .form-group
          v-text-field(
            v-model="user.password_old"
            :light='true'
            solo
            placeholder="Старый пароль"
            :type="show_password ? 'text' : 'password'"
            :append-icon="show_password ? 'visibility_off' : 'visibility'"
            @click:append="show_password = !show_password"
            :error-messages=`err.password_old ? err.password_old : ''`
          )
        .form-group
          v-text-field(
            v-model="user.password"
            :error-messages=`err.password ? err.password : ''`
            :light='true'
            solo
            placeholder="Новый пароль"
            :type="show_password1 ? 'text' : 'password'"
            :append-icon="show_password1 ? 'visibility_off' : 'visibility'"
            @click:append="show_password1 = !show_password1"
          )
        .form-group
          v-text-field(
            v-model="user.password_confirmation"
            :error-messages=`err.password_confirmation ? err.password_confirmation : ''`
            :light='true'
            solo
            placeholder="Подтвердите новый пароль"
            :type="show_password2 ? 'text' : 'password'"
            :append-icon="show_password2 ? 'visibility_off' : 'visibility'"
            @click:append="show_password2 = !show_password2"
          )
        .form-group--save-button
          button( class="link" type='submit' :disabled='loading')
            span Сохранить
            b-spinner(v-if='loading' label='Загрузка...')

</template>

<script>
export default {
  name: "LkPassword",
  data() {
    return {
      loading: false,
      show_password: false,
      show_password1: false,
      show_password2: false,
      user: {
        password_old: "",
        password: "",
        password_confirmation: ""
      },
      err: {}
    };
  },
  methods: {
    changePassword() {
      this.loading = true;
      this.err = [];
      axios
        .post("/api/v1/user/password/change", this.user)
        .then(resp => {
          this.$router.push({ name: "lk.profile.edit" });
          this.$store.dispatch("setSnackbar", {
            color: "success",
            text: "Пароль успешно обновлён",
            toggle: true
          });
        })
        .catch(err => {
          this.err = err.response.data.errors || err.response.data.error.errors;
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

.password-change {
  &--title {
    text-align: left;
    font: $font-medium;
    font-size: 22px;
    color: $color-base-dark;
    line-height: 32px;
    margin-bottom: 10px;
  }
  &--form {
    padding-top: 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    .form-group {
      max-width: 290px;
      width: 100%;
      margin: 16px 0;
      /deep/ .v-input__slot {
        background-color: transparent !important;

        border-radius: $border-radius-standart !important;
        box-shadow: $box-shadow-standart !important;
        height: 41px;
        .v-text-field__prefix {
          font: $font-regular;
          font-size: $fontsize-base;
          color: $color-text-gray;
          padding-right: 4px;
        }
        input {
          color: $color-text-gray;
          font: $font-regular;
          font-size: $fontsize-base;
        }
        .v-icon {
          color: $gray;
        }
      }
      &--save-button {
        margin: 30px 0;
        padding: 7px 32px;
        border-radius: 22px;
        background-color: $color-green-elements;
        .link {
          outline: none;
          text-decoration: none !important;
          font: $font-semibold;
          font-size: $fontsize-base;
          line-height: 19px;
          color: #fff;
          span {
            margin-right: 5px;
          }
          &:hover,
          &:focus,
          &:active {
            outline: none;
          }
        }
      }
    }
  }
}
</style>