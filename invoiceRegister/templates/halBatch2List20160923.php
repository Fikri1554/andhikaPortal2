<!DOCTYPE HTML>
<?php
require_once("../configInvReg.php");

$transNoGet = $_GET['transNo'];
?>

<link href="../css/table.css" rel="stylesheet" type="text/css" />

<body>
<table id="judul1" cellpadding="0" cellspacing="0" width="670" border="0" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;">
<tr align="center">
    <td width="160" height="30" class="">BILLING COMPANY</td>
    <td width="40" class="">CURR</td>
    <td width="130" class="">TOTAL AMOUNT</td>
    <td width="70" class="" style="font-size:10px;">CREDITOR<br>NUMBER</td>
    <td width="270" class="">SENDER / VENDOR NAME</td>
</tr>
</table>
  
<div style="position:absolute;top:30px;overflow:hidden;">
<table cellpadding="0" cellspacing="0" width="670" style="font:0.7em sans-serif;color:#333;">
<?php
$i=0;
$totalAmount = 0;
$amount = 0;
$tabel = "";

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
</body>  
</HTML>