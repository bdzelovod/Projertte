<html lang="ru">
<head>
    <meta charset="UTF-8"> <!-- Установка кодировки UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Адаптация под мобильные устройства -->
    <title>Новости - ЧистоБобр</title> <!-- Заголовок страницы -->
    <link rel="stylesheet" href="styleNews.css"> <!-- Подключение CSS для стилей -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> <!-- Иконки Font Awesome -->
</head>
<body class="news-page">

    <!-- Шапка сайта -->
    <header>
        <h2 class="logo">ЧистоБобр</h2> <!-- Логотип компании -->
        <nav class="navigation"> <!-- Навигационное меню -->
            <a href="index.php">Главная</a> <!-- Ссылка на главную страницу -->
            <a href="services.php">Услуги</a> <!-- Ссылка на страницу услуг -->
            <a href="about.php">О нас</a> <!-- Ссылка на страницу "О нас" -->
            <a href="news.php">Новости</a> <!-- Ссылка на текущую страницу новостей -->
        </nav>
    </header>

    <!-- Основной контент страницы -->
    <main class="news-container">
        <h1>Новости</h1> <!-- Заголовок раздела новостей -->

        <!-- Контейнер для карточек новостей -->
        <div class="news-cards">
            <?php
            require 'db.php'; // Подключение к базе данных

            // Запрос на получение всех новостей, отсортированных по дате создания (по убыванию)
            $stmt = $pdo->query("SELECT * FROM news ORDER BY created_at DESC");
            $news_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Цикл для вывода каждой новости
            foreach ($news_list as $news) {
            ?>
                <!-- Карточка новости -->
                <div class="news-card" data-modal-target="#modal-<?php echo $news['id']; ?>">
                    <img src="<?php echo htmlspecialchars($news['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($news['title']); ?>" 
                         class="news-image"> <!-- Изображение новости -->
                    <h3 class="news-title"><?php echo htmlspecialchars($news['title']); ?></h3> <!-- Заголовок новости -->
                    <p class="news-description"><?php echo htmlspecialchars(substr($news['content'], 0, 100)); ?>...</p> <!-- Краткое описание новости -->
                    <p class="news-date">Дата: <?php echo date('d.m.Y', strtotime($news['created_at'])); ?></p> <!-- Дата публикации -->
                </div>

                <!-- Модальное окно для полного просмотра новости -->
                <div id="modal-<?php echo $news['id']; ?>" class="modal">
                    <div class="modal-content">
                        <span class="close-modal">&times;</span> <!-- Кнопка закрытия модального окна -->
                        <img src="<?php echo htmlspecialchars($news['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($news['title']); ?>" 
                             class="modal-image"> <!-- Изображение в модальном окне -->
                        <h2 class="modal-title"><?php echo htmlspecialchars($news['title']); ?></h2> <!-- Заголовок в модальном окне -->
                        <p class="modal-description"><strong>Дата:</strong> <?php echo date('d.m.Y', strtotime($news['created_at'])); ?></p> <!-- Дата публикации -->
                        <p class="modal-description"><?php echo htmlspecialchars($news['content']); ?></p> <!-- Полное описание новости -->
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>

    <script src="news.js"></script> <!-- Подключение JavaScript для интерактивности -->
</body>
</html>