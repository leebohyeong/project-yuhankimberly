<?php

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>유한킴벌리</title>
    <link rel="stylesheet" href="/assets/css/vendors.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <script src="/assets/js/vendors.js"></script>
    <script src="/assets/js/app.js"></script>
</head>
<body>
    <header class="header">
        <div>
            <h1>
                <a href="#">
                    <span>우리강산 푸르게 푸르게</span>
                </a>
            </h1>
            <nav>
                <ul>
                    <li><a href="#main__intro" class="header__link header__link--active">39주년 반성문</a></li>
                    <li><a href="#main__event" class="header__link">리액션 이벤트</a></li>
                    <li><a href="#main__donation" class="header__link">기부참여</a></li>
                    <li><a href="#main__story" class="header__link">우리의 이야기</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main">
        <section class="main__intro">
            <div class="intro-carousel">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <a href="#" style="background-image: url(/assets/images/img_intro_1.jpg)"></a>
                        </div>
                        <div class="swiper-slide">
                            <a href="#" style="background-image: url(/assets/images/img_intro_2.jpg)"></a>
                        </div>
                        <div class="swiper-slide">
                            <a href="#" style="background-image: url(/assets/images/img_intro_3.jpg)"></a>
                        </div>
                        <div class="swiper-slide">
                            <a href="#" style="background-image: url(/assets/images/img_intro_4.jpg)"></a>
                        </div>
                    </div>
                    <div class="intro-carousel__pagination"></div>
                </div>
            </div>
            <div class="intro-tab">
                <div class="intro-tab__nav">
                    <ul role="tablist">
                        <li><a href="#" class="intro-tab__nav-link intro-tab__nav-link--active">부족했던 우리 이야기</a></li>
                        <li><a href="#" class="intro-tab__nav-link">멸종위기종에게</a></li>
                        <li><a href="#" class="intro-tab__nav-link">산불 피해 후유증</a></li>
                        <li><a href="#" class="intro-tab__nav-link">사라진 숲아 미안해</a></li>
                        <li><a href="#" class="intro-tab__nav-link">2030세대에게</a></li>
                        <li><a href="#" class="intro-tab__nav-link">학교숲의 졸업</a></li>
                    </ul>
                </div>
                <div class="intro-tab__contents">
                    <div class="intro-tab__nav-link--active" style="background-image: url(/assets/images/img_intro_tab_contents_1.jpg)"></div>
                    <div style="background-image: url(/assets/images/img_intro_tab_contents_1.jpg)"></div>
                    <div style="background-image: url(/assets/images/img_intro_tab_contents_1.jpg)"></div>
                    <div style="background-image: url(/assets/images/img_intro_tab_contents_1.jpg)"></div>
                    <div style="background-image: url(/assets/images/img_intro_tab_contents_1.jpg)"></div>
                    <div style="background-image: url(/assets/images/img_intro_tab_contents_1.jpg)"></div>
                </div>
            </div>
        </section>
        <section class="main__event">
            <div style="background-image: url(/assets/images/img_event_1.jpg)">
                <p>
                    <a href="#eventModal" class="main__event-link">
                        <span>리액션 남기러 가기</span>
                    </a>
                </p>
            </div>
            <div>
                <div>
                    <p>
                        <strong>1,983</strong>건
                    </p>
                    <p>1만건 달성</p>
                    <p></p>
                </div>
            </div>
            <div style="background-image: url(/assets/images/img_event_2.jpg)"></div>
        </section>
        <section class="main__donation"></section>
        <section class="main__story"></section>
        <div class="event-modal modal" id="eventModal">
            <div class="modal__backdrop"></div>
            <div class="modal__dialog modal-boost-us__dialog">
                <div class="modal__container">
                    <div class="modal__title">
                        <img src="/assets/images/img_event_modal_title.png" alt="우리강산 푸르게 푸르게 39주년 반성문에 대한 별점과 한줄평 부탁드립니다!">
                    </div>
                    <div class="moda__content">

                        <p>
                            <button type="button" class="modal__submit"></button>
                        </p>
                    </div>
                    <div class="modal__close">
                        <button class="modal__close-button" type="button"><span class="modal__close-text">닫기</span></button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer class="footer">
        <p>
            <a href="https://www.yuhan-kimberly.co.kr/" target="_blank">
                <span>유한킴벌리 홈페이지 바로 가기</span>
            </a>
        </p>
    </footer>
</body>
</html>