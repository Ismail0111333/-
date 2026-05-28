<?php
require_once 'script.php';
$cars = getAllCars();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoWorld - Список автомобилей</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; }
        header { background: #212529; color: white; padding: 15px 20px; }
        header a { color: white; text-decoration: none; margin: 0 10px; font-size: 16px; }
        .container { max-width: 1100px; margin: 0 auto; padding: 20px; }
        .card { border: 1px solid #ddd; border-radius: 10px; padding: 15px; margin: 10px 0; }
        .card img { width: 100%; height: 220px; object-fit: cover; border-radius: 8px; }
        .btn { display: inline-block; padding: 8px 15px; margin: 5px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; }
        .btn-success { background: #198754; color: white; }
        .btn-primary { background: #0d6efd; color: white; }
        .row { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
        .col-md-4 { width: calc(33.333% - 20px); min-width: 280px; }
        .badge { background: red; color: white; border-radius: 50%; padding: 2px 7px; font-size: 12px; position: relative; top: -10px; }
        footer { background: #212529; color: white; text-align: center; padding: 20px; margin-top: 40px; }
        .text-center { text-align: center; }
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
        <h2 class="text-center">Модельный ряд современных автомобилей</h2>
        <div class="row">
            <?php foreach ($cars as $car): ?>
            <div class="col-md-4">
                <div class="card">
                    <img src="<?= htmlspecialchars($car['image']) ?>" alt="<?= htmlspecialchars($car['name']) ?>">
                    <h3><?= htmlspecialchars($car['name']) ?></h3>
                    <p><?= htmlspecialchars($car['description']) ?></p>
                    <p style="font-size: 20px; font-weight: bold; color: #0d6efd;"><?= number_format($car['price'], 0, '', ' ') ?> ₽</p>
                    <button class="btn btn-success" onclick="addToCart(<?= $car['id'] ?>, '<?= htmlspecialchars($car['name']) ?>', <?= $car['price'] ?>)">🛒 Купить</button>
                    <a href="item.php?id=<?= $car['id'] ?>" class="btn btn-primary">Подробнее</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 AutoWorld. Все права защищены.</p>
    </footer>

    <script>
        function addToCart(id, name, price) {
            let cart = JSON.parse(localStorage.getItem('autoworld_cart')) || [];
            const existingItem = cart.find(item => item.id === id);
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({ id: id, name: name, price: price, quantity: 1 });
            }
            localStorage.setItem('autoworld_cart', JSON.stringify(cart));
            updateCartCount();
            alert('✅ ' + name + ' добавлен в корзину!');
        }
        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('autoworld_cart')) || [];
            const count = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('cartCount').textContent = count;
        }
        document.addEventListener('DOMContentLoaded', updateCartCount);
    </script>
</body>
</html>