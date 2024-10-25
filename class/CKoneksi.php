<?php
class mysqlResult
{
	var $localHost;
	var $userHost;
	var $passHost;
	var $dbHost;
	var $koneksiId = 0;
	var $pilDB = 0;
	var $koneksiSts = 0;
	var $brs = 0;
	
	var $mResult;
	var $strSQL;
	
	function mysqlResult($localhost,$user,$pass,$db)
	{
		$this->localhost = $localhost;
		$this->userHost = $user;
		$this->passHost = $pass;
		$this->dbHost = $db;	
	}
	
	function bukaKoneksi()
	{
		$this->koneksiId = mysql_connect($this->localhost,$this->userHost,$this->passHost);
		if(!$this->koneksiId) die("Tidak ada Koneksi!!");
		if($this->dbHost != "")
			$this->pilihDB($this->dbHost);
		
		return $this->koneksiId;
	}
	
	function tutupKoneksi($link)
	{
		$this->koneksiSts = mysql_close($link);
		return $this->koneksiSts;
	}
	function mysqlQuery($result)
	{
		$this->mResult = mysql_query($result);
		if(!$this->mResult) {die(mysql_error());}
		
		return $this->mResult;
	}
	
	function pilihDB($db)
	{
		$pilDB = mysql_select_db($this->dbHost);
		if(!$pilDB)
		{
			echo "Tidak ada Database";
		}
		return $pilDB;
	}	
	
	function mysqlFetch($sql)
	{
		
		$this->strSQL = mysql_fetch_array($sql,MYSQL_ASSOC);
		
		return $this->strSQL;
	}
	
	function mysqlDSeek($sql,$brs)
	{
		$this->brs = $brs;
		$this->strSQL = mysql_data_seek($sql,$this->brs);
		
		return $this->strSQL;
	}
	
	function mysqlFSeek($sql,$brs)
	{
		$this->brs = $brs;
		$this->strSQL = mysql_field_seek($sql,$this->brs);
		
		return $this->strSQL;
	}
	
	function mysqlFRows($sql)
	{
		$this->strSQL = mysql_fetch_row($this->mResult = $sql);
		
		return $this->strSQL;
	}
	
	function mysqlFFields($sql)
	{
		$this->strSQL = mysql_fetch_field($this->mResult = $sql);
		
		return $this->strSQL;
	}
	
	function mysqlNRows($sql)
	{
		$this->strSQL = mysql_num_rows($this->mResult = $sql);
		
		return $this->strSQL;
	}
}

class odbcResult
{
	var $localHost;
	var $userHost;
	var $passHost;
	var $dbHost;
	var $koneksiId = 0;
	var $pilDB = 0;
	var $koneksiSts = 0;
	var $brs = 0;
	
	var $mResult;
	var $strSQL;
	
	function odbcResult($localhost,$user,$pass)
	{
		$this->localhost = $localhost;
		$this->userHost = $user;
		$this->passHost = $pass;
		$this->dbHost = $db;	
	}

	function cekConnect()
	{
		$this->cekConn = "";
		$this->koneksiId = odbc_connect($this->localhost,$this->userHost,$this->passHost);

		if(!$this->koneksiId)
		{
			$this->bukaKoneksi();
		}
		
		return $this->cekConn;
	}
	
	function bukaKoneksi()
	{
		$this->koneksiId = odbc_connect($this->localhost,$this->userHost,$this->passHost);
		if(!$this->koneksiId) die("Tidak ada Koneksi!!".$this->localhost);
		
		return $this->koneksiId;
	}
	
	function tutupKoneksi()
	{
		$this->koneksiSts = odbc_close($this->localhost,$this->userHost,$this->passHost);
		return $this->koneksiSts;
	}
	function odbcExec($koneksiId, $result)
	{
		$this->mResult = odbc_exec($koneksiId, $result);		
		if(!$this->mResult) {die(odbc_error());}
		
		return $this->mResult;
	}
	
	function odbcFetch($sql)
	{
		$this->strSQL = odbc_fetch_array($sql);
		
		return $this->strSQL;
	}
	
	function odbcNRows($sql)
	{
		$this->strSQL = odbc_num_rows($sql);
		
		return $this->strSQL;
	}	
}
?>