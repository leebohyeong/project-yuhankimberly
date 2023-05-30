<?php
/** ==============================================================================
 * File: 관리자 > 로그인 처리 (excel.php)
 * Date: 2023-05-24 오후 3:25
 * Created by @krabbit2.DevGroup
 * Copyright 2023, xn-939awia823kba64a723b9ulkh4aca.com(Group IDD). All rights are reserved
 * ==============================================================================*/
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use Groupidd\Common\CommonFunc;
use Groupidd\Library\Validator;
use Groupidd\Model\ModelBase;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//require $_SERVER['DOCUMENT_ROOT'].'/src/Groupidd/Library/PHPMailer/Exception.php';
//require $_SERVER['DOCUMENT_ROOT'].'/src/Groupidd/Library/PHPMailer/PHPMailer.php';
//require $_SERVER['DOCUMENT_ROOT'].'/src/Groupidd/Library/PHPMailer/SMTP.php';

$jsonResult['result'] = true;
$date = new DateTime();
$name = 'reaction_' . $date->format('ymd') . '.xls';

//header("Content-type: application/vnd.ms-excel");
//header("Content-type: application/vnd.ms-excel; charset=utf-8");
//header("Content-Disposition: attachment; filename ={$name}");
//header("Content-Description: PHP4 Generated Data");

$db = new ModelBase();
$db->from("REACTION");
$db->select("seq, score, category, content, username, userphone, reg_date", true);
$db->orderby('seq', 'desc');
$result = $db->getAll();
$listCnt        = $db->getCountAll();

ob_start();
?>

    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content=\"application/vnd.ms-excel; charset=UTF-8\" name=\"Content-type\">
        <title></title>
    </head>
    <body>
    <h3>유한킴벌리 리액션이벤트 참여자</h3>
    <table border="1">
        <thead>
        <tr>
            <th scope="col">
                번호
            </th>
            <th scope="col">
                이름
            </th>
            <th scope="col">
                연락처
            </th>
            <th scope="col">
                별점
            </th>
            <th scope="col">
                선택
            </th>
            <th scope="col">
                한줄평
            </th>
<!--            <th scope="col">-->
<!--                14세 이상 동의-->
<!--            </th>-->
<!--            <th scope="col">-->
<!--                개인정보수집동의-->
<!--            </th>-->
            <th scope="col">
                등록일
            </th>
        </tr>
        </thead>
        <?php
        if (!empty($result)) {
            $count = count($result);
            foreach ($result as $key => $value) {
                ?>
                <tr>
                    <td style="mso-number-format:\@;"><?= $count-- ?></td>
                    <td style="mso-number-format:\@;"><?//= CommonFunc::stringDecrypt($value['name'], $ENCRYPT_KEY_) ?>
                        <?php
                        $name = CommonFunc::stringDecrypt($value['username'], $ENCRYPT_KEY_);
                        //                                            $name   = $value['name'];
                        if (!empty($name)) {
                            ////--$name = CommonFunc::letterMasking('N', $name);
                            //$name = mb_substr($name,0,1,'utf-8').'*'.mb_substr($name,2,50,'utf-8');
                        }
                        echo $name;
                        ?>
                    </td>
                    <td>
                        <?php
                        $phone = CommonFunc::stringDecrypt($value['userphone'], $ENCRYPT_KEY_);
                        if (!empty($phone) && mb_strlen($phone, 'utf-8') >= 10) {
                            //$phone = CommonFunc::letterMasking('P', $phone);
                        }
                        echo $phone;
                        ?>
                    </td>
                    <td><?=$value['score']?></td>
                    <td><?= $value['category'] ?></td>
                    <td><?= $value['content'] ?></td>
                    <td><?= $value['reg_date'] ?></td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
    </body>
    </html>
<?php
$contents = ob_get_contents();
ob_end_clean();


$excelPath = $ROOT_PATH_.'/sitemanager/board/faq/reaction_'.date('Ymd', time()).'.xls';

$fp = fopen($excelPath, 'w+');
fwrite($fp, $contents);
fclose($fp);






//echo send_email_google("aj0228@groupidd.com", "안은주", "[유한킴벌리] ".date('Y-m-d', time())." 참여자 데이터", date('Y-m-d', time())."기준 참여자 데이터입니다.", $excelPath);

echo send_email_google("krabbit2@groupidd.com", "조기현", "[유한킴벌리] ".date('Y-m-d', time())." 참여자 데이터", date('Y-m-d', time())."기준 참여자 데이터입니다.", $excelPath);


function send_email_google ($user_email, $user_name, $subject, $content, $excelPath) {
    $USER		='krabbit2@groupidd.com'; //보내는 사람 이메일
    $PASSWORD = 'zhlabblyintlbwvh'; //비밀번호
    $SEND_EMAIL = 'krabbit2@groupidd.com';

    $mail = new PHPMailer(true);

    //Enable SMTP debugging.
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER; // debug 표시 레벨(디버깅 원할 시)
    //Set PHPMailer to use SMTP.
    $mail->CharSet = PHPMailer::CHARSET_UTF8;
    $mail->isSMTP();
    //Set SMTP host name
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;;
    //Set TCP port to connect to
    $mail->Port = 465;

    //Set this to true if SMTP host requires authentication to send email
    $mail->SMTPAuth = true;
    //Provide username and password
    $mail->Username = $USER;
    $mail->Password = $PASSWORD;
    $mail->SMTPKeepAlive = true;
    $mail->CharSet = "utf-8"; //이거 설정해야 한글 안깨짐h
    //If SMTP requires TLS encryption then set it

    $mail->setFrom($SEND_EMAIL,"조기현");
    // 이름은 적용이 되는데 메일 적용이 안된다 구글에서 막아놓음. (보내지긴 하는데 user mail로 수정됨)
    // 보내는 사람 메일을 바꾸려면 User메일의 별칭 메일에 send mail을 추가해야 함. (master@mydomain.io가 실제 메일을 받을 수 있어야함)
    $mail->addAddress($user_email, $user_name); //받는 사람
    $mail->addAddress("choeun@groupidd.com", "조은"); //받는 사람
    $mail->addAddress("krabbit2@groupidd.com", "조기현"); //받는 사람
    //$mail->addCC('mojito@groupidd.com');				// 참조
    $mail->isHTML(true);

    //$rand_num = sprintf("%06d",rand(000000,999999));

    $mail->Subject = $subject;
    $mail->Body = $content;
    $mail->addAttachment($excelPath);
    $mail->AltBody = "";

    try {
        $mail->send();
        return "send success";
    } catch (Exception $e) {
        $rand_num = -1;


        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return "error";

    }

}