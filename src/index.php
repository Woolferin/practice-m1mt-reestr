<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Електронний реєстр звернень</title>
    <style>
        body { font-family: sans-serif; margin: 20px; background-color: #f9f9f9; }
        .form-container { background: white; padding: 20px; border-radius: 8px; max-width: 400px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        form { display: flex; flex-direction: column; gap: 12px; }
        input, textarea, button { padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px; }
        button { background-color: #007BFF; color: white; cursor: pointer; border: none; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Подання звернення з питань благоустрою</h2>
        <form action="process.php" method="POST">
            <label for="name">ПІБ:</label>
            <input type="text" id="name" name="name" required>

<label for="contact">Контактні дані (телефон/email):</label>
<input type="text" id="contact" name="contact" required 
       oninvalid="this.setCustomValidity('Будь ласка, заповніть це поле')" 
       oninput="this.setCustomValidity('')">
            <label for="address">Адреса проблеми:</label>
            <input type="text" id="address" name="address" required>

            <label for="description">Опис проблеми:</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <button type="submit">Відправити звернення</button>
        </form>
    </div>
</body>
</html>
