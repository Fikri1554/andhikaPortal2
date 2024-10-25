<?php
class CLaporanPayment
{	
	function CLaporanPayment($koneksiMysql, $koneksiOdbc, $koneksiOdbcId, $CInvReg, $CPublic)
	{
		$this->koneksi = $koneksiMysql;
		$this->CInvreg = $CInvReg;
		$this->CPublic = $CPublic;
	}
	
	function printOutstanding($get)
	{
		//$allIdMailInvGet = "8,33,11,36,12,37,13,38,15,40,17,42,18,43,19,44,20,45,21,46,22,47,23,48,24,49,25,50,26,51,4,27,52,28,53,29,54,30,55,7,31,32,";
		$allIdMailInvGet = $get['allIdMailInv'];
		//echo $allIdMailInvGet;
		$printByGet = $_GET['printBy'];
		$companyGet = $get['company'];
		$fromBarcodeGet = $get['fromBarcode'];
		$toBarcodeGet = $get['toBarcode'];
		$fromDateGet = $get['fromDate'];
		$toDateGet = $get['toDate'];
		
		$limitHeight = 850;//echo $allIdMailInvGet;
		
		$jmlAllHeightModel = $this->jmlAllHeightModel($allIdMailInvGet);
		$maxPage = ceil($jmlAllHeightModel/$limitHeight); // jumlah semua data dibagi limit maka didapatlah jumlah halaman
		$html = "";
		for($a=1;$a<=$maxPage;$a++)
		{
			if($a == 1)//jika halaman satu maka nomor mulai dari 1
			{
				$persenHeight = 0;
				$pagebreak = "";
				
				$batasAwal = ($a * $height1);
				$batasAkhir = ($a * $limitHeight);
				
				$offset = "LIMIT 0, ".$this->cariMaksRowPerHalaman($allIdMailInvGet, $a, $batasAwal, $batasAkhir, "end");
				//echo $offset."<br>";
			}
			if($a > 1)//jika halaman satu maka nomor mulai dari 1
			{
				$persenHeight = ($a-1) * 100;
				$pagebreak = "<tr style=\"page-break-after: left;\"></tr>";
				
				$batasAwal = (($a-1) * $limitHeight);
				$batasAkhir = ($a * $limitHeight);
				
				$offset =  "LIMIT ".$this->cariMaksRowPerHalaman($allIdMailInvGet, $a, $batasAwal, $batasAkhir, "start").", ".$this->cariMaksRowPerHalaman($allIdMailInvGet, $a, $batasAwal, $batasAkhir, "end");
				//echo $offset."<br>";
			}
			
			if($printByGet == "barcode")
			{
				$periodParam = "A00".$fromBarcodeGet." - A00".$toBarcodeGet;
			}
			if($printByGet == "batchno")
			{
				$periodParam = $fromDateGet." - ".$toDateGet;
			}
			
			$heightRow = 20;
			$heightCurr = 20;
			$heightTotalAmt = 30;
			$totalHeight = 0;
			$urutan = 0;
			
			$period = $periodParam;
			//$period = $this->periodDB($allIdMailInvGet, $offset);
			
			$html.= $pagebreak;						
			$html.= "<tr>
						<td height=\"40\" align=\"center\"><span style=\"font-family:Tahoma;font-size:18px;font-weight:bold;\">Incoming Invoice (s)&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;".rtrim($this->CInvreg->detilComp($companyGet, "compname"))."</span></td>
					</tr> 
					<tr>
						<td height=\"25\"><b>Period</b>&nbsp;&nbsp;&nbsp;".$period."</td>
					</tr>";
			$html.= "<tr>
						<td>
							<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
			$html.= "		<tr style=\"font-family:Arial;font-size:12px;font-weight:bold;\" align=\"left\">
								<td width=\"5%\" height=\"18\" class=\"tabelBorderBottomJust\" style=\"border-bottom-width:2px;border-bottom-color:#333;\">Batchno</td>
								<td width=\"7%\" class=\"tabelBorderBottomJust\" style=\"border-bottom-width:2px;border-bottom-color:#333;\" align=\"center\">PO No</td>
								<td width=\"15%\" class=\"tabelBorderBottomJust\" style=\"border-bottom-width:2px;border-bottom-color:#333;\">Invoice No</td>
								<td width=\"7%\" class=\"tabelBorderBottomJust\" style=\"border-bottom-width:2px;border-bottom-color:#333;\">Invoice Date</td>
								<td width=\"22%\" class=\"tabelBorderBottomJust\" style=\"border-bottom-width:2px;border-bottom-color:#333;\">Creditor Name</td>
								<td width=\"30%\" class=\"tabelBorderBottomJust\" style=\"border-bottom-width:2px;border-bottom-color:#333;\">Remarks</td>
								<td width=\"4%\" class=\"tabelBorderBottomJust\" style=\"border-bottom-width:2px;border-bottom-color:#333;\" align=\"center\">Cur</td>
								<td width=\"10%\" class=\"tabelBorderBottomJust\" style=\"border-bottom-width:2px;border-bottom-color:#333;\" align=\"center\">Amount</td>
							</tr>";
			// MEMULAI UNTUK PERULANGAN DATA ROW YANG AKAN DITAMPILKAN tabelBorderBottomJust
			$urutData = 0;
			$query = $this->koneksi->mysqlQuery("SELECT * FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND apprpayment=1 AND preparepayment='N' AND paid='N' AND FIND_IN_SET(idmailinv, '".$allIdMailInvGet."') AND deletests=0 ORDER BY currency ASC, batchno ASC ".$offset.";", $this->koneksi->bukaKoneksi());	
			while($row = $this->koneksi->mysqlFetch($query))
			{
				$urutan++;
				
				$idCekUrutCurrAtas = $this->cekUrutCurr($allIdMailInvGet,  $companyGet, $row['currency'], "atas");
				$idCekUrutCurrBawah = $this->cekUrutCurr($allIdMailInvGet, $companyGet, $row['currency'], "bawah");
				if($row['idmailinv'] == $idCekUrutCurrAtas)
				{
					$totalHeight += $heightCurr;
					$html.= "<tr valign=\"top\" style=\"font-family:'Arial Narrow';font-size:10px;\">
								<td colspan=\"3\" height=\"20\"><u><b>CURRENCY : ".$row['currency']."</b></u></td>
							</tr>";
				}
				
				$amount = $row['amount'];
				$deduc = $row['deduc'];
				$addi = $row['addi'];
				$totalAmountDeduc = (($amount - $deduc) + $addi);
				$invAmount = $this->CPublic->jikaParamSamaDenganNilai1(number_format((float)$totalAmountDeduc, 2, '.', ','), "0.00", "");
				
				
				$classTabelBwh = "tabelBorderAllNull";
				$styleTabelBwh = "";
				if($row['idmailinv'] == $idCekUrutCurrBawah)
				{
					$classTabelBwh = "tabelBorderBottomJust";
					$styleTabelBwh = "border-bottom-color:#333;";
				}
				
				$kreditAccName = $row['kreditaccname'];
				if($row['kreditacc'] == "12300") // JIKA ACCOUNT PAYABLE MAKA TAMPILKAN SENDERVENDOR NAME
				{
					$kreditAccName = $row['sendervendor1'];
				}
				
				$totalHeight += $heightRow;	
				$html.= "<tr valign=\"top\" style=\"font-family:'Arial Narrow';font-size:10px;\">
							<td height=\"20\" class=\"".$classTabelBwh."\" style=\"".$styleTabelBwh."\">".$row['batchno']."</td>
							<td class=\"".$classTabelBwh."\" style=\"".$styleTabelBwh."\" align=\"center\">".$row['barcode']."</td>
							<td class=\"".$classTabelBwh."\" style=\"".$styleTabelBwh."\">".$row['mailinvno']."</td>
							<td class=\"".$classTabelBwh."\" style=\"".$styleTabelBwh."\">".$this->CPublic->convTglNonDB($row['tglinvoice'])."</td>
							<td class=\"".$classTabelBwh."\" style=\"".$styleTabelBwh."\">".$kreditAccName."&nbsp;</td>
							<td class=\"".$classTabelBwh."\" style=\"".$styleTabelBwh."\">".$row['description']."&nbsp;</td>
							<td class=\"".$classTabelBwh."\" style=\"".$styleTabelBwh."\" align=\"center\">".$row['currency']."</td>
							<td class=\"".$classTabelBwh."\" style=\"".$styleTabelBwh."\" align=\"right\">".$invAmount."&nbsp;</td>
						</tr>";
						
				$jmlByCurr = "";
				if($row['idmailinv'] == $idCekUrutCurrBawah)
				{
					$totalHeight += $heightTotalAmt;	
					//$classTabelBwh = "tabelBorderBottomJust";
					$jmlAmountByCurr = $this->jmlAmountByCurr($allIdMailInvGet, $companyGet, $row['currency']);
					$jmlAmountByCurr = $this->CPublic->jikaParamSamaDenganNilai1(number_format((float)$jmlAmountByCurr, 2, '.', ','), "0.00", "");
				
					$jmlByCurr = " <tr valign=\"top\" style=\"font-family:'Arial Narrow';font-size:10px;\">
										<td colspan=\"7\" height=\"30\">&nbsp;</td>
										<td align=\"right\"><b>".$jmlAmountByCurr."&nbsp;</b></td>
									</tr>";	
					$html.=$jmlByCurr;
				}
			}
			$html.= "        </table>
				</td>
			</tr>";
			
			if($a == $maxPage)
			{
				$html.= "<tr style=\"font-family:'Arial Narrow';font-size:12px;\">
							<td align=\"center\" valign=\"bottom\" height=\"20\"><b>Total Invoice(s) : ".$this->jmlAllRow( $allIdMailInvGet, $companyGet )."</b></td>
						</tr>
						<tr>
							<td valign=\"bottom\" height=\"70\">
								<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
								<tr style=\"font-family:'Arial Narrow';font-size:12px;\">
									<td width=\"50%\" align=\"center\"><span style=\"text-decoration:overline;\"><b>Received by / Date</b></span></td>
									<td align=\"center\"><span style=\"text-decoration:overline;\"><b>Prepared by / Date</b></span></td>
								</tr>
								</table>
							</td>
						</tr>";	
			}
			
			$hariEng = $this->CPublic->nmHariSek().", ".ucfirst(strtolower($this->CPublic->detilBulanNamaAngka($this->CPublic->bulanServer(), "eng")))." ".$this->CPublic->zerofill($this->CPublic->tglDayServer(),2).", ".$this->CPublic->tahunServer()." - ".$this->CPublic->jamServer();
			$html.= "<tr>
						<td style=\"position:absolute;bottom:-".$persenHeight."%;width:750px;\" align=\"center\">
							<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"\" style=\"font-size:10px;font-family:'Times New Roman';color:#333;width:100%;\">
							<tr>
								<td width=\"50%\"><i>".$hariEng."</i></td>
								<td width=\"50%\" align=\"right\">PAGE #&nbsp;&nbsp;&nbsp;".$a."</td>
							</tr>
							</table>
						</td>
					</tr>";
		}
		
		return $html;
	}
	
	function periodDB($allIdMailInvGet, $offset)
	{
		$query = $this->koneksi->mysqlQuery("SELECT MIN(batchno) AS minbatchno, MAX(batchno) AS maxbatchno FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND apprpayment=1 AND preparepayment='N' AND paid='N' AND FIND_IN_SET(idmailinv, '".$allIdMailInvGet."') AND deletests=0 ORDER BY batchno ASC ".$offset.";", $this->koneksi->bukaKoneksi());	
		$row = $this->koneksi->mysqlFetch($query);
		
		return $this->CPublic->convBatchnoToTglNonDB($row['minbatchno'])." - ".$this->CPublic->convBatchnoToTglNonDB($row['maxbatchno']);
	}
	
	function jmlAllHeightModel($allIdMailInvGet)
	{
		$urutIsi = 0;					
		$heightRow = 20;
		$heightCurr = 20;
		$heightTotalAmt = 30;
		$totalHeight = 0;
		
		$query = $this->koneksi->mysqlQuery("SELECT * FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND apprpayment=1 AND preparepayment='N' AND paid='N' AND FIND_IN_SET(idmailinv, '".$allIdMailInvGet."') AND deletests=0 ORDER BY currency ASC;");			
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$urutIsi++;	
			$idCekUrutCurrAtas = $this->cekUrutCurr($allIdMailInvGet,  $companyGet, $row['currency'], "atas");
			$idCekUrutCurrBawah = $this->cekUrutCurr($allIdMailInvGet, $companyGet, $row['currency'], "bawah");
			if($row['idmailinv'] == $idCekUrutCurrAtas)
			{
				$totalHeight += $heightCurr;
			}
			$totalHeight += $heightRow;	
			if($row['idmailinv'] == $idCekUrutCurrBawah)
			{
				$totalHeight += $heightTotalAmt;
			}
		}
		return $totalHeight;
	}
	
	function cariMaksRowPerHalaman($allIdMailInvGet, $halamanKe, $batasAwal, $batasAkhir, $aksi)
	{
		$heightRow = 20;
		$heightCurr = 20;
		$heightTotalAmt = 30;
		$totalHeight = 0;
		
		if($halamanKe == 1)
		{
			$urutIsi = 0;	// URUTAN DATA SECARA KESELURUHAN YANG SEHARUSNYA DITAMPILKAN	
			$query = $this->koneksi->mysqlQuery("SELECT * FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND apprpayment=1 AND preparepayment='N' AND paid='N' AND FIND_IN_SET(idmailinv, '".$allIdMailInvGet."') AND deletests=0 ORDER BY currency ASC, batchno ASC;");					
			while($row = $this->koneksi->mysqlFetch($query))
			{
				$urutIsi++;	
				$idCekUrutCurrAtas = $this->cekUrutCurr($allIdMailInvGet,  $companyGet, $row['currency'], "atas");
				$idCekUrutCurrBawah = $this->cekUrutCurr($allIdMailInvGet, $companyGet, $row['currency'], "bawah");
				
				if($row['idmailinv'] == $idCekUrutCurrAtas)
				{	$totalHeight += $heightCurr;	}
				
				$totalHeight += $heightRow;// JIKA TIDAK ADA JUDUL DIVISI NAME ATAU ROW BIASA MAKA TOTAL HEIGHT TAMBAHKAN $HEIGHTROW
				
				if($row['idmailinv'] == $idCekUrutCurrBawah)
				{	$totalHeight += $heightTotalAmt;	}
				
				if($totalHeight >= $batasAwal && $totalHeight < $batasAkhir) 
				{ // TAMMPILKAN DATA YANG BERADA DI ANTARA BATAS AWAL DAN AKHIR BERDASARKAN HEIGHT DATA TSB MASUK APA TIDAK DALAM JANGKAUAN TERSEBUT
					if($aksi == "end") // JIKA AKSI SAMA DENGAN END MAKA TAMPILA DATA TERAKHIR SAJA
						$nilai = $urutIsi;
				}
			}
			if($aksi == "start") // KHUSUS HALAMAN 1 NILAI AWAL ADALAH 0
			{
				$nilai = 0;
			}
		}
		if($halamanKe > 1)
		{
			$urutIsi = 0; // URUTAN DATA SECARA KESELURUHAN YANG SEHARUSNYA DITAMPILKAN
			$urutData = 0;	// URUTAN DARI DATA YANG TAMPIL SAJA BERDASARKAN RANGE HEIGHT
			$query = $this->koneksi->mysqlQuery("SELECT * FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND apprpayment=1 AND preparepayment='N' AND paid='N' AND FIND_IN_SET(idmailinv, '".$allIdMailInvGet."') AND deletests=0 ORDER BY currency ASC, batchno ASC;");	
			while($row = $this->koneksi->mysqlFetch($query))
			{
				$urutIsi++;	
				$idCekUrutCurrAtas = $this->cekUrutCurr($allIdMailInvGet,  $companyGet, $row['currency'], "atas");
				$idCekUrutCurrBawah = $this->cekUrutCurr($allIdMailInvGet, $companyGet, $row['currency'], "bawah");
				
				if($row['idmailinv'] == $idCekUrutCurrAtas)
				{	$totalHeight += $heightCurr;	}
				
				$totalHeight += $heightRow; 
				
				if($row['idmailinv'] == $idCekUrutCurrBawah)
				{	$totalHeight += $heightTotalAmt;	}
				
				if($totalHeight >= $batasAwal && $totalHeight <= $batasAkhir)
				{ // TAMPILKAN DATA YANG BERADA DI ANTARA BARTAS AWAL DAN AKHIR BERDASARKAN HEIGHT DATA TSB MASUK APA TIDAK DALAM JANGKAUAN TERSEBUT
					$urutData++;
					if($aksi == "start") // JIKA AKSI START MAKA TAMPILKAN DATA YANG BERADA DALAM AWAL BARIS YANG BERADA DALAM JANGKAUAN BATAS
					{
						if($urutData == 1)
						{	
							//$nilai = $urutIsi;	
							$nilai = ($urutIsi - 1);	
							//if($halamanKe == 2)
							//{	$nilai = ($urutIsi - 1);	}	
							//	$nilai = ($urutIsi + ($halamanKe-2));	
						}
					}
					if($aksi == "end")
					{	$nilai = $urutData;	}
					//$nilai.= "(".$urutData.")-".$urutIsi.",";
				}
			}
		}
		
		return $nilai;
	}
	
	function printOutstandingOld($get)
	{
		$allIdMailInvGet = $get['allIdMailInv'];
		$printByGet = $_GET['printBy'];
		$fromBarcodeGet = $_GET['fromBarcode'];
		$toBarcodeGet = $_GET['toBarcode'];
		$fromDateGet = $_GET['fromDate'];
		$toDateGet = $_GET['toDate'];
		$fromDateDB = $this->CPublic->convTglDB($fromDateGet);
		$toDateDB = $this->CPublic->convTglDB($toDateGet);
		$companyGet = $get['company'];
		
		$limit = 15; //limit jumlah data mail atau invoice dibawah disposition dari tiap halaman
		$jmlAllRow = $this->jmlAllRow($allIdMailInvGet, $companyGet); 
		$maxPage = ceil( $jmlAllRow/$limit ); // jumlah semua data dibagi limit maka didapatlah jumlah halaman
		$pageNum = 1;
		
		$judulPrintBy = "";
		if($printByGet == "barcode")
		{
			$judulPrintBy = "BARCODE";
			$isiPrintBy = $fromBarcodeGet."&nbsp;&nbsp;-&nbsp;&nbsp;".$toBarcodeGet;
		}
		if($printByGet == "date")
		{
			$judulPrintBy = "DATE ENTRY";
			$isiPrintBy = $fromDateGet."&nbsp;&nbsp;-&nbsp;&nbsp;".$toDateGet;
		}
		if($fromBarcodeGet == "" && $toBarcodeGet == "" && $fromDateGet == "" && $toDateGet == "")
		{
			$judulPrintBy = "";
			$isiPrintBy = "";
		}
		//echo $allIdMailInvGet;
		$html = "";
		for($a=1;$a<=$maxPage;$a++)
		{
			$offset = 0;
			if($a == 1)//jika halaman satu maka nomor mulai dari 1
			{
				$offset = 0;
				$pagebreak = "";
				$persenheight = 0;
				
				
			}
			else//jika selain halaman satu maka bertambah sesuai jumlah halaman dan urutan
			{
				$offset = ($a-1) * $limit; // OFFSET ADALAH DIMULAINYA POTONGAN DATA UTK MASING2 HALAMAN BERDASARKAN LANJUTAN URUTAN DARI DATA HALAMAN SEBELUMNYA
				$pagebreak = "<tr style=\"page-break-after: left\"></tr>";
				$persenheight = ($a-1) * 100;
			}
			
$html.= $pagebreak;
$html.= "<tr>
			<td height=\"40\" align=\"center\"><span style=\"font-family:Tahoma;font-size:18px;font-weight:bold;\">Incoming Invoice (s)&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;".rtrim($this->CInvreg->detilComp($companyGet, "compname"))."</span></td>
		</tr> 
		<tr>
			<td height=\"25\"><span style=\"font-family:Arial;font-size:10px;\"><b>".$judulPrintBy."</b>&nbsp;&nbsp;&nbsp;".$isiPrintBy."</span></td>
		</tr>";
$html.= "<tr>
			<td>
				<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
$html.= "<tr style=\"font-family:Arial;font-size:12px;font-weight:bold;\" align=\"center\">
        	<td width=\"6%\" height=\"18\" class=\"tabelBorderBottomJust\" style=\"border-bottom-width:2px;\">Batchno</td>
            <td width=\"8%\" class=\"tabelBorderBottomJust\" style=\"border-bottom-width:2px;\">PO No</td>
            <td width=\"18%\" class=\"tabelBorderBottomJust\" style=\"border-bottom-width:2px;\">Invoice No</td>
            <td width=\"21%\" class=\"tabelBorderBottomJust\" style=\"border-bottom-width:2px;\">Creditor Name</td>
            <td width=\"34%\" class=\"tabelBorderBottomJust\" style=\"border-bottom-width:2px;\">Remarks</td>
            <td width=\"5%\" class=\"tabelBorderBottomJust\" style=\"border-bottom-width:2px;\">Cur</td>
            <td width=\"8%\" class=\"tabelBorderBottomJust\" style=\"border-bottom-width:2px;\">Amount</td>
        </tr>";

		$urutan = 0;
// MEMULAI UNTUK PERULANGAN DATA ROW YANG AKAN DITAMPILKAN
		$query = $this->koneksi->mysqlQuery("SELECT * FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND apprpayment=1 AND preparepayment='N' AND paid='N' AND FIND_IN_SET(idmailinv, '".$allIdMailInvGet."') AND deletests=0 ORDER BY currency ASC LIMIT ".$offset.", ".$limit.";", $this->koneksi->bukaKoneksi());	
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$urutan++;
			
			$idCekUrutCurrAtas = $this->cekUrutCurr($allIdMailInvGet,  $companyGet, $row['currency'], "atas");
			$idCekUrutCurrBawah = $this->cekUrutCurr($allIdMailInvGet, $companyGet, $row['currency'], "bawah");
			if($idCekUrutCurrAtas == $row['idmailinv'])
			{
$html.= "<tr style=\"font-family:'Arial Narrow';font-size:10px;\">
        	<td colspan=\"3\" height=\"18\"><u><b>CURRENCY : ".$row['currency']."</b></u></td>
        </tr>";
			}
			$classTabelBwh = "tabelBorderAllNull";
			$jmlByCurr = "";
			if($idCekUrutCurrBawah == $row['idmailinv'])
			{
				$classTabelBwh = "tabelBorderBottomJust";
				$jmlAmountByCurr = $this->jmlAmountByCurr($allIdMailInvGet, $companyGet, $row['currency']);
				$jmlAmountByCurr = $this->CPublic->jikaParamSamaDenganNilai1(number_format((float)$jmlAmountByCurr, 2, '.', ','), "0.00", "");
			
				$jmlByCurr = " <tr style=\"font-family:'Arial Narrow';font-size:10px;\">
									<td colspan=\"6\" height=\"18\">&nbsp;</td>
									<td align=\"right\"><b>".$jmlAmountByCurr."</b></td>
								</tr>";		
			}
			
			$invAmount = $this->CPublic->jikaParamSamaDenganNilai1(number_format((float)$row['amount'], 2, '.', ','), "0.00", "");
			
$html.= "<tr style=\"font-family:'Arial Narrow';font-size:10px;\">
        	<td height=\"18\" class=\"".$classTabelBwh."\">".$row['batchno']."</td>
            <td class=\"".$classTabelBwh."\" align=\"center\">".$row['barcode']."</td>
            <td class=\"".$classTabelBwh."\">".$row['mailinvno']."</td>
            <td class=\"".$classTabelBwh."\">".$row['kreditaccname']."&nbsp;</td>
            <td class=\"".$classTabelBwh."\">".$row['description']."&nbsp;</td>
            <td class=\"".$classTabelBwh."\" align=\"center\">".$row['currency']."</td>
            <td class=\"".$classTabelBwh."\" align=\"right\">".$invAmount."</td>
        </tr>";
$html.=$jmlByCurr;
		}
		
$html.= "        </table>
			</td>
		</tr>";
		
		if($a == $maxPage)
		{
$html.= "<tr style=\"font-family:'Arial Narrow';font-size:12px;\">
			<td align=\"center\" valign=\"bottom\" height=\"20\"><b>Total Invoice(s) : ".$urutan."</b></td>
		</tr>
		<tr>
			<td valign=\"bottom\" height=\"70\">
				<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
				<tr style=\"font-family:'Arial Narrow';font-size:12px;\">
					<td width=\"50%\" align=\"center\"><span style=\"text-decoration:overline;\"><b>Received by / Date</b></span></td>
					<td align=\"center\"><span style=\"text-decoration:overline;\"><b>Prepared by / Date</b></span></td>
				</tr>
				</table>
			</td>
		</tr>";	
		}
		$hariEng = $this->CPublic->nmHariSek().", ".$this->CPublic->englishDate()." - ".$this->CPublic->jamServer();
$html.= "<tr><td style=\"position:absolute;bottom:-".$persenheight."%;font:0.9em sans-serif;\"><i>".$hariEng."</i></td><td style=\"position:absolute;bottom:-".$persenheight."%;width:50%;right:1%;font:0.9em sans-serif;color:#333;\" class=\"tabelBorderAllNull\">Page ".$a." of ".$maxPage."</td></tr>";

		}

		return $html;
	}
	
	function jmlAmountByCurr($allIdMailInvGet, $company, $curr)
	{
		$totalAmount = 0;
		$query = $this->koneksi->mysqlQuery("SELECT amount, addi, deduc FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND apprpayment=1 AND preparepayment='N' AND paid='N' AND currency='".$curr."' AND FIND_IN_SET(idmailinv, '".$allIdMailInvGet."') AND deletests=0", $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			//$row['amount']++;
			$amount = $row['amount'];
			$deduc = $row['deduc'];
			$addi = $row['addi'];
			$totalAmountDeduc = (($amount - $deduc) + $addi);
			//$totalAmount += $row['amount'];
			$totalAmount += $totalAmountDeduc;
			
		}
		return $totalAmount;
	}
	
	function jmlAllRow( $allIdMailInvGet, $company )
	{		
		$query = $this->koneksi->mysqlQuery("SELECT * FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND apprpayment=1 AND preparepayment='N' AND paid='N' AND FIND_IN_SET(idmailinv, '".$allIdMailInvGet."') AND deletests=0;", $this->koneksi->bukaKoneksi());
		return $this->koneksi->mysqlNRows($query);
	}
	
	function cekUrutCurr($allIdMailInvGet, $company, $curr, $atasBawah)
	{
		$orderBy = "";
		if($atasBawah == "atas")
			$orderBy = " ORDER BY batchno ASC, idmailinv ASC LIMIT 1";
		if($atasBawah == "bawah")
			$orderBy = " ORDER BY batchno DESC, idmailinv DESC LIMIT 1";

		$query = $this->koneksi->mysqlQuery("SELECT * FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND apprpayment=1 AND preparepayment='N' AND paid='N' AND currency='".$curr."' AND FIND_IN_SET(idmailinv, '".$allIdMailInvGet."') AND deletests=0 ".$orderBy.";", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['idmailinv'];
	}
}