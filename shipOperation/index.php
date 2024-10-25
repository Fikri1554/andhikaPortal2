<?php
require_once("../config.php");
$tpl = new myTemplate("templates/halUtama.html");

if($userIdSession == "")
{
	header("location:../archives/");
	exit;
}else{
	$tpl->AssignInclude("CONTENT_TENGAH","templates/halBtnMenu.html");
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halSelectButton.html");
}

if ($aksiPost == "halamanHistoryVessel") 
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/pageHistoryVessel.php");
}

elseif ($aksiPost == "") {
	unset($_SESSION['vesselCode']);
}

$tpl->prepare();

if($userIdLogin != "")
{
	$tpl->Assign("welcomeUsername", $welcomeUsername );
	$tpl->Assign("spanLogout", "<span class=\"spanLogout\" onClick=\"formLogout.submit();\" title=\"Logout from Andhika Portal\">(&nbsp;Logout&nbsp;)</span>");
	$tpl->Assign("menuHome", $CPublic->menuHome(""));
	$tpl->Assign("menuNews", $CPublic->menuNews(""));
	$tpl->Assign("menuEmployee", $CPublic->menuEmployee(""));
	$tpl->Assign("menuArchives", $CPublic->menuArchives(""));
	$tpl->Assign("menuSetting", "");
}

	$tpl->Assign("menuSetting", $CPublic->menuSetting(""));
	$menuApplication = "<li class=\"has-sub\"><a href=\"#\"><span class=\"on\">Application</span></a>
					<ul>
						".$COtherApp->menuOtherApp($userIdLogin)."
					</ul>
				</li>";
	$tpl->Assign("menuApplication", $menuApplication);

	$tpl->Assign("btnHistoryVessel", $CPublic->btnHistorySO());

if($aksiPost == "logout")
{
	$tpl->Assign("aksiEcho", $CLogin->logout($CPublic, $CHistory));
}

if($aksiPost == "halamanHistoryVessel")
{
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;font-size: 14px;\">HISTORY VESSEL</span>");
}

$tpl->printToScreen();
?>