<script language="javascript">
window.open('', '_self', '');
</script>
<?php
	ini_set('max_execution_time', '256');
	$obj = new readMail();
	$dataPrint = array();
	$dateNow = date('Y-m-d');
	$obj->connect();
	$stIns = "";
	$ttlIns = 0;
	$dt1 = array();
	$dt2 = array();
	$dt3 = array();
	$dt4 = array();
	$dt5 = array();
	$dt6 = array();

	// $obj->sentMailToOut("subject tes","isibody");
	// exit;

	$dt1 = $obj->getReadMail("ast.extendedtrc@vesseltrc.net");
	$dt2 = $obj->getReadMail("no-reply@polestarglobal.com");
	$dt3 = $obj->getReadMail("notification@geotrack.asia");
	$dt4 = $obj->getReadMail("no-reply.ook9zt@zapiermail.com");
	$dt5 = $obj->getReadMail("vtrack@sailink.id");
	$dt6 = $obj->getReadMail("support@geotekno.asia");
	
	for($hal=0;$hal<count($dt1);$hal++)
	{
		array_push($dataPrint,$dt1[$hal]);
	}
	for($lan=0;$lan<count($dt2);$lan++)
	{
		array_push($dataPrint,$dt2[$lan]);
	}
	for($ast=0;$ast<count($dt3);$ast++)
	{
		array_push($dataPrint,$dt3[$ast]);
	}
	for($ast=0;$ast<count($dt4);$ast++)
	{
		array_push($dataPrint,$dt4[$ast]);
	}
	for($ast=0;$ast<count($dt5);$ast++)
	{
		array_push($dataPrint,$dt5[$ast]);
	}
	for($ast=0;$ast<count($dt6);$ast++)
	{
		array_push($dataPrint,$dt6[$ast]);
	}
	// print_r($dataPrint);exit;
	for($lan=0;$lan < count($dataPrint);$lan++)
	{
		$dtCek = $obj->cekDataMail($dataPrint[$lan]['shipName'],$dataPrint[$lan]['datePosition']);
		if($dtCek == 0)
		{
			$sql = "INSERT INTO data_vessel(uid,subject,vessel,date_position,date_position_ori,latitude,lat_ori,longitude,long_ori,speed,course,add_date,user_add)VALUES('".$dataPrint[$lan]['uid']."','".$dataPrint[$lan]['subject']."','".$dataPrint[$lan]['shipName']."','".$dataPrint[$lan]['datePosition']."','".$dataPrint[$lan]['datePositionOri']."','".$dataPrint[$lan]['lat']."','".$dataPrint[$lan]['latOri']."','".$dataPrint[$lan]['long']."','".$dataPrint[$lan]['longOri']."','".$dataPrint[$lan]['speed']."','".$dataPrint[$lan]['course']."','".$dateNow."','localhost') ";
			try {
				$obj->mysqlQuery($sql);
				$stIns = "Insert Success..!!";
				$ttlIns ++;
			} catch (Exception $ex) {
				$stIns = "Failed..!! =>".$ex;
			}
		}
	}
	echo $ttlIns." Data ".$stIns." <br>";
	$obj->getDataLatLong();
	
	class readMail
	{
		function connect()
		{
			$host = "localhost";
			$user = "root";
			$pass = "";
			$db = "andhikaportal";
			
			mysql_connect($host,$user,$pass);
			mysql_select_db($db);
			if (mysqli_connect_errno())
			{
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
		}
		function getReadMail($fromNya = "")
		{
			$dataOut = array();
			if (! function_exists('imap_open'))
			{
				echo "IMAP is not configured.";
				exit();
			}else{
				// if($fromNya == "notification@geotrack.asia")
				// {
				// 	$imapNya = "{mail.andhika.com:143/novalidate-cert}SPAM";
				// }else{
				// 	$imapNya = "{mail.andhika.com:143/novalidate-cert}INBOX";
				// }				
				// $imapNya = "{mail.andhika.com:143/novalidate-cert}INBOX";
				$imapNya = "{imap.zoho.com:993/imap/ssl/novalidate-cert}/INBOX";

				$userEmail = "ahmad.maulana@andhika.com";
				$passEmail = "@kosong2023";
				
				$connection = imap_open($imapNya, $userEmail, $passEmail) or die('Cannot connect to Mail: ' . imap_last_error());

				$date = date ("d M Y",strToTime ( "-2 days" ) );//membaca email hari ini dan kemarin
				$emailData = imap_search($connection, "FROM \"$fromNya\" SINCE \"$date\"");
				
				if (!empty($emailData))
				{
					rsort($emailData);
					foreach ($emailData as $emailIdent)
					{
						$overview = imap_fetch_overview($connection, $emailIdent, 0);
						$messagess = imap_fetchbody($connection, $emailIdent, 1, FT_PEEK);//untuk baca isi body
						
						$cekContnGeotrack = explode("Vessel Tracking Report",$messagess);

						if($fromNya == "notification@geotrack.asia")
						{
							if(count($cekContnGeotrack) < 2)
							{
								$dataOut[] = $this->explodeData($overview,$messagess,$fromNya);
							}
						}else{
							$dataOut[] = $this->explodeData($overview,$messagess,$fromNya);
						}
					}
				}
				imap_close($connection);
			}
			return $dataOut;
		}		
		function explodeData($dataOverview = "",$data = "",$type = "")
		{
			$dataExpl = array();
			$dataExpl["subject"] = $dataOverview[0]->subject;
		
			if($type == "no-reply@polestarglobal.com")
			{
				$dtShipName = explode("Ship Name:",$data);
				$dtShipName = explode("Ship Type:",$dtShipName[1]);
				$dtDatePosition = explode("Position Timestamp:",$data);
				$dtDatePosition = explode("Latitude:",$dtDatePosition[1]);
				$dtLat = explode("Latitude:",$data);
				$dtLat = explode("Longitude:",$dtLat[1]);
				$dtLong = explode("Longitude:",$data);
				$dtLong = explode("Course/Speed:",$dtLong[1]);
				$dtCourseSpeed = explode("Course/Speed:",$data);
				$dtCourseSpeed = explode("Source:",$dtCourseSpeed[1]);
				$dtCourse = explode("degrees at",$dtCourseSpeed[0]);
				$dtSpeed = explode("knots",$dtCourse[1]);
				
				$dataExpl["uid"] = $dataOverview[0]->uid;
				$dataExpl["shipName"] = trim($dtShipName[0]);
				$dataExpl["datePosition"] = $this->convertDate(trim($dtDatePosition[0]));
				$dataExpl["datePositionOri"] = trim($dtDatePosition[0]);
				$dataExpl["lat"] = $this->cnvrtLatLongDmsToDd(trim($dtLat[0]),"poleStart");
				$dataExpl["latOri"] = trim($dtLat[0]);
				$dataExpl["long"] = $this->cnvrtLatLongDmsToDd(trim($dtLong[0]),"poleStart");
				$dataExpl["longOri"] = trim($dtLong[0]);
				$dataExpl["speed"] = trim($dtSpeed[0]);
				$dataExpl["course"] = trim($dtCourse[0]);
			}
			else if($type == "ast.extendedtrc@vesseltrc.net")
			{
				
				$dtShipName = explode("Vessel  name :",$data);
				$dtShipName = explode("Callsign     :",$dtShipName[1]);
				$dtDatePosition = explode("Date & time  :",$data);
				$dtDatePosition = explode("Latitude     :",$dtDatePosition[1]);
				$dtLat = explode("Latitude     :",$data);
				$dtLat = explode("Longitude    :",$dtLat[1]);
				$dtLong = explode("Longitude    :",$data);
				$dtLong = explode("Average speed:",$dtLong[1]);
				
				$dataExpl["uid"] = $dataOverview[0]->uid;
				$dataExpl["shipName"] = trim($dtShipName[0]);
				$dataExpl["datePosition"] = $this->convertDate(trim($dtDatePosition[0]));
				$dataExpl["datePositionOri"] = trim($dtDatePosition[0]);
				$dataExpl["lat"] = $this->cnvrtLatLongDmsToDd(trim($dtLat[0]),"ast");
				$dataExpl["latOri"] = str_replace(array('"',"'"), array('``','`'), trim($dtLat[0]));
				$dataExpl["long"] = $this->cnvrtLatLongDmsToDd(trim($dtLong[0]),"ast");
				$dataExpl["longOri"] = str_replace(array('"',"'"), array('``','`'), trim($dtLong[0]));
				$dataExpl["speed"] = "";
				$dataExpl["course"] = "";
			}
			else if($type == "notification@geotrack.asia")
			{
				$dataEpl = explode("Vessel Name",$data);				
				$dSN = explode("=0D=",$dataEpl[1]);

				$dtShipName = str_replace(array("=","\r","\n","<br>"), array(''), $dSN[0]);				
				$dp = str_replace(array("=0D=0A","=","\r","\n","<br>"), array(' '), $data);

				$dLTs = explode("Report",$dataOverview[0]->subject);

				$dtExplSN = explode("Latitude",trim($dtShipName));
				$dLT = explode("Longitude",$dtExplSN[1]);
				$dLNG = explode("Heading",$dLT[1]);
				$dSpeed = explode("Speed",$dLNG[1]);
				$dSpeed = explode("kn",$dSpeed[1]);
				
				$dataExpl["uid"] = $dataOverview[0]->uid;
				$dataExpl["shipName"] = trim(str_replace(array('</td>        <td colspan"2">','</td>      </tr>      <tr>        <td colspan"2">'), array(''), $dtExplSN[0]));
				$dataExpl["datePosition"] = $this->setFormatDate(trim($dLTs[1]));
				$dataExpl["datePositionOri"] = trim($dLTs[1]);
				$dataExpl["lat"] = trim(str_replace(array('</td>        <td colspan"2">','</td>      </tr>      <tr>        <td colspan"2">'), array(''), $dLT[0]));
				$dataExpl["latOri"] = trim(str_replace(array('</td>        <td colspan"2">','</td>      </tr>      <tr>        <td colspan"2">'), array(''), $dLT[0]));
				$dataExpl["long"] = trim(str_replace(array('</td>        <td colspan"2">','</td>      </tr>      <tr>        <td colspan"2">'), array(''), $dLNG[0]));
				$dataExpl["longOri"] = trim(str_replace(array('</td>        <td colspan"2">','</td>      </tr>      <tr>        <td colspan"2">'), array(''), $dLNG[0]));
				$dataExpl["speed"] = trim(str_replace(array('</td>        <td colspan"2">','</td>      </tr>      <tr>        <td colspan"2">'), array(''), $dSpeed[0]));
				$dataExpl["course"] = "";
			}			
			else if($type == "notification@geotekno.asia")
			{
				$dataEpl = explode("Vessel Name",$data);
				$dSN = explode("=0D=",$dataEpl[1]);

				$dtShipName = str_replace(array("=","\r","\n","<br>"), array(''), $dSN[0]);				
				$dp = str_replace(array("=0D=0A","=","\r","\n","<br>"), array(' '), $data);

				$dLTs = explode("Report",$dataOverview[0]->subject);

				$dtExplSN = explode("Latitude",trim($dtShipName));
				$dLT = explode("Longitude",$dtExplSN[1]);
				$dLNG = explode("Heading",$dLT[1]);
				$dSpeed = explode("Speed",$dLNG[1]);
				$dSpeed = explode("kn",$dSpeed[1]);
				
				$dataExpl["uid"] = $dataOverview[0]->uid;
				$dataExpl["shipName"] = trim(str_replace(array('</td>        <td colspan"2">','</td>      </tr>      <tr>        <td colspan"2">'), array(''), $dtExplSN[0]));
				$dataExpl["datePosition"] = $this->setFormatDate(trim($dLTs[1]));
				$dataExpl["datePositionOri"] = trim($dLTs[1]);
				$dataExpl["lat"] = trim(str_replace(array('</td>        <td colspan"2">','</td>      </tr>      <tr>        <td colspan"2">'), array(''), $dLT[0]));
				$dataExpl["latOri"] = trim(str_replace(array('</td>        <td colspan"2">','</td>      </tr>      <tr>        <td colspan"2">'), array(''), $dLT[0]));
				$dataExpl["long"] = trim(str_replace(array('</td>        <td colspan"2">','</td>      </tr>      <tr>        <td colspan"2">'), array(''), $dLNG[0]));
				$dataExpl["longOri"] = trim(str_replace(array('</td>        <td colspan"2">','</td>      </tr>      <tr>        <td colspan"2">'), array(''), $dLNG[0]));
				$dataExpl["speed"] = trim(str_replace(array('</td>        <td colspan"2">','</td>      </tr>      <tr>        <td colspan"2">'), array(''), $dSpeed[0]));
				$dataExpl["course"] = "";
			}
			else if($type == "vtrack@sailink.id")
			{
				$dataEpl = explode("Vessel Name:",$data);
				$dSN = explode("=0D=",$dataEpl[1]);
				$dtShipName = str_replace(array("=","\r","\n","<br>"), array(''), $dSN[0]);

				$vn = explode("Time(WIB):",$dtShipName);
				$dl = explode("Latitude:",$vn[1]);
				$vslName = trim($vn[0]);
				$strDate = trim($dl[0]);
				$strRplc = str_replace(",", "", $strDate);
				$spltDate = explode(" ", $strRplc);
				$datePos = $spltDate[3]."-".$this->convertDateGetMonth($spltDate[2])."-".$spltDate[1]." ".$spltDate[4];
				$latTemp = explode("Longitude:",$dl[1]);
				$lat = trim($latTemp[0]);
				$longTemp = explode("Speed:",$latTemp[1]);
				$long = trim($longTemp[0]);
				$speedTemp = explode("kn",$longTemp[1]);
				$speed = trim($speedTemp[0]);

				$dataExpl["uid"] = $dataOverview[0]->uid;
				$dataExpl["shipName"] = trim($vslName);
				$dataExpl["datePosition"] = trim($datePos);
				$dataExpl["datePositionOri"] = trim($strRplc);
				$dataExpl["lat"] = trim($lat);
				$dataExpl["latOri"] = trim($lat);
				$dataExpl["long"] = trim($long);
				$dataExpl["longOri"] = trim($long);
				$dataExpl["speed"] = trim($speed);
				$dataExpl["course"] = "";
			}
			else if($type == "no-reply.ook9zt@zapiermail.com")//punya traksat
			{
				
				$dtShipName = explode("Ship Name:",$data);				
				$dtShipName = explode("Ship Type:",$dtShipName[1]);				
				$dtDatePosition = explode("Position Timestamp:",$data);				
				$dtDatePosition = explode("Server Timestamp:",$dtDatePosition[1]);
				$dtLat = explode("Latitude:",$data);				
				$dtLat = explode("Longitude:",$dtLat[1]);				
				$dtLong = explode("Longitude:",$data);				
				$dtLong = explode("Heading:",$dtLong[1]);				
				
				$dataExpl["uid"] = $dataOverview[0]->uid;
				$dataExpl["shipName"] = trim($dtShipName[0]);
				$dataExpl["datePosition"] = $this->convertDate(trim($dtDatePosition[0]));
				$dataExpl["datePositionOri"] = trim($dtDatePosition[0]);
				$dataExpl["lat"] = str_replace(array('"',"'"), array('``','`'), trim($dtLat[0]));
				$dataExpl["latOri"] = str_replace(array('"',"'"), array('``','`'), trim($dtLat[0]));
				$dataExpl["long"] = str_replace(array('"',"'"), array('``','`'), trim($dtLong[0]));
				$dataExpl["longOri"] = str_replace(array('"',"'"), array('``','`'), trim($dtLong[0]));
				$dataExpl["speed"] = "";
				$dataExpl["course"] = "";
			}
			else if($type == "support@geotekno.asia")
			{
				$dataEpl = explode("Vessel Name",$data);
				$dSN = explode("=0D=",$dataEpl[1]);

				$dtShipName = str_replace(array("=","\r","\n","<br>"), array(''), $dSN[0]);

				$dLTs = explode("Report Interval at",$data);
				$dLTs = explode("GMT+0700",$dLTs[1]);
				$dLTs = str_replace(array("=","\r","\n","<br>"), array(''), $dLTs[0]);

				$datePosition = $this->cnvrtDateGeo($dLTs);
				$datePositionOri = $dLTs;
	
				$dtExplSN = explode("Latitude",trim($dtShipName));
				$vslName = trim($dtExplSN[0]);
				
				$dLT = explode("Longitude",$dtExplSN[1]);
				$dLNG = explode("Heading",$dLT[1]);
				$dSpeed = explode("Speed",$dLNG[1]);
				$dSpeed = explode("GeoTekno",$dSpeed[1]);

				$speedNya = str_replace(array('<table>','</table>','<td>','<td colspan3D"2">','</td>','</tr>','<tr>','<ahref3D"mailto:suppor','"',' '), array(''), $dSpeed[0]);
				$speedNya = str_replace(array('<ahref3Dmailto:support@geotekno.asiatarget3D_blank>',' '), array(''), $speedNya);

				$dataExpl["uid"] = $dataOverview[0]->uid;
				$dataExpl["shipName"] = trim(str_replace(array('</td>            <td colspan3D"2">','</td>        </tr>        <tr>            <td colspan3D"2">'), array(''), $vslName));
				$dataExpl["datePosition"] = $datePosition;
				$dataExpl["datePositionOri"] = trim($datePositionOri);
				$dataExpl["lat"] = trim(str_replace(array('</td>            <td colspan3D"2">','</td>        </tr>        <tr>            <td colspan3D"2">'), array(''), $dLT[0]));
				$dataExpl["latOri"] = trim(str_replace(array('</td>            <td colspan3D"2">','</td>        </tr>        <tr>            <td colspan3D"2">'), array(''), $dLT[0]));
				$dataExpl["long"] = trim(str_replace(array('</td>            <td colspan3D"2">','</td>        </tr>        <tr>            <td colspan3D"2">'), array(''), $dLNG[0]));
				$dataExpl["longOri"] = trim(str_replace(array('</td>            <td colspan3D"2">','</td>        </tr>        <tr>            <td colspan3D"2">'), array(''), $dLNG[0]));
				$dataExpl["speed"] = $speedNya;
				$dataExpl["course"] = "";
			}
			else if($type == "notification_210223@geotekno.asia")
			{
				$dataEpl = explode("Vessel Name:",$data);				
				$dSN = explode("=0D=",$dataEpl[1]);				
				$dtShipName = str_replace(array("=","\r","\n","<br>"), array(''), $dSN[0]);				
				$dp = str_replace(array("=0D=0A","=","\r","\n","<br>"), array(' '), $data);
				
				$dtExplSN = explode("Vessel Type:",$dtShipName);

				$dataDP = explode("Timestamp:",$dp);
				$dDP = explode("Vessel Name:",$dataDP[1]);
				$dLTs = explode("Latitude:",$dDP[0]);
				$dLT = explode("Longitude:",$dLTs[1]);
				$dLNG = explode("Speed:",$dLT[1]);
				$dSpeed = explode("kn Heading:",$dLNG[1]);
				$dCourse = explode("Terminal Number:",$dSpeed[1]);
				
				$dataExpl["uid"] = $dataOverview[0]->uid;
				$dataExpl["shipName"] = trim($dtExplSN[0]);
				$dataExpl["datePosition"] = $this->setFormatDate(trim($dLTs[0]));
				$dataExpl["datePositionOri"] = trim($dLTs[0]);
				$dataExpl["lat"] = trim($dLT[0]);
				$dataExpl["latOri"] = trim($dLT[0]);
				$dataExpl["long"] = trim($dLNG[0]);
				$dataExpl["longOri"] = trim($dLNG[0]);
				$dataExpl["speed"] = trim($dSpeed[0]);
				$dataExpl["course"] = strip_tags(trim($dCourse[0]));
			}
			else if($type == "notification_Versi1@geotekno.asia")
			{
				$dataEpl = explode("Vessel Name:",$data);
				$dSN = explode("=0D=",$dataEpl[1]);
				$dtShipName = str_replace(array("=","\r","\n","<br>"), array(''), $dSN[0]);
				$dp = str_replace(array("=0D=0A","=","\r","\n","<br>"), array(' '), $data);
				$dataDP = explode("Timestamp:",$dp);
				$dDP = explode("Vessel Name:",$dataDP[1]);
				$dLT = explode("Latitude:",$dp);
				$dLT = explode("Longitude:",$dLT[1]);
				$dLNG = explode("Speed:",$dLT[1]);
				
				$dataExpl["uid"] = $dataOverview[0]->uid;
				$dataExpl["shipName"] = trim($dtShipName);
				$dataExpl["datePosition"] = $this->setFormatDate(trim($dDP[0]));
				$dataExpl["datePositionOri"] = trim($dDP[0]);
				$dataExpl["lat"] = trim($dLT[0]);
				$dataExpl["latOri"] = trim($dLT[0]);
				$dataExpl["long"] = trim($dLNG[0]);
				$dataExpl["longOri"] = trim($dLNG[0]);
			}

			return $dataExpl;
		}
		function convertDate($dateNya = "")
		{
			$dataDate = "";
			$dtNya = "";
			$hrNya = "";
			$dtDate = explode(" ",$dateNya);
			
			if($dtDate[(count($dtDate)-1)] == "UTC")
			{
				$dataDate = $dtDate[0]." ".$dtDate[1];
				$dtNya = $dtDate[0];
				$hourNya = explode(":",$dtDate[1]);
				$hrNya = $hourNya[0]+7;
				if($hrNya >= 24)
				{
					if($hrNya == 24)
					{
						$hrNya = "00:".$hourNya[1];
					}else{
						$hrNya = $hrNya - 24;
						if(strlen($hrNya) == "1")
						{
							$hrNya = "0".$hrNya;
						}
						$hrNya = $hrNya.":".$hourNya[1];
					}
					$stop_date = new DateTime($dtNya);
					$stop_date->modify('+1 day');
					$dtNya = $stop_date->format('Y-m-d');
					$dtNya = date($dtNya);
					$dataDate = $dtNya." ".$hrNya;
				}else{
					if(strlen($hrNya) == "1")
					{
						$hrNya = "0".$hrNya;
					}
					$hrNya = $hrNya.":".$hourNya[1];
					$dataDate = $dtNya." ".$hrNya;
				}
			}
			else if($dtDate[(count($dtDate)-1)] == "GMT")
			{
				$dateConvrt = new DateTime($dateNya);
				$dateConvrt->setTimezone(new DateTimeZone('Asia/Jakarta'));
				$dataDate = $dateConvrt->format('Y-m-d H:i:s');
			}
			else{
				$dataDate = $dtDate[0]." ".$dtDate[1];
				$dtNya = $dtDate[0];
				$hourNya = explode(":",$dtDate[1]);
				$hrNya = $hourNya[0]+7;
				if($hrNya >= 24)
				{
					if($hrNya == 24)
					{
						$hrNya = "00:".$hourNya[1];
					}else{
						$hrNya = $hrNya - 24;
						if(strlen($hrNya) == "1")
						{
							$hrNya = "0".$hrNya;
						}
						$hrNya = $hrNya.":".$hourNya[1];
					}
					$stop_date = new DateTime($dtNya);
					$stop_date->modify('+1 day');
					$dtNya = $stop_date->format('Y-m-d');
					$dtNya = date($dtNya);
					$dataDate = $dtNya." ".$hrNya;
				}else{
					$jamNya = explode(":",$dtDate[1]);
					if(strlen($hrNya) == "1")
					{
						$hrNya = "0".$hrNya;
					}
					$dataDate = $dtDate[0]." ".$hrNya.":".$jamNya[1];
				}
			}
			return $dataDate;
		}
		function convertDateGetMonth($nb = "")
		{
			$bln = "";

			if($nb == "jan" OR $nb == "Jan")
			{
				$bln = "01";
			}
			else if($nb == "feb" OR $nb == "Feb")
			{
				$bln = "02";
			}
			else if($nb == "mar" OR $nb == "Mar")
			{
				$bln = "03";
			}
			else if($nb == "apr" OR $nb == "Apr")
			{
				$bln = "04";
			}
			else if($nb == "may" OR $nb == "May" OR $nb == "mei" OR $nb == "Mei")
			{
				$bln = "05";
			}
			else if($nb == "jun" OR $nb == "Jun")
			{
				$bln = "06";
			}
			else if($nb == "jul" OR $nb == "Jul")
			{
				$bln = "07";
			}
			else if($nb == "aug" OR $nb == "Aug")
			{
				$bln = "08";
			}
			else if($nb == "sep" OR $nb == "Sep")
			{
				$bln = "09";
			}
			else if($nb == "oct" OR $nb == "Oct" OR $nb == "okt" OR $nb == "Okt")
			{
				$bln = "10";
			}
			else if($nb == "nov" OR $nb == "Nov")
			{
				$bln = "11";
			}
			else if($nb == "dec" OR $nb == "Dec" OR $nb == "des" OR $nb == "Des")
			{
				$bln = "12";
			}

			return $bln;
		}
		function cnvrtLatLongDmsToDd($dtNya = "",$typeData = "")
		{
			$dataOut = "";
			$stDerajat = "";
			$dtNya = str_replace("'", '`', $dtNya);

			if($typeData == "ast")
			{
				$dt = explode(" ",$dtNya);
				$dt1 = $dt[0];
				$dt2 = $dt[1];
				$dt3 = $dt[2];
				$dt4 = $dt[3];
				$dt5 = $dt[4];
				$dt6 = $dt[5];
				if($dt1 < 0){ $dt1 = $dt1*-1; }
				$dataOut = $dt1+((($dt2*60)+$dt4)/3600);
				if($dt6 == "S" || $dt6 == "W")
				{
					if($dataOut < 0)
					{
						$dataOut = $dataOut;
					}else{
						$dataOut = "-".$dataOut;
					}
				}
			}
			else
			{
				$dt = explode(".",$dtNya);
				$dt1 = $dt[0];
				$dt2 = $dt[1];
				$dt3 = explode(' ',$dt[2]);
				$dataOut = $dt1+((($dt2*60)+$dt3[0])/3600);
				if($dt3[1] == "S" || $dt3[1] == "W")
				{
					$dataOut = "-".$dataOut;
				}
			}
			return $dataOut;
		}
		function cnvrtDateGeo($dateNya = "")
		{
			$tempDt = explode(" ",$dateNya);
			$dtNya = $tempDt[2]." ".$tempDt[3]." ".$tempDt[4]." ".$tempDt[5];

			$ymd = DateTime::createFromFormat('M j Y H:i:s',$dtNya)->format('Y-m-d H:i:s');

			return $ymd;
		}
		function cekDataMail($vessel = "",$datePosition = "")
		{
			$sql = "SELECT * FROM data_vessel WHERE vessel = '".$vessel."' AND  date_position = '".$datePosition."'";
			$query = $this->mysqlQuery($sql);
			$result = $this->mysqlNRows($query);
			return $result;
		}
		function mysqlQuery($result)
		{
			$this->mResult = mysql_query($result);
			if(!$this->mResult) {die(mysql_error());}			
			return $this->mResult;
		}
		function mysqlFetch($sql)
		{
			$this->strSQL = mysql_fetch_array($sql,MYSQL_ASSOC);			
			return $this->strSQL;
		}
		function mysqlNRows($sql)
		{
			$this->strSQL = mysql_num_rows($this->mResult = $sql);			
			return $this->strSQL;
		}
		function DMStoDD($input)
		{
			$deg = " " ;
			$min = " " ;
			$sec = " " ;  
			$inputM = " " ;        


			print "<br> Input is ".$input." <br>";

			for ($i=0; $i < strlen($input); $i++) 
			{                     
				$tempD = $input[$i];

				if ($tempD == iconv("UTF-8", "ISO-8859-1//TRANSLIT", '°') ) 
				{ 
					$newI = $i + 1 ;

					$inputM =  substr($input, $newI, -1) ;
					break; 
				}//close if degree

				$deg .= $tempD ;                    
			}//close for degree

			for ($j=0; $j < strlen($inputM); $j++) 
			{ 
				$tempM = $inputM[$j];

				if ($tempM == "'")  
				 {                     
					$newI = $j + 1 ;
					$sec =  substr($inputM, $newI, -1) ;
					break; 
				 }//close if minute
				 $min .= $tempM ;                    
			}//close for min

				$result =  $deg+( (( $min*60)+($sec) ) /3600 );

				print "<br> Degree is ". $deg*1 ;
				print "<br> Minutes is ". $min ;
				print "<br> Seconds is ". $sec ;
				print "<br> Result is ". $result ;

			return $deg + ($min / 60) + ($sec / 3600);
		}
		function setFormatDate($dateNya = "")
		{
			$dt = explode("/", $dateNya);
			$tgl = $dt[1];
			$bln = $dt[0];
			$dThn = $dt[2];
			$dataDtk = explode(" ", $dThn);
			$thn = $dataDtk[0];
			$dtk = $dataDtk[1];
			return $thn."-".$bln."-".$tgl." ".$dtk;
		}
		function getDataLatLong()
		{
			$trNya = "";
			$tempVessel = array();
			$yearNow = date('Y');
			$monthNow = date('m');

			$sqlVsl = " SELECT vessel FROM data_vessel WHERE YEAR(date_position) = '".$yearNow."' AND MONTH(date_position) = '".$monthNow."' AND vessel != '' AND delete_sts = '0' GROUP BY vessel ORDER BY vessel ASC ";
			$queryVsl = $this->mysqlQuery($sqlVsl);
			while($rowVsl = $this->mysqlFetch($queryVsl))
			{
				$tempVessel[]['vessel']=$rowVsl['vessel'];
			}

			foreach ($tempVessel as $key => $value)
			{
				$mmsi = "";
				$whereNya = "";
				if(strstr(strtolower($value['vessel']), strtolower('VIDYANATA')))
				{
					$mmsi = "525106001";
					$whereNya = "vessel LIKE '%VIDYANATA%' ";
				}
				else if(strstr(strtolower($value['vessel']), strtolower('KANISHKA')))
				{
					$mmsi = "525006404";
					$whereNya = "vessel LIKE '%KANISHKA%' ";
				}
				else if(strstr(strtolower($value['vessel']), strtolower('NARESWARI')))
				{
					$mmsi = "525022375";
					$whereNya = "vessel LIKE '%NARESWARI%' ";
				}
				else if(strstr(strtolower($value['vessel']), strtolower('Athalia')))
				{
					$mmsi = "525106002";
					$whereNya = "vessel LIKE '%Athalia%' ";
				}
				else if(strstr(strtolower($value['vessel']), strtolower('Paramesti')))
				{
					$mmsi = "525006390";
					$whereNya = "vessel LIKE '%Paramesti%' ";
				}
				else if(strstr(strtolower($value['vessel']), strtolower('Bulk Batavia')))
				{
					$mmsi = "525106003";
					$whereNya = "vessel LIKE '%Bulk Batavia%' ";
				}
				else if(strstr(strtolower($value['vessel']), strtolower('Alisha')))
				{
					$mmsi = "525106004";
					$whereNya = "vessel LIKE '%andhika alisha%' ";
				}
				else if(strstr(strtolower($value['vessel']), strtolower('Nusantara')))
				{
					$mmsi = "538010125";
					$whereNya = "vessel LIKE '%Bulk Nusantara%' ";
				}

				$trNya = "";
				$sql = " SELECT * FROM data_vessel WHERE ".$whereNya." ORDER BY date_position DESC LIMIT 0,1 ";
				$query = $this->mysqlQuery($sql);
				while($row = $this->mysqlFetch($query))
				{
					$trNya .=  "MMSI=".$mmsi."\n";
					$trNya .=  "LAT=".$row['latitude']."\n";
					$trNya .=  "LON=".$row['longitude']."\n";
					$trNya .=  "SPEED=".$row['speed']."\n";
					$trNya .=  "COURSE=".$row['course']."\n";
					$trNya .=  "TIMESTAMP=".$row['date_position'];
				}

				if($trNya != "")
				{
					$subjectNya = "Vessel : ".strtolower($value['vessel']);
					
					$this->sentMailToOut($subjectNya,$trNya);
				}
			}
		}
		function sentMailToOut($subject="",$bodyNya="")
		{
			require_once("./smtpclass/smtp.php");
			require_once("./smtpclass/sasl.php");

			$from="admin@andhika.com";
			//$from="noreply@andhika.com";
			$to=array("report@marinetraffic.com");
			// $to=array("ahmad.maulana@andhika.com");

			if(strlen($from)==0)
			{
				die("Please set the messages sender address in line ".$from." of the script ".basename(__FILE__)."\n");
			}

			$smtp=new smtp_class;

			$smtp->host_name="smtp.zoho.com";
			$smtp->host_port=465;
			$smtp->ssl=1;

			$smtp->start_tls=0;
			$smtp->cryptographic_method = STREAM_CRYPTO_METHOD_TLS_CLIENT;
			$smtp->localhost="localhost";
			$smtp->direct_delivery=0;
			$smtp->timeout=500;
			$smtp->data_timeout=0;

			$smtp->debug=0;
			$smtp->html_debug=0;
			$smtp->pop3_auth_host="";
			$smtp->user="admin@andhika.com";
			$smtp->realm="";
			$smtp->password="4ndh1k4$";
			$smtp->workstation="";
			$smtp->authentication_mechanism="";

			if($smtp->direct_delivery)
			{
				if(!function_exists("GetMXRR"))
				{
					$_NAMESERVERS=array();
					require_once("./smtpclass/getmxrr.php");
				}
			}

			$viewTo = "";
			for($lan=0;$lan < count($to);$lan++)
			{
				if($viewTo == "")
				{
					$viewTo = $to[$lan];
				}else{
					$viewTo .= ",".$to[$lan];
				}
			}

			if($smtp->SendMessage(
				$from,
				$to,
				array(
					"From: $from",
					"To: $viewTo",
					"Subject: $subject",
					"Date: ".strftime("%a, %d %b %Y %H:%M:%S %Z"),
					"Content-type: text/html; charset=iso-8859-1"
				),
				$bodyNya))
			{
				echo "<br>".$subject.", Message sent to $viewTo OK.\n";
			}
			else{
				echo $subject.", Could not send the message to $viewTo.\n Error: ".$smtp->error."\n";
			}exit;
		}
   
	}

?>
<script language="javascript">
setTimeout(function() {
    window.close();
}, 2000);
</script>