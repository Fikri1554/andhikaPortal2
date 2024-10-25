<?php
require_once("configSpj.php");

if($userIdSession == "" || $aksiGet == "sessionExpired")
{
	header("location:../archives/");
	exit;
}
if($userIdSession != "")
{
	$tpl = new myTemplate("templates/halUtama.html");

	$tpl->AssignInclude("CONTENT_TENGAH","templates/halSpj.html");
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halSelectButton.html");

	if($aksiGet == "spjForm")
	{
		$tpl->AssignInclude("CONTENT_BAWAH","templates/halSpjForm.html");
	}
	
	if($aksiGet == "spjAck")
	{
		$tpl->AssignInclude("CONTENT_BAWAH","templates/halSpjAck.html");
	}
	
	if($aksiGet == "spjAprv")
	{
		$tpl->AssignInclude("CONTENT_BAWAH","templates/halSpjAprv.html");
	}
	
	if($aksiGet == "spjAll")
	{
		$tpl->AssignInclude("CONTENT_BAWAH","templates/halSpjAll.html");
	}
	
	if($aksiGet == "spjReport")
	{
		$tpl->AssignInclude("CONTENT_BAWAH","templates/halSpjReport.html");
	}
	
	if($aksiGet == "reportAck")
	{
		$tpl->AssignInclude("CONTENT_BAWAH","templates/halReportAck.html");
	}
	if($aksiGet == "reportExm")
	{
		$tpl->AssignInclude("CONTENT_BAWAH","templates/halReportExm.html");
	}
	if($aksiGet == "reportHist")
	{
		$tpl->AssignInclude("CONTENT_BAWAH","templates/halReportHist.html");
	}
	if($aksiGet == "reportSummary")
	{
		$tpl->AssignInclude("CONTENT_BAWAH","templates/halReportSummary.html");
	}
	/*if($aksiGet == "reportPrcs")
	{
		$tpl->AssignInclude("CONTENT_BAWAH","templates/halReportPrcs.html");
	}
	if($aksiGet == "reportAll")
	{
		$tpl->AssignInclude("CONTENT_BAWAH","templates/halReportAll.html");
	}*/
}

$tpl->prepare();
$tpl->Assign("idleAfter", $idleAfter);

if($aksiPost == "cekLogin")
{
	$tpl->Assign("aksiEcho", $CLogin->cekLogin($_POST, $CHistory, $CPublic));
}

if($userIdLogin != "")
{
	$tpl->Assign("userIdLogin", $userIdLogin);
	$tpl->Assign("welcomeUsername", $welcomeUsername );
	$tpl->Assign("spanLogout", "<span class=\"spanLogout\" onClick=\"formLogout.submit();\" title=\"Logout from Andhika Portal\">(&nbsp;Logout&nbsp;)</span>");
	
	$tpl->Assign("menuHome", $CPublic->menuHome(""));
	$tpl->Assign("menuNews", $CPublic->menuNews(""));
	$tpl->Assign("menuEmployee", $CPublic->menuEmployee(""));
	$tpl->Assign("menuArchives", $CPublic->menuArchives(""));
	$tpl->Assign("menuSetting", "");
	
	$tpl->Assign("gol", $CEmployee->gol($userEmpNo));
	/*$cekKadiv = $CSpj->cekKadiv($userEmpNo); // cek user merupakan Kadiv atau bukan
	if($userEmpNo == "00625" || $cekKadiv == 1 || $CSpj->userJenis($userIdLogin) == "admin")
	{
		$tpl->Assign('spjAprvBtn', '<button class="spjBtnStandarBsr" type="button" onclick="loadUrl(\'index.php?aksi=spjAprv\'); return false;" style="width:90px;height:50px;" title="List SPJ Need Approval">
          <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
            <tr>
              <td align="center"><img src="picture/File-Complete-32.png" height="15"/> </td> 
            </tr>
            <tr>
              <td align="center">SPJ VERIFICATION</td>
            </tr>
          </table>
      </button>');
	}
	
	//kdDiv HR = 050;
	$kadivEmpNo = $CEmployee->detilDiv("050", "divhead");
	if($userEmpNo == $kadivEmpNo || $CSpj->userJenis($userIdLogin) == "admin")
	{
		$tpl->Assign('spjAckBtn', '<button class="spjBtnStandarBsr" type="button" onclick="loadUrl(\'index.php?aksi=spjAck\'); return false;" style="width:90px;height:50px;" title="List SPJ Need Acknowledge">
						  <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
							<tr>
							  <td align="center"><img src="picture/File-Complete-32.png" height="15"/> </td> 
							</tr>
							<tr>
							  <td align="center">SPJ ACKNOWLEDGE</td>
							</tr>
						  </table>
					  </button>');
	}*/
}

if($aksiGet == "sessionExpired")
{
	$tpl->Assign("wrongPass", "<img src=\"../../picture/exclamation-red.png\" />&nbsp;Your session is expired!");
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

if($userJenisSpj == "admin")
{
	$tpl->Assign("linkPdf", "Adm SPJ Manual Book Rev 1.0.pdf");
	$tpl->Assign("tipeUser", $userJenisSpj);
	$tpl->Assign("printBtn", '<button type="button" id="btnPrint" class="spjBtnStandarDis" onclick="printReport(); return false;" style="width:62px;height:28px;" title="Print Report" disabled>
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                                      <tr>
                                        <td align="right" width="22"><img src="picture/printer.png" height="16"/> </td>
                                        <td align="center">Print</td>
                                      </tr>
                                    </table>
                                </button>');
}

if($userJenisSpj != "admin")
{
	$tpl->Assign("linkPdf", "SPJ Manual Book Rev 1.0.pdf");
	$tpl->Assign("printBtn", '<button type="button" id="btnPrint" class="spjBtnStandarDis" onclick="printReport(); return false;" style="width:62px;height:28px;" title="Print Report" disabled>
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                                      <tr>
                                        <td align="right" width="22"><img src="picture/printer.png" height="16"/> </td>
                                        <td align="center">Print</td>
                                      </tr>
                                    </table>
                                </button>');
}

if($aksiGet == "spjForm" || $aksiGet == "spjAprv" || $aksiGet == "spjAck" || $aksiGet == "spjAll")
{
	if($aksiGet == "spjForm")
	{
		$hal = "List";
		$btnClass = "btnSubMenuActive";
		$btnClass1 = "btnSubMenu1";
		$btnClass2 = "btnSubMenu2";
		$btnClass3 = "btnSubMenu3";
		$link = "";
		$link1 = "loadUrl('index.php?aksi=spjAprv'); pleaseWait();";
		$link2 = "loadUrl('index.php?aksi=spjAck'); pleaseWait();";
		$link3 = "loadUrl('index.php?aksi=spjAll'); pleaseWait();";
	}
	if($aksiGet == "spjAprv")
	{
		$hal = "Approval";
		$btnClass = "btnSubMenu";
		$btnClass1 = "btnSubMenuActive1";
		$btnClass2 = "btnSubMenu2";
		$btnClass3 = "btnSubMenu3";
		$link = "loadUrl('index.php?aksi=spjForm'); pleaseWait();";
		$link1 = "";
		$link2 = "loadUrl('index.php?aksi=spjAck'); pleaseWait();";
		$link3 = "loadUrl('index.php?aksi=spjAll'); pleaseWait();";
	}
	if($aksiGet == "spjAck")
	{
		$hal = "Acknowledge";
		$btnClass = "btnSubMenu";
		$btnClass1 = "btnSubMenu1";
		$btnClass2 = "btnSubMenuActive2";
		$btnClass3 = "btnSubMenu3";
		$link = "loadUrl('index.php?aksi=spjForm'); pleaseWait();";
		$link1 = "loadUrl('index.php?aksi=spjAprv'); pleaseWait();";
		$link2 = "";
		$link3 = "loadUrl('index.php?aksi=spjAll'); pleaseWait();";
	}
	if($aksiGet == "spjAll")
	{
		$hal = "All";
		$btnClass = "btnSubMenu";
		$btnClass1 = "btnSubMenu1";
		$btnClass2 = "btnSubMenu2";
		$btnClass3 = "btnSubMenuActive3";
		$link = "loadUrl('index.php?aksi=spjForm'); pleaseWait();";
		$link1 = "loadUrl('index.php?aksi=spjAprv'); pleaseWait();";
		$link2 = "loadUrl('index.php?aksi=spjAck'); pleaseWait();";
		$link3 = "";
	}
	if($CSpj->cekKadiv($userEmpNo) == 0 && $userJenisSpj != "admin" && $CSpj->cekCustomAprv($userIdSession) == 0 && $userJenisSpj != "CEO")//jika bukan kadiv/admin/ceo, disabled aprove button
	{
		$btnClass1 = "btnSubMenuDis";
		$dis1 = "disabled";
	}
	if($CEmployee->detilDiv("050", "divhead") != $userEmpNo && $userJenisSpj != "admin")// jika bukan admin, kadiv HR, disabled acknowledge button
	{
		$btnClass2 = "btnSubMenuDis";
		$dis2 = "disabled";
	}
	
	
	$tpl->Assign("halaman2","> FORM > ".$hal);
	$tpl->Assign("activeMenu","btnForm");
	
	$tpl->Assign('subMenu','<div style="font-weight:bold;font-size:9px;float:left;margin-top:7px;">>>&nbsp;&nbsp;</div>
      <button id="list" class="'.$btnClass.'" type="button" onclick="'.$link.'" title="Create & List SPJ Form">
        <table width="40" height="23">
            <tr>
                <td align="center">List</td> 
            </tr>
        </table>
      </button><button id="aprv" class="'.$btnClass1.'" type="button" onclick="'.$link1.'" style="border-left:0px;border-right:0px;" title="List SPJ Form for Approval" '.$dis1.'>
        <table width="70" height="23">
            <tr>
                <td align="center">Approval</td> 
            </tr>
        </table>
      </button><button id="ack" class="'.$btnClass2.'" type="button" onclick="'.$link2.'" title="List SPJ Form for Acknowledge" '.$dis2.'>
        <table width="90" height="23">
            <tr>
                <td align="center">Acknowledge</td> 
            </tr>
        </table>
      </button>');
	  if($userJenisSpj == "admin")
	  {
		  $tpl->Assign('subMenuAdm','<button id="ack" class="'.$btnClass3.'" type="button" onclick="'.$link3.'" style="border-left:0px;" title="List SPJ Form for Administrator">
			<table width="40" height="23">
				<tr>
					<td align="center">All</td> 
				</tr>
			</table>
		  </button>');
	  }
}

if($aksiGet == "spjReport" || $aksiGet == "reportAck" || $aksiGet == "reportExm" || $aksiGet == "reportPrcs" || $aksiGet == "reportHist" || $aksiGet == "reportSummary")
{
	if($aksiGet == "spjReport")
	{
		$hal = "List";
		$btnClass = "btnSubMenuActive";
		//$btnClass1 = "btnSubMenu1";
		$btnClass2 = "btnSubMenu2";
		$btnClass3 = "btnSubMenu3";
		$btnClass4 = "btnSubMenu4";
		$btnClass5 = "btnSubMenu5";
		$link = "";
		//$link1 = "loadUrl('index.php?aksi=reportPrcs'); pleaseWait();";
		$link2 = "loadUrl('index.php?aksi=reportAck'); pleaseWait();";
		$link3 = "loadUrl('index.php?aksi=reportExm'); pleaseWait();";
		$link4 = "loadUrl('index.php?aksi=reportHist'); pleaseWait();";
		$link5 = "loadUrl('index.php?aksi=reportSummary'); pleaseWait();";
	}
	/*if($aksiGet == "reportPrcs")
	{
		$hal = "Process";
		$btnClass = "btnSubMenu";
		$btnClass1 = "btnSubMenuActive1";
		$btnClass2 = "btnSubMenu2";
		$btnClass3 = "btnSubMenu3";
		$link = "loadUrl('index.php?aksi=spjReport'); pleaseWait();";
		$link1 = "";
		$link2 = "loadUrl('index.php?aksi=reportAck'); pleaseWait();";
		$link3 = "loadUrl('index.php?aksi=reportExm'); pleaseWait();";
	}*/
	if($aksiGet == "reportAck")
	{
		$hal = "Acknowledge";
		$btnClass = "btnSubMenu";
		//$btnClass1 = "btnSubMenu1";
		$btnClass2 = "btnSubMenuActive2";
		$btnClass3 = "btnSubMenu3";
		$btnClass4 = "btnSubMenu4";
		$btnClass5 = "btnSubMenu5";
		$link = "loadUrl('index.php?aksi=spjReport'); pleaseWait();";
		//$link1 = "loadUrl('index.php?aksi=reportPrcs'); pleaseWait();";
		$link2 = "";
		$link3 = "loadUrl('index.php?aksi=reportExm'); pleaseWait();";
		$link4 = "loadUrl('index.php?aksi=reportHist'); pleaseWait();";
		$link5 = "loadUrl('index.php?aksi=reportSummary'); pleaseWait();";
	}
	if($aksiGet == "reportExm")
	{
		$hal = "Check";
		$btnClass = "btnSubMenu";
		//$btnClass1 = "btnSubMenu1";
		$btnClass2 = "btnSubMenu2";
		$btnClass3 = "btnSubMenuActive3";
		$btnClass4 = "btnSubMenu4";
		$btnClass5 = "btnSubMenu5";
		$link = "loadUrl('index.php?aksi=spjReport'); pleaseWait();";
		//$link1 = "loadUrl('index.php?aksi=reportPrcs'); pleaseWait();";
		$link2 = "loadUrl('index.php?aksi=reportAck'); pleaseWait();";
		$link3 = "";
		$link4 = "loadUrl('index.php?aksi=reportHist'); pleaseWait();";
		$link5 = "loadUrl('index.php?aksi=reportSummary'); pleaseWait();";
	}
	if($aksiGet == "reportHist")
	{
		$hal = "Check";
		$btnClass = "btnSubMenu";
		//$btnClass1 = "btnSubMenu1";
		$btnClass2 = "btnSubMenu2";
		$btnClass3 = "btnSubMenu3";
		$btnClass4 = "btnSubMenuActive4";
		$btnClass5 = "btnSubMenu5";
		$link = "loadUrl('index.php?aksi=spjReport'); pleaseWait();";
		//$link1 = "loadUrl('index.php?aksi=reportPrcs'); pleaseWait();";
		$link2 = "loadUrl('index.php?aksi=reportAck'); pleaseWait();";
		$link3 = "loadUrl('index.php?aksi=reportExm'); pleaseWait();";
		$link4 = "";
		$link5 = "loadUrl('index.php?aksi=reportSummary'); pleaseWait();";
	}
	if($aksiGet == "reportSummary")
	{
		$hal = "Summary";
		$btnClass = "btnSubMenu";
		// $btnClass1 = "btnSubMenu1";
		$btnClass2 = "btnSubMenu2";
		$btnClass3 = "btnSubMenu3";
		$btnClass4 = "btnSubMenu4";
		$btnClass5 = "btnSubMenuActive5";
		$link = "loadUrl('index.php?aksi=spjReport'); pleaseWait();";
		// $link1 = "loadUrl('index.php?aksi=reportPrcs'); pleaseWait();";
		$link2 = "loadUrl('index.php?aksi=reportAck'); pleaseWait();";
		$link3 = "loadUrl('index.php?aksi=reportExm'); pleaseWait();";
		$link4 = "loadUrl('index.php?aksi=reportHist'); pleaseWait();";
		$link5 = "";
	}

	if($CSpj->cekKadiv($userEmpNo) == 0  &&  $userJenisSpj != "admin" && $CSpj->cekCustomAprv($userIdSession) == 0 && $userJenisSpj != "CEO")//jika bukan kadiv/ceo, disabled acknowledge button
	{
		$btnClass2 = "btnSubMenuDis";
		$dis2 = "disabled";
	}
	if($userJenisSpj != "admin")// jika bukan kadiv HR, disabled check button
	{
		$btnClass3 = "btnSubMenuDis";
		$dis3 = "disabled";
		$btnClass5 = "btnSubMenuDis";
		$dis5 = "disabled";
	}
	if($CEmployee->detilDiv("040", "divhead") != $userEmpNo)// jika bukan kadiv Finance, disabled proces button
	{
		$btnClass1 = "btnSubMenuDis";
		$dis1 = "disabled";
	}
	$tpl->Assign("halaman2","> REPORT > ".$hal);
	$tpl->Assign("activeMenu","btnReport");
	
	$tpl->Assign('subMenu','<div style="font-weight:bold;font-size:9px;float:left;margin-top:7px;">>>&nbsp;&nbsp;</div>
      <button id="list Report" class="'.$btnClass.'" type="button" onclick="'.$link.'" title="Create & List SPJ Form">
        <table width="40" height="23">
            <tr>
                <td align="center">List</td> 
            </tr>
        </table>
      </button><button id="ack" class="'.$btnClass2.'" type="button" onclick="'.$link2.'" style="border-left:0px;"title="List SPJ Report for Acknowledge" '.$dis2.'>
        <table width="90" height="23">
            <tr>
                <td align="center">Acknowledge</td> 
            </tr>
        </table>
      </button><button id="ack" class="'.$btnClass3.'" type="button" onclick="'.$link3.'" style="border-left:0px;" title="List SPJ Report for Checked" '.$dis3.'>
			<table width="51" height="23">
				<tr>
					<td align="center">Check</td> 
				</tr>
			</table>
	 </button><!-- <button id="aprv" class="'.$btnClass1.'" type="button" onclick="'.$link1.'" style="border-left:0px;" title="List SPJ Form for Processed" '.$dis1.'>
        <table width="60" height="23">
            <tr>
                <td align="center">Process</td> 
            </tr>
        </table>
      </button>--><button id="ack" class="'.$btnClass4.'" type="button" onclick="'.$link4.'" style="border-left:0px;" title="List SPJ Report History Revise" >
			<table width="58" height="23">
				<tr>
					<td align="center">History</td> 
				</tr>
			</table>
	 </button>
	 <button id="ack" class="'.$btnClass5.'" type="button" onclick="'.$link5.'" style="border-left:0px;" title="List SPJ Report Summary" '.$dis5.'>
			<table width="58" height="23">
				<tr>
					<td align="center">Summary</td> 
				</tr>
			</table>
	 </button>');
	 
	  /*if($userJenisSpj == "admin")
	  {
		  $tpl->Assign('subMenuAdm','<button id="ack" class="'.$btnClass4.'" type="button" onclick="'.$link4.'" style="border-left:0px;" title="List SPJ Report for Administrator" >
			<table width="51" height="23">
				<tr>
					<td align="center">All</td> 
				</tr>
			</table>
	 </button>');
	  }*/
}

$tpl->printToScreen();
?>