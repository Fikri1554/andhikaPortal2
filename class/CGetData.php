<?php
class CGetData 
{
	function CGetData($koneksiOdbcId, $koneksiOdbc, $koneksiMysql)
	{
		$this->koneksi = $koneksiMysql;
		$this->koneksiOdbc = $koneksiOdbc;
		$this->koneksiOdbcId = $koneksiOdbcId;
		$tabel = "";
	}
	function insertComponentJob()
	{
		$vslCode = $_SESSION["vesselCode"];
		$usrInit = $_SESSION['userInitial'];
		$sNo = $this->getSnoAkhir($_POST['partOf']);
		$dateNowUser = date("Ymd#H:i")."#".$usrInit;
		$compcode = str_replace(" ","",$_POST['compCode1'].".".$_POST['compCode2'].".".$_POST['compCode3']);
		$stInsert = "";
		//$vslCode = $_SESSION["vesselCode"];
		$idEdit = $_POST["idEdit"];

		if ($idEdit != "") {
			$sql = "UPDATE tblvslcom".$vslCode." SET  compcode = '".$compcode."',compname = '".$_POST['compName']."',jobcode = '".$_POST['jobCode']."',freq = '".$_POST['plann']."',last_done = '".$_POST['lastDone']."',next_due = '".$_POST['nextDue']."',updusrdt = '".$dateNowUser."' WHERE s_no = '".$idEdit."'";
		}else{
			$sql = "INSERT INTO tblvslcom".$vslCode."(s_no,compcode,compname,jobcode,freq,last_done,next_due,addusrdt,deletests)VALUES('".$sNo."','".$compcode."','".$_POST['compName']."','".$_POST['jobCode']."','".$_POST['plann']."','".$_POST['lastDone']."','".$_POST['nextDue']."','".$dateNowUser."','0')";
		}
		try {
			$this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
			$stInsert = "sukses";
		} catch (Exception $ex) {
			$stInsert = "gagal =>".$ex;
		}

		return $stInsert;
	}
	function getSnoAkhir($partOf)
	{
		$vslCode = $_SESSION["vesselCode"];
		$noNew = "";
		$sql = "SELECT TOP 1
					CASE len(substring(s_no,2,10)+1)
					WHEN '1' THEN substring(s_no,1,1)+'000'+convert(varchar(10),(substring(s_no,2,10)+1))
					WHEN '2' THEN substring(s_no,1,1)+'00'+convert(varchar(10),(substring(s_no,2,10)+1))
					WHEN '3' THEN substring(s_no,1,1)+'0'+convert(varchar(10),(substring(s_no,2,10)+1))
					ELSE substring(s_no,1,1)+''+convert(varchar(10),(substring(s_no,2,10)+1))
				END AS newNo
			FROM tblvslcom".$vslCode."
			WHERE s_no LIKE '%".$partOf."%' ORDER BY s_no DESC";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$noNew = $row['newNo'];
		}
		return $noNew;
	}
	function getVessel()
	{
		$html = "";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT * from mstvessel WHERE stsactive = '1' AND vesname != '' order by vesname asc ");
		$html .= '<select id=\'namaKapal\' name=\'namaKapal\' class=\'elementMenu\' style=\'width:200px;\'>';
		$html.="<option value=\"0\">--SELECT VESSEL--</option>";
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			if ($_SESSION["vesselCode"] == $row['vslcode']) 
			{
				$html.="<option selected=\"selected\" value=\"".$row['vslcode']."\">&nbsp;".$row['vesname']."</option>";
			}
			else{
				$html.="<option value=\"".$row['vslcode']."\">&nbsp;".$row['vesname']."</option>";
			}
		}
        $html .= '</select>';
      	return $html;
	}
	function getSelectEquipment($action = "")
	{
		$html = "";
		$vslCode = $_SESSION["vesselCode"];
		$sql = "";
		if ($action == "all") {
			$sql = "SELECT * FROM mstequipment ORDER BY ename ASC";
			$html .= '<select id=\'namaEquipAll\' name=\'namaEquipAll\' class=\'elementMenu\' style=\'width:90%;\'>';
		}else{
			$sql = "SELECT * FROM mstequipment WHERE ecode IN(SELECT substring(compcode,1,3) AS compcode FROM tblvslcom".$vslCode." GROUP BY substring(compcode,1,3)) ORDER BY ename ASC";
			$html .= '<select id=\'namaEquip\' name=\'namaEquip\' class=\'elementMenu\' style=\'width:300px;\'>';
		}
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);

		if ($action !== "all") {
			$html.="<option value=\"\"> - SELECT EQUIPMENTS -</option>";
			$html.="<option value=\"0\">ALL EQUIPMENTS</option>";
		}else{
			$html.="<option value=\"\"></option>";
		}
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{	
			$html.="<option value=\"".str_replace(" ","",$row['ecode'])."\">&nbsp;".$row['ename']."</option>";
		}
        $html .= '</select>';
      	return $html;
	}
	function getSelectPart()
	{
		$html = "";
		// $vslCode = $_SESSION["vesselCode"];
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT * FROM mstpart");

		$html .= '<select id=\'partName\' name=\'partName\' class=\'elementMenu\' style=\'width:100px;\'>';
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{	
			$html.="<option value=\"".$row['partcode']."\">&nbsp;".$row['partname']."</option>";
		}
        $html .= '</select>';
      	return $html;
	}
	function getSelectJobHeading()
	{
		$html = "";
		// $vslCode = $_SESSION["vesselCode"];
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, 
				"SELECT * FROM mstjobdesc ORDER BY jobcode ASC");

		$html .= '<select id=\'jobHeading\' name=\'jobHeading\' class=\'elementMenu\' style=\'width:90%;\'>';
		$html.="<option value=\"\"></option>";
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{	
			$html.="<option value=\"".str_replace(" ","",$row['jobcode'])."\">&nbsp;".$row['jobhead']."</option>";
		}
        $html .= '</select>';
      	return $html;
	}
	function getDataComponent($actionEquip,$idEdit="",$export="")
	{
		$vesselCode = $_SESSION["vesselCode"];
		$dataTR = "";
		$whereNya = "";
		$sql = "";
		// $abcd = "";
		if ($actionEquip != "0" and $idEdit == "") 
		{
			$whereNya = "and SUBSTRING(compcode,1,3) = '".$actionEquip."'";
		}
		if ($idEdit != "") 
		{
			$whereNya = "and A.s_no = '".$idEdit."'";
		}

		$sql = "SELECT substring(A.compcode,1,3) as codeNya,B.jobhead,convert(varchar,last_done,110) as lastDone,convert(varchar,next_due,110) as nextDue,A.* FROM tblvslcom".$vesselCode." A LEFT JOIN mstjobdesc B ON A.jobcode = B.jobcode WHERE A.deletests = '0'".$whereNya." ORDER BY A.compcode ASC ";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		if ($idEdit != "") {
			$dataOut = array();
			while($row = $this->koneksiOdbc->odbcFetch($query))
			{
				$compCode = explode(".", $row['compcode']);
				$dataOut["sNo"] = $row['s_no'];
				$dataOut["partOf"] = substr($row['s_no'],0,1);
				$dataOut["jobhead"] = $row['jobhead'];
				$dataOut["compname"] = $row['compname'];
				$dataOut["jobcode"] = $row['jobcode'];
				$dataOut["freq"] = $row['freq'];
				$dataOut["lastDone"] = $row['lastDone'];
				$dataOut["nextDue"] = $row['nextDue'];
				$dataOut["comcode1"] = $compCode[0];
				$dataOut["comcode2"] = $compCode[1];
				$dataOut["comcode3"] = $compCode[2];
			}
			return $dataOut;
		}
		else{
			while($row = $this->koneksiOdbc->odbcFetch($query))
			{
				$chkBox = "";
				if ($export == "exportCompJob") 
				{
					$chkBox = "<td class=\"chkCompJob\" align=\"center\"><input id=\"idChkExpCompJob\" type=\"checkbox\" name=\"dataExportCompJob[]\" value=\"".$row['s_no']."\"></td>";
				}
				$dataTR .= "
							<tr id=\"idTR".$row['s_no']."\" class=\"clsTR\" onClick=\"trKlik('".$row['s_no']."');\" >
								".$chkBox."
								<td align=\"center\">".$row['s_no']."</td>
								<td align=\"center\">".$row['compcode']."</td>
								<td align=\"center\">".$row['jobcode']."</td>
								<td align=\"center\">".$row['freq']."</td>
								<td>".$row['compname']."</td>
								<td>".$row['jobhead']."</td>
								<td align=\"center\">".$row['lastDone']."</td>
								<td align=\"center\">".$row['nextDue']."</td>
								<td align=\"center\">".$row['run_hrs']."</td>
							</tr>
							";
			}
			return $dataTR;
		}
	}
	function getDataDescComp($actionCompDesc)
	{
		$vesselCode = $_SESSION["vesselCode"];
		$dataHtml = "";

		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT B.jobdesc FROM tblvslcom".$vesselCode." A LEFT JOIN mstjobdesc B ON A.jobcode = B.jobcode WHERE A.s_no = '".$actionCompDesc."'");
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$dataHtml .= $row['jobdesc'];
		}
		return $dataHtml;
	}
	function delData($idDel)
	{
		$vslCode = $_SESSION["vesselCode"];
		$stDel = "";
		$sql = "UPDATE tblvslcom".$vslCode." SET deletests = '1' WHERE s_no = '".$idDel."'";
		try {
			$this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
			$stDel = "Delete Success..!!";
		} catch (Exception $ex) {
			$stDel = "Failed..!! =>".$ex;
		}

		return $stDel;
	}
	//======== page UpdateJobDone ==========
	function getDataJobDone($searchNya = "")
	{
		$vslCode = $_SESSION["vesselCode"];
		$whereNya = "";
		// $monthNow = date("m");
		// $yearNow = date("Y");
		$dataTR = "";
		if($searchNya != "")
		{
			$whereNya = "AND A.compname LIKE '%".$searchNya."%' ";
		}

		$sql = "SELECT B.jobhead,convert(varchar,A.next_due,110) as nextDue,A.* FROM tblvslcom".$vslCode." A
				LEFT JOIN mstjobdesc B ON A.jobcode = B.jobcode
				WHERE A.next_due <= DATEADD(s,-1,DATEADD(mm, DATEDIFF(m,0,GETDATE())+1,0)) AND A.freq like '%M%' AND A.deletests = '0' ".$whereNya." ORDER BY A.next_due ASC ";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$dataTR .= "
						<tr>
							<td align=\"center\">".$row['s_no']."</td>
							<td>".$row['compname']."</td>
							<td>".$row['jobhead']."</td>
							<td align=\"center\">".$row['freq']."</td>
							<td align=\"center\">".$row['nextDue']."</td>
							<td></td>
							<td align=\"center\">
								<input class=\"chkJob\" style=\"margin:5px;\" type=\"checkbox\" name=\"actionJob[]\" value=\"".$row['s_no']."\">
							</td>
						</tr>
						";
		}

		return $dataTR;
	}
	function updateDoneJob()
	{
		$vslCode = $_SESSION["vesselCode"];
		$lastDoneNya = $_POST["actionDateDone"];
		$sNo = $_POST["actionDoneJob"];
		$stUpdate = "";
		try {
			for ($lan = 0; $lan < count($sNo); $lan++)
			{
				$sql = "UPDATE tblvslcom".$vslCode." SET next_due = (
						SELECT CASE SUBSTRING(freq,1,1)
						WHEN 'H' THEN dateadd(hour,convert(int,SUBSTRING(freq,2,5)),'$lastDoneNya-'+convert(varchar,day(next_due)))
						WHEN 'M' THEN dateadd(month,convert(int,SUBSTRING(freq,2,5)),'$lastDoneNya-'+convert(varchar,day(next_due)))
						WHEN 'W' THEN dateadd(week,convert(int,SUBSTRING(freq,2,5)),'$lastDoneNya-'+convert(varchar,day(next_due)))
						END AS next_due FROM tblvslcom".$vslCode."
						WHERE s_no = '".$sNo[$lan]."'),last_done = '$lastDoneNya-'+convert(varchar,day(last_done)
					)WHERE s_no = '".$sNo[$lan]."' ";
				$this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
			}
			$stUpdate = "Sukses";
		} catch (Exception $ex) {
			$stUpdate = "Gagal =>".$ex;
		}
		return $stUpdate;
	}
	function getDataUnschedule()
	{
		$vslCode = $_SESSION["vesselCode"];
		$monthNow = date("m");
		$yearNow = date("Y");
		$dataTR = "";

		$sql = " SELECT B.jobhead,convert(varchar,A.next_due,110) as nextDue,A.* 
				 FROM tblvslcom".$vslCode." A
				 LEFT JOIN mstjobdesc B ON A.jobcode = B.jobcode
				 WHERE NOT(year(A.next_due)='".$yearNow."' AND month(A.next_due) <= '".$monthNow."')
				 ORDER BY A.s_no ASC";

		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$dataTR .= "
						<tr class=\"clsTR\" id=\"idTR".$row['s_no']."\" onClick=\"actionClick1('".$row['s_no']."');\" ondblClick=\"actionClick('".$row['s_no']."');\">
							<td align=\"center\">".$row['s_no']."</td>
							<td>".$row['compname']."</td>
							<td>".$row['jobhead']."</td>
							<td style=\"display:none;\">".$row['next_due']."</td>
						</tr>
						";
		}

		return $dataTR;
	}
	function saveUnSchedule()
	{
		$vslCode = $_SESSION["vesselCode"];
		$code = $_POST["actionCodeUnsch"];
		$compName = $_POST["actionCompName"];
		$jobHead = $_POST["actionJob"];
		$remark = $_POST["actionRemark"];
		$nextDue = $_POST["actionNextDue"];
		$usrDate = date("Ymd#H:i")."#".$_SESSION['userInitial'];
		$stInsert = "";
		$jobdoneTemp = date("Y-m-").substr($nextDue,8,2);

		$sql = "INSERT INTO tbljobdonehis (vslcode,s_no,realdue,jobdone,remark,addusrdt) 
				VALUES('".$vslCode."','".$code."','".$nextDue."','".$jobdoneTemp."','".$remark."','".$usrDate."')";
		$sqlUpdate = "UPDATE tblvslcom".$vslCode." SET next_due = '".$jobdoneTemp."' WHERE s_no = '".$code."'";

		try {
			$this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
			$this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sqlUpdate);
			$stInsert = "sukses";
		} catch (Exception $ex) {
			$stInsert = "gagal =>".$ex;
		}

		return $stInsert;
	}
	//======== page MontlyCompRunHours ==========
	function getDataEquip($dateSearch = "")
	{
		$vslCode = $_SESSION["vesselCode"];
		$monthNow = date("m");
		$yearNow = date("Y");
		$dataTR = "";
		if ($dateSearch != "") {
			$ds = explode("-",$dateSearch);	
			$monthNow = $ds[1];
			$yearNow = $ds[0];
		}
		$chkMonth = $monthNow - 1;
		$monthBefore = $chkMonth;
		$yearBefore = $yearNow;
		if ($chkMonth == "0") {
			$monthBefore = 12;
			$yearBefore = $yearNow -1;
		}
		$sql = " 
				SELECT substring(A.compcode,1,3) as eCode,B.ename,convert(varchar,ISNULL(D.up2_runhrs,'0')) as monthBefore,convert(varchar,ISNULL(C.up2_runhrs,'0')) as monthNow,convert(varchar,ISNULL(C.overhaul,'0')) as overhaul,C.uniquekey
				FROM tblvslcom".$vslCode." A
				LEFT JOIN mstequipment B ON B.ecode = substring(A.compcode,1,3)
				LEFT JOIN tbleqprunhrs C ON C.ecode = substring(A.compcode,1,3) AND (C.runhrsdt is null OR (year(C.runhrsdt) = '".$yearNow."' AND month(C.runhrsdt) = '".$monthNow."'))
				LEFT JOIN(
					SELECT ecode,up2_runhrs FROM tbleqprunhrs WHERE (runhrsdt is null OR (year(runhrsdt) = '".$yearBefore."' AND month(runhrsdt) = '".$monthBefore."'))
				)D ON D.ecode = substring(A.compcode,1,3) 
				WHERE A.deletests=0 AND LEFT(A.freq,1)='H' AND CAST(RIGHT(A.freq,5) AS INT) > 500 and (c.deletests != '1' or c.deletests is null)
				GROUP BY substring(A.compcode,1,3),B.ename,C.up2_runhrs,C.cur_runhrs,C.overhaul,D.up2_runhrs,C.uniquekey
				";

		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$dataTR .= "
						<tr class=\"clsTR\" id=\"idTR".$row['eCode']."\" onClick=\"trKlik('".$row['eCode']."','1','".$row['uniquekey']."');\" >
							<td align=\"center\">".$row['eCode']."</td>
							<td>".$row['ename']."</td>
							<td align=\"center\">".$row['monthBefore']."</td>
							<td align=\"center\">".$row['overhaul']."</td>
							<td align=\"center\">".$row['monthNow']."</td>
						</tr>
						";
		}
		
		return $dataTR;
	}
	function getDataCompHours()
	{
		$vslCode = $_SESSION["vesselCode"];
		$dateSearch = $_POST['actionDateSearch'];
		$monthNow = date("m");
		$yearNow = date("Y");
		$dataTR = "";
		if ($dateSearch != "") 
		{
			$ds = explode("-", $dateSearch);
			$monthNow = $ds[1];
			$yearNow = $ds[0];
		}
		$chkMonth = $monthNow - 1;
		$monthBefore = $chkMonth;
		$yearBefore = $yearNow;
		$compCode = $_POST["actionCom"];
		if ($chkMonth == "0") {
			$monthBefore = 12;
			$yearBefore = $yearNow -1;
		}
		$sql = "
				SELECT DISTINCT(A.s_no),A.compcode,A.compname,A.jobcode,A.freq,B.jobhead,convert(varchar,ISNULL(D.up2_runhrs,0)) as monthBefore,convert(varchar,ISNULL(C.overhaul,0)) as overhaul,convert(varchar,ISNULL(C.up2_runhrs,0)) as monthNow,C.runhrsdt
				FROM tblvslcom".$vslCode." A
				LEFT JOIN mstjobdesc B ON B.jobcode = A.jobcode
				LEFT JOIN tblcomrunhrs C ON C.compcode = A.compcode and C.jobcode = A.jobcode and C.vslcode = '".$vslCode."' AND (C.runhrsdt is null OR (year(C.runhrsdt) = '".$yearNow."' AND month(C.runhrsdt) = '".$monthNow."'))
				LEFT JOIN(
					SELECT up2_runhrs,compcode,jobcode FROM tblcomrunhrs 
					WHERE (runhrsdt is null OR (year(runhrsdt) = '".$yearBefore."' AND month(runhrsdt) = '".$monthBefore."')) AND vslcode = '".$vslCode."'
				)D ON D.compcode = A.compcode AND D.jobcode = A.jobcode
				WHERE A.deletests=0 AND LEFT(A.freq,1)='H' AND CAST(RIGHT(A.freq,5) AS INT)>500
				
				AND substring(A.compcode,1,3) = '".$compCode."'
				";

		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$abs = $row['monthBefore'];
			$dataTR .= "
						<tr class=\"clsTR1\" id=\"idTR1".$row['s_no']."\" onClick=\"trKlik('".$row['s_no']."','2','');\">
							<td align=\"center\">".$row['s_no']."</td>
							<td>".$row['compcode']."</td>
							<td align=\"center\">".$row['jobcode']."</td>
							<td align=\"center\">".$row['freq']."</td>
							<td>".$row['compname']."</td>
							<td>".$row['jobhead']."</td>
							<td align=\"center\">".$row['monthBefore']."</td>
							<td align=\"center\">".$row['overhaul']."</td>
							<td align=\"center\">".$row['monthNow']."</td>
						</tr>
						";
		}
		
		return $dataTR;
	}
	function editDataCompHours()
	{
		$vslCode = $_SESSION["vesselCode"];
		$monthNow = date("m");
		$yearNow = date("Y");
		$dataTR = "";
		$dataOur = array();
		$idEditComp = $_POST["actionEditComp"];
		//$uniquekey = $_POST["actionUniqueKey"];
		$dateSearch = $_POST['actionDateSearch'];
		if ($dateSearch != "") 
		{
			$ds = explode("-", $dateSearch);
			$monthNow = $ds[1];
			$yearNow = $ds[0];
		}
		$chkMonth = $monthNow - 1;
		$monthBefore = $chkMonth;
		$yearBefore = $yearNow;
		$compCode = $_POST["actionCom"];
		if ($chkMonth == "0") {
			$monthBefore = 12;
			$yearBefore = $yearNow -1;
		}
		$sql = " 
				SELECT substring(A.compcode,1,3) as eCode,B.ename,convert(varchar,ISNULL(D.up2_runhrs,'0')) as monthBefore,convert(varchar,ISNULL(C.up2_runhrs,'0')) as monthNow,convert(varchar,ISNULL(C.overhaul,'0')) as overhaul,C.uniquekey
				FROM tblvslcom".$vslCode." A
				LEFT JOIN mstequipment B ON B.ecode = substring(A.compcode,1,3)
				LEFT JOIN tbleqprunhrs C ON C.ecode = substring(A.compcode,1,3) AND (C.runhrsdt is null OR (year(C.runhrsdt) = '".$yearNow."' AND month(C.runhrsdt) = '".$monthNow."'))
				LEFT JOIN(
					SELECT ecode,up2_runhrs FROM tbleqprunhrs WHERE (runhrsdt is null OR (year(runhrsdt) = '".$yearBefore."' AND month(runhrsdt) = '".$monthBefore."'))
				)D ON D.ecode = substring(A.compcode,1,3)
				WHERE A.deletests=0 AND LEFT(A.freq,1)='H' AND CAST(RIGHT(A.freq,5) AS INT) > 500
				AND substring(A.compcode,1,3) = '".$idEditComp."'
				GROUP BY substring(A.compcode,1,3),B.ename,C.up2_runhrs,C.cur_runhrs,C.overhaul,D.up2_runhrs,C.uniquekey
				";

		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		$rowComp = $this->koneksiOdbc->odbcFetch($query);
		$dataOur["dataOutComp"] = $rowComp;
		$sql2 = "
				SELECT A.s_no,A.compcode,A.compname,E.jobhead,A.jobcode,convert(varchar,ISNULL(D.up2_runhrs,0)) as monthBefore,convert(varchar,ISNULL(C.overhaul,0)) as overhaul,convert(varchar,ISNULL(C.up2_runhrs,0)) as monthNow,C.runhrsdt
				FROM tblvslcom".$vslCode." A
				LEFT JOIN mstjobdesc B ON B.jobcode = A.jobcode
				LEFT JOIN tblcomrunhrs C ON C.compcode = A.compcode and C.jobcode = A.jobcode and C.vslcode = '".$vslCode."' AND (C.runhrsdt is null OR (year(C.runhrsdt) = '".$yearNow."' AND month(C.runhrsdt) = '".$monthNow."'))
				LEFT JOIN(
					SELECT up2_runhrs,compcode,jobcode FROM tblcomrunhrs 
					WHERE (runhrsdt is null OR (year(runhrsdt) = '".$yearBefore."' AND month(runhrsdt) = '".$monthBefore."')) AND vslcode = '".$vslCode."'
				)D ON D.compcode = A.compcode AND D.jobcode = A.jobcode
				LEFT JOIN mstjobdesc E ON E.jobcode = A.jobcode
				WHERE A.deletests=0 AND LEFT(A.freq,1)='H' AND CAST(RIGHT(A.freq,5) AS INT)>500
				AND substring(A.compcode,1,3) = '".$idEditComp."'
				GROUP BY A.s_no,A.compcode,A.compname,E.jobhead,A.jobcode,convert(varchar,ISNULL(D.up2_runhrs,0)),convert(varchar,ISNULL(C.overhaul,0)),convert(varchar,ISNULL(C.up2_runhrs,0)),C.runhrsdt
				ORDER BY A.compcode ASC
				";

		$query2 = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql2);
		while($row2 = $this->koneksiOdbc->odbcFetch($query2))
		{
			$dataTR .= "
						<tr class=\"clsTR\" id=\"idTR".$row2['eCode']."\" >
							<td align=\"center\">
							<input type=\"text\" id=\"txtJobCode\" name=\"txtJobCode[]\" value=\"".$row2['jobcode']."\" style=\"display:none;\">
							<input type=\"text\" id=\"txtCompCode\" name=\"txtCompCode[]\" value=\"".$row2['compcode']."\" class=\"elementSearch\" disabled = \"disabled\" style=\"cursor:pointer;color:black;\">
							</td>
							<td>".$row2['compname']."</td>
							<td>".$row2['jobhead']."</td>
							<td align=\"center\" style=\"font-size: 14px;\">".$row2['monthBefore']."</td>
							<td align=\"center\">
								<input type=\"text\" id=\"txtOverhaulForm\" name=\"txtOverhaulForm[]\" value=\"".$row2['overhaul']."\" class=\"elementSearch\">
							</td>
							<td align=\"center\">
								<input type=\"text\" id=\"txtMonthNowForm\" name=\"txtMonthNowForm[]\" value=\"".$row2['monthNow']."\" class=\"elementSearch\">
							</td>
						</tr>
						";
		}
		$dataOur["dataOutEquip"] = $dataTR;

		return $dataOur;
	}
	function updateDataCompHours()
	{
		$vslCode = $_SESSION["vesselCode"];
		$equipOverhaul = $_POST['actionEquipOverhaul'];
		$equipThisMounth = $_POST['actionEquipThisMonth'];
		$equipCodeComp = $_POST['actionEquipCodeComp'];
		$equipDateSearch = $_POST['actionDateSearch'];
		$actionOverhaul = $_POST['actionOverhaul'];
		$actionMonthNow = $_POST['actionMonthNow'];
		$actionCompCode = $_POST['actionCompCode'];
		$actionJobCode = $_POST['actionJobCode'];
		$dateSearch = $_POST['actionDateSearch'];
		$stCekCompHrs = "";
		$monthNow = date("m");
		$yearNow = date("Y");
		$lastDateMonth = date("Y-m-t", strtotime(date("Y-m-t")));
		$usrDate = date("Ymd#H:i")."#".$_SESSION['userInitial'];

		if ($dateSearch != "") 
		{
			$ds = explode("-", $dateSearch);
			$monthNow = $ds[1];
			$yearNow = $ds[0];
			$lastDateMonth = date("Y-m-t", strtotime($dateSearch));
		}

		try {
			$sqlCekEqp = " SELECT * FROM tbleqprunhrs WHERE vslcode = '".$vslCode."' AND ecode = '".$equipCodeComp."' AND YEAR(runhrsdt) = '".$yearNow."' AND MONTH(runhrsdt) = '".$monthNow."' ";
			$stCekEqpHrs = $this->cekDataInTable($sqlCekEqp);

			if ($stCekEqpHrs == "0") {
				$sqlEqp = "INSERT INTO tbleqprunhrs(vslcode,ecode,runhrsdt,up2_runhrs,cur_runhrs,overhaul,deletests,addusrdt,updusrdt) VALUES('".$vslCode."','".$equipCodeComp."','".$lastDateMonth."','".$equipThisMounth."','0','".$equipOverhaul."','0','".".$usrDate."."','')";
			}else{
				$sqlEqp = "UPDATE tbleqprunhrs SET up2_runhrs = '".$equipThisMounth."',overhaul = '".$equipOverhaul."' WHERE vslcode = '".$vslCode."' AND ecode = '".$equipCodeComp."' AND YEAR(runhrsdt) = '".$yearNow."' AND MONTH(runhrsdt) = '".$monthNow."' ";
			}

			$this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sqlEqp);

			for ($lan=0; $lan < count($actionCompCode); $lan++) 
			{ 
				$sqlCek = " SELECT * FROM tblcomrunhrs WHERE compcode = '".$actionCompCode[$lan]."' AND jobcode = '".$actionJobCode[$lan]."' AND vslcode = '".$vslCode."' AND YEAR(runhrsdt) = '".$yearNow."' AND MONTH(runhrsdt) = '".$monthNow."' ";
				$stCekCompHrs = $this->cekDataInTable($sqlCek);
				if ($stCekCompHrs == "0") 
				{
					$sql = " INSERT INTO tblcomrunhrs(vslcode,compcode,jobcode,runhrsdt,up2_runhrs,overhaul,deletests,addusrdt,updusrdt)VALUES('".$vslCode."','".$actionCompCode[$lan]."','".$actionJobCode[$lan]."','".$lastDateMonth."','".$actionMonthNow[$lan]."','".$actionOverhaul[$lan]."','0','".$usrDate."','')
					";
				}else{
					$sql = "
							UPDATE tblcomrunhrs SET up2_runhrs = '".$actionMonthNow[$lan]."',overhaul = '".$actionOverhaul[$lan]."',updusrdt = '".$usrDate."'
							WHERE compcode = '".$actionCompCode[$lan]."' AND jobcode = '".$actionJobCode[$lan]."' AND vslcode = '".$vslCode."' AND YEAR(runhrsdt) = '".$yearNow."' AND MONTH(runhrsdt) = '".$monthNow."'
							";
				}

				$this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
			}
			$stInsert = "sukses";
		} catch (Exception $ex) {
			$stInsert = "gagal =>".$ex;
		}	
		return $stInsert;
	}
	function delDataEquip()
	{
		$vslCode = $_SESSION["vesselCode"];
		$eCode = $_POST['actionDelEquip'];
		$dateSearch = $_POST['actionDateSearch'];
		$dataTable = "";
		$stDel = "";
		$dataOut = array();
		$monthNow = date("m");
		$yearNow = date("Y");

		if ($dateSearch != "") 
		{
			$ds = explode("-", $dateSearch);
			$monthNow = $ds[1];
			$yearNow = $ds[0];
			$lastDateMonth = date("Y-m-t", strtotime($dateSearch));
		}

		$sql = " 
				UPDATE tbleqprunhrs SET deletests = '1' WHERE ecode = '".$eCode."' AND (runhrsdt is null OR (year(runhrsdt) = '".$yearNow."' AND month(runhrsdt) = '".$monthNow."')) 
			   ";
		try {
			$this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
			$stDel = "Delete Success..!!";
			$dataTable = $this->getDataEquip($dateSearch);
		} catch (Exception $ex) {
			$stDel = "Failed..!! =>".$ex;
		}
		$dataOut["stDel"] = $stDel;
		$dataOut["dataTR"] = $dataTable;
		
		return $dataOut;
	}
	//======== page MasterComp&Job ==========
	function getMstEquipJob()
	{
		$sql = " 
				SELECT distinct(substring(A.compcode,1,3)) AS eCode,B.ename
				FROM mstvslcom A
				LEFT JOIN mstequipment B ON B.ecode = substring(A.compcode,1,3)
				ORDER BY substring(A.compcode,1,3) asc
				";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$dataTR .= "
						<tr class=\"clsTR\" id=\"idTR".$row['eCode']."\" onClick=\"trKlik('".$row['eCode']."','1');\">
							<td align=\"center\">".$row['eCode']."</td>
							<td>".$row['ename']."</td>
						</tr>
						";
		}
		return $dataTR;
	}
	function getMstCompJob()
	{
		$idEcode = $_POST["actionMstCompJob"];
		$sql = "
				SELECT A.*,B.jobhead FROM mstvslcom A
				LEFT JOIN mstjobdesc B ON B.jobcode = A.jobcode
				WHERE substring(A.compcode,1,3) = '".$idEcode."'
				";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$dataTR .= "
						<tr class=\"clsTR1\" id=\"idTR1".$row['s_no']."\" onClick=\"trKlik('".$row['s_no']."','2');\">
							<td align=\"center\">".$row['s_no']."</td>
							<td>".$row['compcode']."</td>
							<td>".$row['jobcode']."</td>
							<td>".$row['freq']."</td>
							<td>".$row['compname']."</td>
							<td>".$row['jobhead']."</td>
							<td>".$row['last_done']."</td>
							<td>".$row['next_due']."</td>
						</tr>
						";
		}
		return $dataTR;
	}
	function getMstDescComp()
	{
		$actionCompDesc = $_POST["actionMstCompDesc"];
		$dataHtml = "";
		
		$sql = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT B.jobdesc FROM mstvslcom A LEFT JOIN mstjobdesc B ON A.jobcode = B.jobcode WHERE A.s_no = '".$actionCompDesc."'");
		
		while($row = $this->koneksiOdbc->odbcFetch($sql))
		{
			$dataHtml .= $row['jobdesc'];
		}
		return $dataHtml;
	}
	//======== page MasterJobDesc ==========
	function getInitJobCode()
	{
		$optionNya = "";
		$sql = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, " SELECT * FROM mstwkclass WHERE deletests = '0' ORDER BY workcode ASC ");
		
		$optionNya .= '<option value=\'\'></option>';
		while($row = $this->koneksiOdbc->odbcFetch($sql))
		{
			$optionNya .= '<option value=\''.$row['workcode'].'\'>'.$row["workcode"].'</option>';
		}
		return $optionNya;
	}
	function getMstListJobDesc()
	{
		$sql = "SELECT *,REPLACE(jobcode,' ', '') as jCode FROM mstjobdesc WHERE deletests = '0' ORDER BY jobcode ASC";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$dataTR .= "
						<tr class=\"clsTR\" id=\"idTR".$row['jCode']."\" onClick=\"trKlik('".$row['jCode']."');\">
							<td align=\"center\">".$row['jobcode']."</td>
							<td>".$row['jobhead']."</td>
						</tr>
						";
		}
		return $dataTR;
	}
	function getMstJobDesc()
	{
		$actionMstJobDesc = $_POST["actionMstJobDesc"];
		$dataHtml = "";
		
		$sql = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, " SELECT jobdesc FROM mstjobdesc WHERE deletests = '0' AND jobcode = '".$actionMstJobDesc."' ORDER BY jobcode ASC ");
		
		while($row = $this->koneksiOdbc->odbcFetch($sql))
		{
			$dataHtml .= $row['jobdesc'];
		}
		return $dataHtml;
	}
	function getMstNewCode()
	{
		$initialCode = $_POST["actionMstgetNewCode"];

		$newCode = "";
		$sql = "
				SELECT TOP 1 CONVERT(INT,SUBSTRING(jobcode,2,5))+1 AS newCode FROM mstjobdesc
				WHERE jobcode LIKE '%".$initialCode."%' ORDER BY jobcode DESC
				";

		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);

		$row = $this->koneksiOdbc->odbcFetch($query);
		$newCode = $row[newCode];
		if ($newCode == "") 
		{
			$newCode = "001";
		}else{
			$jmlChar = strlen($newCode);
			if ($jmlChar == "1") {
				$newCode = "00".$newCode;
			}else if($jmlChar == "2")
			{
				$newCode = "0".$newCode;
			}
		}
		$newCode = $initialCode.$newCode;

		return $newCode;
	}
	function saveJobDesc()
	{
		$slcCode = $_POST["actionMstSlcCode"];
		$txtJobCode = $_POST["actionTxtJobCode"];
		$code = $_POST["actionCode"];
		$descCode = $this->konversiQuotesNya($_POST["actionDescJobCode"]);
		$usrDate = date("Ymd#H:i")."#".$_SESSION['userInitial'];
		$stInsert = "";

		$sqlCekJobDesc = " SELECT * FROM mstjobdesc WHERE jobcode = '".$code."' ";
		$stCek = $this->cekDataInTable($sqlCekJobDesc);

		if ($stCek == '0') {
			$sql = " INSERT INTO mstjobdesc(jobcode,jobhead,jobdesc,deletests,addusrdt,updusrdt) VALUES('".$code."','".$txtJobCode."','".$descCode."','0','".$usrDate."','') ";
		}else{
			$sql = " UPDATE mstjobdesc SET jobhead = '".$txtJobCode."',jobdesc = '".$descCode."', updusrdt = '".$usrDate."' WHERE jobcode = '".$code."' ";
		}

		try {
			$this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
			// $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sqlUpdate);
			$stInsert = "sukses";
		} catch (Exception $ex) {
			$stInsert = "gagal =>".$ex;
		}
		return $stInsert;
	}
	function editMstJobDesc()
	{
		$dataTR = "";
		$dataOur = array();
		$idEditMstDescJob = $_POST["actionEditMstDescJob"];

		$sql = " 
				SELECT substring(jobhead,1,1) as initJobCode, * FROM mstjobdesc WHERE deletests = '0' AND jobcode = '".$idEditMstDescJob."'
				";

		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		$rowComp = $this->koneksiOdbc->odbcFetch($query);
		$dataOur = $rowComp;

		return $dataOur;
	}
	function delMstJobDesc()
	{
		$code = $_POST['actionDelMstJobDesc'];
		$sql = " UPDATE mstjobdesc SET deletests = '1' WHERE jobcode = '".$code."' ";

		try {
			$this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
			$stDel = "Delete Success..!!";
		} catch (Exception $ex) {
			$stDel = "Failed..!! =>".$ex;
		}
		return $stDel;
	}
	//======== page MasterWorkClass =============
	function getMstListWorkClass()
	{
		$sql = "SELECT * FROM mstwkclass WHERE deletests = '0' ORDER BY workcode ASC";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$dataTR .= "
						<tr class=\"clsTR\" id=\"idTR".$row['workcode']."\" onClick=\"trKlik('".$row['workcode']."');\">
							<td align=\"center\">".$row['workcode']."</td>
							<td>".$row['workclass']."</td>
						</tr>
						";
		}
		return $dataTR;
	}
	function editMstWorkClass()
	{
		$dataTR = "";
		$idEditMstWC = $_POST["actionEditMstWorkClass"];

		$sql = "SELECT * FROM mstwkclass WHERE deletests = '0' AND workcode = '".$idEditMstWC."'";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		$rowComp = $this->koneksiOdbc->odbcFetch($query);

		return $rowComp;
	}
	function saveMstWorkClass()
	{
		$idEditWC = $_POST["actionMstIdWorkClass"];
		$txtWorkClass = $_POST["actionTxtWorkClass"];
		$usrDate = date("Ymd#H:i")."#".$_SESSION['userInitial'];
		$stInsert = "";

		$sql = " UPDATE mstwkclass SET workclass = '".$txtWorkClass."',updusrdt = '".$usrDate."' WHERE workcode = '".$idEditWC."' ";
		try {
			$this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
			$stInsert = "sukses";
		} catch (Exception $ex) {
			$stInsert = "gagal =>".$ex;
		}
		return $stInsert;
	}
	//======== page Routine Job =================
	function getRoutinJob()
	{
		$vslCode = $_SESSION["vesselCode"];
		$sql = " SELECT B.jobhead,A.* FROM tblvslcom".$vslCode." A
				 LEFT JOIN mstjobdesc B ON B.jobcode = A.jobcode
				 WHERE A.deletests = 0 AND substring(A.freq,1,1) <> 'M' 
				 AND CAST(RIGHT(A.freq,5) AS INT) <= 500 AND A.type <> 'T'
				 ORDER BY A.s_no ASC ";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$dataTR .= "
						<tr>
							<td align=\"center\">".$row['s_no']."</td>
							<td align=\"center\">".$row['compcode']."</td>
							<td align=\"center\">".$row['jobcode']."</td>
							<td>".$row['freq']."</td>
							<td>".$row['compname']."(".$row['jobhead'].")</td>
						</tr>
						";
		}
		return $dataTR;
	}
	//======== page Maintenance Report ==========
	function getDataMaintenance($sNo = "")
	{
		$vslCode = $_SESSION["vesselCode"];
		$startPage = $_POST["actionStPage"];
		$actionPage = $_POST["actionPage"];
		$dataOut = array();
		$dt = array();
		$jmlData = 0;
		$whereNya = "";
		if ($sNo !== "") 
		{
			$whereNya = "AND A.s_no = '".$sNo."'";
			$startPage = 0;
		}
		$sql = "SELECT A.s_no,A.compcode,A.compname,A.jobcode,B.jobhead,B.jobdesc 
				FROM tblvslcom".$vslCode." A
				LEFT JOIN mstjobdesc B ON A.jobcode = B.jobcode
				WHERE A.deletests = '0' ".$whereNya."
				";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$dt["sNo"] = $row['s_no'];
			$dt["compcode"] = $row['compcode'];
			$dt["compname"] = $row['compname'];
			$dt["jobcode"] = $row['jobcode'];
			$dt["jobhead"] = $row['jobhead'];
			$dt["jobdesc"] = $row['jobdesc'];
			$jmlData = $jmlData +1;
			array_push($dataOut, $dt);
		}
		if ($actionPage == "next" || $actionPage == "startUp") {
			$nowPage = $startPage +1;
		}else if($actionPage == "prev"){
			$nowPage = $startPage -1;
			$startPage = $nowPage-1;
			if ($nowPage == 0) {$nowPage = 1;}
		}else if($actionPage == "first"){
			$startPage = 0;
			$nowPage = 1;
		}else if($actionPage == "last"){
			$nowPage = $startPage;
			$startPage = $nowPage-1;
		}
		$dataOut[$startPage]["countData"] = $jmlData;
		$dataOut[$startPage]["jml"] = $nowPage." - ".$jmlData;
		$dataOut[$startPage]["nextPage"] = $nowPage;

		return $dataOut[$startPage];
	}
	//======== page Work list month =============
	function getDataWorkList($actionDataNya = "")
	{
		$dateSearch = "";
		$dateSearch = $_POST["actionDateSearchWorkList"];
		$vslCode = $_SESSION["vesselCode"];
		$dateNowNya = date('Y-m');
		$dataOut = array();
		$sNoThisMonth = "";
		$sNoOtherMonth = "";
		$sNo = "";

		if ($dateSearch == "") {
			$whereNya = "WHERE A.next_due <= DATEADD(s,-1,DATEADD(mm, DATEDIFF(m,0,GETDATE())+1,0)) AND SUBSTRING(A.freq,1,1) = 'M' AND A.deletests = '0' ";
		}else{
			$whereNya = "WHERE A.next_due <= DATEADD(s,-1,DATEADD(mm, DATEDIFF(m,0,'".$dateSearch."-01')+1,0)) AND SUBSTRING(A.freq,1,1) = 'M' AND A.deletests = '0' ";
			$dateNowNya = $dateSearch;
		}
		$dataTR = "";

		$sql = "SELECT B.jobhead,convert(varchar,A.next_due,110) as nextDue, convert(varchar,A.last_done,110) as lastDone,A.* FROM tblvslcom".$vslCode." A
				LEFT JOIN mstjobdesc B ON A.jobcode = B.jobcode ".$whereNya." ORDER BY A.next_due DESC ";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$colorTR ="";
			//jika next duenya = bulan sekarang
			if (date('Y-m',strtotime($row['next_due'])) == $dateNowNya) 
			{
				if ($sNoThisMonth == "") 
				{
					$sNoThisMonth .= $row['s_no'];
				}else{
					$sNoThisMonth .= ", ".$row['s_no'];
				}
			}else{
				if ($sNoOtherMonth == "") 
				{
					$sNoOtherMonth .= $row['s_no'];
				}else{
					$sNoOtherMonth .= ", ".$row['s_no'];
				}
				$colorTR = "background-color:#f5cdbf;";
			}
			$dataTR .= "
						<tr style=\"".$colorTR."\">
							<td align=\"center\">".$row['s_no']."</td>
							<td>".$row['compcode']."</td>
							<td>".$row['jobcode']."</td>
							<td align=\"center\">".$row['freq']."</td>
							<td>".$row['compname']."</td>
							<td>".$row['jobhead']."</td>
							<td>".$row['lastDone']."</td>
							<td align=\"center\">".$row['nextDue']."</td>
							<td></td>
						</tr>
						";
		}
		$dataOut["dataTR"] = $dataTR;
		$dataOut["sNoThisMonth"] = $sNoThisMonth;
		$dataOut["sNoOtherMonth"] = $sNoOtherMonth;
		return $dataOut;
	}
	//======== page Equip/component Maintenance Forecast =============
	function getDataMaintenanceForecaset()
	{
		$vslCode = $_SESSION["vesselCode"];
		$Tnow = date('Y');
		$Mnow = date('m');
		$freq = "";
		$blackStar = "<label>*</label>";
		$redStar = "<label style=\"color:red;\">*</label>";
		
		$sql = " SELECT B.jobhead,SUBSTRING(A.freq,2,5) as jmlBln, A.* FROM tblvslcom".$vslCode." A
				 LEFT JOIN mstjobdesc B ON A.jobcode = B.jobcode
				 WHERE A.deletests = '0' and SUBSTRING(A.freq,1,1) = 'M'
				 ORDER BY A.compcode,A.s_no ASC ";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);

		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$ds = explode("-",$row['next_due']);
			$ld = explode("-",$row['last_done']);
			$freq = $row['jmlBln'];
			$col1 = "";
			$col2 = "";
			$col3 = "";
			$col4 = "";
			$col5 = "";
			$col6 = "";
			$col7 = "";
			$col8 = "";
			$col9 = "";
			$col10 = "";
			$col11 = "";
			$col12 = "";
			$col13 = "";
			$col14 = "";
			$col15 = "";
			$col16 = "";
			$col17 = "";
			$col18 = "";

			if ($ds[0] < $Tnow) //jika tahunnya kurang dari skrg di cari datanya sampe tahun ini
			{
				for ($hal=0; $hal <=500; $hal++) 
				{ 
					$ds[1] = $ds[1]+$freq;
					if ($ds[1] > 12) 
					{
						$ds[1] = $ds[1] - 12;
						$ds[0] = $ds[0] +1;
					}
					if ($ds[0] == $Tnow) {
						$hal = 500;
					}
				}
			}
			$nb = $ds[1]+$freq;
			for ($lan=1; $lan <= 18; $lan++) 
			{ 
				if ($lan <= 12)
				{
					if($ds[1] == $lan AND $ds[0] == $Tnow) 
					{
						//${'col'.$lan} = $blackStart;
						if ($ds[1] < $Mnow) 
						{
							${'col'.$lan} = $redStar;
						}else{
							${'col'.$lan} = $blackStar;
						}
						$ds[1] = $ds[1]+$freq;
						if ($ds[1] > 12) 
						{
							$ds[1] = $ds[1] - 12;
							$ds[0] = $ds[0] +1;
						}
					} else {
						${'col'.$lan} = "";
					}
				}else{
					if ($ds[1] < $lan) {
						$ds[1] = $ds[1]+12;
					}
					if ($ds[1] == $lan) {
						//${'col'.$lan} = "*";
						if ($ds[1] < $Mnow) 
						{
							${'col'.$lan} = $redStar;
						}else{
							${'col'.$lan} = $blackStar;
						}
						$ds[1] = $ds[1] + $freq;
						$ds[0] = $Tnow +1;
					}else {
						${'col'.$lan} = "";
					}
					if ($nb > 18) {
						//${'col'.$lan} = "";
					}
				}
				if ($ld[0] == $Tnow AND $ld[1] == $lan) 
				{
					${'col'.$lan} = "D";
				}
			}
			$dataTR .= "
						<tr style=\"font-size: 8pt;\">
							<td align=\"center\">".$row['s_no']."</td>
							<td align=\"center\">".$row['compcode']."</td>
							<td align=\"center\">".$row['jobcode']."</td>
							<td align=\"center\">".$row['freq']."</td>
							<td>".$row['compname']."<br>".$row['jobhead']."</td>
							<td align=\"center\">".$col1."</td>
							<td align=\"center\">".$col2."</td>
							<td align=\"center\">".$col3."</td>
							<td align=\"center\">".$col4."</td>
							<td align=\"center\">".$col5."</td>
							<td align=\"center\">".$col6."</td>
							<td align=\"center\">".$col7."</td>
							<td align=\"center\">".$col8."</td>
							<td align=\"center\">".$col9."</td>
							<td align=\"center\">".$col10."</td>
							<td align=\"center\">".$col11."</td>
							<td align=\"center\">".$col12."</td>
							<td align=\"center\">".$col13."</td>
							<td align=\"center\">".$col14."</td>
							<td align=\"center\">".$col15."</td>
							<td align=\"center\">".$col16."</td>
							<td align=\"center\">".$col17."</td>
							<td align=\"center\">".$col18."</td>
						</tr>
						";
		}
		
		return $dataTR;
	}

	function cekDataInTable($sql)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		$jmlRow = $this->koneksiOdbc->odbcNRows($query);

		return $jmlRow;
	}

	function getExportCompJob()
	{
		$vslCode = $_SESSION["vesselCode"];
		$dataSno = $_POST["actionExportCompJob"];
		$initVessel = $this->mstVessel($vslCode);
		$namaKapal = $this->getNameVesselByvslCode($vslCode);
		$queryTeks = "";
		$jobHeadTeks = "";
		$sNo = "";
		$dir = "../exportDoc/";
		$statusAction = "";
		$dataOut = array();

		try {
			$dateTimeSrv = $this->tglServer().$this->jamServer();
			for ($lan=0; $lan < count($dataSno); $lan++) 
			{ 
				if ($sNo == "") 
				{
					$sNo = "'".$dataSno[$lan]."'";
				}else{
					$sNo .= ",'".$dataSno[$lan]."'";
				}
			}
			$sql = " SELECT B.jobhead,B.jobdesc,SUBSTRING(A.freq,2,5) as jmlBln, A.* FROM tblvslcom".$vslCode." A
					 LEFT JOIN mstjobdesc B ON A.jobcode = B.jobcode
					 WHERE A.deletests = '0' and A.s_no IN (".$sNo.")
					 ORDER BY A.s_no ASC ";
			$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
			while($row = $this->koneksiOdbc->odbcFetch($query))
			{
				$queryTeks .= "INSERT INTO tblvslcom (s_no,vesselcode,type,compcode,compname,jobcode,freq,avg_hrs,run_date,run_hrs,last_done,next_due,remark,addusrdt) VALUES('".$row['s_no']."','".$initVessel."','".$row['type']."','".$row['compcode']."','".$row['compname']."','".$row['jobcode']."','".$row['freq']."','".$row['avg_hrs']."','".$row['run_date']."','".$row['run_hrs']."','".$row['last_done']."','".$row['next_due']."','".$row['remark']."','".$row['addusrdt']."')::".$row['s_no']."#-#-";

				$jobHeadTeks .= $row['jobcode']."::".$row['jobhead']."::".$row['jobdesc']."#-#-";
			}
			$queryTeks .= "<=>".$jobHeadTeks;
			$genTeks = $this->encrypted("andhikalain", $queryTeks);
			$fileExport = str_replace(" ","_",$namaKapal)."=_=UPDATE_JOB_DONE=_=".$dateTimeSrv.".txt";
			$cnmsize = $dir.$fileExport;
			$cnm = fopen($cnmsize, "w");
			fwrite($cnm, $genTeks);
			fclose($cnm);

			$dataOut['fileName'] = $fileExport;
			$dataOut['statusAction'] = "Success";
		} catch (Exception $e) {
			$dataOut['fileName'] = "";
			$dataOut['statusAction'] = "Failed =>".$e->getMessage();
		}

		return $dataOut;
	}

	function konversiQuotesNya($string) 
	{ 
		$search = array('"', "'"); 
		$replace = array("\&#34;", "\&#39;");
	
		return str_replace($search, $replace, $string); 
	}

	function nl2br2Nya($string) 
	{
		$string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
		return $string;
	} 

	function encrypted($key, $string)
	{	
		$iv = mcrypt_create_iv(
		mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC),
		MCRYPT_DEV_URANDOM
		);
	
		$encrypted = base64_encode(
			$iv .
			mcrypt_encrypt(
				MCRYPT_RIJNDAEL_128,
				hash('sha256', $key, true),
				$string,
				MCRYPT_MODE_CBC,
				$iv
			)
		);
		return $encrypted;
	}

	function decrypted($key, $encryptedString)
	{
		$data = base64_decode($encryptedString);
		$iv = substr($data, 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));
	
		$decrypted = rtrim(
			mcrypt_decrypt(
				MCRYPT_RIJNDAEL_128,
				hash('sha256', $key, true),
				substr($data, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC)),
				MCRYPT_MODE_CBC,
				$iv
			),
			"\0"
		);
		return $decrypted;
	}

	function tglServer()
	{
		$query = "SELECT CONVERT(VARCHAR,GETDATE(),111) as waktu";
		$queryExec = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId,$query);
		$row = $this->koneksiOdbc->odbcFetch($queryExec);
		$dataTime = str_replace("/","",$row['waktu']);
		return $dataTime;
	}

	function jamServer()
	{
		$query = "SELECT CONVERT(VARCHAR,GETDATE(),108) as waktu";
		$queryExec = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId,$query);
		$row = $this->koneksiOdbc->odbcFetch($queryExec);
		$dataTime = str_replace(":","",$row['waktu']);
		return $dataTime;
	}

	function mstVessel($codeVessel)
	{
		$vesselName = array(
						"81"=>"KAN",
						"82"=>"PAR",
						"83"=>"NAR",
						"79"=>"VID",
						"80"=>"VEN"
					);
		return $vesselName[$codeVessel];
	}

	function getNameVesselByvslCode($vslCode = "")
	{
		$query = "SELECT RTRIM(vesname) as vslName,* FROM mstvessel WHERE vslcode = '".$vslCode."'";
		$queryExec = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId,$query);
		$row = $this->koneksiOdbc->odbcFetch($queryExec);
		$vslName = $row['vslName'];
		return $vslName;
	}

	function getCompanyVesselByVslCode($vslCode = "")
	{
		$query = " SELECT * FROM mstvessel WHERE stsactive = '1' AND vslcode = '".$vslCode."' ";
		$queryExec = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId,$query);
		$row = $this->koneksiOdbc->odbcFetch($queryExec);
		$dtOut = $row['company'];
		return $dtOut;
	}

	function getExportRoutinJob()
	{
		ob_start();
		$nmVsl = $_POST['actionVslName'];		
		$vslCode = $_SESSION["vesselCode"];
		$vslComp = "";

		$sql = " SELECT B.jobhead,A.* FROM tblvslcom".$vslCode." A
				 LEFT JOIN mstjobdesc B ON B.jobcode = A.jobcode
				 WHERE A.deletests = 0 AND substring(A.freq,1,1) <> 'M' 
				 AND CAST(RIGHT(A.freq,5) AS INT) <= 500 AND A.type <> 'T'
				 ORDER BY A.s_no ASC ";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		//$row = $this->koneksiOdbc->odbcFetch($query);
		$vslComp = $this->getCompanyVesselByVslCode($vslCode);

		header("Content-Type: application/vnd.ms-excel");
		echo "<table width=\"100%\">";
			echo "<tr>
					<td colspan=\"5\" align=\"center\">
						<label style=\"font-size: 28pt;font-weight: bold;\">".$vslComp."</label>
					</td>
				</tr>";
			echo "<tr>
					<td colspan=\"5\" align=\"center\">
						<label id=\"lblVesselName\">".$nmVsl."</label>
					</td>
				</tr>";
			echo "<tr colspan=\"2\">
					<td colspan=\"5\" align=\"center\">
						<label> EQUIPMENT/COMPONENT MAINTENANCE (LESS THAN 1 MONTH)</label>
					</td>
				</tr>";
		echo "</table>";

		echo "<table border=\"1\">";
		echo "<tr style=\"background-color:#D2D2D2;\">";
			echo "<td align=\"center\" style=\"width: 60%;\">COMPONENT & JOB HEADING</td>";
			echo "<td align=\"center\" >PMS CODE</td>";
			echo "<td align=\"center\" >COMP CODE</td>";
			echo "<td align=\"center\" >JOB CODE</td>";
			echo "<td align=\"center\" >FREQ</td>";
		echo "</tr>";
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			echo "<tr>";
			echo "<td>".$row['compname']."</td>";
			echo "<td align=\"center\" >".$row['s_no']."</td>";
			echo "<td align=\"center\" >".$row['compcode']."</td>";
			echo "<td>".$row['jobcode']."</td>";
			echo "<td>".$row['freq']."</td>";
			echo "</tr>";
		}
		echo "</table>";

		header("Content-disposition: attachment; filename=exportRoutinJob.xls");
		ob_end_flush();
	}

	function getDataDeficiencyMst()
	{
		$vslCode = $_SESSION["vesselCode"];
		$no = 1;
		$sql = " SELECT convert(varchar,master_date_join,103) as mstDateJoin,convert(varchar,master_date_sign_off,103) as mstDateSignOff,convert(varchar,co_date_join,103) as coDateJoin,convert(varchar,co_date_sign_off,103) as coDateSignOff,convert(varchar,ce_date_join,103) as ceDateJoin,convert(varchar,ce_date_sign_off,103) as ceDateSignOff,convert(varchar,inspection_date,103) as insDate,* FROM tbldeficiency_mst WHERE vslcode = '".$vslCode."' AND status_delete = '0' order by id DESC ";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$dataTR .= "
						<tr id=\"idTR".$row['id']."\" class=\"clsTR\" onClick=\"trKlik('".$row['id']."');\">
							<td align=\"center\">".$no."</td>
							<td align=\"center\"><b>".strtoupper($row['master'])."</b><br><i style=\"font-size:11px;\">(".$row['mstDateJoin']." - ".$row['mstDateSignOff'].")</i></td>
							<td align=\"center\"><b>".strtoupper($row['chief_officer'])."</b><br><i style=\"font-size:11px;\">(".$row['coDateJoin']." - ".$row['coDateSignOff'].")</i></td>
							<td align=\"center\"><b>".strtoupper($row['chief_engineer'])."</b><br><i style=\"font-size:11px;\">(".$row['ceDateJoin']." - ".$row['ceDateSignOff'].")</i></td>
							<td align=\"center\"><b>".strtoupper($row['inspector_name'])."</b><br><i style=\"font-size:11px;\">(".$row['insDate'].")</i></td>
						</tr>
						";
			$no ++;
		}
		return $dataTR;
	}

	function addEditDeficiency()
	{

		$vslCode = $_SESSION["vesselCode"];
		$usrInit = $_SESSION['userInitial'];
		$actionNya = $_POST["actionDeficiency"];
		$idEdit = $_POST["idEditMst"];
		$mstName = $_POST["mstName"];
		$mstDateJoin = $_POST["mstDateJoin"];
		$mstSignOff = $_POST["mstSignOff"];
		$coName = $_POST["coName"];
		$coDateJoin = $_POST["coDateJoin"];
		$coSignOff = $_POST["coSignOff"];
		$ceName = $_POST["ceName"];
		$ceDateJoin = $_POST["ceDateJoin"];
		$ceSignOff = $_POST["ceSignOff"];
		$insName = $_POST["insName"];
		$insDate = $_POST["insDate"];

		$dateNowUser = date("Ymd#H:i")."#".$usrInit;

		if ($actionNya == "add") 
		{
			$sql = "INSERT INTO tbldeficiency_mst (vslcode,master,master_date_join,master_date_sign_off,chief_officer,co_date_join,co_date_sign_off,chief_engineer,ce_date_join,ce_date_sign_off,inspector_name,inspection_date,add_user_date) VALUES ('".$vslCode."','".$mstName."','".$mstDateJoin."','".$mstSignOff."','".$coName."','".$coDateJoin."','".$coSignOff."','".$ceName."','".$ceDateJoin."','".$ceSignOff."','".$insName."','".$insDate."','".$dateNowUser."')";
		}else{
			$sql = "UPDATE tbldeficiency_mst SET master = '".$mstName."',master_date_join = '".$mstDateJoin."',master_date_sign_off = '".$mstSignOff."',chief_officer = '".$coName."',co_date_join = '".$coDateJoin."',co_date_sign_off = '".$coSignOff."',chief_engineer = '".$ceName."',ce_date_join = '".$ceDateJoin."',ce_date_sign_off = '".$ceSignOff."',inspector_name = '".$insName."',inspection_date = '".$insDate."',upd_user_date = '".$dateNowUser."' WHERE id = '".$idEdit."' ";
		}
		try {
			$this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
			$stInsert = "sukses";
		} catch (Exception $ex) {
			$stInsert = "gagal =>".$ex;
		}

		return $stInsert;
	}

	function getDataEditDeficiencyMst()
	{
		$idEdit = $_POST["actionUpdateDefMst"];

		$sql = "SELECT convert(varchar,master_date_join,110) as mstDateJoin,convert(varchar,master_date_sign_off,110) as mstDateSignOff,convert(varchar,co_date_join,110) as coDateJoin,convert(varchar,co_date_sign_off,110) as coDateSignOff,convert(varchar,ce_date_join,110) as ceDateJoin,convert(varchar,ce_date_sign_off,110) as ceDateSignOff,convert(varchar,inspection_date,110) as insDate, * FROM tblDeficiency_mst WHERE status_delete = '0' AND id = '".$idEdit."'";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		$dataEdit = $this->koneksiOdbc->odbcFetch($query);

		return $dataEdit;
	}

	function delDeficiencyMst()
	{
		$idDelNya = $_POST['idDelDeficiencyMst'];
		$sql = " UPDATE tbldeficiency_mst SET status_delete = '1' WHERE id = '".$idDelNya."' ";

		try {
			$this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
			$stDel = "Delete Success..!!";
		} catch (Exception $ex) {
			$stDel = "Failed..!! =>".$ex;
		}
		return $stDel;
	}

	function getDataDeficiencyDetail($idDefMst = "")
	{
		$idDefMst = $_POST["actionSetDefDetail"];
		$no = 1;
		$sql = " SELECT convert(varchar,expected_date_complete,103) as expDateComplete,convert(varchar,date_complete,103) as dateComplete,convert(varchar,upd_from_vessel,103) as updFromVessel, * FROM tbldeficiency_detail WHERE id_def_mst = '".$idDefMst."' AND status_delete = '0' order by id DESC ";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$dataTR .= "
						<tr id=\"idTRDetail".$row['id']."\" class=\"clsTRTbl2\" onClick=\"trKlikDetail('".$row['id']."');\">
							<td align=\"center\">".$no."</td>
							<td align=\"center\">".$row['deficiency']."</td>
							<td align=\"center\">".$row['done_by']."</td>
							<td align=\"center\">".$row['time_given']." Day</td>
							<td align=\"center\">".$row['expDateComplete']."</td>
							<td align=\"center\">".$row['dateComplete']."</td>
							<td align=\"center\">".$row['updFromVessel']."</td>
							<td align=\"center\">".$row['verify']."</td>
							<td align=\"center\">".$row['status_done']."</td>
							<td align=\"left\">".$row['remark']."</td>
						</tr>
						";
			$no ++;
		}
		return $dataTR;
	}

	function addEditDefDetail()
	{
		
		$vslCode = $_SESSION["vesselCode"];
		$usrInit = $_SESSION['userInitial'];
		$actionNya = $_POST["actionDeficiencyDetail"];
		$idEditDetail = $_POST["idEditDetail"];
		$idDefMst = $_POST["idDeficiencyMst"];
		$txtDeficiency = $_POST["txtDeficiency"];
		$slcDoneBy = $_POST["slcDoneBy"];
		$txtTimeGiven = $_POST["txtTimeGiven"];
		$txtExpDateOfCompletion = $_POST["txtExpDateOfCompletion"];
		$txtDateComplte = $_POST["txtDateComplte"];
		$txtVerify = $_POST["txtVerify"];
		$txtUpdateFromVessel = $_POST["txtUpdateFromVessel"];
		$txtProperlyDone = $_POST["txtProperlyDone"];
		$txtRemark = $_POST["txtRemark"];

		$dateNowUser = date("Ymd#H:i")."#".$usrInit;

		if ($actionNya == "add") 
		{
			$sql = "INSERT INTO tbldeficiency_detail(id_def_mst,deficiency,done_by,time_given,expected_date_complete,date_complete,verify,status_done,upd_from_vessel,remark,add_user_date) VALUES('".$idDefMst."','".$txtDeficiency."','".$slcDoneBy."','".$txtTimeGiven."','".$txtExpDateOfCompletion."','".$txtDateComplte."','".$txtVerify."','".$txtProperlyDone."','".$txtUpdateFromVessel."','".$txtRemark."','".$dateNowUser."')";
		}else{
			$sql = "UPDATE tbldeficiency_detail SET deficiency = '".$txtDeficiency."',done_by = '".$slcDoneBy."',time_given = '".$txtTimeGiven."',expected_date_complete = '".$txtExpDateOfCompletion."',date_complete = '".$txtDateComplte."',verify = '".$txtVerify."',status_done = '".$txtProperlyDone."',upd_from_vessel = '".$txtUpdateFromVessel."',remark = '".$txtRemark."',upd_user_date = '".$dateNowUser."' WHERE id = '".$idEditDetail."' ";
		}

		try {
			$this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
			$stInsert = "sukses";
		} catch (Exception $ex) {
			$stInsert = "gagal =>".$ex;
		}

		return $stInsert;
	}

	function getDataEditDeficiencyDetail()
	{
		$idEdit = $_POST["actionUpdateDefDetail"];

		$sql = " SELECT convert(varchar,expected_date_complete,103) as expDateComplete,convert(varchar,date_complete,103) as dateComplete,convert(varchar,upd_from_vessel,103) as updFromVessel, * FROM tbldeficiency_detail WHERE id = '".$idEdit."' AND status_delete = '0'  ";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		$dataEdit = $this->koneksiOdbc->odbcFetch($query);

		return $dataEdit;
	}

	function delDeficiencyDetail()
	{
		$idDelNya = $_POST['idDelDeficiencyDetail'];
		$sql = " UPDATE tbldeficiency_detail SET status_delete = '1' WHERE id = '".$idDelNya."' ";

		try {
			$this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
			$stDel = "Delete Success..!!";
		} catch (Exception $ex) {
			$stDel = "Failed..!! =>".$ex;
		}
		return $stDel;
	}

	function getExportCompAllJob($actionEquip = "")
	{
		ob_start();
		$vslName = $_POST['actionVslName'];
		$vesselCode = $_SESSION["vesselCode"];
		$whereNya = "";
		$vslComp = "";

		if ($actionEquip != "0") 
		{
			$whereNya = "and SUBSTRING(compcode,1,3) = '".$actionEquip."'";
		}
		$sql = " SELECT substring(A.compcode,1,3) as codeNya,B.jobhead,convert(varchar,last_done,110) as lastDone,convert(varchar,next_due,110) as nextDue,B.jobdesc,B.jobdesc,A.* FROM tblvslcom".$vesselCode." A LEFT JOIN mstjobdesc B ON A.jobcode = B.jobcode WHERE A.deletests = '0'".$whereNya." ";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, $sql);
		//$row = $this->koneksiOdbc->odbcFetch($query);
		$vslComp = $this->getCompanyVesselByVslCode($vesselCode);

		header("Content-Type: application/vnd.ms-excel");
		echo "<table width=\"100%\">";
			echo "<tr>
					<td colspan=\"10\" align=\"center\">
						<label style=\"font-size: 28pt;font-weight: bold;\">".$vslComp."</label>
					</td>
				</tr>";
			echo "<tr>
					<td colspan=\"10\" align=\"center\">
						<label id=\"lblVesselName\">".$vslName."</label>
					</td>
				</tr>";
			echo "<tr colspan=\"2\">
					<td colspan=\"10\" align=\"center\">
						<label> ALL COMPONENT & JOB</label>
					</td>
				</tr>";
		echo "</table>";

		echo "<table border=\"1\">";
		echo "<tr height=\"10\" style=\"background-color:#D2D2D2;\" >";
			echo "<td align=\"center\" >CODE</td>";
			echo "<td align=\"center\" >COMPONENT</td>";
			echo "<td align=\"center\" >JOB</td>";
			echo "<td align=\"center\" >FREQ</td>";
			echo "<td align=\"center\" >COMPONENT NAME</td>";
			echo "<td align=\"center\" >JOB HEADING</td>";
			echo "<td align=\"center\" >LAST DONE</td>";
			echo "<td align=\"center\" >NEXT DUE</td>";
			echo "<td align=\"center\" >RUN HOURS</td>";
			echo "<td align=\"center\" >DESCRIPTION</td>";
		echo "</tr>";
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			echo "<tr>";
			echo "<td align=\"center\" >".$row['codeNya']."</td>";
			echo "<td align=\"center\" >".$row['compcode']."</td>";
			echo "<td align=\"center\" >".$row['jobcode']."</td>";
			echo "<td align=\"center\" >".$row['freq']."</td>";
			echo "<td align=\"center\" >".$row['compname']."</td>";
			echo "<td align=\"center\" >".$row['jobhead']."</td>";
			echo "<td align=\"center\" >".$row['lastDone']."</td>";
			echo "<td align=\"center\" >".$row['nextDue']."</td>";
			echo "<td align=\"center\" >".$row['run_hrs']."</td>";
			echo "<td>".$row['jobdesc']."</td>";
			echo "</tr>";
		}
		echo "</table>";

		header("Content-disposition: attachment; filename=exportRoutinJob.xls");
		ob_end_flush();
	}

	

}


?>