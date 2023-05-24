<?php
/** ==============================================================================
 * File: 관리자 > 로그아웃 처리 (logout.php)
 * Date: 2018-07-27 오후 2:34
 * Created by @Mojito.DevGroup
 * Copyright 2018, travelweek.visitkorea.or.kr(Group IDD). All rights are reserved
 * ==============================================================================*/
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

session_unset();
Header("Location:/sitemanager/login.php");
exit();