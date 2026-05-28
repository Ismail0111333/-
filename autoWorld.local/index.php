<?php
require_once 'script.php';

$allCars = getAllCars();
$featuredCars = array_slice($allCars, 0, 3);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoWorld - Современные автомобили</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css">
    <link rel="stylesheet" href="custom.css">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
</head>
<body>
    <header class="bg-dark text-white py-3">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="fs-3 fw-bold"> AutoWorld</span>
                </div>
                <div class="d-flex align-items-center">
                    <nav>
                        <ul class="nav">
                            <li class="nav-item"><a href="index.php" class="nav-link text-white active">Главная</a></li>
                            <li class="nav-item"><a href="list.php" class="nav-link text-white">Автомобили</a></li>
                            <li class="nav-item"><a href="form.php" class="nav-link text-white">Тест-драйв</a></li>
                            <li class="nav-item"><a href="item.php" class="nav-link text-white">О модели</a></li>
                        </ul>
                    </nav>
                    <a href="cart.php" class="ms-3 text-white position-relative text-decoration-none">
                        🛒
                        <span id="cartCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem;">0</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="container my-5">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold">Добро пожаловать в мир современных автомобилей</h1>
                <p class="lead">Узнайте о последних новинках, технологиях и трендах в автопроме.</p>
            </div>
        </div>

        <div class="row mt-5">
            <h2 class="text-center mb-4">Популярные модели</h2>
            
            <?php foreach ($featuredCars as $car): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="<?= htmlspecialchars($car['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($car['name']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($car['name']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($car['description']) ?></p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-primary fs-5"><?= number_format($car['price'], 0, '', ' ') ?> ₽</span>
                            <button class="btn btn-success" onclick="addToCart(<?= $car['id'] ?>, '<?= htmlspecialchars($car['name']) ?>', <?= $car['price'] ?>)">
                                🛒 Купить
                            </button>
                        </div>
                        <a href="item.php?id=<?= $car['id'] ?>" class="btn btn-primary w-100">Подробнее</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-4">
        <div class="container">
            <p class="mb-0">&copy; 2026 AutoWorld. Все права защищены.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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