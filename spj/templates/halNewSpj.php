<!DOCTYPE HTML>
<?php
require_once('../../config.php');
require_once('../configSpj.php');

$height = "height:400px;";
if($userJenisSpj == "admin")
{
	$height = "height:450px;";
}

$vesselCode = "";
/*echo $CEmployee->detilJabatan($CEmployee->detilTblEmpGen($userEmpNo, "kdjabatan"), "nmjabatan"); // jabatan
echo "<br/>".$CEmployee->detilPangkat($CEmployee->detilTblEmpGen($userEmpNo, "kdpangkat"), "nmpangkat") ; // golongan
echo "<br/>".$CSpj->cariAproverId($userIdLogin, $CEmployee, $db); //aproverId*/

?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../css/cssSpj.css" rel="stylesheet" type="text/css" />
<link href="../css/loading.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK>

<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>
<script type="text/javascript" src="../js/jquery.alphanum.js"></script>
<script type="text/javascript" src="../js/limitText.js"></script>
<script src="../../js/JavaScriptUtil.js"></script>
<script src="../../js/Parsers.js"></script>
<script src="../../js/InputMask.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<SCRIPT type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="../js/loading.js"></script>

<?php
if($aksiPost == "add")
{
	// echo "<pre>";
	// print_r($_POST);exit;
	// echo "</pre>";
	
	$tipe = $_POST['tipe'];// draft atau submit(Wait)
	$tipeHist = "";
	if($tipe == "Draft")
	{
		$tipeHist = "Save as draft";
	}
	if($tipe == "Wait")
	{
		$tipeHist = "Submit";
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
	$tipedestination = $_POST['jnsData'];

	$slcCurr = $_POST['slcCurr'];
	$cashAdv= str_replace(",","",$_POST['txtAmount']);
	$vslCode = $_POST['slcVsl'];
	$vslName = $_POST['txtVesselNameHid'];
	
	$urutanAkhir = $CSpj->urutanAkhir();
	$urutan = $urutanAkhir + 1;
	
	if($userJenisSpj != "admin")//jika yang input bukan admin, ownerId SPJ == UserIdLogin
	{
		$employeeId = $userIdLogin;
		$employeeNo = $userEmpNo;
		$employeeName = rtrim($userFullnm);
		
		$jabatan = $CEmployee->detilJabatan($CEmployee->detilTblEmpGen($userEmpNo, "kdjabatan"), "nmjabatan"); // jabatan
		$golongan = $CEmployee->detilPangkat($CEmployee->detilTblEmpGen($userEmpNo, "kdpangkat"), "nmpangkat") ; // golongan
		$aproverEmpNo = $CSpj->detilLoginSpj($CSpj->cariAproverId($userIdLogin, $CEmployee, $db), "empno", $db);
		
		//insert Database
		$insert = $CKoneksiSpj->mysqlQuery("INSERT INTO form (urutan, kdcmp, ownerid, ownerempno, kadivempno, ownername, jabatan, golongan, datefrom, dateto, destination, tipedestination, necessary, vehicle, note, status, addusrdt, currency, cashadvance, vessel_code, vessel_name) VALUES (".$urutan.", ".$kdCmp.", ".$userIdLogin.", ".$userEmpNo.", ".$aproverEmpNo.", '".$userFullnm."', '".$jabatan."', '".$golongan."', ".$fromDate.", ".$toDate.", '".$dest."','".$tipedestination."', '".$neces."', '".$veh."', '".$note."', '".$tipe."', '".$CPublic->userWhoAct()."','".$slcCurr."','".$cashAdv."','".$vslCode."','".$vslName."');");
		$lastInsertId = mysql_insert_id();
		
		$aproverId = $CSpj->cariAproverId($userIdLogin, $CEmployee, $db);
	}
	if($userJenisSpj == "admin")//jika yang input adalah admin
	{
		$employeeId = $_POST['employeeId'];
		$employeeNo = $CSpj->detilLoginSpj($employeeId, "empno", $db);
		$employeeName = $CSpj->detilLoginSpj($employeeId, "userfullnm", $db);
		
		$jabatan = $CEmployee->detilJabatan($CEmployee->detilTblEmpGen($employeeNo, "kdjabatan"), "nmjabatan"); // jabatan
		$golongan = $CEmployee->detilPangkat($CEmployee->detilTblEmpGen($employeeNo, "kdpangkat"), "nmpangkat") ; // golongan
		$aproverEmpNo = $CSpj->detilLoginSpj($CSpj->cariAproverId($employeeId, $CEmployee, $db), "empno", $db);
		
		//insert Database
		$insert = $CKoneksiSpj->mysqlQuery("INSERT INTO form (urutan, kdcmp, ownerid, ownerempno, kadivempno, ownername, jabatan, golongan, datefrom, dateto, destination, tipedestination, necessary, vehicle, note, status, createdby, addusrdt, currency, cashadvance, vessel_code, vessel_name) VALUES (".$urutan.", ".$kdCmp.", ".$employeeId.", ".$employeeNo.", ".$aproverEmpNo.", '".$employeeName."', '".$jabatan."', '".$golongan."', ".$fromDate.", ".$toDate.", '".$dest."', '".$tipedestination."', '".$neces."', '".$veh."', '".$note."', '".$tipe."', ".$userIdLogin.", '".$CPublic->userWhoAct()."','".$slcCurr."','".$cashAdv."','".$vslCode."','".$vslName."');");
		$lastInsertId = mysql_insert_id();
		
		$aproverId = $CSpj->cariAproverId($employeeId, $CEmployee, $db);
	}
	//echo $fromDate." | ".$toDate." | ".$jabatan." | ".$golongan." | ".$userEmpNo." | ".$employeeNo." | ".$userFullnm." | ".$employeeName." | ".$dest." | ".$neces." | ".$veh." | ".$foll." | ".$note." | ".$aproverId;
	
	if($jmlFol != 0)// insert Follower ke Database
	{
		$i;
		for($i=1;$i<=$jmlFol;$i++)
		{
			$CKoneksiSpj->mysqlQuery("INSERT INTO follower (formid, followerid) VALUES (".$lastInsertId.", ".$_POST['foll'.$i].");");
		}
	}
	
	//Notifikasi & Custom CEO proses
	if($tipe == "Wait")
	{
		if($employeeId == $ceoId)// Kondisi khusus bagi CEO (lgsg ke proses Acknowledge)
		{
			// $CKoneksiSpj->mysqlQuery("UPDATE form SET status = 'Approved', aprvempno = ".$employeeNo." WHERE formid = ".$lastInsertId.";");
			$CKoneksiSpj->mysqlQuery("UPDATE form SET status = 'Approved',knowbyadm = 'N',knowempno = '".$employeeNo."', aprvempno = ".$employeeNo." WHERE formid = ".$lastInsertId.";");
			
			//Notifikasi Email
			// $CSpj->emailKeKadivHR("emailAprvForm", $lastInsertId, $CPublic, $CEmployee, $CNotifSpj, $db, $link, "kadivHr");//Kadiv HR
			$CSpj->emailKeAdmin("emailAckForm", $lastInsertId, $CPublic, $CEmployee, $CNotifSpj, $db, $link, "kadivHr");//ADMIN
			//Notifikasi Desktop
			$notesKadiv = $employeeName."''s SPJ Request Form has been approved. It requires your to proccess it.";
			// $notesKadiv = $employeeName."''s SPJ Request Form has been approved. It requires your Acknowledgement to proccess it.";
			// $CSpj->desktopKeKadivHR($koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notesKadiv, $db);//kadiv HR
			$CSpj->desktopKeAdmin($koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notesKadiv, $db);//ADMIN
		}
		
		if($employeeId != $ceoId)// Jika owner form bukan CEO
		{
			//Notifikasi Email
			$CSpj->emailKeAtasan("emailNewForm", $aproverId, $employeeNo, $lastInsertId, $CPublic, $CEmployee, $CNotifSpj, $db, $link, "");
			//Notifikasi Desktop
			$notes = $employeeName." has submitted SPJ Request Form. It requires your Approval.";
			$CSpj->desktopKeAtasan($employeeId, $aproverId, $koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notes, $db);
		}
	}
	
	//insert history
	$CHistory->updateLogSpj($userIdLogin, "".$tipeHist." Form SPJ (formid = <b>".$lastInsertId."</b>, tujuan dinas = <b>".$dest."</b>, mulai dari tgl <b>".$from."</b> s/d <b>".$to."</b>)");
}

?>
<script>
$(document).ready(function() {
	var cekUser = "<?php echo $userJenisSpj; ?>";
	if(cekUser != "admin")
	{
		setAllowence();
	}
	$("#employeeId").change(function(){
		setAllowence();
	});
	$('input[type="radio"]').click(function(){
		setAllowence();
	});
});
window.onload = function()
{
	doneWait();
	$("#dest").alphanum();// prevent \/:*?"<>|
	$("#neces").alphanum();
	$("#veh").alphanum();
}

function setAllowence()
{
	$('#idTdAllow').empty();
	var db = "<?php echo $db; ?>";
	var stData = $('input[name=jnsData]:checked').val();
	var userType = "<?php echo $userJenisSpj; ?>";
	var userId = "<?php echo $userIdLogin; ?>";

	if(userId == "--PLEASE SELECT--")
	{
		alert("Select Employee..!!");
		return false;
	}
	if(userType == "admin")
	{
		userId = $("#employeeId").val();
	}
	$.post('../halPost.php',
		{ halaman : "getDataAllowance",db : db,userId : userId,stData : stData},
			function(data) 
			{	
				var html = data;
				$('#idTdAllow').append(html);
			},
		"json"
	);
}

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

	// alert(kdCmp);return false;

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
	
	if(kdCmp.replace(/ /g,"") == "") // Commpany tidak boleh kosong
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
		document.getElementById('errorMsg').innerHTML = img+ 'Date "To" must be greater than Date "From"';
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
	$.post( 
		"../halPost.php",
		{	halaman: 'jmlFol', jml: jml	},
		function(data){
			$('#divFol').html(data);	
		}
	);
}

/*function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
}*/

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
    	<span class="teksLvlFolder" style="color:#999;font-size:16px;font-style:italic;"><b>:: New SPJ ::</b></span>
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
                            <option value="">--PLEASE SELECT--</option>
							<?php 
                                $queryEmp = $CKoneksiSpj->mysqlQuery("SELECT userid FROM msuser");
								while($rowEmp = $CKoneksiSpj->mysqlFetch($queryEmp))
								{
									$menuEmp.= '<option value="'.$rowEmp['userid'].'">'.$CSpj->detilLoginSpj($rowEmp['userid'], "userfullnm", $db).'</option>';
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
                            <option value="">--PLEASE SELECT--</option>
                            <?php 
                                $query = $CKoneksiSpj->mysqlQuery("SELECT kdcmp,nmcmp FROM company WHERE active = 'Y';");
								while($row = $CKoneksiSpj->mysqlFetch($query))
								{
									$menuCmp.= '<option value="'.$row['kdcmp'].'">'.$row['nmcmp'].'</option>';
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
                            <?php echo $CPaymentAdv->menuVessel($vesselCode); ?>
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
                        <td width="10%"><input type="text" class="elementDefault" id="fromDate" name="fromDate" style="width:80px;height:15px;color:#333;font-weight:bold;font-size:12px;text-align:center;" onclick="displayCalendar(document.getElementById('fromDate'),'dd/mm/yyyy',this, '', '', '193', '170');" readonly>
                        </td>
                        <td width="8%">
                         <img src="../../picture/calendar.gif" width="30" height="25" class="spjKalender" title="Select Date" onclick="displayCalendar(document.getElementById('fromDate'),'dd/mm/yyyy',this, '', '', '193', '70');"/>
                         </td>
                         <td width="5%">
                        to 
                        </td>
                        <td width="10%"><input type="text" class="elementDefault" id="toDate" name="toDate" style="width:80px;height:15px;color:#333;font-weight:bold;font-size:12px;text-align:center;" onclick="displayCalendar(document.getElementById('toDate'),'dd/mm/yyyy',this, '', '', '193', '170');" readonly>
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
                    <td width="83%" align="left" >
						<input type="radio" name="jnsData" class ="elementDefault" value="dalam" checked = "checked"> Domestic
						<input type="radio" name="jnsData" class ="elementDefault" value="luar"> International <br><br>
                   		<input type="text" class="elementDefault" id="dest" name="dest" style="width:99%;height:15px;" title="Tujuan dinas"/>
                    </td>
                </tr>
                <tr valign="top">
                	<td height="28px" width="17%" align="left" title="Keperluan dinas yang akan dilaksanakan">Necessity</td>
                    <td width="83%">
                    	<input type="text" class="elementDefault" id="neces" name="neces" style="width:99%;height:15px;" title="Keperluan dinas yang akan dilaksanakan"/>
                    </td>
                </tr>			
                <tr valign="top">
                	<td height="28px" align="left" title="Kendaraan yang diperlukan untuk dinas">Vehicle</td>
                    <td>
                    	<input type="text" class="elementDefault" id="veh" name="veh" style="width:99%;height:15px;" title="Kendaraan yang diperlukan untuk dinas"/>
                    </td>
                </tr>
                <tr valign="top">
                	<td height="28px" align="left" title="Cash Advance">Cash Advance</td>
                    <td>
                    	<select id="slcCurr" name="slcCurr" class="elementMenu" style="width:20%;">
		                    <option value="">-- CURRENCY  --</option>
		                    <?php echo $CPaymentAdv->menuCurrency($currNya); ?>
		                </select>&nbsp;
                    	<input type="text" class="elementDefault" id="txtAmount" name="txtAmount" oninput="hanyaAngkaAmount('txtAmount');" style="width:30%;height:15px;text-align:right;" title="Total Cash Advance" autocomplete="true" />
                    </td>
                </tr>
                <tr valign="top">
                	<td height="28px" align="left" title="Rekan yang mendampingi dinas">Follower</td>
                    <td align="left">
                    	<input type="text" class="elementDefault" id="jmlFol" name="jmlFol" style="width:5%;height:15px;" title="Tentukan jumlah Follower" onFocus="setup();" onKeyUp="setup();" value="0"/>
                        <button type="button" class="spjBtnStandar" style="width:40px;height:27px;" title="Create Row" onclick="ajaxFol($('#jmlFol').val());">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                              <tr>
                                <td align="center">Add</td>
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
onKeyUp="limitText(this.form.note,this.form.countdown,500);" class="elementDefault" style="width:99%;height:98px;" title="Catatan"></textarea>
						<br/><span style="font-size:11px;margin-top:10px;"><input readonly type="text" style="width:6%" name="countdown" value="500"> characters left.</span>
					</td>
                </tr>
				<tr valign="top">
                	<td height="28px" align="left" title="Tunjangan">Allowance</td>
                    <td id="idTdAllow">
                    	
                    </td>
                </tr>
                <tr>
                	<td>
                    	<input type="hidden" id="aksi" name="aksi" value="add"/>
                    	<input type="hidden" id="tipe" name="tipe" value=""/>
                    	<input type="hidden" id="txtVesselNameHid" name="txtVesselNameHid" value=""/>
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
if($aksiPost == "add")
{
	$tipeRpt = "";
	if($tipe == "Draft")
	{
		$tipeRpt = "Save as draft";
	}
	if($tipe == "Wait")
	{
		$tipeRpt = "Submit";
	}

	echo "parent.exit();
		  parent.report('".$tipeRpt."');
		  parent.refresh('Y','Y');
		  parent.klikTr('1');";
}
?>
</script>
</HTML>	