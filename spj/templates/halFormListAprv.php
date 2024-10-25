<?php
require_once("../../config.php");
require_once("../configSpj.php");

$tglServer = $CPublic->tglServer();
$tgl =  substr($tglServer,6,2);
$bln =  substr($tglServer,4,2);
$thn =  substr($tglServer,0,4);

if($aksiGet == "approve")
{
	$formIdGet = $_GET['formId'];
	$ownerForm = $CSpj->detilForm($formIdGet, "ownername");
	$ownerFormEmpNo = $CSpj->detilForm($formIdGet, "ownerempno");
	$dest = $CSpj->detilForm($formIdGet, "destination");
	
	$trActive = $_GET['trActive'];
	
	$aprvBy = "";
	$hist = "";
	if($CSpj->userJenis($userIdLogin) == "admin")
	{
		$aprvBy = "aprvbyadm = 'Y',";
		$hist = "Administrator ";
	}
	
	$CKoneksiSpj->mysqlQuery("UPDATE form SET ".$aprvBy." status = 'Approved', aprvempno = ".$userEmpNo.", updusrdt = '".$CPublic->userWhoAct()."' WHERE formid = ".$formIdGet.";");
	$CHistory->updateLogSpj($userIdLogin, $hist."Approve Form SPJ (formid = <b>".$formIdGet."</b>, ownerform = <b>".$ownerForm."</b>, tujuan dinas = <b>".$dest."</b>)");
	
	//Notifikasi Email
	$CSpj->emailKeOwner("emailAprvForm", $formIdGet, $CPublic, $CEmployee, $CNotifSpj, $db, $link, "owner");//owner form
	$CSpj->emailKeKadivHR("emailAprvForm", $formIdGet, $CPublic, $CEmployee, $CNotifSpj, $db, $link, "kadivHr");//Kadiv HR
	
	//Notifikasi Desktop
	// $notes = "Your SPJ Request Form has been Approved and will be processed by HR GA.";
	// $CSpj->desktopKeOwner($ownerFormEmpNo, $koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notes, $db);//owner form
	// $notesKadiv = $ownerForm."''s SPJ Request Form has been approved. It requires your Acknowledgement to proccess it.";
	// $CSpj->desktopKeKadivHR($koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notesKadiv, $db);//kadiv HR
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

function onClickTr(trId, jmlRow, status, formId, ownerId)
{	
	var idTrSeb = document.getElementById('idTrSeb').value;
	var idTdNameSeb = document.getElementById('idTdNameSeb').value;
	var idTdTglSeb = document.getElementById('idTdTglSeb').value;
	var user = parent.document.getElementById('tipeUser').value;
	var userIdLogin = $('#userIdLogin').val();
	
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
		parent.detailSpj(formId,'aprovePage','');
	}
	if(status == "Wait")
	{
		parent.detailSpj(formId,'aprovePage','Processed');
	}
	
	if(ownerId != userIdLogin)
	{
		parent.btnAksi(status);
	}
	if(ownerId == userIdLogin)
	{
		$.post( 
			"../halPost.php",
			{	halaman: 'cekSelfAprv'	},
			function(data){
				if(data == 'N')// jika tidak bisa self approve, lock button
				{
					parent.btnAksiKhusus(status);
				}
				if(data == 'Y')// jika bisa self approve, ikuti prosedure
				{
					parent.btnAksi(status);
				}
			}
		);
	}
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
<input type="hidden" id="userIdLogin" value="<?php echo $userIdLogin;?>"/>
<?php
$i=1;
$query = $CKoneksiSpj->mysqlQuery("SELECT * FROM form WHERE kadivempno = ".$userEmpNo." AND ownerempno != ".$userEmpNo." AND status != 'Draft' AND deletests = 0 ORDER BY urutan DESC");
if($CSpj->userJenis($userIdLogin) == "CEO")
{
	$query = $CKoneksiSpj->mysqlQuery("SELECT * FROM form WHERE kadivempno = ".$userEmpNo." AND status != 'Draft' AND deletests = 0 ORDER BY urutan DESC");
}
if($CSpj->userJenis($userIdLogin) == "admin")
{
	$query = $CKoneksiSpj->mysqlQuery("SELECT * FROM form WHERE status != 'Draft' AND deletests = 0 ORDER BY urutan DESC");
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
	
	$ownerId = $row['ownerid'];
		
	$status = $row['status'];
	$stsDisp = "";
	$stsDisp1 = "";	
	
	if($status == "Wait")
	{
		$stsDisp1 = "<div style=\"width:19px;height:15px;background-color:#FF464A;float:left;border:1px solid #CCC;\">&nbsp;</div>";
	}
	if($status == "Processed")
	{
		$stsDisp1 = "<div style=\"width:19px;height:15px;background-color:#FF464A;float:left;border:1px solid #CCC;\">&nbsp;</div>";
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
		$stsDisp1 = "<div style=\"width:19px;height:15px;background-color:#5EFF46;float:left;border:1px solid #CCC;\">&nbsp;</div>";
	}
	if($status == "Completed")
	{
		$stsDisp1 = "<div style=\"width:19px;height:15px;background-color:#5EFF46;float:left;border:1px solid #CCC;\">&nbsp;</div>";
	}
	
	$onClickTr = "onClickTr('".$i."', '".$jmlRow."','".$status."','".$formId."',".$ownerId."); parent.pleaseWait();";
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

if($jmlRow == "0")
{
?>
	<tr class="fontMyFolderList" height="17">
        <td style="color:red;">* There are no SPJ Form need your Approval</td>
    </tr> 
<?php	
}
?>
</table>
</body>
<script language="javascript">
<?php
if($aksiGet == "approve")
{
	echo "parent.report('SPJ Form Succesfully Approved');
		  parent.klikTr('".$trActive."');";
}
?>
</script>