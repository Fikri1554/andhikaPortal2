<?php
class CEmpl
{	
	function CEmpl($koneksiMysql)
	{
		$this->koneksi = $koneksiMysql;
	}
	function detilMstdoc($ide, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM empldoc WHERE ide=".$ide." AND deletests=0");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function detilMstform($idForm, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM emplform WHERE idform=".$idForm." AND deletests=0");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function detilMstformByName($nameForm, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM emplform WHERE namedoc='".$nameForm."' AND deletests=0");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function jmlMstformByIde($ide, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM emplform WHERE ide=".$ide." AND deletests=0");
		$jmlRow = $this->koneksi->mysqlNRows($query);
		
		return $jmlRow;
	}
	
	function fileExtension()
	{
		$tabel = "";
		$query = $this->koneksi->mysqlQuery("SELECT nmekstension FROM tblekstension WHERE status='Y'");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$tabel.= $row['nmekstension'].",";
		}
		
		return $tabel;
	}
	
	function copyDirectory($source, $dest, $excludeSvnFolders=true) 
	{
		$sourceHandle = opendir($source);
	 
		 if (!$sourceHandle) {
			 echo 'failed to copy directory: failed to open source ' . $source;
			 return false;
		}
		mkdir($dest);
	
		while ($file = readdir($sourceHandle)) 
		{	
			if($file!="." && $file!=".." && !is_dir($source."/".$file))
			{
				if ($file == '.' || $file == '..')
				continue;
				if ($excludeSvnFolders && $file == '.svn')
				continue;
				
				copy($source.'/'.$file,$dest.'/'.$file);
			}
		}
		closedir($sourceHandle);
	   
		return true;
	}
	
	function deleteDirectory($source, $excludeSvnFolders=true, $recusion=false) 
	{
	  $dir_handle = opendir($source);
	 
	  if (!$dir_handle)
		return false;
	 
	  while ($file = readdir($dir_handle)) {
		if ($file == '.' || $file == '..')
		  continue;
		if ($excludeSvnFolders && $file == '.svn')
		  continue;
	 
		if (!is_dir($source . '/' . $file)) {
		  unlink($source . '/' . $file);
		} else {
		  self::deleteDirectory($source . '/' . $file, $excludeSvnFolders, true);
		}
	  }
	 
	  closedir($dir_handle);
	 
	  //if ($recusion) {
		rmdir($source);
	  //}
	 
	  return true;
	}
	
	function urutanMinMax($field, $jenis, $ascDes)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM empldoc WHERE jenis='".$jenis."' AND deletests=0 ORDER BY urutan ".$ascDes." LIMIT 1");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function urutanSebSet($field, $jenis, $bsrKcl, $urutanSek, $ascDes)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM empldoc WHERE jenis='".$jenis."' AND deletests=0 AND urutan ".$bsrKcl." ".$urutanSek." ORDER BY urutan ".$ascDes." LIMIT 1");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function urutanFormMinMax($ide, $field, $ascDes)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM emplform WHERE ide=".$ide." AND deletests=0 ORDER BY urutan ".$ascDes." LIMIT 1");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function urutanFormSebSet($ide, $field, $bsrKcl, $urutanSek, $ascDes)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM emplform WHERE ide=".$ide." AND deletests=0 AND urutan ".$bsrKcl." ".$urutanSek." ORDER BY urutan ".$ascDes." LIMIT 1");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function urutanFormLuarMinMax($field, $ascDes)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM emplform WHERE deletests=0 ORDER BY urutanluar ".$ascDes." LIMIT 1");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function urutanFormLuarSebSet($field, $bsrKcl, $urutanSek, $ascDes)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM emplform WHERE deletests=0 AND urutanluar ".$bsrKcl." ".$urutanSek." ORDER BY urutanluar ".$ascDes." LIMIT 1");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
}
?>