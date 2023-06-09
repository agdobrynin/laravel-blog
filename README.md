## Web приложение "Блог".

🎫 [Сертификат об успешном прохождении курса.](https://www.udemy.com/certificate/UC-9087af59-db9d-4d71-8956-df8600250038/)

Создано классическое WEB приложение с возможностью
регистрации пользователя и верификации аккаунта через
email сообщение.

Приложение с поддержкой нескольких языков интерфейса (localization).

Добавлена возможность управление аккаунтом пользователя
сброс и смена забытого пароля, ссылка подтверждения email адреса.

Для выполнения "затратных" операций таких как отправка email, собор
статистики, изменение размера изображений загруженных пользователем
используются фоновые задачи на основе
очередей.

Реализовано простое API для работы с комментариями с авторизацией
через токены (выдача токена доступа к API для зарегистрированных
пользователей на сайте). 

Добавлена документация на API с использованием формата Swagger/OpenApi 3.0
(пакет `darkaonline/l5-swagger`).

Сделаны тесты на основной функционал приложения.

---

**Стек:**

- 🐘 **Php 8.2** + **Laravel 10** (пакет laravel/ui для авторизации и регистрации пользователя для web и
  laravel/sanctum для авторизации через API на базе токенов)
- 🧶 **Boostrap css 5** - css фреймворк
- 🦖 **MariaDb** - основная база
- 🟥 **Redis** - сервер кэширования и сервер очередей
- 🌌 **Swagger** - для документации API + Swagger UI
- ⛑ **PhpUnit** - интенрационное тестирование сайта и API комментариев.
- 🐋 **Docker**, **Laravel Sail** - для локальной разработки.
-------
**Темы изученные в курсе**:

- Eloquent - ORM for interacting with the database
- Advanced features like Queues, Polymorphic relationships, Service Container
- Learn all the theory while building a real application as you progress!
- Creating APIs, serializing data, API resources and API testing
- Routes and Controllers
- Laravel Tinker - command line playground to Laravel
- Blade templates
- Blade components
- Creating Forms, CSRF tokens
- One to One, One to Many, Many to Many relationships
- Polymorphic relationships
- Testing
- Local and Global Eloquent Query Scopes
- Database migrations
- Database seeding and factories
- Authentication (Guard)
- Authorization (Policies and Gates)
- Authorization
- Caching
- How to use queues
- Files and file uploads
- How to send e-mails
- Observers, Events, Listeners and Subscribers
- Localization
- Services, Service Container, Contracts and Facades
- Using Traits in Laravel - SoftDeletes and creating your own!

### Установка проекта

Для развертывания проекта потребуется установленный
🐳 **docker** или же 🐋 **docker desktop** проект будет работать
как на Windows с поддержкой WSL2 так и на Linux машине.

Локальная разработка и тестирование проекта использует
легковесный [Laravel Sail](https://laravel.com/docs/9.x/sail)
для работы с docker контейнерами.

Настроить переменные окружения (если требуется изменить их):

```shell
cp .env.example .env
```

Установить зависимости проекта:

```shell
docker run --rm -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

на этом подготовка к работе с Laravel Sail закончена.

### Запуск проекта

Поднять docker контейнеры с помощтю Laravel Sail

```shell
./vendor/bin/sail up -d
```

```shell
./vendor/bin/sail composer install
```

доступные команды по остановке или пересборке контейнеров можно узнать на странице
[Laravel Sail](https://laravel.com/docs/10.x/sail)
или выполните команду `./vendor/bin/sail` для получения краткой справки о доступных командах.

1. Сгенерировать application key
   ```shell
   ./vendor/bin/sail artisan key:generate
   ```

2. Выполнить миграции и заполнить таблицы тестовыми данными
   ```shell
   ./vendor/bin/sail artisan migrate --seed
   ```
3. Настроить storage link для загруженных файлов
    ```shell
    ./vendor/bin/sail artisan storage:link
    ```
4. Собрать фронт
    ```shell
    ./vendor/bin/sail npm install
    ```
    ```shell
    ./vendor/bin/sail npm run build
    ```
5. Запустить воркер (worker) обрабатывающий задачи из очереди сообщений

    ```shell
    ./vendor/bin/sail artisan queue:work --tries=3 --queue=email,default,low
    ```
   в проекте используется очереди с разными приоритетами.

### Доступные сайты в dev окружении

|                Host                | Назначение                                                   |
|:----------------------------------:|:-------------------------------------------------------------|
|          http://localhost          | сайт приложения                                              |
|       http://localhost:8025        | Mailpit - вэб интерфейс для отладки отправки email сообщения |
| http://localhost/api/documentation | Swagger UI - документация к API формата Swagger 3.0          |
