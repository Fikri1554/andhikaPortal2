<?php
require_once("../config.php");

if($halamanPost == "addUserHaveAccess")
{
	$html = "";
	
	$html.= $CEmployee->menuUserSubCustom_Superior();
	
	echo $html;
}

if($halamanPost == "pilihUserHaveAccess")
{
	$userIdSelect = $_POST['userIdSelect'];
	$html = "";
	
	$html.= $CEmployee->menuUserSubCustom_Subordinate($userIdSelect);
	
	echo $html;
}

if($halamanPost == "cekFolder" || $halamanPost == "cekDailyAct" || $halamanPost == "cekDirectSub" || $halamanPost == "cekApprove" || $halamanPost == "cekBtnSave")
{
	$idCustomSubPost = $_POST['idCustomSub'];
	$statusCentangPost = $_POST['statusCentang'];
	
	$statusCentang = "N";
	$statusBeriHapus = "Hapus";
	if($statusCentangPost == "true")
	{
		$statusCentang = "Y";
		$statusBeriHapus = "Beri";
	}
	
	$userFullNmHA = $CEmployee->detilSubCustom($idCustomSubPost, "userfullnm");
	$userFullNmSub = $CEmployee->detilSubCustom($idCustomSubPost, "sub_userfullnm");
	
	if($halamanPost == "cekFolder")
	{
		$CKoneksi->mysqlQuery("UPDATE tblsubcustom SET sub_folder='".$statusCentang."', updusrdt='".$CPublic->userWhoAct()."' WHERE idcustomsub='".$idCustomSubPost."' AND deletests=0");
	
		$CHistory->updateLog2($userIdLogin, $statusBeriHapus." Akses subordinate custom (Akses Folder <b>".$userFullNmSub."</b> Kepada <b>".$userFullNmHA."</b>) ");
	}
	if($halamanPost == "cekDailyAct")
	{
		$CKoneksi->mysqlQuery("UPDATE tblsubcustom SET sub_dailyact='".$statusCentang."', updusrdt='".$CPublic->userWhoAct()."' WHERE idcustomsub='".$idCustomSubPost."' AND deletests=0");
	
		$CHistory->updateLog2($userIdLogin, $statusBeriHapus." Akses subordinate custom (Akses Daily Activity <b>".$userFullNmSub."</b> Kepada <b>".$userFullNmHA."</b>) ");
	}
	if($halamanPost == "cekDirectSub")
	{
		$CKoneksi->mysqlQuery("UPDATE tblsubcustom SET dailyact_direct='".$statusCentang."', updusrdt='".$CPublic->userWhoAct()."' WHERE idcustomsub='".$idCustomSubPost."' AND deletests=0");
	
		$CHistory->updateLog2($userIdLogin, $statusBeriHapus." Akses subordinate custom (Akses Direct Subordinate <b>".$userFullNmSub."</b> Kepada <b>".$userFullNmHA."</b>) ");
	}
	if($halamanPost == "cekApprove")
	{
		$CKoneksi->mysqlQuery("UPDATE tblsubcustom SET dailyact_approve='".$statusCentang."', updusrdt='".$CPublic->userWhoAct()."' WHERE idcustomsub='".$idCustomSubPost."' AND deletests=0");
	
		$CHistory->updateLog2($userIdLogin, $statusBeriHapus." Akses subordinate custom (Akses Approve <b>".$userFullNmSub."</b> Kepada <b>".$userFullNmHA."</b>) ");
	}
	if($halamanPost == "cekBtnSave")
	{
		$CKoneksi->mysqlQuery("UPDATE tblsubcustom SET dailyact_btnsave='".$statusCentang."', updusrdt='".$CPublic->userWhoAct()."' WHERE idcustomsub='".$idCustomSubPost."' AND deletests=0");
	
		$CHistory->updateLog2($userIdLogin, $statusBeriHapus." Akses subordinate custom (Akses Button Save <b>".$userFullNmSub."</b> Kepada <b>".$userFullNmHA."</b>) ");
	}
}

if($halamanPost == "cekUserApp")
{
	$userIdHAccess = $_POST['userId'];
	$appName = $_POST['appName'];
	
	$nilai = "tidakada";
	$query=$CKoneksi->mysqlQuery("SELECT idapp FROM otherapp WHERE userid='".$userIdHAccess."' AND nmapp='".$appName."' AND deletests=0");
	$jmlRow = $CKoneksi->mysqlNRows($query);
	if($jmlRow > 0)
	{
		$nilai = "ada";
	}
	
	echo "<input type=\"hidden\" id=\"userAppAdaTidak\" name=\"userAppAdaTidak\" value=\"".$nilai."\">";
}

if($halamanPost == "cekNameDivisi")
{
	$nameDivisi = mysql_real_escape_string($_POST['nmDiv']);
	
	$nilai = "tidakada";
	$query=$CKoneksi->mysqlQuery("SELECT kddiv FROM tblmstdiv WHERE nmdiv='".$nameDivisi."' AND deletests=0");
	$jmlRow = $CKoneksi->mysqlNRows($query);
	if($jmlRow > 0)
	{
		$nilai = "ada";
	}
	
	echo "<input type=\"hidden\" id=\"divisiAdaTidak\" name=\"divisiAdaTidak\" value=\"".$nilai."\">";
}

if($halamanPost == "cekKpi")
{
	$kdDivGet = $_POST['kdDiv'];
	$nmKpiOneGet = mysql_real_escape_string($_POST['nmKpiOne']);
	$nmKpiTwoGet = mysql_real_escape_string($_POST['nmKpiTwo']);
	$nmKpiThreeGet = mysql_real_escape_string($_POST['nmKpiThree']);
	
	$kpiTwo = "";
	$kpiThree = "";
	if($nmKpiTwoGet != "")
	{
		$kpiTwo = "AND kpidua='".$nmKpiTwoGet."'";
	}
	if($nmKpiThreeGet != "")
	{
		$kpiTwo = "AND kpitiga='".$nmKpiThreeGet."'";
	}
	
	$nilai = "tidakada";
	$query=$CKoneksi->mysqlQuery("SELECT idkpi FROM tblkpi WHERE kddiv='".$kdDivGet."' AND kpisatu='".$nmKpiOneGet."' ".$kpiTwo." ".$kpiThree." AND deletests=0");
	$jmlRow = $CKoneksi->mysqlNRows($query);
	if($jmlRow > 0)
	{
		$nilai = "ada";
	}
	
	echo "<input type=\"hidden\" id=\"kpiAdaTidak\" name=\"kpiAdaTidak\" value=\"".$nilai."\">";
}

if($halamanPost == "cekMenuSetting" || $halamanPost == "cekBtnUser" || $halamanPost == "cekLogHistory" || $halamanPost == "cekCustomSub" || $halamanPost == "cekOtherApp" || $halamanPost == "cekKpiSetting" || $halamanPost == "cekMenuApplication" || $halamanPost == "cekBtnExportPrint")
{
	$userIdChoosePost = $_POST['userIdChoose'];
	$statusCentangPost = $_POST['statusCentang'];
	
	$statusCentang = "N";
	$statusBeriHapus = "Hapus";
	if($statusCentangPost == "true")
	{
		$statusCentang = "Y";
		$statusBeriHapus = "Beri";
	}
	
	$userFullNmChoose = $CEmployee->detilSubCustom($userIdChoosePost, "sub_userfullnm");
	
	if($halamanPost == "cekMenuSetting")
	{
		$CKoneksi->mysqlQuery("UPDATE login SET menusetting='".$statusCentang."', updusrdt='".$CPublic->userWhoAct()."' WHERE userid='".$userIdChoosePost."' AND deletests=0");
	
		$CHistory->updateLog2($userIdLogin, $statusBeriHapus." Akses Menu Setting (Kepada <b>".$userFullNmChoose."</b>) ");
	}
	if($halamanPost == "cekBtnUser")
	{
		$CKoneksi->mysqlQuery("UPDATE login SET btnuser='".$statusCentang."', updusrdt='".$CPublic->userWhoAct()."' WHERE userid='".$userIdChoosePost."' AND deletests=0");
	
		$CHistory->updateLog2($userIdLogin, $statusBeriHapus." Akses Button User (Kepada <b>".$userFullNmChoose."</b>) ");
	}
	if($halamanPost == "cekLogHistory")
	{
		$CKoneksi->mysqlQuery("UPDATE login SET loghistory='".$statusCentang."', updusrdt='".$CPublic->userWhoAct()."' WHERE userid='".$userIdChoosePost."' AND deletests=0");
	
		$CHistory->updateLog2($userIdLogin, $statusBeriHapus." Akses Button Log History (Kepada <b>".$userFullNmChoose."</b>) ");
	}
	if($halamanPost == "cekCustomSub")
	{
		$CKoneksi->mysqlQuery("UPDATE login SET subcustom='".$statusCentang."', updusrdt='".$CPublic->userWhoAct()."' WHERE userid='".$userIdChoosePost."' AND deletests=0");
	
		$CHistory->updateLog2($userIdLogin, $statusBeriHapus." Akses Button Custom Sub (Kepada <b>".$userFullNmChoose."</b>) ");
	}
	if($halamanPost == "cekOtherApp")
	{
		$CKoneksi->mysqlQuery("UPDATE login SET otherapp='".$statusCentang."', updusrdt='".$CPublic->userWhoAct()."' WHERE userid='".$userIdChoosePost."' AND deletests=0");
	
		$CHistory->updateLog2($userIdLogin, $statusBeriHapus." Akses Button Other App (Kepada <b>".$userFullNmChoose."</b>) ");
	}
	if($halamanPost == "cekKpiSetting")
	{
		$CKoneksi->mysqlQuery("UPDATE login SET kpisetting='".$statusCentang."', updusrdt='".$CPublic->userWhoAct()."' WHERE userid='".$userIdChoosePost."' AND deletests=0");
	
		$CHistory->updateLog2($userIdLogin, $statusBeriHapus." Akses Button KPI Setting (Kepada <b>".$userFullNmChoose."</b>) ");
	}
	if($halamanPost == "cekMenuApplication")
	{
		$CKoneksi->mysqlQuery("UPDATE login SET menuapplication='".$statusCentang."', updusrdt='".$CPublic->userWhoAct()."' WHERE userid='".$userIdChoosePost."' AND deletests=0");
	
		$CHistory->updateLog2($userIdLogin, $statusBeriHapus." Akses Menu Application (Kepada <b>".$userFullNmChoose."</b>) ");
	}
	if($halamanPost == "cekBtnExportPrint")
	{
		$CKoneksi->mysqlQuery("UPDATE login SET btnexportprint='".$statusCentang."', updusrdt='".$CPublic->userWhoAct()."' WHERE userid='".$userIdChoosePost."' AND deletests=0");
	
		$CHistory->updateLog2($userIdLogin, $statusBeriHapus." Akses Button Export / Print (Kepada <b>".$userFullNmChoose."</b>) ");
	}
}


if($halamanPost == "pilihMenuUser")
{
	$userIdPost = $_POST['userId'];
	
	$html = "";
	$html.= "<select class=\"elementMenu\" id=\"menuFold\" name=\"menuFold\" style=\"width:400px;height:29px;\" title=\"Choose Folder\">";
	$html.= "<option value=\"00000\">-- PLEASE SELECT FOLDER LEVEL ONE --</option>";
    $query = $CKoneksi->mysqlQuery("SELECT * FROM tblfolder WHERE foldsub='1' AND idfoldref='' AND folderowner='".$userIdPost."' AND deletests=0 ORDER BY namefold ASC");
	while($row = $CKoneksi->mysqlFetch($query))
	{
		$html.="<option value=\"".$row['idfold']."\">".$row['namefold']."</option>";
	}
	
    $html.= "</select>";
		
	echo $html;
}

if($halamanPost == "simpanFolderUserDataOld")
{
	$allNilaiPost = $_POST['allNilai'];
	
	$expAllNilaiPost = explode(":-:", $allNilaiPost);
	
	$idFold = $expAllNilaiPost[0];
	$idFoldRef = $expAllNilaiPost[1];
	$foldSub = $expAllNilaiPost[2];
	$folderOwner = $expAllNilaiPost[3];
	$expFoldName = explode("\\", str_replace("\\\\", "\\" ,$expAllNilaiPost[4]));
	$bykFoldName = (sizeof($expFoldName) - 1);
	$nmFold = mysql_real_escape_string($expFoldName[$bykFoldName]);
	
	$pathFoldName = str_replace("\\", "/", $expAllNilaiPost[4]);
	$linkFold = str_replace("//10.0.2.7/andhikaportal/archives/data/userdata", $pathArchives."data/userdata", mysql_real_escape_string($pathFoldName));
	$linkFolder = str_replace($nmFold, "", $linkFold);
	
	$tipeKonten = $expAllNilaiPost[5];
	
	$dateTime = $CPublic->dateTimeGabung();
	$tglbuat = $CPublic->indonesiaDate()." ".$CPublic->jamServer();
	$addUsrdt = $CPublic->userWhoAct();
	
	$fileFold = $folderOwner."-".$idFold."-".$dateTime;
	
	//echo $linkFold;	
	$queryCek = $CKoneksi->mysqlQuery("SELECT namefold FROM tblfolder WHERE idfoldref='".$idFoldRef."' AND namefold='".$nmFold."' AND folderowner=".$folderOwner." AND deletests=0 LIMIT 1");	
	$rowCek = $CKoneksi->mysqlFetch($queryCek);
	if($rowCek['namefold'] == "")
	{
		$CKoneksi->mysqlQuery("INSERT INTO tblfolder(idfold, idfoldref, foldsub, folderowner, namefold, filefold, pathfold, tipekonten, tglbuat, convFold, addusrdt) 
		VALUES 
		('".$idFold."', '".$idFoldRef."',  '".$foldSub."', '".$folderOwner."', '".$nmFold."', '".$fileFold."', '', '".$tipeKonten."', '".$tglbuat."', 'Y', '".$addUsrdt."');");
		$lastInsertId = mysql_insert_id();
		$CHistory->updateLog2($folderOwner, "Buat Folder baru (CONVERT FOLDER) (ide = <b>".$lastInsertId."</b>, nama folder = <b>".$nmFold."</b>)");
		
		 //JIKA CONVER FOLDER FORMAT FOLDERNYA MENGIKUTI FORMAT ANDHIKA PORTAL
		/*if(is_dir($pathArchives."/data/documentConvFold/LEVEL".$foldSub.""))
		{
			mkdir($pathArchives."/data/documentConvFold/LEVEL".$foldSub."/".$fileFold, 0700);
		}
		else
		{
			mkdir($pathArchives."/data/documentConvFold/LEVEL".$foldSub."", 0700);
			mkdir($pathArchives."/data/documentConvFold/LEVEL".$foldSub."/".$fileFold, 0700);
		}*/
		
		echo "SIMPAN FOLDER (".$idFold.") (".$nmFold.") KE DALAM DATABASE";
	}
}

if($halamanPost == "simpanOtherFolderUserDataOld")
{
	$allNilaiPost = $_POST['allNilai'];
	
	$expAllNilaiPost = explode(":-:", $allNilaiPost);
	
	$idFold = $expAllNilaiPost[0];
	$idFoldRef = $expAllNilaiPost[1];
	$foldSub = $expAllNilaiPost[2];
	$folderOwner = $expAllNilaiPost[3];
	$expFoldName = explode("\\", str_replace("\\\\", "\\" ,$expAllNilaiPost[4]));
	$bykFoldName = (sizeof($expFoldName) - 1);
	$nmFold = mysql_real_escape_string($expFoldName[$bykFoldName]);
	
	$pathFoldName = str_replace("\\", "/", $expAllNilaiPost[4]);
	$linkFold = str_replace("//10.0.2.7/andhikaportal/archives/data/userdata", $pathArchives."data/userdata", mysql_real_escape_string($pathFoldName));
	$linkFolder = str_replace($nmFold, "", $linkFold);
	
	//$nmFoldParent = $expFoldName[($bykFoldName-1)];
	
	$tipeKonten = $expAllNilaiPost[5];

	$dateTime = $CPublic->dateTimeGabung();
	$tglbuat = $CPublic->indonesiaDate()." ".$CPublic->jamServer();
	$addUsrdt = $CPublic->userWhoAct();
	
	$fileFold = $folderOwner."-".$idFold."-".$dateTime;
	
	//echo $linkFold;
	$queryCek = $CKoneksi->mysqlQuery("SELECT namefold FROM tblfolder WHERE idfoldref='".$idFoldRef."' AND namefold='".$nmFold."' AND folderowner=".$folderOwner." AND deletests=0 LIMIT 1");	
	$rowCek = $CKoneksi->mysqlFetch($queryCek);
	if($rowCek['namefold'] == "")
	{
		$CKoneksi->mysqlQuery("INSERT INTO tblfolder(idfold, idfoldref, foldsub, folderowner, namefold, filefold, pathfold, tipekonten, tglbuat, convFold, addusrdt) 
		VALUES 
		('".$idFold."', '".$idFoldRef."',  '".$foldSub."', '".$folderOwner."', '".$nmFold."', '".$fileFold."', '', '".$tipeKonten."', '".$tglbuat."', 'Y', '".$addUsrdt."');");
		$lastInsertId = mysql_insert_id();
		$CHistory->updateLog2($folderOwner, "Buat Folder baru (CONVERT FOLDER) (ide = <b>".$lastInsertId."</b>, nama folder = <b>".$nmFold."</b>)");
		
		 //JIKA CONVER FOLDER FORMAT FOLDERNYA MENGIKUTI FORMAT ANDHIKA PORTAL
		/*if(is_dir($pathArchives."/data/documentConvFold/LEVEL".$foldSub.""))
		{
			mkdir($pathArchives."/data/documentConvFold/LEVEL".$foldSub."/".$fileFold, 0700);
		}
		else
		{
			mkdir($pathArchives."/data/documentConvFold/LEVEL".$foldSub."", 0700);
			mkdir($pathArchives."/data/documentConvFold/LEVEL".$foldSub."/".$fileFold, 0700);
		}*/
		
		echo "BUAT FOLDER BARU (".$idFold.") (".$nmFold.") DI DALAM FOLDER (".$idFoldRef."), ";
		echo "SIMPAN FOLDER (".$idFold.") (".$nmFold.") KE DALAM DATABASE";
	}
}

if($halamanPost == "simpanFileUserDataOld")
{
	$allNilaiPost = $_POST['allNilai'];
	$expAllNilaiPost = explode(":-:", $allNilaiPost);
	
	$idDoc = $expAllNilaiPost[0];
	$idFoldRef = $expAllNilaiPost[1];
	
	$nmFile = mysql_real_escape_string($expAllNilaiPost[2]);
	$fileDocNew = mysql_real_escape_string($expAllNilaiPost[3]);
	$tipeFile = $expAllNilaiPost[4];
	$fileOwner = $expAllNilaiPost[5];
	$moveFile = $expAllNilaiPost[7]; // STATUS APAKAH FILE DIPINDAHKAN KE DALAM FOLDER "All Other File" ATAU TIDAK
	$userFullnmDB = $CLogin->detilLogin($userIdLogin, "userfullnm");
	$dateTime = $CPublic->dateTimeGabung();
	$wktUpload = $CPublic->waktuSek();
	$fileDocNew = $fileOwner."-".$idDoc."-".$idFoldRef."-".$dateTime."-".$fileOwner.".".$tipeFile;
	
	$pathFileName = str_replace("\\", "/", $expAllNilaiPost[6]);
	$linkFile = str_replace("//10.0.2.7/andhikaportal/archives/data/userdata", $pathArchives."data/userdata", mysql_real_escape_string($pathFileName));
	$fileCopy = str_replace("All Other File/", "", $linkFile).$nmFile.".".$tipeFile;
	$filePaste = $pathArchives."data/documentConvFold/LEVEL".$CFolder->detilFoldByIdFold($idFoldRef, "foldsub")."/".$CFolder->detilFoldByIdFold($idFoldRef, "filefold")."/".$fileDocNew;

	$queryCekk = $CKoneksi->mysqlQuery("SELECT namedoc FROM mstdoc WHERE idfold='".$idFoldRef."' AND namedoc='".$nmFile."' AND extdoc='".$tipeFile."' AND deletests=0 LIMIT 1");	
	$rowCekk = $CKoneksi->mysqlFetch($queryCekk);
	if($rowCekk['namedoc'] == "")
	{
		$CKoneksi->mysqlQuery("INSERT INTO mstdoc (iddoc, idfold, fileowner, namedoc, filedoc, pathdoc, extdoc, wktupload, lastuploadbyid, lastuploadbyname, convFold, addusrdt) 
		VALUES ('".$idDoc."', '".$idFoldRef."', '".$fileOwner."', '".$nmFile."', '".$fileDocNew."', '', '".$tipeFile."', '".$wktUpload."', '".$userIdLogin."', '".$userFullnmDB."', 'Y', '".$CPublic->userWhoAct()."');");
		$lastInsertId = mysql_insert_id();
		$CHistory->updateLog2($userIdLogin, "Buat File baru (CONVERT FOLDER) (ide = <b>".$lastInsertId."</b>, nama file = <b>".$nmFile."</b>)");
		
		
		// JIKA CONVER FOLDER FORMAT FOLDERNYA MENGIKUTI FORMAT ANDHIKA PORTAL
		//copy($fileCopy, $filePaste);
		
		echo "SIMPAN FILE (".$idDoc." | ".$idFoldRef.") (".$nmFile.") KE DALAM DATABASE";
	}
	
	/*$nmFile = $nmFile;
	$oldFileName = "";
	$gantiNameDoc = "N";
	$fileDocNew = $fileDocNew;
	
	if($moveFile == "iya")
	{
		$pjgFilePath = $expAllNilaiPost[9]; //JUMLAH KARAKTER FILE NAME BESERTA PATH NYA
		if($pjgFilePath >= 273)
		{
			$oldFileName = $nmFile;
			$nmFile = str_replace(".".$tipeFile, "", $expAllNilaiPost[8]); // REPLACE EXTENSION DENGAN KOSONG
			$gantiNameDoc = "Y";
			$fileDocNew = $expAllNilaiPost[8];
		}
	}
	
	$queryCekk = $CKoneksi->mysqlQuery("SELECT namedoc FROM mstdoc WHERE idfold='".$idFoldRef."' AND namedoc='".$nmFile."' AND extdoc='".$tipeFile."' AND deletests=0 LIMIT 1");	
	$rowCekk = $CKoneksi->mysqlFetch($queryCekk);
	if($rowCekk['namedoc'] == "")
	{
		$CKoneksi->mysqlQuery("INSERT INTO mstdoc (iddoc, idfold, fileowner, namedoc, oldnamedoc, gantinamedoc, filedoc, pathdoc, extdoc, wktupload, lastuploadbyid, lastuploadbyname, convFold, addusrdt) 
		VALUES ('".$idDoc."', '".$idFoldRef."', '".$fileOwner."', '".$nmFile."', '".$oldFileName."', '".$gantiNameDoc."', '".$fileDocNew."', '".$linkFile."', '".$tipeFile."', '".$wktUpload."', '".$userIdLogin."', '".$userFullnmDB."', 'Y', '".$CPublic->userWhoAct()."');");
		$lastInsertId = mysql_insert_id();
		$CHistory->updateLog2($userIdLogin, "Buat File baru (CONVERT FOLDER) (ide = <b>".$lastInsertId."</b>, nama file = <b>".$nmFile."</b>)");

		echo "SIMPAN FILE (".$idDoc." | ".$idFoldRef.") (".$nmFile.") KE DALAM DATABASE";
	}*/
}

if($halamanPost == "simpanFolderUserData")
{
	$allNilaiPost = $_POST['allNilai'];
	
	$expAllNilaiPost = explode(":-:", $allNilaiPost);
	
	$idFold = $expAllNilaiPost[0];
	$idFoldRef = $expAllNilaiPost[1];
	$foldSub = $expAllNilaiPost[2];
	$folderOwner = $expAllNilaiPost[3];
	
	$expFoldName = explode("\\", str_replace("\\\\", "\\" ,$expAllNilaiPost[4]));
	$bykFoldName = (sizeof($expFoldName) - 1);
	$nmFold = mysql_real_escape_string($expFoldName[$bykFoldName]);
	
	$pathFoldName = str_replace("\\", "/", $expAllNilaiPost[4]);
	$tipeKonten = $expAllNilaiPost[5];	
	$fileFold = $expAllNilaiPost[6];
	
	$dateTime = $CPublic->dateTimeGabung();
	$tglbuat = $CPublic->indonesiaDate()." ".$CPublic->jamServer();
	$addUsrdt = $CPublic->userWhoAct();
	
	$queryCek = $CKoneksi->mysqlQuery("SELECT namefold FROM tblfolder WHERE idfoldref='".$idFoldRef."' AND namefold='".$nmFold."' AND folderowner=".$folderOwner." AND deletests=0 LIMIT 1");	
	$rowCek = $CKoneksi->mysqlFetch($queryCek);
	if($rowCek['namefold'] == "")
	{
		$CKoneksi->mysqlQuery("INSERT INTO tblfolder(idfold, idfoldref, foldsub, folderowner, namefold, filefold, tipekonten, tglbuat, convFold, addusrdt) 
		VALUES 
		('".$idFold."', '".$idFoldRef."',  '".$foldSub."', '".$folderOwner."', '".$nmFold."', '".$fileFold."', '".$tipeKonten."', '".$tglbuat."', 'Y', '".$addUsrdt."');");
		$lastInsertId = mysql_insert_id();
		$CHistory->updateLog2($folderOwner, "Buat Folder baru (CONVERT FOLDER) (ide = <b>".$lastInsertId."</b>, nama folder = <b>".$nmFold."</b>)");
		
		echo "SIMPAN FOLDER (".$idFold.") (".$nmFold.") KE DALAM DATABASE";
	}
}

if($halamanPost == "simpanFileUserData")
{
	$allNilaiPost = $_POST['allNilai'];
	$expAllNilaiPost = explode(":-:", $allNilaiPost);
	
	$idDoc = $expAllNilaiPost[0];
	$idFoldRef = $expAllNilaiPost[1];
	
	$nmFile = mysql_real_escape_string($expAllNilaiPost[2]);
	$fileDocNew = mysql_real_escape_string($expAllNilaiPost[3]);
	$tipeFile = $expAllNilaiPost[4];
	$fileOwner = $expAllNilaiPost[5];
	$moveFile = $expAllNilaiPost[7]; // STATUS APAKAH FILE DIPINDAHKAN KE DALAM FOLDER "All Other File" ATAU TIDAK
	$userFullnmDB = $CLogin->detilLogin($userIdLogin, "userfullnm");
	$dateTime = $CPublic->dateTimeGabung();
	$wktUpload = $CPublic->waktuSek();
	
	$pathFileName = str_replace("\\", "/", $expAllNilaiPost[6]);
	$linkFile = str_replace("//10.0.2.7/andhikaportal/archives/data/userdata", $pathArchives."data/userdata", mysql_real_escape_string($pathFileName));
	$fileCopy = str_replace("All Other File/", "", $linkFile).$nmFile.".".$tipeFile;
	$filePaste = $pathArchives."data/documentConvFold/LEVEL".$CFolder->detilFoldByIdFold($idFoldRef, "foldsub")."/".$CFolder->detilFoldByIdFold($idFoldRef, "filefold")."/".$fileDocNew;
	if($moveFile == "iya") // JIKA FILE AKAN DIMASUK KEDALAM FOLDER "ALLOTHERFILE"
	{
		$fileCopy = str_replace("All Other File/", "", $linkFile);
	}

	$queryCekk = $CKoneksi->mysqlQuery("SELECT namedoc FROM mstdoc WHERE idfold='".$idFoldRef."' AND namedoc='".$nmFile."' AND extdoc='".$tipeFile."' AND deletests=0 LIMIT 1");	
	$rowCekk = $CKoneksi->mysqlFetch($queryCekk);
	if($rowCekk['namedoc'] == "")
	{
		$CKoneksi->mysqlQuery("INSERT INTO mstdoc (iddoc, idfold, fileowner, namedoc, filedoc, extdoc, wktupload, lastuploadbyid, lastuploadbyname, convFold, addusrdt) 
		VALUES ('".$idDoc."', '".$idFoldRef."', '".$fileOwner."', '".$nmFile."', '".$fileDocNew."', '".$tipeFile."', '".$wktUpload."', '".$userIdLogin."', '".$userFullnmDB."', 'Y', '".$CPublic->userWhoAct()."');");
		$lastInsertId = mysql_insert_id();
		$CHistory->updateLog2($userIdLogin, "Buat File baru (CONVERT FOLDER) (ide = <b>".$lastInsertId."</b>, nama file = <b>".$nmFile."</b>)");
				  
		echo "SIMPAN FILE (".$idDoc." | ".$idFoldRef.") (".$nmFile.") KE DALAM DATABASE";
	}
}


if($halamanPost == "finishFileUserData")
{
	echo "SELESAI TRANSFER FILE";
}
if($halamanPost == "finishFolderUserData")
{
	echo "SELESAI TRANSFER FOLDER";
}

if($halamanPost == "gagalUploadTanpaExt")
{
	$allNilaiPost = $_POST['allNilai'];
	$expAllNilai = explode(":-:", $allNilaiPost);
	
	$nmFile = mysql_real_escape_string($expAllNilai[0]);
	$pathFileName = str_replace("\\", "/", $expAllNilai[1]);

	$CHistory->updateConvFoldGagal($CPublic, "Nama File : ".$nmFile." \r\nLokasi File : ".$pathFileName);
}

?>