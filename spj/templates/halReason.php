<?php 
	require_once('../../config.php');
	require_once('../configSpj.php'); 
?>
<!DOCTYPE HTML>

<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../css/cssSpj.css" rel="stylesheet" type="text/css" />
<link href="../css/loading.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>
<script type="text/javascript" src="../js/loading.js"></script>

<?php

if($aksiGet == "revForm" || $aksiGet == "revFormAck" || $aksiGet == "reviseComplete")
{
	$title = "Revise SPJ Form";
}
if($aksiGet == "cancelForm")
{
	$title = "Deny SPJ Form";
}
if($aksiGet == "revReport" || $aksiGet == "revReportCek" || $aksiGet == "revReportPrcs")
{
	$title = "Revise SPJ Report";
}

if($aksiPost == "revForm" || $aksiPost == "revFormAck" || $aksiPost == "cancelForm" || $aksiPost == "reviseComplete")
{
	$formId = $_POST['formId'];
	$reason = mysql_real_escape_string($_POST['reason']);
	$ownerName = $CSpj->detilForm($formId, "ownername");
	$ownerId = $CSpj->detilForm($formId, "ownerid");
	
	$aproverId = $CSpj->detilLoginSpjByEmpno($CSpj->detilForm($formId, "kadivempno"), "userid", $db);
	
	if($aksiPost == "revForm" || $aksiPost == "revFormAck")
	{
		$CKoneksiSpj->mysqlQuery("UPDATE form SET status = 'Revise', knowempno = '00000', updusrdtack = '', reason = '".$reason."', reasonempno = ".$userEmpNo.", updusrdt = '".$CPublic->userWhoAct()."' WHERE formid = ".$formId." AND deletests = 0;");
		$CHistory->updateLogSpj($userIdLogin, "Meminta revisi Form SPJ (formid = <b>".$formId."</b>, ownerform = <b>".$ownerName."</b>)");
		$notes = "Your SPJ form is need revise to keep process.";
	}
	
	if($aksiPost == "cancelForm")
	{
		$CKoneksiSpj->mysqlQuery("UPDATE form SET status = 'Cancel', reason = '".$reason."', reasonempno = ".$userEmpNo.", updusrdt = '".$CPublic->userWhoAct()."' WHERE formid = ".$formId." AND deletests = 0;");
		$CHistory->updateLogSpj($userIdLogin, "Menolak Form SPJ (formid = <b>".$formId."</b>, ownerform = <b>".$ownerName."</b>)");
		$notes = "Your SPJ Form has been Canceled. SPJ Form Request process has been stop.";
	}

	if ($aksiPost == "reviseComplete")
	{
		$sqlCheck = "SELECT * FROM report WHERE formid = '".$formId."'";
		$query = $CKoneksiSpj->mysqlQuery($sqlCheck);
		$countNya = $CKoneksiSpj->mysqlNRows($query);

		if ($countNya == "0")
		{
			$CKoneksiSpj->mysqlQuery("UPDATE form SET status = 'Draft', spjno_old = spjno, spjno = '', reason = '".$reason."', reasonempno = '".$userEmpNo."', updusrdt = '".$CPublic->userWhoAct()."' WHERE formid = '".$formId."' AND deletests = 0;");
			$CHistory->updateLogSpj($userIdLogin, "Meminta revisi Form SPJ (formid = <b>".$formId."</b>, ownerform = <b>".$ownerName."</b>)");
			$notes = "Your SPJ form is need to be revised to keep process.";
		}
			
	}

	
	//notif email ke owner form
	$CSpj->emailKeOwner("emailRevForm", $formId, $CPublic, $CEmployee, $CNotifSpj, $db, $link, $aksiPost);
	//notif desktop ke owner form
	$ownerEmpNo = $CSpj->detilForm($formId, "ownerempno");
	$CSpj->desktopKeOwner($ownerEmpNo, $koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notes, $db);
	
	if($aksiPost == "revFormAck")
	{
		//notif email ke kadiv/atasan owner form
		$CSpj->emailKeAtasan("emailRevForm", $aproverId, $ownerEmpNo, $formId, $CPublic, $CEmployee, $CNotifSpj, $db, $link, "atasan");
		//notif desktop ke kadiv/atasan owner form
		$notes = $ownerName."''s SPJ Form is need revise to keep process.";
		$CSpj->desktopKeAtasan($ownerId, $aproverId, $koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notes, $db);
	}
}

if($aksiPost == "revReport" || $aksiPost == "revReportCek" || $aksiPost == "revReportPrcs")
{
	$reportId = $_POST['reportId'];
	$reason = mysql_real_escape_string($_POST['reason']);
	$ownerName = $CSpj->detilForm($CSpj->detilReport($reportId, "formid"), "ownername");
	
	//buat salinan data revisi =================================
	$CSpj->salinReport($reportId, $userEmpNo, $CPublic, $reason);
	// ==========================================================
	
	$CKoneksiSpj->mysqlQuery("UPDATE report SET status = 'Revise', reason = '".$reason."', reasonempno = ".$userEmpNo.", updusrdt = '".$CPublic->userWhoAct()."' WHERE reportid = ".$reportId." AND deletests = 0;");
	$CHistory->updateLogSpj($userIdLogin, "Meminta revisi Report SPJ (reportid = <b>".$reportId."</b>, ownerreport = <b>".$ownerName."</b>)");
	
	//notif email ke owner form
	$CSpj->emailKeOwner("emailRevReport", $reportId, $CPublic, $CEmployee, $CNotifSpj, $db, $link, "revReport");
	//notif desktop ke owner form
	$ownerEmpNo = $CSpj->detilForm($CSpj->detilReport($reportId, "formid"), "ownerempno");
	$notes = "Your SPJ Report is need revise to keep process.";
	$CSpj->desktopKeOwner($ownerEmpNo, $koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notes, $db);
	
	$ownerId = $CSpj->detilForm($CSpj->detilReport($reportId, "formid"), "ownerid");
	$aproverId = $CSpj->detilLoginSpjByEmpno($CSpj->detilForm($CSpj->detilReport($reportId, "formid"), "kadivempno"), "userid", $db);
	if($aksiPost == "revReportCek" || $aksiPost == "revReportPrcs")
	{
		echo "yes";
		//notif email ke kadiv/atasan owner form
		$CSpj->emailKeAtasan("emailRevReport", $aproverId, $ownerEmpNo, $reportId, $CPublic, $CEmployee, $CNotifSpj, $db, $link, $aksiPost);
		//notif desktop ke kadiv/atasan owner form
		$notes = $ownerName."''s SPJ Report is need revise to keep process.";
		$CSpj->desktopKeAtasan($ownerId, $aproverId, $koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notes, $db);
	}
	
	if($aksiPost == "revReportPrcs")
	{
		//notif email ke kadiv HR
		/*$CSpj->emailKeKadivHR("emailRevReport", $reportId, $CPublic, $CEmployee, $CNotifSpj, $db, $link, $aksiPost);
		//notif desktop ke kadiv HR
		$notes = $ownerName."''s SPJ Report is need revise to keep process.";
		$CSpj->desktopKeKadivHR($koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notes, $db);*/
	}
}
?>

<script>
window.onload = function()
{
	doneWait();
}

function submitReason()
{
	var aksi = formReason.aksi.value;
	var reason = formReason.reason.value;
	
	var img = '<img src=\"../../picture/exclamation-red.png\" width=\"14\"/>&nbsp;';
	
	if(reason.replace(/ /g,"") == "") // reason tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = img+ "Reason still empty";
		document.getElementById('reason').focus();
		return false;
	}
	
	var answer  = confirm("Are you sure want to Submit Reason?");
	if(answer)
	{
		pleaseWait();
		formReason.submit();
	}
	else
	{	return false;	}
}


setInterval(function(){ $('blink').each( function(){ $(this).css('visibility' , $(this).css('visibility') === 'hidden' ? '' : 'hidden') } ); }, 400);
</script>

<body bgcolor="#F8F8F8">
<div id="loaderImg" style="visibility:visible;width:450px;" class="pleaseWait2">
    <div class="isiPleaseWait tabelBorderAll">
    	&nbsp;Please Wait...&nbsp;<img src="../picture/loading (115).gif" height="12"/>&nbsp;
    </div>
</div>
<center>

<table cellpadding="0" cellspacing="0" border="0" width="99%" height="99%" align="center">

<tr valign="top" style="width:100%;">
	<td align="left">
    	<!--<span class="teksLvlFolder" style="color:#666;font-size:14px;"><b></b></span>-->
    </td>
	<td align="right">
    	<span class="teksLvlFolder" style="color:#999;font-size:16px;font-style:italic;"><b>:: <?php echo $title;?> ::</b></span>
    </td>
</tr>

<tr valign="top">
	<td colspan="2" class="spjTdMyFolder" bgcolor="#FFFFFF" valign="top" align="center">
        <div style="width:99%;height:143px;top: expression(offsetParent.scrollTop);">
        <form action="" name="formReason" id="formReason" method="post" enctype="multipart/form-data">
            <table cellpadding="0" cellspacing="5" width="98%" class="spjFormInput" border="0">
                <tr>
                    <td width="12%" align="left" valign="top">Reason</td>
                    <td width="88%" align="left">
                        <textarea maxlength="999" class="elementDefault" id="reason" name="reason" style="width:98%;height:120px;resize:none;"></textarea>
                        <input type="hidden" id="formId" name="formId" value="<?php echo $_GET['formId'];?>"/>
                        <input type="hidden" id="reportId" name="reportId" value="<?php echo $_GET['reportId'];?>"/>
                        <input type="hidden" id="aksi" name="aksi" value="<?php echo $aksiGet;?>"/>
                    </td>
                </tr>            
            </table>
        </form>
        </div>
    </td>
</tr>

<tr valign="top">
	<td colspan="2" bgcolor="#FFFFFF" valign="middle" align="center">
    	<blink><span id="errorMsg" class="errorMsg"></span></blink>
    </td>
</tr>

<tr valign="top">
	<td colspan="2" bgcolor="#FFFFFF" height="35" valign="middle" align="center">
       &nbsp;<button type="button" class="spjBtnStandar" onclick="parent.close(); return false;" style="width:72px;height:25px;" title="Close Window">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                <tr>
                    <td align="right" width="17"><img src="../picture/control-power.png" height="15"/> </td>
                    <td align="center">CLOSE</td>
                </tr>
                </table>
			</button>
		&nbsp;<button type="submit" class="spjBtnStandar" onclick="submitReason(); return false;" style="width:83px;height:25px;" title="Submit Form SPJ">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                <tr>
                    <td align="right" width="17"><img src="../picture/arrow-transition.png" height="15"/> </td>
                    <td align="center">SUBMIT</td>
                </tr>
                </table>
            </button>&nbsp;
    </td>
</tr>

</table>

</center>
</body>
<script language="javascript">
<?php
if($aksiPost == "revForm" || $aksiPost == "revFormAck" || $aksiPost == "cancelForm" || $aksiPost == "revReport" || $aksiPost == "revReportCek" || $aksiPost == "revReportPrcs")
{
	echo "parent.aksiReason('".$aksiPost."');";
}
?>
</script>
</HTML>