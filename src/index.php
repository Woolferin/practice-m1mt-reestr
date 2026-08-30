<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Електронний реєстр звернень</title>
    
    <!-- Підключаємо стилі та скрипти Leaflet.js -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 20px; }
        .container { max-width: 700px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        label { font-weight: bold; margin-top: 10px; display: block; }
        input[type="text"], select, textarea { width: 100%; padding: 10px; margin-top: 5px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #0056b3; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        button:hover { background-color: #004494; }
        /* Стиль для блоку з картою */
        #map { height: 350px; width: 100%; margin-bottom: 15px; border-radius: 4px; border: 1px solid #ccc; z-index: 1; }
    </style>
</head>
<body>

<div class="container">
    <h2>Подати звернення з благоустрою</h2>
    <form action="process.php" method="POST">
        <label for="full_name">ПІБ заявника:</label>
        <input type="text" id="full_name" name="full_name" required>

        <label for="category">Категорія проблеми:</label>
        <select id="category" name="category" required>
            <option value="Дороги">Ями на дорогах</option>
            <option value="Освітлення">Проблеми з освітленням</option>
            <option value="Сміття">Стихійні звалища / Сміття</option>
            <option value="Благоустрій">Пошкодження майна / Парки</option>
        </select>

        <label for="address">Орієнтовна адреса:</label>
        <input type="text" id="address" name="address" required>

        <!-- Блок карти -->
        <label>Вкажіть точне місце на карті (клікніть мишкою):</label>
        <div id="map"></div>
        
        <!-- Приховані поля для передачі координат на сервер -->
        <input type="hidden" id="lat" name="lat" required>
        <input type="hidden" id="lng" name="lng" required>
        <p id="coords-text" style="font-size: 12px; color: #666; margin-top: -10px;">Координати не обрано</p>

        <label for="description">Детальний опис проблеми:</label>
        <textarea id="description" name="description" rows="4" required></textarea>

        <button type="submit">Відправити звернення</button>
    </form>
</div>

<!-- Логіка JavaScript для карти -->
<script>
    // Ініціалізуємо карту по центру Тернополя (49.5535, 25.5948)
    var map = L.map('map').setView([49.5535, 25.5948], 13);

    // Додаємо шар відкритих карт OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var marker = null; // Змінна для маркера

    // Обробник кліку по карті
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        // Якщо маркер вже є - переміщуємо його, якщо немає - створюємо
        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }

        // Записуємо координати у приховані поля форми
        document.getElementById('lat').value = lat.toFixed(6);
        document.getElementById('lng').value = lng.toFixed(6);
        
        // Показуємо координати користувачу для наочності
        document.getElementById('coords-text').innerText = "Обрані координати: " + lat.toFixed(6) + ", " + lng.toFixed(6);
    });
</script>

</body>
</html>