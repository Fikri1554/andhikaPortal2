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
		/*SELECT     Acctcode, Acctname, Acctindo, Currcy, Source, Refcode, Cflgroup, Summgrp, Creddays, Credregno, Addrs1st, Addrs2nd, Addrs3rd, Contact, Phoneno, 
                      Faxno, Emailadd, TDBGroup, Alggroup
FROM         dbo.AccountCode
WHERE     (SUBSTRING(Acctcode, 1, 2) = '10') AND (LEN(RTRIM(Acctcode)) = 5)
ORDER BY Acctindo*/
		$tabel = "";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "
		SELECT RTRIM(Acctcode) AS Acctcode, RTRIM(Acctname) AS Acctname FROM dbo.AccountCode WHERE (LEN(RTRIM(Acctcode)) = 5) AND (LEFT(Acctcode, 2) = '10') AND (Contact IS NULL) AND (Acctcode <> '10000') ORDER BY Acctname");
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
	
}
?>