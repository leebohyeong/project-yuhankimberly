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
            slidesPerView: 'auto',
            // allowTouchMove: false,
            pagination: {
                el: ".intro-carousel__pagination",
            },

            modules: [Pagination],
        });
    })();




}


document.addEventListener('DOMContentLoaded', app);