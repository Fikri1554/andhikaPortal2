<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");

$pathInvReg = $_SERVER["DOCUMENT_ROOT"]."/voucher";

require_once($pathInvReg.'/class/CVoucher.php');
require_once($pathInvReg.'/class/CNumberWordsEng.php');


//require_once($pathInvReg.'/class/CLaporanCari.php');
//require_once($pathInvReg.'/class/CLaporanPrint.php');
//require_once($pathInvReg.'/class/CLaporanPayment.php');
//require_once($pathInvReg.'/class/CNumberWordsEng.php');
//require_once($pathInvReg.'/class/Convert.php');

$DsnAccounting = 'dbaccounting';
$koneksiOdbcAcc = new odbcResult($DsnAccounting,"","");
$koneksiOdbcAcc->bukaKoneksi();
$koneksiOdbcAccId = $koneksiOdbcAcc->bukaKoneksi();

/*
$DsnBridge = 'dbbridge';
$koneksiOdbcBridge = new odbcResult($DsnBridge,"","");
$koneksiOdbcBridge->bukaKoneksi();
$koneksiOdbcBridgeId = $koneksiOdbcBridge->bukaKoneksi();*/

$hostVoucher = $host;
$userVoucher = $user;
$passVoucher = $pass;
$dbVoucher = 'voucher';
$tplDir = "templates/";

error_reporting (E_ALL ^ E_NOTICE);

//$tabelPendingJE = "pendingje2011";

$CKoneksiVoucher = new mysqlResult($hostVoucher, $userVoucher, $passVoucher, $dbVoucher);
$CKoneksiVoucher->bukaKoneksi();
$CVoucher = new CVoucher($CKoneksiVoucher, $koneksiOdbcAcc, $koneksiOdbcAccId, $CPublic);
$CNumberWordsEng = new CNumberWordsEng();

$userJenisVoucher = $CVoucher->detilTblUserjns($userIdSession, "userjenis");
$userWhoAct = $CPublic->userWhoAct();
$userWhoActNew = $CPublic->userWhoActNew($userIdLogin);

//$idleAfter = 10800;

$limitVoucher = 50;  // LIMIT ROW PADA IFRAME VOUCHER LIST

$idleAfter = 36000;
?>