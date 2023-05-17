<template lang="pug">
.subcategories(v-if="props.subcategories.length")
	ul.subcategories-list
		li.subcategories-item
			a.subcategories-name(
				href='javascript:void(0);'
				@click.stop="listeners['selectCategory']({id:-1,name:''})"
			) Все подкатегории
		li.subcategories-item(
			:class="{'active': props.activeId==item.id}"
			v-for='item in props.subcategories'
		)
			a.subcategories-name(
				href='javascript:void(0);'
				@click.stop="listeners['selectCategory'](item)"
			) {{item.name}}
</template>
<script>
export default {
  props: {
  	subcategories: {
  		type: [Array, Object],
  		default: () => {
  			return [];
  		}
  	},
  	activeId: {
  		type: [Number, String]
  	}
  }
  };
</script>
<style lang="scss" scoped>
@import "~styleguide";

.subcategories {
	&-list {
		width: 100%;
		list-style-type: none;
		max-height: 80vh;
		height: auto;
		overflow-y: auto;
		column-count: 2;
		@media (max-width: 992px) {
			column-count: 1;
		}
		@media (max-width: 640px) {

		}
	}

	&-item {
		display: flex;
		align-items: center;
		padding-left: 60px;
		padding-right: 20px;
		min-height: 52px;
		padding-top: 5px;
		padding-bottom: 5px;
		&.active {
			color: #888888;
		}
	}

	&-name {
		font-weight: 600;
		font-size: 14px;
		line-height: 17px;
		color: #222222;
		cursor: pointer;
		text-decoration: none;
		position: relative;
		&:hover {
			color: #888888;
		}


	}
}
</style>