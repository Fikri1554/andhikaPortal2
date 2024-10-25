<!DOCTYPE HTML>
<?php
require_once("../configInvReg.php");

$batchnoGet = $_GET['batchno'];
$idMailInvGet = $_GET['idMailInv'];
?>

<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="../../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../../js/Parsers.js"></script>
<script type="text/javascript" src="../../js/InputMask.js"></script>
<script type="text/javascript" src="../js/masks.js"></script>
<script type="text/javascript" src="../js/invReg.js"></script>
<script type="text/javascript" src="../js/tambahMail.js"></script>

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
	/*$tipeSenderVendor = $_POST['tipeSenderVendor'];
	$senderVendor = $_POST['senderVendor'];*/
	$receiveDate = $CPublic->convTglDB($_POST['receiveDate']);
	$senderVendorPost = strtoupper($_POST['senderVendor']);
	$senderVendorCodePost = rtrim( $_POST['senderVendorCode'] );
	$company = $_POST['company'];
	$companyName = $CInvReg->detilComp($company , "compname");
	$unitt = $_POST['unitt'];
	$unitName = $CInvReg->detilUnit($unitt, "nmunit");
	$barcode = $_POST['barcode'];
	$invoiceDate = $CPublic->convTglDB($_POST['invoiceDate']);
	$dueDay = $_POST['dueDay'];
	$dueDate = $CPublic->convTglDB($_POST['dueDate']);
	$noInvoice = mysql_escape_string( $_POST['noInvoice'] );
	$amount = str_replace(",","",$_POST['amount']);
	$currency = $_POST['currency'];
	$remark = mysql_escape_string( $_POST['remark'] );
	$moreInvMail =  $_POST['moreInvMail'];  

	/*if($tipeSenderVendor == "1")
	{
		$senderVendorName = mysql_escape_string( $senderVendor );
		$senderVendorFieldDB = " sendervendor1='".$senderVendorName."', ";
		$senderVendorLog = "sendervendor1";
		$kreditacc = "";
	}
	elseif($tipeSenderVendor == "2")
	{
		$senderVendorName = $senderVendor;
		$senderVendor2Name = $CInvReg->detilSenderVendor($senderVendor, "AcctIndo");
		$senderVendorFieldDB = " sendervendor2='".$senderVendorName."', sendervendor2name='".$senderVendor2Name."',  ";
		$senderVendorLog = "sendervendor2";
		$kreditacc = $senderVendor;		
	}*/
	
	if($senderVendorCodePost == "")
	{
		$tipeSenVen = "1";
		$senderVendor1 = mysql_escape_string( $senderVendorPost );
		$senderVendor2 = "";
		$senderVendor2Name = "";
		$kreditacc = "";
	}
	if($senderVendorCodePost != "")
	{
		$tipeSenVen = "2";
		$senderVendor1 = "";
		$senderVendor2 = $senderVendorCodePost; //ACCOUNT CODE
		$senderVendor2Name = $senderVendorPost; // ACCOUNT NAME
		$kreditacc = $senderVendorCodePost;
	}
	
	//echo "TIPE SENVEN : ".$tipeSenVen." / SENDVEND1 : ".$senderVendor1." / SENDVEND2 : ".$senderVendor2Name." / ACCOUNTCODE : ".$senderVendor2." / KREDITACCOUNT : ".$kreditacc;

	$fieldUpdate = "";
	if($_FILES['fileUploadNya']['name'] != "")
    {
        $tmpFile = $_FILES['fileUploadNya']['tmp_name'];
        $fileName = $_FILES['fileUploadNya']['name'];
        $dir = "./fileUpload/";
        $newFileName = $barcode;

        unlink($dir."/".$_POST['txtFileOld']);//hapus file yang lama

        $dt = explode(".", $fileName);
        $newFileName = str_replace(array(' ','/','.',',','-'), '', $newFileName).".".trim($dt[count($dt)-1]);

        move_uploaded_file($tmpFile, $dir."/".$fileName);
        rename($dir."/".$fileName, $dir."/".$newFileName);

        $fileNameNya = $newFileName;
        $fieldUpdate = ", file_upload = '".$fileNameNya."'";
    }
	
	$CKoneksiInvReg->mysqlQuery("UPDATE mailinvoice SET tipesenven='".$tipeSenVen."', sendervendor1='".$senderVendor1."', sendervendor2='".$senderVendor2."',sendervendor2name='".$senderVendor2Name."', company='".$company."', companyname='".$companyName."', unit='".$unitt."', unitname='".$unitName."', barcode='".$barcode."', tglinvoice='".$invoiceDate."', dueday='".$dueDay."', tglexp='".$dueDate."', currency='".$currency."', amount='".$amount."', mailinvno='".$noInvoice."', remark='".$remark."', kreditacc='".$kreditacc."', kreditaccname='".$senderVendor2Name."', receivedate='".$receiveDate."', updusrdt='".$userWhoAct."'".$fieldUpdate." WHERE idmailinv=".$idMailInvGet." AND deletests=0 LIMIT 1;");
	
	$CHistory->updateLogInvReg($userIdLogin, "Simpan RUBAH Mail / Invoice (idmailinv=<b>".$idMailInvGet."</b>, urutan=<b></b>, batchno=<b>".$batchnoGet."</b>, tipesenven=<b>".$tipeSenVen."</b>, sendervendor1=<b>".$senderVendor1."</b>, sendervendor2=<b>".$senderVendor2."</b>, sendervendor2name=<b>".$senderVendor2Name."</b>, ".$senderVendorLog."=<b>".$senderVendorName."</b>, sendervendor2name=<b>".$senderVendor2Name."</b>, company=<b>".$company."</b>, companyname=<b>".$companyName."</b>, unit=<b>".$unitt."</b>, unitname=<b>".$unitName."</b>, barcode=<b>".$barcode."</b>, tglinvoice=<b>".$invoiceDate."</b>, tglexp=<b>".$dueDate."</b>, currency=<b>".$currency."</b>, amount=<b>".$amount."</b>, mailinvno=<b>".$noInvoice."</b>, remark=<b>".$remark."</b>, kreditacc=<b>".$kreditacc."</b>, receive date=<b>".$receiveDate."</b>, updusrdt=<b>".$userWhoAct."</b>)");	
			
	echo ("<body onLoad=\"doneWait();\">");
	$tutupWindow = "ya";
}

/*$senderVendor1  = "";
$senderVendor2  = "";
$tipeSenderVen = $CInvReg->detilMailInv($idMailInvGet, "tipesenven");

if($tipeSenderVen == "1")
	$senderVendor1 = $CInvReg->detilMailInv($idMailInvGet, "sendervendor1");
	
if($tipeSenderVen == "2")
	$senderVendor2 = $CInvReg->detilMailInv($idMailInvGet, "sendervendor2");*/
$receiveDate = $CPublic->convTglNonDB( $CInvReg->detilMailInv($idMailInvGet, "receivedate") );
$senderVendor = "";
$tipeSenderVen = $CInvReg->detilMailInv($idMailInvGet, "tipesenven");
if($tipeSenderVen == "1")
	$senderVendor = $CInvReg->detilMailInv($idMailInvGet, "sendervendor1");
	
if($tipeSenderVen == "2")
	$senderVendor = $CInvReg->detilMailInv($idMailInvGet, "sendervendor2name");

$senderVendorCode = $CInvReg->detilMailInv($idMailInvGet, "sendervendor2");

$company = $CInvReg->detilMailInv($idMailInvGet, "company");
$unitt = $CInvReg->detilMailInv($idMailInvGet, "unit");
$barcode = $CInvReg->detilMailInv($idMailInvGet, "barcode");
$invoiceDate = $CPublic->convTglNonDB( $CInvReg->detilMailInv($idMailInvGet, "tglinvoice") );
$dueDay = $CInvReg->detilMailInv($idMailInvGet, "dueday");

$dueDate = "";
if($CInvReg->detilMailInv($idMailInvGet, "tglexp") != "0000-00-00")
	$dueDate = $CPublic->convTglNonDB( $CInvReg->detilMailInv($idMailInvGet, "tglexp") );
	
$noInvoice = $CInvReg->detilMailInv($idMailInvGet, "mailinvno");

$amount = "";
if($CInvReg->detilMailInv($idMailInvGet, "amount") != "0.00")
	//$amount = $CInvReg->detilMailInv($idMailInvGet, "amount");
	$amount = $CPublic->jikaKosongStrip(number_format((float)$CInvReg->detilMailInv($idMailInvGet, "amount"), 2, '.', ','));
	
$currency  = $CInvReg->detilMailInv($idMailInvGet, "currency");
$remark = $CInvReg->detilMailInv($idMailInvGet, "remark");
$SNo = $CInvReg->detilMailInv($idMailInvGet, "urutan");
$fileNya = $CInvReg->detilMailInv($idMailInvGet, "file_upload");

$dis = "";
$bgColor = "";
if(substr($barcode, 0, 1) == "S")
{
	$dis = "disabled";
	$bgColor = "background-color:#E9E9E9;";
}

$dateNow = $CPublic->zerofill($CPublic->tglDayServer(),2)."/".$CPublic->zerofill($CPublic->bulanServer(),2)."/".$CPublic->tahunServer();
?>
<style>
body {background-color: #f9f9f9;}
</style>

<script>
	function viewImg(urlImg)
	{
		var myWindow = window.open("./fileUpload/"+urlImg, "_blank");
	}
window.onload = function()
{
	doneWait(); 
	<?php
	$sure = "Y";
	if($userJenisInvReg == "user")
	{	// TOMBOL SAVE DISABLED JIKA SUDAH ACKNOWLEDGE DAN AKSES EDIT TIDAK BOLEH
		if($CInvReg->detilMailInv($idMailInvGet, "ack") == 1 OR $CInvReg->aksesInvReg($userIdSession, "btnincoming_edit") == "disabled")
		{
			echo "disabledBtn('btnSave')";
			$sure = "N";
		}
	}
	?>
	
	setup();
}
</script>

<div id="loaderImg" style="visibility:hidden;" class="pleaseWait2">
    <div class="isiPleaseWait tabelBorderAll">&nbsp;&nbsp;Please Wait...&nbsp;<img src="../picture/loading (115).gif" height="12"/>&nbsp;</div>
</div>

<div id="idHaTambahMailInv">
    <table cellpadding="0" cellspacing="0" width="540" height="490" style="font:0.8em sans-serif;font-weight:bold;color:#485a88;" border="0" align="center">
    <form method="post" action="halUbahMailInv.php?batchno=<?php echo $batchnoGet; ?>&idMailInv=<?php echo $idMailInvGet; ?>" enctype="multipart/form-data" id="formTambahMailInv" name="formTambahMailInv">
    <!--<input type="hidden" id="tipeSenderVendor" name="tipeSenderVendor">
    <input type="hidden" id="senderVendor" name="senderVendor">-->
    <input type="hidden" id="allowBarcode" name="allowBarcode" value="<?php echo $barcode; ?>">
    <input type="hidden" id="aksi" name="aksi" value="simpanRubah">
    <input type="hidden" id="batchno" name="batchno" value="<?php echo $batchnoGet; ?>">
    <input type="hidden" id="idMailInv" value="<?php echo $idMailInvGet; ?>">
    <input type="hidden" id="dateNow" name="dateNow" value="<?php echo $dateNow; ?>">
    
    <div id="idCekAckYesNo" style="position:absolute;top:0px;left:0px;z-index:10;"><input type="hidden" id="ackYesNo" value="no" size="10"></div>
    <div id="idCekBarcodeSama"><input type="hidden" id="barcodeSamaAdaTidak" value="kosong"></div>
    
    <tr>
        <td valign="top" align="center" height="50" colspan="2"><span style="font-size:16px;">EDIT MAIL / INVOICE</span></td>
    </tr>
    <tr>
        <td height="400" valign="top">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" style="font:1em sans-serif;font-weight:bold;color:#485a88;">
            <tr valign="middle">
                <td height="22" class="">Batchno</td>
                <td class="elementTeks"><?php echo $batchnoGet; ?></td>
            </tr>
            <tr valign="top">
                <td height="22" class="">Receive Date</td>
                <td class="">
					 <span id="spanInvoiceDate"><input type="text" name="receiveDate" id="receiveDate" class="elementInput" style="width:60px;" value="<?php echo $receiveDate; ?>"/></span>
                     <img src="../../picture/calendar.gif" class="gayaKalender" title="Select Date" onclick="displayCalendar(document.getElementById('receiveDate'),'dd/mm/yyyy',this, '', '', '193', '183');"/>&nbsp;<span class="spanKalender">(DD/MM/YYYY)</span>
				</td>
            </tr>
            <tr valign="top">
                <td width="140" height="22" class="">Sender/Vendor</td>
                <td width="400" class="">
                	<!--<input type="text" class="elementInput" id="senderVendor" name="senderVendor" style="width:307px;" onkeypress="return changeToUpperCase(event,this);" onkeyup="maxChar(this, 200);" value="<?php echo $senderVendor; ?>">-->
                    <input type="text" class="elementInput" id="senderVendor" name="senderVendor" style="width:307px;" onkeypress="return" onkeyup="maxChar(this, 200);" value="<?php echo $senderVendor; ?>">
                    <input type="hidden" id="urutSendSelect" style="width:10px;text-align:right;" value="0">
                    <input type="hidden" class="elementMenu" id="senderVendorCode" name="senderVendorCode" style="width:100px;" value="<?php echo $senderVendorCode; ?>">
                    <div id="autoCompSender" class="overout" style="position:absolute;display:none;z-index:10;width:335px;height:300px;overflow:auto;border-color:#333;"></div>
			    	<!--<input class="elementInput" type="text" id="sendervendor1" name="sendervendor1" style="width:363px;" value="<?php echo $senderVendor1; ?>" onkeyup="maxChar(this, 200);">
                    <select id="sendervendor2" name="sendervendor2" class="elementMenu" style="width:375px;top:2px;position:relative;">
						<option value="00000"></option>
						<?php //echo $CInvReg->menuSenderVendor($senderVendor2); ?>
					</select>-->
                    <span id="senderVendorCode2"><?php echo $senderVendorCode; ?></span>
				</td>
            </tr>
            <tr valign="top">
                <td height="16" class="">Addresse</td>
            </tr>
			<tr valign="top">
                <td height="22" class="">&nbsp;&nbsp;&nbsp;Company</td>
                <td class="">
    			<select id="company" name="company" class="elementMenu" style="width:320px;">
					<option value="XXX"></option>
					<?php echo $CInvReg->menuCmp($company); ?>
				</select>
                </td>
            </tr>
			<tr valign="top">
                <td height="22" class="">&nbsp;&nbsp;&nbsp;Unit</td>
                <td class="">
        		<select id="unitt" name="unitt" class="elementMenu" style="width:320px;">
					<option value="XXX"></option>
					<?php echo $CInvReg->menuUnit($unitt); ?>
				</select>
                </td>
            </tr>
            <tr valign="top">
                <td height="22" class="">Barcode</td>
                <td class=""><input type="text" id="barcode" name="barcode" class="elementInput" maxlength="8" style="width:60px;" onkeyup="isiBarcode(this.value);" value="<?php echo $barcode; ?>"><span class="spanKalender">&nbsp;* Use A ( Invoice ) or S ( Mail )</span>
                </td>
            </tr>
            <tr valign="top">
                <td height="22" class="">Invoice / Letter Date</td>
                <td class="">
					 <span id="spanInvoiceDate"><input type="text" name="invoiceDate" id="invoiceDate" class="elementInput" style="width:60px;" value="<?php echo $invoiceDate; ?>"/></span>
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
                <td height="22" class="">Mail/Invoice No</td>
                <td class="">
                	<input type="text" id="noInvoice" name="noInvoice" class="elementInput" style="width:188px;" value="<?php echo $noInvoice; ?>">
                </td>
            </tr>
            <tr valign="top">
                <td height="22" class="">Amount</td>
                <td class="">
					<input type="text" id="amount" name="amount" class="elementInput" style="width:188px;text-align:right;<?php echo $bgColor; ?>" value="<?php echo $amount; ?>" <?php echo $dis; ?> onkeyup="hanyaAngkaAmount();return false;">
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
                <td height="22" class="">File</td>
                <td class="">
                    <input type="file" name="fileUploadNya" id="fileUploadNya" class="btnStandar" style="width:250px" title="File Upload">
                    <input type="hidden" name="txtFileOld" id="txtFileOld" value="<?php echo $fileNya; ?>">
                    &nbsp <a style="cursor:pointer;" onclick="$('#fileUploadNya').val('');"> Clear </a>
                    <?php if($fileNya != ""){ ?>
                    &nbsp <a style="cursor:pointer;" onclick="viewImg('<?php echo $fileNya; ?>');" id="btnViewFile"> View</a>
                    <?php } ?>
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
        <button class="btnStandar" onclick="formTambahMailInv.reset();">
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