### Описание
.env: Файл с настройками базы данных.

config/db.php: Файл для подключения к базе данных.

api/mock_api.php: Файл с мок-функциями для имитации API.

src/OrderService.php: Основной сервис для работы с заказами.

index.php: Точка входа в приложение, где происходит вызов сервиса и обработка заказа.

### Объяснение решения
## Нормализация таблиц:

ticket_types: Содержит информацию о различных типах билетов.

tickets: Содержит информацию о каждом отдельном билете, включая уникальный штрих-код для каждого билета.

## Функциональность:

OrderService:

generateUniqueBarcode: Генерирует уникальный штрих-код.

bookOrder: Бронирует заказ и сохраняет его в базе данных, включая все типы билетов.

insertTickets: Вспомогательная функция для вставки билетов в таблицу tickets.

approveOrder: Подтверждает заказ через сторонний API.