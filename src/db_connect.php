<?php
// Налаштування доступу до бази даних, які ми створили раніше
$host = '127.0.0.1';
$db   = 'm1mt_reestr';
$user = 'reestr_user';
$pass = '12345';

try {
    // Підключаємося через сучасний інтерфейс PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    // Вмикаємо відображення помилок бази даних
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Помилка підключення до бази даних: " . $e->getMessage());
}
?>