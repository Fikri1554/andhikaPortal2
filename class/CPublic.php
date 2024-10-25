<?php
class CPublic
{	
	function CPublic($koneksiMysql, $userInit, $userInitHr)
	{
		$this->koneksi = $koneksiMysql;
		$this->userInit = $userInit;
		$this->userInitHr = $userInitHr;
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
	
	function jikaParamSmDgNilai($param, $paramSama, $nilaiMaka1, $nilaiMaka2)
	{
		//ex: param = "on", nilai1 = "1", nilai2 = "0";
		//jika nilai $param sama dengan $paramSama maka $nilai=$nilaiMaka1, jika $param tidak sama dengan $paramSama maka $nilai=$nilaiMaka2
		$nilai = "";
		if($param == $paramSama)
		{
			$nilai = $nilaiMaka1; 
		}
		elseif($param != $paramSama)
		{
			$nilai = $nilaiMaka2;	
		}
		return $nilai;
		
	}
	
	function nilaiCentang($param)
	{
		if($param == 0)
		{
			$nilai = "uncentang";
		}
		if($param == 1)
		{
			$nilai = "centang";
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
	
	function ifSatuMakaCentang($param)
	{
		$nilai = "";
		if($param == 1 || $param == "1")
		{
			$nilai = "&radic;";
		}
		return $nilai;
	}
	
	function ifSatuMakaIcon($param, $img) // DITAMBAHKAN PADA 19/04/2015
	{
		$nilai = "";
		if($param == 1 || $param == "1")
		{
			$nilai = $img;
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
	
	function datesRange($tglAwal, $tglAkhir) //format YYYY/MM/DD
	{ 
		$tglDate1 = substr($tglAwal,8,2);
		$blnDate1 = substr($tglAwal,5,2);
		$thnDate1 = substr($tglAwal,0,4);
		
		$tglDate2 = substr($tglAkhir,8,2);
		$blnDate2 = substr($tglAkhir,5,2);
		$thnDate2 = substr($tglAkhir,0,4);
		
	   $mkTime1 = mktime(0,0,0,$blnDate1,$tglDate1,$thnDate1);
	   $mkTime2 = mktime(0,0,0,$blnDate2,$tglDate2,$thnDate2);
	   
	   $mkTime = $mkTime2 - $mkTime1;
	   $nilai = ($mkTime /  86400);//nilai jarak hari
	   
	   return round($nilai,0); 
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
	
	function convTglDBMssql($tglPost)
	{
		return date("m/d/Y", strtotime($tglPost)); 
	}
	
	
	function convTglNonDBMonthName($tglPost)
	{
		$tgl = substr($tglPost,8,2);
		$bln = substr($tglPost,5,2);
		$thn = substr($tglPost,0,4);
		
		return $tgl." ".ucfirst(strtolower($this->detilBulanNamaAngka($bln, "ind")))." ".$thn;
	}
	
	function convTglNonDBtoBatchno($tglPost)
	{
		$tgl = substr($tglPost,0,2);
		$bln = substr($tglPost,3,2);
		$thn = substr($tglPost,6,4);
		
		return $thn.$bln.$tgl;
	}
	
	function convBatchnoToTglNonDB($tglPost)
	{
		$tgl = substr($tglPost,6,2);
		$bln = substr($tglPost,4,2);
		$thn = substr($tglPost,0,4);
		
		return $tgl."/".$bln."/".$thn;
	}
	
	function convTglNonDBTitik($tglPost)
	{
		$tgl = substr($tglPost,8,2);
		$bln = substr($tglPost,5,2);
		$thn = substr($tglPost,0,4);
		
		return $tgl.".".$bln.".".$thn;
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
	
	function tglJamServer()
	{
		$query = $this->koneksi->mysqlQuery("SELECT CONCAT(CURDATE(),' ',CURTIME()) as waktu");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['waktu'];
	}
	
	function tglServerWithStrip()
	{
		$query = $this->koneksi->mysqlQuery("SELECT CURDATE() as waktu");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['waktu'];
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
	
	function userWhoActSqlServ() // 20150512
	{
		$userInitHr = strtoupper(substr($this->userInitHr,0,3));
		$waktu =  substr($this->waktuSek(),11,5);
		$tgl =  substr($this->waktuSek(),8,2);
		$bln =  substr($this->waktuSek(),5,2);
		$thn =  substr($this->waktuSek(),0,4);
		
		$userWhoAct = $userInitHr."#".$waktu."#".$tgl."/".$bln."/".$thn;
		return $userWhoAct;
	}
	
	function notesDt() // 20150513
	{
		$tgl =  substr($this->waktuSek(),8,2);
		$bln =  substr($this->waktuSek(),5,2);
		$thn =  substr($this->waktuSek(),0,4);
		$waktu =  substr($this->waktuSek(),11,8);
		
		return	$bln."/".$tgl."/".$thn." ".$waktu;
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
			$arrayBulan = array("JAN", "FEB", "MAR", "APR", "MEI", "JUN", "JUL", "AGUS", "SEP", "OKT", "NOV", "DES");
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
	
	function nmHariSek()
	{
		$query = $this->koneksi->mysqlQuery("SELECT DAYNAME(NOW()) AS namahari;");
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
	
	function potongChar($teks, $limit)
	{
		$pjgKar = strlen($teks);
		
		if($pjgKar <= $limit)
		{
			$isi = $teks;
		}
		elseif($pjgKar > $limit)
		{
			$isi = substr($teks,0,$limit);
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
	  return "<li><a href=\"archives/\" title=\"Please Login\"><span class=\"".$on."\">Login</span></a></li>";
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
  
  function jikaKosongStrip($param = "")
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
	
	function ms_escape_string($data) 
	{
        if ( !isset($data) or empty($data) ) return '';
        if ( is_numeric($data) ) return $data;

        $non_displayables = array(
            '/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
            '/%1[0-9a-f]/',             // url encoded 16-31
            '/[\x00-\x08]/',            // 00-08
            '/\x0b/',                   // 11
            '/\x0c/',                   // 12
            '/[\x0e-\x1f]/'             // 14-31
        );
        foreach ( $non_displayables as $regex )
            $data = preg_replace( $regex, '', $data );
        $data = str_replace("'", "''", $data );
        return $data;
    }
    //add by tukang_batu ===========================================================
	function btnPlannMaintenance()
  	{
	  return "&nbsp;
			<button id =\"idPlannMaintenance\" class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formPlannMaintenance.submit();\" style=\"width:80px;height:50px;display:none;\" title=\"Planned Maintenance\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../picture/Application-Side-List-32.png\" height=\"18\"/> </td> 
				</tr>
				<tr>
					<td align=\"center\" style=\"font-size:9px;font-weight: bold;\">PLANNED MAINTENANCE</td>
				</tr>
				</table>
			</button>";
  	}

  	function btnDeficiency()
  	{
	  return "&nbsp;
			<button id =\"idBtnDeficiency\" class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formDeficiency.submit();\" style=\"width:80px;height:50px;\" title=\"Deficiency\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../picture/Application-Side-Boxes-32.png\" height=\"18\"/> </td> 
				</tr>
				<tr>
					<td align=\"center\" style=\"font-size:9px;font-weight: bold;\">DEFICIENCY</td>
				</tr>
				</table>
			</button>";
  	}

  	function btnSurveyStatus()
  	{
	  return "&nbsp;
			<button id =\"idBtnSurveyStatus\" class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formSurveyStatus.submit();\" style=\"width:90px;height:50px;\" title=\"Survey Status\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../picture/Application-View-List-32.png\" height=\"18\"/> </td> 
				</tr>
				<tr>
					<td align=\"center\" style=\"font-size:9px;font-weight: bold;\">SURVEY STATUS</td>
				</tr>
				</table>
			</button>";
  	}

  	function btnNavUploadFile()
  	{
	  return "&nbsp;
			<button id =\"btnUploadFileExcel\" class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formUploadFileExcel.submit();\" style=\"width:90px;height:50px;display:none;\" title=\"Upload File\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../picture/Outbox-blue-32.png\" height=\"18\"/> </td> 
				</tr>
				<tr>
					<td align=\"center\" style=\"font-size:9px;font-weight: bold;\">PERFORMANCE REPORT</td>
				</tr>
				</table>
			</button>";
  	}

  	function btnUploadFileNoon()
  	{
	  return "&nbsp;
			<button id =\"idBtnUploadFileNoon\" class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formUploadExcelNoon.submit();\" style=\"width:90px;height:50px;\" title=\"Upload File Noon\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../picture/Application-View-List-32.png\" height=\"18\"/> </td> 
				</tr>
				<tr>
					<td align=\"center\" style=\"font-size:9px;font-weight: bold;\">Upload Noon</td>
				</tr>
				</table>
			</button>";
  	}
  	function btnUploadFileOil()
  	{
	  return "&nbsp;
			<button id =\"idBtnUploadFileOil\" class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formUploadExcelOil.submit();\" style=\"width:90px;height:50px;\" title=\"Upload Master Cable\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../picture/Application-View-List-32.png\" height=\"18\"/> </td> 
				</tr>
				<tr>
					<td align=\"center\" style=\"font-size:9px;font-weight: bold;\">Upload Master Cable</td>
				</tr>
				</table>
			</button>";
  	}
  	function btnSummaryConsAndSpeed()
  	{
	  return "&nbsp;
			<button id =\"idBtnUploadFileNoon\" class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formUploadSummaryConsSpeed.submit();\" style=\"width:90px;height:50px;\" title=\"Summary Cons and Speed\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../picture/Application-View-List-32.png\" height=\"18\"/> </td> 
				</tr>
				<tr>
					<td align=\"center\" style=\"font-size:9px;font-weight: bold;\">Summary Cons and Speed</td>
				</tr>
				</table>
			</button>";
  	}
  	function btnSummaryCargoTrace()
  	{
	  return "&nbsp;
			<button id =\"idBtnUploadFileNoon\" class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formUploadSummaryCargoTrace.submit();\" style=\"width:90px;height:50px;\" title=\"Summary Cargo Trace\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../picture/Application-View-List-32.png\" height=\"18\"/> </td> 
				</tr>
				<tr>
					<td align=\"center\" style=\"font-size:9px;font-weight: bold;\">Summary Cargo Trace</td>
				</tr>
				</table>
			</button>";
  	}

  	function btnAllCompJob()
  	{
	  return "&nbsp;
			<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formAllCompJob.submit();\" style=\"width:100px;height:50px;\" title=\"All Comp & Job\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../picture/Report-32.png\" height=\"18\"/> </td> 
				</tr>
				<tr>
					<td align=\"center\" style=\"font-size:9px;\">All Comp & Job</td>
				</tr>
				</table>
			</button>";
  	}

  	function btnMonthlyMaintenanceForecast()
  	{
	  return "&nbsp;
			<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formMaintenanceForecastReport.submit();\" style=\"width:120px;height:50px;\" title=\"Monthly Maintenance Forecast\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../picture/Report-32.png\" height=\"18\"/> </td> 
				</tr>
				<tr>
					<td align=\"center\" style=\"font-size:9px;\">Monthly Maintenance Forecast</td>
				</tr>
				</table>
			</button>";
  	}

  	function btnDueOverDue()
  	{
	  return "&nbsp;
			<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formWorkListReport.submit();\" style=\"width:100px;height:50px;\" title=\"Work List for the Month\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../picture/Report-32.png\" height=\"18\"/> </td> 
				</tr>
				<tr>
					<td align=\"center\" style=\"font-size:9px;\">Work List for the Month</td>
				</tr>
				</table>
			</button>";
  	}

  	function btnUpdateJobsDone()
  	{
	  return "&nbsp;
			<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formUpdateJobDone.submit();\" style=\"width:100px;height:50px;\" title=\"Update Jobs Done\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../picture/Report-32.png\" height=\"18\"/> </td> 
				</tr>
				<tr>
					<td align=\"center\" style=\"font-size:9px;\">Update Jobs Done</td>
				</tr>
				</table>
			</button>";
  	}

  	function btnMonthCompRunningHours()
  	{
	  return "&nbsp;
			<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formmonthCompRunningHours.submit();\" style=\"width:100px;height:50px;\" title=\"Monthly Comp Running Hours\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../picture/Report-32.png\" height=\"18\"/> </td> 
				</tr>
				<tr>
					<td align=\"center\" style=\"font-size:9px;\">Monthly Comp Running Hours</td>
				</tr>
				</table>
			</button>";
  	}

  	function btnMaintenanceReportForm()
  	{
	  return "&nbsp;
			<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formMaintenanceReport.submit();\" style=\"width:100px;height:50px;\" title=\"Maintenance Report Form\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../picture/Report-32.png\" height=\"18\"/> </td> 
				</tr>
				<tr>
					<td align=\"center\" style=\"font-size:9px;\">Maintenance Report Form</td>
				</tr>
				</table>
			</button>";
  	}

  	function btnRoutineJob()
  	{
	  return "&nbsp;
			<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formRoutinJob.submit();\" style=\"width:100px;height:50px;\" title=\"Routine Job\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../picture/Report-32.png\" height=\"18\"/> </td> 
				</tr>
				<tr>
					<td align=\"center\" style=\"font-size:9px;\">Routine Job</td>
				</tr>
				</table>
			</button>";
  	}

  	function btnCompMasterList()
  	{
	  return "&nbsp;
			<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formMasterCompJob.submit();\" style=\"width:100px;height:50px;\" title=\"Components Master List\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../picture/Report-32.png\" height=\"18\"/> </td> 
				</tr>
				<tr>
					<td align=\"center\" style=\"font-size:9px;\">Components Master List</td>
				</tr>
				</table>
			</button>";
  	}

  	function btnJobDescription()
  	{
	  return "&nbsp;
			<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formMasterJobDesc.submit();\" style=\"width:100px;height:50px;\" title=\"Job Description\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../picture/Report-32.png\" height=\"18\"/> </td> 
				</tr>
				<tr>
					<td align=\"center\" style=\"font-size:9px;\">Job Description</td>
				</tr>
				</table>
			</button>";
  	}

  	function btnWorkClassification()
  	{
	  return "&nbsp;
			<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formMasterWorkClass.submit();\" style=\"width:100px;height:50px;\" title=\"Work Classification\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../picture/Report-32.png\" height=\"18\"/> </td> 
				</tr>
				<tr>
					<td align=\"center\" style=\"font-size:9px;\">Work Classification</td>
				</tr>
				</table>
			</button>";
  	}

  	function btnMstVessel()
  	{
	  return "&nbsp;
			<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formMstVesselSurveyStatus.submit();\" style=\"width:100px;height:50px;\" title=\"Master Vessel\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
					<tr>
						<td align=\"center\"><img src=\"../picture/Report-32.png\" height=\"18\"/> </td> 
					</tr>
					<tr>
						<td align=\"center\" style=\"font-size:9px;\">Master Vessel</td>
					</tr>
				</table>
			</button>";
  	}

  	function btnMstPic()
  	{
	  return "&nbsp;
			<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formMstPicSurveyStatus.submit();\" style=\"width:100px;height:50px;\" title=\"Master PIC\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
					<tr>
						<td align=\"center\"><img src=\"../picture/Report-32.png\" height=\"18\"/> </td> 
					</tr>
					<tr>
						<td align=\"center\" style=\"font-size:9px;\">Master PIC</td>
					</tr>
				</table>
			</button>";
  	}

  	function btnMstCertificate()
  	{
	  return "&nbsp;
			<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formMstCertSurveyStatus.submit();\" style=\"width:100px;height:50px;\" title=\"Master Certificate\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
					<tr>
						<td align=\"center\"><img src=\"../picture/Report-32.png\" height=\"18\"/> </td> 
					</tr>
					<tr>
						<td align=\"center\" style=\"font-size:9px;\">Master Certificate</td>
					</tr>
				</table>
			</button>";
  	}

  	function btnCertSurveyStatus()
  	{
	  return "&nbsp;
			<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formCertSurveyStatus.submit();\" style=\"width:100px;height:50px;\" title=\"Certificate Survey Status Certificate\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
					<tr>
						<td align=\"center\"><img src=\"../picture/Application-blue-32.png\" height=\"18\"/> </td> 
					</tr>
					<tr>
						<td align=\"center\" style=\"font-size:9px;\">Certificate</td>
					</tr>
				</table>
			</button>";
  	}

  	function btnSettingSurveyStatus()
  	{
	  return "&nbsp;
			<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formMasterWorkClass.submit();\" style=\"width:100px;height:50px;\" title=\"Setting Survey Status\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
					<tr>
						<td align=\"center\"><img src=\"../picture/Auction-blue-32.png\" height=\"18\"/> </td> 
					</tr>
					<tr>
						<td align=\"center\" style=\"font-size:9px;\">Setting</td>
					</tr>
				</table>
			</button>";
  	}

  	function btnReportSurveyStatus()
  	{
	  return "&nbsp;
			<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"formReportSurveyStatus.submit();\" style=\"width:100px;height:50px;\" title=\"Report Survey Status\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
					<tr>
						<td align=\"center\"><img src=\"../picture/Report-32.png\" height=\"18\"/> </td> 
					</tr>
					<tr>
						<td align=\"center\" style=\"font-size:9px;\">Report</td>
					</tr>
				</table>
			</button>";
  	}

  	function getLatLongViewMap()
	{
		$dataOut = array();
		$dateKemarin = date("Y-m-d", strtotime("- 1 day"));
		$data = $_POST;
		$vn = $data['vslName'];
		$vslName = explode(",", $vn);
		$html = "";
		
		for ($hal=0; $hal < count($vslName); $hal++)
		{
			$query = $this->koneksi->mysqlQuery("SELECT * FROM data_vessel where vessel LIKE '%".$vslName[$hal]."%' AND add_date >= '".$dateKemarin."' ORDER BY date_position ASC ");
			while($row = $this->koneksi->mysqlFetch($query))
			{
				if(strstr(strtolower($row['vessel']),strtolower($vslName[$hal])))
				{
					$dataOut[$vslName[$hal]]['vsl'] = $vslName[$hal];
					$dataOut[$vslName[$hal]]['lat'] = $row['latitude'];
					$dataOut[$vslName[$hal]]['longs'] = $row['longitude'];
					$dataOut[$vslName[$hal]]['datePosition'] = $this->convTglBlnSingkatWaktu($row['date_position']);
				}
			}
		}
		return $dataOut;
	}

	function btnHistorySO()
  	{
	  return "&nbsp;
			<button id =\"idBtnHistoryVessel\" class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"window.open('templates/pageHistoryVessel.php','_blank')\" style=\"width:80px;height:50px;\" title=\"History\">
				<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				<tr>
					<td align=\"center\"><img src=\"../picture/Application-Side-List-32.png\" height=\"18\"/> </td> 
				</tr>
				<tr>
					<td align=\"center\" style=\"font-size:9px;font-weight: bold;\">Vessel Tracking</td>
				</tr>
				</table>
			</button>";
  	}

  	function convTglBlnSingkatWaktu($dateTime)
	{
		$expDateTime = explode(" ", $dateTime);
		$expDateTime1 = explode("-", $expDateTime[0]);
		$expDateTime2 = $expDateTime[1];
		
		$tglDateTime = $expDateTime1[2];
		$blnDateTime = ucfirst( strtolower( $this->bulanSetengah( $expDateTime1[1], "ind") ) );
		$thnDateTime = $expDateTime1[0];
		
		return $tglDateTime." ".$blnDateTime." ".$thnDateTime." ".$expDateTime2;
	}

	function isWeekendNya($dateNya)
	{
		$dayNya = "";
		$dateConv = "";

		$timestamp = strtotime($dateNya);
		$weekday= date("l", $timestamp );
		$weekdayNya = strtolower($weekday);
		
		if ($weekdayNya == "saturday")
		{
			$dates = strtotime("-1 day", $timestamp);
			$da = date('Y-m-d', $dates);
			$ds = explode("-", $da);
			$dayNya = $ds[2];
		}
		else if ($weekdayNya == "sunday")
		{
			$dates = strtotime("+1 day", $timestamp);
			$da = date('Y-m-d', $dates);
			$ds = explode("-", $da);
			$dayNya = $ds[2];
		}
		 else {
		    $dateConv = $this->convTglNonDB($dateNya);
		}

		if($dayNya != "")
		{
			$dateConv = "(".$dayNya.") <label style=\"color:#F00;\">".$this->convTglNonDB($dateNya)."</label>";
		}

		return $dateConv;
	}



}
?>