<?php
require_once("configInvReg.php");

if($userIdSession == "")
{
	header("location:../archives/");
	exit;
}

if($userIdSession != "")
{
	$tpl = new myTemplate("templates/halUtama.html");
	if($userJenisInvReg == "")
	{
		$tpl->AssignInclude("CONTENT_TENGAH","templates/halContactAdmin.html");
	}
	if($userJenisInvReg != "")
	{
		$tpl->AssignInclude("CONTENT_TENGAH","templates/halWelcome.html");
		if($halamanPost == "halIncoming")
		{
			$tpl->AssignInclude("CONTENT_TENGAH","templates/halIncoming.html");
		}
		if($halamanPost == "halProcessAck")
		{
			$tpl->AssignInclude("CONTENT_TENGAH","templates/halProcessAck.html");
		}
		if($halamanPost == "halProcessRet")
		{
			$tpl->AssignInclude("CONTENT_TENGAH","templates/halProcessRet.html");
		}
		if($halamanPost == "halPaymentOutstanding")
		{
			$tpl->AssignInclude("CONTENT_TENGAH","templates/halPaymentOutstanding.html");
		}
		if($halamanPost == "halPaymentPrepare")
		{
			$tpl->AssignInclude("CONTENT_TENGAH","templates/halPaymentPrepare.html");
		}
		if($halamanPost == "halPaymentBatch")
		{
			$tpl->AssignInclude("CONTENT_TENGAH","templates/halPaymentBatch.html");
		}
		if($halamanPost == "halOutgoing")
		{
			$tpl->AssignInclude("CONTENT_TENGAH","templates/halOutgoing.html");
		}
		if($halamanPost == "halPrintDistribution")
		{
			$tpl->AssignInclude("CONTENT_TENGAH","templates/halPrint.html");
		}
		if($halamanPost == "halPrintMailInv")
		{
			$tpl->AssignInclude("CONTENT_TENGAH","templates/halPrintMailInv.html");
		}
		if($halamanPost == "halAgingRpt")
		{
			$tpl->AssignInclude("CONTENT_TENGAH","templates/halAgingRpt.html");
		}
		if($halamanPost == "halCari")
		{
			$tpl->AssignInclude("CONTENT_TENGAH","templates/halCari.html");
		}
	}
}
if($userJenis != "admin")
{
	//$tpl->AssignInclude("CONTENT_TENGAH","templates/halContactAdmin.html");
}

$tpl->prepare();

$tpl->Assign("userJenis", $userJenis);
$tpl->Assign("userJenisInvReg", $userJenisInvReg);
$tpl->Assign("idleAfter", $idleAfter);
if($userIdLogin != "")
{
	$tpl->Assign("welcomeUsername", $welcomeUsername );
	$tpl->Assign("spanLogout", "<span class=\"spanLogout\" onClick=\"formLogout.submit();\" title=\"Logout from Andhika Portal\">(&nbsp;Logout&nbsp;)</span>");
	$tpl->Assign("menuHome", $CPublic->menuHome(""));
	$tpl->Assign("menuNews", $CPublic->menuNews(""));
	$tpl->Assign("menuEmployee", $CPublic->menuEmployee(""));
	$tpl->Assign("menuArchives", $CPublic->menuArchives(""));
}

if($userJenis == "admin")
{
	$tpl->Assign("menuSetting", $CPublic->menuSetting(""));
	$menuApplication = "<li class=\"has-sub\"><a href=\"#\"><span>Application</span></a>
							<ul>
								".$COtherApp->menuOtherApp($userIdLogin)."
							</ul>
						</li>";
	$tpl->Assign("menuApplication", $menuApplication);
}
else if($userJenis == "user")
{
	if($userMenuSetting == "Y")
	{
		$tpl->Assign("menuSetting", $CPublic->menuSetting(""));
	}
	if($userMenuApplication == "Y")
	{
		$menuApplication = "<li class=\"has-sub\"><a href=\"#\"><span class=\"on\">Application</span></a>
								<ul>
									".$COtherApp->menuOtherApp($userIdLogin)."
								</ul>
							</li>";
		$tpl->Assign("menuApplication", $menuApplication);
	}
}
//echo $userJenisInvReg;
if($userJenisInvReg == "user")
{
	$tpl->Assign("disBtnInvProcess", $CInvReg->aksesBtn($userIdSession, "", "btninvprocess", "btnInvProcess"));
	$tpl->Assign("disBtnPayment", $CInvReg->aksesBtn($userIdSession, "", "btnpayment", "btnPayment"));
	$tpl->Assign("disBtnOutgoing", $CInvReg->aksesBtn($userIdSession, "", "btnoutgoing", "btnOutgoing"));
	$tpl->Assign("disBtnPrint", $CInvReg->aksesBtn($userIdSession, "", "btnprint", "btnPrint"));
	$tpl->Assign("disBtnPrintMailInv", $CInvReg->aksesBtn($userIdSession, "", "btnprintmailinv", "btnPrintMailInv"));
	$tpl->Assign("disBtnPrintAging", $CInvReg->aksesBtn($userIdSession, "", "btnprintaging", "btnPrintAging"));
	
	//INCOMING
	$tpl->Assign("disBtnIncoming", $CInvReg->aksesBtn($userIdSession, "", "btnincoming", "btnIncoming"));
	$tpl->Assign("disBtnIncoming_New", $CInvReg->aksesBtn($userIdSession, "", "btnincoming_new", "btnIncomingNew"));
	$tpl->Assign("disBtnIncoming_Edit", $CInvReg->aksesBtn($userIdSession, "", "btnincoming_edit", "btnIncomingEdit"));
	$tpl->Assign("disBtnIncoming_Delete", $CInvReg->aksesBtn($userIdSession, "", "btnincoming_delete", "btnIncomingDelete"));
	$tpl->Assign("disBtnIncoming_Search", $CInvReg->aksesBtn($userIdSession, "", "btnincoming_search", "btnIncomingSearch"));
	
	//ACKNOWLEDGE
	$tpl->Assign("disBtnAckDetail", $CInvReg->aksesBtn($userIdSession, "", "btninvprocess_detailack", "btnAckDetail"));
	$tpl->Assign("disBtnRetDetail", $CInvReg->aksesBtn($userIdSession, "", "btninvprocess_detailret", "btnRetDetail"));
	
	$tpl->Assign("disBtnPayPreppay", $CInvReg->aksesBtn($userIdSession, "", "btnpayment_preppay", "btnPayPreppay"));
	$tpl->Assign("disBtnPayBybatch", $CInvReg->aksesBtn($userIdSession, "", "btnpayment_bybatch", "btnPayBybatch"));
	
	$tpl->Assign("disBtnPrepPayAssign", $CInvReg->aksesBtn($userIdSession, "", "btnpayment_assignprep", "btnPayPrepAssign"));
	$tpl->Assign("disBtnPrepPayResetGrup", $CInvReg->aksesBtn($userIdSession, "", "btnpayment_resetgroupprep", "btnPayPrepResetGroup"));
	
	// PAYMENT
	if($halamanPost == "halPaymentOutstanding")
	{
		// PAYMENT OUTSTANDING
		$tpl->Assign("disBtnPayOutstanding", $CInvReg->aksesBtn($userIdSession, "", "btnpayment_outstanding", "btnPayOutstanding"));
		$tpl->Assign("disBtnRefresh", $CInvReg->aksesBtn($userIdSession, "", "btnpayment_outstanding", "btnRefresh"));
		$tpl->Assign("disBtnRetrieve", $CInvReg->aksesBtn($userIdSession, "", "btnpayment_outstanding", "btnRetrieve"));
		$tpl->Assign("disSortBy", $CInvReg->aksesElement($userIdSession, "", "btnpayment_outstanding", "sortBy"));
		$tpl->Assign("disAscBy", $CInvReg->aksesElement($userIdSession, "", "btnpayment_outstanding", "ascBy"));
	}
	
	if($halamanPost == "halPaymentBatch")
	{
		$tpl->Assign("aksesBtnPayTransAcct",  $CInvReg->detilTblUserjns($userIdSession, "btnpayment_transacc") );
		$tpl->Assign("aksesBtnPayPrintVoucher",  $CInvReg->detilTblUserjns($userIdSession, "btnpayment_printvoucher") );
		$tpl->Assign("aksesBtnCancelled",  $CInvReg->detilTblUserjns($userIdSession, "btnpayment_cancelled") );
	}

	//$tpl->Assign("disBtnBatchRetBatch", $CInvReg->aksesBtn($userIdSession, "", "btnpayment_retbatch", "btnRetBatch"));
	//$tpl->Assign("disBtnPayTransAcct", $CInvReg->aksesBtn($userIdSession, "", "btnpayment_transacc", "btnPayTransAcct"));
	//$tpl->Assign("disBtnPayPrintVoucher", $CInvReg->aksesBtn($userIdSession, "", "btnpayment_printvoucher", "btnPayPrintVoucher"));
}

if($aksiGet == "" || $aksiGet == "incoming")
{
	$thnBlnPilihGet = $_GET['thnBlnPilih'];
	$tglPilihGet = $_GET['tglPilih'];
	if($_GET['tglPilih'] == "")
		$tglPilihGet = $CInvReg->nilaiBatchnoTglAwal( $CInvReg->nilaiBatchnoThnBlnAwal());
	
	$tpl->Assign("menuBatchnoThnBln", $CInvReg->menuBatchnoThnBln($thnBlnPilihGet));
	$tpl->Assign("tglPilih", $tglPilihGet);
	//$tpl->Assign("menuBatchnoTgl", $CInvReg->menuBatchnoTgl( $CInvReg->nilaiBatchnoThnBlnAwal(), $tglPilihGet ));
	
	//$thnBlnPilih = $CInvReg->nilaiBatchnoThnBlnAwal();
	//$tglPilih = $CInvReg->nilaiBatchnoTglAwal( $CInvReg->nilaiBatchnoThnBlnAwal() );
	$batchno = $thnBlnPilihGet.$tglPilihGet;
	if($thnBlnPilihGet.$tglPilihGet == "")
		$batchno = $CInvReg->nilaiBatchnoThnBlnAwal().$CInvReg->nilaiBatchnoTglAwal( $CInvReg->nilaiBatchnoThnBlnAwal());
	//$tpl->Assign("batchno", $batchno);
	
	$tpl->Assign("teksMap", "<a>INCOMING</a>");
}

if($halamanPost == "halProcessAck")
{
	$thnBlnPilihGet = $_GET['thnBlnPilih'];
	$tglPilihGet = $_GET['tglPilih'];
	
	$tpl->Assign("menuBatchnoThnBln", $CInvReg->menuBatchnoThnBln($thnBlnPilihGet));
	$tpl->Assign("tglPilih", $tglPilihGet);
	
	$tpl->Assign("teksMap", "<a>INVOICE PROCESS > ACKNOWLEDGE</a>");
}
if($halamanPost == "halProcessRet")
{
	$thnBlnPilihGet = $_GET['thnBlnPilih'];
	$tglPilihGet = $_GET['tglPilih'];
	
	$tpl->Assign("menuBatchnoThnBln", $CInvReg->menuBatchnoThnBln($thnBlnPilihGet));
	$tpl->Assign("tglPilih", $tglPilihGet);
	
	$tpl->Assign("teksMap", "<a>INVOICE PROCESS > TRANSFER TO ACCT JE</a>");
}
if($halamanPost == "halPaymentOutstanding")
{
	$tpl->Assign("teksMap", "<a>PAYMENT > OUTSTANDING INVOICE</a>");
	$tpl->Assign("menuCmp", $CInvReg->menuCmp('') );
}
if($halamanPost == "halPaymentPrepare")
{	
	$prepareByPost = $_POST['prepareByHidden'];
	if($prepareByPost == "invoice")
		$tpl->Assign("selInv", "selected");
	if($prepareByPost == "barcode")
		$tpl->Assign("selBar", "selected");
	
	$tpl->Assign("teksMap", "<a>PAYMENT > PREPARE FOR PAYMENT</a>");
	$tpl->Assign("lastTransNo", $CPublic->zerofill($CInvReg->lastTransNo(), 6));
	//$tpl->Assign("nextTransNo", ($CInvReg->lastTransNo()+1));
	
	$tpl->Assign("statusData", "<span style=\"color:#900;\">NOT READY FOR ASSIGN</span>");
	
	$query = $CKoneksiInvReg->mysqlQuery("SELECT idmailinv FROM tblpreppay WHERE deletests=0 ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
	$jmlRow = $CKoneksiInvReg->mysqlNRows($query);
	if($jmlRow > 0) // JIKA TBLPREPPAY DI DATABASE TIDAK KOSONG ATAU SUDAH ADA GRUOP PAYMENT YANG DI PERSIAPKAN UNTUK PAYMENT
	{
		$tpl->Assign("statusData", "<span style=\"color:#096;\">READY FOR ASSIGN</span>");
	}
}
if($halamanPost == "halPaymentBatch")
{
	//$tpl->Assign("aksesBtnPayTransAcct",  $CInvReg->detilTblUserjns($userIdSession, "btnpayment_transacc") );
	//$tpl->Assign("aksesBtnPayPrintVoucher",  $CInvReg->detilTblUserjns($userIdSession, "btnpayment_printvoucher") );
	
	$tpl->Assign("teksMap", "<a>PAYMENT > PAYMENT BY BATCH</a>");
	$tpl->Assign("bankCodeFirst", $CInvReg->bankCodeMenuFirst() );
	$tpl->Assign("bankCodeMenu", $CInvReg->bankCodeMenu(""));
	$tpl->Assign("datePaid", $CPublic->convTglNonDB( $CPublic->waktuSek() ));
	$tpl->Assign("menuCurrency", $CInvReg->menuCurrency(""));
	$tpl->Assign("menuCmp", $CInvReg->menuCmp('') );
}
if($halamanPost == "halPrintDistribution")
{
	$tpl->Assign("teksMap", "<a>PRINT DISTRIBUTION LIST</a>");
}

if($halamanPost == "halOutgoing")
{
	$tglPilihGet = $_GET['tglPilih'];	
	if($_GET['tglPilih'] == "")
	{
		$tglPilihGet = $CInvReg->nilaiBatchnoTglAwalOutgoing( $CInvReg->nilaiBatchnoThnBlnAwalOutgoing() );
	}

	$tpl->Assign("menuBatchnoThnBlnOutgoing", $CInvReg->menuBatchnoThnBlnOutgoing($thnBlnPilihGet));
	$tpl->Assign("tglPilih", $tglPilihGet);
	$tpl->Assign("teksMap", "<a>OUTGOING</a>");
}
			
if($halamanPost == "halPrintMailInv")
{
	$tpl->Assign("teksMap", "<a>PRINT > MAIL / INVOICE DATA</a>");
}

if($halamanPost == "halAgingRpt")
{
	$tpl->Assign("menuCompany",$CInvReg->menuCmp(''));
	$tpl->Assign("userId",$userIdLogin);
	
	$tpl->Assign("teksMap", "<a>PRINT > AGING REPORT</a>");
}
if($halamanPost == "halCari")
{
	$tpl->Assign("teksMap", "<a>SEARCH</a>");
}

$tpl->printToScreen();
?>