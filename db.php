<?php

// Настройки подключения к базе данных
$host = 'localhost'; // Хост базы данных (обычно localhost)
$dbname = 'db_users'; // Имя базы данных
$username = 'root'; // Имя пользователя для подключения к базе данных
$password = ''; // Пароль пользователя (пустой для root в локальной среде)

try {
    // Создание подключения к базе данных через PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Установка режима обработки ошибок: выбрасывать исключения при ошибках
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Установка режима извлечения данных по умолчанию: ассоциативный массив
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Обработка ошибок подключения к базе данных
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
?>