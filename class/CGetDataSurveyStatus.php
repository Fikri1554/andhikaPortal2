<?php
class CGetDataSurveyStatus
{
	function CGetDataSurveyStatus($mysqlKoneksi, $CPublic, $dbName)
	{
		$this->koneksi = $mysqlKoneksi;
		$this->cPublic = $CPublic;
		$this->dbName = $dbName;
		$tabel = "";
	}
	function getDataMstVesselSurveyStatus($typeData = "")
	{
		$no = 1;
		$whereIdNya = "";
		if ($typeData != "")
		{
			$whereIdNya = "idvsl = '".$typeData."' AND";
		}
		$query = $this->koneksi->mysqlQuery("SELECT * FROM ".$this->dbName.".tblvessel WHERE ".$whereIdNya." deletests = '0' order by idvsl ASC "); 
		while($row = $this->koneksi->mysqlFetch($query))
		{
			if ($typeData == "") 
			{
				$dataTR .= "
							<tr id=\"idTR".$row['idvsl']."\" class=\"clsTR\" onClick=\"trKlik('".$row['idvsl']."');\">
								<td align=\"center\">".$no."</td>
								<td align=\"center\">".$row['nmvsl']."</td>
								<td align=\"center\">".$row['vsltype']."</td>
								<td align=\"center\">".$row['year']."</td>
								<td align=\"center\">".$row['hp']."</td>
								<td align=\"center\">".$row['dwt']."</td>
								<td align=\"center\">".$row['grt']."</td>
							</tr>
							";
				$no ++;
			}else{
				$dataTR['idvsl'] = $row['idvsl'];
				$dataTR['nmvsl'] = $row['nmvsl'];
				$dataTR['vslType'] = $row['vsltype'];
				$dataTR['vslYear'] = $row['year'];
				$dataTR['vslHp'] = $row['hp'];
				$dataTR['vslDwt'] = $row['dwt'];
				$dataTR['vslGrt'] = $row['grt'];
			}
		}
		// echo "<pre>";print_r($dataTR);exit;
		return $dataTR;
	}
	function saveMstVesselSurveyStatus()
	{
		$usrInit = $_SESSION['userInitial'];
		$dateNowUser = date("Ymd#H:i")."#".$usrInit;
		$vslName = $_POST['vslName'];
		$vslType = $_POST['typeVissel'];
		$vslYear = $_POST['yearVissel'];
		$vslHP = $_POST['hpVissel'];
		$vslDWT = $_POST['dwtVissel'];
		$vslGRT = $_POST['grtVissel'];
		$addUsr = $dateNowUser;
		$stInsert = "";

		$idEdit = $_POST["idEditNya"];

		if ($idEdit != "") {
			$sql = "UPDATE ".$this->dbName.".tblvessel SET  nmvsl = '$vslName',vsltype = '$vslType',year = '$vslYear',hp = '$vslHP',dwt = '$vslDWT',grt = '$vslGRT', updusrdt = '$dateNowUser' WHERE idvsl = '".$idEdit."'";
		}else{
			$sql = "INSERT INTO  ".$this->dbName.".tblvessel(nmvsl,vsltype,year,hp,dwt,grt,addusrdt)VALUES('".$vslName."','".$vslType."','".$vslYear."','".$vslHP."','".$vslDWT."','".$vslGRT."','".$addUsr."')";

		}

		try {
			$this->koneksi->mysqlQuery($sql);
			$stInsert = "sukses";
		} catch (Exception $ex) {
			$stInsert = "gagal =>".$ex;
		}

		return $stInsert;
	}
	function delDataMstVesselSurveyStatus()
	{
		$usrInit = $_SESSION['userInitial'];
		$dateNowUser = date("Ymd#H:i")."#".$usrInit;
		$idDelNya = $_POST["actionIdDelMstSurveyStatus"];
		$stDel = "";
		$sql = "UPDATE ".$this->dbName.".tblvessel SET deletests = '1', delusrdt = '$dateNowUser' WHERE idvsl = '".$idDelNya."'";
		try {
			$this->koneksi->mysqlQuery($sql);
			$stDel = "Delete Success..!!";
		} catch (Exception $ex) {
			$stDel = "Failed..!! =>".$ex;
		}
		return $stDel;
	}
	function getDataMstPicSurveyStatus($idEdit = "")
	{
		$no = 1;
		$whereIdNya = "";
		if ($idEdit != "")
		{
			$whereIdNya = "idpic = '".$idEdit."' AND";
		}
		$query = $this->koneksi->mysqlQuery("SELECT * FROM ".$this->dbName.".mstpic WHERE ".$whereIdNya." deletests = '0' order by idpic ASC "); 
		while($row = $this->koneksi->mysqlFetch($query))
		{
			if ($idEdit == "") 
			{
				$dataTR .= "
							<tr id=\"idTR".$row['idpic']."\" class=\"clsTR\" onClick=\"trKlik('".$row['idpic']."');\">
								<td align=\"center\">".$no."</td>
								<td>&nbsp".$row['nmpic']."</td>
							</tr>
							";
				$no ++;
			}else{
				$dataTR['idpic'] = $row['idpic'];
				$dataTR['nmpic'] = $row['nmpic'];
			}
		}
		return $dataTR;
	}
	function saveMstPicSurveyStatus()
	{
		$usrInit = $_SESSION['userInitial'];
		$dateNowUser = date("Ymd#H:i")."#".$usrInit;
		$picName = $_POST['picName'];
		$stInsert = "";

		$idEdit = $_POST["idEditNya"];

		if ($idEdit != "") {
			$sql = "UPDATE ".$this->dbName.".mstpic SET  nmpic = '$picName', updusrdt = '$dateNowUser' WHERE idpic = '".$idEdit."'";
		}else{
			$sql = "INSERT INTO  ".$this->dbName.".mstpic(nmpic,addusrdt)VALUES('".$picName."','".$dateNowUser."')";
		}

		try {
			$this->koneksi->mysqlQuery($sql);
			$stInsert = "sukses";
		} catch (Exception $ex) {
			$stInsert = "gagal =>".$ex;
		}

		return $stInsert;
	}
	function delDataMstPicSurveyStatus()
	{
		$usrInit = $_SESSION['userInitial'];
		$dateNowUser = date("Ymd#H:i")."#".$usrInit;
		$idDelNya = $_POST["actionIdDelMstPicSurveyStatus"];
		$stDel = "";
		$sql = "UPDATE ".$this->dbName.".mstpic SET deletests = '1', delusrdt = '$dateNowUser' WHERE idpic = '".$idDelNya."'";
		try {
			$this->koneksi->mysqlQuery($sql);
			$stDel = "Delete Success..!!";
		} catch (Exception $ex) {
			$stDel = "Failed..!! =>".$ex;
		}
		return $stDel;
	}
	function getDataMstCertSurveyStatus($idEdit = "")
	{
		$no = 1;
		$whereIdNya = "";
		if ($idEdit != "")
		{
			$whereIdNya = "kdcert = '".$idEdit."' AND";
		}
		$query = $this->koneksi->mysqlQuery("SELECT * FROM ".$this->dbName.".mastercert WHERE ".$whereIdNya." deletests = '0' order by nmcert ASC "); 
		while($row = $this->koneksi->mysqlFetch($query))
		{
			if ($idEdit == "") 
			{
				if ($row['statutory'] == "Y") 
				{
					$statutoryNya = "<span style=\"color:green;\" >&radic;</span>";
				}else{
					$statutoryNya = "";
				}
				$dataTR .= "
							<tr id=\"idTR".$row['kdcert']."\" class=\"clsTR\" onClick=\"trKlik('".$row['kdcert']."');\">
								<td align=\"center\">".$no."</td>
								<td>&nbsp".$row['nmcert']."</td>
								<td align=\"center\">&nbsp".$statutoryNya."</td>
								<td>&nbsp".$row['remarkcert']."</td>
							</tr>
							";
				$no ++;
			}else{
				$dataTR['kdcert'] = $row['kdcert'];
				$dataTR['nmcert'] = $row['nmcert'];
				$dataTR['statutory'] = $row['statutory'];
				$dataTR['remarkcert'] = $row['remarkcert'];
			}
		}
		return $dataTR;
	}
	function saveMstCertSurveyStatus()
	{
		$usrInit = $_SESSION['userInitial'];
		$dateNowUser = date("Ymd#H:i")."#".$usrInit;
		$certName = $_POST['certName'];
		$remark = $_POST['remark'];
		$chkStatutory = $_POST['chkStatutory'];
		$stInsert = "";

		$idEdit = $_POST["idEditNya"];

		if ($idEdit != "") {
			$sql = "UPDATE ".$this->dbName.".mastercert SET  nmcert = '$certName',remarkcert = '$remark',statutory = '$chkStatutory', updusrdt = '$dateNowUser' WHERE kdcert = '".$idEdit."'";
		}else{
			$sql = "INSERT INTO  ".$this->dbName.".mastercert(nmcert,remarkcert,statutory,addusrdt)VALUES('$certName','$remark','$chkStatutory','".$dateNowUser."')";
		}

		try {
			$this->koneksi->mysqlQuery($sql);
			$stInsert = "sukses";
		} catch (Exception $ex) {
			$stInsert = "gagal =>".$ex;
		}

		return $stInsert;
	}
	function delDataMstCertSurveyStatus()
	{
		$usrInit = $_SESSION['userInitial'];
		$dateNowUser = date("Ymd#H:i")."#".$usrInit;
		$idDelNya = $_POST["actionIdDelMstCertSurveyStatus"];
		$stDel = "";
		$sql = "UPDATE ".$this->dbName.".mastercert SET deletests = '1', delusrdt = '$dateNowUser' WHERE kdcert = '".$idDelNya."'";
		try {
			$this->koneksi->mysqlQuery($sql);
			$stDel = "Delete Success..!!";
		} catch (Exception $ex) {
			$stDel = "Failed..!! =>".$ex;
		}
		return $stDel;
	}
	function getDataCertSurveyStatus()
	{
		$no = 1;
		$whereIdNya = "";
		$idvsl = $_POST['idVsl'];
		$sisaHari = "";
		$query = $this->koneksi->mysqlQuery("SELECT a.idcert, a.kdcert, a.idgroup,a.reminder, b.nmcert FROM ".$this->dbName.".tblcert a, ".$this->dbName.".mastercert b WHERE a.idvsl='".$idvsl."' AND a.kdcert=b.kdcert AND a.deletests=0 AND b.deletests=0 ORDER BY a.idgroup ASC "); 
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$sh = $this->sisaHari($row['idcert']);
			$sisaHari = $sh['sisaNya'];
			$stPermanent = $sh['stPermanent'];
			$warnaTeksOverDue = "";
			$warnaMerah = "";
			$cekData = $sh['cekData'];
			if ($cekData == "kosong") 
			{
				$warnaMerah = "color:#F00;";
				$sisaHari = "";
			}
			if($stPermanent == "Y")
			{
				$sisaHari = "&nbsp;PERMANENT";
				$warnaTeksOverDue = "color:#960;";
			}
			if($sisaHari != 0 && $sisaHari < 0)
			{
				$warnaTeksOverDue = "color:#F00;";
			}
			$warnaTeksReminderCert = "color:#00F;";
			if($row['reminder'] == "N")
			{
				$warnaTeksReminderCert = "color:#F00;";
			}
			$dataTR .= "
						<tr id=\"idTR".$row['idcert']."\" class=\"clsTR\" onClick=\"trKlik('".$row['idcert']."','".$cekData."');\">
							<td align=\"center\">".$no."</td>
							<td align=\"center\">&nbsp".$row['idgroup']."</td>
							<td>&nbsp<span  style=\"".$warnaMerah."\">".$row['nmcert']."</span></td>
							<td align=\"center\">&nbsp<span  style=\"".$warnaTeksOverDue."\">".$sisaHari."</span></td>
							<td align=\"center\">&nbsp <span style=\"".$warnaTeksReminderCert."\"><b> ".$row['reminder']."</b></td>
						</tr>
						";
			$no ++;
		}
		return $dataTR;
	}
	function getSelectVessel($typeGetSlc = "")
	{
		$html = "";
        $dataTR .= '<select id=\'idSlcVesselSurveyStatus\' name=\'idSlcVesselSurveyStatus\' class=\'elementMenu\' style=\'width:200px;margin: 10px; float: left;\'>';
        
        if ($typeGetSlc = "")
        {
        	$dataTR .= "<option value=\"0\">- Select Vessel -</option>";
        }else{
        	$dataTR .= "<option value=\"allVessel\">&nbspAll Vessel</option>";
        }
        $query = $this->koneksi->mysqlQuery(" SELECT * FROM ".$this->dbName.".tblvessel WHERE  reminder = 'Y' AND deletests = '0' ORDER BY nmvsl ASC "); 
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$dataTR .= "<option value=\"".str_replace(" ","",$row['idvsl'])."\">&nbsp;".$row['nmvsl']."</option>";
		}
		$dataTR .= '</select>';
      	return $dataTR;
	}	
	function sisaHari($idcert)
	{
		$query = $this->koneksi->mysqlQuery("SELECT * FROM ".$this->dbName.".tbltrans WHERE idcert='".$idcert."' AND deletests=0 ORDER BY enddt DESC  LIMIT 1");
		$row = $this->koneksi->mysqlFetch($query);
		$ed = $row['enddt'];

		$query1 = $this->koneksi->mysqlQuery("SELECT * FROM ".$this->dbName.".tbltrans WHERE idcert='".$idcert."' AND deletests=0 ORDER BY idtrans DESC  LIMIT 1");
		$row1 = $this->koneksi->mysqlFetch($query1);
		$stPermanent = $row1['permanentenddt'];

		$enddt = explode("-",$ed);
		$thnEnd = (int) $enddt[0];
		$blnEnd = (int) $enddt[1];
		$tglEnd = (int) $enddt[2];
		$mktimeEndDt = mktime(0,0,0,$blnEnd,$tglEnd,$thnEnd);
		$mkTimeNow = mktime(0,0,0,date("m"),date("d"),date("Y"));
		$sisaNya = round((($mktimeEndDt - $mkTimeNow) / 86400),0);
		$cekData = "ada";
		if ($ed == "") {$cekData = "kosong";}
		$dataOut = array("sisaNya"=>$sisaNya,"stPermanent"=>$stPermanent,"cekData"=>$cekData);
		// return round((($mktimeEndDt - $mkTimeNow) / 86400),0); // 86400 adalah jumlah detik dalam 1 hari
		return $dataOut;
	}
	function getSlcItemSurveyStatus()
	{
		$html = "";
		$query = $this->koneksi->mysqlQuery("SELECT * FROM ".$this->dbName.".mastercert WHERE deletests=0 ORDER BY nmcert ASC");

		$html .= '<select id=\'slcItemSurveyStatus\' name=\'slcItemSurveyStatus\' class=\'elementMenu\' style=\'width:40%;\'>';
		$html.="<option value=\"0\">--SELECT ITEM--</option>";
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$html.="<option value=\"".$row['kdcert']."\">&nbsp;".$row['nmcert']."</option>";
		}
        $html .= '</select>';
      	return $html;
	}
	function getSlcDataDepart()
	{
		$html = "";
		$query = $this->koneksi->mysqlQuery("SELECT * FROM ".$this->dbName.".tbldepart WHERE  deletests = '0' ORDER BY nmdepart ASC");

		$html .= '<select id=\'slcDepart\' name=\'slcDepart\' class=\'elementMenu\' style=\'width:40%;\'>';
		$html.="<option value=\"0\">- SELECT DEPARTMENT -</option>";
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$html.="<option value=\"".$row['iddepart']."\">&nbsp;".$row['nmdepart']."</option>";
		}
        $html .= '</select>';
      	return $html;
	}
	function getSlcPicSurveyStatus()
	{
		$html = "";
		$query = $this->koneksi->mysqlQuery("SELECT * FROM ".$this->dbName.".mstpic WHERE  deletests = '0' ORDER BY nmpic ASC");

		$html .= '<select id=\'slcPic\' name=\'slcPic\' class=\'elementMenu\' style=\'width:40%;\'>';
		$html.="<option value=\"0\">- SELECT PIC -</option>";
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$html.="<option value=\"".$row['idpic']."\">&nbsp;".$row['nmpic']."</option>";
		}
        $html .= '</select>';
      	return $html;
	}
	function saveCertSurveyStatus()
	{
		$usrInit = $_SESSION['userInitial'];
		$dateNowUser = date("Ymd#H:i")."#".$usrInit;
		$slctItemSS = $_POST['slctItemSS'];
		$idGroup = $_POST['group'];
		$idVsl = $_POST['idVsl'];
		$alert = $_POST['alert'];
		$cekData = "0";
		$idEdit = $_POST["idEditNya"];
		if ($idEdit != "") {
			$sql = "UPDATE ".$this->dbName.".tblcert SET  kdcert = '$slctItemSS', reminder = '$alert',idgroup = '$idGroup',updusrdt = '$dateNowUser' WHERE idcert = '".$idEdit."'";
		}else{
			$cekData = $this->cekCertSurveyStatus($idVsl,$slctItemSS);
			$sql = "INSERT INTO ".$this->dbName.".tblcert(idvsl, kdcert, idgroup, reminder, addusrdt)VALUES('$idVsl','$slctItemSS','$idGroup','$alert','".$dateNowUser."')";
		}

		try {
			if ($cekData == '0') {
				$this->koneksi->mysqlQuery($sql);
				$stInsert = "Success..!!";
			}else{
				$stInsert = "Certificate already exsist for this vessel..!!";
			}
		} catch (Exception $ex) {
			$stInsert = "gagal =>".$ex;
		}

		return $stInsert;
	}
	function cekCertSurveyStatus($idVsl = "",$kdCert = "")
	{
		$sql = "SELECT * FROM ".$this->dbName.".tblcert WHERE idVsl = '$idVsl' AND kdcert = '$kdCert' AND deletests='0' ";
		$query = $this->koneksi->mysqlQuery($sql);
		$jmlData = $this->koneksi->mysqlNRows($query);
		return $jmlData;
	}
	function getDataEditCertSurveyStatus($idEdit = "")
	{
		$query = $this->koneksi->mysqlQuery("SELECT * FROM ".$this->dbName.".tblcert WHERE idcert = '$idEdit' AND deletests = '0' "); 
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$dataOut['idcert'] = $row['idcert'];
			$dataOut['kdcert'] = $row['kdcert'];
			$dataOut['idgroup'] = $row['idgroup'];
			$dataOut['alert'] = $row['reminder'];
		}
		return $dataOut;
	}
	function delDataCertSurveyStatus()
	{
		$usrInit = $_SESSION['userInitial'];
		$dateNowUser = date("Ymd#H:i")."#".$usrInit;
		$idDelNya = $_POST["actionIdDelCertSurveyItem"];
		$stDel = "";
		$sql = "UPDATE ".$this->dbName.".tblcert SET deletests = '1', delusrdt = '$dateNowUser' WHERE idcert = '".$idDelNya."'";
		try {
			$this->koneksi->mysqlQuery($sql);
			$stDel = "Delete Success..!!";
		} catch (Exception $ex) {
			$stDel = "Failed..!! =>".$ex;
		}
		return $stDel;
	}
	function getDataTrans()
	{
		$no = 1;
		$idNya = $_POST['id'];

		$query = $this->koneksi->mysqlQuery("SELECT b.nmdepart,c.nmpic as mstNamaPic,d.file,a.* 
			FROM ".$this->dbName.".tbltrans a 
			LEFT JOIN ".$this->dbName.".tbldepart b ON a.iddepart = b.iddepart 
			LEFT JOIN ".$this->dbName.".mstpic c ON c.idpic = a.idpic 
			LEFT JOIN ".$this->dbName.".tblpdf d ON d.idtrans = a.idtrans AND d.deletests = '0' 
			WHERE a.idcert='".$idNya."' AND a.deletests = '0' 
			ORDER BY a.startdt DESC, a.enddt DESC "); 
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$stFile = "";
			if ($row['file'] != "")
			{
				$stFile = "<span style=\"color:#00F;\" ><b>&radic;</b></span>";
			}
			$dataTR .= "
						<tr id=\"idTR2".$row['idtrans']."\" class=\"clsTR2\" onClick=\"trKlik2('".$row['idtrans']."','".$row['file']."','".$row['file']."');\">
							<td align=\"center\">".$no."</td>
							<td align=\"center\">".$row['startdt']."</td>
							<td align=\"center\">".$row['enddt']."</td>
							<td>&nbsp".$row['nmdepart']."</td>
							<td align=\"center\">&nbsp".$row['mstNamaPic']."</td>
							<td>&nbsp".$row['remarks']."</td>
							<td align=\"center\">".$stFile."</td>
						</tr>
						";
			$no ++;
		}
		return $dataTR;
	}
	function saveDataTrans()
	{
		$usrInit = $_SESSION['userInitial'];
		$dateNowUser = date("Ymd#H:i")."#".$usrInit;
		$idEditNya = $_POST['idEdit2'];
		$idcert = $_POST['idCert'];
		$idDepart = $_POST['depart'];
		$idPic = $_POST['picNya'];
		$nmPic = $_POST['nmPic'];
		$startDate = $_POST['startDate'];
		$endDate = $_POST['endDate'];
		$stPermanent = $_POST['stPermanent'];
		$remark = $_POST['remark'];

		if ($idEditNya == "")
		{
			$sql = "INSERT INTO ".$this->dbName.".tbltrans(idcert,iddepart,idpic,nmpic,startdt,enddt,permanentenddt,remarks,addusrdt) VALUES ('$idcert','$idDepart','$idPic','$nmPic','$startDate','$endDate','$stPermanent','$remark','$dateNowUser') ";
		}else{
			$sql = "UPDATE ".$this->dbName.".tbltrans SET  idcert = '$idcert',iddepart = '$idDepart',idpic = '$idPic',nmpic = '$nmPic',startdt = '$startDate',enddt = '$endDate',permanentenddt = '$stPermanent',remarks = '$remark',updusrdt = '$dateNowUser' WHERE idtrans = '".$idEditNya."'";
		}
		
		try {			
			$this->koneksi->mysqlQuery($sql);
			$stInsert = "Success..!!";
		} catch (Exception $ex) {
			$stInsert = "gagal =>".$ex;
		}

		return $stInsert;
	}
	function getDataEditTransSurveyStatus($idEdit = "")
	{
		$query = $this->koneksi->mysqlQuery("SELECT * FROM ".$this->dbName.".tbltrans WHERE idtrans = '$idEdit' AND deletests = '0' "); 
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$dataOut['idtrans'] = $row['idtrans'];
			$dataOut['idcert'] = $row['idcert'];
			$dataOut['idpic'] = $row['idpic'];
			$dataOut['idDepart'] = $row['iddepart'];
			$dataOut['startdt'] = $row['startdt'];
			$dataOut['enddt'] = $row['enddt'];
			$dataOut['permanentenddt'] = $row['permanentenddt'];
			$dataOut['remarks'] = $row['remarks'];
		}
		return $dataOut;
	}
	function delDataTransSurveyStatus()
	{
		$usrInit = $_SESSION['userInitial'];
		$dateNowUser = date("Ymd#H:i")."#".$usrInit;
		$idDelNya = $_POST["actionIdDelTransSurveyStatus"];
		$stDel = "";
		$sql = "UPDATE ".$this->dbName.".tbltrans SET deletests = '1', delusrdt = '$dateNowUser' WHERE idtrans = '".$idDelNya."'";
		try {
			$this->koneksi->mysqlQuery($sql);
			$stDel = "Delete Success..!!";
		} catch (Exception $ex) {
			$stDel = "Failed..!! =>".$ex;
		}
		return $stDel;
	}
	function uploadPdfSurveyStatus($path)
	{
		$usrInit = $_SESSION['userInitial'];
		$dateNowUser = date("Ymd#H:i")."#".$usrInit;
		$idTrans = $_POST['txtIdTrans'];
		$dir = $path."/shipManagement/uploadFile/";
		$fileName = $_FILES['file']['name'];
		$stData = "";
		$getExt = pathinfo($_FILES["file"]["name"]);
		$getExt = $getExt['extension'];
		$newFileName = $idTrans."_".date("Ymd")."_".str_replace(':','',date("H:i:s"));
		$newFileName = $newFileName.".".$getExt;

		if ( 0 < $_FILES['file']['error'] ) {
        	$stData = 'Error: ' . $_FILES['file']['error'] ;
	    }else{
	    	$sqlCheck = "SELECT * FROM ".$this->dbName.".tblpdf WHERE idtrans = '$idTrans' AND deletests = '0'";
			$query = $this->koneksi->mysqlQuery($sqlCheck);
			$jmlData = $this->koneksi->mysqlNRows($query);

			if ($jmlData > 0)
			{
				$stDel = $this->delDataPdfSurveyStatus($idTrans);
				if ($stDel != "")
				{
					print_r($stDel);
					exit;
				}
			}

			$sql="INSERT INTO ".$this->dbName.".tblpdf(idtrans, file, addusrdt)VALUES('$idTrans','$newFileName','".$dateNowUser."')";
			$this->koneksi->mysqlQuery($sql);
			move_uploaded_file($_FILES['file']['tmp_name'], $dir. $fileName);
			rename($dir. $fileName,$dir. $newFileName);
	    	$stData = "Success..!!";
	    }
	    return $stData;
	}
	function delDataPdfSurveyStatus($idTrans = "")
	{
		$usrInit = $_SESSION['userInitial'];
		$dateNowUser = date("Ymd#H:i")."#".$usrInit;
		$idDelNya = $idTrans;
		$stDel = "";
		$sql = "UPDATE ".$this->dbName.".tblpdf SET deletests = '1', delusrdt = '$dateNowUser' WHERE idtrans = '".$idDelNya."' AND deletests = '0' ";
		try {
			$this->koneksi->mysqlQuery($sql);
		} catch (Exception $ex) {
			$stDel = "Failed..!! =>".$ex;
		}
		return $stDel;
	}
	function getDataReportCertificate($idCheckNya = "")
	{
		$no = 1;
		$idNya = $_POST['id'];
		$whereNya = "";
		$idGen = "";

		if ($idCheckNya != "") 
		{
			$idGenExplode = explode(",",$idCheckNya);
			for ($lan=0; $lan < count($idGenExplode); $lan++) 
			{ 
				if ($idGen == "")
				{
					$idGen = "'".$idGenExplode[$lan]."'";
				}else{
					$idGen .= ",'".$idGenExplode[$lan]."'";
				}
			}
			$whereNya = "WHERE idgen IN(".$idGen.")";
		}
		
		$query = $this->koneksi->mysqlQuery(" SELECT * FROM ".$this->dbName.".tblgenerate ".$whereNya." ORDER BY nmcert ASC "); 
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$stArshanti = $this->convertDateNya($row['startdt_arsanti']);
			$edArshanti = $this->convertDateNya($row['enddt_arsanti']);
			$stJohnCaine = $this->convertDateNya($row['startdt_jcaine']);
			$edJohnCaine = $this->convertDateNya($row['enddt_jcaine']);
			$stKanishka = $this->convertDateNya($row['startdt_kanishka']);
			$edKanishka = $this->convertDateNya($row['enddt_kanishka']);
			$stKalyani = $this->convertDateNya($row['startdt_kalyani']);
			$edKalyani = $this->convertDateNya($row['enddt_kalyani']);
			$stLarasati = $this->convertDateNya($row['startdt_larasati']);
			$edLarasati = $this->convertDateNya($row['enddt_larasati']);
			$stNareswari = $this->convertDateNya($row['startdt_nareswari']);
			$edNareswari = $this->convertDateNya($row['enddt_nareswari']);
			$stParamesti = $this->convertDateNya($row['startdt_paramesti']);
			$edParamesti = $this->convertDateNya($row['enddt_paramesti']);
			
			$dataTR .= "<tr id=\"idTR2".$row['idgen']."\" class=\"clsTR2\">";

			if ($idCheckNya == "")
			{
				$dataTR .= "	<td>
								<input class=\"chkRpt\" style=\"margin:2px;\" type=\"checkbox\" name=\"actionReport[]\" value=\"".$row['idgen']."\">
							</td>";
			}
			$dataTR .= "	<td align=\"center\" style=\"padding:3px;width:3%;\" >".$no."</td>
							<td align=\"left\" style=\"padding-left: 3px;font-size:12px;\">".$row['nmcert']."</td>
							<td align=\"center\" style=\"padding:3px;font-size:12px;\">".$stArshanti."</td>
							<td align=\"center\" style=\"padding:3px;font-size:12px;\">".$edArshanti."</td>
							<td align=\"center\" style=\"padding:3px;font-size:12px;\">".$stJohnCaine."</td>
							<td align=\"center\" style=\"padding:3px;font-size:12px;\">".$edJohnCaine."</td>
							<td align=\"center\" style=\"padding:3px;font-size:12px;\">".$stKanishka."</td>
							<td align=\"center\" style=\"padding:3px;font-size:12px;\">".$edKanishka."</td>
							<td align=\"center\" style=\"padding:3px;font-size:12px;\">".$stKalyani."</td>
							<td align=\"center\" style=\"padding:3px;font-size:12px;\">".$edKalyani."</td>
							<td align=\"center\" style=\"padding:3px;font-size:12px;\">".$stLarasati."</td>
							<td align=\"center\" style=\"padding:3px;font-size:12px;\">".$edLarasati."</td>
							<td align=\"center\" style=\"padding:3px;font-size:12px;\">".$stNareswari."</td>
							<td align=\"center\" style=\"padding:3px;font-size:12px;\">".$edNareswari."</td>
							<td align=\"center\" style=\"padding:3px;font-size:12px;\">".$stParamesti."</td>
							<td align=\"center\" style=\"padding:3px;font-size:12px;\">".$edParamesti."</td>
						</tr>
						";
			$no ++;
		}

		return $dataTR;
	}
	function getDataReportVessel($sqlPrint = "")
	{
		$html = "";
		$nmVsl = "";
		$cekVslId = "";
		$rangeDates = "";
		$sqlEnkrip = "";
		$idVsl = $_POST['slcVessel'];
		$txtDueWithin = $_POST['dueWithin'];
		$chkRdNya = $_POST['chkRdNya'];
		$txtChkRdYes = $_POST['txtChkRdYes'];
		$whereNya = "";
		$dataOut = array();

		if ($sqlPrint == "")
		{
			if ($txtDueWithin != "") 
			{
				if ($chkRdNya == "yes")
				{
					if ($txtChkRdYes == "")
					{
						$whereNya = " AND c.enddt <= ADDDATE(CURDATE(),".$txtDueWithin.")";
					}else{
						$whereNya = " AND c.enddt >=  ADDDATE(CURDATE(),-".$txtChkRdYes.") AND enddt <= ADDDATE(CURDATE(),".$txtDueWithin.")";
					}
				}else{
					$whereNya = " AND c.enddt >= CURDATE() AND enddt <= ADDDATE(CURDATE(),".$txtDueWithin.")";
				}
			}
			if ($txtDueWithin == "" && $chkRdNya == "yes") 
			{
				if ($txtChkRdYes != "")
				{
					$whereNya = " AND c.enddt >= ADDDATE(CURDATE(),-".$txtChkRdYes.")";
				}
			}
			if ($idVsl != "allVessel")
			{
				$whereNya .= " AND a.idvsl = '".$idVsl."'";
				
			}
			$sql = " SELECT DISTINCT (a.idcert), a.kdcert, d.nmcert, b.idvsl, b.nmvsl, c.permanentenddt,c.remarks,e.nmdepart, DATE_FORMAT(c.startdt,'%d-%m-%Y') as startDt, DATE_FORMAT(c.enddt,'%d-%m-%Y') as endDt,datediff(c.enddt,NOW())  as dayDue
					 FROM ".$this->dbName.".tblcert a
					 LEFT JOIN ".$this->dbName.".tblvessel b ON b.idvsl = a.idvsl
					 LEFT JOIN ".$this->dbName.".tbltrans c ON c.idcert = a.idcert
					 LEFT JOIN ".$this->dbName.".mastercert d ON d.kdcert = a.kdcert
					 LEFT JOIN ".$this->dbName.".tbldepart e ON e.iddepart = c.iddepart
					 WHERE a.deletests =0 AND b.deletests =0 AND c.deletests =0 AND c.deletests =0 AND b.reminder = 'Y' AND a.idvsl = b.idvsl AND a.idcert = c.idcert AND c.enddt = ( SELECT enddt FROM ".$this->dbName.".tbltrans WHERE idcert = a.idcert AND deletests = 0 ORDER BY idtrans DESC LIMIT 1 ) AND c.enddt != '0000-00-00'".$whereNya."
					 ORDER BY b.nmvsl ASC, c.enddt DESC, a.idcert DESC ";
			$sqlEnkrip = base64_encode($sql);
		}else{
			$sql = base64_decode($sqlPrint);
		}
		$query = $this->koneksi->mysqlQuery($sql);
		while($row = $this->koneksi->mysqlFetch($query))
		{
			if ($cekVslId != $row['idvsl'])
			{
				$nmVsl = $row['nmvsl'];
			}else{
				$nmVsl = "";
			}
			if ($row['permanentenddt'] == "Y")
			{
				$rangeDates = $row['startDt']." To Permanent";
			}else{
				$rangeDates = $row['startDt']." To ".$row['endDt'];
			}
			$html .= "<tr>
						<td style=\"font-size: 12px;padding-left:5px;\">".$nmVsl."</td>
						<td style=\"font-size: 12px;padding-left:5px;\">".$row['nmcert']."</td>
						<td align=\"center\" style=\"font-size: 12px;\">".$rangeDates."</td>
						<td align=\"center\" style=\"font-size: 12px;\">".$row['dayDue']."</td>
						<td align=\"center\" style=\"font-size: 12px;\">".$row['nmdepart']."</td>
						<td style=\"font-size: 12px;padding-left:5px;\">".$row['remarks']."</td>
					</tr>";
			$cekVslId = $row['idvsl'];
		}
		$dataOut["htmlNya"] = $html;
		$dataOut["sqlPrintNya"] = $sqlEnkrip;
		return $dataOut;
	}
	function generateReportSurveyStatus()
	{
		$usrInit = $_SESSION['userInitial'];
		$dateNowUser = date("Ymd#H:i")."#".$usrInit;
		$valNya = "";
		$noGen = 1;

		$sql = " SELECT * FROM ".$this->dbName.".mastercert WHERE statutory='Y' AND deletests=0 ORDER BY nmcert ASC ";
		$query = $this->koneksi->mysqlQuery($sql);
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$nmCert = $row['nmcert'];
			$sdArsanti = $this->cekDataCertNya($row['kdcert'],'009','startdt');
			$edArsanti = $this->cekDataCertNya($row['kdcert'],'009','enddt');
			$sdJohnCaine = $this->cekDataCertNya($row['kdcert'],'005','startdt');
			$edJohnCaine = $this->cekDataCertNya($row['kdcert'],'005','enddt');
			$sdKanishka = $this->cekDataCertNya($row['kdcert'],'020','startdt');
			$edKanishka = $this->cekDataCertNya($row['kdcert'],'020','enddt');
			$sdKalyani = $this->cekDataCertNya($row['kdcert'],'011','startdt');
			$edKalyani = $this->cekDataCertNya($row['kdcert'],'011','enddt');
			$sdLarasati = $this->cekDataCertNya($row['kdcert'],'016','startdt');
			$edLarasati = $this->cekDataCertNya($row['kdcert'],'016','enddt');
			$sdNareswari = $this->cekDataCertNya($row['kdcert'],'018','startdt');
			$edNareswari = $this->cekDataCertNya($row['kdcert'],'018','enddt');
			$sdPareamesti = $this->cekDataCertNya($row['kdcert'],'019','startdt');
			$edParamesti = $this->cekDataCertNya($row['kdcert'],'019','enddt');
			if ($valNya == "") 
			{
				$valNya = "('".$noGen."','".$nmCert."','".$sdArsanti."','".$edArsanti."','".$sdJohnCaine."','".$edJohnCaine."','".$sdKanishka."','".$edKanishka."','".$sdKalyani."','".$edKalyani."','".$sdLarasati."','".$edLarasati."','".$sdNareswari."','".$edNareswari."','".$sdPareamesti."','".$edParamesti."','".$dateNowUser."')";
			}else{
				$valNya .= ",('".$noGen."','".$nmCert."','".$sdArsanti."','".$edArsanti."','".$sdJohnCaine."','".$edJohnCaine."','".$sdKanishka."','".$edKanishka."','".$sdKalyani."','".$edKalyani."','".$sdLarasati."','".$edLarasati."','".$sdNareswari."','".$edNareswari."','".$sdPareamesti."','".$edParamesti."','".$dateNowUser."')";
			}
			$noGen++;
		}
		$sqlInsert = "INSERT INTO ".$this->dbName.".tblgenerate(idgen, nmcert, startdt_arsanti, enddt_arsanti, startdt_jcaine, enddt_jcaine, startdt_kanishka, enddt_kanishka, startdt_kalyani, enddt_kalyani, startdt_larasati, enddt_larasati, startdt_nareswari, enddt_nareswari, startdt_paramesti, enddt_paramesti, timegenerate)VALUES".$valNya;
		try {
			$this->koneksi->mysqlQuery("TRUNCATE TABLE ".$this->dbName.".tblgenerate");
			$this->koneksi->mysqlQuery($sqlInsert);
			$stInsert = "Success..!!";
		} catch (Exception $ex) {
			$stInsert = "gagal =>".$ex;
		}
		return $stInsert;
	}
	function cekDataCertNya($kdCert = "",$idVsl = "",$field = "")
	{
		$nilai = "";

		$sql = "SELECT idcert FROM ".$this->dbName.".tblcert WHERE kdcert='".$kdCert."' AND idvsl='".$idVsl."' AND deletests='0'";
		$query = $this->koneksi->mysqlQuery($sql);
		$row = $this->koneksi->mysqlFetch($query);
		$idCert = $row['idcert'];

		if ($field == "startdt") 
		{
			$sql2 = "SELECT ".$field." FROM ".$this->dbName.".tbltrans WHERE idcert = '".$idCert."' AND deletests=0 ORDER BY idtrans DESC LIMIT 1";
			$query2 = $this->koneksi->mysqlQuery($sql2);
			$row2 = $this->koneksi->mysqlFetch($query2);
			$nilai = $row2[$field];
		}else{
			$cekStPermanent = "SELECT permanentenddt FROM ".$this->dbName.".tbltrans WHERE idcert = '".$idCert."' AND deletests=0 ";
			$queryCekSt = $this->koneksi->mysqlQuery($cekStPermanent);
			$rowCekStPermanent = $this->koneksi->mysqlFetch($queryCekSt);
			$stCekNya = $rowCekStPermanent['permanentenddt'];
			if ($stCekNya == "Y") 
			{
				$nilai = "Permanent";
			}else{
				$sql3 = "SELECT ".$field." FROM ".$this->dbName.".tbltrans WHERE idcert = '".$idCert."' AND deletests=0 ORDER BY idtrans DESC LIMIT 1";
				$query3 = $this->koneksi->mysqlQuery($sql3);
				$row3 = $this->koneksi->mysqlFetch($query3);
				$nilai = $row3[$field];
			}
		}

		return $nilai;
	}

	function convertDateNya($tglPost)
	{
		$tgl = substr($tglPost,8,2);
		$bln = substr($tglPost,5,2);
		$thn = substr($tglPost,0,4);

		$doNya = $tgl."/".$bln."/".$thn;
		
		if ($tglPost == "0000-00-00") 
		{
			$doNya = "";
		}
		return $doNya;
	}


}


?>