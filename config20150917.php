<?php
ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT']."/tmpManual");
session_start();

//session_destroy();
//$path = dirname("public_html");
$path = $_SERVER["DOCUMENT_ROOT"];//untuk virtual host
global $path;

$pathArchives = $path."/archives/";

require_once($path.'/class/CKoneksi.php');
require_once($path.'/class/CLogin.php');
require_once($path.'/class/CFolder.php');
require_once($path.'/class/CFile.php');
require_once($path.'/class/CPublic.php');
require_once($path.'/class/CEmployee.php');
require_once($path.'/class/CHistory.php');
require_once($path.'/class/CDailyAct.php');
require_once($path.'/class/COtherApp.php');
require_once($path.'/class/CQhse.php');
require_once($path.'/class/CSurvey.php');
require_once($path.'/class/CEmpl.php');
require_once($path.'/class/CNotif.php');
require_once($path.'/class/COtherReport.php');
require_once($path.'/class/thumbnail.class.php');
require_once($path.'/class/template.php');

error_reporting (E_ALL ^ E_NOTICE);

$host = 'localhost';
$user = 'root';
$pass = '4ndh1k4';
$db = 'andhikaportal';
$tplDir = "templates/";

$DsnHrsys = 'dbhrsys';

$userIdSession = $_SESSION['userIdSession'];

$CKoneksi = new mysqlResult($host, $user, $pass, $db);
$CKoneksi->bukaKoneksi();

$koneksiOdbc = new odbcResult($DsnHrsys,"","");
$koneksiOdbc->bukaKoneksi();
$koneksiOdbcId = $koneksiOdbc->bukaKoneksi();
$koneksiOdbcId2 = $koneksiOdbc->bukaKoneksi();

$CLogin = new CLogin($CKoneksi);
$CFolder = new CFolder($CKoneksi);
$CFile = new CFile($CKoneksi);
$CEmployee = new CEmployee($koneksiOdbcId, $koneksiOdbc, $CKoneksi);
$CDailyAct = new CDailyAct($CKoneksi);
$CQhse = new CQhse($CKoneksi);
$CSurvey = new CSurvey($CKoneksi);
$CEmpl = new CEmpl($CKoneksi);
$CNotif = new CNotif($CKoneksi);

$userIdLogin = $userIdSession;
$userName = $CLogin->detilLogin($userIdSession, "username");
$userInit = $CLogin->detilLogin($userIdSession, "userinit");
$userJenis = $CLogin->detilLogin($userIdSession, "userjenis");
$userFullnm = $CLogin->detilLogin($userIdSession, "userfullnm");
$userEmpNo = $CLogin->detilLogin($userIdLogin, "empno");
$userQhse = $CLogin->detilLogin($userIdLogin, "adminqhse");
$adminEmpl = $CLogin->detilLogin($userIdLogin, "adminempl");
$welcomeUsername = "Welcome, ".ucfirst(strtolower($userFullnm));

$userMenuSetting = $CLogin->detilLogin($userIdLogin, "menusetting");
$userMenuApplication = $CLogin->detilLogin($userIdLogin, "menuapplication");
$userBtnUser = $CLogin->detilLogin($userIdLogin, "btnUser");
$userLogHistory = $CLogin->detilLogin($userIdLogin, "loghistory");
$userOtherApp = $CLogin->detilLogin($userIdLogin, "otherapp");
$userSubCustom = $CLogin->detilLogin($userIdLogin, "subcustom");
$userKpiSetting = $CLogin->detilLogin($userIdLogin, "kpisetting");
$userResultSurvey = $CLogin->detilLogin($userIdLogin, "resultsurvey");
$userConvFold = $CLogin->detilLogin($userIdLogin, "convfold");

$CPublic = new CPublic($CKoneksi, $userInit);
$CHistory = new CHistory($CKoneksi, $CPublic);
$COtherApp = new COtherApp($CKoneksi, $CPublic, $db);
$COtherReport = new COtherReport($CKoneksi);

$aksiPost = $_POST['aksi'];
$aksiGet = $_GET['aksi'];
$dologinGet = $_GET['dologin'];
$halamanGet = $_GET['halaman'];
$halamanPost = $_POST['halaman'];
$idePost = $_POST['ide'];
$ideGet = $_GET['ide'];

$tglStartPrevAct = "31/03/2014"; // tanggal mulai start untuk memilih previous activity (database : 2014-03-31)
$idleAfter = 1800;

if($aksiGet == "noRespon")
{
	$CLogin->sessionExpired($CPublic, $CHistory);
}

$link = "http://andhika.portal.com";
/*if($userIdLogin != "")
{
	//$sessionExpired = "false";
	$idletime= 30 * 60; //after 1800 seconds / 30 menit the user gets logged out
	//$idletime= 10;
	//$statusExpPage = "NO";
	if (time() - $_SESSION['timestamp'] > $idletime)
	{	
		//$sessionExpired = "true";
		$CLogin->sessionExpired($CPublic, $CHistory);
		
		//header("Location: http://andhika.portal.com/archives/index.php?aksi=sessionExpired");
		//$statusExpPage = "YES";
	}
	else
	{	$_SESSION['timestamp']=time();	}
	//on session creation
	$_SESSION['timestamp']=time();
}*/
?>