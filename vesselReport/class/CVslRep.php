<?php
class CVslRep
{
	function CVslRep($CKoneksiVslRep, $CPublic, $CKoneksiVslRepODBC = "")
	{
		$this->CKoneksiVslRep = $CKoneksiVslRep;
		$this->CPublic = $CPublic;
		$this->CKoneksiVslRepODBC = $CKoneksiVslRepODBC;
	}
	
	function detilTblUserjns($userId, $field)
	{
		$query = $this->CKoneksiVslRep->mysqlQuery("SELECT ".$field." FROM tbluserjenis WHERE userid = '".$userId."';", $this->CKoneksiVslRep->bukaKoneksi());
		$row = $this->CKoneksiVslRep->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function detilEop($idEop, $field)
	{
		$query = $this->CKoneksiVslRep->mysqlQuery("SELECT ".$field." FROM tbleop WHERE ideop='".$idEop."' AND deletests=0", $this->CKoneksiVslRep->bukaKoneksi());
		$row = $this->CKoneksiVslRep->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function detilEopByGuid($rowGuid, $field)
	{
		$query = $this->CKoneksiVslRep->mysqlQuery("SELECT ".$field." FROM tbleop WHERE guid='".$rowGuid."' AND deletests=0;", $this->CKoneksiVslRep->bukaKoneksi());
		$row = $this->CKoneksiVslRep->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function detilCopByGuid($rowGuid, $field)
	{
		$query = $this->CKoneksiVslRep->mysqlQuery("SELECT ".$field." FROM tblcop WHERE guid='".$rowGuid."' AND deletests=0;", $this->CKoneksiVslRep->bukaKoneksi());
		$row = $this->CKoneksiVslRep->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function detilNoonByGuid($rowGuid, $field)
	{
		$query = $this->CKoneksiVslRep->mysqlQuery("SELECT ".$field." FROM tblnoon WHERE guid='".$rowGuid."' AND deletests=0;", $this->CKoneksiVslRep->bukaKoneksi());
		$row = $this->CKoneksiVslRep->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function detilMorningByGuid($rowGuid, $field)
	{
		$query = $this->CKoneksiVslRep->mysqlQuery("SELECT ".$field." FROM tblmorning WHERE guid='".$rowGuid."' AND deletests=0;", $this->CKoneksiVslRep->bukaKoneksi());
		$row = $this->CKoneksiVslRep->mysqlFetch($query);
		
		return $row[$field];
	}
	function detilPortByGuid($rowGuid, $field)
	{
		$query = $this->CKoneksiVslRep->mysqlQuery("SELECT ".$field." FROM tblport WHERE guid='".$rowGuid."' AND deletests=0;", $this->CKoneksiVslRep->bukaKoneksi());
		$row = $this->CKoneksiVslRep->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function menuPilihVsl() // TAMPILKAN MENU BATHCNO KHUSUS TAHUN+BULAN SAJA
	{
		$html = "";
		$html .= "<option value=\"all\" ".$sel.">-- ALL VESSEL --</option>";
		$query = $this->CKoneksiVslRep->mysqlQuery("SELECT idvsl, nmvsl, initvsl FROM tblvessel WHERE deletests=0 ORDER BY nmvsl ASC;", $this->CKoneksiVslRep->bukaKoneksi());
		while($row = $this->CKoneksiVslRep->mysqlFetch($query))
		{
			$sel = "";
			$html.="<option value=\"".$row['initvsl']."\" ".$sel.">".$row['nmvsl']."</option>";
		}
		
		return $html;
	}
	
	function CCEmailAddress()
	{
		$query = $this->CKoneksiVslRep->mysqlQuery("SELECT emailaddress FROM tblsetting LIMIT 1", $this->CKoneksiVslRep->bukaKoneksi());
		$row = $this->CKoneksiVslRep->mysqlFetch($query);
		
		return $row['emailaddress'];
	}
	
	function sendEmailCC($jenisReport, $rowGuid, $dataVsl = "")
	{
		$initVsl = "";
		if(isset($jenisReport))
		{
			$emailKe = $this->CCEmailAddress();
			
			$subject = "";
			if($jenisReport == "eop")
			{
				$subject = $this->detilEopByGuid($rowGuid, "namakapal")." - ".strtoupper($jenisReport)." REPORT";
				$initVsl = $this->detilEopByGuid($rowGuid, "initvsl");
				//$emailKe .= ",".$this->getEmailVissel($initVsl,"emailvsl");
				$emalKplNya = $this->getEmailVissel($initVsl,"emailvsl");
				if($emalKplNya != "")
				{
					$emailKe .= ",".$emalKplNya;
				}
			}
			if($jenisReport == "cop")
			{
				$subject = $this->detilCopByGuid($rowGuid, "namakapal")." - ".strtoupper($jenisReport)." REPORT";
				$initVsl = $this->detilCopByGuid($rowGuid, "initvsl");
				//$emailKe .= ",".$this->getEmailVissel($initVsl,"emailvsl");
				$emalKplNya = $this->getEmailVissel($initVsl,"emailvsl");
				if($emalKplNya != "")
				{
					$emailKe .= ",".$emalKplNya;
				}
			}
			if($jenisReport == "noon")
			{
				$subject = $this->detilNoonByGuid($rowGuid, "namakapal")." - ".strtoupper($jenisReport)." REPORT";
				$initVsl = $this->detilNoonByGuid($rowGuid, "initvsl");
				//$emailKe .= ",".$this->getEmailVissel($initVsl,"emailvsl");
				$emalKplNya = $this->getEmailVissel($initVsl,"emailvsl");
				if($emalKplNya != "")
				{
					$emailKe .= ",".$emalKplNya;
				}
			}
			if($jenisReport == "morning")
			{
				$subject = $this->detilMorningByGuid($rowGuid, "namakapal")." - ".strtoupper($jenisReport)." REPORT";
				$initVsl = $this->detilMorningByGuid($rowGuid, "initvsl");
				//$emailKe .= ",".$this->getEmailVissel($initVsl,"emailvsl");
				$emalKplNya = $this->getEmailVissel($initVsl,"emailvsl");
				if($emalKplNya != "")
				{
					$emailKe .= ",".$emalKplNya;
				}
			}
			if($jenisReport == "port")
			{
				$subject = $this->detilPortByGuid($rowGuid, "namakapal")." - ".strtoupper($jenisReport)." REPORT";
				$initVsl = $this->detilPortByGuid($rowGuid, "initvsl");
				//$emailKe .= ",".$this->getEmailVissel($initVsl,"emailvsl");
				$emalKplNya = $this->getEmailVissel($initVsl,"emailvsl");
				if($emalKplNya != "")
				{
					$emailKe .= ",".$emalKplNya;
				}
			}
			if($jenisReport == "maintenance")
			{
				$subject = $dataVsl[0]." - ".strtoupper($jenisReport)." REPORT";
				$emalKplNya = $this->getEmailVissel($dataVsl[1],"emailvsl");
				if($emalKplNya != "")
				{
					$emailKe .= ",".$emalKplNya;
				}
			}
			
			$isiMessage = "";
			$isiMessage.= "<table border=0 width=\"100%\" style=\"font-family:Arial Narrow;\" cellpadding=\"0\" cellspacing=\"0\"> 
						  <tr>
							  <td>
								  *************************************************<br>
								  PLEASE DO NOT REPLY THIS EMAIL!<br>
								  *************************************************
								  <br><br>
							  </td>
						  </tr>
							";
			$isiMessage.= "<tr height=\"10\"><td></td></tr>";
			$isiMessage.= "<tr>
							<td>
							   <table width=\"100%\" border=0 cellpadding=\"2\" cellspacing=\"2\">
							   ".strip_tags($this->isiEmailCC($jenisReport, $rowGuid, $dataVsl),'<tr><td><br><span>')."
							   </table>
							</td>
						</tr>";
			// $isiMessage.= "<tr>
							// <td>
							   // <table width=\"100%\" border=0 cellpadding=\"2\" cellspacing=\"2\">
							   // ".$this->isiEmailCC($jenisReport, $rowGuid)."
							   // </table>
							// </td>
						// </tr>";
			$isiMessage.= "<tr height=\"20\"><td></td></tr>";
			$isiMessage.= "<tr><td style=\"border-bottom-width:1px;
				border-top-width:0px;
				border-left-width:0px;
				border-right-width:0px;
				border-color:#000;
				border-style:inset;
				letter-spacing:0px;\">&nbsp;</td></tr>";
			$isiMessage.= "<tr><td height=\"2\"></td></tr>";
			$isiMessage.= "<tr><td height=\"40\" style=\"border-bottom-width:0px;
				border-top-width:1px;
				border-left-width:0px;
				border-right-width:0px;
				border-color:#000;
				border-style:inset;
				letter-spacing:0px;\">&nbsp;</td></tr>";
			
			$isiMessage.= "<tr>
							  <td>
								  *******************************<br>
								  END OF NOTIFICATION <br>
								  *******************************
								  <br><br>
							  </td>
						  </tr>";
			$isiMessage.="</table> \r\n";
			echo $isiMessage;
			
			$headers = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\n";
			$headers .= "X-Priority: 3\n";
			$headers .= "X-MSMail-Priority: Normal\n";
			$headers .= "X-Mailer: php\n";
			$headers .= "From: noreply@andhika.com\n"; 
			//$headers .= "Bcc: arifan.hidayat@andhika.com, hendra.roesli@andhika.com\n";
			
			//mail($emailKe, $subject, $isiMessage, $headers);
			$this->sentMailToOut($emailKe, $subject, $isiMessage);
		}
	}

	function sentMailToOut($mailNya = "",$subject = "",$bodyNya = "")
	{
		require_once("smtpclass/smtp.php");
		require_once("smtpclass/sasl.php");
			
		$from="noreply@andhika.com";
		// $to=array("ahmad.maulana@andhika.com");
		$to = explode(",", $mailNya);

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
		$smtp->user="noreply@andhika.com";
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
	
	function isiEmailCC($jenisReport, $rowGuid, $dataVsl = "")
	{
		$html = "";
		if($jenisReport == "eop")
		{
			$html = "";
			$query = $this->CKoneksiVslRep->mysqlQuery("SELECT * FROM tbleop WHERE guid='".$rowGuid."' AND deletests=0;", $this->CKoneksiVslRep->bukaKoneksi());
			$row = $this->CKoneksiVslRep->mysqlFetch($query);
			
			$namePort = $row['nameport'];	
			$dateEop = ($row['dateeop'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['dateeop'])."&nbsp;&nbsp;".$row['houreop'];
			$arrivalTimes = ($row['arrivaltimes'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['arrivaltimes'])."&nbsp;&nbsp;".$row['hourarrtimes'];
			$norTime = ($row['nortime'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['nortime'])."&nbsp;&nbsp;".$row['hournortime'];
			$droppedAnchor = ($row['droppedanchor'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['droppedanchor'])."&nbsp;&nbsp;".$row['hourdroppedanchor'];
			$fwe = ($row['fwe'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['fwe'])."&nbsp;&nbsp;".$row['hourfwe'];
			$robEop = ($row['robeop'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['robeop'])."&nbsp;&nbsp;".$row['hourrobeop'];
			$robFwe = ($row['robfwe'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['robfwe'])."&nbsp;&nbsp;".$row['hourrobfwe'];
			
			$lastEdit = $this->convTglAddUsrDt($row['updusrdt']);
			$lastReceive = ($row['lastreceive'] == "")?"&nbsp;":$this->convLastReceive($row['lastreceive']);
			
			$arrayTd = array( array("LAST EDIT IN VESSEL",$lastEdit,""), 
									array("LAST RECEIVE",$lastReceive,""),
									array("<br>","",""), 
									array("VOYAGE NO",$row['voyageno'],""),
									array("REFNO",$row['refno'],""), 
									array("VESSEL NAME",$row['namakapal'],""), 
									array("DATE OF EOP",$dateEop,"HRS"),
									array("NAME OF PORT",$row['nameport'],""),
									array("ARRIVAL TIME",$arrivalTimes,"HRS"),
									array("NOR TIME",$norTime,"HRS"),
									array("ARR DRAFT F",$row['arrdraft_f'],"MTR"),
									array("ARR DRAFT M ",$row['arrdraft_m'],"MTR"),
									array("ARR DRAFT A",$row['arrdraft_a'],"MTR"),
									array("DROPPED ANCHOR",$droppedAnchor,"HRS"),
									array("POSITION",$row['position'],"HRS"),
									array("FWE",$fwe,"HRS"),
									array("ROB EOP",$robEop,"HRS"),
									array("MFO EOP",$row['mfoeop'],"MT"),
									array("MDO EOP",$row['mdoeop'],"MT"),
									array("ME CYL LO EOP",$row['mecyleop'],"LTRS"),
									array("ME SYS LO EOP",$row['mesyseop'],"LTRS"),
									array("AE LO EOP",$row['aeloeop'],"LTRS"),
									array("SUMP TK EOP",$row['sumptkeop'],"LTRS"),
									array("FW EOP",$row['fweop'],"MT"),
									array("TOTAL DIST",$row['totaldist'],"NM"),
									array("TOTAL TIME",$row['totaltime'],"HRS"),
									array("AV SPD",$row['avspd'],"KNOTS"),
									array("TOTAL MFO CONSUMP (ME/AE)",$row['totalmfo'],"MT"),
									array("AV MFO COMSUMP (ME/AE)",$row['avmfo'],"MT/ DAY"),
									array("TOTAL MDO CONSUMP (AE)",$row['totalmdo'],"MT"),
									array("AV MDO CONSUMP",$row['avmdo'],"MT/ DAY"),
									array("AV RPM",$row['avrpm'],""),
									array("AV WEATHER COND FOR VOYAGE",$row['avweather'],""),
									array("ROB FWE",$robFwe,"HRS"),
									array("MFO FWE",$row['mfofwe'],"MT"),
									array("MDO FWE",$row['mdofwe'],"MT"),
									array("ME CYL LO FWE",$row['mecyllofwe'],"LTRS"),
									array("ME SYS LO FWE",$row['mesyslofwe'],"LTRS"),
									array("AE LO FWE",$row['aelofwe'],"LTRS"),
									array("SUMP TK FWE",$row['sumptkfwe'],"LTRS"),
									array("FW FWE",$row['fwfwe'],"MT"),
									array("REMARKS",$this->nl2br2( $row['remarks']),"")
								);	
			for($i = 0; $i < count($arrayTd); $i++)
			{
				$judul = $arrayTd[$i][0];
				$isi = $arrayTd[$i][1];
				$isiSpan = $arrayTd[$i][2];
				
				if($i == 2)
				{	$html.= "<tr><td>".$arrayTd[$i][0]."</td></tr>";	}
				else
				{	$html.= "<tr><td>".$judul." : <a>".$isi."</a> <span>".$isiSpan."</span></td></tr>";	}
			}
		}
		if($jenisReport == "cop")
		{
			$html = "";
			$query = $this->CKoneksiVslRep->mysqlQuery("SELECT * FROM tblcop WHERE guid='".$rowGuid."' AND deletests=0;", $this->CKoneksiVslRep->bukaKoneksi());
			$row = $this->CKoneksiVslRep->mysqlFetch($query);
			
			$dateCop = ($row['datecop'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['datecop'])."&nbsp;&nbsp;".$row['hourcop'];
			$pilotOnBoard = ($row['pilotonboard'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['pilotonboard'])."&nbsp;&nbsp;".$row['hourpiloton'];
			$standByEngine = ($row['standbyengine'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['standbyengine'])."&nbsp;&nbsp;".$row['hourstandby'];
			$anchorUp =($row['anchorup'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['anchorup'])."&nbsp;&nbsp;".$row['houranchorup'];
			$pilotOff =($row['pilotoff'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['pilotoff'])."&nbsp;&nbsp;".$row['hourpilotoff'];
			$fullAway =($row['fullaway'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['fullaway'])."&nbsp;&nbsp;".$row['hourfullaway'];
			$etaDest = ($row['etadest'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['etadest'])."&nbsp;&nbsp;".$row['houretadest'];
			
			$lastEdit = $this->convTglAddUsrDt($row['updusrdt']);
			$lastReceive = ($row['lastreceive'] == "")?"&nbsp;":$this->convLastReceive($row['lastreceive']);
			
			$arrayTd = array( array("LAST SAVED",$lastEdit,""), 
									array("LAST RECEIVE",$lastReceive,""), 
									array("<br>","",""), 
									array("VOYAGE NO",$row['voyageno'],""),
									array("REFNO",$row['refno'],""), 
									array("VESSEL NAME",$this->konversiQuotes( $row['namakapal'] ),""), 
									array("DATE OF COP",$dateCop,""), 
									array("LAST PORT",$this->konversiQuotes( $row['lastport'] ),""), 
									array("NEXT PORT",$this->konversiQuotes( $row['nextport'] ),""), 
									array("PILOT ON BOARD",$pilotOnBoard,"HRS"), 
									array("STAND BY ENGINE",$standByEngine,"HRS"), 
									array("UN BERTH",$this->konversiQuotes( $row['unberth'] ),""), 
									array("DROP ANCHOR",$this->konversiQuotes( $row['dropanchor'] ),""), 
									array("ANCHOR UP",$anchorUp,"HRS"), 
									array("PILOT OFF",$pilotOff,"HRS"), 
									array("COP / FULL AWAY",$fullAway,"HRS"), 
									array("DTG",$row['dtg'],"NM"), 
									array("ETA DESTINATION",$etaDest,"HRS"), 
									array("ARR DRAFT F",$row['draft_f'],"MTR"),
									array("ARR DRAFT M ",$row['draft_m'],"MTR"),
									array("ARR DRAFT A",$row['draft_a'],"MTR"),
									array("ROB MFO",$this->konversiQuotes( $row['robmfo'] ),"MT"),
									array("ROB MGO",$this->konversiQuotes( $row['robmgo'] ),"MT"), 
									array("ROB CYLINDER OIL",$this->konversiQuotes( $row['robcyloil'] ),"LTRS"), 
									array("ROW SYSTEM OIL",$this->konversiQuotes( $row['robsysoil'] ),"LTRS"), 
									array("ROB AE LO",$this->konversiQuotes( $row['robaelo'] ),"LTRS"), 
									array("ROB SUMP TANK",$this->konversiQuotes( $row['robsumptank'] ),"LTRS"), 
									array("ROB FW",$this->konversiQuotes( $row['robfw'] ),"MT"), 
									array("RECEIVED MFO",$this->konversiQuotes( $row['recmfo'] ),"MT"),
									array("RECEIVED MGO",$this->konversiQuotes( $row['recmdo'] ),"MT"),
									array("RECEIVED CYL OIL",$this->konversiQuotes( $row['reccyloil'] ),"LTRS"),
									array("RECEIVED SYS OIL",$this->konversiQuotes( $row['recsysoil'] ),"LTRS"),
									array("RECEIVED AE LO",$this->konversiQuotes( $row['recaelo'] ),"LTRS"),
									array("RECEIVED FW",$this->konversiQuotes( $row['recfw'] ),"MT"),
									array("CONSUMPTION MFO",$this->konversiQuotes( $row['conmfo'] ),"MT"),
									array("CONSUMPTION MDO",$this->konversiQuotes( $row['conmdo'] ),"MT"),
									array("CONSUMPTION CYL OIL",$this->konversiQuotes( $row['concyloil'] ),"LTRS"),
									array("CONSUMPTION SYS OIL",$this->konversiQuotes( $row['consysoil'] ),"LTRS"),
									array("CONSUMPTION AE LO",$this->konversiQuotes( $row['conaelo'] ),"LTRS"),
									array("CONSUMPTION FW",$this->konversiQuotes( $row['confw'] ),"MT"),
									array("CARGO NAME",$this->konversiQuotes( $row['cargoname'] ),""),
									array("CARGO QTY",$this->konversiQuotes( $row['cargoqty'] ),"MT"),
									array("REMARKS",$this->konversiQuotes( $this->nl2br2($row['remarks']) ),"")
								);
			
			for($i = 0; $i < count($arrayTd); $i++)
			{
				$judul = $arrayTd[$i][0];
				$isi = $arrayTd[$i][1];
				$isiSpan = $arrayTd[$i][2];
				
				if($i == 2)
				{	$html.= "<tr><td>".$arrayTd[$i][0]."</td></tr>";	}
				else
				{	$html.= "<tr><td>".$judul." : <a>".$isi."</a> <span>".$isiSpan."</span></td></tr>";	}
			}
		}
		if($jenisReport == "noon")
		{
			//$html.= "<tr><td>".$jenisReport." / ".$rowGuid."</td></tr>";
			$html = "";
			$query = $this->CKoneksiVslRep->mysqlQuery("SELECT * FROM tblnoon WHERE guid = '".$rowGuid."' AND deletests=0;", $this->CKoneksiVslRep->bukaKoneksi());
			$row = $this->CKoneksiVslRep->mysqlFetch($query);
			
			$dateNoon = ($row['datenoon'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['datenoon'])."&nbsp;&nbsp;".$row['hournoon'];
			$pilotOnBoard = ($row['pilotonboard'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['pilotonboard'])."&nbsp;&nbsp;".$row['hourpiloton'];
			$standByEngine = ($row['standbyengine'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['standbyengine'])."&nbsp;&nbsp;".$row['hourstandby'];
			$anchorUp =($row['anchorup'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['anchorup'])."&nbsp;&nbsp;".$row['houranchorup'];
			$pilotOff =($row['pilotoff'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['pilotoff'])."&nbsp;&nbsp;".$row['hourpilotoff'];
			$fullAway =($row['fullaway'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['fullaway'])."&nbsp;&nbsp;".$row['hourfullaway'];
			$eta = ($row['eta'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['eta'])."&nbsp;&nbsp;".$row['houreta'];
			
			$lastEdit = $this->convTglAddUsrDt($row['updusrdt']);
			$lastReceive = ($row['lastreceive'] == "")?"&nbsp;":$this->convLastReceive($row['lastreceive']);
			
			$arrayTd = array( array("LAST SAVED",$lastEdit,""), 
									array("LAST RECEIVE",$lastReceive,""), 
									array("<br>","",""), 
									array("VOYAGE NO",$row['voyageno'],""),
									array("REFNO",$row['refno'],""), 
									array("VESSEL NAME",$this->CPublic->konversiQuotes( $row['namakapal'] ),""), 
									array("DATE OF NOON",$dateNoon,""), 
									array("LAST PORT",$this->CPublic->konversiQuotes( $row['lastport'] ),""), 
									array("NEXT PORT",$this->CPublic->konversiQuotes( $row['nextport'] ),""), 
									array("POSITION",$this->CPublic->konversiQuotes( $row['position'] ),""), 
									array("COURSE",$this->CPublic->konversiQuotes( $row['course'] ),"DEG"), 
									array("DISTANCE RUN",$this->CPublic->konversiQuotes( $row['distancerun'] ),"NM"), 
									array("STEAMING TIME",$this->CPublic->konversiQuotes( $row['steamingtime'] ),"HRS "), 
									array("SPEED",$this->CPublic->konversiQuotes( $row['speed'] ),"KTS"), 
									array("DIST TO GO",$this->CPublic->konversiQuotes( $row['disttogo'] ),"NM"), 
									array("ME RPM/DAY / FULL AWAY",$this->CPublic->konversiQuotes( $row['merpmday']),""), 
									array("ENG SLIP",$this->CPublic->konversiQuotes( $row['engslip'] ),"NM"), 
									array("WIND DIR/FORCE",$this->CPublic->konversiQuotes( $ro['winddir'] ),"HRS"), 
									array("SEA SCALE",$this->CPublic->konversiQuotes( $row['seascale'] ),"MTR"),
									array("WEATHER COND",$this->CPublic->konversiQuotes( $row['weathercond'] ),"MTR"),
									array("ETA",$eta,"HRS"),
									array("ROB MFO",$this->CPublic->konversiQuotes( $row['robmfo'] ),"MT"),
									array("ROB MDO",$this->CPublic->konversiQuotes( $row['robmdo'] ),"MT"), 
									array("ROB ME CYL OIL",$this->CPublic->konversiQuotes( $row['robmecyloil'] ),"LTRS"), 
									array("ROB ME SYS OIL",$this->CPublic->konversiQuotes( $row['robmesysoil'] ),"LTRS"), 
									array("ROB SUMP TANK",$this->CPublic->konversiQuotes( $row['robsumptank'] ),"LTRS"), 
									array("ROB AE / LO",$this->CPublic->konversiQuotes( $row['robae'] ),"LTRS"), 
									array("ROB FW",$this->CPublic->konversiQuotes( $row['robfw'] ),"MT"), 
									array("MFO CONSUMP ME",$this->CPublic->konversiQuotes( $row['mfoconsumpme'] ),"MT"),
									array("MDO CONSUMP",$this->CPublic->konversiQuotes( $row['mdoconsump'] ),"MT"),
									array("ME CYL LO CONSUMP",$this->CPublic->konversiQuotes( $row['mecylloconsump'] ),"LTRS"),
									array("ME SYS LO CONSUMP",$this->CPublic->konversiQuotes( $row['mesysloconsump'] ),"LTRS"),
									array("A/E LO CONSUMP",$this->CPublic->konversiQuotes( $row['aeloconsump'] ),"LTRS"),
									array("SUMP TANK CONSUMP",$this->CPublic->konversiQuotes( $row['sumptankconsump'] ),"MT"),
									array("FW CONSUMP",$this->CPublic->konversiQuotes( $row['fwconsump'] ),"MT"),
									array("FW PRODUCT",$this->CPublic->konversiQuotes( $row['fwproduct'] ),"MT"),
									array("REMARKS",$this->CPublic->konversiQuotes( $row['remarks'] ),"")
								);
								
			for($i = 0; $i < count($arrayTd); $i++)
			{
				$judul = $arrayTd[$i][0];
				$isi = $arrayTd[$i][1];
				$isiSpan = $arrayTd[$i][2];
				
				if($i == 2)
				{	
					$html.= "<tr><td>".$judul."</td></tr>";
				}
				else
				{	$html.= "<tr><td>".$judul." : <a>".$isi."</a> <span>".$isiSpan."</span></td></tr>";	}
			}
		}
		if($jenisReport == "morning")
		{
			$html = "";
			$query = $this->CKoneksiVslRep->mysqlQuery("SELECT * FROM tblmorning WHERE guid = '".$rowGuid."' AND guid='".$rowGuid."' AND deletests=0;", $this->CKoneksiVslRep->bukaKoneksi());
			$row = $this->CKoneksiVslRep->mysqlFetch($query);
			
			$lastEdit = $this->convTglAddUsrDt($row['updusrdt']);
			$lastReceive = ($row['lastreceive'] == "")?"&nbsp;":$this->convLastReceive($row['lastreceive']);
			$dateMorning = ($row['datemorning'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['datemorning'])."&nbsp;&nbsp;".$row['hourmorning'];	
			$eta= ($row['eta'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['eta'])."&nbsp;&nbsp;".$row['houreta'];
			
			$arrayTd = array( array("LAST SAVED",$lastEdit,""), 
									array("LAST RECEIVE",$lastReceive,""), 
									array("<br>","",""), 
									array("VOYAGE NO",$row['voyageno'],""),
									array("REFNO",$row['refno'],""), 
									array("VESSEL NAME",$this->CPublic->konversiQuotes( $row['namakapal'] ),""), 
									array("DATE OF MORNING",$dateMorning,"HRS"), 
									array("POSITION",$this->CPublic->konversiQuotes( $row['position'] ),""), 
									array("DIST TO GO",$this->CPublic->konversiQuotes( $row['disttogo'] ),"NM"), 
									array("SPEED",$this->CPublic->konversiQuotes( $row['speed'] ),"KTS"), 
									array("ETA",$eta,"HRS"),
									array("NEXT PORT",$this->CPublic->konversiQuotes( $row['nextport'] ),"")
								);
						
			for($i = 0; $i < count($arrayTd); $i++)
			{
				$judul = $arrayTd[$i][0];
				$isi = $arrayTd[$i][1];
				$isiSpan = $arrayTd[$i][2];
				
				if($i == 2)
				{	$html.= "<tr><td>".$arrayTd[$i][0]."</td></tr>";	}
				else
				{	$html.= "<tr><td>".$judul." : <a>".$isi."</a> <span>".$isiSpan."</span></td></tr>";	}
			}
		}
		if($jenisReport == "port")
		{
			$html = "";
			$query = $this->CKoneksiVslRep->mysqlQuery("SELECT *,(robmfo + memfoconsump + aemfoconsump + boilermfoconsump) as totalMfo,(robmdo+memdoconsump+aemdoconsump+boilermdoconsump) as totalMdo FROM tblport  WHERE guid = '".$rowGuid."' AND deletests=0;", $this->CKoneksiVslRep->bukaKoneksi());
			$row = $this->CKoneksiVslRep->mysqlFetch($query);
			
			$lastReceive = ($row['lastreceive'] == "")?"&nbsp;":$this->convLastReceive($row['lastreceive']);
			$datePort = ($row['dateport'] == "0000-00-00")?"":$this->CPublic->convTglNonDB($row['dateport'])."&nbsp;&nbsp;".$row['hourport'];		
			$lastEdit = $this->convTglAddUsrDt($row['updusrdt']);
		
			$arrayTd = array( array("LAST SAVED",$lastEdit,""), 
						array("LAST RECEIVE",$lastReceive,""),
						array("VOYAGE NO",$row['voyageno'],""),
						array("VESSEL NAME",$row['namakapal'],""), 
						array("REFNO",$row['refno'],""),						
						array("DATE OF PORT",$datePort,""),
						array("POSITION",$row['position'],""), 
						array("WEATHER",$this->konversiQuotes($row['weather']),""),
						array("SHIP ACTIVITY",$row['shipactivity'],""),
						array("DRAFT",$row['draft'],""),
						array("ROB MFO",$row['robmfo']." MT",""),
						array("ME MFO CONSUMP",$row['memfoconsump']." MT",""),
						array("AE MFO CONSUMP",$row['aemfoconsump']." MT",""),
						array("BOILER MFO CONSUMP",$row['boilermfoconsump']." MT",""),
						array("TOTAL MFO CONSUMP",$row['totalMfo']." MT",""),
						array("ROB MDO",$row['robmdo']." MT",""),
						array("ME MDO CONSUMP",$row['memdoconsump']." MT",""),
						array("AE MDO CONSUMP",$row['aemdoconsump']." MT",""),
						array("BOILER MDO CONSUMP",$row['boilermdoconsump']." MT",""),
						array("TOTAL MDO CONSUMP",$row['totalMdo']." MT",""),
						array("ROB ME CYL LO",$row['robmecyllo']." MT",""),
						array("ME CYL LO DAILY CONSUMP",$row['mecyllodailyconsump']." MT",""),
						array("ROB ME SYS LO",$row['robmesyslo']." MT",""),
						array("ME SYS LO DAILY CONSUMP",$row['mesyslodailyconsump']." MT",""),
						array("ROB AE LO",$row['robaelo']." LTRS",""),
						array("AE LO DAILY CONSUMP",$row['aelodailyconsump']." LTRS",""),
						array("ROB SUMP TK",$row['robsumptk']." LTRS",""),
						array("ROB FW",$row['robfw']." MT",""),
						array("FW CONSUMP",$row['fwconsump']." MT",""),
						array("REMARKS ",$this->konversiQuotes($row['remark']),"")
					);
			$html.= "<tr><td>";
			for($i = 0; $i < count($arrayTd); $i++)
			{
				$judul = $arrayTd[$i][0];
				$isi = $arrayTd[$i][1];
				$isiSpan = $arrayTd[$i][2];
				// $html.= "<tr><td>".$judul." : <a>".$isi."</a> <span>".$isiSpan."</span></td></tr>";
				$html.= "".$judul." : <a>".$isi."</a> ".$isiSpan."<br><br>";
			}
			$html.= "</td></tr>";
		}
		// if($jenisReport == "maintenance")
		// {
			// $html = "";
			// $sql = "SELECT B.jobhead,convert(varchar,A.last_done,110) as lastDone,convert(varchar,A.next_due,110) as nextDue, A.* FROM tblvslcom".$dataVsl[2]." A LEFT JOIN mstjobdesc B ON B.jobcode = A.jobcode WHERE A.s_no IN(".$dataVsl[3].")";
			// $query = $this->CKoneksiVslRepODBC->odbcExec($this->CKoneksiVslRepODBC->bukaKoneksi(), $sql);

			// $html.= "<tr><td>LAST RECEIVE IN HO</td><td>:</td><td>".date("d-m-Y H:i:s")." WIB</td></tr>";
			// $html.= "<tr><td>VESSEL NAME</td><td>:</td><td>".$dataVsl[0]."</td></tr>";
			// $html.= "<tr><td>INFORMATION</td><td>:</td><td>";
				// while($row = $this->CKoneksiVslRepODBC->odbcFetch($query))
				// {
					// $html.= "- ".$row['freq']." / ".$row['compname']." / ".$row['jobhead']." / Last Date :".$row['lastDone']." / Next Due :".$row['nextDue']."<br>";
				// }
			// $html.= "</td></tr>";
		// }
		if($jenisReport == "maintenance")
		{
			$html = "";
			$sql = "SELECT B.jobhead,convert(varchar,A.last_done,110) as lastDone,convert(varchar,A.next_due,110) as nextDue, A.* FROM tblvslcom".$dataVsl[2]." A LEFT JOIN mstjobdesc B ON B.jobcode = A.jobcode WHERE A.s_no IN(".$dataVsl[3].")";
			$query = $this->CKoneksiVslRepODBC->odbcExec($this->CKoneksiVslRepODBC->bukaKoneksi(), $sql);

			$html.= "<tr><td>LAST RECEIVED : ".date("d-m-Y H:i:s")." WIB</td></tr>";
			$html.= "<tr><td>VESSEL NAME : ".$dataVsl[0]."</td></tr>";
			$html.= "<tr><td>Following are PMS Master Code Has been done :</td><td></td></tr>";
			$html.= "<tr><td>";
				$dt = "";
				while($row = $this->CKoneksiVslRepODBC->odbcFetch($query))
				{
					if($dt == "")
					{
						$dt = $row['s_no'];
					}else{
						$dt .= ", ".$row['s_no'];
					}
				}
				$html .= $dt.".";
			$html.= "</td></tr>";
		}
		
		return $html;
	}
	
	function getEmailVissel($init,$field)
	{
		$query = $this->CKoneksiVslRep->mysqlQuery("SELECT ".$field." FROM tblvessel WHERE initvsl='".$init."';", $this->CKoneksiVslRep->bukaKoneksi());
		$row = $this->CKoneksiVslRep->mysqlFetch($query);
		
		return $row[$field];
	}
	
	function nl2br2($string) 
	{
		$string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
		return $string;
	} 
	
	function konversiQuotes($string) 
	{ 
		$search = array('"', "'"); 
		$replace = array("\&#34;", "\&#39;");
	
		return str_replace($search, $replace, $string); 
	}
	
	function menuPage()
	{
		return "<div id=\"kotakMenu\" style=\"position:absolute;top:32px;left:67px;z-index:2;visibility:hidden;\">
				<table cellpadding=\"0\" cellspacing=\"0\" class=\"tabelBorderAll\" width=\"200\" height=\"80\" style=\"\">
				<tr><td height=\"20\" class=\"tabelBorderBottomJust\" onclick=\"klikMenuPage('eop');\">&nbsp;END OF PASSAGE REPORT (EOP)</td></tr>
				<tr><td height=\"20\" class=\"tabelBorderBottomJust\" onclick=\"klikMenuPage('cop');\">&nbsp;COMMON OF PASSAGE REPORT (COP)</td></tr>
				<tr><td height=\"20\" class=\"tabelBorderBottomJust\">&nbsp;NOON REPORT</td></tr>
				<tr><td height=\"20\" class=\"tabelBorderBottomJust\">&nbsp;MORNING REPORT</td></tr>
				</table>
			</div>";
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
	
	function convLastReceive($dateTime)  // 2017-04-25 15:52:30 = 25-04-2017 15:52:30
	{
		$expDateTime = explode(" ", $dateTime);
		$tglDateTime = $expDateTime[0];
		$time = $expDateTime[1];
		
		$tgl = substr($tglDateTime,8,2);
		$bln = substr($tglDateTime,5,2);
		$thn = substr($tglDateTime,0,4);
		
		return $tgl."/".$bln."/".$thn." ".$time;
	}
	
	function convTglAddUsrDt($dateTime) // 00001/20170209/10:01:03 =  09/02/2017 10:01:03
	{
		$expDateTime = explode("/", $dateTime);
		$tglDateTime = $expDateTime[1]; //20170217
		$time = $expDateTime[2]; //09:56:31
		
		$tgl = substr($tglDateTime,6,2);
		$bln = substr($tglDateTime,4,2);
		$thn = substr($tglDateTime,0,4);
		
		return $tgl."/".$bln."/".$thn." ".$time;
	}
	
	function jmlPesan($userId, $page)
	{
		$query = $this->CKoneksiVslRep->mysqlQuery("SELECT * FROM tblpesan WHERE userid='".$userId."' AND page='".$page."';", $this->CKoneksiVslRep->bukaKoneksi());
		$jmlRow = $this->CKoneksiVslRep->mysqlNRows($query);
		return $jmlRow;
	}
}
?>