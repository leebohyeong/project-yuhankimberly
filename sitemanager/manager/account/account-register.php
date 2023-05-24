<?php
/** ==============================================================================
 * File: 관리자 > 관리자 등록 (account-register.php)
 * Date: 2023-02-28 오후 12:02
 * Created by @csw9569.DevGroup
 * Copyright 2023, baskinrobbins.co.kr/(Group IDD). All rights are reserved
 * ==============================================================================*/
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use \Groupidd\Library\Validator;

$requestInfo    = array(
    'page'              => $_GET['page'] ?? 1,
    'search'            => $_GET['search'] ?? '',
    'findword'          => $_GET['findword'] ?? '',
    'search_status'     => $_GET['search_status'] ?? ''
);

$validator = new Validator($requestInfo);
$validator->rule('integer', 'page');
$validator->rule('in', 'find', array('admin_id','admin_name', 'email'));
$validator->rule('in', 'search_status', array('W','C', 'S'));
if($validator->validate()) {                // validation 성공
    $requestInfo = $validator->data();
    $page     	 = $requestInfo['page'];    // 페이지 번호
}else{
    echo $validator->errorsJsAlert();
    exit();
}
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
        <h2>관리자 계정 등록</h2>
    </headder>
    <div class="contents">
        <form name="frm" method="POST" action="account-register-proc.php">
            <input type="hidden" name="csrf_token" value="<?=$CSRF_TOKEN_?>">
            <input type="hidden" name="page" value="<?=$requestInfo['page']?>">
            <input type="hidden" name="search" value="<?=$requestInfo['search']?>">
            <input type="hidden" name="findword" value="<?=$requestInfo['findword']?>">
            <input type="hidden" name="search_status" value="<?=$requestInfo['search_status']?>">
            <table class="table row">
                <colgroup>
                    <col style="width: 10%">
                    <col style="width: 40%">
                    <col style="width: 10%">
                    <col style="width: 40%">
                </colgroup>
                <tbody>
                    <tr>
                        <th>상태</th>
                        <td colspan="3">
                            <div class="radio-group">
                                <label>
                                    <input type="radio" name="status" value="W" id="statusW" checked>
                                    <span><label for="statusW">승인대기</label></span>
                                </label>

                                <label>
                                    <input type="radio" name="status" value="C" id="statusC">
                                    <span><label for="statusC">승인완료</label></span>
                                </label>

                                <label>
                                    <input type="radio" name="status" value="S" id="statusS">
                                    <span><label for="statusS">권한중지</label></span>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>* 아이디</th>
                        <td colspan="3">
                            <div style="display: flex; flex-flow: row wrap; align-items: center;}">
                                <input type="text" class="form-input width-300" name="admin_id">
                                <input type="hidden" name="admin_id_check" value="false">
                                <button type="button" class="button blue" style="margin-left: 10px;" data-btn="eventBtn" data-fn="idcheck">중복체크</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="" class="form-label">* 이름</label>
                        </th>
                        <td>
                            <input type="text" class="form-input width-200" name="admin_name" maxlength="10">
                        </td>
                        <th scope="row">
                            <label for="" class="form-label">* 소속/부서</label>
                        </th>
                        <td>
                            <div class="select-input-group">
                                <select name="belong" id="" class="form-input width-100">
                                    <option value="">선택</option>
                                    <option value="BR KOREA">BR KOREA</option>
                                    <option value="외부">외부</option>
                                </select>
                                <input type="text" class="form-input width-200" name="department" maxlength="50">
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
                            <input type="number" name="tel1" class="form-input width-100" maxlength="3"> - <input type="number" name="tel2" class="form-input width-100" maxlength="4"> - <input type="number" name="tel3" class="form-input width-100" maxlength="4">
                        </td>
                        <th scope="row">
                            <label for="" class="form-label">
                                내선번호
                            </label>
                        </th>
                        <td class="tel">
                            <input type="text" name="extension_number" class="form-form width-200" maxlength="">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="" class="form-label">* 이메일</label>
                        </th>
                        <td colspan="3">
                            <input type="text" class="form-input width-200" name="email1" maxlength="50"> @ <input type="text" class="form-input width-200" name="email2" maxlength="50">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="" class="form-label">
                                메모
                            </label>
                        </th>
                        <td colspan="3">
                            <input type="text" class="form-input width-500" name="memo"></input>
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
                                        <input type="checkbox" name="admin_lv[]" value="ALL" id="ALL"> <label for="ALL">슈퍼관리자</label>
                                    </th>
                                </tr>
                                <tr class="level_A">
                                    <th>
                                        <input type="checkbox" id="lv_A" onclick="amdin_1depth_checkd('A')"> <label for="lv_A">메인관리</label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="admin_lv[]" value="AA" id="AA"> <label for="AA">메인 콘텐츠</label>
                                        <input type="checkbox" name="admin_lv[]" value="AB" id="AB"> <label for="AB">검색 아이콘</label>
                                        <input type="checkbox" name="admin_lv[]" value="AC" id="AC"> <label for="AC">약관관리</label>
                                    </td>
                                </tr>
                                <tr class="level_B">
                                    <th>
                                        <input type="checkbox" id="lv_B" onclick="amdin_1depth_checkd('B')"> <label for="lv_B">제품관리</label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="admin_lv[]" value="BA" id="BA"> <label for="BA">제품구성</label>
                                        <input type="checkbox" name="admin_lv[]" value="BB" id="BB"> <label for="BB">이달의 맛</label>
                                        <input type="checkbox" name="admin_lv[]" value="BC" id="BC"> <label for="BC">이달의 맛 히스토리</label>
                                        <input type="checkbox" name="admin_lv[]" value="BD" id="BD"> <label for="BD">제품관리</label>
                                    </td>
                                </tr>
                                <tr class="level_C">
                                    <th>
                                        <input type="checkbox" id="lv_C" onclick="amdin_1depth_checkd('C')"> <label for="lv_C">프로모션 관리</label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="admin_lv[]" value="CA" id="CA"> <label for="CA">이벤트</label>
                                        <input type="checkbox" name="admin_lv[]" value="CB" id="CB"> <label for="CB">배라광장</label>
                                        <input type="checkbox" name="admin_lv[]" value="CC" id="CC"> <label for="CC">BR레시피</label>
                                        <input type="checkbox" name="admin_lv[]" value="CD" id="CD"> <label for="CD">마이플레이버리스트 항목관리</label>
                                        <input type="checkbox" name="admin_lv[]" value="CE" id="CE"> <label for="CE">마이플레이버리스트</label>
                                        <input type="checkbox" name="admin_lv[]" value="CF" id="CF"> <label for="CF">배라이즈백</label>
                                        <input type="checkbox" name="admin_lv[]" value="CG" id="CG"> <label for="CG">프로모션 배너 관리</label>
                                    </td>
                                </tr>
                                <tr class="level_D">
                                    <th>
                                        <input type="checkbox" id="lv_D" onclick="amdin_1depth_checkd('D')"> <label for="lv_D">매장관리</label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="admin_lv[]" value="DA" id="DA"> <label for="DA">메장관리</label>
                                        <input type="checkbox" name="admin_lv[]" value="DB" id="DB"> <label for="DB">단체주문</label>
                                    </td>
                                </tr>
                                <tr class="level_E">
                                    <th>
                                        <input type="checkbox" id="lv_E" onclick="amdin_1depth_checkd('E')"> <label for="lv_E">게시판 관리</label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="admin_lv[]" value="EA" id="EA"> <label for="EA">점포개설문의</label>
                                        <input type="checkbox" name="admin_lv[]" value="EB" id="EB"> <label for="EB">직영점 입점 제의</label>
                                        <input type="checkbox" name="admin_lv[]" value="EC" id="EC"> <label for="EC">고객센터 FAQ</label>
                                        <input type="checkbox" name="admin_lv[]" value="ED" id="ED"> <label for="ED">창업 FAQ</label>
                                        <input type="checkbox" name="admin_lv[]" value="EE" id="EE"> <label for="EE">칭찬점포</label>
                                        <input type="checkbox" name="admin_lv[]" value="EF" id="EF"> <label for="EF">공지사항</label>
                                        <input type="checkbox" name="admin_lv[]" value="EG" id="EG"> <label for="EG">보도자료</label>
                                    </td>
                                </tr>
                                <tr class="level_F">
                                    <th>
                                        <input type="checkbox" id="lv_F" onclick="amdin_1depth_checkd('F')"> <label for="lv_F">관리자 관리</label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="admin_lv[]" value="FA" id="FA"><label for="FA">계정승인/권한관리</label>
                                        <input type="checkbox" name="admin_lv[]" value="FB" id="FB"><label for="FB">관리자 IP등록</label>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="" class="form-label">* 접속 IP</label>
                        </th>
                        <td colspan="3">
                            <input type="text" class="form-input width-100" name="admin_ip1_1" maxlength="3">.<input type="text" class="form-input width-100" name="admin_ip1_2" maxlength="3">.<input type="text" class="form-input width-100" name="admin_ip1_3" maxlength="3">.<input type="text" class="form-input width-100" name="admin_ip1_4" maxlength="3"> ~ <input type="text" class="form-input width-100" name="admin_ip1_treason" maxlength="3"><br>
                            <input type="text" class="form-input width-100" name="admin_ip2_1" maxlength="3">.<input type="text" class="form-input width-100" name="admin_ip2_2" maxlength="3">.<input type="text" class="form-input width-100" name="admin_ip2_3" maxlength="3">.<input type="text" class="form-input width-100" name="admin_ip2_4" maxlength="3"> ~ <input type="text" class="form-input width-100" name="admin_ip2_treason" maxlength="3">
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="bottom-group">
                <button type="button" class="button" data-btn="eventBtn" data-fn="list">목록</button>
                <button type="button" class="button button--blue" data-btn="eventBtn" data-fn="register">등록</button>
            </div>
        </form>
    </div>
</section>
<script>
    var id_check = false;
    $('[name=admin_id]').keyup(function (){
        id_check = false;
    });

    function amdin_1depth_checkd(depth){
        if($("#lv_"+depth).prop('checked')){
            $("#levelTb .level_"+depth+" input[value^='"+depth+"']").prop('checked', true);
        }else{
            $("#levelTb .level_"+depth+" input[value^='"+depth+"']").prop('checked', false);
        }
    }

    var form = $("form");
    var EventListFn = {
        list: function (){
            location.href = "account-list.php?<?=http_build_query($requestInfo)?>";
        },
        idcheck: function () {
            if($("[name=admin_id]").val()==""){
                alert("아이디를 입력해주세요.");
                $("[name=admin_id]").focus();
                return false;
            }
            else{
                $.ajax({
                    url : "account-idcheck-proc.php",
                    dataType: "json",
                    type : "POST",
                    data : {
                        "admin_id": $("[name=admin_id]").val(),
                        "csrf_token": $("[name=csrf_token]").val()
                    },
                    success : function(data) {
                        $("[name=csrf_token]").val(data.csrf_token);        // token값 갱신
                        
                        if(data.result===true){
                            alert(data.messages);
                            id_check = true;
                        }
                        else{
                            alert(data.messages);
                            $('[name=admin_id]').val('');
                        }
                    }
                })
            }
            return false;
        },
        register: function () {
            if(confirm("등록 하시겠습니까?")){
                if(fn_formValidate()){
                    form.submit();
                }
            }
            return false;
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
        }else{
            var regTel = /^(?:(010\d{4})|(01[1|6|7|8|9]\d{3,4}))(\d{4})$/;
            if (!regTel.test($("[name=tel1]").val()+$("[name=tel2]").val()+$("[name=tel3]").val())) {
                $("[name=tel1]").focus();
                alert('휴대폰 번호 형식에 맞춰주세요.');
                return false;
            }
        }

        if($("[name=email1]").val() == "" || $("[name=email2]").val() == ""){
            alert("이메일을 입력해주세요.");
            $("[name=email]").focus();
            return false;
        }else{
            var regEmail = /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/;
            if (!regEmail.test($("[name=email1]").val()+'@'+$("[name=email2]").val())) {
                $("[name=email]").focus();
                alert('이메일 형식에 맞춰주세요.');
                return false;
            }
        }

        if(!$("[name='admin_lv[]']").is(':checked')){
            alert('권한을 선택해주세요');
            return false;
        }

        if(($("[name=admin_ip1_1]").val() == "" || $("[name=admin_ip1_2]").val() == "" || $("[name=admin_ip1_3]").val() == "" || $("[name=admin_ip1_4]").val() == "") &&
            ($("[name=admin_ip2_1]").val() == "" || $("[name=admin_ip2_2]").val() == "" || $("[name=admin_ip2_3]").val() == "" || $("[name=admin_ip2_4]").val() == "")){
            alert("접근 IP를 입력해주세요.");
            $("[name=admin_ip1_1]").focus();
            return false;
        }else{
            var regIp = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
            var ip1Check = $("[name=admin_ip1_1]").val()+$("[name=admin_ip1_2]").val()+$("[name=admin_ip1_3]").val()+$("[name=admin_ip1_4]").val();
            var ip2Check = $("[name=admin_ip2_1]").val()+$("[name=admin_ip2_2]").val()+$("[name=admin_ip2_3]").val()+$("[name=admin_ip2_4]").val();
            var ip1 = $("[name=admin_ip1_1]").val()+'.'+$("[name=admin_ip1_2]").val()+'.'+$("[name=admin_ip1_3]").val()+'.'+$("[name=admin_ip1_4]").val();
            var ip2 = $("[name=admin_ip2_1]").val()+'.'+$("[name=admin_ip2_2]").val()+'.'+$("[name=admin_ip2_3]").val()+'.'+$("[name=admin_ip2_4]").val();
            if (ip1Check != "" && !regIp.test(ip1)) {
                $("[name=admin_ip1_1]").focus();
                alert('IP 형식에 맞춰주세요.');
                return false;
            }
            if (ip2Check != "" && !regIp.test(ip2)) {
                $("[name=admin_ip2_1]").focus();
                alert('IP 형식에 맞춰주세요.');
                return false;
            }
        }

        return true;
    }

</script>

<?php require_once $ROOT_PATH_ . '/sitemanager/assets/include/footer.php'; ?>
</body>
</html>
