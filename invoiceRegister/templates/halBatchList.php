<!DOCTYPE HTML>
<?php 
require_once("../configInvReg.php");

$aksiGet = $_GET['aksi'];
//$jenisPaymentPost = $_GET['jenisPayment'];
//$prepareByPost = $_GET['prepareBy'];
$whereCMP = "";
if ($userCompany != "") //jika ada triger companyNya
{
	if($userCompany != "ALL")
	{
		$dC = explode(",", $userCompany);
		if (count($dC) > 1)
		{
			$cmpNya = '';
			for ($lan=0; $lan < count($dC); $lan++) 
			{ 
				if ($cmpNya == "")
				{
					$cmpNya = "'".$dC[$lan]."'";
				}else{
					$cmpNya .= ",'".$dC[$lan]."'";
				}
			}
			$whereCMP = " AND company IN(".$cmpNya.")";
		}else{
			$whereCMP = " AND company = '".$dC[0]."' ";
		}
	}
}
$yNow = date("Y");
$yBeforeNow = $yNow - 2;
$yBeforeNow = 2002;

$whereCMP .= " AND YEAR(receivedate) >= ".$yBeforeNow;

?>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript" src="../js/payment.js"></script>
<script language="javascript">
function onClickTrBatch(trId, idMailInv, transNo, bgColor) {
    var idTrSeb = document.getElementById('idTrSeb').value;
    var bgColorSeb = document.getElementById('bgColorSeb').value;

    if (idTrSeb != "") {
        document.getElementById(idTrSeb).onmouseover = function onmouseover() {
            this.style.backgroundColor = '#D9EDFF';
        }
        document.getElementById(idTrSeb).onmouseout = function onmouseout() {
            this.style.backgroundColor = bgColorSeb;
        }
        document.getElementById(idTrSeb).style.fontWeight = '';
        document.getElementById(idTrSeb).style.backgroundColor = bgColorSeb;
        document.getElementById(idTrSeb).style.cursor = 'pointer';
        //document.getElementById(idTrSeb).style.height = "22";
        document.getElementById(idTrSeb).style.fontWeight = ''; // FONT TIDAK BOLD UNTUK TD YANG DIPILIH
    }

    document.getElementById('tr' + trId).onmouseout = '';
    document.getElementById('tr' + trId).onmouseover = '';
    document.getElementById('tr' + trId).style.fontWeight = 'bold';
    document.getElementById('tr' + trId).style.backgroundColor = '#B0DAFF';
    document.getElementById('tr' + trId).style.cursor = 'default';
    document.getElementById('tr' + trId).style.fontSize = '11px';
    document.getElementById('idTrSeb').value = 'tr' + trId;
    //document.getElementById('tr'+trId).style.height = "";

    document.getElementById('bgColorSeb').value = bgColor;
    parent.document.getElementById('idMailInv').value = idMailInv;

    $('#barisTransno', parent.document).val(trId); // ID DARI TR YANG DIPILIH DI IFRAME TRANSNO DATA
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
}

window.onload =
    function() {
        var userJenis = "<?php echo $userJenis; ?>";
        if (userJenis != "admin") {
            document.oncontextmenu = function() {
                return false;
            };
        }
        loadScroll('halBatchList');
        parent.doneWait();
        parent.panggilEnableLeftClick();
    }

$(window).scroll(function() {
    $('#judul').css('left', '-' + $(window).scrollLeft());
});
</script>

<link href="../css/invReg.css" rel="stylesheet" type="text/css" />
<link href="../css/table.css" rel="stylesheet" type="text/css" />
<link href="../css/button.css" rel="stylesheet" type="text/css" />

<body onUnload="saveScroll('halBatchList')">
    <input type="hidden" id="idTrSeb" name="idTrSeb">
    <input type="hidden" id="bgColorSeb" name="bgColorSeb">

    <?php
if($aksiGet == "displayTransNo" || $aksiGet == "transToAcct" || $aksiGet == "ketikElementCariBatch" || $aksiGet == "cancelPayment" || $aksiGet == "saveData")
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
	$subAcctGet = $_GET['subAcct'];
	//echo $idMailInvGet." / ".$transNoGet." / ".$datePaidGet." / ".$payTypeGet." / ".$bankCodeGet." / ".$voucherGet." / ".$chequeNoGet;
	
	//echo "<br><br>".$amtConvGet." / ".$adjAccGet." / ".$adjAmtGet."<br>";
	$invNo = $CInvReg->detilMailInv($idMailInvGet, "mailinvno");
	$barcode = $CInvReg->detilMailInv($idMailInvGet, "barcode");
	$thnDatePaid = substr($datePaidGet, 0, 4);
	$blnDatePaid = substr($datePaidGet, 5, 2);
	$company = $CInvReg->detilMailInv($idMailInvGet, "company");
	$cmpPaidBy = $CInvReg->detilMailInv($idMailInvGet, "cmppaidby");
	if($cmpPaidBy != "")
	{
		$company = $cmpPaidBy;
	}
	
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
	if($aksiGet == "saveData")
	{
		$sql = $CKoneksiInvReg->mysqlQuery("SELECT * FROM mailinvoice WHERE transno=".$transNoGet." AND deletests=0 ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
		while($rowSql = $CKoneksiInvReg->mysqlFetch($sql))
		{
			$CKoneksiInvReg->mysqlQuery("UPDATE mailinvoice SET amtconv='".$amtConvGet."', currconv='".$_GET['currency']."', adjacc='".$adjAccGet."', adjamt='".$adjAmtGet."', paytype='".$payTypeGet."', bankcode='".$bankCodeGet."', voucher='".$voucherGet."', reference='".$referenceGet."', chequeno='".$chequeNoGet."', updusrdt='".$userWhoAct."' WHERE idmailinv='".$rowSql['idmailinv']."' AND deletests=0;", $CKoneksiInvReg->bukaKoneksi());
		}
	}
	if($aksiGet == "transToAcct")
	{
		$queryCek = $CKoneksiInvReg->mysqlQuery("SELECT * FROM tblsplit WHERE idmailinv = '".$idMailInvGet."' AND fieldaksi = 'memocredit' ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
		$dataCek = $CKoneksiInvReg->mysqlNRows($queryCek);

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
			if ($dataCek == "0") 
			{
				$query = $CKoneksiInvReg->mysqlQuery("SELECT *, (amount + addi)-deduc AS jmlAmount FROM mailinvoice WHERE transno=".$transNoGet." AND deletests=0 ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
				while($row = $CKoneksiInvReg->mysqlFetch($query))
				{					
					$jmlAmount = $row['jmlAmount'];
					// SIMPAN ROW UNTUK BOOKSTS = DEBIT

						$tempSubAcct = "";
						if($row['kreditacc'] == "12300")
						{
							$tempSubAcct = $subAcctGet;
						}

					$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
					(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Remark, Entryuser, Progdebug, Jobnumber) VALUES 
					('".$company."', '".$source."', '".$bookDate."', '".$voucherGet."', '".$refNumber."', '".substr($row['mailinvno'], 0, 40)."', '".$row['barcode']."', '".substr($CPublic->ms_escape_string( $row['description'] ), 0, 70)."', '".$row['vescode']."', '".$row['kreditacc']."', '".$tempSubAcct."', '', '".$currency."', 'DB', '".number_format((float)$jmlAmount, 2, '.', '')."', '".$currency."', '^', '".$entryDate."', '".$payTypeGet."', '".$userName."', 'Payment:".$CPublic->zerofill($row['transno'], 6)."', '".$row['transno']."')");
				}
			}else{
				$querySplit = $CKoneksiInvReg->mysqlQuery("SELECT * FROM tblsplit WHERE idmailinv = '".$idMailInvGet."' AND fieldaksi = 'memocredit' ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
				while($rowSplite = $CKoneksiInvReg->mysqlFetch($querySplit))
				{
					$tempSubAcct = "";
					if($rowSplite['account'] == "12300")
					{
						$tempSubAcct = $subAcctGet;
					}

					$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, 
					"INSERT INTO ".$dbAccounting."(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Remark, Entryuser, Progdebug, Jobnumber) VALUES ('".$company."', '".$source."', '".$bookDate."', '".$voucherGet."', '".$refNumber."', '".substr($invNo, 0, 40)."', '".$barcode."', '".$rowSplite['description']."', '', '".$rowSplite['account']."', '".$tempSubAcct."', '', '".$currency."', 'DB', '".number_format((float)$rowSplite['amount'], 2, '.', '')."', '".$currency."', '^', '".$entryDate."', '".$payTypeGet."', '".$userName."', 'Payment:".$CPublic->zerofill($transNo, 6)."', '".$transNo."')");
				}
			}

			if($amtConvGet != "" && $amtConvGet != $totalAmount) // JIKA AMOUNT TO BE PAID DIISI DAN AMOUNT TO BE PAID TIDAK SAMA DENGAN TOTAL AMOUNT
			{				
				$bookSts = "DB";
				$tempSubAcct = "";
				if($amtConvGet < $totalAmount) // JIKA AMOUNT TO BE PAID KURANG DARI TOTAL AMOUNT SEMUA TAGIHAN DI TRANSNO YANG SAMA MAKA BOOKSTS = CREDIT
				{	
					$bookSts = "CR";
					$tempSubAcct = $subAcctGet;
				}

				if($adjAccGet == "12300")
				{
					$tempSubAcct = $subAcctGet;
				}
				
				$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
				(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Remark, Entryuser, Progdebug, Jobnumber) VALUES 
				('".$company."', '".$source."', '".$bookDate."', '".$voucherGet."', '".$refNumber."', '".substr($invNo, 0, 40)."', '".$barcode."', 'SELISIH KOMA', '', '".$adjAccGet."', '".$tempSubAcct."', '', '".$currency."', '".$bookSts."', '".number_format((float)$adjAmtGet, 2, '.', '')."', '".$currency."', '^', '".$entryDate."', '".$payTypeGet."', '".$userName."', 'Payment:".$CPublic->zerofill($transNo, 6)."', '".$transNo."')");
				
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

			$tempSubAcct = "";
			if($bankCodeGet == "12300")
			{
				$tempSubAcct = $subAcctGet;
			}

			$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
			(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Remark, Entryuser, Progdebug, Jobnumber) VALUES 
			('".$company."', '".$source."', '".$bookDate."', '".$voucherGet."', '".$refNumber."', '".substr($invNo, 0, 40)."', '".$barcode."', '".$bookDesc."', '', '".$bankCodeGet."', '".$tempSubAcct."', '', '".$currency."', 'CR', '".number_format((float)$totalAmount, 2, '.', '')."', '".$currency."', '^', '".$entryDate."', '".$payTypeGet."', '".$userName."', 'Payment:".$CPublic->zerofill($transNo, 6)."', '".$transNo."')");
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

					$tempSubAcct = "";
					if($row['kreditacc'] == "12300")
					{
						$tempSubAcct = $subAcctGet;
					}

					$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." (Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Remark, Entryuser, Progdebug, Jobnumber) VALUES ('".$company."', '".$source."', '".$bookDate."', '".$voucherGet."', '".$refNumber."', '".substr($row['mailinvno'], 0, 40)."', '".$row['barcode']."', '".substr($CPublic->ms_escape_string( $row['description'] ), 0, 70)."', '".$row['vescode']."', '".$row['kreditacc']."', '".$tempSubAcct."', '', '".$currency."', 'DB', '".number_format((float)$jmlAmount, 2, '.', '')."', '".$currency."', '^', '".$entryDate."', '".$payTypeGet."', '".$userName."', 'Payment:".$CPublic->zerofill($row['transno'], 6)."', '".$row['transno']."')");
				}
				
				$bookDesc = substr($CPublic->ms_escape_string( $kreditAccNm ), 0, 70);
				if($kreditAcc == "12300") // JIKA ACCOUNT PAYABLE MAKA TAMPILKAN SENDERVENDOR NAME
					$bookDesc = substr($CPublic->ms_escape_string( $CInvReg->detilMailInv($idMailInvGet, "sendervendor1") ), 0, 70);
					
				// SIMPAN ROW UNTUK BOOKSTS = CREDIT

				$tempSubAcct = "";
				if($bankCodeGet == "12300")
				{
					$tempSubAcct = $subAcctGet;
				}

				$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." (Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Remark, Entryuser, Progdebug, Jobnumber) VALUES ('".$company."', '".$source."', '".$bookDate."', '".$voucherGet."', '".$refNumber."', '".substr($invNo, 0, 40)."', '".$barcode."', '".$bookDesc."', '', '".$bankCodeGet."', '".$tempSubAcct."', '', '".$currency."', 'CR', '".number_format((float)$totalAmount, 2, '.', '')."', '".$currency."', '^', '".$entryDate."', '".$payTypeGet."', '".$userName."', 'Payment:".$CPublic->zerofill($transNo, 6)."', '".$transNo."')");
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

					$tempSubAcct = "";
					if($row['kreditacc'] == "12300")
					{
						$tempSubAcct = $subAcctGet;
					}

					$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
					(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Remark, Entryuser, Progdebug, Jobnumber) VALUES 
					('".$company."', '".$source."', '".$bookDate."', '".$voucherGet."', '".$refNumber."', '".substr($row['mailinvno'], 0, 40)."', '".$row['barcode']."', '".substr($CPublic->ms_escape_string( $row['description'] ), 0, 70)."', '".$row['vescode']."', '".$row['kreditacc']."', '".$tempSubAcct."', '', '".$Currcy ."', 'DB', '".number_format((float)$amountToIdr, 2, '.', '')."', '".$Currcy ."', '^', '".$entryDate."', '".$payTypeGet."', '".$userName."', 'Payment:".$CPublic->zerofill($row['transno'], 6)."', '".$row['transno']."')");
				}
				
				$bookDesc = substr($CPublic->ms_escape_string( $kreditAccNm ), 0, 70);
				if($kreditAcc == "12300") // JIKA ACCOUNT PAYABLE MAKA TAMPILKAN SENDERVENDOR NAME
					$bookDesc = substr($CPublic->ms_escape_string( $CInvReg->detilMailInv($idMailInvGet, "sendervendor1") ), 0, 70);
					
				// SIMPAN ROW UNTUK BOOKSTS = CREDIT

				$tempSubAcct = "";
				if($bankCodeGet == "12300")
				{
					$tempSubAcct = $subAcctGet;
				}	

				$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
				(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Remark, Entryuser, Progdebug, Jobnumber) VALUES 
				('".$company."', '".$source."', '".$bookDate."', '".$voucherGet."', '".$refNumber."', '".substr($invNo, 0, 40)."', '".$barcode."', '".$bookDesc."', '', '".$bankCodeGet."', '".$tempSubAcct."', '', '".$Currcy ."', 'CR', '".number_format((float)$amtConvGet, 2, '.', '')."', '".$Currcy ."', '^', '".$entryDate."', '".$payTypeGet."', '".$userName."', 'Payment:".$CPublic->zerofill($transNo, 6)."', '".$transNo."')");
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

	$pageTransnoGet = $_GET['pageTransno']; // PARAMETER YANG DIDAPAT DARI MENU PAGE DI PARENT
	$totalTransno = totalTransno($CKoneksiInvReg, $aksiGet, $paramCariGet); // TOTAL KESELURUHAN DATA TRANSNO

	$limitTransno = 100; // LIMIT PERPAGE YANG DITENTUKAN
	
	$maxPage = ceil($totalTransno/$limitTransno); // MAX PAGE YANG DIDAPAT DARI PEMBAGIAN PEMBULATAN 
	$sisa =  ($totalTransno - ($limitTransno * ($maxPage - 1))); // SISA DATA JIKA DIKURANGI KELIPATAN LIMIT, MISAL TOTALTRANSNO=74, MAXPAGE=3, LIMITTRANSNO=30, MAKA KELIPATAN LIMIT LIMITTRANSNO X MAXPAGE - 1 (30X2=90), MAKA SISA ADALAH 74 - 60 = 14
	
	if(isset($pageTransnoGet))
	{
		$pageNum = $pageTransnoGet;
	}	
	$pageTransno = 0;
	if($pageTransnoGet == "" || $pageTransnoGet == 0 || $totalTransno  == 0)
	{
		$offset = 0;
		$maxPage = 1;
		$pageTransno = 1;
	}
	else
	{
		$offset = ($pageTransnoGet - 1) * $limitTransno;
		$pageTransno = $pageTransnoGet;
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
    <table id="judul" cellpadding="0" cellspacing="0" width="<?php echo $width; ?>" border="0"
        style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:10px;position:fixed;left:0px;top:0px;z-index:10;">
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
    <table cellpadding="0" cellspacing="0" width="<?php echo $width; ?>"
        style="font:0.7em sans-serif;color:#333;margin-top:30px;">
        <?php
	$sortByGet = $_GET['sortBy'];
	//$orderBy = orderBy($sortByGet);
	if($aksiGet == "ketikElementCariBatch")// MENCARI HANYA TRANSNO DI HALAMAN PAYMENT BY BATCH
	{
		$paramCariGet = $_GET['paramCari'];
		//$query = $CKoneksiInvReg->mysqlQuery("SELECT idmailinv, urutan, currency, transno, mailinvno, datepreppay, paid, paytype, bankcode, voucher, reference, chequeno, datepaid FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND preparepayment='Y' AND transno LIKE '%".$paramCariGet."%' OR mailinvno LIKE '%".$paramCariGet."%' AND transno !=0 AND deletests=0 GROUP BY transno ORDER BY 0+transno DESC LIMIT ".$offset.",".$limitTransno.";", $CKoneksiInvReg->bukaKoneksi());
		$query = $CKoneksiInvReg->mysqlQuery("SELECT idmailinv, urutan, company, currency, mailinvno, SUM(amount) AS amount, SUM(addi) AS addi, SUM(deduc) AS deduc, transno, datepreppay, paid, cancelpaid, reasoncanpaid, paytype, bankcode, voucher, reference, chequeno, datepaid, amtconv, currconv, adjacc, adjamt, file_upload,buktibayar_file FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND preparepayment='Y' AND transno LIKE '%".$paramCariGet."%' AND transno !=0 AND deletests=0 ".$whereCMP." GROUP BY transno ORDER BY 0+transno DESC LIMIT ".$offset.",".$limitTransno.";", $CKoneksiInvReg->bukaKoneksi());
	}
	else
	{
		$query = $CKoneksiInvReg->mysqlQuery("SELECT idmailinv, urutan, company, currency, mailinvno, SUM(amount) AS amount, SUM(addi) AS addi, SUM(deduc) AS deduc, transno, datepreppay, paid, cancelpaid, reasoncanpaid, paytype, bankcode, voucher, reference, chequeno, datepaid, amtconv, currconv, adjacc, adjamt, file_upload,buktibayar_file FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND preparepayment='Y' AND deletests=0 ".$whereCMP." GROUP BY transno ORDER BY 0+transno DESC LIMIT ".$offset.",".$limitTransno.";", $CKoneksiInvReg->bukaKoneksi());
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
		
		$totalAmount = ($row['amount'] - $row['deduc']) + $row['addi'];
		// $totalAmount = totalAmount($CKoneksiInvReg, $row['transno']);
		// $totalAmount = 100;
		$paidOptYesNo = cekCustomPay($CKoneksiInvReg, $row['company']);
		// $paidOptYesNo = "Y";

		$payType = $CPublic->jikaParamSamaDenganNilai1(trim($row['paytype']), "", "XXX"); // JIKA PAYTYPE SAMA DENGAN KOSONG MAKA NILAI PAYTYPE = XXX
		$bankCode = $CPublic->jikaParamSamaDenganNilai1(trim($row['bankcode']), "", "XXX"); 
		$amtConv = $CPublic->jikaParamSamaDenganNilai1(trim($row['amtconv']), "0.00", ""); 
		
		$klikRowBatch = "parent.klikRowBatch('".$row['idmailinv']."', '". $CPublic->zerofill($row['transno'], 6)."', '".number_format((float)$totalAmount, 2, '.', '')."', '".$row['paid']."', '".$row['cancelpaid']."', '".$row['reasoncanpaid']."', '".$payType."', '".$bankCode."', '".$row['voucher']."', '".$row['reference']."', '".$row['chequeno']."', '".$datePaid ."', '".$row['currency']."', '".$amtConv."', '".$row['currconv']."', '".$row['adjacc']."', '".$row['adjamt']."', '".$paidOptYesNo."', '".$row['file_upload']."', '".$row['buktibayar_file']."');";
		$onClick = "onClickTrBatch('".$i."', '".$row['idmailinv']."', '".$CPublic->zerofill($row['transno'], 6)."', '".$rowColor."');".$klikRowBatch;
		$clikTR = $onClick;
		
		$tabel.="" 
?>
        <tr align="center" valign="bottom" bgcolor="<?php echo $rowColor; ?>"
            onMouseOver="this.style.backgroundColor='#D9EDFF';"
            onMouseOut="this.style.backgroundColor='<?php echo $rowColor; ?>';" id="tr<?php echo $i; ?>"
            onClick="<?php echo $clikTR; ?>" style="cursor:pointer;padding-bottom:1px;">
            <!--<td width="40" height="22" class="tabelBorderTopLeftNull"><?php echo $row['urutan']; ?></td>-->
            <td width="70" height="22" class="tabelBorderTopLeftNull"><?php echo $status; ?></td>
            <td width="60" class="tabelBorderTopLeftNull" style="font-size:11px;color:#096;font-weight:bold;">
                <?php echo $CPublic->zerofill($row['transno']); ?></td>
            <td width="150" class="tabelBorderTopLeftNull">
                <?php echo $CPublic->potongKarakter($CPublic->zerofill($row['mailinvno']), 18); ?></td>

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
?>
</body>

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
setTimeout(function() {
    $('#hrefThickbox', parent.document).attr('href',
        'templates/halPopup.php?aksi=suksesPaid&idMailInv=<?php echo $idMailInvGet; ?>&barisTransno=<?php echo $barisTransnoGet; ?>&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=200&width=400&modal=true'
    );
    window.parent.$('#hrefThickbox').click();
}, 250);
<?php
}

if($aksiGet == "displayTransNo" || $aksiGet == "transToAcct" || $aksiGet == "ketikElementCariBatch" || $aksiGet == "cancelPayment")
{
?>
//alert('paramCari = <?php echo $paramCariGet; ?> / pageTransno = <?php echo $pageTransno; ?>');
var numbers = '<?php echo $maxPage; ?>';
var pageTransno = '<?php echo $pageTransno; ?>';
var option = '';
for (var i = 1; i <= numbers; i++) {
    if (i == pageTransno) {
        option += '<option value="' + i + '" selected>' + i + '</option>';
    } else {
        option += '<option value="' + i + '">' + i + '</option>';
    }
}
$('#menuPageTransno', parent.document).html(option);
$('#maxPageTransno', parent.document).html(numbers);
<?php
}
if($aksiGet == "ketikElementCariBatch" && $barisTransnoGet != "")
{
?>
document.getElementById('tr<?php echo $barisTransnoGet; ?>').click();
setTimeout(function() {
    $('#btnRetBatch', parent.document).click(); // KLIK BTN RETRIEVE BATCH
}, 500);
<?php	
}
?>
</script>

</HTML>

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

function cekCustomPay($CKoneksiInvReg, $compCode)
{
	$nilai = "no";
	$query = $CKoneksiInvReg->mysqlQuery("SELECT company FROM tblcustompay WHERE company='".$compCode."';", $CKoneksiInvReg->bukaKoneksi());
	$jmlRow = $CKoneksiInvReg->mysqlNRows($query);
	if($jmlRow != 0)
	{
		$nilai = "yes";
	}
	
	return $nilai;
}

/*function totalAmountByTransno($CKoneksiInvReg, $transNoGet)
{
	$query = $CKoneksiInvReg->mysqlQuery("SELECT SUM((amount + addi)-deduc) AS total FROM mailinvoice WHERE transno=".$transNoGet." AND deletests=0 ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
	$row = $CKoneksiInvReg->mysqlFetch($query);
	
	return $row['total'];
}*/
?>