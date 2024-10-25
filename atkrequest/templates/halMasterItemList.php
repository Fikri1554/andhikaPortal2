<?php
require_once('../../config.php');
require_once('../configAtk.php');

$tglServer = $CPublic->tglServer();
$bln =  substr($tglServer,4,2);
$thn =  substr($tglServer,0,4);

$cari = mysql_real_escape_string($_GET['cari']);
//echo $thn."/".$bln;
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/main.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/archives.css" rel="stylesheet" type="text/css" />
<script language="javascript">
function onClickTr(trId, jmlRow, itemid)
{	
	var idTrSeb = document.getElementById('idTrSeb').value;
	var idTdNameSeb = document.getElementById('idTdNameSeb').value;
	var tdQtySeb = document.getElementById('tdQtySeb').value;
	var user = parent.document.getElementById('tipeUser').value;
	
	if(idTrSeb != "" || idTdNameSeb != "")
	{
		document.getElementById(idTrSeb).onmouseover = function onmouseover(){	this.style.backgroundColor='#D9EDFF';	}
		document.getElementById(idTrSeb).onmouseout = function onmouseout(){	this.style.backgroundColor='#FFFFFF';	}
		document.getElementById(idTrSeb).style.fontWeight='';
		document.getElementById(idTrSeb).style.backgroundColor='#FFFFFF';
		document.getElementById(idTrSeb).style.cursor = 'pointer';	
		document.getElementById(idTdNameSeb).style.fontWeight='';// FONT TIDAK BOLD UNTUK TD YANG DIPILIH
		document.getElementById(tdQtySeb).style.fontWeight='';
	}
	
	document.getElementById('tr'+trId).onmouseout = '';
	document.getElementById('tr'+trId).onmouseover ='';
	document.getElementById('tr'+trId).style.fontWeight='';
	document.getElementById('tr'+trId).style.backgroundColor='#B0DAFF';
	document.getElementById('tr'+trId).style.cursor = 'default';
	document.getElementById('tr'+trId).style.fontSize='10px';
	document.getElementById('idTrSeb').value = 'tr'+trId;
	
	document.getElementById('tdName'+trId).style.fontWeight='bold'; // FONT BOLD UNTUK TD YANG DIPILIH
	document.getElementById('idTdNameSeb').value = 'tdName'+trId; // BERI ISI idTdNameDivSeb DENGAN ID TD YANG DIPILIH SEBELUMNYA
	document.getElementById('tdQty'+trId).style.fontWeight='bold';
	document.getElementById('tdQtySeb').value = 'tdQty'+trId;
	
	//parent.pilihNameDivisi(kdDiv, nmDivSent, jmlRow);
	parent.detailMaster(itemid);
}
</script>
<body>
<table width="99%">
<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="idTdNameSeb" name="idTdNameSeb">
<input type="hidden" id="tdQtySeb" name="tdQtySeb">
<?php
	$i=1;
	$sql = $CKoneksiAtk->mysqlQuery("SELECT * FROM item WHERE deletests=0 ORDER BY itemname ASC");
	if($cari != "")
	{
		$sql = $CKoneksiAtk->mysqlQuery("SELECT * FROM item WHERE deletests=0 AND itemname LIKE '%".$cari."%' ORDER BY itemname ASC");
	}
	$jmlRow = $CKoneksiAtk->mysqlNRows($sql);
	while($r = $CKoneksiAtk->mysqlFetch($sql))
	{
		$itemId = $r['itemid'];
		$stock = $CReqAtk->lastStock("stockAll", $itemId, $bln, $thn);
		if($stock == "")
		{
			$stock = "0";
		}
		
		$onClickTr = "onClickTr('".$i."','".$jmlRow."','".$r['itemid']."');";
?>
<tr onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';" id="tr<?php echo $i;?>" onClick="<?php echo $onClickTr ;?>" height="25">
    <td class="tdMyFolder">
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr class="fontMyFolderList">
            <td width="9%" align="center" style="color:#000080;font-weight:bold;font-family:Tahoma;"><?php echo $i; ?></td>
            <td width="74%" id="tdName<?php echo $i;?>" title="">
                <?php echo $CPublic->potongKarakter($r['itemname'],"35"); ?>&nbsp;
            </td>
            <td width="17%" id="tdQty<?php echo $i;?>" align="left">
            	<?php echo $stock." ".$r['qtytype']; ?>
                <!--<img src="../../picture/Button-Cross-red-32.png" width="20" onClick="alert('hapus');" onMouseOver="this.style.backgroundColor='#FF888B';" onMouseOut="this.style.backgroundColor='transparent';" style="vertical-align:middle;" title="Delete Cart Item"/>-->
            </td>
        </tr>
        </table>
    </td>
</tr>
<?php $i++;} ?>

</table>
</body>