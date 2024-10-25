<?php require_once("../configPaymentAdvance.php"); ?>
<!DOCTYPE HTML>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="../../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../../js/Parsers.js"></script>
<script type="text/javascript" src="../../js/InputMask.js"></script>
<script type="text/javascript" src="../js/masks.js"></script>
<script type="text/javascript" src="../js/tambahMail.js"></script>

<link rel="stylesheet" type="text/css" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK>
<link rel="stylesheet" type="text/css" href="../../css/archives.css">
<link rel="stylesheet" type="text/css" href="../css/button.css"/>
<link rel="stylesheet" type="text/css" href="../css/table.css"/>
<?php
$batchnoGet = $_GET['batchno'];

if($aksiPost == "simpanBaru")
{

}

?>
<style>
body {background-color: #f9f9f9;}
</style>

<script>
   
</script>

<div id="loaderImg" style="visibility:visible;" class="pleaseWait2">
    <div class="isiPleaseWait tabelBorderAll">&nbsp;&nbsp;&nbsp;Please Wait...&nbsp;<img src="../picture/loading (115).gif" height="12"/>&nbsp;</div>
</div>

<div id="idHalTambahMailInv">
<!--<textarea id="taTes"></textarea>-->
    <table cellpadding="0" cellspacing="0" width="540" height="490" style="font:0.8em sans-serif;font-weight:bold;color:#485a88;" border="0" align="center">
    <form method="post" action="halTambahMailInv.php?batchno=<?php echo $batchnoGet; ?>" enctype="multipart/form-data" id="formTambahMailInv" name="formTambahMailInv">
    <!--<input type="text" id="tipeSenderVendor" name="tipeSenderVendor">
    <input type="text" id="senderVendor" name="senderVendor">-->
    <input type="hidden" id="aksi" name="aksi" value="simpanBaru">
    <input type="hidden" id="dateNow" name="dateNow" value="<?php echo $dateNow; ?>">
    <input type="hidden" id="batchno" name="batchno" value="<?php echo $batchnoGet; ?>">
    
    <div id="idCekBarcodeSama"><input type="hidden" id="barcodeSamaAdaTidak" value="kosong"></div>
    <tr>
        <td valign="top" align="center" height="50" colspan="2"><span style="font-size:16px;">ADD MAIL / INVOICE</span></td>
    </tr>
    <tr>
        <td height="400" valign="top">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" style="font:1em sans-serif;font-weight:bold;color:#485a88;">
             <tr valign="top">
                <td height="22" class="">Batchno</td>
                <td class="elementTeks"><?php echo $batchnoGet; ?></td>
            </tr>
            <tr valign="top">
                <td height="22" class="">Receive Date</td>
                <td class="">
					 <span id="spanInvoiceDate"><input type="text" name="receiveDate" id="receiveDate" class="elementInput" style="width:60px;"/></span>
                     <img src="../../picture/calendar.gif" class="gayaKalender" title="Select Date" onclick="displayCalendar(document.getElementById('receiveDate'),'dd/mm/yyyy',this, '', '', '193', '183');"/>&nbsp;<span class="spanKalender">(DD/MM/YYYY)</span>
				</td>
            </tr>
            <tr valign="top">
                <td width="140" height="22" class="">Sender/Vendor</td>
                <td width="400" class="">
                	<!--<input type="text" class="elementInput" id="senderVendor" name="senderVendor" style="width:307px;" onkeypress="return changeToUpperCase(event,this);" onkeyup="maxChar(this, 200);">-->
                    <input type="text" class="elementInput" id="senderVendor" name="senderVendor" style="width:307px;" onkeypress="return;" onkeyup="maxChar(this, 200);">
                    <input type="hidden" id="urutSendSelect" style="width:10px;text-align:right;" value="0">
                    <input type="hidden" class="elementMenu" id="senderVendorCode" name="senderVendorCode" style="width:100px;">
                	<div id="autoCompSender" class="overout" style="position:absolute;display:none;z-index:10;width:335px;height:300px;overflow:auto;border-color:#333;"></div>
			    	<!--<input class="elementInput" type="text" id="sendervendor1" name="sendervendor1" style="width:363px;" onkeypress="return changeToUpperCase(event,this);" onkeyup="maxChar(this, 200);">
                    <select id="sendervendor2" name="sendervendor2" class="elementMenu" style="width:375px;top:2px;position:relative;">
						<option value="00000"></option>
						<?php //echo $CInvReg->menuSenderVendor(''); ?>
					</select>-->
                    <span id="senderVendorCode2">&nbsp;</span>
				</td>
            </tr>
            <!--<tr valign="top">
                <td height="22" class="">&nbsp;&nbsp;&nbsp;Account Code</td>
                <td class=""><input type="text" class="elementMenu" id="senderVendorCode"></td>
            </tr>-->
            <tr valign="top">
                <td height="16" class="">Addresse</td>
            </tr>
			<tr valign="top">
                <td height="22" class="">&nbsp;&nbsp;&nbsp;Company</td>
                <td class="">
    			<select id="company" name="company" class="elementMenu" style="width:320px;">
					<option value="XXX">-- PLEASE SELECT  --</option>
					<?php echo $CInvReg->menuCmp(''); ?>
				</select>
                </td>
            </tr>
			<tr valign="top">
                <td height="22" class="">&nbsp;&nbsp;&nbsp;Unit</td>
                <td class="">
        		<select id="unitt" name="unitt" class="elementMenu" style="width:320px;">
					<option value="XXX">-- PLEASE SELECT  --</option>
					<?php echo $CInvReg->menuUnit(''); ?>
				</select>
                </td>
            </tr>
            <tr valign="top">
                <td height="22" class="">Barcode</td>
                <td class=""><input type="text" id="barcode" name="barcode" class="elementInput" maxlength="8" style="width:60px;" onkeyup="isiBarcode(this.value);"><span class="spanKalender">&nbsp;* Use A ( Invoice ) or S ( Mail )</span>
                </td>
            </tr>
            <tr valign="top">
                <td height="22" class="">Invoice / Letter Date</td>
                <td class="">
					 <span id="spanInvoiceDate"><input type="text" name="invoiceDate" id="invoiceDate" class="elementInput" style="width:60px;"/></span>
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
                <td height="22" class="">Mail/Invoice No</td>
                <td class="">
                	<input type="text" id="noInvoice" name="noInvoice" class="elementInput" onchange="cekNoInvoiceNya();" style="width:188px;">
                </td>
            </tr>
            <tr valign="top">
                <td height="22" class="">Amount</td>
                <td class="">
					<input type="text" id="amount" name="amount" class="elementInput" style="width:188px;text-align:right;" onkeyup="hanyaAngkaAmount();return false;">
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
                <td height="22" class="">File</td>
                <td class="">
                    <input type="file" name="fileUploadNya" id="fileUploadNya" class="btnStandar" style="width:250px" title="File Upload">
                    &nbsp <a style="cursor:pointer;" onclick="$('#fileUploadNya').val('');"> Clear </a>
                </td>
            </tr>
            <tr valign="top">
                <td height="22" class="">&nbsp;</td>
                <td class=""><input type="checkbox" id="moreInvMail" name="moreInvMail" style="cursor:pointer;"><span style="cursor:pointer;" onclick="$('#moreInvMail').click();">&nbsp;More Invoice / Mail</span></td>
            </tr>
            </table>
        </td>
    </tr>
    </form>
    <tr>
    
    <tr valign="top">
        <td height="20" valign="bottom">
        	<div id="idErrorMsg" class="errorMsgInv" style="visibility:hidden;"><img src="../picture/exclamation-red.png"/>&nbsp;<span>&nbsp;</span>&nbsp;</div>
        </td>
    </tr>
    
    <tr>
        <td height="30" align="center" class="tabelBorderTopJust" style="padding-top:5px;">
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

window.onload = function()
{
	//document.oncontextmenu = function(){	return false;	}; 
	doneWait();
	setup();
	
	var batchno = $('#batchno').val();
	var nu = batchno.search("nu");
	if(nu != -1)
	{
		alert("Error:#nu, Please Contact your Programmer");
		disabledBtn("btnSave");
		return false;
	}
}
</script>
</HTML>