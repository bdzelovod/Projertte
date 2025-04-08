<?php
require 'db.php'; // Подключение к базе данных
session_start(); // Запуск сессии для работы с данными пользователя

// Проверка, что запрос отправлен методом POST (форма входа)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim(htmlspecialchars($_POST['email'])); // Очистка и получение email из формы
    $password = $_POST['password']; // Получение пароля из формы

    try {
        // Поиск пользователя в базе данных по email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Проверка существования пользователя и верности пароля
        if ($user && password_verify($password, $user['password'])) {
            // Установка данных пользователя в сессию
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role'] ?? 'user' // Если роль не указана, устанавливаем значение 'user'
            ];
            
            $_SESSION['success'] = "Вы успешно вошли!"; // Сообщение об успешном входе
            header("Location: index.php"); // Перенаправление на главную страницу
            exit();
        } else {
            // Сообщение об ошибке при неверных данных
            $_SESSION['error'] = "Неверный email или пароль";
            header("Location: index.php"); // Перенаправление на главную страницу
            exit();
        }
    } catch (PDOException $e) {
        // Обработка ошибок базы данных
        $_SESSION['error'] = "Ошибка входа: " . $e->getMessage();
        header("Location: index.php"); // Перенаправление на главную страницу
        exit();
    }
}
?>