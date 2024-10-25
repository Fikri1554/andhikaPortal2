<?php
class CEmployee
{
	var $tabel;
	
	function CEmployee($koneksiOdbcId, $koneksiOdbc, $koneksiMysql)
	{
		$this->koneksi = $koneksiMysql;
		$this->koneksiOdbc = $koneksiOdbc;
		$this->koneksiOdbcId = $koneksiOdbcId;
		$tabel = "";
	}
	
	function menuEmployee($empNo, $cLogin)
	{
		$html = "";
		$html.= "<select class=\"elementMenu\" id=\"empno\" name=\"empno\" title=\"Choose Employee Name\">";
		$html.= "<option value=\"00000\">-- PLEASE SELECT --</option>";
		
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT empno, nama, tglkeluar, stsresign, YEAR(tglkeluar) AS tahunkeluar FROM dbo.tblMstEmp WHERE (stsresign = 0) AND (kdcmp = 01 OR kdcmp = 02 OR kdloc = '001') AND (YEAR(tglkeluar) = '1900') AND (deletests = 0) ORDER BY nama");
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$empNoTblLogin = $cLogin->detilLoginByEmpno($row['empno'], "empno");
			if($empNoTblLogin == "")
			{
				$html.="<option value=\"".$row['empno']."\" ".$sel.">".$row['nama']."</option>";
			}
		}
		
		$html.= "</select>";
		
		return $html;
	}
	
	function menuEmployeeEditUser($empNo)
	{
		$html = "";
		$html.= "<select class=\"elementMenu\" id=\"empno\" name=\"empno\" disabled>";
		$html.= "<option value=\"".$row['empno']."\">".$this->detilEmp($empNo, "nama")."</option>";
		$html.= "</select>";
		
		return $html;
	}
	
	function menuActive($active)
	{
		$html = "";
		
		$array = array("YES", "NO");
		$html.= "<select class=\"elementMenu\" id=\"active\" name=\"active\" title=\"Choose Activation\">";
		for($i = 0; $i < count($array); $i++)
		{
			$kdActive = substr($array[$i], 0, 1);
			
			$sel = "";
			if($active == $kdActive)
			{
				$sel = "selected=\"selected\"";
			}
			
			$html.= "<option value=\"".$kdActive."\" ".$sel.">".$array[$i]."</option>";
		}
		$html.= "</select>";      
		return $html;
	}
	
	function menuAuthorEmployee($cPublic, $empNoSendiri, $ideFold)
	{
		// EMPLOYEE YANG TIDAK TERMASUK KEDALAM MENU EMPLOYEE AUTHOR FOLD ADALAH
		// 1. DIRI SENDIRI / USER LOGIN
		// 2. SUDAH TERDAFTAR DI LOGIN DATABASE ANDHIKAPORTAL
		// 3. BELUM ADA DI DAFTAR AUTHOR FOLDER YANG DIPILIH
		// 4. EMPLOYEE YANG MENJADI ATASAN USER LOGIN, BAHKAN SAMPAI ATASAN2 PALING TINGGI (PAK DIDET) 
		$statusTblAuthorFold = $cPublic->tabelDBKosong("tblauthorfold");
		
		$html = "";
		$html.= "<select class=\"elementMenu\" id=\"empno\" name=\"empno\" ".$disabledEmp." title=\"Choose Name\">";
		$html.= "<option value=\"00000\">-- PLEASE SELECT --</option>";
		$html.= "<option value=\"99999\">&nbsp;ALL</option>";
		$query = $this->koneksi->mysqlQuery("SELECT userid, empno, userfullnm FROM login WHERE empno != '".$empNoSendiri."' AND active='Y' AND deletests=0 ORDER BY userfullnm ASC;");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$empnoAdaTidakTblAuthor = $this->empnoAdaTidakTblAuthor($row['empno'], $ideFold);
			if($empnoAdaTidakTblAuthor == "KOSONG")
			{
				$atasanEmp = $this->atasanEmp($empNoSendiri, $cPublic);
				$cariTextEmpno = strpos($atasanEmp, $row['empno']);
				if ($cariTextEmpno === false)
				{
					$html.="<option value=\"".$row['empno']."\">&nbsp;".$row['userfullnm']."</option>";
				}				
			}
		}
		$html.= "</select>";        
		
		return $html;
	}
	
	function cekSubordinate($koneksiOdbc, $koneksiOdbcId, $bossEmpNo)
	{
		
		$query = $koneksiOdbc->odbcExec($koneksiOdbcId, "SELECT empno, nama, tglkeluar, stsresign, YEAR(tglkeluar) AS tahunkeluar FROM dbo.tblMstEmp WHERE (stsresign = 0) AND (kdcmp = 01 OR kdcmp = 02) AND (YEAR(tglkeluar) = '1900') AND (deletests = 0) AND (bossempno = '".$bossEmpNo."') ORDER BY nama");
		$jmlRow = $koneksiOdbc->odbcNRows($query);
		
		$nilai = "ada";
		if($jmlRow == 0)
		{
			$nilai = "kosong";
		}
		return $nilai;
	}
	
	function menuUserHistory()
	{
		$html = "";
		$html.= "<select class=\"elementMenu\" id=\"userid\" name=\"userid\" title=\"Choose User\">";
		$html.= "<option value=\"00000\">-- PLEASE SELECT --</option>";
		
		$query = $this->koneksi->mysqlQuery("SELECT * FROM login WHERE deletests=0 ORDER by userfullnm ASC");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$html.="<option value=\"".$row['userid']."\" ".$sel.">".$row['userfullnm']."</option>";
		}
		
		$html.= "</select>";
		
		return $html;
	}
	
	function menuUserOtherApp()
	{
		$html = "";
		$html.= "<select class=\"elementMenu\" id=\"userId\" name=\"userId\" style=\"width:270px;height:29px;\" title=\"Choose User\">";
		$html.= "<option value=\"00000\">-- PLEASE SELECT USER --</option>";
		
		$query = $this->koneksi->mysqlQuery("SELECT * FROM login WHERE deletests=0 ORDER by userfullnm ASC");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$html.="<option value=\"".$row['userid']."\" ".$sel.">".$row['userfullnm']."</option>";
		}
		
		$html.= "</select>";
		
		return $html;
	}
	
	function menuUserSubCustom_Superior()
	{
		$html = "";
		$html.= "<select class=\"elementMenu\" id=\"userId\" name=\"userId\" style=\"width:270px;height:29px;\" title=\"Choose User\">";
		$html.= "<option value=\"00000\">-- PLEASE SELECT --</option>";
		
		$query = $this->koneksi->mysqlQuery("SELECT * FROM login WHERE subcustom='N' AND deletests=0 ORDER by userfullnm ASC");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$html.="<option value=\"".$row['userid']."\" ".$sel.">".$row['userfullnm']."</option>";
		}
		
		$html.= "</select>";
		
		return $html;
	}
	
	function menuUserSubCustom_Subordinate($userIdSelect)
	{
		$html = "";
		$html.= "<select class=\"elementMenu\" id=\"userIdSub\" name=\"userIdSub\" style=\"width:270px;height:29px;\" title=\"Choose User\">";
		$html.= "<option value=\"00000\">-- PLEASE SELECT --</option>";
		
		$query = $this->koneksi->mysqlQuery("SELECT * FROM login WHERE deletests=0 ORDER by userfullnm ASC");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			if($userIdSelect != $row['userid'])
			{
				$cariSub_userid = $this->cariSub_userid($userIdSelect, $row['userid']);
				if($cariSub_userid == "KOSONG")
				{
					$html.="<option value=\"".$row['userid']."\" ".$sel.">".$row['userfullnm']."</option>";
				}
			}
		}
		
		$html.= "</select>";
		
		return $html;
	}
	
	function cariSub_userid($userIdSelect, $userIdSub)
	{
		$query = $this->koneksi->mysqlQuery("SELECT * FROM tblsubcustom WHERE userid='".$userIdSelect."' AND sub_userid='".$userIdSub."' AND deletests=0");
		$jmlRow = $this->koneksi->mysqlNRows($query);
		$nilai = "KOSONG";
		if($jmlRow > 0)
		{
			$nilai = "ADA";
		}
		return $nilai;
	}
	
	function empnoAdaTidakTblAuthor($empNo, $ideFold)
	{
		$query = $this->koneksi->mysqlQuery("SELECT empno FROM tblauthorfold WHERE empno='".$empNo."' AND idefold = ".$ideFold." AND deletests=0;");
		$jmlRow = $this->koneksi->mysqlNRows($query);
		
		$nilai = "KOSONG";
		if($jmlRow > 0)
		{
			$nilai = "ADA";
		}
		return $nilai;
	}
	
	function atasanEmp($empNo, $cPublic)
	{
		$bossEmpno1 = $this->detilEmp($empNo, "bossempno");
		$bossEmpno2 = $this->detilEmp($bossEmpno1, "bossempno");
		$bossEmpno3 = $this->detilEmp($bossEmpno2, "bossempno");
		$bossEmpno4 = $this->detilEmp($bossEmpno3, "bossempno");
		$bossEmpno5 = $this->detilEmp($bossEmpno4, "bossempno");
		$bossEmpno6 = $this->detilEmp($bossEmpno5, "bossempno");
		$bossEmpno7 = $this->detilEmp($bossEmpno6, "bossempno");
		$bossEmpno8 = $this->detilEmp($bossEmpno7, "bossempno");
		$bossEmpno9 = $this->detilEmp($bossEmpno8, "bossempno");
		$bossEmpno10 = $this->detilEmp($bosEempno9, "bossempno");
		
		$atasan1 = $cPublic->jikaKosongMaka($bossEmpno1, "", "-");
		$atasan2 = $cPublic->jikaKosongMaka($bossEmpno2, "", "-");
		$atasan3 = $cPublic->jikaKosongMaka($bossEmpno3, "", "-");
		$atasan4 = $cPublic->jikaKosongMaka($bossEmpno4, "", "-");
		$atasan5 = $cPublic->jikaKosongMaka($bossEmpno5, "", "-");
		$atasan6 = $cPublic->jikaKosongMaka($bossEmpno6, "", "-");
		$atasan7 = $cPublic->jikaKosongMaka($bossEmpno7, "", "-");
		$atasan8 = $cPublic->jikaKosongMaka($bossEmpno8, "", "-");
		$atasan9 = $cPublic->jikaKosongMaka($bossEmpno9, "", "-");
		$atasan10 = $cPublic->jikaKosongMaka($bossEmpno10, "", "-");
		
		$nilai = ""; 
		$nilai = $atasan1.$atasan2.$atasan3.$atasan4.$atasan5.$atasan6.$atasan7.$atasan8.$atasan9.$atasan10;
		// hasil nilai berupa empno 
		return $nilai;
	}
	
	function bawahanEmp($bossEmpNo)
	{
		if($bossEmpNo == "00625" || $bossEmpNo == "00425")
		{
			$bossEmpNo2 = "00625-00425-";
			$urutanPertama = 2;
		}
		else
		{
			$bossEmpNo2 = $bossEmpNo;
			$urutanPertama = 1;
		}
		
		$bawahan1 = $this->cariBawahanLangsung($bossEmpNo2, $urutanPertama);
		$bawahan2 = $this->cariBawahanLangsung($bawahan1, 2);
		$bawahan3 = $this->cariBawahanLangsung($bawahan2, 3);
		$bawahan4 = $this->cariBawahanLangsung($bawahan3, 4);
		$bawahan5 = $this->cariBawahanLangsung($bawahan4, 5);
		$bawahan6 = $this->cariBawahanLangsung($bawahan5, 6);
		$bawahan7 = $this->cariBawahanLangsung($bawahan6, 7);
		$bawahan8 = $this->cariBawahanLangsung($bawahan7, 8);
		$bawahan9 = $this->cariBawahanLangsung($bawahan8, 9);
		$bawahan10 = $this->cariBawahanLangsung($bawahan9, 10);
		
		$nilai = "";
		$nilai = $bawahan1.$bawahan2.$bawahan3.$bawahan4.$bawahan5.$bawahan6.$bawahan7.$bawahan8.$bawahan9.$bawahan10;
		
		$nilai2 = "";
		$explodee = explode("-", $nilai);
		$jmlExplodee = count(explode("-", $nilai));
		$a = 1;
		for($i=0; $i<=$jmlExplodee; $i++)
		{
			//$nilai2.= $a++." | ".$this->detilEmp($explodee[$i], "nama")."<br>";
			$nilai2.= $explodee[$i]."-";
		}
		// hasil nilai berupa empno
		return $nilai2;
	}
	
	function bawahanEmpPunyaLogin($bossEmpNo)
	{
		if($bossEmpNo == "00625" || $bossEmpNo == "00425")
		{
			$bossEmpNo2 = "00625-00425-";
			$urutanPertama = 2;
		}
		else
		{
			$bossEmpNo2 = $bossEmpNo;
			$urutanPertama = 1;
		}
		
		$bawahan1 = $this->cariBawahanLangsung($bossEmpNo2, $urutanPertama);
		$bawahan2 = $this->cariBawahanLangsung($bawahan1, 2);
		$bawahan3 = $this->cariBawahanLangsung($bawahan2, 3);
		$bawahan4 = $this->cariBawahanLangsung($bawahan3, 4);
		$bawahan5 = $this->cariBawahanLangsung($bawahan4, 5);
		$bawahan6 = $this->cariBawahanLangsung($bawahan5, 6);
		$bawahan7 = $this->cariBawahanLangsung($bawahan6, 7);
		$bawahan8 = $this->cariBawahanLangsung($bawahan7, 8);
		$bawahan9 = $this->cariBawahanLangsung($bawahan8, 9);
		$bawahan10 = $this->cariBawahanLangsung($bawahan9, 10);
		
		$nilai = "";
		$nilai = $bawahan1.$bawahan2.$bawahan3.$bawahan4.$bawahan5.$bawahan6.$bawahan7.$bawahan8.$bawahan9.$bawahan10;
		
		$nilai2 = "";
		$explodee = explode("-", $nilai);
		$jmlExplodee = count(explode("-", $nilai));
		$a = 1;
		for($i=0; $i<=$jmlExplodee; $i++)
		{
			$userIdBawahanPunyaLogin = $this->detilLoginByEmpno($explodee[$i], "userid");
			if($userIdBawahanPunyaLogin != "")
			{
				$nilai2.= $explodee[$i]."-";
			}
		}
		// hasil nilai berupa empno
		return $nilai2;
	}
	
	function bawahanUserIdByBosEmpno($bossEmpNo)
	{
		if($bossEmpNo == "00625" || $bossEmpNo == "00425")
		{
			$bossEmpNo2 = "00625-00425-";
			$urutanPertama = 2;
		}
		else
		{
			$bossEmpNo2 = $bossEmpNo;
			$urutanPertama = 1;
		}
		
		$bawahan1 = $this->cariBawahanLangsung($bossEmpNo2, $urutanPertama);
		$bawahan2 = $this->cariBawahanLangsung($bawahan1, 2);
		$bawahan3 = $this->cariBawahanLangsung($bawahan2, 3);
		$bawahan4 = $this->cariBawahanLangsung($bawahan3, 4);
		$bawahan5 = $this->cariBawahanLangsung($bawahan4, 5);
		$bawahan6 = $this->cariBawahanLangsung($bawahan5, 6);
		$bawahan7 = $this->cariBawahanLangsung($bawahan6, 7);
		$bawahan8 = $this->cariBawahanLangsung($bawahan7, 8);
		$bawahan9 = $this->cariBawahanLangsung($bawahan8, 9);
		$bawahan10 = $this->cariBawahanLangsung($bawahan9, 10);
		
		$nilai = "";
		$nilai = $bawahan1.$bawahan2.$bawahan3.$bawahan4.$bawahan5.$bawahan6.$bawahan7.$bawahan8.$bawahan9.$bawahan10;
		
		$nilai2 = "";
		$explodee = explode("-", $nilai);
		$jmlExplodee = count(explode("-", $nilai));
		$a = 1;
		for($i=0; $i<=$jmlExplodee; $i++)
		{
			//$nilai2.= $a++." | ".$this->detilEmp($explodee[$i], "nama")."<br>";
			//$nilai2.= $explodee[$i]."-";
			$userIdBawahanPunyaLogin = $this->detilLoginByEmpno($explodee[$i], "userid");
			if($userIdBawahanPunyaLogin != "")
			{
				$nilai2.=$userIdBawahanPunyaLogin."-";
			}
		}
		// hasil nilai berupa userid
		return $nilai2;
	}
	
	function cariBawahanLangsung($bossEmpNo, $urutan)
	{
		$nilai = "";
		
		$expBossEmpno = explode("-", $bossEmpNo);
		$jmlBossEmpno = count(explode("-", $bossEmpNo));
		for($i=0; $i<=($jmlBossEmpno-$urutan); $i++)
		{
			$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT empno, nama FROM dbo.tblMstEmp WHERE (stsresign = 0) AND (kdcmp = 01 OR kdcmp = 02) AND (YEAR(tglkeluar) = '1900 ') AND (deletests = 0) AND bossempno='".$expBossEmpno[$i]."' ORDER BY nama");
			while($row = $this->koneksiOdbc->odbcFetch($query))
			{
				$nilai.=$row['empno']."-";
			}
		}

		return $nilai;
	}
	
	function cariAtasanLangsung($empNoSubordinate)
	{
		$nilai = "";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT TOP (1) bossempno FROM tblMstEmp WHERE (stsresign = 0) AND (kdcmp = 01 OR kdcmp = 02) AND (YEAR(tglkeluar) = '1900') AND (deletests = 0) AND (empno = '".$empNoSubordinate."')");
		$row = $this->koneksiOdbc->odbcFetch($query);
		$nilai = $row['bossempno'];	
		return $nilai;	
	}
	
	function detilLoginByEmpno($empNo, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM login WHERE empno='".$empNo."' AND ACTIVE='Y' AND deletests=0");
		$row = $this->koneksi->mysqlFetch($query);
		$isi = $row[$field];
		
		return $isi;
	}
	
	function detilEmp($empNo, $field)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT ".$field." FROM dbo.tblMstEmp WHERE (stsresign = 0) AND (kdcmp = 01 OR kdcmp = 02) AND (YEAR(tglkeluar) = '1900 ') AND (deletests = 0) AND empno='".$empNo."' ORDER BY nama");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row[$field];
	}
	
	function detilTblEmpGen($empNo, $field)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT  TOP (1) empno, eftivedt, kdcmp, kddiv, kddept, kdjabatan, kdpangkat, addusrdt, updusrdt, deletests, uniquekey FROM dbo.tblEmpGen WHERE (empno = '".$empNo."') AND deletests = 0 ORDER BY eftivedt DESC");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row[$field];
	}
	
	function detilDiv($kdDiv, $field)
	{	// field yang ada kddiv, nmdiv, divhead, deputy
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT ".$field." FROM dbo.tblmstdiv WHERE kddiv='".$kdDiv."' AND deletests=0");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row[$field];
	}
	
	function detilDept($kdDept, $field)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT ".$field." FROM dbo.tblmstdept WHERE kddept='".$kdDept."' AND deletests=0");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row[$field];
	}
	
	function detilJabatan($kdJab, $field)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT ".$field." FROM dbo.tblmstjabatan WHERE kdjabatan='".$kdJab."' AND deletests=0");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row[$field];
	}
	
	function detilPosition($kdPos, $field)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT ".$field." FROM dbo.tblmstpos WHERE kdposition='".$kdPos."' AND deletests=0");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row[$field];
	}
	
	function detilPangkat($kdPos, $field)// nmpangkat, gol, deletests
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT ".$field." FROM dbo.tblmstpgkt WHERE kdpangkat='".$kdPos."' AND deletests=0");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row[$field];
	}
	
	function detilCompany($kdCmp, $field)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT ".$field." FROM dbo.tblmstcmp WHERE kdcmp='".$kdCmp."' AND deletests=0");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row[$field];
	}
	
	function detilSubCustom($idCustomSub, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tblsubcustom WHERE idcustomsub='".$idCustomSub."' AND deletests=0");
		$row = $this->koneksi->mysqlFetch($query);
		$isi = $row[$field];
		
		return $isi;
	}
	
	function detilSubCustomByUser($userId, $userIdSub, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tblsubcustom WHERE userid='".$userId."' AND sub_userid='".$userIdSub."' AND deletests=0");
		$row = $this->koneksi->mysqlFetch($query);
		$isi = $row[$field];
		
		return $isi;
	}
	
	// data diambil dari Database andhikaportal
	function menuDivision($kdDiv, $dis)
	{
		$html = "";
		$html.= "<select class=\"elementMenu\" id=\"divisi\" name=\"divisi\" title=\"Choose Division Name\" ".$dis." style=\"width:99%\">";
		$html.= "<option value=\"000\">-- PLEASE SELECT --</option>";
		
		$query = $this->koneksi->mysqlQuery("SELECT kddiv, nmdiv FROM tblmstdiv WHERE (deletests = 0) ORDER BY nmdiv ASC");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$sel = "";
			if($kdDiv == $row['kddiv'])
			{
				$sel = "selected=\"selected\"";
			}
			$html.="<option value=\"".$row['kddiv']."\" ".$sel.">".$row['nmdiv']."</option>";
		}
		$html.= "</select>";
		
		return $html;
	}
	
	function gol($empno)//cari Golongan dari Employee Number
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "DECLARE @cGol CHAR(3)
				EXEC pGetGol '".$empno."', @cGol OUTPUT
				SELECT @cGol AS cGol");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row['cGol'];
	}

	function menuDivisionHrSys()
	{
		$html = "";

		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT kddiv,nmdiv FROM dbo.tblmstdiv WHERE deletests = 0 ORDER BY nmdiv ASC");
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$html.="<option value=\"".$row['kddiv']."\">".$row['nmdiv']."</option>";
		}
		
		return $html;
	}


}