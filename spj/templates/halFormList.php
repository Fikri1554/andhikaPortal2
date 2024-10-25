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
	$formIdGet = $_GET['formId'];
	$dest = $CSpj->detilForm($formIdGet, "destination");
	
	$CKoneksiSpj->mysqlQuery("UPDATE form SET spjdate = '', status = 'Draft', aprvempno = 00000, aprvbyadm = 'N', knowempno = 00000, knowbyadm = 'N', updusrdt = '".$CPublic->userWhoAct()."' WHERE formid = ".$formIdGet." AND deletests = 0;");
	$CHistory->updateLogSpj($userIdLogin, "Membatalkan Form SPJ (formid = <b>".$formIdGet."</b>, tujuan dinas = <b>".$dest."</b>)");
}

if($aksiGet == "submit")
{
	$formIdGet = $_GET['formId'];
	$dest = $CSpj->detilForm($formIdGet, "destination");
	$statusBefore = $CSpj->detilForm($formIdGet, "status");
	$employeeId = $CSpj->detilForm($formIdGet, "ownerid");
	$employeeNo = $CSpj->detilForm($formIdGet, "ownerempno");
	$employeeName = $CSpj->detilLoginSpj($employeeId, "userfullnm", $db);
	
	$urutanAkhir = $CSpj->urutanAkhir();
	$urutan = $urutanAkhir + 1;
	
	$CKoneksiSpj->mysqlQuery("UPDATE form SET urutan = ".$urutan.", status = 'Wait', aprvempno = 00000, aprvbyadm = 'N', updusrdt = '".$CPublic->userWhoAct()."' WHERE formid = ".$formIdGet." AND deletests = 0;");
	
	$aproverId = $CSpj->detilLoginSpjByEmpno($CSpj->detilForm($formIdGet, "kadivempno"), "userid", $db);
	if($statusBefore != "Revise" && $employeeId != $ceoId)
	{
		$CHistory->updateLogSpj($userIdLogin, "Submit Form SPJ (formid = <b>".$formIdGet."</b>, tujuan dinas = <b>".$dest."</b>)");
		//Notifikasi Email
		$CSpj->emailKeAtasan("emailNewForm", $aproverId, $employeeNo, $formIdGet, $CPublic, $CEmployee, $CNotifSpj, $db, $link, "");
		
		//Notifikasi Desktop
		// $notes = $employeeName." has submitted SPJ Request Form. It requires your Approval.";
		// $CSpj->desktopKeAtasan($employeeId, $aproverId, $koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notes, $db);
	}
	if($statusBefore == "Revise" && $employeeId != $ceoId)
	{
		$CHistory->updateLogSpj($userIdLogin, "Revise Form SPJ (formid = <b>".$formIdGet."</b>, tujuan dinas = <b>".$dest."</b>)");
		//Notifikasi Email
		$CSpj->emailKeAtasan("emailNewForm", $aproverId, $employeeNo, $formIdGet, $CPublic, $CEmployee, $CNotifSpj, $db, $link, "rev");
		
		//Notifikasi Desktop
		// $notes = $employeeName." has revised current SPJ Request Form. It requires your Approval.";
		// $CSpj->desktopKeAtasan($employeeId, $aproverId, $koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notes, $db);
	}
	
	if($employeeId == $ceoId)// Kondisi khusus bagi CEO (lgsg ke proses Acknowledge)
	{
		$CKoneksiSpj->mysqlQuery("UPDATE form SET status = 'Approved', aprvempno = ".$employeeNo." WHERE formid = ".$formIdGet.";");
		
		//Notifikasi Email
		$CSpj->emailKeKadivHR("emailAprvForm", $formIdGet, $CPublic, $CEmployee, $CNotifSpj, $db, $link, "kadivHr");//Kadiv HR
		//Notifikasi Desktop
		// $notesKadiv = $employeeName."''s SPJ Request Form has been approved. It requires your Acknowledgement to proccess it.";
		// $CSpj->desktopKeKadivHR($koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notesKadiv, $db);//kadiv HR
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

function onClickTr(trId, jmlRow, status, formId)
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
	
	parent.document.getElementById('formId').value = formId ;
	parent.document.getElementById('trActive').value = trId ;
	parent.detailSpj(formId,halaman);
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
$whereSearch = "";
if($sName != ""){ $whereSearch .= " AND ownername LIKE '%".$sName."%' "; }
if($sDate != ""){ $whereSearch .= " AND datefrom = '".$sDate."' "; }
$i=1;
if($userJenisSpj == "admin")
{
	if($whereSearch == "")
	{
		$query = $CKoneksiSpj->mysqlQuery("SELECT * FROM form WHERE (ownerid = ".$userIdLogin." OR createdby != 00000) AND deletests = 0 ORDER BY urutan DESC");
	}else{
		$query = $CKoneksiSpj->mysqlQuery("SELECT * FROM form WHERE deletests = 0 ".$whereSearch." ORDER BY urutan DESC");
	}
	
}
if($userJenisSpj != "admin")
{
	$query = $CKoneksiSpj->mysqlQuery("SELECT * FROM form WHERE ownerid = ".$userIdLogin." AND deletests = 0 ".$whereSearch." ORDER BY urutan DESC");
}
$jmlRow = $CKoneksiSpj->mysqlNRows($query);
while($row = $CKoneksiSpj->mysqlFetch($query))
{	
	$formId = $row['formid'];
	$dateForm = $row['datefrom'];
	$thn =  substr($dateForm,0,4);
	$bln =  substr($dateForm,4,2);
	$tgl =  substr($dateForm,6,2);
	$formDate = $CPublic->bulanSetengah($bln, "eng")." ".$tgl.", ".$thn;
		
	$status = $row['status'];
	$stsDisp = "";
	$stsDisp1 = "";	
	
	
	if($status == "Draft")
	{
		$stsDisp1 = "<div style=\"width:19px;height:19px;float:left;\"><img src=\"../picture/script-text-24.png\"/></div>";
	}
	if($status == "Wait")
	{
		$stsDisp1 = "<div style=\"width:19px;height:19px;float:left;\"><img src=\"../picture/hourglass-select-remain.png\" /></div>";
	}
	if($status == "Processed")
	{
		$stsDisp1 = "<div style=\"width:19px;height:19px;float:left;\"><img src=\"../picture/book-open-next.png\"/></div>";
	}
	if($status == "Revise")
	{
		$stsDisp1 = "<div style=\"width:19px;height:19px;float:left;\"><img src=\"../picture/hammer.png\"/></div>";
	}
	if($status == "Cancel")
	{
		$stsDisp1 = "<div style=\"width:19px;height:19px;float:left;\"><img src=\"../picture/minus-circle.png\"/></div>";
	}
	if($status == "Approved")
	{
		$stsDisp1 = "<div style=\"width:19px;height:19px;float:left;\"><img src=\"../picture/thumb-up-24.png\"/></div>";
	}
	if($status == "Completed")
	{
		$stsDisp1 = "<div style=\"width:19px;height:19px;float:left;\"><img src=\"../picture/tick-24.png\"/></div>";
	}
	
	$onClickTr = "onClickTr('".$i."', '".$jmlRow."','".$status."','".$formId."'); parent.pleaseWait();";
	
	$disp = "";
	if($row['ownerid'] == $userIdLogin)
	{
		// $disp = $CPublic->potongKarakter($row['destination'], 14);
		$disp = $CPublic->potongKarakter($row['ownername'], 14);
	}
	if($row['ownerid'] != $userIdLogin)
	{
		$disp = $CPublic->potongKarakter($row['ownername'], 14);
	}
?>

    <tr style="cursor:pointer;" id="tr<?php echo $i;?>" onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';" onClick="<?php echo $onClickTr;?>">
        <td class="spjTdMyFolder">
            <table width="100%">
            <tr class="fontMyFolderList" height="17">
                <td width="7%" align="center" style="color:#000080;font-weight:bold;font-family:Tahoma;"><?php echo $i; ?></td>
                <td width="51%" id="tdName<?php echo $i;?>" title="">
                	<?php echo $disp?>&nbsp;
                </td>
                <td id="tdTgl<?php echo $i;?>" width="35%" align="left">
                	<?php echo $formDate;?>
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
        <td style="color:red;">* There are no SPJ Form</td>
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
	echo "parent.report('Cancel');
		  parent.refresh('Y','Y');
		  parent.refreshStatus();";
}

if($aksiGet == "submit")
{
	echo "parent.report('Submit');
		  parent.klikTr('1')
		  btnAksi('Wait');";
}
?>
</script>