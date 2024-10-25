<!DOCTYPE HTML>
<?php
require_once("../configInvReg.php");

$batchnoGet = $_GET['batchno'];
?>

<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="../../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../../js/Parsers.js"></script>
<script type="text/javascript" src="../../js/InputMask.js"></script>
<script type="text/javascript" src="../js/invReg.js"></script>
<script type="text/javascript" src="../js/tambahOutgoing.js"></script>
<script type="text/javascript" src="./js/tambahOutgoing.js"></script>

<link rel="stylesheet" type="text/css" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK>
<link rel="stylesheet" type="text/css" href="../../css/archives.css"> 
<link href="../css/invReg.css" rel="stylesheet" type="text/css"> 
<link rel="stylesheet" type="text/css" href="../css/button.css"/>
<link rel="stylesheet" type="text/css" href="../css/table.css"/>
<?php
if($aksiPost == "simpanBaru")
{	
	/*foreach ($_POST as $key => $value) {
        echo $key." : ";
        echo $value."</br>";
    }*/
	
	$batchnoPost = $_POST['batchno'];
	$company = $_POST['company'];
	$companyName = $CInvReg->detilComp($company , "compname");
	$customerPost = strtoupper($_POST['customer']);
	$custCodePost = rtrim( $_POST['custCode'] );
	$invoiceDate = $CPublic->convTglDB($_POST['invoiceDate']);
	$dueDay = $_POST['dueDay'];
	$dueDate = $CPublic->convTglDB($_POST['dueDate']);
	$noInvoice = mysql_escape_string( $_POST['noInvoice'] );
	$amount = str_replace(",","",$_POST['amount']);
	$currency = $_POST['currency'];
	$remark = mysql_escape_string( $_POST['remark'] );
	$sentDate = $CPublic->convTglDB($_POST['sentDate']);
	$moreInvOutgoing =  $_POST['moreInvOutgoing']; 
	
	if($custCodePost == "")
	{
		$tipeCust = "1";
		$customer1 = mysql_escape_string( $senderVendorPost );
		$customer2 = "";
		$customer2Name = "";
		$kreditacc = "";
		
		$customerName = $customer1;
	}
	if($custCodePost != "")
	{
		$tipeCust = "2";
		$customer1 = "";
		$customer2 = $custCodePost; // ACCOUNT CODE
		$customer2Name = $customerPost; // ACCOUNT NAME
		$kreditacc = $custCodePost;
		
		$customerName = $customer2Name;
	}
	if($kreditacc == "")
	{
		$kreditacc = "11001";
	}
	
	//echo $tipeCust." | ".$customer1." | ".$customer2." | ".$customer2Name." | ".$customerName." | ".$kreditacc;
	
	$CKoneksiInvReg->mysqlQuery("INSERT INTO outgoinginvoice 
						 (urutan, batchno, tipecustomer, customer1, customer2, customer2name, customername, company, companyname, tglinvoice, dueday, tglexp, currency, amount, outgoinginvno, remark, kreditacc, sentdate, addusrdt) 
						 VALUES 
						( (SELECT CASE WHEN a.urutan IS NOT NULL THEN (MAX(a.urutan+1)) ELSE '1' END AS urutann FROM outgoinginvoice a WHERE a.batchno='".$batchnoGet."' AND a.deletests=0), 
						'".$batchnoPost."', 
						'".$tipeCust."', 
						'".$customer1."', 
						'".$customer2."', 
						'".$customer2Name."',
						'".$customerName."', 
						'".$company."', 
						'".$companyName."', 
						'".$invoiceDate."', 
						'".$dueDay."',  
						'".$dueDate."',  
						'".$currency."', 
						'".$amount."', 
						'".$noInvoice."', 
						'".$remark."',  
						'".$kreditacc."', 
						'".$sentDate."', 
						'".$userWhoAct."')");
						
	$lastInsertId = mysql_insert_id();				
	$CHistory->updateLogInvReg($userIdLogin, "Simpan TAMBAH/BARU Outgoing Invoice (idmailinv=<b>".$lastInsertId."</b>, urutan=<b></b>, batchno=<b>".$batchnoPost."</b>, tipecustomer=<b>".$tipeCust."</b>, customer1=<b>".$customer1."</b>, customer2=<b>".$customer2."</b>, customer2name=<b>".$customer2Name."</b>, company=<b>".$company."</b>, companyname=<b>".$companyName."</b>, unit=<b>".$unitt."</b>, unitname=<b>".$unitName."</b>, barcode=<b>".$barcode."</b>, tglinvoice=<b>".$invoiceDate."</b>, tglexp=<b>".$dueDate."</b>, currency=<b>".$currency."</b>, amount=<b>".$amount."</b>, outgoinginvno=<b>".$noInvoice."</b>, remark=<b>".$remark."</b>, kreditacc=<b>".$kreditacc."</b>, receive date=<b>".$sentDate."</b>, addusrdt=<b>".$userWhoAct."</b>)");
						
	echo ("<body onLoad=\"doneWait();\">");
	if($moreInvOutgoing == "on")
		$tutupWindow = "tidak";

	if($moreInvOutgoing == "")
		$tutupWindow = "ya";
}

$dateNow = $CPublic->zerofill($CPublic->tglDayServer(),2)."/".$CPublic->zerofill($CPublic->bulanServer(),2)."/".$CPublic->tahunServer();
?>
<style>
body {background-color: #f9f9f9;}
</style>

<script>
window.onload = function()
{
	//document.oncontextmenu = function(){	return false;	}; 
	setup();
	doneWait();
}
</script>

<div id="loaderImg" style="visibility:visible;" class="pleaseWait2">
    <div class="isiPleaseWait tabelBorderAll">&nbsp;&nbsp;&nbsp;Please Wait...&nbsp;<img src="../picture/loading (115).gif" height="12"/>&nbsp;</div>
</div>

<div id="idHalTambahMailInv">
<!--<textarea id="taTes"></textarea>-->
    <table cellpadding="0" cellspacing="0" width="540" height="490" style="font:0.8em sans-serif;font-weight:bold;color:#485a88;" border="0" align="center">
    <form method="post" action="halTambahOutgoingInv.php?batchno=<?php echo $batchnoGet; ?>" enctype="multipart/form-data" id="formTambahOutgoingInv" name="formTambahOutgoingInv">
    <!--<input type="text" id="tipeSenderVendor" name="tipeSenderVendor">
    <input type="text" id="senderVendor" name="senderVendor">-->
    <input type="hidden" id="aksi" name="aksi" value="simpanBaru">
    <input type="hidden" id="dateNow" name="dateNow" value="<?php echo $dateNow; ?>">
    <input type="hidden" id="batchno" name="batchno" value="<?php echo $batchnoGet; ?>">
    
    <div id="idCekBarcodeSama"><input type="hidden" id="barcodeSamaAdaTidak" value="kosong"></div>
    <tr>
        <td valign="top" align="center" height="50" colspan="2"><span style="font-size:16px;">ADD OUTGOING INVOICE</span></td>
    </tr>
    <tr>
        <td height="330" valign="top">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" style="font:1em sans-serif;font-weight:bold;color:#485a88;">
             <tr valign="top">
                <td height="22" class="">Batchno</td>
                <td class="elementTeks"><?php echo $batchnoGet; ?></td>
            </tr>
            
            <tr valign="top">
                <td height="22" class="">Company</td>
                <td class="">
    			<select id="company" name="company" class="elementMenu" style="width:320px;">
					<option value="XXX">-- PLEASE SELECT  --</option>
					<?php echo $CInvReg->menuCmp(''); ?>
				</select>
                </td>
            </tr>
     
            <tr valign="top">
                <td width="130" height="22" class="">Customer</td>
                <td width="410" class="">
                    <input type="text" class="elementInput" id="customer" name="customer" style="width:307px;" onkeypress="return;" onkeyup="maxChar(this, 200);">
                    <input type="hidden" id="urutCustSelect" style="width:10px;text-align:right;" value="0">
                    <input type="hidden" class="elementMenu" id="custCode" name="custCode" style="width:100px;">
                	<div id="autoCompCust" class="overout" style="position:absolute;display:none;z-index:10;width:335px;height:300px;overflow:auto;border-color:#333;"></div>
					</select>
                    <span id="custCode2">&nbsp;</span>
				</td>
            </tr>
            
            
            <tr valign="top">
                <td height="22" class="">Invoice Date</td>
                <td class="">
					 <span id="spanInvoiceDate"><input type="text" name="invoiceDate" id="invoiceDate" class="elementInput" style="width:60px;" onchange="$('#sentDate').val(this.value);"/></span>
                     <img src="../../picture/calendar.gif" class="gayaKalender" title="Select Date" onclick="displayCalendar(document.getElementById('invoiceDate'),'dd/mm/yyyy',this, '', '', '193', '183');"/>&nbsp;<span class="spanKalender">(DD/MM/YYYY)</span>
				</td>
            </tr>
			<tr valign="top">
                <td height="22" class="">Due Date</td>
                <td class=""><div id="divDueDay" style="display:none;">&nbsp;</div><div id="divDueDate" style="display:none;">&nbsp;</div>
                     <input type="text" name="dueDay" id="dueDay" class="elementInput" style="width:20px;"/>&nbsp;<span class="spanKalender">(Day)</span>
					 <input type="text" name="dueDate" id="dueDate" class="elementInput" style="width:60px;"/>
                     <img src="../../picture/calendar.gif" class="gayaKalender" title="Select Date" onclick="displayCalendar(document.getElementById('dueDate'),'dd/mm/yyyy',this, '', '', '193', '183');" id="imgCalDueDate"/>&nbsp;<span class="spanKalender">(DD/MM/YYYY)</span>
				</td>
            </tr>
			<tr valign="top">
                <td height="22" class="">Invoice No</td>
                <td class="">
                	<input type="text" id="noInvoice" name="noInvoice" class="elementInput" style="width:188px;">
                </td>
            </tr>
            <tr valign="top">
                <td height="22" class="">Amount</td>
                <td class="">
					<input type="text" id="amount" name="amount" class="elementInput" style="width:188px;text-align:right;">
				</td>
            </tr>
            <tr valign="top">
                <td height="22" class="">Currency</td>
                <td class="">
                    <select id="currency" name="currency" class="elementMenu" style="width:200px;">
                        <option value="XXX">-- PLEASE SELECT  --</option>
                        <?php echo $CInvReg->menuCurrency(''); ?>
                     </select>
                 </td>
            </tr>
            <tr valign="top">
                <td height="70" class="">Remark</td>
                <td class="">
                	<textarea id="remark" name="remark" class="elementInput" rows="5" cols="51" style="height:70px;" onkeyup="textCounter(this, sisaRemarks, 200);"></textarea>
                    <input disabled="disabled" readonly type="text" name="sisaRemarks" value="200" style="width:23px">
                </td>
            </tr>
            
            <tr valign="top">
                <td height="22" class="">Sent Date</td>
                <td class="">
					 <span id="spanInvoiceDate"><input type="text" name="sentDate" id="sentDate" class="elementInput" style="width:60px;"/></span>
                     <img src="../../picture/calendar.gif" class="gayaKalender" title="Select Date" onclick="displayCalendar(document.getElementById('sentDate'),'dd/mm/yyyy',this, '', '', '193', '183');"/>&nbsp;<span class="spanKalender">(DD/MM/YYYY)</span>
				</td>
            </tr>
            
            <tr valign="top">
                <td height="22" class="">&nbsp;</td>
                <td class=""><input type="checkbox" id="moreInvOutgoing" name="moreInvOutgoing" style="cursor:pointer;">
                <span style="cursor:pointer;" onclick="$('#moreInvOutgoing').click();">&nbsp;More Invoice</span></td>
            </tr>
            </table>
        </td>
    </tr>
    </form>
    <tr>
    
    <tr><td height="70"></td></tr>
    
    <tr valign="top">
        <td height="20" valign="bottom">
        	<div id="idErrorMsg" class="errorMsgInv" style="visibility:hidden;"><img src="../picture/exclamation-red.png"/>&nbsp;<span>&nbsp;</span>&nbsp;</div>
        </td>
    </tr>
    
    <tr>
        <td height="30" align="center" class="tabelBorderTopJust" style="padding-top:5px;" valign="bottom">
        <button class="btnStandar" onclick="tutupNewMail('Y');">
            <table border="0" width="63" height="30">
            <tr>
                <td align="center" width="20"><img src="../picture/door-open-out.png"/></td>
                <td align="left">CLOSE</td>
            </tr>
            </table>
        </button>
        <button class="btnStandar" onclick="pilihBtnSave();return false;">
            <table width="53" height="30">
            <tr>
                <td align="center" width="20"><img src="../picture/disk-black.png"/></td>
                <td align="left">SAVE</td>
            </tr>
            </table>
        </button>
        </td>
    </tr>    
    </table>
</div> 

<script>
<?php
if($tutupWindow == "tidak")
{
	echo "parent.openThickboxWindow('', 'newAct')";
}
if($tutupWindow == "ya")
{
	echo "tutupNewMail('N');";
}
?>
</script>
</HTML>