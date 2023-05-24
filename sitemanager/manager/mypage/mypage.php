<?php
/** ==============================================================================
 * File: 관리자 > 마이페이지 (mypage.php)
 * Date: 2023-02-28 오후 12:02
 * Created by @csw9569.DevGroup
 * Copyright 2023, baskinrobbins.co.kr/(Group IDD). All rights are reserved
 * ==============================================================================*/
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use \Groupidd\Model\ModelBase;
use \Groupidd\Common\CommonFunc;

$db = new ModelBase();
$db->select('seq, admin_id, admin_name, admin_tel, extension_number, department, belong, email, login_last_date, reg_date');
$db->from('ADMIN_MEMBER');
$db->where('admin_id', $ADMIN_ID_);
$db->limit(1);
$result = $db->getOne();

if(empty($result)){
    CommonFunc::jsAlert("존재하지 않는 정보 입니다.","location.href='/sitemanager/login.php';");
    exit();
}

$adminTelArray = explode('-', $result['admin_tel']);
$adminEmailArray = explode('@', $result['email']);

//history log
$db->init();
$db->from('ADMIN_HISTORY_LOG');
$historyInfo                = array();
$historyInfo['admin_id']    = $ADMIN_ID_;
$historyInfo['access_ip']   = $USER_IP_;
$historyInfo['depth_1']     = '관리자';
$historyInfo['depth_2']     = '마이페이지';
$historyInfo['work']        = "{$ADMIN_ID_} 님이 마이페이지를 조회하였습니다.";
$db->insert($historyInfo);
$db->close();

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <?php require_once $ROOT_PATH_ . '/sitemanager/assets/include/head.php'; ?>
</head>
<body class="sitemanager">
<?php require_once $ROOT_PATH_ . '/sitemanager/assets/include/left.php'; ?>
<section class="sitemanager__body">
    <headder class="sitemanager__body-header">
        <ul>
            <li>관리자</li>
        </ul>
        <h2>마이페이지</h2>
    </headder>
    <div class="contents">
        <form name="frm" method="POST" action="mypage-modify-proc.php">
            <input type="hidden" name="csrf_token" value="<?=$CSRF_TOKEN_?>">
            <input type="hidden" id="seq" name="seq" value="<?=$result['seq']?>">
            <table class="table row">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label for="" class="form-label">아이디</label>
                        </th>
                        <td>
                            <?=$result['admin_id']?>
                        </td>
                        <th scope="row">
                            <label for="" class="form-label">
                                등록일<br>(마지막 접속)
                            </label>
                        </th>
                        <td>
                            <?=date('Y-m-d', strtotime($result['reg_date']))?> (<?=$result['login_last_date']?>)
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="" class="form-label">이름</label>
                        </th>
                        <td>
                            <?=$result['admin_name']?>
                        </td>
                        <th scope="row">
                            <label for="" class="form-label">소속/부서</label>
                        </th>
                        <td>
                            <?=$result['belong']?>/<?=$result['department']?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="" class="form-label">
                                * 휴대폰
                            </label>
                        </th>
                        <td class="tel">
                            <input type="number" name="tel1" class="form-input width-100" maxlength="3" value="<?=$adminTelArray[0]?>"> - <input type="number" name="tel2" class="form-input width-100" maxlength="4" value="<?=$adminTelArray[1]?>"> - <input type="number" name="tel3" class="form-input width-100" maxlength="4" value="<?=$adminTelArray[2]?>">
                        </td>
                        <th scope="row">
                            <label for="" class="form-label">
                                내선번호
                            </label>
                        </th>
                        <td class="tel">
                            <input type="text" name="extension_number" class="form-form width-200" maxlength="" value="<?=$result['extension_number']?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="" class="form-label">* 이메일</label>
                        </th>
                        <td colspan="3">
                            <input type="text" class="form-input width-200" name="email1" maxlength="50" value="<?=$adminEmailArray[0]?>"> @ <input type="text" class="form-input width-200" name="email2" maxlength="50" value="<?=$adminEmailArray[1]?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="" class="form-label">* 현재 비밀번호</label>
                        </th>
                        <td colspan="3">
                            <input type="password" class="form-input width-200" name="admin_pw" autocomplete="off" placeholder="현재 비밀번호" required>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="" class="form-label">신규 비밀번호</label>
                        </th>
                        <td colspan="3">
                            <input type="password" class="form-input width-200" name="admin_new_pw" autocomplete="off" placeholder="신규 비밀번호">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="" class="form-label">비밀번호 확인</label>
                        </th>
                        <td colspan="3">
                            <input type="password" class="form-input width-200" name="change_login_pw_sub" autocomplete="off" placeholder="비밀번호 확인">
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="bottom-group">
                <button type="button" class="button button--blue" data-btn="eventBtn" data-fn="modify">수정</button>
            </div>
        </form>
    </div>
</section>

<script>
    const form = $("form[name='frm']").get(0);
    var EventListFn = {
        modify: function () {
            if(confirm("수정 하시겠습니까?")){
                if(fn_formValidate()){
                    form.submit();
                }
            }
            return false;
        },
    };

    function fn_formValidate(){

        if(!$.trim($("[name='tel1']").val()) ||!$.trim($("[name='tel2']").val()) || !$.trim($("[name='tel3']").val())){
            alert("휴대폰 번호를 입력해주세요.");
            $("[name=tel1]").focus();
            return false;
        }

        // if(!$.trim($("input[name='extension_number']").val())){
        //     alert("내선번호를 입력해주세요.");
        //     $("input[name=extension_number]").focus();
        //     return false;
        // }

        if(!$.trim($("[name='email1']").val()) || !$.trim($("[name=email2]").val())){
            alert("이메일을 입력해주세요.");
            $("[name=email]").focus();
            return false;
        }

        if(!$.trim($("input[name='admin_pw']").val())){
            alert("현재 비밀번호를 입력해주세요.");
            $("input[name=admin_pw]").focus();
            return false;
        }

        if($("input[name='admin_new_pw']").val() !== $("input[name='change_login_pw_sub']").val()){
            alert("신규 비밀번호 확인이 일치하지 않습니다.");
            $("input[name=change_login_pw_sub]").focus();
            return false;
        }

        return true;
    }
</script>

<?php require_once $ROOT_PATH_ . '/sitemanager/assets/include/footer.php'; ?>
</body>
</html>

