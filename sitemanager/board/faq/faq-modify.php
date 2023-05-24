<?php
/** ==============================================================================
 * File: 관리자 > 게시판 > 고객센터FAQ 수정 (faq-modify.php)
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
    'seq'               => $_GET['seq'] ?? '',
    'page'              => $_GET['page'] ?? 1 ,
    'search'            => $_GET['search'] ?? '',
    'findword'          => $_GET['findword'] ?? '',
    'search_category'   => $_GET['search_category'] ?? '',
    'from_date'         => $_GET['from_date'] ?? '',
    'to_date'           => $_GET['to_date'] ?? '',
    'search_is_view'    => $_GET['search_is_view'] ?? ''
);

$validator = new Validator($requestInfo);
$validator->rule('integer', 'page');
$validator->rule('required', 'seq');
$validator->rule('integer', 'seq');
$validator->rule('in', 'search', array('title','content'));
if($validator->validate()) {                // validation 성공
    $requestInfo = $validator->data();
    $page     	 = $requestInfo['page'];    // 페이지 번호
}else{
    echo $validator->errorsJsAlert();
    exit();
}

$db = new ModelBase();
$db->select('seq, view_cnt_w, view_cnt_m, is_top, is_view, category, content, answer, reg_date, mod_date');
$db->from('BOARD_FAQ');
$db->where('seq', $requestInfo['seq']);
$db->where('is_del','N');
$db->limit(1);
$result = $db->getOne();

if(empty($result)){
    CommonFunc::jsAlert("존재하지 않는 정보 입니다.","history.back();");
    exit();
}

$db->init();
$db->select('category, name');
$db->from('COMMON_CODE');
$db->where('code_usage', 'FAQ');
$db->where('is_view', 'Y');
$db->orderby('order_num');
$faqCategoryResult = $db->getAll();

CommonFunc::history_proc($ADMIN_ID_, $USER_IP_, '게시판', '고객센터FAQ 상세', "{$ADMIN_ID_} 님이 {$result['content']} 정보를 조회하였습니다.");
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
            <li>게시판</li>
        </ul>
        <h2>고객센터 FAQ 수정</h2>
    </headder>
    <div class="contents">
        <form name="frm" method="POST" action="faq-modify-proc.php">
            <input type="hidden" name="csrf_token" value="<?=$CSRF_TOKEN_?>">
            <input type="hidden" name="seq" value="<?=$result['seq']?>">
            <input type="hidden" name="page" value="<?=$requestInfo['page']?>">
            <input type="hidden" name="search" value="<?=$requestInfo['search']?>">
            <input type="hidden" name="findword" value="<?=$requestInfo['findword']?>">
            <input type="hidden" name="search_category" value="<?=$requestInfo['search_category']?>">
            <input type="hidden" name="from_date" value="<?=$requestInfo['from_date']?>">
            <input type="hidden" name="to_date" value="<?=$requestInfo['to_date']?>">
            <input type="hidden" name="search_is_view" value="<?=$requestInfo['search_is_view']?>">
            <table class="table row">
                <colgroup>
                    <col style="width: 10%">
                    <col style="width: 40%">
                    <col style="width: 10%">
                    <col style="width: 40%">
                </colgroup>
                <tbody>
                    <tr>
                        <th>
                            조회수<br>(PC/MO/Total)
                        </th>
                        <td colspan="3">
                            <?=$result['view_cnt_w']?> / <?=$result['view_cnt_m']?> / <?=$result['view_cnt_w']+$result['view_cnt_m']?>
                        </td>
                    </tr>
                    <tr>
                        <th>TOP설정</th>
                        <td>
                            <div class="radio-group">
                                <label>
                                    <input type="radio" name="is_top" value="Y" id="is_top_Y" <?=CommonFunc::getChecked($result['is_top'], 'Y')?>>
                                    <span><label for="is_top_Y">Y</label></span>
                                </label>

                                <label>
                                    <input type="radio" name="is_top" value="N" id="is_top_N" <?=CommonFunc::getChecked($result['is_top'], 'N')?>>
                                    <span><label for="is_top_N">N</label></span>
                                </label>
                            </div>
                        </td>
                        <th>노출여부</th>
                        <td>
                            <div class="radio-group">
                                <label>
                                    <input type="radio" name="is_view" value="Y" id="is_view_Y" <?=CommonFunc::getChecked($result['is_view'], 'Y')?>>
                                    <span><label for="is_view_Y">Y</label></span>
                                </label>

                                <label>
                                    <input type="radio" name="is_view" value="N" id="is_view_N" <?=CommonFunc::getChecked($result['is_view'], 'N')?>>
                                    <span><label for="is_view_N">N</label></span>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            구분
                        </th>
                        <td colspan="3">
                            <div class="select-input-group">
                                <select name="category" id="" class="form-input width-100">
                                    <?php
                                    foreach ($faqCategoryResult as $row) {
                                        ?>
                                        <option value="<?=$row['category']?>" <?=CommonFunc::getSelected($row['category'], $result['category'])?>><?=$row['name']?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>*질문</th>
                        <td colspan="3">
                            <textarea id="content" name="content" id="" cols="100" rows="10"><?=$result['content']?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>*답변</th>
                        <td colspan="3">
                            <textarea id="answer" name="answer" id="" cols="100" rows="10"><?=$result['answer']?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>등록일<br>(최종수정일)</th>
                        <td colspan="3">
                            <?=$result['reg_date']?> (<?=$result['mod_date']?>)
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="bottom-group">
                <button type="button" class="button" data-btn="eventBtn" data-fn="list">목록</button>
                <button type="button" class="button button--blue" data-btn="eventBtn" data-fn="modify">수정</button>
            </div>
        </form>
    </div>
</section>
<script>
    const form = $("form[name='frm']").get(0);
    var EventListFn = {
        list: function () {
            location.href = "faq-list.php?<?=http_build_query($requestInfo)?>";
        },
        modify: function () {
            if(confirm("수정 하시겠습니까?")){
                if(fn_formValidate()){
                    form.submit();
                }
            }
            return false;
        }
    }

    function fn_formValidate(){

        if($("[name='content']").val() == ""){
            alert('질문을 입력해 주세요.');
            return false;
        }

        if($("[name='answer']").val() == ""){
            alert('답변을 입력해 주세요.');
            return false;
        }

        return true;
    }

</script>
<?php require_once $ROOT_PATH_ . '/sitemanager/assets/include/footer.php'; ?>
</body>
</html>

