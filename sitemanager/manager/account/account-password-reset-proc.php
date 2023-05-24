<?php
/** ==============================================================================
 * File: 관리자 > 아이디 조회 처리 (account-idcheck-proc.php)
 * Date: 2023-02-28 오후 12:02
 * Created by @csw9569.DevGroup
 * Copyright 2023, baskinrobbins.co.kr/(Group IDD). All rights are reserved
 * ==============================================================================*/
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use \Groupidd\Model\ModelBase;
use \Groupidd\Library\Validator;
use \Groupidd\Common\CommonFunc;

$response   = array();
$response['csrf_token']  = $CSRF_->generate('baskin_token');            // csrf token 재생성해서 return

$formInfo  = array(
    'seq' => $_POST['seq'] ?? ''
);

$validator = new Validator($formInfo);
$validator->rule('required', 'seq');
$validator->rule('integer', 'seq');
if($validator->validate()) {       			// validation 성공
    $formInfo = $validator->data();
}else{
    $response['messages']     = '정보가 누락되었습니다.';
    $response['result']       = false;
    echo json_encode($response);
    exit();
}

$admin_pw  = mb_strtolower('brkorea31@');
$admin_pw  = hash('sha512', $admin_pw);

$db = new ModelBase();
$updateInfo = array('admin_pw' => $admin_pw, 'default_pw_YN' => 'Y');

$db->beginTransaction();
$db->from('ADMIN_MEMBER');
$db->where('seq', $formInfo['seq']);

if ($db->update($updateInfo)){

    //commit
    $db->executeTransaction();
    $db->close();

    CommonFunc::history_proc($ADMIN_ID_, $USER_IP_, '관리자', '관리자 계정/권한관리', "{$ADMIN_ID_}님이 비밀번호를 초기화({$formInfo['seq']}) 하셨습니다.");
    $response['messages'] = '비밀번호가 초기화 되었습니다.';
    $response['result'] = true;
    echo json_encode($response);
    exit();

}else{
    //rollBack
    $db->rollBack();
    $db->close();

    $response['messages'] = '데이터 처리에 실패 하였습니다.';
    $response['result'] = true;
    echo json_encode($response);
    exit();
}



?>