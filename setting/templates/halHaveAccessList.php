<?php
require_once("../../config.php");

$userIdHAccess = $_GET['userId'];
?>
<script language="javascript">
function onClickTr(trId, userIdNm)
{	
	var idTrSeb = document.getElementById('idTrSeb').value;
	var idTdUserFullNmSeb = document.getElementById('idTdUserFullNmSeb').value;
	
	if(idTrSeb != "" || idTdUserFullNmSeb != "")
	{
		document.getElementById(idTrSeb).onmouseover = function onmouseover(){	this.style.backgroundColor='#D9EDFF';	}
		document.getElementById(idTrSeb).onmouseout = function onmouseout(){	this.style.backgroundColor='#FFFFFF';	}
		document.getElementById(idTrSeb).style.fontWeight='';
		document.getElementById(idTrSeb).style.backgroundColor='#FFFFFF';
		document.getElementById(idTrSeb).style.cursor = 'pointer';	
		document.getElementById(idTdUserFullNmSeb).style.fontWeight='';// FONT TIDAK BOLD UNTUK TD YANG DIPILIH
	}
	
	document.getElementById('tr'+trId).onmouseout = '';
	document.getElementById('tr'+trId).onmouseover ='';
	document.getElementById('tr'+trId).style.fontWeight='';
	document.getElementById('tr'+trId).style.backgroundColor='#B0DAFF';
	document.getElementById('tr'+trId).style.cursor = 'default';
	document.getElementById('tr'+trId).style.fontSize='10px';
	document.getElementById('idTrSeb').value = 'tr'+trId;
	
	document.getElementById('tdUserFullNm'+trId).style.fontWeight='bold'; // FONT BOLD UNTUK TD YANG DIPILIH
	document.getElementById('idTdUserFullNmSeb').value = 'tdUserFullNm'+trId; // BERI ISI idTdUserFullNmSeb DENGAN ID TD YANG DIPILIH SEBELUMNYA
	
	var userIdSelect = document.getElementById('userIdSelect'+trId).value;
	parent.pilihUserHaveAccess(userIdSelect, userIdNm);
}
</script>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportal/css/main.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportal/css/archives.css" rel="stylesheet" type="text/css" />

<style>

</style>

<body>
    
<table width="99%">
<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="idTdUserFullNmSeb" name="idTdUserFullNmSeb">
<?php
if($aksiGet == "addHaveAccess")
{
	$userFullNmHA = $CLogin->detilLogin($userIdHAccess, "userfullnm");
	
	$CKoneksi->mysqlQuery("UPDATE login SET subcustom = 'Y', updusrdt = '".$CPublic->userWhoAct()."' WHERE userid = '".$userIdHAccess."' AND deletests=0;");
	$CHistory->updateLog($userIdLogin, "Beri akses Sub Custom kepada (userid = <b>".$userIdHAccess."</b>, userfullnm = <b>".$userFullNmHA."</b>)");
}

if($aksiGet == "removeSubCustom")
{
	$CKoneksi->mysqlQuery("UPDATE login SET subcustom = 'N', updusrdt = '".$CPublic->userWhoAct()."' WHERE userid = '".$userIdHAccess."' AND deletests=0;");
	$CKoneksi->mysqlQuery("UPDATE tblsubcustom SET deletests = '1', delusrdt = '".$CPublic->userWhoAct()."' WHERE userid = '".$userIdHAccess."' AND deletests=0;");
	
	$CHistory->updateLog($userIdLogin, "Hapus akses Sub Custom kepada (userid = <b>".$userIdHAccess."</b>, userfullnm = <b>".$userFullNmHA."</b>)");
}


$html = "";
$urutan = 1;
$i=0;

$query = $CKoneksi->mysqlQuery("SELECT * FROM login WHERE subcustom='Y' AND deletests=0 ORDER BY userfullnm ASC");
while($row = $CKoneksi->mysqlFetch($query))
{	
	$i++;
	$onClickTr = "onClickTr('".$i."', '".$row['userfullnm']."');";
	$html.= "";
?>

    <tr onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';" onClick="<?php echo $onClickTr; ?>" id="tr<?php echo $i; ?>">
        <td class="tdMyFolder">
            <table width="100%">
            <tr class="fontMyFolderList">
                <td width="7%" align="center" style="color:#000080;font-weight:bold;font-family:Tahoma;"><?php echo $urutan++; ?></td>
                <td id="tdUserFullNm<?php echo $i; ?>" title="Go to Access Detail">&nbsp;
                <?php echo $row['userfullnm']; ?>
                </td>
            </tr>
            </table>
        </td>
    </tr>
    <input type="hidden" id="userIdSelect<?php echo $i; ?>" name="userIdSelect<?php echo $i; ?>" value="<?php echo $row['userid']; ?>">
<?php
	$html.= "";
}
echo $html;
?>
</table>
</body>

<script>
<?php
if($aksiGet == "removeSubCustom")
{
	echo  "parent.refreshPage();";
}
?>
</script>