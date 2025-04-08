<?php
session_start(); // Запуск сессии для работы с данными пользователя
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8"> <!-- Установка кодировки UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Адаптация под мобильные устройства -->
    <title>Услуги - ЧистоБобр</title> <!-- Заголовок страницы -->
    <link rel="stylesheet" href="styleService.css"> <!-- Подключение CSS для стилей -->
</head>
<body class="services-page">

    <?php
    error_reporting(E_ALL); // Включение отображения всех ошибок
    ini_set('display_errors', 1); // Отображение ошибок на экран

    require 'db.php'; // Подключение к базе данных

    // Запрос на получение всех услуг из базы данных
    $stmt = $pdo->query("SELECT * FROM services");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC); // Получение всех услуг в виде массива
    ?>
    
    <!-- Шапка сайта -->
    <header>
        <h2 class="logo">ЧистоБобр</h2> <!-- Логотип компании -->
        <nav class="navigation"> <!-- Навигационное меню -->
            <a href="index.php">Главная</a> <!-- Ссылка на главную страницу -->
            <a href="services.php">Услуги</a> <!-- Ссылка на текущую страницу услуг -->
            <a href="about.php">О нас</a> <!-- Ссылка на страницу "О нас" -->
            <a href="news.php">Новости</a> <!-- Ссылка на страницу новостей -->
        </nav>
    </header>

    <!-- Контейнер для отображения услуг -->
    <div class="services-container">
        <h1>Наши услуги</h1> <!-- Заголовок раздела услуг -->

        <!-- Модальное окно для заказа услуги -->
        <div class="modal" id="orderModal">
            <div class="modal-content">
                <span class="close-modal">&times;</span> <!-- Кнопка закрытия модального окна -->
                <h2>Заказ услуги</h2> <!-- Заголовок модального окна -->

                <!-- Шаг 1: Контактные данные -->
                <div id="contactStep">
                    <div class="input-group">
                        <i class="icon"><ion-icon name="call"></ion-icon></i> <!-- Иконка телефона -->
                        <input type="tel" id="phone" name="phone" required 
                               placeholder="+7 (___) ___-__-__"
                               pattern="\+[0-9]{10,15}" 
                               title="Номер должен начинаться с + и содержать 10-15 цифр">
                        <div class="error-message">Введите номер в формате +79991234567</div> <!-- Сообщение об ошибке -->
                    </div>
                    <div class="input-group">
                        <i class="icon"><ion-icon name="location"></ion-icon></i> <!-- Иконка адреса -->
                        <input type="text" id="address" name="address" required 
                               placeholder="Введите адрес">
                    </div>
                    <button type="button" id="nextStepBtn" class="btn">Продолжить</button> <!-- Кнопка перехода к следующему шагу -->
                </div>
                
                <!-- Шаг 2: Платежные данные -->
                <div id="paymentStep" style="display:none;">
                    <div class="input-group">
                        <i class="icon"><ion-icon name="card"></ion-icon></i> <!-- Иконка карты -->
                        <input type="text" id="cardNumber" name="cardNumber" required 
                               placeholder="0000 0000 0000 0000"
                               pattern="[0-9\s]{16,19}" 
                               title="16 цифр номера карты">
                        <div class="error-message">Введите 16 цифр номера карты</div> <!-- Сообщение об ошибке -->
                    </div>
                    <div class="input-group">
                        <i class="icon"><ion-icon name="calendar"></ion-icon></i> <!-- Иконка календаря -->
                        <input type="text" id="expiryDate" name="expiryDate" required 
                               placeholder="MM/YY"
                               pattern="(0[1-9]|1[0-2])\/[0-9]{2}"
                               title="Формат: MM/YY">
                        <div class="error-message">Введите срок в формате MM/YY</div> <!-- Сообщение об ошибке -->
                    </div>
                    <div class="input-group">
                        <i class="icon"><ion-icon name="key"></ion-icon></i> <!-- Иконка ключа -->
                        <input type="text" id="cvv" name="cvv" required 
                               placeholder="123"
                               pattern="[0-9]{3}"
                               title="3 цифры CVV">
                        <div class="error-message">Введите 3 цифры CVV</div> <!-- Сообщение об ошибке -->
                    </div>
                    <div class="form-navigation">
                        <button type="button" id="prevStepBtn" class="btn">Назад</button> <!-- Кнопка возврата к предыдущему шагу -->
                        <button type="button" id="submitOrderBtn" class="btn">Оплатить</button> <!-- Кнопка отправки заказа -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Модальное окно для оплаты -->
        <div class="modal" id="paymentModal">
            <div class="modal-content">
                <span class="close-modal">&times;</span> <!-- Кнопка закрытия модального окна -->
                <h2>Оплата</h2> <!-- Заголовок модального окна -->
                <form id="paymentForm">
                    <div class="input-group">
                        <i class="icon"><ion-icon name="card"></ion-icon></i> <!-- Иконка карты -->
                        <input type="text" id="cardNumber" name="cardNumber" required placeholder="0000 0000 0000 0000">
                    </div>
                    <div class="input-group">
                        <i class="icon"><ion-icon name="calendar"></ion-icon></i> <!-- Иконка календаря -->
                        <input type="text" id="expiryDate" name="expiryDate" required placeholder="MM/YY">
                    </div>
                    <div class="input-group">
                        <i class="icon"><ion-icon name="key"></ion-icon></i> <!-- Иконка ключа -->
                        <input type="text" id="cvv" name="cvv" required placeholder="123">
                    </div>
                    <button type="submit" class="btn">Оплатить</button> <!-- Кнопка отправки формы оплаты -->
                </form>
            </div>
        </div>
        
        <!-- Контейнер для карточек услуг -->
        <div class="service-cards">
            <?php if (!empty($services)): ?> <!-- Проверка наличия услуг в базе данных -->
                <?php foreach ($services as $service): ?> <!-- Цикл для вывода каждой услуги -->
                    <div class="service-card" data-service="<?php echo $service['id']; ?>">
                        <h3><?php echo htmlspecialchars($service['title']); ?></h3> <!-- Заголовок услуги -->
                        <?php foreach (explode("\n", $service['description']) as $line): ?> <!-- Разделение описания по строкам -->
                            <p><?php echo htmlspecialchars($line); ?></p> <!-- Описание услуги -->
                        <?php endforeach; ?>
                        <div class="price"><?php echo htmlspecialchars($service['price']); ?> ₽</div> <!-- Цена услуги -->
                        <button class="order-btn btn" data-service-id="<?php echo $service['id']; ?>">Заказать</button> <!-- Кнопка заказа услуги -->
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Услуги пока не добавлены.</p> <!-- Сообщение, если услуги отсутствуют -->
            <?php endif; ?>
        </div>
        
        <!-- Кнопка возврата на главную страницу -->
        <div class="back-btn-container">
            <a href="index.php" class="back-btn">← На главную</a>
        </div>
    </div>

    <script src="service.js"></script> <!-- Подключение JavaScript для интерактивности -->
</body>
</html>