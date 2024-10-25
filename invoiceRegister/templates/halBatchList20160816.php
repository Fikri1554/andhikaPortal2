<!DOCTYPE HTML>
<?php 
require_once("../configInvReg.php");

$aksiGet = $_GET['aksi'];
//$jenisPaymentPost = $_GET['jenisPayment'];
//$prepareByPost = $_GET['prepareBy'];
?>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript" src="../js/payment.js"></script>
<script language="javascript">
function onClickTrBatch(trId, idMailInv, transNo, bgColor)
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
	
	document.getElementById('tr'+trId).onmouseout = '';
	document.getElementById('tr'+trId).onmouseover ='';
	document.getElementById('tr'+trId).style.fontWeight='bold';
	document.getElementById('tr'+trId).style.backgroundColor='#B0DAFF';
	document.getElementById('tr'+trId).style.cursor = 'default';
	document.getElementById('tr'+trId).style.fontSize='11px';
	document.getElementById('idTrSeb').value = 'tr'+trId;
	//document.getElementById('tr'+trId).style.height = "";

	document.getElementById('bgColorSeb').value = bgColor;
	parent.document.getElementById('idMailInv').value = idMailInv;
	parent.teksMap("PAYMENT > PAYMENT BY BATCH > "+transNo);
	<?php
	echo "\n\r";
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
	echo "\n\r";
	?>
	
	parent.disabledBtn('btnPayTransAcct');
	parent.disabledBtn('btnPayPrintVoucher');
	parent.disabledBtn('btnCancelled');
	$('#idErrorMsg', parent.document).css('visibility','hidden'); 
	$('#divStatusPaid', parent.document).css('visibility','hidden');
	$('#divStatusCancel', parent.document).css('visibility','hidden');
	
	$('#tdTransNo', parent.document).html( '' );
	$('#payMethod', parent.document).attr("disabled","disabled");
	$('#payMethod', parent.document).css("background-color","E9E9E9");
	$('#bankCode', parent.document).attr("disabled","disabled");
	$('#bankCode', parent.document).css("background-color","E9E9E9");
	$('#voucher', parent.document).attr("disabled","disabled");
	$('#voucher', parent.document).css("background-color","E9E9E9");
	$('#reference', parent.document).attr("disabled","disabled");
	$('#reference', parent.document).css("background-color","E9E9E9");
	$('#chequeNumber', parent.document).attr("disabled","disabled");
	$('#chequeNumber', parent.document).css("background-color","E9E9E9");
	$('#datePaid', parent.document).attr("disabled","disabled");
	$('#datePaid', parent.document).css("background-color","E9E9E9");
	$('#amtConv', parent.document).attr("disabled","disabled");
	$('#amtConv', parent.document).css("background-color","E9E9E9");
	$('#currency', parent.document).attr("disabled","disabled");
	$('#currency', parent.document).css("background-color","E9E9E9");
	$('#adjAcc', parent.document).attr("disabled","disabled");
	$('#adjAcc', parent.document).css("background-color","E9E9E9");
	$('#adjAmt', parent.document).attr("disabled","disabled");
	$('#adjAmt', parent.document).css("background-color","E9E9E9");
	
	$('#barisBgColor', parent.document).val( bgColor );// BACKGROUND COLOR DARI TR YANG DIPILIH DI IFRAME TRANSNO DATA
	$('#barisTransno', parent.document).val( trId );// ID DARI TR YANG DIPILIH DI IFRAME TRANSNO DATA
	$('#payMethod', parent.document).val( 'cheque' );
	$('#bankCode', parent.document).val( $('#bankCodeFirst').val() );
	$('#voucher', parent.document).val( '' );
	$('#reference', parent.document).val( '' );
	$('#chequeNumber', parent.document).val( '' );
	$('#amtConv', parent.document).val( '' );
	$('#currency', parent.document).val( 'IDR' );
	$('#adjAcc', parent.document).val( '' );
	$('#adjAmt', parent.document).val( '' );
	
	parent.loadIframe('iframeList2', '');
	parent.loadIframe('iframeList3', '');
}

window.onload = 
function() 
{
	var userJenis = "<?php echo $userJenis; ?>";
	if(userJenis != "admin")
	{
		document.oncontextmenu = function(){	return false;	}; 
	}
	loadScroll('halBatchList');
	parent.doneWait();
	parent.panggilEnableLeftClick();
}

$(window).scroll(function(){
$('#judul').css('left','-'+$(window).scrollLeft());
});
$(window).scroll(function(){
$('#judul1').css('left','-'+$(window).scrollLeft());
});
$(window).scroll(function(){
$('#judul2').css('left','-'+$(window).scrollLeft());
});
</script>

<link href="../css/invReg.css" rel="stylesheet" type="text/css" />
<link href="../css/table.css" rel="stylesheet" type="text/css" />
<link href="../css/button.css" rel="stylesheet" type="text/css" />

<body onUnload="saveScroll('halBatchList')">
<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="bgColorSeb" name="bgColorSeb">

<?php
if($aksiGet == "displayTransNo" || $aksiGet == "transToAcct" || $aksiGet == "ketikElementCariBatch" || $aksiGet == "cancelPayment")
{
	$barisTransnoGet = $_GET['barisTransno'];
	$idMailInvGet = $_GET['idMailInv'];
	$transNoGet = $_GET['transNo'];
	$datePaidGet = $CPublic->convTglDB( $_GET['datePaid'] );
	$payTypeGet = $_GET['payType'];
	$bankCodeGet = $_GET['bankCode'];
	$voucherGet = $_GET['voucher'];
	$referenceGet = $_GET['reference'];
	$chequeNoGet = $_GET['chequeNumber'];
	$amtConvGet = str_replace(",","",$_GET['amtConv']);
	$adjAccGet = $_GET['adjAcc'];
	$adjAmtGet = str_replace(",","",$_GET['adjAmt']);
	//echo $idMailInvGet." / ".$transNoGet." / ".$datePaidGet." / ".$payTypeGet." / ".$bankCodeGet." / ".$voucherGet." / ".$chequeNoGet;
	
	//echo "<br><br>".$amtConvGet." / ".$adjAccGet." / ".$adjAmtGet."<br>";
	$invNo = $CInvReg->detilMailInv($idMailInvGet, "mailinvno");
	$barcode = $CInvReg->detilMailInv($idMailInvGet, "barcode");
	$thnDatePaid = substr($datePaidGet, 0, 4);
	$blnDatePaid = substr($datePaidGet, 5, 2);
	$company = $CInvReg->detilMailInv($idMailInvGet, "company");
	
	// ##############################################################################################################################
//--- Start -- Hapus Tes jika ingin upload dari AndhiPortalTes ke AndhikaPortal --------------------------------------------------
	$dbAccounting = $company.$thnDatePaid;
//--- End -- Hapus Tes jika ingin upload dari AndhiPortalTes ke AndhikaPortal ----------------------------------------------------
// ##############################################################################################################################

	$source = $CInvReg->detilBankSource($bankCodeGet, "source");
	$bookDate = $datePaidGet; //str_replace("/","-",$_GET['datePaid']);
	$refNumber = $source.$blnDatePaid."-".$CPublic->zerofill($referenceGet, 5); //sourcebulan-reference
	$entryDate = $CPublic->tglServerWithStrip()." ".$CPublic->jamServer();
	$kreditAcc = $CInvReg->detilMailInv($idMailInvGet, "kreditacc");
	$kreditAccNm = $CInvReg->detilMailInv($idMailInvGet, "kreditaccname");
	$currency = $CInvReg->detilMailInv($idMailInvGet, "currency");
	$transNo = $CInvReg->detilMailInv($idMailInvGet, "transno");
	
	//echo "<br><br>".$source."aaa<br>";
	if($aksiGet == "transToAcct")
	{
		$query = $CKoneksiInvReg->mysqlQuery("SELECT * FROM mailinvoice WHERE transno=".$transNoGet." AND deletests=0 ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
		while($row = $CKoneksiInvReg->mysqlFetch($query))
		{		
//currconv='".$_GET['currency']."' AMBIL DARI MENU CURRENCY SAMPING AMOUNT TO BE PAID
			$CKoneksiInvReg->mysqlQuery("UPDATE mailinvoice SET paid='Y', datepaid='".$datePaidGet."', amtconv='".$amtConvGet."', currconv='".$_GET['currency']."', adjacc='".$adjAccGet."', adjamt='".$adjAmtGet."', paidby='".$userIdLogin."', paytype='".$payTypeGet."', bankcode='".$bankCodeGet."', voucher='".$voucherGet."', reference='".$referenceGet."', chequeno='".$chequeNoGet."', updusrdt='".$userWhoAct."' WHERE idmailinv='".$row['idmailinv']."' AND deletests=0;", $CKoneksiInvReg->bukaKoneksi());
			$CHistory->updateLogInvReg($userIdLogin, "PAYMENT PAID - Transfer to Accounting (idmailinv=<b>".$row['idmailinv']."</b>, paid=<b>Y</b>, datepaid=<b>".$datePaidGet."</b>,     Amount to be paid=<b>".$amtConvGet."</b>, Curreny to be paid='".$row['']."', Adjusment Account=<b>".$adjAccGet."</b>, Adjusment Amount=<b>".$adjAmtGet."</b>, paidby=<b>".$userInit."</b>, paytype=<b>".$payTypeGet."</b>, bankcode=<b>".$bankCodeGet."</b>, voucher=<b>".$voucherGet."</b>, reference=<b>".$referenceGet."</b>, chequeno=<b>".$chequeNoGet."</b>)");
		}
		
		$totalAmount = totalAmount($CKoneksiInvReg, $transNoGet);
		$totalRowByTransno = totalRowByTransno($CKoneksiInvReg, $transNoGet);
		
		if($currency == "IDR")
		{
			$query = $CKoneksiInvReg->mysqlQuery("SELECT *, (amount + addi)-deduc AS jmlAmount FROM mailinvoice WHERE transno=".$transNoGet." AND deletests=0 ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
			while($row = $CKoneksiInvReg->mysqlFetch($query))
			{					
				$jmlAmount = $row['jmlAmount'];
				// SIMPAN ROW UNTUK BOOKSTS = DEBIT
				$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
				(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Remark, Entryuser, Progdebug, Jobnumber) VALUES 
				('".$company."', '".$source."', '".$bookDate."', '".$voucherGet."', '".$refNumber."', '".substr($row['mailinvno'], 0, 16)."', '".$row['barcode']."', '".substr($CPublic->ms_escape_string( $row['description'] ), 0, 70)."', '".$row['vescode']."', '".$row['kreditacc']."', '', '', '".$currency."', 'DB', '".number_format((float)$jmlAmount, 2, '.', '')."', '".$currency."', '^', '".$entryDate."', '".$payTypeGet."', '".$userName."', 'Payment:".$CPublic->zerofill($row['transno'], 6)."', '".$row['transno']."')");
			}

			if($amtConvGet != "" && $amtConvGet != $totalAmount) // JIKA AMOUNT TO BE PAID DIISI DAN AMOUNT TO BE PAID TIDAK SAMA DENGAN TOTAL AMOUNT
			{				
				$bookSts = "DB";
				if($amtConvGet < $totalAmount) // JIKA AMOUNT TO BE PAID KURANG DARI TOTAL AMOUNT SEMUA TAGIHAN DI TRANSNO YANG SAMA MAKA BOOKSTS = CREDIT
				{	
					$bookSts = "CR";	
				}
				
				$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
				(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Remark, Entryuser, Progdebug, Jobnumber) VALUES 
				('".$company."', '".$source."', '".$bookDate."', '".$voucherGet."', '".$refNumber."', '".substr($invNo, 0, 16)."', '".$barcode."', 'SELISIH KOMA', '', '".$adjAccGet."', '', '', '".$currency."', '".$bookSts."', '".number_format((float)$adjAmtGet, 2, '.', '')."', '".$currency."', '^', '".$entryDate."', '".$payTypeGet."', '".$userName."', 'Payment:".$CPublic->zerofill($transNo, 6)."', '".$transNo."')");
				
				/* SKRIP LAMA UNTUK MENENTUKAN BOOKDESC BIARKAN DI REMARK
				$bookDesc = $CInvReg->detilMailInv($idMailInvGet, "description");
				if($adjAmtGet != "" && $adjAmtGet != "0") // JIKA ADJUSMENT AMOUNT TIDAK SAMA DENGAN KOSONG, ATAU ADA PEMBULATAN DI BELAKANG KOMA
				{
					$bookDesc = "SELISIH KOMA";
				}
				
				$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
				(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Remark, Entryuser, Progdebug, Jobnumber) VALUES 
				('".$company."', '".$source."', '".$bookDate."', '".$voucherGet."', '".$refNumber."', '', '', '".substr($CPublic->ms_escape_string( $bookDesc ), 0, 70)."', '', '".$adjAccGet."', '', '', '".$currency."', '".$bookSts."', '".number_format((float)$adjAmtGet, 2, '.', '')."', '".$currency."', '^', '".$entryDate."', '".$payTypeGet."', '".$userName."', 'Payment:".$CPublic->zerofill($transNo, 6)."', '".$transNo."')");*/
				$totalAmount = $amtConvGet;
			}
			
			$bookDesc = substr($CPublic->ms_escape_string( $kreditAccNm ), 0, 70);
			if($kreditAcc == "12300") // JIKA ACCOUNT PAYABLE MAKA TAMPILKAN SENDERVENDOR NAME
				$bookDesc = substr($CPublic->ms_escape_string( $CInvReg->detilMailInv($idMailInvGet, "sendervendor1") ), 0, 70);
				
			// SIMPAN ROW UNTUK BOOKSTS = CREDIT
			$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
			(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Remark, Entryuser, Progdebug, Jobnumber) VALUES 
			('".$company."', '".$source."', '".$bookDate."', '".$voucherGet."', '".$refNumber."', '".substr($invNo, 0, 16)."', '".$barcode."', '".$bookDesc."', '', '".$bankCodeGet."', '', '', '".$currency."', 'CR', '".number_format((float)$totalAmount, 2, '.', '')."', '".$currency."', '^', '".$entryDate."', '".$payTypeGet."', '".$userName."', 'Payment:".$CPublic->zerofill($transNo, 6)."', '".$transNo."')");
		}
		else
		{
			$totalAmountToIdr = 0;

			if($amtConvGet == "" || $amtConvGet == "0.00" || $amtConvGet == "0")// JIKA AMOUNT TO BE PAID TIDAK DIISI ATAU KOSONG
			{
				$query = $CKoneksiInvReg->mysqlQuery("SELECT *, (amount + addi)-deduc AS jmlAmount FROM mailinvoice WHERE transno=".$transNoGet." AND deletests=0 ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
				while($row = $CKoneksiInvReg->mysqlFetch($query))
				{					
					$jmlAmount = $row['jmlAmount'];
					// SIMPAN ROW UNTUK BOOKSTS = DEBIT
					$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." (Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Remark, Entryuser, Progdebug, Jobnumber) VALUES ('".$company."', '".$source."', '".$bookDate."', '".$voucherGet."', '".$refNumber."', '".substr($row['mailinvno'], 0, 16)."', '".$row['barcode']."', '".substr($CPublic->ms_escape_string( $row['description'] ), 0, 70)."', '".$row['vescode']."', '".$row['kreditacc']."', '', '', '".$currency."', 'DB', '".number_format((float)$jmlAmount, 2, '.', '')."', '".$currency."', '^', '".$entryDate."', '".$payTypeGet."', '".$userName."', 'Payment:".$CPublic->zerofill($row['transno'], 6)."', '".$row['transno']."')");
				}
				
				$bookDesc = substr($CPublic->ms_escape_string( $kreditAccNm ), 0, 70);
				if($kreditAcc == "12300") // JIKA ACCOUNT PAYABLE MAKA TAMPILKAN SENDERVENDOR NAME
					$bookDesc = substr($CPublic->ms_escape_string( $CInvReg->detilMailInv($idMailInvGet, "sendervendor1") ), 0, 70);
					
				// SIMPAN ROW UNTUK BOOKSTS = CREDIT
				$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." (Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Remark, Entryuser, Progdebug, Jobnumber) VALUES ('".$company."', '".$source."', '".$bookDate."', '".$voucherGet."', '".$refNumber."', '".substr($invNo, 0, 16)."', '".$barcode."', '".$bookDesc."', '', '".$bankCodeGet."', '', '', '".$currency."', 'CR', '".number_format((float)$totalAmount, 2, '.', '')."', '".$currency."', '^', '".$entryDate."', '".$payTypeGet."', '".$userName."', 'Payment:".$CPublic->zerofill($transNo, 6)."', '".$transNo."')");
			}
			if($amtConvGet != "" && $amtConvGet != $totalAmount) // JIKA AMOUNT TO BE PAID DIISI DAN AMOUNT TO BE PAID TIDAK SAMA DENGAN TOTAL AMOUNT
			{
				$pageRow = 0;
				$query = $CKoneksiInvReg->mysqlQuery("SELECT *, (amount + addi)-deduc AS jmlAmount FROM mailinvoice WHERE transno=".$transNoGet." AND deletests=0 ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
				while($row = $CKoneksiInvReg->mysqlFetch($query))
				{			
					$pageRow++;
					$amountToIdr = round(($row['jmlAmount'] / $totalAmount) * $amtConvGet, 2); //JML AMOUNT USD / TOTAL AMOUNT USD * AMOUNT TO BE PAID
					
					if($pageRow != $totalRowByTransno)
					{
						$totalAmountToIdr += $amountToIdr;
					}
					
					if($pageRow > 1 && $pageRow == $totalRowByTransno) // JIKA ROW MENUNJUK PADA ROW PALING AKHIR MAKA AMOUNT DIHITUNG BERDASARKAN SISA DARI AMOUNT TO BE PAID DIKURANGI TOTAL AMOUNT(ADALAH JUMLAH $AMOUNT SELAIN DARI ROW TERAKHIR)
					{
						$amountToIdr = $amtConvGet - $totalAmountToIdr;
					}
					
					//$Currcy = "IDR";
					$Currcy = $_GET['currency'];
					
					// SIMPAN ROW UNTUK BOOKSTS = DEBIT
					$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
					(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Remark, Entryuser, Progdebug, Jobnumber) VALUES 
					('".$company."', '".$source."', '".$bookDate."', '".$voucherGet."', '".$refNumber."', '".substr($row['mailinvno'], 0, 16)."', '".$row['barcode']."', '".substr($CPublic->ms_escape_string( $row['description'] ), 0, 70)."', '".$row['vescode']."', '".$row['kreditacc']."', '', '', '".$Currcy ."', 'DB', '".number_format((float)$amountToIdr, 2, '.', '')."', '".$Currcy ."', '^', '".$entryDate."', '".$payTypeGet."', '".$userName."', 'Payment:".$CPublic->zerofill($row['transno'], 6)."', '".$row['transno']."')");
				}
				
				$bookDesc = substr($CPublic->ms_escape_string( $kreditAccNm ), 0, 70);
				if($kreditAcc == "12300") // JIKA ACCOUNT PAYABLE MAKA TAMPILKAN SENDERVENDOR NAME
					$bookDesc = substr($CPublic->ms_escape_string( $CInvReg->detilMailInv($idMailInvGet, "sendervendor1") ), 0, 70);
					
				// SIMPAN ROW UNTUK BOOKSTS = CREDIT
				$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
				(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Remark, Entryuser, Progdebug, Jobnumber) VALUES 
				('".$company."', '".$source."', '".$bookDate."', '".$voucherGet."', '".$refNumber."', '".substr($invNo, 0, 16)."', '".$barcode."', '".$bookDesc."', '', '".$bankCodeGet."', '', '', '".$Currcy ."', 'CR', '".number_format((float)$amtConvGet, 2, '.', '')."', '".$Currcy ."', '^', '".$entryDate."', '".$payTypeGet."', '".$userName."', 'Payment:".$CPublic->zerofill($transNo, 6)."', '".$transNo."')");
			}
		}
	}
	
	if($aksiGet == "cancelPayment")
	{
		//echo "<br><br>".$aksiGet;
		$idMailInvGet = $_GET['idMailInv'];
		$reasonCancelPayGet = $_GET['reasonCancelPay'];
		$transNo = $CInvReg->detilMailInv($idMailInvGet, "transno");
		//echo "<br><br>".$idMailInvGet;
		$CKoneksiInvReg->mysqlQuery("UPDATE mailinvoice SET cancelpaid='Y', reasoncanpaid='".mysql_escape_string($reasonCancelPayGet)."' WHERE idmailinv='".$idMailInvGet."' AND deletests=0;", $CKoneksiInvReg->bukaKoneksi());
		$CHistory->updateLogInvReg($userIdLogin, "CANCEL PAYMENT PAID (cancelpaid=<b>y</b>, Reason=<b>".$reasonCancelPayGet."</b> (idmailinv=<b>".$idMailInvGet."</b>,trans. no=<b>".$transNo."</b>))");
	}
	
	//######################################################################################################################################################

	$pageTransno = $_GET['pageTransno']; // PARAMETER YANG DIDAPAT DARI MENU PAGE DI PARENT
	$totalTransno = totalTransno($CKoneksiInvReg, $aksiGet, $paramCariGet); // TOTAL KESELURUHAN DATA TRANSNO

	$limitTransno = 100; // LIMIT PERPAGE YANG DITENTUKAN
	
	$maxPage = ceil($totalTransno/$limitTransno); // MAX PAGE YANG DIDAPAT DARI PEMBAGIAN PEMBULATAN 
	$sisa =  ($totalTransno - ($limitTransno * ($maxPage - 1))); // SISA DATA JIKA DIKURANGI KELIPATAN LIMIT, MISAL TOTALTRANSNO=74, MAXPAGE=3, LIMITTRANSNO=30, MAKA KELIPATAN LIMIT LIMITTRANSNO X MAXPAGE - 1 (30X2=90), MAKA SISA ADALAH 74 - 60 = 14
	
	if(isset($pageTransno)){$pageNum = $pageTransno;}	
	
	if($pageTransno == "" || $pageTransno == 0 || $totalTransno  == 0)
	{
		$offset = 0;
		$maxPage = 1;
		$pageTransno = 1;
	}
	else
	{
		$offset = ($pageTransno - 1) * $limitTransno;
	}
	/* JIKA PAGE 1 LIMIT NYA ADALAH SISA DARI KELIPATAN LIMIT PERPAGE (YANG SEDIKIT ADA DI HALAMAN PERTAMA DAN HALAMAN TERAKHIR LIMITNYA SESUAI LIMIT YANG DITENTUKAN)
	if($pageTransno == 1)
	{
		$offset = 0;
		$limitTransno = $sisa;
	}
	else
	{
		$offset = (($pageNum-2) * $limitTransno) + $sisa;
		$limitTransno = $limitTransno;
	}*/
	
	/*if($pageTransno < 1)
	{
		$urut = 1;
	}
	elseif($pageTransno >= 1)
	{
		$urut = $offset + 1;
	}
	
	//echo "<br><br>".$offset.",".$limitTransno;
	*/
    $width = "355";
	$width2 = "75";
    $totalRowBatch = totalRowBatch($CKoneksiInvReg, $aksiGet, $paramCariGet, $offset, $limitTransno);
    if($totalRowBatch > 16)
    {
        $width = "338"; // selisih 17
		$width2 = "58";
    }
?>
    <table id="judul" cellpadding="0" cellspacing="0" width="<?php echo $width; ?>" border="0" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:10px;position:fixed;left:0px;top:0px;z-index:10;">
    <tr align="center">
        <td width="70" class="tabelBorderRightJust" height="30">STATUS</td>
        <td width="60" class="tabelBorderRightJust">TRANS NO</td>
        <td width="150" class="tabelBorderRightJust">INV. NUMBER</td>
        <td width="<?php echo $width2; ?>" style="font-size:0.9em;">DATE GENERATED</td>
    </tr>
    </table>
    
    <?php
	$i = 0;
	$tabel = "";
?>
    <table cellpadding="0" cellspacing="0" width="<?php echo $width; ?>" style="font:0.7em sans-serif;color:#333;margin-top:30px;">
<?php
	$sortByGet = $_GET['sortBy'];
	//$orderBy = orderBy($sortByGet);
	if($aksiGet == "ketikElementCariBatch")// MENCARI HANYA TRANSNO DI HALAMAN PAYMENT BY BATCH
	{
		$paramCariGet = $_GET['paramCari'];
		//$query = $CKoneksiInvReg->mysqlQuery("SELECT idmailinv, urutan, currency, transno, mailinvno, datepreppay, paid, paytype, bankcode, voucher, reference, chequeno, datepaid FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND preparepayment='Y' AND transno LIKE '%".$paramCariGet."%' OR mailinvno LIKE '%".$paramCariGet."%' AND transno !=0 AND deletests=0 GROUP BY transno ORDER BY 0+transno DESC LIMIT ".$offset.",".$limitTransno.";", $CKoneksiInvReg->bukaKoneksi());
		$query = $CKoneksiInvReg->mysqlQuery("SELECT idmailinv, urutan, currency, mailinvno, amount, addi, deduc, transno, datepreppay, paid, cancelpaid, reasoncanpaid, paytype, bankcode, voucher, reference, chequeno, datepaid, amtconv, currconv, adjacc, adjamt FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND preparepayment='Y' AND transno LIKE '%".$paramCariGet."%' OR mailinvno LIKE '%".$paramCariGet."%' AND transno !=0 AND deletests=0 GROUP BY transno ORDER BY 0+transno DESC LIMIT ".$offset.",".$limitTransno.";", $CKoneksiInvReg->bukaKoneksi());
	}
	else
	{
		$query = $CKoneksiInvReg->mysqlQuery("SELECT idmailinv, urutan, currency, mailinvno, amount, addi, deduc, transno, datepreppay, paid, cancelpaid, reasoncanpaid, paytype, bankcode, voucher, reference, chequeno, datepaid, amtconv, currconv, adjacc, adjamt FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND preparepayment='Y' AND deletests=0 GROUP BY transno ORDER BY 0+transno DESC LIMIT ".$offset.",".$limitTransno.";", $CKoneksiInvReg->bukaKoneksi());
	}
	while($row = $CKoneksiInvReg->mysqlFetch($query))
	{
		$i++;
			
		$rowColor = $CPublic->rowColorCustom($i, "#FFFFFF", "#F0F1FF");
		
		$dateGenerate = $CPublic->convTglNonDB($row['datepreppay']);
		
		$status = "&nbsp;";
		if($row['paid'] == "Y")
			$status = "PAID";
		if($row['cancelpaid'] == "Y")
			$status = "CANCELLED";
		
		$datePaid = $CPublic->convTglNonDB( $row['datepaid'] );
		if($datePaid == "00/00/0000")
		{	$datePaid = ""; 	}
		
		$totalAmount = totalAmount($CKoneksiInvReg, $row['transno']);
			
		$klikRowBatch = "parent.klikRowBatch('".$row['idmailinv']."', '". $CPublic->zerofill($row['transno'], 6)."', '".number_format((float)$totalAmount, 2, '.', '')."', '".$row['paid']."', '".$row['cancelpaid']."', '".$row['reasoncanpaid']."', '".$row['paytype']."', '".$row['bankcode']."', '".$row['voucher']."', '".$row['reference']."', '".$row['chequeno']."', '".$datePaid ."', '".$row['currency']."', '".$row['amtconv']."', '".$row['currconv']."', '".$row['adjacc']."', '".$row['adjamt']."');";
		$onClick = "onClickTrBatch('".$i."', '".$row['idmailinv']."', '".$CPublic->zerofill($row['transno'], 6)."', '".$rowColor."');".$klikRowBatch;
		$clikTR = $onClick;
		
		$tabel.="" 
?>
		<tr align="center" valign="bottom" bgcolor="<?php echo $rowColor; ?>"  onMouseOver="this.style.backgroundColor='#D9EDFF';" onMouseOut="this.style.backgroundColor='<?php echo $rowColor; ?>';" id="tr<?php echo $i; ?>" onClick="<?php echo $clikTR; ?>" 
			style="cursor:pointer;padding-bottom:1px;">
			<!--<td width="40" height="22" class="tabelBorderTopLeftNull"><?php echo $row['urutan']; ?></td>-->
            <td width="70" height="22" class="tabelBorderTopLeftNull"><?php echo $status; ?></td>
			<td width="60" class="tabelBorderTopLeftNull" style="font-size:11px;color:#096;font-weight:bold;"><?php echo $CPublic->zerofill($row['transno']); ?></td>
            <td width="150" class="tabelBorderTopLeftNull"><?php echo $CPublic->potongKarakter($CPublic->zerofill($row['mailinvno']), 18); ?></td>
            
			<td width="<?php echo $width2; ?>" class="tabelBorderBottomJust"><?php echo $dateGenerate; ?></td>
		</tr>
<?php 		
		echo "";
	}
	echo $tabel;
?>
    </table>
<?php	
}
if($aksiGet == "header")
{
	$transNoGet = $_GET['transNo'];
?>
	<table id="judul1" cellpadding="0" cellspacing="0" width="670" border="0" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;">
    <tr align="center">
        <td width="160" height="30" class="">BILLING COMPANY</td>
        <td width="40" class="">CURR</td>
        <td width="130" class="">TOTAL AMOUNT</td>
        <td width="70" class="" style="font-size:10px;">CREDITOR<br>NUMBER</td>
        <td width="270" class="">SENDER / VENDOR NAME</td>
    </tr>
    </table>
<?php
	$i=0;
	$totalAmount = 0;
	$amount = 0;
	$tabel = "";
	
?>   
    <div style="position:absolute;top:30px;overflow:hidden;">
    <table cellpadding="0" cellspacing="0" width="670" style="font:0.7em sans-serif;color:#333;">
<?php
	$query = $CKoneksiInvReg->mysqlQuery("SELECT sendervendor1, tipesenven, sendervendor2, sendervendor2name, companyname, currency, addi, deduc, amount, kreditacc, kreditaccname FROM mailinvoice WHERE transno=".$transNoGet." AND deletests=0 ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
	$jmlRow = $CKoneksiInvReg->mysqlNRows($query);
    while($row = $CKoneksiInvReg->mysqlFetch($query))
    {
		$i++;
		
		$deduc = $row['deduc'];
		$addi = $row['addi'];
		$amount = (($row['amount'] - $deduc) + $addi);
		
		//$amount = $row['amount'];
		$totalAmount += $amount;
				
		if($i == $jmlRow)
		{
			$senderVendor = $row['sendervendor1'];// SENDER / VENDOR NAME
			if($row['tipesenven'] == "2")
				$senderVendor = $row['sendervendor2name'];// SENDER / VENDOR NAME
				
			if($row['sendervendor2'] == "")
				$senderVendor = $row['kreditaccname'];// SENDER / VENDOR NAME
			
			$tabel.="" 
?>
            <tr align="center">
                <td width="160" height="20" class="tabelBorderRightJust"><?php echo $row['companyname']; ?></td>
                <td width="40" class="tabelBorderRightJust"><?php echo $row['currency']; ?></td>
                <td width="130" class="tabelBorderRightJust"><?php echo number_format((float)$totalAmount, 2, '.', ','); ?></td>
                <td width="70" class="tabelBorderRightJust"><?php echo $row['kreditacc']; ?></td>
                <td width="270" class="tabelBorderAllNull"><?php echo $senderVendor; ?></td>
            </tr>
<?php
			echo "";
		}
	}
	echo $tabel;
?>
    </table>
    </div>
<?php
}

if($aksiGet == "groupItem")
{
	$transNoGet = $_GET['transNo'];
?>
	<table id="judul2" cellpadding="0" cellspacing="0" width="1595" border="0" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;position:fixed;left:0px;top:0px;">
    <tr align="center">
        <td width="40" height="30" class="">SNO</td>
        <td width="70" class="" style="font-size:10px;">CREDITOR<br>NUMBER</td>
        <td width="270" class="">SENDER / VENDOR NAME</td>
        <td width="70" class="">INV. DATE</td>
        <td width="85" class="">BARCODE</td>
        <td width="150" class="">INV. NUMBER</td>
        <td width="130" class="">AMOUNT</td>
        <td width="70" class="">DUE DATE</td>
        <td width="160" class="">BILLING COMPANY</td>
        <td width="215" class="">VESSEL NAME</td>
        <td width="335" class="">DESCRIPTION</td>
    </tr>
    </table>
    
    <table cellpadding="0" cellspacing="0" width="1595" style="font:0.7em sans-serif;color:#333;margin-top:30px;">
<?php
	$i = 0;
    $tabel = "";
	$senVenGrupPertama = "";
	$billCompGrupPertama = "";
	$currencyGrupPertama = "";
	
	$query = $CKoneksiInvReg->mysqlQuery("SELECT urutan, vesname, sendervendor1, tipesenven, sendervendor2name, tglinvoice, barcode, mailinvno, currency, addi, deduc, amount, tglexp, companyname, description, kreditacc FROM mailinvoice WHERE transno=".$transNoGet." AND deletests=0 ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
	while($row = $CKoneksiInvReg->mysqlFetch($query))
	{
		$i++;
		$rowColor = $CPublic->rowColorCustom($i, "#FFFFFF", "#F0F1FF");
		
		$senderVendor = $row['sendervendor1'];// SENDER / VENDOR NAME
		if($row['tipesenven'] == "2")
			$senderVendor = $row['sendervendor2name'];// SENDER / VENDOR NAME
			
		$tglInv = $CPublic->convTglNonDB( $row['tglinvoice'] );
		$poNumber = $row['barcode']; // BARCODE
		$invNumber = $row['mailinvno'];
		
		$deduc = $row['deduc'];
		$addi = $row['addi'];
		$amountt = (($row['amount'] - $deduc) + $addi);
		
		$amount = $CPublic->jikaKosongStrip(number_format((float)$amountt, 2, '.', ','));
		$tglexp = $CPublic->convTglNonDB( $row['tglexp'] ); // DUE DATE
		
		$tabel.="" 
?>
		<tr align="center" valign="bottom" bgcolor="<?php echo $rowColor; ?>"  onMouseOver="this.style.backgroundColor='#D9EDFF';" onMouseOut="this.style.backgroundColor='<?php echo $rowColor; ?>';" id="tr<?php echo $i; ?>" 
				style="cursor:pointer;padding-bottom:1px;">
			<td width="40" height="20" class="tabelBorderTopLeftNull"><?php echo $row['urutan']; ?></td>
			
			<td width="70" class="tabelBorderTopLeftNull"><?php echo $row['kreditacc']; ?></td>
			<td width="270" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $senderVendor; ?></td>
			<td width="70" class="tabelBorderTopLeftNull"><?php echo $tglInv; ?></td>
			<td width="85" class="tabelBorderTopLeftNull"><?php echo $poNumber; ?></td>
			<td width="150" class="tabelBorderTopLeftNull">&nbsp;<?php echo $invNumber; ?></td>
			<td width="130" class="tabelBorderTopLeftNull" align="right"><?php echo "(".$row['currency'].")"."&nbsp;".$amount; ?>&nbsp;</td>
			<td width="70" class="tabelBorderTopLeftNull"><?php echo $tglexp; ?></td>
			<td width="160" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $row['companyname']; ?></td>
            <td width="215" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $row['vesname']; ?></td>
			<td width="335" class="tabelBorderBottomJust" align="left">&nbsp;<?php echo $row['description']; ?></td>
		</tr>
<?php		
		echo "";
		
		if($i == 1)
		{
			$senVenGrupPertama = $senderVendor;
			$billCompGrupPertama = $compName;
			$currencyGrupPertama = $currency;
		}
		
	}
	$jmlRowGrup = $i;
	echo $tabel;
?>
    </table>
<?php
}
?>

</body>
</HTML>

<script type="text/javascript">
//alert('<?php echo $aksiGet; ?>');
<?php
if($aksiGet == "transToAcct")
{	
	// JIKA SETELAH DI TRANS TO ACCT (PAID) SUKSES DAN TIDAK MAU MUNCULIN POP UP MAKA FUNGSI JS DIBAWAH INI DIPAKAI
	//echo "onClickTrBatch('".$barisTransnoGet."', '".$idMailInvGet."', '".$transNoGet."', '');";
	//echo "parent.klikRowBatch('".$idMailInvGet."', '".$transNoGet."', 'Y', '".$payTypeGet."', '".$bankCodeGet."', '".$voucherGet."', '".$referenceGet."', '".$chequeNoGet."', '".$CPublic->convTglNonDB( $datePaidGet )."');";
	//echo "$('#btnRetBatch', parent.document).click();";
	?>
	setTimeout(function()
	{ 
		$('#hrefThickbox',parent.document).attr('href','templates/halPopup.php?aksi=suksesPaid&idMailInv=<?php echo $idMailInvGet; ?>&barisTransno=<?php echo $barisTransnoGet; ?>&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=200&width=400&modal=true');
		window.parent.$('#hrefThickbox').click();
	}, 250);
<?php
}

if($aksiGet == "displayTransNo" || $aksiGet == "transToAcct" || $aksiGet == "ketikElementCariBatch" || $aksiGet == "cancelPayment")
{
?>
	var numbers = '<?php echo $maxPage; ?>';
	var pageTransno = '<?php echo $pageTransno; ?>';
	var option = '';
	for (var i=1;i<=numbers;i++)
	{
		if(i == pageTransno)
		{
			option += '<option value="'+ i + '" selected>' + i + '</option>';
		}
		else
		{
			option += '<option value="'+ i + '">' + i + '</option>';
		}
	}
	$('#menuPageTransno', parent.document).html(option);    
	$('#maxPageTransno', parent.document).html(numbers);    
<?php
}
?>
</script>

<?php

function totalTransno($CKoneksiInvReg, $aksiGet, $paramCariGet)
{
	if($aksiGet == "ketikElementCariBatch")// MENCARI HANYA TRANSNO DI HALAMAN PAYMENT BY BATCH
	{
		$paramCariGet = $_GET['paramCari'];
		$query = $CKoneksiInvReg->mysqlQuery("SELECT distinct(transno) as transno FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND preparepayment='Y' AND transno LIKE '%".$paramCariGet."%' OR mailinvno LIKE '%".$paramCariGet."%' AND transno !=0 AND deletests=0;", $CKoneksiInvReg->bukaKoneksi());
	}
	else
	{
		$query = $CKoneksiInvReg->mysqlQuery("SELECT distinct(transno) as transno FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND preparepayment='Y' AND deletests=0;", $CKoneksiInvReg->bukaKoneksi());
	}
	$jmlRow = $CKoneksiInvReg->mysqlNRows($query);
	
	return $jmlRow;
}
function totalRowBatch($CKoneksiInvReg, $aksiGet, $paramCariGet, $offset, $limitTransno)
{
	if($aksiGet == "ketikElementCariBatch")// MENCARI HANYA TRANSNO DI HALAMAN PAYMENT BY BATCH
	{
		$paramCariGet = $_GET['paramCari'];
		$query = $CKoneksiInvReg->mysqlQuery("SELECT distinct(transno) as transno FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND preparepayment='Y' AND transno LIKE '%".$paramCariGet."%' OR mailinvno LIKE '%".$paramCariGet."%' AND transno !=0 AND deletests=0 LIMIT ".$offset.", ".$limitTransno.";", $CKoneksiInvReg->bukaKoneksi());
	}
	else
	{
		$query = $CKoneksiInvReg->mysqlQuery("SELECT distinct(transno) as transno FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND preparepayment='Y' AND deletests=0 LIMIT ".$offset.", ".$limitTransno.";", $CKoneksiInvReg->bukaKoneksi());
	}
	$jmlRow = $CKoneksiInvReg->mysqlNRows($query);
	
	return $jmlRow;
}

function totalAmount($CKoneksiInvReg, $transNo)
{
	$totalAmount = "";
	$query = $CKoneksiInvReg->mysqlQuery("SELECT addi, deduc, amount FROM mailinvoice WHERE transno=".$transNo." AND deletests=0 ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
	$jmlRow = $CKoneksiInvReg->mysqlNRows($query);
    while($row = $CKoneksiInvReg->mysqlFetch($query))
    {
		$deduc = $row['deduc'];
		$addi = $row['addi'];
		$amount = (($row['amount'] - $deduc) + $addi);
		
		$totalAmount += $amount;
	}
	
	return $totalAmount;
}

function totalRowByTransno($CKoneksiInvReg, $transNo)
{
	$totalAmount = "";
	$query = $CKoneksiInvReg->mysqlQuery("SELECT idmailinv FROM mailinvoice WHERE transno=".$transNo." AND deletests=0 ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
	$jmlRow = $CKoneksiInvReg->mysqlNRows($query);
	
	return $jmlRow;
}

/*function totalAmountByTransno($CKoneksiInvReg, $transNoGet)
{
	$query = $CKoneksiInvReg->mysqlQuery("SELECT SUM((amount + addi)-deduc) AS total FROM mailinvoice WHERE transno=".$transNoGet." AND deletests=0 ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
	$row = $CKoneksiInvReg->mysqlFetch($query);
	
	return $row['total'];
}*/
?>