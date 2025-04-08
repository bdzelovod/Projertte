<?php
require 'db.php'; // Подключение к базе данных
session_start(); // Запуск сессии для работы с данными пользователя

// Проверка, что запрос отправлен методом POST (форма регистрации)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(htmlspecialchars($_POST['username'])); // Очистка и получение имени пользователя
    $email = trim(htmlspecialchars($_POST['email'])); // Очистка и получение email
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Хэширование пароля для безопасного хранения

    try {
        // Проверка, существует ли пользователь с таким email в базе данных
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Этот email уже зарегистрирован!"; // Сообщение об ошибке, если email занят
            header("Location: index.php"); // Перенаправление на главную страницу
            exit();
        }

        // Добавление нового пользователя в базу данных
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password]);
        
        // Сохранение данных нового пользователя в сессию
        $_SESSION['user_id'] = $pdo->lastInsertId(); // Получение ID нового пользователя
        $_SESSION['user'] = [
            'id' => $_SESSION['user_id'],
            'username' => $username,
            'email' => $email
        ];
        
        $_SESSION['success'] = "Успешная регистрация!"; // Сообщение об успешной регистрации
        header("Location: index.php"); // Перенаправление на главную страницу
        exit();
        
    } catch (PDOException $e) {
        // Обработка ошибок базы данных
        $_SESSION['error'] = "Ошибка регистрации: " . $e->getMessage();
        header("Location: index.php"); // Перенаправление на главную страницу
        exit();
    }
}
?>