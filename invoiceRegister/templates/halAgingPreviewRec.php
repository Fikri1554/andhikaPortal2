<!DOCTYPE HTML>
<?php	
require_once("../configInvReg.php");	

$userId = $_GET['userId'];
$company = $_GET['company'];
$fromDate = $_GET['fromDate'];
$date = $CPublic->convTglDB($_GET['fromDate']);

$tgl = substr($fromDate,0,2);
$bln = substr($fromDate,3,2);
$thn = substr($fromDate,6,4);
$dateDisp = ucfirst(strtolower($CPublic->detilBulanNamaAngka($bln, "eng")))." ".$tgl.", ".$thn;
?>

<link href="../css/table.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery-1.4.2.min.js"></script>
<script>
this.window.onload =
    function() {
        parent.$('#loaderImg').css('visibility', 'hidden');
        parent.$('#btnPrintAgingSum').removeAttr('disabled');
        parent.$('#btnPrintAgingSum').attr('class', 'btnStandar');
        parent.$('#btnExportExcel').removeAttr('disabled');
        parent.$('#btnExportExcel').attr('class', 'btnStandar');

        // $(document).ready(function(){
        // 	$(function () {
        // 		$(window).scroll(function () {
        // 			if ($(this).scrollTop() > 74) {
        // 				$('#divTable').css('position','fixed');
        // 				$('#divTable').css('top','0');
        // 				$('#divTableTemp').css('height','48px');
        // 			} else {
        // 				$('#divTable').css('position','relative');
        // 				$('#divTable').css('top','auto');
        // 				$('#divTableTemp').css('height','0');
        // 			}
        // 		});
        // 	});
        // });
    }
</script>

<body>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="color:#555;">

        <tr>
            <td height="20" style="font:1.2em Arial Narrow;font-weight:bold;background-color:#F9F9F9;">
                &nbsp;<?php echo $CInvReg->detilComp($company, "compname");?></td>
        </tr>
        <tr>
            <td height="20" style="font:1em Arial Narrow;font-weight:normal;background-color:#F9F9F9;">&nbsp;Account
                Receivable Aging Report</td>
        </tr>
        <tr>
            <td height="20" style="font:1em Arial Narrow;font-weight:normal;background-color:#F9F9F9;">&nbsp;As of
                <?php echo $dateDisp;?></td>
        </tr>

        <!-- HEAD TABEL -->
        <tr>
            <td width="100%">
                <div id="divTable" style="width:1500px;background-color:#8A8A8A;color:#F9F9F9;">
                    <table class="tabelBorderBottomJust" width="1500"
                        style="font-family:Arial;font-weight:bold;font-size:11px;border-color:#333;border-bottom-width:2px">
                        <tr align="center">
                            <td rowspan="2" width="30">NO</td>
                            <td rowspan="2" width="220">INVOICE NO</td>
                            <td rowspan="2" width="150">INV. SENT<br>DATE</td>
                            <td rowspan="2" width="150">INV. DATE</td>
                            <td rowspan="2" width="100">TERM</td>
                            <td rowspan="2" width="150">INV. DUE DATE</td>
                            <td rowspan="2" width="100">CURRENT</td>
                            <td colspan="5" width="600" class="tabelBorderBottomJust" style="border-color:#666;"
                                align="center">OVERDUE</td>
                        </tr>
                        <tr align="center">
                            <td width="120">1 - 30 DAYS</td>
                            <td width="120">31 - 60 DAYS</td>
                            <td width="120">61 - 90 DAYS</td>
                            <td width="120">91 - 360 DAYS</td>
                            <td width="120">> 360 DAYS</td>
                        </tr>
                    </table>
                </div>
                <div id="divTableTemp" style=""></div>
            </td>
        </tr>

        <!-- ISI -->
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" width="1500">
                    <?php
    $currOther1 = "";
    $totalOther1RangeSatu = "";
    $totalOther1RangeDua = "";
    $totalOther1RangeTiga = "";
    $totalOther1RangeEmpat = "";
    $totalOther1RangeLima = "";
    $currOther2 = "";
    $totalOther2RangeSatu = "";
    $totalOther2RangeDua = "";
    $totalOther2RangeTiga = "";
    $totalOther2RangeEmpat = "";
    $totalOther2RangeLima = "";

	$query = $CKoneksiInvReg->mysqlQuery("SELECT * FROM summaryaging WHERE userid = ".$userId." ORDER BY urutan ASC;");
	while($row = $CKoneksiInvReg->mysqlFetch($query))
	{
	/*$html= $row['kreditacc']." | ".$vendor." | ".$row['currency']." | ".$row['mailinvno']." | ".$row['barcode']." | ".$row['receivedate']." | ".$row['tglinvoice']." | ".$row['dueday']." | ".$row['tglexp']." | ".$row['amount']."<br/>";
	echo $html;*/
	
// kondisi amount

	// $current = "";
	// if($row['amount'] != 0.00)
	// 	$current = number_format($row['amount'], 2, ",", ".");
    $current = $row['currency'];
	$rangeSatu = "";
	if($row['rangesatu'] != 0.00)
		$rangeSatu = number_format($row['rangesatu'], 2, ",", ".");
	$rangeDua = "";
	if($row['rangedua'] != 0.00)
		$rangeDua = number_format($row['rangedua'], 2, ",", ".");
	$rangeTiga = "";
	if($row['rangetiga'] != 0.00)
		$rangeTiga = number_format($row['rangetiga'], 2, ",", ".");
	$rangeEmpat = "";
	if($row['rangeempat'] != 0.00)
		$rangeEmpat = number_format($row['rangeempat'], 2, ",", ".");
    $rangeLima = "";
    if($row['rangelima'] != 0.00)
        $rangeLima = number_format($row['rangelima'], 2, ",", ".");
	
//kondisi dan function Sub Total
	$subRangeSatu = "";
	if($CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "satu") != 0.00)
		$subRangeSatu = number_format($CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "satu"), 2, ",", ".");
	$subRangeDua = "";
	if($CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "dua") != 0.00)
		$subRangeDua = number_format($CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "dua"), 2, ",", ".");
	$subRangeTiga = "";
	if($CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "tiga") != 0.00)
		$subRangeTiga = number_format($CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "tiga"), 2, ",", ".");
	$subRangeEmpat = "";
	if($CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "empat") != 0.00)
		$subRangeEmpat = number_format($CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "empat"), 2, ",", ".");
    $subRangeLima = "";
    if($CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "lima") != 0.00)
        $subRangeLima = number_format($CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "lima"), 2, ",", ".");
	
//kondisi dan function Total IDR	
	$totalIdr = "";
	if($CAging->hitungTotal($userId, "IDR", "") != 0.00)
		$totalIdr = number_format($CAging->hitungTotal($userId, "IDR", "satu"), 2, ",", ".");
	$totalRangeSatuIdr = "";
	if($CAging->hitungTotal($userId, "IDR", "satu") != 0.00)
		$totalRangeSatuIdr = number_format($CAging->hitungTotal($userId, "IDR", "satu"), 2, ",", ".");
	$totalRangeDuaIdr = "";
	if($CAging->hitungTotal($userId, "IDR", "dua") != 0.00)
		$totalRangeDuaIdr = number_format($CAging->hitungTotal($userId, "IDR", "dua"), 2, ",", ".");
	$totalRangeTigaIdr = "";
	if($CAging->hitungTotal($userId, "IDR", "tiga") != 0.00)
		$totalRangeTigaIdr = number_format($CAging->hitungTotal($userId, "IDR", "tiga"), 2, ",", ".");
	$totalRangeEmpatIdr = "";
	if($CAging->hitungTotal($userId, "IDR", "empat") != 0.00)
		$totalRangeEmpatIdr = number_format($CAging->hitungTotal($userId, "IDR", "empat"), 2, ",", ".");
    $totalRangeLimaIdr = "";
    if($CAging->hitungTotal($userId, "IDR", "lima") != 0.00)
        $totalRangeLimaIdr = number_format($CAging->hitungTotal($userId, "IDR", "lima"), 2, ",", ".");
	
//kondisi dan function Total USD	
	$totalUsd = "";
	if($CAging->hitungTotal($userId, "USD", "") != 0.00)
		$totalUsd = number_format($CAging->hitungTotal($userId, "USD", ""), 2, ",", ".");
	$totalRangeSatuUsd = "";
	if($CAging->hitungTotal($userId, "USD", "satu") != 0.00)
		$totalRangeSatuUsd = number_format($CAging->hitungTotal($userId, "USD", "satu"), 2, ",", ".");
	$totalRangeDuaUsd = "";
	if($CAging->hitungTotal($userId, "USD", "dua") != 0.00)
		$totalRangeDuaUsd = number_format($CAging->hitungTotal($userId, "USD", "dua"), 2, ",", ".");
	$totalRangeTigaUsd = "";
	if($CAging->hitungTotal($userId, "USD", "tiga") != 0.00)
		$totalRangeTigaUsd = number_format($CAging->hitungTotal($userId, "USD", "tiga"), 2, ",", ".");
	$totalRangeEmpatUsd = "";
	if($CAging->hitungTotal($userId, "USD", "empat") != 0.00)
		$totalRangeEmpatUsd = number_format($CAging->hitungTotal($userId, "USD", "empat"), 2, ",", ".");
    $totalRangeLimaUsd = "";
    if($CAging->hitungTotal($userId, "USD", "lima") != 0.00)
        $totalRangeLimaUsd = number_format($CAging->hitungTotal($userId, "USD", "lima"), 2, ",", ".");

    //kondisi bukan USD dan IDR
    if($current != "IDR" AND $current != "USD")
    {
        if($currOther1 == "")
        {
            $currOther1 = $current;

            if($CAging->hitungTotal($userId, $current, "satu") != 0.00)
            $totalOther1RangeSatu = number_format($CAging->hitungTotal($userId, $current, "satu"), 2, ",", ".");
            if($CAging->hitungTotal($userId, $current, "dua") != 0.00)
            $totalOther1RangeDua = number_format($CAging->hitungTotal($userId, $current, "dua"), 2, ",", ".");
            if($CAging->hitungTotal($userId, $current, "tiga") != 0.00)
            $totalOther1RangeTiga = number_format($CAging->hitungTotal($userId, $current, "tiga"), 2, ",", ".");
            if($CAging->hitungTotal($userId, $current, "empat") != 0.00)
            $totalOther1RangeEmpat = number_format($CAging->hitungTotal($userId, $current, "empat"), 2, ",", ".");
            if($CAging->hitungTotal($userId, $current, "lima") != 0.00)
            $totalOther1RangeLima = number_format($CAging->hitungTotal($userId, $current, "lima"), 2, ",", ".");
        }else{
            if($currOther1 == $current)
            {
                if($CAging->hitungTotal($userId, $current, "satu") != 0.00)
                $totalOther1RangeSatu = number_format($CAging->hitungTotal($userId, $current, "satu"), 2, ",", ".");
                if($CAging->hitungTotal($userId, $current, "dua") != 0.00)
                $totalOther1RangeDua = number_format($CAging->hitungTotal($userId, $current, "dua"), 2, ",", ".");
                if($CAging->hitungTotal($userId, $current, "tiga") != 0.00)
                $totalOther1RangeTiga = number_format($CAging->hitungTotal($userId, $current, "tiga"), 2, ",", ".");
                if($CAging->hitungTotal($userId, $current, "empat") != 0.00)
                $totalOther1RangeEmpat = number_format($CAging->hitungTotal($userId, $current, "empat"), 2, ",", ".");
                if($CAging->hitungTotal($userId, $current, "lima") != 0.00)
                $totalOther1RangeLima = number_format($CAging->hitungTotal($userId, $current, "lima"), 2, ",", ".");
            }else{
                if($currOther2 == "")
                {
                    $currOther2 = $current;                    
                }
                if($CAging->hitungTotal($userId, $current, "satu") != 0.00)
                $totalOther2RangeSatu = number_format($CAging->hitungTotal($userId, $current, "satu"), 2, ",", ".");
                if($CAging->hitungTotal($userId, $current, "dua") != 0.00)
                $totalOther2RangeDua = number_format($CAging->hitungTotal($userId, $current, "dua"), 2, ",", ".");
                if($CAging->hitungTotal($userId, $current, "tiga") != 0.00)
                $totalOther2RangeTiga = number_format($CAging->hitungTotal($userId, $current, "tiga"), 2, ",", ".");
                if($CAging->hitungTotal($userId, $current, "empat") != 0.00)
                $totalOther2RangeEmpat = number_format($CAging->hitungTotal($userId, $current, "empat"), 2, ",", ".");
                if($CAging->hitungTotal($userId, $current, "lima") != 0.00)
                $totalOther2RangeLima = number_format($CAging->hitungTotal($userId, $current, "lima"), 2, ",", ".");
            }
        }        
    }
	
	$recDt = $row['receivedate'];
	
//bgColor genap ganjil		
	$bgClr = "background-color:#F0F1FF;";
	if($row['urutangrup'] %2 == 0)
		$bgClr = "background-color:#FFFFF;";

// Jika urutan grup = 1, maka tampilkan nama vendor
	if($row['urutangrup'] == 1)	{
	?>
                    <!-- Nama Vendor -->
                    <tr>
                        <td class="tabelBorderBottomJust" colspan="13" height="28"
                            style="font:1em Arial Narrow;font-weight:bold;">
                            &nbsp;<?php echo $row['kreditacc']." - ".$row['vendor']." - ".$row['currency']; ?></td>
                    </tr>
                    <?php } ?>

                    <!-- LIST -->
                    <tr style="font:0.8em Arial Narrow;font-weight:normal;">
                        <td height="22" width="30" style="<?php echo $bgClr;?>" class="tabelBorderRightJust"
                            align="center"><?php echo $row['urutangrup']; ?></td>
                        <td width="220" style=" <?php echo $bgClr;?>padding-left:3px;" class="tabelBorderRightJust">
                            <div style="width:120px; word-wrap:break-word;"><?php echo $row['mailinvno']; ?></div>
                        </td>
                        <td width="150" style=" <?php echo $bgClr;?>" class="tabelBorderRightJust" align="center">
                            <?php echo $CPublic->convTglNonDB($row['receivedate']); ?></td>
                        <td width="150" style=" <?php echo $bgClr;?>" class="tabelBorderRightJust" align="center">
                            <?php echo $CPublic->convTglNonDB($row['tglinvoice']); ?></td>
                        <td width="100" style=" <?php echo $bgClr;?>" class="tabelBorderRightJust" align="center">
                            <?php echo $row['dueday']; ?></td>
                        <td width="150" style=" <?php echo $bgClr;?>" class="tabelBorderRightJust" align="center">
                            <?php echo $CPublic->convTglNonDB($row['tglexp']); ?></td>
                        <td width="100" style=" <?php echo $bgClr;?>" class="tabelBorderRightJust" align="right">
                            <?php echo $current; ?>&nbsp;</td>
                        <td width="120" style=" <?php echo $bgClr;?>" class="tabelBorderRightJust" align="right">
                            <?php echo $rangeSatu; ?>&nbsp;</td>
                        <td width="120" style=" <?php echo $bgClr;?>" class="tabelBorderRightJust" align="right">
                            <?php echo $rangeDua; ?>&nbsp;</td>
                        <td width="120" style=" <?php echo $bgClr;?>" class="tabelBorderRightJust" align="right">
                            <?php echo $rangeTiga; ?>&nbsp;</td>
                        <td width="120" style=" <?php echo $bgClr;?>" class="tabelBorderRightJust" align="right">
                            <?php echo $rangeEmpat; ?>&nbsp;</td>
                        <td width="120" style=" <?php echo $bgClr;?>" class="tabelBorderLeftRightNull" align="right">
                            <?php echo $rangeLima; ?>&nbsp;</td>
                    </tr>

                    <?php
	//echo $CAging->jmlInvoice($userId, $row['kreditacc'], $row['currency']);
		if($row['urutangrup'] == $CAging->jmlInvoiceOut($userId, $row['kreditacc'], $row['vendor'], $row['currency']))	{
	?>
                    <!-- Sub Total -->
                    <tr style="font:0.9em Arial Narrow;font-weight:bold;" height="20">
                        <td class="tabelBorderLeftRightNull" colspan="6" height="24">&nbsp;</td>
                        <td class="tabelBorderLeftNull">&nbsp;Sub Total</td>
                        <td class="tabelBorderLeftNull" align="right"><?php echo $subRangeSatu; ?>&nbsp;</td>
                        <td class="tabelBorderLeftNull" align="right"><?php echo $subRangeDua; ?>&nbsp;</td>
                        <td class="tabelBorderLeftNull" align="right"><?php echo $subRangeTiga; ?>&nbsp;</td>
                        <td class="tabelBorderLeftNull" align="right"><?php echo $subRangeEmpat; ?>&nbsp;</td>
                        <td class="tabelBorderLeftRightNull" align="right"><?php echo $subRangeLima; ?>&nbsp;</td>
                    </tr>

                    <?php
        }
    }
?>
                    <!-- Total IDR & USD -->
                    <tr style="font:0.9em Arial Narrow;font-weight:bold;background-color:#F5F5DC;">
                        <td class="tabelBorderLeftRightNull" colspan="6" height="24">&nbsp;</td>
                        <td class="tabelBorderRightJust" style="font:1.1em Arial Narrow;font-weight:bold;">&nbsp;Total -
                            IDR</td>
                        <td class="tabelBorderRightJust" align="right"><?php echo $totalRangeSatuIdr; ?>&nbsp;</td>
                        <td class="tabelBorderRightJust" align="right"><?php echo $totalRangeDuaIdr; ?>&nbsp;</td>
                        <td class="tabelBorderRightJust" align="right"><?php echo $totalRangeTigaIdr; ?>&nbsp;</td>
                        <td class="tabelBorderRightJust" align="right"><?php echo $totalRangeEmpatIdr; ?>&nbsp;</td>
                        <td class="tabelBorderLeftRightNull" align="right"><?php echo $totalRangeLimaIdr; ?>&nbsp;</td>
                    </tr>

                    <tr style="font:0.8em Arial Narrow;font-weight:bold;background-color:#F5F5DC;">
                        <td class="tabelBorderLeftRightNull" colspan="6" height="24" class="tabelBorderLeftRightNull">
                            &nbsp;</td>
                        <td class="tabelBorderLeftNull" style="font:1.1em Arial Narrow;font-weight:bold;">&nbsp;Total -
                            USD</td>
                        <td class="tabelBorderLeftNull" align="right"><?php echo $totalRangeSatuUsd; ?>&nbsp;</td>
                        <td class="tabelBorderLeftNull" align="right"><?php echo $totalRangeDuaUsd; ?>&nbsp;</td>
                        <td class="tabelBorderLeftNull" align="right"><?php echo $totalRangeTigaUsd; ?>&nbsp;</td>
                        <td class="tabelBorderLeftNull" align="right"><?php echo $totalRangeEmpatUsd; ?>&nbsp;</td>
                        <td class="tabelBorderLeftRightNull" align="right"><?php echo $totalRangeLimaUsd; ?>&nbsp;</td>
                    </tr>
                    <?php
            if($currOther1 != ""){
        ?>
                    <tr style="font:0.8em Arial Narrow;font-weight:bold;background-color:#F5F5DC;">
                        <td class="tabelBorderLeftRightNull" colspan="6" height="24">&nbsp;</td>
                        <td class="tabelBorderRightJust" style="font:1.1em Arial Narrow;font-weight:bold;">&nbsp;Total -
                            <?php echo $currOther1;?></td>
                        <td class="tabelBorderRightJust" align="right"><?php echo $totalOther1RangeSatu; ?>&nbsp;</td>
                        <td class="tabelBorderRightJust" align="right"><?php echo $totalOther1RangeDua; ?>&nbsp;</td>
                        <td class="tabelBorderRightJust" align="right"><?php echo $totalOther1RangeTiga; ?>&nbsp;</td>
                        <td class="tabelBorderRightJust" align="right"><?php echo $totalOther1RangeEmpat; ?>&nbsp;</td>
                        <td class="tabelBorderLeftRightNull" align="right"><?php echo $totalOther1RangeLima; ?>&nbsp;
                        </td>
                    </tr>
                    <?php
            }
            if($currOther2 != ""){
        ?>
                    <tr style="font:0.8em Arial Narrow;font-weight:bold;background-color:#F5F5DC;">
                        <td class="tabelBorderLeftRightNull" colspan="6" height="24">&nbsp;</td>
                        <td class="tabelBorderRightJust" style="font:1.1em Arial Narrow;font-weight:bold;">&nbsp;Total -
                            <?php echo $currOther2;?></td>
                        <td class="tabelBorderRightJust" align="right"><?php echo $totalOther2RangeSatu; ?>&nbsp;</td>
                        <td class="tabelBorderRightJust" align="right"><?php echo $totalOther2RangeDua; ?>&nbsp;</td>
                        <td class="tabelBorderRightJust" align="right"><?php echo $totalOther2RangeTiga; ?>&nbsp;</td>
                        <td class="tabelBorderRightJust" align="right"><?php echo $totalOther2RangeEmpat; ?>&nbsp;</td>
                        <td class="tabelBorderLeftRightNull" align="right"><?php echo $totalOther2RangeLima; ?>&nbsp;
                        </td>
                    </tr>
                    <?php } ?>

                </table>
            </td>
        </tr>

        <!-- Total IDR & USD -->
        <!--<tr valign="top">
    <td valign="top" height="24" style="background-color:#F5F5DC;">
    <table class="tabelBorderBottomJust" height="100%" cellpadding="0" cellspacing="0" width="100%" border="0" style="font:0.9em Arial Narrow;font-weight:bold;">
        <tr valign="top">
            <td width="46%">&nbsp;</td>
            <td width="9%" class="tabelBorderRightJust">&nbsp;Total - IDR</td>
            <td width="9%" class="tabelBorderRightJust">&nbsp;<?php //echo $totalIdr; ?></td>
            <td width="9%" class="tabelBorderRightJust">&nbsp;<?php //echo $totalRangeSatuIdr; ?></td>
            <td width="9%" class="tabelBorderRightJust">&nbsp;<?php //echo $totalRangeDuaIdr; ?></td>
            <td width="9%" class="tabelBorderRightJust">&nbsp;<?php //echo $totalRangeTigaIdr; ?></td>
            <td width="9%">&nbsp;<?php echo $totalRangeEmpatIdr; ?></td>
        </tr>
    </table>
    </td>
    </tr>-->

        <!--<tr valign="top">
    <td valign="top" height="24" style="background-color:#F5F5DC;">
    <table class="tabelBorderBottomJust" height="100%" cellpadding="0" cellspacing="0" width="100%" style="font:0.9em Arial Narrow;font-weight:bold;border-color:#999;">
        <tr valign="top">
            <td width="46%">&nbsp;</td>
            <td class="tabelBorderRightJust" width="9%">&nbsp;Total - USD</td>
            <td class="tabelBorderRightJust" width="9%">&nbsp;<?php //echo $totalUsd; ?></td>
           	<td class="tabelBorderRightJust" width="9%">&nbsp;<?php //echo $totalRangeSatuUsd; ?></td>
            <td class="tabelBorderRightJust" width="9%">&nbsp;<?php //echo $totalRangeDuaUsd; ?></td>
            <td class="tabelBorderRightJust" width="9%">&nbsp;<?php //echo $totalRangeTigaUsd; ?></td>
            <td width="9%">&nbsp;<?php echo $totalRangeEmpatUsd; ?></td>
        </tr>
    </table>
    </td>-->
        </tr>
    </table>
</body>

</HTML>