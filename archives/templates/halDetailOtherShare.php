<!DOCTYPE HTML>
<?php
require_once("../../config.php");

$ideGet = $_GET['ide'];
$foldSub = $CFolder->detilFold($ideGet, "foldsub");

$halIframe = "../templates/halIsiDetailFolder.php?ide=".$ideGet."&foldSub=".$foldSub;
?>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<form name="formDetail" method="post" action="halDetailFolder.php">
    <input type="hidden" id="foldSub" name="foldSub" value="<?php echo $foldSub; ?>" />
    <input type="hidden" id="ide" name="ide" value="<?php echo $ide; ?>" />
    <input type="hidden" id="aksi" name="aksi" value="detailFolder" />
</form>

<body bgcolor="#F8F8F8">
<table cellpadding="0" cellspacing="0" width="100%" height="98%">
<tr valign="top">
	<td>
        <span class="teksLvlFolder"><?php echo $CFolder->detilFold($ideGet, "namefold");?></span>
    </td>
    <td align="right"><span class="teksLvlFolder" style="color:#999;font-size:16px;font-style:italic;">:: Detail Folder ::</span></td>
</tr>

<tr><td height="5" colspan="2"></td></tr>

<tr valign="top">
    <td class="tdMyFolder" height="435" colspan="2">
        <iframe width="100%" height="100%" src="<?php echo $halIframe; ?>" target="iframeHalDetail" name="iframeHalDetail" id="iframeHalDetail" frameborder="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>
    </td>
</tr>

<tr><td height="5" colspan="2"></td></tr>

<tr valign="top">
	<td class="tdMyFolder" bgcolor="#FFFFFF" height="65" valign="middle" colspan="2">&nbsp;
    	<button class="btnStandarKecil" type="button" style="width:90px;height:55px;" onClick="parent.exit();" title="Close Detail Folder Window">
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
</HTML>