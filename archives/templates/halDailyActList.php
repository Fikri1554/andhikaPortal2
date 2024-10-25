<!DOCTYPE HTML>
<?php
require_once("../../config.php");
/*echo $statusExpPage;
if($statusExpPage == "YES")
{
	echo "<script>";
	echo "parent.loadUrl('../index.php?aksi=sessionExpired')";	
	echo "</script>";
}*/

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
?>
<script type="text/javascript" src="../../js/main.js"></script>
<link href="../../css/main.css" rel="stylesheet" type="text/css"/>
<link href="../../css/archives.css" rel="stylesheet" type="text/css"/>
<link href="../../andhikaportaltes/css/main.css" rel="stylesheet" type="text/css"/>
<link href="../../andhikaportaltes/css/archives.css" rel="stylesheet" type="text/css"/>

<script language="javascript">
function klikUrutNaik(idActivity, urutSeb, urutSek, urutSet)
{
	formUrut.urutSeb.value = urutSeb;
	formUrut.urutSek.value = urutSek;
	formUrut.urutSet.value = urutSet;
	formUrut.idActivity.value = idActivity;
	formUrut.aksi.value = "urutNaik";
	formUrut.submit();
}

function klikUrutTurun(idActivity, urutSeb, urutSek, urutSet)
{
	formUrut.urutSeb.value = urutSeb;
	formUrut.urutSek.value = urutSek;
	formUrut.urutSet.value = urutSet;
	formUrut.idActivity.value = idActivity;
	formUrut.aksi.value = "urutTurun";
	formUrut.submit();
}
</script>

<div id="divIdUrutan"></div>
<body onLoad="loadScroll('halDailyActList');" onUnload="saveScroll('halDailyActList')"> 

<table width="100%">
<input type="hidden" id="prev" value="<?php echo $dateActPrev;?>"/>
<input type="hidden" id="next" value="<?php echo $dateActNext;?>"/>
<input type="hidden" id="lastDate" value="<?php echo $tglLast;?>"/>
<form method="post" action="halDailyActList.php?dateAct=<?php echo $_GET['dateAct']; ?>" name="formUrut">
    <input type="hidden" name="urutSeb">
    <input type="hidden" name="urutSek">
    <input type="hidden" name="urutSet">
    <input type="hidden" name="idActivity">
    <input type="hidden" name="aksi">
</form>
<?php
//------ ==START== Function pilihBtnLeave() pada halNewAct.php ---------
if($halamanGet == "leaveStatus")
{
	$dateActGet = $_GET['dateAct'];
	$tglAct =  substr($dateActGet,0,2);
	$blnAct =  substr($dateActGet,3,2);
	$thnAct =  substr($dateActGet,6,4);
	
	$maxUrutan = $CDailyAct->detilActByDay($userIdLogin, $tglAct, $blnAct, $thnAct, "MAX(urutan)");
	$urutan = $maxUrutan + 1;
	
	if($urutan != "1") // ARTINYA JIKA PILIH LEAVE DALAM KEADAAN DAILY ACTIVITY TIDAK KOSONG PADA HARI YANG DIPILIH
	{
		$deletests = "1";
		$CKoneksi->mysqlQuery("UPDATE tblactivity SET deletests = '".$deletests."', updusrdt = '".$CPublic->userWhoAct()."', delusrdt = '".$CPublic->userWhoAct()."' WHERE tanggal = '".$tglAct."' AND bulan = '".$blnAct."' AND tahun = '".$thnAct."' AND ownerid='".$userIdLogin."';");
		
		$CHistory->updateLog($userIdLogin, "Otomatis Hapus Activity karena Membuat Status Cuti (Activity date = <b>".$dateActGet."</b>, ownerId : <b>".$userIdLogin."</b>, ownerName = <b>".$userFullnm."</b>)");
	}
	
	$cuti = "Y";
	$activity = "LEAVE";
	$bosread = "Y" ;
	$bosreadjob = "Y";
	$bosapprove = "Y";
	
	$CKoneksi->mysqlQuery("INSERT INTO tblactivity (urutan, ownerid, ownername, tanggal, bulan, tahun, activity, bosread, bosreadjob, bosapprove, cuti, addusrdt)VALUES ('".$urutan."', '".$userIdLogin."', '".$userFullnm."', '".$tglAct."', '".$blnAct."', '".$thnAct."', '".$activity."', '".$bosread."', '".$bosreadjob."', '".$bosapprove."', '".$cuti."', '".$CPublic->userWhoAct()."');");
	
	$lastInsertId = mysql_insert_id();
	$CHistory->updateLog($userIdLogin, "Membuat Status Cuti (idactivity = <b>".$lastInsertId."</b>, date = <b>".$dateActGet."</b>, ownerId : <b>".$userIdLogin."</b>, ownerName = <b>".$userFullnm."</b>, Activity = <b>".$activity."</b>, Cuti : <b>".$cuti."</b>)");
}
//------ ==END OF== Function pilihBtnLeave() pada halNewAct.php ---------
//------ ==START== Function pilihBtnSick() pada halNewAct.php ---------
if($halamanGet == "sickStatus")
{
	$dateActGet = $_GET['dateAct'];
	$tglAct =  substr($dateActGet,0,2);
	$blnAct =  substr($dateActGet,3,2);
	$thnAct =  substr($dateActGet,6,4);
	
	$maxUrutan = $CDailyAct->detilActByDay($userIdLogin, $tglAct, $blnAct, $thnAct, "MAX(urutan)");
	$urutan = $maxUrutan + 1;
	
	if($urutan != "1")
	{
		$deletests = "1";
		$CKoneksi->mysqlQuery("UPDATE tblactivity SET deletests = '".$deletests."', updusrdt = '".$CPublic->userWhoAct()."', delusrdt = '".$CPublic->userWhoAct()."' WHERE tanggal = '".$tglAct."' AND bulan = '".$blnAct."' AND tahun = '".$thnAct."' AND ownerid='".$userIdLogin."';");
		
		$CHistory->updateLog($userIdLogin, "Otomatis Hapus Activity karena Membuat Status Sakit (Activity date = <b>".$dateActGet."</b>, ownerId : <b>".$userIdLogin."</b>, ownerName = <b>".$userFullnm."</b>)");
	}
	
	$maxUrutan = $CDailyAct->detilActByDay($userIdLogin, $tglAct, $blnAct, $thnAct, "MAX(urutan)");
	$urutan = $maxUrutan + 1;
	
	$sick = "Y";
	$activity = "SICK";
	$bosread = "Y" ;
	$bosreadjob = "Y";
	$bosapprove = "Y";
	
	//echo "tgl : ".$tglAct."<br/>bulan : ".$blnAct."<br/>tahun : ".$thnAct."<br/>owner ID :".$userIdLogin."<br/>ownerName : ".$userFullnm."<br/>activity : ".$activity."<br/>bosread : ".$bosread."<br/>bosreadjob : ".$bosreadjob."<br/>bosapprove : ".$bosapprove."<br/>sick : ".$sick."<br/>dateadduser : ".$CPublic->userWhoAct();
	
	$CKoneksi->mysqlQuery("INSERT INTO tblactivity (urutan, ownerid, ownername, tanggal, bulan, tahun, activity, bosread, bosreadjob, bosapprove, sakit, addusrdt)VALUES ('".$urutan."', '".$userIdLogin."', '".$userFullnm."', '".$tglAct."', '".$blnAct."', '".$thnAct."', '".$activity."', '".$bosread."', '".$bosreadjob."', '".$bosapprove."', '".$sick."', '".$CPublic->userWhoAct()."');");
	
	$lastInsertId = mysql_insert_id();
	$CHistory->updateLog($userIdLogin, "Membuat Status Sakit (idactivity = <b>".$lastInsertId."</b>, date = <b>".$dateActGet."</b>, ownerId : <b>".$userIdLogin."</b>, ownerName = <b>".$userFullnm."</b>, Activity = <b>".$activity."</b>, Sick : <b>".$sick."</b>)");
}
//------ ==END OF== Function pilihBtnSick() pada halNewAct.php ---------
//------ ==START== Fungsi delete Activity ---------
if($aksiGet == "delete")
{
	$idactivityGet = $_GET['idactivity'];
	
	$CKoneksi->mysqlQuery("UPDATE tblactivity SET deletests=1, delusrdt='".$CPublic->userWhoAct()."' WHERE idactivity=".$idactivityGet." AND deletests=0");
	$CHistory->updateLog($userIdLogin, "Hapus Daily Activity (idactivity = <b>".$idactivityGet."</b>)");
}
//------ ==END OF== Fungsi delete Activity ---------
if($aksiPost == "urutNaik")
{
	$urutSeb = $_POST['urutSeb'];
	$urutSek = $_POST['urutSek'];
	$urutSet = $_POST['urutSet'];
	$idActivity = $_POST['idActivity'];
	
	// ganti urutan sebelumnya dengan sekarang
	$CKoneksi->mysqlQuery("UPDATE tblactivity SET urutan='".$urutSek."', updusrdt='".$CPublic->userWhoAct()."' WHERE ownerid='".$userIdLogin."' AND tanggal='".$tglAct."' AND bulan='".$blnAct."' AND tahun='".$thnAct."' AND urutan='".$urutSeb."' AND deletests=0;");
	// ganti urutan sekarang dengan sebelumnya
	$CKoneksi->mysqlQuery("UPDATE tblactivity SET urutan='".$urutSeb."', updusrdt='".$CPublic->userWhoAct()."' WHERE idactivity='".$idActivity."' AND deletests=0;");
	$CHistory->updateLog($userIdLogin, "Urutan naik untuk Daily Activity (idactivity = <b>".$idActivity."</b>, urutan sebelum = <b>".$urutSek."</b>, urutan sesudah = <b>".$urutSeb."</b>)");
}
if($aksiPost == "urutTurun")
{
	$urutSeb = $_POST['urutSeb'];
	$urutSek = $_POST['urutSek'];
	$urutSet = $_POST['urutSet'];
	$idActivity = $_POST['idActivity'];
	
	// ganti urutan sebelumnya dengan sekarang
	$CKoneksi->mysqlQuery("UPDATE tblactivity SET urutan='".$urutSek."', updusrdt='".$CPublic->userWhoAct()."' WHERE ownerid='".$userIdLogin."' AND tanggal='".$tglAct."' AND bulan='".$blnAct."' AND tahun='".$thnAct."' AND urutan='".$urutSet."' AND deletests=0;");
	// ganti urutan sekarang dengan sebelumnya
	$CKoneksi->mysqlQuery("UPDATE tblactivity SET urutan='".$urutSet."', updusrdt='".$CPublic->userWhoAct()."' WHERE idactivity='".$idActivity."' AND deletests=0;");
	$CHistory->updateLog($userIdLogin, "Urutan turun untuk Daily Activity (idactivity = <b>".$idActivity."</b>, urutan sebelum = <b>".$urutSek."</b>, urutan sesudah = <b>".$urutSet."</b>)");
}

$html = "";
$urutan = 0;
$query = $CKoneksi->mysqlQuery("SELECT * FROM tblactivity WHERE tanggal='".$tglAct."' AND bulan='".$blnAct."' AND tahun='".$thnAct."' AND ownerid=".$userIdLogin." AND deletests=0 ORDER BY urutan ASC");
$jmlRow = $CKoneksi->mysqlNRows($query);
while($row = $CKoneksi->mysqlFetch($query))
{
	$disable = "class=\"btnStandarKecil\"";
	$statusApproved = $CDailyAct->detilActByDay($userIdLogin, $tglAct, $blnAct, $thnAct, "bosapprove");
	if($statusApproved == "Y")
	{
		$disable = "class=\"btnStandarKecilDis\" disabled";
	}
	
	$urutan++;
	
	$iconNaik = "";
	$iconTurun = "";
	if($urutan > 1) // jika nomor urut lebih dari no pertama
	{
		$urutSeb = $CDailyAct->detilActUrutan($userIdLogin, $tglAct, $blnAct, $thnAct, $urutan-2, "urutan");
		$urutSek = $CDailyAct->detilActUrutan($userIdLogin, $tglAct, $blnAct, $thnAct, $urutan-1, "urutan");
		//$urutSet = $CDailyAct->detilActUrutan($userIdLogin, $tglAct, $blnAct, $thnAct, $urutan, "urutan");
		
		//$klikNaik = "ajaxGetUrutan('".$urutSeb."', '".$urutSek."', '".$row['idactivity']."', 'urutNaik', 'divIdUrutan');refreshPage();";
		$klikNaik = "klikUrutNaik('".$row['idactivity']."', '".$urutSeb."', '".$urutSek."', '');";
		$iconNaik = "<img src=\"../../picture/urutanAtas.png\" height=\"10\" width=\"50%\" style=\"cursor: pointer;\" onMouseOver=\"this.style.background='#1582f5';\" onMouseOut=\"this.style.background=''\" onClick=\"".$klikNaik."\" ".$disable.">";
	}
	if($urutan < $jmlRow) // jika nomor urut kurang dari no terakhir
	{
		//$urutSeb = $CDailyAct->detilActUrutan($userIdLogin, $tglAct, $blnAct, $thnAct, $urutan-2, "urutan");
		$urutSek = $CDailyAct->detilActUrutan($userIdLogin, $tglAct, $blnAct, $thnAct, $urutan-1, "urutan");
		$urutSet = $CDailyAct->detilActUrutan($userIdLogin, $tglAct, $blnAct, $thnAct, $urutan, "urutan");
		
		//$klikTurun = "ajaxGetUrutan('".$row['idactivity']."', '".$urutSek."', '".$urutSet."', 'urutTurun', 'divIdUrutan');refreshPage();";
		$klikTurun = "klikUrutTurun('".$row['idactivity']."', '', '".$urutSek."', '".$urutSet."');";
		$iconTurun = "<img src=\"../../picture/urutanBawah.png\" height=\"10\" width=\"50%\" style=\"cursor: pointer;\" onMouseOver=\"this.style.background='#1582f5';\" onMouseOut=\"this.style.background=''\" onClick=\"".$klikTurun."\" ".$disable.">";
	}
	
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
		$iconNewSpvCom = "<img src=\"../../picture/SuperiorComment.png\" height=\"10\" style=\"position:absolute;\" title=\"New Comment from your Superior\">";
	}
	else if($row['responcomment'] != "" && $row['oldresponcomment'] != $row['responcomment'])
	{
		$iconNewSpvCom = "<img src=\"../../picture/responComment.png\" height=\"10\" style=\"position:absolute;\" title=\"New Comment Response for your Superior\">";
	}
	
	$activity = "( ".$row['fromtime']." - ".$row['totime']." ) ".$CPublic->potongKarakter($row['activity'], 75);
	$relatedInfo = $CPublic->potongKarakter($row['relatedinfo'], 90) ;
	$statusComment = $warnaStatus.$status." | ".$CPublic->potongKarakter($row['spvcomment'], 75);
	$disableCutiSakit = "class=\"btnStandarKecil\"";
	if($row['cuti'] == "Y" || $row['sakit'] == "Y")
	{
		$activity = $row['activity'];
		$relatedInfo = "&nbsp;";
		$statusComment = "&nbsp;";
		$disableCutiSakit = "class=\"btnStandarKecilDis\" disabled";
	}
	
	$lockPheonwj = $CDailyAct->detilAct($row['idactivity'], "lockedit");
	if($lockPheonwj == "Y")
	{
		$disable = "class=\"btnStandarKecilDis\" disabled";
	}
	
	$html.="<tr onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\">
            <td class=\"tdMyFolder\">
                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                <tr>
                    <td width=\"5%\">
						<table width=\"100%\" height=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                        <tr>
                        	<td height=\"10\" align=\"center\">".$iconNaik."</td>
                        </tr>	
                    	<tr>
                        	<td height=\"35\" align=\"center\" style=\"font-size:22px;color:#006;font-weight:bold;font-family:Tahoma;".$bosReadJob."\" onclick=\"parent.openThickboxWindow('".$row['idactivity']."','edit');\">".$urutan."</td>
                        </tr>
                        <tr>
                        	<td height=\"10\" align=\"center\">".$iconTurun."</td>
                        </tr>
                        </table>
					</td>
					<!-- <td width=\"68%\"> JIKA PAKAI BUTTON EDIT -->
                    <td width=\"78%\" onclick=\"parent.openThickboxWindow('".$row['idactivity']."','edit');\">
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
						<button type=\"button\" onclick=\"parent.openThickboxWindow('".$row['idactivity']."','edit');\" style=\"width:75px;height:55px;\" ".$disableCutiSakit." title=\"Edit/View this line Activity\">
                            <table width=\"100%\" height=\"100%\">
                              <tr>
                                <td align=\"center\"><img src=\"../../picture/Auction-blue-32.png\" height=\"25\"/> </td>
                                
                              </tr>
                              <tr>
                                <td align=\"center\">EDIT</td>
                              </tr>
                            </table>
                        </button>
						&nbsp;
						<button type=\"button\" onclick=\"parent.deleteAct('".$row['idactivity']."');\" style=\"width:75px;height:55px;\" ".$disable." title=\"Delete this line Activity\">
                            <table width=\"100%\" height=\"100%\">
                              <tr>
                                <td align=\"center\"><img src=\"../../picture/Button-Cross-blue-32.png\" height=\"25\"/> </td>
                                
                              </tr>
                              <tr>
                                <td align=\"center\">DELETE</td>
                              </tr>
                            </table>
                        </button>
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
$statusRead = $CDailyAct->detilActByDay($userIdLogin, $tglAct, $blnAct, $thnAct, "bosread");
$statusApprove = $CDailyAct->detilActByDay($userIdLogin, $tglAct, $blnAct, $thnAct, "bosapprove");
$statusCuti = $CDailyAct->detilActByDay($userIdLogin, $tglAct, $blnAct, $thnAct, "cuti");
$statusSakit = $CDailyAct->detilActByDay($userIdLogin, $tglAct, $blnAct, $thnAct, "sakit");	

$status = "";
$statusRevisi = "";

if($halamanGet == "gantiDateAct" || $halamanGet == "leaveStatus" || $halamanGet == "sickStatus")
{	
	$idRevisi = $CDailyAct->detilActByDay($userIdLogin, $tglAct, $blnAct, $thnAct, "idrevisi");
	if($idRevisi != "00000" && $idRevisi != "") // jika di activity terdapat revisi
	{
		$revisiKe = $CDailyAct->detilRevisi($idRevisi, "revisike");
		$statusRevisi = "&nbsp;|&nbsp;<span class=\"teksLvlFolder\" style=\"text-decoration:underline;\">R".$revisiKe."</span>";
	}
	
	$btnNewAct = "&nbsp;<button class=btnStandar id=btnNewFolder onclick=openThickboxWindow('','newAct'); style=width:96px;height:29px; title=Write A New Activity Today >\"
                    +\"<table cellpadding=0 cellspacing=0 width=100% height=100% >\"
                    +\"<tr>\"
                    +\"    <td align=center><img src=../picture/Folder-blue-32.png height=20/> </td>\"
                    +\"    <td align=center>New Activity</td>\"
                    +\"</tr>\"
                    +\"</table>\"
                +\"</button>";
	
	if($statusRead == "Y") // jika activity sudah dibuka / dibaca oleh atasan langsung
	{
		$status = "<span class=\"teksLvlFolder\" style=\"color:#666;\">STATUS</span> : <span class=\"teksLvlFolder\" style=\"text-decoration:underline;\">READ</span>&nbsp;";
	}
	if($statusApprove == "Y") // jika activity sudah di approve oleh atasan langsung
	{
		$status = "<span class=\"teksLvlFolder\" style=\"color:#666;\">STATUS</span> : <span class=\"teksLvlFolder\" style=\"text-decoration:underline;\">APPROVE</span>&nbsp;";
		$btnNewAct = "&nbsp;";
	}	
	if($statusCuti == "Y") // jika pada hari itu user membuat status cuti
	{
		$status = "<span class=\"teksLvlFolder\" style=\"color:#666;\">STATUS</span> : <span class=\"teksLvlFolder\" style=\"text-decoration:underline;\">LEAVE</span>&nbsp;";
		$btnNewAct = "&nbsp;";
	}if($statusSakit == "Y") // jika pada hari itu user membuat status sakit
	{
		$status = "<span class=\"teksLvlFolder\" style=\"color:#666;\">STATUS</span> : <span class=\"teksLvlFolder\" style=\"text-decoration:underline;\">SICK</span>&nbsp;";
		$btnNewAct = "&nbsp;";
	}
	
	//echo "alert('".$statusRead."');";
	echo "parent.document.getElementById('idHalBtnNewAct').innerHTML = \"".$btnNewAct."\"; ";
	echo "parent.document.getElementById('idHalStatus').innerHTML = '&nbsp;".$status.$statusRevisi."';";
	
	$judul = "&nbsp;:: MY DAILY ACTIVITY LIST ::";
	$hariLiburSqlServer = $CPublic->hariLiburSqlServer($koneksiOdbcId, $koneksiOdbc, $tglAct, $blnAct, $thnAct);
	$jmlJob = $CDailyAct->jmlJob($CPublic, $userIdLogin, $tglAct, $blnAct, $thnAct);

	if($hariLiburSqlServer == 1)
	{
		$judul = "&nbsp;:: HOLIDAY ::";
	}
	if($hariLiburSqlServer == 1 && $jmlJob > 0)
	{
		$judul = "&nbsp;:: MY DAILY ACTIVITY LIST ::";
	}
	
	echo "parent.document.getElementById('tdJudulTitle').innerHTML = '".$judul."';";
	}
?>
</script>
</HTML>