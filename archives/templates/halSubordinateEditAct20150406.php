<?php
require_once("../../config.php");

$idactivityGet = $_GET['idactivity'];
$halamanGet = $_GET['halaman'];
$dateActGet = $_GET['dateAct'];

$subordinateId = $CDailyAct->detilAct($idactivityGet, "ownerid");
$subordinateName = $CDailyAct->detilAct($idactivityGet, "ownername");
$statusChangeByName = $CDailyAct->detilAct($idactivityGet, "statuschangebyname");

$ownerId = $CDailyAct->detilAct($idactivityGet, "ownerid");
$ownerEmpNo = $CLogin->detilLogin($ownerId, "empno"); // empno dari subordinate
$empAtasanLangsung = $CEmployee->cariAtasanLangsung($ownerEmpNo);

if($userEmpNo == $empAtasanLangsung)// jika userlogin merupakan atasan langsung begitu baca job subordinate beri tanda Y pada tblactivity di database
{
	$responComment = $CDailyAct->detilAct($idactivityGet, "responcomment");
	$CKoneksi->mysqlQuery("UPDATE tblactivity SET bosreadjob='Y', oldresponcomment='".mysql_real_escape_string( $responComment )."', updusrdt='".$CPublic->userWhoAct()."' WHERE idactivity=".$idactivityGet." AND deletests=0");
	$CHistory->updateLog($userIdLogin, "Baca Job Daily Activity (idactivity = <b>".$idactivityGet."</b>)");
}

if($userSubCustom == "Y")
{
	if($CEmployee->detilSubCustomByUser($userIdLogin, $subordinateId, "dailyact_direct") == "Y")
	{
		$responComment = $CDailyAct->detilAct($idactivityGet, "responcomment");
		$CKoneksi->mysqlQuery("UPDATE tblactivity SET bosreadjob='Y', oldresponcomment='".mysql_real_escape_string( $responComment )."', updusrdt='".$CPublic->userWhoAct()."' WHERE idactivity=".$idactivityGet." AND deletests=0");
		$CHistory->updateLog($userIdLogin, "Baca Job Daily Activity (idactivity = <b>".$idactivityGet."</b>)");
	}
	$disSaveSubCustom = "";
	if($CEmployee->detilSubCustomByUser($userIdLogin, $subordinateId, "dailyact_btnsave") == "N")
	{
		$disSaveSubCustom = "disabled";
	}
}

if($halamanGet  == "edit")
{
	$fromTime = $CDailyAct->detilAct($idactivityGet, "fromtime");
	$toTime = $CDailyAct->detilAct($idactivityGet, "totime");
	$activity = $CDailyAct->detilAct($idactivityGet, "activity");
	$relatedInfo = $CDailyAct->detilAct($idactivityGet, "relatedinfo");
	$statusAct = $CDailyAct->detilAct($idactivityGet, "status");
	$problemIdent = $CDailyAct->detilAct($idactivityGet, "problemident");
	$corrective = $CDailyAct->detilAct($idactivityGet, "corrective");
	$kpiNumber = $CDailyAct->detilAct($idactivityGet, "kpinumber");
	$spvComment = $CDailyAct->detilAct($idactivityGet, "spvcomment");
	$responComment = $CDailyAct->detilAct($idactivityGet, "responcomment");
	$referIdActivity = $CDailyAct->detilAct($idactivityGet, "referidactivity");
	$tglAct = $CDailyAct->detilAct($idactivityGet, "tanggal");
	$blnAct = $CDailyAct->detilAct($idactivityGet, "bulan");
	$thnAct = $CDailyAct->detilAct($idactivityGet, "tahun");
	
	
	//=========== START === Fungsi Prev & Next Button======================================
	$urutanCurrent = $CDailyAct->detilAct($idactivityGet, "urutan");
	$idActivityPrev = $CDailyAct->btnPrevNext($urutanCurrent, $subordinateId, $tglAct, $blnAct, $thnAct, "prev");
	$idActivityNext = $CDailyAct->btnPrevNext($urutanCurrent, $subordinateId, $tglAct, $blnAct, $thnAct, "next");
	
	$btnPrevJob = $CDailyAct->prevJob($idActivityPrev);
	$btnNextJob = $CDailyAct->nextJob($idActivityNext);
	//=========== END OF === Fungsi Prev & Next Button======================================
	
	$dateCreated = $CDailyAct->detilAct($referIdActivity, "CONCAT(tanggal,'/',bulan,'/',tahun)");
	
	// BERIKAN STATUS JIKA COMMENT RESPONSE SUDAH DIBACA
	//$CKoneksi->mysqlQuery("UPDATE tblactivity SET oldresponcomment='".$responComment."' WHERE idactivity='".$idactivityGet."' AND deletests=0;");
}

if($aksiGet == "simpanEditAct")
{
	$statusActOld = $CDailyAct->detilAct($idactivityGet, "status");
	$statusActNew = $_POST['statusAct']; 
	if($statusActOld != $statusActNew)
	{
		$statusChange = ", statuschangebyid = '".$userIdLogin."', statuschangebyname = '".$userFullnm."'";
	}
	
	$idactivityGet = $_GET['idactivity'];
	$dateActGet = $_GET['dateAct'];	
	$tglAct =  substr($dateActGet,0,2);
	$blnAct =  substr($dateActGet,3,2);
	$thnAct =  substr($dateActGet,6,4);
	
	$corrective = mysql_real_escape_string( $_POST['corrective'] );
	$statusAct = $_POST['statusAct']; 
	$spvComment = mysql_real_escape_string( $_POST['spvComment'] ); 
	$spvCommentOld = $CDailyAct->detilAct($idactivityGet, "spvcomment");
	
	$dateFinish = "0000-00-00";
	if($statusAct == "finish"){
		//$dateFinish = $CPublic->waktuSek();
		$dateFinish = $thnAct."-".$blnAct."-".$tglAct;
	}
	
	$CKoneksi->mysqlQuery("UPDATE tblactivity SET corrective = '".$corrective."', status = '".$statusAct."', datefinish='".$dateFinish."' ".$statusChange.", spvcomment = '".$spvComment."', oldspvcomment = '".mysql_real_escape_string($spvCommentOld)."',  updusrdt = '".$CPublic->userWhoAct()."' WHERE idactivity = '".$idactivityGet."' AND deletests=0;");
	
	$CHistory->updateLog($userIdLogin, "Rubah Daily Activity (idactivity = <b>".$idactivityGet."</b>, Date = <b>".$dateActGet."</b>, Status = <b>".$statusAct."</b>, Supervisor Comment = <b>".$spvComment."</b>)");
}

$disSave = "";
$readonly = "";
$appAlert = "";
$appOrDis = "DISAPPROVED";
$bosApprove = $CDailyAct->detilAct($idactivityGet, "bosapprove");
if($bosApprove == "Y")
{
	$disSave = "disabled";
	$readonly = "readonly";
	$appAlert = "onClick=\"appAlert();\"";
	$appOrDis = "APPROVED";
}
?>

<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<script src="../../js/JavaScriptUtil.js"></script>
<script src="../../js/Parsers.js"></script>
<script src="../../js/InputMask.js"></script>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
function pilihBtnSave()
{
	var answer  = confirm("Are you sure want to save?");
	if(answer)
	{
		formNewAct.submit();
	}
	else
	{	return false;	}
}

function exit()
{
	parent.tb_remove(false);
	parent.document.getElementById('iframeHal').src = "";
	parent.document.getElementById('iframeHal').src = "templates/halSubordinateDailyActList.php?halaman=openSubordinateJobList&dateAct=<?php echo $dateActGet; ?>&subordinateId=<?php echo $subordinateId; ?>";
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

function klikAlsoUsed(dateAlsoUsed)
{
	parent.tb_remove(false);
	parent.loadUrl('../index.php?aksi=openSubordinateJobList&empNo=<?php echo $ownerEmpNo; ?>&dateAct='+dateAlsoUsed);
}

function appAlert()
{
	alert('Cannot Write. \rThis activity is already Approved');
}
</script>

<body bgcolor="#F8F8F8" bottommargin="3" topmargin="3">
<center>

<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
<tr valign="top">
	<td height="25" width="50%">
        <span class="teksLvlFolder" style="color:#666;">Date Activity : </span>
        <span class="formInput" style="text-decoration:underline;color:#00F;font-weight:bold;"><?php echo $dateActGet; ?></span>
        &nbsp;|&nbsp;
        <span class="teksLvlFolder" style="color:#666;">Subordinate : </span>
        <span class="formInput" style="text-decoration:underline;color:#00F;font-weight:bold;"><?php echo $subordinateName; ?></span>
    </td>
    <td align="right"><span class="teksLvlFolder" style="color:#999;font-size:16px;font-style:italic;"><b>:: Edit Subordinate Activity ::</b></span>&nbsp;</td>
</tr>

<tr valign="top">
    <td class="tdMyFolder" bgcolor="#FFFFFF" colspan="2" align="center" valign="top" width="100%">
    	<div style="width:890px;height:454px;overflow:scroll;overflow-x:hidden;top:expression(offsetParent.scrollTop);">
            <table cellpadding="0" cellspacing="0" height="100%" width="99%" border="0">
            <form action="halSubordinateEditAct.php?aksi=simpanEditAct&dateAct=<?php echo $dateActGet; ?>&idactivity=<?php echo $idactivityGet; ?>" method="post" id="formNewAct" name="formNewAct">
            <tr>
                <td align="center" valign="top" height="130">
                
                    <table cellpadding="0" cellspacing="5" width="100%" class="formInput" style="cursor:default;">
                    
                    <tr valign="top">
                    	<td width="15%" class="teksLvlFolder" style="text-decoration:underline;"><?php echo $appOrDis; ?></td>
                        <td width="15%">Time</td>
                        <td width="55%">
                        From&nbsp;&nbsp;&nbsp;
                        <input type="text" class="elementDefault" id="fromTime" name="fromTime" style="width:50px;height:28px;" value="<?php echo $fromTime; ?>" readonly>
                        &nbsp;&nbsp;&nbsp;&nbsp;To&nbsp;&nbsp;&nbsp;
                        <input type="text" class="elementDefault" id="toTime" name="toTime" style="width:50px;height:28px;" value="<?php echo $toTime; ?>" readonly>
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
									echo "&nbsp;&bull; <span onClick=\"klikAlsoUsed('".$dateCreated."');\" class=\"formInput\" style=\"text-decoration:underline;color:#00F;font-weight:bold;\" onMouseOver=\"this.style.color='#000080';\" onMouseOut=\"this.style.color='#00F';\">".$dateCreated."</span>";
								}
								?>
                                </td>
                            </tr>
                            <tr>
                            	<td>&nbsp;</td>
                            </tr>
                            </table>
                        </td>
                        <td>Activity</td>
                        <td><input type="text" class="elementDefault" id="activity" name="activity" style="width:430px;height:28px;" value="<?php echo $CPublic->konversiQuotes1($activity); ?>" readonly></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr valign="top">
                        <td>Related Information</td>
                        <td>
                            <textarea class="elementDefault" id="relatedInfo" name="relatedInfo" style="width:430px;height:56px;" readonly><?php echo $relatedInfo; ?></textarea></td>
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
							$queryCariReferId = $CKoneksi->mysqlQuery("SELECT CONCAT(tanggal,'/',bulan,'/',tahun) AS dateActMerg FROM tblactivity WHERE ownerid='".$ownerId."' AND referidactivity='".$nilaiReferIdAct."' AND deletests = 0 ORDER BY bulan,tanggal;");
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
                        <td width="17%">Status</td>
                        <td width="66%">
                            <select class="elementMenu" id="statusAct" name="statusAct" style="font-size:12px;" title="Choose Subordinate Work Status">
                            <?php
                                echo $CDailyAct->menuStatus($statusAct);
                            ?>
                            </select>
                             &nbsp;<span style="color:#C30;"> Last change by <?php echo $statusChangeByName; ?></span>
                        </td>
                        <td width="17%">&nbsp;</td>
                    </tr>
                    <tr valign="top">
                        <td>Problem Identification</td>
                        <td><textarea class="elementDefault" id="problemIdent" name="problemIdent" style="width:430px;height:56px;" readonly><?php echo $problemIdent; ?></textarea></td>
                        <td valign="middle">&nbsp;</td>
                    </tr>
                    <tr valign="top">
                        <td>Corrective Action</td>
                        <td><textarea class="elementDefault" id="corrective" name="corrective" style="width:430px;height:56px;"><?php echo $corrective; ?></textarea></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr valign="top">
                        <td>KPI Number</td>
                        <td>
                        <input type="text" class="elementDefault" id="kpiNumber" name="kpiNumber" style="width:50px;height:28px;" maxlength="3" value="<?php echo $kpiNumber; ?>" readonly>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr valign="top">
                        <td>Superior Comment</td>
                        <td><textarea class="elementDefault" id="spvComment" name="spvComment" style="width:430px;height:56px;" <?php echo $readonly." ".$appAlert; ?>><?php echo $spvComment; ?></textarea></td>
                        <td>&nbsp;</td>
                    </tr>
                   	<tr valign="top">
                    	<td>Comment Response</td>
                        <td><textarea class="elementDefault" id="responComment" name="responComment" style="width:430px;height:56px;" readonly><?php echo $responComment; ?></textarea></td>
                        <td align="right">&nbsp;</td>
                    </tr>
                    </table>  
                </td>
            </tr>
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
	<td class="tdMyFolder" colspan="2" bgcolor="#FFFFFF" height="65" valign="middle" style="cursor:default;">&nbsp;
		<button class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" type="button" style="width:90px;height:55px;" onClick="tutup();" title="Close Edit Subordinate Activity Window">
        	<table width="100%" height="100%" class="fontBtnKecil" onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'">
            <tr>
            	<td align="center"><img src="../../picture/Metro-Shut-Down-Blue-32.png" height="25"/> </td>
            </tr>
            <tr>
            	<td align="center">CLOSE</td>
            </tr>
        	</table>
        </button>
        &nbsp;
        <button class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" onClick="pilihBtnSave();" style="width:90px;height:55px;" <?php echo $disSave; ?> <?php echo $disSaveSubCustom; ?> title="Save Edit Subordinate Activity">
            <table class="fontBtnKecil" onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'" border="0" width="100%" height="100%">
            <tr>
                <td align="center"><img src="../../picture/Floppy-Disk-blue-32.png" height="25"/> </td> 
            </tr>
            <tr>
                <td align="center">SAVE</td>
            </tr>
            </table>
        </button>
        &nbsp;
        <button class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" onClick="formNewAct.reset();" style="width:90px;height:55px;" title="UNDO (Back to Current Condition)">
            <table class="fontBtnKecil" onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'" border="0" width="100%" height="100%">
            <tr>
                <td align="center"><img src="../../picture/Button-Synchronize-blue-32.png" height="25"/> </td> 
            </tr>
            <tr>
                <td align="center">RESET</td>
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


<script language="javascript">
<?php
if($aksiGet == "simpanEditAct")
{
	echo "exit();";
}
?>
</script>