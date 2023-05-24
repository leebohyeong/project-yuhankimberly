<?php
/** ==============================================================================
 * File: 관리자 > 계정관리 > 계정 수정 처리 (account-modify-proc.php)
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
    'seq'           => $_POST['seq'] ?? '',
    'page'          => $_POST['page'] ?? 1 ,
    'search'        => $_POST['search'] ?? '',
    'findword'      => $_POST['findword'] ?? '',
    'search_status' => $_POST['search_status'] ?? ''
);

$validator = new Validator($requestInfo);
$validator->rule('required', 'seq');
$validator->rule('integer', 'seq');
$validator->rule('integer', 'page');
$validator->rule('in', 'search', array('admin_id','admin_name', 'email'));
$validator->rule('in', 'search_status', array('W','C', 'S'));
if($validator->validate()) {                // validation 성공
    $requestInfo = $validator->data();
}else{
    echo $validator->errorsJsAlert("account-modify.php?".http_build_query($requestInfo));
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

$formInfo           = array(
//    'seq'               => $_POST['seq'] ?? '',                 // 관리자 고유번호
    'admin_id'          => $_POST['admin_id'] ?? '',            // 관리자 아이디
    'status'            => $_POST['status'] ?? '',              // 계정상태
    'admin_name'        => $_POST['admin_name'] ?? '',          // 이름
    'admin_tel'         => $admin_tel,                          // 휴대폰
    'extension_number'  => $_POST['extension_number'] ?? '',    // 내선번호
    'belong'            => $_POST['belong'] ?? '',              // 소속
    'department'        => $_POST['department'] ?? '',          // 부서
    'email'             => $email,                              // 이메일
    'admin_ip1'         => $admin_ip1,                          // ip1
    'admin_ip1_treason' => $_POST['admin_ip1_treason'] ?? '',   // ip1 대역
    'admin_ip2'         => $admin_ip2,                          // ip2
    'admin_ip2_treason' => $_POST['admin_ip2_treason'] ?? '',   // ip2 대역
    'memo'              => $_POST['memo'] ?? '',                // 메모
    'mod_id'            => $ADMIN_ID_                           // 수정 아이디
);

$validator = new Validator($formInfo);
//$validator->rule('required', 'seq');
//$validator->rule('integer', 'seq');
$validator->rule('required', 'status');
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
    CommonFunc::jsAlert('관리자 권한을 선택해주세요.','location.href="account-modify.php?'.http_build_query($requestInfo).'";');
}

if($validator->validate()) {               // validation 성공
    $formInfo = $validator->data();
    // 관리자 권한
    $formInfo['admin_lv']   = implode(',', $_POST['admin_lv']);
}else{
    echo $validator->errorsJsAlert("account-modify.php?".http_build_query($requestInfo));
    exit();
}

// database 처리
$db = new ModelBase();
$db->beginTransaction();
$db->from('ADMIN_MEMBER');
$db->where('seq', $requestInfo['seq']);
$db->where('is_del', 'N');
$admin_id = $formInfo['admin_id'];
// UPDATE 하지 않는 값, 삭제
unset($formInfo['seq']);
unset($formInfo['admin_id']);

if ($db->update($formInfo)){
    //commit
    $db->executeTransaction();
    $db->close();
    CommonFunc::history_proc($ADMIN_ID_, $USER_IP_, '관리자', '관리자 계정/권한관리', "{$ADMIN_ID_}님이 {$admin_id}님의 정보를 수정하였습니다.");
    CommonFunc::jsAlert('관리자정보가 수정 되었습니다.','location.href="account-modify.php?'.http_build_query($requestInfo).'";');
}else{
    //rollBack
    $db->rollBack();
    $db->close();
    CommonFunc::jsAlert("관리자정보 수정에 실패하였습니다.\n확인 후 다시 시도해 주세요.",'location.href="account-modify.php?'.http_build_query($requestInfo).'";');
}
