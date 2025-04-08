<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8"> <!-- Установка кодировки UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Адаптация под мобильные устройства -->
    <title>Главная - ЧистоБобр</title> <!-- Заголовок страницы -->
    <link rel="stylesheet" href="style.css"> <!-- Подключение CSS для стилей -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script> <!-- Иконки Ionicons (модульная версия) -->
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script> <!-- Иконки Ionicons (версия для старых браузеров) -->
</head>
<body>

    <?php
    session_start(); // Запуск сессии для работы с данными пользователя

    // Функция для отображения уведомлений (успех или ошибка)
    function displayNotification($type, $message) {
        echo '<div class="notification '.$type.' fade-in">'.htmlspecialchars($message).'</div>';
    }

    // Отображение уведомления об успехе
    if (isset($_SESSION['success'])) {
        displayNotification('success', $_SESSION['success']);
        unset($_SESSION['success']); // Очистка сообщения после отображения
    }

    // Отображение уведомления об успешной авторизации
    if (isset($_SESSION['login_success'])) {
        displayNotification('success', $_SESSION['login_success']);
        unset($_SESSION['login_success']); // Очистка сообщения после отображения
    }

    // Отображение уведомления об ошибке
    if (isset($_SESSION['error'])) {
        displayNotification('error', $_SESSION['error']);
        unset($_SESSION['error']); // Очистка ошибки после отображения
    }
    ?>

    <!-- Шапка сайта -->
    <header>
        <div class="container">
            <h2 class="logo">ЧистоБобр</h2> <!-- Логотип компании -->
            <nav class="navigation"> <!-- Навигационное меню -->
                <a href="index.php">Главная</a> <!-- Ссылка на главную страницу -->
                <a href="services.php">Услуги</a> <!-- Ссылка на страницу услуг -->
                <a href="about.php">О нас</a> <!-- Ссылка на страницу "О нас" -->
                <a href="news.php">Новости</a> <!-- Ссылка на страницу новостей -->

                <!-- Проверка авторизации пользователя -->
                <?php if (isset($_SESSION['user'])): ?>
                    <?php 
                    // Проверка роли пользователя (администратор или нет)
                    $isAdmin = isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
                    if ($isAdmin): ?>
                        <a href="admin.php" class="admin-link">Админ-панель</a> <!-- Ссылка на админ-панель -->
                    <?php endif; ?>
                    <a href="logout.php" class="btnLogout">Выйти</a> <!-- Ссылка на выход из системы -->
                <?php else: ?>
                    <button class="btnLogin-popup">Войти</button> <!-- Кнопка для входа -->
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <!-- Основной контент страницы -->
    <main class="container">

    </main>

    <!-- Всплывающее окно для входа и регистрации -->
    <div class="wrapper">
        <span class="icon-close"><ion-icon name="close-outline"></ion-icon></span> <!-- Кнопка закрытия окна -->

        <!-- Форма входа -->
        <div class="form-box login">
            <h2>Вход</h2>
            <form action="login.php" method="POST">
                <div class="input-box">
                    <span class="icon"><ion-icon name="mail"></ion-icon></span> <!-- Иконка почты -->
                    <input type="email" name="email" required placeholder=" "> <!-- Поле ввода email -->
                    <label>Email</label> <!-- Подпись к полю email -->
                </div>
                <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span> <!-- Иконка замка -->
                    <input type="password" name="password" required placeholder=" "> <!-- Поле ввода пароля -->
                    <label>Пароль</label> <!-- Подпись к полю пароля -->
                </div>
                <div class="remember-forgot">
                    <label><input type="checkbox"> Запомнить меня</label> <!-- Чекбокс "Запомнить меня" -->
                    <a href="#">Забыли пароль?</a> <!-- Ссылка на восстановление пароля -->
                </div>
                <button type="submit" class="btn">Войти</button> <!-- Кнопка отправки формы -->
                <div class="login-register">
                    <p>Нет аккаунта? <a href="#" class="register-link">Зарегистрироваться</a></p> <!-- Ссылка на регистрацию -->
                </div>
            </form>
        </div>

        <!-- Форма регистрации -->
        <div class="form-box register">
            <h2>Регистрация</h2>
            <form action="register.php" method="POST">
                <div class="input-box">
                    <span class="icon"><ion-icon name="person"></ion-icon></span> <!-- Иконка пользователя -->
                    <input type="text" name="username" required placeholder=" "> <!-- Поле ввода имени пользователя -->
                    <label>Имя пользователя</label> <!-- Подпись к полю имени пользователя -->
                </div>
                <div class="input-box">
                    <span class="icon"><ion-icon name="mail"></ion-icon></span> <!-- Иконка почты -->
                    <input type="email" name="email" required placeholder=" "> <!-- Поле ввода email -->
                    <label>Email</label> <!-- Подпись к полю email -->
                </div>
                <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span> <!-- Иконка замка -->
                    <input type="password" name="password" required placeholder=" "> <!-- Поле ввода пароля -->
                    <label>Пароль</label> <!-- Подпись к полю пароля -->
                </div>
                <div class="remember-forgot">
                    <label><input type="checkbox" required> Я согласен с условиями</label> <!-- Чекбокс согласия с условиями -->
                </div>
                <button type="submit" class="btn">Зарегистрироваться</button> <!-- Кнопка отправки формы -->
                <div class="login-register">
                    <p>Уже есть аккаунт? <a href="#" class="login-link">Войти</a></p> <!-- Ссылка на вход -->
                </div>
            </form>
        </div>
    </div>

    <script src="script.js"></script> <!-- Подключение JavaScript для интерактивности -->
</body>
</html>