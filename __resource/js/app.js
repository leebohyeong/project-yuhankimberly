import {findOne, find, getOffset, on} from './helper';
import Tab from "./Tab";
import Modal from "./Modal";
import Swiper, {Autoplay, Navigation, Pagination } from 'swiper';
Swiper.use([Navigation, Pagination]);


const app = () => {

    //main intro
    (() => {
        const mainIntro = findOne('.main__intro');
        const introCarousel = new Swiper('.intro-carousel .swiper', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            slidesPerView: 'auto',
            // allowTouchMove: false,
            pagination: {
                el: ".intro-carousel__pagination",
                clickable: true,
            },

            modules: [Autoplay, Pagination],
        });
    })();

    //main intro tab
    (() => {
        const category = findOne('.intro-tab');
        const tabMenus = find('.intro-tab__nav a', category);
        const tabPanels = find('.intro-tab__contents div', category);

        const toggleTabPanels = (currentIndex) => {
            tabMenus.forEach((menu, index) =>
                menu.classList[
                    currentIndex === index
                        ? 'add'
                        : 'remove'
                    ](`intro-tab__nav-link--active`));
            tabPanels.forEach((panel, index) =>
                panel.classList[
                    currentIndex === index
                        ? 'add'
                        : 'remove'
                    ](`intro-tab__nav-link--active`));
        };

        tabMenus.forEach((menu, index) =>
            on(menu, 'click', (event) => {
                event.preventDefault();

                toggleTabPanels(index);
            })
        );
    })();

    //리액션 event
    (() => {
        const modal = new Modal();
        const mainEvent = findOne('.main__event');
        const triggers = findOne('.main__event-link', mainEvent);
        const getId = triggers.getAttribute('href');

        // const contents = triggers.reduce((contents, trigger) => {
        //     const id = getId(trigger);
        //     const content = findOne(id);
        //
        //     contents[id] = content;
        //
        //     return contents;
        // }, {});

        triggers.addEventListener('click', (event) => {
            event.preventDefault();

            const content = findOne(getId)
            console.log(content);
            modal.open(content);
        });

        // triggers.forEach((trigger) => {
        //     on(trigger, 'click', (event) => {
        //         event.preventDefault();
        //
        //         const id = getId(trigger);
        //         const content = contents[id];
        //
        //         modal.open(content);
        //     });
        // });



    })();

}

document.addEventListener('DOMContentLoaded', app);