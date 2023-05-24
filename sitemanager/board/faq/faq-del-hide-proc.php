<?php
/** ==============================================================================
 * File: 관리자 > 게시판 > 고객센터FAQ 삭제/숨김 처리 (faq-del-hide-proc.php)
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
    'search'            => $_POST['search'] ?? '',
    'findword'          => $_POST['findword'] ?? '',
    'search_category'   => $_POST['search_category'] ?? '',
    'from_date'         => $_POST['from_date'] ?? '',
    'to_date'           => $_POST['to_date'] ?? '',
    'search_is_view'    => $_POST['search_is_view'] ?? ''
);

$validator = new Validator($requestInfo);
$validator->rule('integer', 'page');
$validator->rule('in', 'search', array('title','content'));
if($validator->validate()) {
    $requestInfo = $validator->data();
}else{
    echo $validator->errorsJsAlert("faq-list.php?".http_build_query($requestInfo));
    exit();
}

// form params
$formInfo = array(
    'del_hide_select' => $_POST['del_hide_select'] ?? '',
);

// validation
$validator = new Validator($formInfo);
$validator->rule('array', $_POST['select_seq']);
$validator->rule('required', 'del_hide_select');
if($validator->validate()) {       					// validation 성공
    $formInfo = $validator->data(); 		// 데이터 받아오기
    $select_seq = $_POST['select_seq'];
} else {               									// validation 실패
    echo $validator->errorsJsAlert("faq-list.php?".http_build_query($requestInfo));
    exit();
}

// database 처리
$db = new ModelBase();
$db->beginTransaction();
$db->from('BOARD_FAQ');
$db->whereIn('seq', $select_seq);
if($formInfo['del_hide_select'] == 'delete') {
    $updateInfo = array(
        "is_del" => "Y",
        "del_date" => date("Y-m-d H:i:s"),
        "del_id" => $ADMIN_ID_,
    );
    $modeText = '삭제';
}else if($formInfo['del_hide_select'] == 'hide') {
    $updateInfo = array(
        "is_view" => "N",
        "mod_id" => $ADMIN_ID_,
    );
    $modeText = '숨김처리';
}

if ($db->update($updateInfo)){
    //commit
    $db->executeTransaction();
    $db->close();
    CommonFunc::history_proc($ADMIN_ID_, $USER_IP_, '게시판', '고객센터FAQ 삭제', "{$ADMIN_ID_}님이 고객센터FAQ정보들을 ".$modeText." 하였습니다.");
    CommonFunc::jsAlert('고객센터FAQ가 '.$modeText.' 되었습니다.','location.href="faq-list.php?'.http_build_query($requestInfo).'";');
}else{
    //rollBack
    $db->rollBack();
    $db->close();

    CommonFunc::jsAlert("고객센터FAQ '.$modeText.'에 실패하였습니다.\\n확인 후 다시 시도해 주세요.",'location.href="faq-list.php?'.http_build_query($requestInfo).'";');
}
?>

