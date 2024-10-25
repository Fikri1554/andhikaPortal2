<?php
require_once("../config.php");

$tpl = new myTemplate("templates/halUtama.html");

if($userIdSession == "")
{
	//$tpl->AssignInclude("CONTENT_TENGAH","../archives/templates/login.html");
	header("location:../archives/");
	exit;
}
if($userIdSession != "")
{
	$tpl->AssignInclude("CONTENT_TENGAH","templates/halQhse.html");
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halSelectButton.html");
}

if($aksiGet == "stopCard")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halStopCard.html");
}
if($aksiGet == "inbox")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halInbox.html");
}

$tpl->prepare();

if($aksiGet == "stopCard")
{
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;\">SAFETY OBSERVATION</span>");
}
if($aksiGet == "inbox")
{
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;\">INBOX</span>");
	$tpl->Assign("monthYear", $CQhse->monthYear($CPublic, "menu"));
	$tpl->Assign("dateCard", $CQhse->dateCard($CPublic));
	$tpl->Assign("ownerCard", $CQhse->ownerCard());
	
	$unread = $CQhse->unReadInbox("".$CQhse->monthYear($CPublic, "nilai")."-00", "00000");
	$tpl->Assign("unread", "".$unread."");
	$tpl->Assign("thnBln", $CQhse->monthYear($CPublic, "nilai"));
	$tpl->Assign("btnMonthlyPrint", $CQhse->btnMonthlyPrint());
}
if($userQhse == "Y")
{
	$tpl->Assign("menuInbox", $CQhse->inboxNotif());
}
if($userIdLogin != "")
{
	$tpl->Assign("welcomeUsername", $welcomeUsername );
	$tpl->Assign("spanLogout", "<span class=\"spanLogout\" onClick=\"formLogout.submit();\" title=\"Logout from Andhika Portal\">(&nbsp;Logout&nbsp;)</span>");
	$tpl->Assign("menuHome", $CPublic->menuHome(""));
	$tpl->Assign("menuNews", $CPublic->menuNews(""));
	$tpl->Assign("menuArchives", $CPublic->menuArchives(""));
	$tpl->Assign("menuSetting", "");
}

if($userJenis == "admin")
{
	$tpl->Assign("menuSetting", $CPublic->menuSetting(""));
	$menuApplication = "<li class=\"has-sub\"><a href=\"#\"><span class=\"on\">Application</span></a>
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

if($aksiPost == "cekLogin")
{
	$tpl->Assign("aksiEcho", $CLogin->cekLogin($_POST, $CHistory, $CPublic));
}
if($dologinGet == "0")
{
	$tpl->Assign("wrongPass", "<img src=\"../../picture/exclamation-red.png\" />&nbsp;Login failed!");
}
if($aksiGet == "sessionExpired")
{
	$tpl->Assign("wrongPass", "<img src=\"../../picture/exclamation-red.png\" />&nbsp;Your session is expired!");
}

$tpl->printToScreen();
?>