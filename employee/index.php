<?php
require_once("../config.php");

$tpl = new myTemplate("templates/halUtama.html");
$tpl->AssignInclude("CONTENT_TENGAH","templates/halEmpl.html");
if($userIdLogin == "" || $aksiGet == "sessionExpired")
{
	header("location:../archives/");
	exit;
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
	$tpl->Assign("menuEmployee", $CPublic->menuEmployee("on"));
	$tpl->Assign("menuArchives", $CPublic->menuArchives(""));
	$tpl->Assign("menuSetting", "");
}

if($aksiGet == "sessionExpired")
{
	$tpl->Assign("wrongPass", "<img src=\"../../picture/exclamation-red.png\" />&nbsp;Your session is expired!");
}

if($userJenis == "admin")
{
	//$tpl->Assign("menuSetting", "<li><a class=\"firLink\" href=\"../setting/\" title=\"Andhika Portal Admin Site\">Setting</a></li>");
	$tpl->Assign("menuSetting", $CPublic->menuSetting(""));
	$menuApplication = "<li class=\"has-sub\"><a href=\"#\"><span>Application</span></a>
					<ul>
						".$COtherApp->menuOtherApp($userIdLogin)."
					</ul>
				</li>";
	$tpl->Assign("menuApplication", $menuApplication);
	$tpl->Assign("selDoc", "<select id=\"jenisDoc\" name=\"jenisDoc\" class=\"elementMenu\" style=\"width:200px;\" onchange=\"emptySearch();leftFrame(this.value);refreshRightFrame();\" title=\"Choose Document Type\">
            <option value=\"0\">--PLEASE SELECT--</option>
            <option value=\"PO\">POLICY</option>
            <option value=\"PR\">PROCEDURE</option>
            <option value=\"FO\">FORM</option>
        </select>");
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
	$tpl->Assign("selDoc", "<select id=\"jenisDoc\" name=\"jenisDoc\" class=\"elementMenu\" style=\"width:200px;\" onchange=\"emptySearch();leftFrame(this.value);refreshRightFrame();\" title=\"Choose Document Type\">
            <option value=\"0\">--PLEASE SELECT--</option>
            <option value=\"PO\">POLICY</option>
            <option value=\"PR\">PROCEDURE</option>
            <option value=\"FO\">FORM</option>
        </select>");
}

if($adminEmpl == "Y" && $userIdLogin != "")
{
	$tpl->Assign("selDoc", "<select id=\"jenisDoc\" name=\"jenisDoc\" class=\"elementMenu\" style=\"width:200px;\" onchange=\"emptySearch();leftFrame(this.value);refreshRightFrame();funcBtnNew(this.value);\" title=\"Choose Document Type\">
            <option value=\"0\">--PLEASE SELECT--</option>
            <option value=\"PO\">POLICY</option>
            <option value=\"PR\">PROCEDURE</option>
            <option value=\"FO\">FORM</option>
        </select>");
		
	$tpl->Assign("btnNewDoc", "<button id=\"idBtnNew\" class=\"btnStandar\" style=\"width:125px;height:29px;\" onclick=\"openThickboxWindow('','new');\" title=\"Display Stop Card of Choosen Date & Employee\">
            <table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
              <tr>
                <td align=\"center\"><img src=\"../picture/Document-blue-32.png\" height=\"20\"/> </td>
                <td align=\"center\">New Document</td>
              </tr>
            </table>
        </button>");
}
$tpl->printToScreen();
?>