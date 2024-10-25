<!DOCTYPE HTML>
<?php
require_once('../../config.php');
require_once('../configSpj.php');

$height = "height:367px;";
if($userJenisSpj == "admin")
{
	$height = "height:397px;";
}

$formIdGet = $_GET['formId'];
$trActiveGet = $_GET['trActive'];

//get database
$fromDateDb = $CSpj->detilForm($formIdGet, "datefrom");
$tgl =  substr($fromDateDb,6,2);
$bln =  substr($fromDateDb,4,2);
$thn =  substr($fromDateDb,0,4);
$fromDateDbDisp = $tgl."/".$bln."/".$thn;

$toDateDb = $CSpj->detilForm($formIdGet, "dateto");
$tglTo =  substr($toDateDb,6,2);
$blnTo =  substr($toDateDb,4,2);
$thnTo =  substr($toDateDb,0,4);
$toDateDbDisp = $tglTo."/".$blnTo."/".$thnTo;

$kdCmp = $CSpj->detilForm($formIdGet, "kdcmp");
$destDb = $CSpj->detilForm($formIdGet, "destination");
$necDb = $CSpj->detilForm($formIdGet, "necessary");
$vehDb = $CSpj->detilForm($formIdGet, "vehicle");
$noteDb = $CSpj->detilForm($formIdGet, "note");
/*$destDb = $CSpj->detilForm($formIdGet, "destination");
$destDb = $CSpj->detilForm($formIdGet, "destination");*/

$jmlFolDb = $CSpj->jmlFollower($formIdGet, "folid");

$slcCurr = $CSpj->detilForm($formIdGet, "currency");
$cashAdv = number_format($CSpj->detilForm($formIdGet, "cashadvance"),2);
$vslCode = $CSpj->detilForm($formIdGet, "vessel_code");
$vslName = $CSpj->detilForm($formIdGet, "vessel_name");

// END OF ==== get database
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../css/cssSpj.css" rel="stylesheet" type="text/css" />
<link href="../css/loading.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK>

<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>
<script type="text/javascript" src="../js/jquery.alphanum.js"></script>
<script src="../../js/JavaScriptUtil.js"></script>
<script src="../../js/Parsers.js"></script>
<script src="../../js/InputMask.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<SCRIPT type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="../js/loading.js"></script>
<script type="text/javascript" src="../js/limitText.js"></script>
<?php
if($aksiPost == "edit")
{
	/*echo "<pre>";
	print_r($_POST);
	echo "</pre>";*/
	
	$tipe = $_POST['tipe'];// draft atau submit(Wait)
	$tipeHist = "";
	$updateUrutan = "";
	
	$status = $CSpj->detilForm($formIdGet, "status");
	
	if($tipe == "Draft")
	{
		$tipeHist = "Save as draft";
		$statusUpdate = "status = '".$tipe."',";
	}
	if($tipe == "Draft" && $status == "Revise")
	{
		$tipeHist = "Save";
		$statusUpdate = "";
	}
	if($tipe == "Wait")
	{
		$tipeHist = "Submit";
		$statusUpdate = "aprvempno = 00000, aprvbyadm = 'N', status = '".$tipe."',";
		
		$urutanAkhir = $CSpj->urutanAkhir();
		$urutan = $urutanAkhir + 1;
		$updateUrutan = "urutan = ".$urutan.",";
	}
	
	$from = $_POST['fromDate'];	
	$tgl =  substr($from,0,2);
	$bln =  substr($from,3,2);
	$thn =  substr($from,6,4);
	$fromDate = $thn.$bln.$tgl; //from Date
	
	$to = $_POST['toDate'];	
	$tglTo =  substr($to,0,2);
	$blnTo =  substr($to,3,2);
	$thnTo =  substr($to,6,4);
	$toDate = $thnTo.$blnTo.$tglTo; //to Date
	
	$kdCmp = $_POST['kdCmp']; // Company
	$dest = mysql_real_escape_string($_POST['dest']); // Destination
	$neces = mysql_real_escape_string($_POST['neces']); // Necessery
	$veh = mysql_real_escape_string($_POST['veh']); // Vehicle
	$note = mysql_real_escape_string($_POST['note']); // Note
	$jmlFol = $_POST['jmlFol']; // jumlah Follower
	
	$statusBefore = $CSpj->detilForm($formIdGet, "status");

	$slcCurr = $_POST['slcCurr'];
	$cashAdv= str_replace(",","",$_POST['txtAmount']);
	$vslCode = $_POST['slcVsl'];
	$vslName = $_POST['txtVesselNameHid'];
	
	//echo $tipeHist." | ".$fromDate." | ".$toDate." | ".$jabatan." | ".$golongan." | ".$userEmpNo." | ".$userFullnm." | ".$dest." | ".$neces." | ".$veh." | ".$jmlFol." | ".$note;
	
	if($userJenisSpj != "admin")//jika yang input bukan admin
	{
		$employeeId = $userIdLogin;
		$employeeNo = $userEmpNo;
		$employeeName = $userFullnm;
		$aproverEmpNo = $CSpj->detilLoginSpj($CSpj->cariAproverId($userIdLogin, $CEmployee, $db), "empno", $db);
		
		//update Database	
		$CKoneksiSpj->mysqlQuery("UPDATE form SET ".$updateUrutan." kdcmp = ".$kdCmp.", datefrom = ".$fromDate.", dateto = ".$toDate.", destination = '".$dest."', necessary = '".$neces."', vehicle = '".$veh."', note = '".$note."', ".$statusUpdate." updusrdt = '".$CPublic->userWhoAct()."', currency = '".$slcCurr."', cashadvance = '".$cashAdv."', vessel_code = '".$vslCode."', vessel_name = '".$vslName."' WHERE formid = ".$formIdGet.";");
		
		$aproverId = $CSpj->detilLoginSpjByEmpno($CSpj->detilForm($formIdGet, "kadivempno"), "userid", $db);
	}
	if($userJenisSpj == "admin")//jika yang input adalah admin
	{
		$employeeId = $_POST['employeeId'];
		$employeeNo = $CSpj->detilLoginSpj($employeeId, "empno", $db);
		$employeeName = $CSpj->detilLoginSpj($employeeId, "userfullnm", $db);
		
		$jabatan = $CEmployee->detilJabatan($CEmployee->detilTblEmpGen($employeeNo, "kdjabatan"), "nmjabatan"); // jabatan
		$golongan = $CEmployee->detilPangkat($CEmployee->detilTblEmpGen($employeeNo, "kdpangkat"), "nmpangkat") ; // golongan
		$aproverEmpNo = $CSpj->detilLoginSpj($CSpj->cariAproverId($employeeId, $CEmployee, $db), "empno", $db);
		
		//update Database	
		$CKoneksiSpj->mysqlQuery("UPDATE form SET ".$updateUrutan." kdcmp = ".$kdCmp.", ownerid = ".$employeeId.", ownerempno = ".$employeeNo.", kadivempno = ".$aproverEmpNo.", ownername = '".$employeeName."', jabatan = '".$jabatan."', golongan = '".$golongan."', datefrom = ".$fromDate.", dateto = ".$toDate.", destination = '".$dest."', necessary = '".$neces."', vehicle = '".$veh."', note = '".$note."', ".$statusUpdate." updusrdt = '".$CPublic->userWhoAct()."', currency = '".$slcCurr."', cashadvance = '".$cashAdv."', vessel_code = '".$vslCode."', vessel_name = '".$vslName."' WHERE formid = ".$formIdGet.";");
		
		$aproverId = $CSpj->cariAproverId($employeeId, $CEmployee, $db);
	}
	
	//delete old follower
	$CKoneksiSpj->mysqlQuery("DELETE FROM follower WHERE formid = ".$formIdGet.";");
	
	if($jmlFol != 0)
	{
		$i;
		for($i=1;$i<=$jmlFol;$i++)
		{
			//insert new follower
			$CKoneksiSpj->mysqlQuery("INSERT INTO follower (formid, followerid) VALUES (".$formIdGet.", ".$_POST['foll'.$i].");");
		}
	}
	
	//$aproverId = $CSpj->detilLoginSpjByEmpno($CSpj->detilForm($formIdGet, "kadivempno"), "userid", $db);
	//Notifikasi
	if($tipe == "Wait")
	{
		if($statusBefore != "Revise")
		{
			$ket = "";
			$notes = $employeeName." has submitted SPJ Request Form. It requires your Approval.";
		}
		if($statusBefore == "Revise")
		{
			$ket = "rev";
			$notes = $employeeName." has revised current SPJ Request Form. It requires your Approval.";
		}
		
		if($employeeId != $ceoId)// Jika owner form bukan CEO
		{
			$CSpj->emailKeAtasan("emailNewForm", $aproverId, $employeeNo, $formIdGet, $CPublic, $CEmployee, $CNotifSpj, $db, $link, $ket);//Notifikasi Email
			
			$CSpj->desktopKeAtasan($employeeId, $aproverId, $koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notes, $db);//Notifikasi Desktop
		}
		
		if($employeeId == $ceoId)// Kondisi khusus bagi CEO (lgsg ke proses Acknowledge)
		{
			
			$CKoneksiSpj->mysqlQuery("UPDATE form SET status = 'Approved', aprvempno = ".$employeeNo." WHERE formid = ".$formIdGet.";");
			
			//Notifikasi Email
			$CSpj->emailKeKadivHR("emailAprvForm", $formIdGet, $CPublic, $CEmployee, $CNotifSpj, $db, $link, "kadivHr");//Kadiv HR
			//Notifikasi Desktop
			$notesKadiv = $employeeName."''s SPJ Request Form has been approved. It requires your Acknowledgement to proccess it.";
			$CSpj->desktopKeKadivHR($koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notesKadiv, $db);//kadiv HR
		}
	}
	
	//insert history
	$CHistory->updateLogSpj($userIdLogin, "Update dan ".$tipeHist." Form SPJ (formid = <b>".$formIdGet."</b>, tujuan dinas = <b>".$dest."</b>, mulai dari tgl <b>".$from."</b> s/d <b>".$to."</b>)");
}
?>
<script>
$(document).ready(function(){
	doneWait();
	
	var jml = $('#jmlFol').val();
	
	if(jml != "" && jml != 0)
	{
		$('#btnFol').click();
	}
	
	$("#dest").alphanum();// prevent \/:*?"<>|
	$("#neces").alphanum();
	$("#veh").alphanum();
	
	setTimeout(function()
	{
		var noteLength = $("#note").val().length;
		sisaLimit(noteLength, 'countdown', '500');
	},50);
});
function submitSpj(tipe)
{
	var tipeUser = parent.$('#tipeUser').val();
	var aksi = formSpj.aksi.value;
	$('#tipe').val(tipe);
	
	var kdCmp = formSpj.kdCmp.value;
	var fromDate = formSpj.fromDate.value;
	var toDate = formSpj.toDate.value;
	var dest = formSpj.dest.value;
	var neces = formSpj.neces.value;
	var veh = formSpj.veh.value;
	var jmlFol = formSpj.jmlFol.value;
	var note = formSpj.note.value;
	
	var fromThn = fromDate.substr(6,4);
	var fromBln = fromDate.substr(3,2);
	var fromTgl = fromDate.substr(0,2);
	var fromDateUbah = fromThn+fromBln+fromTgl;
	
	var toThn = toDate.substr(6,4);
	var toBln = toDate.substr(3,2);
	var toTgl = toDate.substr(0,2);
	var toDateUbah = toThn+toBln+toTgl;
	
	var img = '<img src=\"../../picture/exclamation-red.png\" width=\"14\"/>&nbsp;';

	//alert(paramDateAct+', '+itemName+', '+qty+', '+satuanQty+', '+price);

	if(tipeUser == "admin")
	{
		var employeeId = formSpj.employeeId.value;
		if(employeeId.replace(/ /g,"") == "") // Commpany tidak boleh kosong
		{
			document.getElementById('errorMsg').innerHTML = img+ "Employee still empty";
			document.getElementById('employeeId').focus();
			return false;
		}
	}
	
	if(kdCmp.replace(/ /g,"") == "") // Company tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = img+ "Company still empty";
		document.getElementById('kdCmp').focus();
		return false;
	}
	
	if(fromDate.replace(/ /g,"") == "") // from date tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = img+ "From Date still empty";
		document.getElementById('fromDate').focus();
		return false;
	}
	
	if(toDate.replace(/ /g,"") == "") // to date tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = img+ "To Date still empty";
		document.getElementById('toDate').focus();
		return false;
	}
	
	if(toDateUbah < fromDateUbah)
	{
		document.getElementById('errorMsg').innerHTML = img+ '"To" date must after "From" date';
		document.getElementById('toDate').focus();
		return false;
	}
	
	if(dest.replace(/ /g,"") == "") // Destination tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = img+ "Destination still empty";
		document.getElementById('dest').focus();
		return false;
	}
	
	if(neces.replace(/ /g,"") == "") // Necessary tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = img+ "Necessary still empty";
		document.getElementById('neces').focus();
		return false;
	}
	
	if(veh.replace(/ /g,"") == "") // Vehicle tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = img+ "Vehicle still empty!";
		document.getElementById('veh').focus();
		return false;
	}
	
	if(jmlFol != 0)
	{
		for(var i=1; i<=jmlFol; i++)
		{
			if($('#foll'+i).val().replace(/ /g,"") == "0") // Vehicle tidak boleh kosong
			{
				document.getElementById('errorMsg').innerHTML = img+ "Follower "+i+" still empty!";
				$('#foll'+i).focus();
				return false;
			}
		}
	}
	
	if(note.replace(/ /g,"") == "") // Note tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = img+ "Note still empty!";
		document.getElementById('note').focus();
		return false;
	}
	
	if(tipe == "Draft")
	{
		var answerTxt = "Save as Draft";
	}
	if(tipe == "Wait")
	{
		var answerTxt = "Submit";
	}
	
	var answer  = confirm("Are you sure want to "+answerTxt+" ?");
	if(answer)
	{
		pleaseWait();
		formSpj.submit();
	}
	else
	{	return false;	}
}

function setup()
{
	var decimalSeparator = ".";
    var groupSeparator = ",";
		
	var numParser1 = new NumberParser(0, decimalSeparator, groupSeparator, true);
    var numMask1 = new NumberMask(numParser1, "jmlFol", 2);
}

function hanyaAngkaAmount(id)
{
	var decimalSeparator = ".";
    var groupSeparator = ",";
		
	var numParser1 = new NumberParser(2, decimalSeparator, groupSeparator, true);
    var numMask1 = new NumberMask(numParser1, id, 9);
}

function ajaxFol(jml)
{
	var formId = $('#formId').val();
	var db = $('#db').val();
	$.post( 
		"../halPost.php",
		{	halaman: 'follower', jml: jml, formId: formId, db: db	},
		function(data){
			$('#divFol').html(data);	
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
<input type="hidden" id="formId" name="formId" value="<?php echo $formIdGet;?>"/>
<input type="hidden" id="db" name="db" value="<?php echo $db;?>"/>
<table cellpadding="0" cellspacing="0" border="0" width="99%" height="99%" align="center">
<tr valign="top" style="width:100%;">
	<td align="left">
    	<!--<span class="teksLvlFolder" style="color:#666;font-size:14px;"><b></b></span>-->
    </td>
	<td align="right">
    	<span class="teksLvlFolder" style="color:#999;font-size:16px;font-style:italic;"><b>:: Edit SPJ ::</b></span>
    </td>
</tr>

<tr valign="top">
	<td colspan="2" class="spjTdMyFolder" bgcolor="#FFFFFF" valign="top" align="center">
    <div style="width:99%;<?php echo $height;?>overflow:scroll;overflow-x: hidden;top: expression(offsetParent.scrollTop);">
    <form action="" name="formSpj" id="formSpj" method="post" enctype="multipart/form-data">
    <table cellpadding="0" cellspacing="5" width="98%" class="spjFormInput" border="0">
                <tr><td height="5" colspan="3"></td></tr>
                <?php
					if($userJenisSpj == "admin")
					{
				?>
                <tr valign="top">
                	<td height="28px" align="left" width="17%" title="Nama Karyawan">Employee</td>
                    <td width="83%" align="left">
                   		<select id="employeeId" name="employeeId" class="elementMenu" style="width:100%">
                            <option>--PLEASE SELECT--</option>
                            <?php
								$ownerId = $CSpj->detilForm($formIdGet, "ownerid");
								 
                                $queryEmp = $CKoneksiSpj->mysqlQuery("SELECT userid FROM msuser");
								while($rowEmp = $CKoneksiSpj->mysqlFetch($queryEmp))
								{
									$sel = '';
									if($ownerId == $rowEmp['userid'])
									{
										$sel = 'selected="selected"';
									}
									$menuEmp.= '<option value="'.$rowEmp['userid'].'" '.$sel.'>'.$CSpj->detilLoginSpj($rowEmp['userid'], "userfullnm", $db).'</option>';
								}
								echo $menuEmp;
                            ?>
                        </select>
                    </td>
                </tr>
                <?php } ?>
                <tr valign="top">
                	<td height="28px" align="left" width="17%" title="Tujuan dinas">Company</td>
                    <td width="83%" align="left">
                   		<select id="kdCmp" name="kdCmp" class="elementMenu" style="width:100%">
                            <option>--PLEASE SELECT--</option>
                            <?php 
                                $query = $CKoneksiSpj->mysqlQuery("SELECT kdcmp,nmcmp FROM company WHERE active = 'Y';");
								while($row = $CKoneksiSpj->mysqlFetch($query))
								{
									$sel = '';
									if($kdCmp == $row['kdcmp'])
									{
										$sel = 'selected="selected"';
									}
									$menuCmp.= '<option value="'.$row['kdcmp'].'" '.$sel.'>'.$row['nmcmp'].'</option>';
								}
								echo $menuCmp;
                            ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                	<td height="28px" align="left" width="17%" title="Tujuan dinas">Vessel</td>
                    <td width="83%" align="left">
                   		<select id="slcVsl" name="slcVsl" class="elementMenu" style="width:100%" onchange="getvslName();">
                            <option value="">--PLEASE SELECT--</option>
                            <?php echo $CPaymentAdv->menuVessel($vslCode); ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <td height="28px" width="17%" align="left" valign="middle" title="Tanggal dinas">Date</td>
                    <td width="83%" align="left">
                    <table cellpadding="0" cellspacing="5" width="98%" border="0">
                    <tr>
                        <td width="8%" align="left">
                        From 
                        </td>
                        <td width="10%"><input type="text" class="elementDefault" id="fromDate" name="fromDate" style="width:80px;height:15px;color:#333;font-weight:bold;font-size:12px;text-align:center;" readonly value="<?php echo $fromDateDbDisp;?>">
                        </td>
                        <td width="8%">
                         <img src="../../picture/calendar.gif" width="30" height="25" class="spjKalender" title="Select Date" onclick="displayCalendar(document.getElementById('fromDate'),'dd/mm/yyyy',this, '', '', '193', '70');"/>
                         </td>
                         <td width="5%">
                        to 
                        </td>
                        <td width="10%"><input type="text" class="elementDefault" id="toDate" name="toDate" style="width:80px;height:15px;color:#333;font-weight:bold;font-size:12px;text-align:center;" readonly value="<?php echo $toDateDbDisp;?>">
                        </td>
                        <td width="59%">
                        <img src="../../picture/calendar.gif" width="30" height="25" class="spjKalender" title="Select Date" onclick="displayCalendar(document.getElementById('toDate'),'dd/mm/yyyy',this, '', '', '193', '70');"/>
                        </td>
                    </tr>
                    </table>
                    </td>
                </tr>
                <tr valign="top">
                	<td height="28px" align="left" width="17%" title="Tujuan dinas">Destination</td>
                    <td width="83%">
                   		<input type="text" class="elementDefault" id="dest" name="dest" style="width:99%;height:15px;" title="Tujuan dinas" value="<?php echo $CPublic->konversiQuotes1($destDb);?>"/>
                    </td>
                </tr>
                <tr valign="top">
                	<td height="28px" width="17%" align="left" title="Keperluan dinas yang akan dilaksanakan">Necessary</td>
                    <td width="83%">
                    	<input type="text" class="elementDefault" id="neces" name="neces" style="width:99%;height:15px;" title="Keperluan dinas yang akan dilaksanakan" value="<?php echo $CPublic->konversiQuotes1($necDb);?>"/>
                    </td>
                </tr>
                <tr valign="top">
                	<td height="28px" align="left" title="Kendaraan yang diperlukan untuk dinas">Vehicle</td>
                    <td>
                    	<input type="text" class="elementDefault" id="veh" name="veh" style="width:99%;height:15px;" title="Kendaraan yang diperlukan untuk dinas" value="<?php echo $CPublic->konversiQuotes1($vehDb);?>"/>
                    </td>
                </tr>
                <tr valign="top">
                	<td height="28px" align="left" title="Cash Advance">Cash Advance</td>
                    <td>
                    	<select id="slcCurr" name="slcCurr" class="elementMenu" style="width:20%;">
		                    <option value="">-- CURRENCY  --</option>
		                    <?php echo $CPaymentAdv->menuCurrency($slcCurr); ?>
		                </select>&nbsp;
                    	<input type="text" class="elementDefault" id="txtAmount" name="txtAmount" oninput="hanyaAngkaAmount('txtAmount');" style="width:30%;height:15px;text-align:right;" value="<?php echo $cashAdv; ?>" title="Total Cash Advance"/>
                    </td>
                </tr>
                <tr valign="top">
                	<td height="28px" align="left" title="Rekan yang mendampingi dinas">Follower</td>
                    <td align="left">
                    	<input type="text" class="elementDefault" id="jmlFol" name="jmlFol" style="width:5%;height:15px;" title="Tentukan jumlah Follower" onFocus="setup();" onKeyUp="setup();" value="<?php echo $jmlFolDb;?>"/>
                        <button type="button" class="spjBtnStandar" id="btnFol" style="width:50px;height:27px;" title="Create Row" onclick="ajaxFol($('#jmlFol').val());">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                              <tr>
                                <td align="center">Select</td>
                              </tr>
                            </table>
                        </button>
                        <div id="divFol">
                        </div>
                    </td>
                </tr>
                <tr valign="top">
                	<td height="28px" align="left" title="Catatan">Note</td>
                    <td align="left">
                    	<textarea id="note" name="note" onKeyDown="limitText(this.form.note,this.form.countdown,500);" 
onKeyUp="limitText(this.form.note,this.form.countdown,500);" class="elementDefault" style="width:99%;height:98px;" title="Catatan"><?php echo $noteDb;?></textarea> 
						<br/><span style="font-size:11px;margin-top:10px;"><input readonly type="text" style="width:6%" id="countdown" name="countdown" value="500"> characters left.</span>
                    </td>
                </tr>
                <tr>
                	<td>
                    	<input type="hidden" id="aksi" name="aksi" value="edit"/>
                    	<input type="hidden" id="tipe" name="tipe" value=""/>
                    	<input type="hidden" id="txtVesselNameHid" name="txtVesselNameHid" value="<?php echo $vslName; ?>"/>
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
       &nbsp;<button class="spjBtnStandar" id="btnNewFolder" onclick="submitSpj('Draft'); return false;" style="width:77px;height:25px;" title="Save as draft form SPJ">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                <tr>
                    <td align="right" width="17"><img src="../picture/disk.png" height="15"/> </td>
                    <td align="center">DRAFT</td>
                </tr>
                </table>
            </button>
       &nbsp;<button class="spjBtnStandar" id="btnNewFolder" onclick="submitSpj('Wait'); return false;" style="width:83px;height:25px;" title="Submit Form SPJ">
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
	function getvslName()
	{
		$("#txtVesselNameHid").val('');
		var vslNm = $("#slcVsl option:selected").text();

		document.getElementById('txtVesselNameHid').value = vslNm;
	}
<?php
if($aksiPost == "edit")
{
	$tipeRpt = "";
	$tr = "1";
	if($tipe == "Draft")
	{
		$tipeRpt = "Save as draft";
		$tr = $trActiveGet;
	}
	if($tipe == "Wait")
	{
		$tipeRpt = "Submit";
	}

	echo "parent.exit();
		  parent.report('".$tipeRpt."');
		  parent.refresh('Y','Y');
		  parent.klikTr('".$tr."');";
}
?>
</script>
</HTML>	