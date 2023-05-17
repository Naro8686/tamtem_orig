import BidsList from '../views/Catalog/views/BidsList.vue'
import BidDetail from '../views/Catalog/views/BidDetail.vue'

const routes = [{
		path: '/',
		name: 'bids.list',
		component: BidsList,
		meta: {
			breadcrumb: false,
			title: `${APPNAME} - сервис оптовых заказов`,
			type: 'buy',
			cyrName: 'Заказы'
		},
	},
	{
		path: '/bids/:id',
		name: 'bids.detail',
		component: BidDetail,
		meta: {
			title: `${APPNAME} - Заявка`,
			type: 'buy',
			cyrName: "Заявка"
		},
	},
	{
		path: '/!sales/:id',
		name: 'sells.detail',
		component: BidDetail,
		meta: {
			title: `${APPNAME} - Заявка`,
			type: 'sell'
		},
	},
	{
		path: '/!sales',
		name: 'sells.list',
		component: BidsList,
		meta: {
			breadcrumb: false,
			title: `${APPNAME} - Объявления`,
			type: 'sell',
			cyrName: "Объявления"
		}
	}
]

export default routes