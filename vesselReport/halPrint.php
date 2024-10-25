<?php
require_once("configVslRep.php");

$tpl = new myTemplate($tplDir."laporanPrintKosong.html");
$tpl->prepare();

if($aksiGet == "printEop")
{
	$idEopGet = $_GET['idEop'];
	
	$html = "";
	
	$query = $CKoneksiVslRep->mysqlQuery("SELECT * FROM tbleop WHERE ideop = '".$idEopGet."' AND deletests=0;", $CKoneksiVslRep->bukaKoneksi());
	$row = $CKoneksiVslRep->mysqlFetch($query);
	
	$lastEdit = $CVslRep->convTglAddUsrDt($row['updusrdt']);
	$lastReceive = $CVslRep->convLastReceive( $row['lastreceive'] );
/*	
	$dateEop = $CPublic->jikaParamSamaDenganNilai1($CPublic->convTglNonDBTitik( $row['dateeop'] ), "00.00.0000", "") ;
	$arrivalTimes = $CPublic->jikaParamSamaDenganNilai1($CPublic->convTglNonDBTitik( $row['arrivaltimes'] ), "00.00.0000", "") ;
	$norTime = $CPublic->jikaParamSamaDenganNilai1($CPublic->convTglNonDBTitik( $row['nortime'] ), "00.00.0000", "") ;
	*/
	$dateEop = ($row['dateeop'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['dateeop'])."&nbsp;&nbsp;".$row['houreop'];
	$arrivalTimes = ($row['arrivaltimes'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['arrivaltimes'])."&nbsp;&nbsp;".$row['hourarrtimes'];
	$norTime = ($row['nortime'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['nortime'])."&nbsp;&nbsp;".$row['hournortime'];
	$droppedAnchor = ($row['droppedanchor'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['droppedanchor'])."&nbsp;&nbsp;".$row['hourdroppedanchor'];
	$fwe = ($row['fwe'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['fwe'])."&nbsp;&nbsp;".$row['hourfwe'];
	$robEop = ($row['robeop'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['robeop'])."&nbsp;&nbsp;".$row['hourrobeop'];
	$robFwe = ($row['robfwe'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['robfwe'])."&nbsp;&nbsp;".$row['hourrobfwe'];
		
	$html.= "<tr>
				<td colspan=\"2\" valign=\"top\" style=\"text-decoration:underline;height:40px;color:#333;font-family:Arial;font-size:24px;\"><b>EOP REPORT</b></td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr valign=\"bottom\">
				<td height=\"20\" width=\"50%\" colspan=\"2\" style=\"font-size:15px;color:#485a88;font-weight:bold;font-family:Arial;\">LAST EDIT IN VESSEL : 
				<span style=\"color:#333;font-style:normal;font-size:15px;\">".$lastEdit."</span></td>
			</tr>
			<tr valign=\"bottom\">
				<td height=\"20\" width=\"50%\" colspan=\"2\" style=\"font-size:15px;color:#485a88;font-weight:bold;font-family:Arial;\">LAST RECEIVE IN HO : 
				<span style=\"color:#333;font-style:normal;font-size:15px;\">".$lastReceive."</span></td>
			</tr>
			<tr><td>&nbsp;</td></tr>";
				
	$html.= "<tr>
				<td colspan=\"2\" align=\"center\" valign=\"top\">
					<table width=\"1110\" cellpadding=\"0\" cellspacing=\"0\" class=\"tabelDetailLap\">";
					
					$arrayTd = array(
							array("REFNO",$row['refno'],""), 
							array("VESSEL NAME",$row['namakapal'],""), 
							array("DATE OF EOP",$dateEop,"HRS"),
							array("NAME OF PORT",$dateEop,""),
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
							array("REMARKS",nl2br2( $row['remarks']),"")
						);	
	for($i = 0; $i < count($arrayTd); $i++)
	{
		$judul = $arrayTd[$i][0];
		$isi = $arrayTd[$i][1];
		$isiSpan = $arrayTd[$i][2];
		
		$html.= "<tr><td>".$judul." : <a>".$isi."</a> <span style=\"color:#666;\">".$isiSpan."</span></td></td></tr>";
	}
	
	/*$html.= "<tr><td>REF NO : <a>".$row['refno']."</a></td></tr>";
	$html.= "<tr><td>DATE OF EOP : <a>".$dateEop."</a></td></tr>";
	$html.= "<tr><td>VESSEL NAME : <a>".$row['namakapal']."</a></td></tr>";
	$html.= "<tr><td>NAME OF PORT  : <a>".$row['nameport']."</a></td></tr>";
	$html.= "<tr><td>ARRIVAL TIME : <a>".$arrivalTimes."</a></td></tr>";
	$html.= "<tr><td>NOR TIME : <a>".$norTime."</a></td></tr>";
	$html.= "<tr><td>ARR DRAFT : <a>".$row['arrdraft']."</a></td></tr>";
	$html.= "<tr><td>DROPPED ANCHOR : <a>".$row['droppedanchor']."</a></td></tr>";
	$html.= "<tr><td>POSITION  <a>".$row['position']."</a></td></tr>";
	$html.= "<tr><td>FWE : <a>".$row['fwe']."</a> </td></tr>";
	$html.= "<tr><td>&nbsp;</td></tr>";
	$html.= "<tr><td>ROB EOP : <a>".$row['robeop']."</a></td></tr>";
	$html.= "<tr><td>MFO EOP : <a>".$row['mfoeop']."</a></td></tr>";
	$html.= "<tr><td>MDO EOP : <a>".$row['mdoeop']."</a></td></tr>";
	$html.= "<tr><td>ME CYL LO EOP : <a>".$row['mecyleop']."</a></td></tr>";
	$html.= "<tr><td>ME SYS LO EOP : <a>".$row['mesyseop']."</a></td></tr>";
	$html.= "<tr><td>AE LO EOP : <a>".$row['aeloeop']."</a> </td></tr>";
	$html.= "<tr><td>SUMP TK EOP : <a>".$row['sumptkeop']."</a> </td></tr>";
	$html.= "<tr><td>FW EOP : <a>".$row['fweop']."</a> </td></tr>";
	$html.= "<tr><td>&nbsp;</td></tr>";
	$html.= "<tr><td>TOTAL DIST : <a>".$row['totaldist']."</a> </td></tr>";
	$html.= "<tr><td>TOTAL TIME : <a>".$row['totaltime']."</a> </td></tr>";
	$html.= "<tr><td>AV SPD : <a>".$row['avspd']."</a></td></tr>";
	$html.= "<tr><td>&nbsp;</td></tr>";
	$html.= "<tr><td>TOTAL MFO CONSUMP (ME/AE) : <a>".$row['totalmfo']."</a></td></tr>";
	$html.= "<tr><td>AV MFO COMSUMP (ME/AE) : <a>".$row['avmfo']."</a></td></tr>";
	$html.= "<tr><td>TOTAL MDO CONSUMP (AE) : <a>".$row['totalmdo']."</a></td></tr>";
	$html.= "<tr><td>AV MDO CONSUMP : <a>".$row['avmdo']."</a></td></tr>";
	$html.= "<tr><td>AV RPM : <a>".$row['avrpm']."</a></td></tr>";
	$html.= "<tr><td>AV WEATHER COND FOR VOYAGE : <a>".$row['avweather']."</a></td></tr>";
	$html.= "<tr><td>&nbsp;</td></tr>";
	$html.= "<tr><td>ROB FWE : <a>".$row['robfwe']."</a></td></tr>";
	$html.= "<tr><td>MFO FWE : <a>".$row['mfofwe']."</a></td></tr>";
	$html.= "<tr><td>MDO FWE : <a>".$row['mdofwe']."</a></td></tr>";
	$html.= "<tr><td>ME CYL LO FWE : <a>".$row['mecyllofwe']."</a></td></tr>";
	$html.= "<tr><td>ME SYS LO FWE : <a>".$row['mesyslofwe']."</a></td></tr>";
	$html.= "<tr><td>AE LO FWE : <a>".$row['aelofwe']."</a></td></tr>";
	$html.= "<tr><td>SUMP TK FWE : <a>".$row['sumptkfwe']."</a></td></tr>";
	$html.= "<tr><td>FW FWE : <a>".$row['fwfwe']."</a></td></tr>";
	$html.= "<tr><td>REMARKS : <a>".nl2br2( $row['remarks'])."</a></td></tr>";*/

	$html.= "		</table>
				</td>
			</tr>";
	$printBy = "Printed by ".ucfirst($userName)." ".$CPublic->convTglNonDB($CPublic->tglServerWithStrip())." ".substr($CPublic->jamServer(),0,5);
	$html.= "<tr valign=\"bottom\" style=\"position:absolute;bottom:-0%;\">
				  <td height=\"28\" align=\"right\" style=\"font-style:italic;font-family:Arial Narrow;font-size:12px;color:#666;\">
					  ".$printBy."&nbsp;
				  </td>
			  </tr>";
			
	$tpl->Assign("dataList", $html);
}
if($aksiGet == "printCop")
{
	$idCopGet = $_GET['idCop'];
	
	$html = "";
	
	$query = $CKoneksiVslRep->mysqlQuery("SELECT * FROM tblcop WHERE idcop = '".$idCopGet."' AND deletests=0;", $CKoneksiVslRep->bukaKoneksi());
	$row = $CKoneksiVslRep->mysqlFetch($query);
	
	$lastEdit = $CVslRep->convTglAddUsrDt($row['updusrdt']);
	$lastReceive = $CVslRep->convLastReceive( $row['lastreceive'] );

	$dateCop = ($row['datecop'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['datecop'])."&nbsp;&nbsp;".$row['hourcop'];
	$pilotOnBoard = ($row['pilotonboard'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['pilotonboard'])."&nbsp;&nbsp;".$row['hourpiloton'];
	$standByEngine = ($row['standbyengine'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['standbyengine'])."&nbsp;&nbsp;".$row['hourstandby'];
	$anchorUp = ($row['anchorup'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['anchorup'])."&nbsp;&nbsp;".$row['houranchorup'];
	$pilotOff = ($row['pilotoff'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['pilotoff'])."&nbsp;&nbsp;".$row['hourpilotoff'];
	$fullAway = ($row['fullaway'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['fullaway'])."&nbsp;&nbsp;".$row['hourfullaway'];
	$etaDest = ($row['etadest'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['etadest'])."&nbsp;&nbsp;".$row['houretadest'];
		
	$html.= "<tr>
				<td colspan=\"2\" valign=\"top\" style=\"text-decoration:underline;height:40px;color:#333;font-family:Arial;font-size:24px;\"><b>COP REPORT</b></td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr valign=\"bottom\">
				<td height=\"20\" width=\"50%\" colspan=\"2\" style=\"font-size:15px;color:#485a88;font-weight:bold;font-family:Arial;\">LAST EDIT IN VESSEL : 
				<span style=\"color:#333;font-style:normal;font-size:15px;\">".$lastEdit."</span></td>
			</tr>
			<tr valign=\"bottom\">
				<td height=\"20\" width=\"50%\" colspan=\"2\" style=\"font-size:15px;color:#485a88;font-weight:bold;font-family:Arial;\">LAST RECEIVE IN HO : 
				<span style=\"color:#333;font-style:normal;font-size:15px;\">".$lastReceive."</span></td>
			</tr>
			<tr><td>&nbsp;</td></tr>";
	$html.= "<tr>
				<td colspan=\"2\" align=\"center\" valign=\"top\">
					<table width=\"1110\" cellpadding=\"0\" cellspacing=\"0\" class=\"tabelDetailLap\">";
	
	$html.= "<tr>
				<td colspan=\"2\" align=\"center\" valign=\"top\">
					<table width=\"1110\" cellpadding=\"0\" cellspacing=\"0\" class=\"tabelDetailLap\">";
					
					$arrayTd = array( 
							array("REFNO",$row['refno'],""), 
							array("VESSEL NAME",$CPublic->konversiQuotes( $row['namakapal'] ),""), 
							array("DATE OF COP",$dateCop,""), 
							array("LAST PORT",$CPublic->konversiQuotes( $row['lastport'] ),""), 
							array("NEXT PORT",$CPublic->konversiQuotes( $row['nextport'] ),""), 
							array("PILOT ON BOARD",$pilotOnBoard,"HRS"), 
							array("STAND BY ENGINE",$standByEngine,"HRS"), 
							array("UN BERTH",$CPublic->konversiQuotes( $row['unberth'] ),""), 
							array("DROP ANCHOR",$CPublic->konversiQuotes( $row['dropanchor'] ),""), 
							array("ANCHOR UP",$anchorUp,"HRS"), 
							array("PILOT OFF",$pilotOff,"HRS"), 
							array("COP / FULL AWAY",$fullAway,"HRS"), 
							array("DTG",$row['dtg'],"NM"), 
							array("ETA DESTINATION",$etaDest,"HRS"), 
							array("ARR DRAFT F",$row['draft_f'],"MTR"),
							array("ARR DRAFT M ",$row['draft_m'],"MTR"),
							array("ARR DRAFT A",$row['draft_a'],"MTR"),
							array("ROB MFO",$CPublic->konversiQuotes( $row['robmfo'] ),"MT"),
							array("ROB MGO",$CPublic->konversiQuotes( $row['robmgo'] ),"MT"), 
							array("ROB CYLINDER OIL",$CPublic->konversiQuotes( $row['robcyloil'] ),"LTRS"), 
							array("ROW SYSTEM OIL",$CPublic->konversiQuotes( $row['robsysoil'] ),"LTRS"), 
							array("ROB AE LO",$CPublic->konversiQuotes( $row['robaelo'] ),"LTRS"), 
							array("ROB SUMP TANK",$CPublic->konversiQuotes( $row['robsumptank'] ),"LTRS"), 
							array("ROB FW",$CPublic->konversiQuotes( $row['robfw'] ),"MT"), 
							array("RECEIVED MFO",$CPublic->konversiQuotes( $row['recmfo'] ),"MT"),
							array("RECEIVED MGO",$CPublic->konversiQuotes( $row['recmdo'] ),"MT"),
							array("RECEIVED CYL OIL",$CPublic->konversiQuotes( $row['reccyloil'] ),"LTRS"),
							array("RECEIVED SYS OIL",$CPublic->konversiQuotes( $row['recsysoil'] ),"LTRS"),
							array("RECEIVED AE LO",$CPublic->konversiQuotes( $row['recaelo'] ),"LTRS"),
							array("RECEIVED FW",$CPublic->konversiQuotes( $row['recfw'] ),"MT"),
							array("CONSUMPTION MFO",$CPublic->konversiQuotes( $row['conmfo'] ),"MT"),
							array("CONSUMPTION MDO",$CPublic->konversiQuotes( $row['conmdo'] ),"MT"),
							array("CONSUMPTION CYL OIL",$CPublic->konversiQuotes( $row['concyloil'] ),"LTRS"),
							array("CONSUMPTION SYS OIL",$CPublic->konversiQuotes( $row['consysoil'] ),"LTRS"),
							array("CONSUMPTION AE LO",$CPublic->konversiQuotes( $row['conaelo'] ),"LTRS"),
							array("CONSUMPTION FW",$CPublic->konversiQuotes( $row['confw'] ),"MT"),
							array("CARGO NAME",$CPublic->konversiQuotes( $row['cargoname'] ),""),
							array("CARGO QTY",$CPublic->konversiQuotes( $row['cargoqty'] ),"MT"),
							array("REMARKS",$CPublic->konversiQuotes( nl2br2($row['remarks']) ),"")
						);
						
	for($i = 0; $i < count($arrayTd); $i++)
	{
		$judul = $arrayTd[$i][0];
		$isi = $arrayTd[$i][1];
		$isiSpan = $arrayTd[$i][2];
		
		$html.= "<tr><td>".$judul." : <a>".$isi."</a> <span style=\"color:#666;\">".$isiSpan."</span></td></td></tr>";
	}				
	/*$html.= "<tr><td>REFNO : <a>".$row['refno']."</a></td></tr>";
	$html.= "<tr><td>VESSEL NAME : <a>".$row['namakapal']."</a></td></tr>";
	$html.= "<tr><td>DATE OF COP : <a>".$dateCop."</a></td></tr>";
	$html.= "<tr><td>LAST PORT : <a>".$row['lastport']."</a></td></tr>";
	$html.= "<tr><td>NEXT PORT : <a>".$row['nextport']."</a></td></tr>";
	$html.= "<tr><td>PILOT ON BOARD :  <a>".$pilotOnBoard."</a></td></tr>";
	$html.= "<tr><td>STAND BY ENGINE : <a>".$standByEngine."</a> </td></tr>";
	$html.= "<tr><td>UN BERTH : <a>".$row['unberth']."</a></td></tr>";
	$html.= "<tr><td>DROP ANCHOR : <a>".$row['dropanchor']."</a></td></tr>";
	$html.= "<tr><td>ANCHOR UP : <a>".$anchorUp."</a> </td></tr>";
	$html.= "<tr><td>PILOT OFF : <a>".$pilotOff."</a> </td></tr>";
	$html.= "<tr><td>COP / FULL AWAY : <a>".$fullAway."</a> </td></tr>";
	$html.= "<tr><td>DTG : <a>".$row['dtg']."</a> </td></tr>";
	$html.= "<tr><td>ETA DESTINATION : <a>".$etaDest."</a> </td></tr>";
	$html.= "<tr><td>DRAFT : <a>".$row['draft']."</a> </td></tr>";
	$html.= "<tr><td>ROB MFO : <a>".$row['robmfo']."</a> </td></tr>";
	$html.= "<tr><td>ROB MGO : <a>".$row['robmgo']."</a> </td></tr>";
	$html.= "<tr><td>ROB CYLINDER OIL : <a>".$row['robcyloil']."</a> </td></tr>";
	$html.= "<tr><td>ROW SYSTEM OIL : <a>".$row['robsysoil']."</a> </td></tr>";
	$html.= "<tr><td>ROB AE LO : <a>".$row['robaelo']."</a> </td></tr>";
	$html.= "<tr><td>ROB SUMP TANK : <a>".$row['robsumptank']."</a> </td></tr>";
	$html.= "<tr><td>ROB FW : <a>".$row['robfw']."</a> </td></tr>";
	$html.= "<tr><td>&nbsp;</td></tr>";
	
	$html.= "<tr><td>RECEIVED MFO : <a>".$row['recmfo']."</a></td></tr>";
	$html.= "<tr><td>RECEIVED MGO : <a>".$row['recmdo']."</a></td></tr>";
	$html.= "<tr><td>RECEIVED CYL OIL : <a>".$row['reccyloil']."</a></td></tr>";
	$html.= "<tr><td>RECEIVED SYS OIL : <a>".$row['recsysoil']."</a></td></tr>";
	$html.= "<tr><td>RECEIVED AE LO : <a>".$row['recaelo']."</a></td></tr>";
	$html.= "<tr><td>RECEIVED FW : <a>".$row['recfw']."</a></td></tr>";
	$html.= "<tr><td>&nbsp;</td></tr>";
	
	$html.= "<tr><td>CONSUMPTION MFO : <a>".$row['conmfo']."</a></td></tr>";
	$html.= "<tr><td>CONSUMPTION MDO : <a>".$row['conmdo']."</a> </td></tr>";
	$html.= "<tr><td>CONSUMPTION CYL OIL : <a>".$row['concyloil']."</a> </td></tr>";
	$html.= "<tr><td>CONSUMPTION SYS OIL : <a>".$row['consysoil']."</a> </td></tr>";
	$html.= "<tr><td>CONSUMPTION AE LO : <a>".$row['conaelo']."</a></td></tr>";
	$html.= "<tr><td>CONSUMPTION FW : <a>".$row['confw']."</a> </td></tr>";
	$html.= "<tr><td>CARGO : <a>".$row['cargo']."</a> </td></tr>";
	$html.= "<tr><td>REMARKS : <a>".$row['remarks']."</a></td></tr>";*/
			
	$html.= "		</table>
				</td>
			</tr>";
	$printBy = "Printed by ".ucfirst($userName)." ".$CPublic->convTglNonDB($CPublic->tglServerWithStrip())." ".substr($CPublic->jamServer(),0,5);
	$html.= "<tr valign=\"bottom\" style=\"position:absolute;bottom:-0%;\">
				  <td height=\"28\" align=\"right\" style=\"font-style:italic;font-family:Arial Narrow;font-size:12px;color:#666;\">
					  ".$printBy."&nbsp;
				  </td>
			  </tr>";
			
	$tpl->Assign("dataList", $html);
}
if($aksiGet == "printNoon")
{
	$idNoonGet = $_GET['idNoon'];
	
	$html = "";
	
	$query = $CKoneksiVslRep->mysqlQuery("SELECT * FROM tblnoon WHERE idnoon = '".$idNoonGet."' AND deletests=0;", $CKoneksiVslRep->bukaKoneksi());
	$row = $CKoneksiVslRep->mysqlFetch($query);
	
	$lastEdit = $CVslRep->convTglAddUsrDt($row['updusrdt']);
	$lastReceive = $CVslRep->convLastReceive( $row['lastreceive'] );
	$dateNoon = ($row['datenoon'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['datenoon'])."&nbsp;&nbsp;".$row['hournoon'];
	$eta = ($row['eta'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['eta'])."&nbsp;&nbsp;".$row['houreta'];
	
	$html.= "<tr>
				<td colspan=\"2\" valign=\"top\" style=\"text-decoration:underline;height:40px;color:#333;font-family:Arial;font-size:24px;\"><b>NOON REPORT</b></td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr valign=\"bottom\">
				<td height=\"20\" width=\"50%\" colspan=\"2\" style=\"font-size:15px;color:#485a88;font-weight:bold;font-family:Arial;\">LAST EDIT IN VESSEL : 
				<span style=\"color:#333;font-style:normal;font-size:15px;\">".$lastEdit."</span></td>
			</tr>
			<tr valign=\"bottom\">
				<td height=\"20\" width=\"50%\" colspan=\"2\" style=\"font-size:15px;color:#485a88;font-weight:bold;font-family:Arial;\">LAST RECEIVE IN HO : 
				<span style=\"color:#333;font-style:normal;font-size:15px;\">".$lastReceive."</span></td>
			</tr>
			<tr><td>&nbsp;</td></tr>";
	$html.= "<tr>
				<td colspan=\"2\" align=\"center\" valign=\"top\">
					<table width=\"1110\" cellpadding=\"0\" cellspacing=\"0\" class=\"tabelDetailLap\">";
	$arrayTd = array( 
							array("REFNO",$row['refno'],""), 
							array("VESSEL NAME",$CPublic->konversiQuotes( $row['namakapal'] ),""), 
							array("DATE OF NOON",$dateNoon,""), 
							array("LAST PORT",$CPublic->konversiQuotes( $row['lastport'] ),""), 
							array("NEXT PORT",$CPublic->konversiQuotes( $row['nextport'] ),""), 
							array("POSITION",$CPublic->konversiQuotes( $row['position'] ),""), 
							array("COURSE",$CPublic->konversiQuotes( $row['course'] ),"DEG"), 
							array("DISTANCE RUN",$CPublic->konversiQuotes( $row['distancerun'] ),"NM"), 
							array("STEAMING TIME",$CPublic->konversiQuotes( $row['steamingtime'] ),"HRS "), 
							array("SPEED",$CPublic->konversiQuotes( $row['speed'] ),"KTS"), 
							array("DIST TO GO",$CPublic->konversiQuotes( $row['disttogo'] ),"NM"), 
							array("ME RPM/DAY / FULL AWAY",$CPublic->konversiQuotes( $row['merpmday']),""), 
							
							array("ENG SLIP",$CPublic->konversiQuotes( $row['engslip'] ),"NM"), 
							array("WIND DIR/FORCE",$CPublic->konversiQuotes( $ro['winddir'] ),"HRS"), 
							array("SEA SCALE",$CPublic->konversiQuotes( $row['seascale'] ),"MTR"),
							array("WEATHER COND",$CPublic->konversiQuotes( $row['weathercond'] ),"MTR"),
							array("ETA",$eta,"MTR"),
							array("ROB MFO",$CPublic->konversiQuotes( $row['robmfo'] ),"MT"),
							array("ROB MDO",$CPublic->konversiQuotes( $row['robmdo'] ),"MT"), 
							array("ROB ME CYL OIL",$CPublic->konversiQuotes( $row['robmecyloil'] ),"LTRS"), 
							array("ROB ME SYS OIL",$CPublic->konversiQuotes( $row['robmesysoil'] ),"LTRS"), 
							array("ROB SUMP TANK",$CPublic->konversiQuotes( $row['robsumptank'] ),"LTRS"), 
							
							array("ROB AE / LO",$CPublic->konversiQuotes( $row['robae'] ),"LTRS"), 
							array("ROB FW",$CPublic->konversiQuotes( $row['robfw'] ),"MT"), 
							array("MFO CONSUMP ME",$CPublic->konversiQuotes( $row['mfoconsumpme'] ),"MT"),
							array("MDO CONSUMP",$CPublic->konversiQuotes( $row['mdoconsump'] ),"MT"),
							array("ME CYL LO CONSUMP",$CPublic->konversiQuotes( $row['mecylloconsump'] ),"LTRS"),
							array("ME SYS LO CONSUMP",$CPublic->konversiQuotes( $row['mesysloconsump'] ),"LTRS"),
							array("A/E LO CONSUMP",$CPublic->konversiQuotes( $row['aeloconsump'] ),"LTRS"),
							array("SUMP TANK CONSUMP",$CPublic->konversiQuotes( $row['sumptankconsump'] ),"MT"),
							array("FW CONSUMP",$CPublic->konversiQuotes( $row['fwconsump'] ),"MT"),
							array("FW PRODUCT",$CPublic->konversiQuotes( $row['fwproduct'] ),"MT"),
							array("REMARKS",$CPublic->konversiQuotes( $row['remarks'] ),"")
						);
						
	for($i = 0; $i < count($arrayTd); $i++)
	{
		$judul = $arrayTd[$i][0];
		$isi = $arrayTd[$i][1];
		$isiSpan = $arrayTd[$i][2];
		
		$html.= "<tr><td>".$judul." : <a>".$isi."</a> <span style=\"color:#666;\">".$isiSpan."</span></td></td></tr>";
	}
	/*$html.= "<tr><td>REFNO : <a>".$row['refno']."</a></td></tr>";
	$html.= "<tr><td>VESSEL NAME : <a>".$row['namakapal']."</a></td></tr>";
	$html.= "<tr><td>DATE OF NOON : <a>".$dateNoon."</a></td></tr>";
	$html.= "<tr><td>LAST PORT : <a>".$row['lastport']."</a></td></tr>";
	$html.= "<tr><td>NEXT PORT :  <a>".$row['nextport']."</a></td></tr>";
	$html.= "<tr><td>POSITION : <a>".$row['position']."</a> </td></tr>";
	$html.= "<tr><td>COURSE : <a>".$row['course']."</a></td></tr>";
	$html.= "<tr><td>DISTANCE RUN : <a>".$row['distancerun']."</a></td></tr>";
	$html.= "<tr><td>STEAMING TIME : <a>".$row['steamingtime']."</a></td></tr>";
	$html.= "<tr><td>SPEED : <a>".$row['speed']."</a></td></tr>";
	$html.= "<tr><td>DIST TO GO : <a>".$row['disttogo']."</a></td></tr>";
	$html.= "<tr><td>ME RPM/DAY : <a>".$row['merpmday']."</a></td></tr>";
	$html.= "<tr><td>ENG SLIP : <a>".$row['engslip']."</a></td></tr>";
	$html.= "<tr><td>WIND DIR/FORCE : <a>".$row['winddir']."</a></td></tr>";
	$html.= "<tr><td>SEA SCALE : <a>".$row['seascale']."</a></td></tr>";
	$html.= "<tr><td>WEATHER COND : <a>".$row['weathercond']."</a> </td></tr>";
	$html.= "<tr><td>ETA : <a>".$row['eta']."</a> </td></tr>";
	$html.= "<tr><td>ROB MFO : <a>".$row['robmfo']."</a> </td></tr>";
	$html.= "<tr><td>ROB MDO : <a>".$row['robmdo']."</a> </td></tr>";
	$html.= "<tr><td>ROB ME CYL OIL : <a>".$row['robmecyloil']."</a> </td></tr>";
	$html.= "<tr><td>ROB ME SYS OIL : <a>".$row['robmesysoil']."</a></td></tr>";
	$html.= "<tr><td>ROB SUMP TANK : <a>".$row['robsumptank']."</a></td></tr>";
	$html.= "<tr><td>ROB AE / LO : <a>".$row['robae']."</a></td></tr>";
	$html.= "<tr><td>ROB FW : <a>".$row['robfw']."</a></td></tr>";
	$html.= "<tr><td>MFO CONSUMP ME : <a>".$row['mfoconsumpme']."</a></td></tr>";
	$html.= "<tr><td>MDO CONSUMP : <a>".$row['mdoconsump']."</a></td></tr>";
	$html.= "<tr><td>ME CYL LO CONSUMP : <a>".$row['mecylloconsump']."</a></td></tr>";
	$html.= "<tr><td>ME SYS LO CONSUMP : <a>".$row['mesysloconsump']."</a></td></tr>";
	$html.= "<tr><td>A/E LO CONSUMP : <a>".$row['aeloconsump']."</a></td></tr>";
	$html.= "<tr><td>SUMP TANK CONSUMP : <a>".$row['sumptankconsump']."</a></td></tr>";
	$html.= "<tr><td>FW CONSUMP : <a>".$row['fwconsump']."</a></td></tr>";
	$html.= "<tr><td>FW PRODUCT : <a>".$row['fwproduct']."</a></td></tr>";
	$html.= "<tr><td>REMARKS : <a>".nl2br2( $row['remarks'])."</a></td></tr>";*/
	
	$html.= "		</table>
				</td>
			</tr>";
	$printBy = "Printed by ".ucfirst($userName)." ".$CPublic->convTglNonDB($CPublic->tglServerWithStrip())." ".substr($CPublic->jamServer(),0,5);
	$html.= "<tr valign=\"bottom\" style=\"position:absolute;bottom:-0%;\">
				  <td height=\"28\" align=\"right\" style=\"font-style:italic;font-family:Arial Narrow;font-size:12px;color:#666;\">
					  ".$printBy."&nbsp;
				  </td>
			  </tr>";
			
	$tpl->Assign("dataList", $html);
}
if($aksiGet == "printMorning")
{
	$idMorningGet = $_GET['idMorning'];
	
	$html = "";
	
	$query = $CKoneksiVslRep->mysqlQuery("SELECT * FROM tblmorning WHERE idmorning = '".$idMorningGet."' AND deletests=0;", $CKoneksiVslRep->bukaKoneksi());
	$row = $CKoneksiVslRep->mysqlFetch($query);
	
	$lastEdit = $CVslRep->convTglAddUsrDt($row['updusrdt']);
	$lastReceive = $CVslRep->convLastReceive( $row['lastreceive'] );
	$dateMorning = ($row['datemorning'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['datemorning'])."&nbsp;&nbsp;".$row['hourmorning'];
	$eta = ($row['eta'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['eta'])."&nbsp;&nbsp;".$row['houreta'];
	
	$html.= "<tr>
				<td colspan=\"2\" valign=\"top\" style=\"text-decoration:underline;height:40px;color:#333;font-family:Arial;font-size:24px;\"><b>MORNING REPORT</b></td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr valign=\"bottom\">
				<td height=\"20\" width=\"50%\" colspan=\"2\" style=\"font-size:15px;color:#485a88;font-weight:bold;font-family:Arial;\">LAST EDIT IN VESSEL : 
				<span style=\"color:#333;font-style:normal;font-size:15px;\">".$lastEdit."</span></td>
			</tr>
			<tr valign=\"bottom\">
				<td height=\"20\" width=\"50%\" colspan=\"2\" style=\"font-size:15px;color:#485a88;font-weight:bold;font-family:Arial;\">LAST RECEIVE IN HO : 
				<span style=\"color:#333;font-style:normal;font-size:15px;\">".$lastReceive."</span></td>
			</tr>
			<tr><td>&nbsp;</td></tr>";
	$html.= "<tr>
				<td colspan=\"2\" align=\"center\" valign=\"top\">
					<table width=\"1110\" cellpadding=\"0\" cellspacing=\"0\" class=\"tabelDetailLap\">";
					
					$arrayTd = array( 
							array("REFNO",$row['refno'],""), 
							array("VESSEL NAME",$CPublic->konversiQuotes( $row['namakapal'] ),""), 
							array("DATE OF MORNING",$dateMorning,"HRS"), 
							array("POSITION",$CPublic->konversiQuotes( $row['position'] ),""), 
							array("DIST TO GO",$CPublic->konversiQuotes( $row['disttogo'] ),"NM"), 
							array("SPEED",$CPublic->konversiQuotes( $row['speed'] ),"KTS"), 
							array("ETA",$eta,"HRS"),
							array("NEXT PORT",$CPublic->konversiQuotes( $row['nextport'] ),"")
						);
				
	for($i = 0; $i < count($arrayTd); $i++)
	{
		$judul = $arrayTd[$i][0];
		$isi = $arrayTd[$i][1];
		$isiSpan = $arrayTd[$i][2];
		
		$html.= "<tr><td>".$judul." : <a>".$isi."</a> <span style=\"color:#666;\">".$isiSpan."</span></td></td></tr>";
	}
	
	/*$html.= "<tr><td>REFNO : <a>".$row['refno']."</a></td></tr>";
	$html.= "<tr><td>VESSEL NAME : <a>".$row['namakapal']."</a></td></tr>";
	$html.= "<tr><td>DATE OF MORNING : <a>".$dateMorning."</a></td></tr>";
	$html.= "<tr><td>POSITION : <a>".$row['position']."</a> </td></tr>";
	$html.= "<tr><td>DIST TO GO : <a>".$row['disttogo']."</a></td></tr>";
	$html.= "<tr><td>SPEED : <a>".$row['speed']."</a></td></tr>";
	$html.= "<tr><td>ETA : <a>".$row['eta']."</a> </td></tr>";
	$html.= "<tr><td>NEXT PORT :  <a>".$row['nextport']."</a></td></tr>";*/
	
	$html.= "		</table>
				</td>
			</tr>";
	$printBy = "Printed by ".ucfirst($userName)." ".$CPublic->convTglNonDB($CPublic->tglServerWithStrip())." ".substr($CPublic->jamServer(),0,5);
	$html.= "<tr valign=\"bottom\" style=\"position:absolute;bottom:-0%;\">
				  <td height=\"28\" align=\"right\" style=\"font-style:italic;font-family:Arial Narrow;font-size:12px;color:#666;\">
					  ".$printBy."&nbsp;
				  </td>
			  </tr>";
			
	$tpl->Assign("dataList", $html);
}
if($aksiGet == "printPort")
{
	$idPortGet = $_GET['idPort'];
	$html = "";
	
	$query = $CKoneksiVslRep->mysqlQuery("SELECT *,(robmfo + memfoconsump + aemfoconsump + boilermfoconsump) as totalMfo,(robmdo+memdoconsump+aemdoconsump+boilermdoconsump) as totalMdo FROM tblport WHERE idport = '".$idPortGet."' AND deletests=0;", $CKoneksiVslRep->bukaKoneksi());
	$row = $CKoneksiVslRep->mysqlFetch($query);
	
	$lastEdit = $CVslRep->convTglAddUsrDt($row['updusrdt']);
	$lastReceive = $CVslRep->convLastReceive( $row['lastreceive'] );
	$dateport = ($row['dateport'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['dateport'])."&nbsp;&nbsp;".$row['hourport'];

	$html.= "<tr>
				<td colspan=\"2\" valign=\"top\" style=\"text-decoration:underline;height:40px;color:#333;font-family:Arial;font-size:24px;\"><b>PORT REPORT</b></td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr valign=\"bottom\">
				<td height=\"20\" width=\"50%\" colspan=\"2\" style=\"font-size:15px;color:#485a88;font-weight:bold;font-family:Arial;\">LAST EDIT IN VESSEL : 
				<span style=\"color:#333;font-style:normal;font-size:15px;\">".$lastEdit."</span></td>
			</tr>
			<tr valign=\"bottom\">
				<td height=\"20\" width=\"50%\" colspan=\"2\" style=\"font-size:15px;color:#485a88;font-weight:bold;font-family:Arial;\">LAST RECEIVE IN HO : 
				<span style=\"color:#333;font-style:normal;font-size:15px;\">".$lastReceive."</span></td>
			</tr>
			<tr><td>&nbsp;</td></tr>";
	$html.= "<tr>
				<td colspan=\"2\" align=\"center\" valign=\"top\">
					<table width=\"1110\" cellpadding=\"0\" cellspacing=\"0\" class=\"tabelDetailLap\">";
					
	$arrayTd = array( 	array("LAST SAVED",$lastEdit,""),
						array("REFNO",$row['refno'],""), 
						array("VESSEL NAME",$CPublic->konversiQuotes( $row['namakapal'] ),""), 
						array("DATE OF PORT",$dateport,""),
						array("POSITION",$CPublic->konversiQuotes( $row['position'] ),""), 
						array("WEATHER",$row['weather'],""),
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
						array("REMARKS ",$row['remark'],"")
					);
						
	for($i = 0; $i < count($arrayTd); $i++)
	{
		$judul = $arrayTd[$i][0];
		$isi = $arrayTd[$i][1];
		$isiSpan = $arrayTd[$i][2];
		
		$html.= "<tr><td>".$judul." : <a>".$isi."</a> <span style=\"color:#666;\">".$isiSpan."</span></td></td></tr>";
	}

	$html.= "		</table>
				</td>
			</tr>";
	$printBy = "Printed by ".ucfirst($userName)." ".$CPublic->convTglNonDB($CPublic->tglServerWithStrip())." ".substr($CPublic->jamServer(),0,5);
	$html.= "<tr valign=\"bottom\" style=\"position:absolute;bottom:-0%;\">
				  <td height=\"28\" align=\"right\" style=\"font-style:italic;font-family:Arial Narrow;font-size:12px;color:#666;\">
					  ".$printBy."&nbsp;
				  </td>
			  </tr>";
			
	$tpl->Assign("dataList", $html);
}

function nl2br2($string) 
{
	$string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
	return $string;
} 

$tpl->printToScreen();

$CKoneksi->tutupKoneksi($CKoneksi->bukaKoneksi());
?>