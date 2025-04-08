// Добавляем обработчики клика для всех элементов с классом 'team-member'.
document.querySelectorAll('.team-member').forEach(member => {
    member.addEventListener('click', () => {
        const modalId = member.getAttribute('data-modal-target'); // Получаем ID модального окна.
        const modal = document.querySelector(modalId); // Находим модальное окно.
        if (modal) modal.style.display = 'flex'; // Отображаем модальное окно.
    });
});

// Добавляем обработчики клика для кнопок закрытия модальных окон.
document.querySelectorAll('.close-modal').forEach(closeBtn => {
    closeBtn.addEventListener('click', () => {
        const modal = closeBtn.closest('.modal'); // Находим родительское модальное окно.
        if (modal) modal.style.display = 'none'; // Скрываем модальное окно.
    });
});

// Закрываем модальное окно при клике вне его содержимого.
window.addEventListener('click', event => {
    if (event.target.classList.contains('modal')) { // Проверяем, что клик был на самой подложке модального окна.
        event.target.style.display = 'none'; // Скрываем модальное окно.
    }
});