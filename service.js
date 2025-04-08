document.addEventListener('DOMContentLoaded', () => {
    // Получение элементов DOM
    const orderModal = document.getElementById('orderModal'); // Модальное окно заказа
    const contactStep = document.getElementById('contactStep'); // Шаг 1: Контактные данные
    const paymentStep = document.getElementById('paymentStep'); // Шаг 2: Платежные данные
    const nextStepBtn = document.getElementById('nextStepBtn'); // Кнопка перехода к шагу оплаты
    const prevStepBtn = document.getElementById('prevStepBtn'); // Кнопка возврата к шагу контактов
    const submitOrderBtn = document.getElementById('submitOrderBtn'); // Кнопка отправки заказа
    const closeModalButtons = document.querySelectorAll('.close-modal'); // Кнопки закрытия модального окна
    let selectedServiceId; // ID выбранной услуги

    // Обработка кликов на кнопках заказа услуг
    document.querySelectorAll('.order-btn').forEach(button => {
        button.addEventListener('click', () => {
            selectedServiceId = button.dataset.serviceId; // Сохраняем ID выбранной услуги
            contactStep.style.display = 'block'; // Показываем шаг контактных данных
            paymentStep.style.display = 'none'; // Скрываем шаг платежных данных
            orderModal.style.display = 'flex'; // Открываем модальное окно
            document.getElementById('orderForm').reset(); // Очищаем форму заказа
        });
    });

    // Закрытие модального окна при нажатии на кнопку закрытия
    closeModalButtons.forEach(button => {
        button.addEventListener('click', () => {
            orderModal.style.display = 'none'; // Скрываем модальное окно
        });
    });

    // Закрытие модального окна при клике вне его области
    window.addEventListener('click', (e) => {
        if (e.target === orderModal) {
            orderModal.style.display = 'none'; // Скрываем модальное окно
        }
    });

    // Валидация и форматирование поля телефона
    document.getElementById('phone').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9+]/g, ''); // Удаляем все символы, кроме цифр и "+"
        if (!this.value.startsWith('+')) {
            this.value = '+' + this.value.replace(/^\+/, ''); // Добавляем "+" в начало номера
        }
        if (this.value.length > 16) {
            this.value = this.value.substring(0, 16); // Ограничиваем длину до 16 символов
        }
    });

    // Форматирование номера карты
    document.getElementById('cardNumber').addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, ''); // Удаляем все символы, кроме цифр
        if (value.length > 16) value = value.substring(0, 16); // Ограничиваем длину до 16 цифр
        this.value = value.replace(/(\d{4})(?=\d)/g, '$1 '); // Разделяем цифры пробелами через каждые 4 символа
    });

    // Форматирование срока действия карты
    document.getElementById('expiryDate').addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, ''); // Удаляем все символы, кроме цифр
        if (value.length > 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4); // Форматируем в формат "MM/YY"
        }
        this.value = value;
    });

    // Валидация CVV кода
    document.getElementById('cvv').addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, ''); // Удаляем все символы, кроме цифр
        if (this.value.length > 3) {
            this.value = this.value.substring(0, 3); // Ограничиваем длину до 3 цифр
        }
    });

    // Переход к шагу оплаты
    nextStepBtn.addEventListener('click', () => {
        const phone = document.getElementById('phone');
        const address = document.getElementById('address');

        // Проверка валидности поля телефона
        if (!phone.checkValidity()) {
            phone.reportValidity(); // Показываем сообщение об ошибке валидации
            return;
        }

        // Проверка заполнения адреса
        if (!address.value.trim()) {
            address.setCustomValidity('Введите адрес'); // Устанавливаем пользовательское сообщение об ошибке
            address.reportValidity(); // Показываем сообщение об ошибке
            address.setCustomValidity(''); // Сбрасываем пользовательское сообщение
            return;
        }

        // Переход к шагу оплаты
        contactStep.style.display = 'none'; // Скрываем шаг контактных данных
        paymentStep.style.display = 'block'; // Показываем шаг платежных данных
    });

    // Возврат к шагу контактных данных
    prevStepBtn.addEventListener('click', () => {
        paymentStep.style.display = 'none'; // Скрываем шаг платежных данных
        contactStep.style.display = 'block'; // Показываем шаг контактных данных
    });

    // Отправка данных заказа на сервер
    submitOrderBtn.addEventListener('click', async () => {
        const inputs = document.querySelectorAll('#paymentStep input[required]'); // Все обязательные поля шага оплаты
        let isValid = true;

        // Проверка валидности всех обязательных полей
        inputs.forEach(input => {
            if (!input.checkValidity()) {
                input.reportValidity(); // Показываем сообщение об ошибке валидации
                isValid = false;
            }
        });

        if (!isValid) return; // Прерываем выполнение, если есть ошибки

        // Формирование данных для отправки на сервер
        const formData = {
            service_id: selectedServiceId,
            phone: document.getElementById('phone').value.replace(/\D/g, ''), // Удаляем все символы, кроме цифр
            address: document.getElementById('address').value.trim(),
            cardNumber: document.getElementById('cardNumber').value.replace(/\D/g, ''), // Удаляем все символы, кроме цифр
            expiryDate: document.getElementById('expiryDate').value,
            cvv: document.getElementById('cvv').value
        };

        try {
            // Отправка данных на сервер через fetch
            const response = await fetch('process_order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });

            const result = await response.json(); // Получаем ответ от сервера

            if (result.success) {
                alert('Заказ успешно оформлен!'); // Показываем сообщение об успехе
                orderModal.style.display = 'none'; // Скрываем модальное окно
            } else {
                throw new Error(result.message || 'Ошибка при оформлении заказа'); // Генерируем ошибку при неудаче
            }
        } catch (error) {
            console.error('Error:', error); // Логируем ошибку в консоль
            alert(error.message || 'Произошла ошибка при отправке данных'); // Показываем сообщение об ошибке пользователю
        }
    });
});