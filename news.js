// Добавление обработчика клика на каждую карточку новостей
document.querySelectorAll('.news-card').forEach(card => {
    card.addEventListener('click', (e) => {

        // Если клик был по ссылке (<a>) или кнопке (<button>), игнорируем событие
        if (e.target.closest('a') || e.target.closest('button')) {
            return;
        }
        
        // Получаем ID модального окна из атрибута data-modal-target
        const modalId = card.getAttribute('data-modal-target');
        if (!modalId) return; // Если ID отсутствует, завершаем выполнение
        
        // Находим модальное окно по его ID
        const modal = document.querySelector(modalId);
        if (modal) {
            // Открываем модальное окно и блокируем прокрутку страницы
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden'; 
        }
    });
});


// Добавление обработчика клика на кнопки закрытия модальных окон
document.querySelectorAll('.close-modal').forEach(closeBtn => {
    closeBtn.addEventListener('click', (e) => {
        e.stopPropagation(); // Предотвращаем всплытие события
        
        // Находим ближайшее модальное окно
        const modal = closeBtn.closest('.modal');
        if (modal) {
            // Закрываем модальное окно и восстанавливаем прокрутку страницы
            modal.style.display = 'none';
            document.body.style.overflow = ''; 
        }
    });
});


// Закрытие модального окна при клике вне его области
window.addEventListener('click', event => {
    // Проверяем, что клик был по самому модальному окну (не по содержимому)
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none'; // Скрываем модальное окно
        document.body.style.overflow = ''; // Восстанавливаем прокрутку страницы
    }
});


// Закрытие модального окна при нажатии клавиши Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') { // Проверяем, что нажата клавиша Escape
        document.querySelectorAll('.modal').forEach(modal => {
            // Если модальное окно открыто, закрываем его
            if (modal.style.display === 'flex') {
                modal.style.display = 'none';
                document.body.style.overflow = ''; // Восстанавливаем прокрутку страницы
            }
        });
    }
});