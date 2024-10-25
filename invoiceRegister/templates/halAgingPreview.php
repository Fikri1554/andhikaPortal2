<!DOCTYPE HTML>
<?php	
require_once("../configInvReg.php");	

$userId = $_GET['userId'];
$company = $_GET['company'];
$fromDate = $_GET['fromDate'];
$endDate = $_GET['endDate'];
$date = $CPublic->convTglDB($_GET['fromDate']);

$tgl = substr($endDate,0,2);
$bln = substr($endDate,3,2);
$thn = substr($endDate,6,4);
$dateDisp = ucfirst(strtolower($CPublic->detilBulanNamaAngka($bln, "eng")))." ".$tgl.", ".$thn;

?>

<link href="../css/table.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery-1.4.2.min.js"></script>
<script>
this.window.onload = 
function() 
{
	parent.$('#loaderImg').css('visibility','hidden');
	parent.$('#btnPrintAgingSum').removeAttr('disabled');
	parent.$('#btnPrintAgingSum').attr('class','btnStandar');
    parent.$('#btnExportExcel').removeAttr('disabled');
    parent.$('#btnExportExcel').attr('class','btnStandar');
	
}
</script>

<body>
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="color:#555;"> 

    <tr>
        <td height="20" style="font:1.2em Arial Narrow;font-weight:bold;background-color:#F9F9F9;">&nbsp;<?php echo $CInvReg->detilComp($company, "compname");?></td>
    </tr>
    <tr>
        <td height="20" style="font:1em Arial Narrow;font-weight:normal;background-color:#F9F9F9;">&nbsp;Account Payable Aging Report</td>
    </tr>
    <tr>
        <td height="20" style="font:1em Arial Narrow;font-weight:normal;background-color:#F9F9F9;">&nbsp;as of <?php echo $dateDisp;?></td>
    </tr>
    
<!-- HEAD TABEL -->
    <tr>
    <td width="100%">
    <div id="divTable" style="width:1500px;background-color:#8A8A8A;color:#F9F9F9;">
    <table class="tabelBorderBottomJust" width="1500" style="font-family:Arial;font-weight:bold;font-size:11px;border-color:#333;border-bottom-width:2px">
        <tr align="center">
            <td rowspan="2" width="30" align="center">NO</td>
            <td rowspan="2" width="200" align="center">INVOICE NO</td>
            <td rowspan="2" width="120" align="center">BARCODE</td>
            <td rowspan="2" width="150" align="center">RECEIVED DATE</td>
            <td rowspan="2" width="150" align="center">INVOICE DATE</td>
            <td rowspan="2" width="100" align="center">TERM</td>
            <td rowspan="2" width="150" align="center">DUE DATE</td>
            <td rowspan="2" width="100" align="center">CURRENCY</td>
            <td rowspan="2" width="100" align="center">CURRENT</td>
            <td colspan="5" width="500" class="tabelBorderBottomJust" style="border-color:#666;" align="center">OVERDUE</td>
            <td rowspan="2" width="100" align="center">TOTAL</td>
        </tr>
        <tr align="center">
            <!--<td width="9%">DATE</td>-->
            <td width="100" align="center">1 - 30 DAYS</td>
            <td width="100" align="center">31 - 60 DAYS</td>
            <td width="100" align="center">61 - 90 DAYS</td>
            <td width="100" align="center">91 - 360 DAYS</td>
            <td width="100" align="center">> 360 DAYS</td>
        </tr>
    </table>
    </div>
    <div id="divTableTemp" style=""></div>
    </td>
    </tr>
     
<!-- ISI -->
    <tr>
    <td>
    <table cellpadding="0" cellspacing="0" width="1500" border="0">
<?php
    $currOther1 = "";
    $totalOther1Current = 0;
    $totalOther1RangeSatu = 0;
    $totalOther1RangeDua = 0;
    $totalOther1RangeTiga = 0;
    $totalOther1RangeEmpat = 0;
    $totalOther1RangeLima = 0;
    $currOther2 = "";
    $totalOther2Current = 0;
    $totalOther2RangeSatu = 0;
    $totalOther2RangeDua = 0;
    $totalOther2RangeTiga = 0;
    $totalOther2RangeEmpat = 0;
    $totalOther2RangeLima = 0;
    
    $totalCurrentIdr = 0;
    $totalRange1Idr = 0;
    $totalRange2Idr = 0;
    $totalRange3Idr = 0;
    $totalRange4Idr = 0;
    $totalRange5Idr = 0;
    $totalCurrentUsd = 0;
    $totalRange1Usd = 0;
    $totalRange2Usd = 0;
    $totalRange3Usd = 0;
    $totalRange4Usd = 0;
    $totalRange5Usd = 0;

    $noTemp = 1;
    $frmDateConv = $CPublic->convTglDB($fromDate);
    $endDateConv = $CPublic->convTglDB($endDate);

    $tempData = array();
    $sql = "SELECT *
            FROM (
            SELECT kreditacc,vendor,currency,urutan,urutangrup,idmailinv,company,datedisp,mailinvno,barcode,receivedate,tglinvoice,dueday,tglexp,subaccount,amount,rangesatu,rangedua,rangetiga,rangeempat,rangelima FROM summaryaging 
            WHERE userid = '".$userId."'
            UNION ALL
            SELECT kreditacc,vendor,currency,urutan,urutangrup,idmailinv,company,datedisp,mailinvno,barcode,receivedate,tglinvoice,dueday,tglexp,subaccount,amount,rangesatu,rangedua,rangetiga,rangeempat,rangelima FROM tempsummaryaging
            WHERE paid = 'N' AND company = '".$company."' AND receivedate BETWEEN '".$frmDateConv."' AND '".$endDateConv."'
            ) A ORDER BY kreditacc,subaccount ASC
            ";
    $query = $CKoneksiInvReg->mysqlQuery($sql);
    while($row = $CKoneksiInvReg->mysqlFetch($query))
    {
        $txtSubAcct = "";

        if($row['subaccount'] != "")
        {
            $ds = $CInvReg->getSubAccount($row['company'],$row['kreditacc'],$row['subaccount']);

            if($ds != "")
            {
                $txtSubAcct = $row['subaccount']." - ".$ds;
            }
        }

        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['urutangrup'] = $row['urutangrup'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['idmailinv'] = $row['idmailinv'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['company'] = $row['company'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['datedisp'] = $row['datedisp'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['kreditacc'] = $row['kreditacc'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['vendor'] = $row['vendor'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['currency'] = $row['currency'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['mailinvno'] = $row['mailinvno'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['barcode'] = $row['barcode'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['receivedate'] = $row['receivedate'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['tglinvoice'] = $row['tglinvoice'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['dueday'] = $row['dueday'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['tglexp'] = $row['tglexp'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['subaccount'] = $row['subaccount'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['amount'] = $row['amount'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['rangesatu'] = $row['rangesatu'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['rangedua'] = $row['rangedua'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['rangetiga'] = $row['rangetiga'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['rangeempat'] = $row['rangeempat'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['rangelima'] = $row['rangelima'];
        $noTemp++;
    }
    
    //echo "<pre>";print_r($tempData);exit;
    $endDates = $CPublic->convTglDB($endDate);
    $trNya = "";

    foreach ($tempData as $key => $val)
    {
        $trNya .= "<tr>";
            $trNya .= "<td class=\"tabelBorderBottomJust\" colspan=\"15\" height=\"28\" style=\"font:1em Arial Narrow;font-weight:bold;\">";
                $trNya .= $key;
            $trNya .= "</td>";
        $trNya .= "</tr>";

        foreach ($val as $keys => $vals)
        {
            $no=1;
            $subTotalCurrent = 0;
            $subTotalRangeSatu = 0;
            $subTotalRangeDua = 0;
            $subTotalRangeTiga = 0;
            $subTotalRangeEmpat = 0;
            $subTotalRangeLima = 0;
            
            if($keys == "")
            {
                $keys = "-";
            }

            $trNya .= "<tr>";
                $trNya .= "<td class=\"tabelBorderBottomJust\" colspan=\"15\" height=\"22\" style=\"padding-left:30px;font:0.8em Arial Narrow;font-weight:bold;\">";
                    $trNya .= $keys;
                $trNya .= "</td>";
            $trNya .= "</tr>";
            
            foreach ($vals as $keyd => $value)
            {
                $bgClr = "background-color:#F0F1FF;";
                $currentNya = 0;
                $rangeSatu = 0;
                $rangeDua = 0;
                $rangeTiga = 0;
                $rangeEmpat = 0;
                $rangeLima = 0;
                $currentNyaView = "";
                $rangeSatuView = "";
                $rangeDuaView = "";
                $rangeTigaView = "";
                $rangeEmpatView = "";
                $rangeLimaView = "";

                if($value['urutangrup'] %2 == 0) { $bgClr = "background-color:#FFFFF;"; }
                
                $interval = $CPublic->perbedaanHari(str_replace("-","",$endDates),str_replace("-","",$value['tglexp']));

                if($interval < 1)
                {
                    $currentNya = $value['amount'];
                    $currentNyaView = number_format($currentNya, 2, ",", ".");

                    if(strtoupper($value['currency']) == "IDR")
                    {
                        $totalCurrentIdr = $totalCurrentIdr + $currentNya;
                    }
                    if(strtoupper($value['currency']) == "USD")
                    {
                        $totalCurrentUsd = $totalCurrentUsd + $currentNya;
                    }

                    if(strtoupper($value['currency']) != "IDR" AND strtoupper($value['currency']) != "USD")
                    {
                        if($currOther1 == "")
                        {
                            $currOther1 = $value['currency'];
                            $totalOther1Current = $totalOther1Current + $currentNya;
                        }else{
                            if($currOther1 == $value['currency'])
                            {
                                $totalOther1Current = $totalOther1Current + $currentNya;
                            }else{
                                if($currOther2 == "")
                                {
                                    $currOther2 = $value['currency'];
                                }
                                $totalOther2Current = $totalOther2Current + $currentNya;
                            }
                        }
                    }
                }
                if($interval >= 1 && $interval <= 30)
                {
                    $rangeSatu = $value['amount'];
                    $rangeSatuView = number_format($rangeSatu, 2, ",", ".");

                    if(strtoupper($value['currency']) == "IDR")
                    {
                        $totalRange1Idr = $totalRange1Idr + $rangeSatu;
                    }
                    if(strtoupper($value['currency']) == "USD")
                    {
                        $totalRange1Usd = $totalRange1Usd + $rangeSatu;
                    }

                    if(strtoupper($value['currency']) != "IDR" AND strtoupper($value['currency']) != "USD")
                    {
                        if($currOther1 == "")
                        {
                            $currOther1 = $value['currency'];
                            $totalOther1RangeSatu = $totalOther1RangeSatu + $rangeSatu;
                        }else{
                            if($currOther1 == $value['currency'])
                            {
                                $totalOther1RangeSatu = $totalOther1RangeSatu + $rangeSatu;
                            }else{
                                if($currOther2 == "")
                                {
                                    $currOther2 = $value['currency'];
                                }
                                $totalOther2RangeSatu = $totalOther2RangeSatu + $rangeSatu;
                            }
                        }
                    }
                }
                if($interval >= 31 && $interval <= 60)
                {
                    $rangeDua = $value['amount'];
                    $rangeDuaView = number_format($rangeDua, 2, ",", ".");

                    if(strtoupper($value['currency']) == "IDR")
                    {
                        $totalRange2Idr = $totalRange2Idr + $rangeDua;
                    }
                    if(strtoupper($value['currency']) == "USD")
                    {
                        $totalRange2Usd = $totalRange2Usd + $rangeDua;
                    }

                    if(strtoupper($value['currency']) != "IDR" AND strtoupper($value['currency']) != "USD")
                    {
                        if($currOther1 == "")
                        {
                            $currOther1 = $value['currency'];
                            $totalOther1RangeDua = $totalOther1RangeDua + $rangeDua;
                        }else{
                            if($currOther1 == $value['currency'])
                            {
                                $totalOther1RangeDua = $totalOther1RangeDua + $rangeDua;
                            }else{
                                if($currOther2 == "")
                                {
                                    $currOther2 = $value['currency'];
                                }
                                $totalOther2RangeDua = $totalOther2RangeDua + $rangeDua;
                            }
                        }
                    }
                }
                if($interval >= 61 && $interval <= 90)
                {
                    $rangeTiga = $value['amount'];
                    $rangeTigaView = number_format($rangeTiga, 2, ",", ".");

                    if(strtoupper($value['currency']) == "IDR")
                    {
                        $totalRange3Idr = $totalRange3Idr + $rangeTiga;
                    }
                    if(strtoupper($value['currency']) == "USD")
                    {
                        $totalRange3Usd = $totalRange3Usd + $rangeTiga;
                    }

                    if(strtoupper($value['currency']) != "IDR" AND strtoupper($value['currency']) != "USD")
                    {
                        if($currOther1 == "")
                        {
                            $currOther1 = $value['currency'];
                            $totalOther1RangeTiga = $totalOther1RangeTiga + $rangeTiga;
                        }else{
                            if($currOther1 == $value['currency'])
                            {
                                $totalOther1RangeTiga = $totalOther1RangeTiga + $rangeTiga;
                            }else{
                                if($currOther2 == "")
                                {
                                    $currOther2 = $value['currency'];
                                }
                                $totalOther2RangeTiga = $totalOther2RangeTiga + $rangeTiga;
                            }
                        }
                    }
                }
                 if($interval >= 91 && $interval <= 360)
                {
                    $rangeEmpat = $value['amount'];
                    $rangeEmpatView = number_format($rangeEmpat, 2, ",", ".");

                    if(strtoupper($value['currency']) == "IDR")
                    {
                        $totalRange4Idr = $totalRange4Idr + $rangeEmpat;
                    }
                    if(strtoupper($value['currency']) == "USD")
                    {
                        $totalRange4Usd = $totalRange4Usd + $rangeEmpat;
                    }

                    if(strtoupper($value['currency']) != "IDR" AND strtoupper($value['currency']) != "USD")
                    {
                        if($currOther1 == "")
                        {
                            $currOther1 = $value['currency'];
                            $totalOther1RangeEmpat = $totalOther1RangeEmpat + $rangeEmpat;
                        }else{
                            if($currOther1 == $value['currency'])
                            {
                                $totalOther1RangeEmpat = $totalOther1RangeEmpat + $rangeEmpat;
                            }else{
                                if($currOther2 == "")
                                {
                                    $currOther2 = $value['currency'];
                                }
                                $totalOther2RangeEmpat = $totalOther2RangeEmpat + $rangeEmpat;
                            }
                        }
                    }
                }
                if($interval >= 361)
                {
                    $rangeLima = $value['amount'];
                    $rangeLimaView = number_format($rangeLima, 2, ",", ".");

                    if(strtoupper($value['currency']) == "IDR")
                    {
                        $totalRange5Idr = $totalRange5Idr + $rangeLima;
                    }
                    if(strtoupper($value['currency']) == "USD")
                    {
                        $totalRange5Usd = $totalRange5Usd + $rangeLima;
                    }

                    if(strtoupper($value['currency']) != "IDR" AND strtoupper($value['currency']) != "USD")
                    {
                        if($currOther1 == "")
                        {
                            $currOther1 = $value['currency'];
                            $totalOther1RangeLima = $totalOther1RangeLima + $rangeLima;
                        }else{
                            if($currOther1 == $value['currency'])
                            {
                                $totalOther1RangeLima = $totalOther1RangeLima + $rangeLima;
                            }else{
                                if($currOther2 == "")
                                {
                                    $currOther2 = $value['currency'];
                                }
                                $totalOther2RangeLima = $totalOther2RangeLima + $rangeLima;
                            }
                        }
                    }
                }

                $rowTotal = $currentNya + $rangeSatu + $rangeDua + $rangeTiga + $rangeEmpat + $rangeLima;
                
                $trNya .= "<tr style=\"font:0.8em Arial Narrow;font-weight:normal;\">";
                     $trNya .= "<td height=\"22\" width=\"30\" style=\"".$bgClr."\" class=\"tabelBorderRightJust\" align=\"center\">";
                        $trNya .= $no;
                    $trNya .= "</td>";
                    $trNya .= "<td width=\"200\" style=\"padding-left:3px;".$bgClr."\" class=\"tabelBorderRightJust\">";
                        $trNya .= "<div style=\"width:120px; word-wrap:break-word;\">".$value['mailinvno']."</div>";
                    $trNya .= "</td>";
                    $trNya .= "<td width=\"120\" style=\"".$bgClr."\" class=\"tabelBorderRightJust\" align=\"center\">";
                        $trNya .= $value['barcode'];
                    $trNya .= "</td>";
                    $trNya .= "<td width=\"150\" style=\"".$bgClr."\" class=\"tabelBorderRightJust\" align=\"center\">";
                        $trNya .= $CPublic->convTglNonDB($value['receivedate']);
                    $trNya .= "</td>";
                    $trNya .= "<td width=\"150\" style=\"".$bgClr."\" class=\"tabelBorderRightJust\" align=\"center\">";
                        $trNya .= $CPublic->convTglNonDB($value['tglinvoice']);
                    $trNya .= "</td>";
                    $trNya .= "<td width=\"100\" style=\"".$bgClr."\" class=\"tabelBorderRightJust\" align=\"center\">";
                        $trNya .= $value['dueday'];
                    $trNya .= "</td>";
                    $trNya .= "<td width=\"150\" style=\"".$bgClr."\" class=\"tabelBorderRightJust\" align=\"center\">";
                        $trNya .= $CPublic->isWeekendNya($value['tglexp']);
                    $trNya .= "</td>";
                    $trNya .= "<td width=\"100\" style=\"".$bgClr."\" class=\"tabelBorderRightJust\" align=\"center\">";
                        $trNya .= $value['currency'];
                    $trNya .= "</td>";
                    $trNya .= "<td width=\"100\" style=\"".$bgClr."\" class=\"tabelBorderRightJust\" align=\"right\">";
                        $trNya .= $currentNyaView;
                    $trNya .= "</td>";
                    $trNya .= "<td width=\"100\" style=\" <?php echo $bgClr;?>\" class=\"tabelBorderRightJust\" align=\"right\">";
                        $trNya .= $rangeSatuView;
                    $trNya .= "</td>";
                    $trNya .= "<td width=\"100\" style=\" <?php echo $bgClr;?>\" class=\"tabelBorderRightJust\" align=\"right\">";
                        $trNya .= $rangeDuaView;
                    $trNya .= "</td>";
                    $trNya .= "<td width=\"100\" style=\" <?php echo $bgClr;?>\" class=\"tabelBorderRightJust\" align=\"right\">";
                        $trNya .= $rangeTigaView;
                    $trNya .= "</td>";
                    $trNya .= "<td width=\"100\" style=\" <?php echo $bgClr;?>\" class=\"tabelBorderRightJust\" align=\"right\">";
                        $trNya .= $rangeEmpatView;
                    $trNya .= "</td>";
                    $trNya .= "<td width=\"100\" style=\" <?php echo $bgClr;?>\" class=\"tabelBorderRightJust\" align=\"right\">";
                        $trNya .= $rangeLimaView;
                    $trNya .= "</td>";
                    $trNya .= "<td width=\"100\" style=\" <?php echo $bgClr;?>\" class=\"tabelBorderRightJust\" align=\"right\">";
                        $trNya .= number_format($rowTotal, 2, ",", ".");
                    $trNya .= "</td>";
                $trNya .= "</tr>";

                $subTotalCurrent = $subTotalCurrent + $currentNya;
                $subTotalRangeSatu = $subTotalRangeSatu + $rangeSatu;
                $subTotalRangeDua = $subTotalRangeDua + $rangeDua;
                $subTotalRangeTiga = $subTotalRangeTiga + $rangeTiga;
                $subTotalRangeEmpat = $subTotalRangeEmpat + $rangeEmpat;
                $subTotalRangeLima = $subTotalRangeLima + $rangeLima;

                $no++;
            }

            $subRowTotal = 0;
            if($subTotalCurrent > 0)
            {
                $subRowTotal = $subRowTotal + $subTotalCurrent;
                $subTotalCurrent = number_format($subTotalCurrent, 2, ",", ".");
            }else{
                $subTotalCurrent = "";
            }

            if($subTotalRangeSatu > 0)
            {
                $subRowTotal = $subRowTotal + $subTotalRangeSatu;
                $subTotalRangeSatu = number_format($subTotalRangeSatu, 2, ",", ".");
            }else{
                $subTotalRangeSatu = "";
            }

            if($subTotalRangeDua > 0)
            {
                $subRowTotal = $subRowTotal + $subTotalRangeDua;
                $subTotalRangeDua = number_format($subTotalRangeDua, 2, ",", ".");
            }else{
                $subTotalRangeDua = "";
            }

            if($subTotalRangeTiga > 0)
            {
                $subRowTotal = $subRowTotal + $subTotalRangeTiga;
                $subTotalRangeTiga = number_format($subTotalRangeTiga, 2, ",", ".");
            }else{
                $subTotalRangeTiga = "";
            }

            if($subTotalRangeEmpat > 0)
            {
                $subRowTotal = $subRowTotal + $subTotalRangeEmpat;
                $subTotalRangeEmpat = number_format($subTotalRangeEmpat, 2, ",", ".");
            }else{
                $subTotalRangeEmpat = "";
            }

            if($subTotalRangeLima > 0)
            {
                $subRowTotal = $subRowTotal + $subTotalRangeLima;
                $subTotalRangeLima = number_format($subTotalRangeLima, 2, ",", ".");
            }else{
                $subTotalRangeLima = "";
            }

            $trNya .= "<tr style=\"font:0.9em Arial Narrow;font-weight:bold;\" height=\"20\">";
                $trNya .= "<td class=\"tabelBorderLeftRightNull\" colspan=\"7\" height=\"24\">&nbsp;</td>";
                $trNya .= "<td class=\"tabelBorderLeftNull\">&nbsp;Sub Total</td>";
                $trNya .= "<td class=\"tabelBorderLeftNull\" align=\"right\">".$subTotalCurrent."</td>";
                $trNya .= "<td class=\"tabelBorderLeftNull\" align=\"right\">".$subTotalRangeSatu."</td>";
                $trNya .= "<td class=\"tabelBorderLeftNull\" align=\"right\">".$subTotalRangeDua."</td>";
                $trNya .= "<td class=\"tabelBorderLeftNull\" align=\"right\">".$subTotalRangeTiga."</td>";
                $trNya .= "<td class=\"tabelBorderLeftNull\" align=\"right\">".$subTotalRangeEmpat."</td>";
                $trNya .= "<td class=\"tabelBorderLeftNull\" align=\"right\">".$subTotalRangeLima."</td>";
                $trNya .= "<td class=\"tabelBorderLeftNull\" align=\"right\">".number_format($subRowTotal, 2, ",", ".")."</td>";
            $trNya .= "</tr>";            
        }
    }

    $totalRowIdr = $totalCurrentIdr + $totalRange1Idr + $totalRange2Idr + $totalRange3Idr + $totalRange4Idr + $totalRange5Idr;
    $totalRowUsd = $totalCurrentUsd + $totalRange1Usd + $totalRange2Usd + $totalRange3Usd + $totalRange4Usd + $totalRange5Usd;

    if($totalCurrentIdr > 0) { $totalCurrentIdr = number_format($totalCurrentIdr, 2, ",", "."); } else { $totalCurrentIdr = ""; }
    if($totalRange1Idr > 0) { $totalRange1Idr = number_format($totalRange1Idr, 2, ",", "."); } else { $totalRange1Idr = ""; }
    if($totalRange2Idr > 0) { $totalRange2Idr = number_format($totalRange2Idr, 2, ",", "."); } else { $totalRange2Idr = ""; }
    if($totalRange3Idr > 0) { $totalRange3Idr = number_format($totalRange3Idr, 2, ",", "."); } else { $totalRange3Idr = ""; }
    if($totalRange4Idr > 0) { $totalRange4Idr = number_format($totalRange4Idr, 2, ",", "."); } else { $totalRange4Idr = ""; }
    if($totalRange5Idr > 0) { $totalRange5Idr = number_format($totalRange5Idr, 2, ",", "."); } else { $totalRange5Idr = ""; }
    if($totalCurrentUsd > 0) { $totalCurrentUsd = number_format($totalCurrentUsd, 2, ",", "."); } else { $totalCurrentUsd = ""; }
    if($totalRange1Usd > 0) { $totalRange1Usd = number_format($totalRange1Usd, 2, ",", "."); } else { $totalRange1Usd = ""; }
    if($totalRange2Usd > 0) { $totalRange2Usd = number_format($totalRange2Usd, 2, ",", "."); } else { $totalRange2Usd = ""; }
    if($totalRange3Usd > 0) { $totalRange3Usd = number_format($totalRange3Usd, 2, ",", "."); } else { $totalRange3Usd = ""; }
    if($totalRange4Usd > 0) { $totalRange4Usd = number_format($totalRange4Usd, 2, ",", "."); } else { $totalRange4Usd = ""; }
    if($totalRange5Usd > 0) { $totalRange5Usd = number_format($totalRange5Usd, 2, ",", "."); } else { $totalRange5Usd = ""; }

    $trNya .= "<tr style=\"font:0.8em Arial Narrow;font-weight:bold;background-color:#F5F5DC;\">";
        $trNya .= "<td colspan=\"7\" height=\"24\">&nbsp;</td>";
        $trNya .= "<td class=\"tabelBorderRightJust\" style=\"font:1.1em Arial Narrow;font-weight:bold;\">&nbsp;Total - IDR</td>";
        $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalCurrentIdr."</td>";
        $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalRange1Idr."</td>";
        $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalRange2Idr."</td>";
        $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalRange3Idr."</td>";
        $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalRange4Idr."</td>";
        $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalRange5Idr."</td>";
        $trNya .= "<td align=\"right\">".number_format($totalRowIdr, 2, ",", ".")."</td>";
    $trNya .= "</tr>";
    $trNya .= "<tr style=\"font:0.8em Arial Narrow;font-weight:bold;background-color:#F5F5DC;\">";
        $trNya .= "<td colspan=\"7\" height=\"24\">&nbsp;</td>";
        $trNya .= "<td class=\"tabelBorderRightJust\" style=\"font:1.1em Arial Narrow;font-weight:bold;\">&nbsp;Total - USD</td>";
        $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalCurrentUsd."</td>";
        $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalRange1Usd."</td>";
        $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalRange2Usd."</td>";
        $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalRange3Usd."</td>";
        $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalRange4Usd."</td>";
        $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalRange5Usd."</td>";
        $trNya .= "<td align=\"right\">".number_format($totalRowUsd, 2, ",", ".")."</td>";
    $trNya .= "</tr>";
    
    if($currOther1 != "")
    {
        $totalRowOther1 = $totalOther1Current + $totalOther1RangeSatu + $totalOther1RangeDua + $totalOther1RangeTiga + $totalOther1RangeEmpat + $totalOther1RangeLima;

        if($totalOther1Current > 0) { $totalOther1Current = number_format($totalOther1Current, 2, ",", "."); } else { $totalOther1Current = ""; }
        if($totalOther1RangeSatu > 0) { $totalOther1RangeSatu = number_format($totalOther1RangeSatu, 2, ",", "."); } else { $totalOther1RangeSatu = ""; }
        if($totalOther1RangeDua > 0) { $totalOther1RangeDua = number_format($totalOther1RangeDua, 2, ",", "."); } else { $totalOther1RangeDua = ""; }
        if($totalOther1RangeTiga > 0) { $totalOther1RangeTiga = number_format($totalOther1RangeTiga, 2, ",", "."); } else { $totalOther1RangeTiga = ""; }
        if($totalOther1RangeEmpat > 0){ $totalOther1RangeEmpat= number_format($totalOther1RangeEmpat, 2, ",", "."); }else{ $totalOther1RangeEmpat = ""; }
        if($totalOther1RangeLima > 0) { $totalOther1RangeLima = number_format($totalOther1RangeLima, 2, ",", "."); } else { $totalOther1RangeLima = ""; }

        $trNya .= "<tr style=\"font:0.8em Arial Narrow;font-weight:bold;background-color:#F5F5DC;\">";
            $trNya .= "<td colspan=\"7\" height=\"24\">&nbsp;</td>";
            $trNya .= "<td class=\"tabelBorderRightJust\" style=\"font:1.1em Arial Narrow;font-weight:bold;\">&nbsp;Total - ".$currOther1."</td>";
            $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther1Current."</td>";
            $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther1RangeSatu."</td>";
            $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther1RangeDua."</td>";
            $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther1RangeTiga."</td>";
            $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther1RangeEmpat."</td>";
            $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther1RangeLima."</td>";
            $trNya .= "<td align=\"right\">".number_format($totalRowOther1, 2, ",", ".")."</td>";
        $trNya .= "</tr>";
    }

    if($currOther2 != "")
    {
        $totalRowOther2 = $totalOther2Current + $totalOther2RangeSatu + $totalOther2RangeDua + $totalOther2RangeTiga + $totalOther2RangeEmpat + $totalOther2RangeLima;

        if($totalOther2Current > 0) { $totalOther2Current = number_format($totalOther2Current, 2, ",", "."); } else { $totalOther2Current = ""; }
        if($totalOther2RangeSatu > 0) { $totalOther2RangeSatu = number_format($totalOther2RangeSatu, 2, ",", "."); } else { $totalOther2RangeSatu = ""; }
        if($totalOther2RangeDua > 0) { $totalOther2RangeDua = number_format($totalOther2RangeDua, 2, ",", "."); } else { $totalOther2RangeDua = ""; }
        if($totalOther2RangeTiga > 0) { $totalOther2RangeTiga = number_format($totalOther2RangeTiga, 2, ",", "."); } else { $totalOther2RangeTiga = ""; }
        if($totalOther2RangeEmpat > 0){ $totalOther2RangeEmpat= number_format($totalOther2RangeEmpat, 2, ",", "."); }else{ $totalOther2RangeEmpat = ""; }
        if($totalOther2RangeLima > 0) { $totalOther2RangeLima = number_format($totalOther2RangeLima, 2, ",", "."); } else { $totalOther2RangeLima = ""; }

        $trNya .= "<tr style=\"font:0.8em Arial Narrow;font-weight:bold;background-color:#F5F5DC;\">";
            $trNya .= "<td colspan=\"7\" height=\"24\">&nbsp;</td>";
            $trNya .= "<td class=\"tabelBorderRightJust\" style=\"font:1.1em Arial Narrow;font-weight:bold;\">&nbsp;Total - ".$currOther2."</td>";
            $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther2Current."</td>";
            $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther2RangeSatu."</td>";
            $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther2RangeDua."</td>";
            $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther2RangeTiga."</td>";
            $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther2RangeEmpat."</td>";
            $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther2RangeLima."</td>";
            $trNya .= "<td align=\"right\">".number_format($totalRowOther2, 2, ",", ".")."</td>";
        $trNya .= "</tr>";
    }

    echo $trNya;
	?>
	</table>
    </td>
    </tr>
   
</table>
</body>
</HTML>