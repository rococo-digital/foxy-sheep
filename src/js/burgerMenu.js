const burgerMenu = {

    init() {
        const button = document.querySelector('.burger-btn');
        const slideNav = document.querySelector('.masthead__sidenav');
        const body = document.querySelector('body');

        button.addEventListener('click', () => {
            button.classList.toggle('active');
            slideNav.classList.toggle('active');
            body.classList.toggle('no-scroll');
        })
    }

};

export { burgerMenu }