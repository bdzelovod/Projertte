<?php
session_start(); // Запуск сессии
require 'db.php'; // Подключение к базе данных

error_reporting(E_ALL); // Включение отображения всех ошибок
ini_set('display_errors', 1); // Вывод ошибок на экран

// Проверка прав доступа: только администратор может просматривать эту страницу
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    $_SESSION['error'] = 'Доступ запрещен!';
    header("Location: index.php"); // Перенаправление на главную страницу
    exit();
}

// Обработка удаления новости
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $news_id = (int)$_POST['delete']; // ID новости для удаления
    
    try {
        // Проверка существования новости
        $stmt = $pdo->prepare("SELECT id FROM news WHERE id = ?");
        $stmt->execute([$news_id]);
        
        if (!$stmt->fetch()) {
            $_SESSION['error'] = 'Новость не найдена!';
        } else {
            // Удаление новости из базы данных
            $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
            $stmt->execute([$news_id]);
            $_SESSION['success'] = 'Новость успешно удалена!';
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Ошибка при удалении: ' . $e->getMessage();
    }
    
    header("Location: admin-news.php"); // Перенаправление обратно на страницу новостей
    exit();
}

// Обработка добавления новости
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete'])) {
    $title = trim($_POST['title'] ?? ''); // Получение заголовка новости
    $content = trim($_POST['content'] ?? ''); // Получение текста новости
    $image_url = trim($_POST['image_url'] ?? ''); // Получение ссылки на изображение

    // Валидация ввода
    if (empty($title) || empty($content)) {
        $_SESSION['error'] = 'Заголовок и текст обязательны!';
    } elseif (mb_strlen($title) > 255) {
        $_SESSION['error'] = 'Заголовок слишком длинный (макс. 255 символов)';
    } elseif (!empty($image_url) && !filter_var($image_url, FILTER_VALIDATE_URL)) {
        $_SESSION['error'] = 'Некорректная ссылка на изображение!';
    } else {
        try {
            // Добавление новости в базу данных
            $stmt = $pdo->prepare("INSERT INTO news (title, content, image_url) VALUES (?, ?, ?)");
            $stmt->execute([$title, $content, $image_url]);
            $_SESSION['success'] = 'Новость успешно добавлена!';
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Ошибка базы данных: ' . $e->getMessage();
        }
    }
    
    header("Location: admin-news.php"); // Перенаправление обратно на страницу новостей
    exit();
}

// Загрузка списка новостей из базы данных
try {
    $stmt = $pdo->query("SELECT id, title, image_url, created_at FROM news ORDER BY created_at DESC");
    $news_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = 'Ошибка загрузки новостей: ' . $e->getMessage();
    $news_list = [];
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8"> <!-- Установка кодировки UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Адаптация под мобильные устройства -->
    <title>Управление новостями - Админ-панель</title> <!-- Заголовок страницы -->
    <link rel="stylesheet" href="styleAdmin.css"> <!-- Подключение CSS для стилей -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Иконки Font Awesome -->
</head>
<body>
    <header>
        <h2 class="logo">ЧистоБобр (Админ)</h2> <!-- Логотип админ-панели -->
        <nav class="admin-nav"> <!-- Навигационное меню -->
            <a href="admin-users.php">Пользователи</a> <!-- Ссылка на управление пользователями -->
            <a href="admin-reviews.php">Отзывы</a> <!-- Ссылка на управление отзывами -->
            <a href="admin-services.php">Услуги</a> <!-- Ссылка на управление услугами -->
            <a href="admin-news.php">Новости</a> <!-- Ссылка на текущую страницу -->
            <a href="logout.php">Выйти</a> <!-- Ссылка на выход из системы -->
        </nav>
    </header>

    <div class="admin-container">

        <!-- Отображение уведомлений об ошибках -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="notification error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']) ?>
        <?php endif; ?>

        <!-- Отображение уведомлений об успехе -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="notification success"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']) ?>
        <?php endif; ?>

        <!-- Форма для добавления новой новости -->
        <div class="admin-form">
            <h2>Добавить новость</h2>
            <form method="POST">
                <div class="input-group">
                    <label for="title">Заголовок:</label> <!-- Поле для заголовка -->
                    <input type="text" id="title" name="title" required maxlength="255">
                </div>
                <div class="input-group">
                    <label for="content">Текст новости:</label> <!-- Поле для текста новости -->
                    <textarea id="content" name="content" rows="5" required></textarea>
                </div>
                <div class="input-group">
                    <label for="image_url">Ссылка на изображение:</label> <!-- Поле для ссылки на изображение -->
                    <input type="url" id="image_url" name="image_url" placeholder="https://example.com/image.jpg">
                    <small>Вставьте прямую ссылку на изображение (JPG, PNG, GIF)</small>
                    <div id="image-preview" style="margin-top: 10px; display: none;">
                        <img id="preview-img" src="" alt="Предпросмотр" style="max-width: 300px; max-height: 200px; border: 1px solid #ddd;">
                    </div>
                </div>
                <button type="submit" class="btn">Опубликовать</button> <!-- Кнопка отправки формы -->
            </form>
        </div>

        <!-- Таблица со списком новостей -->
        <h2>Список новостей</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th> <!-- Колонка ID -->
                    <th>Заголовок</th> <!-- Колонка заголовка -->
                    <th>Изображение</th> <!-- Колонка изображения -->
                    <th>Дата</th> <!-- Колонка даты -->
                    <th>Действия</th> <!-- Колонка действий -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($news_list as $news): ?>
                <tr>
                    <td><?= $news['id'] ?></td> <!-- ID новости -->
                    <td><?= htmlspecialchars($news['title']) ?></td> <!-- Заголовок новости -->
                    <td>
                        <?php if (!empty($news['image_url'])): ?>
                            <img src="<?= htmlspecialchars($news['image_url']) ?>" alt="Изображение новости" style="max-width: 100px; max-height: 60px;"> <!-- Изображение новости -->
                        <?php else: ?>
                            <span>Нет изображения</span> <!-- Если изображение отсутствует -->
                        <?php endif; ?>
                    </td>
                    <td><?= date('d.m.Y H:i', strtotime($news['created_at'])) ?></td> <!-- Дата создания новости -->
                    <td class="actions">
                        <form method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить эту новость?')"> <!-- Форма для удаления новости -->
                            <input type="hidden" name="delete" value="<?= $news['id'] ?>"> <!-- Скрытое поле с ID новости -->
                            <button type="submit" class="btn-delete">
                                <i class="fas fa-trash-alt"></i> Удалить <!-- Кнопка удаления -->
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Предварительный просмотр изображения при вводе URL
        document.getElementById('image_url').addEventListener('input', function() {
            const url = this.value.trim();
            const previewImg = document.getElementById('preview-img');
            const previewContainer = document.getElementById('image-preview');
            
            if (url) {
                previewImg.src = url;
                previewContainer.style.display = 'block';

                previewImg.onload = function() {
                    previewContainer.style.display = 'block';
                };
                previewImg.onerror = function() {
                    previewContainer.style.display = 'none';
                    alert('Не удалось загрузить изображение. Проверьте ссылку.');
                };
            } else {
                previewContainer.style.display = 'none';
            }
        });

        // Подтверждение удаления новости перед отправкой формы
        document.querySelectorAll('form[method="POST"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (this.querySelector('[name="delete"]') && !confirm('Вы уверены, что хотите удалить эту новость?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>