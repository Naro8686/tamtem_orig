<template functional lang="pug">
	div.companyselect
		ul.companyselect__list
			li.companyselect__item(v-for="company in props.companies")
				a.companyselect__target(@click.stop="listeners['selectCompany'](company)") 
					p.companyselect__name {{company.value}}
					p.companyselect__kpp(v-if="company.data.kpp || company.data.ogrn") {{company.data.kpp ? `КПП : ${company.data.kpp}` : ""}} {{company.data.ogrn ? `ОГРН : ${company.data.ogrn}` : ""}}
					p.companyselect__address(v-if="company.data.address") {{company.data.address.value}} 
</template>
<script>
export default {
  props: {
    companies: {
      type: [Array, Object]
    }
  }
};
</script>
<style lang="scss" scoped>
@import "~styleguide";
.companyselect {
  z-index: 3;
  width: 100%;
  background: #fff;
  position: absolute;
  &__list {
    border: 1px solid $color-border-gray;
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
    border-top: none;
    overflow-y: auto;
    height: 203px;
  }
  &__item {
    list-style: none;
    cursor: pointer;
    min-height: 101px;
    background: $color-base-gray-light;
    &:not(:last-child) {
      border-bottom: 1px solid $color-border-gray;
    }
    &:hover {
      background: $color-pale-green;
      color: #000;
    }
    &:active {
      color: $color-text-dark;
    }
  }
  &__target {
    background: $color-base-gray-light;
    height: inherit;
    width: 100%;
    display: flex;
    flex-direction: column;
    min-height: 43px;
    padding: 24px 12px 12px;
    color: $color-text-gray;
    &:hover {
      background: $color-pale-green;
      color: #000;
    }
    &:active {
      color: $color-text-gray;
    }
  }
  &__name {
    color: $color-base-accent;
    font-size: 12px;
    line-height: 15px;
    margin-bottom: 3px;
    text-transform: uppercase;
  }
  &__kpp,
  &__address {
    font-size: 10px;
    line-height: 16px;
    color: #000;
  }
}
</style>