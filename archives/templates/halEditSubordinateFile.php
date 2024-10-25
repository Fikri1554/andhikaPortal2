<!DOCTYPE HTML>
<?php
require_once("../../config.php");

$foldSubGet = $_GET['foldSub'];
$idFoldGet = $_GET['idFold'];
$fileExtension = $CFile->fileExtension();
?>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
function exit()
{
	parent.exit();
}

function pilihBtnSave()
{	
	document.getElementById('errorMsg').innerHTML = "";
	
	var ide = document.getElementById('ide').value;
	
	var fileUpload = document.getElementById('fileUpload').value;
	var fileUpload2 = document.getElementById('fileUpload');
	var tipeFile = fileUpload.split('.').pop();

	var fileExtension = "<?php echo $fileExtension; ?>";
	var adaFileExt = fileExtension.indexOf(tipeFile+",");	
	
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

if($halamanPost == "simpanEditSubordinateFile")
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

<input type="hidden" id="ide" name="ide" value="<?php echo $ideGet; ?>" />

<body bgcolor="#F8F8F8" bottommargin="3" topmargin="3">
<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
<tr valign="top">
	<td height="25" width="50%">
    	<span class="teksLvlFolder" style="color:#666;">UNDER FOLDER : </span>
        <span class="formInput" style="text-decoration:underline;color:#00F;font-weight:bold;">
			<?php echo $CFolder->detilFoldByIdFold($idFoldGet, "namefold") ; ?>
        </span>
    </td>
    <td align="right"><span class="teksLvlFolder" style="color:#999;font-size:16px;font-style:italic;"><b>:: Edit File ::</b></span>&nbsp;</td>
</tr>

<tr valign="top">
    <td colspan="2" class="tdMyFolder" width="100%" align="center" valign="top" style="cursor:default;" bgcolor="#FFFFFF">
    <div style="width:850;height:454px;overflow:auto;top: expression(offsetParent.scrollTop);text-align:right">
    
        <table cellpadding="0" cellspacing="0" height="100%" width="75%" border="0" class="">
        <tr><td height="20">&nbsp;</td></tr>
        <tr>
            <td align="center" valign="top" height="50">
                <form action="halEditSubordinateFile.php?idFold=<?php echo $idFoldGet; ?>&ide=<?php echo $ideGet; ?>" method="post" enctype="multipart/form-data" id="formUploadFile" name="formUploadFile">
                <table cellpadding="0" cellspacing="5" width="75%" class="formInput">
                <tr>
                    <td>File upload</td>
                    <td height="20">
                        <input type="file" class="elementDefault" name="fileUpload" id="fileUpload" style="width:335px;" title="Choose File from LocalDisk">
                    </td>
                </tr>
                </table> 
                <input type="hidden" name="halaman" id="halaman" value="simpanEditSubordinateFile"> 
                </form> 
            </td>
        </tr>
        <tr><td height="20" align="center" valign="middle">&nbsp; <span id="errorMsg" class="errorMsg">&nbsp;</span></td></tr>
        <tr>
            <td align="center" height="70">&nbsp;
               <button class="btnStandarKecil" onClick="pilihBtnSave();" style="width:90px;height:55px;" title="Save Editing Subordinate's File">
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
    
    </div>
    </td>
</tr>

<tr><td height="5"></td></tr>

<tr valign="top">
	<td colspan="2" class="tdMyFolder" bgcolor="#FFFFFF" height="65" valign="middle">&nbsp;
    	<button class="btnStandarKecil" type="button" style="width:80px;height:55px;" onClick="exit();" title="Close Subordinate's Edit File Window">
            <table width="100%" height="100%">
              <tr>
                <td align="center"><img src="../../picture/Metro-Shut-Down-Blue-32.png" height="25"/> </td>
                
              </tr>
              <tr>
                <td align="center">CLOSE</td>
              </tr>
            </table>
        </button>
    </td>
</tr>
</table>
</body>

<?php
if($halamanPost == "simpanEditSubordinateFile")
{
	?>
	<script language="javascript">
		exit();
	</script>	
<?php
}
?>
</HTML>