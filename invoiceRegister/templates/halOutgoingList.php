<!DOCTYPE HTML>
<?php 
require_once("../configInvReg.php");

$aksiGet = $_GET['aksi'];
$thnBlnGet = $_GET['thnBln'];
$tglGet = $_GET['tgl'];

$idOutgoingInvGet = $_GET['idOutgoingInv'];
$batchnoGet = $_GET['batchno'];

$whereCMP = "";
if ($userCompany != "") //jika ada triger companyNya
{
	if($userCompany != "ALL")
	{
		$dC = explode(",", $userCompany);
		if (count($dC) > 1)
		{
			$cmpNya = '';
			for ($lan=0; $lan < count($dC); $lan++) 
			{ 
				if ($cmpNya == "")
				{
					$cmpNya = "'".$dC[$lan]."'";
				}else{
					$cmpNya .= ",'".$dC[$lan]."'";
				}
			}
			$whereCMP = " AND company IN(".$cmpNya.")";
		}else{
			$whereCMP = " AND company = '".$dC[0]."' ";
		}
	}
}

if($aksiGet == "delete")
{
	$CKoneksiInvReg->mysqlQuery("UPDATE outgoinginvoice SET deletests=1, delusrdt='".$userWhoAct."' WHERE idoutgoinginv=".$idOutgoingInvGet." AND deletests=0 LIMIT 1;");
	$CHistory->updateLogInvReg($userIdLogin, "Simpan DELETE Outgoing Invoice (idoutgoinginv=<b>".$idOutgoingInvGet."</b>, batchno=<b>".$batchnoGet."</b>, delusrdt=<b>".$userWhoAct."</b>)");	
}

?>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script language="javascript">
function onClickTr(trId, idOutgoingInv, customer, ack, bgColor)
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
		//document.getElementById(idTrSeb).style.height = "22";
		//document.getElementById(idTdNameDivSeb).style.fontWeight=''; // FONT TIDAK BOLD UNTUK TD YANG DIPILIH
	}
	
	document.getElementById('tr'+trId).onmouseout = '';
	document.getElementById('tr'+trId).onmouseover ='';
	//document.getElementById('tr'+trId).style.fontWeight='bold';
	document.getElementById('tr'+trId).style.backgroundColor='#B0DAFF';
	document.getElementById('tr'+trId).style.cursor = 'default';
	document.getElementById('tr'+trId).style.fontSize='11px';
	document.getElementById('idTrSeb').value = 'tr'+trId;
	//document.getElementById('tr'+trId).style.height = "";
	
	document.getElementById('bgColorSeb').value = bgColor;
	
	parent.document.getElementById('idOutgoingInv').value = idOutgoingInv;
	parent.teksMap("INCOMING > "+customer);
	
	parent.enabledBtn('btnOutgoingEdit'); //BUTTON EDIT AKTIF KETIKA ROW DIKLIK, UNTUK AKSES BISA EDIT APA TIDAK, ADA DI TOMBOL SAVE PADA HALAMAN EDIT, JIKA TIDAK PUNYA AKSES EDIT INCOMING MAKA TIDAK BISA SIMPAN / SAVE JADI HANYA BISA LIHAT DETIL EDITNYA SAJA
	<?php
	echo "\n\r";
	
	if($userJenisInvReg == "user")
	{ // JIKA AKSES BUKAN BERNILAI DISABLED (ATAU SAMA DENGAN "") MAKA ...
		if($CInvReg->aksesInvReg($userIdSession, "btnoutgoing_edit") == "")
		{
			//echo "parent.enabledBtn('btnIncomingEdit');";
		}
		if($CInvReg->aksesInvReg($userIdSession, "btnoutgoing_delete") == "")
		{
			echo "parent.enabledBtn('btnOutgoingDelete');";
		}
		echo "
		if(ack == \"1\")
		{
			parent.disabledBtn('btnOutgoingDelete');
		}";
	}
	
	if($userJenisInvReg == "admin")
	{
		echo "parent.enabledBtn('btnOutgoingEdit');";
		echo "parent.enabledBtn('btnOutgoingDelete');";
	}
	
	echo "\n\r";
	?>
	
	
}

function onClickTrCari(trId, idOutgoingInv, batchno, bgColor)
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
	
	parent.document.getElementById('idOutgoingInv').value = idOutgoingInv;
	parent.document.getElementById('batchno').value = batchno;
	parent.document.getElementById('teksBatchno').innerHTML = batchno;
	parent.enabledBtn('btnView');
	parent.enabledBtn('btnBatchnoGroup');
}

window.onload = 
function() 
{
	//document.oncontextmenu = function(){	return false;	}; 
	document.getElementById('loaderImg').style.visibility = "hidden";
	parent.doneWait();
	parent.panggilEnableLeftClick();
	
	parent.disabledBtn('btnIncomingEdit');
	parent.disabledBtn('btnIncomingDelete');
}
$(window).scroll(function(){
$('#judul').css('left','-'+$(window).scrollLeft());
});
</script>

<link href="../css/invReg.css" rel="stylesheet" type="text/css" />
<link href="../css/table.css" rel="stylesheet" type="text/css" />

<body>

<div class="loader" id="loaderImg" style="visibility:hidden;"></div>
<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="bgColorSeb" name="bgColorSeb">

<table id="judul" width="1295" cellpadding="0" cellspacing="0" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;position:fixed;left:0px;top:0px;">
<tr align="center">
    <td width="40" rowspan="2" height="50" class="">SNO</td>
    <td width="270" rowspan="2" class="">CUSTOMER</td>
    <td width="160" rowspan="2" class="">COMPANY</td>
    <td width="70" rowspan="2" class="">INV. DATE</td>
    <td width="70" rowspan="2" class="">DUE DATE</td>
    <td width="150" rowspan="2" class="">INV. NUMBER</td>
    <td width="130" rowspan="2" class="">AMOUNT</td>
    <td width="335" rowspan="2" class="">REMARK</td>
    <td width="70" rowspan="2" class="">SENT DATE</td>
</tr>
</table>

<table width="1295" cellpadding="0" cellspacing="0" style="font:0.7em sans-serif;color:#333;margin-top:50px;">
<?php
if($aksiGet == "display")
{
	$sortByGet = $_GET['sortBy'];
	$ascByGet = strtoupper($_GET['ascBy']);
	
	$orderBy = "";
	if($sortByGet == "entryDate")
		//$orderBy = "ORDER BY CONCAT(DATE(SUBSTRING(addusrdt, 7, 8)),' ',SUBSTRING(addusrdt, 16, 8)) ".$ascByGet;
		$orderBy = "ORDER BY urutan ".$ascByGet;
	if($sortByGet == "company")
		$orderBy = "ORDER BY companyname ".$ascByGet;
	if($sortByGet == "custName")
		$orderBy = "ORDER BY CONCAT(customer1,customer2name) ".$ascByGet;
	if($sortByGet == "mailDate")
		$orderBy = "ORDER BY UNIX_TIMESTAMP(tglinvoice) ".$ascByGet;
	
	$tabel = "";
    $i=0;
	$query = $CKoneksiInvReg->mysqlQuery("SELECT * FROM outgoinginvoice WHERE SUBSTR(batchno, 1, 6)=".$thnBlnGet." AND SUBSTR(batchno, 7, 2)=".$tglGet." AND deletests=0 ".$whereCMP.$orderBy.";", $CKoneksiInvReg->bukaKoneksi());
    while($row = $CKoneksiInvReg->mysqlFetch($query))
    {
        $i++;
        $rowColor = $CPublic->rowColorCustom($i, "#FFFFFF", "#F0F1FF");
        
        $customer = $row['customer1'];
        if($row['tipecustomer'] == "2")
			$customer = $row['customer2name'];
            //$customer = $CInvReg->detilSenderVendor($row['customer2'], "Acctname");
		
		$compName = $row['companyname'];
        $unitName = $row['unitname'];
        if(strlen($row['unitname']) > 30)
           $unitName =  substr($row['unitname'],0,30)."...";

        $tglinvoice = $CPublic->convTglNonDB($row['tglinvoice']);
		$tglexp = $CPublic->jikaParamSamaDenganNilai1($CPublic->convTglNonDB($row['tglexp']), "00/00/0000", "&nbsp;");
        
        $currency = "(".$row['currency'].")";
        if($row['currency'] == "XXX" || $row['currency'] == "")
            $currency = "";
        
        $remark = $row['remark'];
        if(strlen($row['remark']) > 50)
            $remark = substr($row['remark'],0,50)."...";
		
		$tglReceive = $CPublic->convTglNonDB($row['sentdate']);
		
		$onClick = "onClickTr('".$i."', '".$row['idoutgoinginv']."', '".$customer."', '".$row['ack']."', '".$rowColor."');";
		$clikTR = $onClick;
/*        
		$imgMata = "";
        if($row['ack'] == "1") //jika invoice return sudah disimpan maka baris tidak bisa diklik
        {
            $imgMata = "<img src=\"../picture/eye.png\" width=\"14\" title=\"ALREADY ACKNOWLEDGE\">";
			//$clikTR = "";
        }
		
		$imgGembok = "";
        if($row['saveinvret'] == "Y") //jika invoice return sudah disimpan maka baris tidak bisa diklik
        {
			$imgMata = "";
            $imgGembok = "<img src=\"../../picture/Lock-Lock-icon.png\" width=\"14\" title=\"ALREADY RETURNED\">";
            //$clikTR = "";
        }*/
		
		$imgIcon = $CInvReg->imgIconOutgoing($row['idoutgoinginv']); // ICON UNTUK MASING2 ROW UNTUK STATUS TERAKHIR
        
        $tabel.=""?>

            <tr valign="bottom" align="left" bgcolor="<?php echo $rowColor; ?>"  onMouseOver="this.style.backgroundColor='#D9EDFF';" onMouseOut="this.style.backgroundColor='<?php echo $rowColor; ?>';" id="tr<?php echo $i; ?>" onClick="<?php echo $clikTR; ?>" 
            style="cursor:pointer;padding-bottom:1px;">
                <td width="20" height="22" class="tabelBorderBottomJust">&nbsp;<?php echo /*$imgMata.$imgGembok; */$imgIcon; ?></td>
                <td width="20" class="tabelBorderTopLeftNull" align="center"><?php echo $row['urutan']; ?>&nbsp;</td>
                <td width="270" class="tabelBorderTopLeftNull">&nbsp;<?php echo $customer; ?></td>
                <td width="160" class="tabelBorderTopLeftNull">&nbsp;<?php echo $compName; ?></td>
                <td width="70" class="tabelBorderTopLeftNull" align="center"><?php echo $tglinvoice; ?></td>
                <td width="70" class="tabelBorderTopLeftNull" align="center"><?php echo $tglexp; ?></td>
                <td width="150" class="tabelBorderTopLeftNull">&nbsp;<?php echo $row['outgoinginvno']; ?></td>
                <td width="130" class="tabelBorderTopLeftNull" align="right">&nbsp;<?php echo $currency; ?>&nbsp;<?php echo $CPublic->jikaKosongStrip(number_format((float)$row['amount'], 2, '.', ',')); ?>&nbsp;</td>
                <td width="335" class="tabelBorderTopLeftNull">&nbsp;<?php echo $remark; ?></td>
                <td width="70" class="tabelBorderTopLeftNull" align="center"><?php echo $tglReceive; ?></td>
            </tr>
        <?php echo "";
    }
    echo $tabel;
}
if($aksiGet == "cariMailInv")
{
	$cariBerdasarkanGet = $_GET['cariBerdasarkan'];
	$teksCariGet = $_GET['teksCari'];
	$startDateGet = $_GET['startDate'];
	$endDateGet = $_GET['endDate'];
	$sortByGet = $_GET['sortBy'];
	
	$paramCari = "";
	if($cariBerdasarkanGet == "customer")
	{
		$paramCari = "customer1 LIKE '%".$teksCariGet."%' OR customer2name LIKE '%".$teksCariGet."%' AND";
	}
	if($cariBerdasarkanGet == "company")
	{
		$paramCari = "companyname LIKE '%".$teksCariGet."%' AND";
	}
	if($cariBerdasarkanGet == "invDate")
	{	
		$paramCari = "DATE(tglinvoice) >= DATE('".$CPublic->convTglDB($startDateGet)."') AND DATE(tglinvoice) < DATE('".$CPublic->convTglDB($endDateGet)."') AND";
		if($startDateGet == "")
		{
			$paramCari = "DATE(tglinvoice) <= DATE('".$CPublic->convTglDB($endDateGet)."') AND";
		}
		if($endDateGet == "")
		{
			$paramCari = "DATE(tglinvoice) >= DATE('".$CPublic->convTglDB($startDateGet)."') AND";
		}
	}
	if($cariBerdasarkanGet == "entryDate")
	{
		$paramCari = "DATE(SUBSTRING(addusrdt, 7, 8)) >= DATE('".$CPublic->convTglDB($startDateGet)."') AND DATE(SUBSTRING(addusrdt, 7, 8)) <= DATE('".$CPublic->convTglDB($endDateGet)."') AND";
		if($startDateGet == "")
		{
			$paramCari = "DATE(SUBSTRING(addusrdt, 7, 8)) <= DATE('".$CPublic->convTglDB($endDateGet)."') AND";
		}
		if($endDateGet == "")
		{
			$paramCari = "DATE(SUBSTRING(addusrdt, 7, 8)) >= DATE('".$CPublic->convTglDB($startDateGet)."') AND";
		}
	}
	
	$orderBy = "";
	if($sortByGet == "custAsc")
		$orderBy = "ORDER BY customer1 ASC, customer2name ASC";
	if($sortByGet == "custDesc")
		$orderBy = "ORDER BY customer1 DESC, customer2name DESC";
	if($sortByGet == "companyAsc")
		$orderBy = "ORDER BY companyname ASC";
	if($sortByGet == "companyDesc")
		$orderBy = "ORDER BY companyname DESC";
	if($sortByGet == "mailDateAsc")
		$orderBy = "ORDER BY UNIX_TIMESTAMP(tglinvoice) ASC";
	if($sortByGet == "mailDateDesc")
		$orderBy = "ORDER BY UNIX_TIMESTAMP(tglinvoice) DESC";
	if($sortByGet == "entryAsc")
		$orderBy = "ORDER BY CONCAT(DATE(SUBSTRING(addusrdt, 7, 8)),' ',SUBSTRING(addusrdt, 16, 8)) ASC";
	if($sortByGet == "entryDesc")
		$orderBy = "ORDER BY CONCAT(DATE(SUBSTRING(addusrdt, 7, 8)),' ',SUBSTRING(addusrdt, 16, 8)) DESC";
	
	$tabel = "";
    $i=0;
	$query = $CKoneksiInvReg->mysqlQuery("SELECT *, DATE(SUBSTRING(addusrdt, 7, 8)) AS entrydate FROM mailinvoice WHERE ".$paramCari." deletests=0 ".$whereCMP.$orderBy.";", $CKoneksiInvReg->bukaKoneksi());
    while($row = $CKoneksiInvReg->mysqlFetch($query))
    {
        $i++;
        $rowColor = $CPublic->rowColorCustom($i, "#FFFFFF", "#F0F1FF");
		
		$customer = $row['customer1'];
        if($row['tipecustomer'] == "2")
			$customer = $row['customer2name'];
            //$customer = $CInvReg->detilCustomer($row['customer2'], "Acctname");
			
		$compName = $row['companyname'];
        $unitName = $row['unitname'];
        if(strlen($row['unitname']) > 30)
           $unitName =  substr($row['unitname'],0,30)."...";

        $tglinvoice = $CPublic->convTglNonDB($row['tglinvoice']);
		
        $tglexp = $CPublic->convTglNonDB($row['tglexp']);
		if($row['tglexp'] == "0000-00-00")
			$tglexp = "&nbsp;";
        
        $currency = "(".$row['currency'].")";
        if($row['currency'] == "XXX" || $row['currency'] == "")
            $currency = "";
        
        $remark = $row['remark'];
        if(strlen($row['remark']) > 50)
            $remark = substr($row['remark'],0,50)."...";
			
		$onClick = "onClickTrCari('".$i."', '".$row['idoutgoinginv']."', '".$row['batchno']."', '".$rowColor."');";
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
			
		$tabel.=""?>
        <tr valign="bottom" align="left" bgcolor="<?php echo $rowColor; ?>"  onMouseOver="this.style.backgroundColor='#D9EDFF';" onMouseOut="this.style.backgroundColor='<?php echo $rowColor; ?>';" id="tr<?php echo $i; ?>" onClick="<?php echo $clikTR; ?>" 
            style="cursor:pointer;padding-bottom:1px;">
                <td width="20" height="22" class="tabelBorderBottomJust">&nbsp;<?php echo $imgMata.$imgGembok; ?></td>
                <td width="20" class="tabelBorderTopLeftNull" align="center"><?php echo $row['urutan']; ?>&nbsp;</td>
                <td width="270" class="tabelBorderTopLeftNull">&nbsp;<?php echo $customer; ?></td>
                <td width="160" class="tabelBorderTopLeftNull">&nbsp;<?php echo $compName; ?></td>
                <td width="70" class="tabelBorderTopLeftNull" align="center"><?php echo $tglinvoice; ?></td>
                <td width="70" class="tabelBorderTopLeftNull" align="center"><?php echo $tglexp; ?></td>
                <td width="150" class="tabelBorderTopLeftNull">&nbsp;<?php echo $row['outgoinginvno']; ?></td>
                <td width="130" class="tabelBorderTopLeftNull" align="right">&nbsp;<?php echo $currency; ?>&nbsp;<?php echo $CPublic->jikaKosongStrip(number_format((float)$row['amount'], 2, '.', ',')); ?>&nbsp;</td>
                <td width="335" class="tabelBorderTopLeftNull">&nbsp;<?php echo $remark; ?></td>
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
</body>

<script type="text/javascript">
<?php
if($aksiGet == "delete")
{
	echo "parent.klikBtnDisplay();";
}
?>
</script>
</HTML>