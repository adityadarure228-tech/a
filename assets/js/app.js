const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
        }
    });
}, { threshold: 0.18 });

document.querySelectorAll('.scroll-animated').forEach((item) => observer.observe(item));

const hero = document.getElementById('floatingHeroArt');
if (hero) {
    document.addEventListener('mousemove', (event) => {
        const x = (window.innerWidth / 2 - event.clientX) / 48;
        const y = (window.innerHeight / 2 - event.clientY) / 48;
        hero.style.transform = `rotateX(${y}deg) rotateY(${-x}deg)`;
    });
}

const movieCards = document.querySelectorAll('.movie-card');
window.addEventListener('scroll', () => {
    const scrollDepth = window.scrollY / (document.body.scrollHeight - window.innerHeight || 1);
    movieCards.forEach((card, index) => {
        const hue = Math.round(220 + scrollDepth * 70 + index * 12);
        card.style.background = `linear-gradient(180deg, hsla(${hue}, 65%, 15%, 0.92), hsla(${hue + 25}, 70%, 8%, 0.95))`;
    });
});

const track = document.querySelector('.reel-track');
if (track) {
    const rightButtons = document.querySelectorAll('[data-scroll-right]');
    rightButtons.forEach((button) => {
        button.addEventListener('click', () => {
            track.scrollBy({ left: 320, behavior: 'smooth' });
        });
    });
}
