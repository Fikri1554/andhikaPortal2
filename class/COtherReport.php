<?php
class COtherReport
{	
	function COtherReport($koneksiMysql)
	{
		$this->koneksi = $koneksiMysql;
	}
	
	function jmlUser()
	{
		$query = $this->koneksi->mysqlQuery("SELECT userid FROM login WHERE deletests=0;");
		$jml = $this->koneksi->mysqlNRows($query);
		
		return $jml;
	}
	
	function jmlAct($ownerId, $from, $to)
	{
		$query = $this->koneksi->mysqlQuery("SELECT idactivity FROM tblactivity WHERE DATE(CONCAT(tahun,'/',bulan,'/', tanggal)) BETWEEN '".$from."' AND '".$to."' AND project='pheonwj' AND deletests=0 AND ownerid=".$ownerId.";");
		$jml = $this->koneksi->mysqlNRows($query);
		
		return $jml;
	}
	
	function jmlMbrDiv($kdDiv)
	{
		$query = $this->koneksi->mysqlQuery("SELECT userid FROM login WHERE kddiv=".$kdDiv.";");
		$jml = $this->koneksi->mysqlNRows($query);
		
		return $jml;
	}
	
	function pheonwj($userId, $fromDate, $toDate)
	{
		$jmlJamKerja = 0;
		$query = $this->koneksi->mysqlQuery("SELECT idactivity, fromtime, totime
					FROM tblactivity WHERE ownerid=".$userId." AND 
					DATE(CONCAT(tahun,'/',bulan,'/', tanggal)) BETWEEN '".$fromDate."' AND '".$toDate."'
					AND project='pheonwj' AND deletests=0 AND (bosapprove = 'Y' OR lockedit = 'Y') ORDER BY tahun, bulan, tanggal ASC;");
		/*if($userId == "00060")
		{
			$query = $this->koneksi->mysqlQuery("SELECT idactivity, fromtime, totime
					FROM tblactivity WHERE ownerid=".$userId." AND 
					DATE(CONCAT(tahun,'/',bulan,'/', tanggal)) BETWEEN '".$fromDate."' AND '".$toDate."'
					AND project='pheonwj' AND deletests=0 ORDER BY tahun, bulan, tanggal ASC;");
		}*/
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$jamKerja = $this->selisihJam($row['fromtime'],$row['totime'])."";
			$jmlJamKerja = $jmlJamKerja + $jamKerja ;
		}
		
		return $jmlJamKerja;
	}
	
	function selisihJam($waktuAwal, $waktuAkhir)
	{
		$jam1 = new DateTime($waktuAwal);
		$jam2 = new DateTime($waktuAkhir);
		$beda = $jam1->diff($jam2);
		
		$jam = $beda->format('%H');
		$menit = $beda->format('%i');
		$hMenit = floor($menit/60*10)/10;
		//$hMenit = $menit/60;
		$hitung = $jam+($menit/60);
		$selisih = floor($hitung*10)/10;
		
		return $selisih;
	}
	
	function lockActivity($idAct, $yN, $CHistory, $userIdLogin, $owner)
	{
		if($yN == "Y")
		{
			$lock = "Lock";
		}
		if($yN == "N")
		{
			$lock = "Unlock";
		}
		
		$this->koneksi->mysqlQuery("UPDATE tblactivity SET lockedit='".$yN."' WHERE idactivity = ".$idAct.";");
		$CHistory->updateLog2($userIdLogin, $lock." Daily Activity for PHE ONWJ Project(idactivity = <b>".$idAct."</b>, ownername = <b>".$owner."</b>)");
	}
	
	function belumLock($userId, $fromDate, $toDate)
	{
		$query = $this->koneksi->mysqlQuery("SELECT idactivity, fromtime, totime
					FROM tblactivity WHERE ownerid=".$userId." AND 
					DATE(CONCAT(tahun,'/',bulan,'/', tanggal)) BETWEEN '".$fromDate."' AND '".$toDate."'
					AND project='pheonwj' AND deletests=0 AND bosapprove = 'N' AND lockedit = 'N' ORDER BY tahun, bulan, tanggal ASC;");
		$jml = $this->koneksi->mysqlNRows($query);
		
		return $jml;
	}
	
	function sudahLockOrAprv($ownerId, $fromDate, $toDate)
	{
		$query = $this->koneksi->mysqlQuery("SELECT * FROM tblactivity WHERE ownerid = '".$ownerId."' 
					AND deletests=0 AND project = 'pheonwj' AND
					DATE(CONCAT(tahun,'/',bulan,'/',tanggal)) BETWEEN '2015/03/02' AND '2015/03/27'
					AND (bosapprove = 'Y' OR lockedit = 'Y') ORDER BY ownername ASC;");
		$jml = $this->koneksi->mysqlNRows($query);
		
		return $jml;
	}
}
?>