<?php
require 'db_connect.php';

// Отримуємо всі звернення з бази даних (найновіші зверху)
$stmt = $pdo->query("SELECT * FROM appeals ORDER BY created_at DESC");
$appeals = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Панель адміністратора</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f4f4f4; }
        tr:hover { background-color: #f9f9f9; }
    </style>
</head>
<body>
    <h2>Зведені дані: Реєстр звернень</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ПІБ</th>
                <th>Контакти</th>
                <th>Адреса</th>
                <th>Опис проблеми</th>
                <th>Дата подання</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($appeals) > 0): ?>
                <?php foreach ($appeals as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['contact']) ?></td>
                        <td><?= htmlspecialchars($row['address']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Жодного звернення поки не знайдено.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>