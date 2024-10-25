<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/andhikaPortal/config.php");

$pathInvReg = $_SERVER["DOCUMENT_ROOT"]."/andhikaPortal/invoiceRegister";

require_once($pathInvReg.'/class/CInvReg.php');
require_once($pathInvReg.'/class/CLaporanCari.php');
require_once($pathInvReg.'/class/CLaporanPrint.php');
require_once($pathInvReg.'/class/CLaporanPayment.php');
require_once($pathInvReg.'/class/CNumberWordsEng.php');
require_once($pathInvReg.'/class/CNumberWordsInd.php');
require_once($pathInvReg.'/class/CLaporanAging.php');
require_once($pathInvReg.'/class/CAging.php');
//require_once($pathInvReg.'/class/Convert.php');
$hostInvReg = $host;
$userInvReg = $user;
$passInvReg = $pass;
$dbInvReg = 'invoiceregister';
$tplDir = "templates/";

$CKoneksiVoucher = new mysqlResult($hostInvReg, $userInvReg, $passInvReg, "voucher");
$CKoneksiPaymentVoucher = new mysqlResult($hostInvReg, $userInvReg, $passInvReg, "paymentadvance");

$CKoneksiInvReg1 = new mysqlResult($hostInvReg, $userInvReg, $passInvReg, $dbInvReg);
$CKoneksiInvReg1->bukaKoneksi();
$CInvReg1 = new CInvReg($CKoneksiInvReg1, $koneksiOdbcAcc, $koneksiOdbcAccId, $CPublic);
$usrDbComp = $CInvReg1->detilTblUserjns($userIdSession, "userdbcmp");

if($usrDbComp == "IBP")
{
	$DsnAccounting = 'dbaccountingibp';
}else{
	$DsnAccounting = 'sqlaccjkt';
}
// $DsnAccounting = 'sqlaccjkt';
$koneksiOdbcAcc = new odbcResult($DsnAccounting,"sa","4ndh1k4");
$koneksiOdbcAcc->bukaKoneksi();
$koneksiOdbcAccId = $koneksiOdbcAcc->bukaKoneksi();

$DsnBridge = 'bridge';
$koneksiOdbcBridge = new odbcResult($DsnBridge,"sa","4ndh1k4");
$koneksiOdbcBridge->bukaKoneksi();
$koneksiOdbcBridgeId = $koneksiOdbcBridge->bukaKoneksi();

error_reporting (E_ALL ^ E_NOTICE);

//$tabelPendingJE = "pendingje2011";

$CKoneksiInvReg = new mysqlResult($hostInvReg, $userInvReg, $passInvReg, $dbInvReg);
$CKoneksiInvReg->bukaKoneksi();

$CInvReg = new CInvReg($CKoneksiInvReg, $koneksiOdbcAcc, $koneksiOdbcAccId, $CPublic);
$CAging = new CAging($CKoneksiInvReg, $CPublic);
$CLapCari = new CLaporanCari($CKoneksiInvReg, $koneksiOdbcAcc, $koneksiOdbcAccId, $CInvReg, $CPublic);
$CLapPrint = new CLaporanPrint($CKoneksiInvReg, $koneksiOdbcAcc, $koneksiOdbcAccId, $CInvReg, $CPublic);
$CLapPayment = new CLaporanPayment($CKoneksiInvReg, $koneksiOdbcAcc, $koneksiOdbcAccId, $CInvReg, $CPublic);
$CLapAging = new CLaporanAging($CKoneksiInvReg, $CAging, $CPublic, $CInvReg);
$CNumberWordsEng = new CNumberWordsEng();

$userJenisInvReg = $CInvReg->detilTblUserjns($userIdSession, "userjenis");
$userCompany = $CInvReg->detilTblUserjns($userIdSession, "usercmp");
$userWhoAct = $CPublic->userWhoAct();
$userWhoActNew = $CPublic->userWhoActNew($userIdLogin);
$userBtnPaymentListPaid = $CInvReg->detilTblUserjns($userIdSession, "btnpaymentlist_paid");

//$idleAfter = 10800;
$idleAfter = 36000;
$tabelJurnal = ""; // NILAI $tabelTes = "tes" DIBERIKAN KETIKA AKAN MENGETES DATA KEDALAM TABEL DATABASE YANG NAMA BELAKANG ADA NAMA "TES" NYA
?>