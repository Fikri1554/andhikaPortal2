<?php
class CPaymentAdv
{
	function CPaymentAdv($koneksiMysql, $koneksiOdbc, $koneksiOdbcId, $CPublic)
	{
		$this->koneksi = $koneksiMysql;
		$this->koneksiOdbc = $koneksiOdbc;
		$this->koneksiOdbcId = $koneksiOdbcId;
		$this->CPublic = $CPublic;
	}

	function getsearchBatchNoDay()
	{
		$tabel = "";
		$thnBlnNow = date("Ym");

		$tglNya = $this->CPublic->zerofill($this->CPublic->waktuServer("tanggal"), 2);

		$tabel.= "<option value=\"all\">All</option>";
		$tabel.= "<option value=\"".$tglNya."\">".$tglNya."</option>";

		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT(SUBSTR(batchno, 7, 2)) AS tglNya FROM datapayment WHERE st_delete = '0' AND SUBSTR(batchno, 1, 6) = '".$thnBlnNow."' ORDER BY batchno DESC", $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			if($tglNya != $row['tglNya'])
			{
				$tabel.="<option value=\"".$row['tglNya']."\">".$row['tglNya']."</option>";
			}
		}
		
		return $tabel;
	}

	function cekAksesBtn($userId, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tbluserjenis WHERE userid = '".$userId."';", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);

		$nilai = $row[$field];
		
		return $nilai;
	}

	function aksesBtn($userId, $tambahanDepan, $field, $idElement)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tbluserjenis WHERE userid = '".$userId."';", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		if($row[$field] == "N")
		{
			$nilai = $tambahanDepan."disabledBtn('".$idElement."');";
		}
		if($row[$field] == "Y")
		{
			$nilai = $tambahanDepan."enabledBtn('".$idElement."');";
		}
		
		return $nilai;
	}
	
	function aksesElement($userId, $tambahanDepan, $field, $idElement)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tbluserjenis WHERE userid = '".$userId."';", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		if($row[$field] == "N")
		{
			$nilai = $tambahanDepan."disabledElement('".$idElement."');";
		}
		if($row[$field] == "Y")
		{
			$nilai = $tambahanDepan."enabledElement('".$idElement."');";
		}
		
		return $nilai;
	}

	function getsearchBatchNoThnBln()
	{
		$tabel = "";

		$thnBlnSek = $this->CPublic->waktuServer("tahun").$this->CPublic->zerofill($this->CPublic->waktuServer("bulan"), 2);
		$tabel.= "<option value=\"".$thnBlnSek."\">".$thnBlnSek."</option>";

		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT(SUBSTR(batchno, 1, 6)) AS thnbln FROM datapayment WHERE st_delete = '0' ORDER BY batchno DESC", $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			if($thnBlnSek != $row['thnbln'])
			{
				$tabel.="<option value=\"".$row['thnbln']."\">".$row['thnbln']."</option>";
			}
		}
		
		return $tabel;
	}

	function getsearchBatchNoByThnBln($thnBln)
	{
		$tabel = "";

		$tabel.="<option value=\"all\">All</option>";

		$query = $this->koneksi->mysqlQuery("SELECT DISTINCT(SUBSTR(batchno, 7, 2)) AS tglNya, SUBSTR(batchno, 1, 6) AS thnbln, batchno FROM datapayment WHERE SUBSTR(batchno, 1, 6)='".$thnBln."' AND st_delete=0 ORDER BY tglNya DESC;", $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$tabel.="<option value=\"".$row['tglNya']."\">".$row['tglNya']."</option>";
		}
		
		return $tabel;
	}

	function detilTblUserjns($userId, $field)
	{
		$query = $this->koneksi->mysqlQuery("SELECT ".$field." FROM tbluserjenis WHERE userid = '".$userId."';", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);
		
		return $row[$field];
	}

	function getOptSenderVender()
	{
		$paramPost = str_replace("'", "''", $_POST['param']);
		$optNya = "";

		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT Acctcode, RTRIM(AcctIndo) AS AcctIndo, RTRIM(Acctname) AS Acctname FROM AccountCode WHERE (SUBSTRING(Acctcode ,1,2) = '11' OR SUBSTRING(Acctcode ,1,2) = '15' OR LEFT(Acctcode, 2) = '01' OR LEFT(Acctcode, 2) = '17' OR LEFT(Acctcode ,2) >= '50') AND LEN(RTRIM(Acctcode))=5 ORDER BY Acctname ASC;");
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$optNya .="<option value=\"".$row['Acctcode']."\" ".$sel.">".strtoupper( $row['Acctname'] )."</option>";
		}
		return $optNya;
	}

	function cekTableExist($table)
	{
		$nilai = "tidak";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT COUNT(table_name) AS aaa FROM information_schema.tables WHERE table_name = '".$table."'");
		$row = $this->koneksiOdbc->odbcFetch($query);
		if($row['aaa'] == "1")
		{
			$nilai = "ada";
		}
		
		return $nilai;
	}

	function menuCmp($idCmp)
	{
		$tabel = "";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT compcode, RTRIM(compname) as compname FROM Company WHERE noactive=0 ORDER BY compname ASC");
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$sel = "";
			if($row['compcode'] == $idCmp AND $idCmp != "")
			{
				$sel = "selected";
			}
			$tabel.="<option value=\"".$row['compcode']."\" ".$sel.">".strtoupper( $row['compname'] )."</option>";
		}
		return $tabel;
	}

	function menuVessel($idVsl)
	{
		$optNya = "";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT * FROM Vesseltbl WHERE deletests = '0' AND showvsl = '1' AND Handler IS NOT NULL AND shortname IS NOT NULL ORDER BY Fullname ASC");
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$sel = "";
			if($row['Vescode'] == $idVsl)
			{
				$sel = "selected";
			}
			$optNya.="<option value=\"".$row['Vescode']."\" ".$sel.">".trim($row['Fullname'])."</option>";
		}
		
		return $optNya;
	}

	function getVoyageNo($voyNo,$voyYear,$vslCode)
	{
		$optNya = "";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT * FROM VesselVoy WHERE voyyear = '".$voyYear."' AND vslcode = '".$vslCode."' ORDER BY voyage ASC");
		
		$optNya.="<option value=\"\">-- SELECT VOYAGE --</option>";
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$sel = "";
			if($row['voyage'] == $voyNo AND $voyNo != "")
			{
				$sel = "selected";
			}
			$optNya.="<option value=\"".$row['voyage']."\" ".$sel.">".$row['voyage']."</option>";
		}
		
		return $optNya;
	}

	function menuUnit($idUnit)
	{
		$tabel = "";
		$query = $this->koneksi->mysqlQuery("SELECT idunit, nmunit FROM mstunit ORDER BY nmunit ASC", $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$sel = "";
			if($row['nmunit'] == $idUnit)
			{
				$sel = "selected";
			}
			$tabel.="<option value=\"".$row['idunit']."\" ".$sel.">".$row['nmunit']."</option>";
		}
		
		return $tabel;
	}

	function menuCurrency($currCode)
	{
		$tabel = "";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT Currcode, Currname FROM currencytbl");
		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$sel = "";
			if($row['Currcode'] == $currCode)
			{
				$sel = "selected";
			}
			$tabel.="<option value=\"".$row['Currcode']."\" ".$sel.">".$row['Currcode']." - ( ".$row['Currname']." )</option>";
		}
		
		return $tabel;
	}

	function getDataConfirm($batchno,$typeNya = '',$userId = '')
	{
		$trNya = "";
		$thnBlnNow = date("Ym");
		$no = 1;
		$nmDiv = "";

		if($batchno != "")
		{
			$thnBlnNow = $batchno;
		}

		if($typeNya != 'admin')
		{
			$cekDiv = "";
			$sql = "SELECT * FROM mst_confirm WHERE user_id = '".$userId."'";
			$rsl = $this->koneksi->mysqlQuery($sql, $this->koneksi->bukaKoneksi());			

			while($rows = $this->koneksi->mysqlFetch($rsl))
			{
				if($cekDiv == "")
				{
					$cekDiv = "'".$rows['divisi_name']."'";
				}else{
					$cekDiv .= ",'".$rows['divisi_name']."'";
				}
			}
			$nmDiv = " AND divisi IN(".$cekDiv.")";
		}
		
		$sql = "SELECT * FROM datapayment WHERE st_delete = '0' AND st_submit = 'Y' AND st_confirm = 'N' AND batchno LIKE '%".$thnBlnNow."%' ".$nmDiv." ORDER BY batchno DESC";

		$query = $this->koneksi->mysqlQuery($sql, $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$fileNya = "";

			$imgNya = "<input type=\"checkbox\" value=\"".$row['id']."\" name=\"idDataNya[]\" onclick=\"onlickNya('".$row['id']."');\">";

			if($row['file_upload'] != "")
			{
				$fileNya = " <a href=\"./templates/fileUpload/".$row['file_upload']."\" target=\"_blank\" title=\"View File\">";
				$fileNya .= "<img src=\"./picture/document-text-image.png\" width=\"12\" title=\"CHECK\"></a>";
			}

			$amountNya = $row['amount'];

			$cekAmountCR = $this->koneksi->mysqlQuery("SELECT SUM(amount) AS amountCr FROM payment_split WHERE st_delete = '0' AND id_payment = '".$row['id']."' AND type_dbcr = 'cr'", $this->koneksi->bukaKoneksi());
			while($rowsCR = $this->koneksi->mysqlFetch($cekAmountCR))
			{
				$amountNya = $amountNya + $rowsCR['amountCr'];
			}

			$trNya .= "<tr id=\"idTr_".$no."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\" style=\"cursor:pointer;padding-bottom:1px;\">";
				$trNya .= "<td class=\"tabelBorderBottomJust\" style=\"font:0.7em sans-serif;width:30px;padding:2px;\" align=\"center\">".$imgNya.$fileNya."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:20px;padding:2px;\" align=\"center\">".$no."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:200px;padding:2px;\">".$row['company_name']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:150px;padding:2px;\">".$row['request_name']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:130px;padding:2px;\" align=\"right\">(".$row['currency'].") ".number_format($amountNya,2)."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:100px;padding:2px;\" align=\"center\">".$row['barcode']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:200px;padding:2px;\">".$row['divisi']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:100px;padding:2px;\" align=\"center\">".$this->CPublic->convTglNonDB($row['invoice_date'])."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:150px;padding:2px;\">".$row['mailinvno']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:335px;padding:2px;\">".$row['remark']."</td>";
			$trNya .= "</tr>";

			$no++;
		}
		
		return $trNya;
	}

	function getDataCheck($batchno)
	{
		$trNya = "";
		$thnBlnNow = date("Ym");
		$no = 1;

		if($batchno != "")
		{
			$thnBlnNow = $batchno;
		}

		$query = $this->koneksi->mysqlQuery("SELECT * FROM datapayment WHERE st_delete = '0' AND st_confirm = 'Y' AND st_check = 'N' AND batchno LIKE '%".$thnBlnNow."%' ORDER BY batchno DESC", $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$onclickNya = "onClick=\"onlickNya('".$no."','".$row['id']."','give');\"";
			$fileNya = "";

			if($row['st_check'] == "Y")
			{
				$onclickNya = "onClick=\"onlickNya('".$no."','".$row['id']."','');\"";
			}

			if($row['file_upload'] != "")
			{
				$fileNya = "<a href=\"./templates/fileUpload/".$row['file_upload']."\" target=\"_blank\" title=\"View File\">";
				$fileNya .= "<img src=\"./picture/document-text-image.png\" width=\"12\" title=\"CHECK\"></a>";
			}

			$trNya .= "<tr id=\"idTr_".$no."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\" style=\"cursor:pointer;padding-bottom:1px;\" ".$onclickNya.">";
				$trNya .= "<td class=\"tabelBorderBottomJust\" style=\"font:0.7em sans-serif;width:30px;padding:2px;\" align=\"center\">".$fileNya."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:20px;padding:2px;\" align=\"center\">".$no."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:200px;padding:2px;\">".$row['company_name']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:150px;padding:2px;\">".$row['request_name']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:200px;padding:2px;\">".$row['divisi']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:100px;padding:2px;\" align=\"center\">".$row['barcode']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:100px;padding:2px;\" align=\"center\">".$this->CPublic->convTglNonDB($row['invoice_date'])."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:150px;padding:2px;\">".$row['mailinvno']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:130px;padding:2px;\" align=\"right\">(".$row['currency'].") ".number_format($row['amount'],2)."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:335px;padding:2px;\">".$row['remark']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:335px;padding:2px;\">".$row['reject_remark']."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$sqlStlmt = $this->koneksi->mysqlQuery("SELECT * FROM datapayment WHERE st_delete = '0' AND st_bukti = 'Y' AND st_settlement = 'Y' AND st_settlementCheck = 'N' AND batchno LIKE '%".$thnBlnNow."%' ORDER BY batchno DESC", $this->koneksi->bukaKoneksi());
		while($rowStlmt = $this->koneksi->mysqlFetch($sqlStlmt))
		{
			$onclickNya = "onClick=\"onclickSettlement('".$no."','".$rowStlmt['id']."','giveSettlement');\"";
			$fileNya = "";
			$fileBukti = "";
			$fileSettlement = "";
			if($rowStlmt['file_upload'] != "")
			{
				$fileNya = "<a href=\"./templates/fileUpload/".$rowStlmt['file_upload']."\" target=\"_blank\" title=\"View File\">";
				$fileNya .= "<img src=\"./picture/document-text-image.png\" width=\"12\" title=\"View File\"></a>";
			}
			if($rowStlmt['bukti_file'] != "")
			{
				$fileBukti = " <a href=\"./templates/fileUploadBukti/".$rowStlmt['bukti_file']."\" target=\"_blank\" title=\"Bukti Transfer\">";
				$fileBukti .= "<img src=\"./picture/Presentation-blue-32.png\" width=\"12\" title=\"Bukti Transfer\"></a>";
			}
			if($rowStlmt['settlement_file'] != "")
			{
				$fileSettlement = " <a href=\"./templates/fileUploadBukti/".$rowStlmt['settlement_file']."\" target=\"_blank\" title=\"File Settlement\">";
				$fileSettlement .= "<img src=\"./picture/door-open-out.png\" width=\"12\" title=\"File Settlement\"></a>";
			}
			$trNya .= "<tr id=\"idTrSet_".$no."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='#E5CDFC';\" style=\"cursor:pointer;padding-bottom:1px;background-color:#E5CDFC;\" ".$onclickNya.">";
				$trNya .= "<td class=\"tabelBorderBottomJust\" style=\"font:0.7em sans-serif;width:30px;padding:2px;\" align=\"center\">".$fileNya.$fileBukti.$fileSettlement."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:20px;padding:2px;\" align=\"center\">".$no."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:200px;padding:2px;\">".$rowStlmt['company_name']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:150px;padding:2px;\">".$rowStlmt['request_name']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:200px;padding:2px;\">".$rowStlmt['divisi']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:100px;padding:2px;\" align=\"center\">".$rowStlmt['barcode']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:100px;padding:2px;\" align=\"center\">".$this->CPublic->convTglNonDB($rowStlmt['invoice_date'])."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:150px;padding:2px;\">".$rowStlmt['mailinvno']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:130px;padding:2px;\" align=\"right\">(".$rowStlmt['currency'].") ".number_format($rowStlmt['amount'],2)."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:335px;padding:2px;\">".$rowStlmt['settlement_remark']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:335px;padding:2px;\">".$rowStlmt['reject_remark']."</td>";
			$trNya .= "</tr>";

			$no++;
		}
		return $trNya;
	}

	function getDataApprove($batchno)
	{
		$trNya = "";
		$thnBlnNow = date("Ym");
		$no = 1;

		if($batchno != "")
		{
			$thnBlnNow = $batchno;
		}
		
		$query = $this->koneksi->mysqlQuery("SELECT * FROM datapayment WHERE st_delete = '0' AND st_check = 'Y' AND st_approve = 'N' AND batchno LIKE '%".$thnBlnNow."%' ORDER BY batchno DESC", $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$onclickNya = "onClick=\"onlickNya('".$no."','".$row['id']."','approve');\"";
			$imgNya = "";
			$fileNya = "";

			if($row['st_approve'] == "Y")
			{
				$onclickNya = "onClick=\"onlickNya('".$no."','".$row['id']."','');\"";

				$imgNya = "<img src=\"../picture/document-task.png\" width=\"14\" title=\"APPROVE\">";
			}else{
				$imgNya = "<input type=\"checkbox\" value=\"".$row['id']."\" class=\"chkApprove\" onclick=\"onlickNya('".$no."','".$row['id']."','approve');\">";
			}

			if($row['file_upload'] != "")
			{
				$fileNya = " <a href=\"./templates/fileUpload/".$row['file_upload']."\" target=\"_blank\" title=\"View File\">";
				$fileNya .= "<img src=\"./picture/document-text-image.png\" width=\"12\" title=\"CHECK\"></a>";
			}

			$amountNya = $row['amount'];

			$cekAmountCR = $this->koneksi->mysqlQuery("SELECT SUM(amount) AS amountCr FROM payment_split WHERE st_delete = '0' AND id_payment = '".$row['id']."' AND type_dbcr = 'cr'", $this->koneksi->bukaKoneksi());
			while($rowsCR = $this->koneksi->mysqlFetch($cekAmountCR))
			{
				$amountNya = $amountNya + $rowsCR['amountCr'];
			}

			$trNya .= "<tr id=\"idTr_".$no."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\" style=\"cursor:pointer;padding-bottom:1px;\" ".$onclickNya.">";
				$trNya .= "<td class=\"tabelBorderBottomJust\" style=\"font:0.7em sans-serif;width:30px;padding:2px;\" align=\"center\">".$imgNya.$fileNya."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:20px;padding:2px;\" align=\"center\">".$no."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:200px;padding:2px;\">".$row['company_name']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:150px;padding:2px;\">".$row['request_name']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:130px;padding:2px;\" align=\"right\">(".$row['currency'].") ".number_format($amountNya,2)."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:100px;padding:2px;\" align=\"center\">".$row['barcode']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:200px;padding:2px;\">".$row['divisi']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:100px;padding:2px;\" align=\"center\">".$this->CPublic->convTglNonDB($row['invoice_date'])."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:150px;padding:2px;\">".$row['mailinvno']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:335px;padding:2px;\">".$row['remark']."</td>";
				$trNya .= "<input type=\"hidden\" value=\"approve\" id=\"txtTypeDataApprove_".$row['id']."\" >";
			$trNya .= "</tr>";

			$no++;
		}
		$sqlStlmt = $this->koneksi->mysqlQuery("SELECT * FROM datapayment WHERE st_delete = '0' AND st_bukti = 'Y' AND st_settlement = 'Y' AND st_settlementCheck = 'Y' AND st_settlementApprove = 'N' AND settlement_voucher_status = 'N' AND batchno LIKE '%".$thnBlnNow."%' ORDER BY batchno DESC", $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($sqlStlmt))
		{
			$onclickNya = "onClick=\"onlickNya('".$no."','".$row['id']."','approve');\"";
			$imgNya = "";
			$fileNya = "";

			$imgNya = "<input type=\"checkbox\" value=\"".$row['id']."\" class=\"chkApproveSettle\" onclick=\"onlickNya('".$no."','".$row['id']."','approveSettlement');\">";

			if($row['settlement_file'] != "")
			{
				$fileNya = " <a href=\"./templates/fileUploadBukti/".$row['settlement_file']."\" target=\"_blank\" title=\"View File\">";
				$fileNya .= "<img src=\"./picture/document-text-image.png\" width=\"12\" title=\"Settlement\"></a>";
			}

			// $amountNya = $row['settlement_amount'];
			$amountNya = 0;

			$cekAmountCR = $this->koneksi->mysqlQuery("SELECT SUM(amount) AS amountCr FROM payment_split_settlement WHERE st_delete = '0' AND id_payment = '".$row['id']."' AND type_dbcr = 'cr'", $this->koneksi->bukaKoneksi());
			while($rowsCR = $this->koneksi->mysqlFetch($cekAmountCR))
			{
				$amountNya = $amountNya + $rowsCR['amountCr'];
			}

			$trNya .= "<tr id=\"idTr_".$no."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='#E5CDFC';\" style=\"cursor:pointer;padding-bottom:1px;background-color:#E5CDFC;\" ".$onclickNya.">";
				$trNya .= "<td class=\"tabelBorderBottomJust\" style=\"font:0.7em sans-serif;width:30px;padding:2px;\" align=\"center\">".$imgNya.$fileNya."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:20px;padding:2px;\" align=\"center\">".$no."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:200px;padding:2px;\">".$row['company_name']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:150px;padding:2px;\">".$row['request_name']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:130px;padding:2px;\" align=\"right\">(".$row['currency'].") ".number_format($amountNya,2)."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:100px;padding:2px;\" align=\"center\">".$row['barcode']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:200px;padding:2px;\">".$row['divisi']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:100px;padding:2px;\" align=\"center\">".$this->CPublic->convTglNonDB($row['invoice_date'])."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:150px;padding:2px;\">".$row['mailinvno']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:335px;padding:2px;\">".$row['settlement_remark']."</td>";
				$trNya .= "<input type=\"hidden\" value=\"approve settlement\" id=\"txtTypeDataApprove_".$row['id']."\" >";
			$trNya .= "</tr>";

			$no++;
		}
		
		return $trNya;
	}

	function getDataRelease($batchno)
	{
		$trNya = "";
		$thnBlnNow = date("Ym");
		$no = 1;

		if($batchno != "")
		{
			$thnBlnNow = $batchno;
		}

		$query = $this->koneksi->mysqlQuery("SELECT * FROM datapayment WHERE st_delete = '0' AND st_check = 'Y' AND st_approve = 'Y' AND st_release = 'N' AND batchno LIKE '%".$thnBlnNow."%' ORDER BY batchno DESC", $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$fileNya = "";
			$onclickNya = "onClick=\"onlickNya('".$no."','".$row['id']."','release');\"";

			$imgNya = "<input type=\"checkbox\" value=\"".$row['id']."\" class=\"chkRelease\" onclick=\"onlickNya('".$no."','".$row['id']."','release');\">";

			if($row['file_upload'] != "")
			{
				$fileNya = " <a href=\"./templates/fileUpload/".$row['file_upload']."\" target=\"_blank\" title=\"View File\">";
				$fileNya .= "<img src=\"./picture/document-text-image.png\" width=\"12\" title=\"CHECK\"></a>";
			}

			$amountNya = $row['amount'];

			$cekAmountCR = $this->koneksi->mysqlQuery("SELECT SUM(amount) AS amountCr FROM payment_split WHERE st_delete = '0' AND id_payment = '".$row['id']."' AND type_dbcr = 'cr'", $this->koneksi->bukaKoneksi());
			while($rowsCR = $this->koneksi->mysqlFetch($cekAmountCR))
			{
				$amountNya = $amountNya + $rowsCR['amountCr'];
			}

			$trNya .= "<tr id=\"idTr_".$no."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\" style=\"cursor:pointer;padding-bottom:1px;\" ".$onclickNya.">";
				$trNya .= "<td class=\"tabelBorderBottomJust\" style=\"font:0.7em sans-serif;width:30px;padding:2px;\" align=\"center\">".$imgNya.$fileNya."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:20px;padding:2px;\" align=\"center\">".$no."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:200px;padding:2px;\">".$row['company_name']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:150px;padding:2px;\">".$row['request_name']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:130px;padding:2px;\" align=\"right\">(".$row['currency'].") ".number_format($amountNya,2)."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:100px;padding:2px;\" align=\"center\">".$row['barcode']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:200px;padding:2px;\">".$row['divisi']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:100px;padding:2px;\" align=\"center\">".$this->CPublic->convTglNonDB($row['invoice_date'])."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:150px;padding:2px;\">".$row['mailinvno']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:335px;padding:2px;\">".$row['remark']."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$sqlStlmt = $this->koneksi->mysqlQuery("SELECT * FROM datapayment WHERE st_delete = '0' AND st_settlementCheck = 'Y' AND st_settlementApprove = 'Y' AND st_settlementRelease = 'N' AND batchno LIKE '%".$thnBlnNow."%' ORDER BY batchno DESC", $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($sqlStlmt))
		{
			$fileNya = "";
			$onclickNya = "onClick=\"onlickNya('".$no."','".$row['id']."','release');\"";

			$imgNya = "<input type=\"checkbox\" value=\"".$row['id']."\" class=\"chkReleaseSettle\" onclick=\"onlickNya('".$no."','".$row['id']."','release');\">";

			if($row['settlement_file'] != "")
			{
				$fileNya = " <a href=\"./templates/fileUploadBukti/".$row['settlement_file']."\" target=\"_blank\" title=\"View File\">";
				$fileNya .= "<img src=\"./picture/document-text-image.png\" width=\"12\" title=\"Settlement\"></a>";
			}

			// $amountNya = $row['settlement_amount'];
			$amountNya = 0;

			$cekAmountCR = $this->koneksi->mysqlQuery("SELECT SUM(amount) AS amountCr FROM payment_split_settlement WHERE st_delete = '0' AND id_payment = '".$row['id']."' AND type_dbcr = 'cr'", $this->koneksi->bukaKoneksi());
			while($rowsCR = $this->koneksi->mysqlFetch($cekAmountCR))
			{
				$amountNya = $amountNya + $rowsCR['amountCr'];
			}

			$trNya .= "<tr id=\"idTr_".$no."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='#E5CDFC';\" style=\"cursor:pointer;padding-bottom:1px;background-color:#E5CDFC;\" ".$onclickNya.">";
				$trNya .= "<td class=\"tabelBorderBottomJust\" style=\"font:0.7em sans-serif;width:30px;padding:2px;\" align=\"center\">".$imgNya.$fileNya."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:20px;padding:2px;\" align=\"center\">".$no."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:200px;padding:2px;\">".$row['company_name']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:150px;padding:2px;\">".$row['request_name']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:130px;padding:2px;\" align=\"right\">(".$row['currency'].") ".number_format($amountNya,2)."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:100px;padding:2px;\" align=\"center\">".$row['barcode']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:200px;padding:2px;\">".$row['divisi']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:100px;padding:2px;\" align=\"center\">".$this->CPublic->convTglNonDB($row['invoice_date'])."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:150px;padding:2px;\">".$row['mailinvno']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:335px;padding:2px;\">".$row['settlement_remark']."</td>";
				$trNya .= "<input type=\"hidden\" value=\"release settlement\" id=\"txtTypeDataRelease_".$row['id']."\" >";
			$trNya .= "</tr>";

			$no++;
		}
		
		return $trNya;
	}

	function getDataPrepareForPayment($typeSearch,$txtSearch,$fieldSearch)
	{
		$trNya = "";
		$field = "";
		$valRow = "request_name";
		$thnBlnNow = date("Ymd");
		$no = 1;
		$whereNya = "WHERE st_delete = '0' AND st_check = 'Y' AND st_approve = 'Y' AND st_release = 'Y' ";

		if($typeSearch != "")
		{
			if($fieldSearch == "barcode")
			{
				$valRow = "barcode";
			}
			if($fieldSearch == "company")
			{
				$valRow = "company_name";
			}
			if($fieldSearch == "invoice")
			{
				$valRow = "mailinvno";
			}
			if($fieldSearch == "requestname")
			{
				$valRow = "request_name";
			}

			if($txtSearch != "")
			{
				$whereNya .= " AND ".$valRow." LIKE '%".$txtSearch."%' ";
			}			
		}

		$query = $this->koneksi->mysqlQuery("SELECT * FROM datapayment ".$whereNya." AND transno = '0' ORDER BY batchno DESC", $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$amountAll = $row['amount'];

			$cekAmountCR = $this->koneksi->mysqlQuery("SELECT SUM(amount) AS amountCr FROM payment_split WHERE st_delete = '0' AND id_payment = '".$row['id']."' AND type_dbcr = 'cr'", $this->koneksi->bukaKoneksi());
			while($rowsCR = $this->koneksi->mysqlFetch($cekAmountCR))
			{
				$amountAll = $amountAll + $rowsCR['amountCr'];
			}

			$onclickNya = "onClick=\"onclickNya('".$no."','".$row['id']."','group');\"";

			$trNya .= "<tr id=\"idTr_".$no."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\" style=\"cursor:pointer;padding-bottom:1px;\" ".$onclickNya.">";				
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:150px;padding:2px;\">".$row[$valRow]."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.6em sans-serif;width:150px;padding:2px;\" align=\"right\">(".$row['currency'].") ".number_format($amountAll,2)."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$sqlStlmt = $this->koneksi->mysqlQuery("SELECT * FROM datapayment ".$whereNya." AND st_bukti = 'Y' AND st_settlement = 'Y' AND st_settlementCheck = 'Y' AND st_settlementApprove = 'Y' AND  st_settlementRelease = 'Y' AND settlement_transno = '0' ORDER BY batchno DESC", $this->koneksi->bukaKoneksi());
		while($rowStlmt = $this->koneksi->mysqlFetch($sqlStlmt))
		{
			$amountAll = $rowStlmt['voucher_amountpaid'];

			$onclickNya = "onClick=\"onclickSettlement('".$no."','".$rowStlmt['id']."','giveSettlement');\"";

			$trNya .= "<tr id=\"idTrSet_".$no."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='#E5CDFC';\" style=\"cursor:pointer;padding-bottom:1px;background-color:#E5CDFC;\" ".$onclickNya.">";				
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:150px;padding:2px;\">".$rowStlmt[$valRow]."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.6em sans-serif;width:150px;padding:2px;\" align=\"right\">(".$rowStlmt['currency'].") ".number_format($amountAll,2)."</td>";
			$trNya .= "</tr>";

			$no++;
		}
		
		return $trNya;
	}

	function getDataPaymentRequest($idPayment)
	{
		$dataOut = array();

		$query = $this->koneksi->mysqlQuery("SELECT * FROM datapayment WHERE st_delete = '0' AND st_check = 'Y' AND st_approve = 'Y' AND st_release = 'Y' AND id = '".$idPayment."' ", $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$dataOut['mailinvno'] = $row['mailinvno'];
			$dataOut['invoicedate'] = $this->CPublic->convTglNonDB($row['invoice_date']);
			$dataOut['invoiceduedate'] = $this->CPublic->convTglNonDB($row['invoice_due_date']);
			$dataOut['remark'] = $row['remark'];
			$dataOut['request_name'] = $row['request_name'];
			$dataOut['barcode'] = $row['barcode'];
			$dataOut['accountsendervendor'] = $row['accountsendervendor'];
			$dataOut['senderVendor'] = $row['sendervendor'];
			$dataOut['initCompany'] = $row['init_company'];
			$dataOut['company'] = $row['company_name'];
			$dataOut['currency'] = $row['currency'];
			$dataOut['amount'] = $row['amount'];
			$dataOut['displayAmount'] = "(".$row['currency'].") ".number_format($row['amount'],2);
			$dataOut['amountFormat'] = number_format($row['amount'],2);
			$dataOut['vesselName'] = $row['vessel_name'];
			$dataOut['voyageNo'] = $row['voyage_no'];
		}
		
		return $dataOut;
	}

	function getDataPaymentRequestSplit($idPayment,$typeDoc)
	{
		$dataOut = array();
		$trNya = "";
		$amountAllCR = 0;
		$amountCR = 0;
		$currNya = "";
		$transNo = 0;

		$query = $this->koneksi->mysqlQuery("SELECT * FROM datapayment WHERE st_delete = '0' AND st_check = 'Y' AND st_approve = 'Y' AND st_release = 'Y' AND id = '".$idPayment."' ", $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$dataOut['mailinvno'] = $row['mailinvno'];
			$dataOut['invoicedate'] = $this->CPublic->convTglNonDB($row['invoice_date']);
			$dataOut['invoiceduedate'] = $this->CPublic->convTglNonDB($row['invoice_due_date']);
			$dataOut['remark'] = $row['remark'];
			$dataOut['request_name'] = $row['request_name'];
			$dataOut['barcode'] = $row['barcode'];
			$dataOut['accountsendervendor'] = $row['accountsendervendor'];
			$dataOut['senderVendor'] = $row['sendervendor'];
			$dataOut['initCompany'] = $row['init_company'];
			$dataOut['company'] = $row['company_name'];
			$dataOut['currency'] = $row['currency'];
			$dataOut['amount'] = $row['amount'];
			$dataOut['displayAmount'] = "(".$row['currency'].") ".number_format($row['amount'],2);
			$dataOut['amountFormat'] = number_format($row['amount'],2);
			$dataOut['vesselName'] = $row['vessel_name'];
			$dataOut['voyageNo'] = $row['voyage_no'];
			$dataOut['settlement_amount'] = $row['settlement_amount'];
			$dataOut['voucher_amountpaid'] = $row['voucher_amountpaid'];
			$dataOut['release_remark'] = $row['release_remark'];
			$amountAllCR = $row['amount'];
			$currNya = $row['currency'];
			$amountCR = $row['amount'];
		}

		if($typeDoc == "giveSettlement")
		{
			$amountCR = $dataOut['settlement_amount'];
			$amountAllCR = $dataOut['voucher_amountpaid'];
			$dataOut['displayAmount'] = "(".$row['currency'].") ".number_format($dataOut['voucher_amountpaid'],2);

			$transNo = "[ ".$this->getLastTransNo()." ] / (Settlement)";

			$sql = $this->koneksi->mysqlQuery("SELECT * FROM payment_split_settlement WHERE st_delete = '0' AND id_payment = '".$idPayment."' ", $this->koneksi->bukaKoneksi());
		}else{
			$sql = $this->koneksi->mysqlQuery("SELECT * FROM payment_split WHERE st_delete = '0' AND id_payment = '".$idPayment."' ", $this->koneksi->bukaKoneksi());
			$transNo = "[ ".$this->getLastTransNo()." ]";
		}

		while($rows = $this->koneksi->mysqlFetch($sql))
		{
			$trNya .= "<tr>";
        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\" height=\"15\">".$dataOut['barcode']."</td>";
        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\">".$dataOut['invoicedate']."</td>";
        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\">".$dataOut['invoiceduedate']."</td>";        
        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\">".$dataOut['mailinvno']."</td>";
        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\">".$rows['type_dbcr']."</td>";
        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"right\">(".$dataOut['currency'].") ".number_format($rows['amount'],2)."</td>";
        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\">".$rows['vessel_name']."</td>";
        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\">".$rows['voyage_no']."</td>";
        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"padding-left:5px;\">".$rows['description']."</td>";
    		$trNya .= "</tr>";

    		if($rows['type_dbcr'] == "CR" AND $typeDoc != "giveSettlement")
    		{
    			$amountAllCR = $amountAllCR + $rows['amount'];
    		}
		}

		$dataOut['trNya'] = $trNya;
		$dataOut['amountAllCR'] = number_format($amountAllCR,2);
		$dataOut['amountAllCRDisplay'] = "(".$currNya.") ".number_format($amountAllCR,2);
		$dataOut['amountAllCROri'] = $amountAllCR;
		$dataOut['amountCR'] = $amountCR;
		$dataOut['amountCRDisplay'] = "(".$currNya.") ".number_format($amountCR,2);
		$dataOut['transNo'] = $transNo;
		return $dataOut;
	}
	function getDataVoucher($typeSearch,$txtSearch)
	{
		$trNya = "";
		$thnBlnNow = date("Ymd");
		$no = 1;
		$whereNya = "WHERE st_delete = '0' AND st_check = 'Y' AND st_approve = 'Y' AND st_release = 'Y' ";
		$whereSettlementNya = "WHERE st_delete = '0' AND st_check = 'Y' AND st_approve = 'Y' AND st_release = 'Y' ";

		if($typeSearch != "")
		{
			$whereSettlementNya .= " AND settlement_transno LIKE '%".$txtSearch."%' ";
			$whereNya .= " AND transno LIKE '%".$txtSearch."%' ";			
		}

		$query = $this->koneksi->mysqlQuery("SELECT transno,voucher_status,company_name,st_transferToAcct FROM datapayment ".$whereNya." AND transno > 0 GROUP BY transno ORDER BY transno DESC", $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($query))
		{
			$stPaid = "-";			

			if($row['voucher_status'] == "Y" AND $row['st_transferToAcct'] == "Y")
			{
				$stPaid = "<img src=\"./picture/tick.png\" width=\"12\" title=\"ALREADY TRANSFER\">";
			}
			if($row['voucher_status'] == "Y" AND $row['st_transferToAcct'] == "N")
			{
				$stPaid = "<img src=\"./picture/hourglass-select-remain.png\" width=\"12\" title=\"WATTING TO TRANSFER\">";
			}

			$onclickNya = "onClick=\"onclickNya('".$no."','".$row['transno']."');\"";

			$trNya .= "<tr id=\"idTr_".$no."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\" style=\"cursor:pointer;padding-bottom:1px;\" ".$onclickNya.">";				
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:50px;padding:2px;\" align=\"center\">".$stPaid."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:100px;padding:2px;\">".$this->getFormatNo($row['transno'],6)."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:200px;padding:2px;\">".$row['company_name']."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$sqlStlmt = $this->koneksi->mysqlQuery("SELECT settlement_transno,settlement_voucher_status,company_name,settlement_transferToAcct FROM datapayment ".$whereSettlementNya." AND settlement_transno > 0 AND st_bukti = 'Y' AND st_settlement = 'Y' AND st_settlementCheck = 'Y' GROUP BY transno ORDER BY transno DESC", $this->koneksi->bukaKoneksi());
		while($rowStlmt = $this->koneksi->mysqlFetch($sqlStlmt))
		{
			$stPaid = "-";			

			if($rowStlmt['settlement_voucher_status'] == "Y" AND $rowStlmt['settlement_transferToAcct'] == "Y")
			{
				$stPaid = "<img src=\"./picture/tick.png\" width=\"12\" title=\"ALREADY TRANSFER\">";
			}
			if($rowStlmt['settlement_voucher_status'] == "Y" AND $rowStlmt['settlement_transferToAcct'] == "N")
			{
				$stPaid = "<img src=\"./picture/hourglass-select-remain.png\" width=\"12\" title=\"WATTING TO TRANSFER\">";
			}

			$onclickNya = "onClick=\"onclickSettlementNya('".$no."','".$rowStlmt['settlement_transno']."','giveSettlement');\"";

			$trNya .= "<tr id=\"idTrSet_".$no."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='#E5CDFC';\" style=\"cursor:pointer;padding-bottom:1px;background-color:#E5CDFC;\" ".$onclickNya.">";				
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:50px;padding:2px;\" align=\"center\">".$stPaid."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:100px;padding:2px;\">".$this->getFormatNo($rowStlmt['settlement_transno'],6)."(Settlement)</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font:0.7em sans-serif;width:200px;padding:2px;\">".$rowStlmt['company_name']."</td>";
			$trNya .= "</tr>";

			$no++;
		}
		
		return $trNya;
	}

	function getDataVoucherByTransno($transno,$typeDoc)
	{
		$dataOut = array();
		$trNya = "";
		$totalNya = 0;
		$totalExpense = 0;

		if($typeDoc == "giveSettlement")
		{
			$query = $this->koneksi->mysqlQuery("SELECT * FROM datapayment WHERE st_delete = '0' AND settlement_transno = '".$transno."' ", $this->koneksi->bukaKoneksi());
			while($row = $this->koneksi->mysqlFetch($query))
			{
				$imgNya = "";

				$dataOut['paymentGrouping']['company'] = $row['company_name'];
				$dataOut['paymentGrouping']['senderVendor'] = $row['sendervendor'];
				$dataOut['paymentGrouping']['curr'] = $row['currency'];
				$dataOut['paymentGrouping']['stVoucher'] = $row['settlement_voucher_status'];
				$dataOut['paymentGrouping']['slcMethod'] = $row['settlement_voucher_method'];
				$dataOut['paymentGrouping']['slcBank'] = $row['settlement_voucher_bank'];
				$dataOut['paymentGrouping']['txtVoucherNo'] = $row['settlement_voucher_no'];
				$dataOut['paymentGrouping']['txtRef'] = $row['settlement_voucher_referenceno'];
				$dataOut['paymentGrouping']['txtDatePaid'] = $this->CPublic->convTglNonDB($row['settlement_voucher_datepaid']);
				$dataOut['paymentGrouping']['slcCurr'] = $row['settlement_voucher_amountcurr'];
				$dataOut['paymentGrouping']['txtAmount'] = number_format($row['settlement_voucher_amountpaid'],0);
				$dataOut['paymentGrouping']['txtBankCharges'] = number_format($row['settlement_voucher_bankcharges'],0);
				$dataOut['paymentGrouping']['txtPaidToFrom'] = $row['settlement_voucher_paidtofrom'];
				$dataOut['paymentGrouping']['txtCheqNo'] = $row['settlement_voucher_cheqno'];
				$dataOut['paymentGrouping']['txtStTransToAcct'] = $row['settlement_transferToAcct'];

				$totalNya = $totalNya + $row['amount'];
				$totalExpense = $totalExpense + $row['settlement_amount'];

				$barcode = $row['barcode'];

				if($row['file_upload'] != "")
				{
					$imgNya = "<a href=\"./templates/fileUpload/".$row['file_upload']."\" target=\"_blank\" title=\"View File\">";
					$imgNya .= "<img src=\"./picture/document-text-image.png\" width=\"12\" title=\"View File\"></a>";
					$barcode = "<a href=\"./templates/fileUpload/".$row['file_upload']."\" target=\"_blank\" title=\"View File\">".$row['barcode']."</a>";
				}

				$sql = $this->koneksi->mysqlQuery("SELECT * FROM payment_split_settlement WHERE st_delete = '0' AND id_payment = '".$row['id']."' ", $this->koneksi->bukaKoneksi());
				while($rows = $this->koneksi->mysqlFetch($sql))
				{
					$inptNya = "<input type=\"text\" id=\"txtDesc_".$row['id']."\" class=\"elementInput\" style=\"width:100%;\" value=\"".$rows['description']."\" disabled=\"disabled\">";
					$inptNya .= "<input type=\"hidden\" id=\"txtIdPaySplit_".$rows['id']."\" value=\"".$rows['id']."\">";
					
					$trNya .= "<tr>";
		        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\" height=\"15\">".$imgNya." ".$barcode."</td>";
		        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\">".$row['invoice_date']."</td>";
		        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\">".$row['invoice_due_date']."</td>";        
		        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\">".$row['mailinvno']."</td>";
		        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\">".$rows['type_dbcr']."</td>";
		        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"right\">(".$row['currency'].") ".number_format($rows['amount'],2)."</td>";
		        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\">".$rows['vessel_name']."</td>";
		        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\">".$rows['voyage_no']."</td>";
		        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"padding-left:5px;\">".$inptNya."</td>";
		    		$trNya .= "</tr>";
				}
			}
		}else{
			$query = $this->koneksi->mysqlQuery("SELECT * FROM datapayment WHERE st_delete = '0' AND transno = '".$transno."' ", $this->koneksi->bukaKoneksi());
			while($row = $this->koneksi->mysqlFetch($query))
			{
				$imgNya = "";

				$dataOut['paymentGrouping']['company'] = $row['company_name'];
				$dataOut['paymentGrouping']['senderVendor'] = $row['sendervendor'];
				$dataOut['paymentGrouping']['curr'] = $row['currency'];
				$dataOut['paymentGrouping']['stVoucher'] = $row['voucher_status'];
				$dataOut['paymentGrouping']['slcMethod'] = $row['voucher_method'];
				$dataOut['paymentGrouping']['slcBank'] = $row['voucher_bank'];
				$dataOut['paymentGrouping']['txtVoucherNo'] = $row['voucher_no'];
				$dataOut['paymentGrouping']['txtRef'] = $row['voucher_referenceno'];
				$dataOut['paymentGrouping']['txtDatePaid'] = $this->CPublic->convTglNonDB($row['voucher_datepaid']);
				$dataOut['paymentGrouping']['slcCurr'] = $row['voucher_amountcurr'];
				$dataOut['paymentGrouping']['txtAmount'] = number_format($row['voucher_amountpaid'],0);
				$dataOut['paymentGrouping']['txtBankCharges'] = number_format($row['voucher_bankcharges'],0);
				$dataOut['paymentGrouping']['txtPaidToFrom'] = $row['voucher_paidtofrom'];
				$dataOut['paymentGrouping']['txtCheqNo'] = $row['voucher_cheqno'];
				$dataOut['paymentGrouping']['txtStTransToAcct'] = $row['st_transferToAcct'];

				$totalNya = $totalNya + $row['amount'];

				$barcode = $row['barcode'];

				if($row['file_upload'] != "")
				{
					$imgNya = "<a href=\"./templates/fileUpload/".$row['file_upload']."\" target=\"_blank\" title=\"View File\">";
					$imgNya .= "<img src=\"./picture/document-text-image.png\" width=\"12\" title=\"View File\"></a>";
					$barcode = "<a href=\"./templates/fileUpload/".$row['file_upload']."\" target=\"_blank\" title=\"View File\">".$row['barcode']."</a>";
				}

				$sql = $this->koneksi->mysqlQuery("SELECT * FROM payment_split WHERE st_delete = '0' AND id_payment = '".$row['id']."' ", $this->koneksi->bukaKoneksi());
				while($rows = $this->koneksi->mysqlFetch($sql))
				{
					$inptNya = "<input type=\"text\" id=\"txtDesc_".$row['id']."\" class=\"elementInput\" style=\"width:100%;\" value=\"".$rows['description']."\" disabled=\"disabled\">";
					$inptNya .= "<input type=\"hidden\" id=\"txtIdPaySplit_".$rows['id']."\" value=\"".$rows['id']."\">";

					$trNya .= "<tr>";
		        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\" height=\"15\">".$imgNya." ".$barcode."</td>";
		        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\">".$row['invoice_date']."</td>";
		        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\">".$row['invoice_due_date']."</td>";        
		        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\">".$row['mailinvno']."</td>";
		        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\">".$rows['type_dbcr']."</td>";
		        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"right\">(".$row['currency'].") ".number_format($rows['amount'],2)."</td>";
		        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\">".$rows['vessel_name']."</td>";
		        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\">".$rows['voyage_no']."</td>";
		        		$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"padding-left:5px;\">".$inptNya."</td>";
		    		$trNya .= "</tr>";
				}
			}
		}

		$dataOut['paymentGrouping']['totalNya'] = number_format($totalNya,2);
		$dataOut['trNya'] = $trNya;
		$dataOut['transNoFormat'] = $this->getFormatNo($transno,6);
		
		return $dataOut;
	}

	function codeBank($bankCode)
	{
		$optNya = "";
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "SELECT RTRIM(Acctcode) AS Acctcode, RTRIM(Acctname) AS Acctname FROM dbo.AccountCode WHERE (LEN(RTRIM(Acctcode)) = 5) AND (LEFT(Acctcode, 2) = '10') AND (Contact IS NULL) ORDER BY Acctname");

		while($row = $this->koneksiOdbc->odbcFetch($query))
		{
			$sel = "";
			if($row['Acctcode'] == $bankCode AND $bankCode != "")
			{
				$sel = "selected";
			}
			$optNya.="<option value=\"".$row['Acctcode']."\" ".$sel.">".$row['Acctcode']." - ".$row['Acctname']."</option>";
		}
		
		return $optNya;
	}

	function detilBankSource($acctCode, $field)
	{
		$query = $this->koneksiOdbc->odbcExec($this->koneksiOdbcId, "
			SELECT  TOP (1) Source AS source 
			FROM dbo.AccountCode 
			WHERE Acctcode = '".$acctCode."' ");
		$row = $this->koneksiOdbc->odbcFetch($query);
		
		return $row[$field];
	}

	function getLastTransNo()
	{
		$lastTrans = 0;
		$query = $this->koneksi->mysqlQuery("SELECT MAX( transno ) AS transno, MAX( settlement_transno ) AS settTransno FROM datapayment;", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($query);

		if($row['transno'] > $row['settTransno'])
		{
			$lastTrans = $this->getFormatNo($row['transno'],6);
		}
		else{
			$lastTrans = $this->getFormatNo($row['settTransno'],6);
		}

		return $lastTrans;
	}

	function getNewBarcode()
	{
		$barcodeNew = "1";

		$sql = $this->koneksi->mysqlQuery("SELECT MAX(barcode_no)+1 AS barcodeNo FROM datapayment;", $this->koneksi->bukaKoneksi());
    	$rows = $this->koneksi->mysqlFetch($sql);
		
		if($rows['barcodeNo'] == "")
		{
			$barcodeNew = "1";
		}else{
			$barcodeNew = $rows['barcodeNo'];
		}
		
    	return $barcodeNew;
	}

	function getFormatNo($num, $zerofill = 5) 
	{
	    return str_pad($num, $zerofill, '0', STR_PAD_LEFT); 
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

	function sentNotifMailConfirm($idPayment='')
	{
		$isiMessage = "";
		$subject = "Payment Advance -- Confirm Data";
		$notes = "submitted";
		$emailKe = "";

		$sql = $this->koneksi->mysqlQuery("SELECT * FROM datapayment where id = '".$idPayment."'", $this->koneksi->bukaKoneksi());
    	$rows = $this->koneksi->mysqlFetch($sql);

    	$userInput = $rows['add_userId'];
    	$divisiInput = $rows['divisi'];

    	$sqlCek = $this->koneksi->mysqlQuery("SELECT * FROM mst_mailuser where sub_name = 'confirm' AND divisi = '".$divisiInput."'", $this->koneksi->bukaKoneksi());
    	while($rowsCek = $this->koneksi->mysqlFetch($sqlCek))
    	{
    		if($emailKe == "")
    		{
    			$emailKe = $rowsCek['mail'];
    		}else{
    			$emailKe .= ",".$rowsCek['mail'];
    		}
    	}

    	$query = $this->koneksi->mysqlQuery("SELECT userfullnm,empno FROM andhikaportal.login WHERE userid='".$userInput."' AND ACTIVE='Y' AND deletests=0");
		$row = $this->koneksi->mysqlFetch($query);

		$userInput = $row['userfullnm'];
		$empNo = $row['empno'];

		$isiMessage.= "<table border=0 width=\"100%\" style=\"font-family:Arial Narrow;\" cellpadding=\"0\" cellspacing=\"0\"> ";
			$isiMessage.= "<tr>";
				$isiMessage.= "<td>";
					$isiMessage.= "*************************************************<br>";
					$isiMessage.= "PLEASE DO NOT REPLY THIS EMAIL!<br>";
					$isiMessage.= "*************************************************";
				$isiMessage.= "</td>";
			$isiMessage.= "<tr>";
			$isiMessage.= "<tr>";
				$isiMessage.= "<td align=\"center\">";
					$isiMessage.= "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
						$isiMessage.= "<tr>";
							$isiMessage.= "<td align=\"center\">&nbsp;</td>";
						$isiMessage.= "</tr>";
						$isiMessage.= "<tr>";
							$isiMessage.= "<td align=\"left\">";
								$isiMessage.= "<b>&nbsp;*****&nbsp;";
								$isiMessage.= "<span style=\"text-decoration:underline;\">".$userInput." </span>";
								$isiMessage.= "has ".$notes." Payment Request. It requires your Confirmation.";
								$isiMessage.= "&nbsp;*****</b><br><br>";
							$isiMessage.= "</td>";
						$isiMessage.= "</tr>";
					$isiMessage.= "</table>";
				$isiMessage.= "</td>";
			$isiMessage.= "</tr>";
			
			$isiMessage.= "<tr>";
				$isiMessage.= "<td style=\"border:#CCC solid 1px;font-family:Tahoma;font-size:12px;color:#333;\">";
					$isiMessage.= "<table width=\"700px\" border=0 cellpadding=\"0\" cellspacing=\"0\" style=\"font-size:13px;\">";
						$isiMessage.= "<tr height=\"17px\">";
							$isiMessage.= "<td width=\"17%\" align=\"left\">";
								$isiMessage.= "Name";
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"43%\" align=\"left\" style=\"color:#000080;\">";
								$isiMessage.= $rows['request_name'];
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"17%\" align=\"left\">";
								$isiMessage.= "Barcode";
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"33%\" align=\"left\" style=\"color:#000080;\">";
								$isiMessage.= $rows['barcode'];
							$isiMessage.= "</td>";
						$isiMessage.= "</tr>";
						$isiMessage.= "<tr height=\"17px\">";
							$isiMessage.= "<td width=\"17%\" align=\"left\">";
								$isiMessage.= "Divisi";
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"43%\" align=\"left\" style=\"color:#000080;\">";
								$isiMessage.= $rows['divisi'];
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"17%\" align=\"left\">";
								$isiMessage.= "Inv. Date";
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"33%\" align=\"left\" style=\"color:#000080;\">";
								$isiMessage.= $this->CPublic->convTglPO($rows['invoice_date']);
							$isiMessage.= "</td>";
						$isiMessage.= "</tr>";
						$isiMessage.= "<tr height=\"17px\">";
							$isiMessage.= "<td width=\"17%\" align=\"left\">";
								$isiMessage.= "Company";
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"43%\" align=\"left\" style=\"color:#000080;\">";
								$isiMessage.= $rows['company_name'];
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"17%\" align=\"left\">";
								$isiMessage.= "Due Date";
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"33%\" align=\"left\" style=\"color:#000080;\">";
								$isiMessage.= $this->CPublic->convTglPO($rows['invoice_due_date']);
							$isiMessage.= "</td>";
						$isiMessage.= "</tr>";
						$isiMessage.= "<tr height=\"17px\">";
							$isiMessage.= "<td width=\"17%\" align=\"left\">";
								$isiMessage.= "Inv. No";
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"43%\" align=\"left\" style=\"color:#000080;\">";
								$isiMessage.= $rows['mailinvno'];
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"17%\" align=\"left\">";
								$isiMessage.= "Total";
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"33%\" align=\"left\" style=\"color:#000080;\">";
								$isiMessage.= $rows['currency']." ".number_format($rows['amount'],2);
							$isiMessage.= "</td>";
						$isiMessage.= "</tr>";
					$isiMessage.= "</table>";
				$isiMessage.= "</td>";
			$isiMessage.= "</tr>";

			$isiMessage.= "<tr>";
				$isiMessage.= "<td><br><br>";
					$isiMessage.= "*************************************************<br>";
					$isiMessage.= "END OF NOTIFICATION<br>";
					$isiMessage.= "*************************************************";
				$isiMessage.= "</td>";
			$isiMessage.= "<tr>";

		$isiMessage.="</table> \r\n";

		//$emailKe = "ahmad.maulana@andhika.com";
		if($emailKe != "")
		{
			// mail($emailKe, $subject, $isiMessage, $this->headers());
			$allMail = $emailKe;
			$toDisplay = $emailKe;
			$cc = "";
			$bcc = "";
			$this->sentMailToOut($allMail,$subject,$isiMessage,$toDisplay,$cc,$bcc);
		}
	}

	function sentNotifMail($idPayment='',$subName='',$userIdConfirm='')
	{
		$isiMessage = "";
		$subject = "Payment Advance -- ".$subName." Data";
		$emailKe = "";
		$bfrTeks = "";

		if($subName == "Check")
		{
			$bfrTeks = "Confirmed";
		}
		if($subName == "Approve")
		{
			$bfrTeks = "Checked";
		}
		if($subName == "Release")
		{
			$bfrTeks = "Approved";
		}
		if($subName == "After Release")
		{
			$bfrTeks = "Released";
		}

		$sql = $this->koneksi->mysqlQuery("SELECT * FROM datapayment where id IN(".$idPayment.")", $this->koneksi->bukaKoneksi());

    	$sqlCek = $this->koneksi->mysqlQuery("SELECT * FROM mst_mailuser where sub_name = '".$subName."' ", $this->koneksi->bukaKoneksi());
    	while($rowsCek = $this->koneksi->mysqlFetch($sqlCek))
    	{
    		if($emailKe == "")
    		{
    			$emailKe = $rowsCek['mail'];
    		}else{
    			$emailKe .= ",".$rowsCek['mail'];
    		}
    	}

    	if($subName == "After Release")
		{
			$subject = "Payment Advance -- Prepare For Payment Data";
			$subName = "Prepare For Payment";
		}

    	$query = $this->koneksi->mysqlQuery("SELECT userfullnm FROM andhikaportal.login WHERE userid='".$userIdConfirm."' AND ACTIVE='Y' AND deletests=0");
		$row = $this->koneksi->mysqlFetch($query);

		$userInput = $row['userfullnm'];

		$isiMessage.= "<table border=0 width=\"100%\" style=\"font-family:Arial Narrow;\" cellpadding=\"0\" cellspacing=\"0\"> ";
			$isiMessage.= "<tr>";
				$isiMessage.= "<td>";
					$isiMessage.= "*************************************************<br>";
					$isiMessage.= "PLEASE DO NOT REPLY THIS EMAIL!<br>";
					$isiMessage.= "*************************************************";
				$isiMessage.= "</td>";
			$isiMessage.= "<tr>";
			$isiMessage.= "<tr>";
				$isiMessage.= "<td align=\"center\">";
					$isiMessage.= "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
						$isiMessage.= "<tr>";
							$isiMessage.= "<td align=\"center\">&nbsp;</td>";
						$isiMessage.= "</tr>";
						$isiMessage.= "<tr>";
							$isiMessage.= "<td align=\"left\">";
								$isiMessage.= "<b>&nbsp;*****&nbsp;";
								$isiMessage.= "Payment Request has been ".$bfrTeks." by ";
								$isiMessage.= "<span style=\"text-decoration:underline;\">".$userInput." </span>";
								$isiMessage.= ", Please ".$subName." Data";
								$isiMessage.= "&nbsp;*****</b><br><br>";
							$isiMessage.= "</td>";
						$isiMessage.= "</tr>";
					$isiMessage.= "</table>";
				$isiMessage.= "</td>";
			$isiMessage.= "</tr>";
			
			$isiMessage.= "<tr>";
				$isiMessage.= "<td style=\"font-family:Tahoma;font-size:12px;color:#333;\">";
					$isiMessage.= "<table width=\"800px\" border=1 cellpadding=\"0\" cellspacing=\"0\" style=\"font-size:13px;\">";
						$isiMessage.= "<tr height=\"17px\" style=\"background-color:#966500;color:#FFF;\">";
							$isiMessage.= "<td width=\"20%\" align=\"center\">";
								$isiMessage.= "Name";
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"10%\" align=\"center\">";
								$isiMessage.= "Barcode";
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"25%\" align=\"center\">";
								$isiMessage.= "Company <br>(Divisi)";
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"15%\" align=\"center\">";
								$isiMessage.= "Invoice No";
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"10%\" align=\"center\">";
								$isiMessage.= "Invoice Date";
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"10%\" align=\"center\">";
								$isiMessage.= "Due Date";
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"10%\" align=\"center\">";
								$isiMessage.= "Total";
							$isiMessage.= "</td>";
						$isiMessage.= "</tr>";						

					while($rowVal = $this->koneksi->mysqlFetch($sql))
					{
						$isiMessage.= "<tr height=\"14px\">";
							$isiMessage.= "<td width=\"20%\">";
								$isiMessage.= $rowVal['request_name'];
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"10%\" align=\"center\">";
								$isiMessage.= $rowVal['barcode'];
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"25%\" align=\"center\">";
								$isiMessage.= $rowVal['company_name']."<br>".$rowVal['divisi'];
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"15%\">";
								$isiMessage.= $rowVal['mailinvno'];
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"10%\" align=\"center\">";
								$isiMessage.= $this->CPublic->convTglNonDB($rowVal['invoice_date']);
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"10%\" align=\"center\">";
								$isiMessage.= $this->CPublic->convTglNonDB($rowVal['invoice_due_date']);
							$isiMessage.= "</td>";
							$isiMessage.= "<td width=\"10%\" align=\"center\">";
								$isiMessage.= "(".$rowVal['currency'].") ".$rowVal['amount'];
							$isiMessage.= "</td>";
						$isiMessage.= "</tr>";
					}

					$isiMessage.= "</table>";
				$isiMessage.= "</td>";
			$isiMessage.= "</tr>";

			$isiMessage.= "<tr>";
				$isiMessage.= "<td><br><br>";
					$isiMessage.= "*************************************************<br>";
					$isiMessage.= "END OF NOTIFICATION<br>";
					$isiMessage.= "*************************************************";
				$isiMessage.= "</td>";
			$isiMessage.= "<tr>";

		$isiMessage.="</table> \r\n";

		// echo $isiMessage;
		//$emailKe = "ahmad.maulana@andhika.com";
		if($emailKe != "")
		{
			// mail($emailKe, $subject, $isiMessage, $this->headers());

			$allMail = $emailKe;
			$toDisplay = $emailKe;
			$cc = "";
			$bcc = "";
			$this->sentMailToOut($allMail,$subject,$isiMessage,$toDisplay,$cc,$bcc);
		}
	}

	function getSearchData($typeSearch,$txtSearch,$sDate,$eDate,$userType,$userId)
	{
		$trNya = "";
		$whereNya = " WHERE st_delete = '0' ";
		$i=0;

		if($userType != "admin")
		{
			$whereNya .= " AND add_userId = '".$userId."' ";
		}

		if($txtSearch == "" AND $typeSearch != "complete" AND $typeSearch != "notComplete" AND ($sDate == "" OR $eDate == ""))
		{
			$trNya .= "<tr>";
				$trNya .= "<td colspan=\"3\">- Entry Text Search OR Start & End Date -</td>";
			$trNya .= "</tr>";
		}else{
			if($txtSearch != "")
			{
				if($typeSearch == "barcode")
				{
					$whereNya .= " AND barcode LIKE '%".$txtSearch."%' ";
				}
				else if($typeSearch == "batchno")
				{
					$whereNya .= " AND batchno LIKE '%".$txtSearch."%' ";
				}
				else if($typeSearch == "company")
				{
					$whereNya .= " AND company_name LIKE '%".$txtSearch."%' ";
				}
				else if($typeSearch == "invDate")
				{
					$whereNya .= " AND invoice_date LIKE '%".$txtSearch."%' ";
				}
				else if($typeSearch == "invNo")
				{
					$whereNya .= " AND mailinvno LIKE '%".$txtSearch."%' ";
				}
				else if($typeSearch == "remark")
				{
					$whereNya .= " AND remark LIKE '%".$txtSearch."%' ";
				}
				else if($typeSearch == "reqName")
				{
					$whereNya .= " AND request_name LIKE '%".$txtSearch."%' ";
				}
				else if($typeSearch == "unit")
				{
					$whereNya .= " AND divisi LIKE '%".$txtSearch."%' ";
				}
			}
			if($typeSearch == "complete")
			{
				$whereNya .= " AND st_bukti = 'Y'";
			}
			if($sDate != "" AND $eDate != "")
			{
				$whereNya .= " AND entry_date BETWEEN '".$sDate."' AND '".$eDate."' ";
			}

			$sql = $this->koneksi->mysqlQuery("SELECT * FROM datapayment ".$whereNya, $this->koneksi->bukaKoneksi());
			while($row = $this->koneksi->mysqlFetch($sql))
			{
				if($typeSearch == "complete")
				{
					if($row['doc_type'] == "advance")
					{
						if($row['settlement_voucher_status'] == "Y" AND $row['settlement_transferToAcct'] == "Y")
						{
							$i++;
							$rowColor = $this->CPublic->rowColorCustom($i, "#E8F9E8", "#D5FFD4");
							$onClick = "onClickNya('".$i."', '".$row['id']."','".$rowColor."');";

							$trNya .= "<tr valign=\"bottom\" align=\"left\" bgcolor=\"".$rowColor."\" style=\"cursor:pointer;padding-bottom:1px;font-size:10px;\" id=\"tr_".$i."\" onClick=\"".$onClick."\">";
								$trNya .= "<td height=\"22\" class=\"tabelBorderBottomJust\">&nbsp;".strtoupper($row['request_name'])."</td>";
								$trNya .= "<td height=\"22\" class=\"tabelBorderBottomJust\">".strtoupper($row['company_name'])."</td>";
								$trNya .= "<td height=\"22\" class=\"tabelBorderBottomJust\">".$row['barcode']."</td>";
							$trNya .= "<tr>";
						}
					}else{
						$i++;
						$rowColor = $this->CPublic->rowColorCustom($i, "#E8F9E8", "#D5FFD4");
						$onClick = "onClickNya('".$i."', '".$row['id']."','".$rowColor."');";

						$trNya .= "<tr valign=\"bottom\" align=\"left\" bgcolor=\"".$rowColor."\" style=\"cursor:pointer;padding-bottom:1px;font-size:10px;\" id=\"tr_".$i."\" onClick=\"".$onClick."\">";
							$trNya .= "<td height=\"22\" class=\"tabelBorderBottomJust\">&nbsp;".strtoupper($row['request_name'])."</td>";
							$trNya .= "<td height=\"22\" class=\"tabelBorderBottomJust\">".strtoupper($row['company_name'])."</td>";
							$trNya .= "<td height=\"22\" class=\"tabelBorderBottomJust\">".$row['barcode']."</td>";
						$trNya .= "<tr>";
					}
				}
				else if($typeSearch == "notComplete")
				{
					if($row['doc_type'] == "advance")
					{
						if($row['settlement_transferToAcct'] == "N")
						{
							$i++;
							$rowColor = $this->CPublic->rowColorCustom($i, "#E8F9E8", "#D5FFD4");
							$onClick = "onClickNya('".$i."', '".$row['id']."','".$rowColor."');";

							$trNya .= "<tr valign=\"bottom\" align=\"left\" bgcolor=\"".$rowColor."\" style=\"cursor:pointer;padding-bottom:1px;font-size:10px;\" id=\"tr_".$i."\" onClick=\"".$onClick."\">";
								$trNya .= "<td height=\"22\" class=\"tabelBorderBottomJust\">&nbsp;".strtoupper($row['request_name'])."</td>";
								$trNya .= "<td height=\"22\" class=\"tabelBorderBottomJust\">".strtoupper($row['company_name'])."</td>";
								$trNya .= "<td height=\"22\" class=\"tabelBorderBottomJust\">".$row['barcode']."</td>";
							$trNya .= "<tr>";
						}
					}else{
						if($row['st_bukti'] == "N")
						{
							$i++;
							$rowColor = $this->CPublic->rowColorCustom($i, "#E8F9E8", "#D5FFD4");
							$onClick = "onClickNya('".$i."', '".$row['id']."','".$rowColor."');";

							$trNya .= "<tr valign=\"bottom\" align=\"left\" bgcolor=\"".$rowColor."\" style=\"cursor:pointer;padding-bottom:1px;font-size:10px;\" id=\"tr_".$i."\" onClick=\"".$onClick."\">";
								$trNya .= "<td height=\"22\" class=\"tabelBorderBottomJust\">&nbsp;".strtoupper($row['request_name'])."</td>";
								$trNya .= "<td height=\"22\" class=\"tabelBorderBottomJust\">".strtoupper($row['company_name'])."</td>";
								$trNya .= "<td height=\"22\" class=\"tabelBorderBottomJust\">".$row['barcode']."</td>";
							$trNya .= "<tr>";
						}
					}
				}else{
					$i++;
						$rowColor = $this->CPublic->rowColorCustom($i, "#E8F9E8", "#D5FFD4");
						$onClick = "onClickNya('".$i."', '".$row['id']."','".$rowColor."');";

						$trNya .= "<tr valign=\"bottom\" align=\"left\" bgcolor=\"".$rowColor."\" style=\"cursor:pointer;padding-bottom:1px;font-size:10px;\" id=\"tr_".$i."\" onClick=\"".$onClick."\">";
							$trNya .= "<td height=\"22\" class=\"tabelBorderBottomJust\">&nbsp;".strtoupper($row['request_name'])."</td>";
							$trNya .= "<td height=\"22\" class=\"tabelBorderBottomJust\">".strtoupper($row['company_name'])."</td>";
							$trNya .= "<td height=\"22\" class=\"tabelBorderBottomJust\">".$row['barcode']."</td>";
						$trNya .= "<tr>";
				}
			}
		}

		if($trNya == "")
		{
			$trNya .= "<tr>";
				$trNya .= "<td class=\"tabelBorderBottomJust\" colspan=\"3\" align=\"center\">- Data Empty -</td>";
			$trNya .= "</tr>";
		}
		
		return $trNya;
	}

	function getSearchDataDetail($idPaymentAdv)
	{
		$trNya = "";
		$whereNya = " WHERE st_delete = '0' AND id = '".$idPaymentAdv."' ";

		$sql = $this->koneksi->mysqlQuery("SELECT * FROM datapayment ".$whereNya, $this->koneksi->bukaKoneksi());
		while($row = $this->koneksi->mysqlFetch($sql))
		{
			$typeDoc = "GENERAL";
			$confirmNya = "NO";
			$checkNya = "NO";
			$ApproveNya = "NO";
			$releaseNya = "NO";
			$voucherNya = "NO";
			$paidNya = "NO";
			$settlementNya = "NO";
			$barcodeNya = $row['barcode'];

			if($row['doc_type'] == 'advance')
			{
				$typeDoc = "ADVANCE";
			}

			if($row['st_confirm'] == 'Y')
			{
				$confirmNya = "YES (".$this->CPublic->convTglPO($row['confirm_userDate'])." By : ".$this->getUserNamePortal($row['confirm_userId'],'userfullnm').")";
			}

			if($row['st_check'] == 'Y')
			{
				$checkNya = "YES (".$this->CPublic->convTglPO($row['check_userDate'])." By : ".$this->getUserNamePortal($row['check_userId'],'userfullnm').")";
			}

			if($row['st_approve'] == 'Y')
			{
				$ApproveNya = "YES (".$this->CPublic->convTglPO($row['approve_userDate'])." By : ".$this->getUserNamePortal($row['approve_userId'],'userfullnm').")";
			}

			if($row['st_release'] == 'Y')
			{
				$releaseNya = "YES (".$this->CPublic->convTglPO($row['release_userDate'])." By : ".$this->getUserNamePortal($row['release_userId'],'userfullnm').")";
			}
			
			if($row['voucher_status'] == 'Y')
			{
				$voucherNya = "YES (".$this->CPublic->convTglPO($row['voucher_dateUserId'])." By : ".$this->getUserNamePortal($row['voucher_addUserId'],'userfullnm').")";
			}

			if($row['st_transferToAcct'] == 'Y')
			{
				$paidNya = "YES";
			}

			if($row['st_settlement'] == 'Y')
			{
				$settlementNya = "YES";
			}

			if($row['file_upload'] != '')
			{
				$barcodeNya .= " <a href=\"./templates/fileUpload"."/".$row['file_upload']."\" target=\"_blank\" style=\"color:red;\">View File</a>";
			}

			if($row['bukti_file'] != '')
			{
				$paidNya .= " <a href=\"./templates/fileUploadBukti"."/".$row['bukti_file']."\" target=\"_blank\" style=\"color:red;\">Bukti Transfer</a>";
			}

			if($row['settlement_file'] != '')
			{
				$settlementNya .= " <a href=\"./templates/fileUploadBukti"."/".$row['settlement_file']."\" target=\"_blank\" style=\"color:red;\">File Settlement</a>";
			}

			$userInput = $this->getUserNamePortal($row['add_userId'],'userfullnm');

			$trNya .= "<tr><td>DOCUMENT TYPE : <a>".$typeDoc."</a></td></tr>";
                $trNya .= "<tr><td>BATCHNO : <a>".$row['batchno']."</a></td></tr>";
                $trNya .= "<tr><td>ENTRY ON : <a>".$this->CPublic->convTglPO($row['entry_date'])."</a></td></tr>";
				$trNya .= "<tr><td style=\"padding-left:15px;\">COMPANY : <a>".$row['company_name']."</a></td></tr>";
				$trNya .= "<tr><td style=\"padding-left:15px;\">UNIT : <a>".$row['divisi']."</a></td></tr>";
				$trNya .= "<tr><td style=\"padding-left:15px;\">VESSEL NAME : <a>".$row['vessel_name']."</a></td></tr>";
				$trNya .= "<tr><td style=\"padding-left:15px;\">VOYAGE : <a>".$row['voyage_no']."</a></td></tr>";
				$trNya .= "<tr><td style=\"padding-left:15px;\">BARCODE : <a>".$barcodeNya."</a></td></tr>";
				$trNya .= "<tr><td style=\"padding-left:15px;\">INVOICE DATE : <a>".$this->CPublic->convTglPO($row['invoice_date'])."</a></td></tr>";
				$trNya .= "<tr><td style=\"padding-left:15px;\">DUE / EXPIRED DATE : <a>".$this->CPublic->convTglPO($row['invoice_due_date'])."</a></td></tr>";
				$trNya .= "<tr><td style=\"padding-left:15px;\">INVOICE NUMBER : <a>".$row['mailinvno']."</a></td></tr>";
				$trNya .= "<tr><td style=\"padding-left:15px;\">AMOUNT : <a>(".$row['currency'].") ".number_format($row['amount'],2)."</a></td></tr>";
				$trNya .= "<tr><td style=\"padding-left:15px;\">REMARKS : <a>".$row['remark']."</a></td></tr>";
				$trNya .= "<tr><td>INPUT DATA BY : <a>".$userInput."</a></td></tr>";
				$trNya .= "<tr><td>CONFIRM : <a>".$confirmNya."</a></td></tr>";
				$trNya .= "<tr><td>CHECKED : <a>".$checkNya."</a></td></tr>";
				$trNya .= "<tr><td>APPROVED : <a>".$ApproveNya."</a></td></tr>";
				$trNya .= "<tr><td>RELEASED : <a>".$releaseNya."</a></td></tr>";
				$trNya .= "<tr><td>VOUCHER : <a>".$voucherNya."</a></td></tr>";
				$trNya .= "<tr><td style=\"padding-left:15px;\">PAID : <a>".$paidNya."</a></td></tr>";
				$trNya .= "<tr><td style=\"padding-left:15px;\">PAYMENT METHOD : <a>".$row['voucher_method']."</a></td></tr>";
				$trNya .= "<tr><td style=\"padding-left:15px;\">BANK : <a>".$row['voucher_bank']."</a></td></tr>";
				$trNya .= "<tr><td style=\"padding-left:15px;\">VOUCHER NO : <a>".$row['voucher_no']."</a></td></tr>";
				$trNya .= "<tr><td style=\"padding-left:15px;\">CHEQUE NO : <a>".$row['voucher_cheqno']."</a></td></tr>";
				$trNya .= "<tr><td style=\"padding-left:15px;\">DATE PAID : <a>".$row['voucher_datepaid']."</a></td></tr>";
				$trNya .= "<tr><td style=\"padding-left:15px;\">AMOUNT TO BE PAID : <a>(".$row['voucher_amountcurr'].") ".number_format($row['voucher_amountpaid'])."</a></td></tr>";
				$trNya .= "<tr><td>SETTLEMENT : <a>".$settlementNya."</a></td></tr>";
				$trNya .= "<tr><td style=\"padding-left:15px;\">TOTAL EXPENSES : <a>".$row['voucher_amountcurr'].") ".number_format($row['settlement_amount'])."</a></td></tr>";
			$trNya .= "<tr><td style=\"padding-left:15px;\">REMARK EXPENSES : <a>".$row['settlement_remark']."</a></td></tr>";
		}
		
		return $trNya;
	}
	
	function getUserNamePortal($userId,$field)
	{
		$sql = $this->koneksi->mysqlQuery("SELECT ".$field." FROM andhikaportal.login WHERE userid = '".$userId."' ", $this->koneksi->bukaKoneksi());
		$row = $this->koneksi->mysqlFetch($sql);

		return $row[$field];
	}

	function searchDataChangeFile($txtBarcode, $userId, $userType)
	{
		$trNya = "";
		$cekAllData = $this->cekAksesBtn($userId, "btn_payment_request_allData");

		$whereNya = " WHERE st_delete = '0' AND barcode LIKE '%" . $txtBarcode . "%' ";

		if ($userType == "user" AND $cekAllData == "N") {
			$whereNya .= " AND add_userId = '" . $userId . "'";
		}

		$sql = $this->koneksi->mysqlQuery("SELECT * FROM datapayment " . $whereNya, $this->koneksi->bukaKoneksi());
		while ($row = $this->koneksi->mysqlFetch($sql)) {
			$amount = "";
			$LblAmount = "";

			if ($row['amount'] > 0) {
				$amount = number_format($row['amount'], 2, '.', ',');
				$LblAmount = "(" . $row['currency'] . ") " . number_format($row['amount'], 2, '.', ',');
			}
			
			$settlementAmount = "";
			if ($row['settlement_amount'] > 0) {
				$settlementAmount = "(" . $row['currency'] . ") " . number_format($row['settlement_amount'], 2, '.', ',');
			}
			
			$btnUpload = "<button class=\"btnStandar\" onclick=\"showFormUploadChange('" . $row['id'] . "','" . $row['company_name'] . "','" . $row['divisi'] . "','" . $row['barcode'] . "','" . $LblAmount . "');\">Change</button>";
			
			$btnUpdate = "<button class=\"btnStandar\" style=\"margin-top: 5px;\" onclick=\"showFormUpdate('" . $row['id'] . "','" . $row['company_name'] . "','" . $row['divisi'] . "','" . $row['barcode'] . "','" . $LblAmount . "','" . $settlementAmount . "','" . $row['doc_type'] . "','" . $row['transno'] . "','" . $row['settlement_transno'] . "');\">Update</button>";
			

			$trNya .= "<tr onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\" style=\"cursor:pointer;padding-bottom:1px;\">";
			$trNya .= "<td height=\"22\" class=\"tabelBorderBottomJust\" align=\"center\">" . $btnUpload . $btnUpdate . "</td>";
			$trNya .= "<td height=\"22\" class=\"tabelBorderBottomJust\">&nbsp;" . strtoupper($row['request_name']) . "</td>";
			$trNya .= "<td height=\"22\" class=\"tabelBorderBottomJust\" align=\"center\">" . $row['barcode'] . "</td>";
			$trNya .= "<td height=\"22\" class=\"tabelBorderBottomJust\">" . strtoupper($row['company_name']) . "</td>";
			$trNya .= "<td height=\"22\" class=\"tabelBorderBottomJust\">" . strtoupper($row['divisi']) . "</td>";
			$trNya .= "<td height=\"22\" class=\"tabelBorderBottomJust\" align=\"right\"><label style=\"float:left;\">(" . $row['currency'] . ")</label>" . $amount . "</td>";
			$trNya .= "<td height=\"22\" class=\"tabelBorderBottomJust\" align=\"center\">" . $row['mailinvno'] . "</td>";
			$trNya .= "</tr>";
		}

		return $trNya;
	}

	function getDataPaymentSplitById($idPayment) {
		$response = array();
		$response['status'] = 'success';
		$response['data'] = array();
		$duplicates = false;

		$sql = $this->koneksi->mysqlQuery("SELECT id as id_payment_split, account_name, type_dbcr, amount FROM payment_split WHERE id_payment = '".$idPayment."' AND st_delete = 0", $this->koneksi->bukaKoneksi());

		while($row = $this->koneksi->mysqlFetch($sql)) {
			$response['data'][] = array(
				"id_payment_split" => $row['id_payment_split'],
				"account_name" => $row['account_name'],
				"type_dbcr" => $row['type_dbcr'],
				"amount" => $row['amount'],
			);
		}

		$count = count($response['data']);

		if ($count > 0) {
			for ($i = 0; $i < $count; $i++) {
				for ($j = $i + 1; $j < $count; $j++) {
					if ($response['data'][$i] == $response['data'][$j]) {
						$duplicates = true;
						break 2;
					}
				}
			}
			$response['isDuplicate'] = $duplicates;
		} else {
			// Jika tidak ada data
			$response['status'] = 'error';
			$response['message'] = 'No data found';
		}

		return $response;
	}

	function getDataPaymentSplitSettlementById($idPayment) {
		$response = array();
		$response['status'] = 'success';
		$response['data'] = array();
	
		$sql = $this->koneksi->mysqlQuery("SELECT id as id_payment_split_settlement, account_name, type_dbcr, amount FROM payment_split_settlement WHERE id_payment = '".$idPayment."' AND st_delete = 0", $this->koneksi->bukaKoneksi());
	
		while ($row = $this->koneksi->mysqlFetch($sql)) {
			$response['data'][] = array(
				"id_payment_split_settlement" => $row['id_payment_split_settlement'],
				"account_name" => $row['account_name'],
				"type_dbcr" => $row['type_dbcr'],
				"amount" => $row['amount'],
			);
		}
	
		if (count($response['data']) > 0) {
			$response['isDuplicate'] = false;
		} else {
			$response['status'] = 'error';
			$response['message'] = 'No data found';
		}
	
		header('Content-Type: application/json');
		echo json_encode($response);
		exit; // Ensure no extra output
	}
	
	
	function sentMailToOut($allMail="",$subject="",$bodyNya="",$toDispNya = "",$ccNya="",$bccNya="")
	{
		require_once("./smtpclass/smtp.php");
		require_once("./smtpclass/sasl.php");

		$from="noreply@andhika.com";
			
		$to = explode(",", $allMail);
		$toDisplay = explode(",", $toDispNya);
			
		if(strlen($from)==0)
		{
			die("Please set the messages sender address in line ".$from." of the script ".basename(__FILE__)."\n");
		}
			
		$ccBcc = array();
			
		if($ccNya != "")
		{
			$ccBcc = array("Cc: ".$ccNya);
		}
			
		if($bccNya != "")
		{
			if(count($ccBcc) > 0)
			{
				array_push($ccBcc,"Bcc: ".$bccNya);
			}else{
				$ccBcc = array("Bcc: ".$bccNya);
			}				
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
		for($lan=0;$lan < count($toDisplay);$lan++)
		{
			if($viewTo == "")
			{
				$viewTo = $toDisplay[$lan];
			}else{
				$viewTo .= ",".$toDisplay[$lan];
			}
		}
		$header = array(
				"From: $from",
				"To: $viewTo",
				"Subject: $subject",
				"Date: ".strftime("%a, %d %b %Y %H:%M:%S %Z"),
				"Content-type: text/html; charset=iso-8859-1"
			);
			
		if(count($ccBcc) > 0)
		{
			for($lan=0;$lan < count($ccBcc);$lan++)
			{
				array_push($header,$ccBcc[$lan]);
			}
		}
			
		if($smtp->SendMessage(
			$from,
			$to,
			$header,
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