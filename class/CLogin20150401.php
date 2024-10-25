<?php
class CLogin extends mysqlResult
{
	function CLogin($koneksiMysql)
	{
		$this->koneksi = $koneksiMysql;
	}
	
	function cekLogin($post, $cHistory, $cPublic)
	{
		$usernamePost = stripslashes( $post['username'] );
		$passwordPost = stripslashes( $post['password']);
		$usernamePost = mysql_real_escape_string($usernamePost);
		$passwordPost = mysql_real_escape_string($this->enkrip($passwordPost));
		
		$sql = "SELECT * FROM login WHERE username='".$usernamePost."' AND userpass='".$passwordPost."' AND active='Y' AND deletests=0 LIMIT 1";
		$query = $this->koneksi->mysqlQuery($sql);
		$row = $this->koneksi->mysqlFetch($query);
		$jmlRow = $this->koneksi->mysqlNRows($query);
		
		if($jmlRow != '0')
		{
			$this->simpanLastLogin($row['userid'], $cPublic);
			$cHistory->cekFile($row['userid'],"Berhasil login");
			
			session_register("userIdSession");
			$_SESSION['userIdSession'] = $row['userid'];
			
			//$_SESSION['start'] = time();
			//$_SESSION['expire'] = $_SESSION['start'] + (20);
			
			/*$_SESSION['logged_in'] = true; //set you've logged in
			$_SESSION['last_activity'] = time(); //your last activity was now, having logged in.
			$_SESSION['expire_time'] = 30;*/
			$_SESSION['timestamp']=time();

			
			header("location:index.php");
			exit;
		}
		else
		{
			header("location:index.php?dologin=0");
			exit;
		}
	}
	
	function enkrip($str)
	{
		return md5($str);
	}
	
	function simpanLastLogin($userIdLogin, $cPublic)
	{
		$sql = "UPDATE login SET status = '1', lastlogin = '".$cPublic->waktuSek()."' WHERE userid = '".$userIdLogin."' LIMIT 1";
		$this->koneksi->mysqlQuery($sql);
	}
	
	function simpanLastLogout($userIdLogin, $cPublic)
	{
		$sql = "UPDATE login SET status = '0', lastlogout = '".$cPublic->waktuSek()."' WHERE userid = '".$userIdLogin."' LIMIT 1";
		$this->koneksi->mysqlQuery($sql);
	}
	
	function detilLogin($userId, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM login WHERE userid='".$userId."' AND ACTIVE='Y' AND deletests=0");
		$row = $this->koneksi->mysqlFetch($query);
		$isi = $row[$field];
		
		return $isi;
	}
	
	function detilLoginByEmpno($empNo, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM login WHERE empno='".$empNo."' AND ACTIVE='Y' AND deletests=0");
		$row = $this->koneksi->mysqlFetch($query);
		$isi = $row[$field];
		
		return $isi;
	}
	
	function cekPunyaLogin($CKoneksi, $empNo)
	{
		$query = $CKoneksi->mysqlQuery("SELECT empno FROM login WHERE empno='".$empNo."' AND ACTIVE='Y' AND deletests=0");
		$jmlRow = $CKoneksi->mysqlNRows($query);
		
		$nilai = "ada";
		if($jmlRow == 0)
		{
			$nilai = "kosong";
		}
		return $nilai;
	}
	
	function logout($cPublic, $cHistory)
	{
		session_start();
		
		$this->simpanLastLogout($_SESSION['userIdSession'], $cPublic);
		$cHistory->cekFile($_SESSION['userIdSession'],"Berhasil logout");
		if(isset($_SESSION['userIdSession']))
  		unset($_SESSION['userIdSession']);
  
		header("location:index.php");
		exit();
	}
	
	function sessionExpired($CPublic, $CHistory)
	{
		session_start();
		
		$this->simpanLastLogout($_SESSION['userIdSession'], $CPublic);
		$CHistory->cekFile($_SESSION['userIdSession'],"Berhasil logout karena session expired");
		if(isset($_SESSION['userIdSession']))
  		unset($_SESSION['userIdSession']);
  
		header("location:index.php?aksi=sessionExpired");
	}
	
	function encode($str)
	{
		return base64_encode($str);
	}
	
	function decode($str)
	{
		return base64_decode($str);
	}
}