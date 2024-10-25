<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/andhikaPortal/config.php");

$pathInvReg = $_SERVER["DOCUMENT_ROOT"]."/andhikaPortal/paymentAdvance";

require_once($pathInvReg.'/class/CPaymentAdv.php');

$dbNya = 'paymentadvance';
$tplDir = "templates/";
$dsnAccounting = "dbaccounting";

$koneksiOdbcAcc = new odbcResult($dsnAccounting,"","");
$koneksiOdbcAcc->bukaKoneksi();

$CkoneksiPaymentAdv = new mysqlResult($host, $user, $pass, $dbNya);
$CkoneksiPaymentAdv->bukaKoneksi();
$koneksiOdbcAccId = $CkoneksiPaymentAdv->bukaKoneksi();

$CPaymentAdv = new CPaymentAdv($CkoneksiPaymentAdv, $koneksiOdbcAcc, $koneksiOdbcAccId, $CPublic);

$userJenisPaymentAdv = $CPaymentAdv->detilTblUserjns($userIdSession, "userjenis");

error_reporting (E_ALL ^ E_NOTICE);


?>