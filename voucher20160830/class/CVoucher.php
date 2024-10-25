<?php
class CVoucher
{
	function CVoucher($koneksiMysql, $koneksiOdbc, $koneksiOdbcId, $CPublic)
	{
		$this->koneksi = $koneksiMysql;
		$this->koneksiOdbc = $koneksiOdbc;
		$this->koneksiOdbcId = $koneksiOdbcId;
		$this->CPublic = $CPublic;
	}
	
	function detilTblUserjns($userId, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tbluserjenis WHERE userid = '".$userId."';", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function detilVoucher($idVoucher, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tblvoucher WHERE idvoucher = '".$idVoucher."' AND deletests=0;", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function menuCurrFirst()
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT  TOP (1) Currcode, Currname FROM dbo.currencytbl");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row['Currcode'];
	}
	
	function menuCurrency($currCode)
	{
		$tabel = "";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT Currcode, Currname FROM currencytbl");
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$sel = "";
			if($row['Currcode'] == $currCode)
			{
				$sel = "selected";
			}
			$tabel.="<option value=\"".$row['Currcode']."\" ".$sel." title=\"&nbsp;".strtoupper($row['Currname'])."&nbsp;\">".$row['Currcode']."</option>";
		}
		
		return $tabel;
	}
	
	function menuCurrencyCode($currCode)
	{
		$tabel = "";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT Currcode, Currname FROM currencytbl");
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$sel = "";
			if($row['Currcode'] == $currCode)
			{
				$sel = "selected";
			}
			$tabel.="<option value=\"".$row['Currcode']."\" ".$sel.">".$row['Currcode']."</option>";
		}
		
		return $tabel;
	}
	
	function detilComp($compcode, $field)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT ".$field." FROM Company WHERE compcode='".$compcode."' AND noactive=0");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row[$field];
	}
	
	function menuCmp($compCode)
	{
		$tabel = "";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT compcode, RTRIM(compname) as compname FROM Company WHERE noactive=0 ORDER BY compname ASC");
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$sel = "";
			if($row['compcode'] == $compCode)
			{
				$sel = "selected";
			}
			$tabel.="<option value=\"".$row['compcode']."\" ".$sel.">".strtoupper( $row['compname'] )."</option>";
		}
		return $tabel;
	}
	
	function detilUnit($idUnit, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM mstunit WHERE idunit='".$idUnit."';", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function menuUnit($idUnit)
	{
		$tabel = "";
		$query = $this->koneksi->mysqlQuery("SELECT idunit, nmunit FROM mstunit ORDER BY nmunit ASC", $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$sel = "";
			if($row['idunit'] == $idUnit)
			{
				$sel = "selected";
			}
			$tabel.="<option value=\"".$row['idunit']."\" ".$sel.">".$row['nmunit']."</option>";
		}
		
		return $tabel;
	}
	
	function detilVessel($vescode, $field)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT ".$field." FROM Vesseltbl WHERE Vescode='".$vescode."'");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row[$field];
	}
	
	function menuVessel($vesCode)
	{
		$tabel = "";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT Vescode, Fullname FROM Vesseltbl WHERE shortname IS NOT NULL ORDER BY Fullname ASC");
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$sel = "";
			if($row['Vescode'] == $vesCode)
			{
				$sel = "selected";
			}
			$tabel.="<option value=\"".$row['Vescode']."\" ".$sel." title=\"".$row['Fullname']."\">".$row['Vescode']." - ".$row['Fullname']."</option>";
		}
		
		return $tabel;
	}
	
	function bankCodeMenu($bankCode)
	{
		$tabel = "";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "
		SELECT RTRIM(Acctcode) AS Acctcode, RTRIM(Acctname) AS Acctname, source FROM dbo.AccountCode WHERE (LEN(RTRIM(Acctcode)) = 5) AND (LEFT(Acctcode, 2) = '10') AND (Contact IS NULL) AND (Acctcode <> '10000') ORDER BY Acctname");
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$acctCode = $row['Acctcode'];
			$sel = "";
			if($bankCode == $acctCode)
				$sel = "selected";
				
			$tabel.="<option value=\"".$acctCode."\" ".$sel.">".$acctCode."&nbsp;-&nbsp;".$row['Acctname']."&nbsp;(&nbsp;".$row['source']."&nbsp;)</option>";
		}
		return $tabel;
	}
	
	function detilAccountCode($acctCode, $field)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT  TOP (1) ".$field." FROM dbo.AccountCode WHERE (Contact IS NULL) AND Acctcode = '".$acctCode."'");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row[$field];
	}
	
	function detilBankSource($acctCode, $field)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT  TOP (1) Source AS source FROM dbo.AccountCode WHERE (LEN(RTRIM(Acctcode)) = 5) AND (LEFT(Acctcode, 2) = '10') AND (Contact IS NULL) AND Acctcode = '".$acctCode."'");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row[$field];
	}
	
	function bankCodeMenuFirst()
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT  TOP (1) RTRIM(Acctcode) AS Acctcode, RTRIM(Acctname) AS Acctname FROM dbo.AccountCode WHERE (LEN(RTRIM(Acctcode)) = 5) AND (LEFT(Acctcode, 2) = '10') AND (Contact IS NULL) AND (Acctcode <> '10000') ORDER BY Acctname");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row['Acctcode'];
	}
	
/*	function lastBatchno()
	{
		$query = $this->koneksi->mysqlQuery("SELECT batchno FROM tblvoucher WHERE deletests=0;", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['batchno'];
	}*/
	function detilTblDescByIdDesc($idDesc, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tbldesc WHERE iddesc='".$idDesc."';");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
		
	function ubahUrutan($req, $idDesc)
	{
		$idVoucher = $this->detilTblDescByIdDesc($idDesc, "idvoucher");
		$urutan = $this->detilTblDescByIdDesc($idDesc, "urutan");
		
		$idDescTukar = ""; //iddesc yang akan ditukar urutannya
		if($req == "naik")
		{
			//$urutanTukar = $urutan-1;
			$urutanTukar = $this->cariUrutan($idVoucher, $urutan, "<");
		}
		if($req == "turun")
		{
			//$urutanTukar = $urutan+1;
			$urutanTukar = $this->cariUrutan($idVoucher, $urutan, ">");
		}
		$idDescTukar = $this->cariIdDescByUrutan($idVoucher, $urutanTukar);
		
		//tukar urutan berdasarkan request dan iddesc
		$this->updateUrutan($idDesc, $urutanTukar);
		$this->updateUrutan($idDescTukar, $urutan);
	}
	
	function cariUrutan($idVoucher, $urutan, $simbol)
	{
		if($simbol == "<")
			$order = "DESC";
			
		if($simbol == ">")
			$order = "ASC";
			
		$query = $this->koneksi->mysqlQuery("SELECT urutan FROM tbldesc WHERE idvoucher = ".$idVoucher." AND urutan ".$simbol." ".$urutan." AND deletests = 0 ORDER BY urutan ".$order." LIMIT 1;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['urutan'];
	}
	
	function cariIdDescByUrutan($idVoucher, $urutan)
	{
		$query = $this->koneksi->mysqlQuery("SELECT iddesc FROM tbldesc WHERE idvoucher = ".$idVoucher." AND urutan = ".$urutan.";");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['iddesc'];
	}
	
	function updateUrutan($idDesc, $urutan)
	{
		$this->koneksi->mysqlQuery("UPDATE tbldesc SET urutan = ".$urutan." WHERE iddesc = ".$idDesc.";");
	}
	
	function cariUrutanMaxDesc($idVoucher)
	{
		$query = $this->koneksi->mysqlQuery("SELECT max(urutan) as maxurutan FROM tbldesc WHERE idvoucher = ".$idVoucher." AND deletests=0;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['maxurutan'];
	}
	
	function jmlRowDesc($idVoucher)
	{
		$query = $this->koneksi->mysqlQuery("SELECT iddesc FROM tbldesc WHERE idvoucher = ".$idVoucher." AND deletests=0;");
		$jmlRow = $this->koneksi->mysqlNRows($query);
		
		return $jmlRow;
	}
}
?>