<!DOCTYPE HTML>
<?php
require_once("../../config.php");

$subordinateId = $_GET['subordinateId'];
$dateActGet = $CPublic->convTglDB($_GET['dateAct']);

$tglAct = $CPublic->cariNilaiTglDB($dateActGet, "tanggal");
$blnAct = $CPublic->cariNilaiTglDB($dateActGet, "bulan");
$thnAct = $CPublic->cariNilaiTglDB($dateActGet, "tahun");

$tglPrev =  $CPublic->zerofill($tglAct - 1,2);
$dateActPrev = $tglPrev."/".$blnAct."/".$thnAct;
$tglNext = $CPublic->zerofill($tglAct + 1,2);
$dateActNext = $tglNext."/".$blnAct."/".$thnAct;
	
	$blnSek = "'".$thnAct."-".$blnAct."-".$tglAct."'";
	$sqlLast = $CPublic->lastDay($blnSek);
	$tglLast = substr($sqlLast,8,2);

$empNo = $CLogin->detilLogin($subordinateId, "empno"); // no employee dari subordinate yang dipilih

$empAtasanLangsung = $CEmployee->cariAtasanLangsung($empNo);
$statusAtasan = "bukan"; // merupakan status apakah user yang login merupakan atasan langsung dari subordinate yang dipilih atau bukan
if($userEmpNo == $empAtasanLangsung )
{
	$statusAtasan = "atasan";
}

$statusRead = $CDailyAct->detilActByDay($subordinateId, $tglAct, $blnAct, $thnAct, "bosread");
if($statusRead == "N")
{
	if($statusAtasan == "atasan")
	{
		$CKoneksi->mysqlQuery("UPDATE tblactivity SET bosread='Y', readbyid='".$userIdLogin."', readbyname='".$userFullnm."', updusrdt='".$CPublic->userWhoAct()."' WHERE ownerid='".$subordinateId."' AND tanggal='".$tglAct."' AND bulan='".$blnAct."' AND tahun='".$thnAct."' AND deletests=0");
		$CHistory->updateLog($userIdLogin, "Baca Job Daily dari ".$CLogin->detilLoginByEmpno($empNo, "userfullnm")." (ownerid = <b>".$subordinateId."</b>, tanggal=<b>".$tglAct."</b>, bulan=<b>".$blnAct."</b>, tahun=<b>".$thnAct."</b>)");
	}
}
?>
<script type="text/javascript" src="../../js/main.js"></script>
<link href="../../css/main.css" rel="stylesheet" type="text/css"/>
<link href="../../css/archives.css" rel="stylesheet" type="text/css"/>
<link href="../../andhikaportaltes/css/main.css" rel="stylesheet" type="text/css"/>
<link href="../../andhikaportaltes/css/archives.css" rel="stylesheet" type="text/css"/>

<body onLoad="loadScroll('halSubordinateDailyActList');" onUnload="saveScroll('halSubordinateDailyActList')"> 
<table width="100%">
<input type="hidden" id="prev" value="<?php echo $dateActPrev;?>"/>
<input type="hidden" id="next" value="<?php echo $dateActNext;?>"/>
<input type="hidden" id="lastDate" value="<?php echo $tglLast;?>"/>
<?php
if($halamanGet == "approve")
{
	$CKoneksi->mysqlQuery("UPDATE tblactivity SET bosapprove='Y', approvebyid='".$userIdLogin."', approvebyname='".$userFullnm."', updusrdt='".$CPublic->userWhoAct()."' WHERE ownerid='".$subordinateId."' AND tanggal='".$tglAct."' AND bulan='".$blnAct."' AND tahun='".$thnAct."' AND deletests=0");
	$CHistory->updateLog($userIdLogin, "Approve Job Daily dari ".$CLogin->detilLoginByEmpno($empNo, "userfullnm")." (ownerid = <b>".$subordinateId."</b>, tanggal=<b>".$tglAct."</b>, bulan=<b>".$blnAct."</b>, tahun=<b>".$thnAct."</b>)");
}

$html = "";
$urutan = 1;
$query = $CKoneksi->mysqlQuery("SELECT * FROM tblactivity WHERE tanggal='".$tglAct."' AND bulan='".$blnAct."' AND tahun='".$thnAct."' AND ownerid=".$subordinateId." AND deletests=0 ORDER BY urutan ASC");

while($row = $CKoneksi->mysqlFetch($query))
{	
	if($row['status'] == "onprogress")
	{
		$status = "On Progress";
		$warnaStatus = "<span style=\"border-width:1;background-color:#CC0;\">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;";
	}
	if($row['status'] == "postpone")
	{
		$status = "Postpone";
		$warnaStatus = "<span style=\"border-width:1;background-color:#C00;\">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;";
	}
	if($row['status'] == "finish")
	{
		$status = "Finish";
		$warnaStatus = "<span style=\"border-width:1;background-color:#090;\">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;";
	}
	
	$bosReadJob = "background-image:url(../../picture/bintang.png);background-repeat:no-repeat;background-position:top left;";
	if($row['bosreadjob'] == "Y")
	{
		$bosReadJob = "";
	}
	
	$iconNewSpvCom = "";
	if($row['spvcomment'] != "" && $row['oldspvcomment'] != $row['spvcomment'])
	{
		$iconNewSpvCom = "<img src=\"../../picture/SuperiorComment.png\" height=\"10\" style=\"position:absolute;\" title=\"New Comment for your Subordinate\">";
	}
	else if($row['responcomment'] != "" && $row['oldresponcomment'] != $row['responcomment'])
	{
		$iconNewSpvCom = "<img src=\"../../picture/responComment.png\" height=\"10\" style=\"position:absolute;\" title=\"New Comment Reponse from your Subordinate\">";
	}
	
	$activity = "( ".$row['fromtime']." - ".$row['totime']." )".$CPublic->potongKarakter($row['activity'], 75);
	$relatedInfo = $CPublic->potongKarakter($row['relatedinfo'], 90) ; 
	$statusComment = $warnaStatus.$status." | ".$CPublic->potongKarakter($row['spvcomment'], 75);
	$disableCutiSakit = "";
	if($row['cuti'] == "Y" || $row['sakit'] == "Y")
	{
		$activity = $row['activity'];
		$relatedInfo = "&nbsp;";
		$statusComment = "&nbsp;";
		$disableCutiSakit = "disabled";
	}
	$html.="<tr onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\" onclick=\"parent.openThickboxWindow('".$row['idactivity']."','edit');\">
            <td class=\"tdMyFolder\">
                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                <tr>
					<td width=\"5%\">
						<table width=\"100%\" height=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                        <tr>
                        	<td height=\"10\" align=\"center\"></td>
                        </tr>	
                    	<tr>
                        	<td height=\"35\" align=\"center\" style=\"font-size:22px;color:#006;font-weight:bold;font-family:Tahoma;".$bosReadJob."\">".$urutan++."</td>
                        </tr>
                        <tr>
                        	<td height=\"10\" align=\"center\"></td>
                        </tr>
                        </table>
					</td>
                    <!-- <td width=\"68%\"> JIKA PAKAI BUTTON EDIT -->
                    <td width=\"75%\">
                    	<table width=\"100%\" class=\"fontMyFolderList\" ".$bgRow.">
                        <tr>
                        	<td width=\"115\">Activity</td>
							<td width=\"10\">:</td>
							<td>
								<span style=\"color:#006;\">".$activity."</span>
							</td>
                        </tr>
                        <tr>
                            <td>Related Information</td>
							<td>:</td><td style=\"color:#006;\">".$relatedInfo."</td>
                        </tr>
                        <tr>
                            <td>Status | Comment&nbsp;".$iconNewSpvCom."</td><td>:</td>
							<td style=\"color:#006;\">".$statusComment."</td>
                        </tr>
                        </table>
                    </td>
					
					<td align=\"right\" class=\"\">	
						<!--							
                    	<button class=\"btnStandarKecil\" type=\"button\" onclick=\"parent.openThickboxWindow('".$row['idactivity']."','detail');\" style=\"width:75px;height:55px;\">
                            <table width=\"100%\" height=\"100%\">
                              <tr>
                                <td align=\"center\"><img src=\"../../picture/Information-blue-32.png\" height=\"25\"/> </td>
                                
                              </tr>
                              <tr>
                                <td align=\"center\">DETAIL</td>
                              </tr>
                            </table>
                        </button>
						&nbsp; -->
						<button class=\"btnStandarKecil\" type=\"button\" onclick=\"parent.openThickboxWindow('".$row['idactivity']."','edit');\" style=\"width:75px;height:55px;\" ".$disableCutiSakit." title=\"View/Edit this Subordinate's Activity\">
                            <table width=\"100%\" height=\"100%\">
                              <tr>
                                <td align=\"center\"><img src=\"../../picture/Auction-blue-32.png\" height=\"25\"/> </td>
                                
                              </tr>
                              <tr>
                                <td align=\"center\">EDIT</td>
                              </tr>
                            </table>
                        </button>
						<!--&nbsp;
						<button class=\"btnStandarKecil\" type=\"button\" onclick=\"parent.deleteAct('".$row['idactivity']."');\" style=\"width:75px;height:55px;\" disabled>
                            <table width=\"100%\" height=\"100%\">
                              <tr>
                                <td align=\"center\"><img src=\"../../picture/Button-Cross-blue-32.png\" height=\"25\"/> </td>
                                
                              </tr>
                              <tr>
                                <td align=\"center\">DELETE</td>
                              </tr>
                            </table>
                        </button>
						-->
                    </td>
                </table>
            </td>
        </tr>";
}
echo $html;
?>

</table>
</body>

<script>
<?php
if($halamanGet == "approve")
{
	echo "parent.setelahKlikBtnApprove('".$_GET['dateAct']."', '".$empNo."')";
}
else if($halamanGet == "gantiDateAct")
{
	$subordinateName = $CLogin->detilLogin($_GET['subordinateId'], "userfullnm");
	
	$jmlJobUnread = $CDailyAct->jmlJobUnread($CPublic, $subordinateId, $tglAct, $blnAct, $thnAct);
	$statusApprove = $CDailyAct->detilActByDay($subordinateId, $tglAct, $blnAct, $thnAct, "bosapprove");
	$statusCuti = $CDailyAct->detilActByDay($subordinateId, $tglAct, $blnAct, $thnAct, "cuti");
	$statusSakit = $CDailyAct->detilActByDay($subordinateId, $tglAct, $blnAct, $thnAct, "sakit");
	
	$statusRevisi = "&nbsp;";
	$idRevisi = $CDailyAct->detilActByDay($subordinateId, $tglAct, $blnAct, $thnAct, "idrevisi");

	if($idRevisi != "00000" && $idRevisi != "")
	{
		$revisiKe = $CDailyAct->detilRevisi($idRevisi, "revisike");
		$statusRevisi = "|&nbsp;<span class=\"teksLvlFolder\" style=\"text-decoration:underline;font-weight:normal;\">R".$revisiKe."</span>&nbsp;";
	}
	
	if($statusApprove == "N") 
	{
		$dis = "disabled";
		$class = "btnStandarDisabled";
		if($jmlJobUnread == 0)// JIKA ACTIVITY BELOM ADA YANG DIBACA
		{
			$dis = "";
			$class = "btnStandar";
		}
		echo "parent.document.getElementById('idHalBtnApprove').innerHTML = '&nbsp;<button class=\"".$class."\" id=\"btnApprove\"' 
		 +'onclick=\"klikBtnApprove();\" style=\"width:75px;height:29px;\" title=\"Approve Activity Today\" ".$dis.">'
			+'<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">'
			+'<tr>'
			+'	<td align=\"center\"><img src=\"../picture/Lock-blue-32.png\" height=\"20\"/> </td>'
			+'	<td align=\"center\">Approve</td>'
			+'</tr>'
			+'</table>'
		+'</button>';
		";
		echo "parent.document.getElementById('idHalStatus').innerHTML = '&nbsp;<span class=\"teksLvlFolder\" style=\"color:#666;\">STATUS</span> : <span class=\"teksLvlFolder\" style=\"text-decoration:underline;\">DISAPPROVED</span>".$statusRevisi."';";
	}
	if($statusApprove == "Y")
	{
		echo "parent.document.getElementById('idHalBtnApprove').innerHTML = '&nbsp;<button class=\"btnStandar\" id=\"btnUnapprove\"'
		 +'onclick=\"klikBtnUnapprove();\" style=\"width:95px;height:29px;\" title=\"Disapprove Activity Today\">'
			+'<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">'
			+'<tr>'
			+'	<td align=\"center\"><img src=\"../picture/Unlock-blue-32.png\" height=\"20\"/> </td>'
			+'	<td align=\"center\">Disapprove</td>'
			+'</tr>'
			+'</table>'
		+'</button>';
		";
		echo "parent.document.getElementById('idHalStatus').innerHTML = '&nbsp;<span class=\"teksLvlFolder\" style=\"color:#666;\">STATUS</span> : <span class=\"teksLvlFolder\" style=\"text-decoration:underline;\">APPROVED</span>".$statusRevisi."';";
	}
	if($statusApprove == "")
	{
		$dis = "disabled";
		$class = "btnStandarDisabled";
		echo "parent.document.getElementById('idHalBtnApprove').innerHTML = '&nbsp;<button class=\"".$class."\" id=\"btnApprove\"' 
		 +'onclick=\"klikBtnApprove();\" style=\"width:75px;height:29px;\" title=\"Approve Activity Today\" ".$dis.">'
			+'<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">'
			+'<tr>'
			+'	<td align=\"center\"><img src=\"../picture/Lock-blue-32.png\" height=\"20\"/> </td>'
			+'	<td align=\"center\">Approve</td>'
			+'</tr>'
			+'</table>'
		+'</button>';
		";
		echo "parent.document.getElementById('idHalStatus').innerHTML = '&nbsp;<span class=\"teksLvlFolder\" style=\"color:#666;\">STATUS</span> : <span class=\"teksLvlFolder\" style=\"text-decoration:underline;\">NO JOB LIST</span>".$statusRevisi."';";
	}
	
	if($userSubCustom == "Y")
	{
		if($CEmployee->detilSubCustomByUser($userIdLogin, $subordinateId, "dailyact_approve") == "N")
		{
			echo "parent.document.getElementById('idHalBtnApprove').innerHTML = '&nbsp;';";
		}
	}
	
	if($statusCuti == "Y" || $statusSakit == "Y")
	{
		$stat = "";
		$cuti = "LEAVE";
		$sakit = "SICK";
		
		if($statusCuti == "Y")
		{
			$stat = $cuti;
		}
		if($statusSakit == "Y")
		{
			$stat = $sakit;
		}
		echo "parent.document.getElementById('idHalBtnApprove').innerHTML = '&nbsp;';
		";
		echo "parent.document.getElementById('idHalStatus').innerHTML = '&nbsp;<span class=\"teksLvlFolder\" style=\"color:#666;\">STATUS</span> : <span class=\"teksLvlFolder\" style=\"text-decoration:underline;\">".$stat."</span>".$statusRevisi."';";
	}
	
	$judul = "&nbsp;:: <span style=\"text-decoration:underline;\">".$subordinateName."</span> DAILY ACTIVITY LIST ::";
	$hariLiburSqlServer = $CPublic->hariLiburSqlServer($koneksiOdbcId, $koneksiOdbc, $tglAct, $blnAct, $thnAct);
	$jmlJob = $CDailyAct->jmlJob($CPublic, $subordinateId, $tglAct, $blnAct, $thnAct);

	if($hariLiburSqlServer == 1)
	{
		$judul = "&nbsp;:: HOLIDAY ::";
	}
	if($hariLiburSqlServer == 1 && $jmlJob > 0)
	{
		//$judul = "&nbsp;:: MY DAILY ACTIVITY LIST ::";
		$judul = " :: <span style=\"text-decoration:underline;\">".$subordinateName."</span> DAILY ACTIVITY LIST ::";
	}
	
	echo "parent.document.getElementById('tdJudulTitle').innerHTML = '".$judul."';";
}
else
{
	$jmlJobUnread = $CDailyAct->jmlJobUnread($CPublic, $subordinateId, $tglAct, $blnAct, $thnAct);
	$statusApprove = $CDailyAct->detilActByDay($subordinateId, $tglAct, $blnAct, $thnAct, "bosapprove");
	
	//JUMLAH ACTIVITY YANG BELUM DIBACA
	if($jmlJobUnread == 0)
	{
		if($statusApprove == "Y")
		{
			echo "parent.document.getElementById('btnUnapprove').disabled = false;";
			echo "parent.document.getElementById('btnUnapprove').className = 'btnStandar';";
		}
		
		if($statusApprove == "N")
		{
			echo "parent.document.getElementById('btnApprove').disabled = false;";
			echo "parent.document.getElementById('btnApprove').className = 'btnStandar';";
		}
	}
	else
	{
		echo "parent.document.getElementById('btnApprove').disabled = true;";
		echo "parent.document.getElementById('btnApprove').className = 'btnStandarDisabled';";
	}
}

?>
</script>

<script>
/*parent.document.getElementById('idHalBtnApprove').innerHTML = '&nbsp;<button class="btnStandarDisabled" id="btnApprove"' 
		 +'onclick="klikBtnApprove();" style="width:75px;height:29px;" title="Approve Activity Today" disabled>'
			+'<table cellpadding="0" cellspacing="0" width="100%" height="100%">'
			+'<tr>'
			+'	<td align="center"><img src="../picture/Lock-blue-32.png" height="20"/> </td>'
			+'	<td align="center">Approve</td>'
			+'</tr>'
			+'</table>'
		+'</button>';
parent.document.getElementById('idHalStatus').innerHTML = '&nbsp;<span class="teksLvlFolder" style="color:#666;">STATUS</span> : <span class="teksLvlFolder" style="text-decoration:underline;">DISAPPROVED</span>&nbsp;';
parent.document.getElementById('idHalBtnApprove').innerHTML = '&nbsp;'
parent.document.getElementById('tdJudulTitle').innerHTML = '&nbsp;:: <span style="text-decoration:underline;">DESTA ARIAWAN</span> DAILY ACTIVITY LIST ::';*/
</script>

</HTML>