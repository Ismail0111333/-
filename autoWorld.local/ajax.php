<?php
require_once 'script.php';

header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// GET — получить список всех заявок
if ($method === 'GET' && $action === 'get_requests') {
    $requests = getAllTestDriveRequests();
    echo json_encode(['success' => true, 'data' => $requests]);
    exit;
}

// POST — сохранить новую заявку
if ($method === 'POST' && $action === 'save_request') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $car_model = trim($_POST['car_model'] ?? '');
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';

    $errors = [];
    if (empty($name)) $errors[] = 'Введите имя';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Введите корректный email';
    if (empty($phone)) $errors[] = 'Введите телефон';
    if (empty($car_model)) $errors[] = 'Выберите автомобиль';
    if (empty($date)) $errors[] = 'Выберите дату';
    if (empty($time)) $errors[] = 'Выберите время';

    if (!empty($errors)) {
        echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
        exit;
    }

    $result = saveTestDriveRequest([
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'car_model' => $car_model,
        'date' => $date,
        'time' => $time
    ]);

    if ($result === true) {
        echo json_encode(['success' => true, 'message' => '✅ Заявка успешно отправлена!']);
    } else {
        echo json_encode(['success' => false, 'message' => '❌ ' . $result]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Неверный запрос']);