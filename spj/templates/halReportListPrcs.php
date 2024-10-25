<?php
require_once("../../config.php");
require_once("../configSpj.php");

$tglServer = $CPublic->tglServer();
$tgl =  substr($tglServer,6,2);
$bln =  substr($tglServer,4,2);
$thn =  substr($tglServer,0,4);

if($aksiGet == "prcs")
{
	$reportIdGet = $_GET['reportId'];
	$trActive = $_GET['trActive'];
	
	$CKoneksiSpj->mysqlQuery("UPDATE report SET status = 'Processed', prosesempno = ".$userEmpNo.", updusrdt = '".$CPublic->userWhoAct()."' WHERE reportid = ".$reportIdGet.";");
	
	$CHistory->updateLogSpj($userIdLogin, "Process Report SPJ (reportid = <b>".$reportIdGet."</b>, ownerreport = <b>".$CSpj->detilForm($CSpj->detilReport($reportIdGet, "formid"), "ownername")."</b>, SPJ No. = <b>".$CSpj->detilForm($CSpj->detilReport($reportIdGet, "formid"), "spjno")."</b>)");
	
	//Notifikasi Email
	$CSpj->emailKeOwner("emailAckReport", $reportIdGet, $CPublic, $CEmployee, $CNotifSpj, $db, $link, "ownerPrc");
	
	//Notifikasi Desktop
	// $notes = "Your SPJ Report has been Completed.";
	// $ownerEmpNo = $CSpj->detilLoginSpj($CSpj->detilReport($reportIdGet, "ownerid"), "empno", $db);
	// $CSpj->desktopKeOwner($ownerEmpNo, $koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notes, $db);
}
?>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../css/cssSpj.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/main.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>

<script language="javascript">
$(document).ready(function(){
	parent.doneWait();
});

function onClickTr(trId, jmlRow, status, reportId)
{	
	var idTrSeb = document.getElementById('idTrSeb').value;
	var idTdNameSeb = document.getElementById('idTdNameSeb').value;
	var idTdTglSeb = document.getElementById('idTdTglSeb').value;
	var user = parent.document.getElementById('tipeUser').value;
	var halaman = "";
	
	if(idTrSeb != "" || idTdNameSeb != "")
	{
		document.getElementById(idTrSeb).onmouseover = function onmouseover(){	this.style.backgroundColor='#D9EDFF';	}
		document.getElementById(idTrSeb).onmouseout = function onmouseout(){	this.style.backgroundColor='#FFFFFF';	}
		document.getElementById(idTrSeb).style.fontWeight='';
		document.getElementById(idTrSeb).style.backgroundColor='#FFFFFF';
		document.getElementById(idTrSeb).style.cursor = 'pointer';	
		document.getElementById(idTdNameSeb).style.fontWeight='';// FONT TIDAK BOLD UNTUK TD YANG DIPILIH
		document.getElementById(idTdTglSeb).style.fontWeight='';
	}
	
	document.getElementById('tr'+trId).onmouseout = '';
	document.getElementById('tr'+trId).onmouseover ='';
	document.getElementById('tr'+trId).style.fontWeight='';
	document.getElementById('tr'+trId).style.backgroundColor='#B0DAFF';
	document.getElementById('tr'+trId).style.cursor = 'default';
	document.getElementById('tr'+trId).style.fontSize='10px';
	document.getElementById('idTrSeb').value = 'tr'+trId;
	
	document.getElementById('tdName'+trId).style.fontWeight='bold'; // FONT BOLD UNTUK TD YANG DIPILIH
	document.getElementById('tdTgl'+trId).style.fontWeight='bold';
	document.getElementById('idTdNameSeb').value = 'tdName'+trId; // BERI ISI idTdNameSeb DENGAN ID TD YANG DIPILIH SEBELUMNYA
	document.getElementById('idTdTglSeb').value = 'tdTgl'+trId;
	
	parent.document.getElementById('reportId').value = reportId ;
	parent.document.getElementById('trActive').value = trId ;
	parent.detailSpj(reportId,halaman);
	parent.btnAksi(status);
}

function aprvRefresh(id)
{
	document.getElementById('tr'+id).click();
}
</script>

<body onLoad="loadScroll('transList');" onUnload="saveScroll('transList');">

<table width="99%">
<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="idTdNameSeb" name="idTdNameSeb">
<input type="hidden" id="idTdTglSeb" name="idTdTglSeb">

<?php
$kadivFinanceEmpno =  $CEmployee->detilDiv("040", "divhead");
$i=1;
if($userEmpNo == $kadivFinanceEmpno)
{
	$query = $CKoneksiSpj->mysqlQuery("SELECT * FROM report WHERE ((status != 'Draft' AND status != 'Revise') OR reasonempno = ".$userEmpNo.") AND ackempno != 00000 AND periksaempno != 00000 AND deletests = 0 ORDER BY urutan DESC");
$jmlRow = $CKoneksiSpj->mysqlNRows($query);
while($row = $CKoneksiSpj->mysqlFetch($query))
{	
	$reportId = $row['reportid'];
	$tglReport = $row['tglreport'];
	$thn =  substr($tglReport,0,4);
	$bln =  substr($tglReport,4,2);
	$tgl =  substr($tglReport,6,2);
	$echoTglReport = $CPublic->bulanSetengah($bln, "eng")." ".$tgl.", ".$thn;
		
	$status = $row['status'];
	$stsDisp = "";
	$stsDisp1 = "";	
	
	if($status == "Wait" || $status == "Acknowledged" || $status == "Checked" || $row['prosesempno'] == 00000)
	{
		$stsDisp1 = "<div style=\"width:19px;height:15px;background-color:#FF464A;float:left;border:1px solid #CCC;\">&nbsp;</div>";
	}

	if($status == "Processed" || $row['prosesempno'] != 00000)
	{
		$stsDisp1 = "<div style=\"width:19px;height:15px;background-color:#5EFF46;float:left;border:1px solid #CCC;\">&nbsp;</div>";
	}
	
	if($status == "Revise")
	{
		$stsDisp1 = "<div style=\"width:19px;height:15px;float:left;\"><img src=\"../picture/hammer.png\"/></div>";
	}
	
	$proses = "";
	if($row['prosesempno'] != "00000")
	{
		$proses = "exm";
	}
	if($status == "Revise")
	{
		$proses = $status;
	}
	
	$onClickTr = "onClickTr('".$i."', '".$jmlRow."','".$proses."','".$reportId."'); parent.pleaseWait();";
	//$onClickTr = "onClickTr('".$i."', '".$jmlRow."','".$status."','".$reportId."');";
?>

    <tr style="cursor:pointer;" id="tr<?php echo $i;?>" onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';" onClick="<?php echo $onClickTr;?>">
        <td class="spjTdMyFolder">
            <table width="100%">
            <tr class="fontMyFolderList" height="17">
                <td width="7%" align="center" style="color:#000080;font-weight:bold;font-family:Tahoma;"><?php echo $i; ?></td>
                <td width="51%" id="tdName<?php echo $i;?>" title="">
                	<?php echo $CPublic->potongKarakter($CSpj->detilLoginSpj($row['ownerid'], "userfullnm", $db), 14)?>&nbsp;
                </td>
                <td id="tdTgl<?php echo $i;?>" width="35%" align="left">
                	<?php echo $echoTglReport;?>
                </td>
                <td width="7%" align="center" style=" <?php echo $stsDisp;?>"><?php echo $stsDisp1;?></td>
            </tr>
            </table>
        </td>
    </tr>
<?php	
$i++;}
if($jmlRow == 0)
{
?>
	<tr class="fontMyFolderList" height="17">
        <td style="color:red;">* There are no SPJ Report need your Process</td>
    </tr>
<?php
}
}
?>

</table>
</body>
<script language="javascript">
<?php
if($aksiGet == "prcs")
{
	echo "parent.report('SPJ Report Succesfully Processed');
		  parent.klikTr('".$trActive."');";
}
?>
</script>