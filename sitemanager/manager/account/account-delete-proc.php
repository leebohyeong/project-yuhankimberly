<?php
/** ==============================================================================
 * File: 관리자 > 계정관리 > 계정 삭제 처리 (account-delete-proc.php)
 * Date: 2023-02-28 오후 12:02
 * Created by @csw9569.DevGroup
 * Copyright 2023, baskinrobbins.co.kr/(Group IDD). All rights are reserved
 * ==========================================================================
*/
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use \Groupidd\Model\ModelBase;
use \Groupidd\Library\Validator;
use \Groupidd\Common\CommonFunc;

$response   = array();

$formInfo  = array(
    'seq'       => $_POST['seq'] ?? '',
    'del_memo'  => $_POST['del_memo'] ?? ''
);

$validator = new Validator($formInfo);
$validator->rule('required', 'seq');
$validator->rule('integer', 'seq');
$validator->rule('required', 'del_memo');
if($validator->validate()) {       			// validation 성공
    $formInfo = $validator->data();
}else{
    $response['messages']     = '정보가 누락되었습니다.';
    $response['result']       = false;
    echo json_encode($response);
    exit();
}

$db = new ModelBase();
$db->beginTransaction();

// 삭제할 아이디 조회
$db->select('admin_id');
$db->from('ADMIN_MEMBER');
$db->where('seq', $formInfo['seq']);
$db->where('is_del','N');
$db->limit(1);
$result = $db->getOne();

$target_admin_id = "";
if(empty($result)){
    $response['messages']     = '존재하지 않는 정보 입니다.';
    $response['result']       = false;
    echo json_encode($response);
    exit();
}else{
    $target_admin_id = $result['admin_id'];
}

// 계정 삭제 처리
$delInfo  = array();
$delInfo['del_id'] = $ADMIN_ID_;
$delInfo['is_del'] = 'Y';
$delInfo['del_memo'] = $formInfo['del_memo'];
$db->init();
$db->from('ADMIN_MEMBER');
$db->where('seq', $formInfo['seq']);

if ($db->delete($delInfo, false)){
    //commit
    $db->executeTransaction();
    $db->close();
    CommonFunc::history_proc($ADMIN_ID_, $USER_IP_, '관리자', '관리자 계정/권한관리', "{$ADMIN_ID_}님이 관리자 {$target_admin_id}님의 계정를 삭제하였습니다.");

    if ($target_admin_id == $ADMIN_ID_){        // 본인의 계정을 삭제 했을 경우, 세션 삭제 처리
        session_unset();
        $response['messages']     = '로그아웃 처리 되었습니다.';
        $response['result']       = true;
        $response['url'] = '/sitemanager/login.php';
        echo json_encode($response);
        exit();
    }else{
        $response['messages']     = '삭제되었습니다.';
        $response['result']       = true;
        echo json_encode($response);
        exit();
    }

}else{
    //rollBack
    $db->rollBack();
    $db->close();
    $response['messages']     = '실패하였습니다. 확인 후 다시 시도해 주세요.';
    $response['result']       = false;
    echo json_encode($response);
    exit();
}