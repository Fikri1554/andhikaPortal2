<!DOCTYPE HTML>
<?php 
require_once("../configVoucher.php");

$aksiGet = $_GET['aksi'];
$yearProcessGet = $_GET['yearProcess'];

?>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script language="javascript">
function onClickTrCari(trId, idMailInv, batchno, bgColor, idVoucher, menuPageBatchno, detailCari)
{
	var idTrSeb = document.getElementById('idTrSeb').value;
	var bgColorSeb = document.getElementById('bgColorSeb').value;
	
	if(idTrSeb != "")
	{
		document.getElementById(idTrSeb).onmouseover = function onmouseover(){	this.style.backgroundColor='#D9EDFF';	}
		document.getElementById(idTrSeb).onmouseout = function onmouseout(){	this.style.backgroundColor=bgColorSeb;	}
		document.getElementById(idTrSeb).style.fontWeight='';
		document.getElementById(idTrSeb).style.backgroundColor=bgColorSeb;
		document.getElementById(idTrSeb).style.cursor = 'pointer';	
		//document.getElementById(idTdNameDivSeb).style.fontWeight=''; // FONT TIDAK BOLD UNTUK TD YANG DIPILIH
	}
	
	document.getElementById('tr'+trId).onmouseout = '';
	document.getElementById('tr'+trId).onmouseover ='';
	//document.getElementById('tr'+trId).style.fontWeight='bold';
	document.getElementById('tr'+trId).style.backgroundColor='#B0DAFF';
	document.getElementById('tr'+trId).style.cursor = 'default';
	document.getElementById('tr'+trId).style.fontSize='11px';
	document.getElementById('idTrSeb').value = 'tr'+trId;
	
	document.getElementById('bgColorSeb').value = bgColor;
	$('#divDetailCari', parent.document).html( detailCari );
	//parent.document.getElementById('idMailInv').value = idMailInv;
	//parent.document.getElementById('batchno').value = batchno;
	//parent.document.getElementById('teksBatchno').innerHTML = batchno;
	//parent.enabledBtn('btnCariPrintDetail');
	$('#idVoucher', parent.document).val( idVoucher );
	$('#menuPageBatchno', parent.document).val( menuPageBatchno );
	parent.enabledBtn('btnGetResult');
	
}
</script>


<link href="../css/table.css" rel="stylesheet" type="text/css" />

<body>

<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="bgColorSeb" name="bgColorSeb">
<div style="height:298px;overflow-y: scroll;">
    <table id="judul" width="445" cellpadding="0" cellspacing="0" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;position:fixed;left:0px;top:0px;">
    <tr align="center">
        <td width="30" height="40" class="tabelBorderRightJust">NO</td>
        <td width="65" class="tabelBorderRightJust">BATCHNO</td>
        <td width="235" class="tabelBorderRightJust">PAID TO/FROM</td>
        <td width="" class="tabelBorderRightJust">COMPANY</td>
        
    </tr>
    </table>
    
    <table width="445" cellpadding="0" cellspacing="0" style="font:0.7em sans-serif;color:#333;margin-top:40px;">
    <?php
	if($aksiGet == "lakukanCari")
    {
		$cariBerdasarkanGet = $_GET['cariBerdasarkan'];
		$teksCariGet = $_GET['teksCari'];
		$startDateGet = $CPublic->convTglDB($_GET['startDate']);
		$endDateGet = $CPublic->convTglDB($_GET['endDate']);
		//echo $aksiGet." / ".$cariBerdasarkanGet." / ".$teksCariGet." / ".$startDateGet." / ".$endDateGet;
		$paramCari = "";
		if($cariBerdasarkanGet == "paidName")
        {
            $paramCari = "kepada LIKE '%".$teksCariGet."%' AND";
        }
        if($cariBerdasarkanGet == "company")
        {
            $paramCari = "companyname LIKE '%".$teksCariGet."%' AND";
        }
		if($cariBerdasarkanGet == "bank")
        {
			$nilai = "";
			$a = 0;
			$queryBank = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT RTRIM(Acctcode) AS Acctcode, RTRIM(Acctname) AS Acctname, source FROM AccountCode WHERE (LEN(RTRIM(Acctcode)) = 5) AND (LEFT(Acctcode, 2) = '10') AND (Contact IS NULL) AND (Acctcode <> '10000') AND Acctname LIKE '%".$teksCariGet."%' ORDER BY Acctname");
			while($rowBank = $koneksiOdbcAcc->odbcFetch($queryBank))
			{
				$a++;
				if($a == 1)
				{	$nilai.= $rowBank['Acctcode'];	}
				else
				{	$nilai.= ",".$rowBank['Acctcode'];	}
			}
			$paramCari = "bankcode IN ( ".$nilai." ) AND";
        }
		if($cariBerdasarkanGet == "voucherNo")
        {
            $paramCari = "voucher LIKE '%".$teksCariGet."%' AND";
        }
		if($cariBerdasarkanGet == "invNo")
        {
            $paramCari = "invno LIKE '%".$teksCariGet."%' AND";
        }
		if($cariBerdasarkanGet == "datePaid")
        {	
            $paramCari = "DATE(datepaid) >= DATE('".$startDateGet."') AND DATE(datepaid) <= DATE('".$endDateGet."') AND";
            if($startDateGet == "--")
            {
                $paramCari = "DATE(datepaid) <= DATE('".$endDateGet."') AND";
            }
            if($endDateGet == "--")
            {
                $paramCari = "DATE(datepaid) >= DATE('".$startDateGet."') AND";
            }
        }
		//echo "<br>".$paramCari;
		
		
		$tabel = "";
        $i=0;
        $query = $CKoneksiVoucher->mysqlQuery("SELECT *, DATE(SUBSTRING(addusrdt, 7, 8)) AS entrydate FROM tblvoucher WHERE ".$paramCari." deletests=0 AND YEAR(datepaid)='".$yearProcessGet."' ORDER BY companyname ASC;", $CKoneksiVoucher->bukaKoneksi());
        while($row = $CKoneksiVoucher->mysqlFetch($query))
        {
			$i++;
            $rowColor = $CPublic->rowColorCustom($i, "#FFFFFF", "#F0F1FF");
			
			$imgIcon = "<img src=\"../picture/hourglass-select-remain.png\" width=\"14\">";
			$titleRow = "WAITING FOR TRANSFER";
			if($row['trfacct'] == "Y")
			{
				$imgIcon = "<img src=\"../picture/tick.png\" width=\"14\">";
				$titleRow = "ALREADY TRANSFER";
			}
			
			$menuPageBatchno = cariPage($CKoneksiVoucher, $limitVoucher, $row['idvoucher'] );
			
			$detailCari = detailCari($CKoneksiVoucher, $CVoucher, $CPublic, $row['idvoucher'] );
			
			$onClick = "onClickTrCari('".$i."', '".$row['idvoucher']."', '".$row['batchno']."', '".$rowColor."', '".$row['idvoucher']."', '".$menuPageBatchno."', '".$detailCari."');";
            $clikTR = $onClick;
			
			$tabel.=""?>
            <tr valign="bottom" align="left" bgcolor="<?php echo $rowColor; ?>"  onMouseOver="this.style.backgroundColor='#D9EDFF';" onMouseOut="this.style.backgroundColor='<?php echo $rowColor; ?>';" id="tr<?php echo $i; ?>" onClick="<?php echo $clikTR; ?>"  style="cursor:pointer;padding-bottom:1px;" title="<?php echo $titleRow; ?>">
                    <td width="30" height="22" class="tabelBorderTopLeftNull" align="center"><?php echo $i; ?></td>
                    <td width="65" class="tabelBorderTopLeftNull" align="center"><?php echo $imgIcon."&nbsp;".$CPublic->zerofill($row['batchno'], 6); ?></td>
                     <td width="235" class="tabelBorderTopLeftNull" style="padding-left:2px;"><?php echo $row['kepada']; ?>&nbsp;</td>
                    <td width="" class="tabelBorderTopLeftNull" style="padding-left:2px;"><?php echo $row['companyname']; ?>&nbsp;</td>
                </tr>
            <?php echo "";
		}
		//echo $menuPageBatchno;
		echo $tabel;
	}
	?>
    </table>
</div>
</body>
</HTML>

<?php
function cariPage($CKoneksiVoucher, $limitVoucher, $idVoucherParam)
{
	$totalPage = totalPage($CKoneksiVoucher); // TOTAL KESELURUHAN DATA Batchno
	$limitPage = $limitVoucher; // LIMIT PERPAGE YANG DITENTUKAN
	$maxPage = ceil($totalPage/$limitPage); // MAX PAGE YANG DIDAPAT DARI PEMBAGIAN PEMBULATAN 
	
	$nilai = "";
	$nilaiKembalian = "";
	for($a=1;$a<=$maxPage;$a++)
	{
		if($a == 1)//jika halaman satu maka nomor mulai dari 1
		{	$offset = 0;	}
		if($a > 1)//jika halaman satu maka nomor mulai dari 1
		{	$offset = ($a-1) * $limitPage;	} // OFFSET ADALAH DIMULAINYA POTONGAN DATA UTK MASING2 HALAMAN BERDASARKAN LANJUTAN URUTAN DARI DATA HALAMAN SEBELUMNYA
		
		
		$allIdVoucher = "";
		$query = $CKoneksiVoucher->mysqlQuery("SELECT idvoucher, batchno, kepada, voctype, company, companyname, paytype, bankcode, voucher, reference, chequeno, invno, jobno, datepaid, currency, amount, trfacct FROM tblvoucher WHERE deletests=0 AND YEAR(datepaid)='".$yearProcessGet."' GROUP BY batchno ORDER BY 0+batchno DESC LIMIT ".$offset.",".$limitPage.";", $CKoneksiVoucher->bukaKoneksi());
		while($row = $CKoneksiVoucher->mysqlFetch($query))
		{
			$allIdVoucher.= $row['idvoucher'].", ";
			if($row['idvoucher'] == $idVoucherParam)
			{
				$nilaiKembalian = $a;
			}
		}
		
		//$nilai.= "HALAMAN ".$a." = (".$allIdVoucher."), ";
	}
	//$nilai.= "idvoucher=".$idVoucherParam." maka halaman=".$nilaiKembalian;
	$nilai = $nilaiKembalian;
	
	return $nilai;
}

function totalPage($CKoneksiVoucher)
{
	$query = $CKoneksiVoucher->mysqlQuery("SELECT idvoucher FROM tblvoucher WHERE deletests=0 AND YEAR(datepaid)='".$yearProcessGet."' GROUP BY batchno ORDER BY 0+batchno DESC;", $CKoneksiVoucher->bukaKoneksi());
	$jmlRow = $CKoneksiVoucher->mysqlNRows($query);
	
	return $jmlRow;
}


function detailCari($CKoneksiVoucher, $CVoucher, $CPublic, $idVoucher)
{
	$html = "";
	
	$query = $CKoneksiVoucher->mysqlQuery("SELECT *, DATE(SUBSTRING(addusrdt, 7, 8)) AS entrydate, SUBSTRING(addusrdt, 1, 5) AS userinit FROM tblvoucher WHERE idvoucher = '".$idVoucher."' AND deletests=0;", $CKoneksiVoucher->bukaKoneksi());
    $row = $CKoneksiVoucher->mysqlFetch($query);
	
	$batchno = $CPublic->zerofill($row['batchno'], 6); // BATCHNO
	$vocType = $CPublic->jikaParamSmDgNilai($row['voctype'], "R", "RECEIVE", "TRANSFER");
	$teksToForm = $CPublic->jikaParamSmDgNilai($row['voctype'], "R", "FROM", "TO");
	$paidName = $row['kepada'];
	$company = $row['companyname'];
	$payMethod = $row['paytype'];
	$bank =  $CVoucher->detilAccountCode($row['bankcode'], "Acctname");
	$voucherNo = $row['voucher'];
	$refNo = $row['reference'];
	$chequeNo = $row['chequeno'];
	$invNo = $row['invno'];
	$jobNo = $row['jobno'];
	$datePaid = $CPublic->convTglNonDB($row['datepaid']);	
	if($row['datepaid'] == "0000-00-00")
		$datePaid = "&nbsp;";
	$amount = $row['currency']."&nbsp;".$CPublic->jikaKosongStrip(number_format((float)$row['amount'], 2, '.', ','));
	
	$html.= "<table style=\'width:350px;\' class=\'tabelDetailCari\'>";
	$html.= "<tr><td>BATCHNO : <a>".$batchno."</a></td></tr>";
	$html.= "<tr><td>VOUCHER TYPE : <a>".$vocType."</a></td></tr>";
	$html.= "<tr><td>PAID ".$teksToForm." : <a>".$paidName."</a></td></tr>";
	$html.= "<tr><td>COMPANY : <a>".$company."</a></td></tr>";
	$html.= "<tr><td>PAYMENT METHOD : <a>".$payMethod."</a></td></tr>";
	$html.= "<tr><td>BANK : <a>".rtrim($bank)."</a></td></tr>";
	$html.= "<tr><td>VOUCHER NO : <a>".$voucherNo."</a></td></tr>";
	$html.= "<tr><td>REFERENCE NO : <a>".$refNo."</a></td></tr>";
	$html.= "<tr><td>CHEQUE NO : <a>".$chequeNo."</a></td></tr>";
	$html.= "<tr><td>INVOICE NO : <a>".$invNo."</a></td></tr>";
	$html.= "<tr><td>JOB NO : <a>".$jobNo."</a></td></tr>";
	$html.= "<tr><td>DATE PAID : <a>".$datePaid."</a></td></tr>";
	$html.= "<tr><td>AMOUNT : <a>".$amount."</a></td></tr>";
	$html.= "</table>";
	
	return $html;
}
?>