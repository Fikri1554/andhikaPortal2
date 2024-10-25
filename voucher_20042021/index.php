<?php
require_once("configVoucher.php");

if($userIdSession == "")
{
	header("location:../archives/");
	exit;
}

if($userIdSession != "")
{
	$tpl = new myTemplate("templates/halUtama.html");
	if($userJenisVoucher == "")
	{
		$tpl->AssignInclude("CONTENT_TENGAH","templates/halContactAdmin.html");
	}
	if($userJenisVoucher != "")
	{
		$tpl->AssignInclude("CONTENT_TENGAH","templates/halVoucher.html");
	}
}
if($userJenis != "admin")
{
	//$tpl->AssignInclude("CONTENT_TENGAH","templates/halContactAdmin.html");
	
}

$tpl->prepare();

$tpl->Assign("userJenisVoucher", $userJenisVoucher);
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
	/*$tpl->Assign("disBtnInvProcess", $CInvReg->aksesBtn($userIdSession, "", "btninvprocess", "btnInvProcess"));
	$tpl->Assign("disBtnPayment", $CInvReg->aksesBtn($userIdSession, "", "btnpayment", "btnPayment"));
	$tpl->Assign("disBtnOutgoing", $CInvReg->aksesBtn($userIdSession, "", "btnoutgoing", "btnOutgoing"));
	$tpl->Assign("disBtnPrint", $CInvReg->aksesBtn($userIdSession, "", "btnprint", "btnPrint"));*/
}

//$tpl->Assign("statusYearProc", "awal");
//$tpl->Assign("klikYearProc", "$('#tdYearProc').click();");
$tpl->Assign("menuCurrency", $CVoucher->menuCurrency(''));
$tpl->Assign("menuCmp", $CVoucher->menuCmp('',$userCompany));
$tpl->Assign("bankCode", $CVoucher->bankCodeMenu(''));
$tpl->Assign("bankCodeFirst", $CVoucher->bankCodeMenuFirst() );
$tpl->Assign("currFirst", $CVoucher->menuCurrFirst() );
$tpl->Assign("saveDiffYear", $CVoucher->detilTblUserjns($userIdSession, "diffYear") );
$tpl->Assign("userIdSession", $userIdSession);


$tpl->printToScreen();
?>