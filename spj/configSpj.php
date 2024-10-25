<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/andhikaportal/config.php");

$pathSpj = $_SERVER["DOCUMENT_ROOT"]."/andhikaportal/spj";

require_once($pathSpj.'/class/CSpj.php'); //Class SPJ;
require_once($pathSpj.'/class/CNotifSpj.php');
require_once($path.'/paymentAdvance/configPaymentAdvance.php');
// require_once($path.'/paymentAdvance/class/CPaymentAdv.php');

$hostSpj = $host;
$userSpj = $user;
$passSpj = $pass;
$dbSpj = 'spj';
$tplDir = "templates/";

$DsnHrsys = 'dsHRsys';

error_reporting (E_ALL ^ E_NOTICE);

$CKoneksiSpj = new mysqlResult($hostSpj, $userSpj, $passSpj, $dbSpj);
$CKoneksiSpj->bukaKoneksi();

$koneksiOdbcSpj = new odbcResult($DsnHrsys,"sa","4ndh1k4");
// $koneksiOdbcSpj->bukaKoneksi();
$koneksiOdbcIdSpj = $koneksiOdbcSpj->bukaKoneksi();
// $koneksiOdbcId2Spj = $koneksiOdbcSpj->bukaKoneksi();

$CSpj = new CSpj($koneksiOdbcIdSpj, $koneksiOdbcSpj, $CKoneksiSpj);
$CNotifSpj = new CNotifSpj($CKoneksiSpj);

if($userIdSession != "")
{
	$userJenisSpj = $CSpj->userJenis($userIdSession);
	$ceoId = $CSpj->userJenisByJenis("CEO", "userid");
	$ceoEmpNo = $CSpj->userJenisByJenis("CEO", "userempno");
}
?>