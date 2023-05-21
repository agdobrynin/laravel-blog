## Приложение блоги.
🎫 [Сертификат об успешном прохождении курса.](https://www.udemy.com/certificate/UC-9087af59-db9d-4d71-8956-df8600250038/)

Создано классическое WEB приложение с возможностью
регистрации пользователя и верификации аккаунта через
email сообщение.

Добавлена возможность управление аккаунтом пользователя
сброс и смена забытого пароля, ссылка подтверждения email адреса.

Для выполнения "затратных" операций таких как отправка email, собор
статистики, изменение размера изображений загруженных пользователем 
используются фоновые задачи на основе
очередей.

Сделаны тесты на основной функционал приложения.

Реализовано простое API для работы с комментариями с авторизацией 
через токены (выдача токена доступа к API для зарегистрированных 
пользователей).

**Стек:**
- 🐘 Php 8.2 + Laravel 10 (пакет laravel/ui для авторизации и регистрации пользователя)
- 🧶 Boostrap css 5
- 🦖 MariaDb - основная базе
-  🟥 Redis - сервер кэширования и сервер очередей

-------
Темы изученные в курсе:
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

#### Настройка переменных окружения проекта

Создать файл настроект проекта

```shell
cp .env.example .env
```

и если нужно настроить переменные окружения в `.env` файле

#### Утановка зависимостей проекта через composer

Если на машине разработчика **не установлен** локально composer
то зависимости проекта можно установить так

```shell
docker run --rm --interactive --tty \
  -u "$(id -u):$(id -g)" \
  --volume $PWD:/app \
  composer install
```

⚠ если же на машине разработчика установлен **composer** и **php**
то для начала необходимо установить зависимости
проекта выполнив команду

```shell
composer install --ignore-platform-reqs --no-scripts
```

на этом подготовка к работе с Laravel Sail закончен.

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

1.  Сгенерировать application key
    ```shell
    ./vendor/bin/sail artisan key:generate
    ```

2.  Выполинть миграции и заполинть таблицы тестовыми данными
    ```shell
    ./vendor/bin/sail artisan migrate --seed
    ```
3. Натсроить storage link для загруженных файлов
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
   в проекте используется две очереди с разными приоритетами

### Доступные сайты в dev окружении

|         Host          | Назначение                                                                     |
|:---------------------:|:-------------------------------------------------------------------------------|
|   http://localhost    | сайт приложения                                                                |
| http://localhost:8025 | Mailpit - вэб интерфейс для отладки отправки email сообщения                   |
