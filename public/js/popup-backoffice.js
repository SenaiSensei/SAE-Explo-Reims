const buttons = document.querySelectorAll('#open-popup');

buttons.forEach(button => {
    button.addEventListener('click', () => {
        const id = button.getAttribute('data-id');
        const dialog = document.querySelector(`.dialog-${id}`);
        if (dialog) {
            dialog.showModal();
        }
    });
});

const closeButtons = document.querySelectorAll('.close-btn');
closeButtons.forEach(button => {
    button.addEventListener('click', () => {
        const dialog = button.closest('dialog');
        if (dialog) {
            dialog.close();
        }
    });
});
