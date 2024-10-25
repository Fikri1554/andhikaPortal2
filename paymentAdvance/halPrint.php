<?php
require_once("configPaymentAdvance.php");

if($aksiGet == "printVoucher")
{
	$tpl = new myTemplate($tplDir."halPrintVoucher.php");
}

if($aksiGet == "printCreateFile")
{
	$tpl = new myTemplate($tplDir."halPrintCreateFile.php");
}

$tpl->prepare();

if($aksiGet == "printVoucher")
{
	$transNo = $_GET['transno'];
	$typeDoc = $_GET['typeDoc'];
	
	$tpl->Assign("transNo", $CPublic->zerofill($transNo, 6));
	
	$voucher = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	$reference = "";
	$datePaid = "";
	$grupItem = "";
	$remarkItem = "";
	$totalAmount = 0;
	$paymentMethod = "";
	$credAcc = "";
	$credAccName = "";
	$compName = "";
	$trBankCharges = "";
	$totalDB = 0;
	$totalCR = 0;
	$totalFinal = 0;
	$lblPageVoucher = "Payment Voucher";
	$typeDrCr = "CR";
	$barcodeNya = "";

	if($typeDoc == "giveSettlement")
	{
		$ttlAdvance = 0;
		$ttlExpense = 0;
		$queryGroup = $CkoneksiPaymentAdv->mysqlQuery("SELECT * FROM datapayment WHERE settlement_transno='".$transNo."' AND st_delete=0;", $CkoneksiPaymentAdv->bukaKoneksi());
		while($rowGroup = $CkoneksiPaymentAdv->mysqlFetch($queryGroup))
		{
			$compName = $rowGroup['company_name'];
			$amount = $rowGroup['settlement_amount'];
			$totalCurr = $rowGroup['currency'];
			$voucher = $rowGroup['settlement_voucher_no'];
			$reference = $rowGroup['settlement_voucher_referenceno'];
			$credAcc = $rowGroup['accountsendervendor'];
			$credAccName = $rowGroup['settlement_voucher_paidtofrom'];			
			$vesName = $rowGroup['vessel_name'];
			$vesCode = $rowGroup['vessel_code'];
			$paymentMethod = $rowGroup['settlement_voucher_method'];
			$bankCode = $rowGroup['settlement_voucher_bank'];
			$datePaid = $CPublic->convTglNonDB($rowGroup['settlement_voucher_datepaid']);
			$ttlAdvance = $ttlAdvance + $rowGroup['amount'];
			$ttlExpense = $ttlExpense + $rowGroup['settlement_amount'];

			if($rowGroup['remark'] != "")
			{
				if($remarkItem == "")
				{
					$remarkItem = "- ".$rowGroup['remark'];
				}else{
					$remarkItem .= "<br>- ".$rowGroup['remark'];
				}
			}

			$sql = $CkoneksiPaymentAdv->mysqlQuery("SELECT * FROM payment_split_settlement WHERE st_delete = '0' AND id_payment = '".$rowGroup['id']."' ", $CkoneksiPaymentAdv->bukaKoneksi());
			while($rows = $CkoneksiPaymentAdv->mysqlFetch($sql))
			{
				$deskSplit = "";

				if($rows['type_dbcr'] == "CR")
				{
					$totalCR = $totalCR + $rows['amount'];
				}else{
					$totalDB = $totalDB + $rows['amount'];
				}

				if($rows['description'] != "")
				{
					$deskSplit = " - ".$rows['description'];
				}
				
		    	$grupItem.= "<tr style=\"font-size:14px;\">
					        	<td class=\"tabelBorderLeftJust\" height=\"1\">".$rows['account_name'].$deskSplit."</td>
					            <td class=\"tabelBorderLeftJust\" align=\"center\">".$rows['vessel_code']."</td>
					            <td class=\"tabelBorderLeftJust\" align=\"center\"><span style=\"letter-spacing:5px;\">".$rows['account_code']."</span> ".$rows['type_dbcr']."</td>
					            <td class=\"tabelBorderTopBottomNull\">
					                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"font-size:15px;\">
					                    <tr>
					                        <td align=\"right\" width=\"25%\">".$rowGroup['currency']."</td>
					                        <td align=\"right\" width=\"75%\">".number_format((float)$rows['amount'], 2, '.', ',')."&nbsp;&nbsp;&nbsp;</td>
					                    </tr>
					                </table>
					            </td>
					        </tr>";
			}

			if($barcodeNya == "")
			{
				$barcodeNya = $rowGroup['barcode'];
			}else{
				$barcodeNya = ", ".$rowGroup['barcode'];
			}
		}

		$totalFinal = $totalDB - $totalCR;
		if($totalFinal < 0)
		{
			$totalFinal = $totalFinal * -1;
		}

		if($ttlAdvance > $ttlExpense)
		{
			$lblPageVoucher = "Received Voucher";
			$typeDrCr = "DB";
		}
	}else{
		$queryGroup = $CkoneksiPaymentAdv->mysqlQuery("SELECT * FROM datapayment WHERE transno='".$transNo."' AND st_delete=0;", $CkoneksiPaymentAdv->bukaKoneksi());
		while($rowGroup = $CkoneksiPaymentAdv->mysqlFetch($queryGroup))
		{
			$compName = $rowGroup['company_name'];
			$amount = $rowGroup['amount'];
			$totalCurr = $rowGroup['currency'];
			$voucher = $rowGroup['voucher_no'];
			$reference = $rowGroup['voucher_referenceno'];
			$credAcc = $rowGroup['accountsendervendor'];
			$credAccName = $rowGroup['voucher_paidtofrom'];
			
			$vesName = $rowGroup['vessel_name'];
			$vesCode = $rowGroup['vessel_code'];
			$paymentMethod = $rowGroup['voucher_method'];
			$bankCode = $rowGroup['voucher_bank'];
			$datePaid = $CPublic->convTglNonDB($rowGroup['voucher_datepaid']);

			if($rowGroup['remark'] != "")
			{
				if($remarkItem == "")
				{
					$remarkItem = "- ".$rowGroup['remark'];
				}else{
					$remarkItem .= "<br>- ".$rowGroup['remark'];
				}
			}

			$sql = $CkoneksiPaymentAdv->mysqlQuery("SELECT * FROM payment_split WHERE st_delete = '0' AND id_payment = '".$rowGroup['id']."' ", $CkoneksiPaymentAdv->bukaKoneksi());
			while($rows = $CkoneksiPaymentAdv->mysqlFetch($sql))
			{
				$deskSplit = "";

				if($rows['type_dbcr'] == "CR")
				{
					$totalCR = $totalCR + $rows['amount'];
				}else{
					$totalDB = $totalDB + $rows['amount'];
				}

				if($rows['description'] != "")
				{
					$deskSplit = " - ".$rows['description'];
				}
				
		    	$grupItem.= "<tr style=\"font-size:14px;\">
					        	<td class=\"tabelBorderLeftJust\" height=\"1\">".$rows['account_name'].$deskSplit."</td>
					            <td class=\"tabelBorderLeftJust\" align=\"center\">".$rows['vessel_code']."</td>
					            <td class=\"tabelBorderLeftJust\" align=\"center\"><span style=\"letter-spacing:5px;\">".$rows['account_code']."</span> ".$rows['type_dbcr']."</td>
					            <td class=\"tabelBorderTopBottomNull\">
					                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"font-size:15px;\">
					                    <tr>
					                        <td align=\"right\" width=\"25%\">".$rowGroup['currency']."</td>
					                        <td align=\"right\" width=\"75%\">".number_format((float)$rows['amount'], 2, '.', ',')."&nbsp;&nbsp;&nbsp;</td>
					                    </tr>
					                </table>
					            </td>
					        </tr>";
			}

			if($barcodeNya == "")
			{
				$barcodeNya = $rowGroup['barcode'];
			}else{
				$barcodeNya = ", ".$rowGroup['barcode'];
			}
		}

		$totalFinal = $totalDB - $totalCR;
		if($totalFinal < 0)
		{
			$totalFinal = $totalFinal * -1;
		}

	}

	if($voucher == "0")
	{
		$voucher = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	}

	if($reference == "0")
	{
		$reference = "";
	}

	$tpl->Assign("compName", $compName);
	$tpl->Assign("voucher",  $voucher);
	$tpl->Assign("reference", $reference);
	$tpl->Assign("datePaid", $datePaid);
	$tpl->Assign("barcodeNya", $barcodeNya);
					
	$tpl->Assign("credAccName", $credAccName);
	// $tpl->Assign("credAcc", $credAcc);
	$tpl->Assign("credAcc", "");
	
	$tpl->Assign("grupItem", $grupItem);
	$tpl->Assign("totalCurr", $totalCurr);
	$tpl->Assign("remarkItem", $remarkItem);
	$tpl->Assign("totalAmount", number_format((float)$totalFinal, 2, '.', ','));
	$tpl->Assign("paymentMethod", "By ".ucfirst($paymentMethod));
	
	$expAmount = explode(".", $totalFinal);
	$amountDepanKoma = str_replace(",","",$expAmount[0]);
	$amountBlkgKoma = $expAmount[1];
	
	$CNumberWordsInd = new CNumberWordsInd("", false);
	$nilai1 = $CNumberWordsInd->convert($amountDepanKoma);
	$nilai2 = $CNumberWordsInd->convert($amountBlkgKoma);
	
	$totalAmountWords = $nilai1;
	if($nilai2 != "nol")
	{
		$totalAmountWords = $nilai1." dan ".$nilai2." Sen";
	}

	$tpl->Assign("totalAmountWords", ucwords(strtolower( $totalAmountWords)));
	
	$tpl->Assign("bankCode", $bankCode);
	$tpl->Assign("initBank", $CPaymentAdv->detilBankSource($bankCode,'source'));
	
	$printBy = "Printed by ".ucfirst($userName)." ".date('d/m/Y')." ".substr($CPublic->jamServer(),0,5);
	$tpl->Assign("printBy", $printBy);
	$tpl->Assign("ctkLabel", "PAYMENT & ADVANCE MODULE");
	$tpl->Assign("lblPageVoucher", $lblPageVoucher);
	$tpl->Assign("typeDrCr", $typeDrCr);
}

if($aksiGet == "printCreateFile")
{
	$idPayment = $_GET['idPayment'];

	$batchNo = "";
	$reqName = "";
	$entryDate = "";	
	$compName = "";
	$divisi = "";
	$barcode = "";
	$amount = 0;
	$curr = "";
	$remark = "";
	$userIdConfirm = "";
	$dateConfirm = "";

	$query = $CkoneksiPaymentAdv->mysqlQuery("SELECT * FROM datapayment WHERE id='".$idPayment."' AND st_delete=0;", $CkoneksiPaymentAdv->bukaKoneksi());
	while($rows = $CkoneksiPaymentAdv->mysqlFetch($query))
	{
		$batchNo = $rows['batchno'];
		$reqName = $rows['request_name'];
		$entryDate = $rows['entry_date'];
		$compName = $rows['company_name'];
		$divisi = $rows['divisi'];
		$barcode = $rows['barcode'];
		$amount = $rows['amount'];
		$curr = $rows['currency'];
		$remark = $rows['remark'];
		$userIdConfirm = $rows['confirm_userId'];
		$dateConfirm = $rows['confirm_userDate'];
	}

	$tpl->Assign("batchNo", $batchNo);
	$tpl->Assign("reqName", $reqName);
	$tpl->Assign("entryDate", $CPublic->convTglNonDB($entryDate));
	$tpl->Assign("compName", $compName);
	$tpl->Assign("divisi", $divisi);
	$tpl->Assign("barcode", $barcode);
	$tpl->Assign("amount", number_format($amount,2));
	$tpl->Assign("curr", $curr);
	$tpl->Assign("remark", $remark);

	$expAmount = explode(".", $amount);
	$amountDepanKoma = str_replace(",","",$expAmount[0]);
	$amountBlkgKoma = $expAmount[1];
	
	$CNumberWordsInd = new CNumberWordsInd("", false);
	$nilai1 = $CNumberWordsInd->convert($amountDepanKoma);
	$nilai2 = $CNumberWordsInd->convert($amountBlkgKoma);
	
	$totalAmountWords = $nilai1;
	if($nilai2 != "nol")
	{
		$totalAmountWords = $nilai1." dan ".$nilai2." Sen";
	}

	$satBil = "";
	if(strtolower($curr) == "idr") { $satBil = "Rupiah"; }
	if(strtolower($curr) == "usd") { $satBil = "Dolar"; }

	 $userFullByConfirm = $CLogin->detilLogin($userIdConfirm, "userfullnm");
	 $tpl->Assign("fullNameConfirm", $userFullByConfirm);
	 $tpl->Assign("dateConfirm", $CPublic->convTglPO($dateConfirm));
	 $tpl->Assign("datePemohon", $CPublic->convTglPO($entryDate));

	$tpl->Assign("terbilang", ucwords(strtolower( $totalAmountWords))." ".$satBil);

	$printBy = "Printed by ".ucfirst($userName)." ".date('d/m/Y')." ".substr($CPublic->jamServer(),0,5);
	$tpl->Assign("printBy", $printBy);
}

$tpl->printToScreen();

$CKoneksi->tutupKoneksi($CKoneksi->bukaKoneksi());
?>