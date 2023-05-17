<template lang="pug">
.tabs
  .maintabs
    button.maintab.active Избранное
  .orders
    template(v-if="dealList && dealList.items && dealList.items.length")
      .order_block_outside(v-for="(bid, ind) in dealList.items", :key="bid.id")
        BidCardShortNew(:bid="bid", :index="ind", type="favorites")
    p(v-else).order_empty Вы ещё не добавили заявок в избранное
</template>

<script>
import BidCardShortNew from "../../GeneralComponents/components/BidCardShortNew"

export default {
  components: {
    BidCardShortNew
  },
  data() {
    return {
      dealList: null,
      nextPage: 2
    }
  },
  methods: {
    load() {
      this.$parent.loading = true
      axios
        .get("/api/v1/user/favourites")
        .then((resp) => {
          this.dealList = resp.data.data
        })
        .catch((error) => {
          this.printErrorOnConsole(error)
        })
        .then(() => {
          this.$parent.loading = false
        })
    },
    getMore() {
      this.$parent.loading = true
      axios
        .get("/api/v1/user/favourites?page=" + this.nextPage)
        .then((resp) => {
          this.dealList.items = this.dealList.items.concat(resp.data.data.items)
          this.dealList.hasMore = resp.data.data.hasMore
          this.$parent.loading = false
          this.nextPage++
        })
        .catch((error) => {
          this.$parent.loading = false
        })
    }
  },
  mounted() {
    this.load()
  }
}
</script>
