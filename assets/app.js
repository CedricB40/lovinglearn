import './stimulus_bootstrap.js';
import './styles/app.css';

// Génération des étoiles
function generateStars() {
    const starsContainer = document.getElementById('stars-bg');
    if (!starsContainer) return;
    starsContainer.innerHTML = '';
    for (let i = 0; i < 150; i++) {
        const star = document.createElement('div');
        star.classList.add('star');
        star.style.left = Math.random() * 100 + 'vw';
        star.style.top = Math.random() * 100 + 'vh';
        star.style.animationDelay = Math.random() * 3 + 's';
        star.style.width = star.style.height = Math.random() * 3 + 1 + 'px';
        starsContainer.appendChild(star);
    }
}

document.addEventListener('DOMContentLoaded', generateStars);
document.addEventListener('turbo:load', generateStars);