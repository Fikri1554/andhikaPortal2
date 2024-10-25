<?php
class CData
{
	var $Ckoneksi;
	var $tabel;
	
	function CData($mysqlKoneksi)
	{
		$this->koneksi = $mysqlKoneksi;
		$tabel = "";
	}
	
	function detilData($idData, $namaKapal, $hdsn, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM datalaporan WHERE iddata='".$idData."' AND namakapal='".$namaKapal."' AND hdsn='".$hdsn."' AND deletests=0");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function detilInfo($idData, $namaKapal, $hdsn, $fields)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$fields." FROM datainfo WHERE iddata='".$idData."' AND namakapal='".$namaKapal."' AND hdsn='".$hdsn."' AND deletests=0;");
		$row = $this->koneksi->mysqlFetch( $query );
		
		return $row[$fields];
	}
	
	function detilPaper($idData, $namaKapal, $hdsn, $urutan, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM paperdescribe WHERE iddata = '".$idData."' AND namakapal='".$namaKapal."' AND hdsn='".$hdsn."' AND urutan = '".$urutan."' AND deletests=0");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function jmlPaperAktif($idData, $namaKapal, $hdsn)
	{
		$query = $this->koneksi->mysqlQuery("SELECT COUNT(iddata) AS jmlpaper FROM paperdescribe WHERE iddata='".$idData."' AND namakapal='".$namaKapal."' AND hdsn='".$hdsn."' AND deletests=0;");	
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['jmlpaper'];
	}
	
	function detilTblUserjns($userId, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tbluserjenis WHERE userid = '".$userId."';");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function rowColor($i,$warna1,$warna2)
	{
		if ($i % 2 != "0") # An odd row 
		{
			$rowColor = $warna1; 
		}
		else # An even row 
		{
			$rowColor = $warna2;
		}
		return $rowColor;
	}
}
?>