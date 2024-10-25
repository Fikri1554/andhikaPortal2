<?php
class CDailyAct
{
	var $tabel;
	
	function CDailyAct($mysqlKoneksi)
	{
		$this->koneksi = $mysqlKoneksi;
		$tabel = "";
	}
	
	function detilAct($ide, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tblactivity WHERE idactivity=".$ide." AND deletests=0 LIMIT 1;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function detilActByDay($userId, $tanggal, $bulan, $tahun, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tblactivity WHERE ownerid='".$userId."' AND tanggal='".$this->zerofill($tanggal,2)."' AND bulan='".$this->zerofill($bulan,2)."' AND tahun='".$tahun."' AND deletests=0;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function detilActUrutan($userId, $tanggal, $bulan, $tahun, $limit, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tblactivity WHERE ownerid='".$userId."' AND tanggal='".$this->zerofill($tanggal,2)."' AND bulan='".$this->zerofill($bulan,2)."' AND tahun='".$tahun."' AND deletests=0 ORDER BY urutan ASC LIMIT ".$limit.",1;");
		$row = $this->koneksi->mysqlFetch($query);
		return $row[$field];
	}
	
	function maxUrutan($userId, $tanggal, $bulan, $tahun, $field)
	{
		$query = $this->koneksi->mysqlQuery("select max(idcontract) from tblcontract where idperson=a.idperson and deletests=0");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function zerofill($num, $zerofill = 5) 
	{ 
	    return str_pad($num, $zerofill, '0', STR_PAD_LEFT); 
	}
	
	function menuStatus($statusValue)
	{
		$html = "";
		
		$array = array("On Progress", "Postpone", "Finish");
		for($i=0; $i<count($array); $i++)
		{
			$sel = "";
			$strToLower = str_replace(" ","",strtolower($array[$i]));
			
			if($statusValue == $strToLower)
			{
				$sel = "selected=\"selected\"";
			}
			$html.= "<option value=\"".$strToLower."\" ".$sel.">".$array[$i]."</option>";
		}
		return $html;
	}
	
	function empJobList($CPublic, $userId, $tanggal, $bulan, $tahun)
	{
		$nilai = "";
		if($tanggal == "" && $bulan == "" && $tahun == "")
		{
			$nilai = "";
		}
		else
		{
			$query = $this->koneksi->mysqlquery("SELECT COUNT(idactivity) AS jumlah FROM tblactivity WHERE ownerid='".$userId."' AND tanggal='".$CPublic->zerofill($tanggal,2)."' AND bulan='".$CPublic->zerofill($bulan,2)."' AND tahun='".$tahun."' AND deletests=0;");
			$row = $this->koneksi->mysqlFetch($query);
			
			if($row['jumlah'] != 0)
			{
				$jmlUnread = $this->jmlJobUnread($CPublic, $userId, $tanggal, $bulan, $tahun);
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
	
	function jmlJobUnread($CPublic, $userId, $tanggal, $bulan, $tahun)
	{
		$query = $this->koneksi->mysqlquery("SELECT COUNT(idactivity) AS jumlah FROM tblactivity WHERE ownerid='".$userId."' AND bosreadjob='N' AND tanggal='".$CPublic->zerofill($tanggal,2)."' AND bulan='".$CPublic->zerofill($bulan,2)."' AND tahun='".$tahun."' AND deletests=0;");
		$row = $this->koneksi->mysqlFetch($query);	
		return $row['jumlah'];
	}
	
	function jmlJob($CPublic, $userId, $tanggal, $bulan, $tahun)
	{
		$query = $this->koneksi->mysqlquery("SELECT COUNT(idactivity) AS jumlah FROM tblactivity WHERE ownerid='".$userId."' AND tanggal='".$CPublic->zerofill($tanggal,2)."' AND bulan='".$CPublic->zerofill($bulan,2)."' AND tahun='".$tahun."' AND deletests=0;");
		$row = $this->koneksi->mysqlFetch($query);	
		return $row['jumlah'];
	}
	
	function detilRevisi($idRevisi, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tblrevisi WHERE idrevisi='".$idRevisi."' AND deletests=0 ORDER BY idrevisi DESC LIMIT 1 ");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function statusSpvComment($ownerId, $tgl, $bln, $thn)
	{
		$nilai = "noNew";
		$query = $this->koneksi->mysqlQuery("SELECT *, CASE WHEN spvcomment != '' AND spvcomment != oldspvcomment THEN 'NEW' ELSE '' END AS statspvcomment FROM tblactivity WHERE ownerid='".$ownerId."' AND tanggal='".$tgl."' AND bulan='".$bln."' AND tahun='".$thn."' AND deletests=0;");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			
			if($row['statspvcomment'] == "NEW") // jika terdapat spv comment baru maka
			{
				$nilai = "adaNew";
			}
		}
		return $nilai;
	}
	
	function statusSubComment($ownerId, $tgl, $bln, $thn)
	{
		$nilai = "noNew";
		$query = $this->koneksi->mysqlQuery("SELECT *, CASE WHEN responcomment != '' AND responcomment != oldresponcomment THEN 'NEW' ELSE '' END AS statresponcomment FROM tblactivity WHERE ownerid='".$ownerId."' AND tanggal='".$tgl."' AND bulan='".$bln."' AND tahun='".$thn."' AND deletests=0;");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			
			if($row['statresponcomment'] == "NEW") // jika terdapat spv comment baru maka
			{
				$nilai = "adaNew";
			}
		}
		return $nilai;
	}
	
	function statusActByPrefer($preferIde, $ownerId)
	{
		$arrReferId = "";
		$nilai = "belumFinish";
		$nilai = "sudahFinish";
		$query = $this->koneksi->mysqlQuery("SELECT status FROM tblactivity WHERE ownerid='".$ownerId."' AND referidactivity='".$preferIde."' AND deletests=0;");
		//SELECT status FROM tblactivity WHERE ownerid='00011' AND referidactivity='00000000014' AND deletests=0;
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$arrReferId.= $row['status'].", ";
		}
		
		$aaa = $arrReferId;
		
		return $aaa;
	}
	
	function cariReferIdAct($idActivity, $ownerId)
	{
		$query = $this->koneksi->mysqlQuery("SELECT referidactivity FROM tblactivity WHERE ownerid='".$ownerId."' AND referidactivity='".$idActivity."' AND deletests=0;");
		$jmlRow = $this->koneksi->mysqlNRows($query);
		
		return $jmlRow; 
	}
	
	function isiJobListCalender($CPublic, $userIdLogin, $tglAct, $blnAct, $thnAct)
	{
		$html = "";
		$urutan = 0;
		
		$query = $this->koneksi->mysqlQuery("SELECT activity FROM tblactivity WHERE tanggal='".$tglAct."' AND bulan='".$blnAct."' AND tahun='".$thnAct."' AND ownerid=".$userIdLogin." AND deletests=0 ORDER BY urutan ASC");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$urutan++;
			$rowColor = $CPublic->rowColor($urutan);
			
			$html.= "<tr bgcolor=".$rowColor.">";
			$html.= "<td height=20>".$urutan.".&nbsp;".$CPublic->potongKarakter( $CPublic->konversiQuotes($row['activity']), 75)."</td>";
			$html.= "</tr>";
		}
		
		return $html;
	}
	
	function btnPrevNext($urutan, $ownerId, $tanggal, $bulan, $tahun, $status)
	{
		if($status == "prev")
		{
			$ascDesc = "DESC";
			$operatorKurLeb = "<";
		}
		if($status == "next")
		{
			$ascDesc = "ASC";
			$operatorKurLeb = ">";
		}
		
		$query = $this->koneksi->mysqlQuery("SELECT idactivity FROM tblactivity WHERE tanggal='".$tanggal."' AND bulan='".$bulan."' AND tahun='".$tahun."' AND ownerid ='".$ownerId."' AND urutan ".$operatorKurLeb." ".$urutan." AND deletests=0 ORDER BY urutan ".$ascDesc." LIMIT 1");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['idactivity'];
	}
	
	function prevJob($idActivityPrev)
	{
		/*$btnPrev = "<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" style=\"width:55px;height:55px;\" title=\"Previous Activity\" onClick=\"parent.openThickboxWindow('".$idActivityPrev."','edit');\" type=\"button\">
			<table class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\" border=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../../picture/Arrow-Left-blue-32.png\" height=\"40\"/> </td> 
				</tr><tr><td>&nbsp;</td></tr>
			</table>
		</button>";*/
		$btnPrev = "<button class=\"btnStandarKecilPrevNext\" onMouseOver=\"this.className='btnStandarKecilPrevNextHover'\" onMouseOut=\"this.className='btnStandarKecilPrevNext'\" style=\"width:55px;height:55px;\" title=\"Previous Activity\" onClick=\"parent.openThickboxWindow('".$idActivityPrev."','edit');\" type=\"button\">
						<img src=\"../../picture/Arrow-Left-blue-32.png\" height=\"40\"/>
					</button>";
		if($idActivityPrev == "")
		{
			$btnPrev = "&nbsp;";
		}
		return $btnPrev;
	}
	
	function nextJob($idActivityNext)
	{
		/*$btnNext = "<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" style=\"width:55px;height:55px;\" title=\"Next Activity\" onClick=\"parent.openThickboxWindow('".$idActivityNext."','edit');\" type=\"button\">
			<table class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\" border=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../../picture/Arrow-Right-blue-32.png\" height=\"40\"/> </td> 
				</tr><tr><td>&nbsp;</td></tr>
			</table>
		 </button>";*/
		$btnNext = "<button class=\"btnStandarKecilPrevNext\" onMouseOver=\"this.className='btnStandarKecilPrevNextHover'\" onMouseOut=\"this.className='btnStandarKecilPrevNext'\" style=\"width:55px;height:55px;\" title=\"Next Activity\" onClick=\"parent.openThickboxWindow('".$idActivityNext."','edit');\" type=\"button\">
						<img src=\"../../picture/Arrow-Right-blue-32.png\" height=\"40\"/>
					 </button>";
		if($idActivityNext == "")
		{
			$btnNext = "&nbsp;";
		}
		return $btnNext;
	}
	
	function btnPrevNextMonth($urutan, $ownerId, $tanggal, $bulan, $tahun, $status)
	{
		if($status == "prev")
		{
			$ascDesc = "DESC";
			$operatorKurLeb = "<";
		}
		if($status == "next")
		{
			$ascDesc = "ASC";
			$operatorKurLeb = ">";
		}
		
		$query = $this->koneksi->mysqlQuery("SELECT idactivity FROM tblactivity WHERE tanggal='".$tanggal."' AND bulan='".$bulan."' AND tahun='".$tahun."' AND ownerid ='".$ownerId."' AND urutan ".$operatorKurLeb." ".$urutan." AND deletests=0 ORDER BY urutan ".$ascDesc." LIMIT 1");
		$row = $this->koneksi->mysqlFetch($query);
		$jmlRow = $this->koneksi->mysqlNRows($query);
		
		if($jmlRow > 0 )
		{
			return $row['idactivity'];
		}
		if($jmlRow < 1 ) 
		{
			$query1 = $this->koneksi->mysqlQuery("SELECT tanggal FROM tblactivity WHERE bulan='".$bulan."' AND tahun='".$tahun."' AND ownerid ='".$ownerId."' AND tanggal ".$operatorKurLeb." ".$tanggal." AND deletests=0 ORDER BY tanggal ".$ascDesc." LIMIT 1");
			$row1 = $this->koneksi->mysqlFetch($query1);
			
				$query2 = $this->koneksi->mysqlQuery("SELECT idactivity FROM tblactivity WHERE ownerid ='".$ownerId."' AND tanggal='".$row1['tanggal']."' AND bulan = '".$bulan."' AND tahun = '".$tahun."' AND deletests=0 ORDER BY urutan ".$ascDesc." LIMIT 1");
				$row2 = $this->koneksi->mysqlFetch($query2);
				
			return $row2['idactivity'];
		}
	}
	
	function prevJobMonth($idActivityPrev, $halamanGet)
	{
		/*$btnPrev = "<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" style=\"width:55px;height:55px;\" title=\"Previous Activity\" onClick=\"parent.openThickboxWindow('".$idActivityPrev."','edit');\" type=\"button\">
			<table class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\" border=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../../picture/Arrow-Left-blue-32.png\" height=\"40\"/> </td> 
				</tr><tr><td>&nbsp;</td></tr>
			</table>
		</button>";*/
		$btnPrev = "<button class=\"btnStandarKecilPrevNext\" onMouseOver=\"this.className='btnStandarKecilPrevNextHover'\" onMouseOut=\"this.className='btnStandarKecilPrevNext'\" style=\"width:55px;height:55px;\" title=\"Previous Activity\" onClick=\"parent.openThickboxWindow('".$idActivityPrev."','".$halamanGet."', '');\" type=\"button\">
						<img src=\"../../picture/Arrow-Left-blue-32.png\" height=\"40\"/>
					</button>";
		if($idActivityPrev == "")
		{
			$btnPrev = "&nbsp;";
		}
		return $btnPrev;
	}
	
	function nextJobMonth($idActivityNext, $halamanGet)
	{
		/*$btnNext = "<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" style=\"width:55px;height:55px;\" title=\"Next Activity\" onClick=\"parent.openThickboxWindow('".$idActivityNext."','edit');\" type=\"button\">
			<table class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\" border=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../../picture/Arrow-Right-blue-32.png\" height=\"40\"/> </td> 
				</tr><tr><td>&nbsp;</td></tr>
			</table>
		 </button>";*/
		$btnNext = "<button class=\"btnStandarKecilPrevNext\" onMouseOver=\"this.className='btnStandarKecilPrevNextHover'\" onMouseOut=\"this.className='btnStandarKecilPrevNext'\" style=\"width:55px;height:55px;\" title=\"Next Activity\" onClick=\"parent.openThickboxWindow('".$idActivityNext."','".$halamanGet."', '');\" type=\"button\">
						<img src=\"../../picture/Arrow-Right-blue-32.png\" height=\"40\"/>
					 </button>";
		if($idActivityNext == "")
		{
			$btnNext = "&nbsp;";
		}
		return $btnNext;
	}
}
?>