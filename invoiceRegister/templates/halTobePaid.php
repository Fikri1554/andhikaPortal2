<!DOCTYPE HTML>
<?php
require_once("../configInvReg.php");

$batchnoGet = $_GET['batchno'];
$idMailInvGet = $_GET['idMailInv'];
?>

<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<!-- <script type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script> -->
<!-- <script type="text/javascript" src="../../js/JavaScriptUtil.js"></script> -->
<!-- <script type="text/javascript" src="../../js/Parsers.js"></script> -->
<script type="text/javascript" src="../../js/InputMask.js"></script>
<script type="text/javascript" src="../js/masks.js"></script>
<script type="text/javascript" src="../js/invReg.js"></script>
<script type="text/javascript" src="../js/tambahMail.js"></script>

<!-- <link rel="stylesheet" type="text/css" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK> -->
<link rel="stylesheet" type="text/css" href="../../css/archives.css"> 
<link href="../css/invReg.css" rel="stylesheet" type="text/css"> 
<link rel="stylesheet" type="text/css" href="../css/button.css"/>
<link rel="stylesheet" type="text/css" href="../css/table.css"/>

<style>
body {background-color: #f9f9f9;}
</style>

<?php
if($aksiPost == "simpanRubah")
{
    $txtAdd = str_replace(",","",$_POST['txtAdditional']);
    $txtDeduc = str_replace(",","",$_POST['txtDeduction']);
    $slcBankCode = $_POST['slcBankCode'];
    $remarkDeduction = $_POST['remarkDeduction'];

    $transNo = ($CInvReg->lastTransNo()+1);

    $setBank = "";

    if($slcBankCode != "")
    {
        $setBank = ",paytype = 'transfer',bankcode = '".$slcBankCode."' ";
    }
    
    $CKoneksiInvReg->mysqlQuery("UPDATE mailinvoice SET st_tobepaid='Y', addi='".$txtAdd."', deduc='".$txtDeduc."', reasondeduc='".$remarkDeduction."', preparepayment='Y', datepreppay=now(), preppayby='".$userIdLogin."', transno='".$transNo."' ".$setBank." WHERE idmailinv=".$idMailInvGet." AND deletests=0 ;");
    
    $CKoneksiInvReg->mysqlQuery("UPDATE tbllasttransno SET lasttransno='".$transNo."';");

    echo ("<body onLoad=\"doneWait();\">");
    $tutupWindow = "ya";
}
$bankNameOpt = $CInvReg->bankCodeMenu("");
$senderVendor = "";
$tipeSenderVen = $CInvReg->detilMailInv($idMailInvGet, "tipesenven");
if($tipeSenderVen == "1")
    $senderVendor = $CInvReg->detilMailInv($idMailInvGet, "sendervendor1");
    
if($tipeSenderVen == "2")
    $senderVendor = $CInvReg->detilMailInv($idMailInvGet, "sendervendor2name");

$senderVendorCode = $CInvReg->detilMailInv($idMailInvGet, "sendervendor2");

$company = $CInvReg->detilMailInv($idMailInvGet, "companyname");
$unit = $CInvReg->detilMailInv($idMailInvGet, "unitname");
$barcode = $CInvReg->detilMailInv($idMailInvGet, "barcode");
$invoiceDate = $CPublic->convTglNonDB( $CInvReg->detilMailInv($idMailInvGet, "tglinvoice") );
$dueDate = "";
if($CInvReg->detilMailInv($idMailInvGet, "tglexp") != "0000-00-00")
{
    $dueDate = $CPublic->convTglNonDB( $CInvReg->detilMailInv($idMailInvGet, "tglexp") );
}
    
$noInvoice = $CInvReg->detilMailInv($idMailInvGet, "mailinvno");

$amount = "";
if($CInvReg->detilMailInv($idMailInvGet, "amount") != "0.00")
{
    $amount = $CPublic->jikaKosongStrip(number_format((float)$CInvReg->detilMailInv($idMailInvGet, "amount"), 2, '.', ','));
}
    
$currency  = $CInvReg->detilMailInv($idMailInvGet, "currency");
$fileNya = $CInvReg->detilMailInv($idMailInvGet, "file_upload");

$dis = "";
$bgColor = "";
if(substr($barcode, 0, 1) == "S")
{
    $dis = "disabled";
    $bgColor = "background-color:#E9E9E9;";
}

?>
<style>
body {background-color: #f9f9f9;}
</style>

<script>
    function angkaSaja(idForm)
    {
        amountMask = new Mask("#,###.##", "number");
        amountMask.attach(document.getElementById(idForm));
    }
    function viewImg(urlImg)
    {
        var myWindow = window.open("./fileUpload/"+urlImg, "_blank");
    }
    function saveDataNya()
    {
        var answer  = confirm('Are you sure want to Save?');
        if(answer)
        {
            formTambahTobePaid.submit();
        }
    }
window.onload = function()
{
    doneWait();
    <?php
    $sure = "Y";
    if($userJenisInvReg == "user")
    {   // TOMBOL SAVE DISABLED JIKA SUDAH ACKNOWLEDGE DAN AKSES EDIT TIDAK BOLEH
        if($CInvReg->detilMailInv($idMailInvGet, "ack") == 1 OR $CInvReg->aksesInvReg($userIdSession, "btnincoming_edit") == "disabled")
        {
            echo "disabledBtn('btnSave')";
            $sure = "N";
        }
    }
    ?>
    setup();
}
</script>

<div id="loaderImg" style="visibility:hidden;" class="pleaseWait2">
    <div class="isiPleaseWait tabelBorderAll">&nbsp;&nbsp;Please Wait...&nbsp;<img src="../picture/loading (115).gif" height="12"/>&nbsp;</div>
</div>

<div id="idHaTambahMailInv">
    <table cellpadding="0" cellspacing="0" width="540" height="490" style="font:0.8em sans-serif;font-weight:bold;color:#485a88;" border="0" align="center">
    <form method="post" action="halTobePaid.php?batchno=<?php echo $batchnoGet; ?>&idMailInv=<?php echo $idMailInvGet; ?>" enctype="multipart/form-data" id="formTambahTobePaid" name="formTambahTobePaid">
    <input type="hidden" id="aksi" name="aksi" value="simpanRubah">
    <input type="hidden" id="batchno" name="batchno" value="<?php echo $batchnoGet; ?>">
    <input type="hidden" id="idMailInv" value="<?php echo $idMailInvGet; ?>">

    <tr>
        <td valign="top" align="center" height="50" colspan="2"><span style="font-size:16px;">TO BE PAID</span></td>
    </tr>
    <tr>
        <td height="400" valign="top">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" style="font:1em sans-serif;font-weight:bold;color:#485a88;">
            <tr valign="middle">
                <td height="22" class="">Batchno</td>
                <td class="elementTeks"><?php echo $batchnoGet; ?></td>
            </tr>
            <tr valign="top">
                <td height="22" class="">Barcode</td>
                <td class="elementTeks"><?php echo $barcode; ?></td>
            </tr>
            <tr valign="top">
                <td width="140" height="22" class="">Sender/Vendor</td>
                <td class="elementTeks"><?php echo $senderVendorCode." - ".$senderVendor; ?></td>
            </tr>
            <tr valign="top">
                <td width="140" height="22" class="">Company</td>
                <td class="elementTeks"><?php echo $company; ?></td>
            </tr>
            <tr valign="top">
                <td width="140" height="22" class="">Unit</td>
                <td class="elementTeks"><?php echo $unit; ?></td>
            </tr>
            <tr valign="top">
                <td height="22" class="">Invoice Date</td>
                <td class="elementTeks"><?php echo $invoiceDate; ?></td>
            </tr>
            <tr valign="top">
                <td height="22" class="">Due Date</td>
                <td class="elementTeks"><?php echo $dueDate; ?></td>
            </tr>
            <tr valign="top">
                <td height="22" class="">Invoice No</td>
                <td class="elementTeks"><?php echo $noInvoice; ?></td>
            </tr>
            <tr valign="top">
                <td height="22" class="">Amount</td>
                <td class="elementTeks"><?php echo $currency." - ".$amount; ?></td>
            </tr>
            <tr valign="top">
                <td height="22" class="">File</td>
                <td><a style="cursor:pointer;" onclick="viewImg('<?php echo $fileNya; ?>');" id="btnViewFile"> View</a></td>
            </tr>
            <tr valign="top">
                <td height="22" class="">Bank</td>
                <td class="">
                    <select id="slcBankCode" name="slcBankCode" class="elementMenu" style="width:350px;">
                    <option value="">-- PLEASE SELECT  --</option>
                        <?php echo $bankNameOpt; ?>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <td height="22" class="">Additional</td>
                <td class="">
                    <input type="text" id="txtAdditional" name="txtAdditional" class="elementInput" style="width:188px;text-align:right;<?php echo $bgColor; ?>" value="0" onkeyup="angkaSaja('txtAdditional');return false;">
                </td>
            </tr>
            <tr valign="top">
                <td height="22" class="">Deduction</td>
                <td class="">
                    <input type="text" id="txtDeduction" name="txtDeduction" class="elementInput" style="width:188px;text-align:right;<?php echo $bgColor; ?>" value="0" onkeyup="angkaSaja('txtDeduction');return false;">
                </td>
            </tr>
            <tr valign="top">
                <td height="70" class="">Remark Deduction</td>
                <td class="">
                    <textarea id="remarkDeduction" name="remarkDeduction" class="elementInput" rows="5" cols="51" style="height:70px;" onkeyup="textCounter(this, sisaRemarks, 200);"></textarea>
                    <input disabled="disabled" readonly type="text" name="sisaRemarks" value="200" style="width:23px">
                </td>
            </tr>
            </table>
        </td>
    </tr>
    </form>
    <tr>
    
    <tr valign="top">
        <td height="20" valign="bottom">
            <div id="idErrorMsg" class="errorMsgInv" style="visibility:hidden;"><img src="../picture/exclamation-red.png"/>&nbsp;<span>&nbsp;</span>&nbsp;</div>
        </td>
    </tr>
    
    <tr>
        <td height="30" align="center" class="tabelBorderTopJust" style="padding-top:5px;">
        <button class="btnStandar" onclick="tutupNewMail('<?php echo $sure; ?>');">
            <table border="0" width="63" height="30">
            <tr>
                <td align="center" width="20"><img src="../picture/door-open-out.png"/></td>
                <td align="left">CLOSE</td>
            </tr>
            </table>
        </button>
        <button id="btnSave" class="btnStandar" onclick="saveDataNya();">
            <table width="53" height="30">
            <tr>
                <td align="center" width="20"><img src="../picture/disk-black.png"/></td>
                <td align="left">SAVE</td>
            </tr>
            </table>
        </button>
        </td>
    </tr>    
    </table>
</div> 

<script>
<?php
if($tutupWindow == "ya")
{
    echo "tutupNewMail('N');";
}
?>
</script>
</HTML>