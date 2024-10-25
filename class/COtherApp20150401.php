<?php
class COtherApp
{
	var $tabel;
	
	function COtherApp($mysqlKoneksi, $CPublic, $dbName)
	{
		$this->koneksi = $mysqlKoneksi;
		$this->cPublic = $CPublic;
		$this->dbName = $dbName;
		$tabel = "";
	}
	
	function jmlOtherApp($userId)
	{
		$query = $this->koneksi->mysqlQuery("SELECT * FROM otherapp WHERE userid='".$userId."' AND deletests=0 ORDER by userfullnm ASC");
		$jmlRow = $this->koneksi->mysqlNRows($query);
		return $jmlRow;
	}
	
	//MENU YANG LAMA
	/*function menuOtherApp($userId)
	{
		$html = "";
		$query = $this->koneksi->mysqlQuery("SELECT * FROM otherapp WHERE userid='".$userId."' AND deletests=0 ORDER by appfullnm ASC");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$html.= "<li><a href=\"../".$row['nmapp']."/\">".$row['appfullnm']."</a></li>";
		}
		$html.= "<li><a href=\"../qhse/\">QHSE</a></li>";
		
		return $html;
	}*/
	
	function menuOtherApp($userId)
	{
		$html = "";
		$query = $this->koneksi->mysqlQuery("SELECT * FROM ".$this->dbName.".otherapp WHERE userid='".$userId."' AND deletests=0 ORDER by appfullnm ASC");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$html.= "<li><a href=\"../".$row['nmapp']."\"><span>".$row['appfullnm']."</span></a></li>";
		}
		
		return $html;
	}
	
	function detilOtherApp($idApp, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM otherapp WHERE idapp='".$idApp."' AND deletests=0 LIMIT 1;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
}
?>