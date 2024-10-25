<!DOCTYPE HTML>
<?php 
require_once("../configVoucher.php");

$aksiGet = $_GET['aksi'];
$paramCariGet = $_GET['paramCari'];
$yearProcessGet = $_GET['yearProcess'];
?>
<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>
<script type="text/javascript" src="../js/voucher.js"></script>
<script language="javascript">
function onClickTr(trId, idVoucher, bgColor)
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
		document.getElementById(idTrSeb).style.fontWeight=''; // FONT TIDAK BOLD UNTUK TD YANG DIPILIH
	}
	
	document.getElementById('tr'+idVoucher).onmouseout = '';
	document.getElementById('tr'+idVoucher).onmouseover ='';
	document.getElementById('tr'+idVoucher).style.fontWeight='bold';
	document.getElementById('tr'+idVoucher).style.backgroundColor='#B0DAFF';
	document.getElementById('tr'+idVoucher).style.cursor = 'default';
	document.getElementById('tr'+idVoucher).style.fontSize='11px';
	document.getElementById('idTrSeb').value = 'tr'+idVoucher;
	//document.getElementById('tr'+trId).style.height = "";

	document.getElementById('bgColorSeb').value = bgColor;
	parent.document.getElementById('idVoucher').value = idVoucher;

	<?php
	/*echo "\n\r";
	if($userJenisInvReg == "user")
	{ // JIKA AKSES BUKAN BERNILAI DISABLED (ATAU SAMA DENGAN "") MAKA ...
		if($CInvReg->aksesInvReg($userIdSession, "btnpayment_retbatch") == "")
		{
			echo "parent.enabledBtn('btnRetBatch');";
		}
	}
	if($userJenisInvReg == "admin")
	{
		echo "parent.enabledBtn('btnRetBatch');"; 
	}
	echo "\n\r";*/
	?>
}
window.onload = 
function() 
{
	parent.doneWait();
	parent.panggilEnableLeftClick();
	
	loadScroll('halVoucherList');
}

$(window).scroll(function(){
$('#judul').css('left','-'+$(window).scrollLeft());
});
</script>
<link href="../css/table.css" rel="stylesheet" type="text/css" />

<body onUnload="saveScroll('halVoucherList')">
<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="bgColorSeb" name="bgColorSeb">
<?php
//echo "<BR><BR>".$aksiGet;
if($aksiGet == "displayVoucher" || $aksiGet == "simpanBaruVoucher" || $aksiGet == "simpanUbahVoucher" || $aksiGet == "closePopUp" || $aksiGet == "deleteVoucher" || $aksiGet == "btnBackVoucher" || $aksiGet == "transToAcct" || $aksiGet == "ketikElementCariVo")
{
	$idVoucherGet = $_GET['idVoucher'];
	//echo "<BR><BR>".$idVoucherGet;
	$paidToGet = strtoupper( mysql_escape_string( $_GET['paidTo'] ) );
	$voucherTypeGet = strtoupper( $_GET['voucherType'] );
	$bookSts = $CPublic->jikaParamSmDgNilai($voucherTypeGet, "R", "db", "cr");
	$companyGet = $_GET['company'];
	$companyName = $CVoucher->detilComp($companyGet , "compname");
	$payTypeGet = $_GET['payType'];
	$bankCodeGet = $_GET['bankCode'];
	$bankSource = $CVoucher->detilBankSource($bankCodeGet, "source");
	$voucherGet = $_GET['voucher'];
	$referenceGet = $_GET['reference'];
	$chequeNoGet = $_GET['chequeNumber'];
	$invNoGet = $_GET['invNo'];
	$jobNoGet = $_GET['jobNo'];
	$datePaidGet = $CPublic->convTglDB( $_GET['datePaid'] );
	$currencyGet = $_GET['currency'];
	$amountGet = str_replace(",","",$_GET['amount']);
	
	if($aksiGet == "simpanBaruVoucher")
	{
		//echo $noInvoiceGet." / ".$jobNoGet;
		$CKoneksiVoucher->mysqlQuery("INSERT INTO tblvoucher 
						 (batchno, kepada, voctype, booksts, company, companyname, paytype, bankcode, banksource, voucher, reference, chequeno, invno, jobno, datepaid, currency, amount, addusrdt) 
						 VALUES 
						( (SELECT CASE WHEN a.batchno IS NOT NULL THEN (MAX(a.batchno+1)) ELSE '1' END AS batchnoo FROM tblvoucher a), 
						'".$paidToGet."', 
						'".$voucherTypeGet."', 
						'".$bookSts."', 
						'".$companyGet."', 
						'".$companyName."', 
						'".$payTypeGet."', 
						'".$bankCodeGet."', 
						'".$bankSource."', 
						'".$voucherGet."', 
						'".$referenceGet."', 
						'".$chequeNoGet."', 
						'".$invNoGet."', 
						'".$jobNoGet."', 
						'".$datePaidGet."', 
						'".$currencyGet."', 
						'".$amountGet."', 
						'".$userWhoAct."')");
						
		$lastInsertId = mysql_insert_id();				
		$CHistory->updateLogVoucher($userIdLogin, "Simpan TAMBAH BARU Voucher (idvoucher=<b>".$lastInsertId."</b>, kepada=<b>".$paidToGet."</b>, addusrdt=<b>".$userWhoAct."</b>)");
	}
	
	if($aksiGet == "simpanUbahVoucher")
	{
		$CKoneksiVoucher->mysqlQuery("UPDATE tblvoucher SET kepada='".$paidToGet."', voctype='".$voucherTypeGet."', booksts='".$bookSts."', company='".$companyGet."', companyname='".$companyName."', paytype='".$payTypeGet."', bankcode='".$bankCodeGet."', banksource='".$bankSource."', voucher='".$voucherGet."', reference='".$referenceGet."', chequeno='".$chequeNoGet."', invno='".$invNoGet."', jobno='".$jobNoGet."', datepaid='".$datePaidGet."', currency='".$currencyGet."', amount='".$amountGet."', updusrdt='".$userWhoAct."' WHERE idvoucher='".$idVoucherGet."' AND deletests=0"); 
		
		$CHistory->updateLogVoucher($userIdLogin, "Simpan UBAH Voucher (idvoucher=<b>".$idVoucherGet."</b>, kepada=<b>".$paidToGet."</b>, voctype=<b>".$voucherTypeGet."</b>, booksts=<b>".$bookSts."</b> company=<b>".$companyGet."</b>, companyname=<b>".$companyName."</b>, paytype=<b>".$payTypeGet."</b>, bankcode=<b>".$bankCodeGet."</b>, banksource=<b>".$bankSource."</b>, voucher=<b>".$voucherGet."</b>, reference=<b>".$referenceGet."</b>, chequeno=<b>".$chequeNoGet."</b>, invno=<b>".$invNoGet."</b>, jobno=<b>".$jobNoGet."</b>, datepaid=<b>".$datePaidGet."</b>, currency=<b>".$currencyGet."</b>, amount=<b>".$amountGet."</b>, addusrdt=<b>".$userWhoAct."</b>)");
	}
	
	if($aksiGet == "deleteVoucher")
	{
		$CKoneksiVoucher->mysqlQuery("UPDATE tblvoucher SET deletests=1, delusrdt='".$userWhoAct."' WHERE idvoucher='".$idVoucherGet."' AND deletests=0"); 
		$CHistory->updateLogVoucher($userIdLogin, "Simpan HAPUS Voucher (idvoucher=<b>".$idVoucherGet."</b>, delusrdt=<b>".$userWhoAct."</b>)");
	}
	
	if($aksiGet == "transToAcct")
	{
		$CKoneksiVoucher->mysqlQuery("UPDATE tblvoucher SET trfacct='Y', trfacctdate='".$CPublic->tglServerWithStrip()."', trfacctby='".$userIdLogin."', updusrdt='".$userWhoAct."' WHERE idvoucher='".$idVoucherGet."' AND deletests=0;", $CKoneksiVoucher->bukaKoneksi());
		$CHistory->updateLogVoucher($userIdLogin, "VOUCHER - Transfer to Accounting (idvoucher=<b>".$idVoucherGet."</b>, trfacct=<b>Y</b>");
		
		$queryVoc = $CKoneksiVoucher->mysqlQuery("SELECT * FROM tblvoucher WHERE idvoucher = '".$idVoucherGet."' AND deletests=0;", $CKoneksiVoucher->bukaKoneksi());
		$rowVoc = $CKoneksiVoucher->mysqlFetch($queryVoc);
		
		$batchno = $CPublic->zerofill($rowVoc['batchno'], 6);
		$company = 	$rowVoc['company'];
		$source = 	$rowVoc['banksource'];
		$datePaid = $rowVoc['datepaid'];
		$thnDatePaid = substr($datePaid, 0, 4);
		$bookDate = $rowVoc['datepaid'];
		$voucher = $rowVoc['voucher'];
		//$refNumber = $rowVoc['reference'];
		$refNumber = $source.date("m", strtotime($bookDate))."-".$CPublic->zerofill($rowVoc['reference'], 5); //sourcebulan(diambil dari jurnal date)-reference
		$invNo = $rowVoc['invno'];
		$jobNo = $rowVoc['jobno'];
		$bookDesc = $rowVoc['kepada'];
		$vessel = "";
		$bankCode = $rowVoc['bankcode'];
		$subAcc = "";
		$currency = $rowVoc['currency'];
		$bookSts = strtoupper($rowVoc['booksts']);
		$amount = $rowVoc['amount'];
		$codeSts = "*";
		$entryDate = $CPublic->tglServerWithStrip()." ".$CPublic->jamServer();
		$payType = $rowVoc['paytype'];
		$progDebug = "Voucher:".$batchno;
	// ##############################################################################################################################
	//--- Start -- Hapus Tes jika ingin upload dari AndhiPortalTes ke AndhikaPortal -------------------------------------------------
		$dbAccounting = $company.$thnDatePaid."tes";
	//--- End -- Hapus Tes jika ingin upload dari AndhiPortalTes ke AndhikaPortal ---------------------------------------------------
	// ##############################################################################################################################
	
	/*echo $dbAccounting."<br>";echo $company."<br>";echo $source."<br>";echo $bookDate."<br>";echo $voucher."<br>";echo $refNumber."<br>";echo $invNo."<br>";echo $bookDesc."<br>";echo $bankCode."<br>";echo $currency."<br>";echo $bookSts."<br>";echo $amount."<br>";echo $codeSts."<br>";echo $entryDate."<br>";echo $payType."<br>";echo $progDebug."<br>";echo $jobNo."<br>";*/
		
		$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
				(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Remark, Entryuser, Progdebug, Jobnumber) VALUES 
				('".$company."', '".$source."', '".$bookDate."', '".$voucher."', '".$refNumber."', '".$invNo."', '".$batchno."', '".$bookDesc."', '', '".$bankCode."', '','', '".$currency."', '".$bookSts."', '".number_format((float)$amount, 2, '.', '')."', '".$currency."', '".$codeSts."', '".$entryDate."', '".$payType."', '".$userName."', '".$progDebug."', '".$jobNo."')");
		$CHistory->updateLogVoucher($userIdLogin, "VOUCHER - Transfer to Accounting - Simpan ke SQLServer (Company=<b>".$company."</b>, Source=<b>".$source."</b>, Bookdate=<b>".$bookDate."</b>, Voucher=<b>".$voucher."</b>, Refnumber=<b>".$refNumber."</b>, Invoiceno=<b>".$invNo."</b>, Pono=<b>".$batchno."</b>, Bookdesc=<b>".$bookDesc."</b>, Vessel=<b>-</b>, Account=<b>".$bankCode."</b>, Subacct=<b>-</b>, Subcode=<b>-</b>, Currcy=<b>".$currency."</b>, Booksts=<b>".$bookSts."</b>, Amount=<b>".number_format((float)$amount, 2, '.', '')."</b>, Diffcur=<b>".$currency."</b>, Codests=<b>".$codeSts."</b>, Entrydate=<b>".$entryDate."</b>, Remark=<b>".$payType."</b>, Entryuser=<b>".$userName."</b>, Progdebug=<b>".$progDebug."</b>, Jobnumber=<b>".$jobNo."</b>)");
		
		/**/
		$queryDesc = $CKoneksiVoucher->mysqlQuery("SELECT * FROM tbldesc WHERE idvoucher = '".$idVoucherGet."' AND deletests=0;", $CKoneksiVoucher->bukaKoneksi());
		while($rowDesc = $CKoneksiVoucher->mysqlFetch($queryDesc))
		{
			$bookDescDesc = $rowDesc['keterangan'];
			$vesselDesc = $rowDesc['vescode'];
			$accountDesc = $rowDesc['perkiraan'];
			$subAccDesc = $rowDesc['subacc'];
			$subCodeDesc = $rowDesc['unit'];
			$bookStsDesc = strtoupper($rowDesc['booksts']);
			$amountDesc = $rowDesc['amount'];
	/*		echo $bookDescDesc."<br>";echo $vesselDesc."<br>";echo $accountDesc."<br>";echo $subAccDesc."<br>";echo $subCodeDesc."<br>";echo $bookStsDesc."<br>";echo $amountDesc."<br>";echo "--------------------<br>";*/
			
			$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
				(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Remark, Entryuser, Progdebug, Jobnumber) VALUES 
				('".$company."', '".$source."', '".$bookDate."', '".$voucher."', '".$refNumber."', '".$invNo."', '".$batchno."', '".$bookDescDesc."', '".$vesselDesc."', '".$accountDesc."', '".$subAccDesc."','".$subCodeDesc."', '".$currency."', '".$bookStsDesc."', '".number_format((float)$amountDesc, 2, '.', '')."', '".$currency."', '".$codeSts."', '".$entryDate."', '".$payType."', '".$userName."', '".$progDebug."', '".$jobNo."')");	
			$CHistory->updateLogVoucher($userIdLogin, "VOUCHER - Transfer to Accounting - Simpan ke SQLServer (Company=<b>".$company."</b>, Source=<b>".$source."</b>, Bookdate=<b>".$bookDate."</b>, Voucher=<b>".$voucher."</b>, Refnumber=<b>".$refNumber."</b>, Invoiceno=<b>".$invNo."</b>, Pono=<b>".$batchno."</b>, Bookdesc=<b>".$bookDescDesc."</b>, Vessel=<b>".$vesselDesc."</b>, Account=<b>".$accountDesc."</b>, Subacct=<b>".$subAccDesc."</b>, Subcode=<b>".$subCodeDesc."</b>, Currcy=<b>".$currency."</b>, Booksts=<b>".$bookStsDesc."</b>, Amount=<b>".number_format((float)$amountDesc, 2, '.', '')."</b>, Diffcur=<b>".$currency."</b>, Codests=<b>".$codeSts."</b>, Entrydate=<b>".$entryDate."</b>, Remark=<b>".$payType."</b>, Entryuser=<b>".$userName."</b>, Progdebug=<b>".$progDebug."</b>, Jobnumber=<b>".$jobNo."</b>)");
		}
	}
	
//####### Paging #####################################################################################################

	$pageBatchno = "";
	$pageBatchno = $_GET['pageBatchno']; // PARAMETER YANG DIDAPAT DARI MENU PAGE DI PARENT
	$totalBatchno = totalBatchno($CKoneksiVoucher, $aksiGet, $yearProcessGet, $paramCariGet); // TOTAL KESELURUHAN DATA Batchno
	$limitBatchno = $limitVoucher; // LIMIT PERPAGE YANG DITENTUKAN
	
	$maxPage = ceil($totalBatchno/$limitBatchno); // MAX PAGE YANG DIDAPAT DARI PEMBAGIAN PEMBULATAN 
	//echo "<br/><br/>".$pageBatchno." | ".$totalBatchno." | ".$maxPage." | ".$aksiGet;
	if(isset($pageBatchno)){$pageNum = $pageBatchno;}	
	
	if($pageBatchno == "" || $pageBatchno == 0 || $totalBatchno  == 0)
	{
		$offset = 0;
		$maxPage = 1;
		$pageBatchno = 1;
	}
	else
	{
		$offset = ($pageBatchno - 1) * $limitBatchno;
	}
//####### END - Paging #####################################################################################################	

	$width = "447";
	$width2 = "230";
	$totalRowVoc = totalRowVoc($CKoneksiVoucher, $aksiGet, $yearProcessGet, $paramCariGet, $offset, $limitBatchno);
	//echo "<br/><br/>".$totalRowVoc."a".$pageBatchno." | ".$totalBatchno." | ".$maxPage;
	if($totalRowVoc >= 11)
	{
		$width = "430"; // selisih 17
		$width2 = "200";
	}
?>
    <table id="judul" cellpadding="0" cellspacing="0" width="<?php echo $width; ?>" border="0" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:10px;position:fixed;left:0px;top:0px;z-index:10;">
    <tr align="center">
        <td width="32" class="tabelBorderRightJust" height="30">TRF.<BR>ACCT.</td>
        <td width="55" class="tabelBorderRightJust">BATCHNO</td>
        <td width="215" class="tabelBorderRightJust">PAID TO/FROM</td>
        <td class="tabelBorderRightJust">COMPANY</td>
    </tr>
    </table>
    
    <?php
	$i = 0;
	$tabel = "";
?>
	<table cellpadding="0" cellspacing="0" width="<?php echo $width; ?>" style="font:0.7em sans-serif;color:#333;margin-top:30px;">
<?php	
	if($aksiGet == "ketikElementCariVo")
	{
		$paramCariGet = $_GET['paramCari'];
		
		$query = $CKoneksiVoucher->mysqlQuery("SELECT idvoucher, batchno, kepada, voctype, company, companyname, paytype, bankcode, voucher, reference, chequeno, invno, jobno, datepaid, currency, amount, trfacct FROM tblvoucher WHERE (batchno LIKE '%".$paramCariGet."%' OR kepada LIKE '%".$paramCariGet."%' OR companyname LIKE '%".$paramCariGet."%') AND YEAR(datepaid)='".$yearProcessGet."' AND deletests=0 GROUP BY batchno ORDER BY 0+batchno DESC LIMIT ".$offset.",".$limitBatchno.";", $CKoneksiVoucher->bukaKoneksi());
	}
	else
	{
		$query = $CKoneksiVoucher->mysqlQuery("SELECT idvoucher, batchno, kepada, voctype, company, companyname, paytype, bankcode, voucher, reference, chequeno, invno, jobno, datepaid, currency, amount, trfacct FROM tblvoucher WHERE YEAR(datepaid)='".$yearProcessGet."' AND deletests=0 GROUP BY batchno ORDER BY 0+batchno DESC LIMIT ".$offset.",".$limitBatchno.";", $CKoneksiVoucher->bukaKoneksi());
	}
	while($row = $CKoneksiVoucher->mysqlFetch($query))
	{
		$i++;

		$rowColor = $CPublic->rowColorCustom($i, "#FFFFFF", "#F0F1FF");
		$iconTrfAcct = "<img src=\"../picture/hourglass-select-remain.png\" width=\"14\" title=\"WAITING FOR TRANSFER\">";
		if($row['trfacct'] == "Y")
		{
			$iconTrfAcct = "<img src=\"../picture/tick.png\" width=\"14\" title=\"ALREADY TRANSFER\">";
		}
		
		$datePaid = $CPublic->convTglNonDB( $row['datepaid'] );
		if($datePaid == "00/00/0000")
		{	$datePaid = ""; 	}
		
		$jobNo = $row['jobno'];
		if($row['jobno'] == "0")
		{
			$jobNo = "";
		}

		$klikRow = "parent.klikRowVoucher('". $CPublic->zerofill($row['batchno'], 6)."', '".$row['trfacct']."', '".$row['voctype']."', '".$row['kepada']."', '".$row['company']."', '".$row['paytype']."', '".$row['bankcode']."', '".$row['voucher']."', '".$row['reference']."', '".$row['chequeno']."', '".$row['invno']."', '".$jobNo."', '".$datePaid."', '".$row['currency']."', '".$row['amount']."');";
		$clikTR = "onClickTr('".$i."', '".$row['idvoucher']."', '".$rowColor."');".$klikRow;
?>
    
    <tr align="center" valign="bottom" bgcolor="<?php echo $rowColor; ?>" onMouseOver="this.style.backgroundColor='#D9EDFF';" onmouseout="this.style.backgroundColor='<?php echo $rowColor; ?>';" id="tr<?php echo $row['idvoucher']; ?>" onclick="<?php echo $clikTR; ?>" 
			style="cursor:pointer;padding-bottom:1px;"> 
            <td width="32" height="22" class="tabelBorderTopLeftNull"><?php echo $iconTrfAcct; ?></td>
			<td width="55" class="tabelBorderTopLeftNull" style="font-size:11px;color:#096;font-weight:bold;"><?php echo $CPublic->zerofill($row['batchno'], 6); ?></td>
            <td width="215" class="tabelBorderTopLeftNull" align="left" style="padding-left:2px;"><?php echo $row['kepada']; ?>&nbsp;</td>
			<td class="tabelBorderBottomJust" align="left" style="padding-left:2px;"><?php echo $row['companyname']; ?>&nbsp;</td>
		</tr>
<?php
	}

	echo $tabel;
?>
    </table>
<?php
}
?>
</body>
</HTML>

<?php
function totalBatchno($CKoneksiVoucher, $aksiGet, $yearProcess, $paramCariGet)
{
	if($aksiGet == "ketikElementCariVo")// MENCARI HANYA Batchno DI HALAMAN PAYMENT BY BATCH
	{
		$query = $CKoneksiVoucher->mysqlQuery("SELECT idvoucher FROM tblvoucher WHERE (batchno LIKE '%".$paramCariGet."%' OR companyname LIKE '%".$paramCariGet."%') AND YEAR(datepaid)='".$yearProcess."' AND deletests = 0;", $CKoneksiVoucher->bukaKoneksi());
	}
	else
	{
		$query = $CKoneksiVoucher->mysqlQuery("SELECT idvoucher FROM tblvoucher WHERE YEAR(datepaid)='".$yearProcess."' AND deletests=0 GROUP BY batchno ORDER BY 0+batchno DESC;", $CKoneksiVoucher->bukaKoneksi());
	}
	$jmlRow = $CKoneksiVoucher->mysqlNRows($query);
	
	return $jmlRow;
}

function totalRowVoc($CKoneksiVoucher, $aksiGet, $yearProcess, $paramCariGet, $offset, $limit)
{
	if($aksiGet == "ketikElementCariVo")// MENCARI HANYA Batchno DI HALAMAN PAYMENT BY BATCH
	{
		$query = $CKoneksiVoucher->mysqlQuery("SELECT idvoucher FROM tblvoucher WHERE (batchno LIKE '%".$paramCariGet."%' OR companyname LIKE '%".$paramCariGet."%') AND YEAR(datepaid)='".$yearProcess."' AND deletests = 0 LIMIT ".$offset.", ".$limit.";", $CKoneksiVoucher->bukaKoneksi());
	}
	else
	{
		$query = $CKoneksiVoucher->mysqlQuery("SELECT idvoucher FROM tblvoucher WHERE YEAR(datepaid)='".$yearProcess."' AND deletests = 0 LIMIT ".$offset.", ".$limit.";", $CKoneksiVoucher->bukaKoneksi());
	}
	$jmlRow = $CKoneksiVoucher->mysqlNRows($query);
	
	return $jmlRow;
}

?>

<script type="text/javascript">
//alert('<?php echo $aksiGet; ?>');
<?php
if($aksiGet == "simpanBaruVoucher" || $aksiGet == "simpanUbahVoucher" || $aksiGet == "transToAcct")
{	
	if($aksiGet == "simpanBaruVoucher")
	{
		$idVoucher = $lastInsertId;
		$aksi = "suksesSimpanBaruVoucher";
	}
	if($aksiGet == "simpanUbahVoucher")
	{
		$idVoucher = $idVoucherGet;
		$aksi = "suksesSimpanUbahVoucher";
	}
	if($aksiGet == "transToAcct")
	{
		$idVoucher = $idVoucherGet;
		$aksi = "suksesTransferAcct";
	}
	?>
	setTimeout(function()
	{ 
		$('#hrefThickbox',parent.document).prop('href','templates/halPopup.php?aksi=<?php echo $aksi; ?>&idVoucher=<?php echo $idVoucher; ?>&barisTransno=<?php echo $barisTransnoGet; ?>&pageBatchno=<?php echo $pageBatchno; ?>&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=230&width=400&modal=true');
		window.parent.$('#hrefThickbox').click();
	}, 250);
<?php
}
if($aksiGet == "deleteVoucher")
{?>
	//parent.klikBtnRefresh();
	//$('#btnRefresh', parent.document).click();
	parent.klikBtnRefresh();
<?php
}
if($aksiGet == "btnBackVoucher")
{ ?>
	document.getElementById('tr<?php echo $idVoucherGet; ?>').click();
<?php
}
if($aksiGet == "closePopUp")
{ ?>
	//alert('tr<?php echo $idVoucherGet; ?>');
	document.getElementById('tr<?php echo $idVoucherGet; ?>').click();
	//parent.document.getElementById('btnRetrieve').click();
	setTimeout(function()
	{
		$('#btnRetrieve', parent.document).click(); // KLIK BTN RETRIEVE BATCH
	}, 250);	
<?php
}
if($aksiGet == "displayVoucher" || $aksiGet == "ketikElementCariVo" || $aksiGet == "deleteVoucher" ||$aksiGet == "simpanBaruVoucher")
{
?>
	var numbers = '<?php echo $maxPage; ?>';
	//alert(numbers);
	var pageBatchno = '<?php echo $pageBatchno; ?>';
	var option = '';
	for (var i=1;i<=numbers;i++)
	{
		if(i == pageBatchno)
		{
			option += '<option value="'+ i + '" selected>' + i + '</option>';
		}
		else
		{
			option += '<option value="'+ i + '">' + i + '</option>';
		}
	}
	$('#menuPageBatchno', parent.document).html(option);    
	$('#maxPageBatchno', parent.document).html(numbers);    
<?php
}
?>
</script>