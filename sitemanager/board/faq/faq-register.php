<?php
/** ==============================================================================
 * File: 관리자 > 게시판 > 고객센터FAQ 등록 (faq-register.php)
 * Date: 2023-03-03 오후 01:48
 * Created by @csw9569.DevGroup
 * Copyright 2023, baskinrobbins.co.kr/(Group IDD). All rights are reserved
 * ==============================================================================*/
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use \Groupidd\Model\ModelBase;
use \Groupidd\Common\CommonFunc;
use \Groupidd\Library\Validator;

// request check
$requestInfo    = array(
    'page'              => isset($_GET['page']) ?? 1 ,
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
$db->select('category, name');
$db->from('COMMON_CODE');
$db->where('code_usage', 'FAQ');
$db->where('is_view', 'Y');
$db->orderby('order_num');
$faqCategoryResult = $db->getAll();

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
        <h2>고객센터 FAQ 등록</h2>
    </headder>
    <div class="contents">
        <form name="frm" method="POST" action="faq-register-proc.php">
            <input type="hidden" name="csrf_token" value="<?=$CSRF_TOKEN_?>">
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
                        <th>TOP설정</th>
                        <td>
                            <div class="radio-group">
                                <label>
                                    <input type="radio" name="is_top" value="Y" id="is_top_Y" checked>
                                    <span><label for="is_top_Y">Y</label></span>
                                </label>

                                <label>
                                    <input type="radio" name="is_top" value="N" id="is_top_N">
                                    <span><label for="is_top_N">N</label></span>
                                </label>
                            </div>
                        </td>
                        <th>노출여부</th>
                        <td>
                            <div class="radio-group">
                                <label>
                                    <input type="radio" name="is_view" value="Y" id="is_view_Y" checked>
                                    <span><label for="is_view_Y">Y</label></span>
                                </label>

                                <label>
                                    <input type="radio" name="is_view" value="N" id="is_view_N" >
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
                                        <option value="<?=$row['category']?>"><?=$row['name']?></option>
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
                            <textarea id="content" name="content" id="" cols="100" rows="10"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>*답변</th>
                        <td colspan="3">
                            <textarea id="answer" name="answer" id="" cols="100" rows="10"></textarea>
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
    const form = $("form[name='frm']").get(0);
    var EventListFn = {
        list: function () {
            location.href = "faq-list.php?<?=http_build_query($requestInfo)?>";
        },
        register: function () {
            if(confirm("등록 하시겠습니까?")){
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
