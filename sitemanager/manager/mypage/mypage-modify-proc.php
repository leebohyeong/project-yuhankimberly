<?php
/** ==============================================================================
 * File: 관리자 > 마이페이지 > 계정 수정 처리 (mypage-modify-proc.php)
 * Date: 2023-02-28 오후 12:02
 * Created by @csw9569.DevGroup
 * Copyright 2023, baskinrobbins.co.kr/(Group IDD). All rights are reserved
 * ==============================================================================*/
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use \Groupidd\Model\ModelBase;
use \Groupidd\Library\Validator;
use \Groupidd\Common\CommonFunc;

$admin_tel = (isset($_POST['tel1']) ? $_POST['tel1'] : '').'-'.(isset($_POST['tel2']) ? $_POST['tel2'] : '').'-'.(isset($_POST['tel2']) ? $_POST['tel3'] : '');
$email = (isset($_POST['email1']) ? $_POST['email1'] : '').'@'.(isset($_POST['email2']) ? $_POST['email2'] : '');
// search param
$formInfo           = array(
    'seq'                   => $_POST['seq'] ?? '',
    'admin_tel'             => $admin_tel,
    'extension_number'      => $_POST['extension_number'] ?? '',
    'email'                 => $email,
    'admin_pw'              => $_POST['admin_pw'] ?? '',
    'admin_new_pw'          => $_POST['admin_new_pw'] ?? '',
    'change_login_pw_sub'   => $_POST['change_login_pw_sub'] ?? '',
);

$validator = new Validator($formInfo);
$validator->rule('required', 'seq')->message('선택한 관리자 아이디가 없습니다.');
$validator->rule('integer', 'seq')->message('선택한 관리자 고유번호가 잘못되었습니다.');
$validator->rule('required', 'admin_tel')->message('연락처는 필수 입력값입니다.');
$validator->rule('phone', 'admin_tel')->message('휴대폰 번호 형식이 잘못되었습니다.');
$validator->rule('required', 'email')->message('이메일은 필수 입력값입니다.');
$validator->rule('email', 'email')->message('이메일 주소 형식이 잘못되었습니다.');
$validator->rule('required', 'admin_pw')->message('패스워드는 필수 입력값입니다.');
$validator->rule('equals', 'admin_new_pw', 'change_login_pw_sub')->message('신규 비밀번호 확인이 일치하지 않습니다.');
$validator->rule('lengthMin', 'admin_new_pw', 7)->message('패스워드는 8자리 이상이어야 합니다.');
$validator->rule('regex', 'admin_new_pw', "/^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@$%^*]).*$/")->message('영문, 숫자, 특수문자가 반드시 모두 포함되어야 합니다.');
$validator->rule('different', 'admin_pw', 'admin_new_pw')->message('이전 패스워드와 변경할 패스워드가 일치합니다.');

if($validator->validate()) {               // validation 성공
    $formInfo = $validator->data();
    $adminPW    = mb_strtolower($formInfo['admin_pw']);
    $adminNewPW = mb_strtolower($formInfo['admin_new_pw']);
}else{
    echo $validator->errorsJsAlert("mypage.php");
    exit();
}

// 비밀번호 암호화(sha512)
$adminPW_encode     = hash('sha512', $adminPW);
$adminNewPW_encode = "";
if($adminNewPW != ""){
    $adminNewPW_encode  = hash('sha512', $adminNewPW);
}

// database 처리
$db = new ModelBase();
$db->from('ADMIN_MEMBER');
$db->where('seq', $formInfo['seq']);
$db->where('is_del', 'N');
$db->limit(1);
$result = $db->getOne();

if (empty($result)){
    CommonFunc::jsAlert("계정정보가 올바르지 않습니다.","location.href='mypage.php';");
    $db->close();
    exit();
}

$admin_id = $result['admin_id'];

if($adminPW_encode != $result['admin_pw']) {
    CommonFunc::jsAlert("계정정보가 올바르지 않습니다.","location.href='mypage.php';");
    $db->close();
    exit();
}

// UPDATE 하지 않는 값, 삭제

unset($formInfo['admin_new_pw']);
unset($formInfo['change_login_pw_sub']);

if($adminNewPW_encode != "" && $adminPW_encode != $adminNewPW_encode){
    $formInfo['admin_pw'] = $adminNewPW_encode;
    $formInfo['default_pw_YN'] = 'N';
    $formInfo['change_pw_date'] = date('Y-m-d H:i:s');
}else{
    $formInfo['admin_pw'] = $adminPW_encode;
}

$db->init();
$db->beginTransaction();
$db->from('ADMIN_MEMBER');
$db->where('seq', $formInfo['seq']);
unset($formInfo['seq']);
if ($db->update($formInfo) ) {

    //commit
    $db->executeTransaction();
    $db->close();
    CommonFunc::history_proc($ADMIN_ID_, $USER_IP_, '관리자', '마이페이지 수정', "{$ADMIN_ID_}님이 마이페이지에서 정보를 수정하였습니다.");
    CommonFunc::jsAlert('수정 되었습니다.','location.href="mypage.php";');

}else{
    //rollBack
    $db->rollBack();
    $db->close();

    CommonFunc::jsAlert("수정에 실패하였습니다.\\n확인 후 다시 시도해 주세요.",'location.href="mypage.php";');
}