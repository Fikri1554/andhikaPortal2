<?php
class CLaporanPrint
{	
	function CLaporanPrint($koneksiMysql, $koneksiOdbc, $koneksiOdbcId, $CInvReg, $CPublic)
	{
		$this->koneksi = $koneksiMysql;
		$this->CInvreg = $CInvReg;
		$this->CPublic = $CPublic;
	}
	
	function distributionListModel2($get)
	{
		$dataFromGet = $get['dataFrom'];
		$printByGet = $get['printBy'];
		
		$printBy2Get = $get['printBy2'];
		$fromBarcodeGet = $get['fromBarcode'];
		$toBarcodeGet = $get['toBarcode'];
		$fromDateGet = $get['fromDate'];
		$toDateGet = $get['toDate'];

		$divisiBy = $get['divisi'];
		
		$fromDateDB = $this->CPublic->convTglDB($fromDateGet);
		$toDateDB = $this->CPublic->convTglDB($toDateGet);
		
		$fromDateDBBatch = str_replace("-","",$fromDateDB); // date yang dihilangkan garis strip nya ("-") agar bisa sama dengan batchno
		$toDateDBBatch = str_replace("-","",$toDateDB); 
		//$startDtToBatchno = $this->CPublic->convTglNonDBtoBatchno($startDt); // rubah format tanggal DD/MM/YYYY menjadi YYYYMMDD
		//$endDtToBatchno = $this->CPublic->convTglNonDBtoBatchno($endDt);
		
		if($printBy2Get == "barcode")
		{
			if($printByGet == "all")
			{
				$paramPrintBy = "";
				if(rtrim($fromBarcodeGet) == "" && rtrim($toBarcodeGet) != "")
					$paramPrintBy = "SUBSTR(barcode,4)<='".$toBarcodeGet."' AND";
				if(rtrim($toBarcodeGet) == "" && rtrim($fromBarcodeGet) != "")
					$paramPrintBy = "SUBSTR(barcode,4)>='".$fromBarcodeGet."' AND";
				if(rtrim($toBarcodeGet) != "" && rtrim($fromBarcodeGet) != "")
					$paramPrintBy = "SUBSTR(barcode,4)>='".$fromBarcodeGet."' AND SUBSTR(barcode,4)<='".$toBarcodeGet."' AND";
			}
			if($printByGet == "mail")
			{
				$paramPrintBy = "SUBSTR(barcode,4)>='".$fromBarcodeGet."' AND SUBSTR(barcode,4)<='".$toBarcodeGet."' AND SUBSTR(barcode,1,1)='S' AND";
				if(rtrim($fromBarcodeGet) == "")
					$paramPrintBy = "SUBSTR(barcode,4)<='".$toBarcodeGet."' AND SUBSTR(barcode,1,1)='S' AND";
				if(rtrim($toBarcodeGet) == "")
					$paramPrintBy = "SUBSTR(barcode,4)>='".$fromBarcodeGet."' AND SUBSTR(barcode,1,1)='S' AND";
			}
			if($printByGet == "invoice")
			{
				$paramPrintBy = "SUBSTR(barcode,4)>='".$fromBarcodeGet."' AND SUBSTR(barcode,4) <= '".$toBarcodeGet."' AND SUBSTR(barcode,1,1)='A' AND";
				if(rtrim($fromBarcodeGet) == "")
					$paramPrintBy = "SUBSTR(barcode,4)<='".$toBarcodeGet."' AND SUBSTR(barcode,1,1)='A' AND";
				if(rtrim($toBarcodeGet) == "")
					$paramPrintBy = "SUBSTR(barcode,4)>='".$fromBarcodeGet."' AND SUBSTR(barcode,1,1)='A' AND";
			}
		}
		if($printBy2Get == "batchno")
		{
			if($printByGet == "all")
			{
				$paramPrintBy = "batchno >= ".$fromDateDBBatch." AND batchno <= ".$toDateDBBatch." AND";
				if(rtrim($fromDateDBBatch) == "")
					$paramPrintBy = "batchno <= ".$toDateDBBatch." AND";
				if(rtrim($toDateDBBatch) == "")
					$paramPrintBy = "batchno >= ".$fromDateDBBatch." AND";
			}
			if($printByGet == "mail")
			{
				$paramPrintBy = "batchno >= ".$fromDateDBBatch." AND batchno <= ".$toDateDBBatch." AND SUBSTR(barcode,1,1)='S' AND";
				if(rtrim($fromDateDBBatch) == "")
					$paramPrintBy = "batchno <= ".$toDateDBBatch." AND SUBSTR(barcode,1,1)='S' AND";
				if(rtrim($toDateDBBatch) == "")
					$paramPrintBy = "batchno >= ".$fromDateDBBatch." AND SUBSTR(barcode,1,1)='S' AND";
			}
			if($printByGet == "invoice")
			{
				$paramPrintBy = "batchno >= ".$fromDateDBBatch." AND batchno <= ".$toDateDBBatch." AND SUBSTR(barcode,1,1)='A' AND";
				if(rtrim($fromDateDBBatch) == "")
					$paramPrintBy = "batchno <= ".$toDateDBBatch." AND SUBSTR(barcode,1,1)='A' AND";
				if(rtrim($toDateDBBatch) == "")
					$paramPrintBy = "batchno >= ".$fromDateDBBatch." AND SUBSTR(barcode,1,1)='A' AND";
			}
		}

		if ($divisiBy != "all") 
		{
			$paramPrintBy .= " unit = '".$divisiBy."' AND";
		}
		
		$limit = 15; //limit jumlah data mail atau invoice dibawah disposition dari tiap halaman
		$jmlRowIsiDispo = $this->jmlRowIsiDispo($paramPrintBy); //jumlah total invoice atau mail yang dipilih dan dibawah disposition
		$jmlAllJudulDispo = $this->jmlAllJudulDispo($paramPrintBy);
		
		$maxPage = ceil( ($jmlRowIsiDispo+$jmlAllJudulDispo)/$limit ); // jumlah semua data dibagi limit maka didapatlah jumlah halaman
		$pageNum = 1;
		
		//echo $jmlRowIsiDispo." /  ".$jmlAllJudulDispo;
		//echo $paramPrintBy."<br>";
		//echo $jmlAllJudulDispo;
		$html = "";
		for($a=1;$a<=$maxPage;$a++)
		{
			$offset = 0;
			$jmlJudulDispo = 0;
			$jmlJudulDispoSeb = 0;
			//echo $a."<br>";
			if($a == 1)//jika halaman satu maka nomor mulai dari 1
			{
				$offset = 0;
				$pagebreak = "";
				$persenheight = 0;
				
				$jmlJudulDispos = $this->jmlJudulDispo($offset, $limit, $paramPrintBy );
				
				$sisaLimitAll = 0;
				
				$offsetMinusJdl = 0;
				$limitPlusJdl = (int)$limit - (int)$jmlJudulDispos;
				
				
			}
			else//jika selain halaman satu maka bertambah sesuai jumlah halaman dan urutan
			{
				$offset = ($a-1) * $limit; // OFFSET ADALAH DIMULAINYA POTONGAN DATA UTK MASING2 HALAMAN BERDASARKAN LANJUTAN URUTAN DARI DATA HALAMAN SEBELUMNYA
				//$offsetSeb = ($a-2) * $limit;
				$pagebreak = "<tr style=\"page-break-after: left\"></tr>";
				$persenheight = ($a-1) * 100;
				
				$jmlJudulDispo = $this->jmlJudulDispo($offset, $limit, $paramPrintBy);//JUMLAH JUDUL DISPOSITION UNTUK MASING2 UNIT / DIVISI TIAP HALAMAN
				
				$sisaLimit = ($limit - $limitPlusJdl) + 0;//SISA LIMIT ADALAH SISA DATA YANG AKAN MENAMBAH DATA HALAMAN SELANJUTNYA DIKARENAKAN ADA TAMBAHAN JUDUL 
				$sisaLimitAll += $sisaLimit; // TOTAL SEMUA SISA DATA YANG TERSINGKIR KARENA ADA TAMBAHAN JUDUL DIVISI / UNIT
				
				$offsetMinusJdl = ((int)$offset - (int)$sisaLimitAll);
				$limitPlusJdl = (int)$limit - (int)$jmlJudulDispo;

				$jmlJudulDispos = $this->jmlJudulDispo($offsetMinusJdl, $limitPlusJdl, $paramPrintBy);
				
				$offsetMinusJdl = $offsetMinusJdl;
				$limitPlusJdl = $limit - $jmlJudulDispos;
			}

			//$html.= "<tr><td align\"center\"><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
			$urutIsi = 0;
			$urutJudul = 0;
			$html.= $pagebreak;
			//$html.= "<tr><td align\"center\">OFFSETMINUSJDL : ".$offsetMinusJdl.", LIMITMINUSJDL : ".$limitPlusJdl."</td></tr>";
			//$html.= "<tr><td align\"center\">OFFSETMINUSJDLL : ".$offsetMinusJdll.", LIMITMINUSJDLL : ".$limitPlusJdll."</td></tr>";
			//$html.= "<tr><td align\"center\">JMLJUDULSEK : ".$jmlJudulDispos." - ".$sisaLimitAll."</td></tr>";
			$html.= $this->judulDistributionList($printByGet, "&nbsp;"); // judul dispotiton tiap2 unit atau divisi
			$query = $this->koneksi->mysqlQuery("SELECT * FROM mailinvoice WHERE ".$paramPrintBy." deletests=0 ORDER BY unit, idmailinv ASC LIMIT ".$offsetMinusJdl.", ".$limitPlusJdl."");				
			while($row = $this->koneksi->mysqlFetch($query))
			{
				$urutIsi++;

				$unitName = $row['unitname'];
				$cekUrutByUnit = $this->cekUrutByUnit($row['unit'], $paramPrintBy);
				// jika idmailinv sama dengan idmailinv paling atas berdasarkan ASC dari unit yang sama maka munculkan judul
				$classTdPalingAtas = "tabelBorderTopNull";
				if($row['idmailinv'] == $cekUrutByUnit)
				{
					//$jmlJudulDis++;
					$urutJudul++;
					$htmlJudulDisposition = $this->htmlJudulDisposition($unitName, $urutJudul);
				}
				else
				{
					$htmlJudulDisposition = "";
					if($urutIsi == 1)
					{
						$classTdPalingAtas = "tabelBorderAll";
						//$jmlJudulDis++;
						//$htmlJudulDisposition = $this->htmlJudulDisposition($unitName);
					}
				}
				
				$remark = $row['remark'];
				$sNo = $row['urutan'];
				$mailId = $row['barcode'];
				$entryDt =  $this->CPublic->convBatchnoToTglNonDB($row['batchno']);
				$invAmount = $this->CPublic->jikaParamSamaDenganNilai1(number_format((float)$row['amount'], 2, '.', ','), "0.00", "");
				$invCur = $row['currency'];
				$invDt = $this->CPublic->jikaParamSamaDenganNilai1($this->CPublic->convTglNonDB($row['tglinvoice']), "00/00/0000","");
				
				$senderVendor = $row['sendervendor1'];// CREDITOR NAME
				if($row['tipesenven'] == "2")
					$senderVendor = $row['sendervendor2name'];// CREDITOR NAME
					
				if($row['sendervendor2'] == "")
					$senderVendor = $row['kreditaccname'];// CREDITOR NAME
					
				$compName = $row['companyname'];
				$mailInvNo = $row['mailinvno'];
				
				$html.= $htmlJudulDisposition;
				$html.= "<tr>
							  <td class=\"".$classTdPalingAtas."\" valign=\"top\" align=\"center\">
								  <table width=\"99%\" style=\"font:0.7em Arial Narrow;\" cellspacing=\"0\" border=0>
								  <tr>
									  <td class=\"tabelBorderRightJust\" colspan=\"2\" height=\"20\">".$remark."&nbsp;(".$row['company'].")&nbsp;</td>
									  <td class=\"tabelBorderRightJust\" width=\"8%\" align=\"center\">&nbsp;".$sNo."</td>
									  <td class=\"tabelBorderRightJust\" width=\"12%\" align=\"center\">&nbsp;".$mailId."</td>
									  <td width=\"15%\">&nbsp;</td>
								  </tr>
								  <tr>
									  <td width=\"44%\" class=\"\" height=\"18\">&nbsp;&nbsp;&nbsp;".$entryDt."</td>
									  <td width=\"21%\" class=\"tabelBorderRightJust\">&nbsp;".$invAmount."</td>
									  <td class=\"tabelBorderRightJust\" align=\"center\">&nbsp;".$invCur."</td>
									  <td class=\"tabelBorderRightJust\" align=\"center\">&nbsp;".$invDt."</td>
									  <td>&nbsp;</td>
								  </tr>
								  <tr>
									  <td class=\"\" height=\"18\">&nbsp;&nbsp;&nbsp;".$senderVendor."</td>
									  <td class=\"tabelBorderRightJust\">&nbsp;".$company."</td>
									  <td class=\"tabelBorderRightJust\">&nbsp;</td>
									  <td class=\"tabelBorderRightJust\" align=\"center\">&nbsp;".$mailInvNo."</td>
									  <td>&nbsp;</td>
								  </tr>
								  </table>
							  </td>
						  </tr>";	
				
			}
			$html.= "<tr><td style=\"position:absolute;bottom:-".$persenheight."%;width:100%;right:1%;font:0.7em sans-serif;color:#333;\" class=\"tabelBorderAllNull\">Page ".$a." of ".$maxPage."</td></tr>";
			//$html.= "</table></td></tr>";
		}
		
		return $html;
	}
	
	function jmlRowIsiDispo( $paramPrintBy )
	{
		$query = $this->koneksi->mysqlQuery("SELECT * FROM mailinvoice WHERE ".$paramPrintBy." deletests=0;");
		return $this->koneksi->mysqlNRows($query);
	}
	
	function cekUrutByUnit($unit, $paramPrintBy)
	{
		$query = $this->koneksi->mysqlQuery("SELECT idmailinv FROM mailinvoice WHERE ".$paramPrintBy." deletests=0 AND unit='".$unit."' ORDER BY unit, idmailinv ASC LIMIT 1;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['idmailinv'];
	}
	
	function jmlJudulDispo($offset, $limit, $paramPrintBy)
	{
		$jmlJudul = 0;
		//$urutIsi = 0;
		$query = $this->koneksi->mysqlQuery("SELECT * FROM mailinvoice WHERE ".$paramPrintBy." deletests=0 ORDER BY unit, idmailinv ASC LIMIT ".$offset.", ".$limit);					
		while($row = $this->koneksi->mysqlFetch($query))
		{
			//$urutIsi++;
			$cekUrutByUnit = $this->cekUrutByUnit($this->CPublic->zerofill($row['unit'],5), $paramPrintBy);
			if($this->CPublic->zerofill($row['idmailinv'],5) == $this->CPublic->zerofill($cekUrutByUnit,5))// jika idmailinv sama dengan idmailinv paling atas berdasarkan ASC dari unit yang sama maka munculkan judul
			{
				$jmlJudul++;
			}
			// else
			// {
			// 	if($urutIsi == 1)
			// 	{
			// 		$jmlJudul++;
			// 	}
			// }
		}
		
		return $jmlJudul;
	}
	
	function jmlAllJudulDispo($paramPrintBy)
	{
		$jmlJudul = 0;
		$query = $this->koneksi->mysqlQuery("SELECT idmailinv FROM mailinvoice WHERE ".$paramPrintBy." deletests=0 GROUP BY unit;");					
		$jmlRow = $this->koneksi->mysqlNRows($query);
		return $jmlRow;
	}
	
	function judulDistributionList($printBy, $userFullNm)
	{
		if($printBy == "all")
		{
			$judul = "Mails & Invoices Distribution List";
		}
		if($printBy == "mail")
		{
			$judul = "Mails Distribution List";
		}
		if($printBy == "invoice")
		{
			$judul = "Invoices Distribution List";
		}
		
		$tglServerr = explode(" ",$this->CPublic->waktuSek());
		$tglServer = $this->CPublic->convTglNonDB($tglServerr[0]);
		
		$tabel = "";
		$tabel.= "
		<tr>
			<td valign=\"top\" height=\"60\">
				<table width=\"100%\">
				<tr>
					<td style=\"font:1.4em Arial Narrow;font-weight:bold;\" valign=\"top\">
					".$judul."&nbsp;
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td style=\"font-style:italic;font-size:11px;font-weight:normal;\">&nbsp;".$tglServer."&nbsp;</td>
					<td style=\"font-size:11px;color:#666666;\" align=\"right\">&nbsp;<!-- Print by ".$userFullNm." --></td>
				</tr>
				</table>
			</td>
		</tr>
		";	
		
		return $tabel;
	}
	
	function htmlJudulDisposition($unitName, $urutJudul)
	{
		$classTdPalingAtas = "tabelBorderTopNull";
		if($urutJudul == 1)
			$classTdPalingAtas = "tabelBorderAll";
			
		$nilai = "
		<tr>
			<td class=\"".$classTdPalingAtas."\" style=\"font:1.1em sans-serif;font-weight:bold;\" valign=\"middle\" align=\"left\" height=\"20\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			Disposition ".$unitName."&nbsp;
			</td>
		</tr>
		<tr>
			<td class=\"tabelBorderTopNull\" valign=\"top\" align=\"center\">
				<table width=\"99%\" style=\"font:1em Arial Narrow;font-weight:bold;\" cellspacing=\"1\" cellpadding=\"0\">
				<tr>
					<td width=\"44%\">Remarks</td>
					<td width=\"21%\">&nbsp;</td>
					<td width=\"8%\" align=\"center\">Sno</td>
					<td width=\"12%\" align=\"center\">Mailid</td>
					<td width=\"15%\" rowspan=\"3\" align=\"center\" valign=\"top\">Receive by</td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;Entrydate</td>
					<td>Inv. Amount</td>
					<td align=\"center\">Inv. Cur</td>
					<td align=\"center\">Inv. date</td>
				  </tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;Sendername</td>
					<td>Bill To</td>
					<td>&nbsp;</td>
					<td align=\"center\">Mail / Inv. No</td>
				</tr>
				</table>
			</td>
		</tr>
		";
		
		return $nilai;
	}
	
	function jmlAllHeightModel2($paramPrintBy)
	{
		$urutIsi = 0;	 //0.325				
		$totalHeight = 0;
		$heightRow = 30;
		$heightJudul = 40;

		$query = $this->koneksi->mysqlQuery("SELECT * FROM mailinvoice WHERE ".$paramPrintBy." deletests=0 ORDER BY unitname ASC;");			
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$urutIsi++;	
			if($row['idmailinv'] == $this->idMailUnitAtas($paramPrintBy, $row['unit']) || $urutIsi == 1)
			{
				$totalHeight += $heightJudul;
			}
			if(strlen($row['remark']) > 50)
			{	$heightRow = 39;	}
			$totalHeight += $heightRow;
		}
		return $totalHeight;
	}
	
	function distributionListModel1($get)
	{
		$dataFromGet = $get['dataFrom'];
		$printByGet = $get['printBy'];
		
		$printBy2Get = $get['printBy2'];
		$fromBarcodeGet = $get['fromBarcode'];
		$toBarcodeGet = $get['toBarcode'];
		$fromDateGet = $get['fromDate'];
		$toDateGet = $get['toDate'];

		$divisiBy = $get['divisi'];
		
		$fromDateDB = $this->CPublic->convTglDB($fromDateGet);
		$toDateDB = $this->CPublic->convTglDB($toDateGet);
		
		$fromDateDBBatch = str_replace("-","",$fromDateDB); // date yang dihilangkan garis strip nya ("-") agar bisa sama dengan batchno
		$toDateDBBatch = str_replace("-","",$toDateDB); 
		//$startDtToBatchno = $this->CPublic->convTglNonDBtoBatchno($startDt); // rubah format tanggal DD/MM/YYYY menjadi YYYYMMDD
		//$endDtToBatchno = $this->CPublic->convTglNonDBtoBatchno($endDt);
		
		if($printBy2Get == "barcode")
		{
			if($printByGet == "all")
			{
				$paramPrintBy = "";
				if(rtrim($fromBarcodeGet) == "" && rtrim($toBarcodeGet) != "")
					$paramPrintBy = "SUBSTR(barcode,4)<='".$toBarcodeGet."' AND";
				if(rtrim($toBarcodeGet) == "" && rtrim($fromBarcodeGet) != "")
					$paramPrintBy = "SUBSTR(barcode,4)>='".$fromBarcodeGet."' AND";
				if(rtrim($toBarcodeGet) != "" && rtrim($fromBarcodeGet) != "")
					$paramPrintBy = "SUBSTR(barcode,4)>='".$fromBarcodeGet."' AND SUBSTR(barcode,4)<='".$toBarcodeGet."' AND";
			}
			if($printByGet == "mail")
			{
				$paramPrintBy = "SUBSTR(barcode,4)>='".$fromBarcodeGet."' AND SUBSTR(barcode,4)<='".$toBarcodeGet."' AND SUBSTR(barcode,1,1)='S' AND";
				if(rtrim($fromBarcodeGet) == "")
					$paramPrintBy = "SUBSTR(barcode,4)<='".$toBarcodeGet."' AND SUBSTR(barcode,1,1)='S' AND";
				if(rtrim($toBarcodeGet) == "")
					$paramPrintBy = "SUBSTR(barcode,4)>='".$fromBarcodeGet."' AND SUBSTR(barcode,1,1)='S' AND";
			}
			if($printByGet == "invoice")
			{
				$paramPrintBy = "SUBSTR(barcode,4)>='".$fromBarcodeGet."' AND SUBSTR(barcode,4) <= '".$toBarcodeGet."' AND SUBSTR(barcode,1,1)='A' AND";
				if(rtrim($fromBarcodeGet) == "")
					$paramPrintBy = "SUBSTR(barcode,4)<='".$toBarcodeGet."' AND SUBSTR(barcode,1,1)='A' AND";
				if(rtrim($toBarcodeGet) == "")
					$paramPrintBy = "SUBSTR(barcode,4)>='".$fromBarcodeGet."' AND SUBSTR(barcode,1,1)='A' AND";
			}
			if(rtrim($fromBarcodeGet) == "" && rtrim($toBarcodeGet) == "")
				$paramPrintBy = "";
		}
		if($printBy2Get == "batchno")
		{
			if($printByGet == "all")
			{
				$paramPrintBy = "batchno >= ".$fromDateDBBatch." AND batchno <= ".$toDateDBBatch." AND";
				if(rtrim($fromDateDBBatch) == "")
					$paramPrintBy = "batchno <= ".$toDateDBBatch." AND";
				if(rtrim($toDateDBBatch) == "")
					$paramPrintBy = "batchno >= ".$fromDateDBBatch." AND";
				
			}
			if($printByGet == "mail")
			{
				$paramPrintBy = "batchno >= ".$fromDateDBBatch." AND batchno <= ".$toDateDBBatch." AND SUBSTR(barcode,1,1)='S' AND";
				if(rtrim($fromDateDBBatch) == "")
					$paramPrintBy = "batchno <= ".$toDateDBBatch." AND SUBSTR(barcode,1,1)='S' AND";
				if(rtrim($toDateDBBatch) == "")
					$paramPrintBy = "batchno >= ".$fromDateDBBatch." AND SUBSTR(barcode,1,1)='S' AND";
			}
			if($printByGet == "invoice")
			{
				$paramPrintBy = "batchno >= ".$fromDateDBBatch." AND batchno <= ".$toDateDBBatch." AND SUBSTR(barcode,1,1)='A' AND";
				if(rtrim($fromDateDBBatch) == "")
					$paramPrintBy = "batchno <= ".$toDateDBBatch." AND SUBSTR(barcode,1,1)='A' AND";
				if(rtrim($toDateDBBatch) == "")
					$paramPrintBy = "batchno >= ".$fromDateDBBatch." AND SUBSTR(barcode,1,1)='A' AND";
			}
			if(rtrim($fromDateDBBatch) == "" && rtrim($toDateDBBatch) == "")
				$paramPrintBy = "";
		}

		if ($divisiBy != "all") 
		{
			$paramPrintBy .= " unit = '".$divisiBy."' AND";
		}
		
		$limitHeight = 801;
		$jmlAllHeightModel2 = $this->jmlAllHeightModel2($paramPrintBy);

		$maxPage = ceil($jmlAllHeightModel2/$limitHeight); // jumlah semua data dibagi limit maka didapatlah jumlah halaman
		$html = "";
		//echo "<pre>";print_r($maxPage);exit;
		for($a=1;$a<=$maxPage;$a++)
		{
			if($a == 1)//jika halaman satu maka nomor mulai dari 1
			{
				$persenHeight = 0;
				$pagebreak = "";
				$height1 = 0;
				
				$batasAwal = ($a * $height1);
				$batasAkhir = ($a * $limitHeight);
				
				$offset = "LIMIT 0, ".$this->cariMaksRowPerHalaman($paramPrintBy, $a, $batasAwal, $batasAkhir, "end");
			}
			if($a > 1)//jika halaman satu maka nomor mulai dari 1
			{
				$persenHeight = ($a-1) * 100;
				$pagebreak = "<tr style=\"page-break-after: always;\"><td></td></tr>";
				
				$batasAwal = (($a-1) * $limitHeight);
				$batasAkhir = ($a * $limitHeight);

				$offset =  "LIMIT ".$this->cariMaksRowPerHalaman($paramPrintBy, $a, $batasAwal, $batasAkhir, "start").", ".$this->cariMaksRowPerHalaman($paramPrintBy, $a, $batasAwal, $batasAkhir, "end");
			}
			
			$urutIsi = 0;					
			$totalHeight = 0;
			$heightRow = 30;
			$heightJudul = 40;
			
			$html.= $pagebreak;
			$html.= $this->htmlJudulModel2();
			$html.= "<tr>
						<td class=\"\" valign=\"top\" align=\"center\">
							<table width=\"100%\" style=\"font:1.4em Arial Narrow;font-weight:normal;\" cellspacing=\"0\" cellpadding=\"0\">
							<tr align=\"left\">
								<td width=\"4%\" height=\"30\" class=\"\">SNo</td>
								<td width=\"44%\" class=\"\">Sender / Remarks</td>
								<td width=\"12%\" class=\"\">Mail ID</td>
								<td width=\"20%\" class=\"\">Invoice No / Amount</td>
								<td width=\"9%\" class=\"\">Batchno</td>
								<td width=\"11%\" align=\"center\" class=\"\">Receive by</td>
							</tr>";
			//echo "SELECT * FROM mailinvoice WHERE ".$paramPrintBy." deletests=0 ORDER BY unitname ASC ".$offset.""."<br>";
			if($offset != "LIMIT , ")
			{
				$query = $this->koneksi->mysqlQuery("SELECT * FROM mailinvoice WHERE ".$paramPrintBy." deletests=0 ORDER BY unitname ASC ".$offset.";");			
				while($row = $this->koneksi->mysqlFetch($query))
				{
					$urutIsi++;	

					$borderAtasTebal = "";
					if($row['idmailinv'] == $this->idMailUnitAtas($paramPrintBy, $row['unit']) || $urutIsi == 1)
					{ // JIKA IDMAILINV DATA SAMA DENGAN URUTAN IDMAILINV BERDASARKAN GROUP BY UNIT ATAU URUTAN DARI TIAP HALAMAN ADALAH 1
						$totalHeight += $heightJudul;
						$borderAtasTebal = " class=\"tabelBorderTopJust\" style=\"border-color:#333;border-top-width:2px;\"";
					}
					
					$totalHeight += $heightRow;
					$html.= "	<tr>
									<td colspan=\"6\">
										<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" ".$borderAtasTebal.">
										";
					if($row['idmailinv'] == $this->idMailUnitAtas($paramPrintBy, $row['unit']) || $urutIsi == 1)
					{
						$html.= "		<tr>
											<td height=\"30\"><b>Mail Group : ".$row['unitname']."</b></td>
											<td width=\"11%\" class=\"tabelBorderLeftJust\" style=\"border-color:#333;border-left-width:2px;\">&nbsp;</td>
										</tr>";
					}
					
					$senderVendor = $row['sendervendor1'];
					if($row['tipesenven'] == "2")
						$senderVendor = $this->CInvreg->detilSenderVendor($row['sendervendor2'], "Acctname");
					$currency = "(".$row['currency'].")";
					if($row['currency'] == "XXX" || $row['currency'] == "")
						$currency = "";
					
					$heightRemark = "20";	
					if(strlen($row['remark']) > 50)
					{	$heightRemark = "33";	}
					$amount = $this->CPublic->jikaKosongStrip(number_format((float)$row['amount'], 2, '.', ','));
					$html.= "				<tr>
												<td colspan=\"5\">
													<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"font:0.8em Arial Narrow;font-weight:normal;\">
													<tr valign=\"top\" align=\"left\">
														<td width=\"4%\" class=\"\" rowspan=\"3\" height=\"20\">&nbsp;".$row['urutan']."</td>
														<td width=\"44%\" class=\"\">".$senderVendor."</td>
														<td width=\"12%\" class=\"\" rowspan=\"3\">".$row['barcode']."</td>
														<td class=\"\" colspan=\"2\">".$row['mailinvno']."</td>
														<td width=\"9%\" class=\"\" rowspan=\"3\">".$row['batchno']."</td>
														<td width=\"11%\" rowspan=\"3\" class=\"tabelBorderLeftJust\" style=\"border-color:#333;border-left-width:2px;\">&nbsp;</td>
													</tr>
													<tr valign=\"top\">
														<td class=\"\" height=\"".$heightRemark."\">".$row['remark']."&nbsp;(".$row['company'].")</td>
														<td width=\"4%\" class=\"\">".$currency."</td>
														<td width=\"16%\" class=\"\">".$amount."</td>
													</tr>
													</table>
												</td>
											</tr>";
					$html.= "      		</table>
									</td>
								</tr>";
				}
			}
			
			$html.= $this->htmlEndPageModel2($a, $maxPage, $persenHeight);
			$html.= "       </table>
						</td>
					</tr>";
		}
		//echo "<pre>";print_r($html);exit;
		return $html;
	}
	
	function cariMaksRowPerHalaman($paramPrintBy, $halamanKe, $batasAwal, $batasAkhir, $aksi)
	{
		$totalHeight = 0;
		$heightRow = 30;
		$heightJudul = 40;
		
		if($halamanKe == 1)
		{
			$urutIsi = 0;	// URUTAN DATA SECARA KESELURUHAN YANG SEHARUSNYA DITAMPILKAN	
			$query = $this->koneksi->mysqlQuery("SELECT * FROM mailinvoice WHERE ".$paramPrintBy." deletests=0 ORDER BY unitname ASC;");			
			while($row = $this->koneksi->mysqlFetch($query))
			{
				$urutIsi++;	
				if($row['idmailinv'] == $this->idMailUnitAtas($paramPrintBy, $row['unit']))// JIKA ADA JUDUL DIVISI NAME MAKA TOTAL HEIGHT TAMBAHKAN $HEIGHTJUDUL
				{	$totalHeight += $heightJudul;	}
				
				if(strlen($row['remark']) > 50){ $heightRow = 39; }else{ $heightRow = 30; }
				$totalHeight += $heightRow;// JIKA TIDAK ADA JUDUL DIVISI NAME ATAU ROW BIASA MAKA TOTAL HEIGHT TAMBAHKAN $HEIGHTROW
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
		//echo $urutIsi."=><br>";
		if($halamanKe > 1)
		{
			$urutIsi = 0; // URUTAN DATA SECARA KESELURUHAN YANG SEHARUSNYA DITAMPILKAN
			$urutData = 0;	// URUTAN DARI DATA YANG TAMPIL SAJA BERDASARKAN RANGE HEIGHT
			$query = $this->koneksi->mysqlQuery("SELECT * FROM mailinvoice WHERE ".$paramPrintBy." deletests=0 ORDER BY unitname ASC;");			
			while($row = $this->koneksi->mysqlFetch($query))
			{
				$urutIsi++;	
				if($row['idmailinv'] == $this->idMailUnitAtas($paramPrintBy, $row['unit']) || $urutIsi == 1)
				{	$totalHeight += $heightJudul;	} // JIKA ADA JUDUL DIVISI NAME MAKA TOTAL HEIGHT TAMBAHKAN $HEIGHTJUDUL
				
				if(strlen($row['remark']) > 50){ $heightRow = 39; }else{ $heightRow = 30; }
				
				$totalHeight += $heightRow; // JIKA TIDAK ADA JUDUL DIVISI NAME ATAU ROW BIASA MAKA TOTAL HEIGHT TAMBAHKAN $HEIGHTROW
				if($totalHeight >= $batasAwal && $totalHeight <= $batasAkhir)
				{ // TAMPILKAN DATA YANG BERADA DI ANTARA BARTAS AWAL DAN AKHIR BERDASARKAN HEIGHT DATA TSB MASUK APA TIDAK DALAM JANGKAUAN TERSEBUT
					$urutData++;
					if($aksi == "start") // JIKA AKSI START MAKA TAMPILKAN DATA YANG BERADA DALAM AWAL BARIS YANG BERADA DALAM JANGKAUAN BATAS
					{
						if($urutData == 1)
						{
							//$nilai = ($urutIsi - 1);
							$nilai = $urutIsi;
						}
					}
					if($aksi == "end")
					{	$nilai = $urutData;}
					//$nilai.= "(".$urutData.")-".$urutIsi.",";
				}
			}
			//echo $totalHeight." = ".$nilai."++++++++<br>";
		}
		
		return $nilai;
	}
	
	function idMailUnitAtas($paramPrintBy, $unit) // MENCARI IDMAILINV PALING ATAS BERDASARKAN UNITNAME
	{
		$query = $this->koneksi->mysqlQuery("SELECT idmailinv FROM mailinvoice WHERE ".$paramPrintBy." unit = '".$unit."' AND deletests=0 ORDER BY unitname ASC LIMIT 1;");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['idmailinv'];
	}
	
	function idMailUnitBawah($unit) // MENCARI IDMAILINV PALING ATAS BERDASARKAN UNITNAME
	{
		$query = $this->koneksi->mysqlQuery("SELECT idmailinv FROM mailinvoice WHERE unit = '".$unit."' AND deletests=0 ORDER BY unitname DESC LIMIT 1");
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row['idmailinv'];
	}
	
	function isiMailGroupModel2($row, $totalHeight)
	{
		$html = "";
		
		$senderVendor = $row['sendervendor1'];
        if($row['tipesenven'] == "2")
            $senderVendor = $this->CInvreg->detilSenderVendor($row['sendervendor2'], "Acctname");
		$currency = "(".$row['currency'].")";
        if($row['currency'] == "XXX" || $row['currency'] == "")
            $currency = "";
		$amount = $this->CPublic->jikaKosongStrip(number_format((float)$row['amount'], 2, '.', ','));
			
		$html.= "<tr>
					<td colspan=\"5\">
						<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"font:0.8em Arial Narrow;font-weight:normal;\">
						<tr valign=\"top\" align=\"left\">
							<td width=\"4%\" class=\"\" rowspan=\"3\" height=\"20\">&nbsp;".$row['urutan']."</td>
							<td width=\"44%\" class=\"\">".$totalHeight." ## ".$senderVendor."</td>
							<td width=\"12%\" class=\"\" rowspan=\"3\">".$row['barcode']."</td>
							<td class=\"\" colspan=\"2\">".$row['mailinvno']."</td>
							<td width=\"9%\" class=\"\" rowspan=\"3\">".$row['batchno']."</td>
							<td width=\"11%\" rowspan=\"3\" class=\"tabelBorderLeftJust\" style=\"border-color:#333;border-left-width:2px;\">&nbsp;</td>
						</tr>
						<tr valign=\"top\">
							<td class=\"\" height=\"20\">".$row['remark']."</td>
							<td width=\"4%\" class=\"\">".$currency."</td>
							<td width=\"16%\" class=\"\">42,361,128.00</td>
						</tr>
						</table>
					</td>
				</tr>";
		return $html;
	}


	function htmlEndPageModel2($a, $maxPage, $persenHeight)
	{
		$html = "";
		$html.= "<tr>
					<td id=\"idTdpage\" width=\"789\" style=\"position:absolute;bottom:-".$persenHeight."%;\" class=\"\">
						<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"font:1em sans-serif;color:#333;\">
						<tr>
							<td width=\"25%\">For Internal Use</td>
							<td width=\"50%\" align=\"center\">Page ".$a." of ".$maxPage."</td>
							<td>&nbsp;</td>
						</tr>
						</table>
					</td>
				</tr>";
		return $html;
		//$html.= "<tr><td style=\"position:absolute;bottom:-".$persenheight."%;width:100%;right:1%;font:0.7em sans-serif;color:#333;\" class=\"tabelBorderAllNull\">Page ".$a." of ".$maxPage."</td></tr>";
	}
	function htmlJudulModel2()
	{
		$tglSek = $this->CPublic->zerofill( $this->CPublic->tglDayServer(), 2)."-".$this->CPublic->zerofill( $this->CPublic->bulanServer(), 2)."-".$this->CPublic->tahunServer();	
		$nilai = "
		<tr>
			<td valign=\"top\" height=\"50\">
				<table width=\"100%\">
				<!--<tr>
					<td style=\"font-style:normal;font-size:11px;font-weight:normal;\">".$tglSek."&nbsp;</td>
				</tr>-->
				<tr>
					<td style=\"font:1.4em Arial Narrow;font-weight:bold;\" valign=\"top\" align=\"center\">
					Mail Register & Invoice Distribution List&nbsp;
					</td>
				</tr>
				<tr>
					<td style=\"font-style:normal;font-size:13px;font-weight:bold;\" align=\"center\">".$tglSek."&nbsp;</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height=\"20\">&nbsp;</td>
		</tr>
		";
		
		return $nilai;
	}
	
	function covNote($get)
	{
		$dataFromGet = $get['dataFrom'];
		$printByGet = $get['printBy'];
		
		$printBy2Get = $get['printBy2'];
		$fromBarcodeGet = $get['fromBarcode'];
		$toBarcodeGet = $get['toBarcode'];
		$fromDateGet = $get['fromDate'];
		$toDateGet = $get['toDate'];

		$divisiBy = $get['divisi'];
		
		$fromDateDB = $this->CPublic->convTglDB($fromDateGet);
		$toDateDB = $this->CPublic->convTglDB($toDateGet);
		
		$fromDateDBBatch = str_replace("-","",$fromDateDB); // date yang dihilangkan garis strip nya ("-") agar bisa sama dengan batchno
		$toDateDBBatch = str_replace("-","",$toDateDB); 
		
		//$startDtToBatchno = $this->CPublic->convTglNonDBtoBatchno($startDt); // rubah format tanggal DD/MM/YYYY menjadi YYYYMMDD
		//$endDtToBatchno = $this->CPublic->convTglNonDBtoBatchno($endDt);
		
		if($printBy2Get == "barcode")
		{
			if($printByGet == "all")
			{
				$paramPrintBy = "";
				if(rtrim($fromBarcodeGet) == "" && rtrim($toBarcodeGet) != "")
					$paramPrintBy = "SUBSTR(barcode,4)<='".$toBarcodeGet."' AND";
				if(rtrim($toBarcodeGet) == "" && rtrim($fromBarcodeGet) != "")
					$paramPrintBy = "SUBSTR(barcode,4)>='".$fromBarcodeGet."' AND";
				if(rtrim($toBarcodeGet) != "" && rtrim($fromBarcodeGet) != "")
					$paramPrintBy = "SUBSTR(barcode,4)>='".$fromBarcodeGet."' AND SUBSTR(barcode,4)<='".$toBarcodeGet."' AND";
			}
			if($printByGet == "mail")
			{
				$paramPrintBy = "SUBSTR(barcode,4)>='".$fromBarcodeGet."' AND SUBSTR(barcode,4)<='".$toBarcodeGet."' AND SUBSTR(barcode,1,1)='S' AND";
				if(rtrim($fromBarcodeGet) == "")
					$paramPrintBy = "SUBSTR(barcode,4)<='".$toBarcodeGet."' AND SUBSTR(barcode,1,1)='S' AND";
				if(rtrim($toBarcodeGet) == "")
					$paramPrintBy = "SUBSTR(barcode,4)>='".$fromBarcodeGet."' AND SUBSTR(barcode,1,1)='S' AND";
			}
			if($printByGet == "invoice")
			{
				$paramPrintBy = "SUBSTR(barcode,4)>='".$fromBarcodeGet."' AND SUBSTR(barcode,4) <= '".$toBarcodeGet."' AND SUBSTR(barcode,1,1)='A' AND";
				if(rtrim($fromBarcodeGet) == "")
					$paramPrintBy = "SUBSTR(barcode,4)<='".$toBarcodeGet."' AND SUBSTR(barcode,1,1)='A' AND";
				if(rtrim($toBarcodeGet) == "")
					$paramPrintBy = "SUBSTR(barcode,4)>='".$fromBarcodeGet."' AND SUBSTR(barcode,1,1)='A' AND";
			}
		}
		if($printBy2Get == "batchno")
		{
			if($printByGet == "all")
			{
				$paramPrintBy = "batchno >= ".$fromDateDBBatch." AND batchno <= ".$toDateDBBatch." AND";
				if(rtrim($fromDateDBBatch) == "")
					$paramPrintBy = "batchno <= ".$toDateDBBatch." AND";
				if(rtrim($toDateDBBatch) == "")
					$paramPrintBy = "batchno >= ".$fromDateDBBatch." AND";
			}
			if($printByGet == "mail")
			{
				$paramPrintBy = "batchno >= ".$fromDateDBBatch." AND batchno <= ".$toDateDBBatch." AND SUBSTR(barcode,1,1)='S' AND";
				if(rtrim($fromDateDBBatch) == "")
					$paramPrintBy = "batchno <= ".$toDateDBBatch." AND SUBSTR(barcode,1,1)='S' AND";
				if(rtrim($toDateDBBatch) == "")
					$paramPrintBy = "batchno >= ".$fromDateDBBatch." AND SUBSTR(barcode,1,1)='S' AND";
			}
			if($printByGet == "invoice")
			{
				$paramPrintBy = "batchno >= ".$fromDateDBBatch." AND batchno <= ".$toDateDBBatch." AND SUBSTR(barcode,1,1)='A' AND";
				if(rtrim($fromDateDBBatch) == "")
					$paramPrintBy = "batchno <= ".$toDateDBBatch." AND SUBSTR(barcode,1,1)='A' AND";
				if(rtrim($toDateDBBatch) == "")
					$paramPrintBy = "batchno >= ".$fromDateDBBatch." AND SUBSTR(barcode,1,1)='A' AND";
			}
		}

		if ($divisiBy != "all") 
		{
			$paramPrintBy .= " unit = '".$divisiBy."' AND";
		}
		
		$limit = 2; //limit jumlah data mail atau invoice dibawah disposition dari tiap halaman
		$jmlRowIsiDispo = $this->jmlRowIsiDispo($paramPrintBy); //jumlah total invoice atau mail yang dipilih dan dibawah disposition
		//$jmlAllJudulDispo = $this->jmlAllJudulDispo(0, $limit, $paramPrintBy );
		
		$maxPage = ceil(($jmlRowIsiDispo)/$limit); // jumlah semua data dibagi limit maka didapatlah jumlah halaman
		$pageNum = 1;
		
		//echo $jmlRowIsiDispo." /  ".$jmlAllJudulDispo;
		
		$html = "";
		for($a=1;$a<=$maxPage;$a++)
		{
			if($a == 1)//jika halaman satu maka nomor mulai dari 1
			{
				$offset = 0;
				$pagebreak = "";
			}
			else//jika selain halaman satu maka bertambah sesuai jumlah halaman dan urutan
			{
				$offset = ($a-1) * $limit; // OFFSET ADALAH DIMULAINYA POTONGAN DATA UTK MASING2 HALAMAN BERDASARKAN LANJUTAN URUTAN DARI DATA HALAMAN SEBELUMNYA
				$pagebreak = "<tr style=\"page-break-after: auto\"></tr>";
			}
			
			$noCovNote = 0;
			
			$html.= $pagebreak;
					
			$query = $this->koneksi->mysqlQuery("SELECT * FROM mailinvoice WHERE ".$paramPrintBy." deletests=0 ORDER BY unit, idmailinv ASC LIMIT ".$offset.", ".$limit."");					
			while($row = $this->koneksi->mysqlFetch($query))
			{
				$noCovNote++;
				if($a == 1)//jika halaman satu maka nomor mulai dari 1
				{
					if($noCovNote == 1)
					{
						$marginTop1 = 10;
						$marginTop2 = 275;
						$marginTop3 = 730;
					}
					else
					{
						$marginTop1 = 830;
						$marginTop2 = 1095;
						$marginTop3 = 1550;
					}
					
				}
				else//jika selain halaman satu maka bertambah sesuai jumlah halaman dan urutan
				{
					if($noCovNote == 1)
					{
						$marginTop1 = 1680 * ($a-1) + 10;  
						$marginTop2 = 1680 * ($a-1) + 275;   
						$marginTop3 = 1680 * ($a-1) + 730; 
					}
					else
					{
						$marginTop1 = 1680 * ($a-1) + 830; 
						$marginTop2 = 1680 * ($a-1) + 1095;  
						$marginTop3 = 1680 * ($a-1) + 1550; 
					}
				}
				
				$receiveDt = $this->CPublic->jikaParamSamaDenganNilai1( $this->CPublic->convTglNonDB($row['receivedate']) , "00/00/0000", "&nbsp;");;
				$entryDt =  $this->CPublic->convBatchnoToTglNonDB($row['batchno']);
				$senderVendor = $row['sendervendor1'];// CREDITOR NAME
				if($row['tipesenven'] == "2")
				{
					$senderVendor = $row['sendervendor2name'];// CREDITOR NAME
					if($row['sendervendor2'] == "")
						$senderVendor = $row['kreditaccname'];// CREDITOR NAME
				}
				
					
				$description = $row['description'];
				if(rtrim($row['description']) == "")
					$description = $row['remark'];
				
				$html.= "<tr>
							<td valign=\"top\" class=\"\">
								<table width=\"1250\" height=\"260\" class=\"tabelBorderAll\" style=\"behavior: url(ie-css3.htc);border-radius: 8px;background-color:#FFFFFF;border-color:#000000;position:absolute;top:".$marginTop1.";\">
							<tr>
									<td align=\"center\">
										<table style=\"font-size:24px;font-family:'Times New Roman';margin-left:20;margin-top:15;\" width=\"98%\" height=\"98%\">
										<tr valign=\"top\">
											<td width=\"14%\" height=\"22\"><b>Mail ID</b></td>
											<td width=\"2%\">:</td>
											<td width=\"48%\" id=\"idFontCode\" style=\"font-family:Code39;font-size:26px;\">*".$row['barcode']."*</td>
											<td width=\"22%\" align=\"center\"><b>( Invoice Letter )</b></td>
											<td width=\"14%\" align=\"left\"><b>".$row['barcode']."</b>&nbsp;</td>
										</tr>
										<tr valign=\"top\">
											<td height=\"22\"><b>Entry Date</b></td>
											<td>:</td>
											<td colspan=\"3\"><b>".$entryDt."</b>&nbsp;</td>
										</tr>
										<tr valign=\"top\">
											<td height=\"22\"><b>Received Date</b></td>
											<td>:</td>
											<td colspan=\"3\"><b>".$receiveDt."</b>&nbsp;</td>
										</tr>
										<tr valign=\"top\">
											<td height=\"22\"><b>Sender Name</b></td>
											<td height=\"22\">:</td>
											<td colspan=\"3\"><b>".$senderVendor."</b>&nbsp;</td>
										</tr>
										<tr valign=\"top\">
											<td height=\"22\"><b>Disposition</b></td>
											<td>:</td>
											<td colspan=\"3\"><b>".$row['unitname']."</b>&nbsp;</td>
										</tr>
										<tr valign=\"top\">
											<td><b>Remarks</b></td>
											<td>:</td>
											<td colspan=\"3\"><b>".$description."&nbsp;(".$row['company'].")</b>&nbsp;</td>
										</tr>
										</table>
									</td>
								</tr>
								<tr><td height=\"10\"></td></tr>
								</table>
							</td>
						</tr>";
						
				$html.= "<tr>
							<td valign=\"top\" align=\"left\">
								<table border=\"0\" width=\"1250\" class=\"tabelBorderAll\" style=\"behavior: url(ie-css3.htc);border-radius: 8px;background-color:#FFFFFF;border-color:#000000;position:absolute;top:".$marginTop2.";\">
								<tr>
									<td align=\"center\">
										<table style=\"font-size:24px;font-family:'Times New Roman';margin-left:20;margin-top:20;\" width=\"98%\" height=\"98%\">
										<tr>
											<td><span style=\"font-style:italic;\"><b>Comments</b></span>&nbsp;<span style=\"text-decoration:underline;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
											<br><br><br><br><br><br><br><br><br><br><br><br><br><br>
											</td>
										</tr>
										</table>
									</td>
								</tr>
								<tr><td height=\"50\"></td></tr>
								</table>
							</td>
						</tr>";
				
				$html.= "<tr>
							<td width=\"1270\" align=\"right\" class=\"tabelBorderBottomJust\" style=\"font:1em 'Times New Roman';font-style:italic;border-style:dashed;border-color:#000000;position:absolute;top:".$marginTop3.";\">
							<br><br><br><br><br>Cut this line
							</td>
						</tr>";
						
				$html.= "<tr><td height=\"50\"></td></tr>";	
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
		$remark = $this->CPublic->konversiQuotes( $row['remark'] );
		
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
		$desc = $this->CPublic->konversiQuotes( $row['description'] );
	
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