<!DOCTYPE HTML>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<?php
require_once("../../config.php");

$empNoGet = $_GET['empNo'];

$disSubFolder = "";
$disSubDailyAct = "";
if($userSubCustom == "Y")
{
	$userIdSub = $CLogin->detilLoginByEmpno($empNoGet, "userid");
	if($CEmployee->detilSubCustomByUser($userIdLogin, $userIdSub, "sub_folder") == "N")
	{
		$disSubFolder = "disabled";
	}
	if($CEmployee->detilSubCustomByUser($userIdLogin, $userIdSub, "sub_dailyact") == "N")
	{
		$disSubDailyAct = "disabled";
	}
}
?>

<script type="text/javascript">

function klikBtn(aksi)
{
	parent.tb_remove(false);
	if(aksi == "folder")
	{
		parent.loadUrl("../index.php?aksi=openSubordinateFolder&empNo=<?php echo $empNoGet; ?>"); return false;
	}
	if(aksi == "dailyAct")
	{
		parent.loadUrl("../index.php?aksi=openSubordinateDailyAct&empNo=<?php echo $empNoGet; ?>"); return false;
	}
}



</script>

<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
<tr><td height="60px"></td></tr>
<tr>
	<td align="center">&nbsp;
    	<button class="btnStandarKecil" type="button" style="width:120px;height:80px;" onClick="klikBtn('folder');" title="Go to Subordinate's Folder" <?php echo $disSubFolder; ?>>
            <table width="100%" height="100%" border="0">
              <tr>
                <td align="center"><img src="../../picture/Archive-blue-32.png" height="25"/> </td>
                
              </tr>
              <tr>
                <td align="center" height="50">SUBORDINATE'S FOLDER</td>
              </tr>
            </table>
        </button>
        &nbsp;&nbsp;&nbsp;
        <button class="btnStandarKecil" type="button" style="width:120px;height:80px;" onClick="klikBtn('dailyAct');" title="Go to Subordinate's Daily Activity" <?php echo $disSubDailyAct; ?>>
            <table width="100%" height="100%" border="0">
              <tr>
                <td align="center"><img src="../../picture/Presentation-blue-32.png" height="25"/> </td>
                
              </tr>
              <tr>
                <td align="center" height="50">SUBORDINATE'S DAILY ACTIVITY</td>
              </tr>
            </table>
        </button>
        &nbsp;
    </td>
</tr>
</table>
</HTML>