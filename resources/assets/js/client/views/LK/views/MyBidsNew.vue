<template lang="pug">
.tabs
  .maintabs
    button.maintab(@click="changeType('buy')", :class="{'active' : typeDeal == 'buy'}") Заказы
    button.maintab(@click="changeType('sell')", :class="{'active' : typeDeal == 'sell'}") Предложения
  div
    b-tabs(v-model="tabInd", nav-wrapper-class="subtabs", @input="changeTab")
      b-tab(title="Активные", title-link-class="subtab")
        BidsListNew(v-if="tabInd == 0", type="actived", :typeDeal="isMobile ? mobileTabsClick : typeDeal", @loadingSet="loadingSet")

      b-tab(title="На модерации", title-link-class="subtab")
        BidsListNew(v-if="tabInd == 1", type="moderate", :typeDeal="isMobile ? mobileTabsClick : typeDeal", @loadingSet="loadingSet")

      b-tab(title="Завершённые", title-link-class="subtab")
        BidsListNew(v-if="tabInd == 2", type="finished", :typeDeal="isMobile ? mobileTabsClick : typeDeal", @loadingSet="loadingSet")
</template>

<script>
import BidsListNew from "../components/BidsListNew"
export default {
  components: {
    BidsListNew
  },
  data() {
    return {
      tabInd: 0,
      tabs: ["actived", "moderated", "finished"],
      typeDeal: "buy"
    }
  },
  props: {
    mobileTabsClick: null
  },
  computed: {},
  methods: {
    loadingSet(val) {
      this.$parent.loading = val
    },
    changeTab() {
      this.setHash(this.tabs[this.tabInd])
    },
    setHash(hash) {
      this.$router
        .replace({
          hash
        })
        .catch(() => {})
    },
    changeType(type) {
      this.typeDeal = type
    },
    init() {
      const name = this.$router.history.current.hash.replace("#", "").split("&")[0]

      const ind = this.tabs.indexOf(name)

      this.$nextTick(() => {
        if (ind >= 0) {
          this.tabInd = ind
        } else {
          this.tabInd = 0
        }
        this.setHash(this.tabs[this.tabInd])
      })
    }
  },
  created() {
    this.init()
  }
}
</script>
