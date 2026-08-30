<?php
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ticket_id']) && isset($_POST['new_status'])) {
    $ticket_id = (int)$_POST['ticket_id'];
    $new_status = $_POST['new_status'];
    
    $allowed_statuses = ['Нове', 'В роботі', 'Вирішено'];
    
    if (in_array($new_status, $allowed_statuses)) {
        try {
            $update_sql = "UPDATE tickets SET status = :status WHERE id = :id";
            $update_stmt = $pdo->prepare($update_sql);
            $update_stmt->execute([
                ':status' => $new_status,
                ':id' => $ticket_id
            ]);
            
            header("Location: admin.php");
            exit();
        } catch (PDOException $e) {
            die("Помилка оновлення статусу: " . $e->getMessage());
        }
    }
}

try {
    $stmt = $pdo->query("SELECT * FROM tickets ORDER BY created_at DESC");
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Помилка отримання даних: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель адміністратора</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; font-size: 14px; vertical-align: middle; }
        th { background-color: #0056b3; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        
        .map-link { display: inline-block; padding: 5px 10px; background: #28a745; color: white; text-decoration: none; border-radius: 3px; font-size: 12px; }
        .map-link:hover { background: #218838; }
        
        .status-form { display: flex; gap: 5px; align-items: center; margin-top: 5px; }
        .status-select { padding: 5px; border-radius: 3px; border: 1px solid #ccc; font-size: 13px; outline: none; width: 100%; cursor: pointer; }
        
        .badge { padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; color: white; display: inline-block; text-align: center; width: 70px; }
        .badge-new { background-color: #dc3545; }
        .badge-progress { background-color: #17a2b8; }
        .badge-resolved { background-color: #28a745; }
    </style>
</head>
<body>

<div class="container">
    <h2>Електронний реєстр звернень</h2>
    
    <table>
        <tr>
            <th>ID</th>
            <th>ПІБ</th>
            <th>Категорія та Опис</th>
            <th>Локація на карті</th>
            <th style="width: 150px;">Статус звернення</th>
            <th>Дата подачі</th>
        </tr>
        
        <?php if (count($tickets) > 0): ?>
            <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?= htmlspecialchars($ticket['id']) ?></td>
                    <td><strong><?= htmlspecialchars($ticket['full_name']) ?></strong></td>
                    <td>
                        <span style="color: #0056b3; font-weight: bold;"><?= htmlspecialchars($ticket['category']) ?></span><br>
                        <?= htmlspecialchars($ticket['description']) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($ticket['address']) ?><br><br>
                        <?php if ($ticket['lat'] && $ticket['lng']): ?>
                            <a class="map-link" href="https://www.openstreetmap.org/?mlat=<?= $ticket['lat'] ?>&mlon=<?= $ticket['lng'] ?>#map=17/<?= $ticket['lat'] ?>/<?= $ticket['lng'] ?>" target="_blank">Показати на карті</a>
                        <?php else: ?>
                            <span style="color: #999; font-size: 12px;">Координат немає</span>
                        <?php endif; ?>
                    </td>
                    
                    <td>
                        <?php 
                            $badge_class = 'badge-new';
                            if ($ticket['status'] == 'В роботі') $badge_class = 'badge-progress';
                            if ($ticket['status'] == 'Вирішено') $badge_class = 'badge-resolved';
                        ?>
                        <div style="margin-bottom: 8px;">
                            <span class="badge <?= $badge_class ?>"><?= htmlspecialchars($ticket['status']) ?></span>
                        </div>
                        
                        <form action="admin.php" method="POST" class="status-form">
                            <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
                            <!-- Додано onchange="this.form.submit()" для миттєвої відправки форми -->
                            <select name="new_status" class="status-select" onchange="this.form.submit()">
                                <option value="Нове" <?= $ticket['status'] == 'Нове' ? 'selected' : '' ?>>Нове</option>
                                <option value="В роботі" <?= $ticket['status'] == 'В роботі' ? 'selected' : '' ?>>В роботі</option>
                                <option value="Вирішено" <?= $ticket['status'] == 'Вирішено' ? 'selected' : '' ?>>Вирішено</option>
                            </select>
                        </form>
                    </td>
                    
                    <td style="font-size: 12px; color: #555;"><?= htmlspecialchars($ticket['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align: center;">Немає жодного звернення.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

</body>
</html>