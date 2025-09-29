# Organizations API

API для работы с организациями и зданиями на Laravel.  
Все приложение завернуто в Docker, чтобы его можно было развернуть на любой машине.

## Требования

- Docker >= 24.0  
- Docker Compose >= 2.0  

## Быстрый старт

1. Склонировать репозиторий:

```bash
git clone https://github.com/AlexKoss25/OrganizationsAPI.git
cd OrganizationsAPI
Создать .env на основе примера:
cp .env.example .env
В .env можно настроить подключение к БД, порт и API ключ.

Собрать и запустить контейнеры:
docker-compose up -d --build

Установить зависимости Laravel:
docker-compose exec app composer install

Сгенерировать ключ приложения:
docker-compose exec app php artisan key:generate

Выполнить миграции и сиды:
docker-compose exec app php artisan migrate --seed

API будет доступен по адресу:
http://localhost:8080/api

Swagger документация доступна по:
http://localhost:8080/swagger/

Использование API
Все эндпоинты требуют заголовок X-API-KEY с вашим ключом (по умолчанию super-secret-key).

Пример запроса через curl:
curl -X GET "http://localhost:8080/api/organizations/radius?latitude=55.751&longitude=37.618&radius=1&per_page=2" \
-H "X-API-KEY: super-secret-key"
Поддерживаемые эндпоинты
/organizations/building/{buildingId} — список организаций в конкретном здании
/organizations/activity/{activityId} — список организаций по виду деятельности
/organizations/radius — список организаций в радиусе
/organizations/bybbox — список организаций в прямоугольной области (bbox)
/organizations/{id} — информация об организации по ID
/organizations/search-by-activity — поиск организаций по виду деятельности с вложенностью
/organizations/search — поиск организаций по названию

Все эндпоинты поддерживают параметр per_page для ограничения количества результатов.

Структура Docker
Dockerfile — образ PHP 8.2 + Laravel + расширения

docker-compose.yml — сервисы:
app — PHP-FPM + Laravel
nginx — веб-сервер
db — MySQL
phpmyadmin — интерфейс для БД (опционально)

После запуска docker-compose up -d --build все сервисы будут работать сразу.
