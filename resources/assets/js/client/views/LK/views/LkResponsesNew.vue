<template lang="pug">
.tabs
  .maintabs
    button.maintab.active Отклики
  .content
    Loader(v-if="this.loading")
    .responses(v-if="responsesList")
      .response(v-for="response in responsesList")
        ResponsecardNew(:response="response", @leaveDeal="leaveDeal", @reloadResponses="getResponses")
    .order_empty(v-else) У вас нет откликов
</template>
<script>
import ResponsecardNew from "../components/ResponsecardNew"
import { mapFields } from "vee-validate"

export default {
  components: {
    ResponsecardNew
  },
  data() {
    return {
      loading: false,
      responsesList: null
    }
  },
  beforeMount() {
    this.getResponses()
  },
  mounted() {
    if (this.$route.query.error) {
      this.addError(this.$route.query.error)
    }
  },
  computed: {
    organizationId() {
      return this.$root.profile.company.id
    }
  },
  methods: {
    addError(message) {
      this.$store.dispatch("setSnackbar", {
        color: "error",
        text: message,
        toggle: true
      })
      this.$router.replace({ name: "lk.responses" })
    },
    leaveDeal(id) {
      axios
        .post(`/api/v1/deals/user/responses/${id}/finish`)
        .then((result) => {
          if (result.data.result == true) {
            this.getResponses()
          }
        })
        .catch((err) => {
          this.$store.dispatch("setSnackbar", {
            color: "error",
            text: `Произошла ошибка, попробуйте позднее`,
            toggle: true
          })
        })
    },
    getResponses() {
      this.loading = true
      axios
        .get("/api/v1/deals/user/responses")
        .then((result) => {
          if (result.data.result) {
            this.responsesList = this.processedData(result.data.data.items)
          }
          console.log(this.responsesList)
        })
        .catch((err) => {})
        .then(() => {
          this.loading = false
        })
    },
    getAnswers(questions) {
      return Object.assign(
        {},
        ...Object.keys(questions).map((item) => {
          return {
            [item]: {
              name: questions[item].slug,
              header: questions[item].header,
              value: questions[item].question
            }
          }
        })
      )
    },
    //  org_id == user.organization_id -
    getResponseStatus(status, winner_id) {
      let result
      if ((status === "waiting_winner" && (!winner_id || winner_id !== this.organizationId)) || status === "moderation") {
        result = "pending"
      }
      if (status === "waiting_payment" && winner_id == this.organizationId) {
        result = "chosen"
      }
      if (status === "finished" && winner_id == this.organizationId) {
        result = "payed"
      }
      if ((status === "finished" && (!winner_id || winner_id !== this.organizationId)) || status === "banned") {
        result = "closed"
      }

      return result
    },
    transformDate(dateObj) {
      if (dateObj) {
        let [year, month, day] = dateObj.date.split(" ")[0].split("-")
        return `${day}.${month}.${year}`
      } else {
        return null
      }
    },
    getUser(owner, organization) {
      let result
      if (owner) {
        result = {
          company: organization ? organization.name : null,
          companyDescription: organization ? organization.org_products : null,
          userId: owner.id,
          userName: owner.name,
          userPhoto: owner.photo,
          userMail: owner.email,
          userPhone: owner.phone
        }
      }
      return result
    },
    processedData(rawResponses) {
      return rawResponses.map((item) => {
        return {
          dealId: item.deal.id,
          dealName: item.deal.name,
          responseId: item.id,
          status: this.getResponseStatus(item.trading_status, item.deal.winner_id),
          files: item.deal.files,
          budget_to: item.deal.budget_to,
          budget_from: item.deal.budget_from,
          budget_with_nds: item.deal.budget_with_nds,
          priceOffer: item.price_offer,
          is_shipping_included: item.is_shipping_included,
          dealPeriod: item.deal.date_of_event,
          isPayed: item.deal.is_payed,
          isReadedByMe: item.is_readed_owner_response,
          deadlinePayment: this.transformDate(item.deal.winner_wating_payment_at || null),
          summ: item.deal.commission,
          paymentslug: item.deal.slug_payment_type,
          answers: this.getAnswers(item.deal.questions),
          user: this.getUser(item.deal.owner, item.deal.organization)
        }
      })
    }
  }
}
</script>
