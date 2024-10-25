<?php
require_once("configPaymentAdvance.php");

if($userIdSession == "")
{
	header("location:../archives/");
	exit;
}

if($userIdSession != "")
{
	$tpl = new myTemplate("templates/halUtama.html");
	if($userJenisPaymentAdv == "")
	{
		$tpl->AssignInclude("CONTENT_TENGAH","templates/halContactAdmin.html");
	}
	if($userJenisPaymentAdv != "")
	{
		$tpl->AssignInclude("CONTENT_TENGAH","templates/halWelcome.html");
		
		if($halamanPost == "halPaymentRequest")
		{
			$tpl->AssignInclude("CONTENT_TENGAH","templates/halPaymentRequest.php");
		}
		if($halamanPost == "halConfirmPayment")
		{
			$tpl->AssignInclude("CONTENT_TENGAH","templates/halConfirmPayment.php");
		}
		if($halamanPost == "halCheckPayment")
		{
			$tpl->AssignInclude("CONTENT_TENGAH","templates/halCheckPayment.php");
		}
		if($halamanPost == "halApprovePayment")
		{
			$tpl->AssignInclude("CONTENT_TENGAH","templates/halApprovePayment.php");
		}
		if($halamanPost == "halReleasePayment")
		{
			$tpl->AssignInclude("CONTENT_TENGAH","templates/halReleasePayment.php");
		}
		if($halamanPost == "halPrepareForPayment")
		{
			$tpl->AssignInclude("CONTENT_TENGAH","templates/halPrepareForPayment.php");
		}
		if($halamanPost == "halVoucher")
		{
			$tpl->AssignInclude("CONTENT_TENGAH","templates/halVoucher.php");
		}
		if($halamanPost == "halSearch")
		{
			$tpl->AssignInclude("CONTENT_TENGAH","templates/halSearch.php");
		}
	}
}
if($userJenis != "admin")
{
	//$tpl->AssignInclude("CONTENT_TENGAH","templates/halContactAdmin.html");
}

$tpl->prepare();

$tpl->Assign("userJenis", $userJenis);
$tpl->Assign("userJenisPaymentAdv", $userJenisPaymentAdv);
$tpl->Assign("idleAfter", $idleAfter);
$tpl->Assign("searchBatchNoThnBln", $CPaymentAdv->getsearchBatchNoThnBln());
$tpl->Assign("searchBatchNoDay", $CPaymentAdv->getsearchBatchNoDay());

$tpl->Assign("disBtnPaymentReq", $CPaymentAdv->aksesBtn($userIdSession, "", "btn_payment_request", "btnPaymentReq"));
$tpl->Assign("disBtnPaymentConfirm", $CPaymentAdv->aksesBtn($userIdSession, "", "btn_payment_confirm", "btnPaymentConfirm"));
$tpl->Assign("disBtnPaymentCheck", $CPaymentAdv->aksesBtn($userIdSession, "", "btn_payment_check", "btnPaymentCheck"));
$tpl->Assign("disBtnPaymentApprove", $CPaymentAdv->aksesBtn($userIdSession, "", "btn_payment_approve", "btnPaymentApprove"));
$tpl->Assign("disBtnPaymentRelease", $CPaymentAdv->aksesBtn($userIdSession, "", "btn_payment_release", "btnPaymentRelease"));
$tpl->Assign("disBtnPaymentPrepare", $CPaymentAdv->aksesBtn($userIdSession, "", "btn_payment_prepare", "btnPaymentPrepareForPayment"));
$tpl->Assign("disBtnPaymentVoucher", $CPaymentAdv->aksesBtn($userIdSession, "", "btn_payment_voucher", "btnPaymentVoucher"));
$tpl->Assign("disBtnChangeFile", $CPaymentAdv->aksesBtn($userIdSession, "", "btn_payment_request_changefile", "btnChangeFile"));

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

if ($halamanPost == "halConfirmPayment") 
{
	$tpl->Assign("getDataConfirm", $CPaymentAdv->getDataConfirm("",$userJenisPaymentAdv,$userIdSession));
}

if ($halamanPost == "halCheckPayment") 
{
	$tpl->Assign("getDataCheck", $CPaymentAdv->getDataCheck(""));
}

if ($halamanPost == "halApprovePayment") 
{
	$tpl->Assign("getDataApprove", $CPaymentAdv->getDataApprove(""));
}

if($halamanPost == "halReleasePayment")
{
	$tpl->Assign("getDataRelease", $CPaymentAdv->getDataRelease(""));
}

if($halamanPost == "halPrepareForPayment")
{	
	$tpl->Assign("getLastTransNo", $CPaymentAdv->getLastTransNo());
	$tpl->Assign("getDataPrepare", $CPaymentAdv->getDataPrepareForPayment("","",""));
}

if($halamanPost == "halVoucher")
{
	$tpl->Assign("dateNow", date("d/m/Y"));
	$tpl->Assign("getCodeBank", $CPaymentAdv->codeBank(''));
	$tpl->Assign("getCurr", $CPaymentAdv->menuCurrency(''));
	$tpl->Assign("getDataVoucher", $CPaymentAdv->getDataVoucher('',''));
}

$tpl->printToScreen();
?>