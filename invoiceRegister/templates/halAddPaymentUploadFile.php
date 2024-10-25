<?php require_once("../configInvReg.php"); ?>
<!DOCTYPE HTML>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<!-- <script type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script> -->
<!-- <script type="text/javascript" src="../../js/JavaScriptUtil.js"></script> -->
<!-- <script type="text/javascript" src="../../js/Parsers.js"></script> -->
<!-- <script type="text/javascript" src="../../js/InputMask.js"></script> -->
<!-- <script type="text/javascript" src="../js/masks.js"></script> -->
<script type="text/javascript" src="../js/invReg.js"></script>
<!-- <script type="text/javascript" src="../js/tambahMail.js"></script> -->

<!-- <link rel="stylesheet" type="text/css" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK> -->
<!-- <link rel="stylesheet" type="text/css" href="../../css/archives.css">  -->
<link href="../css/invReg.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../css/button.css" />
<!-- <link rel="stylesheet" type="text/css" href="../css/table.css"/> -->
<?php
    $idVoucher = $_GET['idVoucher'];
    $typeVoucher = $_GET['typeVoucher'];
    $typeData = $_GET['typeData'];
    $titleLabel = "";
    $stSimpan = "";
    $batchNo = "";
    $company = "";
    $kepada = "";
    $datePaid = "";
    $voucherNo = "";
    $paymentMethod = "";
    $bank = "";
    $amount = "";
    $trNya = "";

    if($typeData == "voucher")
    {
        $titleLabel = "ADD FILE VOUCHER";
        if($typeVoucher == "simpan")
        {
            $idVoucherNya = $_POST['txtIdVoucher'];

            if($_FILES['fileUploadNya']['name'] != "")
            {
                $tmpFile = $_FILES['fileUploadNya']['tmp_name'];
                $fileName = $_FILES['fileUploadNya']['name'];
                $dir = "./../../voucher/templates/fileUpload/";
                $newFileName = "voucher_".$idVoucherNya;

                $dt = explode(".", $fileName);
                $newFileName = str_replace(array(' ','/','.',',','-'), '', $newFileName).".".trim($dt[count($dt)-1]);

                move_uploaded_file($tmpFile, $dir."/".$fileName);
                rename($dir."/".$fileName, $dir."/".$newFileName);

                $fileNameNya = $newFileName;

                $CKoneksiVoucher->bukaKoneksi();
                $CKoneksiVoucher->mysqlQuery("UPDATE tblvoucher SET file_upload='".$fileNameNya."' WHERE idvoucher='".$idVoucherNya."' AND deletests=0;");
                
                $stSimpan = "success";
                echo ("<body onLoad=\"pleaseWait();\">");
            }
        }
        
        $sql = "SELECT * FROM tblvoucher WHERE deletests = '0' AND idvoucher = '".$idVoucher."' ;";

        $query = $CKoneksiVoucher->mysqlQuery($sql, $CKoneksiVoucher->bukaKoneksi());
        while($rows = $CKoneksiVoucher->mysqlFetch($query))
        {
            $batchNo = $rows['batchno'];
            $company = $rows['companyname'];
            $kepada = $rows['kepada'];
            $datePaid = $CPublic->convTglNonDB($rows['datepaid']);
            $voucherNo = $rows['voucher'];
            $paymentMethod = $rows['paytype'];
            $bank = $rows['bankcode']." - ".$rows['banksource'];
            $amount = "(".$rows['currency'].") ".number_format($rows['amount'],2);

            $trNya .= "<tr>";
                $trNya .= "<td style=\"font-size:11px;\">";
                    $trNya .= $rows['invno'];
                $trNya .= "</td>";
                $trNya .= "<td style=\"font-size:11px;\">";
                    $trNya .= $rows['barcode'];
                $trNya .= "</td>";
                $trNya .= "<td style=\"font-size:11px;\">";
                    $trNya .= "(".$rows['currency'].") <span style=\"float: right;\">".number_format($rows['amount'],2)."</span>";
                $trNya .= "</td>";
                $trNya .= "<td style=\"font-size:11px;\">";
                    $trNya .= "<input type=\"file\" name=\"fileUploadNya\" id=\"fileUploadNya\" class=\"btnStandar\" style=\"\" title=\"File Upload\">
                    &nbsp <a style=\"cursor:pointer;\" onclick=\"$('#fileUploadNya').val('');\"> Clear </a>";
                $trNya .= "</td>";
            $trNya .= "</tr>";
        }
    }else{
        $titleLabel = "ADD FILE";
        $tempData = array();

        if($typeVoucher == "simpan")
        {
            $dir = "./fileUpload";            

            $arrIdMailInv = $_POST['txtIdUpload'];
            $arrBarcode = $_POST['txtBarcodeUpload'];

            if(count($arrIdMailInv) > 0)
            {
                for ($lan=0; $lan < count($arrIdMailInv); $lan++)
                {                    
                    if($_FILES['fileUploadNya_'.$arrIdMailInv[$lan]]['name'] != "")
                    {
                        $tmpFile = $_FILES['fileUploadNya_'.$arrIdMailInv[$lan]]['tmp_name'];
                        $fileName = $_FILES['fileUploadNya_'.$arrIdMailInv[$lan]]['name'];
                        $newFileName = $arrBarcode[$lan];

                        $dt = explode(".", $fileName);
                        $newFileName = str_replace(array(' ','/','.',',','-'), '', $newFileName).".".trim($dt[count($dt)-1]);

                        move_uploaded_file($tmpFile, $dir."/".$fileName);

                        rename($dir."/".$fileName, $dir."/".$newFileName);

                        $fileNameNya = $newFileName;

                        $CKoneksiInvReg->bukaKoneksi();
                        $CKoneksiInvReg->mysqlQuery("UPDATE mailinvoice SET file_upload='".$fileNameNya."' WHERE idmailinv=".$arrIdMailInv[$lan]." AND deletests=0;");
                    }
                }
                $stSimpan = "success";
                echo ("<body onLoad=\"pleaseWait();\">");
            }

        }

        $sql = "SELECT idmailinv, barcode, sendervendor1, sendervendor2name, company, companyname, batchno, voucher, paytype, currency, mailinvno, amount, addi, deduc, transno, file_upload 
            FROM mailinvoice 
            WHERE SUBSTR(barcode, 1, 1)='A' AND preparepayment='Y' AND deletests=0 AND paid = 'N' AND st_payment_list = 'N' AND transno = ".$idVoucher." ORDER BY 0+transno DESC ;";

        $rsl = $CKoneksiInvReg->mysqlQuery($sql, $CKoneksiInvReg->bukaKoneksi());

        while($row = $CKoneksiInvReg->mysqlFetch($rsl))
        {
            $tempData[$row['transno']]['companyname'][] = $row['companyname'];

            if($row['sendervendor1'] == "")
            {
                $tempData[$row['transno']]['senderVendor'][] = $row['sendervendor2name'];
            }else{
                $tempData[$row['transno']]['senderVendor'][] = $row['sendervendor1'];
            }

            $tempData[$row['transno']]['idmailinv'][] = $row['idmailinv'];
            $tempData[$row['transno']]['barcode'][] = $row['barcode'];
            $tempData[$row['transno']]['invNo'][] = $row['mailinvno'];
            $tempData[$row['transno']]['voucher'][] = $row['voucher'];
            $tempData[$row['transno']]['paytype'][] = $row['paytype'];
            $tempData[$row['transno']]['currency'][] = $row['currency'];
            $tempData[$row['transno']]['amount'][] = $row['amount'];
            $tempData[$row['transno']]['addi'][] = $row['addi'];
            $tempData[$row['transno']]['deduc'][] = $row['deduc'];
            $tempData[$row['transno']]['file_upload'][] = $row['file_upload'];
        }

        foreach ($tempData as $key => $val)
        {
            $invNoNya = "";
            $curr = "";
            $total = 0;
            $deduc = 0;
            $no = 1;

            for ($lan = 0; $lan < count($val['idmailinv']); $lan++)
            {
                $btnNya = "";
                $fileNya = "";
                $batchNo = $key;
                $company = $val['companyname'][$lan];
                $kepada = $val['senderVendor'][$lan];
                $voucherNo = $val['voucher'][$lan];
                $paymentMethod = $val['paytype'][$lan];
                $bank = "";
                $curr = $val['currency'][$lan];

                $total = $total + $val['amount'][$lan];
                $deduc = $deduc + $val['deduc'][$lan];

                if($invNoNya == "")
                {
                    $invNoNya = "- ".$val['invNo'][$lan];
                }else{
                    $invNoNya .= "<br>- ".$val['invNo'][$lan];
                }

                if($val['file_upload'][$lan] == "")
                {
                    $btnNya = "<input type=\"file\" name=\"fileUploadNya_".$val['idmailinv'][$lan]."\" id=\"fileUploadNya_".$val['idmailinv'][$lan]."\" class=\"btnStandar\" style=\"\" title=\"File Upload\">&nbsp <a style=\"cursor:pointer;\" onclick=\"$('#fileUploadNya_".$val['idmailinv'][$lan]."').val('');\"> Clear </a>";
                    $btnNya .= "<input type=\"hidden\" name=\"txtIdUpload[]\" id=\"txtIdUpload\" value=\"".$val['idmailinv'][$lan]."\">";
                    $btnNya .= "<input type=\"hidden\" name=\"txtBarcodeUpload[]\" id=\"txtBarcodeUpload\" value=\"".$val['barcode'][$lan]."\">";
                }else{
                    $btnNya = "<a href=\"./fileUpload/".$val['file_upload'][$lan]."\" target=\"_blank\" title=\"View File\">File&nbsp".$no."</a> ";
                    $no++;
                }

                $trNya .= "<tr>";
                    $trNya .= "<td style=\"font-size:11px;height:25px;padding:0px 5px 0px 5px;\">";
                        $trNya .= $val['invNo'][$lan];
                    $trNya .= "</td>";
                    $trNya .= "<td style=\"font-size:11px;text-align:center;\">";
                        $trNya .= $val['barcode'][$lan];
                    $trNya .= "</td>";
                    $trNya .= "<td style=\"font-size:11px;padding:0px 5px 0px 5px;\">";
                        $trNya .= "(".$val['currency'][$lan].") <span style=\"float: right;\">".number_format(($val['amount'][$lan] - $val['deduc'][$lan]),2)."</span>";
                    $trNya .= "</td>";
                    $trNya .= "<td style=\"font-size:11px;padding:0px 5px 0px 5px;\">";
                        $trNya .= $btnNya;
                    $trNya .= "</td>";
                $trNya .= "</tr>";

            }

            $total = $total - $deduc;

            $amount = "(".$curr.") ".number_format($total,2);
        }

    }

?>
<style>
body {
    background-color: #f9f9f9;
}
</style>

<script>
<?php
    if($stSimpan == "success")
    {
        echo "afterSaveVoucher();";
    }
    ?>

window.onload = function() {
    doneWait();
}

function tutupFormNya() {
    var answer = confirm('Are you sure want to Close?');
    if (answer) {
        pleaseWait();
        parent.tb_remove(false);
    }
}

function afterSaveVoucher() {
    alert("upload success..!!");
    parent.$('#formPaymentAddList').submit();
    return false;
}
</script>

<div id="loaderImg" style="visibility:visible;" class="pleaseWait2">
    <div class="isiPleaseWait tabelBorderAll">&nbsp;&nbsp;&nbsp;Please Wait...&nbsp;<img
            src="../picture/loading (115).gif" height="12" />&nbsp;</div>
</div>

<div id="idHalTambahMailInv">
    <table cellpadding="0" cellspacing="0" width="800" height="490"
        style="font:0.8em sans-serif;font-weight:bold;color:#485a88;" border="0" align="center">
        <form method="post"
            action="halAddPaymentUploadFile.php?typeVoucher=simpan&idVoucher=<?php echo $idVoucher; ?>&typeData=<?php echo $typeData; ?>"
            enctype="multipart/form-data" id="formSimpanFileVoucher" name="formSimpanFileVoucher">
            <input type="hidden" id="txtIdVoucher" name="txtIdVoucher" value="<?php echo $idVoucher; ?>">
            <tr>
                <td valign="top" align="center" height="50" colspan="2"><span
                        style="font-size:16px;"><?php echo $titleLabel; ?></span></td>
            </tr>
            <tr>
                <td height="100" valign="top">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%"
                        style="font:1em sans-serif;font-weight:bold;color:#485a88;">
                        <tr valign="top">
                            <td height="22" class="" width="150">Trans / Batch No :</td>
                            <td class="elementTeks" style="vertical-align:top;"><?php echo $batchNo; ?></td>
                        </tr>
                        <tr valign="top">
                            <td height="22" class="">Company :</td>
                            <td class="elementTeks" style="vertical-align:top;"><?php echo $company; ?></td>
                        </tr>
                        <tr valign="top">
                            <td height="22" class="">Paid To :</td>
                            <td class="elementTeks" style="vertical-align:top;"><?php echo $kepada; ?></td>
                        </tr>
                        <tr valign="top">
                            <td height="22" class="">Amount :</td>
                            <td class="elementTeks" style="vertical-align:top;"><?php echo $amount; ?></td>
                        </tr>
                        <!-- <tr valign="top">
                <td height="22" class="">File :</td>
                <td class="">
                    <input type="file" name="fileUploadNya" id="fileUploadNya" class="btnStandar" style="width:250px" title="File Upload">
                    &nbsp <a style="cursor:pointer;" onclick="$('#fileUploadNya').val('');"> Clear </a>
                </td>
            </tr> -->
                    </table>
                    <table cellpadding="0" cellspacing="0" border="1" style="width:100%;margin-top:5px;">
                        <thead>
                            <tr align="center" style="height:30px;">
                                <td width="15%" class="tabelBorderRightJust"
                                    style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:10px;left:0px;top:0px;z-index:10;">
                                    INV. NUMBER</td>
                                <td width="15%" class="tabelBorderRightJust"
                                    style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:10px;left:0px;top:0px;z-index:10;">
                                    BARCODE</td>
                                <td width="20%" class="tabelBorderRightJust"
                                    style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:10px;left:0px;top:0px;z-index:10;">
                                    AMOUNT</td>
                                <td width="40%" class="tabelBorderRightJust"
                                    style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:10px;left:0px;top:0px;z-index:10;">
                                    FILE</td>
                            </tr>
                        </thead>
                        <tbody id="idBodyDetail">
                            <?php echo $trNya; ?>
                        </tbody>
                    </table>
                </td>
            </tr>
        </form>
        <tr>

        <tr valign="top">
            <td height="20" valign="bottom">
                <div id="idErrorMsg" class="errorMsgInv" style="visibility:hidden;"><img
                        src="../picture/exclamation-red.png" />&nbsp;<span>&nbsp;</span>&nbsp;</div>
            </td>
        </tr>

        <tr>
            <td height="30" align="center" class="tabelBorderTopJust" style="padding-top:5px;">
                <button class="btnStandar" onclick="tutupFormNya();">
                    <table border="0" width="63" height="30">
                        <tr>
                            <td align="center" width="20"><img src="../picture/door-open-out.png" /></td>
                            <td align="left">CLOSE</td>
                        </tr>
                    </table>
                </button>
                <button class="btnStandar" onclick="formSimpanFileVoucher.submit();" id="btnSaveVoucher">
                    <table width="53" height="30">
                        <tr>
                            <td align="center" width="20"><img src="../picture/disk-black.png" /></td>
                            <td align="left">SAVE</td>
                        </tr>
                    </table>
                </button>
            </td>
        </tr>
    </table>
</div>

<script>

</script>

</HTML>