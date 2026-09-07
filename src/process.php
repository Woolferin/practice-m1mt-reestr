<?php
require 'db_connect.php';

// Перевіряємо, чи дані прийшли через POST-запит
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Отримуємо дані
    $name = trim($_POST['name'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // Базова перевірка на порожні поля
    if (!empty($name) && !empty($contact) && !empty($address) && !empty($description)) {
        // Підготовлений запит для захисту від SQL-ін'єкцій
        $sql = "INSERT INTO appeals (name, contact, address, description) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$name, $contact, $address, $description])) {
            echo "<h3>Звернення успішно відправлено!</h3>";
            echo "<a href='index.php'>Повернутися до форми</a>";
        } else {
            echo "<h3>Помилка при збереженні звернення.</h3>";
        }
    } else {
        echo "<h3>Помилка: Заповніть усі поля.</h3>";
        echo "<a href='index.php'>Повернутися назад</a>";
    }
} else {
    // Якщо зайшли на сторінку напряму через GET
    header("Location: index.php");
    exit;
}
?>