<!DOCTYPE HTML>
<?php
require_once("../configInvReg.php");

$batchnoGet = $_GET['batchno'];
$idMailInvGet = $_GET['idMailInv'];
?>

<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>
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
if($aksiPost  == "cancelInvRet")
{
	$CKoneksiInvReg->mysqlQuery("UPDATE mailinvoice SET addi=0.00, deduc=0.00, invret=0, saveinvret='N', revisiret='Y' WHERE idmailinv='".$idMailInvGet."' AND deletests=0");
	//$CKoneksiInvReg->mysqlQuery("DELETE FROM tblsplit WHERE idmailinv='".$idMailInvGet."' AND userid='".$userIdLogin."';");
	$querySplit = $CKoneksiInvReg->mysqlQuery("SELECT * FROM tblsplit WHERE idmailinv='".$idMailInvGet."' AND userid=".$userIdLogin.";",$CKoneksiInvReg->bukaKoneksi());
	while($rowSplit = $CKoneksiInvReg->mysqlFetch($querySplit))
	{		
		$CKoneksiInvReg->mysqlQuery("INSERT INTO tblsplittemp (idmailinv, userid, fieldaksi, urutan, pay, booksts, account, amount, description, addusrdt) VALUES ('".$idMailInvGet."', '".$userIdLogin."', '".$rowSplit['fieldaksi']."', '".$rowSplit['urutan']."', '".$rowSplit['pay']."', '".$rowSplit['booksts']."', '".$rowSplit['account']."', '".$rowSplit['amount']."', '".substr($CPublic->ms_escape_string( $rowSplit['description'] ), 0, 70)."', '".$rowSplit['addusrdt']."')");
	}
	$CKoneksiInvReg->mysqlQuery("DELETE FROM tblsplit WHERE idmailinv='".$idMailInvGet."' AND userid='".$userIdLogin."';");
	
	echo ("<body onLoad=\"doneWait();\">");
	$tutupWindow = "ya";
}

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
	$amount = number_format($CInvReg->detilMailInv($idMailInvGet, "amount"), 2, ".", ",");

$currency  = $CInvReg->detilMailInv($idMailInvGet, "currency");
$remark = $CInvReg->detilMailInv($idMailInvGet, "remark");
$SNo = $CInvReg->detilMailInv($idMailInvGet, "urutan");

$dateReturned = $CPublic->convTglNonDB( $CPublic->waktuSek() );
$ignoredJe = $CInvReg->detilMailInv($idMailInvGet, "ignoreje")=="1"?"YES":"NO";
$approvePayment = $CInvReg->detilMailInv($idMailInvGet, "apprpayment")=="1"?"YES":"NO";
$vesselName = $CInvReg->detilVessel($CInvReg->detilMailInv($idMailInvGet, "vescode"), "Fullname");
$source = $CInvReg->detilMailInv($idMailInvGet, "source");
$debitAcc = $CInvReg->detilMailInv($idMailInvGet, "debitacc");

$splitDebAcc = $CInvReg->detilMailInv($idMailInvGet, "debitsplit")=="Y"?"YES":"NO";
$warnaDebAcc = $splitDebAcc == "YES"?"#900;":"#485a88;";
$disBtnView = $splitDebAcc == "YES"?"enabled":"disabled";
$classBtnView = $splitDebAcc == "YES"?"btnStandar":"btnStandarDis";

$debitAccName = $CPublic->ms_escape_string( $CInvReg->detilAcctCode($debitAcc, "Acctname") );  
$description = $CInvReg->detilMailInv($idMailInvGet, "description");
$subCode = $CInvReg->detilMailInv($idMailInvGet, "subcode");
$kreditAcc = $CInvReg->detilMailInv($idMailInvGet, "kreditacc");
$kreditAccName = $CPublic->ms_escape_string( $CInvReg->detilAcctCode($kreditAcc, "Acctname") );  
$voucher = $CInvReg->detilMailInv($idMailInvGet, "voucherje");
$reference = $CInvReg->detilMailInv($idMailInvGet, "referenceje");
$journalDate = $CPublic->convTglNonDB( $CInvReg->detilMailInv($idMailInvGet, "tgljurnal") );
$dueDays = $CInvReg->detilMailInv($idMailInvGet, "dueday");
$noInvoice = $CInvReg->detilMailInv($idMailInvGet, "mailinvno");

$amount = $CInvReg->detilMailInv($idMailInvGet, "amount");
$deduction = $CInvReg->detilMailInv($idMailInvGet, "deduc");
$additional = $CInvReg->detilMailInv($idMailInvGet, "addi");
$totalCredit = ($amount - $deduction) + $additional;
?>
<style>
body {background-color: #f9f9f9;}
</style>

<div id="loaderImg" style="visibility:visible;" class="pleaseWait2">
    <div class="isiPleaseWait tabelBorderAll">&nbsp;&nbsp;Please Wait...&nbsp;<img src="../picture/loading (115).gif" height="12"/>&nbsp;</div>
</div>

<div id="idHalUbahProcessRet" style="position:absolute;top:9px;left:15px;border:solid 0px #666;z-index:auto;">
<table cellpadding="0" cellspacing="0" width="930" height="545" style="font:0.8em sans-serif;font-weight:bold;color:#485a88;" border="0" align="center" class="">
<form method="post" action="halDetailProcessRetYes.php?batchno=<?php echo $batchnoGet; ?>&idMailInv=<?php echo $idMailInvGet; ?>" enctype="multipart/form-data" id="formCancelRet" name="formCancelRet">
<input type="hidden" id="aksi" name="aksi" value="cancelInvRet">
<input type="hidden" id="idMailInv" name="idMailInv" value="<?php echo $idMailInvGet; ?>">
<tr>
    <td colspan="2" align="center">
    <span style="font-size:15px;font-family:sans-serif;font-weight:bold;color:#485a88;text-decoration:underline;">DETAIL JURNAL ENTRY</span> <span id="tess"></span>
    </td>
</tr>
<tr><td height="5"></td></tr>
<tr>
    <td width="50%" valign="top">
    	<table cellpadding="0" cellspacing="0" width="435" class="tabelBorderTopJust" style="border-style:dotted;">
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
            <td class="elementTeks"><?php echo $dateReturned; ?></td>
        </tr>
        <tr>
            <td height="22" class="">Ignored JE</td>
            <td class="elementTeks"><?php echo $ignoredJe; ?></td>
        </tr>
        <tr>
            <td height="22" class="">Approve Payment</td>
            <td class="elementTeks"><?php echo $approvePayment; ?></td>
        </tr>
        <tr>
            <td height="22" class="">Vessel Name</td>
            <td class="elementTeks"><?php echo $vesselName; ?></td>
        </tr>
        <tr>
            <td height="22" class="">Source</td>
            <td class="elementTeks"><?php echo $source; ?></td>
        </tr>
        <tr>
            <td height="22">Debit Account</td>
            <td class="elementTeks" valign="middle"><?php echo $debitAcc." ( ".$debitAccName." )"; ?></td>
        </tr>
        <tr>
            <td height="22" class="">&nbsp;&nbsp;&nbsp;Split Account</td>
            <td style="position:inherit;"><span id="splitDebAccNo" class="elementTeks" style="position:relative;bottom:3px;color:<?php echo $warnaDebAcc; ?>"><?php echo $splitDebAcc; ?></span>
            	<button type="button" class="<?php echo $classBtnView; ?>" id="btnViewSplitDeb" title="SPLIT DEBIT ACCOUNT" style="position:absolute;left:170px;width:62px;height:20px;" onclick="klikBtnViewDebYes();" <?php echo $disBtnView; ?>>
                    <table cellpadding="0" cellspacing="0" width="100%" height="100%">
                    <tr>
                    	<td align="center" width="22"><img src="../picture/eye--exclamation.png"/></td>
                        <td align="center">VIEW</td>
                    </tr>
                    </table>
                </button>
            </td>
        </tr>
        <tr>
            <td height="23" class="">Sub Code</td>
            <td class="elementTeks"><?php echo $subCode; ?></td>
        </tr>
        <tr valign="top">
            <td height="22" class="">Description</td>
            <td class="elementTeks"><?php echo $description; ?>&nbsp;</td>
        </tr>
        <tr>
            <td height="22" class="">Credit Account</td>
            <td class="elementTeks"><?php echo $kreditAcc." ( ".$kreditAccName." )"; ?></td>
        </tr>
        <tr>
            <td height="22">Voucher</td>
            <td class="elementTeks"><?php echo $voucher; ?></td>
        </tr>
        <tr>
            <td height="22">Reference</td>
            <td class="elementTeks"><?php echo $reference; ?></td>
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
    	<table cellpadding="0" cellspacing="0" width="495" class="tabelBorderBottomRightNull" style="border-style:dotted;">
        <tr>
            <td width="130" height="22" class="">&nbsp;Journal Date</td>
            <td width="363" class="elementTeks"><?php echo $journalDate; ?></td>
        </tr>
        <tr>
            <td height="22" class="">&nbsp;Due days</td>
            <td class="elementTeks"><?php echo $dueDays; ?></td>
        </tr>
        <tr>
            <td height="22" class="">&nbsp;Due Date</td>
            <td class="elementTeks"><span id="spanDueDate"><?php echo $dueDate; ?>&nbsp;&nbsp;<span id="idSpanDueDay" class="spanKalender" style="position:static;font-size:12px;color:#900;font-size:11px;">( <?php echo $dueDayFromNow; ?>&nbsp;Day(s) Left )</span>&nbsp;</span></td>
        </tr>
        <tr>
            <td height="22" class="">&nbsp;Mail/Invoice No</td>
            <td class="elementTeks"><?php echo $noInvoice; ?></td>
        </tr>
        
        <tr>
            <td height="22" class="">&nbsp;Currency</td>
            <td class="elementTeks"><?php echo $currency; ?>&nbsp;</td>
        </tr>
        <tr>
            <td height="22" class="">&nbsp;Amount</td>
            <td class="elementTeks"><?php echo number_format($amount, 2, ".", ","); ?>&nbsp;
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
            	<table cellpadding="0" cellspacing="0" width="480" style="border:solid 0px #666;font-size:12px;">
                <tr align="center" style="background-color:#F2FAFF;">
                	<td height="20" width="62" class="tabelBorderBottomRightNull">Account</td>
                    <td width="129" class="tabelBorderTopJust">Amount</td>
                    <td width="257" class="tabelBorderBottomLeftNull">Description</td>
                </tr>
                <?php
				for($i=1;$i<=5;$i++) 
				{
					$deducAcc = $CInvReg->detilSplit($idMailInvGet, $userIdLogin, "deduction", $i, "account");
					$amountt = number_format( $CInvReg->detilSplit($idMailInvGet, $userIdLogin, "deduction", $i, "amount"), 2, ",", ".");
					$deducAmt = $amountt=="0,00"?"":$amountt;
					$deducDesc = $CInvReg->detilSplit($idMailInvGet, $userIdLogin, "deduction", $i, "description");
					?>

<tr>
    <td align="left" height="22">
        <input type="text" id="deducAcc<?php echo $i; ?>" name="deducAcc<?php echo $i; ?>" class="elementInput3" maxlength="5" style="width:99%;" value="<?php echo $deducAcc; ?>" readonly>
    </td>
    <td align="center">
        <input type="text" id="deducAmount<?php echo $i; ?>" name="deducAmount<?php echo $i; ?>" class="elementInput3" style="width:99%;text-align:right;" value="<?php echo $deducAmt; ?>" readonly>
    </td>
    <td align="right">
        <input type="text" id="deducReason<?php echo $i; ?>" name="deducReason<?php echo $i; ?>" class="elementInput3" maxlength="70" style="width:99.7%;" value="<?php echo $deducDesc; ?>" readonly>
    </td>
</tr>
				<?php
				}
				?>
                </table>
            </td>
        </tr>
        <tr>
            <td height="18" class="" valign="bottom">&nbsp;Additional</td>
        </tr>
        <tr>
            <td align="right" colspan="2">
            	<table cellpadding="0" cellspacing="0" width="480" style="border:solid 0px #666;font-size:12px;">
                <tr align="center" style="background-color:#F2FAFF;">
                	<td height="20" width="30" class="tabelBorderBottomRightNull">Pay</td>
                	<td width="62" class="tabelBorderTopJust">Account</td>
                    <td width="129" class="tabelBorderTopJust">Amount</td>
                    <td width="257" class="tabelBorderBottomLeftNull">Description</td>
                </tr>
                <?php
				for($ii=1;$ii<=5;$ii++) 
				{
					$classPay = "tabelBorderLeftJust";
					if($ii == 5)$classPay = "tabelBorderTopRightNull";
					
					$addiPay = $CInvReg->detilSplit($idMailInvGet, $userIdLogin, "additional", $ii, "pay")=="Y"?"checked":"";
					$addiAcc = $CInvReg->detilSplit($idMailInvGet, $userIdLogin, "additional", $ii, "account");
					$amountAccc = number_format( $CInvReg->detilSplit($idMailInvGet, $userIdLogin, "additional", $ii, "amount"), 2, ",", ".");
					$amountAcc = $amountAccc=="0,00"?"":$amountAccc;
					$descAcc = $CInvReg->detilSplit($idMailInvGet, $userIdLogin, "additional", $ii, "description");
					
echo "
<tr>
	<td align=\"center\" class=\"".$classPay."\" style=\"background-color:#FFF;cursor:pointer;\"><input type=\"checkbox\" ".$addiPay." /></td>
	<td align=\"left\" height=\"22\"><input type=\"text\" id=\"addiAcc".$ii."\" name=\"addiAcc".$ii."\" class=\"elementInput3\" maxlength=\"5\" style=\"width:99%;\" value=\"".$addiAcc."\" ></td>
	<td align=\"center\"><input type=\"text\" id=\"addiAmount".$ii."\" name=\"addiAmount".$ii."\" class=\"elementInput3\" style=\"width:99%;text-align:right;\" value=\"".$amountAcc."\"></td>
	<td align=\"right\"><input type=\"text\" id=\"addiReason".$ii."\" name=\"addiReason".$ii."\" class=\"elementInput3\" maxlength=\"70\" style=\"width:99.7%;\" value=\"".$descAcc."\"></td>
</tr>";
				}
				?>
                </table>
            </td>
        </tr>
        </table>
    </td>
</tr>
<tr>
    <td width="50%" height="20" class="">
        <div id="idErrorMsg" class="errorMsgInv" style="visibility:hidden;"><img src="../picture/exclamation-red.png"/>&nbsp;<span>&nbsp;</span>&nbsp;</div>
    </td>
</tr>
<tr>
	<td colspan="2" height="40" align="center" class="tabelBorderTopJust" style="border-style:dotted;">
    	<button type="button" id="btnClose" class="btnStandar" style="width:100px;height:32px;" onclick="tutupDetailProcess('Y');">
            <table cellpadding="0" cellspacing="0" width="100%" height="100%">
            <tr>
            	<td align="center"><img src="../picture/door-open-out.png" style="vertical-align:middle;"/><span style="vertical-align:middle;margin-left:4px;">CLOSE</span></td>
            </tr>
            </table>
        </button>
        <button type="button" id="btnCancelTrf" class="btnStandar" style="width:127px;height:32px;display:inline;" onclick="pilihBtnCancelTrf();">
            <table cellpadding="0" cellspacing="0" width="100%" height="100%">
            <tr>
            	<td align="center"><img src="../picture/hourglass--pencil.png" style="vertical-align:middle;"/><span style="vertical-align:middle;margin-left:4px;">CANCEL TRANSFER</span></td>
            </tr>
            </table>
        </button>
    </td>
</tr>
</form>
</table>
</div> 

<div id="divCloseSplitDeb" style="position:absolute;left:465px;top:110px;z-index:4;display:none;">
<button type="button" class="btnStandar" id="btnCloseSplitDeb" title="" onclick="klikBtnCloseSplitDebYes();">
    <table cellpadding="0" cellspacing="0" width="22" height="19">
    <tr>
        <td align="center" valign="bottom"><img src="../picture/cross.png" height="14" /></td>
    </tr>
    </table>
</button>
</div>

<div id="divKontenSplitDeb" style="position:absolute;left:0px;top:106px; z-index:3;width:490px;background-color:#FFC;border-width:2px; border-color:#FC0;display:none;" class="tabelBorderAll">
<table cellpadding="0" cellspacing="0" width="475" height="350" align="center" style="font:12px sans-serif;font-weight:bold;color:#485a88;">
<tr>
    <td height="20"><span style="font-style:italic;text-decoration:underline;">SPLIT FOR DEBIT ACCOUNT >></span></td>
</tr>
<tr>
	<td height="30">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total (CR) : <span id="spanTotalCredAmt" style="font-size:12px;color:#333;"><?php echo number_format($amount, 2, ".", ","); ?></span>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Balance : <span id="spanBalance" style="font-size:12px;color:#333;">0</span>
    </td>
</tr>
<tr>
	<td valign="top">
    	<div id="divIframeList" style="position:inherit; border: solid 1px #CCC; top:0px; left:0px;text-align:left;width:475px;height:297px;z-index:1;">        
    		<iframe width="100%" height="100%" src="../templates/halSplitDebListYes.php?idMailInv=<?php echo $idMailInvGet; ?>" target="iframeSplitDebList" name="iframeSplitDebList" id="iframeSplitDebList" frameborder="0" marginwidth="0" marginheight="0" scrolling="yes" class="" style="z-index:0;"></iframe>
        </div>
    </td>
</tr>
<tr>
	<td height="30">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total (DB) : <span id="spanTotalDebAmt" style="font-size:12px;color:#333;">&nbsp;</span>
    </td>
</tr>
</table>
</div>

<script>
window.onload = function()
{
	
	doneWait();
	panggilEnableLeftClick();
	
	$("#btnClose").css("width","100px");
	$("#btnCancelTrf").css("display","none");
	var userJenisInvReg = "<?php echo $userJenisInvReg; ?>";
	if(userJenisInvReg == "admin")
	{
		$("#btnCancelTrf").css("display","inline");
		$("#btnClose").css("width","65px");
	}
}
<?php
if($tutupWindow == "ya")
{
	echo "tutupDetailProcess('N');";
}
?>
</script>
</HTML>