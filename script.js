
/* Define quando a classe mobile-menu deve aparecer ou desaparecer */
function menuShow() {
    let menuMobile = document.querySelector('.mobile-menu');
    let iconMobile = document.querySelector('.mobile-menu-icon');
    let iconWeb = document.querySelector('.icon-arraw')


    if(menuMobile.classList.contains('open')) {
        menuMobile.classList.remove('open');
        iconMobile.style.transform = 'rotate(360deg)';
        iconWeb.style.transform = 'rotate(225deg)';

    }
    else {
        menuMobile.classList.add('open');
        iconMobile.style.transform = 'rotate(180deg)';
        iconWeb.style.transform = 'rotate(45deg)';
    }
}

const solucoes =  document.querySelector('#solucoes');
const menu = document.querySelector('.menu');

solucoes.onmouseenter = () => {
    menu.classList.toggle('ativo')
}
solucoes.onmouseleave = () => {
    menu.classList.remove('ativo')
}