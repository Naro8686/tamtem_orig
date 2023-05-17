<template lang="pug">
section#slider-categories(v-if="categoriesArray.length")
  .custom-container
    .slider-categories
      carousel.slider-categories__carousel(:loop="false" :margin="25" :autoWidth="true" :autoHeight="true" :responsive="{0:{nav:false,dots:false},600:{nav:false,dots:false},1200:{nav:true,dots:false}}")
        template(slot="prev")
          span(class="prev")
            button.owl-button
              svg(width='9', height='15', viewBox='0 0 9 15', fill='none', xmlns='http://www.w3.org/2000/svg')
                path(d='M7.69231 14.6667L0.581055 7.55557L7.69231 0.444458L8.58105 1.33319L2.35855 7.55557L8.58105 13.778L7.69231 14.6667Z', fill='#888888')
        template(v-for='category in categoriesArray')
          .item 
           .lolclass
            a.sliders-categories__link(@click='routeToCategory(category.id)') {{category.name}}
        template(slot="next")
          span(class="next")
            button.owl-button
              svg(width='9', height='15', viewBox='0 0 9 15', fill='none', xmlns='http://www.w3.org/2000/svg')
                path(d='M1.80672 14.6667L8.91797 7.55557L1.80672 0.444458L0.917969 1.33319L7.14047 7.55557L0.917969 13.778L1.80672 14.6667Z', fill='#888888')
            

            
</template>
<script>
import { mapGetters, mapState } from "vuex";
import carousel from 'vue-owl-carousel'
export default {
  
	components: {
    carousel
	},
	data() {
		return {

		};
  },
  mounted() {
  },
	methods: {		
		routeToCategory(categoryItem) {  
      this.$router.push('/' + this.routePath + '?page=1&category=' + categoryItem)
    }
  },
  computed: {
    ...mapState("categories", ["categoriesArray"]),
		routePath() {
			if (this.$route.name === 'sells.list') {
				return '!sales'
			}
			return 'bids'
		}

  }
};
</script>
<style>

/* .owl-item {
  display: none;
}
.owl-item.active {
  display: block;
} */

.owl-stage-outer {
  height: 25px !important;
}
#carousel_prev {
  display: block;
}
.owl-nav {
  display: none;
}
.slider-categories__carousel span {
  display: unset !important;
}

.slider-categories {
  margin-bottom: 25px;
}

.owl-button {
  width: 35px;
  height: 35px;
  background: #e2ebfd !important;
  transform: rotate(45deg);
  display: flex;
  justify-content: center;
  align-items: center;
}

.prev {
  position: absolute;
  left: 35px;
  top: -5px;
}

.next {
  position: absolute;
  right: 35px;
  top: -5px;
}

.owl-button svg {
  transform: rotate(-45deg);
}

.custom-container {
  position: relative;
}

@media(max-width: 1199px) {
  .slider-categories__carousel span {
    display: none !important;
  }
}

</style>