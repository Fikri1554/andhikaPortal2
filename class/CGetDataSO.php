<?php
class CGetDataSO
{
	function CGetDataSO($mysqlKoneksi, $CPublic, $dbName)
	{
		$this->koneksi = $mysqlKoneksi;
		$this->cPublic = $CPublic;
		$this->dbName = $dbName;
		$tabel = "";
	}
	function getDataSearchVessel()
	{
		$data = $_POST;
		$dataOut = array();
		$sDateNow = date("Y-m-d 00:00");
		$eDateNow = date("Y-m-d 23:59");
		$slcVessel = $data['slcVessel'];
		$sDate = $data['sDate'];
		$eDate = $data['eDate'];
		$whereNya = "";

		if ($sDate == "" AND $eDate == "")
		{
			$sDate = $sDateNow;
			$eDate = $eDateNow;
		}else{
			$sDate = $sDate." 00:00";
			$eDate = $eDate." 23:59";
		}
		// $whereNya = " WHERE vessel like '%".$slcVessel."%' AND date_position between '".$sDate."' AND '".$eDate."' AND delete_sts = '0' AND latitude like '%-%' ";
		$whereNya = " WHERE vessel like '%".$slcVessel."%' AND date_position between '".$sDate."' AND '".$eDate."' AND delete_sts = '0' AND LENGTH(latitude) >= '4' ";
		$sql = " SELECT * FROM ".$this->dbName.".data_vessel ".$whereNya." ORDER BY date_position ASC";
		
		$query = $this->koneksi->mysqlQuery($sql);
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$dataOut['dataLoc'][] = array('lat'=>$row['latitude'],'longs'=>$row['longitude']);
		}
		$sqlViewVsl = "SELECT color,image,image_2 FROM ".$this->dbName.".mst_vesselview WHERE vessel_init LIKE '%".$slcVessel."%'";
		$queryView = $this->koneksi->mysqlQuery($sqlViewVsl);
		while($rows = $this->koneksi->mysqlFetch($queryView))
		{
			$dataOut['color'] = $rows['color'];
			$dataOut['image'] = $rows['image'];
			$dataOut['image_2'] = $rows['image_2'];
		}

		return $dataOut;
	}
	function getOptVessel()
	{
		$dataTR = "";

		$sql = " SELECT * FROM mst_vesselview ORDER BY vessel ASC ";
		$query = $this->koneksi->mysqlQuery($sql);
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$dataTR .= "<option value=\"".$row['vessel_init']."\">".$row['vessel']."</option>";
		}
		return $dataTR;
	}
	

}


?>