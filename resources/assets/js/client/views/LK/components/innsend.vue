<template lang="pug">
section.inn-send--section
    .inn-send--header
        h3.inn-send--title Сообщить #[br] о проблеме    
        p.inn-send--subtitle Введите свой ИНН, #[br] менеджер свяжется с вами #[br] в ближайшее время
    form.inn-send--body(@submit.prevent="validateForm")
        .form-wrapper.form-wrapper--inn
            .input-wrapper(:class="{'errors' : errors.has('org_inn')}")
                input(
                    v-model.trim="inn"
                    placeholder="Введите ИНН"
                    v-validate="`required|inn`"
                    data-vv-as='ИНН'
                    data-vv-name='org_inn'
                )
                i.errors-list(v-if="errors.has('org_inn')") {{errors.first('org_inn')}}
        .form-wrapper.form-wrapper--send
            button(class="link" type='submit' :disabled="loading")
                span Отправить
</template>
<script>
import { Validator } from "vee-validate";
export default {
    data(){
        return {
            err: {},
            loading: false,
            inn: null
        }
    },
    created(){
        Validator.extend('inn',{
            getMessage: field => "ИНН может содержать 10 или 12 цифр",
            validate: value => {
                return (!(/[^0-9]/.test(value)) && [10, 12].indexOf(value.length) !== -1)
            } 
        })
    },
    methods:{
        validateForm(){
            this.$validator.validateAll().then(result => {
                if (result) {
                    this.err = {};
                    this.sendData()
                }
            });
        },
        sendData(){
            this.loading = true;
            const inn = this.inn;
            axios.post(`/api/v1/send/support/innfailed`,{inn:inn}).then((resp) => {
                // console.log(resp)
                this.$emit('close');
                this.$store.dispatch("setSnackbar", {
                    color: "success",
                    text: "Спасибо за обращение.",
                    toggle: true
                });
            }).catch((err) => {
                this.$emit('close');
                this.$store.dispatch("setSnackbar", {
                    color: "error",
                    text: "Произошла ошибка, попробуйте позднее.",
                    toggle: true
                });                
                this.printErrorOnConsole(err);
                this.err = err.response.data.errors;
            }).then(()=>{
                
                this.loading = false;
            });
        }
    }
}
</script>
<style lang="scss" scoped>
@import '~styleguide';

.inn-send{
    &--section{
        margin: 50px 0;
    }
    &--header{
        margin-bottom: 50px;
    }
    &--title{
        font: $font-medium;
        font-size: $fontsize-desk-header;
        color: $color-base-dark;
        margin-bottom: 16px;
    }
    &--subtitle{
        font: $font-regular;
        font-size: $fontsize-base;
        color: $color-text-dark;
    }
    &--body{
        .form-wrapper{
            display: flex;
            justify-content: center;
            &--inn{
                margin-bottom: 80px;
                .input-wrapper{
                    width: 280px;
                    input{
                        width:100%;
                        height: 49px;
                        border-radius: $border-radius-small;
                        box-shadow: $box-shadow-standart;
                        background-color: $color-base-gray-light;
                        font: $font-regular;
                        font-size: $fontsize-base;
                        color: $color-text-gray;
                        padding:0 20px;
                        border-width: 1px;
                        border-style: solid;
                        border-color: $color-grey;
                            &:active,
                            &:focus {
                            box-shadow: none;
                            border-color: $color-green-elements;
                            }
                    }
                    &.errors{
                        .errors-list{
                        margin: 5px 0;
                        padding: 0px 10px;
                        font: $font-regular;
                        font-size: 10px;
                        color: $danger;
                        display: block;
                        }
                        input{
                        border: 1px solid $danger;
                        } 
                    }
                }
            }
            &--send{
                button{
                outline: none;
                width: 228px;
                height: 44px;
                border-radius: $border-radius-big;
                background-color: $color-green-elements;
                cursor: pointer;
                    &[disabled]{
                        opacity: 0.5;
                        cursor: none;
                    }
                    span{
                        font: $font-semibold;
                        font-size: $fontsize-base;
                        color: #fff;
                    }
                }
            }
        }
    }
}
</style>