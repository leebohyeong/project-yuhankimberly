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
    <div>
        <header class="header header--main-init">
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
                        <div style="background-image: url(/assets/images/img_intro_tab_contents_2.jpg)"></div>
                        <div style="background-image: url(/assets/images/img_intro_tab_contents_3.jpg)"></div>
                        <div style="background-image: url(/assets/images/img_intro_tab_contents_4.jpg)"></div>
                        <div style="background-image: url(/assets/images/img_intro_tab_contents_5.jpg)"></div>
                        <div style="background-image: url(/assets/images/img_intro_tab_contents_6.jpg)"></div>
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
                    <p>
                        <strong>1,983</strong>건
                    </p>
                    <div>
                        <p><span></span></p>
                    </div>
                </div>
                <div style="background-image: url(/assets/images/img_event_2.jpg)"></div>
                <div style="background-image: url(/assets/images/img_event_3.jpg)"></div>
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
                            <form name="frm" action="/reaction-proc.php" method="post">
                                <!-- 별점 -->
                                <div>
                                    <fieldset>
                                        <legend>내가 남긴 별점 <span>0</span>점</legend>
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
                                                <input type="radio" name="theme" id="theme1" value="후기 쓰기">
                                                <span>후기 쓰기</span>
                                            </label>
                                            <label>
                                                <input type="radio" name="theme" id="theme2" value="숲 프로그램 아이디어 제안">
                                                <span>
                                                    숲 프로그램 <br>
                                                    아이디어 제안
                                                </span>
                                            </label>
                                            <label>
                                                <input type="radio" name="theme" id="theme3" value="응원 남기기">
                                                <span>응원 남기기</span>
                                            </label>
                                        </div>
                                    </fieldset>
                                    <div>
                                        <textarea id="review" name="review" rows="3" maxlength="150" placeholder="한줄평 남기기(150자 제한)"></textarea>
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
                                    <div>
                                        <p>&lt;개인정보 수집 및 이용에 관한 안내&gt;</p>
                                        <p>
                                            <br>
                                        </p>
                                        <p>이벤트 중복참여여부 확인, 당첨자 선정 및 안내, 경품 발송을 위해 이벤트 참가자의 개인정보를 수집하고 있습니다.</p>
                                        <p>입력해주신 개인정보는 이벤트 중복참여여부 확인, 당첨자 선정 및 안내, 경품 발송, 마케팅 활용 목적 외 다른 용도로 사용되지 않으며, 당사가 수집한 개인정보는 아래와 같이 처리됩니다.</p>
                                        <p>내용을 자세히 읽어 보신 후 동의 여부를 결정하여 주시기 바랍니다.</p>
                                        <p>
                                            <br>
                                        </p>
                                        <p>1. 수집하는 개인정보의 항목:</p>
                                        <p>이름, 휴대폰 번호</p>
                                        <p>
                                            <br>
                                        </p>
                                        <p>2. 개인정보의 수집 및 이용 목적:</p>
                                        <p>이벤트 참여여부 확인, 당첨자 선정 및 안내, 경품 발송, 마케팅 활용</p>
                                        <p>
                                            <br>
                                        </p>
                                        <p>3. 개인정보의 보유 및 이용 기간:</p>
                                        <p>이벤트 종료일로부터 3개월까지 보유, 이후 일괄 폐기</p>
                                        <p>
                                            <br>
                                        </p>
                                        <p>※ 위와 같이 개인정보를 수집 · 이용하는데 동의를 거부할 권리가 있습니다. 그러나 동의를 거부할 경우, 이벤트 참여에 제한을 받으실 수 있습니다.</p>
                                        <p>
                                            <br>
                                        </p>
                                        <p>&lt;개인정보 관련 처리 업무 위탁 안내&gt;</p>
                                        <p>
                                            <br>
                                        </p>
                                        <p>이벤트 참가자가 요청한 서비스를 제공하기 위해서 아래와 같이 개인정보를 위탁하고 있습니다.</p>
                                        <p>1. 위탁 업체: ㈜그룹아이디디</p>
                                        <p>2. 위탁업무 내용:</p>
                                        <p>이벤트 진행 및 당첨자 발표를 위한 개인정보수집</p>
                                        <p>
                                            <br>
                                        </p>
                                        <p>※ 상기 업체 이외의 수탁자를 통해 이벤트 행사가 진행될 경우, 해당 이벤트 참여 신청 페이지를 통해 개인정보 처리 위탁에 대한 변경사항을 안내하도록 하겠습니다.</p>
                                    </div>
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
    </div>
</body>
</html>