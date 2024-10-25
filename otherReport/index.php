<?php
require_once("../config.php");

$tpl = new myTemplate("templates/halUtama.html");
$tpl->AssignInclude("CONTENT_TENGAH","templates/halOtherReport.html");
$tpl->AssignInclude("CONTENT_BAWAH","templates/halPheonwj.html");
if($userIdLogin == "" || $aksiGet == "sessionExpired")
{
	header("location:../archives/");
	exit;
}
if($aksiGet == "pheonwj")
{

}

$tpl->prepare();

$tpl->Assign("idleAfter", $idleAfter);
if($aksiPost == "cekLogin")
{
	$tpl->Assign("aksiEcho", $CLogin->cekLogin($_POST, $CHistory, $CPublic));
}

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

if($userJenis == "admin")
{
	//$tpl->Assign("menuSetting", "<li><a class=\"firLink\" href=\"../setting/\" title=\"Andhika Portal Admin Site\">Setting</a></li>");
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

if($aksiGet == "sessionExpired")
{
	$tpl->Assign("wrongPass", "<img src=\"../../picture/exclamation-red.png\" />&nbsp;Your session is expired!");
}

$tpl->printToScreen();
?>