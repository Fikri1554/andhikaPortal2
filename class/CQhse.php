<?php
class CQhse
{
	function CQhse($mysqlkoneksi)
	{
		$this->koneksi = $mysqlkoneksi;
		//$this->cpublic = $public;
		$tabel = "";
	}
	
	function inboxNotif()
	{
		$unread = $this->unReadInbox("0000-00-00", "00000");
		$notif = "background-image:url(../../picture/qhse_notif.png);background-repeat:no-repeat;background-position:top right;";
		if($unread == 0)
		{
			$notif = "";
		}
		$html = "<button class=\"btnStandarKecil\" onclick=\"loadUrl('index.php?aksi=inbox'); return false;\" style=\"width:100px;height:50px;".$notif."\" title=\"Inbox Complaint\" id=\"inboxBtnId\">
          <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
            <tr>
              <td align=\"center\"><img src=\"../picture/Wallet-blue-32.png\" height=\"25\"/> </td> 
            </tr>
            <tr>
              <td align=\"center\">INBOX</td>
            </tr>
          </table>
      </button>";
	  
		return $html;
	}
	
	function unReadInbox($date, $ownerId)
	{
		$unread = "0";
		
		$substrDate = substr($date,8,2);
		$substrThnBln = substr($date,0,7);
		//echo $substrDate;
		
		$sqlThnBln = "";
		if($substrThnBln != "0000-00")
		{
			$sqlThnBln = "AND SUBSTR(date,1,7)='".$substrThnBln."'";
		}
		$sqlDate = "";
		if($substrDate != "00")
		{
			$sqlDate = "AND SUBSTR(date,9,2)='".$substrDate."'";
		}
		$sqlOwner = "";
		if($ownerId != "00000")
		{
			$sqlOwner = "AND ownerid = '".$ownerId."'";
		}
		
		$query = $this->koneksi->mysqlQuery("SELECT idkeluhan FROM tblkeluhan WHERE adminread = 'N' ".$sqlThnBln." ".$sqlDate." ".$sqlOwner." AND deletests=0");
		$jmlRow = $this->koneksi->mysqlNRows($query);
		if($jmlRow > 0)
		{
			$unread = $jmlRow;
		}
		return $unread;
	}
	
	function monthYear($CPublic, $aksi)
	{
		$nilai = "";
		$html.="<select class=\"elementMenu\" id=\"thnBln\" name=\"thnBln\" style=\"width:100px;\" title=\"Choose Card Year & Month Created\" onchange=\"ajaxGetDate(this.value, 'cariDate', 'ajaxCariDate');\">";
		$html.="<option value=\"0000-00\">-- ALL --</option>";
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT(SUBSTR(date,1,7)) AS thnbln FROM tblkeluhan ORDER BY thnbln DESC");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$dateDB = $row['thnbln'];
			$bulan = substr($dateDB,5,2);
			$tahun = substr($dateDB,0,4);
			$thnBln = $CPublic->bulanSetengah($bulan, "ind")." ".$tahun ;
			
			$blnSek = $CPublic->bulanServer();
			$thnSek = $CPublic->tahunServer();
			$thnBlnSek = $CPublic->bulanSetengah($blnSek, "ind")." ".$thnSek;
			
			$sel = "";
			if($thnBln == $thnBlnSek)
			{
				$sel = "selected=\"selected\"";
				$nilai = $dateDB;
			}
			$html.="<option value=\"".$dateDB."\" ".$sel.">".$thnBln."</option>";
			
		}
		$html.="</select>";
		
		if($aksi == "menu")
		{
			return $html;
		}
		else if($aksi == "nilai")
		{
			return $nilai;
		}
		
	}
	
	function dateCard($CPublic)
	{
		$blnSek = $CPublic->zerofill($CPublic->bulanServer(),2);
		$thnSek = $CPublic->tahunServer();
		$thnBlnSek = $thnSek."-".$blnSek;
		
		$html.="<select class=\"elementMenu\" id=\"dateCard\" name=\"dateCard\" style=\"width:100px;\" title=\"Choose Card Date Created\" onchange=\"ajaxGetOwner(this.value, 'cariOwner', 'ajaxCariOwner');\">";
		$html.="<option value=\"00\">-- ALL --</option>";
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT(SUBSTR(date,9,2)) AS tgl FROM tblkeluhan WHERE SUBSTR(date,1,7)='".$thnBlnSek."' AND deletests=0 ORDER BY tgl DESC");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$hari = $row['tgl'];
			$html.="<option value=\"".$hari."\">".$hari."</option>";
		}
		$html.="</select>";
		return $html;
	}
	
	function ownerCard()
	{
		$html.="<select class=\"elementMenu\" id=\"ownerId\" name=\"ownerId\" style=\"width:250px;\" title=\"Choose Name\">";
		$html.="<option value=\"00000\">-- ALL --</option>";
		$query = $this->koneksi->mysqlQuery("SELECT ownerid, ownername FROM tblkeluhan WHERE deletests=0 GROUP BY ownerid HAVING count(*)>0 ORDER BY ownername ASC");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$html.="<option value=\"".$row['ownerid']."\">".$row['ownername']."</option>";
		}
		$html.="</select>";
		return $html;
	}
	
	function printCard($idKeluhan, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tblkeluhan WHERE idkeluhan = '".$idKeluhan."' AND deletests=0");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function printMonthly($thnBln, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tblkeluhan WHERE SUBSTR(date,1,7)='".$thnBln."' AND deletests=0;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function btnMonthlyPrint()
	{
		$btn.="<button class=\"btnStandar\" id=\"btnMonthly\" onclick=\"klikBtnPrintMonthly();\" style=\"width:100px;height:29px;\" title=\"Print Monthly Summary Report\">
			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
			<tr>
				<td align=\"center\"><img src=\"../picture/Printer-blue-32.png\" height=\"20\"/> </td>
				<td align=\"center\">Monthly Print</td>
			</tr>
			</table>
		</button>";
		return $btn;
	}
	function judulAtas($CPublic, $thnBln)
	{
		$date = $this->printMonthly($thnBln,"date");
		$thnSub = substr($date,0,4);
		$blnSub = substr($date,5,2);
		$tglPr = ucfirst(strtolower($CPublic->bulanSetengah($blnSub, "eng")))." ".$thnSub;
		$judulAtas = "<tr>
						<td>
							<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
							<tr>
								<td><span class=\"style1\">MONTHLY SAFETY OBSERVATION LOG SHEET</span></td>
							</tr>
							</table>
						</td>
					</tr>
					<tr><td height=\"25\">&nbsp;</td></tr>
					 
					<tr>
						<td valign=\"top\">
							<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"style2\">
								<tr>
									<td width=\"4%\">&nbsp;Period</td>
									<td width=\"74%\">: ".$tglPr."</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>";
		return $judulAtas;
	}
	
	function footer()
	{
		$footer = "<tr>
		<td height=\"30\">&nbsp;</td>
	</tr>";
	
		return $footer; 
	}
}
?>