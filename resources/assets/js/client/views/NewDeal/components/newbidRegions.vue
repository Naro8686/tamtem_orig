<template lang="pug">
	section.select-region
		h4.select-region__title Выберите регион
		div.select-region__search.region-search
			input(type="text" v-model="searchVal" placeholder="Начните вводить название области").region-search__input
			p.region-search__action
				feather(type="search")
		ul.select-region__list
			li(:class="{'active': selectedValuesList.has({id:-1,name:'Россия'})}" @click="selectValue({id:-1,name:'Россия'})") Россия
			li(v-for="region in regionsArray" @click="selectValue(region)") {{region.name}}
		.select-region__footer
			ul.regions-tags
				li(:key="region.id" v-for="region in selectedValuesList") 
					span {{region.name}}
					button(@click="removefromSelectedValueList(region)") &times;
			.select-region__buttons
				button.select-region__button--abort(@click="$emit('close')") Отмена		
				button.select-region__button--save(@click="saveSelected") Сохранить
</template>
<script>
import { mapGetters } from "vuex";
export default {
	props:{
		activeList:{
			type: [Array,Object,Set]
		}
	},
  data() {
	return {
	  searchVal: "",
	  selectedValuesList: new Set()
	};
  },
  created(){
	  !!this.activeList ? this.selectedValuesList = new Set(this.activeList) : "";
  },
  methods: {
	saveSelected(){
		this.$emit('selectRegion',this.selectedValuesList);
	},
	removefromSelectedValueList(region){
		this.selectedValuesList.delete(region);
		this.selectedValuesList = new Set(this.selectedValuesList);
	},
	selectValue(item) {
		
		if(item.id===-1) {
			this.selectedValuesList.clear();
		}
		!this.selectedValuesList.has(item) ? this.selectedValuesList = new Set(this.selectedValuesList.add(item)) : "";
	}
  },
  computed: {
	...mapGetters("regions", ["getFilteredList"]),
	regionsArray() {
	  return this.getFilteredList(this.searchVal);
	},
  }
};
</script>
<style lang="scss" scoped>
@import '~styleguide';
$font-regular: 400 1rem "Montserrat", sans-serif;
.select-region {
	font: $font-regular;
	font-size: 14px;
	&__title {
		font-weight: 500;
		font-size: 26px;
		line-height: 34px;
		margin-bottom: 20px;
	}
	&__list {
		max-height: 258px;
		overflow-y: scroll;
		overflow-x: hidden;
		li {
			height: 43px;
			cursor: pointer;
			display: flex;
			align-items: center;
			padding: 0 15px;
			border-radius: 0;
			transition: background, 0.2s, 0.5s;
			&.active{
				transition: background, 0.2s, 0.5s;
				background-color: $color-pale-green;
			}
			&:hover {
				transition: background, 0.2s, 0.5s;
				background-color: $color-pale-green;
			}
		}
	}
	&__buttons{
		display: flex;
		justify-content: flex-end;
		padding-top: 45px;
	}
	&__button{
		&--abort{
			padding: 0 8%;
			background: transparent;
			color: $color-text-gray;
		}
		&--save{
			@include big-rounded-btn;
			text-transform: none;
			padding: 0 8%;
		}
	}
}
.region-search {
	position: relative;
	margin-bottom: 30px;
	&__input {
		height: 43px;
		border-radius: 35px;
		box-shadow: 0 2px 6px 0 rgba(69, 91, 99, 0.18);
		background-color: #ffffff;
		padding-right: 44px;
		padding-left: 18px;
		width: 100%;
	}
	&__action {
		position: absolute;
		height: 20px;
		top: calc(50% - 10px);
		right: 15px;
		display: flex;
		align-items: center;
		button {
			outline: none;
		}
		& /deep/ svg {
			stroke: $color-base-accent;
		}
	}
}
.regions-tags {
	display: flex;
	flex-wrap: wrap;
	margin-top: 30px;
	li {
		list-style: none;
		font-size: 14px;
		line-height: 18px;
		border-bottom: 1px dashed #000;
		position: relative;
		margin-bottom: 14px;
		&:not(:last-child) {
			margin-right: 27px;
		}
		button {
			position: absolute;
			right: -9px;
			top: -9px;
			font-style: normal;
			font-size: 16px;
		}
	}
}
</style>