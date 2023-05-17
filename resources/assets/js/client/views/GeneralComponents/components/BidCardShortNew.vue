<template lang="pug">
div(:class="{'mobile_active custom-dropdown_open' : dropDownMenu}")
  .order_block(v-if="show", @click="isMobile ? openDropDownMenu() : gotoDetailBid(bid.id)", :id="'bidCardShort-' + bid.id", :class="{'offer' : bid.type_deal=='sell'}")
    .order_block_top
      p Заказ :&nbsp;#[span(:class="{'offer_title' : bid.type_deal=='sell'}") {{ bid.name }}]
      div
        a.not_visible(:to="{ name: 'bids.detail', params: { id: bid.id } }")
          img(src="/images/icon finish.svg")
        FavoriteNew(:bid="bid", @removeFavorite="removeFavorite")
    .order_block_bottom(:class="{'offer_block_bottom' : bid.type_deal=='sell'}")
      .order_block_description(v-if="bid.status != 'moderation'")
        p(v-if="bid.questions && bid.questions.dqh_type_deal") {{ bid.questions.dqh_type_deal.header }} : {{ bid.questions.dqh_type_deal.question }}
        p(v-if="bid.questions && bid.questions.dqh_volume") {{ bid.questions.dqh_volume.header }} : {{ bid.questions.dqh_volume.question }} {{ bid.unit_for_all }}
        p(v-if="bid.questions && bid.questions.dqh_min_party") {{ bid.questions.dqh_min_party.header }} : {{ bid.questions.dqh_min_party.question }}
      p.card-descr(v-else) {{ bid.description }}
      .order_block_info
        p
          img(src="/images/icon_place.svg")
          | {{ bid.cities[0].name }}, Россия
        BudgetNew(:type="bid.type_deal", :from="bid.budget_from", :to="bid.budget_to")
  .order_counters
    span(title="Просмотры")
      img(src="/images/eyecounter.svg")
      | {{ bid.count_views }}
    span(title="Отклики")
      img(src="/images/favoritecounter.svg")
      | {{ bid.count_response_active_after_moderate }}
  .order_dropdown.custom-dropdown(role="list", v-click-outside="closeDropDownMenu")
    ul.panel__account-dropdown-list
      li.panel__account-dropdown-item(@click="gotoDetailBid(bid.id)")
        a.account-dropdown-link(:to="{ name: 'bids.detail', params: { id: bid.id } }")
          svg(width="18", height="18", viewBox="0 0 18 18", fill="none", xmlns="http://www.w3.org/2000/svg")
            path(
              fill-rule="evenodd",
              clip-rule="evenodd",
              d="M0 15.923C0 16.4738 0.218803 17.002 0.608275 17.3915C0.997747 17.7809 1.52598 17.9997 2.07678 17.9997H13.1529C13.7037 17.9997 14.232 17.7809 14.6214 17.3915C15.0109 17.002 15.2297 16.4738 15.2297 15.923V10.3849C15.2297 10.2013 15.1568 10.0252 15.027 9.89538C14.8971 9.76555 14.7211 9.69262 14.5375 9.69262C14.3539 9.69262 14.1778 9.76555 14.048 9.89538C13.9181 10.0252 13.8452 10.2013 13.8452 10.3849V15.923C13.8452 16.1066 13.7723 16.2826 13.6424 16.4125C13.5126 16.5423 13.3365 16.6152 13.1529 16.6152H2.07678C1.89318 16.6152 1.7171 16.5423 1.58728 16.4125C1.45745 16.2826 1.38452 16.1066 1.38452 15.923V4.8468C1.38452 4.6632 1.45745 4.48712 1.58728 4.3573C1.7171 4.22747 1.89318 4.15454 2.07678 4.15454H7.61486C7.79846 4.15454 7.97454 4.0816 8.10436 3.95178C8.23419 3.82195 8.30712 3.64588 8.30712 3.46228C8.30712 3.27868 8.23419 3.1026 8.10436 2.97278C7.97454 2.84295 7.79846 2.77002 7.61486 2.77002H2.07678C1.52598 2.77002 0.997747 2.98882 0.608275 3.37829C0.218803 3.76776 0 4.296 0 4.8468V15.923ZM9.69164 0.693237C9.69164 0.509638 9.76458 0.333559 9.8944 0.203735C10.0242 0.0739109 10.2003 0.000976563 10.3839 0.000976562H17.3065C17.4901 0.000976563 17.6662 0.0739109 17.796 0.203735C17.9258 0.333559 17.9988 0.509638 17.9988 0.693237V7.61584C17.9988 7.79944 17.9258 7.97552 17.796 8.10534C17.6662 8.23516 17.4901 8.3081 17.3065 8.3081C17.1229 8.3081 16.9468 8.23516 16.817 8.10534C16.6872 7.97552 16.6142 7.79944 16.6142 7.61584V1.3855H10.3839C10.2003 1.3855 10.0242 1.31256 9.8944 1.18274C9.76458 1.05291 9.69164 0.876835 9.69164 0.693237Z",
              fill="#2FC06E"
            ) 
          div Открыть
      li.panel__account-dropdown-item
        a.account-dropdown-link(href="#")
          svg(width="19", height="18", viewBox="0 0 19 18", fill="none", xmlns="http://www.w3.org/2000/svg")
            line(y1="17.25", x2="19", y2="17.25", stroke="#3F99FE", stroke-width="1.5")
            path(d="M8.46967 13.5303C8.76256 13.8232 9.23744 13.8232 9.53033 13.5303L14.3033 8.75736C14.5962 8.46447 14.5962 7.98959 14.3033 7.6967C14.0104 7.40381 13.5355 7.40381 13.2426 7.6967L9 11.9393L4.75736 7.6967C4.46447 7.40381 3.98959 7.40381 3.6967 7.6967C3.40381 7.98959 3.40381 8.46447 3.6967 8.75736L8.46967 13.5303ZM8.25 3.27836e-08L8.25 13L9.75 13L9.75 -3.27836e-08L8.25 3.27836e-08Z", fill="#3F99FE") 
          div Завершить
</template>

<script>
import FavoriteNew from "./FavoriteNew"
import BudgetNew from "./BudgetNew"

export default {
  components: {
    FavoriteNew,
    BudgetNew
  },
  data() {
    return {
      show: true,
      dropDownMenu: false
    }
  },
  props: {
    bid: Object,
    index: Number,
    type: String // favorites edit
  },
  computed: {},
  methods: {
    openDropDownMenu() {
      this.dropDownMenu = !this.dropDownMenu
    },
    closeDropDownMenu() {
      let blockTop = document.getElementsByClassName("order_block")
      if (event.composedPath().indexOf(blockTop[0]) == -1 && this.dropDownMenu) this.dropDownMenu = !this.dropDownMenu
    },
    gotoDetailBid(id) {
      // console.log('pusheeee')
      if (id) {
        if (this.bid.type_deal == "buy" && this.bid.date_published) {
          this.$router.push({
            name: "bids.detail",
            params: {
              id: id
            }
          })
        } else {
          this.$router.push({
            name: "sells.detail",
            params: {
              id: id
            }
          })
        }
      }
    },
    deleteBid(evt) {
      this.$store.dispatch("setLoading", true)
      axios
        .post("/api/v1/deals/" + this.bid.id + "/finish")
        .then((resp) => {
          this.$parent.removeFromList(this.index)
        })
        .catch((error) => {
          this.printErrorOnConsole(error)
        })
        .then(() => {
          this.$store.dispatch("setLoading", false)
        })

      evt.preventDefault()
      evt.stopPropagation()
      return false
    },
    removeFavorite(id) {
      if (this.type === "favorites") {
        this.show = false
      }
    }
  },
  mounted() {}
}
</script>

<style>
.offer_title {
  color: #065fd4;
}

.offer_block_bottom {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
</style>
