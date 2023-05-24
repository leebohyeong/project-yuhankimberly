<?php
/** ==============================================================================
 * File: 관리자 > 게시판 > 고객센터FAQ 노출순서 처리 (faq-order-change-proc.php)
 * Date: 2023-02-28 오후 12:02
 * Created by @csw9569.DevGroup
 * Copyright 2023, baskinrobbins.co.kr/(Group IDD). All rights are reserved
 * ==============================================================================*/
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use \Groupidd\Model\ModelBase;
use \Groupidd\Library\Validator;
use \Groupidd\Common\CommonFunc;

$errorFlag = false;
// request check
$requestInfo    = array(
    'page'              => $_POST['page'] ?? 1 ,
    'search'            => $_POST['search'] ?? '',                            // 검색
    'findword'          => $_POST['findword'] ?? '',                    // 검색어
    'search_category'   => $_POST['search_category'] ?? '',      // 구분
    'from_date'         => $_POST['from_date'] ?? '',      // 등록날짜 시작
    'to_date'           => $_POST['to_date'] ?? '',      // 등록날짜 종료
    'search_is_view'    => $_POST['search_is_view'] ?? ''         // 노출여부
);

$validator = new Validator($requestInfo);
$validator->rule('integer', 'page');
$validator->rule('in', 'search', array('answer','content'));
if($validator->validate()) {
    $requestInfo = $validator->data();
}else{
    echo $validator->errorsJsAlert("faq-list.php?".http_build_query($requestInfo));
    exit();
}

$validator = new Validator();
$validator->rule('array', $_POST['seq']);
if($validator->validate()) {       					// validation 성공
    $seqArray = $_POST['seq'];
} else {               									// validation 실패
    echo $validator->errorsJsAlert("faq-list.php?".http_build_query($requestInfo));
    exit();
}

// database 처리
$db = new ModelBase();
$db->beginTransaction();
foreach ($seqArray as $key => $val) {

    $db->init();
    $db->from('BOARD_FAQ');
    $db->where('seq', $val);
    $updateInfo = array(
        "order_num" => ($key+1)
    );
    if ($db->update($updateInfo)) {
        $errorFlag = false;
    } else {
        $errorFlag = true;
    }
}

if (!$errorFlag){
    //commit
    $db->executeTransaction();
    $db->close();
    CommonFunc::history_proc($ADMIN_ID_, $USER_IP_, '게시판', '고객센터FAQ 노출 순서', "{$ADMIN_ID_}님이 고객센터FAQ 노출 순서를 수정하였습니다");
    CommonFunc::jsAlert('고객센터FAQ 노출순서가 수정 되었습니다.','location.href="faq-list.php?'.http_build_query($requestInfo).'";');
}else{
    //rollBack
    $db->rollBack();
    $db->close();

    CommonFunc::jsAlert("고객센터FAQ 노출순서 수정에 실패하였습니다.\\n확인 후 다시 시도해 주세요.",'location.href="faq-list.php?'.http_build_query($requestInfo).'";');
}
?>