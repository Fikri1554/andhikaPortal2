<?php
require_once("configSafir.php");

if($userIdSession == "")
{
	header("location:../archives/");
	exit;
}
if($userIdSession != "")
{
	$tpl = new myTemplate("templates/halUtama.html");
	if($userJenisSafir == "")
	{
		$tpl->AssignInclude("CONTENT_TENGAH","templates/halContactAdmin.html");
	}
	if($userJenisSafir != "")
	{
		$tpl->AssignInclude("CONTENT_TENGAH","templates/halData.html");
	}
}

$tpl->prepare();
$tpl->Assign("idleAfter", $idleAfter);
if($userIdLogin != "")
{
	$tpl->Assign("welcomeUsername", $welcomeUsername );
	$tpl->Assign("spanLogout","<span class=\"spanLogout\" onClick=\"formLogout.submit();\" title=\"Logout from Andhika Safir\">(&nbsp;Logout&nbsp;)</span>");
	
	$tpl->Assign("menuHome", $CPublic->menuHome(""));
	$tpl->Assign("menuNews", $CPublic->menuNews(""));
	$tpl->Assign("menuEmployee", $CPublic->menuEmployee(""));
	$tpl->Assign("menuArchives", $CPublic->menuArchives(""));
	
	$tpl->Assign("fileExport", "");
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
	
	$menuResultSurvey ="<button id=\"btnDisplay\" class=\"btnStandar\" style=\"width:75px;height:29px;\" onclick=\"loadUrl('index.php?aksi=result'); return false;\" title=\"Result of Satisfaction Survey\">
							<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\" border=\"0\">
							  <tr>
								<td align=\"right\" width=\"25\"><img src=\"../picture/Arrow-Right-blue-32.png\" height=\"20\"/> </td>
								<td align=\"center\">Result</td>
							  </tr>
							</table>
						</button>";
	$tpl->Assign("menuResultSurvey", $menuResultSurvey);
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
	if($userResultSurvey == "Y")
	{
		$menuResultSurvey ="<button id=\"btnDisplay\" class=\"btnStandar\" style=\"width:75px;height:29px;\" onclick=\"loadUrl('index.php?aksi=result'); return false;\" title=\"Result of Satisfaction Survey\">
							<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\" border=\"0\">
							  <tr>
								<td align=\"right\" width=\"25\"><img src=\"../picture/Arrow-Right-blue-32.png\" height=\"20\"/> </td>
								<td align=\"center\">Result</td>
							  </tr>
							</table>
						</button>";
		$tpl->Assign("menuResultSurvey", $menuResultSurvey);
	}
}

$tpl->printToScreen();
?>