<?php
require_once("../config.php");
// ======== START == Print Stop Card ===========================================================
if($aksiPost == "printCard"){

		$tpl = new myTemplate("templates/laporanStopCard.html");
		$idKeluhanGet = $_POST['idCard'];
	
	$tpl->prepare();
	
	$ownerName = $CQhse->printCard($idKeluhanGet, "ownername");
	$aman = $CQhse->printCard($idKeluhanGet, "aman");
	if($aman == ""){$aman = "-";}
	$tidakAman = $CQhse->printCard($idKeluhanGet, "tidakaman");
	if($tidakAman == ""){$tidakAman = "-";}
	$lokasi = $CQhse->printCard($idKeluhanGet, "lokasi");
	$dateDB = $CQhse->printCard($idKeluhanGet, "date");
	$hari = substr($dateDB,8,2);
	$bulan = substr($dateDB,5,2);
	$tahun = substr($dateDB,0,4);
	$createdDate = ucfirst(strtolower($CPublic->bulanSetengah($bulan, "eng")))." ".$hari.", ".$tahun;
	
	$A0cek = statusCek($CKoneksi, $idKeluhanGet, "A0");
	$A1cek = statusCek($CKoneksi, $idKeluhanGet, "A1");
	$A2cek = statusCek($CKoneksi, $idKeluhanGet, "A2");
	$A3cek = statusCek($CKoneksi, $idKeluhanGet, "A3");
	$A4cek = statusCek($CKoneksi, $idKeluhanGet, "A4");
	$A5cek = statusCek($CKoneksi, $idKeluhanGet, "A5");
	$A6cek = statusCek($CKoneksi, $idKeluhanGet, "A6");
	$B0cek = statusCek($CKoneksi, $idKeluhanGet, "B0");
	$B1cek = statusCek($CKoneksi, $idKeluhanGet, "B1");
	$B2cek = statusCek($CKoneksi, $idKeluhanGet, "B2");
	$B3cek = statusCek($CKoneksi, $idKeluhanGet, "B3");
	$B4cek = statusCek($CKoneksi, $idKeluhanGet, "B4");
	$B5cek = statusCek($CKoneksi, $idKeluhanGet, "B5");
	$B6cek = statusCek($CKoneksi, $idKeluhanGet, "B6");
	$C0cek = statusCek($CKoneksi, $idKeluhanGet, "C0");
	$C1cek = statusCek($CKoneksi, $idKeluhanGet, "C1");
	$C2cek = statusCek($CKoneksi, $idKeluhanGet, "C2");
	$C3cek = statusCek($CKoneksi, $idKeluhanGet, "C3");
	$C4cek = statusCek($CKoneksi, $idKeluhanGet, "C4");
	$C5cek = statusCek($CKoneksi, $idKeluhanGet, "C5");
	$C6cek = statusCek($CKoneksi, $idKeluhanGet, "C6");
	$C7cek = statusCek($CKoneksi, $idKeluhanGet, "C7");
	$C8cek = statusCek($CKoneksi, $idKeluhanGet, "C8");
	$C9cek = statusCek($CKoneksi, $idKeluhanGet, "C9");
	$C10cek = statusCek($CKoneksi, $idKeluhanGet, "C10");
	$C11cek = statusCek($CKoneksi, $idKeluhanGet, "C11");
	$D0cek = statusCek($CKoneksi, $idKeluhanGet, "D0");
	$D1cek = statusCek($CKoneksi, $idKeluhanGet, "D1");
	$D2cek = statusCek($CKoneksi, $idKeluhanGet, "D2");
	$D3cek = statusCek($CKoneksi, $idKeluhanGet, "D3");
	$E0cek = statusCek($CKoneksi, $idKeluhanGet, "E0");
	$E1cek = statusCek($CKoneksi, $idKeluhanGet, "E1");
	$E2cek = statusCek($CKoneksi, $idKeluhanGet, "E2");
	$E3cek = statusCek($CKoneksi, $idKeluhanGet, "E3");
	$E4cek = statusCek($CKoneksi, $idKeluhanGet, "E4");
	$E5cek = statusCek($CKoneksi, $idKeluhanGet, "E5");
	$E6cek = statusCek($CKoneksi, $idKeluhanGet, "E6");
	
	$tpl->Assign("userName", $ownerName);
	$tpl->Assign("createdDate", $createdDate);
	
	$isiCardList.="<tr height=\"19px;\" class=\"style5\">
					<td colspan=\"2\">
						<br/>&nbsp;<b>REAKSI SESEORANG / <em>REACTION OF PEOPLE</em></b> </td><td width=\"4%\" align=\"right\">".$A0cek."
					</td>
				</tr>
				<tr height=\"19px;\" bgcolor=\"#EFEFEF\" class=\"style5\">
					<td colspan=\"3\">
						&nbsp;".$A1cek." Penyesuaian Penggunaan APD / <em>Adjusting PPE</em></td>
				</tr>
				<tr height=\"19px;\" class=\"style5\">
					<td colspan=\"3\">
						&nbsp;".$A2cek." Merubah Posisi / <em>Changing Position</em></td>
				</tr>
				<tr height=\"19px;\" class=\"style5\">
                    <td bgcolor=\"#EFEFEF\" colspan=\"3\">
                        &nbsp;".$A3cek." Mengatur Pekerjaan /<em> Rearranging Job</em></td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td colspan=\"3\">
                        &nbsp;".$A4cek." Menghentikan Pekerjaan / <em>Stopping Job</em></td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td bgcolor=\"#EFEFEF\" colspan=\"3\">
                        &nbsp;".$A5cek." Memasang Arde / <em>Attaching Ground</em></td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td style=\"border-bottom: dashed 1px #CCC;\" colspan=\"3\">
                        &nbsp;".$A6cek." Melakukan Penguncian / <em>Performing Lockouts</em></td>
                </tr>
				<!-- END OF 1 ================================================================================================================ -->
				<tr height=\"19px;\" class=\"style5\">
                    <td colspan=\"2\">
                        &nbsp;<b>ALAT PELINDUNG PRIBADI / <em>PERSONAL PROTECTIVE EQUIPMENT</em></b> </td><td width=\"4%\" align=\"right\">".$B0cek."
                    </td>
                </tr>
				<tr height=\"19px;\" class=\"style5\">
                    <td bgcolor=\"#EFEFEF\" colspan=\"3\">
                        &nbsp;".$B1cek." Kepala / <em>Head</em></td>
                </tr>
				<tr height=\"19px;\" class=\"style5\">
                    <td colspan=\"3\">
                        &nbsp;".$B2cek." Mata dan Muka / <em>Eyes and Face </em></td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td bgcolor=\"#EFEFEF\" colspan=\"3\">
                        &nbsp;".$B3cek." Telinga / <em>Ear</em></td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td colspan=\"3\">
                        &nbsp;".$B4cek." Sistem Pernapasan / <em>Respiratory System</em></td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td bgcolor=\"#EFEFEF\" colspan=\"3\">
                        &nbsp;".$B5cek." Lengan dan Tangan /<em> Arms and Hands</em></td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td style=\"border-bottom: dashed 1px #CCC;\" colspan=\"3\">
                        &nbsp;".$B6cek." Kaki dan Telapak Kaki / <em>Leg - Foot</em></td>
                </tr>
				<!-- END OF 2 ================================================================================================================ -->
				 <tr height=\"19px;\" class=\"style5\">
                    <td colspan=\"2\">
                        <b>POSISI SESEORANG (Penyebab Cidera) / <em>POSITIONS OF PEOPLE</em> (<em>Injury Causes</em>)</b> </td><td align=\"right\">".$C0cek."
                    </td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td bgcolor=\"#EFEFEF\" colspan=\"3\">
                        &nbsp;".$C1cek." Menabrak Barang / <em>Striking Againts Objects</em></td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td  colspan=\"3\">
                        &nbsp;".$C2cek." Tertimpa Barang / <em>Struck by Objects</em></td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td bgcolor=\"#EFEFEF\" colspan=\"3\">
                        &nbsp;".$C3cek." Terhimpit Barang / <em>Caught In, On, or Between Objects</em></td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td  colspan=\"3\">
                        &nbsp;".$C4cek." Jatuh / <em>Falling</em></td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td bgcolor=\"#EFEFEF\" colspan=\"3\">
                        &nbsp;".$C5cek." Berada di Tempat Sangat Panas / <em>Contracting Temperature Extremes</em></td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td  colspan=\"3\">
                        &nbsp;".$C6cek." Menghisap / <em>Inhaling</em> (Zat Berbahaya / <em>Absorbing a Hazardous Substance</em>)</td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td bgcolor=\"#EFEFEF\" colspan=\"3\">
                        &nbsp;".$C7cek." Absorbsi (Zat Berbahaya / <em>Absorbing a Hazardous Substance</em>)</td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td  colspan=\"3\">
                        &nbsp;".$C8cek." Menelan / <em>Swallowing</em> (Zat Berbahaya / <em>Absorbing a Hazardous Substance</em>)</td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td bgcolor=\"#EFEFEF\" colspan=\"3\">
                        &nbsp;".$C9cek." Gerakan Membahayakan / <em>Over Exertion</em></td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td  colspan=\"3\">
                        &nbsp;".$C10cek." Gerakan Berlebihan / <em>Repetitive Motion</em></td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td style=\"border-bottom: dashed 1px #CCC;\" bgcolor=\"#EFEFEF\" colspan=\"3\">
                        &nbsp;".$C11cek." Posisi Janggal / <em>Awkward Position </em>/ <em>Static Postures</em></td>
                </tr>
<!-- END OF 3 ================================================================================================================ -->
                <tr height=\"19px;\" class=\"style5\">
                    <td colspan=\"2\">
                        <b>PERKAKAS DAN PERALATAN / <em>TOOLS AND EQUIPMENT</em></b> </td><td width=\"4%\" align=\"right\">".$D0cek."
                    </td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td bgcolor=\"#EFEFEF\" colspan=\"3\">
                        &nbsp;".$D1cek." Tidak sesuai dengan pekerjaan / <em>Wrong the Job</em></td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td  colspan=\"3\">
                        &nbsp;".$D2cek."  Salah Penggunaan / <em>Used Incorrectly</em></td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td style=\"border-bottom: dashed 1px #CCC;\" bgcolor=\"#EFEFEF\" colspan=\"3\">
                        &nbsp;".$D3cek."  Berada di Tempat yang Tidak Aman / <em>In Unsafe Condition</em></td>
                </tr>
<!-- END OF 4 ================================================================================================================ -->
                <tr height=\"19px;\" class=\"style5\">
                    <td colspan=\"2\">
                        <b>PROSEDUR DAN PETUNJUK / <em>PROCEDURES AND ORDERLINESS</em></b> </td><td align=\"right\">".$E0cek."
                    </td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td bgcolor=\"#EFEFEF\" colspan=\"3\">
                        &nbsp;".$E1cek." Prosedur Tidak Memadai / <em>Procedures Inaquate</em></td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td  colspan=\"3\">
                        &nbsp;".$E2cek." Prosedur Tidak Diketahui / Tidak Mengerti / <em>Procedures Not Known / Understood</em></td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td bgcolor=\"#EFEFEF\" colspan=\"3\">
                        &nbsp;".$E3cek." Prosedur Tidak Diikuti / <em>Procedures Not Followed</em></td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td  colspan=\"3\">
                        &nbsp;".$E4cek." Petunjuk Standar Tidak Memadai / <em>Orderliness Standards Inaquate</em></td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td bgcolor=\"#EFEFEF\" colspan=\"3\">
                        &nbsp;".$E5cek." Petunjuk Standar Tidak Diketahui / Dimengerti / <em>Orderliness Standards Not Known / Understood</em></td>
                </tr>
                <tr height=\"19px;\" class=\"style5\">
                    <td  colspan=\"3\">
                        &nbsp;".$E6cek." Prosedur Tidak Diikuti / <em>Orderliness Not Followed</em><br/>&nbsp;</td>
                </tr>
<!-- END OF 5 ================================================================================================================ -->";
	
	$tpl->Assign("isiCardList", $isiCardList);
	
	$isiCardComment.= "<tr class=\"style5\">
                  <td colspan=\"3\"><br/>
                    	<ul>
                        	<li style=\"margin-left:-15px;\">Tindakan Aman yang Diamati / <em>Safe Acts Observerd</em></li>
                            <li style=\"margin-left:-15px;\">Rekomendasi untuk peningkatan Keselamatan / <em>Recommendation for Safety Improvement</em></li>
                        </ul>
				  </td>
             </tr>
             <tr class=\"style5\">
            	<td width=\"8%\">&nbsp;</td><td width=\"90%\"  class=\"tabelBorderAll\">".$aman."</td><td width=\"2%\">&nbsp;</td>
             </tr>
             <tr><td height=\"1\">&nbsp;</td></tr>
             <tr class=\"style5\">         
                  <td colspan=\"3\">
                        <ul>
                        	<li style=\"margin-left:-15px;\">Tindakan Tidak Aman yang Diamati / <em>Unsafe Acts Observerd</em></li>
                            <li style=\"margin-left:-15px;\">Tindakan Perbaikan / <em>Immediate Corrective Action</em></li>
                            <li style=\"margin-left:-15px;\">Tindakan Pencegahan / <em>Action to Prevent Recurrence</em></li>
                        </ul>
                  </td>
             </tr>
             <tr class=\"style5\">
            	<td width=\"8%\">&nbsp;</td>
            	<td width=\"90%\"  class=\"tabelBorderAll\">".$tidakAman."</td><td width=\"2%\">&nbsp;</td>
             </tr>
             <tr><td height=\"1\">&nbsp;</td></tr>
            <tr class=\"style5\">
                <td colspan=\"3\">
                 <ul>
                    <li style=\"margin-left:-15px;\">Lokasi / <em>Location</em></li>
                 </ul>			
                 </td>
            </tr>
            <tr class=\"style5\">
            	<td width=\"8%\">&nbsp;</td>
            	<td width=\"90%\"  class=\"tabelBorderAll\">".$CPublic->konversiQuotes($lokasi)."</td><td width=\"2%\">&nbsp;</td>
             </tr>
			 
	<!--<tr class=\"style5\">
                  <td colspan=\"3\"><br/>
                    	<ul>
                        	<li style=\"margin-left:-15px;width:300px\">Tindakan Aman yang Diamati / <em>Safe Acts Observerd</em></li>
                            <li style=\"margin-left:-15px;width:300px\">Rekomendasi untuk peningkatan Keselamatan / <em>Recommendation for Safety Improvement</em></li>
						</ul>
				  </td>
				  </tr>
                  <tr class=\"style5\" style=\"width:100\"><td colspan=\"3\" style=\"border:1;\">".$aman."<td>
				  <tr class=\"style5\"><td colspan=\"3\">
						<ul>
                        	<li style=\"margin-left:-15px;width:300px\">Tindakan <span style=\"color:red\">Tidak</span> Aman yang Diamati / <em>Unsafe Acts Observerd</em></li>
                            <li style=\"margin-left:-15px;width:300px\">Tindakan Perbaikan / <em>Immediate Corrective Action</em></li>
                            <li style=\"margin-left:-15px;width:300px\">Tindakan Pencegahan / <em>Action to Prevent Recurrence</em></li>
                            <li style=\"list-style:none;margin-left:-15px;\"><textarea class=\"elementDefault\" name=\"lapTidakAman\" id=\"lapTidakAman\" style=\"width:300px;height:150px;\" readonly></textarea></li>
                        </ul>
                    </td>
                </tr>
                <tr class=\"style5\">
					<td style=\"width:20;\">$nbsp;</td>
                	<td colspan=\"2\" style=\"width:100;\">Lokasi / <em>Location</em> : ".$CPublic->konversiQuotes($lokasi)."</td>
                </tr>-->";
	
	$tpl->Assign("isiCardComment", $isiCardComment);
	$tpl->printToScreen();
	
}
// ======== END OF == Print Stop Card ===========================================================
// ======== START == Print Monthly Stop Card ===========================================================
if($aksiPost == "printMonthly")
{
	
	$tpl = new myTemplate("templates/laporanMonthly.html");
	$thnBlnPost = $_POST['idMonth'];
	
	$tpl->prepare();
	
	$html = "";
	$isiMonthly = "";
	
	// CARI JUMLAH KELUHAN BERDASARKAN BULAN YANG DIPILIH
	$jmlAllKeluhan = $CKoneksi->mysqlNRows($CKoneksi->mysqlQuery("SELECT * FROM tblkeluhan WHERE SUBSTR(date,1,7)='".$thnBlnPost."' AND deletests=0;")); 
	$limit = 80; // LIMIT DARI JUMLAH KELUHAN PER SATU HALAMAN
	$maxPage = ceil($jmlAllKeluhan/$limit); // JUMLAH PAGE YANG DIDAPAT DARI PEMBAGIAN SEMUA DATA KELUHAN DIBAGI LIMIT PER HALAMAN DAN DIBULATKAN
	//$pageNum = 1;

//-- START 1 halaman ============================================================================================================	
	if($jmlAllKeluhan <= $limit) 
	{
		$limitKeluhan = 40;
		$html.= $CQhse->judulAtas($CPublic, $thnBlnPost);
			
			$html.= "<tr>
						<td>
						<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
						<tr>
							<td>
							<table align=\"center\"><tr><td>";
			$html.= "<table width=\"350\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
            	<tr align=\"center\" height=\"25px;\" bgcolor=\"#E9E9E9\" class=\"style3\">
                	<td width=\"7%\" class=\"tabelBorderRightNull\">No. </td>
                	<td width=\"22%\" class=\"tabelBorderRightNull\">&nbsp;Created Date</td>
                    <td width=\"58%\" class=\"tabelBorderRightNull\">&nbsp;Name</td>
                    <td width=\"10%\" class=\"tabelBorderAll\">Read</td>
                </tr>";
				
				
			$query1 = $CKoneksi->mysqlQuery("SELECT * FROM tblkeluhan WHERE SUBSTR(date,1,7)='".$thnBlnPost."' AND deletests=0 ORDER BY SUBSTR(date,9,2) ASC LIMIT 0, ".$limitKeluhan.";");
			while($row1 = $CKoneksi->mysqlFetch($query1))
			{
				$owner = $row1['ownername'];
				$tgl = $row1['date'];
				$thnSub = substr($tgl,0,4);
				$blnSub = substr($tgl,5,2);
				$tglSub = substr($tgl,8,2);
				$tglPr = ucfirst(strtolower($CPublic->bulanSetengah($blnSub, "eng")))." ".$tglSub.", ".$thnSub;
				$read = $row1['adminread'];
				$ceklist = "<img src=\"../../picture/check-black.png\" width=\"12\" style=\"vertical-align:middle;\"/>";
				if($read == "N")
				{
					$ceklist = "&nbsp;";
				}
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
						<td width=\"22%\" class=\"tabelBorderTopRightNull\">&nbsp;".$tglPr."</td>
						<td width=\"58%\" class=\"tabelBorderTopRightNull\" align=\"left\">&nbsp;".$owner."</td>
						<td width=\"10%\" valign=\"middle\"align=\"center\" class=\"tabelBorderTopNull\">&nbsp;".$ceklist."</td>
					</tr>";
			}
			
			$html.= "</table>
            </td>
            <td valign=\"top\">
            <table width=\"350\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
            	<tr align=\"center\" height=\"25px;\" bgcolor=\"#E9E9E9\" class=\"style3\">
                	<td width=\"7%\" class=\"tabelBorderRightNull\">No. </td>
                	<td width=\"22%\" class=\"tabelBorderRightNull\">&nbsp;Created Date</td>
                    <td width=\"58%\" class=\"tabelBorderRightNull\">&nbsp;Name</td>
                    <td width=\"10%\" class=\"tabelBorderAll\">Read</td>
                </tr>";
				
			$query2 = $CKoneksi->mysqlQuery("SELECT * FROM tblkeluhan WHERE SUBSTR(date,1,7)='".$thnBlnPost."' AND deletests=0 ORDER BY SUBSTR(date,9,2) ASC LIMIT ".$limitKeluhan.", ".$limitKeluhan.";");
			while($row2 = $CKoneksi->mysqlFetch($query2))
			{
				$owner2 = $row2['ownername'];
				$tgl2 = $row2['date'];
				$thnSub2 = substr($tgl2,0,4);
				$blnSub2 = substr($tgl2,5,2);
				$tglSub2 = substr($tgl2,8,2);
				$tglPr2 = ucfirst(strtolower($CPublic->bulanSetengah($blnSub2, "eng")))." ".$tglSub2.", ".$thnSub2;
				$read2 = $row2['adminread'];
				$ceklist2 = "<img src=\"../../picture/check-black.png\" width=\"12\" style=\"vertical-align:middle;\"/>";
				if($read2 == "N")
				{
					$ceklist2 = "&nbsp;";
				}
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
						<td width=\"22%\" class=\"tabelBorderTopRightNull\">&nbsp;".$tglPr2."</td>
						<td width=\"58%\" class=\"tabelBorderTopRightNull\" align=\"left\">&nbsp;".$owner2."</td>
						<td width=\"10%\" valign=\"middle\"align=\"center\" class=\"tabelBorderTopNull\">&nbsp;".$ceklist2."</td>
					</tr>";
			}
				
				
            $html.= "</table>";
			
			$html.= "	</td>
					</tr>
						</table>
						</td>
						</tr>
						</table>
						</td>
					   
					</tr>";
			$html.= $CQhse->footer();
			$html.= "<tr><td style=\"position:absolute;bottom:-0%;width:99%;right:5%;font:0.6em sans-serif;\" class=\"tabelBorderAllNull\">Halaman 1 dari 1</td></tr>";
			
	}
//-- END OF 1 halaman ===========================================================================================================
//-- START LEBIH DARI 1 halaman ================================================================================================
	elseif($jmlAllKeluhan > $limit) 
	{
		for($a=1;$a<=$maxPage;$a++)
		{
			if($a == 1) // JIKA HALAMAN 1 MAKA NOMOR MULAI DARI 1 (NOMOR HALAMAN)
			{	
				$persenheight = 0;
				
				$pagebreak = "";
				$offset = 0;
				$no = $offset + 1;
			}
			else // JIKA SELAIN HALAMAN SATU MAKA BERTAMBAH SESUAI JUMLAH HALAMAN DAN URUTAN
			{
				$persenheight = ($a-1) * 100;
				
				$pagebreak = "<tr style=\"page-break-after: left\"></tr>"; // UNTUK PINDAH HALAMAN BERIKUTNYA
				$offset = ($a-1) * $limit;
				$no = $offset+1;
			}
			
			$limitKeluhan = 40;
			$html.= $pagebreak;
			$html.= $CQhse->judulAtas($CPublic, $thnBlnPost);
			
			$html.= "<tr>
						<td>
						<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
						<tr>
							<td>
							<table align=\"center\"><tr><td>";
			$html.= "<table width=\"350\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
            	<tr align=\"center\" height=\"25px;\" bgcolor=\"#E9E9E9\" class=\"style3\">
                	<td width=\"7%\" class=\"tabelBorderRightNull\">No. </td>
                	<td width=\"22%\" class=\"tabelBorderRightNull\">&nbsp;Created Date</td>
                    <td width=\"58%\" class=\"tabelBorderRightNull\">&nbsp;Name</td>
                    <td width=\"10%\" class=\"tabelBorderAll\">Read</td>
                </tr>";
				
			$query1 = $CKoneksi->mysqlQuery("SELECT * FROM tblkeluhan WHERE SUBSTR(date,1,7)='".$thnBlnPost."' AND deletests=0 ORDER BY SUBSTR(date,9,2) ASC LIMIT ".$offset.", ".$limitKeluhan.";");
			while($row1 = $CKoneksi->mysqlFetch($query1))
			{
				$owner = $row1['ownername'];
				$tgl = $row1['date'];
				$thnSub = substr($tgl,0,4);
				$blnSub = substr($tgl,5,2);
				$tglSub = substr($tgl,8,2);
				$tglPr = ucfirst(strtolower($CPublic->bulanSetengah($blnSub, "eng")))." ".$tglSub.", ".$thnSub;
				$read = $row1['adminread'];
				$ceklist = "<img src=\"../../picture/check-black.png\" width=\"12\" style=\"vertical-align:middle;\"/>";
				if($read == "N")
				{
					$ceklist = "&nbsp;";
				}
				$i++;
				if($i%2 == 1)
				{
					$bgColor = "";
				}
				else
				{
					$bgColor = "bgcolor=\"#F2F2F2\"";
				}
					
			$html.= "<tr align=\"center\" ".$bgColor." height=\"20px;\" class=\"style5\">
						<td width=\"7%\" class=\"tabelBorderTopRightNull\">".$i."</td>
						<td width=\"22%\" class=\"tabelBorderTopRightNull\">&nbsp;".$tglPr."</td>
						<td width=\"58%\" class=\"tabelBorderTopRightNull\" align=\"left\">&nbsp;".$owner."</td>
						<td width=\"10%\" valign=\"middle\"align=\"center\" class=\"tabelBorderTopNull\">&nbsp;".$ceklist."</td>
					</tr>";
			}
			
			$html.= "</table>
            </td>
            <td valign=\"top\">
            <table width=\"350\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
            	<tr align=\"center\" height=\"25px;\" bgcolor=\"#E9E9E9\" class=\"style3\">
                	<td width=\"7%\" class=\"tabelBorderRightNull\">No. </td>
                	<td width=\"22%\" class=\"tabelBorderRightNull\">&nbsp;Created Date</td>
                    <td width=\"58%\" class=\"tabelBorderRightNull\">&nbsp;Name</td>
                    <td width=\"10%\" class=\"tabelBorderAll\">Read</td>
                </tr>";
			$query2 = $CKoneksi->mysqlQuery("SELECT * FROM tblkeluhan WHERE SUBSTR(date,1,7)='".$thnBlnPost."' AND deletests=0 ORDER BY SUBSTR(date,9,2) ASC LIMIT ".($offset+$limitKeluhan).", ".$limitKeluhan.";");
			while($row2 = $CKoneksi->mysqlFetch($query2))
			{
				$owner2 = $row2['ownername'];
				$tgl2 = $row2['date'];
				$thnSub2 = substr($tgl2,0,4);
				$blnSub2 = substr($tgl2,5,2);
				$tglSub2 = substr($tgl2,8,2);
				$tglPr2 = ucfirst(strtolower($CPublic->bulanSetengah($blnSub2, "eng")))." ".$tglSub2.", ".$thnSub2;
				$read2 = $row2['adminread'];
				$ceklist2 = "<img src=\"../../picture/check-black.png\" width=\"12\" style=\"vertical-align:middle;\"/>";
				if($read2 == "N")
				{
					$ceklist2 = "&nbsp;";
				}
				$i++;
				if($i%2 == 1)
				{
					$bgColor = "";
				}
				else
				{
					$bgColor = "bgcolor=\"#F2F2F2\"";
				}
					
			$html.= "<tr align=\"center\" ".$bgColor." height=\"20px;\" class=\"style5\">
						<td width=\"7%\" class=\"tabelBorderTopRightNull\">".$i."</td>
						<td width=\"22%\" class=\"tabelBorderTopRightNull\">&nbsp;".$tglPr2."</td>
						<td width=\"58%\" class=\"tabelBorderTopRightNull\" align=\"left\">&nbsp;".$owner2."</td>
						<td width=\"10%\" valign=\"middle\"align=\"center\" class=\"tabelBorderTopNull\">&nbsp;".$ceklist2."</td>
					</tr>";
			}
			
            $html.="</table>";
			$html.= "	</td>
					</tr>
						</table>
						</td>
						</tr>
						</table>
						</td>
					   
					</tr>";
			$html.= $CQhse->footer();
			$html.= "<tr><td style=\"position:absolute;bottom:-".$persenheight."%;width:99%;right:5%;font:0.6em sans-serif;\" class=\"tabelBorderAllNull\">Halaman ".$a." dari ".$maxPage."</td></tr>";
		}
//-- END OF LEBIH DARI 1 halaman ================================================================================================
	}	
	
	$isiMonthly = $html;
	
	$tpl->Assign("isiMonthly", $isiMonthly);
	$tpl->printToScreen();
}

if($aksiPost == "printMonthlyy")
{
	$tpl = new myTemplate("templates/laporanMonthly.html");
	$thnBlnPost = $_POST['idMonth'];
	
	$tpl->prepare();
	$i = 0 ;
	$query = $CKoneksi->mysqlQuery("SELECT * FROM tblkeluhan WHERE SUBSTR(date,1,7)='".$thnBlnPost."' AND deletests=0;");
	while($row = $CKoneksi->mysqlFetch($query)){
		
		$owner = $row['ownername'];
		$tgl = $row['date'];
		$thnSub = substr($tgl,0,4);
		$blnSub = substr($tgl,5,2);
		$tglSub = substr($tgl,8,2);
		$tglPr = ucfirst(strtolower($CPublic->bulanSetengah($blnSub, "eng")))." ".$tglSub.", ".$thnSub;
		$read = $row['adminread'];
		$ceklist = "<img src=\"../../picture/check-black.png\" width=\"12\" style=\"vertical-align:middle;\"/>";
		if($read == "N")
		{
			$ceklist = "&nbsp;";
		}
		$i++;
		
		$isiMonthly.="<tr align=\"center\" class=\"style5\">
						<td width=\"5%\" class=\"tdBorderLeftTopNull\">".$i."</td>
						<td width=\"24%\" class=\"tdBorderLeftTopNull\">&nbsp;".$tglPr."</td>
						<td width=\"58%\" class=\"tdBorderLeftTopNull\">&nbsp;".$owner."</td>
						<td width=\"10%\" valign=\"top\"align=\"center\" class=\"tdBorderBottomJust\">".$ceklist."</td>
					</tr>";
	}
	$tpl->Assign("isiMonthly", $isiMonthly);
	$tpl->printToScreen();
}
// ======== END OF == Print Monthly Stop Card ===========================================================
?>
<script language="javascript">
<?php
function statusCek($CKoneksi, $idKeluhanGet, $field)
{
	$cek = "&nbsp&nbsp&nbsp&nbsp";
	$query = $CKoneksi->mysqlQuery("SELECT ".$field." FROM tblkeluhan WHERE idkeluhan = '".$idKeluhanGet."' AND deletests=0 LIMIT 1");
	$row = $CKoneksi->mysqlFetch($query);
	
	if($row[$field] == "Y")
	{
		$cek = "<img src=\"../../picture/check-black.png\" width=\"12\" style=\"vertical-align:middle;\"/> ";
	}
	return $cek;
}
?>
</script>