<?php
session_start(); // Запуск сессии
require 'db.php'; // Подключение к базе данных

// Проверка прав доступа
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    $_SESSION['error'] = 'Доступ запрещен!';
    header("Location: index.php");
    exit();
}

// Обработка POST-запроса (удаление отзыва)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    try {
        $review_id = (int)$_POST['delete'];

        // Проверка существования отзыва
        $stmt = $pdo->prepare("SELECT * FROM reviews WHERE id = ?");
        $stmt->execute([$review_id]);
        $review = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$review) {
            throw new Exception('Отзыв не найден!');
        }

        // Удаление отзыва
        $pdo->prepare("DELETE FROM reviews WHERE id = ?")->execute([$review_id]);
        $_SESSION['success'] = 'Отзыв удален!';
    } catch (Exception $e) {
        $_SESSION['error'] = 'Ошибка: ' . $e->getMessage();
    }

    // Перенаправление после обработки POST-запроса
    header("Location: admin-reviews.php");
    exit();
}

// Загрузка списка отзывов (выполняется только при GET-запросе)
$stmt = $pdo->query("SELECT reviews.*, users.username 
                     FROM reviews 
                     JOIN users ON reviews.user_id = users.id 
                     ORDER BY reviews.created_at DESC");
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление отзывами</title>
    <link rel="stylesheet" href="styleAdmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <h2 class="logo">ЧистоБобр (Админ)</h2>
        <nav class="admin-nav">
            <a href="admin-users.php">Пользователи</a>
            <a href="admin-reviews.php">Отзывы</a>
            <a href="admin-services.php">Услуги</a>
            <a href="admin-news.php">Новости</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="admin-back-btn">
        <a href="admin.php" class="btn-back">← Назад в админ-панель</a>
    </div>
    <a href="index.php" class="btn-home">
        <i class="fas fa-home"></i> На главную
    </a>

    <!-- Отображение уведомлений -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="notification success"><?php echo htmlspecialchars($_SESSION['success']); ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="notification error"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Таблица со списком отзывов -->
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Автор</th>
                <th>Текст</th>
                <th>Оценка</th>
                <th>Дата</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reviews as $review): ?>
                <tr>
                    <td><?php echo htmlspecialchars($review['id']); ?></td>
                    <td><?php echo htmlspecialchars($review['username']); ?></td>
                    <td><?php echo htmlspecialchars(substr($review['text'], 0, 50)) . '...'; ?></td>
                    <td><?php echo htmlspecialchars($review['rating']); ?></td>
                    <td><?php echo date('d.m.Y', strtotime($review['created_at'])); ?></td>
                    <td>
                        <!-- Форма для удаления отзыва -->
                        <form action="admin-reviews.php" method="POST">
                            <input type="hidden" name="delete" value="<?php echo htmlspecialchars($review['id']); ?>">
                            <button type="submit" class="delete-btn" onclick="return confirm('Удалить отзыв?')">
                                <i class="fas fa-trash"></i> Удалить
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>