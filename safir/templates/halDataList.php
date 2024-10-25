<?php 
require_once("../configSafir.php"); 

if($aksiGet == "hapusData")
{
	$idDataGet = $_GET['idData'];
	$namaKapalGet = $_GET['namaKapal'];
	$hdsnGet = $_GET['hdsn'];
	
	$statusExportHo = $CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "export_ho");
	$fileExportHo =  $CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "fileexport_ho");
	if($statusExportHo == "Y")
	{
		$fileSek = "../data/exportDoc/".$fileExportHo;
		$fileRename = "../data/exportDoc/DEL_".$CPublic->tglServer().str_replace(":","",$CPublic->jamServer())."_".$fileExportHo;
		rename($fileSek, $fileRename);
	}
	
	$CKoneksiSaf->mysqlQuery("UPDATE datalaporan SET deletests=1, delusrdt='".$CPublic->userWhoAct()."' WHERE iddata='".$idDataGet."' AND namakapal='".$namaKapalGet."' AND hdsn='".$hdsnGet."' AND deletests=0 LIMIT 1 ;");
	$CKoneksiSaf->mysqlQuery("UPDATE datainfo SET deletests=1 WHERE iddata=".$idDataGet." AND namakapal='".$namaKapalGet."' AND hdsn='".$hdsnGet."' AND deletests=0 LIMIT 1;");
	$CKoneksiSaf->mysqlQuery("UPDATE paperdescribe SET deletests=1, delusrdt='".$CPublic->userWhoAct()."' WHERE iddata=".$idDataGet." AND namakapal='".$namaKapalGet."' AND hdsn='".$hdsnGet."' AND deletests=0;");
	
	$CHistory->updateLog2($userIdLogin, "Hapus Data Laporan (iddata = <b>".$idDataGet."</b>, namakapal=<b>".$nmKapalClosed."</b>, hdsn=<b>".$hdsnClosed."</b>)");
}
?>
<script type="text/javascript" src="../js/main.js"></script>

<link href="../css/table.css" rel="stylesheet" type="text/css">
<script language="javascript">
this.window.onload = 
function() 
{
	document.getElementById('loaderImg').style.visibility = "hidden";
	parent.doneWait();
	parent.panggilEnableLeftClick();
}

window.onscroll = 
function ()
{
	document.getElementById('loaderImg').style.top = (document.pageYOffset?document.pageYOffset:document.body.scrollTop);
}

function onClickTrData(urutan, trId, idData, bgColor)
{	
	var idTrSeb = document.getElementById('idTrSeb').value;
	var bgTrSeb = document.getElementById('bgTrSeb').value;
	if(idTrSeb != "")
	{
		document.getElementById(idTrSeb).onmouseover = function onmouseover(){	this.style.backgroundColor='#F4FBF4';	}
		document.getElementById(idTrSeb).onmouseout = function onmouseout(){	this.style.backgroundColor='#FFFFFF';	}
		document.getElementById(idTrSeb).style.fontWeight='';
		document.getElementById(idTrSeb).style.backgroundColor=bgTrSeb;
		document.getElementById(idTrSeb).style.cursor = 'pointer';	
	}		
	
	document.getElementById('tr'+trId).onmouseout = '';
	document.getElementById('tr'+trId).onmouseover ='';
	document.getElementById('tr'+trId).style.fontWeight='bold';
	document.getElementById('tr'+trId).style.backgroundColor='#D3F1D5';
	document.getElementById('tr'+trId).style.cursor = 'default';
	document.getElementById('idTrSeb').value = 'tr'+trId;
	
	document.getElementById('bgTrSeb').value = bgColor;

	//parent.pilihRowDataList(idData);
	
	return false;
/*	
	parent.document.getElementById('idPersonTerpilih').value = idPerson; // berikan nilai pada input type hidden yang punya id idInvoiceTerpilih dengan nilai id invoice yang terpilih
	parent.document.getElementById('btnEditPerson').disabled = false;
	parent.document.getElementById('hiddenAksi').value = document.getElementById('hiddenAksi').value; */
}

function klikRow(idData, namaKapal, hdsn, lastUpdate, lastExport, lastImport, infoAck, ackBy, infoAccept, acceptBy, infoClose, closedBy, nilaiExport, pathFileExport)
{
	var userJenis = "<?php echo $userJenisSafir; ?>";
	//alert(infoAck);
	parent.btnAllEnabled();
	//parent.btnDisabled("btnNewData", "database--plus.png", "NEW DATA");
	//parent.btnDisabled("btnEditData", "pencil.png", "EDIT");
	parent.btnDisabled("btnExport", "document-export.png", "EXPORT");
	parent.btnDisabled("btnExportDoc", "drive-download.png", "DOWNLOAD");
	parent.btnDisabled("btnAck", "traffic-light.png", "ACKNOWLEDGE");
	parent.btnDisabled("btnVerified", "validation-valid-document.png", "VERIFIED");

	if(nilaiExport == "Y") // JIKA FIELD EXPORT DI DATABASE ADALAH "Y" MAKA BUTTON btnExportDoc ENABLE
	{
		parent.btnEnabled("btnExportDoc", "drive-download.png", "DOWNLOAD");
	}
	
	if(infoAck == "YES") 
	{
		parent.btnDisabled("btnHapusData", "cross.png", "DELETE");
		parent.btnEnabled("btnExport", "document-export.png", "EXPORT");
		//parent.btnDisabled("btnImport", "drive-download.png", "IMPORT");
		parent.btnEnabled("btnExportDoc", "drive-download.png", "DOWNLOAD");
		
		if(userJenis == "dpa")
		{
			parent.btnEnabled("btnVerified", "validation-valid-document.png", "VERIFIED");
		}
	}
	
	if(infoAck == "NO") 
	{
		if(userJenis == "safcom")
		{
			parent.btnEnabled("btnAck", "traffic-light.png", "ACKNOWLEDGE");
		}
	}
	
	if(infoClose == "YES") 
	{
		parent.btnDisabled("btnVerified", "validation-valid-document.png", "VERIFIED");
	}
	
	parent.document.getElementById('pathFileExport').href = pathFileExport;
	
	parent.document.getElementById('idData').value = idData;
	parent.document.getElementById('namaKapal').value = namaKapal;
	parent.document.getElementById('hdsn').value = hdsn;
	parent.document.getElementById('iframeDataInfo').src = "";
	parent.document.getElementById('iframeDataInfo').src = "templates/halDataInfo.php?aksi=pilihRow&idData="+idData+"&namaKapal="+namaKapal+"&hdsn="+hdsn+"&pilihBahasa={pilihBahasa}";
	parent.loadingDataInfo();

	parent.document.getElementById('vesselName').innerHTML = namaKapal;
	parent.document.getElementById('lastUpdate').innerHTML = lastUpdate; // ISI INPUT TEXT FILE LASUPDATE
	parent.document.getElementById('lastExport').innerHTML = lastExport; // ISI INPUT TEXT FILE LASEXPORT
	parent.document.getElementById('lastImport').innerHTML = lastImport; 
	parent.document.getElementById('infoAck').innerHTML = infoAck; 
	parent.document.getElementById('infoAccept').innerHTML = infoAccept; 
	parent.document.getElementById('infoClose').innerHTML = infoClose; 
	parent.document.getElementById('ackBy').innerHTML = "";
	parent.document.getElementById('acceptBy').innerHTML = "";
	parent.document.getElementById('closedBy').innerHTML = "";
	if(infoAck == "YES") 
	{
		parent.document.getElementById('ackBy').innerHTML = "(By "+ackBy+")"; 
	}
	if(infoAccept == "YES") 
	{
		parent.document.getElementById('acceptBy').innerHTML = "(By "+acceptBy+")"; 
	}
	if(infoClose == "YES") 
	{
		parent.document.getElementById('closedBy').innerHTML = "(By "+closedBy+")"; 
	}
}
</script>

<style>
.loader 
{
	position:absolute;
	left: 0px;
	top:0px;
	width: 100%;
	height: 100%;
	z-index: 0;
	background: url('../picture/loading (124).gif') 50% 50% no-repeat rgb(249,249,249);
}
</style>
<div class="loader" id="loaderImg" style="visibility:hidden;"></div>
<body onLoad="loadScroll('dataList')" onUnload="saveScroll('dataList')">
<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="bgTrSeb" name="bgTrSeb">
<table width="463" cellpadding="0" cellspacing="0" style="font:0.7em sans-serif;color:#333;">
<?php
$tabel = "";
$i=0;

$query = $CKoneksiSaf->mysqlQuery("SELECT * FROM datalaporan WHERE deletests=0 ORDER BY iddata ASC;");	
$jmlRow = $CKoneksiSaf->mysqlNRows($query);
while($row = $CKoneksiSaf->mysqlFetch($query))
{
	$i++;
	
	$bgColor = $CData->rowColor($i,"#FFFFFF", "#F4FBF4");
	
	$lastUpdate = lastUpdate($CKoneksiSaf, $row['addusrdt']);
	if(!empty($row['addusrdt']))
	{
		$lastUpdate = lastUpdate($CKoneksiSaf, $row['updusrdt']);
	}
	$lastExportHo = lastExport($CKoneksiSaf, $row['waktuexport_ho']);
	$lastImport = lastExport($CKoneksiSaf, $row['lastimport']);
	$infoAck = "NO";
	$infoAccept = "NO";
	$infoClose = "NO";
	if($row['ack'] == "Y")
	{
		$infoAck = "YES";
	}
	if($row['accept'] == "Y")
	{
		$infoAccept = "YES";
	}
	if($row['closed'] == "Y")
	{
		$infoClose = "YES";
	}
	$ackBy = $row['ackby'];
	$acceptBy = $row['acceptby'];
	$closedBy = $row['closedby'];
	
	$pathFileExport = "templates/downloadFile.php?idData=".$row['iddata']."&namaKapal=".$row['namakapal']."&hdsn=".$row['hdsn'];
		
	//$pathFileExport = "../data/exportDoc/".$row['fileexport'];

	$functionKlikRow = "klikRow('".$row['iddata']."', '".$row['namakapal']."', '".$row['hdsn']."', '".$lastUpdate."', '".$lastExportHo."', '".$lastImport."', '".$infoAck."', '".$ackBy."', '".$infoAccept."', '".$acceptBy."', '".$infoClose."', '".$closedBy."', '".$row['export_ho']."', '".$pathFileExport."');";
	$onClick = " onClick=\" onClickTrData('', '".$i."', '".$row['iddata']."', '".$bgColor."'); ".$functionKlikRow."\"";
	
	$tabel.=""?>
    <tr valign="middle" align="left" bgcolor="<?php echo $bgColor; ?>"  onMouseOver="this.style.backgroundColor='#E6FFE9';" onMouseOut="this.style.backgroundColor='<?php echo $bgColor; ?>';" id="tr<?php echo $i; ?>" style="cursor:pointer;" <?php echo $onClick; ?>>
        <td width="33" height="20" class="tabelBorderTopLeftNull" align="center"><?php echo $i; ?></td>
        <td width="153" class="tabelBorderTopLeftNull">&nbsp;<?php echo $row['noreport']; ?></td>
        <td width="277" class="tabelBorderBottomJust">&nbsp;<?php echo $row['nmvessel']; ?></td>
    </tr>
    <?php echo "";
}
echo $tabel;

function lastUpdate($CKoneksiSaf, $addusrdt)
{
	$exp = explode("/", $addusrdt);
	
	$query = $CKoneksiSaf->mysqlQuery("SELECT CONCAT(DAY('".$exp[1]."'),', ',MONTHNAME('".$exp[1]."'),' ',YEAR('".$exp[1]."')) as lastupdate");
	$row = $CKoneksiSaf->mysqlFetch($query);
	
	return $row['lastupdate']." ".$exp[2];
}

function lastExport($CKoneksiSaf, $waktuExport)
{
	$exp = explode("/", $waktuExport);
	
	$query = $CKoneksiSaf->mysqlQuery("SELECT CONCAT(DAY('".$exp[0]."'),', ',MONTHNAME('".$exp[0]."'),' ',YEAR('".$exp[0]."')) as lastexport");
	$row = $CKoneksiSaf->mysqlFetch($query);
	
	return $row['lastexport']." ".$exp[1];
}
?>
</table>
</body>

<script>
<?php
if($aksiGet == "hapusData")
{
	echo "parent.refreshDataList();";
}
?>
</script>