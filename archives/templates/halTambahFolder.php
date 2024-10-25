<!DOCTYPE HTML>
<?php
require_once("../../config.php");
?>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
function exit()
{
	var foldSub = document.getElementById('foldSub').value;
	parent.exit("no", "", foldSub, "", "", "", "closeNewFolder");
}

function exitChild(yesNo, ide, foldSub, nmFold, descFold, contentType, aksi)
{
	parent.exit(yesNo, ide, foldSub, nmFold, descFold, contentType, aksi);
}
</script>

<?php
$foldSubGet = $_GET['foldSub'];
$idFoldRefGet = $_GET['idFoldRef'];
$halamanGet = $_GET['halaman'];

$disExistingFolder = "";
$disNewFolder = "";
$disNewMultiFolder = "";

if($halamanGet == "newFolder")
{
	$halIframe = "../templates/halNewFolder.php?halaman=".$halamanGet."&foldSub=".$foldSubGet."&idFoldRef=".$idFoldRefGet;
	$judul = "Create New Folder";
}
if($halamanGet == "newMultiFolder")
{
	$halIframe = "../templates/halNewMultiFolder.php?halaman=".$halamanGet."&foldSub=".$foldSubGet."&idFoldRef=".$idFoldRefGet;
	$judul = "Create New Several Folders";
}
if($halamanGet == "editFolder")
{
	$halIframe = "../templates/halNewFolder.php?halaman=".$halamanGet."&foldSub=".$foldSubGet."&ide=".$ideGet."&idFoldRef=".$idFoldRefGet;
	$disNewFolder = "disabled";
	$disNewMultiFolder = "disabled";
	$disExistingFolder = "disabled";
	$judul = "Edit Folder";
}
if($halamanGet == "existingFolder")
{
	$halIframe = "../templates/halExistingFolder.php?foldSub=".$foldSubGet."&idFoldRef=".$idFoldRefGet;
	$judul = "Create From Existing Folder ";
}
?>

<input type="hidden" id="foldSub" name="foldSub" value="<?php echo $foldSubGet; ?>" />
<input type="hidden" id="aksi" name="aksi" value="<?php echo $aksi; ?>" />
<input type="hidden" id="ide" name="ide" value="<?php echo $ideGet; ?>" />

<form name="formNewFolder" method="post" action="halTambahFolder.php?halaman=newFolder&foldSub=<?php echo $foldSubGet; ?>&idFoldRef=<?php echo $idFoldRefGet; ?>">
    <input type="hidden" id="ide" name="ide" value="<?php echo $ide; ?>" />
</form>

<form name="formNewMultiFolder" method="post" action="halTambahFolder.php?halaman=newMultiFolder&foldSub=<?php echo $foldSubGet; ?>&idFoldRef=<?php echo $idFoldRefGet; ?>">
    <input type="hidden" id="ide" name="ide" value="<?php echo $ide; ?>" />
</form>

<form name="formExistingFolder" method="post" action="halTambahFolder.php?halaman=existingFolder&foldSub=<?php echo $foldSubGet; ?>&idFoldRef=<?php echo $idFoldRefGet; ?>">
    <input type="hidden" id="ide" name="ide" value="<?php echo $ide; ?>" />
</form>

<body bgcolor="#F8F8F8">
<table cellpadding="0" cellspacing="0" width="100%" height="98%" border="0">
<tr valign="top">
	<td>
        <span class="teksLvlFolder" style="color:#666;">UNDER FOLDER : </span><span class="teksLvlFolder" style="text-decoration:underline;"><?php echo $CFolder->detilFoldByIdFold($idFoldRefGet, "namefold") ; ?></span>
    </td>
    <td align="right"><span class="teksLvlFolder" style="color:#999;font-size:16px;font-style:italic;">:: <?php echo $judul; ?> ::</span></td>
</tr>

<tr><td height="5" colspan="2"></td></tr>

<tr valign="top">
    <td class="tdMyFolder" colspan="2">
        <iframe width="100%" height="435" src="<?php echo $halIframe; ?>" target="iframeHalDetail" name="iframeHalDetail" id="iframeHalDetail" frameborder="0" marginwidth="0" marginheight="0" scrolling="auto"></iframe>
    </td>
</tr>

<tr><td height="5" colspan="2"></td></tr>

<tr valign="top">
	<td class="tdMyFolder" bgcolor="#FFFFFF" height="65" valign="middle" colspan="2">&nbsp;
    	<button class="btnStandarKecil" type="button" style="width:90px;height:55px;" onClick="exit();" title="Close Window">
            <table width="100%" height="100%">
              <tr>
                <td align="center"><img src="../../picture/Metro-Shut-Down-Blue-32.png" height="25"/> </td>
                
              </tr>
              <tr>
                <td align="center">CLOSE</td>
              </tr>
            </table>
        </button>
        &nbsp;
        <button class="btnStandarKecil" type="button" style="width:95px;height:55px;" onClick="formNewFolder.submit();" <?php echo $disNewFolder; ?> title="Go to Create a New Folder">
            <table width="100%" height="100%">
              <tr>
                <td align="center"><img src="../../picture/Button-Add-blue-32.png" height="25"/> </td>
                
              </tr>
              <tr>
                <td align="center">NEW FOLDER</td>
              </tr>
            </table>
        </button>
        &nbsp;
        <button class="btnStandarKecil" type="button" onClick="formNewMultiFolder.submit();" style="width:130px;height:55px;" <?php echo $disNewMultiFolder; ?> title="Go to Create a New Several Folder">
            <table width="100%" height="100%">
              <tr>
                <td align="center"><img src="../../picture/doubleFolderBlue.png" height="25"/> </td>
                
              </tr>
              <tr>
                <td align="center">SEVERAL FOLDERS</td>
              </tr>
            </table>
        </button>
        &nbsp;
        <button class="btnStandarKecil" type="button" onClick="formExistingFolder.submit();" style="width:120px;height:55px;" <?php echo $disExistingFolder; ?> title="Go to Create a New Existing Folder">
            <table width="100%" height="100%">
              <tr>
                <td align="center"><img src="../../picture/Inbox-blue-32.png" height="25"/> </td>
                
              </tr>
              <tr>
                <td align="center">EXISTING	FOLDER</td>
              </tr>
            </table>
        </button>
    </td>
</tr>
</table>
</body>
</HTML>