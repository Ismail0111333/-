<?php
require_once 'script.php';
$allCars = getAllCars();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoWorld - Запись на тест-драйв</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; }
        header { background: #212529; color: white; padding: 15px 20px; }
        header a { color: white; text-decoration: none; margin: 0 10px; font-size: 16px; }
        .container { max-width: 900px; margin: 0 auto; padding: 20px; }
        .badge { background: red; color: white; border-radius: 50%; padding: 2px 7px; font-size: 12px; position: relative; top: -10px; }
        footer { background: #212529; color: white; text-align: center; padding: 20px; margin-top: 40px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 14px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #343a40; color: white; }
    </style>
</head>
<body>
    <header>
        <div style="display: flex; justify-content: space-between; align-items: center; max-width: 1100px; margin: 0 auto;">
            <span style="font-size: 24px; font-weight: bold;"> AutoWorld</span>
            <div style="display: flex; align-items: center;">
                <a href="index.php">Главная</a>
                <a href="list.php">Автомобили</a>
                <a href="form.php">Тест-драйв</a>
                <a href="item.php">О модели</a>
                <a href="cart.php" style="font-size: 24px;">🛒 <span id="cartCount" class="badge">0</span></a>
            </div>
        </div>
    </header>

    <div class="container">
        <h2 style="text-align: center;">📝 Запись на тест-драйв</h2>

        <div id="successMessage" class="alert alert-success" style="display: none;"></div>
        <div id="errorMessage" class="alert alert-danger" style="display: none;"></div>

        <form id="testDriveForm">
            <div class="mb-3">
                <label class="form-label">Ваше имя *</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email *</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Телефон *</label>
                <input type="tel" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Автомобиль *</label>
                <select class="form-select" id="car" name="car_model" required>
                    <option value="">— Выберите —</option>
                    <?php foreach ($allCars as $car): ?>
                        <option value="<?= htmlspecialchars($car['name']) ?>">
                            <?= htmlspecialchars($car['name']) ?> - <?= number_format($car['price'], 0, '', ' ') ?> ₽
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Дата *</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Время *</label>
                <input type="time" class="form-control" id="time" name="time" required>
            </div>
           <button type="submit" class="btn btn-primary w-100" id="submitBtn">✉️ Записаться</button>
        </form>

        <hr>
        <div class="d-flex justify-content-between align-items-center">
            <h3>📋 Список заявок</h3>
            <button class="btn btn-outline-primary btn-sm" onclick="loadRequests()">🔄 Обновить</button>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Имя</th><th>Email</th><th>Телефон</th><th>Автомобиль</th><th>Дата</th><th>Время</th><th>Создано</th>
                    </tr>
                </thead>
                <tbody id="requestsTableBody">
                    <tr><td colspan="7" class="text-center">Загрузка...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Модальное окно успеха -->
    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">✅ Заявка отправлена!</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <p style="font-size: 48px;"></p>
                    <h5>Спасибо за заявку!</h5>
                    <p>Наш менеджер свяжется с вами в ближайшее время.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <footer><p>&copy; 2026 AutoWorld. Все права защищены.</p></footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
    <script>
        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('autoworld_cart')) || [];
            document.getElementById('cartCount').textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
        }
        document.addEventListener('DOMContentLoaded', updateCartCount);
    </script>
</body>
</html>