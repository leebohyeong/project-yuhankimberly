<?php
/** ==============================================================================
 * File: 관리자 > 계정관리 > 계정 등록 처리 (account-register-proc.php)
 * Date: 2023-02-28 오후 12:02
 * Created by @csw9569.DevGroup
 * Copyright 2023, baskinrobbins.co.kr/(Group IDD). All rights are reserved
 * ==============================================================================*/
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use \Groupidd\Model\ModelBase;
use \Groupidd\Library\Validator;
use \Groupidd\Common\CommonFunc;

$errorFlag = false;

// search param
$requestInfo    = array(
    'page'          => $_POST['page'] ?? 1 ,
    'search'        => $_POST['search'] ?? '',
    'findword'      => $_POST['findword'] ?? '',
    'search_status' => $_POST['search_status'] ?? ''
);

$validator = new Validator($requestInfo);
$validator->rule('integer', 'page');
$validator->rule('in', 'find', array('admin_id','admin_name', 'email'));
$validator->rule('in', 'status', array('W','C', 'S'));
if($validator->validate()) {                // validation 성공
    $requestInfo = $validator->data();
    $page     	 = $requestInfo['page'];    // 페이지 번호
}else{
    echo $validator->errorsJsAlert("account-register.php");
    exit();
}

$admin_ip1_1 = ($_POST['admin_ip1_1'] != '') ? $_POST['admin_ip1_1'].'.' : '';
$admin_ip1_2 = ($_POST['admin_ip1_2'] != '') ? $_POST['admin_ip1_2'].'.' : '';
$admin_ip1_3 = ($_POST['admin_ip1_3'] != '') ? $_POST['admin_ip1_3'].'.' : '';
$admin_ip1_4 = ($_POST['admin_ip1_4'] != '') ? $_POST['admin_ip1_4'] : '';
$admin_ip2_1 = ($_POST['admin_ip2_1'] != '') ? $_POST['admin_ip2_1'].'.' : '';
$admin_ip2_2 = ($_POST['admin_ip2_2'] != '') ? $_POST['admin_ip2_2'].'.' : '';
$admin_ip2_3 = ($_POST['admin_ip2_3'] != '') ? $_POST['admin_ip2_3'].'.' : '';
$admin_ip2_4 = ($_POST['admin_ip2_4'] != '') ? $_POST['admin_ip2_4'] : '';
$admin_ip1 = $admin_ip1_1.$admin_ip1_2.$admin_ip1_3.$admin_ip1_4;
$admin_ip2 = $admin_ip2_1.$admin_ip2_2.$admin_ip2_3.$admin_ip2_4;

$admin_tel = (isset($_POST['tel1']) ? $_POST['tel1'] : '').'-'.(isset($_POST['tel2']) ? $_POST['tel2'] : '').'-'.(isset($_POST['tel2']) ? $_POST['tel3'] : '');
$email = (isset($_POST['email1']) ? $_POST['email1'] : '').'@'.(isset($_POST['email2']) ? $_POST['email2'] : '');

// request params
$formInfo           = array(
    'status'            => $_POST['status'] ?? '',
    'admin_id'          => $_POST['admin_id'] ?? '',             // 아이디
    'admin_name'        => $_POST['admin_name'] ?? '',           // 이름
    'admin_tel'         => $admin_tel,                           // 휴대폰
    'extension_number'  => $_POST['extension_number'] ?? '',     // 내선번호
    'belong'            => $_POST['belong'] ?? '',               // 소속
    'department'        => $_POST['department'] ?? '',           // 부서
    'email'             => $email,                               // 이메일
    'admin_ip1'         => $admin_ip1,                           // ip1
    'admin_ip1_treason' => $_POST['admin_ip1_treason'] ?? '',    // ip1 대역
    'admin_ip2'         => $admin_ip2,                           // ip2
    'admin_ip2_treason' => $_POST['admin_ip2_treason'] ?? '',    // ip2 대역
    'memo'              => $_POST['memo'] ?? '',                 // 메모
    'reg_id'            => $ADMIN_ID_                            // 등록 관리자 아이디
);

// validation
$validator = new Validator($formInfo);
$validator->rule('required', 'status');
$validator->rule('required', 'admin_id');
$validator->rule('lengthBetween','admin_id',6,15);
$validator->rule('alphaNum','admin_id');
$validator->rule('notIn','admin_id', array("admins", "administrator", "master", "manager"));
$validator->rule('required', 'admin_name');
$validator->rule('required', 'belong');
$validator->rule('required', 'department');
$validator->rule('required', 'admin_tel');
$validator->rule('phone', 'admin_tel');
$validator->rule('required', 'email');
$validator->rule('email', 'email');
$validator->rule('ip', 'admin_ip1');
$validator->rule('ip', 'admin_ip2');

if(isset($_POST['admin_lv'])){
    foreach ($_POST['admin_lv'] as $value) {
        $validator->rule('array', $_POST['admin_lv']);             // array check
        $validator->rule('in', $value, array('AA', 'AB', 'AC', 'BA', 'BB', 'BC', 'BD', 'CA', 'CB', 'CC', 'CD', 'CE', 'DA', 'DB', 'EA', 'EB', 'EC', 'ED', 'EE', 'FA', 'FB'));
    }
}else{
    CommonFunc::jsAlert('관리자 권한을 선택해주세요.','location.href="account-register.php";');
    exit;
}

if( $validator->validate()) {       					// validation 성공
    $formInfo 				= $validator->data(); 		// 데이터 받아오기
    $formInfo['admin_id']  = mb_strtolower($formInfo['admin_id']);          // 아이디&비번은 소문자로..
    $formInfo['admin_pw']  = mb_strtolower('yuhan0601@');

    // 비밀번호 암호화
    $formInfo['admin_pw']  = hash('sha512', $formInfo['admin_pw']);
    // 관리자 권한
    $formInfo['admin_lv']   = implode(',', $_POST['admin_lv']);

} else {
    echo $validator->errorsJsAlert("account-register.php");
    exit;
}

// database 처리
$db = new ModelBase();
//id 중복 check
$db->select('seq, admin_id');
$db->from('ADMIN_MEMBER');
$db->where('admin_id', $formInfo['admin_id']);
$db->limit(1);
if( $db->getOne() ){
    CommonFunc::jsAlert('사용하실 수 없는 아이디입니다.','location.href=""account-register.php"";');
    $db->close();
    exit();
}
$db->init();
$db->beginTransaction();
$db->from('ADMIN_MEMBER');

if ($db->insert($formInfo)){
    //commit
    $db->executeTransaction();
    $db->close();
    CommonFunc::history_proc($ADMIN_ID_, $USER_IP_, '관리자', '관리자 계정/권한관리', "{$ADMIN_ID_}님이 {$formInfo['admin_id']} 아이디를 등록하셨습니다.");
    CommonFunc::jsAlert('관리자가 등록 되었습니다.','location.href="account-list.php?'.http_build_query($requestInfo).'";');
}else{
    //rollBack
    $db->rollBack();
    $db->close();

    CommonFunc::jsAlert("관리자 등록에 실패하였습니다.\\n확인 후 다시 시도해 주세요.",'location.href="account-list.php?'.http_build_query($requestInfo).'";');
}
