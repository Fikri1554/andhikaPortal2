<?php
class CInvReg
{
	function CInvReg($koneksiMysql, $koneksiOdbc, $koneksiOdbcId, $cPublic)
	{
		$this->koneksi = $koneksiMysql;
		$this->koneksiOdbc = $koneksiOdbc;
		$this->koneksiOdbcId = $koneksiOdbcId;
		$this->cPublic = $cPublic;
	}
	
	// ########## START INCOMING MAIL / INVOICE
	
	function menuBatchnoThnBln($aksi)
	{
		$tabel = "";
		if($this->cariBatchnoSamaDateSekarang() == "kosong")
		{
			$thnBlnSek = $this->cPublic->waktuServer("tahun").$this->cPublic->zerofill($this->cPublic->waktuServer("bulan"), 2);
			$tabel.= "<option value=\"".$thnBlnSek."\">".$thnBlnSek."</option>";
		}
		
		$i = 0;
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT(SUBSTR(batchno, 1, 6)) AS thnbln FROM mailinvoice WHERE deletests=0 ORDER BY batchno DESC", $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$i++;
			if($i == 1)
			{
// AMBIL NILAI MENU TAHUN BULAN YANG BERADA PALING ATAS UNTUK DIGUNAKAN PADA MENU TANGGAL KETIKA PERTAMA KALI MAIL INCOMING DIBUKA
				$thnBlnAwal = $row['thnbln'];
			}
			
			$tabel.="<option value=\"".$row['thnbln']."\">".$row['thnbln']."</option>";
		}
		
		if($aksi == "menu")
		{
			return $tabel;
		}
		else
		{
			return $thnBlnAwal;
		}
	}
	
	function menuBatchnoTgl($thnBln, $aksi)
	{
		$tabel = "";
		if($this->cariBatchnoSamaDateSekarang2($thnBln) == "kosong")
		{
			$tglSek = $this->cPublic->zerofill($this->cPublic->waktuServer("tanggal"), 2);
			$tabel.= "<option value=\"".$tglSek."\">".$tglSek."</option>";
		}

		$i = 0;
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT(SUBSTR(batchno, 7, 2)) AS tgl, SUBSTR(batchno, 1, 6) AS thnbln, batchno FROM mailinvoice WHERE SUBSTR(batchno, 1, 6)='".$thnBln."' AND deletests=0 ORDER BY tgl DESC", $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$i++;
			if($i == 1)
			{
// AMBIL NILAI MENU TANGGAL YANG BERADA PALING ATAS UNTUK DIGUNAKAN KETIKA PERTAMA KALI MAIL INCOMING DIBUKA
				$tglAwal = $row['tgl'];
				if($this->cariBatchnoSamaDateSekarang2($thnBln) == "kosong")
				{
					$tglAwal = $tglSek;
				}
			}
			$tabel.="<option value=\"".$row['tgl']."\">".$row['tgl']."</option>";
		}
		
		if($aksi == "menu")
		{
			return $tabel;
		}
		else if($aksi == "nilai")
		{
			return $tglAwal;
		}
	}
	
	function cariBatchnoSamaDateSekarang()// CARI BATCHNO YANG SAMA DENGAN TAHUN BULAN SEKARANG (TODAY)
	{
		$nilai = "kosong";
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT(batchno) FROM mailinvoice WHERE SUBSTR(batchno, 1, 6) = CONCAT(YEAR(NOW()), DATE_FORMAT(NOW(), '%m')) AND deletests=0;", $this->koneksi->bukaKoneksi());		
		$row = $this->koneksi->mysqlFetch($query);
		if($row['batchno'] != "")
		{
			$nilai = "ada";
		}
		return $nilai;
	}
	
	function cariBatchnoSamaDateSekarang2($thnBln) // CARI BATCHNO YANG SAMA DENGAN TANGGAL SEKARANG (TODAY)
	{
		$nilai = "kosong";
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT(batchno) FROM mailinvoice WHERE batchno = CONCAT(".$thnBln.",DATE_FORMAT(NOW(), '%d')) AND deletests=0;", $this->koneksi->bukaKoneksi());		
		$row = $this->koneksi->mysqlFetch($query);
		if($row['batchno'] != "")
		{
			$nilai = "ada";
		}
		return $nilai;
		
		
	}
	
	function detilSenderVendor($Acctcode, $field)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT ".$field." FROM AccountCode WHERE  SUBSTRING(Acctcode ,1,2) = '12' AND LEN(RTRIM(Acctcode))=5 AND Acctcode='".$Acctcode."'");
		$row = $this->koneksiOdbc->odbcFetch($query);
		return $row[$field];
	}
	
	function detilComp($compcode, $field)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT ".$field." FROM Company WHERE compcode='".$compcode."' AND noactive=0");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row[$field];
	}

	function detilUnitOld($department, $field)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT ".$field." FROM department WHERE department='".$department."'");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row[$field];
	}
	
	function detilUnit($idUnit, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM mstunit WHERE idunit='".$idUnit."'", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	} 
	function menuSenderVendor()
	{
		$i = 1;
		$tabel = "";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT Acctcode, AcctIndo FROM AccountCode WHERE SUBSTRING(Acctcode ,1,2) = '12' AND LEN(RTRIM(Acctcode))=5 ORDER BY AcctIndo ASC");
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$tabel.="<option value=\"".$row['Acctcode']."\">".$row['AcctIndo']."</option>";
		}
		return $tabel;
	}
	
	function menuSenderVendor2()
	{
		$i = 0;
		$tabel = "";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT Acctcode, RTRIM(AcctIndo) AS AcctIndo FROM AccountCode WHERE SUBSTRING(Acctcode ,1,2) = '12' AND LEN(RTRIM(Acctcode))=5 ORDER BY AcctIndo ASC");
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$i++;
			if($i == 1)
			{
				$tabel.="[".$row['Acctcode'].", \"".$row['AcctIndo']."\"]";
			}
			else
			{
				$tabel.=", [".$row['Acctcode'].", \"".$row['AcctIndo']."\"]";
			}
			
		}
		return $tabel;
	}
	
	function menuCmp()
	{
		$tabel = "";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT compcode, RTRIM(compname) as compname FROM Company WHERE noactive=0 ORDER BY compname ASC");
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$tabel.="<option value=\"".$row['compcode']."\">".strtoupper( $row['compname'] )."</option>";
		}
		return $tabel;
	}
	
	
	function menuUnit()
	{
		$tabel = "";
		$query = $this->koneksi->mysqlQuery("SELECT idunit, nmunit FROM mstunit ORDER BY nmunit ASC", $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$tabel.="<option value=\"".$row['idunit']."\">".$row['nmunit']."</option>";
		}
		
		return $tabel;
	}
	
	function menuCurrency($Currcode)
	{
		$tabel = "";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT * FROM currencytbl ORDER BY Currcode ASC");
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$sel = "";
			if($row['Currcode'] == $Currcode)
			{
				$sel = "selected=\"selected\"";
			}
			
			$tabel.="<option value=\"".$row['Currcode']."\" ".$sel.">".$row['Currcode']."</option>";
		}
		
		return $tabel;
	}
	
	function detilMailInvByBarcode($barcode,$field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM mailinvoice WHERE barcode='".$barcode."' AND deletests=0", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function maxUrutanMailInv($batchno)
	{
		$query = $this->koneksi->mysqlQuery("SELECT max(urutan) as urutan FROM mailinvoice WHERE batchno='".$batchno."' AND deletests=0", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['urutan'];
	}
	
	// ########## END INCOMING MAIL / INVOICE
}
?>