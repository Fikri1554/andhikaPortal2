<?php
require_once("../config.php");

$tpl = new myTemplate("templates/halUtama.html");

if($userIdSession == "")
{
	header("location:../archives/");
	exit;
}
if($userIdSession != "")
{
	$tpl->AssignInclude("CONTENT_TENGAH","templates/halSurvey.html");
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halSelectButton.html");
}

if($aksiPost == "satisfaction")
{
	$cekPin = $_POST['cekPin'];
	if($cekPin == "ada")
	{
		$tpl->AssignInclude("CONTENT_BAWAH","templates/halSatiSurvey.html");
	}
	else
	{
		$tpl->AssignInclude("CONTENT_BAWAH","templates/halSelectButton.html");
	}
}

if($aksiGet == "result")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halResult.html");
}

$tpl->prepare();

if($aksiPost == "satisfaction")
{
	$cekPin = $_POST['cekPin'];
	$pin = $_POST['pin'];
	if($cekPin == "ada")
	{
		$tpl->Assign("pin", $pin);
		$tpl->Assign("error", "");
	}
	if($cekPin == "used")
	{
		$tpl->Assign("error", "<span id=\"errorMsg\" class=\"errorMsg\">Your PIN Already Used !</span>");
		$tpl->Assign("pinBefore", $pin);
	}
	if($cekPin == "tidak" || $cekPin == "")
	{
		$tpl->Assign("error", "<span id=\"errorMsg\" class=\"errorMsg\">Wrong PIN !</span>");
		$tpl->Assign("pinBefore", $pin);
	}
}

if($userIdLogin != "")
{
	$tpl->Assign("welcomeUsername", $welcomeUsername );
	$tpl->Assign("spanLogout", "<span class=\"spanLogout\" onClick=\"formLogout.submit();\" title=\"Logout from Andhika Portal\">(&nbsp;Logout&nbsp;)</span>");
	$tpl->Assign("menuHome", $CPublic->menuHome(""));
	$tpl->Assign("menuNews", $CPublic->menuNews(""));
	$tpl->Assign("menuArchives", $CPublic->menuArchives(""));
	$tpl->Assign("menuSetting", "");
	$tpl->Assign("teks", "<td height=\"444\" style=\"font-family:sans-serif;font-weight:bold;font-size:30px;color:#CCC;\">PLEASE INSERT YOUR PIN NUMBER</td>");
}

if($aksiGet == "satisfaction")
{
	$tpl->Assign("teks", "<td height=\"444\" style=\"font-family:sans-serif;font-weight:bold;font-size:30px;color:#666;\">THANK YOU FOR YOUR PARTICIPATION</td>");
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