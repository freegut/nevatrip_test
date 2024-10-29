# Order Management System

Этот проект представляет собой систему управления заказами для покупки билетов на события. Он включает в себя функциональность для генерации уникальных штрих-кодов, бронирования заказов через сторонний API и сохранения заказов в базе данных.

## Структура проекта
project/
│
├── config/
│ └── db.php
│
├── api/
│ └── mock_api.php
│
├── src/
│ └── OrderService.php
│
├── index.php
└── .env

## Создание таблицы в базе данных:

Создайте таблицу orders в вашей базе данных с помощью следующего SQL-запроса:

CREATE TABLE orders (
    id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_id INT(11) NOT NULL,
    event_date VARCHAR(10) NOT NULL,
    ticket_adult_price INT(11) NOT NULL,
    ticket_adult_quantity INT(11) NOT NULL,
    ticket_kid_price INT(11) NOT NULL,
    ticket_kid_quantity INT(11) NOT NULL,
    barcode VARCHAR(120) NOT NULL,
    equal_price INT(11) NOT NULL,
    created DATETIME NOT NULL
);

## Сервис
В директории src/ находится файл OrderService.php, который содержит основной сервис для работы с заказами:

generateUniqueBarcode($length = 8): Генерирует уникальный штрих-код.

bookOrder($eventId, $eventDate, $ticketAdultPrice, $ticketAdultQuantity, $ticketKidPrice, $ticketKidQuantity): Бронирует заказ и сохраняет его в базе данных.

approveOrder($barcode): Подтверждает заказ через сторонний API.