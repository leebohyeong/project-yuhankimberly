<?php
/** ==============================================================================
 * File: 관리자 > 로그인 처리 (excel.php)
 * Date: 2023-05-24 오후 3:25
 * Created by @krabbit2.DevGroup
 * Copyright 2023, xn-939awia823kba64a723b9ulkh4aca.com(Group IDD). All rights are reserved
 * ==============================================================================*/
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use \Groupidd\Model\ModelBase;
use \Groupidd\Common\CommonFunc;
use \Groupidd\Library\Validator;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;


$db = new ModelBase();
$db->from("REACTION");
$db->select("seq, score, category, content, username, userphone, reg_date", true);
$db->orderby('seq', 'desc');
$result = $db->getAll();
$listCnt        = $db->getCountAll();

$date = new DateTime();
$filename = 'reaction'.$date->format('Ymd').'.xls';

// 엑셀 문서 생성
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// 행번호 초기화
$column = 'A';
//$headers = ['No', '별점', '주제', '한줄평', '이름', '휴대폰', '등록일'];
$headers = ['No', '1', '2', '3', '4', '5', '6'];
foreach ($headers as $header) {
    $sheet->setCellValue($column++ . '1', $header);
}
$column = 'A';      // 행번호 초기화
$rowNum = 2;        // 열번호 초기화

if($result) {
    foreach ($result as $idx => $row) {
        $sheet->setCellValue($column++ . $rowNum, $listCnt-$idx);
        $sheet->setCellValue($column++ . $rowNum, $row['score']);
        $sheet->setCellValue($column++ . $rowNum, $row['category']);
        $sheet->setCellValue($column++ . $rowNum, $row['content']);
        $sheet->setCellValue($column++ . $rowNum, CommonFunc::stringDecrypt($row['username'], $ENCRYPT_KEY_));
        $sheet->setCellValue($column++ . $rowNum, CommonFunc::stringDecrypt($row['userphone'], $ENCRYPT_KEY_));
        $sheet->setCellValue($column++ . $rowNum, $row['reg_date']);
        $column = 'A';
    }
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');


