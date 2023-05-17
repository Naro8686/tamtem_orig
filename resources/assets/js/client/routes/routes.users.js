import UsersProfile from '../views/Users/views/Profile'

const routes = [
	{
        path: '/users/:id',
        name: 'users.profile',
        component: UsersProfile,
		meta: {
			title: `${APPNAME} - Страница пользователя`,
		},
    },
]

export default routes