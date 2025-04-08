<?php
require 'db.php'; // Подключение к базе данных
session_start(); // Запуск сессии для работы с данными пользователя

// Включение отображения ошибок
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Проверка метода запроса (должен быть POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Проверка авторизации пользователя
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = 'Авторизуйтесь для отправки отзыва.'; // Сообщение об ошибке
        header("Location: index.php"); // Перенаправление на главную страницу
        exit();
    }

    $user_id = $_SESSION['user']['id']; // ID авторизованного пользователя
    $username = htmlspecialchars($_POST['username']); // Имя пользователя (защищено от XSS)
    $text = htmlspecialchars($_POST['text']); // Текст отзыва (защищен от XSS)
    $rating = (int) $_POST['rating']; // Оценка (преобразована в целое число)

    // Проверка длины текста отзыва
    if (strlen($text) > 100) {
        $_SESSION['error'] = 'Отзыв не должен превышать 100 символов!'; // Сообщение об ошибке
        header("Location: about.php"); // Перенаправление на страницу отзывов
        exit();
    }

    // Проверка обязательных полей и корректности оценки
    if (empty($text) || $rating < 1 || $rating > 5) {
        $_SESSION['error'] = 'Поля "Текст" и "Оценка" обязательны!'; // Сообщение об ошибке
        header("Location: about.php"); // Перенаправление на страницу отзывов
        exit();
    }

    try {
        // Подготовка SQL-запроса для добавления отзыва в базу данных
        $stmt = $pdo->prepare("INSERT INTO reviews (user_id, text, rating) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $text, $rating]); // Выполнение запроса с параметрами
        $_SESSION['success'] = 'Спасибо за отзыв!'; // Сообщение об успешной отправке
    } catch (Exception $e) {
        $_SESSION['error'] = 'Ошибка: ' . $e->getMessage(); // Сообщение об ошибке при выполнении запроса
    }

    header("Location: about.php"); // Перенаправление на страницу отзывов
    exit();
}
?>