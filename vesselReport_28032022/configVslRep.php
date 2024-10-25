<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/andhikaportal/config.php");

$pathVslRep = $_SERVER["DOCUMENT_ROOT"]."/andhikaportal/vesselReport";

require_once($pathVslRep.'/class/CVslRep.php');

$hostVslRep = $host;
$userVslRep = $user;
$passVslRep = $pass;
$dbVslRep = 'vslrep';
$tplDir = "templates/";

error_reporting (E_ALL ^ E_NOTICE);

//$tabelPendingJE = "pendingje2011";

$CKoneksiVslRep = new mysqlResult($hostVslRep, $userVslRep, $passVslRep, $dbVslRep);
$CKoneksiVslRep->bukaKoneksi();
$CVslRep = new CVslRep($CKoneksiVslRep, $CPublic);

$userJenisVslRep = $CVslRep->detilTblUserjns($userIdSession, "userjenis");
$userWhoAct = $CPublic->userWhoAct();
$userWhoActNew = $CPublic->userWhoActNew($userIdLogin);

$conODBCNya = new odbcResult("NewASM","","");
$conODBCASMnya = $conODBCNya->bukaKoneksi();
//$idleAfter = 10800;

$limitVslRep = 50;  // LIMIT ROW PADA IFRAME VOUCHER LIST

$idleAfter = 36000;
?>