const dropdown =  document.querySelector('.dropdown');
const menu = document.querySelector('.dropdown-menu');
const arrow = document.querySelector('.box-arrow-space')

dropdown.onclick = () => {
    menu.classList.toggle('ativo');
    arrow.classList.toggle('ativo');
}

function menuShow() {
    let menuMobile = document.querySelector('.mobile-menu');
    let iconMobile = document.querySelector('.icon-arrow-mobile')
    let iconWeb = document.querySelector('.icon-arrow')

    if(menuMobile.classList.contains('open')) {
        menuMobile.classList.remove('open');
        iconMobile.style.transform = 'rotate(223deg)';
        iconWeb.style.transform = 'rotate(223deg)';
        iconWeb.style.marginTop = '0px';

    }
    else {
        menuMobile.classList.add('open');
        iconMobile.style.transform = 'rotate(43deg)';
        iconWeb.style.transform = 'rotate(43deg)';
        iconWeb.style.marginTop = '5px';
    }
}

function toggleMobileDropdown() {
    let menuMobile = document.querySelector('.dropdown-menu-mobile');
    let iconstyle = document.querySelector('.div-icon-arrow');

    if(menuMobile.classList.contains('open')) {
        menuMobile.classList.remove('open');
        iconstyle.style.transform = 'rotate(0deg)';
    }
    else {
        menuMobile.classList.add('open');
        iconstyle.style.transform = 'rotate(180deg)';
    }
}


window.addEventListener('scroll', function(event) {
    let imgDesktop = document.querySelector('.img-desktop');
    let imgMobile = document.querySelector('.img-mobile');
    let imgImgMobile = document.querySelector('.img-mobile img');
    let imgImgDesktop = document.querySelector('.img-desktop img');
    let scrollTop = document.documentElement.scrollTop;
    
    if (scrollTop === 0) {
        imgMobile.classList.remove('imgStyle');
        imgImgMobile.style.opacity = '0';
        
        imgDesktop.classList.remove('imgStyle');
        imgImgDesktop.style.opacity = '0';
        imgDesktop.style.paddingRight = '0px'
      
    } else {
        imgMobile.classList.add('imgStyle');
        imgImgMobile.style.opacity = '1';

        imgDesktop.classList.add('imgStyle');
        imgImgDesktop.style.opacity = '1'; 
        imgDesktop.style.paddingRight = '60px'

    }
});


