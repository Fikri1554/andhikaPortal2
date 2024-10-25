<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");

$path = $_SERVER["DOCUMENT_ROOT"]."/safir";

require_once($path.'/class/CData.php');

$hostSaf = $host;
$userSaf = $user;
$passSaf = $pass;
$dbSaf = 'safirho';
$tplDir = "templates/";

error_reporting (E_ALL ^ E_NOTICE);

$CKoneksiSaf = new mysqlResult($hostSaf, $userSaf, $passSaf, $dbSaf);
$CKoneksiSaf->bukaKoneksi();

$CData = new CData($CKoneksiSaf);
$userJenisSafir = $CData->detilTblUserjns($userIdSession, "userjenis");
?>