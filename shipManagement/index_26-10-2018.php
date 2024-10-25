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

if ($aksiPost == "halamanPlannMaintenance") 
{
	$_SESSION["vesselCode"] = $_POST["namaKapal"];
	$tpl->AssignInclude("CONTENT_BAWAH","templates/pagePlanMaintenance.html");
}
elseif ($aksiPost == "halamanAllCompJob") 
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/pageAllCompJob.php");
}
elseif ($aksiPost == "halamanUpdateJob") 
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/pageUpdateJobsDone.php");
}
elseif ($aksiPost == "halamanMonthCompRunningHours") 
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/pageMonthlyCompRunHours.php");
}
elseif ($aksiPost == "halamanMasterCompList") 
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/pageMasterCompList.php");
}
elseif ($aksiPost == "halamanMasterJobDesc") 
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/pageMasterJobDesc.php");
}
elseif ($aksiPost == "halamanMasterWorkClass") 
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/pageMasterWorkClass.php");
}
elseif ($aksiPost == "halamanRoutinJob") 
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/pageRoutinJob.php");
}
elseif ($aksiPost == "halamanMaintenanceReport") 
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/pageMaintenanceReport.php");
}
elseif ($aksiPost == "halamanWorkListReport") 
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/pageWorkListMonth.php");
}
elseif ($aksiPost == "halamanMaintenanceForecastReport") 
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/pageMaintenanceForecast.php");
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

	$tpl->Assign("btnPlannMaintenance", $CPublic->btnPlannMaintenance());
	$tpl->Assign("btnAllCompJob", $CPublic->btnAllCompJob());
	$tpl->Assign("btnMonthlyMaintenanceForecast", $CPublic->btnMonthlyMaintenanceForecast());
	$tpl->Assign("btnDueOverDue", $CPublic->btnDueOverDue());
	$tpl->Assign("btnUpdateJobsDone", $CPublic->btnUpdateJobsDone());
	$tpl->Assign("btnMonthCompRunningHours", $CPublic->btnMonthCompRunningHours());
	$tpl->Assign("btnMaintenanceReportForm", $CPublic->btnMaintenanceReportForm());
	$tpl->Assign("btnRoutineJob", $CPublic->btnRoutineJob());
	$tpl->Assign("btnCompMasterList", $CPublic->btnCompMasterList());
	$tpl->Assign("btnJobDescription", $CPublic->btnJobDescription());
	$tpl->Assign("btnWorkClassification", $CPublic->btnWorkClassification());

	$tpl->Assign("selectedNamaKapal", $CGetData->getVessel());
	$tpl->Assign("selectedPart", $CGetData->getSelectPart());
	$tpl->Assign("selectEquipmentAll", $CGetData->getSelectEquipment("all"));
	$tpl->Assign("selectJobHeading", $CGetData->getSelectJobHeading("all"));


if($aksiPost == "logout")
{
	$tpl->Assign("aksiEcho", $CLogin->logout($CPublic, $CHistory));
}

if($aksiPost == "halamanPlannMaintenance")
{
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;font-size: 14px;\">PLANNED MAINTENANCE</span>");
}
elseif ($aksiPost == "halamanAllCompJob") 
{
	$tpl->Assign("selectEquipment", $CGetData->getSelectEquipment());

	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;font-size: 14px;\">PLANNED MAINTENANCE</span>");
	$tpl->Assign("halaman3", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;font-size: 14px;\">ALL COMPONENTS & JOB</span>");
}
elseif ($aksiPost == "halamanUpdateJob") 
{
	$tpl->Assign("getDataUpdateJob", $CGetData->getDataJobDone());

	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;font-size: 14px;\">PLANNED MAINTENANCE</span>");
	$tpl->Assign("halaman3", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;font-size: 14px;\">UPDATE JOBS DONE</span>");
}
elseif ($aksiPost == "halamanMonthCompRunningHours") 
{
	$tpl->Assign("getDataEquip", $CGetData->getDataEquip());
	$tpl->Assign("blnSblm", date('M', strtotime('-1 month')));
	$tpl->Assign("blnSkrg", date('M'));

	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;font-size: 14px;\">PLANNED MAINTENANCE </span>");
	$tpl->Assign("halaman3", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;font-size: 14px;\">MONTHLY COMPONENT RUNNING HOURS</span>");
}
elseif ($aksiPost == "halamanMasterCompList") 
{
	$tpl->Assign("getDataEquip", $CGetData->getMstEquipJob());

	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;font-size: 14px;\">PLANNED MAINTENANCE </span>");
	$tpl->Assign("halaman3", "> <span class=\"teksMyFolder\" style=\"color:#369;font-size: 14px;\">MASTER COMPONENTS & JOB</span>");
}
elseif ($aksiPost == "halamanMasterJobDesc") 
{
	$tpl->Assign("getDataMstJobDesc", $CGetData->getMstListJobDesc());
	$tpl->Assign("getDataSlcCodeJOB", $CGetData->getInitJobCode());

	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;font-size: 14px;\">PLANNED MAINTENANCE </span>");
	$tpl->Assign("halaman3", "> <span class=\"teksMyFolder\" style=\"color:#369;font-size: 14px;\">MASTER ALL JOB DESCRIPTION</span>");
}
elseif ($aksiPost == "halamanMasterWorkClass") 
{
	$tpl->Assign("getDataMstWorkClass", $CGetData->getMstListWorkClass());

	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;font-size: 14px;\">PLANNED MAINTENANCE </span>");
	$tpl->Assign("halaman3", "> <span class=\"teksMyFolder\" style=\"color:#369;font-size: 14px;\">MASTER WORK CLASS</span>");
}
elseif ($aksiPost == "halamanRoutinJob") 
{
	$tpl->Assign("getDataRoutinJob", $CGetData->getRoutinJob());

	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;font-size: 14px;\">PLANNED MAINTENANCE </span>");
	$tpl->Assign("halaman3", "> <span class=\"teksMyFolder\" style=\"color:#369;font-size: 14px;\">ROUTIN JOB</span>");
}
elseif ($aksiPost == "halamanMaintenanceReport") 
{	
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;font-size: 14px;\">PLANNED MAINTENANCE </span>");
	$tpl->Assign("halaman3", "> <span class=\"teksMyFolder\" style=\"color:#369;font-size: 14px;\">MAINTENANCE REPORT</span>");
}
elseif ($aksiPost == "halamanWorkListReport") 
{
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;font-size: 14px;\">PLANNED MAINTENANCE </span>");
	$tpl->Assign("halaman3", "> <span class=\"teksMyFolder\" style=\"color:#369;font-size: 14px;\">WORK LIST</span>");
}
elseif ($aksiPost == "halamanMaintenanceForecastReport") 
{
	$tpl->Assign("getDataMaintenanceForecast", $CGetData->getDataMaintenanceForecaset());
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;font-size: 14px;\">PLANNED MAINTENANCE </span>");
	$tpl->Assign("halaman3", "> <span class=\"teksMyFolder\" style=\"color:#369;font-size: 14px;\">MAINTENANCE FORECAST</span>");
}

if ($_SESSION['btnExportPrint'] == "N") 
{
	$tpl->Assign("stBtnExport", "disabled=\"disabled\"");
}else{
	$tpl->Assign("stBtnExport", "");
}

$tpl->printToScreen();
?>