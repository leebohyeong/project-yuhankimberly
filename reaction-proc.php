<?php
/** ==============================================================================
 * File: 관리자 > 로그인 처리 (reaction-proc.php)
 * Date: 2023-05-22 오후 5:00
 * Created by @krabbit2.DevGroup
 * Copyright 2023, xn-939awia823kba64a723b9ulkh4aca.com(Group IDD). All rights are reserved
 * ==============================================================================*/
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use \Groupidd\Model\ModelBase;
use \Groupidd\Common\CommonFunc;
use \Groupidd\Library\Validator;

// request check
$requestInfo    = array(
    'page'              => $_GET['page'] ?? 1 ,
);

$validator = new Validator($requestInfo);
$validator->rule('integer', 'page');
if($validator->validate()) {
    $requestInfo = $validator->data();
}else{
    echo $validator->errorsJsAlert("privacy-register.php");
    exit();
}

// form params
$formInfo           = array(
    'category'         => $_POST['category'] ?? '',          // TOP 여부
    'username'    => $_POST['username'] ?? '',        // 노출 여부
    'userphone'           => $_POST['userphone'] ?? '',      // 구분
    'content'            => $_POST['content'] ?? ''                // 등록 관리자 아이디
);

// validation
$validator = new Validator($formInfo);
$validator->rule('required', 'category');
$validator->rule('required', 'username');
$validator->rule('required', 'userphone');
$validator->rule('required', 'content');
if( $validator->validate()) {       					// validation 성공
    $formInfo 				= $validator->data(); 		// 데이터 받아오기
    $formInfo['is_adult'] = 'Y';
    $formInfo['is_agree'] = 'Y';
} else {               									// validation 실패
    echo $validator->errorsJsAlert("reaction.php");
    exit;
}

// database 처리
$db = new ModelBase();
$db->init();
$db->beginTransaction();
$db->from('REACTION');
if ($db->insert($formInfo)){
    //commit
    $db->executeTransaction();
    $db->close();
    CommonFunc::jsAlert('등록 되었습니다.','location.href="reaction.php";');
}else{
    //rollBack
    $db->rollBack();
    $db->close();

    CommonFunc::jsAlert("등록에 실패하였습니다.\\n확인 후 다시 시도해 주세요.",'location.href="reaction.php";');
}
?>

