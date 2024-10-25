<?php
class CSpj
{	
	var $tabel;
	
	function CSpj($koneksiOdbcId, $koneksiOdbc, $koneksiMysql)
	{
		$this->koneksi = $koneksiMysql;
		$this->koneksiOdbc = $koneksiOdbc;
		$this->koneksiOdbcId = $koneksiOdbcId;
		$tabel = "";
	}
	
	function detilLoginSpj($userId, $field, $DB)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM ".$DB.".login WHERE userid='".$userId."' AND ACTIVE='Y' AND deletests=0");
		$row = $this->koneksi->mysqlFetch($query);
		$isi = $row[$field];
		
		return $isi;
	}
	
	function detilLoginSpjByEmpno($empNo, $field, $DB)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM ".$DB.".login WHERE empno='".$empNo."' AND ACTIVE='Y' AND deletests=0");
		$row = $this->koneksi->mysqlFetch($query);
		$isi = $row[$field];
		
		return $isi;
	}
	
	function menuUser($db,$idEdit = "")
	{
		$query = $this->koneksi->mysqlQuery("SELECT * FROM ".$db.".login WHERE active = 'Y' AND deletests = 0 ORDER BY userfullnm ASC;");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			if($idEdit != $row['userid'])
			{
				$html.= "<option value=\"".$row['userid']."\" ".$sel.">".$row['userfullnm']."</option>";
			}else{
				$html.= "<option selected=\"selected\" value=\"".$row['userid']."\" ".$sel.">".$row['userfullnm']."</option>";
			}
			
		}
		
		return $html;
	}
	
	function detilForm($formId, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM form WHERE formid = ".$formId." AND deletests = 0");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function follower($formId, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM follower WHERE formid = ".$formId." AND deletests = 0;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function jmlFollower($formId, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM follower WHERE formid = ".$formId."");
		$jml = $this->koneksi->mysqlNRows($query);
		
		return $jml;
	}
	
	function menuFollower($formId, $db)
	{
		$i=1;
		$query = $this->koneksi->mysqlQuery("SELECT * FROM follower WHERE formid = ".$formId."");
		$jml = $this->koneksi->mysqlNRows($query);
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$br = "<br/>";
			if($i == $jml)
			{
				$br = "";
			}
			$html.= ucwords(strtolower($this->detilLoginSpj($row['followerid'], "userfullnm", $db))).$br;
			$i++;
		}
		
		return $html;
	}
	
	function urutanAkhir()
	{
		$query = $this->koneksi->mysqlQuery("SELECT urutan FROM form WHERE deletests = 0 ORDER BY urutan DESC LIMIT 1;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['urutan'];
	}
	
	function cekKadiv($empNo)
	{	// field yang ada kddiv, nmdiv, divhead, deputy
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT kddiv FROM dbo.tblmstdiv WHERE divhead='".$empNo."' AND deletests=0");
		$jml = $this->koneksiOdbc->odbcNRows($query);
		
		return $jml;
	}
	
	function detilDivByDivhead($empNo, $field)
	{	// field yang ada kddiv, nmdiv, divhead, deputy
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT ".$field." FROM dbo.tblmstdiv WHERE divhead='".$empNo."' AND deletests=0");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row[$field];
	}
	
	function userJenis($userId)
	{	
		$query = $this->koneksi->mysqlQuery("SELECT userjenis FROM msuser WHERE userid = ".$userId."");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['userjenis'];
	}
	
	function userJenisByJenis($jenis, $field)
	{	
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM msuser WHERE userjenis = '".$jenis."'");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function idByUserJenis($field, $userJenis)
	{	
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM msuser WHERE userjenis = '".$userJenis."'");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function lastNum($kdCmp, $tahun)
	{
		$query = $this->koneksi->mysqlQuery("SELECT formnum FROM form WHERE kdcmp = ".$kdCmp." AND SUBSTRING(datefrom,1,4) = ".$tahun." AND deletests = 0 ORDER BY formnum DESC LIMIT 1");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['formnum'];
	}
	
	function listTembusan($formId)
	{
		$i = 1;
		$query = $this->koneksi->mysqlQuery("SELECT * FROM copy WHERE formid = ".$formId." ORDER BY copyid ASC;");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$html.= $i.") &nbsp;".$row['copycontent']."<br/>";
			$i++;
		}
		
		return $html;
	}
	
	function jmlTembusan($formId)
	{
		$i = 1;
		$query = $this->koneksi->mysqlQuery("SELECT * FROM copy WHERE formid = ".$formId.";");
		$jml = $this->koneksi->mysqlNRows($query);
		
		return $jml;
	}
	
	function cariKadivId($ownerEmpno, $CEmployee, $db)// Kadiv Employee Number dari pemilik EmpNo
	{
		$kadivEmpNo = $CEmployee->detilDiv($CEmployee->detilTblEmpGen($ownerEmpno, "kddiv"), "divhead");
		$kadivId = $this->detilLoginSpjByEmpno($kadivEmpNo, "userid", $db);
		
		return $kadivId;
	}
	
	function dateRange($startDate, $endDate, $active, $CPublic)
	{
		$start    = new DateTime($startDate);
		$end      = new DateTime($endDate);
		$interval = new DateInterval('P1D'); // 1 day interval
		$period   = new DatePeriod($start, $interval, $end);
		
		
		foreach ($period as $day) {
			// Do stuff with each $day...
			//echo $day->format('Ymd'), "<br/>";
			$html.= $day->format('Ymd')."<br/>";
			$value = $day->format('Ymd');
			
			$sel = "";
			if($value == $active)
			{
				$sel = "selected";
			}
			
			$html.= '<option value="'.$value.'" '.$sel.'>'.$day->format('d')." ".ucwords(strtolower($CPublic->detilBulanNamaAngka($day->format('m'), "ind")))." ".$day->format('Y').'</option>';
		}
		
		//penambahan hari terakhir
		$sel = "";
		$value = str_replace("-","",$endDate);
		if($value == $active)
		{
			$sel = "selected";
		}
		$html.= '<option value="'.$value.'" '.$sel.'>'.substr($endDate,8,2)." ".ucwords(strtolower($CPublic->detilBulanNamaAngka(substr($endDate,5,2), "ind")))." ".substr($endDate,0,4).'</option>';
		
		return $html;
	}
	
	function detilFormEmail($formId, $CPublic, $db)
	{
		$dateForm = $this->detilForm($formId, "datefrom");
		$thn =  substr($dateForm,0,4);
		$bln =  substr($dateForm,4,2);
		$tgl =  substr($dateForm,6,2);
		$formDate =  $tgl." ".$CPublic->detilBulanNamaAngka($bln, "ind")." ".$thn;
		
		$dateTo = $this->detilForm($formId, "dateto");
		$thnTo =  substr($dateTo,0,4);
		$blnTo =  substr($dateTo,4,2);
		$tglTo =  substr($dateTo,6,2);
		$toDate =  $tglTo." ".$CPublic->detilBulanNamaAngka($blnTo, "ind")." ".$thnTo;
		
		$html= '<tr height="17px">
				<td rowspan="5" width="1%"> 
				<td width="13%" align="left">
					Nama
				</td>
				<td width="36%" align="left" style="color:#000080;">
					'.ucwords(strtolower($this->detilForm($formId, "ownername"))).'
				</td>
				<td width="17%" align="left">
					Keperluan
				</td>
				<td width="33%" align="left" style="color:#000080;">
					'.$this->detilForm($formId, "necessary").'
				</td>
			</tr>
			
			<tr height="17px">
				<td align="left">
					Jabatan
				</td>
				<td align="left" style="color:#000080;">
					'.ucwords(strtolower($this->detilForm($formId, "jabatan"))).'
				</td>
				<td align="left">
					Tanggal Berangkat
				</td>
				<td align="left" style="color:#000080;">
					'.ucwords(strtolower($formDate)).'
				</td>
			</tr>
			
			<tr height="17px">
				<td align="left">
					Golongan
				</td>
				<td align="left" style="color:#000080;">
					'.ucwords(strtolower($this->detilForm($formId, "golongan"))).'
				</td>
				<td align="left">
					Tanggal Kembali
				</td>
				<td align="left" style="color:#000080;">
					'.ucwords(strtolower($toDate)).'
				</td>
			</tr>
			
			<tr height="17px">
				<td align="left">
					Tempat Tujuan
				</td>
				<td align="left" style="color:#000080;">
					'.$this->detilForm($formId, "destination").'
				</td>
				<td align="left">
					Kendaraan
				</td align="left">
				<td align="left" style="color:#000080;">
					'.$this->detilForm($formId, "vehicle").'
				</td>
			</tr>
			
			<tr height="17px">
				<td align="left" valign="top">
					Catatan
				</td>
				<td align="left" style="color:#000080;">
					'.$this->detilForm($formId, "note").'
				</td>
				<td align="left" valign="top">
					Pengikut
				</td>
				<td align="left" style="color:#000080;" valign="top">
					'.$this->menuFollower($formId, $db).'
				</td>
			</tr>';
			
		return $html;
	}
	
	function dataAprovalForm($formId, $CPublic, $db)
	{
		$tglSpjEcho = "<i>Waiting . . .</i>";
		if($this->detilForm($formId, "spjdate") != "")
		{
			$tglSpjDb = $this->detilForm($formId, "spjdate");
			$thnSpj =  substr($tglSpjDb,0,4);
			$blnSpj =  substr($tglSpjDb,4,2);
			$tglSpj =  substr($tglSpjDb,6,2);
			$tglSpjEcho =  $tglSpj." ".$CPublic->detilBulanNamaAngka($blnSpj, "ind")." ".$thnSpj;
		}
						
		$aprv = $this->detilForm($formId, "aprvempno");
		$ack = $this->detilForm($formId, "knowempno");
		
		$aprvAdm = $this->detilForm($formId, "aprvbyadm");
		$ackAdm = $this->detilForm($formId, "knowbyadm");
		
		$listTembusan = $this->listTembusan($formId);
		
		$html.= "<tr align=\"left\">
					<td width=\"14%\">
						Dikeluarkan di
					</td>
					<td width=\"86%\" style=\"color:#000080;\">
						Jakarta
					</td>
				</tr>
				<tr align=\"left\">
					<td>
						Pada Tanggal
					</td>
					<td style=\"color:#000080;\">
						".$tglSpjEcho."
					</td>
				</tr>
				<tr><td height=\"5\" style=\"border-bottom:solid 1px #CCC;\"></td><td></td></tr>
				<tr><td height=\"3\" colspan=\"2\"></td></tr>
				
				<tr align=\"left\">
					<td>
						Menyetujui
					</td>
					<td style=\"color:#000080;\">";
						$aprvName = "Kadiv / CEO";
						if($aprv != 00000 && $aprvAdm == "N")
						{
							$aprvName = $this->detilLoginSpjByEmpno($aprv, "userfullnm", $db);
						}
						if($aprv != 00000 && $aprvAdm == "Y")
						{
							$aprvName = $this->detilLoginSpjByEmpno($aprv, "userfullnm", $db);
							// $aprvName = "<i>Approved by Administrator</i>";
						}
		$html.= "		".$aprvName."
					</td>
				</tr>
				<tr align=\"left\">
					<td>
						Status
					</td>
					<td style=\"color:#000080;\">";
						$statAprv = "<i>Waiting . . .</i>";
						if($aprv != 00000)
						{
							$statAprv = "<i>APPROVED</i>";
						}
						
						
		$html.= "		".$statAprv."
					</td>
				</tr>
				<tr><td height=\"5\" style=\"border-bottom:solid 1px #CCC;\"></td><td></td></tr>
				<tr><td height=\"2\" colspan=\"2\"</td></tr>
				
				<tr align=\"left\">
					<td>
						Mengetahui
					</td>
					<td style=\"color:#000080;\">";    
						$know = "[Kepala Divisi HR / perwakilan]";
						if($this->detilForm($formId, "knowempno") != 00000 && $this->detilForm($formId, "knowbyadm") == "N")//jika Sudah Acknowledge
						{
							$know = $this->detilLoginSpjByEmpno($ack, "userfullnm", $db);
						}
						if($this->detilForm($formId, "knowempno") != 00000 && $this->detilForm($formId, "knowbyadm")== "Y")
						{
							$know = $this->detilLoginSpjByEmpno($ack, "userfullnm", $db);
							// $know = "<i>Acknowledged by Administrator</i>";
						}
						
		$html.= "		".$know."
					</td>
				</tr>
				<tr align=\"left\">
					<td>
						Status
					</td>
					<td style=\"color:#000080;\">";
						$statKnow = "<i>Waiting . . .</i>";
						if($this->detilForm($formId, "knowempno") != 00000)
						{
							$statKnow = "<i>ACKNOWLEDGED</i>";
						}
						
		$html.= "		".$statKnow."
					</td>
				</tr>
				<tr><td height=\"5\" style=\"border-bottom:solid 1px #CCC;\"></td><td></td></tr>";
				
		if($aprv != 00000 && $ack != 00000)
		{
			$html.= "<tr align=\"left\">
						<td valign=\"top\">
							Tembusan
						</td>
						<td style=\"color:#000080;\">
							".$listTembusan."
						</td>
					</tr>
					<tr><td height=\"5\" style=\"border-bottom:solid 1px #CCC;\"></td><td></td></tr>";		
		}

		return $html;
	}
	
	function detilRevForm($formId, $ket, $db)
	{
		if($ket == "revForm")
		{
			$tipe = "Revise";
		}
		if($ket == "cancelForm")
		{
			$tipe = "Cancel";
		}
		
		$html.= '<tr height="17px">
					<td width="1%"></td>
					<td align="left" width="13%">'.$tipe.' Request</td>
					<td align="left" width="86%" style="color:#000080;">'.ucwords(strtolower($this->detilLoginSpjByEmpno($this->detilForm($formId, "reasonempno"), "userfullnm", $db))).'</td>
				</tr>
				<tr height="17px">
					<td></td>
					<td align="left">'.$tipe.' Reason</td>
					<td align="left" style="color:#000080;">'.$this->detilForm($formId, "reason").'</td>
				</tr>';
				
		return $html;
	}
	
	function detilReportEmail($reportId, $CPublic, $CEmployee, $db)
	{
		$formId = $this->detilReport($reportId, "formid");
		
		$dateForm = $this->detilForm($formId, "datefrom");
		$thn =  substr($dateForm,0,4);
		$bln =  substr($dateForm,4,2);
		$tgl =  substr($dateForm,6,2);
		$formDate =  $tgl." ".$CPublic->detilBulanNamaAngka($bln, "ind")." ".$thn;
		
		$dateTo = $this->detilForm($formId, "dateto");
		$thnTo =  substr($dateTo,0,4);
		$blnTo =  substr($dateTo,4,2);
		$tglTo =  substr($dateTo,6,2);
		$toDate =  $tglTo." ".$CPublic->detilBulanNamaAngka($blnTo, "ind")." ".$thnTo;
		
		$kdDiv = $CEmployee->detilTblEmpGen($this->detilForm($formId, "ownerempno"), "kddiv");
		$nmDiv = $CEmployee->detilDiv($kdDiv, "nmdiv");
		
		$kdDept = $CEmployee->detilTblEmpGen($this->detilForm($formId, "ownerempno"), "kddept");
		$nmDept = $CEmployee->detilDept($kdDept, "nmdept");
		
		$html= '<tr height="17px">
				<td rowspan="5" width="1%"> 
				<td width="18%" align="left">
					Nama Karyawan
				</td>
				<td width="41%" align="left" style="color:#000080;">
					'.ucwords(strtolower($this->detilForm($formId, "ownername"))).'
				</td>
				<td width="17%" align="left">
					Tujuan
				</td>
				<td width="23%" align="left" style="color:#000080;">
					'.$this->detilForm($formId, "destination").'
				</td>
			</tr>
			
			<tr height="17px">
				<td align="left">
					Jabatan / Golongan
				</td>
				<td align="left" style="color:#000080;">
					'.ucwords(strtolower($this->detilForm($formId, "jabatan"))).' / '.ucwords(strtolower($this->detilForm($formId, "golongan"))).'
				</td>
				<td align="left">
					Tanggal Berangkat
				</td>
				<td align="left" style="color:#000080;">
					'.ucwords(strtolower($formDate)).'
				</td>
			</tr>
			
			<tr height="17px">
				<td align="left">
					Divisi / Departemen
				</td>
				<td align="left" style="color:#000080;">
					'.$nmDiv.' / '.$nmDept.'
				</td>
				<td align="left">
					Tanggal Kembali
				</td>
				<td align="left" style="color:#000080;">
					'.ucwords(strtolower($toDate)).'
				</td>
			</tr>
			
			<tr height="17px">
				<td align="left">
					Nomor SPJ
				</td>
				<td align="left" style="color:#000080;">
					'.$this->detilForm($formId, "spjno").'
				</td>
				<td align="left">
					Kendaraan
				</td align="left">
				<td align="left" style="color:#000080;">
					'.$this->detilForm($formId, "vehicle").'
				</td>
			</tr>';
			
		return $html;
	}
	
	function dataDetilAproval($reportId, $CPublic, $db)
	{
		$echoIdrTotal = "&nbsp;";
		if($this->detilReport($reportId, "idrgrandtotal") != "" && $this->detilReport($reportId, "idrgrandtotal") != 0 )
		{
			$echoIdrTotal = number_format($this->detilReport($reportId, "idrgrandtotal"));
		}
		$echoUsdTotal = "&nbsp;";
		if($this->detilReport($reportId, "usdgrandtotal") != "" && $this->detilReport($reportId, "usdgrandtotal") != 0)
		{
			$echoUsdTotal = number_format($this->detilReport($reportId, "usdgrandtotal"));
		}
		
		$echoIdrDp = "&nbsp;";
		if($this->detilReport($reportId, "idrdp") != "")
		{
			$echoIdrDp = number_format($this->detilReport($reportId, "idrdp"));
		}
		$echoUsdDp = "&nbsp;";
		if($this->detilReport($reportId, "usddp") != "" )
		{
			$echoUsdDp = number_format($this->detilReport($reportId, "usddp"));
		}
		
		$echoIdrKembali = "&nbsp;";
		if($this->detilReport($reportId, "idrtotalkembali") != "")
		{
			$echoIdrKembali = number_format($this->detilReport($reportId, "idrtotalkembali"));
		}
		$echoUsdKembali = "&nbsp;";
		if($this->detilReport($reportId, "usdtotalkembali") != "" )
		{
			$echoUsdKembali = number_format($this->detilReport($reportId, "usdtotalkembali"));
		}
		
		$ack = $this->detilReport($reportId, "ackempno");
		$exm = $this->detilReport($reportId, "periksaempno");
		//$prcs = $this->detilReport($reportId, "prosesempno");

		$echoOtherCur1Total = "";
		if($this->detilReport($reportId, "othercur1grandtotal") != "" && $this->detilReport($reportId, "othercur1grandtotal") != 0)
		{
			$echoOtherCur1Total = number_format($this->detilReport($reportId, "othercur1grandtotal"));
		}
		$echoOtherCur2Total = "";
		if($this->detilReport($reportId, "othercur2grandtotal") != "" && $this->detilReport($reportId, "othercur2grandtotal") != 0)
		{
			$echoOtherCur2Total = number_format($this->detilReport($reportId, "othercur2grandtotal"));
		}
		$otherCur1 = $this->detilReport($reportId, "othercur1");
		$otherCur2 = $this->detilReport($reportId, "othercur2");	
		$other1 = $this->detilCurrency($otherCur1, "currencyname");
		$other2 = $this->detilCurrency($otherCur2, "currencyname");
		if($otherCur1 != 0)
		{
			$tdCur1 = '<td width=\"11%\" style=\"color:#000080;\" align=\"center\" style=\"border:#CCC solid;border-width:1px 1px 1px 0;\"><b>'.$other1.'</b></td>';
			$tdCur1TotalAkhir = "<td style=\"color:#000080;border:#CCC solid;border-width:1px 1px 1px 0;\" align=\"center\">
						<b>".$echoOtherCur1Total."</b>
					</td>";
			$tdHeadCur1 = "<td style=\"color:#000080;border:#CCC solid;border-width:0 1px 0 0;\" align=\"center\">
					</td>";
		}
		if($otherCur2 != 0)
		{
			$tdCur2 = "<td style=\"color:#000080;border:#CCC solid;border-width:1px 1px 1px 0;\" align=\"center\">
						<b>".$echoUsdKembali."</b>
					</td>";
			$tdCur2TotalAkhir = "<td style=\"color:#000080;border:#CCC solid;border-width:1px 1px 1px 0;\" align=\"center\">
						<b>".$echoOtherCur2Total."</b>
					</td>";
			$tdHeadCur2 = "<td style=\"color:#000080;border:#CCC solid;border-width:0 1px 0 0;\" align=\"center\">
					</td>";
		}
		
		$html.= "<tr align=\"left\" height=\"22\">
					<td colspan=\"2\" style=\"border:#CCC solid;border-width:0 1px 0 1px;\">
						&nbsp;Uang Muka Biaya Perjalanan Dinas
					</td>
					<td style=\"color:#000080;border:#CCC solid;border-width:0 1px 0 0;\" align=\"center\">
						".$echoIdrDp."
					</td>
					<td style=\"color:#000080;border:#CCC solid;border-width:0 1px 0 0;\" align=\"center\">
						".$echoUsdDp."
					</td>
					".$tdHeadCur1.$tdHeadCur2."
				</tr>
				<tr align=\"left\" height=\"22\">
					<td colspan=\"2\" style=\"border:#CCC solid;border-width:0 1px 0 1px;\">
						&nbsp;Total Pengeluaran Perjalanan Dinas
					</td>
					<td style=\"color:#000080;border:#CCC solid;border-width:0 1px 0 0;\" align=\"center\">
						".$echoIdrTotal."
					</td>
					<td style=\"color:#000080;border:#CCC solid;border-width:0 1px 0 0;\" align=\"center\">
						".$echoUsdTotal."
					</td>
					<td style=\"color:#000080;border:#CCC solid;border-width:0 1px 0 0;\" align=\"center\">
						".$echoOtherCur1Total."
					</td>
					<td style=\"color:#000080;border:#CCC solid;border-width:0 0px 0 0;\" align=\"center\">
						".$echoOtherCur2Total."
					</td>
				</tr>
				<tr align=\"left\" height=\"22\">
					<td colspan=\"2\" style=\"border:#CCC solid;border-width:1px 1px 1px 1px;\">
						&nbsp;<b>Total Akhir</b>
					</td>
					<td style=\"color:#000080;border:#CCC solid;border-width:1px 1px 1px 0;\" align=\"center\">
						<b>".$echoIdrKembali."</b>
					</td>
					<td style=\"color:#000080;border:#CCC solid;border-width:1px 1px 1px 0;\" align=\"center\">
						<b>".$echoUsdKembali."</b>
					</td>
					".$tdCur1TotalAkhir.$tdCur2TotalAkhir."

				</tr>
				<tr><td height=\"10\" colspan=\"2\"></td></tr>";
				
		if($this->detilReport($reportId, "status") != "Revise")
		{	
			if($ack != 00000)
			{		
				$html.= "<tr height=\"18\" align=\"left\">
							<td width=\"16%\">
								Diketahui Oleh
							</td>
							<td colspan=\"3\" style=\"color:#000080;\">";
							
				$ackName = "Kadiv / CEO";
				if($ack != 00000)
				{
					$ackName = $this->detilLoginSpjByEmpno($ack, "userfullnm", $db);
				}
				$html.= "		".$ackName."
							</td>
						</tr>
						<tr height=\"18\" align=\"left\">
							<td>
								Status
							</td>
							<td style=\"color:#000080;\">";
								$statAck= "<i>Waiting . . .</i>";
								if($ack != 00000)
								{
									$statAck = "<i>ACKNOWLEDGED</i>";
								}						
								
				$html.= "		".$statAck."
							</td>
						</tr>
						<tr><td height=\"5\" style=\"border-bottom:solid 1px #CCC;\"></td><td></td></tr>
						<tr><td height=\"2\" colspan=\"2\"</td></tr>
						<tr align=\"left\" height=\"18\" >
							<td>
								Diperiksa Oleh
							</td>
							<td colspan=\"3\" style=\"color:#000080;\">";
						
				$exmName = "HRGA Dept";
				if($exm != 00000)
				{
					$exmName = $this->detilLoginSpjByEmpno($exm, "userfullnm", $db);
				}
				$html.= "		".$exmName."
							</td>
						</tr>
						<tr align=\"left\" height=\"18\" >
							<td>
								Status
							</td>
							<td style=\"color:#000080;\">";
								$statExm= "<i>Waiting . . .</i>";
								if($exm != 00000)
								{
									$statExm = "<i>CHECKED</i>";
								}						
								
				$html.= "		".$statExm."
							</td>
						</tr>
						<tr><td height=\"5\" style=\"border-bottom:solid 1px #CCC;\"></td><td></td></tr>
						<tr><td height=\"2\" colspan=\"2\"</td></tr>
						<!--<tr align=\"left\" height=\"18\" >
							<td>
								Diproses Oleh
							</td>
							<td style=\"color:#000080;\" colspan=\"3\">-->";
						
				/*$prcsName = "Finance & Accounting Dept";
				if($prcs != 00000)
				{
					$prcsName = $this->detilLoginSpjByEmpno($prcs, "userfullnm", $db);
				}
				$html.= "		".$prcsName."
							</td>
						</tr>
						<tr align=\"left\" height=\"18\" >
							<td>
								Status
							</td>
							<td style=\"color:#000080;\">";
								$statPrcs= "<i>Waiting . . .</i>";
								if($prcs != 00000)
								{
									$statPrcs = "<i>PROCESSED</i>";
								}						
								
				$html.= "		".$statPrcs."
							</td>
						</tr>
						<tr><td height=\"5\" style=\"border-bottom:solid 1px #CCC;\"></td><td></td></tr>
						<tr><td height=\"2\" colspan=\"2\"</td></tr>";*/
			}
		}

		return $html;
	}
	
	function detilRevReport($reportId, $ket, $db)
	{
		$html.= '<tr height="17px">
					<td width="1%"></td>
					<td align="left" width="13%">Revise Request</td>
					<td align="left" width="86%" style="color:#000080;">'.ucwords(strtolower($this->detilLoginSpjByEmpno($this->detilReport($reportId, "reasonempno"), "userfullnm", $db))).'</td>
				</tr>
				<tr height="17px">
					<td></td>
					<td align="left">Revise Reason</td>
					<td align="left" style="color:#000080;">'.$this->detilReport($reportId, "reason").'</td>
				</tr>';
				
		return $html;
	}
	
	/*function emailKeAtasan($classNotif, $userEmpNo, $formId, $CPublic, $CEmployee, $CNotifSpj, $db, $link, $ket)// KADIV & CEO
	{
		$kadivId = $this->cariKadivId($userEmpNo, $CEmployee, $db);
		$ceoId = $this->idByUserJenis("userid", "CEO");
		
		$emailKadiv = $this->detilLoginSpj($kadivId, "useremail", $db)."@andhika.com";
		$emailCeo = $this->detilLoginSpj($ceoId, "useremail", $db)."@andhika.com";
		
		$userIdLogin = $this->detilLoginSpjByEmpno($userEmpNo, "userid", $db);
		if($userIdLogin == $kadivId)// jika jabatan user adalah kadiv, kirim notif ke CEO
		{
			if($this->detilLoginSpj($ceoId, "notifemail", $db) == "N")
			{
				if($classNotif != "emailNewReport" && $classNotif != "emailRevReport")//form
				{
					$CNotifSpj->$classNotif($this, $CPublic, $formId, $emailCeo, $db, $link, $ket);
					//email ke CEO
				}
				if($classNotif == "emailNewReport" || $classNotif == "emailRevReport")//report
				{
					$CNotifSpj->$classNotif($this, $CPublic, $CEmployee, $formId, $emailCeo, $db, $link, $ket);
				}
			}
		}
		if($userIdLogin != $kadivId)// jika jabatan user bukan kadiv, kirim notif ke Kadiv
		{
			if($this->detilLoginSpj($kadivId, "notifemail", $db) == "N")
			{
				if($classNotif != "emailNewReport" && $classNotif != "emailRevReport")//form
				{
					$CNotifSpj->$classNotif($this, $CPublic, $formId, $emailKadiv, $db, $link, $ket);//email ke Kadiv User
				}
				if($classNotif == "emailNewReport" || $classNotif == "emailRevReport")//report
				{
					$CNotifSpj->$classNotif($this, $CPublic, $CEmployee, $formId, $emailKadiv, $db, $link, $ket);
				}
			}
		}
	}*/
	
	function emailKeAtasan($classNotif, $aproverId, $userEmpNo, $formId, $CPublic, $CEmployee, $CNotifSpj, $db, $link, $ket)// KADIV & CEO
	{
		$emailAprvr = $this->detilLoginSpj($aproverId, "useremail", $db)."@andhika.com";
		$userIdLogin = $this->detilLoginSpjByEmpno($userEmpNo, "userid", $db);

		if($this->detilLoginSpj($aproverId, "notifemail", $db) == "N")
		{
			if($classNotif != "emailNewReport" && $classNotif != "emailRevReport")//form
			{
				$CNotifSpj->$classNotif($this, $CPublic, $formId, $emailAprvr, $db, $link, $ket);
			}
			if($classNotif == "emailNewReport" || $classNotif == "emailRevReport")//report
			{
				$CNotifSpj->$classNotif($this, $CPublic, $CEmployee, $formId, $emailAprvr, $db, $link, $ket);
			}
		}
	}
	
	function emailKeKadivHR($classNotif, $formId, $CPublic, $CEmployee, $CNotifSpj, $db, $link, $ket)
	{
		$kadivHrId = $this->detilLoginSpjByEmpno($CEmployee->detilDiv("050", "divhead"), "userid", $db);
		$emailKadivHR = $this->detilLoginSpj($kadivHrId, "useremail", $db)."@andhika.com";
		if($this->detilLoginSpj($kadivHrId, "notifemail", $db) == "N")
		{
			if($classNotif != "emailAckReport" && $classNotif != "emailRevReport")//form
			{
				$CNotifSpj->$classNotif($this, $CPublic, $formId, $emailKadivHR, $db, $link, $ket);// email ke kadiv HR
			}
			if($classNotif == "emailAckReport" || $classNotif == "emailRevReport")//report
			{
				$CNotifSpj->$classNotif($this, $CPublic, $CEmployee, $formId, $emailKadivHR, $db, $link, $ket);// email ke kadiv HR
			}
		}
	}
	
	function emailKeKadivFnc($classNotif, $formId, $CPublic, $CEmployee, $CNotifSpj, $db, $link, $ket)
	{
		$kadivFncId = $this->detilLoginSpjByEmpno($CEmployee->detilDiv("040", "divhead"), "userid", $db);
		$emailKadivFnc = $this->detilLoginSpj($kadivFncId, "useremail", $db)."@andhika.com";
		if($this->detilLoginSpj($kadivFncId, "notifemail", $db) == "N")
		{
			$CNotifSpj->$classNotif($this, $CPublic, $CEmployee, $formId, $emailKadivFnc, $db, $link, $ket);// email ke kadiv HR
		}
	}
	
	function emailKeAdmin($classNotif, $formId, $CPublic, $CEmployee, $CNotifSpj, $db, $link, $ket)
	{
		$query = $this->koneksi->mysqlQuery("SELECT userid FROM msuser WHERE userjenis = 'admin';");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$emailAdmin = $this->detilLoginSpj($row['userid'], "useremail", $db)."@andhika.com";
			/*if($this->detilLoginSpj($row['userid'], "notifemail", $db) == "N")
			{*/
				$CNotifSpj->$classNotif($this, $CPublic, $CEmployee, $formId, $emailAdmin, $db, $link, $ket);// email ke admin
			//}
		}
	}
	
	function emailKeOwner($classNotif, $formId, $CPublic, $CEmployee, $CNotifSpj, $db, $link, $ket)
	{
		if($classNotif != "emailAckReport" && $classNotif != "emailRevReport")//form
		{
			$ownerId = $this->detilForm($formId, "ownerid");
		}
		if($classNotif == "emailAckReport" || $classNotif == "emailRevReport")//report
		{
			$ownerId = $this->detilReport($formId, "ownerid");
		}
		$emailOwner = $this->detilLoginSpj($ownerId, "useremail", $db)."@andhika.com";
		
		/*if($this->detilLoginSpj($ownerId, "notifemail", $db) == "N")
		{*/
			if($classNotif != "emailAckReport" && $classNotif != "emailRevReport")//form
			{
				$CNotifSpj->$classNotif($this, $CPublic, $formId, $emailOwner, $db, $link, $ket);// email ke owner form
			}
			if($classNotif == "emailAckReport" || $classNotif == "emailRevReport")//report
			{
				$CNotifSpj->$classNotif($this, $CPublic, $CEmployee, $formId, $emailOwner, $db, $link, $ket);// email ke owner form
			}
		//}
	}
	
	/*function varNotifDesktop($userId, $koneksiOdbc, $koneksiOdbcId, $CPublic, $CLogin, $CNotifSpj, $notes, $db)
	{
		//$notesDt = $this->notesDt($CPublic->waktuSek());
		$notesDt = $CPublic->notesDt();
		$addUsrdt = $CPublic->userWhoActSqlServ();
		
		if($userId == "")// notif ke admin
		{	
			$query = $this->koneksi->mysqlQuery("SELECT userid FROM msuser WHERE userjenis = 'admin';");
			while($row = $this->koneksi->mysqlFetch($query))
			{
				$adminId = $row['userid'];
				//Notif Desktop ke admin
				if($CLogin->notification($db, "notifdesktop", $adminId) == "N")// jika notif desktop Y, kirim notif
				{
					$notesToAdmin = $this->detilLoginSpj($adminId, "empno", $db);
					$CNotifSpj->notifDesktop($koneksiOdbc, $koneksiOdbcId, $notesDt, $notes, $notesToAdmin, $addUsrdt);
				}
				//dibawah ini script untuk testing desktop notif. Comment script untuk kondisi normal.
				$notesToAdmin = $this->detilLoginSpj($adminId, "empno", $db);
				$CNotifSpj->notifDesktop($koneksiOdbc, $koneksiOdbcId, $notesDt, $notes, $notesToAdmin, $addUsrdt);
			}
		}
		if($userId != "")// notif ke user
		{
			if($CLogin->notification($db, "notifdesktop", $userId) == "N")// jika notif desktop Y, kirim notif
			{
				$notesToUsr = $this->detilLoginSpj($userId, "empno", $db);
				$CNotifSpj->notifDesktop($koneksiOdbc, $koneksiOdbcId, $notesDt, $notes, $notesToUsr, $addUsrdt);
			}
			//dibawah ini script untuk testing desktop notif. Comment script untuk kondisi normal.
			$notesToUsr = $this->detilLoginSpj($userId, "empno", $db);
			$CNotifSpj->notifDesktop($koneksiOdbc, $koneksiOdbcId, $notesDt, $notes, $notesToUsr, $addUsrdt);
		}
	}*/
	
	/*function desktopKeAtasan($userId, $koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notes, $db)// KADIV & CEO
	{
		$userEmpNo = $this->detilLoginSpj($userId, "empno", $db);
		$kadivId = $this->cariKadivId($userEmpNo, $CEmployee, $db);
		$ceoId = $this->idByUserJenis("userid", "CEO");
		$notesToCeo = $this->detilLoginSpj($ceoId, "empno", $db);
		
		$notesDt = $CPublic->notesDt();
		$addUsrdt = $CPublic->userWhoActSqlServ();
		
		if($userId == $kadivId)// jika jabatan user adalah kadiv, kirim notif ke CEO
		{
			if($this->detilLoginSpj($ceoId, "notifemail", $db) == "N")//kirim notif jika status notif Y
			{
				//$CNotifSpj->notifDesktop($koneksiOdbc, $koneksiOdbcId, $notesDt, $notes, $notesToCeo, $addUsrdt);
				$CNotifSpj->notifDesktop($koneksiOdbc, $koneksiOdbcId, $notesDt, $notesToCeo." ".$notes, $notesToCeo, $addUsrdt);
			}
		}
		if($userId != $kadivId)// jika jabatan user bukan kadiv, kirim notif ke Kadiv
		{
			if($this->detilLoginSpj($kadivId, "notifemail", $db) == "N")//kirim notif jika status notif Y
			{
				$notesToKadiv = $this->detilLoginSpj($kadivId, "empno", $db);
				//$CNotifSpj->notifDesktop($koneksiOdbc, $koneksiOdbcId, $notesDt, $notes, $notesToKadiv, $addUsrdt);
				$CNotifSpj->notifDesktop($koneksiOdbc, $koneksiOdbcId, $notesDt, $notesToKadiv." ".$notes, $notesToKadiv, $addUsrdt);
			}
		}
	}*/
	
	function desktopKeAtasan($userId, $aproverId, $koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notes, $db)// KADIV & CEO
	{
		$userEmpNo = $this->detilLoginSpj($userId, "empno", $db);
		$notesToAprover = $this->detilLoginSpj($aproverId, "empno", $db);
		
		$notesDt = $CPublic->notesDt();
		$addUsrdt = $CPublic->userWhoActSqlServ();
		
		if($this->detilLoginSpj($aproverId, "notifemail", $db) == "N")//kirim notif jika status notif Y
		{
			//$CNotifSpj->notifDesktop($koneksiOdbc, $koneksiOdbcId, $notesDt, $notes, $notesToCeo, $addUsrdt);
			$CNotifSpj->notifDesktop($koneksiOdbc, $koneksiOdbcId, $notesDt, $notesToAprover." ".$notes, $notesToAprover, $addUsrdt);
		}
	}
	
	function desktopKeKadivHR($koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notes, $db)
	{
		$notesDt = $CPublic->notesDt();
		$addUsrdt = $CPublic->userWhoActSqlServ();
		
		$notesToKadivHr = $CEmployee->detilDiv("050", "divhead");
		if($this->detilLoginSpjByEmpno($notesToKadivHr, "notifemail", $db) == "N")//kirim notif jika status notif Y
		{
			//$CNotifSpj->notifDesktop($koneksiOdbc, $koneksiOdbcId, $notesDt, $notes, $notesToKadivHr, $addUsrdt);
			$CNotifSpj->notifDesktop($koneksiOdbc, $koneksiOdbcId, $notesDt, $notesToKadivHr." ".$notes, $notesToKadivHr, $addUsrdt);
		}
	}
	
	function desktopKeKadivFnc($koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notes, $db)
	{
		$notesDt = $CPublic->notesDt();
		$addUsrdt = $CPublic->userWhoActSqlServ();
		
		$notesToKadivFnc = $CEmployee->detilDiv("040", "divhead");
		if($this->detilLoginSpjByEmpno($notesToKadivFnc, "notifemail", $db) == "N")//kirim notif jika status notif Y
		{
			//$CNotifSpj->notifDesktop($koneksiOdbc, $koneksiOdbcId, $notesDt, $notes, $notesToKadivFnc, $addUsrdt);
			$CNotifSpj->notifDesktop($koneksiOdbc, $koneksiOdbcId, $notesDt, $notesToKadivFnc." ".$notes, $notesToKadivHr, $addUsrdt);
		}
	}
	
	function desktopKeAdmin($koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notes, $db)
	{
		$notesDt = $CPublic->notesDt();
		$addUsrdt = $CPublic->userWhoActSqlServ();
		
		$query = $this->koneksi->mysqlQuery("SELECT userempno FROM msuser WHERE userjenis = 'admin';");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			//if($this->detilLoginSpjByEmpno($row['userempno'], "notifemail", $db) == "N")//kirim notif jika status notif Y
			//{
				$CNotifSpj->notifDesktop($koneksiOdbc, $koneksiOdbcId, $notesDt, $notes, $row['userempno'], $addUsrdt);
			// $CNotifSpj->notifDesktop($koneksiOdbc, $koneksiOdbcId, $notesDt, $row['userempno']." ".$notes, $row['userempno'], $addUsrdt);
			//}
		}
	}
	
	function desktopKeOwner($ownerEmpNo, $koneksiOdbc, $koneksiOdbcId, $CPublic, $CEmployee, $CNotifSpj, $notes, $db)
	{
		$notesDt = $CPublic->notesDt();
		$addUsrdt = $CPublic->userWhoActSqlServ();
		
		/*if($this->detilLoginSpjByEmpno($ownerEmpNo, "notifemail", $db) == "N")//kirim notif jika status notif Y
		{*/
			//$CNotifSpj->notifDesktop($koneksiOdbc, $koneksiOdbcId, $notesDt, $notes, $ownerEmpNo, $addUsrdt);
			$CNotifSpj->notifDesktop($koneksiOdbc, $koneksiOdbcId, $notesDt, $ownerEmpNo." ".$notes, $ownerEmpNo, $addUsrdt);
		//}
	}
	
	// REPORT CLASS ========================================================================================================
	function detilReport($reportId, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM report WHERE reportid = ".$reportId." AND deletests = 0");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function detilReportDetil($detilId, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM reportdetil WHERE detilid = ".$detilId." AND deletests = 0");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function urutanAkhirReport()
	{
		$query = $this->koneksi->mysqlQuery("SELECT urutan FROM report WHERE deletests = 0 ORDER BY urutan DESC LIMIT 1");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['urutan'];
	}
	
	function urutanAkhirReportDetail($reportId, $tglDetil)
	{
		$query = $this->koneksi->mysqlQuery("SELECT urutan FROM reportdetil WHERE reportid = ".$reportId." AND tgldetil = ".$tglDetil." AND deletests = 0 ORDER BY urutan DESC LIMIT 1");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['urutan'];
	}
	
	function fieldTunj($name, $i, $field, $valCur, $valCost, $valKet)
	{
		$mataUang = array("idr", "usd");
		$ket = "ket".$field;

		$html.= '<tr valign="top">
					<td height="28px" width="22%" align="right">
						<button type="button" class="spjBtnStandar" onclick="ajaxDinamicField(\'kurang\', $(\'#jml'.$name.'\').val(), \''.$name.'\', \'div'.$name.'\', \'jml'.$name.'\', \''.$i.'\')" style="width:30px;height:26px;border-radius:3px;" title="Delete field">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                            <tr>
                                <td align="left"><img src="../picture/cross.png" height="15px"/></td>
                            </tr>
                            </table>
                        </button>
					</td>
					<td width="10%" align="left">
						<select class="elementDefault" id="cur'.$field.'" name="cur'.$field.'" title="Mata uang" style="width:85px;">
							<option value="">--Select--</option>';
						/*for($i=0; $i<2; $i++)
						{	
							$sel = "";
							if($valCur == $mataUang[$i])
							{
								$sel = 'selected="selected"';
							}
							$html.= '<option value="'.$mataUang[$i].'" '.$sel.'>'.strtoupper($mataUang[$i]).'</option>';
						}*/
						$html.= $this->currency($valCur);
	 $html.= '			</select>
					</td>
					<td width="21%" align="left">
						<input type="text" class="elementDefault" id="cost'.$field.'" name="cost'.$field.'" style="width:89%;height:16px;text-align:right;" onFocus="setup(\'cost'.$field.'\');" onKeyUp="setup(\'cost'.$field.'\');" title="Jumlah" value="'.$valCost.'"/>
					</td>
					<td width="39%" align="right">
						<input type="text" class="elementDefault" id="ket'.$field.'" name="ket'.$field.'" style="width:94%;height:16px;" onFocus="setup1(\''.$ket.'\');" onKeyUp="setup1(\''.$ket.'\');" title="Keterangan" value="'.$valKet.'" />
					</td>
					<td width="8%"></td>
				</tr>';
					
		return $html;
	}
	
	function currency($valCur)
	{
		$query = $this->koneksi->mysqlQuery("SELECT * FROM currency WHERE active = 'Y' ORDER BY CASE currencyname WHEN 'IDR' THEN 1 WHEN 'USD' THEN 2 ELSE 3 END, currencyname ASC;");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$sel = "";
			if($valCur == $row['currencyid'])
			{
				$sel = 'selected="selected"';
			}
			$html.= '<option value="'.$row['currencyid'].'" '.$sel.'>'.$row['currencyname'].'</option>';
		}
		
		return $html;
	}
	
	function grandTotal($field, $reportId)
	{
		$query = $this->koneksi->mysqlQuery("SELECT SUM(".$field.") AS total FROM reportdetil WHERE reportid = ".$reportId." AND deletests = 0;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['total'];
	}
	
	function duplctdReport($formId)
	{
		$query = $this->koneksi->mysqlQuery("SELECT reportid FROM report WHERE formid = ".$formId." AND (status != 'Draft' AND status != 'Revise') AND deletests = 0;");
		$jml = $this->koneksi->mysqlNrows($query);
		
		return $jml;
	}
	
	function detilCmp($kdCmp, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM company WHERE kdcmp = ".$kdCmp." AND active = 'Y'");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function detilCurrency($currencyId, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM currency WHERE currencyid = ".$currencyId." AND active = 'Y';");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function otherCurHapusTidak($other1, $other2, $reportId)
	{
		if($other1 == 0.00)
		{
			$this->koneksi->mysqlQuery("UPDATE report SET othercur1 = 0 WHERE reportid = ".$reportId." AND deletests = 0;");
		}
		if($other2 == 0.00)
		{
			$this->koneksi->mysqlQuery("UPDATE report SET othercur2 = 0 WHERE reportid = ".$reportId." AND deletests = 0;");
		}
	}
	
// START -- PENGATURAN ROW Print Report ====================================================================================	
	function jmlTglSama($reportId, $tgl)
	{
		$query = $this->koneksi->mysqlQuery("SELECT detilid FROM reportdetil WHERE reportid = ".$reportId." AND tgldetil = ".$tgl." AND deletests = 0;");
		$jml = $this->koneksi->mysqlNRows($query);
		
		return $jml;
	}
	
	function urutanAwalTglSm($reportId, $tgl)
	{
		$query = $this->koneksi->mysqlQuery("SELECT urutan FROM reportdetil WHERE reportid = ".$reportId." AND tgldetil = ".$tgl." AND deletests = 0 ORDER BY urutan ASC LIMIT 1;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['urutan'];
	}
	
	function jmlLocSama($reportId, $location, $tgl)
	{
		$query = $this->koneksi->mysqlQuery("SELECT detilid FROM reportdetil WHERE reportid = ".$reportId." AND lokasi = '".$location."' AND tgldetil = ".$tgl." AND deletests = 0;");
		$jml = $this->koneksi->mysqlNRows($query);
		
		return $jml;
	}
	
	function urutanAwalLocSm($reportId, $location)
	{
		$query = $this->koneksi->mysqlQuery("SELECT urutan FROM reportdetil WHERE reportid = ".$reportId." AND lokasi = '".$location."' AND deletests = 0 ORDER BY urutan ASC LIMIT 1;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['urutan'];
	}
	
// END OF -- PENGATURAN ROW Print Report ====================================================================================

	function cekCustomAprv($userId)
	{
		$query = $this->koneksi->mysqlQuery("SELECT * FROM customapproval WHERE userid = ".$userId." AND aprove = 'Y'");
		$jml = $this->koneksi->mysqlNRows($query);
		
		return $jml;
	}
	
	function cariAproverId($userId, $CEmployee, $db)
	{
		if($userId == "00060" || $userId == "00112")
		{
			$aproverId = $userId;
		}
		else
		{
			$query = $this->koneksi->mysqlQuery("SELECT aprvid FROM customapproval WHERE userid = ".$userId.";");
			$row = $this->koneksi->mysqlFetch($query);
			
			$aproverId = $row['aprvid'];
			if($aproverId == "")
			{
				$userEmpNo = $this->detilLoginSpj($userId, "empno", $db);
				$aproverId = $this->detilLoginSpjByEmpno($CEmployee->detilDiv($CEmployee->detilTblEmpGen($userEmpNo, "kddiv"), "divhead"), "userid", $db);
				if($aproverId == $userId)
				{
					$aproverId = $this->idByUserJenis("userid", "CEO");
				}
			}
		}
		
		return $aproverId;
	}
	
	function tunjangan($gol, $cur)
	{
		//if($gol > 12 && $gol != 30)
		//{
		//	$gol = "013";
		//}
		
		$query = $this->koneksi->mysqlQuery("SELECT tunjanganid FROM goltunjangan WHERE gol = ".$gol.";");
		$row = $this->koneksi->mysqlFetch($query);
		
		$query1 = $this->koneksi->mysqlQuery("SELECT ".$cur." FROM tunjangan WHERE tunjanganid = ".$row['tunjanganid'].";");
		$row1 = $this->koneksi->mysqlFetch($query1);
		
		return $row1[$cur];
		
	}

	function tunjanganConsumtion($gol, $cur)
	{
		$selecNya = "";
		$totalNya = "0";

		if($gol > 12 && $gol != 30)
		{
			$gol = "013";
		}
		
		$query = $this->koneksi->mysqlQuery("SELECT tunjanganid FROM goltunjangan WHERE gol = ".$gol.";");
		$row = $this->koneksi->mysqlFetch($query);

		if($cur == "IDR")
		{
			$selecNya = "SUM( konsumsipagi_idr + konsumsisiang_idr + konsumsimalam_idr ) AS total";
		}

		if($cur == "USD")
		{
			$selecNya = "SUM( konsumsipagi_usd + konsumsisiang_usd + konsumsimalam_usd ) AS total";
		}
		
		if($selecNya != "")
		{
			$query1 = $this->koneksi->mysqlQuery("SELECT ".$selecNya." FROM tunjangan WHERE tunjanganid = ".$row['tunjanganid'].";");
			$row1 = $this->koneksi->mysqlFetch($query1);

			$totalNya = $row1["total"];
		}
		
		
		return $totalNya;
		
	}
	
// START -- CLASS REVISI =============================================================
	function detilReportRev($reportRevId, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM reportrevise WHERE reportreviseid = ".$reportRevId." AND deletests = 0");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function detilReportDetilRev($detilRevId, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM reportdetilrevise WHERE detilreviseid = ".$detilRevId." AND deletests = 0");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	//print
	function jmlTglSamaRev($reportReviseId, $tgl)
	{
		$query = $this->koneksi->mysqlQuery("SELECT detilreviseid FROM reportdetilrevise WHERE reportreviseid = ".$reportReviseId." AND tgldetil = ".$tgl." AND deletests = 0;");
		$jml = $this->koneksi->mysqlNRows($query);
		
		return $jml;
	}
	
	function urutanAwalTglSmRev($reportReviseId, $tgl)
	{
		$query = $this->koneksi->mysqlQuery("SELECT urutan FROM reportdetilrevise WHERE reportreviseid = ".$reportReviseId." AND tgldetil = ".$tgl." AND deletests = 0 ORDER BY urutan ASC LIMIT 1;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['urutan'];
	}
	
	function jmlLocSamaRev($reportReviseId, $location, $tgl)
	{
		$query = $this->koneksi->mysqlQuery("SELECT detilreviseid FROM reportdetilrevise WHERE reportreviseid = ".$reportReviseId." AND lokasi = '".$location."'AND tgldetil = ".$tgl." AND deletests = 0;");
		$jml = $this->koneksi->mysqlNRows($query);
		
		return $jml;
	}
	
	function urutanAwalLocSmRev($reportReviseId, $location)
	{
		$query = $this->koneksi->mysqlQuery("SELECT urutan FROM reportdetilrevise WHERE reportreviseid = ".$reportReviseId." AND lokasi = '".$location."' AND deletests = 0 ORDER BY urutan ASC LIMIT 1;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['urutan'];
	}
// END -- CLASS REVISI ===============================================================
		
// START -- SALIN DATA REVISI ==============================================================================================
	function salinReport($reportId, $userEmpNo, $CPublic, $reason)
	{
		$this->koneksi->mysqlQuery("INSERT into reportrevise(reportid, formid, ownerid, kadivid, tglrevise, tglreport, idrgrandtotal, usdgrandtotal, idrdp, usddp, idrtotalkembali, usdtotalkembali, othercur1grandtotal, othercur2grandtotal, othercur1, othercur2, status, ackempno, periksaempno, prosesempno, note, reason, reasonempno, addusrdt) SELECT reportid, formid, ownerid, kadivid, ".$CPublic->tglServer().", tglreport, idrgrandtotal, usdgrandtotal, idrdp, usddp, idrtotalkembali, usdtotalkembali, othercur1grandtotal, othercur2grandtotal, othercur1, othercur2, status, ackempno, periksaempno, prosesempno, note, '".$reason."', '".$userEmpNo."', '".$CPublic->userWhoAct()."' FROM report WHERE reportid = ".$reportId." AND deletests = 0;");
		$lastInsertId = mysql_insert_id();
		
		$query = $this->koneksi->mysqlQuery("SELECT detilid FROM reportdetil WHERE reportid = ".$reportId." AND deletests = 0;");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$this->koneksi->mysqlQuery("INSERT into reportdetilrevise (reportreviseid, detilid, reportid, urutan, tgldetil, lokasi, curtunj, costtunj, kettunj, curpjp, costpjp, ketpjp, curtrans, costtrans, kettrans, curakomd, costakomd, ketakomd, curkonsm, costkonsm, ketkonsm, curlain, costlain, ketlain, idrtotal, usdtotal, othercur1total, othercur2total, addusrdt) SELECT '".$lastInsertId."', detilid, reportid, urutan, tgldetil, lokasi, curtunj, costtunj, kettunj, curpjp, costpjp, ketpjp, curtrans, costtrans, kettrans, curakomd, costakomd, ketakomd, curkonsm, costkonsm, ketkonsm, curlain, costlain, ketlain, idrtotal, usdtotal, othercur1total, othercur2total, '".$CPublic->userWhoAct()."' FROM reportdetil WHERE detilid = ".$row['detilid']." AND deletests = 0;");
		}
	}
// END -- SALIN DATA REVISI ================================================================================================

	function dateAfterExtend($toDate = "",$extend = "")
	{
		$ed = substr($toDate,0,4)."-".substr($toDate,4,2)."-".substr($toDate,6,2);
		$returnED = date('Y-m-d', strtotime($ed.' + '.$extend.' days'));
		$endDate = str_replace("-","",$returnED);

		return $endDate;
	}
	
	function synAbsen($reportIdGet = "")
	{
		$dateNow = date("Ymd");
		$timeNow = date("H:i");

		$sql = "SELECT * FROM form WHERE formid IN (SELECT formid FROM report WHERE reportid = '".$reportIdGet."')";
		$query = $this->koneksi->mysqlQuery($sql);
		$row = $this->koneksi->mysqlFetch($query);

		$formId = $row['formid'];
		$empNo = $row['ownerempno'];
		$dateFrom = $row['datefrom'];
		$dateTo = $row['dateto'];
		$extend = $row['extend'];
		$remark = $row['necessary'];
		$usrAdd = $dateNow."#".$timeNow."#SPJ";
		$spjNo = $row['spjno'];
		
		$remark = $spjNo;
		
		$startDate = substr($dateFrom,0,4)."-".substr($dateFrom,4,2)."-".substr($dateFrom,6,2);
		$endDate = substr($dateTo,0,4)."-".substr($dateTo,4,2)."-".substr($dateTo,6,2);
		
		if($extend > 0)//jika ada extend
		{
			$extendDate = date('Y-m-d', strtotime($endDate.' + '.$extend.' days'));
			$endDate = $extendDate;
		}
		$dayCount = $this->datediffNya($startDate,$endDate);
		for($lan = 1;$lan <= $dayCount;$lan++)
		{
			if($lan == 1)
			{
				$sd = $startDate;
			}else{
				// $sd = date('Y-m-d', strtotime($endDate.' + '.($lan - 1).' days'));
				$sd = date('Y-m-d', strtotime($startDate.' + '.($lan - 1).' days'));
			}
			$cekLbr = $this->cekHariLibur($sd);
			if($cekLbr == "")
			{
				$sqlCek = "SELECT * FROM tblabsence WHERE id = '".$empNo."' AND absdt = '".$sd."' ";
				$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sqlCek);
				$jmlh = $this->koneksiOdbc->odbcNRows($query);
				if($jmlh == 0)
				{
					$sqlIns = "INSERT INTO tblabsence(id,absdt,absin,absout,abssts,absremark,source,addusrdt) VALUES('".$empNo."','".$sd."','00:00','00:00','5','".$remark."','SPJ','".$usrAdd."')";
				}else{
					$sqlIns = "UPDATE tblabsence SET abssts = '5', absremark = '".$remark."', updusrdt = '".$usrAdd."' WHERE id = '".$empNo."' AND absdt = '".$sd."'";
				}
				$this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sqlIns);
			}
		}
		//print_r($dayCount);exit;
	}

	function cekHariLibur($sd = "")
	{
		$stCek = "";
		$datetime = DateTime::createFromFormat('Y-m-d', $sd);
		$day = $datetime->format('D');
		if($day!="Sun" && $day!="Sat")
		{
			$stC = $this->cekTglLibur($sd);
			if($stC == "ada")
			{
				$stCek = "ada";
			}else{
				$stCek = "";
			}			
		}else{
			$stCek = "ada";
		}
		return $stCek;
	}

	function cekTglLibur($dateNya = "")
	{
		$stCek = "";
		$ex = explode("-", $dateNya);
		$sql = " SELECT * FROM tblmsthrlibur WHERE tahun='".$ex[0]."' AND bulan='".$ex[1]."' AND tanggal = '".$ex[2]."' ";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		$jmlh = $this->koneksiOdbc->odbcNRows($query);

		if($jmlh > 0 ){ $stCek = "ada"; }
		return $stCek;
	}
	
	function datediffNya($start, $end, $period = "day")
	{
		//Start initialization
		$day = 0;
		$month = 0;
	 
		//create month array
		$month_array = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	 
		//convert date to int value
		$datestart = strtotime($start);
		$dateend = strtotime($end);
	 
		//extract month and year from startdate
		$month_start = strftime("%m", $datestart);
		$current_year = strftime("%y", $datestart);
	 
		//kurangi aja deh
		$diff = $dateend - $datestart;
	 
		//calculate day
		$date = $diff / (60 * 60 * 24);
		$day = $date;
	 
		//flag for start
		$awal = 1;
	 
		while($date > 0)
		{
			//start
			if($awal)
			{
				//set loop form month
				$loop = $month_start - 1;
				//remove flag
				$awal = 0;
			}
			//2nd.. loop
			else
			{
				$loop = 0;
			}
	 
			//loop for month
			for ($i = $loop; $i < 12; $i++)
			{
				//kabisat years
				if($current_year % 4 == 0 && $i == 1)
					$day_of_month = 29;
				else
					$day_of_month = $month_array[$i];
	 
				$date -= $day_of_month;
	 
				if($date <= 0)
				{
					if($date == 0)
						$month++;
					break;
				}
				$month++;
			}
	 
			$current_year++;
		}
	 
		//return value
		switch($period)
		{
			case "day"   : return $day; break;
			case "month" : return $month; break;
			case "year"  : return intval($month / 12); break;
		}
	}

	function userAdminToEmail()
	{	
		$usrAdmin = "";
		$sql = " SELECT * FROM msuser WHERE userjenis = 'admin' ";
		$query = $this->koneksi->mysqlQuery($sql);
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$ua = $this->detilLoginSpjByEmpno($row['userempno'], "useremail", "andhikaportal");
			if($usrAdmin == "")
			{
				$usrAdmin = $ua;
			}else{
				$usrAdmin .= ",".$ua;
			}
		}
		return $usrAdmin;
	}

	function grandTotalOther($field, $whereNya)
	{
		$query = $this->koneksi->mysqlQuery("SELECT SUM(".$field.") AS total FROM reportdetil ".$whereNya);
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['total'];
	}
	
	function getDivByEmpNo($empNo)
	{
		$dataOut = array();

		$sql = "SELECT b.empno,b.nama,d.nmcmp,d.cmpcode,e.nmdiv
				FROM tblMstEmp b  
				LEFT JOIN tblEmpGen c ON c.empno=b.empno AND c.eftivedt=(SELECT MAX(eftivedt) FROM tblempgen WHERE 
				empno=b.empno AND deletests=0) 
				LEFT JOIN tblMstcmp d ON d.kdcmp=c.kdcmp 
				LEFT JOIN tblMstdiv e ON e.kddiv=c.kddiv AND e.deletests=0 
				WHERE b.empno='".$empNo."'";

		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		$dataOut = $this->koneksiOdbc->odbcFetch($query);
		return $dataOut;
	}
	
	
	
	
	
	
}
?>