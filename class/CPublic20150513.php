<?php
class CPublic
{	
	function CPublic($koneksiMysql, $userInit)
	{
		$this->koneksi = $koneksiMysql;
		$this->userInit = $userInit;
	}
	
	function tabelDBKosong($nmTabel)
	{
		$query = $this->koneksi->mysqlQuery("SELECT * FROM ".$nmTabel.""); 
		$jmlRow = $this->koneksi->mysqlNRows($query);
		
		return $jmlRow;
	}
	
	function menuTahun($thnmulai, $thnPilih)
	{
		$tahun = date("Y");
		$html="";
		$html.= "<option value=\"\"></option>";
		for($i = $tahun; $i >= $thnmulai; $i--)
		{
			$sel = "";
			if($thnPilih == $i)
			{
				$sel = "selected=\"selected\"";
			}
			$html.= "<option value=\"".$i."\" ".$sel.">".$i."</option>";	
		}
		return $html;	
	}
	
	function jikaKosongMaka($param, $nilai1, $nilai2)
	{
		//ex: param = "hahaha", nilai1 = "", nilai2 = "&nbsp;";
		// maka jika param kosong maka nilai hanya param saja namun jika nilai param tidak sama dengan kosong maka nilai param +  &nbsp;
		$nilai = "";
		if($param == $nilai1)// jika param nya sama dengan yang ditentukan di nillai 1 maka berikan nilai param saja
		{
			$nilai = $param; 
		}
		elseif($param != $nilai1)// jika param nya tidak sama dengan yang ditentukan di nillai 1 maka berikan nilai param plus nilai 2
		{
			$nilai = $param.$nilai2;	
		}
		return $nilai;
	}
	
	function ifParamMakaReturn($param, $nilai1, $nilai2)
	{
		//ex: param = "hahaha", nilai1 = "", nilai2 = "&nbsp;";
		// maka jika param kosong maka nilai hanya param saja namun jika nilai param tidak sama dengan kosong maka nilai param +  &nbsp;
		$nilai = "";
		if($param == $nilai1)// jika param nya sama dengan yang ditentukan di nillai 1 maka berikan nilai param saja
		{
			$nilai = $param; 
		}
		elseif($param != $nilai1)// jika param nya tidak sama dengan yang ditentukan di nillai 1 maka berikan nilai param plus nilai 2
		{
			$nilai = $param.$nilai2;	
		}
		return $nilai;
	}
	
	function jikaParamSamaDenganNilai1($param, $nilai1, $nilai2)
	{
		//ex: param = "02/03/2012", nilai1 = "00/00/0000", nilai2 = "";
		//jika nilai $param sama dengan $nilai 1 maka berikan $nilai = $nilai2, namun jika $param tidak sama dengan 
		$nilai = "";
		if($param == $nilai1)
		{
			$nilai = $nilai2; 
		}
		elseif($param != $nilai1)
		{
			$nilai = $param;	
		}
		return $nilai;
		
	}
	
	function ifSatuMakaChecked($param)
	{
		$nilai = "";
		if($param == 1 || $param == "1")
		{
			$nilai = "checked=\"checked\"";
		}
		return $nilai;
	}
	
	function yesNo($param)
	{
		$nilai = "NO";
		if($param == "Y")
		{
			$nilai = "YES";
		}
		return $nilai;
	}
	
	function cariNilaiTglDB($tglPost, $field)
	{
		$nilai = "";
		if($field == "tanggal")
		{
			$nilai = substr($tglPost,8,2);
		}
		if($field == "bulan")
		{
			$nilai = substr($tglPost,5,2);
		}
		if($field == "tahun")
		{
			$nilai = substr($tglPost,0,4);
		}
		
		return $nilai;
	}
	
	function cariNilaiTglNonDB($tglPost, $field)
	{
		$nilai = "";
		if($field == "tanggal")
		{
			$nilai = substr($tglPost,0,2);
		}
		if($field == "bulan")
		{
			$nilai = substr($tglPost,3,2);
		}
		if($field == "tahun")
		{
			$nilai = substr($tglPost,6,4);
		}
		
		return $nilai;
	}
	
	function convTglDB($tglPost)
	{
		$tgl = substr($tglPost,0,2);
		$bln = substr($tglPost,3,2);
		$thn = substr($tglPost,6,4);
		
		return $thn."-".$bln."-".$tgl;
	}
	
	function convTglNonDB($tglPost)
	{
		$tgl = substr($tglPost,8,2);
		$bln = substr($tglPost,5,2);
		$thn = substr($tglPost,0,4);
		
		return $tgl."/".$bln."/".$thn;
	}
	
	function convTglNonDBMonthName($tglPost)
	{
		$tgl = substr($tglPost,8,2);
		$bln = substr($tglPost,5,2);
		$thn = substr($tglPost,0,4);
		
		return $tgl." ".ucfirst(strtolower($this->detilBulanNamaAngka($bln, "ind")))." ".$thn;
	}
	
	function convTglPO($tglPost)
	{
		$tgl = substr($tglPost,8,2);
		$bln = substr($tglPost,5,2);
		$thn = substr($tglPost,0,4);
		
		return $tgl."-".ucfirst(strtolower($this->bulanSetengah($bln, "ind")))."-".$thn;
	}
	
	function jamServer()
	{
		$query = $this->koneksi->mysqlQuery("SELECT CURTIME() as waktu");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['waktu'];
	}
	
	function tglServer()
	{
		$query = $this->koneksi->mysqlQuery("SELECT CURDATE() as waktu");
		$row = $this->koneksi->mysqlFetch($query);
		
		return str_replace("-","",$row['waktu']);
	}
	
	function bulanServer()
	{
		$query = $this->koneksi->mysqlQuery("SELECT MONTH(CURDATE()) as bulan");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['bulan'];
	}
	
	function tglDayServer()
	{
		$query = $this->koneksi->mysqlQuery("SELECT DAY(CURDATE()) as tanggal");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['tanggal'];
	}
	
	function tahunServer()
	{
		$query = $this->koneksi->mysqlQuery("SELECT YEAR(CURDATE()) as tanggal");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['tanggal'];
	}
	
	function tglTerakhirBlnSeb($dateFormatDB)
	{
		$query=$this->koneksi->mysqlQuery("SELECT LAST_DAY(DATE_ADD('".$dateFormatDB."', INTERVAL -(SELECT DAY(LAST_DAY('".$dateFormatDB."' )))DAY )) as tanggal;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['tanggal'];
	}
	
	function userWhoAct()
	{
		//$userInit = base64_decode($_SESSION['user_init']);
		$userWhoAct = $this->userInit."/".$this->tglServer()."/".$this->jamServer();
		return $userWhoAct;
	}
	
	function userWhoActNew($userIdLogin)
	{
		//$userInit = base64_decode($_SESSION['user_init']);
		$userWhoAct = $userIdLogin."/".$this->tglServer()."/".$this->jamServer();
		return $userWhoAct;
	}
	
	function zerofill($num, $zerofill = 5) 
	{ 
	    return str_pad($num, $zerofill, '0', STR_PAD_LEFT); 
	}
	
	function menuBulan($bulan) //PARAMETER BULAN DALAM BENTUK HURUF KECIL SEMUANYA
	{
		$html = "";
		
		$arrayBulan = array("JANUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE", "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER");
		for($i = 0; $i < count($arrayBulan); $i++)
		{
			$sel = "";
			if($bulan == strtolower(str_replace(" ", "", $arrayBulan[$i])))
			{
				$sel = "selected=\"selected\"";
			}
			$html.= "<option value=\"".strtolower(str_replace(" ", "", $arrayBulan[$i]))."\" ".$sel.">".$arrayBulan[$i]."</option>";
		}
		return $html;
	}
	
	function menuBulanNamaAngka($bulan, $bahasa) //PARAMETER BULAN DALAM BENTUK HURUF KECIL SEMUANYA
	{
		$html = "";
		
		$arrayBulanEng = array("JANUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE", "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER");
		$arrayBulanInd = array("JANUARI", "FEBRUARI", "MARET", "APRIL", "MEI", "JUNI", "JULI", "AGUSTUS", "SEPTEMBER", "OKTOBER", "NOVEMBER", "DESEMBER");
		
		if($bahasa == "eng")
		{
			$arrayBulan = $arrayBulanEng;
		}
		if($bahasa == "ind")
		{
			$arrayBulan = $arrayBulanInd;
		}
		
		for($i = 0; $i < count($arrayBulan); $i++)
		{
			$sel = "";
			if($bulan == $this->zerofill(($i+1), 2)."-".$arrayBulan[$i])
			{
				$sel = "selected=\"selected\"";
			}
			$html.= "<option value=\"".$this->zerofill(($i+1), 2)."-".$arrayBulanInd[$i]."\" ".$sel.">".$arrayBulan[$i]."</option>";
		}
		return $html;
	}
	
	function detilBulanNamaAngka($bulan, $bahasa) //PARAMETER BULAN DALAM BENTUK HURUF KECIL SEMUANYA
	{		
		$arrayBulanEng = array("JANUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE", "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER");
		$arrayBulanInd = array("JANUARI", "FEBRUARI", "MARET", "APRIL", "MEI", "JUNI", "JULI", "AGUSTUS", "SEPTEMBER", "OKTOBER", "NOVEMBER", "DESEMBER");
		
		if($bahasa == "eng")
		{
			$arrayBulan = $arrayBulanEng;
		}
		if($bahasa == "ind")
		{
			$arrayBulan = $arrayBulanInd;
		}
		
		for($i = 0; $i < count($arrayBulan); $i++)
		{
			$sel = "";
			if($bulan == ($i+1))
			{
				$nilai=$arrayBulan[$i];
			}
			
		}
		return $nilai;
	}
	
	function menuTahunDB($tahun)
	{
		$html = "";
		$query = $this->koneksi->mysqlQuery("SELECT tahun FROM tblyear ORDER BY tahun DESC"); 
		while($row = $this->koneksi->mysqlFetch($query)) 
		{ 
			$sel = "";
			if($tahun == $row['tahun'])
			{
				$sel = "selected=\"selected\"";
			}
			$html.= "<option value=\"".$row['tahun']."\" ".$sel.">".$row['tahun']."</option>";
		}
		return $html;
	}
	
	function menuTahunDB2($tahun)
	{
		$html = "";
		$html.= "<select class=\"elementMenu\" id=\"tahun\" name=\"tahun\" style=\"width:70px;height:29px;\" title=\"Choose Year\">";
		
		$query = $this->koneksi->mysqlQuery("SELECT tahun FROM tblyear ORDER BY tahun DESC");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$html.="<option value=\"".$row['tahun']."\">".$row['tahun']."</option>";
		}
		
		$html.= "</select>";
		
		return $html;
	}
	
	function bulanSetengah($bulan, $bahasa) //PARAMETER BULAN DALAM BENTUK HURUF KECIL SEMUANYA
	{
		if($bahasa == "eng")
		{
			$arrayBulan = array("JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC");
		}
		if($bahasa == "ind")
		{
			$arrayBulan = array("JAN", "FEB", "MAR", "APR", "MEI", "JUN", "JUL", "AGS", "SEP", "OKT", "NOV", "DES");
		}
		
		return $arrayBulan[$bulan-1];
	}
	
	function englishDate()
	{
		$queryMonth = $this->koneksi->mysqlQuery("SELECT DAY(CURDATE()) as hari, MONTH(CURDATE()) as bulan, YEAR(CURDATE()) as tahun;");
		$rowMonth = $this->koneksi->mysqlFetch($queryMonth);
		
		return $this->bulanSetengah($rowMonth['bulan'], "eng")." ".$rowMonth['hari'].", ".$rowMonth['tahun'];
	}
	
	function indonesiaDate()
	{
		$queryMonth = $this->koneksi->mysqlQuery("SELECT DAY(CURDATE()) as hari, MONTH(CURDATE()) as bulan, YEAR(CURDATE()) as tahun;");
		$rowMonth = $this->koneksi->mysqlFetch($queryMonth);
		
		return $rowMonth['hari']." ".$this->bulanSetengah($rowMonth['bulan'], "ind")." ".$rowMonth['tahun'];
	}
	
	function intervalTanggal($tglSekarang, $day)
	{
		$query = $this->koneksi->mysqlQuery("SELECT DATE_ADD('".$tglSekarang."', INTERVAL ".$day." DAY) AS hasil;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['hasil'];
	}
	
	function perbedaanHari($date1, $date2)
	{
		$query = $this->koneksi->mysqlQuery("SELECT DATEDIFF(".$date1.", ".$date2.") AS hasil;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['hasil'];
	}
	
	function perbedaanBulan($date1, $date2)
	{
		$query = $this->koneksi->mysqlQuery("SELECT TIMESTAMPDIFF(MONTH, ".$date1.", ".$date2.") AS hasil;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['hasil'];
	}
	
	function berapaHariKedepan($date, $interval)
	{
		$query = $this->koneksi->mysqlQuery("SELECT DATE_ADD(".$date.", INTERVAL ".$interval.") AS hasil;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['hasil'];
	}
	
	function lastDay($date)
	{
		$query = $this->koneksi->mysqlQuery("SELECT LAST_DAY(".$date.") AS hasil");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['hasil'];
	}
	
	function waktuSek()
	{
		$query = $this->koneksi->mysqlQuery("SELECT CURRENT_TIMESTAMP AS hasil;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['hasil'];
	}
	
	function nmHariServer($tanggal, $bulan, $tahun)
	{
		$query = $this->koneksi->mysqlQuery("SELECT DAYNAME('".$tahun."-".$bulan."-".$tanggal."') AS namahari;");
		
		$row = $this->koneksi->mysqlFetch($query);
		return $row['namahari'];
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
	
	function hariLibur($tanggal, $bulan, $tahun)
	{
		$query = $this->koneksi->mysqlQuery("SELECT tahun, bulan, tanggal, keterangan FROM tblmsthrlibur WHERE (tanggal = ".$tanggal.") AND (bulan = ".$bulan.") AND (tahun = ".$tahun.") AND (deletests = 0)");
		$jmlRow = $this->koneksi->mysqlNRows($query);

		return $jmlRow;
	}
	
	function ketHariLibur($tanggal, $bulan, $tahun)
	{
		$query = $this->koneksi->mysqlQuery("SELECT tahun, bulan, tanggal, keterangan FROM tblmsthrlibur WHERE (tahun = ".$tahun.") AND (bulan = ".$bulan.") AND (tanggal = ".$tanggal.") AND (deletests = 0)");
		$row = $this->koneksi->mysqlFetch($query);

		return $row['keterangan'];
	}
	
	function waktuServer($param)
	{
		$query = $this->koneksi->mysqlQuery("SELECT YEAR(NOW()) AS tahun, MONTH(NOW()) AS bulan, DAY(NOW()) AS tanggal, HOUR(NOW()) AS jam, MINUTE(NOW()) AS menit, SECOND(NOW()) AS detik;");
		$row = $this->koneksi->mysqlFetch($query);

		return $row[$param];
	}
	
	function dateTimeGabung()
	{
		$tahun = $this->waktuServer("tahun");
		$bulan = $this->zerofill($this->waktuServer("bulan"), 2);
		$tanggal = $this->zerofill($this->waktuServer("tanggal"), 2);
		$jam = $this->zerofill($this->waktuServer("jam"), 2);
		$menit = $this->zerofill($this->waktuServer("menit"), 2);
		$detik = $this->zerofill($this->waktuServer("detik"), 2);
		
		return $tahun.$bulan.$tanggal."-".$jam.$menit.$detik;
	}
	
	function convTglBlnSingkat($dateTime)
	{
		$expDateTime = explode(" ", $dateTime);
		$expDateTime1 = explode("-", $expDateTime[0]);
		$expDateTime2 = $expDateTime[1];
		
		$tglDateTime = $expDateTime1[2];
		$blnDateTime = ucfirst( strtolower( $this->bulanSetengah( $expDateTime1[1], "ind") ) );
		$thnDateTime = $expDateTime1[0];
		
		return $tglDateTime.", ".$blnDateTime." ".$thnDateTime." ".$expDateTime2;
	}
	
	function formatTglSuntechno($tglPost)//MAR.01,1973
	{
		$tgl = substr($tglPost,8,2);
		$bln = substr($tglPost,5,2);
		$thn = substr($tglPost,0,4);
		
		return $this->bulanSetengah($bln, "eng").".".$tgl.",".$thn;
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
	
	function cariMingguKe($date) // CARI MINGGU KE BERAPA DALAM BULAN YANG DIPILIH BUKAN TAHUN
	{
		//$query = $this->koneksi->mysqlQuery("SELECT WEEK('".$date."')+1 AS mingguke;");
		$query = $this->koneksi->mysqlQuery("SELECT WEEK('".$date."',5) - WEEK(DATE_SUB('".$date."', INTERVAL DAYOFMONTH('".$date."')-1 DAY),5)+1 AS mingguke;");
		
		$row = $this->koneksi->mysqlFetch($query);
		return $this->addOrdinalNumberSuffix($row['mingguke']);
		
	}
	
	function addOrdinalNumberSuffix($num) {
    if (!in_array(($num % 100),array(11,12,13))){
      switch ($num % 10) {
        // Handle 1st, 2nd, 3rd
        case 1:  return $num.'st';
        case 2:  return $num.'nd';
        case 3:  return $num.'rd';
      }
    }
    return $num.'th';
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
  
  function rowColorCustom($i, $warnaAwal, $warnaAkhir)
  {
	  if ($i % 2 != "0") # An odd row 
	  {
		  $rowColor = $warnaAwal; 
	  }
	  else # An even row 
	  {
		  $rowColor = $warnaAkhir;
	  }
	  return $rowColor;
  }
  
  function menuHome($on)
  {
	  return "<li><a href=\"/\" title=\"Home of Andhika Portal\"><span class=\"".$on."\">Home</span></a></li>";
  }
  
  function menuNews($on)
  {
	  //return "<li><a class=\"firLink\" href=\"../setting/\" title=\"Andhika Portal Admin Site\">Setting</a></li>";
	  return "<li><a href=\"../news/\" title=\"All About Andhika News\"><span class=\"".$on."\">News</span></a></li>";
  }
  
  function menuEmployee($on)
  {
	  return "<li><a href=\"../employee/\" title=\"Andhika Document\"><span class=\"".$on."\">Employee</span></a></li>";
  }
  
  function menuArchives($on)
  {
	  return "<li><a href=\"../archives/\" title=\"Folder And Daily Activity\"><span class=\"".$on."\">Archives</span></a></li>";
  }
  
  function menuSetting($on)
  {
	  //return "<li><a class=\"firLink\" href=\"../setting/\" title=\"Andhika Portal Admin Site\">Setting</a></li>";
	  return "<li><a href=\"../setting/\" title=\"Andhika Portal Admin Site\"><span class=\"".$on."\">Setting</span></a></li>";
  }
  
  function menuLogin($on)
  {
	  return "<li><a href=\"../archives/\" title=\"Please Login\"><span class=\"".$on."\">Login</span></a></li>";
  }
  
  function menuApplication($COtherApp, $userIdLogin)
  {
	  $onOff = "";
	  
	  //MENU YANG LAMA
	 /* return "<li ".$onOff.">
				  <div id=\"ddtopmenubar\" class=\"mattblackmenu\">
					  <a href=\"#\" rel=\"ddsubmenu1\" class=\"firLink\">Application</a>
					  <ul id=\"ddsubmenu1\" class=\"ddsubmenustyle\" style=\"width:150px;background-color: #dde4f3;top:-30;\">
					  ".$COtherApp->menuOtherApp($userIdLogin)."
					  </ul>
				  </div>
			  </li>";*/
			  
		return "<li class=\"has-sub\"><a href=\"#\"><span>Application</span></a>
					<ul>
						".$COtherApp->menuOtherApp($userIdLogin)."
					</ul>
				</li>";		  
  }
  
  function btnUser()
  {
	  return "&nbsp;
			<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formUser.submit();\" style=\"width:85px;height:50px;\" title=\"Andhika Portal User List\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../picture/Users-blue-48.png\" height=\"25\"/> </td> 
				</tr>
				<tr>
					<td align=\"center\">USERS</td>
				</tr>
				</table>
			</button>";
  }
  
  function btnLogHistory()
  {
	  return "&nbsp;
			  <button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formLoghistory.submit();\" style=\"width:85px;height:50px;\" title=\"User Action History\">
				  <table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				  <tr>
					  <td align=\"center\"><img src=\"../picture/Metro-Tasks-Blue-32.png\" height=\"25\"/> </td> 
				  </tr>
				  <tr>
					  <td align=\"center\">LOG HISTORY</td>
				  </tr>
				  </table>
			  </button>";
  }
  
  function btnCustomSub()
  {
	  return "&nbsp;
        <button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formSubCustom.submit();\" style=\"width:85px;height:50px;\" title=\"Adding Subordinate Custom\">
            <table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
            <tr>
                <td align=\"center\"><img src=\"../picture/Site-Map-blue-32.png\" height=\"25\"/> </td> 
            </tr>
            <tr>
                <td align=\"center\">CUSTOM SUB</td>
            </tr>
            </table>
        </button>";
  }
  
  function btnOtherApp()
  {
	  return "&nbsp;
        <button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formOtherApp.submit();\" style=\"width:85px;height:50px;\" title=\"Give Other Application Access\">
            <table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
            <tr>
                <td align=\"center\"><img src=\"../picture/Oscilloscope-blue-32.png\" height=\"25\"/> </td> 
            </tr>
            <tr>
                <td align=\"center\">OTHER APP</td>
            </tr>
            </table>
        </button>";
  }
  
  function btnKpiSetting()
  {
	  return " &nbsp;
        <button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formKpiSetting.submit();\" style=\"width:85px;height:50px;\" title=\"Seeting for KPI\">
            <table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
            <tr>
                <td align=\"center\"><img src=\"../picture/Key-blue-32.png\" height=\"25\"/> </td> 
            </tr>
            <tr>
                <td align=\"center\">KPI SETTING</td>
            </tr>
            </table>
        </button>";
  }
  
  function btnConvertFold()
  {
	  return " &nbsp;
        <button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formConvertFold.submit();\" style=\"width:110px;height:50px;\" title=\"Seeting for KPI\">
            <table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
            <tr>
                <td align=\"center\"><img src=\"../picture/doubleFolderBlue.png\" height=\"25\"/> </td> 
            </tr>
            <tr>
                <td align=\"center\">CONVERT FOLDER</td>
            </tr>
            </table>
        </button>";
  }
  
  function jikaKosongStrip($param)
  {
	  if($param == 0)
	  {
		  $nilai = "-&nbsp;";
	  }
	  elseif($param != 0)
	  {
		  $nilai = $param;
	  }
	  return $nilai;
  }
  
	function konversiQuotes($string) 
	{ 
		$search = array('"', "'"); 
		$replace = array("\&#34;", "\&#39;");
	
		return str_replace($search, $replace, $string); 
	}
	function konversiQuotes1($string) 
	{ 
		$search = array('"', "'"); 
		$replace = array("&#34;", "&#39;"); 
	
		return str_replace($search, $replace, $string); 
	}
	
	function konversiSymbol($string) 
	{ 
		$search = array("%", "'", "#", "&", "?"); 
		$replace = array("%25", "%27", "%23", "%26", "%3F"); 
	
		return str_replace($search, $replace, $string); 
	}
}
?>