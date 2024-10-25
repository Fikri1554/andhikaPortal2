<?php
require_once("../config.php");
// ======== START == Subordinate Print Activity =================================================
if($aksiPost == "printSubordinateJob" || $aksiPost == "printSubordinateWeeklyJob")
{	
	if($aksiPost == "printSubordinateJob")
	{
		$tpl = new myTemplate("templates/laporanSubordinateJob.html");
		$dateActPost = $_POST['dateAct'];
	}
	if($aksiPost == "printSubordinateWeeklyJob"){
		$tpl = new myTemplate("templates/laporanSubordinateWeeklyJob.html");
		$dateActPost = $_POST['dateAct2'];
	}	
	
	$tpl->prepare();

	$tglAct =  substr($dateActPost,0,2);
	$blnAct =  substr($dateActPost,3,2);
	$thnAct =  substr($dateActPost,6,4);
	$subordinateIdPost = $_POST['subordinateId'];
	$subordinateEmpNoPost = $_POST['empNo'];
	
	$subordinateName = $CLogin->detilLogin($subordinateIdPost, "userfullnm");
	$subordinateDiv = $CLogin->detilLogin($subordinateIdPost, "nmdiv");
	$subordinateDept = $CLogin->detilLogin($subordinateIdPost, "nmdept");
	
	$empAtasanLangsung = $CEmployee->cariAtasanLangsung($subordinateEmpNoPost);
	$subordinateBos = $CEmployee->detilEmp($empAtasanLangsung, "nama");

	$tpl->Assign("subordinateName", $subordinateName);
	$tpl->Assign("subordinateDiv", $subordinateDiv);
	$tpl->Assign("subordinateDept", $subordinateDept);
	$tpl->Assign("subordinateBos", $subordinateBos);
	
	$statusRead = "<span style=\"color:#F00;\">NOT READ</span>";
	$bosRead = $CDailyAct->detilActByDay($subordinateIdPost, $tglAct, $blnAct, $thnAct, "bosread");
	if($bosRead == "Y")
	{
		$statusRead = "<span style=\"color:#090;\">READ</span>";
	}
	
	$statusApprove = "<span style=\"color:#F00;\">NOT APPROVED</span>";
	$bosApprove = $CDailyAct->detilActByDay($subordinateIdPost, $tglAct, $blnAct, $thnAct, "bosapprove");
	if($bosApprove == "Y")
	{
		$statusApprove = "<span style=\"color:#090;\">APPROVED</span>";
	}
	
	$tpl->Assign("statusRead", $statusRead);
	$tpl->Assign("statusApprove", $statusApprove);
	
	$dateActivity = ucfirst(strtolower($CPublic->bulanSetengah($blnAct, "eng")))." ".$tglAct.", ".$thnAct;
	$tpl->Assign("dateActivity", $dateActivity);
	
	$dateActDB = $CPublic->convTglDB($dateActPost);
	
	$nomor = 1;
	$isiJobList = "";
	$isiJobWeeklyList = "";
	//$query = $CKoneksi->mysqlQuery("SELECT * FROM tblactivity WHERE tanggal='".$tglAct."' AND bulan='".$blnAct."' AND tahun='".$thnAct."' AND ownerid=".$subordinateIdPost." AND deletests=0 ORDER BY urutan ASC");
	
	if($aksiPost == "printSubordinateJob") // PRINT DAILY UNTUK SUBORDINATE
	{
		$query = $CKoneksi->mysqlQuery("SELECT fromtime, totime, activity, relatedinfo, status, problemident, corrective, kpinumber, bosread, bosreadjob, bosapprove, bulan, tanggal, tahun, CASE WHEN datefinish='0000-00-00' THEN '' ELSE DATE_FORMAT(datefinish,'%b %d, %Y') END AS datefinish FROM tblactivity WHERE tanggal='".$tglAct."' AND bulan='".$blnAct."' AND tahun='".$thnAct."' AND ownerid=".$subordinateIdPost." AND deletests=0 ORDER BY urutan ASC");
	}
	if($aksiPost == "printSubordinateWeeklyJob") // PRINT WEEKLY UNTUK SUBORDINATE
	{
		$mingguKe = $CPublic->cariMingguKe($dateActDB)." in ".ucfirst(strtolower($CPublic->detilBulanNamaAngka($blnAct, "eng")));
		$tpl->Assign("mingguKe", $mingguKe);
		$query = $CKoneksi->mysqlQuery("SELECT idactivity, fromtime, totime, activity, relatedinfo, status, problemident, corrective, kpinumber, bosread, bosreadjob, bosapprove, bulan, tanggal, tahun, cuti, sakit,
CASE WHEN datefinish = '0000-00-00' THEN '' ELSE DATE_FORMAT(datefinish,'%b %d, %Y') END AS datefinish, referidactivity
FROM tblactivity WHERE ownerid=".$subordinateIdPost." AND 
DATE(CONCAT(tahun,'-',bulan,'-', tanggal)) BETWEEN DATE_SUB('".$dateActDB."', INTERVAL DAYOFWEEK('".$dateActDB."')-1 DAY) AND DATE_ADD('".$dateActDB."', INTERVAL 7-DAYOFWEEK('".$dateActDB."') DAY)
AND deletests=0 ORDER BY tahun, bulan, tanggal ASC;");
	}	
	
	while($row = $CKoneksi->mysqlFetch($query))
	{
		$activity = ucfirst($row['activity']);
		$relatedInfo = ucfirst($row['relatedinfo']);
		$problemIdent = ucfirst($row['problemident']);
		$corrective = ucfirst($row['corrective']);
		$date = ucfirst(strtolower($CPublic->bulanSetengah($row['bulan'], "eng")." ".$row['tanggal'].", ".$row['tahun']));
		
		$status = "";
		if($row['status'] == "postpone")
		{	$status = "Postpone";	}	
		if($row['status'] == "finish")
		{	$status = "Finish";	}
		if($row['status'] == "onprogress")
		{	$status = "On Progress";	}
		
		if($aksiPost == "printSubordinateJob")
		{
			$isiJobList.="<tr class=\"style4\" valign=\"top\">
							<td class=\"tabelBorderTopRightNull\" align=\"center\" height=\"20\">&nbsp;".$nomor++."&nbsp;</td>
							<td class=\"tabelBorderTopRightNull\" align=\"center\">&nbsp;".$row['fromtime']."&nbsp;</td>
							<td class=\"tabelBorderTopRightNull\" align=\"center\">&nbsp;".$row['totime']."&nbsp;</td>
							<td class=\"tabelBorderTopRightNull\"><p class=\"paragraph1\">".$activity."&nbsp;</p></td>
							<td class=\"tabelBorderTopRightNull\"><p class=\"paragraph1\" style=\"text-align:justify;\">".$relatedInfo."&nbsp;</p></td>
							<td class=\"tabelBorderTopRightNull\">&nbsp;".$status."</td>
							<td class=\"tabelBorderTopRightNull\"><p class=\"paragraph1\" style=\"text-align:justify;\">".$problemIdent."&nbsp;</p></td>
							<td class=\"tabelBorderTopRightNull\"><p class=\"paragraph1\" style=\"text-align:justify;\">".$corrective."&nbsp;</p></td>
							<td class=\"tabelBorderTopNull\" align=\"center\">&nbsp;".$row['kpinumber']."&nbsp;</td>
						</tr>";
			$isiJobList = $isiJobList;
		}
		if($aksiPost == "printSubordinateWeeklyJob")
		{
			$allIdActivity = allIdActInWeek($CKoneksi, $subordinateIdPost, $dateActDB); // SEMUA IDACTIVITY DI MINGGU DARI TGL TERPILIH
			$cariReferIdAct = strpos($allIdActivity, $row['referidactivity']); // CARI REFERIDACTIVITY DI ARRAY $allIdActivity
			
			$detilStatus = $CDailyAct->detilAct($row['idactivity'], "status");
			
			$nilaiStatus = "";
			
//SECARA DEFAULT MENDAPATKAN DATEFINISH MANA STATUSNYA FINISH DAN REFERIDACTIVITY TIDAK KOSONG
			$dateStart = $date;
			$dateFinish = cariDateFinish($CKoneksi, $subordinateIdPost, $dateActDB, $row['idactivity']);
// JIKA STATUS ONPROGRESS DALAM MINGGU DARI TANGGAL TERPILIH (ACTIVITY DIBUAT DI MINGGU YANG SAMA)
			if($row['status'] == "onprogress" && $row['referidactivity'] == "00000000000")
			{
				$tampil = "YES";
			}
// JIKA STATUS ONPROGRESS DALAM MINGGU DARI TANGGAL TERPILIH (ACTIVITY DIBUAT DI MINGGU BERBEDA)
			else if($row['status'] == "onprogress" && $row['referidactivity'] != "00000000000" && $cariReferIdAct === false)
			{
				$tampil = "YES";
			}
// JIKA TERDAPAT STATUS LEAVE/SICK PADA MINGGU TERSEBUT
			else if($row['cuti'] == "Y" || $row['sakit'] == "Y")
			{
				$tampil = "YES";
			}
// JIKA STATUS POSTPONE DALAM MINGGU DARI TANGGAL TERPILIH (ACTIVITY DIBUAT DI MINGGU YANG SAMA)
			else if($row['status'] == "postpone" && $row['referidactivity'] == "00000000000")
			{
				$tampil = "YES";
			}
// JIKA STATUS POSTPONE DALAM MINGGU DARI TANGGAL TERPILIH (ACTIVITY DIBUAT DI MINGGU BERBEDA)
			else if($row['status'] == "postpone" && $row['referidactivity'] != "00000000000" && $cariReferIdAct === false)
			{
				$tampil = "YES";
			}
//JIKA STATUS FINISH DAN ISI REFERIDACTIVITY NYA KOSONG ATAU FINISH PADA HARI BERSAMAAN DENGAN ACTIVITY TSB DIBUAT 
			else if($row['status'] == "finish" && $row['referidactivity'] == "00000000000") 
			{
				$tampil = "YES";
				$dateFinish = $row['datefinish'];
			}
//JIKA STATUS FINISH DAN ISI REFERIDACTIVITY NYA IDACTIVITY DARI MINGGU YANG BERBEDA
			else if($row['status'] == "finish" && $cariReferIdAct === false)
			{
				$tampil = "YES";
				$dateStart = $CDailyAct->detilAct($row['referidactivity'], "DATE_FORMAT(DATE(CONCAT(tahun,'-',bulan,'-', tanggal)),'%b %d, %Y')");
				$dateFinish = $row['datefinish'];
			}
			else
			{
				$tampil = "NO";
			}
			
			if($tampil == "YES")
			{
				$nilaiStatus = $status;
				// CARI STATUS DARI IDACTIVITY TSB BERDASARKAN REFERIDACTICITY
				$statusActByPrefer = $CDailyAct->statusActByPrefer($row['idactivity'], $subordinateIdPost); 
				// CARI STRING FINISH ADA TIDAK DALAM ARRAY $statusActByPrefer
				$strFinish = strpos($statusActByPrefer, "finish"); 
				if($strFinish !== false)  // JIKA TERDAPAT TEKS "finish" DI ARRAY MAKA
				{
					 $nilaiStatus = "Finish";
					 if($dateFinish == "")
					 {
						 $nilaiStatus = $status;
					 }
				}	
			$isiJobWeeklyList.="<tr class=\"style4\" valign=\"top\">
								<td class=\"tabelBorderTopRightNull\" align=\"center\" height=\"20\">&nbsp;".$nomor++."&nbsp;</td>
								<td class=\"tabelBorderTopRightNull\" align=\"center\">&nbsp;".$dateStart."&nbsp;</td>
								<td class=\"tabelBorderTopRightNull\"><p class=\"paragraph1\">".$activity."&nbsp;</p></td>
								<td class=\"tabelBorderTopRightNull\"><p class=\"paragraph1\">".$relatedInfo."&nbsp;</p></td>
								<td class=\"tabelBorderTopRightNull\" align=\"center\">&nbsp;".$nilaiStatus."</td>
								<td class=\"tabelBorderTopRightNull\" align=\"center\">&nbsp;".$dateFinish."&nbsp;</td>
								<td class=\"tabelBorderTopRightNull\"><p class=\"paragraph1\" style=\"text-align:justify;\">".$problemIdent."&nbsp;</p></td>
								<td class=\"tabelBorderTopNull\"><p class=\"paragraph1\" style=\"text-align:justify;\">".$corrective."&nbsp;</p></td>
							</tr>";	
			}
			$isiJobList = $isiJobWeeklyList;
		}
	}
	
	$tpl->Assign("isiJobList", $isiJobList);
	$tpl->printToScreen();
}
// ======== END == Subordinate Print Activity =================================================

// ======== START == Print Activity ===========================================================
if($aksiPost == "printJob" || $aksiPost == "printJobWeekly"){
	if($aksiPost == "printJob")
	{
		$tpl = new myTemplate("templates/laporanJob.html");
		$dateActPost = $_POST['dateAct'];
	}
	if($aksiPost == "printJobWeekly"){
		$tpl = new myTemplate("templates/laporanWeeklyJob.html");
		$dateActPost = $_POST['dateAct2'];
	}	
	
	$tpl->prepare();
	
	$tglAct =  substr($dateActPost,0,2);
	$blnAct =  substr($dateActPost,3,2);
	$thnAct =  substr($dateActPost,6,4);
	
	$subordinateName = $CLogin->detilLogin($userIdLogin, "userfullnm");
	$userDiv = $CLogin->detilLogin($userIdLogin, "nmdiv");
	$userDept = $CLogin->detilLogin($userIdLogin, "nmdept");
	
	$empAtasanLangsung = $CEmployee->cariAtasanLangsung($userEmpNo);
	$userBos = $CEmployee->detilEmp($empAtasanLangsung, "nama");

	$tpl->Assign("userName", $subordinateName);
	$tpl->Assign("userDiv", $userDiv);
	$tpl->Assign("userDept", $userDept);
	$tpl->Assign("userBos", $userBos);
	
	$statusRead = "<span style=\"color:#F00;\">NOT READ</span>";
	$bosRead = $CDailyAct->detilActByDay($userIdLogin, $tglAct, $blnAct, $thnAct, "bosread");
	if($bosRead == "Y")
	{
		$statusRead = "<span style=\"color:#090;\">READ</span>";
	}
	
	$statusApprove = "<span style=\"color:#F00;\">NOT APPROVED</span>";
	$bosApprove = $CDailyAct->detilActByDay($userIdLogin, $tglAct, $blnAct, $thnAct, "bosapprove");
	if($bosApprove == "Y")
	{
		$statusApprove = "<span style=\"color:#090;\">APPROVED</span>";
	}
	
	$tpl->Assign("statusRead", $statusRead);
	$tpl->Assign("statusApprove", $statusApprove);
	
	$dateActivity = ucfirst(strtolower($CPublic->bulanSetengah($blnAct, "eng")))." ".$tglAct.", ".$thnAct;
	$tpl->Assign("dateActivity", $dateActivity);

	$dateActDB = $CPublic->convTglDB($dateActPost);
	
	$nomor = 1;
	$isiJobList = "";
	$isiJobWeeklyList = "";
	//$query = $CKoneksi->mysqlQuery("SELECT * FROM tblactivity WHERE tanggal='".$tglAct."' AND bulan='".$blnAct."' AND tahun='".$thnAct."' AND ownerid=".$userIdLogin." AND deletests=0 ORDER BY urutan ASC");
	
	if($aksiPost == "printJob") // PRINT DAILY UNTUK USER SEDANG LOGIN
	{
		$tpl->Assign("mingguKe", "&nbsp;");
		$query = $CKoneksi->mysqlQuery("SELECT fromtime, totime, activity, relatedinfo, status, problemident, corrective, kpinumber, bosread, bosreadjob, bosapprove, bulan, tanggal, tahun, CASE WHEN datefinish='0000-00-00' THEN '' ELSE DATE_FORMAT(datefinish,'%b %d, %Y') END AS datefinish FROM tblactivity WHERE tanggal='".$tglAct."' AND bulan='".$blnAct."' AND tahun='".$thnAct."' AND ownerid=".$userIdLogin." AND deletests=0 ORDER BY urutan ASC");
	}
	if($aksiPost == "printJobWeekly") // PRINT WEEKLY UNTUK USER SEDANG LOGIN
	{
		$mingguKe = $CPublic->cariMingguKe($dateActDB)." in ".ucfirst(strtolower($CPublic->detilBulanNamaAngka($blnAct, "eng")));
		$tpl->Assign("mingguKe", $mingguKe);
		$query = $CKoneksi->mysqlQuery("SELECT idactivity, fromtime, totime, activity, relatedinfo, status, problemident, corrective, kpinumber, bosread, bosreadjob, bosapprove, bulan, tanggal, tahun, cuti, sakit,
CASE WHEN datefinish = '0000-00-00' THEN '' ELSE DATE_FORMAT(datefinish,'%b %d, %Y') END AS datefinish, referidactivity
FROM tblactivity WHERE ownerid=".$userIdLogin." AND 
DATE(CONCAT(tahun,'-',bulan,'-', tanggal)) BETWEEN DATE_SUB('".$dateActDB."', INTERVAL DAYOFWEEK('".$dateActDB."')-1 DAY) AND DATE_ADD('".$dateActDB."', INTERVAL 7-DAYOFWEEK('".$dateActDB."') DAY)
AND deletests=0 ORDER BY tahun, bulan, tanggal ASC;");
	}	
	
	while($row = $CKoneksi->mysqlFetch($query))
	{
		$activity = ucfirst($row['activity']);
		$relatedInfo = ucfirst($row['relatedinfo']);
		$problemIdent = ucfirst($row['problemident']);
		$corrective = ucfirst($row['corrective']);
		$date = ucfirst(strtolower($CPublic->bulanSetengah($row['bulan'], "eng")." ".$row['tanggal'].", ".$row['tahun']));
		
		$status = "";
		if($row['status'] == "postpone")
		{	$status = "Postpone";	}	
		if($row['status'] == "finish")
		{	$status = "Finish";	}
		if($row['status'] == "onprogress")
		{	$status = "On Progress";	}	
		
		if($aksiPost == "printJob")
		{
			$isiJobList.="
			<tr class=\"style4\" valign=\"top\">
				<td class=\"tabelBorderTopRightNull\" align=\"center\" height=\"20\">&nbsp;".$nomor++."&nbsp;</td>
				<td class=\"tabelBorderTopRightNull\" align=\"center\">&nbsp;".$row['fromtime']."&nbsp;</td>
				<td class=\"tabelBorderTopRightNull\" align=\"center\">&nbsp;".$row['totime']."&nbsp;</td>
				<td class=\"tabelBorderTopRightNull\"><p class=\"paragraph1\">".$activity."&nbsp;</p></td>
				<td class=\"tabelBorderTopRightNull\"><p class=\"paragraph1\" style=\"text-align:justify;\">".$relatedInfo."&nbsp;</p></td>
				<td class=\"tabelBorderTopRightNull\">&nbsp;".$status."</td>
				<td class=\"tabelBorderTopRightNull\"><p class=\"paragraph1\" style=\"text-align:justify;\">".$problemIdent."&nbsp;</p></td>
				<td class=\"tabelBorderTopRightNull\"><p class=\"paragraph1\" style=\"text-align:justify;\">".$corrective."&nbsp;</p></td>
				<td class=\"tabelBorderTopNull\" align=\"center\">&nbsp;".$row['kpinumber']."&nbsp;</td>
			</tr>";	
			$isiJobList = $isiJobList;
		}
		
		if($aksiPost == "printJobWeekly")
		{
			$allIdActivity = allIdActInWeek($CKoneksi, $userIdLogin, $dateActDB); // SEMUA IDACTIVITY DI MINGGU DARI TGL TERPILIH
			$cariReferIdAct = strpos($allIdActivity, $row['referidactivity']); // CARI REFERIDACTIVITY DI ARRAY $allIdActivity
			
			$detilStatus = $CDailyAct->detilAct($row['idactivity'], "status");
			
			$nilaiStatus = "";
			
//SECARA DEFAULT MENDAPATKAN DATEFINISH MANA STATUSNYA FINISH DAN REFERIDACTIVITY TIDAK KOSONG
			$dateStart = $date;
			$dateFinish = cariDateFinish($CKoneksi, $userIdLogin, $dateActDB, $row['idactivity']);
// JIKA STATUS ONPROGRESS DALAM MINGGU DARI TANGGAL TERPILIH (ACTIVITY DIBUAT DI MINGGU YANG SAMA)
			if($row['status'] == "onprogress" && $row['referidactivity'] == "00000000000")
			{
				$tampil = "YES";
			}
// JIKA STATUS ONPROGRESS DALAM MINGGU DARI TANGGAL TERPILIH (ACTIVITY DIBUAT DI MINGGU BERBEDA)
			else if($row['status'] == "onprogress" && $row['referidactivity'] != "00000000000" && $cariReferIdAct === false)
			{
				$tampil = "YES";
			}
// JIKA TERDAPAT STATUS LEAVE/SICK PADA MINGGU TERSEBUT
			else if($row['cuti'] == "Y" || $row['sakit'] == "Y")
			{
				$tampil = "YES";
			}
// JIKA STATUS POSTPONE DALAM MINGGU DARI TANGGAL TERPILIH (ACTIVITY DIBUAT DI MINGGU YANG SAMA)
			else if($row['status'] == "postpone" && $row['referidactivity'] == "00000000000")
			{
				$tampil = "YES";
			}
// JIKA STATUS POSTPONE DALAM MINGGU DARI TANGGAL TERPILIH (ACTIVITY DIBUAT DI MINGGU BERBEDA)
			else if($row['status'] == "postpone" && $row['referidactivity'] != "00000000000" && $cariReferIdAct === false)
			{
				$tampil = "YES";
			}
//JIKA STATUS FINISH DAN ISI REFERIDACTIVITY NYA KOSONG ATAU FINISH PADA HARI BERSAMAAN DENGAN ACTIVITY TSB DIBUAT 
			else if($row['status'] == "finish" && $row['referidactivity'] == "00000000000") 
			{
				$tampil = "YES";
				$dateFinish = $row['datefinish'];
			}
//JIKA STATUS FINISH DAN ISI REFERIDACTIVITY NYA IDACTIVITY DARI MINGGU YANG BERBEDA
			else if($row['status'] == "finish" && $cariReferIdAct === false)
			{
				$tampil = "YES";
				$dateStart = $CDailyAct->detilAct($row['referidactivity'], "DATE_FORMAT(DATE(CONCAT(tahun,'-',bulan,'-', tanggal)),'%b %d, %Y')");
				$dateFinish = $row['datefinish'];
			}
			else
			{
				$tampil = "NO";
			}
			
			if($tampil == "YES")
			{
				$nilaiStatus = $status;
				// CARI STATUS DARI IDACTIVITY TSB BERDASARKAN REFERIDACTICITY
				$statusActByPrefer = $CDailyAct->statusActByPrefer($row['idactivity'], $userIdLogin); 
				// CARI STRING FINISH ADA TIDAK DALAM ARRAY $statusActByPrefer
				$strFinish = strpos($statusActByPrefer, "finish"); 
				if($strFinish !== false)  // JIKA TERDAPAT TEKS "finish" DI ARRAY MAKA
				{
					 $nilaiStatus = "Finish";
					 if($dateFinish == "")
					 {
						 $nilaiStatus = $status;
					 }
				} 	
				
				$isiJobWeeklyList.="
				<tr class=\"style4\" valign=\"top\">
					<td class=\"tabelBorderTopRightNull\" align=\"center\" height=\"20\">&nbsp;".$nomor++."&nbsp;</td>
					<td class=\"tabelBorderTopRightNull\" align=\"center\">&nbsp;".$dateStart."&nbsp;</td>
					<td class=\"tabelBorderTopRightNull\"><p class=\"paragraph1\">".$activity."&nbsp;</p></td>
					<td class=\"tabelBorderTopRightNull\"><p class=\"paragraph1\">".$relatedInfo."&nbsp;</p></td>
					<td class=\"tabelBorderTopRightNull\" align=\"center\">&nbsp;".$nilaiStatus."</td>
					<td class=\"tabelBorderTopRightNull\" align=\"center\">&nbsp;".$dateFinish."&nbsp;</td>
					<td class=\"tabelBorderTopRightNull\"><p class=\"paragraph1\" style=\"text-align:justify;\">".$problemIdent."&nbsp;</p></td>
					<td class=\"tabelBorderTopNull\"><p class=\"paragraph1\" style=\"text-align:justify;\">".$corrective."&nbsp;</p></td>
				</tr>";
			}
			
			$isiJobList = $isiJobWeeklyList;
		}
	}
	
	$tpl->Assign("isiJobList", $isiJobList);
	
	$tpl->printToScreen();
}
// ======== END == Print Activity ===================================================================== 

// ======== START == Print Daily Act weekly interval function =============================================== 
function allIdActInWeek($CKoneksi, $ownerID, $dateActDB) // mendapatkan ID Activity dengan interval mingguan
{
	$query = $CKoneksi->mysqlQuery("SELECT idactivity
FROM tblactivity WHERE ownerid=".$ownerID." AND 
DATE(CONCAT(tahun,'-',bulan,'-', tanggal)) BETWEEN DATE_SUB('".$dateActDB."', INTERVAL DAYOFWEEK('".$dateActDB."')-1 DAY) AND DATE_ADD('".$dateActDB."', INTERVAL 7-DAYOFWEEK('".$dateActDB."') DAY)
AND deletests=0 ORDER BY tahun, bulan, tanggal ASC;");
	while($row = $CKoneksi->mysqlFetch($query))
	{
		$allIdAct .= $row['idactivity'].",";
	}
	
	return $allIdAct ;
}

function cariDateFinish($CKoneksi, $ownerID, $dateActDB, $idAct) // mendapatkan datefinish dengan interval mingguan
{
	$nilai = "";
	$query = $CKoneksi->mysqlQuery("SELECT CASE WHEN datefinish = '0000-00-00' THEN '' ELSE DATE_FORMAT(datefinish,'%b %d, %Y') END AS datefinish FROM tblactivity WHERE ownerid=".$ownerID." AND 
DATE(CONCAT(tahun,'-',bulan,'-', tanggal)) BETWEEN DATE_SUB('".$dateActDB."', INTERVAL DAYOFWEEK('".$dateActDB."')-1 DAY) AND DATE_ADD('".$dateActDB."', INTERVAL 7-DAYOFWEEK('".$dateActDB."') DAY)
AND referidactivity = '".$idAct."' AND status = 'finish' AND deletests=0;");
	$row = $CKoneksi->mysqlFetch($query);
	$nilai = $row['datefinish'];
	
	return $nilai;
	
}
// ======== END == Print Daily Act weekly interval function ================================================= 
?>