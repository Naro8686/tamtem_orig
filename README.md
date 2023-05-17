# mvp-tamtem система

Система создания и заключения заявок между поставщиками и покупателями. 

**В текущей версии реализовано:**
- Регистрация участников системы
- Отправка email и уведомлений
- Определение местоположения
- Создание заявок
- Участие в Заявках
- Редактирование профиля
- Создание новостей
- Загрузка изображений
- Диалоги на Laravel-Echo-Server
- Жалобы
- Обратная связь
- Доп. аккаунты пользователей с настройкой пра доступа
- Фильтры
    - Организаций
    - Новостей
    - заявок
    - Маркеров на карту
- Панель администратора
- Пополнение кошешька в платежной системе payture

Документацмя по методам API находится в `/docs/api.md`

Используемые технологии:
- Laravel 5.x
- Redis
- MySQL
- Laravel Echo Server
- VueJS
- php 7.x

## Установка

git clone http://gitlab.tecman.ru/roman/b2b
cd b2b
mcedit .env #Check options for Database, Redis, EMAIL, Queue druver, set Broadcast drive as Redis
mcedit laravel-echo-server.json # Set you server addr for auth host
composer update
sudo chmod -R 777 storage
sudo chmod -R 777 bootstrap/cache
php artisan migrate
php artisan db:seed
php artisan key:generate
npm install
npm run dev # or prod
# Start as service
# Start redis-server
!!!!  при релизе нового кода нужно перезагрузать echo-server  и  воркеры очереди  !!!!
laravel-echo-server start
php artisan queue:work --queue=geo,default --tries=3  (https://www.youtube.com/watch?v=eqKEbJzkpGc&t=3s  - автоперезапуск очереди - для админа)

Запускаем крон для периодических заданий, каждый час будут запускаться
 crontab -e
 @hourly php /... path_to_the_artisan ... /artisan schedule:run >> /dev/null 2>&1

## Добавление IP в белый список для запросов с TAMTEM agents

В файле \config\constants.php  в массив ip_white_list  добавляем нужный IP, сохраняем файл.
Даем командцу: php artisan config:cache

Убедитесь в правильности установки и в том что верно настроили среду для проекта.

Панель администратора доступна по адресу http://localhost/admin, `l: admin@admin.com, p: admin`

**Релиз нового кода:**
- остановить echo-server  и  воркеры очереди  
- php artisan down
- Забрать код из гита
- composer install
- composer dump-autoload
- php artisan migrate
- php artisan cache:clear
- php artisan config:cache
- php artisan up
- запустить echo-server  и  воркеры очереди 

# API mvp-tamtem

Формат ответа от сервера имеет следующий вид:

    {
        result: Boolean,
        data: Object
    }
    
Ответы с пагинацией

    {
        result: true,
        data: {
            count: Integer,
            hasMore: Boolean,
            items: Array
        }
    }
    
Ответы которые проходят валидацию, в результате ошибки отдают код `422` и содержат массив `errors` с описанием ошибок.
Ответы требующие авторизацию в случае ошибки отдают код `411`.

## Составные сущности

### Организация

    {
        id: Integer,
        owner_id: Integer,
        owner_first_name: String,
        owner_second_name: String,
        owner_middle_name: String,
        contact_phone: Integer,
        favorite: Boolean,
        rating: Float,
        organization: {
            type: 'supplier' or 'customer',
            inn: String,
            name: String,
            legal_form: String,
            legal_form_slug: String,
            director: String,
            address: String,
            address_legal: String,
            work_time: WorkTime,
            location: {
                lat: Float,
                lon: Float
            },
            site: Url,
            description: String,
            media: {
                logo: String,
                video: String,
                image_1: String,
                image_2: String,
                image_3: String,
            },
            city: {
                id: Integer,
                name: String
            },
            region: {
                id: Integer,
                name: String
            ],
            country: {
                id: Integer,
                name: String
            ],
            categories: ArrayOf {
                id: Integer,
                name: String,
            },
            stores: ArrayOf {
                id: Integer,
                address: String,
                location: {
                    lat: Float,
                    lon: Float
                }
            },
            offices: ArrayOf {
                id: Integer,
                address: String,
                location: {
                    lat: Float,
                    lon: Float
                }
            }
    }

### Новость
    
    {
        id: Integer,
        date_create: Date,
        date_update: Date,
        title: String,
        description: String,
        shortdesc: String,
        url: String,
        media: {
            image_1: String,
            image_2: String,
            image_3: String,
        },
        organization: {
            id: Integer,
            name: String,
        },
        owner: {
            id: Integer,
            name: String,
        },
        city: {
            id: Integer,
            name: String,
        },
        region: {
            id: Integer,
            name: String,
        },
        country: {
            id: Integer,
            name: String,
        },
        categories: ArrayOf {
            id: Integer,
            name: String,
        },
    }
    
### Заявка

    {
        id: Integer,
        date_create: Date,
        date_update: Date,
        name: String,
        description: String,
        pay_type_cash: Boolean,
        pay_type_noncash: Boolean,
        budget_from: Decimal(14.2),
        budget_to: Decimal(14.2),
        fast_deal: Boolean,
        favorite_only: Boolean,
        deadline_deal: Date,
        deadline_service: Date,
        question: String,
        finish: Boolean,
        finished_at: Date,
        winner: Organization,
        winner_id: Integer,
        organization: {
            id: Integer,
            name: String,
        },
        owner: {
            id: Integer,
            name: String,
            phone: String,
            photo: url,
        },
        categories: ArrayOf {
            id: Integer,
            name: String,
        },
        cities: ArrayOf {
            id: Integer,
            name: String,
        },
        questions: ArrayOf {
             id: Integer,
             name: String,
             question: String,
        },
        members: ArrayOf {
            organization: Organization,
            answers: ArrayOf {
                question: Integer/Null,
                answer: String
            }
        },
        status: String,
        favourited: Boolean,
        subtype_deal: String
    }

### Рейтинг

    {
        id: Integer,
        rate: Integer,
        comment: String,
        sender: {
            id: Integer,
        }
    }

### Диалог

    {
        id: Integer,
        date_create: Date,
        date_update: Date,
        organization: {
            id: Integer,
            name: String,
        },
        message: String
    }
    
### Сообщение

    {
        id: Integer,
        date_create: Date,
        owner: {
            id: Integer,
            name: String,
        },
        message: String
    }

### Авторизация - new ----------------------------------------------------------------------------------------------------

    [POST] /api/v1/user/login

| Field      | Required   |
| ---------- | ---------- |
| `password` | yes |
| `email`    | yes |
    
Возвращает `api_token`, который используется для запросов требующих авторизацию. 
Указывается либо как `GET` параметр `?api_token=you-api-token`, либо в заголовке `Authorization: Bearer you-api-token`.


### Регистрация пользователя - new -------------------------------------------------------------------------------------------------------------

    [POST] /api/v1/register/user

| Field                             | Req |
| --------------------------------- | --- |
| `name`                            | y   |
| `email`                           | y   |
| `password`                        | y   |
| `categories` []                   | y   |
| `agent_id`                        | n   | если есть, то считается, что был переход по реферелке организация привязывается к агенту
| `agent_inn`                       | n   | inn - организацию с каким ИНН привязать к агенту
| `to_register_user`                | y   | 1|0  - 1 = автоматически зарегистрировать юзера, авторизовать его и вернуть токен  АПИ.
| ================================= | === |
| Все параметры ниже, должны        |     |
| быть внутри `organization`[]      | y   |
| --------------------------------- | --- |
| `first_name`                      | n   |
| `second_name`                     | n   |
| `middle_name`                     | n   |
| `phone`                           | n   |
| `org_city_id`                     | y   |
| `org_name`                        | y   |
| `org_inn`                         | y   |
| `org_kpp`                         | y   | 
| `org_description`                 | n   |
| `org_products`                    | n   |  краткое описание ,чем занимается компания 5000 симв
| `video`                           | n   |
| `logo`                            | n   |
| `image_1`                         | n   |
| `image_2`                         | n   |
| `image_3`                         | n   |
| `org_address`                     | n   |
| `org_address_legal`               | n   |
| `org_legal_form_id`               | y   |
| `org_director`                    | n   |
| `org_site`                        | n   |
| `org_work_time`                   | n   |
| `offices`[]                       | n   | arrary [JSON]
| `stores`[]                        | n   | arrary [JSON]
| `org_manager_post`                | y   |
| `org_okved`                       | y   |
| `org_ogrn`                        | y   |
| `org_is_active`                   | y   |
| `org_registration_date`           | y   |

В


### Послать повторно письмо с подтверждением почты - new -----------------------------------------------------------------------

    [POST] /api/v1/register/repeateregistermail

| Field                             | Required   |
| --------------------------------- | ---------- |
| `email`                           | yes        |


### Данные профиля ЛК- new --------------------------------------------------------------------------------------------------------------------

    [GET] /api/v1/user/profile
    
Result
    {
        profile: {
            id: integer
            email: string
            name: string,
            role: enum,
            status: enum,
            unique_id: integer,
            is_org_created: 1|0
            phone: string ,
            photo: string, -url  to photo
            ballance: double,
            notice_allowed: boolean,
        },
        permissions: Array of permissions,
        company: Organization,      -  если создана организация юзером
        active_payment_subscriptions: array subscriptions,      -  активные оплаченные тарифные подписки
        user_categories: array of Category - если организация не создана
    }

### Данные профиля ЛК публичные (для всех), по id юзера - new ----------------------------------------------------------------------------------------------

    [GET] /api/v1/user/profile/:id
    
Result
   
    {
        profile: {
            id: integer
            email: string
            name: string,
            phone: string ,
            photo: string, -url  to photo
        },
        company: Organization,      -  если создана организация юзером
        city: {
            id: integer
            name: string
        }, 
        region: {
            id: integer
            name: string
        }, 
        country: {
            id: integer
            name: string
        }, 
        categories: [ 
            {
                id: integer
                name: string
            }
        ], 
        stores: [ 
            {
                id: integer
                address: string
                location: {
                        lat: 
                        lon: 
                    }
            }
        ], 
        offices: [ 
            {
                id: integer
                address: string
                phone: string
                email: string
                location: {
                        lat: 
                        lon: 
                    }
            }
        ], 
    }


### Редактировать данные профиля ЛК- new -------------------------------------------------------------------------------------------------------------------

    [Authorized]
    [POST] /api/v1/user/update
    
| Field                             | Required   |
| --------------------------------- | ---------- |
| `name`                            | no         |
| `phone`                           | no         |
| `photo`                           | no         |

Result
   
    {
        result: true,
        data: {
            id: integer,
            name: string,
            email: string,
            unique_id: integer,
            phone: string,
            photo: string,
            organization_id: integer,
            role: string,
            status: string,
            is_org_created: 1|0,
        }
    }


### Удалить фото профиля ЛК- new -------------------------------------------------------------------------------------------------------------------
   
    [Authorized]
    [POST] /api/v1/user/deletephoto
    
| Field                             | Required   |
| --------------------------------- | ---------- |
| `path`                            | yes        |


Result
   
    {
        result: true|false,
    }

### ЛК - подписка и отписка на оповещения о новых сделка по почте - new ---------------------------------------------------------------------
   
    [Authorized]
    [POST] /api/v1/user/subscribe
    
| Field                             | Req |
| --------------------------------- | --- |
| `notice_allowed`                  | y   | 1 - подписаться. 0 - отписаться

Result
   
    {
        result: true|false,
    }

### Заказ клиентом звонка в службу поддержки- new -------------------------------------------------------------------------------------------------------------------
   
    [POST] /api/v1/user/callme
    
| Field                             | Required   |
| --------------------------------- | ---------- |
| `phone`                           | yes        |


Result
   
    {
        result: true,
        data : "Письмо в службу поддержки успешно отправлено, ожидайте звонок в ближайшее время"
    }

### КЛАДР

#### Список стран

    [GET] /api/v1/kladr/countries
    
#### Список регионов

    [GET] /api/v1/kladr/regions/:country
    
#### Список городов

    [GET] /api/v1/kladr/cities/:region
    
#### Поиск места - new -------------------------------------------------------------------------------------------------------------

    [GET] /api/v1/register/place?query=place-name
    
Result
       
    {
        cities: [],
            id: integer,
            name: string,
            geo_lat: double,
            geo_lon: double,
            region_id: integer,
            region_name: string
        regions: [],
    }
        
#### Тукущее местоположение

    [GET] /api/v1/kladr/position
    
#### Инормация о регионе

    [GET] /api/v1/kladr/region/:region

#### Инормация о стране

    [GET] /api/v1/kladr/country/:country
    
### Новости

#### Новости созданные администраторами

    [GET] /api/v1/news

| Field                             | Required   |
| --------------------------------- | ---------- |
| `category`                        | no         |


#### Получение полной новости

    [GET] /api/v1/news/:news
    
###  Древо категорий

Получение древовидного списка категорий

    [GET] /api/v1/category/tree
    
### Фильтр  - new -------------------------------------------------------------------------------------------------------------

Фильтры это механизмы поиска новостей, организаций, маркеров и заявок на сайте.

#### Организации по фильтрам - new -------------------------------------------------------------------------------------------------------------

    [GET] /api/v1/filter/organization
    
| Field                             | Required   |
| --------------------------------- | ---------- |
| `categories[]`                    | no         | id категории (передается массив)
| `cities[]`                        | no         | id города (передается массив)
| `search`                          | no         | строка поиска по названию компании, или ее описанию

    
#### Маркеры организаций

    [GET] /api/v1/filter/markers
    
| Field                             | Required   |
| --------------------------------- | ---------- |
| `category`                        | no         |
| `country`                         | no         |
| `region`                          | no         |
| `city`                            | no         |

#### Новости и Заявки

    [GET] /api/v1/filter/news
    [GET] /api/v1/filter/stocks
    
| Field                             | Required   |
| --------------------------------- | ---------- |
| `category`                        | no         |
| `country`                         | no         |
| `region`                          | no         |
| `city`                            | no         |

#### Получить Объявления по фильтрам - new -------------------------------------------------------------------------------------------------------------

    [GET] /api/v1/filter/deals
    
| Field                             | Required   |
| --------------------------------- | ---------- |
| `type_deal`                       | no         | 'sell'|'buy'
| `subtype_deal`                    | no         | 'new'|'used'|'na' 
| `categories[]`                    | no         | id категории (передается массив)
| `cities[]`                        | no         | id города (передается массив)
| `date_created`                    | no         | 'ASC', 'DESC'   - сортировка по возрастанию даты создания объявления, и по убыванию
| `date_deadline`                   | no         | 'ASC', 'DESC'   - сортировка по возрастанию даты истечения объявления, и по убыванию
| `with_photo`                      | no         |  0|1 -     1 - показывать с фото
| `deal_name`                       | no         |  string -     ищет строку в заголовке объявления
| `budget_from`                     | no         |  минимальная цена в объявлении
| `budget_to`                       | no         |  максимальная цена в объявлении
| `city`                            | no         |  id города -  для упрощенного поиска по объявлению
| `finish`                          | no         |  0|1  -    1- показывать завершщенные сделки
| `user_id`                         | no         |  сделки пользователя с соотв. id
| `region`                          | no         |  id региона -  для упрощенного поиска по объявлению


### Формы организации

    [GET] /api/v1/legalforms
    
### Организация

#### Информация об организации по ее ID - new --------------------------------------------------------------------

    [GET] /api/v1/organization/:id/info
    
#### Новости организации

    [GET] /api/v1/organization/:id/news
    
#### Оценки организации и отзывы

    [GET] /api/v1/organization/:id/rating
    
#### Организация - управление

Данные запросы требуют наличия авторизации (см. раздел авторизации).

#### Удалить изображение из организации по его пути - new -------------------------------------------------

Удалить изображение  его пути path.  Проверяется, если это изображение не этот юзер размещал, то не удаляем

    [Authorized]
    [POST] /api/v1/organization/manage/deleteimage

    | Field                             | Required   |
    | --------------------------------- | ---------- |
    | `path`                            | yes        |

#### Загрузить изображение для организации  - new -------------------------------------------------

    [Authorized]
    [POST] /api/v1/organization/manage/storeimage

    | Field                             | Required   |
    | --------------------------------- | ---------- |
    | `logo`                            | no         |
    | `image_1`                         | no         |
    | `image_2`                         | no         |
    | `image_3`                         | no         |

    Одно из полей должно быть в запросе обязательно, оно же и единственное

#### Данные организации  - new --------------------------------------------------------------------

    [Authorized]
    [GET] /api/v1/organization/manage/info


#### Создание организации  - new -------------------------------------------------------------------- 

    [Authorized]
    [POST] /api/v1/organization/manage/store

    | Field                             | Req |
    | --------------------------------- | --- |
    | `first_name`                      | n   |
    | `second_name`                     | n   |
    | `middle_name`                     | n   |
    | `phone`                           | n   |
    | `org_city_id`                     | y   |
    | `org_name`                        | n   |
    | `org_inn`                         | y   |
    | `org_kpp`                         | y   | 
    | `org_description`                 | n   |
    | `org_products`                    | n   |  краткое описание ,чем занимается компания 5000 симв
    | `video`                           | n   |
    | `logo`                            | n   |
    | `image_1`                         | n   |
    | `image_2`                         | n   |
    | `image_3`                         | n   |
    | `org_address`                     | n   |
    | `org_address_legal`               | n   |
    | `org_legal_form_id`               | y   |
    | `org_director`                    | n   |
    | `org_site`                        | n   |
    | `org_work_time`                   | n   |
    | `categories`[]                    | Y   | arrary [category_ids]
    | `offices`[]                       | Y   | arrary [JSON]
    | `stores`[]                        | Y   | arrary [JSON]
    | `org_manager_post`                | y   |
    | `org_okved`                       | y   |
    | `org_ogrn`                        | y   |
    | `org_is_active`                   | y   |
    | `org_registration_date`           | y   |

`categories`[] = [1,45,67]
`offices`[] = {"address" : "Брянск, Орловская 11", "phone": "5639429", "geo_lat" : null ,  "geo_lon" : null}
`stores` [] = {"address" : "Брянск, Орловская 10", "geo_lat" : null ,  "geo_lon" : null}


#### Редактирование данных организации  - new -------------------------------------------------------------------- 

    [Authorized]
    [POST] /api/v1/organization/manage/update

    | Field                             | Req |
    | --------------------------------- | --- |
    | `first_name`                      | y |
    | `second_name`                     | y |
    | `middle_name`                     | y |
    | `phone`                           | n |
    | `org_city_id`                     | y |
    | `org_name`                        | n |
    | `org_inn`                         | n |
    | `org_kpp`                         | y | 
    | `org_description`                 | n |
    | `org_products`                    | n |  краткое описание ,чем занимается компания 5000 симв
    | `video`                           | n |
    | `logo`                            | n |
    | `image_1`                         | n |
    | `image_2`                         | n |
    | `image_3`                         | n |
    | `org_address`                     | y |
    | `org_address_legal`               | y |
    | `org_legal_form_id`               | y |
    | `org_director`                    | n |
    | `org_site`                        | n |
    | `org_work_time`                   | n |
    | `categories`[]                    | Y | arrary [category_ids]
    | `org_manager_post`                | n |
    | `org_okved`                       | n |
    | `org_ogrn`                        | n |
    | `org_is_active`                   | n |
    | `org_registration_date`           | n |

#### Обновление списка категорий организации

    [Authorized]
    [POST] /api/v1/organization/manage/categories
    
| Field                             | Required   |
| --------------------------------- | ---------- |
| `categories`                      | yes        |


### Пользователи и разрешения

#### Получение списка доступных прав на действия в системе

    [Authorized]
    [GET] /api/v1/organization/manage/permissions
    
#### Получение списка дополнительных аккаунтов

    [Authorized]
    [GET] /api/v1/organization/manage/users
    
#### Создание дополнительного аккаунта

    [Authorized]
    [POST] /api/v1/organization/manage/users/store
    
| Field                             | Required   |
| --------------------------------- | ---------- |
| `user[name]`                      | yes        |
| `user[email]`                     | yes        |
| `user[password]`                  | yes        |
| `permissions[]`                   | yes        |

#### Редактирование дополнительного аккаунта

    [Authorized]
    [PATCH] /api/v1/organization/manage/users/:user

| Field                             | Required   |
| --------------------------------- | ---------- |
| `user[name]`                      | yes        |
| `user[email]`                     | yes        |
| `user[password]`                  | yes        |
| `permissions[]`                   | yes        |

#### Удаление дополнительного аккаунта

    [Authorized]
    [DELETE] /api/v1/organization/manage/users/:user
    
### Склады офисы

#### Список складов  - new -------------------------------------------------------

    [Authorized]
    [GET] /api/v1/organization/manage/stores
    
#### Добавление склада   - new -------------------------------------------------------

    [Authorized]
    [POST] /api/v1/organization/manage/stores/store
    
| Field                             | Required   |
| --------------------------------- | ---------- |
| `address`                         | yes        |
| `geo_lat`                         | no         |
| `geo_lon`                         | no         |

#### Удаление склада по его id  - new -------------------------------------------------------

    [Authorized]
    [DELETE] /api/v1/organization/manage/stores/delete/:store
    
#### Список офисов - new -------------------------------------------------------

    [Authorized]
    [GET] /api/v1/organization/manage/offices
    
#### Добавление офиса  - new -------------------------------------------------------

    [Authorized]
    [POST] /api/v1/organization/manage/offices/store
    
| Field                             | Required   |
| --------------------------------- | ---------- |
| `address`                         | yes        |
| `phone`                           | no         |
| `email`                           | no         |
| `geo_lat`                         | no         |
| `geo_lon`                         | no         |

#### Редактирование офиса  - new -------------------------------------------------------

    [Authorized]
    [PATCH] /api/v1/organization/manage/offices/update/:office
    
| Field                             | Required   |
| --------------------------------- | ---------- |
| `phone`                           | no         |
| `email`                           | no         |
| `address`                         | yes        |
| `geo_lat`                         | yes        |
| `geo_lon`                         | yes        |

#### Удаление офиса   - new -------------------------------------------------------

    [Authorized]
    [DELETE] /api/v1/organization/manage/offices/delete/:office
    
### Список акций

    [Authorized]
    [GET] /api/v1/organization/manage/stocks

### Новости    

#### Список новостей

    [Authorized]
    [GET] /api/v1/organization/manage/news    
   
#### Создание новости

    [Authorized]
    [POST] /api/v1/organization/manage/news/store
    
| Field                             | Required   |
| --------------------------------- | ---------- |
| `title`                           | yes        |
| `url`                             | yes        |
| `description`                     | yes        |
| `categories`                      | yes        |
| `image_1`                         | no         |
| `image_2`                         | no         |
| `image_3`                         | no         |
| `stock`                           | no         |

#### Редактирование новости

    [Authorized]
    [PATCH] /api/v1/organization/manage/news/update/:news
    
| Field                             | Required   |
| --------------------------------- | ---------- |
| `title`                           | yes        |
| `url`                             | yes        |
| `description`                     | yes        |
| `categories`                      | yes        |
| `image_1`                         | no         |
| `image_2`                         | no         |
| `image_3`                         | no         |
| `stock`                           | no         |

#### Удаление новости

    [Authorized]
    [DELETE] /api/v1/organization/manage/news/delete/:news


### Уведомления

#### Список уведомлений

    [Authorized]
    [GET] /api/v1/organization/manage/notifications

### Избранное

#### Избранное - организации

    [Authorized]
    [GET] /api/v1/organization/favorites
        
#### Избранное - добавление организации

    [Authorized]
    [POST] /api/v1/organization/favorites/add/:organization        
    
#### Избранное - удаление организации

    [Authorized]
    [DELETE] /api/v1/organization/favorites/delete/:organization
    
#### Избранное - выдать избранные пользователем сделки из его избранного- new -------------------------------------------------------

    [Authorized]
    [GET] /api/v1/user/favourites
        
#### Избранное - добавление сделки в избранное пользователя- new --------------------------------------------

    [Authorized]
    [POST] /api/v1/user/favourites/store        

    | Field                             | Required   |
    | --------------------------------- | ---------- |
    | `deal_id`                         | yes        |
    
#### Избранное - удаление сделки из избранного - new -------------------------------------------------------

    [Authorized]
    [POST] /api/v1/user/favourites/delete

    | Field                             | Required   |
    | --------------------------------- | ---------- |
    | `deal_id`                         | yes        |
    
### Заявки

#### Список шаблонов вопросов

    [GET] /api/v1/deals/questions
    
#### Список заявок - new -------------------------------------------------------


    [Authorized]
    [GET] /api/v1/deals/list

    | Field                             | Required   |
    | --------------------------------- | ---------- |
    | `categories`                      | no         |
    | `countries`                       | no         |
    | `regions`                         | no         |
    | `cities`                          | no         |
    | `finish`                          | no         |
    | `type_deal`                       | no         | 'sell'|'buy'
    | `subtype_deal`                    | no         | 'new'|'used'|'na'
    | `user_id`                         | no         | получить заявки юзера по его Id

    если finish = 1  - то показ только продаж удаленных, иначе только не удаленных
    
#### Список активных заявок текущего пользователя - new -------------------------------------------------------

    [Authorized]
    [GET] /api/v1/deals/user/list?page=1&status=active&type_deal={sell|buy}

    | Field                             | Required   |
    | --------------------------------- | ---------- |
    | `type_deal`                       | yes        | 'sell'|'buy'
    | `page`                            | no         |  номер страницы пагинации

#### Список архивных заявок текущего пользователя - new --------------------------------------

    [Authorized]
    [GET] /api/v1/deals/user/list?page=1&status=archive&finish=1&type_deal={sell|buy}

    | Field                             | Required   |
    | --------------------------------- | ---------- |
    | `type_deal`                       | yes        | 'sell'|'buy'
    | `page`                            | no         |  номер страницы пагинации

#### Список заявок на модерации текущего пользователя - new ---------------------------------

    [Authorized]
    [GET] /api/v1/deals/user/list?page=1&status=moderation&type_deal={sell|buy}

    | Field                             | Required   |
    | --------------------------------- | ---------- |
    | `type_deal`                       | yes        | 'sell'|'buy'
    | `page`                            | no         |  номер страницы пагинации

#### Список заявок не прошедших модерацию для текущего пользователя - new -------------------

    [Authorized]
    [GET] /api/v1/deals/user/list?page=1&status=banned&finish=1&type_deal={sell|buy}

    | Field                             | Required   |
    | --------------------------------- | ---------- |
    | `type_deal`                       | yes        | 'sell'|'buy'
    | `page`                            | no         |  номер страницы пагинации

#### Удалить изображение из Заявки - new -------------------------------------------------

Удалить изображение из обюъявления пор его id.  Проверяется, если это изображение не этот юзер размещал, то не удаляем

    [Authorized]
    [POST] /api/v1/deals/deleteimage

    | Field                             | Required   |
    | --------------------------------- | ---------- |
    | `id`                              | yes        |

    
#### Информация о Заявке  - new -------------------------------------------------------

    [GET] /api/v1/deals/:id
    
### Получить заявку по ее url   - new -------------------------------------------------------

    [Authorized]
    [GET] /admin/api/deals/url/:url

### Привязать пользователя (организацию) к ключевым словам  - new -------------------------------------------------------

    [Authorized]
    [POST] /admin/api/deals/tags/subscribe
    
    Посылаемые поля:

| Field                                | Req |                                                                                                                         
| ------------------------------------ | --- | --------------------------------------------------------
| `organization_id`                    | y   | id организации (привязано к юзеру и его сделкам)
| `tags`                               | y   | строка тэгов, разделенная запятыми

Возвращает в data - строку тэгов, которых нет в БД, разделенную запятыми
{
    "result": true,
    "data": "кефирный йогурт дядя ваня, гвозди, доски"
}

#### Создание Заявки на покупку - new --------------------------------------------------------------

    [Authorized]
    [POST] /api/v1/deals/store
    
Посылаемые поля:

| Field                                | Req |                                                                                                                         
| ------------------------------------ | --- | ----------------------------------------------------------------------------------------------------------------------
| `name`                               | y   | название сделки
| `categories` []                      | y   | категории (надо проверить, чтобы была второго уровня!!!)
| `cities`  []                         | y   | 
| `typeDeal` = 'buy'                   | y   | Тип сделки покупка = 'buy'
| `tags`                               | n   | Строка тегов
| `description`                        | y   | 
| `unit_for_all`                       | y   |  единица измерения за полный объем товара (кг, литр) text 
| `budget_from`                        | y   |  бюджет ОТ 
| `budget_to`                          | y   |  бюджет ДО 
| `unit_for_unit`                      | y   |  единицы измерения для товара (литры, метры и т.п.) text
| `budget_with_nds`                    | y   |  бюджет c НДС или без него (1 | 0) 
| `deadline_deal`                      | y   |  дата окончания обявления 'Y-m-d'
| `date_of_event`                      | n   |  дата проведения сделки - просто текст (например сентябрь-октябрь 2020)
| `questions[dqh_volume]`              | y   |  объем
| `questions[dqh_specification]`       | n   |  Спецификация
| `questions[dqh_doc_confirm_quality]` | n   |  Документы подтверждающие качество
| `questions[dqh_logistics]`           | y   |  Логистика
| `questions[dqh_type_deal]`           | n   |  Тип сделки
| `questions[dqh_payment_term]`        | y   |   Условия оплаты
| `questions[dqh_payment_method]`      | y   |   Cпособ оплаты
| `questions[dqh_min_party]`           | y   |   Минимальная партия
| `file`                               | n   |  загружаемый файл для сделки
| `images`  []                         | n   |  картинки джля сделки


#### Создание Заявки на продажу SELL - new --------------------------------------------------------------

    [Authorized]
    [POST] /api/v1/deals/store
    
Посылаемые поля:

| Field                                | Req |                                                                                                                         
| ------------------------------------ | --- | ----------------------------------------------------------------------------------------------------------------------
| `name`                               | y   | название сделки  < 191 симв.
| `categories` []                      | y   | категории (надо проверить, чтобы была второго уровня!!!)
| `cities`  []                         | y   | 
| `typeDeal` = 'buy'                   | y   | Тип сделки покупка = 'buy'
| `description`                        | y   | < 3000 симв.
| `tags`                               | n   | Строка тегов.  < 500 симв.
| `files`  []                          | n   |  файлы архивов, документов и картинок, числом не боле 3х и размер <5 мб


#### Редактирование Заявки на покупку BUY - new --------------------------------------------------------------

    [Authorized]
    [POST] /api/v1/deals/update/:id
    
| Field                             | Req |
| --------------------------------- | --- |
| `type_deal`                       | y   | 'sell'|'buy'
| `subtype_deal`                    | y   | 'new'|'used'|'na'
| `name`                            | y   |
| `description`                     | n   |
| `budget_to`                       | y   | if type_deal = 'sell'
| `is_need_kp`                      | n   | if type_deal = 'sell'
| `deadline_deal`                   | n   |
| `categories` []                   | y   |
| `cities`  []                      | y   |
| `images`  []                      | n   |
| `date_of_event`                   | n   | дата проведения сделки - просто текст (например сентябрь-октябрь 2020)
| `unit_for_unit`                   | n   | единицы измерения для товара (литры, метры и т.п.) text
| `file`                            | n   | загружаемый файл для сделки
| `budget_from`                     | y   | бюджет ОТ ( if type_deal = 'buy')
| `budget_to`                       | y   | бюджет ДО ( if type_deal = 'buy')

#### Удаление (Завершение) Заявки (отметить, как удаленную) - new --------------------------------------

    [Authorized]
    [POST] /api/v1/deals/:deal/finish

    по сути, проставляет:
         organizations_deals.finish = 1 
         organizations_deals.status = 'archive'
         organizations_deals.finished_at = now()
    и некоторые поля для откликов, если сделка по "куплю"

#### Архив заявок. Список заявок залогиненного пользователя из архива - new ----------------------------

    [Authorized]
    [GET] /api/v1/deals/deleteddealslist

    ! Если пользователь - администратор, то выдает список всех архивных заявок, иначе, только своих.


#### Архив заявок. Вернуть заявку из архива в строй по ее id (она уходит опять на модерацию) - new ------

    [Authorized]
    [GET] /api/v1/deals/restoredeal/:id

     ! Если пользователь - администратор, то дает вернуть любую заявку, иначе - только свою

     по сути, проставляет:
         organizations_deals.finish = 0 
         organizations_deals.status = 'moderation'
         organizations_deals.finished_at = null

#### Заявка на участие в сделке  - new ----------------------------------------

    [Authorized]
    [POST] /api/v1/deals/:deal/take

 Посылаемые поля:
| Field                                | Req | Type deal    |
| ------------------------------------ | --- | ------------ |
| `price_offer`                        | n   | 'buy'        | ценовое предложение
| `notice`                             | n   | 'buy'        | коментарии для заказчика
| `is_shipping_included`               | n   | 'buy'        | 0|1 (чекбокс) - включена ли стоимость доставки в ценовое предложение
| `questions[dqh_volume]`              | y   | 'buy'        | 0|1 (чекбокс) - объем
| `questions[dqh_specification]`       | y   | 'buy'        | 0|1 (чекбокс) - Спецификация
| `questions[dqh_doc_confirm_quality]` | y   | 'buy'        | 0|1 (чекбокс) - Документы подтверждающие качество
| `questions[dqh_logistics]`           | y   | 'buy'        | 0|1 (чекбокс) - Логистика
| `questions[dqh_type_deal]`           | y   | 'buy'        | 0|1 (чекбокс) - Тип сделки
| `questions[dqh_payment_term]`        | y   | 'buy'        | 0|1 (чекбокс) -  Условия оплаты
| `questions[dqh_payment_method]`      | y   | 'buy'        | 0|1 (чекбокс) -  Cпособ оплаты
| `questions[dqh_min_party]`           | n   | 'buy'        | 0|1 (чекбокс) -  Минимальная партия   
| `images[]`                           | n   | 'buy'        | файлы изображений (не более 3х)


#### Выбор победителя в Заявке - new ----------------------------------------

    [Authorized]
    [POST] /api/v1/deals/:dealId/winner
    
 Посылаемые поля:
| Field                                | Req | Type deal    |
| ------------------------------------ | --- | ------------ |
| `winner_id`                          | y   | 'buy'        | = одному из members.id   из запроса  /api/v1/deals/:dealId


### Отклики

#### Список откликов для продавца - new ----------------------------------------

    [Authorized]
    [GET] /api/v1/deals/user/responses

    Пример положительного ответа :
    {
        "result": true,
        "data": {
            "count": 2,
            "hasMore": false,
            "items": [
                {
                    "id": 37,
                    "trading_status": "moderation",
                    "price_offer": 0,
                    "is_shipping_included": 1,
                    "notice": "",
                    "files": [
                        {
                            id: 3,
                            user_id: 17,
                            name: "1.jpg",
                            path: "/uploads/users/17/responses/37/files/tz3nvEr0R.jpeg"
                        },
                        {
                            ...
                        }
                    ] ,
                    "date_create": {
                        "date": "2019-08-26 16:08:29.000000",
                        "timezone_type": 3,
                        "timezone": "UTC"
                    },
                    "date_update": {
                        "date": "2019-08-27 08:07:39.000000",
                        "timezone_type": 3,
                        "timezone": "UTC"
                    },
                    "deal": {
                        "id": 30,
                        "name": "Продаю  dopoln",
                        "is_payed": false,
                        "type_deal": "buy",
                        "date_of_event": "дата сделки",
                        "budget_from": 1.23,
                        "budget_to": 121212.2,
                        "date_published": {
                            "date": "2019-08-09 09:21:46.000000",
                            "timezone_type": 3,
                            "timezone": "UTC"
                        },
                        "questions": {
                            "dqh_specification": {
                                "id": 3,
                                ..........

Возвращаемые поля (не учитывая полей пагинации):
| Field                                 | Description                                                                                                          |
| --------------------------------------| -------------------------------------------------------------------------------------------------------------------- |
| `id`                                  | id отклика                                                                                                           |
| `trading_status`                      | статус отклика                                                                                                       |
| `price_offer`                         | ценовое предложение                                                                                                  |
| `is_shipping_included`                | 1|0  стоимость с доставкой или без                                                                                   |                   
| `notice`                              | дополниельная тектовая информация от поставщика                                                                      |                   
| `files[]`                             | файлы, прикрепленные к отклику                                                                                       |                   
| `deal[id]`                            | id сделки                                                                                                            |                   
| `deal[name]`                          | название сделки                                                                                                      |                   
| `deal[is_payed]`                      | true|false - оплачены ли контакты или нет                                                                            |                   
| `deal[date_of_event]`                 | период сделки                                                                                                        |                   
| `deal[budget_from]`                   | бюджет сделки ОТ                                                                                                     |                   
| `deal[budget_to]`                     | бюджет сделки ДО                                                                                                     |                   
| `deal[date_published]`                | дата публикации сделки                                                                                               |                   
| --------------------------------------| -------------------------------------------------------------------------------------------------------------------- |                   
| `deal[questions]`                     | (массив) , вопросы по сделке - массив                                                                                |                   
| `deal[questions][slug]`               | (массив) , slug - это ключ  = слагу вопроса                                                                          |                   
| `deal[questions][slug][question]`     | содержимое вопроса                                                                                                   |                   
| --------------------------------------| -------------------------------------------------------------------------------------------------------------------- |
| `deal[organization]`                  | (массив)  ,  данные по организации, разместившего заявку. !!! Если контакты не оплачены, то массив не приходит       |
| `deal[organization][name]`            | название организзации                                                                                                |
| --------------------------------------| -------------------------------------------------------------------------------------------------------------------- |
| `deal[organization]`                  | (массив)  ,  данные по пользователю, разместившему заявку. !!! Если контакты не оплачены, то массив не приходит      |
| `deal[organization][name]`            | имя пользователя, разместившего заявку                                                                               |   
| `deal[organization][photo]`           | фото пользователя, разместившего заявку                                                                              |   
| `deal[organization][email]`           | почта пользователя, разместившего заявку                                                                             |   
| `deal[organization][phone]`           | телефон пользователя, разместившего заявку                                                                           |       

#### Выйти из своего отклика - new --------------------------------------

    [Authorized]
    [POST] /api/v1/deals/user/responses/:id/finish

   id - это id своего отклика

#### Не прочитанные отклики пользователя - new --------------------------------------

    [Authorized]
    [GET] /api/v1/user/countunreaded

При удаче возвращает JSON:

{
    "result": true,
    "data": {
        "unreaded_owner_response": 0,
        "unreaded_owner_deal": 1
    }
}

**unreaded_owner_response** - кол-во не прочитанных своих откликов (ЛК\Мои отклики)
**unreaded_owner_deal** - кол-во не прочитанных откликов хозяина заявки


#### Пометить отклик прочитанным - new --------------------------------------

    [Authorized]
    [POST] /api/v1/user/markreaded

| Field                             | Req |
| --------------------------------- | --- |
| `id`                              | y   | id отклика
| `command`                         | y   | название поля, для которого ставим  прочитано (is_readed_owner_response is_readed_owner_deal)

`command` значения:
**is_readed_owner_response** - свой отклик (ЛК\Мои отклики)
**is_readed_owner_deal** - отклик хозяина заявки



### Диалоги

Диалоги частично реализованы на Websocket, и имеют следующие события:
# - Глобальная подписка на новые сообщения `dialog.organization.:id`
- Подписка на новые сообщения в рамках диалога `dialog.:id`

#### Список всех диалогов текущего юзера - new ----------------------------------------

    [Authorized]
    [GET] /api/v1/dialogs
    
#### Новый диалог по заявке с id  =  deal_id - new -------------------------------------

    [Authorized]
    [POST] /api/v1/dialogs/new

| Field                             | Required   |
| --------------------------------- | ---------- |
| `deal_id`                         | yes        |

#### Список сообщений в диалоге - new ----------------------------------------------------

    [Authorized]
    [GET] /api/v1/dialogs/:dialogId
    
#### Новое сообщение в диалог - new -----------------------------------------------------

    [Authorized]
    [POST] /api/v1/dialogs/:dialog/send
    
| Field                             | Required   |
| --------------------------------- | ---------- |
| `message`                         | yes        |
| `type`                            | no         | если отклик, то type = 'responce' (варианты: 'responce' - отклик    'message' - обычное сообщение)
| `budget_to`                       | no         | поле бюджет предлагаемой сделки
| `files[]`                         | no         | 
    
#### Удалить диалог - new --------------------------------------------------------------

    [Authorized]
    [DELETE] /api/v1/dialogs/:dialogId

#### Количество непрочитанных сообщений во всех диалогах юзера - new -------------------

    [Authorized]
    [GET] /api/v1/dialogs/messages/countunreaded  

#### Пометить сообщение прочитанным, по его id - new -----------------------------------

    [Authorized]
    [POST] /api/v1/dialogs/messages/markreaded
    
| Field                             | Required   |
| --------------------------------- | ---------- |
| `id`                              | yes        |

### Обратная связь

#### Новое обращение

    [Authorized]
    [POST] /api/v1/feedback/send
    
| Field                             | Required   |
| --------------------------------- | ---------- |
| `theme`                           | yes        |
| `description`                     | yes        |

    [POST] /api/v1/feedback/send
    
| Field                             | Required   |
| --------------------------------- | ---------- |
| `email`                           | yes        |
| `description`                     | yes        |

#### Новая жалоба

    [Authorized]
    [POST] /api/v1/feedback/report
    
| Field                             | Required   |
| --------------------------------- | ---------- |
| `theme`                           | yes        |
| `description`                     | yes        |
| `org_id`                          | no         |
| `news_id`                         | no         |
| `deal_id`                         | no         |
| `message_id`                      | no         |


### Платные услуги и сервисы  - new --------------------------------------------------------------

#### Получить список всех имеющихся платных услуг (для администратора)- new ---------------------------------

    [Authorized]
    [GET] /api/v1/paymentservice/subscriptions

#### Получить список всех доступных услуг (для пользователя)- new -----------------------------------------

    [Authorized]
    [GET] /api/v1/paymentservice/servicesavailableforuser

#### Получить список всех доступных  услуг (для не авторизованного пользователя)- new ----------------------

    [GET] /api/v1/paymentservice/servicesavailableforall

#### Получить список всех доступных тарифов (для пользователя)- new ----------------------------------------

    [Authorized]
    [GET] /api/v1/paymentservice/tariffsavailableforuser

#### Получить список всех доступных тарифов (для  не авторизованного пользователя)- new --------------------

    [GET] /api/v1/paymentservice/tariffsavailableforall

#### Получить описание платной услуги по ее ID - new -----------------------------------------

    [Authorized]
    [GET] /api/v1/paymentservice/subscriptions/:id

#### Создать новую платную услугу - new ------------------------------------------------------

    [Authorized]
    [POST] /api/v1/paymentservice/subscriptions 

| Field                             | Required   |
| --------------------------------- | ---------- |
| `name`                            | yes        | название услуги
| `slug`                            | yes        | краткое имя услуги (уникальный ЧП идентификатор), напр. "payment_all_in_3_days"
| `description`                     | no         | более полное описание услуги
| `started_at`                      | no         | дата старта услуги 
| `duration_days`                   | no         | продолжительность действия услуги, в днях, по умолчанию : 0 - бессрочно
| `status`                          | no         | статус ('pause', 'finished', 'active'), по умолчанию : 'pause'
| `cost`                            | no         | стоимость услуги, по умолчанию : 0 - акция или подарок
| `type`                            | no         | тип (это тариф или сервис)
| `parent_id`                       | no         | id родительской услуги : null.   Чтобы сделать пакет или сделать отношения. например есть проаккаунт, и сделать про аккаунт , но на 3 дня

#### Редактировать платную услугу по ее id - new ------------------------------------------------------

    [Authorized]
    [POST] /api/v1/paymentservice/subscriptions/:id

| Field                             | Required   |
| --------------------------------- | ---------- |
| `name`                            | no         | название услуги
| `slug`                            | no         | краткое имя услуги (уникальный ЧП идентификатор), напр. "payment_all_in_3_days"
| `description`                     | no         | более полное описание услуги
| `started_at`                      | no         | дата старта услуги 
| `duration_days`                   | no         | продолжительность действия услуги, в днях, по умолчанию : 0 - бессрочно
| `status`                          | no         | статус ('pause', 'finished', 'active'), по умолчанию : 'pause'
| `cost`                            | no         | стоимость услуги, по умолчанию : 0 - акция или подарок
| `type`                            | no         | тип (это тариф или сервис)
| `parent_id`                       | no         | id родительской услуги : null.   Чтобы сделать пакет или сделать отношения. например есть проаккаунт, и сделать про аккаунт , но на 3 дня

#### Удалить платную услугу по ее id - new ------------------------------------------------------

    [Authorized]
    [DELETE] /api/v1/paymentservice/subscriptions/:id

#### Активировать платную услугу по ее slug (typeservice) - new ------------------------------------------------------

    [Authorized]
    [POST] /api/v1/paymentservice/subscriptions/:typeservice/activate

typeservice - имя-слаг услуги из БД

#### Купить тариф по его slug (typeservice) - new ------------------------------------------------------

    [Authorized]
    [POST] /api/v1/paymentservice/subscriptions/:typeservice/payment

typeservice - имя-слаг тарифа из БД

| Field                             | Required   |
| --------------------------------- | ---------- |
| `deal_id`                         | no         | id объявления о куплю - если покупаем услугу на просмотр контактов по объявлению

#### Получить описание возожности получения платной услуги по ее slug (typeservice) - new -----------------------------------------

Данный запрос выполняется с фронта перед посылкой на покупку или подписку на сервис, как проверка возможности подписки вообще и предупреждение о платности и стоимости

    [Authorized]
    [GET] /api/v1/paymentservice/:typeservice

  typeservice - имя-слаг услуги из БД  

  Например: /api/v1/paymentservice/payment_all_in_3_days   , вернет

    "result": true,
    "data": {
        "is_pro_account": true,
        "ballance": 370,
        "is_possible": true,
        "message": "Для вас возможно все! У вас подписка на сервис: Pro аккаунт в подарок на 3 дня",
        "cost_of_service": 0,
        "started_at": "2019-05-31 10:45:14"
    }

    is_pro_account - если действует Pro аккаунт, т.е. юзеру доступны все возможности сайта
    ballance - кол-во денег на счету пользователя
    is_possible - есть ли в принципе возможность у юзера получить данный сервис (если false, то либо не хватает денег. либо какая-то другая причина)
    message - сообщение для пользователя
    cost_of_service - стоимость сервиса для покупки (если 0 , то бесплатно или у юзера Pro аккаунт)
    started_at - дата активации сервиса у юзера

#### Получить описание платных услуг по фильтрам - new ------------------------------------------------------

    [Authorized]
    [GET] /api/v1/paymentservice/subscriptions/filter/filters

| Field                             | Required   |
| --------------------------------- | ---------- |
| `name`                            | no         | название услуги
| `slug`                            | no         | краткое имя услуги (уникальный ЧП идентификатор), напр. "payment_all_in_3_days"
| `started_at`                      | no         | дата старта услуги 
| `duration_days`                   | no         | продолжительность действия услуги, в днях
| `status`                          | no         | статус ('pause', 'finished', 'active')
| `cost`                            | no         | стоимость услуги
| `parent_id`                       | no         | id родительской услуги



### Оплата картой через платежную систему PAYTURE и финансовые операции- new -----------------

#### Посыка запроса на оплату картой в плетежную систему new -------------------------

    [Authorized]
    [POST] /api/v1/finance-operations/payture/store

| Field                             | Required   |
| --------------------------------- | ---------- |
| `amount`                          | y          | сумма в рублях
| `deal_id`                         | n          | id сделки , идет обзательно в паре с полем slug
| `slug`                            | n          | slug , идет обзательно в паре с полем deal_id

В случае ошибки - выводить ее пользователю

В случае успеха, запрос должен редиректить юзера на страницу ввода данных карты (см. Роут, на который редиктится Payture после оплаты), 
и если все нормально,то редиректить обратно к нам в /lk/wallet.
ДЛЯ ФРОНТА!!!! => если какая-то ошибка на стадии проверки платежа, то редиректит на "/lk/wallet?error=MESSAGE", MESSAGE - показать
пользователю

!!! Если хотим оплатить сразу показ контактов сделки, то надо передавать сразу два поля deal_id и slug = 'payment_once_deal_buy_get_contacts'

#### Роут, на который редиктится Payture после оплаты new -------------------------

    [GET] /api/v1/finance-operations/payture/store

В случае успеха, запрос должен редиректить юзера на страницу ввода данных карты, и если все нормально,то
редиректить обратно к нам в /lk/wallet.
ДЛЯ ФРОНТА!!!! => если какая-то ошибка на стадии проверки платежа, то редиректит на "/lk/wallet?error=MESSAGE", MESSAGE - показать
пользователю

#### Получить пагинированный список финансовых операций new -------------------------

    [Authorized]
    [POST] /api/v1/finance-operations

*Если обычный пользователь - полуает все только свои и может фильтровать*

| Field filters current User        | Req  | Type   |
| --------------------------------- | -----|--------|
| `payment_system`                  | n    |  int   |
| `status`                          | n    |  int   |
| `date_from `                      | n    | string | format:  'Y-m-d'   Работает только вместе с date_to
| `date_to`                         | n    |  int   | format:  'Y-m-d'   Работает только вместе с date_from


*Если Администратор - без фильтров получает все операции и может фильтровать*

| Field filters Administrator       | Req  | Type   |
| --------------------------------- | -----|--------|
| `text`                            | n    | string |
| `payment_id`                      | n    | string |
| `user_id`                         | n    |  int   |
| `payment_system`                  | n    |  int   |
| `status`                          | n    |  int   |
| `manager_id`                      | n    |  int   |
| `date_from `                      | n    | string | format:  'Y-m-d'   Работает только вместе с date_to
| `date_to`                         | n    |  int   | format:  'Y-m-d'   Работает только вместе с date_from

#### Получить финансовую операцию по ее id  new -------------------------

    [Authorized]
    [GET] /api/v1/finance-operations/{:id}

#### Создать финансовую операцию  new -------------------------

    [Authorized]
    [POST] /api/v1/finance-operations/store

| Field filters                     | Req  | Type   |
| --------------------------------- | -----|--------|
| `text`                            | y    | string |
| `payment_id`                      | y    | string |
| `user_id`                         | n    |  int   | задается ТОЛЬКО!!! менеджером, если не задано. то создал юзер и сюда автоматом прописывается текущий
| `payment_system`                  | y    |  int   |
| `status`                          | y    |  int   |
| `manager_id`                      | n    |  int   | задается менеджером и одновременно задается user_id


#### Редактировать финансовую операцию по ее id new -------------------------

    [Authorized]
    [POST] /api/v1/finance-operations/{:id}

| Field filters                     | Req  | Type   |
| --------------------------------- | -----|--------|
| `text`                            | n    | string |
| `status`                          | y    |  int   |



### Процент, взимаемый с объема сделки - new --------------------------------

#### Получить записи с процентами из таблицы  new -------------------------

    [Authorized]
    [GET] /admin/api/deals-procent

Возвращаемые поля :
| Field                         | Type      |
| ------------------------------| ----------|
| `id`                          | integer   |
| `budget_from`                 | decimal   | бюджет сделки ОТ
| `budget_to`                   | decimal   | бюджет сделки ДО
| `procent`                     | integer   | взимаемый процент, когда бюджет сделки попадает в диапазон от и до

#### Получить запись с процентами по ее id   new -------------------------

    [Authorized]
    [GET] /admin/api/deals-procent/:id

Возвращаемые поля:
| Field                         | Type      |
| ------------------------------| ----------|
| `id`                          | integer   |
| `budget_from`                 | decimal   | бюджет сделки ОТ
| `budget_to`                   | decimal   | бюджет сделки ДО
| `procent`                     | integer   | взимаемый процент, когда бюджет сделки попадает в диапазон от и до

#### Создать запись с процентами  new -------------------------

    [Authorized]
    [POST] /admin/api/deals-procent/store

Посылаемые поля:
| Field                         | Required   | Type      |
| ------------------------------| ---------- | ----------|
| `budget_from`                 |  y         | decimal   | бюджет сделки ОТ
| `budget_to`                   |  y         | decimal   | бюджет сделки ДО
| `procent`                     |  y         | integer   | взимаемый процент, когда бюджет сделки попадает в диапазон от и до

#### Редактировать запись с процентами по ее id new -------------------------

    [Authorized]
    [POST] /admin/api/deals-procent/:id

Посылаемые поля:
| Field                         | Required   | Type      |
| ------------------------------| ---------- | ----------|
| `budget_from`                 |  y         | decimal   | бюджет сделки ОТ
| `budget_to`                   |  y         | decimal   | бюджет сделки ДО
| `procent`                     |  y         | integer   | взимаемый процент, когда бюджет сделки попадает в диапазон от и до

#### Удалить запись с процентами по ее id new -------------------------

    [Authorized]
    [DELETE] /admin/api/deals-procent/:id


### Работа с файлами, для определенного объявления (даже, если оно не свое) для откликов - new --------------------------------

#### Получить информацию о загруженных файлах  new -------------------------

    [Authorized]
    [GET] /api/v1/deals-file

Доступные фильтры:
| Field filters                     | Req  | Type   |
| --------------------------------- | -----|--------|
| `user_id`                         | n    |integer |
| `deal_id`                         | n    |integer |

Возвращаемые поля:
| Field                         | Type      |
| ------------------------------| ----------|
| `id`                          | integer   |
| `deal_id`                     | integer   | id объявления
| `user_id`                     | integer   | id пользователя
| `file_name`                   | string    | имя загруженного файла
| `file_full_path`              | string    | полный путь для загрузки файла с сайта (без имени хоста)


#### Получить информацию о загруженном файле по его id   new -------------------------

    [Authorized]
    [GET] /api/v1/deals-file/:id

Возвращаемые поля:
| Field                         | Type      |
| ------------------------------| ----------|
| `id`                          | integer   |
| `deal_id`                     | integer   | id объявления
| `user_id`                     | integer   | id пользователя
| `file_name`                   | string    | имя загруженного файла
| `file_full_path`              | string    | полный путь для загрузки файла с сайта (без имени хоста)

#### Загрузить файл по определенному объявлению  new -------------------------

    [Authorized]
    [POST] /api/v1/deals-file/store

Посылаемые поля:
| Field                       | Required   | Type      |
| ----------------------------| ---------- | ----------|
| `deal_id`                   |  y         | integer   | id объявления
| `user_id`                   |  y         | integer   | id пользователя
| `file`                      |  y         | file      | загружаемый файл, одного из следующих типов: rtf, doc, docx, xls, xlsx, pdf


#### Удалить файл по еге id new -------------------------

    [Authorized]
    [DELETE] /api/v1/deals-file/:id

!!!! Если файл удаляется из админки, то ключ для авторизации в API брать из cookie name =  **api_token**


### Логирование - new --------------------------------

#### Записать в лог запросы и ответы (сейчас от dadata)  new -------------------------

    [Authorized]
    [POST] /api/v1/statistic/log/store

Посылаемые поля:
| Field filters                     | Req  | Type   |
| --------------------------------- | -----|--------|
| 'url'                             | n    |string  |
| 'method'                          | n    |string  |
| 'status'                          | n    |string  |
| 'data'                            | n    |string  |
| 'response'                        | n    |string  |


### Почта техподдержке - new --------------------------------

#### Письмо менеджерам, если не удалось получить данные по ИНН с dadata  new -------------------------

    [Authorized]
    [POST] /api/v1/send/support/innfailed

Посылаемые поля:
| Field filters                     | Req  |
| --------------------------------- | -----|
| 'inn'                             | y    |

#### Письмо менеджерам, если сделка не состоялась  new -------------------------

    [Authorized]
    [POST] /api/v1/send/support/dealfailed

Посылаемые поля:
| Field filters                     | Req  |
| --------------------------------- | -----|
| 'deal_id'                         | y    |
| 'comment'                         | y    |
| 'phone'                           | n    |


# Админка =============================================================================

## Клиенты - ------------------------------------------

### Создать клиента

    [Authorized]
    [POST] /admin/api/clients/store/user

    | Field                                   | Req |
    | --------------------------------------- | --- |
    | `user[email]`                           | y   |
    | `user[name]`                            | y   |
    | `user[phone]`                           | y   |
    | `organization[org_inn]`                 | y   |
    | `organization[org_kpp]`                 | n   |
    | `organization[org_name]`                | y   |
    | `organization[categories]`              | y   |
    | `organization[org_legal_form_id]`       | n   | 
    | `organization[org_director]`            | n   |
    | `organization[org_address_legal]`       | n   | 
    | `organization[phone]`                   | n   |
    | `organization[org_description]`         | n   |
    | `organization[org_registration_date]`   | n   | 


## Заголовки (главы), вопросов по откликам - ------------------------------------------

### Получить записи с заголовками по откликам

    [Authorized]
    [GET] /admin/api/deals/questions-headers

### Получить запись с заголовками по откликам по id

    [Authorized]
    [GET] /admin/api/deals/questions-headers/:id

Возвращаемые поля :
| Field                         | Type      |
| ------------------------------| ----------|
| `id`                          | integer   |
| `name`                        | string    | название заголовка (уникальное поле в таблице)
| `slug`                        | string    | слаг заголовка (уникальное поле в таблице)


### Создать запись с заголовком по откликам

    [Authorized]
    [POST] /admin/api/deals/questions-headers/store

Посылаемые поля:
| Field                         | Type      |Required  |
| ------------------------------| ----------|----------|
| `name`                        | string    | y        | название заголовка (уникальное поле в таблице)
| `slug`                        | string    | y        | слаг заголовка (уникальное поле в таблице)

### Редактировать запись с заголовком по откликам по ее id

    [Authorized]
    [POST] /admin/api/deals/questions-headers/:id

Посылаемые поля:
| Field                         | Type      |Required  |
| ------------------------------| ----------|----------|
| `name`                        | string    | n        | название заголовка (уникальное поле в таблице)
| `slug`                        | string    | n        | слаг заголовка (уникальное поле в таблице)

### Удалить запись с заголовком по откликам по ее id

    [Authorized]
    [DELETE] /admin/api/deals/questions-headers/:id




## Сделки  ------------------------------------------------------------------------

### Получить пагинированный список сделок

    [Authorized]
    [GET] /admin/api/deals

Посылаемые поля:
| Field                                | Req | Type deal    |
| ------------------------------------ | --- | ------------ |
| `type_deal`                          | n   | 'sell'|'buy' |  в частности, чтобы получить список с откликами

### Редактировать сделку на покупку BUY по ее id н

    [Authorized]
    [POST] /admin/api/deals/:id

Посылаемые поля:
            
| Field                                | Req |                                                                                                                         
| ------------------------------------ | --- | ----------------------------------------------------------------------------------------------------------------------
| `name`                               | y   | название сделки
| `categories` []                      | y   | категории (надо проверить, чтобы была второго уровня!!!)
| `cities`  []                         | y   | 
| `description`                        | y   | 
| `type_deal`                          | y   | = 'buy'
| `is_fake`                            | y   | 0|1 - фейковая сделка, для маркетинга      | 
| `unit_for_all`                       | y   |  единица измерения за полный объем товара (кг, литр) text 
| `budget_from`                        | y   |  бюджет ОТ 
| `budget_to`                          | y   |  бюджет ДО 
| `budget_all`                         | y   |  бюджет полный, за весь объем 
| `unit_for_unit`                      | y   |  единицы измерения для товара (литры, метры и т.п.) text
| `budget_with_nds`                    | y   |  бюджет c НДС или без него (1 | 0) 
| `deadline_deal`                      | y   |  дата окончания обявления 'Y-m-d'
| `date_of_event`                      | y   |  дата проведения сделки - просто текст (например сентябрь-октябрь 2020)
| `questions[dqh_volume]`              | y   |  объем
| `questions[dqh_specification]`       | y   |  Спецификация
| `questions[dqh_doc_confirm_quality]` | y   |  Документы подтверждающие качество
| `questions[dqh_logistics]`           | y   |  Логистика
| `questions[dqh_type_deal]`           | y   |  Тип сделки
| `questions[dqh_payment_term]`        | y   |   Условия оплаты
| `questions[dqh_payment_method]`      | y   |   Cпособ оплаты
| `questions[dqh_min_party]`           | y   |   Минимальная партия
| `count_unit_in_volume`               | y   |  количество единиц в общем объеме (например объем 5 тонн - кол-во единиц 1000 (в килограммах ,это указано в unit_for_unit))
| `procent`                            | y   |  наш процент от сделки (смотрим по таблице зависимости процента от бюджета сделки
| `val_for_all`                        | y   |  количество единиц товара (штук, ящиков...) 
| `commission`                         | y   |   наша комиссия со сделки, в рублях 
| `file`                               | n   |  загружаемый файл для сделки
| `images`  []                         | n   |  картинки джля сделки


### Редактировать сделку на продажу SELL по ее id

    [Authorized]
    [POST] /admin/api/deals/:id

Посылаемые поля:
            
| Field                                | Req |                                                                                                                         
| ------------------------------------ | --- | ----------------------------------------------------------------------------------------------------------------------
| `name`                               | y   | название сделки <=191 симв.
| `categories` []                      | y   | категории (надо проверить, чтобы была второго уровня!!!)
| `cities`  []                         | y   | 
| `description`                        | y   | <= 3000 симв.  | 
| `type_deal`                          | y   | = 'sell'
| `tags`                               | n   | <= 500
| `files`  []                          | n   |  файлы архивов, документов и картинок, числом не боле 3х и размер <5 мб
     
      

### Вернуть на модерацию

    [Authorized]
    [GET] /admin/api/deals/refund/moderation/:id

| Field                                | Req | Type deal    |
| ------------------------------------ | --- | ------------ |
| `id`                                 | y   | 'sell'|'buy' | id сделки    

### Удалить сделку (отправить в архив) 

    [Authorized]
    [DELETE] /admin/api/deals/:id
    
    Если сделка на продажу SELL, то удаляет ее, проставив deleted_at,чтобы она не отобразажалсь в ЛК юзера в завершенных

### Работа с картинками

#### Загрузить картинку

    [Authorized]
    [POST] /admin/api/deals/upload/image

| Field                                | Req | Type deal    |
| ------------------------------------ | --- | ------------ |
| `deal_id`                            | y   | 'sell'|'buy' | id сделки
| `file`                               | y   | 'sell'|'buy' | файл изображения   - максимум 10 Мб

#### Удалить картинку

    [Authorized]
    [POST] /admin/api/deals/image/delete

| Field                                | Req | Type deal    |
| ------------------------------------ | --- | ------------ |
| `id`                                 | y   | 'sell'|'buy' | id картинки    


### Работа с файлами по сделкам

#### Загрузить файл в сделку

    [Authorized]
    [POST] /admin/api/deals/file/upload

| Field                                | Req | Type deal    |
| ------------------------------------ | --- | ------------ |
| `user_id`                            | y   | 'sell'|'buy' | id пользователя, загрузившего файл (для админки это будет ID админа, который сейчас админит)    
| `deal_id`                            | y   | 'sell'|'buy' | id сделки   
| `file`                               | y   | 'sell'|'buy' | файл (rtf,doc,docx,xls,xlsx,pdf)  - максимум 10 Мб

#### Удалить файл из сделки по его id

    [Authorized]
    [POST] /admin/api/deals/file/delete/:id

#### Получить информацию по файлу из сделки по его id

    [Authorized]
    [GET] /admin/api/deals/file/:id



## Модерация  ------------------------------------------------------------------------

### Получить пагинированный список сделок

    [Authorized]
    [GET] /admin/api/deals/show/moderate


| Field                                | Req | Type deal    |
| ------------------------------------ | --- | ------------ |
| `per_page`                           | y   | 'sell'|'buy' | пагинация : строк на страницу
| `page`                               | y   | 'sell'|'buy' | пагинация : номер страницы
| `myUsers`                            | y   | 'sell'|'buy' | false|true  : показывать только сделки текущего менеджера
| `type_deal`                          | y   | 'sell'|'buy' | показывать сделки определенного типа
    
### Получить пагинированный список откликов, по id сделки. По умолчинию показывает те, которые на модерации

    [Authorized]
    [GET] /admin/api/deals/moderation/response/deals/:id

Параметры запроса:
| Field                                | Req |
| ------------------------------------ | --- |
| `per_page`                           | y   | пагинация : строк на страницу
| `page`                               | y   | пагинация : номер страницы
| `all`                                | n   | = true - показать все отклики

Пример положительного ответа :
{
    "result": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 34,  
                "organization_id": 16,
                "trading_status": "moderation",
                "price_offer": 123,
                "is_shipping_included": 1,
                "created_at": {  
                    "date": "2019-08-22 11:04:09.000000",
                    "timezone_type": 3,
                    "timezone": "UTC"
                },
                "user_id": 21,
                "user_name": "olegmor",
                "user_unique_id":
            },
        ......

Возвращаемые поля (не учитывая полей пагинации):
| Field                          | 
| -------------------------------| 
| `id`                           | id отклика
| `organization_id`              | id откликнувшейся организации
| `trading_status`               | статус отклика 
| `price_offer`                  | ценовое предложение
| `is_shipping_included`         | стоимость с доставкой
| `created_at`                   | дата отклика
| `user_id`                      | id откликнувшегося
| `user_name`                    | имя откликнувшегося
| `user_unique_id`               | уникальный id откликнувшегося



### Получить пагинированный список сделок, у которых есть отклики

    [Authorized]
    [GET] /admin/api/deals/moderation/response/deals

| Field                                | Req | 
| ------------------------------------ | --- | 
| `per_page`                           | y   |  пагинация : строк на страницу
| `page`                               | y   |  пагинация : номер страницы
| `only_new`                           | y   |  1|0 - показывать сделки только с немодерированными откликами = 1, 0 - показывать с любыми


### Получить отклик по его id

    [Authorized]
    [GET] /admin/api/deals/moderation/response/:id

Пример положительного ответа :
{
    "result": true,
    "data": {
        "id": 39,
        "organization_id": 16,
        "deal_name": "qqqqqq",
        "trading_status": "moderation",
        "price_offer": 123,
        "is_shipping_included": 1,
        "notice": "responce",
        "created_at": {
            "date": "2019-08-23 11:26:01.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        },
        "user_id": 21,
        "user_name": "olegmor",
        "user_unique_id": 3000222,
        "answers": {
            "dqh_volume": {
                "id": 65,
                "deal_id": 29,
                "organization_id": 16,
                "question_id": 10,
                "question": "объем",
                "is_agree": 0,
                "answer": null
            },
            "dqh_specification": {
                "id": 66,
                "deal_id": 29,
                "organization_id": 16,
                "question_id": 3,
                "question": "спецификация",
                "is_agree": 1,
        ......

Возвращаемые поля (не учитывая полей пагинации):
| Field                          | 
| -------------------------------| 
| `id`                           | id отклика
| `organization_id`              | id откликнувшейся организации
| `deal_name`                    | название сделки
| `trading_status`               | статус отклика 
| `price_offer`                  | ценовое предложение
| `is_shipping_included`         | стоимость с доставкой
| `notice`                       | дополниельная тектовая информация от поставщика
| `created_at`                   | дата отклика
| `user_id`                      | id откликнувшегося
| `user_name`                    | имя откликнувшегося
| `user_unique_id`               | уникальный id откликнувшегося
| `answers`                      | массив ответов (с проставленными чекбоксами) , где ключ массива - это слаг вопроса
| `answers[id]`                  | id  - ответа
| `answers[question_id]`         | id  - вопроса
| `answers[question]`            | содержимое вопроса (текст)
| `answers[is_agree]`            | 1|0 - согласен или нет с вопросом

### Получить сделку по ее id

    [Authorized]
    [GET] /admin/api/deals/:id

### Редактировать отклик продавца, по id отклика

    [Authorized]
    [POST] /admin/api/deals/moderation/response

Передаваемые поля:    
| Field                                | Req | 
| ------------------------------------ | --- | 
| `id`                                 | y   | id отклика
| `price_offer`                        | n   |  ценовое предложение
| `is_shipping_included`               | n   |  0|1 -  стоимость с доставкой (0 = НЕТ, 1 = ДА)
| `notice`                             | n   |  дополниельная тектовая информация от поставщика
| `answers[dqh_volume]`                | n   |  0|1 - объем
| `answers[dqh_specification]`         | n   |  0|1 - Спецификация
| `answers[dqh_doc_confirm_quality]`   | n   |  0|1 - Документы подтверждающие качество
| `answers[dqh_logistics]`             | n   |  0|1 - Логистика
| `answers[dqh_type_deal]`             | n   |  0|1 - Тип сделки
| `answers[dqh_payment_term]`          | n   |  0|1 -  Условия оплаты
| `answers[dqh_payment_method]`        | n   |  0|1 -  Cпособ оплаты
| `answers[dqh_min_party]`             | n   |  0|1 -  Минимальная партия


### Модерация - принять сделку

    [Authorized]
    [POST] /admin/api/deals/moderation/success/:id

| Field                                | Req | Type deal    |
| ------------------------------------ | --- | ------------ |
| `id`                                 | y   | 'sell'|'buy' | id сделки
| `tags`                               | y   | 'sell'       | строка тэгов разделенная запятыми

### Модерация - принять отклик продавца

    [Authorized]
    [POST] /admin/api/deals/moderation/response/success

| Field                                | Req |
| ------------------------------------ | --- |
| `id`                                 | y   | id отклика

### Модерация - отклонить сделку

    [Authorized]
    [POST] /admin/api/deals/moderation/fails/:id

| Field                                | Req | Type deal    |
| ------------------------------------ | --- | ------------ |
| `id`                                 | y   | 'sell'|'buy' | id сделки

### Модерация - отклонить отклик продавца

    [Authorized]
    [POST] /admin/api/deals/moderation/response/fail

| Field                                | Req |
| ------------------------------------ | --- |
| `id`                                 | y   | id отклика

### Модерация. Отклик. Удалить файл из отклик по id файла.

    [Authorized]
    [POST] /admin/api/deals/moderation/response/image/delete/:id

| Field                                | Req |Description   |
| ------------------------------------ | --- |------------- |
| `id`                                 | y   | id файла     |

### Админка. Клиенты -  создать объявление менеджеру

    [Authorized]
    [POST] /admin/api/deals/manager/store

| Field                                | Req | Description                                |
| ------------------------------------ | --- | ------------------------------------------ |
| `user_id`                            | n   | к кому привязать сделку                    |
| `type_deal`                          | y   | 'sell'|'buy'                               |
| `count_deal`                         | y   | количество сделок , которое мы автосоздаем | 
| `is_fake`                            | n   | 0|1 - фейковая сделка, для маркетинга      | 


## Ключевые слова (теги, tags) =========================================================

### Получить пагинированный список ключевых слов

    [Authorized]
    [GET] /admin/api/tags?query=ро

Параметры запроса:

| Field                    | Req |
| -------------------------| --- |
| `query`                  | n   | поиск по части имени ключевого слова

Пример ответа:
 "result": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 6,
                "name": "Продаю dopoln"
            },
            {
                "id": 7,
                "name": "Продаю юю"
            }
        ],
        .......

### Получить ключевых слово по его id

    [Authorized]
    [GET] /admin/api/tags/:id

### Получить ключевых слово по его id

    [Authorized]
    [GET] /admin/api/tags/:id

### Записать ключевое слово в словарь тэгов

    [Authorized]
    [POST] /admin/api/tags/store

    Параметры запроса:

| Field                    | Req |
| -------------------------| --- |
| `name`                   | y   | попутно нормализует тэг максимум до двух слов до запятой

### Обновить ключевое слово по его id в словаре тэгов

    [Authorized]
    [POST] /admin/api/tags/:id

    Параметры запроса:

| Field                    | Req |
| -------------------------| --- |
| `name`                   | y   | попутно нормализует тэг максимум до двух слов до запятой

### Удалить ключевое слово по его id

    [Authorized]
    [DELETE] /admin/api/tags/:id