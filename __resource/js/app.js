import {findOne, find, getOffset, on} from './helper';
import Modal from "./Modal";
import Swiper, {Autoplay, Navigation, Pagination } from 'swiper';
Swiper.use([Navigation, Pagination]);

const app = () => {
    //ScrollHeader
    (() => {
        const header = findOne('.header');
        // const headerInitClassName = 'header--main-init';
        // const scrollHeader = () => header.classList[window.scrollY < header.clientHeight ? 'add' : 'remove'](headerInitClassName);
        //
        // on(window, 'scroll', scrollHeader);
    })();

    //ScrollSpy
    (() => {
        const header = findOne('.header');
        const headerHeight = header.clientHeight;

        const headerInitClassName = 'header--main-init';
        const scrollHeader = () => header.classList[window.scrollY < header.clientHeight ? 'add' : 'remove'](headerInitClassName);

        const links = find('.header__link', header);
        const sections = links.map(link => findOne(link.getAttribute('href')));
        const sectionsStart = [];
        const getSectionsStart = () => sections.forEach((section, index) => sectionsStart[index] = ~~(getOffset(section).top - headerHeight));
        const toggleLink = () => {
            const scrollY = window.scrollY;
            let currentIndex = sectionsStart.length - 1;

            while (currentIndex) {
                if (scrollY >= sectionsStart[currentIndex]) break;

                currentIndex--;
            }

            links.forEach((link, index) => {
                link.classList[index === currentIndex ? 'add' : 'remove']('header__link--active');
            });
        };
        const moveSection = (index) => {
            window.scroll({
                top: sectionsStart[index],
                behavior: 'smooth'
            });
        };

        getSectionsStart();

        links.forEach((link, index) => {

            on(link, 'click', (event) => {
                event.preventDefault();
                if (index !== 2 && index !== 3) {
                    moveSection(index);
                    toggleLink();
                } else {
                    alert('Comming Soon');
                }
            });

        });

        on(window, 'load', getSectionsStart);
        on(window, 'resize', getSectionsStart);
        on(window, 'scroll', toggleLink);
        on(window, 'scroll', scrollHeader);

    })();

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
        const form = findOne('.event-modal form');
        const formScores = find('[name="score"]', form);
        const formThemes = find('[name="theme"]', form);
        const formReview = findOne('[name="review"]', form);
        const formName = findOne('[name="name"]', form);
        const formPhone = findOne('[name="phone"]', form);
        const formAgree1 = findOne('[name="agree1"]', form);
        const formAgree2 = findOne('[name="agree2"]', form);

        triggers.addEventListener('click', (event) => {
            event.preventDefault();

            form.reset()
            const content = findOne(getId)
            modal.open(content);
        });

        //별점 체크
        const reviewScore = findOne('.event-modal__contents form > div:nth-of-type(1) legend span', form);

        formScores.forEach((formScore, index) => {
            on(formScore, 'click', () => {
                //console.log(formScore.value);
                reviewScore.innerHTML = formScore.value;
            })
        })

        //글자수 제한
        document.addEventListener('keyup', (event) => {
            event.preventDefault();

            const eventModal = findOne('.event-modal');
            const textarea = findOne('#review', eventModal);
            const count = findOne('textarea + p span', eventModal);

            const content = textarea.value;
            count.innerHTML = content.length;

            if (content.length > 150) {
                alert('150자 제한');
                count.innerHTML = 150;
            }
        })

        //유효성검사
        const isValid = () => {
            if (formThemes.every(input => !input.checked)) {
                alert('주제를 선택해 주세요.');
                formThemes[0].focus();
                return false;
            }

            if (!formReview.value.trim()) {
                alert('한줄평은 최소 10자 이상입니다.');
                formReview.focus();
                return false;
            }

            if (!formName.value.trim()) {
                if(!formName.value.length < 10) {
                    alert('이름을 입력해주세요.');
                    formName.focus();
                    return false;
                }
            }

            if (!formPhone.value.trim()) {
                alert('연락처를 입력해주세요.');
                formPhone.focus();
                return false;
            }

            if (!formAgree1.checked) {
                alert('개인정보 수집에 동의해 주세요.');
                formAgree1.focus();
                return false;
            }

            if (!formAgree2.checked) {
                alert('개인정보 수집에 동의해 주세요.');
                formAgree2.focus();
                return false;
            }

            return true;
        }

        form.addEventListener('submit', (event) => {
            event.preventDefault();

            if (isValid()) {
                // const formData = new FormData(form);

                // fetch(form.action, {
                //     method: form.method,
                //     body: formData
                // })
                //     .then(response => response.json())
                //     .then(data => {
                //         if (data.result === false){
                //             alert(data.message);
                //         }else{
                //             alert(data.message);
                //         }
                //     })
                //     .catch(error => {
                //         console.error(error);
                //     });
            }

        });



    })();


}

document.addEventListener('DOMContentLoaded', app);