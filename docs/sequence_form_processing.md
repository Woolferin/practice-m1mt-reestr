# Діаграма послідовності: Процес обробки форми звернення (Sequence Diagram)

## Графічна модель

```mermaid
sequenceDiagram
    autonumber
    actor User as Громадянин
    participant Browser as Веб-браузер (Клієнт)
    participant OSM as OpenStreetMap
    participant Server as Веб-сервер (process.php)
    participant DB as База даних (MariaDB)

    User->>Browser: Відкриває сторінку форми (index.php)
    Browser->>OSM: Запит картографічних тайлів (Leaflet.js)
    OSM-->>Browser: Повернення тайлів карти
    User->>Browser: Взаємодія з картою (клік для вибору локації)
    Browser-->>User: Відображення маркера та фіксація координат
    User->>Browser: Заповнення текстових полів та ініціація відправки (Submit)
    
    Browser->>Server: HTTP POST запит (дані форми + lat, lng)
    activate Server
    
    Server->>Server: Санітизація (trim) та валідація даних
    Server->>DB: Підготовка SQL-запиту (PDO prepare)
    activate DB
    
    Server->>DB: Виконання транзакції (PDO execute)
    DB-->>Server: Підтвердження успішного запису
    deactivate DB
    
    Server-->>Browser: HTTP-відповідь (HTML-сторінка статусу)
    deactivate Server
    
    Browser-->>User: Відображення повідомлення про успішну реєстрацію