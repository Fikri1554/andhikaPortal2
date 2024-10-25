<?php
require_once("config.php");
//print_r($tplDir);exit;
$tpl = new myTemplate($tplDir."halUtama.html");
$tpl->AssignInclude("CONTENT_TENGAH",$tplDir."home.html");
if($aksiGet == "underConstruction")
{
	$tpl->AssignInclude("CONTENT_TENGAH", "templates/underConstruction.html");
}

$tpl->prepare();
//echo "<pre>";
//print_r($CPublic);exit;
$tpl->Assign("menuHome", $CPublic->menuHome("on"));
$tpl->Assign("menuLogin", $CPublic->menuLogin(""));

if($userIdLogin != "")
{
	$tpl->Assign("idleAfterr", $idleAfter);
	$tpl->Assign("welcomeUsername", $welcomeUsername);
	$tpl->Assign("spanLogout", "<span class=\"spanLogout\" onClick=\"formLogout.submit();\" title=\"Logout from Andhika Portal\">(&nbsp;Logout&nbsp;)</span>");
	$tpl->Assign("menuHome", $CPublic->menuHome("on"));
	$tpl->Assign("menuNews", $CPublic->menuNews(""));
	$tpl->Assign("menuEmployee", $CPublic->menuEmployee(""));
	$tpl->Assign("menuLogin", "");
	$tpl->Assign("menuArchives", $CPublic->menuArchives(""));
	$tpl->Assign("menuSetting", "");
	
	if($userMenuApplication == "Y")
	{
		/*$menuOtherApp = "<li>
						<div id=\"ddtopmenubar\" class=\"mattblackmenu\">
							<a href=\"#\" rel=\"ddsubmenu1\" class=\"firLink\">Application</a>
							<ul id=\"ddsubmenu1\" class=\"ddsubmenustyle\" style=\"width:150px;background-color: #dde4f3;top:-30;\">
							".$COtherApp->menuOtherApp($userIdLogin)."
							</ul>
						</div>
					</li>";*/
		//$tpl->Assign("menuOtherApp", $CPublic->menuApplication($COtherApp, $userIdLogin, ""));
	}
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
		$menuApplication = "<li class=\"has-sub\"><a href=\"#\"><span>Application</span></a>
					<ul>
						".$COtherApp->menuOtherApp($userIdLogin)."
					</ul>
				</li>";
		$tpl->Assign("menuApplication", $menuApplication);
	}
}

$tpl->printToScreen();
?>