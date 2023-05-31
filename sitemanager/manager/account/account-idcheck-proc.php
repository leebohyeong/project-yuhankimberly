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

$response   = array();

$formInfo   = array(
    'admin_id' => $_POST['admin_id'] ?? ''        // 어드민 아이디
);

$validator = new Validator($formInfo);
$validator->rule('required', 'admin_id');
$validator->rule('lengthBetween','admin_id',6,15);
$validator->rule('alphaNum','admin_id');
$validator->rule('notIn','admin_id', array("admins", "administrator", "master", "manager"));

if($validator->validate()) {       			// validation 성공
    $formInfo = $validator->data();
}else{
    $response['messages']     = '아이디는 6~15자로 입력 해주세요.';
    $response['result']       = false;
    echo json_encode($response);
    exit();
}

$db = new ModelBase();
$db->select('seq');
$db->from('ADMIN_MEMBER');
$db->where('admin_id', mb_strtolower($formInfo['admin_id']));
$db->limit(1);
if( $db->getOne() ){
    $response['messages'] = '사용할 수 없는 아이디입니다.';
    $response['result'] = false;
    echo json_encode($response);
}else{
    $response['messages'] = '사용가능한 아이디 입니다.';
    $response['result'] = true;
    echo json_encode($response);
}
$db->close();
