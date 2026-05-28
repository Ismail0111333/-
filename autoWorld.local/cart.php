<?php
require_once 'script.php';
$cars = getAllCars();
$carsData = [];
foreach ($cars as $car) {
    $carsData[$car['id']] = ['name' => $car['name'], 'price' => $car['price']];
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoWorld - Корзина</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; }
        header { background: #212529; color: white; padding: 15px 20px; }
        header a { color: white; text-decoration: none; margin: 0 10px; font-size: 16px; }
        .container { max-width: 900px; margin: 0 auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #343a40; color: white; }
        .btn { display: inline-block; padding: 8px 15px; margin: 5px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-success { background: #198754; color: white; }
        .btn-outline { background: white; color: #6c757d; border: 1px solid #6c757d; }
        .badge { background: red; color: white; border-radius: 50%; padding: 2px 7px; font-size: 12px; position: relative; top: -10px; }
        footer { background: #212529; color: white; text-align: center; padding: 20px; margin-top: 40px; }
        .text-center { text-align: center; }
        .d-none { display: none; }
        .text-end { text-align: right; }
    </style>
</head>
<body>
    <header>
        <div style="display: flex; justify-content: space-between; align-items: center; max-width: 900px; margin: 0 auto;">
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
        <h2 class="text-center">🛒 Моя корзина</h2>
        <table>
            <thead>
                <tr><th>Автомобиль</th><th>Цена</th><th>Количество</th><th>Сумма</th><th>Действие</th></tr>
            </thead>
            <tbody id="cartItems"><tr><td colspan="5" class="text-center">Загрузка...</td></tr></tbody>
            <tfoot>
                <tr><th colspan="3" class="text-end">Итого:</th><th id="cartTotal">0 ₽</th><th></th></tr>
            </tfoot>
        </table>
        <div class="text-center">
            <a href="list.php" class="btn btn-outline">← Продолжить покупки</a>
            <button class="btn btn-danger" id="clearCartBtn">Очистить корзину</button>
            <button class="btn btn-success" id="checkoutBtn">Оформить заказ</button>
        </div>
        <div id="emptyCartMsg" class="text-center d-none">
            <p style="font-size: 48px;">🛒</p>
            <h3>Корзина пуста</h3>
            <p>Добавьте автомобили из <a href="list.php">списка</a></p>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 AutoWorld. Все права защищены.</p>
    </footer>

    <script>
        const carsData = <?php echo json_encode($carsData); ?>;
        const CART_STORAGE_KEY = 'autoworld_cart';
        let cart = [];

        function loadCart() {
            const saved = localStorage.getItem(CART_STORAGE_KEY);
            cart = saved ? JSON.parse(saved) : [];
            renderCart();
            updateCartCount();
        }

        function saveCart() {
            localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
            updateCartCount();
        }

        function updateCartCount() {
            const count = cart.reduce((sum, item) => sum + item.quantity, 0);
            const el = document.getElementById('cartCount');
            if (el) el.textContent = count;
        }

        function getCarInfo(id) { return carsData[id]; }

        function renderCart() {
            const tbody = document.getElementById('cartItems');
            const totalEl = document.getElementById('cartTotal');
            const emptyMsg = document.getElementById('emptyCartMsg');

            if (!cart.length) {
                tbody.innerHTML = '';
                emptyMsg.classList.remove('d-none');
                totalEl.textContent = '0 ₽';
                return;
            }

            emptyMsg.classList.add('d-none');
            let total = 0;
            let html = '';

            cart.forEach((item, i) => {
                const car = getCarInfo(item.id);
                if (!car) return;
                const sum = car.price * item.quantity;
                total += sum;
                html += `<tr>
                    <td>${car.name}</td>
                    <td>${car.price.toLocaleString('ru-RU')} ₽</td>
                    <td>
                        <button class="btn btn-outline" onclick="changeQuantity(${i}, -1)">−</button>
                        <span style="margin: 0 10px;">${item.quantity}</span>
                        <button class="btn btn-outline" onclick="changeQuantity(${i}, 1)">+</button>
                    </td>
                    <td><b>${sum.toLocaleString('ru-RU')} ₽</b></td>
                    <td><button class="btn btn-danger" onclick="removeFromCart(${i})">Удалить</button></td>
                </tr>`;
            });

            tbody.innerHTML = html;
            totalEl.textContent = total.toLocaleString('ru-RU') + ' ₽';
        }

        function changeQuantity(index, delta) {
            const newQty = cart[index].quantity + delta;
            if (newQty <= 0) removeFromCart(index);
            else { cart[index].quantity = newQty; saveCart(); renderCart(); }
        }

        function removeFromCart(index) {
            if (confirm('Удалить?')) { cart.splice(index, 1); saveCart(); renderCart(); }
        }

        document.getElementById('clearCartBtn').addEventListener('click', () => {
            if (cart.length && confirm('Очистить корзину?')) { cart = []; saveCart(); renderCart(); }
        });

        document.getElementById('checkoutBtn').addEventListener('click', () => {
            if (!cart.length) { alert('Корзина пуста!'); return; }
            alert('✅ Заказ оформлен! Менеджер свяжется с вами.');
            cart = []; saveCart(); renderCart();
        });

        document.addEventListener('DOMContentLoaded', loadCart);
    </script>
</body>
</html>