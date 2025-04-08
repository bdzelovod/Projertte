<?php
session_start(); // Запуск сессии для работы с данными пользователя
require 'db.php'; // Подключение к базе данных

// Проверка прав доступа: только администратор может просматривать эту страницу
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    $_SESSION['error'] = 'Доступ запрещен!'; // Установка сообщения об ошибке
    header("Location: index.php"); // Перенаправление на главную страницу
    exit();
}

// Обработка удаления пользователя
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $user_id = (int)$_POST['delete']; // ID пользователя для удаления (преобразование в целое число)

    // Проверка существования пользователя в базе данных
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    if ($stmt->fetchColumn() === 0) {
        $_SESSION['error'] = 'Пользователь не найден!'; // Сообщение об ошибке, если пользователь не существует
    } else {
        // Удаление всех отзывов, связанных с пользователем
        $stmt_reviews = $pdo->prepare("DELETE FROM reviews WHERE user_id = ?");
        $stmt_reviews->execute([$user_id]);

        // Удаление самого пользователя из базы данных
        $stmt_user = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt_user->execute([$user_id]);
        $_SESSION['success'] = 'Пользователь удален!'; // Сообщение об успешном удалении
    }
    header("Location: admin-users.php"); // Перенаправление обратно на страницу управления пользователями
    exit();
}

// Обработка обновления данных пользователя
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $user_id = (int)$_POST['user_id']; // ID пользователя для обновления
    $username = htmlspecialchars($_POST['username']); // Новое имя пользователя (защищено от XSS)
    $email = htmlspecialchars($_POST['email']); // Новый email (защищен от XSS)
    $role = $_POST['role']; // Новая роль пользователя

    // Проверка существования пользователя в базе данных
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        $_SESSION['error'] = 'Пользователь не найден!'; // Сообщение об ошибке, если пользователь не существует
    } else {
        // Проверка уникальности username и email
        $stmt_check = $pdo->prepare("SELECT * FROM users 
                                    WHERE (username = ? OR email = ?) 
                                    AND id != ?");
        $stmt_check->execute([$username, $email, $user_id]);
        if ($stmt_check->rowCount() > 0) {
            $_SESSION['error'] = 'Email или username уже заняты!'; // Сообщение об ошибке при дублировании данных
        } else {
            // Обновление данных пользователя в базе данных
            $stmt_update = $pdo->prepare("UPDATE users 
                                         SET username = ?, email = ?, role = ? 
                                         WHERE id = ?");
            $stmt_update->execute([$username, $email, $role, $user_id]);
            $_SESSION['success'] = 'Данные обновлены!'; // Сообщение об успешном обновлении
        }
    }
    header("Location: admin-users.php"); // Перенаправление обратно на страницу управления пользователями
    exit();
}

// Загрузка списка пользователей из базы данных
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC); // Получение всех пользователей в виде массива
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8"> <!-- Установка кодировки UTF-8 -->
    <title>Управление пользователями</title> <!-- Заголовок страницы -->
    <link rel="stylesheet" href="styleAdmin.css"> <!-- Подключение CSS для стилей -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Иконки Font Awesome -->
</head>
<body>
    <header>
        <h2 class="logo">ЧистоБобр (Админ)</h2> <!-- Логотип админ-панели -->
        <nav class="admin-nav"> <!-- Навигационное меню -->
            <a href="admin-users.php">Пользователи</a> <!-- Ссылка на текущую страницу -->
            <a href="admin-reviews.php">Отзывы</a> <!-- Ссылка на управление отзывами -->
            <a href="admin-services.php">Услуги</a> <!-- Ссылка на управление услугами -->
            <a href="admin-news.php">Новости</a> <!-- Ссылка на управление новостями -->
            <a href="logout.php">Logout</a> <!-- Ссылка на выход из системы -->
        </nav>
    </header>

    <!-- Кнопка возврата в админ-панель -->
    <div class="admin-back-btn">
        <a href="admin.php" class="btn-back">← Назад в админ-панель</a>
    </div>
    <!-- Кнопка перехода на главную страницу -->
    <a href="index.php" class="btn-home">
        <i class="fas fa-home"></i> На главную
    </a>

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

    <!-- Таблица со списком пользователей -->
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th> <!-- Колонка ID пользователя -->
                <th>Имя</th> <!-- Колонка имени пользователя -->
                <th>Email</th> <!-- Колонка email -->
                <th>Роль</th> <!-- Колонка роли пользователя -->
                <th>Действия</th> <!-- Колонка действий -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td> <!-- ID пользователя -->
                    <td><?php echo htmlspecialchars($user['username']); ?></td> <!-- Имя пользователя (защищено от XSS) -->
                    <td><?php echo htmlspecialchars($user['email']); ?></td> <!-- Email (защищен от XSS) -->
                    <td><?php echo $user['role']; ?></td> <!-- Роль пользователя -->
                    <td>
                        <!-- Форма для обновления данных пользователя -->
                        <form action="admin-users.php" method="POST">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>"> <!-- Скрытое поле с ID пользователя -->
                            <input type="text" name="username" 
                                   value="<?php echo htmlspecialchars($user['username']); ?>" 
                                   placeholder="Имя" required> <!-- Поле для имени -->
                            <input type="email" name="email" 
                                   value="<?php echo htmlspecialchars($user['email']); ?>" 
                                   placeholder="Email" required> <!-- Поле для email -->
                            <select name="role"> <!-- Выбор роли -->
                                <option value="user" <?php if ($user['role'] === 'user') echo 'selected'; ?>>Пользователь</option>
                                <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Админ</option>
                            </select>
                            <button type="submit" name="update" class="update-btn">Сохранить</button> <!-- Кнопка сохранения -->
                        </form>
                        
                        <!-- Форма для удаления пользователя -->
                        <form action="admin-users.php" method="POST">
                            <input type="hidden" name="delete" value="<?php echo $user['id']; ?>"> <!-- Скрытое поле с ID пользователя -->
                            <button type="submit" class="delete-btn" 
                                    onclick="return confirm('Удалить пользователя?')"> <!-- Подтверждение удаления -->
                                <i class="fas fa-trash"></i> <!-- Иконка корзины -->
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>