function toggleCard(nameElement) {
    const card = nameElement.nextElementSibling;
    if (card.classList.contains('active')) {
        card.classList.remove('active');
    } else {
        card.classList.add('active');
    }
}

const swiper = new Swiper('.swiper-container', {
    slidesPerView: 3,
    spaceBetween: 30,
    loop: true,
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    autoplay: {
        delay: 3000,
    },
});