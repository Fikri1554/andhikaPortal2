<?php
class CAging
{
	function CAging($koneksiMysql, $CPublic)
	{
		$this->koneksi = $koneksiMysql;
		$this->CPublic = $CPublic;
	}
	
	function detilAging($userId, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM summaryaging WHERE userid = ".$userId." LIMIT 1;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}
	function insertSummary($userId, $company, $date, $dateEnd, $account)
	{
		$dateConv = $this->CPublic->convTglDB($date);
		$endDate = $this->CPublic->convTglDB($dateEnd);
		$delete = $this->koneksi->mysqlQuery("DELETE FROM summaryaging WHERE userid = ".$userId.";");
		
		$i = 1;
		$urutanGrupSeb = 1;
		$currencySeb = "";
		$kreditAccSeb = "";//menentukan kreditAcc sebelumnya, untuk menentukan nomor urut grup
		
		if($account == "pay")
		{	
			// $query = $this->koneksi->mysqlQuery("SELECT * FROM mailinvoice WHERE company = '".$company."' AND DATE(tglexp) <= DATE('".$dateConv."') 
			// 								AND barcode LIKE '%A%' AND  paid = 'N' AND cancelpaid = 'N' AND saveinvret = 'Y'
			// 								ORDER BY kreditacc, currency;");
			$query = $this->koneksi->mysqlQuery("SELECT * FROM mailinvoice WHERE company = '".$company."' AND receivedate BETWEEN '".$dateConv."' AND '".$endDate."' AND barcode LIKE '%A%' AND  paid = 'N' AND cancelpaid = 'N' AND saveinvret = 'Y' ORDER BY kreditacc, currency;");

			$jml = $this->koneksi->mysqlNRows($query);
			while($row = $this->koneksi->mysqlFetch($query))
			{
				$vendor = $row['sendervendor1'];//menentukan VENDOR NAME
				if($row['tipesenven'] == "2")
				{
					$vendor = $row['sendervendor2name'];
				}else{
					if($row['kreditaccname'] != "")
					{
						$vendor = $row['kreditaccname'];
					}
				}

				$currency = $row['currency'];
				
				// menentukan Urutan Grup berdasarkan Vendor Sebelumnya, jika sama maka urutan grup = + 1, jika tidak = reset jadi 1
				// if($kreditAccSeb == $row['kreditacc'] && $currencySeb == $currency)
				if($kreditAccSeb == $row['kreditacc'])
				{
					// $urutanGrup = $urutanGrupSeb+1;
					if($currencySeb == $currency)
					{
						$urutanGrup = $urutanGrupSeb+1;
					}else{
						$urutanGrup = 1;
					}
				}
				if($kreditAccSeb != $row['kreditacc'])
				{
					$urutanGrup = 1;
				}
				
				//menentukan range aging
				$interval = $this->CPublic->perbedaanHari(str_replace("-","",$endDate),str_replace("-","",$row['tglexp']));
				$dbName = "";
				$dbContent = "";
				if($interval <= 30)
				{
					$dbName = "rangesatu";
					$dbContent = ($row['amount']+$row['addi'])-$row['deduc'];
				}
				if($interval >= 31 && $interval <= 60)
				{
					$dbName = "rangedua";
					$dbContent = ($row['amount']+$row['addi'])-$row['deduc'];
				}
				if($interval >= 61 && $interval <= 90)
				{
					$dbName = "rangetiga";
					$dbContent = ($row['amount']+$row['addi'])-$row['deduc'];
				}
				if($interval >= 91 && $interval <= 360)
				{
					$dbName = "rangeempat";
					$dbContent = ($row['amount']+$row['addi'])-$row['deduc'];
				}
				if($interval >= 361)
				{
					$dbName = "rangelima";
					$dbContent = ($row['amount']+$row['addi'])-$row['deduc'];
				}
				
				// Insert Temp Data ke summaryaging
				$insert = $this->insertQuery($row['idmailinv'],$userId, $company, $endDate, $vendor, $i, $urutanGrup, $dbName, $dbContent);
				if($insert)
				{
					$kreditAccSeb = $row['kreditacc'];
					$urutanGrupSeb = $urutanGrup;
					$vendorSeb = $vendor;
					$currencySeb = $row['currency'];
					// $currencySeb = $currency;
					$i++;
				}
			}
		}
		
		if($account == "rec")
		{
			// $query = $this->koneksi->mysqlQuery("SELECT * FROM outgoinginvoice WHERE company = '".$company."' AND DATE(tglexp) <= DATE('".$dateConv."') ORDER BY kreditacc, currency;");
			$query = $this->koneksi->mysqlQuery("SELECT * FROM outgoinginvoice WHERE company = '".$company."' AND tglexp BETWEEN '".$dateConv."' AND '".$endDate."' ORDER BY kreditacc, currency;");

			$jml = $this->koneksi->mysqlNRows($query);
			while($row = $this->koneksi->mysqlFetch($query))
			{	
				$vendor = $row['customername'];
				$currency = $row['currency'];
				// menentukan Urutan Grup berdasarkan Vendor Sebelumnya, jika sama maka urutan grup = + 1, jika tidak = reset jadi 1
				if($kreditAccSeb == $row['kreditacc'] && $vendorSeb == $vendor && $currencySeb == $currency)
				{
					$urutanGrup = $urutanGrupSeb+1;
				}
				if($kreditAccSeb != $row['kreditacc'] && $vendorSeb != $vendor && $currencySeb != $currency)
				{
					$urutanGrup = 1;
				}
				
				//menentukan range aging
				$interval = $this->CPublic->perbedaanHari(str_replace("-","",$endDate),str_replace("-","",$row['tglexp']));
				$dbName = "";
				$dbContent = "";
				if($interval <= 30)
				{
					$dbName = "rangesatu";
					$dbContent = $row['amount'];
				}
				if($interval >= 31 && $interval <= 60)
				{
					$dbName = "rangedua";
					$dbContent = $row['amount'];
				}
				if($interval >= 61 && $interval <= 90)
				{
					$dbName = "rangetiga";
					$dbContent = $row['amount'];
				}
				if($interval >= 91 && $interval <= 360)
				{
					$dbName = "rangeempat";
					$dbContent = $row['amount'];
				}
				if($interval >= 361)
				{
					$dbName = "rangelima";
					$dbContent = $row['amount'];
				}
				
				// Insert Temp Data ke summaryaging
				$insert = $this->insertQueryOutgoing($row['idoutgoinginv'],$userId, $company, $endDate, $vendor, $i, $urutanGrup, $dbName, $dbContent);
				if($insert)
				{
					$kreditAccSeb = $row['kreditacc'];
					$urutanGrupSeb = $urutanGrup;
					$vendorSeb = $vendor;
					$currencySeb = $currency;
					$i++;
				}
			}
		}
		
		return $insert;
	}
	
	function insertQuery($idmailinv,$userId, $company, $date, $vendor, $i, $urutanGrup, $dbName, $dbContent)
	{
		$query = $this->koneksi->mysqlQuery("INSERT INTO summaryaging (urutan,urutangrup,idmailinv,userid,company,datedisp,kreditacc,vendor,currency,mailinvno,barcode,receivedate,tglinvoice,dueday,tglexp,subaccount,amount,".$dbName.")
					SELECT ".$i.",".$urutanGrup.",idmailinv,".$userId.",'".$company."','".$date."',kreditacc,'".$vendor."',currency,mailinvno,barcode,receivedate,tglinvoice,dueday,tglexp,subcode AS subAcc,(amount+addi)-deduc AS amount,".$dbContent."
					FROM mailinvoice
					WHERE idmailinv = ".$idmailinv.";");
		return $query;
	}
	
	function insertQueryOutgoing($idoutgoinginv,$userId, $company, $date, $vendor, $i, $urutanGrup, $dbName, $dbContent)
	{
		$query = $this->koneksi->mysqlQuery("INSERT INTO summaryaging (urutan,urutangrup,idmailinv,userid,company,datedisp,kreditacc,vendor,currency,mailinvno,receivedate,tglinvoice,dueday,tglexp,amount,".$dbName.")
					SELECT ".$i.",".$urutanGrup.",idoutgoinginv,".$userId.",'".$company."','".$date."',kreditacc,'".$vendor."',currency,outgoinginvno,sentdate,tglinvoice,dueday,tglexp,amount,".$dbContent."
					FROM outgoinginvoice
					WHERE idoutgoinginv = ".$idoutgoinginv.";");
		return $query;
	}
	
	function jmlInvoice($userId, $kreditAcc, $currency)
	{
		$query = $this->koneksi->mysqlQuery("SELECT COUNT(kreditacc) AS jmlinvoice FROM summaryaging WHERE userid = ".$userId." AND kreditacc = ".$kreditAcc." AND currency = '".$currency."';");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['jmlinvoice'];
	}
	
	function jmlInvoiceOut($userId, $kreditAcc, $vendor, $currency)
	{
		$query = $this->koneksi->mysqlQuery("SELECT COUNT(kreditacc) AS jmlinvoice FROM summaryaging WHERE userid = ".$userId." AND kreditacc = ".$kreditAcc." AND vendor = '".$vendor."' AND currency = '".$currency."';");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['jmlinvoice'];
	}
	
	function hitungSubTotal($userId, $kreditAcc, $currency, $range)
	{
		$field = "";
		
		$subTotal = 0;
		$query = $this->koneksi->mysqlQuery("SELECT * FROM summaryaging WHERE userid = ".$userId." AND kreditacc = ".$kreditAcc." AND currency = '".$currency."';");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			if($range == "")
				$field = $row['amount'];
			if($range == "satu")
				$field = $row['rangesatu'];
			if($range == "dua")
				$field = $row['rangedua'];
			if($range == "tiga")
				$field = $row['rangetiga'];
			if($range == "empat")
				$field = $row['rangeempat'];
			if($range == "lima")
				$field = $row['rangelima'];
				
			$subTotal = $subTotal + $field;
		}
		
		return $subTotal;
	}
	
	function hitungTotal($userId, $currency, $range)
	{
		$field = "";
		
		$subTotal = 0;
		$query = $this->koneksi->mysqlQuery("SELECT * FROM summaryaging WHERE userid = ".$userId." AND currency = '".$currency."';");
		while($row = $this->koneksi->mysqlFetch($query))
		{
			if($range == "")
				$field = $row['amount'];
			if($range == "satu")
				$field = $row['rangesatu'];
			if($range == "dua")
				$field = $row['rangedua'];
			if($range == "tiga")
				$field = $row['rangetiga'];
			if($range == "empat")
				$field = $row['rangeempat'];
			if($range == "lima")
				$field = $row['rangelima'];
				
			$subTotal = $subTotal + $field;
		}
		
		return $subTotal;
	}

}
?>