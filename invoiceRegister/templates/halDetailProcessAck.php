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
<script type="text/javascript" src="../js/invReg.js"></script>
<script type="text/javascript" src="../js/process.js"></script>

<link rel="stylesheet" type="text/css" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css"
    media="screen">
</LINK>
<link rel="stylesheet" type="text/css" href="../../css/archives.css">
<link href="../css/invReg.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../css/button.css" />
<link rel="stylesheet" type="text/css" href="../css/table.css" />

<style>
body {
    background-color: #f9f9f9;
}
</style>

<?php
$invReturnedYes = $CPublic->jikaParamSmDgNilai($CInvReg->detilMailInv($idMailInvGet, "invret"), "1", "YES", "NO");
$dateReturned = $CPublic->convTglNonDB( $CPublic->waktuSek() );
$ignoredJeYes = $CPublic->jikaParamSmDgNilai($CInvReg->detilMailInv($idMailInvGet, "ignoreje"), "1", "YES", "NO");
$approvePaymentYes = $CPublic->jikaParamSmDgNilai($CInvReg->detilMailInv($idMailInvGet, "apprpayment"), "1", "YES", "NO");

$kreditAccount = $CInvReg->detilMailInv($idMailInvGet, "kreditacc"); // jika sendervendor2 kosong maka $kreditAccount sama dengan kreditAcc di field database 
if($CInvReg->detilMailInv($idMailInvGet, "sendervendor2") != "")
{
	$kreditAccount = $CInvReg->detilMailInv($idMailInvGet, "sendervendor2");
}
	
$vesselName = $CInvReg->detilVessel($CInvReg->detilMailInv($idMailInvGet, "vescode"), "Fullname");
$source = $CInvReg->detilMailInv($idMailInvGet, "source");
$debitAcc = $CInvReg->detilMailInv($idMailInvGet, "debitacc");
$debitAccName = $CPublic->ms_escape_string( $CInvReg->detilAcctCode($debitAcc, "Acctname") );  
$subCode = $CInvReg->detilMailInv($idMailInvGet, "subcode");
$kreditAcc = $CInvReg->detilMailInv($idMailInvGet, "kreditacc");
$kreditAccName = $CPublic->ms_escape_string( $CInvReg->detilAcctCode($kreditAcc, "Acctname") );  

$senderVendor1  = "";
$senderVendor2  = "";
$tipeSenderVen = $CInvReg->detilMailInv($idMailInvGet, "tipesenven");

if($tipeSenderVen == "1")
	$senderVendor = $CInvReg->detilMailInv($idMailInvGet, "sendervendor1");
	
if($tipeSenderVen == "2")
	$senderVendor = $CInvReg->detilMailInv($idMailInvGet, "sendervendor2name");

$batchnoGet = $CInvReg->detilMailInv($idMailInvGet, "batchno");
$voucher = $CInvReg->detilMailInv($idMailInvGet, "voucherje");
$reference = $CInvReg->detilMailInv($idMailInvGet, "referenceje");
$description = $CInvReg->detilMailInv($idMailInvGet, "description");
$company = $CInvReg->detilMailInv($idMailInvGet, "companyname");
$unitt = $CInvReg->detilMailInv($idMailInvGet, "unitname");
$barcode = $CInvReg->detilMailInv($idMailInvGet, "barcode");
$invoiceDate = $CPublic->convTglNonDB( $CInvReg->detilMailInv($idMailInvGet, "tglinvoice") );
$journalDate = $CPublic->convTglNonDB( $CInvReg->detilMailInv($idMailInvGet, "tgljurnal") );
$dueDayFromNow = $CPublic->perbedaanHari( "'".$CInvReg->detilMailInv($idMailInvGet, "tglexp")."'" , "NOW()") ;

// Ini untuk journal Date sbelum tgl 22 Des 2015
if($journalDate == "00/00/0000")
	$journalDate = $invoiceDate;
// END - Ini untuk journal Date sbelum tgl 22 Des 2015

$dueDate = "";
if($CInvReg->detilMailInv($idMailInvGet, "tglexp") != "0000-00-00")
	$dueDate = $CPublic->convTglNonDB( $CInvReg->detilMailInv($idMailInvGet, "tglexp") );
	
$dueDays = $CInvReg->detilMailInv($idMailInvGet, "dueday");
	
$noInvoice = $CInvReg->detilMailInv($idMailInvGet, "mailinvno");

$amount = "";
if($CInvReg->detilMailInv($idMailInvGet, "amount") != "0.00")
	$amount = number_format($CInvReg->detilMailInv($idMailInvGet, "amount"), 2, ",", ".");
$deduc = "";
if($CInvReg->detilMailInv($idMailInvGet, "deduc") != "0.00")
	$deduc =number_format($CInvReg->detilMailInv($idMailInvGet, "deduc"), 2, ",", ".");
$additional = "";
if($CInvReg->detilMailInv($idMailInvGet, "addi") != "0.00")
	$additional =number_format($CInvReg->detilMailInv($idMailInvGet, "addi"), 2, ",", ".");
	
$reason = $CInvReg->detilMailInv($idMailInvGet, "reasondeduc");

$currency  = $CInvReg->detilMailInv($idMailInvGet, "currency");
$remark = $CInvReg->detilMailInv($idMailInvGet, "remark");
$SNo = $CInvReg->detilMailInv($idMailInvGet, "urutan");
?>
<style>
body {
    background-color: #f9f9f9;
}
</style>

<script>
window.onload = function() {
    doneWait();
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
    blinksDueDay(1);
});
</script>

<div id="loaderImg" style="visibility:visible;" class="pleaseWait2">
    <div class="isiPleaseWait tabelBorderAll">&nbsp;&nbsp;Please Wait...&nbsp;<img src="../picture/loading (115).gif"
            height="12" />&nbsp;</div>
</div>

<div style="position:absolute;top:8px;left:0px;width:962px;text-align:center;">
    <span style="font-size:15px;font-family:sans-serif;font-weight:bold;color:#485a88;text-decoration:underline;">DETAIL
        INVOICE</span>
</div>

<div id="idHalUbahProcessRet" style="position:absolute;top:9px;left:17px;">
    <table cellpadding="0" cellspacing="0" width="930" style="font:0.8em sans-serif;font-weight:bold;color:#485a88;"
        border="0" align="center">
        <form method="post"
            action="halDetailProcessRet.php?batchno=<?php echo $batchnoGet; ?>&idMailInv=<?php echo $idMailInvGet; ?>"
            enctype="multipart/form-data" id="formUbahProcessRet" name="formUbahProcessRet">
            <input type="hidden" id="aksi" name="aksi" value="simpanEditInvRet">
            <input type="hidden" id="idMailInv" name="idMailInv" value="<?php echo $idMailInvGet; ?>">
            <div id="idCekKreditAcc"><input type="hidden" id="kreditAccAdaTidak"></div>

            <!--<tr>
        <td valign="top" align="center" height="20" colspan="2" style="font-size:15px;">DETAIL INVOICE</td>
    </tr>-->
            <tr>
                <td height="18">&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <!-- LEFT SIDE -->
                <td width="50%" valign="top">
                    <table border="0" cellpadding="0" cellspacing="0" width="465" class="tabelBorderTopJust"
                        style="font:1em sans-serif;font-weight:bold;color:#485a88;border-style:dotted;">
                        <tr>
                            <td width="130" height="22" class="tabelBorderBottomJust" style="border-style:dotted;">
                                Batchno</td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $batchnoGet; ?></td>
                        </tr>
                        <tr>
                            <td height="22" class="tabelBorderBottomJust" style="border-style:dotted;">SNo</td>
                            <td class="tabelBorderBottomJust elementTeks"
                                style="border-style:dotted;text-decoration:underline;"><?php echo $SNo; ?></td>
                        </tr>
                        <tr>
                            <td height="22" class="tabelBorderBottomJust" style="border-style:dotted;">Inv. Returned
                            </td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $invReturnedYes; ?></td>
                        </tr>
                        <tr>
                            <td height="22" class="tabelBorderBottomJust" style="border-style:dotted;">Date Returned
                            </td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $dateReturned; ?></td>
                        </tr>
                        <tr>
                            <td height="22" class="tabelBorderBottomJust" style="border-style:dotted;">Ignored JE</td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $ignoredJeYes; ?></td>
                        </tr>
                        <tr>
                            <td height="22" class="tabelBorderBottomJust" style="border-style:dotted;">Approve Payment
                            </td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $approvePaymentYes; ?></td>
                        </tr>
                        <tr>
                            <td height="22" class="tabelBorderBottomJust" style="border-style:dotted;">Vessel Name</td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $vesselName; ?></td>
                        </tr>
                        <tr>
                            <td height="22" class="tabelBorderBottomJust" style="border-style:dotted;">Source</td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $source; ?></td>
                        </tr>
                        <tr>
                            <td height="22" class="tabelBorderBottomJust" style="border-style:dotted;">Debit Account
                            </td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $debitAcc." ( ".$debitAccName." )"; ?></td>
                        </tr>
                        <tr>
                            <td height="22" class="tabelBorderBottomJust" style="border-style:dotted;">Sub Code</td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $subCode; ?></td>
                        </tr>
                        <tr>
                            <td height="22" valign="top" class="tabelBorderBottomJust" style="border-style:dotted;">
                                Description</td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $description; ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="22" class="tabelBorderBottomJust" style="border-style:dotted;">Credit Account
                            </td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $kreditAcc." ( ".$kreditAccName." )"; ?></td>
                        </tr>
                        <tr>
                            <td height="22">Sender/Vendor</td>
                            <td class="elementTeks"><?php echo $senderVendor; ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="22">Voucher</td>
                            <td class="elementTeks"><?php echo $voucher; ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="22">Reference</td>
                            <td class="elementTeks"><?php echo $reference; ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </td>
                <!-- END LEFT SIDE -->

                <!-- RIGHT SIDE -->
                <td valign="top">
                    <table border="0" cellpadding="0" cellspacing="0" width="465" class="tabelBorderBottomRightNull"
                        style="font:1em sans-serif;font-weight:bold;color:#485a88;border-style:dotted;">
                        <tr>
                            <td colspan="2" height="22" class="tabelBorderBottomJust" style="border-style:dotted;">
                                &nbsp;Addresse</td>
                        </tr>
                        <tr>
                            <td height="22" width="120" class="tabelBorderBottomJust" style="border-style:dotted;">
                                &nbsp;&nbsp;&nbsp;Company</td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $company; ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="22" class="tabelBorderBottomJust" style="border-style:dotted;">
                                &nbsp;&nbsp;&nbsp;Unit</td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $unitt; ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="22" class="tabelBorderBottomJust" style="border-style:dotted;">&nbsp;Barcode
                            </td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $barcode; ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="22" class="tabelBorderBottomJust" style="border-style:dotted;">&nbsp;Date</td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $invoiceDate; ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="22" class="tabelBorderBottomJust" style="border-style:dotted;">&nbsp;Journal
                                Date</td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $journalDate; ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="22" class="tabelBorderBottomJust" style="border-style:dotted;">&nbsp;Due Days
                            </td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $dueDays; ?></td>
                        </tr>
                        <tr>
                            <td height="22" class="tabelBorderBottomJust" style="border-style:dotted;">&nbsp;Due Date
                            </td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $dueDate; ?>&nbsp;&nbsp;<span id="idSpanDueDay" class="spanKalender"
                                    style="position:static; font-size:12px;color:#900;font-size:11px;">(
                                    <?php echo $dueDayFromNow; ?>&nbsp;Day(s) Left )</span>&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="22" class="tabelBorderBottomJust" style="border-style:dotted;">
                                &nbsp;Mail/Invoice No</td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $noInvoice; ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="22" class="tabelBorderBottomJust" style="border-style:dotted;">&nbsp;Currency
                            </td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $currency; ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="22" class="tabelBorderBottomJust" style="border-style:dotted;">&nbsp;Amount</td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $amount; ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="22" class="tabelBorderBottomJust" style="border-style:dotted;">
                                &nbsp;&nbsp;&nbsp;Deduction</td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $deduc;?></td>
                        </tr>
                        <tr>
                            <td height="22" class="tabelBorderBottomJust" style="border-style:dotted;">
                                &nbsp;&nbsp;&nbsp;Additional</td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $additional; ?></td>
                        </tr>
                        <tr>
                            <td height="22" class="tabelBorderBottomJust" style="border-style:dotted;">
                                &nbsp;&nbsp;&nbsp;Reason</td>
                            <td class="tabelBorderBottomJust elementTeks" style="border-style:dotted;">
                                <?php echo $reason; ?></td>
                        </tr>
                        <tr>
                            <td height="22" valign="top">&nbsp;Remark</td>
                            <td class="elementTeks"><?php echo $remark; ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </td>
                <!-- END RIGHT SIDE -->
            </tr>
        </form>
        <tr>
            <td height="30" colspan="2" class="tabelBorderTopJust" style="padding-top:2px;border-style:dotted;"></td>
        </tr>
        <!--<tr>-->

        <!-- <tr>
        <td height="20" class="">&nbsp;
        	
        </td>
    </tr>
    
    <tr>
        <td height="30" align="center" class="tabelBorderTopJust" style="padding-top:5px;">
        
        </td>
    </tr>  -->
        <tr>
            <td colspan="2" align="center">
                <button class="btnStandar" onclick="tutupDetailProcess('N');">
                    <table border="0" width="63" height="30">
                        <tr>
                            <td align="center" width="20"><img src="../picture/door-open-out.png" /></td>
                            <td align="left">CLOSE</td>
                        </tr>
                    </table>
                </button>
            </td>
        </tr>
    </table>
</div>

</HTML>