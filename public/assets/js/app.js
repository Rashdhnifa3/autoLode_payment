document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.toast').forEach((message) => {
        window.setTimeout(() => {
            message.classList.add('toast-hide');
            message.addEventListener('transitionend', () => message.remove(), { once: true });
            window.setTimeout(() => message.remove(), 500);
        }, 4000);
    });
});
