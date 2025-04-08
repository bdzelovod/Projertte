<head>
    <meta charset="UTF-8"> <!-- Установка кодировки UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Адаптация под мобильные устройства -->
    <title>О нас - ЧистоБобр</title> <!-- Заголовок страницы -->
    <link rel="stylesheet" href="styleAbout.css"> <!-- Подключение основного CSS файла -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Иконки Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> <!-- Дополнительные иконки Font Awesome -->
</head>
<body class="about-page">

    <header>
        <h2 class="logo">ЧистоБобр</h2> <!-- Логотип компании -->
        <nav class="navigation"> <!-- Навигационное меню -->
            <a href="index.php">Главная</a> <!-- Ссылка на главную страницу -->
            <a href="services.php">Услуги</a> <!-- Ссылка на страницу услуг -->
            <a href="about.php">О нас</a> <!-- Ссылка на текущую страницу -->
            <a href="news.php">Новости</a> <!-- Ссылка на страницу новостей -->
        </nav>
    </header>

    <main class="about-container">
        <!-- Раздел "О компании" -->
        <section class="about-intro">
            <h1>О нашей компании</h1> <!-- Заголовок раздела -->
            <p>
                Компания "ЧистоБобр" была основана в 2025 году с целью предоставления высококачественных клининговых услуг по доступным ценам.
                Мы гордимся своим профессионализмом и вниманием к деталям.
            </p>
        </section>

        <!-- Раздел "Наши ценности" -->
        <section class="about-values">
            <h2 class="section-title">Наши ценности</h2> <!-- Заголовок раздела -->
            <div class="value-cards">
                <!-- Карточка "Качество" -->
                <div class="value-card">
                    <i class="fas fa-star"></i> <!-- Иконка звезды -->
                    <h3>Качество</h3> <!-- Заголовок карточки -->
                    <p>Мы используем только проверенные средства и современное оборудование.</p> <!-- Описание -->
                </div>
                <!-- Карточка "Профессионализм" -->
                <div class="value-card">
                    <i class="fas fa-handshake"></i> <!-- Иконка рукопожатия -->
                    <h3>Профессионализм</h3>
                    <p>Наша команда состоит из опытных специалистов, прошедших тщательный отбор.</p>
                </div>
                <!-- Карточка "Доступность" -->
                <div class="value-card">
                    <i class="fas fa-wallet"></i> <!-- Иконка кошелька -->
                    <h3>Доступность</h3>
                    <p>Наши услуги доступны каждому по разумным ценам.</p>
                </div>
            </div>
        </section>

        <!-- Раздел "Наша команда" -->
        <section class="about-team">
            <h2 class="section-title">Наша команда</h2> <!-- Заголовок раздела -->
            <div class="team-members">
                <!-- Карточка сотрудника: Буря Кирилл -->
                <div class="team-member" data-modal-target="#ivanModal">
                    <div class="member-avatar">
                        <img src="ya.jpg" alt="Буря Кирилл" class="member-image"> <!-- Фото сотрудника -->
                    </div>
                    <h3 class="member-name">Буря Кирилл</h3> <!-- Имя сотрудника -->
                    <p class="member-role">Главный управляющий</p> <!-- Должность -->
                </div>
                <!-- Модальное окно для Буря Кирилл -->
                <div id="ivanModal" class="modal">
                    <div class="modal-content">
                        <span class="close-modal">&times;</span> <!-- Кнопка закрытия модального окна -->
                        <div class="modal-header">
                            <img src="ya.jpg" alt="Буря Кирилл" class="modal-image"> <!-- Фото в модальном окне -->
                            <h2 class="modal-title">Буря Кирилл</h2> <!-- Имя в модальном окне -->
                        </div>
                        <p class="modal-description"><strong>Должность:</strong> Главный управляющий</p>
                        <p class="modal-description">Описание: Кирилл руководит компанией уже 8 дней. Под его управлением компания выросла в три раза.</p>
                    </div>
                </div>

                <!-- Аналогично для других сотрудников -->
                <div class="team-member" data-modal-target="#mariaModal">
                    <div class="member-avatar">
                        <img src="semen1.jpg" alt="Емельянов Семен" class="member-image">
                    </div>
                    <h3 class="member-name">Емельянов Семен</h3>
                    <p class="member-role">Главный технолог</p>
                </div>
                <div id="mariaModal" class="modal">
                    <div class="modal-content">
                        <span class="close-modal">&times;</span>
                        <div class="modal-header">
                            <img src="semen1.jpg" alt="Емельянов Семен" class="modal-image">
                            <h2 class="modal-title">Емельянов Семен</h2>
                        </div>
                        <p class="modal-description"><strong>Должность:</strong> Главный технолог</p>
                        <p class="modal-description">Описание: Семен отвечает за внедрение новых технологий в процесс уборки. Он имеет более 10 сертификатов по клинингу. И очень рад покупке нового пылесоса!</p>
                    </div>
                </div>

                <div class="team-member" data-modal-target="#alexeiModal">
                    <div class="member-avatar">
                        <img src="bobo.jpg" alt="Бобер Бобр" class="member-image">
                    </div>
                    <h3 class="member-name">Бобер Бобр</h3>
                    <p class="member-role">Руководитель отдела клининга</p>
                </div>
                <div id="alexeiModal" class="modal">
                    <div class="modal-content">
                        <span class="close-modal">&times;</span>
                        <div class="modal-header">
                            <img src="bobo.jpg" alt="Бобер Бобр" class="modal-image">
                            <h2 class="modal-title">Бобер Бобр</h2>
                        </div>
                        <p class="modal-description"><strong>Должность:</strong> Руководитель отдела клининга</p>
                        <p class="modal-description">Описание: Бобр возглавляет команду клинеров. Он лично обучил более 52 специалистов. Конкуренты нервно курят в сторонке!</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Раздел "Наша история" -->
        <section class="about-history">
            <h2 class="section-title">Наша история</h2> <!-- Заголовок раздела -->
            <p>
                Компания "ЧистоБобр" начала свою деятельность в 2025 году как небольшая команда энтузиастов, которые хотели изменить подход к уборке.
                За эти дни мы выросли до крупной компании, предоставляющей широкий спектр услуг по уборке квартир, офисов и коммерческих помещений.
            </p>
        </section>

        <!-- Раздел "Отзывы клиентов" -->
        <section class="about-reviews">
            <h2 class="section-title">Отзывы наших клиентов</h2> <!-- Заголовок раздела -->
            <div class="reviews-container">
                <?php
                // Подключение базы данных и загрузка отзывов
                require 'db.php';
                $stmt = $pdo->query("SELECT reviews.*, users.username FROM reviews JOIN users ON reviews.user_id = users.id ORDER BY created_at DESC");
                $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card"> <!-- Карточка отзыва -->
                        <div class="review-header">
                            <div class="user-info">
                                <h3><?php echo htmlspecialchars($review['username']); ?></h3> <!-- Имя пользователя -->
                                <p>Дата: <?php echo date('d.m.Y', strtotime($review['created_at'])); ?></p> <!-- Дата отзыва -->
                            </div>
                        </div>
                        <p class="review-text"><?php echo htmlspecialchars($review['text']); ?></p> <!-- Текст отзыва -->
                        <div class="rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?php echo ($i <= $review['rating']) ? 'checked' : ''; ?>"></i> <!-- Звезды рейтинга -->
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Форма для отправки отзыва -->
            <div class="review-form">
                <h3>Оставьте отзыв</h3>
                <form action="submit_review.php" method="POST">
                    <div class="input-group">
                        <i class="fas fa-user icon-user"></i> <!-- Иконка пользователя -->
                        <input type="text" name="username" placeholder="Ваше имя" required> <!-- Поле ввода имени -->
                    </div>
                    <div class="input-group">
                        <i class="fas fa-comment icon-comment"></i> <!-- Иконка комментария -->
                        <textarea name="text" placeholder="Текст отзыва" required></textarea> <!-- Поле ввода текста отзыва -->
                    </div>
                    <div class="rating-input">
                        <p>Оценка:</p>
                        <select name="rating" required> <!-- Выбор рейтинга -->
                            <option value="">Выберите...</option>
                            <option value="5">⭐⭐⭐⭐⭐</option>
                            <option value="4">⭐⭐⭐⭐</option>
                            <option value="3">⭐⭐⭐</option>
                            <option value="2">⭐⭐</option>
                            <option value="1">⭐</option>
                        </select>
                    </div>
                    <button type="submit">Отправить</button> <!-- Кнопка отправки формы -->
                </form>
            </div>
        </section>
    </main>

    <script src="about.js"></script> <!-- Подключение JavaScript для интерактивности -->
</body>
</html>