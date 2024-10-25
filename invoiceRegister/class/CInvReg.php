<?php
class CInvReg
{
	function CInvReg($koneksiMysql, $koneksiOdbc, $koneksiOdbcId, $CPublic)
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
	
	// ########## START INCOMING MAIL / INVOICE
	function menuBatchnoThnBln($thnBlnParam) // TAMPILKAN MENU BATHCNO KHUSUS TAHUN+BULAN SAJA
	{
		$tabel = "";
		$cariBatchnoSamaDateSekarang = $this->cariBatchnoSamaDateSekarang(); 
		if($cariBatchnoSamaDateSekarang == "kosong") // JIKA BATCHNO TIDAK ADA YANG SAMA DENGAN TAHUN+BULAN SEKARANG MAKA MUNCULKAN TAHUN+BULAN SEKARANG
		{
			$thnBlnSek = $this->CPublic->waktuServer("tahun").$this->CPublic->zerofill($this->CPublic->waktuServer("bulan"), 2);
			$tabel.= "<option value=\"".$thnBlnSek."\">".$thnBlnSek."</option>";
		}
		
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT(SUBSTR(batchno, 1, 6)) AS thnbln FROM mailinvoice WHERE deletests=0 ORDER BY batchno DESC;", $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$sel = "";
			if($thnBlnParam == $row['thnbln'])
				$sel = "selected";
			$tabel.="<option value=\"".$row['thnbln']."\" ".$sel.">".$row['thnbln']."</option>";
		}
		
		return $tabel;
	}
	
	function nilaiBatchnoThnBlnAwal()// TAMPILKAN NILAI BATHCNO KHUSUS TAHUN+BULAN SAJA
	{
		$nilai = "";
		
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT(SUBSTR(batchno, 1, 6)) AS thnbln FROM mailinvoice WHERE deletests=0 ORDER BY batchno DESC LIMIT 1;", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
			$nilai = $row['thnbln'];
			
		$cariBatchnoSamaDateSekarang = $this->cariBatchnoSamaDateSekarang();
		if($cariBatchnoSamaDateSekarang == "kosong") // JIKA BATCHNO TIDAK ADA YANG SAMA DENGAN TAHUN+BULAN SEKARANG MAKA MUNCULKAN TAHUN+BULAN SEKARANG
		{
			$thnBlnSek = $this->CPublic->waktuServer("tahun").$this->CPublic->zerofill($this->CPublic->waktuServer("bulan"), 2);
			$nilai = $thnBlnSek;
		}
		
		return $nilai;
	}
	
	function cariBatchnoSamaDateSekarang()// CARI BATCHNO YANG SAMA DENGAN TAHUN BULAN SEKARANG (TODAY)
	{
		$nilai = "kosong";
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT(batchno) FROM mailinvoice WHERE SUBSTR(batchno, 1, 6) = CONCAT(YEAR(NOW()), DATE_FORMAT(NOW(), '%m')) AND deletests=0;", $this->koneksi->bukaKoneksi());		
		$jmlRow = $this->koneksi->mysqlNRows($query);
		if($jmlRow != 0)
			$nilai = "ada";
		
		return $nilai;
	}
	
	function menuBatchnoTgl($thnBln, $tglParam)
	{
		$tabel = "";
		$cariBatchnoSamaDateSekarang2 = $this->cariBatchnoSamaDateSekarang2($thnBln);
		if($cariBatchnoSamaDateSekarang2 == "kosong")
		{
			$tglSek = $this->CPublic->zerofill($this->CPublic->waktuServer("tanggal"), 2);
			$tabel.= "<option value=\"".$tglSek."\">".$tglSek."</option>";
		}
		
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT(SUBSTR(batchno, 7, 2)) AS tgl, SUBSTR(batchno, 1, 6) AS thnbln, batchno FROM mailinvoice WHERE SUBSTR(batchno, 1, 6)='".$thnBln."' AND deletests=0 ORDER BY tgl DESC;", $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$sel = "";
			if($tglParam == $row['tgl'])
				$sel = "selected";
			$tabel.="<option value=\"".$row['tgl']."\" ".$tglParam.">".$row['tgl']."</option>";
		}
		
		return $tabel;
	}
	
	function nilaiBatchnoTglAwal($thnBln)
	{
		$nilai = "";
		
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT(SUBSTR(batchno, 7, 2)) AS tgl, SUBSTR(batchno, 1, 6) AS thnbln, batchno FROM mailinvoice WHERE SUBSTR(batchno, 1, 6)='".$thnBln."' AND deletests=0 ORDER BY tgl DESC LIMIT 1;", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
			$nilai = $row['tgl'];
			
		$cariBatchnoSamaDateSekarang2 = $this->cariBatchnoSamaDateSekarang2($thnBln);
		if($cariBatchnoSamaDateSekarang2 == "kosong") // JIKA BATCHNO TIDAK ADA YANG SAMA DENGAN TAHUN+BULAN SEKARANG MAKA MUNCULKAN TAHUN+BULAN SEKARANG
		{
			$tglSek = $this->CPublic->zerofill($this->CPublic->waktuServer("tanggal"), 2);
			$nilai = $tglSek;
		}
		
		return $nilai;
	}
	
	function cariBatchnoSamaDateSekarang2($thnBln) // CARI BATCHNO YANG SAMA DENGAN TANGGAL SEKARANG (TODAY)
	{
		$nilai = "kosong";
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT(batchno) FROM mailinvoice WHERE batchno = CONCAT(".$thnBln.",DATE_FORMAT(NOW(), '%d')) AND deletests=0;", $this->koneksi->bukaKoneksi());		
		$jmlRow = $this->koneksi->mysqlNRows($query);
		if($jmlRow != 0)
			$nilai = "ada";
			
		return $nilai;
	}
	
	function cekTableExist($table)
	{
		$nilai = "tidak";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT COUNT(table_name) AS aaa FROM information_schema.tables WHERE table_name = '".$table."'");
		$row = $this->koneksiOdbc->odbcFetch($query);
		if($row['aaa'] == "1")
		{
			$nilai = "ada";
		}
		
		return $nilai;
	}
	
	function menuSenderVendor($acctCode)
	{
		$i = 1;
		$tabel = "";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT RTRIM(Acctcode) AS Acctcode, RTRIM(AcctIndo) AS AcctIndo, RTRIM(Acctname) AS Acctname FROM AccountCode WHERE SUBSTRING(Acctcode ,1,2) = '12' AND LEN(RTRIM(Acctcode))=5 ORDER BY Acctname ASC;");
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$sel = "";//12442
			if($row['Acctcode'] == $acctCode)
				$sel = "selected";
				
			$tabel.="<option value=\"".$row['Acctcode']."\" ".$sel.">".$row['Acctname']."</option>";
		}
		return $tabel;
	}
	
	function menuArraySenderVendor()
	{
		$i = 0;
		$tabel = "";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT RTRIM(Acctcode) AS Acctcode, RTRIM(AcctIndo) AS AcctIndo, RTRIM(Acctname) AS Acctname FROM AccountCode WHERE SUBSTRING(Acctcode ,1,2) = '12' AND LEN(RTRIM(Acctcode))=5 ORDER BY AcctIndo ASC;", $CKoneksiInvReg->bukaKoneksi());
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
			$tabel.="<option value=\"".$row['Currcode']."\" ".$sel.">".$row['Currcode']." - ( ".$row['Currname']." )</option>";
		}
		
		return $tabel;
	}
	
/*	function menuCurrencySelect($Currcode)
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
	}*/

	
	function menuCmp($compCode)
	{
		$tabel = "";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT compcode, RTRIM(compname) as compname FROM Company WHERE (noactive=0) OR (Compcode = 'atm') ORDER BY compname ASC");
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$sel = "";
			if($row['compcode'] == $compCode)
			{
				$sel = "selected";
			}
			$tabel.="<option value=\"".$row['compcode']."\" ".$sel.">".strtoupper( $row['compname'] )."</option>";
		}
		
		$selInsa = "";
		if($compCode == "INS")
		{
			$selInsa = "selected";
		}
		$tabel.="<option value=\"INS\" ".$selInsa.">INSA</option>";
		
		return $tabel;
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
			$tabel.="<option value=\"".$row['Vescode']."\" ".$sel.">".$row['Vescode']." - ".$row['Fullname']."</option>";
		}
		
		return $tabel;
	}
	
	function bankCodeMenu($bankCode)
	{
		/*SELECT RTRIM(Acctcode) AS Acctcode, RTRIM(Acctname) AS Acctname FROM dbo.AccountCode WHERE (LEN(RTRIM(Acctcode)) = 5) AND (LEFT(Acctcode, 2) = '10') AND (Contact IS NULL) ORDER BY Acctname*/
		$tabel = "";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "
		SELECT RTRIM(Acctcode) AS Acctcode, RTRIM(Acctname) AS Acctname FROM dbo.AccountCode WHERE (LEN(RTRIM(Acctcode)) = 5) AND (LEFT(Acctcode, 2) = '10') AND (Contact IS NULL) ORDER BY Acctname");
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$acctCode = $row['Acctcode'];
			$sel = "";
			if($bankCode == $acctCode)
				$sel = "selected";
				
			$tabel.="<option value=\"".$acctCode."\" ".$sel.">".$acctCode." - ".$row['Acctname']."</option>";
		}
		return $tabel;
	}
	
	function bankCodeMenuFirst()
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT  TOP (1) RTRIM(Acctcode) AS Acctcode, RTRIM(Acctname) AS Acctname FROM dbo.AccountCode WHERE (LEN(RTRIM(Acctcode)) = 5) AND (LEFT(Acctcode, 2) = '10') AND (Contact IS NULL) AND (Acctcode <> '10000') ORDER BY Acctname");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row['Acctcode'];
	}
	
	function detilBankCode($acctCode, $field)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT  TOP (1) RTRIM(Acctcode) AS Acctcode, RTRIM(Acctname) AS Acctname FROM dbo.AccountCode WHERE (LEN(RTRIM(Acctcode)) = 5) AND (LEFT(Acctcode, 2) = '10') AND (Contact IS NULL) AND (Acctcode <> '10000') AND Acctcode = '".$acctCode."'");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row[$field];
	}
	
	/*function detilBankSource($acctCode, $field)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT  TOP (1) Source AS source FROM dbo.AccountCode WHERE (LEN(RTRIM(Acctcode)) = 5) AND (LEFT(Acctcode, 2) = '10') AND (Contact IS NULL) AND (Acctcode <> '10000') AND Acctcode = '".$acctCode."'");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row[$field];
	}*/
	
	function detilBankSource($acctCode, $field)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT  TOP (1) Source AS source FROM dbo.AccountCode WHERE (LEN(RTRIM(Acctcode)) = 5) AND (LEFT(Acctcode, 2) = '10') AND (Contact IS NULL) AND Acctcode = '".$acctCode."'");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row[$field];
	}
	
	function detilSenderVendor($Acctcode, $field)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT RTRIM(".$field.") AS ".$field." FROM AccountCode WHERE  SUBSTRING(Acctcode ,1,2) = '12' AND LEN(RTRIM(Acctcode))=5 AND Acctcode='".$Acctcode."'");
		$row = $this->koneksiOdbc->odbcFetch($query);
		return $row[$field];
	}
	
	function detilSenderVendorByName($AcctName, $field)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT RTRIM(".$field.") AS ".$field." FROM dbo.AccountCode WHERE  SUBSTRING(Acctcode ,1,2) = '12' AND LEN(RTRIM(Acctcode))=5 AND Acctname='".$AcctName."'");
		$row = $this->koneksiOdbc->odbcFetch($query);
		return $row[$field];
	}
	
	function detilAcctCode($Acctcode, $field)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT RTRIM(".$field.") AS ".$field." FROM AccountCode WHERE LEN(RTRIM(Acctcode))=5 AND Acctcode='".$Acctcode."'");
		$row = $this->koneksiOdbc->odbcFetch($query);
		return $row[$field];
	}
	
	function detilAcctCodeByName($AcctName, $field)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT RTRIM(".$field.") AS ".$field." FROM AccountCode WHERE LEN(RTRIM(Acctcode))=5 AND Acctname='".$AcctName."'");
		$row = $this->koneksiOdbc->odbcFetch($query);
		return $row[$field];
	}
	
	function detilComp($compcode, $field)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT ".$field." FROM Company WHERE compcode='".$compcode."' AND noactive=0");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		if($compcode == "INS")
		{
			if($field == "compname")
			{
				return "INSA";
			}
		}
		else
		{
			return $row[$field];
		}
	}
	
	function detilUnit($idUnit, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM mstunit WHERE idunit='".$idUnit."'", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	} 
	
	function detilMailInv($idMailInv, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM mailinvoice WHERE idmailinv='".$idMailInv."' AND deletests=0;", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function detilVessel($vescode, $field)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT ".$field." FROM Vesseltbl WHERE Vescode='".$vescode."'");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row[$field];
	}
	
	function aksesInvReg($userId, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tbluserjenis WHERE userid = '".$userId."';", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		if($row[$field] == "N")
		{
			$disabled = "disabled";
		}
		if($row[$field] == "Y")
		{
			$disabled = "";
		}
		
		return $disabled;
	}
	
	function aksesBtn($userId, $tambahanDepan, $field, $idElement)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tbluserjenis WHERE userid = '".$userId."';", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		if($row[$field] == "N")
		{
			$nilai = $tambahanDepan."disabledBtn('".$idElement."');";
		}
		if($row[$field] == "Y")
		{
			$nilai = $tambahanDepan."enabledBtn('".$idElement."');";
		}
		
		return $nilai;
	}
	
	function aksesElement($userId, $tambahanDepan, $field, $idElement)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tbluserjenis WHERE userid = '".$userId."';", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		if($row[$field] == "N")
		{
			$nilai = $tambahanDepan."disabledElement('".$idElement."');";
		}
		if($row[$field] == "Y")
		{
			$nilai = $tambahanDepan."enabledElement('".$idElement."');";
		}
		
		return $nilai;
	}
	
	function lastTransNo()
	{
		$query = $this->koneksi->mysqlQuery("SELECT lasttransno FROM tbllasttransno", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['lasttransno'];
	}
	
	function imgIcon($idMailInv)
	{
		$icon = "";
		$query = $this->koneksi->mysqlQuery("SELECT * FROM mailinvoice WHERE idmailinv='".$idMailInv."' AND deletests=0;", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		
		$imgMata = "<img src=\"../picture/eye.png\" width=\"14\" title=\"ALREADY ACKNOWLEDGE\">";
		$imgJam = "<img src=\"../picture/hourglass-select-remain.png\" width=\"14\" title=\"ALREADY RETURNED\">";
		$imgUang = "<img src=\"../picture/money.png\" width=\"14\" title=\"ALREADY PREPARE FOR PAYMENT\">";
		$imgGembok = "<img src=\"../../picture/Lock-Lock-icon.png\" width=\"14\" title=\"ALREADY PAID\">";
		if($row['ack'] == "0")
		{
			$icon = "&nbsp;";
		}
		if($row['ack'] == "1")
		{
			$icon = $imgMata;
			if($row['saveinvret'] == "Y")
			{
				$icon = $imgJam;
				if($row['preparepayment'] == "Y")
				{
					$icon = $imgUang;
					if($row['paid'] == "Y")
					{
						$icon = $imgGembok;
					}
				}
			}
		}

		return $icon;
	}
	
	function detilOutgoingInv($idOutgoingInv, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM outgoinginvoice WHERE idoutgoinginv='".$idOutgoingInv."' AND deletests=0;", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	/*function detilLogin($koneksiPortalId, $userId, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM login WHERE userid='".$userId."' AND ACTIVE='Y' AND deletests=0", $koneksiPortalId);
		$row = $this->koneksi->mysqlFetch($query);
		$isi = $row[$field];
		
		return $isi;
	}*/
	
	function menuBatchnoThnBlnOutgoing($thnBlnParam) // TAMPILKAN MENU BATHCNO KHUSUS TAHUN+BULAN SAJA
	{
		$tabel = "";
		$cariBatchnoSamaDateSekarang = $this->cariBatchnoOutgoingSamaDateSekarang(); 
		if($cariBatchnoSamaDateSekarang == "kosong") // JIKA BATCHNO TIDAK ADA YANG SAMA DENGAN TAHUN+BULAN SEKARANG MAKA MUNCULKAN TAHUN+BULAN SEKARANG
		{
			$thnBlnSek = $this->CPublic->waktuServer("tahun").$this->CPublic->zerofill($this->CPublic->waktuServer("bulan"), 2);
			$tabel.= "<option value=\"".$thnBlnSek."\">".$thnBlnSek."</option>";
		}
		
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT(SUBSTR(batchno, 1, 6)) AS thnbln FROM outgoinginvoice WHERE deletests=0 ORDER BY batchno DESC;", $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$sel = "";
			if($thnBlnParam == $row['thnbln'])
				$sel = "selected";
			$tabel.="<option value=\"".$row['thnbln']."\" ".$sel.">".$row['thnbln']."</option>";
		}
		
		return $tabel;
	}
	
	function cariBatchnoOutgoingSamaDateSekarang()// CARI BATCHNO YANG SAMA DENGAN TAHUN BULAN SEKARANG (TODAY)
	{
		$nilai = "kosong";
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT(batchno) FROM outgoinginvoice WHERE SUBSTR(batchno, 1, 6) = CONCAT(YEAR(NOW()), DATE_FORMAT(NOW(), '%m')) AND deletests=0;", $this->koneksi->bukaKoneksi());		
		$jmlRow = $this->koneksi->mysqlNRows($query);
		if($jmlRow != 0)
			$nilai = "ada";
		
		return $nilai;
	}
	
	function nilaiBatchnoTglAwalOutgoing($thnBln)
	{
		$nilai = "";
		
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT(SUBSTR(batchno, 7, 2)) AS tgl, SUBSTR(batchno, 1, 6) AS thnbln, batchno FROM outgoinginvoice WHERE SUBSTR(batchno, 1, 6)='".$thnBln."' AND deletests=0 ORDER BY tgl DESC LIMIT 1;", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		{
			$nilai = $row['tgl'];
		}
		$cariBatchnoSamaDateSekarang2 = $this->cariBatchnoSamaDateSekarang2Outgoing($thnBln);
		if($cariBatchnoSamaDateSekarang2 == "kosong") // JIKA BATCHNO TIDAK ADA YANG SAMA DENGAN TAHUN+BULAN SEKARANG MAKA MUNCULKAN TAHUN+BULAN SEKARANG
		{
			$tglSek = $this->CPublic->zerofill($this->CPublic->waktuServer("tanggal"), 2);
			$nilai = $tglSek;
		}
		
		return $nilai;
	}
	
	function cariBatchnoSamaDateSekarang2Outgoing($thnBln) // CARI BATCHNO YANG SAMA DENGAN TANGGAL SEKARANG (TODAY)
	{
		$nilai = "kosong";
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT(batchno) FROM outgoinginvoice WHERE batchno = CONCAT(".$thnBln.",DATE_FORMAT(NOW(), '%d')) AND deletests=0;", $this->koneksi->bukaKoneksi());		
		$jmlRow = $this->koneksi->mysqlNRows($query);
		if($jmlRow != 0)
			$nilai = "ada";
			
		return $nilai;
	}
	
	function nilaiBatchnoThnBlnAwalOutgoing()// TAMPILKAN NILAI BATHCNO KHUSUS TAHUN+BULAN SAJA
	{
		$nilai = "";
		
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT(SUBSTR(batchno, 1, 6)) AS thnbln FROM outgoinginvoice WHERE deletests=0 ORDER BY batchno DESC LIMIT 1;", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		{
			$nilai = $row['thnbln'];
		}
		$cariBatchnoSamaDateSekarang = $this->cariBatchnoSamaDateSekarangOutgoing();
		if($cariBatchnoSamaDateSekarang == "kosong") // JIKA BATCHNO TIDAK ADA YANG SAMA DENGAN TAHUN+BULAN SEKARANG MAKA MUNCULKAN TAHUN+BULAN SEKARANG
		{
			$thnBlnSek = $this->CPublic->waktuServer("tahun").$this->CPublic->zerofill($this->CPublic->waktuServer("bulan"), 2);
			$nilai = $thnBlnSek;
		}
		
		return $nilai;
	}
	
	function cariBatchnoSamaDateSekarangOutgoing()// CARI BATCHNO YANG SAMA DENGAN TAHUN BULAN SEKARANG (TODAY)
	{
		$nilai = "kosong";
		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT(batchno) FROM outgoinginvoice WHERE SUBSTR(batchno, 1, 6) = CONCAT(YEAR(NOW()), DATE_FORMAT(NOW(), '%m')) AND deletests=0;", $this->koneksi->bukaKoneksi());		
		$jmlRow = $this->koneksi->mysqlNRows($query);
		if($jmlRow != 0)
			$nilai = "ada";
		
		return $nilai;
	}
	
	function imgIconOutgoing($idOutgoingInv)
	{
		$icon = "";
		$query = $this->koneksi->mysqlQuery("SELECT * FROM outgoinginvoice WHERE idoutgoinginv='".$idOutgoingInv."' AND deletests=0;", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		
		$imgMata = "<img src=\"../picture/eye.png\" width=\"14\" title=\"ALREADY ACKNOWLEDGE\">";
		$imgJam = "<img src=\"../picture/hourglass-select-remain.png\" width=\"14\" title=\"ALREADY RETURNED\">";
		$imgUang = "<img src=\"../picture/money.png\" width=\"14\" title=\"ALREADY PREPARE FOR PAYMENT\">";
		$imgGembok = "<img src=\"../../picture/Lock-Lock-icon.png\" width=\"14\" title=\"ALREADY PAID\">";
		if($row['ack'] == "0")
		{
			$icon = "&nbsp;";
		}
		if($row['ack'] == "1")
		{
			$icon = $imgMata;
		}
		if($row['saveinvret'] == "Y")
		{
			$icon = $imgJam;
		}
		if($row['preparepayment'] == "Y")
		{
			$icon = $imgUang;
		}
		if($row['paid'] == "Y")
		{
			$icon = $imgGembok;
		}
		
		return $icon;
	}
	
	function detilSplit($idMailInv, $userId, $fieldAksi, $urutan, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tblsplit WHERE idmailinv='".$idMailInv."' AND userid=".$userId." AND fieldaksi='".$fieldAksi."' AND urutan=".$urutan.";", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function detilSplitYes($idMailInv, $fieldAksi, $urutan, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tblsplit WHERE idmailinv='".$idMailInv."' AND fieldaksi='".$fieldAksi."' AND urutan=".$urutan.";", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function detilSplitTemp($idMailInv, $userId, $fieldAksi, $urutan, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tblsplittemp WHERE idmailinv='".$idMailInv."' AND userid=".$userId." AND fieldaksi='".$fieldAksi."' AND urutan=".$urutan.";", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function totalAmountSplitDeb($idMailInv, $userId, $fieldAksi) // TOTAL AMOUNT YANG DI SPLIT SELAIN URUTAN KE 1
	{
		$query = $this->koneksi->mysqlQuery("SELECT SUM(amount) AS totalamount FROM tblsplittemp WHERE idmailinv=".$idMailInv." AND userid=".$userId." AND fieldaksi='".$fieldAksi."' AND urutan!=1;", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['totalamount'];
	}
	
	function jmlRowSplitTemp($idMailInv, $userId, $fieldAksi)
	{
	$query = $this->koneksi->mysqlQuery("SELECT idmailinv FROM tblsplittemp WHERE idmailinv='".$idMailInv."' AND userid='".$userId."' AND fieldaksi='".$fieldAksi."';", $this->koneksi->bukaKoneksi());		
		$jmlRow = $this->koneksi->mysqlNRows($query);
	
		return $jmlRow;
	}

	function getSubAccount($company,$account,$subAcct)
	{
		$acctDesc = "";

		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT * FROM sub_account WHERE company = '".$company."' AND account = '".$account."' AND subaccount = '".$subAcct."'");
		$row = $this->koneksiOdbc->odbcFetch($query);

		$acctDesc = $row['descsub'];

		return $acctDesc;
	}

	function batchNoTglPaymentList()
	{
		$opt = "";

		$opt.="<option value=\"all\">All</option>";

		$sql = "SELECT date_send_paymentlist FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND preparepayment='Y' AND deletests=0 AND paid = 'N' AND st_payment_list = 'Y' GROUP BY YEAR(date_send_paymentlist),MONTH(date_send_paymentlist) ORDER BY date_send_paymentlist DESC ;";
		
		$query = $this->koneksi->mysqlQuery($sql, $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$tempDate = explode("-",$row['date_send_paymentlist']);
			$thn = $tempDate[0];
			$bln = $tempDate[1];

			$opt.="<option value=\"".$thn.$bln."\">".$thn.$bln."</option>";
		}
		return $opt;
	}

	function getDataPaymentListAdd($get)
	{
		$trNya = "";
		$whereNya = "";
		$tempThnBln = $get['thnBln'];
		$ttlIdr = 0;
		$ttlUsd = 0;
		$ttlOther1 = 0;
		$ttlOther2 = 0;
		$currIdr = "";
		$currUsd = "";
		$currOther1 = "";
		$currOther2 = "";

		if($tempThnBln == "all")
		{
			$whereNya = "";
		}else{			
			$thn = substr($tempThnBln, 0,4);
			$bln = substr($tempThnBln, 4,2);
			$tgl = $get['tgl'];

			$tglblnthn = $thn."-".$bln."-".$tgl;
			$whereNya = " AND date_send_paymentlist = '".$tglblnthn."'";
		}

		$trNya .= "<tr>
						<td height=\"40\" align=\"center\" colspan=\"5\"><span style=\"font-family:Tahoma;font-size:18px;font-weight:bold;\"> PAYMENT LIST </span></td>";
		$trNya .= "</tr>";
		$trNya .= "<tr style=\"font-family:Arial;font-size:12px;font-weight:bold;\" align=\"center\">";
			$trNya .= "<td width=\"10%\" height=\"18\" class=\"tabelBorderBottomJust\" style=\"border-bottom-width:2px;border-bottom-color:#333;\">
							BATCH NO
						</td>";
			$trNya .= "<td width=\"10%\" height=\"18\" class=\"tabelBorderBottomJust\" style=\"border-bottom-width:2px;border-bottom-color:#333;\">
							TRANS NO
						</td>";
			$trNya .= "<td width=\"30%\" height=\"18\" class=\"tabelBorderBottomJust\" style=\"border-bottom-width:2px;border-bottom-color:#333;\">
							BILLING COMPANY
						</td>";
			$trNya .= "<td width=\"30%\" height=\"18\" class=\"tabelBorderBottomJust\" style=\"border-bottom-width:2px;border-bottom-color:#333;\">
							SENDER / VENDOR NAME
						</td>";
			$trNya .= "<td width=\"20%\" height=\"18\" class=\"tabelBorderBottomJust\" style=\"border-bottom-width:2px;border-bottom-color:#333;\">AMOUNT</td>";
		$trNya .= "</tr>";

		$sql = " SELECT idmailinv, sendervendor1, sendervendor2name, company, companyname, currency, SUM( amount ) AS amount, transno, datepreppay, paid, date_send_paymentlist FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND preparepayment='Y' AND deletests=0 AND paid = 'N' AND st_payment_list = 'Y' ".$whereNya." GROUP BY transno ORDER BY date_send_paymentlist DESC,0+transno DESC ; ";
		
		$query = $this->koneksi->mysqlQuery($sql, $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$tempDate = explode("-",$row['date_send_paymentlist']);
			$thnNya = $tempDate[0];
			$blnNya = $tempDate[1];
			$tglNya = $tempDate[2];

			$senderVendor = $row['sendervendor1'];

			if($row['sendervendor1'] == "")
			{
				$senderVendor = $row['sendervendor2name'];
			}

			$trNya.="<tr>";
				$trNya.="<td class=\"tabelBorderRightNull\" style=\"font-size:11px;height:20px;\" align=\"center\">".$thnNya.$blnNya.$tglNya."</td>";
				$trNya.="<td class=\"tabelBorderLeftRightNull\" style=\"font-size:11px;\" align=\"center\">".$row['transno']."</td>";
				$trNya.="<td class=\"tabelBorderLeftRightNull\" style=\"font-size:11px;\" align=\"left\">".$row['companyname']."</td>";
				$trNya.="<td class=\"tabelBorderLeftRightNull\" style=\"font-size:11px;\" align=\"left\">".$senderVendor."</td>";				
				$trNya.="<td class=\"tabelBorderLeftNull\" style=\"font-size:11px;\" align=\"right\"><label style=\"padding-left:5px;float: left;\">(".$row['currency'].")</label> <label style=\"padding-right:5px;\">".number_format($row['amount'],2)."</label></td>";
			$trNya.="</tr>";

			if(strtolower($row['currency']) == 'idr')
			{
				$currIdr = $row['currency'];
				$ttlIdr = $ttlIdr + $row['amount'];
			}
			else if(strtolower($row['currency']) == 'usd')
			{
				$currUsd = $row['currency'];
				$ttlUsd = $ttlUsd + $row['amount'];
			}else{
				if($currOther1 == "")
				{
					$currOther1 = $row['currency'];
					$ttlOther1 = $ttlOther1 + $row['amount'];
				}else{
					if($currOther1 == $row['currency'])
					{
						$ttlOther1 = $ttlOther1 + $row['amount'];
					}else{
						$currOther2 = $row['currency'];
						$ttlOther2 = $ttlOther2 + $row['amount'];
					}
				}

			}
		}

		$rowSpanNya = 3;
		if($currOther1 != "") { $rowSpanNya = $rowSpanNya + 1; }
		if($currOther2 != "") { $rowSpanNya = $rowSpanNya + 1; }

		$trNya .= "<tr>";
			$trNya .= "<td height=\"25\" align=\"right\" colspan=\"4\" rowspan=\"".$rowSpanNya."\" class=\"tabelBorderTopJust\" style=\"border-top-width:2px;border-top-color:#333;\">";
				$trNya .= "<span style=\"font-family:Tahoma;font-size:11px;font-weight:bold;\"> TOTAL </span>";
			$trNya .= "</td>";
		$trNya .= "</tr>";
		$trNya .= "<tr>";
			$trNya .= "<td align=\"right\" class=\"tabelBorderTopJust\" style=\"border-top-width:2px;border-top-color:#333;\">";
				$trNya .= "	<label style=\"padding-left:5px;float: left;font-size:11px;\">(".$currIdr.")</label>
							<label style=\"padding-right:5px;font-size:11px;\">".number_format($ttlIdr,2)."</label>";
			$trNya .= "</td>";
		$trNya .= "</tr>";
		if($ttlUsd > 0)
		{
			$trNya .= "<tr>";
				$trNya .= "<td align=\"right\">";
					$trNya .= "	<label style=\"padding-left:5px;float: left;font-size:11px;\">(".$currUsd.")</label>
								<label style=\"padding-right:5px;font-size:11px;\">".number_format($ttlUsd,2)."</label>";
				$trNya .= "</td>";
			$trNya .= "</tr>";
		}

		if($currOther1 != "")
		{
			$trNya .= "<tr>";
				$trNya .= "<td align=\"right\">";
					$trNya .= "	<label style=\"padding-left:5px;float: left;font-size:11px;\">(".$currOther1.")</label>
								<label style=\"padding-right:5px;font-size:11px;\">".number_format($ttlOther1,2)."</label>";
				$trNya .= "</td>";
			$trNya .= "</tr>";
		}

		if($currOther2 != "")
		{
			$trNya .= "<tr>";
				$trNya .= "<td align=\"right\">";
					$trNya .= "	<label style=\"padding-left:5px;float: left;font-size:11px;\">(".$currOther2.")</label>
								<label style=\"padding-right:5px;font-size:11px;\">".number_format($ttlOther2,2)."</label>";
				$trNya .= "</td>";
			$trNya .= "</tr>";
		}

		return $trNya;
	}

	function getRemarkMailInv($transNo)
	{
		$remark = "";

		$sql = "SELECT remark FROM mailinvoice WHERE deletests=0 AND transno = ".$transNo." ";
		
		$query = $this->koneksi->mysqlQuery($sql, $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			if($remark == "")
			{
				if($row['remark'] != "")
				{
					$remark = $row['remark'];
				}
			}else{
				if($row['remark'] != "")
				{
					$remark .= "<br>- ".$row['remark'];
				}				
			}
		}

		return $remark;
	}

	function getExpDateMailInv($transNo)
	{
		$tglexp = "";
		$isiExp = 1;

		$sql = "SELECT tglexp FROM mailinvoice WHERE deletests=0 AND transno = ".$transNo." ";
		
		$query = $this->koneksi->mysqlQuery($sql, $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			if($tglexp == "")
			{
				if($row['tglexp'] != "0000-00-00")
				{
					$tglexp = $this->CPublic->convTglPO($row['tglexp']);
				}
			}else{
				
				if($row['tglexp'] != "0000-00-00")
				{
					if($isiExp == 2)
					{
						$tglexp .= "<br><br>".$this->CPublic->convTglPO($row['tglexp']);
					}else{
						$tglexp .= "<br>".$this->CPublic->convTglPO($row['tglexp']);
					}					
				}
			}
			$isiExp++;
		}

		return $tglexp;
	}

	function convertBankNameToExcel($bankName)
	{
		if(strstr(strtoupper($bankName), "BNI"))
		{
			$bankName = "BNI";
		}
		else if(strstr(strtoupper($bankName), "BCA"))
		{
			$bankName = "BCA";
		}
		else if(strstr(strtoupper($bankName), "BRI"))
		{
			$bankName = "BRI";
		}
		else if(strstr(strtoupper($bankName), "CIMB"))
		{
			$bankName = "CIMB NIAGA";
		}
		else if(strstr(strtoupper($bankName), "CITIBANK"))
		{
			$bankName = "CITIBANK";
		}
		else if(strstr(strtoupper($bankName), "MEGA"))
		{
			$bankName = "MEGA";
		}
		else if(strstr(strtoupper($bankName), "MANDIRI"))
		{
			$bankName = "MANDIRI";
		}			
		else if(strstr(strtoupper($bankName), "OCBC"))
		{
			$bankName = "OCBC";
		}
		else if(strstr(strtoupper($bankName), "PERMATA"))
		{
			$bankName = "PERMATA";
		}			
		else if(strstr(strtoupper($bankName), "SINARMAS"))
		{
			$bankName = "SINARMAS";
		}

		return $bankName;
	}

}
?>