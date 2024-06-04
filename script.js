
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
// Get the number of cards that can fit in the cards at once
let cardPerView = Math.round(cards.offsetWidth / firstCardWidth);
let cardPerView2 = Math.round(cards2.offsetWidth / firstCardWidth2);

// Insert copies of the last few cards to beginning of cards for infinite scrolling
cardsChildrens.slice(-cardPerView).reverse().forEach(card => {
    cards.insertAdjacentHTML("afterbegin", card.outerHTML);
});
cardsChildrens2.slice(-cardPerView2).reverse().forEach(card => {
    cards2.insertAdjacentHTML("afterbegin", card.outerHTML);
});

// Insert copies of the first few cards to end of cards for infinite scrolling
cardsChildrens.slice(0, cardPerView).forEach(card => {
    cards.insertAdjacentHTML("beforeend", card.outerHTML);
});
cardsChildrens2.slice(0, cardPerView2).forEach(card => {
    cards2.insertAdjacentHTML("beforeend", card.outerHTML);
});


// Scroll the cards at appropriate postition to hide first few duplicate cards on Firefox
cards.classList.add("no-transition");
cards.scrollLeft = cards.offsetWidth;
cards.classList.remove("no-transition");

cards2.classList.add("no-transition");
cards2.scrollLeft = cards2.offsetWidth;
cards2.classList.remove("no-transition");

// Add event listeners for the arrow buttons to scroll the cards left and right
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
    // Records the initial cursor and scroll position of the cards
    startX = e.pageX;
    startScrollLeft = cards.scrollLeft;
}

const dragStart2 = (e) => {
    isDragging2 = true;
    cards2.classList.add("dragging");
    // Records the initial cursor and scroll position of the cards
    startX2 = e.pageX;
    startScrollLeft2 = cards2.scrollLeft;
}

const dragging = (e) => {
    if(!isDragging) return; // if isDragging is false return from here
    // Updates the scroll position of the cards based on the cursor movement
    cards.scrollLeft = startScrollLeft - (e.pageX - startX);
}

const dragging2 = (e) => {
    if(!isDragging2) return; // if isDragging is false return from here
    // Updates the scroll position of the cards based on the cursor movement
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
    // If the cards is at the beginning, scroll to the end
    if(cards.scrollLeft === 0) {
        cards.classList.add("no-transition");
        cards.scrollLeft = cards.scrollWidth - (2 * cards.offsetWidth);
        cards.classList.remove("no-transition");
    }
    // If the cards is at the end, scroll to the beginning
    else if(Math.ceil(cards.scrollLeft) === cards.scrollWidth - cards.offsetWidth) {
        cards.classList.add("no-transition");
        cards.scrollLeft = cards.offsetWidth;
        cards.classList.remove("no-transition");
    }

    // Clear existing timeout & start autoplay if mouse is not hovering over cards
    clearTimeout(timeoutId);
    if(!carousel.matches(":hover")) autoPlay();
}



const infiniteScroll2 = () => {
    // If the cards is at the beginning, scroll to the end
    if(cards2.scrollLeft === 0) {
        cards2.classList.add("no-transition");
        cards2.scrollLeft = cards2.scrollWidth - (2 * cards2.offsetWidth);
        cards2.classList.remove("no-transition");
    }
    // If the cards is at the end, scroll to the beginning
    else if(Math.ceil(cards2.scrollLeft) === cards2.scrollWidth - cards2.offsetWidth) {
        cards2.classList.add("no-transition");
        cards2.scrollLeft = cards2.offsetWidth;
        cards2.classList.remove("no-transition");
    }

    // Clear existing timeout & start autoplay if mouse is not hovering over cards
    clearTimeout(timeoutId2);
    if(!carousel2.matches(":hover")) autoPlay2();
}


const autoPlay = () => {
    if(window.innerWidth < 800 || !isAutoPlay) return; // Return if window is smaller than 800 or isAutoPlay is false
    // Autoplay the cards after every 2500 ms
    timeoutId = setTimeout(() => cards.scrollLeft += firstCardWidth, 2500);
}

const autoPlay2 = () => {
    if(window.innerWidth < 800 || !isAutoPlay2) return; // Return if window is smaller than 800 or isAutoPlay is false
    // Autoplay the cards after every 2500 ms
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