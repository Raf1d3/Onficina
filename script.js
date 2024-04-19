
/* Define quando a classe mobile-menu deve aparecer ou desaparecer */
function menuShow() {
    let menuMobile = document.querySelector('.mobile-menu');
    let iconMobile = document.querySelector('.mobile-menu-icon')

    if(menuMobile.classList.contains('open')) {
        menuMobile.classList.remove('open');
        iconMobile.style.transform = 'rotate(360deg)';

    }
    else {
        menuMobile.classList.add('open');
        iconMobile.style.transform = 'rotate(180deg)';
    }
}

const solucoes =  document.querySelector('#solucoes');
const menu = document.querySelector('.menu');

solucoes.onclick = () => {
    menu.classList.toggle('ativo')
}