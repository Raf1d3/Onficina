/*=============== SHOW MENU ===============*/
const showMenu = (toggleId, navId) =>{
    const toggle = document.getElementById(toggleId),
          nav = document.getElementById(navId)
 
    toggle.addEventListener('click', () =>{
        // Add show-menu class to nav menu
        nav.classList.toggle('show-menu')
 
        // Add show-icon to show and hide the menu icon
        toggle.classList.toggle('show-icon')
    })
 }
 
 showMenu('nav-toggle','nav-menu')

 
// lading page
//carrossel
const carousel = document.querySelector(".carousel");
const carousel2 = document.querySelector(".carousel-2");
const cards = document.querySelectorAll(".cards")[0];
const cards2 = document.querySelectorAll(".cards")[1];
const firstCardWidth = cards.querySelector(".card").offsetWidth;
const firstCardWidth2 = cards2.querySelector(".card").offsetWidth;
const arrowBtns = document.querySelectorAll(".carousel i");
const arrowBtns2 = document.querySelectorAll(".carousel-2 i");

const cardsChildrens = [...cards.children];
const cardsChildrens2 = [...cards2.children];

let isDragging = false, isAutoPlay = true, startX, startScrollLeft, timeoutId;
let isDragging2 = false, isAutoPlay2 = true, startX2, startScrollLeft2, timeoutId2;

let cardPerView = Math.round(cards.offsetWidth / firstCardWidth);
let cardPerView2 = Math.round(cards2.offsetWidth / firstCardWidth2);

cardsChildrens.slice(-cardPerView).reverse().forEach(card => {
    cards.insertAdjacentHTML("afterbegin", card.outerHTML);
});
cardsChildrens2.slice(-cardPerView2).reverse().forEach(card => {
    cards2.insertAdjacentHTML("afterbegin", card.outerHTML);
});

cardsChildrens.slice(0, cardPerView).forEach(card => {
    cards.insertAdjacentHTML("beforeend", card.outerHTML);
});
cardsChildrens2.slice(0, cardPerView2).forEach(card => {
    cards2.insertAdjacentHTML("beforeend", card.outerHTML);
});


cards.classList.add("no-transition");
cards.scrollLeft = cards.offsetWidth;
cards.classList.remove("no-transition");

cards2.classList.add("no-transition");
cards2.scrollLeft = cards2.offsetWidth;
cards2.classList.remove("no-transition");

arrowBtns.forEach(btn => {
    btn.addEventListener("click", () => {
        cards.scrollLeft += btn.id == "left" ? -firstCardWidth : firstCardWidth;
    });
});

arrowBtns2.forEach(btn => {
    btn.addEventListener("click", () => {
        cards2.scrollLeft += btn.id == "left" ? -firstCardWidth : firstCardWidth;
    });
});

const dragStart = (e) => {
    isDragging = true;
    cards.classList.add("dragging");
    startX = e.pageX;
    startScrollLeft = cards.scrollLeft;
}

const dragStart2 = (e) => {
    isDragging2 = true;
    cards2.classList.add("dragging");
    startX2 = e.pageX;
    startScrollLeft2 = cards2.scrollLeft;
}

const dragging = (e) => {
    if(!isDragging) return; 
    cards.scrollLeft = startScrollLeft - (e.pageX - startX);
}

const dragging2 = (e) => {
    if(!isDragging2) return;
    cards2.scrollLeft = startScrollLeft2 - (e.pageX - startX2);
}

const dragStop = () => {
    isDragging = false;
    cards.classList.remove("dragging");
}

const dragStop2 = () => {
    isDragging2 = false;
    cards2.classList.remove("dragging");
}

const infiniteScroll = () => {
    if(cards.scrollLeft === 0) {
        cards.classList.add("no-transition");
        cards.scrollLeft = cards.scrollWidth - (2 * cards.offsetWidth);
        cards.classList.remove("no-transition");
    }
    else if(Math.ceil(cards.scrollLeft) === cards.scrollWidth - cards.offsetWidth) {
        cards.classList.add("no-transition");
        cards.scrollLeft = cards.offsetWidth;
        cards.classList.remove("no-transition");
    }

    clearTimeout(timeoutId);
    if(!carousel.matches(":hover")) autoPlay();
}



const infiniteScroll2 = () => {
    if(cards2.scrollLeft === 0) {
        cards2.classList.add("no-transition");
        cards2.scrollLeft = cards2.scrollWidth - (2 * cards2.offsetWidth);
        cards2.classList.remove("no-transition");
    }
    else if(Math.ceil(cards2.scrollLeft) === cards2.scrollWidth - cards2.offsetWidth) {
        cards2.classList.add("no-transition");
        cards2.scrollLeft = cards2.offsetWidth;
        cards2.classList.remove("no-transition");
    }

    clearTimeout(timeoutId2);
    if(!carousel2.matches(":hover")) autoPlay2();
}


const autoPlay = () => {
    if(window.innerWidth < 800 || !isAutoPlay) return;
    timeoutId = setTimeout(() => cards.scrollLeft += firstCardWidth, 2500);
}

const autoPlay2 = () => {
    if(window.innerWidth < 800 || !isAutoPlay2) return;
    timeoutId2 = setTimeout(() => cards2.scrollLeft += firstCardWidth2, 2500);
}

autoPlay();

cards.addEventListener("mousedown", dragStart);
cards.addEventListener("mousemove", dragging);
cards2.addEventListener("mousedown", dragStart2);
cards2.addEventListener("mousemove", dragging2);
document.addEventListener("mouseup", dragStop);
document.addEventListener("mouseup", dragStop2);
cards.addEventListener("scroll", infiniteScroll);
cards2.addEventListener("scroll", infiniteScroll2);

carousel.addEventListener("mouseenter", () => clearTimeout(timeoutId));
carousel.addEventListener("mouseleave", autoPlay);
carousel2.addEventListener("mouseenter", () => clearTimeout(timeoutId2));
carousel2.addEventListener("mouseleave", autoPlay2);

