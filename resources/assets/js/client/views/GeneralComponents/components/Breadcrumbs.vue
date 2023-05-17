<template lang="pug">
section.breadcrumbs
	nav.breadcrumbs__list
		//- .breadcrumbs__item(v-for="item in items")
			router-link(:to="item.linkName").breadcrumbs__link {{item.cyrName}}
		
		//- это текущее положение, если мы не на странице списка заказов
		.breadcrumb__item(v-if="$route.name != 'sells.list' && $route.name != 'bids.list'")
			router-link(:to="$route.name").breadcrumbs__link {{$route.name}}
		//- если мы в списке заказов, то будет выводиться текущая категория из хранилища
		template(v-if="$route.name == 'sells.list' || $route.name=='bids.list'")
			.breadcrumbs__item
				a(href="").breadcrumbs__link {{rootElement}}
			.breadcrumbs__item
				a(href="").breadcrumbs__link {{getBidCatState.name}}
		//- .breadcrumbs__item
			a(href="#").breadcrumbs__link Все категории
</template>

<script>
import { mapGetters, mapState } from "vuex";
export default {
	data() {
		return {
			items: [
				{cyrName: "Заказы", linkName: "bids.list"},
			],
		}
	},
	computed: {
		...mapGetters("categories", ["getBidCatState"]),
		rootElement() {
			if(this.$route.name == 'sells.list' || this.$route.name == 'bids.list') {
				return this.$route.meta.cyrName;
			}
		},
	},
	methods: {

	}
}
</script>

<style lang="scss" scoped>
.breadcrumbs {
    &__list {
        display: flex;
    }
    &__item {
        &:not(:last-child) {
            &::after {
                content: "/";
                font-size: 13px;
                line-height: 16px;
                color: #888888;
                margin: 0 3px;
            }
        }
    }
    &__link {
        font-weight: normal;
        font-size: 13px;
        line-height: 16px;
        color: #888888;
    }
}
</style>