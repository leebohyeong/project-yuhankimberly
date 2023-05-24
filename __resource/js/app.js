import {findOne, find, getOffset, on} from './helper';
import Modal from "./Modal";
import Swiper, {Autoplay, Navigation, Pagination} from 'swiper';

Swiper.use([Navigation, Pagination]);

const app = () => {
    //ScrollSpy
    (() => {
        const header = findOne('.header');

        const headerHeight = header.clientHeight;
        const headerInitClassName = 'header--main-init';
        const scrollHeader = () => header.classList[window.scrollY < header.clientHeight ? 'add' : 'remove'](headerInitClassName);

        const links = find('.header__link', header);
        const bannerLinks = find('.intro-carousel a');
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
                    alert('Coming Soon');
                }
            });
        });

        bannerLinks.forEach((bannerLink, index) => {
            on(bannerLink, 'click', (event) => {
                event.preventDefault();

                if (index !== 2 && index !== 3) {
                    moveSection(index);
                    toggleLink();
                } else {
                    alert('Coming Soon');
                }
            })
        })

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

    //main event reaction
    (() => {
        const mainEvent = findOne('.main__event');
        const numberCases = findOne('p strong', mainEvent);
        const numberCasesInner = numberCases.innerText.trim() || 0;
        const numberCasesGauge = findOne('p > span', mainEvent);

        const getReactionGauge = () => {
            numberCasesGauge.style.width = `calc(${numberCasesInner ? numberCasesInner.replace(/\D/g, '') : numberCasesInner} / 10000 * 100%)`
        };

        on(window, 'load', getReactionGauge);

    })();

    //리액션 event
    (() => {
        const modal = new Modal();
        const mainEvent = findOne('.main__event');
        const eventModal = findOne('.event-modal');
        const triggers = findOne('.main__event-link', mainEvent);
        const getId = triggers.getAttribute('href');
        const form = findOne('form', eventModal);
        const formScores = find('[name="score"]', form);
        const formThemes = find('[name="theme"]', form);
        const formReview = findOne('[name="review"]', form);
        const formName = findOne('[name="name"]', form);
        const formNameCheck = /^[가-힣a-zA-Z]+$/;
        const formPhone = findOne('[name="phone"]', form);
        const formPhoneCheck = /^[0-9]+$/;
        const formAgree1 = findOne('[name="agree1"]', form);
        const formAgree2 = findOne('[name="agree2"]', form);

        triggers.addEventListener('click', (event) => {
            event.preventDefault();

            form.reset()
            reviewScore.innerHTML = "0";
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
        // document.addEventListener('keyup', (event) => {
        formReview.addEventListener('input', (event) => {
            event.preventDefault();

            const count = findOne('textarea + p span', eventModal);
            const content = formReview.value;

            count.innerHTML = content.length;

            if (content.length > 150) {
                alert('150자 제한');
                count.innerHTML = 150;
            }
        })

        //form 유효성검사
        const isValid = () => {
            if (formThemes.every(input => !input.checked)) {
                alert('주제를 선택해 주세요.');
                formThemes[0].focus();
                return false;
            }

            if (formReview.value.trim().length < 10) {
                alert('한줄평은 최소 10자 이상입니다.');
                formReview.focus();
                return false;
            }

            if (!formName.value.trim()) {
                alert('이름을 입력해주세요.');
                formName.focus();
                return false;
            }

            if (!formNameCheck.test(formName.value.trim())) {
                alert('이름을 정확하게 입력해주세요.');
                formName.focus();
                return false;
            }

            if (!formPhone.value.trim()) {
                alert('연락처를 입력해주세요.');
                formPhone.focus();
                return false;
            }

            if (!formPhoneCheck.test(formPhone.value.trim())) {
                alert('연락처를 정확하게 입력해주세요.');
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

            if(isValid()){
                form.submit();
            }
        });

    })();

    //intro
    (() => {
        const intro = findOne('.intro');
        const introUrl = new URLSearchParams(location.search);

        const getIntroUrlValue = introUrl.get('qr');
        // const introUrlCheck = introUrl.has('qr');

        if(!getIntroUrlValue) {
            intro.style.display = 'none'
        } else {
            const headerHeight = findOne('.header').clientHeight;
            const tabList = findOne('.intro-tab');
            const tabListTop = getOffset(tabList).top;;
            const tabLink = find('.intro-tab__nav-link')[getIntroUrlValue-1];
            const image = new Image();

            const moveSection = () => {
                window.scroll({
                    top: tabListTop - headerHeight,
                    behavior: 'smooth'
                });
            }

            moveSection();
            tabLink.click();

            image.onload = function() {
                setTimeout(() => {
                    intro.style.display = 'none'
                }, 1000)
            };
            image.src = "/assets/images/img_loading.jpg";

        }

    })();

}

document.addEventListener('DOMContentLoaded', app);