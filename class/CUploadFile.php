<?php
// require_once ("../../shipManagement/class/excel_reader2.php");
class CUploadFile
{
	function CUploadFile($mysqlKoneksi, $CPublic, $dbName)
	{
		$this->koneksi = $mysqlKoneksi;
		$this->cPublic = $CPublic;
		$this->dbName = $dbName;
		$tabel = "";
	}

	function uploadFileNoon($path, $typeData = "")
	{
		$usrInit = $_SESSION['userInitial'];
		$vesselName = $_SESSION["vesselName"];
		$dateNowUser = date("Ymd#H:i")."#".$usrInit;
		$dataOut = array();
		$fileDir = $_FILES["file"]["tmp_name"];
		$sheetNya = $_POST['sheetNya'] - 1;
		$dataCExcel = new Spreadsheet_Excel_Reader($fileDir);
		
		$dataOut['masterName'] = $dataCExcel->val(3, "E", $sheetNya);// kome ke-1=baris, koma ke-2=kolom,koma ke-3=sheetnya
		$dataOut['satelitePhone'] = $dataCExcel->val(4, "E", $sheetNya);
		$dataOut['sateliteEmail'] = $dataCExcel->val(5, "E", $sheetNya);
		$dataOut['noonPosition'] = $dataCExcel->val(7, "E", $sheetNya)."-".$dataCExcel->val(7, "F", $sheetNya);
		$dataOut['voyageNo'] = $dataCExcel->val(9, "E", $sheetNya);
		$vd = $dataCExcel->sheets[$sheetNya]['cellsInfo'][9][8]['raw'];//sheet[$sheetNya]=posisi sheetNya,['cellsInfo'][9]=barisNya,[8]=kolomNya	
		$dataOut['voyageDate'] = $this->getUnixDateTime($vd,"tglNya");
		$dataOut['voyageFrom'] = $dataCExcel->val(10, "E", $sheetNya);
		$dataOut['voyageTo'] = $dataCExcel->val(10, "H", $sheetNya);
		$dataOut['currentLoc'] = $dataCExcel->val(11, "E", $sheetNya);
		$dataOut['lat'] = $dataCExcel->val(13, "E", $sheetNya);
		$dataOut['long'] = $dataCExcel->val(14, "E", $sheetNya);
		$dataOut['course'] = $dataCExcel->val(15, "E", $sheetNya);
		$dataOut['distancetoRun'] = $dataCExcel->val(16, "E", $sheetNya);
		$dataOut['totalDistance'] = $dataCExcel->val(17, "E", $sheetNya);
		$dataOut['streamingTime'] = $dataCExcel->val(18, "E", $sheetNya);
		$dataOut['totalSteaming'] = $dataCExcel->val(19, "E", $sheetNya);
		$dataOut['averageSpeed'] = $dataCExcel->val(20, "E", $sheetNya);
		$dataOut['generalAverageSpeed'] = $dataCExcel->val(21, "E", $sheetNya);
		$dataOut['distanceToGo'] = $dataCExcel->val(22, "E", $sheetNya);
		$ed = $dataCExcel->sheets[$sheetNya]['cellsInfo'][23][5]['raw'];
		$eh = $dataCExcel->sheets[$sheetNya]['cellsInfo'][23][8]['raw'];
		$ttlEta = $ed+$eh;
		$dataOut['etaDate'] = $this->getUnixDateTime($ttlEta,"tglNya");
		$dataOut['etaHours'] = $this->getUnixDateTime($ttlEta,"jamNya");
		$dataOut['bySpeed'] = $dataCExcel->val(24, "E", $sheetNya);
		$dataOut['rpm'] = $dataCExcel->val(25, "E", $sheetNya);
		$dataOut['weather'] = $dataCExcel->val(26, "E", $sheetNya);
		$dataOut['robMfo'] = $dataCExcel->val(29, "E", $sheetNya);
		$dataOut['robMdo'] = $dataCExcel->val(30, "E", $sheetNya);
		$dataOut['robHsd'] = $dataCExcel->val(31, "E", $sheetNya);
		$dataOut['robLo'] = $dataCExcel->val(32, "E", $sheetNya);
		$dataOut['robFw'] = $dataCExcel->val(33, "E", $sheetNya);
		$dataOut['supplyMfo'] = $dataCExcel->val(36, "E", $sheetNya);
		$dataOut['supplyMdo'] = $dataCExcel->val(37, "E", $sheetNya);
		$dataOut['supplyHsd'] = $dataCExcel->val(38, "E", $sheetNya);
		$dataOut['supplyLo'] = $dataCExcel->val(39, "E", $sheetNya);
		$dataOut['supplyFw'] = $dataCExcel->val(40, "E", $sheetNya);
		$dataOut['currentMfo'] = $dataCExcel->val(43, "E", $sheetNya);
		$dataOut['currentMdo'] = $dataCExcel->val(44, "E", $sheetNya);
		$dataOut['currentHsd'] = $dataCExcel->val(45, "E", $sheetNya);
		$dataOut['currentLo'] = $dataCExcel->val(46, "E", $sheetNya);
		$dataOut['currentFw'] = $dataCExcel->val(47, "E", $sheetNya);
		$dataOut['gradeAName'] = $dataCExcel->val(52, "E", $sheetNya);
		$dataOut['gradeACurrentRob'] = $dataCExcel->val(52, "G", $sheetNya);
		$dataOut['gradeAPumpRate'] = $dataCExcel->val(52, "I", $sheetNya);
		$gAD = $dataCExcel->sheets[$sheetNya]['cellsInfo'][52][12]['raw'];
		$gAH = $dataCExcel->sheets[$sheetNya]['cellsInfo'][52][13]['raw'];
		$ttlGA = $gAD+$gAH;
		$dataOut['gradeAEstCompl'] = $this->getUnixDateTime($ttlGA,"tglNya")." ".$this->getUnixDateTime($ttlGA,"jamNya");
		$dataOut['gradeBName'] = $dataCExcel->val(53, "E", $sheetNya);
		$dataOut['gradeBCurrentRob'] = $dataCExcel->val(53, "G", $sheetNya);
		$dataOut['gradeBPumpRate'] = $dataCExcel->val(53, "I", $sheetNya);
		$gBD = $dataCExcel->sheets[$sheetNya]['cellsInfo'][53][12]['raw'];
		$gBH = $dataCExcel->sheets[$sheetNya]['cellsInfo'][53][13]['raw'];
		$ttlGB = $gBD+$gBH;
		$dataOut['gradeBEstCompl'] = $this->getUnixDateTime($ttlGB,"tglNya")." ".$this->getUnixDateTime($ttlGB,"jamNya");
		$dataOut['gradeCName'] = $dataCExcel->val(54, "E", $sheetNya);
		$dataOut['gradeCCurrentRob'] = $dataCExcel->val(54, "G", $sheetNya);
		$dataOut['gradeCPumpRate'] = $dataCExcel->val(54, "I", $sheetNya);
		$gCD = $dataCExcel->sheets[$sheetNya]['cellsInfo'][54][12]['raw'];
		$gCH = $dataCExcel->sheets[$sheetNya]['cellsInfo'][54][13]['raw'];
		$ttlGC = $gCD+$gCH;
		$dataOut['gradeCEstCompl'] = $this->getUnixDateTime($ttlGC,"tglNya")." ".$this->getUnixDateTime($ttlGC,"jamNya");
		$dataOut['gradeDName'] = $dataCExcel->val(55, "E", $sheetNya);
		$dataOut['gradeDCurrentRob'] = $dataCExcel->val(55, "G", $sheetNya);
		$dataOut['gradeDPumpRate'] = $dataCExcel->val(55, "I", $sheetNya);
		$gDD = $dataCExcel->sheets[$sheetNya]['cellsInfo'][55][12]['raw'];
		$gDH = $dataCExcel->sheets[$sheetNya]['cellsInfo'][55][13]['raw'];
		$ttlGD = $gDD+$gDH;
		$dataOut['gradeDEstCompl'] = $this->getUnixDateTime($ttlGD,"tglNya")." ".$this->getUnixDateTime($ttlGD,"jamNya");
		if($typeData == "")
		{
			return $dataOut;
		}else{
			$stInsert = "";
			$cekData = $this->cekData($dataOut['voyageNo'],"tbluploadnoon");
			if($cekData == 0)
			{
				try {
					$expGradeA = explode(" ", $dataOut['gradeAEstCompl']);
					$expGradeB = explode(" ", $dataOut['gradeBEstCompl']);
					$expGradeC = explode(" ", $dataOut['gradeCEstCompl']);
					$expGradeD = explode(" ", $dataOut['gradeDEstCompl']);
					$sql = "INSERT INTO ".$this->dbName.".tbluploadnoon(vessel,master_name,satellite_phone,satellite_email,position_report,voyage_no,voyage_date,voyage_from,voyage_to,current_location,latitude,longitude,course,distance_to_run,total_distance,streaming_time,total_streaming,average_speed,general_average,distance_to,eta_date,eta_hour,by_speed,rpm,weather,remain_on_board_mfo,remain_on_board_mdo,remain_on_board_hsd,remain_on_board_lo,remain_on_board_fw,supply_actual_mfo,supply_actual_mdo,supply_actual_hsd,supply_actual_lo,supply_actual_fw,consumption_mfo,consumption_mdo,consumption_hsd,consumption_lo,consumption_fw,cargoA_name,cargoA_currentRob,cargoA_pumpRate,cargoA_date,cargoA_hour,cargoB_name,cargoB_currentRob,cargoB_pumpRate,cargoB_date,cargoB_hours,cargoC_name,cargoC_currentRob,cargoC_pumpRate,cargoC_date,cargoC_hours,cargoD_name,cargoD_currentRob,cargoD_pumpRate,cargoD_date,cargoD_hours,add_usr)VALUES('".$vesselName."','".$dataOut['masterName']."','".$dataOut['satelitePhone']."','".$dataOut['sateliteEmail']."','".$dataOut['noonPosition']."','".$dataOut['voyageNo']."','".$dataOut['voyageDate']."','".$dataOut['voyageFrom']."','".$dataOut['voyageTo']."','".$dataOut['currentLoc']."','".$dataOut['lat']."','".$dataOut['long']."','".$dataOut['course']."','".$dataOut['distancetoRun']."','".$dataOut['totalDistance']."','".$dataOut['streamingTime']."','".$dataOut['totalSteaming']."','".$dataOut['averageSpeed']."','".$dataOut['generalAverageSpeed']."','".$dataOut['distanceToGo']."','".$dataOut['etaDate']."','".$dataOut['etaHours']."','".$dataOut['bySpeed']."','".$dataOut['rpm']."','".$dataOut['weather']."','".$dataOut['robMfo']."','".$dataOut['robMdo']."','".$dataOut['robHsd']."','".$dataOut['robLo']."','".$dataOut['robFw']."','".$dataOut['supplyMfo']."','".$dataOut['supplyMdo']."','".$dataOut['supplyHsd']."','".$dataOut['supplyLo']."','".$dataOut['supplyFw']."','".$dataOut['currentMfo']."','".$dataOut['currentMdo']."','".$dataOut['currentHsd']."','".$dataOut['currentLo']."','".$dataOut['currentFw']."','".$dataOut['gradeAName']."','".$dataOut['gradeACurrentRob']."','".$dataOut['gradeAPumpRate']."','".$expGradeA[0]."','".$expGradeA[1]."','".$dataOut['gradeBName']."','".$dataOut['gradeBCurrentRob']."','".$dataOut['gradeBPumpRate']."','".$expGradeB[0]."','".$expGradeB[1]."','".$dataOut['gradeCName']."','".$dataOut['gradeCCurrentRob']."','".$dataOut['gradeCPumpRate']."','".$expGradeC[0]."','".$expGradeC[1]."','".$dataOut['gradeDName']."','".$dataOut['gradeDCurrentRob']."','".$dataOut['gradeDPumpRate']."','".$expGradeD[0]."','".$expGradeD[1]."','".$dateNowUser."')";
					// print_r($sql);exit;
					$this->koneksi->mysqlQuery($sql);
				$stInsert = "sukses";
				} catch (Exception $ex) {
					$stInsert = "gagal =>".$ex;
				}
			}else{
				$idNya = "";				
				try {
					$query = $this->koneksi->mysqlQuery("SELECT * FROM ".$this->dbName.".tbluploadnoon WHERE voyage_no = '".$dataOut['voyageNo']."' AND deletests=0;"); 
					while($row = $this->koneksi->mysqlFetch($query))
					{
						$idNya = $row['id'];
					}
					$extGradeA = explode(" ", $dataOut['gradeAEstCompl']);
					$extGradeB = explode(" ", $dataOut['gradeBEstCompl']);
					$extGradeC = explode(" ", $dataOut['gradeCEstCompl']);
					$extGradeD = explode(" ", $dataOut['gradeDEstCompl']);
					$sqlupdNoon = " UPDATE ".$this->dbName.".tbluploadnoon SET master_name ='".$dataOut['masterName']."',satellite_phone ='".$dataOut['satelitePhone']."',satellite_email ='".$dataOut['sateliteEmail']."',position_report ='".$dataOut['noonPosition']."',voyage_no ='".$dataOut['voyageNo']."',voyage_date ='".$dataOut['voyageDate']."',voyage_from ='".$dataOut['voyageFrom']."',voyage_to ='".$dataOut['voyageTo']."',current_location ='".$dataOut['currentLoc']."',latitude ='".$dataOut['lat']."',longitude ='".$dataOut['long']."',course ='".$dataOut['course']."',distance_to_run ='".$dataOut['distancetoRun']."',total_distance ='".$dataOut['totalDistance']."',streaming_time ='".$dataOut['streamingTime']."',total_streaming ='".$dataOut['totalSteaming']."',average_speed ='".$dataOut['averageSpeed']."',general_average ='".$dataOut['generalAverageSpeed']."',distance_to ='".$dataOut['distanceToGo']."',eta_date ='".$dataOut['etaDate']."',eta_hour ='".$dataOut['etaHours']."',by_speed ='".$dataOut['bySpeed']."',rpm ='".$dataOut['rpm']."',weather ='".$dataOut['weather']."',remain_on_board_mfo ='".$dataOut['robMfo']."',remain_on_board_mdo ='".$dataOut['robMdo']."',remain_on_board_hsd ='".$dataOut['robHsd']."',remain_on_board_lo ='".$dataOut['robLo']."',remain_on_board_fw ='".$dataOut['robFw']."',supply_actual_mfo ='".$dataOut['supplyMfo']."',supply_actual_mdo ='".$dataOut['supplyMdo']."',supply_actual_hsd ='".$dataOut['supplyHsd']."',supply_actual_lo ='".$dataOut['supplyLo']."',supply_actual_fw ='".$dataOut['supplyFw']."',consumption_mfo ='".$dataOut['currentMfo']."',consumption_mdo ='".$dataOut['currentMdo']."',consumption_hsd ='".$dataOut['currentHsd']."',consumption_lo ='".$dataOut['currentLo']."',consumption_fw ='".$dataOut['currentFw']."',cargoA_name ='".$dataOut['gradeAName']."',cargoA_currentRob ='".$dataOut['gradeACurrentRob']."',cargoA_pumpRate ='".$dataOut['gradeAPumpRate']."',cargoA_date ='".$extGradeA[0]."',cargoA_hour ='".$extGradeA[1]."',cargoB_name ='".$dataOut['gradeBName']."',cargoB_currentRob ='".$dataOut['gradeBCurrentRob']."',cargoB_pumpRate ='".$dataOut['gradeBPumpRate']."',cargoB_date ='".$extGradeB[0]."',cargoB_hours ='".$extGradeB[1]."',cargoC_name ='".$dataOut['gradeCName']."',cargoC_currentRob ='".$dataOut['gradeCCurrentRob']."',cargoC_pumpRate ='".$dataOut['gradeCPumpRate']."',cargoC_date ='".$extGradeC[0]."',cargoC_hours ='".$extGradeC[1]."',cargoD_name ='".$dataOut['gradeDName']."',cargoD_currentRob ='".$dataOut['gradeDCurrentRob']."',cargoD_pumpRate ='".$dataOut['gradeDPumpRate']."',cargoD_date ='".$extGradeD[0]."',cargoD_hours ='".$extGradeD[1]."' WHERE id = '".$idNya."' ";
					$this->koneksi->mysqlQuery($sqlupdNoon);

					$stInsert = "sukses";
				} catch (Exception $ed) {
					$stInsert = "gagal =>".$ed;
				}
			}
			return $stInsert;
		}
	}

	function getDataUploadNoon($idGet = "",$searchNya = "")
	{
		$no = 1;
		$idNya = $_POST['id'];
		$dataTR = "";
		$whereNya = "";
		$vesselName = $_SESSION["vesselName"];
		if($idGet != "")
		{
			$whereNya = " AND id = '".$idGet."'";
		}

		if($searchNya == "" AND $idGet == "")
		{
			$whereNya = " AND MONTH(voyage_date) = MONTH(CURRENT_DATE()) AND YEAR(voyage_date) = YEAR(CURRENT_DATE())";
		}else if($searchNya != "" AND $idGet == "")
		{
			$whereNya = $searchNya;
		}

		$query = $this->koneksi->mysqlQuery("SELECT * FROM ".$this->dbName.".tbluploadnoon WHERE vessel = '".$vesselName."' AND deletests = '0'".$whereNya." ORDER BY id DESC ");
		if($idGet == "")
		{
			while($row = $this->koneksi->mysqlFetch($query))
			{
				$dataTR .= "
							<tr>
								<td align=\"center\">".$no."</td>
								<td style=\"padding-left:3px;\">
									<a class=\"spanLogout\" onclick=\"onClickView('".$row['id']."');\" >".$row['master_name']."</a>
								</td>
								<td style=\"padding-left:3px;\">".$row['position_report']."</td>
								<td align=\"center\">".$row['voyage_no']."</td>
								<td align=\"center\">".$row['voyage_date']."</td>
								<td align=\"center\">".$row['voyage_from']." - ".$row['voyage_to']."</td>
								<td style=\"padding-left:3px;\">".$row['current_location']."</td>
								<td style=\"padding-left:3px;\">".$row['weather']."</td>
							</tr>
							";
				$no ++;
			}
		}else{
			while($row = $this->koneksi->mysqlFetch($query))
			{
				$dataOut['masterName'] = $row['master_name'];
				$dataOut['satelitePhone'] = $row['satellite_phone'];
				$dataOut['sateliteEmail'] = $row['satellite_email'];
				$dataOut['noonPosition'] = $row['position_report'];
				$dataOut['voyageNo'] = $row['voyage_no'];
				$dataOut['voyageDate'] = $row['voyage_date'];
				$dataOut['voyageFrom'] = $row['voyage_from'];
				$dataOut['voyageTo'] = $row['voyage_to'];
				$dataOut['currentLoc'] = $row['current_location'];
				$dataOut['lat'] = $row['latitude'];
				$dataOut['long'] = $row['longitude'];
				$dataOut['course'] = $row['course'];
				$dataOut['distancetoRun'] = $row['distance_to_run'];
				$dataOut['totalDistance'] = $row['total_distance'];
				$dataOut['streamingTime'] = $row['streaming_time'];
				$dataOut['totalSteaming'] = $row['total_streaming'];
				$dataOut['averageSpeed'] = $row['average_speed'];
				$dataOut['generalAverageSpeed'] = $row['general_average'];
				$dataOut['distanceToGo'] = $row['distance_to'];
				$dataOut['etaDate'] = $row['eta_date'];
				$dataOut['etaHours'] = $row['eta_hour'];
				$dataOut['bySpeed'] = $row['by_speed'];
				$dataOut['rpm'] = $row['rpm'];
				$dataOut['weather'] = $row['weather'];
				$dataOut['robMfo'] = $row['remain_on_board_mfo'];
				$dataOut['robMdo'] = $row['remain_on_board_mdo'];
				$dataOut['robHsd'] = $row['remain_on_board_hsd'];
				$dataOut['robLo'] = $row['remain_on_board_lo'];
				$dataOut['robFw'] = $row['remain_on_board_fw'];
				$dataOut['supplyMfo'] = $row['supply_actual_mfo'];
				$dataOut['supplyMdo'] = $row['supply_actual_mdo'];
				$dataOut['supplyHsd'] = $row['supply_actual_hsd'];
				$dataOut['supplyLo'] = $row['supply_actual_lo'];
				$dataOut['supplyFw'] = $row['supply_actual_fw'];
				$dataOut['currentMfo'] = $row['consumption_mfo'];
				$dataOut['currentMdo'] = $row['consumption_mdo'];
				$dataOut['currentHsd'] = $row['consumption_hsd'];
				$dataOut['currentLo'] = $row['consumption_lo'];
				$dataOut['currentFw'] = $row['consumption_fw'];
				$dataOut['gradeAName'] = $row['cargoA_name'];
				$dataOut['gradeACurrentRob'] = $row['cargoA_currentRob'];
				$dataOut['gradeAPumpRate'] = $row['cargoA_pumpRate'];
				$dataOut['gradeAEstCompl'] = $row['cargoA_date']." ".$row['cargoA_hour'];
				$dataOut['gradeBName'] = $row['cargoB_name'];
				$dataOut['gradeBCurrentRob'] = $row['cargoB_currentRob'];
				$dataOut['gradeBPumpRate'] = $row['cargoB_pumpRate'];
				$dataOut['gradeBEstCompl'] = $row['cargoB_date']." ".$row['cargoB_hours'];
				$dataOut['gradeCName'] =  $row['cargoC_name'];
				$dataOut['gradeCCurrentRob'] = $row['cargoC_currentRob'];
				$dataOut['gradeCPumpRate'] = $row['cargoC_pumpRate'];
				$dataOut['gradeCEstCompl'] = $row['cargoC_date']." ".$row['cargoC_hours'];
				$dataOut['gradeDName'] = $row['cargoD_name'];
				$dataOut['gradeDCurrentRob'] = $row['cargoD_currentRob'];
				$dataOut['gradeDPumpRate'] = $row['cargoD_pumpRate'];
				$dataOut['gradeDEstCompl'] = $row['cargoD_date']." ".$row['cargoD_hours'];
			}
			$dataTR = $dataOut;
			// print_r($dataTR);exit;
		}

		return $dataTR;
	}

	function uploadFileOil($typeData = "")
	{
		$usrInit = $_SESSION['userInitial'];
		$vesselName = $_SESSION["vesselName"];
		$dateNowUser = date("Ymd#H:i")."#".$usrInit;
		$dataOut = array();
		$fileDir = $_FILES["file"]["tmp_name"];
		$sheetNya = $_POST['sheetNya'] - 1;
		$findCharNya = array("*","?","'","-");
		$dataCExcel = new Spreadsheet_Excel_Reader($fileDir);

		$dV = explode("/", $dataCExcel->val(7, "D", $sheetNya));
		$voyNo = $dV[0];
		$voyType = $dV[1];
		$voyYear = $dV[2];

		$dataOut['masterName'] = $dataCExcel->val(3, "F", $sheetNya);
		$dataOut['satelitePhone'] = $dataCExcel->val(4, "F", $sheetNya);
		$dataOut['sateliteEmail'] = $dataCExcel->val(5, "F", $sheetNya);
		$dataOut['voyageNo'] = str_replace(" ","",$dataCExcel->val(7, "D", $sheetNya));
		$dataOut['oilPosition'] = $dataCExcel->val(7, "G", $sheetNya)."-".$dataCExcel->val(7, "H", $sheetNya);
		$dataOut['inPort'] = $dataCExcel->val(7, "M", $sheetNya);
		$codeAADate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][10][4]['raw'];
		$codeAATime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][10][6]['raw'];
		$ttlCodeAA = $codeAADate+$codeAATime;
		$dataOut['codeAADate'] = $this->getUnixDateTime($ttlCodeAA,"tglNya");
		$dataOut['codeAATime'] = $this->getUnixDateTime($ttlCodeAA,"jamNya");
		$dataOut['previousPort'] = $dataCExcel->val(10, "M", $sheetNya);
		$dataOut['actualDistance'] = $dataCExcel->val(11, "M", $sheetNya);
		$codeBBDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][12][4]['raw'];
		$codeBBTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][12][6]['raw'];
		$ttlCodeBB = $codeBBDate+$codeBBTime;
		$dataOut['codeBBDate'] = $this->getUnixDateTime($ttlCodeBB,"tglNya");
		$dataOut['codeBBTime'] = $this->getUnixDateTime($ttlCodeBB,"jamNya");
		$codeCCDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][13][4]['raw'];
		$codeCCTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][13][6]['raw'];
		$ttlCodeCC = $codeCCDate+$codeCCTime;
		$dataOut['codeCCDate'] = $this->getUnixDateTime($ttlCodeCC,"tglNya");
		$dataOut['codeCCTime'] = $this->getUnixDateTime($ttlCodeCC,"jamNya");
		$codeDDDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][14][4]['raw'];
		$codeDDTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][14][6]['raw'];
		$ttlCodeDD = $codeDDDate+$codeDDTime;
		$dataOut['codeDDDate'] = $this->getUnixDateTime($ttlCodeDD,"tglNya");
		$dataOut['codeDDTime'] = $this->getUnixDateTime($ttlCodeDD,"jamNya");
		$dataOut['gradeA'] = $dataCExcel->val(16, "H", $sheetNya);
		$dataOut['gradeB'] = $dataCExcel->val(17, "H", $sheetNya);
		$dataOut['gradeC'] = $dataCExcel->val(18, "H", $sheetNya);
		$dataOut['gradeD'] = $dataCExcel->val(19, "H", $sheetNya);
		//OPERATION CARGO GRADE ===================================================
		for($lan = 1;$lan<=4;$lan++)
		{
			$gradeName = "";
			$rowGrade = "";
			if($lan == 1)
			{
				$gradeName = "A";
				$rowGrade = "23";
			}
			else if($lan == 2)
			{
				$gradeName = "B";
				$rowGrade = "34";
			}
			else if($lan == 3)
			{
				$gradeName = "C";
				$rowGrade = "45";
			}
			else if($lan == 4)
			{
				$gradeName = "D";
				$rowGrade = "56";
			}
			$aDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade][4]['raw'];
			$aTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade][6]['raw'];
			$ttlADT = $aDate+$aTime;
			$dataOut['grade'.$gradeName.'HoseConnDate'] = $this->getUnixDateTime($ttlADT,"tglNya");
			$dataOut['grade'.$gradeName.'HoseConnTime'] = $this->getUnixDateTime($ttlADT,"jamNya");

			$a1StartDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+1][4]['raw'];
			$a1StartTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+1][6]['raw'];
			$ttlA1startDT = $a1StartDate+$a1StartTime;
			$dataOut['grade'.$gradeName.'1StartDate'] = $this->getUnixDateTime($ttlA1startDT,"tglNya");
			$dataOut['grade'.$gradeName.'1StartTime'] = $this->getUnixDateTime($ttlA1startDT,"jamNya");
			$a1StopDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+2][4]['raw'];
			$a1StopTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+2][6]['raw'];
			$ttlA1StopDT = $a1StopDate+$a1StopTime;
			$dataOut['grade'.$gradeName.'1StopDate'] = $this->getUnixDateTime($ttlA1StopDT,"tglNya");
			$dataOut['grade'.$gradeName.'1StopTime'] = $this->getUnixDateTime($ttlA1StopDT,"jamNya");
			$dataOut['grade'.$gradeName.'1StopNote'] = $dataCExcel->val($rowGrade+2, "K", $sheetNya);

			$a2StartDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+3][4]['raw'];
			$a2StartTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+3][6]['raw'];
			$ttlA2startDT = $a2StartDate+$a2StartTime;
			$dataOut['grade'.$gradeName.'2StartDate'] = $this->getUnixDateTime($ttlA2startDT,"tglNya");
			$dataOut['grade'.$gradeName.'2StartTime'] = $this->getUnixDateTime($ttlA2startDT,"jamNya");
			$a2StopDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+4][4]['raw'];
			$a2StopTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+4][6]['raw'];
			$ttlA2StopDT = $a2StopDate+$a2StopTime;
			$dataOut['grade'.$gradeName.'2StopDate'] = $this->getUnixDateTime($ttlA2StopDT,"tglNya");
			$dataOut['grade'.$gradeName.'2StopTime'] = $this->getUnixDateTime($ttlA2StopDT,"jamNya");
			$dataOut['grade'.$gradeName.'2StopNote'] = $dataCExcel->val($rowGrade+4, "K", $sheetNya);

			$a3StartDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+5][4]['raw'];
			$a3StartTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+5][6]['raw'];
			$ttlA3startDT = $a3StartDate+$a3StartTime;
			$dataOut['grade'.$gradeName.'3StartDate'] = $this->getUnixDateTime($ttlA3startDT,"tglNya");
			$dataOut['grade'.$gradeName.'3StartTime'] = $this->getUnixDateTime($ttlA3startDT,"jamNya");
			$a3StopDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+6][4]['raw'];
			$a3StopTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+6][6]['raw'];
			$ttlA3StopDT = $a3StopDate+$a3StopTime;
			$dataOut['grade'.$gradeName.'3StopDate'] = $this->getUnixDateTime($ttlA3StopDT,"tglNya");
			$dataOut['grade'.$gradeName.'3StopTime'] = $this->getUnixDateTime($ttlA3StopDT,"jamNya");
			$dataOut['grade'.$gradeName.'3StopNote'] = $dataCExcel->val($rowGrade+6, "K", $sheetNya);

			$aDcDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+7][4]['raw'];
			$aDcTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+7][6]['raw'];
			$ttlADcDT = $aDcDate+$aDcTime;
			$dataOut['grade'.$gradeName.'HoseDisConnDate'] = $this->getUnixDateTime($ttlADcDT,"tglNya");
			$dataOut['grade'.$gradeName.'HoseDisConnTime'] = $this->getUnixDateTime($ttlADcDT,"jamNya");
		}
		//=============================================================================
		$unBerthedDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][66][4]['raw'];
		$unBerthedTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][66][6]['raw'];
		$ttlUnBerthedTime = $unBerthedDate+$unBerthedTime;
		$dataOut['unBerthedDate'] = $this->getUnixDateTime($ttlUnBerthedTime,"tglNya");
		$dataOut['unBerthedTime'] = $this->getUnixDateTime($ttlUnBerthedTime,"jamNya");

		$anchorDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][67][4]['raw'];
		$anchorTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][67][6]['raw'];
		$ttlAnchor = $anchorDate+$anchorTime;
		$dataOut['anchorDate'] = $this->getUnixDateTime($ttlAnchor,"tglNya");
		$dataOut['anchorTime'] = $this->getUnixDateTime($ttlAnchor,"jamNya");

		$actualLineDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][68][4]['raw'];
		$actualLineTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][68][6]['raw'];
		$ttlActualLine = $actualLineDate+$actualLineTime;
		$dataOut['actualLineDate'] = $this->getUnixDateTime($ttlActualLine,"tglNya");
		$dataOut['actualLineTime'] = $this->getUnixDateTime($ttlActualLine,"jamNya");

		$atdOuterDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][69][4]['raw'];
		$atdOuterTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][69][6]['raw'];
		$ttlAtdOuter = $atdOuterDate+$atdOuterTime;
		$dataOut['atdOuterDate'] = $this->getUnixDateTime($ttlAtdOuter,"tglNya");
		$dataOut['atdOuterTime'] = $this->getUnixDateTime($ttlAtdOuter,"jamNya");
		//AT LOADING PORT =========================================================
		for($hal = 1;$hal <= 12;$hal++)
		{
			$gradeName = "";
			$docNo = "";
			$dateNya = "";
			$klObs = "";
			$kl15C = "";
			$bbls = "";
			$mt = "";
			$lt = "";
			$rowNya = 0;
			if($hal == 1)
			{
				$gradeName = "bolAGradeName";
				$docNo = "bolADocNo";
				$dateNya = "bolADate";
				$klObs = "bolAKlObs";
				$kl15C = "bolAKL15C";
				$bbls = "bolABBLS";
				$mt = "bolAMT";
				$lt = "bolALT";
				$rowNya = 72;
			}
			else if($hal == 2)
			{
				$gradeName = "bolBGradeName";
				$docNo = "bolBDocNo";
				$dateNya = "bolBDate";
				$klObs = "bolBKlObs";
				$kl15C = "bolBKL15C";
				$bbls = "bolBBBLS";
				$mt = "bolBMT";
				$lt = "bolBLT";
				$rowNya = 73;
			}
			else if($hal == 3)
			{
				$gradeName = "bolCGradeName";
				$docNo = "bolCDocNo";
				$dateNya = "bolCDate";
				$klObs = "bolCKlObs";
				$kl15C = "bolCKL15C";
				$bbls = "bolCBBLS";
				$mt = "bolCMT";
				$lt = "bolCLT";
				$rowNya = 74;
			}
			else if($hal == 4)
			{
				$gradeName = "bolDGradeName";
				$docNo = "bolDDocNo";
				$dateNya = "bolDDate";
				$klObs = "bolDKlObs";
				$kl15C = "bolDKL15C";
				$bbls = "bolDBBLS";
				$mt = "bolDMT";
				$lt = "bolDLT";
				$rowNya = 75;
			}
			else if($hal == 5)
			{
				$gradeName = "sfalAGradeName";
				$docNo = "sfalADocNo";
				$dateNya = "sfalADate";
				$klObs = "sfalAKLObs";
				$kl15C = "sfalAKL15C";
				$bbls = "sfalABBLS";
				$mt = "sfalAMT";
				$lt = "sfalALT";
				$rowNya = 76;
			}
			else if($hal == 6)
			{
				$gradeName = "sfalBGradeName";
				$docNo = "sfalBDocNo";
				$dateNya = "sfalBDate";
				$klObs = "sfalBKLObs";
				$kl15C = "sfalBKL15C";
				$bbls = "sfalBBBLS";
				$mt = "sfalBMT";
				$lt = "sfalBLT";
				$rowNya = 77;
			}
			else if($hal == 7)
			{
				$gradeName = "sfalCGradeName";
				$docNo = "sfalCDocNo";
				$dateNya = "sfalCDate";
				$klObs = "sfalCKLObs";
				$kl15C = "sfalCKL15C";
				$bbls = "sfalCBBLS";
				$mt = "sfalCMT";
				$lt = "sfalCLT";
				$rowNya = 78;
			}
			else if($hal == 8)
			{
				$gradeName = "sfalDGradeName";
				$docNo = "sfalDDocNo";
				$dateNya = "sfalDDate";
				$klObs = "sfalDKLObs";
				$kl15C = "sfalDKL15C";
				$bbls = "sfalDBBLS";
				$mt = "sfalDMT";
				$lt = "sfalDLT";
				$rowNya = 79;
			}
			else if($hal == 9)
			{
				$gradeName = "sfblAGradeName";
				$docNo = "sfblADocNo";
				$dateNya = "sfblADate";
				$klObs = "sfblAKLObs";
				$kl15C = "sfblAKL15C";
				$bbls = "sfblABBLS";
				$mt = "sfblAMT";
				$lt = "sfblALT";
				$rowNya = 80;
			}
			else if($hal == 10)
			{
				$gradeName = "sfblBGradeName";
				$docNo = "sfblBDocNo";
				$dateNya = "sfblBDate";
				$klObs = "sfblBKLObs";
				$kl15C = "sfblBKL15C";
				$bbls = "sfblBBBLS";
				$mt = "sfblBMT";
				$lt = "sfblBLT";
				$rowNya = 81;
			}
			else if($hal == 11)
			{
				$gradeName = "sfblCGradeName";
				$docNo = "sfblCDocNo";
				$dateNya = "sfblCDate";
				$klObs = "sfblCKLObs";
				$kl15C = "sfblCKL15C";
				$bbls = "sfblCBBLS";
				$mt = "sfblCMT";
				$lt = "sfblCLT";
				$rowNya = 82;
			}
			else if($hal == 12)
			{
				$gradeName = "sfblDGradeName";
				$docNo = "sfblDDocNo";
				$dateNya = "sfblDDate";
				$klObs = "sfblDKLObs";
				$kl15C = "sfblDKL15C";
				$bbls = "sfblDBBLS";
				$mt = "sfblDMT";
				$lt = "sfblDLT";
				$rowNya = 83;
			}
			$dataOut[$gradeName] = $dataCExcel->val($rowNya, "H", $sheetNya);
			$dataOut[$docNo] = $dataCExcel->val($rowNya, "I", $sheetNya);
			$dtNya = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowNya][10]['raw'];
			$dataOut[$dateNya] = $this->getUnixDateTime($dtNya,"tglNya");
			$dataOut[$klObs] = str_replace($findCharNya,"",$dataCExcel->val($rowNya, "K", $sheetNya));
			$dataOut[$kl15C] = str_replace($findCharNya,"",$dataCExcel->val($rowNya, "L", $sheetNya));
			$dataOut[$bbls] = str_replace($findCharNya,"",$dataCExcel->val($rowNya, "M", $sheetNya));
			$dataOut[$mt] = str_replace($findCharNya,"",$dataCExcel->val($rowNya, "N", $sheetNya));
			$dataOut[$lt] = str_replace($findCharNya,"",$dataCExcel->val($rowNya, "O", $sheetNya));
		}
		//=========================================================================
		//AT DISCHARGING PORT =====================================================
		for($m = 1;$m <= 16;$m++)
		{
			$gradeName = "";
			$docNo = "";
			$dateNya = "";
			$klObs = "";
			$kl15C = "";
			$bbls = "";
			$mt = "";
			$lt = "";
			$rowNya = "";
			if($m == 1)
			{
				$gradeName = "newBolAGradeName";
				$docNo = "newBolADocNo";
				$dateNya = "newBolADate";
				$klObs = "newBolAKlObs";
				$kl15C = "newBolAKL15C";
				$bbls = "newBolABBLS";
				$mt = "newBolAMT";
				$lt = "newBolALT";
				$rowNya = 86;
			}
			else if($m == 2)
			{
				$gradeName = "newBolBGradeName";
				$docNo = "newBolBDocNo";
				$dateNya = "newBolBDate";
				$klObs = "newBolBKlObs";
				$kl15C = "newBolBKL15C";
				$bbls = "newBolBBBLS";
				$mt = "newBolBMT";
				$lt = "newBolBLT";
				$rowNya = 87;
			}
			else if($m == 3)
			{
				$gradeName = "newBolCGradeName";
				$docNo = "newBolCDocNo";
				$dateNya = "newBolCDate";
				$klObs = "newBolCKlObs";
				$kl15C = "newBolCKL15C";
				$bbls = "newBolCBBLS";
				$mt = "newBolCMT";
				$lt = "newBolCLT";
				$rowNya = 88;
			}
			else if($m == 4)
			{
				$gradeName = "newBolDGradeName";
				$docNo = "newBolDDocNo";
				$dateNya = "newBolDDate";
				$klObs = "newBolDKlObs";
				$kl15C = "newBolDKL15C";
				$bbls = "newBolDBBLS";
				$mt = "newBolDMT";
				$lt = "newBolDLT";
				$rowNya = 89;
			}
			else if($m == 5)
			{
				$gradeName = "sfbdAGradeName";
				$docNo = "sfbdADocNo";
				$dateNya = "sfbdADate";
				$klObs = "sfbdAKLObs";
				$kl15C = "sfbdAKL15C";
				$bbls = "sfbdABBLS";
				$mt = "sfbdAMT";
				$lt = "sfbdALT";
				$rowNya = 90;
			}
			else if($m == 6)
			{
				$gradeName = "sfbdBGradeName";
				$docNo = "sfbdBDocNo";
				$dateNya = "sfbdBDate";
				$klObs = "sfbdBKLObs";
				$kl15C = "sfbdBKL15C";
				$bbls = "sfbdBBBLS";
				$mt = "sfbdBMT";
				$lt = "sfbdBLT";
				$rowNya = 91;
			}
			else if($m == 7)
			{
				$gradeName = "sfbdCGradeName";
				$docNo = "sfbdCDocNo";
				$dateNya = "sfbdCDate";
				$klObs = "sfbdCKLObs";
				$kl15C = "sfbdCKL15C";
				$bbls = "sfbdCBBLS";
				$mt = "sfbdCMT";
				$lt = "sfbdCLT";
				$rowNya = 92;
			}
			else if($m == 8)
			{
				$gradeName = "sfbdDGradeName";
				$docNo = "sfbdDDocNo";
				$dateNya = "sfbdDDate";
				$klObs = "sfbdDKLObs";
				$kl15C = "sfbdDKL15C";
				$bbls = "sfbdDBBLS";
				$mt = "sfbdDMT";
				$lt = "sfbdDLT";
				$rowNya = 93;
			}
			else if($m == 9)
			{
				$gradeName = "sfadAGradeName";
				$docNo = "sfadADocNo";
				$dateNya = "sfadADate";
				$klObs = "sfadAKLObs";
				$kl15C = "sfadAKL15C";
				$bbls = "sfadABBLS";
				$mt = "sfadAMT";
				$lt = "sfadALT";
				$rowNya = 94;
			}
			else if($m == 10)
			{
				$gradeName = "sfadBGradeName";
				$docNo = "sfadBDocNo";
				$dateNya = "sfadBDate";
				$klObs = "sfadBKLObs";
				$kl15C = "sfadBKL15C";
				$bbls = "sfadBBBLS";
				$mt = "sfadBMT";
				$lt = "sfadBLT";
				$rowNya = 95;
			}
			else if($m == 11)
			{
				$gradeName = "sfadCGradeName";
				$docNo = "sfadCDocNo";
				$dateNya = "sfadCDate";
				$klObs = "sfadCKLObs";
				$kl15C = "sfadCKL15C";
				$bbls = "sfadCBBLS";
				$mt = "sfadCMT";
				$lt = "sfadCLT";
				$rowNya = 96;
			}
			else if($m == 12)
			{
				$gradeName = "sfadDGradeName";
				$docNo = "sfadDDocNo";
				$dateNya = "sfadDDate";
				$klObs = "sfadDKLObs";
				$kl15C = "sfadDKL15C";
				$bbls = "sfadDBBLS";
				$mt = "sfadDMT";
				$lt = "sfadDLT";
				$rowNya = 97;
			}
			else if($m == 13)
			{
				$gradeName = "sarAGradeName";
				$docNo = "sarADocNo";
				$dateNya = "sarADate";
				$klObs = "sarAKLObs";
				$kl15C = "sarAKL15C";
				$bbls = "sarABBLS";
				$mt = "sarAMT";
				$lt = "sarALT";
				$rowNya = 98;
			}
			else if($m == 14)
			{
				$gradeName = "sarBGradeName";
				$docNo = "sarBDocNo";
				$dateNya = "sarBDate";
				$klObs = "sarBKLObs";
				$kl15C = "sarBKL15C";
				$bbls = "sarBBBLS";
				$mt = "sarBMT";
				$lt = "sarBLT";
				$rowNya = 99;
			}
			else if($m == 15)
			{
				$gradeName = "sarCGradeName";
				$docNo = "sarCDocNo";
				$dateNya = "sarCDate";
				$klObs = "sarCKLObs";
				$kl15C = "sarCKL15C";
				$bbls = "sarCBBLS";
				$mt = "sarCMT";
				$lt = "sarCLT";
				$rowNya = 100;
			}
			else if($m == 16)
			{
				$gradeName = "sarDGradeName";
				$docNo = "sarDDocNo";
				$dateNya = "sarDDate";
				$klObs = "sarDKLObs";
				$kl15C = "sarDKL15C";
				$bbls = "sarDBBLS";
				$mt = "sarDMT";
				$lt = "sarDLT";
				$rowNya = 101;
			}
			$dataOut[$gradeName] = $dataCExcel->val($rowNya, "H", $sheetNya);
			$dataOut[$docNo] = $dataCExcel->val($rowNya, "I", $sheetNya);
			$dtNya = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowNya][10]['raw'];
			$dataOut[$dateNya] = $this->getUnixDateTime($dtNya,"tglNya");
			$dataOut[$klObs] = str_replace($findCharNya,"",$dataCExcel->val($rowNya, "K", $sheetNya));
			$dataOut[$kl15C] = str_replace($findCharNya,"",$dataCExcel->val($rowNya, "L", $sheetNya));
			$dataOut[$bbls] = str_replace($findCharNya,"",$dataCExcel->val($rowNya, "M", $sheetNya));
			$dataOut[$mt] = str_replace($findCharNya,"",$dataCExcel->val($rowNya, "N", $sheetNya));
			$dataOut[$lt] = str_replace($findCharNya,"",$dataCExcel->val($rowNya, "O", $sheetNya));
		}
		//=========================================================================
		$dataOut['bunkerRobAtaHSFO'] = $dataCExcel->val(105, "D", $sheetNya);
		$dataOut['bunkerRobAtaLSFO'] = $dataCExcel->val(105, "E", $sheetNya);
		$dataOut['bunkerRobAtaMDO'] = $dataCExcel->val(105, "F", $sheetNya);
		$dataOut['bunkerRobAtaHSD'] = $dataCExcel->val(105, "G", $sheetNya);
		$dataOut['bunkerRobAtaFWMT'] = $dataCExcel->val(105, "H", $sheetNya);
		$dataOut['bunkerRobAtaOWST'] = $dataCExcel->val(105, "I", $sheetNya);
		$dataOut['bunkerReplHSFO'] = $dataCExcel->val(106, "D", $sheetNya);
		$dataOut['bunkerReplLSFO'] = $dataCExcel->val(106, "E", $sheetNya);
		$dataOut['bunkerReplMDO'] = $dataCExcel->val(106, "F", $sheetNya);
		$dataOut['bunkerReplHSD'] = $dataCExcel->val(106, "G", $sheetNya);
		$dataOut['bunkerReplFWMT'] = $dataCExcel->val(106, "H", $sheetNya);
		$dataOut['bunkerReplOWST'] = $dataCExcel->val(106, "I", $sheetNya);
		$dataOut['bunkerRobAtdHSFO'] = $dataCExcel->val(107, "D", $sheetNya);
		$dataOut['bunkerRobAtdLSFO'] = $dataCExcel->val(107, "E", $sheetNya);
		$dataOut['bunkerRobAtdMDO'] = $dataCExcel->val(107, "F", $sheetNya);
		$dataOut['bunkerRobAtdHSD'] = $dataCExcel->val(107, "G", $sheetNya);
		$dataOut['bunkerRobAtdFWMT'] = $dataCExcel->val(107, "H", $sheetNya);
		$dataOut['bunkerRobAtdOWST'] = $dataCExcel->val(107, "I", $sheetNya);
		$dataOut['draftFWD'] = $dataCExcel->val(110, "D", $sheetNya);
		$dataOut['draftAFT'] = $dataCExcel->val(110, "E", $sheetNya);
		$dataOut['draftMean'] = $dataCExcel->val(110, "F", $sheetNya);

		$arrivalETADate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][112][4]['raw'];
		$arrivalETATime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][112][5]['raw'];
		$ttlArrival = $arrivalETADate+$arrivalETATime;
		$dataOut['arrivalETADate'] = $this->getUnixDateTime($ttlArrival,"tglNya");
		$dataOut['arrivalETATime'] = $this->getUnixDateTime($ttlArrival,"jamNya");
		$dataOut['arrivalETAPort'] = $dataCExcel->val(112, "F", $sheetNya);
		$arrivalDeviationDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][113][4]['raw'];
		$arrivalDeviationTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][113][5]['raw'];
		$ttlDeviation = $arrivalDeviationDate+$arrivalDeviationTime;
		$dataOut['arrivalDeviationDate'] = $this->getUnixDateTime($ttlDeviation,"tglNya");
		$dataOut['arrivalDeviationTime'] = $this->getUnixDateTime($ttlDeviation,"jamNya");
		$dataOut['arrivalDeviationPort'] = $dataCExcel->val(113, "F", $sheetNya);

		$dataOut['vesselDelayInstallation'] = $dataCExcel->val(115, "D", $sheetNya);
		$dataOut['vesselDelayVessel'] = $dataCExcel->val(116, "D", $sheetNya);
		$dataOut['vesselDelayAgent'] = $dataCExcel->val(117, "D", $sheetNya);
		$dataOut['vesselDelayOther'] = $dataCExcel->val(118, "D", $sheetNya);

		$dataOut['remarkDestPortLokasi1'] = $dataCExcel->val(120, "D", $sheetNya);
		$dataOut['remarkDestPortLokasi2'] = $dataCExcel->val(120, "G", $sheetNya);
		$dataOut['remarkDestPortLokasi3'] = $dataCExcel->val(120, "I", $sheetNya);
		$dataOut['remarkDevPortLokasi1'] = $dataCExcel->val(121, "D", $sheetNya);
		$dataOut['remarkDevPortLokasi2'] = $dataCExcel->val(121, "G", $sheetNya);
		$dataOut['remarkDevPortLokasi3'] = $dataCExcel->val(121, "I", $sheetNya);
		$dataOut['remarkInPort'] = $dataCExcel->val(122, "D", $sheetNya);
		$dataOut['remarkCurrentPosition'] = $dataCExcel->val(123, "D", $sheetNya);
		//LOADING AGREEMENT =======================================================
		for ($ast = 1;$ast <= 4;$ast++)
		{ 
			$gradeName = "";
			$colHour = "";
			$colKg = "";
			if($ast == 1)
			{
				$gradeName = "A";
				$colHour = "G";
				$colKg = "H";
			}
			else if($ast == 2)
			{
				$gradeName = "B";
				$colHour = "I";
				$colKg = "J";
			}
			else if($ast == 3)
			{
				$gradeName = "C";
				$colHour = "K";
				$colKg = "L";
			}
			else if($ast == 4)
			{
				$gradeName = "D";
				$colHour = "M";
				$colKg = "O";
			}
			$dataOut['vesselCapacity'.$gradeName.'Hour'] = $dataCExcel->val(128, $colHour, $sheetNya);
			$dataOut['vesselCapacity'.$gradeName.'Kg'] = $dataCExcel->val(128, $colKg, $sheetNya);
			$dataOut['shoreCapacity'.$gradeName.'Hour'] = $dataCExcel->val(129, $colHour, $sheetNya);
			$dataOut['shoreCapacity'.$gradeName.'Kg'] = $dataCExcel->val(129, $colKg, $sheetNya);
			$dataOut['loading'.$gradeName.'Hour'] = $dataCExcel->val(130, $colHour, $sheetNya);
			$dataOut['loading'.$gradeName.'Kg'] = $dataCExcel->val(130, $colKg, $sheetNya);
			$dataOut['average'.$gradeName.'Hour'] = $dataCExcel->val(128, $colHour, $sheetNya);
			$dataOut['average'.$gradeName.'Kg'] = $dataCExcel->val(128, $colKg, $sheetNya);
		}
		//=========================================================================
		if ($typeData == "") 
		{
			return $dataOut;
		}else{
			$stInsert = "";
			$cekData = $this->cekData($dataOut['voyageNo'],"tbluploadoil");
			if($cekData == 0)
			{
				try {
					$sql = "INSERT INTO ".$this->dbName.".tbluploadoil(vessel,master_name,satellite_phone,satellite_email,voyage_no,v_no,v_type,v_year,position_report,inport,atd_date,atd_hour,previous_port,actual_distance,ata_outer_date,ata_outer_hour,ata_inner_date,ata_inner_hour,berthed_date,berthed_hour,type_grade_a,type_grade_b,type_grade_c,type_grade_d,unberthed_date,unberthed_hour,anchor_inner_date,anchor_inner_hour,actual_line_date,actual_line_hour,atd_outer_date,atd_outer_hour,bunkerrobata_hsfo,bunkerrobata_lsfo,bunkerrobata_mdo,bunkerrobata_hsd,bunkerrobata_fw,bunkerrobata_owst,bunkerreflenishment_hsfo,bunkerreflenishment_lsfo,bunkerreflenishment_mdo,bunkerreflenishment_hsd,bunkerreflenishment_fw,bunkerreflenishment_owst,bunkerrobatd_hsfo,bunkerrobatd_lsfo,bunkerrobatd_mdo,bunkerrobatd_hsd,bunkerrobatd_fw,bunkerrobatd_owst,draft_fwd,draft_aft,draft_mean,arrivaleta_date,arrivaleta_hour,arrivaleta_location,arrivaldeviation_date,arrivaldeviation_hour,arrivaldeviation_location,vesseldue_install,vesseldue_vessel,vesseldue_agent,vesseldue_other,date_upload,add_usr)
					VALUES('".$vesselName."','".$dataOut['masterName']."','".$dataOut['satelitePhone']."','".$dataOut['sateliteEmail']."','".$dataOut['voyageNo']."','".$voyNo."','".$voyType."','".$voyYear."','".$dataOut['oilPosition']."','".$dataOut['inPort']."','".$dataOut['codeAADate']."','".$dataOut['codeAATime']."','".$dataOut['previousPort']."','".$dataOut['actualDistance']."','".$dataOut['codeBBDate']."','".$dataOut['codeBBTime']."','".$dataOut['codeCCDate']."','".$dataOut['codeCCTime']."','".$dataOut['codeDDDate']."','".$dataOut['codeDDTime']."','".$dataOut['gradeA']."','".$dataOut['gradeB']."','".$dataOut['gradeC']."','".$dataOut['gradeD']."','".$dataOut['unBerthedDate']."','".$dataOut['unBerthedTime']."','".$dataOut['anchorDate']."','".$dataOut['anchorTime']."','".$dataOut['actualLineDate']."','".$dataOut['actualLineTime']."','".$dataOut['atdOuterDate']."','".$dataOut['atdOuterTime']."','".$dataOut['bunkerRobAtaHSFO']."','".$dataOut['bunkerRobAtaLSFO']."','".$dataOut['bunkerRobAtaMDO']."','".$dataOut['bunkerRobAtaHSD']."','".$dataOut['bunkerRobAtaFWMT']."','".$dataOut['bunkerRobAtaOWST']."','".$dataOut['bunkerReplHSFO']."','".$dataOut['bunkerReplLSFO']."','".$dataOut['bunkerReplMDO']."','".$dataOut['bunkerReplHSD']."','".$dataOut['bunkerReplFWMT']."','".$dataOut['bunkerReplOWST']."','".$dataOut['bunkerRobAtdHSFO']."','".$dataOut['bunkerRobAtdLSFO']."','".$dataOut['bunkerRobAtdMDO']."','".$dataOut['bunkerRobAtdHSD']."','".$dataOut['bunkerRobAtdFWMT']."','".$dataOut['bunkerRobAtdOWST']."','".$dataOut['draftFWD']."','".$dataOut['draftAFT']."','".$dataOut['draftMean']."','".$dataOut['arrivalETADate']."','".$dataOut['arrivalETATime']."','".$dataOut['arrivalETAPort']."','".$dataOut['arrivalDeviationDate']."','".$dataOut['arrivalDeviationTime']."','".$dataOut['arrivalDeviationPort']."','".$dataOut['vesselDelayInstallation']."','".$dataOut['vesselDelayVessel']."','".$dataOut['vesselDelayAgent']."','".$dataOut['vesselDelayOther']."','".date("Y-m-d")."','".$dateNowUser."')";
					$this->koneksi->mysqlQuery($sql);
					$lastId = mysql_insert_id();
					// INSERT TABLE OPERATION====================================
					for ($ast=1; $ast <= 4; $ast++)
					{
						$typeGrade = "";
						$gradeName = "";
						if($ast == 1)
						{
							$typeGrade = "a";
							$gradeName = "A";
						}else if($ast == 2)
						{
							$typeGrade = "b";
							$gradeName = "B";
						}else if($ast == 3)
						{
							$typeGrade = "c";
							$gradeName = "C";
						}else if($ast == 4)
						{
							$typeGrade = "d";
							$gradeName = "D";
						}
						$sqlOpGrade = "INSERT INTO ".$this->dbName.".tbluploadoil_opt_grade".$typeGrade."(id_uploadoil,hoseconn_date,hoseconn_hour,start1_date,start1_hour,stop1_date,stop1_hour,stop1_note,start2_date,start2_hour,stop2_date,stop2_hour,stop2_note,start3_date,start3_hour,stop3_date,stop3_hour,stop3_note,hosedisconn_date,hosedisconn_hour) VALUES('".$lastId."','".$dataOut['grade'.$gradeName.'HoseConnDate']."','".$dataOut['grade'.$gradeName.'HoseConnTime']."','".$dataOut['grade'.$gradeName.'1StartDate']."','".$dataOut['grade'.$gradeName.'1StartTime']."','".$dataOut['grade'.$gradeName.'1StopDate']."','".$dataOut['grade'.$gradeName.'1StopTime']."','".$dataOut['grade'.$gradeName.'1StopNote']."','".$dataOut['grade'.$gradeName.'2StartDate']."','".$dataOut['grade'.$gradeName.'2StartTime']."','".$dataOut['grade'.$gradeName.'2StopDate']."','".$dataOut['grade'.$gradeName.'2StopTime']."','".$dataOut['grade'.$gradeName.'2StopNote']."','".$dataOut['grade'.$gradeName.'3StartDate']."','".$dataOut['grade'.$gradeName.'3StartTime']."','".$dataOut['grade'.$gradeName.'3StopDate']."','".$dataOut['grade'.$gradeName.'3StopTime']."','".$dataOut['grade'.$gradeName.'3StopNote']."','".$dataOut['grade'.$gradeName.'HoseDisConnDate']."','".$dataOut['grade'.$gradeName.'HoseDisConnTime']."')";
						$this->koneksi->mysqlQuery($sqlOpGrade);
					}
					// ==========================================================
					$sqlAtLoad = "INSERT INTO ".$this->dbName.".tbluploadoil_atloading(id_uploadoil,bol_a_gradeName,bol_a_docNo,bol_a_date,bol_a_klObs,bol_a_kl15c,bol_a_bbls,bol_a_mt,bol_a_lt,bol_b_gradeName,bol_b_docNo,bol_b_date,bol_b_klObs,bol_b_kl15c,bol_b_bbls,bol_b_mt,bol_b_lt,bol_c_gradeName,bol_c_docNo,bol_c_date,bol_c_klObs,bol_c_kl15c,bol_c_bbls,bol_c_mt,bol_c_lt,bol_d_gradeName,bol_d_docNo,bol_d_date,bol_d_klObs,bol_d_kl15c,bol_d_bbls,bol_d_mt,bol_d_lt,sfal_a_gradeName,sfal_a_docNo,sfal_a_date,sfal_a_klObs,sfal_a_kl15c,sfal_a_bbls,sfal_a_mt,sfal_a_lt,sfal_b_gradeName,sfal_b_docNo,sfal_b_date,sfal_b_klObs,sfal_b_kl15c,sfal_b_bbls,sfal_b_mt,sfal_b_lt,sfal_c_gradeName,sfal_c_docNo,sfal_c_date,sfal_c_klObs,sfal_c_kl15c,sfal_c_bbls,sfal_c_mt,sfal_c_lt,sfal_d_gradeName,sfal_d_docNo,sfal_d_date,sfal_d_klObs,sfal_d_kl15c,sfal_d_bbls,sfal_d_mt,sfal_d_lt,sfbl_a_gradeName,sfbl_a_docNo,sfbl_a_date,sfbl_a_klObs,sfbl_a_kl15c,sfbl_a_bbls,sfbl_a_lt,sfbl_a_mt,sfbl_b_gradeName,sfbl_b_docNo,sfbl_b_date,sfbl_b_klObs,sfbl_b_kl15c,sfbl_b_bbls,sfbl_b_mt,sfbl_b_lt,sfbl_c_gradeName,sfbl_c_docNo,sfbl_c_date,sfbl_c_klObs,sfbl_c_kl15c,sfbl_c_bbls,sfbl_c_mt,sfbl_c_lt,sfbl_d_gradeName,sfbl_d_docNo,sfbl_d_date,sfbl_d_klObs,sfbl_d_kl15c,sfbl_d_bbls,sfbl_d_mt,sfbl_d_lt)VALUES('".$lastId."','".$dataOut['bolAGradeName']."','".$dataOut['bolADocNo']."','".$dataOut['bolADate']."','".$dataOut['bolAKlObs']."','".$dataOut['bolAKL15C']."','".$dataOut['bolABBLS']."','".$dataOut['bolAMT']."','".$dataOut['bolALT']."','".$dataOut['bolBGradeName']."','".$dataOut['bolBDocNo']."','".$dataOut['bolBDate']."','".$dataOut['bolBKlObs']."','".$dataOut['bolBKL15C']."','".$dataOut['bolBBBLS']."','".$dataOut['bolBMT']."','".$dataOut['bolBLT']."','".$dataOut['bolCGradeName']."','".$dataOut['bolCDocNo']."','".$dataOut['bolCDate']."','".$dataOut['bolCKlObs']."','".$dataOut['bolCKL15C']."','".$dataOut['bolCBBLS']."','".$dataOut['bolCMT']."','".$dataOut['bolCLT']."','".$dataOut['bolDGradeName']."','".$dataOut['bolDDocNo']."','".$dataOut['bolDDate']."','".$dataOut['bolDKlObs']."','".$dataOut['bolDKL15C']."','".$dataOut['bolDBBLS']."','".$dataOut['bolDMT']."','".$dataOut['bolDLT']."','".$dataOut['sfalAGradeName']."','".$dataOut['sfalADocNo']."','".$dataOut['sfalADate']."','".$dataOut['sfalAKLObs']."','".$dataOut['sfalAKL15C']."','".$dataOut['sfalABBLS']."','".$dataOut['sfalAMT']."','".$dataOut['sfalALT']."','".$dataOut['sfalBGradeName']."','".$dataOut['sfalBDocNo']."','".$dataOut['sfalBDate']."','".$dataOut['sfalBKLObs']."','".$dataOut['sfalBKL15C']."','".$dataOut['sfalBBBLS']."','".$dataOut['sfalBMT']."','".$dataOut['sfalBLT']."','".$dataOut['sfalCGradeName']."','".$dataOut['sfalCDocNo']."','".$dataOut['sfalCDate']."','".$dataOut['sfalCKLObs']."','".$dataOut['sfalCKL15C']."','".$dataOut['sfalCBBLS']."','".$dataOut['sfalCMT']."','".$dataOut['sfalCLT']."','".$dataOut['sfalDGradeName']."','".$dataOut['sfalDDocNo']."','".$dataOut['sfalDDate']."','".$dataOut['sfalDKLObs']."','".$dataOut['sfalDKL15C']."','".$dataOut['sfalDBBLS']."','".$dataOut['sfalDMT']."','".$dataOut['sfalDLT']."','".$dataOut['sfblAGradeName']."','".$dataOut['sfblADocNo']."','".$dataOut['sfblADate']."','".$dataOut['sfblAKLObs']."','".$dataOut['sfblAKL15C']."','".$dataOut['sfblABBLS']."','".$dataOut['sfblAMT']."','".$dataOut['sfblALT']."','".$dataOut['sfblBGradeName']."','".$dataOut['sfblBDocNo']."','".$dataOut['sfblBDate']."','".$dataOut['sfblBKLObs']."','".$dataOut['sfblBKL15C']."','".$dataOut['sfblBBBLS']."','".$dataOut['sfblBMT']."','".$dataOut['sfblBLT']."','".$dataOut['sfblCGradeName']."','".$dataOut['sfblCDocNo']."','".$dataOut['sfblCDate']."','".$dataOut['sfblCKLObs']."','".$dataOut['sfblCKL15C']."','".$dataOut['sfblCBBLS']."','".$dataOut['sfblCMT']."','".$dataOut['sfblCLT']."','".$dataOut['sfblDGradeName']."','".$dataOut['sfblDDocNo']."','".$dataOut['sfblDDate']."','".$dataOut['sfblDKLObs']."','".$dataOut['sfblDKL15C']."','".$dataOut['sfblDBBLS']."','".$dataOut['sfblDMT']."','".$dataOut['sfblDLT']."' ) ";
					$this->koneksi->mysqlQuery($sqlAtLoad);

					$sqlAtDisCharg = "INSERT INTO ".$this->dbName.".tbluploadoil_atdischarging(id_uploadoil,newBol_a_gradeName,newBol_a_docNo,newBol_a_date,newBol_a_klObs,newBol_a_kl15c,newBol_a_bbls,newBol_a_mt,newBol_a_lt,newBol_b_gradeName,newBol_b_docNo,newBol_b_date,newBol_b_klObs,newBol_b_kl15c,newBol_b_bbls,newBol_b_mt,newBol_b_lt,newBol_c_gradeName,newBol_c_docNo,newBol_c_date,newBol_c_klObs,newBol_c_kl15c,newBol_c_bbls,newBol_c_mt,newBol_c_lt,newBol_d_gradeName,newBol_d_docNo,newBol_d_date,newBol_d_klObs,newBol_d_kl15c,newBol_d_bbls,newBol_d_mt,newBol_d_lt,sfbd_a_gradeName,sfbd_a_docNo,sfbd_a_date,sfbd_a_klObs,sfbd_a_kl15c,sfbd_a_bbls,sfbd_a_mt,sfbd_a_lt,sfbd_b_gradeName,sfbd_b_docNo,sfbd_b_date,sfbd_b_klObs,sfbd_b_kl15c,sfbd_b_bbls,sfbd_b_mt,sfbd_b_lt,sfbd_c_gradeName,sfbd_c_docNo,sfbd_c_date,sfbd_c_klObs,sfbd_c_kl15c,sfbd_c_bbls,sfbd_c_mt,sfbd_c_lt,sfbd_d_gradeName,sfbd_d_docNo,sfbd_d_date,sfbd_d_klObs,sfbd_d_kl15c,sfbd_d_bbls,sfbd_d_mt,sfbd_d_lt,sfad_a_gradeName,sfad_a_docNo,sfad_a_date,sfad_a_klObs,sfad_a_kl15c,sfad_a_bbls,sfad_a_mt,sfad_a_lt,sfad_b_gradeName,sfad_b_docNo,sfad_b_date,sfad_b_klObs,sfad_b_kl15c,sfad_b_bbls,sfad_b_mt,sfad_b_lt,sfad_c_gradeName,sfad_c_docNo,sfad_c_date,sfad_c_klObs,sfad_c_kl15c,sfad_c_bbls,sfad_c_mt,sfad_c_lt,sfad_d_gradeName,sfad_d_docNo,sfad_d_date,sfad_d_klObs,sfad_d_kl15c,sfad_d_bbls,sfad_d_mt,sfad_d_lt,sar_a_gradeName,sar_a_docNo,sar_a_date,sar_a_klObs,sar_a_kl15c,sar_a_bbls,sar_a_mt,sar_a_lt,sar_b_gradeName,sar_b_docNo,sar_b_date,sar_b_klObs,sar_b_kl15c,sar_b_bbls,sar_b_mt,sar_b_lt,sar_c_gradeName,sar_c_docNo,sar_c_date,sar_c_klObs,sar_c_kl15c,sar_c_bbls,sar_c_mt,sar_c_lt,sar_d_gradeName,sar_d_docNo,sar_d_date,sar_d_klObs,sar_d_kl15c,sar_d_bbls,sar_d_mt,sar_d_lt)VALUES('".$lastId."','".$dataOut['newBolAGradeName']."','".$dataOut['newBolADocNo']."','".$dataOut['newBolADate']."','".$dataOut['newBolAKlObs']."','".$dataOut['newBolAKL15C']."','".$dataOut['newBolABBLS']."','".$dataOut['newBolAMT']."','".$dataOut['newBolALT']."','".$dataOut['newBolBGradeName']."','".$dataOut['newBolBDocNo']."','".$dataOut['newBolBDate']."','".$dataOut['newBolBKlObs']."','".$dataOut['newBolBKL15C']."','".$dataOut['newBolBBBLS']."','".$dataOut['newBolBMT']."','".$dataOut['newBolBLT']."','".$dataOut['newBolCGradeName']."','".$dataOut['newBolCDocNo']."','".$dataOut['newBolCDate']."','".$dataOut['newBolCKlObs']."','".$dataOut['newBolCKL15C']."','".$dataOut['newBolCBBLS']."','".$dataOut['newBolCMT']."','".$dataOut['newBolCLT']."','".$dataOut['newBolDGradeName']."','".$dataOut['newBolDDocNo']."','".$dataOut['newBolDDate']."','".$dataOut['newBolDKlObs']."','".$dataOut['newBolDKL15C']."','".$dataOut['newBolDBBLS']."','".$dataOut['newBolDMT']."','".$dataOut['newBolDLT']."','".$dataOut['sfbdAGradeName']."','".$dataOut['sfbdADocNo']."','".$dataOut['sfbdADate']."','".$dataOut['sfbdAKLObs']."','".$dataOut['sfbdAKL15C']."','".$dataOut['sfbdABBLS']."','".$dataOut['sfbdAMT']."','".$dataOut['sfbdALT']."','".$dataOut['sfbdBGradeName']."','".$dataOut['sfbdBDocNo']."','".$dataOut['sfbdBDate']."','".$dataOut['sfbdBKLObs']."','".$dataOut['sfbdBKL15C']."','".$dataOut['sfbdBBBLS']."','".$dataOut['sfbdBMT']."','".$dataOut['sfbdBLT']."','".$dataOut['sfbdCGradeName']."','".$dataOut['sfbdCDocNo']."','".$dataOut['sfbdCDate']."','".$dataOut['sfbdCKLObs']."','".$dataOut['sfbdCKL15C']."','".$dataOut['sfbdCBBLS']."','".$dataOut['sfbdCMT']."','".$dataOut['sfbdCLT']."','".$dataOut['sfbdDGradeName']."','".$dataOut['sfbdDDocNo']."','".$dataOut['sfbdDDate']."','".$dataOut['sfbdDKLObs']."','".$dataOut['sfbdDKL15C']."','".$dataOut['sfbdDBBLS']."','".$dataOut['sfbdDMT']."','".$dataOut['sfbdDLT']."','".$dataOut['sfadAGradeName']."','".$dataOut['sfadADocNo']."','".$dataOut['sfadADate']."','".$dataOut['sfadAKLObs']."','".$dataOut['sfadAKL15C']."','".$dataOut['sfadABBLS']."','".$dataOut['sfadAMT']."','".$dataOut['sfadALT']."','".$dataOut['sfadBGradeName']."','".$dataOut['sfadBDocNo']."','".$dataOut['sfadBDate']."','".$dataOut['sfadBKLObs']."','".$dataOut['sfadBKL15C']."','".$dataOut['sfadBBBLS']."','".$dataOut['sfadBMT']."','".$dataOut['sfadBLT']."','".$dataOut['sfadCGradeName']."','".$dataOut['sfadCDocNo']."','".$dataOut['sfadCDate']."','".$dataOut['sfadCKLObs']."','".$dataOut['sfadCKL15C']."','".$dataOut['sfadCBBLS']."','".$dataOut['sfadCMT']."','".$dataOut['sfadCLT']."','".$dataOut['sfadDGradeName']."','".$dataOut['sfadDDocNo']."','".$dataOut['sfadDDate']."','".$dataOut['sfadDKLObs']."','".$dataOut['sfadDKL15C']."','".$dataOut['sfadDBBLS']."','".$dataOut['sfadDMT']."','".$dataOut['sfadDLT']."','".$dataOut['sarAGradeName']."','".$dataOut['sarADocNo']."','".$dataOut['sarADate']."','".$dataOut['sarAKLObs']."','".$dataOut['sarAKL15C']."','".$dataOut['sarABBLS']."','".$dataOut['sarAMT']."','".$dataOut['sarALT']."','".$dataOut['sarBGradeName']."','".$dataOut['sarBDocNo']."','".$dataOut['sarBDate']."','".$dataOut['sarBKLObs']."','".$dataOut['sarBKL15C']."','".$dataOut['sarBBBLS']."','".$dataOut['sarBMT']."','".$dataOut['sarBLT']."','".$dataOut['sarCGradeName']."','".$dataOut['sarCDocNo']."','".$dataOut['sarCDate']."','".$dataOut['sarCKLObs']."','".$dataOut['sarCKL15C']."','".$dataOut['sarCBBLS']."','".$dataOut['sarCMT']."','".$dataOut['sarCLT']."','".$dataOut['sarDGradeName']."','".$dataOut['sarDDocNo']."','".$dataOut['sarDDate']."','".$dataOut['sarDKLObs']."','".$dataOut['sarDKL15C']."','".$dataOut['sarDBBLS']."','".$dataOut['sarDMT']."','".$dataOut['sarDLT']."' )";
					$this->koneksi->mysqlQuery($sqlAtDisCharg);

					$sqlDisChargAgree = "INSERT INTO ".$this->dbName.".tbluploadoil_dischargagreement(id_uploadoil,vessel_a_klHour,vessel_a_kgCm,vessel_b_klHour,vessel_b_kgCm,vessel_c_klHour,vessel_c_kgCm,vessel_d_klHour,vessel_d_kgCm,shore_a_klHour,shore_a_kgCm,shore_b_klHour,shore_b_kgCm,shore_c_klHour,shore_c_kgCm,shore_d_klHour,shore_d_kgCm,loading_a_klHour,loading_a_kgCm,loading_b_klHour,loading_b_kgCm,loading_c_klHour,loading_c_kgCm,loading_d_klHour,loading_d_kgCm,average_a_klHour,average_a_kgCm,average_b_klHour,average_b_kgCm,average_c_klHour,average_c_kgCm,average_d_klHour,average_d_kgCm)VALUES('".$lastId."','".$dataOut['vesselCapacityAHour']."','".$dataOut['vesselCapacityAKg']."','".$dataOut['vesselCapacityBHour']."','".$dataOut['vesselCapacityBKg']."','".$dataOut['vesselCapacityCHour']."','".$dataOut['vesselCapacityCKg']."','".$dataOut['vesselCapacityDHour']."','".$dataOut['vesselCapacityDKg']."','".$dataOut['shoreCapacityAHour']."','".$dataOut['shoreCapacityAKg']."','".$dataOut['shoreCapacityBHour']."','".$dataOut['shoreCapacityBKg']."','".$dataOut['shoreCapacityCHour']."','".$dataOut['shoreCapacityCKg']."','".$dataOut['shoreCapacityDHour']."','".$dataOut['shoreCapacityDKg']."','".$dataOut['loadingAHour']."','".$dataOut['loadingAKg']."','".$dataOut['loadingBHour']."','".$dataOut['loadingBKg']."','".$dataOut['loadingCHour']."','".$dataOut['loadingCKg']."','".$dataOut['loadingDHour']."','".$dataOut['loadingDKg']."','".$dataOut['averageAHour']."','".$dataOut['averageAKg']."','".$dataOut['averageBHour']."','".$dataOut['averageBKg']."','".$dataOut['averageCHour']."','".$dataOut['averageCKg']."','".$dataOut['averageDHour']."','".$dataOut['averageDKg']."' ) ";
					$this->koneksi->mysqlQuery($sqlDisChargAgree);

					$sqlRemark = "INSERT INTO ".$this->dbName.".tbluploadoil_remark(id_uploadoil,dest_loc_1,dest_loc_2,dest_loc_3,dev_loc_1,dev_loc_2,dev_loc_3,distance_inport,distance_current)VALUES('".$lastId."','".$dataOut['remarkDestPortLokasi1']."','".$dataOut['remarkDestPortLokasi2']."','".$dataOut['remarkDestPortLokasi3']."','".$dataOut['remarkDevPortLokasi1']."','".$dataOut['remarkDevPortLokasi2']."','".$dataOut['remarkDevPortLokasi3']."','".$dataOut['remarkInPort']."','".$dataOut['remarkCurrentPosition']."' )  ";
					$this->koneksi->mysqlQuery($sqlRemark);
				$stInsert = "sukses";
				} catch (Exception $ex) {
					$stInsert = "gagal =>".$ex;
				}
			}else{
				$idNya = "";				
				try {
					$query = $this->koneksi->mysqlQuery("SELECT * FROM ".$this->dbName.".tbluploadoil WHERE voyage_no = '".$dataOut['voyageNo']."' AND deletests=0;"); 
					while($row = $this->koneksi->mysqlFetch($query))
					{
						$idNya = $row['id'];
					}
					$sqlUpd = "UPDATE ".$this->dbName.".tbluploadoil SET position_report = '".$dataOut['oilPosition']."',inport = '".$dataOut['inPort']."',atd_date = '".$dataOut['codeAADate']."',atd_hour = '".$dataOut['codeAATime']."',previous_port = '".$dataOut['previousPort']."',actual_distance = '".$dataOut['actualDistance']."',ata_outer_date = '".$dataOut['codeBBDate']."',ata_outer_hour = '".$dataOut['codeBBTime']."',ata_inner_date = '".$dataOut['codeCCDate']."',ata_inner_hour = '".$dataOut['codeCCTime']."',berthed_date = '".$dataOut['codeDDDate']."',berthed_hour = '".$dataOut['codeDDTime']."',type_grade_a = '".$dataOut['gradeA']."',type_grade_b = '".$dataOut['gradeB']."',type_grade_c = '".$dataOut['gradeC']."',type_grade_d = '".$dataOut['gradeD']."',unberthed_date = '".$dataOut['unBerthedDate']."',unberthed_hour = '".$dataOut['unBerthedTime']."',anchor_inner_date = '".$dataOut['anchorDate']."',anchor_inner_hour = '".$dataOut['anchorTime']."',actual_line_date = '".$dataOut['actualLineDate']."',actual_line_hour = '".$dataOut['actualLineTime']."',atd_outer_date = '".$dataOut['atdOuterDate']."',atd_outer_hour = '".$dataOut['atdOuterTime']."',bunkerrobata_hsfo = '".$dataOut['bunkerRobAtaHSFO']."',bunkerrobata_lsfo = '".$dataOut['bunkerRobAtaLSFO']."',bunkerrobata_mdo = '".$dataOut['bunkerRobAtaMDO']."',bunkerrobata_hsd = '".$dataOut['bunkerRobAtaHSD']."',bunkerrobata_fw = '".$dataOut['bunkerRobAtaFWMT']."',bunkerrobata_owst = '".$dataOut['bunkerRobAtaOWST']."',bunkerreflenishment_hsfo = '".$dataOut['bunkerReplHSFO']."',bunkerreflenishment_lsfo = '".$dataOut['bunkerReplLSFO']."',bunkerreflenishment_mdo = '".$dataOut['bunkerReplMDO']."',bunkerreflenishment_hsd = '".$dataOut['bunkerReplHSD']."',bunkerreflenishment_fw = '".$dataOut['bunkerReplFWMT']."',bunkerreflenishment_owst = '".$dataOut['bunkerReplOWST']."',bunkerrobatd_hsfo = '".$dataOut['bunkerRobAtdHSFO']."',bunkerrobatd_lsfo = '".$dataOut['bunkerRobAtdLSFO']."',bunkerrobatd_mdo = '".$dataOut['bunkerRobAtdMDO']."',bunkerrobatd_hsd = '".$dataOut['bunkerRobAtdHSD']."',bunkerrobatd_fw = '".$dataOut['bunkerRobAtdFWMT']."',bunkerrobatd_owst = '".$dataOut['bunkerRobAtdOWST']."',draft_fwd = '".$dataOut['draftFWD']."',draft_aft = '".$dataOut['draftAFT']."',draft_mean = '".$dataOut['draftMean']."',arrivaleta_date = '".$dataOut['arrivalETADate']."',arrivaleta_hour = '".$dataOut['arrivalETATime']."',arrivaleta_location = '".$dataOut['arrivalETAPort']."',arrivaldeviation_date = '".$dataOut['arrivalDeviationDate']."',arrivaldeviation_hour = '".$dataOut['arrivalDeviationTime']."',arrivaldeviation_location = '".$dataOut['arrivalDeviationPort']."',vesseldue_install = '".$dataOut['vesselDelayInstallation']."',vesseldue_vessel = '".$dataOut['vesselDelayVessel']."',vesseldue_agent = '".$dataOut['vesselDelayAgent']."',vesseldue_other = '".$dataOut['vesselDelayOther']."',date_update = '".$dataOut['vesselDelayAgent']."',upd_usr = '".$dateNowUser."' WHERE  voyage_no = '".$dataOut['voyageNo']."' ";
						$this->koneksi->mysqlQuery($sqlUpd);

					for ($mu=1; $mu <= 4; $mu++) 
					{
						$typeGrade = "";
						$gradeName = "";
						if($mu == 1)
						{
							$typeGrade = "a";
							$gradeName = "A";
						}else if($mu == 2)
						{
							$typeGrade = "b";
							$gradeName = "B";
						}else if($mu == 3)
						{
							$typeGrade = "c";
							$gradeName = "C";
						}else if($mu == 4)
						{
							$typeGrade = "d";
							$gradeName = "D";
						}
						$sqlUpdOpt = " UPDATE ".$this->dbName.".tbluploadoil_opt_grade".$typeGrade." SET hoseconn_date = '".$dataOut['grade'.$gradeName.'HoseConnDate']."',hoseconn_hour = '".$dataOut['grade'.$gradeName.'HoseConnTime']."',start1_date = '".$dataOut['grade'.$gradeName.'1StartDate']."',start1_hour = '".$dataOut['grade'.$gradeName.'1StartTime']."',stop1_date = '".$dataOut['grade'.$gradeName.'1StopDate']."',stop1_hour = '".$dataOut['grade'.$gradeName.'1StopTime']."',stop1_note = '".$dataOut['grade'.$gradeName.'1StopNote']."',start2_date = '".$dataOut['grade'.$gradeName.'2StartDate']."',start2_hour = '".$dataOut['grade'.$gradeName.'2StartTime']."',stop2_date = '".$dataOut['grade'.$gradeName.'2StopDate']."',stop2_hour = '".$dataOut['grade'.$gradeName.'2StopTime']."',stop2_note = '".$dataOut['grade'.$gradeName.'2StopNote']."',start3_date = '".$dataOut['grade'.$gradeName.'3StartDate']."',start3_hour = '".$dataOut['grade'.$gradeName.'3StartTime']."',stop3_date = '".$dataOut['grade'.$gradeName.'3StopDate']."',stop3_hour = '".$dataOut['grade'.$gradeName.'3StopTime']."',stop3_note = '".$dataOut['grade'.$gradeName.'3StopNote']."',hosedisconn_date = '".$dataOut['grade'.$gradeName.'HoseDisConnDate']."',hosedisconn_hour = '".$dataOut['grade'.$gradeName.'HoseDisConnTime']."'
						WHERE id_uploadoil = '".$idNya."'";
						$this->koneksi->mysqlQuery($sqlUpdOpt);
					}

					$sqlupdAtLoading = "UPDATE ".$this->dbName.".tbluploadoil_atloading SET bol_a_gradeName = '".$dataOut['bolAGradeName']."',bol_a_docNo ='".$dataOut['bolADocNo']."',bol_a_date ='".$dataOut['bolADate']."',bol_a_klObs ='".$dataOut['bolAKlObs']."',bol_a_kl15c ='".$dataOut['bolAKL15C']."',bol_a_bbls ='".$dataOut['bolABBLS']."',bol_a_lt ='".$dataOut['bolALT']."',bol_a_mt ='".$dataOut['bolAMT']."',bol_b_gradeName ='".$dataOut['bolBGradeName']."',bol_b_docNo ='".$dataOut['bolBDocNo']."',bol_b_date ='".$dataOut['bolBDate']."',bol_b_klObs ='".$dataOut['bolBKlObs']."',bol_b_kl15c ='".$dataOut['bolBKL15C']."',bol_b_bbls ='".$dataOut['bolBBBLS']."',bol_b_mt ='".$dataOut['bolBMT']."',bol_b_lt ='".$dataOut['bolBLT']."',bol_c_gradeName ='".$dataOut['bolCGradeName']."',bol_c_docNo ='".$dataOut['bolCDocNo']."',bol_c_date ='".$dataOut['bolCDate']."',bol_c_klObs ='".$dataOut['bolCKlObs']."',bol_c_kl15c ='".$dataOut['bolCKL15C']."',bol_c_bbls ='".$dataOut['bolCBBLS']."',bol_c_mt ='".$dataOut['bolCMT']."',bol_c_lt ='".$dataOut['bolCLT']."',bol_d_gradeName ='".$dataOut['bolDGradeName']."',bol_d_docNo ='".$dataOut['bolDDocNo']."',bol_d_date ='".$dataOut['bolDDate']."',bol_d_klObs ='".$dataOut['bolDKlObs']."',bol_d_kl15c ='".$dataOut['bolDKL15C']."',bol_d_bbls ='".$dataOut['bolDBBLS']."',bol_d_mt ='".$dataOut['bolDMT']."',bol_d_lt ='".$dataOut['bolDLT']."',sfal_a_gradeName ='".$dataOut['sfalAGradeName']."',sfal_a_docNo ='".$dataOut['sfalADocNo']."',sfal_a_date ='".$dataOut['sfalADate']."',sfal_a_klObs ='".$dataOut['sfalAKLObs']."',sfal_a_kl15c ='".$dataOut['sfalAKL15C']."',sfal_a_bbls ='".$dataOut['sfalABBLS']."',sfal_a_mt ='".$dataOut['sfalAMT']."',sfal_a_lt ='".$dataOut['sfalALT']."',sfal_b_gradeName ='".$dataOut['sfalBGradeName']."',sfal_b_docNo ='".$dataOut['sfalBDocNo']."',sfal_b_date ='".$dataOut['sfalBDate']."',sfal_b_klObs ='".$dataOut['sfalBKLObs']."',sfal_b_kl15c ='".$dataOut['sfalBKL15C']."',sfal_b_bbls ='".$dataOut['sfalBBBLS']."',sfal_b_mt ='".$dataOut['sfalBMT']."',sfal_b_lt ='".$dataOut['sfalBLT']."',sfal_c_gradeName ='".$dataOut['sfalCGradeName']."',sfal_c_docNo ='".$dataOut['sfalCDocNo']."',sfal_c_date ='".$dataOut['sfalCDate']."',sfal_c_klObs ='".$dataOut['sfalCKLObs']."',sfal_c_kl15c ='".$dataOut['sfalCKL15C']."',sfal_c_bbls ='".$dataOut['sfalCBBLS']."',sfal_c_mt ='".$dataOut['sfalCMT']."',sfal_c_lt ='".$dataOut['sfalCLT']."',sfal_d_gradeName ='".$dataOut['sfalDGradeName']."',sfal_d_docNo ='".$dataOut['sfalDDocNo']."',sfal_d_date ='".$dataOut['sfalDDate']."',sfal_d_klObs ='".$dataOut['sfalDKLObs']."',sfal_d_kl15c ='".$dataOut['sfalDKL15C']."',sfal_d_bbls ='".$dataOut['sfalDBBLS']."',sfal_d_mt ='".$dataOut['sfalDMT']."',sfal_d_lt ='".$dataOut['sfalDLT']."',sfbl_a_gradeName ='".$dataOut['sfblAGradeName']."',sfbl_a_docNo ='".$dataOut['sfblADocNo']."',sfbl_a_date ='".$dataOut['sfblADate']."',sfbl_a_klObs ='".$dataOut['sfblAKLObs']."',sfbl_a_kl15c ='".$dataOut['sfblAKL15C']."',sfbl_a_bbls ='".$dataOut['sfblABBLS']."',sfbl_a_mt ='".$dataOut['sfblAMT']."',sfbl_a_lt ='".$dataOut['sfblALT']."',sfbl_b_gradeName ='".$dataOut['sfblBGradeName']."',sfbl_b_docNo ='".$dataOut['sfblBDocNo']."',sfbl_b_date ='".$dataOut['sfblBDate']."',sfbl_b_klObs ='".$dataOut['sfblBKLObs']."',sfbl_b_kl15c ='".$dataOut['sfblBKL15C']."',sfbl_b_bbls ='".$dataOut['sfblBBBLS']."',sfbl_b_mt ='".$dataOut['sfblBMT']."',sfbl_b_lt ='".$dataOut['sfblBLT']."',sfbl_c_gradeName ='".$dataOut['sfblCGradeName']."',sfbl_c_docNo ='".$dataOut['sfblCDocNo']."',sfbl_c_date ='".$dataOut['sfblCDate']."',sfbl_c_klObs ='".$dataOut['sfblCKLObs']."',sfbl_c_kl15c ='".$dataOut['sfblCKL15C']."',sfbl_c_bbls ='".$dataOut['sfblCBBLS']."',sfbl_c_mt ='".$dataOut['sfblCMT']."',sfbl_c_lt ='".$dataOut['sfblCLT']."',sfbl_d_gradeName ='".$dataOut['sfblDGradeName']."',sfbl_d_docNo ='".$dataOut['sfblDDocNo']."',sfbl_d_date ='".$dataOut['sfblDDate']."',sfbl_d_klObs ='".$dataOut['sfblDKLObs']."',sfbl_d_kl15c ='".$dataOut['sfblDKL15C']."',sfbl_d_bbls ='".$dataOut['sfblDBBLS']."',sfbl_d_mt ='".$dataOut['sfblDMT']."',sfbl_d_lt ='".$dataOut['sfblDLT']."' WHERE id_uploadoil = '".$idNya."' ";
						$this->koneksi->mysqlQuery($sqlupdAtLoading);

					$sqlupdDisCharge = " UPDATE ".$this->dbName.".tbluploadoil_atdischarging SET newBol_a_gradeName ='".$dataOut['newBolAGradeName']."',newBol_a_docNo ='".$dataOut['newBolADocNo']."',newBol_a_date ='".$dataOut['newBolADate']."',newBol_a_klObs ='".$dataOut['newBolAKlObs']."',newBol_a_kl15c ='".$dataOut['newBolAKL15C']."',newBol_a_bbls ='".$dataOut['newBolABBLS']."',newBol_a_mt ='".$dataOut['newBolAMT']."',newBol_a_lt ='".$dataOut['newBolALT']."',newBol_b_gradeName ='".$dataOut['newBolBGradeName']."',newBol_b_docNo ='".$dataOut['newBolBDocNo']."',newBol_b_date ='".$dataOut['newBolBDate']."',newBol_b_klObs ='".$dataOut['newBolBKlObs']."',newBol_b_kl15c ='".$dataOut['newBolBKL15C']."',newBol_b_bbls ='".$dataOut['newBolBBBLS']."',newBol_b_mt ='".$dataOut['newBolBMT']."',newBol_b_lt ='".$dataOut['newBolBLT']."',newBol_c_gradeName ='".$dataOut['newBolCGradeName']."',newBol_c_docNo ='".$dataOut['newBolCDocNo']."',newBol_c_date ='".$dataOut['newBolCDate']."',newBol_c_klObs ='".$dataOut['newBolCKlObs']."',newBol_c_kl15c ='".$dataOut['newBolCKL15C']."',newBol_c_bbls ='".$dataOut['newBolCBBLS']."',newBol_c_lt ='".$dataOut['newBolCLT']."',newBol_c_mt ='".$dataOut['newBolCMT']."',newBol_d_gradeName ='".$dataOut['newBolDGradeName']."',newBol_d_docNo ='".$dataOut['newBolDDocNo']."',newBol_d_date ='".$dataOut['newBolDDate']."',newBol_d_klObs ='".$dataOut['newBolDKlObs']."',newBol_d_kl15c ='".$dataOut['newBolDKL15C']."',newBol_d_bbls ='".$dataOut['newBolDBBLS']."',newBol_d_mt ='".$dataOut['newBolDMT']."',newBol_d_lt ='".$dataOut['newBolDLT']."',sfbd_a_gradeName ='".$dataOut['sfbdAGradeName']."',sfbd_a_docNo ='".$dataOut['sfbdADocNo']."',sfbd_a_date ='".$dataOut['sfbdADate']."',sfbd_a_klObs ='".$dataOut['sfbdAKLObs']."',sfbd_a_kl15c ='".$dataOut['sfbdAKL15C']."',sfbd_a_bbls ='".$dataOut['sfbdABBLS']."',sfbd_a_mt ='".$dataOut['sfbdAMT']."',sfbd_a_lt ='".$dataOut['sfbdALT']."',sfbd_b_gradeName ='".$dataOut['sfbdBGradeName']."',sfbd_b_docNo ='".$dataOut['sfbdBDocNo']."',sfbd_b_date ='".$dataOut['sfbdBDate']."',sfbd_b_klObs ='".$dataOut['sfbdBKLObs']."',sfbd_b_kl15c ='".$dataOut['sfbdBKL15C']."',sfbd_b_bbls ='".$dataOut['sfbdBBBLS']."',sfbd_b_mt ='".$dataOut['sfbdBMT']."',sfbd_b_lt ='".$dataOut['sfbdBLT']."',sfbd_c_gradeName ='".$dataOut['sfbdCGradeName']."',sfbd_c_docNo ='".$dataOut['sfbdCDocNo']."',sfbd_c_date ='".$dataOut['sfbdCDate']."',sfbd_c_klObs ='".$dataOut['sfbdCKLObs']."',sfbd_c_kl15c ='".$dataOut['sfbdCKL15C']."',sfbd_c_bbls ='".$dataOut['sfbdCBBLS']."',sfbd_c_mt ='".$dataOut['sfbdCMT']."',sfbd_c_lt ='".$dataOut['sfbdCLT']."',sfbd_d_gradeName ='".$dataOut['sfbdDGradeName']."',sfbd_d_docNo ='".$dataOut['sfbdDDocNo']."',sfbd_d_date ='".$dataOut['sfbdDDate']."',sfbd_d_klObs ='".$dataOut['sfbdDKLObs']."',sfbd_d_kl15c ='".$dataOut['sfbdDKL15C']."',sfbd_d_bbls ='".$dataOut['sfbdDBBLS']."',sfbd_d_mt ='".$dataOut['sfbdDMT']."',sfbd_d_lt ='".$dataOut['sfbdDLT']."',sfad_a_gradeName ='".$dataOut['sfadAGradeName']."',sfad_a_docNo ='".$dataOut['sfadADocNo']."',sfad_a_date ='".$dataOut['sfadADate']."',sfad_a_klObs ='".$dataOut['sfadAKLObs']."',sfad_a_kl15c ='".$dataOut['sfadAKL15C']."',sfad_a_bbls ='".$dataOut['sfadABBLS']."',sfad_a_mt ='".$dataOut['sfadAMT']."',sfad_a_lt ='".$dataOut['sfadALT']."',sfad_b_gradeName ='".$dataOut['sfadBGradeName']."',sfad_b_docNo ='".$dataOut['sfadBDocNo']."',sfad_b_date ='".$dataOut['sfadBDate']."',sfad_b_klObs ='".$dataOut['sfadBKLObs']."',sfad_b_kl15c ='".$dataOut['sfadBKL15C']."',sfad_b_bbls ='".$dataOut['sfadBBBLS']."',sfad_b_mt ='".$dataOut['sfadBMT']."',sfad_b_lt ='".$dataOut['sfadBLT']."',sfad_c_gradeName ='".$dataOut['sfadCGradeName']."',sfad_c_docNo ='".$dataOut['sfadCDocNo']."',sfad_c_date ='".$dataOut['sfadCDate']."',sfad_c_klObs ='".$dataOut['sfadCKLObs']."',sfad_c_kl15c ='".$dataOut['sfadCKL15C']."',sfad_c_bbls ='".$dataOut['sfadCBBLS']."',sfad_c_mt ='".$dataOut['sfadCMT']."',sfad_c_lt ='".$dataOut['sfadCLT']."',sfad_d_gradeName ='".$dataOut['sfadDGradeName']."',sfad_d_docNo ='".$dataOut['sfadDDocNo']."',sfad_d_date ='".$dataOut['sfadDDate']."',sfad_d_klObs ='".$dataOut['sfadDKLObs']."',sfad_d_kl15c ='".$dataOut['sfadDKL15C']."',sfad_d_bbls ='".$dataOut['sfadDBBLS']."',sfad_d_mt ='".$dataOut['sfadDMT']."',sfad_d_lt ='".$dataOut['sfadDLT']."',sar_a_gradeName ='".$dataOut['sarAGradeName']."',sar_a_docNo ='".$dataOut['sfbdADocNo']."',sar_a_date ='".$dataOut['sarADate']."',sar_a_klObs ='".$dataOut['sarAKLObs']."',sar_a_kl15c ='".$dataOut['sarAKL15C']."',sar_a_bbls ='".$dataOut['sarABBLS']."',sar_a_mt ='".$dataOut['sarAMT']."',sar_a_lt ='".$dataOut['sarALT']."',sar_b_gradeName ='".$dataOut['sarBGradeName']."',sar_b_docNo ='".$dataOut['sarBDocNo']."',sar_b_date ='".$dataOut['sarBDate']."',sar_b_klObs ='".$dataOut['sarBKLObs']."',sar_b_kl15c ='".$dataOut['sarBKL15C']."',sar_b_bbls ='".$dataOut['sarBBBLS']."',sar_b_mt ='".$dataOut['sarBMT']."',sar_b_lt ='".$dataOut['sarBLT']."',sar_c_gradeName ='".$dataOut['sarCGradeName']."',sar_c_docNo ='".$dataOut['sarCDocNo']."',sar_c_date ='".$dataOut['sarCDate']."',sar_c_klObs ='".$dataOut['sarCKLObs']."',sar_c_kl15c ='".$dataOut['sarCKL15C']."',sar_c_bbls ='".$dataOut['sarCBBLS']."',sar_c_mt ='".$dataOut['sarCMT']."',sar_c_lt ='".$dataOut['sarCLT']."',sar_d_gradeName ='".$dataOut['sarDGradeName']."',sar_d_docNo ='".$dataOut['sarDDocNo']."',sar_d_date ='".$dataOut['sarDDate']."',sar_d_klObs ='".$dataOut['sarDKLObs']."',sar_d_kl15c ='".$dataOut['sarDKL15C']."',sar_d_bbls ='".$dataOut['sarDBBLS']."',sar_d_mt ='".$dataOut['sarDMT']."',sar_d_lt ='".$dataOut['sarDLT']."' WHERE id_uploadoil = '".$idNya."' ";
					$this->koneksi->mysqlQuery($sqlupdDisCharge);

					$sqlupdDisChargeAgree = " UPDATE ".$this->dbName.".tbluploadoil_dischargagreement SET vessel_a_klHour ='".$dataOut['vesselCapacityAHour']."',vessel_a_kgCm ='".$dataOut['vesselCapacityAKg']."',vessel_b_klHour ='".$dataOut['vesselCapacityBHour']."',vessel_b_kgCm ='".$dataOut['vesselCapacityBKg']."',vessel_c_klHour ='".$dataOut['vesselCapacityCHour']."',vessel_c_kgCm ='".$dataOut['vesselCapacityCKg']."',vessel_d_klHour ='".$dataOut['vesselCapacityDHour']."',vessel_d_kgCm ='".$dataOut['vesselCapacityDKg']."',shore_a_klHour ='".$dataOut['shoreCapacityAHour']."',shore_a_kgCm ='".$dataOut['shoreCapacityAKg']."',shore_b_klHour ='".$dataOut['shoreCapacityBHour']."',shore_b_kgCm ='".$dataOut['shoreCapacityBKg']."',shore_c_klHour ='".$dataOut['shoreCapacityCHour']."',shore_c_kgCm ='".$dataOut['shoreCapacityCKg']."',shore_d_klHour ='".$dataOut['shoreCapacityDHour']."',shore_d_kgCm ='".$dataOut['shoreCapacityDKg']."',loading_a_klHour ='".$dataOut['loadingAHour']."',loading_a_kgCm ='".$dataOut['loadingAKg']."',loading_b_klHour ='".$dataOut['loadingBHour']."',loading_b_kgCm ='".$dataOut['loadingBKg']."',loading_c_klHour ='".$dataOut['loadingCHour']."',loading_c_kgCm ='".$dataOut['loadingCKg']."',loading_d_klHour ='".$dataOut['loadingDHour']."',loading_d_kgCm ='".$dataOut['loadingDKg']."',average_a_klHour ='".$dataOut['averageAHour']."',average_a_kgCm ='".$dataOut['averageAKg']."',average_b_klHour ='".$dataOut['averageBHour']."',average_b_kgCm ='".$dataOut['averageBKg']."',average_c_klHour ='".$dataOut['averageCHour']."',average_c_kgCm ='".$dataOut['averageCKg']."',average_d_klHour ='".$dataOut['averageDHour']."',average_d_kgCm ='".$dataOut['averageDKg']."' WHERE id_uploadoil = '".$idNya."' ";
					$this->koneksi->mysqlQuery($sqlupdDisChargeAgree);

					$sqlupdRemark = " UPDATE ".$this->dbName.".tbluploadoil_remark SET dest_loc_1 ='".$dataOut['remarkDestPortLokasi1']."',dest_loc_2 ='".$dataOut['remarkDestPortLokasi2']."',dest_loc_3 ='".$dataOut['remarkDestPortLokasi3']."',dev_loc_1 ='".$dataOut['remarkDevPortLokasi1']."',dev_loc_2 ='".$dataOut['remarkDevPortLokasi2']."',dev_loc_3 ='".$dataOut['remarkDevPortLokasi3']."',distance_inport ='".$dataOut['remarkInPort']."',distance_current ='".$dataOut['remarkCurrentPosition']."' WHERE id_uploadoil = '".$idNya."' ";
					$this->koneksi->mysqlQuery($sqlupdRemark);

					$stInsert = "sukses";
				} catch (Exception $ed) {
					$stInsert = "gagal =>".$ed;
				}
			}
			return $stInsert;
		}
	}

	function uploadFileOilOld($typeData = "")
	{
		$usrInit = $_SESSION['userInitial'];
		$vesselName = $_SESSION["vesselName"];
		$dateNowUser = date("Ymd#H:i")."#".$usrInit;
		$dataOut = array();
		$fileDir = $_FILES["file"]["tmp_name"];
		$sheetNya = $_POST['sheetNya'] - 1;
		$findCharNya = array("*","?");
		$dataCExcel = new Spreadsheet_Excel_Reader($fileDir);

		$dV = explode("/", $dataCExcel->val(7, "D", $sheetNya));
		$voyNo = $dV[0];
		$voyType = $dV[1];
		$voyYear = $dV[2];

		$dataOut['masterName'] = $dataCExcel->val(3, "E", $sheetNya);
		$dataOut['satelitePhone'] = $dataCExcel->val(4, "E", $sheetNya);
		$dataOut['sateliteEmail'] = $dataCExcel->val(5, "E", $sheetNya);
		$dataOut['voyageNo'] = $dataCExcel->val(7, "D", $sheetNya);
		$dataOut['oilPosition'] = $dataCExcel->val(7, "F", $sheetNya)."-".$dataCExcel->val(7, "G", $sheetNya);
		$dataOut['inPort'] = $dataCExcel->val(7, "L", $sheetNya);
		$codeAADate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][10][4]['raw'];
		$codeAATime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][10][5]['raw'];
		$ttlCodeAA = $codeAADate+$codeAATime;
		$dataOut['codeAADate'] = $this->getUnixDateTime($ttlCodeAA,"tglNya");
		$dataOut['codeAATime'] = $this->getUnixDateTime($ttlCodeAA,"jamNya");
		$dataOut['previousPort'] = $dataCExcel->val(10, "L", $sheetNya);
		$dataOut['actualDistance'] = $dataCExcel->val(11, "L", $sheetNya);
		$codeBBDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][12][4]['raw'];
		$codeBBTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][12][5]['raw'];
		$ttlCodeBB = $codeBBDate+$codeBBTime;
		$dataOut['codeBBDate'] = $this->getUnixDateTime($ttlCodeBB,"tglNya");
		$dataOut['codeBBTime'] = $this->getUnixDateTime($ttlCodeBB,"jamNya");
		$codeCCDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][13][4]['raw'];
		$codeCCTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][13][5]['raw'];
		$ttlCodeCC = $codeCCDate+$codeCCTime;
		$dataOut['codeCCDate'] = $this->getUnixDateTime($ttlCodeCC,"tglNya");
		$dataOut['codeCCTime'] = $this->getUnixDateTime($ttlCodeCC,"jamNya");
		$codeDDDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][14][4]['raw'];
		$codeDDTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][14][5]['raw'];
		$ttlCodeDD = $codeDDDate+$codeDDTime;
		$dataOut['codeDDDate'] = $this->getUnixDateTime($ttlCodeDD,"tglNya");
		$dataOut['codeDDTime'] = $this->getUnixDateTime($ttlCodeDD,"jamNya");
		$dataOut['gradeA'] = $dataCExcel->val(16, "G", $sheetNya);
		$dataOut['gradeB'] = $dataCExcel->val(17, "G", $sheetNya);
		$dataOut['gradeC'] = $dataCExcel->val(18, "G", $sheetNya);
		$dataOut['gradeD'] = $dataCExcel->val(19, "G", $sheetNya);
		//OPERATION CARGO GRADE ===================================================
		for($lan = 1;$lan<=4;$lan++)
		{
			$gradeName = "";
			$rowGrade = "";
			if($lan == 1)
			{
				$gradeName = "A";
				$rowGrade = "23";
			}
			else if($lan == 2)
			{
				$gradeName = "B";
				$rowGrade = "34";
			}
			else if($lan == 3)
			{
				$gradeName = "C";
				$rowGrade = "45";
			}
			else if($lan == 4)
			{
				$gradeName = "D";
				$rowGrade = "56";
			}
			$aDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade][4]['raw'];
			$aTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade][5]['raw'];
			$ttlADT = $aDate+$aTime;
			$dataOut['grade'.$gradeName.'HoseConnDate'] = $this->getUnixDateTime($ttlADT,"tglNya");
			$dataOut['grade'.$gradeName.'HoseConnTime'] = $this->getUnixDateTime($ttlADT,"jamNya");

			$a1StartDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+1][4]['raw'];
			$a1StartTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+1][5]['raw'];
			$ttlA1startDT = $a1StartDate+$a1StartTime;
			$dataOut['grade'.$gradeName.'1StartDate'] = $this->getUnixDateTime($ttlA1startDT,"tglNya");
			$dataOut['grade'.$gradeName.'1StartTime'] = $this->getUnixDateTime($ttlA1startDT,"jamNya");
			$a1StopDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+2][4]['raw'];
			$a1StopTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+2][5]['raw'];
			$ttlA1StopDT = $a1StopDate+$a1StopTime;
			$dataOut['grade'.$gradeName.'1StopDate'] = $this->getUnixDateTime($ttlA1StopDT,"tglNya");
			$dataOut['grade'.$gradeName.'1StopTime'] = $this->getUnixDateTime($ttlA1StopDT,"jamNya");
			$dataOut['grade'.$gradeName.'1StopNote'] = $dataCExcel->val($rowGrade+2, "J", $sheetNya);

			$a2StartDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+3][4]['raw'];
			$a2StartTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+3][5]['raw'];
			$ttlA2startDT = $a2StartDate+$a2StartTime;
			$dataOut['grade'.$gradeName.'2StartDate'] = $this->getUnixDateTime($ttlA2startDT,"tglNya");
			$dataOut['grade'.$gradeName.'2StartTime'] = $this->getUnixDateTime($ttlA2startDT,"jamNya");
			$a2StopDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+4][4]['raw'];
			$a2StopTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+4][5]['raw'];
			$ttlA2StopDT = $a2StopDate+$a2StopTime;
			$dataOut['grade'.$gradeName.'2StopDate'] = $this->getUnixDateTime($ttlA2StopDT,"tglNya");
			$dataOut['grade'.$gradeName.'2StopTime'] = $this->getUnixDateTime($ttlA2StopDT,"jamNya");
			$dataOut['grade'.$gradeName.'2StopNote'] = $dataCExcel->val($rowGrade+4, "J", $sheetNya);

			$a3StartDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+5][4]['raw'];
			$a3StartTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+5][5]['raw'];
			$ttlA3startDT = $a3StartDate+$a3StartTime;
			$dataOut['grade'.$gradeName.'3StartDate'] = $this->getUnixDateTime($ttlA3startDT,"tglNya");
			$dataOut['grade'.$gradeName.'3StartTime'] = $this->getUnixDateTime($ttlA3startDT,"jamNya");
			$a3StopDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+6][4]['raw'];
			$a3StopTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+6][5]['raw'];
			$ttlA3StopDT = $a3StopDate+$a3StopTime;
			$dataOut['grade'.$gradeName.'3StopDate'] = $this->getUnixDateTime($ttlA3StopDT,"tglNya");
			$dataOut['grade'.$gradeName.'3StopTime'] = $this->getUnixDateTime($ttlA3StopDT,"jamNya");
			$dataOut['grade'.$gradeName.'3StopNote'] = $dataCExcel->val($rowGrade+6, "J", $sheetNya);

			$aDcDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+7][4]['raw'];
			$aDcTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowGrade+7][5]['raw'];
			$ttlADcDT = $aDcDate+$aDcTime;
			$dataOut['grade'.$gradeName.'HoseDisConnDate'] = $this->getUnixDateTime($ttlADcDT,"tglNya");
			$dataOut['grade'.$gradeName.'HoseDisConnTime'] = $this->getUnixDateTime($ttlADcDT,"jamNya");
		}
		//=============================================================================
		$unBerthedDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][66][4]['raw'];
		$unBerthedTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][66][5]['raw'];
		$ttlUnBerthedTime = $unBerthedDate+$unBerthedTime;
		$dataOut['unBerthedDate'] = $this->getUnixDateTime($ttlUnBerthedTime,"tglNya");
		$dataOut['unBerthedTime'] = $this->getUnixDateTime($ttlUnBerthedTime,"jamNya");

		$anchorDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][67][4]['raw'];
		$anchorTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][67][5]['raw'];
		$ttlAnchor = $anchorDate+$anchorTime;
		$dataOut['anchorDate'] = $this->getUnixDateTime($ttlAnchor,"tglNya");
		$dataOut['anchorTime'] = $this->getUnixDateTime($ttlAnchor,"jamNya");

		$actualLineDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][68][4]['raw'];
		$actualLineTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][68][5]['raw'];
		$ttlActualLine = $actualLineDate+$actualLineTime;
		$dataOut['actualLineDate'] = $this->getUnixDateTime($ttlActualLine,"tglNya");
		$dataOut['actualLineTime'] = $this->getUnixDateTime($ttlActualLine,"jamNya");

		$atdOuterDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][69][4]['raw'];
		$atdOuterTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][69][5]['raw'];
		$ttlAtdOuter = $atdOuterDate+$atdOuterTime;
		$dataOut['atdOuterDate'] = $this->getUnixDateTime($ttlAtdOuter,"tglNya");
		$dataOut['atdOuterTime'] = $this->getUnixDateTime($ttlAtdOuter,"jamNya");
		//AT LOADING PORT =========================================================
		for($hal = 1;$hal <= 12;$hal++)
		{
			$gradeName = "";
			$docNo = "";
			$dateNya = "";
			$klObs = "";
			$kl15C = "";
			$bbls = "";
			$lt = "";
			$rowNya = 0;
			if($hal == 1)
			{
				$gradeName = "bolAGradeName";
				$docNo = "bolADocNo";
				$dateNya = "bolADate";
				$klObs = "bolAKlObs";
				$kl15C = "bolAKL15C";
				$bbls = "bolABBLS";
				$lt = "bolALT";
				$rowNya = 72;
			}
			else if($hal == 2)
			{
				$gradeName = "bolBGradeName";
				$docNo = "bolBDocNo";
				$dateNya = "bolBDate";
				$klObs = "bolBKlObs";
				$kl15C = "bolBKL15C";
				$bbls = "bolBBBLS";
				$lt = "bolBLT";
				$rowNya = 73;
			}
			else if($hal == 3)
			{
				$gradeName = "bolCGradeName";
				$docNo = "bolCDocNo";
				$dateNya = "bolCDate";
				$klObs = "bolCKlObs";
				$kl15C = "bolCKL15C";
				$bbls = "bolCBBLS";
				$lt = "bolCLT";
				$rowNya = 74;
			}
			else if($hal == 4)
			{
				$gradeName = "bolDGradeName";
				$docNo = "bolDDocNo";
				$dateNya = "bolDDate";
				$klObs = "bolDKlObs";
				$kl15C = "bolDKL15C";
				$bbls = "bolDBBLS";
				$lt = "bolDLT";
				$rowNya = 75;
			}
			else if($hal == 5)
			{
				$gradeName = "sfalAGradeName";
				$docNo = "sfalADocNo";
				$dateNya = "sfalADate";
				$klObs = "sfalAKLObs";
				$kl15C = "sfalAKL15C";
				$bbls = "sfalABBLS";
				$lt = "sfalALT";
				$rowNya = 76;
			}
			else if($hal == 6)
			{
				$gradeName = "sfalBGradeName";
				$docNo = "sfalBDocNo";
				$dateNya = "sfalBDate";
				$klObs = "sfalBKLObs";
				$kl15C = "sfalBKL15C";
				$bbls = "sfalBBBLS";
				$lt = "sfalBLT";
				$rowNya = 77;
			}
			else if($hal == 7)
			{
				$gradeName = "sfalCGradeName";
				$docNo = "sfalCDocNo";
				$dateNya = "sfalCDate";
				$klObs = "sfalCKLObs";
				$kl15C = "sfalCKL15C";
				$bbls = "sfalCBBLS";
				$lt = "sfalCLT";
				$rowNya = 78;
			}
			else if($hal == 8)
			{
				$gradeName = "sfalDGradeName";
				$docNo = "sfalDDocNo";
				$dateNya = "sfalDDate";
				$klObs = "sfalDKLObs";
				$kl15C = "sfalDKL15C";
				$bbls = "sfalDBBLS";
				$lt = "sfalDLT";
				$rowNya = 79;
			}
			else if($hal == 9)
			{
				$gradeName = "sfblAGradeName";
				$docNo = "sfblADocNo";
				$dateNya = "sfblADate";
				$klObs = "sfblAKLObs";
				$kl15C = "sfblAKL15C";
				$bbls = "sfblABBLS";
				$lt = "sfblALT";
				$rowNya = 80;
			}
			else if($hal == 10)
			{
				$gradeName = "sfblBGradeName";
				$docNo = "sfblBDocNo";
				$dateNya = "sfblBDate";
				$klObs = "sfblBKLObs";
				$kl15C = "sfblBKL15C";
				$bbls = "sfblBBBLS";
				$lt = "sfblBLT";
				$rowNya = 81;
			}
			else if($hal == 11)
			{
				$gradeName = "sfblCGradeName";
				$docNo = "sfblCDocNo";
				$dateNya = "sfblCDate";
				$klObs = "sfblCKLObs";
				$kl15C = "sfblCKL15C";
				$bbls = "sfblCBBLS";
				$lt = "sfblCLT";
				$rowNya = 82;
			}
			else if($hal == 12)
			{
				$gradeName = "sfblDGradeName";
				$docNo = "sfblDDocNo";
				$dateNya = "sfblDDate";
				$klObs = "sfblDKLObs";
				$kl15C = "sfblDKL15C";
				$bbls = "sfblDBBLS";
				$lt = "sfblDLT";
				$rowNya = 83;
			}
			$dataOut[$gradeName] = $dataCExcel->val($rowNya, "G", $sheetNya);
			$dataOut[$docNo] = $dataCExcel->val($rowNya, "H", $sheetNya);
			$dtNya = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowNya][9]['raw'];
			$dataOut[$dateNya] = $this->getUnixDateTime($dtNya,"tglNya");
			$dataOut[$klObs] = str_replace($findCharNya,"",$dataCExcel->val($rowNya, "J", $sheetNya));
			$dataOut[$kl15C] = str_replace($findCharNya,"",$dataCExcel->val($rowNya, "K", $sheetNya));
			$dataOut[$bbls] = str_replace($findCharNya,"",$dataCExcel->val($rowNya, "L", $sheetNya));
			$dataOut[$lt] = str_replace($findCharNya,"",$dataCExcel->val($rowNya, "M", $sheetNya));
		}
		//=========================================================================
		//AT DISCHARGING PORT =====================================================
		for($m = 1;$m <= 16;$m++)
		{
			$gradeName = "";
			$docNo = "";
			$dateNya = "";
			$klObs = "";
			$kl15C = "";
			$bbls = "";
			$lt = "";
			$rowNya = "";
			if($m == 1)
			{
				$gradeName = "newBolAGradeName";
				$docNo = "newBolADocNo";
				$dateNya = "newBolADate";
				$klObs = "newBolAKlObs";
				$kl15C = "newBolAKL15C";
				$bbls = "newBolABBLS";
				$lt = "newBolALT";
				$rowNya = 86;
			}
			else if($m == 2)
			{
				$gradeName = "newBolBGradeName";
				$docNo = "newBolBDocNo";
				$dateNya = "newBolBDate";
				$klObs = "newBolBKlObs";
				$kl15C = "newBolBKL15C";
				$bbls = "newBolBBBLS";
				$lt = "newBolBLT";
				$rowNya = 87;
			}
			else if($m == 3)
			{
				$gradeName = "newBolCGradeName";
				$docNo = "newBolCDocNo";
				$dateNya = "newBolCDate";
				$klObs = "newBolCKlObs";
				$kl15C = "newBolCKL15C";
				$bbls = "newBolCBBLS";
				$lt = "newBolCLT";
				$rowNya = 88;
			}
			else if($m == 4)
			{
				$gradeName = "newBolDGradeName";
				$docNo = "newBolDDocNo";
				$dateNya = "newBolDDate";
				$klObs = "newBolDKlObs";
				$kl15C = "newBolDKL15C";
				$bbls = "newBolDBBLS";
				$lt = "newBolDLT";
				$rowNya = 89;
			}
			else if($m == 5)
			{
				$gradeName = "sfbdAGradeName";
				$docNo = "sfbdADocNo";
				$dateNya = "sfbdADate";
				$klObs = "sfbdAKLObs";
				$kl15C = "sfbdAKL15C";
				$bbls = "sfbdABBLS";
				$lt = "sfbdALT";
				$rowNya = 90;
			}
			else if($m == 6)
			{
				$gradeName = "sfbdBGradeName";
				$docNo = "sfbdBDocNo";
				$dateNya = "sfbdBDate";
				$klObs = "sfbdBKLObs";
				$kl15C = "sfbdBKL15C";
				$bbls = "sfbdBBBLS";
				$lt = "sfbdBLT";
				$rowNya = 91;
			}
			else if($m == 7)
			{
				$gradeName = "sfbdCGradeName";
				$docNo = "sfbdCDocNo";
				$dateNya = "sfbdCDate";
				$klObs = "sfbdCKLObs";
				$kl15C = "sfbdCKL15C";
				$bbls = "sfbdCBBLS";
				$lt = "sfbdCLT";
				$rowNya = 92;
			}
			else if($m == 8)
			{
				$gradeName = "sfbdDGradeName";
				$docNo = "sfbdDDocNo";
				$dateNya = "sfbdDDate";
				$klObs = "sfbdDKLObs";
				$kl15C = "sfbdDKL15C";
				$bbls = "sfbdDBBLS";
				$lt = "sfbdDLT";
				$rowNya = 93;
			}
			else if($m == 9)
			{
				$gradeName = "sfadAGradeName";
				$docNo = "sfadADocNo";
				$dateNya = "sfadADate";
				$klObs = "sfadAKLObs";
				$kl15C = "sfadAKL15C";
				$bbls = "sfadABBLS";
				$lt = "sfadALT";
				$rowNya = 94;
			}
			else if($m == 10)
			{
				$gradeName = "sfadBGradeName";
				$docNo = "sfadBDocNo";
				$dateNya = "sfadBDate";
				$klObs = "sfadBKLObs";
				$kl15C = "sfadBKL15C";
				$bbls = "sfadBBBLS";
				$lt = "sfadBLT";
				$rowNya = 95;
			}
			else if($m == 11)
			{
				$gradeName = "sfadCGradeName";
				$docNo = "sfadCDocNo";
				$dateNya = "sfadCDate";
				$klObs = "sfadCKLObs";
				$kl15C = "sfadCKL15C";
				$bbls = "sfadCBBLS";
				$lt = "sfadCLT";
				$rowNya = 96;
			}
			else if($m == 12)
			{
				$gradeName = "sfadDGradeName";
				$docNo = "sfadDDocNo";
				$dateNya = "sfadDDate";
				$klObs = "sfadDKLObs";
				$kl15C = "sfadDKL15C";
				$bbls = "sfadDBBLS";
				$lt = "sfadDLT";
				$rowNya = 97;
			}
			else if($m == 13)
			{
				$gradeName = "sarAGradeName";
				$docNo = "sarADocNo";
				$dateNya = "sarADate";
				$klObs = "sarAKLObs";
				$kl15C = "sarAKL15C";
				$bbls = "sarABBLS";
				$lt = "sarALT";
				$rowNya = 98;
			}
			else if($m == 14)
			{
				$gradeName = "sarBGradeName";
				$docNo = "sarBDocNo";
				$dateNya = "sarBDate";
				$klObs = "sarBKLObs";
				$kl15C = "sarBKL15C";
				$bbls = "sarBBBLS";
				$lt = "sarBLT";
				$rowNya = 99;
			}
			else if($m == 15)
			{
				$gradeName = "sarCGradeName";
				$docNo = "sarCDocNo";
				$dateNya = "sarCDate";
				$klObs = "sarCKLObs";
				$kl15C = "sarCKL15C";
				$bbls = "sarCBBLS";
				$lt = "sarCLT";
				$rowNya = 100;
			}
			else if($m == 16)
			{
				$gradeName = "sarDGradeName";
				$docNo = "sarDDocNo";
				$dateNya = "sarDDate";
				$klObs = "sarDKLObs";
				$kl15C = "sarDKL15C";
				$bbls = "sarDBBLS";
				$lt = "sarDLT";
				$rowNya = 101;
			}
			$dataOut[$gradeName] = $dataCExcel->val($rowNya, "G", $sheetNya);
			$dataOut[$docNo] = $dataCExcel->val($rowNya, "H", $sheetNya);
			$dtNya = $dataCExcel->sheets[$sheetNya]['cellsInfo'][$rowNya][9]['raw'];
			$dataOut[$dateNya] = $this->getUnixDateTime($dtNya,"tglNya");
			$dataOut[$klObs] = str_replace($findCharNya,"",$dataCExcel->val($rowNya, "J", $sheetNya));
			$dataOut[$kl15C] = str_replace($findCharNya,"",$dataCExcel->val($rowNya, "K", $sheetNya));
			$dataOut[$bbls] = str_replace($findCharNya,"",$dataCExcel->val($rowNya, "L", $sheetNya));
			$dataOut[$lt] = str_replace($findCharNya,"",$dataCExcel->val($rowNya, "M", $sheetNya));
		}
		//=========================================================================
		$dataOut['bunkerRobAtaMFO'] = $dataCExcel->val(105, "D", $sheetNya);
		$dataOut['bunkerRobAtaMDO'] = $dataCExcel->val(105, "E", $sheetNya);
		$dataOut['bunkerRobAtaHSD'] = $dataCExcel->val(105, "F", $sheetNya);
		$dataOut['bunkerRobAtaFW'] = $dataCExcel->val(105, "G", $sheetNya);
		$dataOut['bunkerReplMFO'] = $dataCExcel->val(106, "D", $sheetNya);
		$dataOut['bunkerReplMDO'] = $dataCExcel->val(106, "E", $sheetNya);
		$dataOut['bunkerReplHSD'] = $dataCExcel->val(106, "F", $sheetNya);
		$dataOut['bunkerReplFW'] = $dataCExcel->val(106, "G", $sheetNya);
		$dataOut['bunkerRobAtdMFO'] = $dataCExcel->val(107, "D", $sheetNya);
		$dataOut['bunkerRobAtdMDO'] = $dataCExcel->val(107, "E", $sheetNya);
		$dataOut['bunkerRobAtdHSD'] = $dataCExcel->val(107, "F", $sheetNya);
		$dataOut['bunkerRobAtdFW'] = $dataCExcel->val(107, "G", $sheetNya);
		$dataOut['draftFWD'] = $dataCExcel->val(110, "D", $sheetNya);
		$dataOut['draftAFT'] = $dataCExcel->val(110, "E", $sheetNya);
		$dataOut['draftMean'] = $dataCExcel->val(110, "F", $sheetNya);

		$arrivalETADate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][112][4]['raw'];
		$arrivalETATime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][112][5]['raw'];
		$ttlArrival = $arrivalETADate+$arrivalETATime;
		$dataOut['arrivalETADate'] = $this->getUnixDateTime($ttlArrival,"tglNya");
		$dataOut['arrivalETATime'] = $this->getUnixDateTime($ttlArrival,"jamNya");
		$dataOut['arrivalETAPort'] = $dataCExcel->val(112, "F", $sheetNya);
		$arrivalDeviationDate = $dataCExcel->sheets[$sheetNya]['cellsInfo'][113][4]['raw'];
		$arrivalDeviationTime = $dataCExcel->sheets[$sheetNya]['cellsInfo'][113][5]['raw'];
		$ttlDeviation = $arrivalDeviationDate+$arrivalDeviationTime;
		$dataOut['arrivalDeviationDate'] = $this->getUnixDateTime($ttlDeviation,"tglNya");
		$dataOut['arrivalDeviationTime'] = $this->getUnixDateTime($ttlDeviation,"jamNya");
		$dataOut['arrivalDeviationPort'] = $dataCExcel->val(113, "F", $sheetNya);

		$dataOut['vesselDelayInstallation'] = $dataCExcel->val(115, "D", $sheetNya);
		$dataOut['vesselDelayVessel'] = $dataCExcel->val(116, "D", $sheetNya);
		$dataOut['vesselDelayAgent'] = $dataCExcel->val(117, "D", $sheetNya);
		$dataOut['vesselDelayOther'] = $dataCExcel->val(118, "D", $sheetNya);

		$dataOut['remarkDestPortLokasi1'] = $dataCExcel->val(120, "D", $sheetNya);
		$dataOut['remarkDestPortLokasi2'] = $dataCExcel->val(120, "F", $sheetNya);
		$dataOut['remarkDestPortLokasi3'] = $dataCExcel->val(120, "H", $sheetNya);
		$dataOut['remarkDevPortLokasi1'] = $dataCExcel->val(121, "D", $sheetNya);
		$dataOut['remarkDevPortLokasi2'] = $dataCExcel->val(121, "F", $sheetNya);
		$dataOut['remarkDevPortLokasi3'] = $dataCExcel->val(121, "H", $sheetNya);
		$dataOut['remarkInPort'] = $dataCExcel->val(122, "D", $sheetNya);
		$dataOut['remarkCurrentPosition'] = $dataCExcel->val(123, "D", $sheetNya);
		//LOADING AGREEMENT =======================================================
		for ($ast = 1;$ast <= 4;$ast++)
		{ 
			$gradeName = "";
			$colHour = "";
			$colKg = "";
			if($ast == 1)
			{
				$gradeName = "A";
				$colHour = "F";
				$colKg = "G";
			}
			else if($ast == 2)
			{
				$gradeName = "B";
				$colHour = "H";
				$colKg = "I";
			}
			else if($ast == 3)
			{
				$gradeName = "C";
				$colHour = "J";
				$colKg = "K";
			}
			else if($ast == 4)
			{
				$gradeName = "D";
				$colHour = "L";
				$colKg = "M";
			}
			$dataOut['vesselCapacity'.$gradeName.'Hour'] = $dataCExcel->val(128, $colHour, $sheetNya);
			$dataOut['vesselCapacity'.$gradeName.'Kg'] = $dataCExcel->val(128, $colKg, $sheetNya);
			$dataOut['shoreCapacity'.$gradeName.'Hour'] = $dataCExcel->val(129, $colHour, $sheetNya);
			$dataOut['shoreCapacity'.$gradeName.'Kg'] = $dataCExcel->val(129, $colKg, $sheetNya);
			$dataOut['loading'.$gradeName.'Hour'] = $dataCExcel->val(130, $colHour, $sheetNya);
			$dataOut['loading'.$gradeName.'Kg'] = $dataCExcel->val(130, $colKg, $sheetNya);
			$dataOut['average'.$gradeName.'Hour'] = $dataCExcel->val(128, $colHour, $sheetNya);
			$dataOut['average'.$gradeName.'Kg'] = $dataCExcel->val(128, $colKg, $sheetNya);
		}
		//=========================================================================
		if ($typeData == "") 
		{
			return $dataOut;
		}else{
			$stInsert = "";
			$cekData = $this->cekData($dataOut['voyageNo'],"tbluploadoil");
			if($cekData == 0)
			{
				try {
					$sql = "INSERT INTO ".$this->dbName.".tbluploadoil(vessel,master_name,satellite_phone,satellite_email,voyage_no,v_no,v_type,v_year,position_report,inport,atd_date,atd_hour,previous_port,actual_distance,ata_outer_date,ata_outer_hour,ata_inner_date,ata_inner_hour,berthed_date,berthed_hour,type_grade_a,type_grade_b,type_grade_c,type_grade_d,unberthed_date,unberthed_hour,anchor_inner_date,anchor_inner_hour,actual_line_date,actual_line_hour,atd_outer_date,atd_outer_hour,bunkerrobata_mfo,bunkerrobata_mdo,bunkerrobata_hsd,bunkerrobata_fw,bunkerreflenishment_mfo,bunkerreflenishment_mdo,bunkerreflenishment_hsd,bunkerreflenishment_fw,bunkerrobatd_mfo,bunkerrobatd_mdo,bunkerrobatd_hsd,bunkerrobatd_fw,draft_fwd,draft_aft,draft_mean,arrivaleta_date,arrivaleta_hour,arrivaleta_location,arrivaldeviation_date,arrivaldeviation_hour,arrivaldeviation_location,vesseldue_install,vesseldue_vessel,vesseldue_agent,vesseldue_other,date_upload,add_usr)
					VALUES('".$vesselName."','".$dataOut['masterName']."','".$dataOut['satelitePhone']."','".$dataOut['sateliteEmail']."','".$dataOut['voyageNo']."','".$voyNo."','".$voyType."','".$voyYear."','".$dataOut['oilPosition']."','".$dataOut['inPort']."','".$dataOut['codeAADate']."','".$dataOut['codeAATime']."','".$dataOut['previousPort']."','".$dataOut['actualDistance']."','".$dataOut['codeBBDate']."','".$dataOut['codeBBTime']."','".$dataOut['codeCCDate']."','".$dataOut['codeCCTime']."','".$dataOut['codeDDDate']."','".$dataOut['codeDDTime']."','".$dataOut['gradeA']."','".$dataOut['gradeB']."','".$dataOut['gradeC']."','".$dataOut['gradeD']."','".$dataOut['unBerthedDate']."','".$dataOut['unBerthedTime']."','".$dataOut['anchorDate']."','".$dataOut['anchorTime']."','".$dataOut['actualLineDate']."','".$dataOut['actualLineTime']."','".$dataOut['atdOuterDate']."','".$dataOut['atdOuterTime']."','".$dataOut['bunkerRobAtaMFO']."','".$dataOut['bunkerRobAtaMDO']."','".$dataOut['bunkerRobAtaHSD']."','".$dataOut['bunkerRobAtaFW']."','".$dataOut['bunkerReplMFO']."','".$dataOut['bunkerReplMDO']."','".$dataOut['bunkerReplHSD']."','".$dataOut['bunkerReplFW']."','".$dataOut['bunkerRobAtdMFO']."','".$dataOut['bunkerRobAtdMDO']."','".$dataOut['bunkerRobAtdHSD']."','".$dataOut['bunkerRobAtdFW']."','".$dataOut['draftFWD']."','".$dataOut['draftAFT']."','".$dataOut['draftMean']."','".$dataOut['arrivalETADate']."','".$dataOut['arrivalETATime']."','".$dataOut['arrivalETAPort']."','".$dataOut['arrivalDeviationDate']."','".$dataOut['arrivalDeviationTime']."','".$dataOut['arrivalDeviationPort']."','".$dataOut['vesselDelayInstallation']."','".$dataOut['vesselDelayVessel']."','".$dataOut['vesselDelayAgent']."','".$dataOut['vesselDelayOther']."','".date("Y-m-d")."','".$dateNowUser."')";
					$this->koneksi->mysqlQuery($sql);
					$lastId = mysql_insert_id();
					// INSERT TABLE OPERATION====================================
					for ($ast=1; $ast <= 4; $ast++)
					{
						$typeGrade = "";
						$gradeName = "";
						if($ast == 1)
						{
							$typeGrade = "a";
							$gradeName = "A";
						}else if($ast == 2)
						{
							$typeGrade = "b";
							$gradeName = "B";
						}else if($ast == 3)
						{
							$typeGrade = "c";
							$gradeName = "C";
						}else if($ast == 4)
						{
							$typeGrade = "d";
							$gradeName = "D";
						}
						$sqlOpGrade = "INSERT INTO ".$this->dbName.".tbluploadoil_opt_grade".$typeGrade."(id_uploadoil,hoseconn_date,hoseconn_hour,start1_date,start1_hour,stop1_date,stop1_hour,stop1_note,start2_date,start2_hour,stop2_date,stop2_hour,stop2_note,start3_date,start3_hour,stop3_date,stop3_hour,stop3_note,hosedisconn_date,hosedisconn_hour) VALUES('".$lastId."','".$dataOut['grade'.$gradeName.'HoseConnDate']."','".$dataOut['grade'.$gradeName.'HoseConnTime']."','".$dataOut['grade'.$gradeName.'1StartDate']."','".$dataOut['grade'.$gradeName.'1StartTime']."','".$dataOut['grade'.$gradeName.'1StopDate']."','".$dataOut['grade'.$gradeName.'1StopTime']."','".$dataOut['grade'.$gradeName.'1StopNote']."','".$dataOut['grade'.$gradeName.'2StartDate']."','".$dataOut['grade'.$gradeName.'2StartTime']."','".$dataOut['grade'.$gradeName.'2StopDate']."','".$dataOut['grade'.$gradeName.'2StopTime']."','".$dataOut['grade'.$gradeName.'2StopNote']."','".$dataOut['grade'.$gradeName.'3StartDate']."','".$dataOut['grade'.$gradeName.'3StartTime']."','".$dataOut['grade'.$gradeName.'3StopDate']."','".$dataOut['grade'.$gradeName.'3StopTime']."','".$dataOut['grade'.$gradeName.'3StopNote']."','".$dataOut['grade'.$gradeName.'HoseDisConnDate']."','".$dataOut['grade'.$gradeName.'HoseDisConnTime']."')";
						$this->koneksi->mysqlQuery($sqlOpGrade);
					}
					// ==========================================================
					$sqlAtLoad = "INSERT INTO ".$this->dbName.".tbluploadoil_atloading(id_uploadoil,bol_a_gradeName,bol_a_docNo,bol_a_date,bol_a_klObs,bol_a_kl15c,bol_a_bbls,bol_a_lt,bol_b_gradeName,bol_b_docNo,bol_b_date,bol_b_klObs,bol_b_kl15c,bol_b_bbls,bol_b_lt,bol_c_gradeName,bol_c_docNo,bol_c_date,bol_c_klObs,bol_c_kl15c,bol_c_bbls,bol_c_lt,bol_d_gradeName,bol_d_docNo,bol_d_date,bol_d_klObs,bol_d_kl15c,bol_d_bbls,bol_d_lt,sfal_a_gradeName,sfal_a_docNo,sfal_a_date,sfal_a_klObs,sfal_a_kl15c,sfal_a_bbls,sfal_a_lt,sfal_b_gradeName,sfal_b_docNo,sfal_b_date,sfal_b_klObs,sfal_b_kl15c,sfal_b_bbls,sfal_b_lt,sfal_c_gradeName,sfal_c_docNo,sfal_c_date,sfal_c_klObs,sfal_c_kl15c,sfal_c_bbls,sfal_c_lt,sfal_d_gradeName,sfal_d_docNo,sfal_d_date,sfal_d_klObs,sfal_d_kl15c,sfal_d_bbls,sfal_d_lt,sfbl_a_gradeName,sfbl_a_docNo,sfbl_a_date,sfbl_a_klObs,sfbl_a_kl15c,sfbl_a_bbls,sfbl_a_lt,sfbl_b_gradeName,sfbl_b_docNo,sfbl_b_date,sfbl_b_klObs,sfbl_b_kl15c,sfbl_b_bbls,sfbl_b_lt,sfbl_c_gradeName,sfbl_c_docNo,sfbl_c_date,sfbl_c_klObs,sfbl_c_kl15c,sfbl_c_bbls,sfbl_c_lt,sfbl_d_gradeName,sfbl_d_docNo,sfbl_d_date,sfbl_d_klObs,sfbl_d_kl15c,sfbl_d_bbls,sfbl_d_lt)VALUES('".$lastId."','".$dataOut['bolAGradeName']."','".$dataOut['bolADocNo']."','".$dataOut['bolADate']."','".$dataOut['bolAKlObs']."','".$dataOut['bolAKL15C']."','".$dataOut['bolABBLS']."','".$dataOut['bolALT']."','".$dataOut['bolBGradeName']."','".$dataOut['bolBDocNo']."','".$dataOut['bolBDate']."','".$dataOut['bolBKlObs']."','".$dataOut['bolBKL15C']."','".$dataOut['bolBBBLS']."','".$dataOut['bolBLT']."','".$dataOut['bolCGradeName']."','".$dataOut['bolCDocNo']."','".$dataOut['bolCDate']."','".$dataOut['bolCKlObs']."','".$dataOut['bolCKL15C']."','".$dataOut['bolCBBLS']."','".$dataOut['bolCLT']."','".$dataOut['bolDGradeName']."','".$dataOut['bolDDocNo']."','".$dataOut['bolDDate']."','".$dataOut['bolDKlObs']."','".$dataOut['bolDKL15C']."','".$dataOut['bolDBBLS']."','".$dataOut['bolDLT']."','".$dataOut['sfalAGradeName']."','".$dataOut['sfalADocNo']."','".$dataOut['sfalADate']."','".$dataOut['sfalAKLObs']."','".$dataOut['sfalAKL15C']."','".$dataOut['sfalABBLS']."','".$dataOut['sfalALT']."','".$dataOut['sfalBGradeName']."','".$dataOut['sfalBDocNo']."','".$dataOut['sfalBDate']."','".$dataOut['sfalBKLObs']."','".$dataOut['sfalBKL15C']."','".$dataOut['sfalBBBLS']."','".$dataOut['sfalBLT']."','".$dataOut['sfalCGradeName']."','".$dataOut['sfalCDocNo']."','".$dataOut['sfalCDate']."','".$dataOut['sfalCKLObs']."','".$dataOut['sfalCKL15C']."','".$dataOut['sfalCBBLS']."','".$dataOut['sfalCLT']."','".$dataOut['sfalDGradeName']."','".$dataOut['sfalDDocNo']."','".$dataOut['sfalDDate']."','".$dataOut['sfalDKLObs']."','".$dataOut['sfalDKL15C']."','".$dataOut['sfalDBBLS']."','".$dataOut['sfalDLT']."','".$dataOut['sfblAGradeName']."','".$dataOut['sfblADocNo']."','".$dataOut['sfblADate']."','".$dataOut['sfblAKLObs']."','".$dataOut['sfblAKL15C']."','".$dataOut['sfblABBLS']."','".$dataOut['sfblALT']."','".$dataOut['sfblBGradeName']."','".$dataOut['sfblBDocNo']."','".$dataOut['sfblBDate']."','".$dataOut['sfblBKLObs']."','".$dataOut['sfblBKL15C']."','".$dataOut['sfblBBBLS']."','".$dataOut['sfblBLT']."','".$dataOut['sfblCGradeName']."','".$dataOut['sfblCDocNo']."','".$dataOut['sfblCDate']."','".$dataOut['sfblCKLObs']."','".$dataOut['sfblCKL15C']."','".$dataOut['sfblCBBLS']."','".$dataOut['sfblCLT']."','".$dataOut['sfblDGradeName']."','".$dataOut['sfblDDocNo']."','".$dataOut['sfblDDate']."','".$dataOut['sfblDKLObs']."','".$dataOut['sfblDKL15C']."','".$dataOut['sfblDBBLS']."','".$dataOut['sfblDLT']."' ) ";
					$this->koneksi->mysqlQuery($sqlAtLoad);

					$sqlAtDisCharg = "INSERT INTO ".$this->dbName.".tbluploadoil_atdischarging(id_uploadoil,newBol_a_gradeName,newBol_a_docNo,newBol_a_date,newBol_a_klObs,newBol_a_kl15c,newBol_a_bbls,newBol_a_lt,newBol_b_gradeName,newBol_b_docNo,newBol_b_date,newBol_b_klObs,newBol_b_kl15c,newBol_b_bbls,newBol_b_lt,newBol_c_gradeName,newBol_c_docNo,newBol_c_date,newBol_c_klObs,newBol_c_kl15c,newBol_c_bbls,newBol_c_lt,newBol_d_gradeName,newBol_d_docNo,newBol_d_date,newBol_d_klObs,newBol_d_kl15c,newBol_d_bbls,newBol_d_lt,sfbd_a_gradeName,sfbd_a_docNo,sfbd_a_date,sfbd_a_klObs,sfbd_a_kl15c,sfbd_a_bbls,sfbd_a_lt,sfbd_b_gradeName,sfbd_b_docNo,sfbd_b_date,sfbd_b_klObs,sfbd_b_kl15c,sfbd_b_bbls,sfbd_b_lt,sfbd_c_gradeName,sfbd_c_docNo,sfbd_c_date,sfbd_c_klObs,sfbd_c_kl15c,sfbd_c_bbls,sfbd_c_lt,sfbd_d_gradeName,sfbd_d_docNo,sfbd_d_date,sfbd_d_klObs,sfbd_d_kl15c,sfbd_d_bbls,sfbd_d_lt,sfad_a_gradeName,sfad_a_docNo,sfad_a_date,sfad_a_klObs,sfad_a_kl15c,sfad_a_bbls,sfad_a_lt,sfad_b_gradeName,sfad_b_docNo,sfad_b_date,sfad_b_klObs,sfad_b_kl15c,sfad_b_bbls,sfad_b_lt,sfad_c_gradeName,sfad_c_docNo,sfad_c_date,sfad_c_klObs,sfad_c_kl15c,sfad_c_bbls,sfad_c_lt,sfad_d_gradeName,sfad_d_docNo,sfad_d_date,sfad_d_klObs,sfad_d_kl15c,sfad_d_bbls,sfad_d_lt,sar_a_gradeName,sar_a_docNo,sar_a_date,sar_a_klObs,sar_a_kl15c,sar_a_bbls,sar_a_lt,sar_b_gradeName,sar_b_docNo,sar_b_date,sar_b_klObs,sar_b_kl15c,sar_b_bbls,sar_b_lt,sar_c_gradeName,sar_c_docNo,sar_c_date,sar_c_klObs,sar_c_kl15c,sar_c_bbls,sar_c_lt,sar_d_gradeName,sar_d_docNo,sar_d_date,sar_d_klObs,sar_d_kl15c,sar_d_bbls,sar_d_lt)VALUES('".$lastId."','".$dataOut['newBolAGradeName']."','".$dataOut['newBolADocNo']."','".$dataOut['newBolADate']."','".$dataOut['newBolAKlObs']."','".$dataOut['newBolAKL15C']."','".$dataOut['newBolABBLS']."','".$dataOut['newBolALT']."','".$dataOut['newBolBGradeName']."','".$dataOut['newBolBDocNo']."','".$dataOut['newBolBDate']."','".$dataOut['newBolBKlObs']."','".$dataOut['newBolBKL15C']."','".$dataOut['newBolBBBLS']."','".$dataOut['newBolBLT']."','".$dataOut['newBolCGradeName']."','".$dataOut['newBolCDocNo']."','".$dataOut['newBolCDate']."','".$dataOut['newBolCKlObs']."','".$dataOut['newBolCKL15C']."','".$dataOut['newBolCBBLS']."','".$dataOut['newBolCLT']."','".$dataOut['newBolDGradeName']."','".$dataOut['newBolDDocNo']."','".$dataOut['newBolDDate']."','".$dataOut['newBolDKlObs']."','".$dataOut['newBolDKL15C']."','".$dataOut['newBolDBBLS']."','".$dataOut['newBolDLT']."','".$dataOut['sfbdAGradeName']."','".$dataOut['sfbdADocNo']."','".$dataOut['sfbdADate']."','".$dataOut['sfbdAKLObs']."','".$dataOut['sfbdAKL15C']."','".$dataOut['sfbdABBLS']."','".$dataOut['sfbdALT']."','".$dataOut['sfbdBGradeName']."','".$dataOut['sfbdBDocNo']."','".$dataOut['sfbdBDate']."','".$dataOut['sfbdBKLObs']."','".$dataOut['sfbdBKL15C']."','".$dataOut['sfbdBBBLS']."','".$dataOut['sfbdBLT']."','".$dataOut['sfbdCGradeName']."','".$dataOut['sfbdCDocNo']."','".$dataOut['sfbdCDate']."','".$dataOut['sfbdCKLObs']."','".$dataOut['sfbdCKL15C']."','".$dataOut['sfbdCBBLS']."','".$dataOut['sfbdCLT']."','".$dataOut['sfbdDGradeName']."','".$dataOut['sfbdDDocNo']."','".$dataOut['sfbdDDate']."','".$dataOut['sfbdDKLObs']."','".$dataOut['sfbdDKL15C']."','".$dataOut['sfbdDBBLS']."','".$dataOut['sfbdDLT']."','".$dataOut['sfadAGradeName']."','".$dataOut['sfadADocNo']."','".$dataOut['sfadADate']."','".$dataOut['sfadAKLObs']."','".$dataOut['sfadAKL15C']."','".$dataOut['sfadABBLS']."','".$dataOut['sfadALT']."','".$dataOut['sfadBGradeName']."','".$dataOut['sfadBDocNo']."','".$dataOut['sfadBDate']."','".$dataOut['sfadBKLObs']."','".$dataOut['sfadBKL15C']."','".$dataOut['sfadBBBLS']."','".$dataOut['sfadBLT']."','".$dataOut['sfadCGradeName']."','".$dataOut['sfadCDocNo']."','".$dataOut['sfadCDate']."','".$dataOut['sfadCKLObs']."','".$dataOut['sfadCKL15C']."','".$dataOut['sfadCBBLS']."','".$dataOut['sfadCLT']."','".$dataOut['sfadDGradeName']."','".$dataOut['sfadDDocNo']."','".$dataOut['sfadDDate']."','".$dataOut['sfadDKLObs']."','".$dataOut['sfadDKL15C']."','".$dataOut['sfadDBBLS']."','".$dataOut['sfadDLT']."','".$dataOut['sarAGradeName']."','".$dataOut['sfbdADocNo']."','".$dataOut['sfbdADate']."','".$dataOut['sfbdAKLObs']."','".$dataOut['sarAKL15C']."','".$dataOut['sarABBLS']."','".$dataOut['sarALT']."','".$dataOut['sarBGradeName']."','".$dataOut['sarBDocNo']."','".$dataOut['sarBDate']."','".$dataOut['sarBKLObs']."','".$dataOut['sarBKL15C']."','".$dataOut['sarBBBLS']."','".$dataOut['sarBLT']."','".$dataOut['sarCGradeName']."','".$dataOut['sarCDocNo']."','".$dataOut['sarCDate']."','".$dataOut['sarCKLObs']."','".$dataOut['sarCKL15C']."','".$dataOut['sarCBBLS']."','".$dataOut['sarCLT']."','".$dataOut['sarDGradeName']."','".$dataOut['sarDDocNo']."','".$dataOut['sarDDate']."','".$dataOut['sarDKLObs']."','".$dataOut['sarDKL15C']."','".$dataOut['sarDBBLS']."','".$dataOut['sarDLT']."' )";
					$this->koneksi->mysqlQuery($sqlAtDisCharg);

					$sqlDisChargAgree = "INSERT INTO ".$this->dbName.".tbluploadoil_dischargagreement(id_uploadoil,vessel_a_klHour,vessel_a_kgCm,vessel_b_klHour,vessel_b_kgCm,vessel_c_klHour,vessel_c_kgCm,vessel_d_klHour,vessel_d_kgCm,shore_a_klHour,shore_a_kgCm,shore_b_klHour,shore_b_kgCm,shore_c_klHour,shore_c_kgCm,shore_d_klHour,shore_d_kgCm,loading_a_klHour,loading_a_kgCm,loading_b_klHour,loading_b_kgCm,loading_c_klHour,loading_c_kgCm,loading_d_klHour,loading_d_kgCm,average_a_klHour,average_a_kgCm,average_b_klHour,average_b_kgCm,average_c_klHour,average_c_kgCm,average_d_klHour,average_d_kgCm)VALUES('".$lastId."','".$dataOut['vesselCapacityAHour']."','".$dataOut['vesselCapacityAKg']."','".$dataOut['vesselCapacityBHour']."','".$dataOut['vesselCapacityBKg']."','".$dataOut['vesselCapacityCHour']."','".$dataOut['vesselCapacityCKg']."','".$dataOut['vesselCapacityDHour']."','".$dataOut['vesselCapacityDKg']."','".$dataOut['shoreCapacityAHour']."','".$dataOut['shoreCapacityAKg']."','".$dataOut['shoreCapacityBHour']."','".$dataOut['shoreCapacityBKg']."','".$dataOut['shoreCapacityCHour']."','".$dataOut['shoreCapacityCKg']."','".$dataOut['shoreCapacityDHour']."','".$dataOut['shoreCapacityDKg']."','".$dataOut['loadingAHour']."','".$dataOut['loadingAKg']."','".$dataOut['loadingBHour']."','".$dataOut['loadingBKg']."','".$dataOut['loadingCHour']."','".$dataOut['loadingCKg']."','".$dataOut['loadingDHour']."','".$dataOut['loadingDKg']."','".$dataOut['averageAHour']."','".$dataOut['averageAKg']."','".$dataOut['averageBHour']."','".$dataOut['averageBKg']."','".$dataOut['averageCHour']."','".$dataOut['averageCKg']."','".$dataOut['averageDHour']."','".$dataOut['averageDKg']."' ) ";
					$this->koneksi->mysqlQuery($sqlDisChargAgree);

					$sqlRemark = "INSERT INTO ".$this->dbName.".tbluploadoil_remark(id_uploadoil,dest_loc_1,dest_loc_2,dest_loc_3,dev_loc_1,dev_loc_2,dev_loc_3,distance_inport,distance_current)VALUES('".$lastId."','".$dataOut['remarkDestPortLokasi1']."','".$dataOut['remarkDestPortLokasi2']."','".$dataOut['remarkDestPortLokasi3']."','".$dataOut['remarkDevPortLokasi1']."','".$dataOut['remarkDevPortLokasi2']."','".$dataOut['remarkDevPortLokasi3']."','".$dataOut['remarkInPort']."','".$dataOut['remarkCurrentPosition']."' )  ";
					$this->koneksi->mysqlQuery($sqlRemark);
				$stInsert = "sukses";
				} catch (Exception $ex) {
					$stInsert = "gagal =>".$ex;
				}
			}else{
				$idNya = "";				
				try {
					$query = $this->koneksi->mysqlQuery("SELECT * FROM ".$this->dbName.".tbluploadoil WHERE voyage_no = '".$dataOut['voyageNo']."' AND deletests=0;"); 
					while($row = $this->koneksi->mysqlFetch($query))
					{
						$idNya = $row['id'];
					}
					$sqlUpd = "UPDATE ".$this->dbName.".tbluploadoil SET position_report = '".$dataOut['oilPosition']."',inport = '".$dataOut['inPort']."',atd_date = '".$dataOut['codeAADate']."',atd_hour = '".$dataOut['codeAATime']."',previous_port = '".$dataOut['previousPort']."',actual_distance = '".$dataOut['actualDistance']."',ata_outer_date = '".$dataOut['codeBBDate']."',ata_outer_hour = '".$dataOut['codeBBTime']."',ata_inner_date = '".$dataOut['codeCCDate']."',ata_inner_hour = '".$dataOut['codeCCTime']."',berthed_date = '".$dataOut['codeDDDate']."',berthed_hour = '".$dataOut['codeDDTime']."',type_grade_a = '".$dataOut['gradeA']."',type_grade_b = '".$dataOut['gradeB']."',type_grade_c = '".$dataOut['gradeC']."',type_grade_d = '".$dataOut['gradeD']."',unberthed_date = '".$dataOut['unBerthedDate']."',unberthed_hour = '".$dataOut['unBerthedTime']."',anchor_inner_date = '".$dataOut['anchorDate']."',anchor_inner_hour = '".$dataOut['anchorTime']."',actual_line_date = '".$dataOut['actualLineDate']."',actual_line_hour = '".$dataOut['actualLineTime']."',atd_outer_date = '".$dataOut['atdOuterDate']."',atd_outer_hour = '".$dataOut['atdOuterTime']."',bunkerrobata_mfo = '".$dataOut['bunkerRobAtaMFO']."',bunkerrobata_mdo = '".$dataOut['bunkerRobAtaMDO']."',bunkerrobata_hsd = '".$dataOut['bunkerRobAtaHSD']."',bunkerrobata_fw = '".$dataOut['bunkerRobAtaFW']."',bunkerreflenishment_mfo = '".$dataOut['bunkerReplMFO']."',bunkerreflenishment_mdo = '".$dataOut['bunkerReplMDO']."',bunkerreflenishment_hsd = '".$dataOut['bunkerReplHSD']."',bunkerreflenishment_fw = '".$dataOut['bunkerReplFW']."',bunkerrobatd_mfo = '".$dataOut['bunkerRobAtdMFO']."',bunkerrobatd_mdo = '".$dataOut['bunkerRobAtdMDO']."',bunkerrobatd_hsd = '".$dataOut['bunkerRobAtdHSD']."',bunkerrobatd_fw = '".$dataOut['bunkerRobAtdFW']."',draft_fwd = '".$dataOut['draftFWD']."',draft_aft = '".$dataOut['draftAFT']."',draft_mean = '".$dataOut['draftMean']."',arrivaleta_date = '".$dataOut['arrivalETADate']."',arrivaleta_hour = '".$dataOut['arrivalETATime']."',arrivaleta_location = '".$dataOut['arrivalETAPort']."',arrivaldeviation_date = '".$dataOut['arrivalDeviationDate']."',arrivaldeviation_hour = '".$dataOut['arrivalDeviationTime']."',arrivaldeviation_location = '".$dataOut['arrivalDeviationPort']."',vesseldue_install = '".$dataOut['vesselDelayInstallation']."',vesseldue_vessel = '".$dataOut['vesselDelayVessel']."',vesseldue_agent = '".$dataOut['vesselDelayAgent']."',vesseldue_other = '".$dataOut['vesselDelayOther']."',date_update = '".$dataOut['vesselDelayAgent']."',upd_usr = '".$dateNowUser."' WHERE  voyage_no = '".$dataOut['voyageNo']."' ";
						$this->koneksi->mysqlQuery($sqlUpd);

					for ($mu=1; $mu <= 4; $mu++) 
					{
						$typeGrade = "";
						$gradeName = "";
						if($mu == 1)
						{
							$typeGrade = "a";
							$gradeName = "A";
						}else if($mu == 2)
						{
							$typeGrade = "b";
							$gradeName = "B";
						}else if($mu == 3)
						{
							$typeGrade = "c";
							$gradeName = "C";
						}else if($mu == 4)
						{
							$typeGrade = "d";
							$gradeName = "D";
						}
						$sqlUpdOpt = " UPDATE ".$this->dbName.".tbluploadoil_opt_grade".$typeGrade." SET hoseconn_date = '".$dataOut['grade'.$gradeName.'HoseConnDate']."',hoseconn_hour = '".$dataOut['grade'.$gradeName.'HoseConnTime']."',start1_date = '".$dataOut['grade'.$gradeName.'1StartDate']."',start1_hour = '".$dataOut['grade'.$gradeName.'1StartTime']."',stop1_date = '".$dataOut['grade'.$gradeName.'1StopDate']."',stop1_hour = '".$dataOut['grade'.$gradeName.'1StopTime']."',stop1_note = '".$dataOut['grade'.$gradeName.'1StopNote']."',start2_date = '".$dataOut['grade'.$gradeName.'2StartDate']."',start2_hour = '".$dataOut['grade'.$gradeName.'2StartTime']."',stop2_date = '".$dataOut['grade'.$gradeName.'2StopDate']."',stop2_hour = '".$dataOut['grade'.$gradeName.'2StopTime']."',stop2_note = '".$dataOut['grade'.$gradeName.'2StopNote']."',start3_date = '".$dataOut['grade'.$gradeName.'3StartDate']."',start3_hour = '".$dataOut['grade'.$gradeName.'3StartTime']."',stop3_date = '".$dataOut['grade'.$gradeName.'3StopDate']."',stop3_hour = '".$dataOut['grade'.$gradeName.'3StopTime']."',stop3_note = '".$dataOut['grade'.$gradeName.'3StopNote']."',hosedisconn_date = '".$dataOut['grade'.$gradeName.'HoseDisConnDate']."',hosedisconn_hour = '".$dataOut['grade'.$gradeName.'HoseDisConnTime']."'
						WHERE id_uploadoil = '".$idNya."'";
						$this->koneksi->mysqlQuery($sqlUpdOpt);
					}

					$sqlupdAtLoading = "UPDATE ".$this->dbName.".tbluploadoil_atloading SET bol_a_gradeName = '".$dataOut['bolAGradeName']."',bol_a_docNo ='".$dataOut['bolADocNo']."',bol_a_date ='".$dataOut['bolADate']."',bol_a_klObs ='".$dataOut['bolAKlObs']."',bol_a_kl15c ='".$dataOut['bolAKL15C']."',bol_a_bbls ='".$dataOut['bolABBLS']."',bol_a_lt ='".$dataOut['bolALT']."',bol_b_gradeName ='".$dataOut['bolBGradeName']."',bol_b_docNo ='".$dataOut['bolBDocNo']."',bol_b_date ='".$dataOut['bolBDate']."',bol_b_klObs ='".$dataOut['bolBKlObs']."',bol_b_kl15c ='".$dataOut['bolBKL15C']."',bol_b_bbls ='".$dataOut['bolBBBLS']."',bol_b_lt ='".$dataOut['bolBLT']."',bol_c_gradeName ='".$dataOut['bolCGradeName']."',bol_c_docNo ='".$dataOut['bolCDocNo']."',bol_c_date ='".$dataOut['bolCDate']."',bol_c_klObs ='".$dataOut['bolCKlObs']."',bol_c_kl15c ='".$dataOut['bolCKL15C']."',bol_c_bbls ='".$dataOut['bolCBBLS']."',bol_c_lt ='".$dataOut['bolCLT']."',bol_d_gradeName ='".$dataOut['bolDGradeName']."',bol_d_docNo ='".$dataOut['bolDDocNo']."',bol_d_date ='".$dataOut['bolDDate']."',bol_d_klObs ='".$dataOut['bolDKlObs']."',bol_d_kl15c ='".$dataOut['bolDKL15C']."',bol_d_bbls ='".$dataOut['bolDBBLS']."',bol_d_lt ='".$dataOut['bolDLT']."',sfal_a_gradeName ='".$dataOut['sfalAGradeName']."',sfal_a_docNo ='".$dataOut['sfalADocNo']."',sfal_a_date ='".$dataOut['sfalADate']."',sfal_a_klObs ='".$dataOut['sfalAKLObs']."',sfal_a_kl15c ='".$dataOut['sfalAKL15C']."',sfal_a_bbls ='".$dataOut['sfalABBLS']."',sfal_a_lt ='".$dataOut['sfalALT']."',sfal_b_gradeName ='".$dataOut['sfalBGradeName']."',sfal_b_docNo ='".$dataOut['sfalBDocNo']."',sfal_b_date ='".$dataOut['sfalBDate']."',sfal_b_klObs ='".$dataOut['sfalBKLObs']."',sfal_b_kl15c ='".$dataOut['sfalBKL15C']."',sfal_b_bbls ='".$dataOut['sfalBBBLS']."',sfal_b_lt ='".$dataOut['sfalBLT']."',sfal_c_gradeName ='".$dataOut['sfalCGradeName']."',sfal_c_docNo ='".$dataOut['sfalCDocNo']."',sfal_c_date ='".$dataOut['sfalCDate']."',sfal_c_klObs ='".$dataOut['sfalCKLObs']."',sfal_c_kl15c ='".$dataOut['sfalCKL15C']."',sfal_c_bbls ='".$dataOut['sfalCBBLS']."',sfal_c_lt ='".$dataOut['sfalCLT']."',sfal_d_gradeName ='".$dataOut['sfalDGradeName']."',sfal_d_docNo ='".$dataOut['sfalDDocNo']."',sfal_d_date ='".$dataOut['sfalDDate']."',sfal_d_klObs ='".$dataOut['sfalDKLObs']."',sfal_d_kl15c ='".$dataOut['sfalDKL15C']."',sfal_d_bbls ='".$dataOut['sfalDBBLS']."',sfal_d_lt ='".$dataOut['sfalDLT']."',sfbl_a_gradeName ='".$dataOut['sfblAGradeName']."',sfbl_a_docNo ='".$dataOut['sfblADocNo']."',sfbl_a_date ='".$dataOut['sfblADate']."',sfbl_a_klObs ='".$dataOut['sfblAKLObs']."',sfbl_a_kl15c ='".$dataOut['sfblAKL15C']."',sfbl_a_bbls ='".$dataOut['sfblABBLS']."',sfbl_a_lt ='".$dataOut['sfblALT']."',sfbl_b_gradeName ='".$dataOut['sfblBGradeName']."',sfbl_b_docNo ='".$dataOut['sfblBDocNo']."',sfbl_b_date ='".$dataOut['sfblBDate']."',sfbl_b_klObs ='".$dataOut['sfblBKLObs']."',sfbl_b_kl15c ='".$dataOut['sfblBKL15C']."',sfbl_b_bbls ='".$dataOut['sfblBBBLS']."',sfbl_b_lt ='".$dataOut['sfblBLT']."',sfbl_c_gradeName ='".$dataOut['sfblCGradeName']."',sfbl_c_docNo ='".$dataOut['sfblCDocNo']."',sfbl_c_date ='".$dataOut['sfblCDate']."',sfbl_c_klObs ='".$dataOut['sfblCKLObs']."',sfbl_c_kl15c ='".$dataOut['sfblCKL15C']."',sfbl_c_bbls ='".$dataOut['sfblCBBLS']."',sfbl_c_lt ='".$dataOut['sfblCLT']."',sfbl_d_gradeName ='".$dataOut['sfblDGradeName']."',sfbl_d_docNo ='".$dataOut['sfblDDocNo']."',sfbl_d_date ='".$dataOut['sfblDDate']."',sfbl_d_klObs ='".$dataOut['sfblDKLObs']."',sfbl_d_kl15c ='".$dataOut['sfblDKL15C']."',sfbl_d_bbls ='".$dataOut['sfblDBBLS']."',sfbl_d_lt ='".$dataOut['sfblDLT']."' WHERE id_uploadoil = '".$idNya."' ";
						$this->koneksi->mysqlQuery($sqlupdAtLoading);

					$sqlupdDisCharge = " UPDATE ".$this->dbName.".tbluploadoil_atdischarging SET newBol_a_gradeName ='".$dataOut['newBolAGradeName']."',newBol_a_docNo ='".$dataOut['newBolADocNo']."',newBol_a_date ='".$dataOut['newBolADate']."',newBol_a_klObs ='".$dataOut['newBolAKlObs']."',newBol_a_kl15c ='".$dataOut['newBolAKL15C']."',newBol_a_bbls ='".$dataOut['newBolABBLS']."',newBol_a_lt ='".$dataOut['newBolALT']."',newBol_b_gradeName ='".$dataOut['newBolBGradeName']."',newBol_b_docNo ='".$dataOut['newBolBDocNo']."',newBol_b_date ='".$dataOut['newBolBDate']."',newBol_b_klObs ='".$dataOut['newBolBKlObs']."',newBol_b_kl15c ='".$dataOut['newBolBKL15C']."',newBol_b_bbls ='".$dataOut['newBolBBBLS']."',newBol_b_lt ='".$dataOut['newBolBLT']."',newBol_c_gradeName ='".$dataOut['newBolCGradeName']."',newBol_c_docNo ='".$dataOut['newBolCDocNo']."',newBol_c_date ='".$dataOut['newBolCDate']."',newBol_c_klObs ='".$dataOut['newBolCKlObs']."',newBol_c_kl15c ='".$dataOut['newBolCKL15C']."',newBol_c_bbls ='".$dataOut['newBolCBBLS']."',newBol_c_lt ='".$dataOut['newBolCLT']."',newBol_d_gradeName ='".$dataOut['newBolDGradeName']."',newBol_d_docNo ='".$dataOut['newBolDDocNo']."',newBol_d_date ='".$dataOut['newBolDDate']."',newBol_d_klObs ='".$dataOut['newBolDKlObs']."',newBol_d_kl15c ='".$dataOut['newBolDKL15C']."',newBol_d_bbls ='".$dataOut['newBolDBBLS']."',newBol_d_lt ='".$dataOut['newBolDLT']."',sfbd_a_gradeName ='".$dataOut['sfbdAGradeName']."',sfbd_a_docNo ='".$dataOut['sfbdADocNo']."',sfbd_a_date ='".$dataOut['sfbdADate']."',sfbd_a_klObs ='".$dataOut['sfbdAKLObs']."',sfbd_a_kl15c ='".$dataOut['sfbdAKL15C']."',sfbd_a_bbls ='".$dataOut['sfbdABBLS']."',sfbd_a_lt ='".$dataOut['sfbdALT']."',sfbd_b_gradeName ='".$dataOut['sfbdBGradeName']."',sfbd_b_docNo ='".$dataOut['sfbdBDocNo']."',sfbd_b_date ='".$dataOut['sfbdBDate']."',sfbd_b_klObs ='".$dataOut['sfbdBKLObs']."',sfbd_b_kl15c ='".$dataOut['sfbdBKL15C']."',sfbd_b_bbls ='".$dataOut['sfbdBBBLS']."',sfbd_b_lt ='".$dataOut['sfbdBLT']."',sfbd_c_gradeName ='".$dataOut['sfbdCGradeName']."',sfbd_c_docNo ='".$dataOut['sfbdCDocNo']."',sfbd_c_date ='".$dataOut['sfbdCDate']."',sfbd_c_klObs ='".$dataOut['sfbdCKLObs']."',sfbd_c_kl15c ='".$dataOut['sfbdCKL15C']."',sfbd_c_bbls ='".$dataOut['sfbdCBBLS']."',sfbd_c_lt ='".$dataOut['sfbdCLT']."',sfbd_d_gradeName ='".$dataOut['sfbdDGradeName']."',sfbd_d_docNo ='".$dataOut['sfbdDDocNo']."',sfbd_d_date ='".$dataOut['sfbdDDate']."',sfbd_d_klObs ='".$dataOut['sfbdDKLObs']."',sfbd_d_kl15c ='".$dataOut['sfbdDKL15C']."',sfbd_d_bbls ='".$dataOut['sfbdDBBLS']."',sfbd_d_lt ='".$dataOut['sfbdDLT']."',sfad_a_gradeName ='".$dataOut['sfadAGradeName']."',sfad_a_docNo ='".$dataOut['sfadADocNo']."',sfad_a_date ='".$dataOut['sfadADate']."',sfad_a_klObs ='".$dataOut['sfadAKLObs']."',sfad_a_kl15c ='".$dataOut['sfadAKL15C']."',sfad_a_bbls ='".$dataOut['sfadABBLS']."',sfad_a_lt ='".$dataOut['sfadALT']."',sfad_b_gradeName ='".$dataOut['sfadBGradeName']."',sfad_b_docNo ='".$dataOut['sfadBDocNo']."',sfad_b_date ='".$dataOut['sfadBDate']."',sfad_b_klObs ='".$dataOut['sfadBKLObs']."',sfad_b_kl15c ='".$dataOut['sfadBKL15C']."',sfad_b_bbls ='".$dataOut['sfadBBBLS']."',sfad_b_lt ='".$dataOut['sfadBLT']."',sfad_c_gradeName ='".$dataOut['sfadCGradeName']."',sfad_c_docNo ='".$dataOut['sfadCDocNo']."',sfad_c_date ='".$dataOut['sfadCDate']."',sfad_c_klObs ='".$dataOut['sfadCKLObs']."',sfad_c_kl15c ='".$dataOut['sfadCKL15C']."',sfad_c_bbls ='".$dataOut['sfadCBBLS']."',sfad_c_lt ='".$dataOut['sfadCLT']."',sfad_d_gradeName ='".$dataOut['sfadDGradeName']."',sfad_d_docNo ='".$dataOut['sfadDDocNo']."',sfad_d_date ='".$dataOut['sfadDDate']."',sfad_d_klObs ='".$dataOut['sfadDKLObs']."',sfad_d_kl15c ='".$dataOut['sfadDKL15C']."',sfad_d_bbls ='".$dataOut['sfadDBBLS']."',sfad_d_lt ='".$dataOut['sfadDLT']."',sar_a_gradeName ='".$dataOut['sarAGradeName']."',sar_a_docNo ='".$dataOut['sfbdADocNo']."',sar_a_date ='".$dataOut['sfbdADate']."',sar_a_klObs ='".$dataOut['sfbdAKLObs']."',sar_a_kl15c ='".$dataOut['sarAKL15C']."',sar_a_bbls ='".$dataOut['sarABBLS']."',sar_a_lt ='".$dataOut['sarALT']."',sar_b_gradeName ='".$dataOut['sarBGradeName']."',sar_b_docNo ='".$dataOut['sarBDocNo']."',sar_b_date ='".$dataOut['sarBDate']."',sar_b_klObs ='".$dataOut['sarBKLObs']."',sar_b_kl15c ='".$dataOut['sarBKL15C']."',sar_b_bbls ='".$dataOut['sarBBBLS']."',sar_b_lt ='".$dataOut['sarBLT']."',sar_c_gradeName ='".$dataOut['sarCGradeName']."',sar_c_docNo ='".$dataOut['sarCDocNo']."',sar_c_date ='".$dataOut['sarCDate']."',sar_c_klObs ='".$dataOut['sarCKLObs']."',sar_c_kl15c ='".$dataOut['sarCKL15C']."',sar_c_bbls ='".$dataOut['sarCBBLS']."',sar_c_lt ='".$dataOut['sarCLT']."',sar_d_gradeName ='".$dataOut['sarDGradeName']."',sar_d_docNo ='".$dataOut['sarDDocNo']."',sar_d_date ='".$dataOut['sarDDate']."',sar_d_klObs ='".$dataOut['sarDKLObs']."',sar_d_kl15c ='".$dataOut['sarDKL15C']."',sar_d_bbls ='".$dataOut['sarDBBLS']."',sar_d_lt ='".$dataOut['sarDLT']."' WHERE id_uploadoil = '".$idNya."' ";
					$this->koneksi->mysqlQuery($sqlupdDisCharge);

					$sqlupdDisChargeAgree = " UPDATE ".$this->dbName.".tbluploadoil_dischargagreement SET vessel_a_klHour ='".$dataOut['vesselCapacityAHour']."',vessel_a_kgCm ='".$dataOut['vesselCapacityAKg']."',vessel_b_klHour ='".$dataOut['vesselCapacityBHour']."',vessel_b_kgCm ='".$dataOut['vesselCapacityBKg']."',vessel_c_klHour ='".$dataOut['vesselCapacityCHour']."',vessel_c_kgCm ='".$dataOut['vesselCapacityCKg']."',vessel_d_klHour ='".$dataOut['vesselCapacityDHour']."',vessel_d_kgCm ='".$dataOut['vesselCapacityDKg']."',shore_a_klHour ='".$dataOut['shoreCapacityAHour']."',shore_a_kgCm ='".$dataOut['shoreCapacityAKg']."',shore_b_klHour ='".$dataOut['shoreCapacityBHour']."',shore_b_kgCm ='".$dataOut['shoreCapacityBKg']."',shore_c_klHour ='".$dataOut['shoreCapacityCHour']."',shore_c_kgCm ='".$dataOut['shoreCapacityCKg']."',shore_d_klHour ='".$dataOut['shoreCapacityDHour']."',shore_d_kgCm ='".$dataOut['shoreCapacityDKg']."',loading_a_klHour ='".$dataOut['loadingAHour']."',loading_a_kgCm ='".$dataOut['loadingAKg']."',loading_b_klHour ='".$dataOut['loadingBHour']."',loading_b_kgCm ='".$dataOut['loadingBKg']."',loading_c_klHour ='".$dataOut['loadingCHour']."',loading_c_kgCm ='".$dataOut['loadingCKg']."',loading_d_klHour ='".$dataOut['loadingDHour']."',loading_d_kgCm ='".$dataOut['loadingDKg']."',average_a_klHour ='".$dataOut['averageAHour']."',average_a_kgCm ='".$dataOut['averageAKg']."',average_b_klHour ='".$dataOut['averageBHour']."',average_b_kgCm ='".$dataOut['averageBKg']."',average_c_klHour ='".$dataOut['averageCHour']."',average_c_kgCm ='".$dataOut['averageCKg']."',average_d_klHour ='".$dataOut['averageDHour']."',average_d_kgCm ='".$dataOut['averageDKg']."' WHERE id_uploadoil = '".$idNya."' ";
					$this->koneksi->mysqlQuery($sqlupdDisChargeAgree);

					$sqlupdRemark = " UPDATE ".$this->dbName.".tbluploadoil_remark SET dest_loc_1 ='".$dataOut['remarkDestPortLokasi1']."',dest_loc_2 ='".$dataOut['remarkDestPortLokasi2']."',dest_loc_3 ='".$dataOut['remarkDestPortLokasi3']."',dev_loc_1 ='".$dataOut['remarkDevPortLokasi1']."',dev_loc_2 ='".$dataOut['remarkDevPortLokasi2']."',dev_loc_3 ='".$dataOut['remarkDevPortLokasi3']."',distance_inport ='".$dataOut['remarkInPort']."',distance_current ='".$dataOut['remarkCurrentPosition']."' WHERE id_uploadoil = '".$idNya."' ";
					$this->koneksi->mysqlQuery($sqlupdRemark);

					$stInsert = "sukses";
				} catch (Exception $ed) {
					$stInsert = "gagal =>".$ed;
				}
			}
			return $stInsert;
		}
	}

	function getDataUploadOil($idGet = "",$searchNya = "")
	{
		$no = 1;
		$idNya = $_POST['id'];
		$dataTR = "";
		$vesselName = $_SESSION["vesselName"];
		$whereNya = " AND MONTH(date_upload) = MONTH(CURRENT_DATE()) AND YEAR(date_upload) = YEAR(CURRENT_DATE())";

		if($idGet != "")
		{
			$whereNya = " AND id = '".$idGet."'";
		}
		if($searchNya != "")
		{
			$whereNya = $searchNya;
		}

		$query = $this->koneksi->mysqlQuery("SELECT * FROM ".$this->dbName.".tbluploadoil WHERE vessel = '".$vesselName."' AND deletests = '0' ".$whereNya." ORDER BY id DESC ");
		if($idGet == "" || $searchNya != "")
		{
			while($row = $this->koneksi->mysqlFetch($query))
			{
				$dataTR .= "
							<tr>
								<td align=\"center\">".$no."</td>
								<td style=\"padding-left:3px;\">&nbsp ".$row['master_name']."</td>
								<td align=\"center\" style=\"padding-left:3px;\">
									<a class=\"spanLogout\" onclick=\"onClickView('".$row['id']."');\" >".$row['voyage_no']."</a>
								</td>
								<td align=\"center\">".$row['position_report']."</td>
								<td> &nbsp".$row['inport']."</td>
								<td align=\"center\"> &nbsp".$this->convertDateNya($row['date_upload'])."</td>
							</tr>
							";
				$no ++;
			}
			$dataOut = $dataTR;
		}else{
			while($row = $this->koneksi->mysqlFetch($query))
			{
				$dataOut['masterName'] = $row['master_name'];
				$dataOut['satelitePhone'] = $row['satellite_phone'];
				$dataOut['sateliteEmail'] = $row['satellite_email'];
				$dataOut['oilPosition'] = $row['position_report'];
				$dataOut['voyageNo'] = $row['voyage_no'];
				$dataOut['inPort'] = $row['inport'];
				$dataOut['codeAADate'] = $row['atd_date'];
				$dataOut['codeAATime'] = $row['atd_hour'];
				$dataOut['previousPort'] = $row['previous_port'];
				$dataOut['actualDistance'] = $row['actual_distance'];
				$dataOut['codeBBDate'] = $row['ata_outer_date'];
				$dataOut['codeBBTime'] = $row['ata_outer_hour'];
				$dataOut['codeCCDate'] = $row['ata_inner_date'];
				$dataOut['codeCCTime'] = $row['ata_inner_hour'];
				$dataOut['codeDDDate'] = $row['berthed_date'];
				$dataOut['codeDDTime'] = $row['berthed_hour'];
				$dataOut['gradeA'] = $row['type_grade_a'];
				$dataOut['gradeB'] = $row['type_grade_b'];
				$dataOut['gradeC'] = $row['type_grade_c'];
				$dataOut['gradeD'] = $row['type_grade_d'];
				for ($lan=1; $lan <= 4; $lan++)
				{ 
					$tblGrede = "";
					$gradeName = "";
					if($lan == 1)
					{	
						$tblGrede = "a";
						$gradeName = "A";
					}else if($lan == 2)
					{
						$tblGrede = "b";
						$gradeName = "B";
					}else if($lan == 3)
					{
						$tblGrede = "c";
						$gradeName = "C";
					}else if($lan == 4)
					{
						$tblGrede = "d";
						$gradeName = "D";
					}
					$queryGrade = $this->koneksi->mysqlQuery("SELECT * FROM ".$this->dbName.".tbluploadoil_opt_grade".$tblGrede." WHERE id_uploadoil = '".$idGet."' ");
					while($rowGrade = $this->koneksi->mysqlFetch($queryGrade))
					{
						$dataOut["grade".$gradeName."HoseConnDate"] = $rowGrade['hoseconn_date'];
						$dataOut["grade".$gradeName."HoseConnTime"] = $rowGrade['hoseconn_hour'];
						$dataOut["grade".$gradeName."1StartDate"] = $rowGrade['start1_date'];
						$dataOut["grade".$gradeName."1StartTime"] = $rowGrade['start1_hour'];
						$dataOut["grade".$gradeName."1StopDate"] = $rowGrade['stop1_date'];
						$dataOut["grade".$gradeName."1StopTime"] = $rowGrade['stop1_hour'];
						$dataOut["grade".$gradeName."1StopNote"] = $rowGrade['stop1_note'];
						$dataOut["grade".$gradeName."2StartDate"] = $rowGrade['start2_date'];
						$dataOut["grade".$gradeName."2StartTime"] = $rowGrade['start2_hour'];
						$dataOut["grade".$gradeName."2StopDate"] = $rowGrade['stop2_date'];
						$dataOut["grade".$gradeName."2StopTime"] = $rowGrade['stop2_hour'];
						$dataOut["grade".$gradeName."2StopNote"] = $rowGrade['stop2_note'];
						$dataOut["grade".$gradeName."3StartDate"] = $rowGrade['start3_date'];
						$dataOut["grade".$gradeName."3StartTime"] = $rowGrade['start3_hour'];
						$dataOut["grade".$gradeName."3StopDate"] = $rowGrade['stop3_date'];
						$dataOut["grade".$gradeName."3StopTime"] = $rowGrade['stop3_hour'];
						$dataOut["grade".$gradeName."3StopNote"] = $rowGrade['stop3_note'];
						$dataOut["grade".$gradeName."HoseDisConnDate"] = $rowGrade['hosedisconn_date'];
						$dataOut["grade".$gradeName."HoseDisConnTime"] = $rowGrade['hosedisconn_hour'];
					}
				}
				$dataOut['unBerthedDate'] = $row['unberthed_date'];
				$dataOut['unBerthedTime'] = $row['unberthed_hour'];
				$dataOut['anchorDate'] = $row['anchor_inner_date'];
				$dataOut['anchorTime'] = $row['anchor_inner_hour'];
				$dataOut['actualLineDate'] = $row['actual_line_date'];
				$dataOut['actualLineTime'] = $row['actual_line_hour'];
				$dataOut['atdOuterDate'] = $row['atd_outer_date'];
				$dataOut['atdOuterTime'] = $row['atd_outer_hour'];

				$queryAtLoading = $this->koneksi->mysqlQuery("SELECT * FROM ".$this->dbName.".tbluploadoil_atloading WHERE id_uploadoil = '".$idGet."' ");
				while($rowAtLoading = $this->koneksi->mysqlFetch($queryAtLoading))
				{
					$dataOut['bolAGradeName'] = $rowAtLoading['bol_a_gradeName'];
					$dataOut['bolADocNo'] = $rowAtLoading['bol_a_docNo'];
					$dataOut['bolADate'] = $rowAtLoading['bol_a_date'];
					$dataOut['bolAKlObs'] = $rowAtLoading['bol_a_klObs'];
					$dataOut['bolAKL15C'] = $rowAtLoading['bol_a_kl15c'];
					$dataOut['bolABBLS'] = $rowAtLoading['bol_a_bbls'];
					$dataOut['bolAMT'] = $rowAtLoading['bol_a_mt'];
					$dataOut['bolALT'] = $rowAtLoading['bol_a_lt'];
					$dataOut['bolBGradeName'] = $rowAtLoading['bol_b_gradeName'];
					$dataOut['bolBDocNo'] = $rowAtLoading['bol_b_docNo'];
					$dataOut['bolBDate'] = $rowAtLoading['bol_b_date'];
					$dataOut['bolBKlObs'] = $rowAtLoading['bol_b_klObs'];
					$dataOut['bolBKL15C'] = $rowAtLoading['bol_b_kl15c'];
					$dataOut['bolBBBLS'] = $rowAtLoading['bol_b_bbls'];
					$dataOut['bolBMT'] = $rowAtLoading['bol_b_mt'];
					$dataOut['bolBLT'] = $rowAtLoading['bol_b_lt'];
					$dataOut['bolCGradeName'] = $rowAtLoading['bol_c_gradeName'];
					$dataOut['bolCDocNo'] = $rowAtLoading['bol_c_docNo'];
					$dataOut['bolCDate'] = $rowAtLoading['bol_c_date'];
					$dataOut['bolCKlObs'] = $rowAtLoading['bol_c_klObs'];
					$dataOut['bolCKL15C'] = $rowAtLoading['bol_c_kl15c'];
					$dataOut['bolCBBLS'] = $rowAtLoading['bol_c_bbls'];
					$dataOut['bolCMT'] = $rowAtLoading['bol_c_mt'];
					$dataOut['bolCLT'] = $rowAtLoading['bol_c_lt'];
					$dataOut['bolDGradeName'] = $rowAtLoading['bol_d_gradeName'];
					$dataOut['bolDDocNo'] = $rowAtLoading['bol_d_docNo'];
					$dataOut['bolDDate'] = $rowAtLoading['bol_d_date'];
					$dataOut['bolDKlObs'] = $rowAtLoading['bol_d_klObs'];
					$dataOut['bolDKL15C'] = $rowAtLoading['bol_d_kl15c'];
					$dataOut['bolDBBLS'] = $rowAtLoading['bol_d_bbls'];
					$dataOut['bolDMT'] = $rowAtLoading['bol_d_mt'];
					$dataOut['bolDLT'] = $rowAtLoading['bol_d_lt'];
					$dataOut['sfalAGradeName'] = $rowAtLoading['sfal_a_gradeName'];
					$dataOut['sfalADocNo'] = $rowAtLoading['sfal_a_docNo'];
					$dataOut['sfalADate'] = $rowAtLoading['sfal_a_date'];
					$dataOut['sfalAKLObs'] = $rowAtLoading['sfal_a_klObs'];
					$dataOut['sfalAKL15C'] = $rowAtLoading['sfal_a_kl15c'];
					$dataOut['sfalABBLS'] = $rowAtLoading['sfal_a_bbls'];
					$dataOut['sfalAMT'] = $rowAtLoading['sfal_a_mt'];
					$dataOut['sfalALT'] = $rowAtLoading['sfal_a_lt'];
					$dataOut['sfalBGradeName'] = $rowAtLoading['sfal_b_gradeName'];
					$dataOut['sfalBDocNo'] = $rowAtLoading['sfal_b_docNo'];
					$dataOut['sfalBDate'] = $rowAtLoading['sfal_b_date'];
					$dataOut['sfalBKLObs'] = $rowAtLoading['sfal_b_klObs'];
					$dataOut['sfalBKL15C'] = $rowAtLoading['sfal_b_kl15c'];
					$dataOut['sfalBBBLS'] = $rowAtLoading['sfal_b_bbls'];
					$dataOut['sfalBMT'] = $rowAtLoading['sfal_b_mt'];
					$dataOut['sfalBLT'] = $rowAtLoading['sfal_b_lt'];
					$dataOut['sfalCGradeName'] = $rowAtLoading['sfal_c_gradeName'];
					$dataOut['sfalCDocNo'] = $rowAtLoading['sfal_c_docNo'];
					$dataOut['sfalCDate'] = $rowAtLoading['sfal_c_date'];
					$dataOut['sfalCKLObs'] = $rowAtLoading['sfal_c_klObs'];
					$dataOut['sfalCKL15C'] = $rowAtLoading['sfal_c_kl15c'];
					$dataOut['sfalCBBLS'] = $rowAtLoading['sfal_c_bbls'];
					$dataOut['sfalCMT'] = $rowAtLoading['sfal_c_mt'];
					$dataOut['sfalCLT'] = $rowAtLoading['sfal_c_lt'];
					$dataOut['sfalDGradeName'] = $rowAtLoading['sfal_d_gradeName'];
					$dataOut['sfalDDocNo'] = $rowAtLoading['sfal_d_docNo'];
					$dataOut['sfalDDate'] = $rowAtLoading['sfal_d_date'];
					$dataOut['sfalDKLObs'] = $rowAtLoading['sfal_d_klObs'];
					$dataOut['sfalDKL15C'] = $rowAtLoading['sfal_d_kl15c'];
					$dataOut['sfalDBBLS'] = $rowAtLoading['sfal_d_bbls'];
					$dataOut['sfalDMT'] = $rowAtLoading['sfal_d_mt'];
					$dataOut['sfalDLT'] = $rowAtLoading['sfal_d_lt'];
					$dataOut['sfblAGradeName'] = $rowAtLoading['sfbl_a_gradeName'];
					$dataOut['sfblADocNo'] = $rowAtLoading['sfbl_a_docNo'];
					$dataOut['sfblADate'] = $rowAtLoading['sfbl_a_date'];
					$dataOut['sfblAKLObs'] = $rowAtLoading['sfbl_a_klObs'];
					$dataOut['sfblAKL15C'] = $rowAtLoading['sfbl_a_kl15c'];
					$dataOut['sfblABBLS'] = $rowAtLoading['sfbl_a_bbls'];
					$dataOut['sfblAMT'] = $rowAtLoading['sfbl_a_mt'];
					$dataOut['sfblALT'] = $rowAtLoading['sfbl_a_lt'];
					$dataOut['sfblBGradeName'] = $rowAtLoading['sfbl_b_gradeName'];
					$dataOut['sfblBDocNo'] = $rowAtLoading['sfbl_b_docNo'];
					$dataOut['sfblBDate'] = $rowAtLoading['sfbl_b_date'];
					$dataOut['sfblBKLObs'] = $rowAtLoading['sfbl_b_klObs'];
					$dataOut['sfblBKL15C'] = $rowAtLoading['sfbl_b_kl15c'];
					$dataOut['sfblBBBLS'] = $rowAtLoading['sfbl_b_bbls'];
					$dataOut['sfblBMT'] = $rowAtLoading['sfbl_b_mt'];
					$dataOut['sfblBLT'] = $rowAtLoading['sfbl_b_lt'];
					$dataOut['sfblCGradeName'] = $rowAtLoading['sfbl_c_gradeName'];
					$dataOut['sfblCDocNo'] = $rowAtLoading['sfbl_c_docNo'];
					$dataOut['sfblCDate'] = $rowAtLoading['sfbl_c_date'];
					$dataOut['sfblCKLObs'] = $rowAtLoading['sfbl_c_klObs'];
					$dataOut['sfblCKL15C'] = $rowAtLoading['sfbl_c_kl15c'];
					$dataOut['sfblCBBLS'] = $rowAtLoading['sfbl_c_bbls'];
					$dataOut['sfblCMT'] = $rowAtLoading['sfbl_c_mt'];
					$dataOut['sfblCLT'] = $rowAtLoading['sfbl_c_lt'];
					$dataOut['sfblDGradeName'] = $rowAtLoading['sfbl_d_gradeName'];
					$dataOut['sfblDDocNo'] = $rowAtLoading['sfbl_d_docNo'];
					$dataOut['sfblDDate'] = $rowAtLoading['sfbl_d_date'];
					$dataOut['sfblDKLObs'] = $rowAtLoading['sfbl_d_klObs'];
					$dataOut['sfblDKL15C'] = $rowAtLoading['sfbl_d_kl15c'];
					$dataOut['sfblDBBLS'] = $rowAtLoading['sfbl_d_bbls'];
					$dataOut['sfblDMT'] = $rowAtLoading['sfbl_d_mt'];
					$dataOut['sfblDLT'] = $rowAtLoading['sfbl_d_lt'];
				}
				$queryDischarging = $this->koneksi->mysqlQuery("SELECT * FROM ".$this->dbName.".tbluploadoil_atdischarging WHERE id_uploadoil = '".$idGet."' ");
				while($rowDischarg = $this->koneksi->mysqlFetch($queryDischarging))
				{
					$dataOut['newBolAGradeName'] = $rowDischarg['newBol_a_gradeName'];
					$dataOut['newBolADocNo'] = $rowDischarg['newBol_a_docNo'];
					$dataOut['newBolADate'] = $rowDischarg['newBol_a_date'];
					$dataOut['newBolAKlObs'] = $rowDischarg['newBol_a_klObs'];
					$dataOut['newBolAKL15C'] = $rowDischarg['newBol_a_kl15c'];
					$dataOut['newBolABBLS'] = $rowDischarg['newBol_a_bbls'];
					$dataOut['newBolAMT'] = $rowDischarg['newBol_a_mt'];
					$dataOut['newBolALT'] = $rowDischarg['newBol_a_lt'];
					$dataOut['newBolBGradeName'] = $rowDischarg['newBol_b_gradeName'];
					$dataOut['newBolBDocNo'] = $rowDischarg['newBol_b_docNo'];
					$dataOut['newBolBDate'] = $rowDischarg['newBol_b_date'];
					$dataOut['newBolBKlObs'] = $rowDischarg['newBol_b_klObs'];
					$dataOut['newBolBKL15C'] = $rowDischarg['newBol_b_kl15c'];
					$dataOut['newBolBBBLS'] = $rowDischarg['newBol_b_bbls'];
					$dataOut['newBolBMT'] = $rowDischarg['newBol_b_mt'];
					$dataOut['newBolBLT'] = $rowDischarg['newBol_b_lt'];
					$dataOut['newBolCGradeName'] = $rowDischarg['newBol_c_gradeName'];
					$dataOut['newBolCDocNo'] = $rowDischarg['newBol_c_docNo'];
					$dataOut['newBolCDate'] = $rowDischarg['newBol_c_date'];
					$dataOut['newBolCKlObs'] = $rowDischarg['newBol_c_klObs'];
					$dataOut['newBolCKL15C'] = $rowDischarg['newBol_c_kl15c'];
					$dataOut['newBolCBBLS'] = $rowDischarg['newBol_c_bbls'];
					$dataOut['newBolCMT'] = $rowDischarg['newBol_c_mt'];
					$dataOut['newBolCLT'] = $rowDischarg['newBol_c_lt'];
					$dataOut['newBolDGradeName'] = $rowDischarg['newBol_d_gradeName'];
					$dataOut['newBolDDocNo'] = $rowDischarg['newBol_d_docNo'];
					$dataOut['newBolDDate'] = $rowDischarg['newBol_d_date'];
					$dataOut['newBolDKlObs'] = $rowDischarg['newBol_d_klObs'];
					$dataOut['newBolDKL15C'] = $rowDischarg['newBol_d_kl15c'];
					$dataOut['newBolDBBLS'] = $rowDischarg['newBol_d_bbls'];
					$dataOut['newBolDMT'] = $rowDischarg['newBol_d_mt'];
					$dataOut['newBolDLT'] = $rowDischarg['newBol_d_lt'];
					$dataOut['sfbdAGradeName'] = $rowDischarg['sfbd_a_gradeName'];
					$dataOut['sfbdADocNo'] = $rowDischarg['sfbd_a_docNo'];
					$dataOut['sfbdADate'] = $rowDischarg['sfbd_a_date'];
					$dataOut['sfbdAKLObs'] = $rowDischarg['sfbd_a_klObs'];
					$dataOut['sfbdAKL15C'] = $rowDischarg['sfbd_a_kl15c'];
					$dataOut['sfbdABBLS'] = $rowDischarg['sfbd_a_bbls'];
					$dataOut['sfbdAMT'] = $rowDischarg['sfbd_a_mt'];
					$dataOut['sfbdALT'] = $rowDischarg['sfbd_a_lt'];
					$dataOut['sfbdBGradeName'] = $rowDischarg['sfbd_b_gradeName'];
					$dataOut['sfbdBDocNo'] = $rowDischarg['sfbd_b_docNo'];
					$dataOut['sfbdBDate'] = $rowDischarg['sfbd_b_date'];
					$dataOut['sfbdBKLObs'] = $rowDischarg['sfbd_b_klObs'];
					$dataOut['sfbdBKL15C'] = $rowDischarg['sfbd_b_kl15c'];
					$dataOut['sfbdBBBLS'] = $rowDischarg['sfbd_b_bbls'];
					$dataOut['sfbdBMT'] = $rowDischarg['sfbd_b_mt'];
					$dataOut['sfbdBLT'] = $rowDischarg['sfbd_b_lt'];
					$dataOut['sfbdCGradeName'] = $rowDischarg['sfbd_c_gradeName'];
					$dataOut['sfbdCDocNo'] = $rowDischarg['sfbd_c_docNo'];
					$dataOut['sfbdCDate'] = $rowDischarg['sfbd_c_date'];
					$dataOut['sfbdCKLObs'] = $rowDischarg['sfbd_c_klObs'];
					$dataOut['sfbdCKL15C'] = $rowDischarg['sfbd_c_kl15c'];
					$dataOut['sfbdCBBLS'] = $rowDischarg['sfbd_c_bbls'];
					$dataOut['sfbdCMT'] = $rowDischarg['sfbd_c_mt'];
					$dataOut['sfbdCLT'] = $rowDischarg['sfbd_c_lt'];
					$dataOut['sfbdDGradeName'] = $rowDischarg['sfbd_d_gradeName'];
					$dataOut['sfbdDDocNo'] = $rowDischarg['sfbd_d_docNo'];
					$dataOut['sfbdDDate'] = $rowDischarg['sfbd_d_date'];
					$dataOut['sfbdDKLObs'] = $rowDischarg['sfbd_d_klObs'];
					$dataOut['sfbdDKL15C'] = $rowDischarg['sfbd_d_kl15c'];
					$dataOut['sfbdDBBLS'] = $rowDischarg['sfbd_d_bbls'];
					$dataOut['sfbdDMT'] = $rowDischarg['sfbd_d_mt'];
					$dataOut['sfbdDLT'] = $rowDischarg['sfbd_d_lt'];
					$dataOut['sfadAGradeName'] = $rowDischarg['sfad_a_gradeName'];
					$dataOut['sfadADocNo'] = $rowDischarg['sfad_a_docNo'];
					$dataOut['sfadADate'] = $rowDischarg['sfad_a_date'];
					$dataOut['sfadAKLObs'] = $rowDischarg['sfad_a_klObs'];
					$dataOut['sfadAKL15C'] = $rowDischarg['sfad_a_kl15c'];
					$dataOut['sfadABBLS'] = $rowDischarg['sfad_a_bbls'];
					$dataOut['sfadAMT'] = $rowDischarg['sfad_a_mt'];
					$dataOut['sfadALT'] = $rowDischarg['sfad_a_lt'];
					$dataOut['sfadBGradeName'] = $rowDischarg['sfad_b_gradeName'];
					$dataOut['sfadBDocNo'] = $rowDischarg['sfad_b_docNo'];
					$dataOut['sfadBDate'] = $rowDischarg['sfad_b_date'];
					$dataOut['sfadBKLObs'] = $rowDischarg['sfad_b_klObs'];
					$dataOut['sfadBKL15C'] = $rowDischarg['sfad_b_kl15c'];
					$dataOut['sfadBBBLS'] = $rowDischarg['sfad_b_bbls'];
					$dataOut['sfadBMT'] = $rowDischarg['sfad_b_mt'];
					$dataOut['sfadBLT'] = $rowDischarg['sfad_b_lt'];
					$dataOut['sfadCGradeName'] = $rowDischarg['sfad_c_gradeName'];
					$dataOut['sfadCDocNo'] = $rowDischarg['sfad_c_docNo'];
					$dataOut['sfadCDate'] = $rowDischarg['sfad_c_date'];
					$dataOut['sfadCKLObs'] = $rowDischarg['sfad_c_klObs'];
					$dataOut['sfadCKL15C'] = $rowDischarg['sfad_c_kl15c'];
					$dataOut['sfadCBBLS'] = $rowDischarg['sfad_c_bbls'];
					$dataOut['sfadCMT'] = $rowDischarg['sfad_c_mt'];
					$dataOut['sfadCLT'] = $rowDischarg['sfad_c_lt'];
					$dataOut['sfadDGradeName'] = $rowDischarg['sfad_d_gradeName'];
					$dataOut['sfadDDocNo'] = $rowDischarg['sfad_d_docNo'];
					$dataOut['sfadDDate'] = $rowDischarg['sfad_d_date'];
					$dataOut['sfadDKLObs'] = $rowDischarg['sfad_d_klObs'];
					$dataOut['sfadDKL15C'] = $rowDischarg['sfad_d_kl15c'];
					$dataOut['sfadDBBLS'] = $rowDischarg['sfad_d_bbls'];
					$dataOut['sfadDMT'] = $rowDischarg['sfad_d_mt'];
					$dataOut['sfadDLT'] = $rowDischarg['sfad_d_lt'];
					$dataOut['sarAGradeName'] = $rowDischarg['sar_a_gradeName'];
					$dataOut['sarADocNo'] = $rowDischarg['sar_a_docNo'];
					$dataOut['sarADate'] = $rowDischarg['sar_a_date'];
					$dataOut['sarAKLObs'] = $rowDischarg['sar_a_klObs'];
					$dataOut['sarAKL15C'] = $rowDischarg['sar_a_kl15c'];
					$dataOut['sarABBLS'] = $rowDischarg['sar_a_bbls'];
					$dataOut['sarAMT'] = $rowDischarg['sar_a_mt'];
					$dataOut['sarALT'] = $rowDischarg['sar_a_lt'];
					$dataOut['sarBGradeName'] = $rowDischarg['sar_b_gradeName'];
					$dataOut['sarBDocNo'] = $rowDischarg['sar_b_docNo'];
					$dataOut['sarBDate'] = $rowDischarg['sar_b_date'];
					$dataOut['sarBKLObs'] = $rowDischarg['sar_b_klObs'];
					$dataOut['sarBKL15C'] = $rowDischarg['sar_b_kl15c'];
					$dataOut['sarBBBLS'] = $rowDischarg['sar_b_bbls'];
					$dataOut['sarBMT'] = $rowDischarg['sar_b_mt'];
					$dataOut['sarBLT'] = $rowDischarg['sar_b_lt'];
					$dataOut['sarCGradeName'] = $rowDischarg['sar_c_gradeName'];
					$dataOut['sarCDocNo'] = $rowDischarg['sar_c_docNo'];
					$dataOut['sarCDate'] = $rowDischarg['sar_c_date'];
					$dataOut['sarCKLObs'] = $rowDischarg['sar_c_klObs'];
					$dataOut['sarCKL15C'] = $rowDischarg['sar_c_kl15c'];
					$dataOut['sarCBBLS'] = $rowDischarg['sar_c_bbls'];
					$dataOut['sarCMT'] = $rowDischarg['sar_c_mt'];
					$dataOut['sarCLT'] = $rowDischarg['sar_c_lt'];
					$dataOut['sarDGradeName'] = $rowDischarg['sar_d_gradeName'];
					$dataOut['sarDDocNo'] = $rowDischarg['sar_d_docNo'];
					$dataOut['sarDDate'] = $rowDischarg['sar_d_date'];
					$dataOut['sarDKLObs'] = $rowDischarg['sar_d_klObs'];
					$dataOut['sarDKL15C'] = $rowDischarg['sar_d_kl15c'];
					$dataOut['sarDBBLS'] = $rowDischarg['sar_d_bbls'];
					$dataOut['sarDMT'] = $rowDischarg['sar_d_mt'];
					$dataOut['sarDLT'] = $rowDischarg['sar_d_lt'];
				}
				$dataOut['bunkerRobAtaMFO'] = $row['bunkerrobata_mfo'];
				$dataOut['bunkerRobAtaMDO'] = $row['bunkerrobata_mdo'];
				$dataOut['bunkerRobAtaHSD'] = $row['bunkerrobata_hsd'];
				$dataOut['bunkerRobAtaFWMT'] = $row['bunkerrobata_fw'];
				$dataOut['bunkerRobAtaHSFO'] = $row['bunkerrobata_hsfo'];
				$dataOut['bunkerRobAtaLSFO'] = $row['bunkerrobata_lsfo'];
				$dataOut['bunkerRobAtaOWST'] = $row['bunkerrobata_owst'];
				$dataOut['bunkerReplMFO'] = $row['bunkerreflenishment_mfo'];
				$dataOut['bunkerReplMDO'] = $row['bunkerreflenishment_mdo'];
				$dataOut['bunkerReplHSD'] = $row['bunkerreflenishment_hsd'];
				$dataOut['bunkerReplFWMT'] = $row['bunkerreflenishment_fw'];
				$dataOut['bunkerReplHSFO'] = $row['bunkerreflenishment_hsfo'];
				$dataOut['bunkerReplLSFO'] = $row['bunkerreflenishment_lsfo'];
				$dataOut['bunkerReplOWST'] = $row['bunkerreflenishment_owst'];
				$dataOut['bunkerRobAtdMFO'] = $row['bunkerrobatd_mfo'];
				$dataOut['bunkerRobAtdMDO'] = $row['bunkerrobatd_mdo'];
				$dataOut['bunkerRobAtdHSD'] = $row['bunkerrobatd_hsd'];
				$dataOut['bunkerRobAtdFWMT'] = $row['bunkerrobatd_fw'];
				$dataOut['bunkerRobAtdHSFO'] = $row['bunkerrobatd_hsfo'];
				$dataOut['bunkerRobAtdLSFO'] = $row['bunkerrobatd_lsfo'];
				$dataOut['bunkerRobAtdOWST'] = $row['bunkerrobatd_owst'];
				$dataOut['draftFWD'] = $row['draft_fwd'];
				$dataOut['draftAFT'] = $row['draft_aft'];
				$dataOut['draftMean'] = $row['draft_mean'];
				$dataOut['arrivalETADate'] = $row['arrivaleta_date'];
				$dataOut['arrivalETATime'] = $row['arrivaleta_hour'];
				$dataOut['arrivalETAPort'] = $row['arrivaleta_location'];
				$dataOut['arrivalDeviationDate'] = $row['arrivaldeviation_date'];
				$dataOut['arrivalDeviationTime'] = $row['arrivaldeviation_hour'];
				$dataOut['arrivalDeviationPort'] = $row['arrivaldeviation_location'];
				$dataOut['vesselDelayInstallation'] = $row['vesseldue_install'];
				$dataOut['vesselDelayVessel'] = $row['vesseldue_vessel'];
				$dataOut['vesselDelayAgent'] = $row['vesseldue_agent'];
				$dataOut['vesselDelayOther'] = $row['vesseldue_other'];

				$queryRemark = $this->koneksi->mysqlQuery("SELECT * FROM ".$this->dbName.".tbluploadoil_remark WHERE id_uploadoil = '".$idGet."' ");
				while($rowRemark = $this->koneksi->mysqlFetch($queryRemark))
				{
					$dataOut['remarkDestPortLokasi1'] = $rowRemark['dest_loc_1'];
					$dataOut['remarkDestPortLokasi2'] = $rowRemark['dest_loc_2'];
					$dataOut['remarkDestPortLokasi3'] = $rowRemark['dest_loc_3'];
					$dataOut['remarkDevPortLokasi1'] = $rowRemark['dev_loc_1'];
					$dataOut['remarkDevPortLokasi2'] = $rowRemark['dev_loc_2'];
					$dataOut['remarkDevPortLokasi3'] = $rowRemark['dev_loc_3'];
					$dataOut['remarkInPort'] = $rowRemark['distance_inport'];
					$dataOut['remarkCurrentPosition'] = $rowRemark['distance_current'];
				}
				$queryDisChargeAgree = $this->koneksi->mysqlQuery("SELECT * FROM ".$this->dbName.".tbluploadoil_dischargagreement WHERE id_uploadoil = '".$idGet."' ");
				while($rowDisChargeAgree = $this->koneksi->mysqlFetch($queryDisChargeAgree))
				{
					$dataOut['vesselCapacityAHour'] = $rowDisChargeAgree['vessel_a_klHour'];
					$dataOut['vesselCapacityAKg'] = $rowDisChargeAgree['vessel_a_kgCm'];
					$dataOut['vesselCapacityBHour'] = $rowDisChargeAgree['vessel_b_klHour'];
					$dataOut['vesselCapacityBKg'] = $rowDisChargeAgree['vessel_b_kgCm'];
					$dataOut['vesselCapacityCHour'] = $rowDisChargeAgree['vessel_c_klHour'];
					$dataOut['vesselCapacityCKg'] = $rowDisChargeAgree['vessel_c_kgCm'];
					$dataOut['vesselCapacityDHour'] = $rowDisChargeAgree['vessel_d_klHour'];
					$dataOut['vesselCapacityDKg'] = $rowDisChargeAgree['vessel_d_kgCm'];
					$dataOut['shoreCapacityAHour'] = $rowDisChargeAgree['shore_a_klHour'];
					$dataOut['shoreCapacityAKg'] = $rowDisChargeAgree['shore_a_kgCm'];
					$dataOut['shoreCapacityBHour'] = $rowDisChargeAgree['shore_b_klHour'];
					$dataOut['shoreCapacityBKg'] = $rowDisChargeAgree['shore_b_kgCm'];
					$dataOut['shoreCapacityCHour'] = $rowDisChargeAgree['shore_c_klHour'];
					$dataOut['shoreCapacityCKg'] = $rowDisChargeAgree['shore_c_kgCm'];
					$dataOut['shoreCapacityDHour'] = $rowDisChargeAgree['shore_d_klHour'];
					$dataOut['shoreCapacityDKg'] = $rowDisChargeAgree['shore_d_kgCm'];
					$dataOut['loadingAHour'] = $rowDisChargeAgree['loading_a_klHour'];
					$dataOut['loadingAKg'] = $rowDisChargeAgree['loading_a_kgCm'];
					$dataOut['loadingBHour'] = $rowDisChargeAgree['loading_b_klHour'];
					$dataOut['loadingBKg'] = $rowDisChargeAgree['loading_b_kgCm'];
					$dataOut['loadingCHour'] = $rowDisChargeAgree['loading_c_klHour'];
					$dataOut['loadingCKg'] = $rowDisChargeAgree['loading_c_kgCm'];
					$dataOut['loadingDHour'] = $rowDisChargeAgree['loading_d_klHour'];
					$dataOut['loadingDKg'] = $rowDisChargeAgree['loading_d_kgCm'];
					$dataOut['averageAHour'] = $rowDisChargeAgree['average_a_klHour'];
					$dataOut['averageAKg'] = $rowDisChargeAgree['average_a_kgCm'];
					$dataOut['averageBHour'] = $rowDisChargeAgree['average_b_klHour'];
					$dataOut['averageBKg'] = $rowDisChargeAgree['average_b_kgCm'];
					$dataOut['averageCHour'] = $rowDisChargeAgree['average_c_klHour'];
					$dataOut['averageCKg'] = $rowDisChargeAgree['average_c_kgCm'];
					$dataOut['averageDHour'] = $rowDisChargeAgree['average_d_klHour'];
					$dataOut['averageDKg'] = $rowDisChargeAgree['average_d_kgCm'];
				}
			}
		}
		return $dataOut;
	}

	function cekData($voyageNo = "",$dbNya = "")
	{
		$query = $this->koneksi->mysqlquery("SELECT COUNT(id) as jumlah FROM ".$dbNya." WHERE  voyage_no = '".$voyageNo."' AND deletests=0;");
		$row = $this->koneksi->mysqlFetch($query);
		return $row['jumlah'];
	}

	function getDataSummaryBUnkerConsSpeed($searchVoyNo = "",$typeNya = "")
	{
		error_reporting(1);
		$voyNo = "";
		$voyType = "";
		$voyYear = "";
		$voyNoOld = "";
		$voyYearOld = "";
		$dataOut = array();
		$dataPrint = array();
		$dsNya = array();
		$outPrint = array();
		$dataTR = "";
		$whereNya = "WHERE deletests = '0' ";
		$limitNya = "";
		$sLimit = 0;
		$eLimit = 0;
		$displayData = 20;
		$dataPage = $_POST["page"];
		$yearNow = date("y");

		if($searchVoyNo != "")
		{
			$dV = explode("/", $searchVoyNo);
			if(count($dV) <= 1)
			{
				$whereNya .= " AND voyage_no like '%".$searchVoyNo."%' ";
			}else{
				$voyNo = $dV[0];
				$voyType = $dV[1];
				$voyYear = $dV[2];
				$whereNya .= " AND voyage_no like '%".$voyNo."%' AND voyage_no like '%".$voyYear."%' ";
			}
			$typeNya = 'search';
		}else{
			$eLimit = $displayData;
			if($dataPage != "")
			{
				$sLimit = ($dataPage * $displayData) - $displayData;
				$eLimit = $displayData;
			}
			$limitNya = "LIMIT ".$sLimit.",".($eLimit+1);
			$whereNya .= " AND v_year = '".$yearNow."' ";
		}

		if($typeNya == "export")
		{
			$limitNya = "";
		}
		// $sql = " SELECT * FROM tbluploadoil ".$whereNya." ORDER BY v_year,v_no ASC,v_type DESC ".$limitNya;
		$sql = " SELECT * FROM tbluploadoil ".$whereNya." ORDER BY v_year,v_no ASC,(v_type = 'L') DESC ".$limitNya;

		$query = $this->koneksi->mysqlquery($sql);
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$dtCop = array();
			$dtEop = array();
			$dV = explode("/", $row["voyage_no"]);
			$voyNo = $dV[0];
			$voyType = $dV[1];
			$voyYear = $dV[2];
			$bungkeringMT = $row["bunkerreflenishment_mdo"];

			if($row["bunkerreflenishment_mdo"] == "-" || $row["bunkerreflenishment_mdo"] == "")
			{
				$bungkeringMT = 0;
			}

			$dtCop["voyNo"] = $row["voyage_no"];
			$dtCop["copPort"] = $row["inport"];
			$dtCop["copDate"] = $row["atd_outer_date"];
			$dtCop["copTime"] = $row["atd_outer_hour"];
			$dtCop["copDo"] = $row["bunkerrobatd_mdo"];
			$dtCop["copFw"] = $row["bunkerrobatd_fw"];
			$dtCop["bunkeringInMt"] = $bungkeringMT;
			$isiCargo = "";
			if($row["type_grade_a"] != "")
			{
				$isiCargo .= $row["type_grade_a"];
			}
			if($row["type_grade_b"] != "")
			{
				if($isiCargo == "")
				{
					$isiCargo .= $row["type_grade_b"];
				}else{
					$isiCargo .= ", ".$row["type_grade_b"];
				}
			}
			if($row["type_grade_c"] != "")
			{
				if($isiCargo == "")
				{
					$isiCargo .= $row["type_grade_c"];
				}else{
					$isiCargo .= ", ".$row["type_grade_c"];
				}
			}
			if($row["type_grade_d"] != "")
			{
				if($isiCargo == "")
				{
					$isiCargo .= $row["type_grade_d"];
				}else{
					$isiCargo .= ", ".$row["type_grade_d"];
				}
			}
			$dtCop["copCargo"] = $isiCargo;

			$dtEop["voyNo"] = $row["voyage_no"];
			$dtEop["eopPort"] = $row["inport"];
			$dtEop["eopDate"] = $row["ata_outer_date"];
			$dtEop["eopTime"] = $row["ata_outer_hour"];
			$dtEop["eopDo"] = $row["bunkerrobata_mdo"];
			$dtEop["eopFw"] = $row["bunkerrobata_fw"];
			$dtEop["eopDist"] = $row["actual_distance"];

			$dataOut[$voyNo."/".$voyYear]["COP"][$voyType] = $dtCop;
			$dataOut[$voyNo."/".$voyYear]["EOP"][$voyType] = $dtEop;
		}
		$sumTypeVoy = 1;
		// echo"<pre>";print_r($dataOut);exit;
		foreach ($dataOut as $key => $value)
		{
			$dV = explode("/", $key);
			$voyNo = $dV[0]+1;
			$voyYear = $dV[1];
			if($voyNo < 10)
			{
				$voyNo = "0".$voyNo;
			}

			$noTypeVoy = 1;
			foreach ($value["COP"] as $key1 => $value1)
			{
				$cekvoyNo = $dataOut[$voyNo."/".$voyYear]["COP"]["L"]["voyNo"];
				if($cekvoyNo == "")//jika pergantian tahun
				{
					$voyNo = "01";
					$voyYear = $voyYear +1;
				}
				if($key1 == "L")
				{
					$dsNya["voyNo"] = $value1["voyNo"];
					$dsNya["copPort"] = $value1["copPort"];
					$dsNya["copDate"] = $value1["copDate"];
					$dsNya["copTime"] = $value1["copTime"];
					$dsNya["copDo"] = $value1["copDo"];
					$dsNya["copFw"] = $value1["copFw"];
					$dsNya["cargo"] = $value1["copCargo"];
					$dsNya["bunkeringInMt"] = $value1["bunkeringInMt"];
					if(count($value["COP"]) <= 2)
					{
						$dsNya["eopVoyNo"] = $value["EOP"]["D"]["voyNo"];
						$dsNya["eopPort"] = $value["EOP"]["D"]["eopPort"];
						$dsNya["eopDate"] = $value["EOP"]["D"]["eopDate"];
						$dsNya["eopTime"] = $value["EOP"]["D"]["eopTime"];
						$dsNya["eopDo"] = $value["EOP"]["D"]["eopDo"];
						$dsNya["eopFw"] = $value["EOP"]["D"]["eopFw"];
						$dsNya["eopDist"] = $value["EOP"]["D"]["eopDist"];
						$dsNya["copDOCons"] = $dataOut[$voyNo."/".$voyYear]["COP"]["D"]["copDo"];
					}else{
						if(count($value["COP"]["D"]) > 0 )
						{
							$dsNya["eopVoyNo"] = $value["EOP"]["D"]["voyNo"];
							$dsNya["eopPort"] = $value["EOP"]["D"]["eopPort"];
							$dsNya["eopDate"] = $value["EOP"]["D"]["eopDate"];
							$dsNya["eopTime"] = $value["EOP"]["D"]["eopTime"];
							$dsNya["eopDo"] = $value["EOP"]["D"]["eopDo"];
							$dsNya["eopFw"] = $value["EOP"]["D"]["eopFw"];
							$dsNya["eopDist"] = $value["EOP"]["D"]["eopDist"];
							$dsNya["copDOCons"] = $dataOut[$voyNo."/".$voyYear]["COP"]["D"]["copDo"];
						}else{
							$dsNya["eopVoyNo"] = $value["EOP"]["D".$noTypeVoy]["voyNo"];
							$dsNya["eopPort"] = $value["EOP"]["D".$noTypeVoy]["eopPort"];
							$dsNya["eopDate"] = $value["EOP"]["D".$noTypeVoy]["eopDate"];
							$dsNya["eopTime"] = $value["EOP"]["D".$noTypeVoy]["eopTime"];
							$dsNya["eopDo"] = $value["EOP"]["D".$noTypeVoy]["eopDo"];
							$dsNya["eopFw"] = $value["EOP"]["D".$noTypeVoy]["eopFw"];
							$dsNya["eopDist"] = $value["EOP"]["D".$noTypeVoy]["eopDist"];
							$dsNya["copDOCons"] = $dataOut[$voyNo."/".$voyYear]["COP"]["D".$noTypeVoy]["copDo"];
						}
					}
				}else{
					$dsNya["voyNo"] = $value1["voyNo"];
					$dsNya["copPort"] = $value1["copPort"];
					$dsNya["copDate"] = $value1["copDate"];
					$dsNya["copTime"] = $value1["copTime"];
					$dsNya["copDo"] = $value1["copDo"];
					$dsNya["copFw"] = $value1["copFw"];
					$dsNya["cargo"] = $value1["copCargo"];
					$dsNya["bunkeringInMt"] = $value1["bunkeringInMt"];
					if($key1 == "D")
					{
						if(count($value["COP"]) <= 2)
						{
							if(array_key_exists("L",$value["COP"]))//jika ada key L
							{
								$dsNya["eopVoyNo"] = $dataOut[$voyNo."/".$voyYear]["EOP"]["L"]["voyNo"];
								$dsNya["eopPort"] = $dataOut[$voyNo."/".$voyYear]["EOP"]["L"]["eopPort"];
								$dsNya["eopDate"] = $dataOut[$voyNo."/".$voyYear]["EOP"]["L"]["eopDate"];
								$dsNya["eopTime"] = $dataOut[$voyNo."/".$voyYear]["EOP"]["L"]["eopTime"];
								$dsNya["eopDo"] = $dataOut[$voyNo."/".$voyYear]["EOP"]["L"]["eopDo"];
								$dsNya["eopFw"] = $dataOut[$voyNo."/".$voyYear]["EOP"]["L"]["eopFw"];
								$dsNya["eopDist"] = $dataOut[$voyNo."/".$voyYear]["EOP"]["L"]["eopDist"];
								$dsNya["copDOCons"] = $dataOut[$voyNo."/".$voyYear]["COP"]["L"]["copDo"];
							}else{
								$dsNya["eopVoyNo"] = $dataOut[$key]["EOP"]["D2"]["voyNo"];
								$dsNya["eopPort"] = $dataOut[$key]["EOP"]["D2"]["eopPort"];
								$dsNya["eopDate"] = $dataOut[$key]["EOP"]["D2"]["eopDate"];
								$dsNya["eopTime"] = $dataOut[$key]["EOP"]["D2"]["eopTime"];
								$dsNya["eopDo"] = $dataOut[$key]["EOP"]["D2"]["eopDo"];
								$dsNya["eopFw"] = $dataOut[$key]["EOP"]["D2"]["eopFw"];
								$dsNya["eopDist"] = $dataOut[$key]["EOP"]["D2"]["eopDist"];
								$dsNya["copDOCons"] = $dataOut[$key]["COP"]["D2"]["copDo"];
							}
						}
						else{
							$dsNya["eopVoyNo"] = $dataOut[$dV[0]."/".$dV[1]]["EOP"]["D".$noTypeVoy]["voyNo"];
							$dsNya["eopPort"] = $dataOut[$dV[0]."/".$dV[1]]["EOP"]["D".$noTypeVoy]["eopPort"];
							$dsNya["eopDate"] = $dataOut[$dV[0]."/".$dV[1]]["EOP"]["D".$noTypeVoy]["eopDate"];
							$dsNya["eopTime"] = $dataOut[$dV[0]."/".$dV[1]]["EOP"]["D".$noTypeVoy]["eopTime"];
							$dsNya["eopDo"] = $dataOut[$dV[0]."/".$dV[1]]["EOP"]["D".$noTypeVoy]["eopDo"];
							$dsNya["eopFw"] = $dataOut[$dV[0]."/".$dV[1]]["EOP"]["D".$noTypeVoy]["eopFw"];
							$dsNya["eopDist"] = $dataOut[$dV[0]."/".$dV[1]]["EOP"]["D".$noTypeVoy]["eopDist"];
							$dsNya["copDOCons"] = $dataOut[$dV[0]."/".$dV[1]]["COP"]["D".$noTypeVoy]["copDo"];
						}
					}else{
						if($noTypeVoy < count($value["COP"]))
						{
							$dsNya["eopVoyNo"] = $dataOut[$dV[0]."/".$dV[1]]["EOP"]["D".$noTypeVoy]["voyNo"];
							$dsNya["eopPort"] = $dataOut[$dV[0]."/".$dV[1]]["EOP"]["D".$noTypeVoy]["eopPort"];
							$dsNya["eopDate"] = $dataOut[$dV[0]."/".$dV[1]]["EOP"]["D".$noTypeVoy]["eopDate"];
							$dsNya["eopTime"] = $dataOut[$dV[0]."/".$dV[1]]["EOP"]["D".$noTypeVoy]["eopTime"];
							$dsNya["eopDo"] = $dataOut[$dV[0]."/".$dV[1]]["EOP"]["D".$noTypeVoy]["eopDo"];
							$dsNya["eopFw"] = $dataOut[$dV[0]."/".$dV[1]]["EOP"]["D".$noTypeVoy]["eopFw"];
							$dsNya["eopDist"] = $dataOut[$dV[0]."/".$dV[1]]["EOP"]["D".$noTypeVoy]["eopDist"];
							$dsNya["copDOCons"] = $dataOut[$dV[0]."/".$dV[1]]["COP"]["D".$noTypeVoy]["copDo"];
						}else{
							$dsNya["eopVoyNo"] = $dataOut[$voyNo."/".$voyYear]["EOP"]["L"]["voyNo"];
							$dsNya["eopPort"] = $dataOut[$voyNo."/".$voyYear]["EOP"]["L"]["eopPort"];
							$dsNya["eopDate"] = $dataOut[$voyNo."/".$voyYear]["EOP"]["L"]["eopDate"];
							$dsNya["eopTime"] = $dataOut[$voyNo."/".$voyYear]["EOP"]["L"]["eopTime"];
							$dsNya["eopDo"] = $dataOut[$voyNo."/".$voyYear]["EOP"]["L"]["eopDo"];
							$dsNya["eopFw"] = $dataOut[$voyNo."/".$voyYear]["EOP"]["L"]["eopFw"];
							$dsNya["eopDist"] = $dataOut[$voyNo."/".$voyYear]["EOP"]["L"]["eopDist"];
							$dsNya["copDOCons"] = $dataOut[$voyNo."/".$voyYear]["COP"]["L"]["copDo"];
						}
					}
				}
				$dataPrint[] = $dsNya;
				$noTypeVoy ++;
			}
		}
		$no = 1;
		$sumEopDist = 0;
		$sumStreamTime = 0;
		$sumAvrgSpeed = 0;
		$sumDoPerf = 0;
		$sumFwPerf = 0;
		$sumDoDay = 0;
		$sumDoCons = 0;
		//echo "<pre>";print_r($dataPrint);exit;
		for ($lan=0; $lan < count($dataPrint); $lan++)
		{
			$bgColor = "";
			$tdColor = "";
			$bungkering = "";
			if($lan < $displayData || $typeNya == "export" || $typeNya == "search")
			{
				if($dataPrint[$lan]["eopDate"] == "")
				{
					$totalStream = "";
					$avrgSpeed = "";
					$doNya = "";
					$fwNya = "";
					$doDay = "";
					$fwDay = "";
					$doCons = "";
				}else{
					$dateAwal = date_create($dataPrint[$lan]["eopDate"]." ".$dataPrint[$lan]["eopTime"].":00");
					$dateAkhir = date_create($dataPrint[$lan]["copDate"]." ".$dataPrint[$lan]["copTime"].":00");
					
					$totalStream = "";
					$avrgSpeed = "";					
					$doNya = "";
					$fwNya = "";
					$doDay = "";
					$fwDay = "";
					$doCons = "";
					$bungkering = $dataPrint[$lan]["bunkeringInMt"];

					$diff  = date_diff($dateAkhir,$dateAwal);
					if($diff->d > 0)
					{
						$totalStream = ($diff->d * 24);
					}
					// echo "<pre>";print_r($diff);exit;
					$totalStream = ROUND(($totalStream + $diff->h)+($diff->i / 60),2);
					$avrgSpeed = ROUND(($dataPrint[$lan]["eopDist"] / $totalStream),2);
					// $doNya = ($dataPrint[$lan]["copDo"] - $dataPrint[$lan]["eopDo"]);
					$doNya = ROUND(($dataPrint[$lan]["copDo"] - $dataPrint[$lan]["eopDo"]),3);
					$fwNya = ($dataPrint[$lan]["copFw"] - $dataPrint[$lan]["eopFw"]);
					$doDay = ROUND(($doNya/$totalStream) * 24,2);
					$fwDay = ROUND(($fwNya/$totalStream) * 24,2);
					// $doCons = (($dataPrint[$lan]["eopDo"] + $bungkering) - $dataPrint[$lan]["copDOCons"]);
					$doCons = (($dataPrint[$lan]["eopDo"] + $bungkering) - $dataPrint[$lan+1]["copDo"]);
				}
				if($dataPage > 1)
				{
					// $no = ($dataPage *10)+($lan +1) ;
					$no = (($displayData * $dataPage)-$displayData )+($lan +1) ;
				}
				if($lan%2 !== 0)
				{
					$bgColor = "style=\"background-color:#e0e3e4;\"";
				}
				if($avrgSpeed < 12)
				{
					$tdColor = "style='color:red;'";
				}
				$dataTR .= "
							<tr ".$bgColor.">
								<td align=\"center\">".$no."</td>
								<td align=\"center\">".$dataPrint[$lan]["voyNo"]."</td>
								<td style=\"padding-left:5px;\">".$dataPrint[$lan]["copPort"]."</td>
								<td align=\"center\"> ".$this->dateWithMonth($dataPrint[$lan]["copDate"])."</td>
								<td align=\"center\"> ".$dataPrint[$lan]["copTime"]."</td>
								<td align=\"center\"> ".$dataPrint[$lan]["copDo"]."</td>
								<td align=\"center\"> ".$dataPrint[$lan]["copFw"]."</td>
								<td style=\"padding-left:5px;\">".$dataPrint[$lan]["cargo"]."</td>
								<td style=\"padding-left:5px;\">".$dataPrint[$lan]["eopPort"]."</td>
								<td align=\"center\">".$this->dateWithMonth($dataPrint[$lan]["eopDate"])."</td>
								<td align=\"center\">".$dataPrint[$lan]["eopTime"]."</td>
								<td align=\"center\">".$dataPrint[$lan]["eopDo"]."</td>
								<td align=\"center\">".$dataPrint[$lan]["eopFw"]."</td>
								<td align=\"center\">".$dataPrint[$lan]["eopDist"]."</td>
								<td align=\"center\">".$totalStream."</td>
								<td align=\"center\" ".$tdColor.">".$avrgSpeed."</td>
								<td align=\"center\">".$doNya."</td>
								<td align=\"center\">".$fwNya."</td>
								<td align=\"center\">".$doDay."</td>
								<td align=\"center\">".$fwDay."</td>
								<td style=\"padding-left:5px;\"> M/E & A/E</td>
								<td align=\"center\">".$bungkering."</td>
								<td align=\"center\">".$doCons."</td>
							</tr>
						";
				$sumEopDist = $sumEopDist + $dataPrint[$lan]["eopDist"];
				$sumStreamTime = $sumStreamTime + $totalStream;
				$sumAvrgSpeed = $sumAvrgSpeed + $avrgSpeed;
				$sumDoPerf = $sumDoPerf + $doNya;
				$sumFwPerf = $sumFwPerf + $fwNya;
				$sumDoDay = $sumDoDay + $doDay;
				$sumDoCons = $sumDoCons + $doCons;
				$no ++;
			}
		}

		$dataTR .= "
					<tr>
						<td colspan=\"13\" align=\"right\">Total : &nbsp</td>
						<td style=\"text-align:right;font-size:12px;\"> <b>".ROUND($sumEopDist,3)."</b>&nbsp</td>
						<td style=\"text-align:right;font-size:12px;\"> <b>".ROUND($sumStreamTime,3)."</b>&nbsp</td>
						<td style=\"text-align:right;font-size:12px;\"> <b>".ROUND($sumAvrgSpeed,3)."</b>&nbsp</td>
						<td style=\"text-align:right;font-size:12px;\"> <b>".ROUND($sumDoPerf,3)."</b>&nbsp</td>
						<td style=\"text-align:right;font-size:12px;\"> <b>".ROUND($sumFwPerf,3)."</b>&nbsp</td>
						<td style=\"text-align:right;font-size:12px;\"> <b>".ROUND($sumDoDay,3)."</b>&nbsp</td>
						<td></td>
						<td></td>
						<td></td>
						<td style=\"text-align:right;font-size:12px;\"> <b>".ROUND($sumDoCons,3)."</b>&nbsp</td>
					</tr>
					";

		return $dataTR;
	}

	function sumDataConsSpeed()
	{
		$yNow = substr(date('Y'),-2);
		// print_r(substr($yNow,-2));exit;
		$sqlCount = "SELECT COUNT(id) as ID FROM tbluploadoil WHERE v_year = '".$yNow."' AND deletests = '0'";
		$query = $this->koneksi->mysqlquery($sqlCount);
		$row = $this->koneksi->mysqlFetch($query);

		return $row["ID"];
	}

	function getExportBunkerConsSpeed($path = "")
	{
		ob_start();
		$whereNya = "";
		$searchVoyNo = $_POST['actionVoyNo'];
		$dataTR = $this->getDataSummaryBUnkerConsSpeed($searchVoyNo,"export");
		header("Content-Type: application/vnd.ms-excel");
		// header('Content-type: application/ms-excel');
		echo "<table width=\"100%\">";
			echo "<tr>
					<td colspan=\"10\" rowspan=\"3\" align=\"center\" style=\"font-size: 22px;font-weight: bold;\">
							<img width=385 height=50 src=\"".$_SERVER["HTTP_REFERER"]."/pdf/picture/lgAdy.JPG\" style=\"float:right;\" />
					</td>
				</tr><tr></tr><tr></tr>";
			echo "<tr>
					<td colspan=\"10\" align=\"left\">
						<label style=\"font-size: 28pt;font-weight: bold;\">SUMMARY BUNKER CONS AND SPEED</label>
					</td>
				</tr>";
			echo "<tr>
					<td colspan=\"10\" align=\"left\">
						<label id=\"lblVesselName\">ANDHIKA VIDYANATA</label>
					</td>
				</tr>";
		echo "</table>";

		echo "<table border=\"1\">";
		echo "	<tr style=\"font-size: 12px;background-color: #125362;color: #FFFFFF;height:30px;\">
					<td style=\"background-color: #FFF;\"></td>
					<td align=\"center\" colspan=\"6\">Departure (COP)</td>
					<td style=\"background-color: #FFF;\"></td>
					<td align=\"center\" colspan=\"6\">Arrival (EOP)</td>
					<td align=\"center\" colspan=\"7\">Performance</td>
					<td style=\"background-color: #FFF;\" colspan=\"2\"></td>
				</tr>
			";
		echo "
				<tr style=\"font-size: 12px;background-color: #125362;color: #FFFFFF;height:30px;\">
					<td style=\"width: 25px;\" align=\"center\">NO</td>
					<td style=\"width: 80px;\" align=\"center\">Voyage No</td>
					<td style=\"width: 120px;\" align=\"center\">Port</td>
					<td style=\"width: 70px;\" align=\"center\">Date</td>
					<td style=\"width: 50px;\" align=\"center\">Time</td>
					<td style=\"width: 50px;\" align=\"center\">D.O</td>
					<td style=\"width: 50px;\" width=\"50\" align=\"center\">FW</td>
					<td style=\"width: 120px;\" align=\"center\">CARGO (in LT)</td>
					<td style=\"width: 120px;\" align=\"center\">Port</td>
					<td style=\"width: 70px;\" align=\"center\">Date</td>
					<td style=\"width: 50px;\" align=\"center\">Time</td>
					<td style=\"width: 50px;\" align=\"center\">D.O</td>
					<td style=\"width: 50px;\" align=\"center\">FW</td>
					<td style=\"width: 50px;\" align=\"center\">Dist</td>
					<td style=\"width: 50px;\" align=\"center\">Streaming Time in Hours</td>
					<td style=\"width: 50px;\" align=\"center\">Av Speed</td>
					<td style=\"width: 50px;\" align=\"center\">D.O</td>
					<td style=\"width: 50px;\" align=\"center\">FW</td>
					<td style=\"width: 100px;\" align=\"center\">DO/Day</td>
					<td style=\"width: 50px;\" align=\"center\">FW</td>
					<td style=\"width: 180px;\" align=\"center\">Remark</td>
					<td style=\"width: 60px;\" align=\"center\">Bunkering in MT</td>
					<td style=\"width: 60px;\" align=\"center\">D.O Cons in Port</td>
				</tr>
			";
		echo $dataTR;
		echo "</table>";

		header("Content-disposition: attachment; filename=exportBunkerConsSpeed.xls");
		ob_end_flush();
	}

	function getDataSummaryCargoTrace($searchVoyNo = "",$typeNya = "")
	{
		error_reporting(1);
		$dataL = array();
		$dataD = array();
		$dataOut = array();
		$dataPrint = array();
		$typeCargo = "";
		$whereNya = "";
		$dP = array();
		$yearNow = date("y");

		if($searchVoyNo != "")
		{
			$dV = explode("/", $searchVoyNo);
			if(count($dV) <= 1)
			{
				$whereNya .= " AND A.voyage_no like '%".$searchVoyNo."%' ";
			}else{
				$voyNo = $dV[0];
				$voyType = $dV[1];
				$voyYear = $dV[2];
				$whereNya .= " AND A.voyage_no like '%".trim($voyNo)."%' AND A.voyage_no like '%".trim($voyYear)."%' ";
			}
		}else{
			$whereNya .= " AND A.v_year = '".$yearNow."' ";
		}

		$sql = "SELECT A.voyage_no,A.inport,A.previous_port,B.bol_a_gradeName,B.bol_a_bbls,B.bol_a_lt,B.sfal_a_bbls,B.sfal_a_lt,B.bol_b_gradeName,B.bol_b_bbls,B.bol_b_lt,B.sfal_b_bbls,B.sfal_b_lt,B.bol_c_gradeName,B.bol_c_bbls,B.bol_c_lt,B.sfal_c_bbls,B.sfal_c_lt,B.bol_d_gradeName,B.bol_d_bbls,B.bol_d_lt,B.sfal_d_bbls,B.sfal_d_lt,C.sfbd_a_bbls,C.sfbd_a_lt,C.sfbd_b_bbls,C.sfbd_b_lt,C.sfbd_c_bbls,C.sfbd_c_lt,C.sfbd_d_bbls,C.sfbd_d_lt,C.sar_a_gradeName,C.sar_a_bbls,C.sar_a_lt,C.sar_b_gradeName,C.sar_b_bbls,C.sar_b_lt,C.sar_c_gradeName,C.sar_c_bbls,C.sar_c_lt,C.sar_d_gradeName,C.sar_d_bbls,C.sar_d_lt,C.sfad_a_bbls,C.sfad_a_lt,C.sfad_b_bbls,C.sfad_b_lt,C.sfad_c_bbls,C.sfad_c_lt,C.sfad_d_bbls,C.sfad_d_lt,C.newBol_a_bbls,C.newBol_a_lt,C.newBol_b_bbls,C.newBol_b_lt,C.newBol_c_bbls,C.newBol_c_lt,C.newBol_d_bbls,C.newBol_d_lt
				FROM tbluploadoil A
				LEFT JOIN tbluploadoil_atloading B ON A.id = B.id_uploadoil
				LEFT JOIN tbluploadoil_atdischarging C ON A.id = C.id_uploadoil
				WHERE A.deletests = '0' ".$whereNya."
				ORDER BY A.v_year,A.v_no ASC,(A.v_type = 'L') DESC";
		$query = $this->koneksi->mysqlQuery($sql);
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$dV = explode("/", $row["voyage_no"]);
			$voyNo = $dV[0];
			$voyType = $dV[1];
			$voyYear = $dV[2];

			if($voyType == "L")
			{
				$dataL["voyage_no"] = $row["voyage_no"];
				$dataL["bol_a_gradeName"] = $row["bol_a_gradeName"];
				$dataL["bol_a_bbls"] = $row["bol_a_bbls"];
				$dataL["bol_a_lt"] = $row["bol_a_lt"];
				$dataL["sfal_a_bbls"] = $row["sfal_a_bbls"];
				$dataL["sfal_a_lt"] = $row["sfal_a_lt"];
				$dataL["sfad_a_bbls"] = $row["sfad_a_bbls"];
				$dataL["sfad_a_lt"] = $row["sfad_a_lt"];
				$dataL["bol_b_gradeName"] = $row["bol_b_gradeName"];
				$dataL["bol_b_bbls"] = $row["bol_b_bbls"];
				$dataL["bol_b_lt"] = $row["bol_b_lt"];
				$dataL["sfal_b_bbls"] = $row["sfal_b_bbls"];
				$dataL["sfal_b_lt"] = $row["sfal_b_lt"];
				$dataL["sfad_b_bbls"] = $row["sfad_b_bbls"];
				$dataL["sfad_b_lt"] = $row["sfad_b_lt"];
				$dataL["bol_c_gradeName"] = $row["bol_c_gradeName"];
				$dataL["bol_c_bbls"] = $row["bol_c_bbls"];
				$dataL["bol_c_lt"] = $row["bol_c_lt"];
				$dataL["sfal_c_bbls"] = $row["sfal_c_bbls"];
				$dataL["sfal_c_lt"] = $row["sfal_c_lt"];
				$dataL["sfad_c_bbls"] = $row["sfad_c_bbls"];
				$dataL["sfad_c_lt"] = $row["sfad_c_lt"];
				$dataL["bol_d_gradeName"] = $row["bol_d_gradeName"];
				$dataL["bol_d_bbls"] = $row["bol_d_bbls"];
				$dataL["bol_d_lt"] = $row["bol_d_lt"];
				$dataL["sfal_d_bbls"] = $row["sfal_d_bbls"];
				$dataL["sfal_d_lt"] = $row["sfal_d_lt"];
				$dataL["sfad_d_bbls"] = $row["sfad_d_bbls"];
				$dataL["sfad_d_lt"] = $row["sfad_d_lt"];
				$dataOut[$voyNo."/".$voyYear][$voyType] = $dataL;
			}else{
				$dataD["voyage_no"] = $row["voyage_no"];
				$dataD["inport"] = $row["inport"];
				$dataD["previous_port"] = $row["previous_port"];
				$dataD["sar_a_gradeName"] = $row["sar_a_gradeName"];
				$dataD["sfbd_a_bbls"] = $row["sfbd_a_bbls"];
				$dataD["sfbd_a_lt"] = $row["sfbd_a_lt"];
				$dataD["sar_a_bbls"] = $row["sar_a_bbls"];
				$dataD["sar_a_lt"] = $row["sar_a_lt"];
				$dataD["sfad_a_bbls"] = $row["sfad_a_bbls"];
				$dataD["sfad_a_lt"] = $row["sfad_a_lt"];
				$dataD["newBol_a_bbls"] = $row["newBol_a_bbls"];
				$dataD["newBol_a_lt"] = $row["newBol_a_lt"];
				$dataD["sar_b_gradeName"] = $row["sar_b_gradeName"];
				$dataD["sfbd_b_bbls"] = $row["sfbd_b_bbls"];
				$dataD["sfbd_b_lt"] = $row["sfbd_b_lt"];
				$dataD["sar_b_bbls"] = $row["sar_b_bbls"];
				$dataD["sar_b_lt"] = $row["sar_b_lt"];
				$dataD["sfad_b_bbls"] = $row["sfad_b_bbls"];
				$dataD["sfad_b_lt"] = $row["sfad_b_lt"];
				$dataD["newBol_b_bbls"] = $row["newBol_b_bbls"];
				$dataD["newBol_b_lt"] = $row["newBol_b_lt"];
				$dataD["sar_c_gradeName"] = $row["sar_c_gradeName"];
				$dataD["sfbd_c_bbls"] = $row["sfbd_c_bbls"];
				$dataD["sfbd_c_lt"] = $row["sfbd_c_lt"];
				$dataD["sar_c_bbls"] = $row["sar_c_bbls"];
				$dataD["sar_c_lt"] = $row["sar_c_lt"];
				$dataD["sfad_c_bbls"] = $row["sfad_c_bbls"];
				$dataD["sfad_c_lt"] = $row["sfad_c_lt"];
				$dataD["newBol_c_bbls"] = $row["newBol_c_bbls"];
				$dataD["newBol_c_lt"] = $row["newBol_c_lt"];
				$dataD["sar_d_gradeName"] = $row["sar_d_gradeName"];
				$dataD["sfbd_d_bbls"] = $row["sfbd_d_bbls"];
				$dataD["sfbd_d_lt"] = $row["sfbd_d_lt"];
				$dataD["sar_d_bbls"] = $row["sar_d_bbls"];
				$dataD["sar_d_lt"] = $row["sar_d_lt"];
				$dataD["sfad_d_bbls"] = $row["sfad_d_bbls"];
				$dataD["sfad_d_lt"] = $row["sfad_d_lt"];
				$dataD["newBol_d_bbls"] = $row["newBol_d_bbls"];
				$dataD["newBol_d_lt"] = $row["newBol_d_lt"];
				$dataOut[$voyNo."/".$voyYear][$voyType] = $dataD;
			}
		}
		foreach ($dataOut as $key => $value)
		{
			if(count($value) <= 2)
			{
				for ($asl=1; $asl <= 4; $asl++)
				{
					if($asl == 1)
					{
						$typeCargo = "a";
					}
					else if($asl == 2)
					{
						$typeCargo = "b";
					}
					else if($asl == 3)
					{
						$typeCargo = "c";
					}
					else if($asl == 4)
					{
						$typeCargo = "d";
					}
					if($value["D"]["sar_".$typeCargo."_gradeName"] != "")
					{
						$stTrfsLoss_1 = "";
						$stTrfsLoss_2 = "";
						$valBL_1 = str_replace(',','',$value["L"]["bol_".$typeCargo."_bbls"]);
						$valBL_2 = str_replace(',','',$value["L"]["bol_".$typeCargo."_lt"]);
						if($valBL_1 == ""){$valBL_1 = 0;}
						if($valBL_2 == ""){$valBL_2 = 0;}

						$valAfterLoad_1 = str_replace(',','',$value["L"]["sfal_".$typeCargo."_bbls"]);
						$valAfterLoad_2 = str_replace(',','',$value["L"]["sfal_".$typeCargo."_lt"]);
						if($valAfterLoad_1 == ""){$valAfterLoad_1 = 0;}
						if($valAfterLoad_2 == ""){$valAfterLoad_2 = 0;}

						$valSFBefore_1 = str_replace(',','',$value["D"]["sfbd_".$typeCargo."_bbls"]);
						$valSFBefore_2 = str_replace(',','',$value["D"]["sfbd_".$typeCargo."_lt"]);
						if($valSFBefore_1 == ""){$valSFBefore_1 = 0;}
						if($valSFBefore_2 == ""){$valSFBefore_2 = 0;}

						$valActual_1 = str_replace(',','',$value["D"]["sar_".$typeCargo."_bbls"]);
						$valActual_2 = str_replace(',','',$value["D"]["sar_".$typeCargo."_lt"]);

						if($valBL_1 > 0)
						{
							$valDiffPercent2_1 = ROUND((($valSFBefore_1 - $valAfterLoad_1)/$valBL_1)*100,3);
						}else{
							$valDiffPercent2_1 = 0;
						}

						if($valBL_2 > 0)
						{
							$valDiffPercent2_2 = ROUND((($valSFBefore_2 - $valAfterLoad_2)/$valBL_2)*100,3);
						}else{
							$valDiffPercent2_2 = 0;
						}
						
						// $valSelisih_1 = str_replace(',','',$value["D"]["sfad_".$typeCargo."_bbls"]);
						// $valSelisih_2 = str_replace(',','',$value["D"]["sfad_".$typeCargo."_lt"]);
						$dP["voyNo"] = $value["D"]["voyage_no"]." (".$value["D"]["inport"]." - ".$value["D"]["previous_port"].")";
						$dP["cargoName"] = $value["D"]["sar_".$typeCargo."_gradeName"];
						$dP["bL_1"] = $valBL_1;
						$dP["bL_2"] = $valBL_2;
						$dP["sfAfterLoading_1"] = $valAfterLoad_1;
						$dP["sfAfterLoading_2"] = $valAfterLoad_2;

						if($valBL_1 > 0)
						{
							$dP["vefSfAL_1"] = ROUND($valAfterLoad_1 / $valBL_1,5);
						}else{
							$dP["vefSfAL_1"] = 0;
						}

						if($valBL_2 > 0)
						{
							$dP["vefSfAL_2"] = ROUND($valAfterLoad_2 / $valBL_2,5);
						}else{
							$dP["vefSfAL_2"] = 0;
						}
						$dP["diff1_1"] = ROUND($valAfterLoad_1 - $valBL_1,3);
						$dP["diff1_2"] = ROUND($valAfterLoad_2 - $valBL_2,3);
						if($valBL_1 > 0)
						{
							$dP["diffPercent_1"] = ROUND((($valAfterLoad_1 - $valBL_1)/$valBL_1)*100,3);
						}else{
							$dP["diffPercent_1"] = 0;
						}

						if($valBL_2 > 0)
						{
							$dP["diffPercent_2"] = ROUND((($valAfterLoad_2 - $valBL_2)/$valBL_2)*100,3);
						}else{
							$dP["diffPercent_2"] = 0;
						}
						
						$dP["sfBeforeDisch_1"] = ROUND($valSFBefore_1,3);
						$dP["sfBeforeDisch_2"] = ROUND($valSFBefore_2,3);
						$dP["diff2_1"] = ROUND($valSFBefore_1 - $valAfterLoad_1,3);
						$dP["diff2_2"] = ROUND($valSFBefore_2 - $valAfterLoad_2,3);
						$dP["diffPercent2_1"] = $valDiffPercent2_1;
						$dP["diffPercent2_2"] = $valDiffPercent2_2;
						if($valDiffPercent2_1 < -0.071)
						{
							$stTrfsLoss_1 = "Yes";
						}else{
							$stTrfsLoss_1 = "No";
						}
						if($valDiffPercent2_2 < -0.07)
						{
							$stTrfsLoss_2 = "Yes";
						}else{
							$stTrfsLoss_2 = "No";
						}
						$dP["TrsnprtLoss_1"] = $stTrfsLoss_1;
						$dP["TrsnprtLoss_2"] = $stTrfsLoss_2;
						$dP["barrel"] = "Barrel 60";
						$dP["lt"] = "LT";
						$dP["actualRec_1"] = $valActual_1;
						$dP["actualRec_2"] = $valActual_2;
						$dP["diff3_1"] = ROUND($valActual_1 - $valSFBefore_1,3);
						$dP["diff3_2"] = ROUND($valActual_2 - $valSFBefore_2,3);
						$dP["slshFnl_1"] = "";
						$dP["slshFnl_2"] = "";
						$dataPrint[] = $dP;
					}
				}
			}else{				
				for ($lan = 1; $lan < count($value); $lan++)
				{
					$typeKey = "";
					$dP = array();
					if($lan == 1)
					{
						if(count($value["D"]) > 0 ){ $typeKey = "D";}
						if(count($value["D1"]) > 0 ){ $typeKey = "D1";}

						for ($tv=1; $tv <= 4; $tv++)
						{
							$typeIndex = "";
							if($tv == 1){$typeIndex = "a";}
							if($tv == 2){$typeIndex = "b";}
							if($tv == 3){$typeIndex = "c";}
							if($tv == 4){$typeIndex = "d";}

							if($value[$typeKey]["sar_".$typeIndex."_gradeName"] != "")
							{
								$stTrfsLoss_1 = "";
								$stTrfsLoss_2 = "";
								$valBL_1 = str_replace(',','',$value["L"]["bol_".$typeIndex."_bbls"]);
								$valBL_2 = str_replace(',','',$value["L"]["bol_".$typeIndex."_lt"]);
								if($valBL_1 == ""){$valBL_1 = 0;}
								if($valBL_2 == ""){$valBL_2 = 0;}

								$valAfterLoad_1 = str_replace(',','',$value["L"]["sfal_".$typeIndex."_bbls"]);
								$valAfterLoad_2 = str_replace(',','',$value["L"]["sfal_".$typeIndex."_lt"]);
								if($valAfterLoad_1 == ""){$valAfterLoad_1 = 0;}
								if($valAfterLoad_2 == ""){$valAfterLoad_2 = 0;}

								$valSFBefore_1 = str_replace(',','',$value[$typeKey]["sfbd_".$typeIndex."_bbls"]);
								$valSFBefore_2 = str_replace(',','',$value[$typeKey]["sfbd_".$typeIndex."_lt"]);
								$valActual_1 = str_replace(',','',$value[$typeKey]["sar_".$typeIndex."_bbls"]);
								$valActual_2 = str_replace(',','',$value[$typeKey]["sar_".$typeIndex."_lt"]);
								$valSFAD_1 = str_replace(',','',$value[$typeKey]["sfad_".$typeIndex."_bbls"]);
								$valSFAD_2 = str_replace(',','',$value[$typeKey]["sfad_".$typeIndex."_lt"]);

								if($valBL_1 > 0)
								{
									$valDiffPercent2_1 = ROUND((($valSFBefore_1 - $valAfterLoad_1)/$valBL_1)*100,3);
									$dP["vefSfAL_1"] = ROUND($valAfterLoad_1 / $valBL_1,3);
									$dP["diffPercent_1"] = ROUND((($valAfterLoad_1 - $valBL_1)/$valBL_1)*100,3);
								}else{
									$valDiffPercent2_1 = 0;
									$dP["vefSfAL_1"] = 0;
									$dP["diffPercent_1"] = 0;
								}

								if($valBL_2 > 0)
								{
									$valDiffPercent2_2 = ROUND((($valSFBefore_2 - $valAfterLoad_2)/$valBL_2)*100,3);
									$dP["vefSfAL_2"] = ROUND($valAfterLoad_2 / $valBL_2,3);
									$dP["diffPercent_2"] = ROUND((($valAfterLoad_2 - $valBL_2)/$valBL_2)*100,3);
								}else{
									$valDiffPercent2_2 = 0;
									$dP["vefSfAL_2"] = 0;
									$dP["diffPercent_2"] = 0;
								}
								
								$dP["voyNo"] = $value[$typeKey]["voyage_no"]." (".$value[$typeKey]["inport"]." - ".$value[$typeKey]["previous_port"].")";
								$dP["cargoName"] = $value[$typeKey]["sar_".$typeIndex."_gradeName"];
								$dP["bL_1"] = $valBL_1;
								$dP["bL_2"] = $valBL_2;
								$dP["sfAfterLoading_1"] = $valAfterLoad_1;
								$dP["sfAfterLoading_2"] = $valAfterLoad_2;
								$dP["diff1_1"] = ROUND($valAfterLoad_1 - $valBL_1,3);
								$dP["diff1_2"] = ROUND($valAfterLoad_2 - $valBL_2,3);
								$dP["sfBeforeDisch_1"] = ROUND($valSFBefore_1,3);
								$dP["sfBeforeDisch_2"] = ROUND($valSFBefore_2,3);
								$dP["diff2_1"] = ROUND($valSFBefore_1 - $valAfterLoad_1,3);
								$dP["diff2_2"] = ROUND($valSFBefore_2 - $valAfterLoad_2,3);
								$dP["diffPercent2_1"] = $valDiffPercent2_1;
								$dP["diffPercent2_2"] = $valDiffPercent2_2;
								if($valDiffPercent2_1 < -0.071)
								{
									$stTrfsLoss_1 = "Yes";
								}else{
									$stTrfsLoss_1 = "No";
								}
								if($valDiffPercent2_2 < -0.07)
								{
									$stTrfsLoss_2 = "Yes";
								}else{
									$stTrfsLoss_2 = "No";
								}
								$dP["TrsnprtLoss_1"] = $stTrfsLoss_1;
								$dP["TrsnprtLoss_2"] = $stTrfsLoss_2;
								$dP["barrel"] = "Barrel 60";
								$dP["lt"] = "LT";
								$dP["actualRec_1"] = $valActual_1;
								$dP["actualRec_2"] = $valActual_2;
								$dP["diff3_1"] = ROUND($valActual_1 - $valSFBefore_1,3);
								$dP["diff3_2"] = ROUND($valActual_2 - $valSFBefore_2,3);
								$dP["slshFnl_1"] = ROUND($valSFBefore_1 - $valSFAD_1,3);
								$dP["slshFnl_2"] = ROUND($valSFBefore_2 - $valSFAD_2,3);
								$dataPrint[] = $dP;
							}							
						}
					}else{
						$typeKeyBefore = "";
						if($lan <= 2)
						{
							if(count($value["D"]) > 0 ){ $typeKeyBefore = "D";}
							if(count($value["D1"]) > 0 ){ $typeKeyBefore = "D1";}
						}else{
							$typeKeyBefore = "D".($lan -1);
						}
						$typeKey = "D".$lan;
						for ($tv2=1; $tv2 <= 4; $tv2++)
						{
							$typeIndex = "";
							if($tv2 == 1){$typeIndex = "a";}
							if($tv2 == 2){$typeIndex = "b";}
							if($tv2 == 3){$typeIndex = "c";}
							if($tv2 == 4){$typeIndex = "d";}

							if($value[$typeKey]["sar_".$typeIndex."_gradeName"] != "")
							{
								$stTrfsLoss_1 = "";
								$stTrfsLoss_2 = "";
								$valBL_1 = str_replace(',','',$value[$typeKeyBefore]["newBol_".$typeIndex."_bbls"]);
								$valBL_2 = str_replace(',','',$value[$typeKeyBefore]["newBol_".$typeIndex."_lt"]);
								if($valBL_1 == ""){$valBL_1 = 0;}
								if($valBL_2 == ""){$valBL_2 = 0;}
								$valSFADBefore_1 = str_replace(',','',$value[$typeKeyBefore]["sfad_".$typeIndex."_bbls"]);
								$valSFADBefore_2 = str_replace(',','',$value[$typeKeyBefore]["sfad_".$typeIndex."_lt"]);
								$valSFBDBefore_1 = str_replace(',','',$value[$typeKeyBefore]["sfbd_".$typeIndex."_bbls"]);
								$valSFBDBefore_2 = str_replace(',','',$value[$typeKeyBefore]["sfbd_".$typeIndex."_lt"]);
								$valSFBefore_1 = str_replace(',','',$value[$typeKeyBefore]["sfbd_".$typeIndex."_bbls"]);
								$valSFBefore_2 = str_replace(',','',$value[$typeKeyBefore]["sfbd_".$typeIndex."_lt"]);
								$valSF_1 = str_replace(',','',$value[$typeKey]["sfbd_".$typeIndex."_bbls"]);
								$valSF_2 = str_replace(',','',$value[$typeKey]["sfbd_".$typeIndex."_lt"]);
								$valActual_1 = str_replace(',','',$value[$typeKey]["sar_".$typeIndex."_bbls"]);
								$valActual_2 = str_replace(',','',$value[$typeKey]["sar_".$typeIndex."_lt"]);
								$valSlshFnsBefore_1= ROUND($valSFBefore_1 - $valSFADBefore_1,3);
								$valSlshFnsBefore_2= ROUND($valSFBefore_2 - $valSFADBefore_2,3);
								$valAfterLoad_1 = ROUND($valSFBDBefore_1 - $valSlshFnsBefore_1,3);
								$valAfterLoad_2 = ROUND($valSFBDBefore_2 - $valSlshFnsBefore_2,3);
								if($valBL_1 > 0)
								{
									$valDiffPercent2_1 = ROUND((($valSF_1 - $valAfterLoad_1)/$valBL_1)*100,3);
								}else{
									$valDiffPercent2_1 = 0;
								}
								if($valBL_1 > 0)
								{
									$valDiffPercent2_2 = ROUND((($valSF_2 - $valAfterLoad_2)/$valBL_2)*100,3);
								}else{
									$valDiffPercent2_2 = 0;
								}

								$dP["voyNo"] = $value[$typeKey]["voyage_no"]." (".$value[$typeKey]["inport"]." - ".$value[$typeKey]["previous_port"].")";
								$dP["cargoName"] = $value[$typeKey]["sar_".$typeIndex."_gradeName"];
								$dP["bL_1"] = $valBL_1;
								$dP["bL_2"] = $valBL_2;
								$dP["sfAfterLoading_1"] = $valAfterLoad_1;
								$dP["sfAfterLoading_2"] = $valAfterLoad_2;
								// $dP["vefSfAL_1"] = $valAfterLoad_1 / $valBL_1;
								if($valBL_1 > 0)
								{
									$dP["vefSfAL_1"] = ROUND($valAfterLoad_1 / $valBL_1,5);
									$dP["diffPercent_1"] = ROUND((($valAfterLoad_1 - $valBL_1)/$valBL_1)*100,3);
								}else{
									$dP["vefSfAL_1"] = 0;
									$dP["diffPercent_1"] = 0;
								}
								if($valBL_2 > 0)
								{
									$dP["vefSfAL_2"] = ROUND($valAfterLoad_2 / $valBL_2,5);
									$dP["diffPercent_2"] = ROUND((($valAfterLoad_2 - $valBL_2)/$valBL_2)*100,3);
								}else{
									$dP["vefSfAL_2"] = 0;
									$dP["diffPercent_2"] = 0;
								}
								$dP["diff1_1"] = ROUND($valAfterLoad_1 - $valBL_1,3);
								$dP["diff1_2"] = ROUND($valAfterLoad_2 - $valBL_2,3);

								$dP["sfBeforeDisch_1"] = ROUND($valSF_1,3);
								$dP["sfBeforeDisch_2"] = ROUND($valSF_2,3);
								$dP["diff2_1"] = ROUND($valSF_1 - $valAfterLoad_1,3);
								$dP["diff2_2"] = ROUND($valSF_2 - $valAfterLoad_2,3);
								$dP["diffPercent2_1"] = $valDiffPercent2_1;
								$dP["diffPercent2_2"] = $valDiffPercent2_2;
								if($valDiffPercent2_1 < -0.071)
								{
									$stTrfsLoss_1 = "Yes";
								}else{
									$stTrfsLoss_1 = "No";
								}
								if($valDiffPercent2_2 < -0.07)
								{
									$stTrfsLoss_2 = "Yes";
								}else{
									$stTrfsLoss_2 = "No";
								}
								$dP["TrsnprtLoss_1"] = $stTrfsLoss_1;
								$dP["TrsnprtLoss_2"] = $stTrfsLoss_2;
								$dP["barrel"] = "Barrel 60";
								$dP["lt"] = "LT";
								$dP["actualRec_1"] = $valActual_1;
								$dP["actualRec_2"] = $valActual_2;
								$dP["diff3_1"] = ROUND($valActual_1 - $valSF_1,3);
								$dP["diff3_2"] = ROUND($valActual_2 - $valSF_2,3);
								if(($lan +1) < count($value))
								{
									$dP["slshFnl_1"] = ROUND($valSF_1 - $valSFAD_1,3);
									$dP["slshFnl_2"] = ROUND($valSF_2 - $valSFAD_2,3);
								}else{
									$dP["slshFnl_1"] = "";
									$dP["slshFnl_2"] = "";
								}
								$dataPrint[] = $dP;
							}
						}
					}
				}
			}
		}
		$dataTR = "";
		$colorTeks = "";
		$colorTeksLT = "";
		for ($asla = 0; $asla < count($dataPrint); $asla++)
		{
			$valDPrcnt1_1 = $dataPrint[$asla]["diffPercent_1"];
			$valDPrcnt1_2 = $dataPrint[$asla]["diffPercent_1"];
			if($dataPrint[$asla]["diffPercent_1"] == '-0'){$valDPrcnt1_1 = 0;}
			if($dataPrint[$asla]["diffPercent_1"] == '-0'){$valDPrcnt1_2 = 0;}
			if($dataPrint[$asla]["TrsnprtLoss_1"] == "Yes")
			{
				$colorTeks = "style='color:red;'";
			}else{
				$colorTeks = "";
			}
			if($dataPrint[$asla]["TrsnprtLoss_2"] == "Yes")
			{
				$colorTeksLT = "style='color:red;'";
			}else{
				$colorTeksLT = "";
			}
			$dataTR .= "
						<tr>
							<td align=\"center\" rowspan=\"2\">".($asla + 1)."</td>
							<td style=\"padding-left:5px;\" rowspan=\"2\">".$dataPrint[$asla]["voyNo"]."</td>
							<td rowspan=\"2\" align=\"center\">".$dataPrint[$asla]["cargoName"]."</td>
							<td align=\"right\">".number_format($dataPrint[$asla]["bL_1"],3)."</td>
							<td align=\"right\">".number_format($dataPrint[$asla]["sfAfterLoading_1"],3)."</td>
							<td align=\"right\">".$dataPrint[$asla]["vefSfAL_1"]."</td>
							<td align=\"right\">".$dataPrint[$asla]["diff1_1"]."</td>
							<td align=\"right\">".$valDPrcnt1_1."</td>
							<td align=\"right\">".number_format($dataPrint[$asla]["sfBeforeDisch_1"],3)."</td>
							<td align=\"right\">".$dataPrint[$asla]["diff2_1"]."</td>
							<td align=\"right\">".$dataPrint[$asla]["diffPercent2_1"]."</td>
							<td align=\"center\" ".$colorTeks.">".$dataPrint[$asla]["TrsnprtLoss_1"]."</td>
							<td align=\"left\" style=\"padding-left:5px;\">".$dataPrint[$asla]["barrel"]."</td>
							<td align=\"right\">".number_format($dataPrint[$asla]["actualRec_1"],3)."</td>
							<td align=\"right\">".$dataPrint[$asla]["diff3_1"]."</td>
							<td align=\"right\">".$dataPrint[$asla]["slshFnl_1"]."</td>
						</tr>
						<tr style=\"background-color:#c1d2d8;\">
							<td align=\"right\">".number_format($dataPrint[$asla]["bL_2"],3)."</td>
							<td align=\"right\">".number_format($dataPrint[$asla]["sfAfterLoading_2"],3)."</td>
							<td align=\"right\">".$dataPrint[$asla]["vefSfAL_2"]."</td>
							<td align=\"right\">".$dataPrint[$asla]["diff1_2"]."</td>
							<td align=\"right\">".$valDPrcnt1_2."</td>
							<td align=\"right\">".number_format($dataPrint[$asla]["sfBeforeDisch_2"],3)."</td>
							<td align=\"right\">".$dataPrint[$asla]["diff2_2"]."</td>
							<td align=\"right\">".$dataPrint[$asla]["diffPercent2_2"]."</td>
							<td align=\"center\" ".$colorTeksLT.">".$dataPrint[$asla]["TrsnprtLoss_2"]."</td>
							<td align=\"left\" style=\"padding-left:5px;\">".$dataPrint[$asla]["lt"]."</td>
							<td align=\"right\">".number_format($dataPrint[$asla]["actualRec_2"],3)."</td>
							<td align=\"right\">".$dataPrint[$asla]["diff3_2"]."</td>
							<td align=\"right\">".$dataPrint[$asla]["slshFnl_2"]."</td>
						</tr>
						";
		}

		return $dataTR;
	}

	function getExportBunkerCargoTrace()
	{
		ob_start();
		$whereNya = "";
		$searchVoyNo = $_POST['actionVoyNo'];
		$dataTR = $this->getDataSummaryCargoTrace($searchVoyNo,"export");

		header("Content-Type: application/vnd.ms-excel");

		echo "<table width=\"100%\">";
			echo "<tr>
					<td colspan=\"10\" rowspan=\"3\" align=\"left\" style=\"font-size: 22px;font-weight: bold;\">
							<img width=385 height=50 src=\"".$_SERVER["HTTP_REFERER"]."/pdf/picture/lgAdy.JPG\" style=\"float:right;\" />
					</td>
				</tr><tr></tr><tr></tr>";
			echo "<tr>
					<td colspan=\"10\" align=\"left\">
						<label style=\"font-size: 28pt;font-weight: bold;\">SUMMARY CARGO TRACE</label>
					</td>
				</tr>";
			echo "<tr>
					<td colspan=\"10\" align=\"left\">
						<label id=\"lblVesselName\">ANDHIKA VIDYANATA</label>
					</td>
				</tr>";
		echo "</table>";
		
		echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"2000\" border=\"1\" id=\"tblEquipName\">";
		echo "<thead style=\"font-size: 12px;background-color: #125362;color: #FFFFFF;height:30px;\">";
		echo "<tr style=\"font-size: 12px;background-color: #125362;color: #FFFFFF;height:30px;\">";
			echo "<td style=\"width: 30px;\" align=\"center\">NO</td>";
			echo "<td style=\"width: 200px;\" align=\"center\">Voyage No Discharging Port</td>";		
			echo "<td style=\"width: 100px;\" align=\"center\">Cargo Grade</td>";		
			echo "<td style=\"width: 100px;\" align=\"center\">B / L</td>";
			echo "<td style=\"width: 100px;\" align=\"center\">SF After Loading</td>";		
			echo "<td style=\"width: 100px;\" align=\"center\">VEF SF AL/BL</td>";		
			echo "<td style=\"width: 100px;\" align=\"center\">Different</td>";		
			echo "<td style=\"width: 100px;\" align=\"center\">Diff in %</td>";
			echo "<td style=\"width: 100px;\" align=\"center\">SF Before Disch</td>";		
			echo "<td style=\"width: 100px;\" align=\"center\">Different</td>";		
			echo "<td style=\"width: 100px;\" align=\"center\">Diff in %</td>";
			echo "<td style=\"width: 100px;\" align=\"center\">Transport Loss</td>";
			echo "<td style=\"width: 100px;\" align=\"center\"></td>";
			echo "<td style=\"width: 100px;\" align=\"center\">Actual Received</td>";		
			echo "<td style=\"width: 100px;\" align=\"center\">Diff</td>";		
			echo "<td style=\"width: 100px;\" align=\"center\"></td>";		
		echo "</tr>";
		echo "</thead>";
		echo "<tbody style=\"font-size: 11px;cursor: pointer;\" id=\"tblIdBody\">";
			echo $dataTR;
		echo "</tbody>";
		echo "</table>";

		header("Content-disposition: attachment; filename=exportCargoTrace.xls");
		ob_end_flush();
	}

	function getUnixDateTime($vd = "",$typeData = "")
	{
		$valNya = "";
		if($vd != "")
		{
			$unixDate = ($vd - 25569) * 86400;
			if($typeData == "tglNya")
			{
				$valNya = gmdate("Y-m-d", $unixDate);
			}else{
				$valNya = gmdate("H:i", $unixDate+0.0001);
			}
		}
		return $valNya;
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

	function dateWithMonth($tglPost)
	{
		$tgl = substr($tglPost,8,2);
		$bln = substr($tglPost,5,2);
		$thn = substr($tglPost,0,4);
		$months = array (
						1=>'Jan',
						2=>'Feb',
						3=>'Mar',
						4=>'Apr',
						5=>'May',
						6=>'Jun',
						7=>'Jul',
						8=>'Aug',
						9=>'Sep',
						10=>'Oct',
						11=>'Nov',
						12=>'Dec'
						);
		$doNya = $tgl." ".$months[(int)$bln]." ".$thn;
		if ($tglPost == "0000-00-00") 
		{
			$doNya = "";
		}
		return $doNya;
	}



}


?>