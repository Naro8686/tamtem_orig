<template lang="pug">
article.contact.bid-body__contact(v-if="userdata.user")
    template(v-if="userdata.company")
        //-router-link(:to="{name: 'companies.detail', params: {id: userdata.user.id}}" class="contact__card-title") Подробнее о компании
        h3.contact__company-name {{userdata.company.name ? userdata.company.name : userdata.company.org_name}}
        div.contact__company-info
            img(v-if="userdata.company.image_1" :src="userdata.company.image_1")
            nophoto(v-else)
            ul
                li {{userdata.company.org_inn}}
                li {{userdata.company.org_city_name ? userdata.company.org_city_name : userdata.company.org_address}}
        p.contact__company-desc 
            | {{userdata.company.org_products}}
    div.contact__person
        //- img(:src="userdata.user.photo")
        ul
            li {{userdata.user.name}}
            li {{this.phoneFormat(userdata.user.phone)}}
            li {{userdata.user.email}}
            // пишем только владельцу
            //- новый положняк - вообще не пишем
    //- button.contact__btn(v-if="this.canCreateDialog" @click="messageWrite()") Написать
    hr.contact-d.contact-dt
    hr.contact-d.contact-db    
</template>
<script>
import nophoto from "../../GeneralComponents/components/nophoto";

export default {
  props: {
    userdata: {
      type: Object
    },
    canCreateDialog: {
      type: Boolean,
      default: false
    }
  },
  components: {
    nophoto
  },
  data() {
    return {};
  },
  methods: {
    messageWrite() {
      axios
        .post("/api/v1/dialogs/new", { deal_id: this.$route.params.id })
        .then(resp => {
          if (resp.data.result) {
            this.$router.push({
              name: "lk.dialogs.private",
              params: { id: resp.data.data.id }
            });
          }
        })
        .catch(error => {
          this.printErrorOnConsole(error);
        });
    }
  }
};
</script>
<style lang="scss" scoped>
@import "~styleguide";

.contact {
  padding: 10px 7px;
  background: #fafafa;
  @media (max-width: 768px) {
    grid-template-columns: repeat(5, 1fr);
    grid-row-gap: 10px;
    grid-template-areas:
      "name name . . cap"
      "dt dt dt dt dt"
      "info info . more more"
      "db db db db db"
      "desc desc desc desc ."
      "person person person . btn";
  }
  @media (max-width: 420px) {
    grid-template-columns: repeat(6, 1fr);
    grid-row-gap: 15px;
    grid-template-areas:
      "name name name cap cap cap"
      "dt dt dt dt dt dt"
      "info info info info info info "
      "db db db db db db"
      "desc desc desc desc desc desc"
      "person person person person person person"
      "btn btn btn btn btn btn"
      "more more more more more more";
  }
  &-d {
    // это разделители для мобильных разрешений
    background-color: $color-base-gray-light;
    display: none;
    margin: 0;
    @media (max-width: 768px) {
      display: block;
    }
  }
  &-dt {
    grid-area: dt;
  }
  &-db {
    grid-area: db;
  }
  &__card-title {
    align-self: flex-end;
    font-size: 10px;
    color: $black;
    margin-bottom: 15px;
    @media (max-width: 768px) {
      grid-area: cap;
      justify-self: end;
      align-self: center;
    }
    @media (max-width: 420px) {
      margin: 0;
    }
  }
  &__company-name {
    color: #000;
    font-size: 17px;
    font-weight: 600;
    line-height: 24px;
    align-self: flex-start;
    margin-top: 20px;
    margin-bottom: 15px;
    @media (max-width: 768px) {
      grid-area: name;
      margin: 0;
    }
    @media (max-width: 420px) {
      font-size: 16px;
    }
  }
  &__company-info {
    width: 100%;
    display: flex;
    align-items: center;
    padding: 10px 0;
    border-top: 1px solid $color-border-gray;
    border-bottom: 1px solid $color-border-gray;
    img {
      width: 40px;
      // height: 26px;
    }
    ul {
      list-style: none;
      margin-left: 15px;
      li {
        font-size: 11px;
        &:not(:last-child) {
          margin-bottom: 3px;
        }
      }
    }
    @media (max-width: 768px) {
      grid-area: info;
      border: none;
      padding: 0;
    }
  }
  &__company-desc {
    margin-top: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid $color-border-gray;
    @media (max-width: 768px) {
      grid-area: desc;
      margin: 0;
      margin-bottom: 20px;
    }
    @media (max-width: 420px) {
      padding-right: 20px;
      font-size: 12px;
    }
  }
  &__person {
    margin-top: 15px;
    display: flex;
    align-items: center;
    // img {
    //   width: 64px;
    //   height: 64px;
    //   border-radius: 10px;
    // }
    ul {
      list-style: none;
      // margin-left: 15px;
      li {
        font-size: 14px;
        &:not(:last-child) {
          margin-bottom: 3px;
        }
      }
    }
    @media (max-width: 768px) {
      grid-area: person;
      margin: 0;
      font-size: 12px;
    }
    @media (max-width: 420px) {
      justify-self: center;
    }
  }
  &__btn {
    background-color: $color-base-accent;
    color: $color-base-light;
    padding: 10px 35px;
    border-radius: 32px;
    font-size: 14px;
    // min-width: 164px;
    margin: 20px 0;
    @media (max-width: 768px) {
      grid-area: btn;
      margin: 0;
    }
    @media (max-width: 420px) {
      justify-self: center;
    }
  }
  &__more {
    &-icon {
      margin-right: 10px;
    }
    @media (max-width: 768px) {
      grid-area: more;
      font-size: 11px;
      justify-self: end;
    }
    @media (max-width: 420px) {
      justify-self: center;
      margin-top: 15px;
    }
  }
}
</style>