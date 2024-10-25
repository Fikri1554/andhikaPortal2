<?php
require_once("../../config.php");
require_once("../configSpj.php");

$tglServer = $CPublic->tglServer();
$tgl =  substr($tglServer,6,2);
$bln =  substr($tglServer,4,2);
$thn =  substr($tglServer,0,4);

if($aksiGet == "ack")
{
	$formIdGet = $_GET['formId'];
	$ownerForm = $CSpj->detilForm($formIdGet, "ownername");
	$dest = $CSpj->detilForm($formIdGet, "destination");
	
	$trActive = $_GET['trActive'];
	
	$knowBy = "";
	$hist = "";
	if($CSpj->userJenis($userIdLogin) == "admin")
	{
		$knowBy = "knowbyadm = 'Y',";
		$hist = "Administrator ";
	}
	
	$CKoneksiSpj->mysqlQuery("UPDATE form SET ".$knowBy." knowempno = ".$userEmpNo.", updusrdtack = '".$CPublic->userWhoAct()."' WHERE formid = ".$formIdGet.";");
	$CHistory->updateLogSpj($userIdLogin, $hist."Acknowledge Form SPJ (formid = <b>".$formIdGet."</b>, ownerform = <b>".$ownerForm."</b>, tujuan dinas = <b>".$dest."</b>)");
	
	//Notifikasi Email
	$CSpj->emailKeAdmin("emailAckForm", $formIdGet, $CPublic, $CEmployee, $CNotifSpj, $db, $link, "");
	
	//Notifikasi Desktop
	$ownerFormEmpNo = $CSpj->detilForm($formIdGet, "ownerempno");
	$notes = "Please prepare ".$ownerForm."''s SPJ.";
	$CSpj->desktopKeAdmin($koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notes, $db);
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
	//parent.document.getElementById('status').innerHTML = status;
	
	parent.document.getElementById('formId').value = formId ;
	parent.document.getElementById('trActive').value = trId ;
	
	if(status != "Wait")
	{
		parent.detailSpj(formId,'ackPage','');
	}
	if(status == "Wait")
	{
		parent.detailSpj(formId,'ackPage','Processed');
	}
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
$kadivHrEmpno =  $CEmployee->detilDiv("050", "divhead");
$i=1;

if($CSpj->userJenis($userIdLogin) == "admin" || $userEmpNo == $kadivHrEmpno)
{
	$query = $CKoneksiSpj->mysqlQuery("SELECT * FROM form WHERE (status = 'Approved' OR status = 'Completed' OR (status = 'Revise' AND reasonempno = ".$userEmpNo.")) AND deletests = 0 ORDER BY urutan DESC");

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
	
	if($status == "Wait" || $status == "Processed" || $status == "Approved" || $row['knowempno'] == 00000)
	{
		$stsDisp1 = "<div style=\"width:19px;height:15px;background-color:#FF464A;float:left;border:1px solid #CCC;\">&nbsp;</div>";
	}

	if($status == "Completed" || $row['knowempno'] != 00000)
	{
		$stsDisp1 = "<div style=\"width:19px;height:15px;background-color:#5EFF46;float:left;border:1px solid #CCC;\">&nbsp;</div>";
	}
	
	if($status == "Revise")
	{
		$stsDisp1 = "<div style=\"width:19px;height:19px;float:left;\"><img src=\"../picture/hammer.png\"/></div>";
	}
	
	$acked = "";
	if($row['knowempno'] != "00000")
	{
		$acked = "acked";
	}
	if($status == "Revise")
	{
		$acked = "Revise";
	}
	
	$onClickTr = "onClickTr('".$i."', '".$jmlRow."','".$acked."','".$formId."'); parent.pleaseWait();";
?>

    <tr style="cursor:pointer;" id="tr<?php echo $i;?>" onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';" onClick="<?php echo $onClickTr;?>">
        <td class="spjTdMyFolder">
            <table width="100%">
            <tr class="fontMyFolderList" height="17">
                <td width="7%" align="center" style="color:#000080;font-weight:bold;font-family:Tahoma;"><?php echo $i; ?></td>
                <td width="51%" id="tdName<?php echo $i;?>" title="">
                	<?php echo $CPublic->potongKarakter($row['ownername'], 14)?>&nbsp;
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
}
else
{
?>
    <tr class="fontMyFolderList" height="17">
        <td style="color:red;">* There are no SPJ Form need your Acknowledge</td>
    </tr>       
<?php
}
?>
</table>
</body>
<script language="javascript">
<?php
if($aksiGet == "ack")
{
	echo "parent.report('SPJ Form Succesfully Acknowledged');
		  parent.klikTr('".$trActive."');";
}
?>
</script>