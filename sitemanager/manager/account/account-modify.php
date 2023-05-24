<?php
/** ==============================================================================
 * File: 관리자 > 관리자 관리 > 계정 수정 (account-modify.php)
 * Date: 2023-02-28 오후 12:02
 * Created by @csw9569.DevGroup
 * Copyright 2023, baskinrobbins.co.kr/(Group IDD). All rights are reserved
 * ==============================================================================*/
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use \Groupidd\Model\ModelBase;
use \Groupidd\Common\CommonFunc;
use \Groupidd\Library\Validator;

// request check
$requestInfo    = array(
    'seq'           => $_GET['seq'] ?? '',
    'page'          => $_GET['page'] ?? 1,
    'search'        => $_GET['search'] ?? '',
    'findword'      => $_GET['findword'] ?? '',
    'search_status' => $_GET['search_status'] ?? ''
);

$validator = new Validator($requestInfo);
$validator->rule('required', 'seq');
$validator->rule('integer', 'seq');
$validator->rule('integer', 'page');
$validator->rule('in', 'search', array('admin_id','admin_name', 'email'));
$validator->rule('in', 'search_status', array('W','C', 'S'));
if($validator->validate()) {                // validation 성공
    $requestInfo = $validator->data();
    $page     	 = $requestInfo['page'];    // 페이지 번호
}else{
    echo $validator->errorsJsAlert();
    exit();
}

$db = new ModelBase();
$db->select('seq, admin_id, admin_name, admin_lv, admin_tel, extension_number, department, email, status, admin_ip1, admin_ip1_treason, admin_ip2, admin_ip2_treason, belong, memo, admin_lv, del_memo, login_last_date, reg_date');
$db->from('ADMIN_MEMBER');
$db->where('seq', $requestInfo['seq']);
$db->where('is_del','N');
$db->limit(1);
$result = $db->getOne();

if(empty($result)){
    CommonFunc::jsAlert("존재하지 않는 정보 입니다.","history.back();");
    exit();
}

$adminTelArray = explode('-', $result['admin_tel']);
$adminEmailArray = explode('@', $result['email']);
$adminLvArray = explode(',', $result['admin_lv']);
$adminIp1_1 = '';
$adminIp1_2 = '';
$adminIp1_3 = '';
$adminIp1_4 = '';
$adminIp2_1 = '';
$adminIp2_2 = '';
$adminIp2_3 = '';
$adminIp2_4 = '';
$ipPattern = '/(?:(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])\.){3}(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])/';
if(preg_match($ipPattern, $result['admin_ip1'])){
    $adminIp1Array = explode('.', $result['admin_ip1']);
    $adminIp1_1 = $adminIp1Array[0];
    $adminIp1_2 = $adminIp1Array[1];
    $adminIp1_3 = $adminIp1Array[2];
    $adminIp1_4 = $adminIp1Array[3];
}else{
    $adminIp1_1 = $result['admin_ip1'];
}
if(preg_match($ipPattern, $result['admin_ip2'])){
    $adminIp2Array = explode('.', $result['admin_ip2']);
    $adminIp2_1 = $adminIp2Array[0];
    $adminIp2_2 = $adminIp2Array[1];
    $adminIp2_3 = $adminIp2Array[2];
    $adminIp2_4 = $adminIp2Array[3];
}else{
    $adminIp2_1 = $result['admin_ip2'];
}

CommonFunc::history_proc($ADMIN_ID_, $USER_IP_, '관리자', '관리자 계정/권한관리 상세', "{$ADMIN_ID_} 님이 {$result['admin_id']}님의 정보를 조회하였습니다.");
unset($requestInfo['seq']);
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
        <h2>관리자 계정 수정</h2>
    </headder>
    <div class="contents">
        <form name="frm" method="POST" action="account-modify-proc.php">
            <input type="hidden" name="csrf_token" value="<?=$CSRF_TOKEN_?>">
            <input type="hidden" id="seq" name="seq" value="<?=$result['seq']?>">
            <input type="hidden" id="admin_id" name="admin_id" value="<?=$result['admin_id']?>">
            <input type="hidden" id="page" name="page" value="<?=$requestInfo['page']?>">
            <input type="hidden" id="find" name="search" value="<?=$requestInfo['search']?>">
            <input type="hidden" id="findword" name="findword" value="<?=$requestInfo['findword']?>">
            <input type="hidden" id="search_status" name="search_status" value="<?=$requestInfo['search_status']?>">
            <table class="table row">
                <colgroup>
                    <col style="width: 10%">
                    <col style="width: 40%">
                    <col style="width: 10%">
                    <col style="width: 40%">
                </colgroup>
                <tbody>
                <tr>
                    <th scope="row">
                        <label for="" class="form-label">* 계정상태</label>
                    </th>
                    <td>
                        <div class="radio-group">
                            <label>
                                <input type="radio" name="status" value="W" id="statusW" <?=CommonFunc::getChecked('W', $result['status'])?>>
                                <span><label for="statusW">승인대기</label></span>
                            </label>

                            <label>
                                <input type="radio" name="status" value="C" id="statusC" <?=CommonFunc::getChecked('C', $result['status'])?>>
                                <span><label for="statusC">승인완료</label></span>
                            </label>

                            <label>
                                <input type="radio" name="status" value="S" id="statusS" <?=CommonFunc::getChecked('S', $result['status'])?>>
                                <span><label for="statusS">권한중지</label></span>
                            </label>
                        </div>
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
                        <label for="" class="form-label">* 아이디</label>
                    </th>
                    <td>
                        <?=$result['admin_id']?>
                    </td>
                    <th scope="row">
                        <label for="" class="form-label">* 비밀번호 설정</label>
                    </th>
                    <td>
                        <button type="button" class="button button--black width-200" data-btn="eventBtn" data-fn="pwReset">비밀번호 초기화</button> <span>*클릭 시 ‘brkorea31@’ 로 초기화 됩니다.</span>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="" class="form-label">* 이름</label>
                    </th>
                    <td>
                        <input type="text" class="form-input width-200" name="admin_name" maxlength="10" value="<?=$result['admin_name']?>">
                    </td>
                    <th scope="row">
                        <label for="" class="form-label">* 소속/부서</label>
                    </th>
                    <td>
                        <div class="select-input-group">
                            <select name="belong" id="" class="form-input width-100">
                                <option value="" <?=CommonFunc::getSelected('', $result['belong'])?>>선택</option>
                                <option value="BR KOREA" <?=CommonFunc::getSelected('BR KOREA', $result['belong'])?>>BR KOREA</option>
                                <option value="외부" <?=CommonFunc::getSelected('외부', $result['belong'])?>>외부</option>
                            </select>
                            <input type="text" class="form-input width-200" name="department" maxlength="50" value="<?=$result['department']?>">
                        </div>
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
                        <label for="" class="form-label">
                            메모
                        </label>
                    </th>
                    <td colspan="3">
                        <input type="text" class="form-input width-500" name="memo" value="<?=$result['memo']?>"></input>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="" class="form-label">
                            * 접근권한
                        </label>
                    </th>
                    <td colspan="3">
                        <table id="levelTb">
                            <tr>
                                <th colspan="2">
                                    <input type="checkbox" name="admin_lv[]" value="ALL" id="ALL" <?=CommonFunc::getArrayChecked('ALL', $adminLvArray)?>> <label for="ALL">슈퍼관리자</label>
                                </th>
                            </tr>
                            <tr class="level_A">
                                <th>
                                    <input type="checkbox" id="lv_A" onclick="amdin_1depth_checkd('A')"> <label for="lv_A">메인관리</label>
                                </th>
                                <td>
                                    <input type="checkbox" name="admin_lv[]" value="AA" id="AA" <?=CommonFunc::getArrayChecked('AA', $adminLvArray)?>> <label for="AA">메인 콘텐츠</label>
                                    <input type="checkbox" name="admin_lv[]" value="AB" id="AB" <?=CommonFunc::getArrayChecked('AB', $adminLvArray)?>> <label for="AB">검색 아이콘</label>
                                    <input type="checkbox" name="admin_lv[]" value="AC" id="AC" <?=CommonFunc::getArrayChecked('AC', $adminLvArray)?>> <label for="AC">약관관리</label>
                                </td>
                            </tr>
                            <tr class="level_B">
                                <th>
                                    <input type="checkbox" id="lv_B" onclick="amdin_1depth_checkd('B')"> <label for="lv_B">제품관리</label>
                                </th>
                                <td>
                                    <input type="checkbox" name="admin_lv[]" value="BA" id="BA" <?=CommonFunc::getArrayChecked('BA', $adminLvArray)?>> <label for="BA">제품구성</label>
                                    <input type="checkbox" name="admin_lv[]" value="BB" id="BB" <?=CommonFunc::getArrayChecked('BB', $adminLvArray)?>> <label for="BB">이달의 맛</label>
                                    <input type="checkbox" name="admin_lv[]" value="BC" id="BC" <?=CommonFunc::getArrayChecked('BC', $adminLvArray)?>> <label for="BC">이달의 맛 히스토리</label>
                                    <input type="checkbox" name="admin_lv[]" value="BD" id="BD" <?=CommonFunc::getArrayChecked('BD', $adminLvArray)?>> <label for="BD">제품관리</label>
                                </td>
                            </tr>
                            <tr class="level_C">
                                <th>
                                    <input type="checkbox" id="lv_C" onclick="amdin_1depth_checkd('C')"> <label for="lv_C">프로모션 관리</label>
                                </th>
                                <td>
                                    <input type="checkbox" name="admin_lv[]" value="CA" id="CA" <?=CommonFunc::getArrayChecked('CA', $adminLvArray)?>> <label for="CA">이벤트</label>
                                    <input type="checkbox" name="admin_lv[]" value="CB" id="CB" <?=CommonFunc::getArrayChecked('CB', $adminLvArray)?>> <label for="CB">배라광장</label>
                                    <input type="checkbox" name="admin_lv[]" value="CC" id="CC" <?=CommonFunc::getArrayChecked('CC', $adminLvArray)?>> <label for="CC">BR레시피</label>
                                    <input type="checkbox" name="admin_lv[]" value="CD" id="CD" <?=CommonFunc::getArrayChecked('CD', $adminLvArray)?>> <label for="CD">마이플레이버리스트 항목관리</label>
                                    <input type="checkbox" name="admin_lv[]" value="CE" id="CE" <?=CommonFunc::getArrayChecked('CE', $adminLvArray)?>> <label for="CE">마이플레이버리스트</label>
                                    <input type="checkbox" name="admin_lv[]" value="CF" id="CF" <?=CommonFunc::getArrayChecked('CF', $adminLvArray)?>> <label for="CF">배라이즈백</label>
                                    <input type="checkbox" name="admin_lv[]" value="CG" id="CG" <?=CommonFunc::getArrayChecked('CG', $adminLvArray)?>> <label for="CG">프로모션 배너 관리</label>
                                </td>
                            </tr>
                            <tr class="level_D">
                                <th>
                                    <input type="checkbox" id="lv_D" onclick="amdin_1depth_checkd('D')"> <label for="lv_D">매장관리</label>
                                </th>
                                <td>
                                    <input type="checkbox" name="admin_lv[]" value="DA" id="DA" <?=CommonFunc::getArrayChecked('DA', $adminLvArray)?>> <label for="DA">메장관리</label>
                                    <input type="checkbox" name="admin_lv[]" value="DB" id="DB" <?=CommonFunc::getArrayChecked('DB', $adminLvArray)?>> <label for="DB">단체주문</label>
                                </td>
                            </tr>
                            <tr class="level_E">
                                <th>
                                    <input type="checkbox" id="lv_E" onclick="amdin_1depth_checkd('E')"> <label for="lv_E">게시판 관리</label>
                                </th>
                                <td>
                                    <input type="checkbox" name="admin_lv[]" value="EA" id="EA" <?=CommonFunc::getArrayChecked('EA', $adminLvArray)?>> <label for="EA">점포개설문의</label>
                                    <input type="checkbox" name="admin_lv[]" value="EB" id="EB" <?=CommonFunc::getArrayChecked('EB', $adminLvArray)?>> <label for="EB">직영점 입점 제의</label>
                                    <input type="checkbox" name="admin_lv[]" value="EC" id="EC" <?=CommonFunc::getArrayChecked('EC', $adminLvArray)?>> <label for="EC">고객센터 FAQ</label>
                                    <input type="checkbox" name="admin_lv[]" value="ED" id="ED" <?=CommonFunc::getArrayChecked('ED', $adminLvArray)?>> <label for="ED">창업 FAQ</label>
                                    <input type="checkbox" name="admin_lv[]" value="EE" id="EE" <?=CommonFunc::getArrayChecked('EE', $adminLvArray)?>> <label for="EE">칭찬점포</label>
                                    <input type="checkbox" name="admin_lv[]" value="EF" id="EF" <?=CommonFunc::getArrayChecked('EF', $adminLvArray)?>> <label for="EF">공지사항</label>
                                    <input type="checkbox" name="admin_lv[]" value="EG" id="EG" <?=CommonFunc::getArrayChecked('EG', $adminLvArray)?>> <label for="EG">보도자료</label>
                                </td>
                            </tr>
                            <tr class="level_F">
                                <th>
                                    <input type="checkbox" id="lv_F" onclick="amdin_1depth_checkd('F')"> <label for="lv_F">관리자 관리</label>
                                </th>
                                <td>
                                    <input type="checkbox" name="admin_lv[]" value="FA" id="FA" <?=CommonFunc::getArrayChecked('FA', $adminLvArray)?>><label for="FA">계정승인/권한관리</label>
                                    <input type="checkbox" name="admin_lv[]" value="FB" id="FB" <?=CommonFunc::getArrayChecked('FB', $adminLvArray)?>><label for="FB">관리자 IP등록</label>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="" class="form-label">접속 IP</label>
                    </th>
                    <td colspan="3">
                        <input type="text" class="form-input width-100" name="admin_ip1_1" maxlength="3" value="<?=$adminIp1_1?>">.
                        <input type="text" class="form-input width-100" name="admin_ip1_2" maxlength="3" value="<?=$adminIp1_2?>">.
                        <input type="text" class="form-input width-100" name="admin_ip1_3" maxlength="3" value="<?=$adminIp1_3?>">.
                        <input type="text" class="form-input width-100" name="admin_ip1_4" maxlength="3" value="<?=$adminIp1_4?>"> ~ <input type="text" class="form-input width-100" name="admin_ip1_treason" maxlength="3" value="<?=$result['admin_ip1_treason']?>"><br>

                        <input type="text" class="form-input width-100" name="admin_ip2_1" maxlength="3" value="<?=$adminIp2_1?>">.
                        <input type="text" class="form-input width-100" name="admin_ip2_2" maxlength="3" value="<?=$adminIp2_2?>">.
                        <input type="text" class="form-input width-100" name="admin_ip2_3" maxlength="3" value="<?=$adminIp2_3?>">.
                        <input type="text" class="form-input width-100" name="admin_ip2_4" maxlength="3" value="<?=$adminIp2_4?>"> ~ <input type="text" class="form-input width-100" name="admin_ip2_treason" maxlength="3" value="<?=$result['admin_ip2_treason']?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <span class="form-label">
                            계정관리
                        </span>
                    </th>
                    <td colspan="3">
                        사유 <input type="text" class="form-input width-300" name="del_memo" value="<?=$result['del_memo']?>"> <button type="button" class="button button--red whidth-200" data-btn="eventBtn" data-fn="delete">계정삭제</button>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="bottom-group">
                <button type="button" class="button" data-btn="eventBtn" data-fn="list">목록</button>
                <button type="submit" class="button button--blue" data-btn="eventBtn" data-fn="modify">수정</button>
            </div>
        </form>
    </div>
</section>


<script>
    const form = $("form[name='frm']").get(0);
    function amdin_1depth_checkd(depth){
        if($("#lv_"+depth).prop('checked')){
            $("#levelTb .level_"+depth+" input[value^='"+depth+"']").prop('checked', true);
        }else{
            $("#levelTb .level_"+depth+" input[value^='"+depth+"']").prop('checked', false);
        }
    }
    var EventListFn = {
        list: function (){
            location.href = "account-list.php?<?=http_build_query($requestInfo)?>";
        },
        modify: function () {
            if(confirm("수정 하시겠습니까?")){
                if(fn_formValidate()){
                    form.submit();
                }
            }
            return false;
        },
        delete: function (){
            if(confirm("계정을 삭제하시겠습니까?.")){
                if($("input[name='del_memo']").val() == ''){
                    alert ("계정삭제 사유를 입력하여 주세요.");
                    return;
                }else{
                    $.ajax({
                        url:'account-delete-proc.php', //request 보낼 서버의 경로
                        type:'POST', // 메소드(get, post, put 등)
                        dataType:"json",
                        data: {
                            "seq" : $("input[name='seq']").val(),
                            'del_memo' : $("input[name='del_memo']").val(),
                            "csrf_token": $("[name=csrf_token]").val()
                        }, //보낼 데이터
                        success(data) {
                            $("[name=csrf_token]").val(data.csrf_token);        // token값 갱신
                            if(data['result']){
                                alert (data['messages']);
                                if(data['url']){
                                    location.href = data['url'];
                                }else{
                                    location.href = 'account-list.php';
                                }
                            }else{
                                alert (data['messages']);
                            }
                        },
                        error(err) {
                            console.log (err);
                        }
                    });
                }
            }
        },
        pwReset: function (){
            if(confirm("비밀번호를 초기화 하시겠습니까?")){
                $.ajax({
                    url:'account-password-reset-proc.php', //request 보낼 서버의 경로
                    type:'POST', // 메소드(get, post, put 등)
                    dataType:"json",
                    data: {
                        "seq" : $("input[name='seq']").val(),
                        "csrf_token": $("[name=csrf_token]").val()
                    }, //보낼 데이터
                    success(data) {
                        $("[name=csrf_token]").val(data.csrf_token);        // token값 갱신
                        alert (data['messages']);
                    },
                    error(err) {
                        console.log (err);
                    }
                });
            }
        }
    };

    function fn_formValidate(){
        // return true;
        if($("[name=admin_id]").val().length < 6 || $("[name=admin_id]").val().length > 15){
            alert("아이디는 6~15자로 입력 해주세요.");
            $("[name=admin_id]").focus();
            return false;
        }

        if(!id_check){
            alert("아이디 중복체크를 해주세요.");
            $("[name=admin_id]").focus();
            return false;
        }

        if($("[name=admin_name]").val() == ""){
            alert("이름을 입력해주세요.");
            $("[name=admin_name]").focus();
            return false;
        }

        if($("select[name=belong]").val() == ""){
            alert("소속을 선택해주세요.");
            $("select[name=belong]").focus();
            return false;
        }

        if($("[name=department]").val() == ""){
            alert("부서를 입력해주세요.");
            $("select[name=department]").focus();
            return false;
        }

        if($("[name=tel1]").val()=="" || $("[name=tel2]").val()=="" || $("[name=tel3]").val()==""){
            alert("휴대폰 번호를 입력해주세요.");
            $("[name=tel1]").focus();
            return false;
        }

        if($("[name=email1]").val()=="" || $("[name=email2]").val()==""){
            alert("이메일을 입력해주세요.");
            $("[name=email]").focus();
            return false;
        }

        if(!$("[name='admin_lv[]']").is(':checked')){
            alert('권한을 선택해주세요');
            return false;
        }

        return true;
    }
</script>

<?php require_once $ROOT_PATH_ . '/sitemanager/assets/include/footer.php'; ?>
</body>
</html>

