<?php
require_once("../../config.php");

$empNotGet = $_GET['empNo']; //no employee subordinate / bawahan
$dateActGet = $_GET['dateAct'];

$empAtasanLangsung = $CEmployee->cariAtasanLangsung($empNotGet);
$statusAtasan = "bukan"; // merupakan status apakah user yang login merupakan atasan langsung dari subordinate yang dipilih atau bukan
if($userEmpNo == $empAtasanLangsung )
{
	$statusAtasan = "atasan";
}

$tglAct = $CPublic->cariNilaiTglNonDB($dateActGet, "tanggal");
$blnAct = $CPublic->cariNilaiTglNonDB($dateActGet, "bulan");
$thnAct = $CPublic->cariNilaiTglNonDB($dateActGet, "tahun");
$userIdSelect = $CLogin->detilLoginByEmpno($empNotGet, "userid");

$statusRead = $CDailyAct->detilActByDay($userIdSelect, $tglAct, $blnAct, $thnAct, "bosread");

if($statusRead == "N")
{
	if($statusAtasan == "atasan")
	{
		$CKoneksi->mysqlQuery("UPDATE tblactivity SET bosread='Y', readbyid='".$userIdLogin."', readbyname='".$userFullnm."', updusrdt='".$CPublic->userWhoAct()."' WHERE ownerid='".$userIdSelect."' AND tanggal='".$tglAct."' AND bulan='".$blnAct."' AND tahun='".$thnAct."' AND deletests=0");
		$CHistory->updateLog($userIdLogin, "Baca Job Daily dari ".$CLogin->detilLoginByEmpno($empNoGet, "userfullnm")." (ownerid = <b>".$CLogin->detilLoginByEmpno($empNotGet, "userid")."</b>, tanggal=<b>".$tglAct."</b>, bulan=<b>".$blnAct."</b>, tahun=<b>".$thnAct."</b>)");
	}
}

if($aksiGet == "simpanApprove")
{
	$CKoneksi->mysqlQuery("UPDATE tblactivity SET bosapprove='Y', approvebyid='".$userIdLogin."', approvebyname='".$userFullnm."', updusrdt='".$CPublic->userWhoAct()."' WHERE ownerid='".$userIdSelect."' AND tanggal='".$tglAct."' AND bulan='".$blnAct."' AND tahun='".$thnAct."' AND deletests=0");
}
if($aksiGet == "simpanUnapprove")
{
	$CKoneksi->mysqlQuery("UPDATE tblactivity SET bosapprove='N', updusrdt='".$CPublic->userWhoAct()."' WHERE ownerid='".$userIdSelect."' AND tanggal='".$tglAct."' AND bulan='".$blnAct."' AND tahun='".$thnAct."' AND deletests=0");
}

$statusApprove = $CDailyAct->detilActByDay($userIdSelect, $tglAct, $blnAct, $thnAct, "bosapprove");
if($statusApprove == "Y")
{
	$disApprove = "disabled";
	$disUnapprove = "";
	$teksApprove = "APPROVE";
}
if($statusApprove == "N")
{
	$disApprove = "";
	$disUnapprove = "disabled";
	$teksApprove = "NOT APPROVE";
}

if($statusAtasan == "bukan")
{
	$disApprove = "disabled";
	$disUnapprove = "disabled";
}

?>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
function exit()
{
	parent.tb_remove(false);
	parent.document.getElementById('iframeHal').src = "";
	parent.document.getElementById('iframeHal').src = "templates/halCalendarContainer.php?month=<?php echo $blnAct."-".$thnAct; ?>&empNo=<?php echo $empNotGet; ?>";
}

function resetJobDaily()
{
	parent.clickBtnHari('<?php echo $empNotGet; ?>', '<?php echo $dateActGet; ?>');
}

function klikActivity(fromTime, toTime, activity, relatedinfo, status, problemident, corrective, kpinumber)
{
	document.getElementById('fromTime').value = fromTime;
	document.getElementById('toTime').value = toTime;
	document.getElementById('activity').value = activity;
	document.getElementById('relatedInfo').value = relatedinfo;
	document.getElementById('statusAct').value = status;
	document.getElementById('problemIdent').value = problemident;
	document.getElementById('corrective').value = corrective;
	document.getElementById('kpiNumber').value = kpinumber;
}

function klikBtnApprove()
{
	var answer  = confirm("Are you sure want to Approve?");
	if(answer)
	{
		formApprove.submit();
	}
	else
	{	return false;	}
}
function klikBtnUnapprove()
{
	var answer  = confirm("Are you sure want to Disapprove?");
	if(answer)
	{
		formUnapprove.submit();
	}
	else
	{	return false;	}
}

function onClickTr(trId)
{	
	var idTrSeb = document.getElementById('idTrSeb').value;
	if(idTrSeb != "")
	{
		document.getElementById(idTrSeb).onmouseover = function onmouseover(){	this.style.backgroundColor='#D9EDFF';	}
		document.getElementById(idTrSeb).onmouseout = function onmouseout(){	this.style.backgroundColor='#FFFFFF';	}
		document.getElementById(idTrSeb).style.fontWeight='';
		document.getElementById(idTrSeb).style.backgroundColor='#FFFFFF';
		document.getElementById(idTrSeb).style.cursor = 'pointer';	
	}
	
	document.getElementById('td'+trId).onmouseout = '';
	document.getElementById('td'+trId).onmouseover ='';
	document.getElementById('td'+trId).style.fontWeight='bold';
	document.getElementById('td'+trId).style.backgroundColor='#B0DAFF';
	document.getElementById('td'+trId).style.cursor = 'default';
	document.getElementById('idTrSeb').value = 'td'+trId;
}
</script>
<form name="formApprove" method="post" action="halSubordinateJobList.php?aksi=simpanApprove&empNo=<?php echo $empNotGet; ?>&dateAct=<?php echo $dateActGet; ?>">
</form>
<form name="formUnapprove" method="post" action="halSubordinateJobList.php?aksi=simpanUnapprove&empNo=<?php echo $empNotGet; ?>&dateAct=<?php echo $dateActGet; ?>">
</form>

<body bgcolor="#F8F8F8">
<input type="hidden" id="idTrSeb" name="idTrSeb">
<table cellpadding="0" cellspacing="0" width="100%" height="98%" border="0">
<tr valign="top">
	<td width="40%">
        <span class="teksLvlFolder" style="color:#666;">DATE ACTIVITY : </span><span class="teksLvlFolder" style="text-decoration:underline;"><?php echo $dateActGet; ?></span>
    </td>
    <td>
    	<table cellpadding="0" cellspacing="0" width="100%" height="100%" class="formInput" style="cursor:none;">
        <tr>
            <td align="center" class="tabelBorderAll" style="background-color:#EBEBEB;color:#369;font-weight:bold;font-size:18px;border-style:outset;border-width:3;"><?php echo $teksApprove; ?></td>
        </tr>
        </table>
    </td>
</tr>

<tr><td height="5"></td></tr>

<tr valign="top">
    <td class="tdMyFolder" bgcolor="#FFFFFF" colspan="2">
    	<table cellpadding="0" cellspacing="0" width="100%" border="0">
        <tr valign="top">
        	<td width="40%" height="427" class="tabelBorderAll">
            	<div style="width:100%;height:425px;overflow:scroll;overflow-x: hidden;top: expression(offsetParent.scrollTop);background-color:#FFF;">
                <table cellpadding="0" cellspacing="0" width="100%" border="0" class="fontMyFolderList" style="color:#000080;">
                
                <?php
				$html = "";
				$urutan = 1;
				
				$ownerId = $CLogin->detilLoginByEmpno($empNotGet, "userid");
				$query = $CKoneksi->mysqlQuery("SELECT * FROM tblactivity WHERE tanggal='".$tglAct."' AND bulan='".$blnAct."' AND tahun='".$thnAct."' AND ownerid=".$ownerId." AND deletests=0 ORDER BY tanggal, bulan, tahun DESC");
				while($row = $CKoneksi->mysqlFetch($query))
				{
					$html.= "<tr><td height=\"3\"></td></tr>
					<tr id=\"td".$urutan."\" valign=\"top\" onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\" onClick=\"klikActivity('".$row['fromtime']."', '".$row['totime']."', '".$row['activity']."', '".$row['relatedinfo']."', '".$row['status']."', '".$row['problemident']."', '".$row['corrective']."', '".$row['kpinumber']."');onClickTr('".$urutan."');\">
                	<td class=\"tabelBorderTopLeftNull\" style=\"border-style:dotted;\" width=\"8%\" height=\"40\" align=\"center\">".$urutan++."</td>
                    <td class=\"tabelBorderBottomJust\" style=\"border-style:dotted;\" width=\"28%\">&nbsp;".$row['fromtime']." - ".$row['totime']."&nbsp;</td>
                    <td class=\"tabelBorderBottomJust\" style=\"border-style:dotted;\" width=\"64%\">".$row['activity']."&nbsp;
                    </td>
                </tr>";
				}
				echo $html;
				?>
                
                </table>
                </div>
            </td>
            <td width="1%">&nbsp;</td>
            <td>
            	<table cellpadding="0" cellspacing="5" width="100%" class="formInput">
                <tr valign="top">
                    <td width="25%">Time</td>
                    <td width="75%">
                    From&nbsp;&nbsp;&nbsp;
                    <input type="text" class="elementDefault" id="fromTime" name="fromTime" style="width:50px;height:28px;">
                    &nbsp;&nbsp;||&nbsp;&nbsp;To&nbsp;&nbsp;&nbsp;
                    <input type="text" class="elementDefault" id="toTime" name="toTime" style="width:50px;height:28px;">
                    </td>
                </tr>
                <tr valign="top">
                    <td>Activity</td>
                    <td><input type="text" class="elementDefault" id="activity" name="activity" style="width:99%;height:28px;"></td>
                </tr>
                <tr valign="top">
                    <td>Related Information</td>
                    <td><textarea class="elementDefault" id="relatedInfo" name="relatedInfo" style="width:99%;height:56px;"></textarea></td>
                </tr>
                <tr valign="top">
                    <td>Status</td>
                    <td>
                        <select class="elementMenu" id="statusAct" name="statusAct" onChange="pilihStatus(this.value);">
                        <?php
                            echo $CDailyAct->menuStatus($statusAct);
                        ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <td>Problem Identification</td>
                    <td><textarea class="elementDefault" id="problemIdent" name="problemIdent" style="width:99%;height:56px;"></textarea></td>
                </tr>
                <tr valign="top">
                    <td>Corrective Action</td>
                    <td><textarea class="elementDefault" id="corrective" name="corrective" style="width:99%;height:56px;"></textarea></td>
                </tr>
                <tr valign="top">
                    <td>KPI Number</td>
                    <td><input type="text" class="elementDefault" id="kpiNumber" name="kpiNumber" style="width:50px;height:28px;" value=></td>
                </tr>
                </table> 	
            </td>
        </tr>
        </table>
    </td>
</tr>

<tr><td height="5"></td></tr>

<tr valign="top">
	<td class="tdMyFolder" style="cursor:default;" bgcolor="#FFFFFF" height="65" valign="middle" colspan="2">&nbsp;
    	<button class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" type="button" style="width:90px;height:55px;" onClick="exit();" title="Close Window">
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
        <button class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" type="button" style="width:95px;height:55px;" onClick="klikBtnApprove();" <?php echo $disApprove; ?> title="Approve Activity Today">
            <table width="100%" height="100%" class="fontBtnKecil" onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'">
              <tr>
                <td align="center"><img src="../../picture/Button-Check-blue-32.png" height="25"/> </td>
                
              </tr>
              <tr>
                <td align="center">APPROVE</td>
              </tr>
            </table>
        </button>
        &nbsp;
        <button class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" type="button" style="width:95px;height:55px;" onClick="klikBtnUnapprove();" <?php echo $disUnapprove; ?>>
            <table width="100%" height="100%" class="fontBtnKecil" onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'">
              <tr>
                <td align="center"><img src="../../picture/Button-Cross-blue-32.png" height="25"/> </td>
                
              </tr>
              <tr>
                <td align="center">DISAPPROVE</td>
              </tr>
            </table>
        </button>
        &nbsp;
        <button class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" type="button" style="width:95px;height:55px;" onClick="resetJobDaily();">
            <table width="100%" height="100%" class="fontBtnKecil" onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'">
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
</table>
</body>