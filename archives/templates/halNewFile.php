<!DOCTYPE HTML>
<?php
require_once("../../config.php");

$foldSubGet = $_GET['foldSub'];
$idFoldGet = $_GET['idFold'];
$halamanGet = $_GET['halaman'];
$fileExtension = $CFile->fileExtension();
$fileExtensionNValid = $CFile->fileExtensionNValid();

if($halamanGet == "newFile")
{
	$aksi = "simpanNewFile";
	$judul = "Create New File";
}
if($halamanGet == "editFile")
{
	$aksi = "simpanEditFile";
	$judul = "Edit File";
	
	$nmFileAwal = $CFile->detilFile($ideGet, "namedoc");
	$nmFile = $CFile->detilFile($ideGet, "namedoc");
	$descFile = $CFile->detilFile($ideGet, "descdoc");
}
if($halamanGet == "editCariFile")
{
	$aksi = "simpanEditCariFile";
	$judul = "Edit File";
	
	$nmFile = $CFile->detilFile($ideGet, "namedoc");
	$descFile = $CFile->detilFile($ideGet, "descdoc");
	
	$idFold = $CFile->detilFile($ideGet, "idfold");
	$foldSubGet = $CFolder->detilFoldByIdFold($idFold, "foldsub")+1;
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
	var nmFileAwal = document.getElementById('nmFileAwal').value.replace(/&/g,"%26");
	var nmFile = document.getElementById('nmFile').value.replace(/&/g,"%26");
	if(nmFile.replace(/ /g,"") == "")
	{
		document.getElementById('errorMsg').innerHTML = "File name still empty!";
		return false;
	}
	if(nmFile.search(/\\|\/|:|\*|\?|"|<|>|\|/i) != "-1")
	{
		document.getElementById('errorMsg').innerHTML = "A file name cannot contain any of the following characters : \ / : * ? \" < > |";
		return false;
	}
	
	var fileAdaTidak = document.getElementById('fileAdaTidak').value;
	if(fileAdaTidak == "ada") //jika file ada di database
	{
		if(nmFile != nmFileAwal) // jika file tidak sama dengan file awal dan sudah ada di database
		{
			document.getElementById('errorMsg').innerHTML = "File name already exists!";
			return false;
		}
	}
	
	
	var descFile = document.getElementById('descFile').value.replace(/&/g,"%26");
	
	var fileUpload = document.getElementById('fileUpload').value;
	var fileUpload2 = document.getElementById('fileUpload');
	var tipeFile = fileUpload.split('.').pop();

	var aksi = document.getElementById('aksi').value;
	var fileExtension = "<?php echo $fileExtension; ?>";
	var adaFileExt = fileExtension.indexOf(tipeFile+",");
	
	var fileExtensionNValid = "<?php echo $fileExtensionNValid; ?>";
	var adaFileExtNValid = fileExtensionNValid.indexOf(tipeFile+",");

	if(aksi == "simpanNewFile")
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
				if(adaFileExtNValid == -1)
				{
					document.getElementById('errorMsg').innerHTML = "File type not allowed!";
					return false;
				}
				else
				{
					var answer  = confirm("File type not allowed \n\rAre you sure want to continue ?");
					if(answer)
					{
					}
					else
					{
						return false;
					}
				}
			}
		}
	}
	
	if(aksi == "simpanEditFile")
	{
		if(fileUpload.replace(/ /g,"") != "")
		{
			if(adaFileExt == -1)
			{
				if(adaFileExtNValid == -1)
				{
					document.getElementById('errorMsg').innerHTML = "File type not allowed!";
					return false;
				}
				else
				{
					var answer  = confirm("File type not allowed \n\rAre you sure want to continue ?");
					if(answer)
					{
					}
					else
					{
						return false;
					}
				}
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

function ajaxGetFile(namaFile, aksi, idHalaman)
{
	var mypostrequest=new ajaxRequest()
	mypostrequest.onreadystatechange=function()
	{
		if (mypostrequest.readyState==4)
		{
			if (mypostrequest.status==200 || window.location.href.indexOf("http")==-1)
			{
				document.getElementById(idHalaman).innerHTML=mypostrequest.responseText;
			}
		}
	}
	
	if(aksi == "cekFileAdaTidak")
	{
		var idFold = document.getElementById('idFold').value;
		var namaFile = document.getElementById('nmFile').value.replace(/&/g,"%26");
		var parameters="halaman="+aksi+"&namaFile="+namaFile+"&idFold="+idFold;
	}
	
	mypostrequest.open("POST", "../halPostFold.php", true);
	mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	mypostrequest.send(parameters);
}

function pilihUpload()
{
	document.getElementById('errorMsg').innerHTML = "&nbsp;";
	
	var fileUpload = document.getElementById('fileUpload').value;
	//var namaFile = fileUpload.split('\\').pop().split('.').shift();//NAMA FILE TANPA EKSTENSION
	//document.getElementById('nmFile').value = namaFile
	
	var namaFile = fileUpload.split('\\').pop();
	document.getElementById('nmFile').value = namaFileCustom(namaFile);

	ajaxGetFile(namaFile, "cekFileAdaTidak", 'tdFileAdaTidak');
}

function namaFileCustom(namaFileParam)
{
	var nilai = '';
	var namaFile = namaFileParam.split('.');
	
	
	for(i = 0; i<=(namaFile.length-2); i++ )
	{
		if(i == 0)
		{
			nilai += namaFile[i];
		}
		else
		{
			nilai += "."+namaFile[i];
		}
	}
	
	return nilai;
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
	$convFold = $CFolder->detilFoldByIdFold($idFoldGet, "convfold");

	if($convFold == "N")
	{
		$CKoneksi->mysqlQuery("INSERT INTO mstdoc (iddoc, idfold, fileowner, namedoc, filedoc, extdoc, wktupload, lastuploadbyid, lastuploadbyname, descdoc, convfold, addusrdt) 
		VALUES ('".$idDocLast."', '".$idFoldGet."', '".$userIdLogin."', '".$nmFile."', '".$fileDocNew."', '".$tipeFile."', '".$wktUpload."', '".$userIdLogin."', '".$userFullnm."', '".$descFile."', '".$convFold."', '".$CPublic->userWhoAct()."');");
		$lastInsertId = mysql_insert_id();
		
		$foldSub = $CFolder->detilFoldByIdFold($idFoldGet, "foldsub"); // atau level
		$fileFolder = $CFolder->detilFoldByIdFold($idFoldGet, "filefold");
		$pathFilePaste = $pathArchives."data/document/LEVEL".$foldSub."/".$fileFolder."/".$fileDocNew;
	}
	if($convFold == "Y")
	{
		//$fileDoc = $nmFile.".".$tipeFile;
		//$pathDoc = $CFolder->detilFoldByIdFold($idFoldGet, "replace(pathfold , ' /','/')").$CFolder->detilFoldByIdFold($idFoldGet, "namefold")."/";
		
		$CKoneksi->mysqlQuery("INSERT INTO mstdoc (iddoc, idfold, fileowner, namedoc, filedoc, extdoc, wktupload, lastuploadbyid, lastuploadbyname, descdoc, convfold, addusrdt) 
		VALUES ('".$idDocLast."', '".$idFoldGet."', '".$userIdLogin."', '".$nmFile."', '".$fileDocNew."', '".$tipeFile."', '".$wktUpload."', '".$userIdLogin."', '".$userFullnm."', '".$descFile."', '".$convFold."', '".$CPublic->userWhoAct()."');");
		$lastInsertId = mysql_insert_id();
		
		//$pathFilePaste = $pathDoc.$fileDoc;
		$foldSub = $CFolder->detilFoldByIdFold($idFoldGet, "foldsub"); // atau level
		$fileFolder = $CFolder->detilFoldByIdFold($idFoldGet, "filefold");
		$pathFilePaste = $pathArchives."data/documentConvFold/LEVEL".$foldSub."/".$fileFolder."/".$fileDocNew;
	}

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
		
		$convFold = $CFile->detilFile($ideGet, "convfold");
		$folderDelete = $pathArchives."data/documentDel//Del-(".$dateTime.")-".$fileDocSek;
		if($convFold == "N")
		{
			$fileSek = $pathArchives."data/document/LEVEL".$foldSub."/".$fileFolder."/".$fileDocSek;
			$pathFilePaste = $pathArchives."data/document/LEVEL".$foldSub."/".$fileFolder."/".$fileDocNew;
		}
		if($convFold == "Y")
		{
			$fileDocNew = $nmFile.".".$tipeFile;
			
			$fileSek = $pathArchives."data/documentConvFold/LEVEL".$foldSub."/".$fileFolder."/".$fileDocSek;
			$pathFilePaste = $pathArchives."data/documentConvFold/LEVEL".$foldSub."/".$fileFolder."/".$fileDocNew;
			//$fileSek = $CFile->detilFile($ideGet, "pathdoc").$fileDocSek;
			//$pathFilePaste = $CFile->detilFile($ideGet, "pathdoc").$fileDocNew;
		}

		copy($fileSek, $folderDelete); //kopikan file ke folder documentDel/
		move_uploaded_file($_FILES['fileUpload']['tmp_name'], $pathFilePaste);
		unlink($fileSek); //delete file yang akan digantikan
		$CKoneksi->mysqlQuery("UPDATE mstdoc SET namedoc='".$nmFile."', filedoc='".$fileDocNew."', extdoc='".$tipeFile."', wktupload='".$wktUpload."', lastuploadbyid='".$userIdLogin."', lastuploadbyname = '".$userFullnm."', descdoc='".$descFile."', updusrdt='".$CPublic->userWhoAct()."' WHERE ide='".$ideGet."' and idfold='".$idFoldGet."' AND deletests=0;");
	}
	$CHistory->updateLog($userIdLogin, "Rubah File (ide = <b>".$ideGet."</b>)");
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
<!--
<tr>
	<td align="center" height="40">
        <table cellpadding="0" cellspacing="0" width="50%">
        <tr>
            <td align="center"><span class="teksMyFolder">:: <?php //echo $judul; ?> ::</span></td>
        </tr>
        </table>	
    </td>
</tr>
-->
<tr><td height="20">&nbsp;</td></tr>
<tr>
    <td align="center" valign="top" height="130">
    	<form action="halNewFile.php?idFold=<?php echo $idFoldGet; ?>&ide=<?php echo $ideGet; ?>" method="post" enctype="multipart/form-data" id="formUploadFile" name="formUploadFile">
        <table cellpadding="0" cellspacing="5" width="100%" class="formInput">
        <tr align="left">
            <td>File upload</td>
            <td height="20" align="left">
                <input type="file" class="elementDefault" name="fileUpload" id="fileUpload" style="width:345px" onChange="pilihUpload();" title="Choose File from LocalDisk">
            </td>
        </tr>
        <tr valign="top" align="left">
            <td width="22%">Name</td>
            <td width="78%"><input type="text" class="elementDefault" id="nmFile" name="nmFile" style="width:335px;height:17px;" value="<?php echo str_replace("\\","",$CPublic->konversiQuotes($nmFile)); ?>" onFocus="ajaxGetFile(this.value, 'cekFileAdaTidak', 'tdFileAdaTidak');" onKeyUp="ajaxGetFile(this.value, 'cekFileAdaTidak', 'tdFileAdaTidak');"></td>
        </tr>
        <tr valign="top" align="left">
            <td>Description</td>
            <td>
                <textarea class="elementDefault" id="descFile" name="descFile" style="width:335px;height:85px;"><?php echo $descFile; ?></textarea>
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
       <button class="btnStandarKecil" onClick="pilihBtnSave(); return false;" style="width:90px;height:55px;" title="Save Choosen File">
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
<tr><td id="tdFileAdaTidak">&nbsp;<input type="hidden" id="fileAdaTidak" name="fileAdaTidak" value="kosong"></td></tr>
</table>
</center>
</body>

<?php
if($halamanPost == "simpanNewFile" || $halamanPost == "simpanEditFile" || $halamanPost == "simpanEditCariFile")
{?>
	<script language="javascript">
		parent.exit();
	</script>	
<?php
}
?>
</HTML>