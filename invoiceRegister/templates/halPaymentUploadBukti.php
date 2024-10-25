<?php require_once("../configInvReg.php"); ?>
<!DOCTYPE HTML>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="../../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../../js/Parsers.js"></script>
<script type="text/javascript" src="../../js/InputMask.js"></script>
<script type="text/javascript" src="../js/invReg.js"></script>

<link rel="stylesheet" type="text/css" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK>
<link rel="stylesheet" type="text/css" href="../../css/archives.css"> 
<link href="../css/invReg.css" rel="stylesheet" type="text/css"> 
<link rel="stylesheet" type="text/css" href="../css/button.css"/>
<link rel="stylesheet" type="text/css" href="../css/table.css"/>
<?php
$transNo = $_GET['transNo'];
$aksiGet = $_GET['aksi'];

if($aksiPost == "updateUpload")
{
    $transNo = (int) $_POST['txtTransNo'];
    $remark = $_POST['txtRemark'];

    if($_FILES['fileUploadNya']['name'] != "")
    {
        $tmpFile = $_FILES['fileUploadNya']['tmp_name'];
        $fileName = $_FILES['fileUploadNya']['name'];
        $dir = "./fileBuktiBayar/";
        $newFileName = "buktiBayar_".$transNo;

        $dt = explode(".", $fileName);
        $newFileName = str_replace(array(' ','/','.',',','-'), '', $newFileName).".".trim($dt[count($dt)-1]);

        move_uploaded_file($tmpFile, $dir."/".$fileName);
        rename($dir."/".$fileName, $dir."/".$newFileName);

        $fileNameNya = $newFileName;

        $CKoneksiInvReg->mysqlQuery("UPDATE mailinvoice SET buktibayar_file = '".$fileNameNya."', buktibayar_remark = '".$remark."', buktibayar_addUserId = '".$userIdLogin."', buktibayar_addDate = '".date('Y-m-d')."' WHERE transno = ".$transNo);
    }

    // echo "<script>parent.$('#formPaymentBatch').submit();</script>";
    echo "<script>parent.closeFormUploadTransfer();</script>";
}

if($aksiGet == "viewUploadBukti")
{
    $query = $CKoneksiInvReg->mysqlQuery("SELECT sendervendor1, sendervendor2name, companyname, currency, SUM( amount ) AS amount, SUM( addi ) AS addi, SUM( deduc ) AS deduc FROM mailinvoice WHERE deletests=0 AND transno = ".$transNo." ;", $CKoneksiInvReg->bukaKoneksi());
    $data = $CKoneksiInvReg->mysqlFetch($query);

    $cmpName = $data['companyname'];
    $senderVendor = $data['sendervendor1'];
    if($senderVendor == "" )
    {
        $senderVendor = $data['sendervendor2name'];
    }
    
    $amountNya = ($data['amount'] + $data['addi']) - $data['deduc'];

    $curr = $data['currency'];
}

?>
<style>
body {background-color: #f9f9f9;}
</style>

<script>
   
</script>

<div id="loaderImg" style="visibility:hidden;" class="pleaseWait2">
    <div class="isiPleaseWait tabelBorderAll">&nbsp;&nbsp;&nbsp;Please Wait...&nbsp;<img src="../picture/loading (115).gif" height="12"/>&nbsp;</div>
</div>

<div>
<!--<textarea id="taTes"></textarea>-->
    <table cellpadding="0" cellspacing="0" width="540" height="490" style="font:0.8em sans-serif;font-weight:bold;color:#485a88;" border="0" align="center">
    <form method="post" action="halPaymentUploadBukti.php" enctype="multipart/form-data" id="formUpdateBuktiBayar" name="formUpdateBuktiBayar">
    <input type="hidden" id="aksi" name="aksi" value="updateUpload">
    <input type="hidden" id="txtTransNo" name="txtTransNo" value="<?php echo $transNo; ?>">

    <tr>
        <td valign="top" align="center" height="50" colspan="2"><span style="font-size:16px;">UPLOAD BUKTI TRANSFER</span></td>
    </tr>
    <tr>
        <td height="400" valign="top">
            <table border="0" cellpadding="0" cellspacing="10" width="100%" style="font:1em sans-serif;font-weight:bold;color:#485a88;">
             <tr valign="top">
                <td height="22">COMPANY</td>
                <td style="color:#000;"><?php echo $cmpName; ?></td>
            </tr>
            <tr valign="top">
                <td height="22">SENDER / VENDOR</td>
                <td style="color:#000;"><?php echo $senderVendor; ?></td>
            </tr>
            <tr valign="top">
                <td height="22">AMOUNT</td>
                <td style="color:#000;"><?php echo "(".$curr.") ".number_format($amountNya,2); ?></td>
            </tr>            
            <tr valign="top">
                <td height="22" class="">FILE</td>
                <td class="">
                    <input type="file" name="fileUploadNya" id="fileUploadNya" class="btnStandar" style="width:250px" title="File Upload">
                    &nbsp <a style="cursor:pointer;" onclick="$('#fileUploadNya').val('');"> Clear </a>
                </td>
            </tr>
            <tr valign="top">
                <td height="70" class="">Remark</td>
                <td class="">
                    <textarea id="txtRemark" name="txtRemark" class="elementInput" rows="5" cols="51" style="height:70px;" onkeyup="textCounter(this, sisaRemarks, 200);"></textarea>
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
        <button class="btnStandar" onclick="tutupNewMail();">
            <table border="0" width="63" height="30">
            <tr>
                <td align="center" width="20"><img src="../picture/door-open-out.png"/></td>
                <td align="left">CLOSE</td>
            </tr>
            </table>
        </button>
        <button class="btnStandar" onclick="saveUpdateBuktiBayar();return false;">
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
function saveUpdateBuktiBayar()
{
    pleaseWait();
    $('#formUpdateBuktiBayar').submit();
}
function tutupNewMail()
{
    var answer  = confirm('Are you sure want to Close?');
    if(answer)
    {
        pleaseWait();
        parent.tb_remove(false);
        parent.$('#formPaymentBatch').submit();
    }
}
</script>
</HTML>