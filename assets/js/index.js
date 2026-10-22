const door = document.querySelector(".bi-door-closed");

door.addEventListener('mouseenter', () => {
    door.classList.replace('bi-door-closed', 'bi-door-open');
});

door.addEventListener('mouseleave', () => {
    door.classList.replace('bi-door-open', 'bi-door-closed');
});