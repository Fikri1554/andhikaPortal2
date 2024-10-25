<!DOCTYPE HTML>
<?php
require_once("../configInvReg.php");

$batchnoGet = $_GET['batchno'];
$idMailInvGet = $_GET['idMailInv'];

print_r($koneksiOdbcAcc->cekConnect());
// exit;
?>

<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="../../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../../js/Parsers.js"></script>
<script type="text/javascript" src="../../js/InputMask.js"></script>
<script type="text/javascript" src="../js/masks.js"></script>
<script type="text/javascript" src="../js/invReg.js"></script>
<script type="text/javascript" src="../js/process.js"></script>

<link rel="stylesheet" type="text/css" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css"
    media="screen">
</LINK>
<link rel="stylesheet" type="text/css" href="../../css/archives.css">
<link rel="stylesheet" type="text/css" href="../css/invReg.css">
<link rel="stylesheet" type="text/css" href="../css/button.css" />
<link rel="stylesheet" type="text/css" href="../css/table.css" />

<script type="text/javascript">
function getVoyage() {
    var thn = $("#tglJurnal").val();
    var vsl = $("#vesselName").val();

    $("#loaderImg").css('visibility', '');
    setTimeout(function() {
        $.post("../halPostMailInv.php", {
            aksi: "getVoyageNo",
            thn: thn,
            vsl: vsl
        }, function(data) {
            $("#slcVoyageNo").empty();
            $("#slcVoyageNo").append(data);
            $("#loaderImg").css('visibility', 'hidden');
        });
    }, 300);
}
</script>

<style>
body {
    background-color: #f9f9f9;
}
</style>

<?php	
if($aksiPost  == "simpanEditInvRet")
{
	$invRetPost = "1";
	$dateRetPost = $_POST['dateReturned'];
	$dateRetDB = $CPublic->convTglDB($dateRetPost);
	$ignoreJePost = $CPublic->jikaParamSmDgNilai( $_POST['ignoredJe'], "on", "1", "0");
	$apprPaymentPost =  $CPublic->jikaParamSmDgNilai($_POST['approvePayment'], "on", "1", "0");
	$splitDebAccPost =  $CPublic->jikaParamSmDgNilai($_POST['splitDebAcc'], "on", "Y", "N"); // ELEMENT JIKA SPLIT
	$splitCreAccPost =  $CPublic->jikaParamSmDgNilai($_POST['splitCreAcc'], "on", "Y", "N");
	$vslCodePost = $_POST['vesselName'];
	$vslName = $CPublic->ms_escape_string( $CInvReg->detilVessel($vslCodePost, "Fullname") );
	$sourcePost = $_POST['source'];
	$debitAccPost = $_POST['debitAcc'];
	$debitAccNamePost = $CPublic->ms_escape_string( $CInvReg->detilAcctCode($debitAccPost, "Acctname") );  
	$subCodePost = $_POST['subCode'];
	$kreditAccPost = $_POST['kreditAcc'];
	$kreditAccNamePost = $CPublic->ms_escape_string( $CInvReg->detilAcctCode($kreditAccPost, "Acctname") );  
	$descriptionPost = mysql_escape_string( $_POST['description'] );
	$voucherPost = $_POST['voucher'];
	$referencePost = $_POST['reference'];
	$tglJurnalPost = $_POST['tglJurnal'];
	$tglJurnalDB = $CPublic->convTglDB($tglJurnalPost);
	$dueDayRetPost = $_POST['dueDayRet'];
	$dueDatePost = $CPublic->convTglDB($_POST['dueDate']);
	$deducRetPost = str_replace(",","",$_POST['deducRet']);
	$addiRetPost = str_replace(",","",$_POST['addiRet']);
	$reasonDeducPost = mysql_escape_string( $_POST['reasonDeduc'] );
	$invNo = mysql_escape_string( $_POST['mailInvNo'] );
	$totalDeducPost = str_replace(",","",$_POST['totalDeduc']);
	$totalAddiPost = str_replace(",","",$_POST['totalAddi']);
	//echo "splitDebAcc = ".$splitDebAccPost."<br>";
	$alertCompany = "N";
	$voyageNo = $_POST['slcVoyageNo'];
	
	$namaTableAcc = $CInvReg->detilMailInv($idMailInvGet, "company").substr($tglJurnalPost,6, 4).$tabelJurnal;
	//echo $tglJurnalPost." / ".$namaTableAcc." / ".$CInvReg->cekTableExist($namaTableAcc);
	if($CInvReg->cekTableExist($namaTableAcc) == "ada")
	{
		if($splitDebAccPost == "N") // JIKA SPLIT DEBIT ACCOUNT TIDAK DICENTANG
		{	
			$totalAddiPay = 0; // TOTAL ADDITIONAL YANG FIELD PAY DICENTANG
			for($b=1;$b<=5;$b++)
			{
				$cekPayPost = $_POST["cekPay".$b]; // ELEMENT JIKA SPLIT
				$addiAmountPay = str_replace(",","", $_POST["addiAmount".$b]);
				if($cekPayPost == "on")$totalAddiPay += $addiAmountPay; // JUMLAHKAN AMOUNT BERDASARKAN FIELD PAY YANG DICENTANG
			}
			//echo $echo.= "<tr><td>".$totalAddiPay."</td></tr>";
		}

		$CKoneksiInvReg->mysqlQuery("UPDATE mailinvoice SET tgljurnal='".$tglJurnalDB."', dueday='".$dueDayRetPost."', tglexp='".$dueDatePost ."', addi='".$totalAddiPay."', deduc='".$totalDeducPost."', reasondeduc='".$reasonDeducPost."', invret='".$invRetPost."', dateret='".$dateRetDB."', ignoreje='".$ignoreJePost."', apprpayment='".$apprPaymentPost."', vescode='".$vslCodePost."', vesname='".$vslName."', source='".$sourcePost."', debitacc='".$debitAccPost."', debitaccname='".$debitAccNamePost."', debitsplit='".$splitDebAccPost."', subcode='".$subCodePost."', kreditacc='".$kreditAccPost."', kreditaccname='".$kreditAccNamePost."',kreditsplit = '".$splitCreAccPost."', voucherje='".$voucherPost."', referenceje='".$referencePost."',  mailinvno='".$invNo."', description='".$descriptionPost."', saveinvret='Y', retby='".$userIdLogin."', updusrdt='".$userWhoActNew."', voyage_no='".$voyageNo."' WHERE idmailinv='".$idMailInvGet."' AND deletests=0", $CKoneksiInvReg->bukaKoneksi());
		
		$invDate = $CInvReg->detilMailInv($idMailInvGet, "tglinvoice");
		$invCurr = $CInvReg->detilMailInv($idMailInvGet, "currency");  
		$invAmt = $CInvReg->detilMailInv($idMailInvGet, "amount");  
		$acctCode = $debitAccPost;  
		$source = $sourcePost;
		
		$compcode = $CInvReg->detilMailInv($idMailInvGet, "company"); 
		$poNo = $CInvReg->detilMailInv($idMailInvGet, "barcode");  
		$workdesc = $CPublic->ms_escape_string( $CInvReg->detilMailInv($idMailInvGet, "description") );  
		$creditorNo = $kreditAccPost;  
		$credName = $kreditAccNamePost;  

		$credDays = $dueDayRetPost;
		
		$subCode = $subCodePost;  
		$entryDate = date("Y-m-d",strtotime($CPublic->tglServer()))." ".$CPublic->jamServer();
		$transBy = strtoupper( $userName );
		// SIMPAN HISTORY UNTUK UPDATE MAILINVOICE 
		$CHistory->updateLogInvReg($userIdLogin, "Simpan Invoice Returned (idmailinv = <b>".$idMailInvGet."</b>, Dueday = <b>".$dueDayRetPost."</b>, Tglexp = <b>".$dueDatePost ."</b>,  Additional = <b>".$totalAddiPost."</b>, Deduction = <b>".$totalDeducPost."</b>, Reasondeduc = <b>".$reasonDeducPost."</b>, Inv.Returned = <b>".$CPublic->nilaiCentang($invRetPost)."</b>, DateReturned = <b>".$CPublic->convTglNonDB($dateRetPost)."</b>, IgnoreJe = <b>".$CPublic->nilaiCentang($ignoreJePost)."</b>, ApprPayment = <b>".$CPublic->nilaiCentang($apprPaymentPost)."</b>, VslName = <b>".$CInvReg->detilVessel($vslCodePost, "Fullname")."</b>, Source = <b>".$sourcePost."</b>, DebitAcc = <b>".$debitAccPost ."</b>, DebitAccName = <b>".$debitAccNamePost."</b>, DebitAccSplit = <b>".$splitDebAccPost."</b>, SubCode = <b>".$subCodePost."</b>, KreditAcc = <b>".$kreditAccPost."</b>, KreditAccName = <b>".$kreditAccNamePost."</b>)");
								 
		if($ignoreJePost == 0) 
		{
			$CKoneksiInvReg->mysqlQuery("INSERT INTO pendingje (invdate, voucher, reference, invno, invcurr, invamt, addi, deduc, reasondeduc, acctcode, vslcode, source, compcode, pono, workdesc, creditorno, credname, creddays, subcode, entrydate, transby) VALUES ('".$invDate."', '".$voucherPost."', '".$referencePost."', '".$invNo."', '".$invCurr."', '".$invAmt."', '".$totalAddiPost."', '".$totalDeducPost."', '".$reasonDeducPost."', '".$acctCode."', '".$vslCodePost."', '".$source."', '".$compcode."', '".$poNo."', '".$workdesc."', '".$creditorNo."', '".$credName."', '".$credDays."', '".$subCode."', '".$entryDate."', '".$transBy."')", $CKoneksiInvReg->bukaKoneksi());
			// SIMPAN HISTORY UNTUK UPDATE PENDINGJE 
			$CHistory->updateLogInvReg($userIdLogin, "Simpan PendingJE Returned (Inv. Date = <b>".$invDate."</b>, Voucher = <b>".$voucherPost."</b>, Reference = <b>".$referencePost."</b>, Inv. Number = <b>".$invNo."</b>, Currency = <b>".$invCurr."</b>, Amount = <b>".$invAmt."</b>, Additional = <b>".$totalAddiPost."</b>, Deduction = <b>".$totalDeducPost."</b>, Reasondeduc Code = <b>".$reasonDeducPost."</b>, Account Code = <b>".$acctCode."</b>, Vessel Code = <b>".$vslCodePost."</b>, Source = <b>".$source."</b>, Company Code = <b>".$compcode."</b>, PO Number = <b>".$poNo ."</b>, Workdesc = <b>".$workdesc."</b>, Kreditor No = <b>".$creditorNo."</b>, Kreditor Name = <b>".$kreditAccNamePost."</b>, Credddays (Duedate) = <b>".$credDays."</b>, Subcode = <b>".$subCode."</b>, Entry Date = <b>".$entryDate."</b>, Trans By = <b>".$transBy."</b>)");
										 
			$ThnInvDate =  date("Y", strtotime($CInvReg->detilMailInv($idMailInvGet, "tglinvoice")));
			$ThnJurnalDate =  date("Y", strtotime($CInvReg->detilMailInv($idMailInvGet, "tgljurnal"))); //Tahun yang diambil = jurnal date, karena tahun ini yg merupakan update sesuai kebutuhan transfer. 

			// ##############################################################################################################################
			//--- Start -- Hapus Tes jika ingin upload dari AndhiPortalTes ke AndhikaPortal -------------------------------------------------
				$dbAccounting = $compcode.$ThnJurnalDate.$tabelJurnal;
			//--- End -- Hapus Tes jika ingin upload dari AndhiPortalTes ke AndhikaPortal ---------------------------------------------------
			// ##############################################################################################################################
		
			//echo "dbAccounting = ".$dbAccounting."<br>";
			$bookDate = $CInvReg->detilMailInv($idMailInvGet, "tgljurnal"); //tgl jurnal
			$entryDateMSSQL = $CPublic->tglServerWithStrip()." ".$CPublic->jamServer();
			$refNumber = $CInvReg->detilComp($compcode, "Jecode").date("m", strtotime($bookDate))."-".$CPublic->zerofill($referencePost, 5); //sourcebulan(diambil dari jurnal date)-reference

			$koneksiOdbcAcc->cekConnect();
			if($splitDebAccPost == "N") // JIKA SPLIT DEBIT ACCOUNT TIDAK DICENTANG
			{
				$subAcctNya = "";
				if($debitAccPost == "12300"){ $subAcctNya = $subCodePost; }
				// ############ ROW DEBIT
				$debitAmt = ($invAmt - $totalAddiPost);
				//$echo.= number_format((float)$debitAmt, 2, '.', '')."<br>";
				$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
						(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Entryuser, Progdebug, voyage) VALUES 
						('".$compcode."', '".$sourcePost."', '".$bookDate."', '".$voucherPost."', '".$refNumber."', '".substr($invNo, 0, 40)."', '".$poNo."', '".substr($CPublic->ms_escape_string( $_POST['description'] ), 0, 70)."', '".$vslCodePost."', '".$debitAccPost."', '".$subAcctNya."', '', '".$invCurr."', 'DB', '".number_format((float)$debitAmt, 2, '.', '')."', '".$invCurr."', '^', '".$entryDateMSSQL."', '".$userName."', 'Transfer JE:".$batchnoGet."','".$voyageNo."')");
						
				$CHistory->updateLogInvReg($userIdLogin, "Simpan Invoice Returned (DEBIT) (INSERT INTO ".$dbAccounting." (Company = <b>".$compcode."</b>, Source = <b>".$sourcePost."</b>, Bookdate = <b>".$bookDate."</b>, Voucher = <b>".$voucherPost."</b>, Refnumber = <b>".$refNumber."</b>, Invoiceno = <b>".substr($invNo, 0, 40)."</b>, Pono = <b>".$poNo."</b>, Bookdesc = <b>".substr($CPublic->ms_escape_string( $_POST['description'] ), 0, 70)."</b>, Vessel = <b>".$vslCodePost."</b>, Account = <b>".$debitAccPost."</b>, Subacct =, Subcode = <b>".$subAcctNya."</b>, Currcy = <b>".$invCurr."</b>, Booksts = <b>DB</b>, Amount = <b>".number_format((float)$debitAmt, 2, '.', '')."</b>, Diffcur = <b>".$invCurr."</b>, Codests = <b>'^'</b>, Entrydate = <b>".$entryDateMSSQL."</b>, Entryuser = <b>".$userName."</b>, Progdebug = <b>Transfer JE:".$batchnoGet."</b>)");
				
				for($i=1;$i<=5;$i++)
				{
					$addiAcc = $_POST["addiAcc".$i];
					$addiAmount = str_replace(",","", $_POST["addiAmount".$i]);
					$addiVslCode = $_POST["addiVslCode".$i];
					$addiReason = $_POST["addiReason".$i];
					$addiCekPay = $_POST["cekPay".$i]=="on" ? "Y" : "N";
					if($addiAcc != "")
					{
						$subAcctNya = "";
						if($addiAcc == "12300"){ $subAcctNya = $subCodePost; }

						$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
						(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Entryuser, Progdebug,voyage) VALUES 
						('".$compcode."', '".$sourcePost."', '".$bookDate."', '".$voucherPost."', '".$refNumber."', '".substr($invNo, 0, 40)."', '".$poNo."', '".substr($CPublic->ms_escape_string( $addiReason ), 0, 70)."', '".$addiVslCode."', '".$addiAcc."', '".$subAcctNya."', '', '".$invCurr."', 'DB', '".number_format((float)$addiAmount, 2, '.', '')."', '".$invCurr."', '^', '".$entryDateMSSQL."', '".$userName."', 'Transfer JE:".$batchnoGet."','".$voyageNo."')");
						
						$CKoneksiInvReg->mysqlQuery("INSERT INTO tblsplit (idmailinv, userid, fieldaksi, urutan, pay, booksts, account, amount, vslcode, description, addusrdt) VALUES ('".$idMailInvGet."', '".$userIdLogin."', 'additional', '".$i."', '".$addiCekPay."', 'DB', '".$addiAcc."', '".number_format((float)$addiAmount, 2, '.', '')."', '".$addiVslCode."', '".mysql_escape_string($addiReason)."', '".$userWhoActNew."')", $CKoneksiInvReg->bukaKoneksi());
						
						$CHistory->updateLogInvReg($userIdLogin, "Simpan Invoice Returned (DEBIT) (INSERT INTO ".$dbAccounting." (Company = <b>".$compcode."</b>, Source = <b>".$sourcePost."</b>, Bookdate = <b>".$bookDate."</b>, Voucher = <b>".$voucherPost."</b>, Refnumber = <b>".$refNumber."</b>, Invoiceno = <b>".substr($invNo, 0, 40)."</b>, Pono = <b>".$poNo."</b>, Bookdesc = <b>".substr($CPublic->ms_escape_string( $addiReason ), 0, 70)."</b>, Vessel = <b>".$addiVslCode."</b>, Account = <b>".$addiAcc."</b>, Subacct =, Subcode = <b>".$subCodePost."</b>, Currcy = <b>".$invCurr."</b>, Booksts = <b>'DB'</b>, Amount = <b>".number_format((float)$addiAmount, 2, '.', '')."</b>, Diffcur = <b>".$invCurr."</b>, Codests = <b>'^'</b>, Entrydate = <b>".$entryDateMSSQL."</b>, Entryuser = <b>".$userName."</b>, Progdebug = <b>Transfer JE:".$batchnoGet."</b>)");
						$CHistory->updateLogInvReg($userIdLogin, "Simpan Invoice Returned  (DEBIT) (INSERT INTO tblsplit (idmailinv = <b>".$idMailInvGet."</b>, userid = <b>".$userIdLogin."</b>, fieldaksi = <b>additional</b>, urutan = <b>".$i."</b>, pay = <b>".$addiCekPay."</b>, booksts = <b>DB</b>, account = <b>".$addiAcc."</b>, amount = <b>".number_format((float)$addiAmount, 2, '.', '')."</b>, vslcode = <b>".$addiVslCode."</b>, description = <b>".$addiReason."</b>, addusrdt = <b>".$userWhoActNew."</b>)");
					}
				}

				if($splitCreAccPost == "N")
				{
					$subAcctNya = "";
					if($kreditAccPost == "12300"){ $subAcctNya = $subCodePost; }
					// ############ ROW CREDIT
					$creditAmt = ($invAmt - $totalDeducPost);
					//$echo.= number_format((float)$totalDeducPost, 2, '.', '')."<br>";
					$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
							(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Entryuser, Progdebug,voyage) VALUES 
							('".$compcode."', '".$sourcePost."', '".$bookDate."', '".$voucherPost."', '".$refNumber."', '".substr($invNo, 0, 40)."', '".$poNo."', '".substr($CPublic->ms_escape_string( $_POST['description'] ), 0, 70)."', '".$vslCodePost."', '".$kreditAccPost."', '".$subAcctNya."', '', '".$invCurr."', 'CR', '".number_format((float)$creditAmt, 2, '.', '')."', '".$invCurr."', '^', '".$entryDateMSSQL."', '".$userName."', 'Transfer JE:".$batchnoGet."','".$voyageNo."')");
							
					$CHistory->updateLogInvReg($userIdLogin, "Simpan Invoice Returned (CREDIT) (INSERT INTO ".$dbAccounting." (Company = <b>".$compcode."</b>, Source = <b>".$sourcePost."</b>, Bookdate = <b>".$bookDate."</b>, Voucher = <b>".$voucherPost."</b>, Refnumber = <b>".$refNumber."</b>, Invoiceno = <b>".substr($invNo, 0, 40)."</b>, Pono = <b>".$poNo."</b>, Bookdesc = <b>".substr($CPublic->ms_escape_string( $_POST['description'] ), 0, 70)."</b>, Vessel = <b>".$vslCodePost."</b>, Account = <b>".$kreditAccPost."</b>, Subacct =, Subcode = <b>".$subCodePost."</b>, Currcy = <b>".$invCurr."</b>, Booksts = <b>CR</b>, Amount = <b>".number_format((float)$creditAmt, 2, '.', '')."</b>, Diffcur = <b>".$invCurr."</b>, Codests = <b>'^'</b>, Entrydate = <b>".$entryDateMSSQL."</b>, Entryuser = <b>".$userName."</b>, Progdebug = <b>Transfer JE:".$batchnoGet."</b>)");
				}
				for($i=1;$i<=5;$i++)
				{
					$deducAcc = $_POST["deducAcc".$i];
					$deducAmount = str_replace(",","", $_POST["deducAmount".$i]);
					$deducVslCode = $_POST["deducVslCode".$i];
					$deducReason = $_POST["deducReason".$i];
					if($deducAcc != "")
					{
						$subAcctNya = "";
						if($deducAcc == "12300"){ $subAcctNya = $subCodePost; }

						$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
						(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Entryuser, Progdebug, voyage) VALUES 
						('".$compcode."', '".$sourcePost."', '".$bookDate."', '".$voucherPost."', '".$refNumber."', '".substr($invNo, 0, 40)."', '".$poNo."', '".substr($CPublic->ms_escape_string( $deducReason ), 0, 70)."', '".$deducVslCode."', '".$deducAcc."', '".$subAcctNya."', '', '".$invCurr."', 'CR', '".number_format((float)$deducAmount, 2, '.', '')."', '".$invCurr."', '^', '".$entryDateMSSQL."', '".$userName."', 'Transfer JE:".$batchnoGet."','".$voyageNo."')");
						
						$CKoneksiInvReg->mysqlQuery("INSERT INTO tblsplit (idmailinv, userid, fieldaksi, urutan, booksts, account, amount, vslcode, description, addusrdt) VALUES ('".$idMailInvGet."', '".$userIdLogin."', 'deduction', '".$i."', 'CR', '".$deducAcc."', '".number_format((float)$deducAmount, 2, '.', '')."', '".$deducVslCode."', '".mysql_escape_string($deducReason)."', '".$userWhoActNew."')", $CKoneksiInvReg->bukaKoneksi());
						
						$CHistory->updateLogInvReg($userIdLogin, "Simpan Invoice Returned (CREDIT) (INSERT INTO ".$dbAccounting." (Company = <b>".$compcode."</b>, Source = <b>".$sourcePost."</b>, Bookdate = <b>".$bookDate."</b>, Voucher = <b>".$voucherPost."</b>, Refnumber = <b>".$refNumber."</b>, Invoiceno = <b>".substr($invNo, 0, 40)."</b>, Pono = <b>".$poNo."</b>, Bookdesc = <b>".substr($CPublic->ms_escape_string( $deducReason ), 0, 70)."</b>, Vessel = <b>".$deducVslCode."</b>, Account = <b>".$deducAcc."</b>, Subacct =, Subcode = <b>".$subCodePost."</b>, Currcy = <b>".$invCurr."</b>, Booksts = <b>CR</b>, Amount = <b>".number_format((float)$deducAmount, 2, '.', '')."</b>, Diffcur = <b>".$invCurr."</b>, Codests = <b>'^'</b>, Entrydate = <b>".$entryDateMSSQL."</b>, Entryuser = <b>".$userName."</b>, Progdebug = <b>Transfer JE:".$batchnoGet."</b>)");
						$CHistory->updateLogInvReg($userIdLogin, "Simpan Invoice Returned  (CREDIT) (INSERT INTO tblsplit (idmailinv = <b>".$idMailInvGet."</b>, userid = <b>".$userIdLogin."</b>, fieldaksi = <b>deduction</b>, urutan = <b>".$i."</b>, booksts = <b>CR</b>, account = <b>".$deducAcc."</b>, amount = <b>".number_format((float)$deducAmount, 2, '.', '')."</b>, vslcode = <b>".$deducVslCode."</b>, description = <b>".$deducReason."</b>, addusrdt = <b>".$userWhoActNew."</b>)");
					}
				}
			}
			if($splitDebAccPost == "Y")// JIKA SPLIT DEBIT ACCOUNT DICENTANG
			{
				// ############ ROW DEBIT
				$querySplit = $CKoneksiInvReg->mysqlQuery("SELECT * FROM tblsplittemp WHERE idmailinv='".$idMailInvGet."' AND userid=".$userIdLogin." AND fieldaksi='memodebit' ORDER BY urutan;",$CKoneksiInvReg->bukaKoneksi());
				while($rowSplit = $CKoneksiInvReg->mysqlFetch($querySplit))
				{
					$subAcctNya = "";
					if($rowSplit['account'] == "12300"){ $subAcctNya = $subCodePost; }

					$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
					(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Entryuser, Progdebug, voyage) VALUES 
					('".$compcode."', '".$sourcePost."', '".$bookDate."', '".$voucherPost."', '".$refNumber."', '".substr($invNo, 0, 40)."', '".$poNo."', '".substr($CPublic->ms_escape_string( $rowSplit['description'] ), 0, 70)."', '".$rowSplit['vslcode']."', '".$rowSplit['account']."', '".$subAcctNya."', '', '".$invCurr."', 'DB', '".number_format((float)$rowSplit['amount'], 2, '.', '')."', '".$invCurr."', '^', '".$entryDateMSSQL."', '".$userName."', 'Transfer JE:".$batchnoGet."','".$voyageNo."')");
					
					$CKoneksiInvReg->mysqlQuery("INSERT INTO tblsplit (idmailinv, userid, fieldaksi, urutan, pay, booksts, account, amount, vslcode, description, addusrdt) VALUES ('".$idMailInvGet."', '".$userIdLogin."', 'memodebit', '".$rowSplit['urutan']."', '".$rowSplit['pay']."', 'DB', '".$rowSplit['account']."', '".number_format((float)$rowSplit['amount'], 2, '.', '')."', '".$rowSplit['vslcode']."', '".substr($CPublic->ms_escape_string( $rowSplit['description'] ), 0, 70)."', '".$userWhoActNew."')", $CKoneksiInvReg->bukaKoneksi());
					
					$CHistory->updateLogInvReg($userIdLogin, "Simpan Invoice Returned (DEBIT) (INSERT INTO ".$dbAccounting." (Company = <b>".$compcode."</b>, Source = <b>".$sourcePost."</b>, Bookdate = <b>".$bookDate."</b>, Voucher = <b>".$voucherPost."</b>, Refnumber = <b>".$refNumber."</b>, Invoiceno = <b>".substr($invNo, 0, 40)."</b>, Pono = <b>".$poNo."</b>, Bookdesc = <b>".substr($CPublic->ms_escape_string( $rowSplit['description'] ), 0, 70)."</b>, Vessel = <b>".$vslCodePost."</b>, Account = <b>".$rowSplit['account']."</b>, Subacct =, Subcode = <b>".$subCodePost."</b>, Currcy = <b>".$invCurr."</b>, Booksts = <b>DB</b>, Amount = <b>".number_format((float)$rowSplit['amount'], 2, '.', '')."</b>, Diffcur = <b>".$invCurr."</b>, Codests = <b>'^'</b>, Entrydate = <b>".$entryDateMSSQL."</b>, Entryuser = <b>".$userName."</b>, Progdebug = <b>Transfer JE:".$batchnoGet."</b>)");
					$CHistory->updateLogInvReg($userIdLogin, "Simpan Invoice Returned  (DEBIT) (INSERT INTO tblsplit (idmailinv = <b>".$idMailInvGet."</b>, userid = <b>".$userIdLogin."</b>, fieldaksi = <b>memodebit</b>, urutan = <b>".$rowSplit['urutan']."</b>, pay = <b>".$rowSplit['pay']."</b>, booksts = <b>DB</b>, account = <b>".$rowSplit['account']."</b>, amount = <b>".number_format((float)$rowSplit['amount'], 2, '.', '')."</b>, vslcode = <b>".$rowSplit['vslcode']."</b>, description = <b>".substr($CPublic->ms_escape_string( $rowSplit['description'] ), 0, 70)."</b>, addusrdt = <b>".$userWhoActNew."</b>)");
				}
				
				for($i=1;$i<=5;$i++)
				{
					$addiAcc = $_POST["addiAcc".$i];
					$addiAmount = str_replace(",","", $_POST["addiAmount".$i]);
					$addiVslCode = $_POST["addiVslCode".$i];
					$addiReason = $_POST["addiReason".$i];
					$addiCekPay = $_POST['cekPay']=="on" ? "Y" : "N";
					if($addiAcc != "")
					{
						$subAcctNya = "";
						if($addiAcc == "12300"){ $subAcctNya = $subCodePost; }

						$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
						(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Entryuser, Progdebug, voyage) VALUES 
						('".$compcode."', '".$sourcePost."', '".$bookDate."', '".$voucherPost."', '".$refNumber."', '".substr($invNo, 0, 40)."', '".$poNo."', '".substr($CPublic->ms_escape_string( $addiReason ), 0, 70)."', '".$addiVslCode."', '".$addiAcc."', '".$subAcctNya."', '', '".$invCurr."', 'DB', '".number_format((float)$addiAmount, 2, '.', '')."', '".$invCurr."', '^', '".$entryDateMSSQL."', '".$userName."', 'Transfer JE:".$batchnoGet."','".$voyageNo."')");
						
						$CKoneksiInvReg->mysqlQuery("INSERT INTO tblsplit (idmailinv, userid, fieldaksi, urutan, pay, booksts, account, amount, vslcode, description, addusrdt) VALUES ('".$idMailInvGet."', '".$userIdLogin."', 'additional', '".$i."', '".$addiCekPay."', 'DB', '".$addiAcc."', '".number_format((float)$addiAmount, 2, '.', '')."', '".$addiVslCode."', '".mysql_escape_string($addiReason)."', '".$userWhoActNew."')", $CKoneksiInvReg->bukaKoneksi());
						
						$CHistory->updateLogInvReg($userIdLogin, "Simpan Invoice Returned (DEBIT) (INSERT INTO ".$dbAccounting." (Company = <b>".$compcode."</b>, Source = <b>".$sourcePost."</b>, Bookdate = <b>".$bookDate."</b>, Voucher = <b>".$voucherPost."</b>, Refnumber = <b>".$refNumber."</b>, Invoiceno = <b>".substr($invNo, 0, 40)."</b>, Pono = <b>".$poNo."</b>, Bookdesc = <b>".substr($CPublic->ms_escape_string( $addiReason ), 0, 70)."</b>, Vessel = <b>".$addiVslCode."</b>, Account = <b>".$addiAcc."</b>, Subacct =, Subcode = <b>".$subCodePost."</b>, Currcy = <b>".$invCurr."</b>, Booksts = <b>DB</b>, Amount = <b>".number_format((float)$addiAmount, 2, '.', '')."</b>, Diffcur = <b>".$invCurr."</b>, Codests = <b>'^'</b>, Entrydate = <b>".$entryDateMSSQL."</b>, Entryuser = <b>".$userName."</b>, Progdebug = <b>Transfer JE:".$batchnoGet."</b>)");
						$CHistory->updateLogInvReg($userIdLogin, "Simpan Invoice Returned  (DEBIT) (INSERT INTO tblsplit (idmailinv = <b>".$idMailInvGet."</b>, userid = <b>".$userIdLogin."</b>, fieldaksi = <b>additional</b>, urutan = <b>".$i."</b>, pay = <b>".$addiCekPay."</b>, booksts = <b>DB</b>, account = <b>".$addiAcc."</b>, amount = <b>".number_format((float)$addiAmount, 2, '.', '')."</b>, vslcode = <b>".$addiVslCode."</b>, description = <b>".$addiReason."</b>, addusrdt = <b>".$userWhoActNew."</b>)");
					}
				}

				if($splitCreAccPost == "N")
				{
					$subAcctNya = "";
					if($kreditAccPost == "12300"){ $subAcctNya = $subCodePost; }

					// ############ ROW CREDIT
					$creditAmt = ($invAmt-$totalDeducPost);
					$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
							(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Entryuser, Progdebug,voyage) VALUES 
							('".$compcode."', '".$sourcePost."', '".$bookDate."', '".$voucherPost."', '".$refNumber."', '".substr($invNo, 0, 40)."', '".$poNo."', '".substr($CPublic->ms_escape_string( $_POST['description'] ), 0, 70)."', '".$vslCodePost."', '".$kreditAccPost."', '".$subAcctNya."', '', '".$invCurr."', 'CR', '".number_format((float)$creditAmt, 2, '.', '')."', '".$invCurr."', '^', '".$entryDateMSSQL."', '".$userName."', 'Transfer JE:".$batchnoGet."','".$voyageNo."')");
					
					$CHistory->updateLogInvReg($userIdLogin, "Simpan Invoice Returned (CREDIT) (INSERT INTO ".$dbAccounting." (Company = <b>".$compcode."</b>, Source = <b>".$sourcePost."</b>, Bookdate = <b>".$bookDate."</b>, Voucher = <b>".$voucherPost."</b>, Refnumber = <b>".$refNumber."</b>, Invoiceno = <b>".substr($invNo, 0, 40)."</b>, Pono = <b>".$poNo."</b>, Bookdesc = <b>".substr($CPublic->ms_escape_string( $_POST['description'] ), 0, 70)."</b>, Vessel = <b>".$vslCodePost."</b>, Account = <b>".$kreditAccPost."</b>, Subacct =, Subcode = <b>".$subCodePost."</b>, Currcy = <b>".$invCurr."</b>, Booksts = <b>CR</b>, Amount = <b>".number_format((float)$creditAmt, 2, '.', '')."</b>, Diffcur = <b>".$invCurr."</b>, Codests = <b>'^'</b>, Entrydate = <b>".$entryDateMSSQL."</b>, Entryuser = <b>".$userName."</b>, Progdebug = <b>Transfer JE:".$batchnoGet."</b>)");
				}
						
				for($i=1;$i<=5;$i++)
				{
					$deducAcc = $_POST["deducAcc".$i];
					$deducAmount = str_replace(",","", $_POST["deducAmount".$i]);
					$deducVslCode = $_POST["deducVslCode".$i];
					$deducReason = $_POST["deducReason".$i];
					if($deducAcc != "")
					{
						$subAcctNya = "";
						if($deducAcc == "12300"){ $subAcctNya = $subCodePost; }

						$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
						(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Entryuser, Progdebug,voyage) VALUES 
						('".$compcode."', '".$sourcePost."', '".$bookDate."', '".$voucherPost."', '".$refNumber."', '".substr($invNo, 0, 40)."', '".$poNo."', '".substr($CPublic->ms_escape_string( $deducReason ), 0, 70)."', '".$deducVslCode."', '".$deducAcc."', '".$subAcctNya."', '', '".$invCurr."', 'CR', '".number_format((float)$deducAmount, 2, '.', '')."', '".$invCurr."', '^', '".$entryDateMSSQL."', '".$userName."', 'Transfer JE:".$batchnoGet."','".$voyageNo."')");
							
						$CKoneksiInvReg->mysqlQuery("INSERT INTO tblsplit (idmailinv, userid, fieldaksi, urutan, booksts, account, amount, vslcode, description, addusrdt) VALUES ('".$idMailInvGet."', '".$userIdLogin."', 'deduction', '".$i."', 'CR', '".$deducAcc."', '".number_format((float)$deducAmount, 2, '.', '')."', '".$deducVslCode."', '".mysql_escape_string($deducReason)."', '".$userWhoActNew."')", $CKoneksiInvReg->bukaKoneksi());
									
						$CHistory->updateLogInvReg($userIdLogin, "Simpan Invoice Returned (CREDIT) (INSERT INTO ".$dbAccounting." (Company = <b>".$compcode."</b>, Source = <b>".$sourcePost."</b>, Bookdate = <b>".$bookDate."</b>, Voucher = <b>".$voucherPost."</b>, Refnumber = <b>".$refNumber."</b>, Invoiceno = <b>".substr($invNo, 0, 40)."</b>, Pono = <b>".$poNo."</b>, Bookdesc = <b>".substr($CPublic->ms_escape_string( $deducReason ), 0, 70)."</b>, Vessel = <b>".$vslCodePost."</b>, Account = <b>".$deducAcc."</b>, Subacct =, Subcode = <b>".$subCodePost."</b>, Currcy = <b>".$invCurr."</b>, Booksts = <b>CR</b>, Amount = <b>".number_format((float)$deducAmount, 2, '.', '')."</b>, Diffcur = <b>".$invCurr."</b>, Codests = <b>'^'</b>, Entrydate = <b>".$entryDateMSSQL."</b>, Entryuser = <b>".$userName."</b>, Progdebug = <b>Transfer JE:".$batchnoGet."</b>)");
						$CHistory->updateLogInvReg($userIdLogin, "Simpan Invoice Returned  (CREDIT) (INSERT INTO tblsplit (idmailinv = <b>".$idMailInvGet."</b>, userid = <b>".$userIdLogin."</b>, fieldaksi = <b>deduction</b>, urutan = <b>".$i."</b>, booksts = <b>CR</b>, account = <b>".$deducAcc."</b>, amount = <b>".number_format((float)$deducAmount, 2, '.', '')."</b>, vslcode = <b>".$deducVslCode."</b>, description = <b>".$deducReason."</b>, addusrdt = <b>".$userWhoActNew."</b>)");
					}
				}
			}
			if($splitCreAccPost == "Y")
			{
				$querySplitCre = $CKoneksiInvReg->mysqlQuery("SELECT * FROM tblsplittemp WHERE idmailinv='".$idMailInvGet."' AND userid=".$userIdLogin." AND fieldaksi='memocredit' ORDER BY urutan;",$CKoneksiInvReg->bukaKoneksi());

				while($rowSplitCre = $CKoneksiInvReg->mysqlFetch($querySplitCre))
				{
					$subAcctNya = "";
					if($rowSplitCre['account'] == "12300"){ $subAcctNya = $subCodePost; }

					$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
					(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Entryuser, Progdebug,voyage) VALUES 
					('".$compcode."', '".$sourcePost."', '".$bookDate."', '".$voucherPost."', '".$refNumber."', '".substr($invNo, 0, 40)."', '".$poNo."', '".substr($CPublic->ms_escape_string( $rowSplitCre['description'] ), 0, 70)."', '".$rowSplitCre['vslcode']."', '".$rowSplitCre['account']."', '".$subAcctNya."', '', '".$invCurr."', 'CR', '".number_format((float)$rowSplitCre['amount'], 2, '.', '')."', '".$invCurr."', '^', '".$entryDateMSSQL."', '".$userName."', 'Transfer JE:".$batchnoGet."','".$voyageNo."')");

					$CKoneksiInvReg->mysqlQuery("INSERT INTO tblsplit (idmailinv, userid, fieldaksi, urutan, pay, booksts, account, amount, vslcode, description, addusrdt) VALUES ('".$idMailInvGet."', '".$userIdLogin."', 'memocredit', '".$rowSplitCre['urutan']."', '".$rowSplitCre['pay']."', 'CR', '".$rowSplitCre['account']."', '".number_format((float)$rowSplitCre['amount'], 2, '.', '')."', '".$rowSplitCre['vslcode']."', '".substr($CPublic->ms_escape_string( $rowSplitCre['description'] ), 0, 70)."', '".$userWhoActNew."')", $CKoneksiInvReg->bukaKoneksi());

					// $CHistory->updateLogInvReg($userIdLogin, "Simpan Invoice Returned  (CREDIT) (INSERT INTO tblsplit (idmailinv = <b>".$idMailInvGet."</b>, userid = <b>".$userIdLogin."</b>, fieldaksi = <b>memokredit</b>, urutan = <b>".$rowSplitCre['urutan']."</b>, pay = <b>".$rowSplitCre['pay']."</b>, booksts = <b>DB</b>, account = <b>".$rowSplitCre['account']."</b>, amount = <b>".number_format((float)$rowSplitCre['amount'], 2, '.', '')."</b>, vslcode = <b>".$rowSplitCre['vslcode']."</b>, description = <b>".substr($CPublic->ms_escape_string( $rowSplitCre['description'] ), 0, 70)."</b>, addusrdt = <b>".$userWhoActNew."</b>)");
				}
			}
		}	

		// $CKoneksiInvReg->mysqlQuery("UPDATE mailinvoice SET tgljurnal='".$tglJurnalDB."', dueday='".$dueDayRetPost."', tglexp='".$dueDatePost ."', addi='".$totalAddiPay."', deduc='".$totalDeducPost."', reasondeduc='".$reasonDeducPost."', invret='".$invRetPost."', dateret='".$dateRetDB."', ignoreje='".$ignoreJePost."', apprpayment='".$apprPaymentPost."', vescode='".$vslCodePost."', vesname='".$vslName."', source='".$sourcePost."', debitacc='".$debitAccPost."', debitaccname='".$debitAccNamePost."', debitsplit='".$splitDebAccPost."', subcode='".$subCodePost."', kreditacc='".$kreditAccPost."', kreditaccname='".$kreditAccNamePost."',kreditsplit = '".$splitCreAccPost."', voucherje='".$voucherPost."', referenceje='".$referencePost."',  mailinvno='".$invNo."', description='".$descriptionPost."', saveinvret='Y', retby='".$userIdLogin."', updusrdt='".$userWhoActNew."' WHERE idmailinv='".$idMailInvGet."' AND deletests=0", $CKoneksiInvReg->bukaKoneksi());

		$CKoneksiInvReg->mysqlQuery("DELETE FROM tblsplittemp WHERE idmailinv='".$idMailInvGet."' AND userid='".$userIdLogin."';", $CKoneksiInvReg->bukaKoneksi());

		$tutupWindow = "yes";
	}
	else if($CInvReg->cekTableExist($namaTableAcc) == "tidak")
	{
		$alertCompany = "Y";
	} 
}

$SNo = $CInvReg->detilMailInv($idMailInvGet, "urutan");
$company = $CInvReg->detilMailInv($idMailInvGet, "companyname");
$unitt = $CInvReg->detilMailInv($idMailInvGet, "unitname");
$barcode = $CInvReg->detilMailInv($idMailInvGet, "barcode");
$invoiceDate = $CPublic->convTglNonDB( $CInvReg->detilMailInv($idMailInvGet, "tglinvoice") );
$currency  = $CInvReg->detilMailInv($idMailInvGet, "currency");
$amount = $CInvReg->detilMailInv($idMailInvGet, "amount") != "0.00" ? number_format($CInvReg->detilMailInv($idMailInvGet, "amount"), 2, ".", ",") : "";
$remark = $CInvReg->detilMailInv($idMailInvGet, "remark");
$compcodeNya = $CInvReg->detilMailInv($idMailInvGet, "company");

$receivedate = $CPublic->convTglNonDB( $CInvReg->detilMailInv($idMailInvGet, "receivedate") );
	
if($CInvReg->detilMailInv($idMailInvGet, "revisiret") == "N")
{
	$dateReturned = $CPublic->convTglNonDB( $CPublic->waktuSek() );
	$checkIgnoreJE = ""; 
	$checkApprPayment = "checked"; 
	$vesselName = $CInvReg->menuVessel("");
	$source = "MMN"; 
	$classBtnViewSplitDeb = "btnStandarDis";
	$classBtnViewSplitCre = "btnStandarDis";
	$disBtnViewSplitDeb = "disabled";
	$description = $remark = substr($CInvReg->detilMailInv($idMailInvGet, "remark"),0,70); // SEBELUM DI TRANSFER JE NILAI DESCRIPTION SECARA DEFAULT ADALAH SAMA DENGAN REMARKS
	$journalDate = $invoiceDate;
	$dueDay = $CInvReg->detilMailInv($idMailInvGet, "dueday");
	$dueDate = "";
	if($CInvReg->detilMailInv($idMailInvGet, "tglexp") != "0000-00-00")
		$dueDate = $CPublic->convTglNonDB( $CInvReg->detilMailInv($idMailInvGet, "tglexp") );
	$dueDayFromNow = $CPublic->perbedaanHari( "'".$CInvReg->detilMailInv($idMailInvGet, "tglexp")."'" , "NOW()") ;
	$mailInvNo = $CInvReg->detilMailInv($idMailInvGet, "mailinvno");
}
if($CInvReg->detilMailInv($idMailInvGet, "revisiret") == "Y")
{
	$dateReturned = $CPublic->convTglNonDB( $CInvReg->detilMailInv($idMailInvGet, "dateret") );
	$ignoreJE = $CInvReg->detilMailInv($idMailInvGet, "ignoreje");
	$checkIgnoreJE = $ignoreJE=="1" ? "checked" : ""; 
	$apprPayment = $CInvReg->detilMailInv($idMailInvGet, "apprpayment");
	$checkApprPayment = $apprPayment=="1" ? "checked" : ""; 
	$vesselName = $CInvReg->menuVessel($CInvReg->detilMailInv($idMailInvGet, "vescode"));
	$source = $CInvReg->detilMailInv($idMailInvGet, "source");
	$debitAcc = $CInvReg->detilMailInv($idMailInvGet, "debitacc");
	$debitAccName = $CInvReg->detilMailInv($idMailInvGet, "debitaccname");
	$debitSplit = $CInvReg->detilMailInv($idMailInvGet, "debitsplit");
	$checkDebSplit = $debitSplit=="Y" ? "checked" : ""; // JIKA DEBITSPLIT = Y MAKA SPLIT ACCOUNT DI CHECKED
	$classBtnViewSplitDeb = $debitSplit=="Y" ? "btnStandar" : "btnStandarDis";
	$disBtnViewSplitDeb = $debitSplit=="Y" ? "" : "disabled";
	$subCode = $CInvReg->detilMailInv($idMailInvGet, "subcode");
	$description = $remark = substr($CInvReg->detilMailInv($idMailInvGet, "remark"),0,70); // SEBELUM DI TRANSFER JE NILAI DESCRIPTION SECARA DEFAULT ADALAH SAMA DENGAN REMARKS
	$kreditAcc = $CInvReg->detilMailInv($idMailInvGet, "kreditacc");
	$debitAccName = $CInvReg->detilMailInv($idMailInvGet, "kreditaccname");
	$creditSplit = $CInvReg->detilMailInv($idMailInvGet, "kreditsplit");
	$checkCreSplit = $creditSplit=="Y" ? "checked" : ""; // JIKA DEBITSPLIT = Y MAKA SPLIT ACCOUNT DI CHECKED
	$classBtnViewSplitCre = $creditSplit=="Y" ? "btnStandar" : "btnStandarDis";
	$disBtnViewSplitCre = $creditSplit=="Y" ? "" : "disabled";

	$voucher = $CInvReg->detilMailInv($idMailInvGet, "voucherje");
	$reference = $CInvReg->detilMailInv($idMailInvGet, "referenceje");
	$journalDate = $CPublic->convTglNonDB( $CInvReg->detilMailInv($idMailInvGet, "tgljurnal") );
	$dueDay = $CInvReg->detilMailInv($idMailInvGet, "dueday");
	$dueDate = $CPublic->convTglNonDB( $CInvReg->detilMailInv($idMailInvGet, "tglexp") );
	$dueDayFromNow = $CPublic->perbedaanHari( "'".$CInvReg->detilMailInv($idMailInvGet, "tglexp")."'" , "NOW()") ;
	$voucher = $CInvReg->detilMailInv($idMailInvGet, "voucherje");
	$mailInvNo = $CInvReg->detilMailInv($idMailInvGet, "mailinvno");
}
?>
<style>
body {
    background-color: #f9f9f9;
}
</style>

<div id="loaderImg" style="visibility:visible;" class="pleaseWait2">
    <div class="isiPleaseWait tabelBorderAll">&nbsp;&nbsp;Please Wait...&nbsp;<img src="../picture/loading (115).gif"
            height="12" />&nbsp;</div>
</div>

<?php
$display = "display:none;";
if($echo != "")
{
	//$display = "display:inline;";
}
?>
<div
    style="position:absolute;top:9px;left:15px;border:solid 1px #666;background-color:#FFF;z-index:4;<?php echo $display; ?>">
    <table style="font:11px tahoma;">
        <?php echo $echo; ?>
    </table>

</div>

<div id="idHalUbahProcessRet" style="position:absolute;top:9px;left:15px;border:solid 0px #666;z-index:auto;">
    <table cellpadding="0" cellspacing="0" width="930" height="545"
        style="font:0.8em sans-serif;font-weight:bold;color:#485a88;" border="0" align="center" class="">
        <form method="post"
            action="halDetailProcessRet.php?batchno=<?php echo $batchnoGet; ?>&idMailInv=<?php echo $idMailInvGet; ?>"
            enctype="multipart/form-data" id="formUbahProcessRet" name="formUbahProcessRet">
            <input type="hidden" id="aksi" name="aksi" value="simpanEditInvRet">
            <input type="hidden" id="idMailInv" name="idMailInv" value="<?php echo $idMailInvGet; ?>">
            <div id="idCekKreditAcc"><input type="hidden" id="kreditAccAdaTidak"></div>
            <div id="idCekDebitAcc"><input type="hidden" id="debitAccAdaTidak"></div>
            <input type="hidden" id="returnYesNo" value="no" size="10">
            <input type="hidden" id="invoiceDate" name="invoiceDate" value="<?php echo $receivedate; ?>">
            <input type="hidden" id="dueDate" name="dueDate" value="<?php echo $dueDate; ?>">
            <tr>
                <td colspan="2" align="center">
                    <span
                        style="font-size:15px;font-family:sans-serif;font-weight:bold;color:#485a88;text-decoration:underline;">TRANSFER
                        JURNAL ENTRY</span> <span id="tess"></span>
                </td>
            </tr>
            <tr>
                <td height="5"></td>
            </tr>
            <tr>
                <td width="50%" valign="top">
                    <table cellpadding="0" cellspacing="0" width="435" class="tabelBorderTopJust"
                        style="border-style:dotted;">
                        <tr>
                            <td width="130" height="22" class="">Batchno</td>
                            <td width="303" class="elementTeks"><?php echo $batchnoGet; ?></td>
                        </tr>
                        <tr>
                            <td height="22" class="">SNo</td>
                            <td class="elementTeks" style="text-decoration:underline;"><?php echo $SNo; ?></td>
                        </tr>
                        <tr>
                            <td height="22" class="">Date Returned</td>
                            <td class="">
                                <input type="text" name="dateReturned" id="dateReturned" class="elementInput3"
                                    style="width:65px;" value="<?php echo $dateReturned; ?>" />
                                <img src="../../picture/calendar.gif" class="gayaKalender" title="Select Date"
                                    onclick="displayCalendar(document.getElementById('dateReturned'),'dd/mm/yyyy',this, '', '', '193', '183');" />&nbsp;<span
                                    class="spanKalender">(DD/MM/YYYY)</span>
                            </td>
                        </tr>
                        <tr>
                            <td height="22" class="">Ignored JE</td>
                            <td class=""><input type="checkbox" id="ignoredJe" name="ignoredJe" style="cursor:pointer;"
                                    <?php echo $checkIgnoreJE; ?>>&nbsp;<span id="ignoredJeNo"
                                    style="position:relative;bottom:3px;" class="elementTeks">NO</span></td>
                        </tr>
                        <tr>
                            <td height="22" class="">Approve Payment</td>
                            <td class=""><input type="checkbox" id="approvePayment" name="approvePayment"
                                    style="cursor:pointer;" <?php echo $checkApprPayment; ?>>&nbsp;<span
                                    id="approvePaymentNo" style="position:relative;bottom:3px;"
                                    class="elementTeks">YES</span></td>
                        </tr>
                        <tr>
                            <td height="22" class="">Vessel Name</td>
                            <td class="">
                                <select id="vesselName" name="vesselName" class="elementMenu" onchange="getVoyage();"
                                    style="width:200px;<?php echo $bgColor; ?>" <?php echo $dis; ?>>
                                    <option value=""></option>
                                    <?php echo $vesselName; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td height="22" class="">Voyage No</td>
                            <td class="">
                                <select id="slcVoyageNo" name="slcVoyageNo" class="elementMenu"
                                    style="width:200px;<?php echo $bgColor; ?>" <?php echo $dis; ?>>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td height="22" class="">Source</td>
                            <td class="">
                                <input type="text" name="source" id="source" class="elementInput3" style="width:60px;"
                                    maxlength="3" value="<?php echo $source; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td height="22">Debit Account</td>
                            <td valign="middle">
                                <input type="text" name="debitAcc" id="debitAcc" class="elementInput3"
                                    style="width:60px;" value="<?php echo $debitAcc; ?>" maxlength="5" />
                                <span id="spanDebitAccName" class="elementTeks"
                                    style="font-family:'Arial Narrow';"><?php echo $debitAccName; ?>&nbsp;</span>
                            </td>
                        </tr>
                        <tr>
                            <td height="22" class="">&nbsp;&nbsp;&nbsp;List Account</td>
                            <td class="elementTeks" valign="top">
                                <div style="position:relative;float:left;width:27px;">
                                    <button type="button" class="btnStandar" id="btnCariDebAcc" title=""
                                        onclick="listLoad1();klikBtnCariDebAcc();">
                                        <table cellpadding="0" cellspacing="0" width="22" height="19">
                                            <tr>
                                                <td align="center" valign="bottom"><img src="../picture/magnifier.png"
                                                        height="14" /></td>
                                            </tr>
                                        </table>
                                    </button>
                                </div>
                                <div id="listLoading1"
                                    style="position:relative;float:left;margin-top:3px;display:none;"><img
                                        src="../../picture/ajax-loader7.gif" height="14" /></div>
                                <div id="hasilDebitAcc" style="display:none;">
                                    <select id="menuDebitAcc" class="elementMenu" style="width:290px;height:22px;"
                                        onchange="pilihMenuDebitAcc(this.value);">
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td height="22" class="">&nbsp;&nbsp;&nbsp;Split Account</td>
                            <td style="position:inherit;">
                                <input type="checkbox" id="splitDebAcc" name="splitDebAcc" style="cursor:pointer;" <?PHP
                                    echo $checkDebSplit; ?>>
                                <span id="splitDebAccNo" style="position:relative;bottom:3px;"
                                    class="elementTeks">NO</span>
                                <button type="button" class="<?php echo $classBtnViewSplitDeb; ?>" id="btnViewSplitDeb"
                                    title="SPLIT DEBIT ACCOUNT"
                                    style="position:absolute;left:180px;width:62px;height:20px;"
                                    onclick="klikBtnViewDeb();" <?php echo $disBtnViewSplitDeb; ?>>
                                    <table cellpadding="0" cellspacing="0" width="100%" height="100%">
                                        <tr>
                                            <td align="center" width="22"><img src="../picture/eye--exclamation.png" />
                                            </td>
                                            <td align="center">VIEW</td>
                                        </tr>
                                    </table>
                                </button>
                            </td>
                        </tr>
                        <tr valign="top">
                            <td height="44" class="">Description</td>
                            <td class="">
                                <textarea id="description" name="description" class="elementInput" cols="45"
                                    style="height:37px;"
                                    onkeyup="textCounter(this, sisaDesc, 70);"><?php echo $description; ?></textarea>
                                <input disabled="disabled" readonly type="text" name="sisaDesc" value="70"
                                    style="width:23px">
                            </td>
                        </tr>
                        <tr>
                            <td height="22" class="">Credit Account</td>
                            <td class="elementTeks">
                                <span id="senderVendorCode2"><input type="text" name="kreditAcc" id="kreditAcc"
                                        class="elementInput3" style="width:60px;" maxlength="5"
                                        value="<?php echo $kreditAcc; ?>" /></span>
                                <span id="spanKreditAccName"
                                    style="font-family:'Arial Narrow';"><?php echo $debitAccName; ?>&nbsp;</span>
                            </td>
                        </tr>
                        <tr>
                            <td height="22" class="">&nbsp;&nbsp;&nbsp;List Account</td>
                            <td class="elementTeks" valign="top">
                                <div style="position:relative;float:left;width:27px;">
                                    <button type="button" class="btnStandar" id="btnCariKredAcc" title=""
                                        onclick="listLoad();klikBtnCariKredAcc();">
                                        <table cellpadding="0" cellspacing="0" width="22" height="19">
                                            <tr>
                                                <td align="center" valign="bottom"><img src="../picture/magnifier.png"
                                                        height="14" /></td>
                                            </tr>
                                        </table>
                                    </button>
                                </div>
                                <div id="listLoading" style="position:relative;float:left;margin-top:3px;display:none;">
                                    <img src="../../picture/ajax-loader7.gif" height="14" /></div>
                                <div id="hasilKreditAcc" style="display:none;">
                                    <select id="menuKreditAcc" class="elementMenu" style="width:290px;height:22px;"
                                        onchange="pilihMenuKreditAcc(this.value);">
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td height="22" class="">&nbsp;&nbsp;&nbsp;Split Account</td>
                            <td style="position:inherit;">
                                <input type="checkbox" id="splitCreAcc" name="splitCreAcc" style="cursor:pointer;" <?PHP
                                    echo $checkCreSplit; ?>>
                                <span id="splitCreAccNo" style="position:relative;bottom:3px;"
                                    class="elementTeks">NO</span>
                                <button type="button" class="<?php echo $classBtnViewSplitCre; ?>" id="btnViewSplitCre"
                                    title="SPLIT CREDIT ACCOUNT"
                                    style="position:absolute;left:180px;width:62px;height:20px;"
                                    onclick="klikBtnViewCre();" <?php echo $disBtnViewSplitCre; ?>>
                                    <table cellpadding="0" cellspacing="0" width="100%" height="100%">
                                        <tr>
                                            <td align="center" width="22"><img src="../picture/eye--exclamation.png" />
                                            </td>
                                            <td align="center">VIEW</td>
                                        </tr>
                                    </table>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td height="23" class="">Sub Account</td>
                            <td class="">
                                <input type="text" name="subCode" id="subCode"
                                    oninput="displaySubAccountNya('<?php echo $compcodeNya; ?>',$(this).val());"
                                    class="elementInput3" style="width:60px;" maxlength="5"
                                    value="<?php echo $subCode; ?>" /> <span id="lbldescSubAcct"
                                    style="font-size:11px;"></span>
                            </td>
                        </tr>
                        <tr>
                            <td height="22">Voucher</td>
                            <td valign="middle">
                                <input type="text" name="voucher" id="voucher" class="elementInput3" style="width:60px;"
                                    maxlength="5" value="<?php echo $voucher; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td height="22">Reference</td>
                            <td valign="middle">
                                <input type="text" name="reference" id="reference" class="elementInput3"
                                    style="width:60px;" maxlength="5" value="<?php echo $reference; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td height="22" class="">Company</td>
                            <td class="elementTeks"><?php echo $company; ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="22" class="">Unit</td>
                            <td class="elementTeks"><?php echo $unitt; ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="22" class="">Barcode</td>
                            <td class="elementTeks"><?php echo $barcode; ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="22" class="">Date</td>
                            <td class="elementTeks"><?php echo $invoiceDate; ?>&nbsp;</td>
                        </tr>
                    </table>
                </td>

                <td valign="top">
                    <table cellpadding="0" cellspacing="0" width="495" class="tabelBorderBottomRightNull"
                        style="border-style:dotted;">
                        <tr>
                            <td width="120" height="22" class="">&nbsp;Journal Date</td>
                            <td class="elementTeks">
                                <input type="text" name="tglJurnal" id="tglJurnal" class="elementInput3"
                                    style="width:65px;" value="<?php echo $journalDate; ?>" />
                                <img src="../../picture/calendar.gif" class="gayaKalender" title="Select Date"
                                    onclick="displayCalendar(document.getElementById('tglJurnal'),'dd/mm/yyyy',this, '', '', '193', '183');" />&nbsp;<span
                                    class="spanKalender">(DD/MM/YYYY)</span>

                            </td>
                        </tr>
                        <tr>
                            <td height="22" class="">&nbsp;Due days</td>
                            <td class="elementTeks">
                                <input type="text" name="dueDayRet" id="dueDayRet" class="elementInput3"
                                    style="width:25px;" maxlength="3" onkeyup="pilihDueDayRet();"
                                    value="<?php echo $dueDay; ?>" />&nbsp;<span class="spanKalender"
                                    style="">(Day)</span>
                            </td>
                        </tr>
                        <tr>
                            <td height="22" class="">&nbsp;Due Date</td>
                            <td class="elementTeks"><span id="spanDueDate"><?php echo $dueDate; ?>&nbsp;&nbsp;<span
                                        id="idSpanDueDay" class="spanKalender"
                                        style="position:static;font-size:12px;color:#900;font-size:11px;">(
                                        <?php echo $dueDayFromNow; ?>&nbsp;Day(s) Left )</span>&nbsp;</span></td>
                        </tr>

                        <!--<tr>
            <td height="22" class="">&nbsp;Mail/Invoice No</td>
            <td class="elementTeks"><?php echo $noInvoice; ?>&nbsp;</td>
        </tr>-->
                        <tr>
                            <td height="22" class="">&nbsp;Mail/Invoice No</td>
                            <td class="elementTeks">
                                <input type="text" class="elementInput3" id="mailInvNo" name="mailInvNo"
                                    style="width:275px;" value="<?php echo $mailInvNo; ?>">
                            </td>
                        </tr>

                        <tr>
                            <td height="22" class="">&nbsp;Currency</td>
                            <td class="elementTeks"><?php echo $currency; ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="22" class="">&nbsp;Amount</td>
                            <td class="elementTeks"><?php echo $amount; ?>&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td height="22" class="">&nbsp;Remark</td>
                            <td class="elementTeks"><?php echo $remark; ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="18" class="" valign="bottom">&nbsp;Deduction <span id="tess"></span></td>
                        </tr>
                        <tr>
                            <td align="right" colspan="2">
                                <table cellpadding="0" cellspacing="0" width="480"
                                    style="border:solid 0px #666;font-size:12px;">
                                    <tr align="center" style="background-color:#F2FAFF;">
                                        <td height="20" width="50" class="tabelBorderBottomRightNull">Account</td>
                                        <td width="120" class="tabelBorderTopJust">Amount</td>
                                        <td width="40" class="tabelBorderTopJust">Vessel</td>
                                        <td width="" class="tabelBorderBottomLeftNull">Description</td>
                                    </tr>
                                    <?php
				for($i=1;$i<=5;$i++) 
				{ 
					$deducAcc = $CInvReg->detilSplitTemp($idMailInvGet, $userIdLogin, "deduction", $i, "account");
					$amountt = number_format( $CInvReg->detilSplitTemp($idMailInvGet, $userIdLogin, "deduction", $i, "amount"), 2, ".", ",");
					$deducAmt = $amountt=="0.00"?"":$amountt;
					$vslCode = $CInvReg->detilSplitTemp($idMailInvGet, $userIdLogin, "deduction", $i, "vslcode");
					$deducDesc = $CInvReg->detilSplitTemp($idMailInvGet, $userIdLogin, "deduction", $i, "description"); ?>
                                    <tr>
                                        <td align="left" height="22">
                                            <input type="text" id="deducAcc<?php echo $i ?>"
                                                name="deducAcc<?php echo $i ?>" class="elementInput3" maxlength="5"
                                                style="width:99%;" value="<?php echo $deducAcc; ?>"
                                                onBlur="simpanSplitCred(this.value, 'deduction', '<?php echo $i ?>', 'account');">
                                        </td>
                                        <td align="center">
                                            <input type="text" id="deducAmount<?php echo $i ?>"
                                                name="deducAmount<?php echo $i ?>" class="elementInput3"
                                                style="width:99%;text-align:right;" value="<?php echo $deducAmt; ?>"
                                                onBlur="simpanSplitCred(this.value, 'deduction', '<?php echo $i ?>', 'amount');"
                                                onkeyup="hanyaAngkaDed<?php echo $i ?>();return false;">
                                        </td>
                                        <td align="center">
                                            <input type="text" id="deducVslCode<?php echo $i ?>"
                                                name="deducVslCode<?php echo $i ?>" class="elementInput3" maxlength="3"
                                                style="width:99%;text-align:right;" value="<?php echo $vslCode; ?>"
                                                onblur="simpanSplitCred(this.value, 'deduction', '<?php echo $i ?>', 'vslCode');"
                                                onkeyup="return false;">
                                        </td>
                                        <td align="right">
                                            <input type="text" id="deducReason<?php echo $i ?>"
                                                name="deducReason<?php echo $i ?>" class="elementInput3" maxlength="70"
                                                style="width:99.7%;" value="<?php echo $deducDesc; ?>"
                                                onBlur="simpanSplitCred(this.value, 'deduction', '<?php echo $i ?>', 'description');"
                                                onfocus="focusDescription(this.id, '<?php echo mysql_escape_string( $remark ); ?>');">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </table>
                            </td>
                        </tr>
                        <!--<tr>
            <td height="18" class="" valign="bottom">&nbsp;Additional</td>
        </tr>
        <tr>
            <td align="right" colspan="2">
            	<table cellpadding="0" cellspacing="0" width="480" style="border:solid 0px #666;font-size:12px;">
                <tr align="center" style="background-color:#F2FAFF;">
                	<td height="20" width="30" class="tabelBorderBottomRightNull">Pay</td>
                	<td width="50" class="tabelBorderTopJust">Account</td>
                    <td width="120" class="tabelBorderTopJust">Amount</td>
                    <td width="40" class="tabelBorderTopJust">Vessel</td>
                    <td width="" class="tabelBorderBottomLeftNull">Description</td>
                </tr>
                <?php
				for($ii=1;$ii<=5;$ii++) 
				{
					$classPay = $ii==5?"tabelBorderTopRightNull":"tabelBorderLeftJust";
					
					$addiPay = $CInvReg->detilSplitTemp($idMailInvGet, $userIdLogin, "additional", $ii, "pay")=="Y"?"checked":"";
					$addiAcc = $CInvReg->detilSplitTemp($idMailInvGet, $userIdLogin, "additional", $ii, "account");
					$amounttt = number_format( $CInvReg->detilSplitTemp($idMailInvGet, $userIdLogin, "additional", $ii, "amount"), 2, ".", ",");
					$addiAmt = $amounttt=="0.00"?"":$amounttt;
					$addiVslCode = $CInvReg->detilSplitTemp($idMailInvGet, $userIdLogin, "additional", $ii, "vslcode");
					$addiDesc = $CInvReg->detilSplitTemp($idMailInvGet, $userIdLogin, "additional", $ii, "description");
				?>
<tr>
	<td align="center" class="<?php echo $classPay; ?>" style="background-color:#FFF;cursor:pointer;">
		<input type="checkbox" id="cekPay<?php echo $ii; ?>" name="cekPay<?php echo $ii; ?>" 
        onClick="changeCekPay(this.id, this.checked);" <?php echo $addiPay; ?> />
    </td>
	<td align="left" height="22">
    	<input type="text" id="addiAcc<?php echo $ii; ?>" name="addiAcc<?php echo $ii; ?>" class="elementInput3" maxlength="5" style="width:99%;" value="<?php echo $addiAcc; ?>" 
        onBlur="simpanSplitCred(this.value, 'additional', '<?php echo $ii; ?>', 'account');">
    </td>
	<td align="center">
    <input type="text" id="addiAmount<?php echo $ii; ?>" name="addiAmount<?php echo $ii; ?>" class="elementInput3" style="width:99%;text-align:right;" value="<?php echo $addiAmt; ?>" 
        onBlur="simpanSplitCred(this.value, 'additional', '<?php echo $ii; ?>', 'amount');" onkeyup="hanyaAngkaAddi<?php echo $ii; ?>();return false;">
    </td>
    <td align="center">
    	<input type="text" id="addiVslCode<?php echo $ii ?>" name="addiVslCode<?php echo $ii ?>" class="elementInput3" maxlength="3" style="width:99%;text-align:right;" value="<?php echo $addiVslCode; ?>" 
        onBlur="simpanSplitCred(this.value, 'additional', '<?php echo $ii; ?>', 'vslCode');">
        </td>
	<td align="right">
    <input type="text" id="addiReason<?php echo $ii; ?>" name="addiReason<?php echo $ii; ?>" class="elementInput3" maxlength="70" style="width:99.7%;" value="<?php echo $addiDesc; ?>" 
        onBlur="simpanSplitCred(this.value, 'additional', '<?php echo $ii; ?>', 'description');" onfocus="focusDescription(this.id, '<?php echo mysql_escape_string( $remark ); ?>');">
    </td>
</tr>
		<?php } ?>
                </table>
            </td>
        </tr>-->
                    </table>
                </td>
            </tr>
            <tr>
                <td width="50%" height="20" class="" colspan="2">
                    <div id="idErrorMsg" class="errorMsgInv" style="visibility:hidden;"><img
                            src="../picture/exclamation-red.png" />&nbsp;<span>&nbsp;</span>&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td colspan="2" height="40" align="center" class="tabelBorderTopJust" style="border-style:dotted;">
                    <input type="hidden" id="totalSplitDeb" name="totalSplitDeb" style="width:100px;" value="0">
                    <input type="hidden" id="totalSplitCre" name="totalSplitCre" style="width:100px;" value="0">
                    <input type="hidden" id="totalDeduc" name="totalDeduc" style="width:100px;" value="0">
                    <input type="hidden" id="totalAddi" name="totalAddi" style="width:100px;" value="0">
                    <input type="hidden" id="incomingAmt" name="incomingAmt"
                        value="<?php echo str_replace(",","",$amount); ?>" style="width:100px;">
                    <input type="hidden" id="finalAmt" name="finalAmt"
                        value="<?php echo str_replace(",","",$amount); ?>" style="width:100px;">
                    <input type="hidden" id="totalCredAmt" name="totalCredAmt"
                        value="<?php echo str_replace(",","",$amount); ?>" style="width:100px;">
                    <input type="hidden" id="totalDebAmt" name="totalDebAmt"
                        value="<?php echo str_replace(",","",$amount); ?>" style="width:100px;">
                    <input type="hidden" id="balanceAmt" name="balanceAmt" value="" style="width:100px;">
                    <button type="button" id="btnClose" class="btnStandar" onclick="tutupDetailProcess('Y');">
                        <table cellpadding="0" cellspacing="0" width="63" height="30">
                            <tr>
                                <td align="center"><img src="../picture/door-open-out.png"
                                        style="vertical-align:middle;" /><span
                                        style="vertical-align:middle;margin-left:4px;">CLOSE</span></td>
                            </tr>
                        </table>
                    </button>
                    <button type="button" id="btnCloseErase" class="btnStandar" onclick="closeAndErase();">
                        <table cellpadding="0" cellspacing="0" width="110" height="30">
                            <tr>
                                <td align="center"><img src="../picture/exclamation-red.png"
                                        style="vertical-align:middle;" /><span
                                        style="vertical-align:middle;margin-left:4px;">CLOSE & ERASE</span></td>
                            </tr>
                        </table>
                    </button>
                    <button type="button" class="btnStandar" id="btnTransfer" onclick="pilihBtnSaveRet();">
                        <table cellpadding="0" cellspacing="0" width="81" height="30">
                            <tr>
                                <td align="center"><img src="../picture/disk-black.png"
                                        style="vertical-align:middle;" /><span
                                        style="vertical-align:middle;margin-left:4px;">TRANSFER</span></td>
                            </tr>
                        </table>
                    </button>
                    <button type="button" id="btnReset" class="btnStandar"
                        onclick="formUbahProcessRet.reset(); fungsiReset();">
                        <table cellpadding="0" cellspacing="0" width="63" height="30">
                            <tr>
                                <td align="center"><img src="../picture/arrow-return-180-left.png"
                                        style="vertical-align:middle;" /><span
                                        style="vertical-align:middle;margin-left:4px;">RESET</span></td>
                            </tr>
                        </table>
                    </button>
                </td>
            </tr>
        </form>
    </table>
</div>

<!-- #################################################################################################################
JENDELA SPLIT FOR DEBIT ACCOUNT
################################################################################################################# -->

<div id="divCloseSplitDeb" style="position:absolute;left:516px;top:110px;z-index:4;display:none;">
    <button type="button" class="btnStandar" id="btnCloseSplitDeb" title="" onclick="klikBtnCloseSplitDeb();">
        <table cellpadding="0" cellspacing="0" width="22" height="19">
            <tr>
                <td align="center" valign="bottom"><img src="../picture/cross.png" height="14" /></td>
            </tr>
        </table>
    </button>
</div>

<div id="divKontenSplitDeb"
    style="position:absolute;left:0px;top:106px; z-index:3;width:540px;background-color:#FFC;border-width:2px; border-color:#FC0;display:none;"
    class="tabelBorderAll">
    <table cellpadding="0" cellspacing="0" width="475" height="350" align="center"
        style="font:12px sans-serif;font-weight:bold;color:#485a88;">
        <tr>
            <td height="20"><span style="font-style:italic;text-decoration:underline;">SPLIT FOR DEBIT ACCOUNT >></span>
            </td>
        </tr>
        <tr>
            <td height="30">
                <button class="btnStandar" id="btnAddDesc" title="ADD NEW DESCRIPTION" style="width:50px;height:22px;"
                    onclick="klikBtnAddDesc();return false;">
                    <table cellpadding="0" cellspacing="0" width="100%" height="100%">
                        <tr>
                            <td align="center" width="22"><img src="../picture/blue-document--plus.png" /></td>
                            <td align="left">ADD</td>
                        </tr>
                    </table>
                </button>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total (CR) : <span id="spanTotalCredAmt"
                    style="font-size:12px;color:#333;"><?php echo $amount; ?></span>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Balance : <span id="spanBalance"
                    style="font-size:12px;color:#333;">0</span>
            </td>
        </tr>
        <tr>
            <td valign="top">
                <div id="divIframeList"
                    style="position:inherit; border: solid 1px #CCC; top:0px; left:0px;text-align:left;width:525px;height:297px;z-index:1;">
                    <iframe width="100%" height="100%"
                        src="../templates/halSplitDebList.php?idMailInv=<?php echo $idMailInvGet; ?>"
                        target="iframeSplitDebList" name="iframeSplitDebList" id="iframeSplitDebList" frameborder="0"
                        marginwidth="0" marginheight="0" scrolling="yes" class="" style="z-index:0;"></iframe>
                </div>
            </td>
        </tr>
        <tr>
            <td height="30">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total
                (DB) : <span id="spanTotalDebAmt" style="font-size:12px;color:#333;">&nbsp;</span>
            </td>
        </tr>
    </table>
</div>

<div id="divCloseSplitCre" style="position:absolute;left:516px;top:110px;z-index:4;display:none;">
    <button type="button" class="btnStandar" id="btnCloseSplitCre" title="Exit" onclick="klikBtnCloseSplitCre();">
        <table cellpadding="0" cellspacing="0" width="22" height="19">
            <tr>
                <td align="center" valign="bottom"><img src="../picture/cross.png" height="14" /></td>
            </tr>
        </table>
    </button>
</div>
<div id="divKontenSplitCre"
    style="position:absolute;left:0px;top:106px; z-index:3;width:540px;background-color:#FFC;border-width:2px; border-color:#FC0;display:none;"
    class="tabelBorderAll">
    <table cellpadding="0" cellspacing="0" width="475" height="350" align="center"
        style="font:12px sans-serif;font-weight:bold;color:#485a88;">
        <tr>
            <td height="20"><span style="font-style:italic;text-decoration:underline;">SPLIT FOR CREDIT ACCOUNT
                    >></span></td>
        </tr>
        <tr>
            <td height="30">
                <button class="btnStandar" id="btnAddDesc" title="ADD NEW DESCRIPTION" style="width:50px;height:22px;"
                    onclick="klikBtnAddCreditDesc();return false;">
                    <table cellpadding="0" cellspacing="0" width="100%" height="100%">
                        <tr>
                            <td align="center" width="22"><img src="../picture/blue-document--plus.png" /></td>
                            <td align="left">ADD</td>
                        </tr>
                    </table>
                </button>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total (CR) : <span id="spanTotalCredAmt"
                    style="font-size:12px;color:#333;"><?php echo $amount; ?></span>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Balance : <span id="spanBalanceCre"
                    style="font-size:12px;color:#333;">0</span>
            </td>
        </tr>
        <tr>
            <td valign="top">
                <div id="divIframeList"
                    style="position:inherit; border: solid 1px #CCC; top:0px; left:0px;text-align:left;width:525px;height:297px;z-index:1;">
                    <iframe width="100%" height="100%"
                        src="../templates/halSplitCreList.php?idMailInv=<?php echo $idMailInvGet; ?>"
                        target="iframeSplitCreList" name="iframeSplitCreList" id="iframeSplitCreList" frameborder="0"
                        marginwidth="0" marginheight="0" scrolling="yes" class="" style="z-index:0;"></iframe>
                </div>
            </td>
        </tr>
        <tr>
            <td height="30">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total
                (CR) : <span id="spanTotalCreAmt" style="font-size:12px;color:#333;">&nbsp;</span>
            </td>
        </tr>
    </table>
</div>

<script>
window.onload = function() {
    <?php
	if($aksiPost != "simpanEditInvRet") // JIKA AKSI BUKAN SETELAH TRANSFER JURNAL / JIKA AKSI ADALAH KETIKA BUKA HALAMAN TRANSFER JURNAL ENTRY
	{ ?>
    if (!window.location.hash) {
        window.location = window.location + '#loaded';
        window.location.reload();
    }

    doneWait();
    panggilEnableLeftClick();
    cekTabelDeduc();
    updateFinalAmt("deduc");
    updateFinalAmt("addi");

    for (var b = 1; b <= 5; b++) {
        $("#deducAcc" + b).keypress(function(e) {
            var charCode = (e.which) ? e.which : e.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
        });

        $("#addiAcc" + b).keypress(function(e) {
            var charCode = (e.which) ? e.which : e.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
        });

        $("#deducVslCode" + b).keypress(function(e) {
            var charCode = (e.which) ? e.which : e.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
        });

        $("#addiVslCode" + b).keypress(function(e) {
            var charCode = (e.which) ? e.which : e.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
        });

        $("#deducAmount" + b).keypress(function(e) {
            var charCode = (e.which) ? e.which : e.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) // HANYA BISA ANGKA
            {
                if (charCode != 46) // TITIK DIPERBOLEHKAN UNTUK ANGKA DIBELAKANG KOMA
                {
                    return false;
                }
            }
        });

        $("#addiAmount" + b).keypress(function(e) {
            var charCode = (e.which) ? e.which : e.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) // HANYA BISA ANGKA
            {
                if (charCode != 46) // TITIK DIPERBOLEHKAN UNTUK ANGKA DIBELAKANG KOMA
                {
                    return false;
                }
            }
        });

        $("#deducAmount" + b).keyup(function() {
            updateFinalAmt("deduc");
        });

        $("#addiAmount" + b).keyup(function() {
            updateFinalAmt("addi");
        });
    }
    <?php
	} ?>

    setupDetilProcessRet();
    $("#dateReturned")
.focus(); // SET FOCUS SALAH SATU ELEMEN KARENA KADANG2 SUKA TIDAK BISA DIISI ELEMENT INPUT TEKS DI HALAMAN INI
}
<?php
if($tutupWindow == "yes")
{
	echo "tutupDetailProcess('N');";
	//echo "closeAndErase('N');";
}

if($alertCompany == "Y")
{
	echo "alert('TRANSFER FAILED, Company doesnt exist in the system');";
}

for($a=1;$a<=5;$a++) 
{
	$htmlHanyaAngka .="
	function hanyaAngkaDed".$a."()
	{
		amountMask".$a." = new Mask(\"#,###.##\", \"number\");
		amountMask".$a.".attach(document.getElementById('deducAmount".$a."'));
	}
	
	function hanyaAngkaAddi".$a."()
	{
		amountMask".$a." = new Mask(\"#,###.##\", \"number\");
		amountMask".$a.".attach(document.getElementById('addiAmount".$a."'));
	}
	";
}

echo $htmlHanyaAngka;
?>

function focusDescription(idElement, remarks) {
    if ($.trim($("#" + idElement).val()) == "") {
        $("#" + idElement).val(remarks);
    }
}


function blinksDueDay(hide) {
    if (hide === 1) {
        $('#idSpanDueDay').show();
        hide = 0;
    } else {
        $('#idSpanDueDay').hide();
        hide = 1;
    }
    setTimeout("blinksDueDay(" + hide + ")", 400);
}

$(document).ready(function() {
    //setInterval(blinkDueDay, 1500);
    blinksDueDay(1);
});

function displaySubAccountNya(cmp, subaccount) {
    var account = $('#kreditAcc').val();
    var ttlChr = subaccount.length;
    $("#lbldescSubAcct").text("");

    if (ttlChr == '5') {
        $.post("../halPostMailInv.php", {
                aksi: "displaySubAccountNya",
                cmp: cmp,
                account: account,
                subaccount: subaccount
            },
            function(data) {
                $("#lbldescSubAcct").text(data);
            });
    }
}
</script>

</HTML>