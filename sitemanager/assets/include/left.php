<header class="sitemanager__header">
    <div>
        <h1><?= $SITE_NAME_?></h1>
        <div>
            <p>
                <i class="fa-regular fa-circle-user"></i>
                <strong><?= $ADMIN_ID_ ?></strong>
                <span><?= $ADMIN_NM_ ?></span>
            </p>
            <p>
                <a href="/sitemanager/logout.php"><i class="fa-solid fa-circle-exclamation"></i> 로그아웃</a>
            </p>
        </div>
    </div>
    <nav>
        <ul>
            <?php
            $adminLvArr = explode(',', $ADMIN_LV_);
            foreach($adminLvArr as $row){
                if($row != "" && $row != "ALL"){
                    $adminLv[substr($row, 0, 1)][] = $row;
                }else if($row == "ALL"){
                    $adminLv['ALL'] = "ALL";
                }
            }

            if(array_key_exists('A', $adminLv) || array_key_exists('ALL', $adminLv)){
            ?>
            <li class="<?php if ($ARR_REQUEST_URI_[1] == 'main') echo "current" ?>">
                <a href="/sitemanager/board/faq/faq-list.php">이벤트 참여자</a>
            </li>
            <?php
            }

            if(array_key_exists('A', $adminLv) || array_key_exists('ALL', $adminLv)){
            ?>
            <li class="<?php if ($ARR_REQUEST_URI_[1] == 'main') echo "current" ?>">
                <a href="/sitemanager/board/faq/day-list.php">일별 참여자</a>
            </li>
            <?php
            }

            if(array_key_exists('A', $adminLv) || array_key_exists('ALL', $adminLv)){
            ?>
            <li class="<?php if ($ARR_REQUEST_URI_[1] == 'main') echo "current" ?>">
                <a href="/sitemanager/board/faq/month-list.php">월별 참여자</a>
            </li>
            <?php
            }
            ?>

            <li class="<?php if ($ARR_REQUEST_URI_[1] == 'manager') echo "current" ?>">
                <a href="/sitemanager/manager/mypage/mypage.php">관리자</a>
                <ul>
                    <li class="<?php if (strpos($ARR_REQUEST_URI_[3], 'mypage.php') !== false) echo "current" ?>">
                        <a href="/sitemanager/manager/mypage/mypage.php">마이페이지</a>
                    </li>
                    <?php
                    if((isset($adminLv['F']) && in_array('FB', $adminLv['F'])) || array_key_exists('ALL', $adminLv)){
                        ?>
                        <li class="<?php if (strpos($ARR_REQUEST_URI_[2], 'account') !== false) echo "current" ?>">
                            <a href="/sitemanager/manager/account/account-list.php">관리자 계정/권한관리</a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </li>
        </ul>
    </nav>
</header>