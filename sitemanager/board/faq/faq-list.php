<?php
/** ==============================================================================
 * File: 관리자 > 게시판 > 고객센터FAQ 리스트 (faq-list.php)
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
    'page'              => $_GET['page'] ?? 1,
    'search'            => $_GET['search'] ?? '',
    'findword'          => $_GET['findword'] ?? '',
    'search_category'   => $_GET['search_category'] ?? '',
    'from_date'         => $_GET['from_date'] ?? '',
    'to_date'           => $_GET['to_date'] ?? '',
    'search_is_view'    => $_GET['search_is_view'] ?? ''
);

$validator = new Validator($requestInfo);
$validator->rule('integer', 'page');
$validator->rule('in', 'search', array('answer','content'));
if($validator->validate()) {                // validation 성공
    $requestInfo = $validator->data();
    $page     	 = $requestInfo['page'];    // 페이지 번호
}else{
    echo $validator->errorsJsAlert();
    exit();
}

$db = new ModelBase();
$db->from("REACTION");
// search
if( !empty($requestInfo['search']) && !empty($requestInfo['findword'])){
    $db->like($requestInfo['search'], $requestInfo['findword']);
}elseif (empty($requestInfo['search']) && !empty($requestInfo['findword'])){
    $db->like('answer', $requestInfo['findword']);
    $db->or_like('content', $requestInfo['findword']);
}
if (!empty($requestInfo['search_category'])){
    $db->where('category', $requestInfo['search_category']);
}
if ($requestInfo['from_date'] != '' && $requestInfo['to_date'] != '') {
    $db->between('reg_date', "'".$requestInfo['from_date'].' 00:00:00'."'",  "'".$requestInfo['to_date'].' 23:59:59'."'");
}
if (!empty($requestInfo['search_is_view'])){
    $db->where('is_view', $requestInfo['search_is_view']);
}
$db->where('is_del', 'N');
// paging setting
$listCnt        = $db->getCountAll();
$perPage        = 10;
$pageSize       = ceil($listCnt/$perPage);
$currentPage    = ($page-1) * $perPage;

$db->select("seq, order_num, category, content, view_cnt_w, view_cnt_m, reg_date, is_view, is_top", true);
$db->orderby('is_top', 'desc');
$db->orderby('order_num');
$db->orderby('seq', 'desc');
$db->limit($perPage, $currentPage );
$result = $db->getAll();

$db->init();
$db->select('seq, content');
$db->where('is_del', 'N');
$db->orderby('order_num');
$db->orderby('seq', 'desc');
$orderNumResult = $db->getAll();

$db->init();
$db->select('category, name');
$db->from('COMMON_CODE');
$db->where('code_usage', 'FAQ');
$db->where('is_view', 'Y');
$db->orderby('seq','DESC');
$db->orderby('order_num');
$faqCategoryResult = $db->getAll();

CommonFunc::history_proc($ADMIN_ID_, $USER_IP_, '게시판', '고객센터FAQ', "{$ADMIN_ID_}님이 고객센터FAQ 리스트를 조회하셨습니다.");

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <?php require_once $ROOT_PATH_.'/sitemanager/assets/include/head.php'; ?>
</head>
<body class="sitemanager">
<?php require_once $ROOT_PATH_.'/sitemanager/assets/include/left.php'; ?>

<section class="sitemanager__body">
    <headder class="sitemanager__body-header">
        <ul>
            <li>게시판</li>
        </ul>
        <h2>고객센터 FAQ</h2>
    </headder>
    <form name="frm_search" class="search">
        <input type="hidden" name="page" value="<?=$page?>"/>
        <div>
            <div>
                <div></div>
                <div>
                    <div class="select-input-group">
                        <select name="search" class="form-input width-100">
                            <option value="">전체</option>
                            <option value="content" <?=CommonFunc::getSelected('content', $requestInfo['search'])?>>질문</option>
                            <option value="answer" <?=CommonFunc::getSelected('answer', $requestInfo['search'])?>>답변</option>
                        </select>
                        <input type="text" name="findword" value="<?=$requestInfo['findword']?>" class="form-input">
                    </div>
                </div>
            </div>
            <div>
                <div>구분</div>
                <div>
                    <div class="select-input-group">
                        <select name="search_category" id="" class="form-input width-100">
                            <option value="">전체</option>
                            <?php
                            foreach ($faqCategoryResult as $row) {
                                ?>
                                <option value="<?=$row['category']?>" <?=CommonFunc::getSelected($row['category'], $requestInfo['search_category'])?>><?=$row['name']?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div>
                <div>기간</div>
                <div>
                    <div class="vanillajs-daterangepicker">
                        <input type="text" name="from_date" value="<?=$requestInfo['from_date']?>" readonly>
                        <span>-</span>
                        <input type="text" name="to_date" value="<?=$requestInfo['to_date']?>" readonly>
                    </div>
                </div>
            </div>
            <div>
                <div>노출여부</div>
                <div class="label-wrap">
                    <div class="radio-group">
                        <label>
                            <span>전체</span>
                            <input type="radio" name="search_is_view" value="" checked>
                        </label>
                        <label>
                            <span>Y</span>
                            <input type="radio" name="search_is_view" value="Y" <?=CommonFunc::getChecked($requestInfo['search_is_view'], 'Y')?>>
                        </label>
                        <label>
                            <span>N</span>
                            <input type="radio" name="search_is_view" value="N" <?=CommonFunc::getChecked($requestInfo['search_is_view'], 'N')?>>
                        </label>

                    </div>
                </div>
            </div>
        </div>
        <div>
            <button class="button" type="submit">
                <i class="fa-solid fa-magnifying-glass"></i> 검색하기
            </button>
        </div>
    </form>

    <div class="contents">
        <div class="top-group">
            <p>총 <?=number_format($listCnt)?>건이 검색되었습니다.</p>
            <div>
                <button type="button" class="button" data-bs-toggle="modal" data-bs-target="#sortingModal">노출순서 변경</button>
                <button type="button" class="button" data-btn="eventBtn" data-fn=topon>TOP등록</button>
                <button type="button" class="button" data-btn="eventBtn" data-fn="topoff">TOP해지</button>
                <button type="button" class="button button--blue" data-btn="eventBtn" data-fn="register">등록</button>
            </div>
        </div>
        <form name="frm" action="/sitemanager/board/faq/faq-del-hide-proc.php" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo $CSRF_TOKEN_; ?>">
            <input type="hidden" name="page" value="<?=$requestInfo['page']?>">
            <input type="hidden" name="search" value="<?=$requestInfo['search']?>">
            <input type="hidden" name="findword" value="<?=$requestInfo['findword']?>">
            <input type="hidden" name="search_category" value="<?=$requestInfo['search_category']?>">
            <input type="hidden" name="from_date" value="<?=$requestInfo['from_date']?>">
            <input type="hidden" name="to_date" value="<?=$requestInfo['to_date']?>">
            <input type="hidden" name="search_is_view" value="<?=$requestInfo['search_is_view']?>">
            <input type="hidden" name="topon">
            <table class="table">
                <colgroup>
                    <col width="3%">
                    <col width="5%">
                    <col width="10%">
                    <col width="*%">
                    <col width="15%">
                    <col width="14%">
                    <col width="5%">
                </colgroup>
                <thead>
                    <tr>
                        <th><input type="checkbox" name="select"></th>
                        <th>노출순서</th>
                        <th>구분</th>
                        <th>제목</th>
                        <th>조회수 (PC/MO/Total)</th>
                        <th>등록일</th>
                        <th>노출여부</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if($listCnt > 0){
                    foreach ($result as $idx=>$row) {
                        switch ($row['category']){
                            case 'A': $category = '제품'; break;
                            case 'B': $category = '포인트'; break;
                            case 'C': $category = '회원'; break;
                            case 'D': $category = '기타'; break;
                        }
                        if($row['is_top']=='Y'){
                            $rownum = 'TOP';
                        }else{
                            $rownum = $row['order_num'];
                        }
                        ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="select_seq[]" value="<?=$row['seq']?>">
                            </td>
                            <td>
                                <?=$rownum?>
                            </td>
                            <td>
                                <?=$category?>
                            </td>
                            <td>
                                <a href="faq-modify.php?seq=<?=$row['seq'].'&'.http_build_query($requestInfo) ?>"><?=$row['content']?></a>
                            </td>
                            <td>
                                <?=$row['view_cnt_w']?>/<?=$row['view_cnt_m']?>/<?=$row['view_cnt_w']+$row['view_cnt_m']?>
                            </td>
                            <td>
                                <?=date('Y-m-d', strtotime($row['reg_date']))?>
                            </td>
                            <td>
                                <?=$row['is_view']?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
            </table>

            <div class="bottom-group">
                <div class="select-input-group">
                    <select name="del_hide_select" id="" class="form-input width-100">
                        <option value="delete">삭제</option>
                        <option value="hide">숨김</option>
                    </select>
                </div>
                <button type="button" class="button button--blue" data-btn="eventBtn" data-fn="del_hide">적용</button>
            </div>

            <nav class="pagination">
                <?=CommonFunc::getPaging($page, $perPage, $pageSize, $requestInfo)?>
            </nav>
        </form>
    </div>
</section>

<div id="sortingModal" class="modal fade" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="sorting-list">
                <form method="post" action="faq-order-change-proc.php">
                    <input type="hidden" name="csrf_token" value="<?php echo $CSRF_TOKEN_; ?>">
                    <input type="hidden" name="page" value="<?=$requestInfo['page']?>">
                    <input type="hidden" name="search" value="<?=$requestInfo['search']?>">
                    <input type="hidden" name="findword" value="<?=$requestInfo['findword']?>">
                    <input type="hidden" name="search_category" value="<?=$requestInfo['search_category']?>">
                    <input type="hidden" name="from_date" value="<?=$requestInfo['from_date']?>">
                    <input type="hidden" name="to_date" value="<?=$requestInfo['to_date']?>">
                    <input type="hidden" name="search_is_view" value="<?=$requestInfo['search_is_view']?>">
                    <h3>순서 변경</h3>
                    <p>* 드래그 앤 드롭 사용</p>
                    <ul>
                        <?php
                        foreach($orderNumResult as $key => $row2){
                        ?>
                        <li data-sorting-id="<?=$key?>">
                            <input type="hidden" name="seq[]" value="<?=$row2['seq']?>">
                            <?=$row2['content']?>
                        </li>
                        <?php
                        }
                        ?>
                    </ul>
                    <p>
                        <button class="button button--primary" type="submit">저장</button>
                    </p>
                </form>
            </div>
            <button type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
    const EventListFn = {
        register: function () {
            location.href = "/sitemanager/board/faq/faq-register.php?<?=http_build_query($requestInfo)?>";
        },
        topon: function (){
            if ($('input[name="select_seq[]"]:checked').length <= 0) {
                alert("TOP 설정할 질문을 선택해 주세요.");
                return false;
            }
            if(confirm("TOP 설정 하시겠습니까?")){
                $("[name='topon']").val('on');
                $("[name='frm']").attr("action","/sitemanager/board/faq/faq-top-proc.php").submit();
            }
        },
        topoff: function (){
            if ($('input[name="select_seq[]"]:checked').length <= 0) {
                alert("TOP 해지할 질문을 선택해 주세요.");
                return false;
            }
            if(confirm("TOP 해지 하시겠습니까?")){
                $("[name='topon']").val('off');
                $("[name='frm']").attr("action","/sitemanager/board/faq/faq-top-proc.php").submit();
            }
        },
        del_hide: function () {
            const massege = $('select[name="del_hide_select"] option:selected').text();
            if ($('input[name="select_seq[]"]:checked').length <= 0) {
                alert(massege+"할 질문을 선택해 주세요.");
                return false;
            }
            if(confirm(massege+"처리 하시겠습니까?")){
                //$("[name='frm']").attr("action","/sitemanager/board/faq/faq-del-hide-proc.php");
                $("[name='frm']").submit();
            }
        }
    }

    $(function () {
        $('[name=select]').on('click', function () {
            if ($(this).prop("checked")) {
                $("[name='select_seq[]']").prop("checked", true);
            } else {
                $("[name='select_seq[]']").prop("checked", false);
            }
        })
    });

    // 노출순서 변경
    (() => {
        const sortingModal = document.getElementById('sortingModal');
        const sortingList = sortingModal.querySelector('.sorting-list ul');
        let sorting = new Sortable(sortingList, {
            animation: 150,
            dataIdAttr: 'data-sorting-id',
        });
        const originSorted = sorting.toArray();
        sortingModal.addEventListener('show.bs.modal', () => {
            if (sorting) {
                sorting.sort(originSorted, false);
            }
        });
    })();
</script>
<?php require_once $ROOT_PATH_.'/sitemanager/assets/include/footer.php'; ?>
</body>
</html>

