<?php
class CSurvey
{
	function CSurvey($mysqlkoneksi)
	{
		$this->koneksi = $mysqlkoneksi;
		//$this->cpublic = $public;
		$tabel = "";
	}

	function result($CKoneksi, $field, $used)
	{
		$usedCon = "WHERE used='".$used."'";
		
		if($used == "")
		{
			$usedCon = "";
		}
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tblsurvey ".$usedCon."");
		$jmlRow = $CKoneksi->mysqlNRows($query);
		
		return $jmlRow;
	}
	
	function resultSkala($CKoneksi, $field, $skala)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tblsurvey WHERE used='Y' AND ".$field."='".$skala."'");
		$jmlRow = $CKoneksi->mysqlNRows($query);
		
		return $jmlRow;
	}
}
?>