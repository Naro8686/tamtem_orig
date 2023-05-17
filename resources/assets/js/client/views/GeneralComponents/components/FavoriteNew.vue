<template lang="pug">
a(v-if="bid && $root.profile && (!bid.owner || $root.profile.profile.id !== bid.owner.id)", :title="bid.favourited ? 'Удалить из избранного' : 'Добавить в избранное'", @click="favoriteClick($event)")
  svg(:class="{ favourited: bid.favourited }", width="15", height="21", viewBox="0 0 15 21", fill="none", xmlns="http://www.w3.org/2000/svg")
    path(
      fill-rule="evenodd",
      clip-rule="evenodd",
      d="M7.50031 16.5L15.0006 21V3C15.0006 2.20435 14.6845 1.44129 14.1219 0.87868C13.5593 0.31607 12.7962 0 12.0005 0H3.00013C2.20444 0 1.44135 0.31607 0.878716 0.87868C0.316084 1.44129 0 2.20435 0 3V21L7.50031 16.5ZM1.50006 18.351L7.50031 14.751L13.5006 18.351V3C13.5006 2.60218 13.3425 2.22064 13.0612 1.93934C12.7799 1.65804 12.3983 1.5 12.0005 1.5H3.00013C2.60228 1.5 2.22074 1.65804 1.93942 1.93934C1.6581 2.22064 1.50006 2.60218 1.50006 3V18.351Z",
      fill="#888888"
    )
</template>

<script>
export default {
  data() {
    return {}
  },
  props: {
    bid: Object
  },
  computed: {},
  methods: {
    favoriteClick(evt) {
      if (this.bid.favourited) this.del()
      else this.add()
      evt.preventDefault()
    },
    add() {
      axios
        .post("/api/v1/user/favourites/store", { deal_id: this.bid.id })
        .then((resp) => {
          this.bid.favourited = true
        })
        .catch((error) => {
          this.printErrorOnConsole(error)
        })
    },
    del() {
      axios
        .post("/api/v1/user/favourites/delete", { deal_id: this.bid.id })
        .then((resp) => {
          this.bid.favourited = false
        })
        .catch((error) => {
          this.printErrorOnConsole(error)
        })
    }
  },
  mounted() {}
}
</script>

<style lang="scss" scoped>
.favourited path {
  fill: #2fc06e;
}
</style>
