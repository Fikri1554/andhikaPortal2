<!DOCTYPE HTML>
<?php
require_once("../../config.php");

$idactivityGet = $_GET['idactivity'];
$empNoGet = $_GET['empNo'];
if($idactivityGet == "")
{
	$ownerId = $CLogin->detilLoginByEmpno($empNoGet, "userid");
	$dateActGet = $_GET['dateAct'];
	$tglAct =  substr($dateActGet,0,2);
	$blnAct =  substr($dateActGet,3,2);
	$thnAct =  substr($dateActGet,6,4);
	
	$sql = $CKoneksi->mysqlQuery("SELECT idactivity FROM tblactivity WHERE tanggal='".$tglAct."' AND bulan='".$blnAct."' AND tahun='".$thnAct."' AND deletests=0 AND ownerid=".$ownerId."");
	$row = $CKoneksi->mysqlFetch($sql);
	
	$idactivityGet = $row['idactivity'];
}

$kdDivUser = $CLogin->detilLogin($userIdLogin, "kddiv");
$halamanGet = $_GET['halaman']; //detail subordinate / bukan

$statusChangeByName = $CDailyAct->detilAct($idactivityGet, "statuschangebyname");

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
	$ownerIdDb = $CDailyAct->detilAct($idactivityGet, "ownerid");
	$subordinateName = $CDailyAct->detilAct($idactivityGet, "ownername");
	
	$dateActGet = $tglAct."/".$blnAct."/".$thnAct;
	if($_GET['dateAct'] != "")
	{
		$dateActGet = $_GET['dateAct'];
	}
	//$finish = $CDailyAct->detilAct($idactivityGet, "datefinish");
	
// ========== START === BOSS READ JOB & Comment Respon ===============================================
$ownerId = $CDailyAct->detilAct($idactivityGet, "ownerid");
$ownerEmpNo = $CLogin->detilLogin($ownerId, "empno"); // empno dari subordinate
$empAtasanLangsung = $CEmployee->cariAtasanLangsung($ownerEmpNo);
if($userEmpNo == $empAtasanLangsung)// jika userlogin merupakan atasan langsung begitu baca job subordinate beri tanda Y pada tblactivity di database
{
	$responComment = $CDailyAct->detilAct($idactivityGet, "responcomment");
	
	$CKoneksi->mysqlQuery("UPDATE tblactivity SET bosread='Y', readbyid='".$userIdLogin."', readbyname='".$userFullnm."', updusrdt='".$CPublic->userWhoAct()."' WHERE ownerid='".$ownerId."' AND tanggal='".$tglAct."' AND bulan='".$blnAct."' AND tahun='".$thnAct."' AND deletests=0");
	$CHistory->updateLog($userIdLogin, "Baca Job Daily dari ".$CLogin->detilLoginByEmpno($ownerEmpNo, "userfullnm")." (ownerid = <b>".$subordinateId."</b>, tanggal=<b>".$tglAct."</b>, bulan=<b>".$blnAct."</b>, tahun=<b>".$thnAct."</b>)");
	
	$CKoneksi->mysqlQuery("UPDATE tblactivity SET bosreadjob='Y', oldresponcomment='".mysql_real_escape_string( $responComment )."', updusrdt='".$CPublic->userWhoAct()."' WHERE idactivity=".$idactivityGet." AND deletests=0");
	$CHistory->updateLog($userIdLogin, "Baca Job Daily Activity (idactivity = <b>".$idactivityGet."</b>)");
}
// ========== END === OF BOSS READ ==================================================

// ========== START === BERIKAN STATUS JIKA SUPERIOR COMMENT SUDAH DIBACA ============================
if($userEmpNo == $ownerEmpNo )
{	
	$CKoneksi->mysqlQuery("UPDATE tblactivity SET oldspvcomment='".mysql_real_escape_string( $spvComment )."' WHERE idactivity='".$idactivityGet."' AND deletests=0;");
}
// ========== END === OF BERIKAN STATUS JIKA SUPERIOR COMMENT SUDAH DIBACA ===========================

// ========== START === Custom BOSS READ JOB & Comment Respon ===============================================
if($userSubCustom == "Y")
{
	if($CEmployee->detilSubCustomByUser($userIdLogin, $ownerId, "dailyact_direct") == "Y")
	{
		$responComment = $CDailyAct->detilAct($idactivityGet, "responcomment");
		$CKoneksi->mysqlQuery("UPDATE tblactivity SET bosreadjob='Y', oldresponcomment='".mysql_real_escape_string( $responComment )."', updusrdt='".$CPublic->userWhoAct()."' WHERE idactivity=".$idactivityGet." AND deletests=0");
		$CHistory->updateLog($userIdLogin, "Baca Job Daily Activity (idactivity = <b>".$idactivityGet."</b>)");
	}
}
// ========== END OF === Custom BOSS READ JOB & Comment Respon ===============================================

// ========== START === Approve / dissaprove ============================
$appOrDis = "";
if($halamanGet == "subordinate")
{
	$bosApprove = $CDailyAct->detilAct($idactivityGet, "bosapprove");
	$appOrDis = "DISAPPROVED";
	if($bosApprove == "Y")
	{
		$appOrDis = "APPROVED";
	}
}
// ========== END OF === Approve / dissaprove ============================

	//=========== START === Fungsi Prev & Next Button ======================================
	$urutanCurrent = $CDailyAct->detilAct($idactivityGet, "urutan");
	$idActivityPrev = $CDailyAct->btnPrevNextMonth($urutanCurrent, $userIdLogin, $tglAct, $blnAct, $thnAct, "prev");
	$idActivityNext = $CDailyAct->btnPrevNextMonth($urutanCurrent, $userIdLogin, $tglAct, $blnAct, $thnAct, "next");
	if($halamanGet == "subordinate")
	{
		$idActivityPrev = $CDailyAct->btnPrevNextMonth($urutanCurrent, $ownerIdDb, $tglAct, $blnAct, $thnAct, "prev");
		$idActivityNext = $CDailyAct->btnPrevNextMonth($urutanCurrent, $ownerIdDb, $tglAct, $blnAct, $thnAct, "next");
	}
	$btnPrevJob = $CDailyAct->prevJobMonth($idActivityPrev,$halamanGet);
	$btnNextJob = $CDailyAct->nextJobMonth($idActivityNext,$halamanGet);

	//=========== END OF === Fungsi Prev & Next Button======================================
	
	$dateCreated = $CDailyAct->detilAct($referIdActivity, "CONCAT(tanggal,'/',bulan,'/',tahun)");
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
}

function exit()
{
	var paramCari = parent.document.getElementById('paramText').value;
	if(paramCari.replace(/ /g,"") == "")
	{	parent.document.getElementById('aksi').value = "";	}
	else
	{	parent.document.getElementById('aksi').value = "cari";	}
	var aksi = parent.document.getElementById('aksi').value;
	
	parent.tb_remove(false);
	parent.document.getElementById('iframeHal').src = "";
	parent.document.getElementById('iframeHal').src = "templates/halMonthlyActList.php?empNo=<?php echo $empNoGet; ?>&bulan=<?php echo $blnAct; ?>&tahun=<?php echo $thnAct; ?>&aksi="+aksi+"&paramCari="+paramCari;
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
</script>

<body bgcolor="#F8F8F8" onLoad="setupMask();" bottommargin="3" topmargin="3">
<center>

<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
<tr valign="top">
	<td height="25" width="50%" align="left">
    	<span class="teksLvlFolder" style="color:#666;">Date Activity : </span>
        <span class="formInput" style="text-decoration:underline;color:#00F;font-weight:bold;"><?php echo $dateActGet; ?></span>
        <?php
			if($halamanGet == "subordinate")
			{
		?>
        &nbsp;|&nbsp;
        <span class="teksLvlFolder" style="color:#666;">Subordinate : </span>
        <span class="formInput" style="text-decoration:underline;color:#00F;font-weight:bold;"><?php echo $subordinateName; ?></span>
        <?php } ?>
    </td>
    <td align="right">
    <span class="teksLvlFolder" style="color:#999;font-size:16px;font-style:italic;">
    	<b>:: Edit 
			<?php
			if($halamanGet == "subordinate")
			{
				echo "Subordinate";
			}
			?> Activity ::
        </b>
    </span>&nbsp;</td>
</tr>

<tr valign="top">
    <td class="tdMyFolder" bgcolor="#FFFFFF" colspan="2" align="center" valign="top" width="100%">
    	<div style="width:890px;height:457px;overflow:scroll;overflow-x: hidden;top: expression(offsetParent.scrollTop);">
        
            <table cellpadding="0" cellspacing="0" height="100%" width="99%" border="0">
            <tr valign="top">
                <td align="center" valign="top" height="130">
                
                    <table cellpadding="0" cellspacing="5" width="100%" class="formInput" style="cursor:default;">
                    
                    <tr valign="top">
                    	<td width="15%" class="teksLvlFolder" style="text-decoration:underline;"><?php echo $appOrDis; ?></td>
                        <td width="15%" align="left">Time</td>
                        <td width="55%" align="left">
                        From&nbsp;&nbsp;&nbsp;
                        <input type="text" class="elementDefault" id="fromTime" name="fromTime" style="width:31px;" value="<?php echo $fromTime; ?>" readonly>
                        &nbsp;&nbsp;&nbsp;&nbsp;To&nbsp;&nbsp;&nbsp;
                        <input type="text" class="elementDefault" id="toTime" name="toTime" style="width:31px;" value="<?php echo $toTime; ?>" readonly>
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
									echo "&nbsp;&bull; <span onClick=\"parent.openThickboxWindow('".$referIdActivity."','".$halamanGet."','".$dateCreated."');\" class=\"formInput\"  style=\"text-decoration:underline;color:#00F;font-weight:bold;\" onMouseOver=\"this.style.color='#000080';\" onMouseOut=\"this.style.color='#00F';\">".$dateCreated."</span>";
								}
								?>
                                </td>
                            </tr>
                            <tr>
                            	<td>&nbsp;</td>
                            </tr>
                            </table>
                        </td>
                        <td align="left">Activity</td>
                        <td id="ajaxInput1" align="left">
                        <input type="text" class="elementDefault" id="activity" name="activity" style="width:99%;" value="<?php echo $CPublic->konversiQuotes1($activity); ?>" readonly></td> 
                        <td id="ajaxInput2">&nbsp;</td> 
                    </tr>
                    <tr valign="top">
                   	    <td align="left">Related Information</td>
                        <td align="left">
                            <textarea class="elementDefault" id="relatedInfo" name="relatedInfo" style="width:99%;height:40px;" readonly><?php echo $relatedInfo; ?></textarea></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr valign="top">
                    	<td rowspan="6">
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
							$ownerId = $userIdLogin;
							if($halamanGet == "subordinate")
							{
								$ownerId = $ownerIdDb;
							}
							$queryCariReferId = $CKoneksi->mysqlQuery("SELECT CONCAT(tanggal,'/',bulan,'/',tahun) AS dateActMerg FROM tblactivity WHERE ownerid='".$ownerId."' AND referidactivity='".$nilaiReferIdAct."' AND deletests = 0 ORDER BY bulan,tanggal;");//query ownerid vs useridlogin untuk subordinate/not Subordinate
							while($row = $CKoneksi->mysqlFetch($queryCariReferId))
							{
// JIKA TANGGAL SEKARANG TIDAK SAMA DENGAN TANGGAL YANG TERMASUK ACTIVITY YANG MENERUSKAN ACTIVITY SEBELUMNYA 
								if($dateActGet != $row['dateActMerg'])
								{
									$dateActMerg = $row['dateActMerg'];
									$tglActMerg =  substr($dateActMerg,0,2);
									$blnActMerg =  substr($dateActMerg,3,2);
									$thnActMerg =  substr($dateActMerg,6,4);
									$queryCariIdActivity = $CKoneksi->mysqlQuery("SELECT idactivity FROM tblactivity WHERE  referidactivity='".$nilaiReferIdAct."' AND tanggal='".$tglActMerg."' AND bulan='".$blnActMerg."' AND tahun='".$thnActMerg."' AND deletests = 0");
									$rowId = $CKoneksi->mysqlFetch($queryCariIdActivity);
						
									$htmlCariReferId.= "
								<tr>
									<td>&nbsp;&bull; <span onClick=\"parent.openThickboxWindow('".$rowId['idactivity']."','".$halamanGet."','".$row['dateActMerg']."');\" class=\"formInput\" style=\"text-decoration:underline;color:#00F;font-weight:bold;\" onMouseOver=\"this.style.color='#000080';\" onMouseOut=\"this.style.color='#00F';\">".$row['dateActMerg']."</span></td>
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
                            <select class="elementMenu" id="statusAct" name="statusAct" style="font-size:12px;" title="Choose Work Status" readonly>
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
                        <td align="left"><textarea class="elementDefault" id="problemIdent" name="problemIdent" style="width:99%;height:40px;" readonly><?php echo $problemIdent; ?></textarea></td>
                    </tr>
                    <tr valign="top">
                    	<td align="left">Corrective Action</td>
                        <td align="left"><textarea class="elementDefault" id="corrective" name="corrective" style="width:99%;height:40px;" readonly><?php echo $corrective; ?></textarea></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr valign="top">
                    	<td align="left">KPI Number</td>
                        <td align="left">
                            <input type="text" class="elementDefault" id="kpiNumber" name="kpiNumber" style="width:50px;" maxlength="3" value="<?php echo $kpiNumber; ?>" readonly>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="17%" align="left">Project</td>
                        <td width="66%" align="left">
                            <select class="elementMenu" id="project" name="project" style="font-size:12px;" title="Choose Project">
                            <option value="">-</option>
                            <?php
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
                        <td align="left"><textarea class="elementDefault" id="responComment" name="responComment" style="width:99%;height:40px;" readonly><?php echo $responComment; ?></textarea></td>
                        <td align="right">
                        </td>
					</tr>
            	</table>
            	</td>
                    
                    </tr>
                    </table>  
                </td>
            </tr>
            </table>
        </div>
    </td>
</tr>           

<tr><td height="5"></td></tr>

<tr valign="top">

	<td class="tdMyFolder" colspan="2" bgcolor="#FFFFFF" height="65" valign="middle" align="left" style="cursor:default;">&nbsp;
		<button class="btnStandarKecil" type="button" style="width:90px;height:55px;" onClick="exit();" title="Close Edit Activity Window">
        	<table width="100%" height="100%">
            <tr>
            	<td align="center"><img src="../../picture/Metro-Shut-Down-Blue-32.png" height="25"/> </td>
            </tr>
            <tr>
            	<td align="center">CLOSE</td>
            </tr>
        	</table>
        </button>
    </td>
</tr>
<div style="margin-top:425px;margin-left:10px;position:absolute;">
<?php
	echo $btnPrevJob;
?>
</div>
<div style="margin-top:425px;margin-left:810px;position:absolute;">
<?php
	echo $btnNextJob;
?>
</div>
</table>
</center>
</body>
</HTML>