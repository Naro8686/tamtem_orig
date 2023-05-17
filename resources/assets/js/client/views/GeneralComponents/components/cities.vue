<template lang="pug">
	.city-select__wrapper
		.city-select__overlay(@click="selectCity({})" v-if="!cityObject && searchVal")
		.input-wrapper(:class="{'errors':errors.has('city')}")
			input.input-wrapper__input(
				v-if="needValidate"
				data-vv-as='Город'
				data-vv-name='city'
				@focus="($event)=>{$event.target.select()}"
				@input="debounceSearch($event.target.value)"
				v-validate=`'required'`
				v-model="result"
			)
			input.input-wrapper__input(
				v-else
				@focus="($event)=>{$event.target.select()}"
				@input="debounceSearch($event.target.value)"
				v-model="result"
			)
			i.errors-list(v-if="errors.has('city')") {{...errors.collect('city')}}
			ul.city-select__results(v-if="searchVal")
				template(v-if="citiesList.length==0 && !cityObject")
					li.city-select__result.empty(@click="selectCity({})") Ничего не найдено
				template(v-else)
					li.city-select__result(v-for="city in citiesList" :key="city.id" @click="selectCity(city)") {{city.name}}, {{city.region_name}}    
</template>
<script>
export default {
	props: {
		needValidate:{
			type: Boolean,
			default: true,
		},
		selectedCity: {
			type: Object
		}
	},
	data: () => ({
		searchVal: "",
		cityObject: null,
		citiesList: []
	}),
	created() {
		if (this.selectedCity) {
			this.setDefaultCity(this.selectedCity);
		}
	},
	computed: {
		result: {
			get() {
				return this.searchVal;
			},
			set(val) {
				this.searchVal = val;
			}
		}
	},
	methods: {
		debounceSearch(value) {
			if (value.length > 0) {
				this.getData(value);
			} else {
				this.processCitiesArray([]);
			}
			this.$nextTick(() => {
				this.searchVal = value;
			});
			this.cityObject = null;
			this.result = null;
		},
		setDefaultCity(city) {
			 if (Object.values(city).length > 0) {
				this.cityObject = Object.assign({}, city);
		this.result = `${city.name}, ${city.region_name}`;
		 }
		},
		selectCity(city) {
			this.$emit("selectCity", city);

			this.citiesList = [];
			this.searchVal = null;
			if (Object.values(city).length > 0) {
				this.cityObject = Object.assign({}, city);
				this.result = `${city.name}, ${city.region_name}`;
			} else {
				this.cityObject = null;
				this.result = null;
			}
		},
		processCitiesArray(cities) {
			this.citiesList = [...cities];
		},
		getData(value) {
			axios.get(`/api/v1/kladr/place?query=${value}`).then(response => {
				if (response.data.result == true) {
					this.processCitiesArray(response.data.data.cities);
				}
			});
		}
	}
};
</script>
<style lang="scss" scoped>
@import "~styleguide";


.input-wrapper{
&__input {
				@include hamster-field;
				font-size: 14px;
				line-height: 19px;
				width: 100%;
				padding-right: 26px;
				&::placeholder {
					color: $color-order-input-placeholder;
				}
			}
}

.city-select {
	&__wrapper {
		position: relative;
		z-index: 2;
	}
	&__overlay {
		width: 100vw;
		left: 0;
		position: fixed;
		right: 0;
		top: 0;
		bottom: 0;
		background: transparent;
		z-index: 1;
		visibility: visible;
	}
	&__results {
		list-style-type: none;
		position: absolute;
		z-index: 1;
		background: white;
		width: 100%;
		border: 1px solid $color-border-gray;
		box-shadow: $box-shadow-standart;
		top: calc(100% - 1px);
		max-height: 180px;
		overflow-y: auto;
		height: auto;
		&:empty {
			box-shadow: none;
			border: none;
			height: 0;
		}
	}
	&__result {
		cursor: pointer;
		padding: 10px 12px;
		font: $font-regular;
		color: #000;
		font-size: $fontsize-base;
		&:hover {
			background-color: $color-pale-green;
		}
	}
}
</style>