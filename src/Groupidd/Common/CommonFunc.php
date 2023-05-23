<?php

namespace Groupidd\Common;

use Groupidd\Model\ModelBase;
use \PHPMailer\PHPMailer\PHPMailer;

class CommonFunc
{

    // ENCRYPT
    public static function strEncrypt($fromStr, $key)
    {
        $size = mcrypt_get_iv_size(MCRYPT_CAST_128, MCRYPT_MODE_CFB);
        $iv = mcrypt_create_iv($size, MCRYPT_RAND);
        $key = str_pad($key, 16, chr(0));

        $toStrEncrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $fromStr, MCRYPT_MODE_ECB, $iv);
        $toStrEncode = base64_encode($toStrEncrypted);

        return $toStrEncode;
    }

    // DECRYPT
    public static function strDecrypt($fromStr, $key)
    {
        $size = mcrypt_get_iv_size(MCRYPT_CAST_128, MCRYPT_MODE_CFB);
        $iv = mcrypt_create_iv($size, MCRYPT_RAND);
        $key = str_pad($key, 16, chr(0));

        $toStrDecrypted = base64_decode($fromStr);
        $toStrDecode = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $toStrDecrypted, MCRYPT_MODE_ECB, $iv);

        return $toStrDecode;
    }

    public static function stringEncrypt($fromStr, $key)
    {
        return base64_encode(openssl_encrypt($fromStr, 'AES-256-CBC', hash('sha256', $key), true, substr(hash('sha256', $key), 0, 16)));
    }

    public static function stringDecrypt($fromStr, $key)
    {
        return openssl_decrypt(base64_decode($fromStr), 'AES-256-CBC', hash('sha256', $key), true, substr(hash('sha256', $key), 0, 16));
    }

    // NEW_ENCRYPT
    /*public static function strEncrypt($fromStr, $key){

        return strtoupper(bin2hex(openssl_decrypt($fromStr, "AES-256-CBC", $key, true, str_repeat(chr(0), 16))));
    }

    // NEW_DECRYPT
    public static function strDecrypt($fromStr, $key){

        return openssl_decrypt(hex2bin($fromStr), "AES-256-CBC", $key, true, str_repeat(chr(0), 16));
    }*/

    // 자바스크립트(alert & location)
    public static function jsAlert($msg, $action)
    {
        $jsStr = "<script>" . chr(13);

        if ($msg != "") {
            $jsStr = $jsStr . "alert('" . $msg . "');" . chr(13);
        }

        $jsStr = $jsStr . $action . chr(13);
        $jsStr = $jsStr . "</script>" . chr(13);
        echo $jsStr;
    }

    // 자바스크립트(confirm box & location)
    public static function jsConfirm($msg, $action)
    {
        $jsStr = "<script>" . chr(13);
        $jsStr = $jsStr . "if(confirm('{$msg}')==true){" . chr(13);
        $jsStr = $jsStr . $action . chr(13);
        $jsStr = $jsStr . "}else{" . chr(13);
        $jsStr = $jsStr . "history.go(-1);" . chr(13);
        $jsStr = $jsStr . "}</script>" . chr(13);

        echo $jsStr;
    }

    // select box value compare
    public static function getSelected($strValue1, $strValue2)
    {
        if (strtoupper(trim((string)$strValue1)) == strtoupper(trim((string)$strValue2))) {
            return "selected";
        }
    }

    // check box value compare
    public static function getChecked($strValue1, $strValue2)
    {
        if (strtoupper(trim((string)$strValue1)) == strtoupper(trim((string)$strValue2))) {
            return "checked";
        }
    }

    public static function getArrayChecked($strValue, $arrayValue)
    {
        if (in_array($strValue, $arrayValue)) {
            return "checked";
        }
    }

    // XSS & Injection
    public static function verifyValue($value)
    {
        $value = $value != '' ? trim($value) : '';

        $value = str_ireplace("<object", "<x-object", $value);
        $value = str_ireplace("</object", "</x-object", $value);
        $value = str_ireplace("<style", "<x-style", $value);
        $value = str_ireplace("</style", "</x-style", $value);
        $value = str_ireplace("<script", "<x-script", $value);
        $value = str_ireplace("</script", "</x-script", $value);
        $value = str_ireplace("<embed", "<x-embed", $value);
        $value = str_ireplace("</embed", "</x-embed", $value);
        $value = str_ireplace('<video', '<x-video', $value);
        $value = str_ireplace('<audio', '<x-audio', $value);

        $value = str_ireplace("IcxMarcos", "", $value);
        $value = str_ireplace("gb2312", "", $value);
        $value = str_ireplace("encode", "", $value);
        $value = str_ireplace("session", "", $value);
        $value = str_ireplace("request", "", $value);

        $value = str_ireplace("and ", "", $value);
        $value = str_ireplace("or ", "", $value);
        $value = str_ireplace("delete ", "", $value);
        $value = str_ireplace("drop ", "", $value);
        $value = str_ireplace("insert ", "", $value);
        $value = str_ireplace("select ", "", $value);
        $value = str_ireplace("union ", "", $value);
        $value = str_ireplace("update ", "", $value);

        $value = str_ireplace("xp_cmdshell ", "", $value);
        $value = str_ireplace("xp_dirtree ", "", $value);
        $value = str_ireplace("xp_regread ", "", $value);
        $value = str_ireplace("exec ", "", $value);
        $value = str_ireplace("Openrowset ", "", $value);
        $value = str_ireplace("sp_", "", $value);

        $value = str_ireplace('javascript:', '', $value);
        $value = str_ireplace('onload', '', $value);
        $value = str_ireplace('onunload', '', $value);
        $value = str_ireplace('onreset', '', $value);
        $value = str_ireplace('onmouseover', '', $value);
        $value = str_ireplace('onmouseout', '', $value);
        $value = str_ireplace('onerror', '', $value);
        $value = str_ireplace('onfocus', '', $value);
        $value = str_ireplace('onkeyup', '', $value);

        $pattern = '/\"|\'|:|--|<|>|%|\(|\)|\+|;|&/';

        $lastTxt = preg_replace_callback($pattern, 'self::replacePattern', $value);

        return $lastTxt;
    }

    // injection pattern
    private static function replacePattern($matches)
    {
        switch ($matches[0]) {
            case '"':
                return "&quot;";
                break;
            case "'":
                return "´";
                break;
            case ":":
                return "&#58;";
                break;
            case "--":
                return "&#45;&#45;";
                break;
            case "<":
                return "&lt;";
                break;
            case ">":
                return "&gt;";
                break;
            case "%":
                return "&#37;";
                break;
            case "(":
                return "&#40;";
                break;
            case ")":
                return "&#41;";
                break;
            case "+":
                return "&#43;";
                break;
            case ";":
                return "&#59;";
                break;
            case "#":
                return "&#35;";
                break;
            case "&":
                return "&amp;";
                break;
        }
    }

    // replace htmlcode
    public static function replaceHtmlDecode($str, $script = "")
    {
        //기존 치환문자 땜에 넣어놨음.
        $str = str_replace("&amp;lt;", "<", $str);
        $str = str_replace("&amp;gt;", ">", $str);

        $str = str_replace("&amp;", "&", $str);

        $str = str_replace("&quot;", '"', $str);
        $str = str_replace("´", "'", $str);
        $str = str_replace("˝", "\"", $str);
        $str = str_replace("&#58;", ":", $str);
        $str = str_replace("&#59;", ";", $str);
        $str = str_replace("&#45;&#45;", "--", $str);
        $str = str_replace("&lt;", "<", $str);
        $str = str_replace("&gt;", ">", $str);
        $str = str_replace("&#37;", "%", $str);
        $str = str_replace("&#40;", "(", $str);
        $str = str_replace("&#41;", ")", $str);
        $str = str_replace("&#43;", "+", $str);
        $str = str_replace("&#59;", ";", $str);
        $str = str_replace("&#35;", "#", $str);
        $str = str_replace("&amp;", "&", $str);


        if ($script == "script") {
            $str = str_replace("<x-style", "<style", $str);
            $str = str_replace("</x-style", "</style", $str);
        }

        return $str;
    }

    // replace htmlcode
    public static function replaceToOGTag($str)
    {
        //기존 치환문자 땜에 넣어놨음.
        $str = str_replace("&amp;lt;", "<", $str);
        $str = str_replace("&amp;gt;", ">", $str);

        $str = str_replace("&amp;", "&", $str);

        $str = str_replace("&quot;", '"', $str);
        $str = str_replace("´", "'", $str);
        $str = str_replace("˝", "\"", $str);
        $str = str_replace("&#58;", ":", $str);
        $str = str_replace("&#59;", ";", $str);
        $str = str_replace("&#45;&#45;", "--", $str);
        $str = str_replace("&lt;", "<", $str);
        $str = str_replace("&gt;", ">", $str);
        $str = str_replace("&#37;", "%", $str);
        $str = str_replace("&#40;", "(", $str);
        $str = str_replace("&#41;", ")", $str);
        $str = str_replace("&#43;", "+", $str);
        $str = str_replace("&#59;", ";", $str);
        $str = str_replace("&#35;", "#", $str);
        $str = str_replace("&amp;", "&", $str);


        $str = str_replace("<br>", " ", $str);

        return $str;
    }

    // replace br tag
    public static function replaceBrTag($str)
    {
        if (empty($str)) return '';

        $str = str_replace("&amp;lt;", "<", $str);
        $str = str_replace("&amp;gt;", ">", $str);

        $str = str_replace("&lt;", "<", $str);
        $str = str_replace("&gt;", ">", $str);

        return $str;
    }


    // replace br tag
    public static function replaceHrefTag($str)
    {
        if (empty($str)) return '';

        $str = str_replace("&#58;", ":", $str);
        $str = str_replace("&amp;", "&", $str);

        $str = self::makeHttp($str);

        return $str;
    }


    public static function replaceHrefTagtwo($str)
    {
        if (empty($str)) return '';

        $str = str_replace("&#58;", ":", $str);
        $str = str_replace("&amp;", "&", $str);

        return $str;
    }


    // remove Tag
    public static function stripTag($str)
    {
        $str = strip_tags($str);
        $str = str_replace("&nbsp;", " ", $str);
        return $str;
    }

    // string reduction
    public static function cutUtf8Str($str, $len, $tail)
    {
        $len = $len * 2;
        $c = substr(str_pad(decbin(ord($str[$len])), 8, '0', STR_PAD_LEFT), 0, 2);
        if ($c == '10')
            for (; $c != '11' && $c[0] == 1; $c = substr(str_pad(decbin(ord($str[--$len])), 8, '0', STR_PAD_LEFT), 0, 2)) ;

        return substr($str, 0, $len) . (strlen($str) - strlen($tail) >= $len ? $tail : '');
    }


    // paging (Page:현재페이지, pageSize : 페이지 사이즈, totalPage:페이지 전체갯수, param : 검색파라미터, pagingMax : 한페이지 페이지네이션 개수)
    public static function getPaging($Page, $PageSize, $TotalPage, $requestInfo, $pagingMax = "")
    {
        $pagingArea = "";
        if ($TotalPage > 1) {
            $intBlockPage = "";
            $i = "";

            if (!$pagingMax) $pagingMax = 5;    //노출 페이지 수
            $intBlockPage = (int)(($Page - 1) / $pagingMax) * $pagingMax + 1;

            // 이전 페이지 번호 계산
            $intBlockPage2 = $intBlockPage - $pagingMax;
            if ($intBlockPage2 < 1) {
                $intBlockPage2 = 1;
            }

            $pagingArea = "<ul>\n";

            if ($Page > 1) {
                $requestInfo['page'] = 1;
                $pagingArea .= "<li class='first'>\n";
                $pagingArea .= "    <a href='?".http_build_query($requestInfo)."' class='pagination__link'>\n";
                $pagingArea .= "        <span class='pagination__name'>\n";
                $pagingArea .= "            처음\n";
                $pagingArea .= "        </span>\n";
                $pagingArea .= "    </a>\n";
                $pagingArea .= "</li>\n";;
            } else {
                $pagingArea .= "<li class='first disabled'>\n";
                $pagingArea .= "    <a href='?".http_build_query($requestInfo)."' class='pagination__link'>\n";
                $pagingArea .= "        <span class='pagination__name'>\n";
                $pagingArea .= "            처음\n";
                $pagingArea .= "        </span>\n";
                $pagingArea .= "    </a>\n";
                $pagingArea .= "</li>\n";
            }

            if ($intBlockPage > 1) {
                $requestInfo['page'] = $intBlockPage2;
                $pagingArea .= "<li class='prev'>\n";
                $pagingArea .= "    <a href='?" . http_build_query($requestInfo) . "'>\n";
                $pagingArea .= "        <span>\n";
                $pagingArea .= "            이전\n";
                $pagingArea .= "        </span>\n";
                $pagingArea .= "    </a>\n";
                $pagingArea .= "</li>\n";
            } else {
                $pagingArea .= "<li class='prev disabled'>\n";
                $pagingArea .= "    <a href='#'>\n";
                $pagingArea .= "        <span>\n";
                $pagingArea .= "            이전\n";
                $pagingArea .= "        </span>\n";
                $pagingArea .= "    </a>\n";
                $pagingArea .= "</li>\n";
            }

            $i = 1;
            do {
                if ((int)$intBlockPage == (int)$Page) {
                    $pagingArea .= "<li>\n";
                    $pagingArea .= "    <strong>\n";
                    $pagingArea .= $intBlockPage."\n";
                    $pagingArea .= "    </strong>\n";
                    $pagingArea .= "</li>\n";
                } else {
                    $requestInfo['page'] = $intBlockPage;
                    $pagingArea .= "<li>\n";
                    $pagingArea .= "    <a href='?".http_build_query($requestInfo)."'>\n";
                    $pagingArea .= "        <span>\n";
                    $pagingArea .= $intBlockPage."\n";
                    $pagingArea .= "        </span>\n";
                    $pagingArea .= "    </a>\n";
                    $pagingArea .= "</li>\n";
                }
                $intBlockPage = $intBlockPage + 1;
                $i = $i + 1;
            } while ($i <= $pagingMax && $intBlockPage <= $TotalPage);

            if ($intBlockPage <= $TotalPage) {
                $requestInfo['page'] = $intBlockPage;
                $pagingArea .= "<li class='next'>\n";
                $pagingArea .= "    <a href='?".http_build_query($requestInfo)."'>";
                $pagingArea .= "        <span>";
                $pagingArea .= "            다음";
                $pagingArea .= "        </span>";
                $pagingArea .= "    </a>";
                $pagingArea .= "</li>";
            } else {
                $pagingArea .= "<li class='next disabled'>\n";
                $pagingArea .= "    <a href='#'>";
                $pagingArea .= "        <span>";
                $pagingArea .= "            다음";
                $pagingArea .= "        </span>";
                $pagingArea .= "    </a>";
                $pagingArea .= "</li>";
            }

            if ($Page == $TotalPage) {
                $pagingArea .= "<li class='last disabled'>\n";
                $pagingArea .= "    <a href='?" . http_build_query($requestInfo) . "'>\n";
                $pagingArea .= "        <span>\n";
                $pagingArea .= "            마지막\n";
                $pagingArea .= "        </span>\n";
                $pagingArea .= "    </a>\n";
                $pagingArea .= "</li>\n";
            } else {
                $requestInfo['page'] = $TotalPage;
                $pagingArea .= "<li class='last'>\n";
                $pagingArea .= "    <a href='?" . http_build_query($requestInfo) . "'>\n";
                $pagingArea .= "        <span>\n";
                $pagingArea .= "            마지막\n";
                $pagingArea .= "        </span>\n";
                $pagingArea .= "    </a>\n";
                $pagingArea .= "</li>\n";
            }
            $pagingArea .= "</ul>" . chr(13);
        }
        echo $pagingArea;
    }

    // ajax paging (Page:현재페이지, pageSize : 페이지 사이즈, totalPage:페이지 전체갯수, param : 검색파라미터, pagingMax : 한페이지 페이지네이션 개수)
    public static function getPagingAjax($Page, $PageSize, $TotalPage, $requestInfo, $pagingMax = "", $requestExtraString = "", $callPageUrl = "")
    {
        $pagingArea = "";
        if ($TotalPage > 1) {

            if (!$pagingMax) $pagingMax = 10;    //노출 페이지 수
            $intBlockPage = (int)(($Page - 1) / $pagingMax) * $pagingMax + 1;

//<!--        <a href="#" class="first"><span class="blind">첫페이지로</span></a>-->
//<!--        <a href="#" class="last"><span class="blind">마지막페이지로</span></a>-->

            // 이전 페이지 번호 계산
            $intBlockPage2 = $intBlockPage - $pagingMax;
            if ($intBlockPage2 < 1) {
                $intBlockPage2 = 1;
            }

            $pagingArea = "<div class=\"pagination\">" . chr(13);

            if ($intBlockPage > 1) {
                $requestInfo['page'] = $intBlockPage2;
                $pagingArea .= "<a href='{$callPageUrl}?" . http_build_query($requestInfo) . "{$requestExtraString}' class=\"pre\"><span class=\"blind\">이전페이지</span></a>" . chr(13);
            } else {
                $pagingArea .= "<a href='#none' class=\"pre\"><span class=\"blind\">이전페이지</span></a>" . chr(13);
            }

            $i = 1;
            do {
                if ((int)$intBlockPage == (int)$Page) {
                    $pagingArea .= "<a href='#none' class=\"num on\">" . $intBlockPage . "</a>" . chr(13);
                } else {
                    $requestInfo['page'] = $intBlockPage;
                    $pagingArea .= "<a href='{$callPageUrl}?" . http_build_query($requestInfo) . "{$requestExtraString}' class=\"num\">" . $intBlockPage . "</a>" . chr(13);
                }

                $intBlockPage = $intBlockPage + 1;
                $i = $i + 1;
            } while ($i <= $pagingMax && $intBlockPage <= $TotalPage);


            if ($intBlockPage <= $TotalPage) {
                $requestInfo['page'] = $intBlockPage;
                $pagingArea .= "<a href='{$callPageUrl}?" . http_build_query($requestInfo) . "{$requestExtraString}' class=\"next\" ><span class=\"blind\">다음페이지</span></a>" . chr(13);
            } else {
                $pagingArea .= "<a href='#none' class=\"next\"><span class=\"blind\">다음페이지</span></a>" . chr(13);
            }

            $pagingArea .= "</div>" . chr(13);
        }
        return $pagingArea;
    }


    // paging (Page:현재페이지, pageSize : 페이지 사이즈, totalPage:페이지 전체갯수, param : 검색파라미터, pagingMax : 한페이지 페이지네이션 개수)
    public static function frontPaging($Page, $PageSize, $TotalPage, $requestInfo, $pagingMax = "")
    {
        $pagingArea = "";
        if ($TotalPage > 1) {
            $intBlockPage = "";
            $i = "";

            if (!$pagingMax) $pagingMax = 5;    //노출 페이지 수
            $intBlockPage = (int)(($Page - 1) / $pagingMax) * $pagingMax + 1;

            // 이전 페이지 번호 계산
            $intBlockPage2 = $intBlockPage - $pagingMax;
            if ($intBlockPage2 < 1) {
                $intBlockPage2 = 1;
            }

            $pagingArea = "<ul class='pagination'>\n";

            if ($Page > 1) {
                $requestInfo['page'] = 1;
                $pagingArea .= "<li class='pagination__item pagination__item--icon pagination__item--prev'>\n";
                $pagingArea .= "    <a href='?".http_build_query($requestInfo)."' class='pagination__link'>\n";
                $pagingArea .= "        <span class='pagination__name'>\n";
                $pagingArea .= "            처음\n";
                $pagingArea .= "        </span>\n";
                $pagingArea .= "    </a>\n";
                $pagingArea .= "</li>\n";;
            } else {
                $pagingArea .= "<li class='pagination__item pagination__item--icon pagination__item--prev pagination__item--disabled'>\n";
                $pagingArea .= "    <a href='?".http_build_query($requestInfo)."' class='pagination__link'>\n";
                $pagingArea .= "        <span class='pagination__name'>\n";
                $pagingArea .= "            처음\n";
                $pagingArea .= "        </span>\n";
                $pagingArea .= "    </a>\n";
                $pagingArea .= "</li>\n";
            }

            if ($intBlockPage > 1) {
                $requestInfo['page'] = $intBlockPage2;
                $pagingArea .= "<li class='pagination__item pagination__item--icon pagination__item--prev'>\n";
                $pagingArea .= "    <a href='?" . http_build_query($requestInfo)."' class='pagination__link'>\n";
                $pagingArea .= "        <span class='pagination__name'>\n";
                $pagingArea .= "            이전\n";
                $pagingArea .= "        </span>\n";
                $pagingArea .= "    </a>\n";
                $pagingArea .= "</li>\n";
            } else {
                $pagingArea .= "<li class='pagination__item pagination__item--icon pagination__item--prev pagination__item--disabled'>\n";
                $pagingArea .= "    <a href='?".http_build_query($requestInfo)."' class='pagination__link'>\n";
                $pagingArea .= "        <span class='pagination__name'>\n";
                $pagingArea .= "            이전\n";
                $pagingArea .= "        </span>\n";
                $pagingArea .= "    </a>\n";
                $pagingArea .= "</li>\n";
            }

            $i = 1;
            do {
                if ((int)$intBlockPage == (int)$Page) {
                    $pagingArea .= "<li class='pagination__item pagination__item--current' aria-current='page'>\n";
                    $pagingArea .= "    <strong class='pagination__link'>\n";
                    $pagingArea .= "        <span class='pagination__name'>".$intBlockPage."</span>\n";
                    $pagingArea .= "    </strong>\n";
                    $pagingArea .= "</li>\n";
                } else {
                    $requestInfo['page'] = $intBlockPage;
                    $pagingArea .= "<li class='pagination__item'>\n";
                    $pagingArea .= "    <a href='?".http_build_query($requestInfo)."' class='pagination__link'>\n";
                    $pagingArea .= "        <span class='pagination__name'>\n";
                    $pagingArea .= $intBlockPage."\n";
                    $pagingArea .= "        </span>\n";
                    $pagingArea .= "    </a>\n";
                    $pagingArea .= "</li>\n";
                }
                $intBlockPage = $intBlockPage + 1;
                $i = $i + 1;
            } while ($i <= $pagingMax && $intBlockPage <= $TotalPage);

            if ($intBlockPage <= $TotalPage) {
                $requestInfo['page'] = $intBlockPage;
                $pagingArea .= "<li class='pagination__item pagination__item--icon pagination__item--next'>\n";
                $pagingArea .= "    <a href='?".http_build_query($requestInfo)."' class='pagination__link'>";
                $pagingArea .= "        <span class='pagination__name'>\n";
                $pagingArea .= "            다음";
                $pagingArea .= "        </span>";
                $pagingArea .= "    </a>";
                $pagingArea .= "</li>";
            } else {
                $pagingArea .= "<li class='pagination__item pagination__item--icon pagination__item--next pagination__item--disabled'>\n";
                $pagingArea .= "    <a href='#' class='pagination__link'>\n";
                $pagingArea .= "        <span class='pagination__name'>\n";
                $pagingArea .= "            다음";
                $pagingArea .= "        </span>";
                $pagingArea .= "    </a>";
                $pagingArea .= "</li>";
            }

            if ($Page == $TotalPage) {
                $pagingArea .= "<li class='pagination__item pagination__item--icon pagination__item--next pagination__item--disabled'>\n";
                $pagingArea .= "    <a href='#' class='pagination__link'>\n";
                $pagingArea .= "        <span class='pagination__name'>\n";
                $pagingArea .= "            마지막\n";
                $pagingArea .= "        </span>\n";
                $pagingArea .= "    </a>\n";
                $pagingArea .= "</li>\n";
            } else {
                $requestInfo['page'] = $TotalPage;
                $pagingArea .= "<li class='pagination__item pagination__item--icon pagination__item--next'>\n";
                $pagingArea .= "    <a href='?" . http_build_query($requestInfo) . "' class='pagination__link'>\n";
                $pagingArea .= "        <span class='pagination__name'>\n";
                $pagingArea .= "            마지막\n";
                $pagingArea .= "        </span>\n";
                $pagingArea .= "    </a>\n";
                $pagingArea .= "</li>\n";
            }
            $pagingArea .= "</ul>" . chr(13);
        }
        echo $pagingArea;
    }


    // fileupload function (jpeg|jpg|gif|png) , filetype:image/doc
    public static function uploadFile($upload_root, $file_inputbox_name, $upload_dir, $max_filesize = "", $usable_filetype = "image", $ori_filename = false)
    {

        // 파일업로드 용량 설정 : 5MB 제한
        if (empty($max_filesize)) $max_filesize = 1024 * 1024 * 5;

        $error = $_FILES[$file_inputbox_name]['error'];
        if ($error != UPLOAD_ERR_OK) {
            switch ($error) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    return "error : filesize";
                    break;
                case UPLOAD_ERR_NO_FILE:            // 첨부파일이 없을 경우
                    return "";
                    break;
                default:
                    return "error : file upload error";
                    break;
            }
        }

        // upload folder exist check
        if (!is_dir($upload_root . $upload_dir))
            mkdir($upload_root . $upload_dir, 0777, true);


        $upfile_temp = $_FILES[$file_inputbox_name]['tmp_name'];
        $upfile_name = $_FILES[$file_inputbox_name]['name'];
        $file_size = $_FILES[$file_inputbox_name]['size'];
        $extension = pathinfo($_FILES[$file_inputbox_name]['name'], PATHINFO_EXTENSION);
//        $file_type        = $_FILES[$file_inputbox_name]['type'];

        if (is_uploaded_file($upfile_temp)) {

            // file size check
            if ($file_size > $max_filesize) {
                return "error : size";
            }

            // file extension check
            if ($usable_filetype == "doc") {
                if (!(preg_match("/doc|docx|ppt|pptx|xls|xlsx|xml|pdf|txt/i", $extension))) {
                    return "error : extension";
                }
            } else if ($usable_filetype == "image") {
                if (!(preg_match("/jpeg|jpg|gif|png/i", $extension))) {
                    return "error : extension";
                }

                // 실제 이미지 파일인지 확인 (image 파일명으로 파일 변조 막기)
                if (getimagesize($upfile_temp) == false) {
                    return "error : noimage";
                }
            } else if ($usable_filetype == "all") {
                if (!(preg_match("/doc|docx|ppt|pptx|xls|xlsx|zip|jpeg|jpg|gif|png|txt/i", $extension))) {
                    return "error : extension";
                }
            } else if ($usable_filetype == "img/mp4"){
                if (!(preg_match("/jpeg|jpg|gif|png|mp4/i", $extension))) {
                    return "error : extension";
                }
            } else if ($usable_filetype == "img/pdf"){
                if (!(preg_match("/jpeg|jpg|gif|png|pdf/i", $extension))) {
                    return "error : extension";
                }
            }

            // unique file name check (use the hash filename)
            $temp_filename = hash('md5', md5(microtime()));

            if ($ori_filename == true) {
                $temp_filename .= "__" . $upfile_name;
            }

            $file_index = 0;

            while (file_exists($upload_root . $upload_dir . "/" . $temp_filename . "." . $extension)) {
                $temp_filename = $temp_filename . "_" . $file_index;
                $file_index++;
            };


            $real_filename = $temp_filename . "." . $extension; //실제파일명
            $destination = $upload_root . $upload_dir . "/" . $real_filename;
            $temp_path = $upfile_temp;
            if (!move_uploaded_file($temp_path, $destination)) ;

            return $real_filename;

        } else {
            return "";
        }
    }

    /** FILE MIME TYPE RETURN
     * @param $file
     * @return bool|mixed|string
     */
    public static function get_mime($file)
    {
        if (function_exists("finfo_file")) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
            $mime = finfo_file($finfo, $file);
            finfo_close($finfo);
            return $mime;
        } else if (function_exists("mime_content_type")) {
            return mime_content_type($file);
        } else if (!stristr(ini_get("disable_functions"), "shell_exec")) {
            // http://.com/a/134930/1593459
            $file = escapeshellarg($file);
            $mime = shell_exec("file -bi " . $file);
            return $mime;
        } else {
            return false;
        }
    }

    public static function multipleFormReorder($array)
    {
        $newArray = [];
        foreach ($array as $key => $tempArray) {
            $index = 0;
            foreach ($tempArray as $newKey => $value) {
                # code...
                $newArray[$index][$key] = $value;
                $index++;
            }
        }

        return $newArray;
    }

    public static function getRandom($type = '', $len = 10)
    {
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numeric = '0123456789';
        $special = '`~!@#$%^&*()-_=+\\|[{]};:\'",<.>/?';
        $key = '';
        $token = '';
        if ($type == '') {
            $key = $lowercase . $uppercase . $numeric;
        } else {
            if (strpos($type, '09') > -1) $key .= $numeric;
            if (strpos($type, 'az') > -1) $key .= $lowercase;
            if (strpos($type, 'AZ') > -1) $key .= $uppercase;
            if (strpos($type, '$') > -1) $key .= $special;
        }
        for ($i = 0; $i < $len; $i++) {
            $token .= $key[mt_rand(0, strlen($key) - 1)];
        }

        return $token;
    }

    public static function makeHttp($val)
    {
        if (strlen($val) > 0) {
            if (strpos($val, 'ttp') > 0) {
                return $val;
            } else {
                return "http://" . $val;
            }

        } else {
            return '';
        }
    }

    function str_mask($str, $start = 0, $length = null)
    {
        $mask = preg_replace("/\S/", "*", $str);
        if (is_null($length)) {
            $mask = substr($mask, $start);
            $str = substr_replace($str, $mask, $start);
        } else {
            $mask = substr($mask, $start, $length);
            $str = substr_replace($str, $mask, $start, $length);
        }
        return $str;
    }

    public static function isNumber($val, $check_null = false)
    {
        if ($check_null) {
            if (!$val) {
                return false;
            }
        }
        return preg_match('/^[0-9]*$/', $val);
    }

    public static function arr_sort($array, $key, $sort='asc'){
        $keys = array();
        $vals = array();
        foreach ($array as $k => $v) {
            $i = $v[$key] . '.' . $k;
            $vals[$i] = $v;
            array_push($keys, $k);
        }

        unset($array);
        if ($sort == 'asc') {
            ksort($vals, SORT_STRING | SORT_FLAG_CASE );
        } else {
            krsort($vals, SORT_STRING | SORT_FLAG_CASE);
        }

        $ret = array_combine($keys, $vals);

        unset($keys);
        unset($vals);

        return $ret;
    }

    public static function getIpTreason($ip, $ipTreason){
        $return = '';
        if($ip != '' && $ipTreason != ''){
            $return = array();
            $ipArray = explode('.', $ip);
            $lastIpNum = end($ipArray);
            if ($lastIpNum <= $ipTreason) {
                $idx=0;
                for ($i = ((int)$lastIpNum + 1); $i <= (int)$ipTreason; $i++) {
                    $return[$idx] = $ipArray[0] . '.' . $ipArray[1] . '.' . $ipArray[2] . '.' . $i;
                    $idx++;
                }
            }
            return $return;
        }

    }

    public static function file_delete($file_path){
//    $file_path = iconv("UTF-8", "EUC-KR", $file_path);
        if(is_file($file_path)){
            unlink($file_path);
        }
    }

    public static function history_proc($admin_id, $user_ip, $depth_1, $depth_2, $work){
        $db = new ModelBase();
        $db->from('ADMIN_HISTORY_LOG');
        $historyInfo = array();
        $historyInfo['admin_id']    = $admin_id;
        $historyInfo['access_ip']   = $user_ip;
        $historyInfo['depth_1']     = $depth_1;
        $historyInfo['depth_2']     = $depth_2;
        $historyInfo['work']        = $work;
        $db->insert($historyInfo);
        $db->close();
    }

    public static function admin_authority($route, $admin_lv){

        switch($route){

            // 관리자
            case 'account' :
                if(strpos($admin_lv, "FA") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;
            case 'actionLog' :
                if(strpos($admin_lv, "FA") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;

            //메인
            //메인 콘텐츠
            case 'mainContents' :
                if(strpos($admin_lv, "AA") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;
            // 검색 아이콘
            case 'searchIcon' :
                if(strpos($admin_lv, "AB") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;
            // 이용약관
            case 'terms' :
                if(strpos($admin_lv, "AC") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;

            // 게시판
            // 점포개설문의
            case 'storeOpeningInquiry' :
                if(strpos($admin_lv, "EA") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;
            // 직영점 입점 제의
            case 'directstoreInquiry' :
                if(strpos($admin_lv, "EB") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;
            // FAQ
            case 'faq' :
                if(strpos($admin_lv, "EC") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;
            // 창업 FAQ
            case 'founded' :
                if(strpos($admin_lv, "ED") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;
            // 칭찬점포
            case 'compliment' :
                if(strpos($admin_lv, "EE") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;
            // 공지사항
            case 'notification' :
                if(strpos($admin_lv, "EF") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;
            // 보도자료
            case 'press' :
                if(strpos($admin_lv, "EG") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;

            // 제품관리
            //제품구성관리
            case 'composition' :
                if(strpos($admin_lv, "BA") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;
            // 이달의 맛
            case 'monthBest' :
                if(strpos($admin_lv, "BB") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;
            // 이달의 맛 히스토리
            case 'monthBestHistory' :
                if(strpos($admin_lv, "BC") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;
            // 제품 등록
            case 'main' :
                if(strpos($admin_lv, "BD") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;

            // 프로모션
            // 이벤트
            case 'event' :
                if(strpos($admin_lv, "CA") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;
            // 배라광장
            case 'baraPlaza' :
                if(strpos($admin_lv, "CB") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;
            // BR레시피
            case 'brRecipe' :
                if(strpos($admin_lv, "CC") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;
            // 마이 플레이버 리스트 항목관리
            case 'myFlavorManagement' :
                if(strpos($admin_lv, "CD") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;
            // 마이 플레이버 리스트
            case 'myFlavor' :
                if(strpos($admin_lv, "CE") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;
            // 배라이즈백
            case 'baraIsBack' :
                if(strpos($admin_lv, "CF") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;
            // 프로모션 배너관리
            case 'banner' :
                if(strpos($admin_lv, "CG") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;

            // 매장관리
            // 매장관리
            case 'store' :
                if(strpos($admin_lv, "DA") === false && strpos($admin_lv, "ALL") === false){
                    CommonFunc::jsAlert("권한이 없습니다.","history.back();");
                    exit();
                }
                break;
        }
    }

    public static function send_mail($subject, $body){

        $mail = new PHPMailer(true);
        $mail->CharSet = PHPMailer::CHARSET_UTF8; //안쓰면 한글깨짐
        $mail->SMTPAuth    = true;
        $mail->SMTPSecure  = 'ssl';
        $mail->Host        = 'smtp.gmail.com';
        $mail->Port        = 465;
        $mail->Mailer        = 'smtp';
        $mail->Username    = 'csw9569@groupidd.com';
        $mail->Password    = 'osnqpkkmdxpakcne';
        $mail->addAddress('csw9569@naver.com');
        $mail->setFrom('csw9569@groupidd.com');
        $mail->isHTML(true);
        $mail->Subject     = $subject;
        $mail->Body        = $body;
        $mail->send();

    }

}
