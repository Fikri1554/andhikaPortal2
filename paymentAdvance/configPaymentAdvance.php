<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/andhikaPortal/config.php");

$pathPayAdv = $_SERVER["DOCUMENT_ROOT"]."/andhikaPortal/paymentAdvance";

require_once($pathPayAdv.'/class/CPaymentAdv.php');

require_once($pathPayAdv.'/class/CNumberWordsEng.php');
require_once($pathPayAdv.'/class/CNumberWordsInd.php');

$dbNya = 'paymentadvance';
$tplDir = "templates/";

$CkoneksiPaymentAdv = new mysqlResult($host, $user, $pass, $dbNya);
$CkoneksiPaymentAdv->bukaKoneksi();

$dsnAccounting = "sqlaccjkt";
// $dsnAccounting = "dbaccountingTes";

$koneksiOdbcAcc = new odbcResult($dsnAccounting,"sa","4ndh1k4");
$koneksiOdbcAcc->bukaKoneksi();
$koneksiOdbcAccId = $koneksiOdbcAcc->bukaKoneksi();

$CPaymentAdv = new CPaymentAdv($CkoneksiPaymentAdv, $koneksiOdbcAcc, $koneksiOdbcAccId, $CPublic);

$userJenisPaymentAdv = $CPaymentAdv->detilTblUserjns($userIdSession, "userjenis");

error_reporting (E_ALL ^ E_NOTICE);
?>