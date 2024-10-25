<!DOCTYPE HTML>
<?php 
require_once("../configInvReg.php");

$aksiGet = $_GET['aksi'];
?>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../js/cari.js"></script>
<script language="javascript">
function onClickTrCari(trId, idMailInv, batchno, bgColor, detailCari) {
    var idTrSeb = document.getElementById('idTrSeb').value;
    var bgColorSeb = document.getElementById('bgColorSeb').value;

    if (idTrSeb != "") {
        document.getElementById(idTrSeb).onmouseover = function onmouseover() {
            this.style.backgroundColor = '#D9EDFF';
        }
        document.getElementById(idTrSeb).onmouseout = function onmouseout() {
            this.style.backgroundColor = bgColorSeb;
        }
        document.getElementById(idTrSeb).style.fontWeight = '';
        document.getElementById(idTrSeb).style.backgroundColor = bgColorSeb;
        document.getElementById(idTrSeb).style.cursor = 'pointer';
        //document.getElementById(idTdNameDivSeb).style.fontWeight=''; // FONT TIDAK BOLD UNTUK TD YANG DIPILIH
    }

    document.getElementById('tr' + trId).onmouseout = '';
    document.getElementById('tr' + trId).onmouseover = '';
    //document.getElementById('tr'+trId).style.fontWeight='bold';
    document.getElementById('tr' + trId).style.backgroundColor = '#B0DAFF';
    document.getElementById('tr' + trId).style.cursor = 'default';
    document.getElementById('tr' + trId).style.fontSize = '11px';
    document.getElementById('idTrSeb').value = 'tr' + trId;

    document.getElementById('bgColorSeb').value = bgColor;
    $('#divDetailCari', parent.document).html(detailCari);
    parent.document.getElementById('idMailInv').value = idMailInv;
    //parent.document.getElementById('batchno').value = batchno;
    //parent.document.getElementById('teksBatchno').innerHTML = batchno;
    parent.enabledBtn('btnCariPrintDetail');
    //parent.enabledBtn('btnBatchnoGroup');

}

window.onload =
    function() {
        var userJenis = "<?php echo $userJenis; ?>";
        if (userJenis != "admin") {
            document.oncontextmenu = function() {
                return false;
            };
        }
        parent.doneWait();
        parent.panggilEnableLeftClick();
    }

$(window).scroll(function() {
    $('#judul').css('left', '-' + $(window).scrollLeft());
});
</script>

<link href="../css/invReg.css" rel="stylesheet" type="text/css" />
<link href="../css/table.css" rel="stylesheet" type="text/css" />
<link href="../css/button.css" rel="stylesheet" type="text/css" />

<body>

    <input type="hidden" id="idTrSeb" name="idTrSeb">
    <input type="hidden" id="bgColorSeb" name="bgColorSeb">
    <div style="height:316px;overflow-y: scroll;">
        <table id="judul" width="395" cellpadding="0" cellspacing="0"
            style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;position:fixed;left:0px;top:0px;">
            <tr align="center">
                <td width="40" height="40" class="">SNO</td>
                <td width="290" class="">SENDER / VENDOR NAME</td>
                <td width="65" class="">BARCODE</td>
            </tr>
        </table>

        <table width="395" cellpadding="0" cellspacing="0" style="font:0.7em sans-serif;color:#333;margin-top:40px;">
            <?php
    if($aksiGet == "lakukanCari")
    {
        $cariBerdasarkanGet = $_GET['cariBerdasarkan'];
        $teksCariGet = $_GET['teksCari'];
        $startDateGet = $CPublic->convTglDB($_GET['startDate']);
        $endDateGet = $CPublic->convTglDB($_GET['endDate']);
        
        $paramCari = "";
        if($cariBerdasarkanGet == "company")
        {
            $paramCari = "companyname LIKE '%".$teksCariGet."%' AND";
        }
        if($cariBerdasarkanGet == "batchno")
        {
			$startDateGetBatch = str_replace("-","",$startDateGet); // date yang dihilangkan garis strip nya ("-") agar bisa sama dengan batchno
			$endDateGetBatch = str_replace("-","",$endDateGet); 
            $paramCari = "batchno >= ".$startDateGetBatch." AND batchno <= ".$endDateGetBatch." AND";
            if($startDateGetBatch == "")
            {
                $paramCari = "batchno <= ".$endDateGetBatch." AND";
            }
            if($endDateGetBatch == "")
            {
                $paramCari = "batchno >= ".$startDateGet." AND";
            }
        }
        if($cariBerdasarkanGet == "mailInvDate")
        {	
            $paramCari = "DATE(tglinvoice) >= DATE('".$startDateGet."') AND DATE(tglinvoice) < DATE('".$endDateGet."') AND";
            if($startDateGet == "--")
            {
                $paramCari = "DATE(tglinvoice) <= DATE('".$endDateGet."') AND";
            }
            if($endDateGet == "--")
            {
                $paramCari = "DATE(tglinvoice) >= DATE('".$startDateGet."') AND";
            }
        }
        
        if($cariBerdasarkanGet == "senVen")
        {
            $paramCari = "sendervendor1 LIKE '%".$teksCariGet."%' OR sendervendor2name LIKE '%".$teksCariGet."%' AND";
        }
        
        if($cariBerdasarkanGet == "mailInvNo")
        {
            $paramCari = "mailinvno LIKE '%".$teksCariGet."%' AND";
        }
        if($cariBerdasarkanGet == "poNumber")
        {
            $paramCari = "barcode LIKE '%".$teksCariGet."%' AND";
        }
        
        $tabel = "";
        $i=0;
        $query = $CKoneksiInvReg->mysqlQuery("SELECT *, DATE(SUBSTRING(addusrdt, 7, 8)) AS entrydate FROM mailinvoice WHERE ".$paramCari." deletests=0 ORDER BY DATE(SUBSTRING(addusrdt, 7, 8)) DESC;", $CKoneksiInvReg->bukaKoneksi());
        while($row = $CKoneksiInvReg->mysqlFetch($query))
        {
            $i++;
            $rowColor = $CPublic->rowColorCustom($i, "#FFFFFF", "#F0F1FF");
            
            $senderVendor = $row['sendervendor1'];
            if($row['tipesenven'] == "2")
                $senderVendor = $CInvReg->detilSenderVendor($row['sendervendor2'], "Acctname");
    
            $detailCari = detailCari($CKoneksiInvReg, $CPublic, $CLogin, $CInvReg, $row['idmailinv'] );
                
            $onClick = "onClickTrCari('".$i."', '".$row['idmailinv']."', '".$row['batchno']."', '".$rowColor."', '".$detailCari."');";
            $clikTR = $onClick;
            
            $imgMata = "";
            if($row['ack'] == "1") //jika invoice return sudah disimpan maka baris tidak bisa diklik
            {
                $imgMata = "<img src=\"../picture/eye.png\" width=\"14\">";
            }
            
            $imgGembok = "";
            if($row['saveinvret'] == "Y") //jika invoice return sudah disimpan maka baris tidak bisa diklik
            {
                $imgMata = "";
                $imgGembok = "<img src=\"../../picture/Lock-Lock-icon.png\" width=\"14\">";
            }
            
            $imgJam = "";
            if($row['preparepayment'] == "Y") //jika invoice return sudah disimpan maka baris tidak bisa diklik
            {
                $imgMata = "";
                $imgGembok = "";
                $imgJam = "<img src=\"../picture/hourglass-select-remain.png\" width=\"14\">";
            }
            
            $imgDompet = "";
            if($row['paid'] == "Y") //jika invoice return sudah disimpan maka baris tidak bisa diklik
            {
                $imgMata = "";
                $imgGembok = "";
                $imgJam = "";
                $imgDompet = "<img src=\"../picture/money.png\" width=\"14\">";
            }
			
			$imgIcon = $CInvReg->imgIcon($row['idmailinv']); // ICON UNTUK MASING2 ROW UNTUK STATUS TERAKHIR
                
            $tabel.=""?>
            <tr valign="bottom" align="left" bgcolor="<?php echo $rowColor; ?>"
                onMouseOver="this.style.backgroundColor='#D9EDFF';"
                onMouseOut="this.style.backgroundColor='<?php echo $rowColor; ?>';" id="tr<?php echo $i; ?>"
                onClick="<?php echo $clikTR; ?>" style="cursor:pointer;padding-bottom:1px;">
                <td width="20" height="22" class="tabelBorderBottomJust">&nbsp;<?php echo $imgIcon; ?></td>
                <td width="20" class="tabelBorderTopLeftNull" align="center"><?php echo $row['urutan']; ?>&nbsp;</td>
                <td width="290" class="tabelBorderTopLeftNull">&nbsp;<?php echo $senderVendor; ?></td>
                <td width="65" class="tabelBorderTopLeftNull" align="center"><?php echo $row['barcode']; ?></td>
            </tr>
            <?php echo "";
        }
        echo $tabel;
    }
    if($aksiGet == "") // HALAMAN AWAL ADALAH KOSONG
    { 
    ?>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <?php
    }
    ?>
        </table>
    </div>

</body>

</HTML>

<?php
function detailCari($CKoneksiInvReg, $CPublic, $CLogin, $CInvReg, $idMailInv)
{
	$html = "";
	
	$query = $CKoneksiInvReg->mysqlQuery("SELECT *, DATE(SUBSTRING(addusrdt, 7, 8)) AS entrydate, SUBSTRING(addusrdt, 1, 5) AS userinit FROM mailinvoice WHERE idmailinv = '".$idMailInv."' AND deletests=0;", $CKoneksiInvReg->bukaKoneksi());
    $row = $CKoneksiInvReg->mysqlFetch($query);
	
	if(substr($row['barcode'], 0, 1) == "A") // DOCUMENT TYPE
	{	$docType = "INVOICE";	}
	if(substr($row['barcode'], 0, 1) == "S")
	{	$docType = "MAIL";	}
	$batchno = $row['batchno']; // BATCHNO
	$receiveOn = $CPublic->convTglNonDB( $row['receivedate'] ); //  RECEIVE ON
	$senderVendor2 = $row['sendervendor2']; // KREDIT ACCOUNT
	$compName = $row['companyname']; // ADDRESSE BILLING COMPANY
    $unitName = $row['unitname']; // ADDRESSE BILLING UNIT
	$barcode = $row['barcode']; // PO NUMBER / BARCODE 
	$invDate = $CPublic->convTglNonDB( $row['tglinvoice'] ); // INVOICE DATE 
	$dueDate = $CPublic->jikaParamSamaDenganNilai1( $CPublic->convTglNonDB( $row['tglexp'] ), "00/00/0000", "-"); //DUE / EXPIRED DATE
	$payTerms = $CPublic->jikaParamSamaDenganNilai1( $row['dueday'], "", "-"); //PAY TERMS
	$invNumber = $row['mailinvno'];
	$currency = "(".$row['currency'].")";
    if($row['currency'] == "XXX" || $row['currency'] == "")
    {	$currency = "";	}
	$amount = $currency."&nbsp;".$CPublic->jikaKosongStrip(number_format((float)$row['amount'], 2, '.', ','));
	$deduc = $CPublic->jikaKosongStrip(number_format((float)$row['deduc'], 2, '.', ','));
	$addi = $CPublic->jikaKosongStrip(number_format((float)$row['addi'], 2, '.', ','));
	$finalAmount = $CPublic->jikaKosongStrip(number_format((float)(($row['amount'] - $row['deduc']) + $row['addi']), 2, '.', ','));
	$reasonDeduc = $CPublic->konversiQuotes( $row['reasondeduc'] );
	$remark = $CPublic->konversiQuotes( $row['remark'] );	
	$inputDataBy = $CLogin->detilLoginByUserInit2($row['userinit'], "userfullnm");
	
	$ack = $CPublic->jikaParamSmDgNilai($row['ack'], "1", "YES", "NO")."&nbsp;";
	$tglAck = $CPublic->convTglNonDB($row['dateack']) ;	
	if($row['dateack'] == "0000-00-00")
		$tglAck = "&nbsp;";
	$ackBy = "( ".$tglAck." - BY ".$CLogin->detilLogin2($row['ackby'], "userfullnm")." )";
	if($row['ackby'] == "")
		$ackBy = "";
	
	$return = $CPublic->jikaParamSmDgNilai($row['saveinvret'], "Y", "YES", "NO");	
	$tglRet = $CPublic->convTglNonDB($row['dateret']);	
	if($row['dateret'] == "0000-00-00")
			$tglRet = "&nbsp;";
	$retBy = "( ".$tglRet." - BY ".$CLogin->detilLogin2($row['retby'], "userfullnm")." )";
	if($row['retby'] == "")
		$retBy = "";
		
	$ignoreJe = $CPublic->jikaParamSmDgNilai($row['ignoreje'], "1", "YES", "NO");
	$approvePay = $CPublic->jikaParamSmDgNilai($row['apprpayment'], "1", "YES", "NO");
	if($row['vesname'] != "")
		$vessName = "( ".$row['vescode']." ) ".$row['vesname'];
	$source = $row['source'];
	$debitAcc = $row['debitacc'];
	$subCode = $row['subcode'];
	$desc = $CPublic->konversiQuotes( $row['description'] );

	$outstanding = $CPublic->jikaParamSmDgNilai($row['preparepayment'], "Y", "YES", "NO");
	$prepBy = "( BY ".$CLogin->detilLogin2($row['preppayby'], "userfullnm")." )";
	
	if($row['preppayby'] == "")
		$prepBy = "";
	$transNo = $row['transno'];
	$dateGenTransNo = $CPublic->convTglNonDB($row['datepreppay']);
	if($row['datepreppay'] == "0000-00-00")
			$dateGenTransNo = "&nbsp;";
	
	$paid = $CPublic->jikaParamSmDgNilai($row['paid'], "Y", "YES", "NO");		
	$paidBy = "( BY ".$CLogin->detilLogin2($row['paidby'], "userfullnm")." )";
	if($row['paidby'] == "")
		$paidBy = "";
	$payMethod = $row['paytype'];
	if($row['bankcode'] != "")
		$bank = "( ".$row['bankcode']." )&nbsp;".$CInvReg->detilBankCode($row['bankcode'], "Acctname");
	$voucher = $row['voucher'];
	$cheque = $row['chequeno'];			
	$tglPaid = $CPublic->convTglNonDB($row['datepaid']);	
	if($row['datepaid'] == "0000-00-00")
			$tglPaid = "&nbsp;";
	$amountToPaid = $CPublic->jikaKosongStrip(number_format((float)$row['amtconv'], 2, '.', ','));
	$adjAcc = $row['adjacc'];
	$adjAmt = $CPublic->jikaKosongStrip(number_format((float)$row['adjamt'], 2, '.', ','));
			
	$html.= "<table style=\'width:537px;\' class=\'tabelDetailCari\'>";
	$html.= "<tr><td>DOCUMENT TYPE : <a>".$docType."</a></td></tr>";
	$html.= "<tr><td>BATCHNO : <a>".$batchno."</a></td></tr>";
	$html.= "<tr><td>RECEIVED ON : <a>".$receiveOn."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>SENDER / VENDOR ACCOUNT CODE : <a>".$senderVendor2."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>ADDRESSEE / BILLING <a></a></td></tr>";
	$html.= "<tr><td style=\'padding-left:30px;\'>COMPANY : <a>".$compName."</a> </td></tr>";
	$html.= "<tr><td style=\'padding-left:30px;\'>UNIT : <a>".$unitName."</a> </td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>PO NUMBER / BARCODE : <a>".$barcode."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>INVOICE DATE : <a>".$invDate."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>DUE / EXPIRED DATE : <a>".$dueDate."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>PAY TERMS / DUE DAYS : <a>".$payTerms."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>INVOICE NUMBER: <a>".$invNumber."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>AMOUNT : <a>".$amount."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>REMARKS : <a>".nl2br2( $remark)."</a></td></tr>";
	$html.= "<tr><td>INPUT DATA BY : <a>".$inputDataBy."</a></td></tr>";
	$html.= "<tr><td>ACKNOWLEDGE : <a>".$ack." ".$ackBy."</a></td></tr>";
	$html.= "<tr><td>RETURN : <a>".$return." ".$retBy."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>IGNORE JE : <a>".$ignoreJe."</a> </td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>APPROVE PAYMENT : <a>".$approvePay."</a> </td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>VESSEL NAME : <a>".$vessName."</a> </td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>SOURCE : <a>".$source."</a> </td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>DEBIT ACCOUNT	 : <a>".$debitAcc."</a> </td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>SUBCODE : <a>".$subCode."</a> </td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>CREDIT ACCOUNT : <a>".$row['kreditacc']."</a> </td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>VOUCHER NO : <a>".$row['voucherje']."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>REFERENCE NO : <a>".$row['referenceje']."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>FINAL AMOUNT : <a>".$finalAmount."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:30px;\'>ADDITIONAL : <a>".$addi."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:30px;\'>DEDUCTION : <a>".$deduc."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:30px;\'>REASON : <a>".$reasonDeduc."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>DESCRIPTION : <a>".$desc."</a></td></tr>";
	$html.= "<tr><td>OUTSTANDING / PREPARE PAYMENT : <a>".$outstanding." ".$prepBy."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>TRANS NO : <a>".$transNo."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>DATE GENERATED : <a>".$dateGenTransNo."</a></td></tr>";
	$html.= "<tr><td>PAID : <a>".$paid." ".$paidBy."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>PAYMENT METHOD : <a>".$payMethod."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>BANK : <a>".$bank."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>VOUCHER NO : <a>".$voucher."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>CHEQUE NO : <a>".$cheque."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>DATE PAID : <a>".$tglPaid."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>AMOUNT TO BE PAID : <a>".$amountToPaid."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>ADJUSMENT ACCOUNT : <a>".$adjAcc."</a></td></tr>";
	$html.= "<tr><td style=\'padding-left:15px;\'>ADJUSMENT AMOUNT : <a>".$adjAmt."</a></td></tr>";
	$html.= "</table>";

	return $html;
}

function nl2br2($string) 
{
	$string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
	return $string;
} 
?>