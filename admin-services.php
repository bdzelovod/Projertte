<?php
session_start(); // Запуск сессии для работы с данными пользователя
require 'db.php'; // Подключение к базе данных

// Проверка прав доступа: только администратор может просматривать эту страницу
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    $_SESSION['error'] = 'Доступ запрещен!'; // Установка сообщения об ошибке
    header("Location: index.php"); // Перенаправление на главную страницу
    exit();
}

// Обработка добавления новой услуги
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $title = htmlspecialchars($_POST['title']); // Получение названия услуги (защищено от XSS)
    $description = htmlspecialchars($_POST['description']); // Получение описания услуги (защищено от XSS)
    $price = (float)$_POST['price']; // Получение цены услуги (преобразование в число)

    // Валидация ввода: проверка, что все поля заполнены
    if (empty($title) || empty($description) || empty($price)) {
        $_SESSION['error'] = 'Название, описание и цена обязательны!'; // Сообщение об ошибке
    } else {
        try {
            // Добавление новой услуги в базу данных
            $stmt = $pdo->prepare("INSERT INTO services (title, description, price) VALUES (?, ?, ?)");
            $stmt->execute([$title, $description, $price]);
            $_SESSION['success'] = 'Услуга добавлена!'; // Сообщение об успехе
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Ошибка БД: ' . $e->getMessage(); // Сообщение об ошибке базы данных
        }
    }
    header("Location: admin-services.php"); // Перенаправление обратно на страницу услуг
    exit();
}

// Обработка удаления услуги
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $service_id = (int)$_POST['delete']; // ID услуги для удаления (преобразование в целое число)

    // Проверка существования услуги в базе данных
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM services WHERE id = ?");
    $stmt->execute([$service_id]);
    if ($stmt->fetchColumn() === 0) {
        $_SESSION['error'] = 'Услуга не найдена!'; // Сообщение об ошибке, если услуга не существует
    } else {
        // Удаление услуги из базы данных
        $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
        $stmt->execute([$service_id]);
        $_SESSION['success'] = 'Услуга удалена!'; // Сообщение об успешном удалении
    }
    header("Location: admin-services.php"); // Перенаправление обратно на страницу услуг
    exit();
}

// Загрузка списка услуг из базы данных
$stmt = $pdo->query("SELECT * FROM services");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC); // Получение всех услуг в виде массива
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8"> <!-- Установка кодировки UTF-8 -->
    <title>Управление услугами</title> <!-- Заголовок страницы -->
    <link rel="stylesheet" href="styleAdmin.css"> <!-- Подключение CSS для стилей -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Иконки Font Awesome -->
</head>
<body>
    <header>
        <h2 class="logo">ЧистоБобр (Админ)</h2> <!-- Логотип админ-панели -->
        <nav class="admin-nav"> <!-- Навигационное меню -->
            <a href="admin-users.php">Пользователи</a> <!-- Ссылка на управление пользователями -->
            <a href="admin-reviews.php">Отзывы</a> <!-- Ссылка на управление отзывами -->
            <a href="admin-services.php">Услуги</a> <!-- Ссылка на текущую страницу -->
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

    <!-- Форма для добавления новой услуги -->
    <div class="admin-form">
        <h2>Добавить услугу</h2>
        <form action="admin-services.php" method="POST">
            <div class="input-group">
                <label>Название:</label> <!-- Поле для названия услуги -->
                <input type="text" name="title" placeholder="Название услуги" required>
            </div>
            <div class="input-group">
                <label>Описание:</label> <!-- Поле для описания услуги -->
                <textarea name="description" rows="4" placeholder="Описание услуги" required></textarea>
            </div>
            <div class="input-group">
                <label>Цена (₽):</label> <!-- Поле для цены услуги -->
                <input type="number" name="price" placeholder="Цена" required>
            </div>
            <button type="submit" name="add" class="submit-btn">Добавить услугу</button> <!-- Кнопка отправки формы -->
        </form>
    </div>

    <!-- Таблица со списком услуг -->
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th> <!-- Колонка ID услуги -->
                <th>Название</th> <!-- Колонка названия услуги -->
                <th>Цена</th> <!-- Колонка цены услуги -->
                <th>Действия</th> <!-- Колонка действий -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $service): ?>
                <tr>
                    <td><?php echo $service['id']; ?></td> <!-- ID услуги -->
                    <td><?php echo htmlspecialchars($service['title']); ?></td> <!-- Название услуги (защищено от XSS) -->
                    <td><?php echo $service['price']; ?> ₽</td> <!-- Цена услуги -->
                    <td>
                        <!-- Форма для удаления услуги -->
                        <form action="admin-services.php" method="POST">
                            <input type="hidden" name="delete" value="<?php echo $service['id']; ?>"> <!-- Скрытое поле с ID услуги -->
                            <button type="submit" class="delete-btn" 
                                    onclick="return confirm('Удалить услугу?')"> <!-- Подтверждение удаления -->
                                <i class="fas fa-trash"></i> Удалить <!-- Кнопка удаления -->
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>