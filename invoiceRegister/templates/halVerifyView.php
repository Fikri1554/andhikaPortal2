<?php require_once("../configInvReg.php"); ?>
<!DOCTYPE HTML>
<script type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="./js/payment.js"></script>
<script type="text/javascript" src="./js/jquery-1.4.2.min.js"></script>

<link rel="stylesheet" type="text/css" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></link>
<link href="../css/tableReport.css" rel="stylesheet" type="text/css">
<?php
$id = $_GET['id'];
$type = $_GET['type'];
$dateNow = $CPublic->zerofill($CPublic->tglDayServer(),2)."/".$CPublic->zerofill($CPublic->bulanServer(),2)."/".$CPublic->tahunServer();

$company = "";
$transNo = "";
$voucher = "";
$refNo = "";
$datePaid = "";
$credAccName = "";
$credAccCode = "";
$trHeadNya = "";
$trNya = "";
$trFooterNya = "";
$curr = "";
$totalAmount = 0;
$remark = "";
$bankCode = "";
$bankSource = "";
$paymentMethode = "";
$bookSts = "";

if($type == "invReg")
{
    $sql = "SELECT * FROM mailinvoice WHERE deletests = '0' AND transno = ".$id;
    $query = $CKoneksiInvReg->mysqlQuery($sql, $CKoneksiInvReg->bukaKoneksi());

    while($row = $CKoneksiInvReg->mysqlFetch($query))
    {
        $company = $row['companyname'];
        $transNo = $row['transNo'];
        $transNo = $CPublic->zerofill($id, 6);
        $refNo = $row['reference'];        
        $credAccName = $row['kreditaccname'];
        $credAccCode = $row['kreditacc'];
        $amount = (($row['amount'] - $row['deduc']) + $row['addi']);
        $totalAmount += $amount;
        $curr = $row['currency'];
        $bankCode = $row['bankcode'];
        $paymentMethode = "By ".$row['paytype'];
        $bankCode = $row['bankcode'];
        $voucher = $row['voucher'];

        if($row['datepaid'] != "0000-00-00")
        {
            $datePaid = $CPublic->convTglNonDB($row['datepaid']);
        }

        if($row['remark'] != "")
        {
            if($remark == "")
            {
                $remark = "- ".$row['remark'];
            }else{
               $remark .= "<br>- ".$row['remark'];
            }           
        }

        $trNya.= "<tr style=\"font-size:14px;\">
                <td class=\"tabelBorderTopRightNull\" height=\"20\">
                    <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"font-size:12px;\">
                    <tr>
                        <td width=\"18%\" align=\"center\">".$CPublic->convTglNonDB( $row['tglinvoice'] )."</td>
                        <td width=\"18%\" align=\"center\">".$CPublic->convTglNonDB( $row['tglexp'] )."</td>
                        <td width=\"30%\" align=\"center\">".$row['mailinvno']."</td>
                        <td width=\"34%\" align=\"center\">".$row['vesname']."</td>
                    </tr>
                    </table>
                </td>
                <td class=\"tabelBorderTopRightNull\" align=\"center\">&nbsp;".$row['vescode']."&nbsp;</td>
                <td class=\"tabelBorderTopRightNull\">&nbsp;</td>
                <td class=\"tabelBorderTopNull\">
                    <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"font-size:16px;\">
                        <tr>
                            <td align=\"right\" width=\"25%\">".$curr."</td><td align=\"right\" width=\"75%\">".number_format((float)$amount, 2, '.', ',')."&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>";
    }

    $totalAmount = number_format((float)$totalAmount, 2, '.', ',');

    $trHeadNya = "<tr style=\"font-size:17px;\">
                    <td class=\"tabelBorderLeftJust\" height=\"20\"><b><i>".$credAccName."&nbsp;</i></b></td>
                    <td class=\"tabelBorderLeftJust\">&nbsp;</td>
                    <td rowspan=\"3\" align=\"center\" valign=\"middle\" class=\"tabelBorderTopRightNull\" style=\"letter-spacing:10px;font-size:16px;\">".$credAccCode."&nbsp;</td>
                    <td class=\"tabelBorderTopBottomNull\">&nbsp;</td>
                </tr>
                <tr style=\"font-size:16px;\">
                    <td class=\"tabelBorderLeftJust\" height=\"20\"><b>Being payment for following invoices</b></td>
                    <td class=\"tabelBorderLeftJust\">&nbsp;</td>
                    <td class=\"tabelBorderTopBottomNull\">&nbsp;</td>
                </tr>
                <tr>
                    <td class=\"tabelBorderTopRightNull\" height=\"20\">
                        <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"font-size:15px;\">
                            <tr>
                                <td width=\"18%\" align=\"center\">Inv. Date</td>
                                <td width=\"18%\" align=\"center\">Due Date</td>
                                <td width=\"30%\" align=\"center\">Invoice No.</td>
                                <td width=\"34%\" align=\"center\">Vsl Name</td>
                            </tr>
                        </table>
                    </td>
                    <td class=\"tabelBorderTopRightNull\">&nbsp;</td>
                    <td class=\"tabelBorderTopNull\">&nbsp;</td>
                </tr>";

    $trFooterNya = "<tr>
                        <td class=\"tabelBorderTopRightNull\">".$remark."</td>
                        <td class=\"tabelBorderTopRightNull\" align=\"center\" style=\"font-weight:bold;font-size:16px;font-family:Arial Black;\">Total</td>
                        <td class=\"tabelBorderTopRightNull\" align=\"center\" style=\"letter-spacing:10px;font-size:16px;\">&nbsp;".$bankCode."&nbsp;</td>
                        <td class=\"tabelBorderTopNull\">
                            <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"font-size:16px;\">
                                <tr>
                                    <td align=\"right\" width=\"25%\">".$curr."</td><td align=\"right\" width=\"75%\">".$totalAmount."&nbsp;&nbsp;&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>";
}

if($type == "voucher")
{
    $sql = "SELECT * FROM tblvoucher WHERE deletests = '0' AND idvoucher = ".$id;
    $rows = $CKoneksiVoucher->mysqlQuery($sql, $CKoneksiVoucher->bukaKoneksi());
    while($row = $CKoneksiInvReg->mysqlFetch($rows))
    {
        $company = $row['companyname'];
        $transNo = $row['batchno'];
        $refNo = $row['reference'];
        $datePaid = $CPublic->convTglNonDB($row['datepaid']);
        $credAccName = $row['kepada'];
        $curr = $row['currency'];
        $totalAmount = $row['amount'];
        $bankCode = $row['bankcode'];
        $bankSource = $row['banksource'];
        $paymentMethode = "By ".$row['paytype'];
        $bookSts = strtoupper($row['booksts']);
        $voucher = $row['voucher'];
    }

    $trNya .= " <tr style=\"font-size:17px;\">
                    <td class=\"tabelBorderLeftJust\" height=\"20\"><b><i>".$credAccName."&nbsp;</i></b></td>
                    <td class=\"tabelBorderLeftJust\">&nbsp;</td>
                    <td class=\"tabelBorderLeftJust\">&nbsp;</td>
                    <td class=\"tabelBorderTopBottomNull\">&nbsp;</td>
                </tr>";

    $sqlDesc = "SELECT * FROM tbldesc WHERE deletests = '0' AND idvoucher = ".$id;
    $rowDesc = $CKoneksiVoucher->mysqlQuery($sqlDesc, $CKoneksiVoucher->bukaKoneksi());
    while($row = $CKoneksiInvReg->mysqlFetch($rowDesc))
    {
        $amount = $row['amount'];
        $trNya.= "<tr style=\"font-size:14px;\">
                    <td class=\"tabelBorderTopRightNull\" height=\"20\">- ".$row['keterangan']."</td>
                    <td class=\"tabelBorderTopRightNull\" align=\"center\">&nbsp;".$row['vescode']."&nbsp;</td>
                    <td class=\"tabelBorderTopRightNull\" align=\"center\" style=\"letter-spacing:10px;\">
                        <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"font-size:16px;\">
                            <tr>
                                <td width=\"70%\" style=\"letter-spacing:10px;\">&nbsp;".$row['perkiraan']."&nbsp;</td>
                                <td width=\"30%\">&nbsp;".strtoupper($row['booksts'])."</td>
                            </tr>
                        </table>
                    </td>
                    <td class=\"tabelBorderTopNull\">
                        <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"font-size:16px;\">
                            <tr>
                                <td align=\"right\" width=\"25%\">".$curr."</td><td align=\"right\" width=\"75%\">".number_format((float)$amount, 2, '.', ',')."&nbsp;&nbsp;&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>";
    }

    $totalAmount = number_format((float)$totalAmount, 2, '.', ',');

    $trFooterNya = "<tr>
                        <td height=\"30\" align=\"center\" colspan=\"4\">
                            <table width=\"940\" cellpadding=\"5\" cellspacing=\"0\" style=\"font-size:16px;\">
                                <tr>
                                    <td width=\"174\" align=\"right\" class=\"tabelBorderTopJust\" style=\"font-weight:bold;font-size:16px;font-family:Arial Black;\">Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td width=\"70\" align=\"right\" class=\"tabelBorderRightNull\" height=\"30\">".$curr."</td>
                                    <td width=\"172\" align=\"right\" class=\"tabelBorderLeftRightNull\">".$totalAmount."&nbsp;</td>
                                    <td width=\"92\" align=\"right\" class=\"tabelBorderLeftRightNull\">".$bankSource."</td>
                                    <td width=\"141\" align=\"right\" class=\"tabelBorderLeftRightNull\">".$bankCode."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td width=\"229\" align=\"left\" class=\"tabelBorderLeftNull\">&nbsp;".$bookSts."</td>
                                </tr>
                            </table>
                        </td>
                    </tr>";
}

?>
<style>
body {background-color: #f9f9f9;}
</style>

<center>
    <div class="wrap">
        <div id="loaderImgView" style="visibility:hidden;" class="pleaseWaitView">
            <div class="isiPleaseWait tabelBorderAll">&nbsp;Please Wait...&nbsp;<img src="./../picture/loading (115).gif" height="12"/>&nbsp;</div>
        </div>
    </div>
    <table width="100%" cellpadding="0" cellspacing="0" style="font-family:Arial;">
        <tr>
            <td>
                <button class="btnStandar" onclick="parent.closePage('Y');">
                    <table border="0" width="50" height="20">
                    <tr>
                        <td align="center" width="20"><img src="../picture/door-open-out.png"/></td>
                        <td align="left">CLOSE</td>
                    </tr>
                    </table>
                </button>
                <button class="btnStandar" onclick="verifyDataView();">
                    <table width="50" height="20">
                    <tr>
                        <td align="center" width="20"><img src="../picture/tick.png"/></td>
                        <td align="left">Verify</td>
                    </tr>
                    </table>
                </button>
            </td>
        </tr>
        <tr>
            <td valign="middle" align="center" height="40" style="font-size:25px;font-family:arial;"><b><?php echo $company; ?></b></td>
        </tr>
        <tr>
            <td align="center" style="font-size:20px;text-decoration:underline;font-family:Arial Black;"><b>Payment Voucher</b></td>
        </tr>
        <tr>
            <td align="center">
                <table width="940" cellpadding="0" cellspacing="0" style="font-size:16px;">
                <tr>
                    <td height="30">&nbsp;</td>
                    <td align="right"><b>No.</b></td>
                    <td width="254">&nbsp;&nbsp;&nbsp;<i><?php echo $voucher; ?>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;<?php echo $refNo; ?></i></td>
                </tr>
                <tr>
                    <td height="30" width="630">&nbsp;&nbsp;&nbsp;Trans No : <?php echo $transNo; ?></td>
                    <td width="54" align="right"><b>Date :</b></td>
                    <td>&nbsp;&nbsp;&nbsp;<?php echo $datePaid; ?></td>
                </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center">
                <table width="940" height="220" cellpadding="3" cellspacing="0">
                <tr align="center" style="font-weight:bold;font-size:16px;font-family:Arial Black;">
                    <td width="513" height="30" class="tabelBorderRightNull">Particulars</td>
                    <td width="62" class="tabelBorderRightNull">Vsl</td>
                    <td width="142" class="tabelBorderRightNull">A/C Code</td>
                    <td width="241" class="tabelBorderAll">Amount</td>
                </tr>
                <?php 
                    echo $trHeadNya;
                    echo $trNya;
                    echo $trFooterNya;
                ?>                
                </table>
            </td>
        </tr>
        <tr>
            <td align="center">
                <table width="940" cellpadding="0" cellspacing="0">
                <tr>
                    <td height="40" class="" align="left" colspan="2" style="font-size:15px;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Payment Method</i>
                    &nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $paymentMethode; ?></td>
                </tr>
                </table>
            </td>
        </tr>
    </table>
    <input type="hidden" id="txtIdVerifyView" value="<?php echo $id; ?>">
    <input type="hidden" id="txtTypeVerifyView" value="<?php echo $type; ?>">
</center>
</HTML>
<script type="text/javascript">
    function verifyDataView()
    {
        var cfm = confirm("Verify Data..??");

        if(cfm)
        {
            document.getElementById('loaderImgView').style.visibility = "visible";
            var id = document.getElementById('txtIdVerifyView').value;
            var type = document.getElementById('txtTypeVerifyView').value;
            parent.verifyData(id,type);
        }
    }
</script>