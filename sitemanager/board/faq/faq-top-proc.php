<?php
/** ==============================================================================
 * File: 관리자 > 로그인 처리 (faq-top-proc.php)
 * Date: 2023-04-04 오후 5:27
 * Created by @krabbit2.DevGroup
 * Copyright 2023, www.baskinrobbins.co.kr(Group IDD). All rights are reserved
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
$validator->rule('in', 'search', array('answer','content'));
if($validator->validate()) {
    $requestInfo = $validator->data();
}else{
    echo $validator->errorsJsAlert("faq-list.php?".http_build_query($requestInfo));
    exit();
}

// form params
$formInfo = array(
    'topon' => $_POST['topon'] ?? '',
);

// validation
$validator = new Validator($formInfo);
$validator->rule('array', $_POST['select_seq']);
$validator->rule('required', 'topon');
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
if($formInfo['topon'] == 'on') {
    $updateInfo = array(
        "is_top" => "Y"
    );
    $modeText = '설정';
}else if($formInfo['topon'] == 'off') {
    $updateInfo = array(
        "is_top" => "N"
    );
    $modeText = '해지';
}

if ($db->update($updateInfo, false)){
    //commit
    $db->executeTransaction();
    $db->close();
    CommonFunc::history_proc($ADMIN_ID_, $USER_IP_, '게시판', '고객센터FAQ TOP'.$modeText, "{$ADMIN_ID_}님이 고객센터FAQ정보들을 TOP설정을 수정하였습니다.");
    CommonFunc::jsAlert('고객센터FAQ TOP'.$modeText.' 되었습니다.','location.href="faq-list.php?'.http_build_query($requestInfo).'";');
}else{
    //rollBack
    $db->rollBack();
    $db->close();

    CommonFunc::jsAlert("고객센터FAQ TOP설정에 실패하였습니다.\\n확인 후 다시 시도해 주세요.",'location.href="faq-list.php?'.http_build_query($requestInfo).'";');
}
?>
