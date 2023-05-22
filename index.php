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
                <a href="#" class="header__logo">
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
        <section class="main__intro" id="main__intro">
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
        <section class="main__event" id="main__event">
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
        </section >
        <section class="main__donation" id="main__donation"></section>
        <section class="main__story" id="main__story"></section>
        <div class="event-modal modal" id="eventModal">
            <div class="modal__backdrop"></div>
            <div class="modal__dialog modal-boost-us__dialog">
                <div class="modal__container event-modal__container">
                    <div class="modal__title event-modal__title">
                        <img src="/assets/images/img_event_modal_title.png" alt="우리강산 푸르게 푸르게 39주년 반성문에 대한 별점과 한줄평 부탁드립니다!">
                    </div>
                    <div class="moda__content event-modal__contents">
                        <form name="" action="" method="post">
                            <!-- 별점 -->
                            <div>
                                <fieldset>
                                    <legend>내가 남긴 별점 <span>5</span>점</legend>
                                    <div>
                                        <input type="radio" name="score" value="5" id="score5"><label for="score5"></label>
                                        <input type="radio" name="score" value="4" id="score4"><label for="score4"></label>
                                        <input type="radio" name="score" value="3" id="score3"><label for="score3"></label>
                                        <input type="radio" name="score" value="2" id="score2"><label for="score2"></label>
                                        <input type="radio" name="score" value="1" id="score1"><label for="score1"></label>
                                    </div>
                                </fieldset>
                            </div>
                            <!-- 한줄평 남기기 -->
                            <div>
                                <fieldset>
                                    <legend>Q. 주제 선택 후 한줄평을 남겨주세요.</legend>
                                    <div>
                                        <label>
                                            <input type="radio" name="theme" id="theme1" value="">
                                            <span>후기 쓰기</span>
                                        </label>
                                        <label>
                                            <input type="radio" name="theme" id="theme2">
                                            <span>
                                                숲 프로그램 <br>
                                                아이디어 제안
                                            </span>
                                        </label>
                                        <label>
                                            <input type="radio" name="theme" id="theme3">
                                            <span>응원 남기기</span>
                                        </label>
                                    </div>
                                </fieldset>
                                <div>
                                    <textarea id="review" name="review" maxlength="150" placeholder="한줄평 남기기(150자 제한)"></textarea>
                                    <p>
                                        <span></span>
                                        / 150
                                    </p>
                                </div>
                            </div>
                            <!-- 이름 -->
                            <div>
                                <p>이름 *</p>
                                <input type="text" name="name" placeholder="이름을 입력해 주세요.">
                            </div>
                            <!-- 휴대폰 번호 * -->
                            <div>
                                <p>휴대폰 번호 *</p>
                                <input type="text" name="phone" placeholder="'-'없이 숫자만 입력해 주세요.">
                            </div>
                            <!-- 개인정보 수집 동의 -->
                            <div>
                                <p>개인정보 수집 동의</p>
                                <div></div>
                                <fieldset>
                                    <div>
                                        <label>
                                            <input type="checkbox" name="agree1" id="">
                                            <span>(필수) 만 14세 이상입니다</span>
                                        </label>
                                        <label>
                                            <input type="checkbox" name="agree2" id="">
                                            <span>(필수) 동의합니다</span>
                                        </label>
                                    </div>
                                </fieldset>
                            </div>
                            <p>
                                <button type="submit" class="modal__submit"></button>
                            </p>
                        </form>
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