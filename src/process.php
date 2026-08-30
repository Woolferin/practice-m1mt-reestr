<?php
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ЗБЕРІГАЄМО ОРИГІНАЛЬНІ ДАНІ (без htmlspecialchars)
    $full_name = trim($_POST['full_name']);
    $category = trim($_POST['category']);
    $address = trim($_POST['address']);
    $description = trim($_POST['description']);
    
    // Отримуємо координати
    $lat = isset($_POST['lat']) ? floatval($_POST['lat']) : null;
    $lng = isset($_POST['lng']) ? floatval($_POST['lng']) : null;

    if (!empty($full_name) && !empty($category) && !empty($description) && $lat && $lng) {
        try {
            $sql = "INSERT INTO tickets (full_name, category, address, description, lat, lng) 
                    VALUES (:full_name, :category, :address, :description, :lat, :lng)";
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                ':full_name' => $full_name,
                ':category' => $category,
                ':address' => $address,
                ':description' => $description,
                ':lat' => $lat,
                ':lng' => $lng
            ]);

            echo "<div style='font-family: Arial; text-align: center; margin-top: 50px;'>";
            echo "<h2 style='color: green;'>Ваше звернення та геолокацію успішно збережено!</h2>";
            echo "<br><a href='index.php' style='padding: 10px 20px; background: #0056b3; color: white; text-decoration: none; border-radius: 4px;'>Повернутися на головну</a>";
            echo "</div>";
        } catch (PDOException $e) {
            echo "Помилка збереження даних: " . $e->getMessage();
        }
    } else {
        echo "Будь ласка, заповніть усі поля та обов'язково клікніть на карту!";
    }
} else {
    header("Location: index.php");
    exit();
}
?>