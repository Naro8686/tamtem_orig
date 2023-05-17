<template lang="pug">
form.newbid(@submit.prevent="prepareStepToSave()")
	section.newbid-content
		div.container
			div.newbid-step
				h2.newbid__title Заказ | Спецификация
				section.newbid-step__element.newbid-step__element--nostretch
					div.newbid-card.newbid-step__card
						h3.newbid-card__title Наименование сделки
						button(@click.prevent="toggleTip('firstTip')").newbid-card__tiptrigger
							iconTips
						div#firstTip.newbid-card__tip Укажите наименование продукта и его основную характеристику: "морковь мытая", "мука пшеничная" и т.д.
						section.newbid-card__content
							div.input-wrapper(:class="{'errors': errors.has('name')}")
								input(
									ref="name"
									v-model="stepData.name"
									type="text"
									placeholder="Например, мука первый сорт"
									data-vv-as='Наименование'
									data-vv-name='name'
									v-validate=`'required|min:2'`
								).input-wrapper__input
								i.errors-list(v-if="errors.has('name')") {{...errors.collect('name')}}
					div.newbid-tip.newbid-step__tip
						div.newbid-tip__text
							i.newbid-tip__icon
								iconTips
							p Укажите наименование продукта и его основную характеристику: "морковь мытая", "мука пшеничная" и т.д.
				section.newbid-step__element.newbid-step__element--nostretch
					div.newbid-card.newbid-step__card
						h3.newbid-card__title Выберите категорию
						button(@click.prevent="toggleTip('secondTip')").newbid-card__tiptrigger
							iconTips
						div#secondTip.newbid-card__tip
							| Выберите подходящую категорию. После размещения заказа поставщики, которые находятся в этой категории и отвечают вашим запросам, получат уведомление о вашем заказе.
						section.newbid-card__content.input-wrapper(:class="{'errors':errors.has('category')}")
							div(v-b-modal="'modalNewbidCategories'").newbid-ctg
								button(type="button").newbid-ctg__searchicon
									feather(type="search")
								div.newbid-ctg__field
									input.newbid-ctg__input(type="text" v-validate=`'required'` hidden data-vv-as="Категория" data-vv-name="category" v-model="stepData.category")
									| {{ stepData.category ? stepData.category.name : "Категории" }}
								button(type="button").newbid-ctg__toggleicon
									feather(type="chevron-down")
							i.errors-list(v-if="errors.has('category')") {{...errors.collect('category')}}
					div.newbid-tip.newbid-step__tip
						i.newbid-tip__icon
							iconTips
						div.newbid-tip__text
							p Выберите подходящую категорию. После размещения заказа поставщики, которые находятся в этой категории и отвечают вашим запросам, получат уведомление о вашем заказе.
				section.newbid-step__element.newbid-step__element--nostretch
					div.newbid-card.newbid-step__card
						h3.newbid-card__title Параметры продукта/услуги
						button(@click.prevent="toggleTip('thirdTip')").newbid-card__tiptrigger
							iconTips
						div#thirdTip.newbid-card__tip
							p Укажите полную спецификацию по продукту и требования к поставке. Например: морковь мытая, калибр 2,5-5,5 см, длина 10-25 см, фасовка: сетка овощная
							p При наличии ТЗ прикрепите файл. Укажите, нужны ли вам документы, подтверждающие качество продукции. Все это поможет подобрать нужного вам поставщика.
						div.newbid-card__content
							label.newbid__label Город поставки товара
							div.input-wrapper(:class="{'errors': errors.has('city')}")
								input(type="text" v-validate=`'required'` hidden data-vv-as="Город" data-vv-name="city" v-model="stepData.city")
								cities(@selectCity="setCity" :selectedCity="stepData.city")
								i.errors-list(v-if="errors.has('city')") {{...errors.collect('city')}} 	
						div.newbid-card__content.specification
							label.newbid__label.specification__label Спецификация
							div.specification__fieldbox.input-wrapper(:class="{'errors': (errors.has('specification') || errors.has('file'))}")
								div.input-wrapper
									textarea.specification__field(:placeholder="specificationPlaceholder" rows="7" v-validate=`'required|min:16'` v-model="stepData.description" data-vv-as="Спецификация" data-vv-name="specification")
									ul.specification__uploads
										li.specification__file.specification__file--loaded
											button(type="button" ).specification__file-icon.specification__file-icon--add
												icon-file-load(:variant=" stepData.uploadedFile ? 'loaded' : 'upload'")
											input(v-if="clean" type="file" ref="fileInput" :title="(!!stepData.uploadedFile) ? stepData.uploadedFile.name :'Прикрепить файл'" @change="uploadFile($event.target.files[0])" data-vv-name="file" data-vv-as="Файл" v-validate="`ext:doc,docx,xls,xlsx,pdf,rar,zip,7z|size:10240`").specification__file-input
											.specification__file-data(v-if="stepData.uploadedFile")
												span.specification__file-data--name {{stepData.uploadedFile.name}}
												span.specification__file-data--size {{ addFileSize(stepData.uploadedFile)}}
											button(title="Удалить" v-if="stepData.uploadedFile" type="button" @click="deleteFile").specification__file-icon.specification__file-icon--delete
												span x
								i.errors-list(v-if="errors.has('specification')|| errors.has('file')") {{...errors.collect('specification')}}#[br]{{...errors.collect('file')}}
							div.newbid__checkfield
								input( v-model="stepData.dqh_doc_confirm_quality" type="checkbox" id="specification-doc")
								label(for="specification-doc") Необходимы документы подтверждающие качество продукта (декларация о соответствии, меркурий, протокол испытаний и т. д.)
					div.newbid-tip.newbid-step__tip
						i.newbid-tip__icon
							iconTips
						div.newbid-tip__text
							p Укажите полную спецификацию по продукту и требования к поставке. Например: морковь мытая, калибр 2,5-5,5 см, длина 10-25 см, фасовка: сетка овощная
							p При наличии ТЗ прикрепите файл. Укажите, нужны ли вам документы, подтверждающие качество продукции. Все это поможет подобрать нужного вам поставщика.
				b-modal.modal-wrap(
					id="modalNewbidCategories"
					modal-class="custom-modal-wide"
					content-class="modal-content-custom"
					header-class="modal-header-custom"
					body-class="modal-body-custom"
					ref="modalNewbidCategories"
				)
					categories-list-drops(@setCategory="setCategory" :selectedCategory="stepData.category")
					div(slot='modal-footer')

	footer.newbid-footer
		div.container.newbid-footer__container
			div.newbid-footer__nav
				button(
					type="submit" 
				).newbid-footer__next Далее	
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { Validator } from 'vee-validate';
import cities from '../../GeneralComponents/components/cities.vue'
import iconFileLoad from "../../Icons/iconFileLoad"
import categoriesListDrops from "../components/categoriesListDrops.vue"
export default {
	components: {
		iconFileLoad,
		categoriesListDrops,
		cities
	},
	props:{
		activeStep:{
			type: String
		}
	},
	data () {
		return {
			clean: true,
			specificationPlaceholder: `Фасовка
Потребительская упаковка 
Транспортная упаковка 
Маркировка 
Требуются ли предварительно образцы продукции?`,
			isImageLoaded: false,
			isFileLoaded: false,
			stepData:{
				uploadedFile: null,
				name: null,
				category: null,
				description: null,
					dqh_doc_confirm_quality: false,
					city:null
			}
		}
	},
	created(){
		this.stepData = Object.assign({}, this.currentStep);

		const locale = {
			custom:{
				file:{
					ext: (field,rule) => `Разрешен только 1 файл в следующих форматах: ${ rule.join(', ')} `
				}
			}
		}
		this.$validator.localize('ru',locale);
		// Validator.extend('')
	},
	computed:{
		...mapGetters("createBid",["getStep"]),
		currentStep:{
			get () {
				return this.getStep('step_0')
			},
			set (data) {
				this.saveStep({step:"step_0",data: data})
			}
		}
	},
	methods: {
		...mapActions('createBid',["saveStep"]),
		toggleTip(id) {
			document.getElementById(id).classList.toggle('newbid-card__tip--active');
		},
		// этот вызывается при сабмите формы. 
		prepareStepToSave(){
			this.$validator.validateAll().then((result)=>{
				if (result){
					// видимо, такой конструкцией вызывается сеттер объекта хранилища, записывающий объект
					this.currentStep = this.stepData;
					// это событие вызовет метод, изменяющий положение окна просмотра и сменяющее значение текущего шага в главном компоненте
					this.$emit('goNextStep')
				}else{
					let errorFieldNameToScroll = this.errors.items[0].field
					let containerWithError = document.querySelector(`[data-vv-name='${errorFieldNameToScroll}']`).closest('section.newbid-step__element')
					containerWithError.scrollIntoView()
				}
			})
		},
		setCity(cityObject){
			this.stepData.city = cityObject;
		},
		setCategory(selectedCategory){
			this.$refs.modalNewbidCategories.hide();
			this.stepData.category = selectedCategory;
		},
		addFileSize(file){
			if(!file || file.size==0) {
				return '0 b';
			}else{
				const sizesChar = ["","K", "M", "G", "T", "P", "E", "Z", "Y"];
				let fileSize = file.size;

				let char = '';

				for (let i = 0; i<sizesChar.length; i++ ){
					if (fileSize<1024) {
						char=sizesChar[i];
						break;
					}
					 fileSize /= 1024;
				}				
				return `${fileSize.toFixed(2)} ${char}b`;
			}
			return '0 b'
		},
		deleteFile(){
			this.stepData.uploadedFile = null;
			// даже не спрашивать
			this.clean = false;
			this.$nextTick(()=>{
				this.clean = true;
			})
		},
		uploadFile(file){
			this.stepData.uploadedFile = file;
		}
	}
}
</script>

<style lang="scss" scoped>
@import "~styleguide";

// modal override
.newbid /deep/ .wrapper-content{

}

.errors{
	.newbid-ctg{
		border: 1px solid $danger;
	}
}
.newbid-ctg {
	display: flex;
	align-items: center;
	padding: 0 15px;
	min-height: 43px;
	border-radius: 35px;
	box-shadow: 0 2px 6px 0 rgba(69, 91, 99, 0.18);
	background-color: #ffffff;
	&__searchicon {
		width: 20px;
		margin-right: 20px;
		border: none;
		background: none;
		outline: none;
		& /deep/ svg {
			stroke: $color-base-accent;
			width: 20px;
			height: 20px;
		}
	}
	&__field {

	}
	&__toggleicon {
		border: none;
		background: none;
		margin-left: auto;
	}
}

.specification {
	margin-top: 30px;
	&__field {
		@include hamster-field;
		resize: none;
		width: 100%;
		padding: 15px 13px 73px 13px;
	}
	&__uploads {
		list-style: none;
		position: absolute;
		bottom: 0;
		right: 0;
		left: 0;
		padding: 13px 0;
		border-top: 1px solid $color-border-gray;
		margin: 0 13px;
		button {
			cursor: pointer;
		}
	}
	&__image {
		display: flex;
		flex-direction: column;
		position: relative;
		&-icon {
			background: none;
			border: none;
			& /deep/ div {
				display: flex;
				justify-content: center;
			}
		}
		&-change {
			font-size: 10px;
			line-height: 19px;
			color: $color-base-accent;
			text-decoration: underline;
			text-align: center;
			cursor: pointer;
			background: none;
			border: none;
		}
		&-close {
			position: absolute;
			top: -8px;
			right: -8px;
			background: #fff;
			width: 16px;
			height: 16px;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 16px;
			border-radius: 50%;
			box-shadow: 0 2px 6px 0 rgba(69,91,99,0.18);
			cursor: pointer;
			border: none;
		}
		&--loaded {
			@media(max-width: 425px) {
				flex-direction: row;
				align-items: flex-end;
				.specification__image-change {
					margin-left: 10px;
				}
				.specification__image-close {
					right: 54px;
				}
				.specification__image-icon {
					& /deep/ svg {
						width: 45px;
						height: auto;
					}
				}
			}
		}
	}
	&__file{
		display: flex;
		justify-content: space-between;
		&-icon {
			background: none;
			border: none;
			width: 42px;
			height: 42px;
			&--add{

			}
			&--delete{
				display: flex;
				justify-content: center;
				align-items: center;
				outline: none !important;
				span{
					line-height: 13px;
					font-size: 12px;
					border-radius: 50%;
					box-shadow: $box-shadow-standart;
					width: 14px;
					height: 14px;
				}
			}
		}
		&-input{
			width: 42px;
			height: 42px;
			left: 0;
			opacity: 0;
			cursor:pointer;
			position: absolute;
		}
		&-data{
			width: 100%;
			padding-left: 20px;
			height: 42px;
			display: flex;
			flex-direction: column;
			&--name{
				font: $font-regular;
				font-size: $fontsize-smaller;
				color: #000;
			}
			&--size{
				font: $font-regular;
				font-size: $fontsize-smaller;
				color: $color-text-gray;
			}
		}
	}
	&__fieldbox {
		position: relative;
		
	}
}
// .specification-checkgroup {
// 	margin-top: 30px;
// 	input {
// 		display: none;
// 	}
// 	label {
// 		position: relative;
// 		padding-left: 35px;
// 		cursor: pointer;
// 		&::before {
// 			content: '';
// 			position: absolute;
// 			width: 18px;
// 			height: 18px;
// 			top: 0;
// 			left: 0;
// 			border-radius: 3px;
// 			border: 1px solid $color-base-accent;
// 			background-repeat: no-repeat;
// 			background-position: center;
// 			background-size: 99%;
// 		}
// 	}
// 	input:checked + label::before {
// 		background-image: url(/images/check-square.svg);
// 	}
// }
</style>