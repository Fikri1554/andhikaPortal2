<!DOCTYPE HTML>
<?php 
require_once("../configInvReg.php");

$aksiGet = $_GET['aksi'];
$thnBlnGet = $_GET['thnBln'];
$tglGet = $_GET['tgl'];
$jenisProcessPost = $_GET['jenisProcess'];

?>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript" src="../js/process.js"></script>
<script language="javascript">
function onClickTrAck(trId, idMailInv, senderVendor, bgColor)
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
	parent.teksMap("INVOICE PROCESS > ACKNOWLEDGE > "+senderVendor);
	<?php
	echo "\n\r";
	if($userJenisInvReg == "user")
	{ // JIKA AKSES BUKAN BERNILAI DISABLED (ATAU SAMA DENGAN "") MAKA ...
		if($CInvReg->aksesInvReg($userIdSession, "btninvprocess_detailack") == "")
		{
			echo "parent.enabledBtn('btnAckDetail');";
		}
	}
	if($userJenisInvReg == "admin")
	{
		echo "parent.enabledBtn('btnAckDetail');"; 
	}
	echo "\n\r";
	?>
}

function onClickTrRet(trId, idMailInv, senderVendor, saveInvRet, bgColor)
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
	parent.teksMap("INVOICE PROCESS > TRANSFER TO ACCT JE > "+senderVendor);
	
	
	
	if(saveInvRet == "Y")
	{
		 parent.document.getElementById('btnRetDetail').onclick = function onclick(){	parent.openThickboxWindow('klikBtnDetailRetYes');return false;	}
		//$('#btnRetDetail', parent.document).attr("onClick", "alert('klikBtnDetailAck');return false;");
		//$('#iframeList', parent.document).attr('src', 'templates/halIncomingList.php?aksi=display&thnBln='+thnBln+'&tgl='+tgl);
	}
	if(saveInvRet == "N")
	{
		parent.document.getElementById('btnRetDetail').onclick = function onclick()
		{	// JIKA HALAMAN RETURN DETAIL / JURNAL ENTRY SEDANG DIBUKA OLEH USER LAIN MAKA TAMPILKAN PERINGATAN DAN SEBALIKNYA JIKA TIDAK MAKA LANJUT BUKA HALAMAN
			$.post( "../halPostMailInv.php", { aksi:"statusOpenPage", idMailInv:idMailInv }, function( data )
			{ // CEK APAKAH HALAMAN RETURN DETAIL / JURNAL ENTRY SEDANG DIBUKA OLEH USER LAIN ATAU TIDAK
				parent.document.getElementById("openPage").value = data;
			});
			
			setTimeout(function()
			{
				if(parent.document.getElementById("openPage").value == "Y")
				{	alert("Transfer Jurnal Entry page is still open by Another User!");return false;	}
				else
				{	parent.openThickboxWindow('klikBtnDetailRet');	}
			},250);
		}
		//{	parent.openThickboxWindow('klikBtnDetailRet');return false;	}
	}

	<?php
	echo "\n\r";
	if($userJenisInvReg == "user")
	{ // JIKA AKSES BUKAN BERNILAI DISABLED (ATAU SAMA DENGAN "") MAKA ...
		if($CInvReg->aksesInvReg($userIdSession, "btninvprocess_detailret") == "")
		{
			echo "parent.enabledBtn('btnRetDetail');";
		}
	}
	
	if($userJenisInvReg == "admin")
	{
		echo "parent.enabledBtn('btnRetDetail');"; 
	}
	echo "\n\r";
	?>
}

window.onload = 
function() 
{
	var userJenis = "<?php echo $userJenis; ?>";
	if(userJenis != "admin")
	{
		document.oncontextmenu = function(){	return false;	}; 
	}
	loadScroll('halInvProcessList');
	parent.disabledBtn('btnAckDetail');
	parent.disabledBtn('btnRetDetail');
	parent.doneWait();
	parent.panggilEnableLeftClick();
	document.getElementById('loaderImg').style.visibility = "hidden";	
}

$(window).scroll(function(){
$('#judul').css('left','-'+$(window).scrollLeft()+'px');
});
</script>

<link href="../css/invReg.css" rel="stylesheet" type="text/css" />
<link href="../css/table.css" rel="stylesheet" type="text/css" />

<body onUnload="saveScroll('halInvProcessList')">
<div class="loader" id="loaderImg" style="visibility:hidden;"></div>
<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="bgColorSeb" name="bgColorSeb">

<?php
if($aksiGet == "display")
{
	$sortByGet = $_GET['sortBy'];
	$ascByGet = strtoupper($_GET['ascBy']);
	
	$orderBy = "";
	if($sortByGet == "entryDate")
		$orderBy = "ORDER BY urutan ".$ascByGet;
	if($sortByGet == "barcode")
		$orderBy = "ORDER BY SUBSTR(barcode, 2, 7) ".strtoupper( $ascByGet );
	if($sortByGet == "company")
		$orderBy = "ORDER BY companyname ".$ascByGet;
	if($sortByGet == "senVenName")
		$orderBy = "ORDER BY CONCAT(sendervendor1,sendervendor2name) ".$ascByGet;
	if($sortByGet == "mailDate")
		$orderBy = "ORDER BY UNIX_TIMESTAMP(tglinvoice) ".$ascByGet;
	
	if($jenisProcessPost == "ack")
	{
		$tabel = "";
		$i=0;
?>
		<table id="judul" cellpadding="0" cellspacing="0" width="1130" border="0" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;position:fixed;left:0px;top:0px;">
        <tr align="center">
            <td width="40" rowspan="2" height="50" class="">SNO</td>
            <td width="40" rowspan="2" height="50" class="">ACK</td>
            <td width="75" rowspan="2" class="">BARCODE</td>
            <td width="270" rowspan="2" class="">SENDER / VENDOR NAME</td>
            <td colspan="2" class="tabelBorderBottomJust">ADRESSEE</td>
            <!--<td width="70" rowspan="2" class="">DATE</td>
            <td width="70" rowspan="2" class="">DUE DATE</td>
            <td width="150" rowspan="2" class="">INV. NUMBER</td>
            <td width="130" rowspan="2" class="">AMOUNT</td>-->
            <td width="335" rowspan="2" class="">REMARK</td>
        </tr>
        <tr align="center">
            <td width="160" class="">COMPANY</td>
            <td width="210" class="">UNIT</td>
        </tr>
        </table>
        
		<table width="1130" cellpadding="0" cellspacing="0" style="font:0.7em sans-serif;color:#333;margin-top:50px;">
<?php
		$query = $CKoneksiInvReg->mysqlQuery("SELECT * FROM mailinvoice WHERE SUBSTR(batchno, 1, 6)=".$thnBlnGet." AND SUBSTR(batchno, 7, 2)=".$tglGet." AND SUBSTR(barcode, 1, 1)='A' AND deletests=0 ".$orderBy.";", $CKoneksiInvReg->bukaKoneksi());
		while($row = $CKoneksiInvReg->mysqlFetch($query))
		{
			$i++;
			$rowColor = $CPublic->rowColorCustom($i, "#FFFFFF", "#F0F1FF");
			
			$senderVendor = $row['sendervendor1'];
			if($row['tipesenven'] == "2")
				$senderVendor = $CInvReg->detilSenderVendor($row['sendervendor2'], "Acctname");
			
			$compName = $row['companyname'];
			$unitName = $row['unitname'];
			if(strlen($row['unitname']) > 30)
			   $unitName =  substr($row['unitname'],0,30)."...";
	
			/*$tglinvoice = $CPublic->convTglNonDB($row['tglinvoice']);
			
			$tglexp = $CPublic->jikaParamSamaDenganNilai1($CPublic->convTglNonDB($row['tglexp']), "00/00/0000", "&nbsp;"); ;
			
			$currency = "(".$row['currency'].")";
			if($row['currency'] == "XXX" || $row['currency'] == "")
				$currency = "";*/
			
			$remark = $row['remark'];
			if(strlen($row['remark']) > 50)
				$remark = substr($row['remark'],0,50)."...";

			$onClick = "onClickTrAck('".$i."', '".$row['idmailinv']."', '".$senderVendor."', '".$rowColor."');";
			$clikTR = $onClick;

			$disCek = "";
			if($userJenisInvReg == "user") /* MULAI AUTHORITY */
				$disCek = $CInvReg->aksesInvReg($userIdSession, "btninvprocess_cekack");
			
			$checkedAck = "";
			if($row['ack'] == "1")
			{
				$checkedAck = "checked";
				//$disCek = "disabled"; // SEMUA CEK ACKNOWLEDGE YANG DICENTANG PASTI DISABLED
			}

			if(rtrim($row['ackby']) != "" && $row['ackby'] != $userIdLogin)
			{
				$disCek = "disabled";
			}
			if(rtrim($row['ackby']) != "" && $row['ackby'] == $userIdLogin)
			{
				$disCek = "";
			}
			
			if($userJenisInvReg == "admin") // JIKA ADMIN MAKA STATUS CEK ACKNOWLEDGE ENABLED SEMUA
			{
				$disCek = "";
			}
			
			if($row['saveinvret'] == "Y")
			{
				$disCek = "disabled"; // JIKA DATA SUDAH DILAKUKAN TRANSFER JE MAKA DISABLED
			}
/*			
			$imgGembok = "";
			if($row['saveinvret'] == "Y") //jika invoice return sudah disimpan maka baris tidak bisa diklik
			{
				$imgGembok = "<img src=\"../../picture/Lock-Lock-icon.png\" width=\"14\" title=\"ALREADY RETURNED\">";
				$disCek = "disabled";
			}*/
			
			$imgIcon = $CInvReg->imgIcon($row['idmailinv']); // ICON UNTUK MASING2 ROW UNTUK STATUS TERAKHIR
			
			$tabel.=""?>
				<tr valign="bottom" align="left" bgcolor="<?php echo $rowColor; ?>"  onMouseOver="this.style.backgroundColor='#D9EDFF';" onMouseOut="this.style.backgroundColor='<?php echo $rowColor; ?>';" id="tr<?php echo $i; ?>" onClick="<?php echo $clikTR; ?>" 
				style="cursor:pointer;padding-bottom:1px;">
					<td width="20" height="22" class="tabelBorderBottomJust" id="tdIcon<?php echo $i; ?>">&nbsp;<?php echo $imgIcon; ?></td>
					<td width="20" height="22" class="tabelBorderTopLeftNull" align="center"><?php echo $row['urutan']; ?>&nbsp;</td>
					<td width="40" class="tabelBorderTopLeftNull" align="center" id="tdAck<?php echo $i; ?>">
						<input type="checkbox" id="ack-<?php echo $row['idmailinv']; ?>" name="ack-<?php echo $row['idmailinv']; ?>" class="elementMenu" onClick="cekAck(this.id, this.checked, 'tdAck<?php echo $i; ?>', '<?php echo $i; ?>');" <?php echo $checkedAck; ?> <?php echo $disCek; ?>>
                        <input type="hidden" id="sudahAck<?php echo $i; ?>">
					</td>
                    <td width="75" class="tabelBorderTopLeftNull" align="center"><?php echo $row['barcode']; ?></td>
					<td width="270" class="tabelBorderTopLeftNull">&nbsp;<?php echo $senderVendor; ?></td>
					<td width="160" class="tabelBorderTopLeftNull">&nbsp;<?php echo $compName; ?></td>
					<td width="210" class="tabelBorderTopLeftNull">&nbsp;<?php echo $unitName; ?></td>
					<!--<td width="70" class="tabelBorderTopLeftNull" align="center"><?php echo $tglinvoice; ?></td>
					<td width="70" class="tabelBorderTopLeftNull" align="center"><?php echo $tglexp; ?></td>
					<td width="150" class="tabelBorderTopLeftNull">&nbsp;<?php echo $row['mailinvno']; ?></td>
					<td width="130" class="tabelBorderTopLeftNull" align="right">&nbsp;<?php echo $currency; ?>&nbsp;<?php echo $CPublic->jikaKosongStrip(number_format((float)$row['amount'], 2, '.', ',')); ?>&nbsp;</td>-->
					<td width="335" class="tabelBorderTopLeftNull">&nbsp;<?php echo $remark; ?></td>
				</tr>
			<?php echo "";
		}
?>
</table>
<?php
		echo $tabel;
	}
	
	if($jenisProcessPost == "ret")
	{
		$tabel = "";
		$i=0;
?>
		<table id="judul" cellpadding="0" cellspacing="0" width="2705" border="0" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;position:fixed;left:0px;top:0px;">
        <tr align="center">
            <td width="40" rowspan="2" height="50" class="">NO</td>
            <td width="60" rowspan="2" class="" style="font-size:10px;">INV RETURNED</td>
            <td width="70" rowspan="2" class="" style="font-size:10px;">DATE RETURNED</td>
            <td width="60" rowspan="2" class="" style="font-size:10px;">IGNORED<br>JE</td>
            <td width="60" rowspan="2" class="" style="font-size:10px;">APPROVE PAYMENT</td>
            <td width="215" rowspan="2" class="">VESSEL NAME</td>
            <td width="60" rowspan="2" class="">SOURCE</td>
            <td width="60" rowspan="2" class="" style="font-size:10px;">DEBIT ACCOUNT</td>
            <td width="60" rowspan="2" class="" style="font-size:10px;">SUB CODE</td>
            <td width="60" rowspan="2" class="" style="font-size:10px;">KREDIT ACCOUNT</td>
            <td width="70" rowspan="2" class="">BARCODE</td>
            <td width="270" rowspan="2" class="">SENDER / VENDOR NAME</td>
            <td width="235" rowspan="2" class="">DESCRIPTION</td>
            <td colspan="2" class="tabelBorderBottomJust">ADRESSEE</td>  
            <td width="70" rowspan="2" class="">DATE</td>
            <td width="70" rowspan="2" class="">DUE DATE</td>
            <td width="150" rowspan="2" class="">INV. NUMBER</td>
            <td width="130" rowspan="2" class="">AMOUNT</td>
            <td width="130" rowspan="2" class="">ADDITIONAL</td>
            <td width="130" rowspan="2" class="">DEDUCTION</td>
            <td width="335" rowspan="2" class="">REMARK</td>
        </tr>
        <tr align="center">
            <td width="160" class="">COMPANY</td>
            <td width="210" class="">UNIT</td>
        </tr>
        </table>
        
		<table width="2705" cellpadding="0" cellspacing="0" style="font:0.7em sans-serif;color:#333;margin-top:50px;">
<?php		
		$query = $CKoneksiInvReg->mysqlQuery("SELECT * FROM mailinvoice WHERE SUBSTR(batchno, 1, 6)=".$thnBlnGet." AND SUBSTR(batchno, 7, 2)=".$tglGet." AND SUBSTR(barcode, 1, 1)='A' AND ack=1 AND deletests=0 ".$orderBy.";", $CKoneksiInvReg->bukaKoneksi());
		while($row = $CKoneksiInvReg->mysqlFetch($query))
		{
			$i++;
			$rowColor = $CPublic->rowColorCustom($i, "#FFFFFF", "#F0F1FF");
			
			//$invRet = $CPublic->ifSatuMakaCentang($row['invret']);
			$invRet = $CPublic->ifSatuMakaIcon($row['invret'],"<img src=\"../picture/tick.png\" width=\"15\">");
			$dateRet = $CPublic->convTglNonDB($row['dateret']);
			//$ignoreJe = $CPublic->ifSatuMakaCentang($row['ignoreje']);
			$ignoreJe = $CPublic->ifSatuMakaIcon($row['ignoreje'],"<img src=\"../picture/tick.png\" width=\"15\">");
			//$apprPayment = $CPublic->ifSatuMakaCentang($row['apprpayment']);
			$apprPayment = $CPublic->ifSatuMakaIcon($row['apprpayment'],"<img src=\"../picture/tick.png\" width=\"15\">");
			$vesName = $row['vesname'];
			$source = $CPublic->jikaParamSamaDenganNilai1($row['source'], "", "&nbsp;");
			$debitAcc = $row['debitacc'];
			$subCode = $row['subcode'];
			$kreditAcc = $row['kreditacc'];
			
			$senderVendor = $row['sendervendor1'];
			if($row['tipesenven'] == "2")
				$senderVendor = $CInvReg->detilSenderVendor($row['sendervendor2'], "Acctname");
				
			$description = $row['description'];
			if(strlen($row['description']) > 30)
				$description = substr($row['description'],0,30)."...";
			
			$compName = $row['companyname'];
			$unitName = $row['unitname'];
			if(strlen($row['unitname']) > 30)
			   $unitName =  substr($row['unitname'],0,30)."...";
	
			$tglinvoice = $CPublic->convTglNonDB($row['tglinvoice']);
			$tglexp = $CPublic->convTglNonDB($row['tglexp']);
/*			if($row['tglexp'] == "0000-00-00")
				$tglexp = "&nbsp;";*/
			
			$currency = "(".$row['currency'].")";
			if($row['currency'] == "XXX" || $row['currency'] == "")
				$currency = "";
			
			$amount = $CPublic->jikaKosongStrip(number_format((float)$row['amount'], 2, '.', ','));
			$addi = $CPublic->jikaKosongStrip(number_format((float)$row['addi'], 2, '.', ','));
			$deduc = $CPublic->jikaKosongStrip(number_format((float)$row['deduc'], 2, '.', ','));
			
			$remark = $row['remark'];
			if(strlen($row['remark']) > 50)
				$remark = substr($row['remark'],0,50)."...";
			
			$onClick = "onClickTrRet('".$i."', '".$row['idmailinv']."', '".$senderVendor."', '".$row['saveinvret']."', '".$rowColor."');";
			$clikTR = $onClick;
			
			$tabel.=""?>
				<tr valign="bottom" align="center" bgcolor="<?php echo $rowColor; ?>"  onMouseOver="this.style.backgroundColor='#D9EDFF';" onMouseOut="this.style.backgroundColor='<?php echo $rowColor; ?>';" id="tr<?php echo $i; ?>" onClick="<?php echo $clikTR; ?>" 
				style="cursor:pointer;padding-bottom:1px;">
					<!--<td width="20" height="22" class="tabelBorderBottomJust">&nbsp;<?php echo $imgGembok; ?></td>-->
					<td width="40" class="tabelBorderTopLeftNull" height="22"><?php echo $row['urutan']; ?>&nbsp;</td>
					<td width="60" class="tabelBorderTopLeftNull"><?php echo $invRet ; ?></td>
                    <td width="70" class="tabelBorderTopLeftNull"><?php echo $CPublic->jikaParamSamaDenganNilai1($dateRet, "00/00/0000", "&nbsp;"); ?></td>
                    <td width="60" class="tabelBorderTopLeftNull"><?php echo $ignoreJe; ?></td>
                    <td width="60" class="tabelBorderTopLeftNull"><?php echo $apprPayment; ?></td>
                    <td width="215" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $vesName; ?></td>
                    <td width="60" class="tabelBorderTopLeftNull"><?php echo $source; ?></td>
                    <td width="60" class="tabelBorderTopLeftNull"><?php echo $debitAcc; ?></td>
                    <td width="60" class="tabelBorderTopLeftNull"><?php echo $subCode; ?></td>
                    <td width="60" class="tabelBorderTopLeftNull"><?php echo $kreditAcc; ?></td>
                    <td width="70" class="tabelBorderTopLeftNull" align="center"><?php echo $row['barcode']; ?></td>
					<td width="270" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $senderVendor; ?></td>
                    <td width="235" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $description; ?></td>
					<td width="160" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $compName; ?></td>
					<td width="210" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $unitName; ?></td>
					<td width="70" class="tabelBorderTopLeftNull"><?php echo $tglinvoice; ?></td>
					<td width="70" class="tabelBorderTopLeftNull"><?php echo $tglexp; ?></td>
					<td width="150" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $row['mailinvno']; ?></td>
					<td width="130" class="tabelBorderTopLeftNull" align="right"><?php echo $currency; ?>&nbsp;<?php echo $amount; ?>&nbsp;</td>
                    <td width="130" class="tabelBorderTopLeftNull" align="right">&nbsp;<?php echo $addi; ?>&nbsp;</td>
                    <td width="130" class="tabelBorderTopLeftNull" align="right">&nbsp;<?php echo $deduc; ?>&nbsp;</td>
					<td width="335" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $remark; ?></td>
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