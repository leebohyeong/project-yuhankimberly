<?php
/** ==============================================================================
 * File: 관리자 > 게시판 > 고객센터FAQ 리스트 (faq-list.php)
 * Date: 2023-02-28 오후 12:02
 * Created by @csw9569.DevGroup
 * Copyright 2023, baskinrobbins.co.kr/(Group IDD). All rights are reserved
 * ==============================================================================*/
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use \Groupidd\Model\ModelBase;
use \Groupidd\Common\CommonFunc;
use \Groupidd\Library\Validator;

// request check
$requestInfo    = array(
    'page'              => $_GET['page'] ?? 1,
    'search'            => $_GET['search'] ?? '',
    'findword'          => $_GET['findword'] ?? '',
    'from_date'         => $_GET['from_date'] ?? '',
    'to_date'           => $_GET['to_date'] ?? '',
    'mail'    => $_GET['mail'] ?? ''
);

$validator = new Validator($requestInfo);
$validator->rule('integer', 'page');
$validator->rule('in', 'search', array('username','userphone'));
if($validator->validate()) {                // validation 성공
    $requestInfo = $validator->data();
    $page     	 = $requestInfo['page'];    // 페이지 번호
}else{
    echo $validator->errorsJsAlert();
    exit();
}

if($requestInfo["mail"]=="success"){
    CommonFunc::jsAlert('메일이 전송되었습니다.','');
}

$db = new ModelBase();
$db->from("REACTION");
// search
if( !empty($requestInfo['search']) && !empty($requestInfo['findword'])){
    $db->where($requestInfo['search'], CommonFunc::stringEncrypt($requestInfo['findword'], $ENCRYPT_KEY_));
//}elseif (empty($requestInfo['search']) && !empty($requestInfo['findword']) && $requestInfo['search']=="userphone"){
}
if ($requestInfo['from_date'] != '' && $requestInfo['to_date'] != '') {
    $db->between('reg_date', "'".$requestInfo['from_date'].' 00:00:00'."'",  "'".$requestInfo['to_date'].' 23:59:59'."'");
}
// paging setting
$listCnt        = $db->getCountAll();
$perPage        = 20;
$pageSize       = ceil($listCnt/$perPage);
$currentPage    = ($page-1) * $perPage;

$db->select("seq, score, category, content, username, userphone, reg_date", true);
$db->orderby('seq', 'desc');
$db->limit($perPage, $currentPage );
$result = $db->getAll();

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <?php require_once $ROOT_PATH_.'/sitemanager/assets/include/head.php'; ?>
</head>
<body class="sitemanager">
<?php require_once $ROOT_PATH_.'/sitemanager/assets/include/left.php'; ?>

<section class="sitemanager__body">
    <headder class="sitemanager__body-header">
        <ul>
            <li>게시판</li>
        </ul>
        <h2>이벤트 참여자</h2>
    </headder>
    <form name="frm_search" class="search" >
        <input type="hidden" name="page" value="<?=$page?>"/>
        <div>
            <div>
                <div></div>
                <div>
                    <div class="select-input-group">
                        <select name="search" class="form-input width-100">
                            <option value="">선택</option>
                            <option value="username" <?=CommonFunc::getSelected('username', $requestInfo['search'])?>>이름</option>
                            <option value="userphone" <?=CommonFunc::getSelected('userphone', $requestInfo['search'])?>>연락처</option>
                        </select>
                        <input type="text" name="findword" value="<?=$requestInfo['findword']?>" class="form-input">
                    </div>
                </div>
            </div>

            <div>
                <div>기간</div>
                <div>
                    <div class="vanillajs-daterangepicker">
                        <input type="text" name="from_date" value="<?=$requestInfo['from_date']?>" readonly>
                        <span>-</span>
                        <input type="text" name="to_date" value="<?=$requestInfo['to_date']?>" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <button class="button" type="submit">
                <i class="fa-solid fa-magnifying-glass"></i> 검색하기
            </button>
        </div>
    </form>

    <div class="contents">
        <div class="top-group">
            <p>총 <?=number_format($listCnt)?>건이 검색되었습니다.</p>
            <div>
                <button type="button" class="button red" data-btn="eventBtn" data-fn="excel">메일 전송</button>
            </div>
        </div>
        <form name="frm" action="/sitemanager/board/faq/faq-del-hide-proc.php" method="post">
            <input type="hidden" name="page" value="<?=$requestInfo['page']?>">
            <input type="hidden" name="search" value="<?=$requestInfo['search']?>">
            <input type="hidden" name="findword" value="<?=$requestInfo['findword']?>">
            <input type="hidden" name="from_date" value="<?=$requestInfo['from_date']?>">
            <input type="hidden" name="to_date" value="<?=$requestInfo['to_date']?>">
            <input type="hidden" name="mail" value="<?=$requestInfo['mail']?>">
            <table class="table">
                <colgroup>
                    <col width="5%">
                    <col width="7%">
                    <col width="12%">
                    <col width="10%">
                    <col width="17%">
                    <col width="*%">
                    <col width="12%">
                </colgroup>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>이름</th>
                        <th>연락처</th>
                        <th>별점</th>
                        <th>주제</th>
                        <th>한줄평</th>
                        <th>등록일</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if($listCnt > 0){
                    foreach ($result as $idx=>$row) {
                        $rownum = $listCnt-($idx+$currentPage);
                        if($row['score']==1){
                            $score = "★";
                        }elseif ($row['score']==2){
                            $score = "★★";
                        }elseif ($row['score']==3){
                            $score = "★★★";
                        }elseif ($row['score']==4){
                            $score = "★★★★";
                        }elseif ($row['score']==5){
                            $score = "★★★★★";
                        }
                        $username = CommonFunc::stringDecrypt($row['username'], $ENCRYPT_KEY_);
                        $uname = mb_substr($username, 0, 1);
                        for($i=1; $i<mb_strlen($username);$i++){
                            $uname = $uname." *";
                        }
                        $userphone = CommonFunc::stringDecrypt($row['userphone'], $ENCRYPT_KEY_);
                        $uphone = mb_substr($userphone, 0, 7);
                        for($i=7; $i<mb_strlen($userphone);$i++){
                            $uphone = $uphone."*";
                        }
                        ?>
                        <tr>
                            <td>
                                <?=$rownum?>
                            </td>
                            <td>
                                <?=$uname?>
                            </td>
                            <td>
                                <?=$uphone?>
                            </td>
                            <td>
                                <?=$score?>
                            </td>
                            <td>
                                <?=$row['category']?>
                            </td>
                            <td>
                                <?=$row['content']?>
                            </td>
                            <td>
                                <?=$row['reg_date']?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
            </table>

            <div class="bottom-group">
            </div>

            <nav class="pagination">
                <?=CommonFunc::getPaging($page, $perPage, $pageSize, $requestInfo)?>
            </nav>
        </form>
    </div>
</section>


<script>
    const EventListFn = {
        excel: function () {
            location.href = "excel.php";
        }
    }


</script>
<?php require_once $ROOT_PATH_.'/sitemanager/assets/include/footer.php'; ?>
</body>
</html>

