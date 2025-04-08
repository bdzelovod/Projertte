// Получение элементов DOM
const wrapper = document.querySelector('.wrapper'); // Основной контейнер для форм входа и регистрации
const loginLink = document.querySelector('.login-link'); // Ссылка для переключения на форму входа
const registerLink = document.querySelector('.register-link'); // Ссылка для переключения на форму регистрации
const btnPopup = document.querySelector('.btnLogin-popup'); // Кнопка для открытия всплывающего окна
const iconClose = document.querySelector('.icon-close'); // Кнопка для закрытия всплывающего окна

// Обработка клика на ссылку "Зарегистрироваться"
if (registerLink) {
    registerLink.addEventListener('click', (e) => {
        e.preventDefault(); // Предотвращаем стандартное поведение ссылки
        wrapper.classList.add('active'); // Добавляем класс 'active' для отображения формы регистрации
    });
}

// Обработка клика на ссылку "Войти"
if (loginLink) {
    loginLink.addEventListener('click', (e) => {
        e.preventDefault(); // Предотвращаем стандартное поведение ссылки
        wrapper.classList.remove('active'); // Удаляем класс 'active' для отображения формы входа
    });
}

// Обработка клика на кнопку "Войти" (открывает всплывающее окно)
if (btnPopup) {
    btnPopup.addEventListener('click', () => {
        wrapper.classList.add('active-popup'); // Добавляем класс 'active-popup' для отображения всплывающего окна
    });
}

// Обработка клика на кнопку закрытия всплывающего окна
if (iconClose) {
    iconClose.addEventListener('click', () => {
        wrapper.classList.remove('active-popup'); // Удаляем класс 'active-popup' для скрытия всплывающего окна
    });
}

// Закрытие всплывающего окна при клике вне его области
document.addEventListener('click', (e) => {
    if (e.target === wrapper && wrapper.classList.contains('active-popup')) {
        wrapper.classList.remove('active-popup'); // Удаляем класс 'active-popup' для скрытия всплывающего окна
    }
});

// Автоматическое скрытие уведомлений через 2 секунды после загрузки страницы
document.addEventListener('DOMContentLoaded', () => {
    const notifications = document.querySelectorAll('.notification'); // Получаем все уведомления
    notifications.forEach(notification => {
        setTimeout(() => {
            notification.classList.add('hide'); // Добавляем класс 'hide' для анимации исчезновения
            setTimeout(() => {
                notification.remove(); // Удаляем уведомление из DOM после завершения анимации
            }, 500); // Задержка для завершения анимации (например, 500 мс)
        }, 2000); // Задержка перед началом исчезновения (например, 2 секунды)
    });
});

// Анимация меток полей ввода (placeholder)
document.querySelectorAll('.input-box input').forEach(input => {
    const label = input.nextElementSibling; // Получаем метку (label), связанную с полем ввода

    // Обработка фокуса на поле ввода
    input.addEventListener('focus', () => {
        if (input.value === '') {
            label.style.display = 'none'; // Скрываем метку, если поле пустое
        }
    });

    // Обработка потери фокуса на поле ввода
    input.addEventListener('blur', () => {
        if (input.value === '') {
            label.style.display = 'block'; // Показываем метку, если поле пустое
        }
    });
});