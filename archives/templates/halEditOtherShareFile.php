<!DOCTYPE HTML>
<?php
require_once("../../config.php");

$foldSubGet = $_GET['foldSub'];
$idFoldGet = $_GET['idFold'];
$halamanGet = $_GET['halaman'];
$fileExtension = $CFile->fileExtension();

if($halamanGet == "editOtherShareFile")
{
	$aksi = "simpanEditOtherShareFile";
	$judul = "Edit File";
	
	$nmFile = $CFile->detilFile($ideGet, "namedoc");
	$descFile = $CFile->detilFile($ideGet, "descdoc");
	
	$disNmFile = "disabled";
	$disDescFile = "disabled";
}
?>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<script language="JavaScript" src="../../js/masks.js"></script> 

<script type="text/javascript">
function pilihBtnSave()
{	
	document.getElementById('errorMsg').innerHTML = "";
	
	var ide = document.getElementById('ide').value;
	
	var fileUpload = document.getElementById('fileUpload').value;
	var fileUpload2 = document.getElementById('fileUpload');
	var tipeFile = fileUpload.split('.').pop();

	var aksi = document.getElementById('aksi').value;
	var fileExtension = "<?php echo $fileExtension; ?>";
	var adaFileExt = fileExtension.indexOf(tipeFile+",");	
	if(aksi == "simpanEditOtherShareFile")
	{
		if(fileUpload.replace(/ /g,"") == "")
		{
			document.getElementById('errorMsg').innerHTML = "File upload still empty";
			return false;
		}
		else
		{
			if(adaFileExt == -1)
			{
				document.getElementById('errorMsg').innerHTML = "File type not allowed!";
				return false;
			}
		}
	}
	
	var answer  = confirm("Are you sure want to save?");
	if(answer)
	{
		formUploadFile.submit();
	}
	else
	{	return false;	}
}
</script>

<?php
$dateTime = $CPublic->dateTimeGabung();
$wktUpload = $CPublic->waktuSek();

if($halamanPost == "simpanNewFile")
{
	$nmFile = mysql_real_escape_string( $_POST['nmFile'] );	
	$descFile = mysql_real_escape_string( $_POST['descFile'] );

	$expNamaFile = explode(".", $_FILES["fileUpload"]["name"]);
	$tipeFile = end($expNamaFile);

	$idDocLast = $CFile->idDocLast($idFoldGet);
	$fileDocNew = $userIdLogin."-".$idDocLast."-".$idFoldGet."-".$dateTime."-".$userIdLogin.".".$tipeFile;
	
	$CKoneksi->mysqlQuery("INSERT INTO mstdoc (iddoc, idfold, fileowner, namedoc, filedoc, extdoc, wktupload, descdoc, addusrdt)VALUES ('".$idDocLast."', '".$idFoldGet."', '".$userIdLogin."', '".$nmFile."', '".$fileDocNew."', '".$tipeFile."', '".$wktUpload."', '".$descFile."', '".$CPublic->userWhoAct()."');");
	$lastInsertId = mysql_insert_id();
	
	$foldSub = $CFolder->detilFoldByIdFold($idFoldGet, "foldsub"); // atau level
	$fileFolder = $CFolder->detilFoldByIdFold($idFoldGet, "filefold");
	$pathFilePaste = $pathArchives."data/document/LEVEL".$foldSub."/".$fileFolder."/".$fileDocNew;

	move_uploaded_file($_FILES['fileUpload']['tmp_name'], $pathFilePaste);
	
	$CHistory->updateLog($userIdLogin, "Buat File baru (ide = <b>".$lastInsertId."</b>, nama file = <b>".$nmFile."</b>)");
}
if($halamanPost == "simpanEditFile" || $halamanPost == "simpanEditCariFile")
{
	$nmFile = mysql_real_escape_string( $_POST['nmFile'] );
	$descFile = mysql_real_escape_string( $_POST['descFile'] );
	$fileUpload = $_FILES["fileUpload"]["name"];

	if($fileUpload == "")
	{
		$CKoneksi->mysqlQuery("UPDATE mstdoc SET namedoc = '".$nmFile."', descdoc = '".$descFile."', updusrdt = '".$CPublic->userWhoAct()."' WHERE ide = '".$ideGet."' AND idfold='".$idFoldGet."' AND deletests=0;");
	}
	else if($fileUpload != "")
	{
		$expNamaFile = explode(".", $_FILES["fileUpload"]["name"]);
		$tipeFile = end($expNamaFile);
	
		$idDocSek = $CFile->detilFile($ideGet, "iddoc");
		$fileDocNew = $userIdLogin."-".$idDocSek."-".$idFoldGet."-".$dateTime."-".$userIdLogin.".".$tipeFile;
		
		$foldSub = $CFolder->detilFoldByIdFold($idFoldGet, "foldsub"); // atau level
		$fileFolder = $CFolder->detilFoldByIdFold($idFoldGet, "filefold");
		$fileDocSek = $CFile->detilFile($ideGet, "filedoc");
		
		$fileSek = $pathArchives."data/document/LEVEL".$foldSub."/".$fileFolder."/".$fileDocSek;
		$folderDelete = $pathArchives."data/documentDel/Del-".$fileDocSek;
		
		copy($fileSek, $folderDelete); //kopikan file ke folder documentDel/
		
		$pathFilePaste = $pathArchives."data/document/LEVEL".$foldSub."/".$fileFolder."/".$fileDocNew;
		move_uploaded_file($_FILES['fileUpload']['tmp_name'], $pathFilePaste);

		unlink($fileSek); //delete file yang akan digantikan
		
		$CKoneksi->mysqlQuery("UPDATE mstdoc SET namedoc = '".$nmFile."', filedoc = '".$fileDocNew."', extdoc = '".$tipeFile."', wktupload = '".$wktUpload."', descdoc = '".$descFile."', updusrdt = '".$CPublic->userWhoAct()."' WHERE ide = '".$ideGet."' and idfold='".$idFoldGet."' AND deletests=0;");
	}
	$CHistory->updateLog($userIdLogin, "Rubah File (ide = <b>".$ideGet."</b>)");
}
if($halamanPost == "simpanEditOtherShareFile")
{
	$fileDocOld = $CFile->detilFile($ideGet, "filedoc");
	
	$expNamaFile = explode(".", $_FILES["fileUpload"]["name"]);
	$tipeFile = end($expNamaFile);
	$idDocSek = $CFile->detilFile($ideGet, "iddoc");
	$fileDocNew = $userIdLogin."-".$idDocSek."-".$idFoldGet."-".$dateTime."-".$userIdLogin.".".$tipeFile;
	
	$foldSub = $CFolder->detilFoldByIdFold($idFoldGet, "foldsub"); // atau level
	$fileFolder = $CFolder->detilFoldByIdFold($idFoldGet, "filefold");
	$fileDocSek = $CFile->detilFile($ideGet, "filedoc");
	$fileSek = $pathArchives."data/document/LEVEL".$foldSub."/".$fileFolder."/".$fileDocSek;
	$folderDelete = $pathArchives."data/documentDel/Del-".$fileDocSek;
	
	copy($fileSek, $folderDelete); //kopikan file ke folder documentDel/
	
	$pathFilePaste = $pathArchives."data/document/LEVEL".$foldSub."/".$fileFolder."/".$fileDocNew;
	move_uploaded_file($_FILES['fileUpload']['tmp_name'], $pathFilePaste);
	
	unlink($fileSek); //delete file yang akan digantikan
	
	$CKoneksi->mysqlQuery("UPDATE mstdoc SET filedoc = '".$fileDocNew."', extdoc = '".$tipeFile."', wktupload = '".$wktUpload."', lastuploadbyid = '".$userIdLogin."', lastuploadbyname = '".$userFullnm."', updusrdt = '".$CPublic->userWhoAct()."' WHERE ide = '".$ideGet."' and idfold='".$idFoldGet."' AND deletests=0;");
	$CHistory->updateLog($userIdLogin, "Merubah Source File (ide = <b>".$ideGet."</b>, Source lama = <b>".$fileDocOld."</b>, Source baru = <b>".$fileDocNew."</b>)");
}
?>
<input type="hidden" id="foldSub" name="foldSub" value="<?php echo $foldSubGet; ?>" />
<input type="hidden" id="idFold" name="idFold" value="<?php echo $idFoldGet; ?>" />
<input type="hidden" id="aksi" name="aksi" value="<?php echo $aksi; ?>" />
<input type="hidden" id="ide" name="ide" value="<?php echo $ideGet; ?>" />
<input type="hidden" id="nmFileAwal" name="nmFileAwal" value="<?php echo $nmFileAwal; ?>" />
<!--<input type="text" name="fileExtension" id="fileExtension" value="<?php echo $fileExtension; ?>" style="width:400px;"> -->

<body bgcolor="#FFFFFF">
<center>
<table cellpadding="0" cellspacing="0" height="100%" width="55%" border="0" class="">
<tr>
	<td align="center" height="40">
        <table cellpadding="0" cellspacing="0" width="50%">
        <tr>
            <td align="center"><span class="teksMyFolder">:: <?php echo $judul; ?> ::</span></td>
        </tr>
        </table>	
    </td>
</tr>
<tr><td height="20">&nbsp;</td></tr>
<tr>
    <td align="center" valign="top" height="50">
    	<form action="halEditOtherShareFile.php?idFold=<?php echo $idFoldGet; ?>&ide=<?php echo $ideGet; ?>" method="post" enctype="multipart/form-data" id="formUploadFile" name="formUploadFile">
        <table cellpadding="0" cellspacing="5" width="100%" class="formInput">
        <tr>
            <td>File upload</td>
            <td height="20">
                <input type="file" class="elementDefault" name="fileUpload" id="fileUpload" style="width:335px;" title="Choose File from LocalDisk">
            </td>
        </tr>
        </table> 
        <input type="hidden" name="halaman" id="halaman" value="<?php echo $aksi; ?>"> 
        </form> 
    </td>
</tr>
<tr><td height="20" align="center" valign="middle">&nbsp; <span id="errorMsg" class="errorMsg">&nbsp;</span></td></tr>
<tr>
    <td align="center" height="70">&nbsp;
       <button class="btnStandarKecil" onClick="pilihBtnSave();" style="width:90px;height:55px;" title="Save Editing Other Shared File">
            <table border="0" width="100%" height="100%">
            <tr>
                <td align="center"><img src="../../picture/Floppy-Disk-blue-32.png" height="25"/> </td> 
            </tr>
            <tr>
                <td align="center">SAVE</td>
            </tr>
            </table>
        </button>
    </td>
</tr>
<tr><td>&nbsp;</td></tr>
</table>
</center>
</body>

<?php
if($halamanPost == "simpanNewFile" || $halamanPost == "simpanEditFile" || $halamanPost == "simpanEditCariFile")
{
	?>
	<script language="javascript">
		parent.exit();
	</script>	
<?php
}
if($halamanPost == "simpanEditOtherShareFile")
{
	?>
	<script language="javascript">
		parent.exit();
	</script>	
<?php
}
?>
</HTML>