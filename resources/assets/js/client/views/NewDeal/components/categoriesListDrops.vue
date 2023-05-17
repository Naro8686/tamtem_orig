<template lang="pug">
	section.ctgs-drops-list
		h4.ctgs-drops-list__title Категории
		div.ctgs-drops-list__content
			ul.ctgs-drops-list__firstlist.firstlist
				li.firstlist__item(v-for="category in categoriesArray" :class="{'active' : category.id==openedCategoryId}")
					div.firstlist__box(@click="toggleOpenedCategory(category.id)")
						i.firstlist__arrow(v-if="category.items")
							feather(type="chevron-right")
						p.firstlist__text {{category.name}}
					ul.secondlist(:key="category.id" v-if="category.items && category.items.length>0 && category.id==openedCategoryId")
						li.secondlist__item(v-for="subcategory in category.items")
							input(type="radio" :checked="activeSubCategoryId==subcategory.id" :id="createCategoryIdentifier(subcategory)" :name="createCategoryIdentifier(category)")
							label(:for="createCategoryIdentifier(subcategory)" @click="setCategory(subcategory)") {{subcategory.name}}
		button.ctgs-drops-list__submit(@click="save") {{activeSubCategory!=null? 'Сохранить' : 'Отменить'}}
</template>
<script>
import { mapState } from "vuex";
export default {
  props:{
    selectedCategory:{
      type: Object
    }
  },
	data: () => ({
		openedCategoryId: null,
		activeSubCategory: null,
		activeSubCategoryId: null

  }),
  created(){
    if (this.selectedCategory && Object.values(this.selectedCategory).length>0){
      this.openedCategoryId = this.selectedCategory.parent_id;
      this.activeSubCategoryId = this.selectedCategory.id;
      this.activeSubCategory = this.selectedCategory;

    }
  },
  computed: {
    ...mapState("categories", ["categoriesArray"])
  },
  methods:{
	  setCategory(category){
		  this.activeSubCategory = category;
		  this.activeSubCategoryId = category.id;
	  },
	  save(){
		  this.$emit('setCategory',this.activeSubCategory)
	  },
	  toggleOpenedCategory(categoryId){
		  categoryId==this.openedCategoryId ? this.openedCategoryId = null : this.openedCategoryId = categoryId
	  },
	  createCategoryIdentifier(category){
		  return `category_${category.id}`
	  }
  }
};
</script>
<style lang="scss" scoped>
$color-base-accent: #2fc06e;
$font-regular: 400 1rem "Montserrat", sans-serif;
.ctgs-drops-list {
  font: $font-regular;
  font-size: 14px;
  &__title {
    font-size: 22px;
    line-height: 45px;
  }
  &__content {
    display: flex;
    display: grid;
    grid-template-columns: 360px 1fr;
    max-height: 548px;
    overflow: scroll;
    overflow-x: hidden;
    @media (max-width: 525px) {
      grid-template-columns: 1fr;
    }
  }
  &__submit {
    height: 43px;
    width: 153px;
    background-color: $color-base-accent;
    color: #fff;
    border-radius: 32px;
    font-size: 14px;
    font-weight: 600;
    margin-top: 10px;
    margin-left: auto;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .firstlist {
    &__item {
      list-style: none;
    }
    &__box {
      display: flex;
      align-items: center;
      padding: 3px 0;
      border-radius: 4px;
      cursor: pointer;
      &:hover {
        background-color: #d4f5de;
      }
    }
    &__arrow {
      margin-right: 23px;
      display: flex;
      align-items: center;
      transition: transform 0.4s;
    }
    &__text {
      width: 100%;
    }
    &__item.active {
      .firstlist__arrow {
        transform: rotate(90deg);
      }
    }
  }
  .secondlist {
    margin-left: 42px;
    &__item {
      input {
        display: none;
      }
      list-style: none;
      padding: 4px 0;
      label {
		// width:100%;
        padding-left: 25px;
        padding-left: 35px;
        position: relative;
        cursor: pointer;
      }
      label::before {
        content: "";
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 1px solid #707070;
        background-color: #ffffff;
        position: absolute;
        left: 0;
        top: calc(50% - 7px);
      }
      label::after {
        content: "";
        width: 10px;
        height: 10px;
        position: absolute;
        top: calc(50% - 5px);
        left: 6px;
        background: transparent;
        border-radius: 10px;
        width: 4px;
        height: 4px;
        left: 5px;
        top: calc(50% - 2px);
      }
      input:checked + label::before {
        background-color: $color-base-accent;
        border-color: $color-base-accent;
      }
      input:checked + label::after {
        background-color: #fff;
      }
    }
  }
}
</style>