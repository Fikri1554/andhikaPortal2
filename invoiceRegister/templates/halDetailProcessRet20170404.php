<!DOCTYPE HTML>
<?php
require_once("../configInvReg.php");

$batchnoGet = $_GET['batchno'];
$idMailInvGet = $_GET['idMailInv'];
?>

<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="../../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../../js/Parsers.js"></script>
<script type="text/javascript" src="../../js/InputMask.js"></script>
<script type="text/javascript" src="../js/masks.js"></script>
<script type="text/javascript" src="../js/invReg.js"></script>
<script type="text/javascript" src="../js/process.js"></script>

<link rel="stylesheet" type="text/css" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK>
<link rel="stylesheet" type="text/css" href="../../css/archives.css"> 
<link rel="stylesheet" type="text/css" href="../css/invReg.css"> 
<link rel="stylesheet" type="text/css" href="../css/button.css"/>
<link rel="stylesheet" type="text/css" href="../css/table.css"/>

<style>
body {background-color: #f9f9f9;}
</style>

<?php	
if($aksiPost  == "simpanEditInvRet")
{
	//$invRetPost = $CPublic->jikaParamSmDgNilai($_POST['invReturned'], "on", "1", "0"); // JIKA ELEMENT CENTANG INV RETURN DIMUNCULKAN
	$invRetPost = "1";
	$dateRetPost = $_POST['dateReturned'];
	$dateRetDB = $CPublic->convTglDB($dateRetPost);
	$ignoreJePost = $CPublic->jikaParamSmDgNilai( $_POST['ignoredJe'], "on", "1", "0");
	$apprPaymentPost =  $CPublic->jikaParamSmDgNilai($_POST['approvePayment'], "on", "1", "0");
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
	//echo $addiRetPost." / ".$reasonDeducPost;
	$alertCompany = "N";
	
	$namaTableAcc = $CInvReg->detilMailInv($idMailInvGet, "company").substr($tglJurnalPost,6, 4);
	//echo $tglJurnalPost." / ".$namaTableAcc." / ".$CInvReg->cekTableExist($namaTableAcc);
	if($CInvReg->cekTableExist($namaTableAcc) == "ada")
	{
		
		$CKoneksiInvReg->mysqlQuery("UPDATE mailinvoice SET tgljurnal = '".$tglJurnalDB."', dueday = '".$dueDayRetPost."', tglexp = '".$dueDatePost ."', addi='".$addiRetPost."', deduc='".$deducRetPost."', reasondeduc='".$reasonDeducPost."', invret  = '".$invRetPost."', dateret = '".$dateRetDB."', ignoreje = '".$ignoreJePost."', apprpayment = '".$apprPaymentPost."', vescode = '".$vslCodePost."', vesname='".$vslName."', source = '".$sourcePost."', debitacc = '".$debitAccPost."', debitaccname = '".$debitAccNamePost."', subcode = '".$subCodePost."', kreditacc = '".$kreditAccPost."', kreditaccname = '".$kreditAccNamePost."', voucherje='".$voucherPost."', referenceje='".$referencePost."',  mailinvno = '".$invNo."', description = '".$descriptionPost."', saveinvret = 'Y', retby='".$userIdLogin."', updusrdt = '".$userWhoAct."' WHERE idmailinv = '".$idMailInvGet."' AND deletests=0");
		
		//$invDate =  date("m/d/Y", strtotime($CInvReg->detilMailInv($idMailInvGet, "tglinvoice"))); 
		$invDate = $CInvReg->detilMailInv($idMailInvGet, "tglinvoice");
		// FORMAT DATETIME UNTUK SQLSERVER ADALAH MM/DD/YYYY WALAUPUN TAMPILAN DI DATABASE SQLSERVER NANTINYA ADALAH DD/MM/YYYY
		//$invNo = $CPublic->ms_escape_string( $CInvReg->detilMailInv($idMailInvGet, "mailinvno") );  
		$invCurr = $CInvReg->detilMailInv($idMailInvGet, "currency");  
		$invAmt = $CInvReg->detilMailInv($idMailInvGet, "amount");  
		$acctCode = $debitAccPost;  
		// JIKA VSLCODEPOST SAMA DENGAN "XXX" MAKA GANTI DENGAN "" JIKA TIDAK MAKA BERI NILAI NYA
		//$vslCode = $CPublic->jikaParamSmDgNilai($vslCodePost, "XXX", "", $vslCodePost);
		$source = $sourcePost;
		
		$compcode = $CInvReg->detilMailInv($idMailInvGet, "company"); 
		$pOno = $CInvReg->detilMailInv($idMailInvGet, "barcode");  
		$workdesc = $CPublic->ms_escape_string( $CInvReg->detilMailInv($idMailInvGet, "description") );  
		$creditorNo = $kreditAccPost;  
		$credName = $kreditAccNamePost;  
		
		//$tglAwal = $CInvReg->detilMailInv($idMailInvGet, "tglinvoice"); 
		//$tglAkhir = $CInvReg->detilMailInv($idMailInvGet, "tglexp"); // DUE DATE
		//$credDays = $CPublic->datesRange($tglAwal, $tglAkhir);
		$credDays = $dueDayRetPost;
		
		$subCode = $subCodePost;  
		//$entryDate = date("m/d/Y",strtotime($CPublic->tglServer()))." ".$CPublic->jamServer();
		$entryDate = date("Y-m-d",strtotime($CPublic->tglServer()))." ".$CPublic->jamServer();
		//$srcProg = "invReturn";  
		$transBy = strtoupper( $userName );
		
		$CHistory->updateLogInvReg($userIdLogin, "Simpan Invoice Returned (idmailinv = <b>".$idMailInvGet."</b>,
											 Dueday = <b>".$dueDayRetPost."</b>,
											 Tglexp = <b>".$dueDatePost ."</b>, 
											 Additional = <b>".$addiRetPost."</b>, 
											 Deduc = <b>".$deducRetPost."</b>, 
											 Reasondeduc = <b>".$reasonDeducPost."</b>, 
											 Inv.Returned = <b>".$CPublic->nilaiCentang($invRetPost)."</b>,
											 DateReturned = ".$CPublic->convTglNonDB($dateRetPost)."</b>,
											 IgnoreJe = <b>".$CPublic->nilaiCentang($ignoreJePost)."</b>,
											 ApprPayment = <b>".$CPublic->nilaiCentang($apprPaymentPost)."</b>,
											 VslName = <b>".$CInvReg->detilVessel($vslCodePost, "Fullname")."</b>,
											 Source = <b>".$sourcePost."</b>,
											 DebitAcc = <b>".$debitAccPost ."</b>,
											 DebitAccName = <b>".$debitAccNamePost ."</b>,
											 SubCode = <b>".$subCodePost."</b>,
											 KreditAcc = <b>".$kreditAccPost."</b>,
											 KreditAccName = <b>".$kreditAccNamePost."</b>)");
											 
		if($ignoreJePost == 0) 
		{
			// JIKA IGNOREJE TIDAK DICENTANG MAKA SIMPAN / TRANSFER JURNAL ENTRI KE DATABASE 'BRIDGE' TABEL PENDINGJE2011 YANG ADA DI SQLSERVER
			/*$koneksiOdbcBridge->odbcExec($koneksiOdbcBridgeId, "INSERT INTO ".$tabelPendingJE." (invdate, invno, invcurr, invamt, acctcode, vslcode, source, compcode, pono, workdesc, creditorno, credname, creddays, subcode, entrydate, srcprog) VALUES ('".$invDate."', '".$invNo."', '".$invCurr."', '".$invAmt."', '".$acctCode."', '".$vslCodePost."', '".$source."', '".$compcode."', '".$pOno."', '".$workdesc."', '".$creditorNo."', '".$credName."', '".$credDays."', '".$subCode."', '".$entryDate."', '".$srcProg."')");*/
			
			$CKoneksiInvReg->mysqlQuery("INSERT INTO pendingje (invdate, voucher, reference, invno, invcurr, invamt, addi, deduc, reasondeduc, acctcode, vslcode, source, compcode, pono, workdesc, creditorno, credname, creddays, subcode, entrydate, transby) VALUES ('".$invDate."', '".$voucherPost."', '".$referencePost."', '".$invNo."', '".$invCurr."', '".$invAmt."', '".$addiRetPost."', '".$deducRetPost."', '".$reasonDeducPost."', '".$acctCode."', '".$vslCodePost."', '".$source."', '".$compcode."', '".$pOno."', '".$workdesc."', '".$creditorNo."', '".$credName."', '".$credDays."', '".$subCode."', '".$entryDate."', '".$transBy."')");
			
			$CHistory->updateLogInvReg($userIdLogin, "Simpan PendingJE Returned (Inv. Date = <b>".$invDate."</b>,
											 Voucher = <b>".$voucherPost."</b>,
											 Reference = <b>".$referencePost."</b>,
											 Inv. Number = <b>".$invNo."</b>,
											 Currency = ".$invCurr."</b>,
											 Amount = <b>".$invAmt."</b>,
											 Additional = <b>".$addiRetPost."</b>, 
											 Deduc = <b>".$deducRetPost."</b>,
											 Reasondeduc Code = <b>".$reasonDeducPost."</b>,
											 Account Code = <b>".$acctCode."</b>,
											 Vessel Code = <b>".$vslCodePost."</b>,
											 Source = <b>".$source."</b>,
											 Company Code = <b>".$compcode ."</b>,
											 PO Number = <b>".$pOno ."</b>,
											 Workdesc = <b>".$workdesc."</b>,
											 Kreditor No = <b>".$creditorNo."</b>,
											 Kreditor Name = <b>".$kreditAccNamePost."</b>,
											 Credddays (Duedate) = <b>".$credDays."</b>,
											 Subcode = <b>".$subCode."</b>,
											 Entry Date = <b>".$entryDate."</b>,
											 Trans By = <b>".$transBy."</b>)");
											 
			$ThnInvDate =  date("Y", strtotime($CInvReg->detilMailInv($idMailInvGet, "tglinvoice")));
			
			//Tahun yang diambil = jurnal date, karena tahun ini yg merupakan update sesuai kebutuhan transfer. 
			$ThnJurnalDate =  date("Y", strtotime($CInvReg->detilMailInv($idMailInvGet, "tgljurnal"))); 
			
	// ##############################################################################################################################
	//--- Start -- Hapus Tes jika ingin upload dari AndhiPortalTes ke AndhikaPortal --------------------------------------------------
			$dbAccounting = $compcode.$ThnJurnalDate;
	//--- End -- Hapus Tes jika ingin upload dari AndhiPortalTes ke AndhikaPortal ----------------------------------------------------
	// ##############################################################################################################################
		
			//$bookDate = $CInvReg->detilMailInv($idMailInvGet, "tglinvoice"); //tgl invoice
			$bookDate = $CInvReg->detilMailInv($idMailInvGet, "tgljurnal"); //tgl jurnal
			$entryDateMSSQL = $CPublic->tglServerWithStrip()." ".$CPublic->jamServer();
			//$refNumber = $CInvReg->detilComp($compcode, "Jecode").date("m", strtotime($CInvReg->detilMailInv($idMailInvGet, "tglinvoice")))."-".$CPublic->zerofill($referencePost, 5); //sourcebulan-reference
			$refNumber = $CInvReg->detilComp($compcode, "Jecode").date("m", strtotime($bookDate))."-".$CPublic->zerofill($referencePost, 5); //sourcebulan(diambil dari jurnal date)-reference
			
			//echo $dbAccounting."<br>";
			//echo "(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Entryuser, Progdebug)<br>";
			//echo "('".$compcode."', '".$sourcePost."', '".$bookDate."', '', '', '".$invNo."', '".$pOno."', '".$descriptionPost."', '".$vslCodePost."', '".$debitAccPost."', '', '".$subCodePost."', '".$invCurr."', 'DB', '".number_format((float)$invAmt, 2, '.', '')."', '".$invCurr."', '^', '".$entryDateMSSQL."', '".$userName."', 'Transfer JE:".$batchnoGet."')<br>";
			//echo "('".$compcode."', '".$sourcePost."', '".$bookDate."', '', '', '".$invNo."', '".$pOno."', '".$descriptionPost."', '".$vslCodePost."', '".$kreditAccPost."', '', '".$subCodePost."', '".$invCurr."', 'CR', '".number_format((float)$invAmt, 2, '.', '')."', '".$invCurr."', '^', '".$entryDateMSSQL."', '".$userName."', 'Transfer JE:".$batchnoGet."')";
			
			// SIMPAN KEDATABASE SQL SERVER UNTUK CREDIT DEBIT DENGAN SOURCE MMN (TRANSFER JURNAL ENTRY)
			$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
						(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Entryuser, Progdebug) VALUES 
						('".$compcode."', '".$sourcePost."', '".$bookDate."', '".$voucherPost."', '".$refNumber."', '".substr($invNo, 0, 16)."', '".$pOno."', '".substr($CPublic->ms_escape_string( $_POST['description'] ), 0, 70)."', '".$vslCodePost."', '".$debitAccPost."', '', '".$subCodePost."', '".$invCurr."', 'DB', '".number_format((float)(($invAmt+$addiRetPost)-$deducRetPost), 2, '.', '')."', '".$invCurr."', '^', '".$entryDateMSSQL."', '".$userName."', 'Transfer JE:".$batchnoGet."')");
			$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
						(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Entryuser, Progdebug) VALUES 
						('".$compcode."', '".$sourcePost."', '".$bookDate."', '".$voucherPost."', '".$refNumber."', '".substr($invNo, 0, 16)."', '".$pOno."', '".substr($CPublic->ms_escape_string( $_POST['description'] ), 0, 70)."', '".$vslCodePost."', '".$kreditAccPost."', '', '".$subCodePost."', '".$invCurr."', 'CR', '".number_format((float)(($invAmt+$addiRetPost)-$deducRetPost), 2, '.', '')."', '".$invCurr."', '^', '".$entryDateMSSQL."', '".$userName."', 'Transfer JE:".$batchnoGet."')");
			
			// SIMPAN HISTORY UNTUK CREDIT DEBIT DENGAN SOURCE MMN
			$CHistory->updateLogInvReg($userIdLogin, "Simpan PendingJE Returned SQLSERVER (DATABASE : ".$dbAccounting." | DB/CR) (Company = <b>".$compcode."</b>, Source = <b>".$sourcePost."</b>, Bookdate = <b>".$bookDate."</b>, Voucher = <b>".$voucherPost."</b>, Refnumber = ".$refNumber."</b>, Invoiceno = <b>".substr($invNo, 0, 16)."</b>, Pono = <b>".$pOno."</b>,  Bookdesc = <b>".substr($CPublic->ms_escape_string( $_POST['description'] ), 0, 70)."</b>, Vessel = <b>".$vslCodePost."</b>, Account = <b>".$kreditAccPost."</b>, Subacct = <b></b>, Subcode = <b>".$subCodePost."</b>, Currcy = <b>".$invCurr ."</b>, Booksts = <b>DB/CR</b>, Amount = <b>".number_format((float)(($invAmt+$addiRetPost)-$deducRetPost), 2, '.', '')."</b>, Diffcur = <b>".$invCurr."</b>, Codests = <b>^</b>, Entrydate = <b>".$entryDateMSSQL."</b>, Entrydate = <b>".$userName."</b>, Progdebug = <b>Transfer JE:".$batchnoGet."</b>)");
		}	
									
		echo ("<body onLoad=\"doneWait();\">");
		$tutupWindow = "ya";
	}
	else if($CInvReg->cekTableExist($namaTableAcc) == "tidak")
	{
		$alertCompany = "Y";
	} 
}

$dateReturned = $CPublic->convTglNonDB( $CPublic->waktuSek() );

//$readonlyKreditAcc = "";
$kreditAccount = $CInvReg->detilMailInv($idMailInvGet, "kreditacc"); // jika sendervendor2 kosong maka $kreditAccount sama dengan kreditAcc di field database 
if($CInvReg->detilMailInv($idMailInvGet, "sendervendor2") != "")
{
	//$readonlyKreditAcc = "readonly";
	$kreditAccount = $CInvReg->detilMailInv($idMailInvGet, "sendervendor2");
}
	
$vesselName = $CInvReg->menuVessel("");
$description = $remark = substr($CInvReg->detilMailInv($idMailInvGet, "remark"),0,70); // SEBELUM DI TRANSFER JE NILAI DESCRIPTION SECARA DEFAULT ADALAH SAMA DENGAN REMARKS

$senderVendor1  = "";
$senderVendor2  = "";
$tipeSenderVen = $CInvReg->detilMailInv($idMailInvGet, "tipesenven");

if($tipeSenderVen == "1")
	$senderVendor = $CInvReg->detilMailInv($idMailInvGet, "sendervendor1");
	
if($tipeSenderVen == "2")
	$senderVendor = $CInvReg->detilMailInv($idMailInvGet, "sendervendor2name");

$company = $CInvReg->detilMailInv($idMailInvGet, "companyname");

$unitt = $CInvReg->detilMailInv($idMailInvGet, "unitname");
$barcode = $CInvReg->detilMailInv($idMailInvGet, "barcode");
$invoiceDate = $CPublic->convTglNonDB( $CInvReg->detilMailInv($idMailInvGet, "tglinvoice") );
$dueDayFromNow = $CPublic->perbedaanHari( "'".$CInvReg->detilMailInv($idMailInvGet, "tglexp")."'" , "NOW()") ;

$dueDay = $CInvReg->detilMailInv($idMailInvGet, "dueday");
$dueDate = "";
if($CInvReg->detilMailInv($idMailInvGet, "tglexp") != "0000-00-00")
	$dueDate = $CPublic->convTglNonDB( $CInvReg->detilMailInv($idMailInvGet, "tglexp") );
	
$noInvoice = $CInvReg->detilMailInv($idMailInvGet, "mailinvno");

$amount = "";
if($CInvReg->detilMailInv($idMailInvGet, "amount") != "0.00")
	$amount = number_format($CInvReg->detilMailInv($idMailInvGet, "amount"), 2, ",", ".");

$currency  = $CInvReg->detilMailInv($idMailInvGet, "currency");
$remark = $CInvReg->detilMailInv($idMailInvGet, "remark");
$SNo = $CInvReg->detilMailInv($idMailInvGet, "urutan");
?>
<style>
body {background-color: #f9f9f9;}
</style>

<script>
window.onload = function()
{
	setupDetilProcessRet();
}

function blinksDueDay(hide) {
    if (hide === 1) {
        $('#idSpanDueDay').show();
        hide = 0;
    }
    else {
        $('#idSpanDueDay').hide();
        hide = 1;
    }
    setTimeout("blinksDueDay("+hide+")", 400);
}

$(document).ready(function()
{
	//setInterval(blinkDueDay, 1500);
	blinksDueDay(1);
});
</script>

<div id="loaderImg" style="visibility:visible;" class="pleaseWait2">
    <div class="isiPleaseWait tabelBorderAll">&nbsp;&nbsp;Please Wait...&nbsp;<img src="../picture/loading (115).gif" height="12"/>&nbsp;</div>
</div>

<!--<div style="position:absolute;top:2px;left:116px;z-index:2;">
	<span style="font-size:15px;font-family:sans-serif;font-weight:bold;color:#485a88;">TRANSFER TO ACCOUNTING JURNAL ENTRY</span>
</div>-->

<div id="idHalUbahProcessRet" style="position:absolute;top:9px;left:17px;">
<table cellpadding="0" cellspacing="0" width="930" style="font:0.8em sans-serif;font-weight:bold;color:#485a88;" border="0" align="center" class="">
<form method="post" action="halDetailProcessRet.php?batchno=<?php echo $batchnoGet; ?>&idMailInv=<?php echo $idMailInvGet; ?>" enctype="multipart/form-data" id="formUbahProcessRet" name="formUbahProcessRet">
<input type="hidden" id="aksi" name="aksi" value="simpanEditInvRet">
<input type="hidden" id="idMailInv" name="idMailInv" value="<?php echo $idMailInvGet; ?>">
<div id="idCekKreditAcc"><input type="hidden" id="kreditAccAdaTidak"></div>
<div id="idCekDebitAcc"><input type="hidden" id="debitAccAdaTidak"></div>
<input type="hidden" id="returnYesNo" value="no" size="10">
<input type="hidden" id="invoiceDate" name="invoiceDate" value="<?php echo $invoiceDate; ?>">
<input type="hidden" id="dueDate" name="dueDate" value="<?php echo $dueDate; ?>">
<tr>
    <td colspan="2" align="center">
    <span style="font-size:15px;font-family:sans-serif;font-weight:bold;color:#485a88;text-decoration:underline;">TRANSFER JURNAL ENTRY</span>
    </td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
    <td width="50%" valign="top">
    	<table cellpadding="0" cellspacing="0" width="465" class="tabelBorderTopJust" style="border-style:dotted;">
        <tr>
            <td width="120" height="22" class="">Batchno</td>
            <td class="elementTeks"><?php echo $batchnoGet; ?></td>
        </tr>
        <tr>
            <td height="22" class="">SNo</td>
            <td class="elementTeks" style="text-decoration:underline;"><?php echo $SNo; ?></td>
        </tr>
        <tr>
            <td height="22" class="">Date Returned</td>
            <td class="">
                <input type="text" name="dateReturned" id="dateReturned" class="elementInput" style="width:60px;" value="<?php echo $dateReturned; ?>"/>
                 <img src="../../picture/calendar.gif" class="gayaKalender" title="Select Date" onclick="displayCalendar(document.getElementById('dateReturned'),'dd/mm/yyyy',this, '', '', '193', '183');"/>&nbsp;<span class="spanKalender">(DD/MM/YYYY)</span>
            </td>
        </tr>
        <tr>
            <td height="22" class="">Ignored JE</td>
            <td class=""><input type="checkbox" id="ignoredJe" name="ignoredJe" style="cursor:pointer;">&nbsp;<span id="ignoredJeNo" style="position:relative;bottom:3px;" class="elementTeks">NO</span></td>
        </tr>
        <tr>
            <td height="22" class="">Approve Payment</td>
            <td class=""><input type="checkbox" id="approvePayment" name="approvePayment" style="cursor:pointer;" checked>&nbsp;<span id="approvePaymentNo" style="position:relative;bottom:3px;" class="elementTeks">YES</span></td>
        </tr>
        <tr>
            <td height="22" class="">Vessel Name</td>
            <td class="">
                <select id="vesselName" name="vesselName" class="elementMenu" style="width:200px;<?php echo $bgColor; ?>" <?php echo $dis; ?>>
                    <option value=""></option>
                    <?php echo $vesselName; ?>
                 </select>
            </td>
        </tr>
        <tr>
            <td height="22" class="">Source</td>
            <td class="">
                <input type="text" name="source" id="source" class="elementInput" style="width:60px;" value="MMN" maxlength="3"/>
            </td>
        </tr>
        <tr>
            <td height="22">Debit Account</td>
            <td valign="middle">
                <input type="text" name="debitAcc" id="debitAcc" class="elementInput" style="width:60px;" value="" maxlength="5"/>
                <span id="spanDebitAccName" class="elementTeks" style="font-family:'Arial Narrow';">&nbsp;</span>
            </td>
        </tr>
        <tr>
            <td height="22" class="">&nbsp;&nbsp;&nbsp;List Account</td>
            <td class="elementTeks">
            	<div style="position:relative;float:left;width:27px;">
            	<button type="button" class="btnStandar" id="btnCariDebAcc" title="" onclick="listLoad1();klikBtnCariDebAcc();">
                    <table cellpadding="0" cellspacing="0" width="24" height="20">
                    <tr>
                        <td align="center" valign="bottom"><img src="../picture/magnifier.png" height="14" /></td>
                    </tr>
                    </table>
                </button>
                </div>
                <div id="listLoading1" style="position:relative;float:left;margin-top:3px;display:none;"><img src="../../picture/ajax-loader7.gif" height="14"/></div>
                <div id="hasilDebitAcc" style="display:none;">
                <select id="menuDebitAcc" class="elementMenu" style="width:290px;height:22px;" onchange="pilihMenuDebitAcc(this.value);">
                </select>
                </div>
            </td>
        </tr>
        <tr>
            <td height="22" class="">Sub Code</td>
            <td class="">
                <input type="text" name="subCode" id="subCode" class="elementInput" style="width:60px;" value="" maxlength="5"/>
            </td>
        </tr>
        <tr valign="top">
            <td height="44" class="">Description</td>
            <td class="">
                <textarea id="description" name="description" class="elementInput" cols="52" style="height:37px;" onkeyup="textCounter(this, sisaDesc, 70);"><?php echo $description; ?></textarea>
                <input disabled="disabled" readonly type="text" name="sisaDesc" value="70" style="width:23px"></td>
        </tr>
        <tr>
            <td height="22" class="">Credit Account</td>
            <td class="elementTeks">
            <?php
            /*$disBtnCariCredAcc = "";
			$classBtnCariCredAcc = "";
            if($kreditAccount == "")
            {
                $disBtnCariCredAcc = "";
				$classBtnCariCredAcc = "btnStandar";
                echo ""*/
                ?>
                <span id="senderVendorCode2"><input type="text" name="kreditAcc" id="kreditAcc" class="elementInput" style="width:60px;" maxlength="5"/></span>
                <span id="spanKreditAccName" style="font-family:'Arial Narrow';">&nbsp;</span>
             <?php
                /*"";
            }
            if($kreditAccount != "")
            {
                $disBtnCariCredAcc = "disabled";
				$classBtnCariCredAcc = "btnStandarDis";
                echo "<input type=\"text\" name=\"kreditAcc\" id=\"kreditAcc\" value=\"".$kreditAccount."\" style=\"display:none;\"/>".$kreditAccount."&nbsp;";
            }*/
            ?>
            </td>
        </tr>
        <tr>
            <td height="22" class="">&nbsp;&nbsp;&nbsp;List Account</td>
            <td class="elementTeks">
            <div style="position:relative;float:left;width:27px;">
            	<button type="button" class="btnStandar" id="btnCariKredAcc" title="" onclick="listLoad();klikBtnCariKredAcc();">
                    <table cellpadding="0" cellspacing="0" width="24" height="20">
                    <tr>
                        <td align="center" valign="bottom"><img src="../picture/magnifier.png" height="14" /></td>
                    </tr>
                    </table>
                </button>
			</div>
            <div id="listLoading" style="position:relative;float:left;margin-top:3px;display:none;"><img src="../../picture/ajax-loader7.gif" height="14"/></div>
            <div id="hasilKreditAcc" style="display:none;">
                <select id="menuKreditAcc" class="elementMenu" style="width:290px;height:22px;" onchange="pilihMenuKreditAcc(this.value);">
                </select>
                </div>
            </td>
        </tr>
        
        <!--<tr>
            <td height="22" class="">Sender/Vendor</td>
            <td class="elementTeks"><?php echo $senderVendor; ?>&nbsp;</td>
        </tr>
        <tr>
            <td height="22" class="">Sender/Vendor</td>
            <td class="elementTeks">
				<input type="text" class="elementInput" id="senderVendor" name="senderVendor" style="width:275px;" onkeypress="return changeToUpperCase(event,this);" onkeyup="maxChar(this, 200);">
                 <input type="hidden" id="urutSendSelect" style="width:10px;text-align:right;" value="0">
                <input type="hidden" class="elementMenu" id="senderVendorCode" name="senderVendorCode" style="width:100px;">
                <div id="autoCompSender" class="overout" style="position:absolute;display:none;z-index:10;width:287px;height:190px;overflow:auto;border-color:#333;"></div>
                
            </td>
        </tr>-->
        
        <tr>
            <td height="22">Voucher</td>
            <td valign="middle">
                <input type="text" name="voucher" id="voucher" class="elementInput" style="width:60px;" value="" maxlength="5"/>
            </td>
        </tr>
        <tr>
            <td height="22">Reference</td>
            <td valign="middle">
                <input type="text" name="reference" id="reference" class="elementInput" style="width:60px;" value="" maxlength="5"/>
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        </table>
    </td>
    
    
    <td valign="top">
    	<table cellpadding="0" cellspacing="0" width="465" class="tabelBorderBottomRightNull" style="border-style:dotted;">
        <tr>
            <td width="120" height="22" class="">&nbsp;Addressee</td>
        </tr>
        <tr>
            <td height="22" class="">&nbsp;&nbsp;&nbsp;&nbsp;Company</td>
            <td class="elementTeks"><?php echo $company; ?>&nbsp;</td>
        </tr>
        <tr>
            <td height="22" class="">&nbsp;&nbsp;&nbsp;&nbsp;Unit</td>
            <td class="elementTeks"><?php echo $unitt; ?>&nbsp;</td>
        </tr>
        <tr>
            <td height="22" class="">&nbsp;Barcode</td>
            <td class="elementTeks"><?php echo $barcode; ?>&nbsp;</td>
        </tr>
        <tr>
            <td height="22" class="">&nbsp;Date</td>
            <td class="elementTeks"><?php echo $invoiceDate; ?>&nbsp;</td>
        </tr>
        <tr>
            <td height="22" class="">&nbsp;Journal Date</td>
            <td class="elementTeks">
            <input type="text" name="tglJurnal" id="tglJurnal" class="elementInput" style="width:60px;" value="<?php echo $invoiceDate; ?>"/>
                 <img src="../../picture/calendar.gif" class="gayaKalender" title="Select Date" onclick="displayCalendar(document.getElementById('tglJurnal'),'dd/mm/yyyy',this, '', '', '193', '183');"/>&nbsp;<span class="spanKalender">(DD/MM/YYYY)</span>
            
            </td>
        </tr>
        <tr>
            <td height="22" class="">&nbsp;Due days</td>
            <td class="elementTeks">
            <input type="text" name="dueDayRet" id="dueDayRet" class="elementInput" style="width:20px;height:13px;line-height:13px;" maxlength="3" onkeyup="pilihDueDayRet();" value="<?php echo $dueDay; ?>"/>&nbsp;<span class="spanKalender" style="">(Day)</span>
            </td>
        </tr>
        <tr>
            <td height="22" class="">&nbsp;Due Date</td>
            <td class="elementTeks"><span id="spanDueDate"><?php echo $dueDate; ?>&nbsp;&nbsp;<span id="idSpanDueDay" class="spanKalender" style="position:static;font-size:12px;color:#900;font-size:11px;">( <?php echo $dueDayFromNow; ?>&nbsp;Day(s) Left )</span>&nbsp;</span></td>
        </tr>
        
        <!--<tr>
            <td height="22" class="">&nbsp;Mail/Invoice No</td>
            <td class="elementTeks"><?php echo $noInvoice; ?>&nbsp;</td>
        </tr>-->
        <tr>
            <td height="22" class="">&nbsp;Mail/Invoice No</td>
            <td class="elementTeks">
				<input type="text" class="elementInput" id="mailInvNo" name="mailInvNo" style="width:275px;">
            </td>
        </tr>
        
        <tr>
            <td height="22" class="">&nbsp;Currency</td>
            <td class="elementTeks"><?php echo $currency; ?>&nbsp;</td>
        </tr>
        <tr>
            <td height="22" class="">&nbsp;Amount
          	</td>
            <td class="elementTeks"><?php echo $amount; ?>&nbsp;
          	</td>
        </tr>
        <tr>
            <td height="22" class="">&nbsp;&nbsp;&nbsp;&nbsp;Deduction</td>
            <td class="elementTeks">
                <input type="text" name="deducRet" id="deducRet" class="elementInput" style="width:100px;text-align:right;" onKeyUp="hanyaAngkaDeduc();return false;"/>
            </td>
        </tr>
        <tr>
            <td height="22" class="">&nbsp;&nbsp;&nbsp;&nbsp;Additional</td>
            <td class="elementTeks">
            	<input type="text" name="addiRet" id="addiRet" class="elementInput" style="width:100px;text-align:right;" onKeyUp="hanyaAngkaAddi();return false;"/>
            </td>
        </tr>
        <tr valign="top">
            <td height="44" class="">&nbsp;&nbsp;&nbsp;&nbsp;Reason</td>
            <td class="elementTeks">
            	 <textarea id="reasonDeduc" name="reasonDeduc" class="elementInput" cols="52" style="height:47px;" onkeyup="textCounter(this, sisaReason, 70);"></textarea>
                <input type="text" name="" id="sisaReason" class="elementInput" style="width:13px;height:13px;line-height:13px;" value="70" disabled readonly/>
            </td>
        </tr>
        <tr valign="top">
            <td height="22" class="">&nbsp;Remark</td>
            <td class="elementTeks"><?php echo $remark; ?>&nbsp;</td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>
        </table>
    </td>
</tr>
<tr>
    <td width="50%" height="25" class="tabelBorderTopJust" style="padding-top:2px;border-style:dotted;">
        <div id="idErrorMsg" class="errorMsgInv" style="visibility:hidden;height:20px;"><img src="../picture/exclamation-red.png"/>&nbsp;<span>&nbsp;</span>&nbsp;</div>
    </td>
    <td class="tabelBorderTopJust" style="padding-top:2px;border-style:dotted;">&nbsp;</td>
</tr>
<tr><td height="5"></td></tr>
<tr>
	<td colspan="2" align="center">
    	<button type="button" class="btnStandar" onclick="tutupDetailProcess('Y');">
            <table border="0" width="63" height="30">
            <tr>
                <td align="center" width="20"><img src="../picture/door-open-out.png"/></td>
                <td align="left">CLOSE</td>
            </tr>
            </table>
        </button>
        <button type="button" class="btnStandar" onclick="pilihBtnSaveRet();">
            <table width="84" height="30">
            <tr>
                <td align="center" width="20"><img src="../picture/disk-black.png"/></td>
                <td align="left">TRANSFER</td>
            </tr>
            </table>
        </button>
        <button type="button" class="btnStandar" onclick="formUbahProcessRet.reset(); fungsiReset();">
            <table width="53" height="30">
            <tr>
                <td align="center" width="20"><img src="../picture/arrow-return-180-left.png"/></td>
                <td align="left">RESET</td>
            </tr>
            </table>
        </button>
    </td>
</tr>
</form>
</table>
</div> 

<script>
window.onload = function()
{
	doneWait();
	panggilEnableLeftClick();
}
<?php
if($tutupWindow == "ya")
{
	echo "tutupDetailProcess('N');";
}

if($alertCompany == "Y")
{
	echo "alert('TRANSFER FAILED, Company doesnt exist in the system');";
}
?>
</script>
</HTML>