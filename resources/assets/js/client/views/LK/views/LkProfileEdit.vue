<template lang="pug">
.profile-edit
  h3.profile-edit--title Редактирование профиля
  .card
    .card-body
      form.profile-edit--form.form(
        @submit.prevent='validateBeforeSubmit'
        v-if='profile'
      )       
        .form-group
          div.input-wrapper(:class="{'errors' : (errors.has('name'))}")
            input(
              v-model="form.name"
              placeholder='Имя'
              data-vv-as='Имя'
              data-vv-name='name'
              v-validate=`'required'`
            ).input-wrapper__input
            i.errors-list {{...errors.collect('name')}}
        .form-group
          div.input-wrapper(:class="{'errors' : (errors.has('phone'))}")
            input(
              v-model="form.phone"
              placeholder='7 (___) ___ __ __'
              data-vv-as='Телефон'
              data-vv-name='phone'
              v-validate=`'required|mobilePhone'`
            ).input-wrapper__input
            i.errors-list {{...errors.collect('phone')}}
        .form-group--change-pass
          router-link(:to="{ name: 'lk.profile.password', params: {} }" class='link') Изменить пароль
        .form-group--image-wrapper
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
            p Добавить фото	
            span.error-text {{ errors.first('photo') }}
        .form-group--save-button
          button( class="link" type='submit' :disabled='loading')
            span Сохранить
</template>

<script>
import { Validator } from "vee-validate";

export default {
  // props: ["err"],
  data() {
    return {
      loading: false,
      err: {},
      form: {
        name: null,
        phone: null,
        photo: null
      },
      base64Img: null,
      formData: new FormData()
    };
  },
  props: {
    profile: Object
  },
  created(){
    Validator.extend('mobilePhone',{
			field: (field) => `Поле ${field} имеет ошибочный формат`,
			validate: (value) => {
				return value.replace(/[^0-9]/gi, "").replace(/^[78]/, "").length==10}
		});
  },
  methods: {
    addbackground() {
      var res = "background: url('/images/no-photo.svg');background-size:100%;";
      var old = "";
      old = this.profile.profile.photo;
      if (old && old.length > 0) {
        res = "background: url(" + old + "); background-size:100%;";
      } else {
      }
      if (this.base64Img) {
        res =
          "background: rgba(0,0,0,0) url(" +
          this.base64Img +
          ") repeat scroll 0% 0% / 100% auto;";
      }
      return res;
    },
    transformFile(file) {
      const app = this;
      if (file) {
        this.form.photo = file;
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

          for (let key in this.form) {
            if (this.form[key]) this.formData.set(key, this.form[key]);
          }

          this.profileUpdate();
        }
      });
    },
    profileUpdate() {
      this.loading = true;
      axios
        .post("/api/v1/user/update", this.formData, {
          headers: { "Content-Type": "multipart/form-data" }
        })
        .then(resp => {
          this.loading = false;
          if (resp.data.error) {
            this.err = resp.data.error;
          } else {
            this.$store.dispatch("setProfile", { profile: resp.data.data });
            this.$emit("profileUpdateEmit");
            this.$router.push({ name: "lk.profile" });
            this.$store.dispatch("setSnackbar", {
              color: "success",
              text: "Данные успешно обновлены",
              toggle: true
            });
          }
        })
        .catch(error => {
          this.printErrorOnConsole(error);
          this.loading = false;
          this.err = error.response.data.errors;
        });
    },
    deleteImg() {
      axios
        .post("/api/v1/user/deletephoto", { path: this.profile.profile.photo })
        .then(resp => {
          if (resp.data.result) {
          }
        })
        .catch(error => {
          this.printErrorOnConsole(error);
          this.err = error.response.data.errors;
        });
    },
    updateFields() {
      if (this.profile) {
        this.form.name = this.profile.profile.name;
        this.form.phone = this.profile.profile.phone;
      }
    }
  },
  mounted() {
    if (this.profile) {
      this.updateFields();
    }

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
  watch: {
    profile: function(newVal, oldVal) {
      this.updateFields();
    },
    'form.phone'(newVal){
			this.form.phone = newVal.replace(/[^0-9]/gi, "").slice(0,11)
		}
  }
};
</script>
<style lang="scss" scoped>
@import "~styleguide";
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
.profile-edit {
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
      }
      &--change-pass {
        margin-bottom: 30px;
        .link {
          cursor: pointer;
          text-decoration: none;
          color: $color-base-dark;
          font: $font-regular;
          font-size: $fontsize-base;
          line-height: 19px;
        }
      }
      &--image-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        .fakeimg {
          width: 120px;
          height: 120px;
          overflow: hidden;
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