## Приложение блоги.
🎫 [Сертификат об успешном прохождении курса.](https://www.udemy.com/)

Создано классическое WEB приложение с возможностью
регистарции пользователя и верификации аккаунта чрезе
email сообщение.

Добавлена возможность управление аккаунтом пользователя
сброс и смена забытого пароля, ссылка подтверждения email адреса.
````
позже описать особенности реализации...
````

**Стек:**
- 🐘 Php 8.2 + Laravel 10 (пакет laravel/ui для авторизации и регистрации пользователя)
- 🧶 Boostrap css 5
- 🦖 MariaDb - обновная базе
-  🟥 Redis - сервер кэширования

-------
Темы изученые в курсе
````
добавить из описания курса темы попозже.
````
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

### Доступные сайты в dev окружении

|         Host          | Назначение                                                                     |
|:---------------------:|:-------------------------------------------------------------------------------|
|   http://localhost    | сайт приложения                                                                |
| http://localhost:8025 | Mailpit - вэб интерфейс для отладки отправки email сообщения                   |
