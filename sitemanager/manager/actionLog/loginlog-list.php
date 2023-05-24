<?php
/** ==============================================================================
 * File: 관리자 > 관리자 관리 > 로그인 로그 목록 (loginlog-list.php)
 * Date: 2018-07-27 오후 3:05
 * Created by @Mojito.DevGroup
 * Copyright 2018, travelweek.visitkorea.or.kr(Group IDD). All rights are reserved
 * ==============================================================================*/
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use \Groupidd\Model\ModelBase;
use \Groupidd\Common\CommonFunc;
use \Groupidd\Library\Validator;

if(strpos($ADMIN_LV_,"A") === false){
    CommonFunc::jsAlert('관리자관리 권한이 있어야 접근 가능합니다.',"location.href='account-list.php';");
    exit();
}

//인젝션 검사
$requestInfo = array(
    'page'      => isset($_GET['page']) && $_GET['page'] != '' ? $_GET['page'] : 1,
    'find'      => isset($_GET['find'])? $_GET['find'] : '',
    'findword'  => isset($_GET['findword'])? $_GET['findword'] : '',
    'from_date' => isset($_GET['from_date'])? $_GET['from_date'] : '',
    'to_date'   => isset($_GET['to_date'])? $_GET['to_date'] : ''
);

$validator = new Validator($requestInfo);
$validator->rule('integer', 'page');


if($validator->validate()) {       		// validation 성공
    $requestInfo = $validator->data();
    $page     = $requestInfo['page'];   // 페이지 번호
}else{
    echo $validator->errorsJsAlert();
    CommonFunc::jsAlert("","location.href='loginlog-list.php';");
    exit();
}

$db = new ModelBase();
$db->from("ADMIN_LOGIN_LOG");

// search
if(!empty($requestInfo['find']) && !empty($requestInfo['findword'])){
    $db->like('admin_id',$requestInfo['findword']);
}
if($requestInfo['from_date'] !='' && $requestInfo['to_date'] != ''){
    $db->between('SUBSTR(reg_date,1,10)',"'".$requestInfo['from_date']."'", "'".$requestInfo['to_date'] ."'");
}

// paging setting
$listCnt        = $db->getCountAll();
$perPage        = 15;
$pageSize       = ceil($listCnt/$perPage);
$currentPage    = ($page-1) * $perPage;

$db->select("admin_id, access_ip, reg_date, success_YN, result_log",true);
$db->orderby('seq','DESC');
$db->limit($perPage, $currentPage );
$result = $db->getAll();

//history log
$db->init();
$db->from('ADMIN_HISTORY_LOG');
$historyInfo = array();
$historyInfo['admin_id']    = $ADMIN_ID_;
$historyInfo['access_ip']   = $USER_IP_;
$historyInfo['depth_1']     = '로그인관리';
$historyInfo['depth_2']     = '리스트';
$historyInfo['work']        = "{$ADMIN_ID_}님이 로그인관리를 조회하셨습니다.";
$db->insert($historyInfo);
$db->close();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <?php require_once $ROOT_PATH_ . '/sitemanager/assets/include/head.php'; ?>
</head>
<body>
<?php require_once $ROOT_PATH_ . '/sitemanager/assets/include/left.php'; ?>

<div class="site__container">
    <form name="detail_form" action="">
        <input type="hidden" name="page" value="<?=$requestInfo['page']?>">

        <div class="site__content">
            <section class="section">
                <header class="section-header">
                    <h2 class="section-header__title">
                        관리자 관리
                    </h2>
                    <p class="section-header__info">
                        로그인 로그 관리
                    </p>
                </header>

                <div class="search">
                    <fieldset class="search__fieldset">
                        <legend class="search__legend">
                            검색
                        </legend>

                        <table class="search__table">
                            <colgroup>
                                <col width="">
                                <col width="">
                                <col width="">
                                <col width="">
                            </colgroup>
                            <tr>
                                <th>
                                    <label for="" class="form-label search__label">
                                        아이디
                                    </label>
                                </th>
                                <td>
                                    <select name="find" class="form-element">
                                        <option value="admin_id" <?=CommonFunc::getSelected($requestInfo['find'], 'admin_id')?> >아이디</option>
                                    </select>
                                    <input type="text" name="findword" value="<?=$requestInfo['findword']?>" class="form-element">
                                </td>
                                <th>
                                    <label for="" class="form-label search__label">
                                        조회기간
                                    </label>
                                </th>
                                <td>
                                    <div class="datepicker-range">
                                        <input type="text" name="from_date" value="<?=$requestInfo['from_date']?>" class="form-element datepicker-range__date" placeholder="YYYY-MM-DD">
                                        <span class="datepicker-range__line">~</span>
                                        <input type="text" name="to_date" value="<?=$requestInfo['to_date']?>" class="form-element datepicker-range__date" placeholder="YYYY-MM-DD">
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    <div class="search__submit">
                        <button type="submit" class="button button--search search__submit-button">
                            <span class="button__text">검색</span>
                        </button>
                    </div>
                </div>

                <div class="list">
                    <p class="list__total">
                        총 <strong class="list__total-number"><?=$listCnt?></strong>건이 검색 되었습니다.
                    </p>

                    <div class="list__data">
                        <table class="list__table">
                            <colgroup>
                                <col width="">
                                <col width="">
                                <col width="">
                                <col width="">
                                <col width="">
                            </colgroup>
                            <thead>
                            <tr>
                                <th scope="col">
                                    아이디
                                </th>
                                <th scope="col">
                                    아이피
                                </th>
                                <th scope="col">
                                    로그인 시간
                                </th>
                                <th scope="col">
                                    성공여부
                                </th>
                                <th scope="col">
                                    비고
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                            <?php
                            if($result){
                                foreach ($result as $row) {
                            ?>
                                <td><?=$row['admin_id']?></td>
                                <td><?=$row['access_ip']?></td>
                                <td><?=$row['reg_date']?></td>
                                <td><?=$row['success_YN']?></td>
                                <td><?=$row['result_log']?></td>
                            </tr>
                            <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>

                    <nav class="list__pagination">
                        <?=CommonFunc::getPaging($page, $perPage, $pageSize, $requestInfo); ?>
                    </nav>
                </div>
            </section>
        </div>
    </form>
</div>


<script>

    var form = $("form");

    (function () {
        var ListFn = {
            delete: function () {

                var cnt = $("input[name=del_list]:checked").length;

                if(cnt <= 0){
                    alert('삭제할 사용자를 선택하세요.');
                    return;
                }

                if(confirm('삭제하시겠습니까?')){
                    if($('#page_index').val() == "" || $('#page_index').val() == null)
                        $('#page_index').val("1");
                    form.attr('action',"<c:url value='/siteManager/manager/account/delete.do' />");
                    form.submit();
                }

                return false;
            },
            regist: function (data) {

                var id = data.adminid;

                if(id != '')
                    location.href = "<c:url value='/siteManager/manager/account/form.do?admin_id="+id+"' />";
                else
                    location.href = "<c:url value='/siteManager/manager/account/form.do?admin_id=' />";

                return false;
            },
            allCheck: function () {
                if($("[data-fn='allCheck']").prop("checked")) {
                    $("input[name=del_list]").prop("checked",true);
                } else {
                    $("input[name=del_list]").prop("checked",false);
                }
                return true;
            }
        };

        $('[data-btn="listBtn"]').on('click', function () {
            var data = $(this).data();

            ListFn[data.fn](data);
            return true;
        });

    })();
</script>

<?php require_once $ROOT_PATH_ . '/sitemanager/assets/include/footer.php'; ?>
</body>
</html>
