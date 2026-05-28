<?php
/**
 * script.php - Подключение к базе данных и работа с данными
 * Лабораторная работа №4
 */

// ========== НАСТРОЙКИ ПОДКЛЮЧЕНИЯ К БД ==========
define('DB_HOST', 'MySQL-8.0');    // Сервер БД (исправлено)
define('DB_USER', 'root');          // Пользователь (по умолчанию root)
define('DB_PASS', '');              // Пароль (по умолчанию пустой)
define('DB_NAME', 'autoworld_db');  // Имя базы данных

/**
 * Функция подключения к базе данных
 * @return mysqli Объект подключения
 */
function getConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Проверка на ошибку подключения
    if ($conn->connect_error) {
        die("❌ Ошибка подключения к БД: " . $conn->connect_error);
    }
    
    // Устанавливаем кодировку UTF-8
    $conn->set_charset("utf8");
    
    return $conn;
}

/**
 * Получение всех автомобилей из базы данных
 * @return array Массив с автомобилями
 */
function getAllCars() {
    $conn = getConnection();
    
    // SQL-запрос: выбираем все автомобили, сортируем по ID
    $sql = "SELECT * FROM cars ORDER BY id";
    $result = $conn->query($sql);
    
    $cars = [];
    while ($row = $result->fetch_assoc()) {
        // Декодируем JSON-строку характеристик в массив
        $row['specs_array'] = json_decode($row['specs'], true);
        $cars[] = $row;
    }
    
    $conn->close();
    return $cars;
}

/**
 * Получение одного автомобиля по его ID
 * @param int $id ID автомобиля
 * @return array|null Данные автомобиля или null
 */
function getCarById($id) {
    $conn = getConnection();
    
    // SQL-запрос с параметром (защита от SQL-инъекций)
    $sql = "SELECT * FROM cars WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);  // "i" означает integer
    $stmt->execute();
    
    $result = $stmt->get_result();
    $car = $result->fetch_assoc();
    
    if ($car) {
        $car['specs_array'] = json_decode($car['specs'], true);
    }
    
    $stmt->close();
    $conn->close();
    
    return $car;
}

/**
 * Сохранение заявки на тест-драйв
 * @param array $data Данные из формы
 * @return bool|string true при успехе, текст ошибки при неудаче
 */
function saveTestDriveRequest($data) {
    $conn = getConnection();
    
    // SQL-запрос для вставки данных
    $sql = "INSERT INTO test_drive_requests (name, email, phone, car_model, date, time) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssss",  // s = string (6 строковых параметров)
        $data['name'],
        $data['email'],
        $data['phone'],
        $data['car_model'],
        $data['date'],
        $data['time']
    );
    
    if ($stmt->execute()) {
        $result = true;
    } else {
        $result = "Ошибка сохранения: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
    
    return $result;
}

/**
 * Получение всех заявок на тест-драйв (опционально, для админки)
 * @return array Массив заявок
 */
function getAllTestDriveRequests() {
    $conn = getConnection();
    
    $sql = "SELECT * FROM test_drive_requests ORDER BY created_at DESC";
    $result = $conn->query($sql);
    
    $requests = [];
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
    
    $conn->close();
    return $requests;
}
?>