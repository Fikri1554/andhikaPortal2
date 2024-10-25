<?php
require_once("../configInvReg.php");
?>

<script>
window.dhx_globalImgPath = "../../dhtmlxCombo/dhtmlxCombo/codebase/imgs/";
</script>

<link rel="STYLESHEET" type="text/css" href="../../dhtmlxCombo/dhtmlxCombo/codebase/dhtmlxcombo.css">
<script  src="../../dhtmlxCombo/dhtmlxCombo/codebase/dhtmlxcommon.js"></script>
<script  src="../../dhtmlxCombo/dhtmlxCombo/codebase/dhtmlxcombo.js"></script>
 
<link href="../../css/invoiceRegister.css" rel="stylesheet" type="text/css">
<!--<link type="text/css" rel="stylesheet" href="calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK>
<SCRIPT type="text/javascript" src="calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>

<script language="JavaScript" src="../masks.js"></script>  
<script language="JavaScript" src="../invoiceRegister.js"></script>  -->
<script type="text/javascript">
function exit()
{
	var btnYgDipilih = parent.document.getElementById('btnYgDipilih').value;
	
	parent.tb_remove(false);
	if(btnYgDipilih == "go")
	{
		parent.klikBtnGoChooseBatchno('exitTambahMailInv');
	}
	if(btnYgDipilih == "cari")
	{
		parent.klikBtnGoCari(parent.formCari);
	}
}

function tes()
{
/*	var z = new dhtmlXCombo("combo_zone3", "alfa3", 320);
	alert(z.getComboText());*/
	//var alfa3 = document.getElementById('alfa3').value;
	alert(combo.getSelectedValue());

}


/*
function ajaxGetMailInv(id,id2,id3,aksi,divId)
{
	var mypostrequest=new ajaxRequest()
	mypostrequest.onreadystatechange=function()
	{
		if (mypostrequest.readyState==4)
		{
			if (mypostrequest.status==200 || window.location.href.indexOf("http")==-1)
			{
				document.getElementById(divId).innerHTML=mypostrequest.responseText
			}
			else
			{
				alert("An error has occured making the request")
			}
		}
	}
	
	var batchno = document.getElementById("batchnoGet").value;
	
	var sendervendor1 = document.getElementById("sendervendor1").value;
	var sendervendor2 = document.getElementById("sendervendor2").value;
	var company = document.getElementById("company").value;
	var unitt = document.getElementById("unitt").value;
	var barcode = document.getElementById("barcode").value;
	var invoiceDate = document.getElementById("invoiceDate").value;
	var dueDate = document.getElementById("dueDate").value;
	var amount = document.getElementById("amount").value.replace(/,/gi,"");
	var currency = document.getElementById("currency").value;
	var noInvoice = document.getElementById("noInvoice").value;
	var remark = document.getElementById("remark").value;
	
	var repSendervendor1 = sendervendor1.replace(/ /g,"");
	var repBarcode = barcode.replace(/ /g,"");
	var repInvoiceDate = invoiceDate.replace(/ /g,"");
	var repDueDate = dueDate.replace(/ /g,"");
	var repNoInvoice = noInvoice.replace(/ /g,"");
	var repAmount = amount.replace(/ /g,"");

	if(aksi == "simpanTambahMailInv")
	{
		var cekMailInvSama = document.getElementById('cekMailInvSama').value;
		
		if(repSendervendor1 != "" || sendervendor2 != "00000")
		{
			var senderVendor = sendervendor1;
			var inputTypeSenderVendor = "1"; // yang dipilih senderVendor1 dan senderVendor2 kosong
			if(sendervendor2 != "00000")
			{
				var senderVendor = sendervendor2; // yang dipilih senderVendor2 dan senderVendor1 kosong
				var inputTypeSenderVendor = "2";
			}
		}

		if(repSendervendor1 == "" && sendervendor2 == "00000")
		{
			alert('Sender/Vendor is still empty');
			document.getElementById("sendervendor1").focus();
			return false;
		}
		
		if(company == "XXX")
		{
			alert('You have not selected Company');
			document.getElementById("company").focus(); 
			return false;
		}
		
		if(unitt == "XXX")
		{
			alert('You have not selected Unit');
			document.getElementById("unitt").focus(); 
			return false;
		}
		
		if(repBarcode == "")
		{
			alert('Barcode is still empty');
			document.getElementById("barcode").focus();
			return false;
		}
		
		if(barcode.substring(0,1) != "A" && barcode.substring(0,1) != "S")
		{
			alert('Barcode value format is wrong');
			document.getElementById("barcode").focus();
			return false;
		}
		
		if(barcode.length != 8)
		{
			alert('Barcode text length must be 8');
			document.getElementById("barcode").focus();
			return false;
		}
		
		var barcodeAdaAtautidak = document.getElementById('barcodeAdaAtautidak').value;
		if(barcodeAdaAtautidak == "ada")
		{
			alert('Barcode is already exists');
			document.getElementById("barcode").focus();
			return false;
		}
		
		if(repInvoiceDate == "")
		{
			alert('Date is still empty');
			document.getElementById("invoiceDate").focus();
			return false;
		}
		
		if(repNoInvoice == "")
		{
			alert('No is still empty');
			document.getElementById("noInvoice").focus();
			return false;
		}
		
		if(barcode.substring(0,1) == "A")
		{
			if(repDueDate == "")
			{
				alert('Due date is still empty');
				document.getElementById("dueDate").focus();
				return false;
			}
			
			if(repAmount == "")
			{
				alert('Amount is still empty');
				document.getElementById("amount").focus();
				return false;
			}
			if(currency == "XXX")
			{
				alert('You have not selected Currency');
				document.getElementById("currency").focus(); 
				return false;
			}
			
			if(cekMailInvSama == "ada")
			{
				alert('Mail / Invoice already exists');
				return false;
			}
			else
			{
				var parameters="halaman="+aksi+"&senderVendor="+senderVendor+"&inputTypeSenderVendor="+inputTypeSenderVendor+"&company="+company+"&unitt="+unitt+"&barcode="+barcode+"&invoiceDate="+invoiceDate+"&dueDate="+dueDate+"&noInvoice="+noInvoice+"&amount="+amount+"&currency="+currency+"&remark="+remark+"&batchno="+batchno;
				halTarget = "halTambahMailInv.php";
			}
		}
		else if(barcode.substring(0,1) == "S")
		{
			if(cekMailInvSama == "ada")
			{
				alert('Mail / Invoice already exists');
				return false;
			}
			else
			{
				var parameters="halaman="+aksi+"&senderVendor="+senderVendor+"&inputTypeSenderVendor="+inputTypeSenderVendor+"&company="+company+"&unitt="+unitt+"&barcode="+barcode+"&invoiceDate="+invoiceDate+"&noInvoice="+noInvoice+"&amount=&currency=&remark="+remark+"&batchno="+batchno;
			halTarget = "halTambahMailInv.php";
			}
		}
	}
	
	if(aksi == "simpanEditMailInv")
	{
		var cekMailInvSama = document.getElementById('cekMailInvSama').value;
		if(repSendervendor1 != "" || sendervendor2 != "00000")
		{
			var senderVendor = sendervendor1;
			var inputTypeSenderVendor = "1"; // yang dipilih senderVendor1 dan senderVendor2 kosong
			if(sendervendor2 != "00000")
			{
				var senderVendor = sendervendor2; // yang dipilih senderVendor2 dan senderVendor1 kosong
				var inputTypeSenderVendor = "2";
			}
		}
		
		if(repSendervendor1 == "" && sendervendor2 == "00000")
		{
			alert('Sender/Vendor is still empty');
			document.getElementById("sendervendor1").focus();
			return false;
		}
		
		if(company == "XXX")
		{
			alert('You have not selected Company');
			document.getElementById("company").focus(); 
			return false;
		}
		
		if(unitt == "XXX")
		{
			alert('You have not selected Unit');
			document.getElementById("unitt").focus(); 
			return false;
		}
		
		if(repBarcode == "")
		{
			alert('Barcode is still empty');
			document.getElementById("barcode").focus();
			return false;
		}
		
		if(repInvoiceDate == "")
		{
			alert('Date is still empty');
			document.getElementById("invoiceDate").focus();
			return false;
		}
		
		if(repNoInvoice == "")
		{
			alert('No is still empty');
			document.getElementById("noInvoice").focus();
			return false;
		}
		
		if(barcode.substring(0,1) != "A" && barcode.substring(0,1) != "S")
		{
			alert('Barcode value format is wrong');
			document.getElementById("barcode").focus();
			return false;
		}
		
		if(barcode.length != 8)
		{
			alert('Barcode text length must be 8');
			document.getElementById("barcode").focus();
			return false;
		}
		
		if(barcode.substring(0,1) == "A")
		{
			if(repDueDate == "")
			{
				alert('Due date is still empty');
				document.getElementById("dueDate").focus();
				return false;
			}
			
			if(repAmount == "")
			{
				alert('Amount is still empty');
				document.getElementById("amount").focus();
				return false;
			}
			if(currency == "XXX")
			{
				alert('You have not selected Currency');
				document.getElementById("currency").focus(); 
				return false;
			}
			
			if(cekMailInvSama == "ada")
			{
				alert('Mail / Invoice already exists');
				return false;
			}
			else
			{
				var parameters="halaman="+aksi+"&senderVendor="+senderVendor+"&inputTypeSenderVendor="+inputTypeSenderVendor+"&company="+company+"&unitt="+unitt+"&barcode="+barcode+"&invoiceDate="+invoiceDate+"&dueDate="+dueDate+"&noInvoice="+noInvoice+"&amount="+amount+"&currency="+currency+"&remark="+remark+"&batchno="+batchno+"&idMailInv="+id;
				halTarget = "halTambahMailInv.php";
			}
		}
		else if(barcode.substring(0,1) == "S")
		{
			if(cekMailInvSama == "ada")
			{
				alert('Mail / Invoice already exists');
				return false;
			}
			else
			{
				var parameters="halaman="+aksi+"&senderVendor="+senderVendor+"&inputTypeSenderVendor="+inputTypeSenderVendor+"&company="+company+"&unitt="+unitt+"&barcode="+barcode+"&invoiceDate="+invoiceDate+"&noInvoice="+noInvoice+"&amount=&currency=&remark="+remark+"&batchno="+batchno+"&idMailInv="+id;
				halTarget = "halTambahMailInv.php";
			}
		}
	}
	
	if(aksi == "resetEditMailInv")
	{
		var parameters="halaman="+aksi+"&idMailInv="+id;
		
		mypostrequest.open("POST", "halTambahMailInv.php", true);
		mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		mypostrequest.send(parameters);
		
		return false;
	}
	
	if(aksi == "resetTambahMailInv")
	{
		var parameters="halaman="+aksi+"&batchno="+batchno;
		
		mypostrequest.open("POST", "halTambahMailInv.php", true);
		mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		mypostrequest.send(parameters);
		
		return false;
	}
	
	if(aksi == "tulisMailInvSama")
	{
		if(repSendervendor1 != "" || sendervendor2 != "00000")
		{
			var senderVendor = sendervendor1;
			var inputTypeSenderVendor = "1"; // yang dipilih senderVendor1 dan senderVendor2 kosong
			if(sendervendor2 != "00000")
			{
				var senderVendor = sendervendor2; // yang dipilih senderVendor2 dan senderVendor1 kosong
				var inputTypeSenderVendor = "2";
			}
		}
		
		document.getElementById('tdStatusSaved').innerHTML = "&nbsp;";
		var parameters="halaman="+aksi+"&senderVendor1="+sendervendor1+"&senderVendor2="+sendervendor2+"&inputTypeSenderVendor="+inputTypeSenderVendor+"&company="+company+"&unitt="+unitt+"&barcode="+barcode+"&invoiceDate="+invoiceDate+"&noInvoice="+noInvoice+"&amount="+amount+"&currency="+currency+"&batchno="+batchno;
		halTarget = "../halPost.php";
	}
	
	
	if(aksi == "tulisMailInvSamaEdit")
	{
		if(repSendervendor1 != "" || sendervendor2 != "00000")
		{
			var senderVendor = sendervendor1;
			var inputTypeSenderVendor = "1"; // yang dipilih senderVendor1 dan senderVendor2 kosong
			if(sendervendor2 != "00000")
			{
				var senderVendor = sendervendor2; // yang dipilih senderVendor2 dan senderVendor1 kosong
				var inputTypeSenderVendor = "2";
			}
		}
		
		document.getElementById('tdStatusSaved').innerHTML = "&nbsp;";
		var parameters="halaman="+aksi+"&senderVendor1="+sendervendor1+"&senderVendor2="+sendervendor2+"&inputTypeSenderVendor="+inputTypeSenderVendor+"&company="+company+"&unitt="+unitt+"&barcode="+barcode+"&invoiceDate="+invoiceDate+"&noInvoice="+noInvoice+"&amount="+amount+"&currency="+currency+"&batchno="+batchno+"&idMailInv="+id;
		//"+&barcode="+barcode+"&invoiceDate="+invoiceDate
		halTarget = "../halPost.php";
	}
	
	if(aksi == "tulisBarcode")
	{
		var parameters="halaman="+aksi+"&barcode="+repBarcode
		halTarget = "../halPost.php";
	}
	
	mypostrequest.open("POST", halTarget, true);
	mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	mypostrequest.send(parameters);
	
}



function isiBarcode(nilai)
{
	document.getElementById("amount").disabled = false;
	document.getElementById("currency").disabled = false;
	document.getElementById("imgCalDueDate").disabled = false;
	document.getElementById("amount").style.backgroundColor='';
	document.getElementById("currency").style.backgroundColor='';
	document.getElementById("dueDate").style.backgroundColor='';
	if(nilai.substring(0,1) == "S")
	{
		document.getElementById("amount").disabled = true;
		document.getElementById("currency").disabled = true;
		document.getElementById("imgCalDueDate").disabled = true;
		document.getElementById("amount").style.backgroundColor='#CCC';
		document.getElementById("currency").style.backgroundColor='#CCC';
		document.getElementById("dueDate").style.backgroundColor='#CCC';
	}
}

function pilihSenderVendorMenu(nilai)
{
	document.getElementById("sendervendor1").disabled = false;
	document.getElementById("sendervendor1").style.backgroundColor='';
	if(nilai != "00000")
	{
		document.getElementById("sendervendor1").disabled = true;
		document.getElementById("sendervendor1").style.backgroundColor='#CCC';
	}
}

function init()
{	
	hpMask = new Mask("#,###.00", "number");
	hpMask.attach(document.getElementById('amount'));
}

window.onload = function()
{
	var btnYgDipilih = parent.document.getElementById('btnYgDipilih').value;
	if(btnYgDipilih == "go")
	{
		document.getElementById('batchnoGet').value = parent.document.getElementById('batchnoHidden').value;
	}
	if(btnYgDipilih == "cari")
	{
		document.getElementById('batchnoGet').value = parent.document.getElementById('batchnoMailInvTerpilih').value;
	}
	
	
	var barcode = document.getElementById("barcode").value;
	document.getElementById("amount").disabled = false;
	document.getElementById("currency").disabled = false;
	document.getElementById("imgCalDueDate").disabled = false;
	document.getElementById("amount").style.backgroundColor='';
	document.getElementById("currency").style.backgroundColor='';
	if(barcode.substring(0,1) == "S")
	{
		document.getElementById("amount").disabled = true;
		document.getElementById("currency").disabled = true;
		document.getElementById("imgCalDueDate").disabled = true;
		document.getElementById("amount").style.backgroundColor='#CCC';
		document.getElementById("currency").style.backgroundColor='#CCC';
	}
	document.getElementById("idHalSequence").innerHTML = parent.document.getElementById("urutanMailInvTerpilih").value;
}*/
</script>

<?php
/*$batchno = $_POST['batchno'];

$urutanMax = $cmailinv->maxUrutan($batchno);
$senderVendor = $_POST['senderVendor'];
$inputTypeSenderVendor = $_POST['inputTypeSenderVendor'];
$company = $_POST['company'];
$compName = $ccomp->detilComp($company , "compname");
$unitt = $_POST['unitt'];
$unitName = $cunit->detilUnit($unitt, "deptname");
$barcode = $_POST['barcode'];
$invoiceDate = $cpublic->convTglDB($_POST['invoiceDate']);
$dueDate = $cpublic->convTglDB($_POST['dueDate']);
$noInvoice = $_POST['noInvoice'];
$amount = str_replace(",","",$_POST['amount']);
$currency = $_POST['currency'];
$remark = $_POST['remark'];

$idMailInv = $_POST['idMailInv'];

if($halaman == "simpanTambahMailInv")
{
	if($inputTypeSenderVendor == "1")
	{
		$senderVendorFieldDB = " sendervendor1, ";
		$senderVendorLog = $senderVendor;
		$kreditacc = "";
		$sendervendor2name = "";
	}
	elseif($inputTypeSenderVendor == "2")
	{
		$senderVendorFieldDB = " sendervendor2, ";
		$senderVendorLog = $ccoa->detilSenderVendor($senderVendor,"AcctIndo");
		$kreditacc = $senderVendor;
		$sendervendor2name = $senderVendorLog;
	}
	
	$urutan = $urutanMax + 1;

	$koneksi->mysqlQuery("INSERT INTO mailinvoice 
						 (urutan, batchno, ".$senderVendorFieldDB." sendervendor2name, company, companyname, unit, unitname, barcode, tglinvoice, tglexp, currency, amount, mailinvno, remark, kreditacc, addusrdt) 
						 VALUES 
						('".$urutan."', 
						'".$batchno."', 
						'".$senderVendor."', 
						'".$sendervendor2name."', 
						'".$company."', 
						'".$compName."', 
						'".$unitt."', 
						'".$unitName."', 
						'".$barcode."', 
						'".$invoiceDate."', 
						'".$dueDate."',  
						'".$currency."', 
						'".$amount."', 
						'".$noInvoice."', 
						'".$remark."',  
						'".$kreditacc."', 
						'".$userWhoAct."')");
	$statusSaved = "&nbsp;&nbsp;<span style=\"color:#CC3333;\">Mail / Invoice successfully saved</span>&nbsp;&nbsp;<img src=\"../picture/icon_check2.png\">";
	
	
	$clog->updateLog("../data/log/", $userId, "Simpan Mail / Invoice baru dengan rincian (batchno = <b>".$batchno."</b>, senderVendor = ".$senderVendorLog.", company = <b>".$compName."</b>, unit = <b>".$unitName."</b>, barcode = <b>".$barcode."</b>, tglinovice = <b>".$_POST['invoiceDate']."</b>, tglexp= <b>".$_POST['dueDate']."</b>, noinvoice = <b>".$noInvoice."</b>, jumlah = <b>".number_format((float)$amount, 2, '.', '.')."</b>, currency =<b>".$currency."'</b>, remark = <b>".$remark."</b>)");
}

if($halaman == "simpanEditMailInv")
{
	if($inputTypeSenderVendor == "1")// jika yang diisi sendervendor1 maka
	{
		$senderVendorLog = $senderVendor; 
		$sendervendor1 = $senderVendor;
		$sendervendor2 = "";
	}
	elseif($inputTypeSenderVendor == "2")// jika yang diisi sendervendor2 maka
	{
		$senderVendorLog = $ccoa->detilSenderVendor($senderVendor,"AcctIndo");
		$sendervendor1 = "";
		$sendervendor2 = $senderVendor;
	}
	
	$statusSaved = "&nbsp;&nbsp;<span style=\"color:#CC3333;\">Invoice successfully updated</span>&nbsp;&nbsp;<img src=\"../picture/icon_check2.png\"><br>";
	
	$senderVendorLogAwal = $cmailinv->detilMailInvById($idMailInv, "sendervendor1"); //sendervendor awal ambil nilai DB dari field sendervendor1
	if($cmailinv->detilMailInvById($idMailInv, "sendervendor2") != "") //jika sendervndor2 tidak kosong maka svendor awal ambil nilai dari field svendor2
	{
		$senderVendorLogAwal = $ccoa->detilSenderVendor($cmailinv->detilMailInvById($idMailInv, "sendervendor2"), "AcctIndo");
	}
	$compNameAwal = $ccomp->detilComp($cmailinv->detilMailInvById($idMailInv,"company"), "compname");
	$unitNameAwal = $cunit->detilUnit($cmailinv->detilMailInvById($idMailInv,"unit"), "deptname");
	$barcodeAwal = $cmailinv->detilMailInvById($idMailInv, "barcode");
	$invoiceDateAwal = $cpublic->convTglNonDB($cmailinv->detilMailInvById($idMailInv, "tglinvoice"));
	$dueDateAwal = $cpublic->convTglNonDB($cmailinv->detilMailInvById($idMailInv, "tglexp"));
	$noInvoiceAwal = $cmailinv->detilMailInvById($idMailInv, "mailinvno");
	$amountAwal = number_format((float)$cmailinv->detilMailInvById($idMailInv, "amount"), 2, '.', ',');
	$currencyAwal = $cmailinv->detilMailInvById($idMailInv, "currency");
	$remarkAwal = $cmailinv->detilMailInvById($idMailInv, "remark");

	$clog->updateLog("../data/log/", $userId, "Edit Mail / Invoice (".$idMailInv.") dengan rincian awal (batchno = <b>".$batchno."</b>, senderVendor = ".$senderVendorLogAwal.", company = <b>".$compNameAwal."</b>, unit = <b>".$unitNameAwal."</b>, barcode = <b>".$barcodeAwal."</b>, tglinvoice = <b>".$invoiceDateAwal."</b>, noinvoice = <b>".$noInvoiceAwal."</b>, amount = <b>".$amountAwal."</b>, currency = <b>".$currencyAwal."</b>, remark = <b>".$remarkAwal."</b>) <-- menjadi --> (batchno = <b>".$batchno."</b>, senderVendor = ".$senderVendorLog.", company = <b>".$ccomp->detilComp($company, "compname")."</b>, unit = <b>".$cunit->detilUnit($unitt, "deptname")."</b>, barcode = <b>".$barcode."</b>, tglinvoice = <b>".$cpublic->convTglNonDB($invoiceDate)."</b>, tglexp = <b>".$cpublic->convTglNonDB($dueDate)."</b>, noinvoice = <b>".$noInvoice."</b>, amount = <b>".number_format((float)$amount, 2, '.', ',')."</b>, currency = <b>".$currency."</b>, remark = <b>".$remark."</b>)");	
	
	$koneksi->mysqlQuery("UPDATE  mailinvoice SET batchno = '".$batchno."', sendervendor1 = '".$sendervendor1."', sendervendor2 = '".$sendervendor2."', company = '".$company."', unit = '".$unitt."',  barcode = '".$barcode."',  tglinvoice = '".$invoiceDate."',  tglexp = '".$dueDate."',  mailinvno = '".$noInvoice."', amount = '".$amount."', currency = '".$currency."', remark = '".$remark."', updusrdt = '".$userWhoAct."' WHERE idmailinv = '".$idMailInv."' AND deletests=0"); 
}
*/
?>
<style>
body {background-color: #f0efe7;}
</style>


<div id="idHaTambahMailInv">
<?php
if($aksiGet == "tambahMailInv" || $halaman == "resetTambahMailInv")
{
?>
	<input type="hidden" id="batchnoGet" value="<?php echo $batchno; ?>">
    <table cellpadding="0" cellspacing="0" width="100%" height="100%" style="font:0.8em sans-serif;font-weight:bold;color:#485a88;" border="0">
    <div id="idCekBarcodeSama" style="display:none;"><input type="hidden" id="barcodeAdaAtautidak"></div>
    <tr>
        <td valign="top" align="center" height="50" style="font-size:16px;" colspan="2">ADD MAIL / INVOICE</td>
    </tr>
    <tr>
        <td valign="top" align="center">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" height="87%" style="font:1em sans-serif;font-weight:bold;color:#485a88;">
            <tr valign="top">
                <td width="30%">Sender/Vendor</td>
                <td width="70%">
					<input class="inputType" type="text" id="sendervendor1" style="width:276;" onkeyup="ajaxGetMailInv('','','','tulisMailInvSama','idCekMailInvSama');" onfocus="ajaxGetMailInv('','','','tulisMailInvSama','idCekMailInvSama');"> or
					<select class="inputType" id="sendervendor2" onChange="pilihSenderVendorMenu(this.value);ajaxGetMailInv('','','','tulisMailInvSama','idCekMailInvSama');">
						<option value="00000">-- Choose --</option>
						<?php echo $CInvReg->menuSenderVendor(); ?>
					</select>
                    
				</td>
            </tr>
            <tr>
                <td>Addresse</td>
            </tr>
			<tr>
                <td height="22">&nbsp;&nbsp;&nbsp;Company</td>
                <td>
                <select class="inputType" style="width:320px;" id="company" onchange="ajaxGetMailInv('','','','tulisMailInvSama','idCekMailInvSama');">
					<option value="XXX">-- Choose --</option>
					<?php echo $CInvReg->menuCmp(); ?>
				</select>
               <!-- <select class="inputType" id="company" name="company" onchange="">
                <option value="XXX"></option>
                <?php echo $CInvReg->menuCmp(); ?>
                </select>-->
                

                </td>
            </tr>
			<tr>
                <td height="22">&nbsp;&nbsp;&nbsp;Unit</td>
                <td>
				<select class="inputType" style="width:320px;" id="unitt" name="unitt" onchange="">
					<option value="XXX">-- Choose --</option>
					<?php echo $CInvReg->menuUnit(); ?>
				</select>
                </td>
            </tr>
            <tr>
                <td>Barcode</td>
                <td><input maxlength="8" class="inputType" type="text" id="barcode" onfocus="isiBarcode(this.value);ajaxGetMailInv('','','','tulisMailInvSama','idCekMailInvSama'); ajaxGetMailInv('','','','tulisBarcode','idCekBarcodeSama');" onkeyup="isiBarcode(this.value);ajaxGetMailInv('','','','tulisMailInvSama','idCekMailInvSama'); ajaxGetMailInv('','','','tulisBarcode','idCekBarcodeSama');">&nbsp;
				<span style="font-size:11px;color:#666;">* first char use "A" or "S"</span></td>
            </tr>
            <tr>
                <td>Date</td>
                <td>
					 <input class="inputType" type="text" name="invoiceDate" id="invoiceDate" disabled="disabled" style="width:70;" onchange="ajaxGetMailInv('','','','tulisMailInvSama','idCekMailInvSama');"/>&nbsp;<img src="../../picture/calendar.gif" style="cursor: pointer; border: 1px solid red;" title="Select Date" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" onclick="displayCalendar(document.getElementById('invoiceDate'),'dd/mm/yyyy',this)"/>&nbsp;<span class="style2" style="font-size:11px;">(DD/MM/YYYY)</span>
				</td>
            </tr>
			<tr>
                <td>Due Date</td>
                <td>
					 <input class="inputType" type="text" name="dueDate" id="dueDate" disabled="disabled" style="width:70;" onchange="ajaxGetMailInv('','','','tulisMailInvSama','idCekMailInvSama');"/>&nbsp;<img src="../../picture/calendar.gif" style="cursor: pointer; border: 1px solid red;" title="Select Date" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" onclick="displayCalendar(document.getElementById('dueDate'),'dd/mm/yyyy',this)" id="imgCalDueDate"/>&nbsp;<span class="style2" style="font-size:11px">(DD/MM/YYYY)</span>
				</td>
            </tr>
			<tr>
                <td>No</td>
                <td>
                 <input type="text" class="inputType" id="noInvoice" style="width:276;" onkeyup="ajaxGetMailInv('','','','tulisMailInvSama','idCekMailInvSama');" onfocus="ajaxGetMailInv('','','','tulisMailInvSama','idCekMailInvSama');">
                </td>
            </tr>
            <tr>
                <td>Amount</td>
                <td>
					<input type="text" class="inputType" id="amount"  onKeyPress="init();" onkeyup="ajaxGetMailInv('','','','tulisMailInvSama','idCekMailInvSama');" onfocus="ajaxGetMailInv('','','','tulisMailInvSama','idCekMailInvSama');">
				</td>
            </tr>
            <tr>
                <td>Currency</td>
                <td>
				<select class="inputType" id="currency" name="currency" onchange="ajaxGetMailInv('','','','tulisMailInvSama','idCekMailInvSama');">
                    <option value="XXX">-- Choose --</option>
                    <?php
                    //echo $ccurr->menuCurrency();
                    ?>
                 </select>                </td>
            </tr>
            <tr>
                <td valign="top">Remark</td>
                <td>
                 <textarea id="remark" class="inputType" rows="5" cols="51"></textarea>
                </td>
            </tr>
            </table>
        </td>
    </tr>
    <div id="idCekMailInvSama"><input type="hidden" id="cekMailInvSama"  value="kosong"/></div>
    <tr><td id="tdStatusSaved">&nbsp;<?php echo $statusSaved; ?><hr></td></tr>
    <tr>
        <td align="center">
        <button type="button" style="width:70;height:35;cursor:pointer;" onClick="tes();ajaxGetMailInv('','','','simpanTambahMailInv','idHaTambahMailInv');">
            <table border="0" bordercolor="" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td align="center" width="25"><img src="../../picture/Floppy-Disk-blue-32.png" height="20"/></td>
                <td align="center" class="buttonAtas">Save</td>
            </tr>
            </table>
        </button>&nbsp;&nbsp;
        <button type="button" style="width:70;height:35;cursor:pointer;" onClick="exit();">
            <table border="0" bordercolor="" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td align="center" width="25"><img src="../../picture/Metro-Shut-Down-Blue-32.png" height="20"/></td>
                <td align="center" class="buttonAtas">Close</td>
            </tr>
            </table>
        </button>
        </td>
    </tr>
    </table>
<?php
} 
?>
</div> 
<div id="idHalEditMailInv">
<?php
if($aksiGet == "editMailInv" || $halaman == "resetEditMailInv")
{
	if($halaman  == "resetEditMailInv")
	{
		$idMailInvGet = $_POST['idMailInv'];
	}
	else
	{
		$idMailInvGet = $_GET['idMailInv'];
	}
	
	$batchnoDB = $cmailinv->detilMailInvById($idMailInvGet, "batchno");
	
	$urutan = $cmailinv->detilMailInvById($idMailInvGet, "urutan");
	$senderVendor1 = $cmailinv->detilMailInvById($idMailInvGet, "sendervendor1");
	$senderVendor2 = $cmailinv->detilMailInvById($idMailInvGet, "sendervendor2");
	$company = $cmailinv->detilMailInvById($idMailInvGet, "company");
	$unit = $cmailinv->detilMailInvById($idMailInvGet, "unit");
	$barcode = $cmailinv->detilMailInvById($idMailInvGet, "barcode");
	$invoiceDate = $cmailinv->detilMailInvById($idMailInvGet, "tglinvoice");
	$dueDate = $cmailinv->detilMailInvById($idMailInvGet, "tglexp");
	$noInvoice = $cmailinv->detilMailInvById($idMailInvGet, "mailinvno");
	$amount = $cmailinv->detilMailInvById($idMailInvGet, "amount");
	$currency = $cmailinv->detilMailInvById($idMailInvGet, "currency");
	$remark = $cmailinv->detilMailInvById($idMailInvGet, "remark");
	
	$disSenderVendor1 = "";
	$bgSenderVendor1 = "";
	if($senderVendor2 != "") // jika nilai sendervendor2 tidak kosong maka disable sendervendor2
	{
		$disSenderVendor1 = "disabled";
		$bgSenderVendor1 = "background-color:#CCCCCC;";
	}
	
	if(substr($barcode,0,1) == "S")
	{
		$disAmountCurrency = "disabled style=background-color:#CCCCCC;";
	}
	else
	{
		$disAmountCurrency = "";
	}
	
	$menuSenderVendor2 = $ccoa->menuSenderVendor2Select($senderVendor2);
	$menuCompany = $ccomp->menuCmpSelect($company);
	$menuUnit = $cunit->menuUnitSelect($unit);
	$menuCurrency = $ccurr->menuCurrencySelect($currency);
?>
	<input type="hidden" id="batchnoGet" value="<?php echo $batchnoDB; ?>">
    <table cellpadding="0" cellspacing="0" width="100%" height="100%" style="font:0.8em sans-serif;font-weight:bold;color:#485a88;" border="0">
    <tr>
        <td valign="top" align="center" height="50" style="font-size:16px;" colspan="2">EDIT MAIL / INVOICE</td>
    </tr>
    <tr>
        <td valign="top" align="center">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" height="87%" style="font:1em sans-serif;font-weight:bold;color:#485a88;cursor:default;">
            <tr valign="top">
                <td width="30%">Sender/Vendor</td>
                <td width="70%">
					<input class="inputType" type="text" id="sendervendor1" style="width:276;<?php echo $bgSenderVendor1; ?>" value="<?php echo $senderVendor1; ?>" <?php echo $disSenderVendor1; ?>  
					onkeyup="ajaxGetMailInv('<?php echo $idMailInvGet; ?>','','','tulisMailInvSamaEdit','idCekMailInvSama');" 
					onfocus="ajaxGetMailInv('<?php echo $idMailInvGet; ?>','','','tulisMailInvSamaEdit','idCekMailInvSama');"> or
					<select class="inputType" id="sendervendor2" onchange="pilihSenderVendorMenu(this.value);ajaxGetMailInv('<?php echo $idMailInvGet; ?>','','','tulisMailInvSamaEdit','idCekMailInvSama');">
						<option value="00000">-- Choose --</option>
						<?php echo $menuSenderVendor2; ?>
					</select>				</td>
            </tr>
			<tr>
                <td>Addresse</td>
            </tr>
			<tr>
                <td height="22">&nbsp;&nbsp;&nbsp;Company</td>
                <td>
                <select class="inputType" id="company" onchange="ajaxGetMailInv('<?php echo $idMailInvGet; ?>','','','tulisMailInvSamaEdit','idCekMailInvSama');">
					<option value="XXX">-- Choose --</option>
					<?php echo $menuCompany; ?>
				</select>                </td>
            </tr>
			<tr>
                <td height="22">&nbsp;&nbsp;&nbsp;Unit</td>
                <td>
				<select class="inputType" id="unitt" onchange="ajaxGetMailInv('<?php echo $idMailInvGet; ?>','','','tulisMailInvSamaEdit','idCekMailInvSama');">
					<option value="XXX">-- Choose --</option>
					<?php echo $menuUnit; ?>
				</select>                </td>
            </tr>
			<tr>
                <td>Barcode</td>
                <td><input maxlength="8" class="inputType" type="text" id="barcode" value="<?php echo $barcode; ?>" 
				onfocus="isiBarcode(this.value);ajaxGetMailInv('<?php echo $idMailInvGet; ?>','','','tulisMailInvSamaEdit','idCekMailInvSama');" 
				onkeyup="isiBarcode(this.value);ajaxGetMailInv('<?php echo $idMailInvGet; ?>','','','tulisMailInvSamaEdit','idCekMailInvSama');">&nbsp;
				<span style="font-size:11px;color:#666;">* first char use "A" or "S"</span></td>
            </tr>
            <tr>
                <td>Date</td>
                <td>
					 <input class="inputType" type="text" name="invoiceDate" id="invoiceDate" disabled="disabled" style="width:70;" value="<?php echo $cpublic->convTglNonDB($invoiceDate); ?>" onchange="ajaxGetMailInv('<?php echo $idMailInvGet; ?>','','','tulisMailInvSamaEdit','idCekMailInvSama');"/>&nbsp;<img src="../picture/calendar.gif" style="cursor: pointer; border: 1px solid red;" title="Select Date" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" {disDueCalen} onclick="displayCalendar(document.getElementById('invoiceDate'),'dd/mm/yyyy',this)"/>&nbsp;<span class="style2" style="font-size:11px;">(DD/MM/YYYY)</span>				</td>
            </tr>
			<tr>
                <td>Due Date</td>
                <td>
					 <input class="inputType" type="text" name="dueDate" id="dueDate" disabled="disabled" style="width:70;" value="<?php echo $cpublic->convTglNonDB($dueDate); ?>" onchange="ajaxGetMailInv('<?php echo $idMailInvGet; ?>','','','tulisMailInvSamaEdit','idCekMailInvSama');"/>&nbsp;<img src="../picture/calendar.gif" style="cursor: pointer; border: 1px solid red;" title="Select Date" onmouseover="this.style.background='red';" onmouseout="this.style.background=''" onclick="displayCalendar(document.getElementById('dueDate'),'dd/mm/yyyy',this)" id="imgCalDueDate"/>&nbsp;<span class="style2" style="font-size:11px;">(DD/MM/YYYY)</span>
				</td>
            </tr>
			<tr>
                <td>No</td>
                <td><input4 type="text" class="inputType" style="width:276;" id="noInvoice" name="noInvoice" 
				 onfocus="ajaxGetMailInv('<?php echo $idMailInvGet; ?>','','','tulisMailInvSamaEdit','idCekMailInvSama');"
				 onkeyup="ajaxGetMailInv('<?php echo $idMailInvGet; ?>','','','tulisMailInvSamaEdit','idCekMailInvSama');" value="<?php echo $noInvoice; ?>" /></td>
            </tr>
            <tr>
                <td>Amount</td>
                <td>
					<input type="text" class="inputType" id="amount" name="amount"  onKeyPress="init();" value="<?php echo number_format((float) $amount, 2, '.', ','); ?>" 
					onfocus="ajaxGetMailInv('<?php echo $idMailInvGet; ?>','','','tulisMailInvSamaEdit','idCekMailInvSama');" 
					onkeyup="ajaxGetMailInv('<?php echo $idMailInvGet; ?>','','','tulisMailInvSamaEdit','idCekMailInvSama');"
					<?php echo $disAmountCurrency; ?>>
				</td>
            </tr>
            <tr>
                <td>Currency</td>
                <td>
				<select class="inputType" id="currency" name="currency" onchange="ajaxGetMailInv('<?php echo $idMailInvGet; ?>','','','tulisMailInvSamaEdit','idCekMailInvSama');" <?php echo $disAmountCurrency; ?>>
                    <option value="XXX">-- Choose --</option>
                    <?php
                    echo $menuCurrency;
                    ?>
                 </select>				 </td>
            </tr>
			<tr>
                <td valign="top">Remark</td>
                <td><textarea cols="51" rows="5" class="inputType" id="remark"><?php echo $remark; ?></textarea></td>
            </tr>
			</table>
		</td>
	</tr>
	<div id="idCekMailInvSama"><input type="hidden" id="cekMailInvSama"  value="kosong"/></div>
    <tr><td id="tdStatusSaved">
	&nbsp;<?php echo $statusSaved; ?></td></tr>
	<tr><td style="color:#999999;font:0.9em sans-serif;font-weight:bold;cursor:default;">Sno&nbsp;:&nbsp;&nbsp;<span id="idHalSequence" style="color:#485a88;"><?php echo $urutan; ?></span><br /><hr></td></tr>
    <tr>
        <td align="center">
        	<button type="button" style="width:70;height:35;cursor:pointer;" onClick="ajaxGetMailInv('<?php echo $idMailInvGet; ?>','','','simpanEditMailInv','idHalEditMailInv');">
            <table border="0" bordercolor="" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td align="left" width="25%"><img src="../picture/Save-32.png" height="20"/></td>
                <td align="left" class="buttonAtas">&nbsp;Save</td>
            </tr>
            </table>
        </button>&nbsp;&nbsp;
		<button type="button" style="width:70;height:35;cursor:pointer;" onClick="ajaxGetMailInv('<?php echo $idMailInvGet; ?>','','','resetEditMailInv','idHalEditMailInv');">
			<table border="0" bordercolor="" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td align="left" width="25%"><img src="../picture/Refresh-32.png" height="20"/></td>
				<td align="left" class="buttonAtas">&nbsp;Reset</td>
			</tr>
			</table>
		</button>
		&nbsp;&nbsp;
        <button type="button" style="width:70;height:35;cursor:pointer;" onClick="exit();">
            <table border="0" bordercolor="" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td align="left" width="25%"><img src="../picture/Logout-32.png" height="20"/></td>
                <td align="left" class="buttonAtas">&nbsp;Close</td>
            </tr>
            </table>
        </button>
        </td>
    </tr>
	</table>
<?php 
}
?>
</div>
<div id="idHaTambahMailInv">
<?php 
if($halaman == "simpanTambahMailInv")
{
	?>
	<table cellpadding="0" cellspacing="0" width="100%" height="100%" style="font:0.8em sans-serif;font-weight:bold;color:#485a88;" border="0">
    <tr><td>&nbsp;<?php echo $statusSaved; ?><hr></td></tr>
    <tr>
        <td align="center">
        <button type="button" style="width:70;height:35;cursor:pointer;" onclick="exit();">
            <table border="0" bordercolor="" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td align="left" width="25%"><img src="../picture/Logout-32.png" height="20"/></td>
                <td align="left" class="buttonAtas">&nbsp;Close</td>
            </tr>
            </table>
        </button>
		&nbsp;&nbsp;&nbsp;
		<a href="halTambahMailInv.php?aksi=tambahMailInv">Add another Mail / Invoice</a>
        </td>
    </tr>       
    </table>
<?php	
}
?>
</div>
<div id="idHalEditMailInv">
<?php
if($halaman == "simpanEditMailInv")
{ ?>
	<table cellpadding="0" cellspacing="0" width="100%" height="100%" style="font:0.8em sans-serif;font-weight:bold;color:#485a88;" border="0">
    <tr><td>&nbsp;<?php echo $statusSaved; ?><hr></td></tr>
    <tr>
        <td align="center">
        <button type="button" style="width:70;height:35;cursor:pointer;" onclick="exit();">
            <table border="0" bordercolor="" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td align="left" width="25%"><img src="../picture/Logout-32.png" height="20"/></td>
                <td align="left" class="buttonAtas">&nbsp;Close</td>
            </tr>
            </table>
        </button>
        </td>
    </tr>       
    </table>
<?php	
}
?>
</div>