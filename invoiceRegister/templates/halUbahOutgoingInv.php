<!DOCTYPE HTML>
<?php
require_once("../configInvReg.php");

$batchnoGet = $_GET['batchno'];
$idOutgoingInvGet = $_GET['idOutgoingInv'];
?>

<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="../../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../../js/Parsers.js"></script>
<script type="text/javascript" src="../../js/InputMask.js"></script>
<script type="text/javascript" src="../js/invReg.js"></script>
<script type="text/javascript" src="../js/tambahOutgoing.js"></script>

<link rel="stylesheet" type="text/css" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK>
<link rel="stylesheet" type="text/css" href="../../css/archives.css"> 
<link href="../css/invReg.css" rel="stylesheet" type="text/css"> 
<link rel="stylesheet" type="text/css" href="../css/button.css"/>
<link rel="stylesheet" type="text/css" href="../css/table.css"/>

<style>
body {background-color: #f9f9f9;}
</style>

<?php
if($aksiPost == "simpanRubah")
{
	$sentDate = $CPublic->convTglDB($_POST['sentDate']);
	$customerPost = strtoupper($_POST['customer']);
	$custCodePost = rtrim( $_POST['custCode'] );
	$company = $_POST['company'];
	$companyName = $CInvReg->detilComp($company , "compname");
	$invoiceDate = $CPublic->convTglDB($_POST['invoiceDate']);
	$dueDay = $_POST['dueDay'];
	$dueDate = $CPublic->convTglDB($_POST['dueDate']);
	$noInvoice = mysql_escape_string( $_POST['noInvoice'] );
	$amount = str_replace(",","",$_POST['amount']);
	$currency = $_POST['currency'];
	$remark = mysql_escape_string( $_POST['remark'] );
	$moreInvMail =  $_POST['moreInvMail'];  
	
	if($custCodePost == "")
	{
		$tipeCust = "1";
		$customer1 = mysql_escape_string( $customerPost );
		$customer2 = "";
		$customer2Name = "";
		$kreditacc = "";
		
		$customerName = $customer1;
	}
	if($custCodePost != "")
	{
		$tipeCust = "2";
		$customer1 = "";
		$customer2 = $custCodePost; //ACCOUNT CODE
		$customer2Name = $customerPost; // ACCOUNT NAME
		$kreditacc = $custCodePost;
		
		$customerName = $customer2Name;
	}
	if($kreditacc == "")
	{
		$kreditacc = "11001";
	}
	
	$CKoneksiInvReg->mysqlQuery("UPDATE outgoinginvoice SET tipecustomer='".$tipeCust."', customer1='".$customer1."', customer2='".$customer2."',customer2name='".$customer2Name."', customername='".$customerName."', company='".$company."', companyname='".$companyName."', tglinvoice='".$invoiceDate."', dueday='".$dueDay."', tglexp='".$dueDate."', currency='".$currency."', amount='".$amount."', outgoinginvno='".$noInvoice."', remark='".$remark."', kreditacc='".$kreditacc."', sentdate='".$sentDate."', updusrdt='".$userWhoAct."' WHERE idoutgoinginv=".$idOutgoingInvGet." AND deletests=0 LIMIT 1;");
	
	$CHistory->updateLogInvReg($userIdLogin, "Simpan RUBAH Outgoing Invoice (idoutgoinginv=<b>".$idOutgoingInvGet."</b>, urutan=<b></b>, batchno=<b>".$batchnoGet."</b>, tipecustomer=<b>".$tipeCust."</b>, customer1=<b>".$customer1."</b>, customer2=<b>".$customer2."</b>, customer2name=<b>".$customer2Name."</b>, ".$senderVendorLog."=<b>".$customerName."</b>, customer2name=<b>".$customer2Name."</b>, company=<b>".$company."</b>, companyname=<b>".$companyName."</b>, tglinvoice=<b>".$invoiceDate."</b>, tglexp=<b>".$dueDate."</b>, currency=<b>".$currency."</b>, amount=<b>".$amount."</b>, outgoinginvno=<b>".$noInvoice."</b>, remark=<b>".$remark."</b>, kreditacc=<b>".$kreditacc."</b>, receive date=<b>".$sentDate."</b>, updusrdt=<b>".$userWhoAct."</b>)");	
			
	echo ("<body onLoad=\"doneWait();\">");
	$tutupWindow = "ya";
}

$sentDate = $CPublic->convTglNonDB( $CInvReg->detilOutgoingInv($idOutgoingInvGet, "sentdate") );
$customer = "";
$tipeSenderVen = $CInvReg->detilOutgoingInv($idOutgoingInvGet, "tipecustomer");
if($tipeSenderVen == "1")
	$customer = $CInvReg->detilOutgoingInv($idOutgoingInvGet, "customer1");
	
if($tipeSenderVen == "2")
	$customer = $CInvReg->detilOutgoingInv($idOutgoingInvGet, "customer2name");

$custCode = $CInvReg->detilOutgoingInv($idOutgoingInvGet, "customer2");

$company = $CInvReg->detilOutgoingInv($idOutgoingInvGet, "company");
$invoiceDate = $CPublic->convTglNonDB( $CInvReg->detilOutgoingInv($idOutgoingInvGet, "tglinvoice") );
$dueDay = $CInvReg->detilOutgoingInv($idOutgoingInvGet, "dueday");

$dueDate = "";
if($CInvReg->detilOutgoingInv($idOutgoingInvGet, "tglexp") != "0000-00-00")
	$dueDate = $CPublic->convTglNonDB( $CInvReg->detilOutgoingInv($idOutgoingInvGet, "tglexp") );
	
$noInvoice = $CInvReg->detilOutgoingInv($idOutgoingInvGet, "outgoinginvno");

$amount = "";
if($CInvReg->detilOutgoingInv($idOutgoingInvGet, "amount") != "0.00")
	$amount = $CPublic->jikaKosongStrip(number_format((float)$CInvReg->detilOutgoingInv($idOutgoingInvGet, "amount"), 2, '.', ','));
	
$currency  = $CInvReg->detilOutgoingInv($idOutgoingInvGet, "currency");
$remark = $CInvReg->detilOutgoingInv($idOutgoingInvGet, "remark");
$SNo = $CInvReg->detilOutgoingInv($idOutgoingInvGet, "urutan");

$dis = "";

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
	
	setTimeout(function()
	{
		$('#remark').keyup();
	},50);
	 
	<?php
	$sure = "Y";
	if($userJenisInvReg == "user")
	{	// TOMBOL SAVE DISABLED JIKA SUDAH ACKNOWLEDGE DAN AKSES EDIT TIDAK BOLEH
		if($CInvReg->detilOutgoingInv($idOutgoingInvGet, "ack") == 1 OR $CInvReg->aksesInvReg($userIdSession, "btnincoming_edit") == "disabled")
		{
			echo "disabledBtn('btnSave')";
			$sure = "N";
		}
	}
	?>
}
</script>

<div id="loaderImg" style="visibility:hidden;" class="pleaseWait2">
    <div class="isiPleaseWait tabelBorderAll">&nbsp;&nbsp;Please Wait...&nbsp;<img src="../picture/loading (115).gif" height="12"/>&nbsp;</div>
</div>

<div id="idHaTambahMailInv">
    <table cellpadding="0" cellspacing="0" width="540" height="490" style="font:0.8em sans-serif;font-weight:bold;color:#485a88;" border="0" align="center">
    <form method="post" action="halUbahOutgoingInv.php?batchno=<?php echo $batchnoGet; ?>&idOutgoingInv=<?php echo $idOutgoingInvGet; ?>" enctype="multipart/form-data" id="formTambahOutgoingInv" name="formTambahOutgoingInv">
    <input type="hidden" id="aksi" name="aksi" value="simpanRubah">
    <input type="hidden" id="batchno" name="batchno" value="<?php echo $batchnoGet; ?>">
    <input type="hidden" id="idOutgoingInv" value="<?php echo $idOutgoingInvGet; ?>">
    <input type="hidden" id="dateNow" name="dateNow" value="<?php echo $dateNow; ?>">
    
    <div id="idCekAckYesNo" style="position:absolute;top:0px;left:0px;z-index:10;"><input type="hidden" id="ackYesNo" value="no" size="10"></div>
    
    <tr>
        <td valign="top" align="center" height="50" colspan="2"><span style="font-size:16px;">EDIT INVOICE</span></td>
    </tr>
    <tr>
        <td height="330" valign="top">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" style="font:1em sans-serif;font-weight:bold;color:#485a88;">
            <tr valign="middle">
                <td height="22" class="">Batchno</td>
                <td class="elementTeks"><?php echo $batchnoGet; ?></td>
            </tr>
            <tr valign="top">
                <td height="22" class="">Company</td>
                <td class="">
    			<select id="company" name="company" class="elementMenu" style="width:320px;">
					<option value="XXX"></option>
					<?php echo $CInvReg->menuCmp($company); ?>
				</select>
                </td>
            </tr>
            
            <tr valign="top">
                <td width="130" height="22" class="">Customer</td>
                <td width="410" class="">
                    <input type="text" class="elementInput" id="customer" name="customer" style="width:307px;" onkeypress="return" onkeyup="maxChar(this, 200);" value="<?php echo $customer; ?>">
                    <input type="hidden" id="urutCustSelect" style="width:10px;text-align:right;" value="0">
                    <input type="hidden" class="elementMenu" id="custCode" name="custCode" style="width:100px;" value="<?php echo $custCode; ?>">
                    <div id="autoCompCust" class="overout" style="position:absolute;display:none;z-index:10;width:335px;height:300px;overflow:auto;border-color:#333;"></div>
                    
                    <span id="custCode2"><?php echo $custCode; ?></span>
				</td>
            </tr>
            
            <tr valign="top">
                <td height="22" class="">Invoice Date</td>
                <td class="">
					 <span id="spanInvoiceDate"><input type="text" name="invoiceDate" id="invoiceDate" class="elementInput" style="width:60px;" value="<?php echo $invoiceDate; ?>"  onchange="$('#sentDate').val(this.value);"/></span>
                     <img src="../../picture/calendar.gif" class="gayaKalender" title="Select Date" onclick="displayCalendar(document.getElementById('invoiceDate'),'dd/mm/yyyy',this, '', '', '193', '183');"/>&nbsp;<span class="spanKalender">(DD/MM/YYYY)</span>
				</td>
            </tr>
			<tr valign="top">
                <td height="22" class="">Due Date</td>
                <td class=""><div id="divDueDay" style="display:none;">&nbsp;</div><div id="divDueDate" style="display:none;">&nbsp;</div>
                     <input type="text" name="dueDay" id="dueDay" class="elementInput" style="width:20px;<?php echo $bgColor; ?>" value="<?php echo $dueDay; ?>" <?php echo $dis; ?>/>&nbsp;<span class="spanKalender">(Day)</span>
					 <input type="text" name="dueDate" id="dueDate" class="elementInput" style="width:60px;<?php echo $bgColor; ?>" value="<?php echo $dueDate; ?>" <?php echo $dis; ?>/>
                     <img src="../../picture/calendar.gif" class="gayaKalender" title="Select Date" onclick="displayCalendar(document.getElementById('dueDate'),'dd/mm/yyyy',this, '', '', '193', '183');" id="imgCalDueDate" <?php echo $dis; ?>/>&nbsp;<span class="spanKalender">(DD/MM/YYYY)</span>
				</td>
            </tr>
			<tr valign="top">
                <td height="22" class="">Invoice No</td>
                <td class="">
                	<input type="text" id="noInvoice" name="noInvoice" class="elementInput" style="width:188px;" value="<?php echo $noInvoice; ?>">
                </td>
            </tr>
            <tr valign="top">
                <td height="22" class="">Amount</td>
                <td class="">
					<input type="text" id="amount" name="amount" class="elementInput" style="width:188px;text-align:right;<?php echo $bgColor; ?>" value="<?php echo $amount; ?>" <?php echo $dis; ?>>
				</td>
            </tr>
            <tr valign="top">
                <td height="22" class="">Currency</td>
                <td class="">
                    <select id="currency" name="currency" class="elementMenu" style="width:200px;<?php echo $bgColor; ?>" <?php echo $dis; ?>>
                        <option value="XXX"></option>
                        <?php echo $CInvReg->menuCurrency($currency); ?>
                     </select>
                 </td>
            </tr>
            <tr valign="top">
                <td height="70" class="">Remark</td>
                <td class="">
                	<textarea id="remark" name="remark" class="elementInput" rows="5" cols="51" style="height:70px;" onkeyup="textCounter(this, sisaRemarks, 200);"><?php echo $remark; ?></textarea>
                    <input disabled="disabled" readonly type="text" name="sisaRemarks" value="200" style="width:23px">
                </td>
            </tr>
            <tr valign="top">
                <td height="22" class="">Sent Date</td>
                <td class="">
					 <span id="spanInvoiceDate"><input type="text" name="sentDate" id="sentDate" class="elementInput" style="width:60px;" value="<?php echo $sentDate; ?>"/></span>
                     <img src="../../picture/calendar.gif" class="gayaKalender" title="Select Date" onclick="displayCalendar(document.getElementById('sentDate'),'dd/mm/yyyy',this, '', '', '193', '183');"/>&nbsp;<span class="spanKalender">(DD/MM/YYYY)</span>
				</td>
            </tr>
            <tr valign="middle">
                <td height="22" class="">SNo</td>
                <td class="elementTeks" style="text-decoration:underline;"><?php echo $SNo; ?></td>
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
        <td height="30" align="center" class="tabelBorderTopJust" style="padding-top:5px;">
        <button class="btnStandar" onclick="tutupNewMail('<?php echo $sure; ?>');">
            <table border="0" width="63" height="30">
            <tr>
                <td align="center" width="20"><img src="../picture/door-open-out.png"/></td>
                <td align="left">CLOSE</td>
            </tr>
            </table>
        </button>
        <button id="btnSave" class="btnStandar" onclick="pilihBtnSave();">
            <table width="53" height="30">
            <tr>
                <td align="center" width="20"><img src="../picture/disk-black.png"/></td>
                <td align="left">SAVE</td>
            </tr>
            </table>
        </button>
        <button class="btnStandar" onclick="formTambahOutgoingInv.reset();">
            <table width="53" height="30">
            <tr>
                <td align="center" width="20"><img src="../picture/arrow-return-180-left.png"/></td>
                <td align="left">RESET</td>
            </tr>
            </table>
        </button>
        </td>
    </tr>    
    </table>
</div> 

<script>
<?php
if($tutupWindow == "ya")
{
	echo "tutupNewMail('N');";
}
?>
</script>
</HTML>