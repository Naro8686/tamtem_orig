<template lang="pug">
.orders
  template(v-if="dealList && dealList.items && dealList.items.length")
    .order_block_outside(v-for="(bid, ind) in dealList.items", :key="bid.id")
      BidCardShortNew(:bid="bid", :index="ind", type="edit")
  .order_empty(v-else-if="this.typeDeal === 'bids'")
    img(src="/images/icon no ads.png")
    p
      | Разместите&nbsp;
      router-link(:to="{name : 'new.bid'}") Новое Предложение
      | , чтобы получать отклики от поставщиков
  .order_empty(v-else)
    img(src="/images/icon no ads.png")
    p
      | Разместите&nbsp;
      router-link(:to="{name : 'new.bid'}") Новый Заказ
      | , чтобы получать отклики от поставщиков
</template>

<script>
import BidCardShortNew from "../../GeneralComponents/components/BidCardShortNew"

export default {
  components: {
    BidCardShortNew
  },
  props: {
    type: String,
    typeDeal: String
  },
  data() {
    return {
      isLoadingMore: false,
      dealList: null,
      nextPage: 2
      // typeDeal: "buy"
    }
  },
  computed: {
    requestLink() {
      let link = `status=active&type_deal=${this.typeDeal}`
      switch (this.type) {
        case "active":
          link = `status=active&type_deal=${this.typeDeal}`
          break
        case "moderate":
          link = `status=moderation&type_deal=${this.typeDeal}`
          break
        case "finished":
          link = `status=archive&finish=1&type_deal=${this.typeDeal}`
          break
        case "blocked":
          link = `status=banned&finish=1&type_deal=${this.typeDeal}`
          break
      }
      return link
    }
  },
  watch: {
    typeDeal() {
      this.load()
    }
  },
  methods: {
    load() {
      this.$emit("loadingSet", true)
      axios
        .get("/api/v1/deals/user/list?page=1&" + this.requestLink)
        .then((resp) => {
          this.dealList = resp.data.data
        })
        .catch((error) => {
          this.printErrorOnConsole(error)
        })
        .then(() => {
          this.$emit("loadingSet", false)
        })
    },
    getMore() {
      this.isLoadingMore = true
      this.$emit("loadingSet", true)
      axios
        .get("/api/v1/deals/user/list?page=" + this.nextPage + "&" + this.requestLink)
        .then((resp) => {
          this.dealList.items = this.dealList.items.concat(resp.data.data.items)
          this.dealList.hasMore = resp.data.data.hasMore
          this.nextPage++
        })
        .catch((error) => {
          this.printErrorOnConsole(error)
        })
        .then(() => {
          this.$emit("loadingSet", false)
          this.isLoadingMore = false
        })
    },
    removeFromList(index) {
      this.dealList.count = this.dealList.count--
      this.dealList.items.splice(index, 1)
    }
  },
  created() {
    this.load()
  }
}
</script>
