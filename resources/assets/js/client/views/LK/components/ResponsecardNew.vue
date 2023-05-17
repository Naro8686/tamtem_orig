<template lang="pug">
article(:class="[cardStatus, {'responsecard--folded' : folded}, {'responsecard--payed-unfold' : isOpenPayed}, {'active' : isOpenBody}, {'new' : !response.isReadedByMe}]", @click="openResponse()")
  b-modal.modal-wrap(:id="`modalResponseReport${response.responseId}`", :ref="`modalResponseReport${response.responseId}`", modal-class="modal-response", body-class="response-modal-body")
  .response_title(@click="openBody")
    .response_first_block
      p.response_first_block_title Заказ: {{response.dealName}}
      .response_first_block_info
        BudgetNew(type="buy", :from="response.budget_from", :to="response.budget_to")
        p(v-if="response.budget_with_nds==1") с учетом НДС
        p Период закупки: {{response.dealPeriod}}
    .response_second_block
      .response_second_block_left
        .response_info
          p
            span.response_span_type Отклик:
            span.response_span_date 20.06.2020
          p.price_p
            span.response_span_price {{response.priceOffer}} ₽
            span
              svg(width="22", height="16", viewBox="0 0 22 16", fill="none", xmlns="http://www.w3.org/2000/svg")
                path(
                  fill-rule="evenodd",
                  clip-rule="evenodd",
                  d="M0.585786 0.585786C0.960859 0.210714 1.46957 0 2 0H16V4H19L22 8V13H20C20 13.7956 19.6839 14.5587 19.1213 15.1213C18.5587 15.6839 17.7956 16 17 16C16.2044 16 15.4413 15.6839 14.8787 15.1213C14.3161 14.5587 14 13.7956 14 13H8C8 13.7956 7.68393 14.5587 7.12132 15.1213C6.55871 15.6839 5.79565 16 5 16C4.20435 16 3.44129 15.6839 2.87868 15.1213C2.31607 14.5587 2 13.7956 2 13H0V2C0 1.46957 0.210714 0.960859 0.585786 0.585786ZM18.5 5.5H16V8H20.47L18.5 5.5ZM6.06066 11.9393C5.77936 11.658 5.39782 11.5 5 11.5C4.60218 11.5 4.22064 11.658 3.93934 11.9393C3.65804 12.2206 3.5 12.6022 3.5 13C3.5 13.3978 3.65804 13.7794 3.93934 14.0607C4.22064 14.342 4.60218 14.5 5 14.5C5.39782 14.5 5.77936 14.342 6.06066 14.0607C6.34196 13.7794 6.5 13.3978 6.5 13C6.5 12.6022 6.34196 12.2206 6.06066 11.9393ZM18.0607 11.9393C17.7794 11.658 17.3978 11.5 17 11.5C16.6022 11.5 16.2206 11.658 15.9393 11.9393C15.658 12.2206 15.5 12.6022 15.5 13C15.5 13.3978 15.658 13.7794 15.9393 14.0607C16.2206 14.342 16.6022 14.5 17 14.5C17.3978 14.5 17.7794 14.342 18.0607 14.0607C18.342 13.7794 18.5 13.3978 18.5 13C18.5 12.6022 18.342 12.2206 18.0607 11.9393Z",
                  fill="#393939"
                )
      .response_second_block_right(:class="{'custom-dropdown_open' : mobileDropdown}")
        .response_second_block_right_top
          div(v-if="!response.isReadedByMe")
            span Новый
          div(v-if="response.isPayed || (response.status == 'closed')")
            span завершено
          .response_btn_dropdown(v-if="!isMobile", @click="openDropdown", v-click-outside="openDropdownOut")
            svg(width="3", height="15", viewBox="0 0 3 15", fill="none", xmlns="http://www.w3.org/2000/svg")
              circle(cx="1.5", cy="1.5", r="1.5", fill="#888888")
              circle(cx="1.5", cy="7.5", r="1.5", fill="#888888")
              circle(cx="1.5", cy="13.5", r="1.5", fill="#888888")
        .response_second_block_right_bottom
          span.green_status(v-if="response.status == 'pending'") ожидает ответа
            svg(width="13", height="13", viewBox="0 0 13 13", fill="none", xmlns="http://www.w3.org/2000/svg", @click="$root.$emit('showForm','pendingPopUp')")
              path(
                d="M7.15482 3C6.98082 3 6.81394 3.06912 6.6909 3.19216C6.56787 3.3152 6.49875 3.48207 6.49875 3.65607C6.49875 3.83007 6.56787 3.99695 6.6909 4.11998C6.81394 4.24302 6.98082 4.31214 7.15482 4.31214C7.32882 4.31214 7.49569 4.24302 7.61873 4.11998C7.74177 3.99695 7.81089 3.83007 7.81089 3.65607C7.81089 3.48207 7.74177 3.3152 7.61873 3.19216C7.49569 3.06912 7.32882 3 7.15482 3ZM6.99736 5.08631C6.47688 5.13004 5.05539 6.26286 5.05539 6.26286C4.96791 6.32847 4.99416 6.32409 5.06414 6.44656C5.13412 6.56465 5.12537 6.5734 5.20847 6.51654C5.29595 6.45968 5.44028 6.36783 5.68084 6.21912C6.60809 5.62428 5.82955 6.99766 5.43154 9.3114C5.27408 10.4573 6.3063 9.86687 6.5731 9.69192C6.83553 9.52134 7.53971 9.03585 7.60969 8.98774C7.70592 8.92213 7.63594 8.86965 7.56158 8.7603C7.5091 8.68595 7.45661 8.73843 7.45661 8.73843C7.17231 8.92651 6.65183 9.32015 6.58185 9.07084C6.49875 8.82153 7.03235 7.11138 7.3254 5.93482C7.37351 5.6549 7.50472 5.04257 6.99736 5.08631Z",
                fill="#888888"
              )
              circle(cx="6.5", cy="6.5", r="6.15", stroke="#656565", stroke-width="0.7")
          template(v-if="response.status == 'chosen' && response.status != 'closed'") 
            span.info_new_response принят, ожидает оплаты
              svg(width="13", height="13", viewBox="0 0 13 13", fill="none", xmlns="http://www.w3.org/2000/svg", @click="$root.$emit('showForm','chosenPopUp')")
                path(
                  d="M7.15482 3C6.98082 3 6.81394 3.06912 6.6909 3.19216C6.56787 3.3152 6.49875 3.48207 6.49875 3.65607C6.49875 3.83007 6.56787 3.99695 6.6909 4.11998C6.81394 4.24302 6.98082 4.31214 7.15482 4.31214C7.32882 4.31214 7.49569 4.24302 7.61873 4.11998C7.74177 3.99695 7.81089 3.83007 7.81089 3.65607C7.81089 3.48207 7.74177 3.3152 7.61873 3.19216C7.49569 3.06912 7.32882 3 7.15482 3ZM6.99736 5.08631C6.47688 5.13004 5.05539 6.26286 5.05539 6.26286C4.96791 6.32847 4.99416 6.32409 5.06414 6.44656C5.13412 6.56465 5.12537 6.5734 5.20847 6.51654C5.29595 6.45968 5.44028 6.36783 5.68084 6.21912C6.60809 5.62428 5.82955 6.99766 5.43154 9.3114C5.27408 10.4573 6.3063 9.86687 6.5731 9.69192C6.83553 9.52134 7.53971 9.03585 7.60969 8.98774C7.70592 8.92213 7.63594 8.86965 7.56158 8.7603C7.5091 8.68595 7.45661 8.73843 7.45661 8.73843C7.17231 8.92651 6.65183 9.32015 6.58185 9.07084C6.49875 8.82153 7.03235 7.11138 7.3254 5.93482C7.37351 5.6549 7.50472 5.04257 6.99736 5.08631Z",
                  fill="#888888"
                )
                circle(cx="6.5", cy="6.5", r="6.15", stroke="#656565", stroke-width="0.7")
            button(@click="payforContacts()") ОПЛАТИТЬ
          span.info_new_response(v-if="response.status == 'closed'") Нет ответа от поставщика
            svg(width="13", height="13", viewBox="0 0 13 13", fill="none", xmlns="http://www.w3.org/2000/svg")
              path(
                d="M7.15482 3C6.98082 3 6.81394 3.06912 6.6909 3.19216C6.56787 3.3152 6.49875 3.48207 6.49875 3.65607C6.49875 3.83007 6.56787 3.99695 6.6909 4.11998C6.81394 4.24302 6.98082 4.31214 7.15482 4.31214C7.32882 4.31214 7.49569 4.24302 7.61873 4.11998C7.74177 3.99695 7.81089 3.83007 7.81089 3.65607C7.81089 3.48207 7.74177 3.3152 7.61873 3.19216C7.49569 3.06912 7.32882 3 7.15482 3ZM6.99736 5.08631C6.47688 5.13004 5.05539 6.26286 5.05539 6.26286C4.96791 6.32847 4.99416 6.32409 5.06414 6.44656C5.13412 6.56465 5.12537 6.5734 5.20847 6.51654C5.29595 6.45968 5.44028 6.36783 5.68084 6.21912C6.60809 5.62428 5.82955 6.99766 5.43154 9.3114C5.27408 10.4573 6.3063 9.86687 6.5731 9.69192C6.83553 9.52134 7.53971 9.03585 7.60969 8.98774C7.70592 8.92213 7.63594 8.86965 7.56158 8.7603C7.5091 8.68595 7.45661 8.73843 7.45661 8.73843C7.17231 8.92651 6.65183 9.32015 6.58185 9.07084C6.49875 8.82153 7.03235 7.11138 7.3254 5.93482C7.37351 5.6549 7.50472 5.04257 6.99736 5.08631Z",
                fill="#888888"
              )
              circle(cx="6.5", cy="6.5", r="6.15", stroke="#656565", stroke-width="0.7")
          span.chat_status(v-if="response.status == 'payed' && response.status != 'closed'", @click="messageWrite()") Перейти в чат
        .response_dropdown.custom-dropdown(role="list")
          ul.panel__account-dropdown-list
            li.panel__account-dropdown-item(@click="openBodyDropdown")
              a.account-dropdown-link
                svg(width="18", height="18", viewBox="0 0 18 18", fill="none", xmlns="http://www.w3.org/2000/svg")
                  path(
                    fill-rule="evenodd",
                    clip-rule="evenodd",
                    d="M0 15.923C0 16.4738 0.218803 17.002 0.608275 17.3915C0.997747 17.7809 1.52598 17.9997 2.07678 17.9997H13.1529C13.7037 17.9997 14.232 17.7809 14.6214 17.3915C15.0109 17.002 15.2297 16.4738 15.2297 15.923V10.3849C15.2297 10.2013 15.1568 10.0252 15.027 9.89538C14.8971 9.76555 14.7211 9.69262 14.5375 9.69262C14.3539 9.69262 14.1778 9.76555 14.048 9.89538C13.9181 10.0252 13.8452 10.2013 13.8452 10.3849V15.923C13.8452 16.1066 13.7723 16.2826 13.6424 16.4125C13.5126 16.5423 13.3365 16.6152 13.1529 16.6152H2.07678C1.89318 16.6152 1.7171 16.5423 1.58728 16.4125C1.45745 16.2826 1.38452 16.1066 1.38452 15.923V4.8468C1.38452 4.6632 1.45745 4.48712 1.58728 4.3573C1.7171 4.22747 1.89318 4.15454 2.07678 4.15454H7.61486C7.79846 4.15454 7.97454 4.0816 8.10436 3.95178C8.23419 3.82195 8.30712 3.64588 8.30712 3.46228C8.30712 3.27868 8.23419 3.1026 8.10436 2.97278C7.97454 2.84295 7.79846 2.77002 7.61486 2.77002H2.07678C1.52598 2.77002 0.997747 2.98882 0.608275 3.37829C0.218803 3.76776 0 4.296 0 4.8468V15.923ZM9.69164 0.693237C9.69164 0.509638 9.76458 0.333559 9.8944 0.203735C10.0242 0.0739109 10.2003 0.000976563 10.3839 0.000976562H17.3065C17.4901 0.000976563 17.6662 0.0739109 17.796 0.203735C17.9258 0.333559 17.9988 0.509638 17.9988 0.693237V7.61584C17.9988 7.79944 17.9258 7.97552 17.796 8.10534C17.6662 8.23516 17.4901 8.3081 17.3065 8.3081C17.1229 8.3081 16.9468 8.23516 16.817 8.10534C16.6872 7.97552 16.6142 7.79944 16.6142 7.61584V1.3855H10.3839C10.2003 1.3855 10.0242 1.31256 9.8944 1.18274C9.76458 1.05291 9.69164 0.876835 9.69164 0.693237Z",
                    fill="#2FC06E"
                  )
                .title_dd_link(v-if="foldedBody") смотреть детали
                .title_dd_link(v-else) скрыть детали
            li.panel__account-dropdown-item(v-b-modal="`modalResponseReport${response.responseId}`")
              a.account-dropdown-link
                svg(width="16", height="18", viewBox="0 0 16 18", fill="none", xmlns="http://www.w3.org/2000/svg")
                  path(d="M5 0V1H0V3H1V16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H13C13.5304 18 14.0391 17.7893 14.4142 17.4142C14.7893 17.0391 15 16.5304 15 16V3H16V1H11V0H5ZM3 3H13V16H3V3ZM5 5V14H7V5H5ZM9 5V14H11V5H9Z", fill="#FF6E6F", fill-opacity="0.8") Удалить
                .title_dd_link Сделка не состоялась?
  .response_body
    .response_body_content
      .response_body_left
        p.response_terms Условия:
        ul
          li
            p.response_term_title Спецификация
            p.response_term_description
              | Требуются водолазки детские для школы, пастельные тона, для мальчика и девочки. Важно: необходимы образцы.
          li(v-for="answer in sortSequence")
            p.response_term_title {{response.answers[answer].header}}
            p.response_term_description {{response.answers[answer].value}}
      .response_body_right(v-if="response.status == 'payed' && response.user")
        p
          b Поставщик:
          | {{response.user.userName}}
        .response_body_right_infoblock
          img(v-if="response.user.userPhoto", :src="response.user.userPhoto")
          img(v-else, src="/images/logo57.png")
          div
            p Нижний Новгород
            p {{this.phoneFormat(response.user.userPhone)}}
            p {{response.user.userMail}}
        p
          b Контактное лицо:
          span {{response.user.userName}}
    button.hide_button(@click="openBody")
      img(src="/images/icon catalog.png")
  .mobile_fixed_menu
    .mobile_fixed_menu_top
      a(href="#") Принять предложение
      a(href="#") Удалить
    .mobile_fixed_menu_bottom
      a(href="#") Отмена
</template>
<script>
import BudgetNew from "../../GeneralComponents/components/BudgetNew"
import modalDeny from "../components/modalDeny.vue"
import { mapActions } from "vuex"
import Retreat from "../../GeneralComponents/components/retreatBid"
import clip from "../../Icons/iconClip.vue"
export default {
  components: {
    BudgetNew,
    clip,
    modalDeny,
    Retreat
  },
  props: {
    response: {
      type: Object,
      required: true
    }
  },
  directives: {
    slidescroll: {
      inserted(el, bindings) {
        const container = el
        const scrollLeft = container.querySelector(`.${bindings.value.buttonLeft}`)
        const scrollRight = container.querySelector(`.${bindings.value.buttonRight}`)
        const scrollContainer = container.querySelector(`.${bindings.value.scrollElem}`)
        if (scrollLeft) {
          scrollLeft.addEventListener("click", () => {
            scroll("left")
          })
        }
        if (scrollRight) {
          scrollRight.addEventListener("click", () => {
            scroll("right")
          })
        }
        function scroll(direction) {
          switch (direction) {
            case "left":
              scrollContainer.scrollLeft -= 120
              break
            case "right":
              scrollContainer.scrollLeft += 120
              break
            default:
              break
          }
        }
      }
    }
  },
  data() {
    return {
      mobileDropdown: false,
      folded: true,
      foldedBody: true,
      notificationsData: null,
      sortSequence: ["dqh_type_deal", "dqh_volume", "dqh_min_party", "dqh_logistics", "dqh_doc_confirm_quality", "dqh_payment_method", "dqh_payment_term"]
    }
  },
  created() {
    this.notificationsData = {
      id: this.response.responseId,
      command: "is_readed_owner_response"
    }
  },
  computed: {
    cardStatus() {
      return `responsecard--${this.response.status}`
    },
    smallDevice() {
      return window.innerWidth <= 590
    },
    isOpenPayed() {
      return this.response.status == "payed" && this.folded != true
    },
    isOpenBody() {
      return this.foldedBody != true
    },
    isDelivered() {
      return this.response.is_shipping_included == true
    }
  },
  methods: {
    ...mapActions(["updateNotifications"]),
    openDropdown() {
      if (!this.isMobile) {
        this.mobileDropdown = !this.mobileDropdown
      }
    },
    openDropdownOut() {
      if (!this.isMobile && this.mobileDropdown) {
        this.mobileDropdown = !this.mobileDropdown
      }
    },
    payforContacts() {
      let url = `/api/v1/paymentservice/subscriptions/${this.response.paymentslug}/payment`
      axios
        .post(url, { deal_id: this.response.dealId })
        .then((resp) => {
          if (resp.data.result) {
            this.$store.dispatch("setSnackbar", {
              color: "success",
              text: "Успешно оплачено",
              toggle: true
            })

            this.yandexReachGoal("pay_button_in_lk_if_balance_norm")
            this.googleReachGoal("pay_button_in_lk_if_balance_norm")

            window.Api.loadProfile()

            if (this.response.isReadedByMe == 0) {
              this.updateNotifications(this.notificationsData)
            }

            this.$emit("reloadResponses")
          } else {
            this.gotoPayment()
          }
        })
        .catch((error) => {
          this.printErrorOnConsole(error)
          this.$store.dispatch("setSnackbar", {
            color: "error",
            text: "Произошла ошибка, попробуйте позднее",
            toggle: true
          })
        })
    },
    messageWrite() {
      axios
        .post("/api/v1/dialogs/new", { deal_id: this.response.dealId })
        .then((resp) => {
          if (resp.data.result) {
            if (this.response.isReadedByMe == 0) {
              this.updateNotifications(this.notificationsData)
            }
            this.$router.push({
              name: "lk.dialogs.private",
              params: { id: resp.data.data.id }
            })
          }
        })
        .catch((error) => {
          this.printErrorOnConsole(error)
        })
    },
    openResponse() {
      if (this.folded && this.response.isReadedByMe == 0 && this.response.isPayed) {
        this.updateNotifications(this.notificationsData)
      }
      this.folded = !this.folded
    },
    openBody() {
      let blockRight = document.getElementsByClassName("response_second_block_right")
      if (event.composedPath().indexOf(blockRight[0]) == -1) this.foldedBody = !this.foldedBody
    },
    openBodyDropdown() {
      this.foldedBody = !this.foldedBody
    },
    gotoPayment() {
      if (true) {
        let props = {
          summ: this.response.summ,
          slug: this.response.paymentslug,
          dealId: this.response.dealId
        }
        this.$router.push({ name: "lk.wallet", params: { response: props } })
      } else {
      }
    }
  }
}
</script>

<style>
.chat_status {
  width: max-content;
}
</style>
