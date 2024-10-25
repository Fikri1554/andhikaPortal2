<?php
class CLaporanCari
{	
	function CLaporanCari($koneksiMysql, $koneksiOdbc, $koneksiOdbcId, $CInvReg, $CPublic)
	{
		$this->koneksi = $koneksiMysql;
		$this->CInvreg = $CInvReg;
		$this->CPublic = $CPublic;
	}
	
	function printResult($get)
	{
		$cariBerdasarkanGet = $get['cariBerdasarkan'];
		$teksCariGet = $get['teksCari'];
		$startDateGet = $this->CPublic->convTglDB($_GET['startDate']);
		$endDateGet = $this->CPublic->convTglDB($get['endDate']);
		
		$searchBy = "";
		$keywords = $teksCariGet;
		$paramCari = "";
		
		if($cariBerdasarkanGet == "company")
		{
			$searchBy = "COMPANY / BILLING";
			$paramCari = "companyname LIKE '%".$teksCariGet."%' AND";
		}
		if($cariBerdasarkanGet == "batchno")
		{
			$searchBy = "ENTRY DATE";
			$keywords = $_GET['startDate']." to ".$get['endDate'];
			
			$startDateGetBatch = str_replace("-","",$startDateGet); // date yang dihilangkan garis strip nya ("-") agar bisa sama dengan batchno
			$endDateGetBatch = str_replace("-","",$endDateGet);
		 
			$paramCari = "batchno >= ".$startDateGetBatch." AND batchno <= ".$endDateGetBatch." AND";
            if($startDateGetBatch == "")
            {
                $paramCari = "batchno <= ".$endDateGetBatch." AND";
				$keywords = "... to ".$get['endDate'];
            }
            if($endDateGetBatch == "--")
            {
                $paramCari = "batchno >= ".$startstartDateGetBatchDateGet." AND";
				$keywords = $_GET['startDate']." to ...";
            }
		}
		if($cariBerdasarkanGet == "mailInvDate")
		{	
			$searchBy = "MAIL / INVOICE DATE";
			$keywords = $_GET['startDate']." to ".$get['endDate'];
			$paramCari = "DATE(tglinvoice) >= DATE('".$startDateGet."') AND DATE(tglinvoice) < DATE('".$endDateGet."') AND";
            if($startDateGet == "--")
            {
                $paramCari = "DATE(tglinvoice) <= DATE('".$endDateGet."') AND";
				$keywords = "... to ".$get['endDate'];
            }
            if($endDateGet == "--")
            {
                $paramCari = "DATE(tglinvoice) >= DATE('".$startDateGet."') AND";
				$keywords = $_GET['startDate']." to ...";
            }
		}
		if($cariBerdasarkanGet == "senVen")
		{
			$searchBy = "SENDER / VENDOR";
			$paramCari = "sendervendor1 LIKE '%".$teksCariGet."%' OR sendervendor2name LIKE '%".$teksCariGet."%' AND";
			$paramCari = "sendervendor1 LIKE '%".$teksCariGet."%' OR sendervendor2name LIKE '%".$teksCariGet."%' AND";
		}
		if($cariBerdasarkanGet == "mailInvNo")
		{
			$searchBy = "MAIL / INVOICE NO";
			$paramCari = "mailinvno LIKE '%".$teksCariGet."%' AND";
		}
		if($cariBerdasarkanGet == "poNumber")
		{
			$searchBy = "BARCODE / PO NUMBER";
			$paramCari = "barcode LIKE '%".$teksCariGet."%' AND";
		}
		
		$jmlAllData = $this->koneksi->mysqlNRows($this->koneksi->mysqlQuery("SELECT *, DATE(SUBSTRING(addusrdt, 7, 8)) AS entrydate FROM mailinvoice WHERE ".$paramCari." deletests=0 ORDER BY DATE(SUBSTRING(addusrdt, 7, 8)) DESC;", $this->koneksi->bukaKoneksi()));
		$limit = 25;
		$maxPage = ceil($jmlAllData/$limit); // jumlah semua certificate dibagi limit maka didapatlah jumlah halaman
		$pageNum = 1;
		
		$html = "";
		if($jmlAllData <= $limit) // jika jumlah semua certificate kurang dari sama dengan limit yang ditentukan
		{
			$tanggalPrint = "Date Print : ".$this->CPublic->indonesiaDate()." ".substr($this->CPublic->jamServer(), 0, 5);
			$html.= $this->judulAtasResult($searchBy, $keywords, $tanggalPrint);
			$html.= "<tr>
						<td colspan=\"2\" align=\"center\" valign=\"top\">
							<table width=\"1110\" cellpadding=\"0\" cellspacing=\"0\" style=\"font-size:9px;\">";
			$html.= $this->judulTabelResult();
			
			$query = $this->koneksi->mysqlQuery("SELECT *, DATE(SUBSTRING(addusrdt, 7, 8)) AS entrydate FROM mailinvoice WHERE ".$paramCari." deletests=0 ORDER BY DATE(SUBSTRING(addusrdt, 7, 8)) DESC;", $this->koneksi->bukaKoneksi());
			while($row = $this->koneksi->mysqlFetch($query))
			{
				$senderVendor = $row['sendervendor1'];
				if($row['tipesenven'] == "2")
					$senderVendor = $this->CInvreg->detilSenderVendor($row['sendervendor2'], "Acctname");
				$mailInvNo = "&nbsp;";
				if($row['mailinvno'] != "")
					$mailInvNo = $row['mailinvno'];
				$transNo = "&nbsp;";
				if($row['transno'] != "0")
					$transNo = $row['transno'];
				$ack = $this->CPublic->jikaParamSmDgNilai($row['ack'], "1", "YES", "NO");
				$return = $this->CPublic->jikaParamSmDgNilai($row['invret'], "1", "YES", "NO");	
				$outstanding = $this->CPublic->jikaParamSmDgNilai($row['preparepayment'], "Y", "YES", "NO");
				$paid = $this->CPublic->jikaParamSmDgNilai($row['paid'], "Y", "YES", "NO");		
						
				$html.= "<tr align=\"center\" valign=\"bottom\">
								<td class=\"tabelBorderTopRightNull\" height=\"20\">".$row['urutan']."</td>
								<td class=\"tabelBorderTopRightNull\">".$row['batchno']."</td>
								<td class=\"tabelBorderTopRightNull\" align=\"left\">&nbsp;".$senderVendor."</td>
								<td class=\"tabelBorderTopRightNull\">".$row['companyname']."</td>
								<td class=\"tabelBorderTopRightNull\">".$row['unitname']."</td>
								<td class=\"tabelBorderTopRightNull\" align=\"left\">&nbsp;".$mailInvNo."</td>
								<td class=\"tabelBorderTopRightNull\">".$row['barcode']."</td>
								<td class=\"tabelBorderTopRightNull\">".$transNo."</td>
								<td class=\"tabelBorderTopRightNull\">".$ack."</td>
								<td class=\"tabelBorderTopRightNull\">".$return."</td>
								<td class=\"tabelBorderTopRightNull\">".$outstanding."</td>
								<td class=\"tabelBorderTopNull\">".$paid."</td>
							</tr>";
			}
			
			$html.= "		</table>
						</td>
					</tr>";
			$html.= "<tr><td style=\"position:absolute;bottom:-0%;width:99%;right:5%;font:1em sans-serif;\" class=\"tabelBorderAllNull\">Page 1 from 1</td></tr>";
		}
		elseif($jmlAllData > $limit) // jika jumlah semua certificate lebih dari limit yang ditentukan
		{
			for($a=1;$a<=$maxPage;$a++)
			{
				if($a == 1)//jika halaman satu maka nomor mulai dari 1
				{			
					$persenheight = 0;
					$persenheightFooter = 10;
					
					$pagebreak = "";
					$offset = 0;
					$no = $offset + 1;
					$tanggalPrint = "Date Print : ".$this->CPublic->indonesiaDate()." ".substr($this->CPublic->jamServer(), 0, 5);
				}
				else//jika selain halaman satu maka bertambah sesuai jumlah halaman dan urutan
				{
					$persenheight = ($a-1) * 100;
					$persenheightFooter = -((($a-1) * 100)-10);
					
					$pagebreak = "<tr style=\"page-break-after: left\"></tr>";
					$offset = ($a-1) * $limit;
					$no = $offset+1;
					$tanggalPrint = "&nbsp;";
				}
				
				//echo $paramCari." - ".$offset." - ".$limit."<br>";
				$html.= $pagebreak;
				$html.= $this->judulAtasResult($searchBy, $keywords, $tanggalPrint);
				$html.= "<tr>
							<td colspan=\"2\" align=\"center\" valign=\"top\">
								<table width=\"1110\" cellpadding=\"0\" cellspacing=\"0\" style=\"font-size:9px;\">";
				$html.= $this->judulTabelResult();
				$query = $this->koneksi->mysqlQuery("SELECT *, DATE(SUBSTRING(addusrdt, 7, 8)) AS entrydate FROM mailinvoice WHERE ".$paramCari." deletests=0 ORDER BY DATE(SUBSTRING(addusrdt, 7, 8)) DESC LIMIT ".$offset.", ".$limit.";", $this->koneksi->bukaKoneksi());
				while($row = $this->koneksi->mysqlFetch($query))
				{
					$senderVendor = $row['sendervendor1'];
					if($row['tipesenven'] == "2")
						$senderVendor = $this->CInvreg->detilSenderVendor($row['sendervendor2'], "Acctname");
					$compName = "&nbsp;";
					if($row['companyname'] != "")
						$compName = $row['companyname'];
					$unitName = "&nbsp;";
					if($row['unitname'] != "")
						$unitName = $row['unitname'];
					$mailInvNo = "&nbsp;";
					if($row['mailinvno'] != "")
						$mailInvNo = $row['mailinvno'];
					$transNo = "&nbsp;";
					if($row['transno'] != "0")
						$transNo = $row['transno'];
					$ack = $this->CPublic->jikaParamSmDgNilai($row['ack'], "1", "YES", "NO");
					$return = $this->CPublic->jikaParamSmDgNilai($row['invret'], "1", "YES", "NO");	
					$outstanding = $this->CPublic->jikaParamSmDgNilai($row['preparepayment'], "Y", "YES", "NO");
					$paid = $this->CPublic->jikaParamSmDgNilai($row['paid'], "Y", "YES", "NO");		
					
							
					$html.= "<tr align=\"center\" valign=\"bottom\">
									<td class=\"tabelBorderTopRightNull\" height=\"20\">".$row['urutan']."</td>
									<td class=\"tabelBorderTopRightNull\">".$row['batchno']."</td>
									<td class=\"tabelBorderTopRightNull\" align=\"left\">&nbsp;".$senderVendor."</td>
									<td class=\"tabelBorderTopRightNull\">".$compName."</td>
									<td class=\"tabelBorderTopRightNull\">".$unitName."</td>
									<td class=\"tabelBorderTopRightNull\" align=\"left\">&nbsp;".$mailInvNo."</td>
									<td class=\"tabelBorderTopRightNull\">".$row['barcode']."</td>
									<td class=\"tabelBorderTopRightNull\">".$transNo."</td>
									<td class=\"tabelBorderTopRightNull\">".$ack."</td>
									<td class=\"tabelBorderTopRightNull\">".$return."</td>
									<td class=\"tabelBorderTopRightNull\">".$outstanding."</td>
									<td class=\"tabelBorderTopNull\">".$paid."</td>
								</tr>";
				}
				$html.= "	</table>
						</td>
					</tr>";
				$html.= "<tr><td style=\"position:absolute;bottom:-".$persenheight."%;width:99%;right:5%;font:1em sans-serif;\" class=\"tabelBorderAllNull\">Page ".$a." from ".$maxPage."</td></tr>";
			}
		}
		
		return $html;
	}
	
	function judulAtasResult($searchBy, $keywords, $tanggalPrint)
	{
		$html = "";
		$html.= "<tr>
					<td colspan=\"2\" valign=\"top\" style=\"font-size:15px;text-decoration:underline;height:40px;\"><b>PRINT RESULT</b></td>
				</tr>
				<tr>
					<td width=\"7%\">SEARCH BY : </td>
					<td width=\"93%\" style=\"font-style:italic;\">".$searchBy."</td>
				</tr>
				<tr>
					<td>KEYWORDS : </td>
					<td style=\"font-style:italic;\">".$keywords."</td>
				</tr>
				<tr><td height=\"20\" colspan=\"2\" align=\"right\">".$tanggalPrint."&nbsp;</td></tr>";
		return $html;
	}
	
	function judulTabelResult()
	{
		$html = "";
		$html.= "<tr align=\"center\" style=\"font-size:10px;font-weight:bold;background-color:#F7F7F7;\">
					<td width=\"31\" height=\"30\" class=\"tabelBorderRightNull\">SNO</td>
					<td width=\"62\" class=\"tabelBorderRightNull\">BATCHNO</td>
					<td width=\"222\" class=\"tabelBorderRightNull\">SENDER / VENDOR</td>
					<td width=\"152\" class=\"tabelBorderRightNull\">COMPANY</td>
					<td width=\"200\" class=\"tabelBorderRightNull\">UNIT</td>
					<td width=\"99\" class=\"tabelBorderRightNull\">INVOICE NO</td>
					<td width=\"62\" class=\"tabelBorderRightNull\">BARCODE</td>
					<td width=\"60\" class=\"tabelBorderRightNull\">TRANSNO</td>
					<td width=\"56\" class=\"tabelBorderRightNull\">ACKNOW</td>
					<td width=\"50\" class=\"tabelBorderRightNull\">RETURN</td>
					<td width=\"79\" class=\"tabelBorderRightNull\">OUTSTANDING</td>
					<td width=\"35\" class=\"tabelBorderAll\">PAID</td>
				</tr>";
		return $html;
	}
	
	function nl2br2($string) 
	{
		$string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
		return $string;
	} 
	
	function printDetail($get, $CLogin)
	{
		$idMailInvGet = $get['idMailInv'];
		
		$html = "";
		
		$html.= $this->judulAtasResult($searchBy, $keywords, $tanggalPrint);
		$html.= "<tr>
					<td colspan=\"2\" align=\"center\" valign=\"top\">
						<table width=\"1110\" cellpadding=\"0\" cellspacing=\"0\" style=\"font-size:9px;\" class=\"tabelDetailLap\">";
						
		$query = $this->koneksi->mysqlQuery("SELECT *, DATE(SUBSTRING(addusrdt, 7, 8)) AS entrydate FROM mailinvoice WHERE idmailinv = '".$idMailInvGet."' AND deletests=0;", $this->koneksi->bukaKoneksi());
    	$row = $this->koneksi->mysqlFetch($query);
	
		if(substr($row['barcode'], 0, 1) == "A") // DOCUMENT TYPE
		{	$docType = "INVOICE";	}
		if(substr($row['barcode'], 0, 1) == "S")
		{	$docType = "MAIL";	}
		$batchno = $row['batchno']; // BATCHNO
		$receiveOn = $this->CPublic->convTglNonDB( $row['entrydate'] ); //  RECEIVE ON
		$kreditAcc = $row['kreditacc']; // KREDIT ACCOUNT
		$compName = $row['companyname']; // ADDRESSE BILLING COMPANY
		$unitName = $row['unitname']; // ADDRESSE BILLING UNIT
		$barcode = $row['barcode']; // PO NUMBER / BARCODE 
		$invDate = $this->CPublic->convTglNonDB( $row['tglinvoice'] ); // INVOICE DATE 
		$dueDate = $this->CPublic->jikaParamSamaDenganNilai1( $this->CPublic->convTglNonDB( $row['tglexp'] ), "00/00/0000", "-"); //DUE / EXPIRED DATE
		$payTerms = $this->CPublic->jikaParamSamaDenganNilai1( $row['dueday'], "", "-"); //PAY TERMS
		$invNumber = $row['mailinvno'];
		$currency = "(".$row['currency'].")";
		if($row['currency'] == "XXX" || $row['currency'] == "")
		{	$currency = "";	}
		$amount = $currency."&nbsp;".$this->CPublic->jikaKosongStrip(number_format((float)$row['amount'], 2, '.', ','));
		$remark = $row['remark'];
		
		$tglAck = "( ".$this->CPublic->convTglNonDB($row['dateack'])." )" ;	
		if($row['dateack'] == "0000-00-00")
			$tglAck = "&nbsp;";
		$ack = $this->CPublic->jikaParamSmDgNilai($row['ack'], "1", "YES", "NO")."&nbsp;".$tglAck;
		$ackBy = "- BY <a>".$CLogin->detilLogin2($row['ackby'], "userfullnm")."</a>";
		if($row['ackby'] == "")
			$ackBy = "";
		
		$tglRet = "( ".$this->CPublic->convTglNonDB($row['dateret'])." )" ;	
		if($row['dateret'] == "0000-00-00")
				$tglRet = "&nbsp;";
		$return = $this->CPublic->jikaParamSmDgNilai($row['invret'], "1", "YES", "NO")."&nbsp;".$tglRet;	
		$retBy = "- BY <a>".$CLogin->detilLogin2($row['retby'], "userfullnm")."</a>";
		if($row['retby'] == "")
			$retBy = "";
		$ignoreJe = $this->CPublic->jikaParamSmDgNilai($row['ignoreje'], "1", "YES", "NO");
		$approvePay = $this->CPublic->jikaParamSmDgNilai($row['apprpayment'], "1", "YES", "NO");
		if($row['vesname'] != "")
			$vessName = "( ".$row['vescode']." ) ".$row['vesname'];
		$source = $row['source'];
		$debitAcc = $row['debitacc'];
		$subCode = $row['subcode'];
		$desc = $row['description'];
	
		$outstanding = $this->CPublic->jikaParamSmDgNilai($row['preparepayment'], "Y", "YES", "NO");
		$prepBy = "- BY <a>".$CLogin->detilLogin2($row['preppayby'], "userfullnm")."</a>";
		if($row['preppayby'] == "")
			$prepBy = "";
		$transNo = $row['transno'];
		$dateGenTransNo = $this->CPublic->convTglNonDB($row['datepreppay']);
		if($row['datepreppay'] == "0000-00-00")
				$dateGenTransNo = "&nbsp;";
		
		$paid = $this->CPublic->jikaParamSmDgNilai($row['paid'], "Y", "YES", "NO");		
		$paidBy = "- BY <a>".$CLogin->detilLogin2($row['paidby'], "userfullnm")."</a>";
		if($row['paidby'] == "")
			$paidBy = "";
		$payMethod = $row['paytype'];
		if($row['bankcode'] != "")
			$bank = "( ".$row['bankcode']." )&nbsp;".$this->CInvreg->detilBankCode($row['bankcode'], "Acctname");
		$voucher = $row['voucher'];
		$cheque = $row['chequeno'];			
		$tglPaid = $this->CPublic->convTglNonDB($row['datepaid']);	
		if($row['datepaid'] == "0000-00-00")
				$tglPaid = "&nbsp;";
					
		$html.= "<tr><td>DOCUMENT TYPE : <a>".$docType."</a></td></tr>";
		$html.= "<tr><td>BATCHNO : <a>".$batchno."</a></td></tr>";
		$html.= "<tr><td>RECEIVE ON : <a>".$receiveOn."</a></td></tr>";
		$html.= "<tr><td style=\"padding-left:15px;\">KREDIT ACCOUNT : <a>".$kreditAcc."</a></td></tr>";
		$html.= "<tr><td style=\"padding-left:15px;\">ADDRESSE / BILLING <a></a></td></tr>";
		$html.= "<tr><td style=\"padding-left:30px;\">COMPANY : <a>".$compName."</a> </td></tr>";
		$html.= "<tr><td style=\"padding-left:30px;\">UNIT : <a>".$unitName."</a> </td></tr>";
		$html.= "<tr><td style=\"padding-left:15px;\">PO NUMBER / BARCODE : <a>".$barcode."</a></td></tr>";
		$html.= "<tr><td style=\"padding-left:15px;\">INVOICE DATE : <a>".$invDate."</a></td></tr>";
		$html.= "<tr><td style=\"padding-left:15px;\">DUE / EXPIRED DATE : <a>".$dueDate."</a></td></tr>";
		$html.= "<tr><td style=\"padding-left:15px;\">PAY TERMS / DUE DAYS : <a>".$payTerms."</a></td></tr>";
		$html.= "<tr><td style=\"padding-left:15px;\">INVOICE NUMBER: <a>".$invNumber."</a></td></tr>";
		$html.= "<tr><td style=\"padding-left:15px;\">AMOUNT : <a>".$amount."</a></td></tr>";
		$html.= "<tr><td style=\"padding-left:15px;\">REMARKS : <a>".$this->nl2br2( $remark )."</a></td></tr>";				
		$html.= "<tr><td>ACKNOWLEDGE : <a>".$ack."</a> ".$ackBy."</td></tr>";
		
		$html.= "<tr><td>RETURN : <a>".$return."</a> ".$retBy."</td></tr>";
		$html.= "<tr><td style=\"padding-left:15px;\">IGNORE JE : <a>".$ignoreJe."</a> </td></tr>";
		$html.= "<tr><td style=\"padding-left:15px;\">APPROVE PAYMENT : <a>".$approvePay."</a> </td></tr>";
		$html.= "<tr><td style=\"padding-left:15px;\">VESSEL NAME : <a>".$vessName."</a> </td></tr>";
		$html.= "<tr><td style=\"padding-left:15px;\">SOURCE : <a>".$source."</a> </td></tr>";
		$html.= "<tr><td style=\"padding-left:15px;\">DEBIT ACCOUNT	 : <a>".$debitAcc."</a> </td></tr>";
		$html.= "<tr><td style=\"padding-left:15px;\">SUBCODE : <a>".$subCode."</a> </td></tr>";
		$html.= "<tr><td style=\"padding-left:15px;\">DESCRIPTION : <a>".$desc."</a></td></tr>";
		$html.= "<tr><td>OUTSTANDING / PREPARE PAYMENT : <a>".$outstanding."</a> ".$prepBy."</td></tr>";
		$html.= "<tr><td style=\"padding-left:15px;\">TRANS NO : <a>".$transNo."</a></td></tr>";
		$html.= "<tr><td style=\"padding-left:15px;\">DATE GENERATED : <a>".$dateGenTransNo."</a></td></tr>";
		$html.= "<tr><td>PAID : <a>".$paid."</a> ".$paidBy."</td></tr>";
		$html.= "<tr><td style=\"padding-left:15px;\">PAYMENT METHOD : <a>".$payMethod."</a></td></tr>";
		$html.= "<tr><td style=\"padding-left:15px;\">BANK : <a>".$bank."</a></td></tr>";
		$html.= "<tr><td style=\"padding-left:15px;\">VOUCHER NO : <a>".$voucher."</a></td></tr>";
		$html.= "<tr><td style=\"padding-left:15px;\">CHEQUE NO : <a>".$cheque."</a></td></tr>";
		$html.= "<tr><td style=\"padding-left:15px;\">DATE PAID : <a>".$tglPaid."</a></td></tr>";
							
		$html.= "		</table>
					</td>
				</tr>";
		return $html;
	}
	
}
?>