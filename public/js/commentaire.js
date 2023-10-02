const commentairesContainer = document.querySelector('.commentaires-container');
const carousel = document.querySelector('.carousel');
const commentaires = document.querySelectorAll('.commentaire');
const arrowLeft = document.querySelector('.arrow-left');
const arrowRight = document.querySelector('.arrow-right');
let currentIndex = 0;
const commentairesPerPage = 3;

arrowLeft.addEventListener('click', () => {
    if (currentIndex > 0) {
        currentIndex--;
        moveCarousel();
    }
});

arrowRight.addEventListener('click', () => {
    if (currentIndex < commentaires.length - commentairesPerPage) {
        currentIndex++;
        moveCarousel();
    }
});

function moveCarousel() {
    const translateValue = -currentIndex * (commentaires[0].offsetWidth + 10); // Ajustez l'espacement entre les commentaires
    carousel.style.transform = `translateX(${translateValue}px)`;
}

// Appelez moveCarousel pour afficher les commentaires initiaux
moveCarousel();


