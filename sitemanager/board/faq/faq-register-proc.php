<?php
/** ==============================================================================
 * File: 관리자 > 게시판 > 고객센터FAQ 등록 처리 (faq-register-proc.php)
 * Date: 2023-03-03 오후 01:48
 * Created by @csw9569.DevGroup
 * Copyright 2023, baskinrobbins.co.kr/(Group IDD). All rights are reserved
 * ==============================================================================*/
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use \Groupidd\Model\ModelBase;
use \Groupidd\Library\Validator;
use \Groupidd\Common\CommonFunc;

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
if($validator->validate()) {                // validation 성공
    $requestInfo = $validator->data();
    $page     	 = $requestInfo['page'];    // 페이지 번호
}else{
    echo $validator->errorsJsAlert("faq-register.php?".http_build_query($requestInfo));
    exit();
}

// form params
$formInfo           = array(
    'is_top'        => $_POST['is_top'] ?? '',      // TOP 여부
    'is_view'       => $_POST['is_view'] ?? '',     // 노출 여부
    'category'      => $_POST['category'] ?? '',    // 구분
    'content'       => $_POST['content'] ?? '',     // 질문
    'answer'        => $_POST['answer'] ?? '',      // 답변
    'reg_id'        => $ADMIN_ID_,                  // 등록 관리자 아이디
);

// validation
$validator = new Validator($formInfo);
$validator->rule('required', 'content');
$validator->rule('required', 'answer');
if( $validator->validate()) {
    $formInfo = $validator->data();
} else {
    echo $validator->errorsJsAlert("faq-register.php?".http_build_query($requestInfo));
    exit;
}

// database 처리
$db = new ModelBase();
$db->beginTransaction();
$db->from('BOARD_FAQ');
if ($db->insert($formInfo)){
    //commit
    $db->executeTransaction();
    $db->close();
    CommonFunc::history_proc($ADMIN_ID_, $USER_IP_, '게시판', '고객센터FAQ 등록', "{$ADMIN_ID_}님이 고객센터FAQ를 등록 하셨습니다.");
    CommonFunc::jsAlert('등록 되었습니다.','location.href="faq-list.php?'.http_build_query($requestInfo).'";');
}else{
    //rollBack
    $db->rollBack();
    $db->close();

    CommonFunc::jsAlert("등록에 실패하였습니다.\\n확인 후 다시 시도해 주세요.",'location.href="faq-list.php?'.http_build_query($requestInfo).'";');
}
?>