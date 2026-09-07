# Архітектура системи (C4 Model - Рівень контексту та контейнерів)

## Графічна модель

```mermaid
graph TD
    %%
    classDef actor fill:#08427b,stroke:#052e56,stroke-width:2px,color:#fff,border-radius:50%;
    classDef system fill:#1168bd,stroke:#0b4884,stroke-width:2px,color:#fff;
    classDef db fill:#2b8a3e,stroke:#1e602b,stroke-width:2px,color:#fff;

    Citizen(["Громадянин"]):::actor
    Admin(["Адміністратор"]):::actor

    subgraph "Електронний реєстр звернень (MVP)"
        WebApp["Веб-сервер (Apache + PHP)"]:::system
        Database[("База даних (MariaDB)")]:::db
    end

    Citizen -->|"1. Подає звернення"| WebApp
    Admin -->|"2. Переглядає дані"| WebApp
    
    WebApp -->|"3. Виконує SQL-запити через PDO"| Database
    Database -->|"4. Повертає результати"| WebApp