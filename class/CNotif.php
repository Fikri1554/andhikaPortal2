<?php
class CNotif
{	
	function CNotif($koneksiMysql)
	{
		$this->koneksi = $koneksiMysql;
	}
	
	function emailSubmitTrans($CReqAtk, $transId, $emailKe, $db, $link) // notif email submit cart to transaction
	{	
		$jmlItem = $CReqAtk->jmlTrans($transId);
		$s = "";
		if($jmlItem > 1)
		{
			$s = "s";
		}
		$subject = "'ATK Request' New Transaction Notification"; 
		
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
							  <tr>
								  <td align=\"left\">
									<b>&nbsp;*****  
									<span style=\"text-decoration:underline;\">".$CReqAtk->detilLoginAtk($CReqAtk->atkTrans("ownerid",$transId),"userfullnm",$db)."</span> 
									&nbsp;has ordered ".$jmlItem." item".$s."
									*****</b>
								</td>
							  </tr> 
							 </table>
						  </td>
					  </tr>
						";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td>
						   <table width=\"400px\" border=0 cellpadding=\"0\" cellspacing=\"0\">
								<tr align=\"center\" bgcolor=\"#E9E9E9\" style=\"font-family:'Arial Narrow';font-weight:bold;font-size:12px;\" height=\"25\">
									<td width=\"35\" style=\"border-bottom-width:1px;border-top-width:1px;border-left-width:1px;border-right-width:1px;border-color:#CCC;border-style:solid;letter-spacing:0px;\">
										No
									</td>
									<td width=\"295\" height=\"20\" style=\"border-bottom-width:1px;border-top-width:1px;border-left-width:0;border-right-width:0;border-color:#CCC;border-style:solid;letter-spacing:0px;\">
										Item Name ( Trans Id = ".$transId." )
									</td>
									<td width=\"70\" style=\"border-bottom-width:1px;border-top-width:1px;border-left-width:1px;border-right-width:1px;border-color:#CCC;border-style:solid;letter-spacing:0px;\">
										Qty
									</td>
								</tr>
								".$CReqAtk->detailNotifEmail($transId)."
						   	</table>
						  </td>
						</tr>";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td>
							To respon this Transaction, please check <a href=\"".$link."/\">Andhika Portal</a>.
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
		
		$headers = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\n";
		$headers .= "X-Priority: 3\n";
		$headers .= "X-MSMail-Priority: Normal\n";
		$headers .= "X-Mailer: php\n";
		$headers .= "From: noreply@andhika.com\n"; 
				
		mail($emailKe, $subject, $isiMessage, $headers);
		//mail("ichsannur.sogi@andhika.com", $subject, $isiMessage, $headers);
	}
	
	function emailNewReq($CReqAtk, $reqId, $emailKe, $db, $link) // notif email submit Request New Item
	{		
		$subject = "'ATK Request' Request New Item Notification"; 
		
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
							  <tr>
								  <td align=\"left\">
									<b>&nbsp;*****  
									<span style=\"text-decoration:underline;\">".$CReqAtk->detilLoginAtk($CReqAtk->atkReq("ownerid", $reqId), "userfullnm", $db)."</span> 
									has requested New Item *****</b>
								</td>
							  </tr> 
							 </table>
						  </td>
					  </tr>
						";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td style=\"border:1px #CCC solid;\">
						   <table width=\"400px\" border=0 cellpadding=\"0\" cellspacing=\"0\" style=\"font-family:'Arial Narrow';font-size:12px;\">
								".$CReqAtk->detailNotifEmailRequest("req", $reqId)."
							</table>
						  </td>
						</tr>";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td>
							To respon this Request, please check <a href=\"".$link."/\">Andhika Portal</a>.
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
		
		$headers = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\n";
		$headers .= "X-Priority: 3\n";
		$headers .= "X-MSMail-Priority: Normal\n";
		$headers .= "X-Mailer: php\n";
		$headers .= "From: noreply@andhika.com\n"; 
				
		mail($emailKe, $subject, $isiMessage, $headers);
		//mail("ichsannur.sogi@andhika.com", $subject, $isiMessage, $headers);
	}
	
	function emailAprvTrans($jenis, $CReqAtk, $transId, $emailKe, $date, $db, $link) // notif email Approval Transaction
	{	
		if($jenis == "Approved")
		{ 
			$subject = "'ATK Request' Approved Transaction Notification";
			$ket = "<b>&nbsp;*****  Your order at 
					<span style=\"text-decoration:underline;\">".$date."</span> 
					has been Approved by HRGA  *****</b>";
			$aprvComp = "Approved";
			$ket1 = "To respon this Approval Transaction, please check <a href=\"".$link."/\">Andhika Portal</a>."; 
		}
		if($jenis == "Completed")
		{ 
			$subject = "'ATK Request' Completed Transaction Notification"; 
			$ket = "<b>&nbsp;*****  
					<span style=\"text-decoration:underline;\">".$CReqAtk->detilLoginAtk($CReqAtk->atkTrans("ownerid", $transId), "userfullnm", $db)."'s</span> order at 
					<span style=\"text-decoration:underline;\">".$date."</span> 
					&nbsp;has been Completed  *****</b>";
			$aprvComp = "Received";
			$ket1 = "To see more detail Completed Transaction, please check <a href=\"".$link."/\">Andhika Portal</a>.";
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
							  <tr>
								  <td align=\"left\">
									".$ket."
								</td>
							  </tr> 
							 </table>
						  </td>
					  </tr>
						";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td>
						   <table width=\"400px\" border=0 cellpadding=\"0\" cellspacing=\"0\">
								<tr align=\"center\" bgcolor=\"#E9E9E9\" style=\"font-family:'Arial Narrow';font-weight:bold;font-size:12px;\" height=\"25\">
									<td width=\"35\" style=\"border-bottom-width:1px;border-top-width:1px;border-left-width:1px;border-right-width:1px;border-color:#CCC;border-style:solid;letter-spacing:0px;\">
										No
									</td>
									<td width=\"225\" height=\"20\" style=\"border-bottom-width:1px;border-top-width:1px;border-left-width:0;border-right-width:0;border-color:#CCC;border-style:solid;letter-spacing:0px;\">
										Item Name
									</td>
									<td width=\"70\" style=\"border-bottom-width:1px;border-top-width:1px;border-left-width:1px;border-right-width:0px;border-color:#CCC;border-style:solid;letter-spacing:0px;\">
										Qty
									</td>
									<td width=\"70\" style=\"border-bottom-width:1px;border-top-width:1px;border-left-width:1px;border-right-width:1px;border-color:#CCC;border-style:solid;letter-spacing:0px;\">
										".$aprvComp." Qty
									</td>
								</tr>
								".$CReqAtk->detailNotifEmailAprvTrans($transId)."
						   	</table>
						  </td>
						</tr>";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td>
							".$ket1."
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
		
		$headers = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\n";
		$headers .= "X-Priority: 3\n";
		$headers .= "X-MSMail-Priority: Normal\n";
		$headers .= "X-Mailer: php\n";
		$headers .= "From: noreply@andhika.com\n"; 
				
		mail($emailKe, $subject, $isiMessage, $headers);
		//mail("ichsannur.sogi@andhika.com", $subject, $isiMessage, $headers);
	}
	
	function emailCancelTrans($jenis, $CReqAtk, $transId, $emailKe, $date, $db) // notif email Approval Transaction
	{	
		$subject = "'ATK Request' Cancel Transaction Notification";
		$ket = "<b>&nbsp;*****  Your order below, at 
				<span style=\"text-decoration:underline;\">".$date."</span> 
				has been Canceled  *****</b>";
		$aprvComp = "Approved";
		$ket1 = "Contact HRGA for more information."; 
		
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
							  <tr>
								  <td align=\"left\">
									".$ket."
								</td>
							  </tr> 
							 </table>
						  </td>
					  </tr>
						";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td>
						   <table width=\"400px\" border=0 cellpadding=\"0\" cellspacing=\"0\">
								<tr align=\"center\" bgcolor=\"#E9E9E9\" style=\"font-family:'Arial Narrow';font-weight:bold;font-size:12px;\" height=\"25\">
									<td width=\"35\" style=\"border-bottom-width:1px;border-top-width:1px;border-left-width:1px;border-right-width:1px;border-color:#CCC;border-style:solid;letter-spacing:0px;\">
										No
									</td>
									<td width=\"225\" height=\"20\" style=\"border-bottom-width:1px;border-top-width:1px;border-left-width:0;border-right-width:0;border-color:#CCC;border-style:solid;letter-spacing:0px;\">
										Item Name
									</td>
									<td width=\"70\" style=\"border-bottom-width:1px;border-top-width:1px;border-left-width:1px;border-right-width:1px;border-color:#CCC;border-style:solid;letter-spacing:0px;\">
										Qty
									</td>
								</tr>
								".$CReqAtk->detailNotifEmailAprvTrans($transId)."
						   	</table>
						  </td>
						</tr>";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td>
							".$ket1."
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
		
		$headers = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\n";
		$headers .= "X-Priority: 3\n";
		$headers .= "X-MSMail-Priority: Normal\n";
		$headers .= "X-Mailer: php\n";
		$headers .= "From: noreply@andhika.com\n"; 
				
		mail($emailKe, $subject, $isiMessage, $headers);
		//mail("ichsannur.sogi@andhika.com", $subject, $isiMessage, $headers);
	}
	
	function emailAprvReq($CReqAtk, $reqId, $dateReq, $emailKe, $link)
	{
		$subject = "'ATK Request' Completed Request New Item Notification"; 
		
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
							  <tr>
								  <td align=\"left\">
									<b>&nbsp;*****  Your Request at
									<span style=\"text-decoration:underline;\">".$dateReq."</span> 
									has been Available *****</b>
								</td>
							  </tr> 
							 </table>
						  </td>
					  </tr>
						";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr><td>Your Request :</td></tr>
					   <tr height=\"5\"><td></td></tr>
					   <tr>
						<td style=\"border:1px #CCC solid;\">
						   <table width=\"400px\" border=0 cellpadding=\"0\" cellspacing=\"0\" style=\"font-family:'Arial Narrow';font-size:12px;\">
								".$CReqAtk->detailNotifEmailRequest("req", $reqId)."
							</table>
						  </td>
						</tr>
						<tr height=\"10\"><td></td></tr>
						<tr><td>Administrator Give :</td></tr>
						<tr height=\"5\"><td></td></tr>
					    <tr>
						<td style=\"border:1px #CCC solid;\">
						   <table width=\"400px\" border=0 cellpadding=\"0\" cellspacing=\"0\" style=\"font-family:'Arial Narrow';font-size:12px;\">
								".$CReqAtk->detailNotifEmailRequest("give", $reqId)."
							</table>
						  </td>
						</tr>
						<tr height=\"10\"><td></td></tr>
						";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td>
							To see more, please check <a href=\"".$link."/\">Andhika Portal</a>.
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
		
		$headers = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\n";
		$headers .= "X-Priority: 3\n";
		$headers .= "X-MSMail-Priority: Normal\n";
		$headers .= "X-Mailer: php\n";
		$headers .= "From: noreply@andhika.com\n";
		
		mail($emailKe, $subject, $isiMessage, $headers);
		//mail("ichsannur.sogi@andhika.com", $subject, $isiMessage, $headers);
	}
	
	function emailCancelReq($CReqAtk, $reqId, $dateReq, $emailKe) // notif email submit Request New Item
	{		
		$subject = "'ATK Request' Cancel Request New Item Notification"; 
		
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
							  <tr>
								  <td align=\"left\">
									<b>&nbsp;*****  Your Request below, at
									<span style=\"text-decoration:underline;\">".$dateReq."</span> 
									has been Canceled *****</b>
								</td>
							  </tr> 
							 </table>
						  </td>
					  </tr>
						";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td style=\"border:1px #CCC solid;\">
						   <table width=\"400px\" border=0 cellpadding=\"0\" cellspacing=\"0\" style=\"font-family:'Arial Narrow';font-size:12px;\">
								".$CReqAtk->detailNotifEmailRequest("req", $reqId)."
							</table>
						  </td>
						</tr>";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td>
							Contact HRGA for more information.
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
		
		$headers = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\n";
		$headers .= "X-Priority: 3\n";
		$headers .= "X-MSMail-Priority: Normal\n";
		$headers .= "X-Mailer: php\n";
		$headers .= "From: noreply@andhika.com\n"; 
				
		mail($emailKe, $subject, $isiMessage, $headers);
		//mail("ichsannur.sogi@andhika.com", $subject, $isiMessage, $headers);
	}
	
	function emailLessStock($CReqAtk, $emailKe, $date, $bln, $thn, $jml)
	{
		$subject = "'ATK Request' Less Stock Notification"; 
		
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
							  <tr>
								  <td align=\"left\">
									<!--<b>&nbsp;*****  There are ".$jml." less stock at 
									<span style=\"text-decoration:underline;\">".$date."</span> 
									*****</b>-->
									<b>&nbsp;*****  Please check, there are some items below Min. Stock  *****</b>
								</td>
							  </tr> 
							 </table>
						  </td>
					  </tr>
						";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td>
						   <table width=\"400px\" border=0 cellpadding=\"0\" cellspacing=\"0\">
								<tr align=\"center\" bgcolor=\"#E9E9E9\" style=\"font-family:'Arial Narrow';font-weight:bold;font-size:12px;\" height=\"25\">
									<td width=\"35\" style=\"border-bottom-width:1px;border-top-width:1px;border-left-width:1px;border-right-width:1px;border-color:#CCC;border-style:solid;letter-spacing:0px;\">
										No
									</td>
									<td width=\"225\" height=\"20\" style=\"border-bottom-width:1px;border-top-width:1px;border-left-width:0;border-right-width:0;border-color:#CCC;border-style:solid;letter-spacing:0px;\">
										Item Name
									</td>
									<td width=\"70\" style=\"border-bottom-width:1px;border-top-width:1px;border-left-width:1px;border-right-width:0px;border-color:#CCC;border-style:solid;letter-spacing:0px;\">
										Minimum Stock
									</td>
									<td width=\"70\" style=\"border-bottom-width:1px;border-top-width:1px;border-left-width:1px;border-right-width:1px;border-color:#CCC;border-style:solid;letter-spacing:0px;\">
										Now Stock
									</td>
								</tr>
								".$CReqAtk->detailNotifEmailLessStock($bln, $thn)."
						   	</table>
						  </td>
						</tr>";
		$isiMessage.= "<tr height=\"10\"><td></td></tr>";
		$isiMessage.= "<tr>
						<td>
							For more information about less stock, please check <a href=\"".$link."/\">Andhika Portal</a>.
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
		
		$headers = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\n";
		$headers .= "X-Priority: 3\n";
		$headers .= "X-MSMail-Priority: Normal\n";
		$headers .= "X-Mailer: php\n";
		$headers .= "From: noreply@andhika.com\n"; 
				
		mail($emailKe, $subject, $isiMessage, $headers);
		//mail("ichsannur.sogi@andhika.com", $subject, $isiMessage, $headers);
	}
	
	function notifDesktop($koneksiOdbc, $koneksiOdbcId, $notesDt, $notes, $notesTo, $addUsrdt)
	{
		/*$notesDt = "01/29/2015 15:10:45"; // bln/tgl/thn
		$notes = "TES NOTIFIKASI";*/
		$notesFrom = "00000";
		/*$notesTo = "00879"; // empNo
		$addUsrdt = ""; //SGI#15:30#15/01/2015*/
		$query = $koneksiOdbc->odbcExec($koneksiOdbcId, "INSERT INTO HRsys..tblRemindMe (notesdt, notes, notesfrom, notesto, addusrdt) VALUES ('".$notesDt."', '".$notes."', '".$notesFrom."', '".$notesTo."', '".$addUsrdt."');");
	}
}
?>