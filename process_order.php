<?php
session_start(); // Запуск сессии для работы с данными пользователя
error_reporting(E_ALL); // Включение отображения всех ошибок
ini_set('display_errors', 1); // Отображение ошибок на экран

require 'db.php'; // Подключение к базе данных

header('Content-Type: application/json'); // Установка заголовка для возврата JSON


// Проверка авторизации пользователя
if (!isset($_SESSION['user_id']) && !isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'message' => 'Требуется авторизация']); // Возврат ошибки, если пользователь не авторизован
    exit;
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : $_SESSION['user']['id']; // Получение ID пользователя из сессии


$data = json_decode(file_get_contents('php://input'), true); // Получение данных из тела запроса (JSON)

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Неверный формат данных']); // Возврат ошибки, если данные не переданы или имеют неверный формат
    exit;
}


$required = ['service_id', 'phone', 'address', 'cardNumber', 'expiryDate', 'cvv']; // Список обязательных полей
foreach ($required as $field) {
    if (empty($data[$field])) {
        echo json_encode(['success' => false, 'message' => "Не заполнено поле: $field"]); // Возврат ошибки, если какое-либо обязательное поле пустое
        exit;
    }
}


// Валидация телефона
if (!preg_match('/^\+?\d{10,15}$/', $data['phone'])) {
    echo json_encode(['success' => false, 'message' => 'Неверный формат телефона']); // Возврат ошибки при неверном формате телефона
    exit;
}

// Валидация номера карты
if (!preg_match('/^\d{16}$/', $data['cardNumber'])) {
    echo json_encode(['success' => false, 'message' => 'Неверный номер карты']); // Возврат ошибки при неверном номере карты
    exit;
}

// Валидация срока действия карты
if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $data['expiryDate'])) {
    echo json_encode(['success' => false, 'message' => 'Неверный срок действия карты']); // Возврат ошибки при неверном сроке действия карты
    exit;
}

// Валидация CVV кода
if (!preg_match('/^\d{3}$/', $data['cvv'])) {
    echo json_encode(['success' => false, 'message' => 'Неверный CVV код']); // Возврат ошибки при неверном CVV коде
    exit;
}

try {
    // Поиск услуги в базе данных по ID
    $stmt = $pdo->prepare("SELECT price FROM services WHERE id = ?");
    $stmt->execute([$data['service_id']]);
    $service = $stmt->fetch();

    if (!$service) {
        echo json_encode(['success' => false, 'message' => 'Услуга не найдена']); // Возврат ошибки, если услуга не существует
        exit;
    }

    // Добавление нового заказа в таблицу orders
    $stmt = $pdo->prepare("
        INSERT INTO orders (user_id, service_id, phone, address, card_number, expiry_date, cvv, price)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $success = $stmt->execute([
        $user_id,
        $data['service_id'],
        htmlspecialchars($data['phone']), // Защита от XSS
        htmlspecialchars($data['address']), // Защита от XSS
        htmlspecialchars($data['cardNumber']), // Защита от XSS
        htmlspecialchars($data['expiryDate']), // Защита от XSS
        htmlspecialchars($data['cvv']), // Защита от XSS
        $service['price']
    ]);

    // Возврат результата операции
    echo json_encode([
        'success' => $success && $stmt->rowCount() > 0, // Проверка успешности выполнения запроса
        'message' => $success ? 'Заказ успешно создан' : 'Ошибка при создании заказа'
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка базы данных: ' . $e->getMessage()]); // Обработка ошибок базы данных
}
?>