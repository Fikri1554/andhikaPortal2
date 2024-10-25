<?php
require_once("../config.php");

if($halamanPost == "pilihBtnExpand")
{
	$ideSek = $idePost;

	$idFoldSek = $CFolder->detilFold($ideSek, "idfold");
	$foldSubSek = $CFolder->detilFold($ideSek, "foldsub");
	$foldSubSet = $foldSubSek+1;
	$idFoldRefSek = $CFolder->detilFold($ideSek, "idfoldref");
	$tipeKontenSek = $CFolder->detilFold($ideSek, "tipekonten");

	$folderNameMap = $CFolder->folderNameMap($idFoldSek, $foldSubSek);
		
	$levelSek = $foldSubSek + 1;
	?>

<!-- ############# TEKS PENUNJUK DOKUMEN -->
<table cellpadding="0" cellspacing="0" width="100%" height="100%">
    <tr>
        <td colspan="3" class="batasTitik" height="10"></td>
    </tr>
    <tr>
        <td height="20" colspan="3"><?php echo $folderNameMap; ?></td>
    </tr>
    <tr>
        <td valign="bottom" width="100">
            <input type="hidden" id="ideDipilih" name="ideDipilih" value="<?php echo $idePost; ?>" />
            <input type="hidden" id="idFoldDipilih" name="idFoldDipilih" value="<?php echo $idFoldSek; ?>" />
            <input type="hidden" id="foldSub" name="foldSub" value="<?php echo $foldSubSet; ?>" />
            <input type="hidden" id="halaman" />
            &nbsp;&nbsp;<span class="teksLvlFolder">Level <?php echo $levelSek ; ?></span> <img
                src="../picture/Arrow-Bottom-Right-32.png" style="width:10px;height:10px;display:inline-block;" />
        </td>
        <td width="400">&nbsp;
            <?php		
		$btnNew = "<button class=\"btnStandar\" id=\"btnNewFolder\" onclick=\"openThickboxWindow('','newFolder');\" style=\"width:100px;height:29px;\" title=\"Create New Folder\">
                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
                      <tr>
                        <td align=\"right\"><img src=\"../picture/Folder-blue-32.png\" height=\"20\"/> </td>
                        <td align=\"center\">New Folder</td>
                      </tr>
                    </table>
                </button>";
		$onFokusCari = "cariFolder(this.value);"; 
        if($tipeKontenSek == "file")
        {
            $btnNew = "<button class=\"btnStandar\" id=\"btnNewFile\" onclick=\"openThickboxWindow('','newFile');\" style=\"width:80px;height:29px;\" title=\"Create New File\">
                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
                      <tr>
                        <td align=\"right\"><img src=\"../picture/Document-blue-32.png\" height=\"20\"/> </td>
                        <td align=\"center\">New File</td>
                      </tr>
                    </table>
                </button>";
			$onFokusCari = "cariFile(this.value);";
        }
		echo $btnNew;
?>
        </td>
        <td align="right">
            <img src="../picture/Search2-32.png" width="25" style="vertical-align:bottom;" />
            <input type="text" class="elementSearch" id="paramText" size="41" onfocus="<?php echo $onFokusCari; ?>"
                onkeyup="<?php echo $onFokusCari; ?>">
            &nbsp;
        </td>
    </tr>
</table>
<?php
}
if($halamanPost == "pilihBtnExpand2")
{
	$ideSek = $idePost;
	$foldSubSek = $CFolder->detilFold($ideSek, "foldsub");
	$foldSubSet = $foldSubSek+1;
	$idFoldRefSek = $CFolder->detilFold($ideSek, "idfoldref");
	
	if($ideSek != "") // jika sudah mencapai level 1 maka tombol back hilang
	{ 
?>
<button class="btnStandar" id="btnBack" type="button"
    onclick="parent.pilihBtnBack('<?php echo $ideSek; ?>', '<?php echo $foldSubSek; ?>', '<?php echo $idFoldRefSek; ?>', '');"
    style="width:60px;height:31px;" title="Back to Parent Folder">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" width="25"><img src="../picture/Arrow-Left-blue-32.png" height="20" /> </td>
            <td align="center">Back</td>
        </tr>
    </table>
</button>
&nbsp;
<?php
	}
}
//######################################################################################################################
if($halamanPost == "pilihBtnBack")
{
	$idFoldSek = $CFolder->detilFold($idePost, "idfoldref"); // idfold sekarang setelah back artinya mundur selangkah
	$ideSek = $CFolder->detilFoldByIdFold($idFoldSek, "ide"); // ide sekarang berdasarkan parameter idefold
	$foldSubSek = $CFolder->detilFoldByIdFold($idFoldSek, "foldsub");
	$foldSubSet = $foldSubSek+1;
	$tipeKontenSek = $CFolder->detilFoldByIdFold($idFoldSek, "tipekonten");
	$idFoldRefSek = $CFolder->detilFoldByIdFold($idFoldSek, "idfoldref"); // idfold diatas dari idfold yang dipilih sekarang

	$folderNameMap = $CFolder->folderNameMap($idFoldSek, $foldSubSeb);
	$levelSek = $foldSubSek + 1;
	?>
<!-- ############# TEKS PENUNJUK DOKUMEN -->
<table cellpadding="0" cellspacing="0" width="100%" height="100%">
    <tr>
        <td colspan="3" class="batasTitik" height="10"></td>
    </tr>
    <tr>
        <td height="20" colspan="3"><?php echo $folderNameMap; ?></td>
    </tr>
    <tr>
        <td valign="bottom" width="100">
            <input type="hidden" id="ideDipilih" name="ideDipilih" value="<?php echo $ideSek; ?>" />
            <input type="hidden" id="idFoldDipilih" name="idFoldDipilih" value="<?php echo $idFoldSek; ?>" />
            <input type="hidden" id="foldSub" name="foldSub" value="<?php echo $foldSubSet; ?>" />
            <input type="hidden" id="halaman" />
            &nbsp;&nbsp;<span class="teksLvlFolder">Level <?php echo $levelSek ; ?></span> <img
                src="../picture/Arrow-Bottom-Right-32.png" style="width:10px;height:10px;display:inline-block;" />
        </td>
        <td width="400">&nbsp;
            <?php
        if($tipeKontenSek == "folder" || $idFoldSek == "")
        {
            echo "<button class=\"btnStandar\" id=\"btnNewFolder\" onclick=\"openThickboxWindow('','newFolder');\" style=\"width:100px;height:29px;\" title=\"Create New Folder\">
                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
                      <tr>
                        <td align=\"right\"><img src=\"../picture/Folder-blue-32.png\" height=\"20\"/> </td>
                        <td align=\"center\">New Folder</td>
                      </tr>
                    </table>
                </button>";
			$onFokusCari = "cariFolder(this.value);";	
        }
        else if($tipeKontenSek == "file")
        {
            echo "<button class=\"btnStandar\" id=\"btnNewFile\" onclick=\"openThickboxWindow('','newFile');\" style=\"width:80px;height:29px;\" title=\"Create New File\">
                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
                      <tr>
                        <td align=\"right\"><img src=\"../picture/Document-blue-32.png\" height=\"20\"/> </td>
                        <td align=\"center\">New File</td>
                      </tr>
                    </table>
                </button>";
			$onFokusCari = "cariFile(this.value);";
        }
?>
        </td>
        <td align="right">
            <?php
		if($idFoldSek != "") // jika sudah mencapai level 1 maka tombol back hilang
		{ 
			echo "&nbsp;";
 		} 
?>
            <img src="../picture/Search2-32.png" width="25" style="vertical-align:bottom;" />
            <input type="text" class="elementSearch" id="paramText" size="41" onfocus="<?php echo $onFokusCari; ?>"
                onkeyup="<?php echo $onFokusCari; ?>">
            &nbsp;
        </td>
    </tr>
</table>
<?php
}
if($halamanPost == "pilihBtnBack2")
{
	$idFoldSek = $CFolder->detilFold($idePost, "idfoldref"); // idfold sekarang setelah back artinya mundur selangkah
	$ideSek = $CFolder->detilFoldByIdFold($idFoldSek, "ide"); // ide sekarang berdasarkan parameter idefold
	$foldSubSek = $CFolder->detilFoldByIdFold($idFoldSek, "foldsub");
	$idFoldRefSek = $CFolder->detilFoldByIdFold($idFoldSek, "idfoldref"); // idfold diatas dari idfold yang dipilih sekarang
	
	if($idFoldSek != "") // jika sudah mencapai level 1 maka tombol back hilang
	{ 
		?>
<button class="btnStandar" id="btnBack" type="button"
    onclick="parent.pilihBtnBack('<?php echo $ideSek; ?>', '<?php echo $foldSubSek; ?>', '<?php echo $idFoldRefSek; ?>', '');"
    style="width:60px;height:31px;" title="Back to Parent Folder">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" width="25"><img src="../picture/Arrow-Left-blue-32.png" height="20" /> </td>
            <td align="center">Back</td>
        </tr>
    </table>
</button>
&nbsp;
<?php
	}
}
//######################################################################################################################
//######################################################################################################################

// KETIKA BUTTON VIEW SIDE DIPILIH
if($halamanPost == "pilihBtnExpandSide")
{
	$ideSek = $idePost;
	$idFoldSek = $CFolder->detilFold($ideSek, "idfold");
	$foldSubSek = $CFolder->detilFold($ideSek, "foldsub");
	$foldSubSet = $foldSubSek+1;
	$idFoldRefSek = $CFolder->detilFold($ideSek, "idfoldref");
	$tipeKontenSek = $CFolder->detilFold($ideSek, "tipekonten");

	$folderNameMap = $CFolder->folderNameMap($idFoldSek, $foldSubSek);	
	$levelSek = $foldSubSek + 1;
	?>

<!-- ############# TEKS PENUNJUK DOKUMEN -->
<table cellpadding="0" cellspacing="0" width="100%" height="100%">
    <tr>
        <td colspan="3" class="batasTitik" height="10"></td>
    </tr>
    <tr>
        <td height="20" colspan="3"><?php echo $folderNameMap; ?></td>
    </tr>
    <tr>
        <td valign="bottom" width="100">
            <input type="hidden" id="ideDipilih" name="ideDipilih" value="<?php echo $idePost; ?>" />
            <input type="hidden" id="idFoldDipilih" name="idFoldDipilih" value="<?php echo $idFoldSek; ?>" />
            <input type="hidden" id="foldSub" name="foldSub" value="<?php echo $foldSubSet; ?>" />
            <input type="hidden" id="halaman" />
            &nbsp;&nbsp;<span class="teksLvlFolder">Level <?php echo $levelSek ; ?></span> <img
                src="../picture/Arrow-Bottom-Right-32.png" style="width:10px;height:10px;display:inline-block;" />
        </td>
        <td width="400">&nbsp;
            <?php
        $btnNew = "<button class=\"btnStandarDisabled\" id=\"btnNewFolder\" style=\"width:100px;height:29px;\" disabled>
                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
                      <tr>
                        <td align=\"right\"><img src=\"../picture/Folder-blue-32.png\" height=\"20\"/> </td>
                        <td align=\"center\">New Folder</td>
                      </tr>
                    </table>
                </button>";
		$disParam = "disabled";
		$valueParam = "";
		$onFokusCari = "cariFolder(this.value);"; 
		
        if($tipeKontenSek == "file")
        {
            $btnNew = "<button class=\"btnStandar\" id=\"btnNewFile\" onclick=\"openThickboxWindow('','newFile');\" style=\"width:80px;height:29px;\" title=\"Create New File\">
                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
                      <tr>
                        <td align=\"right\"><img src=\"../picture/Document-blue-32.png\" height=\"20\"/> </td>
                        <td align=\"center\">New File</td>
                      </tr>
                    </table>
                </button>";
			$disParam = "";
			$valueParam = "";
			$onFokusCari = "cariFile(this.value);";
        }
		echo $btnNew;
?>
        </td>
        <td align="right">
            <img src="../picture/Search2-32.png" width="25" style="vertical-align:bottom;" />
            <input type="text" class="elementSearch" id="paramText" size="41" style="background-color:#E8E8E8;"
                onfocus="<?php echo $onFokusCari; ?>" onkeyup="<?php echo $onFokusCari; ?>"
                value="<?php echo $valueParam; ?>" <?php echo $disParam; ?>>
            &nbsp;
        </td>
    </tr>
</table>
<?php
}
if($halamanPost == "pilihBtnExpandSide2")
{
	$ideSek = $idePost;
	$foldSubSek = $CFolder->detilFold($ideSek, "foldsub");
	$foldSubSet = $foldSubSek+1;
	$idFoldRefSek = $CFolder->detilFold($ideSek, "idfoldref");
	if($ideSek != "")
	{
?>
<button class="btnStandarDisabled" id="btnBack" type="button" style="width:60px;height:31px;" disabled>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" width="25"><img src="../picture/Arrow-Left-blue-32.png" height="20" /> </td>
            <td align="center">Back</td>
        </tr>
    </table>
</button>
&nbsp;
<?php
	}
}
//######################################################################################################################
// KHUSUS UNTUK AUTHORIZATION FOLDER 
//######################################################################################################################
$idAuthorFoldPost = $_POST['idAuthorFold'];
$statusCentangPost = $_POST['statusCentang'];
if($halamanPost == "cekDetailFold" || $halamanPost == "cekExpandFold" || $halamanPost == "cekOpenFile" || $halamanPost == "cekDetailFile" || $halamanPost == "cekUploadFile")
{
	$statusCentang = "N";
	$statusBeriHapus = "Hapus";
	if($statusCentangPost == "true")
	{
		$statusCentang = "Y";
		$statusBeriHapus = "Beri";
	}
	
	$ideFold = $CFolder->detilAuthorFold($idAuthorFoldPost, "idefold");
	$nmFold = $CFolder->detilFold($ideFold, "namefold");
	$authorEmp = $CFolder->detilAuthorFold($idAuthorFoldPost, "nama");
	
	if($halamanPost == "cekExpandFold")
	{
		$CKoneksi->mysqlQuery("UPDATE tblauthorfold SET expand  = '".$statusCentang."', updusrdt = '".$CPublic->userWhoAct()."' WHERE idauthorfold = '".$idAuthorFoldPost."' AND deletests=0");
		$CHistory->updateLog2($userIdLogin, $statusBeriHapus." akses user <b>".$authorEmp."</b> pada <b>expand</b> folder ".$nmFold."");
	}
	if($halamanPost == "cekDetailFold")
	{
		$CKoneksi->mysqlQuery("UPDATE tblauthorfold SET detail = '".$statusCentang."', updusrdt = '".$CPublic->userWhoAct()."' WHERE idauthorfold = '".$idAuthorFoldPost."' AND deletests=0");
		$CHistory->updateLog2($userIdLogin, $statusBeriHapus." akses user <b>".$authorEmp."</b> pada <b>detil</b> folder ".$nmFold."");
	}
	if($halamanPost == "cekOpenFile")
	{
		$CKoneksi->mysqlQuery("UPDATE tblauthorfold SET openfile = '".$statusCentang."', updusrdt = '".$CPublic->userWhoAct()."' WHERE idauthorfold = '".$idAuthorFoldPost."' AND deletests=0");
		$CHistory->updateLog2($userIdLogin, $statusBeriHapus." akses user <b>".$authorEmp."</b> pada <b>openfile</b> folder ".$nmFold."");
	}
	if($halamanPost == "cekDetailFile")
	{
		$CKoneksi->mysqlQuery("UPDATE tblauthorfold SET detailfile = '".$statusCentang."', updusrdt = '".$CPublic->userWhoAct()."' WHERE idauthorfold = '".$idAuthorFoldPost."' AND deletests=0");
		$CHistory->updateLog2($userIdLogin, $statusBeriHapus." akses user <b>".$authorEmp."</b> pada <b>detailfile</b> folder ".$nmFold."");
	}
	if($halamanPost == "cekUploadFile")
	{
		$CKoneksi->mysqlQuery("UPDATE tblauthorfold SET uploadfile = '".$statusCentang."', updusrdt = '".$CPublic->userWhoAct()."' WHERE idauthorfold = '".$idAuthorFoldPost."' AND deletests=0");
		$CHistory->updateLog2($userIdLogin, $statusBeriHapus." akses user <b>".$authorEmp."</b> pada <b>uploadfile</b> folder ".$nmFold."");
	}
}

$idAuthorFoldPost = $_POST['idAuthorFold']; // idAuthorFold yang dipilih atau id pada tblauthorfold yang dipilih

if($halamanPost == "pilihBtnExpandOtherShare")
{
	$ideSek = $idePost;
	$idFoldSek = $CFolder->detilFold($ideSek, "idfold");
	$foldSubSek = $CFolder->detilFold($ideSek, "foldsub");
	
	$folderNameMap = $CFolder->sharedFolderNameMap($idAuthorFoldPost, $idFoldSek, $foldSubSek);	
	echo $folderNameMap;
}

if($halamanPost == "pilihBtnExpandOwnShare")
{
	$ideSek = $idePost;
	$idFoldSek = $CFolder->detilFold($ideSek, "idfold");
	$foldSubSek = $CFolder->detilFold($ideSek, "foldsub");
	
	$folderNameMap = $CFolder->sharedFolderNameMap($idAuthorFoldPost, $idFoldSek, $foldSubSek);	
	echo $folderNameMap;
}

if($halamanPost == "pilihBtnExpandSubordinate")
{
	$ideSek = $idePost;
	
	
	if($ideSek != "") // jika yang diklik adalah bukan teks map "home"
	{
		$idFoldSek = $CFolder->detilFold($ideSek, "idfold");
		$foldSubSek = $CFolder->detilFold($ideSek, "foldsub");
		$folderNameMap = $CFolder->subordinateFolderNameMap($idFoldSek, $foldSubSek);	
		echo $folderNameMap;
	}
	
}

if($halamanPost == "saveChangePass")
{
	$newpassPost = $_POST['newpass'];
	$CKoneksi->mysqlQuery("UPDATE login SET userpass = '".$newpassPost."', updusrdt = '".$CPublic->userWhoAct()."' WHERE userid = '".$userIdLogin."' AND deletests=0 LIMIT 1");
	
	$CHistory->updateLog2($userIdLogin, "Ganti password");
}

if($halamanPost == "createRow")
{
	$jmlFolderPost = $_POST['jmlFolder'];
	$idFoldRefPost = $_POST['idFoldRef'];
	
	$html = "";
	$html.= "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
	for($i=1; $i<=$jmlFolderPost; $i++)
	{
		$html.= "<!-- URUTAN 1 ############### -->
				<tr>
					<td class=\"tabelBorderBottomJust\" style=\"border-style:dotted;\">";
		$html.= "		<table cellpadding=\"0\" cellspacing=\"5\" width=\"100%\" class=\"formInput\" border=\"0\">
						<tr>
							<td class=\"tabelBorderAll\" width=\"10%\" rowspan=\"3\" align=\"center\" style=\"font-size:14px;color:#000080;font-weight:bold;font-family:Tahoma;\">".$i."</td>
							
						</tr>
						<tr valign=\"top\">
							<td rowspan=\"2\">&nbsp;</td>
							<td width=\"25%\">Name</td>
							<td width=\"65%\"><input type=\"text\" class=\"elementSearch\" id=\"nmFold_".$i."\" name=\"nmFold_".$i."\" style=\"width:310px;height:28px;font-family:sans-serif;\" onFocus=\"tulisNmFold();\" onKeyUp=\"tulisNmFold();\"></td>
						</tr>
						<tr>
							<td>Content Type</td>
							<td height=\"20\">
								<input type=\"radio\" name=\"contentType_".$i."\" id=\"contentType\" value=\"folder\" checked/>&nbsp;Folder&nbsp;&nbsp;
								<input type=\"radio\" name=\"contentType_".$i."\" id=\"contentType\" value=\"file\"/>&nbsp;File
							</td>
						</tr>
						</table>";
		$html.= "   </td>
        		</tr>";
	}
	$html.= "<input type=\"hidden\" id=\"jmlFolder\" name=\"jmlFolder\" value=\"".$jmlFolderPost."\">
			<input type=\"hidden\" name=\"halaman\" id=\"halaman\" value=\"simpanMultiFolder\"> 
        	<input type=\"hidden\" id=\"idFoldRef\" name=\"idFoldRef\" value=\"".$idFoldRefPost."\" />";
	$html.= "</table>";
	
	echo $html;
}

if($halamanPost == "createRowFile")
{
	$jmlFilePost = $_POST['jmlFile'];
	$idFoldPost = $_POST['idFold'];
	
	$html = "";
	$html.= "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
	for($i=1; $i<=$jmlFilePost; $i++)
	{
		$html.= "<tr>
        			<td class=\"tabelBorderBottomJust\" style=\"border-style:dotted;\">";
		$html.= "		<table cellpadding=\"0\" cellspacing=\"5\" width=\"100%\" class=\"formInput\" border=\"0\">
						<tr>
							<td class=\"tabelBorderAll\" width=\"10%\" rowspan=\"3\" align=\"center\" style=\"font-size:14px;color:#000080;font-weight:bold;font-family:Tahoma;\">".$i."</td>
							
						</tr>
						<tr align=\"left\">
							<td rowspan=\"2\">&nbsp;</td>
							<td>File upload</td>
							<td height=\"20\">
								<input type=\"file\" class=\"elementDefault\" name=\"fileUpload_".$i."\" id=\"fileUpload_".$i."\" style=\"width:320px;\" onChange=\"pilihUpload('".$i."');\" title=\"Choose File from LocalDisk\">
							</td>
						</tr>
						<tr valign=\"top\" align=\"left\">
							
							<td width=\"25%\">Name</td>
							<td width=\"65%\">
								<input type=\"text\" class=\"elementDefault\" id=\"nmFile_".$i."\" name=\"nmFile_".$i."\" style=\"width:310px;height:17px;\" onFocus=\"cekNmFile();\" onKeyUp=\"cekNmFile();\">
							</td>
						</tr>
						
						</table>";
		$html.= "   </td>
        		</tr>";
	}
	
	$html.= "<input type=\"hidden\" id=\"jmlFile\" name=\"jmlFile\" value=\"".$jmlFilePost."\">
			<input type=\"hidden\" name=\"halaman\" id=\"halaman\" value=\"simpanMultiFile\">
			<input type=\"hidden\" name=\"idFold\" id=\"idFold\" value=\"".$idFoldPost."\"> 
			 ";
	$html.= "</table>";
	
	echo $html;
}

if($halamanPost == "cekFolderAdaTidak")
{
	$nmFoldPost = mysql_real_escape_string( $_POST['nmFold'] );
	$idFoldRefPost = $_POST['idFoldRef'];

	$query = $CKoneksi->mysqlQuery("SELECT namefold FROM tblfolder WHERE idfoldref='".$idFoldRefPost."' AND namefold='".$nmFoldPost."' AND folderowner=".$userIdLogin." AND deletests=0 LIMIT 1");	
	$row = $CKoneksi->mysqlFetch($query);
	
	$nilai = "kosong";
	if($row['namefold'] != "")
	{
		$nilai = "ada";
	}
	
	echo "&nbsp;<input type=\"hidden\" id=\"folderAdaTidak\" name=\"folderAdaTidak\" value=\"".$nilai."\">";
}


if($halamanPost == "cekFolderAdaTidakDB")
{
	$allNmFold = $_POST['allNmFold'];
	$idFoldRefPost = $_POST['idFoldRef'];

	$explAllNmFold = explode("#*#*", $allNmFold);
	$jmlAllNmFold = count($explAllNmFold);
	
	for($i=0;$i <= $jmlAllNmFold; $i++)
	{
		$namaFold = mysql_real_escape_string( $explAllNmFold[$i] );
		if($namaFold  != "")
		{
			$query = $CKoneksi->mysqlQuery("SELECT namefold FROM tblfolder WHERE idfoldref='".$idFoldRefPost."' AND namefold='".$namaFold."'  AND folderowner=".$userIdLogin." AND deletests=0 LIMIT 1");	
			$row = $CKoneksi->mysqlFetch($query);
			
			if($row['namefold'] != "")
			{
				$adaKosong.= "ada";
			}
			else
			{
				$adaKosong.= "kosong";
			}
		}
	}
	
	$allAdaKosong = $adaKosong;
	$cariTeksAda = strpos($allAdaKosong ,"ada");

	if($cariTeksAda >= 0)
	{
		$nilai = "ada";
	}
	if($cariTeksAda === false)
	{
		$nilai = "kosong";
	}
	
	echo "&nbsp;<input type=\"hidden\" id=\"folderAdaTidakDB\" name=\"folderAdaTidakDB\" value=\"".$nilai."\">";
}

if($halamanPost == "cekFileAdaTidak")
{
	$namaFilePost = mysql_real_escape_string( $_POST['namaFile'] );
	$idFoldPost = $_POST['idFold'];

	$query = $CKoneksi->mysqlQuery("SELECT namedoc FROM mstdoc WHERE idfold='".$idFoldPost."' AND namedoc='".$namaFilePost."' AND deletests=0 LIMIT 1");	
	$row = $CKoneksi->mysqlFetch($query);
	
	$nilai = "kosong";
	if($row['namedoc'] != "")
	{
		$nilai = "ada";
	}
	
	echo "&nbsp;<input type=\"hidden\" id=\"fileAdaTidak\" name=\"fileAdaTidak\" value=\"".$nilai."\">";
}

if($halamanPost == "cekFileAdaTidakDB")
{
	$allNmFile = $_POST['allNmFile'];
	$idFoldPost = $_POST['idFold'];

	$explAllNmFile = explode("#*#*", $allNmFile);
	$jmlAllNmFile = count($explAllNmFile);
	
	for($i=0;$i <= $jmlAllNmFile; $i++)
	{
		$namaFile = mysql_real_escape_string( $explAllNmFile[$i] );
		if($namaFile  != "")
		{
			$query = $CKoneksi->mysqlQuery("SELECT namedoc FROM mstdoc WHERE idfold='".$idFoldPost."' AND namedoc='".$namaFile ."' AND deletests=0 LIMIT 1");	
			$row = $CKoneksi->mysqlFetch($query);
			
			if($row['namedoc'] != "")
			{
				$adaKosong.= "ada";
			}
			else
			{
				$adaKosong.= "kosong";
			}
		}
	}
	
	$allAdaKosong = $adaKosong;
	$cariTeksAda = strpos($allAdaKosong ,"ada");

	if($cariTeksAda >= 0)
	{
		$nilai = "ada";
	}
	if($cariTeksAda === false)
	{
		$nilai = "kosong";
	}
	
	echo "&nbsp;<input type=\"hidden\" id=\"fileAdaTidakDB\" name=\"fileAdaTidakDB\" value=\"".$nilai."\">";
}

if($halamanPost == "cekFolderAdaTidakDB_ExistsFold")
{
	$allFolderPost = $_POST['allFolder'];
	$idFoldRefPost = $_POST['idFoldRef'];
	
	$folderOwner = $userIdLogin;
	
	$nilaiAda = "";
	$nilai = "";
	$explodeAllFolder = explode("#*#*",$allFolderPost);
	$pjgExplodeAllFolder = count($explodeAllFolder);
	$a = 0;
	for($i=0; $i<=$pjgExplodeAllFolder; $i++)
	{
		if($explodeAllFolder[$i]!="")
		{
			$folderTerakhir = cariFolderTerakhir($explodeAllFolder[$i]); // nama folder dari misal C:/aaa/bbb maka namafolder adalah bbb
			$cekNmFoldDB = cekNmFoldDB($CKoneksi, $folderOwner, $idFoldRefPost, $folderTerakhir);
			if($cekNmFoldDB == "ada")
			{
				$a++;
				$nilaiAda.= "<b>".($a).". ".$folderTerakhir."</b><br>";
			}
		}
		
		if($nilaiAda != "")
		{
			$nilai = "Folder name <br>".$nilaiAda." is already exist and can't be saved...";
		}
	}
	//echo $allFolderPost." / ".$idFoldRefPost;
	echo $nilai;
}

function cariFolderTerakhir($pathFolder)
{
	$expPathFolder = explode("\\", $pathFolder);
	$pjgExpPathFolder = count($expPathFolder);
	for($i=0; $i<=($pjgExpPathFolder-1); $i++)
	{
		if($expPathFolder[$i]!="")
		{
			if($i == ($pjgExpPathFolder-1))
			{
				$folderTerakhir = $expPathFolder[$i];
			}
		}
	}
	return $folderTerakhir;
}

function cekNmFoldDB($CKoneksi, $folderOwner, $idFoldRef, $namaFold)
{
	$adaKosong = "";
	$query = $CKoneksi->mysqlQuery("SELECT namefold FROM tblfolder WHERE idfoldref='".$idFoldRef."' AND namefold='".$namaFold."' AND folderowner=".$folderOwner." AND deletests=0 LIMIT 1");	
	$row = $CKoneksi->mysqlFetch($query);
	
	if($row['namefold'] != "")
	{
		$adaKosong.= "ada";
	}
	else
	{
		$adaKosong.= "kosong";
	}
	
	return $adaKosong;
}

// ========= Mulai Ajax untuk "Judul New Activity" =========
if($halamanPost == "chooseAct1")
{
	$dateActPost= $_POST['dateAct'];
	$dateActDBFormat = $CPublic->convTglDB($dateActPost);
	$tglStartPrevActDBFormat = $CPublic->convTglDB($tglStartPrevAct);
	
	$menuActOnProgress= "";
	$nilaiMenu = "";
	$menuActOnProgress.="<div id=\"divLoad\" style=\"position:fixed;margin-left:250px;display:none;\">
							<img src=\"../../picture/ajax-loader20.gif\"/>
						</div>
						<select class=\"elementMenu\" id=\"activityOnProgress\" name=\"activityOnProgress\" style=\"width:101.5%;\" title=\"Choose Your Activity\">
						<option value=\"00000000000\">-- PLEASE SELECT --</option>";
	$query = $CKoneksi->mysqlQuery("SELECT idactivity, activity, status, tanggal, bulan, tahun, DATE(CONCAT(tahun,'-',bulan,'-', tanggal)) AS dateActMerg FROM tblactivity WHERE (ownerid='".$userIdLogin."') AND (status = 'onprogress' OR status = 'postpone') AND (datefinish = 0000-00-00) AND referidactivity='0000000000' AND (deletests=0) AND DATE(CONCAT(tahun,'-',bulan,'-', tanggal)) <= DATE('".$dateActDBFormat."') AND DATE(CONCAT(tahun,'-',bulan,'-', tanggal)) > DATE('".$tglStartPrevActDBFormat."') ORDER BY dateActMerg; ");
	while($row = $CKoneksi->mysqlFetch($query))
	{
		$date=ucfirst(strtolower($CPublic->bulanSetengah($row['bulan'], "eng")." ".$row['tanggal'].", ".$row['tahun']));	
		
		$statusActByPrefer = $CDailyAct->statusActByPrefer($row['idactivity'], $userIdLogin); // CARI STATUS DARI IDACTIVITY TSB BERDASARKAN REFERIDACTICITY
		$strFinish = strpos($statusActByPrefer, "finish"); // CARI STRING FINISH ADA TIDAK DALAM ARRAY $statusActByPrefer
		if($strFinish === false) 
		{
			 $menuActOnProgress.= "<option value=\"".$row['idactivity']."\" >".$date." | ".$row['activity']."</option>";	
		} 					
	}
	
	$menuActOnProgress.="</select>";
	echo $menuActOnProgress;
}
if($halamanPost == "chooseAct2")
{
	$activityPost = $_POST['activity'];
	$btnActOnProgress = "&nbsp;<button type=\"button\" class=\"btnStandarKecil\" id=\"btnChooseAct\" onClick=\"tampilSelection('typeAct1', 'ajaxInput1');tampilSelection('typeAct2', 'ajaxInput2');document.getElementById('statusBtnChooseAct').value='teksAct';\" style=\"width:109px;\" title=\"Type a New Activity\">
                            <table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
                            <tr>
                            	<td align=\"center\"><img src=\"../../picture/Pencil-blue-32.png\" height=\"20\"/> </td>
                                <td align=\"center\">Type Activity</td>
                            </tr>
                            </table></button>";
	echo $btnActOnProgress;
	echo "<input type=\"hidden\" id=\"activity\" name=\"activity\" value=\"".$activityPost."\" style=\"width:300px;\"/>" ;
}

if($halamanPost == "typeAct1")
{
	$activityPost = $_POST['activity'];
	$menuActOnProgress = "<div id=\"divLoad\" style=\"position:fixed;margin-left:250px;display:none;\">
							<img src=\"../../picture/ajax-loader20.gif\"/>
						</div>
						<input type=\"text\" class=\"elementDefault\" id=\"activity\" name=\"activity\" style=\"width:99%;\" value=\"".$activityPost."\">";					
	echo $menuActOnProgress;
}

if($halamanPost == "typeAct2")
{
	$menuActOnProgress = "&nbsp;<button type=\"button\" class=\"btnStandarKecil\" id=\"btnChooseAct\" onClick=\"tampilSelection('chooseAct1', 'ajaxInput1');tampilSelection('chooseAct2', 'ajaxInput2');document.getElementById('statusBtnChooseAct').value='menuAct';\" style=\"width:127px;\" title=\"Choose Previous Activity\">
                            <table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
                            <tr>
                            	<td align=\"center\"><img src=\"../../picture/Search-blue-32.png\" height=\"20\"/> </td>
                                <td align=\"center\">Choose Activity</td>
                            </tr>
                            </table>
                        </button>";			
	echo $menuActOnProgress;
}
// ========= Akhir Ajax untuk "Judul New Activity" =========

// ========= Mulai Ajax untuk "Judul Edit Activity" =========
if($halamanPost == "typeAct3")
{
	$activityPost = $_POST['activity'];
	$readOnlyActPost = $_POST['readOnlyAct'];
	$menuActOnProgress = "<div id=\"divLoad\" style=\"position:fixed;margin-left:250px;display:none;\">
								<img src=\"../../picture/ajax-loader20.gif\"/>
							</div>
							<input type=\"text\" class=\"elementDefault\" id=\"activity\" name=\"activity\" style=\"width:99%;\" value=\"".$activityPost."\" ".$readOnlyActPost.">";					
	echo $menuActOnProgress;
}
if($halamanPost == "typeAct4")
{
	$menuActOnProgress = "&nbsp;<button type=\"button\" class=\"btnStandarKecil\" id=\"btnChooseAct\" onClick=\"tampilSelection('chooseAct3', 'ajaxInput1');tampilSelection('chooseAct4', 'ajaxInput2');document.getElementById('statusBtnChooseAct').value='menuAct';\" style=\"width:127px;height:28px;\" title=\"Choose Previous Activity\">
                            <table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
                            <tr>
                            	<td align=\"center\"><img src=\"../../picture/Search-blue-32.png\" height=\"20\"/> </td>
                                <td align=\"center\">Choose Activity</td>
                            </tr>
                            </table>
                        </button>";			
	echo $menuActOnProgress;
}
if($halamanPost == "chooseAct3")
{
	$idActivityGet = $_POST['idActivity'];
	$dateActGet = $_POST['dateAct'];
	$referIdActivity  = $_POST['referIdAct'];
	$dateActDBFormat = $CPublic->convTglDB($dateActGet);
	$tglStartPrevActDBFormat = $CPublic->convTglDB($tglStartPrevAct);
						
	$menuActOnProgress= "";
	$nilaiMenu = "";
	$menuActOnProgress.="<div id=\"divLoad\" style=\"position:fixed;margin-left:250px;display:none;\">
							<img src=\"../../picture/ajax-loader20.gif\"/>
						</div>
						<select class=\"elementMenu\" id=\"activityOnProgress\" name=\"activityOnProgress\" style=\"width:101.5%;\" title=\"Choose Your Activity\">";
	$menuActOnProgress.="<option value=\"00000000000\">-- PLEASE SELECT --</option>";
	
	$query = $CKoneksi->mysqlQuery("SELECT idactivity, activity, status, tanggal, bulan, tahun, DATE(CONCAT(tahun,'-',bulan,'-', tanggal)) AS dateActMerg FROM tblactivity WHERE (ownerid='".$userIdLogin."') AND (status = 'onprogress' OR status = 'postpone') AND (datefinish = 0000-00-00) AND referidactivity='0000000000' AND (deletests=0) AND DATE(CONCAT(tahun,'-',bulan,'-', tanggal)) <= DATE('".$dateActDBFormat."') AND DATE(CONCAT(tahun,'-',bulan,'-', tanggal)) > DATE('".$tglStartPrevActDBFormat."') OR idactivity = '".$referIdActivity."' ORDER BY dateActMerg; ");
	while($row = $CKoneksi->mysqlFetch($query))
	{
		// CARI STATUS DARI IDACTIVITY TSB BERDASARKAN REFERIDACTICITY
		$statusActByPrefer = $CDailyAct->statusActByPrefer($row['idactivity'], $userIdLogin); 
		$sel = "";
		$StrFinish = strpos($statusActByPrefer, "finish"); // CARI STRING FINISH ADA TIDAK DALAM ARRAY $statusActByPrefer
		if($StrFinish === false || $referIdActivity == $row['idactivity']) 
		{
			if($referIdActivity == $row['idactivity'])
			{
				$sel = " selected";
			}
			if($idActivityGet != $row['idactivity'])
			{
				$date=ucfirst(strtolower($CPublic->bulanSetengah($row['bulan'], "eng")." ".$row['tanggal'].", ".$row['tahun']));
				$menuActOnProgress.= "<option value=\"".$row['idactivity']."\" ".$sel." >".$date." | ".$row['activity']."</option>";
			}
		} 					
	}
	$menuActOnProgress.="</select>";
	echo $menuActOnProgress;

}
if($halamanPost == "chooseAct4")
{
	$activityPost = $_POST['activity'];
	$btnActOnProgress = "&nbsp;<button type=\"button\" class=\"btnStandarKecil\" id=\"btnChooseAct\" onClick=\"tampilSelection('typeAct3', 'ajaxInput1');tampilSelection('typeAct4', 'ajaxInput2');document.getElementById('statusBtnChooseAct').value='teksAct';\" style=\"width:109px;height:28px;\" title=\"Type a New/Edit Activity\">
                            <table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
                            <tr>
                            	<td align=\"center\"><img src=\"../../picture/Pencil-blue-32.png\" height=\"20\"/> </td>
                                <td align=\"center\">Type Activity</td>
                            </tr>
                            </table></button>";
	echo $btnActOnProgress;
	echo "<input type=\"hidden\" id=\"activityTemp\" name=\"activityTemp\" value=\"".$activityPost."\" style=\"width:300px;\"/>" ;
}

// ========= Akhir Ajax untuk "Judul Edit Activity" =========

// ========= -START- Cek Ada/Tidak New Activity ketika melakukan Approve =========
if($halamanPost == "cekNewActAdaTidak")
{
	$subordinateIdPost = $_POST['subordinateId'];
	$dateActPost = $_POST['dateAct'];
	$tglAct =  substr($dateActPost,0,2);
	$blnAct =  substr($dateActPost,3,2);
	$thnAct =  substr($dateActPost,6,4);
		
	$nilai = "tidakada";
	
	$query = $CKoneksi->mysqlQuery("SELECT idactivity FROM tblactivity WHERE ownerid='".$subordinateIdPost."' AND tanggal='".$CPublic->zerofill($tglAct,2)."' AND bulan='".$CPublic->zerofill($blnAct,2)."' AND tahun='".$thnAct."' AND bosreadjob='N' AND deletests=0 ORDER BY urutan ASC;");
	$row = $CKoneksi->mysqlFetch($query);
	
	$jmlRow = $CKoneksi->mysqlNRows($query);
	if($jmlRow > 0)
	{
		$nilai = "ada";
	}
	
	echo "<input type=\"hidden\" id=\"newActAdaTidak\" name=\"newActAdaTidak\" value=\"".$nilai."\"/>";
}
// ========= -END OF- Cek Ada/Tidak New Activity ketika melakukan Approve =========

// ========= -START- Cek Ada/Tidak Approve ketika membuat Activity Baru =========

if($halamanPost == "cekApprvAdaTidak")
{
	$ownerIdPost = $_POST['ownerId'];
	$dateActPost = $_POST['dateAct'];
	$tglAct =  substr($dateActPost,0,2);
	$blnAct =  substr($dateActPost,3,2);
	$thnAct =  substr($dateActPost,6,4);
		//$nilai = $ownerIdPost;
		//echo "ownerid : ".$ownerIdPost."date act: ".$dateActPost;
	$nilai = "tidakada";
	
	$query = $CKoneksi->mysqlQuery("SELECT idactivity FROM tblactivity WHERE ownerid='".$ownerIdPost."' AND tanggal='".$CPublic->zerofill($tglAct,2)."' AND bulan='".$CPublic->zerofill($blnAct,2)."' AND tahun='".$thnAct."' AND bosapprove='Y' AND deletests=0 ORDER BY urutan ASC;");
	$row = $CKoneksi->mysqlFetch($query);
	
	$jmlRow = $CKoneksi->mysqlNRows($query);
	if($jmlRow > 0)
	{
		$nilai = "ada";
	}
	
	echo "<input type=\"hidden\" id=\"apprvAdaTidak\" name=\"apprvAdaTidak\" value=\"".$nilai."\"/>";
}
// ========= -END OF- Cek Ada/Tidak Approve ketika membuat Activity Baru =========
?>