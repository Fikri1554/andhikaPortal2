<?php
require_once("../../config.php");

$ideFoldGet = $_GET['ideFold'];
$aksiGet == $_GET['aksi'];
$idAuthorFoldGet = $_GET['idAuthorFold'];

if($aksiGet == "deleteEmpAuthor")
{
	$CKoneksi->mysqlQuery("UPDATE tblauthorfold SET deletests = 1, delusrdt = '".$CPublic->userWhoAct()."' WHERE idauthorfold = '".$idAuthorFoldGet."' AND deletests=0;");
	$CHistory->updateLog($userIdLogin, "Hapus Employee pada tblauthorfold (idauthorfold = <b>".$idAuthorFoldGet."</b>)");
}
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<script language="javascript">
function onClickTr(trId, idAccfold, ideFoldGet )
{	
	var idTrSeb = document.getElementById('idTrSeb').value;
	if(idTrSeb != "")
	{
		document.getElementById(idTrSeb).onmouseover = function onmouseover(){	this.style.backgroundColor='#D9EDFF';	}
		document.getElementById(idTrSeb).onmouseout = function onmouseout(){	this.style.backgroundColor='#FFFFFF';	}
		document.getElementById(idTrSeb).style.fontWeight='';
		document.getElementById(idTrSeb).style.backgroundColor='#FFFFFF';
		document.getElementById(idTrSeb).style.cursor = 'pointer';	
	}
	
	document.getElementById('td'+trId).onmouseout = '';
	document.getElementById('td'+trId).onmouseover ='';
	document.getElementById('td'+trId).style.fontWeight='bold';
	document.getElementById('td'+trId).style.backgroundColor='#B0DAFF';
	document.getElementById('td'+trId).style.cursor = 'default';
	document.getElementById('idTrSeb').value = 'td'+trId;
	
	parent.pilihAuthorEmp(idAccfold, ideFoldGet);
}
</script>

<input type="hidden" id="idTrSeb" name="idTrSeb">
<table cellpadding="0" cellspacing="0" width="100%" border="0">
<tr>
	<td>
    	<table width="100%" class="fontMyFolderList">
<?php
		$i=1;
		$tabel = "";
		$query = $CKoneksi->mysqlQuery("SELECT * FROM tblauthorfold WHERE idefold='".$ideFoldGet."' AND deletests=0");
		while($row = $CKoneksi->mysqlFetch($query))
		{
			$i++;
			$tabel.= "<tr id=\"td".$i."\" onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\" onclick=\"onClickTr('".$i."', '".$row['idauthorfold']."', '".$ideFoldGet."');\" title=\"View/Edit Permission\">";
			$tabel.= "	<td class=\"tdMyFolder\" height=\"25\">
							&nbsp;<img src=\"../../picture/Button-Cross-red-32.png\" height=\"15\" onClick=\"parent.deleteEmpAuthorFold('".$row['idauthorfold']."', '".$ideFoldGet."')\" onMouseOver=\"this.style.backgroundColor='#FF888B';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\" style=\"cursor: pointer;\" title=\"Remove Access\"/>&nbsp;".$row['nama']."";
			$tabel.= "	</td>";
			$tabel.= "</tr>";
		}
		echo $tabel;
?>
        </table>
    </td>
</tr>

</table>

<?php
if($aksiGet == "deleteEmpAuthor")
{
	?>
	<script language="javascript">
		parent.refreshHalAuthorization();
	</script>	
<?php
}
?>