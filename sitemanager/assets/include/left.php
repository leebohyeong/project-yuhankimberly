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
                <a href="/sitemanager/board/faq/faq-list.php">리액션 참가자</a>
            </li>
            <?php
            }
            ?>

        </ul>
    </nav>
</header>