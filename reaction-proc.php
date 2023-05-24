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


// form params
$formInfo           = array(
    'score' => $_POST['score'] ?? '',
    'category'         => $_POST['theme'] ?? '',          // theme
    'username'    => $_POST['name'] ?? '',        // name
    'userphone'           => $_POST['phone'] ?? '',      // phone
    'content'            => $_POST['review'] ?? ''                // review
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
    CommonFunc::jsAlert('필수 항목이 누락되었습니다.','location.href="index.php";');
    exit;
}
$today = Date('Y-m-d');

// database 처리
$db = new ModelBase();

$query = "SELECT * from REACTION where userphone = '".$formInfo['userphone']."' and date_format(reg_date,'%Y-%m-%d') = '".$today."'  ";
$db->query($query);
$result = $db->getAll();
$listCnt = count($result);

if($listCnt>0){
    CommonFunc::jsAlert('리액션 및 보너스 이벤트는 1일 1회 참여가 가능합니다.','location.href="index.php";');
}else{
    $db->init();
    $db->beginTransaction();
    $db->from('REACTION');
    if ($db->insert($formInfo)){
        //commit
        $db->executeTransaction();
        $db->close();
        CommonFunc::jsAlert($formInfo['username'].'님의 리액션 참여가 완료되었습니다.','location.href="index.php";');
    }else{
        //rollBack
        $db->rollBack();
        $db->close();
        CommonFunc::jsAlert("리액션 참여가 실패하였습니다.\\n확인 후 다시 시도해 주세요.",'location.href="index.php";');
    }
}
?>

