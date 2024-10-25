<!DOCTYPE HTML>
<?php
require_once("../../config.php");
?>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
<?php

if($aksiGet == "editCariFile" || $halamanPost == "simpanEditCariFile")
{
?>
	function exit()
	{
		parent.tb_remove(false);
		var srcIframeSek = parent.document.getElementById('srcIframeSek').value;
		parent.document.getElementById('iframeHalFolder').src = srcIframeSek;
	}
<?php
}
else
{
?>
	function exit()
	{
		var foldSub = document.getElementById('foldSub').value;
		parent.exit("no", "", foldSub, "", "", "", "closeNewFile");
	}
<?php
}
?>


function exitChild(yesNo, ide, foldSub, nmFold, descFold, contentType, aksi)
{
	parent.exit(yesNo, ide, foldSub, nmFold, descFold, contentType, aksi);
}
</script>

<?php
$pathFolder="../data/document/";

$foldSubGet = $_GET['foldSub'];
$idFoldGet = $_GET['idFold'];
$halamanGet = $_GET['halaman'];

if($halamanGet == "newFile")
{
	$halIframe = "../templates/halNewFile.php?halaman=".$halamanGet."&foldSub=".$foldSubGet."&idFold=".$idFoldGet;
	$judul = "Create New File";
}
if($halamanGet == "editFile")
{
	$halIframe = "../templates/halNewFile.php?halaman=".$halamanGet."&ide=".$ideGet."&foldSub=".$foldSubGet."&idFold=".$idFoldGet;
	$disNewFile = "disabled";
	$disNewMultiFile = "disabled";
	$judul = "Edit File";
}
if($aksiGet == "editCariFile")
{
	$idFold = $CFile->detilFile($ideGet, "idfold");
	$foldSubGet = $CFolder->detilFoldByIdFold($idFold, "foldsub")+1;
	$idFoldGet = $idFold;
	
	$halIframe = "../templates/halNewFile.php?halaman=".$aksiGet."&ide=".$ideGet."&foldSub=".$foldSubGet."&idFold=".$idFoldGet;
	$disNewFile = "disabled";
	$disNewMultiFile = "disabled";
	$judul = "Edit File";
}
if($halamanGet == "newMultiFile")
{
	$halIframe = "../templates/halNewMultiFile.php?halaman=".$halamanGet."&foldSub=".$foldSubGet."&idFold=".$idFoldGet;
	$judul = "Create New Several File";
}
if($halamanGet == "editOtherShareFile")
{
	$halIframe = "../templates/halEditOtherShareFile.php?halaman=".$halamanGet."&ide=".$ideGet."&foldSub=".$foldSubGet."&idFold=".$idFoldGet;
	$disNewFile = "disabled";
	$disNewMultiFile = "disabled";
	$judul = "Edit Other Share File";
}
?>

<input type="hidden" id="foldSub" name="foldSub" value="<?php echo $foldSubGet; ?>" />
<input type="hidden" id="idFold" name="idFold" value="<?php echo $idFoldGet; ?>" />
<input type="hidden" id="aksi" name="aksi" value="<?php echo $aksi; ?>" />
<input type="hidden" id="ide" name="ide" value="<?php echo $ideGet; ?>" />

<form name="formNewFile" method="post" action="halTambahFile.php?halaman=newFile&foldSub=<?php echo $foldSubGet; ?>&idFold=<?php echo $idFoldGet; ?>">
    <input type="hidden" id="ide" name="ide" value="<?php echo $ide; ?>" />
</form>

<form name="formNewMultiFile" method="post" action="halTambahFile.php?halaman=newMultiFile&foldSub=<?php echo $foldSubGet; ?>&idFold=<?php echo $idFoldGet; ?>">
    <input type="hidden" id="ide" name="ide" value="<?php echo $ide; ?>" />
</form>

<body bgcolor="#F8F8F8">
<table cellpadding="0" cellspacing="0" width="100%" height="98%" border="0">
<tr valign="top">
	<td>
        <span class="teksLvlFolder" style="color:#666;">UNDER FOLDER : </span><span class="teksLvlFolder" style="text-decoration:underline;"><?php echo $CFolder->detilFoldByIdFold($idFoldGet, "namefold") ; ?></span>
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
    	<button class="btnStandarKecil" type="button" style="width:80px;height:55px;" onClick="exit();" title="Close Window">
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
        <button class="btnStandarKecil" type="button" onClick="formNewFile.submit();" style="width:80px;height:55px;" <?php echo $disNewFile; ?> title="Create a New File">
            <table width="100%" height="100%">
              <tr>
                <td align="center"><img src="../../picture/Button-Add-blue-32.png" height="25"/> </td>
                
              </tr>
              <tr>
                <td align="center">NEW FILE</td>
              </tr>
            </table>
        </button>
        &nbsp;
        <button class="btnStandarKecil" type="button" onClick="formNewMultiFile.submit();" style="width:110px;height:55px;" <?php echo $disNewMultiFile; ?> title="Create a New Several File">
            <table width="100%" height="100%">
              <tr>
                <td align="center"><img src="../../picture/Documents-blue-32.png" height="25"/> </td>
                
              </tr>
              <tr>
                <td align="center">SEVERAL FILES</td>
              </tr>
            </table>
        </button>
    </td>
</tr>
</table>
</body>
</HTML>