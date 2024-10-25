<!DOCTYPE HTML>
<?php
require_once('../../config.php');
require_once('../configSpj.php');

$reportIdGet = $_GET['reportId'];
$trActive = $_GET['trActive'];
$aksiVal = "new";
$judul = "New";

$height = "height:260px;";
if($userJenisSpj == "admin")
{
	$height = "height:290px;";
}

$ownerId = $userIdLogin;
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../css/cssSpj.css" rel="stylesheet" type="text/css" />
<link href="../css/loading.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK>

<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>
<script src="../../js/JavaScriptUtil.js"></script>
<script src="../../js/Parsers.js"></script>
<script src="../../js/InputMask.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<SCRIPT type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="../js/loading.js"></script>
<script type="text/javascript" src="../js/limitText.js"></script>
<?php
if($aksiPost == "new")
{
	$createdBy = $_POST['createdBy'];
	$formId = $_POST['spjNo'];
	$idrDp = str_replace(",","",$_POST['idrDp']);
	$usdDp = str_replace(",","",$_POST['usdDp']);
	$sgdDp = str_replace(",","",$_POST['sgdDp']);
	$note = mysql_real_escape_string($_POST['note']); // Note
	$urutanAkhir = $CSpj->urutanAkhirReport();
	$urutan = $urutanAkhir + 1;
	
	$kdCmp = $CEmployee->detilCompany($CEmployee->detilEmp($userEmpNo, "kdcmp"), "kdcmp");
	$jabatan = $CEmployee->detilJabatan($CEmployee->detilTblEmpGen($userEmpNo, "kdjabatan"), "nmjabatan"); // jabatan
	$golongan = $CEmployee->detilPangkat($CEmployee->detilTblEmpGen($userEmpNo, "kdpangkat"), "nmpangkat") ; // golongan
	$ownerId = $CSpj->detilForm($formId, "ownerid");
	$aproverId = $CSpj->detilLoginSpjByEmpno($CSpj->detilForm($formId, "kadivempno"), "userid", $db);
	
	//echo $formId." | ".$note." | ".$CPublic->tglServer();
	
	//insert Database
	$CKoneksiSpj->mysqlQuery("INSERT INTO report (formId, urutan, ownerid, kadivid, createdby, idrdp, usddp, sgddp, note, addusrdt) VALUES (".$formId.", ".$urutan.", ".$ownerId.", ".$aproverId.", ".$createdBy.", '".$idrDp."', '".$usdDp."', '".$sgdDp."', '".$note."', '".$CPublic->userWhoAct()."');");
	$lastInsertId = mysql_insert_id();
	
	//insert history
	$CHistory->updateLogSpj($userIdLogin, "Membuat Draft Report SPJ (reportid = <b>".$lastInsertId."</b>, SPJ No. = <b>".$CSpj->detilForm($formId, "spjno")."</b>)");
}

if($aksiPost == "edit")
{
	$createdBy = $_POST['createdBy'];
	$reportId = $_POST['reportId'];
	$formId = str_replace(",","",$_POST['spjNo']);
	$ownerId = $CSpj->detilForm($formId, "ownerid");
	$aproverId = $CSpj->detilLoginSpjByEmpno($CSpj->detilForm($formId, "kadivempno"), "userid", $db);
	$idrDp= str_replace(",","",$_POST['idrDp']);
	$usdDp = str_replace(",","",$_POST['usdDp']);
	$sgdDp = str_replace(",","",$_POST['sgdDp']);
	$note = mysql_real_escape_string($_POST['note']); // Note
	$trActive = $_POST['trActive'];
	
	//echo $formId." | ".$note." | ".$reportId;
	$idrGrandTotal = 0;
	if($CSpj->grandTotal("idrtotal", $reportId) != "")
	{
		$idrGrandTotal = $CSpj->grandTotal("idrtotal", $reportId);
	
	}
	$usdGrandTotal = 0;
	if($CSpj->grandTotal("usdtotal", $reportId) != "")
	{
		$usdGrandTotal = $CSpj->grandTotal("usdtotal", $reportId);
	}
	$sgdGrandTotal = 0;
	// if($CSpj->grandTotal("usdtotal", $reportId) != "")
	// {
	// 	$sgdGrandTotal = $CSpj->grandTotal("usdtotal", $reportId);
	// }
	// uang kembali count & update
	if($idrDp == "")
	{
		$idrDp = 0;
	}
	$idrKembali = $idrDp - $idrGrandTotal;
	if($usdDp == "")
	{
		$usdDp = 0;
	}
	$usdKembali = $usdDp - $usdGrandTotal;
	if($sgdDp == "")
	{
		$sgdDp = 0;
	}
	$sgdKembali = $sgdDp - $sgdGrandTotal;
	//echo $idrKembali." ".$usdKembali;
	//insert Database
	$CKoneksiSpj->mysqlQuery("UPDATE report set formId = ".$formId.", ownerid = ".$ownerId.", kadivid = ".$aproverId.", createdby = ".$createdBy.", idrdp = '".$idrDp."', usddp = '".$usdDp."', sgddp = '".$sgdDp."', idrtotalkembali = ".$idrKembali.", usdtotalkembali = ".$usdKembali.", sgdtotalkembali = ".$sgdKembali.", note = '".$note."', updusrdt = '".$CPublic->userWhoAct()."' WHERE reportid = ".$reportId." AND deletests = 0;");
	
	//insert history
	$CHistory->updateLogSpj($userIdLogin, "Edit Draft Report SPJ (reportid = <b>".$reportId."</b>, SPJ No. = <b>".$CSpj->detilForm($formId, "spjno")."</b>)");
}

if($halamanGet == "edit")
{
	$judul = "Edit";
	
	$formIdGet = $CSpj->detilReport($reportIdGet, "formid");
	$ownerId = $CSpj->detilReport($reportIdGet, "ownerid");
	$noteGet = $CSpj->detilReport($reportIdGet, "note");
	$idrGet = "";
	if($CSpj->detilReport($reportIdGet, "idrdp") != "")
	{
		$idrGet = number_format($CSpj->detilReport($reportIdGet, "idrdp"));
	}
	$usdGet = "";
	if($CSpj->detilReport($reportIdGet, "usddp") != "")
	{
		$usdGet = number_format($CSpj->detilReport($reportIdGet, "usddp"));
	}
	$sgdGet = "";
	if($CSpj->detilReport($reportIdGet, "sgddp") != "")
	{
		$sgdGet = number_format($CSpj->detilReport($reportIdGet, "sgddp"));
	}
	$aksiVal = $halamanGet ;

}
?>
<script>
window.onload = function()
{
	doneWait();
	
	setTimeout(function()
	{
		var noteLength = $("#note").val().length;
		sisaLimit(noteLength, 'countdown', '400');
	},50);
}
function submitReport()
{
	var aksi = formReport.aksi.value;
	
	var ownerId = formReport.ownerId.value;
	var spjNo = formReport.spjNo.value;
	var idrDp = formReport.idrDp.value;
	var usdDp = formReport.usdDp.value;
	var sgdDp = formReport.sgdDp.value;
	var note = formReport.note.value;
	
	var img = '<img src=\"../../picture/exclamation-red.png\" width=\"14\"/>&nbsp;';
 
	if(ownerId.replace(/ /g,"") == "")
	{
		document.getElementById('errorMsg').innerHTML = img+ "Employee still empty";
		document.getElementById('ownerId').focus();
		return false;
	}
	if(spjNo.replace(/ /g,"") == "")
	{
		document.getElementById('errorMsg').innerHTML = img+ "SPJ Number still empty";
		document.getElementById('spjNo').focus();
		return false;
	}
	if(idrDp.replace(/ /g,"") == "" && usdDp.replace(/ /g,"") == "") // DP tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = img+ "Down Payment still empty";
		document.getElementById('spjNo').focus();
		return false;
	}
	
	var answer  = confirm("Are you sure want to save?");
	if(answer)
	{
		pleaseWait();
		formReport.submit();
	}
	else
	{	return false;	}
}

function setup()
{
	var decimalSeparator = ".";
    var groupSeparator = ",";
		
	var numParser1 = new NumberParser(2, decimalSeparator, groupSeparator, true);
    var numMask1 = new NumberMask(numParser1, "jmlFol");
}

function ajaxFol(jml)
{
	$.post( 
		"../halPost.php",
		{	halaman: 'jmlFol', jml: jml	},
		function(data){
			$('#divFol').html(data);	
		}
	);
}

function setup(id)
{
	var decimalSeparator = ".";
    var groupSeparator = ",";
		
	var numParser1 = new NumberParser(2, decimalSeparator, groupSeparator, true);
    var numMask1 = new NumberMask(numParser1, id, 9);
}

function ajaxSpjNo(ownerId)
{
	$.post( 
		"../halPost.php",
		{	halaman: 'spjNo', ownerId: ownerId	},
		function(data){
			$('#tdSpj').html(data);	
		}
	);
}

setInterval(function(){ $('blink').each( function(){ $(this).css('visibility' , $(this).css('visibility') === 'hidden' ? '' : 'hidden') } ); }, 400);
</script>
<body bgcolor="#F8F8F8">
<div id="loaderImg" style="visibility:visible;width:500px;" class="pleaseWait2">
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
    	<span class="teksLvlFolder" style="color:#999;font-size:16px;font-style:italic;"><b>:: <?php echo $judul;?> Report ::</b></span>
    </td>
</tr>

<tr valign="top">
	<td colspan="2" class="spjTdMyFolder" bgcolor="#FFFFFF" valign="top" align="center">
    <div style="width:99%;<?php echo $height;?>overflow-x: hidden;top: expression(offsetParent.scrollTop);">
    <form action="" name="formReport" id="formReport" method="post" enctype="multipart/form-data">
    <table cellpadding="0" cellspacing="5" width="98%" class="spjFormInput" border="0">
                <tr><td height="5" colspan="3"></td></tr>
                <?php
					if($userJenisSpj == "admin")
					{
				?>
                <tr width="3%" valign="top">
                    <td height="28px" width="20%" align="left" valign="middle" title="Nama Karyawan">Employee</td>
                    <td align="left" colspan="2">
                    	<select id="ownerId" name="ownerId" class="elementMenu" style="width:102%;" title="Related SPJ" onChange="ajaxSpjNo(this.value);">
                        	<option value="">-- PLEASE SELECT --</option>
						<?php
                        	$queryEmp = $CKoneksiSpj->mysqlQuery("SELECT userid FROM msuser");
							while($rowEmp = $CKoneksiSpj->mysqlFetch($queryEmp))
							{
								$sel1 = '';
								if($rowEmp['userid'] == $ownerId)
								{
									$sel1 = 'selected="selected"';
								}
								$menuEmp.= '<option value="'.$rowEmp['userid'].'" '.$sel1.'>'.$CSpj->detilLoginSpj($rowEmp['userid'], "userfullnm", $db).'</option>';
							}
							echo $menuEmp;
							echo '<input type="hidden" id="createdBy" name="createdBy" value="'.$userIdLogin.'"/>';
                        ?>
                        </select>
                    </td>
                </tr>
                <?php 
					} 
					if($userJenisSpj != "admin")
					{
						$html = '<input type="hidden" id="ownerId" name="ownerId" value="'.$userIdLogin.'"/>
								<input type="hidden" id="createdBy" name="createdBy" value="00000"/>';
					}
					echo $html;
				?>
                <tr width="3%" valign="top">
                    <td height="28px" width="20%" align="left" valign="middle" title="Tanggal dinas">SPJ No.</td>
                    <td align="left" colspan="2" id="tdSpj">
                    	<select id="spjNo" name="spjNo" class="elementMenu" style="width:102%;" title="Related SPJ">
                        	<option value="">-- SELECT SPJ NUMBER --</option>
						<?php
							if($halamanGet == "edit")
							{
								$query = $CKoneksiSpj->mysqlQuery("SELECT * FROM form WHERE status = 'Completed' AND ownerid = ".$ownerId." AND deletests = 0 ORDER BY formnum DESC;");
							}else{
								$query = $CKoneksiSpj->mysqlQuery("SELECT a.formid,a.spjno FROM form a LEFT JOIN report b ON a.formid = b.formid WHERE a.ownerid = '".$ownerId."' AND a.STATUS =  'Completed' AND a.deletests = '0' AND b.formid is null ORDER BY a.formnum DESC");
							}
							while($row = $CKoneksiSpj->mysqlFetch($query))
							{
								$sel = "";
								if($formIdGet == $row['formid'])
								{
									$sel = "selected=\"selected\"";
								}
								$menuSpj.= '<option value="'.$row['formid'].'" '.$sel.'>'.$row['spjno'].'</option>';
							}
							echo $menuSpj;
                        ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                	<td height="28px" align="left" title="Uang muka Dinas">Advance</td>
                    <td width="5%">IDR</td>
                    <td width="75%" align="left"><input type="text" id="idrDp" name="idrDp" onFocus="setup('idrDp');" onKeyUp="setup('idrDp');" class="elementDefault" style="width:50%;text-align:right;" value="<?php echo $idrGet;?>"/></td>
                </tr>
                <tr valign="top">
                	<td height="28px" align="left" title="Uang muka Dinas"></td>
                    <td>USD</td>
                    <td align="left">
                    	<input type="text" id="usdDp" name="usdDp" onFocus="setup('usdDp');" onKeyUp="setup('usdDp');" class="elementDefault" style="width:50%;text-align:right;" value="<?php echo $usdGet;?>"/>
                    </td>
                </tr>
                <tr valign="top">
                	<td height="28px" align="left" title="Uang muka Dinas"></td>
                    <td>SGD</td>
                    <td align="left">
                    	<input type="text" id="sgdDp" name="sgdDp" onFocus="setup('sgdDp');" onKeyUp="setup('sgdDp');" class="elementDefault" style="width:50%;text-align:right;" value="<?php echo $sgdGet;?>"/>
                    </td>
                </tr>
                <tr valign="top">
                	<td height="28px" align="left" title="Catatan">Note</td>
                    <td colspan="2" align="left">
                    	<textarea id="note" name="note" onKeyDown="limitText(this.form.note,this.form.countdown,400);" 
onKeyUp="limitText(this.form.note,this.form.countdown,400);" class="elementDefault" style="width:99%;height:98px;resize:" title="Catatan"><?php echo $noteGet;?></textarea>
                        <br/><span style="font-size:11px;margin-top:10px;"><input readonly type="text" style="width:6%" id="countdown" name="countdown" value="400"> characters left.</span> 
                    </td>
                </tr>
                <tr>
                	<td>
                    	<input type="hidden" id="aksi" name="aksi" value="<?php echo $aksiVal;?>"/>
                       	<input type="hidden" id="reportId" name="reportId" value="<?php echo $reportIdGet;?>"/>
                        <input type="hidden" id="trActive" name="trActive" value="<?php echo $trActive;?>"/>
                    </td>
                	<td align="left" valign="middle"></td>
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
       &nbsp;<button class="spjBtnStandar" id="btnNewFolder" onclick="parent.close();" style="width:72px;height:25px;" title="Close Window">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                <tr>
                    <td align="right" width="17"><img src="../picture/control-power.png" height="15"/> </td>
                    <td align="center">CLOSE</td>
                </tr>
                </table>
            </button>
       &nbsp;<button class="spjBtnStandar" id="btnNewFolder" onclick="submitReport(); return false;" style="width:68px;height:25px;" title="Save as draft form SPJ">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                <tr>
                    <td align="right" width="17"><img src="../picture/disk.png" height="15"/> </td>
                    <td align="center">SAVE </td>
                </tr>
                </table>
            </button>
    </td>
</tr>
</table>

</center>
</body>
<script language="javascript">
<?php
if($aksiPost == "new" || $aksiPost == "edit")
{
	$tipeRpt = "";
	if($aksiPost == "new")
	{
		$tipeRpt = "Your report succesfully Save";
		$tr = "1";
	}
	if($aksiPost == "edit")
	{
		$tipeRpt = "Your report succesfully update";
		$tr = $trActive;
	}

	echo "parent.exit();
		  parent.report('".$tipeRpt."');
		  parent.refresh('Y','Y');
		  parent.klikTr('".$tr."');
		  pleaseWait();";
}
?>
</script>
</HTML>	