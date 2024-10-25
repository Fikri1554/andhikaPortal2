<!DOCTYPE HTML>
<?php
require_once("../../config.php");
require_once("../configSpj.php");

$tglServer = $CPublic->tglServer();
$tgl =  substr($tglServer,6,2);
$bln =  substr($tglServer,4,2);
$thn =  substr($tglServer,0,4);

$sName = $_GET['sName'];
$sDate = $_GET['sDate'];

if($aksiGet == "cancel")
{
	$reportIdGet = $_GET['reportId'];
	$formId = $CSpj->detilReport($reportIdGet, "formid");
	$spjNo = $CSpj->detilForm($formId, "spjno");
	
	$CKoneksiSpj->mysqlQuery("UPDATE report SET status = 'Draft', ackempno = 00000, updusrdt = '".$CPublic->userWhoAct()."' WHERE reportId = ".$reportIdGet." AND deletests = 0;");
	$CHistory->updateLogSpj($userIdLogin, "Membatalkan Report SPJ (reportid = <b>".$reportIdGet."</b>, Nomor SPJ = <b>".$spjNo."</b>)");
}

if($aksiGet == "submit")
{
	$reportIdGet = $_GET['reportId'];
	$spjNo = $CSpj->detilForm($CSpj->detilReport($reportIdGet, "formid"),"spjno");
	$statusBefore = $CSpj->detilReport($reportIdGet, "status");
	$ownerId = $CSpj->detilReport($reportIdGet, "ownerid");
	$ownerEmpno = $CSpj->detilLoginSpj($ownerId, "empno", $db);
	$ownerFullNm = $CSpj->detilLoginSpj($ownerId, "userfullnm", $db);
	
	$urutanAkhir = $CSpj->urutanAkhirReport();
	$urutan = $urutanAkhir + 1;
	
	$CKoneksiSpj->mysqlQuery("UPDATE report SET urutan = ".$urutan.", status = 'Wait', ackempno = 00000, periksaempno = 00000, prosesempno = 00000, updusrdtrpt = '".$CPublic->userWhoAct()."' WHERE reportid = ".$reportIdGet." AND deletests = 0;");
	
	$aproverId = $CSpj->detilReport($reportIdGet, "kadivid");
	if($statusBefore != "Revise" && $ownerId != $ceoId)// notif untuk kondisi bukan revisi dan bukan ceo
	{
		$CHistory->updateLogSpj($userIdLogin, "Submit Report SPJ (reportid = <b>".$reportIdGet."</b>, SPJ No. = <b>".$spjNo."</b>)");
		//Notifikasi Email
		$CSpj->emailKeAtasan("emailNewReport", $aproverId, $userEmpNo, $reportIdGet, $CPublic, $CEmployee, $CNotifSpj, $db, $link, "");
		//Notifikasi Desktop
		//$notes = $userFullnm." has submitted SPJ Report. It requires your Acknowledge.";
		//$CSpj->desktopKeAtasan($userIdLogin, $aproverId, $koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notes, $db);
	}
	if($statusBefore == "Revise" && $ownerId != $ceoId)// notif untuk kondisi revisi dan bukan ceo
	{
		$CHistory->updateLogSpj($userIdLogin, "Submit Revise Report SPJ (reportid = <b>".$reportIdGet."</b>, SPJ No. = <b>".$spjNo."</b>)");
		//Notifikasi Email
		$CSpj->emailKeAtasan("emailNewReport", $aproverId, $ownerEmpno, $reportIdGet, $CPublic, $CEmployee, $CNotifSpj, $db, $link, "rev");
		//Notifikasi Desktop
		//$notes = $userFullnm." has revised current SPJ Report. It requires your Acknowledge.";
		//$CSpj->desktopKeAtasan($userIdLogin, $aproverId, $koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notes, $db);
	}
	
	if($ownerId == $ceoId && $createdBy == 00000)// notif untuk kondisi report milik ceo
	{
		$CHistory->updateLogSpj($userIdLogin, "Submit Report SPJ (reportid = <b>".$reportIdGet."</b>, SPJ No. = <b>".$spjNo."</b>)");
		// Khusus CEO, proses lgsg otomatis Acknowledge, dan menuju tahap Pemeriksaan oleh HR GA Dept.
		$ackEmpno= $CSpj->detilForm($CSpj->detilReport($reportIdGet, "formid"),"kadivempno");
		$CKoneksiSpj->mysqlQuery("UPDATE report SET status = 'Acknowledged', ackempno = ".$ackEmpno.", updusrdt = '".$CPublic->userWhoAct()."' WHERE reportid = ".$reportIdGet.";");
		
		//Notifikasi Email
		$CSpj->emailKeAdmin("emailAckReport", $reportIdGet, $CPublic, $CEmployee, $CNotifSpj, $db, $link, "admin");
		//$CSpj->emailKeKadivHR("emailAckReport", $reportIdGet, $CPublic, $CEmployee, $CNotifSpj, $db, $link, "kadivHr");
		
		//Notifikasi Desktop
		//$notesAdmin = $ownerFullNm."''s SPJ Report has been Acknowledged. It requires your Check to proccess it.";
		//$notesAdmin = $ownerFullNm." has submitted SPJ Report. It requires your Acknowledge.";
		//$CSpj->desktopKeAdmin($koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notesAdmin, $db);
	}
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
		document.getElementById(idTdNameSeb).style.fontSize='12px';	
		document.getElementById(idTdNameSeb).style.fontWeight='';// FONT TIDAK BOLD UNTUK TD YANG DIPILIH
		document.getElementById(idTdTglSeb).style.fontWeight='';
		document.getElementById(idTdTglSeb).style.fontSize='12px';	
	}
	
	document.getElementById('tr'+trId).onmouseout = '';
	document.getElementById('tr'+trId).onmouseover ='';
	document.getElementById('tr'+trId).style.fontWeight='';
	document.getElementById('tr'+trId).style.backgroundColor='#B0DAFF';
	document.getElementById('tr'+trId).style.cursor = 'default';
	document.getElementById('tr'+trId).style.fontSize='10px';
	document.getElementById('idTrSeb').value = 'tr'+trId;
	
	document.getElementById('tdName'+trId).style.fontSize='11px';
	document.getElementById('tdName'+trId).style.fontWeight='bold'; // FONT BOLD UNTUK TD YANG DIPILIH
	document.getElementById('tdTgl'+trId).style.fontWeight='bold';
	document.getElementById('tdTgl'+trId).style.fontSize='11px'
	document.getElementById('idTdNameSeb').value = 'tdName'+trId; // BERI ISI idTdNameSeb DENGAN ID TD YANG DIPILIH SEBELUMNYA
	document.getElementById('idTdTglSeb').value = 'tdTgl'+trId;
	//parent.document.getElementById('status').innerHTML = status;
	
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

<table width="100%">
<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="idTdNameSeb" name="idTdNameSeb">
<input type="hidden" id="idTdTglSeb" name="idTdTglSeb">

<?php
$whereSearch = "";
if($sName != ""){ $whereSearch .= " AND B.spjno LIKE '%".$sName."%' "; }
if($sDate != ""){ $whereSearch .= " AND A.tglreport = '".$sDate."' "; }
$i=1;
if($userJenisSpj == "admin")
{
	// $query = $CKoneksiSpj->mysqlQuery("SELECT * FROM report WHERE (ownerid = ".$userIdLogin." OR createdby != 00000) AND deletests = 0 ORDER BY urutan DESC");
	if($whereSearch == "")
	{
		$query = $CKoneksiSpj->mysqlQuery("SELECT * FROM report WHERE deletests = 0 ORDER BY urutan DESC");
	}else{	
		$query = $CKoneksiSpj->mysqlQuery("SELECT A.* FROM report A LEFT JOIN form B ON A.formid = B.formid WHERE A.deletests = 0 ".$whereSearch." ORDER BY A.urutan DESC");
	}
}
if($userJenisSpj != "admin")
{
	if($whereSearch == "")
	{
		$query = $CKoneksiSpj->mysqlQuery("SELECT * FROM report WHERE ownerid = ".$userIdLogin." AND deletests = 0 ORDER BY urutan DESC");
	}else{
		$query = $CKoneksiSpj->mysqlQuery("SELECT A.* FROM report A LEFT JOIN form B ON A.formid = B.formid WHERE A.deletests = 0 ".$whereSearch." ORDER BY A.urutan DESC");
	}
}
$jmlRow = $CKoneksiSpj->mysqlNRows($query);
while($row = $CKoneksiSpj->mysqlFetch($query))
{	
	$reportId = $row['reportid'];
	$formId = $row['formid'];
	$tglReport = $row['tglreport'];
	$thn =  substr($tglReport,0,4);
	$bln =  substr($tglReport,4,2);
	$tgl =  substr($tglReport,6,2);
	$tglReportEcho = "";
	if($tglReport != "")
	{
		$tglReportEcho = $CPublic->bulanSetengah($bln, "eng")." ".$tgl.", ".$thn;
	}
	
	$status = $row['status'];
	$stsDisp = "";
	$stsDisp1 = "";	
	
	
	if($status == "Draft")
	{
		$stsDisp1 = "<div style=\"width:19px;height:19px;float:left;\"><img src=\"../picture/script-text-24.png\" width=\"18px\"/></div>";
	}
	if($status == "Wait")
	{
		$stsDisp1 = "<div style=\"width:19px;height:19px;float:left;\"><img src=\"../picture/hourglass-select-remain.png\" width=\"18px\"/></div>";
	}
	if($status == "Revise")
	{
		$stsDisp1 = "<div style=\"width:19px;height:19px;float:left;\"><img src=\"../picture/hammer.png\"/></div>";
	}
	if($status == "Acknowledged")
	{
		$stsDisp1 = "<div style=\"width:19px;height:19px;float:left;\"><img src=\"../picture/eye.png\" width=\"18px\"/></div>";
	}
	if($status == "Checked")
	{
		$stsDisp1 = "<div style=\"width:19px;height:19px;float:left;\"><img src=\"../picture/thumb-up-24.png\" width=\"18px\"/></div>";
	}
	if($status == "Processed")
	{
		$stsDisp1 = "<div style=\"width:19px;height:19px;float:left;\"><img src=\"../picture/tick-24.png\" width=\"18px\"/></div>";
	}
	
	$onClickTr = "onClickTr('".$i."', '".$jmlRow."','".$status."','".$reportId."'); parent.pleaseWait();";
?>

    <tr style="cursor:pointer;" id="tr<?php echo $i;?>" onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';" onClick="<?php echo $onClickTr;?>">
        <td class="spjTdMyFolder">
            <table width="100%">
            <tr class="fontMyFolderList" height="17">
                <td width="7%" align="center" style="color:#000080;font-weight:bold;font-family:Tahoma;"><?php echo $i; ?></td>
                <td width="51%" id="tdName<?php echo $i;?>" title="">
                	<?php echo $CSpj->detilForm($formId, "spjno");?>&nbsp;
                </td>
                <td id="tdTgl<?php echo $i;?>" width="35%" align="left">
                	<?php echo $tglReportEcho;?>
                </td>
                <td width="7%" align="center" style=" <?php echo $stsDisp;?>"><?php echo $stsDisp1;?></td>
            </tr>
            </table>
        </td>
    </tr>
<?php	
$i++;}

if($jmlRow == "0")
{
?>
	<tr class="fontMyFolderList" height="17">
        <td style="color:red;">* There are no SPJ Report</td>
    </tr> 
<?php	
}
?>
</table>
</body>
<script language="javascript">
<?php
if($aksiGet == "cancel")
{
	echo "parent.report('Your report has been canceled');
		  parent.refresh('Y','Y');
		  parent.refreshStatus();";
}

if($aksiGet == "submit")
{
	echo "parent.report('Your Report Succesfully Submit');
		  parent.klikTr('1')
		  btnAksi('Wait');";
}
?>
</script>
</HTML>