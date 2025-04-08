<?php
session_start(); // Запуск сессии для работы с данными пользователя
require 'db.php'; // Подключение к базе данных

// Проверка прав доступа: только администратор может просматривать эту страницу
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    $_SESSION['error'] = 'Доступ запрещен!'; // Установка сообщения об ошибке
    header("Location: index.php"); // Перенаправление на главную страницу
    exit();
}
?>

<!-- Отображение уведомлений об успехе -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="notification success"><?php echo $_SESSION['success']; ?></div>
<?php unset($_SESSION['success']); ?> <!-- Очистка сообщения после отображения -->
<?php endif; ?>

<!-- Отображение уведомлений об ошибках -->
<?php if (isset($_SESSION['error'])): ?>
    <div class="notification error"><?php echo $_SESSION['error']; ?></div>
<?php unset($_SESSION['error']); ?> <!-- Очистка ошибки после отображения -->
<?php endif; ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8"> <!-- Установка кодировки UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Адаптация под мобильные устройства -->
    <title>Админ-панель — ЧистоБобр</title> <!-- Заголовок страницы -->
    <link rel="stylesheet" href="styleAdmin.css"> <!-- Подключение CSS для стилей -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Иконки Font Awesome -->
</head>
<body>
    <header>
        <h2 class="logo">ЧистоБобр (Админ)</h2> <!-- Логотип админ-панели -->
        <nav class="admin-nav"> <!-- Навигационное меню -->
            <a href="admin-users.php">Управление пользователями</a> <!-- Ссылка на управление пользователями -->
            <a href="admin-reviews.php">Отзывы</a> <!-- Ссылка на управление отзывами -->
            <a href="admin-news.php">Новости</a> <!-- Ссылка на управление новостями -->
            <a href="admin-services.php">Услуги</a> <!-- Ссылка на управление услугами -->
            <a href="logout.php">Выйти</a> <!-- Ссылка на выход из системы -->
        </nav>
    </header>

    <div class="admin-content">
        <!-- Отображение уведомлений об успехе -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="notification success"><?php echo $_SESSION['success']; ?></div>
        <?php unset($_SESSION['success']); ?> <!-- Очистка сообщения после отображения -->
        <?php endif; ?>

        <!-- Отображение уведомлений об ошибках -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="notification error"><?php echo $_SESSION['error']; ?></div>
        <?php unset($_SESSION['error']); ?> <!-- Очистка ошибки после отображения -->
        <?php endif; ?>

        <h2>Добро пожаловать в админ-панель!</h2> <!-- Приветственное сообщение -->
        <p>Здесь вы можете управлять контентом и пользователями.</p> <!-- Описание функционала админ-панели -->
    </div>
</body>
</html>