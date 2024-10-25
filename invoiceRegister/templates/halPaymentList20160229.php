<!DOCTYPE HTML>
<?php 
require_once("../configInvReg.php");

$aksiGet = $_GET['aksi'];
$jenisPaymentPost = $_GET['jenisPayment'];

?>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../js/payment.js"></script>
<script language="javascript">
function onClickTrOutstanding(trId, idMailInv, senderVendor, bgColor)
{
	var idTrSeb = document.getElementById('idTrSeb').value;
	var bgColorSeb = document.getElementById('bgColorSeb').value;
	
	if(idTrSeb != "")
	{
		document.getElementById(idTrSeb).onmouseover = function onmouseover(){	this.style.backgroundColor='#D9EDFF';	}
		document.getElementById(idTrSeb).onmouseout = function onmouseout(){	this.style.backgroundColor=bgColorSeb;	}
		document.getElementById(idTrSeb).style.fontWeight='';
		document.getElementById(idTrSeb).style.backgroundColor=bgColorSeb;
		document.getElementById(idTrSeb).style.cursor = 'pointer';	
		//document.getElementById(idTrSeb).style.height = "22";
		//document.getElementById(idTdNameDivSeb).style.fontWeight=''; // FONT TIDAK BOLD UNTUK TD YANG DIPILIH
	}
	
	document.getElementById('tr'+trId).onmouseout = '';
	document.getElementById('tr'+trId).onmouseover ='';
	//document.getElementById('tr'+trId).style.fontWeight='bold';
	document.getElementById('tr'+trId).style.backgroundColor='#B0DAFF';
	document.getElementById('tr'+trId).style.cursor = 'default';
	document.getElementById('tr'+trId).style.fontSize='11px';
	document.getElementById('idTrSeb').value = 'tr'+trId;
	//document.getElementById('tr'+trId).style.height = "";
	
	document.getElementById('bgColorSeb').value = bgColor;
	parent.document.getElementById('idMailInv').value = idMailInv;
	parent.teksMap("PAYMENT > OUTSTANDING INVOICE > "+senderVendor);
	<?php
	echo "\n\r";
	if($userJenisInvReg == "user")
	{ // JIKA AKSES BUKAN BERNILAI DISABLED (ATAU SAMA DENGAN "") MAKA ...
		/*if($CInvReg->aksesInvReg($userIdSession, "btnpayment_detailoutstanding") == "")
		{
			echo "parent.enabledBtn('btnPayOutDetail');";
		}*/
	}
	if($userJenisInvReg == "admin")
	{
		//echo "parent.enabledBtn('btnPayOutDetail');"; 
	}
	echo "\n\r";
	?>
}

window.onload = 
function() 
{
	document.oncontextmenu = function(){	return false;	}; 
	document.getElementById('loaderImg').style.visibility = "hidden";
	parent.doneWait();
	parent.panggilEnableLeftClick();
	
	parent.disabledBtn('btnAckDetail');
}

$(window).scroll(function(){
$('#judul').css('left','-'+$(window).scrollLeft());
});
</script>

<link href="../css/invReg.css" rel="stylesheet" type="text/css" />
<link href="../css/table.css" rel="stylesheet" type="text/css" />

<body>
<div class="loader" id="loaderImg" style="visibility:hidden;"></div>
<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="bgColorSeb" name="bgColorSeb">

<?php
function batchnoToDate($batchno) // BATCHNO YYYYMMDD
{
	$tgl = substr($batchno,6,2);
	$bln = substr($batchno,4,2);
	$thn = substr($batchno,0,4);
	
	return $tgl."/".$bln."/".$thn;
}

function orderBy($sortBy, $ascBy)
{
	$orderBy = "";
	/*if($sortBy == "senVenAsc")
		$orderBy = "ORDER BY CONCAT(sendervendor1,sendervendor2name) ASC";
	if($sortBy == "senVenDesc")
		$orderBy = "ORDER BY CONCAT(sendervendor1,sendervendor2name) DESC ";
	if($sortBy == "companyAsc")
		$orderBy = "ORDER BY companyname ASC";
	if($sortBy == "companyDesc")
		$orderBy = "ORDER BY companyname DESC";
	if($sortBy == "mailDateAsc")
		$orderBy = "ORDER BY UNIX_TIMESTAMP(tglinvoice) ASC";
	if($sortBy == "mailDateDesc")
		$orderBy = "ORDER BY UNIX_TIMESTAMP(tglinvoice) DESC";
	if($sortBy == "entryAsc")
		$orderBy = "ORDER BY CONCAT(DATE(SUBSTRING(addusrdt, 7, 8)),' ',SUBSTRING(addusrdt, 16, 8)) ASC";
	if($sortBy == "entryDesc")
		$orderBy = "ORDER BY CONCAT(DATE(SUBSTRING(addusrdt, 7, 8)),' ',SUBSTRING(addusrdt, 16, 8)) DESC";*/
	if($sortBy == "entryDate")
		$orderBy = "ORDER BY urutan ".$ascBy;
	if($sortBy == "barcode")
		$orderBy = "ORDER BY SUBSTR(barcode, 2, 7) ".strtoupper( $ascBy );
	if($sortBy == "company")
		$orderBy = "ORDER BY companyname ".$ascBy;
	if($sortBy == "senVenName")
		$orderBy = "ORDER BY CONCAT(sendervendor1,sendervendor2name) ".$ascBy;
	if($sortBy == "mailDate")
		$orderBy = "ORDER BY UNIX_TIMESTAMP(tglinvoice) ".$ascBy;
	
	return $orderBy;
}

if($aksiGet == "display" || $aksiGet == "retrieveOutstanding")
{
	$sortByGet = $_GET['sortBy'];
	$ascByGet = strtoupper($_GET['ascBy']);
	
	$orderBy = orderBy($sortByGet, $ascByGet);
	
	if($jenisPaymentPost == "outstanding" || $aksiGet == "retrieveOutstanding")
	{
		$tabel = "";
		$i=0;
?>
		<table id="judul" cellpadding="0" cellspacing="0" width="1703" border="0" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;position:fixed;left:0px;top:0px;">
        <tr align="center">
            <td width="35" height="50" class="">SNO</td>
            <td width="43" class="" style="font-size:8px;">CHECK TO PRINT ALL <input type="checkbox" onClick="checkAllOutstand(this.checked);" style="cursor:pointer;"></td>
            <td width="65" class="">DUE DATE</td>
            <td width="70" class="">BARCODE</td>
            <td width="70" class="" style="font-size:10px;">CREDITOR<br>NUMBER</td>
            <td width="270" class="">SENDER / VENDOR NAME</td>
            <td width="70" class="">DATE</td>
            <td width="50" class="">PAY<br>TERMS</td>
            <td width="150" class="">INV. NUMBER</td>
            <td width="130" class="">AMOUNT</td>
            <td width="70" class="">ACCT CODE</td>
            <td width="160" class="">BILLING</td>
            <td width="215" class="">VESSEL NAME</td>
            <td width="235" class="">DESCRIPTION</td>
            <td width="70" class="" style="font-size:10px;">INV RECORD</td>
        </tr>
        </table>
        
		<table width="1700" cellpadding="0" cellspacing="0" style="font:0.7em sans-serif;color:#333;margin-top:50px;">
<?php
		if($aksiGet == "retrieveOutstanding")
		{
			$printByGet = $_GET['printBy'];
			$fromBarcodeGet = $_GET['fromBarcode'];
			$toBarcodeGet = $_GET['toBarcode'];
			$fromDateGet = $_GET['fromDate'];
			$toDateGet = $_GET['toDate'];
			$fromDateDB = $CPublic->convTglDB($fromDateGet);
			$toDateDB = $CPublic->convTglDB($toDateGet);
			$companyGet = $_GET['company'];
			
			if($printByGet == "barcode")
			{
				$paramPrintBy = "SUBSTR(barcode,4)>='".$fromBarcodeGet."' AND SUBSTR(barcode,4) <= '".$toBarcodeGet."' AND";
				if(rtrim($fromBarcodeGet) == "")
					$paramPrintBy = "SUBSTR(barcode,4)<='".$toBarcodeGet."' AND";
				if(rtrim($toBarcodeGet) == "")
					$paramPrintBy = "SUBSTR(barcode,4)>='".$fromBarcodeGet."' AND";
			}
			if($printByGet == "batchno")
			{
				$fromDateDBBatch = str_replace("-","",$fromDateDB); // date yang dihilangkan garis strip nya ("-") agar bisa sama dengan batchno
				$toDateDBBatch = str_replace("-","",$toDateDB); 
				
				$paramPrintBy = "batchno >= ".$fromDateDBBatch." AND batchno <= ".$toDateDBBatch." AND";
				if(rtrim($fromDateDBBatch) == "")
					$paramPrintBy = "batchno <= ".$toDateDBBatch." AND";
				if(rtrim($toDateDBBatch) == "")
					$paramPrintBy = "batchno >= ".$fromDateDBBatch." AND";
				if(rtrim($toDateDBBatch) == "" && rtrim($toDateDBBatch) == "")
					$paramPrintBy = "";
			}
			
			$query = $CKoneksiInvReg->mysqlQuery("SELECT * FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND apprpayment=1 AND preparepayment='N' AND paid='N' AND ".$paramPrintBy." company='".$companyGet."' AND deletests=0 ".$orderBy.";", $CKoneksiInvReg->bukaKoneksi());		
		}
		else
		{
			$query = $CKoneksiInvReg->mysqlQuery("SELECT * FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND apprpayment=1 AND preparepayment='N' AND paid='N' AND deletests=0 ".$orderBy.";", $CKoneksiInvReg->bukaKoneksi());		
		}
		while($row = $CKoneksiInvReg->mysqlFetch($query))
		{
			$i++;
			$rowColor = $CPublic->rowColorCustom($i, "#FFFFFF", "#F0F1FF");
			
			$senderVendor = $row['sendervendor1'];
			if($row['tipesenven'] == "2")
				$senderVendor = $CInvReg->detilSenderVendor($row['sendervendor2'], "Acctname");
			
			$compName = $row['companyname'];
			$vesName = $row['vesname'];
			$unitName = $row['unitname'];
			if(strlen($row['unitname']) > 30)
			   $unitName =  substr($row['unitname'],0,30)."...";
	
			$tglinvoice = $CPublic->convTglNonDB($row['tglinvoice']);
			$tglexp = $CPublic->convTglNonDB($row['tglexp']);
			$currency = "(".$row['currency'].")";
			
			$amount = (($row['amount'] - $row['deduc']) + $row['addi']);
			
			$description = $row['description'];
			if(strlen($row['description']) > 30)
				$description = substr($row['description'],0,30)."...";

			$onClick = "onClickTrOutstanding('".$i."', '".$row['idmailinv']."', '".$senderVendor."', '".$rowColor."');";
			$clikTR = $onClick;
			
			$tabel.=""?>        	
				<tr valign="bottom" align="center" bgcolor="<?php echo $rowColor; ?>"  onMouseOver="this.style.backgroundColor='#D9EDFF';" onMouseOut="this.style.backgroundColor='<?php echo $rowColor; ?>';" id="tr<?php echo $i; ?>" onClick="<?php echo $clikTR; ?>" 
				style="cursor:pointer;padding-bottom:1px;">
					<!--<td width="20" height="22" class="tabelBorderBottomJust">&nbsp;</td>-->
					<td width="40" height="22" class="tabelBorderTopLeftNull"><?php echo $row['urutan']; ?>&nbsp;</td>
                    <td width="30" class="tabelBorderTopLeftNull">
                    	<input type="checkbox" id="cek<?php echo $i; ?>" value="<?php echo $row['idmailinv']; ?>" onClick="klikCheckRow();"></td>
					<td width="70" class="tabelBorderTopLeftNull"><?php echo $tglexp; ?></td>
                    <td width="70" class="tabelBorderTopLeftNull">&nbsp;<?php echo $row['barcode']; ?></td>
                    <td width="70" class="tabelBorderTopLeftNull">&nbsp;<?php echo $row['kreditacc']; ?></td>
					<td width="270" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $senderVendor; ?></td>
                    <td width="70" class="tabelBorderTopLeftNull"><?php echo $tglinvoice; ?></td>
                    <td width="50" class="tabelBorderTopLeftNull">&nbsp;<?php echo $row['dueday']; ?></td>
                    <td width="150" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $row['mailinvno']; ?></td>
                    <td width="130" class="tabelBorderTopLeftNull" align="right">&nbsp;<?php echo $currency; ?>&nbsp;<?php echo $CPublic->jikaKosongStrip(number_format((float)$amount, 2, '.', ',')); ?>&nbsp;</td>
                    <td width="70" class="tabelBorderTopLeftNull">&nbsp;<?php echo $row['debitacc']; ?></td>
					<td width="160" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $compName; ?></td>
                    <td width="215" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $vesName; ?></td>
                    <td width="235" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $description; ?></td>
                    <td width="70" class="tabelBorderTopLeftNull"><?php echo batchnoToDate($row['batchno']); ?></td>				
				</tr>

			<?php echo "";
		}
?>
		<input type="hidden" id="allIdMailInv" name="allIdMailInv" style="width:300px;">
        <input type="hidden" id="jmlCB" name="jmlCB" value="<?php echo $i; ?>">
		</table>
<?php
		echo $tabel;
	}
	
	if($jenisProcessPost == "ret")
	{
		$tabel = "";
		$i=0;
?>
		<table id="judul" cellpadding="0" cellspacing="0" width="2200" border="0" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;position:fixed;left:0px;top:0px;">
        <tr align="center">
            <td width="40" rowspan="2" height="50" class="">NO</td>
            <td width="62" rowspan="2" class="">INV RETURNED</td>
            <td width="62" rowspan="2" class="">DATE RETURNED</td>
            <td width="62" rowspan="2" class="">IGNORED<br>JE</td>
            <td width="62" rowspan="2" class="">APPROVE PAYMENT</td>
            <td width="215" rowspan="2" class="">VESSEL NAME</td>
            <td width="60" rowspan="2" class="">SOURCE</td>
            <td width="60" rowspan="2" class="">DEBIT ACCOUNT</td>
            <td width="60" rowspan="2" class="">SUB CODE</td>
            <td width="60" rowspan="2" class="">KREDIT ACCOUNT</td>
            <td width="240" rowspan="2" class="">SENDER / VENDOR</td>
            <td colspan="2" class="tabelBorderBottomJust">ADRESSEE</td>
            <td width="71" rowspan="2" class="">BARCODE</td>
            <td colspan="2" class="tabelBorderBottomJust">MAIL / INVOICE</td>
            <td width="142" rowspan="2" class="">NUMBER</td>
            <td width="125" rowspan="2" class="">AMOUNT</td>
            <td width="365" rowspan="2" class="">REMARK</td>
        </tr>
        <tr align="center">
            <td width="164" class="">COMPANY</td>
            <td width="210" class="">UNIT</td>
            <td width="70" class="">DATE</td>
            <td width="70" class="">DUE DATE</td>
        </tr>
        </table>
        
		<table width="2200" cellpadding="0" cellspacing="0" style="font:0.7em sans-serif;color:#333;margin-top:50px;">
<?php		
		$query = $CKoneksiInvReg->mysqlQuery("SELECT * FROM mailinvoice WHERE SUBSTR(batchno, 1, 6)=".$thnBlnGet." AND SUBSTR(batchno, 7, 2)=".$tglGet." AND SUBSTR(barcode, 1, 1)='A' AND ack=1 AND deletests=0 ".$orderBy.";", $CKoneksiInvReg->bukaKoneksi());
		while($row = $CKoneksiInvReg->mysqlFetch($query))
		{
			$i++;
			$rowColor = $CPublic->rowColorCustom($i, "#FFFFFF", "#F0F1FF");
			
			$invRet = $CPublic->ifSatuMakaCentang($row['invret']);
			$dateRet = $CPublic->convTglNonDB($row['dateret']);
			$ignoreJe = $CPublic->ifSatuMakaCentang($row['ignoreje']);
			$apprPayment = $CPublic->ifSatuMakaCentang($row['apprpayment']);
			$vesName = $row['vesname'];
			$source = $row['source'];
			$debitAcc = $row['debitacc'];
			$subCode = $row['subcode'];
			$kreditAcc = $row['kreditacc'];
			
			$senderVendor = $row['sendervendor1'];
			if($row['tipesenven'] == "2")
				$senderVendor = $CInvReg->detilSenderVendor($row['sendervendor2'], "Acctname");
			
			$compName = $row['companyname'];
			$unitName = $row['unitname'];
			if(strlen($row['unitname']) > 30)
			   $unitName =  substr($row['unitname'],0,30)."...";
	
			$tglinvoice = $CPublic->convTglNonDB($row['tglinvoice']);
			
			$tglexp = $CPublic->convTglNonDB($row['tglexp']);
			if($row['tglexp'] == "0000-00-00")
				$tglexp = "&nbsp;";
			
			$currency = "(".$row['currency'].")";
			if($row['currency'] == "XXX" || $row['currency'] == "")
				$currency = "";
			
			$remark = $row['remark'];
			if(strlen($row['remark']) > 50)
				$remark = substr($row['remark'],0,50)."...";
			
			$onClick = "onClickTrRet('".$i."', '".$row['idmailinv']."', '".$senderVendor."', '".$row['saveinvret']."', '".$rowColor."');";
			
			$imgGembok = "";
			if($row['saveinvret'] == "Y") //jika invoice return sudah disimpan maka baris tidak bisa diklik
			{
				$imgGembok = "<img src=\"../../picture/Lock-Lock-icon.png\" width=\"14\">";
			}
			
			$clikTR = $onClick;
			
			$tabel.=""?>
				<tr valign="bottom" align="center" bgcolor="<?php echo $rowColor; ?>"  onMouseOver="this.style.backgroundColor='#D9EDFF';" onMouseOut="this.style.backgroundColor='<?php echo $rowColor; ?>';" id="tr<?php echo $i; ?>" onClick="<?php echo $clikTR; ?>" 
				style="cursor:pointer;padding-bottom:1px;">
					<td width="19" height="22" class="tabelBorderBottomJust">&nbsp;<?php echo $imgGembok; ?></td>
					<td width="22" class="tabelBorderTopLeftNull"><?php echo $row['urutan']; ?>&nbsp;</td>
					<td width="62" class="tabelBorderTopLeftNull">&nbsp;<?php echo $invRet ; ?></td>
                    <td width="62" class="tabelBorderTopLeftNull">&nbsp;<?php echo $CPublic->jikaParamSamaDenganNilai1($dateRet, "00/00/0000", "&nbsp;"); ?></td>
                    <td width="62" class="tabelBorderTopLeftNull">&nbsp;<?php echo $ignoreJe; ?></td>
                    <td width="62" class="tabelBorderTopLeftNull">&nbsp;<?php echo $apprPayment; ?></td>
                    <td width="217" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $vesName; ?></td>
                    <td width="60" class="tabelBorderTopLeftNull">&nbsp;<?php echo $source; ?></td>
                    <td width="60" class="tabelBorderTopLeftNull">&nbsp;<?php echo $debitAcc; ?></td>
                    <td width="60" class="tabelBorderTopLeftNull">&nbsp;<?php echo $subCode; ?></td>
                    <td width="60" class="tabelBorderTopLeftNull">&nbsp;<?php echo $kreditAcc; ?></td>
					<td width="237" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $senderVendor; ?></td>
					<td width="166" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $compName; ?></td>
					<td width="210" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $unitName; ?></td>
					<td width="69" class="tabelBorderTopLeftNull" align="center">&nbsp;<?php echo $row['barcode']; ?></td>
					<td width="69" class="tabelBorderTopLeftNull"><?php echo $tglinvoice; ?></td>
					<td width="71" class="tabelBorderTopLeftNull"><?php echo $tglexp; ?></td>
					<td width="141" class="tabelBorderTopLeftNull" align="right"><?php echo $row['mailinvno']; ?>&nbsp;</td>
					<td width="125" class="tabelBorderTopLeftNull" align="right">&nbsp;<?php echo $currency; ?>&nbsp;<?php echo $CPublic->jikaKosongStrip(number_format((float)$row['amount'], 2, '.', ',')); ?>&nbsp;</td>
					<td width="364" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $remark; ?></td>
				</tr>
			<?php echo "";
		}
?>
</table>        
<?php
		echo $tabel;
	}
}
if($aksiGet == "") // HALAMAN AWAL ADALAH KOSONG
{ 
?>
	<table>
    <tr>
    	<td>&nbsp;</td>
    </tr>
    </table>
<?php
}
?>

</body>
</HTML>