<?php
require_once("../../config.php");

$foldSubGet = $_GET['foldSub'];
$idFoldGet = $_GET['idFold'];
$halamanGet = $_GET['halaman'];

if($halamanGet == "newFile")
{
	$aksi = "simpanNewFile";
	$judul = "Create new File";
}
if($halamanGet == "editFile")
{
	$aksi = "simpanEditFile";
	$judul = "Edit File";
	
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
	var nmFile = document.getElementById('nmFile').value;
	if(nmFile.replace(/ /g,"") == "")
	{
		document.getElementById('errorMsg').innerHTML = "File name still empty!";
		return false;
	}
	var fileAdaTidak = document.getElementById('fileAdaTidak').value;
	if(fileAdaTidak == "ada")
	{
		document.getElementById('errorMsg').innerHTML = "File name already exists!";
		return false;
	}
	var descFile = document.getElementById('descFile').value;
	
	var fileUpload = document.getElementById('fileUpload').value;
	var fileUpload2 = document.getElementById('fileUpload');
	var tipeFile = fileUpload.split('.').pop();

	var aksi = document.getElementById('aksi').value;
	if(aksi == "simpanNewFile")
	{
		if(fileUpload.replace(/ /g,"") == "")
		{
			document.getElementById('errorMsg').innerHTML = "File upload still empty";
			return false;
		}
		else
		{
			if(tipeFile != "pdf" && tipeFile != "doc" && tipeFile != "docx" && tipeFile != "xls"&& tipeFile != "xlsx"&& tipeFile != "xlsm"&& tipeFile != "ods" && tipeFile != "ppt" && tipeFile != "pptx")
			{
				document.getElementById('errorMsg').innerHTML = "File type not allowed!";
				return false;
			}
		}
	}
	
	if(aksi == "simpanEditFile")
	{
		if(fileUpload.replace(/ /g,"") != "")
		{
			if(tipeFile != "pdf" && tipeFile != "doc" && tipeFile != "docx" && tipeFile != "xls"&& tipeFile != "xlsx"&& tipeFile != "xlsm"&& tipeFile != "ods" && tipeFile != "ppt" && tipeFile != "pptx")
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
		var parameters="halaman="+aksi+"&namaFile="+namaFile+"&idFold="+idFold;
	}
	
	mypostrequest.open("POST", "../halPostFold.php", true);
	mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	mypostrequest.send(parameters);
}

function pilihUpload()
{
	var fileUpload = document.getElementById('fileUpload').value;
	var namaFile = fileUpload.split('\\').pop().split('.').shift();//NAMA FILE TANPA EKSTENSION

	document.getElementById('nmFile').value = namaFile
	
	ajaxGetFile(namaFile, "cekFileAdaTidak", 'tdFileAdaTidak');
}
</script>

<?php
$dateTime = $CPublic->dateTimeGabung();
$wktUpload = $CPublic->waktuSek();

if($halamanPost == "simpanNewFile")
{
	$nmFile = $_POST['nmFile'];	
	$descFile = $_POST['descFile'];

	$expNamaFile = explode(".", $_FILES["fileUpload"]["name"]);
	$tipeFile = end($expNamaFile);

	$idDocLast = $CFile->idDocLast($idFoldGet);
	$fileDocNew = $idDocLast."-".$idFoldGet."-".$dateTime."-".$userIdLogin.".".$tipeFile;
	
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
	$nmFile = $_POST['nmFile'];	
	$descFile = $_POST['descFile'];
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
		$fileDocNew = $idDocSek."-".$idFoldGet."-".$dateTime."-".$userIdLogin.".".$tipeFile;
		
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
?>
<input type="hidden" id="foldSub" name="foldSub" value="<?php echo $foldSubGet; ?>" />
<input type="hidden" id="idFold" name="idFold" value="<?php echo $idFoldGet; ?>" />
<input type="hidden" id="aksi" name="aksi" value="<?php echo $aksi; ?>" />
<input type="hidden" id="ide" name="ide" value="<?php echo $ideGet; ?>" />

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
    <td align="center" valign="top" height="130">
    	<form action="halNewFile.php?idFold=<?php echo $idFoldGet; ?>&ide=<?php echo $ideGet; ?>" method="post" enctype="multipart/form-data" id="formUploadFile" name="formUploadFile">
        <table cellpadding="0" cellspacing="5" width="100%" class="formInput">
        <tr>
            <td>File upload</td>
            <td height="20">
                <input type="file" class="elementSearch" name="fileUpload" id="fileUpload" size="34" onChange="pilihUpload();">
            </td>
        </tr>
        <tr valign="top">
            <td width="22%">Name</td>
            <td width="78%"><input type="text" class="elementSearch" id="nmFile" name="nmFile" size="50" style="height:28px;" value="<?php echo $nmFile; ?>" onFocus="ajaxGetFile(this.value, 'cekFileAdaTidak', 'tdFileAdaTidak');" onKeyUp="ajaxGetFile(this.value, 'cekFileAdaTidak', 'tdFileAdaTidak');"></td>
        </tr>
        <tr valign="top">
            <td>Description</td>
            <td>
                <textarea class="elementSearch" id="descFile" name="descFile" cols="52" rows="5"><?php echo $descFile; ?></textarea>
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
       <button class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" onClick="pilihBtnSave();" style="width:90px;height:55px;">
            <table class="fontBtnKecil" onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'" border="0" width="100%" height="100%">
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
{
	?>
	<script language="javascript">
		parent.exit();
	</script>	
<?php
}
?>