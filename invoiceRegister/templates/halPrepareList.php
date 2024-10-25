<!DOCTYPE HTML>
<?php 
require_once("../configInvReg.php");

$aksiGet = $_GET['aksi'];
$jenisPaymentPost = $_GET['jenisPayment'];
$prepareByPost = $_GET['prepareBy'];
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
$whereCMP .= " AND YEAR(tglexp) > 2017 ";
?>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../js/payment.js"></script>
<script language="javascript">
function onClickTrPrepare(trId, idMailInv, mailInvoice, bgColor)
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
		document.getElementById(idTrSeb).style.fontWeight=''; // FONT TIDAK BOLD UNTUK TD YANG DIPILIH
	}
	
	document.getElementById('tr'+trId).onmouseout = '';
	document.getElementById('tr'+trId).onmouseover ='';
	document.getElementById('tr'+trId).style.fontWeight='bold';
	document.getElementById('tr'+trId).style.backgroundColor='#B0DAFF';
	document.getElementById('tr'+trId).style.cursor = 'default';
	document.getElementById('tr'+trId).style.fontSize='11px';
	document.getElementById('idTrSeb').value = 'tr'+trId;
	//document.getElementById('tr'+trId).style.height = "";

	document.getElementById('bgColorSeb').value = bgColor;
	parent.document.getElementById('idMailInv').value = idMailInv;
	parent.teksMap("PAYMENT > PREPARE FOR PAYMENT > "+mailInvoice);
	<?php
	echo "\n\r";
	if($userJenisInvReg == "user")
	{ // JIKA AKSES BUKAN BERNILAI DISABLED (ATAU SAMA DENGAN "") MAKA ...
		if($CInvReg->aksesInvReg($userIdSession, "btnpayment_addgroupprep") == "")
		{
			echo "parent.enabledBtn('btnPayPrepAddGroup');";
		}
	}
	if($userJenisInvReg == "admin")
	{
		echo "parent.enabledBtn('btnPayPrepAddGroup');"; 
	}
	echo "\n\r";
	?>
}

window.onload = 
function() 
{
	var userJenis = "<?php echo $userJenis; ?>";
	if(userJenis != "admin")
	{
		document.oncontextmenu = function(){	return false;	}; 
	}
	parent.loadScroll('halPrepareList');
	parent.doneWait();
	parent.panggilEnableLeftClick();
	document.getElementById('loaderImg').style.visibility = "hidden";
}

$(window).scroll(function(){
$('#judul').css('left','-'+$(window).scrollLeft()+'px');
});
$(window).scroll(function(){
$('#judul1').css('left','-'+$(window).scrollLeft()+'px');
});
$(window).scroll(function(){
$('#judul2').css('left','-'+$(window).scrollLeft()+'px');
});
</script>

<link href="../css/invReg.css" rel="stylesheet" type="text/css" />
<link href="../css/table.css" rel="stylesheet" type="text/css" />
<link href="../css/button.css" rel="stylesheet" type="text/css" />

<body onUnload="parent.saveScroll('halPrepareList')">
<div class="loader" id="loaderImg" style="visibility:hidden;"></div>
<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="bgColorSeb" name="bgColorSeb">

<?php
if($prepareByPost == "invoice" || $prepareByPost == "barcode" || $aksiGet == "ketikElementCari")
{
	$paramCariGet = $_GET['paramCari'];
	if($prepareByPost == "invoice")
	{
		$judulTabel = "INVOICE NUMBER";
		$paramCari = "mailinvno LIKE '%".$paramCariGet."%' AND";
	}
	if($prepareByPost == "barcode")
	{
		$judulTabel = "BARCODE";
		$paramCari = "barcode LIKE '%".$paramCariGet."%' AND";
	}
		/*$paramCariGet = $_GET['paramCari']; // PARAMETER CARI YANG DIKETIK
			if($prepareByPost == "invoice") // JIKA PILIHAN PREPAY BY ADALAH INVOICE DI HALAMAN PREPARE FOR PAYMENT
				$paramCari = "mailinvno LIKE '%".$paramCariGet."%' AND";
			if($prepareByPost == "barcode") // JIKA PILIHAN PREPARE BY ADALAH BARCODE 
				$paramCari = "barcode LIKE '%".$paramCariGet."%' AND";*/
?>
    <table id="judul" cellpadding="0" cellspacing="0" width="205" border="0" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;position:fixed;left:0px;top:0px;">
    <tr align="center">
        <td width="40" height="30" class="tabelBorderRightJust">SNO</td>
        <td width="165" class=""><?php echo $judulTabel; ?></td>
    </tr>
    </table>
    
    <!--188 | 16-->
<?php
    $width = "205";
    $totalRowPrepare = totalRowPrepare($CKoneksiInvReg, $paramCari);
    if($totalRowPrepare > 16)
    {
        $width = "188";
    }
	//echo "<br><br>".$totalRowPrepare;
	$i = 0;
	$tabel = "";
?>
    <table cellpadding="0" cellspacing="0" width="<?php echo $width; ?>" style="font:0.7em sans-serif;color:#333;margin-top:30px;">
<?php
        //$sortByGet = $_GET['sortBy'];
		//echo $sortByGet;
        //$orderBy = orderBy($sortByGet);
		//$orderBy = "ORDER BY CONCAT(DATE(SUBSTRING(addusrdt, 7, 8)),' ',SUBSTRING(addusrdt, 16, 8)) DESC";
		$orderBy = "ORDER BY urutan DESC";
		
        if($aksiGet == "ketikElementCariPrep") // MENCARI NO INVOICE ATAU BARCODE DI HALAMAN PREPARE FOR PAYMENT
		{
			$query = $CKoneksiInvReg->mysqlQuery("SELECT idmailinv, urutan, tipesenven, sendervendor1, sendervendor2name, companyname, barcode, currency, amount, deduc, addi, mailinvno, kreditacc FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND apprpayment=1 AND preparepayment='N' AND paid='N' AND ".$paramCari." deletests=0 ".$whereCMP.$orderBy.";", $CKoneksiInvReg->bukaKoneksi());
		}
		else
		{
			$query = $CKoneksiInvReg->mysqlQuery("SELECT idmailinv, urutan, tipesenven, sendervendor1, sendervendor2name, companyname, barcode, currency, amount, deduc, addi, mailinvno, kreditacc FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND apprpayment=1 AND preparepayment='N' AND paid='N' AND deletests=0 ".$whereCMP.$orderBy.";", $CKoneksiInvReg->bukaKoneksi());
		}
        
        while($row = $CKoneksiInvReg->mysqlFetch($query))
        {
			$cekPrepPay = cekPrepPay($CKoneksiInvReg, $row['idmailinv']); // CEK INVOICE JIKA SUDAH ADA DI GROUP ITEM MAKA TIDAK DITAMPILKAN
			if($cekPrepPay == "kosong")
			{  
				$i++;
				
				$rowColor = $CPublic->rowColorCustom($i, "#FFFFFF", "#F0F1FF");
				
				$mailInvoice = $row['mailinvno'];   // $prepareByPost == "invoice"
				if($prepareByPost == "barcode")
				{
					$mailInvoice = $row['barcode'];  
				}
				
				$senderVendor = $row['sendervendor1'];
				if($row['tipesenven'] == "2")
					$senderVendor = $row['sendervendor2name'];
				
				$deduc = $row['deduc'];
				$addi = $row['addi'];
				$amount = (($row['amount'] - $deduc) + $addi);
				$totalAmount = $CPublic->jikaKosongStrip(number_format((float)$amount, 2, '.', ','));	
					
				//parent.klikRowPrepare(idMailInv, sNo, billComp, amount, credNumb, credName);
				$klikRowPrepare = "parent.klikRowPrepare('".$row['idmailinv']."', '".$row['urutan']."', '".rtrim($row['companyname'])."', '".rtrim($row['kreditacc'])."', '".rtrim($senderVendor)."', '".rtrim($row['currency'])."', '".$totalAmount."');";
				
				$onClick = "onClickTrPrepare('".$i."', '".$row['idmailinv']."', '".$mailInvoice."', '".$rowColor."');".$klikRowPrepare;
           		$clikTR = $onClick;
			
            	$tabel.="" 
?>
            	<tr valign="bottom" bgcolor="<?php echo $rowColor; ?>"  onMouseOver="this.style.backgroundColor='#D9EDFF';" onMouseOut="this.style.backgroundColor='<?php echo $rowColor; ?>';" id="tr<?php echo $i; ?>" onClick="<?php echo $clikTR; ?>" 
                    style="cursor:pointer;padding-bottom:1px;">
                	<td width="40" height="22" align="center" class="tabelBorderBottomJust"><?php echo $row['urutan']; ?>&nbsp;</td>
                	<td align="left" class="tabelBorderTopRightNull">&nbsp;<?php echo $mailInvoice; ?></td>
            	</tr>
<?php 			echo "";
			}
        }
		echo $tabel;
?>
    </table>
<?php
}
if($aksiGet == "header")
{
?>
	<table id="judul1" cellpadding="0" cellspacing="0" width="670" border="0" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;">
    <tr align="center">
        <td width="160" height="30" class="">BILLING COMPANY</td>
        <td width="40" class="">CURR</td>
        <td width="130" class="">TOTAL AMOUNT</td>
        <td width="70" class="" style="font-size:10px;">CREDITOR<br>NUMBER</td>
        <td width="270" class="">SENDER / VENDOR NAME</td>
    </tr>
    </table>
<?php
	$i=0;
	$totalAmount = 0;
	$amount = 0;
	$tabel = "";
	
?>   
    <div style="position:absolute;top:30px;overflow:hidden;">
    <table cellpadding="0" cellspacing="0" width="670" style="font:0.7em sans-serif;color:#333;">
    
<?php
	
	$query = $CKoneksiInvReg->mysqlQuery("SELECT idmailinv FROM tblpreppay WHERE userid='".$userIdLogin."' AND deletests=0 ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
	$jmlRow = $CKoneksiInvReg->mysqlNRows($query);
    while($row = $CKoneksiInvReg->mysqlFetch($query))
    {
		$i++;
		
		$deduc = $CInvReg->detilMailInv($row['idmailinv'], "deduc");
		$addi = $CInvReg->detilMailInv($row['idmailinv'], "addi");
		$amount = (($CInvReg->detilMailInv($row['idmailinv'], "amount") - $deduc) + $addi);
		$totalAmount += $amount;
				
		if($i == $jmlRow)
		{
			$compName = $CInvReg->detilMailInv($row['idmailinv'], "companyname"); // BILLING COMPANY
			$currency = $CInvReg->detilMailInv($row['idmailinv'], "currency");
			/*$amount = $CPublic->jikaKosongStrip(number_format((float)$CInvReg->detilMailInv($row['idmailinv'], "amount"), 2, '.', ','));*/
			$kreditAcc = $CInvReg->detilMailInv($row['idmailinv'], "kreditacc"); // CREDITOR NUMBER
			
			$senderVendor = $CInvReg->detilMailInv($row['idmailinv'], "sendervendor1");// SENDER / VENDOR NAME
			if($CInvReg->detilMailInv($row['idmailinv'], "tipesenven") == "2")
				$senderVendor = $CInvReg->detilMailInv($row['idmailinv'], "sendervendor2name");// SENDER / VENDOR NAME
				
			if($CInvReg->detilMailInv($row['idmailinv'], "sendervendor2") == "")
				$senderVendor = $CInvReg->detilMailInv($row['idmailinv'], "kreditaccname");// SENDER / VENDOR NAME
			
			$tabel.="" 
?>
            <tr align="center">
                <td width="160" height="22" class="tabelBorderRightJust"><?php echo $compName; ?></td>
                <td width="40" class="tabelBorderRightJust"><?php echo $currency; ?></td>
                <td width="130" class="tabelBorderRightJust"><?php echo number_format((float)$totalAmount, 2, '.', ','); ?></td>
                <td width="70" class="tabelBorderRightJust"><?php echo $kreditAcc; ?></td>
                <td width="270" class="tabelBorderAllNull"><?php echo $senderVendor; ?></td>
            </tr>
<?php
			echo "";
		}
		echo "<input type=\"hidden\" id=\"idMailInvHeader\" value=\"".$row['idmailinv']."\">";
	}
	echo $tabel;
?>
	
    </table>
    </div>
<?php	
}
if($aksiGet == "groupItem" || $aksiGet == "addGroupItem" || $aksiGet == "resetGroupItem" || $aksiGet == "assignTransNo")
{
	if($aksiGet == "addGroupItem")
	{
		$idMailInvGet = $_GET['idMailInv'];
		
		$CKoneksiInvReg->mysqlQuery("INSERT INTO tblpreppay 
						 (idmailinv, userid, urutan, addusrdt) 
						 VALUES 
						( '".$idMailInvGet."', '".$userIdLogin."', (SELECT CASE WHEN a.urutan IS NOT NULL THEN (MAX(a.urutan+1)) ELSE '1' END AS urutann FROM tblpreppay a WHERE a.deletests=0), '".$userWhoAct."')");
		$lastInsertId = mysql_insert_id();	
		$CHistory->updateLogInvReg($userIdLogin, "PREPARE FOR PAYMENT - Add Group (idmailinv=<b>".$idMailInvGet."</b>)");	
	}
	
	if($aksiGet == "resetGroupItem")
	{
		//$CKoneksiInvReg->mysqlQuery("TRUNCATE TABLE tblpreppay;");
		$CKoneksiInvReg->mysqlQuery("DELETE FROM tblpreppay WHERE userid='".$userIdLogin."';");
		$CHistory->updateLogInvReg($userIdLogin, "PREPARE FOR PAYMENT - Reset Group ( mengosongkan tblpreppay dengan userid='".$userIdLogin."' )");
	}
	
	if($aksiGet == "assignTransNo")
	{
		$transNo = ($CInvReg->lastTransNo()+1);
		
		$query = $CKoneksiInvReg->mysqlQuery("SELECT idmailinv FROM tblpreppay WHERE userid='".$userIdLogin."' AND deletests=0 ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
		while($row = $CKoneksiInvReg->mysqlFetch($query))
		{
			$CKoneksiInvReg->mysqlQuery("UPDATE mailinvoice SET preparepayment='Y', datepreppay=now(), preppayby='".$userIdLogin."', transno='".$transNo."', updusrdt='".$userWhoAct."' WHERE idmailinv='".$row['idmailinv']."' AND deletests=0;", $CKoneksiInvReg->bukaKoneksi());
			$CHistory->updateLogInvReg($userIdLogin, "PREPARE FOR PAYMENT - Assign to Trans. No (idmailinv=<b>".$row['idmailinv']."</b>,trans. no=<b>".$transNo."</b>)");
		}
		
		//$CKoneksiInvReg->mysqlQuery("TRUNCATE TABLE tblpreppay;", $CKoneksiInvReg->bukaKoneksi());
		$CKoneksiInvReg->mysqlQuery("DELETE FROM tblpreppay WHERE userid='".$userIdLogin."';");
		$CKoneksiInvReg->mysqlQuery("UPDATE tbllasttransno SET lasttransno='".$transNo."';");
		$CHistory->updateLogInvReg($userIdLogin, "PREPARE FOR PAYMENT - update tbllasttransno (lasttransno=<b>".$transNo."</b>)");	
	}
?>
	<table id="judul2" cellpadding="0" cellspacing="0" width="1255" border="0" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;position:fixed;left:0px;top:0px;">
    <tr align="center">
        <td width="40" height="30" class="">SNO</td>
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
    
    <table cellpadding="0" cellspacing="0" width="1255" style="font:0.7em sans-serif;color:#333;margin-top:30px;">
<?php	
	$i = 0;
    $tabel = "";
	$senVenGrupPertama = "";
	$kreditAccGrupPertama = "";
	$billCompGrupPertama = "";
	$currencyGrupPertama = "";
	
	$query = $CKoneksiInvReg->mysqlQuery("SELECT idmailinv FROM tblpreppay WHERE userid='".$userIdLogin."' AND deletests=0 ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
	while($row = $CKoneksiInvReg->mysqlFetch($query))
	{
		$i++;
		$rowColor = $CPublic->rowColorCustom($i, "#FFFFFF", "#F0F1FF");
		
		$urutan = $CInvReg->detilMailInv($row['idmailinv'], "urutan"); //SNO
		$vesName = $CInvReg->detilMailInv($row['idmailinv'], "vesname");
		$kreditAcc = $CInvReg->detilMailInv($row['idmailinv'], "kreditacc"); // CREDITOR NUMBERR
		
		$senderVendor = $CInvReg->detilMailInv($row['idmailinv'], "sendervendor1");// SENDER / VENDOR NAME
		if($CInvReg->detilMailInv($row['idmailinv'], "tipesenven") == "2")
			$senderVendor = $CInvReg->detilMailInv($row['idmailinv'], "sendervendor2name");// SENDER / VENDOR NAME
			
		$tglInv = $CPublic->convTglNonDB( $CInvReg->detilMailInv($row['idmailinv'], "tglinvoice") );
		$poNumber = $CInvReg->detilMailInv($row['idmailinv'], "barcode"); // BARCODE
		$invNumber = $CInvReg->detilMailInv($row['idmailinv'], "mailinvno");
		$currency = $CInvReg->detilMailInv($row['idmailinv'], "currency");
		
		$deduc = $CInvReg->detilMailInv($row['idmailinv'], "deduc");
		$addi = $CInvReg->detilMailInv($row['idmailinv'], "addi");
		$amountt = (($CInvReg->detilMailInv($row['idmailinv'], "amount") - $deduc) + $addi);
		
		$amount = $CPublic->jikaKosongStrip(number_format((float)$amountt, 2, '.', ','));
		$tglexp = $CPublic->convTglNonDB($CInvReg->detilMailInv($row['idmailinv'], "tglexp")); // DUE DATE
		$compName = $CInvReg->detilMailInv($row['idmailinv'], "companyname"); // BILLING COMPANY
		$desc = $CInvReg->detilMailInv($row['idmailinv'], "description"); // REMARKS
		
		$tabel.="" 
?>
		<tr align="center" valign="bottom" bgcolor="<?php echo $rowColor; ?>"  onMouseOver="this.style.backgroundColor='#D9EDFF';" onMouseOut="this.style.backgroundColor='<?php echo $rowColor; ?>';" id="tr<?php echo $i; ?>" onClick="<?php echo $clikTR; ?>" 
				style="cursor:pointer;padding-bottom:1px;">
			<td width="40" height="22" class="tabelBorderTopLeftNull"><?php echo $urutan; ?>&nbsp;</td>
			<td width="70" class="tabelBorderTopLeftNull"><?php echo $tglInv; ?></td>
			<td width="85" class="tabelBorderTopLeftNull"><?php echo $poNumber; ?></td>
			<td width="150" class="tabelBorderTopLeftNull">&nbsp;<?php echo $invNumber; ?></td>
			<td width="130" class="tabelBorderTopLeftNull" align="right"><?php echo "(".$currency.")"."&nbsp;".$amount; ?>&nbsp;</td>
			<td width="70" class="tabelBorderTopLeftNull"><?php echo $tglexp; ?></td>
			<td width="160" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $compName; ?></td>
            <td width="215" class="tabelBorderTopLeftNull" align="left">&nbsp;<?php echo $vesName; ?></td>
			<td width="335" class="tabelBorderBottomJust" align="left">&nbsp;<?php echo $desc; ?></td>
		</tr>
<?php		
		echo "";

		if($i == 1)
		{
			$senVenGrupPertama = $senderVendor;
			$kreditAccGrupPertama = $kreditAcc;
			$billCompGrupPertama = $compName;
			$currencyGrupPertama = $currency;
		}
		
	}
	$jmlRowGrup = $i;
	echo $tabel;
	
	/*$query2 = $CKoneksiInvReg->mysqlQuery("SELECT idmailinv FROM tblpreppay WHERE deletests=0 ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
	while($row2 = $CKoneksiInvReg->mysqlFetch($query2))
	{
		$idMailPrepPay.=$row2['idmailinv'].",";
	}*/
?>
	<!--<input type="text" id="idMailPrepPay" value="<?php echo $idMailPrepPay; ?>">-->
    </table>
<?php	
}
?>

</body>
</HTML>

<script type="text/javascript">

<?php
if($aksiGet == "groupItem" || $aksiGet == "addGroupItem" || $aksiGet == "resetGroupItem")
{
?>
	parent.windowDisplayPrepare();
	
	$("#jmlRowGrup", parent.document).val( "<?php echo $jmlRowGrup; ?>" );
	$("#senVenGrupPertama", parent.document).val( "<?php echo rtrim($senVenGrupPertama); ?>" );
	$("#kreditAccGrupPertama", parent.document).val( "<?php echo rtrim($kreditAccGrupPertama); ?>" );
	$("#billCompGrupPertama", parent.document).val( "<?php echo rtrim($billCompGrupPertama); ?>" );
	$("#currencyGrupPertama", parent.document).val( "<?php echo rtrim($currencyGrupPertama); ?>" );
		
	
	parent.loadIframe('iframeList2', '');
	parent.loadIframe('iframeList2', 'templates/halPrepareList.php?aksi=header');
<?php
	if( $aksiGet == "addGroupItem" )
	{ 
?>
		$("#statusData", parent.document).html( "<span style=\"color:#096;\">READY FOR ASSIGN</span>" );
<?php	
	}
	if( $aksiGet == "resetGroupItem" ) 
	{ 
?>
		$("#statusData", parent.document).html( "<span style=\"color:#900;\">NOT READY FOR ASSIGN</span>" );
<?php
	}
}
if($aksiGet == "assignTransNo")
{
	?>
	setTimeout(function()
	{ 
		$('#hrefThickbox',parent.document).attr('href','templates/halPopup.php?aksi=suksesAssignTransno&placeValuesBeforeTB_=savedValues&TB_iframe=true&height=200&width=320&modal=true');
		window.parent.$('#hrefThickbox').click();
	}, 500);
<?php
}
?>
</script>

<?php
function totalRowPrepare($CKoneksiInvReg, $paramCari)
{	
	$query = $CKoneksiInvReg->mysqlQuery("SELECT urutan, mailinvno FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND apprpayment=1 AND preparepayment='N' AND paid='N' AND ".$paramCari." deletests=0");
	$jmlRow = $CKoneksiInvReg->mysqlNRows($query);
	
	return $jmlRow;
}

function cekPrepPay($CKoneksiInvReg, $idMailInv)
{
	$nilai = "kosong";
	
	$query = $CKoneksiInvReg->mysqlQuery("SELECT idmailinv FROM tblpreppay WHERE idmailinv='".$idMailInv."' AND deletests=0;", $CKoneksiInvReg->bukaKoneksi());
	$jmlRow = $CKoneksiInvReg->mysqlNRows($query);
	if($jmlRow > 0)
		$nilai = "ada";
	
	return $nilai;
}
?>