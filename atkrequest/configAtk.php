<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/andhikaPortal/config.php");

$path = $_SERVER["DOCUMENT_ROOT"]."/andhikaPortal/atkrequest";

require_once($_SERVER["DOCUMENT_ROOT"].'/andhikaPortal/class/CReqAtk.php');

$hostAtk = $host;
$userAtk = $user;
$passAtk = $pass;
$dbAtk = 'atkrequest';
$tplDir = "templates/";

error_reporting (E_ALL ^ E_NOTICE);

$CKoneksiAtk = new mysqlResult($hostAtk, $userAtk, $passAtk, $dbAtk);
$CKoneksiAtk->bukaKoneksi();

$CReqAtk = new CReqAtk($CKoneksiAtk);
$adminAtk = $CReqAtk->userJenis($userIdLogin);
?>