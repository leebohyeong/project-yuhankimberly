<?php
/* ==============================================================================
 * File: 관리자 > 로그인 (login.php)
 * Date: 2023-02-28 오후 12:02
 * Created by @csw9569.DevGroup
 * Copyright 2023, baskinrobbins.co.kr/(Group IDD). All rights are reserved
' ================================================================================= */
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use \Groupidd\Model\ModelBase;
use \Groupidd\Common\CommonFunc;

if (empty($USER_IP_)) exit();      // 아이피 받아오지 않으면 로그인 페이지 아예 안뜸..

// 관리자 목록에서 허용된 아이피가 아니라면 관리자 페이지가 노출 되지 않게 (이럴경우 별도 안내 메세지 띄울 필요 없음)
$db = new ModelBase();
$db->query('SELECT admin_ip1 AS IP, admin_ip1_treason AS IP_TREASON FROM ADMIN_MEMBER UNION ALL SELECT admin_ip2 AS IP, admin_ip2_treason AS IP_TREASON FROM ADMIN_MEMBER');
$ipCheck = $db->getAll();
$commonFnc = new CommonFunc();

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <?php require_once $ROOT_PATH_ . '/sitemanager/assets/include/head.php'; ?>
</head>
<body class="sitemanager sitemanager--login">
<section class="login">
    <div>
        <h2>사이트 관리자</h2>
        <form method="post" action="login-proc.php">
            <p>
                <label>
                    <span>아이디</span>
                    <input type="text" name="admin_id" placeholder="아이디" required>
                </label>
            </p>
            <p>
                <label>
                    <span>비밀번호</span>
                    <input type="password" name="admin_pw" placeholder="비밀번호" autocomplete="off" required>
                </label>
            </p>
            <p>
                <button class="button button--blue" type="submit">로그인</button>
            </p>
        </form>
    </div>
</section>

<!-- 관리자 로그인 비밀번호 변경 -->
<div id="login-change-password" class="login-change-password modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <h2>
                관리자 로그인 <br>
                <strong>비밀번호 변경</strong>
            </h2>

            <form method="post" action="password-change-proc.php">
                <input type="hidden" name="admin_id">
                <p>
                    <label>
                        <span>현재 비밀번호</span>
                        <input type="password" name="admin_pw" autocomplete="off" placeholder="현재 비밀번호" required>
                    </label>
                </p>
                <p>
                    <label>
                        <span>변경 비밀번호</span>
                        <input type="password" name="admin_new_pw" autocomplete="off" placeholder="변경 비밀번호" required>
                    </label>
                </p>
                <p>
                    <label>
                        <span>확인 비밀번호</span>
                        <input type="password" name="change_login_pw_sub" autocomplete="off" placeholder="확인 비밀번호" required>
                    </label>
                </p>
                <div></div>
                <p>
                    <button type="submit" class="button button--blue">등록</button>
                </p>
            </form>
        </div>
    </div>
</div>
<script>
    // 비밀번호 변경
    const loginChangePassword = new bootstrap.Modal('#login-change-password');
    (function () {
        const form = document.querySelector('.login form');
        const id = form.querySelector('[name="admin_id"]');
        const password = form.querySelector('[name="admin_pw"]');
        const submit = form.querySelector('[type="submit"]');

        const isValid = function () {
            if (!id.value.trim()) {
                alert('아이디를 입력해주세요');
                id.focus();
                return false;
            }
            if (!password.value.trim()) {
                alert('비밀번호를 입력해주세요');
                password.focus();
                return false;
            }

            return true;
        };
        const onLogin = function (event) {
            event.preventDefault();
            if (isValid()) {
                const formData = new FormData(form);
                $.ajax({
                    url:form.action, //request 보낼 서버의 경로
                    type:form.method, // 메소드(get, post, put 등)
                    contentType: false,
                    processData: false,
                    data: formData, //보낼 데이터
                    success(data) {
                        const result = JSON.parse(data);
                        $("[name=csrf_token]").val(result.csrf_token);        // token값 갱신
                        if(result['result']){
                            location.href = result['url'];
                        }else{
                            alert (result['message']);
                            if(result['password_setting']){
                                $('#login-change-password input[name=admin_id]').val(id.value);
                                loginChangePassword.show();
                            }
                        }
                    },
                    error(err) {
                        console.log (err);
                    }
                });
            }
        };
        submit.addEventListener('click', onLogin);
    })();
</script>
</body>
</html>


