<?php
require_once("../../config.php");

$userIdHAccess = $_GET['userId'];
$userIdSub = $_GET['userIdSub'];
$idCustomSub = $_GET['idCustomSub'];
?>
<script type="text/javascript" src="../../js/main.js"></script>
<script language="javascript">
function onClickTr(trId)
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
	
	var idCustomSub = document.getElementById('idCustomSub'+trId).value;
	parent.pilihUserSub(idCustomSub)
}

function ajaxGetSubCustom(statusCentang, trId, aksi)
{
	var mypostrequest=new ajaxRequest()
	mypostrequest.onreadystatechange=function()
	{
		if (mypostrequest.readyState==4)
		{
			if (mypostrequest.status==200 || window.location.href.indexOf("http")==-1)
			{
				document.getElementById('idHalCentangSubCustom').innerHTML=mypostrequest.responseText;
			}
		}
	}
	
	/*if(aksi == "cekDetailFold" || aksi == "cekExpandFold" || aksi == "cekOpenFile" || aksi == "cekDetailFile" || aksi == "cekUploadFile")
	{
		var idAuthorFold = document.getElementById('idAuthorFold').value;
		var parameters="halaman="+aksi+"&idAuthorFold="+idAuthorFold+"&statusCentang="+statusCentang;
	}*/
	
	if(aksi == "cekFolder" || aksi == "cekDailyAct" || aksi == "cekDirectSub" || aksi == "cekApprove" || aksi == "cekBtnSave")
	{
		var idCustomSub = document.getElementById('idCustomSub'+trId).value;

		var parameters="halaman="+aksi+"&idCustomSub="+idCustomSub+"&statusCentang="+statusCentang;
	}
	
	mypostrequest.open("POST", "../halPostSetting.php", true);
	mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	mypostrequest.send(parameters);
}
</script>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportal/css/main.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportal/css/archives.css" rel="stylesheet" type="text/css" />

<style>

</style>

<!--<body bgcolor="#EBEBEB">-->
<body>

<table width="99%">
<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="idTdUserFullNmSeb" name="idTdUserFullNmSeb">
<?php
if($aksiGet == "addSubCustom")
{
	$userFullNmHA = $CLogin->detilLogin($userIdHAccess, "userfullnm");
	$userFullNmSub = $CLogin->detilLogin($userIdSub, "userfullnm");
	
	$CKoneksi->mysqlQuery("INSERT INTO tblsubcustom (userid, userfullnm, sub_userid, sub_userfullnm, addusrdt)VALUES ('".$userIdHAccess."', '".$userFullNmHA."', '".$userIdSub."', '".$userFullNmSub."',  '".$CPublic->userWhoAct()."');");

	$CHistory->updateLog($userIdLogin, "Beri Hak Akses subordinate custom (<b>".$userFullNmSub."</b>) kepada (<b>".$userFullNmHA."</b>)");
}
if($aksiGet == "removeSubCustom")
{
	$userFullNmHA = $CEmployee->detilSubCustom($idCustomSub, "userfullnm");
	$userFullNmSub = $CEmployee->detilSubCustom($idCustomSub, "sub_userfullnm");
	
	$CHistory->updateLog($userIdLogin, "Hapus Hak Akses subordinate custom (<b>".$userFullNmSub."</b>) dari (<b>".$userFullNmHA."</b>)");
	
	$CKoneksi->mysqlQuery("UPDATE tblsubcustom SET deletests='1' WHERE idcustomsub='".$idCustomSub."' AND deletests=0;");	
}

$html = "";
$urutan = 1;
$i=0;

$query = $CKoneksi->mysqlQuery("SELECT * FROM tblsubcustom WHERE userid='".$userIdHAccess."' AND deletests=0 ORDER BY userfullnm ASC");
while($row = $CKoneksi->mysqlFetch($query))
{	
	$i++;
	
	$cekFolder = "";
	$cekDailyAct = "";
	$cekDirectSub = "";
	$cekApprove = "";
	$cekBtnSave = "";
	if($CEmployee->detilSubCustom($row['idcustomsub'], "sub_folder") == "Y")
	{
		$cekFolder = "checked";
	}
	if($CEmployee->detilSubCustom($row['idcustomsub'], "sub_dailyact") == "Y")
	{
		$cekDailyAct = "checked";
	}
	if($CEmployee->detilSubCustom($row['idcustomsub'], "dailyact_direct") == "Y")
	{
		$cekDirectSub = "checked";
	}
	if($CEmployee->detilSubCustom($row['idcustomsub'], "dailyact_approve") == "Y")
	{
		$cekApprove = "checked";
	}
	if($CEmployee->detilSubCustom($row['idcustomsub'], "dailyact_btnsave") == "Y")
	{
		$cekBtnSave = "checked";
	}
	
	$html.= "";
?>

    <tr onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';" onClick="onClickTr('<?php echo $i; ?>')"  id="tr<?php echo $i; ?>">
        <td class="tdMyFolder">
            <table width="100%" cellspacing="0">
            <tr class="fontMyFolderList">
                <td width="7%" height="23" align="center" style="color:#000080;font-weight:bold;font-family:Tahoma;"><?php echo $urutan++; ?></td>
                <td id="tdUserFullNm<?php echo $i; ?>">&nbsp;
                <?php echo $row['sub_userfullnm']; ?> 
                </td>
            </tr>
            <tr class="fontMyFolderList" bgcolor="#EFEFEF">
            	<td align="right"><?php echo $i; ?>.a</td>
            	<td style="font-family:Tahoma;font-size:12px;color:#369;font-weight:bold;">
                	<input type="checkbox" onClick="ajaxGetSubCustom(this.checked, '<?php echo $i; ?>', 'cekFolder');" <?php echo $cekFolder; ?>>Folder&nbsp;
                    <input type="checkbox" onClick="ajaxGetSubCustom(this.checked, '<?php echo $i; ?>', 'cekDailyAct');" <?php echo $cekDailyAct; ?>>Daily Act&nbsp;
                    <input type="checkbox" onClick="ajaxGetSubCustom(this.checked, '<?php echo $i; ?>', 'cekDirectSub');" <?php echo $cekDirectSub; ?>>Direct Sub&nbsp;
                    <input type="checkbox" onClick="ajaxGetSubCustom(this.checked, '<?php echo $i; ?>', 'cekApprove');" <?php echo $cekApprove; ?>>Approve&nbsp;
                    <input type="checkbox" onClick="ajaxGetSubCustom(this.checked, '<?php echo $i; ?>', 'cekBtnSave');" <?php echo $cekBtnSave; ?>>Button Save&nbsp;
                </td>
            </tr>
            </table>
        </td>
    </tr>
    <input type="hidden" id="idCustomSub<?php echo $i; ?>" name="idCustomSub<?php echo $i; ?>" value="<?php echo $row['idcustomsub']; ?>">
<?php
	$html.= "";
}
echo $html;
?>
<div id="idHalCentangSubCustom" style="visibility:hidden;display:none;"></div>
</table>
</body>