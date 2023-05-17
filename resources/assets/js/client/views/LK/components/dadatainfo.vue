<template lang="pug" functional>
section.organization-data--wrapper
    dl.organization-data--dl
        dt.organization-data--dt ОГРН
        dd.organization-data--dd {{props.orgData.org_ogrn}}            
        template(v-if="props.orgData.org_director")
          dt.organization-data--dt ФИО руководителя
          dd.organization-data--dd {{props.orgData.org_director}}            
        template(v-if="props.orgData.org_manager_post")
          dt.organization-data--dt Должность руководителя
          dd.organization-data--dd {{props.orgData.org_manager_post}}            
        dt.organization-data--dt Название организации/ЮР лица
        dd.organization-data--dd(v-html="props.orgData.org_name")            
        dt.organization-data--dt Юридический адрес
        dd.organization-data--dd {{props.orgData.org_address_legal}}
        dt.organization-data--dt ОКВЭД
        dd.organization-data--dd {{props.orgData.org_okved}} - основной код ОКВЭД
        dt.organization-data--dt Состояние компании
        dd.organization-data--dd(:class="{ 'dd--green' : props.orgData.org_status.id==1, 'dd--red' : props.orgData.org_status.id!=1 }") {{props.orgData.org_status.text}}
        template(v-if="props.orgData.org_kpp")
          dt.organization-data--dt КПП
          dd.organization-data--dd {{props.orgData.org_kpp}}
        dt.organization-data--dt Дата регистрации
        dd.organization-data--dd {{$options.datetransform(props.orgData.org_registration_date)}}
        dt.organization-data--dt Город
        dd.organization-data--dd {{props.orgData.org_address}}
</template>
<script>
export default {
    props:{
        orgData:{
            type: Object
        }
    },
    datetransform(date){
      return date.split("-").reverse().join(".")
    }
}
</script>
<style lang="scss" scoped>
@import '~styleguide';

.organization-data{
  &--wrapper{
    align-self: flex-end;
    border-radius: $border-radius-standart;
    box-shadow: $box-shadow-standart;
    padding: 13px 14px;
    width: 246px;
  }
  &--dl{
    margin-bottom: 0;
  }
  &--dt{
    color: $color-text-bright;
    font: $font-regular;
    font-size: 10px; 
    margin-bottom: 3px;
  }
  &--dd{
    &.dd--green{
      color: $color-green-elements;
    }
    &.dd--red{
      color: $danger;
    }
    color: #000;
    font: $font-regular;
    font-size: 10px; 
    margin-bottom: 10px;
    &:last-child{
      margin-bottom: 0;
    }
  }
}
</style>