<?php
require_once 'script.php';

$carId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$car = getCarById($carId);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoWorld - <?= $car ? htmlspecialchars($car['name']) : 'Ошибка' ?></title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; }
        header { background: #212529; color: white; padding: 15px 20px; }
        header a { color: white; text-decoration: none; margin: 0 10px; font-size: 16px; }
        .container { max-width: 1100px; margin: 0 auto; padding: 20px; }
        .badge { background: red; color: white; border-radius: 50%; padding: 2px 7px; font-size: 12px; position: relative; top: -10px; }
        footer { background: #212529; color: white; text-align: center; padding: 20px; margin-top: 40px; }
        .btn { display: inline-block; padding: 10px 20px; margin: 10px 5px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; font-size: 16px; }
        .btn-success { background: #198754; color: white; }
        .btn-primary { background: #0d6efd; color: white; }
        .btn-outline { background: white; color: #6c757d; border: 1px solid #6c757d; }
        img { max-width: 100%; border-radius: 10px; }
        .row { display: flex; flex-wrap: wrap; gap: 30px; }
        .col-md-6 { flex: 1; min-width: 300px; }
        li { padding: 10px; border-bottom: 1px solid #eee; list-style: none; }
        ul { padding: 0; }
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
        <?php if ($car): ?>
            <div class="row">
                <div class="col-md-6">
                    <img src="<?= htmlspecialchars($car['image']) ?>" alt="<?= htmlspecialchars($car['name']) ?>">
                </div>
                <div class="col-md-6">
                    <h2><?= htmlspecialchars($car['name']) ?></h2>
                    <p style="color: #6c757d;"><?= htmlspecialchars($car['subtitle']) ?></p>
                    <p><?= htmlspecialchars($car['description']) ?></p>
                    <p style="font-size: 24px; font-weight: bold; color: #0d6efd;">Цена: <?= number_format($car['price'], 0, '', ' ') ?> ₽</p>
                    
                    <h3>Характеристики:</h3>
                    <ul>
                        <?php 
                        $specs = json_decode($car['specs'], true);
                        if ($specs && is_array($specs)):
                            foreach ($specs as $spec): 
                        ?>
                            <li><?= htmlspecialchars($spec) ?></li>
                        <?php endforeach; endif; ?>
                    </ul>
                    
                    <button class="btn btn-success" onclick="addToCart(<?= $car['id'] ?>, '<?= htmlspecialchars($car['name']) ?>', <?= $car['price'] ?>)">🛒 Купить</button>
                    <a href="form.php?car=<?= urlencode($car['name']) ?>" class="btn btn-primary">Записаться на тест-драйв</a>
                    <a href="list.php" class="btn btn-outline">← Назад к списку</a>
                </div>
            </div>
        <?php else: ?>
            <div style="text-align: center;">
                <h2>Автомобиль не найден</h2>
                <p>Вернитесь к <a href="list.php">списку автомобилей</a>.</p>
            </div>
        <?php endif; ?>
    </div>

    <footer><p>&copy; 2026 AutoWorld. Все права защищены.</p></footer>

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