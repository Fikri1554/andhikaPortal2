<?php
require_once("../../config.php");

$ideFoldGet = $_GET['ideFold'];
$idAuthorFoldGet = $_GET['idAuthorFold'];
?>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/main.js"></script>
<script language="javascript">
function ajaxGetAuthorFold(statusCentang, aksi)
{
	var mypostrequest=new ajaxRequest()
	mypostrequest.onreadystatechange=function()
	{
		if (mypostrequest.readyState==4)
		{
			if (mypostrequest.status==200 || window.location.href.indexOf("http")==-1)
			{
				document.getElementById('idHalCentangAuthor').innerHTML=mypostrequest.responseText;
			}
		}
	}
	
	if(aksi == "cekDetailFold" || aksi == "cekExpandFold" || aksi == "cekOpenFile" || aksi == "cekDetailFile" || aksi == "cekUploadFile")
	{
		var idAuthorFold = document.getElementById('idAuthorFold').value;
		var parameters="halaman="+aksi+"&idAuthorFold="+idAuthorFold+"&statusCentang="+statusCentang;
	}
	
	mypostrequest.open("POST", "../halPostFold.php", true);
	mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	mypostrequest.send(parameters);
}
</script>

<?php
if($aksiGet == "pilihAuthorEmp")
{
	$cekFolderDetail = "";
	$cekFolderExpand = "";
	$cekFileOpen = "";
	$cekFileDetail = "";
	$cekFileUpload = "";
	
	if($CFolder->detilAuthorFold($idAuthorFoldGet, "detail") == "Y")
	{
		$cekFolderDetail = "checked";
	}
	if($CFolder->detilAuthorFold($idAuthorFoldGet, "expand") == "Y")
	{
		$cekFolderExpand = "checked";
	}
	if($CFolder->detilAuthorFold($idAuthorFoldGet, "openfile") == "Y")
	{
		$cekFileOpen = "checked";
	}
	if($CFolder->detilAuthorFold($idAuthorFoldGet, "detailfile") == "Y")
	{
		$cekFileDetail = "checked";
	}
	if($CFolder->detilAuthorFold($idAuthorFoldGet, "uploadfile") == "Y")
	{
		$cekFileUpload = "checked";
	}
	
?>
	<center>
    
    <input type="hidden" id="idAuthorFold" name="idAuthorFold" value="<?php echo $idAuthorFoldGet; ?>">
	<table cellpadding="0" cellspacing="0" width="98%" height="100%" border="0">
    <tr><td id="idHalCentangAuthor"></td></tr>
    <tr align="center" valign="top">
        <td valign="top">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" class="fontMyFolderList">
            <tr>
            	<td height="20" colspan="2" style="text-decoration:underline;"><b>FOLDER</b></td>
            </tr>
            <tr>
            	<td height="20" width="20%" style="color:#309;font-weight:bold;">&nbsp;&nbsp;Detail</td><td>&nbsp;<input type="checkbox" id="folderDetail" onClick="ajaxGetAuthorFold(this.checked, 'cekDetailFold')" <?php echo $cekFolderDetail; ?>></td>
            </tr>
            <tr>
            	<td height="20" style="color:#309;font-weight:bold;">&nbsp;&nbsp;Expand</td><td>&nbsp;<input type="checkbox" id="expandFolder" onClick="ajaxGetAuthorFold(this.checked, 'cekExpandFold')" <?php echo $cekFolderExpand; ?>></td>
            </tr>
            <tr>
            	<td height="20" colspan="2" style="text-decoration:underline;"><b>FILE</b></td>
            </tr>
            <tr>
            	<td height="20" style="color:#309;font-weight:bold;">&nbsp;&nbsp;Detail</td><td>&nbsp;<input type="checkbox" id="detailFile" onClick="ajaxGetAuthorFold(this.checked, 'cekDetailFile')" <?php echo $cekFileDetail; ?>></td>
            </tr>
            <tr>
            	<td height="20" style="color:#309;font-weight:bold;">&nbsp;&nbsp;Open / Save</td><td>&nbsp;<input type="checkbox" id="openFile" onClick="ajaxGetAuthorFold(this.checked, 'cekOpenFile')" <?php echo $cekFileOpen; ?>></td>
            </tr>
            <tr>
            	<td height="20" style="color:#309;font-weight:bold;">&nbsp;&nbsp;Upload</td><td>&nbsp;<input type="checkbox" id="uploadFile" onClick="ajaxGetAuthorFold(this.checked, 'cekUploadFile')" <?php echo $cekFileUpload; ?>></td>
            </tr>
            <tr><td>&nbsp;</td></tr>
            </table>
        </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    </table>
    </center>
<?php
}
else
{
?>

<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
<tr align="center">
	<td style="background-color:#E8E8E8;font-family:sans-serif;font-weight:bold;font-size:30px;color:#CCC;">PAGE DISABLED</td>
</tr>
</table>

<?php
}
?>