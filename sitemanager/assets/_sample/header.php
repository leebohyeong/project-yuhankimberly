<header class="site-header">
    <div class="site-profile">
        <h1 class="site-logo">
            <strong class="site-logo__text">
                TEST
            </strong>
        </h1>
        <p class="site-admin">
            <strong class="site-admin__name">
                admin
            </strong>
            <span class="site-admin__greeting">
                관리자님 좋은 하루되세요.
            </span>
        </p>
        <div class="site-logout">
            <a href="#" class="site-logout__button button button--logout">
                <span class="button__text">로그아웃</span>
            </a>
        </div>
    </div>

    <!--
        [D] 활성화 되는 li와 a 태그 클래스 추가
        <li class="site-menu__item site-menu__item--active">
        <a href="#" class="site-menu__link site-menu__link--active">
    -->
    <nav class="site-menu">
        <ul class="site-menu__list">
            <li class="site-menu__item">
                <a href="#" class="site-menu__link">
                    <span class="site-menu__text">
                        main-menu-1
                    </span>
                </a>
                <ul class="site-menu__list site-menu__list--sub">
                    <li class="site-menu__item">
                        <a href="#" class="site-menu__link">
                            <span class="site-menu__text">
                                main-menu-sub-1-1
                            </span>
                        </a>
                    </li>
                    <li class="site-menu__item">
                        <a href="#" class="site-menu__link">
                            <span class="site-menu__text">
                                main-menu-sub-1-2
                            </span>
                        </a>
                    </li>
                    <li class="site-menu__item">
                        <a href="#" class="site-menu__link">
                            <span class="site-menu__text">
                                main-menu-sub-1-3
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="site-menu__item site-menu__item--active">
                <a href="#" class="site-menu__link site-menu__link--active">
                    <span class="site-menu__text">
                        main-menu-2
                    </span>
                </a>
                <ul class="site-menu__list site-menu__list--sub">
                    <li class="site-menu__item">
                        <a href="#" class="site-menu__link">
                            <span class="site-menu__text">
                                main-menu-sub-2-1
                            </span>
                        </a>
                    </li>
                    <li class="site-menu__item site-menu__item--active">
                        <a href="#" class="site-menu__link site-menu__link--active">
                            <span class="site-menu__text">
                                main-menu-sub-2-2
                            </span>
                        </a>
                        <ul class="site-menu__list site-menu__list--sub-sub">
                            <li class="site-menu__item">
                                <a href="#" class="site-menu__link">
                                    <span class="site-menu__text">
                                        main-menu-sub-2-2-1
                                    </span>
                                </a>
                            </li>
                            <li class="site-menu__item site-menu__item--active">
                                <a href="#" class="site-menu__link site-menu__link--active">
                                    <span class="site-menu__text">
                                        main-menu-sub-2-2-2
                                    </span>
                                </a>
                            </li>
                            <li class="site-menu__item">
                                <a href="#" class="site-menu__link">
                                    <span class="site-menu__text">
                                        main-menu-sub-2-2-3
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="site-menu__item">
                        <a href="#" class="site-menu__link">
                            <span class="site-menu__text">
                                main-menu-sub-2-3
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="site-menu__item">
                <a href="#" class="site-menu__link">
                    <span class="site-menu__text">
                        main-menu-3
                    </span>
                </a>
            </li>
        </ul>
    </nav>
</header>