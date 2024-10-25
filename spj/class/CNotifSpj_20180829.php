<?php
class CNotifSpj
{	
	function CNotifSpj($koneksiMysql)
	{
		$this->koneksi = $koneksiMysql;
	}
	
	function headers()
	{
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\n";
		$headers .= "X-Priority: 3\n";
		$headers .= "X-MSMail-Priority: Normal\n";
		$headers .= "X-Mailer: php\n";
		$headers .= "From: noreply@andhika.com\n";
		
		return $headers;
	}

// START -- NOTIFIKASI SPJ FORM =======================================================================================	
	function emailNewForm($CSpj, $CPublic, $formId, $emailKe, $db, $link, $ket) // notif email submit new SPJ form
	{
		$subject = "SPJ App -- New SPJ Form"; 
		$notes = "submitted";
		if($ket == "rev")
		{
			$subject = "SPJ App -- SPJ Form Revise";
			$notes = "revised current"; 
		}
		
		$isiMessage.= "<table border=0 width=\"100%\" style=\"font-family:Arial Narrow;\" cellpadding=\"0\" cellspacing=\"0\"> 
					  <tr>
						  <td>
							  *************************************************<br>
							  PLEASE DO NOT REPLY THIS EMAIL!<br>
							  *************************************************
							  <br><br>
						  </td>
					  </tr>
					  <tr>
						  <td align=\"center\">
							 <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
							 <tr>
								  <td align=\"center\">&nbsp;</td>
							  </tr>
							  <!-- JUDUL -->
							  <tr>
								  <td align=\"left\">
									<b>&nbsp;*****  
									<span style=\"text-decoration:underline;\">".$CSpj->detilForm($formId, "ownername")."</span>
									has ".$notes." SPJ Request Form. It requires your Approval.
									*****</b>
									".$emailKe."
								</td>
							  </tr> 
							 </table>
						  </td>
					  </tr>
						";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td style=\"border:#CCC solid 1px;font-family:Tahoma;font-size:12px;color:#333;\">
						   <table width=\"700px\" border=0 cellpadding=\"0\" cellspacing=\"0\">
								".$CSpj->detilFormEmail($formId, $CPublic, $db)."
						   	</table>
						  </td>
						</tr>";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td>
							To respon this Form, please check <a href=\"".$link."/\">Andhika Portal</a>->Application->SPJ.
						</td>
					    </tr>";
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
		
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\n";
		$headers .= "X-Priority: 3\n";
		$headers .= "X-MSMail-Priority: Normal\n";
		$headers .= "X-Mailer: php\n";
		$headers .= "From: noreply@andhika.com\n";
		//$headers .= "Cc: sri.ratnawati@andhika.com\n";
		$headers .= "Cc: arifan.hidayat@andhika.com\n";
				
		//mail($emailKe, $subject, $isiMessage, $headers);
		mail("arifan.hidayat@andhika.com", $subject, $isiMessage, $headers);
		/*mail("hendra.roesli@andhika.com", $subject, $isiMessage, $headers));
		mail("arifan.hidayat@andhika.com", $subject, $isiMessage, $headers);*/
		
		//Administrator
		mail("fikri.pahlevi@andhika.com", $subject, $isiMessage, $headers);
		mail("dian.karlina@andhika.com", $subject, $isiMessage, $headers);
	}
	
	function emailAprvForm($CSpj, $CPublic, $formId, $emailKe, $db, $link, $ket) // notif email Approval SPJ Form
	{	
		$text = "";
		
		if($ket == "owner")
		{
			$text = "Your SPJ Request Form has been Approved and will be processed by HR GA";
		}
		if($ket == "kadivHr")
		{
			$text = "<span style=\"text-decoration:underline;\">".$CSpj->detilForm($formId, "ownername")."'s</span> SPJ Request Form has been approved. It requires your Acknowledgement to proccess it.";
		}
		$subject = "SPJ App -- SPJ Form Approved"; 
		
		$isiMessage.= "<table border=0 width=\"100%\" style=\"font-family:Arial Narrow;\" cellpadding=\"0\" cellspacing=\"0\"> 
					  <tr>
						  <td>
							  *************************************************<br>
							  PLEASE DO NOT REPLY THIS EMAIL!<br>
							  *************************************************
							  <br><br>
						  </td>
					  </tr>
					  <tr>
						  <td align=\"center\">
							 <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
							 <tr>
								  <td align=\"center\">&nbsp;</td>
							  </tr>
							  <!-- JUDUL -->
							  <tr>
								  <td align=\"left\">
									<b>&nbsp;*****
									".$text."
									*****</b>
									".$emailKe."
								</td>
							  </tr> 
							 </table>
						  </td>
					  </tr>
						";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td style=\"border:#CCC solid 1px;font-family:Tahoma;font-size:12px;color:#333;\">
						   <table width=\"700px\" border=0 cellpadding=\"0\" cellspacing=\"0\">
								".$CSpj->detilFormEmail($formId, $CPublic, $db)."
						   	</table>
						  </td>
						</tr>
						<tr height=\"15\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td align=\"center\">
							<table style=\"font-family:Tahoma;font-size:12px;color:#333;\"  cellpadding=\"0\" cellspacing=\"3\" width=\"98%\">
								".$CSpj->dataAprovalForm($formId, $CPublic, $db)."
							</table>
						  </td>
						</tr>";
		$isiMessage.= "<tr height=\"15\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td>
							For more detail, please check <a href=\"".$link."/\">Andhika Portal</a>->Application->SPJ.
						</td>
					    </tr>";
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
				
		//mail($emailKe, $subject, $isiMessage, $this->headers());
		mail("arifan.hidayat@andhika.com", $subject, $isiMessage, $this->headers());
		/*mail("hendra.roesli@andhika.com", $subject, $isiMessage, $this->headers());
		mail("arifan.hidayat@andhika.com", $subject, $isiMessage, $this->headers());*/
		
		//Administrator
		mail("fikri.pahlevi@andhika.com", $subject, $isiMessage, $headers);
		mail("dian.karlina@andhika.com", $subject, $isiMessage, $headers);
	}
	
	function emailAckForm($CSpj, $CPublic, $CEmployee, $formId, $emailKe, $db, $link, $ket) // notif email Approval SPJ Form
	{	
		$subject = "SPJ App -- SPJ Form Acknowledged"; 
		
		$isiMessage.= "<table border=0 width=\"100%\" style=\"font-family:Arial Narrow;\" cellpadding=\"0\" cellspacing=\"0\"> 
					  <tr>
						  <td>
							  *************************************************<br>
							  PLEASE DO NOT REPLY THIS EMAIL!<br>
							  *************************************************
							  <br><br>
						  </td>
					  </tr>
					  <tr>
						  <td align=\"center\">
							 <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
							 <tr>
								  <td align=\"center\">&nbsp;</td>
							  </tr>
							  <!-- JUDUL -->
							  <tr>
								  <td align=\"left\">
									<b>&nbsp;*****
									Please prepare <span style=\"text-decoration:underline;\">".$CSpj->detilForm($formId, "ownername")."'s</span> SPJ.  
									*****</b>
									".$emailKe."
								</td>
							  </tr> 
							 </table>
						  </td>
					  </tr>
						";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td style=\"border:#CCC solid 1px;font-family:Tahoma;font-size:12px;color:#333;\">
						   <table width=\"700px\" border=0 cellpadding=\"0\" cellspacing=\"0\">
								".$CSpj->detilFormEmail($formId, $CPublic, $db)."
						   	</table>
						  </td>
						</tr>
						<tr height=\"15\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td align=\"center\">
							<table style=\"font-family:Tahoma;font-size:12px;color:#333;\"  cellpadding=\"0\" cellspacing=\"3\" width=\"98%\">
								".$CSpj->dataAprovalForm($formId, $CPublic, $db)."
							</table>
						  </td>
						</tr>";
		$isiMessage.= "<tr height=\"15\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td>
							For more detail, please check <a href=\"".$link."/\">Andhika Portal</a>->Application->SPJ.
						</td>
					    </tr>";
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
				
		//mail($emailKe, $subject, $isiMessage, $this->headers());
		mail("arifan.hidayat@andhika.com", $subject, $isiMessage, $this->headers());
		/*mail("hendra.roesli@andhika.com", $subject, $isiMessage, $this->headers());
		mail("arifan.hidayat@andhika.com", $subject, $isiMessage, $this->headers());*/
		
		//Administrator
		mail("fikri.pahlevi@andhika.com", $subject, $isiMessage, $headers);
		mail("dian.karlina@andhika.com", $subject, $isiMessage, $headers);
	}
	
	function emailComplForm($CSpj, $CPublic, $formId, $emailKe, $db, $link, $ket) // notif email Approval SPJ Form
	{		
		$subject = "SPJ App -- SPJ Form Completed"; 
		
		$isiMessage.= "<table border=0 width=\"100%\" style=\"font-family:Arial Narrow;\" cellpadding=\"0\" cellspacing=\"0\"> 
					  <tr>
						  <td>
							  *************************************************<br>
							  PLEASE DO NOT REPLY THIS EMAIL!<br>
							  *************************************************
							  <br><br>
						  </td>
					  </tr>
					  <tr>
						  <td align=\"center\">
							 <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
							 <tr>
								  <td align=\"center\">&nbsp;</td>
							  </tr>
							  <!-- JUDUL -->
							  <tr>
								  <td align=\"left\">
									<b>&nbsp;*****
									Your SPJ Form has been Completed.
									*****</b>
									".$emailKe."
								</td>
							  </tr> 
							 </table>
						  </td>
					  </tr>
						";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
							<td style=\"font-family:Tahoma;font-size:12px;font-weight:bold;color:#333;\"> SPJ No. : ".$CSpj->detilForm($formId, "spjno")."
						</tr>
						<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td style=\"border:#CCC solid 1px;font-family:Tahoma;font-size:12px;color:#333;\">
						   <table width=\"700px\" border=0 cellpadding=\"0\" cellspacing=\"0\">
								".$CSpj->detilFormEmail($formId, $CPublic, $db)."
						   	</table>
						  </td>
						</tr>
						<tr height=\"15\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td align=\"center\">
							<table style=\"font-family:Tahoma;font-size:12px;color:#333;\"  cellpadding=\"0\" cellspacing=\"3\" width=\"98%\">
								".$CSpj->dataAprovalForm($formId, $CPublic, $db)."
							</table>
						  </td>
						</tr>";
		$isiMessage.= "<tr height=\"15\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td>
							For more detail and information, please check <a href=\"".$link."/\">Andhika Portal</a>->Application->SPJ and contact HR GA.
						</td>
					    </tr>";
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
				
		//mail($emailKe, $subject, $isiMessage, $this->headers());
		mail("arifan.hidayat@andhika.com", $subject, $isiMessage, $this->headers());
		/*mail("hendra.roesli@andhika.com", $subject, $isiMessage, $this->headers());
		mail("arifan.hidayat@andhika.com", $subject, $isiMessage, $this->headers());*/
		
		//Administrator
		mail("fikri.pahlevi@andhika.com", $subject, $isiMessage, $headers);
		mail("dian.karlina@andhika.com", $subject, $isiMessage, $headers);
	}
	
	function emailRevForm($CSpj, $CPublic, $formId, $emailKe, $db, $link, $ket) // notif email submit new SPJ form
	{
		if($ket == "revForm" || $ket == "revFormAck")
		{
			$subject = "SPJ App -- SPJ Form Revise";
			$msg = "Your SPJ Form is need revise to keep process";
		}
		if($ket == "atasan")
		{
			$subject = "SPJ App -- SPJ Form Revise";
			$msg = "<span style=\"text-decoration:underline;\">".$CSpj->detilForm($formId, "ownername")."'s</span> SPJ Form is need revise to keep process";
		}
		if($ket == "cancelForm")
		{
			$subject = "SPJ App -- SPJ Form Canceled";
			$msg = "Your SPJ Form has been cancelled. SPJ Form Request process has been stop.";
		}
		
		$isiMessage.= "<table border=0 width=\"100%\" style=\"font-family:Arial Narrow;\" cellpadding=\"0\" cellspacing=\"0\"> 
					  <tr>
						  <td>
							  *************************************************<br>
							  PLEASE DO NOT REPLY THIS EMAIL!<br>
							  *************************************************
							  <br><br>
						  </td>
					  </tr>
					  <tr>
						  <td align=\"center\">
							 <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
							 <tr>
								  <td align=\"center\">&nbsp;</td>
							  </tr>
							  <!-- JUDUL -->
							  <tr>
								  <td align=\"left\">
									<b>&nbsp;*****  
									".$msg."
									*****</b>
									".$emailKe."
								</td>
							  </tr> 
							 </table>
						  </td>
					  </tr>
						";
		$isiMessage.= "<tr height=\"15\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td style=\"font-family:Tahoma;font-size:12px;color:#333;\">
						   <table width=\"700px\" border=0 cellpadding=\"0\" cellspacing=\"0\">
								".$CSpj->detilRevForm($formId, $ket, $db)."
						   	</table>
						  </td>
						</tr>";
		$isiMessage.= "<tr height=\"5\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td style=\"border:#CCC solid 1px;font-family:Tahoma;font-size:12px;color:#333;\">
						   <table width=\"700px\" border=0 cellpadding=\"0\" cellspacing=\"0\">
								".$CSpj->detilFormEmail($formId, $CPublic, $db)."
						   	</table>
						  </td>
						</tr>";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td>
							For more detail and information, please check <a href=\"".$link."/\">Andhika Portal</a>->Application->SPJ.
						</td>
					    </tr>";
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
				
		//mail($emailKe, $subject, $isiMessage, $this->headers());
		mail("arifan.hidayat@andhika.com", $subject, $isiMessage, $this->headers());
		/*mail("hendra.roesli@andhika.com", $subject, $isiMessage, $this->headers());
		mail("arifan.hidayat@andhika.com", $subject, $isiMessage, $this->headers());*/
		
		//Administrator
		mail("fikri.pahlevi@andhika.com", $subject, $isiMessage, $headers);
		mail("dian.karlina@andhika.com", $subject, $isiMessage, $headers);
	}
	
	function notifDesktop($koneksiOdbc, $koneksiOdbcId, $notesDt, $notes, $notesTo, $addUsrdt)
	{
		$notesFrom = "00000";
		//$query = $koneksiOdbc->odbcExec($koneksiOdbcId, "INSERT INTO HRsys..tblRemindMe (notesdt, notes, notesfrom, notesto, addusrdt) VALUES ('".$notesDt."', 'SPJ App -- ".$notes." Please, check Andhika Portal->Application->SPJ.', '".$notesFrom."', '".$notesTo."', '".$addUsrdt."');");
		
		$query = $koneksiOdbc->odbcExec($koneksiOdbcId, "INSERT INTO HRsys..tblRemindMe (notesdt, notes, notesfrom, notesto, addusrdt) VALUES ('".$notesDt."', 'SPJ App -- ".$notes." Please, check Andhika Portal->Application->SPJ.', '".$notesFrom."', '00879', '".$addUsrdt."');");
		
		/*$query1 = $koneksiOdbc->odbcExec($koneksiOdbcId, "INSERT INTO HRsys..tblRemindMe (notesdt, notes, notesfrom, notesto, addusrdt) VALUES ('".$notesDt."', 'SPJ App -- ".$notes." Please, check Andhika Portal->Application->SPJ.', '".$notesFrom."', '00008', '".$addUsrdt."');");
		
		$query2 = $koneksiOdbc->odbcExec($koneksiOdbcId, "INSERT INTO HRsys..tblRemindMe (notesdt, notes, notesfrom, notesto, addusrdt) VALUES ('".$notesDt."', 'SPJ App -- ".$notes." Please, check Andhika Portal->Application->SPJ.', '".$notesFrom."', '00599', '".$addUsrdt."');");*/
		
		//fikri
		$query1 = $koneksiOdbc->odbcExec($koneksiOdbcId, "INSERT INTO HRsys..tblRemindMe (notesdt, notes, notesfrom, notesto, addusrdt) VALUES ('".$notesDt."', 'SPJ App -- ".$notes." Please, check Andhika Portal->Application->SPJ.', '".$notesFrom."', '00605', '".$addUsrdt."');");
		
		//dian
		$query2 = $koneksiOdbc->odbcExec($koneksiOdbcId, "INSERT INTO HRsys..tblRemindMe (notesdt, notes, notesfrom, notesto, addusrdt) VALUES ('".$notesDt."', 'SPJ App -- ".$notes." Please, check Andhika Portal->Application->SPJ.', '".$notesFrom."', '00870', '".$addUsrdt."');");
	}
		/*$notesDt = "01/29/2015 15:10:45"; // bln/tgl/thn
		$notes = "TES NOTIFIKASI";
		$notesTo = "00879"; // empNo
		$addUsrdt = ""; //SGI#15:30#15/01/2015*/
// END -- NOTIFIKASI SPJ FORM ============================================================================================

// START -- NOTIFIKASI SPJ REPORT ========================================================================================
	function emailNewReport($CSpj, $CPublic, $CEmployee, $reportId, $emailKe, $db, $link, $ket) // notif email submit new SPJ Report
	{
		$formId = $CSpj->detilReport($reportId, "formid");
		$subject = "SPJ App -- New SPJ Report";
		$note = "submitted";
		if($ket == "rev")
		{
			$subject = "SPJ App -- SPJ Report Revise";
			$note = "revised current"; 
		
		}
		
		$isiMessage.= "<table border=0 width=\"100%\" style=\"font-family:Arial Narrow;\" cellpadding=\"0\" cellspacing=\"0\"> 
					  <tr>
						  <td>
							  *************************************************<br>
							  PLEASE DO NOT REPLY THIS EMAIL!<br>
							  *************************************************
							  <br><br>
						  </td>
					  </tr>
					  <tr>
						  <td align=\"center\">
							 <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
							 <tr>
								  <td align=\"center\">&nbsp;</td>
							  </tr>
							  <!-- JUDUL -->
							  <tr>
								  <td align=\"left\">
									<b>&nbsp;*****  
									<span style=\"text-decoration:underline;\">".$CSpj->detilForm($formId, "ownername")."</span>
									has ".$note." SPJ Report. It requires your Acknowledge.
									*****</b>
									".$emailKe."
								</td>
							  </tr> 
							 </table>
						  </td>
					  </tr>
						";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td style=\"border:#CCC solid 1px;font-family:Tahoma;font-size:12px;color:#333;\">
						   <table width=\"700px\" border=0 cellpadding=\"0\" cellspacing=\"0\">
								".$CSpj->detilReportEmail($reportId, $CPublic, $CEmployee, $db)."
						   	</table>
						  </td>
						</tr>";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td align=\"center\">
							<table style=\"font-family:Tahoma;font-size:12px;color:#333;\" cellpadding=\"0\" cellspacing=\"0\" width=\"98%\">
								<tr align=\"left\" height=\"25px\">
									<td width=\"34%\" colspan=\"2\" align=\"center\" style=\"border:#CCC solid 1px;\">
										<b>Deskripsi</b>
									</td>
									<td width=\"15%\" style=\"color:#000080;\" align=\"center\" style=\"border:#CCC solid;border-width:1px 1px 1px 0;\">
										<b>IDR</b>
									</td>
									<td width=\"11%\" style=\"color:#000080;\" align=\"center\" style=\"border:#CCC solid;border-width:1px 1px 1px 0;\">
										<b>USD</b>
									</td>
									<td width=\"40%\">
										&nbsp;
									</td>
								</tr>
								".$CSpj->dataDetilAproval($reportId, $CPublic, $db)."
							</table>
						  </td>
						</tr>";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td>
							For more detail, please check <a href=\"".$link."/\">Andhika Portal</a>->Application->SPJ.
						</td>
					   </tr>";
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
				
		//mail($emailKe, $subject, $isiMessage, $this->headers());
		mail("arifan.hidayat@andhika.com", $subject, $isiMessage, $this->headers());
		/*mail("hendra.roesli@andhika.com", $subject, $isiMessage, $this->headers());
		mail("arifan.hidayat@andhika.com", $subject, $isiMessage, $this->headers());*/
		
		//Administrator
		mail("fikri.pahlevi@andhika.com", $subject, $isiMessage, $headers);
		mail("dian.karlina@andhika.com", $subject, $isiMessage, $headers);
	}
	
	function emailAckReport($CSpj, $CPublic, $CEmployee, $reportId, $emailKe, $db, $link, $ket) // notif email Ack SPJ Report
	{
		$formId = $CSpj->detilReport($reportId, "formid");
		$subject = "SPJ App -- SPJ Report Acknowledged"; 
		$note = "";
		//untuk Acknowledged
		if($ket == "owner")
		{
			$text = "Your SPJ Report has been Acknowledged and will be processed by HR GA Dept";
			$note.= "<tr height=\"28px\">
						<td style=\"font-family:Tahoma;font-size:12px;color:#333;\">
						   <table width=\"700px\" border=0 cellpadding=\"0\" cellspacing=\"0\">
								Submit supporting documents to HRGA manually, please.
						   	</table>
						  </td>
						</tr>";
		}
		if($ket == "admin")
		{
			$text = "<span style=\"text-decoration:underline;\">".$CSpj->detilForm($formId, "ownername")."'s</span> SPJ Report has been Acknowledged. It requires your Check to process it.";
		}
		
		//untuk Checked
		if($ket == "ownerExm")
		{
			$subject = "SPJ App -- SPJ Report Checked"; 
			//$text = "Your SPJ Report has been Checked and will be processed by Finance & Accounting Dept";
			$text = "Your SPJ Report has been Checked by HR&GA Dept. Process has been done.";
		}
		if($ket == "kadivFnc")
		{
			$subject = "SPJ App -- SPJ Report Checked";
			$text = "<span style=\"text-decoration:underline;\">".$CSpj->detilForm($formId, "ownername")."'s</span> SPJ Report has been Checked. It requires your Approval to process it."; 
		}
		
		//untuk Processed
		if($ket == "ownerPrc")
		{
			$subject = "SPJ App -- SPJ Report Completed"; 
			$text = "Your SPJ Report has been Completed";
		}
		
		$isiMessage.= "<table border=0 width=\"100%\" style=\"font-family:Arial Narrow;\" cellpadding=\"0\" cellspacing=\"0\"> 
					  <tr>
						  <td>
							  *************************************************<br>
							  PLEASE DO NOT REPLY THIS EMAIL!<br>
							  *************************************************
							  <br><br>
						  </td>
					  </tr>
					  <tr>
						  <td align=\"center\">
							 <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
							 <tr>
								  <td align=\"center\">&nbsp;</td>
							  </tr>
							  <!-- JUDUL -->
							  <tr>
								  <td align=\"left\">
									<b>&nbsp;*****  
									".$text."
									*****</b>
									".$emailKe."
								</td>
							  </tr> 
							 </table>
						  </td>
					  </tr>
						";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= $note;
		$isiMessage.= "<tr>
						<td style=\"border:#CCC solid 1px;font-family:Tahoma;font-size:12px;color:#333;\">
						   <table width=\"700px\" border=0 cellpadding=\"0\" cellspacing=\"0\">
								".$CSpj->detilReportEmail($reportId, $CPublic, $CEmployee, $db)."
						   	</table>
						  </td>
						</tr>";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td align=\"center\">
							<table style=\"font-family:Tahoma;font-size:12px;color:#333;\" cellpadding=\"0\" cellspacing=\"0\" width=\"98%\">
								<tr align=\"left\" height=\"25px\">
									<td width=\"34%\" colspan=\"2\" align=\"center\" style=\"border:#CCC solid 1px;\">
										<b>Deskripsi</b>
									</td>
									<td width=\"15%\" style=\"color:#000080;\" align=\"center\" style=\"border:#CCC solid;border-width:1px 1px 1px 0;\">
										<b>IDR</b>
									</td>
									<td width=\"11%\" style=\"color:#000080;\" align=\"center\" style=\"border:#CCC solid;border-width:1px 1px 1px 0;\">
										<b>USD</b>
									</td>
									<td width=\"40%\">
										&nbsp;
									</td>
								</tr>
								".$CSpj->dataDetilAproval($reportId, $CPublic, $db)."
							</table>
						  </td>
						</tr>";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td>
							For more detail, please check <a href=\"".$link."/\">Andhika Portal</a>->Application->SPJ.
						</td>
					   </tr>";
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
				
		//mail($emailKe, $subject, $isiMessage, $this->headers());
		mail("arifan.hidayat@andhika.com", $subject, $isiMessage, $this->headers());
		/*mail("hendra.roesli@andhika.com", $subject, $isiMessage, $this->headers());
		mail("arifan.hidayat@andhika.com", $subject, $isiMessage, $this->headers());*/
		
		//Administrator
		mail("fikri.pahlevi@andhika.com", $subject, $isiMessage, $headers);
		mail("dian.karlina@andhika.com", $subject, $isiMessage, $headers);
	}
	
	function emailRevReport($CSpj, $CPublic, $CEmployee, $reportId, $emailKe, $db, $link, $ket) // notif email submit new SPJ Report
	{
		$subject = "SPJ App -- SPJ Report Revise";
		$formId = $CSpj->detilReport($reportId, "formid");
		
		if($ket == "revReport")
		{
			$title = "Your SPJ Report is need revise to keep process ";
		}
		if($ket == "revReportCek" || $ket == "revReportPrcs")
		{
			$title = "<span style=\"text-decoration:underline;\">".$CSpj->detilForm($formId, "ownername")."'s</span> SPJ Report is need revise to keep process";
		}
		
		$isiMessage.= "<table border=0 width=\"100%\" style=\"font-family:Arial Narrow;\" cellpadding=\"0\" cellspacing=\"0\"> 
					  <tr>
						  <td>
							  *************************************************<br>
							  PLEASE DO NOT REPLY THIS EMAIL!<br>
							  *************************************************
							  <br><br>
						  </td>
					  </tr>
					  <tr>
						  <td align=\"center\">
							 <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
							 <tr>
								  <td align=\"center\">&nbsp;</td>
							  </tr>
							  <!-- JUDUL -->
							  <tr>
								  <td align=\"left\">
									<b>&nbsp;*****  
									".$title."
									*****</b>
									".$emailKe."
								</td>
							  </tr> 
							 </table>
						  </td>
					  </tr>
						";
		$isiMessage.= "<tr height=\"15\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td style=\"font-family:Tahoma;font-size:12px;color:#333;\">
						   <table width=\"700px\" border=0 cellpadding=\"0\" cellspacing=\"0\">
								".$CSpj->detilRevReport($reportId, $ket, $db)."
						   	</table>
						  </td>
						</tr>";
		$isiMessage.= "<tr height=\"5\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td style=\"border:#CCC solid 1px;font-family:Tahoma;font-size:12px;color:#333;\">
						   <table width=\"700px\" border=0 cellpadding=\"0\" cellspacing=\"0\">
								".$CSpj->detilReportEmail($reportId, $CPublic, $CEmployee, $db)."
						   	</table>
						  </td>
						</tr>";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td align=\"center\">
							<table style=\"font-family:Tahoma;font-size:12px;color:#333;\" cellpadding=\"0\" cellspacing=\"0\" width=\"98%\">
								<tr align=\"left\" height=\"25px\">
									<td width=\"34%\" colspan=\"2\" align=\"center\" style=\"border:#CCC solid 1px;\">
										<b>Deskripsi</b>
									</td>
									<td width=\"15%\" style=\"color:#000080;\" align=\"center\" style=\"border:#CCC solid;border-width:1px 1px 1px 0;\">
										<b>IDR</b>
									</td>
									<td width=\"11%\" style=\"color:#000080;\" align=\"center\" style=\"border:#CCC solid;border-width:1px 1px 1px 0;\">
										<b>USD</b>
									</td>
									<td width=\"40%\">
										&nbsp;
									</td>
								</tr>
								".$CSpj->dataDetilAproval($reportId, $CPublic, $db)."
							</table>
						  </td>
						</tr>";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td>
							For more detail, please check <a href=\"".$link."/\">Andhika Portal</a>->Application->SPJ.
						</td>
					   </tr>";
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
				
		//mail($emailKe, $subject, $isiMessage, $this->headers());
		mail("arifan.hidayat@andhika.com", $subject, $isiMessage, $this->headers());
		/*mail("hendra.roesli@andhika.com", $subject, $isiMessage, $this->headers());
		mail("arifan.hidayat@andhika.com", $subject, $isiMessage, $this->headers());*/
		
		//Administrator
		mail("fikri.pahlevi@andhika.com", $subject, $isiMessage, $headers);
		mail("dian.karlina@andhika.com", $subject, $isiMessage, $headers);
	}
// END -- NOTIFIKASI SPJ REPORT ==========================================================================================
}
?>