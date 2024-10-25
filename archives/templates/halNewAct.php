<!DOCTYPE HTML>
<?php
require_once("../../config.php");

$halamanGet = $_GET['halaman'];
$dateActGet = $_GET['dateAct'];
//echo $dateActGet;
$kdDivUser = $CLogin->detilLogin($userIdLogin, "kddiv");
$tutupWindow = "";

if($aksiGet == "simpanNewAct")
{
	$dateActGet = $_GET['dateAct'];	
	$tglAct =  substr($dateActGet,0,2);
	$blnAct =  substr($dateActGet,3,2);
	$thnAct =  substr($dateActGet,6,4);
	
	$fromTime = $_POST['fromTime']; 
	$toTime = $_POST['toTime']; 
	$statusBtnChooseAct = $_POST['statusBtnChooseAct'];
	
	//JIKA YANG DIPILIH ACTIVITYNYA BERUPA TEKS INPUT
	if($statusBtnChooseAct == "teksAct"){
		$activity = mysql_real_escape_string( $_POST['activity2'] );
	}
	//JIKA YANG DIPILIH ACTIVITYNYA BERUPA TEKS INPUT
	if($statusBtnChooseAct == "menuAct"){
		$idActivityOnProgress = $_POST['activityOnProgress2'];
		$activity = mysql_real_escape_string($CDailyAct->detilAct($idActivityOnProgress,'activity'));
	}
	
	
	$relatedInfo = mysql_real_escape_string( $_POST['relatedInfo'] ); 
	$statusAct = $_POST['statusAct']; 
	$problemIdent = mysql_real_escape_string( $_POST['problemIdent'] ); 
	$corrective = mysql_real_escape_string( $_POST['corrective'] ); 
	$kpiNumber = $_POST['kpiNumber']; 
	$project = $_POST['project'];  
	$anotherAct = mysql_real_escape_string( $_POST['anotherAct'] );  
	
	$maxUrutan = $CDailyAct->detilActByDay($userIdLogin, $tglAct, $blnAct, $thnAct, "MAX(urutan)");
	$urutan = $maxUrutan + 1;
	
	$bosRead = $CDailyAct->detilActByDay($userIdLogin, $tglAct, $blnAct, $thnAct, "bosread"); // bos read untuk perday bukan perjob
	$nilaiBosRead = "N";
	if($bosRead != "")
	{
		$nilaiBosRead = "Y";
	}
	
	$idRevisi = $CDailyAct->detilActByDay($userIdLogin, $tglAct, $blnAct, $thnAct, "idrevisi");
	$revisiKe = "0";
	if($idRevisi != "00000" || $idRevisi != "")
	{
		$revisiKe = $CDailyAct->detilRevisi($idRevisi, "revisike");
	}
	$dateFinish = "0000-00-00";
	if($statusAct == "finish"){
		$dateFinish = $thnAct."-".$blnAct."-".$tglAct;
	}
	//echo "Act : ".$activity;
	$CKoneksi->mysqlQuery("INSERT INTO tblactivity (urutan, idrevisi, revisike, ownerid, ownername, tanggal, bulan, tahun, fromtime, totime, activity, relatedinfo, status, project, datefinish, referidactivity, statuschangebyid, statuschangebyname, problemident, corrective, kpinumber, bosread, addusrdt)VALUES ('".$urutan."', '".$idRevisi."', '".$revisiKe."', '".$userIdLogin."', '".$userFullnm."', '".$tglAct."', '".$blnAct."', '".$thnAct."', '".$fromTime."', '".$toTime."', '".$activity."', '".$relatedInfo."', '".$statusAct."', '".$project."', '".$dateFinish."', '".$idActivityOnProgress."', '".$userIdLogin."', '".$userFullnm."', '".$problemIdent."', '".$corrective."', '".$kpiNumber."', '".$nilaiBosRead."', '".$CPublic->userWhoAct()."');");
	$lastInsertId = mysql_insert_id();
	if($statusBtnChooseAct == "teksAct"){
		$CHistory->updateLog($userIdLogin, "Buat Daily Activity baru (idactivity = <b>".$lastInsertId."</b>, Date = <b>".$dateActGet."</b>, From time = <b>".$fromTime."</b>, To time = <b>".$toTime."</b>, Activity = <b>".$activity."</b>, Related Information = <b>".$relatedInfo."</b>, Status = <b>".$statusAct."</b>, Refer Id Activity = <b>".$idActivityOnProgress."</b>, Problem identification = <b>".$problemIdent."</b>, Corrective = <b>".$corrective."</b>, KPI Number = <b>".$kpiNumber."</b>, <b>project = ".$project."</b>)");
	}
	if($statusBtnChooseAct == "menuAct"){	
		$CHistory->updateLog($userIdLogin, "Buat Daily Activity baru berdasarkan Daily Activity lain (idactivity = <b>".$lastInsertId."</b>, Date = <b>".$dateActGet."</b>, From time = <b>".$fromTime."</b>, To time = <b>".$toTime."</b>, Activity = <b>".$activity."</b>, Related Information = <b>".$relatedInfo."</b>, Status = <b>".$statusAct."</b>, Refer Id Activity = <b>".$idActivityOnProgress."</b>, Problem identification = <b>".$problemIdent."</b>, Corrective = <b>".$corrective."</b>, KPI Number = <b>".$kpiNumber."</b>, <b>project = ".$project."</b>)");
	}
	
	if($anotherAct == "on")
	{
		$tutupWindow = "tidak";
	}
	if($anotherAct == "")
	{
		$tutupWindow = "ya";
	}
}
?>

<script src="../../js/jquery-1.4.3.js"></script>
<script src="../../js/main.js"></script>
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
	dateMaskToTime.validationMessage = errorMessage;
	
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
			document.getElementById('activity2').focus(); 
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
		var delay=1000//1 seconds
		
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
	if(aksi == "chooseAct1")
	{
		var btnChooseAct = document.getElementById('btnChooseAct').value;
		var parameters="halaman="+aksi+"&btnChooseAct="+btnChooseAct+"&dateAct=<?php echo $dateActGet; ?>";
	}
	if(aksi == "chooseAct2")
	{
		var activity = encodeURIComponent(document.getElementById('activity').value);
		//var activity = document.getElementById('activity').value
		var btnChooseAct = document.getElementById('btnChooseAct').value;
		var parameters="halaman="+aksi+"&btnChooseAct="+btnChooseAct+"&activity="+activity;
	}
	if(aksi == "typeAct1")
	{
		var activity = encodeURIComponent(document.getElementById('activity').value);
		//var activity = document.getElementById('activity').value;
		var btnChooseAct = document.getElementById('btnChooseAct').value;
		var parameters="halaman="+aksi+"&btnChooseAct="+btnChooseAct+"&activity="+activity;
	}
	if(aksi == "typeAct2")
	{
		var btnChooseAct = document.getElementById('btnChooseAct').value;
		var parameters="halaman="+aksi+"&btnChooseAct="+btnChooseAct;
	}
	mypostrequest.open("POST", "../halPostFold.php", true);
	mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	mypostrequest.send(parameters);
}
function pilihBtnLeave (){
	var answer  = confirm("Are you sure about Leave (Cuti) Status ?");
	if(answer){
		parent.tb_remove(false);
		parent.document.getElementById('iframeHal').src = "";
		parent.document.getElementById('iframeHal').src = "templates/halDailyActList.php?halaman=leaveStatus&dateAct=<?php echo $dateActGet; ?>";
	}else{
		return false;
	}
}
function pilihBtnSick (){
	var answer  = confirm("Are you sure about Sick Status?");
	if(answer){
		parent.tb_remove(false);
		parent.document.getElementById('iframeHal').src = "";
		parent.document.getElementById('iframeHal').src = "templates/halDailyActList.php?halaman=sickStatus&dateAct=<?php echo $dateActGet; ?>";
	}else{
		return false;
	}
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
    <td align="right"><span class="teksLvlFolder" style="color:#999;font-size:16px;font-style:italic;"><b>:: Create New Activity ::</b></span>&nbsp;</td>
</tr>

<tr valign="top">
    <td class="tdMyFolder" bgcolor="#FFFFFF" colspan="2" align="center" valign="top" width="100%" style="cursor:default;">
    	<div style="width:950px;height:454px;overflow:scroll;overflow-x: hidden;top: expression(offsetParent.scrollTop);background-color:#FFF;">
            <table cellpadding="0" cellspacing="0" height="100%" width="99%" border="0">
            <form action="halNewAct.php?aksi=simpanNewAct&dateAct=<?php echo $dateActGet; ?>" method="post" id="formNewAct" name="formNewAct">
            <tr valign="top">
                <td align="center" valign="top" height="130">
                
                    <table cellpadding="0" cellspacing="5" width="100%" class="formInput" border="0" style="cursor:default;">
                    
                    <tr valign="top">
                    	<td width="15%">&nbsp;</td>
                        <td width="15%" align="left">Time</td>
                        <td align="left">
                        From&nbsp;&nbsp;&nbsp;
                        <input type="text" class="elementDefault" id="fromTime" name="fromTime" style="width:31px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;To&nbsp;&nbsp;&nbsp;
                        <input type="text" class="elementDefault" id="toTime" name="toTime" style="width:31px;">
                        </td>
                        <td width="15%">&nbsp;</td>
                    </tr>
                    <tr valign="top">
                    	<td>&nbsp;</td>
                        <td align="left">
                        	Activity
                        	<a id="idHref" onClick="tampilLoad();"/>
                        </td>
                        <td id="ajaxInput1" style="height:28px;">
                            <div id="divLoad" style="position:fixed;margin-left:250px;display:none;">
                                <img src="../../picture/ajax-loader20.gif"/>
                            </div>
                        	<input type="text" class="elementDefault" id="activity" name="activity" style="width:99%;">
                        </td>
                      	<td id="ajaxInput2" align="left">
                        &nbsp;<button type="button" class="btnStandarKecil" id="btnChooseAct" onClick="tampilSelection('chooseAct1', 'ajaxInput1');tampilSelection('chooseAct2', 'ajaxInput2');document.getElementById('statusBtnChooseAct').value='menuAct';" style="width:127px;" title="Choose Previous Activity">
                            <table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
                            <tr>
                            	<td align="center"><img src="../../picture/Search-blue-32.png" height="20"/> </td>
                                <td align="center">Choose Activity</td>
                            </tr>
                            </table>
                        </button>
                        </td>
                    </tr>
                    <tr valign="top">
                    	<td>&nbsp;</td>
                        <td align="left">Related Information</td>
                        <td>
                            <textarea class="elementDefault" id="relatedInfo" name="relatedInfo" style="width:99%;height:40px;"></textarea></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr valign="top">
                    	<td>&nbsp;</td>
                        <td align="left">Status</td>
                        <td align="left">
                            <select class="elementMenu" id="statusAct" name="statusAct" style="font-size:12px;" title="Choose Work Status">
                            <?php
                                echo $CDailyAct->menuStatus("");
                            ?>
                            </select>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr valign="top">
                    	<td>&nbsp;</td>
                        <td align="left">Problem Identification</td>
                        <td><textarea class="elementDefault" id="problemIdent" name="problemIdent" style="width:99%;height:40px;"></textarea></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr valign="top">
                    	<td>&nbsp;</td>
                        <td align="left">Corrective Action</td>
                        <td><textarea class="elementDefault" id="corrective" name="corrective" style="width:99%;height:40px;"></textarea></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr valign="top">
                    	<td>&nbsp;</td>
                        <td align="left">KPI Number</td>
                        <td>
                        
                        <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="13%" align="left">
                            <input type="text" class="elementDefault" id="kpiNumber" name="kpiNumber" style="width:50px;" maxlength="3" >
                            </td>
                            <td width="87%" align="left"><!-- start === Animated Collapsible DIV contents-->
                                <a id="hrefKPI" href="javascript:animatedcollapse.toggle('jason')"></a>  
                                    &nbsp;<button type="button" class="btnStandarKecil" style="width:90px;" title="Open KPI List" onClick="document.getElementById('hrefKPI').click();">
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
                        	<td colspan="2">
                                <div class="elementDefault" id="jason" style="display:none;width:auto;">
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
                    <tr valign="top">
                        <td>&nbsp;</td>
                        <td align="left">Project</td>
                        <td align="left">
                            <select class="elementMenu" id="project" name="project" style="font-size:12px;" title="Choose Project">
                            <option value="">-</option>
                            <option value="pheonwj">PHE ONWJ</option>
                            </select>
                        </td>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                    	<td>&nbsp;</td>
                    	<td>&nbsp;</td>
                        <td align="left">
                        	<input type="checkbox" class="elementSearch" id="anotherAct" name="anotherAct" >&nbsp;Add another activity
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    </table>  
                    
                </td>
            </tr>
            <!--<tr><td height="20" align="center" valign="middle">&nbsp; <span id="errorMsg" class="errorMsg">&nbsp;</span></td></tr>-->
            <tr><td>
            	<div id="idApprvAdaTidak"><input type="hidden" id="apprvAdaTidak" name="apprvAdaTidak"/> </div>
            	<input type="hidden" id="statusBtnChooseAct" name="statusBtnChooseAct" value="teksAct"/>
                <input type="hidden" id="activityOnProgress2" name="activityOnProgress2"/>
                <input type="hidden" id="activity2" name="activity2"/>
            &nbsp;</td></tr>
            </form>
            </table>
        </div>
    </td>
</tr>           

<tr><td height="5"></td></tr>

<tr valign="top">
	<td class="tdMyFolder" colspan="2" bgcolor="#FFFFFF" height="65" alig align="left" valign="middle" style="cursor:default;">&nbsp;
		<button class="btnStandarKecil" type="button" style="width:90px;height:55px;" onClick="tutup();" title="Close Create New Activity Window">
        	<table width="100%" height="100%">
            <tr>
            	<td align="center"><img src="../../picture/Metro-Shut-Down-Blue-32.png" height="25"/> </td>
            </tr>
            <tr>
            	<td align="center">CLOSE</td>
            </tr>
        	</table>
        </button>
        &nbsp;
        <button class="btnStandarKecil" onClick="pilihBtnSave();" style="width:90px;height:55px;" title="Save New Acitivy">
            <table border="0" width="100%" height="100%">
            <tr>
                <td align="center"><img src="../../picture/Floppy-Disk-blue-32.png" height="25"/> </td> 
            </tr>
            <tr>
                <td align="center">SAVE</td>
            </tr>
            </table>
        </button>
        &nbsp;
        <button class="btnStandarKecil" onClick="formNewAct.reset();" style="width:90px;height:55px;" title="UNDO (Back to Current Condition)">
            <table border="0" width="100%" height="100%">
            <tr>
                <td align="center"><img src="../../picture/Button-Synchronize-blue-32.png" height="25"/> </td> 
            </tr>
            <tr>
                <td align="center">RESET</td>
            </tr>
            </table>
        </button>
        &nbsp;
        <button class="btnStandarKecil" onClick="pilihBtnLeave();" style="width:95px;height:55px;" title="Leave (Cuti) Status on Calendar Activity">
            <table border="0" width="100%" height="100%">
            <tr>
                <td align="center"><img src="../../picture/Shopping-Basket-blue-32.png" height="25"/> </td> 
            </tr>
            <tr>
                <td align="center">LEAVE / CUTI</td>
            </tr>
            </table>
        </button>
        &nbsp;
        <button class="btnStandarKecil" onClick="pilihBtnSick();" style="width:90px;height:55px;" title="Sick Status on Calendar Activity">
            <table border="0" width="100%" height="100%">
            <tr>
                <td align="center"><img src="../../picture/Weather-Thunder-blue-32.png" height="25"/> </td> 
            </tr>
            <tr>
                <td align="center">SICK</td>
            </tr>
            </table>
        </button>
        <blink><span id="errorMsg" class="errorMsg">&nbsp;</span></blink>
    </td>
</tr>
</table>
</center>
</body>

<script language="javascript">
<?php
if($tutupWindow == "tidak")
{
	//echo "parent.openThickboxWindow('', 'newAct')";
}
if($tutupWindow == "ya")
{
	echo "exit();";
}
?>
</script>
</HTML>	