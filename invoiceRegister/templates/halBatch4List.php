<!DOCTYPE HTML>
<?php
require_once("../configInvReg.php");

$idMailInv = $_GET['idMailInv'];
?>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>

<script language="javascript">
window.onload =
    function() {
        parent.doneWait();
        parent.panggilEnableLeftClick();
    }

$(window).scroll(function() {
    $('#judul3').css('left', '-' + $(window).scrollLeft() + 'px');
});
</script>

<link href="../css/table.css" rel="stylesheet" type="text/css" />

<body>
    <table id="judul3" cellpadding="0" cellspacing="0" width="760" border="0"
        style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;position:fixed;left:0px;top:0px;">
        <tr align="center">
            <td width="4%" height="30" class="">NO</td>
            <td width="16%" class="">ACCOUNT</td>
            <td width="10%" class="">INV.DATE</td>
            <td width="10%" class="">BARCODE</td>
            <td width="10%" class="">INV.NUMBER</td>
            <td width="10%" class="">AMOUNT</td>
            <td width="10%" class="">VSL CODE</td>
            <td width="30%" class="">DESCRIPTION</td>
        </tr>
    </table>

    <table cellpadding="0" cellspacing="0" width="760" style="font:0.7em sans-serif;color:#333;margin-top:30px;">
        <?php
$i = 0;
$tabel = "";
$senVenGrupPertama = "";
$billCompGrupPertama = "";
$currencyGrupPertama = "";
$invDate = "";
$barCode = "";
$invNumber = "";

$queryInvoice = $CKoneksiInvReg->mysqlQuery("SELECT * FROM mailinvoice WHERE idmailinv =".$idMailInv." AND deletests=0", $CKoneksiInvReg->bukaKoneksi());
while($rowInvoice = $CKoneksiInvReg->mysqlFetch($queryInvoice))
{
    $invDate = $CPublic->convTglNonDB( $rowInvoice['tglinvoice'] );
    $barCode = $rowInvoice['barcode']; 
    $invNumber = $rowInvoice['mailinvno'];
}

$query = $CKoneksiInvReg->mysqlQuery("SELECT * FROM tblsplit WHERE idmailinv=".$idMailInv." AND fieldaksi = 'memocredit' ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
while($row = $CKoneksiInvReg->mysqlFetch($query))
{
    $i++;
    $rowColor = $CPublic->rowColorCustom($i, "#FFFFFF", "#F0F1FF");
    $amountt = $row['amount'] ;
    $amount = $CPublic->jikaKosongStrip(number_format((float)$amountt, 2, '.', ','));

    $tabel.="" 
?>
        <tr align="center" valign="bottom" bgcolor="<?php echo $rowColor; ?>"
            onMouseOver="this.style.backgroundColor='#D9EDFF';"
            onMouseOut="this.style.backgroundColor='<?php echo $rowColor; ?>';" id="tr<?php echo $i; ?>"
            style="cursor:pointer;padding-bottom:1px;">
            <td width="4%" height="20" class="tabelBorderTopLeftNull"><?php echo $i; ?></td>
            <td width="16%" class="tabelBorderTopLeftNull"><?php echo $row['account']; ?></td>
            <td width="10%" class="tabelBorderTopLeftNull"><?php echo $invDate; ?></td>
            <td width="10%" class="tabelBorderTopLeftNull"><?php echo $barCode; ?></td>
            <td width="10%" class="tabelBorderTopLeftNull"><?php echo $invNumber; ?></td>
            <td width="10%" class="tabelBorderTopLeftNull" align="right"><?php echo $amount; ?>&nbsp;</td>
            <td width="10%" class="tabelBorderTopLeftNull"><?php echo $row['vslcode']; ?></td>
            <td width="30%" class="tabelBorderTopLeftNull" align="left" style="padding-left:3px;">
                <?php echo $row['description']; ?>&nbsp;</td>
        </tr>
        <?php		
    echo "";    
}
echo $tabel;
?>
    </table>
</body>

</HTML>

<script language="javascript">
function ketikVslInfo(idMailInv, i, paramNilai) {
    $.post("../halPostMailInv.php", {
        aksi: "ketikVslInfo",
        idMailInv: idMailInv,
        paramNilai: paramNilai
    }, function(data) {
        $('#vslInfo' + i).val(data);
    });
}
</script>