<!DOCTYPE HTML>
<?php
require_once("../../config.php");

$idactivityGet = $_GET['idactivity'];
$kdDivUser = $CLogin->detilLogin($userIdLogin, "kddiv");
$halamanGet = $_GET['halaman'];
$dateActGet = $_GET['dateAct'];

$statusChangeByName = $CDailyAct->detilAct($idactivityGet, "statuschangebyname");

if($halamanGet  == "edit")
{
	$fromTime = $CDailyAct->detilAct($idactivityGet, "fromtime");
	$toTime = $CDailyAct->detilAct($idactivityGet, "totime");
	$activity = $CDailyAct->detilAct($idactivityGet, "activity");
	$relatedInfo = $CDailyAct->detilAct($idactivityGet, "relatedinfo");
	$statusAct = $CDailyAct->detilAct($idactivityGet, "status");
	$project = $CDailyAct->detilAct($idactivityGet, "project");
	$problemIdent = $CDailyAct->detilAct($idactivityGet, "problemident");
	$corrective = $CDailyAct->detilAct($idactivityGet, "corrective");
	$kpiNumber = $CDailyAct->detilAct($idactivityGet, "kpinumber");
	$spvComment = $CDailyAct->detilAct($idactivityGet, "spvcomment");
	$responComment = $CDailyAct->detilAct($idactivityGet, "responcomment");
	$referIdActivity = $CDailyAct->detilAct($idactivityGet, "referidactivity");
	$tglAct = $CDailyAct->detilAct($idactivityGet, "tanggal");
	$blnAct = $CDailyAct->detilAct($idactivityGet, "bulan");
	$thnAct = $CDailyAct->detilAct($idactivityGet, "tahun");
	//$finish = $CDailyAct->detilAct($idactivityGet, "datefinish");
	
	//=========== START === Fungsi Prev & Next Button ======================================
	$urutanCurrent = $CDailyAct->detilAct($idactivityGet, "urutan");
	$idActivityPrev = $CDailyAct->btnPrevNext($urutanCurrent, $userIdLogin, $tglAct, $blnAct, $thnAct, "prev");
	$idActivityNext = $CDailyAct->btnPrevNext($urutanCurrent, $userIdLogin, $tglAct, $blnAct, $thnAct, "next");
	
	$btnPrevJob = $CDailyAct->prevJob($idActivityPrev);
	$btnNextJob = $CDailyAct->nextJob($idActivityNext);

	//=========== END OF === Fungsi Prev & Next Button======================================
	
	$dateCreated = $CDailyAct->detilAct($referIdActivity, "CONCAT(tanggal,'/',bulan,'/',tahun)");

	// BERIKAN STATUS JIKA SUPERIOR COMMENT SUDAH DIBACA
	$CKoneksi->mysqlQuery("UPDATE tblactivity SET oldspvcomment='".mysql_real_escape_string( $spvComment )."' WHERE idactivity='".$idactivityGet."' AND deletests=0;");
}

if($aksiGet == "simpanEditAct")
{
	$statusActOld = $CDailyAct->detilAct($idactivityGet, "status");
	// memberi paramater ID User yang mengubah status (progress / finish / postpone)
	$statusActNew = $_POST['statusAct']; 
	if($statusActOld != $statusActNew)
	{
		$statusChange = ", statuschangebyid = '".$userIdLogin."', statuschangebyname = '".$userFullnm."'";
	}
	
	$dateActGet = $_GET['dateAct'];	
	$tglAct =  substr($dateActGet,0,2);
	$blnAct =  substr($dateActGet,3,2);
	$thnAct =  substr($dateActGet,6,4);
	
	$fromTime = $_POST['fromTime']; 
	$toTime = $_POST['toTime']; 
	$statusBtnChooseAct = $_POST['statusBtnChooseAct'];
	
	//JIKA YANG DIPILIH ACTIVITYNYA BERUPA TEKS INPUT
	if($statusBtnChooseAct == "teksAct"){
		$idActivityOnProgress = "00000000000";
		$activity = mysql_real_escape_string( $_POST['activity2'] );
	}
	//JIKA YANG DIPILIH ACTIVITYNYA BERUPA TEKS INPUT
	if($statusBtnChooseAct == "menuAct"){
		$idActivityOnProgress = $_POST['activityOnProgress2'];
		$activity = mysql_real_escape_string($CDailyAct->detilAct($idActivityOnProgress,'activity'));
	}
	
	$relatedInfo = mysql_real_escape_string( $_POST['relatedInfo'] ); 
	$statusAct = $_POST['statusAct'];
	$project = $_POST['project']; 
	$problemIdent = mysql_real_escape_string( $_POST['problemIdent'] ); 
	$corrective = mysql_real_escape_string( $_POST['corrective'] ); 
	$kpiNumber = $_POST['kpiNumber'];  
	$responComment = mysql_real_escape_string($_POST['responComment'] );
	
	$dateFinish = "0000-00-00";
	if($statusAct == "finish"){
		//$dateFinish = $CPublic->waktuSek();
		$dateFinish = $thnAct."-".$blnAct."-".$tglAct;
	}
	//echo "Act : ".$activity; 
/*"fromtime:".$fromTime."-totime:".$toTime."-activity:".$activity."-relatedinfo:".$relatedInfo."-status:".$statusAct."".$statusChange."-datefinish:".$dateFinish."-referIdActivity:".$idActivityOnProgress."-problemIdent:".$problemIdent."-corrective:".$corrective."-kpiNumber:".$kpiNumber."-responComment:".$responComment;*/
	$CKoneksi->mysqlQuery("UPDATE tblactivity SET fromtime = '".$fromTime."', totime = '".$toTime."', activity = '".$activity."', relatedinfo = '".$relatedInfo."', status = '".$statusAct."' ".$statusChange.", project = '".$project."', datefinish = '".$dateFinish."', referidactivity = '".$idActivityOnProgress."', problemident = '".$problemIdent."', corrective = '".$corrective."', kpinumber = '".$kpiNumber."', responcomment = '".$responComment."', updusrdt = '".$CPublic->userWhoAct()."' WHERE idactivity = '".$idactivityGet."' AND deletests=0;");
	$CHistory->updateLog($userIdLogin, "Rubah Daily Activity (idactivity = <b>".$idactivityGet."</b>, Date = <b>".$dateActGet."</b>, From time = <b>".$fromTime."</b>, To time = <b>".$toTime."</b>, Activity = <b>".$activity."</b>, Related Information = <b>".$relatedInfo."</b>, Status = <b>".$statusAct."</b>, Refer Id Activity = <b>".$idActivityOnProgress."</b>, Problem identification = <b>".$problemIdent."</b>, Corrective = <b>".$corrective."</b>, KPI Number = <b>".$kpiNumber."</b>, project = <b>".$project."</b>)");
}

$disSave = "class=\"btnStandarKecil\"";
$bosApprove = $CDailyAct->detilAct($idactivityGet, "bosapprove");
if($bosApprove == "Y")
{
	$disSave = "class=\"btnStandarKecilDis\" disabled";
}

$lockMsg = "";
$lockEdit = $CDailyAct->detilAct($idactivityGet, "lockedit");
if($lockEdit =="Y")
{
	$lockMsg = "&nbsp * Activity had been locked";
	$disSave = "class=\"btnStandarKecilDis\" disabled";
}

$cariReferIdAct = $CDailyAct->cariReferIdAct($idactivityGet, $userIdLogin);
$readOnlyAct = "";
if($cariReferIdAct >= 1) // jika idactivity ini terdapat di referidactivity aktivitas lain
{
	$readOnlyAct = "readonly";
}

function timeDifference($timeEnd, $timeStart){
  $tResult = strtotime($timeEnd) - strtotime($timeStart);
  return date("G:i", $tResult-3600);
}
?>

<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<script src="../js/main.js"></script>
<script src="../../js/JavaScriptUtil.js"></script>
<script src="../../js/Parsers.js"></script>
<script src="../../js/InputMask.js"></script>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../js/animatedcollapse.js"></script>
<script type="text/javascript" src="../ddlevelsmenu/ddlevelsfiles/ddlevelsmenu.js"></script>
<script type="text/javascript">
// === start == Animated Collapsible DIV
animatedcollapse.addDiv('jason', 'fade=1,height=auto,overflow-y=scroll')
animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
	//$: Access to jQuery
	//divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
	//state: "block" or "none", depending on state
}
animatedcollapse.init()
// === end of == Animated Collapsible DIV

function setupMask() 
{
	//Set up the date parsers
	var dateParser = new DateParser("dd/MM/yyyy HH:mm");   

	//Set up the DateMasks
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";

	var dateParserFromTime = new DateParser("HH:mm");
	var dateMaskFromTime = new DateMask(dateParserFromTime, "fromTime");
	dateMaskFromTime.validationMessage = errorMessage;
	
	var dateParserToTime = new DateParser("HH:mm");
	var dateMaskToTime = new DateMask(dateParserToTime, "toTime");
	
	if(detectBrowser() == "MSIE")
	{
		document.getElementById('jason').style.width = '99%';
	}
	else if(detectBrowser() == "Firefox")
	{
		document.getElementById('jason').style.width = '96.4%';
	}
}
	
/*function pilihStatus(statusAct)
{
	
	if(statusAct == "postpone")
	{
		document.getElementById('problemIdent').disabled = false;
		document.getElementById('corrective').disabled = false;
	}
	else
	{
		document.getElementById('problemIdent').disabled = true;
		document.getElementById('corrective').disabled = true;
	}
}*/
function ajaxApprvDailyAct(id, aksi, halaman)
{
	var mypostrequest=new ajaxRequest()
	mypostrequest.onreadystatechange=function()
	{
		if (mypostrequest.readyState==4)
		{
			if (mypostrequest.status==200 || window.location.href.indexOf("http")==-1)
			{
				document.getElementById(halaman).innerHTML=mypostrequest.responseText;
			}
		}
	}
	
	if(aksi == "cekApprvAdaTidak")
	{
		var ownerId = "<?php echo $userIdLogin; ?>";
		var dateAct = "<?php echo $dateActGet; ?>";
		var parameters="halaman="+aksi+"&dateAct="+dateAct+"&ownerId="+ownerId;
	}
	
	mypostrequest.open("POST", "../halPostFold.php", true);
	mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	mypostrequest.send(parameters);
}

function pilihBtnSave()
{
	document.getElementById('errorMsg').innerHTML = "&nbsp;	";
	
	var fromTime = document.getElementById('fromTime').value; 
	var toTime = document.getElementById('toTime').value;
	var relatedInfo = document.getElementById('relatedInfo').value;
	var statusBtnChooseAct = document.getElementById('statusBtnChooseAct').value;

	if(fromTime.replace(/ /g,"") == "")
	{
		document.getElementById('errorMsg').innerHTML = "<img src=\"../../picture/exclamation-red.png\" width=\"14\"/>&nbsp;From time still empty!";
		document.getElementById('fromTime').focus(); 
		return false;
	}
	if(toTime.replace(/ /g,"") == "")
	{
		document.getElementById('errorMsg').innerHTML = "<img src=\"../../picture/exclamation-red.png\" width=\"14\"/>&nbsp;To time still empty!";
		document.getElementById('toTime').focus(); 
		return false;
	}
	if (statusBtnChooseAct == 'teksAct'){
		var activity = document.getElementById('activity').value;
		document.getElementById('activity2').value = activity;
		if(activity.replace(/ /g,"") == "")
		{
			document.getElementById('errorMsg').innerHTML = "<img src=\"../../picture/exclamation-red.png\" width=\"14\"/>&nbsp;Activity still empty!";
			document.getElementById('activity').focus(); 
			return false;
		}
	}
	if(statusBtnChooseAct == 'menuAct')
	{
		var activityOnProgress = document.getElementById('activityOnProgress').value;
		document.getElementById('activityOnProgress2').value = activityOnProgress;
		if(activityOnProgress=='00000000000')
		{
			document.getElementById('errorMsg').innerHTML = "<img src=\"../../picture/exclamation-red.png\" width=\"14\"/>&nbsp;You have not choose Activity!";
			document.getElementById('activityOnProgress').focus(); 
			return false;
		}
	}
	if(relatedInfo.replace(/ /g,"") == "")
	{
		document.getElementById('errorMsg').innerHTML = "<img src=\"../../picture/exclamation-red.png\" width=\"14\"/>&nbsp;related Information still empty!";
		document.getElementById('relatedInfo').focus(); 
		return false;
	}
	
	var answer  = confirm("Are you sure want to save?");
	if(answer)
	{
		ajaxApprvDailyAct("", "cekApprvAdaTidak", "idApprvAdaTidak");
		//return false;
		var delay=1000;//1 seconds
		
    	setTimeout(function()
		{
			var apprvAdaTidak = document.getElementById('apprvAdaTidak').value;
			if(apprvAdaTidak == "ada")
			{
				alert('Daily Activity is already Approve by Supervisor');
				return false;	
			}
			formNewAct.submit();
   		},delay);
	}
	else
	{	return false;	}
}

function pilihBtnReset()
{
	formNewAct.reset();
}

function exit()
{
	parent.tb_remove(false);
	parent.document.getElementById('iframeHal').src = "";
	parent.document.getElementById('iframeHal').src = "templates/halDailyActList.php?halaman=openJobDailyList&dateAct=<?php echo $dateActGet; ?>";
	//parent.gantiDateAct('<?php echo $dateActGet; ?>');
}

function tutup()
{
	var answer  = confirm("Are you sure want to close?");
	if(answer)
	{
		exit();
		
	}
	else
	{	return false;	}
}

function tampilSelection (aksi, idHalaman){
	document.getElementById('idHref').click();
	var mypostrequest=new ajaxRequest()
	mypostrequest.onreadystatechange=function()
	{
		if (mypostrequest.readyState==4)
		{
			if (mypostrequest.status==200 || window.location.href.indexOf("http")==-1)
			{
				document.getElementById(idHalaman).innerHTML=mypostrequest.responseText;
			}
		}
	}

	if(aksi == "chooseAct3")
	{
		var idActivity = "<?php echo $idactivityGet; ?>";
		var dateAct = "<?php echo $dateActGet; ?>";
		var referIdAct = "<?php echo $referIdActivity; ?>";
		
		var btnChooseAct = document.getElementById('btnChooseAct').value;
		var parameters="halaman="+aksi+"&btnChooseAct="+btnChooseAct+"&idActivity="+idActivity+"&dateAct="+dateAct+"&referIdAct="+referIdAct;
	}
	if(aksi == "chooseAct4")
	{
		var activity = encodeURIComponent(document.getElementById('activity').value);
		//var activity = document.getElementById('activity').value
		var btnChooseAct = document.getElementById('btnChooseAct').value;
		var parameters="halaman="+aksi+"&btnChooseAct="+btnChooseAct+"&activity="+activity;
	}
	if(aksi == "typeAct3")
	{
		var readOnlyAct = "<?php echo $readOnlyAct; ?>";
		var activity = encodeURIComponent(document.getElementById('activityTemp').value);
		//var activity = document.getElementById('activityTemp').value;
		var btnChooseAct = document.getElementById('btnChooseAct').value;
		var parameters="halaman="+aksi+"&btnChooseAct="+btnChooseAct+"&activity="+activity+"&readOnlyAct="+readOnlyAct;
	}
	if(aksi == "typeAct4")
	{
		var btnChooseAct = document.getElementById('btnChooseAct').value;
		var parameters="halaman="+aksi+"&btnChooseAct="+btnChooseAct;
	}
	mypostrequest.open("POST", "../halPostFold.php", true);
	mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	mypostrequest.send(parameters);
}

function klikAlsoUsed(dateAlsoUsed)
{
	parent.tb_remove(false);
	parent.loadUrl('../index.php?aksi=openJobDailyList&empNo=<?php echo $userEmpNo; ?>&dateAct='+dateAlsoUsed);
}

function onClickTr(idKpi)
{
	var idTrSeb = document.getElementById('idTrSeb').value;
	var tdKpiListSeb = document.getElementById('tdKpiListSeb').value;
	
	if(idTrSeb != "" || tdKpiListSeb != "")
	{
		document.getElementById(idTrSeb).onmouseover = function onmouseover(){	this.style.backgroundColor='#D9EDFF';	}
		document.getElementById(idTrSeb).onmouseout = function onmouseout(){	this.style.backgroundColor='#f5f5f5';	}
		document.getElementById(idTrSeb).style.fontWeight='';
		document.getElementById(idTrSeb).style.backgroundColor='#f5f5f5';
		document.getElementById(idTrSeb).style.cursor = 'pointer';	
		document.getElementById(tdKpiListSeb).style.fontWeight='';// FONT TIDAK BOLD UNTUK TD YANG DIPILIH
	}
	
	document.getElementById('tr'+idKpi).onmouseout = '';
	document.getElementById('tr'+idKpi).onmouseover ='';
	document.getElementById('tr'+idKpi).style.fontWeight='';
	document.getElementById('tr'+idKpi).style.backgroundColor='#B0DAFF';
	document.getElementById('tr'+idKpi).style.cursor = 'default';
	document.getElementById('tr'+idKpi).style.fontSize='10px';
	document.getElementById('idTrSeb').value = 'tr'+idKpi;
	
	document.getElementById('tdKpiList'+idKpi).style.fontWeight='bold'; // FONT BOLD UNTUK TD YANG DIPILIH
	document.getElementById('tdKpiListSeb').value = 'tdKpiList'+idKpi; // BERI ISI tdKpiListSeb DENGAN ID TD YANG DIPILIH SEBELUMNYA
	
	document.getElementById('kpiNumber').value=idKpi;
}

function tampilLoad()
{
	document.getElementById('divLoad').style.display = "block";
}

setInterval(function(){ $('blink').each( function(){ $(this).css('visibility' , $(this).css('visibility') === 'hidden' ? '' : 'hidden') } ); }, 1000);
</script>

<body bgcolor="#F8F8F8" onLoad="setupMask();" bottommargin="3" topmargin="3">
<center>

<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
<tr valign="top">
	<td height="25" width="50%" align="left">
    	<span class="teksLvlFolder" style="color:#666;">Date Activity : </span>
        <span class="formInput" style="text-decoration:underline;color:#00F;font-weight:bold;"><?php echo $dateActGet; ?></span>
    </td>
    <td align="right"><span class="teksLvlFolder" style="color:#999;font-size:16px;font-style:italic;"><b>:: Edit Activity ::</b></span>&nbsp;</td>
</tr>

<tr valign="top">
    <td class="tdMyFolder" bgcolor="#FFFFFF" colspan="2" align="center" valign="top" width="100%">
    	<div style="width:950px;height:474px;overflow:scroll;overflow-x: hidden;top: expression(offsetParent.scrollTop);">
        
            <table cellpadding="0" cellspacing="0" height="100%" width="99%" border="0">
            <form action="halEditAct.php?aksi=simpanEditAct&dateAct=<?php echo $dateActGet; ?>&idactivity=<?php echo $idactivityGet; ?>" method="post" id="formNewAct" name="formNewAct">
            <tr valign="top">
                <td align="center" valign="top" height="130">
                
                    <table cellpadding="0" cellspacing="5" width="100%" class="formInput" style="cursor:default;">
                    
                    <tr valign="top">
                    	<td width="15%">&nbsp;</td>
                        <td width="15%" align="left">Time</td>
                        <td width="55%" align="left">
                        From&nbsp;&nbsp;&nbsp;
                        <input type="text" class="elementDefault" id="fromTime" name="fromTime" style="width:31px;" value="<?php echo $fromTime; ?>">
                        &nbsp;&nbsp;&nbsp;&nbsp;To&nbsp;&nbsp;&nbsp;
                        <input type="text" class="elementDefault" id="toTime" name="toTime" style="width:31px;" value="<?php echo $toTime; ?>">
                        </td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                    <tr valign="top">
                    	
                        <td rowspan="2">
                        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" class="tabelBorderAll teksLvlFolder" bgcolor="#F0F9FF" style="color:#666;">
                        	<tr>
                            	<td align="center" height="30" bgcolor="#DDF0FF">Created in</td>
                            </tr>
                            <tr>
                            	<td height="5"></td>
                            </tr>
                            <tr>
                            	<td>
                                <?php
								if($dateCreated != "")
								{
									echo "&nbsp;&bull; <span onClick=\"klikAlsoUsed('".$dateCreated."');\" class=\"formInput\"  style=\"text-decoration:underline;color:#00F;font-weight:bold;\" onMouseOver=\"this.style.color='#000080';\" onMouseOut=\"this.style.color='#00F';\">".$dateCreated."</span>";
								}
								?>
                                </td>
                            </tr>
                            <tr>
                            	<td>&nbsp;</td>
                            </tr>
                            </table>
                        </td>
                        <td align="left">
                        	Activity
                        	<a id="idHref" onClick="tampilLoad();"/>
                        </td>
                        <td id="ajaxInput1" align="left">
                        	<div id="divLoad" style="position:fixed;margin-left:250px;display:none;">
                                <img src="../../picture/ajax-loader20.gif"/>
                            </div>
                        <!-- == START == Edit activity yang tidak memiliki Refer ID Activity-->
                        <?php
							if($referIdActivity == 00000000000)
							{
								$activityTemp = $activity;
                        ?>
                        <input type="text" class="elementDefault" id="activity" name="activity" style="width:99%;" value="<?php echo $CPublic->konversiQuotes1($activity); ?>" <?php echo $readOnlyAct; ?>></td>
                        <!-- ==END OF== Edit activity yang tidak memiliki Refer ID Activity-->
                        <!-- == START == Edit activity yang memiliki Refer ID Activity-->
                        <?php
							}
							if($referIdActivity != 00000000000)
							{
								$activityTemp = "";
								$dateActDBFormat = $CPublic->convTglDB($dateActGet);
								$tglStartPrevActDBFormat = $CPublic->convTglDB($tglStartPrevAct); 
								$menuActOnProgress= "";
								$nilaiMenu = "";
								$menuActOnProgress.="<select class=\"elementMenu\" id=\"activityOnProgress\" name=\"activityOnProgress\" style=\"width:101.5%;\" title=\"Choose Your Activity\">";
								$menuActOnProgress.="<option value=\"00000000000\" >-- PLEASE SELECT --</option>";
								
								$query = $CKoneksi->mysqlQuery("SELECT idactivity, activity, status, tanggal, bulan, tahun, DATE(CONCAT(tahun,'-',bulan,'-', tanggal)) AS dateActMerg FROM tblactivity WHERE (ownerid='".$userIdLogin."') AND (status = 'onprogress' OR status = 'postpone') AND (datefinish = 0000-00-00) AND referidactivity='0000000000' AND (deletests=0) AND DATE(CONCAT(tahun,'-',bulan,'-', tanggal)) <= DATE('".$dateActDBFormat."') AND DATE(CONCAT(tahun,'-',bulan,'-', tanggal)) > DATE('".$tglStartPrevActDBFormat."') ORDER BY dateActMerg;");
								while($row = $CKoneksi->mysqlFetch($query))
								{
									$statusActByPrefer = $CDailyAct->statusActByPrefer($row['idactivity'], $userIdLogin); // CARI STATUS DARI IDACTIVITY TSB BERDASARKAN REFERIDACTICITY
									$sel = "";
									$StrFinish = strpos($statusActByPrefer, "finish"); // CARI STRING FINISH ADA TIDAK DALAM ARRAY $statusActByPrefer
									if($StrFinish === false || $referIdActivity == $row['idactivity']) 
									{
										if($referIdActivity == $row['idactivity'])
										{
											$sel = " selected";
										}
										$date= ucfirst(strtolower($CPublic->bulanSetengah($row['bulan'], "eng")." ".$row['tanggal'].", ".$row['tahun']));
										$menuActOnProgress.= "<option value=\"".$row['idactivity']."\" ".$sel." >".$date." | ".$row['activity']."</option>";
									} 					
								}
								$menuActOnProgress.="</select>";
								echo $menuActOnProgress;
							}
						?>
                        <!-- ==END OF== Edit activity yang memiliki Refer ID Activity-->
                        <td id="ajaxInput2" align="left">
                        <!-- == START == Edit activity yang tidak memiliki Refer ID Activity-->
                        <?php
							if($referIdActivity == 00000000000){
                        ?>
                        	 &nbsp;<button type="button" class="btnStandarKecil" id="btnChooseAct" onClick="tampilSelection('chooseAct3', 'ajaxInput1');tampilSelection('chooseAct4', 'ajaxInput2');document.getElementById('statusBtnChooseAct').value='menuAct';" style="width:127px;" title="Choose Previous Activity">
                            <table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
                            <tr>
                            	<td align="center"><img src="../../picture/Search-blue-32.png"/ height="20"/> </td>
                                <td align="center">Choose Activity</td>
                            </tr>
                            </table>
                        </button>
                        <!-- ==END OF== Untuk edit activity yang tidak memiliki Refer ID Activity-->
                        <!-- == START == Edit activity yang memiliki Refer ID Activity-->
                        <?php
							}
							if($referIdActivity != 00000000000)
							{
						?>
                        &nbsp;<button type="button" class="btnStandarKecil" id="btnChooseAct" onClick="tampilSelection('typeAct3', 'ajaxInput1');tampilSelection('typeAct4', 'ajaxInput2');document.getElementById('statusBtnChooseAct').value='teksAct';" style="width:127px;" title="Type a New/Edit Activity">
                            <table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
                            <tr>
                            	<td align="center"><img src="../../picture/Pencil-blue-32.png" height="20"/> </td>
                                <td align="center">Type Activity</td>
                            </tr>
                            </table>
                        </button>
                        <?php
							}
						?>
                        <input type="hidden" id="activityTemp" name="activityTemp" value="<?php echo $activityTemp; ?>">
                        <!-- ==END OF== Edit activity yang memiliki Refer ID Activity-->
                        </td> 
                    </tr>
                    <tr valign="top">
                   	    <td align="left">Related Information</td>
                        <td align="left">
                            <textarea class="elementDefault" id="relatedInfo" name="relatedInfo" style="width:99%;height:40px;"><?php echo $relatedInfo; ?></textarea></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr valign="top">
                    	<td rowspan="7">
                        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" class="tabelBorderAll teksLvlFolder" bgcolor="#F0F9FF" style="color:#666;">
                        	<tr>
                            	<td align="center" height="30" bgcolor="#DDF0FF">Also used in</td>
                            </tr>
                            <tr>
                            	<td height="5"></td>
                            </tr>
                            <?php
							$htmlCariReferId = "";
//CARI TANGGAL ACTIVITY YANG MENGANDUNG IDACTIVITY YANG PERTAMA KALI DIBUAT
// JIKA EDIT ACTIVITY BUKAN ACTIVITY PERTAMA KALI DIBUAT / ACTIVITY YANG MENERUSKAN ACTIVITY SEBELUMNYA
							$nilaiReferIdAct = $referIdActivity; 
							if($referIdActivity == 00000000000)
							{
								$nilaiReferIdAct = $idactivityGet; // JIKA EDIT ACTIVITY PADA ACTIVITY PERTAMA KALI DIBUAT
							}
							$queryCariReferId = $CKoneksi->mysqlQuery("SELECT CONCAT(tanggal,'/',bulan,'/',tahun) AS dateActMerg FROM tblactivity WHERE ownerid='".$userIdLogin."' AND referidactivity='".$nilaiReferIdAct."' AND deletests = 0 ORDER BY bulan,tanggal;");
							while($row = $CKoneksi->mysqlFetch($queryCariReferId))
							{
// JIKA TANGGAL SEKARANG TIDAK SAMA DENGAN TANGGAL YANG TERMASUK ACTIVITY YANG MENERUSKAN ACTIVITY SEBELUMNYA 
								if($dateActGet != $row['dateActMerg'])
								{
									$htmlCariReferId.= "
								<tr>
									<td>&nbsp;&bull; <span onClick=\"klikAlsoUsed('".$row['dateActMerg']."');\" class=\"formInput\" style=\"text-decoration:underline;color:#00F;font-weight:bold;\" onMouseOver=\"this.style.color='#000080';\" onMouseOut=\"this.style.color='#00F';\">".$row['dateActMerg']."</span></td>
								</tr>";
								}
							}
							echo $htmlCariReferId;
							?>
                            <tr>
                            	<td>&nbsp;</td>
                            </tr>
                       	</table>                        
                        </td>
                        
                        <td colspan="3">
                        <table cellpadding="0" cellspacing="5" width="100%" border="0" class="formInput" style="cursor:default;">
                        	<tr>
                            	<td width="17%" align="left">Status</td>
                                <td width="66%" align="left">
                                    <select class="elementMenu" id="statusAct" name="statusAct" style="font-size:12px;" title="Choose Work Status">
                                    <?php
                                        echo $CDailyAct->menuStatus($statusAct);
                                    ?>
                                    </select>
                                     &nbsp;<span style="color:#C30;"> Last change by <?php echo $statusChangeByName; ?></span>
                                </td>
                                <td width="17%">&nbsp;</td>
                            </tr>
                            
                            <tr valign="top">
                                <td align="left">Problem Identification</td>
                                <td align="left"><textarea class="elementDefault" id="problemIdent" name="problemIdent" style="width:99%;height:40px;"><?php echo $problemIdent; ?></textarea></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr valign="top">
                                <td align="left">Corrective Action</td>
                                <td align="left"><textarea class="elementDefault" id="corrective" name="corrective" style="width:99%;height:40px;"><?php echo $corrective; ?></textarea></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr valign="top">
                                <td align="left">KPI Number</td>
                                <td>
                                
                                <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="13%" align="left">
                                    <input type="text" class="elementDefault" id="kpiNumber" name="kpiNumber" style="width:50px;" maxlength="3" value="<?php echo $kpiNumber; ?>">
                                    </td>
                                    <td width="87%" align="left"><!-- start === Animated Collapsible DIV contents-->
                                    <a id="hrefKPI" href="javascript:animatedcollapse.toggle('jason')"></a>  
                                        <button type="button" class="btnStandarKecil" style="width:90px;" title="Open KPI List" onClick="document.getElementById('hrefKPI').click();">
                                            <table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
                                            <tr>
                                                <td align="center"><img src="../../picture/Metro-Tasks-Blue-32.png" height="20" border="0"/> </td>
                                                <td align="center">KPI List</td>
                                            </tr>
                                            </table>
                                        </button>
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" height="0">
                                        <div class="elementDefault" id="jason" style="display:none;">
                                            <table width="100%">
                                            <input type="hidden" id="idTrSeb" name="idTrSeb">
                                            <input type="hidden" id="tdKpiListSeb" name="tdKpiListSeb">
                                            <?php
                                                $html = "";
                                                $urutan = 1;
                                                $i=0;
                                                $query = $CKoneksi->mysqlQuery("SELECT * FROM tblkpi WHERE kddiv = '".$kdDivUser."' AND deletests=0 ORDER BY urutan ASC");
                                                while($row = $CKoneksi->mysqlFetch($query))
                                                {
                                                    $kpiSatuEnter = "";
                                                    $kpiDuaEnter = "";
                                                    $kpitigaEnter = "";
                                                    
                                                    if($row['kpidua']!="")
                                                    {
                                                        $kpiDuaEnter = "<tr class=\"fontMyFolderList\" valign=\"top\">
                                                                            <td width=\"93%\" valign=\"top\">".$row['kpidua']."</td>
                                                                        </tr>";
                                                    }
                                                    if($row['kpitiga']!="")
                                                    {
                                                        $kpitigaEnter = "<tr class=\"fontMyFolderList\" valign=\"top\">
                                                                            <td width=\"93%\" valign=\"top\">".$row['kpitiga']."</td>
                                                                        </tr>";
                                                }
                                                $i++;
                                                $html.= "";
                                            ?>
                                                 <tr onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#f5f5f5';" onClick="onClickTr('<?php echo $i; ?>');document.getElementById('hrefKPI').click();"  id="tr<?php echo $i; ?>" valign="top">
                                                    <td class="tdMyFolder">
                                                        <table width="100%" cellspacing="0">
                                                        <tr class="fontMyFolderList">
                                                            <td width="100%" id="tdKpiList<?php echo $i; ?>">
                                                            <table  width="100%" cellspacing="0">
                                                                <tr class="fontMyFolderList" valign="top">
                                                                    <td rowspan="3" width="7%" align="center" style="color:#000080;font-weight:bold;font-family:Tahoma;"><?php echo $urutan++; ?></td>
                                                                    <?php echo $kpiSatuEnter; ?>
                                                                    <td width="93%" valign="top"><?php echo $row['kpisatu']; ?></td>
                                                                </tr>
                                                                <?php echo $kpiDuaEnter; ?><?php echo $kpitigaEnter; ?>
                                                            </table>
                                                            </td>
                                                        </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                               <?php
                                                    $html.= "";	
                                                }
                                                echo $html;
                                                ?>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                </table>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                            	<td width="17%" align="left">Project</td>
                                <td width="66%" align="left">
                                    <select class="elementMenu" id="project" name="project" style="font-size:12px;" title="Choose Project">
                                    <option value="">-</option>
                                    <?php
									$sel = "";
									if($project == "pheonwj")
									{
										$sel = "selected";
									}
									?>
                                    <option value="pheonwj" <?php echo $sel;?>>PHE ONWJ</option>
                                    </select>
                                </td>
                                <td width="17%">&nbsp;</td>
                            </tr>
                            <tr valign="top">
                                <td align="left">Superior Comment</td>
                                <td align="left"><textarea class="elementDefault" id="spvComment" name="spvComment" style="width:99%;height:40px;" readonly><?php echo $spvComment; ?></textarea></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr valign="top">
                                <td align="left">Comment Response</td>
                                <td align="left"><textarea class="elementDefault" id="responComment" name="responComment" style="width:99%;height:40px;"><?php echo $responComment; ?></textarea></td>
                                <td align="right">
                                </td>
                            </tr>
                        </table>
                        </td>

                    </tr>
                    
                    
<!-- NEW =================================================================================================================== -->
                    <!--<tr valign="top">
                    	<td>&nbsp;</td>
                        <td colspan="3">&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>-->
<!--  END of NEW =========================================================================================================== -->                
                    
                    <tr><td align="center" valign="middle" colspan="5"><?php
						if($referIdActivity == 00000000000)
							{
								echo "<input type=\"hidden\" id=\"statusBtnChooseAct\" name=\"statusBtnChooseAct\" value=\"teksAct\"/>";
							}
						if($referIdActivity != 00000000000)
							{
								echo "<input type=\"hidden\" id=\"statusBtnChooseAct\" name=\"statusBtnChooseAct\" value=\"menuAct\"/>";
							}
					?>
                        <input type="hidden" id="activityOnProgress2" name="activityOnProgress2" value="<?php echo $referIdActivity; ?>"/>
                        <div id="idApprvAdaTidak"><input type="hidden" id="apprvAdaTidak" name="apprvAdaTidak"/> </div>
                        <input type="hidden" id="activity2" name="activity2"/>
                        </td></tr>
                    </table>  
                </td>
            </tr>
            
            </form>
            </table>
        </div>
    </td>
</tr>           

<tr><td height="5"></td></tr>

<tr valign="top">

	<td class="tdMyFolder" colspan="2" bgcolor="#FFFFFF" height="57" valign="middle" style="cursor:default;" align="left">&nbsp;
		<button class="btnStandarKecil" type="button" style="width:90px;height:50px;" onClick="tutup();" title="Close Edit Activity Window">
        	<table width="100%" height="100%">
            <tr>
            	<td align="center"><img src="../../picture/Metro-Shut-Down-Blue-32.png" height="23"/> </td>
            </tr>
            <tr>
            	<td align="center">CLOSE</td>
            </tr>
        	</table>
        </button>
        &nbsp;
        <button onClick="pilihBtnSave();" style="width:90px;height:50px;" <?php echo $disSave; ?> title="Save Edit Acitivy">
            <table border="0" width="100%" height="100%">
            <tr>
                <td align="center"><img src="../../picture/Floppy-Disk-blue-32.png" height="23"/> </td> 
            </tr>
            <tr>
                <td align="center">SAVE</td>
            </tr>
            </table>
        </button>
        &nbsp;
        <button <?php echo $disSave; ?> onClick="formNewAct.reset();" style="width:90px;height:50px;" title="UNDO (Back to Current Condition)">
            <table border="0" width="100%" height="100%">
            <tr>
                <td align="center"><img src="../../picture/Button-Synchronize-blue-32.png" height="23"/> </td> 
            </tr>
            <tr>
                <td align="center">RESET</td>
            </tr>
            </table>
        </button>
        <span class="report"><?php echo $lockMsg;?></span>
        <blink><span id="errorMsg" class="errorMsg" style="text-decoration:blink;">&nbsp;</span></blink>
    </td>
</tr>
<div style="margin-top:425px;margin-left:10px;position:absolute;">
<?php
	echo $btnPrevJob;
?>
</div>
<div style="margin-top:425px;margin-left:870px;position:absolute;">
<?php
	echo $btnNextJob;
?>
</div>
</table>
</center>
</body>

<script language="javascript">
<?php
if($aksiGet == "simpanEditAct")
{
	echo "exit();";
}
?>
</script>
</HTML>