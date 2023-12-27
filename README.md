## Об этом проекте

Это - просто тестовое задание для одной вакансии.

## Описание задачи

Разработать API backend на фреймворке Laravel 10. В качестве БД использовать MySQL.

Результат должен быть выложен на Github

Требуемый функционал:

Регистрация: ФИО, email (уникальный), телефон (уникальный), пароль, подтверждение пароля. Все поля обязательны.

Авторизация: email или телефон (одно поле), пароль
Для авторизованных пользователей доступен “каталог товаров”. Товар: название, цена, количество. Свойства (опции) товара: название и значение
Свойства товара должны быть произвольными т.е. заполняться в отдельной таблице БД (например: color / red; color / white; weight / 1000, weight / 1250)

Реализовать фильтрацию списка товаров по названию.

Методы доступные неавторизованным пользователям: регистрация, авторизация

Методы доступные авторизованным пользователям: список товаров (“каталог товаров”) пагинированных по 40

Идентификация пользователя должна происходить по Bearer токену. Добавить Swagger документацию.

## Описание решения

Я взял за основу начальные наработки для одного несостоявшегося проекта. Там уже была настроена среда на основе docker-compose (настраивал сам), а так же был установлен Meilisearch, который в итоге используется для поиска. Я не стал применять паттерны "DTO" и "Репозиторий", поскольку для такой архитектуры (по моему мнению) это - лишнее усложнение (тем более, что внедрить их при необходимости не сложно).

Свойства товара реализованы в виде связи один-ко-многим с таблицей товаров и составным уникальным индексом "имя, ID товара". Я знаю, что это обычно реализуется связью многие-ко-многим с промежуточным значением, но тут, во-первых имеется формулировка "свойства товара должны быть произвольными", во-вторых, не указаны ни типы, ни диапозоны значений. Из этого я сделал вывод, что строгий контроль целостности и уникальности здесь не требуется, но требуется простота и гибкость добовления новых свойств, по этой причине сознательно пренебрёг нормализацией.

В корне проекта содержится Makefile, если выполнить команду make help, там будут инструкции и команды по его использованию.