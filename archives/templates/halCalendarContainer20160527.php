<?php
require_once("../../config.php");

// Get current year, month and day
list($iNowYear, $iNowMonth, $iNowDay) = explode('-', date('Y-m-d'));
 
// Get current year and month depending on possible GET parameters
if (isset($_GET['month'])) {
	list($iMonth, $iYear) = explode('-', $_GET['month']);
	$iMonth = (int)$iMonth;
	$iYear = (int)$iYear;
} else {
	list($iMonth, $iYear) = explode('-', date('n-Y'));
}
 
// Get name and number of days of specified month
$iTimestamp = mktime(0, 0, 0, $iMonth, $iNowDay, $iYear);
list($sMonthName, $iDaysInMonth) = explode('-', date('F-t', $iTimestamp));
 
// Get previous year and month
$iPrevYear = $iYear;
$iPrevMonth = $iMonth - 1;
if ($iPrevMonth <= 0) {
	$iPrevYear--;
	$iPrevMonth = 12; // set to December
}
 
// Get next year and month
$iNextYear = $iYear;
$iNextMonth = $iMonth + 1;
if ($iNextMonth > 12) {
	$iNextYear++;
	$iNextMonth = 1;
}
 
// Get number of days of previous month
$iPrevDaysInMonth = (int)date('t', mktime(0, 0, 0, $iPrevMonth, $iNowDay, $iPrevYear));
 
// Get numeric representation of the day of the week of the first day of specified (current) month
$iFirstDayDow = (int)date('w', mktime(0, 0, 0, $iMonth, 1, $iYear));
 
// On what day the previous month begins
$iPrevShowFrom = $iPrevDaysInMonth - $iFirstDayDow + 1;
 
// If previous month
$bPreviousMonth = ($iFirstDayDow > 0);
 
// Initial day
$iCurrentDay = ($bPreviousMonth) ? $iPrevShowFrom : 1;
 
$bNextMonth = false;
$sCalTblRows = '';
 
// Generate rows for the calendar
for ($i = 0; $i < 6; $i++)
{ // 6-weeks range
	$sCalTblRows .= '<tr>';
	for ($j = 0; $j < 7; $j++) 
	{ // 7 days a week
	
		$iconRead = '&nbsp;&nbsp;&nbsp;&nbsp;';
		$iconApprove = '';
		$iconSpvComment = '';
		$iconSubComment = '';
 		$tanggal = '';
 		$bulan = '';
		$tahun = '';
		$sClass = '';
		$alignTeksAct = 'left';
		
		if ($iNowYear == $iYear && $iNowMonth == $iMonth && $iNowDay == $iCurrentDay && !$bPreviousMonth && !$bNextMonth) 
		{
			$sClass = 'today';
			$tanggal = zerofill($iCurrentDay,2);
			$bulan = zerofill($iMonth,2);
			$tahun = $iYear;
		} 
		elseif (!$bPreviousMonth && !$bNextMonth) 
		{
			$sClass = 'current';
			
			$tanggal = zerofill($iCurrentDay,2);
			$bulan = zerofill($iMonth,2);
			$tahun = $iYear;
			
			if($aksiGet == "dailyActViewMonth" || $aksiGet == "openSubordinateDailyActBalik")
			{
				$dateActGet = $_GET['dateAct'];
				$tglPilihSeb =  substr($dateActGet,0,2);
				if($tanggal == $tglPilihSeb)
				{
					$sClass = 'current:hover';
				}
			}
		}
		$userIdSelect = $_GET['userIdSelect'];
		
		$nmHari = nmHariServer($CKoneksi, $tanggal, $bulan, $tahun); // ambil nama hari dari server mysql
		//$warnaHari = " style=\"color:#666;\"";
		//$ketLibur = "";
		$warnaHari = " style=\"color:#CC0000;\"";
		$ketLibur = " title=\"SUNDAY\"";
		
		if($nmHari != "Sunday" || $nmHari != "Saturday") // jika tanggal merupakan hari minggu
		{
			$warnaHari = " style=\"color:#666;\"";
			$ketLibur = "";
			$empJobList = "";
			if($tanggal != "") // jika tanggal berada dalam bulan yang dipilih
			{
				$statusApprove = detilActByDay($CKoneksi, $userIdSelect, $tanggal, $bulan, $tahun, "bosapprove");
				$statusRead = detilActByDay($CKoneksi, $userIdSelect, $tanggal, $bulan, $tahun, "bosread");
					
				if($statusRead == "Y")
				{
					$iconRead = '<img src=\'../../picture/book-open-list.png\' border=0 title="Already Read by Superior">';
				}
				if($statusApprove == "Y")
				{
					$iconApprove = '<img src=\'../../picture/tick.png\' border=0 title="Already Approve by Superior">';
				}
				
				$statusNewSpvCom = statusSpvComment($CKoneksi, $userIdSelect, $tanggal, $bulan, $tahun);
				if($statusNewSpvCom == "adaNew")
				{
					$iconSpvComment = '&nbsp;&nbsp;<img src=\'../../picture/daySuperiorComment_32.png\' height=15 border=0 title="New Superior Comment">';
				}
				
				$statusNewSubCom = statusSubComment($CKoneksi, $userIdSelect, $tanggal, $bulan, $tahun);
				if($statusNewSubCom == "adaNew")
				{
					$iconSubComment = '&nbsp;&nbsp;<img src=\'../../picture/daySubordinateComment_32.png\' height=15 border=0 title="New Comment Reponse Subordinate">';
				}
				
				$jmlUnread = jmlJobUnread($CKoneksi, $userIdSelect, $tanggal, $bulan, $tahun);
				$empJobList = empJobList($CKoneksi, $userIdSelect, $tanggal, $bulan, $tahun, $jmlUnread);
				if($_GET['empNo'] == $userEmpNo) // jika buka job daily sendiri dan berada dalam bulan yang dipilih
				{
					$onClick = ' onclick="parent.loadUrl(\'../index.php?aksi=openJobDailyList&empNo='.$_GET['empNo'].'&dateAct='.$tanggal.'/'.$bulan.'/'.$tahun.'\'); return false;"';
				}
				else // jika buka job daily subordinate dan berada dalam bulan yang dipilih
				{
					$onClick = ' onclick="alert(\'No Job List\');"';
					if($empJobList != "") // jika joblist tidak kosong maka bisa diklik
					{
						$onClick = ' onclick="parent.loadUrl(\'../index.php?aksi=openSubordinateJobList&empNo='.$_GET['empNo'].'&dateAct='.$tanggal.'/'.$bulan.'/'.$tahun.'\'); return false;"';
					}
				}
				
				$statusCuti = detilActByDay($CKoneksi,$userIdSelect, $tanggal, $bulan, $tahun, "cuti"); //ambil field DB "cuti"
				$statusSakit = detilActByDay($CKoneksi,$userIdSelect, $tanggal, $bulan, $tahun, "sakit"); //ambil field DB "sakit"
				if($statusCuti == "Y")
				{
					$iconRead = "&nbsp;&nbsp;&nbsp;&nbsp;";
					$iconApprove = "";
					$alignTeksAct = 'center';
					$empJobList = "<span style=\"color:#CC0000;font-weight:bold;font-size:14px;\">LEAVE</span>";
				}
				if($statusSakit == "Y")
				{
					$iconRead = "&nbsp;&nbsp;&nbsp;&nbsp;";
					$iconApprove = "";
					$alignTeksAct = 'center';
					$empJobList = "<span style=\"color:#CC0000;font-weight:bold;font-size:14px;\">SICK</span>";
				}
				
				$isiJobList = isiJobListCalender($CKoneksi, $userIdSelect, $tanggal, $bulan, $tahun);
				
				$warnaHari = " style=\"color:#666;\"";
				$hariLibur = hariLiburSqlServer($koneksiOdbcId, $koneksiOdbc, $tanggal, $bulan, $tahun);//SQLSERVER
				$ketHariLibur = ketHariLiburSqlServer($koneksiOdbcId, $koneksiOdbc, $tanggal, $bulan, $tahun);//SQLSERVER
				if($hariLibur == 1) //jika tanggal table hari libur di server tidak kosong
				{
					$warnaHari = " style=\"color:#CC0000;\"";
					$ketLibur = " title=\"".$ketHariLibur."\"";
				}
			}
			else // jika tanggal tidak berada dalam bulan yang dipilih maka diklik tidak terjadi apa2
			{
				$onClick = '';
			}
		}

		//$sCalTblRows .= '<td class="'.$sClass.'"><a href="javascript: void(0)">'.$iCurrentDay.'</a></td>';
		$sCalTblRows .= '<td class="'.$sClass.'"><a href="javascript: void(0)" '.$onClick.'>';
		$sCalTblRows .= '<table cellpadding="0" cellspacing="0">
						<tr>
							<td align=left height="20">'.$iconRead.'&nbsp;&nbsp;'.$iconApprove.$iconSpvComment.$iconSubComment.'</td>
							<td '.$warnaHari.' '.$ketLibur.' width="20">'.$iCurrentDay.'</td>
						</tr>
						<tr>
							<td colspan=2 align="'.$alignTeksAct.'" style="color:#000080;font-weight:normal;font-size:12px;"> 
							<span 
							onMouseOver="parent.showJobList(\'popUpJobList\', \''.$isiJobList.'\', \'1\')" onMouseOut="parent.showJobList(\'popUpJobList\', \'\', \'0\')">'.$empJobList.'</span>
							</td>
						</tr>
						</table>
						';
		$sCalTblRows .= '</a></td>';
		
 
		// Next day
		$iCurrentDay++;
		if ($bPreviousMonth && $iCurrentDay > $iPrevDaysInMonth) {
			$bPreviousMonth = false;
			$iCurrentDay = 1;
		}
		if (!$bPreviousMonth && !$bNextMonth && $iCurrentDay > $iDaysInMonth) 
		{
			$bNextMonth = true;
			$iCurrentDay = 1;
		}
	}
	$sCalTblRows .= '</tr>';
}
 
// Prepare replacement keys and generate the calendar
$aKeys = array(
	'__prev_month__' => "{$iPrevMonth}-{$iPrevYear}",
	'__next_month__' => "{$iNextMonth}-{$iNextYear}",
	'__cal_caption__' => $sMonthName . ', ' . $iYear,
	'__cal_rows__' => $sCalTblRows,
);
$sCalendarItself = strtr(file_get_contents('../templates/calendar.html'), $aKeys);
 
// AJAX requests - return the calendar
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_GET['month'])) {
	header('Content-Type: text/html; charset=utf-8');
	echo $sCalendarItself;
	exit;
}
 
$aVariables = array(
	'__calendar__' => $sCalendarItself,
);
echo strtr(file_get_contents('../templates/CalendarContainer.html'), $aVariables);

// -------------------------------------------------------------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------------------------------------------

function detilActByDay($CKoneksi, $userId, $tanggal, $bulan, $tahun, $field)
{
	$query = $CKoneksi->mysqlQuery("SELECT ".$field." FROM tblactivity WHERE ownerid='".$userId."' AND tanggal='".$tanggal."' AND bulan='".$bulan."' AND tahun='".$tahun."' AND deletests=0;");
	$row = $CKoneksi->mysqlFetch($query);
	
	return $row[$field];
}

function statusSpvComment($CKoneksi, $userId, $tanggal, $bulan, $tahun)
{
	$query = $CKoneksi->mysqlQuery("SELECT CASE WHEN spvcomment != '' AND spvcomment != oldspvcomment THEN 'adaNew' ELSE 'noNew' END AS statspvcomment FROM tblactivity WHERE ownerid='".$userId."' AND tanggal='".$tanggal."' AND bulan='".$bulan."' AND tahun='".$tahun."' AND deletests=0 LIMIT 1;");
	$row = $CKoneksi->mysqlFetch($query);
	
	return $row['statspvcomment'];
}

function statusSubComment($CKoneksi, $userId, $tanggal, $bulan, $tahun)
{
	$query = $CKoneksi->mysqlQuery("SELECT CASE WHEN responcomment != '' AND responcomment != oldresponcomment THEN 'adaNew' ELSE 'noNew' END AS statresponcomment FROM tblactivity WHERE ownerid='".$userId."' AND tanggal='".$tanggal."' AND bulan='".$bulan."' AND tahun='".$tahun."' AND deletests=0 LIMIT 1;");
	$row = $CKoneksi->mysqlFetch($query);
	
	return $row['statresponcomment'];
}

function empJobList($CKoneksi, $userId, $tanggal, $bulan, $tahun, $jmlUnread)
{
	$nilai = "";
	if($tanggal != "" && $bulan != "" && $tahun != "")
	{
		$query = $CKoneksi->mysqlquery("SELECT COUNT(idactivity) AS jumlah FROM tblactivity WHERE ownerid='".$userId."' AND tanggal='".$tanggal."' AND bulan='".$bulan."' AND tahun='".$tahun."' AND deletests=0;");
		$row = $CKoneksi->mysqlFetch($query);
		if($row['jumlah'] != 0)
		{
			if($jmlUnread != 0) //jika jumlah job yang belom dibaca masih ada
			{
				$unRead = "(<b>".$jmlUnread." Unread</b>)";
			}
			
			$nilai = "<b>".$row['jumlah']."</b> Job ".$unRead;
			if($row['jumlah'] > 1)
			{
				$nilai = "<b>".$row['jumlah']."</b> Jobs ".$unRead;
			}
		}
	}
	return $nilai;
}

function jmlJobUnread($CKoneksi, $userId, $tanggal, $bulan, $tahun)
{
	$query = $CKoneksi->mysqlquery("SELECT COUNT(idactivity) AS jumlah FROM tblactivity WHERE ownerid='".$userId."' AND bosreadjob='N' AND tanggal='".$tanggal."' AND bulan='".$bulan."' AND tahun='".$tahun."' AND deletests=0;");
	$row = $CKoneksi->mysqlFetch($query);	
	return $row['jumlah'];
}

function nmHariServer($CKoneksi, $tanggal, $bulan, $tahun)
{
	$query = $CKoneksi->mysqlQuery("SELECT DAYNAME('".$tahun."-".$bulan."-".$tanggal."') AS namahari;");
	
	$row = $CKoneksi->mysqlFetch($query);
	return $row['namahari'];
}

function ketHariLibur($CKoneksi, $tanggal, $bulan, $tahun)
{
	$query = $CKoneksi->mysqlQuery("SELECT tahun, bulan, tanggal, keterangan FROM tblmsthrlibur WHERE (tahun = ".$tahun.") AND (bulan = ".$bulan.") AND (tanggal = ".$tanggal.") AND (deletests = 0)");
	$row = $CKoneksi->mysqlFetch($query);

	return $row['keterangan'];
}

function hariLiburSqlServer($koneksiOdbcId, $koneksiOdbc, $tanggal, $bulan, $tahun)
{
	$query = $koneksiOdbc->odbcExec($koneksiOdbcId, "
	SELECT TOP (1) tahun, bulan, tanggal, deletests, uniquekey 
	FROM dbo.tblMstHrLibur 
	WHERE (tahun = ".$tahun.") AND (bulan = ".$bulan.") AND (tanggal = ".$tanggal.") AND (deletests = 0)");
	$jmlRow = $koneksiOdbc->odbcNRows($query);

	return $jmlRow;
}

function ketHariLiburSqlServer($koneksiOdbcId, $koneksiOdbc, $tanggal, $bulan, $tahun)
{
	$query = $koneksiOdbc->odbcExec($koneksiOdbcId, "
	SELECT TOP (1) tahun, bulan, tanggal, keterangan, deletests, uniquekey 
	FROM dbo.tblMstHrLibur 
	WHERE (tahun = ".$tahun.") AND (bulan = ".$bulan.") AND (tanggal = ".$tanggal.") AND (deletests = 0)");
	$row = $koneksiOdbc->odbcFetch($query);

	return $row['keterangan'];
}

function isiJobListCalender($CKoneksi, $userId, $tanggal, $bulan, $tahun)
{
	$html = "";
	$urutan = 0;
	
	$query = $CKoneksi->mysqlQuery("SELECT activity FROM tblactivity WHERE tanggal='".$tanggal."' AND bulan='".$bulan."' AND tahun='".$tahun."' AND ownerid=".$userId." AND deletests=0 ORDER BY urutan ASC");
	while($row = $CKoneksi->mysqlFetch($query))
	{
		$urutan++;
		$rowColor = rowColor($urutan);
		
		$html.= "<tr bgcolor=".$rowColor.">";
		$html.= "<td height=20>".$urutan.".&nbsp;".potongKarakter( konversiQuotes($row['activity']), 75)."</td>";
		$html.= "</tr>";
	}
	
	return $html;
}

function rowColor($i)
{
	if ($i % 2 != "0") # An odd row 
	{
		$rowColor = "#F2FBFF"; 
	}
	else # An even row 
	{
		$rowColor = "#DDF0FF";
	}
	return $rowColor;
}

function zerofill($num, $zerofill = 5) 
{ 
	return str_pad($num, $zerofill, '0', STR_PAD_LEFT); 
}

function konversiQuotes($string) 
{ 
	$search = array('"', "'"); 
	$replace = array("\&#34;", "\&#39;"); 

	return str_replace($search, $replace, $string); 
}

function potongKarakter($teks, $limit)
{
	$pjgKar = strlen($teks);
	
	if($pjgKar <= $limit)
	{
		$isi = $teks;
	}
	elseif($pjgKar > $limit)
	{
		$isi = substr($teks,0,$limit)."...";
	}
	
	return $isi;
}
?>