<!DOCTYPE HTML>
<?php
require_once("../../config.php");

if($aksiGet == "sessionExpired")
{
	echo "expired";
	echo ("<script language=\"JavaScript\">
     parent.exit('no', '', foldSub, '', '', '', 'closeNewFolder');
     </script>
      ");
}

$foldSubGet = $_GET['foldSub'];
$halamanGet = $_GET['halaman'];
$idFoldRefGet = $_GET['idFoldRef'];

if($halamanGet == "newFolder")
{
	$aksi = "simpanNewFolder";
	$judul = "Create New Folder";
	
	$checkedFolder = "checked=\"checked\"";
	$checkedFile = "";
	$disKontenType = "";
}
if($halamanGet == "editFolder")
{
	$aksi = "simpanEditFolder";
	$judul = "Edit Folder";
	
	$idFold = $CFolder->detilFold($ideGet, "idfold");
	$nmFold = $CFolder->detilFold($ideGet, "namefold");
	$descFold = $CFolder->detilFold($ideGet, "descfold");
	$tipeKonten = $CFolder->detilFold($ideGet, "tipekonten");
	
	if($tipeKonten=="folder")
	{
		$checkedFolder = "checked";
		$checkedFile = "";
	}
	else if($tipeKonten=="file")
	{
		$checkedFolder = "";
		$checkedFile = "checked";
	}
	
	$jmlIsiKonten = ($CFolder->jmlFolder($idFold) + $CFile->jmlFile($idFold));
	$disKontenType = "";
	if($jmlIsiKonten != 0)
	{
		$disKontenType = "disabled";
	}
}

?>

<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
function pilihBtnSave()
{	
	document.getElementById('errorMsg').innerHTML = "&nbsp;";
	var ide = document.getElementById('ide').value;
	var nmFold = document.getElementById('nmFold').value.replace(/&/g,"%26");
	if(nmFold.replace(/ /g,"") == "")
	{
		document.getElementById('errorMsg').innerHTML = "Folder name still empty!";
		return false;
	}
	if(nmFold.search(/\\|\/|:|\*|\?|"|<|>|\|/i) != "-1")
	{
		document.getElementById('errorMsg').innerHTML = "A folder name cannot contain any of the following characters : \ / : * ? \" < > |";
		return false;
	}
	var folderAdaTidak = document.getElementById('folderAdaTidak').value;
	if(folderAdaTidak == "ada")
	{
		var aksi = document.getElementById('aksi').value;
		if(aksi == "simpanNewFolder")
		{
			document.getElementById('errorMsg').innerHTML = "Folder name already exists!";
			return false;
		}
		if(aksi == "simpanEditFolder")
		{
			if(nmFold != document.getElementById('nmFoldEdit').value.replace(/&/g,"%26"))
			{
				document.getElementById('errorMsg').innerHTML = "Folder name already exists!";
				return false;
			}
		}
	}

	var foldSub = document.getElementById('foldSub').value;
	var descFold = document.getElementById('descFold').value.replace("&","%26");
	var cekConType = document.getElementById('contentType').checked;
	var contentType="file";
	if(cekConType == true)
	{
		var contentType="folder";
	}
	var aksi = document.getElementById('aksi').value;
	
	var answer  = confirm("Are you sure want to save?");
	if(answer)
	{
		parent.exitChild("yes", ide, foldSub, nmFold, descFold, contentType, aksi);
	}
	else
	{	return false;	}
}

function ajaxGetFolder(nmFold, aksi, idHalaman)
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
	
	if(aksi == "cekFolderAdaTidak")
	{
		var idFoldRef = document.getElementById('idFoldRef').value;
		var nmFold = document.getElementById('nmFold').value.replace(/&/g,"%26");
		var parameters="halaman="+aksi+"&nmFold="+nmFold+"&idFoldRef="+idFoldRef;
	}
	
	mypostrequest.open("POST", "../halPostFold.php", true);
	mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	mypostrequest.send(parameters);
}
</script>

<input type="hidden" id="foldSub" name="foldSub" value="<?php echo $foldSubGet; ?>" />
<input type="hidden" id="idFoldRef" name="idFoldRef" value="<?php echo $idFoldRefGet; ?>" />
<input type="hidden" id="aksi" name="aksi" value="<?php echo $aksi; ?>" />
<input type="hidden" id="ide" name="ide" value="<?php echo $ideGet; ?>" />
<input type="hidden" id="nmFoldEdit" name="nmFoldEdit" value="<?php echo $nmFold; ?>" />


<body bgcolor="#FFFFFF" onLoad="ajaxGetFolder('<?php echo $CPublic->konversiQuotes($nmFold); ?>', 'cekFolderAdaTidak', 'tdFolderAdaTidak')">
<center>
<table cellpadding="0" cellspacing="0" height="100%" width="60%" border="0" class="">
<!--<tr>
	<td align="center" height="40">
        <table cellpadding="0" cellspacing="0" width="50%">
        <tr>
            <td align="center"><span class="teksMyFolder">:: <?php //echo $judul; ?> ::</span></td>
        </tr>
        </table>	
    </td>
</tr>-->
<tr><td height="20">&nbsp;</td></tr>
<tr>
    <td align="center" valign="top" height="130">
        <table cellpadding="0" cellspacing="5" width="100%" class="formInput" style="cursor:none;" border="0">
        <tr valign="top">
            <td width="22%">Name</td>
            <td width="78%"><input type="text" class="elementDefault" id="nmFold" style="width:98%;height:15px;" value="<?php echo str_replace("\\","",$CPublic->konversiQuotes($nmFold)); ?>" onFocus="ajaxGetFolder(this.value, 'cekFolderAdaTidak', 'tdFolderAdaTidak');" onKeyUp="ajaxGetFolder(this.value, 'cekFolderAdaTidak', 'tdFolderAdaTidak');">
            </td>
        </tr>
        <tr valign="top">
            <td>Description</td>
            <td><textarea class="elementDefault" id="descFold" style="width:98%;height:90px;"><?php echo $descFold; ?></textarea>
            </td>
        </tr>
        <tr>
            <td>Content Type</td>
            <td height="20" align="left">
                <input type="radio" name="contentType" id="contentType" <?php echo $checkedFolder." ".$disKontenType; ?>/>&nbsp;Folder&nbsp;&nbsp;
                <input type="radio" name="contentType" id="contentType" <?php echo $checkedFile." ".$disKontenType; ?>/>&nbsp;File
            </td>
        </tr>
        </table>  
    </td>
</tr>
<tr><td height="15px" align="center" valign="middle">&nbsp; <span id="errorMsg" class="errorMsg">&nbsp;</span></td></tr>
<tr>
    <td align="center" height="70">&nbsp;
       <button class="btnStandarKecil" onClick="pilihBtnSave();" style="width:90px;height:55px;" title="Save">
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
<tr><td id="tdFolderAdaTidak">&nbsp;<input type="hidden" id="folderAdaTidak" name="folderAdaTidak"></td></tr>
</table>
</center>
</body>
</HTML>