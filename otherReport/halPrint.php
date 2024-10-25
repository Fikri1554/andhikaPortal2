<?php
require_once("../config.php");

$halPost = $_POST['hal'];

$fromDate = $_POST['fromDate'];
$tgl =  substr($fromDate,0,2);
$bln =  substr($fromDate,4,2);
$thn =  substr($fromDate,6,4);
$dateForm = $thn."/".$bln."/".$tgl;
$fromDateD = $CPublic->bulanSetengah($bln, "eng")." ".$tgl.", ".$thn;

$toDate = $_POST['toDate'];
$tglTo =  substr($toDate,0,2);
$blnTo =  substr($toDate,4,2);
$thnTo =  substr($toDate,6,4);
$dateTo = $thnTo."/".$blnTo."/".$tglTo;
$toDateD = $CPublic->bulanSetengah($blnTo, "eng")." ".$tglTo.", ".$thnTo;

// ======== START == Print ALL REPORT ===========================================================
if($halPost == "all")
{
	$jmlUser = $COtherReport->jmlUser();
	$halfUser = round($jmlUser/2) ;

	$tpl = new myTemplate("templates/report.html");
	
	$tpl->prepare();
	
	$html = "";
	$isiReport = "";

//-- START 1 halaman ==========================================================================================================	
	
	$html.= "<tr>
				<td>
					<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
					<tr>
						<td width=\"30px\"></td>
						<td><span class=\"style1\">PHE ONWJ WORKING HOUR</span></td>
						<td align=\"right\"><span class=\"style2\">
						&nbsp;Period : ".$fromDateD." until ".$toDateD."
						</span></td>
						<td width=\"30px\"></td>
					</tr>
					</table>
				</td>
			</tr>
			<tr><td height=\"25\">&nbsp;</td></tr>";
		
	$html.= "<tr>
				<td>
				<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
				<tr>
					<td>
					<table align=\"center\"><tr><td>";
	$html.= "<table width=\"350\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
				<tr align=\"center\" height=\"25px;\" bgcolor=\"#E9E9E9\" class=\"style3\">
					<td width=\"7%\" class=\"tabelBorderRightNull\">No. </td>
					<td width=\"70%\" class=\"tabelBorderRightNull\">&nbsp;Name</td>
					<td width=\"20%\" class=\"tabelBorderAll\">Term (hour)</td>
				</tr>";
	$jamKerja1 = 0;	
	$query1 = $CKoneksi->mysqlQuery("SELECT userfullnm,userid FROM login WHERE deletests = 0 AND active = 'Y' ORDER BY userfullnm ASC LIMIT 0,".$halfUser."");
	while($row1 = $CKoneksi->mysqlFetch($query1))
	{
		$jam1= $COtherReport->pheonwj($row1['userid'], $dateForm, $dateTo);
		$jamKerja1 = $jamKerja1 + $jam1;
		
		$i++;
		if($i%2 == 1)
		{
			$bgColor = "";
		}
		else
		{
			$bgColor = "bgcolor=\"#F2F2F2\"";
		}
		
	$html.= "<tr align=\"center\" ".$bgColor." height=\"20px\" class=\"style5\">
				<td width=\"7%\" class=\"tabelBorderTopRightNull\">".$i."</td>
				<td width=\"70%\" class=\"tabelBorderTopRightNull\" align=\"left\">&nbsp;".$row1['userfullnm']."</td>
				<td width=\"20%\" valign=\"middle\"align=\"center\" class=\"tabelBorderTopNull\">&nbsp;".$COtherReport->pheonwj($row1['userid'], $dateForm, $dateTo)."</td>
			</tr>";
	}
	
	$html.= "</table>
	</td>
	<td valign=\"top\">
	<table width=\"350\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
		<tr align=\"center\" height=\"25px;\" bgcolor=\"#E9E9E9\" class=\"style3\">
			<td width=\"7%\" class=\"tabelBorderRightNull\">No. </td>
			<td width=\"70%\" class=\"tabelBorderRightNull\">&nbsp;Name</td>
			<td width=\"20%\" class=\"tabelBorderAll\">Term (hour)</td>
		</tr>";
	
	$jamKerja2 = 0;	
	$query2 = $CKoneksi->mysqlQuery("SELECT userfullnm,userid FROM login WHERE deletests = 0 AND active = 'Y' ORDER BY userfullnm ASC LIMIT ".$halfUser.",".$halfUser."");
	while($row2 = $CKoneksi->mysqlFetch($query2))
	{
		$jam2= $COtherReport->pheonwj($row2['userid'], $dateForm, $dateTo);
		$jamKerja2 = $jamKerja2 + $jam2;
		
		$i++;
		if($i%2 == 1)
		{
			$bgColor = "";
		}
		else
		{
			$bgColor = "bgcolor=\"#F2F2F2\"";
		}	
	$html.= "<tr align=\"center\" ".$bgColor." height=\"20px\" class=\"style5\">
				<td width=\"7%\" class=\"tabelBorderTopRightNull\">".$i."</td>
				<td width=\"70%\" class=\"tabelBorderTopRightNull\" align=\"left\">&nbsp;".$row2['userfullnm']."</td>
				<td width=\"20%\" valign=\"middle\"align=\"center\" class=\"tabelBorderTopNull\">&nbsp;".$COtherReport->pheonwj($row2['userid'], $dateForm, $dateTo)."</td>
			</tr>";
	}
		
	$total = $jamKerja1 + $jamKerja2;	
	$html.= "</table>";
	
	$html.= "	</td>
			</tr>
				</table>
				</td>
				</tr>
				</table>
				</td>
			</tr>";
	$html.= "<tr>
				<td align=\"center\">
				<table width=\"710\">
					<tr style=\"font-family:Arial;font-size:12px;\" bgcolor=\"#E9E9E9\" height=\"20px;\" width=\"700\">
					<td style=\"color:#000080;font-weight:bold;font-family:Tahoma;\">
						&nbsp;All Total : ".$total." hours
					</td>
					</tr>
				</table>
					
				</td>
			</tr>";
	$html.= "<tr>
				<td height=\"30\">&nbsp;</td>
			</tr>";
	$html.= "<tr><td style=\"position:absolute;bottom:-0%;width:99%;right:5%;font:0.6em sans-serif;\" class=\"tabelBorderAllNull\">Page 1 of 1</td></tr>";
			
	
//-- END OF 1 halaman ===========================================================================================================	
	$isiReport = $html;
	
	$tpl->Assign("isiReport", $isiReport);
	$tpl->printToScreen();
}
//-- END OF Print ALL REPORT  ===================================================================================================

// ======== START == Print DIVISON REPORT ===========================================================
if($halPost == "divisi")
{
	$tpl = new myTemplate("templates/report.html");
	
	$tpl->prepare();
	
	$html.= "<tr>
				<td>
					<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
					<tr>
						<td width=\"60px\"></td>
						<td><span class=\"style1\">PHE ONWJ WORKING HOUR</span></td>
						<td align=\"right\"><span class=\"style2\">
						&nbsp;Period : ".$fromDateD." until ".$toDateD."
						</span></td>
						<td width=\"60px\"></td>
					</tr>
					</table>
				</td>
			</tr>
			<tr><td height=\"10\"></td></tr>";
			
	$html.= "<tr>
				<td>
				<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
				<tr>
					<td>
					<table align=\"center\"><tr><td>";
					
	$html.= "<tr>
				<td>
				<div style=\"float:left;\">";
	
	$jamKerja1 = 0;			
	$query = $CKoneksi->mysqlQuery("SELECT * FROM tblmstdiv ORDER BY nmdiv ASC LIMIT 0,8;");
	while($row = $CKoneksi->mysqlFetch($query))
	{
		$jmlMbrDiv = $COtherReport->jmlMbrDiv($row['kddiv']);
		if($jmlMbrDiv != 0)
		{
				
	$html.= "	<table width=\"350\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
				<tr style=\"font-family:Arial;font-size:12px;font-weight:bold;\">
					<td colspan=\"3\"> ".$row['nmdiv']."
				</tr>
				<tr align=\"center\" height=\"23px;\" bgcolor=\"#E9E9E9\" class=\"style3\">
					<td width=\"7%\" class=\"tabelBorderRightNull\">No. </td>
					<td width=\"70%\" class=\"tabelBorderRightNull\">&nbsp;Name</td>
					<td width=\"20%\" class=\"tabelBorderAll\">Term (hour)</td>
				</tr>";
					
	$i = 1;
	$totalJam1 = 0;
	$queryUser = $CKoneksi->mysqlQuery("SELECT userfullnm,userid FROM login WHERE kddiv=".$row['kddiv']." AND active = 'Y' AND deletests = 0 ORDER BY userfullnm ASC");
	while($rowUser = $CKoneksi->mysqlFetch($queryUser))
	{
		//$COtherReport->pheonwj($rowUser['userid'], $fromDate, $toDate);
		$jam1= $COtherReport->pheonwj($rowUser['userid'], $dateForm, $dateTo);
		$jamKerja1 = $jamKerja1 + $jam1;
		$totalJam1 = $totalJam1 + $jam1;
		$clr = "#F2F2F2";
		if($i%2 == 0)
		{
			$clr = "";
		}	
						
	$html.= "<tr align=\"center\" ".$bgColor." height=\"18px\" class=\"style5\">
						<td width=\"7%\" class=\"tabelBorderTopRightNull\">".$i."</td>
						<td width=\"70%\" class=\"tabelBorderTopRightNull\" align=\"left\">&nbsp;".$rowUser['userfullnm']."</td>
						<td width=\"20%\" valign=\"middle\"align=\"center\" class=\"tabelBorderTopNull\">&nbsp;".$COtherReport->pheonwj($rowUser['userid'], $dateForm, $dateTo)."</td>
					</tr>";
					
					$i++;}
	if($jmlMbrDiv > 1)
	{
	$html.= "<tr align=\"center\" height=\"20px\" class=\"style5\">
						<td colspan=\"2\" width=\"77%\" class=\"tabelBorderRightNull\" align=\"center\"><b>Total</b></td>
						<td width=\"20%\" valign=\"middle\"align=\"center\" class=\"tabelBorderAll\" bgcolor=\"#E9E9E9\">&nbsp;<b>".$totalJam1."</b></td>
					</tr>";
	}
	$html.= "<tr><td colspan=\"3\" height=\"5px\"></td></tr>
				</table>";
				
		}
	}
							
	$html.= "	</div>
				<div style=\"float:left;margin-left:10px;\">";
	
	$jamKerja2 = 0;
	$query = $CKoneksi->mysqlQuery("SELECT * FROM tblmstdiv ORDER BY nmdiv ASC LIMIT 8,9;");
	while($row = $CKoneksi->mysqlFetch($query))
	{
		$jmlMbrDiv = $COtherReport->jmlMbrDiv($row['kddiv']);
		if($jmlMbrDiv != 0)
		{
				
	$html.= "	<table width=\"350\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
				<tr style=\"font-family:Arial;font-size:12px;font-weight:bold;\">
					<td colspan=\"3\"> ".$row['nmdiv']."
				</tr>
				<tr align=\"center\" height=\"25px;\" bgcolor=\"#E9E9E9\" class=\"style3\">
					<td width=\"7%\" class=\"tabelBorderRightNull\">No. </td>
					<td width=\"70%\" class=\"tabelBorderRightNull\">&nbsp;Name</td>
					<td width=\"20%\" class=\"tabelBorderAll\">Term (hour)</td>
				</tr>";
					
	$i = 1;
	$totalJam2 = 0;
	$queryUser = $CKoneksi->mysqlQuery("SELECT userfullnm,userid FROM login WHERE kddiv=".$row['kddiv']." AND active = 'Y' AND deletests = 0 ORDER BY userfullnm ASC");
	while($rowUser = $CKoneksi->mysqlFetch($queryUser))
	{
		//$COtherReport->pheonwj($rowUser['userid'], $fromDate, $toDate);
		$jam2= $COtherReport->pheonwj($rowUser['userid'], $dateForm, $dateTo);
		$jamKerja2 = $jamKerja2 + $jam2;
		$totalJam2 = $totalJam2 + $jam2;
		$clr = "#F2F2F2";
		if($i%2 == 0)
		{
			$clr = "";
		}	
						
	$html.= "<tr align=\"center\" ".$bgColor." height=\"20px\" class=\"style5\">
						<td width=\"7%\" class=\"tabelBorderTopRightNull\">".$i."</td>
						<td width=\"70%\" class=\"tabelBorderTopRightNull\" align=\"left\">&nbsp;".$rowUser['userfullnm']."</td>
						<td width=\"20%\" valign=\"middle\"align=\"center\" class=\"tabelBorderTopNull\">&nbsp;".$COtherReport->pheonwj($rowUser['userid'], $dateForm, $dateTo)."</td>
					</tr>";
					
					$i++;}
	if($jmlMbrDiv > 1)
	{
	$html.= "<tr align=\"center\" height=\"20px\" class=\"style5\">
						<td colspan=\"2\" width=\"77%\" class=\"tabelBorderRightNull\" align=\"center\"><b>Total</b></td>
						<td width=\"20%\" valign=\"middle\"align=\"center\" class=\"tabelBorderAll\" bgcolor=\"#E9E9E9\">&nbsp;<b>".$totalJam2."</b></td>
					</tr>";
	}
	$html.= "<tr><td colspan=\"3\" height=\"5px\"></td></tr>
				</table>";
				
		}
	}
	$total = $jamKerja1 + $jamKerja2;
								
	$html.= "	</div>
				</td>
			</tr>";
			
	$html.= "<tr style=\"font-family:Arial;font-size:12px;\" bgcolor=\"#E9E9E9\"height=\"20px;\">
				<td style=\"color:#000080;font-weight:bold;font-family:Tahoma;\">
					&nbsp;All Total : ".$total." hours
				</td>
			</tr>";				
					
	$html.= "		</td></tr></table>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height=\"30\">&nbsp;</td>
			</tr>
			<tr><td style=\"position:absolute;bottom:-0%;width:99%;right:7%;font:0.6em sans-serif;\" class=\"tabelBorderAllNull\">Page 1 of 1</td></tr>";
	
	$isiReport = $html;
	
	$tpl->Assign("isiReport", $isiReport);
	$tpl->printToScreen();
}
// ======== END OF == Print DIVISION REPORT ===========================================================

// ======== START OF == Print detail REPORT ===========================================================
if($halPost == "detail")
{
	$jmlPrint = $_POST['jmlPrint'];
	
	$tpl = new myTemplate("templates/report.html");
	
	$tpl->prepare();
			
	$jml = $CKoneksi->mysqlNRows($CKoneksi->mysqlQuery("SELECT * FROM tblactivity WHERE deletests=0 AND project = 'pheonwj' AND
DATE(CONCAT(tahun,'/',bulan,'/',tanggal)) BETWEEN '".$dateForm."' AND '".$dateTo."'
AND (bosapprove = 'Y' OR lockedit = 'Y') ORDER BY ownername ASC;"));
	$limit = $jmlPrint; // LIMIT DARI JUMLAH KELUHAN PER SATU HALAMAN
	$maxPage = ceil($jml/$limit);
	
	for($a=1; $a<=$maxPage; $a++)
	{	
		if($a == 1) // JIKA HALAMAN 1 MAKA NOMOR MULAI DARI 1 (NOMOR HALAMAN)
		{	
			$persenheight = 0;
			
			$pagebreak = "";
			$offset = 0;
			$no = $offset + 1;
			
			$total= 0;
		}
		else // JIKA SELAIN HALAMAN SATU MAKA BERTAMBAH SESUAI JUMLAH HALAMAN DAN URUTAN
		{
			$persenheight = ($a-1) * 100;
			
			$pagebreak = "<tr style=\"page-break-after: left\"></tr>"; // UNTUK PINDAH HALAMAN BERIKUTNYA
			$offset = ($a-1) * $limit;
			$no = $offset+1;
			
			$total= $total;
		}
		
		$html.= $pagebreak;
		$html.= "<tr>
				<td>
					<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
					<tr>
						<td width=\"60px\"></td>
						<td><span class=\"style1\">PHE ONWJ WORKING HOUR</span></td>
						<td align=\"right\"><span class=\"style2\">
						&nbsp;Period : ".$fromDateD." until ".$toDateD."
						</span></td>
						<td width=\"60px\"></td>
					</tr>
					</table>
				</td>
			</tr>
			<tr><td height=\"15\"></td></tr>";
	
		// HEAD tabel
		$html.= "<tr><td align=\"center\">
				<table cellpadding=\"1\" cellspacing=\"0\" border=\"0\" width=\"85%\">
				<tr align=\"center\" height=\"23px;\" bgcolor=\"#E9E9E9\" class=\"style3\">
					<td align=\"center\" width=\"5%\" class=\"tabelBorderBottomNull\">
						No.
					</td>
					<td colspan=\"3\" align=\"center\" width=\"81%\" class=\"tabelBorderBottomLeftNull\">
					<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\" class=\"style3\">
						<tr><td class=\"tabelBorderBottomJust\" align=\"center\">Name</td></tr>
						<tr><td align=\"center\">Date - Information</td></tr>
					</table>
					</td>
					<td align=\"center\" width=\"14%\" class=\"tabelBorderBottomLeftNull\">
						Term(hour)
					</td>
				</tr>";	
					
		$j=1;
		$query = $CKoneksi->mysqlQuery("SELECT * FROM tblactivity WHERE deletests=0 AND project = 'pheonwj' AND
				DATE(CONCAT(tahun,'/',bulan,'/',tanggal)) BETWEEN '".$dateForm."' AND '".$dateTo."'
				AND (bosapprove = 'Y' OR lockedit = 'Y') ORDER BY ownername ASC LIMIT ".$offset.", ".$limit.";");
		$jml = $CKoneksi->mysqlNRows($query);
		while($row = $CKoneksi->mysqlFetch($query))
		{
			$date = $row['tanggal']."/".$row['bulan']."/".substr($row['tahun'],2,2);
			$total = $total + $COtherReport->selisihJam($row['fromtime'], $row['totime']);
			
			$border = "class=\"tabelBorderTopNull\"";
			$border1 = "class=\"tabelBorderTopLeftNull\"";
			if($j==1)
			{
				$border = "class=\"tabelBorderAll\"";
				$border1 = "class=\"tabelBorderLeftNull\"";
			}
			if($i%2 == 1)
			{
				$bgColor = "";
			}
			else
			{
				$bgColor = "bgcolor=\"#F2F2F2\"";
			}
		
			$sisa = ($limit - $j)+1; //sisa baris yg akan di print
			if($namaSeb != 	$row['ownername'])
			{
				$i++;
				$jmlAct = $COtherReport->sudahLockOrAprv($row['ownerid'], $dateForm, $dateTo);
				$jmlBaris = $jmlAct+1;
				
				if($jmlAct <= $sisa)
				{
					$rowspan = "rowspan=".$jmlBaris."";
				}
				if($jmlAct > $sisa) // baris aktifitas yg akan di cetak pada OWNER dan halaman yg sama, jika tidak cukup, lanjut di hal selanjutnya
				{
					$jmlBaris = $sisa+1;
					$rowspan = "rowspan=".$jmlBaris."";
					$sisaRow = $jmlAct - $sisa ;
				}
				//OWNER aktifitas
				$html.=	"<tr height=\"20px\" class=\"style5\" ".$bgColor.">
							<td align=\"center\" ".$border.$rowspan.">".$i."</td>
							<td colspan=\"3\" ".$border1.">&nbsp;&nbsp;".$row['ownername']."</td>
							<td align=\"center\" ".$border1.">
								<b>".$COtherReport->pheonwj($row['ownerid'], $dateForm, $dateTo)."</b>
							</td>
						</tr>";
			}
						
		$html.=	"<tr height=\"17px\" class=\"style5\">";
			$border2 = "class=\"tabelBorderBottomJust\"";
			$border3 = "class=\"tabelBorderTopLeftNull\"";
			if($namaSeb == $row['ownername'] && $j==1 && $a != 1) // jika ada aktifitas yg tersisa dari halaman sebelumnya
			{
				$border2 = "class=\"tabelBorderLeftRightNull\"";
				$border3 = "class=\"tabelBorderLeftNull\"";
				
				$sisaRowTampil = "";
				if($sisaRow != "" || $sisaRow != 0)
				{
					$sisaRowTampil = "rowspan=\"".$sisaRow."\"";
				}
					$html.= "<td ".$sisaRowTampil." class=\"tabelBorderAll\">&nbsp;</td>";
			}
		// detail aktifitas
		$html.= "	<td ".$border2." align=\"center\" width=\"7%\">".$date."</td>
					<td class=\"tabelBorderTopRightNull\" width=\"1%\">&nbsp;</td>
					<td ".$border3.">".$row['relatedinfo']."</td>
					<td align=\"center\" ".$border3.">".$COtherReport->selisihJam($row['fromtime'], $row['totime'])."</td>
				</tr>";
				$namaSeb = $row['ownername'];
		$j++;}
		
		if($a != 1)
		{
			// total seluruh jam kerja yg dicetak
			$html.=	"<tr><td height=\"15\" colspan=\"4\"></td></tr>
					<tr style=\"font-family:Arial;font-size:12px;\" bgcolor=\"#E9E9E9\"height=\"20px;\" width=\"85%\">
						<td colspan=\"4\" style=\"color:#000080;font-weight:bold;font-family:Tahoma;\">
							&nbsp;All Total : ".$total." hours
						</td>
					</tr>";
		}
		$html.= "</table>
				</td></tr>";
		
		//page info	
		$html.= "<tr><td style=\"position:absolute;bottom:-".$persenheight."%;width:99%;right:5%;font:0.6em sans-serif;\" class=\"tabelBorderAllNull\">Halaman ".$a." dari ".$maxPage."</td></tr>";
		
	}
	$isiReport = $html;
	
	$tpl->Assign("isiReport", $isiReport);
	$tpl->printToScreen();
}
// ======== END OF == Print detail REPORT ===========================================================
?>