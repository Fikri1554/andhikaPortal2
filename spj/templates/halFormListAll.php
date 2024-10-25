<?php
require_once("../../config.php");
require_once("../configSpj.php");

$tglServer = $CPublic->tglServer();

if($aksiGet == "generate")
{
	$trActiveGet = $_GET['trActive'];
	$formIdGet = $_GET['formId'];
	$ownerForm = $CSpj->detilForm($formIdGet, "ownername");
	$kdCmp = $CSpj->detilForm($formIdGet, "kdcmp");
	
	$tgl = $CSpj->detilForm($formIdGet, "datefrom");
	$bln =  substr($tgl,4,2);
	$thn =  substr($tgl,2,2);
	$blnThn = $bln.$thn;
	
	$lastNum = $CSpj->lastNum($kdCmp, substr($tgl,0,4));
	$nextNum = $CPublic->zerofill($lastNum + 1, "4") ;
	
	$spjNo = $nextNum."/SPJ-".$CSpj->detilCmp($kdCmp, "inisial")."/".$blnThn;
	
	$CKoneksiSpj->mysqlQuery("UPDATE form SET formnum = ".$nextNum.", spjno = '".$spjNo."', status = 'Completed', spjdate = ".$tglServer.", updusrdtall = '".$CPublic->userWhoAct()."' WHERE formid = ".$formIdGet.";");
	$CHistory->updateLogSpj($userIdLogin, "Generate Form SPJ (formid = <b>".$formIdGet."</b>, spjno = <b>".$spjNo."</b>,ownerspj = <b>".$ownerForm."</b>)");

	$sql = $CKoneksiSpj->mysqlQuery("SELECT * FROM form WHERE deletests = '0' AND formid = ".$formIdGet);
	while($row = $CKoneksiSpj->mysqlFetch($sql))
	{
		$batchno = date("Ymd");
		$txtEntryDate = date("Y-m-d");
		$txtReqName = $row['ownername'];
		$vslCode = $row['vessel_code'];
		$vslName = $row['vessel_name'];

		$dataEmp = $CSpj->getDivByEmpNo($row['ownerempno']);

		$slcInitCompany = $dataEmp['cmpcode'];
		$slcComName = strtoupper($dataEmp['nmcmp']);
		$slcUnitName = $dataEmp['nmdiv'];

		$txtBarcodeNo = $CPaymentAdv->getNewBarcode();
		$txtBarcode = "P".$CPaymentAdv->getFormatNo($txtBarcodeNo,7);
		$txtNoInvoice = $spjNo;
		$txtAmount = $row['cashadvance'];
		$txtCurrency = $row['currency'];
		$txtRemark = $row['note'];
		$userId = $row['ownerid'];
		$dateNow = $txtEntryDate;

		if($txtAmount > 0)
		{
			$sqlIns = "INSERT INTO paymentadvance.datapayment (doc_type,batchno,entry_date,request_name,vessel_code,vessel_name,init_company,company_name,divisi,barcode,barcode_no,mailinvno,amount,currency,remark,add_userId,add_userDate,st_submit) VALUES ('advance','".$batchno."','".$txtEntryDate."','".$txtReqName."','".$vslCode."','".$vslName."','".$slcInitCompany."','".$slcComName."','".$slcUnitName."','".$txtBarcode."','".$txtBarcodeNo."','".$txtNoInvoice."','".$txtAmount."','".$txtCurrency."','".$txtRemark."','".$userId."','".$dateNow."','N')";

			$CkoneksiPaymentAdv->mysqlQuery($sqlIns);
		}
	}
	$CKoneksiSpj->bukaKoneksi();

	//Notifikasi Email
	// $CSpj->emailKeOwner("emailComplForm", $formIdGet, $CPublic, $CEmployee, $CNotifSpj, $db, $link, "");//owner form
	
	//Notifikasi Desktop
	$notes = "Your SPJ Form has been Completed.";
	// $ownerFormEmpNo = $CSpj->detilForm($formIdGet, "ownerempno");
	// $CSpj->desktopKeOwner($ownerFormEmpNo, $koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notes, $db);
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
	var halaman = "spjFormAll";
	
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
$i=1;
$whereSearch = "";
$sName = $_GET['sName'];
if($sName != ""){ $whereSearch .= " AND ownername LIKE '%".$sName."%' "; }

$query = $CKoneksiSpj->mysqlQuery("SELECT * FROM form WHERE (status != 'Draft' AND status != 'Revise') ".$whereSearch." AND deletests = 0 ORDER BY urutan DESC");
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
	$stsDisp1 = "";	
	$sts = "";
	if($status == "Approved" && $row['knowempno'] != 00000)
	{
		$stsDisp1 = "<div style=\"width:19px;height:15px;background-color:#F5FF46;float:left;border:1px solid #CCC;\">&nbsp;</div>";
		$sts = "Ready";
	}
	else if($status == "Completed")
	{
		$stsDisp1 = "<div style=\"width:19px;height:15px;background-color:#5EFF46;float:left;border:1px solid #CCC;\">&nbsp;</div>";
		$sts = "Completed";
	}
	else if($status == "Cancel")
	{
		// $stsDisp1 = "<div style=\"width:19px;height:15px;background-color:#5EFF46;float:left;border:1px solid #CCC;\">&nbsp;</div>";
		$stsDisp1 = "<div style=\"width:19px;height:19px;float:left;\"><img src=\"../picture/minus-circle.png\"/></div>";
		$sts = "Canceled";
	}
	else
	{
		$stsDisp1 = "<div style=\"width:19px;height:15px;background-color:#FF464A;float:left;border:1px solid #CCC;\">&nbsp;</div>";
		$sts = "Processed";
	}
	
	$onClickTr = "onClickTr('".$i."', '".$jmlRow."','".$sts."','".$formId."'); parent.pleaseWait();";
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
        <td style="color:red;">* There are no SPJ Form</td>
    </tr> 
<?php	
}
?>
</table>
</body>
<script language="javascript">
<?php
if($aksiGet == "generate")
{
	echo "parent.report('Generated');
		  parent.klikTr('".$trActiveGet."')
		  btnAksi('Completed');";
}
?>
</script>