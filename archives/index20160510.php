<?php
require_once("../config.php");

$tpl = new myTemplate("templates/halUtamaBelumLogin.html");
if($userIdLogin != "")
{
	$tpl = new myTemplate("templates/halUtamaSudahLogin.html");
}

if($userIdLogin == "" || $aksiGet == "sessionExpired")
{
	$tpl->AssignInclude("CONTENT_TENGAH","templates/login.html");
}
if($userIdLogin != "")
{
	$tpl->AssignInclude("CONTENT_TENGAH","templates/halArchives.html");
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halSelectButton.html");
	
	//**********************************************************
	// DIGUNAKAN UNTUK MEMUNCULKAN HALAMAN UNDER CONTRUCTION
	//**********************************************************
	if($userJenis != "admin")
	{
		//$tpl->AssignInclude("CONTENT_TENGAH", "../templates/underConstruction.html");
	}
	
	if($userIdLogin == "00011")
	{
		//$tpl->AssignInclude("CONTENT_TENGAH","templates/halArchives.html");
	}
}
if($aksiGet == "myFolder" || $aksiGet == "openSearchOwnFolder")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halFolder.html");
}
if($aksiGet == "otherShare")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halOtherShare.html");
}
if($aksiGet == "ownShared")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halOwnShared.html");
}
if($aksiGet == "subordinate")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halSubordinate.html");
}
if($aksiGet == "subordinateFolder" || $aksiGet == "openSearchSubordinateFolder" || $aksiGet == "openSubordinateFolder")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halSubordinateFolder.html");
}
if($aksiGet == "pilihBtnSubordinate")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halSubordinate.html");
}
if($aksiGet == "openSubordinateDailyAct" || $aksiGet == "openSubordinateDailyActBalik")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halDailyActMonth.html");
}
if($aksiGet == "search")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halSearch.html");
}
if($aksiGet == "openSearchFolder")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halFolder.html");
}
if($aksiGet == "openSearchShareFolder")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halOtherShare.html");
}
if($aksiGet == "password")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halPassword.html");
}
if($aksiGet == "dailyAct" || $aksiGet == "dailyActViewMonth")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halDailyActMonth.html");
}

if($aksiGet == "openJobDailyList")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halDailyAct.html");
}

if($aksiGet == "openSubordinateJobList")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halSubordinateDailyAct.html");
}

$tpl->prepare();

$tpl->Assign("idleAfter", $idleAfter);
$tpl->Assign("menuHome", $CPublic->menuHome(""));
$tpl->Assign("menuLogin", $CPublic->menuLogin("on"));
if($userIdLogin != "")
{
	$tpl->Assign("welcomeUsername", $welcomeUsername );
	$tpl->Assign("spanLogout", "<span class=\"spanLogout\" onClick=\"formLogout.submit();\" title=\"Logout from Andhika Portal\">(&nbsp;Logout&nbsp;)</span>");
	$tpl->Assign("menuHome", $CPublic->menuHome(""));
	$tpl->Assign("menuNews", $CPublic->menuNews(""));
	$tpl->Assign("menuEmployee", $CPublic->menuEmployee(""));
	$tpl->Assign("menuArchives", $CPublic->menuArchives("on"));
	$tpl->Assign("menuSetting", "");
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

if($aksiPost == "cekLogin")
{
	$tpl->Assign("aksiEcho", $CLogin->cekLogin($_POST, $CHistory, $CPublic));
}
if($aksiPost == "logout")
{
	$tpl->Assign("aksiEcho", $CLogin->logout($CPublic, $CHistory));
}

if($dologinGet == "0")
{
	$tpl->Assign("wrongPass", "<img src=\"../../picture/exclamation-red.png\" />&nbsp;Login failed!");
}
if($aksiGet == "sessionExpired")
{
	$tpl->Assign("wrongPass", "<img src=\"../../picture/exclamation-red.png\" />&nbsp;Your session is expired!");
}

if($aksiGet == "myFolder")
{
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;\">MY FOLDER</span>");
	
	$idHalTeksLvl = "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\" border=\"0\">
					<tr>
						<td colspan=\"3\" class=\"batasTitik\" height=\"10\"></td>
					</tr>
					<tr><td height=\"20\" colspan=\"3\">&nbsp;</td></tr>
					<tr>
						<td valign=\"bottom\" width=\"100\">
							<input type=\"hidden\" id=\"ideDipilih\" name=\"ideDipilih\" /> 
							<input type=\"hidden\" id=\"idFoldDipilih\" name=\"idFoldDipilih\"/> 
							<input type=\"hidden\" id=\"foldSub\" name=\"foldSub\" value=\"1\" />
							<input type=\"hidden\" id=\"halaman\"/>
							&nbsp;&nbsp;<span class=\"teksLvlFolder\">Level 1</span> <img src=\"../picture/Arrow-Bottom-Right-32.png\" class=\"gambarPanah\"/>
						</td>
						<td width=\"400\" align=\"left\">&nbsp;
							<button class=\"btnStandar\" id=\"btnNewFolder\" onclick=\"openThickboxWindow('','newFolder');\" style=\"width:100px;height:29px;\" title=\"Create New Folder\">
								<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\" border=\"0\">
								<tr>
									<td align=\"right\"><img src=\"../picture/Folder-blue-32.png\" height=\"20\"/> </td>
									<td align=\"center\">New Folder</td>
								</tr>
								</table>
							</button>
						</td>
						<td align=\"right\"><img src=\"../picture/Search2-32.png\" width=\"25\" style=\"vertical-align:bottom;\"/>
						<input type=\"text\" class=\"elementSearch\" id=\"paramText\" size=\"41\" style=\"height:16px;color:#333;\" onfocus=\"cariFolder(this.value);\" onkeyup=\"cariFolder(this.value);\">
						&nbsp;
						</td>
					</tr>
					</table>";
	$tpl->Assign("idHalTeksLvl", $idHalTeksLvl);
	
	$sql = $CKoneksi->mysqlQuery("SELECT * FROM tblfolder WHERE folderowner=".$userIdLogin." AND idfoldref='' AND deletests=0 ORDER BY namefold ASC");
	$jmlFolder = $CKoneksi->mysqlNRows($sql);
	
	$tpl->Assign("wktTungguBukFolder", $jmlFolder);
	$tpl->Assign("foldSub", "1");
	$tpl->Assign("idFoldRef", "");
	$tpl->Assign("srcMyFolder", "templates/halFolderList.php?foldSub=1&idFoldRef=");
	$tpl->Assign("btnZoomFrame", "<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" style=\"width:36px;height:36px;\" type=\"button\" onclick=\"frameSize('down');\">
                                <table class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\" border=\"0\" width=\"100%\" height=\"100%\">
                                    <tr>
                                        <td align=\"center\"><img id=\"imgDown\" src=\"../../picture/btnDown.png\" height=\"25\" border=\"0\"/> </td> 
                                    </tr><tr><td>&nbsp;</td></tr>
                                </table>
       						 </button>
							 <button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" style=\"width:36px;height:36px;\" type=\"button\" onclick=\"frameSize('up');\">
                                <table class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\" border=\"0\" width=\"100%\" height=\"100%\">
                                    <tr>
                                        <td align=\"center\"><img id=\"imgUp\" src=\"../../picture/btnUp.png\" height=\"25\" border=\"0\"/></td> 
                                    </tr><tr><td>&nbsp;</td></tr>
                                </table>
       						 </button>");
}
if($aksiGet == "ownShared")
{
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;\">OWN SHARED</span>");
	$tpl->Assign("userIdOwner", $userIdLogin);
	if($userIdLogin != "")
	{
		$tpl->Assign("menuEmpOwnShare", $CFolder->menuEmpOwnShare($userIdLogin));
	}
	$tpl->Assign("btnZoomFrame", "<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" style=\"width:36px;height:36px;\" type=\"button\" onclick=\"frameSize('down');\">
                                <table class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\" border=\"0\" width=\"100%\" height=\"100%\">
                                    <tr>
                                        <td align=\"center\"><img id=\"imgDown\" src=\"../../picture/btnDown.png\" height=\"25\" border=\"0\"/> </td> 
                                    </tr><tr><td>&nbsp;</td></tr>
                                </table>
       						 </button>
							 <button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" style=\"width:36px;height:36px;\" type=\"button\" onclick=\"frameSize('up');\">
                                <table class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\" border=\"0\" width=\"100%\" height=\"100%\">
                                    <tr>
                                        <td align=\"center\"><img id=\"imgUp\" src=\"../../picture/btnUp.png\" height=\"25\" border=\"0\"/></td> 
                                    </tr><tr><td>&nbsp;</td></tr>
                                </table>
       						 </button>");
}
if($aksiGet == "otherShare")
{
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;\">OTHER SHARE</span>");
	$tpl->Assign("userIdShared", $userIdLogin);
	
	/*$sql = $CKoneksi->mysqlQuery("SELECT * FROM tblfolder WHERE folderowner=".$userIdLogin." AND deletests=0 ORDER BY namefold ASC");
	
	SELECT * FROM tblauthorfold WHERE (empno=".$userEmpNo." OR empno=99999) AND folderowner=".$userIdOwnerGet." AND deletests=0 GROUP BY idefold ORDER BY namefold ASC
	
	$jmlFolder = $CKoneksi->mysqlNRows($sql);
	
	$tpl->Assign("wktTungguBukFolder", $jmlFolder);*/
	
	if($userIdLogin != "")
	{
		$tpl->Assign("menuEmpOtherShare", $CFolder->menuEmpOtherShare($userEmpNo, "", $CLogin));
	}
	$tpl->Assign("btnZoomFrame", "<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" style=\"width:36px;height:36px;\" type=\"button\" onclick=\"frameSize('down');\">
                                <table class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\" border=\"0\" width=\"100%\" height=\"100%\">
                                    <tr>
                                        <td align=\"center\"><img id=\"imgDown\" src=\"../../picture/btnDown.png\" height=\"25\" border=\"0\"/> </td> 
                                    </tr><tr><td>&nbsp;</td></tr>
                                </table>
       						 </button>
							 <button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" style=\"width:36px;height:36px;\" type=\"button\" onclick=\"frameSize('up');\">
                                <table class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\" border=\"0\" width=\"100%\" height=\"100%\">
                                    <tr>
                                        <td align=\"center\"><img id=\"imgUp\" src=\"../../picture/btnUp.png\" height=\"25\" border=\"0\"/></td> 
                                    </tr><tr><td>&nbsp;</td></tr>
                                </table>
       						 </button>");
}
if($aksiGet == "subordinate")
{
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;\">SUBORDINATE</span>");
	$tpl->Assign("judulList", ":: SUBORDINATE TREE ::");
}

if($aksiGet == "subordinateFolder" || $aksiGet == "openSubordinateFolder")
{
	$empNoGet = $_GET['empNo'];
	$empName = $CLogin->detilLoginByEmpno($empNoGet, "userfullnm");
	$tpl->Assign("judulList", ":: ".strtoupper( $empName." FOLDER LIST")." ::");
	$userIdOwner = $CLogin->detilLoginByEmpno($empNoGet, "userid");
	
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;\">SUBORDINATE FOLDER</span>");
	$tpl->Assign("userIdOwner", $userIdOwner);
	$tpl->Assign("foldSub", "1");
	$tpl->Assign("idFoldRef", "");
	
	$tpl->Assign("srcIframeSek", "templates/halSubordinateFolderList.php?userIdOwner=".$userIdOwner."&foldSub=1&idFoldRef=");
	$tpl->Assign("btnZoomFrame", "<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" style=\"width:36px;height:36px;\" type=\"button\" onclick=\"frameSize('down');\">
                                <table class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\" border=\"0\" width=\"100%\" height=\"100%\">
                                    <tr>
                                        <td align=\"center\"><img id=\"imgDown\" src=\"../../picture/btnDown.png\" height=\"25\" border=\"0\"/> </td> 
                                    </tr><tr><td>&nbsp;</td></tr>
                                </table>
       						 </button>
							 <button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" style=\"width:36px;height:36px;\" type=\"button\" onclick=\"frameSize('up');\">
                                <table class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\" border=\"0\" width=\"100%\" height=\"100%\">
                                    <tr>
                                        <td align=\"center\"><img id=\"imgUp\" src=\"../../picture/btnUp.png\" height=\"25\" border=\"0\"/></td> 
                                    </tr><tr><td>&nbsp;</td></tr>
                                </table>
       						 </button>");
}
if($aksiGet == "openSearchSubordinateFolder")
{
	$userIdOwner = $CFolder->detilFold($ideGet, "folderowner");
	$empName = $CLogin->detilLogin($userIdOwner, "userfullnm");
	$foldSub = $CFolder->detilFold($ideGet, "foldsub");
	$idFoldRef = $CFolder->detilFold($ideGet, "idfoldref");
	
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;\">SUBORDINATE FOLDER</span>");
	$tpl->Assign("judulList", ":: ".$empName." FOLDER LIST ::");
	$tpl->Assign("userIdOwner", $userIdOwner);
	$tpl->Assign("foldSub", $foldSub);
	$tpl->Assign("idFoldRef", $idFoldRef);
	
	$tpl->Assign("srcIframeSek", "templates/halSubordinateFolderList.php?userIdOwner=".$userIdOwner."&foldSub=".$foldSub."&idFoldRef=".$idFoldRef);
}

if($aksiGet == "openSubordinateDailyAct" || $aksiGet == "openSubordinateDailyActBalik")
{
	$empNoGet = $_GET['empNo'];
	
	if($aksiGet == "openSubordinateDailyAct")
	{
		$tgl = $CPublic->zerofill($CPublic->waktuServer("tanggal"), 2); 
		$bln = $CPublic->zerofill($CPublic->waktuServer("bulan"), 2); 
		$thn = $CPublic->waktuServer("tahun");
		$dateAct = $tgl."/".$bln."/".$thn;
		$monthParam = $bln."-".$thn;
		//$blnParam = $bln;
		$blnParam = $bln."-".$CPublic->detilBulanNamaAngka($bln, "eng");
	}
	if($aksiGet == "openSubordinateDailyActBalik")
	{
		$dateAct = $_GET['dateAct'];
		$tgl =  substr($dateAct,0,2);
		$bln =  substr($dateAct,3,2);
		$thn =  substr($dateAct,6,4);
		$monthParam = $bln."-".$thn;
		
		if($bln != "10")
		{
			$bln = str_replace("0","",$bln);
		}
		$blnParam = $CPublic->zerofill( $bln, 2) ."-".$CPublic->detilBulanNamaAngka($bln, "eng");
	}
	
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;\">SUBORDINATE DAILY ACTIVITY</span>");
	$tpl->Assign("dateAct", $dateAct);
	$tpl->Assign("empNo", $empNoGet);
	$tpl->Assign("userIdSelect", $CLogin->detilLoginByEmpno($empNoGet, "userid"));
	$tpl->Assign("menuTahun", $CPublic->menuTahunDB($thn));
	$tpl->Assign("menuBulan", $CPublic->menuBulanNamaAngka($blnParam, "eng"));
	$tpl->Assign("subordinateName", $CLogin->detilLoginByEmpno($empNoGet, "userfullnm"));
	
	$tpl->Assign("subordinateLbl", "<span style=\"text-decoration:underline;\">SUBORDINATE</span>");
	
	/*if($aksiGet == "openSubordinateDailyActBalik")
	{
		$tpl->Assign("srcMyFolder", "templates/halCalendarContainer.php?aksi=".$aksiGet."&dateAct=".$dateAct."&month=".$monthParam."&empNo=".$empNoGet);
		$tpl->Assign("load", "<img style=\"vertical-align:bottom;\" height=\"18\" src=\"../picture/ajax-loader23.gif\"/>&nbsp;&nbsp;<b>( Please Wait )</b>");
	}*/
	$tpl->Assign("srcMyFolder", "templates/halSelectButton.php");
}

if($aksiGet == "pilihBtnSubordinate")
{
	$empNoGet = $_GET['empNo'];
	
	$tpl->Assign("bodyOnload", " onload=\"openThickboxWindow('".$empNoGet."', 'pilihHalaman')\"");
}

if($aksiGet == "openSearchOwnFolder")
{
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;\">MY FOLDER</span>");
	
	$foldSub = $CFolder->detilFold($ideGet, "foldsub")+1;
	$idFoldRef = $CFolder->detilFold($ideGet, "idfold");
	$tpl->Assign("ideDipilih", $ideGet);
	$tpl->Assign("idFoldDipilih", $idFoldRef);
	$tpl->Assign("foldSub", $foldSub );
	$tpl->Assign("idFoldRef", $idFoldRef);

	$ideSek = $ideGet;
	$idFoldSek = $CFolder->detilFold($ideSek, "idfold"); // idfold sekarang 
	$idFoldRefSek = $CFolder->detilFold($ideSek, "idfoldref"); // idfold sekarang 
	$foldSubSek = $CFolder->detilFold($ideSek, "foldsub");
	$foldSubSet = $foldSubSek+1;
	$tipeKontenSek = $CFolder->detilFoldByIdFold($idFoldSek, "tipekonten");
	
	$folderNameMap = $CFolder->folderNameMap($idFoldSek, $foldSubSek);
	$levelSek = $foldSubSek + 1;
			
	$idHalTeksLvl.= "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\" border=\"0\">
					<tr>
						<td colspan=\"3\" class=\"batasTitik\" height=\"10\"></td>
					</tr>
					<tr><td height=\"20\" colspan=\"3\">".$folderNameMap."</td></tr>
					<tr>
						<td valign=\"bottom\" width=\"100\">
							<input type=\"hidden\" id=\"ideDipilih\" name=\"ideDipilih\" value=\"".$ideSek."\"/> 
							<input type=\"hidden\" id=\"idFoldDipilih\" name=\"idFoldDipilih\" value=\"".$idFoldRef."\"/> 
							<input type=\"hidden\" id=\"foldSub\" name=\"foldSub\" value=\"".$foldSub."\" /> 
							<input type=\"hidden\" id=\"halaman\"/>
							&nbsp;&nbsp;<span class=\"teksLvlFolder\">Level ".$levelSek."</span> <img src=\"../picture/Arrow-Bottom-Right-32.png\" class=\"gambarPanah\"/>
						</td>
						<td width=\"400\" align=\"left\">&nbsp;";
	 if($tipeKontenSek == "folder" || $idFoldSek == "")
	{
		$idHalTeksLvl.=" <button class=\"btnStandar\" id=\"btnNewFolder\" onclick=\"openThickboxWindow('','newFolder');\" style=\"width:100px;height:29px;\" title=\"Create New Folder\">
							<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\" border=\"0\">
							  <tr>
								<td align=\"right\"><img src=\"../picture/Folder-blue-32.png\" height=\"20\"/> </td>
								<td align=\"center\">New Folder</td>
							  </tr>
							</table>
						</button>";
		$textFieldCari = "<input type=\"text\" class=\"elementSearch\" id=\"paramText\" size=\"41\" style=\"height:16px;color:#333;\" onfocus=\"cariFolder(this.value);\" onkeyup=\"cariFolder(this.value);\">";
	}
	else if($tipeKontenSek == "file")
	{
		$idHalTeksLvl.="<button class=\"btnStandar\" onclick=\"openThickboxWindow('','newFile');\" style=\"width:80px;height:3129pxpx;\">
				<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\" title=\"Create New File\">
				  <tr>
					<td align=\"right\"><img src=\"../picture/Document-blue-32.png\" height=\"20\"/> </td>
					<td align=\"center\">New File</td>
				  </tr>
				</table>
			</button>";
		$textFieldCari = "<input type=\"text\" class=\"elementSearch\" id=\"paramText\" size=\"41\" style=\"height:16px;color:#333;\" onfocus=\"cariFile(this.value);\" onkeyup=\"cariFile(this.value);\">";
	}
	$idHalTeksLvl.="	</td>
						<td align=\"right\"><img src=\"../picture/Search2-32.png\" width=\"25\" style=\"vertical-align:bottom;\"/>
						".$textFieldCari."&nbsp;</td>
					</tr>
					</table>";
	$tpl->Assign("idHalTeksLvl", $idHalTeksLvl);
	
	$ideSek = $ideGet;
	$foldSubSek = $CFolder->detilFold($ideSek, "foldsub");
	$foldSubSet = $foldSubSek+1;
	$idFoldRefSek = $CFolder->detilFold($ideSek, "idfoldref");
	
    
	$btnBack = "<button class=\"btnStandar\" onclick=\"parent.pilihBtnBack('".$ideSek."', '".$foldSubSek."', '".$idFoldRefSek."', '');\" style=\"width:60px;height:31px;\">
        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
          <tr>
            <td align=\"center\" width=\"25\"><img src=\"../picture/Arrow-Left-blue-32.png\" height=\"20\"/> </td>
            <td align=\"center\">Back</td>
          </tr>
        </table>
    </button>&nbsp;&nbsp;";
	$tpl->Assign("btnBack", $btnBack);

	$tipeKonten = $CFolder->detilFold($ideGet, "tipekonten");
	if($tipeKonten == "folder")
	{
		$tpl->Assign("srcMyFolder", "templates/halFolderList.php?foldSub=".$foldSub."&idFoldRef=".$idFoldRef);
	}
	if($tipeKonten == "file")
	{
		$tpl->Assign("srcMyFolder", "templates/halFileList.php?foldSub=".$foldSub."&idFold=".$idFoldRef);
	}
}
if($aksiGet == "openSearchShareFolder")
{
	$userIdOwner = $CFolder->detilFold($ideGet, "folderowner");
	
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;\">OTHER SHARE</span>");
	
	if($userIdLogin != "")
	{
		$foldSub = $CFolder->detilFold($ideGet, "foldsub")+1;
		$idFoldRef = $CFolder->detilFold($ideGet, "idfold");
		$idAuthorFold = $CFolder->detilAuthorFoldByIdFold($ideGet, "idauthorfold");
		$tpl->Assign("idAuthorFold", $idAuthorFold);
		
		$ideSek = $ideGet;
		$idFoldSek = $CFolder->detilFold($ideSek, "idfold");
		$foldSubSek = $CFolder->detilFold($ideSek, "foldsub");
		$folderNameMap = $CFolder->sharedFolderNameMap($idAuthorFold, $idFoldSek, $foldSubSek);	
		
		$idHalTeksLvl = $folderNameMap;
		$tpl->Assign("idHalTeksLvl", $idHalTeksLvl);
		$tpl->Assign("menuEmpOtherShare", $CFolder->menuEmpOtherShare($userEmpNo, $userIdOwner, $CLogin));
		
		$tipeKonten = $CFolder->detilFold($ideGet, "tipekonten");
		
		if($tipeKonten == "folder")
		{
			$tpl->Assign("srcMyFolder", "templates/halOtherShareList.php?aksi=expand&foldSub=".$foldSub."&idFoldRef=".$idFoldRef."&idAuthorFold=".$idAuthorFold);
			$tpl->Assign("srcIframeSek", "templates/halOtherShareList.php?aksi=expand&foldSub=".$foldSub."&idFoldRef=".$idFoldRef."&idAuthorFold=".$idAuthorFold);
		}
		if($tipeKonten == "file")
		{
			$tpl->Assign("srcMyFolder", "templates/halOtherShareFileList.php?aksi=expand&foldSub=".$foldSub."&idFold=".$idFoldRef."&userIdOwner=".$userIdOwner."&idAuthorFold=".$idAuthorFold);
		}		
	}
}

if($aksiGet == "search")
{
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;\">SEARCH</span>");
}

if($aksiGet == "password")
{
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;\">PASSWORD</span>");
}

if($aksiGet == "dailyAct")
{
	$tglSek = $CPublic->zerofill($CPublic->waktuServer("tanggal"), 2); 
	$blnSek = $CPublic->zerofill($CPublic->waktuServer("bulan"), 2); 
	$dateAct = $tglSek."/".$blnSek."/".$CPublic->waktuServer("tahun");
	$monthParam = $blnSek."-".$CPublic->waktuServer("tahun");
	
	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;\">MY DAILY ACTIVITY</span>");
	$tpl->Assign("dateAct", $dateAct);
	$tpl->Assign("empNo", $userEmpNo);
	$tpl->Assign("userIdSelect", $userIdLogin);
	$tpl->Assign("menuTahun", $CPublic->menuTahunDB($CPublic->waktuServer("tahun")));
	$tpl->Assign("menuBulan", $CPublic->menuBulanNamaAngka($blnSek."-".$CPublic->detilBulanNamaAngka($blnSek, "eng"),"eng"));

	//$tpl->Assign("srcMyFolder", "templates/halCalendarContainer.php?month=".$monthParam."&empNo=".$userEmpNo);
	$tpl->Assign("srcMyFolder", "templates/halSelectButton.php");
}

if($aksiGet == "dailyActViewMonth")
{
	$dateActGet = $_GET['dateAct'];
	$tgl =  substr($dateActGet,0,2);
	$bln =  substr($dateActGet,3,2);
	$thn =  substr($dateActGet,6,4);
	$monthParam = $bln."-".$thn;
	
	if($bln != "10")
	{
		$bln = str_replace("0","",$bln);
	}

	$tpl->Assign("halaman2", "> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;\">MY DAILY ACTIVITY</span>");
	$tpl->Assign("menuTahun", $CPublic->menuTahunDB($thn));
	$tpl->Assign("menuBulan", $CPublic->menuBulanNamaAngka($CPublic->zerofill($bln,2)."-".$CPublic->detilBulanNamaAngka($bln, "eng"), "eng"));
	$tpl->Assign("dateAct", $dateAct);
	$tpl->Assign("empNo", $userEmpNo);
	$tpl->Assign("userIdSelect", $userIdLogin);

	/*$tpl->Assign("srcMyFolder", "templates/halCalendarContainer.php?aksi=".$aksiGet."&dateAct=".$dateActGet."&month=".$monthParam."&empNo=".$userEmpNo."&userIdSelect=".$userIdLogin);
	$tpl->Assign("load", "<img style=\"vertical-align:bottom;\" height=\"18\" src=\"../picture/ajax-loader23.gif\"/>&nbsp;&nbsp;<b>( Please Wait )</b>");*/
	$tpl->Assign("srcMyFolder", "templates/halSelectButton.php");
}

if($aksiGet == "openJobDailyList")
{
	$dateActGet = $_GET['dateAct'];
	$tgl =  substr($dateActGet,0,2);
	$bln =  substr($dateActGet,3,2);
	$thn =  substr($dateActGet,6,4);

	$statusRead = $CDailyAct->detilActByDay($userIdLogin, $tgl, $bln, $thn, "bosread");
	$statusApprove = $CDailyAct->detilActByDay($userIdLogin, $tgl, $bln, $thn, "bosapprove");
	$statusCuti = $CDailyAct->detilActByDay($userIdLogin, $tgl, $bln, $thn, "cuti");
	$statusSakit = $CDailyAct->detilActByDay($userIdLogin, $tgl, $bln, $thn, "sakit");

	$status = "";
	$btnNewAct = "<button class=\"btnStandar\" id=\"btnNewFolder\" onclick=\"openThickboxWindow('','newAct');\" style=\"width:96px;height:29px;\" title=\"Write A New Activity Today\">
                    <table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\" border=\"0\">
                    <tr>
                        <td align=\"center\"><img src=\"../picture/Folder-blue-32.png\" height=\"20\"/> </td>
                        <td align=\"center\">New Activity</td>
                    </tr>
                    </table>
                </button>";
	if($statusRead == "Y") // jika activity sudah dibuka / dibaca oleh atasan langsung
	{
		$status = "<span class=\"teksLvlFolder\" style=\"color:#666;\">STATUS</span> : <span class=\"teksLvlFolder\" style=\"text-decoration:underline;\">READ</span>&nbsp;";
	}
	if($statusApprove == "Y") // jika activity sudah di approve oleh atasan langsung
	{
		$status = "<span class=\"teksLvlFolder\" style=\"color:#666;\">STATUS</span> : <span class=\"teksLvlFolder\" style=\"text-decoration:underline;\">APPROVE</span>&nbsp;";
		$btnNewAct = "&nbsp;";
	}
	if($statusCuti == "Y") // jika activity sudah di approve oleh atasan langsung
	{
		$status = "<span class=\"teksLvlFolder\" style=\"color:#666;\">STATUS</span> : <span class=\"teksLvlFolder\" style=\"text-decoration:underline;\">LEAVE</span>&nbsp;";
		$btnNewAct = "&nbsp;";
	}
	if($statusSakit == "Y") // jika activity sudah di approve oleh atasan langsung
	{
		$status = "<span class=\"teksLvlFolder\" style=\"color:#666;\">STATUS</span> : <span class=\"teksLvlFolder\" style=\"text-decoration:underline;\">SICK</span>&nbsp;";
		$btnNewAct = "&nbsp;";
	}
	
	$statusRevisi = "";
	$idRevisi = $CDailyAct->detilActByDay($userIdLogin, $tgl, $bln, $thn, "idrevisi");
	if($idRevisi != "00000" && $idRevisi != "") // jika di activity terdapat revisi
	{
		$revisiKe = $CDailyAct->detilRevisi($idRevisi, "revisike");
		$statusRevisi = "&nbsp;|&nbsp;<span class=\"teksLvlFolder\" style=\"text-decoration:underline;\">R".$revisiKe."</span>";
	}
	
	$tpl->Assign("halaman2","> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;font-weight:normal;\">DAILY ACTIVITY</span>");
	$tpl->Assign("halaman", $aksiGet);
	$tpl->Assign("subordinateId", "");
	$tpl->Assign("dateAct", $dateActGet);
	$tpl->Assign("btnNewAct", $btnNewAct);
	$tpl->Assign("status", $status);
	$tpl->Assign("statusRevisi", "".$statusRevisi);
	
	$tpl->Assign("judulTitle", "&nbsp;:: MY DAILY ACTIVITY LIST ::");
	$hariLiburSqlServer = $CPublic->hariLiburSqlServer($koneksiOdbcId, $koneksiOdbc, $tgl, $bln, $thn);
	$jmlJob = $CDailyAct->jmlJob($CPublic, $userIdLogin, $tgl, $bln, $thn);

	if($hariLiburSqlServer == 1)
	{
		$tpl->Assign("judulTitle", "&nbsp;:: HOLIDAY ::");
	}
	if($hariLiburSqlServer == 1 && $jmlJob > 0)
	{
		$tpl->Assign("judulTitle", "&nbsp;:: MY DAILY ACTIVITY LIST ::");
	}
	
	$tpl->Assign("srcMyFolder", "templates/halDailyActList.php?halaman=".$aksiGet."&dateAct=".$dateActGet);
}
if($aksiGet == "openSubordinateJobList")
{
	$empNoGet = $_GET['empNo']; //no employee bawahan atau subordinate
	//$empName = $CLogin->detilLoginByEmpno($empNoGet, "userfullnm");
	$userIdSelect = $CLogin->detilLoginByEmpno($empNoGet, "userid");	
	$subordinateName = "<span style=\"text-decoration:underline;\">".$CLogin->detilLoginByEmpno($empNoGet, "userfullnm")."</span>";	
	$dateActGet = $_GET['dateAct'];
	$tgl =  substr($dateActGet,0,2);
	$bln =  substr($dateActGet,3,2);
	$thn =  substr($dateActGet,6,4);
	
	$btnApprove = '<button class="btnStandarDisabled" id="btnApprove" onclick="klikBtnApprove();" style="width:75px;height:29px;" disabled title="Approve Activity Today">
						<table cellpadding="0" cellspacing="0" width="100%" height="100%">
						<tr>
							<td align="center"><img src="../picture/Lock-blue-32.png" height="20"/> </td>
							<td align="center">Approve</td>
						</tr>
						</table>
					</button>';
	
	$statusApprove = $CDailyAct->detilActByDay($userIdSelect, $tgl, $bln, $thn, "bosapprove");
	$statusCuti = $CDailyAct->detilActByDay($userIdSelect, $tgl, $bln, $thn, "cuti");
	$statusSakit = $CDailyAct->detilActByDay($userIdSelect, $tgl, $bln, $thn, "sakit");
	$status = "<span class=\"teksLvlFolder\" style=\"color:#666;\">STATUS</span> : <span class=\"teksLvlFolder\" style=\"text-decoration:underline;\">DISAPPROVED</span>";
	if($statusApprove == "Y")
	{
		$btnApprove = "&nbsp;";
		$approve = "APPROVED";
		$cuti = "LEAVE";
		$sakit = "SICK";
		$disabled = "";
		if($statusApprove == "Y")
		{
			$btnApprove = '<button class="btnStandar" id="btnUnapprove" onclick="klikBtnUnapprove();" style="width:95px;height:29px;" title="Disapprove Activity Today">
						<table cellpadding="0" cellspacing="0" width="100%" height="100%">
						<tr>
							<td align="center"><img src="../picture/Unlock-blue-32.png" height="20"/> </td>
							<td align="center">Disapprove</td>
						</tr>
						</table>
					</button>';
			$stat = $approve;
		}
		if($statusCuti == "Y")
		{
			$btnApprove = "&nbsp;";
			$stat = $cuti;
		}
		if($statusSakit == "Y")
		{
			$btnApprove = "&nbsp;";
			$stat = $sakit;
		}
		
		$status = "<span class=\"teksLvlFolder\" style=\"color:#666;\">STATUS</span> : <span class=\"teksLvlFolder\" style=\"text-decoration:underline;\">".$stat."</span>";
	}
	
	$empAtasanLangsung = $CEmployee->cariAtasanLangsung($empNoGet);
	if($userEmpNo != $empAtasanLangsung )// jika bukan merupakan atasan langsung maka button approve dihilangkan
	{
		$btnApprove = "&nbsp;";
	}
	
	$statusRevisi = "";
	$idRevisi = $CDailyAct->detilActByDay($userIdSelect, $tgl, $bln, $thn, "idrevisi");
	if($idRevisi != "00000" && $idRevisi != "")
	{
		$revisiKe = $CDailyAct->detilRevisi($idRevisi, "revisike");
		$statusRevisi = "&nbsp;|&nbsp;<span class=\"teksLvlFolder\" style=\"text-decoration:underline;font-weight:normal;\">R".$revisiKe."</span>";
	}

	$tpl->Assign("halaman2","> <span class=\"teksMyFolder\" style=\"color:#369;text-decoration:underline;\">DAILY ACTIVITY</span>");
	$tpl->Assign("halaman", $aksiGet);
	$tpl->Assign("empNo", $empNoGet);
	$tpl->Assign("subordinateId", $userIdSelect);
	$tpl->Assign("dateAct", $dateActGet);
	$tpl->Assign("btnApprove", $btnApprove);
	$tpl->Assign("status", $status);
	$tpl->Assign("statusRevisi", "".$statusRevisi);
	
	$tpl->Assign("judulTitle", "&nbsp;:: ".$subordinateName." DAILY ACTIVITY LIST ::");
	$hariLiburSqlServer = $CPublic->hariLiburSqlServer($koneksiOdbcId, $koneksiOdbc, $tgl, $bln, $thn);
	$jmlJob = $CDailyAct->jmlJob($CPublic, $userIdSelect, $tgl, $bln, $thn);
	
	if($hariLiburSqlServer == 1)
	{
		$tpl->Assign("judulTitle", "&nbsp;:: HOLIDAY ::");
	}
	if($hariLiburSqlServer == 1 && $jmlJob > 0)
	{
		$tpl->Assign("judulTitle", "&nbsp;:: ".$subordinateName." DAILY ACTIVITY LIST ::");
	}
	
	$tpl->Assign("srcMyFolder", "templates/halSubordinateDailyActList.php?halaman=".$aksiGet."&dateAct=".$dateActGet."&subordinateId=".$userIdSelect."");
}
if($aksiGet == "openSubordinateJobList" && $userSubCustom == "Y")
{
	$subordinateId = $CLogin->detilLoginByEmpno($empNoGet, "userid");
	$dateActGet = $_GET['dateAct'];
	$tgl =  substr($dateActGet,0,2);
	$bln =  substr($dateActGet,3,2);
	$thn =  substr($dateActGet,6,4);	

	$btnApprove = "&nbsp;";
	if($CEmployee->detilSubCustomByUser($userIdLogin, $subordinateId, "dailyact_approve") == "Y")
	{
		$btnApprove = '<button class="btnStandarDisabled" id="btnApprove" onclick="klikBtnApprove();" style="width:75px;height:29px;" disabled title="Approve Activity Today">
						<table cellpadding="0" cellspacing="0" width="100%" height="100%">
						<tr>
							<td align="center"><img src="../picture/Lock-blue-32.png" height="20"/> </td>
							<td align="center">Approve</td>
						</tr>
						</table>
					</button>';
		$statusApprove = $CDailyAct->detilActByDay($userIdSelect, $tgl, $bln, $thn, "bosapprove");
		$statusCuti = $CDailyAct->detilActByDay($userIdSelect, $tgl, $bln, $thn, "cuti");
		$statusSakit = $CDailyAct->detilActByDay($userIdSelect, $tgl, $bln, $thn, "sakit");
		if($statusApprove == "Y")
		{
			if($statusCuti == "Y")
			{
				$btnApprove = "&nbsp;";
			}
			if($statusSakit == "Y")
			{
				$btnApprove = "&nbsp;";
			}
			$btnApprove = '<button class="btnStandar" id="btnUnapprove" onclick="klikBtnUnapprove();" style="width:95px;height:29px;" title="Disapprove Activity Today">
							<table cellpadding="0" cellspacing="0" width="100%" height="100%">
							<tr>
								<td align="center"><img src="../picture/Unlock-blue-32.png" height="20"/> </td>
								<td align="center">Disapprove</td>
							</tr>
							</table>
						</button>';
		}
	}
	
	$empAtasanLangsung = $CEmployee->cariAtasanLangsung($empNoGet);
	if($userEmpNo == $empAtasanLangsung )// jika merupakan atasan langsung maka button approve diadakan
	{
		$btnApprove = '<button class="btnStandarDisabled" id="btnApprove" onclick="klikBtnApprove();" style="width:75px;height:29px;" disabled title="Approve Activity Today">
						<table cellpadding="0" cellspacing="0" width="100%" height="100%">
						<tr>
							<td align="center"><img src="../picture/Lock-blue-32.png" height="20"/> </td>
							<td align="center">Approve</td>
						</tr>
						</table>
					</button>';
	}
	
	$tpl->Assign("btnApprove", $btnApprove);
}

$tpl->printToScreen();
?>