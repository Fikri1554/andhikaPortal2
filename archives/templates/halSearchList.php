<?php
require_once("../../config.php");
$pathFolder="../data/document/";
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<body>
<table width="100%">
<?php
$aksiGet = $_GET['aksi'];
$paramTextGet = mysql_real_escape_string( $_GET['paramText'] );
$folderFileGet = $_GET['folderFile'];

$html = "";
$urutan = 1;

if($aksiGet == "cariStandar")
{
	// cari query folder bawahan 
	//$empNoUserBawahan = $CEmployee->bawahanEmp($userEmpNo);
	$empNoUserBawahan = $CEmployee->bawahanEmpPunyaLogin($userEmpNo);
	
	$explodee = explode("-", $empNoUserBawahan);
	$jmlExplodee = count(explode("-", $empNoUserBawahan));
	for($i=0; $i<=$jmlExplodee; $i++)
	{
		$userIdBawahan = $CLogin->detilLoginByEmpno($explodee[$i], "userid");
		if($explodee[$i] != "")
		{
			if($i == 0)
			{
				$folderUserBawahan.= " folderowner='".$userIdBawahan."'";
			}
			else
			{
				$folderUserBawahan.= " OR folderowner='".$userIdBawahan."'";
			}
		}
	}
	
	if($folderFileGet == "folder")
	{
		//query gabungan cari di folder sendiri dan folder yang di share ke userlogin
		/*$query = $CKoneksi->mysqlQuery("(SELECT convert('00000000000', unsigned) AS idauthorfold, ide, namefold, folderowner, 'milik' AS statusfolder FROM tblfolder WHERE namefold LIKE '%".$paramTextGet."%' AND (folderowner='".$userIdLogin."' ".$folderUserBawahan.") AND deletests=0)
		UNION 
		(SELECT idauthorfold, idefold, namefold, folderowner, 'share' AS statusfolder FROM tblauthorfold WHERE namefold LIKE '%".$paramTextGet."%' AND empno='".$userEmpNo."' AND deletests=0)
		ORDER BY namefold;");*/
		
		if($folderUserBawahan != "") // JIKA PUNYA BAWAHAN
		{
			$query = $CKoneksi->mysqlQuery("
(SELECT convert('00000000000', unsigned) AS idauthorfold, ide, namefold, folderowner, 'milik' AS statusfolder FROM tblfolder WHERE namefold LIKE '%".$paramTextGet."%' AND (folderowner='".$userIdLogin."') AND deletests=0) 
UNION 
(SELECT convert('00000000000', unsigned) AS idauthorfold, ide, namefold, folderowner, 'subordinate' AS statusfolder FROM tblfolder WHERE namefold LIKE '%".$paramTextGet."%' AND (".$folderUserBawahan.") AND deletests=0) 
UNION 
(SELECT idauthorfold, idefold, namefold, folderowner, 'othershare' AS statusfolder FROM tblauthorfold WHERE namefold LIKE '%".$paramTextGet."%' AND empno='".$userEmpNo."' AND deletests=0) 
ORDER BY namefold;");
		}
		if($folderUserBawahan == "") // JIKA TIDAK PUNYA BAWAHAN
		{
			$query = $CKoneksi->mysqlQuery("
(SELECT convert('00000000000', unsigned) AS idauthorfold, ide, namefold, folderowner, 'milik' AS statusfolder FROM tblfolder WHERE namefold LIKE '%".$paramTextGet."%' AND (folderowner='".$userIdLogin."') AND deletests=0) 
UNION 
(SELECT idauthorfold, idefold, namefold, folderowner, 'othershare' AS statusfolder FROM tblauthorfold WHERE namefold LIKE '%".$paramTextGet."%' AND empno='".$userEmpNo."' AND deletests=0) 
ORDER BY namefold;");	
		}
		
		while($row = $CKoneksi->mysqlFetch($query))
		{
			$idfold = $CFolder->detilFold($row['ide'], "idfold");
			$tglBuat = $CFolder->detilFold($row['ide'], "tglbuat"); // idefold diganti menjadi ide karena memakai union jadi diambil field pertama
			$tipeKonten = $CFolder->detilFold($row['ide'], "tipekonten");
			
			if($tipeKonten == "folder") // jika tipe konten folder maka ambil jumlah folder yang berada di level setelahnya
			{
				$jmlIsi = $CFolder->jmlFolder($idfold);
			}
			else if($tipeKonten == "file") // jika tipe konten file maka ambil jumlah file yang berada di level setelahnya
			{
				$jmlIsi = $CFile->jmlFile($idfold);
			}
			
			if($row['statusfolder'] == "milik")
			{
				$nameFolderOwner = $CLogin->detilLogin($row['folderowner'], "userfullnm");
				$bgRow = "";
			}
			if($row['statusfolder'] == "subordinate")
			{
				$nameFolderOwner = $CLogin->detilLogin($row['folderowner'], "userfullnm");
				$bgRow = " style=\"background-repeat:no-repeat;background-position:right;\" background=\"../../picture/imgSubordinate.png\"";
			}
			if($row['statusfolder'] == "othershare")
			{
				$nameFolderOwner = $CLogin->detilLogin($row['folderowner'], "userfullnm");
				$bgRow = " style=\"background-repeat:no-repeat;background-position:right;\" background=\"../../picture/imgShared.png\"";
			}
			
			$html.="<tr onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\">
						<td class=\"tdMyFolder\">
							<table width=\"100%\" border=\"0\">
							<tr>
								<td width=\"5%\" align=\"center\" style=\"font-size:22px;color:#000080;font-weight:bold;font-family:Tahoma;\">".$urutan++."</td>
								<td width=\"85%\">
									<table width=\"100%\" class=\"fontMyFolderList\" ".$bgRow.">
									<tr>
										<td width=\"100\">Name</td><td width=\"10\">:</td>
										<td>
											<span style=\"color:#000080;\">".$row['namefold']."</span>
										</td>
									</tr>
									<tr>
										<td>Created date</td>
										<td>:</td><td style=\"color:#000080;\">".$tglBuat."</td>
									</tr>
									<tr>
										<td>Content / Owner</td><td>:</td>
										<td style=\"color:#000080;\">".ucfirst($tipeKonten)." (<b>".$jmlIsi."</b>) / <b>".$nameFolderOwner."</b></td>
									</tr>
									</table>
								</td>
								<td width=\"10%\" align=\"right\">";
			if($row['statusfolder'] == "milik")
			{
				$html.= tombolFolderMilik($row['ide']);
			}
			if($row['statusfolder'] == "subordinate")
			{
				$html.= tombolFolderSubordinate($row['ide']);
			}
			if($row['statusfolder'] == "othershare")
			{
				$aksesOpen = $CFolder->detilAuthorFold($row['idauthorfold'], "expand");
				$html.= tombolFolderOtherShare($row['ide'], $aksesOpen);
			}
									
			$html.="			&nbsp;
								</td>
							</table>
						</td>
					</tr>";
		}
		echo $html;
	}
	if($folderFileGet == "file")
	{
		/* ##### START MODUL UNTUK MENCARI FILE YANG DI SHARE ####*/
		$urutanIdFoldShare = 0;
		$query1 = $CKoneksi->mysqlQuery("SELECT idauthorfold, empno, nama, idefold, folderowner FROM tblauthorfold WHERE (empno=".$userEmpNo." OR empno=99999) AND deletests=0;");
		while($row1 = $CKoneksi->mysqlFetch($query1))
		{
			$urutanIdFoldShare++;
			$idFold = $CFolder->detilFold($row1['idefold'], "idfold");
			$pjgIdFold = strlen($idFold);
			if($urutanIdFoldShare == 1)
			{
				$ideFoldShare.= " SUBSTR(idfold, 1, ".$pjgIdFold.") = '".$idFold."'";
			}
			else
			{
				$ideFoldShare.= " OR SUBSTR(idfold, 1, ".$pjgIdFold.") = '".$idFold."'";
			}
			$kumpulanIdeFoldShare = " AND (".$ideFoldShare.") ";
		}
		
		if($kumpulanIdeFoldShare != "")
		{
			$urutanIdeFileShare = 0;
			$query3 = $CKoneksi->mysqlQuery("SELECT ide FROM mstdoc WHERE namedoc LIKE '%".$paramTextGet."%' ".$kumpulanIdeFoldShare." AND deletests=0;");
			while($row3 = $CKoneksi->mysqlFetch($query3))
			{
				$urutanIdeFileShare++;
				if($urutanIdeFileShare == 1)
				{
					$ideFileShare .= " ide = ".$row3['ide'];
				}
				else
				{
					$ideFileShare .= " OR ide = ".$row3['ide'];
				}
				$kumpulanIdeFileShare = " OR ( ".$ideFileShare." )";
			}
		}
		/* ##### END MODUL UNTUK MENCARI FILE SUBORDINATE ####*/

		/* ##### START MODUL UNTUK MENCARI FILE SUBORDINATE ####*/
		$userIdBawahan = $CEmployee->bawahanUserIdByBosEmpno($userEmpNo);
		$explodee = explode("-", $userIdBawahan);
		$jmlExplodee = count(explode("-", $userIdBawahan));
		for($i=0; $i<=$jmlExplodee; $i++)
		{
			if($explodee[$i]!="")
			{
				if($i == 0)
				{
					$fileOwnerBawahan.= " fileowner='".$explodee[$i]."'";
				}
				else
				{
					$fileOwnerBawahan.= " OR fileowner='".$explodee[$i]."'";
				}
			}
		}
		if($userIdBawahan != "") // jika tidak punya bawahan
		{
			$allFileOwnerBawahan = "AND (".$fileOwnerBawahan.")";
		}
		
		if($allFileOwnerBawahan != "")
		{
			$urutanIdeFileBawahan = 0;
			$query2 = $CKoneksi->mysqlQuery("SELECT * FROM mstdoc WHERE namedoc LIKE '%".$paramTextGet."%' ".$allFileOwnerBawahan." AND deletests=0;");
			while($row2 = $CKoneksi->mysqlFetch($query2))
			{
				$urutanIdeFileBawahan++;
				if($urutanIdeFileBawahan == 1)
				{
					$ideFileBawahan .= " ide = ".$row2['ide'];
				}
				else
				{
					$ideFileBawahan .= " OR ide = ".$row2['ide'];
				}
				$kumpulanIdeFileBawahan = " OR ( ".$ideFileBawahan." )";
			}
		}
		/* ##### END MODUL UNTUK MENCARI FILE SUBORDINATE ####*/
		
		$query = $CKoneksi->mysqlQuery("SELECT * FROM mstdoc WHERE namedoc LIKE '%".$paramTextGet."%' AND (fileowner='".$userIdLogin."') ".$kumpulanIdeFileBawahan." ".$kumpulanIdeFileShare." AND deletests=0;");
		while($row = $CKoneksi->mysqlFetch($query))
		{
			//--------------------------------------------------------------------------------------------------------------------------
			$idFold = $CFile->detilFile($row['ide'], "idfold");
			$fileFold = $CFolder->detilFoldByIdFold($idFold, "filefold");
			$foldSub = $CFolder->detilFoldByIdFold($idFold, "foldsub");
			$levelFolder="LEVEL".$foldSub;
			
			$hrefOpenFile = "<a id=\"hrefOpenFile".$row['ide']."\" href=\"".$pathFolder.$levelFolder."/".$fileFold."/".$row['filedoc']."\"></a>";
			$clickBtnOpen = "document.getElementById('hrefOpenFile".$row['ide']."').click();";
			
			$iconShow = "../../picture/".$CFile->detilExtension($row['extdoc'] , "iconekstension");
			if($row['extdoc'] == "pdf")
			{
				$clickBtnOpen = "parent.openPdf('".$pathFolder.$levelFolder."/".$fileFold."/".$row['filedoc']."');";
			}
			
			$expWktUpload = explode(" ", $row['wktupload']);
			$expWktUpload1 = explode("-", $expWktUpload[0]);
			$expWktUpload2 = $expWktUpload[1];
			
			$tglUpload = $expWktUpload1[2];
			$blnUpload = ucfirst( strtolower( $CPublic->bulanSetengah( $expWktUpload1[1], "ind") ) );
			$thnUpload = $expWktUpload1[0];
			
			$wktUpload = $tglUpload." ".$blnUpload." ".$thnUpload." ".$expWktUpload2;
			
			$fileOwner = $CLogin->detilLogin($row['fileowner'], "userfullnm");
			
			$html.="<tr onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\">
				<td class=\"tdMyFolder\">
					<table width=\"100%\" boder=\"0\">
					<tr>
						<td width=\"5%\" align=\"center\" style=\"font-size:22px;color:#000080;font-weight:bold;font-family:Tahoma;\">".$urutan++."</td>
						<td width=\"55%\">
							<table width=\"100%\" class=\"fontMyFolderList\" ".$bgRow.">
							<tr>
								<td width=\"110\">Name</td>
								<td width=\"10\">:</td>
								<td style=\"color:#000080;\">".$row['namedoc']."</td>
							</tr>
							<tr>
								<td>Upload date</td>
								<td>:</td>
								<td style=\"color:#000080;\">".$wktUpload."</td>
							</tr>
							<tr>
								<td>Type file / Owner</td><td>:</td>
								<td style=\"color:#000080;\">".$row['extdoc']." / <b>".$fileOwner."</b></td>
							</tr>
							</table>
						</td>
						<td width=\"40%\" align=\"right\">".$hrefOpenFile."";
			if($row['fileowner'] == $userIdLogin)
			{
				$html.=tombolFileMilik($row['ide'], $clickBtnOpen, $iconShow);
			}
			if($row['fileowner'] != $userIdLogin)
			{
				$html.=tombolFileBukanMilik($row['ide'], $clickBtnOpen, $iconShow);
			}	
		 	$html.="	</td>
					</tr>
					
					</table>
				</td>
			</tr>";
		}
		echo $html;
	}
}

if($aksiGet == "cariUnauthorized")
{
	/* ##### START MODUL UNTUK MENCARI FOLDER SUBORDINATE #### */
	$empNoUserBawahan = $CEmployee->bawahanEmpPunyaLogin($userEmpNo);//cari bawahan berdasarkan empno user yang login
	
	$explodee = explode("-", $empNoUserBawahan); // pisahkan empno bawahan yang didapat berdasarkan "-"
	$jmlExplodee = count(explode("-", $empNoUserBawahan)); // jumlah empno bawahan yang dibawahi
	for($i=0; $i<=$jmlExplodee; $i++)
	{
		$userIdBawahan = $CLogin->detilLoginByEmpno($explodee[$i], "userid"); // cari userid bawahan berdasarkan parameter empno
		if($userIdBawahan!="")
		{
			$folderUserBawahan2.=" AND folderowner!='".$userIdBawahan."'"; // kumpulan folderowner di tblfolder yang bukan punya bawahan
		}
	}
	/* ##### END MODUL UNTUK MENCARI FOLDER SUBORDINATE #### */
	
	/* ##### START MODUL UNTUK MENCARI FOLDER YANG DISHARE #### */
	$urutanIdFoldShare = 0;
	//query dibawah untuk mencari di tblauthorfold folder manakah yang dishare ke user yang sedang login
	$query1 = $CKoneksi->mysqlQuery("SELECT idauthorfold, empno, nama, idefold, folderowner FROM tblauthorfold WHERE (empno=".$userEmpNo." OR empno=99999) AND deletests=0");
	while($row1 = $CKoneksi->mysqlFetch($query1))
	{
		$urutanIdFoldShare++;
		$idFold = $CFolder->detilFold($row1['idefold'], "idfold"); // idfold dari folder yang dishare ke user login
		$pjgIdFold = strlen($idFold); // panjang karakter idfold misal : (1.1) panjang 3, (1.1.1) panjang 5 dst
		
		if($urutanIdFoldShare == 1)
		{
			$ideFoldShare.= " SUBSTR(idfold, 1, ".$pjgIdFold.") != '".$idFold."'"; 
		}
		else
		{
			$ideFoldShare.= " AND SUBSTR(idfold, 1, ".$pjgIdFold.") != '".$idFold."'";
		}
		
		$kumpulanIdeFoldShare = $ideFoldShare;// cari idfold selain yang dishare ke userlogin
	}
	
	if($kumpulanIdeFoldShare!="")
	{
		$kumpulanIdeFoldShare = "AND(".$kumpulanIdeFoldShare.")";
	}
	
	if($folderFileGet == "folder")
	{
		//query cari folder orang lain berdasarkan $paramTextGet yang userlogin tidak punya akses
		$query = $CKoneksi->mysqlQuery("SELECT * FROM tblfolder WHERE namefold LIKE '%".$paramTextGet."%' 
AND (folderowner!='".$userIdLogin."' ".$folderUserBawahan2.") ".$kumpulanIdeFoldShare." AND deletests=0;");
		while($row = $CKoneksi->mysqlFetch($query))
		{
			$tglBuat = $row['tglbuat']; // idefold diganti menjadi ide karena memakai union jadi diambil field pertama
			$tipeKonten = $row['tipekonten'];
			
			if($tipeKonten == "folder") // jika tipe konten folder maka ambil jumlah folder yang berada di level setelahnya
			{
				$jmlIsi = $CFolder->jmlFolder($row['idfold']);
			}
			else if($tipeKonten == "file") // jika tipe konten file maka ambil jumlah file yang berada di level setelahnya
			{
				$jmlIsi = $CFile->jmlFile($row['idfold']);
			}
			
			$nameFolderOwner = $CLogin->detilLogin($row['folderowner'], "userfullnm");
			$bgRow = " style=\"background-repeat:no-repeat;background-position:right;\" background=\"../../picture/imgNotShared.png\"";
			
			$html.="<tr onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\">
						<td class=\"tdMyFolder\">
							<table width=\"100%\" border=\"0\">
							<tr>
								<td width=\"5%\" align=\"center\" style=\"font-size:22px;color:#000080;font-weight:bold;font-family:Tahoma;\">".$urutan++."</td>
								<td width=\"85%\">
									<table width=\"100%\" class=\"fontMyFolderList\" ".$bgRow.">
									<tr>
										<td width=\"100\">Name</td><td width=\"10\">:</td>
										<td>
											<span style=\"color:#000080;\">".$row['namefold']."</span>
										</td>
									</tr>
									<tr>
										<td>Created date</td>
										<td>:</td><td style=\"color:#000080;\">".$tglBuat."</td>
									</tr>
									<tr>
										<td>Content / Owner</td><td>:</td>
										<td style=\"color:#000080;\">".ucfirst($tipeKonten)." (<b>".$jmlIsi."</b>) / <b>".$nameFolderOwner."</b></td>
									</tr>
									</table>
								</td>
								<td width=\"10%\" align=\"right\">&nbsp;</td>
							</table>
						</td>
					</tr>";
		}
		echo $html;
	}
	if($folderFileGet == "file")
	{
		/* ##### START MODUL UNTUK MENCARI FILE SELAIN SUBORDINATE ####*/
		$userIdBawahan = $CEmployee->bawahanUserIdByBosEmpno($userEmpNo);
		$explodee = explode("-", $userIdBawahan);
		$jmlExplodee = count(explode("-", $userIdBawahan));
		for($i=0; $i<=$jmlExplodee; $i++)
		{
			if($explodee[$i]!="")
			{
				if($i == 0)
				{
					$fileOwnerBawahan.= " fileowner='".$explodee[$i]."'";
				}
				else
				{
					$fileOwnerBawahan.= " OR fileowner='".$explodee[$i]."'";
				}
			}
		}
		
		if($userIdBawahan != "") // jika tidak punya bawahan
		{
			$allFileOwnerBawahan = "AND (".$fileOwnerBawahan.")";
		}
		
		if($allFileOwnerBawahan != "")
		{
			$urutanIdeFileBawahan = 0;
			$query2 = $CKoneksi->mysqlQuery("SELECT * FROM mstdoc WHERE namedoc LIKE '%".$paramTextGet."%' ".$allFileOwnerBawahan." AND deletests=0;");
			while($row2 = $CKoneksi->mysqlFetch($query2))
			{
				$urutanIdeFileBawahan++;
				if($urutanIdeFileBawahan == 1)
				{
					$ideFileBawahan .= " ide != ".$row2['ide'];
				}
				else
				{
					$ideFileBawahan .= " AND ide != ".$row2['ide'];
				}
				$kumpulanIdeFileBukanBawahan = " AND ( ".$ideFileBawahan." )";
			}
		}
		
		/* ##### END MODUL UNTUK MENCARI FILE SUBORDINATE ####*/
		$kumpulanIdeFoldShare = $kumpulanIdeFoldShare;
		
		$query = $CKoneksi->mysqlQuery("SELECT * FROM mstdoc WHERE namedoc LIKE '%".$paramTextGet."%' AND (fileowner!='".$userIdLogin."') ".$kumpulanIdeFileBukanBawahan." ".$kumpulanIdeFoldShare." AND deletests=0;");
		while($row = $CKoneksi->mysqlFetch($query))
		{
			$bgRow = " style=\"background-repeat:no-repeat;background-position:right;\" background=\"../../picture/imgNotShared.png\"";
			
			$expWktUpload = explode(" ", $row['wktupload']);
			$expWktUpload1 = explode("-", $expWktUpload[0]);
			$expWktUpload2 = $expWktUpload[1];
			
			$tglUpload = $expWktUpload1[2];
			$blnUpload = ucfirst( strtolower( $CPublic->bulanSetengah( $expWktUpload1[1], "ind") ) );
			$thnUpload = $expWktUpload1[0];
			
			$wktUpload = $tglUpload." ".$blnUpload." ".$thnUpload." ".$expWktUpload2;
			
			$fileOwner = $CLogin->detilLogin($row['fileowner'], "userfullnm");
			
			$html.="<tr onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\">
					<td class=\"tdMyFolder\">
						<table width=\"100%\" boder=\"0\">
						<tr>
							<td width=\"5%\" align=\"center\" style=\"font-size:22px;color:#000080;font-weight:bold;font-family:Tahoma;\">".$urutan++."</td>
							<td width=\"75%\">
								<table width=\"100%\" class=\"fontMyFolderList\" ".$bgRow.">
								<tr>
									<td width=\"110\">Name</td>
									<td width=\"10\">:</td>
									<td style=\"color:#000080;\">".$row['namedoc']."</td>
								</tr>
								<tr>
									<td>Upload date</td>
									<td>:</td>
									<td style=\"color:#000080;\">".$wktUpload."</td>
								</tr>
								<tr>
									<td>Type file / Owner</td><td>:</td>
									<td style=\"color:#000080;\">".$row['extdoc']." / <b>".$fileOwner."</b></td>
								</tr>
								</table>
							</td>
							<td width=\"20%\" align=\"right\">&nbsp;</td>
						</tr>
						
						</table>
					</td>
				</tr>";
		}
		echo $html;
	}
}
?>
</table>
</body>

<?php

function cariFolderShareIsiFile($CFolder, $CKoneksi, $urutanIdFoldShare, $ideFold)
{
	$idFold = $CFolder->detilFold($ideFold, "idfold"); // cari idfold di tblfolder berdasarkan idefold yang dishare ke user login
	$countIdFold = strlen($idFold);//jumlah angka idfold setelah dipisahkan titik
	
	$urutanIdFoldFile = 0;
	//query dibawah untuk mencari idfold yang angka awalnya mengandung $idFold (mencari turunan folder yang berisi file) yang didaapat dan kontentipe=file, 
	$query=$CKoneksi->mysqlQuery("SELECT * FROM tblfolder WHERE SUBSTRING(idfold, 1, ".$countIdFold.")='".$idFold."' AND tipekonten='file' AND deletests=0");
	while($row = $CKoneksi->mysqlFetch($query))
	{
		$urutanIdFoldFile++;
		if($urutanIdFoldShare == 1 && $urutanIdFoldFile == 1) // jika urutan paling pertama
		{ 
			$idFoldFile.= " idfold = '".$row['idfold']."' ";
		}
		else
		{
			$idFoldFile.= "OR idfold = '".$row['idfold']."' ";
		}
	}
	
	return $idFoldFile;
}

function tombolFolderMilik($ide)
{
	$onClickOpen = "parent.openThickboxWindow('".$ide."', 'openOwnFolder');";
	$html = "";
	$html = "<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"".$onClickOpen."\" style=\"width:75px;height:55px;\" title=\"Open this Folder Content\">
				<table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
				  <tr>
					<td align=\"center\"><img src=\"../../picture/folderOpen.png\" height=\"25\"/> </td>
					
				  </tr>
				  <tr>
					<td align=\"center\">OPEN</td>
				  </tr>
				</table>
			</button>";
	return $html;
}

function tombolFolderSubordinate($ide)
{
	$onClickOpen = "parent.openThickboxWindow('".$ide."', 'openSubordinateFolder');";
	$html = "";
	$html = "<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"".$onClickOpen."\" style=\"width:75px;height:55px;\" title=\"Open this Folder Content\">
				<table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
				  <tr>
					<td align=\"center\"><img src=\"../../picture/folderOpen.png\" height=\"25\"/> </td>
					
				  </tr>
				  <tr>
					<td align=\"center\">OPEN</td>
				  </tr>
				</table>
			</button>";
	return $html;
}

function tombolFolderOtherShare($ide, $aksesOpen)
{
	$onClickOpen = "alert('You do not have permissions');";
	if($aksesOpen == "Y")
	{
		$onClickOpen = "parent.openThickboxWindow('".$ide."', 'openShareFolder');";
	}
	$html = "";
	$html = "<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"".$onClickOpen."\" style=\"width:75px;height:55px;\" title=\"Open this Folder Content\">
			<table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
			  <tr>
				<td align=\"center\"><img src=\"../../picture/folderOpen.png\" height=\"25\"/> </td>
				
			  </tr>
			  <tr>
				<td align=\"center\">OPEN</td>
			  </tr>
			</table>
		</button>";
	
	return $html;
}

function tombolFileMilik($ide, $onClickOpen, $iconShow)
{
	$onClickDetil = "parent.openThickboxWindow('".$ide."', 'detilOwnFile');";
	$onClickEdit = "parent.openThickboxWindow('".$ide."', 'editOwnFile');";
	$onClickDelete = "parent.openThickboxWindow('".$ide."', 'deleteOwnFile');";
	$html = "";
	$html = "<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"".$onClickDetil."\" style=\"width:75px;height:55px;\" title=\"Detail of this File\">
				  <table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
					<tr>
					  <td align=\"center\"><img src=\"../../picture/Information-blue-32.png\" height=\"25\"/> </td>
					</tr>
					<tr>
					  <td align=\"center\">DETAIL</td>
					</tr>
				  </table>
			  </button>
			  &nbsp;
			  <button type=\"button\" class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" onClick=\"".$onClickOpen."\" style=\"width:75px;height:55px;\" title=\"Open/Save this File to LocalDisk\">
				  <table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
					<tr>
					  <td align=\"center\"><img src=\"".$iconShow."\" height=\"28\"/></td>
					</tr>
					<tr>
					  <td align=\"center\">OPEN/SAVE</td>
					</tr>
				  </table>
			  </button>
			  &nbsp;
			  <button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"".$onClickEdit."\" style=\"width:75px;height:55px;\" title=\"Edit this File\">
				  <table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
					<tr>
					  <td align=\"center\"><img src=\"../../picture/Auction-blue-32.png\" height=\"25\"/> </td>
					</tr>
					<tr>
					  <td align=\"center\">EDIT</td>
					</tr>
				  </table>
			  </button>
			  &nbsp;
			  <button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"".$onClickDelete."\" style=\"width:75px;height:55px;\" title=\"Delete this File\">
				  <table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
					<tr>
					  <td align=\"center\"><img src=\"../../picture/Button-Cross-blue-32.png\" height=\"25\"/> </td>
					</tr>
					<tr>
					  <td align=\"center\">DELETE</td>
					</tr>
				  </table>
			  </button>";
	return $html;
}

function tombolFileBukanMilik($ide, $onClickOpen, $iconShow)
{
	$onClickDetil = "parent.openThickboxWindow('".$ide."', 'detilOwnFile');";
	$html = "";
	$html = "<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onClick=\"".$onClickDetil."\" style=\"width:75px;height:55px;\" title=\"Detail of This File\">
				  <table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
					<tr>
					  <td align=\"center\"><img src=\"../../picture/Information-blue-32.png\" height=\"25\"/> </td>
					  
					</tr>
					<tr>
					  <td align=\"center\">DETAIL</td>
					</tr>
				  </table>
			  </button>
			  &nbsp;
			  <button type=\"button\" class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" onClick=\"".$onClickOpen."\" style=\"width:75px;height:55px;\" title=\"Open/Save this File\">
				  <table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
					<tr>
					  <td align=\"center\"><img src=\"".$iconShow."\" height=\"28\"/></td>
					</tr>
					<tr>
					  <td align=\"center\">OPEN/SAVE</td>
					</tr>
				  </table>
			  </button>";
	return $html;
}
?>