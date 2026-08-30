# Архітектура системи (C4 Model - Рівень контексту та контейнерів)

## Графічна модель

```mermaid
graph TD
    classDef actor fill:#08427b,stroke:#052e56,stroke-width:2px,color:#fff,border-radius:50%;
    classDef system fill:#1168bd,stroke:#0b4884,stroke-width:2px,color:#fff;
    classDef db fill:#2b8a3e,stroke:#1e602b,stroke-width:2px,color:#fff;
    classDef external fill:#999999,stroke:#666666,stroke-width:2px,color:#fff;

    Citizen(["Громадянин"]):::actor
    Admin(["Адміністратор"]):::actor

    subgraph "Електронний реєстр звернень (MVP)"
        WebApp["Веб-сервер (Apache + PHP)"]:::system
        Database[("База даних (MariaDB)")]:::db
    end

    OSM["Зовнішній сервіс (OpenStreetMap)"]:::external

    Citizen -->|"1. Подає звернення та геодані"| WebApp
    Admin -->|"2. Переглядає дані, змінює статус"| WebApp
    
    WebApp -->|"3. Виконує SQL-запити через PDO"| Database
    Database -->|"4. Повертає результати"| WebApp
    
    WebApp -.->|"5. Завантажує тайли карти Leaflet"| OSM

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
