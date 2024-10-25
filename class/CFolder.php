<?php
class CFolder
{
	var $tabel;
	
	function CFolder($mysqlkoneksi)
	{
		$this->koneksi = $mysqlkoneksi;
		//$this->cpublic = $public;
		$tabel = "";
	}
	
	function detilFold($ide, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tblfolder WHERE ide='".$ide."' AND deletests=0 LIMIT 1 ");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function detilAuthorFold($idAuthorFold, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tblauthorfold WHERE idauthorfold=".$idAuthorFold." AND deletests=0 LIMIT 1 ");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	function detilAuthorFoldByIdFold($ideFold, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tblauthorfold WHERE idefold=".$ideFold." AND deletests=0 LIMIT 1 ");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function detilFoldByRef($idFoldRef, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tblfolder WHERE idfoldref=".$idFoldRef." AND deletests=0 LIMIT 1 ");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function detilFoldByIdFold($idFold, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tblfolder WHERE idfold='".$idFold."' AND deletests=0 LIMIT 1 ");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function jmlFolder($idFold)
	{
		$query = $this->koneksi->mysqlQuery("SELECT COUNT(ide) AS hasil FROM tblfolder WHERE idfoldref='".$idFold."' AND deletests=0");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['hasil'];
	}
	
	function jmlFolderByUserId($userId)
	{
		$query = $this->koneksi->mysqlQuery("SELECT COUNT(ide) AS hasil FROM tblfolder WHERE folderowner=".$userId." AND deletests=0;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['hasil'];
	}
	
	function cekFolderPublic($userId)
	{
		$query = $this->koneksi->mysqlQuery("SELECT COUNT(ide) AS hasil FROM tblfolder WHERE folderowner=".$userId." AND deletests=0;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['hasil'];
	}
	
	function menuFoldLevelOne($userId)
	{
		$html = "";
		$html.= "<select class=\"elementMenu\" id=\"idFold\" name=\"idFold\" style=\"width:270px;height:29px;\" title=\"Choose Folder\">";
		$html.= "<option value=\"00000\">-- PLEASE SELECT FOLDER LEVEL ONE --</option>";
		
		$query = $this->koneksi->mysqlQuery("SELECT * FROM tblfolder WHERE foldsub='1' AND idfoldref='' AND folderowner=".$userId." deletests=0 ORDER BY namefold ASC");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$html.="<option value=\"".$row['idfold']."\">".$row['namefold']."</option>";
		}
		
		$html.= "</select>";
		
		return $html;
	}
	
	function menuEmpOtherShare($userEmpNo, $userId, $CLogin)
	{
		$html = "";
		$html.= "<select class=\"elementMenu\" id=\"userid\" name=\"userid\" style=\"width:250px;\" title=\"Choose Name\">";
		$html.= "<option value=\"00000\">-- PLEASE SELECT --</option>";
		$query = $this->koneksi->mysqlQuery("SELECT idauthorfold, empno, nama, idefold, folderowner FROM tblauthorfold WHERE (empno=".$userEmpNo." OR empno=99999) AND deletests=0 GROUP BY folderowner");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$sel = "";
			if($userId == $row['folderowner'])
			{
				$sel = "selected=\"selected\"";
			}
			
			$namaEmp = $CLogin->detilLogin($row['folderowner'], "userfullnm"); // nama dari pemilik folder
			$empNoOwner = $CLogin->detilLogin($row['folderowner'], "empno"); // empno dari pemilik folder
			if($empNoOwner != $userEmpNo) // jika yang folder yang dishared bukan untuk pemilik folder sendiri
			{
				if($namaEmp != "") //jika nama dari pemilik folder berstatus aktif sebagai user login
				{
					$html.="<option value=\"".$row['folderowner']."\" ".$sel.">".$namaEmp."</option>";
				}
				
			}
		}
		
		$html.= "</select>";
		
		return $html;
	}
	
	function menuEmpOwnShare($userId)
	{
		$html = "";
		$html.= "<select class=\"elementMenu\" id=\"empNoShared\" name=\"empNoShared\" style=\"width:250px;\" title=\"Choose Name\">";
		$html.= "<option value=\"00000\">-- PLEASE SELECT --</option>";
		$html.= "<option value=\"99999\">ALL</option>";
		
		$query = $this->koneksi->mysqlQuery("SELECT idauthorfold, empno, nama, idefold, folderowner FROM tblauthorfold WHERE folderowner=".$userId." AND deletests=0 GROUP BY nama ASC");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			if($row['empno'] != "99999") // folder yang diberi authorisasi All jangan ditapilkan di menu karena sudah ada diatas setelah "Please Select"
			{
				$html.="<option value=\"".$row['empno']."\" ".$sel.">".$row['nama']."</option>";
			}
			
		}
		
		$html.= "</select>";
		
		return $html;
	}
	
	function cariIdeFoldDiAuthor($userEmpNo, $folderOwner)
	{
		$html = "";
		$query = $this->koneksi->mysqlQuery("SELECT idefold FROM tblauthorfold WHERE empno=".$userEmpNo." AND folderowner=".$folderOwner." AND deletests=0");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$html.= $row['idefold']."-";
		}
		return $html;
	}
	
	function idFoldLast($foldSub, $idFoldRef)
	{
		$nilai = "";
		//$query = $this->koneksi->mysqlQuery("SELECT ide, idfold AS idfoldlast FROM tblfolder WHERE foldsub='".$foldSub."' AND idfoldref='".$idFoldRef."' AND deletests=0 ORDER BY ide DESC LIMIT 1");
		$query = $this->koneksi->mysqlQuery("SELECT ide, idfold AS idfoldlast FROM tblfolder WHERE foldsub='".$foldSub."' AND idfoldref='".$idFoldRef."' AND deletests=0 ORDER BY REPLACE(idfold, '.', '')+0 DESC LIMIT 1;");
		$row = $this->koneksi->mysqlFetch($query);
		$isi = $row['idfoldlast'];

		$idFoldLastExp = explode(".",$isi);
		$pjgtextidfoldlast = count(explode(".",$isi));
		
		if($isi == "")
		{
			if($pjgtextidfoldlast == 1)
			{
				if($foldSub == "1")
				{
					$idFold = "1";
				}
				else
				{
					$idFold = $idFoldRef.".1";
				}
			}
			elseif($pjgtextidfoldlast != 1)
			{
				$idFold = $idFoldRef.".1";
			}
			$nilai = $idFold;
		}
		else
		{
			for($i=1; $i<=$pjgtextidfoldlast; $i++)
			{
				if($i == $pjgtextidfoldlast)
				{
					$idFold = $idFoldLastExp[$i-1] + 1;
				}
				else
				{
					$idFold = $idFoldLastExp[$i-1].".";
				}
				
				$AllIdFold.= $idFold;
			}
			$nilai = $AllIdFold;
		}
		
		return $nilai;	
	}
	
	function folderNameMap($idFoldDipilih, $foldSubDipilih)
	{
		$nilai = "";

		$idFoldExp = explode(".",$idFoldDipilih);
		$pjgExpIdFoldExp = count(explode(".",$idFoldDipilih));	
		
		$panah = "";
		if($idFoldDipilih != "")
		{
			$panah = ">";
		}
		
		for($i=1;$i<=$pjgExpIdFoldExp;$i++)
		{
			$idFold = $this->potongIdFold($idFoldDipilih, $i);
			
			$ide = $this->detilFoldByIdFold($idFold, "ide");
			$tipeKonten = $this->detilFoldByIdFold($idFold, "tipekonten");
			$foldSubSek = $this->detilFoldByIdFold($idFold, "foldsub");
			$foldSubSet = $foldSubSek+1;
			
			$nmFold = $this->detilFoldByIdFold($idFold, "namefold");
			$AllIdFold .= "&nbsp;&nbsp;<span class=\"mapTeksLvl\" onClick=\"parent.pilihTeksFoldName('".$ide."', '".$foldSubSet."', '".$idFold."', '".$tipeKonten."')\">".$nmFold."&nbsp;&nbsp;".$panah."</span> ";
		}
		$nilai = $AllIdFold;
		
		return $nilai;
	}
	
	function sharedFolderNameMap($idAuthorFold, $idFoldDipilih, $foldSubDipilih)
	{
		$nilai = "";
		
		$idFoldAuthor = $this->detilAuthorFold($idAuthorFold, "idefold");
		$foldSubPertamaPilih = $this->detilFold($idFoldAuthor, "foldsub"); // urutan atau foldsub dari idefold yang pertama dipilih atau expand pertama kali

		$pjgExpIdFoldExp = count(explode(".",$idFoldDipilih));	
		
		$panah = "";
		if($idFoldDipilih != "")
		{
			$panah = ">";
		}
		
		for($i=$foldSubPertamaPilih;$i<=$pjgExpIdFoldExp;$i++) 
		{
			$idFold = $this->potongIdFold($idFoldDipilih, $i);
			
			$ide = $this->detilFoldByIdFold($idFold, "ide");
			$tipeKonten = $this->detilFoldByIdFold($idFold, "tipekonten");
			$foldSubSek = $this->detilFoldByIdFold($idFold, "foldsub");
			$foldSubSet = $foldSubSek+1;
			
			$nmFold = $this->detilFoldByIdFold($idFold, "namefold");
			$AllIdFold .= "&nbsp;&nbsp;<span class=\"mapTeksLvl\" onClick=\"parent.pilihTeksFoldName('".$ide."', '".$foldSubSet."', '".$idFold."', '".$tipeKonten."', '".$idAuthorFold."')\">".$nmFold."&nbsp;&nbsp;".$panah."</span> ";
		}
		$nilai = $AllIdFold;
		
		return $nilai;
	}
	
	function subordinateFolderNameMap($idFoldDipilih, $foldSubDipilih)
	{
		$nilai = "";

		$idFoldExp = explode(".",$idFoldDipilih);
		$pjgExpIdFoldExp = count(explode(".",$idFoldDipilih));	
		
		$panah = "";
		if($idFoldDipilih != "")
		{
			$panah = ">";
		}
		
		$home = "&nbsp;&nbsp;<span class=\"mapTeksLvl\" onClick=\"parent.pilihTeksFoldName('', '1', '', 'folder')\">Home&nbsp;&nbsp;></span>";
		for($i=1;$i<=$pjgExpIdFoldExp;$i++)
		{
			$idFold = $this->potongIdFold($idFoldDipilih, $i);
			
			$ide = $this->detilFoldByIdFold($idFold, "ide");
			$tipeKonten = $this->detilFoldByIdFold($idFold, "tipekonten");
			$foldSubSek = $this->detilFoldByIdFold($idFold, "foldsub");
			$foldSubSet = $foldSubSek+1;
			
			$nmFold = $this->detilFoldByIdFold($idFold, "namefold");
			$AllIdFold .= "&nbsp;&nbsp;<span class=\"mapTeksLvl\" onClick=\"parent.pilihTeksFoldName('".$ide."', '".$foldSubSet."', '".$idFold."', '".$tipeKonten."')\">".$nmFold."&nbsp;&nbsp;".$panah."</span>";
		}
		$nilai = $home.$AllIdFold;
		
		return $nilai;
	}
	
	function potongIdFold($idFold, $idFoldDiinginkan)
	{
		$idFoldExp = explode(".",$idFold);
		$pjgExpIdFoldExp = count(explode(".",$idFold));// setelah dipisahkan oleh titik (.), hitung ada berapa jumlah angka
		
		for($i=0;$i<=$pjgExpIdFoldExp;$i++) 
		{
			if($i == $idFoldDiinginkan) // jika sudaj sesuai dengan idFold yang diingin kan berdasarkan  level
			{
				$idFoldd.= $idFoldExp[$i];
				break;
			}
			else
			{
				if($i==0)
				{
					$idFoldd.= $idFoldExp[$i];
				}
				else
				{
					$idFoldd.= ".".$idFoldExp[$i];
				}
			}
			
			$AllIdFold = $idFoldd;
		}
		
		return $AllIdFold;
	}
	
	function dirSize($dir)
    {
		@$dh = opendir($dir);
		$size = 0;
		while ($file = @readdir($dh))
		{
		  if ($file != "." and $file != "..") 
		  {
			$path = $dir."/".$file;
			if (is_dir($path))
			{
			  $size += dirsize($path); // recursive in sub-folders
			}
			elseif (is_file($path))
			{
			  $size += filesize($path); // add file
			}
		  }
		}
		@closedir($dh);
		return $size;
    }
	
	function display_size($size) 
	{ // Function 2
		$sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		if ($retstring === null) 
		{ 
			$retstring = '%01.2f %s'; 
		}
		$lastsizestring = end($sizes);
		foreach ($sizes as $sizestring) 
		{
			if ($size < 1024) { break; }
			if ($sizestring != $lastsizestring) { $size /= 1024; }
		}
		if ($sizestring == $sizes[0]) 
		{ $retstring = '%01d %s'; } // Bytes aren't normally fractional
		return sprintf($retstring, $size, $sizestring);
	}
}
?>