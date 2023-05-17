import CompaniesDetail from '../views/Companies/views/Detail'

const routes = [
	{
        path: '/companies/:id',
        name: 'companies.detail',
        component: CompaniesDetail,
		meta: {
			title: `${APPNAME} - Страница компании`,
		},
    },
]

export default routes