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
	$tpl->AssignInclude("CONTENT_TENGAH","templates/halSetting.html");
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halSelectButton.html");
}

if($aksiPost == "halamanUser")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halUser.html");
}
else if($aksiPost == "halamanHistory")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halHistory.html");
}
else if($aksiPost == "halamanSubCustom")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halSubCustom.html");
}
else if($aksiPost == "otherApp")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halOtherApp.html");
}
else if($aksiPost == "kpiSetting")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halKpiSetting.html");
}
else if($aksiPost == "convertFold")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halConvertFold.html");
}

$tpl->prepare();

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
	$tpl->Assign("menuSetting", $CPublic->menuSetting("on"));
	$tpl->Assign("menuApplication", $CPublic->menuApplication($COtherApp, $userIdLogin, ""));
	$tpl->Assign("btnUser", $CPublic->btnUser());
	$tpl->Assign("btnLogHistory", $CPublic->btnLogHistory());
	$tpl->Assign("btnCustomSub", $CPublic->btnCustomSub());
	$tpl->Assign("btnOtherApp", $CPublic->btnOtherApp());
	$tpl->Assign("btnKpiSetting", $CPublic->btnKpiSetting());
	$tpl->Assign("btnConvertFold", $CPublic->btnConvertFold());
}
else if($userJenis == "user")
{
	if($userMenuSetting == "Y")
	{
		if($userBtnUser == "Y")
		{
			$tpl->Assign("btnUser", $CPublic->btnUser());
		}
		if($userLogHistory == "Y")
		{
			$tpl->Assign("btnLogHistory", $CPublic->btnLogHistory());
		}
		if($userSubCustom == "Y")
		{
			$tpl->Assign("btnCustomSub", $CPublic->btnCustomSub());
		}
		if($userOtherApp == "Y")
		{
			$tpl->Assign("btnOtherApp", $CPublic->btnOtherApp());
		}
		if($userKpiSetting == "Y")
		{
			$tpl->Assign("btnKpiSetting", $CPublic->btnKpiSetting());
		}
		if($userConvFold == "Y")
		{
			$tpl->Assign("btnConvertFold", $CPublic->btnConvertFold());
		}
		
		if($userMenuSetting == "Y")
		{
			$tpl->Assign("menuSetting", $CPublic->menuSetting("on"));
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
}

if($aksiPost == "logout")
{
	$tpl->Assign("aksiEcho", $CLogin->logout());
}

if($aksiPost == "halamanUser")
{
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;\">USER LIST</span>");
}
if($aksiPost == "halamanHistory")
{
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;\">USER LOG HISTORY</span>");
	$tpl->Assign("menuUserHistory", $CEmployee->menuUserHistory());
}
else if($aksiPost == "halamanSubCustom")
{
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;\">SUBORDINATE CUSTOM</span>");
	$tpl->Assign("menuUserSubCustom_Superior", $CEmployee->menuUserSubCustom_Superior());
}
else if($aksiPost == "otherApp")
{
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;\">OTHER APPLICATION</span>");
	$tpl->Assign("menuUserOtherApp", $CEmployee->menuUserOtherApp());
}

else if($aksiPost == "kpiSetting")
{
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;\">KPI SETTING</span>");
	//$tpl->Assign("menuDivisi", $CEmployee->menuDiv());
	$tpl->Assign("menuTahun", $CPublic->menuTahunDB2(""));
}
else if($aksiPost == "convertFold")
{
	//echo $aksiPost;
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;\">CONVERT FOLDER</span>");
	
	$userIdMenu = "";
	$query = $CKoneksi->mysqlQuery("SELECT * FROM login WHERE deletests=0 ORDER by userfullnm ASC");
	while($row = $CKoneksi->mysqlFetch($query))
	{
		$userIdMenu.="<option value=\"".$row['userid']."\">".$row['userfullnm']."</option>";
	}
		
	$tpl->Assign("userIdMenu", $userIdMenu);
	
	$tpl->Assign("menuFoldLevelOne", $CFolder->menuFoldLevelOne(""));
}

$tpl->printToScreen();
?>