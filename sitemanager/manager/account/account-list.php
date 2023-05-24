<?php
/** ==============================================================================
 * File: 관리자 > 관리자 계정/권한관리 (account-list.php)
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
    'page'              => $_GET['page'] ?? 1 ,
    'search'            => $_GET['search'] ?? '',
    'findword'          => $_GET['findword'] ?? '',
    'search_status'     => $_GET['search_status'] ?? ''
);

$validator = new Validator($requestInfo);
$validator->rule('integer', 'page');
$validator->rule('in', 'search', array('admin_id','admin_name', 'email'));
$validator->rule('in', 'search_status', array('W','C', 'S'));
if($validator->validate()) {                // validation 성공
    $requestInfo = $validator->data();
    $page     	 = $requestInfo['page'];    // 페이지 번호
}else{
    echo $validator->errorsJsAlert();
    exit();
}

$db = new ModelBase();
$db->from("ADMIN_MEMBER");

// search
if( !empty($requestInfo['search']) && !empty($requestInfo['findword'])){
    $db->like($requestInfo['search'], $requestInfo['findword']);
}
if(!empty($requestInfo['findword']) && empty($requestInfo['search'])){
    $db->like('admin_id', $requestInfo['findword']);
    $db->or_like('admin_name', $requestInfo['findword']);
    $db->or_like('email', $requestInfo['findword']);
}
if (!empty($requestInfo['search_status'])){
    $db->where('status', $requestInfo['search_status']);
}

// paging setting
$listCnt        = $db->getCountAll();
$perPage        = 10;
$pageSize       = ceil($listCnt/$perPage);
$currentPage    = ($page-1) * $perPage;

$db->select("seq, admin_id, admin_name, email, status, is_del, del_memo, reg_date", true);
$db->orderby('seq','DESC');
$db->limit($perPage, $currentPage);
$result = $db->getAll();

CommonFunc::history_proc($ADMIN_ID_, $USER_IP_, '관리자', '관리자 계정/권한관리', $ADMIN_ID_.'님이 관리자 계정 리스트를 조회하였습니다.');

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <?php require_once $ROOT_PATH_ . '/sitemanager/assets/include/head.php'; ?>
</head>
<body class="sitemanager">
<?php require_once $ROOT_PATH_ . '/sitemanager/assets/include/left.php'; ?>

<section class="sitemanager__body">
    <headder class="sitemanager__body-header">
        <ul>
            <li>관리자</li>
        </ul>
        <h2>관리자 계정 리스트</h2>
    </headder>
    <form name="frm_search" class="search" method="get">
        <input type="hidden" name="page" value="<?=$page?>"/>
        <div>
            <div>
                <div></div>
                <div>
                    <div class="select-input-group">
                        <select name="search" class="form-input width-100">
                            <option value="">전체</option>
                            <option value="admin_id" <?=CommonFunc::getSelected('admin_id', $requestInfo['search'])?>>아이디</option>
                            <option value="admin_name" <?=CommonFunc::getSelected('admin_name', $requestInfo['search'])?>>이름</option>
                            <option value="email" <?=CommonFunc::getSelected('email', $requestInfo['search'])?>>이메일</option>
                        </select>
                        <input type="text" name="findword" value="<?=$requestInfo['findword']?>" class="form-input">
                    </div>
                </div>
            </div>
            <div>
                <div>계정상태</div>
                <div>
                    <div class="select-input-group">
                        <div>
                            <select name="search_status" class="form-input width-100">
                                <option value="">전체</option>
                                <option value="W" <?=CommonFunc::getSelected('W', $requestInfo['search_status'])?>>승인대기</option>
                                <option value="C" <?=CommonFunc::getSelected('C', $requestInfo['search_status'])?>>승인완료</option>
                                <option value="S" <?=CommonFunc::getSelected('S', $requestInfo['search_status'])?>>권한중지</option>
                            </select>
                        </div>
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
            <p>총 <?=$listCnt?>건이 검색되었습니다.</p>

            <div>
                <button type="button" class="button button--blue" data-btn="eventBtn" data-fn="register">등록</button>
            </div>
        </div>

        <table class="table">
            <thead>
            <tr>
                <th>번호</th>
                <th>아이디</th>
                <th>이름</th>
                <th>이메일</th>
                <th>계정상태</th>
                <th>아이디 생성일</th>
                <th>관리</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if($listCnt > 0){
                foreach ($result as $idx=>$row) {

                    if($row['status'] == 'W') $status = '승인대기';
                    else if($row['status'] == 'C') $status = '승인완료';
                    else if($row['status'] == 'S') $status = '권한중지';

                    $admin_id = $row['admin_id'];
                    $admin_name = $row['admin_name'];
                    $email = $row['email'];

                    if($row['is_del'] == 'Y'){
                        $status = '<span style="color:red">계정삭제('.$row["del_memo"].')</span>';
                        $admin_id = '-';
                        $email = '-';
                    }
                    ?>
                    <tr>
                        <td>
                            <?=$listCnt-($idx+$currentPage)?>
                        </td>
                        <td>
                            <?=$admin_id?>
                        </td>
                        <td>
                            <?=$admin_name?>
                        </td>
                        <td>
                            <?=$email?>
                        </td>
                        <td>
                            <?=$status?>
                        </td>
                        <td>
                            <?=$row['reg_date']?>
                        </td>
                        <td>
                            <a href="account-modify.php?seq=<?=$row['seq'].'&'.http_build_query($requestInfo) ?>">[관리]</a>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>

        <nav class="pagination">
            <?=CommonFunc::getPaging($page, $perPage, $pageSize, $requestInfo)?>
        </nav>
    </div>
</section>
<script>
    const EventListFn = {
        register: function () {
            location.href = "/sitemanager/manager/account/account-register.php?<?=http_build_query($requestInfo)?>";
        },
    }
</script>

<?php require_once $ROOT_PATH_ . '/sitemanager/assets/include/footer.php'; ?>
</body>
</html>
