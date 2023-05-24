<!--
    [D]
    pagination__item pagination__control의 비활성화는 클래스 pagination__item--disabled 추가

    활성화 페이지는 pagination__item--current 클래스가 존재하며 클릭이 안되게 strong으로 생성
    <li class="pagination__item pagination__item--current">
        <strong class="pagination__link"></strong>
    </li>
-->
<ul class="pagination">
    <li class="pagination__item pagination__control pagination__item--first">
        <a href="#" class="pagination__link">
            <span class="pagination__page">
                처음
            </span>
        </a>
    </li>
    <li class="pagination__item pagination__control pagination__item--prev pagination__item--disabled">
        <span class="pagination__link">
            <span class="pagination__page">
                이전
            </span>
        </span>
    </li>
    <li class="pagination__item">
        <a href="#" class="pagination__link">
            <span class="pagination__page">
                1
            </span>
        </a>
    </li>
    <li class="pagination__item pagination__item--current">
        <strong class="pagination__link">
            <span class="pagination__page">
                2
            </span>
        </strong>
    </li>
    <li class="pagination__item">
        <a href="#" class="pagination__link">
            <span class="pagination__page">
                3
            </span>
        </a>
    </li>
    <li class="pagination__item">
        <a href="#" class="pagination__link">
            <span class="pagination__page">
                4
            </span>
        </a>
    </li>
    <li class="pagination__item">
        <a href="#" class="pagination__link">
            <span class="pagination__page">
                5
            </span>
        </a>
    </li>
    <li class="pagination__item pagination__control pagination__item--next">
        <a href="#" class="pagination__link">
            <span class="pagination__page">
                다음
            </span>
        </a>
    </li>
    <li class="pagination__item pagination__control pagination__item--last">
        <a href="#" class="pagination__link">
            <span class="pagination__page">
                마지막
            </span>
        </a>
    </li>
</ul>