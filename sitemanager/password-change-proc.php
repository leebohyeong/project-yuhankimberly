<?php
/** ==============================================================================
 * File: 관리자 > 패스워드 변경 처리 (password-change-proc.php)
 * Date: 2023-02-28 오후 12:02
 * Created by @csw9569.DevGroup
 * Copyright 2023, baskinrobbins.co.kr/(Group IDD). All rights are reserved
 * ==============================================================================*/
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use \Groupidd\Model\ModelBase;
use \Groupidd\Library\Validator;
use \Groupidd\Common\CommonFunc;

// response : json
$response = array();

$formInfo= array();
$formInfo['admin_id']       = isset($_POST['admin_id'])? $_POST['admin_id'] : '';           // 로그인 아이디
$formInfo['admin_pw']       = isset($_POST['admin_pw'])? $_POST['admin_pw'] : '';           // 기존 패스워드
$formInfo['admin_new_pw']   = isset($_POST['admin_new_pw'])? $_POST['admin_new_pw'] : '';   // 변경 패스워드

$validator = new Validator($formInfo);

$validator->rule('required', 'admin_id')->message('ID는 필수 입력값입니다.');
$validator->rule('required', 'admin_pw')->message('PW는 필수 입력값입니다.');
$validator->rule('required', 'admin_new_pw')->message('PW는 필수 입력값입니다.');
$validator->rule('lengthBetween', 'admin_id', 6, 15)->message('ID는 6~15자리입니다.');
$validator->rule('lengthMin', 'admin_new_pw', 7)->message('패스워드는 8자리 이상이어야 합니다.');
$validator->rule('regex', 'admin_new_pw', "/^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@$%^*]).*$/")->message('영문, 숫자, 특수문자가 반드시 모두 포함되어야 합니다.');
$validator->rule('different', 'admin_pw', 'admin_new_pw')->message('이전 패스워드와 변경할 패스워드가 일치합니다.');


if($validator->validate()) {            // validation 성공
    $formInfo   = $validator->data();     // 데이터 받아오기
    $adminID    = mb_strtolower($formInfo['admin_id']);
    $adminPW    = mb_strtolower($formInfo['admin_pw']);
    $adminNewPW = mb_strtolower($formInfo['admin_new_pw']);
} else {
    // validation 실패
    echo $validator->errorsJsAlert('/sitemanager/login.php');
    exit();
}

// 비밀번호 암호화(sha512)
$adminPW_encode     = hash('sha512', $adminPW);
$adminNewPW_encode  = hash('sha512', $adminNewPW);

// database 처리
$db = new ModelBase();

// 관리자 정보 가져오기
$db->select("admin_pw, admin_name, admin_lv, status, admin_ip1, admin_ip1_treason, admin_ip2, admin_ip2_treason, default_pw_YN, login_fail_cnt, login_last_date, change_pw_date");
$db->from("ADMIN_MEMBER");
$db->where("admin_id", $adminID);
$db->where("is_del", "N");
$db->limit(1);
$result = $db->getOne();

/**
' ------------------------------------------------------------------
' 로그인 실패 처리 CASE 6 + 로그인 처리와 동일하게 처리
' ------------------------------------------------------------------
 */
// 로그인 실패1 : 아이디 조회가 되지 않을 때
if (empty($result)){
    CommonFunc::jsAlert("계정정보가 올바르지 않습니다.",'location.href="/sitemanager/login.php"');
    $db->close();
    exit();
}

// 로그인 실패2 : 승인상태가 권한중지일때
if( $result['status'] == 'S'){
    // login log
    $db->init();
    $insertInfo = array('admin_id'=>$adminID , 'access_ip'=>$USER_IP_ , 'success_YN'=>'N', 'result_log'=>'사용잠금 계정 접근');
    $db->from("ADMIN_LOGIN_LOG");
    $db->insert($insertInfo);

    CommonFunc::jsAlert("계정정보가 올바르지 않습니다.\\n관리자에게 문의해주세요.",'location.href="/sitemanager/login.php"');
    $db->close();
    exit();
}

// 로그인 실패3 : 패스워드 정보가 일치하지 않을 때 -> 로그인 실패 횟수+1
if($adminPW_encode != $result['admin_pw']) {

    // 로그인 실패 횟수 +1
    $db-> init();
    $updateInfo = array('login_fail_cnt'=>'login_fail_cnt + 1');
    $db->from('ADMIN_MEMBER');
    $db->where('admin_id', $adminID);
    $db->update($updateInfo);

    // login log
    $db->init();
    $insertInfo = array('admin_id'=>$adminID , 'access_ip'=>$USER_IP_ , 'success_YN'=>'N', 'result_log'=>'패스워드 정보 불일치');
    $db->from("ADMIN_LOGIN_LOG");
    $db->insert($insertInfo);

    CommonFunc::jsAlert("계정정보가 올바르지 않습니다.",'location.href="/sitemanager/login.php"');
    $db->close();
    exit();
}

// 로그인 실패4 : 허용되지 않은 아이피로 접근 한 경우
if ($result['admin_ip1'] != $USER_IP_ && $result['admin_ip2'] != $USER_IP_){

    $ipCheckArray = array();
    if($result['admin_ip1_treason'] != ''){
        $ipTreason = CommonFunc::getIpTreason($result['admin_ip1'], $result['admin_ip1_treason']);
        $ipCheckArray = array_merge($ipCheckArray, $ipTreason);
    }
    if($result['admin_ip2_treason'] != ''){
        $ipTreason = CommonFunc::getIpTreason($result['admin_ip2'], $result['admin_ip2_treason']);
        $ipCheckArray = array_merge($ipCheckArray, $ipTreason);
    }

    if(!in_array($USER_IP_, $ipCheckArray)){
        $db->init();
        $insertInfo = array('admin_id'=>$adminID , 'access_ip'=>$USER_IP_ , 'success_YN'=>'N', 'result_log'=>'접근아이피 불일치');
        $db->from("ADMIN_LOGIN_LOG");
        $db->insert($insertInfo);

        CommonFunc::jsAlert("계정정보가 올바르지 않습니다.",'location.href="/sitemanager/login.php"');
        $db->close();
        exit();
    }
}

// 로그인 실패5 : 비밀번호 오류 횟수가 5회 이상일 때 + 계정잠금 상태로 처리
if( $result['login_fail_cnt'] >= 6){
    // login log
    $db->init();
    $insertInfo = array('admin_id'=>$adminID , 'access_ip'=>$USER_IP_ , 'success_YN'=>'N', 'result_log'=>'패스워드 오류횟수 초과');
    $db->from("ADMIN_LOGIN_LOG");
    $db->insert($insertInfo);

    // 로그인 5회 이상 실패시 계정 잠금 처리
    $db->init();
    $updateInfo = array('status'=>'S');
    $db->from('ADMIN_MEMBER');
    $db->where('admin_id',$adminID);
    $db->update($updateInfo);

    CommonFunc::jsAlert("패스워드입력 초과회수가 6회 이상이므로 계정 잠금 처리 되었습니다.\n계속 이용하고 싶으시면 관리자에게 문의하여 주시기 바랍니다.",'location.href="/sitemanager/login.php"');
    $db->close();
    exit();
}

// 로그인 실패6 : 마지막 로그인 날짜가 30일이 지났을 경우 + 계정잠금 처리
if (!empty($result['login_last_date'])){
    $last_login_date = new Datetime($result['login_last_date']);
    $now_date = new Datetime(date('Y-m-d H:i:s'));
    $diff = date_diff($last_login_date,$now_date);
    if( $diff->days >= 30){
        // login log
        $db->init();
        $insertInfo = array('admin_id'=>$adminID , 'access_ip'=>$USER_IP_ , 'success_YN'=>'N', 'result_log'=>'마지막 로그인 날짜 '. $diff->days .'일 초과');
        $db->from("ADMIN_LOGIN_LOG");
        $db->insert($insertInfo);

        // 30일 이상 접근 기록 없음 - 계정 잠금
        $db->init();
        $update = array('status'=>'S');
        $db->from('ADMIN_MEMBER');
        $db->where('admin_id',$adminID);
        $db->update($update);

        CommonFunc::jsAlert("30일 이상 접근 기록이 없어 잠금 계정 처리 되었습니다.\\n계속 이용하고 싶으시면 관리자에게 문의하여 주시기 바랍니다.",'location.href="/sitemanager/login.php"');
        $db->close();
        exit();
    }
}

/**
' ------------------------------------------------------------------
' 패스워드 변경 처리
' ------------------------------------------------------------------
 */
// 초기패스워드 변경상태 및 비밀번호 변경
$db->init();
if($result['default_pw_YN'] == 'Y') $updateInfo = array('admin_pw'=>$adminNewPW_encode, 'default_pw_YN'=>'N','change_pw_date'=> date('y-m-d H:i:s'), 'status' => 'C' );
else $updateInfo = array('admin_pw'=>$adminNewPW_encode, 'default_pw_YN'=>'N','change_pw_date'=> date('y-m-d H:i:s') );
$db->from('ADMIN_MEMBER');
$db->where('admin_id', $adminID);
$db->update($updateInfo);

// 세션 처리
$_SESSION['ADMIN_ID'] = $adminID;
$_SESSION['ADMIN_NM'] = $result['admin_name'];
$_SESSION['ADMIN_LV'] = $result['admin_lv'];    // 관리자 권한

CommonFunc::jsAlert("비밀번호가 변경되었습니다.",'location.href="/sitemanager/manager/mypage/mypage.php"');
$db->close();
exit();
?>