<!DOCTYPE HTML>
<?php
require_once("../../config.php");

$aksiPost = $_POST['aksi'];
$foldSub = $_GET['foldSub'];
$ide = $_GET['ide'];
$judul = "Detail File";

$halIframe = "../templates/halIsiDetailFile.php?ide=".$ide."&foldSub=".$foldSub;
if($aksiPost == "detailFile")
{
	$foldSub = $_POST['foldSub'];
	$ide = $_POST['ide'];
	$halIframe = "../templates/halIsiDetailFile.php?ide=".$ide."&foldSub=".$foldSub;
	$judul = "Detail File";
}
if($aksiPost == "authorFile")
{
	$foldSub = $_POST['foldSub'];
	$ide = $_POST['ide'];
	
	$halIframe = "../templates/halAuthorFile.php?foldSub=".$foldSub."&ide=".$ide."";
	$judul = "Give Access / Permissions to File";
}
?>

<script type="text/javascript">
<?php

if($aksiGet == "detilCariFile")
{
?>
	function exit()
	{
		parent.tb_remove(false);
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

</script>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<form name="formDetail" method="post" action="halDetailFile.php">
    <input type="hidden" id="foldSub" name="foldSub" value="<?php echo $foldSub; ?>" />
    <input type="hidden" id="ide" name="ide" value="<?php echo $ide; ?>" />
    <input type="hidden" id="aksi" name="aksi" value="detailFile" />
</form>

<form name="formAuthor" method="post" action="halDetailFile.php">
    <input type="hidden" id="foldSub" name="foldSub" value="<?php echo $foldSub; ?>" />
    <input type="hidden" id="ide" name="ide" value="<?php echo $ide; ?>" />
    <input type="hidden" id="aksi" name="aksi" value="authorFile" />
</form>

<body bgcolor="#F8F8F8">
<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
<tr valign="top">
	<td>
        <span class="teksLvlFolder"><?php echo $CFile->detilFile($ide, "namedoc"); ?></span>
    </td>
    <td align="right"><span class="teksLvlFolder" style="color:#999;font-size:16px;font-style:italic;">:: <?php echo $judul; ?> ::</span></td>
</tr>

<tr><td height="5" colspan="2"></td></tr>

<tr valign="top">
    <td class="tdMyFolder" colspan="2">
        <iframe width="100%" height="435" src="<?php echo $halIframe; ?>" target="iframeHalDetail" name="iframeHalDetail" id="iframeHalDetail" frameborder="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>
    </td>
</tr>

<tr><td height="5" colspan="2"></td></tr>

<tr valign="top">
	<td class="tdMyFolder" bgcolor="#FFFFFF" height="65" valign="middle" colspan="2">&nbsp;
    	<button class="btnStandarKecil" type="button" style="width:90px;height:55px;" onClick="exit();" title="Close Detail File Window">
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
    </td>
</tr>
</table>
</body>
</HTML>