<template lang="pug">
form.newbid(@submit.prevent="prepareStepToSave()")
	section.newbid-content
		div.container
			h2.newbid__title Новое объявление
			section.newbid-step__element.newbid-step__element--nostretch
				div.newbid-card.newbid-step__card
					h3.newbid-card__title Наименование
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
								v-validate=`'required|min:2|max:191'`
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
						| Выберите подходящую категорию.
					section.newbid-card__content.input-wrapper(:class="{'errors':errors.has('category')}")
						div(v-b-modal="'modalNewbidCategories'").newbid-ctg
							button(type="button").newbid-ctg__searchicon
								feather(type="search")
							div.newbid-ctg__field
								input.newbid-ctg__input(
									type="text" 
									v-validate=`'required'` 
									hidden 
									data-vv-as="Категория" 
									data-vv-name="category" 
									v-model="stepData.category"
								) 
								| {{ stepData.category ? stepData.category.name : "Категории" }}
							button(type="button").newbid-ctg__toggleicon
								feather(type="chevron-down")
						i.errors-list(v-if="errors.has('category')") {{...errors.collect('category')}}
				div.newbid-tip.newbid-step__tip
						i.newbid-tip__icon
							iconTips
						div.newbid-tip__text
							p Выберите подходящую категорию.
			section.newbid-step__element.newbid-step__element--nostretch
				div.newbid-card.newbid-step__card
					h3.newbid-card__title Параметры продукта/услуги
					button(@click.prevent="toggleTip('thirdTip')").newbid-card__tiptrigger
						iconTips
					div#thirdTip.newbid-card__tip
						p Укажите полную спецификацию по продукции, прикрепите прайс и фото товара. Укажите какие документы предоставляете покупателю, есть ли доставка и пробные образцы
					div.newbid-card__content
						label.newbid__label Город производства товара
						div.input-wrapper(:class="{'errors': errors.has('city')}")
							input(type="text" v-validate=`'required'` hidden data-vv-as="Город" data-vv-name="city" v-model="stepData.city")
							cities(@selectCity="setCity" :selectedCity="stepData.city")
							i.errors-list(v-if="errors.has('city')") {{...errors.collect('city')}}
					div.newbid-card__content.specification
						label.newbid__label.specification__label Спецификация
						div.specification__fieldbox.input-wrapper(:class="{'errors': (errors.has('specification') || errors.has('file') || overfiles)}")
							div.input-wrapper
								textarea.specification__field(
									:placeholder="specificationPlaceholder" 
									rows="7" 
									v-validate=`'required|min:16|max:3000'` 
									v-model="stepData.description" data-vv-as="Спецификация" 
									data-vv-name="specification")
								ul.specification__uploads
									li.specification__file.specification__file--loaded
										button(type="button" ).specification__file-icon.specification__file-icon--add
											icon-file-load(:variant=" stepData.uploadedFiles.length > 0 ? 'loaded' : 'upload'")
										input(
											v-if="clean"
											type="file" 
											multiple
											ref="fileInput" 
											:title="(!!stepData.uploadedFiles) ? stepData.uploadedFiles.name :'Прикрепить файл'" 
											@change="uploadFiles()"
											data-vv-name="file" 
											data-vv-as="Файл"
											v-validate="`ext:png,jpg,doc,docx,xls,xlsx,pdf,rar,zip,7z|size:5000`"
										).specification__file-input
										input(
											type="text"
											ref="filesGuard"
											data-vv-name="file"
											data--vv-as="Файл"
											v-model="fileslength"
											hidden
											v-validate="'max_value:3'"
										)
										.specification__file-data(v-if="stepData.uploadedFiles")
											div.specification__file-data--item(v-for="item in stepData.uploadedFiles")
												span.specification__file-data--name {{item.name}}
												span.specification__file-data--size {{ addFileSize(item)}}
										button(title="Удалить все" v-if="stepData.uploadedFiles" type="button" @click="deleteFile").specification__file-icon.specification__file-icon--delete
											span x
							i.errors-list(
								v-if="errors.has('specification')|| errors.has('file') || overfiles"
							) {{...errors.collect('specification')}}#[br]{{...errors.collect('file')}}#[br]{{overfilesString}}
							
					
				div.newbid-tip.newbid-step__tip
					i.newbid-tip__icon
						iconTips
					div.newbid-tip__text
						p Укажите полную спецификацию по продукции, прикрепите прайс и фото товара. Укажите какие документы предоставляете покупателю, есть ли доставка и пробные образцы
			section.newbid-step__element.newbid-step__element--nostretch
				div.newbid-card.newbid-step__card
					h3.newbid-card__title Ключевые слова объявления
					div.newbid-card__content.keywords
						label.newbid__label Введите ключевые слова
						div.input-wrapper.keywords__row(:class="{'errors': (errors.has('keywords'))}")
							div.input-wrapper.keywords__wrapper
								input.input-wrapper__input.keywords__input(
									:placeholder="keywordsPlaceholder"
									v-model="keywordCurrent"
									@change="addKeyword()"
								)
								i.errors-list(v-if="errors.has('keywords')") {{...errors.collect('keywords')}}
							button(@click.prevent="addKeyword()").keywords__push Добавить
						div.keywords__box
							textarea(
								v-model="stepData.keywords"
								data-vv-as='Ключевые слова'
								data-vv-name='keywords'
								v-validate=`'max:500'`
							).keywords__text
							ul.keywords__tags
								li(:key="keyword.id" v-for="keyword in keywordsList") 
									span {{keyword}}
									button(@click.prevent="removefromKeywordsList(keyword)") &times;
				div.newbid-tip.newbid-step__tip
					i.newbid-tip__icon
						iconTips
					div.newbid-tip__text
						p Введите ключевые слова. Например: "Морковь" или "Морковь мытая" и нажимайте "Добавить".
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
				).newbid-footer__next Опубликовать
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { Validator } from 'vee-validate';
import cities from '../../GeneralComponents/components/cities.vue'
import iconFileLoad from "../../Icons/iconFileLoad"
import categoriesListDrops from "../components/categoriesListDrops.vue"
export default {
	props: {
		activeStep: {
			type: String
		}
	},
	components: {
		iconFileLoad,
		categoriesListDrops,
		cities
	},
	data() {
		return {
			clean: true,
			stepData:{
				name: null,
				category: null,
				description: null,
				city: null,
				uploadedFiles: null,
				keywords: null
			},
			specificationPlaceholder: `Какой вид товара продаете?
Количество товара?
Транспортная упаковка?
Есть ли образцы продукции?
Есть ли прайсы на продукцию?`,
			keywordCurrent: '',
			keywordsList: new Set(),
			keywordsPlaceholder: 'Введите ключевое слово',
			keywordsResult: '',
			fileslength: '',
			overfiles: false,
			overfilesMessage: 'Можно загрузить не более трех файлов',
			overfilesString: ''
		}
	},
	methods: {
		...mapActions('createBid',["saveStep"]),
		// метод, который забубенит данные в хранилище по сути
		prepareStepToSave() {
			this.yandexReachGoal('obyavlenie_razmestit');
			this.$validator.validateAll().then((result)=>{
				if (result){
					// видимо, такой конструкцией вызывается сеттер объекта хранилища, записывающий объект
					this.currentStep = this.stepData;
					// это событие вызовет метод, изменяющий положение окна просмотра и сменяющее значение текущего шага в главном компоненте
					// тут должно генерироваться событие создания заказа
					this.$emit('createSell')
				} else {
					let errorFieldNameToScroll = this.errors.items[0].field
					let containerWithError = document.querySelector(`[data-vv-name='${errorFieldNameToScroll}']`).closest('section.newbid-step__element')
					containerWithError.scrollIntoView()
				}
			})
		},
		// метод, который пройдет по массиву собранных ключевых слов и склеит их в одну строку, где каждый их бывших элементов массива будет с большой буквы и через запятую
		transformKeywords() {
			let list = Array.from(this.keywordsList)
			this.keywordsResult = ''
			list.forEach((item, i, bob) => {
				let word = item.trim()
				word = word.charAt(0).toUpperCase() + word.slice(1)
				if(i >= 1) {
					this.keywordsResult += ', ' + word
				} else {
					this.keywordsResult += word
				}
			})
			this.stepData.keywords = this.keywordsResult
		},

		toggleTip(id) {
			document.getElementById(id).classList.toggle('newbid-card__tip--active');
		},
		setCity(cityObject){
			this.stepData.city = cityObject;
		},
		setCategory(selectedCategory){
			this.$refs.modalNewbidCategories.hide();
			this.stepData.category = selectedCategory;
		},
		removefromKeywordsList(keyword){
			this.keywordsList.delete(keyword);
			this.keywordsList = new Set(this.keywordsList);
			this.transformKeywords()
		},
		addKeyword() {
			if(this.keywordCurrent) {
				this.selectValue(this.keywordCurrent);
				this.transformKeywords()
			}
		},
		selectValue(item) {
			!this.keywordsList.has(item) ? this.keywordsList = new Set(this.keywordsList.add(item)) : "";
			this.keywordCurrent = '';
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
			this.stepData.uploadedFiles = [];
			// даже не спрашивать
			this.clean = false;
			this.$nextTick(()=>{
				this.clean = true;
			})
			// this.fileslength = this.stepData.uploadedFiles.length
			this.fileslength = 0
		},
		uploadFiles(){
			// this.stepData.uploadedFiles.push(file);
			// console.log(file)
			this.stepData.uploadedFiles = Array.from(this.$refs.fileInput.files)
			this.fileslength = this.stepData.uploadedFiles.length
		},
	},
	computed: {
		...mapGetters("createBid",["getStep"]),
		currentStep:{
			get () {
				return this.getStep('create_sell')
			},
			set (data) {
				this.saveStep({step:"create_sell",data: data})
			}
		}
	},
	watch: {
		fileslength() {
			if(this.fileslength > 3) {
				this.overfiles = true
				this.overfilesString = this.overfilesMessage
			}
			if(this.fileslength <= 3) {
				this.overfiles = false
				this.overfilesString = ''
			}
		}
	},
	created() {
		this.stepData = Object.assign({}, this.currentStep);
		// Validator.extend('addfiles', {
		// 	getMessage: field => {
		// 		`Можно загрузить не более трех файлов`
		// 	},
		// 	// validate: value => {
		// 	// 	return value.files.FileList.length <= 3
		// 	// }
		// 	validate: () => {
		// 		return this.$refs.fileInput.files.length > 3
		// 	}
		// });
		const locale = {
			custom:{
				file:{
					ext: (field,rule) => `Разрешено только 3 файла в следующих форматах: ${ rule.join(', ')} `,
					max_value: (field,rule) => `Можно загрузить не более трех файлов`
				}
			}
		}
		this.$validator.localize('ru',locale);
	}
}
</script>

<style lang="scss" scoped>
@import "~styleguide";

.errors{
	.newbid-ctg {
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
			// height: 42px;
			display: flex;
			align-items: center;
			flex-direction: row;
			flex-wrap: wrap;
			&--item {
				padding: 4px 0;
				&:not(:last-child) {
					margin-right: 15px;
				}
			}
			&--name{
				font: $font-regular;
				font-size: $fontsize-smaller;
				color: #000;
				margin-right: 5px;
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
.keywords {
	margin-top: 30px;
	&__row {
		display: flex;
	}
	&__wrapper {
		width: calc(70% - 15px);
		margin-right: 30px;
	}
	&__push {
		width: calc(30% - 15px);
		display: flex;
		justify-content: center;
		align-items: center;
		border-radius: 32px;
		background-color: $color-base-accent;
		color: #fff;
	}
	&__text {
		display: none;
	}
	&__tags {
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
}
</style>