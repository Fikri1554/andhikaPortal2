<!DOCTYPE HTML>
<?php
require_once("../configInvReg.php");

$transNoGet = $_GET['transNo'];
?>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script language="javascript">
$(window).scroll(function(){
$('#judul2').css('left','-'+$(window).scrollLeft());
});
</script>

<link href="../css/table.css" rel="stylesheet" type="text/css" />

<body>
<table id="judul2" cellpadding="0" cellspacing="0" width="1595" border="0" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;position:fixed;left:0px;top:0px;">
<tr align="center">
    <td width="40" height="30" class="">SNO</td>
    <td width="70" class="" style="font-size:10px;">CREDITOR<br>NUMBER</td>
    <td width="270" class="">SENDER / VENDOR NAME</td>
    <td width="70" class="">INV. DATE</td>
    <td width="85" class="">BARCODE</td>
    <td width="150" class="">INV. NUMBER</td>
    <td width="130" class="">AMOUNT</td>
    <td width="70" class="">DUE DATE</td>
    <td width="160" class="">BILLING COMPANY</td>
    <td width="215" class="">VESSEL NAME</td>
    <td width="335" class="">DESCRIPTION</td>
</tr>
</table>

<table cellpadding="0" cellspacing="0" width="1595" style="font:0.7em sans-serif;color:#333;margin-top:30px;">
<?php
$i = 0;
$tabel = "";
$senVenGrupPertama = "";
$billCompGrupPertama = "";
$currencyGrupPertama = "";

$query = $CKoneksiInvReg->mysqlQuery("SELECT urutan, vesname, sendervendor1, tipesenven, sendervendor2name, tglinvoice, barcode, mailinvno, currency, addi, deduc, amount, tglexp, companyname, description, kreditacc FROM mailinvoice WHERE transno=".$transNoGet." AND deletests=0 ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
while($row = $CKoneksiInvReg->mysqlFetch($query))
{
    $i++;
    $rowColor = $CPublic->rowColorCustom($i, "#FFFFFF", "#F0F1FF");
    
    $senderVendor = $row['sendervendor1'];// SENDER / VENDOR NAME
    if($row['tipesenven'] == "2")
        $senderVendor = $row['sendervendor2name'];// SENDER / VENDOR NAME
        
    $tglInv = $CPublic->convTglNonDB( $row['tglinvoice'] );
    $poNumber = $row['barcode']; // BARCODE
    $invNumber = $row['mailinvno'];
    
    $deduc = $row['deduc'];
    $addi = $row['addi'];
    $amountt = (($row['amount'] - $deduc) + $addi);
    
    $amount = $CPublic->jikaKosongStrip(number_format((float)$amountt, 2, '.', ','));
    $tglexp = $CPublic->convTglNonDB( $row['tglexp'] ); // DUE DATE
    
    $tabel.="" 
?>
    <tr align="center" valign="bottom" bgcolor="<?php echo $rowColor; ?>"  onMouseOver="this.style.backgroundColor='#D9EDFF';" onMouseOut="this.style.backgroundColor='<?php echo $rowColor; ?>';" id="tr<?php echo $i; ?>" 
            style="cursor:pointer;padding-bottom:1px;">
        <td width="40" height="20" class="tabelBorderTopLeftNull"><?php echo $row['urutan']; ?></td>
        
        <td width="70" class="tabelBorderTopLeftNull"><?php echo $row['kreditacc']; ?></td>
        <td width="270" class="tabelBorderTopLeftNull" align="left" style="padding-left:3px;"><?php echo $senderVendor; ?>&nbsp;</td>
        <td width="70" class="tabelBorderTopLeftNull"><?php echo $tglInv; ?></td>
        <td width="85" class="tabelBorderTopLeftNull"><?php echo $poNumber; ?></td>
        <td width="150" class="tabelBorderTopLeftNull">&nbsp;<?php echo $invNumber; ?></td>
        <td width="130" class="tabelBorderTopLeftNull" align="right"><?php echo "(".$row['currency'].")"."&nbsp;".$amount; ?>&nbsp;</td>
        <td width="70" class="tabelBorderTopLeftNull"><?php echo $tglexp; ?></td>
        <td width="160" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $row['companyname']; ?></td>
        <td width="215" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $row['vesname']; ?></td>
        <td width="335" class="tabelBorderBottomJust" align="left" style="padding-left:3px;"><?php echo $row['description']; ?>&nbsp;</td>
    </tr>
<?php		
    echo "";    
}
echo $tabel;
?>
</table>
</body>
</HTML>