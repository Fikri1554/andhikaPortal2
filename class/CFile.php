<?php
class CFile
{
	var $tabel;
	
	function CFile($mysqlkoneksi)
	{
		$this->koneksi = $mysqlkoneksi;
		//$this->cpublic = $public;
		$tabel = "";
	}
	
	function detilFile($ide, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM mstdoc WHERE ide=".$ide." AND deletests=0 LIMIT 1 ");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function idDocLast($idFold)
	{
		$query = $this->koneksi->mysqlQuery("SELECT MAX(iddoc) AS iddoclast FROM mstdoc WHERE idfold='".$idFold."' AND deletests=0");
		$row = $this->koneksi->mysqlFetch($query);
		$isi = $row['iddoclast'];
		if($isi == "")
		{
			$iddoc = "1";
		}
		else
		{
			$iddoc = $isi+1;
		}
		
		return $iddoc;
	}
	
	function jmlFile($idFold)
	{
		$query = $this->koneksi->mysqlQuery("SELECT COUNT(ide) AS hasil FROM mstdoc WHERE idfold='".$idFold."' AND deletests=0");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['hasil'];
	}
	
	function fileExtension()
	{
		$tabel = "";
		$query = $this->koneksi->mysqlQuery("SELECT nmekstension FROM tblekstension WHERE status='Y' AND valid = 'Y'");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$tabel.= $row['nmekstension'].",";
		}
		
		return $tabel;
	}
	
	function fileExtensionNValid()
	{
		$tabel = "";
		$query = $this->koneksi->mysqlQuery("SELECT nmekstension FROM tblekstension WHERE status='Y' AND valid = 'N'");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$tabel.= $row['nmekstension'].",";
		}
		
		return $tabel;
	}
	
	function detilExtension($nmEkts, $field)
	{
		$tabel = "";
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tblekstension WHERE nmekstension='".$nmEkts."' AND status='Y' LIMIT 1;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
}